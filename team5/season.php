<?php
require_once "db.php";
$result = mysqli_query($conn, "SELECT * FROM season_menu ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>계절 한정 메뉴</title>
<link rel="stylesheet" href="style.css">

<style>
.back-btn {
    background:#704214;
    color:white;
    padding:10px 18px;
    font-size:16px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    transition:0.25s;
}
.back-btn:hover {
    background:#5c3a10;
}
.back-top-wrap {
    width:100%;
    display:flex;
    justify-content:flex-end;
    margin:10px 40px 20px 0;
}
.item-meta {
    text-align:center;
    font-size:15px;
    line-height:1.7;
    color:#5a3b1d;
    padding-bottom:12px;
}
</style>
</head>

<body>

<?php include "topmenu.php"; ?>

<div class="back-top-wrap">
    <button class="back-btn" onclick="history.back()">← 뒤로가기</button>
</div>

<h1 class="page-title" style="text-align:center; margin:40px 0;">계절 한정 메뉴</h1>

<div class="grid">
<?php
while($row = mysqli_fetch_assoc($result)){
    echo "
    <div class='item' onclick=\"location.href='season_detail.php?id={$row['id']}'\">
        <img src='{$row['img']}' alt='계절 메뉴'>
        <div class='item-name'>{$row['name']} ({$row['season']})</div>

        <div class='item-meta'>
            📅 판매 시기: {$row['period']}<br>
            🔥 칼로리: {$row['calorie']} kcal<br>
            👥 권장 인원: {$row['people']}명
        </div>

    </div>";
}
?>
</div>

</body>
</html>
