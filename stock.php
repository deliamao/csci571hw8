
<!doctype html>
<html lang="en" class="mainPage">
<head>
<style> 
    p{
        margin: 5px;
        font-family: serif;
        font-style:italic;
        text-align: center;
        font-size: 30px;
        font-weight:bold;
    }
    #main{
            padding: 0px;
            width: 400px;
			border: 2px solid gray;
			line-height: 20pt;
            margin-left: 400px;
            background-color:gainsboro;
    }
    
    table {
    border-collapse: collapse;
    border: 2px solid black;
    }

     table, th, td {
    border: 1px solid gray;
    text-align: left;
   }
    th{
        background-color: gainsboro;
    }
    #stockListBox{
            margin-top:50px;
            width: 700px;
			border: 2px solid gray;
			padding: 10px;
			line-height: 20pt;
            margin-left: 300px;

            
    }
    #blankBox,#blankDetail{
            margin-top:50px;
            width: 600px;
			border: 2px solid gray;
			padding: 10px;
			line-height: 20pt;
            margin-left: 300px;
           text-align: center;
    }
    #detailBox{
            width: 700px;
			border: 2px solid black;
			padding: 10px;
			line-height: 20pt;
            margin-left: 300px;
          
    }
    #buttons{
        margin-left: 180px;
    }
</style>
<script>
    function checkFormValue(input){
        if(input.value == null || input.value == " "){
          input.setCustomValidity("Please enter Name or Symbol");
        }
    }
    
    function clearResult(){
            document.location.href = "stock.php";
    }
</script>
<meta charset="utf-8">
<meta name="description" content="XML Exercise">
<meta name="robots" content="index, follow">
<title>XML Exercise</title>
</head>
<body>
<?php
    $inputText="";
    $Name="";
    $Symbol="";
    $Exchange="";
?>
    
<div  id="main">
    <p>Stcok Search<p><hr>
    <div>
    <form name='form' id = "stockForm"  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" >
        Company Name or Symbol:<input id="input" type="text" name = "inputText" oninput="checkFormValue(this)" required value="<?php if(isset($_POST['inputText'])) {echo $_POST['inputText'];}
        if(isset($_GET['input'])){echo $_GET['input'];}?>"><br/>
        
        <?php 
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $inputText = $_POST['inputText'];
                
            }
        ?>
        <div id="buttons">
            <button type="submit" name = "submit" value="Search" >Search</button>
            <input type="button"  value="clear"  onclick ="clearResult()"><br/>
        </div>
        <div style="text-align: center;">
        <a href='http://www.markit.com/product/markit-on-demand'>Powered by Market on Demand</a>
        </div>
    </form>
    </div>   
</div>
<?php 
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $markit_on_Demand_url = "http://dev.markitondemand.com/MODApis/Api/v2/Lookup/xml?input=";
        $markit_on_Demand_url = $markit_on_Demand_url . urlencode($inputText);
        $xmlDoc = simplexml_load_file($markit_on_Demand_url);
        if($xmlDoc ->LookupResult ->count() > 0){
					echo "<table id=\"stockListBox\">";
						echo "<tr>";
							echo "<th>Name</th>";
							echo "<th>Symbol</th>";
							echo "<th>Exchange</th>";
							echo "<th>Details</th>";
						echo "</tr>";
						foreach ($xmlDoc -> LookupResult as $searchResult) {
							$Name = $searchResult -> Name;
							echo "<tr><td>" . $Name . "</td>";
							$Symbol = $searchResult -> Symbol;
							echo "<td>" . $Symbol . "</td>";
							$Exchange = $searchResult -> Exchange;
							echo "<td>" . $Exchange . "</td>";
							echo '<td><a href = "'.$_SERVER["PHP_SELF"]."?symbol=".$Symbol."&input=".$_POST['inputText']."\"> More Info</a></td></tr>";
							echo "<tr></tr>"; 
						}
					echo "</table>";
				}
				else {
					echo "<div id=\"blankBox\" >";
					echo "<table><tr> No Records has been found.</tr></table>";
					echo "</div>";
				}
    }
    
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        if(isset($_GET["symbol"])){
            $jasonSymbol = $_GET["symbol"];
            $jasonURL = 'http://dev.markitondemand.com/MODApis/Api/v2/Quote/json?symbol=' . $jasonSymbol;
            $jasonContent = file_get_contents($jasonURL);
            $jasonObject = json_decode($jasonContent);
            if($jasonObject -> {'Status'} == "SUCCESS"){
                echo "<table id=\"detailBox\" border=\"1\">";
                echo "<tr><td> Name </td><td>" . $jasonObject -> {'Name'}."</td></tr>";
                echo "<tr><td> Symbol </td><td>" . $jasonObject -> {'Symbol'}."</td></tr>";
                echo "<tr><td> Last Price </td><td>" . $jasonObject -> {'LastPrice'}."</td></tr>";
                echo "<tr><td> Change </td><td>" . round($jasonObject -> {'Change'},2); if(round($jasonObject -> {'Change'},2) < 0)
                   {echo "<img src= 'Red_Arrow_Down.png' style ='width:15px;height:15px;'" ;}
                if(round($jasonObject -> {'Change'},2) > 0)
                   {echo "<img src= 'Green_Arrow_Up.png' style ='width:15px;height:15px;'" ;}
                echo "</td></tr>";
                echo "<tr><td> Change Percent </td><td>" . round($jasonObject -> {'ChangePercent'},2)."%";
                if(round($jasonObject -> {'ChangePercent'},2)< 0)
                   {echo "<img src= 'Red_Arrow_Down.png' style ='width:15px;height:15px;'" ;}
                if(round($jasonObject -> {'ChangePercent'},2) > 0)
                   {echo "<img src= 'Green_Arrow_Up.png' style ='width:15px;height:15px;'" ;}
                echo "</td></tr>";
                date_default_timezone_set('America/Los_Angeles');
                $datetime = strtotime($jasonObject -> {'Timestamp'});
                echo "<tr><td> Timestamp </td><td>" . date("Y-m-d h:i A", $datetime)." PST
                </td></tr>";
                if(round($jasonObject -> {'MarketCap'}/1000000000,2) > 0){
                echo "<tr><td> Market Cap </td><td>" . round($jasonObject -> {'MarketCap'}/1000000000,2)."B</td></tr>";}
                if(round($jasonObject -> {'MarketCap'}/1000000000,2) <= 0){
                echo "<tr><td> Market Cap </td><td>" . round($jasonObject -> {'MarketCap'}/1000000,2)."M</td></tr>";
                }
                echo "<tr><td> Volume </td><td>" . number_format($jasonObject -> {'Volume'})."</td></tr>";
                $minusValue =($jasonObject -> {'LastPrice'} - $jasonObject -> {'ChangeYTD'});
                if(round($minusValue,2)<0){
                    echo "<tr><td> Change YTD </td><td>(" . round($minusValue,2).")<img src= 'Red_Arrow_Down.png' style ='width:15px;height:15px;'</td></tr>";
                }
                if(round($minusValue,2)==0){
                    echo "<tr><td> Change YTD </td><td>" . round($minusValue,2)."</td></tr>";
                }
                if(round($minusValue,2)>0){
                    echo "<tr><td> Change YTD </td><td>" . round($minusValue,2)."<img src= 'Green_Arrow_Up.png' style ='width:15px;height:15px;'</td></tr>";
                }
                echo "<tr><td> Change Percent YTD </td><td>" . round($jasonObject -> {'ChangePercentYTD'},2)."%";
                if(round($jasonObject -> {'ChangePercentYTD'},2) < 0)
                   {echo "<img src= 'Red_Arrow_Down.png' style ='width:15px;height:15px;'" ;}
                if(round($jasonObject -> {'ChangePercentYTD'},2) > 0)
                   {echo "<img src= 'Green_Arrow_Up.png' style ='width:15px;height:15px;'" ;}
                echo "</td></tr>>";
                echo "<tr><td> High </td><td>" . $jasonObject -> {'High'}."</td></tr>";
                echo "<tr><td> Low </td><td>" . $jasonObject -> {'Low'}."</td></tr>";
                echo "<tr><td> Open </td><td>" . $jasonObject -> {'Open'}."</td></tr>";
                echo "</table>";  
            }
            else {
					echo "<div id=\"blankDetail\" >";
					echo "<table><tr>There is no stock information available.</tr><table>";
					echo "</div>";
				}
        }
        
    }
    
?>
</body>
</html>