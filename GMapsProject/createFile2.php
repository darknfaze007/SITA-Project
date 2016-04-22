<?php
		$list = $_POST['coords2'];
		$test = $_POST['y'];
		$header = array("Latitude","Longitude","Description","Title", "Connections");
		$fp = fopen("GeocodedCustomers/".$test, "w");
		fputcsv ($fp, $header, ",");
		foreach($list as $row){
			fputcsv($fp, $row, ",");
		}
		fclose($fp);
?>