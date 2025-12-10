<?php require 'head.php'; ?>
<?php require 'menu.php'; ?>   <!-- 햄버거 메뉴 추가됨 -->

<div class="login-box">
    <h2>로그인</h2>
    <form action="login.php" method="POST">
        <label for="userid">아이디</label>
        <input type="text" name="userid" id="userid" placeholder="아이디를 입력하세요" required>

        <label for="pwd">비밀번호</label>
        <input type="password" name="pwd" id="pwd" placeholder="비밀번호를 입력하세요" required>

        <button type="submit" class="login-btn">로그인</button>
    </form>
</div>

<?php require 'tail.php'; ?>
