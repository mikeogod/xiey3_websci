<?php 
$curl=curl_init();
//$url="http://openweathermap.org/data/2.5/weather?lat=42.7299661&lon=-73.6767256";
$url="api.openweathermap.org/data/2.5/forecast?q=Troy,us";
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$data=json_decode(curl_exec($curl), true);
$data=array_slice($data["list"], 0, 5);
$output="";

$address='xiey3@rpi.edu';
$reply_to='xiey3@rpi.edu';
$headers='From: My Weather Alert'. "\r\n". "Reply-To: $reply_to";
$subject="Weather Alert";
$rain=false;
$snow=false;
foreach($data as $item)
{
	if(isset($item["rain"]) && (int)$item["rain"]["3h"] > 0)
	{
		$rain=true;
	}
	if(isset($item["snow"]) && (int)$item["snow"]["3h"] > 0)
	{
		$snow=true;
	}
}
if($rain)
{

	$output.="Watch out for the rain today! \n";
}
else
{
	$output.="There's no rain today! \n";
}
if($snow)
{
	$output.="Watch out for the snow today! \n";
	
}
else
{
	$output.="There's no snow today! \n";
}

if($snow || $rain)
{
	$mail_sent=mail($address, $subject, $output, $headers);
}

curl_close($curl);
?>

<!-- apikey: 3378f6c9f75811a6a6b31d2d26f32b2f -->
<!DOCTYPE html>
<html>
<head>
  <title>Lab2-Weather App</title>
  
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">

  <!-- Optional theme -->
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap-theme.min.css">

  <style>
  	.navbar{
  	  border-radius: 0px;
  	  box-shadow:5px 5px 10px grey;
  	}
    .info-box{
      padding: 10px;
      border-radius: 5px;
      box-shadow:5px 5px 10px grey;
      background-color: rgb(240, 255, 255);
    }
    .temperature-container{
      font-size: 300%;    	
    }
    .info-container{
      font-size: 120%;
    }
    #mapholder{
      padding: 10px;
      border-radius: 5px;
      box-shadow:5px 5px 10px grey;
      background-color: rgb(240, 255, 255);
    }
  </style>
  
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="http://maps.google.com/maps/api/js?sensor=false"></script>
  
  <!-- Latest compiled and minified JavaScript -->
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
  
  
  <script></script>
  <script>
  $(document).ready(function(){

	  var lat, lon;
	  var image_url;

	  //The callback funtion will be my Main function, in which the ajax call executes.
	  function getLocation(callback)
	  {
	      if (navigator.geolocation)
	      {
	          navigator.geolocation.getCurrentPosition(callback, showError);
	      }
	      else
	      {
		      $("#mapholder").html("<p>Geolocation is not supported by this browser</p>");
		      $("#weather").html("<p>Geolocation is not supported by this browser</p>");
	      }
	  }
	  
	  function showPositionText(position)
	  {
	      my_position.innerHTML = "Latitude: " + position.coords.latitude + 
	      "<br>Longitude: " + position.coords.longitude; 
	  }

	  //Display the map of your current location
	  function showPositionMap(position)
	  {
	  	  var latlon = position.coords.latitude + "," + position.coords.longitude;
	      
	 	  img_url = "http://maps.googleapis.com/maps/api/staticmap?center="+latlon+"&zoom=12&size=470x330&sensor=false";

	  }

	  //Error handling in case of denied request of user location
	  function showError(error)
	  {
		  switch(error.code) 
		    {
		    case error.PERMISSION_DENIED:
		      $("#mapholder").html("<p>User denied the request for Geolocation.</p>");
		      $("#weather").html("<p>User denied the request for Geolocation.</p>");
		      break;
		    case error.POSITION_UNAVAILABLE:
			  $("#mapholder").html("<p>Location information is unavailable.</p>");
			  $("#weather").html("<p>Location information is unavailable.</p>");
		      break;
		    case error.TIMEOUT:
			  $("#mapholder").html("<p>The request to get user location timed out.</p>");
			  $("#weather").html("<p>The request to get user location timed out.</p>");
		      break;
		    case error.UNKNOWN_ERROR:
			  $("#mapholder").html("<p>An unknown error occurred.</p>");
		      $("weather").html("<p>An unknown error occurred.</p>")
		      break;
		    }
	  }

	  //This is only for visualizing return json data
	  function printToTmp(object, depth)
	  {
		  var output="";
		  for(key in object)
		  {
			  if(!$.isPlainObject(object[key]))
			  {
				  if((typeof object[key])=="object")
				  {
					  var d="";
					  for(var i=0; i!=depth; i++)
					  {
						  d+="--";
					  }
					  output+="<p>"+key+"</p>";
					  for(index in object[key])
					  {
						  output+="<p>"+d+printToTmp(object[key][index], depth+1)+"</p>";
					  }
					  
				  }
				  else
				  {
					  var d="";
					  for(var i=0; i!=depth; i++)
					  {
						  d+="--";
					  }
				  	  output+="<p>"+d+key+": "+object[key]+"</p>";
				  }
			  }
			 
			  else
			  {
				  
				  output+="<p>"+key+"</p>"+printToTmp(object[key], depth+1);
			  }
		  }

		  if(depth==0)
		  {
		  	  $("#tmp").html(output);
		  }
		  else
		  {
			  return output;
		  }
	  }

	  //Main Code
	  getLocation(Main);

	  function Main(position)
	  {
		  lat=position.coords.latitude;
		  lon=position.coords.longitude;
		  
		  //showPositionText(position);
		  showPositionMap(position);
		  $.ajax({
		  	  url:"http://openweathermap.org/data/2.5/weather?lat="+lat.toString()+"&lon="+lon.toString(),
		  	  type:"GET",
		  	  dataType:"jsonp",
		  	  data:"",
		  	  headers:{"x-api-key":"3378f6c9f75811a6a6b31d2d26f32b2f"}, 
		  	  ajax:true,
		  	  success:function(data, textStatus, jqXHR){
			  	  /*for (key in data)
			  	  {
				  	  printToTmp(data, 0);
			  	  }*/

			  	  var city_name=data["name"];
			  	  var wind_speed=data["wind"]["speed"];
			  	  var humidity=data["main"]["humidity"];

			  	  //Temperatures are stored in the three variables as floating point numbers.
			  	  //Makes it easy to convert between units(C and F)
			  	  var temperature_float_C=parseInt(data["main"]["temp"])-273.15;
			  	  var temperature=((parseInt(data["main"]["temp"])-273.15).toFixed(0)).toString();
			  	  
			  	  var temp_min_float_C=parseInt(data["main"]["temp_min"])-273.15;
				  var temp_min=((parseInt(data["main"]["temp_min"])-273.15)).toFixed(0).toString();
				  
				  var temp_max_float_C=parseInt(data["main"]["temp_max"])-273.15;
				  var temp_max=((parseInt(data["main"]["temp_max"])-273.15)).toFixed(0).toString();
				  
				  var weather_desc=data["weather"][0]["description"];
				  var icon="http://openweathermap.org/img/w/"+data["weather"][0]["icon"]+".png";
				  var sunset=data["sunset"];
				  var sunrise=data["sunrise"];

				  var temperature_unit="&degC";

				  $("#weather #city_name").html(city_name);
				  $("#weather #weather_desc").html(weather_desc);
				  $("#weather #humidity").html("Humidity: "+humidity+"%");
				  $("#weather #temperature_min").html("Temperature low: "+temp_min);
				  $("#weather #temperature_max").html("Temperature high: "+temp_max);
				  $("#weather #sunset").html(sunset);
				  $("#weather #sunrise").html(sunrise);
				  
				  $("#weather #temperature").html(temperature+"<sup style='font-size:50%'>"+temperature_unit+"</sup>");
				  $("#weather #weather_icon").attr("src", icon);
				  
				  $("#mapholder img").attr("src", img_url);

				  //The unit conversion button
				  $("#weather #change_unit").on("click", function(e){
				  	if($(this).html()=="Change to Fahrenheit")
				  	{
					  	//Change all temp displayed to Fahrenheit
					  	temperature_unit="&degF";

					  	//Unit conversion starts
					  	$("#weather #temperature").html((temperature_float_C*1.8+32).toFixed(0).toString()
							  	+"<sup style='font-size:50%'>"+temperature_unit+"</sup>");
					  	$("#weather #temperature_min").html("Temperature low: "+(temp_min_float_C*1.8+32).toFixed(0).toString()
							  	+"<sup style='font-size:50%'>"+temperature_unit+"</sup>");
					  	$("#weather #temperature_max").html("Temperature high: "+(temp_max_float_C*1.8+32).toFixed(0).toString()
							  	+"<sup style='font-size:50%'>"+temperature_unit+"</sup>");

					  	$(this).html("Change to Celsius");
				  	}
				  	else if($(this).html()=="Change to Celsius")
				  	{
					  	//Change all temp displayed to Celsius
					  	temperature_unit="&degC";
					  	$("#weather #temperature").html(temperature_float_C.toFixed(0).toString()
							  	+"<sup style='font-size:50%'>"+temperature_unit+"</sup>");
					  	$("#weather #temperature_min").html("Temperature low: "+temp_min_float_C.toFixed(0).toString()
							  	+"<sup style='font-size:50%'>"+temperature_unit+"</sup>");
					  	$("#weather #temperature_max").html("Temperature high: "+temp_max_float_C.toFixed(0).toString()
							  	+"<sup style='font-size:50%'>"+temperature_unit+"</sup>");
							 
					  	
					  	$(this).html("Change to Fahrenheit");
				  	}
				  });	    	  
			  },
			  
		  	  error:function(jqXHR, textStatus, errorThrown){
	              $("#tmp").html("Something wrong has occured!");
			  }
		  });
	  }
	  
	  
  });

  </script>
</head>

<body>
  <div class="navbar navbar-inverse fixed-top">
    <div class="container">
      <div class="navbar-header text-left">
        <h3 style="color:white">
          Hello! Welcome to lab2!<br />
          <small>Here is the current weather of your location: </small>	
        </h3>
      </div>
    </div>
  </div>
  
  <div class="fluid-container">
  
    <div class="row">
    
      <div class="col-md-2 hidden-xs">
      </div>
    
      <div class="col-md-4 col-xs-12">
        
        <div id="weather" class="info-box">
          <div class="row">
            <div class="col-md-6">
              <div class="weather-icon-container text-center">
                <img id="weather_icon" width="100" height="100">
              </div>
              <div class="temperature-container text-center">
                <p id="temperature"></p>
                
              </div>
            </div>
            <div class="col-md-6">
              <div class="info-container text-center">
                <p id="city_name"></p>
                <p id="weather_desc"></p>
                <p id="humidity"></p>
                <p id="temperature_max"></p>
                <p id="temperature_min"></p>
                <p id="sunset"></p>
                <p id="sunrise"></p>
                <button id="change_unit" class="btn btn-sm btn-block">Change to Fahrenheit</button>
              </div>
            </div>
          </div>
        </div>
        
        <p id="test"></p>
        <div id="tmp"></div>
      </div>
    
      <div class="col-md-4 col-xs-12">
        
        <div id="mapholder" class="text-center" >
          <img class="image-rounded">
        </div>
      </div>
    
      <div class="col-md-2 hidden-xs">
      </div>
    </div>
  </div>
</body>

</html>

<?php
