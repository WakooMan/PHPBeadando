<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
</head>
<body>
<form action="" method="POST" novalidate>
        <label for="usern">Felhasználónév</label> <br>
        <input type="text" name="username" id="usern"> <br>
        <label for="email">E-mail Cím</label> <br>
        <input type="text" name="address" id="email"> <br>
        <label for="pwd">Jelszó</label> <br>
        <input type="password" name="password" id="pwd"> <br>
        <label for="pwdconfirm">Jelszó Megerősítése</label> <br>
        <input type="password" name="passwordconfirm" id="pwdconfirm"> <br>
        <button type="submit">Regisztráció</button>
    </form>
    <a href="bejelentkezes.php">Bejelentkezés</a>
</body>
</html>