<div class="container mt-4">
    <h2 class="mb-4">📚 My Page – 내가 좋아요한 구절</h2>

    <div id="liked-list"></div>
</div>

<script>
// 좋아요 목록 화면에 그리는 함수
function renderLiked() {
    let liked;

    try {
        liked = JSON.parse(localStorage.getItem("likedQuotes") || "[]");
    } catch (e) {
        liked = [];
    }

    const listDiv = document.getElementById("liked-list");

    if (!liked || liked.length === 0) {
        listDiv.innerHTML = "<div class='alert alert-info'>아직 좋아요한 구절이 없습니다.</div>";
        return;
    }

    let html = "";
    liked.forEach((q, index) => {
        html += `
            <div class="card mb-3">
                <div class="card-body">

                    <h5>${q.origin}</h5>
                    <p class="text-muted">${q.interpret || ""}</p>

                    <button class="btn btn-outline-danger btn-sm"
                            onclick="removeLiked(${index})">
                        삭제
                    </button>

                    <button class="btn btn-outline-primary btn-sm"
                            onclick="copyLiked(${index})">
                        공유 / 복사
                    </button>

                </div>
            </div>
        `;
    });

    listDiv.innerHTML = html;
}

// 삭제 기능
function removeLiked(index) {
    let liked = JSON.parse(localStorage.getItem("likedQuotes") || "[]");
    liked.splice(index, 1);
    localStorage.setItem("likedQuotes", JSON.stringify(liked));
    renderLiked(); // 다시 그리기
}

// 복사 기능 (원문만 복사)
function copyLiked(index) {
    let liked = JSON.parse(localStorage.getItem("likedQuotes") || "[]");
    const text = liked[index]?.origin || "";

    navigator.clipboard.writeText(text)
        .then(() => alert("복사됨!"))
        .catch(() => alert("복사에 실패했습니다."));
}

// 페이지 로드 시 실행
document.addEventListener("DOMContentLoaded", renderLiked);
</script>

<div class="container mt-4">
    <h2 class="mb-4">📚 한국고전종합DB 바로가기 </h2>

    <!-- 🔗 한국고전종합DB 링크 -->
    <p class="mb-3">
        <a href="https://db.itkc.or.kr" target="_blank"
           class="btn btn-outline-secondary btn-sm">
            🔗 한국고전종합DB 바로가기
        </a>
    </p>

    <div id="liked-list"></div>
</div>

<script>
// 좋아요 목록 화면에 그리는 함수
function renderLiked() {
    let liked;

    try {
        liked = JSON.parse(localStorage.getItem("likedQuotes") || "[]");
    } catch (e) {
        liked = [];
    }

    const listDiv = document.getElementById("liked-list");

    if (!liked || liked.length === 0) {
        listDiv.innerHTML = "<div class='alert alert-info'>아직 좋아요한 구절이 없습니다.</div>";
        return;
    }

    let html = "";
    liked.forEach((q, index) => {
        html += `
            <div class="card mb-3">
                <div class="card-body">

                    <h5>${q.origin}</h5>
                    <p class="text-muted">${q.interpret || ""}</p>

                    <button class="btn btn-outline-danger btn-sm"
                            onclick="removeLiked(${index})">
                        삭제
                    </button>

                    <button class="btn btn-outline-primary btn-sm"
                            onclick="copyLiked(${index})">
                        공유 / 복사
                    </button>

                </div>
            </div>
        `;
    });

    listDiv.innerHTML = html;
}

// 삭제 기능
function removeLiked(index) {
    let liked = JSON.parse(localStorage.getItem("likedQuotes") || "[]");
    liked.splice(index, 1);
    localStorage.setItem("likedQuotes", JSON.stringify(liked));
    renderLiked(); // 다시 그리기
}

// 복사 기능 (원문만 복사)
function copyLiked(index) {
    let liked = JSON.parse(localStorage.getItem("likedQuotes") || "[]");
    const text = liked[index]?.origin || "";

    navigator.clipboard.writeText(text)
        .then(() => alert("복사됨!"))
        .catch(() => alert("복사에 실패했습니다."));
}

// 페이지 로드 시 실행
document.addEventListener("DOMContentLoaded", renderLiked);
</script>