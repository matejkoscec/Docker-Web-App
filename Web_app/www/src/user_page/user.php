<?php

session_start();

require '../page_scripts/dbh.php';

require '../page_scripts/user_check.php';

?>

<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style/user_page_style.css">
</head>

<body>
    <div class="wrapper">
        <div class="header">
            <h3>Prijavljeni ste kao: </h3>
            <?php
            echo '<h4>' . $username . '</h4>';
            ?>
            <form action="../page_scripts/logout.php" method="post" id="logout">
                <button type=submit name="logout-submit">Odjava</button>
            </form>
        </div>
        <br>
        <br>
        <button onclick="window.location.href = '../admin_page/admin.php';">Postavljanje zvona</button>

        <div class="calendar">

        </div>
        <div class="selected">

        </div>
    </div>
</body>

</html>