<?php
    $name1 = "홍길동";
    $name2 = "이순신";

    $names = array("이순신", "홍길동", "정약용", "이도", "세종", "세조", "송시열");
    echo "$names[0]<br>";
    echo "$names[1]<br>";

    for($i=0; $i<count($names); $i++)
    {
        echo "$i : $names[$i]<br>";
    }

    $std = array();
    $std[] = "aaa";
    $std[] = "bbb";
    $std[] = "ccc";

    for($i=0; $i<count($std); $i++)
    {
        echo "$i : $std[$i]<br>";
    }

    $text = "서울,경기,충청,제주,강원";
    $words = explode(",", $text);

     $text = "水旱果天數乎。果人事乎。堯湯未免。天數也。休咎有徵。人事也。古之人修人事以應天數。";
    $words = explode("。", $text);


    for($i=0; $i<count($words); $i++)
    {
        echo "$i : $words[$i]<br>";
    }


    
?>