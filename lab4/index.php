<!DOCTYPE html>
<html>
<head>
  
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.1/jquery.mobile-1.4.1.min.css" />
  
  <style>
  /*for extra small device*/
  @media only screen and (min-width: 0px) and (max-width:480px)
  {
  	  .responsive_width{
  	  	  width:100%;
  	  	  margin-left:auto;
  	  	  margin-right:auto;
  	  }
  }
  /*for small device*/
  @media only screen and (min-width: 481px) and (max-width: 767px)
  {
  	  .responsive_width{
  	  	  width:80%;
  	  	  margin-left:auto;
  	  	  margin-right:auto;
  	  }
  }
  /*for medium device*/
  @media only screen and (min-width: 768px) and (max-width: 960px)
  {
  	  .responsive_width{
  	  	  width:80%;
  	  	  margin-left:auto;
  	  	  margin-right:auto;
  	  }
  }
  /*for medium to large device*/
  @media only screen and (min-width: 961px) and (max-width: 1440px)
  {
  	  .responsive_width{
  	  	  width:40%;
  	  	  margin-left:auto;
  	  	  margin-right:auto;
  	  }
  }
  /*for large device*/
  @media only screen and (min-width: 1441px) 
  {
  	  .responsive_width{
  	  	  width:40%;
  	  	  margin-left:auto;
  	  	  margin-right:auto;
  	  }
  }
  
  
  
  a, button {
  	  margin-left: auto;
  	  margin-right:auto;
  }
  #login_button, #logout_button, #get_data{
  	  display:none;
  }
 
  
  </style>
  
  <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="http://code.jquery.com/mobile/1.4.1/jquery.mobile-1.4.1.min.js"></script>
  <script>
  //Facebook Login
  //The access token to fetch a user's info from facebook
  var access_token;
  window.fbAsyncInit = function() {
  FB.init({
    appId      : '722200331145073',
    status     : true, // check login status
    cookie     : true, // enable cookies to allow the server to access the session
    xfbml      : true  // parse XFBML
  });

  //Check login status and show appropriate buttons depending on whether the user is logged into the app
  FB.getLoginStatus(function(response){
  	  if(response["status"]=="connected")
  	  {
  	  	  $("#logout_button").show();
  	  	  $("#get_data").show();
  	  }
  	  else
  	  {
  	  	  alert("You need to login to facebook before using this app");
  	  	  destroy_data();
  	  	  $("#login_button").show();
  	  	  $("#logout_button").hide();
  	  	  $("#get_data").hide();
  	  }
  });
  

  //Subscribe to the event when user's login status changed
  FB.Event.subscribe('auth.authResponseChange', function(response) {
    // Here we specify what we do with the response anytime this event occurs. 
    if (response.status === 'connected') {
      $("#login_button").hide();
      $("#logout_button").show();
      $("#get_data").show();
      access_token=response["authResponse"]["accessToken"];
      
    }else {
      destroy_data();
    }
  });
  };

  // Load the Facebook SDK asynchronously
  (function(d){
   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement('script'); js.id = id; js.async = true;
   js.src = "//connect.facebook.net/en_US/all.js";
   ref.parentNode.insertBefore(js, ref);
  }(document));

  //Prompts the user to login 
  function login()
  {
	  FB.login();
  }
  //Log out the user
  function logout()
  {
	  FB.logout();
	  $("#login_button").show();
	  $("#logout_button").hide();
	  $("#get_data").hide();
	  destroy_data();
  }
  //Destroy the data
  function destroy_data()
  {
	  var data=document.getElementById("data");
	  data.innerHTML="";
  }
  
  $(document).ready(function(){
	//Initializing the setting of the jQuery loading animation  
    $( document ).on( "mobileinit", function() {
	  $.mobile.loader.prototype.options.text = "loading";
	  $.mobile.loader.prototype.options.textVisible = false;
	  $.mobile.loader.prototype.options.theme = "a";
	  $.mobile.loader.prototype.options.html = "";
	});
    //Subscribe to the event when user clicks the button, while the app is grabbing data from facebook,
    //a loader image shows at the center of the page
    $(document).on( "click", ".loading", function() {
	  theme = $.mobile.loader.prototype.options.theme,
	  msgText = $.mobile.loader.prototype.options.text,
	  textVisible = $.mobile.loader.prototype.options.textVisible,
	  textonly = false;
	  html = $.mobile.loader.prototype.options.html;
	  $.mobile.loading( 'show', {
	    text: msgText,
	    textVisible: textVisible,
	    theme: theme,
	    textonly: textonly,
	    html: html
	  });

	  //Making a REST call to facebook graph API
	  $.ajax({
		url:"https://graph.facebook.com/me?fields=id,name,bio,birthday,picture,address,education,email,age_range,favorite_teams",
		type:"GET",
		dataType:"",
		data:{access_token: access_token},
		
		ajax:true,
		success: function(data, textStatus, jqXHR){
			var url="https://graph.facebook.com/me?fields=id,name,bio,birthday,picture,address,education,email,age_range,favorite_teams";
			var name="", bio="", bday="", picture_url="", education="", work="", email="", age_range="", favorite_teams="";
			var list="";
			if(data["name"]!=null)
			{
				name=data["name"];
				list+="<li>Name: "+name+"</li>";
			}
			if(data["bio"]!=null)
			{
				bio=data["bio"];
				list+="<li>bio: "+bio+"</li>";
			}
			if(data["birthday"]!=null)
			{
				bday=data["birthday"];
				list+="<li>birthday: "+birthday+"</li>";
			}
			
			if(data["picture"]!=null)
			{
				picture_url=data["picture"]["data"]["url"];
			}
			if(data["education"]!=null)
			{
				education="<li>education: <ul>";
				for(var i=0; i!=data["education"].length; i++)
				{
					education+="<li>"+data["education"][i]["school"]["name"]+"</li>";	
				}
				education+="</ul></li>";
				list+=education;
			}
			if(data["work"]!=null)
			{
				work="<li>work: <ul>";
				for(var i=0; i!=data["work"].length; i++)
				{
					work+="<li>"+data["work"][i]["employer"]["name"]+"</li>";	
				}
				work+="</ul></li>";
				list+=work;
			}
			if(data["email"]!=null)
			{
				email="<li>email: "+data["email"]+"</li>";
				list+=email;
			}
			if(data["age_range"]!=null)
			{
				age_range="<li>age range: <ul>";
				age_range+="<li>from: "+data["age_range"]["min"]+"</li>";
				age_range+="<li>to: "+data["age_range"]["max"]+"</li>";
				age_range+="</ul></li>";
				list+=age_range;
			}
			if(data["favorite_teams"]!=null)
			{
				favorite_teams="<li>favorite teams: "+data["favorite_teams"]+"</li>";
				list+=favorite_teams;
			}
			var content=document.getElementById("data");
			content.innerHTML="<img id='profile' src='"+picture_url+"' width='50' height='50'></div>"+"<ul>"+list+"</ul>";
	    },
		error: function(jqXHR, textStatus, errorThrown){
			alert("failed: "+textStatus+" "+errorThrown+" "+jqXHR.responseText);
		},
		complete: function(jqXHR, textStatus){
			$.mobile.loading("hide");
		}
	  });
	});
  });
  

</script>

<!--
  Below we include the Login Button social plugin. This button uses the JavaScript SDK to
  present a graphical Login button that triggers the FB.login() function when clicked. -->

  <title>Lab4 - jQuery Mobile</title>
</head>
<body>
  <div class="main" data-role="page" data-position="fixed" data-fullscreen="true">
    <div data-role="header">
      <h1>This is lab 4</h1>
      <div id="fb-root"></div>
    </div>
        
    <div class="content" data-role="content">
      <div class="responsive_width center_text">
        <h2>Content</h2>
        <div id="tmp"></div>
        <div id="data">
        </div>
      </div>
      
      <div class="responsive_width"><button id="login_button" onclick="login()" data-inline="false">Login to Facebook</button></div>
      <div class="responsive_width"><button id="logout_button" onclick="logout()" data-inline="false">Logout</button></div>
      <div class="responsive_width"><button id="get_data" class="loading responsive_width" data-inline="false">Get my facebook data</a></button></div>
    </div>
        
    <div class="footer" data-role="footer" data-position="fixed" data-fullscreen="true">
      <h1 id="date"></h1>
      <script>$("#date").html(new Date());</script>
    </div>
  </div>
</body>
</html>
