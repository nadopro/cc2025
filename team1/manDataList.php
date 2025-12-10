<?php
// ------------------------------------------
// 삭제 처리
// ------------------------------------------
if (isset($_GET['delete'])) {
    $deleteIdx = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM sentence WHERE idx = ?");
    $stmt->bind_param("i", $deleteIdx);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('삭제되었습니다.'); location.href='index.php?cmd=manDataList';</script>";
    exit;
}

// ------------------------------------------
// 데이터 가져오기 (소분류 cat 기준 정렬)
// ------------------------------------------
$result = $conn->query("
    SELECT idx, cat, org, memo 
    FROM sentence 
    ORDER BY cat ASC, idx ASC
");
?>

<div class="container mt-4">
    <h3>문장 데이터 목록</h3>
    <hr>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th width="60">순서</th>
                <th width="80">idx</th>
                <th width="120">소분류</th>
                <th>원문(org)</th>
                <th width="250">메모(memo)</th>
                <th width="150">비고</th>
            </tr>
        </thead>
        <tbody>

        <?php
        $no = 1;

        while ($row = $result->fetch_assoc()) {
            $catName = isset($catList[$row['cat']]) ? $catList[$row['cat']] : "미분류";
        ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['idx']; ?></td>
                <td><?= htmlspecialchars($catName); ?></td>
                <td style="white-space: pre-line;"><?= htmlspecialchars($row['org']); ?></td>
                <td style="white-space: pre-line;"><?= htmlspecialchars($row['memo']); ?></td>

                <td>
                    <!-- 수정 버튼 -->
                    <a href="index.php?cmd=manData&idx=<?= $row['idx']; ?>" 
                       class="btn btn-sm btn-primary mb-1">수정</a>

                    <!-- 삭제 버튼 -->
                    <a href="javascript:void(0);" 
                       onclick="if(confirm('정말 삭제하시겠습니까?')) location.href='index.php?cmd=manDataList&delete=<?= $row['idx']; ?>';"
                       class="btn btn-sm btn-danger">삭제</a>
                </td>
            </tr>
        <?php
        }
        ?>

        </tbody>
    </table>
</div>
