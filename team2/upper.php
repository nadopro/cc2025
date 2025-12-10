<?php
// upper.php
$subNames = [
    11 => '티셔츠',
    12 => '원피스 / 드레스',
    13 => '아우터',
];

$sub = isset($_GET['sub']) ? (int)$_GET['sub'] : 0;

$where = "(cat DIV 10) = 1";
$currentTitle = "상의 전체 상품";

if (in_array($sub, [11, 12, 13], true)) {
    $where = "cat = " . $sub;
    $currentTitle = "상의 - " . $subNames[$sub];
}

$sql = "SELECT * FROM model WHERE {$where} ORDER BY id DESC";
$result = $conn->query($sql);
?>

<style>
  .text-black-strong { color:#000000; font-weight:700; }
  .text-black-soft   { color:#1f2937; } /* 진하지만 너무 쨍하지 않음 */
  .nav-pills .nav-link {
      color:#000000;
      border-radius:999px;
      font-weight:600;
  }
  .nav-pills .nav-link:hover {
      background:rgba(0,0,0,0.05);
      color:#000000;
  }
  .nav-pills .nav-link.active {
      background:#000000;
      color:#ffffff;
  }
  .product-card .card-title,
  .product-card .product-price {
      color:#000000 !important;
  }
  .product-card .card-body a {
      color:#000000;
  }
  .product-card .card-body a:hover {
      opacity:0.6;
      color:#000000;
  }
</style>

<div class="container my-4">

  <div class="mb-3">
      <h4 class="mb-1 text-black-strong"><?= htmlspecialchars($currentTitle) ?></h4>
      <p class="small mb-0 text-black-soft text-black-strong">
      </p>
  </div>

  <ul class="nav nav-pills mb-4 small">
      <li class="nav-item">
          <a href="index.php?cmd=upper"
             class="nav-link <?= !$sub ? 'active' : '' ?>">전체</a>
      </li>
      <li class="nav-item">
          <a href="index.php?cmd=upper&sub=11"
             class="nav-link <?= $sub === 11 ? 'active' : '' ?>">티셔츠</a>
      </li>
      <li class="nav-item">
          <a href="index.php?cmd=upper&sub=12"
             class="nav-link <?= $sub === 12 ? 'active' : '' ?>">원피스 / 드레스</a>
      </li>
      <li class="nav-item">
          <a href="index.php?cmd=upper&sub=13"
             class="nav-link <?= $sub === 13 ? 'active' : '' ?>">아우터</a>
      </li>
  </ul>

  <?php if ($result && $result->num_rows > 0): ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php $idx = (int)$row['id']; ?>
            <div class="col">
                <div class="card h-100 product-card product-card text-black-strong">

                    <a href="index.php?cmd=detail&idx=<?= $idx ?>">
                      <div class="ratio ratio-4x3 bg-light">
                        <img src="<?= htmlspecialchars($row['img1'] ?: $row['img2'] ?: $row['img3']) ?>"
                             class="card-img-top" style="object-fit:cover;">
                      </div>
                    </a>

                    <div class="card-body">
                        <h6 class="card-title mb-1 text-black-strong">
                          <a href="index.php?cmd=detail&idx=<?= $idx ?>"><?= htmlspecialchars($row['name']) ?></a>
                        </h6>
                        <p class="product-price mb-0 text-black-strong">
                            <?= number_format((int)$row['price']) ?> 원
                        </p>
                        <?php
                          $c = (int)$row['cat'];
                          if (isset($subNames[$c])) {
                            echo "<div class='small text-muted mt-1' style='color:#000000;'>[" . htmlspecialchars($subNames[$c]) . "]</div>";
                          }
                        ?>
                    </div>

                </div>
            </div>
        <?php endwhile; ?>
    </div>
  <?php else: ?>
    <div class="alert border-0 text-center py-5 text-black-soft">
        등록된 상품이 없습니다.
    </div>
  <?php endif; ?>

</div>
