<?php

session_start();

require '../page_scripts/dbh.php';

require '../page_scripts/admin_check.php';

date_default_timezone_set('Europe/Zagreb');


if (isset($_POST['form-reset'])) {
    $_SESSION['option-name'] = NULL;
    $_SESSION['time-string'] = NULL;
    $_SESSION['ring-enable'] = NULL;
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
    $_SESSION['selected-button-value-4'] = NULL;

    $saveEnable = false;
} else $saveEnable = true;

if ($_SESSION['record-deleted']) {
    $_SESSION['option-name'] = NULL;
    $_SESSION['time-string'] = NULL;
    $_SESSION['ring-enable'] = NULL;
    $_SESSION['selected-button-value-1'] = NULL;
    $_SESSION['selected-button-value-2'] = NULL;
    $_SESSION['selected-button-value-3'] = NULL;
    $_SESSION['selected-button-value-4'] = NULL;
    $saveEnable = false;
} else $saveEnable = true;

globalDataSetup();
arduinoDataSetup();

?>

<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style/admin_page_style.css">
</head>

<body>
    <div class="wrapper">

        <header>
            <?php require '../page_scripts/header.php'; ?>
        </header>
        <br>
        <a class="setup" href="../user_page/user.php">↶ Povratak na prethodnu stranicu</a>
        <br>
        <a class="setup" style="text-align: right;" href="./settings_by_date.php">📅 Raspored zvona</a>
        <br>
        <section class="section1" style="display: block;">
            <form action="save_to_db.php" method="post" id="auto-time-set">
                <div class="settings-left">
                    <h2>Naziv postavke</h2>
                    <input class="option-name" type="text" maxlength="50" name="name" placeholder="<?php echo $_SESSION['name']; ?>">
                    <br><br>
                    <h2>Početak jutarnje smjene</h2>
                    <input class="option-time" type="text" name="start-hours" maxlength="2" placeholder="<?php echo $_SESSION['sh']; ?>" pattern="([01]?[0-9]|2[0-3])">
                    <h2 style="display: inline; font-weight: bolder;">:</h2>
                    <input class="option-time" type="text" name="start-minutes" maxlength="2" placeholder="<?php echo $_SESSION['sm']; ?>" pattern="([0-5][0-9])">
                    <p>Format: 00:00</p>
                    <br><br>
                    <h2>Trajanje nastavnog sata (min)</h2>
                    <input class="option-time" type="text" name="class-len" maxlength="3" placeholder="<?php echo $_SESSION['len']; ?>" pattern="([2-9][0-9]|[1-2][0-9][0-9]|[3][0][0])">
                    <p>20 - 300 min</p>
                </div>
                <div class="settings-right">
                    <h2>Trajanje malih odmora (min)</h2>
                    <input class="option-time" type="text" name="break" maxlength="3" placeholder="<?php echo $_SESSION['break']; ?>" pattern="([0-9]|[1][0])">
                    <p>0 - 10 min</p>
                    <br><br>
                    <h2>Trajanje velikih odmora (min)</h2>
                    <input class="option-time" type="text" name="long-break" maxlength="3" placeholder="<?php echo $_SESSION['l-break']; ?>" pattern="([1-9][0-9]|[1-2][0-9][0-9]|[3][0][0])">
                    <p>10 - 300 min</p>
                    <br><br>
                    <h2>Pauza između smjena (min)</h2>
                    <input class="option-time" type="text" name="shift-break" maxlength="3" placeholder="<?php echo $_SESSION['s-break']; ?>" pattern="([0-9]|[1-9][0-9]|[1-9][0-9][0-9])">
                    <p>0 - 999 min</p>
                    <br>
                </div>
            </form>
            <br>
            <button style="margin-left: 462px;" class="select" type="submit" form="auto-time-set" name="auto-gen">Generiraj</button>
            <br><br>
            <h5>
                <?php

                if (isset($_GET['error'])) {
                    if ($_GET['error'] == 'emptyfields') echo 'Popunite sva polja.';
                    if ($_GET['error'] == 'nameexists') echo 'Postavka pod tim nazivom već postoji.';
                    if ($_GET['error'] == 'sqlerror') echo 'Greška u bazi podataka, pokušajte ponovo.';
                }

                ?>
            </h5>
        </section>

        <section class="section2">
            <div class="menus">
                <form method="post" id="menu">
                    <h2>Aktivna postavka (<?php echo date('d.m.Y.', time()); ?>)</h2>
                    <div class="vertical-menu" style="height: 34px;">
                        <?php

                        $sql = "SELECT option_name FROM active_setting;";
                        $result = mysqli_query($conn, $sql);

                        $i = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<button type="submit" class="vm-option" form="menu" value="' . $row['option_name'] . '" name="active-button">';
                            echo $row['option_name'];
                            echo '</button>';
                            $i++;
                        }

                        ?>
                    </div>
                    <br>
                    <h2>Spremljene postavke</h2>
                    <div class="vertical-menu">
                        <?php

                        $sql = "SELECT option_name FROM time_set;";
                        $result = mysqli_query($conn, $sql);

                        $i = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<button type="submit" class="vm-option" form="menu" value="' . $row['option_name'] . '" name="ts-button' . $i . '">';
                            echo $row['option_name'];
                            echo '</button>';
                            $i++;
                        }

                        ?>
                    </div>
                    <br>
                    <h2>Arduino memorija</h2>
                    <div class="vertical-menu">
                        <?php

                        $sql = "SELECT option_name FROM eeprom_mirror;";
                        $result = mysqli_query($conn, $sql);

                        $i = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<button type="submit" class="vm-option" form="menu" value="' . $row['option_name'] . '" name="eeprom-button' . $i . '">';
                            echo $row['option_name'];
                            echo '</button>';
                            $i++;
                        }

                        ?>
                    </div>
                </form>
                <br>
                <form id="set-active" action="save_to_db.php" method="post">
                    <?php

                    if (isset($_SESSION['selected-button-value-1']) || isset($_SESSION['selected-button-value-2']) || isset($_SESSION['selected-button-value-3']) || isset($_SESSION['selected-button-value-4'])) {
                        if (!isset($_SESSION['selected-button-value-4'])) echo '<button class="select" type="submit" form="set-active" name="active-save">Postavi kao aktivno</button>';
                        echo '<button class="select" type="submit" form="set-active" name="delete">Obriši postavku</button>';
                    }

                    ?>
                </form>
            </div>
            <div class="manual-setup-tables">
                <form action="save_to_db.php" method="post" id="time_set">
                    <?php require 'manual_edit.php'; ?>
                </form>
                <br>
                <div class="buttons">
                    <form action="admin.php" id="reset" method="post" style="display: inline-block;">
                        <button class="select" type="submit" form="reset" name="form-reset">Očisti unos</button>
                    </form>
                    <?php
                    if (isset($_SESSION['selected-button-value-1']) || isset($_SESSION['selected-button-value-4'])) {
                        echo '<button class="select" type="submit" form="time_set" name="db-save">Spremi promjene</button>';
                        if ($saveEnable && isset($_SESSION['selected-button-value-1'])) echo '<button class="select" type="submit" form="time_set" name="eeprom-save">Spremi na arduino</button>';
                    } else {
                        if ($saveEnable && $_GET['error'] != 'emptyfields' && !isset($_SESSION['selected-button-value-2']) && !isset($_POST['form-reset'])) echo '<button class="select" type="submit" form="time_set" name="db-save">Spremi</button>';
                    }
                    ?>
                </div>
            </div>

        </section>
    </div>
</body>

</html>


<?php

function globalDataSetup()
{
    require '../page_scripts/dbh.php';

    $numOfResults = 1;
    $sql = "SELECT * FROM time_set;";
    $result = mysqli_query($conn, $sql);
    $numOfResults += mysqli_num_rows($result);
    $sql = "SELECT * FROM eeprom_mirror;";
    $result = mysqli_query($conn, $sql);
    $numOfResults += mysqli_num_rows($result);

    for ($i = 0; $i < $numOfResults; $i++) {
        if (isset($_POST['ts-button' . $i]) || isset($_POST['eeprom-button' . $i]) || isset($_POST['calendar-button' . $i]) || isset($_POST['active-button'])) {

            $_SESSION['option-name'] = NULL;
            $_SESSION['time-string'] = NULL;

            $_SESSION['eeprom-action'] = 'x';

            for ($i = 0; $i < $numOfResults; $i++) {
                if (isset($_POST['ts-button' . $i])) {
                    $_SESSION['selected-button-value-1'] = $_POST['ts-button' . $i];
                    $_SESSION['selected-button-value-2'] = NULL;
                    $_SESSION['selected-button-value-3'] = NULL;
                    $_SESSION['selected-button-value-4'] = NULL;
                }
            }

            for ($i = 0; $i < $numOfResults; $i++) {
                if (isset($_POST['eeprom-button' . $i])) {
                    $_SESSION['selected-button-value-2'] = $_POST['eeprom-button' . $i];
                    $_SESSION['selected-button-value-1'] = NULL;
                    $_SESSION['selected-button-value-3'] = NULL;
                    $_SESSION['selected-button-value-4'] = NULL;
                }
            }

            for ($i = 1; $i <= 31; $i++) {
                if (isset($_POST['calendar-button' . $i])) {
                    $_SESSION['selected-button-value-3'] = $_POST['calendar-button' . $i];
                    $_SESSION['selected-button-value-1'] = NULL;
                    $_SESSION['selected-button-value-2'] = NULL;
                    $_SESSION['selected-button-value-4'] = NULL;
                }
            }


            if (isset($_POST['active-button'])) {
                $_SESSION['selected-button-value-4'] = $_POST['active-button'];
                $_SESSION['selected-button-value-1'] = NULL;
                $_SESSION['selected-button-value-2'] = NULL;
                $_SESSION['selected-button-value-3'] = NULL;
            }

            if (isset($_SESSION['selected-button-value-1'])) {
                $sql = 'SELECT * FROM time_set WHERE option_name = \'' . $_SESSION['selected-button-value-1'] . '\'';
            }
            if (isset($_SESSION['selected-button-value-2'])) {
                $sql = 'SELECT * FROM eeprom_mirror WHERE option_name = \'' . $_SESSION['selected-button-value-2'] . '\'';
            }
            if (isset($_SESSION['selected-button-value-3'])) {
                if ($_SESSION['selected-button-value-3'] < 10) {
                    if (strlen($_SESSION['selected-button-value-3']) < 2) $_SESSION['selected-button-value-3'] = '0' . $_SESSION['selected-button-value-3'];
                }
                $dateToParse = $_SESSION['calendar-date'] . '-' . $_SESSION['selected-button-value-3'];
                $sql = 'SELECT * FROM settings_by_date WHERE date_active = \'' . $dateToParse . '\'';
                $_SESSION['date-to-parse'] = $dateToParse;
            }
            if (isset($_SESSION['selected-button-value-4'])) {
                $sql = 'SELECT * FROM active_setting WHERE option_name = \'' . $_SESSION['selected-button-value-4'] . '\'';
            }
            $result = mysqli_query($conn, $sql);

            if (!empty($result)) {
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['option_name'] == $_SESSION['selected-button-value-1']) break;
                    if ($row['option_name'] == $_SESSION['selected-button-value-2']) break;
                    if ($row['option_name'] == $_SESSION['selected-button-value-3']) break;
                    if ($row['option_name'] == $_SESSION['selected-button-value-4']) break;
                    if ($row['date_active'] == $dateToParse) break;
                }
                $_SESSION['to-be-set-active'] = $row;
            }
        } else if (!$_SESSION['record-deleted']) {
            if (isset($_SESSION['selected-button-value-1'])) $sql = 'SELECT * FROM time_set WHERE option_name = \'' . $_SESSION['selected-button-value-1'] . '\'';
            if (isset($_SESSION['selected-button-value-2'])) $sql = 'SELECT * FROM eeprom_mirror WHERE option_name = \'' . $_SESSION['selected-button-value-2'] . '\'';
            if (isset($_SESSION['selected-button-value-3'])) $sql = 'SELECT * FROM settings_by_date WHERE date_active = \'' . $_SESSION['date-to-parse'] . '\'';
            if (isset($_SESSION['selected-button-value-4'])) $sql = 'SELECT * FROM active_setting WHERE option_name = \'' . $_SESSION['selected-button-value-4'] . '\'';
            $result = mysqli_query($conn, $sql);

            if (!empty($result)) {
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['option_name'] == $_SESSION['selected-button-value-1']) break;
                    if ($row['option_name'] == $_SESSION['selected-button-value-2']) break;
                    if ($row['option_name'] == $_SESSION['selected-button-value-3']) break;
                    if ($row['option_name'] == $_SESSION['selected-button-value-4']) break;
                    if ($row['date_active'] == $dateToParse) break;
                }
                $_SESSION['to-be-set-active'] = $row;
            }
        }
    }

    $_SESSION['record-deleted'] = false;
}


function arduinoDataSetup()
{
    require '../page_scripts/dbh.php';

    if (!isset($_SESSION['eeprom-action'])) $_SESSION['eeprom-action'] = 'x';

    $sql = "DELETE FROM arduino_command;";
    mysqli_query($conn, $sql);

    if ($_SESSION['eeprom-action'] != 'x') {

        if ($_SESSION['eeprom-action'] == 'w') {
            $sql = 'SELECT * FROM eeprom_mirror WHERE option_name = \'' . $_SESSION['to-be-set-active']['option_name'] . '\';';
            $result = mysqli_query($conn, $sql);
            if (!empty($result)) $row = mysqli_fetch_assoc($result);
        }
        if ($_SESSION['eeprom-action'] == 'a') {
            $sql = 'SELECT * FROM active_setting WHERE id = 1';
            $result = mysqli_query($conn, $sql);
            if (!empty($result)) $row = mysqli_fetch_assoc($result);
        }
        if ($_SESSION['eeprom-action'] == 'd') {
            $row['option_name'] = $_SESSION['to-be-set-active']['option_name'];
            $row['time_string'] = '';
            $row['ring_enable'] = '';
        }

        $sql = "INSERT INTO arduino_command ( eeprom_action, option_name, time_string, ring_enable ) VALUES (?, ?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ./admin.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "ssss", $_SESSION['eeprom-action'], $row['option_name'], $row['time_string'], $row['ring_enable']);
            mysqli_stmt_execute($stmt);
        }

        sleep(5);

        $sql = "DELETE FROM arduino_command;";
        mysqli_query($conn, $sql);
        $sql = "INSERT INTO arduino_command ( eeprom_action, option_name, time_string, ring_enable ) VALUES ('x', 'x', 'x', 'x');";
        mysqli_query($conn, $sql);
        
    } else {
        $sql = "INSERT INTO arduino_command ( eeprom_action, option_name, time_string, ring_enable ) VALUES ('x', 'x', 'x', 'x');";
        mysqli_query($conn, $sql);
    }
}
