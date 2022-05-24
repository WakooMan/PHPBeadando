<?php
session_start();
include('storages.php');
if(isset($_SESSION['felhasznalo']))
{
    header('Location:index.php');
    exit();
}
function validate($post,&$data,&$errors,UsersStorage $users)
{
    if(!isset($post['username']) || trim($post['username']) === '')
    {
        $errors['username'] = 'A felhasználónevet kötelező megadni!';
    }
    else
    {
        if($users->findByUserName($post['username']) !== NULL)
        {
            $errors['username'] = 'Már van ilyen felhasználónévvel rendelkező felhasználó!';
        }
        else
        {
            $data['username'] = $post['username'];
        }
    }

    if(!isset($post['address']) || trim($post['address']) === '')
    {
        $errors['address'] = 'Az e-mail címet kötelező megadni!';
    }
    else
    {
        if(!filter_var($post['address'],FILTER_VALIDATE_EMAIL))
        {
            $errors['address'] = 'Az e-mail cím formátuma nem jó!';
        }
        else
        {
            $data['address'] = $post['address'];
        }
    }

    if(!isset($post['password']) || trim($post['password']) === '')
    {
        $errors['password'] = 'A jelszót kötelező megadni!';
    }
    else
    {
        if(!isset($post['passwordconfirm']) || trim($post['passwordconfirm']) === '')
        {
            $errors['passwordconfirm'] = 'A jelszómegerősítőt kötelező megadni!';
        }
        else
        {
            if(strcmp($post['password'],$post['passwordconfirm']) !== 0)
            {
                $errors['passwordconfirm'] = 'A jelszómegerősítőnek meg kell egyeznie a megadott jelszóval!';
            }
            else
            {
                $data['password'] = $post['password'];
            }
        }
    }
    return count($errors) === 0;
}

if(isset($_POST) && count($_POST)>0)
{
    $users = new UsersStorage();
    $series = new SeriesStorage();
    $data = [];
    $errors = [];
    if(validate($_POST,$data,$errors,$users))
    {
        $initialseries = [];
        foreach($series->findAll() as $ser)
        {
            $initialseries[$ser['id']] = 0;
        }
        $users->add(
            [
                'username' =>$data['username'],
                'password' =>$data['password'],
                'email'=>$data['address'],
                'isadmin'=>false,
                'watched'=> $initialseries,
            ]);
        $_SESSION['sikeresregisztracio'] = 'Sikeres regisztráció!';
        header('Location: bejelentkezes.php');
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
    <title>Regisztráció</title>
    <style>
        .error
        {
            color: red;
        }
    </style>
</head>
<body>
<form action="" method="POST" novalidate>
        <label for="usern">Felhasználónév</label> <br>
        <input type="text" name="username" id="usern" value="<?=(isset($_POST['username']))?$_POST['username']:''?>"> 
        <?php if(isset($errors['username'])) : ?>
            <span class="error"><?=$errors['username']?></span>    
        <?php endif ?>
        <br>
        <label for="email">E-mail Cím</label> <br>
        <input type="text" name="address" id="email" value="<?=(isset($_POST['address']))?$_POST['address']:''?>"> 
        <?php if(isset($errors['address'])) : ?>
            <span class="error"><?=$errors['address']?></span>    
        <?php endif ?>
        <br>
        <label for="pwd">Jelszó</label> <br>
        <input type="password" name="password" id="pwd" value="<?=(isset($_POST['password']))?$_POST['password']:''?>"> 
        <?php if(isset($errors['password'])) : ?>
            <span class="error"><?=$errors['password']?></span>    
        <?php endif ?>
        <br>
        <label for="pwdconfirm">Jelszó Megerősítése</label> <br>
        <input type="password" name="passwordconfirm" id="pwdconfirm" value="<?=(isset($_POST['passwordconfirm']))?$_POST['passwordconfirm']:''?>"> 
        <?php if(isset($errors['passwordconfirm'])) : ?>
            <span class="error"><?=$errors['passwordconfirm']?></span>    
        <?php endif ?>
        <br>
        <button type="submit">Regisztráció</button>
    </form>
    <a href="bejelentkezes.php">Bejelentkezés</a>
</body>
</html>