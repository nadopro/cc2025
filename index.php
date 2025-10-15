<?php
  session_save_path("sess");
  session_start();

  include "db.php";
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
  if(!isset($_GET['cmd']))
    $cmd = "init";
  else
    $cmd = $_GET['cmd'];

if (is_file("$cmd.php")) {
    include "$cmd.php";
} else {
    http_response_code(404);
    echo '<div class="alert alert-danger">요청하신 페이지를 찾을 수 없습니다.</div>';
}
?>
</main>

<?php
  include "tail.php";
?>