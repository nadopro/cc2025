<?php
// difference.php

// 데이터베이스 연결 설정
$host = "localhost";
$user = "team4";
$pass = "1111";
$dbname = "team4";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT content FROM difference";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>위대한 개츠비: 원작 vs 영화 차이점</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<script>
    // 고대비 모드 감지 (Windows/High Contrast)
    const isHighContrast = window.matchMedia('(forced-colors: active)').matches
                           || window.matchMedia('(prefers-contrast: high)').matches;
    if(isHighContrast){
        document.body.classList.add('high-contrast');
    }
</script>

<?php
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $content = $row['content'];

    $lines = explode("\n", $content);

    echo "<table class='difference-table'>";
    echo "<caption>위대한 개츠비: 원작 vs 영화 차이점</caption>";

    foreach ($lines as $index => $line) {
        $cols = preg_split("/\t|\|/", $line);
        if ($index === 0) {
            echo "<tr>";
            foreach ($cols as $col) {
                echo "<th>" . htmlspecialchars(trim($col)) . "</th>";
            }
            echo "</tr>";
        } else {
            echo "<tr>";
            foreach ($cols as $col) {
                echo "<td>" . htmlspecialchars(trim($col)) . "</td>";
            }
            echo "</tr>";
        }
    }

    echo "</table>";
} else {
    echo "<p class='no-data'>차이점 데이터가 없습니다.</p>";
}

$conn->close();
?>

</body>
</html>
