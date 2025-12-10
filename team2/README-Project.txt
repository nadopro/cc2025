

1. 쇼핑몰의 분류를 만들어.

create table cat1 ()

필요한 항목
1. idx : auto_incremtn, primary key
2. name : 카테고리명
3. useflag : 사용여부 , 0(사용안함), 1(사용함)



제품등록

간단한 쇼핑몰을 위한 제품 등록을 위한 데이터베이스를 설계로하려고 해.

table : model
필드
- idx : auto_increment, primary key
- cat : 카테고리 인덱스,정수
- name : 제품명
- price : 정수
- size : "라지,스몰,미들" 처럼 제품선택내용을 콤마 단위로 나열
- color : 사이즈 처럼 색상 목록을 콤마로 나열
- img1 : 사진
- img2 : 사진
- img3 : 사진
- memo : 설명


이렇게 만들어 줘.


CREATE TABLE model (
    idx INT AUTO_INCREMENT PRIMARY KEY,       -- 제품 고유번호
    cat INT NOT NULL,                         -- 카테고리 인덱스
    name VARCHAR(255) NOT NULL,               -- 제품명
    price INT NOT NULL,                       -- 가격
    size VARCHAR(255),                        -- 사이즈 목록 (예: '스몰,미들,라지')
    color VARCHAR(255),                       -- 색상 목록 (예: '블랙,화이트,레드')
    img1 VARCHAR(255),                        -- 이미지1 (파일명 또는 URL)
    img2 VARCHAR(255),                        -- 이미지2
    img3 VARCHAR(255),                        -- 이미지3
    memo TEXT                                 -- 제품 설명
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



Q: 
다시 질문할께.

카테고리는 다음과 같이 되어 있어.

$cat[1] = "대분류1";
$cat[2] = "대분류2";
$cat[3] = "대분류3";

$cat[11] = "소분류1-1";
$cat[12] = "소분류1-2";

$cat[21] = "소분류2-1";
$cat[22] = "소분류2-2";
$cat[31] = "소분류3-1";
$cat[32] = "소분류3-2";


이렇게 만들어진 테이블을 이용해서
데이터베이스에 제품을 등록하는 관리자 메뉴를 만들려고 해.

index.php?cmd=manModel 이렇게 접속할거야.
index.php에서 이미 데이터베이스 연결을 끝나고, 데이터베이스 연결정보는 $conn에 저장되어 있어.
manModel.php 파일만 만들면 돼.

그런데, 파일은 사진만 올린건데, 사진은 모두 3개야.
각각의 파일이름이 같을 수도 있으니까
각각 data/1 
data/2
data/3
폴더에 저장할거야.

그런데 같은 파일이름이 올라올 수 있기 때문에 
파일 이름이 a.jpg 인 경우
20251112123456.jpg 처럼 현재시간.jpg 형태로 변경해 저장해.

입력하는 부분은 Bootstrap5를 이용해서 화면 구성을 깔끔하게 처리해 줘.

카테고리 선택은 위의 분류를 SELECT로 선택하게 할거야.


이 제품을 등록하는 PHP 파일을 만들어 줘.



config.php 파일을 다음과 같이 구성했어.

<?php
    $cat[1] = "대분류1";
    $cat[2] = "대분류2";
    $cat[3] = "대분류3";

    $cat[11] = "소분류 1-1";
    $cat[12] = "소분류 1-2";

   
    $cat[21] = "소분류 2-1";
    $cat[22] = "소분류 2-2";
    $cat[23] = "소분류 2-3";

    $cat[31] = "소분류 3-1";
    $cat[32] = "소분류 3-2";

?>


이렇게 되어있을때, 앞의 코드를 이를 이용하도록 수정해 줘.




======================

이 코드를 수정해서 이미지를 클릭하면
index.php?cmd=detail&idx=데이터베이스의키값
이런 형태로 호출하도록 변경해 줘.


========================

index.php?cmd=detail&idx=데이터베이스의키값

이렇게 사용되는 detail.php 파일을 만들어 줘.

좌우로 나눠서
왼쪽에는 사진을 보여줘.
오른쪽에는 선택 정보를 다음처럼 보여줘.
  제품명 : 이름
  색상 : 색상 선택 SELECT
  사이즈 : 사이즈 선택 SELECT
  가격 : 제품의 가격
  수량 : 1~10 선택 창
  합계 : 가격 * 수량 자동 계산
  장바구니 버튼

하단에는 제품의 상세 정보를 출력하도록 구성해 줘.

장바구니 담기를 위한 cart테이블을 정의하고 싶어.
idx : auto_increment, primary key
model : 제품의 키
color : 색상 저장
size : 사이즈 저장
cnt  : 선택한 제품의 갯수
price : 제품의 가격을 여기에 저장
id  : 사용자 id
time : now();

이때, model 테이블의 키값은 id




CREATE TABLE cart (
    idx INT UNSIGNED NOT NULL AUTO_INCREMENT ,   -- 장바구니 PK
    model INT UNSIGNED NOT NULL,                -- model.id (제품 ID)
    color VARCHAR(50) DEFAULT NULL,             -- 선택한 색상
    size VARCHAR(50) DEFAULT NULL,              -- 선택한 사이즈
    cnt INT UNSIGNED NOT NULL DEFAULT 1,        -- 수량
    price INT UNSIGNED NOT NULL,                -- 장바구니 담을 당시의 제품 가격
    id VARCHAR(100) NOT NULL,                   -- 사용자의 로그인 ID
    time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, -- 담은 시간

    PRIMARY KEY (idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


제품 상세보기 페이지가 아래와 같을 때, 
장바구니에 담기를 수행하고 싶어.
조금전에 만든 테이블에 넣도록 코드를 수정해 줘.


index.php?cmd=cart 를 하면 장바구니 보기를 할거야.
index.php에서 cmd를 보고 cart.php를 include하기 때문에 DB접속 등은 이미 다 완료되었어.
접속정보는 $conn에 들어있어.

cart.php를 만드는데, 현재 접속한 아이디의 장바구니 목록을 idx 오름차순으로 정리해서 표형태로 보여줘.
사진, 제품명, 사이즈, 색상, 가격, 수량, 합계 순서대로 보여줘.

화면 하단에는 표의 마지막에 총 합계 금액도 표시되어야 하고,
하단에는 주문자 정보를 입력받는 부분이 필요해

주문자 정보는 
이름, 아이디, 주소, 전화번호

배송지 정보는
주문자와 배송지의 주소가 서로 다른 경우를 위해 필요하고,

주문자정보와 배송지 정보 사이에는 체크박스로 "주문자와 같음"을 체크하면 주문자 입력정보가 자동으로 입력되도록 해 줘.

맨 마지막에는 주문실행 버튼을 만들어 줘.


-------------

뒷부분을 다음과 같이 memo를 추가했어.

<div class="mb-3">
    <label for="ship_tel" class="form-label">전화번호</label>
    <input type="text" class="form-control" id="ship_tel" name="ship_tel" required>
</div>

<div class="mb-3">
    <label for="ship_tel" class="form-label">요청사항</label>
    <input type="text" class="form-control" id="memo" name="memo" required>
</div>

이제 "주문 실행"버튼을 누르면 정보를 저장해야해.

두 개의 정보가 필요한데, 입력한 주문자 정보를 저장하는 order_table 과 각 주문이 포함하는 제품의 정보인 item_table이 필요해.

order_table은 다음과 같아.
idx : 주문번호 , auto_increment, primary key
주문자 아이디, 주문자 이름, 주소, 전화번호, 
배송지 이름, 주소, 전화번호, 메모
주문일 : now()
상태 : 1(접수중), 2(결재확인중), 3(결재완료), 4(제품준비중), 5(배송중) , 6(배송완료), 7(취소)

item_table에는 
idx, order_table의 키값과 cart에 있는 나머지 정보가 모두 필요해.

두 테이블을 정의해 줘.

========================
이제 cart.php의 동작을 일부 바꿀거야.

주문실행버튼을 클릭하면
1. order_table에 입력한 정보를 저장해
  이때 idx가 주문 번호가 돼.

2. cart에 있는 나의 제품들을 item_table에 복사해. 이때 주문 번호는 직전에 설정한 order_table의 idx값이야.

3. 1~2번이 끝나면 cart에 있는 내가 담은 제품을 제거해.

코드를 수정해 줘.

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
    <form method="post" action="index.php?cmd=order">
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
                            <label for="ship_tel" class="form-label">요청사항</label>
                            <input type="text" class="form-control" id="memo" name="memo" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 주문 실행 버튼 -->
        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary btn-lg">
                주문 실행
            </button>
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
        } else {
            // 체크 해제 시 비우고 싶으면 아래 활성화
            // shipName.value = '';
            // shipAddr.value = '';
            // shipTel.value  = '';
        }
    });
});
</script>


----
이제 관리자가 index.php?cmd=manOrder로 접속해서
주문 정보를 볼거야.
이를 위한 manOrder.php를 만들거야.

상단에는 상태 정보별로 pagnation으로 보여주고 싶어.

 1(접수중), 2(결재확인중), 3(결재완료), 4(제품준비중), 5(배송중) , 6(배송완료), 7(취소)

 접수중 옆에는 badge로 숫자를 함께 포함해 줘.

 각 페이지네이션 별로 해당하는 status를 보고 주문 내용을 표 형태로 보여줘.

 표는 다음과 같이 구성되어 있어.
 순서, 주문자, 주문일, 제품명, 총 금액, 비고(보기)


 --------------

 manOrderView&order_idx=2

 이렇게 접속해서, 주문 상세정보를 보고 싶어.
 그런데, 이때 관리자는 주문 상태를 <select>로 변경할 수 있어야 해.
 주문 상세 보기는 주문하기 페이지와 유사하지만,
 상태정보를 제외한 나머지는 입력창이 아니라 텍스트로 출력되어야 해.
 manOrderView.php를 만들어 줘.


 --------------
 같은 방법으로 mypage.php를 만들고 싶어.
 여기서는 나의 아이디의 모든 주문 내역을 보고 싶어.
 방법은 관리자의 주문보기 페이지와 동일하게 7가지 상태별로 보여줘.
 상세보기를 눌렀을 때는 상태 변경하는 기능만 제거하고 보여주면 돼.
 