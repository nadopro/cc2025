


    $catList[1] = "진로,학업";
    $catList[2] = "인간관계";
    $catList[3] = "마음건강";
    $catList[4] = "자기계발";
    $catList[5] = "사회,현실";

이렇게 대분류가 설계되어 있어.
각각의 대분류에는 소분류가 다음과 같이 설계되어 있어.
두 자리 수의 앞자리는 대분류를 의미해.

    $catList[11] = "진로고민";
    $catList[12] = "시간관리";
    $catList[13] = "실패극복";

    $catList[21] = "가족";
    $catList[22] = "친구";
    $catList[23] = "회사";

    $catList[31] = "자존감";
    $catList[32] = "불안";
    $catList[33] = "회복과 용기";

    $catList[41] = "자기성찰";
    $catList[42] = "도전";
    $catList[43] = "열정,목표";

    $catList[51] = "조직갈등";
    $catList[52] = "돈,물질";
    $catList[53] = "정의";


이것을 이용해 데이터베이스테이블을 하나 만들고 싶어.

table name : sentence
idx : int, auto_increment, primary key
cat : 앞의 소분류 번호
org : 한문 원문과 해석이 된 텍스트
memo : 관리자가 등록할 부연설명



CREATE TABLE `sentence` (
    `idx` INT AUTO_INCREMENT PRIMARY KEY,
    `cat` TINYINT NOT NULL COMMENT '소분류 번호 (예: 11, 12, 21...)',
    `org` TEXT NOT NULL COMMENT '한문 원문과 해석 텍스트',
    `memo` TEXT NULL COMMENT '관리자 부연설명',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

이 테이블에 데이터를 넣는 코드를 manData.php 파일에 만들려고 해.
그런데 모든 접속은 index.php?cmd=manData와 같이 접속해서
이미 index.php에서 접속은 다 처리되어서
$conn = connectDB();처럼 데이터베이스 접속까지 끝났어.
bootstrap5를 이용해 manData.php 파일을 만들려고 하는데,
이 데이터베이스 테이블에 넣고 싶어.
단, cat는 앞에서 제시한 catList가 selectbox로 선택되도록 하고 싶어.
