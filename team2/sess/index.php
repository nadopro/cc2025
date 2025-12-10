<?php
  session_save_path("sess");
  session_start();

  include "db.php";
  include "config.php";
  include "head.php";

  $conn = connectDB();
?>

<body class="d-flex flex-column min-vh-100">
  <body class="shop-layout">

  <header class="shop-header">
    <div class="shop-header-inner">
      <a href="index.php" class="shop-brand">
        <span class="logo-circle">jml</span>
        <div>
          문화콘텐츠실습
          <small class="d-block">mini 쇼핑몰</small>
        </div>
      </a>

      <nav class="shop-menu">
        <!-- cmd 값에 따라 active 클래스 주면 됨 -->
        <a href="index.php?cmd=upper" class="nav-chip <?php if(($cmd ?? '') === 'upper') echo 'active'; ?>">
          <i class="bi bi-cloud-sun"></i> 상의
        </a>
        <a href="index.php?cmd=bottom" class="nav-chip <?php if(($cmd ?? '') === 'bottom') echo 'active'; ?>">
          <i class="bi bi-cloud-moon"></i> 하의
        </a>
        <a href="index.php?cmd=all" class="nav-chip <?php if(($cmd ?? '') === 'all') echo 'active'; ?>">
          <i class="bi bi-grid-3x3-gap"></i> 전체보기
        </a>
        <a href="index.php?cmd=cart" class="nav-chip <?php if(($cmd ?? '') === 'cart') echo 'active'; ?>">
          <i class="bi bi-bag-heart"></i> 장바구니
        </a>
      </nav>
    </div>
  </header>

  <!-- 여기에 <main class="shop-main"> 안에 upper.php / bottom.php 내용 들어가게 하면 됨 -->


<?php
  include "menu.php";

    // localhost/index.php?cmd=test
?>

<main class="container my-4 flex-grow-1">
<?php
  if(!isset($_GET['cmd']))
    $cmd ="upper";
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