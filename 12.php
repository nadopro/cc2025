<?php
// db.php 파일 포함
include 'db.php';

// DB 연결 시도
$conn = connectDB();

// 쿼리 예제: first 테이블의 최근 10개 데이터 가져오기
$sql = "SELECT id, user_id, name FROM first ORDER BY id DESC LIMIT 10";
$result = mysqli_query($conn, $sql);
?>
<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>DB 연결 테스트</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container py-4">
    <h1 class="mb-4">DB 연결 테스트</h1>

    <?php if ($result): ?>
      <div class="alert alert-success">DB 연결 및 쿼리 실행 성공</div>
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>id</th>
              <th>user_id</th>
              <th>name</th>
            </tr>
          </thead>
          <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['id']); ?></td>
                  <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                  <td><?php echo htmlspecialchars($row['name']); ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="3" class="text-center">데이터가 없습니다.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-danger">
        쿼리 실행 실패: <?php echo mysqli_error($conn); ?>
      </div>
    <?php endif; ?>

  </div>

  <!-- Bootstrap5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// DB 연결 종료
mysqli_close($conn);
?>
