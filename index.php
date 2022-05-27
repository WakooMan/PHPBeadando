<?php
session_start();
include('storages.php');
$users = new UsersStorage();
$series = new SeriesStorage();
$user = (isset($_SESSION['felhasznalo']))?$users->findById($_SESSION['felhasznalo']):NULL;
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
    <?php if($user === NULL) : ?>
        <a href="bejelentkezes.php">Bejelentkezés</a>
    <?php else : ?>
        <a href="kijelentkezes.php">Kijelentkezés</a>
    <?php endif ?>
    <?php if($user !== NULL) : ?>
    <h2>Elkezdett Sorozatok</h2>
        <table>
            <tr>
                <th>Cím</th><th>Epizódok száma</th><th>Utolsó rész megjelenésének dátuma</th>
            </tr>
            <?php foreach(array_filter($series -> findAll(),function($elem) use($user) {return $user['watched'][$elem['id']]>0;}) as $ser) : ?>
                <tr>
                <td><?=$ser['title']?></td> <td><?=count($ser['episodes'])?></td> <td><?=end($ser['episodes'])['date']?></td> <td><a href="reszletek.php?id=<?=$ser['id']?>">Részletek</a></td> <?php if($isAdmin) : ?><td><a href="modifySeries.php?id=<?=$ser['id']?>">Módosítás</a></td> <td><a href="deleteSeries.php?id=<?=$ser['id']?>">Törlés</a></td><?php endif ?>
                </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>
    <h2><?=($user !== NULL)?'Nem Elkezdett ':''?>Sorozatok</h2>
    <table>
        <tr>
            <th>Cím</th><th>Epizódok száma</th><th>Utolsó rész megjelenésének dátuma</th>
        </tr>
        <?php $outseries = ($user !== NULL)?array_filter($series -> findAll(),function($elem) use($user) {return $user['watched'][$elem['id']]==0;}):$series -> findAll();
        foreach(array_slice($outseries,-5) as $ser) : ?>
            <tr>
            <td><?=$ser['title']?></td> <td><?=count($ser['episodes'])?></td> <td><?=end($ser['episodes'])['date']?></td> <td><a href="reszletek.php?id=<?=$ser['id']?>">Részletek</a></td> <?php if($isAdmin) : ?><td><a href="modifySeries.php?id=<?=$ser['id']?>">Módosítás</a></td> <td><a href="deleteSeries.php?id=<?=$ser['id']?>">Törlés</a></td><?php endif ?>
            </tr>
        <?php endforeach ?>
    </table>
    <?php if($isAdmin) : ?>
        <a href="addSeries.php">Sorozat hozzáadása</a>
    <?php endif ?>
</body>
</html>