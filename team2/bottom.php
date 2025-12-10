<?php
// bottom.php
$subNames = [
    21 => '바지',
    22 => '치마',
];

$sub = isset($_GET['sub']) ? (int)$_GET['sub'] : 0;

$where = "(cat DIV 10) = 2";
$currentTitle = "하의 전체 상품";

if (in_array($sub, [21, 22], true)) {
    $where = "cat = " . $sub;
    $currentTitle = "하의 - " . $subNames[$sub];
}

$sql = "SELECT * FROM model WHERE {$where} ORDER BY id DESC";
$result = $conn->query($sql);
?>

<style>
  .text-black-strong { color:#000000; font-weight:700; }
  .text-black-soft   { color:#1f2937; }
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
  .product-card .card-body a,
  .product-card .product-price {
      color:#000000 !important;
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
          <a href="index.php?cmd=bottom"
             class="nav-link <?= !$sub ? 'active' : '' ?>">전체</a>
      </li>
      <li class="nav-item">
          <a href="index.php?cmd=bottom&sub=21"
             class="nav-link <?= $sub === 21 ? 'active' : '' ?>">바지</a>
      </li>
      <li class="nav-item">
          <a href="index.php?cmd=bottom&sub=22"
             class="nav-link <?= $sub === 22 ? 'active' : '' ?>">치마</a>
      </li>
  </ul>

  <?php if ($result && $result->num_rows > 0): ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php $idx = (int)$row['id']; ?>
            <div class="col">
                <div class="card h-100 product-card">

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
                        <div class="small text-muted mt-1" style="color:#000000;">
                            [<?= htmlspecialchars($subNames[(int)$row['cat']] ?? "미분류") ?>]
                        </div>
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
