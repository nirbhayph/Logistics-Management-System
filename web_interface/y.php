<!DOCTYPE HTML>
<?php
session_start();
include "config.php";
$query = "select * from delivery_boy_info where current_status='A'";
$result=mysql_query($query);
/********** FOR PLOTTING LOCATION OF CLIENT(S) *************/
$query2="select * from client_location";
$result_loc=mysql_query($query2);

/***********************************************************/
if(isset($_SESSION['username'])){
$x=$_SESSION['username'];
}
else 
header("Location: http://52.38.52.58/login.html");





?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>


<style>
.cool_but{
    font-family:arial;
    color:red;
    text-align:center;
    background: green;

}
</style>


    

<html>
    
<head>
    
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="LMS">
        <meta name="author" content="Nirbhay Pherwani and R Hari">
        <!-- App Favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- App title -->
        <title>Dashboard | Manager</title>

        <!-- jvectormap -->
        <link href="assets/plugins/jvectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />

    <!-- Morphbutton -->

    <!-- Demo -->
        <!-- App CSS -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />
        

        

        <script src="assets/js/modernizr.min.js"></script>
<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'analytics.js', 'ga');
    ga('create', 'UA-74137680-1', 'auto');
    ga('send', 'pageview');
</script>
    </head>


    <body class="fixed-left">

         

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Top Bar Start -->
            <div class="topbar">

                <!-- LOGO -->
                <div class="topbar-left">
                    <a href="index.html" class="logo"><span>Manager<span>to</span></span><i class="zmdi zmdi-layers"></i></a>                </div>

                <!-- Button mobile view to collapse sidebar menu -->
                <div class="navbar navbar-default" role="navigation">
                    <div class="container">

                        <!-- Page title -->
                        <ul class="nav navbar-nav navbar-left">
                            <li>
                                <button class="button-menu-mobile open-left">
                                    
                                    <i class="zmdi zmdi-menu"></i>                                </button>
                            </li>
                            <li>
                                <h4 class="page-title">Maps</h4>
                            </li>
                        </ul>

                        <!-- Right(Notification and Searchbox -->
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <!-- Notification -->
                                <div class="notification-box">
                                    <ul class="list-inline m-b-0">
                                        <li>
                                            
                                            <a href="javascript:void(0);" class="right-bar-toggle">
                                                <i class="zmdi zmdi-notifications-none"></i>                                            </a>
                                            <div class="noti-dot">
                                                
                                                <span class="dot pulse"></span>
                                                
                                                <span class="pulse"></span>    

                                                                                        </div>
                                        </li>
                                    </ul>
                                </div>
                                <!-- End Notification bar -->
                                

                            </li>
                            <li class="hidden-xs">
                                <form id="my_form" role="search" class="app-search" method="get" action="get_location_of_new_client.php">
                                    <input type="text" placeholder="Add a Client" class="form-control" name="place" required>
                                    
                                    <a href="javascript:{}" onclick="document.getElementById('my_form').submit();" name="sub_place_insert"><i class="fa fa-plus" aria-hidden="true" style="color: #8cff00;"></i>
</a>
                                </form>
                            </li>
                        </ul>
                    </div><!-- end container -->
                </div><!-- end navbar -->
            </div>
            <!-- Top Bar End -->


            <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">
                <div class="sidebar-inner slimscrollleft">

                    <!-- User -->
                    <div class="user-box">
                        <div class="user-img">
                            <img src="manager.png" alt="user-img" title="<?php echo $x; ?>" class="img-circle img-thumbnail img-responsive">
                            <div class="user-status offline"><i class="zmdi zmdi-dot-circle"></i></div>
                        </div>
                        <h5><a href="#"><?php echo $x; ?></a> </h5>
                        <ul class="list-inline">
                            <li>
                                <a href="#" >
                                    <i class="zmdi zmdi-settings"></i>                                </a>                            </li>

                            <li>
                                <a href="logout.php" class="text-custom">
                                    <i class="zmdi zmdi-power"></i>                                
                                </a>                            
                            </li>
                        </ul>
                    </div>
                    <!-- End User -->

                    <!--- Sidebar -->
                    <div id="sidebar-menu">
                        <ul>
                            <li class="text-muted menu-title">Navigation</li>

                            <li>
                                <a href="y.php" class="waves-effect"><i class="zmdi zmdi-view-dashboard"></i> <span> Dashboard </span> </a>                            </li>

                            <li>
                                <a href="rec.php" class="waves-effect"><i class="zmdi zmdi-format-underlined"></i> <span> Generated Reciepts </span> </a>                            
                                </li>

                            <li>
                                <a href="clients.php" class="waves-effect"><i class="zmdi zmdi-collection-text"></i> <span> Clientele </span> </a>                            
                                </li>
            </div>
                    <!-- Sidebar -->
                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- Left Sidebar End -->


          <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">


                     <!--   <div class="row">
                            <div class="col-sm-12">
                                <h4 class="page-header m-b-20 header-title">Vector Maps</h4>
                            </div>
                        </div>
                        <!-- end row -->


               


                        <div class="row">


                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="page-header m-t-0 m-b-20 header-title"> Current Scenario</h4>
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="row">
                        <div class="col-lg-12" >
                                <div class="card-box" >
                                    <div class="dropdown pull-right">
                                        <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false">
                                            <i class="zmdi zmdi-more-vert"></i>                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li> <i class="fa fa-map-marker fa-lg" aria-hidden="true" style="color:#FF9725;margin-left:10%"><a href="#" style = "color:white;font-family:arial;"><b style="font-size:15px;">&nbsp&nbsp&nbsp Manager</b></a></i> </li>
                                            <li class="divider"></li>
                                            <li><i class="fa fa-map-marker fa-lg" aria-hidden="true" style="color:#6991FD;margin-left:10%"> <a href="#" style = "color:white;font-family:arial;"><b style="font-size:15px;">&nbsp&nbsp&nbspClient(s)</b></a></i> </li>
                                            <li class="divider"></li>
                                            <li><i class="fa fa-map-marker fa-lg" aria-hidden="true" style="color:#F7584C;margin-left:10%"><a href="#" style = "color:white;font-family:arial;"><b style="font-size:15px;">&nbsp&nbsp&nbsp Available DB(s)<b></a></i> </li>
                                            <li class="divider"></li>
                                            <li><i class="fa fa-map-marker fa-lg" aria-hidden="true" style="color:#00E64D;margin-left:10%"> <a href="#" style = "color:white;font-family:arial;"><b style="font-size:15px;">&nbsp&nbsp&nbspBusy DB(s)<b></a></i> </li>
                                            <li class="divider"></li>
                                            
                                            
                                        </ul>
                                    </div>

                                    <h4 class="header-title m-t-0 m-b-30" style="text-align:center"><u>SYSTEM MANAGER</u></h4>
                                    <h4 class="header-title m-t-0 m-b-30" id="fillclient0" style="text-align:center">CLIENT SELECTED <i class="fa fa-arrow-right" aria-hidden="true"></i><b id="fillclient1"> None</b></h4>
                                 <!--   <h4 class="header-title m-t-0 m-b-30" id="fillclient1" style="text-align:center">None</h4> -->
                                    

                                    <div id="gmaps-markers" class="gmaps" ></div>
                                </div>
                            </div><!-- end col -->
<!--
                            <div class="col-lg-6">
                                <div class="card-box">
                                    <div class="dropdown pull-right">
                                        <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false">
                                            <i class="zmdi zmdi-more-vert"></i>                                        </a>
                                        <ul class="dropdown-menu" 
role="menu">
                                            <li><a href="#">Action</a></li>
                                            <li><a href="#">Another action</a></li>
                                            <li><a href="#">Something else here</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a></li>
                                        </ul>
                                    </div>

                                    <h4 class="header-title m-t-0 m-b-30">Overlays</h4>

                                    <div id="gmaps-overlay" class="gmaps"></div>
                            </div>
                            </div><!-- end col -->
                        </div>
                        <!-- end row -->


<!--                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card-box">
                                    <div class="dropdown pull-right">
                                        <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false">
                                            <i class="zmdi zmdi-more-vert"></i>                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Action</a></li>
                                            <li><a href="#">Another action</a></li>
                                            <li><a href="#">Something else here</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a></li>
                                        </ul>
                                    </div>

                                    <h4 class="header-title m-t-0 m-b-30">Street View Panoramas</h4>

                                    <div id="panorama" class="gmaps-panaroma"></div>
                                </div>
                            </div>-->
                            <!-- end col -->

<!--                            <div class="col-lg-6">
                                <div class="card-box">
                                    <div class="dropdown pull-right">
                                        <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false">
                                            <i class="zmdi zmdi-more-vert"></i>                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Action</a></li>
                                            <li><a href="#">Another action</a></li>
                                            <li><a href="#">Something else here</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a></li>
                                        </ul>
                                    </div>

                                    <h4 class="header-title m-t-0 m-b-30">Map Types</h4>

                                    <div id="gmaps-types" class="gmaps"></div>
                                </div>
                            </div><!-- end col -->
<!--                        </div>
                        <!-- end row -->
                    </div> <!-- container -->
                </div> <!-- content -->

                <footer class="footer">
                    2016 The LMS Team.    


                               </footer>
            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


            <!-- Right Sidebar -->
            <div class="side-bar right-bar">
                <a href="javascript:void(0);" class="right-bar-toggle">
                    <i class="zmdi zmdi-close-circle-o"></i>                </a>
                <h4 class="">Active Delivery Boys</h4>
                <div class="notification-list nicescroll">
                    <ul class="list-group list-no-border user-list">
                        <?php
                        while ($row = mysql_fetch_assoc($result)) {
                        
                        ?>
                        <li class="list-group-item">
                            <a href="#" class="user-list-item">
                                <div class="avatar">
                                    <img src="delivery.jpg" alt="">                                </div>
                                <div class="user-desc">
                                    <span class="name"><?php echo $row['name']; ?></span>
                                    <span class="desc"><?php echo $row['email']; ?></span>
                                    <span class="time"><?php echo $row['phone']; ?></span>                                </div>
                            </a> 
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <!-- /Right-bar -->
        </div>
        <!-- END wrapper -->


<script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="https://cdn.socket.io/socket.io-1.4.5.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>

        <!-- App js -->
        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>
        <script src="assets/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
        <script src="assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
        <script src="assets/plugins/jvectormap/gdp-data.js"></script>
        <script src="assets/plugins/jvectormap/jquery-jvectormap-us-aea-en.js"></script>
        <script src="assets/plugins/jvectormap/jquery-jvectormap-uk-mill-en.js"></script>
        <script src="assets/plugins/jvectormap/jquery-jvectormap-au-mill.js"></script>
        <script src="assets/plugins/jvectormap/jquery-jvectormap-ca-lcc.js"></script>
        <script src="assets/pages/jvectormap.init.js"></script>




          
    

    

 <script>
        var serverResponse = "";
        var statuses = [];
        var locationInfo = [];
        var deliveryBoyInfo = [];
        var map;
        var markers = [];
        var requestedDeliveryBoys = [];

       
        
       function initMap() 
        {
             
            

            map = new google.maps.Map(document.getElementById('gmaps-markers'), {
              center: {lat: 19.0760, lng: 72.8777},
              zoom: 11,
              disableDefaultUI: false,
              mapTypeControl: false,
              streetViewControl: false
            });
            
            
            <?php 
        while ($row = mysql_fetch_assoc($result_loc)){ 
                $address = $row['address'];
                $latit   = $row['lat'];
                $longit  = $row['lng']; 
                $id_cl   = $row['id_']; ?>
            
            var myLatLng = {lat: <?php echo $latit; ?> , lng: <?php echo $longit; ?>};

            var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: '<?php echo $id_cl ?>',
            myid: '<?php echo $id_cl; ?>'
            
            
            });
            marker.setIcon('http://maps.google.com/mapfiles/ms/icons/blue-dot.png');

                   
              google.maps.event.addListener(marker, 'click', function() {
    // do something. You may use jQuery here.
                        var xyz = '<?php echo $id_cl; ?>';
            
            
                    $.ajax({
                        url : 'trial.php',
                        type : 'POST',
                        data : ({postid: xyz}),
                        dataType : 'json',
                        success : function (result) {

          
                                $('#fillclient1').html("| Id: "+result['id_nir']+" | Address: "+result['addr_nir']+" | Lat: "+result['lat_nir']+" | Lng: "+result['lng_nir']+" |");
                                
                                window.globalVarx = result['addr_nir'];
                                window.latitudex = result['lat_nir'];
                                window.longitudey = result['lng_nir'];
                                
           
          
                        },
                        error : function () {
                        alert("error");
                    }
                    })
                    
            });  

            <?php } ?>

        }



        
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
        document.write("Geolocation is not supported by this browser.");
        }
    

    function showPosition(position) {
    
    var myLatLng = {lat: position.coords.latitude , lng: position.coords.longitude};

            var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: 'Manager'
            
            
            }); 
             marker.setIcon('http://maps.google.com/mapfiles/ms/icons/orange-dot.png');

    }

           
   </script>
 <?php //} ?>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDz75mZCJ0G5PTEPim5Y_tmgRUjJSPh3ww&callback=initMap"></script>

    <script>


        var socket = io.connect("http://ec2-52-27-55-158.us-west-2.compute.amazonaws.com:9001");
        //var socket = io.connect("http://localhost:9001/");
        socket.on('deliveryBoyInfo', function(data)
                                     {                              
                                         serverResponse = data;
                                         var json = JSON.parse(serverResponse);
                                         deliveryBoyInfo = json.info;
                                     });
        function findStatus(deliveryBoyId)
        {
            var i = 0;
            for(i = 0;i < statuses.length;i++)
                if(statuses[i].delivery_boy_id == deliveryBoyId)
                    return statuses[i].current_status;
            return "Error !";
        }

        function findName(deliveryBoyId)
        {
            var i = 0;
            for(i = 0;i < deliveryBoyInfo.length;i++)
                if(deliveryBoyInfo[i].uid == deliveryBoyId)
                    return deliveryBoyInfo[i].name;
            return "Error !";
        }



        <?php 
        //     if(isset($_POST['client']){
        //     $id_client=$_POST['client_id'];
        //     $query_client="select * from client_location where id_=".$id_client;
        //     $res_client=mysql_query($query_client);
        //     while($row2=mysql_fetch_assoc($res_client)){
        //         $addr_client = $row['address'];
        //         $lat_client   = $row['lat'];
        //         $long_client = $row['lng'];
        //         $id_cl=$row['id_'];
        //     }
        // }



        ?>





        function sendRequest(deliveryBoyId)
        {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() 
                                         {
                                             if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                                                 console.log("response : " + xmlhttp.responseText);
                                         };
            xmlhttp.open("POST", "http://phpscripts.mybluemix.net/deliveryapp/send_gcm_request.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            //alert(var zzr = document.getElementById('fillclient2').innerHTML);
            xmlhttp.send("id=" + deliveryBoyId + "&managerLat="+window.latitudex+"&managerLong="+window.longitudey+"&managerAdrs="+window.globalVarx);
            requestedDeliveryBoys.push(deliveryBoyId);

        }

        socket.on("locationInfo", function(data)
                                  {
                                      serverResponse = data;
                                      var json = JSON.parse(serverResponse);
                                      locationInfo = json.locationInfo;
                                      statuses = json.statuses;
                                      console.log("Markers updated.");
                                      var i = 0;
                                      for(i = 0;i < markers.length;i++)
                                          markers[i].setMap(null);
                                      markers = [];
                                      var k = 0;
                                      for(k = 0; k < locationInfo.length; k++)
                                      {
                                          var deliveryBoyId = locationInfo[k].delivery_boy_id;
                                          var latitude = parseFloat(locationInfo[k].latitude);
                                          var longitude = parseFloat(locationInfo[k].longitude);
                                          var recordedAt = locationInfo[k].recorded_at;
                                          var current_status = findStatus(deliveryBoyId);
                                          var name = findName(deliveryBoyId);
                                          var myLatLng = {lat: latitude, lng: longitude};
                                          var marker = new google.maps.Marker({
                                                                                  position: myLatLng,
                                                                                  title:"Name : " + name + "\nLast seen at : " + recordedAt
                                                                              });
                                          if(current_status=="B")
                                            marker.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png');
                                          if((requestedDeliveryBoys.indexOf(deliveryBoyId) != -1) && (current_status == "B"))
                                              marker.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png');

                                          var contentString = " <button class=\"btn btn-success btn-rounded w-md waves-effect waves-light m-b-5\" onclick = \"sendRequest(\'" + deliveryBoyId + "\')\">Request</button> <br> <a href=\"orc.php?did=" + deliveryBoyId + "\" target=\"_blank\"><button class=\"btn btn-primary btn-rounded w-md waves-effect waves-light m-b-5\">Reciept</button></a> ";
                                          var infowindow = new google.maps.InfoWindow({
                                                                                          content : contentString,
                                                                                          position : myLatLng
                                                                                      });
                                          google.maps.event.addListener(marker,'click', (function(marker, deliveryBoyId, infowindow)
                                          { 
                                              return function() 
                                              {
                                                  infowindow.open(map);
                                              };
                                          })(marker, deliveryBoyId, infowindow));               
                                          markers.push(marker);
                                          marker.setMap(map);
                                      }

                                  });
    </script>

               
            
        
      


    </body>

    
</html>




