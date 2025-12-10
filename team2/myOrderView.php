<?php
// myOrderView.php
// 사용: index.php?cmd=myOrderView&order_idx=번호
// 전제: index.php에서 $conn = connectDB();, Bootstrap5 로드 완료

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['sino_id'])) {
    echo '<div class="container py-4"><div class="alert alert-danger">로그인이 필요합니다.</div></div>';
    exit;
}
$userId = $_SESSION['sino_id'];

// 주문 번호 확인
if (!isset($_GET['order_idx'])) {
    echo '<div class="container py-4"><div class="alert alert-danger">잘못된 접근입니다. (order_idx 없음)</div></div>';
    exit;
}

$orderIdx = (int)$_GET['order_idx'];
if ($orderIdx <= 0) {
    echo '<div class="container py-4"><div class="alert alert-danger">잘못된 주문 번호입니다.</div></div>';
    exit;
}

// 상태 이름/색상 정의
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

// 1) 내 주문 정보 조회 (user_id 체크 포함)
$sqlOrder = "
    SELECT
        idx,
        user_id,
        order_name,
        order_addr,
        order_tel,
        ship_name,
        ship_addr,
        ship_tel,
        memo,
        order_date,
        status
    FROM order_table
    WHERE idx = ? AND user_id = ?
";
$stmtOrder = $conn->prepare($sqlOrder);
$stmtOrder->bind_param("is", $orderIdx, $userId);
$stmtOrder->execute();
$resOrder = $stmtOrder->get_result();
$order = $resOrder->fetch_assoc();

if (!$order) {
    echo '<div class="container py-4"><div class="alert alert-warning">해당 주문을 찾을 수 없거나, 접근 권한이 없습니다.</div></div>';
    exit;
}

$currentStatus = (int)$order['status'];
if (!isset($statusNames[$currentStatus])) {
    $currentStatus = 1;
}

// 2) 주문 상품 목록 조회
$sqlItems = "
    SELECT
        i.idx,
        i.model,
        i.color,
        i.size,
        i.cnt,
        i.price,
        m.name,
        m.img1
    FROM item_table AS i
    LEFT JOIN model AS m ON i.model = m.id
    WHERE i.order_idx = ?
    ORDER BY i.idx ASC
";
$stmtItems = $conn->prepare($sqlItems);
$stmtItems->bind_param("i", $orderIdx);
$stmtItems->execute();
$resItems = $stmtItems->get_result();

$items = [];
$totalSum = 0;
while ($r = $resItems->fetch_assoc()) {
    $rowTotal = (int)$r['price'] * (int)$r['cnt'];
    $r['row_total'] = $rowTotal;
    $totalSum += $rowTotal;
    $items[] = $r;
}
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">주문 상세 보기 (주문번호: <?= $orderIdx ?>)</h3>
        <div>
            <span class="me-2">주문 상태:</span>
            <span class="badge bg-<?= $statusColors[$currentStatus] ?? 'secondary' ?>">
                <?= $statusNames[$currentStatus] ?>
            </span>
        </div>
    </div>

    <!-- 주문자 / 배송지 정보 -->
    <div class="row g-4 mb-4">
        <!-- 주문자 정보 -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <strong>주문자 정보</strong>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <div class="form-label mb-0">아이디</div>
                        <p class="form-control-plaintext mb-1"><?= htmlspecialchars($order['user_id']) ?></p>
                    </div>

                    <div class="mb-2">
                        <div class="form-label mb-0">이름</div>
                        <p class="form-control-plaintext mb-1"><?= htmlspecialchars($order['order_name']) ?></p>
                    </div>

                    <div class="mb-2">
                        <div class="form-label mb-0">주소</div>
                        <p class="form-control-plaintext mb-1"><?= htmlspecialchars($order['order_addr']) ?></p>
                    </div>

                    <div class="mb-2">
                        <div class="form-label mb-0">전화번호</div>
                        <p class="form-control-plaintext mb-1"><?= htmlspecialchars($order['order_tel']) ?></p>
                    </div>

                    <div class="mb-2">
                        <div class="form-label mb-0">주문일</div>
                        <p class="form-control-plaintext mb-1"><?= htmlspecialchars($order['order_date']) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 배송지 정보 -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <strong>배송지 정보</strong>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <div class="form-label mb-0">수령인 이름</div>
                        <p class="form-control-plaintext mb-1"><?= htmlspecialchars($order['ship_name']) ?></p>
                    </div>

                    <div class="mb-2">
                        <div class="form-label mb-0">주소</div>
                        <p class="form-control-plaintext mb-1"><?= htmlspecialchars($order['ship_addr']) ?></p>
                    </div>

                    <div class="mb-2">
                        <div class="form-label mb-0">전화번호</div>
                        <p class="form-control-plaintext mb-1"><?= htmlspecialchars($order['ship_tel']) ?></p>
                    </div>

                    <div class="mb-2">
                        <div class="form-label mb-0">요청사항</div>
                        <p class="form-control-plaintext mb-1">
                            <?= $order['memo'] !== '' ? nl2br(htmlspecialchars($order['memo'])) : '<span class="text-muted">없음</span>' ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 주문 상품 목록 -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <strong>주문 상품 목록</strong>
        </div>
        <div class="card-body">
            <?php if (count($items) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:120px;">사진</th>
                                <th>제품명</th>
                                <th style="width:100px;">사이즈</th>
                                <th style="width:100px;">색상</th>
                                <th style="width:120px;">단가</th>
                                <th style="width:80px;">수량</th>
                                <th style="width:140px;">합계</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $it): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($it['img1'])): ?>
                                            <img src="<?= htmlspecialchars($it['img1']) ?>"
                                                 alt="<?= htmlspecialchars($it['name']) ?>"
                                                 style="max-width:100px; max-height:100px; object-fit:cover;">
                                        <?php else: ?>
                                            <span class="text-muted">이미지 없음</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-start">
                                        <?= htmlspecialchars($it['name'] ?? '제품명 없음') ?>
                                    </td>
                                    <td><?= htmlspecialchars($it['size']) ?></td>
                                    <td><?= htmlspecialchars($it['color']) ?></td>
                                    <td><?= number_format((int)$it['price']) ?> 원</td>
                                    <td><?= (int)$it['cnt'] ?></td>
                                    <td class="fw-semibold text-primary">
                                        <?= number_format((int)$it['row_total']) ?> 원
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-secondary">
                                <th colspan="6" class="text-end">총 합계</th>
                                <th class="fw-bold text-danger">
                                    <?= number_format($totalSum) ?> 원
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted mb-0">이 주문에는 상품 정보가 없습니다.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="text-end">
        <a href="index.php?cmd=mypage&status=<?= $currentStatus ?>" class="btn btn-outline-secondary">
            주문 목록으로
        </a>
    </div>
</div>
