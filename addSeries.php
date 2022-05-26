<?php
session_start();
include('storages.php');
include('validateSeries.php');
$users = new UsersStorage();
$series = new SeriesStorage();
if(!isset($_SESSION['felhasznalo']) || !($users->findById($_SESSION['felhasznalo']['id'])['isadmin']))
{
    header('Location: index.php');
    exit();
}
if(!isset($_SESSION['ujsorozat']))
{
    $_SESSION['ujsorozat'] = 
    [
        'year' => 0,
        'title' => '',
        'plot' => '',
        'cover' => '',
        'episodes' => [],
    ];
}
if(count($_POST)>0)
{
    $data = [];
    $errors = [];
    if(validate($_POST,$data,$errors,$series) || $_POST['leadas'] === 'cancel')
    {
        if($_POST['leadas'] === 'add')
        {
            $_SESSION['ujsorozat']['title'] = $data['title'];
            $_SESSION['ujsorozat']['year'] = $data['year'];
            $_SESSION['ujsorozat']['plot'] = $data['plot'];
            $_SESSION['ujsorozat']['cover'] = $data['cover'];
            $series -> add($_SESSION['ujsorozat']);
        }
        $_SESSION['ujsorozat'] = NULL;
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
    <title>Sorozat Hozzáadása</title>
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
        <label for="cim">Cím</label> <input type="text" name="title" id="cim" value="<?=(isset($_POST['title']))?$_POST['title']:''?>"> 
        <?php if(isset($errors['title'])) : ?>
            <span class="error"><?=$errors['title']?></span>
        <?php endif ?>
        <br>
        <label for="evjarat">Megjelenés dátuma</label> <input type="text" name="year" id="evjarat" value="<?=(isset($_POST['year']))?$_POST['year']:''?>"> 
        <?php if(isset($errors['year'])) : ?>
            <span class="error"><?=$errors['year']?></span>
        <?php endif ?>
        <br>
        <label for="leiras">Leírás</label> <input type="text" name="plot" id="leiras" value="<?=(isset($_POST['plot']))?$_POST['plot']:''?>"> 
        <?php if(isset($errors['plot'])) : ?>
            <span class="error"><?=$errors['plot']?></span>
        <?php endif ?>
        <br>
        <label for="borito">Borító</label> <input type="text" name="cover" id="borito" value="<?=(isset($_POST['cover']))?$_POST['cover']:''?>"> 
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
        <?php foreach($_SESSION['ujsorozat']['episodes'] as $epizod) : ?>
            <tr>
                <td><?=$epizod['title']?></td> <td><?=$epizod['date']?></td> <td><?=$epizod['plot']?></td>
            </tr>
        <?php endforeach ?>
    </table>
    <a href="addEpisode.php">Epizód hozzáadása</a>
</body>
</html>