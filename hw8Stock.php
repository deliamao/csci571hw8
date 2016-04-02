<?php 
header("Access-Control-Allow-Origin: *");
        
      if(isset($_GET["input"])) {
        $jsonUrl = "http://dev.markitondemand.com/MODApis/Api/v2/Lookup/json?input=".$_GET["input"];
    	$mjson = file_get_contents($jsonUrl);
    	echo $mjson;
         //echo $_GET["userInput"]
        }
  
  

?>

