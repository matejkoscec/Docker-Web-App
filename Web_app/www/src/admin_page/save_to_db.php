<?php

session_start();

require '../page_scripts/dbh.php';

ini_set('memory_limit', '1024M');


if (isset($_POST['db-save']) || isset($_POST['eeprom-save'])) {

    dataSetup();

    if (isset($_SESSION['selected-button-value-1']) || isset($_SESSION['selected-button-value-2']) || isset($_SESSION['selected-button-value-3']) || isset($_SESSION['selected-button-value-4'])) {
        if (isset($_SESSION['selected-button-value-1'])) $sql = "UPDATE time_set SET option_name = ?, time_string = ?, ring_enable = ? WHERE option_name = '" . $_SESSION['to-be-set-active']['option_name'] . "';";
        if (isset($_SESSION['selected-button-value-2'])) $sql = "UPDATE eeprom_mirror SET option_name = ?, time_string = ?, ring_enable = ? WHERE option_name = '" . $_SESSION['to-be-set-active']['option_name'] . "';";
        if (isset($_SESSION['selected-button-value-3'])) $sql = "UPDATE settings_by_date SET option_name = ?, time_string = ?, ring_enable = ? WHERE option_name = '" . $_SESSION['to-be-set-active']['option_name'] . "';";
        if (isset($_SESSION['selected-button-value-4'])) $sql = "UPDATE active_setting SET option_name = ?, time_string = ?, ring_enable = ? WHERE option_name = '" . $_SESSION['to-be-set-active']['option_name'] . "';";
        if (isset($_POST['eeprom-save'])) {
            $sql = "INSERT INTO eeprom_mirror (option_name, time_string, ring_enable) VALUES (?, ?, ?);";
            $_SESSION['eeprom-action'] = 'w';
        }
    } else {
        if (isset($_POST['db-save'])) {
            $sql = "INSERT INTO time_set (option_name, time_string, ring_enable) VALUES (?, ?, ?);";
            $_SESSION['eeprom-action'] = 'x';
        }
    }
    $stmt = mysqli_stmt_init($conn);

    checkRecords();
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ./admin.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "sss", $_SESSION['option-name'], $_SESSION['time-string'], $_SESSION['ring-enable']);
        mysqli_stmt_execute($stmt);
    }
    header("Location: ./admin.php");
    exit();
}


if (isset($_POST['active-save'])) {
    
    $sql = "DELETE FROM active_setting WHERE id = 1";
    mysqli_query($conn, $sql);
    $sql = "INSERT INTO active_setting (id, option_name, time_string, ring_enable) VALUES (1, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ./admin.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "sss", $_SESSION['to-be-set-active']['option_name'], $_SESSION['to-be-set-active']['time_string'], $_SESSION['to-be-set-active']['ring_enable']);
        mysqli_stmt_execute($stmt);
    }

    $sql = "DELETE FROM settings_by_date WHERE date_active = '" . date('Y-m-d', time()) . "'";
    mysqli_query($conn, $sql);
    $sql = "INSERT INTO settings_by_date (date_active, option_name, time_string, ring_enable) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ./admin.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "ssss", date('Y-m-d', time()), $_SESSION['to-be-set-active']['option_name'], $_SESSION['to-be-set-active']['time_string'], $_SESSION['to-be-set-active']['ring_enable']);
        mysqli_stmt_execute($stmt);
    }

    $_SESSION['eeprom-action'] = 'a';

    header("Location: ./admin.php");
    exit();
}


if (isset($_POST['delete'])) {

    $_SESSION['record-deleted'] = true;

    if (isset($_SESSION['selected-button-value-1'])) $sql = "DELETE FROM time_set WHERE option_name = '" . $_SESSION['selected-button-value-1'] . "';";
    if (isset($_SESSION['selected-button-value-2'])) $sql = "DELETE FROM eeprom_mirror WHERE option_name = '" . $_SESSION['selected-button-value-2'] . "';";
    if (isset($_SESSION['selected-button-value-4'])) {
        $sql = "DELETE FROM active_setting WHERE option_name = '" . $_SESSION['selected-button-value-4'] . "';";
        $sql .= "DELETE FROM settings_by_date WHERE date_active = '" . $_SESSION['date-to-parse'] . "';";
    }
    mysqli_multi_query($conn, $sql);

    if (isset($_SESSION['selected-button-value-2'])) $_SESSION['eeprom-action'] = 'd';
    else $_SESSION['eeprom-action'] = 'x';

    header("Location: ./admin.php");
    exit();
}


if (isset($_POST['auto-gen'])) {

    $_SESSION['selected-button-value-1'] = NULL;
    $_SESSION['selected-button-value-2'] = NULL;
    $_SESSION['selected-button-value-3'] = NULL;
    $_SESSION['selected-button-value-4'] = NULL;

    $_SESSION['eeprom-action'] = 'x';

    autoGenData();

    header("Location: ./admin.php");
    exit();
} else {
    header("Location: ./admin.php");
    exit();
}

header("Location: ./admin.php");
exit();



function checkRecords()
{
    require '../page_scripts/dbh.php';

    if (isset($_POST['db-save'])) $sql = "SELECT * FROM time_set WHERE option_name = '" . $_SESSION['option-name'] . "'";
    if (isset($_POST['eeprom-save'])) $sql = "SELECT * FROM eeprom_mirror WHERE option_name = '" . $_SESSION['option-name'] . "'";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        header("Location: ./admin.php?error=nameexists");
        exit();
    }
}


function dataSetup()
{
    $optionName = $_POST['opn'];

    if (!empty($optionName)) {
        if (strcmp($optionName, $_SESSION['option-name']) != 0) {
            $_SESSION['option-name'] = $optionName;
        }
    }

    if (!(isset($_SESSION['selected-button-value-1']) || isset($_SESSION['selected-button-value-2']) || isset($_SESSION['selected-button-value-3']) || isset($_SESSION['selected-button-value-4']))) {
        checkRecords();
    } else if (empty($optionName)) $_SESSION['option-name'] = $_SESSION['to-be-set-active']['option_name'];


    $timeString = '';
    for ($i = 1; $i <= 7; $i++) {
        for ($j = 0; $j < 4; $j++) {
            (is_numeric($_POST['u' . $i . $j])) ? $timeString = $timeString . $_POST['u' . $i . $j] : $timeString = $timeString . 'xx';
        }
    }
    for ($i = 1; $i <= 7; $i++) {
        for ($j = 0; $j < 4; $j++) {
            (is_numeric($_POST['p' . $i . $j])) ? $timeString = $timeString . $_POST['p' . $i . $j] : $timeString = $timeString . 'xx';
        }
    }
    $timeString;

    $tempString = NULL;
    for ($i = 0; $i < strlen($timeString); $i++) {
        if (is_numeric($timeString[$i])) {
            $tempString = $tempString . $timeString[$i];
        } else {
            if (!empty($_SESSION['time-string'])) $tempString = $tempString . $_SESSION['time-string'][$i];
            else $tempString = $tempString . $_SESSION['to-be-set-active']['time_string'][$i];
        }
    }
    $_SESSION['time-string'] = $tempString;


    $tempString = NULL;
    for ($i = 1; $i <= 7; $i++) {
        if (isset($_POST['u' . $i])) $tempString = $tempString . '1';
        else $tempString = $tempString . '0';
    }
    for ($i = 1; $i <= 7; $i++) {
        if (isset($_POST['p' . $i])) $tempString = $tempString . '1';
        else $tempString = $tempString . '0';
    }

    $_SESSION['ring-enable'] = $tempString;
}


function autoGenData()
{
    if (!empty($_POST['name'])) $_SESSION['name'] = $_POST['name'];
    if (!empty($_POST['start-hours'])) $_SESSION['sh'] = $_POST['start-hours'];
    if (!empty($_POST['start-minutes'])) $_SESSION['sm'] = $_POST['start-minutes'];
    $classStart = $_SESSION['sh'] . $_SESSION['sm'];
    if (!empty($_POST['class-len'])) $_SESSION['len'] = $_POST['class-len'];
    if (!empty($_POST['break'])) $_SESSION['break'] = $_POST['break'];
    if (!empty($_POST['long-break'])) $_SESSION['l-break'] = $_POST['long-break'];
    if (!empty($_POST['shift-break'])) $_SESSION['s-break'] = $_POST['shift-break'];
    $timeString = '' . $classStart;

    if (
        isset($_SESSION['option-name']) && isset($_SESSION['name']) && isset($_SESSION['time-string']) && isset($_SESSION['sh']) && isset($_SESSION['sm'])
        && isset($_SESSION['len']) && isset($_SESSION['break']) && isset($_SESSION['l-break']) && isset($_SESSION['s-break'])
    ) {
        $_SESSION['option-name'] = $_SESSION['name'];
        header("Location: ./admin.php");
    } else if (empty($_SESSION['name']) || empty($classStart) || empty($_SESSION['len']) || empty($_SESSION['break']) || empty($_SESSION['l-break']) || empty($_SESSION['s-break'])) {
        $_SESSION['option-name'] = NULL;
        $_SESSION['time-string'] = NULL;
        header('Location: ./admin.php?error=emptyfields');
        exit();
    }

    for ($i = 0; $i < 14; $i++) {
        
        $addMinutes = $_SESSION['len'];

        $newMinutes = $timeString[$i * 8 + 2] * 10 + $timeString[$i * 8 + 3] + $addMinutes;
        $newHours = $timeString[$i * 8] * 10 + $timeString[$i * 8 + 1];
        if ($newMinutes >= 60) {
            while ($newMinutes >= 60) {
                $newMinutes -= 60;
                $newHours += 1;
            }
        }
        $newHours %= 24;
        if ($newMinutes < 10) $newMinutes = '0' . $newMinutes;
        if ($newHours < 10) $newHours = '0' . $newHours;

        $timeString = $timeString . $newHours . $newMinutes;
        if ($i == 13) break;

        if ($i % 8 == 6) $addMinutes = $_SESSION['s-break'];
        else if ($i % 8 == 2 || $i % 8 == 9) $addMinutes = $_SESSION['l-break'];
        else $addMinutes = $_SESSION['break'];

        $newMinutes = $timeString[$i * 8 + 6] * 10 + $timeString[$i * 8 + 7] + $addMinutes;
        $newHours = $timeString[$i * 8 + 4] * 10 + $timeString[$i * 8 + 5];
        if ($newMinutes >= 60) {
            while ($newMinutes >= 60) {
                $newMinutes -= 60;
                $newHours += 1;
            }
        }
        $newHours %= 24;
        if ($newMinutes < 10) $newMinutes = '0' . $newMinutes;
        if ($newHours < 10) $newHours = '0' . $newHours;

        $timeString = $timeString . $newHours . $newMinutes;

    }

    $timeString = $timeString;
    if (!empty($_SESSION['name'])) $_SESSION['option-name'] = $_SESSION['name'];
    $_SESSION['time-string'] = $timeString;
    
}
