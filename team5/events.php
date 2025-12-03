<?php
require_once "db.php";
$result = mysqli_query($conn, "SELECT * FROM events ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>진행 중 이벤트</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<?php include "topmenu.php"; ?>



<!-- 이벤트 준비중 이미지 출력 -->
<div class="text-center"> 
    <h1 class="event-title-center">진행 중 이벤트</h1>
    <img src="img/event_ready.png" style="width:400px; margin:30px 0;" alt="이벤트 준비중">
    <p style="font-size:18px; font-weight:bold; margin-bottom:20px;">아직 이벤트 준비중입니다.</p>
</div>

<div class="event-container">
<?php
while($row = mysqli_fetch_assoc($result)){
    echo "
    <div class='event-box'>
        <img src='{$row['img']}'>
        <h2>{$row['title']}</h2>
        <p>{$row['description']}</p>
    </div>";
}
?>
</div>

<!-- 하단 뒤로가기 버튼도 원하면 유지 가능 -->
<div class=\"text-center\" style=\"margin-top:30px;\">
    <button class=\"back-btn\" onclick=\"history.back()\">← 이전 페이지</button>
</div>

<!-- 🔙 상단 뒤로가기 버튼 -->
<button class="back-btn" onclick="history.back()">⬅ 뒤로가기</button>


</body>
</html>
