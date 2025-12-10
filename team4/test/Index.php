<?php
require __DIR__ . "/init.php";
require __DIR__ . "/head.php";
?>

<div id="main-content" class="container content">
<?php
$page = isset($_GET['page']) ? basename($_GET['page']) : 'home';

switch ($page) {
    case 'home':
?>
<div class="hero">
    <div class="image-wrapper">
        <img src="<?= $home_movie['image'] ?>" alt="<?= $home_movie['title'] ?>" class="hero-image">
    </div>
    <div class="hero-text">
        <h1><?= $home_movie['title'] ?></h1>
        <p><?= $home_movie['subtitle'] ?></p>
    </div>
</div>

<div class="movie-details">
    <h2>🎬 감독</h2>
    <p><?= $home_movie['director'] ?></p>

    <h2>🍅 로튼 토마토 통계</h2>
    <ul>
        <?php foreach ($home_movie['stats'] as $label => $stat): ?>
        <li><?= $label ?> — <?= $stat ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>🧑‍🔧 출연 & 배역</h2>
    <ul>
        <?php foreach ($home_movie['cast'] as $actor => $role): ?>
        <li><?= $actor ?> — <?= $role ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php
        break;

    case 'introduce':
    case 'scene':
    case 'difference':
    case 'board':
        $file = __DIR__ . "/{$page}.php";
        (file_exists($file)) ? include $file : include __DIR__ . "/notfound.php";
        break;

    default:
        include __DIR__ . "/notfound.php";
        break;
}
?>
</div>

<?php require __DIR__ . "/tail.php"; ?>
