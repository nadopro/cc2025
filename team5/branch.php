<?php
require_once "db.php";
$id = $_GET['id'];

// 데이터 불러오기
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM branch WHERE id=$id"));
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title><?php echo $data['name']; ?></title>

<!-- 공통 스타일 적용 -->
<link rel="stylesheet" href="style.css">

<style>
.detail-wrap {
    max-width: 850px;
    background: white;
    margin: 60px auto;
    padding: 40px;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.18);
}

.detail-img {
    width: 100%;
    border-radius: 12px;
    margin-bottom: 20px;
}

.page-title {
    font-size: 36px;
    font-weight: bold;
    margin-bottom: 25px;
}

.map-btn, .menu-btn {
    display: inline-block;
    background: #a65329;
    color: #fff;
    padding: 12px 22px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    margin-top: 15px;
    transition: .25s;
}

.map-btn:hover, .menu-btn:hover {
    background: #8a4623;
}

.back-btn {
    position: absolute;
    right: 40px;
    top: 90px;
    background:#704214;
    color:white;
    padding:10px 18px;
    border:none;
    border-radius:8px;
    cursor:pointer;
}
.back-btn:hover { background:#5c3a10; }
</style>

</head>

<body>

<?php include "topmenu.php"; ?>

<!-- 🔙 상단 뒤로가기 버튼 -->
<button class="back-btn" onclick="history.back()">⬅ 뒤로가기</button>

<div class="detail-wrap">

    <h1 class="page-title"><?php echo $data['name']; ?></h1>

   <!-- ⭐ 지점 이미지 출력 -->
<img src="img/<?php echo $data['img']; ?>" class="detail-img" alt="지점 이미지">

    <h3>지점 특징</h3>
    <p><?php echo nl2br($data['feature']); ?></p>

    <h3>주소</h3>
    <p><?php echo $data['address']; ?></p>

    <!-- 네이버 지도 팝업 -->
    <a class="map-btn"
       href="<?php echo $data['map_link']; ?>"
       onclick="
            const w = 1200, h = 600;
            const left = (screen.width - w) / 2;
            const top = (screen.height - h) / 2;
            window.open('<?php echo $data['map_link']; ?>','mapPopup',
                `width=${w},height=${h},left=${left},top=${top}`);
            return false;">
        📍 네이버지도 보기
    </a>

    <!-- 대표 메뉴 버튼 -->
    <br>
    <a class="menu-btn" href="branch_menu.php?branch_id=<?php echo $data['id']; ?>">
        🍞 대표 메뉴 보러가기
    </a>

</div>

</body>
</html>

