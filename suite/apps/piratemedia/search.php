<?php
    require_once 'php/perform_search.php';

    if (!isset($_GET["q"])) {
        header("Location: index.php");
        exit();
    }
?>

<html>
    <head>
        <link rel="stylesheet" href="css/search.css?<?php echo time(); ?>" />
        <script type="text/javascript">
            function back() {
                window.location.href = "index.php";
            }
        </script>
    </head>
    <body>
        <div class="content">
            <?php perform_search($_GET["q"]); ?>
        </div>
    </body>
</html>