<?php

require '../page_scripts/dbh.php';

if (isset($_POST['db-save']) || isset($_POST['eeprom-save'])) {

    $optionName = $_POST['opn'];
    $timeString = $_POST['u10'] . $_POST['u11'] . $_POST['u12'] . $_POST['u13'] .
        $_POST['u20'] . $_POST['u21'] . $_POST['u22'] . $_POST['u23'] .
        $_POST['u30'] . $_POST['u31'] . $_POST['u32'] . $_POST['u33'] .
        $_POST['u40'] . $_POST['u41'] . $_POST['u42'] . $_POST['u43'] .
        $_POST['u50'] . $_POST['u51'] . $_POST['u52'] . $_POST['u53'] .
        $_POST['u60'] . $_POST['u61'] . $_POST['u62'] . $_POST['u63'] .
        $_POST['u70'] . $_POST['u71'] . $_POST['u72'] . $_POST['u73'] .
        $_POST['p10'] . $_POST['p11'] . $_POST['p12'] . $_POST['p13'] .
        $_POST['p20'] . $_POST['p21'] . $_POST['p22'] . $_POST['p23'] .
        $_POST['p30'] . $_POST['p31'] . $_POST['p32'] . $_POST['p33'] .
        $_POST['p40'] . $_POST['p41'] . $_POST['p42'] . $_POST['p43'] .
        $_POST['p50'] . $_POST['p51'] . $_POST['p52'] . $_POST['p53'] .
        $_POST['p60'] . $_POST['p61'] . $_POST['p62'] . $_POST['p63'] .
        $_POST['p70'] . $_POST['p71'] . $_POST['p72'] . $_POST['p73'] . '#';

    if (isset($_POST['db-save'])) $sql = "INSERT INTO time_set (option_name, time_string) VALUES (?, ?);";
    if (isset($_POST['eeprom-save'])) $sql = "INSERT INTO eeprom_mirror (option_name, time_string) VALUES (?, ?);";
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
else exit();