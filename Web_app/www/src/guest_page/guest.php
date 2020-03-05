<?php

require '../page_scripts/dbh.php';

?>


<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style/guest_page_style.css">

</head>

<body>
    <div class="wrapper">

        <h1>Raspored zvona</h1>
        <br>
        <?php

        echo '<h2>' . date("d.m.Y", time()) . '</h2>';

        ?>

        <br>
        <br>
        <br>
        <h3>Naziv: </h3>
        <?php

        $sql = "SELECT * FROM active_setting WHERE id = 1";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        require '../page_scripts/time_display.php';

        ?>


    </div>

    <p><a href="../index.php">Povratak na početnu stranicu</a></p>
</body>

</html>