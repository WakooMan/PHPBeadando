<?php
session_start();
include('storages.php');
$series = new SeriesStorage();
$users = new UsersStorage();
$user = (isset($_SESSION['felhasznalo']))?$users->findById($_SESSION['felhasznalo']):NULL;
print_r($user);
if(!isset($_GET['id']) || $series->findById($_GET['id']) === NULL)
{
    header('Location: index.php');
    exit();
}
$ser = $series->findById($_GET['id']);
if(isset($_POST['inc']))
{
    $user['watched'][$ser['id']]++;
    $users->update($user['id'],$user);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sorozatcím</title>
    <style>
        .error{color:red;}
        .success{color:green;}
    </style>
</head>
<body>
    <h1>Sorozatcím</h1>
    <table>
        <tr>
            <th>Cím</th><td><?=$ser['title']?></td>
        </tr>
        <tr>
            <th>Megjelenés éve</th><td><?=$ser['year']?></td>
        </tr>
        <tr>
            <th>Epizódok száma</th><td><?=count($ser['episodes'])?></td>
        </tr>
        <tr>
        <tr>
            <th>Borítókép</th><td><img src="<?=$ser['cover']?>" alt="boritokep"></td>
        </tr>
        </tr>
            <th>Leírás</th><td><?=$ser['plot']?></td>
    </table>
    <h1>Epizódok</h1>
    <table>
        <tr>
            <th>Epizód címe</th><th>Megjelenés Dátuma</th><th>Leírás</th><th>Értékelés</th><?php if($user !== NULL) : ?><th>Megtekintett</th><?php endif ?>
        </tr>
        <?php $i=0; foreach($ser['episodes'] as $episode) : ?>
            <tr>
                <td><?=$episode['title']?></td><td><?=$episode['date']?></td><td><?=$episode['plot']?></td><td><?=$episode['rating']?></td>
                <?php if($user !== NULL) : ?>
                    <td>
                        <?php if($i === $user['watched'][$ser['id']]) : ?>
                            <form action="" method="POST"> <button type="submit" name="inc" value="novel">Megnéz</button></form>
                        <?php elseif($i < $user['watched'][$ser['id']]) : ?>
                            <div class="success">Megtekintett</div>
                        <?php else : ?>
                            <div class="error">Nem Megtekintett</div>
                        <?php endif ?>
                    </td>
                <?php endif ?>
            </tr>
        <?php $i++; endforeach ?>
    </table>
    <a href="index.php">Vissza</a>
</body>
</html>