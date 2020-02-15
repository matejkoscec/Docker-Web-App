<?php

session_start();

require '../page_scripts/dbh.php';

/*if (isset($_POST['db-save']) || isset($_POST['eeprom-save'])) {
    if ($_SESSION['option-name'] != '' && $_SESSION['time-string'] != '') {

        $optionName = $_SESSION['option-name'];
        $timeString = $_SESSION['time-string'];
        if (isset($_POST['db-save-1'])) $sql = "INSERT INTO time_set (option_name, time_string) VALUES (?, ?);";
        if (isset($_POST['eeprom-save-1'])) $sql = "INSERT INTO eeprom_mirror (option_name, time_string) VALUES (?, ?);";

        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ./admin.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $optionName, $timeString);
            mysqli_stmt_execute($stmt);
        }
        header("Location: ./admin.php");
        exit();
    }
}*/

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

    if (isset($_POST['db-save'])) $sql = "INSERT INTO time_set (option_name, time_string) VALUES (?, ?);";
    if (isset($_POST['eeprom-save'])) $sql = "INSERT INTO eeprom_mirror (option_name, time_string) VALUES (?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ./admin.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "ss", $_SESSION['option-name'], $_SESSION['time-string']);
        mysqli_stmt_execute($stmt);
    }
    header("Location: ./admin.php");
    exit();
}


if (isset($_POST['auto-gen'])) {
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['sh'] = $_POST['start-hours'];
    $_SESSION['sm'] = $_POST['start-minutes'];
    $classStart = $_POST['start-hours'] . $_POST['start-minutes'];
    $_SESSION['len'] = $_POST['class-len'];
    $_SESSION['break'] = $_POST['break'];
    $_SESSION['l-break'] = $_POST['long-break'];
    $_SESSION['s-break'] = $_POST['shift-break'];
    $timeString = '' . $classStart;

    if (empty($_SESSION['name']) || empty($classStart) || empty($_SESSION['len']) || empty($_SESSION['break']) || empty($_SESSION['l-break']) || empty($_SESSION['s-break'])) {
        $_SESSION['option-name'] = NULL;
        $_SESSION['time-string'] = NULL;
        header('Location: ./admin.php?error=emptyfields');
        exit();
    } else {
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
    }
} else {
    header("Location: ./admin.php");
    exit();
}
