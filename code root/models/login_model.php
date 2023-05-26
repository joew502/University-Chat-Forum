<?php

require_once "./main_model.php";

/**
 * Class LoginModel: a model for managing user login
 */
class LoginModel extends Model
{

    /**
     * Function to get user passwordhash
     * @param int $userID - The ID of the user whose account is logged on
     * @return string - The passwordhash
     */
    public function getUserPasswordHash(int $userID)
    {
        return (string) array_values(array_values($this->query('SELECT passwordHash FROM User WHERE userID = ?;', 'i', $userID))[0])[0];
    }

    /**
     * Function to change a users password.
     *
     * ---------------WARNING---------------
     * THIS SHOULD ALREADY BE IN HASH FORM
     * DO NOT PASS THROUGH PLAIN TEXT!!!
     * -------------------------------------
     *
     * @param int $userID           The ID of the user who is updateing their hashed password
     * @param string $hash          The hash of their new password
     * @return array|bool|false     returns true is successful, false if error
     */
    public function setUserPasswordHash(int $userID, string $hash)
    {
        return $this->query('UPDATE User SET passwordHash = ? WHERE userID = ?;', 'si', $hash, $userID);
    }

    /**
     * Get user id from user name
     *
     * @param string $username - the username to get id for
     * @return int|false         The ID of the user, false if error
     */
    public function getUserID(string $username){
        return (int) array_values(array_values($this->query('select userID from User where userName = ?','s',$username))[0])[0];
    }

    /**
     * Check that a username already exists in the database or not
     * @param string $username  The username you wish to check for in the database
     * @return bool             True if exists, false if not
     */
    public function userNameExists(string $username)
    {
        return (bool) array_values($this->query('select exists (select userID from User where userName = ?);',"s",$username)[0])[0];
    }

    /**
     * Function to get user information
     * This gets the users name, global role and activation status
     * @param int $userID   The ID of the user that you wish to get information for
     * @return array        Array of the users information [userName,globalRole,activationStatus]
     */
    public function getUserData(int $userID){
        return array_values(array_values($this->query('select userName,globalRole,activationStatus from User where userID = ?','i',$userID))[0]);
    }

    /**
     * Function to check if a user is active or not
     * @param int $userID   ID of the user whose activation status you wish to receive
     * @return bool         True if active, false if not
     */
    public function isUserActive(int $userID){
        return ("Active" === array_values(array_values($this->query('select activationStatus from User where userID = ?','i',$userID))[0])[0]);
    }
}