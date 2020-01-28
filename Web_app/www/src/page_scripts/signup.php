<?php

if (isset($_POST['signup-submit'])) {

    require 'dbh.php';

    $username = $_POST['uid'];
    $password = $_POST['pwd'];
    $passwordRepeat = $_POST['pwd-repeat'];
    $entry = $_POST['entry'];

    if (empty($username) || empty($password) || empty($passwordRepeat) || empty($entry)) {
        header("Location: ../account_creation_page/account_creation.php?error=emptyfields&uid=".$username);
        exit();
    }
    else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        header("Location: ../account_creation_page/account_creation.php?error=invaliduid");
        exit();
    }
    else if ($password != $passwordRepeat) {
        header("Location: ../account_creation_page/account_creation.php?error=passwordcheck&uid=".$username);
        exit();
    }
    else {

        $sql = "SELECT user FROM accounts WHERE user=?";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../account_creation_page/account_creation.php?error=sqlerror");
            exit();
        }
        else {
            
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt);
            if ($resultCheck > 0) {
                header("Location: ../account_creation_page/account_creation.php?error=usertaken");
                exit();
            }
            else {

                $sql = "INSERT INTO accounts (user, userPassword, entry) VALUES (?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: ../account_creation_page/account_creation.php?error=sqlerror");
                    exit();
                }
                else {
                    
                    $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt, "sss", $username, $hashedPwd, $entry);
                    mysqli_stmt_execute($stmt);
                    header("Location: ../account_creation_page/account_creation.php?signup=success");
                    exit();

                }

            }

        }

    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

}
else {
    header("Location: ../account_creation_page/account_creation.php");
    exit();
}