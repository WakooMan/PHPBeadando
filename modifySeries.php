<?php
session_start();
include('storages.php');
include('validateSeries.php');
$users = new UsersStorage();
$series = new SeriesStorage();
if(!isset($_SESSION['felhasznalo']) || !($users->findById($_SESSION['felhasznalo']['id'])['isadmin']) || !isset($_GET['id']) || $series->findById($_GET['id']) === NULL)
{
    header('Location: index.php');
    exit();
}
if(!isset($_SESSION['modositottsorozat']))
{
    $_SESSION['modositottsorozat'] =  $series->findById($_GET['id']);
}
if(count($_POST)>0)
{
    $data = [];
    $errors = [];
    if(validate($_POST,$data,$errors,$series) || $_POST['leadas'] === 'cancel')
    {
        if($_POST['leadas'] === 'add')
        {
            $_SESSION['modositottsorozat']['title'] = $data['title'];
            $_SESSION['modositottsorozat']['year'] = $data['year'];
            $_SESSION['modositottsorozat']['plot'] = $data['plot'];
            $_SESSION['modositottsorozat']['cover'] = $data['cover'];
            $series -> update($_SESSION['modositottsorozat']['id'],$_SESSION['modositottsorozat']);
        }
        $_SESSION['modositottsorozat'] = NULL;
        header('Location: index.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sorozat Módosítása</title>
    <style>
        .error
        {
            color: red;
        }
        .success
        {
            color:green;
        }
    </style>
</head>
<body>
<h1>Sorozat Hozzáadása</h1>
    <h2>Sorozat adatai</h2>
    <form action="" method="POST" novalidate>
        <label for="cim">Cím</label> <input type="text" name="title" id="cim" value="<?=(isset($_POST['title']))?$_POST['title']:$_SESSION['modositottsorozat']['title']?>"> 
        <?php if(isset($errors['title'])) : ?>
            <span class="error"><?=$errors['title']?></span>
        <?php endif ?>
        <br>
        <label for="evjarat">Megjelenés dátuma</label> <input type="text" name="year" id="evjarat" value="<?=(isset($_POST['year']))?$_POST['year']:$_SESSION['modositottsorozat']['year']?>"> 
        <?php if(isset($errors['year'])) : ?>
            <span class="error"><?=$errors['year']?></span>
        <?php endif ?>
        <br>
        <label for="leiras">Leírás</label> <input type="text" name="plot" id="leiras" value="<?=(isset($_POST['plot']))?$_POST['plot']:$_SESSION['modositottsorozat']['plot']?>"> 
        <?php if(isset($errors['plot'])) : ?>
            <span class="error"><?=$errors['plot']?></span>
        <?php endif ?>
        <br>
        <label for="borito">Borító</label> <input type="text" name="cover" id="borito" value="<?=(isset($_POST['cover']))?$_POST['cover']:$_SESSION['modositottsorozat']['cover']?>"> 
        <?php if(isset($errors['cover'])) : ?>
            <span class="error"><?=$errors['cover']?></span>
        <?php endif ?>
        <br>
        <button type="submit" name ="leadas" value="add">Hozzáad</button> <button type="submit" name ="leadas" value="cancel">Mégse</button>
    </form>
    <h2>Sorozat Epizódjai</h2>
    <table>
        <tr>
            <th>Epizód címe</th> <th>Megjelenés Dátuma</th> <th>Leírás</th>
        </tr>
        <?php foreach($_SESSION['modositottsorozat']['episodes'] as $epizod) : ?>
            <tr>
                <td><?=$epizod['title']?></td> <td><?=$epizod['date']?></td> <td><?=$epizod['plot']?></td><td><a href="modifyEpisode.php?id=<?=$epizod['id']?>">Módosítás</a></td><td><a href="deleteEpisode.php?id=<?=$epizod['id']?>">Törlés</a></td>
            </tr>
        <?php endforeach ?>
    </table>
    <a href="addEpisode.php">Epizód hozzáadása</a>
</body>
</html>