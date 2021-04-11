<?php

  require 'db.php';

  $message = $_POST['msg'];

  $sql = "SELECT * FROM questions WHERE question=?;";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt,$sql)){
    echo "Error no connection to database";
    exit();
  }
  else{
      mysqli_stmt_bind_param($stmt,"s",$message);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      if($row = mysqli_fetch_assoc($result)){
        session_start();
        $_SESSION['question'] = $_POST['msg'];
        $_SESSION['ans'] = $row['answer'];
        echo "<script>open('http://localhost/Bank/Login%20System/login.php','_self')</script>";
          }
          else{
            session_start();
            $_SESSION['ans'] = "Sorry, but im not sure I understand what you are saying!!";
            echo "<script>open('http://localhost/Bank/Login%20System/login.php','_self')</script>";
          }
        }
