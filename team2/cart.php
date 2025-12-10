<?php
// cart.php
// 사용: index.php?cmd=cart
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


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 로그인 확인
if (empty($_SESSION['sino_id'])) {
    echo '<div class="container py-4"><div class="alert alert-danger">로그인이 필요합니다.</div></div>';
    exit;
}

$userId = $_SESSION['sino_id'];

// =========================
// A) 장바구니 비우기 처리
// =========================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_cart'])) {
    $sqlDel = "DELETE FROM cart WHERE id = ?";
    $stmtDel = $conn->prepare($sqlDel);
    $stmtDel->bind_param("s", $userId);
    if ($stmtDel->execute()) {
        echo "<script>alert('장바구니를 비웠습니다.');location.href='index.php?cmd=cart';</script>";
        exit;
    } else {
        echo "<div class='container py-4'><div class='alert alert-danger'>장바구니 비우기 실패: "
            . htmlspecialchars($stmtDel->error) . "</div></div>";
    }
}

// =========================
// B) 주문 실행 처리 (POST)
// =========================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['do_order'])) {
    // ... (기존 주문 처리 로직 그대로)
}



// =========================
// 1) 주문 실행 처리 (POST)
// =========================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['do_order'])) {

    // 장바구니 비었는지 먼저 확인
    $sqlCartCheck = "SELECT idx, model, color, size, cnt, price FROM cart WHERE id = ?";
    $stmtCartCheck = $conn->prepare($sqlCartCheck);
    $stmtCartCheck->bind_param("s", $userId);
    $stmtCartCheck->execute();
    $resCartCheck = $stmtCartCheck->get_result();

    $cartItems = [];
    while ($c = $resCartCheck->fetch_assoc()) {
        $cartItems[] = $c;
    }

    if (count($cartItems) === 0) {
        echo '<div class="container py-4"><div class="alert alert-warning">장바구니에 담긴 상품이 없습니다.</div></div>';
    } else {

        // 주문자 / 배송지 정보 받기
        $order_name = trim($_POST['order_name'] ?? '');
        $order_id   = trim($_POST['order_id'] ?? '');
        $order_addr = trim($_POST['order_addr'] ?? '');
        $order_tel  = trim($_POST['order_tel'] ?? '');

        $ship_name  = trim($_POST['ship_name'] ?? '');
        $ship_addr  = trim($_POST['ship_addr'] ?? '');
        $ship_tel   = trim($_POST['ship_tel'] ?? '');
        $memo       = trim($_POST['memo'] ?? '');

        // 최소 검증
        if ($order_name === '' || $order_addr === '' || $order_tel === '' ||
            $ship_name === '' || $ship_addr === '' || $ship_tel === '') {

            echo '<div class="container py-4"><div class="alert alert-danger">주문/배송 정보가 부족합니다.</div></div>';

        } else {

            // order_id와 실제 로그인 사용자가 다르면 막기 (보안)
            if ($order_id !== $userId) {
                echo '<div class="container py-4"><div class="alert alert-danger">주문자 아이디가 현재 로그인 정보와 일치하지 않습니다.</div></div>';
            } else {

                // 트랜잭션 시작
                $conn->begin_transaction();

                try {
                    // 1) order_table에 주문 정보 저장
                    $sqlOrder = "
                        INSERT INTO order_table
                            (user_id, order_name, order_addr, order_tel,
                             ship_name, ship_addr, ship_tel, memo, status)
                        VALUES
                            (?, ?, ?, ?, ?, ?, ?, ?, 1)
                    ";
                    $stmtOrder = $conn->prepare($sqlOrder);
                    $stmtOrder->bind_param(
                        "ssssssss",
                        $userId,
                        $order_name,
                        $order_addr,
                        $order_tel,
                        $ship_name,
                        $ship_addr,
                        $ship_tel,
                        $memo
                    );

                    if (!$stmtOrder->execute()) {
                        throw new Exception('주문 정보 저장 실패: ' . $stmtOrder->error);
                    }

                    $orderIdx = $conn->insert_id; // 방금 생성된 주문번호

                    // 2) item_table에 cart 내용 복사
                    $sqlItem = "
                        INSERT INTO item_table
                            (order_idx, model, color, size, cnt, price, user_id)
                        VALUES
                            (?, ?, ?, ?, ?, ?, ?)
                    ";
                    $stmtItem = $conn->prepare($sqlItem);

                    foreach ($cartItems as $ci) {
                        $model  = (int)$ci['model'];
                        $color  = (string)$ci['color'];
                        $size   = (string)$ci['size'];
                        $cnt    = (int)$ci['cnt'];
                        $price  = (int)$ci['price'];
                        $uid    = $userId;

                        $stmtItem->bind_param(
                            "iissiis",
                            $orderIdx,
                            $model,
                            $color,
                            $size,
                            $cnt,
                            $price,
                            $uid
                        );

                        if (!$stmtItem->execute()) {
                            throw new Exception('아이템 정보 저장 실패: ' . $stmtItem->error);
                        }
                    }

                    // 3) cart 에서 해당 사용자 장바구니 비우기
                    $sqlDelCart = "DELETE FROM cart WHERE id = ?";
                    $stmtDelCart = $conn->prepare($sqlDelCart);
                    $stmtDelCart->bind_param("s", $userId);
                    if (!$stmtDelCart->execute()) {
                        throw new Exception('장바구니 비우기 실패: ' . $stmtDelCart->error);
                    }

                    // 모든 작업 성공 → 커밋
                    $conn->commit();

                    // 알림 후 페이지 이동
                    echo "<script>alert('주문이 정상적으로 접수되었습니다. 주문번호: {$orderIdx}'); location.href='index.php?cmd=cart';</script>";
                    exit;

                } catch (Exception $e) {
                    // 실패 시 롤백
                    $conn->rollback();
                    $msg = htmlspecialchars($e->getMessage());
                    echo "<div class='container py-4'><div class='alert alert-danger'>주문 처리 중 오류가 발생했습니다.<br>{$msg}</div></div>";
                }
            }
        }
    }
}

// =========================
// 2) 장바구니 화면 출력 (GET)
// =========================

// 현재 사용자 장바구니 조회
$sql = "
    SELECT 
        c.idx,
        c.model,
        c.color,
        c.size,
        c.cnt,
        c.price,
        m.name,
        m.img1
    FROM cart AS c
    JOIN model AS m ON c.model = m.id
    WHERE c.id = ?
    ORDER BY c.idx ASC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

$rows = [];
$totalSum = 0;

while ($r = $result->fetch_assoc()) {
    $rowTotal = (int)$r['price'] * (int)$r['cnt'];
    $r['row_total'] = $rowTotal;
    $totalSum += $rowTotal;
    $rows[] = $r;
}
?>

<div class="container py-4">
    <h3 class="mb-4">장바구니</h3>

    <!-- 장바구니 목록 -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <?php if (count($rows) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:120px;">사진</th>
                                <th>제품명</th>
                                <th style="width:100px;">사이즈</th>
                                <th style="width:100px;">색상</th>
                                <th style="width:120px;">가격</th>
                                <th style="width:80px;">수량</th>
                                <th style="width:140px;">합계</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $item): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($item['img1'])): ?>
                                            <img src="<?= htmlspecialchars($item['img1']) ?>"
                                                 alt="<?= htmlspecialchars($item['name']) ?>"
                                                 style="max-width:100px; max-height:100px; object-fit:cover;">
                                        <?php else: ?>
                                            <span class="text-muted">이미지 없음</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-start">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($item['size']) ?></td>
                                    <td><?= htmlspecialchars($item['color']) ?></td>
                                    <td><?= number_format((int)$item['price']) ?> 원</td>
                                    <td><?= (int)$item['cnt'] ?></td>
                                    <td class="fw-semibold text-primary">
                                        <?= number_format((int)$item['row_total']) ?> 원
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
                <p class="mb-0 text-muted">장바구니에 담긴 상품이 없습니다.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- 주문/배송 정보 입력 -->
    <form method="post" action="index.php?cmd=cart">
        <div class="row g-4">
            <!-- 주문자 정보 -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-light">
                        <strong>주문자 정보</strong>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="order_name" class="form-label">이름</label>
                            <input type="text" class="form-control" id="order_name" name="order_name" required>
                        </div>

                        <div class="mb-3">
                            <label for="order_id" class="form-label">아이디</label>
                            <input type="text"
                                   class="form-control"
                                   id="order_id"
                                   name="order_id"
                                   value="<?= htmlspecialchars($userId) ?>"
                                   readonly>
                        </div>

                        <div class="mb-3">
                            <label for="order_addr" class="form-label">주소</label>
                            <input type="text" class="form-control" id="order_addr" name="order_addr" required>
                        </div>

                        <div class="mb-3">
                            <label for="order_tel" class="form-label">전화번호</label>
                            <input type="text" class="form-control" id="order_tel" name="order_tel" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 배송지 정보 -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <strong>배송지 정보</strong>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="sameInfoCheck">
                            <label class="form-check-label" for="sameInfoCheck">
                                주문자와 같음
                            </label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="ship_name" class="form-label">이름</label>
                            <input type="text" class="form-control" id="ship_name" name="ship_name" required>
                        </div>

                        <div class="mb-3">
                            <label for="ship_addr" class="form-label">주소</label>
                            <input type="text" class="form-control" id="ship_addr" name="ship_addr" required>
                        </div>

                        <div class="mb-3">
                            <label for="ship_tel" class="form-label">전화번호</label>
                            <input type="text" class="form-control" id="ship_tel" name="ship_tel" required>
                        </div>

                        <div class="mb-3">
                            <label for="memo" class="form-label">요청사항</label>
                            <input type="text" class="form-control" id="memo" name="memo">
                        </div>
                    </div>
                </div>
            </div>
        </div>

       <!-- 주문 실행 / 장바구니 비우기 버튼 -->
<div class="mt-4 d-flex justify-content-end gap-2">
    <form method="post" action="index.php?cmd=cart">
        <button type="submit" name="clear_cart" value="1"
                class="btn btn-outline-danger btn-lg"
                <?= count($rows) === 0 ? 'disabled' : '' ?>>
            장바구니 비우기
        </button>
    </form>

    <form method="post" action="index.php?cmd=cart">
        <button type="submit" name="do_order" value="1"
                class="btn btn-primary btn-lg"
                <?= count($rows) === 0 ? 'disabled' : '' ?>>
            주문 실행
        </button>
    </form>
</div>
    </form>
</div>

<script>
// "주문자와 같음" 체크 시 배송지에 주문자 정보 복사
document.addEventListener('DOMContentLoaded', function () {
    const sameCheck = document.getElementById('sameInfoCheck');

    const orderName = document.getElementById('order_name');
    const orderAddr = document.getElementById('order_addr');
    const orderTel  = document.getElementById('order_tel');

    const shipName = document.getElementById('ship_name');
    const shipAddr = document.getElementById('ship_addr');
    const shipTel  = document.getElementById('ship_tel');

    if (!sameCheck) return;

    sameCheck.addEventListener('change', function () {
        if (this.checked) {
            shipName.value = orderName.value;
            shipAddr.value = orderAddr.value;
            shipTel.value  = orderTel.value;
        }
    });
});
</script>

