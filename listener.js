const WebSocket = require('ws');

const ws = new WebSocket('wss://trackback.trackroutepro.com/api/socket', {
  headers: {
    Authorization: 'Bearer YOUR_TOKEN'
  }
});

ws.on('message', function incoming(data) {
    console.log('GPS Data:', data);
});
