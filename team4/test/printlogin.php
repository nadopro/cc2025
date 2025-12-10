<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require __DIR__ . "/head.php";
?>

<div class="login-box">
    <h2>로그인</h2>
    <form action="login.php" method="POST">
        <label for="userid">아이디</label>
        <input type="text" name="userid" id="userid" placeholder="아이디를 입력하세요" required>
        <label for="pwd">비밀번호</label>
        <input type="password" name="pwd" id="pwd" placeholder="비밀번호를 입력하세요" required>
        <button type="submit" class="login-btn">로그인</button>
    </form>

    <?php if(isset($_GET['error'])): ?>
        <p style="color:#ff7676; margin-top:12px;">
        <?php
            switch($_GET['error']){
                case 'id': echo "존재하지 않는 아이디입니다."; break;
                case 'pw': echo "비밀번호가 일치하지 않습니다."; break;
                default: echo "로그인 정보가 부족합니다."; break;
            }
        ?>
        </p>
    <?php endif; ?>
</div>

<?php require __DIR__ . "/tail.php"; ?>
