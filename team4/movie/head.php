<?php
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>영화 소개 사이트</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1 class="site-title">영화 소개 사이트</h1>

<div class="top-right-buttons">
    <?php if(isset($_SESSION['user'])): ?>
        <span class="welcome-msg"><?php echo htmlspecialchars($_SESSION['user']); ?>님 환영합니다</span>
        <a href="logout.php" class="logout-btn">로그아웃</a>
    <?php else: ?>
        <a href="login.php" class="login-btn">로그인</a>
    <?php endif; ?>
</div>
