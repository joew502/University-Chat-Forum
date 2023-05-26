<?php

require_once "./main_model.php";

/**
 * Class LoginModel: a model for managing user login
 */
class ComPointsModel extends Model
{
    /**
     * Get user id from user name.
     *
     * @param string $username - The username to get id for.
     * @return int|false - The user id.
     */
    public function getUserID(string $username) : int{
        return (int) array_values(array_values($this->query('select userID from User where userName = ?','s',$username))[0])[0];
    }

    /**
     * function to check if a username already exists or not
     * @param string $username  The username you wish to check for
     * @return bool                true if exist, false if not
     */
    public function userNameExists(string $username) : bool{
        return (bool) array_values($this->query('select exists (select userID from User where userName = ?);',"s",$username)[0])[0];
    }

    /**
     * Get the timestamp for when the user last received points
     * @param int $uid      The user you wish to check for
     * @return int          The timestamp of when they last got points
     */
    public function getLastGotPoints(int $uid) : int{
        return (int) array_values(array_values($this->query('select UNIX_TIMESTAMP(lastGotPoints) from User where userID = ?','i',$uid))[0])[0];
    }

    /**
     * Function to get the amount of community points that a user has
     * @param int $uid  The ID of the user you wish to get the community points for
     * @return int      The amount of community points the given user has
     */
    public function getComPointTotal(int $uid) : int{
        return (int) array_values(array_values($this->query('select communityPoints from User where userID = ?','i',$uid))[0])[0];
    }

    /**
     * Multi query to give a user new community points and to also update the timestamp that points to when the user last got points
     * @param int $uid              The user who is gaining the community points
     * @return array|bool|false     True if successful, false if not
     */
    public function incrementComPoints(int $uid){
        return $this->multiquery([
            ["UPDATE User SET communityPoints = communityPoints + 1 WHERE userID = ?;","i",$uid],
            ["UPDATE User SET lastGotPoints = CURRENT_TIMESTAMP WHERE userID = ?;","i",$uid]
        ]);
    }


    /**
     * Function to allow a user to send points to another user
     * @param string $uid_send      The ID of the user who is sending points
     * @param string $uid_receive   The ID of the user who is receiving points
     * @param int $amount           The amount of points that they are transferring
     * @return array|bool|false     True if success, false if not
     */
    public function transferPoints(string $uid_send, string $uid_receive, int $amount){
        return $this->multiquery([
            ["UPDATE User SET communityPoints = communityPoints - ? WHERE userID = ?;","ii",$amount,$uid_send],
            ["UPDATE User SET communityPoints = communityPoints + ? WHERE userID = ?;","ii",$amount,$uid_receive]
        ]);
    }
}