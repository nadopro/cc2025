<?php
// list.php

$cat = intval($_GET['cat'] ?? 0);

// 대분류인지 판단 (1자리 숫자면 대분류)
if ($cat > 0 && $cat < 10) {

    // 대분류의 첫 번째 소분류 계산 : ex) 1 → 11
    $firstSub = $cat * 10 + 1;

    // 해당 소분류 이름이 실제 존재하는지 확인
    if (isset($catList[$firstSub])) {
        // 자동 리다이렉트
        header("Location: index.php?cmd=list&cat={$firstSub}");
        exit;
    }
}

// ----------------------------------------------
// 이제부터는 cat이 소분류일 때의 일반 처리
// ----------------------------------------------

$isSub = ($cat >= 10);

// 대분류 번호
$mainCat = intval($cat / 10);

// 소분류 범위
$startSub = $mainCat * 10 + 1;
$endSub   = $mainCat * 10 + 3;

// 분류명
$mainName = $catList[$mainCat] ?? "분류 없음";

?>

<h2 class="mb-4"><?= $mainName ?></h2>

<div class="row">

    <!-- 소분류 목록 -->
    <div class="col-12 col-md-4 col-lg-3 mb-3">

        <div class="list-group">
            <?php for ($i = $startSub; $i <= $endSub; $i++): ?>
                <?php if (!isset($catList[$i])) continue; ?>

                <a href="index.php?cmd=list&cat=<?= $i ?>"
                   class="list-group-item list-group-item-action <?= ($cat == $i ? 'active' : '') ?>">
                    <?= $catList[$i] ?>
                </a>

            <?php endfor; ?>
        </div>

    </div>


    <!-- 문장 출력 -->
    <div class="col-12 col-md-8 col-lg-9">

        <h4 class="mb-3"><?= $catList[$cat] ?> 관련 문장</h4>

        <?php
        $stmt = $conn->prepare("SELECT idx, org, memo FROM sentence WHERE cat = ? ORDER BY idx DESC");
        $stmt->bind_param("i", $cat);
        $stmt->execute();
        $result = $stmt->get_result();
        ?>

        <?php if ($result->num_rows === 0): ?>

            <p class="text-muted">등록된 문장이 없습니다.</p>

        <?php else: ?>

            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card mb-3">
                    <div class="card-body">

                        <div class="fw-bold mb-2">원문 / 해석</div>
                        <div><?= nl2br($row['org']) ?></div>

                        <?php if (!empty($row['memo'])): ?>
                            <div class="mt-2 text-secondary">
                                <small><?= nl2br($row['memo']) ?></small>
                            </div>
                        <?php endif; ?>

                        <!-- ❤️ 좋아요 버튼 (여기만 싱글/더블 따옴표 조합으로 수정) -->
                        <button class="btn btn-outline-danger btn-sm mt-3"
                                onclick='likeQuote(
                                    <?= json_encode($row['org'], JSON_UNESCAPED_UNICODE) ?>,
                                    <?= json_encode($row['memo'] ?? '', JSON_UNESCAPED_UNICODE) ?>
                                )'>
                            ❤️ 좋아요
                        </button>

                    </div>
                </div>
            <?php endwhile; ?>

        <?php endif; ?>

    </div>

</div>

<div class="text-center mt-5 mb-4">
    <a href="index.php" class="btn btn-primary px-4 py-2">홈으로 돌아가기</a>
</div>


<!-- ❤️ 저장 기능 스크립트 -->
<script>
function likeQuote(origin, interpret) {

    let liked = JSON.parse(localStorage.getItem("likedQuotes") || "[]");

    liked.push({
        origin: origin,
        interpret: interpret
    });

    localStorage.setItem("likedQuotes", JSON.stringify(liked));

    alert("좋아요 목록에 추가되었습니다 ❤️");
}
</script>