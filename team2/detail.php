<?php
// detail.php
// 사용: index.php?cmd=detail&idx=제품ID
// 전제: index.php에서 $conn = connectDB();, Bootstrap5 로드 완료
//       로그인 ID는 $_SESSION['sino_id'] 에 저장되어 있다고 가정

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// idx 파라미터로만 받는다
$idx = 0;

if (isset($_GET['idx'])) {
    $idx = intval($_GET['idx']);
} 
// 혹시 기존 링크(id)를 사용하는 페이지가 있을 수 있으니 호환 처리
elseif (isset($_GET['id'])) {  
    $idx = intval($_GET['id']);
}

if ($idx <= 0) {
    echo '<div class="container py-4"><div class="alert alert-danger">잘못된 상품 번호입니다.</div></div>';
    exit;
}


// 2) 상품 조회
$sql  = "SELECT * FROM model WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idx);
$stmt->execute();
$result = $stmt->get_result();
$row    = $result->fetch_assoc();

if (!$row) {
    echo '<div class="container py-4"><div class="alert alert-warning">해당 상품을 찾을 수 없습니다.</div></div>';
    exit;
}

// 3) 데이터 정리
$name     = $row['name'];
$price    = (int)$row['price'];
$colorStr = trim((string)$row['color']);
$sizeStr  = trim((string)$row['size']);
$memo     = (string)$row['memo'];

$colors = $colorStr !== '' ? array_filter(array_map('trim', explode(',', $colorStr))) : [];
$sizes  = $sizeStr  !== '' ? array_filter(array_map('trim', explode(',', $sizeStr)))  : [];

$mainImg = '';
if (!empty($row['img1'])) {
    $mainImg = $row['img1'];
} elseif (!empty($row['img2'])) {
    $mainImg = $row['img2'];
} elseif (!empty($row['img3'])) {
    $mainImg = $row['img3'];
}

// 4) 장바구니 담기 처리 (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_cart') {

    if (empty($_SESSION['sino_id'])) {
        echo "<script>alert('로그인이 필요합니다.');location.href='index.php?cmd=printLogin';</script>";
        exit;
    }

    $userId   = $_SESSION['sino_id'];
    $selColor = isset($_POST['color']) ? trim($_POST['color']) : '';
    $selSize  = isset($_POST['size'])  ? trim($_POST['size'])  : '';
    $qty      = isset($_POST['qty'])   ? intval($_POST['qty']) : 1;

    if ($qty < 1)  $qty = 1;
    if ($qty > 10) $qty = 10;

    $cartPrice = $price;

    $sqlCart = "INSERT INTO cart (model, color, size, cnt, price, id)
                VALUES (?, ?, ?, ?, ?, ?)";
    $stmtCart = $conn->prepare($sqlCart);
    $stmtCart->bind_param(
        "ississ",
        $idx,
        $selColor,
        $selSize,
        $qty,
        $cartPrice,
        $userId
    );

    if ($stmtCart->execute()) {
        echo "<script>alert('장바구니에 담었습니다.');location.href='index.php?cmd=detail&idx={$idx}';</script>";
        exit;
    } else {
        $err = htmlspecialchars($stmtCart->error);
        echo "<div class='container py-4'><div class='alert alert-danger'>장바구니 담기 실패: {$err}</div></div>";
    }
}
?>

<style>
  body {
      background:#f8fafc;
  }
  .detail-wrap {
      max-width: 1040px;
  }
  .detail-main-card {
      border:none;
      border-radius:18px;
      box-shadow:0 8px 24px rgba(0,0,0,0.06);
  }
  .detail-sub-card {
      border:none;
      border-radius:18px;
      box-shadow:0 4px 14px rgba(0,0,0,0.04);
  }
  .detail-label {
      font-size:0.8rem;
      font-weight:700;
      letter-spacing:0.08em;
      text-transform:uppercase;
      color:#6b7280;
      margin-bottom:4px;
  }
  .detail-name {
      font-size:1.4rem;
      font-weight:700;
      color:#111827;
  }
  .detail-price {
      font-size:1.1rem;
      font-weight:700;
      color:#000;
  }
  .detail-muted {
      font-size:0.82rem;
      color:#6b7280;
  }
  .detail-form .form-select,
  .detail-form .form-control {
      border-radius:12px;
      font-size:0.9rem;
  }
  .detail-form .form-control-plaintext {
      padding-left:0;
  }
  .detail-btn-cart {
      border-radius:999px;
      font-weight:600;
      font-size:0.9rem;
  }
  .detail-img-card {
      border:none;
      border-radius:18px;
      overflow:hidden;
      background:#f9fafb;
  }
  .detail-img-card img {
      border-radius:0;
  }
</style>

<div class="container py-4 detail-wrap">
    <!-- 상단: 이미지 / 옵션 -->
    <div class="card detail-main-card mb-4">
      <div class="card-body p-4">
        <div class="row g-4">
          <!-- 왼쪽: 이미지 영역 -->
          <div class="col-md-6">
              <div class="detail-img-card h-100">
                  <?php if ($mainImg): ?>
                      <div class="ratio ratio-4x3">
                          <img src="<?= htmlspecialchars($mainImg) ?>"
                               alt="<?= htmlspecialchars($name) ?>"
                               class="img-fluid"
                               style="object-fit: cover;">
                      </div>
                  <?php else: ?>
                      <div class="ratio ratio-4x3 d-flex align-items-center justify-content-center">
                          <span class="text-muted">이미지가 없습니다.</span>
                      </div>
                  <?php endif; ?>
              </div>
          </div>

          <!-- 오른쪽: 옵션 / 구매 영역 -->
          <div class="col-md-6">
              <div class="d-flex flex-column h-100 detail-form">
                  <div class="mb-2">
                      <div class="detail-label">PRODUCT</div>
                      <div class="detail-name"><?= htmlspecialchars($name) ?></div>
                  </div>

                  <div class="mb-3">
                      <div class="detail-label">PRICE</div>
                      <div class="detail-price"><?= number_format($price) ?> 원</div>
                  </div>

                  <!-- 장바구니 폼 -->
                  <form class="mt-2" method="post" action="index.php?cmd=detail&idx=<?= $idx ?>">
                      <input type="hidden" name="action" value="add_cart">

                      <!-- 제품명 (읽기전용) -->
                      <div class="mb-3">
                          <div class="detail-label">제품명</div>
                          <input type="text"
                                 class="form-control-plaintext fw-semibold"
                                 value="<?= htmlspecialchars($name) ?>"
                                 readonly>
                      </div>

                      <!-- 색상 선택 -->
                      <div class="mb-3">
                          <div class="detail-label">색상</div>
                          <?php if (!empty($colors)): ?>
                              <select id="color_select" name="color" class="form-select">
                                  <?php foreach ($colors as $c): ?>
                                      <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
                                  <?php endforeach; ?>
                              </select>
                          <?php else: ?>
                              <input type="text"
                                     class="form-control"
                                     value="선택 가능한 색상이 없습니다."
                                     readonly>
                          <?php endif; ?>
                      </div>

                      <!-- 사이즈 선택 -->
                      <div class="mb-3">
                          <div class="detail-label">사이즈</div>
                          <?php if (!empty($sizes)): ?>
                              <select id="size_select" name="size" class="form-select">
                                  <?php foreach ($sizes as $s): ?>
                                      <option value="<?= htmlspecialchars($s) ?>"><?= htmlspecialchars($s) ?></option>
                                  <?php endforeach; ?>
                              </select>
                          <?php else: ?>
                              <input type="text"
                                     class="form-control"
                                     value="선택 가능한 사이즈가 없습니다."
                                     readonly>
                          <?php endif; ?>
                      </div>

                      <!-- 가격 -->
                      <div class="mb-3">
                          <div class="detail-label">가격</div>
                          <div class="input-group">
                              <input type="text"
                                     id="product_price"
                                     class="form-control"
                                     data-price="<?= $price ?>"
                                     value="<?= number_format($price) ?>"
                                     readonly>
                              <span class="input-group-text">원</span>
                          </div>
                      </div>

                      <!-- 수량 -->
                      <div class="mb-3">
                          <div class="detail-label">수량</div>
                          <select id="qty_select" name="qty" class="form-select">
                              <?php for ($i = 1; $i <= 10; $i++): ?>
                                  <option value="<?= $i ?>"><?= $i ?></option>
                              <?php endfor; ?>
                          </select>
                      </div>

                      <!-- 합계 -->
                      <div class="mb-4">
                          <div class="detail-label">합계</div>
                          <div class="input-group">
                              <input type="text"
                                     id="total_price"
                                     class="form-control fw-bold"
                                     value=""
                                     readonly>
                              <span class="input-group-text">원</span>
                          </div>
                      </div>

                      <!-- 장바구니 버튼 -->
                      <div class="mt-auto d-grid">
                          <button type="submit" class="btn btn-dark detail-btn-cart">
                              장바구니
                          </button>
                      </div>
                  </form>
              </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 하단: 상세 정보 -->
    <div class="card detail-sub-card mb-4">
        <div class="card-header bg-white border-0 px-4 pt-3">
            <div class="detail-label mb-1">DETAIL</div>
            <div class="detail-muted">제품 상세 정보</div>
        </div>
        <div class="card-body px-4 pb-4 pt-0">
            <?php if (trim($memo) !== ''): ?>
                <div class="form-control"
                     style="min-height: 120px; white-space: pre-wrap; border-radius:14px; border-color:#e5e7eb;">
                    <?= nl2br(htmlspecialchars($memo)) ?>
                </div>
            <?php else: ?>
                <p class="text-muted mb-0">상세 정보가 없습니다.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// 가격 * 수량 = 합계 계산
document.addEventListener('DOMContentLoaded', function () {
    const priceEl = document.getElementById('product_price');
    const qtyEl   = document.getElementById('qty_select');
    const totalEl = document.getElementById('total_price');

    if (!priceEl || !qtyEl || !totalEl) return;

    const unitPrice = parseInt(priceEl.dataset.price, 10) || 0;

    function updateTotal() {
        const qty   = parseInt(qtyEl.value, 10) || 1;
        const total = unitPrice * qty;
        totalEl.value = total.toLocaleString('ko-KR');
    }

    qtyEl.addEventListener('change', updateTotal);
    updateTotal();
});
</script>
