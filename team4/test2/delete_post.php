<?php
// delete_post.php
// 게시글 삭제 -> 삭제 후 항상 index.php?page=board 로 리디렉션

require_once 'init.php';

// 로그인 확인
if (!isset($_SESSION['user_id'])) {
    // 로그인 안 된 상태면 게시판으로 리디렉션
    header("Location: index.php?page=board");
    exit;
}

// GET 파라미터 허용: id 또는 idx
$delete_id = null;
if (isset($_GET['id']) && $_GET['id'] !== '') {
    $delete_id = intval($_GET['id']);
} elseif (isset($_GET['idx']) && $_GET['idx'] !== '') {
    $delete_id = intval($_GET['idx']);
}

if (!$delete_id) {
    // 잘못된 접근 -> 게시판으로
    header("Location: index.php?page=board");
    exit;
}

// 게시글 조회
try {
    $stmt = $pdo->prepare("SELECT * FROM board WHERE idx = ?");
    $stmt->execute([$delete_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // DB 오류 발생 시에도 게시판으로 리디렉션
    header("Location: index.php?page=board");
    exit;
}

if (!$post) {
    // 삭제 대상 없음 -> 게시판으로
    header("Location: index.php?page=board");
    exit;
}

// 권한 확인: 작성자 본인 또는 admin
$current_user = $_SESSION['user_id'];
$is_admin = ($current_user === 'admin') || (!empty($_SESSION['is_admin']) && $_SESSION['is_admin']);

if ($current_user !== $post['id'] && !$is_admin) {
    // 권한 없음 -> 게시판으로
    header("Location: index.php?page=board");
    exit;
}

// 실제 삭제 실행
try {
    $delStmt = $pdo->prepare("DELETE FROM board WHERE idx = ?");
    $delStmt->execute([$delete_id]);
} catch (Exception $e) {
    // 삭제 실패해도 게시판으로 리디렉션
    header("Location: index.php?page=board");
    exit;
}

// 삭제 완료 후 게시판으로 리디렉션 (항상 이 위치로 이동)
header("Location: index.php?page=board");
exit;
