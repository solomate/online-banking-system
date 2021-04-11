<?php
    require_once "Database.php";
    require_once "Address.php";
    require_once "Task.php";
    class Employee {
        private $id;
        public function __construct($id)
        {
            $this->id = $id;
        }
        public function getTask($path, $divId){
            echo "<script>document.getElementById('checkClickedGetTask').value = 'notClicked';</script>";
            // GET TASKS FROM DATABASE
            $data = Task::getTask($this->id);
            // $data = ['2019/12/14', 'dsfdsgjwngwn', '2019/12/16', 'woifjoef'];
            $done = [];
            // DISPLAY TASKS IN TABLE
            for($i = 0; $i < count($data); $i++):
                echo "<tr>
                    <th>$data[$i]</th>";
                $i++;
                echo "<td>$data[$i]</td>
                    <td><input id='done$i' name='done$i' type='checkbox'></td>
                ";
                $done[] = $data[$i];
                $done[] = $i;
            endfor;
            // CHECK TASKS HAVE DONE
            for($i = 0; $i < count($done); $i++):
                $task = $done[$i];
                $i++;
                $doneNumber = 'done'.$done[$i];
                if(@ $_POST[$doneNumber] == 'on'):
                    $this->doneTask($task);
                    echo "<script>open('$path', '_self')</script>";
                endif;
            endfor;
            echo "<script>document.getElementById('$divId').style.display = 'block';</script>";
        }
        public function doneTask($task){
            global $db;
            $db->update("UPDATE `task` SET `Done` = '1' WHERE `ID` LIKE '$this->id' AND `Task` LIKE '$task'");
        }
        public function addComment($comment, $path){
            global $db;
            $number = $db->search("SELECT * FROM `comment`", ["Number"]);
            if (count($number) == 0):
                $number = 1;
            else:
                $number = $number[count($number) - 1] + 1;
            endif;
            $data = $db->search("SELECT `Name`, `Department`, `Job` FROM `employee` WHERE `ID` LIKE '$this->id'", ["Name", "Department", "Job"]);
            $name = $data[0];
            $department = $data[1];
            $job = $data[2];
            $date = implode(explode("/", date("Y/m/d")));
            $db->insert("INSERT INTO `comment`(`Number`, `Date`, `ID`, `Name`, `Department`, `Job`, `Comment`)
                VALUES ('$number', '$date', '$this->id', '$name', '$department', '$job', '$comment')");
            echo "<script>open('$path', '_self')</script>";
        }
        public function addReport($report, $path){
            global $db;
            $id = $this->id;
            var_dump($id);
            $data = $db->search("SELECT `Name`, `Computer Number`, `Department` FROM `employee` WHERE `ID` LIKE '$id'", ['Name', 'Computer Number', 'Department']);
            echo "<pre>";
            print_r($data);
            echo "</pre>";
            $number = $db->search("SELECT * FROM `report`", ["Number"]);
            if(empty($number)):
                $number = 1;
            else:
                $number = $number[count($number) - 1] + 1;
            endif;
            $db->insert("INSERT INTO `report`(`Number`, `ID`, `Name`, `Department`, `Computer Number`, `Report`) VALUES('$number', '$this->id', '$data[0]', '$data[2]', '$data[1]', '$report')");
            //echo "<script>open('$path', '_self');</script>";
        }
        public function displayHint(){
            global $db;
            /*
            $newTask = $db->search("SELECT `Seen` FROM `task` WHERE `ID` LIKE '$this->id' AND `Seen` LIKE '0'", ['Seen']);
            if(!empty($newTask)):
                echo "<script>document.getElementById('hintTask').style.visibility = 'visible';</script>";
            endif;
            */
            $taskNotDone = $db->search("SELECT `Done` FROM `task` WHERE `ID` LIKE '$this->id' AND `Done` LIKE '0'", ['Done']);
            if(!empty($taskNotDone)):
                $count = count($taskNotDone);
                echo
                    "<script>
                        document.getElementById('taskNotDone').innerHTML = '$count';
                        document.getElementById('taskNotDone').style.visibility = 'visible';
                    </script>";
            endif;
        }
    }
