<?php

function tfw($filename, $data,$owrite=true){
	#return;
	if($filename=="") return;
	if($filename==".txt") return;
	$fn=$_SESSION["clientpath"]."/temp/$filename";
	#$fn="./temp/$filename";
	filewriter($fn, $data,$owrite);
}

function ptfw($filename, $data,$owrite=true){
	$fn=$_SESSION["clientpath"]."/temp/$filename";
	filewriter($fn, $data,$owrite);
}


if ($_SESSION["validuser"]){
// addbarstyle();
}
function addbarstyle(){
?>
<style>
<!--
.menu {text-decoration:none; color:white;}
.menu:hover {text-decoration:none; color:orange;}
BODY
	{
	scrollbar-face-color : #000000;
	scrollbar-shadow-color : #2F3E5D;
	scrollbar-highlight-color : #2F3E5D;
	scrollbar-3dlight-color : #cccccc;
	scrollbar-darkshadow-color : #8d8d8d;
	scrollbar-track-color : #5d5d5d;
	/*scrollbar-arrow-color : #FF9600;*/
	scrollbar-arrow-color : green;
	}
-->
</style>
<?php
}
Function readuser($userx){
$sqlx= "select * from users where userid='".$userx."' ";
return $sqlx;
}
Function readjtuser($userx){
$sqlx= "select * from users where jtlogin=1 and userid='".$userx."' ";
return $sqlx;
}

Function readusers(){
$sqlx= "select * from users
order by userorder
";
return $sqlx;
}

Function readdiary($pdate){
 $query="
 SELECT * FROM diary
 WHERE date='$pdate'
 ORDER BY start";
// echo $query;
}

Function arrayreaduser($userid){
 $sqlx="
 SELECT * FROM users
 WHERE userid='".$userid."' ";
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
  return $cqd ;
 }
}

Function readcust($custid,$leftx=null){
 $sqlx="
 SELECT * FROM customer as c
 $leftx
 WHERE c.customerid=".$custid." ";
// echo $sqlx;
 $cresult = mysql_query($sqlx);
 tfw("rcq.txt",$sqlx,true);
 #if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
  return $cqd ;
 }
}

Function readcontact($contactid){
 $sqlx="
 SELECT * FROM contact
 WHERE contactid=".$contactid." ";
// echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
  return $cqd ;
 }
}

Function readprospect($custid){
 $sqlx="
 SELECT * FROM prospect
 WHERE customerid=".$custid." ";
// echo $sqlx;

 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
  return $cqd ;
 }
}

Function readprospectcontact($contactid){
 $sqlx="
 SELECT * FROM prospectcontact
 WHERE contactid=".$contactid." ";
// echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
  return $cqd ;
 }
}

Function readjob($jobid){
 $sqlx="SELECT * FROM jobs as j
 left outer join customer as c on
 j.customerid=c.customerid
 WHERE jobid=".$jobid." ";
 //echo $sqlx;
 tfw("qq.txt",$sqlx,true);
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
  return $cqd ;
 }
}

Function readtask($taskid){
 $sqlx="SELECT * FROM task
 WHERE taskid=$taskid";
// echo "ttt $sqlx";
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
  return $cqd ;
 }
}

Function useropenjobs($userid){
 $sqlx="SELECT * FROM jobs as j
 left outer join customer as c on
 j.customerid=c.customerid
 WHERE userid='".$userid."'
 and jobstatus<>'closed'
 order by duedate asc
 ";
 //echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
  return $cresult ;
}

Function useropenquotes($userid){
 $sqlx="SELECT  distinct ql.refno,qh.qstatus,c.companyname,qh.quotedate,
 sum(ql.qty*ql.price) as net
 FROM olquotelines as ql
  left outer join olquotes as qh
  on ql.refno=qh.refno
  left outer join customer as c
  on ql.customerid=c.customerid
  WHERE qh.userid='$userid'
  and qh.qstatus<>'declined'
  and qh.qstatus<>'accepted'
  group by refno,qstatus
  order by qh.qstatus desc
 ";
 //echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
  return $cresult ;
}

Function countopenquotes($userid){
 /*
 $sqlx="SELECT count(refno) as rcount
 FROM olquotes as qh
  WHERE qh.userid='$userid'
  and qh.qstatus='new'
 ";
 */

 $sqlx="
 SELECT count(distinct(ql.refno)) as rcount
  FROM olquotelines as ql
  left outer join olquotes as qh
  on ql.refno=qh.refno
  where qh.userid='$userid'
  and qh.qstatus!='declined'
  and qh.qstatus!='accepted'
 ";


 //echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($qd = mysql_fetch_array($cresult)){
 $rcount=$qd["rcount"];
 return $rcount;
 }
}

Function readcall($callid){
 $sqlx="SELECT * FROM call as c
 left outer join contact as cn on
 (c.contactid=cn.contactid)
 WHERE callid=".$callid." ";
// echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
  return $cqd ;
 }

}

Function readcallcust($callid){
 $sqlx="SELECT cutomerid FROM call
 WHERE callid=".$callid." ";
// echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
  $custid=$cqd["customerid"];
  return $custid ;
 }

}

function readappt($apptid){
 $sqlx="SELECT d.apptid,d.dte,d.customerid,d.userid,d.start,d.duration,d.end,
 d.subject,d.notes,d.completed,d.processed,d.chargeid,d.contactid,d.createdby,d.datecreated,
 d.keyactivity,d.callbackdate,d.jobid,d.taskid,d.profileitemid,
 c.companyname,co.firstname,co.surname
 FROM diary as d
 left outer join customer as c
 on d.customerid=c.customerid
 left outer join contact as co
 on d.contactid=co.contactid
 WHERE d.apptid=".$apptid." ";
 //echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
  return $cqd ;
 }

}


Function readjobtrans($jobid){
 $sqlx="SELECT * FROM diary
 WHERE jobid=".$jobid." ";
// echo $sqlx;
 return $sqlx;

}

Function fieldon($table,$fieldn){
$sqlx="select * from tablename
where tableid='".$table."'
and fieldid='".$fieldn."'
order by pos
";
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
return $cresult;
/*
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
$rc=mysql_num_rows($cresult);
if ($rc>0){
 return true;
}
*/
}

Function isfieldon($table,$fieldn){
$sqlx="select * from tablename
where tableid='".$table."'
and fieldid='".$fieldn."'
order by pos
";
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
$rc=mysql_num_rows($cresult);
if ($rc>0){
 return true;
}
}


Function allfields($table){
$db=$GLOBALS["default_dbname"];
$result = mysql_list_fields("$db", "$table", $GLOBALS["link_id"]);
return $result;
}


Function fieldon2($table,$fieldn){
$sqlx="select inuse,pos from tablename
where tableid='".$table."'
and fieldid='".$fieldn."'
order by pos
";
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
if($qd = mysql_fetch_array($cresult)){
 $a =$qd["inuse"];
 return true;
 }
 return false;
}

Function getsortedfields($table){
$sqlx="select * from tablename
where tableid='".$table."'
and inuse='on'
order by pos,colorder";
//echo "ggggg $sqlx";
//tfw("gsf.txt",$sqlx,true);
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
return $cresult;

}

function sortedfieldsarray($table){
 $cres=getsortedfields($table);
  while($cqd = mysql_fetch_array($cres)){
   $lf++;
   $fieldn=$cqd["fieldid"];
   $ft=$cqd["type"];
   if ($lf==1){
    $GLOBALS["orderby"]=$fieldn;
   }
   $fielddesc=$cqd["fielddesc"];
   if ($fielddesc==""){
    $fielddesc=$fieldn;
   }
   $GLOBALS["iufid"][$fieldn]=$fieldn;
   $GLOBALS["iufdesc"][$fieldn]=$fielddesc;
   $GLOBALS["iuft"][$fieldn]=$ft;
  }
}

Function gettablecontents($ltable){
 $sqlx=" SELECT * FROM ".$ltable
 ;

 //echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
  return $cresult ;
}

Function getfilteredtablecontents($ltable,$fid,$fv){
 $sqlx=" SELECT * FROM ".$ltable."
 where $fid='$fv'";
 if ($ltable=="contact"){
 $sqlx.=" order by surname";
 }
 ;
 //echo "fff $sqlx";

$cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
  return $cresult ;
}


Function dofield($fieldn,$itype,$modex,$dv){
  $dv=stripslashes($dv);
  if ($itype!="hidden"){
  $dfx.="<TR width=\"100\"><TD class=\"formlabel\">".
  $fieldn."</TD><TD class=\"formdata\">" ;
  }
 if (($_SESSION["internaluser"])|($_SESSION["externalok"])){
  $dfx.="<INPUT class=formdata type=\"".$itype."\" name=\"".$fieldn."\" ";
  if ($dv!=""){
   $dfx.="value=\"".$dv."\" ";
  }
  $dfx.=" size=\"50\">";
  }
  else{
  $dfx.=$dv;
 }
 if ($itype!="hidden"){
  $dfx.="</TD></TR>";
 }
 return $dfx;
}


function dotextfield($fieldn,$dv){
  $dv=stripslashes($dv);
  $dx.="<TR width=100><TD class=formlabel>".
  $fieldn."</TD><TD class=formdata>" ;
  if ($_SESSION["internaluser"]){
   $dx.="<TEXTAREA class=formdata name=\"".$fieldn."\" cols=50 rows=10>";
   if ($dv!=""){
    $dx.=$dv;
   }
   $dx.="</TEXTAREA></TD></TR>";
   }
   else{

  }
  return $dx;
 }



function dodatefield($fieldn,$itype,$modex,$dv){
  if ($itype!="hidden"){
  echo "<TR width=100><TD class=formlabel>".
  $fieldn."</TD><TD class=formdata>" ;
  }
  if ($_SESSION["internaluser"]){
   echo "<INPUT class=formdata type=\"".$itype."\" name=\"".$fieldn."\" ";
   if ($dv!=""){
    echo "value=\"".$dv."\" ";
   }
   echo " size=50 readonly>";
    if ($itype!="hidden"){
     echo "<TD>
           <A HREF=\"javascript:showCalendar(1,'".$fieldn."');\">
           <IMG SRC=\"10Min200011cal.gif\"
           WIDTH=\"39\" HEIGHT=\"21\" ALT=\"Click here
           to select date\" BORDER=\"0\"></A>
          </TD>
        </TR>";
   }
  }else{
  echo $dv;
 }
}


Function doselectfield($ltable,$fieldn,$idf,$ldesc,$modex,$dv){
  //echo $ltable."sf<BR>";
  $sfr=gettablecontents($ltable);
  echo "<TR width=100><TD class=formlabel>".
  $fieldn."</TD><TD class=formdata>" ;
  if ($_SESSION["externaluser"]){
   echo $dv;
   }else{
    echo "<SELECT class=formdata  name=\"".$fieldn."\" ";
    if ($_SESSION["onchangecode"]!=""){
     echo $_SESSION["onchangecode"];
    }
    echo ">";
//  echo "<OPTION>".$dv."</OPTION>";

   while($sfd = mysql_fetch_array($sfr)){
    $a =$sfd[$idf];
    $b =$sfd[$ldesc];
    if ($b==$dv){
    echo "<OPTION selected>".$b."</OPTION>";
    }else{
    echo "<OPTION>".$b."</OPTION>";
    }
   }
  echo " </SELECT>
         </TD></TR>";

 }
}


Function doselectfieldfilter($ltable,$fieldn,$idf,$ldesc,$modex,$dv,$fid,$fv,$ldesc2,$optv,$exptext){
  $sfr=getfilteredtablecontents($ltable,$fid,$fv);
  echo "<TR width=100><TD class=formlabel>".
  $fieldn."</TD><TD class=formlabel>" ;
  echo "<SELECT class=formdata  name=\"".$fieldn."\"> ";
  //echo "<OPTION value=".$dv.">".$optv."</OPTION>";
  while($sfd = mysql_fetch_array($sfr)){
   $a =$sfd[$idf];
   $b =$sfd[$ldesc];
   if ($ldesc2!=""){
   $c=$sfd[$ldesc2];
   }
   if ($b." ".$c==$dv){
   echo "<OPTION selected>".$dv."</OPTION>";
   }else{
   echo "<OPTION value=".$a.">".$b." ".$c."</OPTION>";
   }
  }
  echo "
          </SELECT>".$exptext."
         </TD>
        </TR>";
}


Function sendnewstuff($tableid){
$sqlx="select * from $tableid
 where uploaded is null
 and central".$tableid."id =0 ";
 return $sqlx;
}

Function addnewjob($a,$f,$eng){
$d="appt";
$b="appt";
$c=date('Y-m-d H:i:s');
$dd=date('Y-m-d H:i:s');
$e="new";
$sqlx="insert into jobs(customerid,jobtype,cost,sell,leaddate,duedate,origin,jobstatus,jobdescription,userid)
values(".$a.",'".$b."',0,0,'".$c."','".$dd."','".$d."','".$e."','".$f."','".$eng."')";
//echo $sqlx;

$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
$newid=mysql_insert_id();
return $newid;
}


Function findupermission($tablex,$uid,$cid){
$sqlx= "SELECT * FROM ".$tablex." WHERE userid='".$uid."' AND colleagueid='".$cid."' ";
$cresult = mysql_query($sqlx);
//	echo $sqlx;
if(!$cresult) error_message(sql_error());
if($qd = mysql_fetch_array($cresult)){
 return true;
 }
 else{
 return false;
 }
}

function getchargesavail(){
   $query = "SELECT * FROM charge
   ORDER BY chargedesc";
return $query;
}

function addtobasket($cid,$qty,$rate){
$sqlx="insert into basket
(sessionid,date,itemtype,chargeid,price,qty)
values('".session_id()."',
'".date("Y-m-d")."',
'service',
".$cid.",
".$rate.",
".$qty.")";
//echo $sqlx;

$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());

}


function getbasket(){
$sqlx="select * from basket as b
right outer join charge as c
on b.chargeid=c.chargeid
where b.sessionid='".session_id()."' ";
return $sqlx;
}

function killbasketline($c){
$sqlx="delete from basket
where sessionid='".session_id()."'
and lcount=".$c;
//echo $sqlx;
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());

}

function killbasket(){
$sqlx="delete from basket
where sessionid='".session_id()."' ";
//echo $sqlx;
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
//$_SESSION["jobid"]="";
//$_SESSION["apptid"]="";

}

function saveinvhead($c,$j,$n,$a,$d,$u){
$sqlx="insert into invoice(jobid,invoicedate,notes,customerid,apptid,userid)
values(".$j.",'".$d."','".$n."',".$c.",".$a.",'$u')";
//echo $sqlx;
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
}

function editinvhead($i,$n){
$sqlx="update invoice
 set notes='".$n."'
 where invoiceno=$i";
//echo $sqlx;
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
}

function getlastinv($c,$d){
$sqlx="select invoiceno from invoice
where invoicedate='".$d."'
and customerid=".$c;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
 $ino=$cqd["invoiceno"];
 return $ino;
 }
}

function saveinvline($invno,$c,$j,$p,$q,$cid,$cdesc,$d){
$ct="service";
$sqlx="insert into invoicelines(invoiceno,customerid,jobid,price,qty,chargeid,chargedesc,chargetype,invoicedate)
values(".$invno.",".$c.",".$j.",".$p.",".$q.",".$cid.",'".$cdesc."','".$ct."','".$d."')";
//echo $sqlx;
 tfw("invline.txt",$sqlx,false);
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
}

Function empdiarycount($d1,$d2,$emp){
$sqlx="SELECT sum(duration) as hours
     FROM diary
     WHERE userid='".$emp."'
     AND dte>='".$d1."'
     AND dte<='".$d2."' ";
//echo $sqlx;
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
if($qd = mysql_fetch_array($cresult)){
$hours=$qd["hours"];
return $hours;
}
}

Function empdiarycountcharged($d1,$d2,$emp){
$sqlx="SELECT sum(duration) as hours
     FROM diary
     WHERE userid='".$emp."'
     AND dte>='".$d1."'
     AND dte<='".$d2."'
     and jobid>0
     ";
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
if($qd = mysql_fetch_array($cresult)){
$hours=$qd["hours"];
return $hours;
}
}



function readdiarycusts($d1,$d2){
 $sqlx="SELECT distinct(il.customerid) as custid,
	Sum(il.qty * il.price) as invtot,
	sum(il.qty) as hours,
	C.companyname as cn
	FROM invoicelines as il
	right outer join customer as C on il.customerid=C.customerid
	WHERE invoicedate>='".$d1."' AND invoicedate<='".$d2."'
	group by C.CompanyName, custid
	order by C.CompanyName";
//echo $sqlx;
return $sqlx;
}


Function weekcharges($ci,$d1,$d2){
$sqlx=" SELECT * FROM invoicelines as l
left outer join invoice as v
on l.invoiceno=v.invoiceno
left outer join jobs as j
on l.jobid=j.jobid
left outer join diary as d
on l.invoiceno=d.chargeid
Where l.invoicedate>='".$d1."'
AND l.invoicedate<='".$d2."'
AND l.customerid='".$ci."'
order by l.invoiceno";
return $sqlx;
}


function readnote($custid,$subj){
$sqlx="select * from custnotes
where customerid=".$custid." and
subject='".$subj."' ";
//echo $sqlx;
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
 if($qd = mysql_fetch_array($cresult)){
  $note=$qd["note"];
  return $note;
 }
}

function contactexists($custid,$fname,$surname){
$sqlx="select * from contact
where customerid=".$custid;
if ($firstname!=''){
$sqlx.="
and firstname='".$firstname."' ";
}

if ($surname!=''){
$sqlx.="
and surname='".$surname."' ";
}

$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
 if($qd = mysql_fetch_array($cresult)){
  return true;
 }
return false;
}

function newcontact($custid,$fname,$surname){
$sqlx="insert into contact(customerid,firstname,surname)
values(".$custid.",'".$fname."','".$surname."')";
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());

}

function greendiarywithjob($jobid,$aid){
$sqlx="update diary set jobid=".$jobid."
where apptid=".$aid;
//echo $sqlx;
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());

}

function getusernote($userid){
$sqlx="select * from usernote where userid='".$userid."' ";
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
 if($qd = mysql_fetch_array($cresult)){
  return $qd["note"];
 }
}

function usernewcalls($userid,$fromothersonly){
 $sqlx="SELECT * FROM call as j
 left outer join customer as c on
 j.customerid=c.customerid
 WHERE j.userid='".$userid."' ";
 if ($fromothersonly){
  $sqlx.="and (j.enteredby is null or j.enteredby <>'".$userid."')" ;
 }
 $sqlx.=" and (j.completed is null or j.completed='' or j.completed='0')";
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
  return $cresult ;
}

function usercallbacks($userid,$ddate){
 $sqlx="SELECT * FROM call as j
 left outer join customer as c on
 j.customerid=c.customerid
 WHERE j.userid='".$userid."'
 and j.callback<='".$ddate."'
 and j.callback>0
 and (completed='0' or completed ='')";
// echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
  return $cresult ;
}

function userapptcallbacks($userid,$ddate){
 $sqlx="SELECT * FROM diary as j
 left outer join customer as c on
 j.customerid=c.customerid
 WHERE j.userid='".$userid."'
 and j.callbackdate<='".$ddate."'
 and j.callbackdate>0
 and (completed='0' or completed ='' or completed is null)";
//echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
  return $cresult ;
}


Function readjobstatus(){
 $sqlx="SELECT * FROM jobstatus";
 return $sqlx;
}
Function readjobtypes(){
 $sqlx="SELECT * FROM jobtype";
 return $sqlx;
}

Function findnotifyrule($mvo,$sl,$userx,$methd){
$sqlx= "SELECT * FROM userservice WHERE userid='$userx'
AND serviceid='$sl'
and deliverymethod='$methd'";
//echo $sqlx." - ".$mvo;

$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
while ($qd = mysql_fetch_array($cresult)){
// echo "mvo:".$qd[$mvo];
 if ($qd[$mvo]=="on"){
  return true;
 }
 else{
 return false;
 }
}
}

function returncompany($customerid){
	if($customerid>0){
	$sqlx="select companyname from customer
	where customerid=$customerid";
	$cresult = mysql_query($sqlx);
	if(!$cresult) error_message(sql_error());
		if ($qd = mysql_fetch_array($cresult)){
		 return $qd["companyname"];
		}
	}
}

function returncustfield($customerid,$fieldn){
$sqlx="select $fieldn from customer
where customerid=$customerid";
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
if ($qd = mysql_fetch_array($cresult)){
 return $qd[$fieldn];
}

}

function returncontact($contactid){
$sqlx="select concat(firstname,' ',surname) as cname from contact
where contactid=$contactid";
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error($sqlx));
if ($qd = mysql_fetch_array($cresult)){
 return $qd["cname"];
}

}


function getmail($user){
$sqlx="select email from users
where userid='$user'";
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
if ($qd = mysql_fetch_array($cresult)){
 return $qd["email"];
}
}

function getusername($user){
$sqlx="select firstname,surname from users
where userid='$user'";
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
if ($qd = mysql_fetch_array($cresult)){
 $unx=ucwords($qd["firstname"]." ".$qd["surname"]);
 return $unx;
}
}

function htmlhead($image){
$hhead="
<style type=\"text/css\">
.white 		{
		font-family : Tahoma, Arial;
		font-size : 8pt;
		font-weight : bold;
		color : white;
		text-align : left;
		text-decoration:none;
		}

</style>
<HTML>
<TABLE   valign=\"top\" border=0>
 <TR valign=top>
  <TD><img src=\"".$image."\"></TD>
 </TR>
 <TR  valign=top bgcolor=\"#3E4651\">
  <TD valign=top class=white>Infomaniac CRM</TD>
 </TR>
</TABLE>";
return $hhead;

}

function doineedtonotify($service,$meth,$operator,$user){
 $sqlx="select * from userservice
 where userid='$user'
 and serviceid='$service'
 and deliverymethod='$meth'
 ";
// echo "<BR>".$sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if ($qd = mysql_fetch_array($cresult)){
  $self=$qd["self"];
  $others=$qd["others"];
 //2 rules to check - for same, for diff
  if ($operator==$user){
   if ($self=="on"){
    return true;
   }
  }
  if ($operator!=$user){
//   echo "<BR>".$operator." not ".$user;
   if ($others=="on"){
//    echo "<BR> should not see ".$others;
    return true;
   }
  }
 }
 return false;
}

function chargeagainstdiary($a,$invno){
$sqlx="update diary
set chargeid=$invno
where apptid=$a ";
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());

}

function readinvoice($invno){
$sqlx="select * from invoice
where invoiceno=$invno ";
//echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($iqd = mysql_fetch_array($cresult)){
  return $iqd ;
 }
}

function readinvoicelt($refno){
$sqlx="select iv.invoiceno,iv.invoicedate,iv.customerid,iv.notes,
iv.userid,iv.contactid,iv.shipto,
c.customerid,c.companyname,c.shipping1,c.shipping2,c.shipsuburb,c.mail1,c.mail2,c.mailsuburb,c.postcode,c.state,c.costcentre,
co.contactid,co.firstname,co.surname,co.phone,c.fax,co.mobile,co.email
from invoice as iv
left outer join customer as c
on iv.customerid=c.customerid
left outer join contact as co
on iv.contactid=co.contactid
where iv.invoiceno=$refno
";
//echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($iqd = mysql_fetch_array($cresult)){
  return $iqd ;
 }
}


function readinvlines($invno){
$sqlx="select * from invoicelines
where invoiceno=$invno ";
//echo $sqlx;
 $lresult = mysql_query($sqlx);
 if(!$lresult) error_message(sql_error());
  return $lresult ;

}

Function empdiarycountbilledthisperiod($d1,$d2,$emp){
$sqlx="SELECT sum(qty) as hours
     FROM invoicelines as il
     right outer join diary as d
     on il.invoiceno=d.chargeid
     WHERE d.userid='".$emp."'
     AND il.invoicedate>='".$d1."'
     AND il.invoicedate<='".$d2."'
     and il.chargetype='service'
     ";
//echo "<BR>".$sqlx;

$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
if($qd = mysql_fetch_array($cresult)){
$bhours=$qd["hours"];
return $bhours;
}
}

Function empdiarycountbilledever($d1,$d2,$emp){
$sqlx="SELECT sum(qty) as hours
     FROM invoicelines as il
     right outer join diary as d
     on il.invoiceno=d.chargeid
     WHERE d.userid='".$emp."'
     AND d.dte>='".$d1."'
     AND d.dte<='".$d2."'
     and il.chargetype='service'
     ";
//echo "<BR>".$sqlx;

$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
if($qd = mysql_fetch_array($cresult)){
$bhours=$qd["hours"];
return $bhours;
}
}


function workedcusts($d1,$d2){
$sqlx="SELECT distinct d.customerid,c.companyname from diary as d
     right outer join customer as c
     on d.customerid=c.customerid
     where d.dte>='".$d1."'
     AND d.dte<='".$d2."' ";
return $sqlx;
}


Function custdiarycountbilledthisperiod($d1,$d2,$cust){
$sqlx="SELECT sum(qty) as hours
     FROM invoicelines as il
     right outer join diary as d
     on il.invoiceno=d.chargeid
     WHERE d.customerid='".$cust."'
     AND il.invoicedate>='".$d1."'
     AND il.invoicedate<='".$d2."'
     and il.chargetype='service'
     ";
//echo "<BR>".$sqlx;

$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
if($qd = mysql_fetch_array($cresult)){
$bhours=$qd["hours"];
return $bhours;
}
}

Function custdiarycountbilledever($d1,$d2,$cust){
$sqlx="SELECT sum(qty) as hours
     FROM invoicelines as il
     right outer join diary as d
     on il.invoiceno=d.chargeid
     WHERE d.customerid='".$cust."'
     AND d.dte>='".$d1."'
     AND d.dte<='".$d2."'
     and il.chargetype='service'
     ";
//echo "<BR>".$sqlx;

$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
if($qd = mysql_fetch_array($cresult)){
$bhours=$qd["hours"];
return $bhours;
}
}

Function custdiarycount($d1,$d2,$cust){
$sqlx="SELECT sum(duration) as hours
     FROM diary
     WHERE customerid='".$cust."'
     AND dte>='".$d1."'
     AND dte<='".$d2."' ";
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
if($qd = mysql_fetch_array($cresult)){
$hours=$qd["hours"];
return $hours;
}
}

Function custdiarycountcharged($d1,$d2,$cust){
$sqlx="SELECT sum(duration) as hours
     FROM diary
     WHERE customerid='".$cust."'
     AND dte>='".$d1."'
     AND dte<='".$d2."'
     and jobid>0
     ";
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
if($qd = mysql_fetch_array($cresult)){
$hours=$qd["hours"];
return $hours;
}
}


Function doselectdistinctfield($ltable,$fieldn,$idf,$ldesc,$modex,$dv){
  //echo $ltable."sf".$ldesc."<BR>";
  $sfr=getdistincttablecontents($ltable,$fieldn);
  echo "<TR width=100><TD class=formlabel>".
  $fieldn."</TD><TD>" ;
  echo "<SELECT class=formdata  name=\"".$fieldn."\"> ";
  echo "<OPTION>".$dv."</OPTION>";

   while($sfd = mysql_fetch_array($sfr)){
    $a =$sfd[$idf];
    $b =$sfd[$ldesc];
    $c =$sfd[$fieldn];
    //using actual field for distinct searches
    echo "<OPTION>".$c."</OPTION>";
   }
  echo "
          </SELECT>
         </TD></TR>";

}

Function getdistincttablecontents($ltable,$fieldx,$ftcount){
 //$ltable="customer";
 //hard coded untill needed elsewhere.
 //only on customer filter at 13.1.2003;
  $sqlx=" SELECT distinct ".$fieldx;
  if($GLOBALS["countfilters"]){
  $sqlx.=",count($ftcount) as countv";
  }
  $sqlx.=" FROM ".$ltable ." group by $fieldx";
//echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
  return $cresult ;
}


function xcountusercallbacks($userid,$ddate){
 $sqlx="SELECT count(callid) as tc FROM call
 WHERE userid='".$userid."'
 and callback<='".$ddate."'
 and callback>0
 and (completed='0' or completed ='')";
// echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($qd = mysql_fetch_array($cresult)){
 $tc=$qd["tc"];
 return $tc;
 }
}

function countusercallbacks($userid,$ddate,$odueonly=false){
 $sqlx="SELECT count(phonecallid) as tc FROM phonecall
 WHERE userid='".$userid."'";
 if($odueonly){
  $sqlx.=" and callback<='$ddate'";
 }
 $sqlx.="
 and callback>0
 and (completed='0' or completed ='' or completed is null)";
 //echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($qd = mysql_fetch_array($cresult)){
 $tc=$qd["tc"];
 return $tc;
 }
}


function countusercallsmade($userid,$ddate){
 $sqlx="SELECT count(callid) as tc FROM call
 WHERE userid='".$userid."'
 and date='".$ddate."'
 ";
// echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($qd = mysql_fetch_array($cresult)){
 $tc=$qd["tc"];
 return $tc;
 }
}

Function countuseropenjobs($userid){
 $sqlx="SELECT count(jobid) as ji FROM jobs
 WHERE userid='".$userid."'
 and jobstatus<>'closed'
 ";
 //echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($qd = mysql_fetch_array($cresult)){
 $tc=$qd["ji"];
 return $tc;
 }
}

Function readasset($custid,$assetno){
 $sqlx="
 SELECT * FROM assets
 WHERE customerid=".$custid."
 and assetno=".$assetno;
// echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
  return $cqd ;
 }
}

Function arrayreadoluser($userid){
 $sqlx="
 SELECT c.companyname,ol.oluserid,ol.contactid,ol.firstname,ol.surname,
ol.priceband,ol.password,ol.discountA,ol.notes,ol.customerid,
ol.discountB,ol.showstock,ol.paymentoption,ol.phone,ol.fax,ol.email,ol.shipline1,
ol.shipline2,ol.billingline1,ol.billingline2,ol.billingline3,ol.showimages,
ol.access
 FROM olusers as ol
 right outer join customer as c
 on ol.customerid=c.customerid
 WHERE oluserid='".$userid."' ";
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
  return $cqd ;
 }
}

Function readextuser($userx){
$sqlx= "select * from olusers as ol
where oluserid='".$userx."' ";
return $sqlx;
}

function modok($n){
$sqlx="select * from module
where moduleid='$n' ";
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
 return true;
 }
 return false;
}

function editok($n){
$sqlx="select cmodify from userprofilerights
where userprofiledesc='".$_SESSION["uprofile"]."'
and tableid='$n' ";
//echo "eeee $sqlx";
$cresult = mysql_query($sqlx);
if($cqd = mysql_fetch_array($cresult)){
 if ($cqd["cmodify"]){
  return true;
 }
}
return false;
}

function readok($n){
$sqlx="select cread from userprofilerights
where userprofiledesc='".$_SESSION["uprofile"]."'
and tableid='$n' ";
tfw("rok.txt",$sqlx,true);
$cresult = mysql_query($sqlx);
if($cqd = mysql_fetch_array($cresult)){
 if ($cqd["cread"]){
  return true;
 }
}
return false;
}

function killok($n){
$sqlx="select cdelete from userprofilerights
where userprofiledesc='".$_SESSION["uprofile"]."'
and tableid='$n' ";
//echo "<br>kkk $sqlx";
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
if($cqd = mysql_fetch_array($cresult)){
 if ($cqd["cdelete"]){
  return true;
 }
}
return false;
}


Function unflaggeddetailcharges($ci){
$sqlx=" SELECT distinct l.invoiceno,l.linec,v.jobid,
d.userid,d.dte,l.chargeid,l.price,l.invoicedate,l.chargetype,
l.chargedesc,l.qty,v.notes  FROM invoicelines as l
left outer join invoice as v
on l.invoiceno=v.invoiceno
left outer join jobs as j
on l.jobid=j.jobid
left outer join diary as d
on v.apptid=d.apptid
where v.externallyinvoiced<>'on'
AND l.customerid=".$ci."
and l.chargetype='service'
group by l.invoiceno,l.linec
order by l.jobid";
//echo $sqlx;
//group by l.chargeid
return $sqlx;
}

function unflaggeddiarycusts($d1,$d2){
 $sqlx="SELECT distinct(il.customerid) as custid,
	Sum(il.qty * il.price) as invtot,
	sum(il.qty) as hours,il.chargetype,
	C.companyname as cn
	FROM invoicelines as il
	right outer join invoice as inv
	on il.invoiceno=inv.invoiceno
	right outer join customer as C on il.customerid=C.customerid
        WHERE inv.externallyinvoiced <>'on'
        and (il.qty * il.price)<>0
        and il.chargetype='service'
	group by C.CompanyName, custid
	order by C.CompanyName";
//echo $sqlx;
return $sqlx;
}




function periodcharges($d1,$d2){
$sqlx=" SELECT distinct il.invoiceno as invoiceno,il.invoicedate as invoicedate,il.chargetype,
     il.customerid as customerid,
     c.companyname,
     Sum(il.qty * il.price) as invtot, iv.jobid
     FROM invoicelines as il
     right outer join invoice as iv on
     il.invoiceno=iv.invoiceno
     right outer join customer as c on
     il.customerid=c.customerid
     Where il.invoicedate>= '".$d1."'
     AND il.invoicedate<='".$d2."'
     and il.customerid<>''
     and il.chargetype='service'
     group by il.invoiceno,iv.jobid,il.invoicedate, il.customerid";
return $sqlx;
}

function unflaggedcharges(){
$sqlx=" SELECT distinct il.invoiceno as invoiceno,il.invoicedate as invoicedate,il.chargetype,
     il.customerid as customerid,
     c.companyname,
     Sum(il.qty * il.price) as invtot, iv.jobid
     FROM invoicelines as il
     right outer join invoice as iv on
     il.invoiceno=iv.invoiceno
     right outer join customer as c on
     il.customerid=c.customerid
     where iv.externallyinvoiced<>'on'
     and il.customerid<>''
     and il.chargetype='service'
     group by il.invoiceno,iv.jobid,il.invoicedate, il.customerid
     order by c.companyname";
//echo $sqlx;
return $sqlx;
}

function chooseimage($logoid){
$sqlx="select logolink from logos
where logoid='".$logoid."'";
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($qd = mysql_fetch_array($cresult)){
 $imgx=$qd["logolink"];
 return $imgx;
 }
}

function killinvlines($i){
$sqlx="delete from invoicelines
where invoiceno=".$i;
//echo $sqlx;
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
}

function amiadmin(){
 if ($_SESSION["administrator"]){
  return true;
 }
 else{
  return false;
 }
}

function navlinkbar($reset,$replace,$thislevel){
 $cn=$_SESSION["currentnav"];
 if ($replace){
  $la=strrpos($cn,"=");
  $cn=substr($cn,0,$la);
 }
 //echo "la=".$la;
 if ($reset){
  $cn=$thislevel;
 }
 $cn.="=>".$thislevel;
 $_SESSION["currentnav"]=$cn;
 return "<TR class=cnav><TD>".$cn."</TD></TR>";
}

function apptreport($date,$user){
$sqlx="select * from diary as d
right outer join customer as c
on d.customerid=c.customerid
where d.userid='$user'
and d.dte='$date'
order by start";
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
return $cresult;

}

Function readprod($prodid){
 $sqlx="
 SELECT * FROM inventory
 WHERE prodid='$prodid' ";
// echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
  return $cqd ;
 }
}

Function readolprodgroups(){
 $sqlx="
 SELECT * FROM olprodgroup
 ";
// echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 return $cresult ;
}


Function inwhichgroups($prodid){
$sqlx="select * from onlineparts
where prodid='$prodid'";
//echo $sqlx;
$aresult = mysql_query($sqlx);
if(!$aresult) error_message(sql_error());
return $aresult ;

}

Function  readsuppliers($resvq="res"){
$sqlx="SELECT * FROM customer as c
right outer join custtype as ct
on c.custtype=ct.custtypedesc
where ct.issupplier='on'
order by c.companyname";
//echo $sqlx;
$GLOBALS["crsqlx"]=$sqlx;
switch($resvq){
 case "q":
 return $sqlx;
 break;
 default:
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 return $cresult ;
}

}

Function readprodsuppliers($prodid){
$sqlx="select * from prodsuppliers as ps
left outer join customer as c
on ps.supplierid=c.customerid
where ps.prodid='$prodid'
and (ps.supplierid!='' and ps.supplierid!='0')
and c.customerid is not null
";
//echo $sqlx;
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
return $cresult ;

}

Function  issupplier($custid){
if($custid>0){
$sqlx="SELECT custtype FROM customer as c
right outer join custtype as ct
on c.custtype=ct.custtypedesc
where ct.issupplier='on'
and c.customerid=$custid";
//etest($sqlx);
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
  return true ;
 }
}
return false ;

}

Function  iscust($custid){
 if($custid=="") return false;
 if($custid<=0) return false;
$sqlx="SELECT custtype FROM customer as c
right outer join custtype as ct
on c.custtype=ct.custtypedesc
where ct.iscust='on'
and c.customerid=$custid";
tfw("isc.txt",$sqlx,true);
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
  return true ;
 }
return false ;

}

function readporder($poref){
$sqlx="select * from purchaseorders
where poref=$poref ";
//echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($iqd = mysql_fetch_array($cresult)){
  return $iqd ;
 }
}

function readsupplierorders($suppid){
$sqlx="select * from purchaseorders
where supplierid=$suppid ";
//echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 return $cresult ;
}
function readossupplierorders($suppid){
$sqlx="select distinct podate,poref from polines
where supplierid=$suppid
and qty>receivedqty
group by poref,podate
order by poref
";
//echo "<br>ooo $sqlx";
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 return $cresult ;
}


function readporderforbasket($poref){
$sqlx="select p.poref,p.podate,p.supplierid,p.pocomments,
c.customerid,c.companyname,c.shipping1,c.shipping2,c.shipsuburb,c.mail1,c.mail2,c.mailsuburb,c.postcode,c.state,c.costcentre
from purchaseorders as p
left outer join customer as c
on p.supplierid=c.customerid
where poref=$poref";
//echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
  if($iqd = mysql_fetch_array($cresult)){
   return $iqd ;
  }
}

function readsorderforbasket($soref){
$sqlx="select s.soref,s.sodate,s.customerid,s.notes,s.internalnotes,s.custordref,s.notbefore,
c.customerid,c.companyname,c.shipping1,c.shipping2,c.shipsuburb,c.mail1,c.mail2,c.mailsuburb,c.postcode,c.state,c.costcentre
from salesorder as s
left outer join customer as c
on s.customerid=c.customerid
where soref=$soref";
//echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
  if($iqd = mysql_fetch_array($cresult)){
   return $iqd ;
  }

}

function readrfqforbasket($rfq){
$sqlx="select p.supplierid,p.rfqcomment,p.rfqintnotes,
c.customerid,c.companyname,c.shipping1,c.shipping2,c.shipsuburb,c.mail1,c.mail2,c.mailsuburb,c.postcode,c.state,c.costcentre
from rfq as p
left outer join customer as c
on p.supplierid=c.customerid
where rfqno=$rfq";
//echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
  if($iqd = mysql_fetch_array($cresult)){
   return $iqd ;
  }
}

function readcnforbasket($cn){
$sqlx="select s.con_noteid,s.shipdate,s.customerid,s.notes,s.custordref,s.shipto,
c.customerid,c.companyname,c.shipping1,c.shipping2,c.shipsuburb,c.mail1,c.mail2,c.mailsuburb,c.postcode,c.state,c.costcentre
from consignment as s
left outer join customer as c
on s.customerid=c.customerid
where s.con_noteid=$cn";
//echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
  if($iqd = mysql_fetch_array($cresult)){
   return $iqd ;
  }

}


function readpolines($poref){
$sqlx="select * from polines as pl
left outer join inventory as p
on pl.prodid=p.prodid
where poref=$poref ";
//echo $sqlx;
 $lresult = mysql_query($sqlx);
 if(!$lresult) error_message(sql_error());
  return $lresult ;
}

function readrfqlines($rfq){
$sqlx="select * from rfqlines as rl
left outer join inventory as p
on rl.prodid=p.prodid
left outer join prodsuppliers as ps
on ps.prodid=p.prodid
where rl.rfqno=$rfq
and (ps.preferred='on' or ps.supplierid is null)
";
//echo $sqlx;
 $lresult = mysql_query($sqlx);
 if(!$lresult) error_message(sql_error());
  return $lresult ;
}

function readsolines($soref){
$sqlx="select * from solines as sl
left outer join inventory as p
on sl.prodid=p.prodid
left outer join customer as c
on sl.customerid=c.customerid

where soref=$soref ";
//echo $sqlx;
 $lresult = mysql_query($sqlx);
 if(!$lresult) error_message(sql_error());
  return $lresult ;
}

function readsolinestype($soref,$ct){
$sqlx="select * from solines as sl";
switch ($ct){
 case "product":
 $sqlx.=" left outer join inventory as p
 on sl.prodid=p.prodid";
 break;
 case "nonstock":
 $sqlx.=" left outer join charge as p
 on sl.prodid=p.chargeid";
 break;
}
$sqlx.=" where soref=$soref
and chargetype='$ct'
";
//echo $sqlx;
 $lresult = mysql_query($sqlx);
 if(!$lresult) error_message(sql_error());
  return $lresult ;

}


function readcnlines($cn){
$sqlx="select * from consignlines as sl
left outer join inventory as p
on sl.prodid=p.prodid
where con_noteid=$cn ";
//echo $sqlx;
 $lresult = mysql_query($sqlx);
 if(!$lresult) error_message(sql_error());
  return $lresult ;
}



function readcredit($refno){
$sqlx="select cr.crid,cr.crdate,cr.customerid,cr.notes,
cr.userid,cr.shipto,
c.customerid,c.companyname,c.shipping1,c.shipping2,c.shipsuburb,c.mail1,c.mail2,c.mailsuburb,c.postcode,c.state,c.costcentre,
co.contactid,co.firstname,co.surname,co.phone,c.fax,co.mobile,co.email
from crnote as cr
left outer join customer as c
on cr.customerid=c.customerid
left outer join contact as co
on cr.contactid=co.contactid
where cr.crid=$refno
";
//echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($iqd = mysql_fetch_array($cresult)){
  return $iqd ;
 }
}


function readquote($refno){
$sqlx="select q.refno,q.quotedate,q.customerid,q.qnote,
q.userid,q.followupdate,q.contactid,q.qstatus,q.shipto,
c.customerid,c.companyname,c.shipping1,c.shipping2,c.shipsuburb,c.mail1,c.mail2,c.mailsuburb,c.postcode,c.state,c.costcentre,
co.contactid,co.firstname,co.surname,co.phone,c.fax,co.mobile,co.email
from olquotes as q
left outer join customer as c
on q.customerid=c.customerid
left outer join contact as co
on q.contactid=co.contactid
where refno=$refno
";
//echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($iqd = mysql_fetch_array($cresult)){
  return $iqd ;
 }


}


function readquotelines($refno,$section){
$sqlx="select * from olquotelines as olq
left outer join inventory as p
on olq.prodid=p.prodid
where refno=$refno";


if ($section!=""){
$sqlx.="
 and sectionid='$section'
";
}

//echo $sqlx;
 $lresult = mysql_query($sqlx);
 if(!$lresult) error_message(sql_error());
  return $lresult ;

}

function readquotesection($refno){
/*
$sqlx="select * from olquotesection
where refno=$refno
order by sorder
";
*/
//did this to get historical lines
$sqlx="select distinct l.sectionid,h.comments, 1 as sorder
from olquotelines as l
left outer join olquotesection as h
on l.sectionid=h.sectionid
where l.refno=$refno
group by sectionid
";
//echo $sqlx;
 $lresult = mysql_query($sqlx);
 if(!$lresult) error_message(sql_error());
  return $lresult ;

}

function reloadquotesection($refno){
$sqlx="select * from olquotesection
where refno=$refno
order by sorder
";
//echo $sqlx;
 $lresult = mysql_query($sqlx);
 if(!$lresult) error_message(sql_error());
  return $lresult ;

}


function activeprodgroups(){
$sqlx="select distinct prodgroup,count(prodid) as pcount from inventory
group by prodgroup";
return $sqlx;
}

function getprodsingroup($pg){
$sqlx="select * from inventory
where prodgroup='".$pg."'
order by prodid";
//echo $sqlx;
return $sqlx;

}

function clearbasket(){
unset ($_SESSION["basket"]["prodid"]);
unset ($_SESSION["basket"]["qty"]);
unset ($_SESSION["basket"]["price"]);
unset ($_SESSION["basket"]["ld"]);
unset ($_SESSION["basket"]["sd"]);
unset ($_SESSION["invcname"]);
unset ($_SESSION["invcust"]);
unset ($_SESSION["invemail"]);
unset ($_SESSION["invad1"]);
unset ($_SESSION["invad3"]);
unset ($_SESSION["invad3"]);
unset ($_SESSION["binvad1"]);
unset ($_SESSION["binvad3"]);
unset ($_SESSION["binvad3"]);
unset ($_SESSION["qno"]);
unset ($_SESSION["editqno"]);
unset ($_SESSION["basketnotes"]);
unset ($_SESSION["qnamex"]);
unset ($_SESSION["cid"]);
unset ($_SESSION["qstatus"]);
unset ($_SESSION["basketheads"]);
unset ($_SESSION["basketheadlines"]);
 unset ($_SESSION["qbasket"]["sorder"]);
 unset ($_SESSION["qbasket"]["stitles"]);
 unset ($_SESSION["qbasket"]["scomm"]);
 unset ($_SESSION["activehead"]);
 unset ($_SESSION["clausin"]);
$_SESSION["qblc"]=10;
unset($GLOBALS["slab"]);
unset($GLOBALS["mlab"]);
loadstandardqclause();
}

function clearinvbasket(){
unset ($_SESSION["ibasket"]["prodid"]);
unset ($_SESSION["ibasket"]["qty"]);
unset ($_SESSION["ibasket"]["price"]);
unset ($_SESSION["ibasket"]["ld"]);
unset ($_SESSION["ibasket"]["sd"]);
unset ($_SESSION["ibasket"]["soref"]);
unset ($_SESSION["ibasket"]["poref"]);
unset ($_SESSION["invcname"]);
unset ($_SESSION["invemail"]);
unset ($_SESSION["iinvad1"]);
unset ($_SESSION["iinvad3"]);
unset ($_SESSION["iinvad3"]);
unset ($_SESSION["ibinvad1"]);
unset ($_SESSION["ibinvad3"]);
unset ($_SESSION["ibinvad3"]);
unset ($_SESSION["inv"]);
unset ($_SESSION["iinvdate"]);
unset ($_SESSION["editinv"]);
unset ($_SESSION["ibasketnotes"]);
unset ($_SESSION["qnamex"]);
unset ($_SESSION["cid"]);
unset ($_SESSION["iinvcust"]);
unset ($_SESSION["iinvcname"]);

unset ($_SESSION["ibasket"]["nscid"]);
unset ($_SESSION["ibasket"]["nscqty"]);
unset ($_SESSION["ibasket"]["nscsellp"]);
unset ($_SESSION["ibasket"]["nscdesc"]);

}

function clearsobasket(){
unset ($_SESSION["sobasket"]["prodid"]);
unset ($_SESSION["sobasket"]["qty"]);
unset ($_SESSION["sobasket"]["sellp"]);
unset ($_SESSION["sobasket"]["eprice"]);
unset ($_SESSION["sobasket"]["ld"]);
unset ($_SESSION["sobasket"]["sd"]);
unset ($_SESSION["sobasketnotes"]);
unset ($_SESSION["sobasketintnotes"]);
unset ($_SESSION["sobasket"]["qno"]);
unset ($_SESSION["pobasket"]["soref"]);
unset ($_SESSION["so"]);
unset ($_SESSION["soref"]);
unset ($_SESSION["editso"]);
unset ($_SESSION["soinvcust"]);
unset ($_SESSION["socust"]);
unset ($_SESSION["soinvemail"]);
unset ($_SESSION["soinvcname"]);
unset ($_SESSION["soinvad1"]);
unset ($_SESSION["sobinvad1"]);
unset ($_SESSION["soinvad2"]);
unset ($_SESSION["sobinvad2"]);
unset ($_SESSION["soinvad3"]);
unset ($_SESSION["sobinvad3"]);
unset ($_SESSION["sodate"]);
unset ($_SESSION["custordref"]);
unset ($_SESSION["socontname"]);
unset ($_SESSION["socid"]);
unset ($_SESSION["sobasket"]["nscid"]);
unset ($_SESSION["sobasket"]["nscqty"]);
unset ($_SESSION["sobasket"]["nscsellp"]);
unset ($_SESSION["sobasket"]["nscdesc"]);



if (!isset($_SESSION["sodate"])){
 $sodate  = date("Y-m-d",mktime (0,0,0,date("m")  ,date("d")+30,date("Y")));
 $_SESSION["sodate"]=$sodate  ;
}

}

function clearcnbasket(){
unset ($_SESSION["cnbasket"]["prodid"]);
unset ($_SESSION["cnbasket"]["qty"]);
unset ($_SESSION["cnbasket"]["sellp"]);
unset ($_SESSION["cnobasket"]["ld"]);
unset ($_SESSION["cnbasket"]["sd"]);
unset ($_SESSION["cnbasketnotes"]);
unset ($_SESSION["cnbasket"]["qno"]);
unset ($_SESSION["cnbasket"]["soref"]);
unset ($_SESSION["cn"]);
unset ($_SESSION["cnref"]);
unset ($_SESSION["editcn"]);
unset ($_SESSION["cncust"]);
unset ($_SESSION["cnmail"]);
unset ($_SESSION["cncname"]);
unset ($_SESSION["cnad1"]);
unset ($_SESSION["cnad1"]);
unset ($_SESSION["cnad2"]);
unset ($_SESSION["cnad2"]);
unset ($_SESSION["cnad3"]);
unset ($_SESSION["cnad3"]);
unset ($_SESSION["cndate"]);
unset ($_SESSION["cncustordref"]);

if (!isset($_SESSION["cndate"])){
 $cndate  = date("Y-m-d",mktime (0,0,0,date("m")  ,date("d")+30,date("Y")));
 $_SESSION["cndate"]=$cndate  ;
}

}

function clearcrbasket(){
unset ($_SESSION["crbasket"]["prodid"]);
unset ($_SESSION["crbasket"]["qty"]);
unset ($_SESSION["crbasket"]["sellp"]);
unset ($_SESSION["crbasket"]["eprice"]);
unset ($_SESSION["crbasket"]["ld"]);
unset ($_SESSION["crbasket"]["sd"]);
unset ($_SESSION["crbasketnotes"]);
unset ($_SESSION["crbasketintnotes"]);
unset ($_SESSION["crbasket"]["qno"]);
unset ($_SESSION["crbasket"]["soref"]);
unset ($_SESSION["cr"]);
unset ($_SESSION["editcr"]);
unset ($_SESSION["crcust"]);
unset ($_SESSION["crdate"]);
unset ($_SESSION["crcontname"]);
unset ($_SESSION["crinvcname"]);
unset ($_SESSION["crcid"]);
unset ($_SESSION["crbasket"]["nscid"]);
unset ($_SESSION["crbasket"]["nscqty"]);
unset ($_SESSION["crbasket"]["nscsellp"]);
unset ($_SESSION["crbasket"]["nscdesc"]);



if (!isset($_SESSION["crdate"])){
 $crdate  = date("Y-m-d",mktime (0,0,0,date("m")  ,date("d")+30,date("Y")));
 $_SESSION["crdate"]=$crdate  ;
}

}




function sessioniseonrecall($custid){
$_SESSION["invcust"]=$custid;
//echo "setinv"
$cqd=readcust($custid);
$cn=$cqd["companyname"];
$em=$cqd["email"];
$ad1=$cqd["shipping1"];
$ad2=$cqd["shipping2"];
$ad3=$cqd["shipsuburb"];
$bad1=$cqd["mail1"];
$bad2=$cqd["mail2"];
$bad3=$cqd["mailsuburb"]." ".$cqd["state"]." ".$cqd["postcode"];
$pl=$cqd["priceband"];
$_SESSION["invcname"]=$cn;
$_SESSION["invemail"]=$em;
$_SESSION["invad1"]=$ad1;
$_SESSION["invad3"]=$ad2;
$_SESSION["invad3"]=$ad3;
$_SESSION["binvad1"]=$bad1;
$_SESSION["binvad3"]=$bad2;
$_SESSION["binvad3"]=$bad3;
$_SESSION["pricelevel"]=$pl;
}

function posessioniseonrecall($custid){
$_SESSION["invcust"]=$custid;
//echo "setinv"
$cqd=readcust($custid);
$cn=$cqd["companyname"];
$em=$cqd["email"];
$ad1=$cqd["shipping1"];
$ad2=$cqd["shipping2"];
$ad3=$cqd["shipsuburb"];
$bad1=$cqd["mail1"];
$bad2=$cqd["mail2"];
$bad3=$cqd["mailsuburb"]." ".$cqd["state"]." ".$cqd["postcode"];
$pl=$cqd["priceband"];
$_SESSION["invcname"]=$cn;
$_SESSION["invemail"]=$em;
$_SESSION["invad1"]=$ad1;
$_SESSION["invad3"]=$ad2;
$_SESSION["invad3"]=$ad3;
$_SESSION["binvad1"]=$bad1;
$_SESSION["binvad3"]=$bad2;
$_SESSION["binvad3"]=$bad3;
$_SESSION["pricelevel"]=$pl;
}

function isessioniseonrecall($custid){
$_SESSION["iinvcust"]=$custid;
//echo "setinv"
$cqd=readcust($custid);
$cn=$cqd["companyname"];
$em=$cqd["email"];
$ad1=$cqd["shipping1"];
$ad2=$cqd["shipping2"];
$ad3=$cqd["shipsuburb"];
$bad1=$cqd["mail1"];
$bad2=$cqd["mail2"];
$bad3=$cqd["mailsuburb"]." ".$cqd["state"]." ".$cqd["postcode"];
$pl=$cqd["priceband"];
$_SESSION["iinvcname"]=$cn;
$_SESSION["iinvemail"]=$em;
$_SESSION["iinvad1"]=$ad1;
$_SESSION["iinvad3"]=$ad2;
$_SESSION["iinvad3"]=$ad3;
$_SESSION["ibinvad1"]=$bad1;
$_SESSION["ibinvad3"]=$bad2;
$_SESSION["ibinvad3"]=$bad3;
$_SESSION["ipricelevel"]=$pl;
}

function sosessioniseonrecall($custid){
$_SESSION["socust"]=$custid;
//echo "setinv"
$cqd=readcust($custid);
$cn=$cqd["companyname"];
$em=$cqd["email"];
$ad1=$cqd["shipping1"];
$ad2=$cqd["shipping2"];
$ad3=$cqd["shipsuburb"];
$bad1=$cqd["mail1"];
$bad2=$cqd["mail2"];
$bad3=$cqd["mailsuburb"]." ".$cqd["state"]." ".$cqd["postcode"];
$pl=$cqd["priceband"];
$nogst=$cqd["nogst"];
$_SESSION["soinvcname"]=$cn;
$_SESSION["soinvemail"]=$em;
$_SESSION["soinvad1"]=$ad1;
$_SESSION["soinvad3"]=$ad2;
$_SESSION["soinvad3"]=$ad3;
$_SESSION["sobinvad1"]=$bad1;
$_SESSION["sobinvad3"]=$bad2;
$_SESSION["sobinvad3"]=$bad3;
$_SESSION["sopricelevel"]=$pl;
}

function cnsessioniseonrecall($custid){
$_SESSION["cncust"]=$custid;
//echo "setinv"
$cqd=readcust($custid);
$cn=$cqd["companyname"];
$em=$cqd["email"];
$ad1=$cqd["shipping1"];
$ad2=$cqd["shipping2"];
$ad3=$cqd["shipsuburb"];
$bad1=$cqd["mail1"];
$bad2=$cqd["mail2"];
$bad3=$cqd["mailsuburb"]." ".$cqd["state"]." ".$cqd["postcode"];
$pl=$cqd["priceband"];
$_SESSION["cncname"]=$cn;
$_SESSION["cnemail"]=$em;
$_SESSION["cnad1"]=$ad1;
$_SESSION["cnad3"]=$ad2;
$_SESSION["cnad3"]=$ad3;
$_SESSION["cnad1"]=$bad1;
$_SESSION["cnad3"]=$bad2;
$_SESSION["cnad3"]=$bad3;
$_SESSION["cnpricelevel"]=$pl;
}

function crsessioniseonrecall($custid){
$_SESSION["crinvcust"]=$custid;
$_SESSION["crcust"]=$custid;
 //echo "setinv"
$cqd=readcust($custid);
$cn=$cqd["companyname"];
$em=$cqd["email"];
$ad1=$cqd["shipping1"];
$ad2=$cqd["shipping2"];
$ad3=$cqd["shipsuburb"];
$bad1=$cqd["mail1"];
$bad2=$cqd["mail2"];
$bad3=$cqd["mailsuburb"]." ".$cqd["state"]." ".$cqd["postcode"];
$pl=$cqd["priceband"];
$_SESSION["crinvcname"]=$cn;
$_SESSION["crpricelevel"]=$pl;
}



Function readusersa(){
$sqlx= "select * from users
where (inactive is null or inactive!='on')
order by userorder
";
//echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 while($qqd = mysql_fetch_array($cresult)){
  $ui=$qqd["userid"];
  $up=$qqd["userprofiledesc"];
  $ul=$qqd["ulevel"];
  $uina=$qqd["inactive"];
  $seq=$qqd["userorder"];
//echo $ui;
  $GLOBALS["usersa"][$ui]=$ui;
  $GLOBALS["ul"][$ui]=$ul;
  $GLOBALS["up"][$ui]=$up;
  $GLOBALS["uina"][$ui]=$uina;
  $GLOBALS["sseq"][$ui]=$seq;
  $GLOBALS["un"][$ui]=$qqd["firstname"]."".$qqd["surname"];
 }
}

Function readinvoiceusersa($m,$y){
$sqlx= "select distinct userid from invoice
where month(invoicedate)=$m
and year(invoicedate)=$y
";
//echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 while($qqd = mysql_fetch_array($cresult)){
  $ui=$qqd["userid"];
//echo $ui;
  $GLOBALS["usersa"][$ui]=$ui;
 }

}




Function qstatusa(){
$sqlx="select * from qstatus";
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 while($qqd = mysql_fetch_array($cresult)){
  $qs=$qqd["qstatusdesc"];
  $GLOBALS["qstatus"][$qs]=$qs;
 }
}

Function  quotesplit($yr,$mno){
unset($GLOBALS["usersa"]);
unset($GLOBALS["qstatus"]);
readusersa();
qstatusa();

$sqlx= "select ";
foreach ($GLOBALS["usersa"] as $ui) {
 foreach ($GLOBALS["qstatus"] as $qs) {
//echo "<BR>".$ui.$qs;
 $ic++;
 if ($ic>1){
  $sqlx.=",";
 }
 $sqlx.="SUM(IF((qh.qstatus = '".$qs."')&(qh.userid='".$ui."') , (ql.qty*ql.price), 0)) AS 't".$ui.$qs."' ";
 }
}
$sqlx.=" from olquotelines as ql
left outer join olquotes as qh
on ql.refno=qh.refno
where month(ql.quotedate)=$mno
and year(ql.quotedate)=$yr
 ";

//echo $sqlx;

$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
if($cqd = mysql_fetch_array($cresult)){
 $uqvx.="<TABLE border=0>";
 $uqvx.="<TR class=tableheadbar>";
  $uqvx.="<TD>User</TD>";
 foreach ($GLOBALS["qstatus"] as $qs) {
  $uqvx.="<TD>".$qs."</TD>";
 }
 $uqvx.="</TR>";

 foreach ($GLOBALS["usersa"] as $ui) {
  $utot=0;
  $userqv="<TR class=lstextstyle><TD>".$ui."</TD>";
  foreach ($GLOBALS["qstatus"] as $qs) {
   $qstrv="t".$ui.$qs;
   $dv=$cqd[$qstrv];
   $utot+=$dv;
   $userqv.="<TD align=right>$".number_format($dv,2)."</TD>";
  }
  $userqv.="</TR>";
  if ($utot>0){
  $uqvx.=$userqv;
  }
 }
 $uqvx.="</TABLE>";
}
echo $uqvx;
}

Function  invoicesplit($yr,$mno){
unset($GLOBALS["usersa"]);
unset($GLOBALS["qstatus"]);
//readusersa();
readinvoiceusersa($mno,$yr);
$invtypes=array("service","product");


if (!isset($GLOBALS["usersa"])){
 //where not there
$ui=$session["userid"];
$GLOBALS["usersa"][$ui]=$ui;
}

$sqlx= "select ";
foreach ($GLOBALS["usersa"] as $ui) {
 foreach ($invtypes as $ct) {
//echo "<BR>".$ui.$qs;
 $ic++;
 if ($ic>1){
  $sqlx.=",";
 }
 $sqlx.="SUM(IF((il.chargetype = '".$ct."')&(ih.userid='".$ui."') , (il.qty*il.price), 0)) AS 't".$ui.$ct."' ";
 }
}
$sqlx.=" from invoicelines as il
left outer join invoice as ih
on il.invoiceno=ih.invoiceno
where month(il.invoicedate)=$mno
and year(il.invoicedate)=$yr
 ";

//echo $sqlx;

$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
if($cqd = mysql_fetch_array($cresult)){
 $uqvx.="<TABLE border=0>";
 $uqvx.="<TR class=tableheadbar>";
  $uqvx.="<TD>User</TD>";
 foreach ($invtypes as $ct) {
  $uqvx.="<TD>".$ct."</TD>";
 }
 $uqvx.="</TR>";

 foreach ($GLOBALS["usersa"] as $ui) {
  $utot=0;
  $userqv="<TR class=lstextstyle><TD>".$ui."</TD>";
 foreach ($invtypes as $ct) {
   $qstrv="t".$ui.$ct;
   $dv=$cqd[$qstrv];
   if ($GLOBALS["pricesincgst"]){
    $gstcomponent=$dv/11;
    $dv=$dv-$gstcomponent;
   }

   $utot+=$dv;
   $userqv.="<TD align=right>$".number_format($dv,2)."</TD>";
  }
  $userqv.="</TR>";
  if ($utot>0){
  $uqvx.=$userqv;
  }
 }
 $uqvx.="</TABLE>";
}
echo $uqvx;
}


function readrules(){
 $sqlx=" SELECT * FROM pricerules ";
// echo $sqlx;
 return $sqlx;

// $cresult = mysql_query($sqlx);
// if(!$cresult) error_message(sql_error());
// return $cresult ;

}

function readrule($pr){
 $sqlx="
 SELECT * FROM pricerules
 WHERE pricegroup=$pr ";
// echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
  return $cqd ;
 }
}

function readruledesc($pr){
 $sqlx="
 SELECT * FROM pricerules
 WHERE pricegroupdesc='$pr' ";
 //echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
  return $cqd ;
 }
}

function sysvar($n){
$sqlx="select * from systemvariables
where varid='$n' ";
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
  if($cqd["vvalue"]){
   return true;
  }
 }
 return false;
}

function sysvalue($n,$nl=true){
$sqlx="select * from systemvariables
where varid='$n' ";
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
  $val=$cqd["vvalue"];
  if ($val!=""){
  	if($nl)  return nl2br($val);
  	return $val;
  }
 }
 //return false;
}

function sysvarcharval($x){
$sqlx="select * from systemvarchars
where varid='$x' ";
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
  $val=$cqd["vvalue"];
  if ($val!=""){
   return nl2br($val);
  }
 }
}

function readprodgroups(){
$sqlx="select * from prodgroup";
//echo $sqlx;
$cresult = mysql_query($sqlx);
return $cresult;
}

function findcustfromcontact($contactid){
$sqlx="select co.customerid, co.companyname from contact as c
right outer join customer as co
on c.customerid=co.customerid
where c.contactid=$contactid";
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
if ($qd = mysql_fetch_array($cresult)){
 return $qd["customerid"];
}
}

function findprospectfromcontact($contactid){
$sqlx="select co.customerid, co.companyname from prosectcontact as c
right outer join prospect as co
on c.customerid=co.customerid
where c.contactid=$contactid";
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
if ($qd = mysql_fetch_array($cresult)){
 return $qd["customerid"];
}
}


function rpathinfo(){
//$rv=getenv('PATH_INFO')."?".getenv('QUERY_STRING');
$rv=$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"];
return $rv;
}

function reloadinv($refno){
$sqlx="select i.invoiceno,i.invoicedate,i.customerid,i.notes,
c.customerid,c.companyname,c.shipping1,c.shipping2,c.shipsuburb,c.mail1,c.mail2,c.mailsuburb,c.postcode,c.state,c.costcentre
from invoice as i
left outer join customer as c
on i.customerid=c.customerid
where invoiceno=$refno
";
//echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 if($iqd = mysql_fetch_array($cresult)){
  return $iqd ;
 }


}

function reloadinvlines($refno){
$sqlx="select * from invoicelines as olq
left outer join inventory as p
on olq.prodid=p.prodid
where invoiceno=$refno ";
//echo $sqlx;
 $lresult = mysql_query($sqlx);
 if(!$lresult) error_message(sql_error());
  return $lresult ;
}

function reloadinvlinestype($refno,$ct){
$sqlx="select * from invoicelines as il";
switch ($ct){
 case "product":
 $sqlx.=" left outer join inventory as p
 on il.prodid=p.prodid";
 break;
 case "nonstock":
 $sqlx.=" left outer join charge as p
 on il.prodid=p.chargeid";
 break;
}
$sqlx.=" where invoiceno=$refno
and chargetype='$ct'
";
//echo $sqlx;
 $lresult = mysql_query($sqlx);
 if(!$lresult) error_message(sql_error());
  return $lresult ;

}



function reloadcnlines($refno){
$sqlx="select * from consignlines as cl
left outer join inventory as p
on cl.prodid=p.prodid
where con_noteid=$refno ";
//echo $sqlx;
 $lresult = mysql_query($sqlx);
 if(!$lresult) error_message(sql_error());
  return $lresult ;

}

function readqclause($refno){
$sqlx="select * from olclauses as olq
left outer join qclause as qcl
on olq.clauseid=qcl.clauseid
where refno=$refno ";
//echo $sqlx;
 $lresult = mysql_query($sqlx);
 if(!$lresult) error_message(sql_error());
  return $lresult ;

}

function loadstandardqclause(){
$sqlx="select * from qclause as qcl
where standard='on' ";
//echo $sqlx;
$res = mysql_query($sqlx);
return $res;
/*
if(!$res) error_message(sql_error());
 while($qqd = mysql_fetch_array($res)){
  $cid=$qqd["clauseid"];
  $_SESSION["clausin"][$cid]=$cid;
  $_SESSION["clausid"][$cid]=$cid;
 }
*/
}

function isfieldset($t,$f){
$sqlx="select * from tablename
where tableid='$t' and fieldid='$f'
and inuse='on'";
//echo $sqlx;
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
 return true;
 }
 return false;
}

function formaldate($olddate){
 $cd=date("D,jS F ,Y",strtotime($olddate));
 return $cd;
}

function fulldate($olddate){
 $cd=date("d M Y",strtotime($olddate));
 return $cd;
}

function inmailgroup($contactid,$mailid){
 $sqlx="select * from groupmembers
 where mailgroupid='$mailid'
 and contactid=$contactid";
 //echo $sqlx;

 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 $rc=mysql_num_rows($cresult);

 return $rc;

}

function userhasmenu($uid){
$sqlx="select * from menulevel1
where userid='$uid'";
//echo $sqlx;
$cresult1 = mysql_query($sqlx);
if(!$cresult1) error_message(sql_error());
if (mysql_num_rows($cresult1)>0){
 return true;
}else{
 return false;
}
}


function defaultaddresses($custid){
 /**/
$sqlx="select * from addresslabel
 where customerid=$custid
 and (primarymail='on' or primaryship='on')";
// echo $sqlx;

 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 while($cqd = mysql_fetch_array($cresult)){
  $lx=str_replace("\"","",$cqd["address"]);
  $ldesc=str_replace("\"","",$cqd["ldesc"]);

  $pm=str_replace("\"","",$cqd["primarymail"]);
  $ps=str_replace("\"","",$cqd["primaryship"]);

  if ($pm){
   $GLOBALS["mlab"]=$lx;
   $GLOBALS["mldesc"]=$ldesc;
  }
  if ($ps){
   $GLOBALS["slab"]=$lx;
   $GLOBALS["sldesc"]=$ldesc;
  }
 }

 if($GLOBALS["mlab"]=="") $GLOBALS["mlab"]=cardMailLabel($custid,"\n");

/**/
}

function docnavs($dt,$ref){
 switch ($dt){
  case "so":
  $eref="./store/istore.php?mode=e&bm=so&ref=$ref";
  $vref="formdoc.php?fid=".wform("salesorder")."&ref=$ref ";
  $dnx.="
  <a href=$eref target=_blank>".ib("e","edit sales order")."</a>
  <a href=$vref target=_blank>".ib("v","view sales order")."</a>";
  break;

  case "q":
  $eref="imstore.php?mode=eq&q=$ref";
//  $vref="formdoc.php?fid=picklist&ref=$ref ";
  $vref="olquotedetail.php?ref=$ref ";
  $dnx.="
  <a href=$eref target=_blank>".ib("e","edit quote")."</a>
  <a href=$vref target=_blank>".ib("v","view quote")."</a>";
  break;

  case "po":
  $eref="./store/istore.php?mode=e&bm=po&ref=$ref";
//  $vref="formdoc.php?fid=picklist&ref=$ref ";
  $vref="formdoc.php?fid=".wform("purchaseorder")."&ref=$ref ";
  $dnx.="
  <a href=$eref target=_blank>".ib("e","edit purchase order")."</a>
  <a href=$vref target=_blank>".ib("p","view purchase order")."</a>";
  break;

  case "inv":
  $eref="./store/istore.php?mode=e&bm=inv&ref=$ref";
  $ix=sdlink($eref,"e","edit");
  $pref="formdoc.php?fid=".wform("salesinvoice")."&ref=$ref ";
  $px=sdlink($pref,"p","view doc");
//  $vref="formdoc.php?fid=picklist&ref=$ref ";
  $dnx.="$ix $px";
  /*
  if (editok("credits_from_invoices")){
   $cref="crstore.php?mode=ci&invid=$ref";
   $dnx.="<a href=$cref target=_blank>";
   $dnx.=ib("ncn","raise credit");
   $dnx.="</a>";
  }
  */
  break;

  case "con":
  $eref="newconnote.php?mode=es&s=$ref";
  $vref="formdoc.php?fid=".wform("con_note")."&ref=$ref ";
  $dnx.="
  <a href=$eref target=_blank>".ib("e","edit consignment")."</a>
  <a href=$vref target=_blank>".ib("v","view consignment")."</a>";
  break;

  case "rfq":
  $eref="newporder.php?mode=erfq&p=$ref";
  $vref="formdoc.php?fid=".wform("rfq")."&ref=$ref ";
  $dnx.="
  <a href=$eref target=_blank>".ib("e","edit rfq")."</a>
  <a href=$vref target=_blank>".ib("v","view rfq")."</a>";
  break;

 }
 return $dnx;
}

function slink($vref,$type,$altx){
 $sx="<a href=$vref target=_blank>".ib($type,$altx)."</a>";
 return $sx;
}

function sdlink($vref,$type,$altx){
 switch($type){
	 case "button":
	 $vref=str_replace('javascript:',' ',$vref);
	 $ocx="onclick=$vref";
	 $sx="<button $ocx>$altx</button>";
	 #$sx="button";
	 break;
	 default:
	 $sx="<a href=$vref title=$altx>".ib($type,$altx)."</a>";
	 break;
 }
 return $sx;
}
function slinkw($vref,$type,$altx){
 $sx="<a href=$vref>".ib($type,$altx)."</a>";
 return $sx;
}

function klink($kref,$type,$altx){
 $vref="javascript:confirmkill('$kref')";
 $sx="<a href=\"$vref\">".ib($type,$altx)."</a>";
 //$sx="<button onclick=\"$vref\">$altx</button>";
 return $sx;
}
function kplink($kref,$type,$altx){
 $vref="javascript:confirmpkill('$kref')";
 $sx="<a href=\"$vref\">".ib($type,$altx)."</a>";
 //$sx="<button onclick=\"$vref\">$altx</button>";
 return $sx;
}

function jslink($vref,$type,$altx){
 $sx="<a href=\"$vref\">".ib($type,$altx)."</a>";
 //$sx="<button onclick=\"$vref\">$altx</button>";
 return $sx;
}


function jwlaunch($vref,$type=null,$altx,$h=600,$w=700){
 $vref="javascript:calljw('$vref',$h,$w)";
 switch($type){
  case "b":
  $sx="<button class=\"formbutton\" onclick=\"$vref\">$altx</button>";
  break;
  default:
  $sx="<a href=\"$vref\">".ib($type,$altx)."</a>";
  break;
 }
 return $sx;
}



function jbutton($vref,$type,$altx,$bracketclose=false){
 //$sx="<a href=\"$vref\">".ib($type,$altx)."</a>";
 if($bracketclose){
 	/*useful in grid situations where ref added in bulk*/
 	$vref.="')";
 }
 $sx="<button class=formdata onclick=\"$vref\">$altx</button>";
 return $sx;
}



function ib($x,$altx){
 $ibx.="<img src=\"../../infbase/images/";
// if($GLOBALS["ajpath"]=true){
// $ibx.="<img src=\"../images/";
// }
 switch ($x){
  case "d":
  $ibx.="infdel.gif";
  break;
  case "dp":
  $ibx.="infdel.gif";
  break;
  case "e":
  $ibx.="infedit.gif";
  break;
  case "v":
  $ibx.="infview.gif";
  break;
  case "p":
  $ibx.="infprint.gif";
  break;
  case "ncn":
  $ibx.="infview.gif";
//  $ibx.="credit";
  break;
  case "cal":
  $ibx.="cal.gif";
  break;
  case "lup":
  $ibx.="lup.gif";
  break;

  case "left":
  $ibx.="/nav/navleft.gif";
  break;
  case "right":
  $ibx.="/nav/navright.gif";
  break;

  case "right_sd":
  $ibx.="/nav/gold_arr_right.gif";
  break;

  case "call":
  $ibx.="call.gif";
  break;

  case "phonecall":
  $ibx.="call.gif";
  break;

  case "pdf":
  $ibx.="pdf.jpg";
  break;

  case "xl":
  $ibx.="msxl.gif";
  break;

  case "eml":
  $ibx.="/icons/misc/message.gif";
  break;


 }
 $ibx.="\" border=0 alt=\"$altx\" title=\"$altx\"></a>";
 return $ibx;
}

function wform($ft){

 $sqlx="select * from formlink
 where formtype='$ft'";
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 while($cqd = mysql_fetch_array($cresult)){
  $fid=$cqd["formid"];
 }
 return $fid;
}

function findcustfromold($ci){
$sqlx="select customerid from customer as cid
where oldcode='$ci' ";
//echo "finding ".$ci."<BR>".$sqlx;
$cresult = mysql_query($sqlx);
if(!$cresult) error_message(sql_error());
 if($qd = mysql_fetch_array($cresult)){
  $a =$qd["customerid"];
//  echo "<BR>found ".$a;
  return $a;
 }
}

function singlevalue($tableid,$fieldid,$idfield,$idval,$numeric=false){
 $sqlx="select $fieldid from $tableid";
 if ($numeric){
 $sqlx.=" where $idfield=$idval";
 }else{
 $sqlx.=" where $idfield='$idval'";
 }
 tfw("svx.txt",$sqlx,true);
 $cresult = mysql_query($sqlx);
 #if(!$cresult) error_message(sql_error());
  if($qd = mysql_fetch_array($cresult)){
   return $qd[$fieldid];
  }
}


function getmonthname($n,$mode="full"){
 #reposition array
 $ml=$n-1;

 $full=array("January","February","March","April","May","June","July","August","September","October","November","December");
 $short=array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
 switch ($mode){
     case "short":
     $mn=$short[$ml];
     break;
     default:
     $mn=$full[$ml];
     break;
  }
  return $mn;
}

function fdec($n,$d,$pos=2){
 $n=number_format($n,$pos);
 if($d){
  $nx="$".$n;
  return $nx;
 }
 return $n;
}



function sud($fn){
 $imgx="sortup.gif";
 if (!(is_null($_GET["sby"]))){
  if (($_GET["sby"]==$fn)&($_GET["sm"]=="asc")){
   $imgx="sortupactive.gif";
  }
 }
 $hrefx=$GLOBALS["hrv"]."sby=$fn&sm=asc";
 $ua="<a href=".$hrefx."><img src=\"images/nav/".$imgx."\" border=0></a>";

 //$sx="images\nav\sort
 $imgx="sortdown.gif";
 if (!(is_null($_GET["sby"]))){
  if (($_GET["sby"]==$fn)&($_GET["sm"]=="desc")){
   $imgx="sortdownactive.gif";
  }
 }
 $hrefx=$GLOBALS["hrv"]."sby=$fn&sm=desc";
 $ua.="<a href=".$hrefx."><img src=\"images/nav/".$imgx."\" border=0></a>";
 return $ua;
}

Function activeusers(){
$q= "select * from users
where (dateleft is null or dateleft=0)
and (inactive is null or inactive!='on')
order by userorder";
$tf=kda("userid");
$da=iqa($q,$tf);
$ua=key2val(aFV($da,"userid"));
return $ua;
}

Function currentusers(){
$sqlx= "select * from users
where dateleft is null or dateleft=0
order by userorder
";
//echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 while($qqd = mysql_fetch_array($cresult)){
  $ui=$qqd["userid"];
//echo $ui;
  $usersa[]=$ui;
 }
 return $usersa;
}

Function tablecontents($tableid){
$sqlx= "select * from $tableid ";
//echo $sqlx;
 $cresult = mysql_query($sqlx);
 return $cresult;
}

function mailable($email){
 $xm="<a href=\"mailto:$email\" class=greylinks>$email</a>";
 return $xm;
}

Function getdistinctdatetablecontents($ltable,$fieldx,$ftcount){
  $sqlx=" SELECT distinct month($fieldx) as m ,year($fieldx) as y";
  if($GLOBALS["countfilters"]){
  $sqlx.=",count($ftcount) as countv";
  }
  $sqlx.=" FROM ".$ltable ." group by month($fieldx),year($fieldx)
  order by year ($fieldx) desc ,month($fieldx) desc
  ";
//echo "qqqq $sqlx";
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
  return $cresult ;

}

function performpostinsert($t,$avoid,$pa=null,$incvpost=null){
  switch($incvpost){
   case "include":
    foreach($pa as $fn){
     $dv=$_POST[$fn];
     //echo "<br>$fn $dv";
     $names[]=$fn;
     $values[]=$dv;
    }
   break;
   default:
    foreach($_POST as $pf=>$pv){
 	  if (!in_array($pf, $avoid)){
		  $names[]=$pf;
		  $values[]=$pv;
 	  }
    }
   break;
  }
  $fieldsx=implode(",",$names);
  $valuesx="'".implode("','",$values)."'";
  $jsqlx="insert into $t($fieldsx) values($valuesx)";
 //echo "<br> $jsqlx";
 $jresult = mysql_query($jsqlx);
  $newid=mysql_insert_id();
  return $newid;
}

function performpostupdate($t,$avoid,$fid,$fidv,$pa=null,$incvpost=null){
  switch($incvpost){
   case "include":
    foreach($pa as $fn){
     $dv=$_POST[$fn];
     //echo "<br>$fn $dv";
     $fx=$fn."='".$dv."'";
     $uvx[]=$fx;
    }
   break;
   default:
    foreach($_POST as $pf=>$pv){
 	  if (!in_array($pf, $avoid)){
	     //echo "<br>$fn $dv";
	     $fx=$pf."='".$pv."'";
	     $uvx[]=$fx;
 	  }
    }
   break;
  }
  $fieldsx=implode(",",$uvx);
  $jsqlx="update $t set $fieldsx
  where $fid=$fidv
  ";
  if($fidv<>''){
  //echo "<br>jjj $jsqlx";
  $jresult = mysql_query($jsqlx);
  }
}

function datediff($d1,$d2){
 $m1=date("m",strtotime($d1));
 $m2=date("m",strtotime($d2));
 $y1=date("Y",strtotime($d1));
 $y2=date("Y",strtotime($d2));
 $day1=date("d",strtotime($d1));
 $day2=date("d",strtotime($d2));

 $diff=(mktime(0,0,0,$m2,$day2,$y2)-mktime(0,0,0,$m1,$day1,$y1))/86400;
 return $diff;
}

function infdatediff($interval, $datefrom, $dateto, $using_timestamps = false) {
  /*
    $interval can be:
    yyyy - Number of full years
    q - Number of full quarters
    m - Number of full months
    y - Difference between day numbers
      (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
    d - Number of full days
    w - Number of full weekdays
    ww - Number of full weeks
    h - Number of full hours
    n - Number of full minutes
    s - Number of full seconds (default)
  */

  if (!$using_timestamps) {
    $datefrom = strtotime($datefrom, 0);
    $dateto = strtotime($dateto, 0);
  }
  $difference = $dateto - $datefrom; // Difference in seconds

  switch($interval) {

    case 'yyyy': // Number of full years

      $years_difference = floor($difference / 31536000);
      if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
        $years_difference--;
      }
      if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
        $years_difference++;
      }
      $datediff = $years_difference;
      break;

    case "q": // Number of full quarters

      $quarters_difference = floor($difference / 8035200);
      while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
        $months_difference++;
      }
      $quarters_difference--;
      $datediff = $quarters_difference;
      break;

    case "m": // Number of full months

      $months_difference = floor($difference / 2678400);
      while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
        $months_difference++;
      }
      $months_difference--;
      $datediff = $months_difference;
      break;

    case 'y': // Difference between day numbers

      $datediff = date("z", $dateto) - date("z", $datefrom);
      break;

    case "d": // Number of full days

      $datediff = floor($difference / 86400);
      break;

    case "w": // Number of full weekdays

      $days_difference = floor($difference / 86400);
      $weeks_difference = floor($days_difference / 7); // Complete weeks
      $first_day = date("w", $datefrom);
      $days_remainder = floor($days_difference % 7);
      $odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
      if ($odd_days > 7) { // Sunday
        $days_remainder--;
      }
      if ($odd_days > 6) { // Saturday
        $days_remainder--;
      }
      $datediff = ($weeks_difference * 5) + $days_remainder;
      break;

    case "ww": // Number of full weeks

      $datediff = floor($difference / 604800);
      break;

    case "h": // Number of full hours

      $datediff = floor($difference / 3600);
      break;

    case "n": // Number of full minutes

      $datediff = floor($difference / 60);
      break;

    default: // Number of full seconds (default)

      $datediff = $difference;
      break;
  }
  return $datediff;
}

function monthheadings(){
 $months=array("January","February","March","April","May","June","July","August","September","October","November","December");
 $GLOBALS["mhead"].="<tr class=\"tableheadbar\"><td>Year</td>";
	 $i=0;
 	 foreach($months as $mx){
 	  $GLOBALS["mhead"].="<td>$mx</td>";
 	 }
 $GLOBALS["mhead"].="<td>Total</td></tr>";
}

function mail_label($custid){
 $sqlx="select address from addresslabel
 where customerid=$custid and
 primarymail='on'";
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
 while ($qd = mysql_fetch_array($cresult)){
  $ml=$qd["address"];
 }
 return $ml;
}

function cardMailLabel($custid,$linesep="<br>"){
 $cqd=readcust($custid);
 $mailx=$cqd["companyname"];
 if($cqd["mail1"]!=""){
	 $mailx.="$linesep".$cqd["mail1"];
 }
 if(($cqd["mail2"]!="")&&($cqd["mail2"]!="0")){
 	$mailx.="$linesep".$cqd["mail2"];
 }
 $mailx.="$linesep".$cqd["mailsuburb"]." ".$cqd["state"]." ".$cqd["postcode"];
 return $mailx;
}


function checkFieldExists($tablen,$da){
    $tfa=totalfieldarray($tablen);
	$fi=tinfo($tablen,"strcase");

	foreach($da as $fn=>$fv){
		$fax.="$fn $fv";
		#case conversions.
		$strc=$fi[$fn]["strcase"];
		#echo "alert('$fn has case $strc')\n";
		switch($strc){
			case "ucwords":
			$fv=ucwords($fv);
			break;
			case "upper":
			$fv=strtoupper($fv);
			break;
			case "lower":
			$fv=strtolower($fv);
			break;
		}
		$da[$fn]=$fv;
		if(!in_array($fn,$tfa)){
			unset($da[$fn]);
		}
		#temp storage for case converted primary id stuff
		$GLOBALS["cfe"][$fn]=$fv;
	}
	return $da;
}


function performarrayinsert($t,$ad){
  $ad=checkFieldExists($t,$ad);
  foreach($ad as $df=>$dv){
		  $fc++;
		  if($fc>1){
			  $fields.=",";
			  $values.=",";
		  }
		  #urldecode required?
		  #$dv=urldecode(addslashes($dv));
		  $dv=addslashes($dv);
		  $fields.="$df";
		  $values.="'$dv'";
  }
  $jsqlx="insert into $t($fields) values($values)";
  #echo ("<br>jjj $jsqlx");
 //report_sql_error("attempt insert $jsqlx");
 tfw("jjj$t.txt",$jsqlx,false);
 $jresult = mysql_query($jsqlx);
 $newid=mysql_insert_id();
 if(!$jresult){
  return false;
 }
 return $newid;
}

function performarrayupdate($t,$ad,$fid,$fidv){
  $ad=checkFieldExists($t,$ad);
  foreach($ad as $df=>$dv){
		  $fc++;
		  if($fc>1){
			  $fields.=",";
		  }
		  $dv=addslashes($dv);
		  $fields.="$df='$dv'";
  }
  $jsqlx="update $t set $fields";
  $jsqlx.=" where $fid='$fidv'";
  $dt=date("i-s");
  tfw("puqx$dt.txt",$jsqlx,true);
  if($fidv<>''){
  //etest("$jsqlx");
  $jresult = mysql_query($jsqlx);
  }
  return mysql_affected_rows();
}



function xsb($x){
 $x=nl2br(stripslashes($x));
 return $x;
}

function phoneable($custid,$contactid,$dv){
    $cref="setcallcust.php?mode=new";
	if(isset($cqd["customerid"])){
	 $customerid=$cqd["customerid"];
	}
     if(isset($contactid)){
     $cref.="&cid=".$contactid;
     }else{
		if(isset($custid)){
	     $cref.="&custid=".$custid;
		}
     }
    $dv=slink($cref,"call","new call").$dv;
 return $dv;
}

function ownername(){
 $ox=sysvalue("ownername");
 return $ox;
}

function dateadder($startd,$n,$t){
 if($startd>0){
 $inty=date("Y",strtotime($startd));
 $intm=date("m",strtotime($startd));
 $intd=date("d",strtotime($startd));
 switch($t){
  case "m":
  $intm+=$n;
  break;
  case "Y":
  $inty+=$n;
  break;
  case "d":
  $intd+=$n;
  break;
 }

 $newdate =date("Y-m-d",mktime(0,0,0,$intm,$intd,$inty));
 //echo "<br>s:n  $startd $newdate";
 return $newdate;
}
}

function lstyle($rc=1){
// $rowstyles=array("lgtextstyle","lstextstyle");
 $rowstyles=array("listr1","listr2");
 $style=$rowstyles[$rc%2];
 return $style;
}
function crmlstyle($rc=1){
 $rowstyles=array("lgtextstyle","lstextstyle");
 $style=$rowstyles[$rc%2];
 return $style;
}


function contactloader($custid){
	$query="SELECT * FROM contact
	where customerid = $custid";
	//$query.=" limit 1";
	$result = mysql_query($query);
	if(!$result) error_message(sql_error());
	 while($qd = mysql_fetch_array($result)){
	  $dn=$qd["firstname"]." ".$qd["surname"];
	  $ci=$qd["contactid"];
	  $c++;
	  $_SESSION["apptcontactid"][$ci]=$dn;
	 }
}

function hoursinjob($userid){
$sqlx="
SELECT sum(d.duration) as dur,j.jobid,
j.customerid,j.leaddate,j.duedate,j.jobdescription,
j.jobstatus,c.companyname FROM jobs as j
 left outer join customer as c on
 j.customerid=c.customerid
 left outer join diary as d
 on j.jobid=d.jobid
 WHERE j.userid='$userid'
 and j.jobstatus<>'closed'
 group by j.jobid
 order by duedate asc";

 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
  return $cresult ;
}

function make_array($sqlx,$displayf,$idf){
  $result = mysql_query($sqlx);
  if(!$result) error_message(sql_error());
  while($vqd = mysql_fetch_array($result)){
   $idval=$vqd[$idf];
   $val=$vqd[$displayf];
   $mav[$idval]=$val;
  }
 return $mav;
}

function dateok($testdate){
 if(isset($GLOBALS["lockdate"])){
	 $cm=date("m",strtotime($GLOBALS["lockdate"]));
	 $cy=date("Y",strtotime($GLOBALS["lockdate"]));
 }else{
	 $cm=date("m");
	 $cy=date("Y");
 }
 $tm=date("m",strtotime($testdate));
 $ty=date("Y",strtotime($testdate));
 if($ty>=$cy){
  if($tm>=$cm){
   return true;
  }
 }
 return false;
}

function load_defaultcontactvals($custid){
 $sqlx="select phone,fax from customer
 where customerid=$custid";
 $cresult = mysql_query($sqlx);
// if(!$cresult) error_message(sql_error());
 if($cqd = mysql_fetch_array($cresult)){
  $GLOBALS["phone"]=$cqd["phone"];
  $GLOBALS["fax"]=$cqd["fax"];
 }
}

function kda($raw){
 $da=explode(",",$raw);
 $ka=key2val($da);
 return $ka;
}

function key2val($raw){
 if(is_array($raw)){
	$fv=array_values($raw);
	 foreach($fv as $v){
	  $tfields[$v]=$v;
	 }
	 return $tfields;
 }
}

function keyfromva2($a1,$a2){
/*takes values of second array, indexes by values of first array*/
$fv=array_values($a1);
$fv2=array_values($a2);
 foreach($fv as $k=>$v){
  $newa[$v]=$fv2[$k];
  //echo "<br>k2v $v $k";
 }
 return $newa;
}


function spacefill($x,$rlen,$spacesat,$spacechar=" "){
 $slen=strlen($x);
 //echo "<br> $x $slen";
 $short=$rlen-$slen;
 for($i=0;$i<$short;$i++){
  //$sp.="&nbsp";
  $sp.=$spacechar;
 }
 if($spacesat=="left"){
 $rx=$sp.$x;
 }else{
 $rx=$x.$sp;
 }
 return $rx;
};



function instant_jclose(){
 $sx="<script> \n
  window.close() \n
 </script> \n";
 echo $sx;
}

function qRowCount($sqlx){
//echo $sqlx;
$cresult = mysql_query($sqlx);
if(!$cresult) {
	error_message(sql_error());
	return;
}
$rc=mysql_num_rows($cresult);
return $rc;
}

function etest($x){
  //echo "<br><font color=black>$x</font>";
}

function fileWriter($filename, $data,$owrite=true)
{

if(!$owrite){
//Imports old data
$handle = fopen($filename, "r");
$old_content = fread($handle, filesize ($filename));
fclose($handle);
}
//Sets up new data
$final_content = $data.$old_content;
//$final_content = $data;

//Writes new data
$handle2 = fopen($filename, "w");
$finalwrite = fwrite($handle2, $final_content);
fclose($handle2);
}

function monthpicker($dt,$ec=false){
 if($dt==""){
  $dt=date();
 }
 $nd=date("Y-m-t");
 $arr[$nd]=date("M y",strtotime($nd));
 /*build array from today back to firt period*/
 while ($nd>$GLOBALS["systemstarts"]){
  $nd=dateadder($nd,-1,"m");
  $td=date("Y-m-t",strtotime($nd));
  $xd=date("M y",strtotime($nd));
  $arr[$td]=$xd;
 }

 $dpx="
 <form method=\"post\">
 <select class=formdata name=\"period\">";
 $dpx.=makeidselectoptionsarraysimple($arr,$dt);
 $dpx.="
 <input class=formbutton type=\"submit\" value=\"go\">
 </form>
 ";

 if(isset($_POST["period"])){
  $d2=date("Y-m-t",strtotime($_POST["period"]));
 }else{
  $d2=date("Y-m-t");
 }

 $da=array($dpx,$d2);
 return $da;

}


function extraCols($da1,$da2,$d2fields,$commonid){
 /*takes raw data, adds columns for each member of da2
 *requires a common id, and da2 must be indexed by the commonid
 */
 /*dont replace id value*/
 unset($d2fields[$commonid]);
 if(isset($da1)){
 foreach($da1 as $did1=>$rowd){
  $idv=$rowd[$commonid];
  foreach($d2fields as $newfn){
  	$newdv=$da2[$idv][$newfn];
  	#ignor blanks.
  	if($newdv!="") 	$da1[$did1][$newfn]=$newdv;
	#echo "alert('3439 ndv $newdv idv $idv nfn $newfn') \n";
	//etest("xxxx $idv $newfn $newdv");
  }
 }
 }
 return $da1;
}

function jRunner($x,$defer=true){
	if($defer) $dfx="defer=\"defer\"";
	$x="<script $dfx>\n
	 $x \n
	</script>\n";
	echo $x;
}



function multiUserPA(){
$query="
SELECT uf.userid,uf.firstname,uf.surname,
ur.colleagueid ,uf.userorder
FROM userviewsrequested as ur
right outer join userviewspermitted as up
on ur.colleagueid=up.userid
right outer join users as uf on
ur.colleagueid=uf.userid
where (ur.userid='".$_SESSION["userid"]."' or
ur.userid='".ucwords($_SESSION["userid"])."' or
ur.userid='".strtolower($_SESSION["userid"])."' )
and (up.colleagueid='".$_SESSION["userid"]."' or
up.colleagueid='".ucwords($_SESSION["userid"])."' or
up.colleagueid='".strtolower($_SESSION["userid"])."' )

order by uf.userorder,uf.userid
";
tfw("muq.txt",$query,true);
$tf=kda("userid,firstname,surname,userorder");
$da=iqa($query,$tf);
foreach($da as $i=>$row){
    $userid=$row["userid"];
    $oap[$userid]=$i-1;
}
//$isa=serialize($oap);
//tfw("szq.txt",$isa,true);
asort($oap);
reset($oap);
$GLOBALS["oap"]=$oap;
//return $oap;
}

function multiUserPermittedA(){
$query="
SELECT uf.userid,uf.firstname,uf.surname,
up.colleagueid ,uf.userorder
FROM userviewspermitted as up
right outer join users as uf on
up.userid=uf.userid
where (up.colleagueid='".$_SESSION["userid"]."' or
up.colleagueid='".ucwords($_SESSION["userid"])."' or
up.colleagueid='".strtolower($_SESSION["userid"])."' )
order by uf.userorder,uf.userid
";
tfw("mup.txt",$query,true);
$tf=kda("userid,firstname,surname,userorder");
$da=iqa($query,$tf);
foreach($da as $i=>$row){
    $userid=$row["userid"];
    $oap[$userid]=$i-1;
}
//$isa=serialize($oap);
//tfw("szq.txt",$isa,true);
asort($oap);
reset($oap);
$GLOBALS["mup"]=$oap;
//return $oap;
}


function jtop(){
	echo "window.scrollTo(0,0);\n";
}

function pagereload(){
	echo "window.location.reload();\n";
}

function xl_header(){
 // header("Content-Type: application/octet-stream");
 #header("Content-Type: application/x-msexcel");
 #header("Content-Disposition: attachment; filename=excelfile.xls");
 #header("Cache-Control: max-age=60");

 header("Pragma: public");
 header("Expires: 0");
 header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
 header("Content-Type: application/force-download");
 header("Content-Type: application/octet-stream");
 header("Content-Type: application/download");;
 header("Content-Disposition: attachment;filename=excelfile.xls ");
 header("Content-Transfer-Encoding: binary ");


}

function iStringCount($searchstring, $findstring){
   return (strpos($searchstring, $findstring) === false ? 0 :  count(split($findstring, $searchstring)) - 1);
}

function savesysvar($fn,$fv){
	$da["vvalue"]=$fv;
	if(sysvar($fn)){
		performarrayupdate("systemvariables",$da,"varid",$fn);
	}else{
		$da["varid"]=$fn;
		performarrayinsert("systemvariables",$da);
	}
}


function addTime($start,$hours=0, $minutes=0)
{
$totalHours = date("H",strtotime($start)) + $hours;
$totalMinutes = date("i",strtotime($start)) + $minutes;
$totalSeconds = date("s",strtotime($start)) + $seconds;
$totalMonths = date("m",strtotime($start)) + $months;
$totalDays = date("d",strtotime($start)) + $days;
$totalYears = date("Y",strtotime($start)) + $years;
$timeStamp = mktime($totalHours, $totalMinutes, $totalSeconds, $totalMonths, $totalDays, $totalYears);
$myTime = date("H:i", $timeStamp);
return $myTime;

}

function profileDefs($pdesc){
	$q="select * from activeformdef where activeprofiledesc='$pdesc'";
	$tf=key2val(tfa("activeformdef"));
	#tfw("pdfx.txt",$q,true);
	$pa=iqa($q,$tf);
	return $pa;
}



##navfunctions start##
function icopymenu($srcuid,$targetuid){
//kill existing menus
$ksqlx="delete from menulevel1
where userid='$targetuid'";
//echo $ksqlx;

$kresult = mysql_query($ksqlx);
if(!$kresult) error_message(sql_error());


$ksqlx2="delete from menulevel2
where userid='$targetuid'";
$kresult = mysql_query($ksqlx2);
if(!$kresult) error_message(sql_error());

$q1=iqusermlevel1($srcuid);
$cresult = mysql_query($q1);
if(!$cresult) error_message(sql_error());
 while ($qd = mysql_fetch_array($cresult)){
  $oid=$qd["menulevel1id"];
  $mlv1desc=$qd["menulevel1desc"];
  $mlv1url=$qd["menulevel1url"];
  $morder=$qd["morder"];
  $isqlx="insert into menulevel1(userid,menulevel1desc,menulevel1url,morder)
  values('$targetuid','$mlv1desc','$mlv1url',$morder)";
  //echo "ccc $sqlx";
  $icresult = mysql_query($isqlx);
  $qid=mysql_insert_id();
  $xref[$oid]=$qid;
 }

$q2=iqusermlevel2($srcuid);
$cresult2 = mysql_query($q2);
if(!$cresult2) error_message(sql_error());
 while ($qd2 = mysql_fetch_array($cresult2)){
  $mlv2desc=$qd2["menulevel2desc"];
  $mlv2url=$qd2["menulevel2url"];
  $mlv1id=$qd2["menulevel1id"];
  $target1id=$xref[$mlv1id];
  $morder=$qd2["morder"];
  $isqlx2="insert into menulevel2(userid,menulevel2desc,menulevel2url,morder,menulevel1id)
  values('$targetuid','$mlv2desc','$mlv2url',$morder,$target1id)";
//  echo $sqlx2;
  $icresult2 = mysql_query($isqlx2);
 }

//copy colours;
$cqd=arrayreaduser($srcuid);
$fl=$cqd["menuleft"];
$ft=$cqd["menutop"];
$pbc=$cqd["pbc"];
$ptc=$cqd["ptc"];
$psbc=$cqd["psbc"];
$pstc=$cqd["pstc"];
$dbc=$cqd["dbc"];
$dtc=$cqd["dtc"];
$sdbc=$cqd["sdbc"];
$sdtc=$cqd["sdtc"];
$twd=$cqd["twd"];
$ntrsp=$cqd["navtrsp"];
isavetopleft($targetuid,$fl,$ft,$pbc,$ptc,$psbc,$pstc,$dbc,$dtc,$sdbc,$sdtc,$twd,$ntrsp);


}


function iqusermlevel1($uid){
$sqlx="select * from menulevel1
where userid='$uid'
order by morder
";
return $sqlx;
}

function iqusermlevel2($uid){
$sqlx="select * from menulevel2
where userid='$uid'
order by morder
";
return $sqlx;
}

function isavetopleft($uid,$fl,$ft,$pbc,$ptc,$psbc,$pstc,$dbc,$dtc,$sdbc,$sdtc,$twd,$ntrsp){
 $sqlx="update users
 set menuleft=$fl,
 menutop=$ft,
 pbc='$pbc',
 ptc='$ptc',
 psbc='$psbc',
 pstc='$pstc',
 dbc='$dbc',
 dtc='$dtc',
 sdbc='$sdbc',
 sdtc='$sdtc',
 twd=$twd,
 navtrsp=$ntrsp
 where userid='$uid'";
//echo $sqlx;
 $cresult = mysql_query($sqlx);
 if(!$cresult) error_message(sql_error());
}

function idefaultmenucolors(){
 $GLOBALS["pbc"]="#000000";
 $GLOBALS["ptc"]="#FFFFFF";
 $GLOBALS["psbc"]="#000000";
 $GLOBALS["pstc"]="#FFFFFF";
 $GLOBALS["dbc"]="#000000";
 $GLOBALS["dtc"]="#FFFFFF";
 $GLOBALS["sdbc"]="#000000";
 $GLOBALS["sdtc"]="#FFFFFF";
}
##navfunctions end##


function basicMail($mess,$subj,$recips){
$headers = "Content-Type: text/html; charset=iso-8859-1\n";
$headers.="From: sql@infomaniac.com.au\r\n";

ini_set("sendmail_from", "infomaniac");


foreach($recips as $recip=>$oktosend){
 if($oktosend){
	if (mail($recip, $subj, $mess,$headers)){
		#echo "alert('sent mail ok - $recip')\n";
		$succesfull++;
	 }else{
		#echo "alert('failed mail  - $recip')\n";
		#mail failure
		$ra["simonb@velocityweb.com.au"]=true;
		$fmessage="Failed to send mail to $recip by ".$_SESSION["userid"];
		basicMail($fmessage,"Failure",$ra);
	}
 }
}
 return $succesfull;

}

function tableSort(){
			echo "standardistaTableSortingInit();\n";
}

function qcbld($condA){
	#query condition build
	foreach($condA as $k=>$v)	$i++;if($ii=1){$conjx="where";}else{$conjx="and";}	$condx.="$conjx $k='$v' ";
	return $condx;
}

function currf($n){
$n=number_format($number, 2, '.','');
return $n;
}
// 1234.57

function bankselection($defaultfirst=null,$dv=null,$jsx=null){
 $banka=bankaccounts();
 if(!$dv){
 	if($defaultfirst){
 	$dv=$GLOBALS["firstval"];
 	}
 }
 $GLOBALS["blocknewoffer"]=true;
 $hx.="<td  class=formlabel>Bank Account</td>
 	<td colspan=10><select class=\"formdata\" name=\"bankgl\" $jsx>".makeidselectoptionsarraysimple($banka,$dv)."</select></td>";
 return $hx;
}

function bank_choice(){
  $dfb=$GLOBALS["primarybank"];
  $bgx=bankselection(0,$dfb);
  $gx.="$bgx";
  return $gx;
}

function bankaccounts(){
 $sqlx="select * from glchart where
 reconcile='on'";
 $tfields=array("glchartid","glchartdesc");
 $ba=infqueryarray_pair($sqlx,$tfields);
 return $ba;
}


function html2txt($document){
$document = str_replace('&nbsp;',' ',$document);
$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
               '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
               '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
               '/  +/',
               '/\\t/',
               '/\\r\n\\r\n\\r\n/',
               '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA
);
$text = preg_replace($search, '', $document);
#$text = preg_replace('/\\r\n\\r\n/','/\\r\n/', $text);
#$text = str_replace('/\\r\n\\r\n/','\\n', $text);
#$text = str_replace('/\\r\n\\r\n/','\\n', $text);
#$text = str_replace('/ \\r\n\\r\n/','\\n', $text);
$text=trim($text);
$text=htmlspecialchars_decode($text);

return $text;
}



?>
