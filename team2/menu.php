<?php
// menu.php
?>
<style>
/* ================================
   NAV 기본 스타일
================================= */
.jml-navbar {
    position: sticky;
    top: 0;
    z-index: 1200;
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(0,0,0,0.08);
    padding: 10px 0;
}

/* 내부 flex 구조: 좌 / 중간(검색) / 우 */
.nav-inner {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between; /* 세 영역을 균등 배치 */
}

/* ================================
   좌측 메뉴 그룹
================================= */
.nav-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

/* 드롭다운 기본 */
.jml-navbar .nav-link {
    font-size: 0.9rem;
    font-weight: 600;
    color: #000;
    padding: 6px 12px;
    border-radius: 999px;
}

.jml-navbar .nav-link:hover {
    opacity: 0.55;
}

/* 드롭다운 */
.jml-navbar .dropdown-menu {
    font-size: 0.85rem;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

/* ================================
   중앙 검색창
================================= */
.nav-center {
    flex: 1; /* 여기가 핵심 – 가운데 영역을 넓게 확보 */
    display: flex;
    justify-content: center;
}

.nav-search-box {
    position: relative;
    width: 60%; /* 검색창 너비 – 원하는 만큼 조절 가능 */
    min-width: 240px;
}

.nav-search-input {
    width: 100%;
    padding: 7px 14px;
    font-size: 0.9rem;
    border-radius: 999px;
    border: 1px solid rgba(0,0,0,0.3);
    background: #ffffffcc;
}

.nav-search-input:focus {
    outline: none;
    border-color: #000;
}

/* 인기 검색어 */
.search-suggest-box {
    position: absolute;
    top: 42px;
    width: 100%;
    background: #fff;
    border-radius: 10px;
    padding: 8px 0;
    box-shadow: 0 4px 18px rgba(0,0,0,0.12);
    display: none;
    z-index: 9999;
}

.search-suggest-item {
    padding: 8px 14px;
    cursor: pointer;
}
.search-suggest-item:hover {
    background: #f3f4f6;
}

/* ================================
   우측 메뉴 (장바구니/로그인)
================================= */
.nav-right {
    display: flex;
    align-items: center;
    gap: 10px;
}

.jml-top-link {
    font-size: 0.82rem;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 999px;
    border: 1px solid rgba(0,0,0,0.2);
}

/* 모바일 */
@media (max-width: 992px) {
    .nav-inner {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
    }
    .nav-center {
        width: 100%;
    }
    .nav-search-box {
        width: 100%;
    }
}
</style>

<nav class="navbar jml-navbar">
  <div class="container nav-inner">

    <!-- ⭐ 왼쪽 메뉴 -->
    <div class="nav-left">
      <a class="navbar-brand fw-bold" href="index.php?cmd=home">DEMO</a>

      <!-- C/S -->
      <div class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">C/S</a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="index.php?cmd=board&bid=1">배송문의</a></li>
          <li><a class="dropdown-item" href="index.php?cmd=board&bid=2">상품문의</a></li>
        </ul>
      </div>

      <!-- 카테고리 -->
      <div class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">카테고리</a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="index.php?cmd=upper">상의</a></li>
          <li><a class="dropdown-item" href="index.php?cmd=bottom">하의</a></li>
          <li><a class="dropdown-item" href="index.php?cmd=shoes">신발</a></li>
        </ul>
      </div>

      <!-- 관리자 메뉴 -->
      <?php if (isset($_SESSION['sino_level']) && $_SESSION['sino_level'] == 9): ?>
      <div class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">관리자</a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="index.php?cmd=manModel">제품관리</a></li>
          <li><a class="dropdown-item" href="index.php?cmd=subModel">제품목록</a></li>
          <li><a class="dropdown-item" href="index.php?cmd=manOrder">주문관리</a></li>
        </ul>
      </div>
      <?php endif; ?>
    </div>

    <!-- ⭐ 중앙 검색창 -->
    <div class="nav-center">
      <div class="nav-search-box">
        <form action="index.php" method="get">
          <input type="hidden" name="cmd" value="search">
          <input type="text" name="q" class="nav-search-input" placeholder="검색어를 입력하세요" autocomplete="off" id="navSearchInput">
        </form>

        <div class="search-suggest-box" id="searchSuggestBox">
          <div class="search-suggest-item">후드티</div>
          <div class="search-suggest-item">데님 팬츠</div>
          <div class="search-suggest-item">패딩</div>
          <div class="search-suggest-item">크롭 니트</div>
          <div class="search-suggest-item">롱부츠</div>
        </div>
      </div>
    </div>

    <!-- ⭐ 우측 메뉴 -->
    <div class="nav-right">
      <a href="index.php?cmd=cart" class="jml-top-link">장바구니</a>

      <?php if (isset($_SESSION['sino_id'])): ?>
        <a href="index.php?cmd=logout" class="jml-top-link">
          <?= htmlspecialchars($_SESSION['sino_name']) ?> 로그아웃
        </a>
      <?php else: ?>
        <a href="index.php?cmd=printLogin" class="jml-top-link">로그인</a>
      <?php endif; ?>
    </div>

  </div>
</nav>

<script>
// 검색창 동작
document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("navSearchInput");
    const box = document.getElementById("searchSuggestBox");

    input.addEventListener("focus", () => box.style.display = "block");
    input.addEventListener("input", () => {
        const k = input.value.toLowerCase();
        document.querySelectorAll(".search-suggest-item").forEach(item => {
            item.style.display = item.textContent.toLowerCase().includes(k) ? "block" : "none";
        });
    });

    document.querySelectorAll(".search-suggest-item").forEach(item => {
        item.addEventListener("click", () => {
            input.value = item.textContent;
            box.style.display = "none";
            input.closest("form").submit();
        });
    });

    document.addEventListener("click", e => {
        if (!input.contains(e.target) && !box.contains(e.target)) {
            box.style.display = "none";
        }
    });
});
</script>
