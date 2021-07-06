<?php
    require_once 'php/view_series.php';

    if (!isset($_GET["id"])) {
        header("Location: index.php");
        exit();
    }
?>

<html>
    <head>
        <link rel="stylesheet" href="css/series.css?<?php echo time(); ?>" />
    </head>
    <body>
        <?php view_series($_GET["id"]); ?>
    </body>
</html>