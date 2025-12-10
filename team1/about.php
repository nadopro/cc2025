<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>프로젝트 개요와 핵심 내용</title>

    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f1ede6; /* 연한 베이지 */
            font-family: 'Noto Sans KR', sans-serif;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .title {
            text-align: center;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #333;
        }

        .accordion .item {
            background: #ffffff;
            margin-bottom: 15px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.07);
            overflow: hidden;
        }

        .accordion-btn {
            width: 100%;
            padding: 18px 20px;
            font-size: 17px;
            font-weight: 600;
            border: none;
            outline: none;
            background: #ffffff;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* 오른쪽 화살표 */
        .accordion-btn::after {
            content: "▼";
            font-size: 18px;
            transition: 0.3s;
        }

        .accordion-btn.active::after {
            transform: rotate(180deg);
        }

        /* 내용 영역 */
        .content {
            max-height: 0;
            overflow: hidden;
            padding: 0 20px;
            transition: max-height 0.3s ease;
            background: white;
        }

        .content p {
            margin: 15px 0 18px;
            font-size: 15px;
            line-height: 1.6;
        }
    </style>
</head>

<body>

<div class="container">
    <h2 class="title">프로젝트 개요와 핵심 내용</h2>

    <div class="accordion">

        <div class="item">
            <button class="accordion-btn">1. 홈페이지 소개</button>
            <div class="content"><p>오늘날 사람들은 진로, 인간관계, 건강 등 다양한 고민과 스트레스를 안고 살아갑니다. 그럴 때 오랜 역사를 가진 고전의 가르침과 지혜는 현대인들에게 방향을 잡아주는 지표가 될 수 있습니다.
         저희는 그 중에서도 공자의 말씀이 담긴 논어를 기반으로 합니다. 논어에는 우리가 겪는 모든 인간적인 문제, 예를 들어, 갈등, 배움, 관계, 삶의 태도 등에 대한 깊은 통찰이 담겨 있습니다. 논어를 단순히 어렵고 공부해야 할 학문으로 국한하지 않고, 논어 속 공자의 지혜를 현대적 고민과 연결하여, 누구나 위로와 성찰을 얻을 수 있는 디지털 인문학 상담 웹사이트 제작을 계획하고 있습니다.
         '고전의 지혜로 현대의 고민을 비추다.’라는 슬로건을 가지고, 논어 상담소 페이지를 운영하는 것이 전체 프로젝트의 방향입니다.
</p></div>
        </div>

        <div class="item">
            <button class="accordion-btn">2. '논어 상담소'의 목표</button>
            <div class="content"><p>
1.누구나 쉽게 논어를 접할 수 있는 공간<br>
고전 지식이 없는 사람도 부담 없이 들어와 필요한 구절을 찾을 수 있도록 단순한 구조를 지향합니다.<br><br>

2.상황별로 바로 적용할 수 있는 지혜 제공<br>
단순히 원문을 보여주는 것을 넘어, 사용자가 처한 상황이나 관심사에 맞는 구절을 제안하고 쉬운 해설을 제공합니다.<br><br>

3.지속적인 학습을 돕는 플랫폼<br>
‘오늘의 논어’ 기능 등을 통해 매일 작은 공부를 이어갈 수 있도록 돕습니다.<br><br>
</p></div>
        </div>

        <div class="item">
            <button class="accordion-btn">3. 핵심 기능</button>
            <div class="content"><p>이 프로젝트의 주요 기능은 다섯 가지입니다.<br>

첫 번째, 고민 선택형 메뉴입니다. 현대인들의 가장 주된 고민들이라 할 수 있는 다섯 가지 카테고리, 진로, 인간관계, 마음건강, 자기계발, 사회 및 현실 고민으로 구성했습니다. 사용자는 자신의 현재 고민과 관련된 키워드를 클릭할 수 있습니다.<br><br>

두 번째는 세부 선택 탭 제시입니다. 사용자가 다섯 가지 고민 중 한 가지를 선택하면, 그 안에 더 세부적인 고민을 나눠서 선택할 수 있습니다. 예를 들어 ‘인간관계’를 선택했다면, ‘가족’, ‘친구’, ‘회사’ 등의 키워드가 제시됩니다.<br><br>

세 번째는 논어 구절 제시입니다. 사용자가 세부 선택까지 마치면, 선택된 고민 주제에 맞는 논어의 구절을 띄워주고, 원문과 해석, 짧은 코멘트를 함께 제공합니다.<br><br>

네 번째는 공유 및 저장 기능입니다. 사용자는 마음에 드는 구절을 저장하고 관리하며, 다른 사람과 공유할 수 있습니다.<br><br>

다섯 번째는 ‘오늘의 논어 한마디’ 메뉴입니다. 하루의 시작이나 마무리에 부담 없이 읽을 수 있는 문장을 제공하여, 짧게라도 사유와 성찰의 시간을 가지도록 돕습니다.
</p></div>
        </div>

    

    </div>
</div>

<script>
    const buttons = document.querySelectorAll('.accordion-btn');

    buttons.forEach(btn => {
        btn.addEventListener('click', function () {
            const content = this.nextElementSibling;

            // 다른 항목 닫기
            document.querySelectorAll('.content').forEach(c => {
                if (c !== content) {
                    c.style.maxHeight = null;
                    c.previousElementSibling.classList.remove('active');
                }
            });

            // 현재 항목 토글
            if (content.style.maxHeight) {
                content.style.maxHeight = null;
                this.classList.remove('active');
            } else {
                content.style.maxHeight = content.scrollHeight + "px";
                this.classList.add('active');
            }
        });
    });
</script>

</body>
</html>