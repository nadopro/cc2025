<?php
function connectDB() {
    $host = "localhost";   // 보통 같은 서버에 있으면 localhost
    $user = "cnu";         // DB 사용자
    $pass = "1111";        // DB 비밀번호
    $dbname = "cnu";       // DB 이름

    // mysqli 객체 생성
    $conn = new mysqli($host, $user, $pass, $dbname);

    // 연결 확인
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // 성공 시 $conn 반환
    return $conn;
}
?>
