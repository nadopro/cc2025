<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
<title>the Great Gatsuby_Movie</title>
</head>
<body>

<header class="header">
    <div class="top-right">
        <?php if (isset($_SESSION['user_name'])): ?>
            <span class="welcome-msg"><?= $_SESSION['user_name'] ?>님 환영합니다</span>
            <a href="logout.php" class="logout-btn">로그아웃</a>
        <?php else: ?>
            <a href="printlogin.php" class="login-btn">로그인</a>
        <?php endif; ?>
        <button class="hamburger" onclick="toggleMenu()">☰</button>
    </div>
</header>

<script>
function toggleMenu() {
    document.querySelector(".menu").classList.toggle("open");
}
</script>
