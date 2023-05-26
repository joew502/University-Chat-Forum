<?php

#Summon database creds,  note must be fully qualified name due to accessing a restricted directory
require_once "/home/xemcyq1zrauf/sec_groupm/db_creds.php";
require_once __DIR__."/utils/rarray_values.php";

/**
 * The main model for extending all other models.
 *
 * Provides an abstraction of the entire secure database connection process with the key methods of:
 *      query: to contact the database
 *      isError: to detect error state
 *      getError: to get error details to pass to trigger_error (NOT TO THE USER)
 * It also provides some minor rarely used methods of:
 *      addError: to add an additional line of error info to the error stack
 *      nullError: to flag an error as recovered and allow continued database operation
 */
class Model
{
    ##Attributes
    private $connection;
    private $error = false;
    private $error_stack = "";

    ##Error functions

    /**
     * returns if the model is in an error state.
     *
     * @return bool
     */
    public function isError(){
        return $this->error;
    }

    /**
     * DO NOT RETURN THIS INFO TO THE USER - SQL EXPLOIT VULNERABILITY MORE LIKELY IF PUBLIC.
     * instead call:
     *      trigger_error( $model->getError() , E_USER_ERROR )
     * so it can be used for debug purposes.
     *
     * @return string - the errors the model/DB has experienced
     */
    public function getError(){
        return $this->error_stack;
    }

    /**
     * Flag an error as having occurred and add it to the error stack
     *
     * @param $err - The error message to add tot he stack
     */
    protected function addError($err){
        $this->error = true;
        $this->error_stack .= $err."<br>";
    }


    /**
     * Mark the model as in a recovered state rather than an error one
     *
     * (useful for optional database query's where a failure still allows the code to continue querying other aspects of the database)
     */
    public function nullError(){
        $this->error = false;
        $this->error_stack = "...";
    }

    ##Conneciton functions

    /**
     * Model constructor.  Set up the DB connection.
     */
    public function __construct(){
        $this->connection = new mysqli(DB_host,DB_username,DB_password,DB_database);
        if($this->connection->connect_error){
            $this->addError("Connection Failed: ".$this->connection->connect_error);
        }
    }

    /**
     * Model deconstructer. Tear down the DB connection.
     */
    public function __destruct(){
        $this->connection->close();
    }

    ##request wrapper functions
    //https://stackoverflow.com/questions/10752815/mysqli-get-result-alternative
    /**
     * Parses the results of a sucessful DB query
     *
     * @param $Statement - a Statement object to parse
     * @return array - A 2d array containing the the returned data
     */
    private function get_result($Statement) {
        $RESULT = array();
        $Statement->store_result();
        for ( $i = 0; $i < $Statement->num_rows; $i++ ) {
            $Metadata = $Statement->result_metadata();
            $PARAMS = array();
            while ( $Field = $Metadata->fetch_field() ) {
                $PARAMS[] = &$RESULT[ $i ][ $Field->name ];
            }
            call_user_func_array( array( $Statement, 'bind_result' ), $PARAMS );
            $Statement->fetch();
        }
        return $RESULT;
    }

    /**
     * Method for querying the database with no set parameters
     *
     * @param string $query - the query to be sent to the DB
     * @return array|false - False if an error was encountered,  a 2d array containing any results otherwise
     */
    public function unbound_query(string $query){
        $res = $this->connection->query($query);

        if($res === false){
            $this->addError($this->connection->error);
        } else {
            $dem_arr = array();
            while ($row = $res->fetch_row()){
                array_push($dem_arr,$row[0]);
            }
            return $dem_arr;
        }

        $this->addError("Error occurred in the execution of $query with unbound params");
        return False;
    }

    /**
     * The main method for querying the database.
     *
     * @param string $query - the prepared statement to be sent to the DB
     * @param string $typestring - a string cont a character per parameter with the char representing the type (s = string, i = int, d = double, b = BLOB)
     * @param mixed ...$query_params - the parameters to pass into the prepared statement
     * @return array|false - False if an error was encountered,  a 2d array containing any results otherwise
     */
    public function query(string $query,string $typestring,...$query_params){
        $stmt = $this->connection->prepare($query);
        if(False === $stmt){
            $this->addError($this->connection->error);
        } elseif(False === $stmt->bind_param($typestring,...$query_params)){
            $this->addError($stmt->error);
        } elseif(False === $stmt->execute()){
            $this->addError($stmt->error);
        } else {
            $results = $this->get_result($stmt);
            $stmt->close();

            return $results;
        }

        $this->addError("Error occurred in the execution of $query with params: ".implode(", ",$query_params));
        if($stmt !== False){
            $stmt->close();
        }

        return False;
    }

    /**
     * A transaction safe way of calling multiple dependent query's, also supports passing data from return values as a parameter.
     *
     * Setting up a dataflow:
     *
     * sometimes one query in a multiquery will depend on a previous one whose
     * result cannot be known until the it is ran.  In such a case the $use_res
     * parameter defines how the data should be passed.
     *
     * The $use_res is an array whos values follow he following structure:
     *   ["?1?,?2?" => "?3?,?4?,?5?", ...]
     * each element of the array contains 5 arguments.
     *  -The first 2 are for specifying the parameter to overwrite with ?1? being the statement
     *   and ?2? being the parameter in that statement.
     *  -The next 3 specify the result to use with ?3? specifying the statement and ?4? and ?5?
     *   being the row and column to read from.
     *
     * An example can be found at {@link ../testing_root/tests/models/main_model_multi_query.php}
     *
     * @param array $statements - A array of arrays where the inner arrays are the params
     *                            that would be passed to the normal query command
     * @param array $use_res    - If a secondary query depends on the result of a previous one this parameter
     *                            can be used to define the data flow (See above for details)
     * @return array|false      - An array containing the the results of each query in turn or
     *                            false if an error was encountered
     */
    public function multiquery(array $statements,array $use_res = []){
        $this->connection->autocommit(false);
        $ress = [];
        for($i = 0;$i<count($statements);$i++){
            $statement = $statements[$i];
            for($j = 0;$j<count($statement);$j++){
                if(isset($use_res["$i,$j"])){
                    list($rcon,$rcol,$rrow) = explode(",",$use_res["$i,$j"]);
                    $statement[$j] = $ress[$rcon][$rcol][$rrow];
                }
            }
            $res = call_user_func_array(array($this, 'query'), $statement);
            array_push($ress,rarray_values($res));
            if($this->isError()){
                $this->connection->rollback();
                $this->connection->autocommit(true);

                ob_start();
                var_dump($statement);
                $errval = ob_get_clean();
                ob_start();
                var_dump($statements);
                $errseq = ob_get_clean();

                $this->addError("Error occurred in multi-query while attempting to execute: ($errval) in statement sequence ($errseq)");
                return false;
            }
        }
        $this->connection->commit();
        $this->connection->autocommit(true);

        return $ress;
    }
}