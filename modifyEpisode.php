<?php
session_start();
include('storages.php');
include('validateEpisode.php');
$users = new UsersStorage();
$series = new SeriesStorage();
if(!isset($_SESSION['felhasznalo']) || !($users->findById($_SESSION['felhasznalo']['id'])['isadmin']) || !isset($_GET['id']) || !isset($_SESSION['sorozat']) || $_SESSION['sorozat']['episodes'][$_GET['id']] === NULL || !isset($_SESSION['oldal']))
{
    header('Location: index.php');
    exit();
}
$episode = $_SESSION['sorozat']['episodes'][$_GET['id']];
if(count($_POST)>0)
{
    $data = [];
    $errors = [];
    if(validate($_POST,$data,$errors,$_SESSION['sorozat']['episodes'],$episode) || $_POST['elkuld'] === 'cancel')
    {
        if($_POST['elkuld'] === 'add')
        {
            $_SESSION['sorozat']['episodes'][$_GET['id']]=
            [
                'id' => $id,
                'title'=> $data['title'],
                'date' => $data['date'],
                'plot' => $data['plot'],
            ];
        }
        header('Location: '.$_SESSION['oldal']);
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
    <title>Epizód Módosítása</title>
    <style>
        .error {color:red;}
        .success {color:green;}
    </style>
</head>
<body>
<h1>Epizód Módosítása</h1>
    <form action="" method="POST">
        <label for="cim">Cím</label> <input type="text" id="cim" name="title" value="<?=(isset($_POST['title'])?$_POST['title']:$episode['title'])?>">
        <?php if(isset($errors['title'])) : ?>
            <span class="error"><?=$errors['title']?></span>
        <?php endif ?>
        <br>
        <label for="datum">Megjelenés dátuma</label> <input type="text" id="datum" name="date" value="<?=(isset($_POST['date'])?$_POST['date']:$episode['date'])?>">
        <?php if(isset($errors['date'])) : ?>
            <span class="error"><?=$errors['date']?></span>
        <?php endif ?>
        <br>
        <label for="leiras">Leírás</label> <input type="text" id="leiras" name="plot" value="<?=(isset($_POST['plot'])?$_POST['plot']:$episode['plot'])?>">
        <?php if(isset($errors['plot'])) : ?>
            <span class="error"><?=$errors['plot']?></span>
        <?php endif ?>
        <br>
        <button type="submit" name="elkuld" value="add">Módosít</button> <button type="submit" name="elkuld" value="cancel">Mégse</button>
    </form>
</body>
</html>