<?php
// index.php에서 config.php include, DB접속($conn) 완료됨.

// ---------------------------------------------------
// 수정 모드인지 확인
// ---------------------------------------------------
$editMode = false;
$editData = [
    'idx' => '',
    'cat' => '',
    'org' => '',
    'memo' => ''
];

if (isset($_GET['idx']) && is_numeric($_GET['idx'])) {
    $editIdx = intval($_GET['idx']);

    $stmt = $conn->prepare("SELECT idx, cat, org, memo FROM sentence WHERE idx = ?");
    $stmt->bind_param("i", $editIdx);
    $stmt->execute();
    $result = $stmt->get_result();
    $editData = $result->fetch_assoc();
    $stmt->close();

    if ($editData) {
        $editMode = true;
    }
}

// ---------------------------------------------------
// POST 처리 (등록 또는 수정)
// ---------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cat  = $_POST['cat'] ?? '';
    $org  = $_POST['org'] ?? '';
    $memo = $_POST['memo'] ?? '';
    $idx  = $_POST['idx'] ?? '';  // 수정 모드이면 존재

    if ($cat && $org) {

        if ($idx) {
            // ------------------------------
            // UPDATE 처리
            // ------------------------------
            $stmt = $conn->prepare("
                UPDATE sentence SET cat = ?, org = ?, memo = ? WHERE idx = ?
            ");
            $stmt->bind_param("issi", $cat, $org, $memo, $idx);

            if ($stmt->execute()) {
                $alert = '<div class="alert alert-success mt-3">수정되었습니다.</div>';
            } else {
                $alert = '<div class="alert alert-danger mt-3">수정 실패: '.$stmt->error.'</div>';
            }

            $stmt->close();

        } else {
            // ------------------------------
            // INSERT 처리
            // ------------------------------
            $stmt = $conn->prepare("
                INSERT INTO sentence (cat, org, memo)
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("iss", $cat, $org, $memo);

            if ($stmt->execute()) {
                $alert = '<div class="alert alert-success mt-3">등록되었습니다.</div>';
            } else {
                $alert = '<div class="alert alert-danger mt-3">등록 실패: '.$stmt->error.'</div>';
            }

            $stmt->close();
        }

    } else {
        $alert = '<div class="alert alert-warning mt-3">카테고리와 원문은 필수입니다.</div>';
    }
}

?>

<div class="container mt-4">

    <h3>문장 데이터 <?= $editMode ? "수정" : "등록" ?></h3>
    <hr>

    <?= $alert ?? '' ?>

    <form method="POST">

        <?php if ($editMode): ?>
            <input type="hidden" name="idx" value="<?= $editData['idx'] ?>">
        <?php endif; ?>

        <!-- 카테고리 선택 -->
        <div class="mb-3">
            <label class="form-label">카테고리(소분류)</label>
            <select name="cat" class="form-select" required>
                <option value="">선택하세요</option>

                <?php
                for ($big = 1; $big <= 5; $big++) {

                    if (!isset($catList[$big])) continue;

                    echo '<optgroup label="'.$catList[$big].'">';

                    foreach ($catList as $key => $value) {
                        if ($key >= 10 && intdiv($key, 10) == $big) {

                            $selected = ($editMode && $editData['cat'] == $key) ? "selected" : "";

                            echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                        }
                    }

                    echo '</optgroup>';
                }
                ?>
            </select>
        </div>

        <!-- 원문 -->
        <div class="mb-3">
            <label class="form-label">원문(한문 + 해석)</label>
            <textarea name="org" class="form-control" rows="6" required><?= $editMode ? htmlspecialchars($editData['org']) : "" ?></textarea>
        </div>

        <!-- 메모 -->
        <div class="mb-3">
            <label class="form-label">메모(관리자 메모)</label>
            <textarea name="memo" class="form-control" rows="3"><?= $editMode ? htmlspecialchars($editData['memo']) : "" ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            <?= $editMode ? "수정하기" : "등록하기" ?>
        </button>
    </form>
</div>
