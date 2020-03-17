<?php

session_start();

require '../page_scripts/dbh.php';

require '../page_scripts/admin_check.php';

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
        <p><a href="../user_page/user.php">Povratak na prethodnu stranicu</a></p>
        <section class="section1">
            <form action="save_to_db.php" method="post" id="auto-time-set">
                <div class="settings-left">
                    <h2>Naziv postavke</h2>
                    <input class="option-name" type="text" maxlength="50" name="name" placeholder="<?php echo $_SESSION['name']; ?>">
                    <h2>Početak jutarnje smjene</h2>
                    <input type="text" name="start-hours" maxlength="2" placeholder="<?php echo $_SESSION['sh']; ?>" pattern="([01]?[0-9]|2[0-3])">
                    :
                    <input type="text" name="start-minutes" maxlength="2" placeholder="<?php echo $_SESSION['sm']; ?>" pattern="([0-5][0-9])">
                    <h2>Trajanje nastavnog sata (min)</h2>
                    <input class="option-time" type="text" name="class-len" maxlength="3" placeholder="<?php echo $_SESSION['len']; ?>" pattern="([2-9][0-9]|[1-2][0-9][0-9]|[3][0][0])">
                </div>
                <div class="settings-right">
                    <h2>Trajanje malih odmora (min)</h2>
                    <input class="option-time" type="text" name="break" maxlength="3" placeholder="<?php echo $_SESSION['break']; ?>" pattern="([0-9]|[1][0])">
                    <h2>Trajanje velikih odmora (min)</h2>
                    <input class="option-time" type="text" name="long-break" maxlength="3" placeholder="<?php echo $_SESSION['l-break']; ?>" pattern="([1-9][0-9]|[1-2][0-9][0-9]|[3][0][0])">
                    <h2>Pauza između smjena (min)</h2>
                    <input class="option-time" type="text" name="shift-break" maxlength="3" placeholder="<?php echo $_SESSION['s-break']; ?>" pattern="([0-9]|[1-9][0-9]|[1-9][0-9][0-9])">
                    <br>
                </div>
            </form>
            <br>
            <button style="margin-left: 462px;" class="select" type="submit" form="auto-time-set" name="auto-gen">Generiraj</button>
            <p style="display: inline;"><?php if (isset($_GET['error'])) if ($_GET['error'] == 'emptyfields') echo 'Popunite sva polja.'; ?></p>
        </section>

        <section class="section2">
            <div class="menus">
                <form method="post" id="menu">
                    <h2>Aktivna postavka (<?php echo date('d.m.Y', time()); ?>)</h2>
                    <div class="vertical_menu">
                        <?php

                        $sql = "SELECT option_name FROM active_setting;";
                        $result = mysqli_query($conn, $sql);

                        $i = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<button type="submit" class="vm_option" form="menu" value="' . $row['option_name'] . '" name="active-button">';
                            echo $row['option_name'];
                            echo '</button>';
                            $i++;
                        }

                        ?>
                    </div>

                    <h2>Spremljene postavke</h2>
                    <div class="vertical_menu">
                        <?php

                        $sql = "SELECT option_name FROM time_set;";
                        $result = mysqli_query($conn, $sql);

                        $i = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<button type="submit" class="vm_option" form="menu" value="' . $row['option_name'] . '" name="ts-button' . $i . '">';
                            echo $row['option_name'];
                            echo '</button>';
                            $i++;
                        }

                        ?>
                    </div>

                    <h2>Arduino memorija</h2>
                    <div class="vertical_menu">
                        <?php

                        $sql = "SELECT option_name FROM eeprom_mirror;";
                        $result = mysqli_query($conn, $sql);

                        $i = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<button type="submit" class="vm_option" form="menu" value="' . $row['option_name'] . '" name="eeprom-button' . $i . '">';
                            echo $row['option_name'];
                            echo '</button>';
                            $i++;
                        }

                        ?>
                    </div>
                </form>
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

                <div class="buttons">
                    <form action="admin.php" id="reset" method="post" style="display: inline-block;">
                        <button class="select" type="submit" form="reset" name="form-reset">Očisti unos</button>
                    </form>
                    <?php
                    if (isset($_SESSION['selected-button-value-1']) || isset($_SESSION['selected-button-value-2']) || isset($_SESSION['selected-button-value-3']) || isset($_SESSION['selected-button-value-4'])) {
                        echo '<button class="select" type="submit" form="time_set" name="db-save">Spremi promjene</button>';
                        if ($saveEnable && isset($_SESSION['selected-button-value-1'])) echo '<button class="select" type="submit" form="time_set" name="eeprom-save">Spremi na arduino</button>';
                    } else {
                        if ($saveEnable && $_GET['error'] != 'emptyfields') echo '<button class="select" type="submit" form="time_set" name="db-save">Spremi</button>';
                    }
                    ?>
                </div>
            </div>

        </section>

        <footer>
            <button class="select" onclick="window.location.href = './settings_by_date.php';">Raspored zvona</button>
        </footer>

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
