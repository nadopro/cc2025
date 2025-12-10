<?php
require_once 'init.php';
$idx = $_GET['idx'] ?? 0;

$pdo->prepare("UPDATE board SET hit = hit + 1 WHERE idx=?")->execute([$idx]);
$stmt = $pdo->prepare("SELECT * FROM board WHERE idx=?");
$stmt->execute([$idx]);
$post = $stmt->fetch();

if(!$post){
    echo "<script>alert('존재하지 않는 글입니다.'); history.back();</script>";
    exit;
}

$logged_in = isset($_SESSION['user_id']);
$is_admin = ($logged_in && $_SESSION['user_id'] === 'admin');
?>

<?php require 'head.php'; ?>
<div id="main-content" class="container">
    <div class="board-list">
        <div class="board-item">
            <h2><?= htmlspecialchars($post['name']) ?></h2>
            <p class="meta">
                작성자: <?= htmlspecialchars($post['name'].' ('.substr($post['id'],0,strlen($post['id'])-4).'****)') ?> |
                <?= $post['time'] ?> | 조회수: <?= $post['hit'] ?>
            </p>
            <div class="content-box"><?= nl2br(htmlspecialchars($post['content'])) ?></div>

            <div style="margin-top:10px;">
                <a href="board.php" class="write-btn">목록으로</a>
                <?php if($is_admin): ?>
                    <a href="delete.php?idx=<?= $post['idx'] ?>" class="write-btn" style="background:red;">삭제</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php require 'tail.php'; ?>
