<?php
session_start();
include('storages.php');
if(isset($_SESSION['felhasznalo']))
{
    header('Location:index.php');
    exit();
}
function validateUser($get,&$errors,UsersStorage $users)
{
    if(!isset($get['username']) || trim($get['username']) === '')
    {
        $errors['username'] = 'A felhasználónevet kötelező megadni!';
        return NULL;
    }
    else
    {
        $user = $users->findByUserName($get['username']);
        if($user === NULL)
        {
            $errors['username'] = 'Nincs ilyen felhasználónévvel rendelkező felhasználó!';
            return NULL;
        }
        else
        {
            if(!isset($get['password']) || trim($get['password']) === '')
            {
                $errors['password'] = 'A jelszót kötelező megadni!';
                return NULL;
            }
            else
            {
                if($user['password'] !== $get['password'])
                {
                    $errors['password'] = 'Rossz jelszót adtál meg!';
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
    $errors = [];
    $user = validateUser($_GET,$errors,$users);
    if($user !== NULL)
    {
        $_SESSION['felhasznalo']=$user['id'];
        // [
        //     'id'=>$user['id'],
        //     'username'=>$user['username'],
        //     'email'=>$user['email'],
        //     'watched'=>$user['watched'],
        // ];
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
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
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
            <a class="nav-link active" href="index.php">Főoldal</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="regisztracio.php">Regisztráció</a>
            </li>
        </ul>
                </div>
            </div>
        </nav>
    <div class="text-center">
    <h1>Bejelentkezés</h1>
    <?php if(isset($_SESSION['sikeresregisztracio'])) : ?>
        <span class="success"><?=$_SESSION['sikeresregisztracio']?></span>
        <?php $_SESSION['sikeresregisztracio'] = NULL; ?>
    <?php endif ?>
    <form action="" method="GET" novalidate>
        <label for="usern">Felhasználónév</label> <br>
        <input type="text" name="username" id="usern" value='<?=(isset($_GET['username']))?$_GET['username']:''?>'> <br>
        <?php if(isset($errors['username'])) : ?>
            <span class="error"><?=$errors['username']?></span> <br>
        <?php endif ?>
        <label for="pwd">Jelszó</label> <br>
        <input type="password" name="password" id="pwd" value='<?=(isset($_GET['password']))?$_GET['password']:''?>'> <br>
        <?php if(isset($errors['password'])) : ?>
            <span class="error"><?=$errors['password']?></span> <br>
        <?php endif ?>
        <button type="submit" class="btn btn-primary">Belépés</button>
    </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>
</html>