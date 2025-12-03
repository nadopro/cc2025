<?php
require_once "db.php";
include "topmenu.php";  // 상단 메뉴
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>성심당 메인 페이지</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="text-center">
<h1>성심당 대표 지점</h1>
</div>
<div class="grid">
<?php
$branch_sql = "SELECT * FROM branch ORDER BY id ASC";
$branch_result = mysqli_query($conn, $branch_sql);
if ($branch_result && mysqli_num_rows($branch_result) > 0) {
while ($row = mysqli_fetch_assoc($branch_result)) {
echo "
<div class='item' onclick=\"location.href='branch.php?id={$row['id']}'\">
<img src='img/{$row['img']}' alt='지점 이미지'>
<div class='item-name'>{$row['name']}</div>
</div>
";
}
}
?>
</div>
</body>
</html>
