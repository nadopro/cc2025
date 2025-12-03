<?php
require_once "db.php";

$branch_id = $_GET['id']; // 어떤 지점인지 확인하기

// 지점 기본 정보 조회
$branch = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM branch WHERE id = $branch_id"
));

// 메뉴 5개 조회
$menus = mysqli_query($conn,
    "SELECT * FROM branch_menu WHERE branch_id = $branch_id ORDER BY id ASC"
);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title><?php echo $branch['name']; ?> 대표메뉴</title>
<link rel="stylesheet" href="style.css">

<style>
.menu-wrap {
    max-width: 900px;
    margin: 40px auto;
    padding: 40px;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

.menu-title {
    font-size: 32px;
    font-weight: bold;
    margin-bottom: 20px;
}

.menu-card {
    display: flex;
    align-items: center;
    margin-bottom: 25px;
    background: #f5eee2;
    padding: 18px;
    border-radius: 12px;
}

.menu-img {
    width: 150px;
    height: 120px;
    border-radius: 10px;
    object-fit: cover;
    margin-right: 20px;
}

.menu-name {
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 6px;
}

.menu-desc {
    font-size: 16px;
    color: #444;
}

/* 오른쪽 상단 뒤로가기 버튼 */
.back-btn {
    position: absolute;
    right: 40px;
    top: 130px;
    background: #ccc;
    color: #333;
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
}
.back-btn:hover {
    background: #b5b5b5;
}
</style>
</head>

<body>

<?php include "topmenu.php"; ?>

<a class="back-btn" href="branch.php?id=<?php echo $branch_id; ?>">⬅ 뒤로가기</a>

<div class="menu-wrap">
    <h1 class="menu-title"><?php echo $branch['name']; ?> 대표메뉴</h1>

    <?php while ($m = mysqli_fetch_assoc($menus)) { ?>
        <div class="menu-card">
            <img src="<?php echo $m['menu_img']; ?>" class="menu-img">
            
            <div>
                <div class="menu-name"><?php echo $m['menu_name']; ?></div>
                <div class="menu-desc"><?php echo nl2br($m['menu_desc']); ?></div>
            </div>
        </div>
    <?php } ?>
</div>

</body>
</html>
