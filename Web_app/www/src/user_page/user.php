<?php

session_start();

require '../page_scripts/dbh.php';

require '../page_scripts/user_check.php';

$_SESSION['option-name'] = NULL;
$_SESSION['time-string'] = NULL;
$_SESSION['name'] = NULL;
$_SESSION['sh'] = NULL;
$_SESSION['sm'] = NULL;
$_SESSION['len'] = NULL;
$_SESSION['break'] = NULL;
$_SESSION['l-break'] = NULL;
$_SESSION['s-break'] = NULL;
$_SESSION['selected-button-value-1'] = NULL;
$_SESSION['selected-button-value-2'] = NULL;
$_SESSION['selected-button-value-3'] = NULL;

?>

<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style/user_page_style.css">
</head>

<body>
    <div class="wrapper">
        
        <br>
        <br>
        <button onclick="window.location.href = '../admin_page/admin.php';">Postavljanje zvona</button>
    </div>
</body>

</html>