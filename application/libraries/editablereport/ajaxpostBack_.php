<?php
session_start();
# for use with companion post files
# alternative method is to just use ajl



$_SESSION["validuser"]=true;
$GLOBALS["pricesIncTax"]=false;
$GLOBALS["basepath"]=$_SESSION["basepath"];
$GLOBALS["clientpath"]=$_SESSION["clientpath"];
$basepath=$_SESSION["basepath"];
$clientpath=$_SESSION["clientpath"];


#include_once $_SESSION["clientpath"]."/ajstore/infincScreen.php";
#use standard single location
include_once $_SESSION["clientpath"]."/ii/iinc.php";
include_once "$basepath/crmDBAccess.php";
include_once "$basepath/masterfunctionsreturn.php";
include_once "$basepath/infclasses/arrayManipulator.php";
include_once "$basepath/infclasses/iSalesUtils.php";
include_once "$basepath/infclasses/iDivTable.php";
include_once "$basepath/infclasses/iCustom.php";
include_once "$clientpath/customscript/customInterface.php";
include_once "$basepath/infclasses/iForm.php";

$aS=new custominterface();
#$paramsx=urldecode($_POST["params"]);

$paramsx=str_replace("!amp!","&",$_POST["params"]);
#logitn("PB1: ".$paramsx);
$aS->logit("RAWNOTE: ".$paramsx);
#$paramsx=str_replace("amp;","&;",$paramsx);
#elog("pp $paramsxs","decoded 1 ajaxpostback 35");
$paramsx=html_entity_decode(rawurldecode($paramsx));
$paramsx=str_replace("&nbsp;"," ",$paramsx);

#ANK 16.12.2014
#$paramsx=str_replace("%2520","%20",$paramsx);
#$aS->logit("NOTE2520:".$paramsx);

#$paramsx=str_replace("%253D","%3D",$paramsx);
#$aS->logit("NOTE253D:".$paramsx);
#logitn("PB2: ".$paramsx);

#ANK strip out asc(160)
$search = chr(160);
$paramsx = str_replace($search," ",$paramsx);


if( isset($_POST["delim1"]) ){
 $delim1=$_POST["delim1"]!=""?$_POST["delim1"]:"!I!";
} else {
 $delim1 = "!I!";	
}	
#$delim1=$_POST["delim1"]!=""?$_POST["delim1"]:"!I!";

if( isset($_POST["delim2"]) ){
 $delim2=$_POST["delim2"]!=""?$_POST["delim2"]:"=";
} else {
 $delim2 = "=";	
}	
# $delim2=$_POST["delim2"]!=""?$_POST["delim2"]:"=";
 

#} else {
# $delim2 = "";	
#}
#elog("$delim1 $delim2","apb 31");
$aS->paramsA=formCruncher($paramsx,$delim1,$delim2);
$url=$aS->paramsA["url"];
#elog("$url","ajaxpostBack 34");

#tfw("ajpb.txt","u $url $p $paramsx",true);

if(!(strpos($url,".php"))&!(strpos($url,".html"))) $url.=".php";

if(is_readable("$basepath/content/$url")) {
	$path="$basepath/content";
}
#echo "<br>?pp $path $params ok<br>";

if(is_readable("$basepath/contentajl/$url")) {
	$path="$basepath/contentajl";
}

if(is_readable("$basepath/masterscripts/$url")) {
	$path="$basepath/masterscripts";
}

if(is_readable("$clientpath/customscript/content/$url")){
	$path="$clientpath/customscript/content";
}
if(is_readable("$clientpath/customscript/contentajl/$url")){
	$path="$clientpath/customscript/contentajl";
}


if(is_readable("$clientpath/customscript/masterscripts/$url")){
	$path="$clientpath/customscript/masterscripts";
	#elog("path $path");

}

#tfw("ajbpath.txt","p $paramsx tried $url found u path $path  $p $paramsx",true);
#elog("try $path $url","apb 64");

if($_POST["params"]){
	$aS->paramsA["via"]="postback";
	$aS->paramsX=$paramsx;
	include_once "$path/$url";
}



?>
