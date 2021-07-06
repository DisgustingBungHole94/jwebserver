<html>
    <head>
        <link rel="stylesheet" href="css/index.css?<?php echo time(); ?>" />
        <script type="text/javascript" src="js/websocket.js"></script>
        <script type="text/javascript" src="js/mainactionhandler.js"></script>
        <script type="text/javascript" src="js/musicactionhandler.js"></script>
        <script type="text/javascript" src="js/rgbcontroller.js"></script>
        <script type="text/javascript">
            window.onload = function() {
                controller = new RGBController('76.236.31.36', '6969');
                //
                window.addEventListener('message', function(action) {
                    switch(controller.currentScreen) {
                        case 'default':
                            break;
                        case 'main_controller':
                            controller.mainActionHandler.execute(action);
                            break;
                        case 'music_controller':
                            controller.musicActionHandler.execute(action);
                            break;
                        default:
                            console.log('[::executeAction] Current screen is invalid.');
                            break;
                    }
                }, false);
                //
                controller.start();
            }
        </script>
    </head>
    <body>
        <center>
            <div class="header">
                <span class="title">rgb controller</span>
            </div>
            <div class="content">
                <div id="error" class="error">
                    <?php
                        session_start();
                    
                        if (isset($_SESSION['serverError'])) {
                            echo '<span class="response-text">' . $_SESSION['serverError'] . '</span>';
                            unset($_SESSION['serverError']);
                        }
                    ?>
                </div>
                <div id="response" class="response"></div>
                <iframe id="content-frame" class="content-frame" scrolling="yes"></iframe>
            </div>
        </center>
    </body>
</html>
