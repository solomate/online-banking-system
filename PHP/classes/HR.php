<?php
    require_once "Database.php";
    require_once "Employee.php";
    require_once "Address.php";
    require_once "Task.php";
    class HR extends Employee {
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
        public function newEmployee($name, $age, $nationalID, $address, $phoneNumber, $gender, $email, $department, $job, $salary){
            global $db;
            $data = $db->search("SELECT `National ID`, `Email`, `In` FROM `employee`", ['National ID', 'Email', 'In']);
            if(in_array($email, $data)):
                $this->message('Email Is Already Exists');
                return;
            elseif(in_array($nationalID, $data)):
                $this->message('National ID Is Already Exists');
                return;
            endif;
            $id = $db->search("SELECT `ID` FROM `employee`", ["ID"]);
            $id = $id[count($id) - 1] + 1;
            $addressNumber = $address->addressNumber();
            $db->insert("INSERT INTO `employee`(`ID`, `Name`, `Age`, `National ID`, `Address`, `Phone Number`, `Email`, `Gender`,
             `Department`, `Job`, `Salary`, `Computer Number`, `In`) VALUES ('$id', '$name', '$age', '$nationalID', '$addressNumber', '$phoneNumber',
              '$email', '$gender', '$department', '$job', '$salary', '$id', '1')");

            $pass = rand(1000000, 10000000);
            $hashedpassword = password_hash($pass, PASSWORD_DEFAULT);
            $number = $db->search("SELECT * FROM `employeelogin`", ['Number']);
            if(empty($number)):
                $number = 1;
            else:
                $number = $number[count($number) - 1] + 1;
            endif;
            $db->insert("INSERT INTO `employeelogin`(`Number`, `ID`, `Username`, `Email`, `Password`) VALUES ('$number', '$id', '$id', '$email', '$hashedpassword')");

            $to = $email;
            $subject = 'Username and Password';
            $message = '<p>Welcome to our MasterBank, thanks being part in our community.Bellow you will have your USERNAME and PASSWORD
            to be able to login into our system.</p>';
            $message .= '<p>Here is your USERNAME and PASSWORD : </p>';
            $message .= '<p>USERNAME : '. $id.'</p>';
            $message .= '<p>PASSWORD : '.$pass.'</p>';
            $headers = "From: Master Bank <yonehazaki@gmail.com>\r\n";
            $headers .="Reply-To: yonehazaki@gmail.com\r\n";
            $headers .= "Content-Type: text/html\r\n";
            require_once(realpath($_SERVER["DOCUMENT_ROOT"]).'\Bank\Login System\Mail\mailer.php');

            $this->message('Successful Operation');
        }
        public function getData($id){
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
            echo "<script>
                document.getElementById('table').style.display = 'block';
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
        public function remove($id){
            global $db;
            // DELETE FROM `task` WHERE `task`.`Number` = 2;
            $emailAndNationalId = $db->search("SELECT `Email`, `National ID` FROM `employee` WHERE ID LIKE '$id' AND `In` LIKE '1'", ['Email', 'National ID']);
            if(count($emailAndNationalId) == 0):
                $this->message('Wrong ID');
                return;
            endif;
            $email = $emailAndNationalId[0] . '0';
            $nationalID = $emailAndNationalId[1]. '  Not In';
            //$addressNumber = implode($addressNumber);
            $db->update("UPDATE `employee` SET `In` = '0', `Email` = '$email', `National ID` = '$nationalID' WHERE `ID` LIKE '$id'");
            $numbers = $db->search("SELECT `Number`, `ID` FROM `employeelogin`", ['Number', 'ID']);
            $index = -1;
            for($i = 1; $i < count($numbers); $i+=2):
                if($numbers[$i] == $id):
                    $index = $i + 1;
                break;
                endif;
            endfor;
            for($i = $index; $i < count($numbers); $i+=2):
                $numbers[$i] --;
            endfor;
            $db->remove("DELETE FROM `employeelogin` WHERE `ID` LIKE '$id'");
            for($i = $index; $i < count($numbers); $i+=2):
                $j = $i + 1;
                echo $numbers[$i] . $numbers[$j] . "<br>";
                $number = $numbers[$i];
                $x = $numbers[$j];
                $db->update("UPDATE `employeelogin` SET `Number` = '$number' WHERE `ID` LIKE '$x'");
            endfor;
            /*
            $db->remove("DELETE FROM `task` WHERE `ID` LIKE '$id'");
            $db->remove("DELETE FROM `report` WHERE `ID` LIKE '$id'");
            $db->remove("DELETE FROM `comment` WHERE `ID` LIKE '$id'");
            $db->remove("DELETE FROM `employee` WHERE `ID` LIKE '$id'");
            $db->remove("DELETE FROM `address` WHERE `Number` LIKE '$addressNumber'");
            */
            $this->message('Successful Operation');
        }
        public function update($id, $city = '', $street = '', $homeNumber = '', $email = '', $salary = ''){
            global $db;
            $employeeData = $db->search("SELECT `Address`, `Email`, `Salary`, `Job` FROM `employee` WHERE `ID` LIKE '$id' AND `In` LIKE '1'",
                ['Address', 'Email', 'Salary', 'Job']);
            if(count($employeeData) == 0):
                $this->message('Wrong ID');
                return;
            endif;
            $addressNumber = $employeeData[0];
            $addressData = $db->search("SELECT * FROM `address` WHERE `Number` LIKE '$addressNumber'", ['City', 'Street', 'Home Number']);
            if($city == ''):
                $city = $addressData[0];
            endif;
            if($street == ''):
                $street = $addressData[1];
            endif;
            if($homeNumber == ''):
                $homeNumber = $addressData[2];
            endif;
            if($email == ''):
                $email = $employeeData[1];
            else:
                $data = $db->search("SELECT `Email` FROM `employee` WHERE `In` LIKE '1'", ['Email']);
                if(in_array($email, $data)):
                    $this->message('Email Is Already Exists');
                    return;
                endif;
            endif;
            if($salary == ''):
                $salary = $employeeData[2];
            endif;
            $address = new Address($city, $street, $homeNumber);
            $addressNumber = $address->addressNumber();
            $db->update("UPDATE `employeelogin` SET `Email` = '$email' WHERE `ID` LIKE '$id'");
            $db->update("UPDATE `employee` SET `Address` = '$addressNumber', `Email` = '$email', `Salary` = '$salary' WHERE `ID` LIKE '$id'");
            $this->message('Successful Operation');
        }
    }
