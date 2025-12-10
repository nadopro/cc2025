<?php
require_once 'init.php';

$idx = isset($_GET['idx']) ? intval($_GET['idx']) : 0;
if ($idx <= 0) {
    echo "<script>alert('잘못된 접근입니다.'); location.href='index.php?page=board';</script>";
    exit;
}

// 게시글 조회
try {
    $stmt = $pdo->prepare("SELECT * FROM board WHERE idx = ?");
    $stmt->execute([$idx]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "게시글을 불러오는 중 오류가 발생했습니다: " . htmlspecialchars($e->getMessage());
    exit;
}

if (!$post) {
    echo "<script>alert('게시글을 찾을 수 없습니다.'); location.href='index.php?page=board';</script>";
    exit;
}

// 조회수 증가
try {
    $pdo->prepare("UPDATE board SET hit = COALESCE(hit,0) + 1 WHERE idx = ?")->execute([$idx]);
    // 최신 값 반영
    $stmt = $pdo->prepare("SELECT * FROM board WHERE idx = ?");
    $stmt->execute([$idx]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // 조회수 증가 실패시에도 본문은 보여줌
}

// 권한 확인
$current_user = $_SESSION['user_id'] ?? null;
$is_admin = (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) || ($current_user === 'admin');

require 'head.php';
require 'menu.php';
?>

<div class="view-form">
    <h2 class="form-title"><?= htmlspecialchars($post['title'] ?? '제목 없음') ?></h2>

    <div class="view-meta">
        작성자: <?= htmlspecialchars($post['name'] ?? '익명') ?> |
        아이디: <?= htmlspecialchars((isset($post['id']) && $post['id'] !== '') ? (mb_strlen($post['id']) > 3 ? mb_substr($post['id'],0,3) . str_repeat('*', mb_strlen($post['id'])-3) : str_repeat('*', mb_strlen($post['id']))) : '***') ?> |
        작성일: <?= htmlspecialchars($post['time'] ?? '') ?> |
        조회: <?= htmlspecialchars($post['hit'] ?? 0) ?>
    </div>

    <div class="view-content"><?= nl2br(htmlspecialchars($post['content'] ?? '')) ?></div>

    <div class="view-actions">
        <?php if ($current_user && ($current_user === $post['id'] || $is_admin)): ?>
            <a href="edit_post.php?idx=<?= urlencode($post['idx']) ?>" class="btn-edit">수정</a>
            <a href="delete_post.php?idx=<?= urlencode($post['idx']) ?>" class="btn-delete" onclick="return confirm('정말 삭제하시겠습니까?');">삭제</a>
        <?php endif; ?>
        <a href="index.php?page=board" class="btn-back">목록으로</a>
    </div>
</div>

<?php require 'tail.php'; ?>
