<?php
// 공통 데이터
require __DIR__ . "/init.php";

// 이 페이지 제목
$title = "리뷰 게시판";
echo $title;

// head / 메뉴 / (원하면 intro도 같이)
require __DIR__ . "/head.php";
require __DIR__ . "/menu.php";

?>

<main class="container board-main">
  <section class="card">
    <div class="hd">The Great Gatsby Reviews </div>
    <div class="bd">

      <!-- 간단한 입력 폼 (DB는 나중에 연결) -->
      <form class="review-form" method="post" action="#">
        <div class="review-form-row">
          <label for="nickname">닉네임</label>
          <input type="text" id="nickname" name="nickname" placeholder="닉네임을 입력하세요">
        </div>
        <div class="review-form-row">
          <label for="content">내용</label>
          <textarea id="content" name="content" rows="4" placeholder="영화에 대한 의견을 남겨주세요. (데이터 저장 기능은 나중에 연결 예정입니다.)"></textarea>
        </div>
        <button type="submit" class="btn-review-submit">등록</button>
      </form>

      

    </div>
  </section>
</main>

<?php require __DIR__ . "/tail.php"; ?>