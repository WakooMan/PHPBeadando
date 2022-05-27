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
if(isset($_GET['lapozas']))
{
    $array = explode('-',$_GET['lapozas']);
    $_SESSION['allserieslap'] = 
    [
        "tol" => $array[0],
        "ig" => $array[1],
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

if(isset($_GET['lapozas2']))
{
    $array = explode('-',$_GET['lapozas2']);
    $_SESSION['visitedserieslap'] = 
    [
        "tol" => $array[0],
        "ig" => $array[1],
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
        <table>
            <tr>
                <th>Cím</th><th>Epizódok száma</th><th>Utolsó rész megjelenésének dátuma</th>
            </tr>
            <?php $elkezdettseries = array_filter($series -> findAll(),function($elem) use($user) {return $user['watched'][$elem['id']]>0;});
            foreach(array_reverse(array_slice($elkezdettseries,-$_SESSION['visitedserieslap']['ig'],$_SESSION['visitedserieslap']['ig']-$_SESSION['visitedserieslap']['tol']+1)) as $ser) : ?>
                <tr>
                <td><?=$ser['title']?></td> <td><?=count($ser['episodes'])?></td> <td><?=end($ser['episodes'])['date']?></td> <td><a href="reszletek.php?id=<?=$ser['id']?>">Részletek</a></td> <?php if($user['isadmin']) : ?><td><a href="modifySeries.php?id=<?=$ser['id']?>">Módosítás</a></td> <td><a href="deleteSeries.php?id=<?=$ser['id']?>">Törlés</a></td><?php endif ?>
                </tr>
            <?php endforeach ?>
            <tr>
            <form action="" method="GET" novalidate>
            <?php if($_SESSION['visitedserieslap']['tol']>5 && $_SESSION['visitedserieslap']['ig'] > 5) : ?>
                <td><button type="submit" name="lapozas2" value="<?=($_SESSION['visitedserieslap']['tol']-5).'-'.($_SESSION['visitedserieslap']['ig']-($_SESSION['visitedserieslap']['ig']%5))?>"><?=($_SESSION['visitedserieslap']['tol']-5).'-'.($_SESSION['visitedserieslap']['ig']-($_SESSION['visitedserieslap']['ig']%5))?></button></td>
            <?php endif ?>
            <td><button type="submit" name="lapozas2" value="<?=($_SESSION['visitedserieslap']['tol']).'-'.($_SESSION['visitedserieslap']['ig'])?>" disabled><?=($_SESSION['visitedserieslap']['tol']).'-'.((count($elkezdettseries)<$_SESSION['visitedserieslap']['ig'])?count($elkezdettseries):$_SESSION['visitedserieslap']['ig'])?></button></td>
            <?php if(count($elkezdettseries)>=($_SESSION['visitedserieslap']['tol']+5)) : ?>
                <?php $tmpig = (count($elkezdettseries)<($_SESSION['visitedserieslap']['ig']+5))?count($elkezdettseries):$_SESSION['visitedserieslap']['ig']+5?>
                <td><button type="submit" name="lapozas2" value="<?=($_SESSION['visitedserieslap']['tol']+5).'-'.$tmpig?>"><?=($_SESSION['visitedserieslap']['tol']+5).'-'.$tmpig?></button></td>
            <?php endif ?>
            </form>
        </tr>
        </table>
    <?php endif ?>
    <h2>Összes Sorozat</h2>
    <table>
        <tr>
            <th>Cím</th><th>Epizódok száma</th><th>Utolsó rész megjelenésének dátuma</th>
        </tr>
        <?php $allseries = $series -> findAll();
        foreach(array_reverse(array_slice($allseries,-$_SESSION['allserieslap']['ig'],$_SESSION['allserieslap']['ig']-$_SESSION['allserieslap']['tol']+1)) as $ser) : ?>
            <tr>
            <td><?=$ser['title']?></td> <td><?=count($ser['episodes'])?></td> <td><?=end($ser['episodes'])['date']?></td> <td><a href="reszletek.php?id=<?=$ser['id']?>">Részletek</a></td> <?php if($user['isadmin']) : ?><td><a href="modifySeries.php?id=<?=$ser['id']?>">Módosítás</a></td> <td><a href="deleteSeries.php?id=<?=$ser['id']?>">Törlés</a></td><?php endif ?>
            </tr>
        <?php endforeach ?>
        <tr>
            <form action="" method="GET" novalidate>
            <?php if($_SESSION['allserieslap']['tol']>5 && $_SESSION['allserieslap']['ig'] > 5) : ?>
                <td><button type="submit" name="lapozas" value="<?=($_SESSION['allserieslap']['tol']-5).'-'.($_SESSION['allserieslap']['ig']-($_SESSION['allserieslap']['ig']%5))?>"><?=($_SESSION['allserieslap']['tol']-5).'-'.($_SESSION['allserieslap']['ig']-($_SESSION['allserieslap']['ig']%5))?></button></td>
            <?php endif ?>
            <td><button type="submit" name="lapozas" value="<?=($_SESSION['allserieslap']['tol']).'-'.($_SESSION['allserieslap']['ig'])?>" disabled><?=($_SESSION['allserieslap']['tol']).'-'.((count($allseries)<$_SESSION['allserieslap']['ig'])?count($allseries):$_SESSION['allserieslap']['ig'])?></button></td>
            <?php if(count($allseries)>=($_SESSION['allserieslap']['tol']+5)) : ?>
                <?php $tmpig = (count($allseries)<($_SESSION['allserieslap']['ig']+5))?count($allseries):$_SESSION['allserieslap']['ig']+5?>
                <td><button type="submit" name="lapozas" value="<?=($_SESSION['allserieslap']['tol']+5).'-'.$tmpig?>"><?=($_SESSION['allserieslap']['tol']+5).'-'.$tmpig?></button></td>
            <?php endif ?>
            </form>
        </tr>
    </table>
    <?php if($user['isadmin']) : ?>
        <a href="addSeries.php">Sorozat hozzáadása</a>
    <?php endif ?>
</body>
</html>