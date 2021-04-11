<?php
    require_once "Database.php";
    class BenefitsReceived {
        public static function addBenefitsLoan($date, $accountNumber, $benefits){
            global $db;
            $number = $db->search("SELECT * FROM `benefits received`", ['Number']);
            if(empty($number)):
                $number = 1;
            else:
                $number = $number[count($number) - 1] + 1;
            endif;
            $db->insert("INSERT INTO `benefits received`(`Number`, `Account Number`, `Date`, `Benefits`) VALUES('$number', '$accountNumber', '$date', '$benefits')");
        }
        public static function filterAccountNumbers($accountNumbers = [], $month, $day){
            global $db;
            $date = $month.$day;
            $accountNumbersCalculatedBenefits = $db->search("SELECT `Account Number` FROM `benefits received` WHERE `Date` LIKE '$date'", ['Account Number']);
            $data = [];
            for($i = 0; $i < count($accountNumbers); $i++):
                if(in_array($accountNumbers[$i], $accountNumbersCalculatedBenefits)):
                    continue;
                endif;
                $data[] = $accountNumbers[$i];
            endfor;
            return $data;
        }
    }