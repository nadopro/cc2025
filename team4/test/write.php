<?php
session_start();
require __DIR__ . "/init.php";

// 로그인 여부 체크
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='index.php?page=login';</script>";
    exit;
}

// 사용자 정보
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? "사용자";

// 관리자 여부
$is_admin = ($_SESSION['role'] ?? '') === 'admin';

// head include
require __DIR__ . "/head.php";
?>

<div class="write-container">
    <form action="write_process.php" method="post">

        <!-- 제목 입력 -->
        <input type="text" name="title" class="write-title-input" placeholder="제목을 입력하세요" required>

        <!-- 내용 입력 -->
        <textarea name="content" class="write-content" placeholder="내용을 입력하세요" required></textarea>

        <!-- 공지글 옵션 (관리자만 표시) -->
        <?php if ($is_admin): ?>
            <label class="notice-option">
                <input type="checkbox" name="notice" value="1">
                공지글로 등록
            </label>
        <?php endif; ?>

        <!-- 작성 완료 버튼 -->
        <button type="submit" class="write-btn-submit">작성 완료</button>

    </form>
</div>

<?php require __DIR__ . "/tail.php"; ?>
