<?php
require_once 'init.php';

$uid = $_POST['userid'];
$pw = $_POST['pwd'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND pw = ?");
$stmt->execute([$uid, $pw]);
$user = $stmt->fetch();

if ($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    header("Location: index.php");
    exit;
} else {
    echo "<script>alert('로그인 실패'); history.back();</script>";
}
