<?php
session_start();
$_SESSION['felhasznalo']= NULL;
header('Location: index.php');
exit();
?>