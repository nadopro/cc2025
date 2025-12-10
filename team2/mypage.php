<?php
// mypage.php
// 사용: index.php?cmd=mypage
// 전제: index.php에서 $conn = connectDB();, Bootstrap5 로드 완료

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 로그인 확인
if (empty($_SESSION['sino_id'])) {
    echo '<div class="container py-4"><div class="alert alert-danger">로그인이 필요합니다.</div></div>';
    exit;
}

$userId = $_SESSION['sino_id'];

// 상태 정의
$statusNames = [
    1 => "접수중",
    2 => "결재확인중",
    3 => "결재완료",
    4 => "제품준비중",
    5 => "배송중",
    6 => "배송완료",
    7 => "취소"
];

$statusColors = [
    1 => "secondary",
    2 => "warning",
    3 => "success",
    4 => "info",
    5 => "primary",
    6 => "dark",
    7 => "danger"
];

// 현재 선택된 status
$curStatus = isset($_GET['status']) ? intval($_GET['status']) : 1;
if (!isset($statusNames[$curStatus])) {
    $curStatus = 1;
}

// 각 상태별 주문 건수 (내 주문만)
$counts = [];
$sqlCnt = "SELECT status, COUNT(*) AS cnt FROM order_table WHERE user_id = ? GROUP BY status";
$stmtCnt = $conn->prepare($sqlCnt);
$stmtCnt->bind_param("s", $userId);
$stmtCnt->execute();
$resCnt = $stmtCnt->get_result();
while ($row = $resCnt->fetch_assoc()) {
    $counts[(int)$row['status']] = (int)$row['cnt'];
}

// 현재 상태의 내 주문 목록 조회
// 첫 번째 상품명 + 합계
$sql = "
    SELECT 
        o.idx AS order_idx,
        o.order_date,
        o.status,
        MIN(m.name) AS first_name,
        COUNT(i.idx) AS item_cnt,
        SUM(i.price * i.cnt) AS total_price
    FROM order_table AS o
    JOIN item_table AS i ON o.idx = i.order_idx
    LEFT JOIN model AS m ON i.model = m.id
    WHERE o.user_id = ? AND o.status = ?
    GROUP BY o.idx
    ORDER BY o.idx DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $userId, $curStatus);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container py-4">
    <h3 class="mb-4">나의 주문 내역</h3>

    <!-- 상태 네비게이션 -->
    <ul class="nav nav-pills mb-4">
        <?php foreach ($statusNames as $key => $label): 
            $cnt = $counts[$key] ?? 0;
        ?>
        <li class="nav-item">
            <a class="nav-link <?= ($key === $curStatus) ? 'active' : '' ?>"
               href="index.php?cmd=mypage&status=<?= $key ?>">
                <?= $label ?>
                <span class="badge bg-light text-dark ms-1"><?= $cnt ?></span>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>

    <!-- 주문 목록 -->
    <div class="card shadow-sm">
        <div class="card-body">
            <?php if ($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:80px;">순서</th>
                                <th style="width:180px;">주문일</th>
                                <th>제품명</th>
                                <th style="width:160px;">총 금액</th>
                                <th style="width:100px;">비고</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): 
                                $orderIdx   = (int)$row['order_idx'];
                                $firstName  = $row['first_name'] ?: '상품';
                                $itemCnt    = (int)$row['item_cnt'];
                                $totalPrice = (int)$row['total_price'];

                                if ($itemCnt > 1) {
                                    $productText = htmlspecialchars($firstName) . ' 외 ' . ($itemCnt - 1) . '건';
                                } else {
                                    $productText = htmlspecialchars($firstName);
                                }
                            ?>
                                <tr>
                                    <td><?= $orderIdx ?></td>
                                    <td><?= htmlspecialchars($row['order_date']) ?></td>
                                    <td class="text-start"><?= $productText ?></td>
                                    <td class="fw-semibold text-primary">
                                        <?= number_format($totalPrice) ?> 원
                                    </td>
                                    <td>
                                        <a href="index.php?cmd=myOrderView&order_idx=<?= $orderIdx ?>"
                                           class="btn btn-sm btn-outline-secondary">
                                            상세보기
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted mb-0">해당 상태의 주문이 없습니다.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
