<?php
// shoes.php
// 전제: index.php에서 $conn = connectDB(); 완료, Bootstrap 5도 이미 로드됨.

// 신발(대분류 3) 상품 조회 (최신순)
$sql = "SELECT * FROM model WHERE (cat DIV 10) = 3 ORDER BY id DESC";
$result = $conn->query($sql);
?>

<div class="container my-4">
    <h4 class="mb-3">신발 상품 목록</h4>

    <?php if ($result && $result->num_rows > 0): ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php $idx = (int)$row['id']; ?>

                <div class="col">
                    <div class="card h-100 shadow-sm">

                        <!-- 이미지 클릭 → detail 페이지 이동 -->
                        <a href="index.php?cmd=detail&idx=<?= $idx ?>" class="text-decoration-none">

                            <?php if (!empty($row['img1'])): ?>
                                <div class="ratio ratio-4x3">
                                    <img src="<?= htmlspecialchars($row['img1']) ?>"
                                         class="card-img-top"
                                         alt="<?= htmlspecialchars($row['name']) ?>"
                                         style="object-fit: cover;">
                                </div>
                            <?php else: ?>
                                <div class="ratio ratio-4x3 bg-light d-flex align-items-center justify-content-center">
                                    <span class="text-muted">이미지 없음</span>
                                </div>
                            <?php endif; ?>

                        </a>

                        <div class="card-body">
                            <h6 class="card-title mb-2">
                                <a href="index.php?cmd=detail&idx=<?= $idx ?>" 
                                   class="text-decoration-none text-dark">
                                    <?= htmlspecialchars($row['name']) ?>
                                </a>
                            </h6>

                            <p class="card-text fw-semibold mb-0">
                                <?= number_format((int)$row['price']) ?> 원
                            </p>
                        </div>

                    </div>
                </div>

            <?php endwhile; ?>
        </div>

    <?php else: ?>
        <div class="alert alert-secondary">
            등록된 상품이 없습니다.
        </div>
    <?php endif; ?>
</div>
