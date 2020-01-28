<?php

if (isset($_POST['login-submit'])) {    

    require 'dbh.php';

    $username = $_POST['uid'];
    $password = $_POST['pwd'];

    if (empty($username) || empty($password)) {
        header("Location: ../index.php?error=emptyfields");
        exit();
    }
    else {
        
        $sql = "SELECT * FROM accounts WHERE user=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../index.php?error=sqlerror");
            exit();
        }
        else {

            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $pwdCheck = password_verify($password, $row['userPassword']);
                if ($pwdCheck == true) {
                    session_start();
                    $_SESSION['user'] = $row['user'];
                    $_SESSION['entry'] = $row['entry'];
                    header("Location: ../user_page/user.php");
                    exit();
                }
                else {
                    header("Location: ../index.php?error=wrongpwd");
                    exit();
                }
            }
            else {
                header("Location: ../index.php?error=nouser");
                exit();
            }

        }

    }

}
else {
    header("Location: ../index.php");
    exit();
}