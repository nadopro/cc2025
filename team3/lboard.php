<?php
// a.php (Lboard.php modified) - single-file board viewer/editor
// Assumes index.php includes this file with $conn (mysqli) and $sino_level defined
// If you want to use this as standalone, define $conn and $sino_level before including.

// Safety checks
if (!isset($conn)) {
    echo '<div class="alert alert-danger">DB 연결이 설정되어 있지 않습니다 ($conn 필요).</div>';
    return;
}
if (!isset($sino_level)) $sino_level = 0;

// helpers
function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function mb_left($s, $n = 5) {
    if (mb_strlen($s) <= $n) return $s;
    return mb_substr($s, 0, $n) . '...';
}

// 처리: 삭제 (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
    $del_id = (int)$_POST['id'];
    if ($sino_level == 9) {
        $stmt = $conn->prepare("DELETE FROM input WHERE id = ?");
        $stmt->bind_param("i", $del_id);
        $ok = $stmt->execute();
        $stmt->close();
        if ($ok) {
            echo "<script>alert('삭제되었습니다.'); location.href='index.php?cmd=Lboard';</script>";
            exit;
        } else {
            echo "<div class='alert alert-danger'>삭제 중 오류가 발생했습니다: ".h($conn->error)."</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>삭제 권한이 없습니다.</div>";
    }
}

// mode 결정 (허용: list, show, edit)
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'list';
if (!in_array($mode, ['list','show','edit'])) $mode = 'list';

// 카테고리 정렬 순서 고정: 학이, 위정, 팔일
$category_order = ["학이", "위정", "팔일", "옹야", "술이", "자한", "자장"];


// --- 목록 보기 (list) ---
if ($mode === 'list') {
    $order_case = "FIELD(source_category";
    foreach ($category_order as $c) $order_case .= "," . "'" . $conn->real_escape_string($c) . "'";
    $order_case .= ")";

    $sql = "SELECT * FROM input
            ORDER BY {$order_case}, source_number ASC, id ASC";
    $res = $conn->query($sql);
    $rows = [];
    while ($r = $res->fetch_assoc()) $rows[] = $r;

    $groups = [];
    foreach ($rows as $r) {
        $cat = $r['source_category'];
        if (!isset($groups[$cat])) $groups[$cat] = [];
        $groups[$cat][] = $r;
    }
    ?>

    <div class="container mt-4 mb-5" style="max-width:1000px;">
        <h3 class="mb-4 fw-bold">목록</h3>

        <?php foreach ($category_order as $cat): ?>
            <?php if (!isset($groups[$cat]) || count($groups[$cat]) === 0) continue; ?>
            <div class="mb-4">
                <h4 class="mb-2" style="font-weight:800; font-size:1.25rem;"><?php echo h($cat); ?></h4>

                <ul class="list-group">
                    <?php foreach ($groups[$cat] as $item): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <small class="me-2 text-muted"><?php echo h($item['source_number']); ?>.</small>
                                <a href="index.php?cmd=Lboard&mode=show&id=<?php echo (int)$item['id']; ?>">
                                    <?php
                                        $title = mb_left($item['original'], 5);
                                        echo h($title);
                                    ?>
                                </a>
                            </div>
                            <div class="text-muted small"><?php echo h(mb_substr($item['original'], 0, 20)); ?></div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>

    </div>
    <?php
    exit;
}

// --- 글 내용 보기 (show) ---
if ($mode === 'show') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($id <= 0) {
        echo "<div class='alert alert-warning'>잘못된 게시물입니다.</div>";
        return;
    }

    $stmt = $conn->prepare("SELECT * FROM input WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();
    if (!$row) {
        echo "<div class='alert alert-warning'>게시물이 없습니다.</div>";
        return;
    }

    $order_case_sql = "FIELD(source_category";
    foreach ($category_order as $c) $order_case_sql .= "," . "'" . $conn->real_escape_string($c) . "'";
    $order_case_sql .= ")";

    $id_list = [];
    $sql_all = "SELECT id FROM input ORDER BY {$order_case_sql}, source_number ASC, id ASC";
    $r_all = $conn->query($sql_all);
    while ($rr = $r_all->fetch_assoc()) $id_list[] = (int)$rr['id'];
    $pos = array_search($id, $id_list);
    $prev_id = ($pos !== false && $pos > 0) ? $id_list[$pos - 1] : null;
    $next_id = ($pos !== false && $pos < count($id_list) - 1) ? $id_list[$pos + 1] : null;

    $original = nl2br(h($row['original']));
    $source_category = h($row['source_category']);
    $source_number = (int)$row['source_number'];
    $simplified = nl2br(h($row['simplified']));
    $simplified_pron = nl2br(h($row['simplified_pron']));
    $korean = nl2br(h($row['korean']));
    $korean_pron = nl2br(h($row['korean_pron']));
    ?>

<style>
.view-container { max-width:1000px; margin:20px auto; }
.area-box { border:1px solid #e5e5e5; border-radius:8px; padding:12px; background:#fff; }
/* 상단 원문 영역 높이 줄임 */
.scroll-area { max-height:300px; overflow-y:auto; padding:8px; }
/* 제목 글씨 더 크게 변경 */
.title-large { font-size:1.9rem; font-weight:800; line-height:2.5rem; }
/* 기존 small-right 옆의 원문 미리보기 제거되었음 */
.title-small { display:none; }
#simplified-body, #korean-body, #simplified-pron, #korean-pron {
    min-height: 30px; /* 고정된 빈칸 영역 유지 */
}
</style>

<div class="container view-container">

    <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
            <a href="index.php?cmd=Lboard" class="btn btn-outline-secondary btn-sm">목록</a>
        </div>
        <div>
            <a href="<?php echo $prev_id ? 'index.php?cmd=Lboard&mode=show&id='.$prev_id : '#'; ?>" class="btn btn-light btn-sm <?php echo $prev_id ? '' : 'disabled'; ?>" title="이전 글">&larr;</a>
            <a href="<?php echo $next_id ? 'index.php?cmd=Lboard&mode=show&id='.$next_id : '#'; ?>" class="btn btn-light btn-sm <?php echo $next_id ? '' : 'disabled'; ?>" title="다음 글">&rarr;</a>
        </div>
    </div>

    <div class="area-box mb-1">  <!-- ← 원문과 아래 box 간의 여백을 3→1단위로 줄임 -->
        <div class="d-flex justify-content-between align-items-start">
            <div style="flex:1;">
                <!-- 아래 작은 원문 미리보기 제거 -->
                <div class="title-large mb-2"><?php echo $original; ?></div>
            </div>
            <div class="small-right">
                出处: 《<?php echo $source_category; ?>》<?php echo $source_number; ?>편
            </div>
        </div>
    </div>

<div class="area-box">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
            <button type="button" id="btn-toggle-lang" class="btn btn-unified btn-sm">
                간체자简体字
            </button>
            <button type="button" id="btn-toggle-body" class="btn btn-outline-secondary btn-sm">
                간체자 숨기기
            </button>
            <button type="button" id="btn-toggle-pron" class="btn btn-outline-secondary btn-sm">
                발음 숨기기
            </button>
        </div>
        <div class="d-flex gap-2">
            <?php if ($sino_level == 9): ?>
                <a href="index.php?cmd=Lboard&mode=edit&id=<?php echo (int)$row['id']; ?>"
                   class="btn btn-warning btn-sm">수정</a>
                <form method="post" onsubmit="return confirm('정말 삭제하시겠습니까?');" style="margin:0;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>">
                    <button type="submit" class="btn btn-dark btn-sm">삭제</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div id="area-lower">

        <!-- 간체자 영역 -->
        <div id="box-simplified">
            <h5 class="mb-2">간체자 해석</h5>
            <div id="simplified-body"><?php echo $simplified; ?></div>

            <div class="mt-3">
                <h5 class="mb-2">간체자 발음</h5>
                <div id="simplified-pron"><?php echo $simplified_pron; ?></div>
            </div>
        </div>

        <!-- 한글 영역 -->
        <div id="box-korean" style="display:none;">
            <h5 class="mb-2">한글 해석</h5>
            <div id="korean-body"><?php echo $korean; ?></div>

            <div class="mt-3">
                <h5 class="mb-2">한글 발음</h5>
                <div id="korean-pron"><?php echo $korean_pron; ?></div>
            </div>
        </div>

    </div>
</div>

<script>
(function() {
    var isKorean = false;

    var btnLang = document.getElementById('btn-toggle-lang');
    var btnBody = document.getElementById('btn-toggle-body');
    var btnPron = document.getElementById('btn-toggle-pron');

    var boxSimpl = document.getElementById('box-simplified');
    var boxKor = document.getElementById('box-korean');

    var simplBody = document.getElementById('simplified-body');
    var simplPron = document.getElementById('simplified-pron');

    var korBody = document.getElementById('korean-body');
    var korPron = document.getElementById('korean-pron');

    btnLang.addEventListener('click', function() {
        isKorean = !isKorean;

        if (isKorean) {
            btnLang.textContent = '한글韓文';
            boxSimpl.style.display = 'none';
            boxKor.style.display = 'block';
            btnBody.textContent = '한글 숨기기';
        } else {
            btnLang.textContent = '간체자简体字';
            boxSimpl.style.display = 'block';
            boxKor.style.display = 'none';
            btnBody.textContent = '간체자 숨기기';
        }
    });

    btnBody.addEventListener('click', function() {
        if (!isKorean) {
            if (simplBody.style.visibility === 'hidden') {
                simplBody.style.visibility = 'visible';
                btnBody.textContent = '간체자 숨기기';
            } else {
                simplBody.style.visibility = 'hidden';
                btnBody.textContent = '간체자 표시';
            }
        } else {
            if (korBody.style.visibility === 'hidden') {
                korBody.style.visibility = 'visible';
                btnBody.textContent = '한글 숨기기';
            } else {
                korBody.style.visibility = 'hidden';
                btnBody.textContent = '한글 표시';
            }
        }
    });

    btnPron.addEventListener('click', function() {
        if (!isKorean) {
            if (simplPron.style.visibility === 'hidden') {
                simplPron.style.visibility = 'visible';
                btnPron.textContent = '발음 숨기기';
            } else {
                simplPron.style.visibility = 'hidden';
                btnPron.textContent = '발음 표시';
            }
        } else {
            if (korPron.style.visibility === 'hidden') {
                korPron.style.visibility = 'visible';
                btnPron.textContent = '발음 숨기기';
            } else {
                korPron.style.visibility = 'hidden';
                btnPron.textContent = '발음 표시';
            }
        }
    });

})();
</script>

<?php
exit;
}

// --- 글 수정 (edit) ---
if ($mode === 'edit') {
    if ($sino_level != 9) {
        echo "<div class='alert alert-danger'>수정 권한이 없습니다.</div>";
        return;
    }

    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($id <= 0) {
        echo "<div class='alert alert-warning'>잘못된 요청입니다.</div>";
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
        $original = $_POST['original'] ?? '';
        $simplified = $_POST['simplified'] ?? '';
        $simplified_pron = $_POST['simplified_pron'] ?? '';
        $korean = $_POST['korean'] ?? '';
        $korean_pron = $_POST['korean_pron'] ?? '';

        $stmt = $conn->prepare("
            UPDATE input SET
                original = ?, simplified = ?, simplified_pron = ?,
                korean = ?, korean_pron = ?
            WHERE id = ?
        ");

        if ($stmt === false) {
            echo "<div class='alert alert-danger'>준비 실패: " . h($conn->error) . "</div>";
        } else {
            $stmt->bind_param("sssssi",
                $original, $simplified, $simplified_pron,
                $korean, $korean_pron, $id
            );

            if ($stmt->execute()) {
                echo "<script>alert('수정되었습니다.'); location.href='index.php?cmd=Lboard&mode=show&id={$id}';</script>";
                exit;
            } else {
                echo "<div class='alert alert-danger'>수정 중 오류 발생: ".h($conn->error)."</div>";
            }
            $stmt->close();
        }
    }

    $stmt = $conn->prepare("SELECT * FROM input WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    if (!$row) {
        echo "<div class='alert alert-warning'>데이터 없음</div>";
        return;
    }
    ?>

    <div class="container" style="max-width:900px;">
        <h3 class="fw-bold mb-3">수정</h3>

        <form method="post">
            <input type="hidden" name="action" value="update">

            <div class="mb-3">
                <label class="form-label fw-bold">원문</label>
                <textarea name="original" class="form-control" rows="3"><?php echo h($row['original']); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">간체자 해석</label>
                <textarea name="simplified" class="form-control" rows="3"><?php echo h($row['simplified']); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">간체자 발음</label>
                <textarea name="simplified_pron" class="form-control" rows="3"><?php echo h($row['simplified_pron']); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">한글 해석</label>
                <textarea name="korean" class="form-control" rows="3"><?php echo h($row['korean']); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">한글 발음</label>
                <textarea name="korean_pron" class="form-control" rows="3"><?php echo h($row['korean_pron']); ?></textarea>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="index.php?cmd=Lboard&mode=show&id=<?php echo $id; ?>" class="btn btn-secondary">취소</a>
                <button type="submit" class="btn btn-primary">저장</button>
            </div>

        </form>
    </div>

    <?php
    exit;
}

?>
