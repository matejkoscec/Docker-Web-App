<?php

$conn = new mysqli('db', 'root', getenv('MYSQL_ROOT_PASSWORD'), 'information_schema');

if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
}

$conn->query("CREATE DATABASE IF NOT EXISTS SJWP;");

$conn = new mysqli('db', 'root', getenv('MYSQL_ROOT_PASSWORD'), 'SJWP');

if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
}

$conn->query("CREATE TABLE IF NOT EXISTS lijekovi ( naziv_lijeka varchar(40), cijena int, kolicina int );");
$conn->query("CREATE TABLE IF NOT EXISTS racun ( naziv_lijeka varchar(40), cijena int, kolicina int );");

if (!isset($ibuprofen)) $ibuprofen = 0;
if (!isset($normabel)) $normabel = 0;
if (!isset($lupocet)) $lupocet = 0;
if (!isset($rakija)) $rakija = 0;
if (!isset($cijena)) $cijena = 0;

if (isset($_POST['lijek'])) {
    $sql = 'SELECT * FROM lijekovi WHERE naziv_lijeka = \'' . $_POST['lijek'] . '\'';
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $sql = 'INSERT INTO racun (naziv_lijeka, cijena, kolicina ) VALUES (\'' . $_POST['lijek'] . '\', ' . $row['cijena'] . ', ' . $_POST['kolicina'] . ');';
    mysqli_query($conn, $sql);
}

?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Dobar dan, došli ste na online ljekarnu</h1>
    <form method="post">
        <h2 style="display: inline;">Lijek</h2>
        <select style="display: inline;" name="lijek">
            <option value="Ibuprofen">Ibuprofen</option>
            <option value="Normabel">Normabel</option>
            <option value="Lupocet">Lupocet</option>
            <option value="Rakija">Rakija</option>
        </select>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <h2 style="display: inline;">Količina</h2>
        <select style="display: inline;" name="kolicina">
            <option value="1">1 kom</option>
            <option value="2">2 kom</option>
            <option value="3">3 kom</option>
        </select>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <button type="submit">Unos</button>
    </form>

    <br>
    <form method="post"><button name="kraj">Kraj</button></form>
    <br><br><br><br>

    <?php

    if (isset($_POST['kraj'])) {
        
        echo '<h2>Račun</h2><br><br>';
        echo '<table><tr>';
        echo '<td>Naziv lijeka</td>';
        echo '<td>Količina</td>';
        echo '<td>Cijena</td></tr><tr>';

        $sql = 'SELECT * FROM racun';
        $result = mysqli_query($conn, $sql);
        $ukupno = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<td>' . $row['naziv_lijeka'] . '</td><td>' . $row['kolicina'] . '</td><td>' . ($row['cijena'] * $row['kolicina']) . '</td></tr><tr>';
            $ukupno += $row['cijena'] * $row['kolicina'];
        }
        echo '<td></td><td>Ukupno</td><td>' . $ukupno . '</td></tr><tr>';
        echo '<td></td><td>PDV</td><td>' . ($ukupno * 0.05) . '</td></tr><tr>';
        echo '<td></td><td>Iznos za platiti</td><td>' . ($ukupno * 1.05) . '</td></tr>';
        echo '</table>';

        $conn->query("DELETE FROM racun");
    }

    ?>
</body>

</html>