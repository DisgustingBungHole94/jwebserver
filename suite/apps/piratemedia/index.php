<html>
    <head>
        <link rel="stylesheet" href="css/index.css?<?php echo time(); ?>" />
    </head>
    <body>
        <?php if (isset($_GET["err"])): ?>
            <p>Error!</p>
        <?php endif; ?>
        
        <div class="content">
            <center>
                <span id="title-one">Pirate</span><span id="title-two">Media</span>
                <br /><br />
                <span id="info"><i>Hentai free entertainment provided by Josh Dittmer!</i></span>
                <br /><br />
                <form action="search.php" method="get">
                    <input id="search" type="text" name="q" placeholder="Search..." required />
                    <input id="submit" type="submit" value="Search" />
                </form>
            </center>
        </div>
    </body>
</html>