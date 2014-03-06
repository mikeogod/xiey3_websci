var twitter = require('ntwitter');
var http=require('http');
var fs=require('fs');
var express=require('express');

var app=express();

var twit = new twitter({
  consumer_key: 'C3nXQBz9wGAoMlcB2oVjdg',
  consumer_secret: 'SVxhd1Lt1lj68S1fFwHwRb3QA0XFfB1iylKQwkpmDk',
  access_token_key: '143462620-SnxAJFHhBBPnCXjyaqbFuMfjnlQz3TJ6yA2pTC9s',
  access_token_secret: 'X4ge2S04xsPTaL7S9o60iWobv2kFnCkqhOIwEuRntuBlV'
});

app.get('/', function(req, res){
	res.send('Hello!');
});
console.log(app.routes);
app.listen(8000);

//http.createServer(function(request, response) {
//   response.writeHead(200, {
//      'Content-type': 'text/plain'
//   });
//   response.end('Hello HTTP!');
//}).listen(8000);
console.log('Listening on http://127.0.0.1:8000');

var sw='-73.68,42.72', ne='-73.67,42.73'; //  RPI
twit.stream('statuses/filter', {'locations':sw +','+ne},
function(stream) {
  stream.on('data', function(new_tweet) {
    console.log("One tweet came in");
    var old_content_str=fs.readFileSync('lab4-MikeXie-661058666.json', {encoding:'utf-8', flag: 'r'});
    old_content_str=old_content_str.trim();
    if(old_content_str=='')
    {
    	var old_content=new Array();
    }
    else
    {
    	var old_content=JSON.parse(old_content_str);
   	}
    
    //If there are 1000 existing tweets, stop and destroy the stream
    console.log('There are '+old_content.length.toString()+' tweets');
    if(old_content.length==1000)
    {
    	stream.destroy();
    }
    else
    {
    	old_content.push(new_tweet);
    	var updated_content=old_content;
    	var updated_content_str=JSON.stringify(updated_content);
    	fs.writeFileSync('lab4-MikeXie-661058666.json', updated_content_str, {encoding:'utf-8', flag: 'w'});
    }
	
  });
  
});
