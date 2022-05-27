<?php
session_start();
unset($_SESSION['felhasznalo']);
header('Location: index.php');
exit();
?>