<?php

if (isset($_SESSION['selected-button-value-1']) || isset($_SESSION['selected-button-value-2']) || isset($_SESSION['selected-button-value-3']) || isset($_SESSION['selected-button-value-4'])) {
    $optionName = $_SESSION['to-be-set-active']['option_name'];

    $pm = substr($_SESSION['to-be-set-active']['time_string'], -(int) (strlen($_SESSION['to-be-set-active']['time_string']) / 2));
    $am = $_SESSION['to-be-set-active']['time_string'];
    $am = str_replace($pm, '', $am);

    $ringEnable = $_SESSION['to-be-set-active']['ring_enable'];
} else {
    $optionName = $_SESSION['option-name'];

    $pm = substr($_SESSION['time-string'], -(int) (strlen($_SESSION['time-string']) / 2));
    $am = $_SESSION['time-string'];
    $am = str_replace($pm, '', $am);

    $ringEnable = '11111111111111';
}

$i = 0;

?>
<h2>Naziv postavke</h2>
<input class="option-name" type="text" maxlength="50" name="opn" placeholder="<?php echo $optionName; ?>">
<br>
<table class="time-setup">
    <th>Jutarnja smjena</th>
    <th>Zvoni</th>
    <tr>
        <td>
            <h3>1. sat: </h3>
            <input type="text" name="u10" maxlength="2" min="00" max="24" placeholder="<?php echo $am[$i] . $am[$i + 1];
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
        <td><input type="checkbox" name="u1" value="1" <?php if ($ringEnable[0] == '1') echo 'checked' ?>></td>
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
        <td><input type="checkbox" name="u2" value="1" <?php if ($ringEnable[1] == '1') echo 'checked' ?>></td>
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
        <td><input type="checkbox" name="u3" value="1" <?php if ($ringEnable[2] == '1') echo 'checked' ?>></td>
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
        <td><input type="checkbox" name="u4" value="1" <?php if ($ringEnable[3] == '1') echo 'checked' ?>></td>
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
        <td><input type="checkbox" name="u5" value="1" <?php if ($ringEnable[4] == '1') echo 'checked' ?>></td>
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
        <td><input type="checkbox" name="u6" value="1" <?php if ($ringEnable[5] == '1') echo 'checked' ?>></td>
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
        <td><input type="checkbox" name="u7" value="1" <?php if ($ringEnable[6] == '1') echo 'checked' ?>></td>
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
        <td><input type="checkbox" name="p1" value="1" <?php if ($ringEnable[7] == '1') echo 'checked' ?>></td>
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
        <td><input type="checkbox" name="p2" value="1" <?php if ($ringEnable[8] == '1') echo 'checked' ?>></td>
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
        <td><input type="checkbox" name="p3" value="1" <?php if ($ringEnable[9] == '1') echo 'checked' ?>></td>
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
        <td><input type="checkbox" name="p4" value="1" <?php if ($ringEnable[10] == '1') echo 'checked' ?>></td>
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
        <td><input type="checkbox" name="p5" value="1" <?php if ($ringEnable[11] == '1') echo 'checked' ?>></td>
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
        <td><input type="checkbox" name="p6" value="1" <?php if ($ringEnable[12] == '1') echo 'checked' ?>></td>
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
        <td><input type="checkbox" name="p7" value="1" <?php if ($ringEnable[13] == '1') echo 'checked' ?>></td>
    </tr>
</table>