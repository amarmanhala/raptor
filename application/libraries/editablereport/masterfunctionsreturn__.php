<?php

if(array_key_exists("x1",$_GET)) {
	
if(!$_GET["xl"]){
 if(is_readable("./infscripts.js")){
	if(!$GLOBALS["noinfjs"]){
  	#include_once("./infscripts.js");
	}
 }
}

}
$GLOBALS["totalblocknewoffer"]=true;
$GLOBALS["blocknewoffer"]=true;

if(array_key_exists("mode",$_GET)) {
	
if (!(is_null($_GET["mode"]))){
 $modex=$_GET["mode"];
	 if (($modex=="new")|($modex=="edit")){
		 $GLOBALS["labelclass"]="formlabel";
		 $GLOBALS["dataclass"]="formdata";
         }else{
	 $GLOBALS["labelclass"]="vlabel";
	 $GLOBALS["dataclass"]="vdata";
	 }
}else{
	 $GLOBALS["labelclass"]="vlabel";
	 $GLOBALS["dataclass"]="vdata";
}

}
// $GLOBALS["labelclass"]="formlabel";
// $GLOBALS["dataclass"]="formdata";

 $GLOBALS["lstyle"][0]="lgtextstyle";
 $GLOBALS["lstyle"][1]="lstextstyle";
 $GLOBALS["textonly"]["contactid"]="contactid";
 $GLOBALS["textonly"]["customerid"]="customerid";
 $GLOBALS["textonly"]["prodid"]="prodid";
 $GLOBALS["textonly"]["serialno"]="serialno";
 $GLOBALS["textonly"]["phone"]="phone";
 $GLOBALS["textonly"]["mobile"]="mobile";
 $GLOBALS["textonly"]["fax"]="fax";


function masterform($table,$result,$cqd,$modex){
$GLOBALS["table"]=$table;
//$mfx.=dofield("modex","hidden",$modex,$modex);
while($scf = mysql_fetch_array($result)){
 $jl="";
 $ja="";
 $fieldn=$scf["fieldid"];

//echo "xx $fieldn";
//echo "<br>xx $fieldn";

 $fname=$scf["fielddesc"];
 if ($fname==""){
  $fname=$fieldn;
 }
  unset($GLOBALS["tix"]);
  unset($GLOBALS["tix"]);
 $ft=$scf["type"];
 $dt=$scf["datatype"];
 $vt=$scf["valtable"];
 $rn=$scf["pos"];
 $cs=$scf["colspans"];
 $pv=$scf["privilege"];
 $ul=$scf["userlevel"];
 $tix=$scf["tabindex"];
 $GLOBALS["tix"]=$tix;
 $cmp=$scf["compulsory"];
 $jl=($scf["jlink"]);
 $ja=($scf["jaction"]);
 $ttip=($scf["tooltip"]);

 if(($ft!="autoid")&&($ft!="hidden")){
 	$inf++;
 	if($inf==1){
 	 $GLOBALS["focusf"]=$fieldn;
 	}
 }
 $dfv="";
 eval(stripslashes($scf["defaultv"]));
// echo "<br>vvvvv".$scf["defaultv"]." $dfv";
/* if($dfv=="date(\"Y-m-d\")"){
  $dfv=date("Y-m-d");
 }else{
  if ($ft=="date"){
   $dfv=date("Y-m-d");
  }
 }
*/
 //$dfv=eval($dfv);


 unset($cflag);
 if($cmp){
  $cflag="*";
 }
 //always validated unless any of the following
 if (($vt!="")&&
 ($ft!="mastertable")&&
 ($ft!="cust_pop")&&
 ($ft!="subtable")&&
 ($ft!="validatedsql")&&
 ($ft!="validatedarray")&&
 ($ft!="options_list")&&
 ($ft!="radio")&&
 ($ft!="validatedid")){
  $ft="validated";
 }


 if ($pv!=""){
//  if (!editok($pv)){
  if (!editok($table)){
   //overrides a validated table
   $ft="hidden";
  }
 }

 $datastyle="formdata";
 $isz=25;
 if ($rn!=$lastrow){
  if ($ft!="hidden"){
   if ($lastrow>0){
	if (levelok($ul)){
	    $mfx.="</TR><TR>";
	}
   }
  }
 }
 $csx="";
 if ($cs>0){
  $csx=" colspan=".$cs;
 }
// echo "mmmmsss $ft $fname";

 $mfx.="<div id=\"FID$fieldn\">";
 switch ($ft){
  case "hidden":
  break;
  case "autoid":
  if($modex!="new"){
   if (levelok($ul)){
	   $mfx.="
	   <TD class=".$GLOBALS["labelclass"]." valign=top>".$fname." $cflag</TD>
	   <TD class=".$GLOBALS["dataclass"]." valign=top ".$csx.">";
	  }
  }
  break;
  case "submit":
  $mfx.="<td>";
  break;
  case "button":
  $mfx.="<td>";
  break;
  case "comment":
  $mfx.="<td colspan=2 class=fcomment>$fname ";
  break;


  default:
	if($jl!=""){
//	$xj="go in here";
//	$xj=eval($jl);
	}
   if (levelok($ul)){
       $fname=tooltip_gear($fieldn,$fname,$ttip);
	   $mfx.="
	   <TD class=".$GLOBALS["labelclass"]." valign=top>";
	   eval($jl);
	   $mfx.="  $fname $cflag </TD>
	   <TD class=".$GLOBALS["dataclass"]." valign=top ".$csx.">";
		$xj="";
   }
  break;
 }

 $dv=stripslashes($cqd[$fieldn]);
 //echo "<br> xxxx lokking for $fieldn $dv";
 if(($dv=="")||($dv=="Choose an option")){
//  echo " xwtfx $fieldn";
  //loading from session values for claim options
  //requires decustomisation.
  if(isset($_SESSION["claim"][$fieldn])){
   $GLOBALS["sessinplay"]=true;
   $dv=stripslashes($_SESSION["claim"][$fieldn]);
// echo "<br>wtf $fieldn : $dv";

  }
  if(isset($_SESSION["newjob"][$fieldn])){
   $GLOBALS["sessinplay"]=true;
   $dv=stripslashes($_SESSION["newjob"][$fieldn]);
 //echo "<br> $fieldn : $dv";

  }

 }

 $GLOBALS[$fieldn]=$dv;
// echo $dv;
 if($dv==""){
  $dv=$dfv;
 }
 if (levelok($ul)){
	 $mfx.=displayoredit($ft,$fieldn,$dv,$vt,$datastyle,$modex,$isz,$dt,$ja,$tix);
	 $mfx.="</TD></div>";
 }
 $lastrow=$rn;
 //$mfx.="<tr><td><br>was $fieldn</td></tr>";
}
if ($modex!="new"){
 $submitval="Update";
 }
 else{
 $submitval="Add New";
}
if(isset($GLOBALS["alt_submitlabel"])){
 $submitval=$GLOBALS["alt_submitlabel"];
}

//echo "wtfimb";
if (($_SESSION["internaluser"])||($_SESSION["casualuser"])){
 if (($modex=="new")|($modex=="edit")){
  $mfx.="<TR><TD><INPUT class=formbutton type=\"submit\" name=\"update\" value=\"".$submitval."\" tabindex=300></TD>";
  if ($table=="inventory"){
  $submitval="Copy to New";
  $mfx.="<TD><INPUT class=formbutton type=\"submit\" name=\"update\" value=\"".$submitval."\"></TD>";
  }
  $mfx.="</TR>";
 }
}
$mfx.="</TABLE>";
$mfx.="</FORM>";
$mfx.=includedfunctions($table);
createbranchfunctions();

return $mfx;
}

Function displayoredit($ft,$fieldn,$dv,$vt,$datastyle,$modex,$isz,$dt,$ja,$tix){
    //echo "$modex";
    switch($modex){
     case "edit":
     $mfx.=liveoptions($ft,$fieldn,$dv,$vt,$datastyle,$isz,$dt,$ja,$tix);
     break;
     case "new":
     //$mfx.="<br>$fieldn $ft $vt <br>";
     $mfx.=liveoptions($ft,$fieldn,$dv,$vt,$datastyle,$isz,$dt,$ja,$tix);
     break;
     default:
     if ($fieldn=="imagename"){
      $GLOBALS["imx"]=$dv;
     }
     //echo "ttttt $ft $fieldn";
     if(substr($ft,0,4)=="date"){
       $dv=date("d M, Y",strtotime($dv));
     }else{
      if($ft=="date_input"){
       $dv=date("d M, Y",strtotime($dv));
      }
     }
     if ($fieldn!="password"){
      if ($ft!="hidden"){
      $mfx.=nl2br($dv);
         if(isset($GLOBALS["phonefields"])){
      		 	if(is_array($GLOBALS["phonefields"])){
      			if(in_array($fieldn, $GLOBALS["phonefields"])){
      				$mfx.=newcall_link($GLOBALS["phonecust"]);
      			}
      			}
         }

      }
     }
    }
 return $mfx;
}


function liveoptions($ft,$fieldn,$dv,$vt,$datastyle,$isz,$dt,$ja,$tix){
// echo "<br> lo : $fieldn $dv";
//echo "fffff $fieldn $ft";

 switch ($fieldn){
  //caters for exceptions
  case "xstandardcost":
  $mfx.="<INPUT class=".$datastyle." type=\"text\" name=\"".$fieldn."\" size=".$isz." value=\"".$dv."\" onChange=\"computesells(this)\">";
  break;
  case "xlatestcost":
  $mfx.="<INPUT class=".$datastyle." type=\"text\" name=\"".$fieldn."\" size=".$isz." value=\"".$dv."\" onChange=\"computesells(this)\">";
  break;
  case "xsell1":
  $mfx.="<INPUT class=".$datastyle." type=\"text\" name=\"".$fieldn."\" size=".$isz." value=\"".$dv."\" onChange=\"computesells(this)\">";
  break;
  case "xcurrency":
  if (modok(currency)){
   $mfx.="<INPUT class=".$datastyle." type=\"text\" name=\"".$fieldn."\" size=".$isz." value=\"".$dv."\" onChange=\"computesells(this)\">";
  }
  break;
  //case "Location":
  //$mfx.="<INPUT class=".$datastyle." type=\"text\" name=\"".$fieldn."\" size=".$isz." value=\"".$dv."\" onChange=\"computesells(this)\">";
  //break;
 default:
  switch ($ft){
   case "text":
   //input type text, data type could be numeric
// echo "wtf-".$ft.$dv;
   if ($dt=="numeric"){
      if($dv==""){
       $dv="0";
      }
   }
   $mfx.="<INPUT class=".$datastyle." type=\"text\" name=\"".$fieldn."\" size=".$isz." value=\"".$dv."\" $ja tabindex=$tix>";
   if(isset($GLOBALS["phonefields"])){
		 	if(is_array($GLOBALS["phonefields"])){
			if(in_array($fieldn, $GLOBALS["phonefields"])){
				$mfx.=newcall_link($GLOBALS["phonecust"]);
			}
			}
   }
   break;
   case "textarea":
   if(isset($GLOBALS["tix"])){
     $txx="tabindex=".$GLOBALS["tix"];
   }
   if($vt){
    $rowscols=$vt;
   }else{
    $rowscols="rows=5 cols=60";
   }

   $mfx.="<TEXTAREA class=".$datastyle." name=\"".$fieldn."\" $rowscols $txx $ja>".$dv."</TEXTAREA>";
   break;
   case "checkbox":
   $mfx.="<INPUT class=".$datastyle." type=\"checkbox\" name=\"".$fieldn."\" ";
   if ($dv=="on"){
    $mfx.="checked";
   }
   $mfx.=" $ja>";
   break;
   case "validated":
   //etest("fff $fieldn : $dv");
   $msx=makeselect($vt,$datastyle,$fieldn,$dv,true,$ja);
   $mfx.=$msx;
   break;
   case "validatednodesc":
   $msx=makeselect($vt,$datastyle,$fieldn,$dv,false,$ja);
   $mfx.=$msx;
   break;

   case "validatedid":
   $msx=makemasteridselect($vt,$datastyle,$fieldn,$dv,$ja);
   $mfx.=$msx;
   break;

   case "options_list":
   //$arrx="array(".$vt.")";
   //$arrv=explode(",",$vt);
   //echo $vt;
   if(isset($vt)){
   	eval(stripslashes($vt));
   //echo "<br>2222 $fieldn $vt  ".implode(",",$arr)."333";

   }
//   $arr=$GLOBALS["stimes"];
   $sz=0;
   if(isset($arr)){
   $sz=sizeof($arr);
   	$bno=true;
   }
   if($sz>=0){
   	//echo "ttt $fieldid test $dv $vt $sz";
   	if($_SESSION["selectadds"]){
   	//echo " ttt1";
	if(!$GLOBALS["blocknewoffer"]){
   	//echo " ttt2";
   	 if(!$GLOBALS["blocknewoffer"][$fieldn]){
   	//echo "ttt3";
   	  if(!$GLOBALS["blocknewoffer"][$fieldn]){
   	   //allows blocking of new tables where higher info
   	   //must pre-exist (eg custid for new contacts).
   	    $bno=false;
   	//echo "ttt4";
   	   $ax="onclick=\"testfornew(this);\"";
	 	if($fieldn=="contactid"){
	   	  $ax="onclick=\"testfornewcust(this,'contact');\"";
		}
   	  }
   	 }
   	}
   	}
   	$mfx.="<select class=\"formdata\" name=\"$fieldn\" $ax>";
   	$mfx.=makeidselectoptionsarraysimple($arr,$dv,$bno);
   }
   break;

   case "validatedarray":
   //show values as radio options
   $arrx="array(".$vt.")";
   $arrv=explode(",",$vt);
   //echo "<br> $vt : $arrx : $arrv";
   $GLOBALS["radiofield"][$fieldn]=$fieldn;
   if(isset($arrv)){
   	foreach($arrv as $av){
         loadbranchfunctions($fieldn,$av);
         $sz=sizeof($GLOBALS["radiocalls"][$fieldn]);
         //echo "<br> found $av size = $sz";
         if ($sz>0){
          $cv="onclick=\"$av();\" ";
         }else{
          $cv=$ja;
         }
   	$mfx.="<div class=gutscell><input type=radio class=\"formdata\"  name=\"$fieldn\" value=\"$av\" ";
   	if($dv==$av){
   	$mfx.="CHECKED";
   	}
   	$mfx.=" $cv>$av</div>";
   	}
   }
   break;
   case "validatedsql":
   $sqlx=$vt;
   //echo $sqlx;
   //field names fixed so far.
   //need function to auto determine first 2 fields in query.
   //in the meantime, write sql to rename the field
   $fieldid=$fieldn;
   $fieldtofind=$fieldn;
//   $fieldid="customerid";
   //echo "<br> $fieldid $fieldn";
   $msx.="<select class=\"formdata\" name=\"$fieldid\">";
   //$fieldid="customerid";
   $msx.=makeidselectoptions($sqlx,$dv,$fieldtofind,$fieldid);
   $mfx.=$msx;

   break;
   case "mastertable":
   $GLOBALS["mastertableA"][]=$vt;
   $msx=makemasterselect($vt,$datastyle,$fieldn,$dv);
   //$msx.="msubtable ver";
   //echo "yyyy $msx zzz";
   $mfx.=$msx;
   break;
   case "subtable":
   $msx=makesubtselect($vt,$datastyle,$fieldn,$dv);
   //$msx.="subtable ver";
   $mfx.=$msx;
   break;


   case "radio":
   //show values as radio options;
   $tfields=totalfieldarray($vt);
   $sqlx="select * from $vt";
   $fid=$vt."id";
   $fname=$vt."desc";
   $tfields=array($fid,$fname);
   $arrv=infqueryarray_pair($sqlx,$tfields);
   //echo "<br> $vt : $arrx : $arrv";
   $GLOBALS["radiofield"][$fieldn]=$fieldn;
   if(isset($arrv)){
   	foreach($arrv as $av){
         loadbranchfunctions($fieldn,$av);
         $sz=sizeof($GLOBALS["radiocalls"][$fieldn]);
         //echo "<br> found $av size = $sz";
         if ($sz>0){
          $cv="onclick=\"$av();\" ";
         }else{
          $cv=$ja;
         }
   	$mfx.="<div class=gutscell><input type=radio class=\"formdata\"  name=\"$fieldn\" value=\"$av\" ";
   	if($dv==$av){
   	$mfx.="CHECKED";
   	}
   	$mfx.=" $cv>$av</div>";
   	}
   }
   break;

   case "date":
   $msx=makedate($fieldn,$fid,$datastyle,$dv,false);
   $mfx.=$msx;
   break;
   case "time":
   $msx=maketime($fieldn,$inputok=1,$dv);
   $mfx.=$msx;
   break;
   case "date_calendar":
   $msx=makedate($fieldn,$fid,$datastyle,$dv,false);
   $mfx.=$msx;
   break;
   case "date_input":
   $msx=makedate($fieldn,$fid,$datastyle,$dv,true);
   $mfx.=$msx;
   break;
   case "date_nocal":
   $msx=makedate($fieldn,$fid,$datastyle,$dv,true,false);
   $mfx.=$msx;
   break;
   case "date_form":
   $msx=makedate_Form($fieldn,$fid,$datastyle,$dv);
   $mfx.=$msx;
   break;



   case "hidden":
   $mfx.="<INPUT type=\"hidden\" name=\"".$fieldn."\" value=\"".$dv."\" >";
   break;
   case "autoid":
   $mfx.="<INPUT type=\"hidden\" name=\"".$fieldn."\" value=\"".$dv."\" >";
   break;
   case "image":
   //$mfx.="<INPUT  class=".$datastyle." type=\"text\" name=\"".$fieldn."\"></TD>";
   //$mfx.="<input class=$datastyle name=\"$fieldn\" type=\"file\">";
   break;
   case "doc":
   //$mfx.="<INPUT  class=".$datastyle." type=\"text\" name=\"".$fieldn."\"></TD>";
   //$mfx.="<input class=$datastyle name=\"$fieldn\" type=\"file\">";
   $mfx.=docloader();
   break;

   case "cust_pop":
   $mfx=inputcustfield($dv);
   //makemasteridselect($vt,$datastyle,$fieldn,$dv);
   break;

   case "readonlytext":
   $datastyle=roformdata;
   $mfx.="<INPUT  class=".$datastyle." type=\"text\" name=\"".$fieldn."\" value=\"$dv\" readonly tabindex=800></TD>";
   break;

   case "submit":
   $datastyle=formdata;
   $mfx.="<INPUT  class=".$datastyle." type=\"submit\" name=\"".$fieldn."\" value=\"$dv\" readonly></TD>";
   break;

   case "button":
   $datastyle=formdata;
   $mfx.="<button  class=".$datastyle." name=\"".$fieldn."\" $ja>$dv</button></TD>";
   break;

   case "comment":
   $datastyle=formdata;
   $mfx.="</TD>";
   break;

   default:
   if ($dt=="numeric"){
    $vx="value=\"0\"";
   }
   $mfx.="<INPUT  class=".$datastyle." type=\"text\" name=\"".$fieldn."\" $vx></TD>";
   break;
  }
  break;

 }
 return $mfx;
}


function makeselect($vt,$datastyle=null,$fieldn=null,$dv=null,$sortonid=null,$jcall=null){
 switch ($vt){
  case ("users"):
  $sqlx="select * from users
  where (inactive is null
  or inactive!='on')
  order by userid ";
  $fieldtofind="userid";
  #elog("msu $slqx","mf 565");
  break;
  default:
  $sqlx="select * from $vt";
  if($sortonid){
    $sqlx.=" order by ".$vt."id";
  }else{
    $sqlx.=" order by $fieldn";
  }
 //order by ".$vt."id";
 //echo $sqlx;
  $fieldtofind=$vt."desc";
  break;
 }
 if(isset($GLOBALS["tix"])){
  $txx="tabindex=".$GLOBALS["tix"];
 }
 $txx.=" $jcall";
 if(!$GLOBALS["blocknewoffer"]){
 $oclicknew="onclick=\"testfornew(this);\"";
 }
 $msx="<SELECT class=".$datastyle." name=\"".$fieldn."\"  $oclicknew $jcall";
  $msx.=$GLOBALS["masterselectsizex"];
  if ($fieldn=="pricegroupid"){
  $msx.="onChange=\"computesells(this)\" ";
  }
  $msx.=" $txx>";
  if ($_GET["mode"]=="new"){
   //$dv="normal";
  }
  //echo $msx;
  switch ($vt){
   case ("users"):
   $msx.=makeselectoptionsfulluser($sqlx,$dv,$fieldtofind);
   break;
   default:
   $msx.=makeselectoptions($sqlx,$dv,$fieldtofind);
   break;
  }
  if ($vt=="userprofile"){
   if (!amiadmin()){
    unset($msx);
   }
  }
  return $msx;
}

function makeselectextra($vt,$datastyle,$fieldn,$dv,$ax){
 switch ($vt){
  case ("users"):
  $sqlx="select * from users
  where (inactive is null
  or inactive!='on')
  order by userid";
  $fieldtofind="userid";
  break;
  default:
  $sqlx="select * from $vt
  order by ".$vt."id";
 //echo $sqlx;
  $fieldtofind=$vt."desc";
  break;
 }
  $msx="<SELECT class=".$datastyle." name=\"".$fieldn."\" $ax";
  if ($fieldn=="pricegroupid"){
  $msx.="onChange=\"computesells(this)\" ";
  }
  $msx.=">";
  if ($_GET["mode"]=="new"){
   $dv="normal";
  }
  //echo $msx;
  $msx.=makeselectoptions($sqlx,$dv,$fieldtofind,$vt);
  if ($vt=="userprofile"){
   if (!amiadmin()){
    unset($msx);
   }
  }
  return $msx;
}


function makeselectoptions($sqlx,$dv,$fieldtofind){
  //$dv usually in edit mode from record.
  //could also be from session variable on tableid;
  if($dv==""){
   $dv=$_SESSION["claim"][$fieldtofind];
  }
  $result = mysql_query($sqlx);
  if(!$result) error_message(sql_error());
  $msx.="<OPTION  class=lgtextstyle>Choose an option</OPTION>";
  if(allownewadd($fieldtofind)){
  $msx.="<OPTION  class=lgtextstyle value=\"<--Add new-->\"><--Add new--></OPTION>";
  }

  while($vqd = mysql_fetch_array($result)){
   $val=$vqd[$fieldtofind];
   if ($fieldn=="pricegroupid"){
    $val=str_replace(" ","_",$val);
   }
   //etest("$val vs $dv");
   if ($val==$dv){
    $msx.="<OPTION value=\"".$val."\" SELECTED>".$val."</OPTION>";
    }else{
    $msx.="<OPTION value=\"".$val."\">".$val."</OPTION>";
   }
  }
  $msx.="</SELECT>";
  return $msx;
}

function makeidselectoptions($sqlx,$dv,$fieldtofind,$fieldid,$cao=true){
  //filewriter("iiii.txt","sq: $sqlx $fieldtofind $fieldid",true);
  $result = mysql_query($sqlx);
  if(!$result) $erx=sql_error();
	$erx=mysql_errno();
  if(!$result) tfw("badq.txt","bad $sqlx error:$erx",true);
  if($cao){
  $msx.="<OPTION value='' class=lgtextstyle>Choose an option</OPTION>";
  }
//  $msx.="<OPTION  class=lgtextstyle value=\"<--Add new-->\"><--Add new--></OPTION>";
  while($vqd = mysql_fetch_array($result)){
   $val=$vqd[$fieldtofind];
   $idval=$vqd[$fieldid];
   if ($fieldn=="pricegroupid"){
    $val=str_replace(" ","_",$val);
   }
//echo "<br> find $fieldid $idval";
   if ($idval==$dv){
//echo "<br> found $fieldid $idval";
   }
   $sx="";
   if ((($idval==$dv)|($val==$dv))&&($idval!="Choose an option")){
   $sx="SELECTED";
   }
   $msx.="<OPTION value=\"".$idval."\" $sx>".$val."</OPTION>";
  }
  $msx.="</SELECT>";
  #tfw("msx.txt","$sqlx $msx $fieldid $fieldtofind",true);
  return $msx;

}

function makeidselectoptionsarray($arr,$arrv,$dv,$fieldtofind,$fieldid){
  $msx.="<OPTION  class=lgtextstyle>Choose an option</OPTION>";
//  $msx.="<OPTION  class=lgtextstyle value=\"<--Add new-->\"><--Add new--></OPTION>";
  if (isset($arr)){
   foreach($arr as $idval){
    if (isset($arrv)){
     $val=$arrv[$idval];
     if ($fieldn=="pricegroupid"){
      $val=str_replace(" ","_",$val);
     }
    }else{
     $val=$idval;
    }
    if ($idval==$dv){
     $msx.="<OPTION value=\"".$idval."\" SELECTED>".$val."</OPTION>";
     }else{
     $msx.="<OPTION value=\"".$idval."\">".$val."</OPTION>";
    }
   }
  $msx.="</SELECT>";
  }
  return $msx;
}


function makeidselectoptionskeyedarray($arr,$dv,$fieldtofind,$fieldid){
  $msx.="<OPTION  class=lgtextstyle>Choose an option</OPTION>";
  if (isset($arr)){
   foreach($arr as $key=>$val){
    $sx="";
    if (($key==$dv)&&($key!="Choose an option")){
     $sx="SELECTED";
    }
    $msx.="<OPTION value=\"".$key."\" $sx>".$val."</OPTION>";
   }
  $msx.="</SELECT>";
  }
  return $msx;
}


function makeidselectoptionsvaluearray($arr,$dv){
  $msx.="<OPTION  class=lgtextstyle value=\"Choose an option\">Choose an option</OPTION>";
  if($_SESSION["selectadds"]){
  //$msx.="<OPTION  class=lgtextstyle value=\"<--Add new-->\"><--Add new--></OPTION>";
  }
  $i=1;
  if (isset($arr)){
   foreach($arr as $i=>$v){
    if ($v==$dv){
     $msx.="<OPTION value=\"".$v."\" SELECTED>".$v."</OPTION>";
     }else{
     $msx.="<OPTION value=\"".$v."\">".$v."</OPTION>";
    }
   }
  }
  $msx.="</SELECT>";
  return $msx;
}

function makeidselectoptionsarraysimple($arr,$dv="",$bno=true,$all=null){
  #echo "mmm $all";
  $cao=is_null($all)?"Choose an option":$all;
  if($bno){
  $GLOBALS["blocknewoffer"]=1;
  }
  if(!($GLOBALS["blockAll"])) $msx.="<OPTION  class=lgtextstyle value=\"$cao\">$cao</OPTION>";
  if($_SESSION["selectadds"]){
   if(!($GLOBALS["blocknewoffer"])){
  $msx.="<OPTION  class=lgtextstyle value=\"<--Add new-->\"><--Add new--></OPTION>";
   }
  }
  $i=1;
  if (isset($arr)){
   foreach($arr as $i=>$v){
     if($GLOBALS["id_plusdesc"]){
      $vx="$i - $v";
     }else{
	$vx=$v;
     }
    unset ($ckx);
    if ((($i==$dv)&&($i>0))||($v==$dv)){
     $ckx="SELECTED";
    }
    if (($i==$dv)&&(!is_numeric($i))){
     $ckx="SELECTED";
    }
    //$i=urlencode($i);
    //$vx=urlencode($vx);
    $msx.="<OPTION value=\"".$i."\" $ckx>".$vx."</OPTION>";
   }
  }
  $msx.="</SELECT>";
  return $msx;
}

function makeidselectoptionscountarray($arr,$dv=null){
  $msx.="<OPTION  class=lgtextstyle value=\"Choose an option\">Choose an option</OPTION>";
  if (isset($arr)){
//   echo "arr set";
   foreach($arr as $i=>$v){
    //echo "ddd $i $v";
    if (($i==$dv)||($v==$dv)){
     $sx="SELECTED";
    }else{
    $sx="";
    }
    $msx.="<OPTION value=\"".$i."\" $sx>".$i."(".$v.")</OPTION>";
   }
  }
  $msx.="</SELECT>";
  return $msx;
}

function makeidselectoptionsarraysimple_sized($arr,$dv,$sza,$all=null){
  $cao=is_null($all)?"Choose an option":$all;
  $msx.="<OPTION  class=lgtextstyle value=\"Choose an option\">$cao</OPTION>";
  if($_SESSION["selectadds"]){
   if(!($GLOBALS["blocknewoffer"])){
  $msx.="<OPTION  class=lgtextstyle value=\"<--Add new-->\"><--Add new--></OPTION>";
   }
  }
  $i=1;
  if (isset($arr)){
   foreach($arr as $i=>$v){
   	$sz=$sza[$i];
    if ((($i==$dv)&&($i>0))||($v==$dv)){
     //echo "match i:$i v:$v dv:$dv";
     $msx.="<OPTION value=\"".$i."\" SELECTED>".$v."</OPTION>";
     }else{
     $msx.="<OPTION value=\"".$i."\">".$v."($sz) </OPTION>";
    }
   }
  }
  $msx.="</SELECT>";
  return $msx;
}


function makeselectoptionsfulluser($sqlx,$dv,$fieldtofind){
  //$dv usually in edit mode from record.
  //could also be from session variable on tableid;
  if($dv==""){
   $dv=$_SESSION["claim"][$fieldtofind];
  }
  $result = mysql_query($sqlx);
  if(!$result) error_message(sql_error());
  $msx.="<OPTION  class=lgtextstyle>Choose an option</OPTION>";
  if($_SESSION["selectadds"]){
  //$msx.="<OPTION  class=lgtextstyle value=\"<--Add new-->\"><--Add new--></OPTION>";
  }

  while($vqd = mysql_fetch_array($result)){
   $userid=strtolower($vqd["userid"]);
   $val=$vqd["firstname"];
   $val.=" ";
   $val.=$vqd["surname"];
   if ($fieldn=="pricegroupid"){
    $val=str_replace(" ","_",$val);
   }
   //echo "847 $userid $val";
   if (($val==$dv)||($userid==$dv)){
    $msx.="<OPTION value=\"".$userid."\" SELECTED>".$val."</OPTION>";
    }else{
    $msx.="<OPTION value=\"".$userid."\">".$val."</OPTION>";
   }
  }
  $msx.="</SELECT>";
  return $msx;
}


function nextprev($sid,$tableid,$url){
//echo "xxxx np $tableid $url sid=$sid" ;
//$mfx.="wtf ".$sid." - ".$tableid;
$rv=rpathinfo();
$lpos=strrpos($rv,"=");
$shortrv=substr($rv,1,$lpos);

if (isset($_SESSION["search"][$tableid])){
	 $new=$_SESSION["search"][$tableid];
	 $sz=sizeof($_SESSION["search"][$tableid]);
	 $thisindex=array_search($sid,$new);
	 $previd=urlencode($new[$thisindex-1]);
	 $nextid=urlencode($new[$thisindex+1]);

	 $hrefxp=$shortrv."$previd";
	 $hrefxn=$shortrv."$nextid";
//	echo "<BR>prev ".$hrefxp;
//	echo "<BR>next ".$hrefxn;

	 if ($previd!=""){
		 //$phref=$GLOBALS["thref"][$tableid].$previd;
		 $phref="../".$shortrv."$previd";
		 $mfx.=sdlink($phref,"left","previous");
	 }
	 $sz_lo=($sz-1);
	 $this_lo=($thisindex-1);
	 $mfx.=" &nbsp; $this_lo of $sz_lo &nbsp;";

	 if ($nextid!=""){
		 //$nhref=$GLOBALS["thref"][$tableid].$nextid;
		 $nhref="../".$shortrv."$nextid";

		 $mfx.=sdlink($nhref,"right","next");
		// $mfx.="<a href=$hrefxn>";
		// $mfx.="<img src=\"./images/nav/navright.gif\" border=0></a>";
	 }

	 for ($i=0;$i<20;$i++){
	  $mfx.="&nbsp;";
	 }
}
 //echo "returning $mfx";
 return $mfx;
}


function resultcheckboxes($result,$fxforval,$fxfordesc){
while($vqd = mysql_fetch_array($result)){
 $val=$vqd[$fxforval];
 $desc=$vqd[$fxfordesc];
 $bx.="<TR><TD class=formlabel>$desc</td>
 <td>
 <INPUT  class=formdata type=\"hidden\" name=\"cbx[$val]\" value=$val>
 <INPUT  class=formdata type=\"checkbox\" name=\"cb[$val]\" ".isboxchecked($val).">
 </TD></TR>";
}
 return $bx;
}

function formboxchoices($tableid,$fxforval,$fxfordesc){
//starts with general table query
$sqlx="select * from $tableid";
$result = mysql_query($sqlx);
if(!$result) error_message(sql_error());
$bx.=resultcheckboxes($result,$fxforval,$fxfordesc);
return $bx;
}

function isboxchecked($val){
// echo "cid-".$_SESSION["contactid"];
 if (isset($_SESSION["contactid"])){
  $rc=inmailgroup($_SESSION["contactid"],$val);
  if ($rc>0){
   $ibx="checked";
   return $ibx;
  }
 }
}

Function distinctfieldselect($ltable,$fieldn,$dv){
  //$dfs.="<BR>dfs";

  $ftcount=$GLOBALS["ftc"][$ltable];
  $sfr=getdistincttablecontents($ltable,$fieldn,$ftcount);
//  $dfs.="<TR width=100><TD class=formlabel>". $fieldn."</TD><TD>" ;
  $dfs.="<SELECT class=formdata  name=\"".$fieldn."\"> ";
  $dfs.="<OPTION  class=lgtextstyle>Choose an option</OPTION>";
//  $dfs.="<OPTION>".$dv."</OPTION>";

   while($sfd = mysql_fetch_array($sfr)){
    $c =$sfd[$fieldn];
    //using actual field for distinct searches
    if ($c==$dv){
     $dfs.="<OPTION value=\"$c\" SELECTED>$c </OPTION>";
     }else{
     $dfs.="<OPTION value=\"".$c."\">".$c." </OPTION>";
    }
   }
  $dfs.="
          </SELECT>";
  //$dfx.=" </TD></TR>";

  return $dfs;
}

Function distinctdatefieldselect($tableid,$fid,$dv,$ftcount){
  //$dfs.="<BR>dfs";
  //$ftcount=$GLOBALS["ftc"][$ltable];
  $sfr=getdistinctdatetablecontents($tableid,$fid,$ftcount);
  $dfs.="<SELECT class=formdata  name=\"".$fid."\"> ";
  $dfs.="<OPTION  class=lgtextstyle>Choose an option</OPTION>";
//  $dfs.="<OPTION>".$dv."</OPTION>";
/**/
   while($sfd = mysql_fetch_array($sfr)){
    $y=$sfd["y"];
    $m=$sfd["m"];
    $cv=$sfd["countv"];

    if($m<10){
     $m="0".$m;
    }
    $c =$y."-".$m;

    //using actual field for distinct searches
    if ($c==$dv){
     $dfs.="<OPTION value=\"$c\" SELECTED>$c</OPTION>";
     }else{
     $dfs.="<OPTION value=\"".$c."\">".$c;
     if($GLOBALS["countfilters"]){
       $dfs.=" ($cv)";
     }
     $dfs.="</OPTION>";

    }
   }
/**/
  $dfs.="
          </SELECT>";
  //$dfx.=" </TD></TR>";
  return $dfs;
}

function distinctdatefieldselectarray($arr,$tableid,$fid,$dv,$ftcount){
  //$dfs.="<BR>dfs";
  //$ftcount=$GLOBALS["ftc"][$ltable];
  $sfr=getdistinctdatetablecontents($tableid,$fid,$ftcount);
  $dfs.="<SELECT class=formdata  name=\"".$fid."\"> ";
  $dfs.="<OPTION  class=lgtextstyle>Choose an option</OPTION>";
//  $dfs.="<OPTION>".$dv."</OPTION>";
/**/
   if(isset($arr)){
    foreach($arr as $date=>$count){
//    echo "<br> ddd $date $count";
//    $y=date("Y",strtotime($date));
//    $m=date("m",strtotime($date));
//    if($m<10){
//     $m="0".$m;
//    }
//    $c =$y."-".$m;

    //using actual field for distinct searches
    if ($date==$dv){
     $dfs.="<OPTION value=\"$date\" SELECTED>$date</OPTION>";
     }else{
      $dfs.="<OPTION value=\"".$date."\">".$date;
      if($GLOBALS["countfilters"]){
        $dfs.=" ($count)";
      }
      $dfs.="</OPTION>";
    }
   }
  }
/**/
  $dfs.="
          </SELECT>";
  //$dfx.=" </TD></TR>";
  return $dfs;
}



function makedate_Form($fieldn,$fid="mods",$datastyle="formdata",$dv=null,$tix=null,$jsGuess="dateGuess",$postGuessCall=null,$clickx=null){
 //etest("mfd $fid");
 if($fid){
  $rfid=$fid;
 }else{
  $rfid="mods";
 }
 if(!isset($dv)){
 	$dv=date("Y-m-d");
 }
 if($dv>0){
 $dvfake=date("d/m/Y",strtotime($dv));
 }else{
 }
 $fakefn=$fieldn."fake";
 $ocx="onclick=\"displayDatePicker('$fakefn');\"";

 $x="<input class=formdata value=\"$dvfake\" name=\"".$fieldn."fake\"  id=\"".$fieldn."fake\"  $clickx type=\"text\" onChange=\"$jsGuess(document.$rfid.$fieldn,document.$rfid.".$fieldn."fake);$postGuessCall();\" $ocx $tix>
   <INPUT class=formdata value=\"$dv\" TYPE=\"hidden\" name=\"$fieldn\" id=\"$fieldn\" >";


 $zx="<input class=formdata value=\"$dvfake\" name=\"".$fieldn."fake\"  id=\"".$fieldn."fake\"  type=\"text\" onChange=\"ajdateGuess();\" $tix>
  <br>
  <INPUT class=formdata value=\"$dv\" TYPE=\"hidden\" name=\"$fieldn\" id=\"$fieldn\" >";

 //if($GLOBALS["pdate"]==2){
 tfw("mdf$fieldn.txt",$x,true);
 return $x;
 //}else{
 // return $GLOBALS["pdate"];
 //}
}


function makedate($fieldn,$fid,$datastyle,$dv,$inputok,$pop=true){
 if($fid){
  $rfid=$fid;
 }else{
  $rfid="mods";
 }
 if(!$inputok){
 $readonlyx="readonly";
 }
 if($dv>0){
 $dvfake=date("d/m/Y",strtotime($dv));
 }
 if(isset($GLOBALS["tix"])){
  $txx="tabindex=".$GLOBALS["tix"];
 }

 $GLOBALS["datefieldcount"]++;
  $mdx="
  	<table>
  	<tr>
  	<td>
        <INPUT  class=formdata TYPE=\"hidden\" Name=\"$fieldn\" VALUE=\"$dv\">
        <INPUT  class=formdata TYPE=\"text\" Name=\"".$fieldn."fake\" VALUE=\"$dvfake\" $readonlyx onChange=\"dateGuess(document.$rfid.$fieldn,document.$rfid.".$fieldn."fake);\" $txx>
        </td>";
 if($pop){
 $mdx.="
        <td>
        <A HREF=\"javascript:showCalendar(".$GLOBALS["datefieldcount"].");\">
           <IMG SRC=\"10Min200011cal.gif\"
           WIDTH=\"39\" HEIGHT=\"21\" ALT=\"Click here to select date\" BORDER=\"0\"></A>
          </TD>";
 }
 $mdx.="
         </tr>
         </table>";
 return $mdx;
}

 function maketime($fieldn,$inputok=1,$dv=1){
  if($fid){
   $rfid=$fid;
  }else{
   $rfid="mods";
  }
//  $dv=$this->dfv;
//  $dvfake=$this->dfv;
  $dv=$dv;
  $dvfake=$dv;
  //$mdx="<tr><td>";
  $mdx="
        <INPUT  class=formdata TYPE=\"hidden\" Name=\"$fieldn\" VALUE=\"$dv\">
        <INPUT  class=formdata TYPE=\"text\" Name=\"".$fieldn."fake\" VALUE=\"$dvfake\" $readonlyx onChange=\"timeGuess(document.$rfid.$fieldn,document.$rfid.".$fieldn."fake);\" >
        ";
  //$mdx.="</td></tr>";
  return $mdx;
 }

function makemasterselect($vt,$datastyle,$fieldn,$dv){
 switch ($vt){
  default:
  $sqlx="select * from $vt
  order by ".$vt."id";
  $fieldtofind=$vt."desc";
  break;
 }
  $msx="<SELECT class=".$datastyle." name=\"$fieldn\" ";
  $msx.=" onChange=\"loadbox2()\" ";
  $msx.=">";
  if ($_GET["mode"]=="new"){
   if(!$GLOBALS["sessinplay"]){
    $dv="normal";
   }
  }
  //echo $msx;
  $msx.=makeselectoptions($sqlx,$dv,$fieldtofind);
  return $msx;
}

function makemasteridselect($vt,$datastyle,$fieldn,$dv,$ja=null){
 switch ($vt){
  default:
  $sqlx="select * from $vt
  order by ".$vt."desc";
  $fieldtofind=$vt."desc";
  $fieldid=$vt."id";
  break;
 }
  if(isset($GLOBALS["tix"])){
   $txx="tabindex=".$GLOBALS["tix"];
  }
  $msx="<SELECT class=".$datastyle." name=\"$fieldn\" ";
  $msx.=" $txx $ja>";
  if ($_GET["mode"]=="new"){
   if(!$GLOBALS["sessinplay"]){
    $dv="normal";
   }
  }
  //$msx.=makeselectoptions($sqlx,$dv,$fieldtofind);
  $msx.=makeidselectoptions($sqlx,$dv,$fieldtofind,$fieldid);
  return $msx;
}

function makesubtselect($vt,$datastyle,$fieldn,$dv){
 switch ($vt){
  default:
  $sqlx="select * from $vt
  order by ".$vt."id";
  $fieldtofind=$vt."desc";
  break;
 }
  $msx="
  <input type=\"hidden\" name=\"$fieldn\" value=\"$dv\">
  <SELECT class=".$datastyle." name=\"$fieldn"."descx"."\" ";
  $msx.=" onChange=\"loadsubt()\";>
  ";
/*??
  $msx="
  <SELECT class=".$datastyle." name=\"$fieldn\" >";
*/
  if ($_GET["mode"]=="new"){
   $dv="normal";
  }
  //echo $msx;
  $msx.=makeselectoptions($sqlx,$dv,$fieldtofind);
  return $msx;
}


function gobox1($show){
$sqlx="select * from ".$GLOBALS["mastertab"];
//echo $sqlx;
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
 while($cqd = mysql_fetch_array($cresult)){
  $tv=$cqd[$GLOBALS["mastertab"]."desc"];
  $GLOBALS["mbox"][]=$tv;
  //echo "<BR>$tv";
 }
 //$gbx.="<select>";
 $arr=$GLOBALS["mbox"];
 $ftf=$GLOBALS["mastertab"]."desc";
 $gbx.=makeidselectoptionsarray($arr,$arrv,$dv,$ftf,$fieldid);
 if($show){
  echo $gbx;
 }else{
 gobox2();
 }
}

function gobox2(){
$sqlx="select * from ".$GLOBALS["subtab"]." as st
 left outer join ".$GLOBALS["mastertab"]." as mt
 on st.".$GLOBALS["masterid"]."=mt.".$GLOBALS["masterid"];

//echo $sqlx;
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
 while($cqd = mysql_fetch_array($cresult)){
  $sv=$cqd[$GLOBALS["subtab"]."desc"];
  $mv=$cqd[$GLOBALS["mastertab"]."desc"];
  $GLOBALS["sbox"][$mv][]=$sv;
 }
 //$gbx.="<select>";
 $arr=$GLOBALS["mbox"];
 $ftf=$GLOBALS["mastertab"]."desc";
 foreach($GLOBALS["mbox"] as $sa){
  $arr=$GLOBALS["sbox"][$sa];
  $ftf=$GLOBALS["subtab"]."desc";
  $gbx.="<select>";
  $gbx.=makeidselectoptionsarray($arr,$arrv,$dv,$ftf,$fieldid);
 }
// echo $gbx;
}

function makel2js(){
 foreach($GLOBALS["mbox"] as $ma){
  $max=str_replace(" ","_",$ma);
  $l2x.="var $max = new Array();\n";
  $i=0;
  if(isset($GLOBALS["sbox"][$ma])){
	  foreach($GLOBALS["sbox"][$ma] as $sa){
	   $l2x.=$max."[$i]=\"".$sa."\";\n";
	   $i++;
	  }
  }
 }
 return $l2x;
}

function getosize(){
 return "2";
}


function mastersubjs($mtable){
 $GLOBALS["mastertab"]=$mtable;
 $GLOBALS["subtab"]=$GLOBALS["mastertab"]."sub";
 $GLOBALS["masterid"]=$GLOBALS["mastertab"]."id";
 gobox1(false);

 $msjs="
<script language=\"JavaScript\">
var array1 = null ; \n
var array2 = null ; \n";
 $msjs.=makel2js();

 $msjs.="

function loadsubt(){
//alert('loading subt');
var subt; \n
var subt; \n
subt = document.mods.".$mtable."subdescx.value; \n
subti = document.mods.".$mtable."subdescx.selectedIndex; \n
subt=subt.replace(\" \",\"_\"); \n
subti=subti.replace(\" \",\"_\"); \n

//alert(subt);
//alert(subti);
document.mods.".$mtable."subdesc.value=subt;
//var subth = document.mods.".$mtable."sub.value; \n
//alert(subth);
}

function loadbox2(){
//alert('loading box2');
//clearing loop \n
var box2 = document.mods.".$mtable."subdescx.options; \n
for (i = 0; i < document.mods.".$mtable."subdescx.options.length; i++){ \n
box2[i].text = \"\"; \n
box2[i].value = \"\"; \n
}//ends clearing FOR loop \n


/*the suffix 'desc' may or may not be necessary depending on table name*/
//var choice =document.mods.".$mtable."desc.value \n
var choice =document.mods.".$mtable.".value \n
choice=choice.replace(\" \",\"_\"); \n

//alert(choice) \n
//array1 = TV; \n
array1 = eval(choice); \n


var box2 = document.mods.".$mtable."subdescx.options; \n

//clearing loop \n
for (i = 0; i < document.mods.".$mtable."subdescx.options.length; i++){ \n
 box2[i].text = \"\"; \n
 box2[i].value = \"\"; \n
}//ends clearing FOR loop \n

 document.mods.".$mtable."subdescx.value=array1[0];
 document.mods.".$mtable."sub.value=array1[0];
 for (i = 0; i < array1.length; i++){ \n
  box2[i + 0].text = array1[i]; \n
  box2[i + 0].value = array1[i]; \n
  //alert(array1[i]);
 } \n
 // ends populating FOR \n
}//ends population function \n

</script>

 ";
 return $msjs;
 //echo $msjs;
}

function listablefields($tableid){
 if(!isset($_SESSION["ulevel"])) {
 	$ulevel=0;
 }else{
 	$ulevel=$_SESSION["ulevel"];
 }
 if($ulevel=="") $ulevel=0;
 $ulevel=1;
 $sqlx="select * from tablename
 where tableid='$tableid'
 and listvieworder>0
 and (userlevel<=$ulevel or userlevel is null)
 order by listvieworder asc
 ";
tfw("llf.txt",$sqlx,true);
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 while($cqd = mysql_fetch_array($cresult)){
  $lf++;
  $fieldn=$cqd["fieldid"];
  $ft=$cqd["type"];
  if(substr($ft,0,4)=="date"){
   $GLOBALS["datefields"][]=$fieldn;
  }
  if(strpos($fieldn,"date")){
   $GLOBALS["datefields"][]=$fieldn;
  }
  if ($lf==1){
   $GLOBALS["orderby"]=$fieldn;
  }
  $fielddesc=$cqd["fielddesc"];
  if ($fielddesc==""){
   $fielddesc=$fieldn;
  }
  $GLOBALS["lfldid"][$fieldn]=$fieldn;
  $GLOBALS["lflddesc"][$fieldn]=$fielddesc;
  $GLOBALS["lfldid_pos"][$fieldn]=$lf;
  $tf[$fieldn]=$fielddesc;
 }
 return $tf;
}

function useablefields($tableid,$extra_conditions=false,$oby=null){
 $sqlx="select * from tablename
 where tableid='$tableid'
 and inuse='on'";
 if($extra_conditions){
 $sqlx.="$extra_conditions";
 }
 if($oby){
 	$sqlx.=$oby;
 }else{
 $sqlx.=" order by pos,colorder ";
 }
 #echo "uuu $sqlx";
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 while($cqd = mysql_fetch_array($cresult)){
  $lf++;
  $fieldn=$cqd["fieldid"];
  $ft=$cqd["type"];
  if(substr($ft,0,4)=="date"){
   $GLOBALS["datefields"][]=$fieldn;
  }
  if(strpos($fieldn,"date")){
   $GLOBALS["datefields"][]=$fieldn;
  }
  if ($lf==1){
   $GLOBALS["orderby"]=$fieldn;
  }
  $fielddesc=$cqd["fielddesc"];
  if ($fielddesc==""){
   $fielddesc=$fieldn;
  }
  $GLOBALS["lfldid"][$fieldn]=$fieldn;
  $GLOBALS["lflddesc"][$fieldn]=$fielddesc;
  $GLOBALS["lfldid_pos"][$fieldn]=$lf;
  $da[$fieldn]=$fielddesc;
 }
 return $da;
}

function findcompulsoryfields($tid){

 $sqlx="select * from tablename
 where tableid='$tid'
 and compulsory='on'
 and inuse='on'
 and userlevel<=".$_SESSION["ulevel"]."
 order by pos ";
//echo "<br>xxx $sqlx";
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 while($cqd = mysql_fetch_array($cresult)){
  $lf++;
  $fieldn=$cqd["fieldid"];
  $fieldl=$cqd["fielddesc"];
  if ($fieldl==""){
   $fieldl=$fieldn;
  }
  $GLOBALS["compulsory"][$fieldn]=$fieldn;
  $GLOBALS["labels"][$fieldn]=$fieldl;
 }
}

function findnumericfields($tid){
 $sqlx="select * from tablename
 where tableid='$tid'
 and inuse='on'
 and datatype='numeric'
 order by pos ";
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 while($cqd = mysql_fetch_array($cresult)){
  $lf++;
  $fieldn=$cqd["fieldid"];
  $fieldl=$cqd["fielddesc"];
  if ($fieldl==""){
   $fieldl=$fieldn;
  }
  $GLOBALS["numerics"][$fieldn]=$fieldn;
  $GLOBALS["labels"][$fieldn]=$fieldl;
 }
}

function finddatefields($tid){
 $sqlx="select * from tablename
 where tableid='$tid'
 and inuse='on'
 and type like 'date%'
 order by pos,colorder ";
 //echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 while($cqd = mysql_fetch_array($cresult)){
  $lf++;
  $fieldn=$cqd["fieldid"];
  $fieldl=$cqd["fielddesc"];
  if ($fieldl==""){
   $fieldl=$fieldn;
  }
  $GLOBALS["datefc"][$lf]=$lf;
  $GLOBALS["datefn"][$lf]=$fieldn;
  $GLOBALS["datel"][$lf]=$fieldl;
 }
}

function findnonzerosfields($tid){
 $sqlx="select * from tablename
 where tableid='$tid'
 and inuse='on'
 and datatype='non-zero'
 order by pos ";
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 while($cqd = mysql_fetch_array($cresult)){
  $lf++;
  $fieldn=$cqd["fieldid"];
  $fieldl=$cqd["fielddesc"];
  if ($fieldl==""){
   $fieldl=$fieldn;
  }
  $GLOBALS["non-zeros"][$fieldn]=$fieldn;
  $GLOBALS["labels"][$fieldn]=$fieldl;
 }
}

function pagedisplay(){
 if(isset($_GET["pn"])){
  $tpc=$_GET["pn"];
 }else{
  $tpc=1;
 }

 $pcount=ceil($GLOBALS["rcount"]/$GLOBALS["lpp"]);
 if($pcount>1){
 $pdx.="<TR><td colspan=16>".$GLOBALS["rcount"]." records  Page ";
 for ($i=1;$i<$pcount+1;$i++){
      $pnc="pgnumber";
      if(isset($_GET["pn"])){
       if($_GET["pn"]==$i){
       $pnc="pgnumberselected";
       }
      }

  $hrefx=rpathinfo()."&pn=$i";
  $pos=strpos($hrefx,"&pn=");
//  echo "<br>$hrefx found pn= at".$pos;
  $usebit=substr($hrefx,0,$pos);
  $hrefx=$usebit."&pn=$i";
  $pdx.="&nbsp;"."<a class=$pnc href=$hrefx>".$i."</a>&nbsp";
 }
 $pdx.="</td></tr>";
 }
 //echo "look at page $tpc ";
 $startrow=1+($GLOBALS["lpp"]*($tpc-1));
 $endrow=$startrow+$GLOBALS["lpp"];
 //echo "start at row $startrow ";
  for ($pr=$startrow;$pr<$endrow;$pr++){
   $pdx.=$GLOBALS["listdata"][$pr];
  }


 echo $pdx;
}

function loadbranchfunctions($fieldn,$av){
 //step 1. create array for all active fields in the condition
 // 		only loads if branch conditions exist
 //step 2. create text functions using active fields,
 //also searching arrays for alternate conditions
 //        to switch off
 //echo "<br>create $fieldn for $av";
 $sqlx="select * from tablename where tableid='".$GLOBALS["table"]."'
 and branchif='$av'";
 //echo "<br> $sqlx";
  $result = mysql_query($sqlx);
  if(!$result) error_message(sql_error());
  while($vqd = mysql_fetch_array($result)){
   $bfield=$vqd["fieldid"];
   //echo "<br> $fieldn $av $bfield";
   $GLOBALS["radiocalls"][$fieldn][$av]=$av;
   $GLOBALS["radiocallsboth"][$fieldn][$bfield]=$bfield;
   $GLOBALS["radioactivate"][$fieldn][$av][$bfield]=$bfield;
  }
}

function createbranchfunctions(){
 $fx="\n<script>";
 if(isset($GLOBALS["radiofield"])){
 	 foreach($GLOBALS["radiofield"] as $fieldn){
 		  if(isset($GLOBALS["radiocalls"][$fieldn])){
 		  	foreach($GLOBALS["radiocalls"][$fieldn] as $av){
		  		$fx.="\nfunction $av() { \n";
 		  	 	$tempa=$GLOBALS["radiocallsboth"][$fieldn];
	  	 		foreach($tempa as $b){
 		 		  	 //echo "<br>all $fieldn - $b";
	  	 		}
 		  	 	if(isset($GLOBALS["radioactivate"][$fieldn][$av])){
 		  	 		foreach($GLOBALS["radioactivate"][$fieldn][$av] as $p){
		 		  	 //echo "<br>pos $av - $p";
		 		  	 $fx.="document.mods.$p.disabled=false; \n";
		 		  	 $fx.="document.mods.$p.style.backgroundColor='white'; \n";
		 		  	 unset($tempa[$p]);
 		  	 		}
 		  	 	}
				//whats left?
	  	 		foreach($tempa as $b){
 		 		  	 //echo "<br>negs $fieldn - $b";
		 		  	 $fx.="document.mods.$b.disabled=true; \n";
		 		  	 $fx.="document.mods.$b.style.backgroundColor='CCCCCC'; \n";
		 		  	 $fx.="document.mods.$b.value=''; \n";
	  	 		}
		  		$fx.="}\n";
 		  	}
 		  }
   	 }
 }
 $fx.="</script>\n";
 $GLOBALS["branchfx"]=$fx;
}


function includedfunctions($table){
 $js="\n<script>";
 $sqlx="select * from tablesecurity
 where tableid='$table'";
 $cresult = mysql_query($sqlx);
 if ($qd = mysql_fetch_array($cresult)){
  $js.=stripslashes($qd["jscript"]);
 }
 $js.="\n</script>";
 return $js;
}

function tsecurity($table,$el){
 $sqlx="select * from tablesecurity
 where tableid='$table'
 and primid<>''
 ";
 $sresult = mysql_query($sqlx);
 if(!$sresult) error_message(sql_error());
 $rc=mysql_num_rows($sresult);
 if($rc>0){
	 if($sqd = mysql_fetch_array($sresult)){
	 $rel=$sqd[$el];
 	 return $rel;
	 }
 }
}

function levelok($ul){
// echo "<br>ccc comparing ".$_SESSION["ulevel"]." v $ul";
 if($_SESSION["ulevel"]>=$ul){
  return true;
 }
}

function inputcustfield($custid){
 if(isset($_SESSION["appt"]["custid"])){
  $href="custdetail.php?tab=1&customerid=$custid";
  $tpx.=slink($href,"v","view");
 }
 if(isset($_SESSION["appt"]["custid"])){
 	$custid=$_SESSION["appt"]["custid"];
 	$cname=$_SESSION["appt"]["cname"];
 }
 if(isset($_SESSION["phonecall"]["custid"])){
 	$custid=$_SESSION["phonecall"]["custid"];
 	$cname=$_SESSION["phonecall"]["cname"];
	//echo "<br>cccc session name retrieval $cname $custid";
 }
 //too unreliable on sessions.
 if(isset($GLOBALS["custid"])){
  $custid=$GLOBALS["custid"];
  $cname=returncompany($custid);
  //echo "<br>cccc global name retrieval $cname $custid";
 }
$tpx.="<!--TR-->
      <!--TD class=formLabel colspan=>Customer ID:</td>
      <td colspan=2-->
      <INPUT class=\"formdata\" TYPE=\"text\" Name=\"CustName\" value=\"$cname\" size=35 onkeydown=\"return mcustlookup(event)\";>
      <INPUT TYPE=\"hidden\" Name=\"customerid\" VALUE=\"$custid\">
        <A HREF=\"javascript:showPerson(2);\">
          <IMG SRC=\"images/lup.gif\"
          ALT=\"select customer\" BORDER=\"0\"></A>
      </TD>

          </TD>
        <!--/TR-->";
$tpx.="<td class=\"crumb4\">
<button class=\"formdata\" name=\"newcustbutto\" onClick=\"testfornewcust(this,'customer');\">New Customer</button>
</td>";

//echo "inhere $tpx";
return $tpx;
}



function infdata_array($table,$keyf=null,$keyv=null){
 useablefields($table);
 if(isset($GLOBALS["altlabels"])){
  $GLOBALS["lflddesc"]=$GLOBALS["altlabels"];
 }
 unset($GLOBALS["altlabels"]);
 $tfields=$GLOBALS["lflddesc"];

/*good bit from newtech - take to infdemo*/
 /**/
 if(isset($GLOBALS["lfldid_pos"])){
  //echo "adddddd $detailidf";
  if(isset($GLOBALS["lfldid_pos"][$detailidf])){
   $GLOBALS["lfldid_pos"][$detailidf]="Ref";
  }
  asort($GLOBALS["lfldid_pos"]);
 //ksort($GLOBALS["lfldid_pos"]);
  reset($GLOBALS["lfldid_pos"]);
  $tfields=$GLOBALS["lfldid_pos"];
 //$tfields=array_flip($tfields);
 }
/**/

  $sqlx="select * from $table";
  if(isset($keyv)){
  $sqlx.=" where $keyf='$keyv'";
  }
 //echo "<br> qqq $sqlx";
 $tresult = mysql_query($sqlx);
 //if(!$tresult) error_message(sql_error());
 while($cqd = mysql_fetch_array($tresult)){
  $r++;
  $primid=$cqd[$keyf];
  foreach($tfields as $fid=>$cpos){
   $tv=$cqd[$fid];
   //loading by field name OR fid
   $rd[$primid][$fid]=$tv;
//   echo "<br>ffff loading line prim: $primid $r $fid  = $tv";
  }
 }
 $fulld=array($tfields,$rd);
 return $fulld;
}


function infdata($table,$keyf,$keyv){
 listablefields($table);
 if(isset($GLOBALS["altlabels"])){
  $GLOBALS["lflddesc"]=$GLOBALS["altlabels"];
 }
 unset($GLOBALS["altlabels"]);
 $tfields=$GLOBALS["lflddesc"];

  $el="editurl";
  $eref=tsecurity($table,$el);
  $el="detailurl";
  $vref=tsecurity($table,$el);
  $el="primid";
  $prim_key=tsecurity($table,$el);
  if($prim_key!=""){
   $detailidf=$prim_key;
  }else{
   $detailidf=$keyf;
  }

/*good bit from newtech - take to infdemo*/
 /**/
 if(isset($GLOBALS["lfldid_pos"])){
  //echo "adddddd $detailidf";
  if(isset($GLOBALS["lfldid_pos"][$detailidf])){
   $GLOBALS["lfldid_pos"][$detailidf]="Ref";
  }
  asort($GLOBALS["lfldid_pos"]);
 //ksort($GLOBALS["lfldid_pos"]);
  reset($GLOBALS["lfldid_pos"]);
  $tfields=$GLOBALS["lfldid_pos"];
 //$tfields=array_flip($tfields);
 }
/**/

 if(isset($GLOBALS["infdquery"])){
 	$sqlx=$GLOBALS["infdquery"];
 }else{
 	$sqlx="select * from $table
 	where $keyf='$keyv'";
 }
// echo "<br> qqq $sqlx";
 $tresult = mysql_query($sqlx);
 //if(!$tresult) error_message(sql_error());
 while($cqd = mysql_fetch_array($tresult)){
  $r++;
  foreach($tfields as $fid=>$cpos){
   $tv=$cqd[$fid];
   //loading by field name OR fid
   $rd[$r][$fid]=$tv;
  //$px.="<br>ffff loading line $fn $r $fid  = $tv";
  }
 }
 $el="editurl";
 $editref=tsecurity($table,$el);
 $el="detailurl";
 $viewref=tsecurity($table,$el);
 $el="primid";
 $prim_key=tsecurity($table,$el);

 $vmode="v";
 if($GLOBALS["vpopmode"]){
  $vmode="vp";
 }
 if($eref){
  if($vref!=""){
   $eref=array($eref=>"e",$vref=>$vmode);
  }
 }
 if(killok($table)){
  $el="killurl";
  $kurl=tsecurity($table,$el);
  	if(strpos($kurl,"php")){
  		$GLOBALS["kref"]=$kurl;
  	}
 }

 if($editref){
  if($viewref!=""){
   $eref=array($editref=>"e",$viewref=>"v");
  }
 }
 $el="newurl";
 $newref=tsecurity($table,$el);

 if($prim_key!=""){
  $detailidf=$prim_key;
 }else{
  $detailidf=$keyf;
 }

 if($GLOBALS["loglinks"]){
 	$eref=$GLOBALS["loglinks"];
 }
 //echo "ppppkey $table $detailidf";
 $px.=inftable($tfields,$rd,$r,$eref,$detailidf,null,$newref,$vref);
// $px.=inftable($tfields,$rd,$r,$eref,$detailidf);
 return $px;
}


function supplierOptions(){
	$res=readsuppliers();
	#$da = mysql_fetch_array($res);

	while($da = mysql_fetch_array($res)){
		$id=$da["customerid"];
		$name=$da["companyname"];
		$sa[$id]=$name;
	}
	return $sa;
}

function infqueryarray_pair($sqlx,$tfields){
 $tresult = mysql_query($sqlx);
 $fid=$tfields[0];
 $fn=$tfields[1];

 if(!$tresult) tfw("sqlPairerror.txt",sql_error(),true);

 while($cqd = mysql_fetch_array($tresult)){
  $r++;
  $tidv=$cqd[$fid];
  $tv=$cqd[$fn];
  if($r==1){
   $GLOBALS["firstval"]=$tv;
  }
  $rd[$tidv]=$tv;
  //etest("lll $r loading line $fid:$fn = :$tidv  = $tv");
  //etest("lll $r loading line $fid:$fn = :$tidv  = $tv");
 }
 return $rd;
}

function infqueryarray($sqlx,$tfields,$keyf=null,$tablen=null){
 $tresult = mysql_query($sqlx);
  if(!$tresult){
  	//$em=sql_error();
  	$em=mysql_error();
  	$errx=$sqlx." - ".$em;
  	tfw("errorview.txt",$errx,true);
  }

  if(isset($tablen)){
 	if(!isset($tfields)){
 		$tfields=key2val(totalfieldarray($tablen));
 	}
 }
 if(!$tresult){
 	tfw("mqview.txt",$sqlx,false);
 	return null;
 }
 tfw("dmqview.txt",$sqlx,false);
 if(sizeof($tresult)==0) return null;

   $ukey=false;
   if(isset($keyf)) $ukey=true;

 $r=0; //ANK
 while($cqd = mysql_fetch_array($tresult)){
  $r++;
  if(isset($tfields)){
	  foreach($tfields as $fid=>$fn){
	   $tv=trim($cqd[$fid]);
	   #$tv=stripslashes($tv);
		#$tv=str_replace('\\','_slash_',$tv);
		$tv=str_replace('\\','\\\\',$tv);

	   if($ukey){
	    $kf=$cqd[$keyf];
	   }else{
	    $kf=$r;
	   }
	   if(array_key_exists("notags",$GLOBALS)) {
	   	 $tv = strip_tags($tv);
	   }
	   #if($GLOBALS["notags"])  $tv=strip_tags($tv);
	   $rd[$kf][$fid]=$tv;
	   #$rax.=";$kf $fid = $tv";
	  #echo "alert('lll $r loading line $kf $fid:$fn = $tv')\n";
	  }
  }
 }
 #tfw("iqa.txt",$rax,false);
 return $rd;
}

function iqaRes($tresult=null,$tfields=null){
 while($cqd = mysql_fetch_array($tresult)){
  $r++;
  if(isset($tfields)){
	  foreach($tfields as $fid=>$fn){
	   $tv=trim($cqd[$fid]);
	   #$tv=stripslashes($tv);
		#$tv=str_replace('\\','_slash_',$tv);
		$tv=str_replace('\\','\\\\',$tv);

	   if($ukey){
	    $kf=$cqd[$keyf];
	   }else{
	    $kf=$r;
	   }
	   if($GLOBALS["notags"])  $tv=strip_tags($tv);
	   $rd[$kf][$fid]=$tv;
	   $rax.=";$kf $fid = $tv";
	  #echo "alert('lll $r loading line $kf $fid:$fn = $tv')\n";
	  }
  }
 }
 tfw("iqa.txt",$rax,false);
 return $rd;

}

function infqueryarray_singlerow($sqlx,$tfields){
 //echo "qqq $sqlx";
 $tresult = mysql_query($sqlx);
 if($cqd = mysql_fetch_array($tresult)){
  foreach($tfields as $fid=>$fn){
   $tv=$cqd[$fid];
   $rd[$fid]=$tv;
 //  echo "<br> lll $r loading line $fn = $tv";
  }
 }
 return $rd;
}

function infqueryarray_forkeylist($sqlx,$keyf,$tfields){
 $tresult = mysql_query($sqlx);
 while($cqd = mysql_fetch_array($tresult)){
  $r++;
  unset ($descx);
  $keyx=$cqd[$keyf];
  $f=0;
  foreach($tfields as $fid=>$fn){
   $f++;
   //if($fid!=$keyf){
   if($f>1){
    $descx.=" (";
   }
   $descx.=$cqd[$fid];
 	 //echo "<br> lll $r loading line $fid:$fn = $tv";
   //}
   if($f>1){
    $descx.=")";
   }
  }
  $rd[$keyx]=$descx;
 }
 return $rd;
}

function infquerydata($sqlx,$tfields,$usetips=false){
 //echo "ii $sqlx";
 $rd=infqueryarray($sqlx,$tfields);
 $GLOBALS["iqda"]=$rd;
 $r=sizeof($rd);
 if(isset($GLOBALS["detailidf"])){
  $detailidf=$GLOBALS["detailidf"];
  }else{
  $detailidf=null;
 }
 $newref=null;
 if(isset($GLOBALS["loglinks"])){
 	$eref=$GLOBALS["loglinks"];
 	}else{$eref=null;
 }
 $vref=null;
 if($usetips){
  echo "using tips 1b";
 }

 $px.=inftable($tfields,$rd,$r,$eref,$detailidf,null,$newref,$vref,$usetips);
 return $px;
}


function inftable($tfields,$rd,$r=null,$eref=null,$detailidf=null,$tline=null,$newref=null,$vref=null,$usetips=false){
 //$usetips=true;
 if($usetips){
  //echo "using tips 1c";
 }
 if(!isset($r)){
  $r=sizeof($rd);
 }


 $rowstyle[0]="lgtextstyle";
 $rowstyle[1]="lstextstyle";

 if(isset($GLOBALS["altstyles"])){
  $rowstyle=$GLOBALS["altstyles"];
 }

 $tog=0;
 $tformatx=$GLOBALS["table_formatx"];
 if(!$GLOBALS["tBodyOnly"]){
 $ix.="<table $tformatx>";
 }
  if($newref){
   if($newref=="jsnew"){
	//echo "jjj $newref";
    $stable=$_GET["st"];
    if($stable<>""){
    	$jref="testfornew('$stable');";
    	$linx=jbutton($jref,"e","add new");
    }
   }else{
    $linx=sdlink($newref,"e","add new");
   }
      if(isset($GLOBALS["extratoplink"])){
       foreach($GLOBALS["extratoplink"] as $ek=>$ev){
       	$jref="window.open('$ek')";
       	$linx.=jbutton($jref,"e",$ev);
       }
      }

   $ix.="<tr><td>$linx Add new</td></tr>";
  }
 if(isset($GLOBALS["ttitle"])){
  $ix.=$GLOBALS["ttitle"];
 }


 //if($newref){
 //$ix.="<tr><td>".sdlink($newref,"e","add new")."Add new</td></tr>";
 //}
 $colheadx.="
 <tr class=tableheadbar>";
 if($eref){
  //this goes to whether links in separate columns
  if($GLOBALS["sparehead"]){
  $colheadx.="<td>x</td>";
  }
 }


 if(isset($tfields)){
 	foreach($tfields as $tfn=>$tfx){
 	  if(isset($GLOBALS["coldisplay_suppress"])){
 	  	if (!in_array($tfn, $GLOBALS["coldisplay_suppress"])){
	 	 if(($tfx!="")&&($tfx!="Choose an option")){
	 	 $col="<td>$tfx</td>";
	  	 }
	  	}
	  }else{
	 	 if(($tfx!="")&&($tfx!="Choose an option")){
	 	 	$tdesc=$tfx;
	 	 	if($GLOBALS["lflddesc"][$tfn]!=""){
		 	 	$tdesc=$GLOBALS["lflddesc"][$tfn];
	 	 	}
	 	 	$col="<th>$tdesc</th>";
	 	 }
	  }

	  /*auto detect dates*/
	    if(substr($tfx,0,4)=="date"){
	     $GLOBALS["datefields"][]=$tfn;
	    }
	    if(strpos($tfx,"date")){
	     $GLOBALS["datefields"][]=$tfn;
	    }

	    if(substr($tfn,0,4)=="date"){
	     $GLOBALS["datefields"][]=$tfn;
	    }
	    if(strpos($tfn,"date")){
	     $GLOBALS["datefields"][]=$tfn;
	    }



	  $col=makesortable($col,$tfn,$tfx);
	  $colheadx.=$col;
 	}
 }
 $colheadx.="</tr>";
 if($GLOBALS["tBodyOnly"]){
  $colheadx="";
 }
 $ix.=$colheadx;
 $GLOBALS["colheads"]=$colheadx;
 for($i=0;$i<=$r;$i++){
  unset($nwx);
  if($i==1){
   $nwx="nowrap";
  }
  $tog=!$tog;
  $style=$rowstyle[$tog];

  if(isset($GLOBALS["group_totals"])){
   foreach($GLOBALS["group_totals"] as $breakfn=>$breakfv){
    if($i>=0){
//   	echo "<br>$breakfn $breakfv $i";
   	$breakv=$rd[$i][$breakfn];
   	$subtval=$rd[$i][$breakfv];
   	$ix.=testforbreak($breakv,$subtval);
    }
   }
  }
    if(isset($GLOBALS["total_fields"])){
    	foreach($GLOBALS["total_fields"] as $k=>$tfn){
    	 $tv=$rd[$i][$tfn];
    	 //echo "<br> adding tot: $k $tfn ; v: $tv";
    	 $GLOBALS["grandtotal"][$tfn]+=$tv;
    	}
    }

 /*start of line*/
//  echo "<br> pre started $i";
  if(isset($rd[$i])){
  $ix.="<tr class=$style>";
  if($eref){
  	 if(is_array($eref)){
  	  $ix.="<td $nwx>";
  	  foreach($eref as $el=>$et){
  		   $el.=urlencode($rd[$i][$detailidf]);
		  	 if(isset($GLOBALS["e_extra"])){
		  	  foreach($GLOBALS["e_extra"] as $prefix=>$fname){
			    //echo "<br>$el $et llooking $prefix $fname";
		  	  $el.="&$prefix=".urlencode($rd[$i][$fname]);
		  	  }
		  	 }
		     if(strpos($el,"avascript:")){
	    		/*must close the brackets, can't store it in inftables*/
	    		$el.="')";
		     }


		   //echo "<br>iiii $detailidf $el et: $et id:".$rd[$i][$detailidf] ;
  		   unset($linkx);
  		   switch($et){
  		    case "e":
  		    $linkx=slink($el,$et,"edit");
  		    break;
  		    case "pdf":
  		    $linkx=slink($el,$et,"create pdf");
  		    break;
  		    case "v":
  		    $linkx=sdlink($el,$et,"view");
  		    break;
  		    case "vp":
  		    $linkx=slink($el,"v","view");
  		    break;
  		    case "vnw":
  		    $et="v";
  		    $linkx=slink($el,$et,"view");
  		    break;
  		    case "p":
  		    $linkx=slink($el,$et,"print");
  		    break;
  		    case "d":
  		    $linkx.=klink($el,$et,"delete");
  		    break;
  		    case "right_sd":
  		    $linkx=sdlink($el,$et,"right");
  		    break;
  		    case "dp":
  		    $linkx=kplink($el,$et,"delete");
  		    break;
  		    case "jep":
		    $linkx=jwlaunch($el,"e","edit",600,700);
		    //$ix.=jslink($el,$et,"edit");
  		    break;
  		    case "pdf":
  		    $linkx=slink($el,$et,"create pdf");
  		    break;
  		    case "fiscal":
  		     $dtcheck=$rd[$i][$GLOBALS["checkdate"]];
  		    if(dateok($dtcheck)){
  		    $linkx=slink($el,"e","edit");
  		    }
  		    break;
			case "condv":
  		    //$linkx=slink($el,$et,"view");
			$condv=$rd[$i][$GLOBALS["condv"]["condf"]];
				//etest("cccc $condv");
			if($condv>0){
  		    	$linkx=sdlink($el,"v","view");
  		    }

			break;
  		   }

  		   $ix.=$linkx;
  	  }
	  $detailv=$rd[$i][$detailidf];
	 	if(isset($GLOBALS["datefields"])){
	 		if (in_array($detailidf, $GLOBALS["datefields"])){
			  if($detailv!=0){
			  	$detailv=date("d M Y", strtotime($detailv));
			  }
			  $nwx=" nowrap";
			  }else{
			  $detailv=xsb($detailv);
			}
		}
	  if(isset($GLOBALS["coldisplay_suppress"])){
	   if (in_array($detailidf, $GLOBALS["coldisplay_suppress"])){
	    $detailv="";
	   }
	  }
	  $ix.=$detailv."</td>";
  	 }else{
  	 $vref=$eref.urlencode($rd[$i][$detailidf]);
  	 if(isset($GLOBALS["e_extra"])){
  	  foreach($GLOBALS["e_extra"] as $prefix=>$fname){
  	   $vref.="&$prefix=".urlencode($rd[$i][$fname]);
  	  }
  	 }
  	 $ix.="<td $nwx>".sdlink($vref,"v","view this")."</td>";
  	 }
  }
  $pass=0;
  if(isset($rd[$i])){
  	foreach($rd[$i] as $lfn=>$cellv){
	  $colc++;
	  unset($nwx);
	  unset($tiplink);
	  if($colc==1){
	   $nwx="nowrap";
	  }
  	 if($lfn=="contactid"){
	 	 if($GLOBALS["replace_cont_with_name"]){
	 		$cellv=returncontact($cellv);
	 	 }
	 }

  	 if(($lfn!=$detailidf)||(is_null($detailidf))){
 		unset($alx);
	  	$alx="valign=top ";
 		if(is_numeric($cellv)){
 		 //can be exceptions
 		 if(isset($GLOBALS["textonly"])){
 		 	if (!in_array($lfn, $GLOBALS["textonly"])){
 		 		$alx.="align=right";
 		 		$cellv=number_format($cellv,2);
 		 	}
 		 }else{
 		 		$alx.="align=right";
		 		$cellv=number_format($cellv,2);
 		 }
 		}
 		if(isset($GLOBALS["integers"])){
		 	if(is_array($GLOBALS["integers"])){
			 	if (in_array($lfn, $GLOBALS["integers"])){
					$cellv=number_format($cellv,0);
		 		}
		 	}
		}

	 	if(isset($GLOBALS["datefields"])){
		 	if(is_array($GLOBALS["datefields"])){
		 	if (in_array($lfn, $GLOBALS["datefields"])){
			  if($cellv!=0){
			  	$cellv=date("d M Y", strtotime($cellv));
			    $cellv=date("d M y", strtotime($cellv));
			  }
			  $nwx=" nowrap";
			  }else{
			  $cellv=xsb($cellv);
			}
			}
		}
		if(isset($GLOBALS["phonefields"])){
		 	if(is_array($GLOBALS["phonefields"])){
			if(in_array($lfn, $GLOBALS["phonefields"])){
			 $customerid=$rd[$i]["customerid"];
			 $contactid=$rd[$i]["contactid"];
			 //echo "<br> cccc:$i  $customerid cn: $contactid";
			 $cellv=newcall_link($customerid,$contactid).$cellv;
			}
			}
		}

		if($lfn=="companyname"){
		 if($usetips){
		 //echo "<br>uuuu tip $lfn";
		 $tt=new tooltip();
		 }

		 unset($tiplink);
		 if($rd[$i]["customerid"]!=""){
				if($usetips){
					$tt->load_paneltips("customer",$rd[$i]["customerid"]);
					$tt->display_searchlinks();
			            	if($tt->dst){
				            	$tiplink=$tt->dst;
							 //echo "<br>ttttuuuu tip $lfn tl:$tiplink";
			            	}
			        }
		        $rowx.=slinkw($hrefx,"v","view $tableid");
		 	$cellv="<a class=greylinks href=\"custdetail.php?customerid=".$rd[$i]["customerid"]."\">".$cellv."</a>";


		 }
		}
		if(isset($GLOBALS["markups"])){
		   if (in_array($lfn, $GLOBALS["markups"])){
		//    echo "mmmmmup $lfn";
		   }
		}
		 /*cell detail displays here*/
//		 if(isset($GLOBALS["coldisplay_suppress"])){
//		   if (!in_array($lfn, $GLOBALS["coldisplay_suppress"])){
// 			$ix.="<td class=$style  $nwx $alx>$tiplink".xsb($cellv)."</td>";
//		   }
//		 }
//		 else{
	 		$use_td=true;
		 	if($lfn!=""){
 			 if(isset($GLOBALS["inputfields"])){
		 		 if((array_key_exists($lfn,$GLOBALS["inputfields"]))&&(!$GLOBALS["inputlinesuppress"][$lfn][$i])){
		 		  $ftype=$GLOBALS["inputfields"][$lfn];
		 		  if(isset($GLOBALS["inputfieldsize"][$lfn])){
		 		  $fsize=$GLOBALS["inputfieldsize"][$lfn];
		 		  }else{
		 		   $fsize=5;
		 		  }
		 		  $use_td=true;
		 		  if($ftype=="hidden"){
		 	 		  $use_td=false;
	   	 		  }

				  switch($ftype){
		 		   case "textarea":
		 		   $icellx="<textarea class=formdata name=\"$lfn"."[$i]"."\"  rows=$fsize[0] cols=$fsize[1]>$cellv</textarea>";
					break;
		 		   case "showhidden":
		 		   $icellx="<input type=hidden class=formdata name=\"$lfn"."[$i]"."\" value=\"$cellv\" size=\"$fsize\">$cellv";
					break;
		 		   case "checbox":
		 		   unset($cboxck);
		 		   if($cellv){
		 		    $cboxck="checked";
		 		   }
		 		   $icellx="<input type=checkbox class=formdata name=\"$lfn"."[$i]"."\" value=\"$cellv\" $cboxck>";
					break;

		 		   default:
		 		   $icellx="<input type=$ftype class=formdata name=\"$lfn"."[$i]"."\" value=\"$cellv\" size=\"$fsize\">";
		 		   break;
		 		  }
		 		 }else{
		 		 $icellx=xsb($cellv);
		 		 }
		 	 }else
		 	 //ie. inputfields not set.
		 	 {
		 		 $icellx=xsb($cellv);
		 	 }
		 	  	//echo "<br>test mrh $lfn xx";
		 	 if(isset($GLOBALS["midrowhref"][$lfn])){
		 	  	//echo "<br> mrh $lfn";
		 	  	$urla=$GLOBALS["midrowhref"][$lfn];
		 	  	$urlx=$urla[0].urlencode($rd[$i][$urla[1]]);
		 	  	$icellx="<a class=greylinks href=$urlx>$icellx</a>";
		 	 }
		 	 if(isset($GLOBALS["functiontip"][$lfn])){
		 	    $funca=$GLOBALS["functiontip"][$lfn];
		 	    $tipa=$funca[0];
		 	  	$tipappend=urlencode($rd[$i][$funca[1]]);
				//echo "<br>taa $tipappend fl".$funca[1]." fv:".$rd[$i]["phonecallid"];
				$tt->create_functionlinks($tipa,$tipappend);
				$tt->open_functionlinks();
			   	if($tt->ofl){
				         $open_tiplink=$tt->ofl;

						 //echo "<br>ttttuuuu tip $lfn tl:$tiplink";
			   	}
		 	 }

			 //echo "<br>tttt333333 tip $lfn tl:$tiplink";


		 	$display_ok=true;
		 	if(isset($GLOBALS["coldisplay_suppress"])){
		 	  if (in_array($lfn, $GLOBALS["coldisplay_suppress"])){
		 	   $display_ok=false;
		 	  }
		 	}
		 	if($display_ok){
			 	 if($open_tiplink){
			 	  $tiplink=$open_tiplink;
			 	 }
		 		 if($use_td){
 				 	$ix.="<td class=$style  $nwx $alx>$tiplink";
 				 }
				 $ix.=$icellx;
			 	 if($open_tiplink){
			 	  $ix.="</a>";
			 	  unset($open_tiplink);
			 	 }

		 	 	 if($use_td){
		 	 		$ix.="</td>";
		 	 	 }
		 	  }
		 	}
//		 }
  	 }
  	}
  }
   if(isset($GLOBALS["right_actionlink"])){
   	$rx=$GLOBALS["right_actionlink"][0];
   	$rx.=$rd[$i][$GLOBALS["right_actionlink"][1]]."'";
   	$ix.="<td>".jbutton($rx,"b","close")."</td>";
   }
 if(isset($GLOBALS["kref"])){
  $kurl=$GLOBALS["kref"];
  $kurl.=$rd[$i][$detailidf];
  $kref=kplink($kurl,"dp","remove");
  $ix.="<td>$kref</td>";
 }
 $ix.="</tr>";
 }
 /*end of data test*/
 }
 if($tline){
  $ix.=$tline;
 }
 if(!$GLOBALS["suppress_totals"]){
   $ix.=grandtotal();
 }

 if(!$GLOBALS["tBodyOnly"]){
 $ix.="</table>";
 }
 return $ix;
}

function arrdatescript($formx,$da){
  $dsx.="<script> \n";
  $dsx.="function showCalendar(i) { \n";
  $dsx.="  var tbm;\n";
  	foreach($da as $dc=>$dn){
  	 $dsx.="  if(i==$dc) tbm='$formx.$dn' ;\n";
  	}
  //$dsx.="alert(tbm) \n";
  $dsx.="window.open('calpop.php?tbm='+tbm,'ncal','width=220, height=220'); \n ";
  $dsx.="} \n";
 $dsx.="</script>";
 return $dsx;
}

function allownewadd($field){
 if($GLOBALS["totalblocknewoffer"]){
 	return false;
 }
 if(!$_SESSION["selectadds"]){
  if(!$GLOBALS["blocknewoffer"][$field]){
  	return true;
  }
 }
 return false;
}

function totalfieldarray($table,$prefix=null){
 if(!tableExists($table)) return false;

 if($table!=""){
 $sqlx="show fields from $table";
 tfw("tfax.txt",$sqlx,false);
 $result = mysql_query($sqlx);
 while($qd = mysql_fetch_array($result)){
  $fn=$qd["Field"];
  if($prefix){
  	$fn=$prefix.".$fn";
  	$fields[]=$fn;
  	}else{
  	$fields[]=$fn;
  }
 }
 return $fields;
 }
}

function ktfa($table,$prefix=null){
   $fa=key2val(totalfieldarray($table,$prefix));
   return $fa;
}

function tfa($table,$prefix=null){
	$fa=totalfieldarray($table,$prefix);
    return $fa;
}

function tinfo($t,$fn=null){
   $tf=key2val(array_values(totalfieldarray("tablename")));
   if($fn!="") $tf=kda($fn);
   $sqlx="select * from tablename where tableid='$t'";
   if( $t!=""){
	  $itfi=iqa($sqlx,$tf,"fieldid");
   }
   return $itfi;
}

function tex($tn){
	if(tableExists($tn)) return true;
	return false;
}
function tableExists($tn){
	$q="show tables like '$tn'";
	if(qRowCount($q)>0) return true;
	return false;
}


function itfi($t){
   $tf=key2val(array_values(totalfieldarray("tablename")));
   $sqlx="select * from tablename where tableid='$t'";
   if( $t!=""){
   $itfi=infqueryarray_singlerow($sqlx,$tf);
   }
   return $itfi;
}

function tprimary($table){
 if($table!=""){
 $sqlx="show fields from $table";
 #tfw("tfax.txt",$sqlx,false);
 $result = mysql_query($sqlx);
 while($qd = mysql_fetch_array($result)){
  $fn=$qd["Field"];
  $key=$qd["Key"];
  $type=$qd["Type"];
  if($key=="PRI") return $fn;
 }
 }
}

function getPrimKey($table,$aS=null){
	if(!$aS) return;
	tfi($table,$aS);
	$primid=$aS->primaryid;
	return $primid;
}

function tfi($table,$aS=null){
 if(!tableExists($table)) return false;

 if($table!=""){
 $sqlx="show fields from $table";
 #tfw("tfax.txt",$sqlx,false);
 $result = mysql_query($sqlx);
 while($qd = mysql_fetch_array($result)){
  $fn=$qd["Field"];
  $key=$qd["Key"];
  $type=$qd["Type"];
  $da[$fn]["field"]=$fn;
  $da[$fn]["key"]=$key;
  $da[$fn]["type"]=$type;
  if($aS){
  	if($key=="PRI"){
  	$aS->primaryid=$fn;
  		#echo "** $fn";
  	}
  	if($key=="PRI") $aS->primaryidA[]=$fn;
  }

  }
 }
 return $da;

}

function tfax($table,$prefix=null){
	$fa=tfa($table,$prefix);
    $fx=implode(",",$fa);
    return $fx;
}

function docloader(){
 $dl="<input class=formdata name=\"docfile\" type=\"file\" size=20 >";
 //onChange=\"docexist();\"

 return $dl;
}

function testforbreak($breakv,$subtval){
 $GLOBALS["breaktest"]++;
 if($GLOBALS["use_sectionheads"]){
 	$newsection="
 	<tr><td>&nbsp;</td></tr>
 	<tr class=numtot>
 	<td colspan=10>Section: $breakv</td>
 	</tr>";
 }
 if(($breakv!=$GLOBALS["currentv"])&&($GLOBALS["breaklc"]>0)){
  $bx.="<tr class=numtot>
  <td colspan=".$GLOBALS["tcspans"].">Sub Total for ".$GLOBALS["currentv"]."</td>
  <td align=right>".number_format($GLOBALS["grid_subtot"],2)."</td>
  </tr>";
  $GLOBALS["breaklc"]++;
  unset($GLOBALS["currentv"]);
  unset($GLOBALS["grid_subtot"]);
  if($GLOBALS["subtotal_newpage"]){
	$bx.=$GLOBALS["breakstring"];
	$bx.=$GLOBALS["ttitle"];
	$bx.=$GLOBALS["colheads"];
	$bx.=$newsection;
	$broken=1;
  	unset($GLOBALS["breaklc"]);
  }
  $bx.=$newsection;
  $GLOBALS["breaklc"]++;
  $GLOBALS["breaklc"]++;

// should be inside the newpages per section break test
//  $broken=1;
//  unset($GLOBALS["breaklc"]);
  unset($GLOBALS["grid_subtot"]);
 }else{
 	if($GLOBALS["breaktest"]==1){
 	  $bx.=$newsection;
 	}
 }

 $GLOBALS["grid_subtot"]+=$subtval;
 //totals should occur before function call, always calc'd
 $GLOBALS["grid_gtot"]+=$subtval;
// echo "<br> testb add $subtval = ".$GLOBALS["grid_subtot"];
 $GLOBALS["currentv"]=$breakv;

  if(!$broken){
	 if(isset($GLOBALS["breakat"])){
	  $GLOBALS["breaklc"]++;
//echo "<br>testbreak ".$GLOBALS["breaklc"];
	  if($GLOBALS["breaklc"]>=$GLOBALS["breakat"]){
		$bx.=$GLOBALS["breakstring"];
		$bx.=$GLOBALS["ttitle"];
		$bx.=$GLOBALS["colheads"];
		$bx.=$newsection;
		unset($GLOBALS["breaklc"]);

	  }
 	 }
  }


 return $bx;
}

function grandtotal(){
  //reverted to odyssey style for sales reports.
  if($GLOBALS["currentv"]!=0){
  $bx.="<tr class=numtot>
  <td colspan=".$GLOBALS["tcspans"].">Sub Total for ".$GLOBALS["currentv"]."</td>
  <td align=right>".number_format($GLOBALS["grid_subtot"],2)."</td>
  </tr>";
  $GLOBALS["breaklc"]++;
  }

  $gt=$GLOBALS["report_total"];
  if($GLOBALS["grid_gtot"]!=0){
  $gt=$GLOBALS["grid_gtot"];
  }
  $bx.="<tr class=numtot>
  <td colspan=".$GLOBALS["tcspans"].">Report Totals</td>
  <td align=right>".number_format($gt,2)."</td>
  </tr>";
  unset($GLOBALS["breaklc"]);
  unset($GLOBALS["currentv"]);
  unset($GLOBALS["grid_subtot"]);
  unset($GLOBALS["grid_gtot"]);
  unset($GLOBALS["report_total"]);
  $mx.=multifield_totals();
  if($mx!="</tr>"){
   $bx=$mx;
  }else{
   /*only test if multi is blank*/
  	if($gt==0){
  	 $bx="";
  	}
  }

 return $bx;
}

function multifield_totals(){
  if(isset($GLOBALS["total_fields"])){
	$bx.="<tr class=numtot>
	<td colspan=".$GLOBALS["tcspans"].">Report Totals</td>";
  	foreach($GLOBALS["total_fields"] as $k=>$tfn){
  	 $tv=$GLOBALS["grandtotal"][$tfn];
	 $bx.="<td align=right nowrap>".number_format($tv,2)."</td>";
  	}
  }
  $bx.="</tr>";
 return $bx;
}

function newcall_link($customerid,$contactid=null){
    $cref="setcallcust.php?rvt=wc&mode=new";
    $_SESSION["callurl"]="call.php";
    if(isset($contactid)){
     $cref.="&cid=$contactid";
     }else{
     $cref.="&custid=$customerid";
    }
    $dv=slink($cref,"phonecall","new call").$dv;
 return $dv;
}

function makesortable($col,$tfn,$tfx){
 //echo "mks $col";
 $display=true;
 if(isset($GLOBALS["inputfields"])){
  if($GLOBALS["inputfields"][$tfn]=="hidden"){
   $display=false;
   $col=null;
  }
 }
 if($display){
 if($GLOBALS["sortable_cols"]){
   //$ss=sud($tfn);
   $col="<td class=greytitle nowrap>$tfx $ss</td>";
   $irf=new iReportFilter();
   $bclass="xgreytitle";
   $bclass="formbutton";
   $col="<td>".$irf->sortablecolhead($tfn,$tfx,$bclass)."</td>";
 }
 }

 return $col;
}


function xltable($tfields,$ageda){
 $cs="\t";
 $rs="\n";
 foreach($tfields as $l){
  $x.="$l $cs";
 }
 $x.="$rs";
 foreach($ageda as $rc=>$rowx){
  foreach($rowx as $k=>$rd){
    $rd=str_replace(chr(34),"'",$rd);
    $rd=chr(34).stripslashes($rd).chr(34);
    $x.="$rd $cs";
    if(isset($GLOBALS["total_fields"])){
    	 //echo "<br> adding tot: $k $rd ; ";
    	 $GLOBALS["grandtotal"][$k]+=$rd;
    }

  }
  $x.="$rs";
 }

 /*totals*/
 $x.="$rs Totals $cs";
 for($i=1;$i<$GLOBALS["tcspans"];$i++){
  $x.="$cs";
 }
 if(isset($GLOBALS["total_fields"])){
 //echo "<br>got totals";
  	foreach($GLOBALS["total_fields"] as $k=>$tfn){
  	 $tv=$GLOBALS["grandtotal"][$tfn];
	  $x.="$tv $cs";
 //echo "<br>for $k $tfn $tv";

  	}
  }

 return $x;
}

function tooltip_gear($fieldn,$label,$tt){
 //echo "<br>ppppp test $fieldn $label $tt";
 $toolformats="this.T_BGCOLOR='#d3e3f6';this.T_BORDERCOLOR='#336699'; this.T_WIDTH=400; this.T_SHADOWCOLOR='#FFCC00';this.T_SHADOWWIDTH=8;";
 if($tt!=""){
	$tt=nl2br($tt);
	$tt=str_replace("\n","",$tt);
	$tt=ereg_replace(Chr(13),"", $tt);

 	$ufn="<a class=formlabeltip href=\"javascript:alert('no need to click this')\" onmouseover=\"$toolformats return escape('$tt')\" tabindex=300>$label</a>";
	return $ufn;
	//echo "<br>ppppp got $tt";

 }
 return $label;
}

function inftable_rowarray($tableid,$idf=null,$idv=null){
 if(!isset($idf)){
  $idf=$tablid."id";
 }
 if(isset($idv)){
 $fields=totalfieldarray($tableid);
 $sqlx="select * from $tableid
 where $idf='$idv'";
 //echo "<br>$sqlx";
 $result = mysql_query($sqlx);
 if(!$result) tfw("badifnra.txt",$sqlx,true);
  if($vqd = mysql_fetch_array($result)){
	foreach($fields as $fn){
	 $ira[$fn]=$vqd[$fn];
	}
  }
 }
 return $ira;
}



function infdataloader($table,$filterarr,$fieldsarr,$detailidf,$href,$editok,$tablevarray="table"){
 if(!isset($fieldsarr)){
 $fieldsarr=key2val(totalfieldarray($table));
 //report_sql_error("loaded farr from $table");
 }

 $sqlx="select * from $table ";
 $conjx="where";
 if(isset($filterarr)){
	 foreach($filterarr as $fn=>$fv){
	  $fc++;
	  $sqlx.="$conjx $fn='$fv'";
	  $conjx=" and ";
	 }
	 //$sqlx.=" limit 1";
	 //echo "iii $sqlx";
	 //report_sql_error("inf dl $sqlx");
 }
 $tresult = mysql_query($sqlx);
 if(!$tresult) error_message(sql_error());
 while($cqd = mysql_fetch_array($tresult)){
  $r++;
  $skz=sizeof($fieldsarr);
 // $tv=$cqd[$detailidf];
 // $rd[$r][$detailidf]=$tv;
  $rowid=$r;
  if(isset($detailidf)){
   $rowid=$cqd[$detailidf];
  }
  foreach($fieldsarr as $sk=>$sv){
   $tv=$cqd[$sk];
   $rd[$rowid][$sk]=$tv;
   //etest("sss $rowid $sk $tv");
   //report_sql_error("rd $r $sk = $tv ");
  }
 }
 switch($tablevarray){
  case "array":
  //etest("zz".sizeof($rd));
  return $rd;
  break;
  default:
  $px.=inftable($fieldsarr,$rd,$r,$href,$detailidf,null,null,$editok);
  return $px;
  break;
 }
}


 function fieldoffer($tableid,$dv){
  $xcondx=" and (type!='text' and type!='hidden')";
  useablefields($tableid,$xcondx);
  $GLOBALS["lfldid"][$fieldn]=$fieldn;
  $GLOBALS["lflddesc"][$fieldn]=$fielddesc;
  $jcall="onchange=\"document.subs.submit()\"";
  $sx="$dv<select name=\"fieldn\" $jcall>";
  //echo "<br>sss".sizeof($GLOBALS["lfldid"]);
  $sx.=makeidselectoptionsarraysimple($GLOBALS["lfldid"],$dv);
  return $sx;
 }


 function field_valuesoffer($tableid,$fn,$fv,$wcount=false,$formname="fieldv"){
  $primid=tsecurity($tableid,"primid");
  if($primid){
  $sqlx="select distinct $fn, count($primid) as count
  from $tableid
  group by $fn
  order by $fn
  ";
  //echo $sqlx;
  $tfields=array($fn,"count");
  $da=infqueryarray_pair($sqlx,$tfields);
  if($wcount){
	  $jcall="onchange=\"document.subs.submit()\"";
  }
  $sx="$fn<select name=\"$formname\" $jcall>";
  if($wcount){
  $jcall="onchange=\"document.subs.submit()\"";
  $sx.=makeidselectoptionscountarray($da,$fv);
  }else{
  $sx.=makeidselectoptionscountarray($da,$fv);
  }
  return $sx;
  }
 }

function breakingtable(){
}

function loadInputDates($tid){
	$extra_conditions="and type='date_form'";
	useablefields($tid,$extra_conditions);
	$GLOBALS["inputdatef"]=$GLOBALS["lfldid"];
}

 function distinct_array($table,$fieldid){
  $sqlx="select distinct $fieldid from $table";
  $da=infqueryarray_forkeylist($sqlx,$fieldid,key2val(array($fieldid))) ;
  return $da;
 }

 function selectbox_array($fname,$da,$jcall=null,$wcount=null,$dv=null,$idn=null){
  if($idn) $idnx="id=$idn";
  $sx="<select $idnx class=\"formdata\" name=\"$fname\" $jcall>";
  if($wcount){
	  $sx.=makeidselectoptionscountarray($da,$dv);
	  }else{
	  $sx.=makeidselectoptionsarraysimple($da,$dv);
  }
  $sx.="</select>";
  return $sx;
 }

function xasciiOnly($str,$replaceMent=""){
#replaced as per below.
$str= preg_replace('/[^(\x20-\x7F)]*/',$replaceMent, $str);
return $str;
}

function asciiOnly($str,$replaceMent=""){
#String resultString = subjectString.replaceAll("[^\\x00-\\x7F]", "");
$str = preg_replace('/[\x00-\x1F\x80-\xFF]/', $replaceMent, $str);
return $str;
}


function alphasOnly($str){
	#$str= ereg_replace("[^A-Za-z0-9]", " ", $str);
	#+ some specials
	#$str= preg_replace("[^A-Za-z0-9 @/!$%&*+-:()]", "aa", $str);
	#$str = preg_replace("[^A-Za-z0-9\s\s+\.\:\-\/%+\(\)\*\&\$\#\!\@\"\';]"," ",$str);

	#$str= ereg_replace("[^A-Za-z0-9 @/!$%&*+-:()[ ]","aa",$str);
	#need to allow sq brackets too
	$str= ereg_replace("[^A-Za-z0-9 @/!<>$%&*+-:()]","",$str);
    #$str= preg_replace("[^A-Za-z0-9 @/!<>$%&*+-:()]","",$str);

	return $str;
}


function vEntities($fv){
	#plus sign not handled ?!#@
	$fv=htmlentities($fv);
	tfw("vent.txt",$fv,true);
	$fv=str_replace("&amp;#43","+",$fv);
	#$fv="vent";
	return $fv;
}

function vClean($fv){
		$fv=str_replace("&gt;",">",$fv);
		$fv=str_replace("&lt;","<",$fv);

   		$fv=ereg_replace("(\r\n|\n|\r)","<txtaNl>", $fv);
		$fv=ereg_replace(Chr(13),"", $fv);

		#added nov 07 during textarea edits
		$fv=str_replace("\\","",$fv);
		$fv=addslashes($fv);
		$fv=str_replace("<txtaNl>","\\n",$fv);
		$fv=str_replace("&amp;#43","pp",$fv);
		$fv=str_replace("!I!SQ","QQ",$fv);

		//$fv=str_replace("\\')","')",$fv);
		//echo "alert('$fv')\n";
 return $fv;
}

function mClean($fv){
		#a version of vclean that allows line breaks to be preserved
		# in hidden fields so that they can flow to in place edits
		# via a newline replace function
		$fv=addslashes($fv);
		$fv=str_replace("\\'","'",$fv);
   		$fv=ereg_replace("(\r\n|\n|\r)","<txtaNl>", $fv);

		#added the next line 23.01.09 in response to probs on textarea on cust master.
		#watching for subsequent probs
		$fv=str_replace("<txtaNl>","",$fv);

 return $fv;
}


function taDisplay($fv){
	    #echo "alert('$fv')\n";
   		$fv=ereg_replace("(\r\n|\n|\\n|\r)","<txtaNl>", $fv);
		#$fv=str_replace("\\n","<txtaNl>",$fv);
   		#tfw("tads.txt","$fv",true);
		#$fv=ereg_replace(Chr(13),"", $fv);
		#$fv=str_replace("line","xxx",$fv);
		#$fv=str_replace("\n","aaa",$fv);
		//$fv=addslashes($fv);
		//$fv=str_replace("\\')","')",$fv);
		//echo "alert('$fv')\n";
		#$fv="tae";
 return $fv;
}



function fixJlink($fv){
#		$fv=str_replace("(\\\\'","(\'xx",$fv);
#		$fv=str_replace("\\\","'",$fv);
#		$fv=str_replace("(\\'","(\'",$fv);
##		$fv=str_replace("(\'","(\'",$fv);
#		$fv=str_replace("\\\')","')",$fv);
##		$fv=str_replace("\\')","')",$fv);
##		$fv=str_replace("\')","')",$fv);
#		tfw("fjl.txt",$fv,true);

$fv=stripslashes($fv);
$fv=stripslashes($fv);
$fv=stripslashes($fv);
$fv=stripslashes($fv);
$fv=addslashes($fv);
		return $fv;
}


function aLineBreaks($html) {
      $patterns = array(
         "/\n/",
         "/\r/"
      );
      $replace = array(
         "\\n",
         "\\r"
      );
      $fix = addslashes($html);
      $fix = preg_replace($patterns,$replace,$fix);
      return $fix;
}

function aLineBreaks2($html) {
      $patterns = array(
         "/\n/"
      );
      $replace = array(
         "\\n"
      );
      $fix = addslashes($html);
      $fix = preg_replace($patterns,$replace,$fix);
      return $fix;
}

function plainText($cfx){
	#removes crazy non ASCII eg. square characters
	$cfx = preg_replace('/[^(\x20-\x7F)]*/','', $cfx);
	return $cfx;
}

function aBRLineBreaks($html) {
      $patterns = array(
         "<br><br>",
         "<br />\n<br />",
         "<br />",
         "<**lb**> <**lb**>",
         "**lb**"
      );
      $replace = array(
         "<br>",
         "<br>",
         "**lb**",
		"<**J1**>",
		"<**J2**>"
      );
      $fix = addslashes($html);
      $fix = preg_replace($patterns,$replace,$fix);
      return $fix;
}




function unClean($fv){
		$fv=str_replace("\'","'",$fv);
		$fv=str_replace("class='sortable'","class=tibs",$fv);
 return $fv;
}


function iqasrf($sqlx,$field){
	/*single row field query*/
	$erx=mysql_errno();
	#if(!$result) tfw("badq.txt","$sqlx  err $erx",true);
	$result=mysql_query($sqlx);

	if($vqd = mysql_fetch_array($result)){
	   $val=trim($vqd[$field]);
	   return $val;
	}
}

function iqatr($table=null,$idf=null,$idv=null){
	$tf=tfa($table);
	if(is_array($idf)){
		$condx=qcbld($idf);
		}else{
		$condx="where $idf='$idv'";
	}
	$fx=implode(",",$tf);
	#$qx="select $fx from $table where $idf='$idv'";
	$qx="select * from $table $condx";
	#echo "<br>$qx";
	tfw("iqa.txt",$qx,true);
	/*single row field query*/
	$result=mysql_query($qx);
	elog("iqat $qx","mastf 3119 ");
	if($cqd = mysql_fetch_array($result,MYSQL_ASSOC)){
		foreach($cqd as $fn=>$fx) $fdx.="$fn = $fx";
		tfw("iqatt.txt",$fdx,true);
	 	return $cqd;
	}
}


function iqakey($tn,$tf=null,$keyA=null){
  #table, key, field
  if($tf=="") return;
  $fx=implode(",",$tf);
  $cond=qcbld($keyA);
  $q="select $fx from $tn $cond";
  #echo $q;
  elog("$q","tkf $q");
  $da=iqa($q,$tf);
  return $da;
}

function iqatkf($tn,$tf=null,$keyA=null){
  #table, key, field
  if($tf=="") return;
  $fx=implode(",",$tf);
  $cond=qcbld($keyA);
  $q="select $fx from $tn $cond";
  elog("$q","tkf $q");
  $da=iqasra($q,$tf);
  return $da;
}


function iqasra($sqlx=null,$tf=null,$tid=null,$condx=null){
 #echo "alert('qqq ')\n";
 if($sqlx!=""){
 	$qx=$sqlx;
 }else{
 	if($tid!=""){
 		if(!isset($tf)) $tf=tfa($tid);
 		if(is_array($tf)){
 			$fx=implode(",",$tf);
 			$qx="select $fx from $tid $condx";
 			elog("iqa sra $qx","masterfuncret 3136");
 			tfw("iqx$tid.txt",$qx,true);
 		}
 	}else{
 		return;
 	}
 }
 #elog("iqa sra $qx","masterfuncret 3143");
 $tresult = mysql_query($qx);
 tfw("iquery.txt","q $qx",true);
 if(!tresult) tfw("badquery.txt","q $qx",true);
 if($cqd = mysql_fetch_array($tresult,MYSQL_ASSOC)){
 	return $cqd;
 }
}


function dealWJparam($params){
 $px=explode("&",$params);
 foreach($px as $pair){
	 $pa=explode("=",$pair);
	 $pn=$pa[0];
	 $pv=$pa[1];
	 $qstring[$pn]=$pv;
 }
 return $qstring;
}


function aFV_resultSet($cresult,$fn){
	while($cqd = mysql_fetch_array($cresult)){
		$val=$cqd[$fn];
			if(!isset($vals)){
				#if(is_array($vals)){
					if(sizeof($vals)>0){
						if(in_array($val,$vals)){
							$oka=false;
						}
					}
				#}
			}
			if($oka){
				$vals[]=$val;
			}
	}
	if(isset($vals)) $vals=array_unique($vals);
	#echo $afx;
	#tfw("afv.txt",$afx,true);
	return $vals;
}

function aFV($da,$fn,$clean=false){
	if(isset($da)){
		foreach($da as $row){
			$val=$row[$fn];
			if($clean) $val=vClean($val);
			$afx.="<br>add $fn $val";
			$oka=true;
			if($val=="") $oka=false;
			if(!isset($vals)){
				#if(is_array($vals)){
					if(sizeof($vals)>0){
						if(in_array($val,$vals)){
							$oka=false;
						}
					}
				#}
			}
			if($oka){
				$vals[]=$val;
			}
		}
	}
	if(isset($vals)) $vals=array_unique($vals);
	#echo $afx;
	#elog("afv $fn $afx ","mfunc 3245");
	return $vals;
}

function aFS($da,$fn){
   #array field sum
  if(isset($da)){
    foreach($da as $i=>$row){
      $val=$row[$fn];
      #$afx.="<br>row $i add $fn $val";
      $tval+=$val;
    }
  }
  echo $afx;
  return $tval;
}



function daFSA($da,$fname=null){
	#data set of unique values in a 'da' format from array created by k2val
	if(isset($da)){
	foreach($da as $i=>$v){
		$nda[$i][$fname]=$v;
	}
	return $nda;
	}
}


function daFV($da,$fn,$clean=false,$fieldn="value"){
	#data set of unique values in a 'da' format with "value" as field name;
	if(isset($da)){
		foreach($da as $row){
			$val=$row[$fn];
			if($clean) $val=vClean($val);
			$afx.="<br>add $val";
			$oka=true;
			if($val=="") $oka=false;
			if(!isset($vals)){
				#if(is_array($vals)){
					if(sizeof($vals)>0){
						if(in_array($val,$vals)){
							$oka=false;
						}
					}
				#}
			}
			if($oka){
				$vals[]=$val;
			}
		}
	}
	if(isset($vals)) $vals=array_unique($vals);
	foreach($vals as $uv) $uva[][$fieldn]=$uv;
	#echo $afx;
	tfw("afv$fn.txt",$afx,true);
	return $uva;
}



function dCV($table=null,$field=null,$aS=null){
	#distinct count values
	$pk=getPrimKey($table,$aS);
	$q="select distinct $field as fval, count($pk) as vcount from $table
	group by $field";

	tfw("dcq_prim.txt","$pk $q",true);

	if($pk=="") return;

	tfw("dcq.txt",$q,true);
	$tf=kda("fval,vcount");
	$da=iqa($q,$tf);
	return $da;

}


function kFV($da,$fn,$clean=false){
	$da=aFV($da,$fn,$clean);
	$kda=key2val($da);
	return $kda;
}

function aFVQ($sqlx,$fn){
	#distinct aFV values from a query and single field
	$tf=kda($fn);
	$da=iqa($sqlx,$tf);
	$nda=aFV($da,$fn);
	$kda=key2val($nda);
	return $kda;
}

function iqa($sqlx,$tfields=null,$keyf=null,$tablen=null){
	$da=infqueryarray($sqlx,$tfields,$keyf,$tablen);
	return $da;
}

function iqatcont($table){
 $da=stca($table,null,null,null,true);
 return $da;
}

function iqta($table,$aS){
 $tfl=listablefields($table);
 $lfA=array_keys($tfl);
 $firstField=$lfA[0];
 $aS->listableFA=$lfA;

 getPrimKey($table,$aS);
 foreach($aS->primaryidA as $pfid){
 	#echo "<br>ad pk $pfid";
 	$tfl[$pfid]=$pfid;
 }


 if(sizeof($tfl)==0) echo "<br>! No fields are noted listable in $table yet";
 $tf=key2val(array_keys($tfl));
 $tfx=implode(",",$tf);
 $q="select $tfx from $table order by $firstField";
 #needs all fields, hidden or otherwise
 #$q="select * from $table";
 #echo $q;
 $da=iqa($q,$tfl);
 #returns array of data and fields
 $dpack[0]=$da;
 $dpack[1]=$tfl;
 return $dpack;
}


function stca($table,$fn=null,$strcase=null,$ordby=null,$full=null){
	$tfi=tfi($table);
	if(isset($tfi)){
		foreach($tfi as $fn=>$fA) if($fA["key"]=="PRI") $primid=$fn;
	}

	#standard table contents array;
	if($full){
			$tfa=tfa($table);
			$fn=implode(",",$tfa);
	}else{
		if($fn=="") {
			$tfa=tfa($table);
			#assume standard table structure, sort by also
			$fn=$table."desc";
			if(in_array($fn,$tfa)){
				$obx=" order by $fn";
			}
		}
	}
	if($ordby!="") $obx=$ordby;
	switch($strcase){
		case "upper":
		$q="select distinct upper($fn) as $fn from $table $obx";
		break;

		default:
		$q="select distinct $fn from $table $obx";
		break;
	}
#echo $q;
	tfw("qskx.txt",$q,true);
	$tresult = mysql_query($q);
 	$fa=kda($fn);
 	if($full){
	 	$fa=key2val($tfa);
	 	$ta=iqa($q,$fa);
	}else{
	 	$da=iqa($q,$fa,$fn);
	 	$ta=aFV($da,$fn);
 	}
 	return $ta;
}


function reCruncher($da,$delim="!I!",$delim2="="){
	foreach($da as $k=>$v) {
    $x.="$delim";
    $x.="$k";
    $x.="$delim2";
    $x.=urlencode($v);
	  #$x=urlencode($x);//required eg. apostrophes in contact name search
    }


	return $x;
}
function logitm($string){

	  $file = 'C:/temp/cronreport.log';
	  $fh = fopen($file, 'a');
	  fwrite($fh, $string."\n");
	  fclose($fh);
}

function formCruncher($str,$delim="!I!",$delim2="=",$lastInOk=false){
	#lastinok to allow replacing if later values should prevail eg. accumulating date parameters
	if(array_key_exists("fcrc",$GLOBALS)){
	  $GLOBALS["fcrc"]++;
	}
	#echo "<br> delim $delim";
	if($str=="") return;
	#$sufx=$GLOBALS["fcrc"]; ANK removed
	$str=str_replace("undefined","",$str);
	$da=explode($delim,$str);
	$px=implode($delim2,$da);

	#tfw("fcr$sufx.txt","d1 $delim d2 $delim2 $str *** $px",true);

	if(strpos('more',$str)){
		echo "alert('fcc $params')\n";
		exit;
	}
	#tfw("fcrunch$sufx.txt","s $str now $px",true);
	$tdx="";
	foreach($da as $dx){
		$tdx.="~~ $dx";
		$ra=explode($delim2,$dx);
		//logitm("dx: $dx count:". count($ra));
		
		$rfn=$ra[0];
		#if(count($ra) == 2){
		  $rdv=rawurldecode($ra[1]);
		#} else {
		#  $rdv = "";	
		#}	
		#$rdv=(isset(rawurldecode($ra[1])) ? rawurldecode($ra[1]) : "");
		
		#$rdv=urldecode($rdv);
		#$raw = (isset($ra[1]) ? $ra[1] : "");
		$raw=$ra[1];
		#tfw("fc$rfn.txt","$raw : $rdv",true);
		#ANK 19.12.2014 add checkrequired = false
		if(isset($newda)) {
			$checkRequired=true;
		} else {
			$checkRequired=false;
		}	
		$pass=false;
		#testing pass to avoid warning on $newda not existing
		if($checkRequired){
			if((!array_key_exists($rfn,$newda))|($lastInOk)) $pass=true;
		}else{
			$pass=true;
		}

		#if((!array_key_exists($rfn,$newda))|($lastInOk)){
		if($pass){
			#avoid blank value - espec if unwanted dup appearance
			#but blanks OK if correcting...
			#if($rdv!="") $newda[$rfn]=$rdv;
			$newda[$rfn]=$rdv;
		}
		#$fcx.="**$rfn : $raw : $rdv";
		#look for nested arrays eg checkbox,(eg prodsizes)
		if(strpos($rfn,"[")){
			$nsa=explode("[",$rfn);
			/**/
			if(is_array($nsa)){
				$nsn=$nsa[0];
				$nsv=$nsa[1];
				$nsv=str_replace("]","",$nsv);
				$nestedx.="$nsn = $nsv";
				$newda[$nsn][$nsv]=$rdv;
			}
			/**/
		}

	}
	#tfw("fcrdx$sufx.txt","$tdx",true);
	#tfw("nested.txt","$fcx $nestedx",true);
   return $newda;
}

function dragGroupCrucher($gx){
	 	$ga=explode("!GX!",$gx);
		foreach($ga as $gcount=>$gax){
	 		 $attra=explode("!ATTR!",$gax);
			 foreach($attra as $atn=>$atv){
				switch($atn){
					default:
					$linea=explode(":",$atv);
					$kn=$linea[0];
					$kv=$linea[1];
					//echo "alert('got  $kn : $kv')\n";
					$temp[$kn]=$kv;
					break;
				}
		 	}
		}
}



function xformCruncherMultiLine($str,$firstel){
	$da=explode("!I!",$str);
	$px=implode("=",$da);
	foreach($da as $linec=>$dx){
		$ra=explode("=",$dx);
		$rawfn=$ra[0];
		$openbrackPos=strpos($rawfn,"[");
		$fn=substr($rawfn,0,$openbrackPos);
		$fv=$ra[1];
		if($fn==$firstel) $lineCount++;
		$newda[$lineCount][$fn]=$fv;
	}

   return $newda;
}

function formCruncherMultiLine($str,$firstel="jline",$delim1="!I!",$delim2="="){
	#trim leading part up to first repeating element
	$startPos=strpos($str,$firstel);
	if($startPos>0){
		$str=substr($str,$startPos);
	}


	$da=explode($delim1,$str);
	$px=implode("=",$da);
	$lineCount=-1;
	foreach($da as $dx){
		$ra=explode("$delim2",$dx);
		$fn=$ra[0];
		$dv=urldecode($ra[1]);
		if($fn!=""){
			#echo "alert('f $fn d $dv')\n";
			if($fn==$firstel) $lineCount++;
			$newda[$lineCount][$fn]=$dv;
			$fcm.=" ** $lineCount $fn $dv";
		}
	}
	tfw("fcm.txt","px:$px str: $str fcm: $fcm",true);
   return $newda;
}


function tableData($table,$condx=null,$keyf=null){
	$q="select * from $table $condx";
	$tf=key2val(tfa($table));
	tfw("tdx.txt",$q,true);
	$da=iqa($q,$tf,$keyf);
 	return $da;
}

function singleRowFlip($tf,$da){
	foreach($da as $i=>$row){
		$nda[$i]=array_flip($row);
	}
	return $nda;
}


function br2nl($string)
{
    return preg_replace("/\<br(\s*)?\/?\>/i", "\n", $string);
}

function makePlainText($plainx){
#want to preserve breaks in header which html2txt does ok
$plainx=html2txt($plainx);

#then want consecutive <br> removed - struggling
#$plainx = ereg_replace("[\n\r]", "\t", $plainx);
#$plainx = ereg_replace("\t\t+", "\n", $plainx);

$plainx=nl2br($plainx);

#$plainx=str_replace('<br />', '*linebreak*', $plainx);
#$plainx=str_replace('\n\r', 'wtf', $plainx);


$plainx=preg_replace('#\r?\n#','', $plainx);
$plainx=preg_replace('#<br />#','!lb!', $plainx);
$plainx=preg_replace('#wtf\n\rwtf#','xxx', $plainx);
#$plainx=str_replace('\n', 'xyz', $plainx);
#$plainx=str_replace('*linebreak* *linebreak*', '*dbl*', $plainx);


$pa=strposAllText($plainx,"!lb!");
foreach($pa as $pos){
	$xc=substr($plainx,$pos+5,4);
	#echo "<br>pos: $pos then $xc";
	if($xc=="!lb!"){
		$badpos[]=$pos;
	}
}
foreach($badpos as $pp){
	$a=substr($plainx,0,$pp);
	$b=substr($plainx,$pp+4);
	$plainx=$a."....".$b;
}
$plainx=str_replace('!lb!!lb!', '<br />', $plainx);
$plainx=str_replace('!lb!', '<br />', $plainx);
$plainx=str_replace('....', '', $plainx);

#$plainx=html2txt($plainx);
return $plainx;

}



function strposAllText($haystack,$needle){
    $s=0;
    $i=0;

    while (is_integer($i)){

        $i = strpos($haystack,$needle,$s);

        if (is_integer($i)) {
            $aStrPos[] = $i;
            $s = $i+strlen($needle);
        }
    }
    if (isset($aStrPos)) {
        return $aStrPos;
    }
    else {
        return false;
    }
}

function blob2Array($dv,$cperline=85){
		#splits blobs into lines
		#echo "consider desclines";
		$bcount=iStringCount($dv,"\n");
		$linea=explode("\n",$dv);
		foreach($linea as $lx){
			unset($fposn);
			$nposn=0;;
			$ln=strlen($lx);
			if($ln>$cperline){
				#echo"<br>got:$ln $lx";
				while($fposn<$ln){
					$fposn+=$cperline;
					$firstx=substr($lx,$nposn,$fposn);
					$sublen=strlen($firstx);
					#break line on a space if nec.
					if($sublen>=$cperline){
						$lspace=strrpos($firstx," ");
					}else{
						$lspace=$sublen;
					}
					$sx=substr($lx,$nposn,$lspace);
					$eposn=$lspace;
					#$lcx="subline got:$nposn - $eposn $sx";
				    $sx=str_replace("\n","",$sx);
					#$sx.="- $lspace";
					$lcx=$sx;
					#$nposn+=$cperline;
					$nposn+=$lspace;
					#echo"<br>add:$ln $lcx";
					$rlinea[]=$lcx;
				}
			}else{
				    #$lx=str_replace("\n","",$lx);
				    #if($lx!=""){
				    	$llen=strlen($lx);
				    	#if($llen>1)
				    	$rlinea[]=$lx;
					#}
			}
		}
		$szx=sizeof($linea);
		$lcx.="$bcount lines = $szx";
		return $rlinea;
}


function innerLoad($divid,$x){
	echo "if(g('$divid')) g('$divid').innerHTML='$x' \n";
}


/*start document iform functions*/
function reviewHeaderSize($d){
	$mailto=$d->head["mailto"];
	$shipto=$d->head["shipto"];
	$ma=blobLines($mailto,100);
	$msz=sizeof($ma);

	$sa=blobLines($shipto,100);
	$ssz=sizeof($sa);

	#echo "$mail $shipto";
	#echo "<br>m $msz $s $ssz";
	$hl=$ssz>$msz?$ssz:$msz;
	$orig=$d->headerSize;
	$new=$d->headerSize+$hl;
	return $new;
}

function blobLines($dv,$cperline=85){
		#splits blobs into lines
		#echo "consider desclines";
		$bcount=iStringCount($dv,"\n");
		$linea=explode("\n",$dv);
		foreach($linea as $lx){
			unset($fposn);
			$nposn=0;;
			$ln=strlen($lx);
			if($ln>$cperline){
				#echo"<br>got:$ln $lx";
				while($fposn<$ln){
					$fposn+=$cperline;
					$firstx=substr($lx,$nposn,$fposn);
					$sublen=strlen($firstx);
					#break line on a space if nec.
					if($sublen>=$cperline){
						$lspace=strrpos($firstx," ");
					}else{
						$lspace=$sublen;
					}
					$sx=substr($lx,$nposn,$lspace);
					$eposn=$lspace;
					#$lcx="subline got:$nposn - $eposn $sx";
				    $sx=str_replace("\n","",$sx);
					#$sx.="- $lspace";
					$lcx=$sx;
					#$nposn+=$cperline;
					$nposn+=$lspace;
					#echo"<br>add:$ln $lcx";
					$rlinea[]=$lcx;
				}
			}else{
				    #$lx=str_replace("\n","",$lx);
				    #if($lx!=""){
				    	$llen=strlen($lx);
				    	#if($llen>1)
				    	$rlinea[]=$lx;
					#}
			}
		}
		$szx=sizeof($linea);
		$lcx.="$bcount lines = $szx";
		return $rlinea;
}
/*end document iform functions*/

function nls2p($str)
{
	$new_string=urlencode ($str);
    $new_string=ereg_replace("%0D", " ", $new_string);
    $new_string=urldecode  ($new_string);
    return $new_string;

}

function daul($da,$classx=null){
	$lx=implode("</li><li class=$classx>",$da);
	$ux="<ul><li  class=$classx>$lx</li></ul>";
	return $ux;
}

function dalist($da,$title="Listing",$colheads=array("item","value"),$classx=null){
	foreach($da as $label=>$value){
		$r++;
		$row[$r][$colheads[0]]=$label;
		$row[$r][$colheads[1]]=$value;
	}
	$ist=new divtable();
	$ist->noxl=1;
	$ist->showIt(key2val(array_values($colheads)),$row,$title);
}




function hintLink($hint,$class="ftip",$icon="hint"){
       $v="$v<a class=\"$class\" href=\"#\" title=\"$v hint:|$hint\"> ".ib($icon,$icon)."</a>";
       # need call eg.$aS->jcallA[]=" $('a.ftip').cluetip({splitTitle: '|'});";

	return $v;
}

function paramsDecode($str){
	#for stripping html entity codes, retaining line breaks and removing other tags
	#used in point and click editing eg. eipjq
	$str=str_replace("!amp!","&amp",$str);
	$str=str_replace("&amp;","&",$str);
	$str=html_entity_decode($str);
	$str=br2nl($str);//chrome appears unncessary seems to result in double lines - for inserting in textareas
	#$str=str_replace("\n");
	$str=strip_tags($str);
	$str=str_replace("\n\n","\n",$str);

	return $str;
}

function paramsDecode2($str){
  #for stripping html entity codes, retaining line breaks and removing other tags
  #used in point and click editing eg. eipjq
  $str=str_replace("<br>","!break!",$str);
  $str=str_replace("/n","!break!",$str);
  $str=strip_tags($str);
  $str=html_entity_decode($str);

  #$str=asciiOnly($str," ");
  #$str=str_replace("  ","dblsp",$str);
  #$str=str_replace(" ","",$str);
  #$str=str_replace("dblsp","",$str);
  #$str=str_replace("!amp!","&amp",$str);
  #$str=str_replace("&amp;","&",$str);
  #$str=str_replace("!break!","<br>",$str);
  #$str=br2nl($str);//chrome appears unncessary seems to result in double lines - for inserting in textareas
  #$str=str_replace("\n");
  #$str=str_replace("\n\n","\n",$str);

  return $str;
}

function fpa($aS,$formElementName=null,$dl1="!A!",$dl2="!eq!"){
	#Form via Parameter Array
	$fx=$aS->paramsA[$formElementName];
	$fa=formCruncher($fx,$dl1,$dl2);
	return $fa;
}

function fpml($aS,$formElementName=null,$firstel="linec",$dl1="!A!",$dl2="!eq!"){
	#Form via Parameter Array
	$fx=$aS->paramsA[$formElementName];
	$fa=formCruncherMultiLine($fx,$firstel,$dl1,$dl2);
	return $fa;
}

function afsum($da, $fn) {
	#array field sum
        $sum = 0;
        foreach($da as $sub_array) {
            $sum += $sub_array[$fn];
        }
        return $sum;
}

function jsonQset($q){
  $rst = mysql_query($q);
while($r = mysql_fetch_assoc($rst)) {
    $rows[] = $r;
}
$jx=json_encode($rows);
elog("jx $jx","mfret 3869");
print json_encode($rows);
}

function makeselectOptionsOnly($vt,$datastyle=null,$fieldn=null,$dv=null,$sortonid=null,$jcall=null){
 switch ($vt){
  case ("users"):
  $sqlx="select * from users
  where (inactive is null
  or inactive!='on')
  order by userid ";
  $fieldtofind="userid";
  #elog("msu $slqx","mf 565");
  break;
  default:
  $sqlx="select * from $vt";
  if($sortonid){
    $sqlx.=" order by ".$vt."id";
  }else{
    $sqlx.=" order by $fieldn";
  }
 //order by ".$vt."id";
 //echo $sqlx;
  $fieldtofind=$vt."desc";
  elog("mks $sqlx","mreturn577");
  break;
 }
 if(isset($GLOBALS["tix"])){
  $txx="tabindex=".$GLOBALS["tix"];
 }
 $txx.=" $jcall";
 if(!$GLOBALS["blocknewoffer"]){
 $oclicknew="onclick=\"testfornew(this);\"";
 }
  $msx.=$GLOBALS["masterselectsizex"];
  if ($_GET["mode"]=="new"){
   //$dv="normal";
  }
  //echo $msx;
  switch ($vt){
   case ("users"):
   $msx.=makeselectoptionsfulluser($sqlx,$dv,$fieldtofind);
   break;
   default:
   $msx.=makeselectoptions($sqlx,$dv,$fieldtofind);
   break;
  }
  if ($vt=="userprofile"){
   if (!amiadmin()){
    unset($msx);
   }
  }
  return $msx;
}

function qRowCount($sqlx){
//echo $sqlx;
    $CI = & get_instance();
    $CI->load->database();
    $db = $CI->db;
$cresult = $db->query($sqlx);
//if(!$cresult) error_message(sql_error());
//$rc=mysql_num_rows($cresult);
//return $rc;
return true;
}

function tfw($filename, $data,$owrite=true){
	//if(!$GLOBALS["testmode"]) return;
	#too many memory outs, use elog
	return;
	if($filename=="") return;
	if($filename==".txt") return;
	$fn= "C:/temp/$filename";
	filewriter($fn, $data,$owrite);
}


?>