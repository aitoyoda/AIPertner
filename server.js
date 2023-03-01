const http = require("http");
const settings = require("./settings.js");
const server = http.createServer();

server.on("request", function (req, res) {
    res.writeHead(200, { "Content-Type": "text/plain" });
    res.write("Hello World");
    res.end();
});

server.listen(settings.port, settings.host);
console.log("server listen...");