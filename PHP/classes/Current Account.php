<?php
    require_once "Database.php";
    class CurrentAccount {
        public static function getMoney($accountNumber){
            global  $db;
            return $db->search("SELECT `Money` FROM `current account` WHERE `Account Number` LIKE '$accountNumber'", ['Money'])[0];
        }
    }
