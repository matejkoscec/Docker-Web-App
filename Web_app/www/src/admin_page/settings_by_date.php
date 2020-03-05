<?php

session_start();

require '../page_scripts/dbh.php';

require '../page_scripts/admin_check.php';

?>

<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style/settings_by_date_style.css">
</head>

<body>
    <div class="wrapper">
        <header>
            <?php require '../page_scripts/header.php'; ?>
        </header>

        <section class="section1">
            <div class="menus">
                <form method="post" id="menu">
                    <h2>Spremljene postavke</h2>
                    <div class="vertical_menu">
                        <?php

                        $sql = "SELECT option_name FROM time_set;";
                        $result = mysqli_query($conn, $sql);

                        $i = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<button type="submit" class="vm_option" form="menu" value="' . $row['option_name'] . '" name="ts-button' . $i . '">';
                            echo $row['option_name'];
                            echo '</button>';
                            $i++;
                        }

                        ?>
                    </div>

                    <h2>Arduino memorija</h2>
                    <div class="vertical_menu">
                        <?php

                        $sql = "SELECT option_name FROM eeprom_mirror;";
                        $result = mysqli_query($conn, $sql);

                        $i = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<button type="submit" class="vm_option" form="menu" value="' . $row['option_name'] . '" name="eeprom-button' . $i . '">';
                            echo $row['option_name'];
                            echo '</button>';
                            $i++;
                        }

                        ?>
                    </div>
                </form>
                <button class="select" type="submit" form="" name="date-select">Odaberi datum/raspon datuma</button>
            </div>
            <div class="calendar-wrapper">
                <?php require '../page_scripts/calendar.php'; ?>
            </div>
        </section>

        <section class="section2">
            <?php

            $numOfResults = 1;
            $sql = "SELECT * FROM time_set;";
            $result = mysqli_query($conn, $sql);
            $numOfResults += mysqli_num_rows($result);
            $sql = "SELECT * FROM eeprom_mirror;";
            $result = mysqli_query($conn, $sql);
            $numOfResults += mysqli_num_rows($result);

            for ($i = 0; $i < $numOfResults; $i++) {
                if (isset($_POST['ts-button' . $i]) || isset($_POST['eeprom-button' . $i]) || isset($_POST['calendar-button' . $i])) {
                    for ($i = 0; $i < $numOfResults; $i++) {
                        if (isset($_POST['ts-button' . $i])) {
                            $_SESSION['selected-button-value-1'] = $_POST['ts-button' . $i];
                            $_SESSION['selected-button-value-2'] = NULL;
                            $_SESSION['selected-button-value-3'] = NULL;
                        }
                    }

                    for ($i = 0; $i < $numOfResults; $i++) {
                        if (isset($_POST['eeprom-button' . $i])) {
                            $_SESSION['selected-button-value-2'] = $_POST['eeprom-button' . $i];
                            $_SESSION['selected-button-value-1'] = NULL;
                            $_SESSION['selected-button-value-3'] = NULL;
                        }
                    }

                    for ($i = 1; $i <= 31; $i++) {
                        if (isset($_POST['calendar-button' . $i])) {
                            $_SESSION['selected-button-value-3'] = $_POST['calendar-button' . $i];
                            $_SESSION['selected-button-value-1'] = NULL;
                            $_SESSION['selected-button-value-2'] = NULL;
                        }
                    }

                    if (isset($_SESSION['selected-button-value-1'])) {
                        $sql = 'SELECT * FROM time_set WHERE option_name = \'' . $_SESSION['selected-button-value-1'] . '\'';
                    }
                    if (isset($_SESSION['selected-button-value-2'])) {
                        $sql = 'SELECT * FROM eeprom_mirror WHERE option_name = \'' . $_SESSION['selected-button-value-2'] . '\'';
                    }
                    if (isset($_SESSION['selected-button-value-3'])) {
                        if ($_SESSION['selected-button-value-3'] < 10) {
                            if (strlen($_SESSION['selected-button-value-3']) < 2) $_SESSION['selected-button-value-3'] = '0' . $_SESSION['selected-button-value-3'];
                        }
                        $dateToParse = $_SESSION['calendar-date'] . '-' . $_SESSION['selected-button-value-3'];
                        $sql = 'SELECT * FROM settings_by_date WHERE date_active = \'' . $dateToParse . '\'';
                        $_SESSION['date-to-parse'] = $dateToParse;
                    }
                    $result = mysqli_query($conn, $sql);

                    if (!empty($result)) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            if ($row['option_name'] == $_SESSION['selected-button-value-1']) break;
                            if ($row['option_name'] == $_SESSION['selected-button-value-2']) break;
                            if ($row['option_name'] == $_SESSION['selected-button-value-3']) break;
                            if ($row['option_name'] == $_SESSION['selected-button-value-4']) break;
                            if ($row['date_active'] == $dateToParse) break;
                        }
                        $_SESSION['to-be-set-active'] = $row;
                    }
                }
            }

            require '../page_scripts/time_display.php';

            ?>
        </section>

        <footer>

        </footer>
    </div>
</body>

</html>


<?php
