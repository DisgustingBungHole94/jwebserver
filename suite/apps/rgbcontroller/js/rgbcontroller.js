class RGBController {
    constructor(host, port) {
        this.socket = new RCSocket(host, port);
        this.socketConnected = false;
        
        this.currentScreen = null;
        
        this.contentFrame = document.getElementById('content-frame');
        
        this.mainActionHandler = null;
        this.musicActionHandler = null;
    }
    
    start() {
        this.setScreen('default');
        
        this.mainActionHandler = new MainActionHandler(this);
        this.musicActionHandler = new MusicActionHandler(this);
        
        let self = this;
        this.socket.start(async function() {
            self.socketConnected = true;
            
            self.populatePage();
        }, function() {
            self.contentFrame.contentDocument.getElementById('default-text').innerHTML = 'failed to connect :(';
        }, function() {
            if (self.socketConnected) {
                self.setError('Server connection lost! Please refresh the page.');
                self.socketConnected = false;
            }
        });
    }
    
    async setScreen(screen) {
        switch(screen) {
            case 'default':
                this.contentFrame.src = 'default.php';
                this.currentScreen = 'default';
                console.log('[RGBController::setScreen] Screen set to [default].');
                break;
            case 'main_controller':
                this.contentFrame.src = 'main_controller.php';
                this.currentScreen = 'main_controller';
                console.log('[RGBController::setScreen] Screen set to [main_controller].');
                break;
            case 'music_controller':
                this.contentFrame.src = 'music_controller.php';
                this.currentScreen = 'music_controller';
                console.log('[RGBController::setScreen] Screen set to [music_controller].');
                break;
            default:
                console.log('[RGBController::setScreen] Invalid screen specified!');
                return;
                break;
        }
        
        let frameLoaded = false;
        this.contentFrame.addEventListener('load', function() {
            frameLoaded = true;
        })
        
        while(!frameLoaded) {
            await new Promise(resolve => setTimeout(resolve, 1));
        }
    }
    
    setError(error) {
        let responseDiv = document.getElementById('response');
        let errorDiv = document.getElementById('error');
        
        responseDiv.innerHTML = '';
        errorDiv.innerHTML = '<span class="response-text">' + error + '</span>';
    }
    
    setResponse(response) {
        let responseDiv = document.getElementById('response');
        let errorDiv = document.getElementById('error');
        
        responseDiv.innerHTML = '<span class="response-text">' + response + '</span>';
        errorDiv.innerHTML = '';
    }
    
    clear() {
        let errorDiv = document.getElementById('error');
        errorDiv.innerHTML = '';
        
        let responseDiv = document.getElementById('response');
        responseDiv.innerHTML = '';
    }
    
    async populatePage() {
        let sendBuf = new Uint8Array(1);
            sendBuf[0] = 0x00;
            
            let data = null;
            do {
                data = await this.socket.send(sendBuf);
                if (!data) break;
            } while (data[0] == 0x00);
        
            if (!data || data.length !== 7) {
                this.setError('Warning: Failed to retrieve server status!');
                console.log(data);
                return;
        }
        
        switch(data[2]) {
            case 0x00:
                await this.setScreen('main_controller');
                this.contentFrame.contentDocument.getElementById('program-selector').value = '';
                break;
            case 0x01:
                await this.setScreen('main_controller');
                this.contentFrame.contentDocument.getElementById('program-selector').value = 'rainbow_fade';
                break;
            case 0x02:
                await this.setScreen('main_controller');
                this.contentFrame.contentDocument.getElementById('program-selector').value = 'rave';
                break;
            case 0x03:
                await this.setScreen('music_controller');
                break;
            case 0x04:
                await this.setScreen('main_controller');
                this.contentFrame.contentDocument.getElementById('program-selector').value = 'psychedelic_fade';
                break;
            default:
                this.setError('Warning: Failed to retrieve server status!');
                console.log('[RGBController::populatePage] Server returned invalid program.');
                break;
        }
        
        if (this.currentScreen == 'main_controller') {
            let speedSelector = this.contentFrame.contentDocument.getElementById('speed-selector');
            let colorSelector = this.contentFrame.contentDocument.getElementById('color-selector');

            switch(data[1]) {
                case 0x00:
                    speedSelector.value = 'very_slow';
                    break;
                case 0x01:
                    speedSelector.value = 'slow';
                    break;
                case 0x02:
                    speedSelector.value = 'medium';
                    break;
                case 0x03:
                    speedSelector.value = 'fast';
                    break;
                case 0x04:
                    speedSelector.value = 'very_fast';
                    break;
                case 0x05:
                    speedSelector.value = 'hyperspeed';
                    break;
                default:
                    console.log('[RGBController::populatePage] Server returned invalid speed.');
                    break;
            }

            let r = Number(data[3]).toString(16); if (r.length < 2) { r = '0' + r; }
            let g = Number(data[4]).toString(16); if (g.length < 2) { g = '0' + g; }
            let b = Number(data[5]).toString(16); if (b.length < 2) { b = '0' + b; }
            colorSelector.value = '#' + r + g + b;
        }
    }
}