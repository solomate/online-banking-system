<?php
    require_once "Employee.php";
    require_once "Database.php";

    class IT extends Employee{
        public function __construct($id)
        {
            $this->id = $id;
        }
        public function getReports(){
            global $db;
            $data = $db->search("SELECT * FROM `report`", ['Number', 'ID', 'Name', 'Department', 'Computer Number', 'Report']);
            $displayData = [];
            for($i = 1; $i < count($data); $i+=6):
                $isIn = $db->search("SELECT `In` FROM `employee` WHERE `ID` LIKE '$data[$i]'", ['In']);
                if($isIn[0] == 1):
                    $j = $i - 1;
                    for($k = 0; $k < 6; $k++):
                        $displayData[] = $data[$j];
                        $j++;
                    endfor;
                endif;
            endfor;
            $i = 0;
            while($i < count($displayData)):
                echo "<tr>";
                for($j = 0; $j < 6; $j++):
                    echo "<td>$displayData[$i]</td>";
                    $i++;
                endfor;
                echo "</tr>";
            endwhile;
        }
    }