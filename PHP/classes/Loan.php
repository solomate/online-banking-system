<?php
    require_once "Database.php";
    require_once "Client.php";
    require_once "Accountant.php";
    require_once "Bank Account.php";
    require_once "Financial Transactions.php";
    require_once "Current Account.php";
    require_once "Storage.php";
    class Loan {
        public static function addLoan($accountNumber, $from, $to, $amount, $guaranteeType){
            global $db;
            $number = $db->search("SELECT * FROM `loan`", ['Number']);
            if(empty($number)):
                $number = 1;
            else:
                $number = $number[count($number) - 1] + 1;
            endif;
            $db->insert("INSERT INTO `loan`(`Number`, `Account Number`, `From`, `To`, `Amount`, `Remaining`, `Withdraw`, `Guarantee`, `Paid`, `Close`)
                VALUES('$number', '$accountNumber', '$from', '$to', '$amount', '$amount', '0', '$guaranteeType', '0', '0')");
        }
        public static function updatePaid($accountNumber){
            global $db;
            $db->update("UPDATE `loan` SET `Paid` = '1' WHERE `Account Number` LIKE '$accountNumber'");
        }
        public static function updateWithdraw($accountNumber, $money){
            global $db;
            $db->update("UPDATE `loan` SET `Withdraw` = '$money' WHERE `Account Number` LIKE '$accountNumber'");
        }
        public static function paidLoanForAccountNumber($accountNumber, $moneyToDeposit){
            $date = implode(explode("/", date("Y/m/d")));

            $paid = Loan::getRemainingAmountOfLoanForAccountNumber($accountNumber);
            $remainingToPaid = Loan::getRemainingAmountOfLoanForAccountNumber($accountNumber);
            $amount = self::getAmountOfLoanForAccountNumber($accountNumber);
            $storageMoney = BankAccount::getMoneyFromAccountNumber(0);
            $remainingMoneyFromLoan = Loan::getAmountOfLoanForAccountNumber($accountNumber) - Loan::getWithdrawOfLoanForAccountNumber($accountNumber);
            $overMoneySentToDeposit = 0;
            $createAccount = 0;
            $money = $moneyToDeposit;
            if($moneyToDeposit == $paid):
                $paid = 0;
                $money = $moneyToDeposit;
            elseif($moneyToDeposit > $paid):
                $money = $paid;
                $overMoneySentToDeposit = $moneyToDeposit - $paid;
                $paid = 0;
            endif;
            if(($remainingMoneyFromLoan > 0 || $overMoneySentToDeposit != 0) && $paid == 0):
                $createAccount = 1;
                self::updatePaid($accountNumber);
            endif;
            FinancialTransactions::newTransaction($date, $accountNumber, 0, 'Paid Loan', $money, $remainingToPaid, 0, 'Loan');
            Storage::newTransaction($date, $accountNumber, $money, 'Deposit');
            Loan::newLoanPaidDetails($date, $accountNumber, $money);
            $remainingToPaid -= $money;
            self::updateRemainingLoanForAccountNumber($accountNumber, $remainingToPaid);
            if($createAccount == 1):
                self::updateWithdraw($accountNumber, $amount);
                FinancialTransactions::newTransaction($date, 0, $accountNumber, 'Send Remaining Loan', $remainingMoneyFromLoan + $overMoneySentToDeposit, $storageMoney, 0, 'Loan');
                $storageMoney -= ($remainingMoneyFromLoan + $overMoneySentToDeposit);
                BankAccount::update(0, $storageMoney);
                if(Client::hasCurrentAccount($accountNumber) == 1):
                    $currentAccountMoney = Client::getMoneyFromAccountNumber($accountNumber, 'current account');
                    FinancialTransactions::newTransaction($date, $accountNumber, 0, 'Received Remaining Loan', $remainingMoneyFromLoan + $overMoneySentToDeposit, $currentAccountMoney, 0, 'Current Account');
                    $currentAccountMoney += $remainingMoneyFromLoan + $overMoneySentToDeposit;
                    Client::update($accountNumber, 'current account', 'Money', $currentAccountMoney);
                else:
                    Client::addAccount($date, $accountNumber, 'Current Account', $remainingMoneyFromLoan + $overMoneySentToDeposit);
                    FinancialTransactions::newTransaction($date, $accountNumber, 0, 'Received Remaining Loan', $remainingMoneyFromLoan + $overMoneySentToDeposit, 0, 0, 'Current Account');
                endif;
                $accountType = Client::getSpecificData($accountNumber, ['Account Type'])[0];
                $accountType = explode(",", $accountType);
                $type = [];
                for($i = 0; $i < count($accountType); $i++):
                    if($accountType[$i] == 'Saving Account' || $accountType[$i] == 'Current Account'):
                        $type[] = $accountType[$i] . ',';
                    endif;
                endfor;
                if(!in_array('Current Account', $type)):
                    $type[] = ',Current Account';
                endif;
                $type = implode($type);
                Client::update($accountNumber, 'client', 'Account Type', $type);
            endif;
        }
        public static function warning($accountNumber, $date){
            global $db;
            $number = $db->search("SELECT * FROM `loan warning`", ['Number']);
            if(empty($number)):
                $number = 1;
            else:
                $number = $number[count($number) - 1] + 1;
            endif;
            $warning = 'You Did Not Paid A Part Of Your Loan Last Month';
            $db->insert("INSERT INTO `loan warning`(`Number`, `Date`, `Account Number`, `Warning`, `Seen`)
                VALUES('$number', '$date', '$accountNumber', '$warning', '0')");
        }
        public static function reminder($accountNumber, $date){
            global $db;
            $number = $db->search("SELECT * FROM `loan reminder`", ['Number']);
            if(empty($number)):
                $number = 1;
            else:
                $number = $number[count($number) - 1] + 1;
            endif;
            $reminderMonth = $db->search("SELECT `To` FROM `loan` WHERE `Account Number` LIKE '$accountNumber'", ['To'])[0];
            $reminderMonth = str_split($reminderMonth, 1);
            $year = $reminderMonth[0] . $reminderMonth[1] . $reminderMonth[2] . $reminderMonth[3];
            $month = $reminderMonth[4]. $reminderMonth[5];
            $date2 = str_split($date, 1);
            $year2 = $date2[0] . $date2[1] . $date2[2] . $date2[3];
            $month2 = $date2[4]. $date2[5];
            $year -= $year2;
            $month -= $month2;
            $year *= 12;
            $reminderMonth = $year + $month;

            $reminderMessaage = 'You Have ' . $reminderMonth . ' Months To Paid Your Loan';
            $db->insert("INSERT INTO `loan reminder`(`Number`, `Date`, `Account Number`, `Reminder`)
                VALUES('$number', '$date', '$accountNumber', '$reminderMessaage')");
        }
        public static function getWarning($accountNumber){
            global $db;
            $data = $db->search("SELECT `Date`, `Warning` FROM `loan warning` WHERE `Account Number` LIKE '$accountNumber'", ['Date', 'Warning']);
            $db->update("UPDATE `loan warning` SET `Seen` = '1' WHERE `Account Number` LIKE '$accountNumber' AND `Seen` LIKE '0'");
            for($i = 0; $i < count($data); $i+=2):
                $date = $data[$i];
                $date = str_split($date, 1);
                $date = $date[6].$date[7] . "/" . $date[4].$date[5] . "/" . $date[0].$date[1].$date[2].$date[3];
                $data[$i] = $date;
            endfor;
            return $data;
        }
        public static function getReminder($accountNumber){
            global $db;
            $data = $db->search("SELECT `Date`, `Reminder` FROM `loan reminder` WHERE `Account Number` LIKE '$accountNumber'", ['Date', 'Reminder']);
            for($i = 0; $i < count($data); $i+=2):
                $date = $data[$i];
                $date = str_split($date, 1);
                $date = $date[6].$date[7] . "/" . $date[4].$date[5] . "/" . $date[0].$date[1].$date[2].$date[3];
                $data[$i] = $date;
            endfor;
            return $data;
        }
        public static function addRemainingForDataToCalculateBenefits($accountNumbers){
            $data = [];
            for($i = 0; $i < count($accountNumbers); $i++):
                $data[] = $accountNumbers[$i];
                $data[] = self::getRemainingAmountOfLoanForAccountNumber($accountNumbers[$i]);
            endfor;
            return $data;
        }
        public static function getRemainingAmountOfLoanForAccountNumber($accountNumber){
            global $db;
            return $db->search("SELECT `Remaining` FROM `loan` WHERE `Account Number` LIKE '$accountNumber'", ['Remaining'])[0];
        }
        public static function getAmountOfLoanForAccountNumber($accountNumber){
            global $db;
            return $db->search("SELECT `Amount` FROM `loan` WHERE `Account Number` LIKE '$accountNumber'", ['Amount'])[0];
        }
        public static function getWithdrawOfLoanForAccountNumber($accountNumber){
            global $db;
            return $db->search("SELECT `Withdraw` FROM `loan` WHERE `Account Number` LIKE '$accountNumber'", ['Withdraw'])[0];
        }
        public static function calculateBenefitsForLoanAccountNumber($amount){
            $amount = $amount * 1 / 360;
            $amount = number_format((double)$amount, 2, '.', '');
            return round($amount);
        }
        public static function updateRemainingLoanForAccountNumber($accountNumber, $amount){
            global $db;
            $db->update("UPDATE `loan` SET `Remaining` = '$amount' WHERE `Account Number` LIKE '$accountNumber'");
        }
        public static function getAllAccountNumbersToCalculateBenefits($year, $month, $day){
            global $db;
            $data = $db->search("SELECT `Account Number`, `From` FROM `loan` WHERE `Paid` LIKE '0' AND `Close` LIKE '0'", ['Account Number', 'From']);
            $accountNumbers = [];
            for($i = 1; $i < count($data); $i+=2):
                $date = $data[$i];
                if($date > $year.$month.$day):
                    continue;
                endif;
                $accountNumbers[] = $data[$i - 1];
            endfor;
            return $accountNumbers;
        }
        public static function withdrawLoanForAccountNumber($accountNumber, $money){
            global $db;
            $amount = self::getAmountOfLoanForAccountNumber($accountNumber);
            $withdraw = self::getWithdrawOfLoanForAccountNumber($accountNumber);
            $withdraw += $money;
            if($withdraw > $amount):
                return -1;
            endif;
            $db->update("UPDATE `loan` SET `Withdraw` = '$withdraw' WHERE `Account Number` LIKE '$accountNumber'");
            return $withdraw;
        }
        public static function newLoanPaidDetails($date, $accountNumber, $money){
            global $db;
            $number = $db->search("SELECT * FROM `loan paid`", ['Number']);
            if(empty($number)):
                $number = 1;
            else:
                $number = $number[count($number) - 1] + 1;
            endif;
            $db->insert("INSERT INTO `loan paid`(`Number`, `Date`, `Account Number`, `Money`) VALUES('$number', '$date', '$accountNumber', '$money')");
        }
        public static function getAccountNumbersToAddWarning($year, $month, $day, $table){
            global $db;
            $date = $month . $day;
            $data = $db->search("SELECT `Account Number`, `From` FROM `loan`", ['Account Number', 'From']);
            $accountNumbers = [];
            for($i = 1; $i < count($data); $i+=2):
                $loanDate = str_split($data[$i], 4);
                $dayAndMonth = str_split($loanDate[1], 2);
                if($dayAndMonth[1] == $day &&  $loanDate[0].$loanDate[1] != $year.$month.$day):
                    $accountNumbers[] = $data[$i - 1];
                endif;
            endfor;
            if($table == 'loan warning'):
                $accountNumbers = self::filterAccountNumbersToAddWarningAccountNumbersDidntPaidPartOfLoanAtMonth($accountNumbers, $year, $month, $day);
            endif;

            //$accountNumbers = self::filterAccountNumbersToAddWarning1($accountNumbers, $year, $month, $day);
            $accountNumbers = self::filterAccountNumbersToAddWarning($accountNumbers, $year, $month, $day, $table);
            return $accountNumbers;
        }
        public static function newDate($year, $month){
            $month--;
            if($month == '00'):
                $month = '12';
                $year--;
            endif;
            if($month >= 1 && $month <= 9):
                $month = '0' . $month;
            endif;
            return [$year, $month];
        }
        public static function filterAccountNumbersToAddWarningAccountNumbersDidntPaidPartOfLoanAtMonth($data, $year, $month, $day){
            global $db;
            $date = self::newDate($year, $month);
            $date = $date[0] . $date[1];
            $dateStart = $date . '01';
            $dateEnd = $date . '31';
            $accountNumbersPaidPartOfLoan = $db->search("SELECT `Account Number` FROM `loan paid` WHERE `Date` >= '$dateStart' AND `Date` <= '$dateEnd'", ['Account Number']);
            $accountNumbers = [];
            for($i = 0; $i < count($data); $i++):
                if(!in_array($data[$i], $accountNumbersPaidPartOfLoan)):
                    $accountNumbers[] = $data[$i];
                endif;
            endfor;
            return $accountNumbers;
        }
        /*
        public static function filterAccountNumbersToAddWarning1($data, $year, $month, $day){
            global $db;
            $accountNumbers = [];
            for($i = 0; $i < count($data); $i++):
                $date = $db->search("SELECT `From` FROM `loan` WHERE `Account Number` LIKE '$data[$i]'",['From'])[0];
                if($date > $year.$month.$day):
                    continue;
                endif;
                $accountNumbers[] = $data[$i];
            endfor;
            return $accountNumbers;
        }
        */
        public static function filterAccountNumbersToAddWarning($data, $year, $month, $day, $table){
            global $db;
            $date = $year . $month . $day;
            $hasWarning = $db->search("SELECT `Account Number` FROM `$table` WHERE `Date` LIKE '$date'", ['Account Number']);
            if(empty($hasWarning)):
                return $data;
            endif;
            $accountNumbers = [];
            for($i = 0; $i < count($data); $i++):
                if(!in_array($data[$i], $hasWarning)):
                    $accountNumbers[] = $hasWarning[$i];
                endif;
            endfor;
            return $accountNumbers;
        }
        public static function getAccountNumbersToAddReminder($year, $month, $day, $table){
            global $db;
            $date = $month . $day;
            $data = $db->search("SELECT `Account Number`, `From` FROM `loan`", ['Account Number', 'From']);
            $accountNumbers = [];
            for($i = 1; $i < count($data); $i+=2):
                $loanDate = str_split($data[$i], 4);
                $dayAndMonth = str_split($loanDate[1], 2);
                if($dayAndMonth[1] == $day &&  $loanDate[0].$loanDate[1] != $year.$month.$day):
                    $accountNumbers[] = $data[$i - 1];
                endif;
            endfor;
            $accountNumbers = Loan::filterAccountNumbersToAddWarning($accountNumbers, $year, $month, $day, 'loan reminder');

            return $accountNumbers;
        }
    }
