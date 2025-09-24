<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>PHP 9.php 예제</title>
</head>
<body>
    <header>
        <h1>1부터 100까지 출력 (3, 6, 9 포함된 숫자 제외)</h1>
        <hr>
    </header>

    <main>
        <?php
        for ($i = 1; $i <= 100; $i = $i + 1) {
            // 숫자를 문자열로 변환해서 '3', '6', '9' 포함 여부 확인
            $str = strval($i);
            if (strpos($str, '3') !== false || strpos($str, '6') !== false || strpos($str, '9') !== false) {
                continue; // 건너뛰기
            }
            echo $i . " ";
        }
        ?>
    </main>

    <footer>
        <hr>
        <p>&copy; 2025 PHP 연습 페이지</p>
    </footer>
</body>
</html>
