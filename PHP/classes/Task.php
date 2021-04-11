<?php
    require_once "Database.php";
    class Task{

        private static function validID($id){
            global $db;
            $data = $db->search("SELECT `ID`, `Job` FROM `employee`", ["ID", "Job"]);
            if(in_array($id, $data)):
                $index = array_search($id, $data);
                return $data[$index + 1];
            else:
                return -1;
            endif;
        }
        public static function addTask($id, $task){
            $data = self::validID($id);
            if($data == -1):
                echo "Wrong id";
                return;
            endif;
            global $db;
            $number = $db->search("SELECT * FROM `task`", ["Number"]);
            if(count($number) == 0):
                $number = 1;
            else:
                $number = $number[count($number) - 1] + 1;
            endif;
            $date = implode(explode("/", date("Y/m/d")));
            $db->insert("INSERT INTO `task`(`Number`, `Date`, `ID`, `Job`, `Task`, `Seen`, `Done`)
                VALUES('$number', '$date', '$id', '$data', '$task', '0', '0')");
        }
        public static function getTask($id){
            global $db;
            $data = $db->search("SELECT `Date`, `Task` FROM `task` WHERE `ID` LIKE '$id' AND `Done` LIKE '0'", ["Date" ,"Task"]);
            $db->update("UPDATE `task` SET `Seen` = '1' WHERE `ID` LIKE '$id' AND `Seen` LIKE '0'");
            return $data;
        }
    }
