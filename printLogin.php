<div class="container mt-5" style="max-width: 400px;">
  <h4 class="text-center mb-4">로그인</h4>
  
  <form action="index.php?cmd=login" method="post">
    <div class="mb-3">
      <label for="id" class="form-label">아이디</label>
      <input type="text" class="form-control" id="id" name="id" placeholder="아이디를 입력하세요" required>
    </div>
    <div class="mb-3">
      <label for="pass" class="form-label">비밀번호</label>
      <input type="password" class="form-control" id="pass" name="pass" placeholder="비밀번호를 입력하세요" required>
    </div>
    <div class="d-grid">
      <button type="submit" class="btn btn-primary">로그인</button>
    </div>
  </form>
</div>
