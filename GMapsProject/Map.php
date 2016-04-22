<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta name="robots" content="noindex, nofollow">
  <meta name="googlebot" content="noindex, nofollow">
      <script type="text/javascript" src=" https://maps.googleapis.com/maps/api/js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>
		<script src="moment.js"></script>

  <style type="text/css">
     html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map-canvas {
        height: 95%;
      }
}
  </style>

  <title></title>
  
</head>

<body>

<script>

window.onload = function() {
	var map;
	var infowindow = new google.maps.InfoWindow({
    content: ''
	});
	
	function initMap() {
		map = new google.maps.Map(document.getElementById('map-canvas'), {
		center: {lat: 0, lng: 0},
		zoom: 3
	});
	var geocoder = new google.maps.Geocoder();
	
	document.getElementById('submit').addEventListener('click', function() {
		geocodeAddress(geocoder, map);
	});
	document.getElementById('submit2').addEventListener('click', function() {
		geocodeAddress2(geocoder, map);
	});
	}
	
function geocodeAddress(geocoder, resultsMap) {
	var coords = [];
	var coords2 = [];
	var y= document.getElementById('s2').value+".csv";

	d3.csv("FlightPlans/"+y, function(data) {
	data.forEach(function(d) {
		marker = [d.Location, d.Airline, d.DepartureTime];
		console.log(d.DepartureTime);
		coords.push(marker);
		});
	});
	
	var completed = false;
	
	setTimeout(function geocode() {
		for(var i=0; i<coords.length; i++) {
			//alert(coords.length);
			var currAddress = coords[i][0];
			$.getJSON('http://maps.googleapis.com/maps/api/geocode/json?address='+currAddress+'&sensor=false', null, function (data) {
				coords2.push([data.results[0].geometry.location.lat, data.results[0].geometry.location.lng]);
				if(coords2.length == coords.length) {
					completed = true;
				}
			});
		}
	}, 1200);
	
	setTimeout(function getResult() {
		if (completed) {
			for(var i=0; i<coords2.length; i++) {
				coords2[i][2] = coords[i][1];
				coords2[i][3] = coords[i][2];
				console.log(coords2[i][3]);
			}
			$.post('createFile.php', {coords2:coords2, y:y});
			location.reload();
      } else {
           setTimeout(getResult, 250);
      }

	}, 250);
}

function geocodeAddress2(geocoder, resultsMap) {
	var coords = [];
	var coords2 = [];
	var y= document.getElementById('s0').value+".csv";

	d3.csv("Customers/"+y, function(data) {
	data.forEach(function(d) {
		marker = [d.Location, d.Description, d.Title, d.Connections];
		console.log(d.Description);
		coords.push(marker);
		});
	});
	
	var completed = false;
	
	setTimeout(function geocode() {
		for(var i=0; i<coords.length; i++) {
			//alert(coords.length);
			var currAddress = coords[i][0];
			alert(currAddress);
			$.getJSON('http://maps.googleapis.com/maps/api/geocode/json?address='+currAddress+'&sensor=false', null, function (data) {
				coords2.push([data.results[0].geometry.location.lat, data.results[0].geometry.location.lng]);
				if(coords2.length == coords.length) {
					completed = true;
				}
			});
		}
	}, 1200);
	
	setTimeout(function getResult() {
		if (completed) {
			for(var i=0; i<coords2.length; i++) {
				coords2[i][2] = coords[i][1];
				coords2[i][3] = coords[i][2];
				coords2[i][4] = coords[i][3];
				console.log(coords2[i][3]);
			}
			$.post('createFile2.php', {coords2:coords2, y:y});
			location.reload();
      } else {
           setTimeout(getResult, 250);
      }

	}, 250);
}

initMap();
}
</script>

<div id="map-canvas"></div>
<script>
//CREDIT TO @ReneKorss
function runCsv() {	

var gmarkers1 = [];
var markers1 = [];
var infowindow = new google.maps.InfoWindow({
    content: ''
});

var p= document.getElementById('s1').value+".csv";

d3.csv("GeocodedCustomers/"+p, function(data) {
var i = 4;
var x = 0;
  data.forEach(function(d) {
    d.Latitude = +d.Latitude;
    d.Longitude = +d.Longitude;
	marker = [""+i, ""+data[x].Title+"<br/>"+data[x].Connections, data[x].Latitude, data[x].Longitude, data[x].Description];
	markers1.push(marker);
	addMarker(marker);
	i++;
	x++;
  });
});

/**
 * Function to init map
 */

function initialize() {
    var center = new google.maps.LatLng(0, 0);
    var mapOptions = {
        zoom: 3,
        center: center,
        mapTypeId: google.maps.MapTypeId.TERRAIN
    };

    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
    for (i = 0; i < markers1.length; i++) {
        addMarker(markers1[i]);
    }
	
}

/**
 * Function to add marker to map
 */
 
function addMarker(marker) {
    var category = marker[4];
    var title = marker[1];
    var pos = new google.maps.LatLng(marker[2], marker[3]);
    var content = marker[1];
	
	
	if(category == "IPVPN") {
		marker1 = new google.maps.Marker({
			icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png',
			title: title,
			position: pos,
			category: category,
			map: map
		});
	}
	else if(category == "APH") {
		marker1 = new google.maps.Marker({
			icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png',
			title: title,
			position: pos,
			category: category,
			map: map
		});
	}
	else if(category == "Check-in") {
		marker1 = new google.maps.Marker({
			icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png',
			title: title,
			position: pos,
			category: category,
			map: map
		});
	}
    gmarkers1.push(marker1);

    // Marker click listener
    google.maps.event.addListener(marker1, 'click', (function (marker1, content) {
        return function () {
            console.log('Gmarker 1 gets pushed');
            infowindow.setContent(content);
            infowindow.open(map, marker1);
            //map.panTo(this.getPosition());
            //map.setZoom(15);
        }
    })(marker1, content));
}
 
filterMarkers = function (category) {
    for (i = 0; i < markers1.length; i++) {
        marker = gmarkers1[i];
        // If is same category or category not picked
        if (marker.category == category || category.length === 0) {
            marker.setVisible(true);
        }
        // Categories don't match 
        else {
            marker.setVisible(false);
        }
    }
}

// Init map
initialize();
}

function runCsv2() {	

var gmarkers1 = [];
var markers1 = [];
var times = [];
var infowindow = new google.maps.InfoWindow({
    content: ''
});

var p= document.getElementById('s3').value+".csv";

d3.csv("GeocodedFlightPlans/"+p, function(data) {
var i = 4;
var x = 0;
  data.forEach(function(d) {
    d.Latitude = +d.Latitude;
    d.Longitude = +d.Longitude;
	marker = [data[x].DepartureTime, ""+data[x].Airline+"<br/>"+data[x].DepartureTime, data[x].Latitude, data[x].Longitude];
	markers1.push(marker);
	addMarker(marker);
	i++;
	x++;
  });
});

/**
 * Function to add marker to map
 */
 
function addMarker(marker) {
    var title = marker[0];
	
    var pos = new google.maps.LatLng(marker[2], marker[3]);
    var content = marker[1];

    marker1 = new google.maps.Marker({
        title: title,
        position: pos,
        icon: 'airport.png',
        map: map
    });

    gmarkers1.push(marker1);

    // Marker click listener
    google.maps.event.addListener(marker1, 'click', (function (marker1, content) {
        return function () {
            console.log('Gmarker 1 gets pushed');
            infowindow.setContent(content);
            infowindow.open(map, marker1);
            //map.panTo(this.getPosition());
            //map.setZoom(15);
        }
    })(marker1, content));
}

//NEEDS REVISION
filterMarkers2 = function (category) {
    for (i = 0; i < markers1.length; i++) {
        marker = gmarkers1[i];
		
		var time = moment(marker.title, "MM-DD-YYYY HH:mm");
		var whichSelected = parseInt(category.substring(0,1));
		var timeFuture = moment().add(whichSelected, 'hour');
		
		console.log(timeFuture.diff(time, "hours"));
		
        // If is same category or category not picked
        if (parseInt(timeFuture.diff(time, "hours")) <= whichSelected && parseInt(timeFuture.diff(time, "hours")) >= 0 || category.length === 0) {
            marker.setVisible(true);
        }
        // Categories don't match 
        else {
            marker.setVisible(false);
        }
    }
}
}

function runCsv3() {	

var gmarkers1 = [];
var markers1 = [];
var infowindow = new google.maps.InfoWindow({
    content: ''
});
var p= "Connections.csv";


d3.csv(p, function(data) {
var i = 4;
var x = 0;
  data.forEach(function(d) {
    d.Latitude = +d.Latitude;
    d.Longitude = +d.Longitude;
	marker = [""+i, ""+data[x].Title+"<br/>"+data[x].Connections, data[x].Latitude, data[x].Longitude, data[x].Description];
	markers1.push(marker);
	addMarker(marker);
	i++;
	x++;
  });
});

/**
 * Function to init map
 */

function initialize() {
    var center = new google.maps.LatLng(0, 0);
    var mapOptions = {
        zoom: 3,
        center: center,
        mapTypeId: google.maps.MapTypeId.TERRAIN
    };

    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
    for (i = 0; i < markers1.length; i++) {
        addMarker(markers1[i], 0);
    }
	
}

/**
 * Function to add marker to map
 */
	var j = 0;
function addMarker(marker, value) {
    var category = marker[4];
    var title = marker[1];
    var pos = new google.maps.LatLng(marker[2], marker[3]);
    var content = marker[1];
	
	
	if(value == 1) {
		  marker = new google.maps.Marker({
			icon: 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png',
			map: map,
			title: title,
			animation: google.maps.Animation.DROP,
			position: pos
		  });
		    marker.setAnimation(google.maps.Animation.BOUNCE);
	}
	
	if(j%6 == 0) {
		marker1 = new google.maps.Marker({
			icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png',
			title: title,
			position: pos,
			category: category,
			map: map
		});
		j++;
	}
	else {
		marker1 = new google.maps.Marker({
			icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png',
			title: title,
			position: pos,
			category: category,
			map: map
		});
		j++;
	}
	console.log(j);
    gmarkers1.push(marker1);

    // Marker click listener
    google.maps.event.addListener(marker1, 'click', (function (marker1, content) {
        return function () {
            console.log('Gmarker 1 gets pushed');
            infowindow.setContent(content);
            infowindow.open(map, marker1);
            //map.panTo(this.getPosition());
            //map.setZoom(15);
        }
    })(marker1, content));
}
 
filterMarkers = function (category) {
    for (i = 0; i < markers1.length; i++) {
        marker = gmarkers1[i];
        // If is same category or category not picked
        if (marker.category == category || category.length === 0) {
            marker.setVisible(true);
        }
        // Categories don't match 
        else {
            marker.setVisible(false);
        }
    }
}



// Init map
initialize();

setTimeout(function(){
	addMarker(markers1[5], 1);
	window.alert("Warning: connection is down.");
	},3000);
}

</script>

<select id="s0">
      <option value="" selected="selected">Select a list of customers to be geocoded</option>
  <?php 
       foreach(glob("Customers/*.csv") as $filename){
       $filename = basename($filename, ".csv");
       echo "<option value='" . $filename . "'>".$filename."</option>";
    }
?>
</select>
 
<button type="button" id="submit2">Submit</button>

<select id="s1">
      <option value="" selected="selected">Select a list of customers</option>
  <?php 
       foreach(glob("GeocodedCustomers/*.csv") as $filename){
       $filename = basename($filename, ".csv");
       echo "<option value='" . $filename . "'>".$filename."</option>";
    }
?>
</select>
 
<button type="button" onclick="runCsv();">Submit</button>
<select id="type" onchange="filterMarkers(this.value);">
    <option value="">Select category of customer</option>
    <option value="IPVPN">IPVPN</option>
    <option value="APH">APH</option>
    <option value="Check-in">Check-in</option> 
</select>

      <select id="s2">
      <option value="" selected="selected">Select a file to be geocoded</option>
		<?php 
       foreach(glob("FlightPlans/*.csv") as $filename){
       $filename = basename($filename, ".csv");
       echo "<option value='" . $filename . "'>".$filename."</option>";
		}
		?>
		</select>
		
		
      <input id="submit" type="button" value="Geocode">
		
		<select id="s3">
      <option value="" selected="selected">Select a geocoded Flight Plan to display</option>
		<?php 
       foreach(glob("GeocodedFlightPlans/*.csv") as $filename){
       $filename = basename($filename, ".csv");
       echo "<option value='" . $filename . "'>".$filename."</option>";
		}
		?>
		</select>
	
		 <input id="submit2" type="button" onclick="runCsv2();" value="Submit FP">
		 
		 <select id="s4" onchange="filterMarkers2(this.value);">
      <option value="" selected="selected">Filter by time</option>
	  <option> 1 hour </option>
	  <option> 2 hours </option>
	  <option> 3 hours </option>
	  <option> 4 hours </option>
	  </select>
	  
	  <input id="submit2" type="button" onclick="runCsv3();" value="Display Risk Analysis">
</body>
</html>