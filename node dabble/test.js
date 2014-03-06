var http = require('http');
var sys=require('sys');
var fs=require('fs');
var twit=require('ntwitter');

sys.puts("Hello! World!");

setTimeout(function(){
	console.log("World!");
	
}, 2000);
console.log("Hello");

//Create the server. Function passed as parameter is called on every request made.
//request variable holds all request parameters
//response variable allows you to do anything with response sent to the client.
http.createServer(function (request, response) {
     // Write headers to the response.
     // 200 is HTTP status code (this one means success)
     // Second parameter holds header fields in object
     // We are sending plain text, so Content-Type should be text/plain
     response.writeHead(200, {
         'Content-Type': 'text/plain'
     });
     
     response.write('8080 is listening! \n');
     // Send data and end response.
     response.end('Hello HTTP!');
//Listen on the 8080 port.
}).listen(8080);

console.log('Listening on http://127.0.0.1:8080');

var sw='-73.68,42.72', ne='-73.67,42.73'; //  RPI
twit.stream('statuses/filter', {'locations':sw +','+ne},
function(stream) {
  stream.on('data', function (data) {
    console.log(data);
  });
});
