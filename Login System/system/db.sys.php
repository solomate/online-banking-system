<?php

$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "bank_system";

$conn = mysqli_connect($servername,$dbUsername,$dbPassword,$dbName);

if(!$conn){
  die("Connection failed: ".mysqli_connect_error());
}
