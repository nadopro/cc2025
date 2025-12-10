<footer class="mt-auto py-3 bg-light border-top">
  <div class="container text-center small">
    충남대학교 한문학과 문화콘텐츠실습
  </div>
</footer>

<?php
// 필요 시 DB 연결 종료
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>

<!-- 🌙 다크모드 토글 버튼 (왼쪽 상단 고정) -->
<button id="darkmode-toggle"
        style="
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 9999;
            padding: 5px 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            background: #f2f2f2;
            cursor: pointer;
        ">
    🌙
</button>

<style>
/* 기본 모드 */
body {
    background-color: #ffffff;
    color: #000000;
    transition: background-color .3s, color .3s;
}

/* 다크모드 전체 */
body.dark {
    background-color: #121212;
    color: #f5f5f5;
}

/* 카드 */
body.dark .card {
    background-color: #1e1e1e !important;
    border-color: #333 !important;
    color: #f5f5f5;
}
body.dark .card-body {
    color: #f5f5f5;
}

/* 리스트 그룹 */
body.dark .list-group-item {
    background-color: #1e1e1e !important;
    color: #f5f5f5 !important;
    border-color: #333 !important;
}

/* 연한 글씨(설명 텍스트) 보이게 */
body.dark .text-muted,
body.dark .text-secondary,
body.dark small {
    color: #d0d0d0 !important;
}

/* 링크 & 버튼 색상 튜닝 */
body.dark a {
    color: #9ecbff;
}
body.dark .btn-outline-primary {
    border-color: #9ecbff;
    color: #9ecbff;
}
body.dark .btn-outline-danger {
    border-color: #ff8080;
    color: #ff8080;
}

/* footer도 어둡게 */
body.dark footer {
    background-color: #1e1e1e !important;
    border-color: #333 !important;
    color: #f5f5f5 !important;
}
</style>

<script>
// 페이지 로드 시 기존 설정 반영
document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("darkmode-toggle");

    if (localStorage.getItem("darkmode") === "on") {
        document.body.classList.add("dark");
        btn.textContent = "☀️";
    }

    btn.addEventListener("click", () => {
        document.body.classList.toggle("dark");

        if (document.body.classList.contains("dark")) {
            localStorage.setItem("darkmode", "on");
            btn.textContent = "☀️";
        } else {
            localStorage.setItem("darkmode", "off");
            btn.textContent = "🌙";
        }
    });
});
</script>

</body>
</html>