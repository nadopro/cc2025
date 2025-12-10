<?php
// manOrder.php
// 전제: index.php 에서 $conn = connectDB();, Bootstrap5 로딩, 관리자 권한 체크 이미 처리된 상태

// -----------------------------
// 상태 정의
// -----------------------------
$statusNames = [
    1 => "접수중",
    2 => "결재확인중",
    3 => "결재완료",
    4 => "제품준비중",
    5 => "배송중",
    6 => "배송완료",
    7 => "취소"
];

// -----------------------------
// 현재 선택된 status
// -----------------------------
$curStatus = isset($_GET['status']) ? intval($_GET['status']) : 1;
if (!isset($statusNames[$curStatus])) {
    $curStatus = 1;
}

// -----------------------------
// 각 상태별 주문 건수 조회
// -----------------------------
$counts = [];
$sqlCnt = "SELECT status, COUNT(*) AS cnt FROM order_table GROUP BY status";
$resCnt = $conn->query($sqlCnt);
while ($row = $resCnt->fetch_assoc()) {
    $counts[(int)$row['status']] = (int)$row['cnt'];
}

// -----------------------------
// 현재 상태의 주문 목록 조회
// item_table 합산 포함
// -----------------------------
$sql = "
    SELECT 
        o.idx AS order_idx,
        o.user_id,
        o.order_name,
        o.order_date,
        SUM(i.price * i.cnt) AS total_price,
        MIN(i.model) AS model_sample
    FROM order_table AS o
    JOIN item_table AS i ON o.idx = i.order_idx
    WHERE o.status = ?
    GROUP BY o.idx
    ORDER BY o.idx DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $curStatus);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container py-4">
    <h3 class="mb-4">주문 관리</h3>

    <!-- 상태 네비게이션 -->
    <ul class="nav nav-pills mb-4">
        <?php foreach ($statusNames as $key => $label): 
            $cnt = $counts[$key] ?? 0;
        ?>
        <li class="nav-item">
            <a class="nav-link <?= ($key === $curStatus) ? 'active' : '' ?>"
               href="index.php?cmd=manOrder&status=<?= $key ?>">
                <?= $label ?>
                <span class="badge bg-light text-dark ms-1"><?= $cnt ?></span>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>

    <!-- 주문 목록 테이블 -->
    <div class="card shadow-sm">
        <div class="card-body">
            <?php if ($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:80px;">순서</th>
                                <th style="width:140px;">주문자</th>
                                <th style="width:180px;">주문일</th>
                                <th>제품명</th>
                                <th style="width:160px;">총 금액</th>
                                <th style="width:100px;">비고</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['order_idx'] ?></td>
                                    <td><?= htmlspecialchars($row['order_name']) ?></td>
                                    <td><?= $row['order_date'] ?></td>
                                    <td>
                                        주문 포함 제품
                                    </td>
                                    <td class="fw-semibold text-primary">
                                        <?= number_format((int)$row['total_price']) ?> 원
                                    </td>
                                    <td>
                                        <a href="index.php?cmd=manOrderView&order_idx=<?= $row['order_idx'] ?>"
                                           class="btn btn-sm btn-outline-secondary">
                                            보기
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
