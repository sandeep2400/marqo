<?php
	function getall(){
		try{$conn = new PDO("mysql:host=localhost; dbname=marqodb", "sandeep2400", "kepler123"); 
    		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); } 
		catch(PDOException $e){ 
     		echo $e->getMessage(); } 
		$sql = "Select item_id, metadata from item order by item_id asc;";
		$stmt = $conn->prepare($sql); 
		$stmt->execute();     
		$result = $stmt->fetch(PDO::FETCH_ASSOC); 

		$listItem = new ListObj();

		$val = array();
		 if ($result) 
	        { while ($result){ 
	        	$listItem = new ListObj();
	        	$listItem->item_id = $result['item_id'];
	        	$listItem->metadata = $result['metadata'];
	        	array_push($val, $listItem);
	//        	echo $val;
	      	    $result = $stmt->fetch(PDO::FETCH_ASSOC); 
	          } 
	        } 
	      else{
	        	$listItem = new ListObj();
	        	$listItem->item_id = 'no data';
	        	$listItem->metadata = 'no data';
	        	array_push($val, $listItem);
	      }  

		//var_dump($val);
		$val = json_encode($val);
		return($val);      		
	}
?>