<!Doctype html>
<html>
    <?php
    session_start();

    if (!isset($_SESSION["user_id"])) die("No Login! <a href='home.php'> Log here! </a>");

    session_unset();
    session_destroy();
    ?>
</html>