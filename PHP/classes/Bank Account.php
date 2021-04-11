<?php
    require_once "Database.php";

    class BankAccount {
        public static function update($accountNumber, $money){
            global $db;
            $db->update("UPDATE `bank account` SET `Money` = '$money' WHERE `Account Number` LIKE '$accountNumber'");
        }
        public static function getMoneyFromAccountNumber($accountNumber){
            global $db;
            return $db->search("SELECT `Money` FROM `bank account` WHERE `Account Number` LIKE '$accountNumber'", ['Money'])[0];
        }
    }