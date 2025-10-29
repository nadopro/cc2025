<?php
// wysiwyg.php
// 이 파일은 index.php?cmd=wysiwyg 로 include 되어 표시됩니다.
// 저장 처리용 엔드포인트는 예시로 index.php?cmd=wysiwyg_save 로 지정했습니다.
// 실제 저장 로직은 wysiwyg_save.php 에서 구현하세요.
?>

<div class="card shadow-sm">
  <div class="card-header">
    <h5 class="m-0">게시글 작성</h5>
  </div>

  <form id="postForm" action="index.php?cmd=wysiwyg_save" method="post" class="card-body">

    <!-- 1줄: 제목 -->
    <div class="mb-3">
      <label for="title" class="form-label">제목</label>
      <input type="text" id="title" name="title" class="form-control" placeholder="제목을 입력하세요" required>
    </div>

    <!-- 2줄: 툴바 -->
    <div class="mb-2 d-flex gap-2 align-items-center flex-wrap">
      <div class="btn-group btn-group-sm" role="group" aria-label="서식">
        <button type="button" class="btn btn-outline-secondary" data-cmd="bold" title="굵게 (Bold)"><strong>B</strong></button>
        <button type="button" class="btn btn-outline-secondary" data-cmd="underline" title="밑줄 (Underline)"><span style="text-decoration: underline;">U</span></button>
        <button type="button" class="btn btn-outline-secondary" data-cmd="italic" title="기울임 (Italic)"><em>I</em></button>
      </div>

      <div class="btn-group btn-group-sm ms-1" role="group" aria-label="색상">
        <button type="button" class="btn btn-outline-secondary" data-cmd="foreColor" data-value="#d00000" title="텍스트 빨간색">
          <span style="display:inline-block;width:12px;height:12px;background:#d00000;border-radius:2px;vertical-align:middle;margin-right:6px;"></span>빨강
        </button>
        <button type="button" class="btn btn-outline-secondary" data-cmd="foreColor" data-value="#000000" title="텍스트 검정색">
          <span style="display:inline-block;width:12px;height:12px;background:#000;border-radius:2px;vertical-align:middle;margin-right:6px;"></span>검정
        </button>
      </div>
    </div>

    <!-- 3줄: 편집 영역 -->
    <div class="mb-3">
      <label for="editor" class="form-label">내용</label>
      <div id="editor"
           class="form-control"
           contenteditable="true"
           style="min-height: 240px; overflow:auto;"
           aria-label="게시글 내용 편집 영역"></div>
      <!-- 전송용 숨은 필드: HTML -->
      <input type="hidden" name="content_html" id="content_html">
    </div>

    <!-- 4줄: 등록 버튼 -->
    <div class="d-flex justify-content-end">
      <button type="submit" class="btn btn-primary">등록</button>
    </div>
  </form>
</div>

<script>
// 간단한 툴바 동작: document.execCommand 사용 (브라우저 지원 넓음)
(function () {
  const toolbarButtons = document.querySelectorAll('[data-cmd]');
  const editor = document.getElementById('editor');
  const form = document.getElementById('postForm');
  const hidden = document.getElementById('content_html');

  // 편집 영역 포커스 유지
  function focusEditor() {
    if (document.activeElement !== editor) editor.focus();
  }

  toolbarButtons.forEach(btn => {
    btn.addEventListener('click', function () {
      const cmd = this.getAttribute('data-cmd');
      const val = this.getAttribute('data-value') || null;
      focusEditor();
      // 선택 영역에 명령 적용
      document.execCommand(cmd, false, val);
      editor.dispatchEvent(new Event('input'));
    });
  });

  // 제출 시 HTML 동기화
  form.addEventListener('submit', function (e) {
    // 앞/뒤 불필요한 공백 제거한 뒤 빈 내용 방지
    const html = editor.innerHTML.trim();
    if (!html || html === '<br>') {
      alert('내용을 입력해 주세요.');
      e.preventDefault();
      return false;
    }
    hidden.value = html;
  });

  // 엔터/쉬프트+엔터 처리(기본 브라우저 동작 사용)
  editor.addEventListener('keydown', function (e) {
    // 필요 시 커스텀 처리 추가
  });

  // 기본 placeholder 유사 효과
  if (editor.innerHTML.trim() === '') {
    editor.setAttribute('data-placeholder', '내용을 입력하세요');
    editor.classList.add('placeholder');
  }
  editor.addEventListener('input', function () {
    if (editor.textContent.trim().length === 0 && editor.innerHTML.indexOf('<img') === -1) {
      editor.setAttribute('data-placeholder', '내용을 입력하세요');
      editor.classList.add('placeholder');
    } else {
      editor.removeAttribute('data-placeholder');
      editor.classList.remove('placeholder');
    }
  });
})();
</script>

<style>
/* contenteditable placeholder 스타일 */
#editor.placeholder:before {
  content: attr(data-placeholder);
  color: #6c757d;
}
#editor.placeholder:before {
  pointer-events: none;
}
</style>
