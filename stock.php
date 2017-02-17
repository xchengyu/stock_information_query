<!doctype html>
<html>
    <head>
        <title>Stock Search</title>
        <style>
            body{
                padding: 0px;
                border: 0px;
                margin: 0px auto;
                text-align: center;
            }
            .searchform{
                width: 400px;
                height: 180px;
                border: 2px;
                border-style: solid;
                border-color:#CCC;
                padding: 0px;
                text-align: center;
                position: relative;
                margin:auto;
                top: 50px;
                background-color:#F0F0F0;
            }
            .line{
                border: 0px;
                padding: 0px;
                width:390px;
                position: relative;
                border:1px;
                border-style: solid;
                border-color:#CCC;
                margin:0px auto;
            }
            .theform{
                border: 0px;
                padding-top: 10px;
                width: 390px;
                height: 60px;
                position: relative;
                margin: 0px auto;
                text-align: left;
            }
            #stockform{
                border: 0px;
                padding: 0px;
                width:inherit;
                height: inherit;
                position: relative;
            }
            #search{
                position: relative;
                left: 184px;
                top: 5px;
            }
            #clear{
                position: relative;
                left: 184px;
                top: 5px;
            }
            .result1{
                position: relative;
                width: 500px;
                height:30px;
                border: 2px;
                border-style: solid;
                border-color:#CCC;
                background-color:#F0F0F0;
                text-align:center;
                margin:0px auto;
                top: 70px;
                padding: 0px;
            }
            .p1{
                width: 500px;
                height:30px;
                position: relative;
                line-height: 0px;
            }
            #result2{
                position: relative;
                border: 2px;
                border-style: solid;
                border-color:#CCC;
                background-color:#FCFCFC; 
                width: 550px;
                height:auto;
                text-align:left;
                margin:0px auto;
                top: 70px;
                padding: 0px;
                border-collapse:collapse; 
            }
            .tdStyle{
                margin: 0px auto;
                padding: 0px;
                border: 1px;
                border-style: solid;
                border-color:#CCC;
                border-collapse:collapse;
            }
            .thStyle{
                margin: 0px auto;
                padding: 0px;
                border: 1px;
                border-style: solid;
                border-color:#CCC;
                border-collapse:collapse;
                background-color: #F0F0F0;
            }
            #result3{
                position: relative;
                border: 2px;
                border-style: solid;
                border-color:#CCC;
                width: auto;
                height:auto;
                text-align:center;
                margin:0px auto;
                top: 70px;
                padding: 0px;
                border-collapse:collapse; 
            }
            .col1{
                margin: 0px auto;
                padding: 0px;
                width:250px;
                border: 1px;
                border-style: solid;
                border-color:#CCC;
                border-collapse:collapse;
                background-color: #F0F0F0;
                text-align:left;
            }
            .col2{
                margin: 0px auto;
                padding: 0px;
                border: 1px;
                width:250px;
                border-style: solid;
                border-color:#CCC;
                border-collapse:collapse;
                background-color:#FCFCFC; 
            }
        </style>
        <script type="text/javascript">
                    function clearAll(){
                        document.location = "http://cs-server.usc.edu:32929/stock.php";
                    }
        </script>
    </head>
    <body>
        <div class="searchform">
            <h1 style="line-height:10px;"><i><b>Stock Search</b></i></h1>
            <div class="line"></div>
            <div class="theform">
                <form method="post" id="stockform">
                    <label>Company Name or Symbol:</label>
                    <input type="text" name="NameSymbol" id="Name_Symbol" value="<?php echo (isset($_POST["NameSymbol"])||isset($_GET["Name"]))&&!isset($_POST["clear"])?(isset($_GET["Name"])?$_GET["Name"]:$_POST["NameSymbol"]):""?>" size="20px" required>
                    <br>
                    <input name="submit" id="search" type="submit" value="Search">
			        <input name="clear" id="clear" type="button" value="Clear" onclick="clearAll()">
                </form>
            </div>
            <a href="http://www.markit.com/product/markit-on-demand">Powered by Markit on Demand</a>
        </div>
        <?php
        if(!isset($_POST["NameSymbol"]))
        {
            echo "";
        }
        else
        {
            $lookUpURL1="http://dev.markitondemand.com/MODApis/Api/v2/Lookup/xml?input=".$_POST["NameSymbol"];
            $xmlFile = simplexml_load_file($lookUpURL1);
            $result="";
            if(empty($xmlFile))
            {
                $result .= "<div class='result1'>";
                $result .= "<p class='p1'>"."No Records has been found!"."</p>";
                $result .= "</div>";
                if(isset($_POST["clear"]))
                {
                    $result="";
                    echo "";
                }
                else
                {
                    echo $result;
                }
            }
            else
            {
                $result.="<table id='result2'><tr><th class='thStyle'>Name</th><th class='thStyle'>Symbol</th><th class='thStyle'>Exchange</th><th class='thStyle'>Details</th></tr>";
                $num=$xmlFile->count();
                for($i=0;$i<$num;$i++)
                {
                    $result.="<tr>";
                    $curChild=$xmlFile->children()[$i];
                    $result.="<td class='tdStyle'>".$curChild->children()[1]."</td>";
                    $result.="<td class='tdStyle'>".$curChild->children()[0]."</td>";
                    $result.="<td class='tdStyle'>".$curChild->children()[2]."</td>";
                    $result.="<td class='tdStyle'>"."<a href='http://cs-server.usc.edu:32929/stock.php?Name=".$_POST["NameSymbol"]."&"."data=".$curChild->children()[0]."'>More Info</a>"."</td>";
                    $result.="</tr>";
                }
                $result.="</table>";
                if(isset($_POST["clear"]))
                {
                    $result="";
                    echo "";
                }
                else
                {
                    echo $result;
                }
            }
        }
        if(!isset($_GET["data"]))
        {
            echo "";
        }
        else
        {
            $result="";
            $lookUpURL2="http://dev.markitondemand.com/MODApis/Api/v2/Quote/json?symbol=".$_GET["data"];
            if(file_get_contents($lookUpURL2)==FALSE)
            {
                $result .= "<div class='result1'>";
                $result .= "<p class='p1'>"."There is no stock information available!"."</p>";
                $result .= "</div>";
                echo $result;
            }
            else
            {
                $jsonFile = file_get_contents($lookUpURL2);
                $jsonObj=json_decode($jsonFile,true);
                $status=$jsonObj["Status"];
                if($status!="SUCCESS")
                {
                    $result.= "<div class='result1'>";
                    $result.= "<p class='p1'>"."There is no stock information available!"."</p>";
                    $result.= "</div>";
                    echo $result;
                }
                else
                {
                    $name=$jsonObj["Name"];
                    $symbol=$jsonObj["Symbol"];
                    $lastPrice=$jsonObj["LastPrice"];
                    $change=number_format($jsonObj["Change"],2,".",",");
                    if($jsonObj["Change"]<0)
                    {
                        $change.="<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png' width='4%' height='4%'/>";
                    }
                    else
                    {
                        $change.="<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png' width='4%' height='4%'/>";
                    }
                    $changePercent=number_format($jsonObj["ChangePercent"],2,".",",")."%";
                    if($jsonObj["ChangePercent"]<0)
                    {
                        $changePercent.="<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png' width='4%' height='4%'/>";
                    }
                    else
                    {
                        $changePercent.="<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png' width='4%' height='4%'/>";
                    }
                    date_default_timezone_set('America/Los_Angeles');
                    $timestamp=date("Y-m-d h:ia",strtotime($jsonObj["Timestamp"]))." PST";
                    $marketCap=$jsonObj["MarketCap"]<5000000?number_format($jsonObj["MarketCap"]/1000000.0,2,".",",")."M":number_format($jsonObj["MarketCap"]/1000000000.0,2,".",",")."B";
                    $volume=number_format($jsonObj["Volume"],2,".",",");
                    $diff=$jsonObj["LastPrice"]-$jsonObj["ChangeYTD"];
                    $changeYTD="";
                    if($diff<0)
                    {
                        $changeYTD.="(".number_format($diff,2,".",",").")";
                        $changeYTD.="<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png' width='4%' height='4%'/>";
                    }
                    else
                    {
                        $changeYTD.=number_format($diff,2,".",",");
                        $changeYTD.="<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png' width='4%' height='4%'/>";
                    }
                    $changePercentYTD=number_format($jsonObj["ChangePercentYTD"],2,".",",")."%";
                    if($jsonObj["ChangePercentYTD"]<0)
                    {
                        $changePercentYTD.="<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png' width='4%' height='4%'/>";
                    }
                    else
                    {
                        $changePercentYTD.="<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png' width='4%' height='4%'/>";
                    }
                    $high=number_format($jsonObj["High"],2,".",",");
                    $low=number_format($jsonObj["Low"],2,".",",");
                    $open=number_format($jsonObj["Open"],2,".",",");

                    $result="<table id='result3'>";
                    $result.="<tr><td class='col1'>Name</td><td class='col2'>".$name."</td></tr>";
                    $result.="<tr><td class='col1'>Symbol</td><td class='col2'>".$symbol."</td></tr>";
                    $result.="<tr><td class='col1'>Last Price</td><td class='col2'>".$lastPrice."</td></tr>";
                    $result.="<tr><td class='col1'>Change</td><td class='col2'>".$change."</td></tr>";
                    $result.="<tr><td class='col1'>Change Percent</td><td class='col2'>".$changePercent."</td></tr>";
                    $result.="<tr><td class='col1'>Timestamp</td><td class='col2'>".$timestamp."</td></tr>";
                    $result.="<tr><td class='col1'>Market Cap</td><td class='col2'>".$marketCap."</td></tr>";
                    $result.="<tr><td class='col1'>Volume</td><td class='col2'>".$volume."</td></tr>";
                    $result.="<tr><td class='col1'>Change YTD</td><td class='col2'>".$changeYTD."</td></tr>";
                    $result.="<tr><td class='col1'>Change Percent YTD</td><td class='col2'>".$changePercentYTD."</td></tr>";
                    $result.="<tr><td class='col1'>High</td><td class='col2'>".$high."</td></tr>";
                    $result.="<tr><td class='col1'>Low</td><td class='col2'>".$low."</td></tr>";
                    $result.="<tr><td class='col1'>Open</td><td class='col2'>".$open."</td></tr></table>";
                    echo $result;
                }
            }             
        }
        ?>
        <noscript></noscript>
    </body>
</html>