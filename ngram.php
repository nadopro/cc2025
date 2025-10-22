<?php
// index.php
// UTF-8 고정
mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');

// 기본값
$minFreq   = isset($_POST['min_freq']) ? max(1, (int)$_POST['min_freq']) : 2;
$maxGram   = isset($_POST['max_gram']) ? max(1, min(10, (int)$_POST['max_gram'])) : 5; // 안전상 한도 10
$rowsLimit = isset($_POST['rows_limit']) ? max(1, (int)$_POST['rows_limit']) : 15;
$rawText   = isset($_POST['text']) ? trim($_POST['text']) : '';

$result = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // [한자 전용 전처리]
    // - 공백/구두점/영어/숫자/특수문자 -> 경계(분리자)
    // - 연속된 한자만 남기는 분리: \p{Han} = CJK Unified Ideographs
    // - 결과: 한자 덩어리(토막) 배열
    $hanChunks = preg_split('/[^\p{Han}]+/u', $rawText, -1, PREG_SPLIT_NO_EMPTY);

    // n-gram 계산: 각 덩어리 내부에서만 계산(경계 넘지 않음)
    $globalCountsByN = array_fill(1, $maxGram, []); // n => [gram => count]

    foreach ($hanChunks as $chunk) {
        // 음절(한 글자) 단위 분해
        $chars = preg_split('//u', $chunk, -1, PREG_SPLIT_NO_EMPTY);
        $len   = count($chars);
        if ($len === 0) continue;

        for ($n = 1; $n <= $maxGram; $n++) {
            if ($len < $n) continue;

            for ($i = 0; $i <= $len - $n; $i++) {
                // n개 이어 붙인 그램 생성
                $gram = '';
                for ($k = 0; $k < $n; $k++) {
                    $gram .= $chars[$i + $k];
                }
                if (!isset($globalCountsByN[$n][$gram])) $globalCountsByN[$n][$gram] = 0;
                $globalCountsByN[$n][$gram]++;
            }
        }
    }

    // n별 결과 필터/정렬/상위 rowsLimit 추출
    for ($n = 1; $n <= $maxGram; $n++) {
        $counts = $globalCountsByN[$n];

        // 최소출현수 필터
        $counts = array_filter($counts, function ($c) use ($minFreq) { return $c >= $minFreq; });

        // 빈도 내림차순, 동률이면 사전순
        uksort($counts, function ($a, $b) use ($counts) {
            if ($counts[$a] === $counts[$b]) return strcmp($a, $b);
            return ($counts[$a] > $counts[$b]) ? -1 : 1;
        });

        // 상위 rowsLimit
        $top = array_slice($counts, 0, $rowsLimit, true);

        // "문자열 빈도" 표시
        $display = [];
        foreach ($top as $g => $c) $display[] = $g . ' ' . $c;

        $result[$n] = $display;
    }
}
?>

<!-- Bootstrap 5 기본 스타일 (원하시면 기존 페이지에 포함된 링크 사용) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  textarea { min-height: 320px; }
  .btn-analyze { height: 100%; width: 100%; font-weight: 600; }
  .table thead th { white-space: nowrap; }
</style>

<div class="container py-4">
  <h1 class="h4 mb-3">엔그램(한자 음절) 분석</h1>

  <form method="post" class="row g-3">
    <div class="col-12 col-lg-9">
      <label for="text" class="form-label">텍스트를 입력하는 곳</label>
      <textarea class="form-control" id="text" name="text"
        placeholder="원문 텍스트 입력하는 곳"><?= htmlspecialchars($rawText ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></textarea>
    </div>

    <div class="col-12 col-lg-3 d-grid">
      <label class="form-label invisible d-none d-lg-block">분석</label>
      <button type="submit" class="btn btn-primary btn-analyze">분석</button>
    </div>

    <div class="col-12 col-md-4">
      <label for="min_freq" class="form-label">최소출현</label>
      <input type="number" class="form-control" id="min_freq" name="min_freq" min="1" value="<?= (int)$minFreq ?>">
      <div class="form-text">엔그램 최소 출현 횟수</div>
    </div>

    <div class="col-12 col-md-4">
      <label for="max_gram" class="form-label">최대음절</label>
      <input type="number" class="form-control" id="max_gram" name="max_gram" min="1" max="10" value="<?= (int)$maxGram ?>">
      <div class="form-text">예: 3 이면 1~3그램 계산</div>
    </div>

    <div class="col-12 col-md-4">
      <label for="rows_limit" class="form-label">표라인수</label>
      <input type="number" class="form-control" id="rows_limit" name="rows_limit" min="1" value="<?= (int)$rowsLimit ?>">
      <div class="form-text">표에 표시할 상위 라인 수</div>
    </div>
  </form>

  <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <hr class="my-4">
    <?php
      // 출력 행수 결정(각 n의 최대와 rowsLimit 중 큰 값)
      $maxRows = 0;
      for ($n = 1; $n <= $maxGram; $n++) { $maxRows = max($maxRows, count($result[$n] ?? [])); }
      $maxRows = max($maxRows, $rowsLimit);
    ?>
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-secondary">
          <tr>
            <th class="text-center" style="width:60px">#</th>
            <?php for ($n = 1; $n <= $maxGram; $n++): ?>
              <th class="text-center"><?= $n ?>음절</th>
            <?php endfor; ?>
          </tr>
        </thead>
        <tbody>
        <?php for ($r = 0; $r < $maxRows; $r++): ?>
          <tr>
            <td class="text-center"><?= $r + 1 ?></td>
            <?php for ($n = 1; $n <= $maxGram; $n++): ?>
              <td><?= htmlspecialchars($result[$n][$r] ?? '-', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
            <?php endfor; ?>
          </tr>
        <?php endfor; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
