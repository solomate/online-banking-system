<?php
    // 'http://puamasters.000webhostapp.com/HTML/HR/hr.php'
    /*
		if($_SERVER['REQUEST_METHOD'] != 'POST'):
			echo "<script> alert('You Can Not Access This Page');</script>";
			exit();
		endif;
    */
    require_once "../../PHP/classes/HR.php";
    require_once "../../PHP/classes/Address.php";
    require_once "../../PHP/classes/Database.php";
    require_once "../../PHP/classes/Employee.php";
    session_start();
    //$id = $_SESSION['userId']; // ->-> FROM LOGIN SYSTEM
    $id = 1;
    $hr = new HR($id);
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
              <span class="hintSpan" id="errorMessage"></span>
            </form>
          </div>
        </div>
    </div>
		<div class="about">

            <span class="hr">HR</span>
        </div>
        <div class="navigation">
        		<div class="logo">
        			<img class="banklogo" src="images/logo.png" width="60px">
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
        		<button onclick="op('newEmployee')"> New Employee </button>
        		<button onclick="op('update')"> Update Employee </button>
        		<button onclick="op('remove')"> Remove Employee </button>
        		<button onclick="op('comment')"> Comment </button>
            <form method="GET" style="padding: 0px; margin: 0px; background-color: unset; box-shadow: unset; display: inline">
              <input style="display: none" name="checkClickedGetTask" id="checkClickedGetTask" value="notClicked">
              <button type="submit" value=" Get Task " onclick="openTask()" style="position: relative;"> Get Task
                <span id="hintTask" class="notificationHint" style="position: absolute; right: 15%; bottom: 3.5%; font-size: 45px; color: red; visibility: hidden;">!</span>
                <span id="taskNotDone" class="notificationHint" style="position: absolute; right: 5%; top: 3.5%; font-weight: bold; font-size: 25px; color: red; visibility:hidden"></span>
              </button>
            </form>
            <?php
              $hr->displayHint();
            ?>
        		<button onclick="op('table')"> Get Employee data </button>
        	</div>
    	</div>
      <div class="report">
        <button onclick="op('report_id')">
          <span class="myreport">Report</span>
        </button>
      </div>
      <div id="newEmployee" class="newEmployeeModel">
            <div class="modal-content">
                <div class="myform">
                <a class="close" onclick="cl('newEmployee')">&times;</a>
                <form class="newEmployeeForm" method="POST">
                    <input name="name" type="text" placeholder="Name" required>
                    <input name="age" type="number" placeholder="Age" required>
                    <input name="nationalID" type ="number"placeholder="National ID" required>
                    <input name="phoneNumber" type ="number"placeholder="Phone Number" required>
                    <input name="city" type="text" placeholder="City" required>
                    <input name="streetName" type="text" placeholder="Street" required>
                    <input name="homeNumber" type="number" placeholder="Home No" required>
                    <input name="email" type="Email" placeholder="Email" required>
                    <select name="gender" required>
                        <option selected>Gender</option>
                        <option>Male</option>
                        <option>Female</option>
                    </select>
                    <select name="department" required>
                        <option selected>Department</option>
                        <option>Accounting</option>
                        <option>Manager</option>
                        <option>IT</option>
                        <option>HR</option>
                        <option>Financial</option>
                    </select>
                    <select name="job" required>
                        <option selected>Job</option>
                        <option>Accountant</option>
                        <option>Manager</option>
                        <option>IT</option>
                        <option>HR</option>
                        <option>Financial</option>
                    </select>
                     <input name="salary" type="number" placeholder="Salary" required>
                    <button class="mysubmit">Submit</button>
                </form>
                <?php
                  if(isset($_POST['name'])):
                    $address = @ new Address($_POST['city'], $_POST['streetName'],$_POST['homeNumber']);
                      @$hr->newEmployee($_POST['name'], $_POST['age'], $_POST['nationalID'], $address,
                        $_POST['phoneNumber'], $_POST['gender'], $_POST['email'], $_POST['department'], $_POST['job'], $_POST['salary']);
                  endif;
                ?>
            </div>
            </div>
        </div>

        <div id="update" class="updatemodel">
            <div class ="modal-content">
                <div class="myform">
                     <a class="close" onclick="cl('update')">&times;</a>
                     <form class="updateform" method="POST">
                      <h2>Update Employee</h2>
                      <input name="uid" type="number" placeholder="ID:"required>
                      <input name="uCity" type="text" placeholder="Update City">
                      <input name="uStreet" type="text" placeholder="Update Street">
                      <input name="uHomeNumber" type="number" placeholder="Update Home No">
                      <input name="uEmail" type="Email" placeholder="Update Email">
                      <input name="uSalary" type="number" placeholder="Update Salary">
                      <button class="mysubmit">Submit</button>
                    </form>
                    <?php
                      if(isset($_POST['uid'])):
                        @ $hr->update($_POST['uid'], $_POST['uCity'], $_POST['uStreet'], $_POST['uHomeNumber'], $_POST['uEmail'], $_POST['uSalary']);
                      endif;
                    ?>
                </div>
            </div>
        </div>
        <div id="remove" class="removemodel">
            <div class="modal-content">
                <div class="myform">
                  <a class="close" onclick="cl('remove')">&times;</a>
                  <form class="removeform" method="POST">
                    <input name="rId" type="number" placeholder="ID:"required>
                    <button class="mysubmit">Submit</button>
                  </form>
                  <?php
                    if(isset($_POST['rId'])):
                      $hr->remove($_POST['rId']);
                    endif;
                  ?>
                  </div>
              </div>
        </div>
        <div id="comment" class="commentmodel">
            <div class="modal-content">
                    <div class="myform">
                        <a class="close" onclick="cl('comment')">&times;</a>
                        <form class="commentform" method="POST">
                            <textarea name="comment" rows="15" placeholder=" Your Comment.."></textarea>
                            <button class="mysubmit">Submit</button>
                      </form>
                      <?php
                        if(isset($_POST['comment'])):
                          $hr->addComment($_POST['comment'], 'http://localhost/Bank/HTML/HR/hr.php');
                        endif;
                      ?>
                     </div>
                 </div>
            </div>
            <div id="task" class="taskmodel">
            <div class="modal-content">
                    <div class="myform">
                        <a class="close" onclick="cl('task')">&times;</a>
                        <form class="taskform" method="POST">
                            <table>
                              <th>Date</th>
                              <th>Task</th>
                              <th>Done</th>
                              <?php
                                $url = $_SERVER['REQUEST_URI'];
                                @$url = explode("?", $url)[1];
                                if($url == 'checkClickedGetTask=clicked'):
                                  $hr->getTask('http://localhost/Bank/HTML/HR/hr.php', 'task');
                                endif;
                              ?>
                            </table>
                            <button class='mysubmit' type="submit" onclick="cli()" >Submit</button>
                      </form>
                     </div>
                 </div>
            </div>
            <div id="table" class="tablemodel">
            <div class="modal-content">
                <div class="myform">
                  <a class="close" onclick="cl('table')">&times;</a>
                  <form class="tableform" method="POST">
                        <input name="id" type="number"placeholder="ID" required>
                        <table>
                            <tr>
                              <th>ID</th>
                              <td id="empID"></td>
                            </tr>
                            <tr>
                              <th>Name</th>
                              <td id="empName"></td>
                            </tr>
                            <tr>
                              <th>Age</th>
                              <td id="empAge"></td>
                            </tr>
                            <tr>
                              <th>Email</th>
                              <td id="empEmail"></td>
                            </tr>
                            <tr>
                              <th>National ID</th>
                              <td id="empNationalId"></td>
                            </tr>
                            <tr>
                              <th>City</th>
                              <td id="empCity"></td>
                            </tr>
                            <tr>
                              <th>Street</th>
                              <td id="empStreet"></td>
                            </tr>
                            <tr>
                              <th>Home Number</th>
                              <td id="empHomeNumber"></td>
                            </tr>
                            <tr>
                              <th>Phone Number</th>
                              <td id="empPhoneNumber"></td>
                            </tr>
                            <tr>
                              <th>Gender</th>
                              <td id="empGender"></td>
                            </tr>
                            <tr>
                              <th>Department</th>
                              <td id="empDepartment"></td>
                            </tr>
                            <tr>
                              <th>Job</th>
                              <td id="empJob"></td>
                            </tr>
                            <tr>
                              <th>Salary</th>
                              <td id="empSalary"></td>
                            </tr>
                            <tr>
                              <th>Computer Number</th>
                              <td id="empComputerNumber"</td>
                            </tr>
                        </table>
                        <button class="mysubmit">Submit</button>
                    </form>
                    <?php
                      if(isset($_POST['id'])):
                        $hr->getData($_POST['id']);
                      endif;
                    ?>
                </div>
            </div>
        </div>
        <div id="report_id" class="class_report">
            <div class="modal-content">
                <div class="myform">
                    <a class="close" onclick="cl('report_id')">&times;</a>
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
                        $hr->addReport('Slow Site Speed', 'http://localhost/Bank/HTML/manager/manager.php');
                      endif;
                      if(isset($_POST['box2'])):
                        $hr->addReport('Links and Site Forms not working', 'http://localhost/Bank/HTML/manager/manager.php');
                      endif;
                      if(isset($_POST['box3'])):
                        $hr->addReport('Compatibility Problems', 'http://localhost/Bank/HTML/manager/manager.php');
                      endif;
                      if(isset($_POST['box4']) && $_POST['box4'] != ''):
                        $hr->addReport($_POST['box4'], 'http://localhost/Bank/HTML/manager/manager.php');
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
                open('http://localhost/Bank/HTML/HR/hr.php', '_self');
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
