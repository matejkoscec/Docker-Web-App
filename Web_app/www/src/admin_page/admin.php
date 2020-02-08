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
        <form action="save_to_db.php" method="post" id="time_set">
            <span class="manual_setup">
                <table class="time_setup">
                    <th>Jutarnja smjena</th>
                    <tr>
                        <td>
                            <h3>1. sat: </h3>
                            <input type="text" name="u10" maxlength="2" placeholder="07">
                            <h3>:</h3>
                            <input type="text" name="u11" maxlength="2" placeholder="30">
                            <h3> - </h3>
                            <input type="text" name="u12" maxlength="2" placeholder="08">
                            <h3>:</h3>
                            <input type="text" name="u13" maxlength="2" placeholder="15">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3>2. sat: </h3>
                            <input type="text" name="u20" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="u21" maxlength="2">
                            <h3> - </h3>
                            <input type="text" name="u22" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="u23" maxlength="2">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3>3. sat: </h3>
                            <input type="text" name="u30" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="u31" maxlength="2">
                            <h3> - </h3>
                            <input type="text" name="u32" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="u33" maxlength="2">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3>4. sat: </h3>
                            <input type="text" name="u40" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="u41" maxlength="2">
                            <h3> - </h3>
                            <input type="text" name="u42" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="u43" maxlength="2">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3>5. sat: </h3>
                            <input type="text" name="u50" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="u51" maxlength="2">
                            <h3> - </h3>
                            <input type="text" name="u52" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="u53" maxlength="2">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3>6. sat: </h3>
                            <input type="text" name="u60" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="u61" maxlength="2">
                            <h3> - </h3>
                            <input type="text" name="u62" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="u63" maxlength="2">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3>7. sat: </h3>
                            <input type="text" name="u70" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="u71" maxlength="2">
                            <h3> - </h3>
                            <input type="text" name="u72" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="u73" maxlength="2">
                        </td>
                    </tr>
                </table>

                <table class="time_setup">
                    <th>Popodnevna smjena</th>
                    <tr>
                        <td>
                            <h3>1. sat: </h3>
                            <input type="text" name="p10" maxlength="2" placeholder="13">
                            <h3>:</h3>
                            <input type="text" name="p11" maxlength="2" placeholder="35">
                            <h3> - </h3>
                            <input type="text" name="p12" maxlength="2" placeholder="14">
                            <h3>:</h3>
                            <input type="text" name="p13" maxlength="2" placeholder="20">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3>2. sat: </h3>
                            <input type="text" name="p20" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="p21" maxlength="2">
                            <h3> - </h3>
                            <input type="text" name="p22" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="p23" maxlength="2">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3>3. sat: </h3>
                            <input type="text" name="p30" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="p31" maxlength="2">
                            <h3> - </h3>
                            <input type="text" name="p32" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="p33" maxlength="2">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3>4. sat: </h3>
                            <input type="text" name="p40" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="p41" maxlength="2">
                            <h3> - </h3>
                            <input type="text" name="p42" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="p43" maxlength="2">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3>5. sat: </h3>
                            <input type="text" name="p50" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="p51" maxlength="2">
                            <h3> - </h3>
                            <input type="text" name="p52" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="p53" maxlength="2">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3>6. sat: </h3>
                            <input type="text" name="p60" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="p61" maxlength="2">
                            <h3> - </h3>
                            <input type="text" name="p62" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="p63" maxlength="2">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3>7. sat: </h3>
                            <input type="text" name="p70" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="p71" maxlength="2">
                            <h3> - </h3>
                            <input type="text" name="p72" maxlength="2">
                            <h3>:</h3>
                            <input type="text" name="p73" maxlength="2">
                        </td>
                    </tr>
                </table>
            </span>
            <br><br><br>
            <h2>Naziv opcije</h2>
            <input class="option_name" type="text" maxlength="50" name="opn" placeholder="npr. Skraćeno 5 min">
            <br>
        </form>

        <button class="select" type="submit" form="time_set">Spremi</button>
        <br><br>
        <button class="select">Spremi na arduino</button>
        <br><br><br><br>

        <h2>Spremljene postavke</h2>

        <div class="menus">
            <form method="post" id="menu">
                <div class="vertical_menu">
                    <?php

                    $sql = "SELECT option_name FROM time_set;";
                    $result = mysqli_query($conn, $sql);

                    $i = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<input type="submit" class="vm_option" form="menu" value="' . $row['option_name'] . '" name="button' . $i . '">';
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
                        echo '<input type="submit" class="vm_option" form="menu" value="' . $row['option_name'] . '" name="button' . $i . '">';
                        $i++;
                    }

                    ?>
                </div>
            </form>
        </div>

        <div class="time_display">
            <?php

            $sql = "SELECT * FROM time_set;";
            $result = mysqli_query($conn, $sql);

            for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                if (isset($_POST['button' . $i])) $selectedButtonValue = $_POST['button' . $i];
            }
            

            $i = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['option_name'] == $selectedButtonValue) break;
                $i++;
            }

            echo '<br>';
            echo '<p>' . $row['option_name'] . '</p>';
            echo '<br>';
            require '../page_scripts/time_display.php';

            ?>
        </div>
    </div>
</body>

</html>