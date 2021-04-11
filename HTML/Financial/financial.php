<?php
    // 'http://puamasters.000webhostapp.com/HTML/Financial/financial.php'
    /*
		if($_SERVER['REQUEST_METHOD'] != 'POST'):
			echo "<script> alert('You Can Not Access This Page');</script>";
			exit();
		endif;
    */
    require_once "../../PHP/classes/Financial Transactions.php";
    require_once "../../PHP/classes/Database.php";
    session_start();
    //$id = $_SESSION['userId']; // ->-> FROM LOGIN SYSTEM
    $id = 5;
    $financial = new FinancialTransactions($id);
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
              <a class="close" onclick="cl('hint_id')">&times;</a>
              <form class="hint_form">
                <h2>Hint</h2>
                <span class="hintSpan" id="errorMessage">sflksjdf</span>
              </form>
            </div>
          </div>
    </div>
		<div class="about">

            <span class="hr">Financial</span>
        </div>
        <div class="navigation">
        		<div class="logo">
        			<img class="banklogo" src="images/logo1.png" width="60px">
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
        		<button onclick="op('salary')"> Get Employee Salary </button>
        	  <button onclick="op('storage')"> Get Bank Storage </button>
        	  <button onclick="op('comment')"> Comment </button>
          	<form method="GET" style="padding: 0px; margin: 0px; background-color: unset; box-shadow: unset; display: inline">
              <input style="display: none" name="checkClickedGetTask" id="checkClickedGetTask" value="notClicked">
              <button type="submit" value=" Get Task " onclick="openTask()" style="position: relative;"> Get Task
                <span id="hintTask" class="notificationHint" style="position: absolute; right: 15%; bottom: 3.5%; font-size: 45px; color: red; visibility: hidden;">!</span>
                <span id="taskNotDone" class="notificationHint" style="position: absolute; right: 5%; top: 3.5%; font-weight: bold; font-size: 25px; color: red; visibility:hidden"></span>
              </button>
            </form>
            <?php
              $financial->displayHint();
            ?>
        	</div>
        </div>
        	</div>
      <div class="report">
        <button onclick="op('report_id')">
          <span class="myreport">Report</span>
        </button>
      </div>
      <div id="salary" class="getsalarymodel">
            <div class="modal-content">
                <div class="myform">
                <a class="close" onclick="cl('salary')">&times;</a>
                <form class="getsalaryform" method="POST">
                    <input name="sID" type ="number"placeholder="ID" required>
                    <table>
                      <tr>
                        <th>Salary</th>
                        <td id="displaySalary"></td>
                      </tr>
                    </table>
                    <button class="mysubmit">Submit</button>
                </form>
                <?php
                  if(isset($_POST['sID'])):
                    $financial->getSalary($_POST['sID']);
                  endif;
                ?>
            </div>
          </div>
        </div>
        <div id="storage" class="storagemodel">
            <div class ="modal-content">
                <div class="myform">
                     <a class="close" onclick="cl('storage')">&times;</a>
                      <form class="storageform">
                      <table>
                          <tr>
                            <th>Number</th>
                            <th>Date</th>
                            <th>Account Number 1</th>
                            <th>Type Account Number 1</th>
                            <th>Account Number 2</th>
                            <th>Transaction</th>
                            <th>Money&emsp;&emsp;&emsp;&emsp;</th>
                          </tr>
                        <?php
                          $financial->getTransactionsInStorage();
                        ?>
                      </table>
                      <table>
                        <tr>
                          <th>Total in bank storage</th>
                          <?php
                            $financial->getTotalStorage();
                          ?>
                        </tr>
                      </table>
                        <button class="mysubmit" >Submit</button>
                      </form>
                </div>
            </div>
        </div>
        <div id="comment" class="commentmodel">
            <div class="modal-content">
                    <div class="myform">
                        <a class="close" onclick="cl('comment')">&times;</a>
                        <form class="commentform" method="POST">
                            <textarea name="comment" rows="15" placeholder=" Your Comment.." required></textarea>
                            <button class="mysubmit">Submit</button>
                      </form>
                      <?php
                        if(isset($_POST['comment'])):
                          $financial->addComment($_POST['comment'], 'http://localhost/Bank/HTML/Financial/financial.php');
                        endif;
                      ?>
                     </div>
                 </div>
            </div>
            <div id="task" class="taskmodel">
            <div class="modal-content">
                    <div class="myform">
                      <a class="close" onclick="cl('task')">&times;</a>
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
                              $financial->getTask('http://localhost/Bank/HTML/Financial/financial.php', 'task');
                            endif;
                          ?>
                        </table>
                        <button class="mysubmit">submit</button>
                      </form>
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
                          echo "1";
                        $financial->addReport('Slow Site Speed', 'http://localhost/Bank/HTML/Financial/financial.php');
                      endif;
                      if(isset($_POST['box2'])):
                        $financial->addReport('Links and Site Forms not working', 'http://localhost/Bank/HTML/Financial/financial.php');
                      endif;
                      if(isset($_POST['box3'])):
                        $financial->addReport('Compatibility Problems', 'http://localhost/Bank/HTML/Financial/financial.php');
                      endif;
                      if(isset($_POST['box4']) && $_POST['box4'] != ''):
                        $financial->addReport($_POST['box4'], 'http://localhost/Bank/HTML/Financial/financial.php');
                      endif;
                  ?>
                 </div>
             </div>
        </div>
        <script>
            function op(id) {
                document.getElementById(id).style.display = 'block';
            }
            function cl(id) {
                document.getElementById(id).style.display = 'none';
                open('http://localhost/Bank/HTML/Financial/financial.php', '_self');
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
            function openTask(){
              document.getElementById('checkClickedGetTask').value = 'clicked';
            }
        </script>
	</body>
</html>
