CREATE TABLE input (
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- 1. 원문原文
    original TEXT NOT NULL,
    -- 2. 출처: 카테고리 + 번호
    source_category VARCHAR(20) NOT NULL,   -- 학이 / 위정 / 팔일
    source_number INT NOT NULL,             -- 1 ~ 40
    -- 3. 간체자
    simplified TEXT NOT NULL,
    -- 4. 간체자 발음
    simplified_pron TEXT NOT NULL,
    -- 5. 한글 번역
    korean TEXT NOT NULL,
    -- 6. 한글 발음
    korean_pron TEXT NOT NULL,
    -- 등록 시간
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

이런 형태로 게시판을 요청할거야.
모든 파일은 index.php를 거쳐서 가고 데이터베이스 설정은 이미 끝나서
$conn에 모든 접속정보를 포함하기 때문에,
Lboard.php는 순수한 게시판 동작만 있으면 돼.

GET방식으로 $mode를 확인해서
$mode가 없으면 $mode = "list", 즉 목록 보기
$mode == "show" : 글 내용보기

이때 각각의 기능은 다음과 같아.

글 목록 보기($mode = "list")
input 테이블에서 보여줘.
出处 카테고리를 기준으로
대분류(학이,위정,팔일)를 큰 글자로 하고 아래로 1,2,3...의 번호 순서대로 표시할거야.
완전히 동일한 항목이 두 개 설정된 경우는 먼저 만들어진 순서로 띄워줘.
또한 각각의 번호 옆에 '원문原文' 내용의 앞에서부터 5글자를 제목으로 해줘.
제목을 클릭하면 글 내용보기($mode = "show")로 이동해줘.

글 내용 보기($mode = "show")
원문, 출처, 간체자, 간체자 발음, 한글, 한글 발음
글 내용보기에서 줄바꿈이 있는 경우에는 <br>처리를 해줘.
만약 글 내용이 아무리 적어도 글 내용의 공간을 높이 300px을 최소한 자리를 잡아줘.
하단에는 삭제와 목록 버튼이 있고, 관리자(sino_level == 9)만 삭제가 가능해

1. '원문原文'부터 '出处'까지는 하나의 <div>로 묶을 거고 '간체자简体字'부터 끝까지 하나의 <div>로 묶을거야.
2. '원문原文'과 '간체자简体字'버튼은 스타일을 통일시키고 싶어.
3. '원문原文'을 입력한 내용이 가장 크고, '出处'은 '出处: 《(카테고리)》(번호)편'의 형식으로 오른쪽 끝에 작은 글씨로 넣고 싶어.
4. '간체자简体字' 버튼을 누르면 해당 버튼이 '한글韩文'로 변하며 표시 내용이 '한글韩文'입력, '한글韩文'발음 입력한 부분으로 바뀌게 하고 싶어.
5. 해석과 발음은 끄기/켜기 버튼으로 각각의 항목이 숨김/표시가 되었으면 좋겠어.
6. 좌우 화살표 버튼을 누르면 이전글/다음글로 넘어갔으면 좋겠어.
7. 두 div는 각각 위아래로 스크롤되며 따로 동작했으면 좋겠어.

최종 출력되는 화면은 가장 처음에 보냈던 사진 구성과 비슷해.
Lboard.php 파일을 만들어 줘.

---

원문과 해설 부분을 입력하는 입력창을 만들고 싶어.
그런데 WYSIWYG 형태로 입력을 받고 싶어.
하단에는 저장하기 버튼이 있어.

index.php?cmd=input 으로 접속하고
index.php에서 디비접속을 포함한 몸체는 다 만들어져있어.
$conn 에 DB접속 정보가 들어있어.
body에서 cmd값을 보고 input.php를 include할 것이기 때문에
순수 기능만 있으면 돼.
bootstrap5를 이용하고 있어. 이때 위지윅형태의 input.php 파일을 만들어줘
// --


첨부한 board를 만들기 위한 입력화면을 WYSIWYG게시판의 형식으로 만드려고 해.
nav바 아래 body부분만 만들거고, 저장은 input.php로 할 생각이야.
입력에 필요한 부분(textarea)은 차례대로 다음과 같아.

1. title: '원문原文'입력칸.
2. '出处'입력칸.
3. '간체자简体字'입력칸.
4. '간체자简体字'발음 입력 칸.
5. '한글韩文'입력칸.
6. '한글韩文'발음 입력 칸.

그리고 '등록'버튼과 '취소'버튼이 있었으면 좋겠어.
//--
2. 出处부분을 드롭다운 메뉴로 수정할 수 있을까
 카테고리 형식으로 수정하고 싶어.
 각각 항목은 학이, 위정, 팔일이고
 그 옆에 1부터 40까지 선택할 수 있는 드롭다운으로 해당 부분만 수정해줘.


//--

1. 위 입력한 내용을 db에 저장하고
2. 그것을 게시판 형태로 구현한 show.php를 만들거야.
위와 똑같이 순수 게시판 동작만 있으면 돼.

<목록 보기>
出处 카테고리를 기준으로
대분류(학이,위정,팔일)를 큰 글자로 하고 아래로 1,2,3...의 번호 순서대로 표시할거야.
완전히 동일한 항목이 두 개 설정된 경우는 먼저 만들어진 순서로 띄워줘.
또한 각각의 번호 옆에 '원문原文' 5글자를 제목으로 해줘.
해당 제목을 클릭하면 상세 페이지로 넘어갈거야.

db의 입력내용을 바탕으로 출력될 상세 페이지의 화면 구성은 다음과 같아.
1. '원문原文'부터 '出处'까지는 하나의 <div>로 묶을 거고 '간체자简体字'부터 끝까지 하나의 <div>로 묶을거야.
2. '원문原文'과 '간체자简体字'버튼은 스타일을 통일시키고 싶어.
3. '원문原文'을 입력한 내용이 가장 크고, '出处'은 '出处: 《(카테고리)》(번호)편'의 형식으로 오른쪽 끝에 작은 글씨로 넣고 싶어.
4. '간체자简体字' 버튼을 누르면 해당 버튼이 '한글韩文'로 변하며 표시 내용이 '한글韩文'입력, '한글韩文'발음 입력한 부분으로 바뀌게 하고 싶어.
5. 해석과 발음은 끄기/켜기 버튼으로 각각의 항목이 숨김/표시가 되었으면 좋겠어.
6. 좌우 화살표 버튼을 누르면 이전글/다음글로 넘어갔으면 좋겠어.
7. 관리자 모드로 설정시에만 '삭제'버튼이 떴으면 좋겠어.

---
<?php
// Lboard.php
// index.php에서 $conn (mysqli) 및 $sino_level 변수가 준비된 상태에서 include 해서 사용.
// - GET 'mode' : 기본 'list', 'show'는 글 보기
// - POST action=delete 처리 (관리자만 가능)

// 안전장치: $conn, $sino_level 존재 체크 (없으면 에러 메시지)
if (!isset($conn)) {
    echo '<div class="alert alert-danger">DB 연결이 설정되어 있지 않습니다 (\$conn 필요).</div>';
    return;
}
if (!isset($sino_level)) $sino_level = 0;

// helper: 안전 출력
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

// mode 결정
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'list';
if ($mode !== 'show') $mode = 'list';

// 카테고리 정렬 순서 고정: 학이, 위정, 팔일
$category_order = ["학이", "위정", "팔일"];

// --- 목록 보기 (list) ---
if ($mode === 'list') {
    // 전체 행을 가져와 그룹화
    // 정렬: source_category (우선순위), source_number ASC, id ASC
    // To ensure category ordering, we'll map category to FIELD(...) in ORDER BY
    $order_case = "FIELD(source_category";
    foreach ($category_order as $c) $order_case .= "," . "'" . $conn->real_escape_string($c) . "'";
    $order_case .= ")";

    $sql = "SELECT * FROM input
            ORDER BY {$order_case}, source_number ASC, id ASC";
    $res = $conn->query($sql);
    $rows = [];
    while ($r = $res->fetch_assoc()) $rows[] = $r;

    // 그룹화
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
                            <div class="text-muted small"><?php echo h(mb_substr($item['original'], 0, 20)); // 간단한 미리보기 ?></div>
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
if ($mode === 'list') exit; // 안전
if ($mode === 'show') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($id <= 0) {
        echo "<div class='alert alert-warning'>잘못된 게시물입니다.</div>";
        return;
    }

    // 현재 글 가져오기
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

    // 이전/다음 id 구하기 (전체 순서 기준: same ordering as list)
    $order_case_sql = "FIELD(source_category";
    foreach ($category_order as $c) $order_case_sql .= "," . "'" . $conn->real_escape_string($c) . "'";
    $order_case_sql .= ")";

    // 모든 id를 정렬해서 가져오고 현재 위치 찾아서 이전/다음 결정
    $id_list = [];
    $sql_all = "SELECT id FROM input ORDER BY {$order_case_sql}, source_number ASC, id ASC";
    $r_all = $conn->query($sql_all);
    while ($rr = $r_all->fetch_assoc()) $id_list[] = (int)$rr['id'];
    $pos = array_search($id, $id_list);
    $prev_id = ($pos !== false && $pos > 0) ? $id_list[$pos - 1] : null;
    $next_id = ($pos !== false && $pos < count($id_list) - 1) ? $id_list[$pos + 1] : null;

    // 출력 준비 (nl2br, htmlspecialchars)
    $original = nl2br(h($row['original']));
    $source_category = h($row['source_category']);
    $source_number = (int)$row['source_number'];
    $simplified = nl2br(h($row['simplified']));
    $simplified_pron = nl2br(h($row['simplified_pron']));
    $korean = nl2br(h($row['korean']));
    $korean_pron = nl2br(h($row['korean_pron']));
    ?>

    <style>
        /* 두 개의 독립 스크롤 영역을 위한 스타일 */
        .view-container { max-width:1000px; margin:20px auto; }
        .area-box { border:1px solid #e5e5e5; border-radius:8px; padding:12px; background:#fff; }
        .scroll-area { max-height: 360px; min-height:300px; overflow-y:auto; padding:8px; }
        .title-large { font-size:1.25rem; font-weight:800; }
        .small-right { float:right; font-size:0.85rem; color:#6c757d; }
        .toggle-btn { min-width:120px; }
        .btn-unified { background:#343a40; color:#fff; border:none; }
        .btn-unified:hover { opacity:0.9; }
    </style>

    <div class="container view-container">

        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <a href="index.php?cmd=Lboard" class="btn btn-outline-secondary btn-sm">목록</a>
            </div>
            <div>
                <!-- 이전/다음 -->
                <a href="<?php echo $prev_id ? 'index.php?cmd=Lboard&mode=show&id='.$prev_id : '#'; ?>" class="btn btn-light btn-sm <?php echo $prev_id ? '' : 'disabled'; ?>" title="이전 글">&larr;</a>
                <a href="<?php echo $next_id ? 'index.php?cmd=Lboard&mode=show&id='.$next_id : '#'; ?>" class="btn btn-light btn-sm <?php echo $next_id ? '' : 'disabled'; ?>" title="다음 글">&rarr;</a>
            </div>
        </div>

        <!-- 상단: 원문 + 출처 -->
        <div class="area-box mb-3">
            <div class="d-flex justify-content-between align-items-start">
                <div style="flex:1;">
                    <div class="title-large mb-2"><?php echo $original; ?></div>
                    <!-- 오른쪽 작은 출처 텍스트 -->
                </div>
                <div class="small-right">
                    出处: 《<?php echo $source_category; ?>》<?php echo $source_number; ?>편
                </div>
            </div>

            <div class="scroll-area mt-2" id="area-original">
                <?php
                    // 원문 최소 높이 확보(이미 scroll-area에 min-height:300px)
                    echo $original;
                ?>
            </div>
        </div>

        <!-- 하단: 간체자 / 토글 한글, 발음 토글 -->
        <div class="area-box">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <!-- 간체자 버튼 (통일된 스타일) -->
                    <button type="button" id="btn-toggle-lang" class="btn btn-unified toggle-btn btn-sm">
                        간체자简体字
                    </button>

                    <!-- 해석 토글 -->
                    <button type="button" id="btn-toggle-korean" class="btn btn-outline-secondary btn-sm ms-2">해석(한글) 표시</button>

                    <!-- 발음 토글 -->
                    <button type="button" id="btn-toggle-pron" class="btn btn-outline-secondary btn-sm ms-2">발음 표시</button>
                </div>

                <div>
                    <!-- 삭제(관리자) -->
                    <?php if ($sino_level == 9): ?>
                        <form method="post" style="display:inline;" onsubmit="return confirm('정말 삭제하시겠습니까?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">삭제</button>
                        </form>
                    <?php endif; ?>
                    <a href="index.php?cmd=Lboard" class="btn btn-secondary btn-sm ms-2">목록</a>
                </div>
            </div>

            <div class="scroll-area" id="area-lower">
                <!-- 기본은 간체자 표시 -->
                <div id="box-simplified">
                    <h5 class="mb-2">간체자</h5>
                    <div><?php echo $simplified; ?></div>

                    <div class="mt-3">
                        <h6>간체자 발음</h6>
                        <div id="simplified-pron"><?php echo $simplified_pron; ?></div>
                    </div>
                </div>

                <!-- 한글 영역: 기본 숨김 (토글로 표시) -->
                <div id="box-korean" style="display:none;">
                    <h5 class="mb-2">한글 번역</h5>
                    <div id="korean-content"><?php echo $korean; ?></div>

                    <div class="mt-3">
                        <h6>한글 발음</h6>
                        <div id="korean-pron"><?php echo $korean_pron; ?></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        (function(){
            // 토글 버튼: 간체자 <-> 한글
            var isKorean = false;
            var btnToggle = document.getElementById('btn-toggle-lang');
            var boxSimpl = document.getElementById('box-simplified');
            var boxKor = document.getElementById('box-korean');

            btnToggle.addEventListener('click', function(){
                isKorean = !isKorean;
                if (isKorean) {
                    btnToggle.textContent = '한글韩文';
                    boxSimpl.style.display = 'none';
                    boxKor.style.display = 'block';
                } else {
                    btnToggle.textContent = '간체자简体字';
                    boxSimpl.style.display = 'block';
                    boxKor.style.display = 'none';
                }
            });

            // 해석 토글 (한글 영역 표시 on/off)
            var btnKor = document.getElementById('btn-toggle-korean');
            btnKor.addEventListener('click', function(){
                // if currently showing korean (boxKor visible) => hide boxKor, else show
                var showing = (boxKor.style.display !== 'none');
                if (showing) {
                    // hide korean section entirely (but keep simplified visible)
                    boxKor.style.display = 'none';
                    // update button styles
                    btnKor.classList.remove('btn-secondary');
                    btnKor.classList.add('btn-outline-secondary');
                    btnKor.textContent = '해석(한글) 표시';
                } else {
                    // show korean section
                    boxKor.style.display = 'block';
                    btnKor.classList.remove('btn-outline-secondary');
                    btnKor.classList.add('btn-secondary');
                    btnKor.textContent = '해석(한글) 숨기기';
                }
            });

            // 발음 토글: 간체자/한글 발음 모두 토글 (hide/show)
            var btnPron = document.getElementById('btn-toggle-pron');
            var simplifiedPron = document.getElementById('simplified-pron');
            var koreanPron = document.getElementById('korean-pron');
            btnPron.addEventListener('click', function(){
                var showing = (simplifiedPron.style.display !== 'none' || koreanPron.style.display !== 'none');
                if (showing) {
                    simplifiedPron.style.display = 'none';
                    koreanPron.style.display = 'none';
                    btnPron.classList.remove('btn-secondary');
                    btnPron.classList.add('btn-outline-secondary');
                    btnPron.textContent = '발음 표시';
                } else {
                    // Show whichever area is active. If both hidden, show both
                    simplifiedPron.style.display = '';
                    koreanPron.style.display = '';
                    btnPron.classList.remove('btn-outline-secondary');
                    btnPron.classList.add('btn-secondary');
                    btnPron.textContent = '발음 숨기기';
                }
            });

            // 초기 상태: 발음 보이기, 해석은 hidden (but boxKor hidden by default)
            simplifiedPron.style.display = '';
            koreanPron.style.display = 'none';
        })();
    </script>

    <?php
    exit;
}

이렇게lboard.php가 있어. 다음과 같은 코드를 기준으로 해당 사항들만 변경해줘.
1.  '해석(한글)표시' 토글의 기능을 바꿔줘. '간체자' 일 때는 간체자 on/off로, '한글'일 때는 한글 번역 on/off로 기능하게 하고싶어.
2.  '발음 표시' 토글의 기능도 마찬가지로 '간체자' 일 때는 간체자의 발음을, '한글'일 때는 한글 발음을 on/off 하는 걸로 바꿔줘.
3. 해당 토글들 옆에 있는 '목록' 버튼 대신 관리자($sino_level == 9) 모드일 때만 '글 삭제' 버튼이 뜨고 여기서만 '삭제' 기능이 작동할 수 있게 바꿔줘. 

-
네 말대로 해당 부분의 코드를 수정했더니 다음과 같은 문제가 일어나.
1. 모든 토글이 왼쪽으로 이동됨.
2. '한글 숨기기', '간체자 숨기기' 버튼을 눌렀을 때 모든 글이 사라짐
3. 관리자의 권한을 가지고 있음에도 '삭제' 버튼이 뜨지 않음.

내가 보낸 사진을 참고해줘. 다음과 같이 변경하고 싶어.
1. 모든 토글은 상단으로 옮김.
2. '한글 숨기기', '간체자 숨기기' 버튼을 눌렀을 때 각각 '한글 발음'과 간체자 발음'은 유지하고 싶음.
(각각의 토글이 각각의 항목을 관장했으면 함.)
3. '삭제'버튼의 원활한 기능.

다음 사항들을 반영해서 한번 더 수정해줄 수 있어?

-
해당 코드의 기본적인 틀을 유지한채로 다음과 같은 부분만 바꾸고 싶어.
1. '간체자 숨기기', '한글 숨기기' 버튼을 눌렀을 경우 숨기지 않았을 때 늘어난만큼의 빈칸을 유지하고 싶어.
2. 出处 옆에 있는 원문 표기에서, 제목(굵은 글씨)의 글자 크기를 조금 더 키우고 아래 작게 뜨는 원문은 지우고 싶어.
3. 원문 밑에있는 여백의 크기를 간체자 박스의 여백 크기만큼 줄이고 싶어.
해당 부분을 수정해서 전체 파일로 a.php 파일 링크를 만들어 제공해줘. 시간 많이 걸려도 상관 없으니까 내용 담아서 줘야 해.