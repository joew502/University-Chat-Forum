<?php

require_once "./main_model.php";

/**
 * Class InnerRoomModel: a model for the inside of a room, displaying comments
 */
class InnerRoomModel extends Model
{

    /**
     * Function to get all of the room details once inside of the room
     * @param int $roomID - id of room
     * @return array - array of all data for given room
     * ^^^ This is an array of [roomID, roomName, room description, and parent hall]
     */
    public function getRoom(int $roomID)
    {
        return rarray_values($this->query('SELECT * FROM Room WHERE roomID = ?', 'i', $roomID))[0];
    }

    /**
     * Function to get username from userID
     * @param int $userID - The user id to get the username for
     * @return mixed        The username for the user - false if error occurs
     */
    public function getUsername(int $userID){
        return rarray_values($this->query('SELECT userName FROM User WHERE userID = ?', 'i', $userID)[0])[0];
    }

    /**
     * checks if a block referred to by it's name and hall exists
     *
     * @param string $roomName      The rooms name
     * @param string $blockName     The name of the block that the room is in
     * @param string $hallName      The hall name that the room is in
     * @return bool - true if the block exists
     */
    public function roomExists(string $roomName, string $blockName, string $hallName) : bool
    {
        return (bool) array_values($this->query('select exists (select roomID from Room where title = ? AND parentBlock = 
                                                (SELECT blockID FROM Block WHERE blockName = ? AND parentHall = ?));',"sss",$roomName, $blockName,$hallName)[0])[0];
    }

    /**
     * Function to get the timestamp of when a given room was created
     * @param int $roomID   The id of the room whose timestamp you want
     * @return mixed        timestamp for the rooms creation
     */
    public function getRoomTime(int $roomID){
        return array_values($this->query('Select UNIX_TIMESTAMP(timeStamp) from Room where roomID = ?', "i", $roomID)[0])[0];
    }

    /**
     * Get a rooms ID via the names of the hall, block, and room
     * @param string $hall      The hall name
     * @param string $block     The block name
     * @param string $room      The room name
     * @return int              The room ID
     */
    public function getRoomID(string $hall, string $block, string $room)
    {
        return array_values($this->query("SELECT roomID FROM Room WHERE title = ? and parentBlock = 
                                            (SELECT blockID FROM Block WHERE blockName = ? and parentHall = ?)", "sss", $room, $block, $hall)[0])[0];
    }

    /**
     * Function to get all of the comments for the room
     * @param int $roomID - id of room
     * @return array - returns array of all comments' text with the userID and username of the poster
     */
    public function getComments(int $roomID)
    {
        return $this->query(
            'SELECT commentID, User.userName, commentText, UNIX_TIMESTAMP(timeStamp), parent 
            FROM Comments INNER JOIN User on Comments.userID = User.userID WHERE roomID = ?;', 'i', $roomID);
    }

    /**
     * Function to get the amount of votes for each comment in a given room.
     *
     * This SQL works by first getting a table of all the comments. This is then joined to another table with
     * all of the votes for said comments. From this combined table the comment ID and two copies of the votetype and voter UID is selected.
     * This is then returned to the outer select. The outer select then groups the comments by their commentID with the combined function of count.
     * This is performed upon votes of the selected type
     *
     * @param int $roomID           The ID of the room that you wish get the comment votes for
     * @return array|bool|false     Returns a boolean of false if fails. Otherwise returns a list of comments IDs and amount of their votes
     */
    public function getCommentVotes(int $roomID){
        return $this->query("
SELECT v1.commentID, count(case v1.c1uvt when 'Up' then 1 else null end) - count(case v1.c1uvt when 'Down' then 1 else null end) votes from 
(SELECT r.commentID, c1.userID as c1uid, c1.voteType as c1uvt, c2.userID as c2uid, c2.voteType as c2uvt FROM 
    (
    Comments r
    join CommentVotes c1 on r.commentID = c1.commentID
    join CommentVotes c2 on r.commentID = c2.commentID and c1.voteType = c2.voteType and c1.userID = c2.userID
    )
WHERE roomID = ? Group by r.commentID,c1.userID) v1
Group by commentID;
",'i',$roomID);
    }

    /**
     * To vote 'Up' on a room
     * @param int $roomID           The room you want to vote on
     * @param int $userID           The user who is voting
     */
    public function upRoomVote(int $roomID, int $userID)
    {
        $this->query("call Room_vote(?, ?, 'Up')","ii",$userID,$roomID);
    }

    /**
     * To vote 'Up' on a comment
     * @param int $commentID           The room you want to vote on
     * @param int $userID           The user who is voting
     */
    public function upCommentVote(int $commentID, int $userID)
    {
        $this->query("call comment_vote(?, ?, 'Up')","ii",$userID,$commentID);
    }

    /**
     * To vote 'Down' on a room
     * @param int $roomID   The room you want ot vote on
     * @param int $userID   The person who is voting
     */
    public function downRoomVote(int $roomID, int $userID)
    {
        $this->query("call Room_vote(?, ?, 'Down')","ii",$userID,$roomID);
    }

    /**
     * To vote 'Down' on a comment
     * @param int $commentID   The room you want ot vote on
     * @param int $userID   The person who is voting
     */
    public function downCommentVote(int $commentID, int $userID)
    {
        $this->query("call comment_vote(?, ?, 'Down')","ii",$userID,$commentID);
    }

    /**
     * function to see if a comment already exists or not
     * @param $commentID    The ID of the comment you wish to check for
     * @return bool         Boolean true of false as to whether the comment exists or not
     */
    public function commentExists($commentID){
        return (bool) array_values($this->query('select exists (select commentID from Comments where commentID = ?);',"i",$commentID)[0])[0];

    }

    /**
     * Function to add a comment to a room
     * @param int $roomID The room you want to comment on
     * @param int $userID The user who is adding the comment
     * @param string $comment The contents of the comment
     * @param int $parent   The block the room you are commenting in belongs to
     */
    public function comment(int $roomID, int $userID, string $comment, int $parent)
    {
        if($parent === 0){
            $this->query("call comment(?, ?, ?, ?)","iisi",$userID, $roomID, $comment, null);
        } else {
            $this->query("call comment(?, ?, ?, ?)","iisi",$userID, $roomID, $comment, $parent);
        }

    }

    /**
     * Function to edit a current comment
     * @param int $comment  The ID of the comment that you wish to change
     * @param string $text  The new contents you would like the comment to display
     */
    public function edit_comment(int $comment, string $text)
    {
        $this->query("call edit_comment(?, ?)", "is", $comment, $text);
    }

    /**
     * Function to edit a room
     * @param int $roomID  The ID of the room that you wish to change
     * @param string $text  The new contents you would like the room to display
     * @param int $userID The $userID of the user making the change
     */
    public function edit_room(int $roomID, string $text, int $userID)
    {
        $this->query("UPDATE Room SET content = ? WHERE roomID = ? AND creator = ?;", "sii", $text, $roomID, $userID);
    }

    /**
     * Function to update the vote of a user on a room
     * @param int $userID - ID of the user whose vote is changing
     * @param int $roomID - ID of the room being voted on
     * @param string $upDown - The Enum up/down value to change vote to
     */
    public function updateRoomVote(int $userID, int $roomID, string $upDown){

        $this->query('call update_room_vote(?,?,?)', "iis", $userID, $roomID, $upDown);
    }

    /**
     * Function to update the vote of a user on a comment
     * @param int $userID - ID of the user whose vote is changing
     * @param int $commentID - ID of the comment being voted on
     * @param string $upDown - The Enum up/down value to change vote to
     */
    public function updateCommentVote(int $userID, int $commentID, string $upDown){

        $this->query('call update_comment_vote(?,?,?)', "iis", $userID, $commentID, $upDown);
    }

    /**
     * Check if there is already a vote so that you know if you need to update or insert
     * @param int $userID       The ID of the user who voted
     * @param int $commentID    The comment they voted on
     * @return string the type of vote the user has made
     */
    public function hasCommentVoted(int $userID, int $commentID){
        return (string) array_values($this->query('select voteType from (select * from CommentVotes where commentID = ? AND userID = ?) AS commentvotes;',"ii",$commentID, $userID)[0])[0];
    }

    /**
     * Check if there is already a vote so that you know if you need to update or insert
     * @param int $userID       The ID of the user who voted
     * @param int $roomID    The room they voted on
     * @return string the type of vote the user has made
     */
    public function getRoomVoteType(int $userID, int $roomID): string
    {
        return (string) array_values($this->query('select voteType from (select * from RoomVotes where roomID = ? AND userID = ?) AS roomvotes;',"ii",$roomID, $userID)[0])[0];
    }

    /**
     * Delete a comment vote from the database
     * @param int $userID The ID of the user who voted
     * @param int $commentID The comment they voted on
     */
    public function deleteCommentVote(int $userID, int $commentID){
        $this->query('delete from CommentVotes where commentID = ? AND userID = ? ;', 'ii', $commentID, $userID);
    }

    /**
     * Delete a comment vote from the database
     * @param int $userID The ID of the user who voted
     * @param int $commentID The comment they voted on
     */
    public function deleteRoomVote(int $userID, int $roomID){
        $this->query('delete from RoomVotes where roomID = ? AND userID = ? ;', 'ii', $roomID, $userID);
    }

    /**
     * Check if there is already a vote so that you know if you need to update or insert
     * @param int $userID       The ID of the user who voted
     * @param int $roomID       The room they voted on
     * @return array the type of vote the user has made in a 2d array or an empty array otherwise
     */
    public function hasRoomVoted(int $userID, int $roomID): array
    {
        return $this->query('select voteType from RoomVotes where roomID = ? AND userID = ?;',"ii",$roomID, $userID);
    }

    /**
     * Function to get the total amount of votes on a given room
     *
     * This works by adding the votes (where Up = +1 and down = -1) from another table made
     * where the roomID, vote type and amount of times a room is votes on is displayed
     * @param int $roomID   The room you wish to get the votes for
     * @return int          The amount of votes on the room
     */
    public function getRoomVotes(int $roomID): int
    {
        return (int) array_values($this->query("
Select sum(case voteType when 'Up' then roomVotes else -roomVotes end) as votes 
from (
    SELECT roomID,voteType,count(roomID) as roomVotes 
    FROM RoomVotes 
    WHERE roomID = ? group by voteType) 
v1 group by roomID;
",'i',$roomID)[0])[0];
    }

    /**
     * Function to get the commentID and vote type of all of the comments votes that a user has made
     * @param int $userID UserID to get comments for
     * @return array|false array of comments that user has voted on or false if none are found.
     */
    public function getUserCommentVotes(int $userID){
        return $this->query('SELECT commentID, voteType FROM CommentVotes WHERE userID = ?;', 'i', $userID);
    }
}
