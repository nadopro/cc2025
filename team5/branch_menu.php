<?php
require_once "db.php";

$branch_id = $_GET['branch_id'];

// 지점 이름 가져오기
$branch = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM branch WHERE id=$branch_id"));

// 메뉴 목록 가져오기
$menus = mysqli_query($conn, "SELECT * FROM branch_menu WHERE branch_id=$branch_id");
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
    margin: 50px auto;
}

.page-title {
    font-size: 36px;
    font-weight: bold;
    margin-bottom: 20px;
}

.grid {
    display: flex;
    justify-content: center;
    gap: 25px;
    flex-wrap: wrap;
    margin-top: 30px;
}

.card {
    width: 240px;
    background: #fff;
    border-radius: 12px;
    padding-bottom: 15px;
    box-shadow: 0 6px 14px rgba(0,0,0,0.15);
    text-align: center;
}

.card img {
    width: 100%;
    height: 170px;
    border-radius: 12px 12px 0 0;
    object-fit: cover;
}

.menu-name {
    font-size: 18px;
    margin-top: 10px;
    font-weight: bold;
}

.back-btn2 {
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
</style>
</head>

<body>

<?php include "topmenu.php"; ?>

<a class="back-btn2" href="branch.php?id=<?php echo $branch_id; ?>">⬅ 뒤로가기</a>

<div class="menu-wrap">
    <h1 class="page-title"><?php echo $branch['name']; ?> 대표메뉴</h1>

    <div class="grid">
        <?php while($m = mysqli_fetch_assoc($menus)) { ?>
        <div class="card">
            <img src="<?php echo $m['menu_img']; ?>">
            <div class="menu-name"><?php echo $m['menu_name']; ?></div>
            <div><?php echo nl2br($m['menu_desc']); ?></div>
        </div>
        <?php } ?>
    </div>
</div>

</body>
</html>
