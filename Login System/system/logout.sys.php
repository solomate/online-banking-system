<?php

session_start();
session_unset();
session_destroy();

echo "<script>open('http://localhost/Bank/Login%20System/login.php', '_self');</script>";
//header("Location: http://localhost/Bank/Login%20System/login.php");
exit();
