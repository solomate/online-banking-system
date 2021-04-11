<?php
    require_once "Database.php";
    class BenefitsTransaction {
        public static function newBenefitsDetails($date, $accountNumber, $accountType, $benefitsType, $benefits){
            global $db;
            $number = $db->search("SELECT * FROM `benefits transactions`", ['Number']);
            if(empty($number)):
                $number = 1;
            else:
                $number = $number[count($number) - 1] + 1;
            endif;
            $db->insert("INSERT INTO `benefits transactions`(`Number`, `Date`, `Account Number`, `Account Type`, `Benefits Type`, `Benefits`) 
                VALUES('$number', '$date', '$accountNumber', '$accountType', '$benefitsType', '$benefits')");
        }
    }