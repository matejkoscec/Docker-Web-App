<?php

session_start();

require '../page_scripts/dbh.php';

require '../page_scripts/admin_check.php';

if (!isset($_SESSION['time1'])) $_SESSION['time1'] = date('Y-m', time());
if (!isset($_SESSION['time2'])) $_SESSION['time2'] = date('Y-m', time());

?>

<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style/admin_page_style.css">
</head>

<body>
    <div class="wrapper">

        <header style="background-color: black; color: white;">
            header
        </header>

        <section class="section1">
            <div class="settings">
                <form action="save_to_db.php" method="post" id="auto-time-set">
                    <h2>Naziv postavke</h2>
                    <input class="option-name" type="text" maxlength="50" name="name" placeholder="<?php echo $_SESSION['option-name']; ?>">
                    <h2>Početak jutarnje smjene</h2>
                    <input type="text" name="start-hours" maxlength="2" placeholder="<?php echo $_SESSION['sh']; ?>">
                    :
                    <input type="text" name="start-minutes" maxlength="2" placeholder="<?php echo $_SESSION['sm']; ?>">
                    <h2>Trajanje sata (min)</h2>
                    <input class="option-name" type="text" name="class-len" maxlength="50" placeholder="<?php echo $_SESSION['len']; ?>">
                    <h2>Trajanje malih odmora (min)</h2>
                    <input class="option-name" type="text" name="break" maxlength="50" placeholder="<?php echo $_SESSION['break']; ?>">
                    <h2>Trajanje velikih odmora (min)</h2>
                    <input class="option-name" type="text" name="long-break" maxlength="50" placeholder="<?php echo $_SESSION['l-break']; ?>">
                    <h2>Pauza između smjena (min)</h2>
                    <input class="option-name" type="text" name="shift-break" maxlength="50" placeholder="<?php echo $_SESSION['s-break']; ?>">
                    <br>
                    <button class="select" type="submit" form="auto-time-set" name="auto-gen">Generiraj</button>
                    <button class="select" type="reset" form="auto-time-set" style="margin-left: 70px;">Očisti unos</button>
                </form>
            </div>
            <div class="time-display">
                <?php
                $row['option_name'] = $_SESSION['option-name'];
                $row['time_string'] = $_SESSION['time-string'];
                require '../page_scripts/time_display.php';
                ?>
            </div>
        </section>
        <button id="baton" onclick="document.getElementById('baton').style.background = 'green';">Aaaaaaa</button>
        <section class="section2">
            <div class="manual-setup-tables">
                <form action="save_to_db.php" method="post" id="time_set">
                    <?php
                    $i = 0;
                    $pm = substr($row['time_string'], -(int) (strlen($row['time_string']) / 2) - 1);
                    $am = $row['time_string'];
                    $am = str_replace($pm, '', $am);
                    ?>
                    <h2>Naziv postavke</h2>
                    <input class="option-name" type="text" maxlength="50" name="opn" placeholder="<?php echo $_SESSION['option-name']; ?>">
                    <br>
                    <table class="time-setup">
                        <th>Jutarnja smjena</th>
                        <th>Zvoni</th>
                        <tr>
                            <td>
                                <h3>1. sat: </h3>
                                <input type="text" name="u10" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="u11" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3> - </h3>
                                <input type="text" name="u12" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="u13" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                            </td>
                            <td><input type="checkbox" name="u1" value="1" checked></td>
                        </tr>
                        <tr>
                            <td>
                                <h3>2. sat: </h3>
                                <input type="text" name="u20" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="u21" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3> - </h3>
                                <input type="text" name="u22" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="u23" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                            </td>
                            <td><input type="checkbox" name="u2" value="1" checked></td>
                        </tr>
                        <tr>
                            <td>
                                <h3>3. sat: </h3>
                                <input type="text" name="u30" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="u31" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3> - </h3>
                                <input type="text" name="u32" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="u33" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                            </td>
                            <td><input type="checkbox" name="u3" value="1" checked></td>
                        </tr>
                        <tr>
                            <td>
                                <h3>4. sat: </h3>
                                <input type="text" name="u40" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="u41" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3> - </h3>
                                <input type="text" name="u42" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="u43" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                            </td>
                            <td><input type="checkbox" name="u4" value="1" checked></td>
                        </tr>
                        <tr>
                            <td>
                                <h3>5. sat: </h3>
                                <input type="text" name="u50" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="u51" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3> - </h3>
                                <input type="text" name="u52" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="u53" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                            </td>
                            <td><input type="checkbox" name="u5" value="1" checked></td>
                        </tr>
                        <tr>
                            <td>
                                <h3>6. sat: </h3>
                                <input type="text" name="u60" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="u61" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3> - </h3>
                                <input type="text" name="u62" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="u63" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                            </td>
                            <td><input type="checkbox" name="u6" value="1" checked></td>
                        </tr>
                        <tr>
                            <td>
                                <h3>7. sat: </h3>
                                <input type="text" name="u70" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="u71" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3> - </h3>
                                <input type="text" name="u72" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="u73" maxlength="2" placeholder="<?php echo $am[$i] . $am[$i + 1];
                                                                                            $i = 0; ?>">
                            </td>
                            <td><input type="checkbox" name="u7" value="1" checked></td>
                        </tr>
                    </table>

                    <table class="time-setup">
                        <th>Popodnevna smjena</th>
                        <th>Zvoni</th>
                        <th></th>
                        <tr>
                            <td>
                                <h3>1. sat: </h3>
                                <input type="text" name="p10" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="p11" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3> - </h3>
                                <input type="text" name="p12" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="p13" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                            </td>
                            <td><input type="checkbox" name="p1" value="1" checked></td>
                        </tr>
                        <tr>
                            <td>
                                <h3>2. sat: </h3>
                                <input type="text" name="p20" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="p21" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3> - </h3>
                                <input type="text" name="p22" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="p23" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                            </td>
                            <td><input type="checkbox" name="p2" value="1" checked></td>
                        </tr>
                        <tr>
                            <td>
                                <h3>3. sat: </h3>
                                <input type="text" name="p30" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="p31" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3> - </h3>
                                <input type="text" name="p32" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="p33" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                            </td>
                            <td><input type="checkbox" name="p3" value="1" checked></td>
                        </tr>
                        <tr>
                            <td>
                                <h3>4. sat: </h3>
                                <input type="text" name="p40" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="p41" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3> - </h3>
                                <input type="text" name="p42" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="p43" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                            </td>
                            <td><input type="checkbox" name="p4" value="1" checked></td>
                        </tr>
                        <tr>
                            <td>
                                <h3>5. sat: </h3>
                                <input type="text" name="p50" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="p51" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3> - </h3>
                                <input type="text" name="p52" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="p53" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                            </td>
                            <td><input type="checkbox" name="p5" value="1" checked></td>
                        </tr>
                        <tr>
                            <td>
                                <h3>6. sat: </h3>
                                <input type="text" name="p60" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="p61" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3> - </h3>
                                <input type="text" name="p62" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="p63" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                            </td>
                            <td><input type="checkbox" name="p6" value="1" checked></td>
                        </tr>
                        <tr>
                            <td>
                                <h3>7. sat: </h3>
                                <input type="text" name="p70" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="p71" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3> - </h3>
                                <input type="text" name="p72" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                                <h3>:</h3>
                                <input type="text" name="p73" maxlength="2" placeholder="<?php echo $pm[$i] . $pm[$i + 1];
                                                                                            $i += 2 ?>">
                            </td>
                            <td><input type="checkbox" name="p7" value="1" checked></td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="calendar-wrapper">
                <?php 
                $_SESSION['calendar-id'] = 1;
                require '../page_scripts/calendar.php'
                ?>
            </div>
        </section>

        <section class="section3">
            <div class="buttons">
                <button class="select" type="submit" form="time_set" name="db-save">Spremi</button>
                <button class="select" type="reset" form="time_set">Očisti unos</button>
                <button class="select" type="submit" form="time_set" name="eeprom-save">Spremi na arduino</button>
            </div>
        </section>

        <footer>
            <div class="menus">
                <div class="calendar-wrapper">
                    <?php
                    $_SESSION['calendar-id'] = 2;
                    require '../page_scripts/calendar2.php'
                    ?>
                </div>
                <h2>Spremljene postavke</h2>
                <form method="post" id="menu">
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
            </div>

            <div class="time-display">
                <?php

                $sql = "SELECT * FROM time_set;";
                $result = mysqli_query($conn, $sql);
                $numOfResults = mysqli_num_rows($result);
                $sql = "SELECT * FROM eeprom_mirror;";
                $result = mysqli_query($conn, $sql);
                $numOfResults += mysqli_num_rows($result);

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
                    if ($_SESSION['selected-button-value'] < 10) $_SESSION['selected-button-value-3'] = '0' . $_SESSION['selected-button-value'];
                    $dateToParse = $htmlTitle . '-' . $_SESSION['selected-button-value'];
                    $sql = 'SELECT * FROM settings_by_date WHERE date_active = \'' . $dateToParse . '\'';
                }
                $result = mysqli_query($conn, $sql);

                if (!empty($result)) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['option_name'] == $_SESSION['selected-button-value']) break;
                        if ($row['date_active'] == $dateToParse) break;
                    }
                }

                require '../page_scripts/time_display.php';

                ?>
            </div>
        </footer>

    </div>
</body>

</html>