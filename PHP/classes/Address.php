<?php
    require_once "Database.php";
    class Address {
        public $streetName;
        public $homeNumber;
        public $city;
        public function __construct($city, $streetName, $homeNumber)
        {
            $this->streetName = $streetName;
            $this->homeNumber = $homeNumber;
            $this->city = $city;
        }
        public static function getData($addressNumber){
            global $db;
            return $db->search("SELECT * FROM `address` WHERE `Number` LIKE '$addressNumber'", ['City', 'Street', 'Home Number']);
        }
        public function inArray($data){
            for($i = 1; $i < count($data); $i+=4):
                if($data[$i] == $this->city && $data[$i + 1] == $this->streetName && $data[$i+2] == $this->homeNumber):
                    return $data[$i - 1];
                endif;
            endfor;
            return -1;
        }
        public function addressNumber(){
            global $db;
            $search = $db->search("SELECT * FROM `address`", ["Number", "City", "Street", "Home Number"]);
            $number = $this->inArray($search);
            if($number == -1):
                if(count($search) == 0):
                    $number = 1;
                else:
                    $number = $search[count($search) - 4] + 1;
                endif;
                $db->insert("INSERT INTO `address`(`Number`, `City`, `Street`, `Home Number`)
                        VALUES ('$number', '$this->city', '$this->streetName', '$this->homeNumber')");
                return $number;
            else:
                return $number;
            endif;
        }
    }
