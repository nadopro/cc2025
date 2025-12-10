<?php
require __DIR__ . "/init.php";
require __DIR__ . "/head.php";
require __DIR__ . "/menu.php";
?>

<div id="main-content" class="container content">
<?php
$page = isset($_GET['page']) ? basename($_GET['page']) : 'home';

switch($page){
    case 'home':
?>
<div class="hero">
    <div class="image-wrapper">
        <img src="<?php echo $home_movie['image']; ?>" alt="<?php echo htmlspecialchars($home_movie['title']); ?>" class="hero-image">
    </div>
    <div class="hero-text">
        <h1><?php echo htmlspecialchars($home_movie['title']); ?></h1>
        <p><?php echo htmlspecialchars($home_movie['subtitle']); ?></p>
    </div>
</div>

<div class="movie-info">
    <div class="stats">
        <h2>Rotten Tomatoes</h2>
        <p><?php echo htmlspecialchars($home_movie['stats']['Rotten Tomatoes']); ?></p>
    </div>
    <div class="director">
        <h2>감독</h2>
        <p><?php echo htmlspecialchars($home_movie['director']); ?></p>
    </div>
    <div class="cast">
        <h2>주요 배우</h2>
        <ul>
            <?php foreach($home_movie['cast'] as $actor => $role): ?>
                <li><?php echo htmlspecialchars($actor) . " - " . htmlspecialchars($role); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php
    break;
    case 'introduce':
    case 'scene':
    case 'difference':
        $file = __DIR__ . "/{$page}.php";
        if(file_exists($file)) include $file;
        else include __DIR__ . "/notfound.php";
        break;
    default:
        include __DIR__ . "/notfound.php";
        break;
}
?>
</div>

<?php require __DIR__ . "/tail.php"; ?>
