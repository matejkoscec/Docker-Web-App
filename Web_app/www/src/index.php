<!-- Landing page -->

<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style/style.css">

    <?php

    $mysqli = new mysqli('db', 'root', getenv('MYSQL_ROOT_PASSWORD'), 'information_schema');

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
    echo $mysqli->host_info . "<br>";

    $_string = $_POST["_string"];

    echo $_string;

    // $mysqli->query("DROP DATABASE IF EXISTS rtc_auth_data");
    $mysqli->query("CREATE DATABASE rtc_auth_data;");

    $mysqli = new mysqli('db', 'root', getenv('MYSQL_ROOT_PASSWORD'), 'rtc_auth_data');

    $mysqli->query("CREATE TABLE account ( id int PRIMARY KEY AUTO_INCREMENT NOT NULL, username varchar(20) NOT NULL, password varchar(30) NOT NULL, entry varchar(10) NOT NULL );");

    $mysqli->query("CREATE TABLE time_set ( option_name varchar(50) PRIMARY KEY NOT NULL, time_string TINYTEXT NOT NULL);");

    $mysqli->query("INSERT INTO account (username, password, entry) VALUES ('master', 'master_password', 'master')");
    $mysqli->query("INSERT INTO time_set (option_name, time_string) VALUES ('probno','\#048941098406798')");

    echo '#0706575649834865482#';

    ?>
</head>

<body>
    <div class="wrapper">
        <img src="img/tsrb_logo.png">
        <form action="login.php" method="post" id="login">
            <h2>Korisničko ime:</h2>
            <br><input type="text" name="username">
            <br><br>
            <h2>Lozinka:</h2>
            <br><input type="text" name="password"><br>
        </form>

        <button type="submit" form="form1" value="Submit">Prijava</button>
        <br>
        <br>

        <p>Niste prijavljeni u sustav? <a href="account_creation_page/index.html">Novi račun</a></p>
        <br>
        <p><a href="guest_page/index.html">Nastavi kao gost...</a></p>
    </div>
</body>

</html>