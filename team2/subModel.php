<?php
// manModelList.php
// index.php 에서 이미 DB 연결($conn), 세션 등이 되어 있다고 가정

// 카테고리 맵 (manModel.php와 의미 맞추기)
$catMap = [
    11 => '상의 · T-Shirt',
    12 => '상의 · Dress',
    13 => '상의 · Outer',
    21 => '하의 · Pants',
    22 => '하의 · Skirt',
    30 => '신발',
];

// --- 페이징 설정 ---
$perPage = 20;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $perPage;

// 현재 cmd 값 (페이징 링크에 재사용)
$currentCmd = isset($_GET['cmd']) ? $_GET['cmd'] : 'manModelList';

// 전체 개수
$total = 0;
$sqlCnt = "SELECT COUNT(*) AS cnt FROM model";
$resCnt = $conn->query($sqlCnt);
if ($resCnt && $rowCnt = $resCnt->fetch_assoc()) {
    $total = (int)$rowCnt['cnt'];
}
$totalPages = ($total > 0) ? ceil($total / $perPage) : 1;

// 목록 조회
$sql = "SELECT * FROM model ORDER BY id DESC LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $offset, $perPage);
$stmt->execute();
$result = $stmt->get_result();
?>

<style>
  .admin-list-wrap {
      max-width: 1100px;
  }
  .admin-card {
      border: none;
      border-radius: 18px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.06);
  }
  .admin-title {
      font-weight: 700;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      font-size: 0.9rem;
      color: #000;
  }
  .table-admin {
      font-size: 0.85rem;
  }
  .table-admin thead {
      border-bottom: 1px solid #e5e7eb;
  }
  .table-admin thead th {
      font-weight: 600;
      color: #4b5563;
      background-color: #f9fafb;
  }
  .table-admin tbody tr {
      border-bottom: 1px solid #f3f4f6;
  }
  .table-admin tbody tr:hover {
      background-color: #f9fafb;
  }
  .badge-cat {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 999px;
      border: 1px solid #d1d5db;
      font-size: 0.7rem;
      color: #111827;
      background-color: #ffffff;
  }
  .thumb-img {
      max-width: 64px;
      max-height: 64px;
      object-fit: cover;
      border-radius: 8px;
      border: 1px solid #e5e7eb;
  }
  .btn-dark.btn-xs {
      padding: 4px 10px;
      font-size: 0.78rem;
      border-radius: 999px;
  }
</style>

<div class="container mt-5 admin-list-wrap">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="admin-title mb-1">PRODUCT LIST</div>
            <div class="text-muted small">
                총 <?= number_format($total) ?>개 상품
            </div>
        </div>
        <a href="index.php?cmd=manModel" class="btn btn-dark btn-sm rounded-pill">+ Add Product</a>
    </div>

    <div class="card admin-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-admin mb-0 align-middle">
                    <thead>
                        <tr>
                            <th style="width:60px;">ID</th>
                            <th style="width:140px;">카테고리</th>
                            <th>제품명</th>
                            <th style="width:120px;" class="text-end">가격</th>
                            <th style="width:120px;">사이즈</th>
                            <th style="width:120px;">색상</th>
                            <th style="width:90px;">이미지</th>
                            <th style="width:220px;">메모</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= (int)$row['id'] ?></td>

                                <td>
                                    <?php
                                      $c = (int)$row['cat'];
                                      $cLabel = isset($catMap[$c]) ? $catMap[$c] : ('기타('.$c.')');
                                    ?>
                                    <span class="badge-cat"><?= htmlspecialchars($cLabel) ?></span>
                                </td>

                                <td>
    <div class="fw-semibold">
        <a href="index.php?cmd=detail&idx=<?= (int)$row['id'] ?>"
           class="text-decoration-none text-dark">
            <?= htmlspecialchars($row['name']) ?>
        </a>
    </div>
</td>


                                <td class="text-end">
                                    <?= number_format((int)$row['price']) ?> 원
                                </td>

                                <td><?= htmlspecialchars($row['size']) ?></td>
                                <td><?= htmlspecialchars($row['color']) ?></td>

                                <td>
    <?php if (!empty($row['img1'])): ?>
        <a href="index.php?cmd=detail&idx=<?= (int)$row['id'] ?>">
            <img src="<?= htmlspecialchars($row['img1']) ?>" alt="img1" class="thumb-img">
        </a>
    <?php else: ?>
        <span class="text-muted small">없음</span>
    <?php endif; ?>
</td>


                                <td class="text-muted">
                                    <?php
                                      $memo = trim($row['memo']);
                                      if (mb_strlen($memo, 'UTF-8') > 50) {
                                          echo htmlspecialchars(mb_substr($memo, 0, 50, 'UTF-8')) . '…';
                                      } else {
                                          echo htmlspecialchars($memo);
                                      }
                                    ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                등록된 제품이 없습니다.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 페이징 -->
        <?php if ($total > 0): ?>
            <div class="card-footer bg-white">
                <nav>
                    <ul class="pagination justify-content-center mb-0">
                        <?php
                            $prevPage = $page - 1;
                            $nextPage = $page + 1;
                        ?>
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="index.php?cmd=<?= htmlspecialchars($currentCmd) ?>&page=<?= max(1, $prevPage) ?>">
                               이전
                            </a>
                        </li>

                        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                            <li class="page-item <?= ($p == $page) ? 'active' : '' ?>">
                                <a class="page-link"
                                   href="index.php?cmd=<?= htmlspecialchars($currentCmd) ?>&page=<?= $p ?>">
                                   <?= $p ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="index.php?cmd=<?= htmlspecialchars($currentCmd) ?>&page=<?= min($totalPages, $nextPage) ?>">
                               다음
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>
