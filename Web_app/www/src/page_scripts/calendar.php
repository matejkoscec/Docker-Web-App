<?php

date_default_timezone_set('Europe/Zagreb');

if (!isset($_SESSION['time1'])) $_SESSION['time1'] = date('Y-m', time());

if (isset($_GET['ym'])) {
    $ym = $_GET['ym'];
    $_SESSION['time1'] = $ym;
} else {
    $ym = date('Y-m', strtotime($_SESSION['time1'], "-01"));
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

    if ($today == $date) {
        $week .= '<td>' . $day;
    } else {
        $week .= '<td><button class="calendar-button" type="submit" form="calendar" value="' . $day . '" name="calendar-button' . $day . '">' . $day . '</button>';
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