<?php
require_once "db.php";
$id = $_GET['id'];

$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM season_menu WHERE id=$id"));
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title><?php echo $data['name']; ?></title>
<link rel="stylesheet" href="style.css">

<style>
.detail-box {
    max-width:700px;
    background:white;
    margin:80px auto;
    padding:30px;
    border-radius:14px;
    box-shadow:0 5px 15px rgba(0,0,0,0.2);
}
.detail-box img {
    width:100%;
    border-radius:12px;
    margin-bottom:16px;
}

/* 뒤로가기 버튼 */
.back-btn {
    background:#704214;
    color:white;
    padding:10px 18px;
    font-size:16px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    transition:0.25s;
    margin-bottom:20px;
}
.back-btn:hover {
    background:#5c3a10;
}
</style>
</head>

<body>

<?php include "topmenu.php"; ?>

<div class="detail-box" style="text-align:center;">
    <button class="back-btn" onclick="history.back()">← 뒤로가기</button>

    <h1 class="page-title"><?php echo $data['name']; ?></h1>
    <img src="<?php echo $data['img']; ?>" alt="메뉴 이미지">

    <h3>메뉴 설명</h3>
    <p><?php echo nl2br($data['period']); ?></p>

    <p>🔥 칼로리: <?php echo $data['calorie']; ?> kcal</p>
    <p>👥 권장 인원: <?php echo $data['people']; ?>명</p>
</div>

</body>
</html>
