<?php
// 고정: 2025년 9월
$year  = 2025;
$month = 9;

// 해당 달 정보
$firstDayTs   = strtotime(sprintf('%04d-%02d-01', $year, $month));
$firstWeekday = (int)date('w', $firstDayTs); // 0:일 ~ 6:토
$daysInMonth  = (int)date('t', $firstDayTs);

// 달력 채우기용 포인터
$day = 1;

// 주(행) 생성 함수
function renderWeekRow(&$day, $firstWeekday, $daysInMonth, $isFirstRow = false) {
    echo "<tr>\n";
    for ($w = 0; $w < 7; $w++) {
        $cls = ($w === 0) ? 'text-danger' : (($w === 6) ? 'text-primary' : '');
        $openTd = $cls ? "<td class=\"$cls\">" : "<td>";

        if ($isFirstRow && $w < $firstWeekday) {
            echo $openTd . "</td>\n"; // 첫 주 앞의 빈칸
        } elseif ($day > $daysInMonth) {
            echo $openTd . "</td>\n"; // 마지막 주 뒤의 빈칸
        } else {
            echo $openTd . $day . "</td>\n";
            $day++;
        }
    }
    echo "</tr>\n";
}
?>
<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $year; ?>년 <?php echo $month; ?>월 달력</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h1 class="mb-4"><?php echo $year; ?>년 <?php echo $month; ?>월 달력</h1>
    <table class="table table-bordered text-center">
      <thead class="table-light">
        <tr>
          <th class="text-danger">일</th>
          <th>월</th>
          <th>화</th>
          <th>수</th>
          <th>목</th>
          <th>금</th>
          <th class="text-primary">토</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // 1주차 (첫 날의 요일 보정 포함)
        renderWeekRow($day, $firstWeekday, $daysInMonth, true);

        // 이후 주차: 남은 날짜가 있을 때까지 행 생성
        // 2025-09는 총 5주가 필요하므로 while로 유연하게 처리
        while ($day <= $daysInMonth) {
            renderWeekRow($day, 0, $daysInMonth, false);
        }
        ?>
      </tbody>
    </table>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
