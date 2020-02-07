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
                    <input type="text" name="u10" maxlength="2" placeholder="13">
                    <h3>:</h3>
                    <input type="text" name="u11" maxlength="2" placeholder="35">
                    <h3> - </h3>
                    <input type="text" name="u12" maxlength="2" placeholder="14">
                    <h3>:</h3>
                    <input type="text" name="u13" maxlength="2" placeholder="20">
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
    </div>
</body>

</html>