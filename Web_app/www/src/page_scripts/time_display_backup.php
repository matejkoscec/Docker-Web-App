<table>
    <tr>
        <td colspan="2" style="text-align: center;">Jutarnja smjena</td>
    </tr>
    <?php

    for ($i = 0; $i < 7; $i++) {

        echo '<tr>';
        echo '<td>';

        for ($j = 0; $j < 8; $j++) {
            if ($row['time_string'][$i * 8 + 8 + $j] == '#') $allowCalc = false;
        }
        if ($allowCalc) {
            $time1 = ($row['time_string'][$i * 8 + 4] * 10 + $row['time_string'][$i * 8 + 5]) * 60 + $row['time_string'][$i * 8 + 6] * 10 + $row['time_string'][$i * 8 + 7];
            $time2 = ($row['time_string'][$i * 8 + 8] * 10 + $row['time_string'][$i * 8 + 9]) * 60 + $row['time_string'][$i * 8 + 10] * 10 + $row['time_string'][$i * 8 + 11];
        }

        echo ($i + 1) . '. sat: </td><td>';
        echo $row['time_string'][$i * 8];
        echo $row['time_string'][$i * 8 + 1];
        echo ':';
        echo $row['time_string'][$i * 8 + 2];
        echo $row['time_string'][$i * 8 + 3];
        echo ' - ';
        echo $row['time_string'][$i * 8 + 4];
        echo $row['time_string'][$i * 8 + 5];
        echo ':';
        echo $row['time_string'][$i * 8 + 6];
        echo $row['time_string'][$i * 8 + 7];
        echo '</td></tr>';

        if ($allowCalc) {
            if ($time2 - $time1 <= 10 && $i != 6) {
                echo '<tr><td>Mali odmor: </td><td>';
                echo $row['time_string'][$i * 8 + 4];
                echo $row['time_string'][$i * 8 + 5];
                echo ':';
                echo $row['time_string'][$i * 8 + 6];
                echo $row['time_string'][$i * 8 + 7];
                echo ' - ';
                echo $row['time_string'][$i * 8 + 8];
                echo $row['time_string'][$i * 8 + 9];
                echo ':';
                echo $row['time_string'][$i * 8 + 10];
                echo $row['time_string'][$i * 8 + 11];
                echo '</td></tr>';
            } else if ($time2 - $time1 < 30 && $i != 6) {
                echo '<tr><td>Veliki odmor: </td><td>';
                echo $row['time_string'][$i * 8 + 4];
                echo $row['time_string'][$i * 8 + 5];
                echo ':';
                echo $row['time_string'][$i * 8 + 6];
                echo $row['time_string'][$i * 8 + 7];
                echo ' - ';
                echo $row['time_string'][$i * 8 + 8];
                echo $row['time_string'][$i * 8 + 9];
                echo ':';
                echo $row['time_string'][$i * 8 + 10];
                echo $row['time_string'][$i * 8 + 11];
                echo '</td></tr>';
            }
        }
    }

    ?>

</table>

<table>
    <tr>
        <td colspan="2" style="text-align: center;">Popodnevna smjena</td>
    </tr>
    <?php

    $allowCalc = true;

    for ($i = 7; $i < 14; $i++) {

        echo '<tr>';
        echo '<td>';

        for ($j = 0; $j < 8; $j++) {
            if ($row['time_string'][$i * 8 + 8 + $j] == '#') $allowCalc = false;
        }
        if ($allowCalc) {
            $time1 = ($row['time_string'][$i * 8 + 4] * 10 + $row['time_string'][$i * 8 + 5]) * 60 + $row['time_string'][$i * 8 + 6] * 10 + $row['time_string'][$i * 8 + 7];
            $time2 = ($row['time_string'][$i * 8 + 8] * 10 + $row['time_string'][$i * 8 + 9]) * 60 + $row['time_string'][$i * 8 + 10] * 10 + $row['time_string'][$i * 8 + 11];
        }


        echo ($i - 6) . '. sat: </td><td>';
        echo $row['time_string'][$i * 8];
        echo $row['time_string'][$i * 8 + 1];
        echo ':';
        echo $row['time_string'][$i * 8 + 2];
        echo $row['time_string'][$i * 8 + 3];
        echo ' - ';
        echo $row['time_string'][$i * 8 + 4];
        echo $row['time_string'][$i * 8 + 5];
        echo ':';
        echo $row['time_string'][$i * 8 + 6];
        echo $row['time_string'][$i * 8 + 7];
        echo '</td></tr>';

        if ($allowCalc) {
            if ($time2 - $time1 <= 10) {
                echo '<tr><td>Mali odmor: </td><td>';
                echo $row['time_string'][$i * 8 + 4];
                echo $row['time_string'][$i * 8 + 5];
                echo ':';
                echo $row['time_string'][$i * 8 + 6];
                echo $row['time_string'][$i * 8 + 7];
                echo ' - ';
                echo $row['time_string'][$i * 8 + 8];
                echo $row['time_string'][$i * 8 + 9];
                echo ':';
                echo $row['time_string'][$i * 8 + 10];
                echo $row['time_string'][$i * 8 + 11];
                echo '</td></tr>';
            } else if ($time2 - $time1 < 30) {
                echo '<tr><td>Veliki odmor: </td><td>';
                echo $row['time_string'][$i * 8 + 4];
                echo $row['time_string'][$i * 8 + 5];
                echo ':';
                echo $row['time_string'][$i * 8 + 6];
                echo $row['time_string'][$i * 8 + 7];
                echo ' - ';
                echo $row['time_string'][$i * 8 + 8];
                echo $row['time_string'][$i * 8 + 9];
                echo ':';
                echo $row['time_string'][$i * 8 + 10];
                echo $row['time_string'][$i * 8 + 11];
                echo '</td></tr>';
            }
        }
    }

    ?>

</table>