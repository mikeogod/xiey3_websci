<!DOCTYPE html>
<html>
<head>
  <title>Lab1-HTML5, CSS3, JS</title>
  <link rel="stylesheet" href="">
  <style>
  	#tweets_container{
  		margin:auto;
  		background-color: rgb(100,200,255);
  		width: 80%;
  	}
    #tweets {
    	background-color: rgb(150,150,255);
    	border: groove grey 0.2em;
    	width: 70%;
    	height: 600px;
    	position: relative;
    	left: 15%;
    	right: 15%;
    	overflow: hidden;
    }
    #tweets ul{
    	margin: 0px;
    }
    #tweets ul li{
    	list-style: none;
    	background-color: #ffffff;
    	border-top: solid rgb(170,170,255);
    	position: absolute;
    	left: -5px;
    	width: 100%;
    	height: 20%;
    	overflow: hidden;
    }
    #tweets ul li img{
    	float: left;
    	position: absolute;
    	top: 0px;
    	left: 5px;
    	height:100%;
    }
    #tweets ul li div{
    	width: 85%;
    	height: 100%;
    	float: right;
    }
    #tweets ul li div p{
    	margin: 0px;
    	font-style: italic;
    }
    #tweets ul li div .text{
    	font-size: 120%;
    	color: rgb(150,150,255);
    	position: relative;
    	left: 20px;
    }
    #tweets ul li div .created_at{
    	font-size: 80%;
    }
    
    
  </style>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script>
  	//This function grabs a tweet from an array of tweets and pushes it to the current tweet stream
    function grab_next_tweet(current_index, tweets)
    {
		//Calculate the height of a single tweet dynamically 
        var tweet_h=0.2*document.getElementById("tweets").clientHeight;
		tweet_h=tweet_h.toString();
        
        //Get the fields that we need: created_at, text, user, place, entities, and compile them in a <li>
        var output="<li><div>";

		//arr is used for parsing datetime information
       	var arr=new Array();
       	arr=tweets[current_index]["created_at"].split(" ");
       	
        output+="<p class='created_at'> At "+arr[3]+" "+arr[1]+" "+arr[2]+"</p>";
        output+="<p class='user_name'>"+tweets[current_index]["user"]["name"]+" tweets: </p>";
        output+="<p class='text'>"+tweets[current_index]["text"]+"</p>";
        if(tweets[current_index]["place"]!=null)
        {
        	output+="<p class='place'>In: "+tweets[current_index]["place"]["full_name"]+"</p>";
        }
        output+="<img src='"+tweets[current_index]["user"]["profile_image_url"]+"' alt='profile image' >";
		output+="</div></li>";
		
		if($("#tweets ul li").length==0)
		{
			$("#tweets ul").prepend(output).hide().fadeIn(200);
		}
		else
		{
			//Animate each tweet down with the distance equal to the height of the each tweet
	        $("#tweets ul li").each(function(i){
		        $(this).animate({top: "+="+tweet_h}, 400, "swing", function(){
					if(i==0)
					{
						$("#tweets ul").prepend(output);
			    		$("#tweets ul li:first-child").hide();
			        	$("#tweets ul li:first-child").fadeIn(200);
					}
					else if(i>5)
		            {
		        		$(this).remove();
		            }
	        	});
	        });
		}
    }
    
    $(document).ready(function(){
  
    		$.ajax({
			    url:"tweets.json",
				type:"GET",
				ajax: true,
				dataType: "json",
				success: function(data, textStatus, jqXHR){
					/*var str="";
					for(field in data[0])
					{
						str+="<p>"+field+"</p>";
						if($.isPlainObject(data[0][field]))
						{
							for(sub_field in data[0][field])
							{
								str+=("<p> -- "+sub_field+"</p>");
							}
						}
					}
					$("#tmp").html(str);*/

					
					var num=0;
		    		window.setInterval(function(){
		        		grab_next_tweet(num, data);
		        		num+=1;
		        		if(num==150)
		        		{
			        		num=0;
		        		}
		        	}, 3000);
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert("Failed! "+textStatus+" "+errorThrown);
		  	    }
			})
    });
  </script>
</head>
<body>
  <div id="tmp"></div>
  <div id="tweets_container">
  	<div id="tweets">
      <ul id="stream">
      
      </ul>
    </div>
  </div>
</body>
<?php