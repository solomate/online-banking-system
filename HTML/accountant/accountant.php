<?php
  // 'http://localhost/Bank/HTML/accountant/accountant.php'
  // 'http://puamasters.000webhostapp.com/HTML/accountant/accountant.php'
  session_start();
  /*
  if($_SESSION['successfulLogin'] == 0):
    echo "<script>alert('You Do Not Able To See This Page');</script>";
    exit();
  endif;
  */
  require_once "../../PHP/classes/Accountant.php";
  require_once "../../PHP/classes/Address.php";
  require_once "../../PHP/classes/Employee.php";
  //$id = $_SESSION['userId'];
  $id = 3;
  $accountant = new Accountant($id);
  $accountant->benefitsReceived();
  $accountant->benefitsPaid();
  $accountant->profitsAndLoss();
  $accountant->incoming();
  $accountant->warningAndReminder();
?>
<!DOCTYPE html>
<html Lang="en">
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="normalize.css">
    <link rel="stylesheet" href="style.css">
  </head>
	<body>
    <div id="hint_id" class="class_hint">
        <div class="modal-content">
            <div class="myform">
              <a class="close" onclick="closeForm('hint_id')">&times;</a>
              <form class="hint_form">
                <h2>Hint</h2>
                <span class="hintSpan" id="errorMessage"></span>
              </form>
            </div>
          </div>
    </div>
		<div class="about">

            <span class="hr">Accountant</span>
        </div>
        <div class="navigation">
        		<div class="logo">
        			<img class="banklogo" src="images/logo.png" width="60px;">
        		</div>
        		<div class="bankname">
        			<span class="master"><span class="m">M</span>aster <span class="b">B</span>ank </span>
        		</div>
        		<ul class="others">
        			<button type="submit" onclick="location.href='http://localhost/Bank/Login%20System/system/logout.sys.php'">Log out</button>
        		</ul>
        </div>
        <div class="container">
        	<div class ="form">
        		<button  onclick="op('new_id')"> New Client / Account </button>
        		<button  onclick="op('deposit_id')"> Deposit </button>
        		<button  onclick="op('draw_id')"> WithDraw </button>
        		<button  onclick="op('Transfer_id')"> Transfer </button>
        		<button  onclick="op('table_id')"> Get Data </button>
        		<button  onclick="op('Update_data')"> Update Data </button>
            <button  onclick="op('loan_id')"> Loan </button>
            <button  onclick="op('comment_id')"> Comment </button>
            <form method="GET" style="padding: 0px; margin: 0px; background-color: unset; box-shadow: unset; display: inline">
              <input style="display: none" name="checkClickedGetTask" id="checkClickedGetTask" value="notClicked">
              <button type="submit" value=" Get Task " onclick="openTask()" style="position: relative;"> Get Task
                <span id="hintTask" class="notificationHint" style="position: absolute; right: 15%; bottom: 3.5%; font-size: 45px; color: red; visibility: hidden;">!</span>
                <span id="taskNotDone" class="notificationHint" style="position: absolute; right: 5%; top: 3.5%; font-weight: bold; font-size: 25px; color: red; visibility: hidden;"></span>
              </button>
            </form>
            <?php
              $accountant->displayHint();
            ?>
    	</div>
        <div class="report">
        <button onclick="op('report_id')">
          <span class="myreport">Report</span>
        </button>
      </div>
      <div id="new_id" class="new_form">
            <div class="modal-content">
                <div class="myform">
                  <a class="close" onclick="closeForm('new_id')">&times;</a>
                  <form class="newDeposit">
                    <h2>New Client / Account</h2>
                    <input class="btn1" type="button" onclick="openNewClientForm()" value="New Client">
                    <input class="btn1" type="button" onclick="openNewAccountForm()" style="margin-top: 15px" value="New Account">
                  </form>
                </div>
            </div>
        </div>
        <div id="new_client" class="newClient_form">
            <div class="modal-content">
                <div class="myform">
                  <a class="close" onclick="closeForm('new_client')">&times;</a>
                    <form class="newClient" method="POST">
                      <h2>New Client</h2>
                      <input type="text" name="firstName" placeholder="First Name" required>
                      <input type="text" name="secondName" placeholder="Second Name" required>
                      <input type="text" name="familyName" placeholder="Family Name" required>
                      <input type="number" name="age" placeholder="Age" min="18" required>
                      <input type="email" name="email" placeholder="Email" required>
                      <input type="number" name="nationalID" placeholder="National Id" min="0" required>
                      <input type="text" name="city" placeholder="City" style="width: 43%;" required>
                      <input type="text" name="street" placeholder="Street" style="width: 43%;" required>
                      <input type="number" name="homeNumber" placeholder="Home Number" min="0" style="width: 50%;" required>
                      <input type="number" name="phoneNumber" min="0" placeholder="Phone Number" required>
                      <select name="gender" required>
                          <option selected>Gender</option>
                          <option>Male</option>
                          <option>Female</option>
                      </select>
                      <select name="accountType" required>
                          <option selected>Account Type</option>
                          <option>Saving Account</option>
                          <option>Current Account</option>
                      </select>
                      <input type="number" name="money" placeholder="Money" min="1000" required>
                      <button class="mysubmit">submit</button>
                    </form>
                    <?php
                      if(isset($_POST['city']) && isset($_POST['street']) && isset($_POST['homeNumber']) && isset($_POST['firstName']) && isset($_POST['secondName']) && isset($_POST['familyName'])
                        && isset($_POST['age']) && isset($_POST['nationalID']) && isset($_POST['phoneNumber']) && isset($_POST['gender'])
                        && isset($_POST['email']) && isset($_POST['accountType']) && isset($_POST['money'])):

                        $address = new Address($_POST['city'], $_POST['street'], $_POST['homeNumber']);
                        $accountant->addClient($_POST['firstName'], $_POST['secondName'], $_POST['familyName'], $_POST['age'], $_POST['nationalID'], $address, $_POST['phoneNumber'], $_POST['gender'],
                          $_POST['email'], $_POST['accountType'], $_POST['money']);
                      endif;
                    ?>
                </div>
            </div>
        </div>
        <div id="newAccount_id" class="newAccount_form">
            <div class="modal-content">
                <div class="myform">
                  <a class="close" onclick="closeForm('newAccount_id')">&times;</a>
                  <form class="newAccount" method="POST">
                    <h2>New Account</h2>
                    <input type="number" name="accountNumberNewAccount" min="0" placeholder="Account Number" required>
                    <select name="accountTypeNewAccount" required>
                      <option selected>Account Type</option>
                      <option>Saving Account</option>
                      <option>Current Account</option>
                    </select>
                    <input type="number" name="amountNewAccount" min="0" placeholder="Money" required>
                    <button class="mysubmit">submit</button>
                  </form>
                  <?php
                    if(isset($_POST['accountNumberNewAccount'])):
                      $accountant->addAccount($_POST['accountNumberNewAccount'], $_POST['accountTypeNewAccount'], $_POST['amountNewAccount']);
                    endif;
                  ?>
                </div>
            </div>
        </div>
        <div id="deposit_id" class="deposit_form">
            <div class="modal-content">
                <div class="myform">
                  <a class="close" onclick="closeForm('deposit_id')">&times;</a>
                  <form class="newDeposit newAccount" method="POST">
                    <h2>Deposit</h2>
                    <input type="number" name="accountNumberDeposit" min="0" placeholder="Account Number" required>
                    <input type="number" name="amountDeposit" placeholder="Amount" min="0" required>
                    <select id="accountType" name="accountTypeDeposit" required>
                      <option selected>Account Type</option>
                      <option>Saving Account</option>
                      <option>Current Account</option>
                      <option>Loan</option>
                    </select>
                    <button class="mysubmit">submit</button>
                  </form>
                  <?php
                      if(isset($_POST['accountNumberDeposit']) && isset($_POST['amountDeposit']) && $_POST['accountTypeDeposit'] != 'Account Type'):
                        $accountant->deposit($_POST['accountNumberDeposit'], $_POST['amountDeposit'], $_POST['accountTypeDeposit']);
                      endif;
                  ?>
                </div>
            </div>
        </div>
        <div id="loan_id" class="deposit_form">
          <div class="modal-content">
            <div class="myform">
              <a class="close" onclick="closeForm('loan_id')">&times;</a>
              <form class="newAccount" method="POST">
                <h2>Loan</h2>
                <input type="accountNumberLoan" name="accountNumberLoan" min="0" placeholder="Account Number" required>
                <!--input type="date" name="dateFrom" min="0" placeholder="From" required-->
                <input id="dateStart" type="text" readonly>
                <input type="date" name="dateTo" min="0" placeholder="To" required>
                <input type="loanAmount" name="loanAmount" min="0" placeholder="Amount" required>
                <select name="guarantee">
                  <option selected>Guarantee Type</option>
                  <option>Personal Guarantee</option>
                  <option>Goods Guarantee</option>
                  <option>Property Guarantee</option>
                </select>
                <button class="mysubmit">Submit</button>
              </form>
              <?php
                $date = $accountant->getDate();
                echo "<script>document.getElementById('dateStart').value = '$date';</script>";
                if(isset($_POST['accountNumberLoan']) && isset($_POST['dateTo']) &&
                 isset($_POST['loanAmount']) && $_POST['guarantee'] != 'Guarantee Type'):
                      $accountant->loan($_POST['accountNumberLoan'], $_POST['dateTo'], $_POST['loanAmount'], $_POST['guarantee']);
                endif;
              ?>
            </div>
          </div>
        </div>
        <div id="draw_id" class="draw_form">
            <div class="modal-content">
                <div class="myform">
                  <a class="close" onclick="closeForm('draw_id')">&times;</a>
                  <form class="newDraw newAccount" method="POST">
                      <h2>With Draw</h2>
                      <input type="number" name="accountNumberDraw" min="0" placeholder="Account Number" required>
                      <input type="number" name="amountDraw" placeholder="Amount" min="0" required>
                      <select id="accountType" name="accountTypeDraw" required>
                        <option selected>Account Type</option>
                        <option>Saving Account</option>
                        <option>Current Account</option>
                        <option>Loan</option>
                      </select>
                      <button class="mysubmit">submit</button>
                  </form>
                  <?php
                    if(isset($_POST['accountNumberDraw']) && isset($_POST['amountDraw']) && $_POST['accountTypeDraw'] != 'Account Type'):
                      $accountant->withdraw($_POST['accountNumberDraw'], $_POST['amountDraw'], $_POST['accountTypeDraw']);
                    endif;
                  ?>
                </div>
            </div>
        </div>
        <div id="Transfer_id" class="Transfer_form">
            <div class="modal-content">
                <div class="myform">
                  <a class="close" onclick="closeForm('Transfer_id')">&times;</a>
                  <form class="transfer_ newAccount" method="POST">
                    <h2>Transfer</h2>
                    <input type="number" name="senderAccountNumber" min="0" placeholder="Sender Account Number" required>
                    <select id="accountType" name="senderAccountType" required>
                      <option selected>Account Type</option>
                      <option>Saving Account</option>
                      <option>Current Account</option>
                    </select>
                    <input type="number" name="receiverAccountNumber" min="0" placeholder="Receiver Account Number" required>
                    <select id="accountType" name="receiverAccountType" required>
                      <option selected>Account Type</option>
                      <option>Saving Account</option>
                      <option>Current Account</option>
                    </select>
                    <input type="number" name="amountTransfer" placeholder="Amount" min="0" required>
                    <button class="mysubmit">submit</button>
                  </form>
                  <?php
                    if(isset($_POST['senderAccountNumber']) && isset($_POST['receiverAccountNumber']) && isset($_POST['amountTransfer']) && $_POST['senderAccountType'] != 'Account Type' && $_POST['receiverAccountType'] != 'Account Type'):
                      $accountant->transfer($_POST['senderAccountNumber'], $_POST['receiverAccountNumber'], $_POST['senderAccountType'], $_POST['receiverAccountType'], $_POST['amountTransfer']);
                    endif;
                  ?>
                </div>
            </div>
        </div>
        <div id="Update_data" class="update_form">
            <div class="modal-content">
                <div class="myform">
                  <a class="close" onclick="closeForm('Update_data')">&times;</a>
                  <form class="data_" method="POST">
                      <h2>Update Data</h2>
                      <input type="number" name="uAccountNumber" min="0" placeholder="Account Number" required>
                      <input type="text" name="uCity" placeholder="Your City" style="width: 43%;" >
                      <input type="text" name="uStreet" placeholder="Your street" style="width: 43%;" >
                      <input type="number" name="uHomeNumber" placeholder="Home Number" min="0" style="width: 50%;" >
                      <input type="number" name="uPhoneNumber" placeholder="Phone Number" >
                      <input type="email" name="uEmail" placeholder="Your Email" >
                      <button class="mysubmit">submit</button>
                  </form>
                  <?php
                    if(isset($_POST['uAccountNumber'])):
                      @$accountant->update($_POST['uAccountNumber'], $_POST['uCity'], $_POST['uStreet'], $_POST['uHomeNumber'], $_POST['uPhoneNumber'], $_POST['uEmail']);
                    endif;
                  ?>
                </div>
            </div>
        </div>
         <div id="comment_id" class="comment_form">
            <div class="modal-content">
                <div class="myform">
                  <a class="close" onclick="closeForm('comment_id')">&times;</a>
                  <form class="newComment" method="POST">
                      <h2>Comment</h2>
                      <textarea name="comment" rows="15" placeholder="Your Comment"></textarea>
                      <button class="mysubmit">submit</button>
                  </form>
                  <?php
                    if(isset($_POST['comment'])):
                      $accountant->addComment($_POST['comment'], 'http://localhost/Bank/HTML/accountant/accountant.php');
                    endif;
                  ?>
                </div>
            </div>
        </div>
        <div id="task_id" class="task_form">
            <div class="modal-content">
                <div class="myform">
                  <a class="close" onclick="closeForm('task_id')">&times;</a>
                  <form class="newTask" method="POST">
                    <h2>Get Task</h2>
                    <table>
                      <th>Date</th>
                      <th>Task</th>
                      <th>Done</th>
                      <?php
                        $url = $_SERVER['REQUEST_URI'];
                        @$url = explode("?", $url)[1];
                        if($url == 'checkClickedGetTask=clicked'):
                          $accountant->getTask('http://localhost/Bank/HTML/accountant/accountant.php', 'task_id');
                        endif;
                      ?>
                    </table>
                    <button class="mysubmit">submit</button>
                  </form>
                </div>
            </div>
        </div>
        <div id="table_id" class="table_form">
            <div class="modal-content">
                <div class="myform">
                  <a class="close" onclick="closeForm('table_id')">&times;</a>
                  <form class="newTable" method="POST">
                      <h2>Get Data</h2>
                      <input type="number" name="accountNumberData" min="0" placeholder="Account Number" required>
                      <table>
                          <tr>
                            <th>Account Number</th>
                            <td id="cAccountNumber"></td>
                          </tr>
                          <tr>
                            <th>Name</th>
                            <td id="cName"></td>
                          </tr>
                          <tr>
                            <th>Age</th>
                            <td id="cAge"></td>
                          </tr>
                          <tr>
                            <th>Email</th>
                            <td id="cEmail"></td>
                          </tr>
                          <tr>
                            <th>National Id</th>
                            <td id="cNationalID"></td>
                          </tr>
                          <tr>
                            <th>City</th>
                            <td id="cCity"></td>
                          </tr>
                          <tr>
                            <th>Street</th>
                            <td id="cStreet"></td>
                          </tr>
                          <tr>
                            <th>Home Number</th>
                            <td id="cHomeNumber"></td>
                          </tr>
                          <tr>
                            <th>Phone Number</th>
                            <td id="cPhoneNumber"></td>
                          </tr>
                          <tr>
                            <th>Gender</th>
                            <td id="cGender"></td>
                          </tr>
                          <tr>
                            <th>Account Type</th>
                            <td id="cAccountType"></td>
                          </tr>
                      </table>
                      <button class="mysubmit">submit</button>
                  </form>
                  <?php
                    if(isset($_POST['accountNumberData'])):
                      $accountant->getData($_POST['accountNumberData']);
                    endif;
                  ?>
                </div>
            </div>
        </div>
        <div id="report_id" class="class_report">
            <div class="modal-content">
                <div class="myform">
                    <a class="close" onclick="closeForm('report_id')">&times;</a>
                    <form class="report_form" method="POST">
                      <h2>Report</h2>
                      <input type="checkbox" name="box1" value="Slow Site Speed">
                      <label>Slow Site Speed</label><br>
                      <input type="checkbox" name="box2" value="Links and Site Forms not working">
                      <label>Links and Site Forms not working</label><br>
                      <input type="checkbox" name="box3" value="Compatibility Problems">
                      <label>Compatibility Problems</label><br>
                      <input id="myCheck" type="checkbox" onclick="myFunction()">
                      <label>Others</label><br><br>
                      <textarea id="textarea_" name="box4" rows="3" style="display: none;"></textarea><br><br>
                      <button class="mysubmit">Submit</button>
                  </form>
                  <?php
                      if(isset($_POST['box1'])):
                        $accountant->addReport('Slow Site Speed', 'http://localhost/Bank/HTML/accountant/accountant.php');
                      endif;
                      if(isset($_POST['box2'])):
                        $accountant->addReport('Links and Site Forms not working', 'http://localhost/Bank/HTML/accountant/accountant.php');
                      endif;
                      if(isset($_POST['box3'])):
                        $accountant->addReport('Compatibility Problems', 'http://localhost/Bank/HTML/accountant/accountant.php');
                      endif;
                      if(isset($_POST['box4']) && $_POST['box4'] != ''):
                        $accountant->addReport($_POST['box4'], 'http://localhost/Bank/HTML/accountant/accountant.php');
                      endif;
                  ?>
                 </div>
             </div>
        </div>

    <script type="text/javascript">
            function op(id) {
                document.getElementById(id).style.display = 'block';
            }
            function closeForm(id) {
             document.getElementById(id).style.display = "none";
             open('http://localhost/Bank/HTML/accountant/accountant.php', '_self');
            }
            function myFunction() {
                var checkBox = document.getElementById("myCheck");
                var text = document.getElementById("textarea_");
                if (checkBox.checked == true){
                  text.style.display = "block";
                } else {
                   text.style.display = "none";
                }
            }
            function openNewClientForm(){
              document.getElementById('new_id').style.display = 'none';
              document.getElementById('new_client').style.display = 'block';
            }
            function openNewAccountForm(){
              document.getElementById('new_id').style.display = 'none';
              document.getElementById('newAccount_id').style.display = 'block';
            }
            function openTask(){
              document.getElementById('checkClickedGetTask').value = 'clicked';
            }
            //var option = document.createElement('option');
            //var text  = document.createTextNode('Saving Account');
            //option.appendChild(text);
            //document.getElementById('accountType').appendChild(option);
            
        </script>
	</body>
</html>
<?php
    //$accountant->displayHint();
    /*
    $realTime = 0;
    $time = $realTime;
    $first = 1;
    function t1(){
      global $time, $realTime;
      if($time == $realTime):
        global $accountant;
        $accountant->displayHint();
        $time += 500;
        t1();
      else:
        $realTime++;
        echo $realTime . $time . "<br>";
        sleep(1);
        t1();
      endif;
    }
    t1();
    */
    /*
    $realTime = date('h:i:s');
    $realTime  = explode(":", $realTime)[2];
    $time = $realTime + 1;
    function x(){
      global $time, $realTime, $accountant;
      if($time == $realTime):
        $accountant->displayHint();
        $time += 1;
        if($time > 60):
          $time = 1;
        endif;
      else:
        //sleep(2);
        $realTime = date('h:i:s');
        $realTime = explode(":", $realTime)[2];
        x();
      endif;
    }
    x();
    */
?>
