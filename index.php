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
</head>
<body>
    <h1>Sorozatfigyelő</h1>
    <h2>Rövid ismertetés</h2>
    <p></p>
    <?php if($user === NULL) : ?>
        <a href="bejelentkezes.php">Bejelentkezés</a>
    <?php else : ?>
        <a href="kijelentkezes.php">Kijelentkezés</a>
    <?php endif ?>
    <?php if($user !== NULL) : ?>
    <h2>Elkezdett Sorozatok</h2>
        <table id="tabla1">
            <tr>
                <th>Cím</th><th>Epizódok száma</th><th>Utolsó rész megjelenésének dátuma</th>
            </tr>
            <?php $_SESSION['elkezdett'] = array_filter($series -> findAll(),function($elem) use($user) {return $user['watched'][$elem['id']]>0;});
            foreach(array_reverse(array_slice($_SESSION['elkezdett'],-$_SESSION['visitedserieslap']['ig'],$_SESSION['visitedserieslap']['ig']-$_SESSION['visitedserieslap']['tol']+1)) as $ser) : ?>
                <tr>
                <td><?=$ser['title']?></td> <td><?=count($ser['episodes'])?></td> <td><?=end($ser['episodes'])['date']?></td> <td><a href="reszletek.php?id=<?=$ser['id']?>">Részletek</a></td> <?php if($user!==NULL && $user['isadmin']) : ?><td><a href="modifySeries.php?id=<?=$ser['id']?>">Módosítás</a></td> <td><a href="deleteSeries.php?id=<?=$ser['id']?>">Törlés</a></td><?php endif ?>
                </tr>
            <?php endforeach ?>
        </table>
        <div id="lapozasform1">
            <?php if($_SESSION['visitedserieslap']['tol']>5 && $_SESSION['visitedserieslap']['ig'] > 5) : ?>
                <button value="<?=($_SESSION['visitedserieslap']['tol']-5).'-'.($_SESSION['visitedserieslap']['ig']-($_SESSION['visitedserieslap']['ig']%5))?>"><?=($_SESSION['visitedserieslap']['tol']-5).'-'.($_SESSION['visitedserieslap']['ig']-($_SESSION['visitedserieslap']['ig']%5))?></button>
            <?php endif ?>
            <button value="<?=($_SESSION['visitedserieslap']['tol']).'-'.($_SESSION['visitedserieslap']['ig'])?>" disabled><?=($_SESSION['visitedserieslap']['tol']).'-'.((count($_SESSION['elkezdett'])<$_SESSION['visitedserieslap']['ig'])?count($_SESSION['elkezdett']):$_SESSION['visitedserieslap']['ig'])?></button>
            <?php if(count($_SESSION['elkezdett'])>=($_SESSION['visitedserieslap']['tol']+5)) : ?>
                <?php $tmpig = (count($_SESSION['elkezdett'])<($_SESSION['visitedserieslap']['ig']+5))?count($_SESSION['elkezdett']):$_SESSION['visitedserieslap']['ig']+5?>
                <button value="<?=($_SESSION['visitedserieslap']['tol']+5).'-'.$tmpig?>"><?=($_SESSION['visitedserieslap']['tol']+5).'-'.$tmpig?></button>
            <?php endif ?>
        </div>
    <?php endif ?>
    <h2>Összes Sorozat</h2>
    <table id="tabla2">
        <tr>
            <th>Cím</th><th>Epizódok száma</th><th>Utolsó rész megjelenésének dátuma</th>
        </tr>
        <?php $_SESSION['osszes'] = $series -> findAll();
        foreach(array_reverse(array_slice($_SESSION['osszes'],-$_SESSION['allserieslap']['ig'],$_SESSION['allserieslap']['ig']-$_SESSION['allserieslap']['tol']+1)) as $ser) : ?>
            <tr>
            <td><?=$ser['title']?></td> <td><?=count($ser['episodes'])?></td> <td><?=end($ser['episodes'])['date']?></td> <td><a href="reszletek.php?id=<?=$ser['id']?>">Részletek</a></td> <?php if($user !== NULL && $user['isadmin']) : ?><td><a href="modifySeries.php?id=<?=$ser['id']?>">Módosítás</a></td> <td><a href="deleteSeries.php?id=<?=$ser['id']?>">Törlés</a></td><?php endif ?>
            </tr>
        <?php endforeach ?>
    </table>
    <div id="lapozasform2">
        <?php if($_SESSION['allserieslap']['tol']>5 && $_SESSION['allserieslap']['ig'] > 5) : ?>
            <button value="<?=($_SESSION['allserieslap']['tol']-5).'-'.($_SESSION['allserieslap']['ig']-($_SESSION['allserieslap']['ig']%5))?>"><?=($_SESSION['allserieslap']['tol']-5).'-'.($_SESSION['allserieslap']['ig']-($_SESSION['allserieslap']['ig']%5))?></button>
        <?php endif ?>
        <button value="<?=($_SESSION['allserieslap']['tol']).'-'.($_SESSION['allserieslap']['ig'])?>" disabled><?=($_SESSION['allserieslap']['tol']).'-'.((count($_SESSION['osszes'])<$_SESSION['allserieslap']['ig'])?count($_SESSION['osszes']):$_SESSION['allserieslap']['ig'])?></button>
        <?php if(count($_SESSION['osszes'])>=($_SESSION['allserieslap']['tol']+5)) : ?>
            <?php $tmpig = (count($_SESSION['osszes'])<($_SESSION['allserieslap']['ig']+5))?count($_SESSION['osszes']):$_SESSION['allserieslap']['ig']+5?>
            <button value="<?=($_SESSION['allserieslap']['tol']+5).'-'.$tmpig?>"><?=($_SESSION['allserieslap']['tol']+5).'-'.$tmpig?></button>
        <?php endif ?>
    </div>
    <?php if($user!==NULL && $user['isadmin']) : ?>
        <a href="addSeries.php">Sorozat hozzáadása</a>
    <?php endif ?>
    <script src="ajax.js"></script>
</body>
</html>