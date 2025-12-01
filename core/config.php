<?php
# ============================================================
#  Project Name : SlideCastBot
#  Description  : Telegram bot for slide-based presentations
#  Developer    : Hojjat Jahānpour
#  Version      : 1.0.0
# ============================================================

// Telegram Bot API Token  
define('API_KEY', "");

// Admin user ID (GET: https://t.me/userinfobot)
$admin_user_id = ['759869599']; 

//
// MariaDB / MySQL Settings
//
$connect = new mysqli('localhost',' ',' ',' '); 
$connect->query("SET NAMES 'utf8'"); $connect->set_charset('utf8mb4');

// users table
$userTableQuery = "CREATE TABLE IF NOT EXISTS `users` (
    `user_id` BIGINT(48) NOT NULL PRIMARY KEY,
    `lang` ENUM('ar','fa','en','not_set') NOT NULL DEFAULT 'not_set',
    `step` varchar(255) DEFAULT 'none',
    `temp` INT(50) DEFAULT '0',
    `data` LONGTEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$connect->query($userTableQuery);

// Presentation table
$presentationTableQuery = "CREATE TABLE IF NOT EXISTS `presentation` (
    `presentation_id` INT(50) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `slug` VARCHAR(255) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `channel_id` VARCHAR(100) NOT NULL,
    `message_id` VARCHAR(255) NOT NULL,
    `slide_count` VARCHAR(100) NOT NULL,
    `created_by` INT(50) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$connect->query($presentationTableQuery);

// slides table
$slidesTableQuery = "CREATE TABLE IF NOT EXISTS `slides` (
    `p_id` VARCHAR(255) NOT NULL,
    `slide_n` VARCHAR(100) NOT NULL,
    `file_id` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$connect->query($slidesTableQuery);
?>