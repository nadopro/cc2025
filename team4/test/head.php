<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>영화 사이트</title>
    <link rel="stylesheet" href="style.css">

    <!-- 모드 전환 스크립트: 모든 페이지 공통 적용 -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const mode = localStorage.getItem("mode");

            if (mode === "high") {
                document.body.classList.add("high-contrast");
            }

            // 버튼 클릭 이벤트
            const highBtn = document.getElementById("high-btn");
            const normalBtn = document.getElementById("normal-btn");

            if (highBtn) {
                highBtn.addEventListener("click", () => {
                    document.body.classList.add("high-contrast");
                    localStorage.setItem("mode", "high");
                });
            }

            if (normalBtn) {
                normalBtn.addEventListener("click", () => {
                    document.body.classList.remove("high-contrast");
                    localStorage.setItem("mode", "normal");
                });
            }
        });
    </script>
</head>
<body>
<header class="site-header">
    <div class="header-left">
        <a href="index.php">홈</a>
    </div>

    <div class="header-right">
        <?php if (isset($_SESSION['user'])): ?>
            <span class="welcome">환영합니다, <?= htmlspecialchars($_SESSION['user']['name']) ?>님</span>
            <a href="logout.php" class="login-btn">로그아웃</a>
        <?php else: ?>
            <a href="login.php" class="login-btn">로그인</a>
        <?php endif; ?>

        <!-- 햄버거 -->
        <div class="hamburger">☰</div>
    </div>
</header>

<nav id="mobile-menu" class="mobile-menu">
    <a href="index.php">홈</a>
    <a href="index.php?page=board">게시판</a>
</nav>
