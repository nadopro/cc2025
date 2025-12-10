<?php
    $id   = $_POST["id"];
    $pass = $_POST["pass"];

    echo "id = $id <br>pass = $pass <br>";

    // SQL 쿼리 (비밀번호 암호화 X)
    $sql = "SELECT * FROM users WHERE id='$id' AND pass='$pass'";
    $result = mysqli_query($conn, $sql);

    // 결과 확인
    if ($result) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if ($row) {
            // 로그인 성공
            $_SESSION["sino_id"]   = $row["name"];
            $_SESSION["sino_name"] = $row["name"];
            $_SESSION["sino_level"] = $row["level"];

            $msg = "로그인 성공";
        } else {
            // 로그인 실패
            $msg = "아이디와 비밀번호를 확인하세요";
        }
    } else {
        echo "쿼리 오류: " . mysqli_error($conn);
    }

    echo "
    <script>
        alert('$msg');
        location.href='index.php';
    </script>
    ";
?>