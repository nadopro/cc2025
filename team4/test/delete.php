<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'init.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인 후 이용 가능합니다.'); history.back();</script>";
    exit;
}

$idx = isset($_GET['idx']) ? intval($_GET['idx']) : 0;

// 글 정보 조회
$stmt = $pdo->prepare("SELECT * FROM board WHERE idx=?");
$stmt->execute([$idx]);
$post = $stmt->fetch();

if (!$post) { echo "<script>alert('삭제할 글이 존재하지 않습니다.'); history.back();</script>"; exit; }

if ($_SESSION['user_id'] !== $post['id'] && $_SESSION['user_id'] !== 'admin') {
    echo "<script>alert('삭제 권한이 없습니다.'); history.back();</script>"; exit;
}

// 글 삭제
$pdo->prepare("DELETE FROM board WHERE idx=?")->execute([$idx]);

echo "<script>alert('글이 삭제되었습니다.'); location.href='board.php';</script>";
