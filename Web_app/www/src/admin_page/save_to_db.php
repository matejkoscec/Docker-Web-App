<?php

session_start();

require '../page_scripts/dbh.php';

if (isset($_POST['db-save']) || isset($_POST['eeprom-save'])) {

    $optionName = $_POST['opn'];
    $timeString = $_POST['u10'] . $_POST['u11'] . '.' . $_POST['u12'] . $_POST['u13'] . '.' .
        $_POST['u20'] . $_POST['u21'] . '.' . $_POST['u22'] . $_POST['u23'] . '.' .
        $_POST['u30'] . $_POST['u31'] . '.' . $_POST['u32'] . $_POST['u33'] . '.' .
        $_POST['u40'] . $_POST['u41'] . '.' . $_POST['u42'] . $_POST['u43'] . '.' .
        $_POST['u50'] . $_POST['u51'] . '.' . $_POST['u52'] . $_POST['u53'] . '.' .
        $_POST['u60'] . $_POST['u61'] . '.' . $_POST['u62'] . $_POST['u63'] . '.' .
        $_POST['u70'] . $_POST['u71'] . '.' . $_POST['u72'] . $_POST['u73'] . '.' .
        $_POST['p10'] . $_POST['p11'] . '.' . $_POST['p12'] . $_POST['p13'] . '.' .
        $_POST['p20'] . $_POST['p21'] . '.' . $_POST['p22'] . $_POST['p23'] . '.' .
        $_POST['p30'] . $_POST['p31'] . '.' . $_POST['p32'] . $_POST['p33'] . '.' .
        $_POST['p40'] . $_POST['p41'] . '.' . $_POST['p42'] . $_POST['p43'] . '.' .
        $_POST['p50'] . $_POST['p51'] . '.' . $_POST['p52'] . $_POST['p53'] . '.' .
        $_POST['p60'] . $_POST['p61'] . '.' . $_POST['p62'] . $_POST['p63'] . '.' .
        $_POST['p70'] . $_POST['p71'] . '.' . $_POST['p72'] . $_POST['p73'] . '.' . '#';

    if (!empty($optionName)) {
        if (strcmp($optionName, $_SESSION['option-name']) != 0) {
            $_SESSION['option-name'] = $optionName;
        }
    }

    if (!(isset($_SESSION['selected-button-value-1']) || isset($_SESSION['selected-button-value-2']) || isset($_SESSION['selected-button-value-3']) || isset($_SESSION['selected-button-value-4']))) {
        checkRecords();
    } else $_SESSION['option-name'] = $_SESSION['to-be-set-active']['option_name'];


    $tempString = '';
    for ($i = 0; $i < strlen($timeString); $i++) {
        if ($timeString[$i] == '.') {
            if (is_numeric($timeString[$i - 1]) && is_numeric($timeString[$i - 2]) && is_numeric($timeString[$i - 3]) && is_numeric($timeString[$i - 4])) {
                $tempString = $tempString . $timeString[$i - 4] . $timeString[$i - 3] . $timeString[$i - 2] . $timeString[$i - 1];
            } else $tempString = $tempString . 'xxxx';
        }
    }

    for ($i = 3; $i < strlen($_SESSION['time-string']); $i += 4) {
        if ($tempString[$i] != 'x') {
            $_SESSION['time-string'][$i] = $tempString[$i];
            $_SESSION['time-string'][$i - 1] = $tempString[$i - 1];
            $_SESSION['time-string'][$i - 2] = $tempString[$i - 2];
            $_SESSION['time-string'][$i - 3] = $tempString[$i - 3];
        }
    }

    $tempString = '';
    for ($i = 1; $i <= 7; $i++) {
        if (isset($_POST['u' . $i])) $tempString = $tempString . '1';
        else $tempString = $tempString . '0';
    }
    for ($i = 1; $i <= 7; $i++) {
        if (isset($_POST['p' . $i])) $tempString = $tempString . '1';
        else $tempString = $tempString . '0';
    }

    $_SESSION['ring-enable'] = $tempString;

    if (isset($_SESSION['selected-button-value-1']) || isset($_SESSION['selected-button-value-2']) || isset($_SESSION['selected-button-value-3']) || isset($_SESSION['selected-button-value-4'])) {
        if (isset($_SESSION['selected-button-value-1'])) $sql = "UPDATE time_set SET option_name = ?, time_string = ?, ring_enable = ? WHERE option_name = '" . $_SESSION['to-be-set-active']['option_name'] . "';";
        if (isset($_SESSION['selected-button-value-2'])) $sql = "UPDATE eeprom_mirror SET option_name = ?, time_string = ?, ring_enable = ? WHERE option_name = '" . $_SESSION['to-be-set-active']['option_name'] . "';";
        if (isset($_SESSION['selected-button-value-3'])) $sql = "UPDATE settings_by_date SET option_name = ?, time_string = ?, ring_enable = ? WHERE option_name = '" . $_SESSION['to-be-set-active']['option_name'] . "';";
        if (isset($_SESSION['selected-button-value-4'])) $sql = "UPDATE active_setting SET option_name = ?, time_string = ?, ring_enable = ? WHERE option_name = '" . $_SESSION['to-be-set-active']['option_name'] . "';";
    } else {
        if (isset($_POST['db-save'])) $sql = "INSERT INTO time_set (option_name, time_string, ring_enable) VALUES (?, ?, ?);";
        if (isset($_POST['eeprom-save'])) $sql = "INSERT INTO eeprom_mirror (option_name, time_string, ring_enable) VALUES (?, ?, ?);";
    }
    $stmt = mysqli_stmt_init($conn);

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
    header("Location: ./admin.php");
    exit();
}

if (isset($_POST['delete'])) {

    $_SESSION['to-be-set-active']['option_name'] = NULL;

    if (isset($_SESSION['selected-button-value-1'])) $sql = "DELETE FROM time_set WHERE option_name = ?";
    if (isset($_SESSION['selected-button-value-2'])) $sql = "DELETE FROM eeprom_mirror WHERE option_name = ?";
    if (isset($_SESSION['selected-button-value-3'])) $sql = "DELETE FROM settings_by_date WHERE date_active = ?";
    if (isset($_SESSION['selected-button-value-4'])) $sql = "DELETE FROM active_setting WHERE option_name = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ./admin.php?error=sqlerror");
        exit();
    } else {
        if (isset($_SESSION['selected-button-value-1'])) mysqli_stmt_bind_param($stmt, "s", $_SESSION['selected-button-value-1']);
        if (isset($_SESSION['selected-button-value-2'])) mysqli_stmt_bind_param($stmt, "s", $_SESSION['selected-button-value-2']);
        if (isset($_SESSION['selected-button-value-3'])) mysqli_stmt_bind_param($stmt, "s", $_SESSION['date-to-parse']);
        if (isset($_SESSION['selected-button-value-4'])) mysqli_stmt_bind_param($stmt, "s", $_SESSION['selected-button-value-4']);
        mysqli_stmt_execute($stmt);
    }
    header("Location: ./admin.php");
    exit();
}


if (isset($_POST['auto-gen'])) {

    $_SESSION['selected-button-value-1'] = NULL;
    $_SESSION['selected-button-value-2'] = NULL;
    $_SESSION['selected-button-value-3'] = NULL;
    $_SESSION['selected-button-value-4'] = NULL;

    $changesMade = 0;

    if (!empty($_POST['name'])) $_SESSION['name'] = $_POST['name'];
    if (!empty($_POST['start-hours'])) $_SESSION['sh'] = $_POST['start-hours'];
    if (!empty($_POST['start-minutes'])) $_SESSION['sm'] = $_POST['start-minutes'];
    $classStart = $_SESSION['sh'] . $_SESSION['sm'];
    if (!empty($_POST['class-len'])) $_SESSION['len'] = $_POST['class-len'];
    if (!empty($_POST['break'])) $_SESSION['break'] = $_POST['break'];
    if (!empty($_POST['long-break'])) $_SESSION['l-break'] = $_POST['long-break'];
    if (!empty($_POST['shift-break'])) $_SESSION['s-break'] = $_POST['shift-break'];
    $timeString = '' . $classStart;
    /*echo $_SESSION['name'];
    echo $_SESSION['sh'];
    echo $_SESSION['sm'];
    echo $_SESSION['len'];
    echo $_SESSION['break'];
    echo $_SESSION['l-break'];
    echo $_SESSION['s-break'];*/

    if (empty($_POST['name']) && empty($_POST['start-hours']) && empty($_POST['start-minutes']) && empty($_POST['class-len']) && empty($_POST['break']) && empty($_POST['long-break']) && empty($_POST['shift-break'])) {
    }

    if (
        isset($_SESSION['option-name']) && isset($_SESSION['name']) && isset($_SESSION['time-string']) && isset($_SESSION['sh']) && isset($_SESSION['sm'])
        && isset($_SESSION['len']) && isset($_SESSION['break']) && isset($_SESSION['l-break']) && isset($_SESSION['s-break'])
    ) {
        $_SESSION['option-name'] = $_SESSION['name'];
        header("Location: ./admin.php");
    }
    else if (empty($_SESSION['name']) || empty($classStart) || empty($_SESSION['len']) || empty($_SESSION['break']) || empty($_SESSION['l-break']) || empty($_SESSION['s-break'])) {
        $_SESSION['option-name'] = NULL;
        $_SESSION['time-string'] = NULL;
        header('Location: ./admin.php?error=emptyfields');
        exit();
    }

    for ($i = 0; $i < 14; $i++) {
        $addMinutes = $_SESSION['len'];
        $newMinutes = $timeString[$i * 8 + 2] * 10 + $timeString[$i * 8 + 3] + $addMinutes;
        if ($newMinutes >= 60) {
            $newMinutes %= 60;
            $newHours = $timeString[$i * 8] * 10 + $timeString[$i * 8 + 1] + 1;
        } else $newHours = $timeString[$i * 8] * 10 + $timeString[$i * 8 + 1];
        if ($newMinutes < 10) $newMinutes = '0' . $newMinutes;
        if ($newHours < 10) $newHours = '0' . $newHours;

        $timeString = $timeString . $newHours . $newMinutes;
        if ($i == 13) break;

        if ($i % 8 == 6) $addMinutes = $_SESSION['s-break'];
        else if ($i % 8 == 2 || $i % 8 == 9) $addMinutes = $_SESSION['l-break'];
        else $addMinutes = $_SESSION['break'];

        $newMinutes = $timeString[$i * 8 + 6] * 10 + $timeString[$i * 8 + 7] + $addMinutes;
        if ($newMinutes >= 60) {
            $newMinutes %= 60;
            $newHours = $timeString[$i * 8 + 4] * 10 + $timeString[$i * 8 + 5] + 1;
        } else $newHours = $timeString[$i * 8 + 4] * 10 + $timeString[$i * 8 + 5];
        if ($newMinutes < 10) $newMinutes = '0' . $newMinutes;
        if ($newHours < 10) $newHours = '0' . $newHours;

        $timeString = $timeString . $newHours . $newMinutes;
    }
    $timeString = $timeString . '#';
    if (!empty($_SESSION['name'])) $_SESSION['option-name'] = $_SESSION['name'];
    $_SESSION['time-string'] = $timeString;
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
