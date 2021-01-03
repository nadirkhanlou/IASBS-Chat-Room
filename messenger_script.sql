-- MySQL Script generated by MySQL Workbench
-- Fri Jan  1 23:40:21 2021
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering


SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema messenger
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `messenger` ;

--

-- -----------------------------------------------------
-- Schema messenger
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `messenger` DEFAULT CHARACTER SET utf8 ;
SHOW WARNINGS;
USE `messenger` ;

-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `users` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `phone` NCHAR(11) NOT NULL,
    `handle` VARCHAR(255) NOT NULL,
    `password` NVARCHAR(255) NOT NULL,
    `full_name` NVARCHAR(150) NOT NULL DEFAULT '',
    `is_active` TINYINT(1) NOT NULL DEFAULT 0,
    `is_reported` TINYINT(1) NOT NULL DEFAULT 0,
    `is_blocked` TINYINT(1) NOT NULL DEFAULT 0,
    `preferences` TEXT,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE (phone),
    UNIQUE (handle))
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `messages`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `messages` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `messages` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `status` VARCHAR(2) NOT NULL,
    `reciever_id`	INT NOT NULL,
    `sender_id` INT NOT NULL,
    `message_type` ENUM('text', 'image', 'vedio', 'audio') NOT NULL,
    `message` LONGTEXT NOT NULL DEFAULT '',
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NOT NULL DEFAULT '',
    `deleted_at` DATETIME NOT NULL  DEFAULT '',
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_messages_users2`
    FOREIGN KEY (`reciever_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_messages_users1`
    FOREIGN KEY (`sender_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `reports`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reports` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `reports` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `users_id` INT NOT NULL,
    `participants_id` INT NOT NULL,
    `report_type` VARCHAR(45) NOT NULL,
    `notes` TEXT NOT NULL,
    `status` ENUM('pending', 'resolved') NOT NULL DEFAULT 'pending',
    `created_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_reports_users1`
    FOREIGN KEY (`users_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `block_list`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `block_list` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `block_list` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `users_id` INT NOT NULL,
    `participants_id` INT NOT NULL,
    `created_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_blocks_users1`
    FOREIGN KEY (`users_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    CONSTRAINT `fk_blocks_users2`
    FOREIGN KEY (`participants_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;

SHOW WARNINGS;


-- -----------------------------------------------------
-- Table `activities`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `activities` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `activities` (
    `id` INT NOT NULL,
    `activity_type` VARCHAR(45) NOT NULL,
    `activity_id` INT NOT NULL,
    `title` VARCHAR(45) NOT NULL,
    `detail` TEXT NOT NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`))
    ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `attachments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `attachments` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `attachments` (
    `id` INT NOT NULL,
    `messages_id` INT NOT NULL,
    `thumb_url` VARCHAR(45) NOT NULL,
    `file_url` VARCHAR(45) NOT NULL,
    `created_at` TIMESTAMP NOT NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_attachments_messages1`
    FOREIGN KEY (`messages_id`)
    REFERENCES `messages` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;

SHOW WARNINGS;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

----------------------------------------------------------------------------------------
--------------------------------------PROCEDURES----------------------------------------
----------------------------------------------------------------------------------------

DELIMITER $$
CREATE PROCEDURE ADD_USER
(IN PHONE NCHAR(11),
    IN HANDLE NVARCHAR(255),
    IN `PASSWORD` NVARCHAR(255),
    IN FULL_NAME NVARCHAR(150))
BEGIN
INSERT INTO users (phone, handle, `password`, full_name, created_at, updated_at)
VALUES (PHONE, HANDLE, `PASSWORD`, FULL_NAME, NOW(), NOW());
END$$

---------------------------------------------------------------------------------------

DELIMITER $$
CREATE PROCEDURE CHECK_PASSWORD
(
    IN HANDLE NVARCHAR(255),
    IN `PASSWORD` NVARCHAR(255))
BEGIN
SELECT * FROM users WHERE
        HANDLE = users.handle && `PASSWORD` = users.`password`;
END$$

---------------------------------------------------------------------------------------

DROP PROCEDURE GET_CONTACTS

    DELIMITER $$
CREATE PROCEDURE GET_CONTACTS
(
    IN HANDLE NVARCHAR(255))
BEGIN
	SET @userId = (SELECT id FROM users WHERE HANDLE = users.handle);
SELECT users.full_name, users.phone, users.handle, users.id FROM users WHERE
    HANDLE != users.handle && users.id NOT IN
    (SELECT block_list.participants_id FROM block_list WHERE users_id = @userId);
END$$

----------------------------------------------------------------------------------------

DELIMITER $$
CREATE PROCEDURE GET_BLOCKED
(
    IN HANDLE NVARCHAR(255))
BEGIN
	SET @userId = (SELECT id FROM users WHERE HANDLE = users.handle);
SELECT users.full_name, users.phone, users.handle, users.id FROM users WHERE
    HANDLE != users.handle && users.id IN
    (SELECT block_list.participants_id FROM block_list WHERE users_id = @userId);
END$$

----------------------------------------------------------------------------------------

DELIMITER $$
CREATE PROCEDURE GET_USER_BY_HANDLE
(
    IN HANDLE NVARCHAR(255))
BEGIN
SELECT users.full_name, users.phone, users.handle, users.id FROM users WHERE users.handle = HANDLE;
END$$

----------------------------------------------------------------------------------------

DELIMITER $$
CREATE PROCEDURE GET_USER_BY_PHONE
(
    IN PHONE NCHAR(11))
BEGIN
SELECT users.full_name, users.phone, users.handle, users.id FROM users WHERE users.phone = PHONE;
END$$

----------------------------------------------------------------------------------------

DROP PROCEDURE EDITPROFILE

DELIMITER $$
CREATE PROCEDURE EDITPROFILE(IN USEROLD VARCHAR(255),IN USERNEW VARCHAR(255), IN PASS VARCHAR(255), IN FN VARCHAR(150))
BEGIN
UPDATE users SET handle = USERNEW, password = PASS, full_name = FN, updated_at = NOW() WHERE handle = USEROLD;
END $$
DELIMITER ;

CALL EDITPROFILE('WWW','WWW','WWW','WWW','WWW');

----------------------------------------------------------------------------------------

DELIMITER $$
CREATE PROCEDURE BLOCK_USER
	(
    IN HANDLE NCHAR(11),
    IN TO_BE_BLOCKED_HANDLE NCHAR(11))
BEGIN
	SET @users_id = (SELECT id FROM users WHERE HANDLE = users.handle);
	SET @participants_id = (SELECT id FROM users WHERE HANDLE = users.handle);
    IF EXISTS(SELECT * FROM block_list WHERE @users_id = users_id && @participants_id = participants_id)
    THEN
	INSERT INTO block_list (users_id, participants_id, created_at) 
	VALUES (@users_id, @participants_id, NOW());
    END IF;
END$$

DELIMITER ;

CALL BLOCKUSER('WWW', 'DOD');

------------------------------------------------------------------------------------------

DELIMITER $$

CREATE PROCEDURE SENDMESSAGE(IN FROMUSER VARCHAR(300), IN TOUSER VARCHAR(300), IN MSG LONGTEXT, IN MSGTYPE VARCHAR(20))
BEGIN
	INSERT INTO MESSAGES(status, sender_id, reciever_id, message, message_type, created_at)
	VALUES('1', FROMUSER, TOUSER, MSG, MSGTYPE, NOW());
END $$

DELIMITER ;

CALL SENDMESSAGE('www', 'ddd', 'hello');

------------------------------------------------------------------------------------------
DELIMITER $$

CREATE PROCEDURE DELETEMESSAGE(IN MID INT)
BEGIN
SET
UPDATE messages SET status = '2', deleted_at = NOW() WHERE messages.id = MID;   #inja 2 be manaye vaziate hazf shode ast va 3 ke dar edame khahim did be manaye edit shode ast
END $$

DELIMITER ;

CALL DELETEMESSAGE(1);

-------------------------------------------------------------------------------------------

DELIMITER $$

CREATE PROCEDURE EDITMESSAGE(IN MID INT, IN MSG LONGTEXT)
BEGIN
UPDATE messages SET status = '3', message = MSG, updated_at = NOW() WHERE messages.id = MID;
END $$

DELIMITER ;

CALL EDITMESSAGE( 1 , 'HI');
-------------------------------------------------------------------------------------------
DELIMITER $$

CREATE PROCEDURE GETHISTORY(IN FROMUSER VARCHAR(300), IN TOUSER VARCHAR(300))
BEGIN
SELECT * FROM messages WHERE sender_id = FROMUSER AND reciever_id = TOUSER;
END $$

DELIMITER ;

CALL GETHISTORY('www', 'ddd');

-------------------------------------------------------------------------------------------