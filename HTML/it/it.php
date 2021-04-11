<?php
  require_once "../../PHP/classes/IT.php";
  require_once "../../PHP/classes/Database.php";
  //session_start();
  $id = 1;
  //  $id = $_SESSION['userId'];
  $it = new IT($id);
?>
<!DOCTYPE html>
<html Lang="en">
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="normalize.css">
    <link rel="stylesheet" href="style.css">
  </head>
	<body>
  		<div class="about">
         <span class="manager">IT</span>
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

      <div id="Employee_data" class="Get_Employee_Data">
            <div class="modal-content">
                <div class="myform">
                <form class="Employee_Data_form">
                    <h2>Employee Reports</h2>
                        <table>
                          <table>
                            <tr>
                              <th>Number</th>
                              <th>Id</th>
                              <th>Name</th>
                              <th>Department</th>
                              <th>Machine Number</th>
                              <th>Report Type</th>
                            </tr>
                            <?php
                              $it->getReports();
                            ?>
                          </table>
                        </table>
                   </form>
               </div>
            </div>
        </div>
        <script>
            function op(id) {
                document.getElementById(id).style.display = 'block';
            }
            function cl(id) {
                document.getElementById(id).style.display = 'none';
            }
        </script>
	</body>
</html>
