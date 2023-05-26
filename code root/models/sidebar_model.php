<?php

require_once "./main_model.php";

/**
 * Class SidebarModel: a model for the data that will be displayed in the sidebar
 */
class SidebarModel extends Model
{
    /**
     * Get a list of all the blocks to which the user is subscribed to
     * @param int $userID the ID of user who is logged in
     * @return array - of [blockID, blockName, description, parentHall]
     */
    public function getBlocks(int $userID)
    {
        return $this->query('SELECT blockName, parentHall FROM Block WHERE blockID IN (SELECT blockID FROM Subscribe WHERE userID = ?);', 'i', $userID);
    }
}