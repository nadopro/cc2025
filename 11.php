<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>숫자 입력 예제</title>
</head>
<body>
  <h1>숫자 입력 확인</h1>
  <input type="text" id="numInput" placeholder="숫자를 입력하세요">
  <button onclick="checkInput()">확인</button>

  <script>
    function checkInput(){
      let value = document.getElementById("numInput").value;

      // isNaN() : 숫자가 아닌지 검사
      if (value.trim() === "" || isNaN(value)) {
        confirm("숫자를 입력하세요");
      } else {
        alert("입력한 값은: " + value);
      }
    }
  </script>
</body>
</html>
