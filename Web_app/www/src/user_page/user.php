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
$_SESSION['selected-button-value'] = NULL;
$_SESSION['selected-button-value-1'] = NULL;
$_SESSION['selected-button-value-2'] = NULL;
$_SESSION['selected-button-value-3'] = NULL;
$_SESSION['selected-button-value-4'] = NULL;

?>

<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style/user_page_style.css">
</head>

<body>
    <div class="wrapper">
        <header>
            <?php require '../page_scripts/header.php'; ?>
        </header>
        <?php if ($_SESSION['entry'] == 'admin') echo '<a class="setup" href="../admin_page/admin.php">🕐 Postavljanje zvona</a>'; ?>
        <section class="section1">
            <div class="calendar-wrapper">
                <?php

                if (!isset($_SESSION['time'])) {
                    $_SESSION['time'] = date('Y-m', time());
                    $_SESSION['date-to-parse'] = date('d.m.Y.');
                }
                calendarHandler();

                require '../page_scripts/calendar.php';

                ?>
            </div>
            <div>
                <?php
                
                echo '<p>' . $_SESSION['date-to-parse'] . '</p><br>';
                require '../page_scripts/time_display.php';

                ?>
            </div>
        </section>
    </div>
</body>

</html>

<?php

function calendarHandler()
{
    require '../page_scripts/dbh.php';

    $numOfResults = 32;
    for ($i = 0; $i < $numOfResults; $i++) {
        if (isset($_POST['calendar-button' . $i])) {
            for ($i = 1; $i <= 31; $i++) {
                if (isset($_POST['calendar-button' . $i])) $_SESSION['selected-button-value'] = $_POST['calendar-button' . $i];
            }

            if (isset($_SESSION['selected-button-value'])) {
                if ($_SESSION['selected-button-value'] < 10) $_SESSION['selected-button-value'] = '0' . $_SESSION['selected-button-value'];
                $dateToParse = $_SESSION['calendar-date'] . '-' . $_SESSION['selected-button-value'];
                $_SESSION['date-to-parse'] = date('d.m.Y.', strtotime($dateToParse));

                $sql = 'SELECT * FROM settings_by_date WHERE date_active = \'' . $dateToParse . '\'';
            }
            $result = mysqli_query($conn, $sql);

            if (!empty($result)) {
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['option_name'] == $_SESSION['selected-button-value']) break;
                    if ($row['date_active'] == $dateToParse) break;
                }
                $_SESSION['to-be-set-active'] = $row;
            }
            return;
        }
    }

    $sql = 'SELECT * FROM settings_by_date WHERE date_active = \'' . date('Y-m-d', time()) . '\'';
    $result = mysqli_query($conn, $sql);

    if (!empty($result)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['to-be-set-active'] = $row;
        }
    }
}
