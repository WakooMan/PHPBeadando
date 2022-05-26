<?php
session_start();
include('storages.php');
$users = new UsersStorage();
$series = new SeriesStorage();
$isAdmin = false;
if(isset($_SESSION['felhasznalo']) && $users->findById($_SESSION['felhasznalo']['id'])['isadmin'])
{
    $isAdmin = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Főoldal</title>
</head>
<body>
    <h1>Sorozatfigyelő</h1>
    <h2>Rövid ismertetés</h2>
    <p></p>
    <?php if(!isset($_SESSION['felhasznalo'])) : ?>
        <a href="bejelentkezes.php">Bejelentkezés</a>
    <?php else : ?>
        <a href="kijelentkezes.php">Kijelentkezés</a>
    <?php endif ?>
    <h2>Sorozatok:</h2>
    <ul>
        <?php foreach($series -> findAll() as $ser) : ?>
            <li><?=$ser['title']?> <a href="reszletek.php?id=<?=$ser['id']?>">Részletek</a> <?php if($isAdmin) : ?><a href="modifySeries.php?id=<?=$ser['id']?>">Módosítás</a> <a href="deleteSeries.php?id=<?=$ser['id']?>">Törlés</a><?php endif ?></li>
        <?php endforeach ?>
    </ul>
    <?php if($isAdmin) : ?>
        <a href="addSeries.php">Sorozat hozzáadása</a>
    <?php endif ?>
</body>
</html>