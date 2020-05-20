<?php

session_start();

require '../page_scripts/dbh.php';

require '../page_scripts/admin_check.php';


if (isset($_POST['weekend-ignore'])) {
    $_SESSION['weekend-ignore'] = !$_SESSION['weekend-ignore'];
    getDateRange();
}

if (isset($_POST['date-select'])) {
    $_SESSION['date-select-active'] = !$_SESSION['date-select-active'];
    $_SESSION['range-select-active'] = false;

    unset($_SESSION['dateArray']);
    $_SESSION['dateArray'] = array();
    $_SESSION['control-index'] = 0;
    $_SESSION['date1'] = '';
    $_SESSION['date2'] = '';
}


if (isset($_POST['date-range-select'])) {
    $_SESSION['range-select-active'] = !$_SESSION['range-select-active'];
    $_SESSION['date-select-active'] = false;

    unset($_SESSION['dateArray']);
    $_SESSION['dateArray'] = array();
    $_SESSION['control-index'] = 0;
    $_SESSION['date1'] = '';
    $_SESSION['date2'] = '';
}


if (isset($_POST['confirm'])) {

    for ($i = 0; $i < count($_SESSION['dateArray']); $i++) {
        $sql = "DELETE FROM settings_by_date WHERE date_active = '" . $_SESSION['dateArray'][$i] . "'";
        mysqli_query($conn, $sql);
    }

    $sql = "INSERT INTO settings_by_date (date_active, option_name, time_string, ring_enable) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ./settings_by_date.php?error=sqlerror");
        exit();
    } else {
        for ($i = 0; $i < count($_SESSION['dateArray']); $i++) {
            mysqli_stmt_bind_param($stmt, "ssss", $_SESSION['dateArray'][$i], $_SESSION['to-be-set-active']['option_name'], $_SESSION['to-be-set-active']['time_string'], $_SESSION['to-be-set-active']['ring_enable']);
            mysqli_stmt_execute($stmt);
        }
    }

    $_SESSION['date-select-active'] = false;
    $_SESSION['range-select-active'] = false;
    unset($_SESSION['dateArray']);
    $_SESSION['dateArray'] = array();
    $_SESSION['control-index'] = 0;
    $_SESSION['date1'] = '';
    $_SESSION['date2'] = '';
}


if (isset($_POST['delete'])) {
    for ($i = 0; $i < count($_SESSION['dateArray']); $i++) {
        $sql = "DELETE FROM settings_by_date WHERE date_active = '" . $_SESSION['dateArray'][$i] . "'";
        mysqli_query($conn, $sql);
    }

    $_SESSION['date-select-active'] = false;
    $_SESSION['range-select-active'] = false;
    unset($_SESSION['dateArray']);
    $_SESSION['dateArray'] = array();
    $_SESSION['control-index'] = 0;
    $_SESSION['date1'] = '';
    $_SESSION['date2'] = '';
}

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
        <p><a class="setup" href="./admin.php">↶ Povratak na prethodnu stranicu</a></p>
        <br>
        <section class="section1">
            <div class="menus">
                <form method="post" id="menu">
                    <h2>Spremljene postavke</h2>
                    <div class="vertical-menu">
                        <?php

                        $sql = "SELECT option_name FROM time_set;";
                        $result = mysqli_query($conn, $sql);

                        $i = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<button type="submit" class="vm-option" form="menu" value="' . $row['option_name'] . '" name="ts-button' . $i . '">';
                            echo $row['option_name'];
                            echo '</button>';
                            $i++;
                        }

                        ?>
                    </div>

                    <h2>Arduino memorija</h2>
                    <div class="vertical-menu">
                        <?php

                        $sql = "SELECT option_name FROM eeprom_mirror;";
                        $result = mysqli_query($conn, $sql);

                        $i = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<button type="submit" class="vm-option" form="menu" value="' . $row['option_name'] . '" name="eeprom-button' . $i . '">';
                            echo $row['option_name'];
                            echo '</button>';
                            $i++;
                        }

                        ?>
                    </div>
                    <br>
                    <?php buttonDisplay(); ?>

                </form>
            </div>
            <div class="calendar-wrapper">
                <?php

                if (!isset($_SESSION['time'])) $_SESSION['time'] = date('Y-m', time());
                calendarHandler();

                require '../page_scripts/calendar.php';

                ?>

            </div>
        </section>
        <br>
        <section class="section2">
            <div>
                <?php require '../page_scripts/time_display.php'; ?>
            </div>
        </section>

        <footer>

        </footer>
    </div>
</body>

</html>


<?php

function calendarHandler()
{
    require '../page_scripts/dbh.php';

    $numOfResults = 32;
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


            if ($_SESSION['date-select-active']) {
                getDates();
                return;
            }


            if ($_SESSION['control-index'] == 0) $_SESSION['control-index'] = 1;
            if ($_SESSION['range-select-active']) {
                getDateEndpoints();
                getDateRange();
                return;
            }


            if (isset($_SESSION['selected-button-value-1'])) {
                $sql = 'SELECT * FROM time_set WHERE option_name = \'' . $_SESSION['selected-button-value-1'] . '\'';
            }
            if (isset($_SESSION['selected-button-value-2'])) {
                $sql = 'SELECT * FROM eeprom_mirror WHERE option_name = \'' . $_SESSION['selected-button-value-2'] . '\'';
            }
            if (isset($_SESSION['selected-button-value-3'])) {
                if ($_SESSION['selected-button-value-3'] < 10) $_SESSION['selected-button-value-3'] = '0' . $_SESSION['selected-button-value-3'];
                $dateToParse = $_SESSION['calendar-date'] . '-' . $_SESSION['selected-button-value-3'];
                $_SESSION['date-to-parse'] = $dateToParse;

                $sql = 'SELECT * FROM settings_by_date WHERE date_active = \'' . $dateToParse . '\'';
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
}


function buttonDisplay()
{
    $calendarButtonSet = false;
    if (!($_SESSION['date-select-active'] || $_SESSION['range-select-active'])) {
        for ($i = 1; $i <= 31; $i++) {
            if (isset($_POST['calendar-button' . $i])) {
                $calendarButtonSet = true;
                break;
            }
        }
    }

    if (!$calendarButtonSet) {
        echo '<button class="select" type="submit" form="menu" name="date-select" ';
        if ($_SESSION['date-select-active']) echo 'style="background-color: red"';
        echo '>Odaberi datum(e)</button>';
        echo '<br>';
        echo '<button class="select" type="submit" form="menu" name="date-range-select" ';
        if ($_SESSION['range-select-active']) echo 'style="background-color: red"';
        echo '>Odaberi raspon datuma</button>';
    }

    if ($_SESSION['date-select-active'] || $_SESSION['range-select-active']) {
        echo '<br>';
        echo '<br>';
        if ($_SESSION['range-select-active']) {
            echo '<p><button class="weekend-ignore-check" name="weekend-ignore" value="1">';
            if (!$_SESSION['weekend-ignore']) echo '✔';
            else echo '&nbsp&nbsp&nbsp';
            echo '</button> Zanemari subote i nedjelje</p>';
        }
        echo '<br>';
        echo '<button class="select" type="submit" form="menu" name="confirm">Spremi promjene</button>';
        echo '<button class="select" type="submit" form="menu" name="delete">Obriši odabrano</button>';
    }
}


function getDates()
{
    if ($_SESSION['selected-button-value-3'] < 10) $_SESSION['selected-button-value-3'] = '0' . $_SESSION['selected-button-value-3'];
    if (!empty($_SESSION['selected-button-value-3'])) $selectedDate = $_SESSION['calendar-date'] . '-' . $_SESSION['selected-button-value-3'];

    $dateRemoved = false;
    for ($i = 0; $i < count($_SESSION['dateArray']); $i++) {
        if ($_SESSION['dateArray'][$i] == $selectedDate) {
            $_SESSION['dateArray'][$i] = 'unset';
            $dateRemoved = true;
        }
    }
    if (!$dateRemoved) $_SESSION['dateArray'][count($_SESSION['dateArray'])] = $selectedDate;
}


function getDateEndpoints()
{
    if ($_SESSION['selected-button-value-3'] < 10) $_SESSION['selected-button-value-3'] = '0' . $_SESSION['selected-button-value-3'];
    if (!empty($_SESSION['selected-button-value-3'])) $selectedDate = $_SESSION['calendar-date'] . '-' . $_SESSION['selected-button-value-3'];

    if ($selectedDate == $_SESSION['date1']) {
        $_SESSION['date1'] = '';
        $_SESSION['control-index'] = 1;
    } else if ($selectedDate == $_SESSION['date2']) {
        $_SESSION['date2'] = '';
        $_SESSION['control-index'] = 2;
    } else {
        if ($_SESSION['control-index'] == 1) {
            $_SESSION['date1'] = $selectedDate;
            $_SESSION['control-index'] = 2;
        } else if ($_SESSION['control-index'] == 2) {
            $_SESSION['date2'] = $selectedDate;
            $_SESSION['control-index'] = 0;
        }
    }
}


function getDateRange()
{
    $startDate = $_SESSION['date1'];
    $endDate = $_SESSION['date2'];

    unset($_SESSION['dateArray']);
    $_SESSION['dateArray'] = array();

    if (empty($startDate)) {
        $startDate = $endDate;
    }
    if (empty($endDate)) {
        $endDate = $startDate;
    }

    if (strtotime($startDate) > strtotime($endDate)) {
        $temp = $startDate;
        $startDate = $endDate;
        $endDate = $temp;
    }

    if ($startDate == $endDate) $_SESSION['dateArray'][0] = $startDate;
    else {
        $i = 0;
        while ($startDate != date('Y-m-d', strtotime($endDate . '+1 day'))) {
            if ($_SESSION['weekend-ignore']) {
                $_SESSION['dateArray'][$i] = $startDate;
                $i++;
            } else {
                if (!(date_format(date_create($startDate), 'D') == 'Sat' || date_format(date_create($startDate), 'D') == 'Sun')) {
                    $_SESSION['dateArray'][$i] = $startDate;
                    $i++;
                }
            }
            $startDate = date('Y-m-d', strtotime($startDate . '+1 day'));
        }
    }
}
