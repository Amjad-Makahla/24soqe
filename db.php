<?php
$host = 'localhost';
$dbname = 'car_system'; // تأكد أنها نفس اسم الداتا بيس
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("فشل الاتصال: " . $conn->connect_error);
}
?>
