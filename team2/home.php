<?php
// home.php
// 전제: index.php에서 $conn = connectDB(); 완료, Bootstrap 5 로드됨.

// DB에서 이미지 불러오기 (img1 기준)
$sql = "SELECT img1 FROM model WHERE img1 IS NOT NULL AND img1 <> '' ORDER BY id DESC";
$result = $conn->query($sql);

$images = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $images[] = $row['img1'];
    }
}



// 이미지가 하나도 없을 때 대비
if (empty($images)) {
    $images[] = 'img/no-image.png'; // 적당한 기본 이미지 경로
}

// 왼쪽: 정순 / 오른쪽: 역순
$leftImages  = $images;
$rightImages = array_reverse($images);

// 첫 장이 같으면 오른쪽 배열을 한 번 틀어서 다르게 맞춰줌
if ($leftImages[0] === $rightImages[0] && count($rightImages) > 1) {
    $first = array_shift($rightImages);
    $rightImages[] = $first;
}
?>


<style>
.brand-story {
    max-width: 1200px;
    margin: 80px auto 80px auto;
    padding: 0 20px;
    text-align: center;
}

.brand-story-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 15px;
}

.brand-story-sub {
    font-size: 15px;
    color: #555;
    line-height: 1.7;
    max-width: 700px;
    margin: 0 auto 30px auto;
}

.brand-story-img {
    width: 100%;
    max-width: 900px;
    margin: 0 auto;
    border-radius: 10px;
    overflow: hidden;
}
</style>

<div class="brand-story">
    <div class="brand-story-title">OUR STORY</div>
    <div class="brand-story-sub">
        우리는 일상을 특별하게 바꾸는 패션을 만듭니다.<br>
        미니멀하지만 감각적인 디자인, 부담 없는 가격, 안정적인 품질.<br>
        이 세 가지를 기준으로 고객에게 가장 합리적인 선택지를 제공합니다.
    </div>
    
</div>


<style>



/* ===== 이벤트 배너 ===== */
.event-banner-wrap {
    width: 100%;
    background: linear-gradient(135deg, #ff7f7f, #ffb347);
    margin: 60px 0 40px 0;
}

.event-banner-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 28px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    color: #fff;
}

.event-banner-text-main {
    font-size: 22px;
    font-weight: 700;
}

.event-banner-text-sub {
    font-size: 14px;
    opacity: 0.9;
    margin-top: 5px;
}

.event-banner-btn {
    padding: 10px 22px;
    border-radius: 999px;
    border: 1px solid rgba(255,255,255,0.8);
    background: rgba(255,255,255,0.1);
    color: #fff;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    white-space: nowrap;
    transition: 0.2s ease;
}

.event-banner-btn:hover {
    background: #fff;
    color: #ff6b6b;
}

@media (max-width: 768px) {
    .event-banner-inner {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<!-- ===== 이벤트 배너 (전체 클릭 or 버튼만 클릭 둘 다 가능하게) ===== -->
<a href="index.php?cmd=event&code=welcome" style="text-decoration:none;">
    <div class="event-banner-wrap">
        <div class="event-banner-inner">
            <div>
                <div class="event-banner-text-main">
                    신규 회원 전원 3,000원 쿠폰
                </div>
                <div class="event-banner-text-sub">
                    지금 가입하면 즉시 사용 가능한 할인 쿠폰을 드립니다.
                </div>
            </div>
            <div>
                <span class="event-banner-btn">이벤트 자세히 보기</span>
            </div>
        </div>
    </div>
</a>

<style>
  html, body {
      margin:0;
      padding:0;
      width:100%;
  }
  body {
      background:#ffffff;
      font-family:'Inter','Pretendard',sans-serif;
  }

  .hero-split {
      width:100%;
      height:min(72vh, 620px);
      display:flex;
      overflow:hidden;
  }

  .hero-panel {
      flex:1 1 50%;
      position:relative;
      overflow:hidden;
  }

  .hero-panel img {
      width:100%;
      height:100%;
      object-fit:cover;
      display:block;
      transition: opacity 0.6s ease;
      opacity:1;
  }

  .hero-panel img.fade-out {
      opacity:0;
  }

  @media(max-width:768px){
      .hero-split {
          flex-direction:column;
          height:auto;
      }
      .hero-panel {
          height:50vh;
      }
  }

  
  /* 드롭다운이 사진 뒤로 안 가리게 */
  .dropdown-menu {
      z-index: 9999 !important;
      position: absolute !important;
  }
</style>

<div class="hero-split">
  <!-- 왼쪽 패널 -->
  <div class="hero-panel">
    <?php if (!empty($leftImages)): ?>
      <img id="heroLeft"
           src="<?= htmlspecialchars($leftImages[0]) ?>"
           alt="left hero">
    <?php else: ?>
      <div class="bg-light d-flex align-items-center justify-content-center h-100">
        <span class="text-muted small">이미지 없음</span>
      </div>
    <?php endif; ?>
  </div>

  <!-- 오른쪽 패널 -->
  <div class="hero-panel">
    <?php if (!empty($rightImages)): ?>
      <img id="heroRight"
           src="<?= htmlspecialchars($rightImages[0]) ?>"
           alt="right hero">
    <?php else: ?>
      <div class="bg-light d-flex align-items-center justify-content-center h-100">
        <span class="text-muted small">이미지 없음</span>
      </div>
    <?php endif; ?>
  </div>
</div>

<script>
// PHP 배열을 JS로 가져오기
const leftImages  = <?= json_encode($leftImages,  JSON_UNESCAPED_UNICODE) ?>;
const rightImages = <?= json_encode($rightImages, JSON_UNESCAPED_UNICODE) ?>;

const leftImgEl  = document.getElementById('heroLeft');
const rightImgEl = document.getElementById('heroRight');

let leftIndex  = 0;
let rightIndex = 0;

// 2~4초 랜덤 딜레이(ms)
function randomDelay() {
    return (Math.floor(Math.random() * 3) + 2) * 1000; // 2,3,4초
}

function nextIndex(current, arrLen) {
    return (current + 1) % arrLen;
}

// 왼쪽 슬라이더
function runLeftSlider() {
    if (!leftImgEl || leftImages.length === 0) return;

    setTimeout(() => {
        // 페이드 아웃
        leftImgEl.classList.add('fade-out');

        setTimeout(() => {
            let next = nextIndex(leftIndex, leftImages.length);

            // 오른쪽과 같은 이미지가 되려 하면 한 칸 더 넘김(가능한 한 겹침 방지)
            if (rightImgEl && leftImages[next] === rightImgEl.src.replace(location.origin + '/', '').replace(/^\/+/, '')) {
                next = nextIndex(next, leftImages.length);
            }

            leftIndex = next;
            leftImgEl.src = leftImages[leftIndex];
            leftImgEl.classList.remove('fade-out');
        }, 400);

        runLeftSlider();
    }, randomDelay());
}

// 오른쪽 슬라이더
function runRightSlider() {
    if (!rightImgEl || rightImages.length === 0) return;

    setTimeout(() => {
        rightImgEl.classList.add('fade-out');

        setTimeout(() => {
            let next = nextIndex(rightIndex, rightImages.length);

            // 왼쪽과 같은 이미지가 되려 하면 한 칸 더 넘김(가능한 한 겹침 방지)
            if (leftImgEl && rightImages[next] === leftImgEl.src.replace(location.origin + '/', '').replace(/^\/+/, '')) {
                next = nextIndex(next, rightImages.length);
            }

            rightIndex = next;
            rightImgEl.src = rightImages[rightIndex];
            rightImgEl.classList.remove('fade-out');
        }, 400);

        runRightSlider();
    }, randomDelay());
}

document.addEventListener('DOMContentLoaded', () => {
    runLeftSlider();
    runRightSlider();
});
</script>

<?php
// ===== 베스트 상품 불러오기 =====
// 조회수나 판매량 컬럼이 있으면 교체 가능:
// 예: ORDER BY view DESC
$sql_best = "
    SELECT id, img1, name, price
    FROM model
    WHERE img1 IS NOT NULL AND img1 <> ''
    ORDER BY id DESC
    LIMIT 8
";
$result_best = $conn->query($sql_best);

$bestItems = [];
if ($result_best && $result_best->num_rows > 0) {
    while ($row = $result_best->fetch_assoc()) {
        $bestItems[] = $row;
    }
}
?>

<style>
.best-section {
    width: 100%;
    max-width: 1200px;
    margin: 60px auto 80px auto;
    padding: 0 20px;
}

.best-title {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 25px;
}

.best-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 25px;
}

.best-item {
    position: relative;
    cursor: pointer;
    text-decoration: none;
    color: #000;
}

.best-item img {
    width: 100%;
    height: 280px;
    object-fit: cover;
    border-radius: 4px;
    transition: 0.25s ease;
}

.best-item img:hover {
    transform: scale(1.03);
}

/* BEST 배지 */
.best-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #ff5050;
    color: #fff;
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 600;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

.best-name {
    font-size: 15px;
    margin-top: 8px;
}

.best-price {
    font-size: 14px;
    margin-top: 2px;
    color: #666;
}
</style>

<div class="best-section">
    <div class="best-title">BEST ITEMS</div>

    <div class="best-grid">

        <?php if (!empty($bestItems)): ?>
            <?php foreach ($bestItems as $index => $item): ?>
                
                <a class="best-item"
                   href="index.php?cmd=detail&id=<?= $item['id'] ?>">
                   
                    <div class="best-badge">
                        BEST <?= $index + 1 ?>
                    </div>

                    <img src="<?= htmlspecialchars($item['img1']) ?>"
                         alt="<?= htmlspecialchars($item['name']) ?>">

                    <div class="best-name">
                        <?= htmlspecialchars($item['name']) ?>
                    </div>

                    <div class="best-price">
                        <?= number_format($item['price']) ?>원
                    </div>

                </a>
            
            <?php endforeach; ?>

        <?php else: ?>
            <div>베스트 상품이 없습니다.</div>
        <?php endif; ?>

    </div>
</div>


<?php
// --- 신상품 8개 불러오기 ----------------------------------------
$sql_new = "
    SELECT id, img1, name, price 
    FROM model 
    WHERE img1 IS NOT NULL AND img1 <> ''
    ORDER BY id DESC
    LIMIT 8
";
$result_new = $conn->query($sql_new);

$newItems = [];
if ($result_new && $result_new->num_rows > 0) {
    while ($row = $result_new->fetch_assoc()) {
        $newItems[] = $row;
    }
}
?>

<style>
.new-section {
    width: 100%;
    max-width: 1200px;
    margin: 60px auto 80px auto;
    padding: 0 20px;
}

.new-title {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 25px;
}

.new-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 25px;
}

.new-item {
    cursor: pointer;
    text-decoration: none;
    color: #000;
}

.new-item img {
    width: 100%;
    height: 280px;
    object-fit: cover;
    border-radius: 4px;
    transition: 0.25s ease;
}

.new-item img:hover {
    transform: scale(1.03);
}

.new-name {
    font-size: 15px;
    margin-top: 8px;
}

.new-price {
    font-size: 14px;
    margin-top: 2px;
    color: #666;
}
</style>

<div class="new-section">
    <div class="new-title">신상품 NEW ARRIVALS</div>

    <div class="new-grid">

        <?php if (!empty($newItems)): ?>
            <?php foreach ($newItems as $item): ?>
                <a class="new-item"
                   href="index.php?cmd=detail&id=<?= $item['id'] ?>">
                   
                    <img src="<?= htmlspecialchars($item['img1']) ?>" 
                         alt="<?= htmlspecialchars($item['name']) ?>">

                    <div class="new-name">
                        <?= htmlspecialchars($item['name']) ?>
                    </div>

                    <div class="new-price">
                        <?= number_format($item['price']) ?>원
                    </div>

                </a>
            <?php endforeach; ?>

        <?php else: ?>
            <div>신상품이 없습니다.</div>
        <?php endif; ?>

    </div>
</div>


<style>
/* ===== 왼쪽 세로 카테고리 메뉴 ===== */
.left-category-menu {
    position: fixed;
    top: 140px;               /* 헤더 높이에 맞춰 조절 가능 */
    left: 20px;
    width: 140px;
    background: #fff;
    border: 1px solid #e5e5e5;
    border-radius: 8px;
    padding: 12px 0;
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    z-index: 999;
}

.left-category-title {
    font-size: 14px;
    font-weight: 600;
    padding: 6px 14px 10px 14px;
    border-bottom: 1px solid #f2f2f2;
}

.left-category-list a {
    display: block;
    padding: 10px 14px;
    font-size: 14px;
    color: #333;
    text-decoration: none;
    transition: 0.18s ease;
}

.left-category-list a:hover {
    background: #f8f8f8;
    padding-left: 18px;
}

@media (max-width: 992px) {
    .left-category-menu {
        display: none; /* 모바일에서는 숨김 */
    }
}
</style>

<div class="left-category-menu">
    <div class="left-category-title">카테고리</div>
    <div class="left-category-list">
        <a href="index.php?cmd=upper">상의</a>
        <a href="index.php?cmd=bottom">하의</a>
        <a href="index.php?cmd=shoes">신발</a>
        <a href="index.php?cmd=modelList&cat=outer">아우터</a>
        <a href="index.php?cmd=modelList&cat=set">세트</a>
        <a href="index.php?cmd=modelList&cat=acc">악세사리</a>
    </div>
</div>

<style>
/* ===== 문의하기 플로팅 버튼 ===== */
.floating-contact-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 62px;
    height: 62px;
    background: #000000ff;
    border-radius: 50%;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    transition: 0.22s ease;
    z-index: 9999;
}

.floating-contact-btn:hover {
    transform: translateY(-4px);
    background: #ff5050;
}
</style>

<a href="https://pf.kakao.com/당신의_채널" class="floating-contact-btn">
    💬
</a>

<!-- 아이콘 (Bootstrap Icons) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- 오디오 -->
<audio id="bgMusic">
    <source src="music/song.mp3" type="audio/mpeg">
</audio>

<!-- 🔘 노래 재생 버튼 (문의하기 버튼과 동일 크기) -->
<button type="button" id="musicBtn"
        class="btn btn-primary rounded-circle shadow"
        onclick="toggleMusic()"
        style="
            position: fixed;
            bottom: 105px;     /* 문의하기 바로 위 */
            right: 30px;
            width: 62px;       /* 문의하기와 동일 크기 */
            height: 62px;      /* 문의하기와 동일 크기 */
            z-index: 2000;
            display: flex;
            justify-content: center;
            align-items: center;
        ">
    <i class="bi bi-music-note-beamed" style="font-size: 26px;"></i>
</button>

<script>
function toggleMusic() {
    const audio = document.getElementById('bgMusic');
    const btn   = document.getElementById('musicBtn');

    if (audio.paused) {
        audio.play();
        btn.innerHTML = '<i class="bi bi-pause-fill" style="font-size: 26px;"></i>';
    } else {
        audio.pause();
        btn.innerHTML = '<i class="bi bi-music-note-beamed" style="font-size: 26px;"></i>';
    }
}
</script>



<style>
/* ===== FOOTER ===== */
.footer-wrap {
    width: 100%;
    background: #f9f9f9;
    border-top: 1px solid #e5e5e5;
    margin-top: 80px;
    padding: 40px 0 30px 0;
    font-size: 14px;
    color: #555;
}

.footer-inner {
    max-width: 1250px;
    margin: 0 auto;
    padding: 0 20px;

    display: grid;
    grid-template-columns: 1.2fr 1fr 1.2fr;
    gap: 40px;
}

.footer-title {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 12px;
    color: #333;
}

.footer-link a {
    display: block;
    color: #555;
    text-decoration: none;
    margin-bottom: 6px;
    transition: 0.15s ease;
}

.footer-link a:hover {
    color: #000;
}

/* 사업자 정보 텍스트 */
.footer-info div {
    margin-bottom: 4px;
    line-height: 1.5;
}

/* 저작권 */
.footer-bottom {
    max-width: 1250px;
    margin: 30px auto 0 auto;
    font-size: 12px;
    color: #666;
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

/* 반응형 */
@media (max-width: 900px) {
    .footer-inner {
        grid-template-columns: 1fr;
    }
}
</style>

<footer class="footer-wrap">

    <div class="footer-inner">

        <!-- 왼쪽: 브랜드 소개 -->
        <div>
            <div class="footer-title">BRAND</div>
            <div style="line-height:1.6; margin-bottom:12px;">
                최신 트렌드를 반영한 감각적인 패션 쇼핑몰입니다.<br>
                좋은 퀄리티와 합리적인 가격으로 고객님을 만족시키겠습니다.
            </div>

            <div class="footer-link">
                <a href="#">회사소개</a>
                <a href="#">이용약관</a>
                <a href="#">개인정보 처리방침</a>
            </div>
        </div>

        <!-- 중앙: 고객센터 -->
        <div>
            <div class="footer-title">고객센터</div>
            
            <div class="footer-info">
                <div>📞 010-1234-5678</div>
                <div>카카오톡 문의: @yourshop</div>
                <div>운영시간: 평일 10:00 ~ 17:00</div>
                <div>점심시간: 12:00 ~ 13:00</div>
                <div>주말/공휴일 휴무</div>
            </div>

            <div class="footer-link" style="margin-top:12px;">
                <a href="index.php?cmd=contact">문의하기</a>
                <a href="#">배송 안내</a>
                <a href="#">교환/반품 안내</a>
            </div>
        </div>

        <!-- 오른쪽: 사업자 정보 -->
        <div>
            <div class="footer-title">사업자 정보</div>

            <div class="footer-info">
                <div>상호명 : DENo</div>
                <div>대표 : 김민주 이승찬 조현규</div>
                <div>사업자등록번호 : 123-45-67890</div>
                <div>통신판매업번호 : 제2025-대전유성-0230호</div>
                <div>주소 : 대전 유성구 충남대학교</div>
                <div>이메일 : DEMOshop@gmail.com</div>
            </div>
        </div>

    </div>

    <div class="footer-bottom">
        ⓒ 2025 YOURSHOP. All Rights Reserved.
    </div>

</footer>


