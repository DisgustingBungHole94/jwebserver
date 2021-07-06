<html>
    <head>
        <title>Grandma Frogger</title>
        <link rel="stylesheet" href="css/index.css? <?php echo time(); ?>" />
        <script type="text/javascript">
            
            function Object(x, y, w, h, texture) {
                this.x = x;
                this.y = y;
                
                this.w = w;
                this.h = h;
                
                this.texture = texture;
                
                this.draw = function() {
                    cc.drawImage(this.texture, this.x, this.y, this.w, this.h);
                }
            }
            
            function Car(x, y, w, h, direction) {
                let carNum = Math.floor((Math.random() * 3) + 1);
                
                if (direction == 'left') {
                    Object.call(this, x, y, w, h, document.getElementById('car' + carNum + '_left'));
                } else {
                    Object.call(this, x, y, w, h, document.getElementById('car' + carNum + '_right'));
                }
                

                this.direction = direction;
                this.passed = false;
            }
            
            startLoop = false;
            
            window.onload = function() {
                c = document.getElementById('gamecanvas');
                cc = c.getContext('2d');
                
                cc.fillStyle = '#c2c2c2';
                
                NUM_ROWS = 14;
                NUM_COLS = 10;
                
                let playerW = c.width / NUM_COLS;
                let playerH = c.height / NUM_ROWS;
                
                player = new Object(c.width - playerW * (NUM_COLS / 2), c.height - playerH * (NUM_ROWS / 2), playerW, playerH, document.getElementById('player'));
                
                playerDest = player.x;
                
                cars = new Array();
                
                carSpawnTimer = 0;
                carSpawnRow = 0;
                carDefaultDir = true;
                
                deathMsg = new Object((c.width / 2) - (291 / 2), -55, 291, 55, document.getElementById('death_msg'));
                
                screen = 'game';
                
                touchGesture = false;
                
                score = 0;
                
                window.addEventListener('keydown', onKeyDown)
                window.addEventListener('touchstart', onTouch);
                
                if (!startLoop) {
                    setInterval(tick, 1000 / 60); // 60 fps
                    startLoop = true;
                }
            }
            
            function tick() {
                draw();
                update();
            }
            
            function draw() {
                for (let i = 0; i < NUM_ROWS; i++) {
                    cc.fillStyle = (cc.fillStyle == '#000000') ? '#101010' : '#000000';
                    cc.fillRect(0, 0 + ((c.height / NUM_ROWS) * i), c.width, c.height / NUM_ROWS);
                }
                
                player.draw();
                
                for (let i = 0; i < cars.length; i++) {
                    cars[i].draw();
                }
                
                if (screen == 'game') {
                    let oldFill = cc.fillStyle;
                    
                    cc.fillStyle = '#FFFFFF';
                    cc.font = "30px Courier New";
                    cc.fillText("Score: " + score, 0, 50);
                    
                    cc.fillStyle = oldFill;
                }
                
                
                if (screen == 'dead' || screen == 'dead_animate') {
                    deathMsg.draw();
                }
            }
            
            function update() {
                switch(screen) {
                    case 'game':
                        if (playerDest != player.x) {
                            if (Math.abs(player.x - playerDest) < 4) {
                                player.x = playerDest;
                            }
                            
                            if (playerDest > player.x) player.x += 5;
                            if (playerDest < player.x) player.x -= 5;
                        }

                        
                        carSpawnTimer++;
                        if (carSpawnTimer >= 20) {
                            carSpawnTimer = 0;

                            if (carSpawnRow + 1 >= NUM_ROWS) {
                                carSpawnRow = 0;
                            } else {
                                carSpawnRow++;
                            }

                            if (Math.random() > 0.5) {
                                let lCarX = -((c.width / NUM_COLS) * 2);
                                let rCarX = c.width;

                                if (carSpawnRow % 2 == 0) {
                                    cars.push(new Car((carDefaultDir) ? lCarX : rCarX, 0 + ((c.height / NUM_ROWS) * carSpawnRow) - (c.height / NUM_ROWS) * 6, (c.width / NUM_COLS) * 2, c.height / NUM_ROWS, (carDefaultDir) ? 'left' : 'right'));
                                } else {
                                    cars.push(new Car((carDefaultDir) ? rCarX : lCarX, 00 + ((c.height / NUM_ROWS) * carSpawnRow) - (c.height / NUM_ROWS) * 6, (c.width / NUM_COLS) * 2, c.height / NUM_ROWS, (carDefaultDir) ? 'right' : 'left'));
                                }
                            }
                        }
                        break;
                    case 'dead_animate':
                        if (deathMsg.y < (c.height / 2) - (deathMsg.h / 2) - 40) {
                            deathMsg.y += 12;
                        } else {
                            screen = 'dead';
                        }
                        break;
                }
                
                // delete old cars
                if (cars[0] && (cars[0].x + cars[0].w < 0 || cars[0].x > c.width)) {
                    cars.shift();
                }
                
                for (let i = 0; i < cars.length; i++) {                
                    if (cars[i].direction == 'left') {
                        cars[i].x += 1.0;
                    } else {
                        cars[i].x -= 1.0;
                    }
                    
                    if (player.x + player.w > cars[i].x && player.x < cars[i].x + cars[i].w &&
                        player.y <= cars[i].y && player.y - player.h >= cars[i].y - cars[i].h) {
                        screen = 'dead_animate';
                    }
                }
            }
            
            function onKeyDown(e) {
                if (screen == 'dead') {
                    window.onload();
                }
                switch(event.keyCode) {
                    case 87:
                        move('forward');
                        break;
                    case 65:
                        move('left');
                        break;
                    case 68:
                        move('right');
                    default:
                        break;
                }
            }
            
            function onTouch(e) {
                if (screen == 'dead') {
                    window.onload();
                }
                
                if (e.touches[0].clientX < 150) {
                    move('left');
                } else if (e.touches[0].clientX > 850) {
                    move('right');
                } else {
                    move('forward');
                }
            }
            
            function move(direction) {
                if (screen != 'game') return;
                if (playerDest != player.x) return;
                switch (direction) {
                    case 'forward':
                        scroll();
                        break;
                    case 'left':
                        if (player.x - (c.width / NUM_COLS) < 0) break;
                        playerDest = player.x - c.width / NUM_COLS;
                        break;
                    case 'right':
                        if (player.x + (c.width / NUM_COLS) > c.width - player.w) break;
                        playerDest = player.x + c.width / NUM_COLS;
                        break;
                }
            }
            
            function scroll() {
                cc.fillStyle = (cc.fillStyle == '#000000') ? '#101010' : '#000000';
                for (let i = 0; i < cars.length; i++) {
                    cars[i].y += cars[i].h;
                }
                carSpawnRow++;
                carDefaultDir = !carDefaultDir;
                
                score++;
            }
        </script>
    </head>
    <body>
        <center>
            <canvas id="gamecanvas" width="380" height="812"></canvas>
        </center>
        <div class="resources">
            <?php
                if (isset($_GET['player']) && $_GET['player'] == 1) {
                    echo '<img src="img/player_2.png" id="player" />';
                } else {
                    echo '<img src="img/player_1.png" id="player" />';
                }
            ?>
            <img src="img/death_msg.png" id="death_msg" />
            
            <img src="img/car1_left.png" id="car1_left" />
            <img src="img/car1_right.png" id="car1_right" />
            <img src="img/car2_left.png" id="car2_left" />
            <img src="img/car2_right.png" id="car2_right" />
            <img src="img/car3_left.png" id="car3_left" />
            <img src="img/car3_right.png" id="car3_right" />
        </div>
    </body>
</html>