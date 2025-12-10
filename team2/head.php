<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DEMO </title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;500;700&display=swap" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <!-- ✅ 전체 페이지 공통 스타일 (모노톤 + 부드러운 hover) -->
  <style>
    html, body {
        margin:0;
        padding:0;
        width:100%;
    }

    body {
        background:#f8fafc;
        font-family: "Noto Sans KR", system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        color:#000;
    }

    /* 기본 링크 무드 */
    a {
        text-decoration:none;
        color:#000;
        transition: opacity 0.2s;
    }
    a:hover {
        opacity:0.55;
    }

    /* 제품 카드 공통 무드 */
    .product-card {
        border:none;
        border-radius:14px;
        background:#fff;
        box-shadow:0 8px 20px rgba(0,0,0,0.07);
        transition: transform 0.18s ease-out, box-shadow 0.18s ease-out;
    }
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow:0 14px 30px rgba(0,0,0,0.13);
    }

    /* 썸네일 이미지 */
    .thumb-img {
        width:100%;
        height:100%;
        object-fit:cover;
        transition: opacity 0.3s;
    }
    .thumb-img:hover {
        opacity:0.8;
    }

    /* 버튼 공통 무드 (검정) */
    .btn.btn-dark {
        border-radius:999px;
        font-weight:600;
        font-size:0.9rem;
        padding:8px 18px;
    }

    /* 네비 링크 hover 색이 검게 변해도 안 이상하게 흐리게만 */
    .nav-link:hover {
        opacity:0.6;
        background:none;
        color:#000;
    }

    /* 활성화 상태 */
    .nav-link.active {
        background:#000 !important;
        color:#fff !important;
        border-radius:999px;
    }
  </style>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
