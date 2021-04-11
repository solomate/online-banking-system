<?php
    // 'http://puamasters.000webhostapp.com/HTML/manager/manager.php'
    /*
		if($_SERVER['REQUEST_METHOD'] != 'POST'):
			echo "<script> alert('You Can Not Access This Page');</script>";
			exit();
		endif;
    */
    require_once "../../PHP/classes/Manager.php";
    require_once "../../PHP/classes/Database.php";

    session_start();
    $id = $_SESSION['userId']; // ->-> FROM LOGIN SYSTEM
    $manager = new Manager($id);
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
            <form class="hint_form" style="text-align: center;">
              <h2>Hint</h2>
              <span class="hintSpan" id="errorMessage" style="color: red; font-size: 35px; font-family: Arial, Helvetica, sans-serif;"></span>
            </form>
          </div>
        </div>
    </div>
  		<div class="about">
         <span class="manager">Manager</span>
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
      		<button onclick="op('Employee_data')"> Get Employee Data </button>
      		<button onclick="op('bank_storage')"> Get Bank Storage </button>
      		<button onclick="op('comments')">Get Comments </button>
      		<button class="button4" onclick="op('add_task')"> Add Tasks </button>
      		<button class="button5" onclick="op('check_task')"> Check Tasks</button>
      	</div>
  	  </div>
      <div class="report">
        <button onclick="op('report_id')">
          <span class="myreport">Report</span>
        </button>
      </div>
      <div id="Employee_data" class="Get_Employee_Data">
            <div class="modal-content">
                <div class="myform">
                <a class="close" onclick="cl('Employee_data')">&times;</a>
                <form class="tableform" method="POST">
                        <h2>Employee Data</h2>
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
                        $manager->getEmployeeData($_POST['id']);
                      endif;
                    ?>
             </div>
          </div>
        </div>
        <div id="bank_storage" class="Get_bank_storage">
            <div class="modal-content">
                <div class="myform">
                  <a class="close" onclick="cl('bank_storage')">&times;</a>
                  <form class="bank_storage_form">
                    <h2>Data of Bank Storage</h2>
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
                          $manager->getTransactionsInStorage();
                        ?>
                    </table>
                    <table>
                      <tr>
                        <th>Total in bank storage</th>
                        <?php
                          $manager->getTotalStorage();
                        ?>
                      </tr>
                    </table>
                 </form>
             </div>
          </div>
        </div>
        <div id="comments" class="get_comments">
            <div class ="modal-content">
                <div class="myform">
                     <a class="close" onclick="cl('comments')">&times;</a>
                      <form class="get_comments_form">
                        <h2>Comments</h2>
                        <table>
                            <tr>
                              <th>Number</th>
                              <th>Date</th>
                              <th>Name</th>
                              <th>Id</th>
                              <th>Department</th>
                              <th>Job</th>
                              <th>Comment&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                            </tr>
                            <?php
                              $manager->getComments();
                            ?>
                        </table>
                      </form>
                </div>
            </div>
        </div>
        <div id="add_task" class="addtasks">
            <div class="modal-content">
                <div class="myform">
                    <a class="close" onclick="cl('add_task')">&times;</a>
                    <form class="addtasks_form" method="POST">
                      <h2>Add Task</h2>

                     <!-- <input type="Data" name="date" placeholder="Date" required> -->
                      <input name="taskID" type="number" placeholder="ID:"required>
                      <!--
                      <select>
                        <option selected>Department</option>
                        <option>HR</option>
                        <option>Accountant</option>
                        <option>IT</option>
                        <option>Financial</option>
                      </select>
                            -->
                      <textarea name="task" rows="7" placeholder="Write down the task.."></textarea>
                      <button class="mysubmit">Submit</button>
                  </form>
                  <?php
                    if(isset($_POST['taskID']) && isset($_POST['task'])):
                      $manager->addTask($_POST['taskID'], $_POST['task']);
                    endif;
                  ?>
                 </div>
             </div>
        </div>
        <div id="check_task" class="check_my_tasks">
            <div class="modal-content">
              <div class="myform">
                  <a class="close" onclick="cl('check_task')">&times;</a>
                  <form class="check_my_tasks_form">
                      <h2>Check Tasks</h2>
                      <table>
                        <tr>
                          <th>Number</th>
                          <th>Date</th>
                          <th>Id</th>
                          <th>Job</th>
                          <th>Task</th>
                          <th>Status</th>
                        </tr>
                        <?php
                          $manager->checkTasks();
                        ?>
                      </table>
                  </form>
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
                        $manager->addReport('Slow Site Speed', 'http://localhost/Bank/HTML/manager/manager.php');
                      endif;
                      if(isset($_POST['box2'])):
                        $manager->addReport('Links and Site Forms not working', 'http://localhost/Bank/HTML/manager/manager.php');
                      endif;
                      if(isset($_POST['box3'])):
                        $manager->addReport('Compatibility Problems', 'http://localhost/Bank/HTML/manager/manager.php');
                      endif;
                      if(isset($_POST['box4']) && $_POST['box4'] != ''):
                        $manager->addReport($_POST['box4'], 'http://localhost/Bank/HTML/manager/manager.php');
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
                open('http://localhost/Bank/HTML/manager/manager.php', '_self');
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
        </script>
	</body>
</html>
