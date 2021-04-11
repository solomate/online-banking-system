<?php
    require_once "Database.php";
    require_once "Employee.php";
    require_once "Bank Account.php";
    class FinancialTransactions extends Employee {
        public function __construct($id)
        {
            parent::__construct($id);
        }
        public static function newTransaction($date, $accountNumber1, $accountNumber2, $transaction, $amount, $money, $calculatedBenefits, $accountType, $currentMoney = 0){
            global $db;
            $transactionNumbers = $db->search("SELECT * FROM `financial transactions`", ["Number"]);
            if(empty($transactionNumbers)):
                $number = 1;
            else:
                $number = $transactionNumbers[count($transactionNumbers) - 1] +1;
            endif;
            if($transaction == 'Withdraw' || $transaction == 'Transfer/Sender' || $transaction == 'Paid Loan' || $transaction == 'Send Remaining Loan'):
                $amount *= -1;
            endif;
            $currentMoney = $money + $amount;

            if($transaction == 'Withdraw Loan'):
                $currentMoney = $money;
            endif;
            $db->insert("INSERT INTO `financial transactions`(`Number`, `Date`, `Account Number 1`, `Account Number 2`, `Transition`, `Type`, `Amount`, `Money`, `Current Money`, `Calculated Benefits`)
                 VALUES('$number', '$date', '$accountNumber1', '$accountNumber2', '$transaction', '$accountType', '$amount', '$money', '$currentMoney', '$calculatedBenefits')");
        }
        public static function getTransactions(){
            global $db;
            $transactions = $db->search("SELECT * FROM `financial transactions`", ['Number', 'Date', 'Account Number 1', 'Type', 'Account Number 2', 'Transition', 'Amount']);
            return $transactions;
        }
        public static function updateCalculatedBenefits($dateStart, $dateEnd, $accountNumber){
            global $db;
            $db->update("UPDATE `financial transactions` SET `Calculated Benefits` = '1' WHERE `Account Number 1` LIKE '$accountNumber' AND `Type` LIKE 'Saving Account' AND `Date` >= '$dateStart' AND `Date` < '$dateEnd'");
        }
        private function message($message){
            echo
                "<script>
                    document.getElementById('errorMessage').innerHTML = '$message';
                    document.getElementById('hint_id').style.display = 'block';
                </script>";
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
            while($i != (count($data)) ):
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
        public function getSalary($id){
            global $db;
            $salary = $db->search("SELECT `Salary` FROM `employee` WHERE `ID` LIKE '$id'", ['Salary']);
            if(empty($salary)):
                $this->message('Wrong ID');
                return;
            endif;
            $salary = implode($salary);
            echo "<script>document.getElementById('displaySalary').innerHTML = '$salary';</script>";
            echo "<script>document.getElementById('salary').style.display = 'block';</script>";
        }
    }
