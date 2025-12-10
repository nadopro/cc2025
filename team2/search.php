<?php
// search.php
// 사용: index.php?cmd=search&q=검색어

if (!isset($_GET['q']) || trim($_GET['q']) === '') {
    echo "<div class='container py-4'><h5>검색어를 입력해주세요.</h5></div>";
    exit;
}

$keyword = trim($_GET['q']);

$conn = connectDB();  // db.php는 index.php에서 이미 include됨

// LIKE 검색
$sql = "
    SELECT id, name, img1, price 
    FROM model
    WHERE name LIKE CONCAT('%', ?, '%')
       OR memo LIKE CONCAT('%', ?, '%')
    ORDER BY id DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $keyword, $keyword);
$stmt->execute();
$result = $stmt->get_result();

// 여기까지 PHP, 이제 HTML 출력 시작
?>

<style>
.search-title {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 20px;
}

.search-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 24px;
}

.search-item {
    text-decoration: none;
    color: #000;
}

.search-item img {
    width: 100%;
    height: 280px;
    object-fit: cover;
    border-radius: 8px;
    transition: 0.25s ease;
}

.search-item img:hover {
    transform: scale(1.04);
}

.search-name {
    margin-top: 6px;
    font-size: 0.95rem;
    font-weight: 600;
}

.search-price {
    font-size: 0.9rem;
    color: #666;
}
</style>

<div class="container py-4">

    <div class="search-title">
        🔍 "<?= htmlspecialchars($keyword) ?>" 검색 결과
    </div>

    <div class="search-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <a class="search-item" href="index.php?cmd=detail&idx=<?= $row['id'] ?>">
                    <img src="<?= htmlspecialchars($row['img1']) ?>" alt="">
                    <div class="search-name"><?= htmlspecialchars($row['name']) ?></div>
                    <div class="search-price"><?= number_format($row['price']) ?>원</div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <div>검색 결과가 없습니다.</div>
        <?php endif; ?>
    </div>

</div>
