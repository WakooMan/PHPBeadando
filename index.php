<?php
session_start();
include('storages.php');
$users = new UsersStorage();
$series = new SeriesStorage();
$user = (isset($_SESSION['felhasznalo']))?$users->findById($_SESSION['felhasznalo']):NULL;
if(!isset($_SESSION['allserieslap']))
{
    $_SESSION['allserieslap'] = 
    [
        "tol" => 1,
        "ig" => 5,
    ];
}
if($user !== NULL && !isset($_SESSION['visitedserieslap']))
{
    $_SESSION['visitedserieslap'] = 
    [
        "tol" => 1,
        "ig" => 5,
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Főoldal</title>
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
            <a class="nav-link active" href="<?=($user===NULL)?'bejelentkezes.php':'kijelentkezes.php'?>"><?=($user===NULL)?'Bejelentkezés':'Kijelentkezés'?></a>
            </li>
            <?php if($user!==NULL && $user['isadmin']) : ?>
                <li class="nav-item">
                    <a class="nav-link active" href="addSeries.php">Sorozat Hozzáadása</a>
                </li>
            <?php endif ?>
        </ul>
                </div>
            </div>
        </nav>
    <h1 class="text-center display-1">Sorozatfigyelő</h1>
    <article>
        <h2 class="text-center">
            Regisztrálj és kövesd nyomon kedvenc sorozataidat! <br>
            És <br>
            Tudj meg többet róluk!
        </h2>
    </article>
    <?php if($user !== NULL) : ?>
    <h2>Elkezdett Sorozatok</h2>
        <table id="tabla1" class="table table-striped">
            <tr>
                <th>Cím</th><th>Epizódok száma</th><th>Utolsó rész megjelenésének dátuma</th> <?=($user!==NULL && $user['isadmin']?'<th></th><th></th><th></th>':'')?>
            </tr>
            <?php $_SESSION['elkezdett'] = array_filter($series -> findAll(),function($elem) use($user) {return $user['watched'][$elem['id']]>0;});
            foreach(array_reverse(array_slice($_SESSION['elkezdett'],-$_SESSION['visitedserieslap']['ig'],$_SESSION['visitedserieslap']['ig']-$_SESSION['visitedserieslap']['tol']+1)) as $ser) : ?>
                <tr>
                <td><?=$ser['title']?></td> <td><?=count($ser['episodes'])?></td> <td><?=(count($ser['episodes'])>0)?end($ser['episodes'])['date']:'-'?></td> <td><a href="reszletek.php?id=<?=$ser['id']?>" class="btn btn-secondary">Részletek</a></td> <?php if($user!==NULL && $user['isadmin']) : ?><td><a href="modifySeries.php?id=<?=$ser['id']?>" class="btn btn-secondary">Módosítás</a></td> <td><a href="deleteSeries.php?id=<?=$ser['id']?>" class="btn btn-secondary">Törlés</a></td><?php endif ?>
                </tr>
            <?php endforeach ?>
        </table>
        <div id="lapozasform1">
            <?php if($_SESSION['visitedserieslap']['tol']>5 && $_SESSION['visitedserieslap']['ig'] > 5) : ?>
                <button class="btn btn-primary" value="<?=($_SESSION['visitedserieslap']['tol']-5).'-'.($_SESSION['visitedserieslap']['ig']-($_SESSION['visitedserieslap']['ig']%5))?>"><?=($_SESSION['visitedserieslap']['tol']-5).'-'.($_SESSION['visitedserieslap']['ig']-($_SESSION['visitedserieslap']['ig']%5))?></button>
            <?php endif ?>
            <button class="btn btn-primary" value="<?=($_SESSION['visitedserieslap']['tol']).'-'.($_SESSION['visitedserieslap']['ig'])?>" disabled><?=($_SESSION['visitedserieslap']['tol']).'-'.((count($_SESSION['elkezdett'])<$_SESSION['visitedserieslap']['ig'])?count($_SESSION['elkezdett']):$_SESSION['visitedserieslap']['ig'])?></button>
            <?php if(count($_SESSION['elkezdett'])>=($_SESSION['visitedserieslap']['tol']+5)) : ?>
                <?php $tmpig = (count($_SESSION['elkezdett'])<($_SESSION['visitedserieslap']['ig']+5))?count($_SESSION['elkezdett']):$_SESSION['visitedserieslap']['ig']+5?>
                <button class="btn btn-primary" value="<?=($_SESSION['visitedserieslap']['tol']+5).'-'.$tmpig?>"><?=($_SESSION['visitedserieslap']['tol']+5).'-'.$tmpig?></button>
            <?php endif ?>
        </div>
    <?php endif ?>
    <h2>Összes Sorozat</h2>
    <table id="tabla2" class="table table-striped">
        <tr>
            <th>Cím</th><th>Epizódok száma</th><th>Utolsó rész megjelenésének dátuma</th> <?=($user!==NULL && $user['isadmin']?'<th></th><th></th><th></th>':'')?>
        </tr>
        <?php $_SESSION['osszes'] = $series -> findAll();
        foreach(array_reverse(array_slice($_SESSION['osszes'],-$_SESSION['allserieslap']['ig'],$_SESSION['allserieslap']['ig']-$_SESSION['allserieslap']['tol']+1)) as $ser) : ?>
            <tr>
            <td><?=$ser['title']?></td> <td><?=count($ser['episodes'])?></td> <td><?=(count($ser['episodes'])>0)?end($ser['episodes'])['date']:'-'?></td> <td><a href="reszletek.php?id=<?=$ser['id']?>" class="btn btn-secondary">Részletek</a></td> <?php if($user!=NULL && $user['isadmin']) : ?><td><a href="modifySeries.php?id=<?=$ser['id']?>" class="btn btn-secondary">Módosítás</a></td> <td><a href="deleteSeries.php?id=<?=$ser['id']?>" class="btn btn-secondary">Törlés</a></td><?php endif ?>
            </tr>
        <?php endforeach ?>
    </table>
    <div id="lapozasform2">
        <?php if($_SESSION['allserieslap']['tol']>5 && $_SESSION['allserieslap']['ig'] > 5) : ?>
            <button class="btn btn-primary" value="<?=($_SESSION['allserieslap']['tol']-5).'-'.($_SESSION['allserieslap']['ig']-($_SESSION['allserieslap']['ig']%5))?>"><?=($_SESSION['allserieslap']['tol']-5).'-'.($_SESSION['allserieslap']['ig']-($_SESSION['allserieslap']['ig']%5))?></button>
        <?php endif ?>
        <button class="btn btn-primary" value="<?=($_SESSION['allserieslap']['tol']).'-'.($_SESSION['allserieslap']['ig'])?>" disabled><?=($_SESSION['allserieslap']['tol']).'-'.((count($_SESSION['osszes'])<$_SESSION['allserieslap']['ig'])?count($_SESSION['osszes']):$_SESSION['allserieslap']['ig'])?></button>
        <?php if(count($_SESSION['osszes'])>=($_SESSION['allserieslap']['tol']+5)) : ?>
            <?php $tmpig = (count($_SESSION['osszes'])<($_SESSION['allserieslap']['ig']+5))?count($_SESSION['osszes']):$_SESSION['allserieslap']['ig']+5?>
            <button class="btn btn-primary" value="<?=($_SESSION['allserieslap']['tol']+5).'-'.$tmpig?>"><?=($_SESSION['allserieslap']['tol']+5).'-'.$tmpig?></button>
        <?php endif ?>
    </div>
    <script src="ajax.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>
</html>