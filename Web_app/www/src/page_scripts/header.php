<div class="header">
    <h3>Prijavljeni ste kao: </h3>
    <?php

    require '../page_scripts/dbh.php';

    $username = $_SESSION['user'];
    $entry = $_SESSION['entry'];

    echo '<h3>' . $username . ' (' . $entry . ')</h3>';

    ?>
    <form action="../page_scripts/logout.php" method="post" id="logout">
        <button class="select" type=submit name="logout-submit">Odjava</button>
    </form>
</div>