<?php
session_start();
include('storages.php');
include('validateSeries.php');
$users = new UsersStorage();
$series = new SeriesStorage();
$user = (isset($_SESSION['felhasznalo']))?$users->findById($_SESSION['felhasznalo']):NULL;
$_SESSION['oldal'] = 'addSeries.php';
if($user === NULL  || !$user['isadmin'])
{
    header('Location: index.php');
    exit();
}
if(!isset($_SESSION['sorozat']))
{
    $_SESSION['sorozat'] = 
    [
        'episodes' => [],
    ];
}
if(count($_POST)>0)
{
    $data = [];
    $errors = [];
    if(validate($_POST,$data,$errors,$series) || (isset($_POST['leadas']) && $_POST['leadas'] === 'cancel') || isset($_POST['addepisode']) || isset($_POST['modifyepisode']) || isset($_POST['deleteepisode']))
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
            $id = $series -> add($_SESSION['sorozat']);
            foreach($users-> findAll() as $u)
            {
                $u['watched'][$id] = 0;
                $users->update($u['id'],$u);
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
    <title>Sorozat Hozz??ad??sa</title>
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>
<body>
    <div class="text-center">
        <h1>Sorozat Hozz??ad??sa</h1>
        <h2>Sorozat adatai</h2>
        <form action="" method="POST" novalidate>
            <label for="cim">C??m</label> <input type="text" name="title" id="cim" value="<?=(isset($_POST['title']))?$_POST['title']:((isset($_SESSION['sorozat']['title']))?$_SESSION['sorozat']['title']:'')?>"> 
            <?php if(isset($errors['title'])) : ?>
                <span class="error"><?=$errors['title']?></span>
            <?php endif ?>
            <br>
            <label for="evjarat">Megjelen??s ??ve</label> <input type="text" name="year" id="evjarat" value="<?=(isset($_POST['year']))?$_POST['year']:((isset($_SESSION['sorozat']['year']))?$_SESSION['sorozat']['year']:'')?>"> 
            <?php if(isset($errors['year'])) : ?>
                <span class="error"><?=$errors['year']?></span>
            <?php endif ?>
            <br>
            <label for="leiras">Le??r??s</label> <input type="text" name="plot" id="leiras" value="<?=(isset($_POST['plot']))?$_POST['plot']:((isset($_SESSION['sorozat']['plot']))?$_SESSION['sorozat']['plot']:'')?>"> 
            <?php if(isset($errors['plot'])) : ?>
                <span class="error"><?=$errors['plot']?></span>
            <?php endif ?>
            <br>
            <label for="borito">Bor??t??</label> <input type="text" name="cover" id="borito" value="<?=(isset($_POST['cover']))?$_POST['cover']:((isset($_SESSION['sorozat']['cover']))?$_SESSION['sorozat']['cover']:'')?>"> 
            <?php if(isset($errors['cover'])) : ?>
                <span class="error"><?=$errors['cover']?></span>
            <?php endif ?>
            <br>
            <button type="submit" class="btn btn-primary" name ="leadas" value="add">Hozz??ad</button> <button type="submit" class="btn btn-primary" name ="leadas" value="cancel">M??gse</button> <br>
            <h2>Sorozat Epiz??djai</h2>
            <table class="table table-striped">
                <tr>
                    <th>Epiz??d c??me</th> <th>Megjelen??s D??tuma</th> <th>Le??r??s</th> <th>??rt??kel??s</th>
                </tr>
                <?php foreach($_SESSION['sorozat']['episodes'] as $epizod) : ?>
                    <tr>
                        <td><?=$epizod['title']?></td> <td><?=$epizod['date']?></td> <td><?=$epizod['plot']?></td> <td><?=$epizod['rating']?></td> <td><button type="submit" class="btn btn-secondary" name="modifyepisode" value="<?=$epizod['id']?>">M??dos??t??s</button></td><td><button type="submit" class="btn btn-secondary" name="deleteepisode" value="<?=$epizod['id']?>">T??rl??s</button></td>
                    </tr>
                <?php endforeach ?>
            </table>
            <button type="submit" class="btn btn-primary" name="addepisode" value="add">Epiz??d hozz??ad??sa</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>
</html>