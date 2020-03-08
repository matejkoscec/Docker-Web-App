<table class="time-display-table">
    <tr>
        <?php if (isset($_SESSION['to-be-set-active'])) echo '<td colspan="2" style="text-align: center;">Jutarnja smjena</td><td style="text-align: center;">Zvoni</td>' ?>
    </tr>
    <?php

    echo '<p>' . $_SESSION['to-be-set-active']['option_name'] . '</p>';
    echo '<br>';
    echo '<tr><td>';
    $allowCalc = true;
    for ($i = 0; $i <= floor(strlen($_SESSION['to-be-set-active']['time_string']) / 2); $i++) {

        if ($i % 8 == 2) echo ':';
        if ($i % 8 == 4) echo ' - ';
        if ($i % 8 == 6) echo ':';

        if ($i % 8 == 0) {

            if ($i != 0) {
                echo '</td><td>';
                if ($_SESSION['to-be-set-active']['ring_enable'][$i / 8 - 1] == 1) echo '✔';
            }

            echo '</td></tr>';

            if ($i == floor(strlen($_SESSION['to-be-set-active']['time_string']) / 2)) {
                break;
            }

            if (!$allowCalc) break;
            echo '<tr><td>';

            if ($i != 0) {
                for ($j = 0; $j < 8; $j++) {
                    if ($_SESSION['to-be-set-active']['time_string'][$i + 8 + $j] == '#' || $_SESSION['to-be-set-active']['time_string'][$i + 8 + $j] == NULL) $allowCalc = false;
                }
                if ($allowCalc) {
                    $time1 = ($_SESSION['to-be-set-active']['time_string'][$i - 4] * 10 + $_SESSION['to-be-set-active']['time_string'][$i - 3]) * 60 + $_SESSION['to-be-set-active']['time_string'][$i - 2] * 10 + $_SESSION['to-be-set-active']['time_string'][$i - 1];
                    $time2 = ($_SESSION['to-be-set-active']['time_string'][$i] * 10 + $_SESSION['to-be-set-active']['time_string'][$i + 1]) * 60 + $_SESSION['to-be-set-active']['time_string'][$i + 2] * 10 + $_SESSION['to-be-set-active']['time_string'][$i + 3];
                }

                if ($time2 - $time1 <= 10) {
                    echo 'Mali odmor: </td><td>';
                    echo $_SESSION['to-be-set-active']['time_string'][$i - 4] . $_SESSION['to-be-set-active']['time_string'][$i - 3] . ':' . $_SESSION['to-be-set-active']['time_string'][$i - 2] . $_SESSION['to-be-set-active']['time_string'][$i - 1];
                    echo ' - ';
                    echo $_SESSION['to-be-set-active']['time_string'][$i] . $_SESSION['to-be-set-active']['time_string'][$i + 1] . ':' . $_SESSION['to-be-set-active']['time_string'][$i + 2] . $_SESSION['to-be-set-active']['time_string'][$i + 3];
                    echo '</td></tr>';
                    echo '<tr><td>';
                } else if ($time2 - $time1 < 30) {
                    echo 'Veliki odmor: </td><td>';
                    echo $_SESSION['to-be-set-active']['time_string'][$i - 4] . $_SESSION['to-be-set-active']['time_string'][$i - 3] . ':' . $_SESSION['to-be-set-active']['time_string'][$i - 2] . $_SESSION['to-be-set-active']['time_string'][$i - 1];
                    echo ' - ';
                    echo $_SESSION['to-be-set-active']['time_string'][$i] . $_SESSION['to-be-set-active']['time_string'][$i + 1] . ':' . $_SESSION['to-be-set-active']['time_string'][$i + 2] . $_SESSION['to-be-set-active']['time_string'][$i + 3];
                    echo '</td></tr>';
                    echo '<tr><td>';
                }
            }

            echo ($i / 8 + 1) . '. sat: </td><td>';
        }

        if ($allowCalc) echo $_SESSION['to-be-set-active']['time_string'][$i];
    }

    ?>

</table>

<table class="time-display-table">
    <tr>
        <?php if (isset($_SESSION['to-be-set-active'])) echo '<td colspan="2" style="text-align: center;">Popodnevna smjena</td>' ?>
    </tr>
    <?php

    echo '<tr><td>';
    $allowCalc = true;
    for ($i = strlen($_SESSION['to-be-set-active']['time_string']) / 2; $i < strlen($_SESSION['to-be-set-active']['time_string']); $i++) {

        if ($i % 8 == 2) echo ':';
        if ($i % 8 == 4) echo ' - ';
        if ($i % 8 == 6) echo ':';

        if ($i % 8 == 0) {

            if ($i != strlen($_SESSION['to-be-set-active']['time_string']) / 2) {
                echo '</td><td>';
                if ($_SESSION['to-be-set-active']['ring_enable'][$i / 8 - 1] == 1) echo '✔';
            }

            echo '</td></tr>';
            if (!$allowCalc) break;
            echo '<tr><td>';

            if ($i != strlen($_SESSION['to-be-set-active']['time_string']) / 2) {
                for ($j = 0; $j < 8; $j++) {
                    if ($_SESSION['to-be-set-active']['time_string'][$i + 8 + $j] == '#' || $_SESSION['to-be-set-active']['time_string'][$i + 8 + $j] == NULL) $allowCalc = false;
                }
                if ($allowCalc) {
                    $time1 = ($_SESSION['to-be-set-active']['time_string'][$i - 4] * 10 + $_SESSION['to-be-set-active']['time_string'][$i - 3]) * 60 + $_SESSION['to-be-set-active']['time_string'][$i - 2] * 10 + $_SESSION['to-be-set-active']['time_string'][$i - 1];
                    $time2 = ($_SESSION['to-be-set-active']['time_string'][$i] * 10 + $_SESSION['to-be-set-active']['time_string'][$i + 1]) * 60 + $_SESSION['to-be-set-active']['time_string'][$i + 2] * 10 + $_SESSION['to-be-set-active']['time_string'][$i + 3];
                }

                if ($time2 - $time1 <= 10) {
                    echo 'Mali odmor: </td><td>';
                    echo $_SESSION['to-be-set-active']['time_string'][$i - 4] . $_SESSION['to-be-set-active']['time_string'][$i - 3] . ':' . $_SESSION['to-be-set-active']['time_string'][$i - 2] . $_SESSION['to-be-set-active']['time_string'][$i - 1];
                    echo ' - ';
                    echo $_SESSION['to-be-set-active']['time_string'][$i] . $_SESSION['to-be-set-active']['time_string'][$i + 1] . ':' . $_SESSION['to-be-set-active']['time_string'][$i + 2] . $_SESSION['to-be-set-active']['time_string'][$i + 3];
                    echo '</td></tr>';
                    echo '<tr><td>';
                } else if ($time2 - $time1 < 30) {
                    echo 'Veliki odmor: </td><td>';
                    echo $_SESSION['to-be-set-active']['time_string'][$i - 4] . $_SESSION['to-be-set-active']['time_string'][$i - 3] . ':' . $_SESSION['to-be-set-active']['time_string'][$i - 2] . $_SESSION['to-be-set-active']['time_string'][$i - 1];
                    echo ' - ';
                    echo $_SESSION['to-be-set-active']['time_string'][$i] . $_SESSION['to-be-set-active']['time_string'][$i + 1] . ':' . $_SESSION['to-be-set-active']['time_string'][$i + 2] . $_SESSION['to-be-set-active']['time_string'][$i + 3];
                    echo '</td></tr>';
                    echo '<tr><td>';
                }
            }

            echo (int) ($i / 8 - (strlen($_SESSION['to-be-set-active']['time_string']) / 16) + 1) . '. sat: </td><td>';
        }

        echo $_SESSION['to-be-set-active']['time_string'][$i];
    }

    ?>

</table>