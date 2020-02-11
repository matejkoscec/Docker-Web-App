<?php

session_start();

require '../page_scripts/dbh.php';

require '../page_scripts/admin_check.php';

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
            <h2>Naziv opcije</h2>
            <input class="option-name" type="text" maxlength="50" name="opn" placeholder="npr. Raspored - Standardno">
            <h2>Početak jutarnje smjene</h2>
            <input class="option-name" type="text" maxlength="50" name="opn">
            :
            <input class="option-name" type="text" maxlength="50" name="opn">
            <h2>Trajanje sata (min)</h2>
            <input class="option-name" type="text" maxlength="50" name="opn">
            <h2>Trajanje malih odmora (min)</h2>
            <input class="option-name" type="text" maxlength="50" name="opn">
            <h2>Trajanje velikih odmora (min)</h2>
            <input class="option-name" type="text" maxlength="50" name="opn">
            <h2>Pauza između smjena (min)</h2>
            <input class="option-name" type="text" maxlength="50" name="opn">
            <button class="select" type="submit" form="time_set" name="db-save">Generiraj</button>
        </header>

        <section class="section1" style="background-color: white;">
            <div class="menus">
                <div class="calendar-wrapper">
                    <?php require '../page_scripts/calendar.php' ?>
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
                $numOfResults = mysqli_num_rows($result);
                $sql = "SELECT * FROM active_setting;";
                $result = mysqli_query($conn, $sql);

                for ($i = 0; $i < $numOfResults; $i++) {
                    if (isset($_POST['ts-button' . $i])) {
                        $selectedButtonValue = $_POST['ts-button' . $i];
                        $sql = 'SELECT * FROM time_set WHERE option_name = \'' . $selectedButtonValue . '\'';
                        $result = mysqli_query($conn, $sql);
                    }
                }

                for ($i = 0; $i < $numOfResults; $i++) {
                    if (isset($_POST['eeprom-button' . $i])) {
                        $selectedButtonValue = $_POST['eeprom-button' . $i];
                        $sql = 'SELECT * FROM eeprom_mirror WHERE option_name = \'' . $selectedButtonValue . '\'';
                        $result = mysqli_query($conn, $sql);
                    }
                }

                for ($i = 1; $i <= 31; $i++) {
                    if (isset($_POST['calendar-button' . $i])) {
                        $selectedButtonValue = $_POST['calendar-button' . $i];
                        if ($selectedButtonValue < 10) $selectedButtonValue = '0' . $selectedButtonValue;
                        $dateToParse = $htmlTitle . '-' . $selectedButtonValue;
                        $sql = 'SELECT * FROM settings_by_date WHERE date_active = \'' . $dateToParse . '\'';
                        $result = mysqli_query($conn, $sql);
                    }
                }

                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['option_name'] == $selectedButtonValue) break;
                    if ($row['date_active'] == $dateToParse) break;
                }

                echo '<p>' . $row['option_name'] . '</p>';
                echo '<br>';
                require '../page_scripts/time_display.php';

                ?>
            </div>
        </section>

        <section class="section2" style="background-color: white;">
            <div class="manual-setup-tables">
                <form action="save_to_db.php" method="post" id="time_set">
                    <?php
                    $i = 0;
                    $pm = substr($row['time_string'], -(int) (strlen($row['time_string']) / 2) - 1);
                    $am = $row['time_string'];
                    $am = str_replace($pm, '', $am);
                    ?>
                    <table class="time-setup">
                        <th>Jutarnja smjena</th>
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
                        </tr>
                    </table>

                    <table class="time-setup">
                        <th>Popodnevna smjena</th>
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
                        </tr>
                    </table>

                    <div class="buttons">
                        <br><br>
                        <h2>Naziv opcije</h2>
                        <input class="option-name" type="text" maxlength="50" name="opn" placeholder="<?php echo $row['option_name']; ?>">
                        <br><br>
                        <button class="select" type="submit" form="time_set" name="db-save">Spremi</button>
                        <button class="select" type="reset" form="time_set" style="margin-left: 70px;">Očisti unos</button>
                        <br><br>
                        <button class="select" type="submit" form="time_set" name="eeprom-save">Spremi na arduino</button>
                    </div>
                </form>
            </div>
        </section>

        <footer style="background-color: black; color: white;">
            footer
        </footer>

    </div>
</body>

</html>