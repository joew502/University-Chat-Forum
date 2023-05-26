<?php

require_once "./main_model.php";

/**
 * Class BlocksModel: a model for the list of blocks within a hall
 */
class BlocksModel extends Model
{

    /**
     * Function for getting the description of the hall you are inside
     * @param string $hallName - name of current hall
     * @return array - returns the description of current hall
     */
    public function getHallDesc(string $hallName)
    {
        return array_values($this->query('Select description from Hall where HallName = ?;', 's', $hallName)[0])[0];
    }

    /**
     * Function to get the blocks from inside the hall.
     * This is all halls in block. No relation to subscribe
     * @param string $hallName - the name of the current hall
     * @return array - returns array of blockID, blockName, description of all blocks within given hall
     */
    public function getBlocks(string $hallName)
    {
        # returns details of all blocks within hall
        return $this->query('Select blockID, blockName, description from Block where parentHall = ?;', 's', $hallName);
    }

    /**
     * Private function to get list of blocks that the user has subscribed to
     * This is to be passed to getSubsInHall as it return user subscriptions from all halls
     * @param int $userID - ID of given user
     * @return array - returns array with blockID, blockName, description of all blocks the user is subscribed to
     */
    private function getSubs(int $userID)
    {
        # gets all block id of subsciptions of given user
        return $this->query('Select blockID, blockName, description from Block where blockID = (SELECT blockID from Subscribe where userID = ?);', 'i', $userID);
    }

    /**
     * Function that returns all blocks in the hall that the user has subscribed to
     * @param int $userID - the ID of the given user
     * @param string $hallName - the name of the hall user is in
     * @return array - returns array with blockID, blockName, description, memberCount for all blocks the user is subscribed to in the current hall
     */
    public function getSubsInHall(int $userID, string $hallName){
        return array_intersect(getSubs($userID), getBlocks($hallName));
    }

    public function getBlockID(string $blockName, string $hallName){
        return array_values($this->query("SELECT blockID FROM Block WHERE blockName = ? AND parentHall = ?", "ss", $blockName, $hallName)[0])[0];
    }

    /**
     * checks if a block referred to by it's name and hall exists
     *
     * @param string $blockName
     * @param string $hallName
     * @return bool - true if the block exists
     */
    public function blockExists(string $blockName, string $hallName) : bool
    {
        return (bool) array_values($this->query('select exists (select blockID from Block where blockName = ? AND parentHall = ?);',"ss",$blockName, $hallName)[0])[0];
    }

    /**
     * Function to subscribe user to a block
     * @param int $userID - ID of user to subscribe
     * @param int $blockID - ID of block to be subscribed to
     */
    public function subscribeBlock(int $userID, int $blockID){
        $this->query('call subscribe(?,?)', "ii", $userID, $blockID);
    }

    public function unsubscribeBlock(int $userID, int $blockID){
        $this->query('DELETE FROM Subscribe WHERE userID = ? AND blockID = ?;', "ii", $userID, $blockID);
    }



    public function getBlockFromStampAtIndex(string $hallName,int $blid, int $dex, int $lim){
        return $this->query('select blockID, blockName, description from Block where parentHall = ? and blockID<=? order by BlockID desc limit ? offset ?;','siii', $hallName, $blid, $lim ,$dex);
    }

    public function getMxBlid(){
        return (int) ($this->unbound_query('select max(BlockID) from Block;')[0]);
    }

    /**
     * @param int $userID User ID to check if admin for
     * @param int $blockID Block ID to check if the user as an admin for
     * @return bool True if user is amdin, false if not
     */
    public function isAdmin(int $userID, int $blockID)
    {
        return (bool) array_values($this->query('select exists (select * from Subscribe where userID = ? AND blockID = ? AND blockRole = "Admin");',"ii",$userID, $blockID)[0])[0];
    }
}


