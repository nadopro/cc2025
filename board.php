<?php
// board.php
// Assumptions:
// - All requests route through index.php (e.g., index.php?cmd=board&bid=1)
// - A valid MySQLi connection is available as $conn
// - Session is already started outside, with $_SESSION['sino_id'] and $_SESSION['sino_level']
// - Table: `board` as defined by the user prompt

if (!isset($conn) || !($conn instanceof mysqli)) {
    die('DB connection ($conn) not found.');
}

// --- Helpers -----------------------------------------------------------------
function h($str) { return htmlspecialchars($str ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function param_int($key, $default = 0) { return isset($_GET[$key]) ? max(0, (int)$_GET[$key]) : $default; }
function post($key, $default = '') { return isset($_POST[$key]) ? trim($_POST[$key]) : $default; }

$bid  = isset($_GET['bid']) ? (int)$_GET['bid'] : 1;
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'list';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

// Sanity: restrict unrealistic bid
if ($bid <= 0) { $bid = 1; }

// --- Routing -----------------------------------------------------------------
switch ($mode) {
    case 'write':
        // If POST, attempt insertion then redirect to list
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title   = post('title');
            $name    = post('name');
            $content = post('html'); // Using column name 'html' to store the content

            $user_id   = $_SESSION['sino_id']   ?? '';
            $user_level= (int)($_SESSION['sino_level'] ?? 0);

            if ($user_id === '') {
                echo '<div class="alert">로그인 후 글쓰기가 가능합니다.</div>';
            } elseif ($title === '' || $name === '' || $content === '') {
                echo '<div class="alert">제목, 작성자, 내용을 모두 입력하세요.</div>';
            } else {
                $sql = 'INSERT INTO board (bid, title, html, id, name) VALUES (?,?,?,?,?)';
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param('issss', $bid, $title, $content, $user_id, $name);
                    $stmt->execute();
                    $stmt->close();
                    // Redirect to list
                    header('Location: index.php?cmd=board&bid='.$bid.'&mode=list');
                    exit;
                } else {
                    echo '<div class="alert">글 저장 중 오류가 발생했습니다.</div>';
                }
            }
        }
        // Show write form
        echo '<div class="board write">';
        echo '  <h2>글 쓰기</h2>';
        echo '  <form method="post" action="index.php?cmd=board&bid='.h($bid).'&mode=write">';
        echo '    <div class="form-row">';
        echo '      <label>제목</label>';
        echo '      <input type="text" name="title" maxlength="255" required />';
        echo '    </div>';
        echo '    <div class="form-row">';
        echo '      <label>작성자</label>';
        echo '      <input type="text" name="name" maxlength="50" required />';
        echo '    </div>';
        echo '    <div class="form-row">';
        echo '      <label>내용</label>';
        echo '      <textarea name="html" rows="12" required></textarea>';
        echo '    </div>';
        echo '    <div class="actions">';
        echo '      <button type="submit">글등록</button>';
        echo '      <a class="btn" href="index.php?cmd=board&bid='.h($bid).'&mode=list">목록보기</a>';
        echo '    </div>';
        echo '  </form>';
        echo '</div>';
        break;

    case 'show':
        $idx = param_int('idx');
        if ($idx <= 0) {
            echo '<div class="alert">잘못된 요청입니다.</div>';
            break;
        }

        // Increase hit count (best-effort; ignore errors)
        $uh = $conn->prepare('UPDATE board SET hit = hit + 1 WHERE idx = ? AND bid = ?');
        if ($uh) { $uh->bind_param('ii', $idx, $bid); $uh->execute(); $uh->close(); }

        $stmt = $conn->prepare('SELECT idx, bid, title, html, id, name, time, hit FROM board WHERE idx = ? AND bid = ?');
        if (!$stmt) {
            echo '<div class="alert">글을 불러오는 중 오류가 발생했습니다.</div>';
            break;
        }
        $stmt->bind_param('ii', $idx, $bid);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();

        if (!$row) {
            echo '<div class="alert">해당 글이 존재하지 않습니다.</div>';
            break;
        }

        $can_delete = false;
        $session_id    = $_SESSION['sino_id']   ?? '';
        $session_level = (int)($_SESSION['sino_level'] ?? 0);
        if ($session_id !== '' && ($session_id === $row['id'] || $session_level === 9)) {
            $can_delete = true;
        }

        echo '<div class="board show">';
        echo '  <h2>'.h($row['title']).'</h2>';
        echo '  <div class="meta">작성자: '.h($row['name']).' | 작성일: '.h($row['time']).' | 조회수: '.(int)$row['hit'].'</div>';

        // Content rules: convert newlines to <br>, minimum height 300px area
        $content = nl2br(h($row['html']));
        echo '  <div class="content" style="min-height:300px;">'.$content.'</div>';

        echo '  <div class="actions">';
        if ($can_delete) {
            echo '    <form method="post" action="index.php?cmd=board&bid='.h($bid).'&mode=delete" onsubmit="return confirm(\'정말 삭제하시겠습니까?\');" style="display:inline-block;">';
            echo '      <input type="hidden" name="idx" value="'.(int)$row['idx'].'" />';
            echo '      <button type="submit">삭제</button>';
            echo '    </form>';
        }
        echo '    <a class="btn" href="index.php?cmd=board&bid='.h($bid).'&mode=list">목록</a>';
        echo '  </div>';
        echo '</div>';
        break;

    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo '<div class="alert">잘못된 요청입니다.</div>';
            break;
        }
        $idx = (int)($_POST['idx'] ?? 0);
        if ($idx <= 0) { echo '<div class="alert">잘못된 요청입니다.</div>'; break; }

        // Load post to check permissions
        $stmt = $conn->prepare('SELECT id FROM board WHERE idx = ? AND bid = ?');
        if (!$stmt) { echo '<div class="alert">삭제 중 오류가 발생했습니다.</div>'; break; }
        $stmt->bind_param('ii', $idx, $bid);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();

        if (!$row) { echo '<div class="alert">존재하지 않는 글입니다.</div>'; break; }

        $session_id    = $_SESSION['sino_id']   ?? '';
        $session_level = (int)($_SESSION['sino_level'] ?? 0);

        if ($session_id === '' || ($session_id !== $row['id'] && $session_level !== 9)) {
            echo '<div class="alert">삭제 권한이 없습니다.</div>';
            echo '<div><a class="btn" href="index.php?cmd=board&bid='.h($bid).'&mode=list">목록으로</a></div>';
            break;
        }

        $del = $conn->prepare('DELETE FROM board WHERE idx = ? AND bid = ?');
        if ($del) {
            $del->bind_param('ii', $idx, $bid);
            $del->execute();
            $del->close();
            header('Location: index.php?cmd=board&bid='.$bid.'&mode=list');
            exit;
        } else {
            echo '<div class="alert">삭제 처리 중 오류가 발생했습니다.</div>';
        }
        break;

    case 'list':
    default:
        $per_page = 10;
        $offset   = ($page - 1) * $per_page;

        // Count total
        $count = 0;
        $sc = $conn->prepare('SELECT COUNT(*) AS cnt FROM board WHERE bid = ?');
        if ($sc) { $sc->bind_param('i', $bid); $sc->execute(); $r = $sc->get_result()->fetch_assoc(); $count = (int)($r['cnt'] ?? 0); $sc->close(); }

        $stmt = $conn->prepare('SELECT idx, title, time FROM board WHERE bid = ? ORDER BY idx DESC LIMIT ? OFFSET ?');
        if (!$stmt) {
            echo '<div class="alert">목록을 불러오는 중 오류가 발생했습니다.</div>';
            break;
        }
        $stmt->bind_param('iii', $bid, $per_page, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        echo '<div class="board list">';
        echo '  <h2>'.($bid === 1 ? '공지사항' : ($bid === 2 ? '자유게시판' : ('게시판 #'.(int)$bid))).'</h2>';

        echo '  <table class="list-table">';
        echo '    <thead><tr><th style="width:80px;">순서</th><th>제목</th><th style="width:160px;">작성일</th></tr></thead>';
        echo '    <tbody>';

        $seq_start = $count - $offset; // descending sequence number
        $rownum = 0;
        while ($row = $result->fetch_assoc()) {
            $rownum++;
            $seq = $seq_start - ($rownum - 1);
            $link = 'index.php?cmd=board&bid='.h($bid).'&mode=show&idx='.(int)$row['idx'];
            echo '      <tr>';
            echo '        <td class="seq">'.(int)$seq.'</td>';
            echo '        <td class="title"><a href="'.$link.'">'.h($row['title']).'</a></td>';
            echo '        <td class="date">'.h($row['time']).'</td>';
            echo '      </tr>';
        }
        if ($rownum === 0) {
            echo '      <tr><td colspan="3" class="empty">등록된 글이 없습니다.</td></tr>';
        }
        echo '    </tbody>';
        echo '  </table>';

        // Actions: 글쓰기
        echo '  <div class="actions">';
        echo '    <a class="btn primary" href="index.php?cmd=board&bid='.h($bid).'&mode=write">글쓰기</a>';
        echo '  </div>';

        // Pagination
        $total_pages = max(1, (int)ceil($count / $per_page));
        if ($total_pages > 1) {
            echo '<div class="pagination">';
            $base = 'index.php?cmd=board&bid='.h($bid).'&mode=list&page=';
            // simple prev/next + page numbers
            if ($page > 1) echo '<a href="'.$base.($page-1).'">이전</a>';
            for ($p = 1; $p <= $total_pages; $p++) {
                if ($p == $page) {
                    echo '<span class="current">'.$p.'</span>';
                } else {
                    echo '<a href="'.$base.$p.'">'.$p.'</a>';
                }
            }
            if ($page < $total_pages) echo '<a href="'.$base.($page+1).'">다음</a>';
            echo '</div>';
        }

        echo '</div>';

        $stmt->close();
        break;
}

// --- Minimal styles ----------------------------------------------------------
?>
<style>
.board h2 { margin: 0 0 16px; }
.alert { padding: 12px; background: #ffecec; border: 1px solid #f5c2c2; border-radius: 6px; margin: 12px 0; }
.list-table { width: 100%; border-collapse: collapse; }
.list-table th, .list-table td { border-bottom: 1px solid #e5e7eb; padding: 10px; text-align: left; }
.list-table .seq { text-align: center; color: #666; }
.list-table .title a { text-decoration: none; }
.list-table .date { color: #666; font-size: 0.94em; }
.form-row { margin-bottom: 10px; display: flex; flex-direction: column; }
.form-row label { margin-bottom: 6px; font-weight: 600; }
.form-row input[type=text] { padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; }
.form-row textarea { padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; resize: vertical; }
.actions { margin-top: 16px; }
.actions .btn, .actions button { display: inline-block; padding: 8px 14px; margin-right: 6px; border: 1px solid #d1d5db; background: #f9fafb; border-radius: 6px; cursor: pointer; text-decoration: none; }
.actions .btn.primary { background: #2563eb; color: #fff; border-color: #2563eb; }
.board.show .meta { color: #666; margin-bottom: 12px; }
.board.show .content { padding: 12px; background: #fafafa; border: 1px solid #eee; border-radius: 6px; white-space: normal; }
.pagination { margin-top: 16px; }
.pagination a, .pagination span { display: inline-block; padding: 6px 10px; margin-right: 4px; border: 1px solid #d1d5db; border-radius: 6px; text-decoration: none; }
.pagination .current { background: #111827; color: #fff; border-color: #111827; }
</style>
