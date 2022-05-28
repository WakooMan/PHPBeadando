<?php
session_start();
include('storages.php');
include('validateSeries.php');
$users = new UsersStorage();
$series = new SeriesStorage();
$user = (isset($_SESSION['felhasznalo']))?$users->findById($_SESSION['felhasznalo']):NULL;
$_SESSION['oldal'] = 'modifySeries.php?id='.$_GET['id'];
if($user === NULL  || !$user['isadmin'] || !isset($_GET['id']) || $series->findById($_GET['id']) === NULL)
{
    header('Location: index.php');
    exit();
}
if(!isset($_SESSION['sorozat']))
{
    $_SESSION['sorozat'] =  $series->findById($_GET['id']);
}
if(count($_POST)>0)
{
    $data = [];
    $errors = [];
    if(validate($_POST,$data,$errors,$series,$_SESSION['sorozat']) || (isset($_POST['leadas']) && $_POST['leadas'] === 'cancel') || isset($_POST['addepisode']) || isset($_POST['modifyepisode']) || isset($_POST['deleteepisode']))
    {
        if(isset($_POST['addepisode']) || isset($_POST['modifyepisode']) || isset($_POST['deleteepisode']) )
        {
            $_SESSION['sorozat']['title'] = (isset($_POST['title']))?$_POST['title']:'';
            $_SESSION['sorozat']['year'] = (isset($_POST['year']))?$_POST['year']:'';
            $_SESSION['sorozat']['plot'] = (isset($_POST['plot']))?$_POST['plot']:'';
            $_SESSION['sorozat']['cover'] = (isset($_POST['cover']))?$_POST['cover']:'';
            if(isset($_POST['addepisode']))
            {
                header('Location: addEpisode.php');
            }
            elseif(isset($_POST['modifyepisode']))
            {
                header('Location: modifyEpisode.php?id='.$_POST['modifyepisode']);
            }
            elseif(isset($_POST['deleteepisode']))
            {
                header('Location: deleteEpisode.php?id='.$_POST['deleteepisode']);
            }
            exit();
        }
        if($_POST['leadas'] === 'add')
        {
            $_SESSION['sorozat']['title'] = $data['title'];
            $_SESSION['sorozat']['year'] = $data['year'];
            $_SESSION['sorozat']['plot'] = $data['plot'];
            $_SESSION['sorozat']['cover'] = $data['cover'];
            $series -> update($_SESSION['sorozat']['id'],$_SESSION['sorozat']);
            foreach($users-> findAll() as $user)
            {
                $id = $_SESSION['sorozat']['id'];
                if(count($_SESSION['sorozat']['episodes']) < $user['watched'][$id])
                {
                    $user['watched'][$id] = count($_SESSION['sorozat']['episodes']);
                    $users->update($user['id'],$user);
                }
            }
        }
        unset($_SESSION['sorozat']);
        unset($_SESSION['oldal']);
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
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
    <div class="text-center">
        <h1>Sorozat Módosítása</h1>
        <h2>Sorozat adatai</h2>
        <form action="" method="POST" novalidate>
            <label for="cim">Cím</label> <input type="text" name="title" id="cim" value="<?=(isset($_POST['title']))?$_POST['title']:$_SESSION['sorozat']['title']?>"> 
            <?php if(isset($errors['title'])) : ?>
                <span class="error"><?=$errors['title']?></span>
            <?php endif ?>
            <br>
            <label for="evjarat">Megjelenés éve</label> <input type="text" name="year" id="evjarat" value="<?=(isset($_POST['year']))?$_POST['year']:$_SESSION['sorozat']['year']?>"> 
            <?php if(isset($errors['year'])) : ?>
                <span class="error"><?=$errors['year']?></span>
            <?php endif ?>
            <br>
            <label for="leiras">Leírás</label> <input type="text" name="plot" id="leiras" value="<?=(isset($_POST['plot']))?$_POST['plot']:$_SESSION['sorozat']['plot']?>"> 
            <?php if(isset($errors['plot'])) : ?>
                <span class="error"><?=$errors['plot']?></span>
            <?php endif ?>
            <br>
            <label for="borito">Borító</label> <input type="text" name="cover" id="borito" value="<?=(isset($_POST['cover']))?$_POST['cover']:$_SESSION['sorozat']['cover']?>"> 
            <?php if(isset($errors['cover'])) : ?>
                <span class="error"><?=$errors['cover']?></span>
            <?php endif ?>
            <br>
            <button type="submit" class="btn btn-primary" name ="leadas" value="add">Mósodít</button> <button type="submit" class="btn btn-primary" name ="leadas" value="cancel">Mégse</button>
            <h2>Sorozat Epizódjai</h2>
            <table class="table table-striped">
                <tr>
                    <th>Epizód címe</th> <th>Megjelenés Dátuma</th> <th>Leírás</th> <th>Értékelés</th>
                </tr>
                <?php foreach($_SESSION['sorozat']['episodes'] as $epizod) : ?>
                    <tr>
                        <td><?=$epizod['title']?></td> <td><?=$epizod['date']?></td> <td><?=$epizod['plot']?></td> <td><?=$epizod['rating']?></td> <td><button type="submit" class="btn btn-secondary" name="modifyepisode" value="<?=$epizod['id']?>">Módosítás</button></td><td><button type="submit" class="btn btn-secondary" name="deleteepisode" value="<?=$epizod['id']?>">Törlés</button></td>
                    </tr>
                <?php endforeach ?>
            </table>
            <button class="btn btn-primary" type="submit" name="addepisode" value="add">Epizód hozzáadása</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>
</html>