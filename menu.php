<?php
// Bootstrap 5 Navbar (드롭다운 3개)
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">문화콘텐츠실습</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="메뉴 토글">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

        <!-- 메뉴1 -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="menu1Dropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            한문학과
          </a>
          <ul class="dropdown-menu" aria-labelledby="menu1Dropdown">
            <li><a class="dropdown-item" href="index.php?cmd=intro">학과소개</a></li>
            <li><a class="dropdown-item" href="index.php?cmd=menu1-2">메뉴1-2</a></li>
            <li><a class="dropdown-item" href="index.php?cmd=menu1-3">메뉴1-3</a></li>
          </ul>
        </li>

        <!-- 메뉴2 -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="menu2Dropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            메뉴2
          </a>
          <ul class="dropdown-menu" aria-labelledby="menu2Dropdown">
            <li><a class="dropdown-item" href="index.php?cmd=menu2-1">메뉴2-1</a></li>
            <li><a class="dropdown-item" href="index.php?cmd=menu2-2">메뉴2-2</a></li>
          </ul>
        </li>

        <!-- 메뉴3 -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="menu3Dropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            메뉴3
          </a>
          <ul class="dropdown-menu" aria-labelledby="menu3Dropdown">
            <li><a class="dropdown-item" href="index.php?cmd=menu3-1">메뉴3-1</a></li>
            <li><a class="dropdown-item" href="index.php?cmd=menu3-2">메뉴3-2</a></li>
          </ul>
        </li>

      </ul>

      <!-- 우측 예시: 로그인 링크 등 -->
      <div class="d-flex">
        <a class="btn btn-outline-light btn-sm" href="index.php?cmd=printLogin">로그인</a>
      </div>
    </div>
  </div>
</nav>