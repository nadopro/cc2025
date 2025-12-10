<?php
  session_save_path("sess");
  session_start();

  include "db.php";
  include "config.php";
  include "head.php";

  $conn = connectDB();
?>

<body class="d-flex flex-column min-vh-100">

<?php
  include "menu.php";

  // localhost/index.php?cmd=test
?>

<main class="container my-4 flex-grow-1">
<?php
  // cmd 값 처리
  if (!isset($_GET['cmd']) || $_GET['cmd'] === '') {
      // 쿼리스트링이 없을 때 → 기본 홈
      $cmd = "home";
  } else {
      $cmd = $_GET['cmd'];
  }

  // cmd가 "0"이면 강제로 home으로 보냄 (네가 편하게 쓰라고 그대로 지원)
  if ($cmd === "0") {
      $page = "home";
  } else {
      $page = $cmd;
  }

  if (is_file("$page.php")) {
      include "$page.php";
  } else {
      http_response_code(404);
      echo '<div class="alert alert-danger">요청하신 페이지를 찾을 수 없습니다.</div>';
  }
?>
</main>



<?php
  include "tail.php";
?>

