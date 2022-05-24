<?php
session_start();
include('storages.php');
$users = new UsersStorage();
if(!isset($_SESSION['felhasznalo']) || !($users->findById($_SESSION['felhasznalo']['id'])['isadmin']) || !isset($_GET['id']) || $series->findById($_GET['id']) === NULL)
{
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sorozat Módosítása</title>
</head>
<body>
    
</body>
</html>