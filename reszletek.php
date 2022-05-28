<?php
session_start();
include('storages.php');
$series = new SeriesStorage();
$users = new UsersStorage();
$user = (isset($_SESSION['felhasznalo']))?$users->findById($_SESSION['felhasznalo']):NULL;
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
            <a class="nav-link active" href="index.php">Főoldal</a>
            </li>
        </ul>
                </div>
            </div>
        </nav>
    <div class="text-center">
    <h1 class="display-1"><?=$ser['title']?></h1>
    <table class="table table-striped">
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
            <th>Borítókép</th><td><img height="100vw"src="<?=$ser['cover']?>" alt="boritokep"></td>
        </tr>
        </tr>
            <th>Leírás</th><td><?=$ser['plot']?></td>
    </table>
    <h1>Epizódok</h1>
    <table class="table table-striped">
        <tr>
            <th>Epizód címe</th><th>Megjelenés Dátuma</th><th>Leírás</th><th>Értékelés</th><?php if($user !== NULL) : ?><th>Megtekintett</th><?php endif ?>
        </tr>
        <?php $i=0; foreach($ser['episodes'] as $episode) : ?>
            <tr>
                <td><?=$episode['title']?></td><td><?=$episode['date']?></td><td><?=$episode['plot']?></td><td><?=$episode['rating']?></td>
                <?php if($user !== NULL) : ?>
                    <td>
                        <?php if($i === $user['watched'][$ser['id']]) : ?>
                            <form action="" method="POST"> <button type="submit" class="btn btn-secondary" name="inc" value="novel">Megnéz</button></form>
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
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>
</html>