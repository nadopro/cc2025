<nav class="menu">
    <ul>
        <li><a href="index.php?page=home">홈</a></li>
        <li><a href="index.php?page=introduce">소개</a></li>
        <li><a href="index.php?page=scene">명장면</a></li>
        <li><a href="index.php?page=difference">차이점</a></li>
        <li><a href="index.php?page=board">게시판</a></li>
    </ul>
</nav>

<script>
const hamburger = document.querySelector('.hamburger');
const menu = document.querySelector('.menu');
hamburger.addEventListener('click', () => {
    menu.classList.toggle('open');
});
</script>
