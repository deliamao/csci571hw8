<?php 
header("Access-Control-Allow-Origin: *");
        
      if(isset($_GET["input"])) {
        $jsonUrl = "http://dev.markitondemand.com/MODApis/Api/v2/Lookup/json?input=".$_GET["input"];
    	$mjson = file_get_contents($jsonUrl);
    	echo $mjson;
         //echo $_GET["userInput"]
        }
  
      if(isset($_GET["symbolVal"])) {
        //use api to get the Json file
        $quoteURL = "http://dev.markitondemand.com/MODApis/Api/v2/Quote/json?symbol=".$_GET["symbolVal"];
        //$quoteURL = "http://dev.markitondemand.com/MODApis/Api/v2/Quote/json?symbol=AAPL";
        $mjson = file_get_contents($quoteURL);
        echo $mjson;
	  }
        
      if(isset($_GET["chartVal"])) {
        //use api to get the Json file
        $chartURL = "http://dev.markitondemand.com/MODApis/Api/v2/InteractiveChart/json?parameters="."{\"Normalized\":false,\"NumberOfDays\":1095,\"DataPeriod\":\"Day\",\"Elements\":[{\"Symbol\":\"".$_GET["chartVal"]."\",\"Type\":\"price\",\"Params\":[\"ohlc\"]}]}";
          
        //$chartURL = "http://dev.markitondemand.com/MODApis/Api/v2/InteractiveChart/json?parameters="."{\"Normalized\":false,\"NumberOfDays\":1095,\"DataPeriod\":\"Day\",\"Elements\":[{\"Symbol\":\"".$_GET["symbol"]."\",\"Type\":\"price\",\"Params\":[\"ohlc\"]}]}";
        
        $mjson = file_get_contents($chartURL);
        echo $mjson;
	  }

       if (isset($_GET['bingVal'])) {
        // this is use the API document to get the jason by php
        // stream_context_create create a stream to include all the option parameter then will be 
        // used in file_get_contents
        $accountKey = "HhVd7fTsisZ+g0BR90ix/xD+Xb/gcFj+Duw5aRimTqc";
        $WebSearchURL =  "https://api.datamarket.azure.com/Bing/Search/v1/News?Query=%27". $GET["bingVal"] . "%27&\$format=json";
       // $WebSearchURL =  "https://api.datamarket.azure.com/Bing/Search/v1/News?     Query=%27AAPL%27&\$format=json";
       $context = stream_context_create(array(
            'http' => array(
                'request_fulluri' => true,
                'header'  => "Authorization: Basic " . base64_encode($accountKey . ":" . $accountKey)
            )
        ));
        
        $mjson = file_get_contents($WebSearchURL, 0, $context);
        
        echo $mjson;
   }

?>



