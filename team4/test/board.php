<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "init.php";
?>

<h2 class="board-title">게시판</h2>

<?php
$stmt = $db->query("SELECT * FROM board ORDER BY num DESC");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($rows) === 0): ?>
    <p class="no-post">작성된 글이 없습니다.</p>
<?php else: ?>

<div class="board-wrap">
    <?php foreach ($rows as $r): ?>
        <div class="post-item <?= $r['isnotice'] ? 'notice' : '' ?>">
            <a href="index.php?page=view&num=<?= $r['num'] ?>" class="post-title">
                <?= $r['isnotice'] ? "<span class='notice-icon'>❗</span>" : "" ?>
                <?= htmlspecialchars($r['title']) ?>
            </a>
            <div class="post-info">
                <?= htmlspecialchars($r['nickname']) ?>
                (<?= substr($r['userid'], 0, -4) ?>****)
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php endif; ?>

<!-- 글쓰기 버튼 -->
<?php if (isset($_SESSION['user'])): ?>
<a href="index.php?page=write" class="write-btn">글쓰기</a>
<?php endif; ?>
