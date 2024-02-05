<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>DYMO Label Winsol</title>
<link rel="stylesheet" type="text/css" href="PreviewAndPrintLabel.css">
<script src="dymo.connect.framework.js" type="text/javascript" charset="UTF-8"> </script>
<script src="PreviewAndPrintLabel.js" type="text/javascript" charset="UTF-8"> </script>
<meta id="ConnectiveDocSignExtentionInstalled" name="ConnectiveDocSignExtentionInstalled" data-extension-version="1.0.5"></head>

<body>
<h1>DYMO Label Winsol</h1>
 


<div class="top">
<div class="left" style="display:none;">
    <div id="labelFileSelection">
        <label for="labelFile">Select label file with address object: </label>
        <input type="file" id="labelFile" name="labelFile">
    </div>
    
    <div id="addressDiv">
        <label for="addressTextArea">Current address:</label><br>
        <textarea name="addressTextArea" id="addressTextArea" rows="5" cols="40" disabled="">        </textarea>
    </div>
</div>

<div class="content">
    <div id="labelImageDiv">
        <img id="labelImage" src="" alt="label preview">
    </div>

    <div class="printControls">
        <div id="printersDiv">
            <label for="printersSelect">Printer:</label>
            <select id="printersSelect"></select>
        </div>

        <div id="printDiv">
            <button id="printButton" disabled="">Print</button>
<!--            <a id="printButton"><img src="" alt=""/>Print</a>-->
        </div>
    </div>

    <div>
        <label>***** <b>Printer Details Information</b> *****</label>
        <div id="printerDetail"></div>
    </div>

    <div style="display:none;">
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $i5link = db2_connect("S4411711","QPGMR", "ZONNETENT");
    if (!$i5link){
        die("<br>Connect fail errno=".db2_errno()." msg=".db2_errormsg());
    }
    $sql  = " SELECT WFI, ARTNR, ARTNB, ARTGEB, GEBRNB, AREKN1 FROM OARTLIB.PFWARTIK WHERE WUSER='".substr(trim($_GET['username']),0,10)."'";
    $result= db2_exec($i5link, $sql) or die ("<p>Failed Query". db2_stmt_error() . ":" . db2_stmt_errormsg() . "<p>");
    if(!empty($result)) {
        $ii=0;
        echo "<ul id='list'>";
        while ($d = db2_fetch_both($result)) {
            $ii=$ii+1;
            $d['ARTNB']=str_replace("&"," - ",$d['ARTNB'] );
            echo "<li>" . trim($d['ARTNR'])."$".trim($d['ARTNB'])."$".trim($d['ARTGEB'])."$".trim($d['GEBRNB'])."$".trim($d['AREKN1']) . "</li>";
        }
        echo "</ul>";
    }
    ?>
    </div>
</div>
</div>

</body></html>