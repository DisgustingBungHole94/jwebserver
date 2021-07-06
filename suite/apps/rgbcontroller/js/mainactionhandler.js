class MainActionHandler {
    constructor(controller) {
        this.controller = controller;
    }
    
    // call from parent
    execute(event) {
        controller.clear();
        
        switch(event.data) {
            case 'startProgram':
                this.startProgram();
                break;
            case 'stopProgram':
                this.stopProgram();
                break;
            case 'setSpeed':
                this.setSpeed();
                break;
            case 'setColor':
                this.setColor();
                break;
            default:
                console.log('[MainActionHandler::handleAction] Received request for unknown action!');
                break;
        }
    }
    
    async startProgram() {
        let sendBuf = new Uint8Array(2);
        sendBuf[0] = 0x02;
        
        let musicMode = false;
        switch(this.controller.contentFrame.contentDocument.getElementById('program-selector').value) {
            case '':
                return;
                break;
            case 'rainbow_fade':
                sendBuf[1] = 0x01;
                break;
            case 'psychedelic_fade':
                sendBuf[1] = 0x04;
                break;
            case 'rave':
                sendBuf[1] = 0x02;
                break;
            case 'music_sync':
                sendBuf[1] = 0x03;
                musicMode = true;
                break;
            default:
                console.log('[MainActionHandler::startProgram] Unknown program specified!');
                return;
                break;
        }
        
        let recvBuf = await this.controller.socket.send(sendBuf);
        if (!recvBuf) {
            console.log('[MainActionHandler::startProgram] Failed to send over socket!');
            return;
        }
        
        console.log('[MainActionHandler::startProgram] Successfully executed!');
        
        if (musicMode) {
            if (recvBuf[0] != 0x00) {
                this.controller.setError('Music Sync could not start: Program failed to execute.');
            } else {
                this.controller.setScreen('music_controller');
            }
        } else {
            this.handleActionResponse(recvBuf);
        }
    }
    
    async stopProgram() {
        let sendBuf = new Uint8Array(1);
        sendBuf[0] = 0x03;
        
        let recvBuf = await this.controller.socket.send(sendBuf);
        if (!recvBuf) {
            console.log('[MainActionHandler::stopProgram] Failed to send over socket!');
            return;
        }
        
        this.controller.contentFrame.contentDocument.getElementById('program-selector').value = '';
        
        console.log('[MainActionHandler::stopProgram] Successfully executed!');
        this.handleActionResponse(recvBuf);
    }
    
    async setSpeed() {
        let sendBuf = new Uint8Array(2);
        sendBuf[0] = 0x05;
        
        let speed = 0;
        
        switch(this.controller.contentFrame.contentDocument.getElementById('speed-selector').value) {
            case '':
                return;
                break;
            case 'very_slow':
                speed = 0x00;
                break;
            case 'slow':
                speed = 0x01;
                break;
            case 'medium':
                speed = 0x02;
                break;
            case 'fast':
                speed = 0x03;
                break;
            case 'very_fast':
                speed = 0x04;
                break;
            case 'hyperspeed':
                speed = 0x05;
                break;
            default:
                console.log('[MainActionHandler::setSpeed] Unknown speed specified!'); 
                return;
                break;
        }
        sendBuf[1] = speed;
        
        let recvBuf = await this.controller.socket.send(sendBuf);
        if (!recvBuf) {
            console.log('[MainActionHandler::setSpeed] Failed to send over socket!');
            return;
        }
        
        console.log('[MainActionHandler::setSpeed] Successfully executed!');
        this.handleActionResponse(recvBuf);
    }
    
    async setColor() {
        let hexColor = this.controller.contentFrame.contentDocument.getElementById('color-selector').value;
        
        var rgbColor = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hexColor);
        rgbColor = rgbColor ? {
            r: parseInt(rgbColor[1], 16),
            g: parseInt(rgbColor[2], 16),
            b: parseInt(rgbColor[3], 16)
        } : null;
        
        let sendBuf = new Uint8Array(4);
        sendBuf[0] = 0x01;
        sendBuf[1] = rgbColor.r;
        sendBuf[2] = rgbColor.g;
        sendBuf[3] = rgbColor.b;
        
        console.log(sendBuf);
        
        let recvBuf = await this.controller.socket.send(sendBuf);
        if (!recvBuf) {
            console.log('[MainActionHandler::setColor] Failed to send over socket!');
            return;
        }
        
        console.log('[MainActionHandler::setColor] Successfully executed!');
        this.handleActionResponse(recvBuf);
    }
    
    handleActionResponse(recvBuf) {
        switch(recvBuf[0]) {
            case 0x00:
                this.controller.setResponse('Command completed successfully!');
                console.log('[MainActionHandler::handleActionResponse] Action completed with no errors.');
                break;
            case 0x01:
                this.controller.setError('Invalid command!');
                break;
            case 0x02:
                this.controller.setError('Failed to set color!');
                break;
            case 0x03:
                this.controller.setError('The specified program does not exist!');
                break;
            case 0x04:
                this.controller.setError('No program is currently running!');
                break;
            case 0x05:
                this.controller.setError('Invalid buffer size for specified command!');
                break;
            case 0x06:
                console.log('[MainActionHandler::handleActionResponse] Received server info response.');
                break;
            default:
                this.controller.setError('An unknown error occurred.');
                break;
        }
    }
}