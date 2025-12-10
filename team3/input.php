<?php
// input.php - bootstrap5 기반 입력 및 DB 저장 기능 포함
// index.php에서 이미 $conn(DB 연결)이 준비된 상태라고 가정

// 등록 처리 (POST 요청일 때 실행)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $original        = $_POST['original'];
    $source_category = $_POST['source_category'];
    $source_number   = $_POST['source_number'];
    $simplified      = $_POST['simplified'];
    $simplified_pron = $_POST['simplified_pron'];
    $korean          = $_POST['korean'];
    $korean_pron     = $_POST['korean_pron'];

    $sql = "INSERT INTO input
            (original, source_category, source_number, simplified, simplified_pron, korean, korean_pron)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssissss",
        $original,
        $source_category,
        $source_number,
        $simplified,
        $simplified_pron,
        $korean,
        $korean_pron
    );

    if ($stmt->execute()) {
        echo "<script>alert('등록이 완료되었습니다.'); window.location.href='index.php?cmd=input';</script>";
        exit;
    } else {
        echo "<script>alert('DB 오류 발생: " . $conn->error . "');</script>";
    }

    $stmt->close();
}
?>
<?php
// input.php - bootstrap5 기반 입력 화면 (순수 기능 UI만 구성)
?>

<div class="container mt-4 mb-5" style="max-width: 900px;">
    <h3 class="mb-4 fw-bold">게시물 등록</h3>

    <form method="post" action="index.php?cmd=input">

        <!-- 1. 원문原文 -->
        <div class="mb-3">
            <label class="form-label fw-bold">1. 원문原文</label>
            <textarea class="form-control" name="original" rows="4" placeholder="원문을 입력하세요..."></textarea>
        </div>

        <!-- 2. 出처 (카테고리 + 번호 선택) -->
<div class="mb-3">
    <label class="form-label fw-bold">2. 出处</label>
    <div class="row g-2">
        <div class="col-md-6">
            <select class="form-select" name="source_category">
                <option value="학이">학이</option>
                <option value="위정">위정</option>
                <option value="팔일">팔일</option>
                <option value="학이">옹야</option>
                <option value="위정">술이</option>
                <option value="팔일">자한</option>
                <option value="팔일">자장</option>

                //, "옹야", "술이", "자한", "자장"
            </select>
        </div>
        <div class="col-md-6">
            <select class="form-select" name="source_number">
                <?php for ($i = 1; $i <= 40; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>
    </div>
</div>

        <!-- 3. 간체자简体字 -->
        <div class="mb-3">
            <label class="form-label fw-bold">3. 간체자简体字</label>
            <textarea class="form-control" name="simplified" rows="4" placeholder="간체자를 입력하세요..."></textarea>
        </div>

        <!-- 4. 간체자 발음 -->
        <div class="mb-3">
            <label class="form-label fw-bold">4. 간체자简体字 발음</label>
            <textarea class="form-control" name="simplified_pron" rows="2" placeholder="발음을 입력하세요..."></textarea>
        </div>

        <!-- 5. 한글 -->
        <div class="mb-3">
            <label class="form-label fw-bold">5. 한글韩文</label>
            <textarea class="form-control" name="korean" rows="4" placeholder="한글 번역을 입력하세요..."></textarea>
        </div>

        <!-- 6. 한글 발음 -->
        <div class="mb-3">
            <label class="form-label fw-bold">6. 한글韩文 발음</label>
            <textarea class="form-control" name="korean_pron" rows="2" placeholder="한글 발음을 입력하세요..."></textarea>
        </div>

        <!-- 버튼 영역 -->
        <div class="d-flex justify-content-end mt-4 gap-2">
            <a href="index.php" class="btn btn-secondary">취소</a>
            <button type="submit" class="btn btn-dark">등록</button>
        </div>

    </form>
</div>
