<?php
session_start();
require_once 'init.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인 후 이용 가능합니다.'); history.back();</script>";
    exit;
}

// 입력 데이터
$name = $_POST['name'];
$content = $_POST['content'];
$id = $_SESSION['user_id'];
$notice = isset($_POST['notice']) ? 1 : 0;

// DB 등록
$stmt = $pdo->prepare("INSERT INTO board (name, content, id, notice) VALUES (?, ?, ?, ?)");
$stmt->execute([$name, $content, $id, $notice]);

echo "<script>alert('글이 등록되었습니다.'); location.href='board.php';</script>";
