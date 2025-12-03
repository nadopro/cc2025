<?php
$host = "localhost";
$user = "team5";
$pass = "1111";
$dbname = "team5";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("DB 연결 실패: " . mysqli_connect_error());
}
?>
