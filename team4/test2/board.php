<?php
require_once 'init.php'; // $pdo, session 등 초기화

// 페이지 접근 권한 제한은 목록 공개가 목적이면 로그인 없이도 허용 가능하나
// 기존 흐름상 로그인 필요하면 아래 체크를 사용 (주석 처리 시 공개 목록으로 동작)
// if (!isset($_SESSION['user_id'])) { header('Location: printlogin.php'); exit; }

// 페이징 (간단)
$perPage = 12;
$page = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
$offset = ($page - 1) * $perPage;

try {
    // 공지글 우선, 최신순
    $stmt = $pdo->prepare("SELECT idx, id, name, title, time, hit, notice FROM board ORDER BY notice DESC, idx DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 전체 글 개수(페이징 용)
    $countStmt = $pdo->query("SELECT COUNT(*) FROM board");
    $totalPosts = (int)$countStmt->fetchColumn();
    $totalPages = (int)ceil($totalPosts / $perPage);
} catch (Exception $e) {
    // 오류시 간단 안내
    echo "게시글을 불러오는 중 오류가 발생했습니다: " . htmlspecialchars($e->getMessage());
    exit;
}

require 'menu.php';
?>

<div id="main-content">
    <h2 style="text-align:center; margin-bottom:20px;">게시판</h2>

    <div class="board-list">
        <?php if (empty($posts)): ?>
            <div class="board-item empty">작성된 글이 없습니다.</div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <?php
                    $isNotice = !empty($post['notice']);
                    $title = $post['title'] ?? '제목 없음';
                    $display_name = $post['name'] ?? '익명';
                    $raw_id = $post['id'] ?? '';
                    // id 마스킹: id가 없거나 짧으면 전체 * 처리
                    if ($raw_id === '') {
                        $masked_id = '***';
                    } else {
                        $masked_id = (mb_strlen($raw_id) > 3) ? htmlspecialchars(mb_substr($raw_id, 0, 3) . str_repeat('*', mb_strlen($raw_id) - 3)) : str_repeat('*', mb_strlen($raw_id));
                    }
                ?>
                <div class="board-item <?= $isNotice ? 'notice-item' : '' ?>">
                    <a class="board-link" href="view.php?idx=<?= urlencode($post['idx']) ?>">
                        <?= htmlspecialchars($title) ?>
                        <?php if ($isNotice): ?><span class="notice-mark">!</span><?php endif; ?>
                    </a>
                    <div class="meta">
                        작성자: <?= htmlspecialchars($display_name) ?> (<?= $masked_id ?>)
                        | <?= htmlspecialchars($post['time'] ?? '') ?>
                        | 조회: <?= htmlspecialchars($post['hit'] ?? 0) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- 간단 페이징 -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination" style="margin-top:20px;">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?p=<?= $i ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

    <a href="write.php" class="write-btn">글쓰기</a>
</div>

<?php require 'tail.php'; ?>
