<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>PHP 기본 예제</title>
</head>
<body>
    <header>
        <h1>PHP 기초 공부</h1>
        <hr>
    </header>

    <main>
        <?php
        // 1. 변수
        $name = "KJMIN";
        $age = 20;

        echo "<h2>변수 예제</h2>";
        echo "안녕하세요, 제 이름은 $name 입니다.<br>";
        echo "나이는 $age 살 입니다.<br><br>";

        // 2. 조건문
        echo "<h2>조건문 예제</h2>";
        if ($age < 13) {
            echo "어린이 입니다.<br><br>";
        } elseif ($age < 20) {
            echo "청소년 입니다.<br><br>";
        } else {
            echo "성인 입니다.<br><br>";
        }

        // 3. 반복문
        echo "<h2>반복문 예제 (for)</h2>";
        for ($i = 1; $i <= 5; $i++) {
            echo "for문 출력: $i <br>";
        }

        echo "<h2>반복문 예제 (while)</h2>";
        $j = 1;
        while ($j <= 5) {
            echo "while문 출력: $j <br>";
            $j++;
        }
        ?>
    </main>

    <footer>
        <hr>
        <p>&copy; 2025 PHP 연습 페이지</p>
    </footer>
</body>
</html>
