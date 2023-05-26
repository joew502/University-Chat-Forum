DELIMITER $$
CREATE DEFINER=`groupm`@`144.173.%.%` PROCEDURE `comment`(IN user INT, IN room INT, IN text mediumtext, IN parentCom INT)
BEGIN
	INSERT INTO `Comments` (userID, roomID, commentText, timeStamp, parent)
    VALUES (user, room, text, NOW(), parentCom);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`groupm`@`144.173.%.%` PROCEDURE `comment_vote`(IN user INT, IN comment INT, IN type ENUM('Up', 'Down'))
BEGIN
	INSERT INTO `CommentVotes`
    VALUES(user, comment, type);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`groupm`@`144.173.%.%` PROCEDURE `create_block`(IN block varchar(45), IN descript mediumtext, IN hall varchar(45))
BEGIN
	INSERT INTO Block(blockname,description,parentHall)
    Values (block, descript, hall);    
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`groupm`@`144.173.%.%` PROCEDURE `create_room`(IN name varchar(45), IN contents mediumtext, IN block INT, In user int)
BEGIN
	INSERT INTO `Room` (title, content, timestamp, parentBlock, creator)
    VALUES (name, contents, NOW(), block, user);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`groupm`@`144.173.%.%` PROCEDURE `create_user`(IN role ENUM('Student', 'Lecturer'), IN usrname varchar(35), IN pass varchar(225), IN address varchar(225))
BEGIN
	INSERT INTO `User` (globalRole, userName, passwordHash, email)
    VALUES (role, usrname, pass, address);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`groupm`@`144.173.%.%` PROCEDURE `delete_comment`(IN comment int)
BEGIN
	DELETE FROM `Comments`
    WHERE commentID = comment;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`groupm`@`144.173.%.%` PROCEDURE `edit_block_description`(IN block INT, IN descript mediumtext)
BEGIN
	UPDATE `Block`
    SET description = descript
    WHERE blockID = block;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`groupm`@`144.173.%.%` PROCEDURE `edit_comment`(IN comment INT, IN text mediumtext)
BEGIN
	UPDATE `Comments`
    SET commentText = text
    WHERE commentID = comment;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`groupm`@`144.173.%.%` PROCEDURE `make_admin`(IN user INT, IN block INT)
BEGIN
	UPDATE `Subscribe`
    SET blockRole = 'Admin'
    WHERE (user = userID) and (block = blockID);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`groupm`@`144.173.%.%` PROCEDURE `Room_vote`(IN user INT, IN room INT, IN type ENUM('Up', 'Down'))
BEGIN
	INSERT INTO `RoomVotes`
    VALUES (user, room, type);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`groupm`@`144.173.%.%` PROCEDURE `subscribe`(IN user INT, IN block INT)
BEGIN
	INSERT INTO `Subscribe`
    VALUES (user, block, 'Member', NOW());
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`groupm`@`144.173.%.%` PROCEDURE `update_comment_vote`(IN user INT, IN comment INT, IN type ENUM('Up', 'Down'))
BEGIN
	UPDATE `CommentVotes`
    SET voteType = type
    WHERE (user = userID) and (comment = commentID); 
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`groupm`@`144.173.%.%` PROCEDURE `update_room_vote`(IN user INT, IN room INT, IN type ENUM('Up', 'Down'))
BEGIN
	UPDATE `RoomVotes`
    SET voteType = type
    WHERE (user = userID) and (room = roomID);
END$$
DELIMITER ;
