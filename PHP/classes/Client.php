<?php
    require_once "Database.php";
    require_once "Loan.php";
    class Client {
        private $accountNumber;
        public function __construct($accountNumber)
        {
            $this->accountNumber = $accountNumber;
        }
        public static function newClient($name, $age, $nationalID, $addressNumber, $phoneNumber, $gender, $email, $accountType, $accountNumber){
            global $db;
            $insert = "INSERT INTO `client`(`Account Number`, `Name`, `Age`, `National ID`, `Address`, `Phone Number`, `Email`, `Gender`, `Account Type`)
                VALUES ('$accountNumber', '$name', '$age', '$nationalID', '$addressNumber', '$phoneNumber', '$email', '$gender', '$accountType')";
            $db->insert($insert);
        }
        public static function hasCurrentAccount($accountNumber){
            global $db;
            $check = $db->search("SELECT * FROM `current account` WHERE `Account Number` LIKE '$accountNumber'", ['Account Number']);
            if(empty($check)):
                return -1;
            else:
                return 1;
            endif;
        }
        public static function addAccount($date, $accountNumber, $accountType, $money){
            global $db;
            if($accountType == 'Saving Account'):
                $date = str_split($date, 2);
                $date = $date[count($date) - 2] . $date[count($date) - 1];
                $date += 1;
                
                $insert = "INSERT INTO `saving account`(`Date`, `Account Number`, `Money`, `Benefits`) VALUES('$date', '$accountNumber', '$money', '0')";
            else:
                $insert = "INSERT INTO `current account`(`Account Number`, `Money`) VALUES('$accountNumber', '$money')";
            endif;
            $db->insert($insert);
        }
        public static function newLoginData($accountNumber, $email, $hashedpassword){
            global $db;
            $insert = "INSERT INTO `client login`(`ID`, `Username`, `Email`, `Password`) VALUES ('$accountNumber', '$accountNumber', '$email', '$hashedpassword')";
            $db->insert($insert);
        }
        public static function update($accountNumber, $table, $arg, $val){
            global $db;
            $db->update("UPDATE `$table` SET `$arg` = '$val' WHERE `Account Number` LIKE '$accountNumber'");
        }
        public static function getMoneyFromAccountNumber($accountNumber, $table){
            global $db;
            return $db->search("SELECT `Money` FROM `$table` WHERE `Account Number` LIKE '$accountNumber'", ['Money'])[0];
        }
        public static function getSpecificData($accountNumber, $data = []){
            global $db;
            return $db->search("SELECT * FROM `client` WHERE `Account Number` LIKE '$accountNumber'", $data);
        }
        public function start(){
            global $db;
            $clientData = self::getSpecificData($this->accountNumber,  ['Account Number', 'Name', 'Age', 'National ID', 'Address', 'Phone Number', 'Email', 'Gender']);
            // Account Number[0], Name[1], Age[2], National ID[3], Address[4], Phone Number[5], Email[6]
            $addressData = $db->search("SELECT * FROM `address` WHERE `Number` LIKE '$clientData[4]'", ['City', 'Street', 'Home Number']);
            $clientData[4] = $addressData[2] . "&emsp;" . $addressData[1] . ".ST &emsp;" . $addressData[0];
            $cName = explode(" ", $clientData[1]);
            $cName = $cName[0] . " " . $cName[2];
            if($clientData[7] == 'Female'):
                echo "<script>document.getElementById('personPic').src = 'images/person4.png';</script>";
            endif;
            echo
                "<script>
                    document.getElementById('accountNumber').innerHTML = 'Account Number: $clientData[0]';
                    document.getElementById('cName').innerHTML = '$cName';
                    document.getElementById('name').innerHTML = '$clientData[1]';
                    document.getElementById('age').innerHTML = '$clientData[2]';
                    document.getElementById('nationalID').innerHTML = '$clientData[3]';
                    document.getElementById('address').innerHTML = '$clientData[4]';
                    document.getElementById('phoneNumber').innerHTML = '$clientData[5]';
                    document.getElementById('email').innerHTML = '$clientData[6]';
                </script>";
        }
        public function getAccountStatement($accountType, $from, $to){
            global $db;
            $from = implode(explode("-", $from));
            $to = implode(explode("-", $to));
            $data = $db->search("SELECT * FROM `financial transactions` WHERE `Account Number 1` LIKE '$this->accountNumber'
                AND `Type` LIKE '$accountType' AND `Date` >= '$from' AND `Date` <= '$to'", ['Date', 'Transition', 'Amount']);
            for($i = 0; $i < count($data); $i+=3):
                $date = $data[$i];
                $date = str_split($date, 1);
                $date = $date[6].$date[7] . "/" . $date[4].$date[5] . "/" . $date[0].$date[1].$date[2].$date[3];
                $data[$i] = $date;
            endfor;
            for($i = 2; $i < count($data); $i+=3):
                if($data[$i] < 0):
                    $data[$i] *= -1;
                endif;
            endfor;
            $number = 1;
            for($i = 0; $i < count($data); $i++):
                echo "<tr>";
                echo "<td>$number</td>";
                echo "<td>$data[$i]</td>"; $i++;
                echo "<td>$data[$i]</td>"; $i++;
                echo "<td>$data[$i]</td>";
                echo "</tr>";
                $number++;
            endfor;
            echo "<script>document.getElementById('statment').style.display = 'block';</script>";
        }
        public function getTotalMoney($accountType){
            $table = strtolower($accountType);
            $total = self::getMoneyFromAccountNumber($this->accountNumber, $table);
            echo "<td>$total</td>";
        }
        public function getWarning(){
            $data = Loan::getWarning($this->accountNumber);
            for($i = 0; $i < count($data); $i++):
                echo "<tr>";
                echo "<td>$data[$i]</td>"; $i++;
                echo "<td>$data[$i]</td>";
                echo "</tr>";
            endfor;
            echo "<script>document.getElementById('warnings').style.display = 'block';</script>";
        }
        public function getReminder(){
            $data = Loan::getReminder($this->accountNumber);
            for($i = 0; $i < count($data); $i++):
                echo "<tr>";
                echo "<td>$data[$i]</td>"; $i++;
                echo "<td>$data[$i]</td>";
                echo "</tr>";
            endfor;
        }
        public function enquiry($name, $email, $phoneNumber, $enquiry){
            global $db;
            $number = $db->search("SELECT * FROM `enquiry`", ['Number']);
            if(empty($number)):
                $number = 1;
            else:
                $number = $number[count($number) - 1] + 1;
            endif;
            $db->insert("INSERT INTO `enquiry`(`Number`, `Name`, `Email`, `Phone Number`, `Customer Enquiry`)
                VALUES('$number', '$name', '$email', '$phoneNumber', '$enquiry')");
            echo "<script>open('http://localhost/bank/htML/client%20page/client.php', '_self');</script>";
        }
        public function applyForVisa($firstName, $lastName, $cardName, $gender, $day, $month, $year, $email, $phoneNumber, $address, $dualCitizenship){
            $name = $firstName . " " . $lastName;
            $dateOfBirth = $day . $month . $year;
            global $db;
            $number = $db->search("SELECT * FROM `apply for visa`", ['Number']);
            if(empty($number)):
                $number = 1;
            else:
                $number = $number[count($number) - 1] + 1;
            endif;
            $addressNumber = $address->addressNumber();
            $db->insert("INSERT INTO `apply for visa`(`Number`, `Name`, `Card Name`, `Gender`, `Date Of Birth`, `Email`, `Phone Number`,
                `Address`, `Dual Citizenship`) VALUES('$number', '$name', '$cardName', '$gender', '$dateOfBirth', '$email', '$phoneNumber',
                '$addressNumber', '$dualCitizenship')");
            echo "<script>open('http://localhost/bank/htML/client%20page/client.php', '_self');</script>";
        }
    }
