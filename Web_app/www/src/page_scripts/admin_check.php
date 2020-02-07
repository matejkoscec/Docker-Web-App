<?php

$username = $_SESSION['user'];
$entry = $_SESSION['entry'];

if (empty($username) || empty($entry)) {
    header("Location: ../user_page/user.php");
    exit();
}

$sql = "SELECT user FROM accounts WHERE user=?";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../account_creation_page/account_creation.php?error=sqlerror");
    exit();
} else {

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $resultCheck = mysqli_stmt_num_rows($stmt);
    if (!($resultCheck > 0)) {
        session_unset();
        session_destroy();
        header("Location: ../user_page/user.php");
        exit();
    }
    if (!($entry == 'admin')) {
        header("Location: ../user_page/user.php");
        exit();
    }
}