<?php
session_start();
include('storages.php');
if(isset($_SESSION['felhasznalo']))
{
    header('Location:index.php');
    exit();
}
function validateUser($get,string &$error,UsersStorage $users)
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
if(isset($_GET) && count($_GET)>0)
{
    $users = new UsersStorage();
    $error = '';
    $user = validateUser($_GET,$error,$users);
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
    <style>
        .error
        {
            color: red;
        }
        .success
        {
            color:green;
        }
    </style>
</head>
<body>
    <?php if(isset($error) && trim($error)!=='') : ?>
        <span class="error"><?=$error?></span>
    <?php endif ?>
    <?php if(isset($_SESSION['sikeresregisztracio'])) : ?>
        <span class="success"><?=$_SESSION['sikeresregisztracio']?></span>
        <?php $_SESSION['sikeresregisztracio'] = NULL; ?>
    <?php endif ?>
    <form action="" method="GET" novalidate>
        <label for="usern">Felhasználónév</label> <br>
        <input type="text" name="username" id="usern" value='<?=(isset($_GET['username']))?$_GET['username']:''?>'> <br>
        <label for="pwd">Jelszó</label> <br>
        <input type="password" name="password" id="pwd" value='<?=(isset($_GET['password']))?$_GET['password']:''?>'> <br>
        <button type="submit">Belépés</button>
    </form>
    <a href="regisztracio.php">Regisztráció</a>
    <a href="index.php">Vissza a Főoldalra </a>
</body>
</html>