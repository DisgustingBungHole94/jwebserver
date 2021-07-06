<?php
    require_once 'php/view_episode.php';
    require_once 'php/view_movie.php';

    if (!isset($_GET['id']) || !isset($_GET['type'])) {
        header("Location: index.php");
        exit();
    }

    if ($_GET['type'] == 'MOVIE') {
        $type = 'MOVIE';
    } else if ($_GET['type'] == 'EPISODE') {
        if (!isset($_GET['season']) || !isset($_GET['episode'])) {
            header("Location: index.php");
            exit();
        }
        $type = 'EPISODE';
    }
?>

<html>
    <head>
        <link rel="stylesheet" href="css/watch.css?<?php echo time(); ?>" />
    </head>
    <body>
        <?php
            /*if ($type == 'MOVIE') {
                view_movie($_GET['id']);
            } else if ($type == 'EPISODE') {
                view_episode($_GET['id'], $_GET['season'], $_GET['episode']);
            }*/
        ?>
        <h1 style="color:white">WILL BE FIXED SOON....</h1>
    </body>
</html>