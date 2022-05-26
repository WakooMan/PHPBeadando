<?php
session_start();
include('storages.php');
$users = new UsersStorage();
$series = new SeriesStorage();
if(!isset($_SESSION['felhasznalo']) || !($users->findById($_SESSION['felhasznalo']['id'])['isadmin']) || !isset($_GET['id']) || $series->findById($_GET['id']) === NULL)
{
    header('Location: index.php');
    exit();
}
$series->delete($_GET['id']);
header('Location: index.php');
exit();
?>