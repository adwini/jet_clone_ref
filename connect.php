<?php 
	$connection = new mysqli('localhost', 'root','','jet_clone');
	
	if (!$connection){
		die (mysqli_error($mysqli));
		
	}
		
?>