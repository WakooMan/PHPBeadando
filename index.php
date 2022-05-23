<?php
include('seriesStorage.php');
include('usersStorage.php');
$series = new SeriesStorage();
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
    <a href="bejelentkezes.php">Bejelentkezés</a>
    <h2>Sorozatok:</h2>
    <ul>
        <?php foreach($series as $ser) : ?>
            <li><?=$ser['title']?> <a href="reszletek.php?id=<?=$ser['id']?>">Részletek</a></li>
        <?php endforeach ?>
    </ul>
</body>
</html>