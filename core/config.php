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
    `data` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$connect->query($userTableQuery);

// slide_manager
$slidesTableQuery = "CREATE TABLE IF NOT EXISTS `slide_manager` (
    `slide_id` INT(50) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$connect->query($slidesTableQuery);
?>