<!-- Landing page -->

<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style/style.css">
</head>

<body>
    <div class="wrapper">
        <img src="img/tsrb_logo.png">
        <form action="page_scripts/login.php" method="post" id="login">
            <h2>Korisničko ime:</h2>
            <br><input type="text" name="uid">
            <br><br>
            <h2>Lozinka:</h2>
            <br><input type="password" name="pwd"><br>
            <br>
        </form>

        <?php

        if (isset($_GET['error'])) {

            if ($_GET['error'] == "emptyfields") echo '<br><p style="color: red; font-family: Arial, Helvetica, sans-serif; font-weight: lighter;">Popunite sva polja.</p><br>';
            if ($_GET['error'] == "sqlerror") echo '<br><p style="color: red; font-family: Arial, Helvetica, sans-serif; font-weight: lighter;">Greška u bazi podataka.</p><br>';
            if ($_GET['error'] == "wrongpwd") echo '<br><p style="color: red; font-family: Arial, Helvetica, sans-serif; font-weight: lighter;">Lozinka je neispravna.</p><br>';
            if ($_GET['error'] == "nouser") echo '<br><p style="color: red; font-family: Arial, Helvetica, sans-serif; font-weight: lighter;">Korisničko ime ne postoji.</p><br>';

        }

        ?>

        <button type="submit" form="login" name="login-submit">Prijava</button>
        <br>
        <br>

        <p>Niste prijavljeni u sustav? <a href="account_creation_page/entry_selection.php">Novi račun</a></p>
        <br>
        <p><a href="guest_page/guest.php">Nastavi kao gost...</a></p>
    </div>
</body>

</html>