var AudioContext = window.AudioContext || window.webkitAudioContext;

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

class MusicSync {
    static init() {
        MusicSync.audioContext = null;
        MusicSync.audioAnalyser = null;
        MusicSync.audioSource = null;
        MusicSync.audioFreqArray = null;

        MusicSync.runAudioLoop = false;
        MusicSync.loopExited = true;

        MusicSync.socket = null;
        MusicSync.socketReady = false;
    }
        
    static async start() {
        MusicSync.audioContext = new AudioContext();
        
        MusicSync.audioAnalyser = MusicSync.audioContext.createAnalyser();
        MusicSync.audioAnalyser.fftSize = 2048;
        
        let audioElement = document.getElementById('music-player');
        MusicSync.audioSource = MusicSync.audioContext.createMediaElementSource(audioElement);
        
        MusicSync.audioSource.connect(MusicSync.audioAnalyser);
        MusicSync.audioAnalyser.connect(MusicSync.audioContext.destination);
        
        MusicSync.audioFreqArray = new Uint8Array(MusicSync.audioAnalyser.frequencyBinCount);
        
        MusicSync.socket = new WebSocket('ws://76.236.31.36:6969');
        MusicSync.socket.binaryType = 'arraybuffer';
        
        this.socket.onopen = function(event) {
            console.log('[MusicSync::startMusicSync] Socket is ready!');
            MusicSync.socketReady = true;
        }
        
        this.socket.onerror = function(event) {
            console.log('[MusicSync::startMusicSync] Socket error occurred.');
            MusicSync.controller.setError('Musis Sync socket failed to connect!');
        }
        
        this.socket.onclose = function(event) {
            console.log('[MusicSync::startMusicSync] Socket closed!');
        }
        
        while(MusicSync.socketReady !== true) {
                await new Promise(resolve => setTimeout(resolve, 1));
        }
        
        console.log('[MusicSync::startMusicSync] Music Sync started!');
        
        MusicSync.runAudioLoop = true;
        MusicSync.loopExited = false;
        
        MusicSync.loop();
        
        return new Promise(resolve => {
            resolve();
        });
    }

    static async loop() {
        MusicSync.audioAnalyser.getByteFrequencyData(MusicSync.audioFreqArray);
        
        MusicSync.audioFreqArray[0] = 0x04;
        MusicSync.socket.send(MusicSync.audioFreqArray);

        if (!MusicSync.runAudioLoop) {
            console.log('[MusicSync::musicLoop] Music loop exited!');
            MusicSync.loopExited = true;
            MusicSync.socket.close();
            return;
        }

        await sleep(10);
        
        window.requestAnimationFrame(MusicSync.loop);
    }
}