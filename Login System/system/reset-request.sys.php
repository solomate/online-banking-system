<?php

if(isset($_POST['reset-request-submit'])){

  $selector = bin2hex(random_bytes(8));
  $token = random_bytes(32);

  $url = "http://puamasters.000webhostapp.com/Login%20System/create-new-password.php?selector=" . $selector . "&validator=" . bin2hex($token);

  $expires = date("U") + 1800;

  require 'db.sys.php';

  $userEmail = $_POST['email'];

  $sql = "DELETE FROM pwdreset WHERE pwdResetEmail=?;";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt,$sql)){
    header("Location: ../reset-password.php?error=sqlerror");
    exit();
  }
  else{
    mysqli_stmt_bind_param($stmt,"s",$userEmail);
    mysqli_stmt_execute($stmt);
  }

  $sql = "INSERT INTO pwdreset (pwdResetEmail,pwdResetSelector,pwdResetToken,pwdResetExpires) VALUES (?,?,?,?);";
  $stmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($stmt,$sql)){
    header("Location: ../reset-password.php?error=sqlerror");
    exit();
  }
  else{
    $hashedToken = password_hash($token,PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt,"ssss",$userEmail,$selector,$hashedToken,$expires);
    mysqli_stmt_execute($stmt);
  }

  mysqli_stmt_close($stmt);
  mysqli_close($conn);


  $to = $userEmail;

  $subject = 'Reset Your Password For MasterBank';

  $message = '<p>We recieved a password reset request. The link to reset your password is below. If you did not make this request,
  you can ignore this email</p>';
  $message .= '<p>Here is your password reset link: </br>';
  $message .= '<a href="' . $url . '">' . $url . '</a></p>';

  $headers = "From: Master Bank <yonehazaki@gmail.com>\r\n";
  $headers .="Reply-To: yonehazaki@gmail.com\r\n";
  $headers .= "Content-Type: text/html\r\n";

  require_once(realpath($_SERVER["DOCUMENT_ROOT"]).'\Bank\Login System\Mail\mailer.php');

  header("Location: ../reset-password.php?reset=success");


}
else {
  echo "<script>open('http://localhost/Bank/','_self')</script>";
}
