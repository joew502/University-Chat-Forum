<?php

require_once "./main_model.php";

/**
 * Class NavbarModel: a model for the navbar
 */
class NavbarModel extends Model
{

    /**
     * Function to get user points so that level/exeP can be derived
     * @param int $userID - The ID of the user whose account is logged on
     * @return array      - Return array of with three points, each for the different style of points
     */
    public function getPoints(int $userID)
    {
        return $this->query('SELECT academicPoints, comminutyPoints, metaPoints from User where userID = ?;', 'i', $userID);
    }
}
