<footer class="mt-auto py-3 bg-light border-top">
  <div class="container text-center small">
    충남대학교 한문학과 문화콘텐츠실습
  </div>
</footer>

<?php
// 필요 시 DB 연결 종료 (mysqli는 스크립트 종료 시 자동 종료되지만 명시적 close 권장)
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>

</body>
</html>