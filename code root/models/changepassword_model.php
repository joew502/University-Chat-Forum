<?php

require_once "./main_model.php";

/**
 * Class CPModel abbreviated from community points model
 */
class CPModel extends Model {

    /**
     * Function to see if a user exists based off of their userID
     * @param int $userID   The userID to check for in the database
     * @return mixed        Boolean of if the user exists or not
     */
    public function userExists(int $userID)
    {
        return $this->query('SELECT userID FROM User WHERE userID = ?;',"i", $userID)[0];
    }
}