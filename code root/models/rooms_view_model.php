<?php

require_once "./main_model.php";

/**
 * Class RoomsModel: a model for the list of rooms within a block
 */
class RoomsModel extends Model
{

    /**
     * Function to get a list of everyone who is a member of the block
     * !!Admins are not included!!
     * @param int $blockID - id of current block
     * @return array - ordered array of members by community points within the block
     */
    public function getMembers(int $blockID)
    {
        return $this->query('Select User.userID, User.userName, User.communityPoints from User INNER JOIN Subscribe ON User.userID = Subscribe.userID WHERE Subscribe.blockID = ? AND Subscribe.blockRole = "Member" ORDER BY User.communityPoints  DESC ;', 'i', $blockID);
    }

    /**
     * Function to return the Admins of the given block
     * @param int $blockID - id of current block
     * @return array - array of admins of current block
     */
    public function getAdmins(int $blockID)
    {
        return $this->query('Select User.userID, User.userName from User INNER JOIN Subscribe ON User.userID = Subscribe.userID WHERE Subscribe.blockID = ? AND Subscribe.blockRole = "Admin" ORDER BY User.communityPoints;', 'i', $blockID);
    }

    /**
     * Function to check if a user is an admin of a given block
     * @param int $userID       The ID of the user you are checking
     * @param int $blockID      The ID of the room you are checking
     * @return bool             True if admin, false if not
     */
    public function isAdmin(int $userID, int $blockID)
    {
        return (bool) array_values($this->query('select exists (select * from Subscribe where userID = ? AND blockID = ? AND blockRole = ?);',"iis",$userID, $blockID,"Admin")[0])[0];
    }

    /**
     * Function to get all of the blocks details
     * (For about section of the page)
     * @param int $blockID - id of given block
     * @return array - array of blockName, description, parentHall for given block
     */
    public function getBlock(int $blockID)
    {
        return $this->query('Select blockName, description, parentHall FROM Block WHERE blockID = ?', 'i', $blockID)[0];
    }

    /**
     * Function to get a list of the rooms inside of the block
     * @param $blockID - id of block
     * @return array - array of the data of rooms within block
     */
    public function getRooms($blockID)
    {
        return $this->query('SELECT * FROM Room WHERE parentBlock = ?', 'i', $blockID);
    }

    /**
     * @param string $roomName
     * @param string $blockName
     * @param int $hallName
     * @return array|bool|false
     */
    public function getRoomID(string $roomName, string $blockName, string $hallName){
        return $this->query('SELECT roomID FROM Room WHERE title = ? AND parentBlock = 
                                    (SELECT blockID FROM Block WHERE blockName = ? AND parentHall = ?)', "sss", $roomName, $blockName, $hallName);
    }


    /**
     * Get the amount of given votes on a room
     * @param int $roomID The room you want to get the up votes for
     * @param string $type The type (ENUM('Up', 'Down')) of the vote
     * @return array            Array with the number of up votes
     * @deprecated - This is an inefficient method: there are almost always better ways please do not use this
     */
    public function getRoomVotes(int $roomID, string $type)
    {
        return rarray_values($this->query('SELECT COUNT(userID) FROM RoomVotes WHERE roomID = ? AND voteType = ?', 'is', $roomID, $type));
    }


    /**
     * Function to create a room from inside a block
     * @param string $name The name of the block you wish to create
     * @param string $contents The The Contents of the block. Your question
     * @param int $block The block in which to create the room
     * @return array
     */
    public function createRoom(string $name, string $contents, int $block, int $userID)
    {
        return $this->query("call create_room(?, ?, ?, ?)", "ssii", $name, $contents, $block, $userID);
    }

    /**
     * Function to change the description of the block
     * @param int $block    The block whose description is to be changed
     * @param string $desc  the new description
     */
    public function editBlockDesc(int $block, string $desc)
    {
        $this->query("call edit_block_description(?, ?)", "is", $block, $desc);
    }

    /**
     * Function to make somebody an admin of a block
     * @param int $user     the user to make admin
     * @param int $block    the block to make someone admin in
     */
    public function makeAdmin(int $user, int $block)
    {
        $this->query("call make_admin(?, ?)", "ii", $user, $block);
    }

    /**
     * checks if a block referred to by it's name and hall exists
     *
     * @param string $hall - the hall name the block belongs to
     * @param string $block - the block name
     * @return bool - true if the block exists
     */
    public function blockExists(string $hall, string $block) : bool
    {
        return (bool) array_values($this->query('select exists (select blockID from Block where (parentHall = ? and blockName = ?));',"ss",$hall,$block)[0])[0];
    }

    /**
     * checks if a block referred to by it's name and hall exists
     *
     * @param string $roomName
     * @param string $blockName
     * @param string $hallName
     * @return bool - true if the block exists
     */
    public function roomExists(string $roomName, string $blockName, string $hallName) : bool
    {
        return (bool) array_values($this->query('select exists (select roomID from Room where title = ? AND parentBlock = 
                                                (SELECT blockID FROM Block WHERE blockName = ? AND parentHall = ?));',"sss",$roomName, $blockName,$hallName)[0])[0];
    }

    /**
     * gets a block referred to by it's name and hall
     *
     * @param string $hall - the hall name the block belongs to
     * @param string $block - the block name
     * @return int - the id of the block
     */
    public function getBlockId(string $hall, string $block) : int
    {
        return array_values($this->query('select blockID from Block where (parentHall = ? and blockName = ?);',"ss",$hall,$block)[0])[0];
    }

    /**
     * Function to get the time of when a room was created.
     * This is so that if a room is created whilst another user is scrolling, the infinite scroll will not break
     * @param int $blockID  The parent block ID
     * @param int $ts       The timestamp
     * @param int $dex      The offset of the room creation
     * @param int $lim      The title limit
     * @return array|false  Arrry (possibly empty) on success, false if error occurs.
     */
    public function getRoomFromStampAtIndex(int $blockID,int $ts, int $dex, int $lim){
        return $this->query('select roomID, title, content, UNIX_TIMESTAMP(timestamp), parentBlock, User.userName from Room INNER JOIN User on Room.creator = User.userID  where parentBlock = ? and unix_timestamp(timestamp)<=? order by timestamp desc, title limit ? offset ?;
','iiii', $blockID, $ts, $lim ,$dex);
    }


    /**
     * @param int $userID
     * @param int $blockID
     * @return bool
     */
    public function subscriptionStatus(int $userID, int $blockID): bool
    {
        return (bool) rarray_values($this->query('SELECT EXISTS (SELECT blockRole FROM Subscribe WHERE userID = ? AND blockID = ?);',"ii",$userID,$blockID))[0][0];
    }
  
    /** Gets the rooms a user has voted on.
     * @param int $userID User ID to get the rooms for
     * @return array|false Returns an array of the rooms a user is subscribed to or false if there is an error.
     */
    public function getUserRoomVotes(int $userID){
        return $this->query('SELECT roomID, voteType FROM RoomVotes WHERE userID = ? ;','i', $userID);
    }

    public function getRoomsVotesInBlock(int $blockid){
        return $this->query("
Select roomID, sum(case voteType when 'Up' then roomVotes else -roomVotes end) as votes 
from (
    SELECT r.roomID, voteType, count(rv.roomID) as roomVotes 
	FROM RoomVotes rv
	Join Room r on r.roomID = rv.roomID
	WHERE parentBlock = ? group by r.roomID,voteType)
v1 group by roomID;",'i',$blockid);
    }

    /**
     * @param int $userID User ID to check for subscription for
     * @param int $blockID Block ID to check subscription for
     * @return bool
     */
    public function isMember(int $userID, int $blockID): bool
    {
        return (bool) array_values($this->query('SELECT exists (SELECT userID FROM Subscribe WHERE userID = ? AND blockRole = "Member" AND blockID = ?);', 'ii', $userID, $blockID)[0])[0];
    }

    /**
     * Get user id from user name.
     *
     * @param string $username - The username to get id for.
     * @return int|false - The user id.
     */
    public function getUserID(string $username) : int{
        return (int) array_values(array_values($this->query('select userID from User where userName = ?','s',$username))[0])[0];
    }
}
