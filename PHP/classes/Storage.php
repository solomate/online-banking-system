<?php
    require_once "Database.php";
    class Storage{
        public static function newTransaction($date, $accountNumber, $money, $transAction){
            global $db;
            $storageNumbers = $db->search("SELECT * FROM `storage`", ["Number"]);
            if(empty($storageNumbers)):
                $number = 1;
            else:
                $number = $storageNumbers[count($storageNumbers) - 1] +1;
            endif;
            if($transAction == 'Withdraw'):
                $money *= -1;
            endif;
            $db->insert("INSERT INTO `storage`(`Number`, `Date`, `Account Number`, `Money`) VALUES('$number', '$date', '$accountNumber', '$money')");
        }
        public function update($accountNumber, $arg, $val){
            global $db;
            $db->update("UPDATE `bank account` SET `$arg` = '$val' WHERE `Account Number` LIKE '$accountNumber'");
        }
    }