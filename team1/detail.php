<?php
// detail.php – 문구 상세 페이지

// 1) GET으로 전달받은 구절과 설명 받기
$quote = $_GET['quote'] ?? '';
$explanation = $_GET['exp'] ?? '';

// 2) 저장 버튼 링크에서 사용될 수 있게 escape 처리
$quote_safe = addslashes($quote);
$exp_safe = addslashes($explanation);
?>

<!-- 문구 출력 -->
<h2><?= htmlspecialchars($quote) ?></h2>
<p class="text-muted"><?= nl2br(htmlspecialchars($explanation)) ?></p>

<!-- 저장 버튼 -->
<a href="save_quote.php?quote=<?= urlencode($quote_safe) ?>&exp=<?= urlencode($exp_safe) ?>"
   class="btn btn-outline-success btn-sm">
    ⭐ 이 구절 저장하기
</a>

