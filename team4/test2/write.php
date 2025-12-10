<?php
require_once 'init.php';

// 로그인 확인
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인 후 이용 가능합니다.'); location.href='printlogin.php';</script>";
    exit;
}

$current_user = $_SESSION['user_id'];

// 관리자 계정: id가 'admin'일 경우
$is_admin = ($current_user === 'admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    // 공지글 여부: 관리자만 체크 가능
    $notice = ($is_admin && isset($_POST['notice']) && $_POST['notice'] == 1) ? 1 : 0;

    if (!$title || !$content) {
        echo "<script>alert('제목과 내용을 입력해주세요.'); history.back();</script>";
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO board (title, content, id, notice, time) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$title, $content, $current_user, $notice]);

    echo "<script>alert('글이 작성되었습니다.'); location.href='index.php?page=board';</script>";
    exit;
}
?>

<?php require 'head.php'; ?>
<?php require 'menu.php'; ?>

<div class="board-form">
    <h2 class="form-title">글 작성하기</h2>
    <form method="post">
        <label for="title">제목</label>
        <input type="text" name="title" id="title" required>

        <label for="content">내용</label>
        <textarea name="content" id="content" required></textarea>

        <?php if ($is_admin): ?>
            <label class="notice-label">
                <input type="checkbox" name="notice" value="1">
                공지글로 작성
            </label>
        <?php endif; ?>

        <div class="form-actions">
            <button type="submit" class="login-btn centered-btn">작성 완료</button>
        </div>
    </form>
</div>

<?php require 'tail.php'; ?>
