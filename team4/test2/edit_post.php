<?php
require_once 'init.php';

// idx 검사
$idx = isset($_GET['idx']) ? intval($_GET['idx']) : 0;
if ($idx <= 0) {
    echo "<script>alert('잘못된 접근입니다.'); location.href='index.php?page=board';</script>";
    exit;
}

// 게시글 로드
try {
    $stmt = $pdo->prepare("SELECT * FROM board WHERE idx = ?");
    $stmt->execute([$idx]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "게시글을 불러오는 중 오류가 발생했습니다: " . htmlspecialchars($e->getMessage());
    exit;
}

if (!$post) {
    echo "<script>alert('수정할 게시글을 찾을 수 없습니다.'); location.href='index.php?page=board';</script>";
    exit;
}

// 권한 체크: 작성자 또는 관리자
$current_user = $_SESSION['user_id'] ?? null;
$is_admin = (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) || ($current_user === 'admin');

if (!$current_user || ($current_user !== $post['id'] && !$is_admin)) {
    echo "<script>alert('수정 권한이 없습니다.'); location.href='index.php?page=board';</script>";
    exit;
}

// POST(수정) 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    // 공지 변경은 관리자만 허용 (선택적)
    $notice = ($is_admin && isset($_POST['notice']) && $_POST['notice'] == '1') ? 1 : 0;

    if ($title === '' || $content === '') {
        $error = "제목과 내용을 입력해 주세요.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE board SET title = ?, content = ?, notice = ? WHERE idx = ?");
            $stmt->execute([$title, $content, $notice, $idx]);

            // 수정 완료 후 view로 이동
            header("Location: view.php?idx=" . urlencode($idx));
            exit;
        } catch (Exception $e) {
            $error = "수정 중 오류가 발생했습니다: " . $e->getMessage();
        }
    }
}

require 'head.php';
require 'menu.php';
?>

<div id="main-content">
    <div class="board-form">
        <h2 class="form-title">게시글 수정</h2>

        <?php if (!empty($error)): ?>
            <div style="color:red; margin-bottom:12px;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <label for="title">제목</label>
            <input id="title" name="title" type="text" value="<?= htmlspecialchars($post['title'] ?? '') ?>" required>

            <label for="content">내용</label>
            <textarea id="content" name="content" rows="12" required><?= htmlspecialchars($post['content'] ?? '') ?></textarea>

            <?php if ($is_admin): ?>
                <label class="notice-label">
                    <input type="checkbox" name="notice" value="1" <?= !empty($post['notice']) ? 'checked' : '' ?>> 공지글
                </label>
            <?php endif; ?>

            <div class="form-actions" style="margin-top:14px;">
                <button type="submit" class="login-btn centered-btn">수정 완료</button>
                <a href="view.php?idx=<?= urlencode($idx) ?>" class="btn btn-back" style="margin-left:8px;">취소</a>
            </div>
        </form>
    </div>
</div>

<?php require 'tail.php'; ?>
