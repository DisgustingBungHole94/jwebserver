<?php
    require "php/audio_player.php";
?>

<html>
    <head>
        <link rel="stylesheet" href="css/main.css?<?php echo time(); ?>" />
        <link rel="stylesheet" href="css/music_controller.css?<?php echo time(); ?>" />
        <script src="js/musicsync.js"></script>
        <script type="text/javascript">
            MusicSync.init();
            
            var syncStarted = false;
            
            function invoke(action) {
                parent.postMessage(action, '*');
            }
            
            async function startMusicSync() {
                let musicPlayer = document.getElementById('music-player');
                let startButton = document.getElementById('start-player');
                
                if (!syncStarted) {
                    await MusicSync.start();
                    MusicSync.audioContext.resume();
                    
                    musicPlayer.play();
                    startButton.style = 'display: none';
                    
                    syncStarted = true;
                }
            }
            
            async function exitMusicSync() {
                MusicSync.runAudioLoop = false;
                while(!MusicSync.loopExited) {
                    await new Promise(resolve => setTimeout(resolve, 1));
                }

                invoke('exitMusicSync');
            }
            
            window.onload = function() {                
                let musicPlayer = document.getElementById('music-player');
                let startButton = document.getElementById('start-player');
                if (musicPlayer.src !== '') {
                    if (!syncStarted) {
                        startButton.style = 'display: inline';
                    }
                }
            }
        </script>
    </head>
    <body>
        <center>
            <div class="content">
                <div class="row">
                    <div class="cell">
                        <span class="title">Music Upload</span>
                        <br />
                        <br />
                        <span class="text">Select a compatible music file to play.</span>
                        <br />
                        <br />
                        <form enctype="multipart/form-data" action="php/upload_audio.php" method="POST">
                            <input type="file" class="file-upload" name="file-upload" required />
                            <br />
                            <br />
                            <input type="submit" class="button" value="Upload" />
                        </form>
                    </div>
                    <div class="cell">
                        <span class="title">Music Player</span>
                        <br />
                        <br />
                        <?php displayPlayer() ?>
                        <br />
                        <br />
                        <button class="button player-button" id="start-player" onclick="startMusicSync()">Start</button>
                    </div>
                    <div class="cell">
                        <span class="title">Music Search</span>
                        <br />
                        <br />
                        <span class="text">Enter the name of any song to play.</span>
                        <br />
                        <br />
                        <form action="php/search_audio.php" method="POST">
                            <input type="text" class="text-box" name="q" placeholder="Search..." required />
                            <br />
                            <br />
                            <input type="submit" class="button" value="Search" />
                        </form>
                    </div>
                </div>
                <button class="exit-link" onclick="exitMusicSync()">Exit Music Sync</button>
            </div>
        </center>
    </body>
</html>