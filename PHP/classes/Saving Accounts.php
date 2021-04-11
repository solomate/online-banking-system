<?php
    require_once "Database.php";
    class SavingAccount {
        public static function getBenefitsFromAccountNumber($accountNumber){
            global $db;
            return $db->search("SELECT `Benefits` FROM `saving account` WHERE `Account Number` LIKE '$accountNumber'", ['Benefits'])[0];
        }
        public static function getAllAccountNumbersFromTable(){
            global $db;
            return $db->search("SELECT `Account Number` FROM `saving account`", ['Account Number']);
        }
        public static function getMoneyFromAccountNumberAtSpecificMonth($accountNumber, $from, $to){
            global $db;
            return $db->search("SELECT `Money` FROM `financial transactions` WHERE `Account Number 1` LIKE '$accountNumber' AND `Type` LIKE 'Saving Account' AND `Calculated Benefits` LIKE '0' AND `Date` >= '$from' AND `Date` < '$to'", ['Money']);
        }
        public static function getDeliveredBenefitsDateForAccountNumber($accountNumber){
            global $db;
            return $db->search("SELECT `Date` FROM `saving account` WHERE `Account Number` LIKE '$accountNumber'", ['Date'])[0];
        }
        public static function getYearCreateAccountNumber($accountNumber){
            global $db;
            $date = $db->search("SELECT `Date` FROM `saving account` WHERE `Account Number` LIKE '$accountNumber'", ['Date'])[0];
            $date = str_split($date, 4)[0];
        }
    }
