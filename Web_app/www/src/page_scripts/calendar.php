<?php

date_default_timezone_set('Europe/Zagreb');

if (isset($_GET['ym'])) {
    $ym = $_GET['ym'];
    $_SESSION['time'] = $ym;
} else {
    $ym = date('Y-m', strtotime($_SESSION['time'], "-01"));
}

$timeStamp = strtotime($ym, "-01");
if ($timeStamp == false) {
    $timeStamp = time();
}

$today = date('Y-m-d', time());

$_SESSION['calendar-date'] = date('Y-m', $timeStamp);

$prev = date('Y-m', mktime(0, 0, 0, date('m', $timeStamp) - 1, 1, date('Y', $timeStamp)));
$next = date('Y-m', mktime(0, 0, 0, date('m', $timeStamp) + 1, 1, date('Y', $timeStamp)));

$daysInAMonth = date('t', $timeStamp);

$str = date('w', mktime(0, 0, 0, date('m', $timeStamp), 0, date('Y', $timeStamp)));

$weeks = array();
$week = '';

$week .= str_repeat('<td></td>', $str);

for ($day = 1; $day <= $daysInAMonth; $day++, $str++) {

    $date = $ym . ' ' . $day;

    for ($j = 0; $j <= count($_SESSION['dateArray']); $j++) {
        if ($_SESSION['dateArray'][$j] == ($ym . '-' . $day) || $_SESSION['dateArray'][$j] == ($ym . '-0' . $day)) {
            $highlight = 'style="background-color: green;"';
            break;
        } else $highlight = '';
    }

    if ($today == $date) {
        $week .= '<td>' . $day;
    } else {
        $week .= '<td><button class="calendar-button" type="submit" form="calendar" value="' . $day . '" name="calendar-button'
            . $day . '" ' . $highlight . '>' . $day . '<br>Test' . '</button>';
    }
    $week .= '</td>';

    if ($str % 7 == 6 || $day == $daysInAMonth) {

        if ($day == $daysInAMonth) {
            $week .= str_repeat('<td></td>', $str % 7);
        }

        $weeks[] = '<tr>' . $week . '</tr>';
        $week = '';
    }
}

?>

<h4><a href="?ym=<?php echo $prev; ?>">&lt; </a><?php echo $_SESSION['calendar-date']; ?> <a href="?ym=<?php echo $next; ?>">&gt;</a></h4>
<div class="calendar">
    <table>
        <tr>
            <th>Pon</th>
            <th>Uto</th>
            <th>Sri</th>
            <th>Čet</th>
            <th>Pet</th>
            <th>Sub</th>
            <th>Ned</th>
        </tr>
        <?php
        echo '<form method="post" id="calendar">';
        foreach ($weeks as $week) {
            echo $week;
        }
        echo '</form>';
        ?>
    </table>
</div>