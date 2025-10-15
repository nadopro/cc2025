<?php
    $id = $_POST["id"];
    $pass = $_POST["pass"];

    echo "id = $id <br>pass = $pass <br>";

    if($id == "test" and $pass == "1234")
    {
        $_SESSION["sino_id"] = $id;
        $_SESSION["sino_name"] = "한돌이";
    }else
    {
        
    }

?>