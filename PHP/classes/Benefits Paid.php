<?php
    require_once "Database.php";
    class BenefitsPaid {
        public static function addBenefitsPaidData($accountNumber, $date, $deliveredDate, $benefits){
            global $db;
            $number = $db->search("SELECT * FROM `benefits paid`", ['Number']);
            if(empty($number)):
                $number = 1;
            else:
                $number = $number[count($number) - 1] + 1;
            endif;
            $db->insert("INSERT INTO `benefits paid`(`Number`, `Account Number`, `Date`, `Delivered Date`, `Benefits`, `Delivered`) VALUES('$number', '$accountNumber', '$date', '$deliveredDate', '$benefits', '0')");
        }
        public static function getDataOfAccountNumbersToDeliveredBenefits($year, $month, $day, $update = true){
            global $db;
            $day--;
            if($day == 0):
                if($month == '01'):
                    $day = '31';
                    $month = '12';
                    $year --;
                elseif($month == '03'):
                    if($year % 4 == 0):
                        $day = '29';
                    else:
                        $day = '28';
                    endif;
                    $month = '02';
                elseif($month == '02' || $month == '04' || $month == '06' || $month == '08' || $month == '09' || $month == '11'):
                    $day = '31';
                    $month--;
                elseif($month == '05' || $month == '07' || $month == '10' || $month == '12'):
                    $day = '30';
                    $month--;
                endif;
            endif;
            if($day >= 1 && $day <= 9):
                $day = '0' . $day;
            endif;
            if($month >= 1 && $month <= 9):
                $month = '0' . $month;
            endif;
            // 202001 ... 202012
            // 0506
            $dateStart = $year . '01';
            $dateEnd = $year . '12';
            $monthAndDay = $month . $day;
            $data = $db->search("SELECT `Account Number`, `Benefits` FROM `benefits paid` WHERE `Date` >= '$dateStart' AND `Date` <= '$dateEnd' AND `Delivered Date` LIKE '$monthAndDay' AND `Delivered` LIKE '0'", ['Account Number', 'Benefits']);
            if($update == true):
                $db->update("UPDATE `benefits paid` SET `Delivered` = '1' WHERE `Date` >= '$dateStart' AND `Date` <= '$dateEnd' AND `Delivered Date` LIKE '$monthAndDay' AND `Delivered` LIKE '0'");
            endif;
            return $data;
        }
        public static function isThereAccountToCalaulateBenefits($year, $month, $day){
            $check = self::getDataOfAccountNumbersToDeliveredBenefits($year, $month, $day, false);
            if(empty($check)):
                return false;
            else:
                return true;
            endif;
        }
    }