<?php

require_once "./main_model.php";

/**
 * Class HallsModel: a model for the landing page with halls and user feed
 */
class HallsModel extends Model {

    /**
     * Updates a blocks description
     * @param string $blockID   Block ID to update
     * @param string $desc      Description to update to
     */
    public function  editBlockDesc(string $blockID, string $desc){
        $this->query('UPDATE Block SET description = ? WHERE blockID = ?', 'si', $desc, $blockID);
    }

    /**
     * Function to get a blocks ID through the block name and hall name
     * @param string $block     The blockname
     * @param string $hall      The hallname
     * @return int              This is blocks ID
     */
    public function getBlockID(string $block, string $hall)
    {
        return $this->query('SELECT blockID FROM Block WHERE blockName=? AND parentHall=?', 'ss', $block, $hall)[0];
    }

    /**
     * This is a multi query function to allow a user to create a block
     * This function will automatically subscribe the user as well as make them an admin of that block
     * @param string $blockName     The name of the block that they wish to create
     * @param string $description   The description of the block that they wish to create
     * @param string $hallName      The name of the hall that they wish to create
     * @param int $userID           The ID of the user who wants to create this block
     * @return boolean              True or false as to if this process was successful or not
     */
    public function createBlock(string $blockName, string $description, string $hallName, int $userID){
        $statements = [ ["call create_block(?, ?, ?)", "sss", $blockName, $description, $hallName],
                        ["SELECT blockID FROM Block WHERE blockName=? AND parentHall=?", "ss", $blockName, $hallName],
                        ["call subscribe(?, ?)", "ii", $userID, "SET-BY-RES"],
                        ["call make_admin(?, ?)", "ii", $userID, "SET-BY-RES"]];
        $useresin = ["2,3"=>"1,0,0", "3,3"=>"1,0,0"];
        return $this->multiquery($statements, $useresin);
    }

    /**
     * Function to make a user an admin of a block
     * @param int $userID   The user who is to becoome an admin
     * @param int $blockID  The block they are to become and admin in
     */
    public function promote(int $userID, int $blockID){
        $this->query("call make_admin(?, ?)", "ii", $userID, $blockID);
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

}