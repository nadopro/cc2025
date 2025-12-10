<?php
session_start();
require __DIR__ . "/init.php"; // $pdo 포함

$uid = $_POST['userid'];
$pw = $_POST['pwd'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND pw = ?");
$stmt->execute([$uid, $pw]);
$user = $stmt->fetch();

if ($user) {
    $_SESSION['user_id'] = $user['id'];     // 로그인 확인용
    $_SESSION['user_name'] = $user['name']; // 화면 표시용
    header("Location: index.php");
    exit;
} else {
    echo "<script>alert('로그인 실패'); history.back();</script>";
}
