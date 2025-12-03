<!-- 상단 메뉴 스타일 + 메뉴바 HTML -->
<style>
.top-menu {
    display: flex;
    justify-content: flex-end;
    gap: 18px;
    padding: 20px 40px;
}

.top-menu a {
    font-size: 17px;
    font-weight: bold;
    padding: 10px 18px;
    background: #eee;
    text-decoration: none;
    border-radius: 8px;
    color: #333;
    transition: 0.2s;
}

.top-menu a:hover {
    background:#dcdcdc;
}
</style>

<div class="top-menu">
    <a href="index.php">홈</a>
    <a href="season.php">계절별 메뉴</a>
    <a href="events.php">진행중인 이벤트</a>
</div>
