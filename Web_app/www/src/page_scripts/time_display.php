<table class="time-display-table">
    <tr>
        <td colspan="2" style="text-align: center;">Jutarnja smjena</td>
    </tr>
    <?php

    echo '<tr><td>';
    $allowCalc = true;
    for ($i = 0; $i < strlen($row['time_string']) / 2 - 1; $i++) {

        if ($i % 8 == 2) echo ':';
        if ($i % 8 == 4) echo ' - ';
        if ($i % 8 == 6) echo ':';

        if ($i % 8 == 0) {
            echo '</td></tr>';
            if (!$allowCalc) break;
            echo '<tr><td>';

            if ($i != 0) {
                for ($j = 0; $j < 8; $j++) {
                    if ($row['time_string'][$i + 8 + $j] == '#' || $row['time_string'][$i + 8 + $j] == NULL) $allowCalc = false;
                }
                if ($allowCalc) {
                    $time1 = ($row['time_string'][$i - 4] * 10 + $row['time_string'][$i - 3]) * 60 + $row['time_string'][$i - 2] * 10 + $row['time_string'][$i - 1];
                    $time2 = ($row['time_string'][$i] * 10 + $row['time_string'][$i + 1]) * 60 + $row['time_string'][$i + 2] * 10 + $row['time_string'][$i + 3];
                }

                if ($time2 - $time1 <= 10) {
                    echo 'Mali odmor: </td><td>';
                    echo $row['time_string'][$i - 4] . $row['time_string'][$i - 3] . ':' . $row['time_string'][$i - 2] . $row['time_string'][$i - 1];
                    echo ' - ';
                    echo $row['time_string'][$i] . $row['time_string'][$i + 1] . ':' . $row['time_string'][$i + 2] . $row['time_string'][$i + 3];
                    echo '</td></tr>';
                    echo '<tr><td>';
                } else if ($time2 - $time1 < 30) {
                    echo 'Veliki odmor: </td><td>';
                    echo $row['time_string'][$i - 4] . $row['time_string'][$i - 3] . ':' . $row['time_string'][$i - 2] . $row['time_string'][$i - 1];
                    echo ' - ';
                    echo $row['time_string'][$i] . $row['time_string'][$i + 1] . ':' . $row['time_string'][$i + 2] . $row['time_string'][$i + 3];
                    echo '</td></tr>';
                    echo '<tr><td>';
                }
            }
            
            echo ($i / 8 + 1) . '. sat: </td><td>';
        }

        if ($allowCalc) echo $row['time_string'][$i];
    }

    ?>

</table>

<table class="time-display-table">
    <tr>
        <td colspan="2" style="text-align: center;">Popodnevna smjena</td>
    </tr>
    <?php

    echo '<tr><td>';
    $allowCalc = true;
    for ($i = strlen($row['time_string']) / 2; $i < strlen($row['time_string']); $i++) {

        if ($i % 8 == 2) echo ':';
        if ($i % 8 == 4) echo ' - ';
        if ($i % 8 == 6) echo ':';

        if ($i % 8 == 0) {

            echo '</td></tr>';
            if (!$allowCalc) break;
            echo '<tr><td>';

            if ($i != strlen($row['time_string']) / 2) {
                for ($j = 0; $j < 8; $j++) {
                    if ($row['time_string'][$i + 8 + $j] == '#' || $row['time_string'][$i + 8 + $j] == NULL) $allowCalc = false;
                }
                if ($allowCalc) {
                    $time1 = ($row['time_string'][$i - 4] * 10 + $row['time_string'][$i - 3]) * 60 + $row['time_string'][$i - 2] * 10 + $row['time_string'][$i - 1];
                    $time2 = ($row['time_string'][$i] * 10 + $row['time_string'][$i + 1]) * 60 + $row['time_string'][$i + 2] * 10 + $row['time_string'][$i + 3];
                }

                if ($time2 - $time1 <= 10) {
                    echo 'Mali odmor: </td><td>';
                    echo $row['time_string'][$i - 4] . $row['time_string'][$i - 3] . ':' . $row['time_string'][$i - 2] . $row['time_string'][$i - 1];
                    echo ' - ';
                    echo $row['time_string'][$i] . $row['time_string'][$i + 1] . ':' . $row['time_string'][$i + 2] . $row['time_string'][$i + 3];
                    echo '</td></tr>';
                    echo '<tr><td>';
                } else if ($time2 - $time1 < 30) {
                    echo 'Veliki odmor: </td><td>';
                    echo $row['time_string'][$i - 4] . $row['time_string'][$i - 3] . ':' . $row['time_string'][$i - 2] . $row['time_string'][$i - 1];
                    echo ' - ';
                    echo $row['time_string'][$i] . $row['time_string'][$i + 1] . ':' . $row['time_string'][$i + 2] . $row['time_string'][$i + 3];
                    echo '</td></tr>';
                    echo '<tr><td>';
                }
            }
            
            echo (int)($i / 8 - (strlen($row['time_string']) / 16) + 1) . '. sat: </td><td>';
        }

        echo $row['time_string'][$i];
    }

    ?>

</table>