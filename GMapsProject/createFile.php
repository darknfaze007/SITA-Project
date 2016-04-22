<?php
		$list = $_POST['coords2'];
		$test = $_POST['y'];
		$header = array("Latitude","Longitude","Airline","DepartureTime");
		$fp = fopen("GeocodedFlightPlans/".$test, "w");
		fputcsv ($fp, $header, ",");
		foreach($list as $row){
			fputcsv($fp, $row, ",");
		}
		fclose($fp);
?>