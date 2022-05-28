<?php
session_start();
include('storages.php');
include('validateEpisode.php');
$users = new UsersStorage();
$series = new SeriesStorage();
$user = (isset($_SESSION['felhasznalo']))?$users->findById($_SESSION['felhasznalo']):NULL;
if($user === NULL  || !$user['isadmin'] || !isset($_SESSION['sorozat']) || !isset($_SESSION['oldal']))
{
    header('Location: index.php');
    exit();
}

if(count($_POST)>0)
{
    $data = [];
    $errors = [];
    if(validate($_POST,$data,$errors,$_SESSION['sorozat']['episodes']) || $_POST['elkuld'] === 'cancel')
    {
        if($_POST['elkuld'] === 'add')
        {
            $id = uniqid();
            $_SESSION['sorozat']['episodes'][$id]=
            [
                'id' => $id,
                'title'=> $data['title'],
                'date' => $data['date'],
                'plot' => $data['plot'],
                'rating' => $data['rating'],
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
    <title>Epizód Hozzáadása</title>
    <style>
        .error {color:red;}
        .success {color:green;}
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>
<body>
    <div class="text-center">
        <h1>Epizód Hozzáadása</h1>
        <form action="" method="POST">
            <label for="cim">Cím</label> <input type="text" id="cim" name="title" value="<?=(isset($_POST['title'])?$_POST['title']:'')?>">
            <?php if(isset($errors['title'])) : ?>
                <span class="error"><?=$errors['title']?></span>
            <?php endif ?>
            <br>
            <label for="datum">Megjelenés dátuma</label> <input type="text" id="datum" name="date" value="<?=(isset($_POST['date'])?$_POST['date']:'')?>">
            <?php if(isset($errors['date'])) : ?>
                <span class="error"><?=$errors['date']?></span>
            <?php endif ?>
            <br>
            <label for="leiras">Leírás</label> <input type="text" id="leiras" name="plot" value="<?=(isset($_POST['plot'])?$_POST['plot']:'')?>">
            <?php if(isset($errors['plot'])) : ?>
                <span class="error"><?=$errors['plot']?></span>
            <?php endif ?>
            <br>
            <label for="ertekeles">Értékelés</label> <input type="text" id="ertekeles" name="rating" value="<?=(isset($_POST['rating'])?$_POST['rating']:'')?>">
            <?php if(isset($errors['rating'])) : ?>
                <span class="error"><?=$errors['rating']?></span>
            <?php endif ?>
            <br>
            <button type="submit" class="btn btn-primary" name="elkuld" value="add">Hozzáadás</button> <button class="btn btn-primary" type="submit" name="elkuld" value="cancel">Mégse</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>
</html>