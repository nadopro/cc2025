<div class="menu">
    <div class="hamburger">☰</div>
    <ul class="menu-list">
        <li><a href="index.php?page=home">홈</a></li>
        <li><a href="index.php?page=introduce">소개</a></li>
        <li><a href="index.php?page=scene">명장면</a></li>
        <li><a href="index.php?page=difference">차이점</a></li>
        <li><a href="board.php">게시판</a></li>
    </ul>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const menuList = document.querySelector('.menu-list');
    if(hamburger && menuList){
        hamburger.addEventListener('click', function() {
            menuList.classList.toggle('show');
        });
    }
});
</script>
