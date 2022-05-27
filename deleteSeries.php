<?php
session_start();
include('storages.php');
$users = new UsersStorage();
$series = new SeriesStorage();
$user = (isset($_SESSION['felhasznalo']))?$users->findById($_SESSION['felhasznalo']):NULL;
if($user === NULL  || !$user['isadmin'] || !isset($_GET['id']) || $series->findById($_GET['id']) === NULL)
{
    header('Location: index.php');
    exit();
}
$series->delete($_GET['id']);
foreach($users-> findAll() as $user)
{
    unset($user['watched'][$_GET['id']]);
    $users->update($user['id'],$user);
}
header('Location: index.php');
exit();
?>