<?php
header("Content-Type: application/json; charset=UTF-8");

$quotes = [
    [
        "text" => "三人行, 必有我師焉.",
        "mean" => "세 사람이 길을 가면 그 가운데 반드시 나의 스승이 있다.",
        "comment" => "주변 사람에게서 배우려는 자세를 가지면 인간관계가 한층 편안해집니다."
    ],
    [
        "text" => "學而時習之, 不亦說乎?",
        "mean" => "배우고 때로 익히면 또한 기쁘지 아니한가?",
        "comment" => "오늘도 작은 복습 하나가 큰 차이를 만듭니다."
    ],
    [
        "text" => "知之者不如好之者, 好之者不如樂之者.",
        "mean" => "아는 사람은 좋아하는 사람만 못하고, 좋아하는 사람은 즐기는 사람만 못하다.",
        "comment" => "즐기면 꾸준할 수 있고, 꾸준하면 실력이 됩니다."
    ],
    [
        "text" => "君子和而不同 小人同而不和",
        "mean" => "군자는 조화를 이루되 같아지려 하지 않고, 소인은 같아지려 하나 조화롭지 못하다.",
        "comment" => "다름을 인정하는 것이 조화의 시작입니다."
    ]
];

echo json_encode($quotes[array_rand($quotes)], JSON_UNESCAPED_UNICODE);
