<?php

require_once "./main_model.php";

/**
 * Class RegModel: a model for managing user registration
 */
class RegModel extends Model
{

    /**
     * Function to check if a username already exists or not
     * @param string $username  The user name you are checking for
     * @return bool             True if exists, false if not
     */
    public function userNameExists(string $username)
    {
        return (bool) array_values($this->query('select exists (select userID from User where userName = ?);',"s",$username)[0])[0];
    }

    /**
     * Function to get user ID through the users name
     * @param string $uname The users name
     * @return int          The users ID
     */
    public function uidFromName(string $uname){
        return (int) array_values($this->query('select userID from User where userName = ?','s',$uname)[0])[0];
    }

    /**
     * Function to create a new user
     * @param string $userName  the users name
     * @param string $password  the HASHED password
     * @param string $email     the users email
     * @param string $role      ENUM('Student', 'Lecturer')
     */
    public function add_new_user(string $userName, string $password, string $email, string $role){
        $this->query("call create_user(?, ?, ?, ?)", "ssss", $role, $userName, $password, $email);
    }

    /**
     * Checks if an email exists
     *
     * @param string $email     The email to check for
     * @return bool             True if exists, false if not
     */
    public function emailExists(string $email){
        return (bool) array_values($this->query('select exists (select userID from User where email = ?);',"s",$email)[0])[0];
    }
}