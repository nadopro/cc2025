<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once "db.php";
$conn = connectDB();

// 로그인 체크
if (!isset($_SESSION['sino_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); history.back();</script>";
    exit;
}

$user_id = $_SESSION['sino_id'];

// GET, POST 둘 다 지원
$quote = $_POST['quote'] ?? $_GET['quote'] ?? '';
$exp   = $_POST['exp']   ?? $_GET['exp']   ?? '';

$quote = mysqli_real_escape_string($conn, $quote);
$exp   = mysqli_real_escape_string($conn, $exp);

// 값 체크
if (!$quote || !$exp) {
    echo "<script>alert('저장할 구절 정보가 전달되지 않았습니다.'); history.back();</script>";
    exit;
}

$sql = "INSERT INTO favorite_quotes (user_id, quote, explanation)
        VALUES ('$user_id', '$quote', '$exp')";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('저장되었습니다!'); location.href='index.php?cmd=mypage';</script>";
} else {
    echo "<script>alert('저장 실패: " . mysqli_error($conn) . "'); history.back();</script>";
}
?>
