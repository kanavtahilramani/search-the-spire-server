const http = require('http');
const server = require('../server');

const port = parseInt(process.env.PORT, 10) || 8000;
server.set('port', port);

const host = http.createServer(server);
host.listen(port);