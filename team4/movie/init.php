<?php
// 세션 시작
session_start();

// DB 연결
$host = 'localhost';
$db   = 'team4';
$user = 'team4';
$pass = '1111'; // MySQL 비밀번호
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    echo "DB 연결 실패: " . $e->getMessage();
    exit;
}

// 홈 화면 영화 정보
$home_movie = [
    'title' => '위대한 개츠비 (The Great Gatsby)',
    'subtitle' => '사랑, 꿈, 그리고 잃어버린 환상의 시대를 그린 걸작',
    'image' => 'imgmovie/gatsby.png',
    'stats' => [
        'Rotten Tomatoes' => '평점: 87%, 관객 점수: 75%'
    ],
    'director' => '바즈 루어만 (Baz Luhrmann)',
    'cast' => [
        '레오나르도 디카프리오' => '제이 개츠비',
        '토비 맥과이어' => '닉 캐러웨이',
        '캐리 멀리건' => '데이지 뷰캐넌',
        '조엘 에저튼' => '톰 뷰캐넌'
    ]
];
