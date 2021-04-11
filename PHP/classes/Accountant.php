<?php
    // 'http://localhost/Bank/HTML/accountant/accountant.php'
    // 'http://puamasters.000webhostapp.com/HTML/accountant/accountant.php'
    require_once "Database.php";
    require_once "Address.php";
    require_once "Employee.php";
    require_once "Financial Transactions.php";
    require_once "Storage.php";
    require_once "Client.php";
    require_once "Bank Account.php";
    require_once "Benefits Paid.php";
    require_once "Saving Accounts.php";
    require_once "Loan.php";
    require_once "Benefits Received.php";
    require_once "Benefits Transaction.php";
    class Accountant extends Employee{
        private $date;
        public function __construct($id){
            parent::__construct($id);
            $this->date = implode(explode("/", date("Y/m/d")));
        }
        public function getDate(){
            return date('Y/m/d');
        }
        private function validAccountNumber($accountNumber){
            for($i = 0; $i < 6; $i++):
                if($accountNumber == $i):
                    return -1;
                endif;
            endfor;
            global $db;
            $data = $db->search("SELECT `Account Number` FROM `client`", ["Account Number"]);
            if(in_array($accountNumber, $data)):
                return 1;
            else:
                return -1;
            endif;
        }
        private function validMoney($totalMoney, $money){
            if($totalMoney >= $money):
                return true;
            else:
                return false;
            endif;
        }
        private function message($message){
            echo
            "<script>
                document.getElementById('errorMessage').innerHTML = '$message';
                document.getElementById('hint_id').style.display = 'block';
            </script>";
        }
        public function deposit($accountNumber, $money, $accountType){
            if($this->validAccountNumber($accountNumber) == -1):
                $this->message('Wrong Account Number');
                return;
            endif;
            if($accountType == 'Account Type'):
                $this->message('Choose Account Type');
                return;
            endif;
            //$index = $data[count($data) - 1];
            /*
            $date = date("Y/m/d"); // ->->  2019/12/04
            $date = explode("/", $date); // 2019/12/04 ->-> $date[0]="2019", $date[1]="12", $date[2]="04"
            $date = implode($date); // ($date[0]="2019", $date[1]="12", $date[2]="04") ->-> $date="20191204"
            */
            $clientAccountType = Client::getSpecificData($accountNumber, ['Account Type'])[0];
            $clientAccountType = explode(",", $clientAccountType);
            if(!in_array($accountType, $clientAccountType)):
                $this->message('Wrong Account Type');
                return;
            endif;
            if($accountType == 'Loan'):
                $this->paidLoan($accountNumber, $money);
                $this->message('Successful Operation');
                return;
            endif;
            $money = number_format((double)$money, 2, '.', '');
            $table = strtolower($accountType);
            Storage::newTransaction($this->date, $accountNumber, $money, "Deposit");
            $clientMoney = Client::getMoneyFromAccountNumber($accountNumber, $table);
            FinancialTransactions::newTransaction($this->date, $accountNumber, 0, 'Deposit', $money, $clientMoney, 0, $accountType);
            $clientMoney = $clientMoney + $money;
            Client::update($accountNumber, $table, "Money", $clientMoney);
            $money += BankAccount::getMoneyFromAccountNumber(0);
            BankAccount::update(0, $money);
            $this->message('Successful Operation');
        }
        public function withdraw($accountNumber, $money, $accountType){
            if($this->validAccountNumber($accountNumber) == -1):
                $this->message('Wrong Account Number');
                return;
            endif;
            if($accountType == 'Account Type'):
                $this->message('Choose Account Type');
                return;
            endif;
            $clientAccountType = Client::getSpecificData($accountNumber, ['Account Type'])[0];
            $clientAccountType = explode(",", $clientAccountType);
            if(!in_array($accountType, $clientAccountType)):
                $this->message('Wrong Account Type');
                return;
            endif;
            if($accountType == 'Loan'):
                $check = $this->withdrawLoan($accountNumber, $money);
                if($check == -1):
                    $this->message('No Enough Money Remaining From The Loan');
                    return;
                endif;
                $this->message('Successful Operation');
                return;
            endif;
            $table = strtolower($accountType);
            $clientMoney = Client::getMoneyFromAccountNumber($accountNumber, $table);
            if(!$this->validMoney($clientMoney, $money)):
                $this->message('No Enough Money');
                return;
            endif;
            $money = number_format((double)$money, 2, '.', '');
            Storage::newTransaction($this->date, $accountNumber, $money, "Withdraw");
            $storageMoney = BankAccount::getMoneyFromAccountNumber(0);
            $storageMoney = $storageMoney - $money;
            BankAccount::update(0, $storageMoney);
            FinancialTransactions::newTransaction($this->date, $accountNumber, 0, 'Withdraw', $money, $clientMoney, 0, $accountType);
            $clientMoney = $clientMoney - $money;
            Client::update($accountNumber, $table, "Money", $clientMoney);
            $this->message('Successful Operation');
        }
        public function transfer($accountNumber1, $accountNumber2, $accountType1, $accountType2, $money){
            $data = $this->validAccountNumber($accountNumber1);
            if($data == -1):
                $this->message('Sender Account Number Is Wrong');
                return;
            endif;
            if($accountType1 == 'Account Type'):
                $this->message('Choose Sender Account Type');
                return;
            endif;
            $data = $this->validAccountNumber($accountNumber2);
            if($data == -1):
                $this->message('Receiver Account Number Is Wrong');
                return;
            endif;
            if($accountType2 == 'Account Type'):
                $this->message('Choose Receiver Account Type');
                return;
            endif;
            global $db;
            $totalMoney = $db->search("SELECT `Money` FROM `$accountType1` WHERE `Account Number` LIKE '$accountNumber1'", ['Money']);
            if(!$this->validMoney($totalMoney, $money)):
                $this->message('No Enough Money');
                return;
            endif;
            // Storage ????
            $clientAccountType1 = Client::getSpecificData($accountNumber1, ['Account Type'])[0];
            $clientAccountType1 = explode(",", $clientAccountType1);
            if(!in_array($accountType1, $clientAccountType1)):
                $this->message('Sender Account Type Is Wrong');
                return;
            endif;
            $clientAccountType2 = Client::getSpecificData($accountNumber2, ['Account Type'])[0];
            $clientAccountType2 = explode(",", $clientAccountType2);
            if(!in_array($accountType2, $clientAccountType2)):
                $this->message('Receiver Account Type Is Wrong');
                return;
            endif;
            $money = number_format((double)$money, 2, '.', '');
            $tableAccountType1 = strtolower($accountType1);
            $tableAccountType2 = strtolower($accountType2);
            $senderMoney = Client::getMoneyFromAccountNumber($accountNumber1, $tableAccountType1);
            FinancialTransactions::newTransaction($this->date, $accountNumber1, $accountNumber2, "Transfer/Sender", $money, $senderMoney, 0, $accountType1);
            $receiverMoney = Client::getMoneyFromAccountNumber($accountNumber2, $tableAccountType2);
            FinancialTransactions::newTransaction($this->date, $accountNumber1, $accountNumber2, "Transfer/Receiver", $money, $receiverMoney, 0, $accountType2);
            $senderMoney = $senderMoney - $money;
            Client::update($accountNumber1, $tableAccountType1, "Money", $senderMoney);
            $receiverMoney = $receiverMoney + $money;
            Client::update($accountNumber2, $tableAccountType2, "Money", $receiverMoney);
            $this->message('Successful Operation');
        }
        public function loan($accountNumber, $to, $amount, $guaranteeType){
            if($this->validAccountNumber($accountNumber) == -1):
                $this->message('Wrong Account Number');
                return;
            endif;
            $clientAccountType = Client::getSpecificData($accountNumber, ['Account Type'])[0];
            $clientAccountType = explode(",", $clientAccountType);
            if(in_array('Loan', $clientAccountType)):
                $this->message('This Account Number Has A Loan');
                return;
            endif;
            $storageMoney = BankAccount::getMoneyFromAccountNumber(0);
            if($amount > $storageMoney):
                $this->message('No Enough Money In Storage');
                return;
            endif;
            $amount = number_format((double)$amount, 2, '.', '');
            //$from = implode(explode("-", $from));
            $from = $this->date;
            $to = implode(explode("-", $to));
            if($to < $from):
                $this->message('Choose Correct Date');
                return;
            endif;
            Loan::addLoan($accountNumber, $from, $to, $amount, $guaranteeType);
            $accountType = Client::getSpecificData($accountNumber, ['Account Type']);
            $accountType = implode($accountType);
            $accountType = $accountType . "," . 'Loan';
            Client::update($accountNumber, 'client', 'Account Type', $accountNumber);
            FinancialTransactions::newTransaction($this->date, $accountNumber, 0, 'New Loan', $amount, 0, 0, 'Loan');
            /*
            Storage::newTransaction($this->date, $accountNumber, $amount, 'Withdraw');
            $storageMoney = BankAccount::getMoneyFromAccountNumber(0);
            $storageMoney -= $amount;
            BankAccount::update(0, $storageMoney);
            */
            $this->message('Successful Operation');
        }
        public function addClient($firstName, $secondName, $familyName, $age, $nationalID, $address, $phoneNumber, $gender, $email, $accountType, $money){
            if($accountType == 'Account Type'):
                $this->message('Choose Account Type');
                return;
            endif;
            global $db;
            $data = $db->search("SELECT `National ID`, `Email` FROM `client`", ['National ID', 'Email']);
            if(in_array($email, $data)):
                $this->message('Email Is Already Exists');
                return;
            elseif(in_array($nationalID, $data)):
                $this->message('National ID Is Already Exists');
                return;
            endif;
            $accountNumbers = $db->search("SELECT `Account Number` FROM `client`", ["Account Number"]);
            $accountNumber = rand(100000000, 999999999);
            while(in_array($accountNumber, $accountNumbers)):
                $accountNumber = rand(100000000, 999999999);
            endwhile;
            $addressNumber = $address->addressNumber();
            $name = $firstName . " " . $secondName . " " . $familyName;
            $money = number_format((double)$money, 2, '.', '');
            Client::newClient($name, $age, $nationalID, $addressNumber, $phoneNumber, $gender, $email, $accountType, $accountNumber);
            Client::addAccount($this->date, $accountNumber, $accountType, $money);
            $pass = rand(100000000, 999999999);
            $hashedpassword = password_hash($pass, PASSWORD_DEFAULT);
            $insert = "INSERT INTO `clientlogin`(`ID`, `Username`, `Email`, `Password`) VALUES ('$accountNumber', '$accountNumber', '$email', '$hashedpassword')";
            $db->insert($insert);
            
            $to = $email;
            $subject = 'Username and Password';
            $message = '<p>Welcome to our MasterBank, thanks for registeration and choosing our bank.Bellow you will have your USERNAME and PASSWORD
            to be able to login into our system.</p>';
            $message .= '<p>Here is your USERNAME and PASSWORD : </p>';
            $message .= '<p>USERNAME : '. $accountNumber.'</p>';
            $message .= '<p>PASSWORD : '.$pass.'</p>';
            $headers = "From: Master Bank <yonehazaki@gmail.com>\r\n";
            $headers .="Reply-To: yonehazaki@gmail.com\r\n";
            $headers .= "Content-Type: text/html\r\n";
            mail($to, $subject, $message, $headers);

            Storage::newTransaction($this->date, $accountNumber, $money, "Deposit");
            $storageMoney = BankAccount::getMoneyFromAccountNumber(0);
            $storageMoney = $money + $storageMoney;
            BankAccount::update(0, $storageMoney);
            FinancialTransactions::newTransaction($this->date, $accountNumber, 0, "New Account", $money, 0, 0, $accountType);
            $this->message('Account Number Is ' . $accountNumber);
        }
        public function addAccount($accountNumber, $accountType, $money){
            if($this->validAccountNumber($accountNumber) == -1):
                $this->message('Wrong Account Number');
                return;
            endif;
            if($accountType == 'Account Type'):
                $this->message('Choose Account Type');
                return;
            endif;
            if($this->validAccountNumber($accountNumber) == -1):
                $this->message('Wrong Account Number');
                return;
            endif;
            $clientAccountType = Client::getSpecificData($accountNumber, ['Account Type'])[0];
            $clientAccountType = explode(",", $clientAccountType);
            if(in_array($accountType, $clientAccountType)):
                $this->message('This Account Number Has ' . $accountType);
                return -1;
            endif;
            $money = number_format((double)$money, 2, '.', '');
            $data =  Client::getSpecificData($accountNumber, ['Account Type']);
            $data = implode($data) . "," . $accountType;
            Client::update($accountNumber, 'client', 'Account Type', $data);
            Client::addAccount($this->date, $accountNumber, $accountType, $money);
            Storage::newTransaction($this->date, $accountNumber, $money, "Deposit");
            $storageMoney = BankAccount::getMoneyFromAccountNumber(0);
            $storageMoney = $money + $storageMoney;
            BankAccount::update(0, $storageMoney);
            FinancialTransactions::newTransaction($this->date, $accountNumber, 0, "New Account", $money, 0, 0, $accountType);
            $this->message('Successful Operation  ' . $accountType);
        }
        public function getData($accountNumber){
             $clientData = Client::getSpecificData($accountNumber, ['Account Number', 'Name', 'Age', 'Email', 'National ID', 'Address',
              'Phone Number', 'Gender', 'Account Type']);
            if(count($clientData) == 0):
                $this->message('Wrong Account Number');
                return;
            else:
                $addressData = Address::getData($clientData[5]);
                $clientData[] = $addressData[0];
                $clientData[] = $addressData[1];
                $clientData[] = $addressData[2];
            endif;
            echo
                "<script>
                    document.getElementById('table_id').style.display = 'block';
                    document.getElementById('cAccountNumber').innerHTML = '$clientData[0]';
                    document.getElementById('cName').innerHTML = '$clientData[1]';
                    document.getElementById('cAge').innerHTML = '$clientData[2]';
                    document.getElementById('cEmail').innerHTML = '$clientData[3]';
                    document.getElementById('cNationalID').innerHTML = '$clientData[4]';
                    document.getElementById('cCity').innerHTML = '$clientData[9]';
                    document.getElementById('cStreet').innerHTML = '$clientData[10]';
                    document.getElementById('cHomeNumber').innerHTML = '$clientData[11]';
                    document.getElementById('cPhoneNumber').innerHTML = '$clientData[6]';
                    document.getElementById('cGender').innerHTML = '$clientData[7]';
                    document.getElementById('cAccountType').innerHTML = '$clientData[8]';
                </script>";
        }
        public function update($accountNumber, $city = '', $street = '', $homeNumber = '', $phoneNumber = '', $email = ''){
            $clientData = Client::getSpecificData($accountNumber, ['Address', 'Email', 'Phone Number']);
            if(count($clientData) == 0):
                $this->message('Wrong Account Number');
                return;
            endif;
            $addressNumber = $clientData[0];
            $addressData = Address::getData($addressNumber);
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
                $email = $clientData[1];
            endif;
            if($phoneNumber == ''):
                $phoneNumber = $clientData[2];
            endif;
            $address = new Address($city, $street, $homeNumber);
            $addressNumber = $address->addressNumber();
            Client::update($accountNumber, 'client login', 'Email', $email);
            Client::update($accountNumber, 'client', 'Address', $addressNumber);
            Client::update($accountNumber, 'client', 'Email', $email);
            Client::update($accountNumber, 'client', 'Phone Number', $phoneNumber);
            $this->message('Successful Operation');
        }
        public function incoming(){
            $date = str_split($this->date, 1);
            $month = $date[4]. $date[5];
            $day = $date[6]. $date[7];
            // DATE TO CALCULATE INCOMING IS 01/01
            if($month . $day == '1227'):
                $profits = BankAccount::getMoneyFromAccountNumber(3);
                $loss = BankAccount::getMoneyFromAccountNumber(4);
                $incoming = BankAccount::getMoneyFromAccountNumber(5);
                $incoming += ($profits - $loss);
                if($incoming < 0):
                    $incoming *= -1;
                    $profits = BankAccount::update(3, 0);
                    $loss = BankAccount::update(4, $incoming);
                    $incoming = 0;
                endif;
                BankAccount::update(5, $incoming);
            endif;
        }
        public function profitsAndLoss(){
            $date = str_split($this->date, 1);
            $year = $date[0] . $date[1] . $date[2] . $date[3];
            $month = $date[4]. $date[5];
            $day = $date[6]. $date[7];
            //0101 START OF NEW YEAR .. IN THIS DATE WILL CALCULATE THE PROFITS AND LOSS
            $year = '2021';
            $month = '01';
            $day = '01';
            if(!BenefitsPaid::isThereAccountToCalaulateBenefits($year, $month, $day)):
                return;
            endif;
            $benefitsReceived = BankAccount::getMoneyFromAccountNumber(2);
            $benefitsPaid = BankAccount::getMoneyFromAccountNumber(1);
            $totalBenefits = $this->deliveredAllBenefitsToAllAccountNumbers($year, $month, $day);
            $benefitsReceived -= $totalBenefits;
            $benefitsPaid -= $totalBenefits;
            if($benefitsReceived < 0):
                $benefitsReceived *= -1;
                $profits = BankAccount::getMoneyFromAccountNumber(3);
                $loss = BankAccount::getMoneyFromAccountNumber(4);
                $loss += $benefitsReceived;
                BankAccount::update(4, $loss);
                if($profits < $benefitsReceived):
                    $benefitsReceived -= $profits;
                    BankAccount::update(3, 0);
                    $benefitsReceived *= -1;
                else:
                    $benefitsReceived *= -1;
                    $benefitsReceived += $profits;
                    BankAccount::update(3, $benefitsReceived);
                endif;
                if($benefitsReceived < 0):
                    $storageMoney = BankAccount::getMoneyFromAccountNumber(0);
                    $storageMoney += $benefitsReceived;
                    BankAccount::update(0, $storageMoney);
                    $benefitsReceived = 0;
                else:
                    $benefitsReceived = 0;
                endif;
            endif;
            BankAccount::update(1, $benefitsPaid);
            BankAccount::update(2, $benefitsReceived);
        }
        private function sendBenefitsForAccountNumber($accountNumber, $benefits){
            $totalBenefits = SavingAccount::getBenefitsFromAccountNumber($accountNumber);
            $totalBenefits -= $benefits;
            $benefitsReceived = BankAccount::getMoneyFromAccountNumber(2);
            $benefitsReceived -= $benefits;
            $benefits += Client::getMoneyFromAccountNumber($accountNumber, 'saving account');
            Client::update($accountNumber, 'Saving Account', 'Money', $benefits);
            Client::update($accountNumber, 'Saving Account', 'Benefits', $totalBenefits);
            BankAccount::update(2, $benefitsReceived);
        }
        private function deliveredAllBenefitsToAllAccountNumbers($year, $month, $day){
            if(!BenefitsPaid::isThereAccountToCalaulateBenefits($year, $month, $day)):
                return;
            endif;
            $data = BenefitsPaid::getDataOfAccountNumbersToDeliveredBenefits($year, $month, $day);
            $totalBenefits = 0;
            for($i = 0; $i < count($data); $i+=2):
                $this->sendBenefitsForAccountNumber($data[$i], $data[$i+1]);
                $totalBenefits += $data[$i + 1];
            endfor;
            for($i = 0; $i < count($data); $i+=2):
                Client::update($data[$i], 'saving account', 'Benefits', 0);
            endfor;
            return $totalBenefits;
        }
        private function calculateBenefitsAtMonthForAccountNumber($accountNumber, $from, $to){
            $data = SavingAccount::getMoneyFromAccountNumberAtSpecificMonth($accountNumber, $from, $to);
            if(empty($data)):
                return -1;
            endif;
            $minimumMoneyAtMonth = $data[0];
            for($j = 1; $j < count($data); $j++):
                if($data[$j] < $minimumMoneyAtMonth):
                    $minimumMoneyAtMonth = $data[$j];
                endif;
            endfor;
            return $minimumMoneyAtMonth;
        }
        private function calculateBenefitsForAllAccountNumbersAtMonth($year, $monthStart, $monthEnd){
            $dateEnd = $year . $monthEnd . '01';
            if($monthStart == '12'):
                $year--;
            endif;
            $dateStart = $year . $monthStart . '01';
            $savingAccounts = SavingAccount::getAllAccountNumbersFromTable();
            for($i = 0; $i < count($savingAccounts); $i++):
                $minimumMoneyAtMonth = $this->calculateBenefitsAtMonthForAccountNumber($savingAccounts[$i], $dateStart, $dateEnd);
                if($minimumMoneyAtMonth == -1):
                    continue;
                endif;
                $minimumMoneyAtMonth = $minimumMoneyAtMonth * 10 / 360;
                $minimumMoneyAtMonth = number_format((double)$minimumMoneyAtMonth, 2, '.', '');
                $minimumMoneyAtMonth = round($minimumMoneyAtMonth);
                $date = $year . $monthStart;
                $deliveredDate = SavingAccount::getDeliveredBenefitsDateForAccountNumber($savingAccounts[$i]);
                BenefitsPaid::addBenefitsPaidData($savingAccounts[$i], $date, $deliveredDate, $minimumMoneyAtMonth);
                BenefitsTransaction::newBenefitsDetails($this->date, $savingAccounts[$i], 'Saving', 'Paid', $minimumMoneyAtMonth);
                $benefitsPaidMoney = BankAccount::getMoneyFromAccountNumber(1);
                $benefitsPaidMoney += $minimumMoneyAtMonth;
                BankAccount::update(1, $benefitsPaidMoney);
                $minimumMoneyAtMonth += SavingAccount::getBenefitsFromAccountNumber($savingAccounts[$i]);
                Client::update($savingAccounts[$i], 'Saving Account', 'Benefits', $minimumMoneyAtMonth);
                FinancialTransactions::updateCalculatedBenefits($dateStart, $dateEnd, $savingAccounts[$i]);
            endfor;
        }
        public function benefitsPaid(){
            $date = str_split($this->date, 1);
            $year = $date[0] . $date[1] . $date[2] . $date[3];
            $month = $date[4] . $date[5];
            $day = $date[6] . $date[7];
            $monthAndDay = $date[4] . $date[5] . $date[6] . $date[7];
            $year = '2020';
            //$monthAndDay = '0401';
            $day = '01';
            $month = '02';
            if($day == '01'):
                $monthStart = $month - 1;
                if($monthStart == '00'):
                    $monthStart = '12';
                endif;
                if($monthStart >= 1 && $monthStart <= 9):
                    $monthStart = '0' . $monthStart;
                endif;
                //echo $year . " " . $monthStart . " " . $month;
                $this->calculateBenefitsForAllAccountNumbersAtMonth($year, $monthStart, $month);
            endif;
            /*
            if($monthAndDay == '0101'):
                $year--;
                $this->calculateBenefitsForAllAccountNumbersAtMonth($year, '12', '01');
            elseif($monthAndDay == '0201'):
                $this->calculateBenefitsForAllAccountNumbersAtMonth($year, '01', '02');
            elseif($monthAndDay == '0301'):
                $this->calculateBenefitsForAllAccountNumbersAtMonth($year, '02', '03');
            elseif($monthAndDay == '0401'):
                $this->calculateBenefitsForAllAccountNumbersAtMonth($year, '03', '04');
            elseif($monthAndDay == '0501'):
                $this->calculateBenefitsForAllAccountNumbersAtMonth($year, '04', '05');
            elseif($monthAndDay == '0601'):
                $this->calculateBenefitsForAllAccountNumbersAtMonth($year, '05', '06');
            elseif($monthAndDay == '0701'):
                $this->calculateBenefitsForAllAccountNumbersAtMonth($year, '06', '07');
            elseif($monthAndDay == '0801'):
                $this->calculateBenefitsForAllAccountNumbersAtMonth($year, '07', '08');
            elseif($monthAndDay == '0901'):
                $this->calculateBenefitsForAllAccountNumbersAtMonth($year, '08', '09');
            elseif($monthAndDay == '1001'):
                $this->calculateBenefitsForAllAccountNumbersAtMonth($year, '09', '10');
            elseif($monthAndDay == '1101'):
                $this->calculateBenefitsForAllAccountNumbersAtMonth($year, '10', '11');
            elseif($monthAndDay == '1201'):
                $this->calculateBenefitsForAllAccountNumbersAtMonth($year, '11', '12');
            endif;
            */
        }
        private function calculateDailyBenefitsForLoan($accountNumbers, $month, $day){
            $data = Loan::addRemainingForDataToCalculateBenefits($accountNumbers);
            for($i = 0; $i < count($data); $i+=2):
                $benefits = Loan::calculateBenefitsForLoanAccountNumber($data[$i + 1]);
                BenefitsReceived::addBenefitsLoan($month.$day, $data[$i], $benefits);
                BenefitsTransaction::newBenefitsDetails($this->date, $data[$i], 'Loan', 'Received', $benefits);
                $benefitsReceived = BankAccount::getMoneyFromAccountNumber(2);
                $benefitsReceived += $benefits;
                BankAccount::update(2, $benefitsReceived);
                $benefits += Loan::getRemainingAmountOfLoanForAccountNumber($data[$i]);
                Loan::updateRemainingLoanForAccountNumber($data[$i], $benefits);
            endfor;
        }
        private function newDate($year, $month, $day){
            $day--;
            if($day == 0):
                if($month == '01'):
                    $day = '31';
                    $month = '12';
                    $year--;
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
            return [$year, $month, $day];
        }
        public function benefitsReceived(){
            $date = str_split($this->date, 1);
            $year = $date[0] . $date[1] . $date[2] . $date[3];
            $month = $date[4] . $date[5];
            $day = $date[6] . $date[7];
            $day = '01';
            $month = '02';
            $year = '2020';
            /*
            $day--;
            if($day == 0):
                if($month == '01'):
                    $day = '31';
                    $month = '12';
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
            */
            $date = $this->newDate($year, $month, $day);
            $year = $date[0];
            $month = $date[1];
            $day = $date[2];
            $accountNumbers = Loan::getAllAccountNumbersToCalculateBenefits($year, $month, $day);
            if(empty($accountNumbers)):
                return;
            endif;
            $accountNumbers = BenefitsReceived::filterAccountNumbers($accountNumbers, $month, $day);
            if(empty($accountNumbers)):
                return;
            endif;
            $this->calculateDailyBenefitsForLoan($accountNumbers, $month, $day);
        }
        private function paidLoan($accountNumber, $money){
            Loan::paidLoanForAccountNumber($accountNumber, $money);
        }
        private function withdrawLoan($accountNumber, $money){
            $money = number_format((double)$money, 2, '.', '');
            $withdraw = Loan::withdrawLoanForAccountNumber($accountNumber, $money);
            if($withdraw == -1):
                return -1;
            endif;
            $remaining = Loan::getRemainingAmountOfLoanForAccountNumber($accountNumber);
            Storage::newTransaction($this->date, $accountNumber, $money, 'Withdraw');
            $storageMoney = BankAccount::getMoneyFromAccountNumber(0);
            $storageMoney -= $money;
            BankAccount::update(0, $storageMoney);
            FinancialTransactions::newTransaction($this->date, $accountNumber, 0, 'Withdraw Loan', $money, $remaining, 0, 'Loan');
        }
        public function warningAndReminder(){
            $date = str_split($this->date, 1);
            $year = $date[0] . $date[1] . $date[2] . $date[3];
            $month = $date[4] . $date[5];
            $day = $date[6] . $date[7];
            $day = '01';
            $month = '05';
            $year = '2020';
            $date = $this->newDate($year, $month, $day);
            $yearToCalculate = $date[0];
            $monthToCalculate = $date[1];
            $dayToCalculate = $date[2];
            $accountNumbers = Loan::getAccountNumbersToAddWarning($yearToCalculate, $monthToCalculate, $dayToCalculate, 'loan warning');
            if(empty($accountNumbers)):
                return;
            endif;
            for($i = 0; $i < count($accountNumbers); $i++):
                Loan::warning($accountNumbers[$i], $year.$month.$day);
            endfor;
            $accountNumbers = Loan::getAccountNumbersToAddWarning($year, $month, $day, 'loan reminder');
            if(empty($accountNumbers)):
                return;
            endif;
            for($i = 0; $i < count($accountNumbers); $i++):
                Loan::reminder($accountNumbers[$i], $year.$month.$day);
            endfor;
        }
    }
