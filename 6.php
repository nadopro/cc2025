<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bootstrap 5 폼 입력 예제</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  1<br>
  2<br>
  3<br><br>
  <?php

    $age = 17;
    if($age >=18)
    {
      echo "당신은 성인입니다.<br>";
    }else
    {
      echo "미성년자입니다.<br>";
    }


    for($i=1; $i<=7; $i++)
    {
      echo "$i<br>";
    }
  ?>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>