<?php
require __DIR__ . "/init.php";
require __DIR__ . "/menu.php";
?>

<main class="container" style="padding: 20px 0;">

  <!-- ======================= -->
  <!--   영상 (이 부분은 그대로) -->
  <!-- ======================= -->
  <video controls width="100%" style="border-radius:12px; margin-bottom:20px;">
    <source src="imgmovie/first scene.mp4" type="video/mp4">
    브라우저가 동영상을 지원하지 않습니다.
  </video>

  <!-- ======================= -->
  <!--   아래 내용 : 좌측(포스터+줄거리+평점), 우측(감독/등장인물) -->
  <!-- ======================= -->
  <div style="display: grid; grid-template-columns: minmax(0,1fr) 280px; gap: 24px; align-items: flex-start;">

    <!-- ======================= -->
    <!-- LEFT AREA               -->
    <!-- ======================= -->
    <div>

      <!-- 1) 포스터 + 줄거리 카드 -->
      <div style="
            background:#101114;
            border:1px solid #262a33;
            border-radius:14px;
            padding:18px;
          ">
        <div style="display: grid; grid-template-columns: 130px minmax(0,1fr); gap: 16px; align-items:flex-start;">

          <!-- POSTER -->
          <div>
            <img src="imgmovie/gatsby poster.jpg"
                 alt="Gatsby Poster"
                 style="width:100%; border-radius:10px; display:block;">
          </div>

          <!-- STORY -->
          <div style="
    line-height:1.55; 
    font-size:15px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    height:100%;
">

            <p>
              
            </p>

            <p style="margin-top:10px;">
              미드웨스트 출신 닉 캐러웨이(토비 맥과이어)는 아메리칸 드림을 찾아 1922년 뉴욕에 도착한다.
              작가 지망생인 닉은 백만장자 제이 개츠비(레오나르도 디카프리오)의 옆집으로 이사하고,
              그의 사촌 데이지(캐리 멀리건)와 바람둥이 남편 톰(조엘 에저턴)이 사는 만 건너편에 자리 잡는다.
              그렇게 닉은 부유층의 매혹적인 세계에 빠져들게 되고, 그들의 환상과 속임수를 목격하며
              불가능한 사랑과 꿈, 비극에 관한 이야기를 써내려간다.
            </p>
          </div>

        </div>
      </div>

      <!-- 2) 영화 평점 요약 카드 (줄거리 카드 바로 아래, 같은 폭) -->
      <div style="
            margin-top:16px;
            background:#101114;
            border:1px solid #262a33;
            border-radius:14px;
            padding:14px 18px;
          ">
        <h3 style="margin:0 0 15px 0; font-size:16px;">영화 평점 요약</h3>

        <ul style="list-style:none; padding:0; margin:0; line-height:1.6; font-size:14px;">
          <li>
            <strong style="color:#ff4f4f;">Rotten Tomatoes</strong>
            — Tomatometer 48%, Popcornmeter 67%
          </li>
          <li>
            <strong style="color:#f3b700;">IMDb</strong>
            — ★ 7.2 / 10
          </li>
          <li>
            <strong style="color:#43a047;">Metacritic</strong>
            — 55 / 100
          </li>
          <li>
            <strong style="color:#90caf9;">Letterboxd</strong>
            — 3.4 / 5
          </li>
        </ul>
      </div>

    </div>

    <!-- ======================= -->
    <!-- RIGHT AREA : 감독/배우   -->
    <!-- ======================= -->
    <aside style="
        background:#101114;
        border:1px solid #262a33;
        border-radius:14px;
        padding:16px;
        height:fit-content;
      ">

      <h3 style="margin-top:0; margin-bottom:10px;">감독 및 등장인물</h3>

      <!-- 감독 -->
      <div style="margin-bottom:14px;">
        <strong>감독</strong>
        <div style="margin-top:6px;">
          <span style="
            display:inline-block;
            background:#14161a;
            border:1px solid #262a33;
            padding:6px 10px;
            border-radius:999px;
            font-size:14px;
          ">Baz Luhrmann</span>
        </div>
      </div>

      <!-- 등장인물 -->
      <div>
        <strong>주요 등장인물</strong>
        <div style="margin-top:6px; display:flex; flex-wrap:wrap; gap:6px;">
          <span class="chip">Jay Gatsby — Leonardo DiCaprio</span>
          <span class="chip">Nick Carraway — Tobey Maguire</span>
          <span class="chip">Daisy Buchanan — Carey Mulligan</span>
          <span class="chip">Tom Buchanan — Joel Edgerton</span>
          <span class="chip">Jordan Baker — Elizabeth Debicki</span>
          <span class="chip">Myrtle Wilson — Isla Fisher</span>
          <span class="chip">George Wilson — Jason Clarke</span>
        </div>
      </div>

    </aside>

  </div>

</main>

<?php require __DIR__ . "/tail.php"; ?>
