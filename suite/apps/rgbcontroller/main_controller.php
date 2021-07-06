<html>
    <head>
        <link rel="stylesheet" href="css/main.css?<?php echo time(); ?>" />
        <script type="text/javascript">
            function invoke(action) {
                parent.postMessage(action, '*');
            }
        </script>
    </head>
    <body>
        <center>
            <div class="content">
                <div class="row">
                    <div class="cell">
                        <span class="title">Program Selector</span>
                        <br />
                        <br />
                        <select class="selector" id="program-selector">
                            <option value=""></option>
                            <option value="rainbow_fade">Rainbow Fade</option>
                            <option value="psychedelic_fade">Psychedelic Fade</option>
                            <option value="rave">Rave</option>
                            <option value="music_sync">Music Sync</option>
                        </select>
                        <br />
                        <br />
                        <button class="button" onclick="invoke('startProgram')">Start Program</button>
                        <br />
                        <br />
                        <button class="button" onclick="invoke('stopProgram')">Stop Program</button>
                    </div>
                    <div class="cell">
                        <span class="title">Speed Selector</span>
                        <br />
                        <br />
                        <select class="selector" id="speed-selector">
                            <option value=""></option>
                            <option value="very_slow">Very Slow</option>
                            <option value="slow">Slow</option>
                            <option value="medium">Medium</option>
                            <option value="fast">Fast</option>
                            <option value="very_fast">Very Fast</option>
                            <option value="hyperspeed">Hyperspeed</option>
                        </select>
                        <br />
                        <br />
                        <button class="button" onclick="invoke('setSpeed')">Update Speed</button>
                    </div>
                    <div class="cell">
                        <span class="title">Color Selector</span>
                        <br />
                        <br />
                        <input type="color" class="color-picker" id="color-selector" />
                        <br />
                        <br />
                        <button class="button" onclick="invoke('setColor')">Update Color</button>
                    </div>
                </div>
            </div>
        </center>
    </body>
</html>