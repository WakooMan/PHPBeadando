<?php
session_start();
include('userStorage.php');
function validate($get,string &$error,UsersStorage $users)
{
    if(!isset($get['username']) || trim($get['username']) === '')
    {
        $error = 'A felhasználónevet kötelező megadni!';
        return NULL;
    }
    else
    {
        $user = $users->findByUserName($get['username']);
        if($user === NULL)
        {
            $error = 'Nincs ilyen felhasználónévvel rendelkező felhasználó!';
            return NULL;
        }
        else
        {
            if(!isset($get['password']) || trim($get['password']) === '')
            {
                $error = 'A jelszót kötelező megadni!';
                return NULL;
            }
            else
            {
                if($user['password'] !== $get['password'])
                {
                    $error = 'Rossz jelszót adtál meg!';
                    return NULL;
                }
                return $user;
            }
        }
    }
}
if(isset($_SESSION['felhasznalo']))
{
    header('Location:index.php');
    exit();
}
$users = new UsersStorage();
if(isset($_GET) && count($_GET)>0)
{
    $error = '';
    $user = validate($_GET,$error,$users);
    if($user !== NULL)
    {
        $_SESSION['felhasznalo']=
        [
            'id'=>$user['id'],
            'username'=>$user['username'],
            'email'=>$user['email'],
            'watched'=>$user['watched'],
        ];
        header('Location: index.php');
        exit();
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
</head>
<body>
    <form action="" method="GET" novalidate>
        <label for="usern">Felhasználónév</label> <br>
        <input type="text" name="username" id="usern"> <br>
        <label for="pwd">Jelszó</label> <br>
        <input type="password" name="password" id="pwd"> <br>
        <button type="submit">Belépés</button>
    </form>
    <a href="regisztracio.php">Regisztráció</a>
    <a href="index.php">Vissza a Főoldalra </a>
</body>
</html>