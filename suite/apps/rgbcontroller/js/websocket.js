class RCSocket {
    constructor(host, port) {
        this.host = host;
        this.port = port;
        
        this.socket = null;
        
        this.data = null;
        this.dataReceived = false;
    }
    
    start(onOpen, onError, onClose) {
        this.socket = new WebSocket('ws://' + this.host + ':' + this.port);
        this.socket.binaryType = 'arraybuffer';
        
        this.socket.onopen = function(event) {
            console.log('[RCSocket::onOpen] Connection is ready!');
            
            onOpen();
        };
               
        this.socket.onerror = function(event) {           
            console.log('[RCSocket::onError] Socket error occurred.');
            
            onError();
        };
        
        this.socket.onclose = function(event) {
            console.log('[RCSocket::onClose] Connection closed!');
            
            onClose();
        };  
        
        let self = this;
        this.socket.onmessage = async function(event) {
            while(self.dataReceived === true) {
                await new Promise(resolve => setTimeout(resolve, 1));
            }
            
            self.data = new Uint8Array(event.data);  
            self.dataReceived = true;
        };
    }
    
    async send(data) {
        if (Object.prototype.toString.call(data) !== '[object Uint8Array]') {
            console.log('[RCSocket::send] Invalid data type!');
            return null;
        }
        
        if (!this.socket || this.socket.readyState !== 1) {
            console.log('[RCSocket::send] Socket is not ready!');
            return null;
        }
        
        this.socket.send(data);
        
        while (!this.dataReceived) {
            await new Promise(resolve => setTimeout(resolve, 1));
        }
        
        //console.log('[RCSocket::send] Data sent, received response!');
        
        let recvBuf = this.data;
        this.data = null;
        this.dataReceived = false;
        
        return recvBuf;
    }
}