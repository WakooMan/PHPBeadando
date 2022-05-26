<?php
session_start();
include('storages.php');
$users = new UsersStorage();
$series = new SeriesStorage();
if(!isset($_SESSION['felhasznalo']) || !($users->findById($_SESSION['felhasznalo']['id'])['isadmin']) || !isset($_GET['id']) || !isset($_SESSION['sorozat']) || $_SESSION['sorozat']['episodes'][$_GET['id']] === NULL || !isset($_SESSION['oldal']))
{
    header('Location: index.php');
    exit();
}
unset($_SESSION['sorozat']['episodes'][$_GET['id']]);
header('Location: '.$_SESSION['oldal']);
exit();
?>