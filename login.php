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
            echo "로그인 성공!<br>";
            echo "환영합니다, " . $_SESSION["sino_name"] . "님";
        } else {
            // 로그인 실패
            echo "아이디 또는 비밀번호가 올바르지 않습니다.";
        }
    } else {
        echo "쿼리 오류: " . mysqli_error($conn);
    }
?>