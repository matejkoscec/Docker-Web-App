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
        <?php echo '<h2>' . date("d.m.Y", time()) . '</h2>'; ?>

        <br><br><br>

        <div class="time-display-wrapper">
            <div class="time-display">
                <?php

                $sql = "SELECT * FROM active_setting WHERE id = 1;";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);

                require '../page_scripts/user_time_display.php';

                ?>
            </div>
        </div>
        <br><br>
        <a class="setup" href="../index.php">Povratak na početnu stranicu</a>
    </div>
</body>

</html>