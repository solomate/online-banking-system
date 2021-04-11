<?php
if(isset($_POST['login-submit'])){

  require 'db.sys.php';

  $mailuid = $_POST['mailuid'];
  $password = $_POST['pwd'];
  $userType = $_POST['userType'];

  if(empty($mailuid) || empty($password)){
    header("Location: ../login.php?error=emptyfields");
    exit();
  }
  else if($userType == 'Employee'){
    $sql = "SELECT * FROM employeelogin WHERE Username=? OR Email=?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){
      header("Location: ../login.php?error=sqlerror");
      exit();
    }
    else{
      mysqli_stmt_bind_param($stmt,"ss",$mailuid,$mailuid);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      if($row = mysqli_fetch_assoc($result)){
          $pwdCheck = password_verify($password,$row['Password']);
          if($pwdCheck == false){
            header("Location: ../login.php?error=wrongpassword");
            exit();
          }
          else{
            session_start();
            $_SESSION['userId'] = $row['ID'];
            $_SESSION['userUid'] = $row['Username'];
            $ID = $row['ID'];

            $sql2 = "SELECT Department FROM employee WHERE ID=$ID";
            $result2 = mysqli_query($conn,$sql2);
            if(mysqli_num_rows($result2) > 0){
              while($row = mysqli_fetch_assoc($result2)){
                if($row['Department'] == "Accounting"){
                echo "<script>open('http://localhost/Bank/HTML/accountant/accountant.php?loginsuccessful','_self')</script>";
                }
                if($row['Department'] == "HR"){
                  echo "<script>open('http://localhost/Bank/HTML/HR/hr.php?loginsuccessful','_self')</script>";
                }
                if($row['Department'] == "Manager"){
                  echo "<script>open('http://localhost/Bank/HTML/manager/manager.php?loginsuccessful','_self')</script>";
                }
                if($row['Department'] == "Financial"){
                  echo "<script>open('http://localhost/Bank/HTML/Financial/financial.php?loginsuccessful','_self')</script>";
                }
                if($row['Department'] == "IT"){
                  echo "<script>open('http://localhost/Bank/HTML/it/it.php?loginsuccessful','_self')</script>";
                }
              }
            }
        }
      }
        else {
          header("Location: ../login.php?error=nouser");
          exit();
        }
      }
    }
    else{
        #client login
        header("Location: ../login.php");
        exit();
    }
  }
  else {
    echo "<script>open('http://localhost/Bank/','_self')</script>";
  }
