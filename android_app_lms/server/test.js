var express   =    require("express");
var mysql     =    require('mysql');
var bodyParser = require('body-parser');
var app = express();

var server = require('http').createServer(app); 
var io = require('socket.io')(server);
app.use(bodyParser.urlencoded({ 
  							      extended: true
						      })); 

var pool = mysql.createPool({
							    connectionLimit : 10000,
							    host     : 'enter_host_name',
							    user     : 'user',
							    password : 'passwd',
							    database : 'db_name',
							    debug    :  false
							});

var response1 = {};

function sendDeliveryBoyInfo(socket)
{
	pool.getConnection(function(error, connection)
					   {
					   	   if(error)
					   	   {
					   	   	   console.log("Error in connecting to database.");
					   	   	   return;
					   	   }
					       query = "SELECT uid, name, phone, gcm_id FROM delivery_boy_info;";
					       response1 = {};
					       connection.query(query, function(error, result)
					       					{
					       						if(error)
					       						{
					       							console.log("Cannot execute query : " + query);
					       							return;
					       						}
					       						response1.no_of_delivery_boys = result.length;
					       						response1.info = [];
					       						var i = 0;
					       						for(i = 0;i < result.length;i++)
					       						{
					       							var deliveryBoyInfo = {};
					       							deliveryBoyInfo.uid = result[i].uid;
					       							deliveryBoyInfo.name = result[i].name;
					       							deliveryBoyInfo.phone = result[i].phone;
					       							deliveryBoyInfo.gcm_id = result[i].gcm_id;
					       							response1.info.push(deliveryBoyInfo);
					       						}
					       						socket.emit("deliveryBoyInfo", JSON.stringify(response1));
					       						console.log("Sent : " + JSON.stringify(response1));
					       						connection.release();
					       						setInterval(function(){ sendLocationData(socket); }, 4000);
					       					});
					   });
}

var response2 = {};

function sendLocationData(socket)
{
	pool.getConnection(function(error1,connection)
					   {	
					       if(error1)
					   	   {
					   	   	   console.log("Error in connecting to database.");
					   	   	   return;
					   	   }
					       query1 = "SELECT * FROM (SELECT delivery_boy_id, latitude, longitude, recorded_at FROM location_info ORDER BY recorded_at DESC) AS temp_table GROUP BY delivery_boy_id;";
					       response2 = {};
					       response2.statuses = [];
					       response2.locationInfo = [];
					       connection.query(query1, function(error2, result1)
					       					{
					       						if(error2)
					       						{
					       							console.log("Cannot execute query : " + query1);
					       							return;
					       						}
					       						var i = 0;
					       						for(i = 0;i < result1.length;i++)
					       						{
					       							var location = {};
					       							location.delivery_boy_id = result1[i].delivery_boy_id;
					       							location.latitude = result1[i].latitude;
					       							location.longitude = result1[i].longitude;
					       							location.recorded_at = result1[i].recorded_at;
					       							response2.locationInfo.push(location);
					       						}
					       						var query2 = "SELECT uid, current_status FROM delivery_boy_info;";
					       						connection.query(query2, function(error3, result2)
										       							 {
										       							     if(error3)
										       							     {
										       							     	console.log("Cannot execute query : " + query2);
					       														return;
										       							     }
										       							     var j = 0;
										       							     for(j = 0;j < result2.length;j++)
										       							     {
										       							     	var status = {};
										       							     	status.delivery_boy_id = result2[j].uid;
										       							     	status.current_status = result2[j].current_status;
										       							     	response2.statuses.push(status);
										       							     }
										       							     socket.emit("locationInfo", JSON.stringify(response2));
										       							     console.log("Sent : " + JSON.stringify(response2));
										       							     connection.release();
										       							 });
					   						});
						});
}		

function receiveLocation(request, response, latitude, longitude, id)
{
	pool.getConnection(function(error,connection)
					   {				   
		                	query = "INSERT INTO location_info (`delivery_boy_id`, `latitude`, `longitude`) VALUES ('" + id + "', '" + latitude + "', '" + longitude + "');";
	                	 	connection.query(query, function(error, result)
							                	 	 {
							                	 	 	connection.release();
							                	 	 	response.send("Insert successful");
							                	 	 });
	                	});
}

app.post("/deliveryboy",function(request, response)
					    {
					    	var latitude = request.body.latitude;
					    	var longitude = request.body.longitude;
					    	var id = request.body.id;
			    		    receiveLocation(request, response, latitude, longitude,id);
					    });


io.on('connection', function(socket) 
					{  
						console.log("manager connected");
						sendDeliveryBoyInfo(socket);
					    socket.on('disconnect', function()
					    {
						    console.log('manager disconnected');
						});
					
					});

var PORT = 9001;

server.listen(PORT,function(){
console.log("Server is running at port " + PORT);
});
