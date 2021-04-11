<?php
    require_once "Employee.php";
    require_once "Database.php";
    require_once "Task.php";
    require_once "Financial Transactions.php";
    require_once "Bank Account.php";
    class Manager extends Employee {
        public function __construct($id)
        {
            parent::__construct($id);
        }
        private function message($message){
            echo
                "<script>
                    document.getElementById('errorMessage').innerHTML = '$message';
                    document.getElementById('hint_id').style.display = 'block';
                </script>";
        }
        public function getEmployeeData($id){
            global $db;
            $employeeData = $db->search("SELECT * FROM employee WHERE ID LIKE '$id' AND `In` LIKE '1'", ['ID', 'Name', 'Age', 'National ID', 'Address', 'Phone Number', 'Email',
                 'Gender', 'Department', 'Job', 'Salary', 'Computer Number']);
            if(count($employeeData) == 0):
                $this->message('Wrong ID');
                return;
            else:
                $address = $db->search("SELECT * FROM `address` WHERE `Number` LIKE '$employeeData[4]'", ['City', 'Street', 'Home Number']);
                $employeeData[] = $address[0];
                $employeeData[] = $address[1];
                $employeeData[] = $address[2];
            endif;
            // DISPLAY IN PAGE
            echo "<script>
                document.getElementById('Employee_data').style.display = 'block';
                document.getElementById('empName').innerHTML = '$employeeData[1]';
                document.getElementById('empID').innerHTML = '$employeeData[0]';
                document.getElementById('empAge').innerHTML = '$employeeData[2]';
                document.getElementById('empNationalId').innerHTML = '$employeeData[3]';
                document.getElementById('empCity').innerHTML = '$employeeData[12]';
                document.getElementById('empStreet').innerHTML = '$employeeData[13]';
                document.getElementById('empHomeNumber').innerHTML = '$employeeData[14]';
                document.getElementById('empPhoneNumber').innerHTML = '$employeeData[5]';
                document.getElementById('empEmail').innerHTML = '$employeeData[6]';
                document.getElementById('empGender').innerHTML = '$employeeData[7]';
                document.getElementById('empDepartment').innerHTML = '$employeeData[8]';
                document.getElementById('empJob').innerHTML = '$employeeData[9]';
                document.getElementById('empSalary').innerHTML = '$employeeData[10]';
                document.getElementById('empComputerNumber').innerHTML = '$employeeData[11]';
            </script>";
        }
        public function addTask($id, $task){
            global $db;
            $check = $db->search("SELECT `ID` FROM `employee` WHERE `ID` LIKE '$id' AND `In` LIKE '1'", ['ID']);
            if(empty($check)):
                $this->message('Wrong ID');
                return;
            endif;
            if($task == ''):
                $this->message('Can Not Set An Empty Task');
                return;
            endif;
            Task::addTask($id, $task);
            $this->message('Successful Operation');
        }
        public function getComments(){
            global $db;
            // Number, Date, Name, ID, Department, Job, Comment
            $data = $db->search("SELECT * FROM `comment`", ["Number", "Date", "Name", "ID", "Department", "Job", "Comment"]);

            $displayData = [];
            for($i = 3; $i < count($data); $i+=7):
                $isIn = $db->search("SELECT `In` FROM `employee` WHERE `ID` LIKE '$data[$i]'", ['In']);
                if($isIn[0] == 1):
                    $j = $i - 3;
                    for($k = 0; $k < 7; $k++):
                        $displayData[] = $data[$j];
                        $j++;
                    endfor;
                endif;
            endfor;
            for($i = 1; $i < count($displayData); $i+=7):
                $date = $displayData[$i];
                $date = str_split($date, 1);
                $date = $date[6].$date[7] . "/" . $date[4].$date[5] . "/" . $date[0].$date[1].$date[2].$date[3];
                $displayData[$i] = $date;
            endfor;
            $i = 0;
            while($i != count($displayData)):
                echo "<tr>";
                for($j = 0; $j < 7; $j++):
                    echo "<td>$displayData[$i]</td>";
                    $i++;
                endfor;
                echo "</tr>";
            endwhile;
        }
        public function checkTasks(){
            global $db;
            // Number, Date, ID, Job, Task, Done
            $data = $db->search("SELECT * FROM `task`", ['Number', 'Date', 'ID', 'Job', 'Task', 'Done']);
            $displayData = [];
            for($i = 2; $i < count($data); $i+=6):
                $isIn = $db->search("SELECT `In` FROM `employee` WHERE `ID` LIKE '$data[$i]'", ['In']);
                if($isIn[0] == 1):
                    $j = $i - 2;
                    for($k = 0; $k < 6; $k++):
                        $displayData[] = $data[$j];
                        $j++;
                    endfor;
                endif;
            endfor;
            for($i = 1; $i < count($displayData); $i+=6):
                $date = $displayData[$i];
                $date = str_split($date, 1);
                $date = $date[6].$date[7] . "/" . $date[4].$date[5] . "/" . $date[0].$date[1].$date[2].$date[3];
                $displayData[$i] = $date;
            endfor;
            for($i = 5; $i < count($displayData); $i+=6):
                if($displayData[$i] == 0):
                    $displayData[$i] = 'Not Done';
                else:
                    $displayData[$i] = 'Done';
                endif;
            endfor;
            $i = 0;
            while($i != count($displayData)):
                echo "<tr>";
                for($j = 0; $j < 6; $j++):
                    echo "<td>$displayData[$i]</td>";
                    $i++;
                endfor;
                echo "</tr>";
            endwhile;
        }
        private function transactionsData(){
            // 'Number', 'Date', 'Account Number 1', 'Type', 'Account Number 2', 'Transition', 'Amount'
            $data = FinancialTransactions::getTransactions();

            for($i = 1; $i < count($data); $i+=7):
                $date = $data[$i];
                $date = str_split($date, 1);
                $date = $date[6].$date[7] . "/" . $date[4].$date[5] . "/" . $date[0].$date[1].$date[2].$date[3];
                $data[$i] = $date;
            endfor;
            for($i = 4; $i < count($data); $i+=7):
                if($data[$i] == 0):
                    $data[$i] = 'No Account Number';
                endif;
            endfor;
            for($i = 2; $i < count($data); $i+=7):
                if($data[$i] == 0):
                    $data[$i] = 'Storage';
                    $data[$i + 1] = 'Storage';
                    $data[$i + 2] = $data[$i + 2] . ' / Current Account';
                endif;
            endfor;
            return $data;
        }
        public function getTransactionsInStorage(){
            $data = $this->transactionsData();
            // DISPLAY IN PAGE
            $i = 0;
            while($i != (count($data))):
                echo "<tr>";
                for($j = 0; $j < 7; $j++):
                    echo "<td>$data[$i]</td>";
                    $i++;
                endfor;
                echo "</tr>";
            endwhile;
        }
        public function getTotalStorage(){
            $total = BankAccount::getMoneyFromAccountNumber(0);
            echo "<td>$total</td>";
        }
    }
