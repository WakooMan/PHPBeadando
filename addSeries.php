<?php
session_start();
include('storages.php');
$users = new UsersStorage();
$series = new SeriesStorage();
if(!isset($_SESSION['felhasznalo']) || !($users->findById($_SESSION['felhasznalo']['id'])['isadmin']))
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
    <title>Sorozat Hozzáadása</title>
</head>
<body>
    
</body>
</html>