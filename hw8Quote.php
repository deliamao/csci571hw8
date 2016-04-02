<?php
header("Access-Control-Allow-Origin: *");

	if(isset($_GET["input"])) {
        //use api to get the Json file
        $quoteURL = "http://dev.markitondemand.com/MODApis/Api/v2/Quote/json?symbol=".$_GET["input"];
        //$quoteURL = "http://dev.markitondemand.com/MODApis/Api/v2/Quote/json?symbol=AAPL";
        $mjson = file_get_contents($quoteURL);
        echo $mjson;
	//}
	
?>
