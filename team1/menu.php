<?php
// Bootstrap 5 Navbar (드롭다운 3개)
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">논어 상담소</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="메뉴 토글">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

        <!-- 메뉴1 -->
        <li class="nav-item">
          <a class="nav-link text-white" href="index.php">HOME</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="index.php?cmd=about">ABOUT</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="index.php?cmd=today">오늘의 논어</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="index.php?cmd=mypage">My Page</a>
        </li>

        <?php
        if(isset($_SESSION['sino_level']) and $_SESSION['sino_level'] == 9)
        {
          ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="menu3Dropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              관리자 메뉴
            </a>
            <ul class="dropdown-menu" aria-labelledby="menu3Dropdown">
              <li><a class="dropdown-item" href="index.php?cmd=manData">데이터관리</a></li>
              <li><a class="dropdown-item" href="index.php?cmd=manDataList">데이터목록</a></li>
            </ul>
          </li>
          <?php
        }
        ?>

      </ul>

      <div class="d-flex">
        <?php
        if($_SESSION['sino_id'])
        {
          $name = $_SESSION['sino_name'];
          ?>
          <a class="btn btn-outline-light btn-sm" href="index.php?cmd=logout">
            <?php echo $name; ?> 로그아웃
          </a>
          <?php
        } else {
          ?>
          <a class="btn btn-outline-light btn-sm" href="index.php?cmd=printLogin">로그인</a>
          <?php
        }
        ?>
      </div>

    </div>
  </div>
</nav>
