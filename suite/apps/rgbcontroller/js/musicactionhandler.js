class MusicActionHandler {
    constructor(controller) {
        this.controller = controller;
    }
    
    execute(event) {
        controller.clear();
        
        switch(event.data) {
            case 'exitMusicSync':
                this.exitMusicSync();
                break;
            default:
                console.log('[MusicActionHandler::handleAction] Received request for unknown action!');
                break;
        }
    }
    
    async exitMusicSync() {
        let sendBuf = new Uint8Array(1);
        sendBuf[0] = 0x03;
        
        let recvBuf = await this.controller.socket.send(sendBuf);
        if (!recvBuf || recvBuf[0] !== 0x00) {
            console.log('[MusicActionHandler::exitMusicSync] Failed to stop program!');
            this.controller.setError('Failed to exit Music Sync. Please try refreshing the page.');
            return;
        }
        
        await this.controller.populatePage();
        console.log('[MusicActionHandler::startMusicSync] Music Sync stopped!');
    }
}