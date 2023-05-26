<?php

require_once "./main_model.php";

/**
 * Class AccountModel: a model for users account page
 * This class is used to interact with the database
 */
class AccountModel extends Model
{

    /**
     * Function to get user data
     * @param int $userID - The ID of the user whose account is logged on
     * @return array of [userName, globalRole, email, metaPoints, communityPoints, academicPoints]
     */
    public function getUser(int $userID)
    {
        return $this->query('SELECT userName, globalRole, email, metaPoints, communityPoints, academicPoints FROM User WHERE userID = ?;', 'i', $userID);
    }

    /**
     * function to check to see if a userID correlates to a user in the database
     * @param int $userID   The id you wish to check for in the database
     * @return boolean      The return value, true or false as to if the user exists or not
     */
    public function userExists(int $userID)
    {
        return array_values($this->query('SELECT EXISTS (SELECT userID FROM User WHERE userID = ?);',"i", $userID)[0])[0];
    }

    /**
     * Check to see if a username is already used up or not, so as to prevent duplicate usernames
     * @param string $username      The username you wish to check does not already exist
     * @return boolean              True (The username already exists) | False (The username is not already take)
     */
    public function userNameExists(string $username)
    {
        return $this->query('SELECT userName FROM User WHERE userName = ?;',"s", $username);
    }

    /**
     * Function to get user points so that level/exeP can be derived
     * @param int $userID - The ID of the user whose account is logged on
     * @return array      - Return array of with three points, each for the different style of points
     */
    public function getPoints(int $userID)
    {
        return array_values($this->query(
            'SELECT academicPoints, communityPoints, metaPoints from User where userID = ?;', 'i',
             $userID)[0]);
    }

    /**
     * Get the amount of up-votes on rooms that a user has created
     * @param int $userID   The user you are getting the up-votes for
     * @return int          The number of up votes for rooms the given user was the creator for
     */
    public function getRoomVotes(int $userID){
        return array_values($this->query('SELECT count(*) FROM RoomVotes WHERE voteType = ? and roomID IN (SELECT roomID FROM Room WHERE creator = ?);', "si", "Up", $userID)[0])[0];
    }

    /**
     * Get the amount of up-votes on comments that a user has created
     * @param int $userID   The user you are getting the up votes for
     * @return int        The number of up votes for comments the given user made
     */
    public function getCommentVotes(int $userID){
        return array_values($this->query('SELECT count(*) FROM CommentVotes WHERE voteType = ? and commentID IN (SELECT commentID FROM Comments WHERE userID = ?);', "si", "Up", $userID)[0])[0];
    }
}
