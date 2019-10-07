<?php
/*
# 2013.10.22 - STB line 12773
*/

//include __DIR__ . '/../../dcfm.inc';

//echo $GLOBALS['JT_HTDOCS'];

class customInterface extends standardInterface{

	function logit($string){
		if ($_SESSION['userid'] != 'itglobal1') return;
	  //return false;
	  $file = 'C:/temp/custint.log';
	  $fh = fopen($file, 'a');
	  fwrite($fh, $string."\n");
 	  fclose($fh);
	}

	function customInterface(){
		#$this->baseFolder="infbase_DCFM";
		$this->baseFolder="infbase";
		$this->standardInterface();
		#echo "<br>bf $this->baseFolder";
		$smode="local";
		#$smode="devel";
		#$smode="hosted";
		$this->smode=$smode;
		switch($smode){
			case "laptop":
			//$this->useCharts=true;
			//$this->clientFolder="dcfm07";
			//$this->smtp="smtp.telstrabusiness.com";
			//$this->smtp="127.0.0.1";
			
			//$this->mailFrom="dcfm@dcfm.com.au";
			#$this->mailBcc="jobtracker@dcfm.com.au;d.c.f.m.b.c.c@gmail.com";
			#$this->mailFromName="DCFM";
			//$this->mailBcc=kda("jobtracker@dcfm.com.au,d.c.f.m.b.c.c@gmail.com");
			//$this->eipPrefix="editSpan";
						
			break;
			
			case "devel":
			//$this->basepath="/srv/www/htdocs/infbase";
			//$this->clientpath="/srv/www/htdocs/dcfm07";
			//$this->wkpath="/srv/www/wkhtmltopdf";
			//$this->chartpath="/srv/www/htdocs/infbase/libchart/classes/libchart.php";

			#$this->testRecip="matthewfedak@hotmail.com";
			//$this->testRecip="tcbflash77@gmail.com";
			#$this->pdfPath="/srv/www/htdocs/infomaniacDocs/custdocs";
			//$this->pdfPath="/srv/www/htdocs/infomaniacDocs";
			//$this->pdfPathRel="../../infomaniacDocs";
			//$this->clientFolder="dcfm07";
			//$this->docPath["jobs"]="/srv/www/htdocs/infomaniacDocs/jobdocs";
			//$this->docPath["variation"]="/srv/www/htdocs/infomaniacDocs/variation";
			#$this->docPath["customer"]="/srv/www/htdocs/infomaniacDocs/custdocs";
			//$this->docPath["customer"]="/srv/www/htdocs/infomaniacDocs/jobdocs";
			//$this->docFolder["jobs"]="infomaniacDocs/jobdocs";
			//$this->docFolder["jobsafety"]="infomaniacDocs/jobdocs/safety";
			//$this->docFolder["customer"]="infomaniacDocs/custdocs";
			//$this->docFolder["customer"]="infomaniacDocs/jobdocs";
			//$this->useCharts=true;
			//$this->useSwiftMail=true;
			#$this->smtp="192.168.0.9";
			#not working off the IP
			//$this->smtp="localhost";
			//$this->mailFrom="tcbflash77@gmail.com";
			//$this->mailBcc[]="tcbflash77@gmail.com";
			//$this->mailBcc[]="d.c.f.m.b.c.c@gmail.com";

			break;

			case "local":
			$this->useCharts=true;
			$this->clientFolder="dcfm07";
			//$this->basepath= "D:\Apache Software Foundation/htdocs/infbase";
			$this->basepath= $GLOBALS['JT_HTDOCS'] . "/infbase";
			
			$this->clientpath=  $GLOBALS['JT_HTDOCS'] . "/dcfm07";
			$this->pdfPath= $GLOBALS['JT_HTDOCS'] . "/infomaniacDocs/";
			$this->pdfPathRel="../../infomaniacDocs";
			$this->docPath["jobs"]= $GLOBALS['JT_HTDOCS'] . "/infomaniacDocs/jobdocs";
			$this->docPath["variation"]= $GLOBALS['JT_HTDOCS'] . "/infomaniacDocs/variation";
			$this->docPath["customer"]= $GLOBALS['JT_HTDOCS'] . "/infomaniacDocs/custdocs";
			$this->docPath["compliance"]= $GLOBALS['JT_HTDOCS'] . "/infomaniacDocs/compliance";
			$this->docFolder["complianceDocs"]="infomaniacDocs/compliance";
			$this->docPath["complianceDocs"]= $GLOBALS['JT_HTDOCS'] . "/infomaniacDocs/compliance";
			$this->wkpath="D:\wkhtmltopdf";

			$this->docFolder["jobs"]="infomaniacDocs/jobdocs";
			$this->docFolder["customer"]="infomaniacDocs/jobdocs";
			$this->docFolder["jobsafety"]="infomaniacDocs/jobdocs";
			$this->docPath["jobsafety"]= $GLOBALS['JT_HTDOCS'] . "/infomaniacDocs/jobdocs";
			$this->useCharts=true;
			$this->chartpath= $GLOBALS['JT_HTDOCS'] . "/infbase/libchart/classes/libchart.php";
			$this->useSwiftMail=true;
			//$this->smtp="smtp.telstrabusiness.com";
			$this->smtp="127.0.0.1";
			$this->mailFrom="dcfm@dcfm.com.au";
			#$this->mailBcc="jobtracker@dcfm.com.au;d.c.f.m.b.c.c@gmail.com";
			#$this->mailFromName="DCFM";
			$this->mailBcc=kda("jobtracker@dcfm.com.au,d.c.f.m.b.c.c@gmail.com");
			$this->eipPrefix="editSpan";



			break;

			case "hosted":
			$this->basepath="/srv/www/htdocs/infbase";
			$this->clientpath="/srv/www/htdocs/dcfm07";
			$this->wkpath="/srv/www/wkhtmltopdf";
			$this->chartpath="/srv/www/htdocs/infbase/libchart/classes/libchart.php";

			#$this->pdfPath="/srv/www/htdocs/infomaniacDocs/custdocs";
			$this->pdfPath="/srv/www/htdocs/infomaniacDocs";
			$this->pdfPathRel="../../infomaniacDocs";
			$this->clientFolder="dcfm07";
			$this->docPath["jobs"]="/srv/www/htdocs/infomaniacDocs/jobdocs";
			$this->docPath["variation"]="/srv/www/htdocs/infomaniacDocs/variation";
			$this->docPath["customer"]="/srv/www/htdocs/infomaniacDocs/custdocs";
			$this->docFolder["jobs"]="infomaniacDocs/jobdocs";
			$this->docFolder["jobsafety"]="infomaniacDocs/jobdocs/safety";
			$this->docFolder["customer"]="infomaniacDocs/custdocs";
			$this->useCharts=true;
			$this->useSwiftMail=true;
			#$this->smtp="192.168.0.9";
			#not working off the IP
			$this->smtp="localhost";
			$this->mailFrom="dcfm@dcfm.com.au";
			$this->mailBcc="jobtracker@dcfm.com.au";
			break;

		}

		if(strpos($_SERVER["SERVER_NAME"],".com")){
			$this->internalMaps=true;
		}
		$this->logMail=true;
		$this->deferMailViaBatch=true;
		$this->docFolder["jobs"]="infomaniacDocs/jobdocs";
		$_SESSION["uploadFolder"]["jobs"]=$this->docFolder["jobs"];
		$_SESSION["uploadFolder"]["complianceDocs"]=$this->docFolder["complianceDocs"];
		$this->noSave=true;//session search save option


		$this->masterNavDescriptionField["customer"]=kda("customerid,companyname");

		#$this->onLoadJFunction="onload=ibuffer('taskList');";
		#$this->onLoadJFunction["op"]="onload=ibuffer('taskList');";
		$this->onLoadJFunction["op"]="onload=ajl('taskListOffer');";
		
		if (array_key_exists("from_tech_portal",$_SESSION)){
		 if ($_SESSION["from_tech_portal"])
		 {
			if ($_SESSION["from_tech_portal_request"] == 'my_tasks')
			{
				$this->onLoadJFunction["op"]="onload=ajl_from_portal('taskListOffer');";
			}
			else
			{
				$this->onLoadJFunction["op"]="onload=ajl_from_portal('docLibrary');";
			}
		 }
		 else
		 {
			$this->onLoadJFunction["op"]="onload=ajl('taskListOffer');";
		 }
		}
		$this->accordionMenu=true;
		#$this->oldMenu=true;
		$this->wkpdf=true;
		$this->useLiquidCss=true;
		$this->systemFlags["aragedStatm"]=true;
		$this->systemFlags["arDisplayEsent"]=true;
		#$this->mailFrom="DCFM[mailto:dcfm@dcfm.com.au]";
		$this->mailFromName="dcfm";
		$this->mailReply="dcfm@dcfm.com.au";

		#in conjunction with mailMode..
		$this->mailConfirm["accounts"]="accounts@dcfm.com.au";
		$this->mailConfirm["jobs"]="dcfm@dcfm.com.au";
		$this->confirmReading=true;

		$this->sysIP="59.154.157.90";
		$this->serverName="dcfm.com.au";
		$this->owner="DCFM";
		#default position for searches is contains, not begins
		$this->searchesContain=true;
		$this->custSearchContains=true;

		#schedule stays on screen after allocation.
		$this->schedRemainsAfterAll=true;
		$_SESSION["clientpath"]=$this->clientpath;
		$_SESSION["basepath"]=$this->basepath;
		$_SESSION["clientFolder"]=$this->clientFolder;
		$_SESSION["docFolder"]=$this->docFolder;
		$_SESSION["useridonly"]=true;//userid cant rely on names

		$_SESSION["uploadFolder"]["jobs"]=$this->docFolder["jobs"];
		$_SESSION["uploadFolder"]["customer"]=$this->docFolder["customer"];
		$_SESSION["uploadFolder"]["jobsafety"]=$this->docFolder["jobsafety"];




		$this->contextIfsHoriz["job"]=350;

		$this->notips=true;
		$this->noSearchTips=true;

		#$this->menuLogo="<img src=\"../images/logos/logo250.jpg\">";
		#$this->menuLogo="<img src=\"../images/logos/tn250_DCFM Logo.png\">";
		#$this->menuLogo="<img src=\"../images/logos/DCFM2011.png\">";
		#$this->menuLogo="<img src=\"./load.gif\">";
		$this->menuLogo="<img src=\"../images/logos/tn_DCFM Logo1.png\">";

		$this->newTriggersCustom["custnotes"]=true;
		$this->editTriggersCustom["custnotes"]=true;
		$this->newTriggersCustom["jobnote"]=true;
		$this->newTriggersCustom["task"]=true;

		$this->newTriggersCustom["jobs"]=true;
		$this->editTriggersCustom["jobs"]=true;

		$this->newTriggersCustom["customer"]=true;
		$this->editTriggersCustom["customer"]=true;
		$this->customFieldUpdateRqd["jobs"]["jobstage"]=true;
		$this->customFieldUpdateRqd["jobs"]["quotestatus"]=true;
		$this->customFieldUpdateRqd["jobs"]["sitefm"]=true;

		$this->altSearchPage["jobs"]="onclick=javascript:iscreen(\'rjobs\')";

		#store tables that use an interface rather than default card layout
		$this->customMasterInferface=kda("jobs");
		$this->postSearchPopulateBehaviour["job"]=true;
		$this->postSearchPopulateBehaviour["externalAllocation"]=true;
		$this->customDateRules=true;


		$this->contextSearchConditions["externalAllocation"]=" and custtype like 'Supplier' ";
		$this->contextSearchConditions["job"]=" and ucase(custtype) like '%CLIENT%' ";
		$this->postIscreenBehaviour["externalAllocation"]=true;
		$this->contextIfsFields["job"]=kda("companyname,mail1,mailsuburb,customerid");

		#master tables to log changes for.
		$this->masterLogging=kda("jobs");

		$this->inDcfmCourtStageA=kda("qte_responded");
		$this->useCustomSched=true;
		$this->searchPrefilter["customer"]=true;
		$this->useTerritoryAlgorithm=true;
		$this->customFtype["jobs"]["shipsuburb"]="iisuburbBoxJq";
		$this->manageContractorSchedules=true;
		$this->allowMultiAllocation=true;
		#$this->noMasterExtras=true;
		$this->standardScrollDimensions="height:400px; width:600px; overflow:auto;";
		$this->customVariables();
		#sched prefs
		#$this->useUITabs=true;
		$this->startMulti=true;
		$this->customDisplayFields["salesorder"]=kda("soref,sodate,property,billee");

		//ANK FIX
		if(array_key_exists("primaryid",$_SESSION)){
		  $this->action["reportpack"]="$"."this->clientreportsetup('".$_SESSION["primaryid"]["customer"]."');";
		}
		$this->customTempSearchA=kda("sitecont");
		$this->useCustomGridSearchQuery["sitecont"]=true;
		$this->customSearchRequired["sitecont"]=true;


		#$this->useCustomJoiners["inventory"]=true;
		$this->customGridSearchQueryPrefix["sitecont"]["normal"]["companyname"]="c.";

		#preferred condition will restrict search on supplier order searches, but otherwise wrong suppliers will appear on searches.
		#$this->customGridSearchQueryCondx["inventory"]=" (ps.preferred='on' or ps.prodid is null)";
		$this->customTempSearchContext["inventory"]["polines"]=true;

		$this->customTabTreatment["addresslabel"]=true;

		#now in iiscript
		#$this->customJobNoteFunction="$"."this->jobNoteMailer();";

		##refocus csr contexts
		$this->csrRefocus["job"]="billingaddress";
		$this->arStatmentsShowRecentPay=true;
		$this->gridYearLimit=4;
		#$this->powerUsers=kda("service5,manager,bdm,operations,finance,nswoperations,estimator2,business,vicoperations,accounts,accounts2,compliance");

		#ANK Move this to userseurity table
		$qs = "SELECT userid FROM usersecurity us INNER JOIN usersecurityfunction usf ON us.functionid=usf.functionid
		WHERE functionname = 'poweruser' AND hasaccess=1";
		$tfs = kda("userid");
		$das = iqa($qs,$tfs);
		foreach ($das as $srow){
			$pua[] = $srow['userid'];
		}
		#$this->logit(implode(",",$pua));
		#$this->logit("powerusers");
		$this->powerUsers = $pua;
		
		#option to allow GP on inv history
		$this->allowInvHistGp=true;

		#contact searches on customer?
		$this->customerContactSearch=true;

		$this->atbViaJq["po"]=true;
		$this->atbViaJq["trf"]=true;
		$this->atbViaJq["adj"]=true;


		$this->partialExtraList=kda("inventory");
		$this->contactNotesAppendMailLog=true;
		$this->statementTemplateName="statement_";
		$this->noFullEdit["jobs"]=true;


	}

    function customUserSettings(){
    	 $extraUserButtons="
    	 <br><button type=button onclick=\"jqUiModalExisting('divid=dialogTask!I!tn=userterritories!I!title=User Territories!I!u=')\">Assign territory</button>
		 <br><button type=button onclick=\"jqUiModalExisting('divid=dialogTask!I!tn=usersitefm!I!title=Assign Sitefm!I!u=')\">Assign Sitefm</button>
		 <br><button type=button onclick=\"jmg('userSupplier','Supplier!I!u=')\">Assign Supplier</button>
		 ";
		 return $extraUserButtons;
    }

	function customVariables(){
		$bcx="onclick=priceCalculator();";
		$this->priceGridLink="<div class=\"nav3\"><button $bcx>Calculator</button></div>";
		$this->customAJXbranches=kda("gitKeyDateSetup,viewKeyDates,newsalesorder,ordsummary,skillset,saveskills,showskillgroup,showTradeSkills,savetradeskills,savetrade,showStageDetail,newJob,jobGridDetail,delContractorSkill,siteLookup,externalAction,resetContractorList,receivedRfqReply,acceptPOref,clientreports,saveClientReportSetup,externalCompletion,externalbillnow,formedit,formeditsave,internalCompletion,internalbillnow,calcddue,deleteRfq,deleteJrq,receivedRfqResponse,receivedJrqResponse,jrqSaver,applyRfq,acceptRfq,closeRfq,showKeywordSkills,saveKeywordskills,serviceareas,saveterritory,delContractorTerritory,delJob,canJob,testsched,tdragsched,saveRchain,qsent,unbillableDetail,unbilledGridDetail,acceptThenAllocate,externalImport,saveAttach,newContactEmail,jobNoteMailNow,jobNoteMailNowUIDryRun,jobNoteMailNowUI,jobQuoteMailNowUIDryRun,jobQuoteMailNowUI,jobJrqMailNowUIDryRun,jobJrqMailNowUI,jobRfqMailNowUIDryRun,jobRfqMailNowUI,contractorBatchUIDryRun,contractorBatchMailNowUI,rfqCompletor,jobCompletor,jobQuoteMailNow,saveSafetyAttach,saveOptionalJobDoc,costgrid,pocostdaysummary,pocostdaydetail,invCreditJob,allocateJobTimeFcal,closeQuoteNote,quoteFollowUp,contractorReminderBulk,contractorBatchPremail,jobNoteComplete,saveSimpleJobMail,jobEmailNowUIDryRun,jobEmailNowUI,poBatchInvPdf,poBatchInvPdfSend,batchReportPdf,tmakePdfPack,batchReportHtml,unbillableDelJrq");
		#used to include makeprimaryMerge,makesecondaryMerge,delSecondary,processMerge
		#dates to process after auto guess
		$this->dateFields=kda("confirm_details,place_order,loaded_on_ship,arrived_in_sydney");
	}


function getLeftNav($mode){
	return false;
	$vbslink="javascript:ticketReport();";
	$sdlink="javascript:ticketReport();";
	$actlink="javascript:showActionList();";
	$emlink="javascript:showEmailEdit();";

	//$aclink="javascript:showSearchGrid();";
	$aclink="javascript:leftMenuChoice('Search')";
	$aclink2="javascript:leftMenuChoice('neworder')";
	$oldaclink2="javascript:window.open('isorder.php')";
	//$aclink2="javascript:neworder('isorder.php')";
	$npolink="javascript:newpo()";
	$link3="javascript:orderlist()";

	$link4="javascript:leftMenuChoice('Info Centre')";
	$link5="javascript:document.location='../crmlogin.php'";
	$link6="javascript:schedule('week')";
	$link6b="javascript:schedule('multi')";
	$callLink="javascript:phonecallEntry()";
	$cmlinnk="javascript:customLists()";
	$newinvL="javascript:newMaster('inventory','normal')";
	$link7="javascript:invgrid()";
	$admin="javascript:leftMenuChoice('admin')";
	$ssearch="javascript:iscreen('skillsearch')";
	#$subbylist="javascript:iscreen('subbyList')";
	$subbylist="javascript:ibuffer('subbyList')";
	$tradex="javascript:iscreen('tradeSkill')";
	$keyx="javascript:iscreen('keyWords')";
	#$newjob="javascript:newJob()";
	$newjob="javascript:reloadWJSF('newJob')";

	$sched="javascript:singleUserSchedule('reset')";
	$sched2="javascript:testsched()";

	$qroller="javascript:iscreen('rjobs','q')";
	$qproller="javascript:iscreen('rjobs','qp')";
	$holyroller="javascript:iscreen('rjobs','hold')";
	$sched3="javascript:dschedule();";

	#revised rollers
	$scrollSort="javascript:ibuffer('scrollSort.html','q')";
	$qroller="javascript:ibuffer('rjobs.php','m=q!I!t=RollingJobs')";
	$qproller="javascript:ibuffer('rjobs.php','m=qp!I!t=RollingJobs')";
	$holyroller="javascript:ibuffer('rjobs.php','m=hold!I!t=RollingJobs')";


	$schedw="isched.php";
	$schedw2="ijqcal.php";
	$tasks="javascript:ibuffer('taskList.php')";
	$phoneN="javascript:jqUiModalExisting('divid=dialogPhonecall!I!tn=phonecall!I!title=Phone')";


	$newMasterLink="javascript:leftMenuChoice('New Company')";
	$custSearch="javascript:searchPage('customer');";
	$contSearch="javascript:searchPage('contact');";
	$jobsearch="javascript:searchPage('jobs');";
	#$jobsearch="javascript:relocate('iint.php?u1=searchPage&u2=jobs');";
	$oldroller="javascript:iscreen('rjobs')";

	$roller="javascript:ibuffer('rjobs.php')";
	$recuring="javascript:ibuffer('rjobs.php','m=recurring!I!')";
	#$roller="javascript:relocate('iint.php?u1=iscreen&u2=rjobs');";
	$jobgrid="javascript:iscreen('jobStatusGrid')";
	$cjobgrid="javascript:ibuffer('currentGrid')";
	#$jobgrid="javascript:relocate('iint.php?u1=iscreen&u2=jobStatusGrid')";
	#$newjob="javascript:newJob()";
	$newjob="javascript:reloadWJSF('newJob')";

	#$newjob="javascript:relocate('iint.php?u1=newJob')";
	#$newjob="iint.php?u1=newJob";

	$iroller="javascript:iscreen('rjobs','i')";

	$clister="javascript:iscreen('custListMaker','i')";

	$unbillgrid="javascript:iscreen('unbilledGrid')";
	$cdesign="javascript:ibuffer('fdesign')";

/*
		"New Inventory"=>$newinvL,
		"New Sales Order"=>$aclink2,
		"old New Sales Order"=>$oldaclink2,
		"New Purchase Order"=>$npolink,
		"Order List"=>$link3,
		"Invoice List"=>$link7,
		"Info Centre"=>$link4,
		"New Call"=>$callLink,
		"Schedule"=>$link6,
		"Multi Schedule"=>$link6b,
		"Lists"=>$cmlinnk,
*/
	$dbn="dbn ".$GLOBALS["dbn"];
	$tgrid="javascript:newWindow('test1.html')";
	$cmodal="javascript:testmodal()";
	#$contSearch="javascript:ibuffer('siteContSearch')";
	$contSearch="javascript:searchPage('sitecont');";

	$manLink="http://systems.velocityweb.com.au/infomaniac_manual/dcfm.php";
	$manLink="http://info.velocityweb.com.au/tutorials/dcfm";
	switch($mode){
		case "admin":
		break;
		default:
		#"Form Designs"=>$cdesign,
		$tpick="javascript:ibuffer('tpick')";
		$nava=array(
		"Trade Contractors"=>$subbylist,
		"Search Names"=>$custSearch,
		"Search Contacts"=>$contSearch,
		"Job Search"=>$jobsearch,
		"Skill Groups"=>$ssearch,
		"Trade Skills"=>$tradex,
		"Key Words"=>$keyx,
		"New Company"=>$newMasterLink,
		"New Job"=>$newjob,
		"Rolling Jobs "=>$roller,
		"Unbilled Jobs "=>$iroller,
		"Unbilled Grid "=>$unbillgrid,
		"Rolling Quotes"=>$qroller,
		"Quotes pending decision"=>$qproller,
		"Recurring Jobs "=>$recuring,
		"Held Jobs "=>$holyroller,
		"Info Centre"=>$link4,
		"Job Grid"=>$jobgrid,
		"Current Grid"=>$cjobgrid,
		"Admin"=>$admin,
		"Lists"=>$cmlinnk,
		"Schedule"=>$schedw,
		"Schedule 2"=>$schedw2,
		"Tasks"=>$tasks,
		"New Call"=>$phoneN,

		"Reports"=>"javascript:ibuffer('sqlReporter.php');",
		"Charts"=>"javascript:ibuffer('charts.php');",
		"Import Names"=>"javascript:newWindow('./conversions.php');",
		"Manual"=>"javascript:newWindow('$manLink');",
		"Log out"=>$link5
		);

/*		"$dbn"=>"xx",
		"test grid"=>$tgrid,
		"test contact"=>$cmodal,
		"C List reports"=>$clister,
		"Scroll Sort"=>$scrollSort,
		"old Rolling Jobs "=>$oldroller,

*/

		#"test Schedule"=>$sched2,
		#"display Schedule"=>$sched3,

		//"Return to Primary CRM"=>$aclink);
		break;

		case "sched":
		$dt=date("Y-m-d");
		$scheds="isched.php?t=s&dt=$dt";
		$schedm="isched.php?t=m&dt=$dt";
		$schedw="isched.php?t=t&dt=$dt";

		$nava=array(
		"Today - Single"=>$scheds,
		"Today - Multi"=>$schedm
		);

		break;

		case "ar":
		$invhist="javascript:invgrid()";
		$arlink="javascript:ajl('araged_ajl')";
		$docSearch="javascript:ibuffer('docsearch')";

		$chlink="javascript:iscreenBase('cashSummary')";
		$deplink="javascript:iscreenBase('depositSummary')";

		$arjlink="javascript:aradjgrid()";
		$crlink="javascript:iscreen('cashReceipts')";
		$crlink2="javascript:ibuffer('cashReceipt')";
		#$dslink="javascript:window.open('iformdoc.php?fid=hodgDepSlip&custform=1')";
		$dslink="javascript:neworder('iformdoc.php?fid=depSlip&custform=1')";

		$resetx="javascript:clearDepositSlip()";
		$ajlink="javascript:newArAdj()";
		$tslink="javascript:ibuffer('timesheet')";
		$costlink="javascript:costgrid()";
		$newgl="javascript:newMaster('glchart','normal')";
		$glchart="javascript:ibuffer('glChart')";

		#"Deposit Slip"=>$dslink,
		#"Reset Deposit Slip"=>$resetx,
		#"Deposit History"=>$deplink,

		$nava=array(
		"Aged Receivables"=>$arlink,
		"Invoice History"=>$invhist,
		"Cash History"=>$chlink,
		"Cost Summary"=>$costlink,
		"Adjustment History"=>$arjlink,
		"New Cash Receipt"=>$crlink2,
		"New Cash Adjustment"=>$ajlink,
		"Invoice Search"=>$docSearch,
		"New GL Code"=>$newgl,
		"Chart of Acc"=>$glchart,

		"Time Sheet"=>$tslink,
		);
		//"Return to Primary CRM"=>$aclink);
		break;


	}

 	return $nava;
}


function lowerLeftMenu(){
   		#$admin="javascript:iscreen('infcentre','admin')";
		$admin="javascript:leftMenuChoice('admin')";

		$alink="./iint.php?mode=ar";
		$aplink="./iint.php?mode=ap";
		$olink="./iint.php?mode=op";
		$plink="./iint.php?mode=po";
		$glink="./iint.php?mode=gl";
		$link5="javascript:document.location='../crmlogin.php'";

		$saleslink="./iint.php?mode=sales";

		$aclink="javascript:leftMenuChoice('Search')";
		$ilink="javascript:searchPage('inventory')";
		$clink="javascript:searchPage('customer')";
		#$schedlink="./iint.php?mode=sched";

		$schedlink="isched.php?module=1";


		/*"Sales Transactions"=>$saleslink,
		"Inventory Transactions"=>$plink,
		"Accounts Payable"=>$aplink,
		"GL Reports"=>$glink,
		"Admin"=>$admin,
		*/
		#$prelink="javascript:newWindow('../dayplan.php');";
		#$prelink="javascript:newWindow('../../dcfmsite/dayplan.php');";
		$prelink="javascript:newWindow('../../infomaniac/dayplan.php');";
		$tslink="javascript:ibuffer('timesheet');";
		$schedw2="ijqcal.php";

		$nava=array(
		"Operations"=>$olink,
		"Accounts Receivable"=>$alink,
		"Schedule"=>$schedw2,
		"Admin"=>"./iint.php?mode=ad",
		"Reports"=>"javascript:ibuffer('sqlReporter.php');",
		"Log out"=>$link5,
		);

		$userid=$_SESSION["userid"];
		$moduleQ="select moduleid,url, moduledesc from module where userid='$userid'
		and (hidden is null or hidden!='on')
		order by pos ";
		$tf=kda("moduleid,moduledesc,id,url");
		$mda=iqa($moduleQ,$tf);
		$msz=sizeof($mda);
		tfw("moduleq.txt","mq $moduleQ sz $msz",true);
		if(sizeof($mda)>0){
			unset($nava);
			#if db links exist, replace the default nav
			foreach($mda as $mrow){
				$url=$mrow["url"];
				$moduleid=$mrow["moduleid"];
				$mdesc=$mrow["moduledesc"];
				if($moduleid!=""){
					$url="./iint.php?mode=$moduleid";
				}
				$nava[$mdesc]=$url;
				$mxx.="$mdesc = mu: $url";
			}
		}
		tfw("moduleq2.txt","mq $mxx $moduleQ sz $msz",true);



#		"XML Purchase Order"=>$xml,
		return $nava;
}

function getExtraLeftNav($mode=null){
	$lla=$this->lowerLeftMenu();
	if(isset($lla)){
	foreach($lla as $label=>$url){
		$optionalTypeA=explode(":",$url);
		if($optionalTypeA[0]=="nyro"){
			$url=$optionalTypeA[1];
			$linkx.="<LI class=\"\"><A  href=\"$url\">$label</A>";
		}else{
			$linkx.="<LI class=\"\"><A  href=\"$url\">$label</A>";
		}
	}
	}
	$ex="<DIV id=\"extraNavContent\" >$linkx</DIV>";
	switch($mode){
	}

	return $ex;
}

function getExtraLeftNavX($mode=null){
	$lla=$this->lowerLeftMenu();
	if(isset($lla)){
	foreach($lla as $label=>$url){
	 $linkx.="<LI class=\"\"><A  href=\"$url\">$label</A>";
	}
	}
	#$ex="<DIV id=\"extraNavContent\" ><br><br>Modules $linkx</DIV>";
	$ex="$linkx";
	switch($mode){
	}

	return $ex;
}



function subNavSetup(){
	$this->leftMenuSub["Search"]=array(
		"customer"=>"javascript:searchPage('customer');",
		"inventory"=>"javascript:searchPage('inventory');");

	//$link4="javascript:iscreen('infcentre')";

	$this->leftMenuSub["Info Centre"]=array(
		"logistics"=>"javascript:iscreen('infcentre','logi');",
		"accounting"=>"javascript:iscreen('infcentre','acc');",
		"sales"=>"javascript:iscreen('infcentre','sales');"
		);

	$this->loadInputFormTypes('customer');
	foreach($this->iftypesA as $ftype){
		$this->leftMenuSub["New Company"][$ftype]="javascript:newMaster('customer','$ftype','main-content')";
	}
}


	function priceCalculator($prodid){
		include_once "$this->clientpath/customscript/pricecalcAJ.php";
		$cpc=new priceCalculator();
		$cpc->prodid=$prodid;
		$cpc->displayCalc();

		/*an ajax process*/
		//echo "alert('pc hey')\n";
		//echo "var dx=g('main-content').innerHTML \n";
		//$pcx="<div>other stuff</div>";
		//$pcx=$cpc->calcx;
		$pcx=vClean($cpc->calcx);

		//echo "var newmc=dx \n";

		//echo "g('nav2').style.display = 'none'\n";
		echo "g('guts').style.display = 'none'\n";
		//echo "g('main-content').style.display = 'none'\n";
		echo "g('main-content').style.display = 'block'\n";
		echo "g('guts').innerHTML='$pcx' \n";
		echo "g('guts').style.display = 'block'\n";

	}

    function pricePreview($prodid,$mode,$fob,$exr,$frr,$dutyr,$mup,$muph,$mupr,$fitc,$gstinc,$pricebreaks){
	  $sx="prodid:	$prodid fob:$fob exr:$exr, frr: $frr, dutyr: $dutyr, mup: $mup, muph: $muph, mupr: $mupr, fitc: $fitc, gst:$gstinc, pbk:$pricebreaks";
	  if($exr>0){
	  	$localprice=round(($fob/$exr),2);
	  }else{
	  	$localprice=$fob;
	  }
	  $landa=number_format($localprice*(1+($frr/100)),2);
      $landa=number_format($landa*(1+($dutyr/100)),2);
     //echo "alert('44 land: $landa')\n;";
      $wsell=$landa*(1+($mup/100));
      $hsell=$wsell*(1+($muph/100));
      $rsell=$wsell*(1+($mupr/100));

	  $wsell=number_format($this->pnear5($wsell, .05),2);
	  $hsell=number_format($this->pnear5($hsell, .05),2);
	  $rsell=number_format($this->pnear5($rsell, .05),2);

      //echo "alert('pid $prodid rsell $rsell')\n";
      $rfitsell=number_format($rsell+round($fitc,2),2);
      $rgst=$rsell;
      $rfitsellgst=$rfitsell;

 	  $_SESSION["gstrate"]=.1;
 	  if($gstinc=='on'){
 	  	 //echo "alert('inc gst ".$_SESSION["gstrate"]."')\n;";
         $rgst=number_format($rsell*(1+$_SESSION["gstrate"]),2);
         $rfitsellgst=number_format($rfitsell*(1+$_SESSION["gstrate"]),2);
		 }else{
 	  	 //echo "alert('not inc gst ".$_SESSION["gstrate"]."')";

	 }

        //alert(rsell+' '+fitc+' '+rfitsell);
	 //alert('local:'+localprice);

 	 echo "g('buya').value=$localprice;\n";
	 echo "g('lnp').value=$landa;\n";
   	 //echo "alert('ppb 73 $localprice $pricebreaks')\n;";
	 echo "g('wsp').value='".$this->pnear5($wsell, .05)."';\n";
	 echo "g('hpp').value='".$this->pnear5($hsell, .05)."';\n";
	 echo "g('rp').value='".$this->pnear5($rsell, .05)."';\n";
	 echo "g('rpf').value='".$this->pnear5($rfitsell, .05)."';\n";
	 echo "g('rgst').value='".$this->pnear5($rgst,.05)."';\n";
	 echo "g('rgstf').value='".$this->pnear5($rfitsellgst, .05)."';\n";



 	 if($pricebreaks=='on'){
    	 //echo "alert('ppb 74 $pricebreaks')\n;";
	 	$ws10=number_format($this->pnear5($wsell*.9,.05),2);
	 	$ws20=number_format($this->pnear5($wsell*.8,.05),2);
	 	$ws50=number_format($this->pnear5($wsell*.75,.05),2);
	 	$hs10=number_format($this->pnear5($hsell*.9,.05),2);
	 	//$hs10="xx";
	 	$hs20=number_format($this->pnear5($hsell*.8,.05),2);
	 	echo "g('ws10').value='$ws10';\n";
	 	echo "g('ws20').value='$ws20';\n";
	 	echo "g('ws50').value='$ws50';\n";
	 	echo "g('h10').value='$hs10';\n";
	 	echo "g('h20').value='$hs20';\n";


	 	//echo "g('ws10').value=near5($ws10,.05);\n";
	 	//echo "g('ws20').value=near5($ws20,.05);\n";
	 	//echo "g('ws50').value=near5($ws50,.05);\n";
	 	//echo "g('h10').value=near5($hs10,.05);\n";
	 	//echo "g('h20').value=$near5($hs20,.05);\n";

        }else{
    	 //echo "alert('noppb 93 wtf')\n;";

	 	echo "g('ws10').value='';\n";
	 	echo "g('ws20').value='';\n";
	 	echo "g('ws50').value='';\n";
	 	echo "g('h10').value='';\n";
	 	echo "g('h20').value='';\n";
     }

	 settype($landa,"string");
	 settype($wsell,"string");
	 settype($hsell,"string");
	 settype($rsell,"string");
	 settype($rfitsell,"string");
	 settype($rgst,"string");
	 settype($rfitsellgst,"string");
	 settype($ws10,"string");
	 settype($ws20,"string");
	 settype($ws50,"string");
	 settype($hs10,"string");
	 settype($hs20,"string");
	 settype($localprice,"string");

     $para=array(
     "fob"=>$fob,
     "exr"=>$exr,
     "frr"=>$frr,
     "dutyr"=>$dutyr,
     "mup"=>$mup,
     "muph"=>$muph,
     "mupr"=>$mupr,
     "fittc"=>$fitc,
     "gsta"=>$gstinc,
     "ws10"=>$ws10,
     "ws20"=>$ws20,
     "ws50"=>$ws50,
     "h10"=>$hs10,
     "h20"=>$hs20,
 	 "buya"=>$localprice,
	 "lnp"=>$landa,
	 "wsp"=>$wsell,
	 "hpp"=>$hsell,
	 "rp"=>$rsell,
	 "rpf"=>$rfitsell,
	 "rgst"=>$rgst,
	 "rgstf"=>$rfitsellgst,
     "pricebreaks"=>$pricebreaks
     );

	//echo "alert('mode: $mode')\n;";

     switch($mode){
     	case "reset":
	    /*new prices & settings*/
     	//echo "alert('save $prodid r: $rsell $rsf')\n";
     	$this->saver($prodid,$wsell,$hsell,$rsell,$rfitsell,$ws10,$ws20,$ws50,$hs10,$hs20);
	    $this->update_calcvals($prodid,$para);
    	echo "alert('Prices Saved')\n";
     	break;

     	case "save":
	    /*no new prices, just settings*/
	    $this->update_calcvals($prodid,$para);
    	echo "alert('Settings Saved')\n";
     	break;

     	case "preview":
	    /*screen only*/
    	 //echo "alert('234 wtf')\n";
     	break;

     }

	     // echo "alert('148 $mode')\n;";
		 // return;
    	 //echo "alert('238 wtf')\n";

    }



  function saver($prodid,$ws,$hs,$rs,$rsf,$ws10,$ws20,$ws50,$h10,$h20){
    $dql="delete from prod_cust_pricing where prodid='$prodid'";
    $result = mysql_query($dql);


    $this->newprice($prodid,"WS",1,$ws);
    $this->newprice($prodid,"HOSP",1,$hs);
    $this->newprice($prodid,"RETAIL",1,$rs);
    $this->newprice($prodid,"RETAILFIT",1,$rsf);

    $this->newprice($prodid,"WS",10,$ws10);
    $this->newprice($prodid,"WS",20,$ws20);
    $this->newprice($prodid,"WS",50,$ws50);

    $this->newprice($prodid,"HOSP",10,$h10);
    $this->newprice($prodid,"HOSP",20,$h20);


  }

  function newprice($prodid,$band,$minq,$price){
    $da["priceband"]=$band;
    $da["prodid"]=$prodid;
    $da["basemethod"]="STATIC";
    $da["price"]=$price;
    $da["minqty"]=$minq;
    $da["rounding"]=2;
    $da["surcharge"]=0;
    $da["basefield"]='0';
    $t="prod_cust_pricing";
    if($price>0){
  	  performarrayinsert($t,$da);
  	  //saveq
    }
  }

  function update_calcvals($prodid,$para){
   $t="inventory";
   //$pnx=implode("|",$parn);
   //$pvx=implode("|",$parv);
   $psx=serialize($para);
   //echo "<br>$pnx";
   //echo "<br>$pvx";
   //filewriter("psx.txt",$psx);
   $da["pricecalcsettings"]=$psx;
   //$da["pricecalcvalues"]=$pvx;
	//echo "alert('nsv')\n;";
   performarrayupdate($t,$da,"prodid",$prodid);
  }

  function delpricebreak($prodid,$priceid){
  	$dq="delete from prod_cust_pricing where prodid='$prodid' and priceid='$priceid'";
  	mysql_query($dq);
  }


   function pnear5($value, $number) {
	  $rawvalue=$value;
	  $value = number_format((round(20*$value))/20,2);
	  if ($value == floor($value)){
	     $value.='.00';
	  }
	  if ($value == floor($value*10)){
	     $value.='0';
	  }
	  //tfw("pn5.txt","$rawvalue now $value",false);
	  return $value;

/*	  $cx="** $value $number";
	  $ceil = ceil($value);
	  $remainder = $value % $number;
	  $cx.=$remainder;
	  echo "alert('$value % $number rem: $remainder');\n";
	  if ($remainder > 0){
	    $value = $value - $remainder + $number;
	  }
	  echo "alert('newv v-rem+.05 $value');\n";
	  $cx.="new val: $value";
	  $value = round($value*100)/100;
	  if ($value == floor($value)){
	     $value.='00';
	  }
	  if ($value == floor($value*10)){
	     $value.='0';
	  }
	  echo "alert('$value');\n";
     //filewriter("pnp.txt",$cx,false);
	  return round($value,2);
*/

	}


function fieldSubstitutionsNewRecord($tablen,$da){
    $tfa=totalfieldarray($tablen);
	switch($tablen){
		case "customer";
		$da["referalcustid"]=$da["referringcustid"];
		$da["alt_billee"]=$da["altid"];

		unset($da["customerid"]);
		foreach($da as $fn=>$fv){
			if(!in_array($fn,$tfa)){
				unset($da[$fn]);
			}
		}
		$cname=$da["companyname"];
		if($cname==""){
			$cname=$da["surname"].", ".$da["firstname"];
			$da["companyname"]=$cname;
		}

		break;

		case "jobs":
		if($da["siteline1"]!="") $site[]=$da["siteline1"];
		if($da["siteline2"]!="") $site[]=$da["siteline2"];
		if($da["siteline3"]!="") $site[]=$da["siteline3"];
		$siteSSS[]=$da["sitesuburb"];
		$siteSSS[]=$da["sitestate"];
		$siteSSS[]=$da["sitepostcode"];
		$sp1=implode("\n",$site);
		$sp2=implode(" ",$siteSSS);
		$da["siteaddress"]=$sp1."\n".$sp2;


		foreach($da as $fn=>$fv){
			$fax.="$fn $fv";
			if(!in_array($fn,$tfa)){
				unset($da[$fn]);
			}
		}

		break;

		default:
		foreach($da as $fn=>$fv){
			$fax.="$fn $fv";
			if(!in_array($fn,$tfa)){
				unset($da[$fn]);
			}
		}
		tfw("fax.txt",$fax,true);
		break;


	}

	return $da;
}

/**custom switch detection and functions**/
function customSwitch($switchx,$qstx){
	switch($switchx){
		case "gitKeyDateSetup":
		$git=new gitInterface($gitid,"edit");
		$git->gitKeyDateSetup($aS);
		break;

		case "viewKeyDates":
		//$gitKeyDateSetup($aS,$qstx[1],$qstx[2]);
		//echo "alert('got $qstx[1],$qstx[2]');\n";
		$pda=array($qstx[1],$qstx[2]);
		iScreen($aS,"keyDateReviewDetail",$pda);
		break;

		case "newsalesorder":
		$this->newsalesorder();
		break;

/*ioeajx calls*/
			case "prodEntry":
			//echo "alert('xx');\n";
			tempFieldSaver($oe);
			$this->prodEntry($qstx[1]);
			break;

			case "ordSummary":
			//echo "alert('ordsumm');\n";
			$this->orderSummary();
			break;

			case "loadPrices":
			$this->custPricesInfo($oe,$qstx[1]);
			break;

			case "postprocess":
			//echo "alert('now?')\n";
			$this->postProcess();
			break;

			case "calcLine":
			//echo "alert('now?')\n";
			$this->calcLine($qstx[1],$qstx[2],$qstx[3]);
			break;

			case "firstInventoryLoad":
			$this->loadInventoryDetail($qstx[1],$qstx[2]);
			break;

			case "saveOrderLine":
			$this->saveOrderLine($oe,$qstx[1],$qstx[2],$qstx[3],$qstx[4]);
			break;

			case "removeline":
			$this->removeline($oe,$qstx[1]);
			break;

			case "taxline":
			$this->taxline($oe,$qstx[1],$qstx[2]);
			break;

			case "saveorder":
			$this->tempFieldSaver($oe);
			saveorder($oe);
			break;

			case "tempFieldSaver";
			$this->tempFieldSaver($oe);
			break;

			case "updateSalesOrder":
			$this->updateSalesOrder($oe,$qstx[1]);
			break;

			case "getLabels":
			$this->getLabels($qstx[1]);
			break;

			case 'saveTempFieldSession':
			$fn=$qstx[1];
			$fv=$qstx[2];
			//echo "alert('80 got $fn $fv')";
			//$fv=str_replace("\n","*newline*",$fv);
			$fv=str_replace("*newline*","<br />",$fv);
			//$fv=str_replace("\r","*newline*",$fv);
			   $patterns = array("n","r","line");
      			$replace = array("nn","rr","wtf");
      			//$fix = addslashes($fv);
      			//$fix = preg_replace($patterns,$replace,$fv);
      			//$fv=$fix;
			$_SESSION["oe"][$fn]=$fv;
			break;

			case "loadOrder":
			$this->loadOrder($oe,$qstx[1]);
			break;

			case "setAddLabel";
			$a=$qstx[1];
			echo "alert('ssa $a')\n";
			$this->setAddLabel($oe,$qstx[1],$qstx[2]);
			break;

			case "loadAddLabelChoices";
			$this->loadAddressLabelChoices($oe,$qstx[1]);
			break;

			case "tableupdate";
			$this->tableupdate($oe,$qstx[1],$qstx[2],$qstx[3],$qstx[4]);
			break;


/*ioeajx calls*/


		case "skillset":
		$this->displaySkillset($qstx[1]);
		break;

		case "serviceareas":
		$this->displayServiceareas($qstx[1]);
		#$this->displaySkillset($qstx[1]);
		break;

		case "saveskills":
		$this->saveskills($qstx[1],$qstx[2]);
		break;

		case "saveterritory":
		$this->saveterritories($qstx[1],$qstx[2]);
		break;


		case "showskillgroup":
		$this->showSubbySkillgroup($qstx[1]);
		break;

		case "showTradeSkills":
		$this->loadAndDisplaySkillgroup($qstx[1]);
		break;

		case "savetradeskills":
		$this->tradeskillSaver($qstx[1],$qstx[2]);
		break;

		case "saveKeywordskills":
		$this->keywordSkillSaver($qstx[1],$qstx[2]);
		break;


		case "savetrade":
		$this->savetradeSubby($qstx[1],$qstx[2]);
		break;

		case "showKeywordSkills":
		$this->loadAndDisplayKeywordSkillgroup($qstx[1]);
		break;


		case "showStageDetail":
		$this->showJobStageDetail($qstx[1]);
		break;

		case "newJob":
		$this->newjob();
		break;

		case "jobGridDetail":
		$this->jobGridDetail($qstx[1],$qstx[2],$qstx[3],$qstx[4]);
		break;

		case "delContractorSkill":
		$this->delCustSkill($qstx[1],$qstx[2]);
		break;

		case "delContractorTerritory":
		$this->delCustTerritory($qstx[1],$qstx[2]);
		break;

		case "delJob":
		$jobid=$qstx[1];
		$this->delJob($jobid);
		break;

		case "canJob":
		#echo "alert('cjd')\n";
		#exit;
		$jobid=$qstx[1];
		$this->cancelJob($jobid);
		break;

		case "siteLookup":
		$searchx=$qstx[1];
		$mode="address";
		$context="job";
		//echo "alert('got $searchx')\n";
		$this->ifs(true,"addresslabel","address","div",$searchx,$mode,$context);
		break;

		case "externalAction":
		$this->createExternalAction($qstx[1],$qstx[2],$qstx[3]);
		break;

		case "resetContractorList":
		unset($_SESSION["jobs"]["tcontractors"]);
		$this->displayContractorList();
		break;



		case "receivedRfqResponse":
		$this->recordRfqResponse($qstx[1],$qstx[2],$qstx[3]);
		break;

		case "receivedJrqResponse":
		$this->recordJrqResponse($qstx[1],$qstx[2],$qstx[3]);
		break;

		case "jrqSaver":
		$this->saveJrqDetail($qstx[1],$qstx[2]);
		break;

		case "receivedRfqReply":
		$this->recordRfqReply($qstx[1],$qstx[2],$qstx[3],$qstx[4],$qstx[5],$qstx[6]);
		break;

		case "deleteRfq":
		$this->rfqDelete($qstx[1]);
		break;

		case "deleteJrq":
		$this->jrqDelete($qstx[1]);
		break;

		case "acceptPOref":
		$this->recordPOrefAccepted($qstx[1],$qstx[2]);
		break;

		case "clientreports":
		$this->clientreportsetup($qstx[1]);
		break;

		case "saveClientReportSetup":
		$this->clientReportSetupSaver($qstx[1],$qstx[2]);
		break;

		case "externalCompletion":
		$this->externalComplete($qstx[1],$qstx[2],$qstx[3],$qstx[4],$qstx[5]);
		break;

		case "externalbillnow":
		$this->externalNewInvoice($qstx[1],$qstx[2],$qstx[3],$qstx[4]);
		break;

		case "checkDup":
				$this->checkDup($qstx[1],$qstx[2],$qstx[3],$qstx[4]);
		break;



		case "formedit":
		$this->formeditor($qstx[1],$qstx[2],$qstx[3],$qstx[4]);
		break;

		case "formeditsave";
		$this->formeditsave($qstx[1]);
		break;

		case "internalCompletion":
		$this->internalComplete($qstx[1],$qstx[2],$qstx[3]);
		break;

		case "internalbillnow":
		$this->logit("bill now params");	
		$this->logit(print_r($qstx,1));	
		$this->genInvoice("int",$qstx[1],$qstx[2],$qstx[3],$qstx[4]);
		break;

		case "calcddue":
		$this->cbuffdue($qstx[1],$qstx[2],$qstx[3],$qstx[4],$qstx[5],$qstx[6]);
		break;

		case "applyRfq":
		$this->applyRfqVal($qstx[1],$qstx[2],$qstx[3],$qstx[4],$qstx[5],$qstx[6]);
		break;

		case "acceptRfq":
		$this->acceptRfqVal($qstx[1],$qstx[2],$qstx[3],$qstx[4],$qstx[5],$qstx[6]);
		break;

		case "closeRfq":
		$this->closeRfq($qstx[1],$qstx[2]);
		break;

		case "testsched":
		$this->tsched($qstx[1]);
		break;

		case "tdragsched";
		$this->tsched($qstx[1]);
		break;

		case "saveRchain";
		$this->saveChain($qstx[1]);
		break;

		case "qsent":
		$this->qsent($qstx[1]);
		break;

		case "unbilledGridDetail":
		$this->unbilledGridDetail($qstx[1],$qstx[2]);
		break;

		case "unbillableDetail":
		$this->unbillableDetail($qstx[1],$qstx[2]);
		break;

		case "unbillableDelJrq":
		$this->unbillableDelJrq($qstx[1]);
		break;

		case "acceptThenAllocate":
		$jobstage="accepted";
		$this->qStageChange($qstx[1],$jobstage,true);
		break;

		case "saveAttach":
		$this->saveAttach($qstx[1],$qstx[2],$qstx[3],$qstx[4]);
		break;

		case "newContactEmail":
		$this->newContactEmail($qstx[1]);
		break;

		case "jobNoteMailNow":
		$this->jobNoteMailNow($qstx[1],$qstx[2],$qstx[3]);
		break;

		case "jobNoteMailNowUIDryRun":
		$this->jobNoteMailNowUIDryRun($qstx[1],$qstx[2],$qstx[3],$qstx[4],$qstx[5],$qstx[6]);
		break;

		case "jobNoteMailNowUI":
		$this->jobNoteMailNowUI($qstx[1],$qstx[2],$qstx[3],$qstx[4],$qstx[5],$qstx[6]);
		break;

		case "jobQuoteMailNowUIDryRun":
		$this->jobQuoteMailNowUIDryRun($qstx[1],$qstx[2],$qstx[3],$qstx[4],$qstx[5]);
		break;

		case "jobQuoteMailNowUI":
		$this->jobQuoteMailNowUI($qstx[1],$qstx[2],$qstx[3],$qstx[4],$qstx[5]);
		break;

		case "jobJrqMailNowUIDryRun":
		$this->jobJrqMailNowUIDryRun($qstx[1],$qstx[2],$qstx[3],$qstx[4],$qstx[5],$qstx[6]);
		break;

		case "jobJrqMailNowUI":
		$this->jobJrqMailNowUI($qstx[1],$qstx[2],$qstx[3],$qstx[4],$qstx[5],$qstx[6]);
		break;

		case "jobRfqMailNowUIDryRun":
		$this->jobRfqMailNowUIDryRun($qstx[1],$qstx[2],$qstx[3],$qstx[4],$qstx[5],$qstx[6]);
		break;

		case "jobRfqMailNowUI":
		$this->jobRfqMailNowUI($qstx[1],$qstx[2],$qstx[3],$qstx[4],$qstx[5],$qstx[6]);
		break;

		case "jobEmailNowUIDryRun":
		$this->jobEmailNowUIDryRun($qstx[1],$qstx[2]);
		break;

		case "jobEmailNowUI":
		$this->jobEmailNowUI($qstx[1],$qstx[2]);
		break;


		case "contractorBatchUIDryRun":
		$this->contractorBatchUIDryRun($qstx[1],$qstx[2]);
		break;

		case "contractorBatchMailNowUI":

		$this->contractorBatchMailNowUI();
		break;

		case "jobCompletor":
		$this->jobCompletor($qstx[1]);
		break;

		case "rfqCompletor":
		$this->rfqCompletor($qstx[1]);
		break;


		case "jobQuoteMailNow";
		#$head=$_POST["head"];
		#$eml=$_POST["eml"];
		#was thinking via post, but via db instead.
		$head=$qstx[1];
		$eml=$qstx[2];
		$ccl=$qstx[3];
		$this->jobQuoteMailNow($head,$eml,$ccl);
		break;

		case "saveSafetyAttach":
		$this->saveSafetyAttach($qstx[1],$qstx[2]);
		break;

		case "saveOptionalJobDoc":
		$this->saveOptionalJobDoc($qstx[1],$qstx[2],$qstx[3],$qstx[4],$qstx[5],$qstx[6]);
		break;

		case "costgrid":
		$this->costgrid();
		break;

		case "pocostdaysummary":
		$this->pocostdaysummary($qstx[1],$qstx[2],$qstx[3]);
		break;

		case "pocostdaydetail":
		$this->pocostdaydetail($qstx[1],$qstx[2]);
		break;

		case "invCreditJob":
		$this->invCreditJob($qstx[1]);
		break;


		case "allocateJobTimeFcal":
			$pdate=$qstx[1];
			$colN=$qstx[2];
			$view=$qstx[3];
			$date=date("Y-m-d",strtotime($pdate));
			$time=date("H:i",strtotime($pdate));
			#$colN is column offset for user.
			#multiUserPermittedA();
			#multiUserPA();
			multiUserRequested();
			$mua=array_keys($GLOBALS["oap"]);
			switch($view){
				case "agendaWeek":
				$userid=$_SESSION["fcuser"];
				break;
				case "agendaDay":
				$userid=$_SESSION["fcuser"];
				break;
				case "month":
				$userid=$_SESSION["fcuser"];
				$time="12:00";
				#find first slot
				$q="select max(end) as end from diary where dte='$date' and userid='$userid'";
				$time=iqasrf($q,"end");
				#echo "alert('ends at $time')\n";
				if($time=="") $time="08:00";
				break;
				case "agendaMulti":
				$userid=$mua[$colN];
				break;
			}
			$jobid=$qstx[4];

			#echo "alert('v $view dd $date $time n:$colN u:$userid')\n";
			$_SESSION["fcdate"]=$date;

			$result = $this->allocateJobToSchedule($date,$time,$userid,$jobid);
			if ($result != "OK"){
			 echo "alert('$result');";
			}
			#then show schedule
			#echo "g('joblist').style.display='none'\n";
			#echo "iscreen('iSchedule')\n";
			unset($_SESSION["appt"]["ajobid"]);
			#return options.. job edit (job at a time)  or job list(allocating from list)
			#echo "alert('passed j $jobid')\n";
			$jobstage="internal_incomplete";

			if($aS->altjstage!="")$jobstage=$aS->altjstage;
			$this->jobStageChange($jobid,$jobstage,false);
			if($this->useSOasJob){
				$linec=$jobid;
				$jobid=singlevalue("solines","soref","linec",$jobid);
			}

			#echo "window.location.reload();\n";
			echo "document.location='ijqcal.php' \n";
			$_SESSION["appt"]["jobview"]=$jobid;

			#then reset;
			#echo "if(g('jobid')) g('jobid').value=0 \n";


		break;
		case "closeQuoteNote":
		$this->closeQuoteNote($qstx[1]);
		#refresh tabbed panel
		$px='!I!divid=qfollowup';
		$this->quoteFollowUp($px);
		break;

		case "quoteFollowUp":
		$this->quoteFollowUp($qstx[1]);
		break;


		case "contractorReminderBulk":
		$cidx=($qstx[1]);
		tfw("crb.txt","get it $cidx",true);
		$cra=formCruncherMultiLine($cidx,"subid");
		$cax=serialize($cra);
		tfw("ippcax.txt","$lines $cax",true);
		#elog("save $cax","cinf 1448");
		$sid=$_SESSION["userid"]."creminder";
		savesysvar($sid,$cax);
		break;

		case "contractorBatchPremail":
		$cidx=urldecode($qstx[1]);
		$headx=urldecode($qstx[2]);
		tfw("crbPref.txt","get it $cidx",true);
		tfw("crbHref.txt","get it $headx",true);
		$ff="subid";
		$cra=formCruncherMultiLine($cidx,$ff);
		$craHead=formCruncher($headx);

		#$da["header"]=$headx;
		#$da["lines"]=$cidx;

		$da["header"]=$craHead;
		$da["lines"]=$cra;



		$cax=serialize($da);
		tfw("ippcax.txt","$cidx $cax head $caHeadx",true);
		$sid=$_SESSION["userid"]."cmail";
		#$cax="now what";
		savesysvar($sid,$cax);
		break;

		case "jobNoteComplete":
		$jid=$qstx[1];
		$cidx=urldecode($qstx[1]);
		tfw("jnix.txt","cid $cid $cidx",true);
		#close all job notes related to this jobnoteid
		#$jq="select jobid from jobnote where jobnoteid=$jid";
		#$jobid=iqasrf($jq,"jobid");
		#if($jobid>0){
		$uq="update jobnote set completed='on' where jobid=$jid
		and ntype='quote followup'		";
		mysql_query($uq);
		tfw("muq.txt","$jq then $uq",true);
		break;

		case "saveSimpleJobMail";
			$cx=urldecode($qstx[1]);
			$xv=urldecode($qstx[2]);
			$this->saveSimpleJobMail($cx,$xv);
		break;


		case "poBatchInvPdfSend":
		$bstr=$qstx[1];
		$this->poInvSender($bstr);
		break;


		case "tmakePdfPack";
		echo "alert('do it')\n";
		$this->tmakePdfPack();
		break;


		case "batchReportPdf":
		$bstr=$qstx[1];
		$ba=formCruncher($bstr);
		$pkA=explode("*",$ba["packd"]);
		$pkx=$ba["packd"];
		tfw("apdfbbb.txt","got $pkx from $bstr",true);
		$this->makePdfPack($pkA);
		#$this->tmakePdfPack();
		break;

		case "batchReportHtml":
		$bstr=$qstx[1];
		$ba=formCruncher($bstr);
		$pkA=explode("*",$ba["packd"]);
		$pkx=$ba["packd"];
		tfw("apdfbbb.txt","got $pkx from $bstr",true);
		$this->makeHtmlPack($pkA);
		#$this->tmakePdfPack();
		break;



		case "poBatchInvPdf":
		#next
		$bstr=$qstx[1];
		tfw("pobbbb.txt",".. $bstr",true);
		$pa=formCruncher($bstr);
		$ixa=formCruncherMultiLine($bstr,"invoiceno","!A!","!eq!");
		$ia=aFV($ixa,"invoiceno");

		foreach($ia as $i=>$invno){
			#echo "<br>16 $i $invno";
			$invA[$invno]=$invno;
			$pdfbn="invoice_$invno";
		}

		$mailmode=$pa["mailmode"];
		$custid=$pa["custid"];
		if($mailmode=="sesp"){
			$this->multiPdfName=$custid."_".date("d-m-Y_Hi");
			$pdfbn=$this->multiPdfName;
		}


		#$fid=wform('isalesinvoice');
		$fid="dcfminv07J";
		tfw("aixx$invno.txt","ss bstr $fid $invno",true);


		$this->pdfBuildViaBatch($invA,$fid,"invoice","invoices",$pdfbn);

		ob_start();
		ob_get_contents();
		ob_end_clean();
		ob_start();
		if($pdfbn!="") echo $pdfbn;
		break;


	}
}

function saveSimpleJobMail($cx,$xv){
		$xva=formCruncher($xv);
		$headx=$xva["head"];
		$attx=$xva["att"];
		$ha=formCruncher($headx,"!A!","!eq!");
		$atta=formCruncherMultiLine($attx,"fname","!A!","!eq!");
		foreach($atta as $a=>$row){
			$i++;
			$fname=$row["fname"];
			$dname=$row["dname"];
			$inc=$row["include"];
			$dtx.="fn:$fname #dn:$dname #inc:$inc";
			if($inc=="on"){
				$attDa[]=$dname;
			}
		}
		if(sizeof($attDa)>0){
			$atx=" attached: ".implode(",",$attDa);
		}

		tfw("ssjm.txt","cc $cx xv $xv hh:$headx aa:$attx",true);
		$nta["jobid"]=$ha["jobid"];
		$emb=$ha["emailBody"];
		$lastp=strpos($emb,"Thanks and regards");
		$nx=substr($emb,0,$lastp);
		$rcp=$ha["recips"];
		$nta["notes"]="email sent to $rcp $nx $atx";
		$nta["date"]=date("Y-m-d");
		$nta["timestamp"]=date("Y-m-d h:i");
		$nta["ntype"]="email";
		$nta["userid"]=$_SESSION["userid"];
		$jnid=pai("jobnote",$nta);
		echo $jnid;
}

function customPostSwitch($switchx){
		switch($switchx){
			case "formeditsave":
			$str=$_POST["str"];
			$this->formeditsave($str);
			break;

			case "externalImport":
			$eij=$_POST["eij"];
			tfw("exti.txt",$eij,true);
			$this->externalImport($eij);
			break;

		}
}


function loadAndDisplaySkillgroup($trade,$refreshForm=true){
		$tsx=$this->showTradeSkillgroup($trade);
		//$cx='ldsk';
		$cx.=vClean($tsx);
		echo "g('skillhead').style.display = 'block'\n";
		echo "g('skillhead').innerHTML='$this->headrx' \n";


		if($refreshForm){
			echo "g('skillform').style.display = 'block'\n";
			echo "g('skillform').innerHTML='$cx'\n";
		}
		$this->showSelectedSkillSet();

}

function showSelectedSkillSet(){
		//echo "alert('sts')\n";
		echo "g('skillsSelected').style.display = 'block'\n";
		//echo "alert('sts')\n";
		$stx=vClean($this->skillTable);
		//$stx="wtf";
		echo "g('skillsSelected').innerHTML='$stx'\n";
}



function loadAndDisplayKeywordSkillgroup($keyword,$refreshForm=true){
		$tsx=$this->showKeywordSkillgroup($keyword);
		$cx.=vClean($tsx);
		echo "g('skillhead').style.display = 'block'\n";
		echo "g('skillhead').innerHTML='$this->headrx' \n";


		if($refreshForm){
			echo "g('skillform').style.display = 'block'\n";
			echo "g('skillform').innerHTML='$cx'\n";
		}
		$this->showSelectedSkillSet();
}


function newsalesorder(){
	$x="new job here";
	if($_SESSION["nj"]){
	$x="reset ?";
	}
	//$this->resetJobBasket();
	//echo "alert('edit $poref');\n";
	#header stuff;
		$sfn="xx";
		$oe=new orderInterface();
		$x="reads ok";
		#$ji->poSummaryPage($sfn,'edit',$poref);
		$this->orderSummary($oe);

		#$headx=vClean($ji->fx);
		#$headjcall=$ji->initLookupCall;
		#line stuff;
		#$ji->poLines();
		#$linex=vClean($ji->polinex);
		#//$jx=$headx.$linex;
		#$jx=$headx;


	#$ji->prodEntry();


	/*load blank divisions*/
	#$jx.=$ji->lineEntryDiv;
	#$jx.=$ji->spanelx;
	#$jx.=$ji->lineContentDiv;


	#$pepx=vClean($ji->pep);
	#$jcx=vClean($jx);
	#$jclx=vClean($jlx);

	$jx="head";
	echo "g('main-content').innerHTML='$jx' \n";
	#echo "g('line-content').innerHTML='$linex' \n";
	#echo "g('lineEntry').innerHTML='$ji->pep' \n";

	#$jcall=$ji->initLookupCall;
	#$jcall.="g('custsearch').focus();\n";
	#echo $headjcall;
	#echo $jcall;
	#$_SESSION["nj"]=true;

}

/*ioeajx functions*/
function prodEntry($custid){
	$oe=new orderInterface();
	$da=readcust($custid);
	$priceband=$da["priceband"];
	$disc=$da["prod_discountcode"];
	$suburb=$da["shipsuburb"];
	$cname=$da["companyname"];
	$_SESSION["oe"]["cname"]=$cname;
	$_SESSION["oe"]["suburb"]=$suburb;
	$_SESSION["oe"]["priceband"]=$priceband;
	$_SESSION["oe"]["customerid"]=$custid;
	$_SESSION["oe"]["disc"]=$disc;

	echo "g('orderheadcustid').innerHTML='$custid'\n";
	echo "g('orderheadpriceband').innerHTML='$priceband'\n";
	echo "g('orderheaddisc').innerHTML='$disc'\n";
	//echo "alert('28ok')\n";
	$cx="&nbsp;";
	$osp=$oe->prodEntryPage();

	$cx.=vClean($oe->pep);

	$oe->basketLines();
	$cx.=vClean($oe->blx);


	echo "g('main-content').style.display = 'block'\n";
	echo "g('main-content').innerHTML='$cx'\n";
    //echo "pcLookup()\n";
    $jcall=$oe->initLookupCall;
    //$jcall.="alert('ffjjwtt')\n";
    $jcall.="g('searchval').focus();\n";

	//filewriter("oss.txt",$jcall,true);
	//filewriter("pep.txt",$cx,true);
    //echo "alert('wtt')\n";
//    echo "g('searchval').value='killme'\n";
    echo "g('searchval').blur();\n";
    echo "g('searchval').focus();\n";

    echo $jcall;


}

function orderSummary($oe){
		//echo "alert('28ok')\n";
		//$cx="xx";
		$sfn="shipsuburb";
		$oe->orderSummaryPage($sfn);

		//$cx.="nyet";
		$cx.=vClean($oe->osp);
		//$cx.=$oe->osp;

		$oe->basketLines();
		$cx.=vClean($oe->blx);
		//$cx=str_replace("<txtaNl>","\\n",$cx);

		if($_SESSION["oe"]["soref"]>0){
				//$ocx="onclick=updateSalesOrder(".$_SESSION["oe"]["soref"].");";
				$ocx="onclick=updateSalesOrderForm(this)";
				//$ocx="onclick=document.salesorderhead.submit();";
				$buttons="<button $ocx>Update Order ".$_SESSION["oe"]["soref"]."</button>";
				$cx.="$buttons";
		}else{
			if($oe->tov>0){
				$ocx="onclick=saveOrder();";
				$buttons="<button $ocx>save order</button>";
				$cx.="$buttons";
			}
		}

		$cx.="wtf";
		echo "g('main-content').style.display = 'block'\n";
		echo "g('main-content').innerHTML='$cx'\n";

	    $jcall=$oe->initLookupCall;
	    $jcall.="g('custsearch').focus();\n";
		//filewriter("oss.txt",$cx,true);
	    echo $jcall;

}



function pocstcodeSP(){
   	$x="oorder summary";

	$pc=new iForm();
   	$new_fa=array("date2");
	$new_ft=array("iisuburbBoxAJ");

	//$defaults["date1"]=singlevalue("systemvariables","vvalue","varid","minfiscal",false);
	//$defaults["date2"]=singlevalue("systemvariables","vvalue","varid","maxfiscal",false);
	//$pc->ajaxExtraFields["searchval"]=array("customerid","companyname","mailsuburb","firstname","surname","phone");
	$pc->dclass="formdata";
	$pc->formfields($new_fa,$new_ft,$defaults,$oa,$pc->compulsorya,$numerica,$new_rowpos,$new_colpos,$jcalla);
   	$fx=$pc->formtext;
   	return $fx;

}

function custPricesInfo($oe,$prodid){
		if($_SESSION["lastpriceQ"]==$prodid) return;

		$_SESSION["lastpriceQ"]=$prodid;
		//echo "alert('get prices')\n";
		$priceband=$_SESSION["oe"]["priceband"];
		$oe->priceGrid($prodid,$priceband);
		//echo "g('prices').value= 'ok then'\n";
		//echo "g('prices').innerHTML= '<input type=\"text\" value=\"ok then\" >blah'\n";
		//echo "g('prices').innerHTML= '$oe->pgx'\n";

		echo "while (g('prices').options.length){ \n";
		echo "g('prices').options[0] = null;\n";
		echo "}\n;";


		$i=-1;
		foreach($oe->priceOptions as $row){
			$i++;
			$price=$row["price"];
			$mqty=$row["minqty"];
			echo "g('prices').options[$i] = new Option('$price (Min=$mqty)','$price');\n";
		}


}


function postProcess(){
	$iix=$_SESSION["iipx"];
	$ajxf=$_SESSION["ajxfirst"];
	//filewriter("fff.txt",$ajxf,true);
	$qc=vclean($_SESSION["ajquery"]);
	$q=$_SESSION["ajquery"];
	if($q!=""){
		$limq=$q." limit 20";
		//filewriter("ppp.txt","yo:$iix $q",true);
			if($q!=""){
				//echo "alert('$q')\n";
			}else{
				//echo "alert('no q $q')\n";
			}
			$i++;
    	$tf=kda("prodid");
    	$qa=infqueryarray($limq,$tf);
		/*reset previous*/
		echo "while (g('priors').options.length){ \n";
		echo "g('priors').options[0] = null;\n";
		echo "}\n;";

		echo "while (g('groupfilter').options.length){ \n";
		echo "g('groupfilter').options[0] = null;\n";
		echo "}\n;";

    	foreach($qa as $i=>$row){
    	 $prodid=$row["prodid"];
    	 $prodgroup=$row["prodgroup"];
		 $pga[$prodgroup]=$prodgroup;
		 echo "g('priors').options[$i] = new Option('$prodid','2');\n";
		}
		$pga=getProdGroups($q);
		asort($pga);
		reset($pga);
    	foreach($pga as $pg){
    	 $p++;
		 echo "g('groupfilter').options[$p] = new Option('$pg','2');\n";
		}
		unset($_SESSION["ajquery"]);
	}
}

function getProdGroups($q){
 $tq=" CREATE TEMPORARY TABLE prodlist type=isam
  $q ";
   $cresult = mysql_query($tq);
   $q2="select distinct prodgroup from prodlist";
   $pgr=mysql_query($q2);
   while($row = mysql_fetch_array($pgr)){
     $pg=$row["prodgroup"];
     $pgA[$pg]=$pg;
   }
  return $pgA;

}


function calcLine($qty,$price,$discp){
		 $discprice=$price*(1-($discp/100));
		 $grossval=round($qty*$discprice,2);

		 echo "g('extended').value = $grossval;\n";
		 echo "g('discounted').value = $discprice;\n";
}

function loadInventoryDetail($prodid,$price){
	/*when price clicked, load remaining inv detail, ie. discounts etc
	need prod levels too.
	*/
	$disc=$_SESSION["oe"]["disc"];
	$tf=kda("a_discount,b_discount,taxcode");
	$tid="inventory";
	$condx="where prodid='$prodid'";
	$da=iqasra($sqlx,$tf,$tid,$condx);
	$adisc=$da["a_discount"];
	$bdisc=$da["b_discount"];
	$taxc=$da["taxcode"];

	switch($disc){
		case "A":
		$discp=$adisc;
		break;
		case "B":
		$discp=$bdisc;
		break;
	}
	$_SESSION["oe"]["discp"]=$discp;
	//echo "alert('iid $prodid $disc = $adisc $bdisc $taxc')\n";
	$discprice=round($price*(1-$discp/100),2);
	echo "g('discountp').value= '$discp'\n";
	//echo "g('discounted').value = $discprice;\n";
	echo "g('taxcode').value= '$taxc'\n";
}

function saveOrderLine($oe,$prodid,$qty,$price,$discounted){
	$_SESSION["oe"]["blc"]++;
	$blc=$_SESSION["oe"]["blc"];

	$_SESSION["oe"]["lno"][$blc]=$blc;
	$_SESSION["oe"]["retail"][$blc]=$price;
	$_SESSION["oe"]["price"][$blc]=$discounted;
	$_SESSION["oe"]["qty"][$blc]=$qty;
	$_SESSION["oe"]["prodid"][$blc]=$prodid;

	displayBasketLines($oe);

	headCalc($oe);
	fieldReset();
}

function headCalc($oe){
	echo "g('orderheadcount').innerHTML=' Linec: $oe->linec lines'\n";
	echo "g('orderheadvalue').innerHTML='Total Order Value: $ $oe->tov'\n";
}


function removeline($oe,$linec){
	$lf=kda("lno,retail,price,qty,prodid");
	foreach($lf as $fn){
	 unset($_SESSION["oe"][$fn][$linec]);
	}
	displayBasketLines($oe);
	headCalc($oe);
}

function displayBasketLines($oe){
	$oe->basketLines();
	$cx.=vClean($oe->blx);
	echo "g('basketlines').innerHTML='$cx'\n";
}



function fieldReset(){
    //echo "alert('saving');\n";
	$fa=kda("prodid,qty,price,discountp,discounted,searchval,extended,shortdesc,reordercode");
	foreach($fa as $fn){
		echo "g('$fn').value = '';\n";
	}
    echo "g('searchval').focus();\n";
}

function taxline($oe,$blc,$exempt){
	 if($exempt){
		 $_SESSION["oe"]["gstexempt"][$blc]=true;
	 }else{
		 $_SESSION["oe"]["gstexempt"][$blc]=false;
	 }
	 $oe->basketLines();
	 $cx.=vClean($oe->blx);
	 echo "g('basketlines').innerHTML='$cx'\n";
	 headCalc($oe);
}


function updateCust($oe){
	$cpf=$_SESSION["oe"]["frtpaidby"];
	//echo "alert('cpf: $cpf')\n";
	if($_SESSION["oe"]["frtpaidby"]=="cust"){
		$da["customerpaysfreight"]="on";
	}else{
		$da["customerpaysfreight"]="";
	}
	//echo "alert('updating cust')\n";
	$da["carrieraccno"]=vclean($_SESSION["oe"]["carrieraccno"]);
	$oe->updateCustInfo($_SESSION["oe"]["customerid"],$da);
}



function saveorder($oe){
	/*header*/
	$sodate=date("Y-m-d");
	$soHeadf=kda("customerid,custordref,shipto,mailto,internalnotes,notes,fnote,frtpaidby,carrieraccno");
	foreach($soHeadf as $fn){
		$headf[$fn]=vClean($_SESSION["oe"][$fn]);
	}
	$_SESSION["oe"]["sodate"]=$sodate;
	$t="salesorder";
	$headf["sodate"]=$sodate;
	$_SESSION["oe"]["soref"]=performarrayinsert($t,$headf);
	$oe->updateShipAddresses($_SESSION["oe"]["customerid"],$_SESSION["oe"]["shipto"]);
	/*lines*/
    soLineSaver();
}


function updateSalesOrder($oe,$soref){
	/*header*/
	$soHeadf=kda("customerid,custordref,shipto,mailto,internalnotes,notes,fnote,frtpaidby,carrieraccno,sodate");
	foreach($soHeadf as $fn){
		$headf[$fn]=vClean($_SESSION["oe"][$fn]);
	}
	$headf["sodate"]=$sodate;
	$t="salesorder";
	performarrayupdate($t,$headf,"soref",$soref);
	/*lines*/
	$blc=$_SESSION["oe"]["blc"];
    $kql="delete from solines where soref=$soref";
    /*delete lines prior to save*/
    mySql_Query($kql);
    soLineSaver();
}

function soLineSaver(){
	$soref=$_SESSION["oe"]["soref"];
	$sodate=$_SESSION["oe"]["sodate"];
	$custid=$_SESSION["oe"]["customerid"];

	/*reset prior to save for update*/
	$kql="delete from solines where soref=$soref";
	mysql_query($kql);
	$t="solines";
	foreach($_SESSION["oe"]["lno"] as $blc){
		$retail=$_SESSION["oe"]["retail"][$blc];
		$price=$_SESSION["oe"]["price"][$blc];
		$qty=$_SESSION["oe"]["qty"][$blc];
		$prodid=$_SESSION["oe"]["prodid"][$blc];
		$taxexempt=$_SESSION["oe"]["gstexempt"][$blc];
		$lda["soref"]=$soref;
		$lda["sodate"]=$sodate;
		$lda["customerid"]=$custid;
		$lda["prodid"]=$prodid;
		$lda["qty"]=$qty;
		$lda["price"]=$price;
		$lda["taxexempt"]=$taxexempt;
		$lda["discountp"]=$_SESSION["oe"]["discp"];
		$lda["chargetype"]="product";
		performarrayinsert($t,$lda);
	}
	resetBasket($oe);
}



function resetBasket($oe){
	unset($_SESSION["oe"]);
	//orderSummary($oe);
	echo "window.close();\n";
}


function getLabels($custid){
	$cqd=readcust($custid);
	$cn=$cqd["companyname"];
	$fn=$cqd["firstname"];
	$sn=$cqd["surname"];
	if($cn==$sn.", $fn"){
		$cn="$fn $sn";
	}


	$streetx=$cqd["shipping1"];
	$ad2=$cqd["shipping2"];
	if($ad2!=""){
		$streetx.="\\n $ad2";
	}
	$ad3=$cqd["shipsuburb"];
	$ad4=$cqd["shipstate"];
	$ad5=$cqd["shippostcode"];
	$labelx="$cn \\n$streetx\\n$ad3 $ad4 $ad5";
    echo "g('shipto').value='$labelx'\n";

	$mailx=$cqd["mail1"];
	$ad2=$cqd["mail2"];
	if($ad2!=""){
		$mailx.="\\n $ad2";
	}

	$ad3=$cqd["mailsuburb"];
	$ad4=$cqd["state"];
	$ad5=$cqd["postcode"];
	$labelx="$cn\\n $mailx\\n$ad3 $ad4 $ad5";
    //$labelx="mail";
    echo "g('mailto').value='$labelx'\n";


}


function tempFieldSaver($oe){
	$possibleInputs=kda("custordref,shipto,mailto,internalnotes,notes,fnote,carrieraccno,sodate");
	foreach($possibleInputs as $pif){
		//echo "alert('consider temp update $pif')\n";
		echo "if(g('$pif')){ \n";
		echo "	var $pif=g('$pif').value;\n";
		echo "	var str=g('$pif').value;\n";
		//$fv=str_replace("\n","*newline*",$fv);
		//echo "$pif=$pif.replace('bc','*newline*');\n";
		//echo "$pif=$pif.replace(/\\n/g,'*newline*');\n";
		echo "$pif=$pif.replace(/\\n/g,'<txtaNl>');\n";
		//echo "	alert('str  $pif'+$pif)\n";
		//echo "	alert('saving $pif'+$pif)\n";
		echo "ajx.send('saveTempFieldSession','$pif',$pif);\n";
		echo "}\n";
	}

	$possibleChecboxes=kda("supervisorapproved");
	foreach($possibleChecboxes as $pif){
		echo "if(g('$pif')){ \n";
		echo "if(g('$pif').checked){ \n";
		echo "	var $pif='on';\n";
		echo "}else{var $pif='off'}	;\n";
		//echo "	alert('saving $pif'+$pif)\n";
		echo "ajx.send('saveTempFieldSession','$pif',$pif);\n";
		echo "}\n";
	}
	$possibleRadios=kda("frtpaidby");
	foreach($possibleRadios as $pif){
		//echo "  alert('517 ss $pif');\n";
		echo "  var rv = getRadioValue('$pif');\n";
		//echo "  alert('$pif 519 rv:'+rv);\n";
		echo "  ajx.send('saveTempFieldSession','$pif',rv);\n";
	}
	updateCust($oe);
}


function loadOrder($oe,$soref){
	unset($_SESSION["oe"]);
	//echo "alert('loading $soref')\n";
	$oe->loadSalesOrder($soref);
	orderSummary($oe);
		/*populate cust lookup fields*/
	foreach($oe->custF as $fn){
			$dv=$oe->oHeadA[$fn];
			//echo "alert('luf $fn $dv')\n";
			echo "if(g('$fn')){\n";
			echo "g('$fn').value='$dv';\n";
			echo "}\n";
	}
}

function xloadAddressLabelChoices($oe,$custid){
	//echo "alert('lac $custid')\n";
	$lac=vClean($oe->loadAddressLabelChoices($custid));
	$lac=$oe->loadAddressLabelChoices($custid);

	//filewriter("newlac.txt",$lac,true);
	//echo "g('innerldesc').innerHTML='<td>wtf</td>';\n";
	//echo "g('fnote').focus();\n";
	//echo "g('inner$fn').value='$lac';\n";
}


function loadAddressLabelChoices($oe,$custid){
	//echo "alert('lac $custid')\n";
	$lac=vClean($oe->loadAddressLabelChoices($custid));
	//$lac=$oe->loadAddressLabelChoices($custid);
	//filewriter("newlac.txt",$lac,true);
	echo "g('innerldesc').innerHTML='$lac';\n";
	//echo "g('testt').innerHTML='wtf';\n";
	/*load billing and shipping label*/
	getLabels($custid);
}

function setAddLabel($oe,$custid,$ldesc){
 	//$custid=$_SESSION["oe"]["customerid"];
	//echo "alert('ok $custid')\n";
	$oe->loadShipAddresses($custid,$ldesc);
	echo "g('shipto').value='$oe->shipLabel';\n";
	//echo "alert('ok')\n";
}



function tableUpdate($aS,$t,$kn,$kv,$str){
	$da=explode("!I!",$str);
	$px=implode("=",$da);
	$tfa=tfa($t);
	foreach($da as $dx){
		$ra=explode("=",$dx);
		$fn=$ra[0];
		if(in_array($fn,$tfa)){
		$uda[$ra[0]]=$ra[1];
		}
	}

	foreach($uda as $fn=>$fv){
		//echo "alert('577 $fn $fv')\n";
	}

	performarrayupdate($t,$uda,$kn,$kv);
    soLineSaver();
	//performarrayinsert($t,$newda);
	//echo "alert('$q')\n";
	//foreach($str as
	//echo "g('main-content').style.display = 'none'\n";
	//echo "g('main-content').innerHTML='$cx'\n";
	//echo "alert('saved new $id');\n";
	//echo "genericTableView('$tablen','$id')\n";
}

/*end ioeajx functions*/



function displayServiceareas($custid){
	//echo "alert('dsk')\n";
	//exit;
	$sx=$this->loadTerritorySet($custid);
	$dsx="<div style=\"float:left;\"><h3>Service Areas</h3><br>Click to include $sx</div>";
	$terrList=vClean($this->loadTerritorySummary($custid));
	$dsx.="<h3>Territory summary</h3><div id=territorysummary style=\"float:left;padding-left:5px;\">$terrList</div>";
	//$cx='jff';
	$cx=vClean($dsx);
	//echo "g('main-content').style.display = 'block'\n";
	//echo "g('main-content').innerHTML='$cx'\n";
	echo "g('guts').style.display = 'block'\n";
	echo "g('guts').innerHTML='$cx'\n";
}

function displaySkillset($custid){
	//echo "alert('dsk')\n";
	//exit;
	$sx=$this->loadSkillSet($custid);
	$dsx="<div style=\"float:left;\"><h3>Trades & Skills</h3>Select a trade to pre-select multiple skills, <br>or choose individual skills from the skill list. $sx</div>";
	#$skillsummx=vClean($this->loadSkillsummary($custid));
	$skillsummx=$this->loadSkillsummary($custid);
	$dsx.="<h3>Skill summary</h3><div id=skillsummary style=\"float:left;padding-left:5px;\">$skillsummx</div>";
	//$cx='jff';
	$cx=vClean($dsx);
	//echo "g('main-content').style.display = 'block'\n";
	//echo "g('main-content').innerHTML='$cx'\n";
	echo "g('guts').style.display = 'block'\n";
	echo "g('guts').innerHTML='$cx'\n";
}

function saveskills($customerid,$skillx){
	tfw("ssk.txt","$customerid $skillx",true);
	if(!$customerid>0) exit;
	$da=explode("!I!",$skillx);
	$kq="delete from subbyskill where customerid=$customerid";
	mysql_query($kq);


	#echo "alert('save tradesubby 2018')\n";

	foreach($da as $x){
		$newda["skilldesc"]=$x;
		$newda["customerid"]=$customerid;
		performarrayinsert("subbyskill",$newda);
	}
	#redisplay summary
	$skillsummx=$this->loadSkillsummary($customerid);
	$cx=vClean($skillsummx);
	echo "g('skillsummary').innerHTML='$cx' \n";
}

function saveterritories($customerid,$dx,$fullReload=false){
	if(!$customerid>0) exit;
	$da=explode("!I!",$dx);
	$kq="delete from subbyterritory where customerid=$customerid";
	mysql_query($kq);

	foreach($da as $x){
		$newda["territory"]=$x;
		$newda["customerid"]=$customerid;
		performarrayinsert("subbyterritory",$newda);
	}
	if($fullReload){
		$this->displayServiceareas($customerid);
	}else{
	#redisplay summary only
	$rx=$this->loadTerritorysummary($customerid);
	$cx=vClean($rx);
	echo "g('territorysummary').innerHTML='$cx' \n";
	}
}

function delCustSkill($desc,$custid){
	#load skillset
	#remove the one to delete
	#recomiit.
	$sqlx="select customerid,skilldesc,'r' as remove from subbyskill where customerid=$custid
			and skilldesc not like 'trade!%'";
			$sf=key2val(kda("customerid,skilldesc"));
			$da=iqa($sqlx,$sf);
	$skillSet=key2val(aFV($da,"skilldesc"));
	$sx=implode(",",$skillSet);
	$desc=urldecode($desc);
	unset($skillSet[$desc]);

	$newsx=implode("!I!",$skillSet);
	$this->saveskills($custid,$newsx);


}

function delCustTerritory($desc,$custid){
	#load skillset
	#remove the one to delete
	#recomiit.
	$sqlx="select customerid,territory,'r' as remove from subbyterritory where customerid=$custid";
			$sf=key2val(kda("customerid,territory"));
			$da=iqa($sqlx,$sf);
	$da=key2val(aFV($da,"territory"));
	$sx=implode(",",$da);
	$desc=urldecode($desc);
	unset($da[$desc]);
	$newsx=implode("!I!",$da);
	//echo "alert('delete $desc $custid $newsx');\n";

	$this->saveterritories($custid,$newsx,true);


}



function showSubbySkillgroup($skillx){
	//echo "alert('skg')\n";
	$skg="groups here";
	$q="select * from subbyskill as sk
	left outer join customer as c
	on sk.customerid=c.customerid
	where skilldesc='$skillx'";
	$tf=kda("customerid,companyname,firstname,surname,skilldesc,shipsuburb,state");
	$da=iqa($q,$tf);

	$ist=new divTable();
	$lfn="companyname";
	$ist->ajaxFname[$lfn]="masterview";
	$ist->ajaxData[$lfn]=kda("customerid");
	$ist->linka[$lfn]=array("ajaxHref"=>"view");

		$ist->ajaxStaticData[$lfn]=kda("customer");
		$ist->ajaxData[$lfn]=kda("customerid");
		$ist->displaysuppress[$lfn]=true;

	$filt_fields=kda("shipsuburb");
	$ist->da=$da;
	$ist->buildFilters($filt_fields);
	$ist->filterCountsInBrackets();

	$filter_vals=$ist->session_filters($tf);
	if(sizeof($filter_vals>0)){
	$da=$ist->postFilter($da,$tf,$filter_vals);
	}
	$_SESSION["filterJRecall"]="showgroup('$skillx')";


	$nx=$ist->inf_sortable($tf,$da,"Subbies with $skillx",null,$ist->ifa);
	$cx=vClean($nx);
	tfw("skgx.txt",$cx,true);
	echo "g('resultset').innerHTML='$cx' \n";
	//echo "standardistaTableSortingInit();\n";

}


function loadSkillSet($custid){
	$x="skillshere";
		   	$pc=new iForm();
		    $tf=kda("trade,skills");
		   	$pc->flabel=$tf;

		   	#gen $pc->ftypes
		   	$pc->allText($tf);
		   	#then override by exception.
		   	#$pc->ftypes["skills"]="customList";
		   	#$pc->ftypes["chargetype"]="array";

		   	$pc->ftypes["trade"]="checkboxarray";
		   	$pc->ftypes["skills"]="checkboxarray";
		   	$pc->valtable["skills"]="skillset";
		   	$pc->valtable["trade"]="tradet";

		   	$oa["skills"]=stca("skillset",null,"upper");
		   	$oa["trade"]=stca("trade");


		   	#set up positional arrays
			$new_fa=array_keys($pc->flabel);
			foreach($new_fa as $i=>$fn){
				$new_ft[$i]=$pc->ftypes[$fn];
			}
			$posByName=array_flip(array_values($new_fa));
			$row[]=kda("test");
			$row[]=kda("trade");
			$row[]=kda("skills");

			foreach($row as $rowNo=>$rowd){
				foreach($rowd as $colPos=>$fname){
					$apos=$posByName[$fname];
					$new_rowpos[$apos]=$rowNo;
					$new_colpos[$apos]=$colPos;
				}
			}

			$jcalla["skills"]="onclick=saveskills($custid)";
			$jcalla["trade"]="onclick=preloadAndSaveSkills($custid)";


			#skill defaults
			$sqlx="select skilldesc from subbyskill where customerid=$custid";
			$sf=kda("skilldesc");
			$sda=iqa($sqlx,$sf);
			if(is_array($sda)){
				$sda=aFV($sda,"skilldesc");
				$skillA=$sda;
				#tfw("sxk.txt","$sqlx $sx",true);
				$pc->defaults["skills"]=$skillA;
			}
			#trade defaults
			$sqlx="select skilldesc from subbyskill where customerid=$custid and skilldesc like 'trade!%'";
			$sf=kda("skilldesc");
			$sda=iqa($sqlx,$sf);
			$sda=aFV($sda,"skilldesc");
			if(isset($sda)){
			foreach($sda as $sx){
				$ia=explode("!",$sx);
				$tx=$ia[1];
				$tra[$tx]=$tx;
			}
			}
//			tfw("sxk.txt","$sqlx $sx",true);
			$pc->defaults["trade"]=$tra;

		   	$pc->formfields($new_fa,$new_ft,$pc->defaults,$oa,$pc->compulsorya,$numerica,$new_rowpos,$new_colpos,$jcalla);
			$x=$pc->formtext;
			$rx="<table>$x</table>";
	return $rx;

}


function loadSkillsummary($custid){
	$x="skillshere";
	$ist=new divTable();
			$sqlx="select customerid,skilldesc,'r' as remove from subbyskill where customerid=$custid
			and skilldesc not like 'trade!%'";
			tfw("lsks.txt",$sqlx,true);
			$sf=key2val(kda("customerid,skilldesc,remove"));
			$sda=iqa($sqlx,$sf);
			$skillA=$sda;
			//$ist->input_fields["remove"]="ajaxCheckbox";
			$ist->input_fields["remove"]="checkbox";
			$ist->input_fields["customerid"]="hidden";
			$ist->input_jcall["remove"]="onclick=delContractorSkill";
  		    $ist->ajaxFname["remove"]="delContractorSkill";
		    $ist->ajaxData["remove"]=kda("skilldesc,customerid");
			$ist->trailingquote["remove"]=false;

	$nx=$ist->inf_sortable($sf,$sda,"Trade Contractor Skill set summary.<br>Use the checkboxes on the left to add and remove from this list",null,null);
	return $nx;

}


function loadTerritorySummary($custid){
	$x="skillshere";
	$ist=new divTable();
			$sqlx="select customerid,territory,'r' as remove from subbyterritory where customerid=$custid";
			$sf=key2val(kda("customerid,territory,remove"));
			#$sf=key2val(kda("customerid,territory"));
			$sda=iqa($sqlx,$sf);
			$skillA=$sda;
			//$ist->input_fields["remove"]="ajaxCheckbox";
			$ist->input_fields["remove"]="checkbox";
			$ist->input_fields["customerid"]="hidden";
			$ist->input_jcall["remove"]="onclick=delContractorTerritory";
  		    $ist->ajaxFname["remove"]="delContractorTerritory";
		    $ist->ajaxData["remove"]=kda("territory,customerid");
			$ist->trailingquote["remove"]=false;

	$nx=$ist->inf_sortable($sf,$sda,"Trade Contractor Territory List<br>Use the checkboxes on the left to add and remove from this list",null,null);
	return $nx;
}


function showTradeSkillgroup($tx){
	//echo "alert('sts $tx')\n";
	$x="skillshere";
		   	$pc=new iForm();
		    $tf=kda("skills");
		   	$pc->flabel=$tf;

		   	#gen $pc->ftypes
		   	$pc->allText($tf);
		   	#then override by exception.
		   	#$pc->ftypes["skills"]="customList";
		   	#$pc->ftypes["chargetype"]="array";

		   	$pc->ftypes["skills"]="checkboxarray";
		   	$pc->valtable["skills"]="skillset";

		   	$oa["skills"]=stca("skillset",null,"upper");


		   	#set up positional arrays
			$new_fa=array_keys($pc->flabel);
			foreach($new_fa as $i=>$fn){
				$new_ft[$i]=$pc->ftypes[$fn];
			}
			$posByName=array_flip(array_values($new_fa));
			$row[]=kda("test");
			$row[]=kda("skills");

			foreach($row as $rowNo=>$rowd){
				foreach($rowd as $colPos=>$fname){
					$apos=$posByName[$fname];
					$new_rowpos[$apos]=$rowNo;
					$new_colpos[$apos]=$colPos;
				}
			}

		   	$pc->flabel["invfc"]="$ FC";
		   	$utx=urlencode($tx);
			$jcalla["skills"]="onclick=savetradeskills('$utx')";



			$sqlx="select skilldesc from tradeskill where tradedesc='$tx'";
			$sf=kda("skilldesc");
			$sda=iqa($sqlx,$sf);
			$rawSkills=$sda;
			$sda=aFV($sda,"skilldesc");
			$skillA=$sda;
			$pc->defaults["skills"]=$skillA;

			#display summary table - skillsSelected
			$ist=new divTable();
			$title="List of  $tx Skills  ";
			$this->skillTable=$ist->inf_sortable($sf,$rawSkills,$title,null,null,true);



		   	$pc->formfields($new_fa,$new_ft,$pc->defaults,$oa,$pc->compulsorya,$numerica,$new_rowpos,$new_colpos,$jcalla);
			$x=$pc->formtext;
			tfw("rxk.txt","sfx $x",true);
			$this->headrx.="<h3>Skillset for $tx</h3>";
			$rx.="<table>$x</table>";
	return $rx;

}

function tradeskillSaver($trade,$skillx){
	#echo "alert('$trade $skillx')\n";
	if($trade=="") exit;
	$da=explode("!I!",$skillx);
	$kq="delete from tradeskill where tradedesc='$trade'";
	mysql_query($kq);

	foreach($da as $x){
		$newda["tradedesc"]=$trade;
		$newda["skilldesc"]=$x;
		performarrayinsert("tradeskill",$newda);
	}

	#refresh;
	//echo "alert('redisplay $trade')\n";
	$this->loadAndDisplaySkillgroup($trade,false);
}

function keywordSkillSaver($word,$skillx){
	//echo "alert('$trade $skillx')\n";
	if($word=="") exit;
	$da=explode("!I!",$skillx);
	$skx=implode(",",$da);
	$kq="update keyword set otherinfo='$skx' where word='$word' and wtype='job'";
	mysql_query($kq);
	#refresh;
	$this->loadAndDisplayKeywordSkillgroup($word,false);
}


function savetradeSubby($customerid,$tradex){
	#echo "alert('$customerid $tradex')\n";
	if(!$customerid>0) exit;
	$da=explode("!I!",$tradex);
	$kq="delete from subbyskill where customerid=$customerid and skilldesc like 'trade!%'";
	mysql_query($kq);

	#load associated skills with subby.
	tfw("stsx.txt",$tradex,true);
	#echo "alert('save tradesubby $tdesc 2355')\n";
	foreach($da as $tradedesc){
		$tdesc=trim($tradedesc);
		$newda["skilldesc"]="trade!$tdesc";
		$newda["customerid"]=$customerid;
		performarrayinsert("subbyskill",$newda);

		$skillA=$this->loadTradeSkills($tdesc);
		foreach($skillA as $x){
			$newda["skilldesc"]=$x;
			$newda["customerid"]=$customerid;
			performarrayinsert("subbyskill",$newda);
		}
	}
	#$this->displaySkillset($customerid);
	#instead- don't refresh trade panel, just skill summary

	#redisplay summary
	/*$skillsummx=$this->loadSkillsummary($customerid);
	$cx=vClean($skillsummx);
	echo "g('skillsummary').innerHTML='$cx' \n";
	*/
	#instead - total redisplay skill choices to reflect newly selected trade;
	$this->displaySkillset($customerid);

}

function loadTradeSkills($tdesc){
	if(trim($tdesc=="")) return;
	$q="select upper(skilldesc) as skilldesc from tradeskill where tradedesc='$tdesc'";
	tfw("lts.txt",$q,true);
	$tf=kda("skilldesc");
	$da=iqa($q,$tf);
	$sa=aFV($da,"skilldesc");
	return $sa;
}


function showKeywordSkillgroup($tx){
	//echo "alert('sts $tx')\n";
	$x="skillshere";
		   	$pc=new iForm();
		    $tf=kda("skills");
		   	$pc->flabel=$tf;

		   	$pc->allText($tf);
		   	$pc->ftypes["skills"]="checkboxarray";
		   	$pc->valtable["skills"]="skillset";
		   	$oa["skills"]=stca("skillset",null,"upper");

		   	#set up positional arrays
			$new_fa=array_keys($pc->flabel);
			foreach($new_fa as $i=>$fn){
				$new_ft[$i]=$pc->ftypes[$fn];
			}
			$posByName=array_flip(array_values($new_fa));
			$row[]=kda("test");
			$row[]=kda("skills");

			foreach($row as $rowNo=>$rowd){
				foreach($rowd as $colPos=>$fname){
					$apos=$posByName[$fname];
					$new_rowpos[$apos]=$rowNo;
					$new_colpos[$apos]=$colPos;
				}
			}

		   	$pc->flabel["invfc"]="$ FC";
		   	$utx=urlencode($tx);
			$jcalla["skills"]="onclick=saveKeywordskills('$utx')";


			#load defaults
			$qlx="select otherinfo from keyword where word='$tx' and wtype='job' ";
			$sf=kda("otherinfo");
			$sda=iqa($qlx,$sf);

			$oi=strtoupper(iqasrf($qlx,"otherinfo"));
			$sda=explode(",",$oi);
			$skillA=key2val($sda);
			$skx=implode(",",$skillA);
			//tfw("sxk.txt","$skx",true);
			$pc->defaults["skills"]=$skillA;
			$pc->checkboxValuesonly["skills"]=true;

			#load for selected list
			$rawSkills=$skillA;
			foreach($rawSkills as $k=>$kv){
				$ksa[]["skill"]=$kv;
			}
			#display summary table - skillsSelected
			$ist=new divTable();
			$sf=kda("skill");
			$title="List of  $tx Skills  ";
			$this->skillTable=$ist->inf_sortable($sf,$ksa,$title,null,null,true);
			//tfw("tst.txt","rrr $rx ttt $this->skillTable",true);

		   	$pc->formfields($new_fa,$new_ft,$pc->defaults,$oa,$pc->compulsorya,$numerica,$new_rowpos,$new_colpos,$jcalla);
			$x=$pc->formtext;
			//tfw("rxk.txt","sfx $x",true);
			$this->headrx.="<h3>Skillset for $tx</h3>";
			$rx.="<table>$x</table>";
	return $rx;

}




function showJobStageDetail($astage){
	$cx="got $astage ";
	include_once "$this->clientpath/customscript/jobQuery.php";
   	$jq=new jobQuery;
   	$jq->tf=kda("jobid,leaddate,astage,companyname");
   	$jq->query="
   	select oj.jobid,oj.astage,j.leaddate,c.companyname from openjledger as oj
   	left outer join jobs as j
   	on oj.jobid=j.jobid
   	left outer join customer as c
   	on j.customerid=c.customerid
   	where oj.astage='$astage'

   	";
   	$jq->buildOpenJobData("passed");

	$ist=new divTable();
   	$ist->highlighter=true;
   	$ist->highlightFname="stageDetail";
   	$ist->rowID=kda("astage");

	$title="List of  $astage Jobs  ";
	$nx=$ist->inf_sortable($jq->tf,$jq->da,$title,null,null,true);
	$cx=vClean($nx);
	echo "g('stagedetail').innerHTML='$cx'\n";
}

function newjob(){

	unset($_SESSION["oe"]);
	unset($_SESSION["nje"]);
	$ji=new jobInterface();
	$ji->jobSummaryPage();
    $cx=vClean($ji->fx);
    echo "g('main-content').innerHTML = '$cx'\n";
	tfw("njc.txt","$cx $ji->initLookupCall",true);

    echo "$ji->initLookupCall \n";

    $this->jcallA=$ji->jcallA;
	$this->extraNewJobCalls();
	$this->extraNewJobCalls_2();
    $this->jcallA[]=$this->jcl;
    $this->jcallA[]=$this->jcl2;

   $this->ibuffCalls();
   elog("2816newjobform $ji->fx");
    #echo "alert('wtfigo'); \n";
    #echo "var ld=g('leaddate').value(); \n";
    #echo "alert('nld '+leaddate) \n";
    #echo "g('custsearch').focus(); \n";
    #echo "alert('done')\n";
    echo "pullFocusForm('salesorderhead','companyname')\n";;

	#echo "setTimeout(\"g('custsearch').focus()\",1250);\n";
	#echo "setTimeout(\"alert('custsearch')\",1250);\n";

}
function extraNewJobCalls_2(){
	 #$this->jcl2="alert('aa')";
	 $this->jcl2="
	 //alert('baa');

	$('.newfm').click(function(){
    cl('nfm');
      var custid=$('#customerid').val();
      var role=escape('sitefm');
    jmg('contact','!I!title=New!I!via=jobcard!I!role='+role+'!I!customerid='+custid);
   });


   	$('.newsitecontact').click(function(){
    cl('nfm');
      var custid=$('#customerid').val();
      var role=escape('site contact');
    var sitesuburb=$('#sitesuburb','#salesorderhead').val();
    var sitepostcode=$('#sitepostcode','#salesorderhead').val();
    var sitestate=$('#sitestate','#salesorderhead').val();
    var territory=$('#territory','#salesorderhead').val();
    var labelid=$('#labelid','#salesorderhead').val();
    var p='!I!customerid='+custid+'!I!';
    p+='labelid='+labelid+'!I!suburb='+sitesuburb+'!I!state='+sitestate+'!I!postcode='+sitepostcode+'!I!territory='+territory;
    p=escape(p);
    jmg('contact','!I!title=New!I!via=jobcard!I!role='+role+'!I!'+p);
   });

   
   

	$('.scLookup').click(function(){
    //alert('fm click');
    //fmLookup();//old method
      var custid=$('#customerid','#salesorderhead').val();
      //alert('lookup'+custid)
      //if(g('editcustomerid')) custid=g('editcustomerid').value;
      if(custid!=''){
        //alert('ok');
        var params='!I!customerid='+custid+'!I!tn=fmLookup_ajlI!I!role=site contact!I!!I!divid=dialogGeneric';
        var eparams=escape(params);
        jmg('fmLookup_aj',eparams);
      }else{
        alert('Choose a customer first');
      }
       });

  $('#bsitecontact_edit').click(function(){
    //alert('cc');
    var contactid=$('#sitecontactid','#salesorderhead').val();
    var sitecontact=$('#sitecontact','#salesorderhead').val();
    var customerid=$('#customerid','#salesorderhead').val();
    if(contactid==''){
    	alert('no contact exists');
    	//return false;
    }
    if(sitecontact==''){
    	alert('no name available');
    }	

    cl('fmed'+contactid);
    var role=escape('site contact');
    //pre-populate contact card.
    var sitephone=$('#sitephone','#salesorderhead').val();
    var siteemail=$('#siteemail','#salesorderhead').val();

    var sitesuburb=$('#sitesuburb','#salesorderhead').val();
    var sitepostcode=$('#sitepostcode','#salesorderhead').val();
    var sitestate=$('#sitestate','#salesorderhead').val();
    var territory=$('#territory','#salesorderhead').val();
    var labelid=$('#labelid','#salesorderhead').val();

    var p='!I!customerid='+customerid+'!I!firstname='+sitecontact+'!I!phone='+sitephone+'!I!siteemail='+siteemail;
    p+='!I!suburb='+sitesuburb+'!I!state='+sitestate+'!I!postcode='+sitepostcode+'!I!territory='+territory;
    p=escape(p);
    cl(p);
    jmg('contact','!I!title=New!I!role='+role+'!I!via=jobcard!I!jobid=$aS->jobid!I!id='+contactid+p);

  });

  $('#bfmedit').click(function(){
    //alert('cc');
    var contactid=$('#contactid','#salesorderhead').val();
    var sitefm=$('#sitefm','#salesorderhead').val();
    var customerid=$('#customerid','#salesorderhead').val();
    if(contactid==''){
    	alert('no FM contact exists');
    	//return false;
    }
    if(sitefm==''){
    	alert('no name available');
    }	

    cl('fmed'+contactid);
    var role=escape('sitefm');
    //pre-populate contact card.
    var sitefmph=$('#sitefmph','#salesorderhead').val();
    var fmemail=$('#sitefmemail','#salesorderhead').val();

    var sitesuburb=$('#sitesuburb','#salesorderhead').val();
    var sitepostcode=$('#sitepostcode','#salesorderhead').val();
    var sitestate=$('#sitestate','#salesorderhead').val();
    var territory=$('#territory','#salesorderhead').val();
    var labelid=$('#labelid','#salesorderhead').val();

    var p='!I!customerid='+customerid+'!I!firstname='+sitecontact+'!I!phone='+sitefmph+'!I!email='+fmemail;
    p+='!I!suburb='+sitesuburb+'!I!state='+sitestate+'!I!postcode='+sitepostcode+'!I!territory='+territory;
    p=escape(p);
    cl(p);
    jmg('contact','!I!title=New!I!role='+role+'!I!via=jobcard!I!jobid=$aS->jobid!I!id='+contactid+p);

  });





	function getSiteContactDetail(item){
  //alert(item.id);
   var dtext=item.text;
   $('#sitecontact').val(dtext);
   var cid=item.id
   $('#sitecontactid').val(cid);
      //alert(cid);
      var jobid=$('#jobid').val();
   //jobid=302000;
   var data='params=!I!p=yes!I!url=jobEditRules!I!mode=siteContactDetails!I!contactid='+cid+'!I!jobid='+jobid+'!I!';
   cl('596'+data);
   $.ajax({
      url: ijurl,type: 'POST',data: data,async: true,cache: false,
      success: function (jdata) {
          var json = $.parseJSON(jdata);
          var cid=json.sitecontactid;
          $('#activeval').html(json.sitefm);
          $('#sitecontactid').val(json.sitecontactid);
          $('#sitecontact').val(json.sitecontact);

          $('#sitephone','#salesorderhead').html(json.sitephone);
          $('#siteemail','#salesorderhead').html(json.siteemail);
          $('#sitephone','#salesorderhead').val(json.sitephone);
          $('#siteemail','#salesorderhead').val(json.siteemail);

             //alert('done');
          if(jobid>0){
            cl('try eipsave'+jobid)
            //$('#eipsaver').click();
          //}else{
           siteContactChangeViaNew(cid);
          }

       } 
    })   
}";


}	


function extraNewJobCalls(){
	#modelled on jobview calls, but modified for entry, new mode
	 $this->jcl="
      $('#custLookup').unbind('click').click(function(){
      //alert('jjj');
      //orgLookup();
      var customerid=$('#customerid').val();
      if(customerid=='') alert('Select a customer first');
      var par='!I!mode=newjob!I!customerid='+customerid;
      //alert(par);
      jmg('siteLookup',par);
   });

function getsitedetail(item){
   //alert('get '+item.id);
      var data='params=!I!p=yes!I!url=siteLookup!I!mode=applyid!I!labelid='+item.id;
            $.ajax({
                  url: ijurl,type: 'POST',data: data,async: true,cache: false,
                  success: function (jdata) {
                  	cl(jdata);
                   var json = $.parseJSON(jdata);
                     console.log('sitefm:'+json.sitefm);
                     console.log('site line1:'+json.siteline1);
                       $('#sitefm').val(json.sitefm);
                       var contactid=json.contactid;
                       //contactid=0;
                       cl('got contact'+contactid);
						var jobcust=$('#customerid','#salesorderhead').val();
						var contcust=json.contcust;
						cl('jobcust '+jobcust+' vs '+contcust);
						//if these are different, could require diff treatment

                       if(contactid>0){
	                       $('#contactid','#salesorderhead').val(json.contactid);


                       }else{
	                       //alert and correct
                       		cl('fix fm data');
                       		fixFM(json.labelid);
                       		return;
                       }


                       $('#siteline1').val(json.siteline1);
                       $('#siteline2').val(json.siteline2);
                       var ssub=json.sitesuburb+' '+json.sitestate+' '+json.sitepostcode;
                       $('#territory').val(json.territory);
                       $('#sitesuburb').val(json.sitesuburb);
                       $('#sitestate').val(json.sitestate);
                       $('#sitepostcode').val(json.sitepostcode);
                       $('#sitefmemail').val(json.sitefmemail);
                       $('#sitefmph').val(json.sitefmph);

                       var sitecontactid=json.sitecontactid;
                       $('#sitecontactid','#salesorderhead').val(json.sitecontactid);
                       $('#sitecontact').val(json.sitecontact);
                       $('#sitephone').val(json.sitephone);
                       $('#siteemail').val(json.siteemail);


                       //$('#dialogGeneric').dialog('close');

						$('#bsitecontact_edit').prop('disabled', false);
						$('#bfmedit').prop('disabled', false);

                  }
             });

}

function getFmDetail(item){
      //alert('gfd');
      //return;
      cl('gfd');
   var dtext=item.text;
   $('#sitefm').val(dtext);
   var cid=item.id
   $('#contactid').val(cid);
      //alert(cid);
      var jobid=$('#jobid').val();
   //jobid=302000;
   var data='params=!I!p=yes!I!url=jobEditRules!I!mode=fmDetails!I!contactid='+cid+'!I!jobid='+jobid+'!I!';
   cl(data);
   $.ajax({
      url: ijurl,type: 'POST',data: data,async: true,cache: false,
      success: function (jdata) {
          var json = $.parseJSON(jdata);
          cl('2889'+json);
          //alert(jdata);
           $('#activeval').html(json.sitefm);

           $('#sitefmphText').html(json.phone);
           $('#sitefmemailText').html(json.email);

           //actual fields while live
           $('#sitefmph').val(json.phone);
           $('#sitefmemail').val(json.email);

          //alert('save now');
           cl('jobid:'+jobid);
           cl('atest'+cid);
           if(typeof jobid == 'undefined')
          {
          fmChangeViaNew(cid);
          }

          if(jobid>0){
          $('#eipsaver').click();
          //}else{
          cl('nojobid');
          fmChangeViaNew(cid);
          }
      }
    })

   }

  $('.fmLookup').click(function(){
   	//alert('fm click');
   	//fmLookup();//old method
      var custid=$('#customerid').val();
      //if(g('editcustomerid')) custid=g('editcustomerid').value;
      if(custid!=''){
        //alert('ok');
        var params='!I!customerid='+custid+'!I!role=sitefm!I!tn=fmLookup!I!divid=dialogGeneric';
        jmg('fmLookup_aj',params);
      }else{
        alert('Choose a customer first');
      }
   });

 function fixFM(labelid){
 	cl('fx2');
 	jmg('jobEditRules','!I!mode=fmfix!I!labelid='+labelid);
 }

";

}
/**end of custom switched detection and functions**/


function jobGridDetail($jobstage,$keyfield,$jcount,$keyvalue){
	#echo  "alert('$jobstage,$keyfield,$jcount,$keyvalue')\n";
	#$_SESSION["backsearch"]["jobs"]="onclick=javascript:iscreen(\'jobStatusGrid\');";
	$_SESSION["backsearch"]["jobs"]="onclick=javascript:jobGridDetail(\'$jobstage\',\'$keyfield\',\'$jcount\',\'$keyvalue\');";

	#$priority=2;
	$priority=str_replace("p","",$priority);
	if($jobstage!="all") $condA[]="j.jobstage='$jobstage'";
	#$condA[]="i.jobid is null";
	$condA[]="$keyfield='$keyvalue'";
	$condA[]="j.leaddate>'2007-07-01'";
	$condx=implode(" and ",$condA);

	include_once "$this->clientpath/customscript/jobQuery.php";
   	$jq=new jobQuery;
   	$jq->tf=kda("jobid,leaddate,jobstage,companyname,site,subcontractor");
   	$jq->query="
   	select distinct j.jobid,j.leaddate,j.jobstage,c.companyname,tradec.companyname as subcontractor,
   	if(j.siteaddress!='',j.siteaddress,
   	concat(j.siteline1,' ',j.siteline2,' ',j.sitesuburb)) as site from jobs as j
   	left outer join customer as c
   	on j.customerid=c.customerid
   	left outer join invoice as i
   	on j.jobid=i.jobid
   	left outer join purchaseorders as p
   	on j.jobid=p.jobid
   	left outer join customer as tradec
   	on p.supplierid=tradec.customerid

   	where $condx
   	group by j.jobid
   	";
	tfw("nnjgd.txt","qu=$jq->query",true);

   	$jq->buildOpenJobData("passed");
   	$jq->tf["leaddate"]="Job Date";
   	$jq->tf["subcontractor"]="Trade Contractor";

	$ist=new divTable();
	$da=iqa($jq->query,$jq->tf);
	$ist->datefields=kda("leaddate");

  	$ist->highlighter=true;
  	$ist->highlightFname="jobview";
  	$ist->rowID=kda("jobid");

	#$ox="onclick=g(\'searchgrid\').style.display=\'block\'";
	$ox="onclick=javascript:iscreen(\'jobStatusGrid\')";
	$cx.="<button $ox>Re-Display Grid</button>";
	#$title=$jq->jquery;

	$safeist=$ist;
	#optionally frozen panel
	$ist->scrollable=true;
	#$ist->tableIDcontainer="frozen";
	#$x=$ist->iFixedHead($jq->tf,$da);
	$x=$ist->inf_sortable($jq->tf,$da,$title,null,null,true);

	#$cx.=vClean($jq->query);
	$cx.=vClean($x);

#	$ist=new divTable();
#  	$ist->highlighter=true;
#  	$ist->highlightFname="stageDetail";
#  	$ist->rowID=kda("astage");
#	$title="List of  $astage Jobs  ";
#	$nx=$ist->inf_sortable($jq->tf,$jq->da,$title,null,null,true);
#	$cx=vClean($nx);

	/*
	echo "if(g('detailGrid')){ \n";
	echo "g('detailGrid').innerHTML='$cx'\n";
	echo "}else{\n";
	echo "g('main-content').innerHTML='$cx'\n";
	echo "}\n";
	*/

	#echo "g('detailGrid').innerHTML='$cx'\n";
	echo "g('main-content').innerHTML='$cx'\n";

	#echo "g('searchgrid').style.display='none'\n";

    echo $GLOBALS["initLookupCall"];
    tfw("linit2.txt",$GLOBALS["initLookupCall"],true);

	tfw("jgd.txt",$cx,true);
	jtop();


}

function addressHandler($id,$mode,$str){
	#echo "alert('cah $id $mode $str')\n";
	#$custid,$jobid


		#case "sitechoice":
		#$ca=iqatr("contact","contactid",$id);
		#$ph=$ca["mobile"];
		#$eml=$ca["email"];
		#$fmname=$ca["firstname"]." ".$ca["surname"];
		#if jobid, update job, refresh view
		#echo "alert('got $id: for $jobid str $str')\n";
		$pA=formCruncher($str);
		if($pA["jobid"]>0){
			$ca=iqatr("addresslabel","labelid",$id);
			$sfa=kda("siteline1,siteline2,siteline3,sitestate,sitesuburb,sitepostcode,sitephone,sitefax,territory,sitecontact");
			$jobid=$pA["jobid"];

			$this->returnLowestTerritory=true;
			$suburb=$ca["sitesuburb"];
			$state=$ca["sitestate"];
			$postcode=$ca["sitepostcode"];
			$lterr=$this->getLowestTerritory($suburb,$state,$postcode);
			if($lterr!="") $ca["territory"]=$lterr;
			foreach($sfa as $fn){
				$uja[$fn]=$ca[$fn];
			}
			#echo "alert('got $id: for $jobid')\n";
			$uja["labelid"]=$id;
			pau("jobs",$uja,"jobid",$jobid);
			echo "ajm('Refreshing view',800)\n";;
			echo "jobview($jobid)\n";
		}else{
		#echo "alert('new job cid $id label $id')\n"	;
		$this->loadLabelDetail($id);
		$sfmcid=$this->loadDefaultSitefm($id);
		#echo "alert('sfm $sfm')\n"	;
		$this->jqsuggestHandler("contactid",$sfmcid,"sitefm");
		}
		#break;
}

function loadDefaultSitefm($id){
	#data approach (assume client provided).
	$q="select sitefm,contactid,customerid from addresslabel
	where labelid=$id";
	#echo "alert('sfm $sfm q')\n"	;
	$tf=kda("customerid,contactid,sitefm");
	$da=iqasra($q,$tf);
	$cid=$da["contactid"];
	$sfm=trim($da["sitefm"]);
	$custid=$da["customerid"];
	if($cid>0){
		#echo "alert('got real $cid fm:$sfm cust:$custid')\n";
		return $cid;
	}
	# OR...
	if($sfm!=''){
		#echo "alert('trying $sfm')\n";
		$q="select contactid from contact where trim(firstname)='$sfm'
		and customerid=$custid";
		$cid=iqasrf($q,"contactid");
		if($cid>0){
			#echo "alert('got from contact $cid fm:$sfm cust:$custid')\n";
			#update this label, so works right next time
			$au["contactid"]=$cid;
			pau("addresslabel",$au,"labelid",$id);
			return $cid;
		}
	}

	# OR...
	#historical approach.
	$q="select jobid,sitefm,contactid,customerid from jobs
	where labelid=$id
	and contactid is not null
	order by jobid desc	limit 1";
	#echo "alert('sfm $sfm q')\n"	;
	$tf=kda("customerid,contactid,sitefm");
	$da=iqasra($q,$tf);
	$cid=$da["contactid"];
	$sfm=trim($da["sitefm"]);
	$custid=$da["customerid"];
	#echo "alert('historical got cid: $cid fm:$sfm cust:$custid')\n";
	if($cid>0){
		return $cid;
	}else{
		#text lookup
		$q="select contactid from contact where trim(firstname)='$sfm'
		and customerid=$custid";
		$cid=iqasrf($q,"contactid");
		return $cid;
	}
}


function customTriggerEdit($tablen,$id,$fn,$val=NULL){
	#return;
	#echo "alert('cte $tablen $fn $val')\n";
	switch($tablen){
		case "jobs":
		#not all fields require redraw
		#echo "alert('qresp $fn $id')\n";
		$triggers=kda("notexceed,dcfmbufferv,estimatedsell,internalduedate,jdaysbuffer,responseduedate,quotefud");
		if(in_array($fn,$triggers)){
			$tfx=implode(",",$triggers);
			$jq="select $tfx from jobs where jobid=$id";
			$ja=infqueryarray_singlerow($jq,$triggers);
			$jdue=$ja["responseduedate"];
			$jrbuff=0-$ja["jdaysbuffer"];
			$intdd=dateadder($jdue,$jrbuff,"d");
			$nja["internalduedate"]=$intdd;
			//echo "alert('new dd for $id : $jdue $jrbuff $intdd ')\n";
			#$iql=$ja["notexceed"]-$ja["dcfmbufferv"];
			$iql=$ja["notexceed"]-$ja["dcfmbufferv"]>0?$ja["notexceed"]-$ja["dcfmbufferv"]:0;

			$nja["internalqlimit"]=$iql;
			//echo "alert('new iql for $id : $iql')\n";
			performarrayupdate("jobs",$nja,"jobid",$id);
		}
		switch($fn){
			case "clientnotified":
			if($val='on'){
				$nja["datenotified"]=date("Y-m-d");
				$nja["jobstage"]="client_notified";
				performarrayupdate("jobs",$nja,"jobid",$id);
			}
			break;
			case "qrespdate":
				#dont change job stage here as of Nov 20 2010
				#$this->jobStageChange($id,"qte_responded");
			break;
			case "jrespdate":
				#dont change job stage here as of Nov 20 2010
				#$this->jobStageChange($id,"job_responded");
			break;
			case "quoterqd":
				if($val=='on'){
					$quotestatus=singlevalue("jobs","quotestatus","jobid",$id,false);
					if(($quotestatus=="")|($quotestatus=="choose an option")|($quotestatus=="Choose an option")){
						#echo "alert('must be p')\n";
						$nja["quotestatus"]="pending_submission";
						performarrayupdate("jobs",$nja,"jobid",$id);
					}
				}
			break;
			case "jobstage":
				#echo "alert('jobedit $id $val')\n";
				$val.="_(vialist)";
				$this->jobStageChange($id,$val,false);
			break;
			case "quotestatus":
				#echo "alert('qst c')\n";
				$this->qStageChange($id,$val,false);
				#effect change.
					$nja["quotestatus"]=$val;
					performarrayupdate("jobs",$nja,"jobid",$id);

				#now capture reasons
				switch($val){
					case "declined":
					#echo "alert('yep capture reason')\n";
					#jobstage=>'declined';
					$this->jobStageChange($id,"declined",true);
					#echo "quoteReasons('$id','declined')\n";
					$pmx="divid=dialogGeneric!I!mode=$val!I!tn=quoteReasons!I!title=Quote!I!jobid=$id";
					#echo "alert('get reason')\n";
					echo "jqUiModalExisting('$pmx')\n";

					break;
					case "accepted":
					#echo "alert('yep capture reason')\n";
					$jobstage='next_allocate';
					$jobstage='info_pending';
					$this->jobStageChange($id,$jobstage,true);
					#echo "quoteReasons('$id','accepted')\n";
					$pmx="divid=dialogGeneric!I!mode=$val!I!tn=quoteReasons!I!title=Quote!I!jobid=$id";
					echo "jqUiModalExisting('$pmx')\n";

					break;

				}
			break;

			case "sitefm":
			#echo "alert('site fm')\n";
			#update siteaddress record
			$jra=iqatr("jobs","jobid",$id);

			$sitefm=$jra["sitefm"];
			$sitefmph=$jra["sitefmph"];
			$sitefmemail=$jra["sitefmemail"];
			$siteline1=$jra["siteline1"];
			$siteline2=$jra["siteline2"];
			$sitesuburb=$jra["sitesuburb"];
			$custid=$jra["customerid"];
			$q="update addresslabel set sitefm='$sitefm',sitefmemail='$sitefmemail',
			sitefmph='$sitefmph'
			where customerid=$custid
			and siteline1='$siteline1'
			and siteline2='$siteline2'
			and sitesuburb='$sitesuburb'";
			mysql_query($q);
			tfw("ciiq.txt",$q,true);

			break;

			case "qfollowuserid":
			#echo "alert('cte qqq $tablen $fn')\n";

			#create followup note
			#include site FM by default, harvest as contact first
			$jra=iqatr("jobs","jobid",$id);
				$cha["customerid"]=$jra["customerid"];
				$cha["phone"]=$jra["sitephone"];
				$cha["contactname"]=$jra["sitefm"];
				$cha["mobile"]=$jra["sitefmph"];
				$cha["email"]=$jra["sitefmemail"];
				#harvest is too problematic - FM should only be added via form.
				#$this->contactHarvest($cha);

				if($this->newContactid) $fnote["contactid"]=$this->newContactid;
				if($this->dupid) $fnote["contactid"]=$this->dupid;


			$fnote["date"]=date("Y-m-d");
			$fnote["jobid"]=$id;
			$fnote["ntype"]="quote followup";
			$fnote["followupby"]=$val;
			$fnote["followupdate"]=$jra["quotefud"];
			$fnote["timestamp"]=date("Y-m-d h:i");
			$fnote["notes"]="Quote followup assigned to $val";
			$fnote["userid"]=$_SESSION["userid"];
			$fnote["customerid"]=$jra["customerid"];
			$id=pai("jobnote",$fnote);
			#flick over to task as well
			$this->customTrigger("jobnote",$id,$fnote);
			break;
			case "jobnote":
			echo "alert('e jn')\n";
			break;
			case "custordref":
			case "custordref2":
			case "custordref3":
			$this->checkResendInvoice($id,$fn,$val);
			break;



		}
		break;

		case "customer":
		#check if supplier needs to be on schedule
		#echo "alert('sos')\n";
		if($fn=="customtext1"){
			$this->supplierOnSched($id);
		}
		break;


	}
}

function customTrigger($tablen,$id,$da=null){
	tfw("actrig.txt","$tablen $id",true);
	switch($tablen){
		case "custnotes":
		$ndt=$da["ndate"];
		$fdt=$da["followupdate"];
		$cname=returncontact($da["contactid"]);
		if($fdt!=$ndt){
			#ignore most notes where followup date defaults to today
			$taskd=$da;
			$taskd["tdate"]=$da["ndate"];
			$taskd["detail"]=$da["note"];
			$taskd["area"]="Contact Note Followup";
			$taskd["subject"]="Contact Note Followup - $cname";
			$taskd["source"]="customer notes";
			$taskd["sourceid"]=$id;
			$taskd["allocatedto"]=$da["followupuser"];
			pai("task",$taskd);
		}

		break;

		case "task":
			$jobid=$da["jobid"];
			tfw("aajntrig.txt","trigger jjj $jobid",true);
			if($jobid=="") return;
			$rmdt=dateadder($fdt,-1,"d");

			$noted=$da;
			$da["tdate"]=$da[$tdate]!=""?$da[$tdate]:date("Y-m-d");
			$noted["date"]=$da["tdate"];
			#$noted["notetype"]="Reminder for ".$da["allocatedto"];
			$fup=$da["allocatedto"];
			$noted["ntype"]="reminder ($fup)";
			$noted["notetype"]="internal";
			$noted["notes"]=$da["detail"];
			$noted["followupby"]=$da["allocatedto"];
			$tstamp=date("Y-m-d H:i");
			$noted["timestamp"]=$tstamp;

			pai("jobnote",$noted);
			
			$area=$da["area"];
			elog("3516trigarea $area $jobid");
			if($area=="Outstanding Debtors"){
				$invoiceno=singlevalue("invoice","invoiceno","jobid",$jobid);
				elog("3519trigarea $area $jobid inv: $invoiceno");
				if($invoiceno>0){
					$inv_note=singlevalue("invoice","paynote","invoiceno",$invoiceno);
					$newNote="$tstamp : Task allocated to ".$da["allocatedto"];
					$unote=$newNote."\n".$inv_note;
					$ua["paynote"]=$unote;
					$keyA["invoiceno"]=$invoiceno;
					pau("invoice",$ua,$keyA);
				}
			}

			
			

		break;



		case "jobnote":
		$ndt=$da["ndate"];
		$fdt=$da["followupdate"];
		$cname=returncontact($da["contactid"]);
		tfw("aajntrig.txt","trigger $ndt $fdt",true);
		#ignore regular note
		if($fdt=="") return;
		$rmdt=dateadder($fdt,-1,"d");

		if($fdt!=$ndt){
			#ignore most notes where followup date defaults to today
			$taskd=$da;
			$taskd["tdate"]=$da["date"];
			$taskd["detail"]=$da["notes"];
			$taskd["area"]=$da["ntype"];
			$taskd["subject"]="Job Note Followup - $cname";
			$taskd["source"]="job notes";
			$taskd["sourceid"]=$id;
			$taskd["allocatedto"]=$da["followupby"];
			$taskd["reminder"]=$rmdt;

			pai("task",$taskd);
		}

		break;

		case "jobs":
		//echo "alert('job trigger $id')\n";
		$jq="select * from jobs where jobid=$id";
		$tf=kda("jobid,customerid,jobdescription,leaddate,esttime,billingcontactname,sitecontact,sitephone,billingcontactnumber,labelid,siteline1,siteline2,siteline3,sitestate,sitepostcode,sitesuburb,territory,sitefm,sitefmph,sitefmemail");
		$ja=infqueryarray_singlerow($jq,$tf);
		$custid=$ja["customerid"];

		$ta["jobid"]=$id;
		$ta["customerid"]=$ja["customerid"];
		$ta["jobtypedesc"]=$ja["jobdescription"];
		$ta["tdate"]=$ja["leaddate"];
		#$ta["estimatedtime"]=$ja["customerid"];
		#$ta["price"]=$ja["customerid"];
		#$ta["chargeid"]=$ja["customerid"];
		$ta["detail"]=$ja["jobdescription"];
		$ta["estimatedtime"]=$ja["esttime"];
		#performarrayinsert("task",$ta);//not required 13.11.13
		#$tsqlx="insert into task(jobid,customerid,jobtypedesc,tdate,estimatedtime,price,chargeid,detail)
		#	 	 values($jid,$custid,'$nscdesc','$d',$nscqty,$nscprice,$nscid,'$detail')";


		#all changes are permanent at this stage.
		#update customer card
		#gotta split contact name
		$bcname=$ja["billingcontactname"];
		$bnames=explode(" ",$bcname);
		foreach($bnames as $bi=>$bnx){
			if($bnames==1){
				$fname=$bnx;
			}else{
				$surnA[]=$bnx;
			}
		}
		$surnx=implode(" ",$surnA);
		$ca["firstname"]=$fname;
		$ca["surname"]=$surnx;
		$ca["phone"]=$ja["billingcontactnumber"];
		performarrayupdate("customer",$ca,"customerid",$custid);

		#update label
		$addresslabelid=$ja["labelid"];
		tfw("custtraa.txt","trig $tablen $id lid: $addresslabelid",true);
		$aa["sitecontact"]=$ja["sitecontact"];
		$aa["sitephone"]=$ja["sitephone"];
		$aa["siteline1"]=$ja["siteline1"];
		$aa["siteline2"]=$ja["siteline2"];
		$aa["siteline3"]=$ja["siteline3"];
		$aa["sitesuburb"]=$ja["sitesuburb"];
		$aa["sitestate"]=$ja["sitestate"];
		$aa["sitepostcode"]=$ja["sitepostcode"];
		$aa["territory"]=$ja["territory"];
		$aa["sitefm"]=$ja["sitefm"];
		$aa["sitefmph"]=$ja["sitefmph"];
		$aa["sitefmemail"]=$ja["sitefmemail"];
		if($addresslabelid>0){
			#on reflection - don't save changes; good chance its an unintentional edit
			#caused by clicking an existing, and totally changing it to a new
			#new one's however only ever saved if none selected and blank id.
			#
			# -short term review - save territory.
			tfw("pfu.txt","update $addresslabelid",true);
			performarrayupdate("addresslabel",$aa,"labelid",$addresslabelid);
		}else{
			//echo "alert('should create new')\n";
			#save new
			$aa["customerid"]=$ja["customerid"];
			performarrayinsert("addresslabel",$aa);
		}
		break;
	}

}

function displayCustomInterface($t,$id,$topnavs=null){
		$_SESSION["ErrorReport"] = false;
		error_reporting(0);
		
	switch($t){
			case "jobs";
			#echo "yeah";
			$jfa=tfa("jobs","j");
			$jfx=implode(",",$jfa);
			$jq="select $jfx ,
			concat(c.mail1,if(c.mail2!='',concat('<br>',c.mail2),''),'<br>',c.mailsuburb,' ',c.state,' ',c.postcode) as billingaddress,
			concat(j.sitesuburb,' ',j.sitestate,' ',j.sitepostcode) as sitesuburb,j.sitestate as shipstate,j.sitepostcode as shippostcode
			from jobs as j
			left outer join customer as c
			on j.customerid=c.customerid
			where jobid=$id";
			tfw("jqx.txt",$jq,true);
			$tf=key2val(tfa("jobs"));

			$tf["billingaddress"]="billingaddress";
			$tf["billingcontactname"]="billingcontactname";
			$tf["billingcontactnumber"]="billingcontactnumber";
			$tf["shipsuburb"]="shipsuburb";
			$tf["shipstate"]="shipstate";
			$tf["shippostcode"]="shippostcode";

			$ja=infqueryarray_singlerow($jq,$tf);
			$this->ja=$ja;
			$ji=new jobInterface($id);
			$ji->powerUsers=$this->powerUsers;

			$ji->editInPlace=true;
			$ji->preloadedDefaultA=$ja;
			$custid=$ji->preloadedDefaultA["customerid"];
			$this->custid=$custid;
			$ji->preloadedDefaultA["custsearch"]=returncompany($ja["customerid"]);
			$ji->jobSummaryPage();

			#$cx=vClean($ji->fx);
			echo $ji->fx;
			$this->noClean=true;
			$this->jobExtras($id);
			#$this->altJobExtras($id);
			$this->viaAjxLog=true;
			$logx=$this->displayExternalLog($id);
			$jx="<div id=masterExtras style='padding-top:10px;'>$cdivq $this->masterExtrax</div>";
			$jx=str_replace("replaceLogHere",$logx,$jx);
			echo stripslashes($jx);
			tfw("jjx.txt",$jx,true);
	 	 	$this->jcallA[]=$ji->initLookupCall;



			break;

			case "xjobs":
			#echo "alert('jjj ci')\n";
			#exit;
			$szt=sizeof($_SESSION["jobs"]["tcontractors"]);
			#if($szt>0) unset($_SESSION["jobs"]["tcontractors"]);
			if($szt>0) unset($_SESSION["jobs"]);

			$jfa=tfa("jobs","j");
			$jfx=implode(",",$jfa);
			$jq="select $jfx ,
			concat(c.mail1,if(c.mail2!='',concat('<br>',c.mail2),''),'<br>',c.mailsuburb,' ',c.state,' ',c.postcode) as billingaddress,
			concat(j.sitesuburb,' ',j.sitestate,' ',j.sitepostcode) as sitesuburb,j.sitestate as shipstate,j.sitepostcode as shippostcode
			from jobs as j
			left outer join customer as c
			on j.customerid=c.customerid
			where jobid=$id";
			tfw("jqx.txt",$jq,true);
			$tf=key2val(tfa("jobs"));

			$tf["billingaddress"]="billingaddress";
			$tf["billingcontactname"]="billingcontactname";
			$tf["billingcontactnumber"]="billingcontactnumber";
			$tf["shipsuburb"]="shipsuburb";
			$tf["shipstate"]="shipstate";
			$tf["shippostcode"]="shippostcode";

			$ja=infqueryarray_singlerow($jq,$tf);
			$this->ja=$ja;
			$ji=new jobInterface($id);
			$ji->editInPlace=true;
			$ji->preloadedDefaultA=$ja;
			$custid=$ji->preloadedDefaultA["customerid"];
			$this->custid=$custid;
			$ji->preloadedDefaultA["custsearch"]=returncompany($ja["customerid"]);
			$ji->jobSummaryPage();

		    $cx=vClean($ji->fx);

			  #edit info fields
			  $eif.="<input id=\'tablename\' value=\'jobs\' type=hidden>";
			  $eif.="<input id=\'primaryid\' value=\'jobid\' type=hidden>";
			  $eif.="<input id=\'primaryval\' value=\'$id\' type=hidden>";
			$cx.=$eif;

		    #load id for allocation
		    # changed-handled via $_get in isched now.
		    #$_SESSION["appt"]["ajobid"]=$id;
#			break;
#		}
#later
		//$jx="display $t $id";
		$this->tablestyle="style=\"width:1500px;\"";

		$jx.=$topnavs."<div id=guts $this->tablestyle > $cx</div>";

		$this->glConditions($id);

		$q="select glchartid,glchartdesc from glchart where glgroupdesc='sales' $this->glcondx";
		$tf=array("glchartid","glchartdesc");
		$salesGL=infqueryarray_pair($q,$tf);
		/*
		$salesGL["4-1000"]="Sales Sydney Internal";
		$salesGL["4-1001"]="Sales Sydney External";
		$salesGL["4-1002"]="Sales National";
		$salesGL["4-1003"]="Sales Melbourne";
		$salesGL["4-1004"]="Sales Canberra";
		$salesGL["4-1005"]="Sales Adelaide";
		$salesGL["4-1006"]="Sales Brisbane";
		$salesGL["4-1007"]="Sales Perth";
		$salesGL["4-1008"]="Sales Darwin";
		$salesGL["4-1009"]="Sales Tasmania";
		*/
		#$this->salesGL=$salesGL;
		foreach($salesGL as $k=>$v) $this->salesGL[$k]="$k $v";

		$cdivq=vClean($divq);
		$this->jobExtras($id);
		$jx.="<div id=masterExtras style=\"padding-top:10px;\">$cdivq $this->masterExtrax</div>";
		echo "g('main-content').style.display = 'block'\n";

		#echo "g('main-content').innerHTML='$jx'\n";
		$jx="<button type=button id=\'ibq\'>ibq</button>";

		echo "$('#main-content').html('$jx')\n";

		tfw("dxl.txt","jj $ji->initLookupCall globs:".$GLOBALS["initLookupCall"],true);
		$this->displayExternalLog($id);
		#suburb lookup not required until editing
		#echo "alert('$ji->initLookupCall')\n";
		#echo $GLOBALS["initLookupCall"]."\n";

		#?? - unsure why exit was used possible duplication of calls between globals and ji.
		#exit;

		#temp hiding, was calling docloads, got to find where
		$this->ibuffCalls();

 	 	echo $ji->initLookupCall."\n";
		//tfw("dci.txt",$jx,true);
		jtop();

		break;
		}
}

function glConditions($jobid){
	$divq="select sitestate,division,territory, costcentreid as jdiv from jobs
	as j left outer join costcentre as cc
	on j.division=cc.costcentredesc
	where j.jobid=$jobid";
	$jff=kda("sitestate,territory,jdiv");
	$sdivA=iqasra($divq,$jff);
	$state=$sdivA["sitestate"];
	$terr="x".strtolower($sdivA["territory"]);
	$jdiv=$sdivA["jdiv"];
	$cityA=kda("sydney,melbourne,adelaide,brisbane,perth,canberra,darwin");
        foreach($cityA as $city){
        	$cx.=" - try $city in $terr";
        	if(strpos($terr,$city)) $regCond=" and (region='$state' )";
        }
	if($regCond!='') $stx=" and (region='$state') ";
	if($regCond=='') if($state!='') $stx=" and (region='NAT') ";
	if($state=='TAS') $stx=" and (region='$state') ";
#	if($state=='NT') $stx=" and (region='$state') ";
	if($jdiv!='') $stx.=" and division='$jdiv' ";;
	tfw("glreg.txt","$divq cx $cx rc $regCond",true);

	$this->glcondx=$stx;
}

function subJobs($id){
	$q="select * from jobs where parentid=$id";
	$tf=kda("jobid,leaddate,custordref,custordref2,jobstage,estimatedsell,siteline1,sitesuburb");
	$tf["custordref"]="Client Ref 1";
	$tf["custordref2"]="Client Ref 2";
	$tf["sitesuburb"]="Site Suburb";
	$tf["siteline1"]="Site Address";
	
	$da=iqa($q,$tf);
	$this->icount=sizeof($da);
	$ist=new divTable();
	$ist->tableid="subjobs";

		$ist->highlighter=true;
		$ist->rowID=kda("jobid");
		$ist->highlightFname="jobview";

	if(!$this->noClean) $ist->noCleanUpSerial=true;
	$ibx=$ist->inf_sortable($tf,$da,"Sub Jobs" ,null,null,true);
	if(sizeof($da)>0){
		if($this->noClean){
			$this->subjobx=$ibx;
		}else{
			$this->subjobx=vClean($ibx);
		}
	}

}


function potentialBillables($id){
		#not in 'cancel' etc
		$notinx="and j.jobstage not in('cancel','cancelled','declined')";

		$q="select p.jobid,poref,p.cost,invoiceref,c.companyname,p.glchartid_sell as glchartid,
		j.estimatedsell as sell
		from purchaseorders as p
		left outer join customer as c on p.supplierid=c.customerid
		left outer join jobs as j on p.jobid=j.jobid
		where p.jobid=$id and (completed='on' or (p.completed is null and p.syncdate is not null))
		and (j.nonchargeable is null or j.nonchargeable !='on')
		$notinx

		";

		/* ANK 2015-09-07 Removed for performance reasones
		$q.=" union
		select j.jobid,e.oldvalue,0,'(deleted JRQ)','','',j.estimatedsell as sell
		from editlog as e
		left outer join jobs as j
		on e.recordid=j.jobid
		left outer join invoicelines as il
		on e.recordid=il.jobid
		where e.fieldname='Job Request deleted'
		and (j.nonchargeable is null or j.nonchargeable !='on')
		and e.recordid=$id
		$notinx
		";
		
		*/

		elog("pbill $q","cinf 3536");
		$tf=kda("jobid,poref,companyname,invoiceref,cost,sell,glchartid,billnow");
		$da=iqa($q,$tf);
		$ist=new divTable();
		$ist->tableidx=$this->tablestyle;
		$da=iqa($q,$tf);

	#$ist->datefields=kda("dte");
	#GL Selection
	$ist->input_fields["glchartid"]="select";
	$ist->colClassText["glchartid"]="glc";
	$ist->colClassText["sell"]="posell";


	if(!isset($this->salesGL)){
	$this->glConditions($id);	
	$sq="select glchartid,glchartdesc from glchart where glgroupdesc='sales' $this->glcondx";
	$stf=array("glchartid","glchartdesc");
	$this->salesGL=infqueryarray_pair($sq,$stf);
	}

	$ist->selectOptions["glchartid"]=$this->salesGL;


##
/*	$gq="select glchartid, concat(glchartid,' ',glchartdesc) as glchartdesc from glchart
	where glgroupdesc='expenses' $this->glcondx";
	$gtf=kda("glchartid,glchartdesc");
	$dca=iqa($gq,$gtf);

	$gcca["not-to-be-migrated"]="not-to-be-migrated";
	foreach($dca as $row) {
		$rg=$row["glchartid"];
		$rd=$row["glchartdesc"];
		#echo "alert('$rg $rd')\n";
		$gcca[$rg]=$rd;
	}
	$ist->selectOptions["glchartid"]=$gcca;
*/
##

	#$ist->input_jcall["billnow"]="onclick=externalbillnow";
	#$ist->input_fields["billnow"]="checkbox";
	$ist->input_fields["sell"]="text";
	#$ist->ajaxData["billnow"]=kda("jobid,cost,sell,poref");
	$ist->entrymode=true;
	$ist->hpd=kda("cost,poref");

	$fn="billnow";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="billnow";
	$ist->noCheckAll[$fn]=true;




	$ist->total_fields=kda("cost,sell");

	$tf["sell"]="Sell (Ex GST)";
	$tf["cost"]="Cost (Ex GST)";
	$ebx=$ist->inf_sortable($tf,$da,"External Job to be billed " ,null,null,true);
	$this->tobeBilled=$ebx;
	$jcl="alert('ablc');";
	$aS->jcallA[]="$jcl";
}

function xpotentialBillables($id){
		#not in 'cancel' etc
		$notinx="and j.jobstage not in('cancel','cancelled','declined')";

		$q="select p.jobid,poref,p.cost,invoiceref,c.companyname,
		j.estimatedsell as sell
		from purchaseorders as p
		left outer join customer as c on p.supplierid=c.customerid
		left outer join jobs as j on p.jobid=j.jobid
		where p.jobid=$id and (completed='on' or (p.completed is null and p.syncdate is not null))
		and (j.nonchargeable is null or j.nonchargeable !='on')
		$notinx
		";

		$q.=" union
		select j.jobid,e.oldvalue,0,'(deleted JRQ)','',j.estimatedsell as sell
		from editlog as e
		left outer join jobs as j
		on e.recordid=j.jobid
		left outer join invoicelines as il
		on e.recordid=il.jobid
		where e.fieldname='Job Request deleted'
		and (j.nonchargeable is null or j.nonchargeable !='on')
		$notinx
		and e.recordid=$id";

		$tf=kda("jobid,poref,companyname,invoiceref,cost,sell,glchartid,billnow");
		$da=iqa($q,$tf);
		$ist=new divTable();
		$ist->tableidx=$this->tablestyle;
		$da=iqa($q,$tf);

	#$ist->datefields=kda("dte");
	#GL Selection
	$ist->input_fields["glchartid"]="select";
	$ist->colClassText["glchartid"]="glc";

	if(!isset($this->salesGL)){
	$sq="select glchartid,glchartdesc from glchart where glgroupdesc='sales' $this->glcondx";
	$stf=array("glchartid","glchartdesc");
	$this->salesGL=infqueryarray_pair($sq,$stf);
	}

	$ist->selectOptions["glchartid"]=$this->salesGL;


##
/*	$gq="select glchartid, concat(glchartid,' ',glchartdesc) as glchartdesc from glchart
	where glgroupdesc='expenses' $this->glcondx";
	$gtf=kda("glchartid,glchartdesc");
	$dca=iqa($gq,$gtf);

	$gcca["not-to-be-migrated"]="not-to-be-migrated";
	foreach($dca as $row) {
		$rg=$row["glchartid"];
		$rd=$row["glchartdesc"];
		#echo "alert('$rg $rd')\n";
		$gcca[$rg]=$rd;
	}
	$ist->selectOptions["glchartid"]=$gcca;
*/
##






	#$ist->input_jcall["billnow"]="onclick=externalbillnow";
	#$ist->input_fields["billnow"]="checkbox";
	$ist->input_fields["sell"]="text";
	#$ist->ajaxData["billnow"]=kda("jobid,cost,sell,poref");
	$ist->entrymode=true;
	$ist->hpd=kda("cost,poref");

	$fn="billnow";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="billnow";
	$ist->noCheckAll[$fn]=true;




	$ist->total_fields=kda("cost,sell");

	$tf["sell"]="Sell (Ex GST)";
	$tf["cost"]="Cost (Ex GST)";
	$ebx=$ist->inf_sortable($tf,$da,"External Job to be billed" ,null,null,true);
	$this->tobeBilled=$ebx;
}


function jobInvoices($id){
	#check possibility of lines not carrying job no after edit.
	$uq="update invoicelines as l left outer join invoice as h
	on l.invoiceno=h.invoiceno
	set l.jobid=h.jobid
	where h.jobid=$id
	and (l.jobid=0 or l.jobid='')";
	#mysql_query($uq);  ANK 2015-09-07


	$q="select distinct il.invoicedate as rd,il.invoiceno,sum(grossval) as gval,
	ih.syncdate,0 as syncbase,il.glchartid,ih.paynote,ih.clientpaynote,
	(select sum(ir.rvalue) from invreceipts as ir where ir.invoiceno=il.invoiceno) as rvalue,
	(select ir.rdate from invreceipts as ir where ir.invoiceno=il.invoiceno order by ir.rdate desc limit 1) as rdate,
	invdoctype,origdocno,
	IF(isprogressclaim=1, '<input id=progclaim type=checkbox checked>','<input type=checkbox>') as isprogressclaim,
	IF(isfinalclaim=1, '<input id=finalclaim type=checkbox checked>','<input type=checkbox>') as isfinalclaim,
	il.invoiceno as thisdocno

	from invoicelines as il
	left outer join customer as c on il.customerid=c.customerid
	left outer join invoice as ih on il.invoiceno=ih.invoiceno
	where il.jobid=$id group by il.invoiceno";
	$tf=kda("invoiceno,rd,gval,glchartid,syncdate,rvalue,rdate,paynote,clientpaynote,invdoctype,thisdocno,origdocno,isprogressclaim,isfinalclaim");
	$tf["gval"]="$ Invoiced";
	
	$tf["invdoctype"] = "Doc Type";
	$tf["origdocno"] = "Original Doc No.";
	$tf["thisdocno"] = "Document No.";
	$tf['isprogressclaim'] = "Progress Claim";
	$tf['isfinalclaim'] = "Final Claim";
	
	$da=iqa($q,$tf);
	$this->icount=sizeof($da);
	#echo "alert('found $this->icount invl')\n";
	$ist=new divTable();
	$ist->datefields=kda("rd,rdate");

    $ist->multilink["invoiceno"][1]=array("ajaxHrefIconMulti","2","Print");
    $ist->multilink["invoiceno"][2]=array("ajaxHrefIconMulti","2","Edit");;
    $ist->multilink["invoiceno"][3]=array("ajaxHrefIconMulti","2","Raise Credit");;
    #$ist->multilink["invoiceno"][4]=array("ajaxHrefIconMulti","2","PDF Inv");;
    $ist->multilink["invoiceno"][4]=array("ajaxHrefIconMultiJq","2","PDF Inv");;
	#$ist->linka["invoiceno"]=array("ajaxHrefIconMulti"=>"view");
	$ist->ajaxDataM["invoiceno"][1]=kda("invoiceno");
	$ist->ajaxStaticDataM["invoiceno"][1]=kda("dcfminv07J");
	$ist->ajaxFnameM["invoiceno"][1]="idoc";
	$ist->iconM["invoiceno"][1]="p";

	$ist->ajaxDataM["invoiceno"][2]=kda("invoiceno");
	$ist->ajaxFnameM["invoiceno"][2]="invedit";
	$ist->iconM["invoiceno"][2]="e";

	$ist->ajaxDataM["invoiceno"][3]=kda("invoiceno");
	$ist->ajaxFnameM["invoiceno"][3]="invCreditJob";
	$ist->iconM["invoiceno"][3]="credit";


	$ist->ajaxDataM["invoiceno"][4]=kda("invoiceno");
	$ist->ajaxFnameM["invoiceno"][4]="invCredt";
	$ist->iconM["invoiceno"][4]="pdf";
	$ist->colClassM["invoiceno"][4]="epdf";
	##hover function
	
	
	$ist->colClass["isprogressclaim"]="class=progclaim";
	$ist->colClass["isfinalclaim"]="class=finalclaim";
	
	
	
	$fn="invoiceno";
/*	$multipos=4;
	$ist->multilink[$fn][$multipos]=array("hrefIconMulti","2","Email PDF invoice");
	$ist->ajaxFnameM[$fn][$multipos]="pdfinv";
	#$ist->ajaxStaticDataM[$fn][$multipos];
	$ist->ajaxDataM[$fn][$multipos]=kda("invoiceno");
	$ist->iconM[$fn][$multipos]="e";
*/

	$multipos=5;
	$tipurl="previewInfo.php?rn=singleInvoiceHistory";
   	$ist->tipTitle[$fn]="allocation detail";
	$ist->multilink[$fn][$multipos]=array("hrefClueTipMulti");
	$ist->ajaxFnameM[$fn][$multipos]=$tipurl;
	#$ist->ajaxStaticDataM[$fn][$multipos];
	$ist->ajaxDataM[$fn][$multipos]=kda("invoiceno");
	$ist->iconM[$fn][$multipos]="news";
	$ist->iconM[$fn]["iconTrails"]=true;

   	$ist->displaysuppress[$fn]=true;

	$ist->linkSeparator="&nbsp;";

	$tf["rd"]="Invoice Date";

	#code to evaluate
	$evalc='if($this->currentrowd["syncdate"]>0) $normaltip=false;';
	$ist->iconEval["invoiceno"]["e"]=$evalc;

	$ist->cssEntireRow["syncdate"]=true;
	$ist->cssNumericComparison["syncdate"]["left"][]="syncdate";
	$ist->cssNumericComparison["syncdate"]["right"][]="syncbase";
	$ist->cssNumericComparison["syncdate"]["operator"]="<";
	$ist->cssNumericComparison["syncdate"]["stylecall"]=$stylecall;
	$ist->cssCondRowLocking=true;
	$ist->total_fields=kda("gval,rvalue");

	$tf["more"]="more";
	#$ist->input_fields["more"]="checkboxJq";
	$ist->xbc["more"]="more";
	$mj="$('.more').click(function(){
	})";

	$ist->tableid="invoices";
	$ist->hpd=kda("invoiceno,gval,paynote,clientpaynote");

	$ist->colClass["more"]="more";
	$mj="
		//alert('hey');
		$('#invoices > tbody  > tr').each(function() {
		var row=$(this).closest('tr');
		var gval= row.find('input[name=gval]').val();
		var ival= row.find('input[name=invoiceno]').val();
		//alert(ival)
		if(gval<0){
			//alert('credit')	;
			var bx='<button class=credoptions inv='+ival+'>Reverse CR '+ival+'</button>';
			//row.find('.more').html(bx);
			row.find('td:nth-child(6)').html(bx);
		}
	});
	$('#invoices').delegate('.credoptions','click',function(){
		var row=$(this).closest('tr');
		var gval= row.find('input[name=gval]').val();
		var ival= $(this).attr('inv');
		//alert('reverse me'+ival);
		var data='params=!I!p=yes!I!url=miscFunctions!I!mode=reverseCredit!I!invoiceno='+ival;
			$.ajax({
					url: ijurl,type: 'POST',data: data,async: false,cache: false,
					success: function (html) {
						//dostuff
						//row.find('.more').html(html);
						row.find('td:nth-child(6)').html(html);
					}
		        })

	});
	$('.invhist','#invoices').click(function(){
		alert('hov');
	});
	
	$('.finalclaim').click(function(){
		console.log('final claim');
		var row=$(this).closest('tr');
		var invoiceno = row.find('td:nth-child(11)').html();
		console.log(invoiceno);
		var invoiceno = invoiceno;
		
		$.ajax({
			url: '../../../common/phoenix/bl/ajax/ajax-invoice.php',
				global: false,
				type: 'POST',
				data: 'mode=processfinalclaim&invoiceno='+invoiceno,
				dataType: 'text',
				async:false,
		        success: function(msg){
		        	console.log(msg);
					alert(msg);
			    }
			 });   
		
		
		
		
	});
";

	$this->moreCall=$mj;
	#$this->jcallA[]=$mj;
	$tf["rdate"]="Receipt Date";
	$tf["rvalue"]="$ Received";

	$ist->xtstyle="style=max-width:1200px";
	$ist->colClass["clientpaynote"]="mpaynote";
	$ist->colClass["paynote"]="mpaynote";
	$ist->noSorting=1;


	

	$ibx=$ist->inf_sortable($tf,$da,"Job Invoices" ,null,null,true);
	#$ist->showIt($tf,$da);
	$this->jobinvs=$ibx;

	foreach($ist->jcallA as $icall) $this->jcallA[]=$icall;


	#check for related invoices if 0
	if(sizeof($da)>0){
		$this->invCount=sizeof($da);
		if($ist->totalValue["gval"]==0){
			$rlx=$this->showRelatedInvoices($id);
			$ibx.="<br>$rlx";
			$this->jobinvs.="<br>$rlx";
		}
	}

	if(sizeof($da)>0){
		if($aS->noClean){
			$this->jobinvx=$ibx;
		}else{
			$this->jobinvx=vClean($ibx);
		}
	}

}

function xjobInvoices($id){
	#check possibility of lines not carrying job no after edit.
		$uq="update invoicelines as l left outer join invoice as h
		on l.invoiceno=h.invoiceno
		set l.jobid=h.jobid
		where h.jobid=$id
		and (l.jobid=0 or l.jobid='')";
		mysql_query($uq);


	$q="select distinct il.invoicedate as rd,il.invoiceno,sum(grossval) as gval,
	ih.syncdate,0 as syncbase,il.glchartid
	from invoicelines as il
	left outer join customer as c on il.customerid=c.customerid
	left outer join invoice as ih on il.invoiceno=ih.invoiceno
	where il.jobid=$id group by il.invoiceno";
	$tf=kda("invoiceno,rd,gval,glchartid,syncdate");
	$tf["gval"]="$ Invoiced";
	$da=iqa($q,$tf);
	$this->icount=sizeof($da);
	#echo "alert('found $this->icount invl')\n";
	$ist=new divTable();
	$ist->datefields=kda("rd");

    $ist->multilink["invoiceno"][1]=array("ajaxHrefIconMulti","2","Print");
    $ist->multilink["invoiceno"][2]=array("ajaxHrefIconMulti","2","Edit");;
    $ist->multilink["invoiceno"][3]=array("ajaxHrefIconMulti","2","Raise Credit");;
	#$ist->linka["invoiceno"]=array("ajaxHrefIconMulti"=>"view");
	$ist->ajaxDataM["invoiceno"][1]=kda("invoiceno");
	$ist->ajaxStaticDataM["invoiceno"][1]=kda("dcfminv07J");
	$ist->ajaxFnameM["invoiceno"][1]="idoc";
	$ist->iconM["invoiceno"][1]="p";

	$ist->ajaxDataM["invoiceno"][2]=kda("invoiceno");
	#$ist->ajaxStaticDataM["invoiceno"][2]=kda("invEdit");
	$ist->ajaxFnameM["invoiceno"][2]="invedit";
	$ist->iconM["invoiceno"][2]="e";

	$ist->ajaxDataM["invoiceno"][3]=kda("invoiceno");
	$ist->ajaxFnameM["invoiceno"][3]="invCreditJob";
	$ist->iconM["invoiceno"][3]="credit";


	$tf["rd"]="Invoice Date";

	#code to evaluate
	$evalc='if($this->currentrowd["syncdate"]>0) $normaltip=false;';
	$ist->iconEval["invoiceno"]["e"]=$evalc;

	$ist->cssEntireRow["syncdate"]=true;
	$ist->cssNumericComparison["syncdate"]["left"][]="syncdate";
	$ist->cssNumericComparison["syncdate"]["right"][]="syncbase";
	$ist->cssNumericComparison["syncdate"]["operator"]="<";
	$ist->cssNumericComparison["syncdate"]["stylecall"]=$stylecall;
	$ist->cssCondRowLocking=true;
	$ist->total_fields=kda("gval");

	$tf["more"]="more";
	#$ist->input_fields["more"]="checkboxJq";
	$ist->xbc["more"]="more";
	$mj="$('.more').click(function(){
	})";

	$ist->tableid="invoices";
	$ist->hpd=kda("invoiceno,gval");
	$ist->colClass["more"]="more";
	$mj="
		//alert('hey');
		$('#invoices > tbody  > tr').each(function() {
		var row=$(this).closest('tr');
		var gval= row.find('input[name=gval]').val();
		var ival= row.find('input[name=invoiceno]').val();
		//alert(ival)
		if(gval<0){
			//alert('credit')	;
			var bx='<button class=credoptions inv='+ival+'>Reverse CR '+ival+'</button>';
			//row.find('.more').html(bx);
			row.find('td:nth-child(6)').html(bx);
		}
	});
	$('#invoices').delegate('.credoptions','click',function(){
		var row=$(this).closest('tr');
		var gval= row.find('input[name=gval]').val();
		var ival= $(this).attr('inv');
		//alert('reverse me'+ival);
		var data='params=!I!p=yes!I!url=miscFunctions!I!mode=reverseCredit!I!invoiceno='+ival;
			$.ajax({
					url: ijurl,type: 'POST',data: data,async: false,cache: false,
					success: function (html) {
						//dostuff
						//row.find('.more').html(html);
						row.find('td:nth-child(6)').html(html);
					}
		        })

	})";

	$this->moreCall=$mj;
	#$this->jcallA[]=$mj;
	$ibx=$ist->inf_sortable($tf,$da,"Job Invoices" ,null,null,true);
	$this->jobinvs=$ibx;



	#check for related invoices if 0
	if(sizeof($da)>0){
		$this->invCount=sizeof($da);
		if($ist->totalValue["gval"]==0){
			$rlx=$this->showRelatedInvoices($id);
			$ibx.="<br>$rlx";
			$this->jobinvs.="<br>$rlx";
		}
	}

	if(sizeof($da)>0){
		if($aS->noClean){
			$this->jobinvx=$ibx;
		}else{
			$this->jobinvx=vClean($ibx);
		}
	}

}

function familyJobsReport(){
	$jobcx=implode(",",$this->jobFam);

	#invoices
	$ivq="select distinct il.jobid,custordref,il.invoiceno,sum(il.netval) as netsales from invoicelines
	as il left outer join invoice as ih
	on il.invoiceno=ih.invoiceno
	where il.jobid in ($jobcx)
	group by invoiceno
	";
	$iff=kda("jobid,netsales,custordref,invoiceno");
	$sqa=iqa($ivq,$iff);
	foreach($sqa as $i=>$row){
		$jobid=$row["jobid"];
		$sales=$row["netsales"];
		$invoiceno=$row["invoiceno"];
		$custordref=$row["custordref"];
		$fda[$jobid]["jobid"]=$jobid;
		$fda[$jobid]["sales"]+=$sales;
		$fda[$jobid]["invoiceno"]=$invoiceno;
		$fda[$jobid]["custordref"]=$custordref;
		$bxx.="<br>add $jobid $sales $invoiceno";
	}


	#cr invoices
	$ivq="select distinct jobid,poref,sum(cost) as cost from purchaseorders
	where jobid in ($jobcx)
	group by poref
	";
	$iff=kda("jobid,poref,cost");
	$sqa=iqa($ivq,$iff);
	foreach($sqa as $i=>$row){
		$jobid=$row["jobid"];
		$sales=$row["cost"];
		$poref=$row["poref"];
		#$custordref=$row["custordref"];
		$fda[$jobid]["jobid"]=$jobid;
		$fda[$jobid]["cost"]+=$cost;
		$fda[$jobid]["poref"]=$poref;
		#$fda[$jobid]["custordref"]=$custordref;
		$bxx.="<br>add $jobid $sales $invoiceno";
	}


	#cr parents
	$pvq="select distinct jobid,parentid,custordref from jobs
	where jobid in ($jobcx)
	group by jobid
	";
	$iff=kda("jobid,parentid,custordref");
	$sqa=iqa($pvq,$iff);
	foreach($sqa as $i=>$row){
		$jobid=$row["jobid"];
		$pid=$row["parentid"];
		$custordref=$row["custordref"];
		$cost=$fda[$jobid]["cost"];
		$sales=$fda[$jobid]["sales"];
		if(($cost<>0)|($sales<>0)){
		$fda[$jobid]["jobid"]=$jobid;
		$fda[$jobid]["parentid"]=$pid;
		$fda[$jobid]["custordref"]=$custordref;
		$bxx.="<br>add $jobid $sales $invoiceno";
		}
	}







	$ist=new divTable();
	$itf=kda("jobid,parentid,custordref,invoiceno,sales,cost");
	$ist->total_fields=kda("sales,cost");
	$ist->highlighter=true;
	#$ist->highlightFname="jobviewInParent";
	$ist->highlightFname="jobviewNW";
	$ist->rowID=kda("jobid");
	$itf["sales"]="Sales Ex GST";
	$ibx=$ist->inf_sortable($itf,$fda,"Job & Sub Job Related" ,null,null,true);
	#return "$ivq $bxx $ibx";
	return "$ibx";
}

function familyJobs($jobid){
        if($this->jobFamC>50) return false;
	$this->jobFamC++;
	$q="select jobid,parentid from jobs where jobid=$jobid or parentid=$jobid";
	$tf=kda("jobid,parentid");
	$ja=iqa($q,$tf);
	foreach($ja as $i=>$row){
		$pid=$row["parentid"];
		$jid=$row["jobid"];
		if($pid!=""){
	        	if(!in_array($pid,$this->jobFam)){
	        	$this->jobFam[]=$pid;
	        	#$this->jobFamQ[]=$q;
	        	$this->familyJobs($pid);
	        	}
	        }
		if($jid!=""){
	        	if(!in_array($jid,$this->jobFam)){
	        	$this->jobFam[]=$jid;
	        	#$this->jobFamQ[]=$q;
	        	$this->familyJobs($jid);
	        	}
	        }

	}

	/*$q="select jobid from jobs where parentid=$jobid";
	$pf=kda("jobid");
	$ja=iqa($q,$tf);
	foreach($ja as $row){
		$pid=$ja["parentid"];
	}
	*/

}

function showRelatedInvoices($jobid){
	$q="select custordref,custordref2 from jobs where jobid=$jobid";
	$tf=kda("custordref,custordref2");
	$ja=iqasra($q,$q);
	$c1=$ja["custordref"];
	$c2=$ja["custordref2"];
	if($c1!="") $cra[]=$c1;
	if($c2!="") $cra[]=$c2;
	$inxs=implode("','",$cra);


	$q="select ih.jobid,i.invoiceno,i.invoicedate,i.grossval,j.siteline1
	from invoicelines as i
	left outer join invoice as ih
	on i.invoiceno=ih.invoiceno
	left outer join jobs as j
	on i.jobid=j.jobid
	where ih.custordref in('$inxs')
	and i.jobid!=$jobid";

	$ist=new divTable();
	$itf=kda("jobid,siteline1,invoiceno,invoicedate,grossval");
	$itf["grossval"]="$ Inv";
	$itf["siteline1"]="Customer";
	$itf["invoicedate"]="Invoice Date";
	$da=iqa($q,$itf);

	$ist->highlighter=true;
	#$ist->highlightFname="jobviewInParent";
	$ist->highlightFname="jobviewNW";
	$ist->rowID=kda("jobid");

/*	$ist->ajaxDataM["invoiceno"][1]=kda("invoiceno");
	$ist->ajaxStaticDataM["invoiceno"][1]=kda("dcfminv07J");
	$ist->ajaxFnameM["invoiceno"][1]="idoc";
	$ist->iconM["invoiceno"][1]="p";
*/

	$ibx=$ist->inf_sortable($itf,$da,"Related Job Invoices" ,null,null,true);
	return "$ibx";

}


function altJobExtras($id){
	$this->subJobs($id);
	if($this->subjobx!="") $this->masterExtrax.="<div id=subjobs>$this->subjobx</div>";

	#moved job invoices to GP tab
	$this->jobInvoices($id);

	#$this->masterExtrax.="jexq".vclean($q);

	## test for potential billing - if invoices exist - only on GP tab
	# ie completed externals, internals, unbilled
	# or (p.completed is null and p.syncdate is not null
	if($this->invCount>0){
		$plurs=$this->invCount>1?"s":"";
		$this->jobinvx="$this->invCount invoice$plurs for this job can be viewed on the GP tab";
		$this->suppressExternalBilling=true;
	}
	if(!$this->suppressExternalBilling){
		$this->potentialBillables($id);

	}
	$this->masterExtrax.="<div id=jobinvoices> $this->jobinvx</div>";




		if(!$this->suppressExternalBilling){
			if($this->tobeBilled!="") {
					if($this->noClean){
						$this->masterExtrax.=$this->tobeBilled;
					}else{
						$this->masterExtrax.=vclean($this->tobeBilled);;
					}
			}


			#$ebx=$ist->inf_sortable($tf,$da,"External Job to be billed" ,null,null,true);
			#if(sizeof($da)>0)	$this->masterExtrax.=vclean($ebx);
		}


	##job notes
	$nx=$this->loadJobNotes($id);
	if($this->noClean){
	$this->masterExtrax.="<div id='jobnotes'>notes $nx </div>";
	}else{
	$this->masterExtrax.="<div id=jobnotes>notes".vClean($nx)."</div>";
	}


	$this->masterExtrax.=$qx;


}


function jobExtras($id){
    $this->tablestyle="style=\"width:800px;\"";
   	/*
   	include_once "$this->clientpath/customscript/jobQuery.php";
	$jq=new jobQuery;
	$mx=$jq->singleJobProjection($id);
	#$tq=vClean($jq->tempq);
	$this->masterExtrax.="<div id=marginSummary>$tq".vClean($mx)."</div>";
*/

	## show invoices
	$this->subJobs($id);

	#moved job invoices to GP tab
	$this->jobInvoices($id);

	#$this->masterExtrax.="jexq".vclean($q);

	## test for potential billing - if invoices exist - only on GP tab
	# ie completed externals, internals, unbilled
	# or (p.completed is null and p.syncdate is not null
	if($this->invCount>0){
		$plurs=$this->invCount>1?"s":"";
		$this->jobinvx="$this->invCount invoice$plurs for this job can be viewed on the GP tab";
		$this->suppressExternalBilling=true;
	}
	if(!$this->suppressExternalBilling){
		$this->potentialBillables($id);
		include_once "$this->clientpath/customscript/jobInterface.php";

		$ji=new jobInterface();
		$ji->jobInvScripts($id);
		foreach($ji->jcallA as $jfx){
			$jjjx.=$jfx;
			$this->jcallA[]=$jfx;
		}
		tfw("jfx.txt","j $jjjx",true);

	}




	//if($this->icount<1) $this->jobinvx="";
	if($this->subjobx!="") $this->masterExtrax.="<div id=subjobs>$this->subjobx</div>";

	#$salesGL=$this->regionalGlcode($jobid,"sales");

	#if(sizeof($da)>0){
		$this->masterExtrax.="<div id=jobinvoices> $this->jobinvx</div>";
		if(!$this->suppressExternalBilling){
			if($this->tobeBilled!="") {
					if($this->noClean){
						$this->masterExtrax.=$this->tobeBilled;
					}else{
						$this->masterExtrax.=vclean($this->tobeBilled);;
					}
			}


			#$ebx=$ist->inf_sortable($tf,$da,"External Job to be billed" ,null,null,true);
			#if(sizeof($da)>0)	$this->masterExtrax.=vclean($ebx);
		}
		#$this->masterExtrax.="jexq".vclean($q);
	#}else{
	#	$this->masterExtrax.="<div id=jobinvoices>$this->jobinvx</div>";
	#}


	##job notes
	$nx=$this->loadJobNotes($id);
	if($this->noClean){
	$this->masterExtrax.="<div id=jobnotes>notes".$nx."</div>";
	}else{
	$this->masterExtrax.="<div id=jobnotes>notes".vClean($nx)."</div>";
	}



	##internal allocation
	$q="select d.jobid,d.apptid,d.taskid,d.dte,d.start,d.userid,d.notes,
	j.estimatedsell as sell,u.email,d.materialNotes,d.materialCost,
	if(t.completed='on' or d.completed='on','on','') as completed
	 from diary as d
	left outer join task as t on d.taskid=t.taskid
	left outer join jobs as j on d.jobid=j.jobid
	left outer join users as u on d.userid=u.userid
	where d.jobid='$id'
	and (u.email='' or u.email is null or u.email like '%@%')
	";
	tfw("intq.txt",$q,true);

	$tf=kda("jobid,apptid,taskid,dte,start,userid,notes,materialNotes,materialCost,completed,intsell,salesgl,intbillnow");
	$tf["intsell"]="$ Sell (Ex GST)";
	#$tf["intbillnow"]="Bill Now";
	$da=iqa($q,$tf);

	/* get material costs */
	$sql = sprintf("SELECT apptid, inventory.prodid, qty, cost, shortdesc
					FROM job_parts_allocation, inventory
					WHERE jobid = '%s'
					AND inventory.prodid = job_parts_allocation.prodid",
					mysql_real_escape_string($id));
	$result = mysql_query($sql);
	if(mysql_num_rows($result)){
		while($row = mysql_fetch_assoc($result)){
			$aParts[$row['apptid']]['materialnotes'] = $aParts[$row['apptid']]['materialnotes'].$row['qty'].' x '.$row['shortdesc']."\n\n";
			$aParts[$row['apptid']]['materialcost'] = $aParts[$row['apptid']]['materialcost']+($row['cost']*$row['qty']);
		}
	}
	foreach($da as $key => $row){
		if(isset($aParts[$row['apptid']])){
			$da[$key]['materialCost'] = $aParts[$row['apptid']]['materialcost'];
			$da[$key]['materialNotes'] = $aParts[$row['apptid']]['materialnotes'];
		}
	}
	//print_r($aParts);
	//print_r($da);
	$uA=aFV($da,"userid");
	$uX=implode(",",$uA);
	foreach($uA as $ui){
		$hA[]="<a class=iucr userid=$ui style='cursor:pointer';>$ui</a>";
	}
	$uX=implode(",&nbsp;",$hA);
	$this->masterExtrax.="<br>Internal contractors Compliance review ..Click to review<br>$uX";
	$this->jcallA[]="$('.iucr').click(function(){
		var uid=$(this).attr('userid');
		alert(uid);
	});";
	if(sizeof($da)>0){
		$title="<h3>Internal Allocation Detail </h3>";
		$ist=new divTable();
		$ist->tableidx=$this->tablestyle;

		/**/
		$ist->highlighter=true;
		$ist->rowID=kda("userid,dte");
		#$ist->highlightFname="viewSchedule";
		$ist->highlightFname="viewScheduleFcal";
		$ist->noHighlight=kda("intbillnow,completed,intsell,salesgl");
		/**/

		$ist->highlightColFname["dte"]="viewSchedule";
		$ist->highlightColFname["userid"]="viewSchedule";
		$ist->highlightColFname["start"]="viewSchedule";

		//$ist->editableFields=kda("materialCost,notes,materialNotes");
		//$da=iqa($q,$tf);
		$ist->datefields=kda("dte");

		#$ist->input_jcall["completed"]="onclick=internalCompleted";
		$ist->input_fields["completed"]="checkbox";
		$ist->input_fields["completed"]="checkboxJq";
		$ist->ckboxClass["completed"]="intComplete";


		$ist->input_jcall["intbillnow"]="onclick=internalbillnow";
		$ist->input_fields["intbillnow"]="checkbox";

		$ist->input_fields["intsell"]="text";
		#$ist->input_fields["materialCost"]="text";

		#$ist->input_fields["notes"]="textarea";
		$ist->ajaxData["completed"]=kda("jobid,taskid,apptid");
		$ist->ajaxData["intbillnow"]=kda("jobid");
		$ist->entrymode=true;
		$ist->editable=true;
		$ist->tablen="diary";

		#editable if not excluded via noHighlight and no specifiedFunction

		#GL Selection
		$ist->input_fields["salesgl"]="select";
		$ist->selectOptions["salesgl"]=$this->salesGL;
		$x=$ist->inf_sortable($tf,$da,$title,null,null,true);
	}else{
		$x="This job has not been allocated yet.";
	}
	if(sizeof($da)>0){
		if($this->noClean){
		$this->masterExtrax.=$x;
		}else{
		$this->masterExtrax.=vClean($x);
		}
	}

	##contractor allocation
	$q="select d.jobid,d.apptid,d.taskid,d.dte,d.start,d.duration,te.lm_te_activity as activity,d.userid,d.notes,
	j.estimatedsell as sell,u.email,d.materialNotes,d.materialCost,lm_te_approval_status,lm_te_approved_by,lm_te_approve_date,
	if(t.completed='on' or d.completed='on','on','') as completed
	 from diary as d
	left outer join task as t on d.taskid=t.taskid
	left outer join jobs as j on d.jobid=j.jobid
	left outer join users as u on d.userid=u.userid
	left outer join lm_timeentry te ON d.apptid=te.lm_te_apptid
	where d.jobid='$id'
	and (u.email!='' and u.email is not null and u.email not like '%@%')
	";

	$tf=kda("jobid,apptid,taskid,dte,start,duration,activity,userid,lm_te_approved_by,lm_te_approve_date,notes");
	
	$tf['lm_te_approved_by'] = "Approved By";
	$tf['lm_te_approve_date'] = "Approval Date";
	
	$da=iqa($q,$tf);
	if(sizeof($da)>0){
		$title="<h3>Contractor Allocation Detail</h3>";
		$ist=new divTable();
				/**/
			#$ist->highlighter=true;
			#$ist->rowID=kda("userid,dte");
			#$ist->highlightFname="viewSchedule";
			#$ist->noHighlight=kda("billnow,completed,sell");
				/**/

		$ist->highlighter=true;
		$ist->rowID=kda("userid,dte");
		#$ist->highlightFname="viewSchedule";
		$ist->highlightFname="viewScheduleFcal";
		$ist->noHighlight=kda("billnow,completed,sell");
		$ist->noHighlight=kda("billnow,completed,sell,materialcost");
		/**/

		$ist->highlightColFname["dte"]="viewScheduleFcal";
		$ist->highlightColFname["userid"]="viewScheduleFcal";
		$ist->highlightColFname["start"]="viewScheduleFcal";


		#$ist->editableFields=kda("materialCost,notes,materialNotes");

		$ist->editable=true;
		$ist->tablen="diary";

		$ist->tableidx=$this->tablestyle;

		$da=iqa($q,$tf);
		$ist->datefields=kda("dte,lm_te_approve_date");


		$ist->total_fields=kda("duration");
				/**/
				/**/


		if(sizeof($da)>0) $x=$ist->inf_sortable($tf,$da,$title,null,null,true);
	}else{
		$x="This job has not been allocated yet.";
	}
	if($this->noClean){
	$this->masterExtrax.=$x;
	}else{
	$this->masterExtrax.=vClean($x);
	}



	####External allocation
    	if($this->viaAjxLog) $replaceLogx="replaceLogHere";
    	$ex.="<div id=eaLog>tcal Trade Contractors Action Log replaceLogHere</div>";
	$this->masterExtrax.=$ex;

	##quotes (update dates first time only)
	$qdate=singlevalue("jobs","qdate","jobid",$id,false);
	$clickx="onclick=";
	#$clickx.="buildPdf(\'!I!fid=dcfmq07J!I!id=$id!I!prefix=quote!I!folder=quotes\');";

	if($qdate==""){
		$dt=date("Y-m-d");
		$dtf=date("d/m/Y");
		$oqx.="g(\'fakeqdate\').value=\'$dtf\';g(\'qdate\').value=\'$dt\';";
	}
	$oqx=$clickx."window.open(\'./iformdoc.php?fid=dcfmq07J&custform=1&ref=$id\')";

	if($_SESSION["oe"]["qtereqd"])	{
	$qx="<br><button type=button $oqx>Display Client Quote</button>";

	#$url="../customscript/quoteMailer.php?rn=1&jobid=$id&nid=";
	#$eurl="javascript:thickBox(\'Email_Quote\',\'x\',\'$url\',\'$id\')";
	#$eurl="javascript:edoc(\'dcfmq07J\',\'$id\')";
	#$qx.="&nbsp;";
	#$qx.=sdlink($eurl,"eml","email quote");

	#$eurl2="javascript:jqUiModalQuote

	#buildPdf(params)

	#$eurl2="javascript:alert(\'aaa\');jqUiModalExisting(\'divid=dialogQuoteMailer!I!tn=quoteMailer!I!title=Address!I!jobid=$id\');";
	#$eurl2="javascript:jqUiModalExisting(\'divid=dialogQuoteMailer!I!tn=quoteMailer!I!title=Address!I!jobid=$id\');";
	#async build, then display
	$params="!I!divid=dialogQuoteMailer!I!tn=quoteMailer!I!title=Address!I!jobid=$id";
	$params.="!I!fid=dcfmq07J!I!id=$id!I!prefix=quote!I!folder=quotes";
	#$clickx.="buildPdf(\'$params\',\'$xvars\');";

	$eurl2="javascript:qpdfEmail(\'qstr=$params&func=buildPdf\');";
	tfw("eurl.txt",$eurl2,true);


	#$qx.="&nbsp;".sdlink($eurl2,"eml","email quote");

	$qx.="&nbsp;<a class=ibq>".ib("eml","email quote","")."</a>";

	#$qx.="<button type=button id='ibq'>eml yes ibq</button>";
	$jcqx="$('.ibq').click(function(){
		//alert('ibq');
		jmg('jobQuoteMailerUiJq','!I!jobid=$id!I!mode=bpdf');
	});";
	$this->jcallA[]=$jcqx;

	$msx="onclick=quoteSent($id);";
	$qx.="&nbsp;<button $msx>Mark As Sent?</button></br>";

	$oqtx=$clickx."window.open(\'./rtftemplate.php?fid=dcfmq07J&custform=1&ref=$id\')";
	$qx.="&nbsp;<button $oqtx>Load Quote Template</button></br>";
	}
	$this->masterExtrax.=$qx;

	##Doc Loader:
	$this->screenRefresh="onclick=javascript:jobview(\'$id\');";
	#$clx="onclick=jqUiModalLoader(\'dialogGeneric\',\'testajl\',\'dialogGeneric\')";
	$clx="onclick=jqUiModalLoader('dialogGeneric','ajlDocload','dialogGeneric','!I!jobid=$id!I!reload=minidocs!I!reloaddivid=minidocs')";
	$this->altLoader="<div style='margin-top:6px;'><a href='https://www.youtube.com/watch?v=W1zuNyqEEKc' target=_blank><img src='../images/help.jpg' style='margin-top:0px;width:25px;height=25px;'></a> &nbsp <button class='btn-green' style='vertical-align: top;' $clx >Doc Loader</button></div>";

	$this->showMasterDocData("jobs",$id);
	$this->showMasterImages("jobs",$id);
	#$this->showTestImages("jobs",$id);
	#$this->xshowMasterImages("jobs",$id);
	$this->masterExtrax.=$this->elx;
	//tfw("mex.txt","$this->masterExtrax",true);
	
	$rx= $this->showReports($id);

	##Call Centre emails
	$nx=$this->loadCallCtrEmails($id);
	//if($this->noClean) $this->masterExtrax.="<br><div style='float:left;padding-left:5px;padding-top:5px;width:600;'>$nx</div>";
	//if(!$this->noClean) $this->masterExtrax.="<br><div style=\'float:left;padding-left:5px;padding-top:5px;width:600;\'>".vClean($nx)."</div>";
	
	$this->masterExtrax.="<br><div style='padding-top:5px;width:800;'>$rx</div>";
	$this->masterExtrax.="<br><div style='padding-top:5px;width:600;'>$nx</div>";
}

function loadCallCtrEmails($id){
	$custid=singlevalue("jobs","customerid","jobid",$id,false);
	$q="select * from contact where customerid='$custid' and
	(position like '%call_centre%' or role='call centre') and email!=''";
	$tf=kda("contactid,firstname,firstname,email");
	$tf["firstname"]="NAME";
	$da=iqa($q,$tf);
	$caption="New call centre email jobid: $id";
	$caption="New&nbsp;call&nbsp;centre&nbsp;email&nbsp;$id";
	$url="../customscript/contactEmail.php?rn=1&jobid=$id";
	$imageGroup="x";
	$nbx="onclick=tb_show('$caption','$url','$imageGroup')";

	#$newb="<button $nbx>Add Email</button>";
	#if(sizeof($da)>0){
		$title="<h3>CALL CENTRE CONTACTS $newb</h3>";
		$ist=new divTable();
		$ist->tableidx=$this->tablestyle;
		$ist->editable=true;
		$ist->tablen="contact";

		$ist->input_fields["contactid"]="hidden";

		$ist->toolA[0]["label"]="NEW CALL CENTRE CONTACT";
		$ist->toolA[0]["ttype"]="button";
	$pmx="divid=dialogContact!I!tn=contact!I!title=New!I!role=call%20centre";
	$pmx.="!I!customerid=$custid";
	$ocx="jqUiModalExisting('$pmx')";
		#$ist->toolA[0]["jname"]="jqUiModalExisting('divid=dialogJobNote!I!tn=jobEmail!I!contacttype=call_centre!I!customerid=$custid!I!jobid=$id!I!title=email','1')";
		$ist->toolA[0]["jname"]=$ocx;



		$da=iqa($q,$tf);
		unset($ist->charLimit);
		$nx=$ist->inf_sortable($tf,$da,$title,null,null,true);
	#}
	return $nx;
}
function showReports($id) {
	#$q = "SELECT ins_sitereport_id,report_type,inspection_Date,event_date,notes,is_approved FROM ins_sitereport WHERE jobid=$id";
	$q = "SELECT ins_sitereport_id,RT.name as report_type,inspection_Date,create_date,event_date,notes,is_approved FROM 
	      ins_sitereport IR LEFT JOIN  rep_reporttype RT ON IR.report_type_id = RT.id WHERE jobid=$id and hide=0";
	
	$tf=kda("ins_sitereport_id,report_type,create_date,inspection_Date,event_date,notes,Editreport,Showreport,is_approved");
	
	$tf["ins_sitereport_id"] = "Report Id";
	$tf["report_type"] = "Report Type";
	$tf["create_date"] = "Create Date";
	$tf["inspection_Date"] = "Inspection Date";
	$tf["event_date"] = "Event Date";
	$tf["notes"] = "Inspection Notes";
	$tf["Editreport"] = "Edit Report";
	$tf["Showreport"] = "Generate Report";
	#$tf["Emailreport"] = "Email Report";
	$tf["is_approved"] = "Approved";
	
	$da = iqa($q,$tf);
	
	$title = "<h3>Reports</h3>";
	
	$linkedjobreportid  = singlevalue("jobs","rep_parent_siterepid","jobid",$id);
	$linkedjobid = singlevalue("ins_sitereport","jobid","ins_sitereport_id",$linkedjobreportid);
	
	if ($linkedjobreportid != '') {
		$title.= "Linked job report id: $linkedjobreportid on job $linkedjobid<br>";
	} else {
		$title.= "No linked job report<br>";
	}
	
	$ist=new divTable();
	$ist->tableidx=$this->tablestyle;
	$ist->tablen="report";
	
	$ist->jbuttondesc["Showreport"]="Generate Report";
	$ist->input_fields["Showreport"]="jqButtonInp";
	$ist->buttClass["Showreport"]="Showreport";

	$ist->jbuttondesc["Editreport"]="Edit Report";
	$ist->input_fields["Editreport"]="jqButtonInp";
	$ist->buttClass["Editreport"]="Editreport";
	
	
	$erjx = "
	$('.Editreport').click(function(){
 		var row = $(this).closest('tr');
		var reportid = $.trim(row.find('td:eq(0)').html());
		var reporttype = $.trim(row.find('td:eq(1)').html());
 		var url = '../../itglobal/admin/admin/areatypetopicvalue/$id';
    	
	    window.open(url, '_blank');
	});
	";
   
	
		
    $rjx = "
 	$('.Showreport').click(function(){
 		var row = $(this).closest('tr');
		var reportid = $.trim(row.find('td:eq(0)').html());
		var reporttype = $.trim(row.find('td:eq(1)').html());
 		var url = '../../infbase/ajax/ajaxpostBack.php';
		var reportfile;
  		console.log(reporttype);
  		switch (reporttype){
  			
 			case 'Job Report':
				data='params=!I!url=sitereport!I!mode=jobReport!I!jobid='+$id;
			    reportfile = '../../InfomaniacDocs/reports/JobReports/' + $id + '.pdf';

			break;
			case 'Insurance':
				data='params=!I!url=sitereport!I!mode=SiteReport!I!reportid='+reportid+'!I!jobid='+$id;
			    reportfile = '../../InfomaniacDocs/reports/Insurance/' + $id + '_' + reportid + '.pdf';

			break;
			case 'DTZ/CUB Property Inspection':
				data='params=!I!url=sitereport!I!mode=DTZCUBInspectionReport!I!reportid='+reportid+'!I!jobid='+$id;
			    reportfile = '../../InfomaniacDocs/reports/Inspections/' + $id + '_' + reportid + '.pdf';

			break;
			
			case 'Property Inspection':
				data='params=!I!url=sitereport!I!mode=PropertyInspectionReport!I!reportid='+reportid+'!I!jobid='+$id;
			    reportfile = '../../InfomaniacDocs/reports/Inspections/' + $id + '_' + reportid + '.pdf';

			break;
			case 'Post-vacate Property Inspection':
				data='params=!I!url=sitereport!I!mode=PropertyInspectionReport!I!reportid='+reportid+'!I!jobid='+$id;
			    reportfile = '../../InfomaniacDocs/reports/Inspections/' + $id + '_' + reportid + '.pdf';

			break;
			case 'Office Assets':
				data='params=!I!url=sitereport!I!mode=OfficeAssetsReport!I!reportid='+reportid+'!I!jobid='+$id;
			    reportfile = '../../InfomaniacDocs/reports/Inspections/' + $id + '_' + reportid + '.pdf';

			break;
			case 'Asset Inspection':
				data='params=!I!url=sitereport!I!mode=OfficeAssetsReport!I!reportid='+reportid+'!I!jobid='+$id;
			    reportfile = '../../InfomaniacDocs/reports/Inspections/' + $id + '_' + reportid + '.pdf';

			break;
			case 'Pre-vacate Property Inspection':
				data='params=!I!url=sitereport!I!mode=PropertyInspectionReport!I!reportid='+reportid+'!I!jobid='+$id;
			    reportfile = '../../InfomaniacDocs/reports/Inspections/' + $id + '_' + reportid + '.pdf';

			break;

			case 'Ergon Property Assessment':
				data='params=!I!url=sitereport!I!mode=ErgonPropertyInspectionReport!I!reportid='+reportid+'!I!jobid='+$id;
			    reportfile = '../../InfomaniacDocs/reports/Inspections/' + $id + '_' + reportid + '.pdf';

			break;
						
			case 'Leak Detection':
				data='params=!I!url=sitereport!I!mode=LeakReport!I!reportid='+reportid+'!I!jobid='+$id;
			    reportfile = '../../InfomaniacDocs/reports/LeakReports/' + $id + '_' + reportid + '.pdf';

			break;

			case 'Air Services':
				data='params=!I!url=sitereport!I!mode=generate!I!reportid='+reportid+'!I!jobid='+$id;
			    reportfile = '../../InfomaniacDocs/reports/AirServices/' + $id + '_' + reportid + '.pdf';

			case 'Claim Central':
				data='params=!I!url=sitereport!I!mode=generate!I!reportid='+reportid+'!I!jobid='+$id;
			    reportfile = '../../InfomaniacDocs/reports/ClaimCentral/' + $id + '_' + reportid + '.pdf';

			break;
			
			case 'Maintenance Check':
				data='params=!I!url=sitereport!I!mode=generate!I!reportid='+reportid+'!I!jobid='+$id;
			    reportfile = '../../InfomaniacDocs/reports/Inspections/' + $id + '_' + reportid + '.pdf';

			break;

						
			default:
		       data='params=!I!url=sitereport!I!mode=preview!I!reportid='+reportid+'!I!jobid='+$id;
			   reportfile = '../../InfomaniacDocs/reports/sitereport' + reportid + '.pdf';
 								
 		}

		//alert(data);
 		$.ajax({
      		url: url,
      		type: 'POST',
      		data: data,
      		async: false,
      		cache: false,
      		dataType: 'text',
      		success: function(data){
      			
      			if (data.length < 10){
     			window.open(reportfile, '_blank');
     			} else {
     				alert(data);
     			}
     			jobview('$id');    		}
		});	
  	});
	";
	
	$rjrx = "
 	$('.jobReport').click(function(){
 		var jobid = $('.divHead').html().replace('Job ','').trim().substr(0,6);
 		
		var url = '../../infbase/ajax/ajaxpostBack.php';
 
		data='params=!I!url=sitereport!I!mode=jobReport!I!jobid='+jobid;
		
 			$.ajax({
      		url: url,
      		type: 'POST',
      		data: data,
      		async: false,
      		cache: false,
      		dataType: 'text',
      		success: function(data){
				var reportfile = '../../InfomaniacDocs/reports/Jobreports/' + jobid + '.pdf';
      			window.open(reportfile, '_blank');    		}
		});	
  	});
	";
	$ist->toolA[0]["label"]="Create Job Report";
	$ist->toolA[0]["ttype"]="button";
	$ist->toolA[0]["class"]="jobReport";
	
	$ist->toolA[1]["label"]="Link Job Report";
	$ist->toolA[1]["ttype"]="button";
	$ist->toolA[1]["class"]="linkjobReport";
	
	$ist->jbuttondesc["Emailreport"]="Send Email";
	$ist->input_fields["Emailreport"]="jqButtonInp";
	$ist->buttClass["Emailreport"]="Emailreport";
	
	$ist->input_fields["is_approved"]="checkboxJq";
    $ist->noCheckAll["is_approved"]=true;
    $ist->colClass["is_approved"]="class=approvereport";
	
	$ljx = "
	  $('.linkjobReport').click(function(){
		jqUiModalLoader('dialogGeneric','reportlink','dialogGeneric','!I!jobid=$id');
	  });
	";
	$this->jcallA[]=$ljx;	
	
	$ajx = "
	  $('.approvereport').click(function(){
	  	if ($(this).is(':checked')){
	  	  var cfm = confirm('Approve report and release to client portal?');
		  if (cfm){
		  	var row = $(this).closest('tr');
			var reportid = $.trim(row.find('td:eq(0)').html());
			var url = '../../infbase/ajax/ajaxpostBack.php';
			
			data='params=!I!url=sitereport!I!mode=approve!I!reportid='+reportid+'!I!jobid='+$id;
		
 			$.ajax({
      		  url: url,
      		  type: 'POST',
      		  data: data,
      		  async: false,
      		  cache: false,
      		  dataType: 'text',
      		  success: function(data){
      		  	//alert(data);
  	            alert('Approved Successfully.');
  	            jobview($id);
		
			  }  
		    });	
	  	  }
	  	}
	  });
	
	";
	$this->jcallA[]=$ajx;
	
	$ejx = "
		$('.Emailreport').click(function(){
 		var row = $(this).closest('tr');
		var reportid = $.trim(row.find('td:eq(0)').html());
		var url = '../../infbase/ajax/ajaxpostBack.php';

		data='params=!I!url=sitereport!I!mode=email!I!reportid='+reportid+'!I!jobid='+$id;
		
 		$.ajax({
      		url: url,
      		type: 'POST',
      		data: data,
      		async: false,
      		cache: false,
      		dataType: 'text',
      		success: function(data){
  	          alert('Email Queued Successfully.');
			}  
		});	
  	});
  ";
 
	$jcqx="$('.Emailreport').click(function(){
		var row = $(this).closest('tr');
		var reportid = $.trim(row.find('td:eq(0)').html());
		var data = '!I!jobid='+$id+'!I!reportid='+reportid;
		jmg('jobReportMailerUiJq',data);
	});";
	$this->jcallA[]=$jcqx; 
 
 
   $this->jcallA[]=$ejx;
   $this->jcallA[]=$erjx;
   
	$this->jcallA[]=$rjx;
	$this->jcallA[]=$rjrx;
	
	$nx=$ist->inf_sortable($tf,$da,$title,null,null,true);
	return $nx;
}
function loadJobNotes($id){
	//$q="select jobnoteid,date,ntype,notetype,notes,userid,if(timestamp is not null,timestamp,pdate) as timestamp
	$q="select jobnoteid,date,ntype,notetype,notes,userid,sync_time, pdate
	from jobnote where jobid='$id'
	and notes not like '%added when earlier due date encountered%'
	order by date desc, jobnoteid desc
	";

	$ist=new divTable();
	/*pagination usage*/
	$perPage=25;
	$pageNo=$this->paramsA["np"]>0?$this->paramsA["np"]:"0";
	$offset=$pageNo>0?($pageNo-1)*$perPage:"";
	$ist->offset=$pageNo;
	$ist->paginate=true;
	$ist->pagRPP=$perPage;
	$ist->noLimitQ=$q;
	$ist->offerFullQueryExtract=true;
	$ist->fullQuerySimpleXLFields=true;
	$ist->pageLinkUrl="jobview";
	$ist->noCleanUpSerial=true;
	$ist->pageLinkParams="!I!jobid=$id!I!mode=jnotepage!I!params=1";
	$ist->pageLinkTargetDiv="jobnotelog";
	$ist->pageLinkFunc="ajl";
	$limitx=$offset!=""?" limit $offset,$perPage":" limit $perPage";//to be included in query
	#$ist->paginateAppendQstring=true;//optional for non 'ajl' type links
	$q.=$limitx;



	$tf=kda("jobnoteid,date,ntype,notetype,notes,userid,pdate,sync_time");
	$da=iqa($q,$tf);
	if(sizeof($da)<$perPage) $ist->paginate=false;

	$nbx="onclick=newJobNote(\'$id\',\'normal\');";
	#$newb="<button type=button $nbx>xAdd Note</button>";
	#if(sizeof($da)>0){
		$title="<h3>Job Notes $newb</h3>";
		#$ist->tableidx=$this->tablestyle;
		$ist->tableid="jobnotelist";

		$da=iqa($q,$tf);
		$tf["notetype"]="type";
		$tf["ntype"]="type";
		$tf["notetype"]="Int/Client";
		
		$tf["pdate"]="Timestamp";
		$tf["sync_time"]="Sync Time";		

		$ist->datefields=kda("date,sync_time,pdate");
		$ist->altDtFormat["sync_time"]="d/m/Y H:i:s";
		$ist->altDtFormat["pdate"]="d/m/Y H:i:s";
		unset($ist->charLimit);


		$ist->multilink["notetype"][2]=array("ajaxHrefIconMultiJq");
		$ist->iconM["notetype"][2]="eml";
		$ist->colClassM["notetype"][2]="noteMail";
		$ist->input_fields["jobnoteid"]="hpd";

		$fn="jobnoteid";
		$ist->multilink[$fn][1]=array("ajaxHrefIconMultiJq");
		$ist->colClassM[$fn][1]="noteEdit";
		$ist->input_fields["jobnoteid"]="hpd";
		$ist->iconM[$fn][1]="e";

		/**/
		$ist->toolA[0]["label"]="Add New Note";
		$ist->toolA[0]["ttype"]="button";
		$ist->toolA[0]["class"]="newjobnote";

		$ist->toolA[1]["label"]="Task Notes";
		$ist->toolA[1]["ttype"]="button";
		$ist->toolA[1]["class"]="tasknote";

		$ist->toolA[2]["label"]="Add New Email";
		$ist->toolA[2]["ttype"]="button";
		$ist->toolA[2]["class"]="jobemail";
		
		if ($_SESSION['userid'] == 'itglobal1'){
		  $ist->toolA[3]["label"]="New job note";
		  $ist->toolA[3]["ttype"]="button";
		  $ist->toolA[3]["class"]="newjnote";
		  
		  $jxnew = "$('.newjnote').click(function(){
		  	console.log ('new job note');
		  	
		  	var url = '../../common/phoenix/views/jobnote.php';
   			console.log(url);
   			var jobid = $id;
			console.log('jobid: ' + jobid);
   			var params = 'jobid=' + jobid + '&mode=log';
   			var dn = 'modaldialog';
        
   			$('#' + dn).dialog( {
      			modal:true,
      			width: 900,
      			height: 650,
      			autopen: false,
      			open: function (event,ui) {
  					$('#'+dn).load(url+'?'+params); 	      	
      			},
     			title: 'Add Job Note - (Job ' + jobid +')',
      		});
			jobview(jobid);	
		  })";
		  
		  $this->jcallA[] = $jxnew;
		  
		}

		if($this->altTf["jobnotes"]) $tf=$this->altTf["jobnotes"];

		#unset($ist->toolA);
		$ist->sortMethod=="normal";
		$nx=$ist->inf_sortable($tf,$da,$title,null,null,true);
		foreach($ist->jcallA as $icall) $this->jcallA[]=$icall;

	#}
	
	
	
	
	
	return "<div id=jobnotelog>$nx</div>";
}


function performPostSearchBehaviour($context,$custid,$pa=null){
	#echo "alert('pbbjjjj $context')\n";

	switch ($context){
		case "externalAllocation":
		$cname=returncompany($custid);

		if(isset($_SESSION["jobs"]["tcontractors"])){
			array_unshift($_SESSION["jobs"]["tcontractors"],$cname);
			$fa=array_flip($_SESSION["jobs"]["tcontractors"]);
			$fa[$cname]=$custid;
			$_SESSION["jobs"]["tcontractors"]=array_flip($fa);
			}else{
			$_SESSION["jobs"]["tcontractors"][$custid]=$cname;
		}
		$this->displayContractorList();
		break;

		default:
	$q="select custtype,supplier_threshold,concat(c.mail1,if(c.mail2!='',concat('',c.mail2),''),'\n',c.mailsuburb,' ',c.state,' ',c.postcode) as billingaddress,
			concat(c.firstname,' ',c.surname) as billingcontactname,
			c.phone as billingcontactnumber
			from customer as c
			where customerid=$custid";
	$tf=kda("billingaddress,billingcontactname,billingcontactnumber,custtype,supplier_threshold");
	$da=iqasra($q,$tf);
	$bca=vClean($da["billingaddress"]);
	$bcn=vClean($da["billingcontactname"]);
	$bcp=$da["billingcontactnumber"];
	$custtype=$da["custtype"];
	$notexceed=$da["supplier_threshold"];


	echo "g('billingaddress').value='$bca';\n";
	echo "g('billingcontactname').value='$bcn';\n";
	echo "g('billingcontactnumber').value='$bcp';\n";
	echo "g('billingcontactnumber').value='$bcp';\n";
	echo "g('notexceed').value='$notexceed';\n";
	echo "g('keydate').value = '$custid';\n";

	$this->extraInfo="cust type: $custtype";
	echo "calcbuffer()\n";
	echo "ofc()\n";


	#prior notifyurl
	$nuq="select notifyurl from jobs
	where customerid=$custid
	and notifyurl!=''
	order by jobid desc limit 1";
	$nurl=iqasrf($nuq,"notifyurl");
	#echo "alert('try $custid $nurl')\n";
	if($nurl!="") echo "if(g('notifyurl')) g('notifyurl').value='$nurl';\n";


	//echo "alert('extrainfo xx');\n";
		break;
	}
}

function byPassJSDate($dtfield,$dtbufferField,$internalDate,$dateVal,$dbuff){
	#if($this->jobid=="") return;
	#$dbuff=singlevalue("jobs",$dtbufferField,"jobid",$this->jobid,false);
	$this->cbuffdue($dtfield,$dtbufferField,$dateVal,$dbuff,$internalDate,$this->jobid);

	#cbuffdue($dfield,$buffField,$dd,$buffd=null,$targetDate=null,$jobid=0)
}

function customDateBehaviour($fn,$dv,$params){
	$pa=formCruncher($params);
	#echo "alert('cdb $fn $dv 3718 $params')\n";
	#go to params instead of session 10.7.09
	#if($_SESSION["jobid"]>0){
	if($pa["jobid"]>0){
		#$jobid=$_SESSION["jobid"];
		$jobid=$pa["jobid"];
		$this->jobid=$jobid;
		$ja[$fn]=$dv;
		performarrayupdate("jobs",$ja,"jobid",$jobid);
	}

	switch($fn){
		case "duedate":
		#apply 2 days prior for internal
		#but can be over ridden, need a calc to react to jdaysbuffer
		#needs to go via js to get buffer
		#echo "calcinternalduedate('duedate','jdaysbuffer','internalduedate')\n";
		$buffv=$pa["jdaysbuffer"];
		$this->byPassJSDate('duedate','jdaysbuffer','internalduedate',$dv,$buffv);
		break;

		case "qduedate":
		#echo "calcinternalduedate('qduedate','qduebuffer','qdueintdate')\n";
		$buffv=$pa["qduebuffer"];
		$this->byPassJSDate('qduedate','qduebuffer','qdueintdate',$dv,$buffv);
		break;

		case "qrespduedate":
		#echo "calcinternalduedate('qrespduedate','qdrbuffer','qrespintdate')\n";
		$buffv=$pa["qdrbuffer"];
		$this->byPassJSDate('qrespduedate','qdrbuffer','qrespintdate',$dv,$buffv);
		break;

		case "responseduedate":
		#echo "calcinternalduedate('responseduedate','jrespbuffer','jrespintdate')\n";
		$buffv=$pa["jrespbuffer"];
		$this->byPassJSDate('responseduedate','jrespbuffer','jrespintdate',$dv,$buffv);
		break;

		case "extenduntil":
		#echo "alert('hey extend');\n";
		$this->logMasterEdit("jobs","jobid",$jobid,"duedate",$dv);


	    $tablen="jobnote";
	    $t=date("d M Y, H:i");
	    $uid=$_SESSION["userid"];

	    #first time only
	    $nq="select * from jobnote where jobid=$jobid and notes like '%Due date extended%' ";
	    if(qRowCount($nq)==0){
	    	$odt=date("d/m/Y",strtotime($this->oldval));
	    	$ndt=date("d/m/Y",strtotime($dv));
	    	$newda["notes"]="Due date extended from $odt to $ndt on $t by $uid";
	    	$newda["notetype"]="auto";
	    	$newda["jobid"]=$jobid;
	    	$newda["date"]=date("Y-m-d");
	    	$newda["userid"]=$uid;
	    	$id=performarrayinsert($tablen,$newda);
	    }

		$view=true;
		#extend job or quotedate.
		$qst=singlevalue("jobs","quotestatus","jobid",$jobid);
		$qrq=singlevalue("jobs","quoterqd","jobid",$jobid);
		#echo "alert('extend? qst $qst')\n";
		if($qrq!='on') $extendJD=true;
		if($qrq=='on'){
			if($qst!=""){
				$ignorA=kda("accepted,declined");
				if(!in_array($qst,$ignorA)){
					$qdbuff=singlevalue("jobs","qduebuffer","jobid",$jobid);
					echo "g('qduedate').value='$dv'\n";
					$fd=date("d/m/Y",strtotime($dv));
					echo "alert('extending Quote Date to $fd')\n";
					echo "g('fakeqduedate').value='$fd'\n";
					$this->cbuffdue("qduedate","qduebuffer",$dv,$qdbuff,"qdueintdate",$jobid);
				}else{
					$extendJD=true;
				}
			}else{
					$extendJD=true;
			}
		}
		if($extendJD){
				echo "if(g('duedate')) g('duedate').value='$dv'\n";
				$jdbuff=singlevalue("jobs","jdaysbuffer","jobid",$jobid);
				$fd=date("d/m/Y",strtotime($dv));
				echo "alert('Extending job due date to $fd')\n";
				echo "if(g('fakeduedate')) g('fakeduedate').value='$fd'\n";
				#echo "calcinternalduedate('duedate','jdaysbuffer','internalduedate')\n";
				#can skip jscript and go direct?? YES!
				#echo "alert('straight to update')\n";
				$this->cbuffdue("duedate","jdaysbuffer",$dv,$jdbuff,"internalduedate",$jobid);
				#update job notes after datecalcs
		}
		#$ncall="newJobNote($jobid,'extend')\n";
	  	if($pa["via"]) $viax="!I!via=".$pa["via"];
	  	#$ncall="jqUiModalExisting('divid=dialogJobNote!I!tn=jobnote!I!ntype=extend!I!jobid=$jobid!I!title=jobnote!I!duedate=$dv$viax')";
	  	$ncall="jqUiModalExisting('divid=dialogGeneric!I!tn=jobnote!I!ntype=extend!I!jobid=$jobid!I!title=jobnote!I!duedate=$dv$viax')";
		#echo "alert('about to ncall viax: $viax')\n";

		echo "$ncall";

		#launch mailer. - now after note saved
		$this->noteid=$id;
		$this->jobid=$jobid;
		#$this->jobNoteMailer();
		break;

	}
}

function cbuffdue($dfield,$buffField,$dd,$buffd=null,$targetDate=null,$jobid=0){
		#exit;
		$this->logit($targetDate);
		if(($dd=="")|($dd=="undefined")) return;
		
		if($buffd<0) {
		 echo "alert('Buffer cannot be less than 0.');console.log($targetDate);";
		 
		switch($targetDate) {
		  case "internalduedate":
			 echo "$('#jdaysbuffer').val(0);";
			 break;
		  case "jrespintdate":
			 echo "$('#jrespbuffer').val(0);";
			 break;
		  case "qrespintdate":
			 echo "$('#qdrbuffer').val(0);";
			 break;
		  case "qdueintdate":
			 echo "$('#qduebuffer').val(0);";
			 break;		  
		  default:
		  
		}
	
		 exit;
		}
		#echo "alert('$dd')\n";
		$intd=dateadder($dd,-$buffd,"d");
		$fdate=date("d/m/Y",strtotime($intd));
		#echo "alert('dtd $targetDate')\n";
		#echo "alert('2084 $dfield $dd $buffd ndt:$intd $fdate jobid: $jobid')\n";


		#echo "g('fake$targetDate').value='$fdate';\n";
		#echo "g('$targetDate').value='$intd';\n";
		#echo "alert('2417  jid $jobid buff $buffField ')\n";
		if($jobid>0) {
			#echo "alert('ok $jobid save $dfield $dd & $buffField as $buffd & $targetDate as $intd')\n";
			#rpt to save log. - no redisplay
			$da[$dfield]=$dd;
			if($buffField!="null"){
				#echo "alert('2417  f $buffField v $buffd d t:$targetDate dv:$intd j $jobid')\n";
				#echo "alert('2430 ok')\n";
				$da[$buffField]=$buffd;
				$da[$targetDate]=$intd;
			}
			#echo "alert('2430  f $buffField v $buffd d $targetDate $intd j $jobid')\n";
			$this->logMasterEdit("jobs","jobid",$jobid,$targetDate,$intd);
			performarrayupdate("jobs",$da,"jobid","$jobid");
			#$this->saveFieldValue("jobs","jobid",$jobid,$targetDate,$intd,"false");
			#echo "alert('logged ok $jobid $targetDate as $intd fdate: $fdate ')\n";
			#if successful update
			echo "if(g('fake$targetDate')) g('fake$targetDate').value='$fdate';\n";
			echo "if(g('$targetDate')) g('$targetDate').value='$intd';\n";
			#echo "alert('still ok $jobid ')\n";

		}else{
			#for new jobs
			echo "g('fake$targetDate').value='$fdate';\n";
			echo "g('$targetDate').value='$intd';\n";
		}
		#refocus:

		#some redisplay requ'd
		#echo "alert('try $dfield')\n";
		switch($dfield){
			case "duedate":
			#only possible after datecalcs
					$ntx=$this->loadJobNotes($jobid);
						#echo "alert('refresh $jobid');\n";
						$jn=vClean($ntx);
						#$jn="jobnotes here";
						echo "if(g('jobnotes')) g('jobnotes').innerHTML='$jn';\n";
			break;
		}
		echo "if(g('$buffField')) g('$buffField').focus();\n";


}

function performIscreenPost($url,$suburl){
	switch($url){
		case "externalAllocation":
		$this->displayContractorList();
		$this->displayExternalLog($suburl);
		//echo "alert('spis')\n";
		break;
	}
}

function displayContractorList(){
		   	$pc=new iForm();
		    $tf=kda("contractors");
		   	$pc->flabel=$tf;

		   	#gen $pc->ftypes
		   	$pc->allText($tf);
		   	#then override by exception.

		   	#$pc->ftypes["contractors"]="checkboxarray";
		   	$pc->ftypes["contractors"]="radio";
		   	$pc->vertRadio["contractors"]=true;

		   	#$pc->valtable["contractors"]=$_SESSION["jobs"]["tcontractors"];

			$sz=sizeof($_SESSION["jobs"]["tcontractors"]);
		   	$oa["contractors"]=$_SESSION["jobs"]["tcontractors"];
		   	#foreach($_SESSION["jobs"]["tcontractors"] as $jc=>$jci) echo "<br>$jc $jci";

		   	#set up positional arrays
			$new_fa=array_keys($pc->flabel);
			foreach($new_fa as $i=>$fn){
				$new_ft[$i]=$pc->ftypes[$fn];
			}
			$posByName=array_flip(array_values($new_fa));
			$row[]=kda("contractors");

			foreach($row as $rowNo=>$rowd){
				foreach($rowd as $colPos=>$fname){
					$apos=$posByName[$fname];
					$new_rowpos[$apos]=$rowNo;
					$new_colpos[$apos]=$colPos;
				}
			}
			$pc->styleCall["contractors"]="style:width:750px;";
			$pc->formfields($new_fa,$new_ft,$pc->defaults,$oa,$pc->compulsorya,$numerica,$new_rowpos,$new_colpos,$jcalla);
			$x="<form id=contractors><table class=normal width=700px>List of Contractors that have worked in this territory $pc->formtext</table></form>";
			$rx="<div style='height:150px; overflow:scroll;'>$x</div>";
			#tfw("rrx.txt",$rx,true);
			$cx=vClean($rx);
			if($this->returnIt) return $rx;
			echo "g('contractorList').innerHTML='$cx';\n";
}


function createExternalAction($jobid,$tc,$atype,$rfq=null,$returnit=false){
	$this->logit("enter createExternalAction $jobid $atype");
	switch($atype){
		case "rfq":
			$ra["supplierid"] = $tc;
			$ra["rfqdate"] = date("Y-m-d");
			$ra["jobid"] = $jobid;
			$ra["userid"] = $_SESSION["userid"];

		 	$rfq = performarrayinsert("rfq",$ra);

			//ANK 6.3.2014 Update jobstage to internal_incomplete for internal contractors

			if ($origin == 'internal'){
				$this->jobStageChange($jobid,"internal_incomplete");
			}
			else {
				$this->jobStageChange($jobid,"waiting_rfq_response");
			}

			//ANK 4.4.2014 Email events fromm client portal
			include_once($this->clientpath."/contractor/Include/Class/cls.mail.php");
	    	$objmail = new clsmail;
	    	$this->logit("before eventmail");
	    	$objmail->eventEmail($jobid,"QUOTESTART");



		 	/*
		 	* check what docs are required here
		 	*/
		 	$customerid=singlevalue("jobs","customerid","jobid",$jobid);
		 	
			/*
			 * check for contract related jrqrule
			 * /
			

			/* ANK 10.4.2014 Restrict to reuqired_start=1 or required_complete=1 only */
			
		 	$sql = sprintf("SELECT required_start, required_complete, dont_send,documentid,  rule_id
		 					FROM job_rule_category, rule_categories, rules_categories_docs
							WHERE job_rule_category.job_id = '%s'
							AND required_start + required_complete > 0
							AND job_rule_category.rule_id = rule_categories.id
							AND rules_categories_docs.cat_id = rule_categories.id
							AND job_rule_category.rule_id = rules_categories_docs.cat_id",
							mysql_real_escape_string($jobid));

			/* ANK 10.05.2013 Only add completion doc if not jrqonly  */

		 	$sql.=" union
		 	SELECT required_start, required_complete, dont_send,documentid ,r.id
			FROM rules_categories_docs as d
			left outer join rule_categories as r on d.cat_id=r.id
			WHERE r.customerid = $customerid
			AND required_start + required_complete > 0
			AND (jrqonly IS NULL or jrqonly='')";
			
			/* ANK 11.3.2016 Add Document for specific sitees */
			
			$labelid = singlevalue("labelid","jobs","jobid",$jobid);

		 	$sql.= " union 
		 	SELECT required_start, required_complete, dont_send,documentid ,r.id
			FROM rules_categories_docs AS d
			LEFT OUTER JOIN rule_categories AS r ON d.cat_id=r.id
			WHERE r.customerid = $labelid AND TYPE = 'address'";

			
		 	elog("$sql");
		 	$result = mysql_query($sql);
		 	$this->logIt('Check what docs needed using:'.$sql);
		 	if(mysql_num_rows($result)){
		 		while($row = mysql_fetch_array($result)){
		 			$docid=$row["documentid"];
		 			$aRequiredDocs[$docid] = $row;
		 		}
		 		$this->logIt('Found some docs. '. count($aRequiredDocs).' in total.');

		 		/*
		 		* insert into necessary docs into job_document_required table
		 		* as these are coming from a category rule we have an extra field in table called from_category_field
		 		*/

		 		//ANK Add CREATE_DATE to insert query
				foreach($aRequiredDocs as $doc){
				$sql = sprintf("INSERT INTO job_document_required (documentid, jobid, rfqno, required_start, required_complete, dont_send,from_category_rule,create_date)
						 		VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', CURRENT_DATE())",
						 				$doc['documentid'], $jobid, $rfq, $doc['required_start'],
		 							$doc['required_complete'],$doc['dont_send'], $doc['rule_id']);
		 			mysql_query($sql);
		 			$this->logIt('Added to job_document_required table here using: '.$sql);
		 		}
		 	}
		break;

		case "jrq":
		#check quote status first
		#auto accept disabled 21.10.08 on request by Lauren.
		#$this->checkQteAccepted($jobid);
		$this->logit("Accept jrq");
		
		
		#check to see if a quoted job and if there is a matchin rfq
		error_reporting(0);
		$_SESSION["ErrorReport"] = false;
		
		if ($_SESSION['userid'] == 'itglobal1'){
			$this->logit("Check quote"); 
			include ($_SERVER['DOCUMENT_ROOT'] . "/common/phoenix/rfq.class.php");
			$orfq = new rfq(AppType::JobTracker,$_SESSION['userid']);
			$rfqno = $orfq->getRFQ($jobid,$tc);
			$this->logit("After quote1 rfq: $rfqno");
			
			if ($rfqno>0)  $ra['rfqno'] = $rfqno;

			error_reporting(0);
			
		}
		
		$ra["supplierid"]=$tc;
		$ra["podate"]=date("Y-m-d");
		$ra["jobid"]=$jobid;
		$ra["glchartid"]=$this->regionalGlcode($jobid,"po");
		$ra["userid"]=$_SESSION["userid"];
		#echo "alert('rfq: $rfq')\n";
		if($this->apptid>0) $ra["apptid"]=$this->apptid;

		if($rfq>0) {
			$ptext=singlevalue("rfq","rfqcomment","rfqno",$rfq,false);
			$ra["rfqno"]=$rfq;
			$ra["pocomments"]=$ptext;
		}
		if(isset($this->rule_duedate)) $ra["rule_duedate"]=$this->rule_duedate;//recurring jobs

		//ANK 6.3.2014 If internal contractor, set accepted = 'on' and update jobstage
		$origin = singlevalue("customer","origin","customerid",$tc);
		$this->logit($origin);
		
		if ($origin=='internal'){
			$ra["accepted"] = 'on';
			$ra["acceptdate"] = date("Y-m-d");
		}
		
		#2015-12-19 ETP Enhncements
		$ra['statusid'] = singlevalue("purchaseorder_status","id","isactive=1 and code","ALLOCATED");
		$jq = "SELECT IFNULL(jrespintdate,responseduedate- INTERVAL jrespbuffer DAY) AS jrespintdate,
			IFNULL(jrespinttime,responseduetime) AS jrespinttime, 
			IFNULL(internalduedate,duedate - INTERVAL jdaysbuffer DAY) AS internalduedate,
			IFNULL(internalduetime,duetime) AS internalduetime,
			internalqlimit,responseduedate,responseduetime,jdaysbuffer,jrespbuffer
			 from jobs where jobid=$jobid";
		$tf = kda("jrespintdate,internalduedate,jrespinttime,internalduetime,internalqlimit,responseduedate,responseduetime,jdaysbuffer,jrespbuffer");
		$ja=infqueryarray_singlerow($jq,$tf);
		
		$this->logit(print_r($ja,1));
		
		$this->logit("jrespintdate:".$ja['jrespintdate']);
		
		$ra['attendbydate'] = $ja['jrespintdate'];
		$ra['attendbytime'] = $ja['jrespinttime'];
		$ra['completebydate'] = $ja['internalduedate'];
		$ra['completebytime'] = $ja['internalduetime'];
		$ra['polimit'] = $ja['internalqlimit'];
		
	 	$poref = performarrayinsert("purchaseorders",$ra);
	 	$this->porefA[]=$poref;
		
		//ANK 6.3.2014 Update jobstage to internal_incomplete for internal contractors
		if ($origin == 'internal'){
			$this->jobStageChange($jobid,"internal_incomplete");
		}
		else {
			$this->jobStageChange($jobid,"waiting_jrq_response");
		}

	 	$customerid=singlevalue("jobs","customerid","jobid",$jobid);

	 	#no redisplay for internal allocation contractors

		
		//ETP Auto Allocate
	    include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/phoenix/ETP_Auto_Allocate.class.php");
        $etpAutoAllocate = new ETP_Auto_Allocate(AppType::JobTracker, $_SESSION['userid']);
        $etpAutoAllocate->CreateETPJob_Diary($poref);
	 	
	 	/* Check jrqrules to see if there is an override
		 * 
		*/
		error_reporting(0);
		$_SESSION["ErrorReport"] = false;
		 
		include ($_SERVER['DOCUMENT_ROOT'] . "/common/phoenix/jrqrule.class.php");
		$jrqrules = new jrqrule(AppType::JobTracker,$_SESSION['userid']);
		$rulesDocs = $jrqrules->getJRQdocuments($customerid,$jobid); 
		
		$this->logIt("*************JRQ rules documents");
		if(isset($rulesDocs)){
		  $this->logIt(print_r($rulesDocs,1));
		}		 
		 
	 	/* check if there are any docs required here and if there are any entries for these and this job id already
	 	* in the job_document_required_table, if they are we just need to update these now with the purchase order
	 	* ref number (poref)
	 	*/

	 	$this->logIt('Here in jrq case');
	 	$sql = sprintf("SELECT required_start, required_complete, dont_send,documentid,  rule_id
	 					FROM job_rule_category, rule_categories, rules_categories_docs
						WHERE job_rule_category.job_id = '%s'
						AND job_rule_category.rule_id = rule_categories.id
						AND rules_categories_docs.cat_id = rule_categories.id
						AND required_start + required_complete > 0
						AND job_rule_category.rule_id = rules_categories_docs.cat_id",
						mysql_real_escape_string($jobid));
		 	$sql.=" union
		 	SELECT required_start, required_complete, dont_send,documentid ,r.id
			FROM rules_categories_docs as d
			left outer join rule_categories as r on d.cat_id=r.id
			WHERE required_start + required_complete > 0 AND r.customerid = $customerid";
			
			
			/* ANK 11.3.2016 Add Document for specific sitees */
			
			$labelid = singlevalue("jobs","labelid","jobid",$jobid);

		 	$sql.= " union 
		 	SELECT required_start, required_complete, dont_send,documentid ,r.id
			FROM rules_categories_docs AS d
			LEFT OUTER JOIN rule_categories AS r ON d.cat_id=r.id
			WHERE r.customerid = $labelid AND TYPE = 'address'";
					
			
						elog("jrq rules q $sql","cinf 4680");
	 	$result = mysql_query($sql);
	    $this->logIt('Check what docs needed using:'.$sql);


	 	if(mysql_num_rows($result)){
	 		while($row = mysql_fetch_array($result)){
	 			$docid=$row["documentid"];
	 			$aRequiredDocs[$docid] = $row;
	 		}
	 		$this->logIt('Found some docs. '. count($aRequiredDocs).' in total.');

	 		/*
	 		* if there is already an rfq we need to check what docs (if any) are already in job_document_required table
	 		*/

	 		if($rfq > 0){
		 		$sql = sprintf("SELECT * FROM job_document_required WHERE jobid = '%s' AND rfqno = '%s'",
								$jobid, $rfq);
			 	$result = mysql_query($sql);
			 	$this->logIt('RFQ is set so looking for existing job_document_required records using: '.$sql);
			 	if(mysql_num_rows($result)){
			 		while($row = mysql_fetch_array($result)){
			 			$aExistingDocs[$row['documentid']] = $row;
			 		}
			 		$this->logIt('Some docs already in job_document_required table. '.count($aExistingDocs).' in total');
			 	}
			}


		if(isset($rulesDocs)){
		  $this->logIt(print_r($aRequiredDocs,1));
		  $this->logIt(print_r($rulesDocs,1));
		  
		  foreach($rulesDocs as $doc){
		  	$docid = $doc['documentid'];
		  	$aRequiredDocs[$docid] = $doc;
			
		  }
		  #$aRequiredDocs = $rulesDocs;
		  $this->logit("New aRequiredDocs");
		  $this->logIt(print_r($aRequiredDocs,1));
		  
		}	

	 		/*
	 		* insert into necessary docs into job_document_required table
	 		* if any docs already in there, update these records with a poref
	 		*/
	 		foreach($aRequiredDocs as $doc){
	 			if(isset($aExistingDocs[$doc['documentid']])){
	 				$sql = sprintf("UPDATE job_document_required SET poref = '%s' WHERE rfqno = '%s' AND jobid = '%s'",
	 								$poref, $rfq, $jobid);
	 				mysql_query($sql);
	 				$this->logIt('Updating existing record in job_document_required table using: '.$sql);
	 			}else{
		 			$sql = sprintf("INSERT INTO job_document_required (documentid, jobid, rfqno, required_start, required_complete,dont_send, poref, from_category_rule)
		 							VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
		 							$doc['documentid'], $jobid, $rfq, $doc['required_start'],
		 							$doc['required_complete'],$doc['dont_send'], $poref, $doc['rule_id']);
		 			mysql_query($sql);
		
					$this->logit('scribe ' . $jobid );
					$this->logit('customInterface.php - scribe - safety sheet check.');

					$isSafetySheetRequired = true;
					$safetySheetSql = "SELECT podate,poref,po.pdate, j.jobid,po.ssnotreqd,po.rule_code,po.apptid FROM purchaseorders po INNER JOIN jobs j ON po.jobid=j.jobid WHERE po.poref=" . $poref ." ORDER BY poref ASC LIMIT 1";
					$this->logIt('customInterface.php - scribe - safety sheet check. query: ' . $safetySheetSql  . ', jobid: ' . $jobid  );
					$safetySheetResult = mysql_query($safetySheetSql);
					$this->logIt('customInterface.php - scribe - safety sheet check. count: ' . count($safetySheetResult)  . ', jobid: ' . $jobid );
									
					 while($safetySheetRow = mysql_fetch_array($safetySheetResult)){
						  $ssRequired = $safetySheetRow["ssnotreqd"];
						  $apptid = $safetySheetRow["apptid"];
						  
						  if(  !isset($ssRequired) )
						  {
								$this->logIt('customInterface.php - scribe - safety sheet check. ssnotreqd value is null jobid: ' . $jobid . 'apptid: '. $apptid);
						  }
						  
						  if( isset($ssRequired) && $ssRequired == "on" )
						  {
							  $this->logIt('customInterface.php - scribe - safety sheet check. ssnotreqd value: ' . $ssRequired . ', jobid: ' . $jobid . 'apptid: '.$apptid );
							  $isSafetySheetRequired = false;
						  }
						  
					 }

					 if( $isSafetySheetRequired ) 
					 {					 
						$sql = sprintf( "UPDATE diary SET ss_uploaded=0 WHERE apptid=%s", $apptid );
						$this->logit('Inserting record into job_document_required table using: '.$sql);
						mysql_query($sql);
						$this->logit('Updating the required safety document field in diary table using: '.$sql);
					}
					else{
						$this->logIt('Safety sheet is not required for this job. apptid: ' .$apptid);
					}

	 			}
	 		}
	 	}

		# ANK 2014-11-04 Add External Tech to Schedule
		$onschedule = singlevalue("customer","onschedule","customerid",$customerid);
		$this->logit("onschedule: $onschedule");
		if ($onschedule == 'on'){
			
				$_SESSION["ErrorReport"] = false;
				require_once ($_SERVER['DOCUMENT_ROOT'] . "/common/phoenix/diary.class.php");
				$diary = new diary(AppType::JobTracker, $_SESSION['userid']);
				
				$tcemail = singlevalue("customer","email","customerid",$tc);
				$this->logit("tecemail: $tcemail");
				$dte = date("Y-m-d");
				$time = "07:00";
				$userid = $tcemail;
				$apptid = $diary -> AllocateJobToSchedule($dte,$time,$userid,$jobid,$dur=1);
				$this->logit("apptid: $apptid");
				error_reporting(0);		
			
		}
		
		
		##STB
		####combine with client level rules also if necessary eg caltex
		$cjobq="select customerdoctyperules from jobs as j
		left outer join customer as c on j.customerid=c.customerid
		where j.jobid=$jobid";
		$rx=iqasrf($cjobq,"customerdoctyperules");
		$cruleA=implode("|",$rx);
		elog("custrules $cjobq : $rx","cinf 4718");


	 	#then check closing dates via modal..
	 	#echo "jrqDates($jobid);\n";
	 	#replaced by...

		#po is completed, but..
		#now processing job completion via modal
		#$this->jobCompletion($jobid,$jobstage,$dt);
		#echo "g('masterExtras').innerHTML='$this->masterExtrax' \n";
		#$this->displayExternalLog($jobid);
		#now ask whether job is fully complete.
		if($this->viaJquery) return;

		$params="divid=dialogCompletion!I!tn=checkClosingDates!I!jobid=$jobid!I!title=CheckDates";
		#tfw("jqp.txt","pp $params",true);
		$this->jqUiModal($params);


	 	if($returnit) return;
		break;
	}

	elog("l4768 ","custface 4768");

	$this->displayExternalLog($jobid);
	if(isset($this->djcallA)) foreach($this->djcallA as $jcx) echo $jcx;
}


function regionalGlcode($jobid){
	$state=singlevalue("jobs","sitestate","jobid",$jobid,false);
	$rc["QLD"]="5-1036";
	$rc["NSW"]="5-1031";
	$rc["VIC"]="5-1033";
	$rc["ACT"]="5-1034";
	$rc["SA"]="5-1035";
	$rc["WA"]="5-1037";
	$rc["NT"]="5-1036";
	$rc["TAS"]="5-1039";
	$glc=$rc[$state];
	return $glc;
}

function optionalDocs_new(){
	#feb 2013 - now from doc folder
	$dcq="select * from document where xreftable='static' order by docname";
	$tf=kda("documentid,docname");
	$da=iqa($dcq,$tf);
	foreach($da as $row){
		$docid=$row["documentid"];
		$dnA=explode(".",$row["docname"]);
		array_pop($dnA);
		$name=implode("",$dnA);
		$this->doca_new[$name]=$docid;
	}
}


function optionalDocs(){
	#$this->doca["safety"]="hazards";
	#outlook cant handle &,brackets or anything, so only eave spaces which we replace with _
	$this->doca["safety"]="Work Instruction - Instruction for completing DCFM safety sheet";
	$this->doca["using swmns"]="Work Instruction - Rules for using DCFM generic SWMS";
	$this->doca["quote"]="quote";
	$this->doca["quote procedures"]="quoteprocedure";
	#$this->doca["compliance"]="insuranceDec";
	#$this->doca["work method"]="procedures";
	$this->doca["B swmns-Blank"]="B - SWMS - BLANK TEMPLATE";
	$this->doca["B swmns-DCFM"]="SWMS - DCFM BLANK TEMPLATE";
	$this->doca["work method"]="Technician Job Process Summary";
	$this->doca["PTCD"]="COMP - 0002 (PTCD) - DCFM Technician Declaration Form";
	$this->doca["claim"]="contractorClaim";
	#$this->doca["quote procedures"]="standardprocedure";

	##extras from matt
	$this->doca["Safety & Action"]="Form - 004 Safety Identifications - Action Sheet Check Sheet -DCFM";
	$this->doca["Hazard Aus Post"]="Form - 004-1 -Hazard Prompt Sheet UGL -Aust Post";
	$this->doca["Hazard UGL"]="Form - 004-2 -Hazard Prompt Sheet UGL";
	$this->doca["Hazard Take 5 Caltex"]="Form - 004-3 -Take5 Hazard Assessment Form -Caltex";
	$this->doca["Hazard Five D"]="Form - 004-4 -JSEA Five-D";

}

function getSafetyComplianceProgress($jobid, $rfqno, $poref)
{
  //get job ids we want to look at
  $sql = 'SELECT * FROM job_document_required WHERE jobid = '.mysql_real_escape_string($jobid);
  
  //ANK 2015-10-14 changed condition to rfq and not poref as docs were not appearing on ob card for poref with jrq set.
  if($rfqno && ! $poref){
  	$sql .= ' AND rfqno = '.mysql_real_escape_string($rfqno);
  }
  if($poref){
  	$sql .= ' AND poref = '.mysql_real_escape_string($poref);
  }
  $result = mysql_query($sql);
  $this->logIt("progress: ".$sql);
  if(mysql_num_rows($result)){
	$required_start_received = 0;
	$required_start_count = 0;
	$required_start_progress = 0;
	$required_complete_received = 0;
	$required_complete_count = 0;
	$required_complete_progress = 0;
	while($row = mysql_fetch_array($result)){
		 #$this->logIt("row: ".print_r($row,1));
	  	if($row['required_start']){
	      $required_start_count++;
	      if($row['received']){
	        $required_start_received++;
                #ANK - move outside of loop for correct calculation
	        #$required_start_progress = 100*($required_start_received/$required_start_count);
	      }
	    }
	    if($row['required_complete']){
	      $required_complete_count++;
	      if($row['received']){
	        $required_complete_received++;
                #ANK - move outside of loop for correct calculation
	        #$required_complete_progress = 100*($required_complete_received/$required_complete_count);
	      }
	    }
	}
        if ($required_start_count != 0){
            //ANK - now counting recieved docs from docmatch as job_document_required doesn't get updated until all scanned in.
            $sql = 'SELECT max(rfq_docs_rcvd) as rcv_count from docmatch WHERE jobid = '.mysql_real_escape_string($jobid);
            $sql .= ' AND xrefid = '.mysql_real_escape_string($rfqno);
            $result = mysql_query($sql);
            while($row = mysql_fetch_array($result)){
                $required_start_received = $row['rcv_count'];
            }

            //End ANK
			//ANK 2.10.2013 Add rounding function
           $required_start_progress = round(100*($required_start_received/$required_start_count));
           if ($required_start_progress>100){
             $required_start_progress=100;
             }
         }
        else {
           //ANK Set to 100 if no start docs required.   2.10.2013 Changed to set to 0
           #$required_start_progress = 100;
		   $required_start_progress = 0;
        }

        if ($required_complete_count != 0){
		//ANK 2.10.2013 Add rounding function
           $required_complete_progress = round(100*($required_complete_received/$required_complete_count));
            if ($required_complete_progress>100){
		   		    $required_complete_progess=100;
		   }
        }


	return array('start' => $required_start_progress, 'complete' => $required_complete_progress, 'startreq' => $required_start_count);
  }
}

function getSafetyComplianceProgress_old($jobid, $rfqno, $poref)
{
  //get job ids we want to look at
  $sql = 'SELECT * FROM job_document_required WHERE jobid = '.mysql_real_escape_string($jobid);
  if($rfqno){
  	$sql .= ' AND rfqno = '.mysql_real_escape_string($rfqno);
  }
  if($poref){
  	$sql .= ' AND poref = '.mysql_real_escape_string($poref);
  }
  $result = mysql_query($sql);
  $this->logIt("progress: ".$sql);
  if(mysql_num_rows($result)){
	$required_start_received = 0;
	$required_start_count = 0;
	$required_start_progress = 0;
	$required_complete_received = 0;
	$required_complete_count = 0;
	$required_complete_progress = 0;
	while($row = mysql_fetch_array($result)){
	  	if($row['required_start']){
	      $required_start_count++;
	      if($row['received']){
	        $required_start_received++;
	        $required_start_progress = 100*($required_start_received/$required_start_count);

	      }
	    }
	    if($row['required_complete']){
	      $required_complete_count++;
	      if($row['received']){
	        $required_complete_received++;
	        $required_complete_progress = 100*($required_complete_received/$required_complete_count);
	      }
	    }
	}
	 if ($required_start_progress>100){
			$required_start_progress=100;
	}
	return array('start' => $required_start_progress, 'complete' => $required_complete_progress);
  }
}

function displayExternalLog($jobid){
	#JRQ log split for access by jobview pagination 22.5.13
	#return;
	###rfq
	//$qupdate = "update rfq set projectedcost"
	error_reporting(0);
	$_SESSION["ErrorReport"] = false;
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/phoenix/rfq.class.php");	
	$orfq = new rfq(AppType::JobTracker,$_SESSION['userid']);
	$orfq->updaterfqprojectedcost($jobid);
	$this->logit("After updaterfqprojectedcost");
	
	error_reporting(0);
			
	
	
	
	$q = "select rfqno,'rfq' as atype,rfqdate as date,qval,margin,actualcost,supplierid,c.companyname,r.userid,
	responded,responsedate,replied,replydate,apply,closed,supplierqref,accepted as accept,defaultrfq,costtodate,availablecost,budgetstatus,projectedcost
	from rfq as r
	left outer join customer as c
	on r.supplierid=c.customerid
	where jobid=$jobid";
	$tf = kda("rfqno,atype,companyname,date,userid,responded,responsedate,replied,replydate,supplierqref,qval,margin,apply,accept,adjust,defaultrfq,closed,costtodate,availablecost,budgetstatus,actualcost,projectedcost,delete,supplierid,startprogress,startreq");
	$tf["replied"] = "Quoted";
	$tf["qval"] = "$ Quote (Ex GST)";
	$tf["margin"] = "$ Margin";
	$tf["supplierqref"] = "Qte No.";
	$tf["responsedate"] = "Resp.Dt.";
	$tf["replydate"] = "Qte.Dt.";
	$tf["startprogress"] = "Progress";
	$tf["defaultrfq"] = "JRQ Default";
	$tf["costtodate"] = "$ Cost to Date";
	$tf["availablecost"] = "$ Available Budget";
	$tf["budgetstatus"] = "Budget Status";
	$tf["projectedcost"] = "$ Projected Cost";
	$tf["actualcost"] = "$ Actual Cost";
	
	$da = iqa($q,$tf);

	$aNewArray = array();

	foreach($da as $row){
		#$this->logIt("get rfq progress");
		$progress = $this->getSafetyComplianceProgress($jobid, $row['rfqno'], $row['poref']);
		if($progress){
			$row['startprogress'] = $progress['start'].'% start';
			$row['startreq'] = $progress['startreq'];
		}
		unset($progress);
		$aNewArray[] = $row;
	}
	$da = $aNewArray;
	unset($aNewArray);

	$ist=new divTable();
	$ist->tableidx=$this->tablestyle;


	$fn="atype";

	$fn="atype";

	# breaking bad jquery
	#		$ist->multilink[$fn][1]=array("ajaxHrefIconMulti");
	#		$ist->ajaxDataM[$fn][1]=kda("rfqno");
	#		$ist->ajaxStaticDataM[$fn][1]=kda("divid=dialogRfqMailer!I!tn=rfqMailer!I!title=RFQ!I!jobid=$jobid");
	#		$ist->ajaxFnameM[$fn][1]="jqUiModalExisting";
	#		$ist->iconM[$fn][1]="eml";

	#go via customised function first to build PDF
	$params.="!I!fid=dcfmrfq07J!I!prefix=rfq!I!folder=rfq";
	$ist->ajaxStaticDataM[$fn][1]=kda("!I!divid=dialogRfqMailer!I!tn=rfqMailer!I!title=Rfq!I!jobid=$jobid$params");
	$ist->ajaxFnameM[$fn][1]="jrqPdfEmail";

	$ist->colClass["startprogress"] = "progress clue";

	//Add cluetip icon on progress column to show safety compliance progress
	$ist->multilink["startprogress"][1]=array("hrefClueTipMulti");
	$ist->ajaxFnameM["startprogress"][1]="previewSafety.php?jobid=$jobid";
	$ist->ajaxDataM["startprogress"][1]=kda("rfqno");
	$ist->iconM["startprogress"][1]="news";
	$ist->iconM["startprogress"]["iconTrails"]=true;
	$ist->noLinkIfBlank["startprogress"][1]=true;

	/*
		#superceded by UI email
		$ist->multilink["atype"][2]=array("ajaxHrefIconMulti");
			$caption="Mail&nbsp;Job&nbsp;Note";
			$ist->multilink["atype"][2]=array("ajaxHrefIconMulti");
			$ist->ajaxDataM["atype"][2]=kda("rfqno,supplierid");

			$ist->multilink["atype"][2]=array("ajaxHrefIconMulti");
			$url="../customscript/jobAttachments.php?rn=1&doctype=rfq&jobid=$jobid&nid=";
			$imageGroup="x";
			$ist->ajaxStaticDataM[$fn][2]=kda("$caption,$imageGroup,$url");
			$ist->ajaxFnameM[$fn][2]="thickBoxDual";
			$ist->iconM[$fn][2]="p";
	*/

	#more breaking bad
	/*
	$ist->multilink["atype"][3]=array("ajaxHrefIconMulti");
			$ist->ajaxFnameM[$fn][3]="idoc";;
		    $ist->ajaxStaticDataM[$fn][3]=kda("dcfmrfq07J");;
		  	$ist->ajaxDataM[$fn][3]=kda("rfqno");
		    $ist->iconM[$fn][3]="e";
		    $ist->iconM[$fn][3]="p";
	*/

	$ist->multilink[$fn][1]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][1]="rfqEml";
	$ist->input_fields["rfqno"]="hpd";
	$ist->iconM[$fn][1]="eml";

	#$ist->multilink[$fn][2]=array("ajaxHrefIconMultiJq");
	#$ist->colClassM[$fn][2]="rfqPdf";
	#$ist->input_fields["rfqno"]="hpd";
	#$ist->iconM[$fn][2]="pdf";

	$ist->multilink[$fn][2]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][2]="rfqDoc";
	$ist->input_fields["rfqno"]="hpd";
	$ist->iconM[$fn][2]="p";

    $ist->multilink[$fn][3]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][3]="rfqCompliance";
	$ist->input_fields["rfqno"]="hpd";
	$ist->iconM[$fn][3]="v";

    $ist->multilink[$fn][4]=array("jqButton");
	$ist->colClassM[$fn][4]="rfqEml";
	$ist->buttonClass[$fn]="infoRq";
	$ist->input_fields["rfqno"]="hpd";
	$ist->jbuttondesc[$fn]="RFQ Info Rq.";

	$fn="companyname";
	$ist->multilink[$fn][1]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][1]="companyEdit";
	$ist->input_fields["supplierid"]="hpd";
	$ist->iconM[$fn][1]="v";


	$ist->noCheckAll["responded"]=true;
	$ist->noCheckAll["replied"]=true;

	#superceded by jquery
	#$ist->input_jcall["responded"]="onclick=rfqResponded";
	$ist->input_fields["responded"]="checkboxJq";
	$ist->ajaxData["responded"]=kda("rfqno");
	$fn="responded";
	$ist->ckboxClass[$fn]=$fn;

	#superceded by jquery
	#$ist->input_jcall["replied"]="onclick=rfqReceived";
	$ist->input_fields["replied"]="checkboxJq";
	$ist->ajaxData["replied"]=kda("rfqno");
	$fn="replied";
	$ist->ckboxClass[$fn]=$fn;

	#ANK lock cells after quote sent
	$qstatus = singlevalue("jobs","quotestatus","jobid",$jobid);
	$rostatus = ($qstatus != "pending_submission") ? "readonlytext" : "text";
	$chkstatus = ($qstatus != "pending_submission") ? "tickfield" : "checkboxJq";
	

	#superceded by jquery
	#$ist->input_jcall["apply"]="onclick=rfqApply";
	#$ist->ajaxData["apply"]=kda("rfqno");
	$fn="apply";
	$ist->input_fields[$fn]= $chkstatus;
	$ist->ckboxClass[$fn]="rfqapply";
	$ist->noCheckAll["apply"]=true;

	#superceded by jquery
	#$ist->input_jcall["accept"]="onclick=rfqAccept";
	#$ist->ajaxData["accept"]=kda("rfqno");
	

	
	
	$ist->input_fields["accept"]= $chkstatus;
	$fn="accept";
	$ist->ckboxClass[$fn]="rfqaccept";
	$ist->noCheckAll[$fn]=true;

	$fn="adjust";
	$ist->linka[$fn]=array("ajaxButton"=>"hx");
	$ist->jbuttondesc[$fn]="Adjust";
	$ist->buttonClass[$fn]="adjust";


	#superceded by jquery
	#$ist->input_jcall["delete"]="onclick=rfqDelete";
	#$ist->ajaxData["delete"]=kda("rfqno");
	$ist->input_fields["delete"]="checkboxJq";
	$fn="delete";
	$ist->ckboxClass[$fn]="rfqdel";
	$ist->noCheckAll[$fn]=true;

		

	#saveRfqval not a function??
	$ist->input_fields["qval"]=$rostatus;
	$ist->colClassText["qval"]="qvalc";
	#$ist->input_jcall["qval"]="onchange=saveRfqval";


	
	$ist->input_fields["margin"]= $rostatus;
	$ist->colClassText["margin"]="marginc";

	#TODO - Remove
	$ist->input_fields["qval"]="text";
	$ist->input_fields["margin"]="text";
	$ist->input_fields["accept"]= "checkboxJq";	
	$ist->input_fields["apply"]="checkboxJq";
	
	#$ist->input_jcall["margin"]="onchange=saveRfqval";

	$ist->input_fields["supplierqref"]="text";
	$ist->colClassText["supplierqref"]="sqref";

	#superceded by jquery
	#$ist->input_jcall["closed"]="onclick=rfqClose";
	#$ist->ajaxData["closed"]=kda("rfqno");
	$ist->input_fields["closed"]="checkboxJq";
	$fn="closed";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="rfqclose";
	$ist->noCheckAll[$fn]=true;

	$ist->entrymode=true;

	$ist->input_fields["actualcost"]="text";
	$ist->colClassText["actualcost"]="acost";

	$ist->total_fields=kda("qval,margin,actualcost,projectedcost");

	$jax=$this->ja["attachments"];
	$ata=unserialize($jax);

	$title="RFQ Action Log";

	/*
	#now handled via thickbox jobAttachments
	$title="RFQ Action Log   <i>Optional Docs:</i> <form name=attachmentsdcfmrfq07J>";
	$this->optionalDocs();

	foreach($this->doca as $lab=>$docn){
		$flag=$ata['dcfmrfq07J'][$docn];
		unset($ckx);
		if($flag) $ckx="checked";
		$title.="<input type=checkbox name=dcfmrfq07J$docn $ckx onclick=saveAttach('$docn','dcfmrfq07J');><i>$lab &nbsp;</i>";
	}
	*/

	/*$contrx.="<br><b>Dynamic Forms</b>";
	#load contractor forms.
	$dtype="dcfmrfq07J";
	$contrx.=$this->loadContractorForms($dtype);
	$title.=$contrx;
	$title.="</form>";
	*/

	$ist->tableid="rfqList";

	if(sizeof($da)>0)	$nx=$ist->inf_sortable($tf,$da,$title,null,null,true);

//	tfw("dexl.txt",$nx,true);

	##auto update safety for flagged suppliers
	/* ANK 29.11.2013 Disabled this as it is taken care of elsewhere in reminders and overwrites the safety flag
	$safeq="update purchaseorders as p	left outer join customer as c
	on p.supplierid=c.customerid set p.safety='on'
	where c.customtext3='on'";
	mysql_query($safeq); */
	
	$nx.=$this->rfqSummary($jobid);
	
	// ANK modal and js for RFQ Adjustment
	$nx.="<div id=myModal></div>"; 
    $this->jcallA[]=$this->rfqAdjust($jobid);
	
	
	$nx.=$this->jrqLog($jobid);
	return $nx;

}
function rfqAdjust($jobid){
	$html="
		$('.adjust').click(function(){
		var rfqno = $(this).closest('tr').children('td:first').text().trim();
		console.log('rfq',rfqno);
		
		var jdata = new Object();
    	jdata['rfqno'] = rfqno;
    	jdata['jobid'] = $jobid;
   		jjson = JSON.stringify(jdata);

		var url = '../../common/phoenix/views/rfqadjust.php?&jdata='+jjson;
		$('#myModal').dialog(
			{
			height: 600,
			width: 620,
			title: 'RFQ Adjustments',
			modal: true,
			open: function(event,ui){
				//$('#myModal').load('../../common/phoenix/bl/ajax/ajax-workflow.php?mode=rfqadjust&jdata='+jjson);
				$('#myModal').load(url);
			},
			buttons: { 'OK': function(){ $(this).dialog('close');   }},
			
			close:function(event, ui) { $('#myModal').html(''); }
			}
		);
	});";
	
	return $html;
	
}
function rfqSummary($jobid){
	
	#ANK Add Budget Summary
	$q = "SELECT jrqcostsunallocatedtorfq as unallocated,jrqcostsallocatedtorfq as allocated , jrqcostsunallocatedtorfq + jrqcostsallocatedtorfq as totaljrqcosts,rfqbudget,availablebudget,rfqbudgetposition FROM jobs WHERE jobid=$jobid";
	$tfr = kda("unallocated,allocated,totaljrqcosts,rfqbudget,availablebudget,rfqbudgetposition");
	$dabudget = iqa($q,$tfr);
	$tfr['unallocated'] = " $ Unallocated JRQ";
	$tfr['allocated'] = " $ Allocated JRQ";
	$tfr['totaljrqcosts'] = "$ Total JRQ Costs";
	$tfr['rfqbudget'] = "$ RFQ Budget";
	$tfr['availablebudget'] = "Budget Remaining";
	$tfr['rfqbudgetposition'] = "Budget Position";
	
	$ist = new divTable();
	$ist->tableid="rfqSummary";
	
	$ist->paginate=false;
	$ist->entrymode=true;
	
	$tblstatus=$ist->inf_sortable($tfr,$dabudget,"RFQ Budget Summary",null,null,true);
	
	echo $tblstatus;	
	
	#ANK Add RFQ combo-box
	$q = "SELECT rfqno,supplierid,companyname FROM rfq INNER JOIN customer c ON rfq.supplierid=c.customerid WHERE jobid = $jobid ORDER BY companyname";
	$tfq = kda("rfqno,supplierid,companyname");
	$daq = iqa($q,$tfq);
	
	$cbo = $tblstatus."<br>Select RFQ to assign to JRQ: <select id='rfqassign'>";
	
	foreach($daq as $i=>$row){
		$rfq = $row['rfqno'];
		$cbo.="<option value='$rfq'>".$row['companyname']." ($rfq)</option>";
	}
	$cbo = $cbo."</select>
	<button id='assignrfq'>Assign</button>
	<button id='unassignrfq'>Unassign</button>
	<button id='setdefault'>Set as Default</button>
	<button id='updaterfqcosts'>Update RFQ Costs</button>
	<button id='fixcheckbox'>Fix Checkbox</button>
	<button id='zerojrqonly'>Show $0 JRQ Only</button>
	<button id='showalljrq'>Show All JRQs</button>
	<button id='updaterfqprojcosts'>Update RFQ Projected costs</button>
	
	<br>";
	
	$jtest = "$('#fixcheckbox').click(function(){
		var selected = $('#jrqList tbody tr');
		var len=$('#jrqList tr').length;
		//console.log('len:',len);
		var id='0';
		var cnt=0;

		selected.each(function(){
   			var row = $(this).closest('tr');
   			var poref = $.trim(row.find('td:eq(0)').html());
   			var td1 = $(row.find('td:eq(1)'));
			td1.attr('counter',cnt++);
			if (td1.html().trim().length == 0){
				//console.log('poref:',poref,'id:',id);
				var thisid = parseInt(id.replace('assignrfq',''));
				thisid = 'assignrfq' +(thisid+1).toString();
				var qt = String.fromCharCode(34);
				var chk = '<input id = ' + qt + thisid + qt + 'class = ' + qt + 'assignrfq' + qt + ' type = ' + qt + 'checkbox' + qt + 'name=' + qt + 'assignrfq' + qt + '></input>';
				td1.html(chk);
			} else {
				id = td1.attr('counter');
			}
   			
		});
	})";
	$this->jcallA[]=$jtest;
	
	$jzero = "$('#zerojrqonly').click(function(){
		console.log('jzero');
		var selected = $('#jrqList tbody tr');
		var len=$('#jrqList tr').length;
		console.log('len:',len);

		selected.each(function(){
   			var row = $(this).closest('tr');
   			var cost = $.trim(row.find('td:eq(23) input').val());
			if (cost>0){
  			 console.log('cost:',cost);
			 row.hide();	
			}
			else {
			  var cost2 = $.trim(row.find('td:eq(23)').text());
			  console.log('cost2:',cost2);
			  if (cost2 != ''){
				row.hide();
			  }
			}
		});
			
	})";
	$this->jcallA[]=$jzero;
	
	$jshowall = "$('#showalljrq').click(function(){
		console.log('jzero');
		var selected = $('#jrqList tbody tr');
		var len=$('#jrqList tr').length;
		console.log('len:',len);

		selected.each(function(){
   			var row = $(this).closest('tr');
   		    row.show();	
		});
			
	})";
	$this->jcallA[]=$jshowall;
	
		
	$jdefrfq = "$('#setdefault').click(function(){
		var rfq = $('#rfqassign').val();
		if(!confirm('Ready to assign RFQ ' + rfq + ' as the Default RFQ for labour and materials on this job?')) return false;
 		
		var url = '../../infbase/ajax/ajaxpostBack.php';
  		data ='params=!I!url=assignrfq!I!mode=assigndefaultrfq!I!rfq='+rfq+'!I!jobid='+$jobid;

  		$.ajax({
      		url: url,
      		type: 'POST',
      		data: data,
      		async: false,
      		cache: false,
      		dataType: 'text',
      		success: function(data){
 				alert('Default RFQ assigned.');
				jobview($jobid);
				
        	}
  		});	
		
	})";
	
	$this->jcallA[]=$jdefrfq;
	
	$juprojrfq = "$('#updaterfqcosts').click(function(){
		if(!confirm('Ready to update costs on RFQ lines on this job?')) return false;
 		
		var url = '../../infbase/ajax/ajaxpostBack.php';
  		data ='params=!I!url=assignrfq!I!mode=updaterfqcosts!I!jobid='+$jobid;

  		$.ajax({
      		url: url,
      		type: 'POST',
      		data: data,
      		async: false,
      		cache: false,
      		dataType: 'text',
      		success: function(data){
 				alert('RFQ Costs updated.');
				jobview($jobid);
				
        	}
  		});		
	
	})";
	
	$this->jcallA[]=$juprojrfq;
	
	$jucrfq = "$('#updaterfqprojcosts').click(function(){
		if(!confirm('Ready to update projected costs on RFQ lines on this job?')) return false;
 		
		var jdata = new Object();
		jdata['jobid']=$jobid;
		
		var json =JSON.stringify(jdata);
		data = 'mode=updaterfqprojcosts&jdata='+json;
	
		var url = '../../common/phoenix/bl/ajax/ajax-job.php';

		$.ajax({
			url: url,
	    	type: 'post',
	    	data: data,
	    	async: false,
	    	cache: false,
	    	dataType: 'text',
	    	success: function(data) {
	    		result = JSON.parse(data);
	      		alert(result.message);
	      		
				jobview	($jobid);		
 	    	}
		});
		
	})";
	
	$this->jcallA[]=$jucrfq;
	

	$jrfq = "$('#assignrfq').click(function(){
		var rfq = $('#rfqassign').val();
		var len=$('#jrqList input[name=assignrfq]:checked').length;
		if(len==0) {
			alert('No JRQ lines selected.');
			return false;
		}
		if(!confirm('Ready to assign RFQ ' + rfq + ' to ' +len+' selected JRQ lines?')) return false;
  		var selected = $('#jrqList input[name=assignrfq]:checked');

  		var postring = '';

  		selected.each(function(){
   			var row = $(this).closest('tr');
   			var poref = $.trim(row.find('td:eq(0)').html());
   			var rfqno = $.trim(row.find('td:eq(2)').html());
 			postring = postring + poref + ',';
 		});
 		var porefs = '(' + postring.substring(0,postring.length-1) + ')';
 
   		var url = '../../infbase/ajax/ajaxpostBack.php';
  		data ='params=!I!url=assignrfq!I!mode=assignrfq!I!rfq='+rfq+'!I!porefs='+porefs;

  		$.ajax({
      		url: url,
      		type: 'POST',
      		data: data,
      		async: false,
      		cache: false,
      		dataType: 'text',
      		success: function(data){
 				alert('JRQ lines updated.');
				jobview($jobid);
				
        	}
  		});
		
		
	})";
	$this->jcallA[]=$jrfq;
	
	$jurfq = "$('#unassignrfq').click(function(){
		var len=$('#jrqList input[name=assignrfq]:checked').length;
		if(len==0) {
			alert('No JRQ lines selected.');
			return false;
		}
		if(!confirm('Ready to clear the RFQ assignments on ' +len+' selected JRQ lines?')) return false;
  		var selected = $('#jrqList input[name=assignrfq]:checked');

  		var postring = '';

  		selected.each(function(){
   			var row = $(this).closest('tr');
   			var poref = $.trim(row.find('td:eq(0)').html());
 			postring = postring + poref + ',';
		});
 		var porefs = '(' + postring.substring(0,postring.length-1) + ')';
 
   		var url = '../../infbase/ajax/ajaxpostBack.php';
  		data ='params=!I!url=assignrfq!I!mode=unassignrfq!I!porefs='+porefs;

  		$.ajax({
      		url: url,
      		type: 'POST',
      		data: data,
      		async: false,
      		cache: false,
      		dataType: 'text',
      		success: function(data){
 				alert('JRQ lines updated.');
				jobview($jobid);
				
        	}
  		});
		
		
	})";
	$this->jcallA[]=$jurfq;
	
   return "<div id='rfqsummary'>$cbo</div>";	
	
}


function jrqLog($jobid){
	###po JRQ lines start here
	//Check for Compliance Job on customerid 6519
	$isComplianceJob = false;
	$custid = singlevalue("jobs","customerid","jobid",$jobid);
	if($custid=='6519'){
		$isComplianceJob = true;
	} 
	
	$siteline1 = singlevalue("jobs","siteline1","jobid",$jobid);
	$dcfmjob = false;
	if (str_replace("DCFM", "", $siteline1) != $siteline1){
		$dcfmjob = true;
	}
	$jdescription = singlevalue("jobs","custordref2","jobid",$jobid);
	$prelimjob = false;
	if (strtolower($jdescription) == "preliminary costs") $prelimjob = true;
	
	$nc = singlevalue("jobs","IFNULL(nonchargeable,'')","jobid",$jobid);
	$nonchargeable = false;
	if($nc=='on') $nonchargeable = true;
		
	if($isComplianceJob){
		$cjx = "c.shipstate as state,c.shipsuburb as suburb,c.industrysector as primarytrade,";
	} else{
		$cjx = "";
	}
	
	if($this->paramsA["fval"]!=""){
		$fval=$this->paramsA["fval"];
		$condx=" and c.companyname like '%$fval%'";
		#echo "condx: $condx";
	}

	if($this->paramsA['jrq0']=="true"){
		$condx=" and cost=0";
	}
		
	$pq="select $cjx r.poref,'jrq' as atype,podate as date,r.apptid,reportdate,r.supplierid,c.companyname,r.rfqno,rq.qval,
	invoiceref,r.unapprovedlabourcost,r.labourcost,r.materialcost,r.oncosts,r.cost,completed as pocompleted,completedate as pocompletedate,r.responded as poresponded,
	r.responsedate,r.acceptdate,exceed,safety,r.varianceValue,r.invnotreqd,r.ssnotreqd,r.rule_code,r.invoicejobid,r.custinvoiceno,r.invoice_rulecode,r.invoice_rulecode_date,
	r.materialjobid,r.custordref,r.userid,r.accepted,glchartid,invoicedate,paydate,r.syncdate,0 as syncbase,j.internalqlimit,linkedpo,polimit,ps.name as etpstatus,subworksid,subworks,
	isinvrejected as invreject,isinvrejected,c.custstatus
	from purchaseorders as r
	left outer join customer as c
	on r.supplierid=c.customerid
	left outer join rfq as rq
	on r.rfqno=rq.rfqno
	left outer join jobs as j
	on r.jobid=j.jobid
	left outer join purchaseorder_status ps
	on r.statusid=ps.id
	where r.jobid=$jobid
	$condx
	order by poref desc
	";

	$ist=new divTable();
	/*pagination usage*/
	$perPage=25;
	$pageNo=$this->paramsA["np"]>0?$this->paramsA["np"]:"0";
	$offset=$pageNo>0?($pageNo-1)*$perPage:"";
	$ist->offset=$pageNo;
	$ist->paginate=true;
	$ist->pagRPP=$perPage;
	$ist->noLimitQ=$pq;
	$ist->offerFullQueryExtract=true;
	$ist->fullQuerySimpleXLFields=true;
	$ist->pageLinkUrl="jobview";
	$ist->noCleanUpSerial=true;
	$ist->pageLinkParams="!I!jobid=$jobid!I!mode=jrqpage!I!params=1!I!fval=$fval";
	$ist->pageLinkTargetDiv="jrqlog";
	$ist->pageLinkFunc="ajl";
	$limitx=$offset!=""?" limit $offset,$perPage":" limit $perPage";//to be included in query
	#$ist->paginateAppendQstring=true;//optional for non 'ajl' type links
	$pq.=$limitx;
	
	//ANK line 5310 additional fields invnotreqd,ssnotreqd,rule_code
	#$tf=kda("supplierid,poref,atype,companyname,date,poresponded,responsedate,userid,accepted,acceptdate,invoiceref,invoicedate,paydate,cost,glchartid,pocompleted,pocompletedate,exceed,safety,podelete,syncdate,syncbase");
	$tf=kda("supplierid,poref,assignrfq,rfqno,qval,atype,inductions,companyname,date,poresponded,responsedate,userid,apptid,subworksid,subworks,etpstatus,accepted,acceptdate,linkedpo,invoiceref,custordref,invoicedate,paydate,reportdate,unapprovedlabourcost,labourcost,materialcost,oncosts,cost,polimit,varianceValue,glchartid,pocompleted,pocompletedate,taskcompleted,exceed,invreject,safety,checkdocs,progress,sendreminder,invnotreqd,ssnotreqd,rule_code,podelete,custinvoiceno,invoicejobid,invoice_rulecode,syncdate,syncbase,internalqlimit,startreq,custstatus");
	
	//If compliance Job, display additional fields
	if ($isComplianceJob){
	$tf=kda("supplierid,poref,assignrfq,rfqno,qval,atype,companyname,suburb,state,primarytrade,date,poresponded,responsedate,userid,accepted,acceptdate,linkedpo,invoiceref,custordref,invoicedate,paydate,reportdate,labourcost,materialcost,oncosts,cost,varianceValue,glchartid,pocompleted,pocompletedate,taskcompleted,exceed,safety,checkdocs,progress,invnotreqd,ssnotreqd,rule_code,podelete,custinvoiceno,invoicejobid,invoice_rulecode,syncdate,syncbase,internalqlimit,startreq");
	}
	
		
	
	#$tf["poresponded"]="Responded";
	$tf["polimit"] = "$ Limit";
	$tf["etpstatus"] = "JRQ Status";
	$tf["subworksid"] = "Sub Works";
	$tf["cost"]="$ Cost (Ex GST)";
	$tf["varianceValue"]="$ Var Req.";
	$tf["podelete"]="Delete";
	$tf["responsedate"]="Resp.Dt.";
	$tf["acceptdate"]="Accept Dt.";
	$tf["exceed"]="$ > Limit ";
	$tf["invreject"] = "Supplier invoice";
	$tf["safety"]="Docs Complete";
	$tf["taskcomplete"]="Task Complete";
	#$tf["materialjobid"]="Material Job";
	$tf["custordref"]="Material Job Ord";
	$tf["pocompletedate"]="Comp. Dt.";

	$tf["ssnotreqd"] = "Docs not reqd.";
	$tf["invnotreqd"] = "Invoice not reqd.";
	$tf["rule_code"] = "Contract Rule";
	$tf["checkdocs"] = "Check Docs";
	$tf["updatedocs"] = "Update Docs";
	$tf["assignrfq"] = "Assign RFQ";
	$tf["sendreminder"] = "Send Reminder";
	
	$tf["unapprovedlabourcost"] = "Unapp'vd Lab Cost";
	$tf["linkedpo"] = "Linked JRQ";
	
	$tf["custinvoiceno"] = "Invoiced on";
	$tf["invoicejobid"] = "Invoice job";
	$tf["invoice_rulecode"] = "Invoice rule";
	$tf["custstatus"] = "Supplier Status";
		
	unset($tf["poresponded"]);
	unset($tf["responsedate"]);

	$pa=iqa($pq,$tf);
	if(isset($pa)){
		foreach($pa as $row){
			$qlimit=$row["internalqlimit"];
			$progress = $this->getSafetyComplianceProgress($jobid, $row['rfqno'], $row['poref']);
			if($progress){
				$row['progress'] = $progress['start'].'% start<br />'.$progress['complete'].'% complete';
				$row['startreq'] = $progress['startreq'];

			}

			unset($progress);
			
			if ($row['invreject'] == "1") {
				$row['invreject']="<button name='invreject' value=1 class='invreject'>Accept Invoice</button>";		
			} else {
				$row['invreject']="<button name='invreject' value=0 class='invreject'>Reject Invoice</button>";		
			}
			
			$aNewArray[] = $row;
			
		}
	}

	$pa = $aNewArray;
	unset($aNewArray);

	unset($tf["internalqlimit"]);

	#$ist=new divTable();
	$ist->tableidx=$this->tablestyle;
	$ist->datefields=kda("date,reportdate,responsedate,acceptdate,pocompletedate,syncdate");

	$ist->colClass["progress"] = "progress clue";
	$ist->colClass["startreq"]="startreq";
	$ist->colClass["custstatus"]="custstatus";

	//Add cluetip icon on progress column to show safety compliance progress
	$ist->multilink["progress"][1]=array("hrefClueTipMulti");
	$ist->ajaxFnameM["progress"][1]="previewSafety.php?mode=jrq&jobid=$jobid";
	$ist->ajaxDataM["progress"][1]=kda("poref");
	$ist->iconM["progress"][1]="news";
	$ist->iconM["progress"]["iconTrails"]=true;
	$ist->noLinkIfBlank["progress"][1]=true;

	$fn="companyname";
	$ist->multilink[$fn][1]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][1]="companyEdit";
	$ist->input_fields["supplierid"]="hpd";
	$ist->iconM[$fn][1]="v";

	//if ($jobid=='418464'){
	$fn="invoiceref";
	
	$qs = "SELECT count(*) as ucount FROM usersecurity us INNER JOIN usersecurityfunction usf ON us.functionid=usf.functionid
	WHERE functionname = 'jrqcredit' AND hasaccess=1 and userid = '".$_SESSION['userid']."'";
	
	$ucount=iqasrf($qs,"ucount");
	if ($ucount==1){
	
	$ist->multilink[$fn][1]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][1]="jrqcredit";
	$ist->iconM[$fn][1]="e";
	
	$cnjx="$('.jrqcredit').click(function(){
		var row=$(this).closest('tr');
		var poref= row.find('input[name=poref]').val();
		var linkedporef= row.find('.linkedpo').text();
		var syncdate = row.find('.syncdate').text();
		
		var invref = row.find('input[name=invoiceref]').val();
		if (invref == ''){
			alert('You cannot raise a credit on a JRQ that has no invoice.');
			return;
		}
		console.log('linkedpo:', linkedporef);
		if(linkedporef.trim() != ''){
			alert('You cannot raise a credit on a credit.');
			return;
		}
		console.log('syncdate:', syncdate.length);
		if(syncdate.length < 12){
			alert('JRQ has not been synced yet. Adjust the JRQ rather than raising a credit.');
			return;
		}

		jmg('jrqcredit','!I!poref='+poref);
	});";
	$this->jcallA[]=$cnjx;
	}
	
	//}

/*	$ist->multilink["progress"][0]=array("hrefClueTipMulti");
	$ist->ajaxFnameM["progress"][0]="previewSafety.php?mode=jrq&jobid=$jobid";
	$ist->ajaxDataM["progress"][0]=kda("poref");
	$ist->iconM["progress"][0]="jb";
	#$ist->iconM["progress"]["iconTrails"]=true;
	#$ist->noLinkIfBlank["progress"][1]=true;
*/


	$stylecall="style=color:white;background-color:#999;";
	$ist->cssPreAnalyseNeeded=true;

	$ist->cssEntireRow["syncdate"]=true;
	$ist->cssNumericComparison["syncdate"]["left"][]="syncdate";
	$ist->cssNumericComparison["syncdate"]["right"][]="syncbase";
	$ist->cssNumericComparison["syncdate"]["operator"]="<";
	$ist->cssNumericComparison["syncdate"]["stylecall"]=$stylecall;
	$ist->cssCondRowLocking=true;


	$fn="atype";

#breaking bad
#	$ist->multilink[$fn][1]=array("ajaxHrefIconMulti");
#	$ist->ajaxDataM[$fn][1]=kda("poref");
	#$ist->ajaxFnameM[$fn][1]="jqUiModalExisting";

	$ist->multilink[$fn][1]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][1]="jrqEml";
	$ist->input_fields["poref"]="hpd";
	$ist->iconM[$fn][1]="eml";

	#$ist->multilink[$fn][2]=array("ajaxHrefIconMultiJq");
	#$ist->colClassM[$fn][2]="jrqPdf";
	#$ist->input_fields["rfqno"]="hpd";
	#$ist->iconM[$fn][2]="pdf";

	$ist->multilink[$fn][2]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][2]="jrqDoc";
	$ist->input_fields["jrqno"]="hpd";
	$ist->iconM[$fn][2]="p";

    $ist->multilink[$fn][3]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][3]="jrqCompliance";
	$ist->iconM[$fn][3]="v";

    $ist->multilink[$fn][4]=array("jqButton");
	$ist->colClassM[$fn][4]="rfqEml";
	$ist->buttonClass[$fn]="infoRqJrq";
	$ist->input_fields["rfqno"]="hpd";
	$ist->jbuttondesc[$fn]="Info Rq.";

	$fn="inductions";
	$ist->multilink[$fn][1]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][1]="jrqIndEml";
	$ist->iconM[$fn][1]="eml";

   	$ist->multilink[$fn][2]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][2]="jrqInduction";
	$ist->iconM[$fn][2]="v";

		

	#go via customised function first to build PDF
	$rparams.="!I!fid=dcfmwo07J!I!prefix=jrq!I!folder=jrq";
	$ist->ajaxStaticDataM[$fn][1]=kda("!I!divid=dialogJrqMailer!I!tn=jrqMailer!I!title=Address!I!jobid=$jobid$rparams");
	$ist->ajaxFnameM[$fn][1]="jrqPdfEmail";

	$ist->iconM[$fn][1]="eml";

	$ist->input_fields["subworksid"]="hidden";
	$fn="subworks";
	$ist->multilink[$fn][1]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][1]="jrqsubworks";
	$ist->iconM[$fn][1]="e";

	$swjx="$('.jrqsubworks').click(function(){
		var row=$(this).closest('tr');
		var poref= row.find('input[name=poref]').val();
		var subworksid= row.find('input[name=subworksid]').val();
		console.log(poref,subworksid);
		
		var url = '../../common/phoenix/views/jrqsubworks.php';
   		console.log(url);
    	var params = 'poref=' + poref ;
   		var dn = 'dialogGeneric';
        $('#'+dn).load(url+'?'+params, function(){	      	
    
     		var dlg = $('#' + dn).dialog( {
      		autoOpen:true,
      		resizable: false,
      		modal:true,
      		width: 500,
      		height: 250,
      	
  	   		title: 'Update Sub-works - (PO ref ' + poref +')',
      	});
      	
		
		//dlg.dialog('open');
		
		});
		return false;
		
      		
		
	});";
	$this->jcallA[]=$swjx;


	$supinvjx="$('.invreject').click(function(){
		var row=$(this).closest('tr');
		var poref= row.find('input[name=poref]').val();
		var invref = row.find('input[name=invoiceref]').val();
		var cost = row.find('input[name=cost]').val();
				
		var invreject= row.find('.invreject').val();
		console.log(invreject,invref);	
		
		if (invref==''){
			alert('Supplier invoice details must be entered first. Invoice Ref is missing.');
			return;
		}
		
		if (cost=='0.00'){
			alert('Supplier invoice details must be entered first. Cost must be non-zero.');
			return;
		}
		
		if (invreject==0){
			
		var url = '../../common/phoenix/views/suppinvoice.php';
   		console.log(url);
    	var params = 'poref=' + poref ;
   		var dn = 'dialogGeneric';
		
        $('#'+dn).load(url+'?'+params, function(){	      	
    
     		var dlg = $('#' + dn).dialog( {
      		autoOpen:true,
      		resizable: false,
      		modal:true,
      		width: 700,
      		height: 400,
 	   		title: 'Supplier Invoice - (PO ref ' + poref +')',
      		});
  		});
		return false;
		
		} else {
			
		var url = '../../common/phoenix/views/suppinvoiceaccept.php';
   		console.log(url);
    	var params = 'poref=' + poref ;
   		var dn = 'dialogGeneric';
		
        $('#'+dn).load(url+'?'+params, function(){	      	
    
     		var dlg = $('#' + dn).dialog( {
      		autoOpen:true,
      		resizable: false,
      		modal:true,
      		width: 700,
      		height: 600,
 	   		title: 'Supplier Invoice - (PO ref ' + poref +')',
      		});
  		});
			
			

		}
      		
		
	});";
	$this->jcallA[]=$supinvjx;

	#breaking bad
	#		$ist->multilink["atype"][3]=array("ajaxHrefIconMulti");
	#		$ist->ajaxFnameM[$fn][3]="idoc";;
	#	    $ist->ajaxStaticDataM[$fn][3]=kda("dcfmwo07J");;
	#	  	$ist->ajaxDataM[$fn][3]=kda("poref");
	#	    $ist->iconM[$fn][3]="e";
	#	    $ist->iconM[$fn][3]="p";

	$ist->entrymode=true;
	$ist->input_fields["poref"]="hpd";
	$ist->input_fields["rfqno"]="hpd";
	$ist->input_fields["qval"]="hidden";
	$ist->input_fields["supplierid"]="hidden";

	#superceded by jquery
	#$ist->input_jcall["poresponded"]="onclick=jrqResponded";
	#$ist->input_fields["poresponded"]="checkbox";
	#$ist->ajaxData["poresponded"]=kda("poref");
	#seems not in use
	#$fn="poresponded";
	#$ist->input_fields[$fn]="checkboxJq";
	#$ist->ckboxClass[$fn]="jrqrespond";
	#$ist->noCheckAll[$fn]=true;

	#superceded by jquery
	#$ist->input_jcall["accepted"]="onclick=jrqAccepted";
	#$ist->input_fields["accepted"]="checkbox";
	#$ist->ajaxData["accepted"]=kda("poref");
	$fn="accepted";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="jrqaccept";
	$ist->noCheckAll[$fn]=true;

	#superceded by jquery
	#$ist->input_jcall["pocompleted"]="onclick=jrqCompleted";
	#$ist->input_fields["pocompleted"]="checkbox";

	//ANK disable jrqcomplete
	#$fn="pocompleted";
	#$ist->input_fields[$fn]="checkboxJq";
	#$ist->ckboxClass[$fn]="jrqComplete";
	#$ist->noCheckAll[$fn]=true;


	#$ist->ajaxData["pocompleted"]=kda("poref");
	//End ANK
	$fn="taskcompleted";
	$ist->linka[$fn]=array("ajaxButton"=>"hx");
	#$ist->linka["desc"]=array("ajaxHrefIcon"=>"hx");

	#$ist->ajaxFname[$fn]="jrqTaskCompleted";;
  	#$ist->ajaxData[$fn]=kda("poref,jobid");
	$ist->jbuttondesc["taskcompleted"]="done";
	$ist->buttonClass[$fn]="jTC";

	$ist->colClass["materialjobid"]="mjobid";
	$ist->colClass["custordref"]="coref";
	$ist->colClass["poref"]="poref";
	$ist->colClass["linkedpo"]="linkedpo";
	$ist->colClass["syncdate"]="syncdate";
		
	
	$mjx="$('.coref').click(function(){
		var row=$(this).closest('tr');
		var poref= row.find('input[name=poref]').val();
		var customerid= row.find('input[name=customerid]').val();
		var customerid= $('#customerid').val();
		jmg('materialJobSearch','!I!poref='+poref+'!I!customerid='+customerid);
	});";
	$this->jcallA[]=$mjx;

	$ist->colClass["assignrfq"]="rfqassign";

   	
	#  $ist->iconM[$fn][0]="e";
	#  $ist->iconM[$fn][0]="p";

	/*
		$ist->linkinput_fields["taskcompleted"]="ajaxButton";
		$ist->ajaxData["taskcompleted"]=kda("poref");
		$ist->ajaxFnameM["taskcompleted"]="jrqTaskCompleted";
		$ist->jbuttondesc["taskcompleted"]="done";
	*/

	#superceded by jquery
	#$ist->input_jcall["safety"]="onclick=jrqCheckBoxVal";
	#$ist->input_fields["safety"]="checkbox";
	#$ist->ajaxStaticData["safety"]=kda("safety");
	#$ist->ajaxData["safety"]=kda("poref");

	$fn="safety";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="safety";
	$ist->noCheckAll[$fn]=true;

	#superceded by jquery
	#$ist->input_jcall["exceed"]="onclick=jrqCheckBoxVal";
	#$ist->input_fields["exceed"]="checkbox";
	#$ist->ajaxStaticData["exceed"]=kda("exceed");
	#$ist->ajaxData["exceed"]=kda("poref");

	$fn="exceed";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="safety";
	$ist->noCheckAll[$fn]=true;

	#superceded by jquery
	#$ist->input_jcall["podelete"]="onclick=jrqDelete";
	#$ist->input_fields["podelete"]="checkbox";
	#$ist->colClass["podelete"]="podelete";
	#$ist->ajaxData["podelete"]=kda("poref");

	$fn="podelete";
	/*$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="podel";
	$ist->noCheckAll[$fn]=true;
	*/
	#$ist->input_fields[$fn]="checkboxJq";
	$ist->linka[$fn]=array("ajaxButton"=>"hx");
	$ist->jbuttondesc[$fn]="Delete";
	$ist->buttonClass[$fn]="podel";

	//ANK Doc Upload
	$fn="upload";
	$ist->linka[$fn]=array("ajaxButton"=>"hx");
	$ist->jbuttondesc[$fn]="Upload Docs.";
	$ist->buttonClass[$fn]="upload";

	$fn="checkdocs";
	$ist->linka[$fn]=array("ajaxButton"=>"hx");
	$ist->jbuttondesc[$fn]="Check Docs.";
	$ist->buttonClass[$fn]="checkdocs";

	$fn="sendreminder";
	$ist->linka[$fn]=array("ajaxButton"=>"hx");
	$ist->jbuttondesc[$fn]="Send Reminder";
	$ist->buttonClass[$fn]="sendreminder";


	$fn="updatedocs";
	$ist->linka[$fn]=array("ajaxButton"=>"hx");
	$ist->jbuttondesc[$fn]="Update Docs.";
	$ist->buttonClass[$fn]="updatedocs";

	$fn="assignrfq";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="assignrfq";
	#$ist->noCheckAll[$fn]=true;


	

	//End ANK

	//ANK New fields
	$fn="ssnotreqd";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="ssnotreqd";
	$ist->noCheckAll[$fn]=true;

	$fn="invnotreqd";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="invnotreqd";
	$ist->noCheckAll[$fn]=true;
	//End ANK



	$ist->input_fields["invoiceref"]="text";
	#$ist->input_fields["invoicedate"]="date_ClickCal";
	$ist->input_fields["invoicedate"]="uiDatePicker";
	$fn="invoicedate";
	$ist->dtClass[$fn]="invdate";
	$closeCode="var cn=$(this).attr('name');
   	var row=$(this).closest('tr');
	var cval=row.find('input[name=fakeinvoicedate]').val();
	var poref=row.find('input[name=poref]').val();
   	var data='params=!I!idn=poref!I!idv='+poref+'!I!mode=saveField!I!url=rfqJrqHandler!I!tn=purchaseorders!I!fn=invoicedate!I!fv='+cval;
       	saver(data);
	";


	$closeFx=",onClose:function(){".$closeCode."}";
	#$sugcall="$('.invdate').datepicker({inline: true,dateFormat: 'dd/mm/yy',altField: '.fakeinvoicedate',altFormat: 'yy-mm-dd' $closeFx });";
	#$sugcall="$('.invdate').datepicker({inline: true,dateFormat: 'dd/mm/yy',altFormat: 'yy-mm-dd' $closeFx });";
	#$this->jcallA[]=$sugcall;

	$dpx="$('.invdate').each(function() {
	  $(this).datepicker({
	    inline: true,dateFormat: 'dd/mm/yy',altFormat: 'yy-mm-dd',
	    altField: $(this).closest('tr').find('input[name=fakeinvoicedate]')
	    $closeFx

	  });
	});";
	$this->jcallA[]=$dpx;

	##report date edit
	$ist->input_fields["reportdate"]="uiDatePicker";
	$ist->neverLock[]="reportdate";
	$fn="reportdate";
	$ist->dtClass[$fn]="rdate";
	$closeCode="var cn=$(this).attr('name');
   	var row=$(this).closest('tr');
	var cval=row.find('input[name=fakereportdate]').val();
	var poref=row.find('input[name=poref]').val();
   	var data='params=!I!idn=poref!I!idv='+poref+'!I!mode=saveField!I!url=rfqJrqHandler!I!tn=purchaseorders!I!fn=reportdate!I!fv='+cval;
       	saver(data);
	";
	$closeFx=",onClose:function(){".$closeCode."}";
	#$sugcall="$('.invdate').datepicker({inline: true,dateFormat: 'dd/mm/yy',altField: '.fakeinvoicedate',altFormat: 'yy-mm-dd' $closeFx });";
	#$sugcall="$('.invdate').datepicker({inline: true,dateFormat: 'dd/mm/yy',altFormat: 'yy-mm-dd' $closeFx });";
	#$this->jcallA[]=$sugcall;

	$dpx="$('.rdate').each(function() {
	  $(this).datepicker({
	    inline: true,dateFormat: 'dd/mm/yy',altFormat: 'yy-mm-dd',
	    altField: $(this).closest('tr').find('input[name=fakereportdate]')
	    $closeFx
  	  });
	});";
	$this->jcallA[]=$dpx;

	##



	#$ist->input_fields["paydate"]="date_ClickCal";
	$fn="paydate";
	$ist->dtClass[$fn]="paydate";
	$ist->input_fields[$fn]="uiDatePicker";
        //ANK
        #STB- back in untill implement link to compliant doc date
      	#$ist->input_fields["paydate"]="hpd";
        // End ANK

	$closeCode="var cn=$(this).attr('name');
   	var row=$(this).closest('tr');
	var cval=row.find('input[name=fakepaydate]').val();
	var poref=row.find('input[name=poref]').val();
   	var data='params=!I!idn=poref!I!idv='+poref+'!I!mode=saveField!I!url=rfqJrqHandler!I!tn=purchaseorders!I!fn=paydate!I!fv='+cval;
       	saver(data);
	";

	$closeFx=",onClose:function(){".$closeCode."}";

	$dppx="$('.paydate').each(function() {
	  $(this).datepicker({
	    inline: true,dateFormat: 'dd/mm/yy',altFormat: 'yy-mm-dd',
	    altField: $(this).closest('tr').find('input[name=fakepaydate]')
	    $closeFx
  	  });
	});";
        //ANK
        #STB- back in untill implement link to compliant doc date
		$this->jcallA[]=$dppx;
        // end ANK

	#$ist->input_fields["glchart"]="selectStatic";
	#$ist->input_jcall["glchartid"]="onchange=jrqSaver";
	#$ist->ajaxData["glchartid"]=kda("poref");
	$fn="glchartid";
	$ist->input_fields[$fn]="text";
	$ist->colClassText[$fn]="jrqChange";

	$ist->input_fields["glchartid"]="select";
	$ist->input_fields["syncbase"]="hidden";

	$this->glConditions($jobid);
	$this->logIt("GLCOND: ".$this->glcondx);
	$gq="select glchartid, concat(glchartid,' ',glchartdesc) as glchartdesc from glchart
	where glgroupdesc='expenses' $this->glcondx";
	$gtf=kda("glchartid,glchartdesc");
	$dca=iqa($gq,$gtf);

	$gcca["not-to-be-migrated"]="not-to-be-migrated";
	foreach($dca as $row) {
		$rg=$row["glchartid"];
		$rd=$row["glchartdesc"];
		#echo "alert('$rg $rd')\n";
		$gcca[$rg]=$rd;
	}
	$ist->selectOptions["glchartid"]=$gcca;

	if ($nonchargeable  && ! $dcfmjob && ! $prelimjob){
		$costclass = "readonlytext";
	} else {
		$costclass = "text";
	}
	$ist->input_fields["cost"]=$costclass;
	$ist->input_fields["materialcost"]=$costclass;
	$ist->input_fields["labourcost"]=$costclass;
	
	$ist->input_fields["oncosts"]="readonlytext";
	$ist->input_fields["unapprovedlabourcost"]="readonlytext";
	$ist->input_fields["polimit"]="text";
	
	#$ist->colClassText[$fn]="jrqChange";
	$ist->curValAttr[$fn]="true";
	//$ist->colClassText[$fn]="jrqcost";
	$ist->colClassText["materialcost"]="jrqcost";
	$ist->colClassText["labourcost"]="jrqcost";
	$ist->colClassText["oncosts"]="jrqcost";
	$ist->colClassText["unapprovedlabourcost"]="jrqcost";
	$ist->colClassText["polimit"]="polimit";
	$ist->colClass["pocompleted"]="pocompleted";	


	#$ist->input_jcall["invoiceref"]="onchange=jrqSaver";
	#$ist->ajaxData["invoiceref"]=kda("poref");
	$fn="invoiceref";
	$ist->input_fields[$fn]="text";
	$ist->colClassText[$fn]="jrqChange";

	#??? jq date saving...leave for moment..stbreview
	#$fn="invoicedate";
	#$ist->colClassText[$fn]="jrqChange";


	#$ist->total_fields=kda("cost");
	$ist->total_fields=kda("unapprovedlabourcost,cost,polimit");
	
	$jax=$this->ja["attachments"];
	$ata=unserialize($jax);

	$filt=$this->paramsA["fval"];
	#echo "<br>got $filt";
	$jrqFilter="<br>Filter:<input id=jrqFilter value=$filt>";
	$title="Job Request Action Log $jrqFilter";
	$filtc="$('#jrqFilter').keyup(function(){
		    	delay(function(){
		 		  
			      var fval=escape($('#jrqFilter').val());
			      //alert('filt'+fval);
			      ajl('miscFunctions','!I!mode=filteredJrq!I!jobid=$jobid!I!fval='+fval,'jrqlog');
		    	}, 500 );

	});";
	$this->jcallA[]=$filtc;

	//$title.="&nbsp; <button id='showzero'>Show 0 value only</button>";




	#<i>Optional Docs:</i> <form name=attachmentsdcfmwo07J>";
	#$title.="<br><font color=#999;>MYOB sync'd read only</font>";
	#$title="<br><div style='color:#999;'>MYOB sync'd read only</div>";
	$title.="<br>MYOB sync'd read only";
	/*if(isset($this->doca)){
		foreach($this->doca as $lab=>$docn){
			$flag=$ata['dcfmwo07J'][$docn];
			unset($ckx);
			if($flag) $ckx="checked";
			$title.="<input type=checkbox name=dcfmwo07J$docn $ckx onclick=saveAttach('$docn','dcfmwo07J');><i>$lab &nbsp;</i>";
		}
	}
	*/
	/*
	$contrx="<br><b>Dynamic Forms</b>";
	#load contractor forms.
	$dtype="dcfmwo07J";
	$contrx.=$this->loadContractorForms($dtype);
	$title.=$contrx;
	$title.="</form>";
	*/

#return "rx 5307";
#determined that glchartid blows memory on compliance jobs
#rule for moment is that if data rows>20, don't display GL
#introduced pagination instead, so can handle it
#if(sizeof($pa)>20) unset($tf["glchartid"]);

	$ist->tableid="jrqList";




	if(sizeof($pa)<$perPage) $ist->paginate=false;

	if((sizeof($pa)>0)|($fval!='')){
	 $jrqlog=$ist->inf_sortable($tf,$pa,$title,null,null,true);
	}
	foreach($ist->jcallA as $icall) $this->jcallA[]=$icall;
	#$this->jcallA[]="alert('ok')";

	if($this->viaFilterRefresh){
		$nx.="$jrqlog";//dont recreate target container div
	}else{
		$nx.="<div id=jrqlog>$jrqlog</div>";
	}
	//tfw("jrq.txt",$nx,true);
	#elog("n $nx");
	if($this->returnText) return $nx;
	if($this->noClean){
		$cx=$nx;
	}else{
		$cx=vClean($nx);
	}

	#$tjx="alert('pok')\n;";
	if($qlimit=="") $qlimit=0;
	$qvtest="$('.jrqcost').change(function(){
		//alert('test');
		var row=$(this).closest('tr');
		var rfq=row.find('input[name=rfqno]').val();
		var qval=round(row.find('input[name=qval]').val(),2);
		var cval=round($(this).val(),2);
		var lno=row.find('input[name=rowc]').val();
		var poref=row.find('input[name=poref]').val();
		var fail=false;
		console.log('jrqcost change',poref);



		 //summ all rfq
		 var tqval=0;
		 $('#rfqList input[name=accept]:checked').each(function(){
		 var row = $(this).parents('tr');
		 	var rfqv=round(row.find('input[name=qval]').val(),2);
			tqval+=round(rfqv,2);
			//alert(rfqv+' '+tqval);
		 });

		//summ all jrq
		 var tcval=0;
		 $('#jrqList input[name=accepted]:checked').each(function(){
		 var row = $(this).parents('tr');
		 	var cv=round(row.find('input[name=cost]').val(),2);
			tcval+=round(cv,2);
			//alert('cv '+cv);
		 });
		//alert(' tcv '+tcval);

		 var qlim=50;
		 //alert('qlim '+qlim);
		 if(tqval==0){
		 	var qtype='internal limit'
		 	tqval=$qlimit;
		 }else{
		 	var qtype='quoted'
		 }

		if(tqval>0){
			//alert('tqv '+tqval+' vs '+tcval);
			if(tcval>tqval){
				var max=round(tqval-(tcval-cval),2);
				//alert('This change would result in total accepted costs '+tcval+' exceeding total '+qtype+' value of '+tqval+'. Maximum would be '+max);
				//fail=true;
				$(this).val(0);
				jqml('dialogGeneric','rfqExceed','dialogGeneric','!I!poref='+poref+'!I!rfqno='+rfq+'!I!qval='+qval+'!I!cval='+cval+'!I!tqval='+tqval+'!I!tcval='+tcval)

			}
			else
			{
			var max=round(tqval-(tcval-cval),2);
				if(rfq!='')
				{
				//$(this).val(0);
				jqml('dialogGeneric','rfqClose','dialogGeneric','!I!poref='+poref+'!I!rfqno='+rfq+'!I!qval='+qval+'!I!cval='+cval)
				}
			}

		 }



		//alert(cval+' on po: '+lno);
		//if(rfq!=''){
		if(rfq>0){
		//alert('test '+cval+' vs  '+qval);
		//alert('testing change to cost '+cval+' on rfq:'+rfq+' '+qval);

			if(cval>qval){
				//alert('Cost '+cval+' exceeds quoted value of '+qval+' on rfq:'+rfq);
				jqml('dialogGeneric','rfqExceed','dialogGeneric','!I!poref='+poref+'!I!rfqno='+rfq+'!I!qval='+qval+'!I!cval='+cval)
				$(this).val(qval);
				fail=true;
			}
		}

		if(!fail){
			//alert('Cost '+cval+' is ok');
			jrqSaver(lno);
		}else{
			//alert('failed');
		}


	});\n";
	#$this->djcallA[]=$tjx;

	$pod="$('.podelete').click(function(){
		//alert('kill me');
		var row=$(this).closest('tr');
		var poref=row.find('input[name=poref]').val()
		var inc= row.find('input[name=podelete]').is(':checked');
		if(inc)	jqml('dialogGeneric','poDelete','dialogGeneric','!I!jobid=$jobid!I!poref='+poref);
	});";

	$this->djcallA[]=$qvtest;
	$this->djcallA[]=$pod;

	//ANK Upload Docs
	$upl = "$('.upload').click(function(){
	   var row=$(this).closest('tr');
	   var poref=row.find('input[name=poref]').val();
	   jqml('dialogGeneric','docUpload','dialogGeneric','!I!type=po!I!poref='+poref);

	});";
	//echo $upl;
	$this->jcallA[]=$upl;
	
	$companyedit = "$('.companyEdit').click(function(){
	   var row=$(this).closest('tr');
	   var supplierid=row.find('input[name=supplierid]').val();
	   custviewnw(supplierid);
	});
	
	";
	$this->jcallA[] = $companyedit;
	
	$sendreminder = "$('.sendreminder').click(function(){
	   var row=$(this).closest('tr');
	   var poref=row.find('input[name=poref]').val();
	   var pocompleted=row.find('.pocompleted').html().trim();
	   console.log('po:',pocompleted);
	   
	   if(pocompleted != 'on'){
	   	alert('Reminders can only be sent on completed JRQs.');
		return;   
	   }
	
	if (!confirm('Do you want to send a reminder this JRQ?')  ) return false;
		
		var data = 'params=!I!url=emailContractorReminder!I!mode=sendjrqreminder!I!poref='+poref;
	    var url = '../../infbase/ajax/ajaxpostBack.php';

	    $.ajax({
	      url: url,
	      type: 'post',
	      data: data,
	      async: false,
	      cache: false,
	      dataType: 'text',
	      success: function(data) {
	       var esuccess = data.substr(0,1);
		   if (esuccess == '1'){	
  	        alert('Reminder sent!');
  	       } else {
  	       	alert('Error sending reminder!');
  	       }
	      }
		});
		
		
	});
	
	";
	$this->jcallA[] = $sendreminder;
	
	$chkdocs = "$('.checkdocs').click(function(){
	   var row=$(this).closest('tr');
	   var poref=row.find('input[name=poref]').val();


		if (!confirm('Do you want to recheck Docs Complete for this JRQ?')  ) return false;

		var data = 'params=!I!url=emailContractorReminder!I!mode=checkDocscomplete!I!poref='+poref;
	    var url = '../../infbase/ajax/ajaxpostBack.php';

	    $.ajax({
	      url: url,
	      type: 'post',
	      data: data,
	      async: false,
	      cache: false,
	      dataType: 'json',
	      success: function(data) {
  	       jobview($jobid);
	      }
		});

	});";

	//echo $chkdocs;
	$this->jcallA[]=$chkdocs;

    $updatedocs = "$('.updatedocs').click(function(){
	   var row=$(this).closest('tr');
	   var poref=row.find('input[name=poref]').val();


		if (!confirm('Do you want to update required documents for this JRQ?')  ) return false;

		var data = 'params=!I!url=emailContractorReminder!I!mode=updateDocs!I!poref='+poref;
	    var url = '../../infbase/ajax/ajaxpostBack.php';

	    $.ajax({
	      url: url,
	      type: 'post',
	      data: data,
	      async: false,
	      cache: false,
	      dataType: 'text',
	      success: function(data) {
  	       jobview($jobid);
	      }
		});

	});";

	//echo $chkdocs;
	$this->jcallA[]=$updatedocs;
   $inrjx = "$('.invnotreqd').click(function(){
    	var row=$(this).closest('tr');
		var poref= row.find('input[name=poref]').val();
		var invnrstat = row.find('input[name=invnotreqd]').val();
	
		var cval = 'on';
		if (invnrstat == 'on'){
			if (confirm('Are you sure you want to untick this?')) {
				cval = '';
			} else {
				row.find('input[name=invnotreqd]').prop('checked');

			}
		} 

		var data='params=!I!idn=poref!I!idv='+poref+'!I!mode=saveField!I!url=rfqJrqHandler!I!tn=purchaseorders!I!fn=invnotreqd!I!fv='+cval;
       	saver(data);
		jobview($jobid);
		
    });";
	
	$this->jcallA[] = $inrjx;
	//End ANK

	//PO Limit change
	$limitjx = "$('.polimit').click(function(){
		var row=$(this).closest('tr');
		var poref= row.find('input[name=poref]').val();
		var polimit = $(this).val();
		console.log('polimit ',poref,polimit);
		
		var jobid = $('#nav2.nav2').html().replace('&nbsp;','');
		var jdata = new Object();
		jdata['jobid']=$jobid;
		jdata['poref']=poref;
		jdata['polimit']=polimit;
		
	var json =JSON.stringify(jdata);

	data = 'mode=checkjrqlimit&jdata='+json;
	
	var url = '../../common/phoenix/bl/ajax/ajax-job.php';

	$.ajax({
		url: url,
	    type: 'post',
	    data: data,
	    async: false,
	    cache: false,
	    dataType: 'text',
	    success: function(data) {
	    	result = JSON.parse(data);
	      	if (! result.success) {
	      		alert(result.message);
	      	};
			jobview	($jobid);		
 	    }
	});
		
		
		
		
	})";
	$this->jcallA[] = $limitjx;


$hovCall="
  //$('#divTable').tablesorter();
  $('#divTable').dataTable();
  $('.div-tip').cluetip({
     hoverClass: 'highlight',
     sticky: true,
     closePosition: 'top',
     width: '800',
     closeText: '<img src=\'/infbase/images/icons/alerts/error.gif\' alt=close>',
     ajaxSettings: {
       type: 'POST'
     }
  });";
  	#unset($this->jcallA);
  	foreach($this->jcallA as $i=>$jcl){
  		//used to spot offending jcalls if broken
  		if($i==44){
  		//elog("bad jc $jcl","cinf 5793");
  		//unset($this->jcallA[$i]);
  		}
  	}
  	$this->jcallA[]=$hovCall;




	if($this->viaAjxLog){
		$this->logx=$cx;
		return $cx;
	}
	echo "g('eaLog').innerHTML='$cx'\n";
}

function jrqLog_June2014($jobid){
	###po JRQ lines start here

	//Check for Compliance Job on customerid 6519
	$isComplianceJob = false;
	$custid = singlevalue("jobs","customerid","jobid",$jobid);
	if($custid=='6519'){
		$isComplianceJob = true;
	} 
	
	if($isComplianceJob){
		$cjx = "c.shipstate as state,c.shipsuburb as suburb,c.industrysector as primarytrade,";
	} else{
		$cjx = "";
	}
	
	$pq="select $cjx r.poref,'jrq' as atype,podate as date,reportdate,r.supplierid,c.companyname,r.rfqno,rq.qval,
	invoiceref,r.labourcost,r.materialcost,r.oncosts,r.cost,completed as pocompleted,completedate as pocompletedate,r.responded as poresponded,
	r.responsedate,r.acceptdate,exceed,safety,r.varianceValue,r.invnotreqd,r.ssnotreqd,r.rule_code,
	r.materialjobid,r.custordref,r.userid,r.accepted,glchartid,invoicedate,paydate,r.syncdate,0 as syncbase,j.internalqlimit
	from purchaseorders as r
	left outer join customer as c
	on r.supplierid=c.customerid
	left outer join rfq as rq
	on r.rfqno=rq.rfqno
	left outer join jobs as j
	on r.jobid=j.jobid
	where r.jobid=$jobid
	order by poref desc
	";

   
	
	$ist=new divTable();
	/*pagination usage*/
	$perPage=25;
	$pageNo=$this->paramsA["np"]>0?$this->paramsA["np"]:"0";
	$offset=$pageNo>0?($pageNo-1)*$perPage:"";
	$ist->offset=$pageNo;
	$ist->paginate=true;
	$ist->pagRPP=$perPage;
	$ist->noLimitQ=$pq;
	$ist->offerFullQueryExtract=true;
	$ist->fullQuerySimpleXLFields=true;
	$ist->pageLinkUrl="jobview";
	$ist->noCleanUpSerial=true;
	$ist->pageLinkParams="!I!jobid=$jobid!I!mode=jrqpage!I!params=1";
	$ist->pageLinkTargetDiv="jrqlog";
	$ist->pageLinkFunc="ajl";
	$limitx=$offset!=""?" limit $offset,$perPage":" limit $perPage";//to be included in query
	#$ist->paginateAppendQstring=true;//optional for non 'ajl' type links
	$pq.=$limitx;



	//ANK line 5310 additional fields invnotreqd,ssnotreqd,rule_code
	$tf=kda("supplierid,poref,atype,companyname,date,poresponded,responsedate,userid,accepted,acceptdate,invoiceref,invoicedate,paydate,cost,glchartid,pocompleted,pocompletedate,exceed,safety,podelete,syncdate,syncbase");
	$tf=kda("supplierid,poref,assignrfq,rfqno,qval,atype,companyname,date,poresponded,responsedate,userid,accepted,acceptdate,invoiceref,custordref,invoicedate,paydate,reportdate,labourcost,materialcost,oncosts,cost,varianceValue,glchartid,pocompleted,pocompletedate,taskcompleted,exceed,safety,checkdocs,updatedocs,progress,invnotreqd,ssnotreqd,rule_code,podelete,syncdate,syncbase,internalqlimit,startreq");
	
	//If compliance Job, display additional fields
	if ($isComplianceJob){
	$tf=kda("supplierid,poref,rfqno,qval,atype,companyname,suburb,state,primarytrade,date,poresponded,responsedate,userid,accepted,acceptdate,invoiceref,custordref,invoicedate,paydate,reportdate,labourcost,materialcost,oncosts,cost,varianceValue,glchartid,pocompleted,pocompletedate,taskcompleted,exceed,safety,checkdocs,updatedocs,progress,invnotreqd,ssnotreqd,rule_code,podelete,syncdate,syncbase,internalqlimit,startreq");
	}
	
	#$tf["poresponded"]="Responded";
	$tf["cost"]="$ Cost (Ex GST)";
	$tf["varianceValue"]="$ Var Req.";
	$tf["podelete"]="Delete";
	$tf["responsedate"]="Resp.Dt.";
	$tf["acceptdate"]="Accept Dt.";
	$tf["exceed"]="$ > Limit ";
	$tf["safety"]="Docs Complete";
	$tf["taskcomplete"]="Task Complete";
	#$tf["materialjobid"]="Material Job";
	$tf["custordref"]="Material Job Ord";
	$tf["pocompletedate"]="Comp. Dt.";

	$tf["ssnotreqd"] = "Docs not reqd.";
	$tf["invnotreqd"] = "Invoice not reqd.";
	$tf["rule_code"] = "Contract Rule";
	$tf["checkdocs"] = "Check Docs";
	$tf["updatedocs"] = "Update Docs";
	

	unset($tf["poresponded"]);
	unset($tf["responsedate"]);

	$pa=iqa($pq,$tf);
	if(isset($pa)){
		foreach($pa as $row){
			$qlimit=$row["internalqlimit"];
			$progress = $this->getSafetyComplianceProgress($jobid, $row['rfqno'], $row['poref']);
			if($progress){
				$row['progress'] = $progress['start'].'% start<br />'.$progress['complete'].'% complete';
				$row['startreq'] = $progress['startreq'];

			}
			unset($progress);
			$aNewArray[] = $row;
		}
	}

	$pa = $aNewArray;
	unset($aNewArray);

	unset($tf["internalqlimit"]);

	#$ist=new divTable();
	$ist->tableidx=$this->tablestyle;
	$ist->datefields=kda("date,reportdate,responsedate,acceptdate,pocompletedate,syncdate");

	$ist->colClass["progress"] = "progress clue";
	$ist->colClass["startreq"]="startreq";

	//Add cluetip icon on progress column to show safety compliance progress
	$ist->multilink["progress"][1]=array("hrefClueTipMulti");
	$ist->ajaxFnameM["progress"][1]="previewSafety.php?mode=jrq&jobid=$jobid";
	$ist->ajaxDataM["progress"][1]=kda("poref");
	$ist->iconM["progress"][1]="news";
	$ist->iconM["progress"]["iconTrails"]=true;
	$ist->noLinkIfBlank["progress"][1]=true;


/*	$ist->multilink["progress"][0]=array("hrefClueTipMulti");
	$ist->ajaxFnameM["progress"][0]="previewSafety.php?mode=jrq&jobid=$jobid";
	$ist->ajaxDataM["progress"][0]=kda("poref");
	$ist->iconM["progress"][0]="jb";
	#$ist->iconM["progress"]["iconTrails"]=true;
	#$ist->noLinkIfBlank["progress"][1]=true;
*/


	$stylecall="style=color:white;background-color:#999;";
	$ist->cssPreAnalyseNeeded=true;

	$ist->cssEntireRow["syncdate"]=true;
	$ist->cssNumericComparison["syncdate"]["left"][]="syncdate";
	$ist->cssNumericComparison["syncdate"]["right"][]="syncbase";
	$ist->cssNumericComparison["syncdate"]["operator"]="<";
	$ist->cssNumericComparison["syncdate"]["stylecall"]=$stylecall;
	$ist->cssCondRowLocking=true;


	$fn="atype";

#breaking bad
#	$ist->multilink[$fn][1]=array("ajaxHrefIconMulti");
#	$ist->ajaxDataM[$fn][1]=kda("poref");
	#$ist->ajaxFnameM[$fn][1]="jqUiModalExisting";

	$ist->multilink[$fn][1]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][1]="jrqEml";
	$ist->input_fields["poref"]="hpd";
	$ist->iconM[$fn][1]="eml";

	#$ist->multilink[$fn][2]=array("ajaxHrefIconMultiJq");
	#$ist->colClassM[$fn][2]="jrqPdf";
	#$ist->input_fields["rfqno"]="hpd";
	#$ist->iconM[$fn][2]="pdf";

	$ist->multilink[$fn][2]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][2]="jrqDoc";
	$ist->input_fields["jrqno"]="hpd";
	$ist->iconM[$fn][2]="p";

    $ist->multilink[$fn][3]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][3]="jrqCompliance";
	$ist->iconM[$fn][3]="v";

    $ist->multilink[$fn][4]=array("jqButton");
	$ist->colClassM[$fn][4]="rfqEml";
	$ist->buttonClass[$fn]="infoRqJrq";
	$ist->input_fields["rfqno"]="hpd";
	$ist->jbuttondesc[$fn]="Info Rq.";



	#go via customised function first to build PDF
	$rparams.="!I!fid=dcfmwo07J!I!prefix=jrq!I!folder=jrq";
	$ist->ajaxStaticDataM[$fn][1]=kda("!I!divid=dialogJrqMailer!I!tn=jrqMailer!I!title=Address!I!jobid=$jobid$rparams");
	$ist->ajaxFnameM[$fn][1]="jrqPdfEmail";

	$ist->iconM[$fn][1]="eml";

	#breaking bad
	#		$ist->multilink["atype"][3]=array("ajaxHrefIconMulti");
	#		$ist->ajaxFnameM[$fn][3]="idoc";;
	#	    $ist->ajaxStaticDataM[$fn][3]=kda("dcfmwo07J");;
	#	  	$ist->ajaxDataM[$fn][3]=kda("poref");
	#	    $ist->iconM[$fn][3]="e";
	#	    $ist->iconM[$fn][3]="p";

	$ist->entrymode=true;
	$ist->input_fields["poref"]="hpd";
	$ist->input_fields["rfqno"]="hpd";
	$ist->input_fields["qval"]="hidden";
	$ist->input_fields["supplierid"]="hidden";

	#superceded by jquery
	#$ist->input_jcall["poresponded"]="onclick=jrqResponded";
	#$ist->input_fields["poresponded"]="checkbox";
	#$ist->ajaxData["poresponded"]=kda("poref");
	#seems not in use
	#$fn="poresponded";
	#$ist->input_fields[$fn]="checkboxJq";
	#$ist->ckboxClass[$fn]="jrqrespond";
	#$ist->noCheckAll[$fn]=true;

	#superceded by jquery
	#$ist->input_jcall["accepted"]="onclick=jrqAccepted";
	#$ist->input_fields["accepted"]="checkbox";
	#$ist->ajaxData["accepted"]=kda("poref");
	$fn="accepted";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="jrqaccept";
	$ist->noCheckAll[$fn]=true;

	#superceded by jquery
	#$ist->input_jcall["pocompleted"]="onclick=jrqCompleted";
	#$ist->input_fields["pocompleted"]="checkbox";

	//ANK disable jrqcomplete
	#$fn="pocompleted";
	#$ist->input_fields[$fn]="checkboxJq";
	#$ist->ckboxClass[$fn]="jrqComplete";
	#$ist->noCheckAll[$fn]=true;


	#$ist->ajaxData["pocompleted"]=kda("poref");
	//End ANK
	$fn="taskcompleted";
	$ist->linka[$fn]=array("ajaxButton"=>"hx");
	#$ist->linka["desc"]=array("ajaxHrefIcon"=>"hx");

	#$ist->ajaxFname[$fn]="jrqTaskCompleted";;
  	#$ist->ajaxData[$fn]=kda("poref,jobid");
	$ist->jbuttondesc["taskcompleted"]="done";
	$ist->buttonClass[$fn]="jTC";

	$ist->colClass["materialjobid"]="mjobid";
	$ist->colClass["custordref"]="coref";
	$ist->colClass["poref"]="poref";
	$mjx="$('.coref').click(function(){
		var row=$(this).closest('tr');
		var poref= row.find('input[name=poref]').val();
		var customerid= row.find('input[name=customerid]').val();
		var customerid= $('#customerid').val();
		jmg('materialJobSearch','!I!poref='+poref+'!I!customerid='+customerid);
	});";
	$this->jcallA[]=$mjx;

   	
	#  $ist->iconM[$fn][0]="e";
	#  $ist->iconM[$fn][0]="p";

	/*
		$ist->linkinput_fields["taskcompleted"]="ajaxButton";
		$ist->ajaxData["taskcompleted"]=kda("poref");
		$ist->ajaxFnameM["taskcompleted"]="jrqTaskCompleted";
		$ist->jbuttondesc["taskcompleted"]="done";
	*/

	#superceded by jquery
	#$ist->input_jcall["safety"]="onclick=jrqCheckBoxVal";
	#$ist->input_fields["safety"]="checkbox";
	#$ist->ajaxStaticData["safety"]=kda("safety");
	#$ist->ajaxData["safety"]=kda("poref");

	$fn="safety";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="safety";
	$ist->noCheckAll[$fn]=true;

	#superceded by jquery
	#$ist->input_jcall["exceed"]="onclick=jrqCheckBoxVal";
	#$ist->input_fields["exceed"]="checkbox";
	#$ist->ajaxStaticData["exceed"]=kda("exceed");
	#$ist->ajaxData["exceed"]=kda("poref");

	$fn="exceed";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="safety";
	$ist->noCheckAll[$fn]=true;

	#superceded by jquery
	#$ist->input_jcall["podelete"]="onclick=jrqDelete";
	#$ist->input_fields["podelete"]="checkbox";
	#$ist->colClass["podelete"]="podelete";
	#$ist->ajaxData["podelete"]=kda("poref");

	$fn="podelete";
	/*$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="podel";
	$ist->noCheckAll[$fn]=true;
	*/
	#$ist->input_fields[$fn]="checkboxJq";
	$ist->linka[$fn]=array("ajaxButton"=>"hx");
	$ist->jbuttondesc[$fn]="Delete";
	$ist->buttonClass[$fn]="podel";

	//ANK Doc Upload
	$fn="upload";
	$ist->linka[$fn]=array("ajaxButton"=>"hx");
	$ist->jbuttondesc[$fn]="Upload Docs.";
	$ist->buttonClass[$fn]="upload";

	$fn="checkdocs";
	$ist->linka[$fn]=array("ajaxButton"=>"hx");
	$ist->jbuttondesc[$fn]="Check Docs.";
	$ist->buttonClass[$fn]="checkdocs";

	$fn="updatedocs";
	$ist->linka[$fn]=array("ajaxButton"=>"hx");
	$ist->jbuttondesc[$fn]="Update Docs.";
	$ist->buttonClass[$fn]="updatedocs";



	//End ANK

	//ANK New fields
	
	$fn="assignrfq";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="assignrfq";
	$ist->noCheckAll[$fn]=true;
	
	
	$fn="ssnotreqd";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="ssnotreqd";
	$ist->noCheckAll[$fn]=true;

	$fn="invnotreqd";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="invnotreqd";
	$ist->noCheckAll[$fn]=true;
	//End ANK



	$ist->input_fields["invoiceref"]="text";
	#$ist->input_fields["invoicedate"]="date_ClickCal";
	$ist->input_fields["invoicedate"]="uiDatePicker";
	$fn="invoicedate";
	$ist->dtClass[$fn]="invdate";
	$closeCode="var cn=$(this).attr('name');
   	var row=$(this).closest('tr');
	var cval=row.find('input[name=fakeinvoicedate]').val();
	var poref=row.find('input[name=poref]').val();
   	var data='params=!I!idn=poref!I!idv='+poref+'!I!mode=saveField!I!url=rfqJrqHandler!I!tn=purchaseorders!I!fn=invoicedate!I!fv='+cval;
       	saver(data);
	";


	$closeFx=",onClose:function(){".$closeCode."}";
	#$sugcall="$('.invdate').datepicker({inline: true,dateFormat: 'dd/mm/yy',altField: '.fakeinvoicedate',altFormat: 'yy-mm-dd' $closeFx });";
	#$sugcall="$('.invdate').datepicker({inline: true,dateFormat: 'dd/mm/yy',altFormat: 'yy-mm-dd' $closeFx });";
	#$this->jcallA[]=$sugcall;

	$dpx="$('.invdate').each(function() {
	  $(this).datepicker({
	    inline: true,dateFormat: 'dd/mm/yy',altFormat: 'yy-mm-dd',
	    altField: $(this).closest('tr').find('input[name=fakeinvoicedate]')
	    $closeFx

	  });
	});";
	$this->jcallA[]=$dpx;

	##report date edit
	$ist->input_fields["reportdate"]="uiDatePicker";
	$ist->neverLock[]="reportdate";
	$fn="reportdate";
	$ist->dtClass[$fn]="rdate";
	$closeCode="var cn=$(this).attr('name');
   	var row=$(this).closest('tr');
	var cval=row.find('input[name=fakereportdate]').val();
	var poref=row.find('input[name=poref]').val();
   	var data='params=!I!idn=poref!I!idv='+poref+'!I!mode=saveField!I!url=rfqJrqHandler!I!tn=purchaseorders!I!fn=reportdate!I!fv='+cval;
       	saver(data);
	";
	$closeFx=",onClose:function(){".$closeCode."}";
	#$sugcall="$('.invdate').datepicker({inline: true,dateFormat: 'dd/mm/yy',altField: '.fakeinvoicedate',altFormat: 'yy-mm-dd' $closeFx });";
	#$sugcall="$('.invdate').datepicker({inline: true,dateFormat: 'dd/mm/yy',altFormat: 'yy-mm-dd' $closeFx });";
	#$this->jcallA[]=$sugcall;

	$dpx="$('.rdate').each(function() {
	  $(this).datepicker({
	    inline: true,dateFormat: 'dd/mm/yy',altFormat: 'yy-mm-dd',
	    altField: $(this).closest('tr').find('input[name=fakereportdate]')
	    $closeFx
  	  });
	});";
	$this->jcallA[]=$dpx;

	##



	#$ist->input_fields["paydate"]="date_ClickCal";
	$fn="paydate";
	$ist->dtClass[$fn]="paydate";
	$ist->input_fields[$fn]="uiDatePicker";
        //ANK
        #STB- back in untill implement link to compliant doc date
      	#$ist->input_fields["paydate"]="hpd";
        // End ANK

	$closeCode="var cn=$(this).attr('name');
   	var row=$(this).closest('tr');
	var cval=row.find('input[name=fakepaydate]').val();
	var poref=row.find('input[name=poref]').val();
   	var data='params=!I!idn=poref!I!idv='+poref+'!I!mode=saveField!I!url=rfqJrqHandler!I!tn=purchaseorders!I!fn=paydate!I!fv='+cval;
       	saver(data);
	";

	$closeFx=",onClose:function(){".$closeCode."}";

	$dppx="$('.paydate').each(function() {
	  $(this).datepicker({
	    inline: true,dateFormat: 'dd/mm/yy',altFormat: 'yy-mm-dd',
	    altField: $(this).closest('tr').find('input[name=fakepaydate]')
	    $closeFx
  	  });
	});";
        //ANK
        #STB- back in untill implement link to compliant doc date
		$this->jcallA[]=$dppx;
        // end ANK

	#$ist->input_fields["glchart"]="selectStatic";
	#$ist->input_jcall["glchartid"]="onchange=jrqSaver";
	#$ist->ajaxData["glchartid"]=kda("poref");
	$fn="glchartid";
	$ist->input_fields[$fn]="text";
	$ist->colClassText[$fn]="jrqChange";

	$ist->input_fields["glchartid"]="select";
	$ist->input_fields["syncbase"]="hidden";

	$this->glConditions($jobid);
	$this->logIt("GLCOND: ".$this->glcondx);
	$gq="select glchartid, concat(glchartid,' ',glchartdesc) as glchartdesc from glchart
	where glgroupdesc='expenses' $this->glcondx";
	$gtf=kda("glchartid,glchartdesc");
	$dca=iqa($gq,$gtf);

	$gcca["not-to-be-migrated"]="not-to-be-migrated";
	foreach($dca as $row) {
		$rg=$row["glchartid"];
		$rd=$row["glchartdesc"];
		#echo "alert('$rg $rd')\n";
		$gcca[$rg]=$rd;
	}
	$ist->selectOptions["glchartid"]=$gcca;

	$ist->input_fields["cost"]="text";

	#superceded
	#$ist->input_jcall["cost"]="onchange=jrqSaver";
	#$ist->colClass["cost"]="jrqcost";
	#$ist->ajaxData["cost"]=kda("poref");

	$fn="cost";
	$ist->input_fields[$fn]="readonlytext";
	$ist->input_fields["materialcost"]="text";
	$ist->input_fields["labourcost"]="text";
	$ist->input_fields["oncosts"]="readonlytext";

	#$ist->colClassText[$fn]="jrqChange";
	$ist->curValAttr[$fn]="true";
	$ist->colClassText[$fn]="jrqcost";
	$ist->colClassText["materialcost"]="jrqcost";
	$ist->colClassText["labourcost"]="jrqcost";
	$ist->colClassText["oncosts"]="jrqcost";



	#$ist->input_jcall["invoiceref"]="onchange=jrqSaver";
	#$ist->ajaxData["invoiceref"]=kda("poref");
	$fn="invoiceref";
	$ist->input_fields[$fn]="text";
	$ist->colClassText[$fn]="jrqChange";

	#??? jq date saving...leave for moment..stbreview
	#$fn="invoicedate";
	#$ist->colClassText[$fn]="jrqChange";


	$ist->total_fields=kda("cost");

	$jax=$this->ja["attachments"];
	$ata=unserialize($jax);

	$title="Job Request Action Log ";

	#<i>Optional Docs:</i> <form name=attachmentsdcfmwo07J>";
	#$title.="<br><font color=#999;>MYOB sync'd read only</font>";
	#$title="<br><div style='color:#999;'>MYOB sync'd read only</div>";
	$title.="<br>MYOB sync'd read only";
	/*if(isset($this->doca)){
		foreach($this->doca as $lab=>$docn){
			$flag=$ata['dcfmwo07J'][$docn];
			unset($ckx);
			if($flag) $ckx="checked";
			$title.="<input type=checkbox name=dcfmwo07J$docn $ckx onclick=saveAttach('$docn','dcfmwo07J');><i>$lab &nbsp;</i>";
		}
	}
	*/
	/*
	$contrx="<br><b>Dynamic Forms</b>";
	#load contractor forms.
	$dtype="dcfmwo07J";
	$contrx.=$this->loadContractorForms($dtype);
	$title.=$contrx;
	$title.="</form>";
	*/

#return "rx 5307";
#determined that glchartid blows memory on compliance jobs
#rule for moment is that if data rows>20, don't display GL
#introduced pagination instead, so can handle it
#if(sizeof($pa)>20) unset($tf["glchartid"]);

	$ist->tableid="jrqList";





	if(sizeof($pa)<$perPage) $ist->paginate=false;

	if(sizeof($pa)>0) $jrqlog=$ist->inf_sortable($tf,$pa,$title,null,null,true);
	foreach($ist->jcallA as $icall) $this->jcallA[]=$icall;
	#$this->jcallA[]="alert('ok')";
	$nx.="<div id=jrqlog>$jrqlog</div>";
	//tfw("jrq.txt",$nx,true);
	#elog("n $nx");
	if($this->returnText) return $nx;
	if($this->noClean){
		$cx=$nx;
	}else{
		$cx=vClean($nx);
	}

	#$tjx="alert('pok')\n;";
	if($qlimit=="") $qlimit=0;
	$qvtest="$('.jrqcost').change(function(){

		//alert('test');
		var row=$(this).closest('tr');
		var rfq=row.find('input[name=rfqno]').val()
		var qval=round(row.find('input[name=qval]').val(),2)
		var cval=round($(this).val(),2);
		var lno=row.find('input[name=rowc]').val()
		var poref=row.find('input[name=poref]').val()
		var fail=false;



		 //summ all rfq
		 var tqval=0;
		 $('#rfqList input[name=accept]:checked').each(function(){
		 var row = $(this).parents('tr');
		 	var rfqv=round(row.find('input[name=qval]').val(),2);
			tqval+=round(rfqv,2);
			//alert(rfqv+' '+tqval);
		 });

		//summ all jrq
		 var tcval=0;
		 $('#jrqList input[name=accepted]:checked').each(function(){
		 var row = $(this).parents('tr');
		 	var cv=round(row.find('input[name=cost]').val(),2);
			tcval+=round(cv,2);
			//alert('cv '+cv);
		 });
		//alert(' tcv '+tcval);

		 var qlim=50;
		 //alert('qlim '+qlim);
		 if(tqval==0){
		 	var qtype='internal limit'
		 	tqval=$qlimit;
		 }else{
		 	var qtype='quoted'
		 }

		if(tqval>0){
			//alert('tqv '+tqval+' vs '+tcval);
			if(tcval>tqval){
				var max=round(tqval-(tcval-cval),2);
				//alert('This change would result in total accepted costs '+tcval+' exceeding total '+qtype+' value of '+tqval+'. Maximum would be '+max);
				//fail=true;
				$(this).val(0);
				jqml('dialogGeneric','rfqExceed','dialogGeneric','!I!poref='+poref+'!I!rfqno='+rfq+'!I!qval='+qval+'!I!cval='+cval+'!I!tqval='+tqval+'!I!tcval='+tcval)

			}
			else
			{
			var max=round(tqval-(tcval-cval),2);
				if(rfq!='')
				{
				//$(this).val(0);
				jqml('dialogGeneric','rfqClose','dialogGeneric','!I!poref='+poref+'!I!rfqno='+rfq+'!I!qval='+qval+'!I!cval='+cval)
				}
			}

		 }



		//alert(cval+' on po: '+lno);
		//if(rfq!=''){
		if(rfq>0){
		//alert('test '+cval+' vs  '+qval);
		//alert('testing change to cost '+cval+' on rfq:'+rfq+' '+qval);

			if(cval>qval){
				//alert('Cost '+cval+' exceeds quoted value of '+qval+' on rfq:'+rfq);
				jqml('dialogGeneric','rfqExceed','dialogGeneric','!I!poref='+poref+'!I!rfqno='+rfq+'!I!qval='+qval+'!I!cval='+cval)
				$(this).val(qval);
				fail=true;
			}
		}

		if(!fail){
			//alert('Cost '+cval+' is ok');
			jrqSaver(lno);
		}else{
			//alert('failed');
		}


	});\n";
	#$this->djcallA[]=$tjx;

	$pod="$('.podelete').click(function(){
		//alert('kill me');
		var row=$(this).closest('tr');
		var poref=row.find('input[name=poref]').val()
		var inc= row.find('input[name=podelete]').is(':checked');
		if(inc)	jqml('dialogGeneric','poDelete','dialogGeneric','!I!jobid=$jobid!I!poref='+poref);
	});";

	$this->djcallA[]=$qvtest;
	$this->djcallA[]=$pod;

	//ANK Upload Docs
	$upl = "$('.upload').click(function(){
	   var row=$(this).closest('tr');
	   var poref=row.find('input[name=poref]').val();
	   jqml('dialogGeneric','docUpload','dialogGeneric','!I!type=po!I!poref='+poref);

	});";
	//echo $upl;
	$this->jcallA[]=$upl;

	$chkdocs = "$('.checkdocs').click(function(){
	   var row=$(this).closest('tr');
	   var poref=row.find('input[name=poref]').val();


		if (!confirm('Do you want to recheck Docs Complete for this JRQ?')  ) return false;

		var data = 'params=!I!url=emailContractorReminder!I!mode=checkDocscomplete!I!poref='+poref;
	    var url = '../../infbase/ajax/ajaxpostBack.php';

	    $.ajax({
	      url: url,
	      type: 'post',
	      data: data,
	      async: false,
	      cache: false,
	      dataType: 'json',
	      success: function(data) {
  	       jobview($jobid);
	      }
		});

	});";

	//echo $chkdocs;
	$this->jcallA[]=$chkdocs;

$updatedocs = "$('.updatedocs').click(function(){
	   var row=$(this).closest('tr');
	   var poref=row.find('input[name=poref]').val();


		if (!confirm('Do you want to update required documents for this JRQ?')  ) return false;

		var data = 'params=!I!url=emailContractorReminder!I!mode=updateDocs!I!poref='+poref;
	    var url = '../../infbase/ajax/ajaxpostBack.php';

	    $.ajax({
	      url: url,
	      type: 'post',
	      data: data,
	      async: false,
	      cache: false,
	      dataType: 'text',
	      success: function(data) {
  	       jobview($jobid);
	      }
		});

	});";

	//echo $chkdocs;
	$this->jcallA[]=$updatedocs;


   $inrjx = "$('.invnotreqd').click(function(){
    	var row=$(this).closest('tr');
		var poref= row.find('input[name=poref]').val();
		var invnrstat = row.find('input[name=invnotreqd]').val();
	
		var cval = 'on';
		if (invnrstat == 'on'){
			if (confirm('Are you sure you want to untick this?')) {
				cval = '';
			} else {
				row.find('input[name=invnotreqd]').prop('checked');

			}
		} 

		var data='params=!I!idn=poref!I!idv='+poref+'!I!mode=saveField!I!url=rfqJrqHandler!I!tn=purchaseorders!I!fn=invnotreqd!I!fv='+cval;
       	saver(data);
		jobview($jobid);
		
    });";
	
	$this->jcallA[] = $inrjx;
	//End ANK



$hovCall="
  //$('#divTable').tablesorter();
  $('#divTable').dataTable();
  $('.div-tip').cluetip({
     hoverClass: 'highlight',
     sticky: true,
     closePosition: 'top',
     width: '800',
     closeText: '<img src=\'/infbase/images/icons/alerts/error.gif\' alt=close>',
     ajaxSettings: {
       type: 'POST'
     }
  });";
  	#unset($this->jcallA);
  	foreach($this->jcallA as $i=>$jcl){
  		//used to spot offending jcalls if broken
  		if($i==44){
  		//elog("bad jc $jcl","cinf 5793");
  		//unset($this->jcallA[$i]);
  		}
  	}
  	$this->jcallA[]=$hovCall;




	if($this->viaAjxLog){
		$this->logx=$cx;
		return $cx;
	}
	echo "g('eaLog').innerHTML='$cx'\n";
}

function jrqLog_safeJuly2013($jobid){
	###po JRQ lines start here

	$pq="select r.poref,'jrq' as atype,podate as date,r.supplierid,c.companyname,r.rfqno,rq.qval,
	invoiceref,r.cost,completed as pocompleted,completedate as pocompletedate,r.responded as poresponded,
	r.responsedate,r.acceptdate,exceed,safety,r.varianceValue,r.invnotreqd,r.ssnotreqd,r.rule_code,
	r.materialjobid,r.custordref,r.userid,r.accepted,glchartid,invoicedate,paydate,r.syncdate,0 as syncbase,j.internalqlimit
	from purchaseorders as r
	left outer join customer as c
	on r.supplierid=c.customerid
	left outer join rfq as rq
	on r.rfqno=rq.rfqno
	left outer join jobs as j
	on r.jobid=j.jobid
	where r.jobid=$jobid
	order by poref desc
	";

	$ist=new divTable();
	/*pagination usage*/
	$perPage=25;
	$pageNo=$this->paramsA["np"]>0?$this->paramsA["np"]:"0";
	$offset=$pageNo>0?($pageNo-1)*$perPage:"";
	$ist->offset=$pageNo;
	$ist->paginate=true;
	$ist->pagRPP=$perPage;
	$ist->noLimitQ=$pq;
	$ist->offerFullQueryExtract=true;
	$ist->fullQuerySimpleXLFields=true;
	$ist->pageLinkUrl="jobview";
	$ist->noCleanUpSerial=true;
	$ist->pageLinkParams="!I!jobid=$jobid!I!mode=jrqpage!I!params=1";
	$ist->pageLinkTargetDiv="jrqlog";
	$ist->pageLinkFunc="ajl";
	$limitx=$offset!=""?" limit $offset,$perPage":" limit $perPage";//to be included in query
	#$ist->paginateAppendQstring=true;//optional for non 'ajl' type links
	$pq.=$limitx;




	$tf=kda("supplierid,poref,atype,companyname,date,poresponded,responsedate,userid,accepted,acceptdate,invoiceref,invoicedate,paydate,cost,glchartid,pocompleted,pocompletedate,exceed,safety,podelete,syncdate,syncbase");
	$tf=kda("supplierid,poref,rfqno,qval,atype,companyname,date,poresponded,responsedate,userid,accepted,acceptdate,invoiceref,custordref,invoicedate,paydate,cost,varianceValue,glchartid,pocompleted,pocompletedate,taskcompleted,exceed,safety,progress,ssnotreqd,invnotreqd,rule_code,podelete,syncdate,syncbase,internalqlimit");
	#$tf["poresponded"]="Responded";
	$tf["cost"]="$ Cost (Ex GST)";
	$tf["varianceValue"]="$ Var Req.";
	$tf["podelete"]="Delete";
	$tf["responsedate"]="Resp.Dt.";
	$tf["acceptdate"]="Accept Dt.";
	$tf["exceed"]="$ > Limit ";
	$tf["safety"]="Docs Complete";
	$tf["taskcomplete"]="Task Complete";
	#$tf["materialjobid"]="Material Job";
	$tf["custordref"]="Material Job Ord";
	$tf["pocompletedate"]="Comp. Dt.";

	unset($tf["poresponded"]);
	unset($tf["responsedate"]);

	$pa=iqa($pq,$tf);
	if(isset($pa)){
		foreach($pa as $row){
			$qlimit=$row["internalqlimit"];
			$progress = $this->getSafetyComplianceProgress($jobid, $row['rfqno'], $row['poref']);
			if($progress){
				$row['progress'] = $progress['start'].'% start<br />'.$progress['complete'].'% complete';
			}
			unset($progress);
			$aNewArray[] = $row;
		}
	}

	$pa = $aNewArray;
	unset($aNewArray);

	unset($tf["internalqlimit"]);

	#$ist=new divTable();
	$ist->tableidx=$this->tablestyle;
	$ist->datefields=kda("date,responsedate,acceptdate,pocompletedate,syncdate");

	$ist->colClass["progress"] = "progress clue";
	$ist->colClass["startreq"]="startreq";

	//Add cluetip icon on progress column to show safety compliance progress
	$ist->multilink["progress"][1]=array("hrefClueTipMulti");
	$ist->ajaxFnameM["progress"][1]="previewSafety.php?mode=jrq&jobid=$jobid";
	$ist->ajaxDataM["progress"][1]=kda("poref");
	$ist->iconM["progress"][1]="news";
	$ist->iconM["progress"]["iconTrails"]=true;
	$ist->noLinkIfBlank["progress"][1]=true;


/*	$ist->multilink["progress"][0]=array("hrefClueTipMulti");
	$ist->ajaxFnameM["progress"][0]="previewSafety.php?mode=jrq&jobid=$jobid";
	$ist->ajaxDataM["progress"][0]=kda("poref");
	$ist->iconM["progress"][0]="jb";
	#$ist->iconM["progress"]["iconTrails"]=true;
	#$ist->noLinkIfBlank["progress"][1]=true;
*/


	$stylecall="style=color:white;background-color:#999;";
	$ist->cssPreAnalyseNeeded=true;

	$ist->cssEntireRow["syncdate"]=true;
	$ist->cssNumericComparison["syncdate"]["left"][]="syncdate";
	$ist->cssNumericComparison["syncdate"]["right"][]="syncbase";
	$ist->cssNumericComparison["syncdate"]["operator"]="<";
	$ist->cssNumericComparison["syncdate"]["stylecall"]=$stylecall;
	$ist->cssCondRowLocking=true;


	$fn="atype";

#breaking bad
#	$ist->multilink[$fn][1]=array("ajaxHrefIconMulti");
#	$ist->ajaxDataM[$fn][1]=kda("poref");
	#$ist->ajaxFnameM[$fn][1]="jqUiModalExisting";

	$ist->multilink[$fn][1]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][1]="jrqEml";
	$ist->input_fields["poref"]="hpd";
	$ist->iconM[$fn][1]="eml";

	#$ist->multilink[$fn][2]=array("ajaxHrefIconMultiJq");
	#$ist->colClassM[$fn][2]="jrqPdf";
	#$ist->input_fields["rfqno"]="hpd";
	#$ist->iconM[$fn][2]="pdf";

	$ist->multilink[$fn][2]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][2]="jrqDoc";
	$ist->input_fields["jrqno"]="hpd";
	$ist->iconM[$fn][2]="p";

    $ist->multilink[$fn][3]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][3]="jrqCompliance";
	$ist->iconM[$fn][3]="v";

    $ist->multilink[$fn][4]=array("jqButton");
	$ist->colClassM[$fn][4]="rfqEml";
	$ist->buttonClass[$fn]="infoRqJrq";
	$ist->input_fields["rfqno"]="hpd";
	$ist->jbuttondesc[$fn]="Info Rq.";



	#go via customised function first to build PDF
	$rparams.="!I!fid=dcfmwo07J!I!prefix=jrq!I!folder=jrq";
	$ist->ajaxStaticDataM[$fn][1]=kda("!I!divid=dialogJrqMailer!I!tn=jrqMailer!I!title=Address!I!jobid=$jobid$rparams");
	$ist->ajaxFnameM[$fn][1]="jrqPdfEmail";

	$ist->iconM[$fn][1]="eml";

	#breaking bad
	#		$ist->multilink["atype"][3]=array("ajaxHrefIconMulti");
	#		$ist->ajaxFnameM[$fn][3]="idoc";;
	#	    $ist->ajaxStaticDataM[$fn][3]=kda("dcfmwo07J");;
	#	  	$ist->ajaxDataM[$fn][3]=kda("poref");
	#	    $ist->iconM[$fn][3]="e";
	#	    $ist->iconM[$fn][3]="p";

	$ist->entrymode=true;
	$ist->input_fields["poref"]="hpd";
	$ist->input_fields["rfqno"]="hpd";
	$ist->input_fields["qval"]="hidden";
	$ist->input_fields["supplierid"]="hidden";

	#superceded by jquery
	#$ist->input_jcall["poresponded"]="onclick=jrqResponded";
	#$ist->input_fields["poresponded"]="checkbox";
	#$ist->ajaxData["poresponded"]=kda("poref");
	#seems not in use
	#$fn="poresponded";
	#$ist->input_fields[$fn]="checkboxJq";
	#$ist->ckboxClass[$fn]="jrqrespond";
	#$ist->noCheckAll[$fn]=true;

	#superceded by jquery
	#$ist->input_jcall["accepted"]="onclick=jrqAccepted";
	#$ist->input_fields["accepted"]="checkbox";
	#$ist->ajaxData["accepted"]=kda("poref");
	$fn="accepted";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="jrqaccept";
	$ist->noCheckAll[$fn]=true;

	#superceded by jquery
	#$ist->input_jcall["pocompleted"]="onclick=jrqCompleted";
	#$ist->input_fields["pocompleted"]="checkbox";
	$fn="pocompleted";
	//ANK Make pocomplete readonly
	#$ist->input_fields[$fn]="checkboxJq";
	#$ist->ckboxClass[$fn]="jrqComplete";
	#$ist->noCheckAll[$fn]=true;
	//End ANK

	#$ist->ajaxData["pocompleted"]=kda("poref");

	$fn="taskcompleted";
	$ist->linka[$fn]=array("ajaxButton"=>"hx");
	#$ist->linka["desc"]=array("ajaxHrefIcon"=>"hx");

	#$ist->ajaxFname[$fn]="jrqTaskCompleted";;
  	#$ist->ajaxData[$fn]=kda("poref,jobid");
	$ist->jbuttondesc["taskcompleted"]="done";
	$ist->buttonClass[$fn]="jTC";

	$ist->colClass["materialjobid"]="mjobid";
	$ist->colClass["custordref"]="coref";
	$ist->colClass["poref"]="poref";
	$mjx="$('.coref').click(function(){
		var row=$(this).closest('tr');
		var poref= row.find('input[name=poref]').val();
		var customerid= row.find('input[name=customerid]').val();
		var customerid= $('#customerid').val();
		jmg('materialJobSearch','!I!poref='+poref+'!I!customerid='+customerid);
	});";
	$this->jcallA[]=$mjx;

	#  $ist->iconM[$fn][0]="e";
	#  $ist->iconM[$fn][0]="p";

	/*
		$ist->linkinput_fields["taskcompleted"]="ajaxButton";
		$ist->ajaxData["taskcompleted"]=kda("poref");
		$ist->ajaxFnameM["taskcompleted"]="jrqTaskCompleted";
		$ist->jbuttondesc["taskcompleted"]="done";
	*/

	#superceded by jquery
	#$ist->input_jcall["safety"]="onclick=jrqCheckBoxVal";
	#$ist->input_fields["safety"]="checkbox";
	#$ist->ajaxStaticData["safety"]=kda("safety");
	#$ist->ajaxData["safety"]=kda("poref");

	$fn="safety";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="safety";
	$ist->noCheckAll[$fn]=true;

	#superceded by jquery
	#$ist->input_jcall["exceed"]="onclick=jrqCheckBoxVal";
	#$ist->input_fields["exceed"]="checkbox";
	#$ist->ajaxStaticData["exceed"]=kda("exceed");
	#$ist->ajaxData["exceed"]=kda("poref");

	$fn="exceed";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="safety";
	$ist->noCheckAll[$fn]=true;

	#superceded by jquery
	#$ist->input_jcall["podelete"]="onclick=jrqDelete";
	#$ist->input_fields["podelete"]="checkbox";
	#$ist->colClass["podelete"]="podelete";
	#$ist->ajaxData["podelete"]=kda("poref");

	$fn="podelete";
	/*$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="podel";
	$ist->noCheckAll[$fn]=true;
	*/
	#$ist->input_fields[$fn]="checkboxJq";
	$ist->linka[$fn]=array("ajaxButton"=>"hx");
	$ist->jbuttondesc[$fn]="Delete";
	$ist->buttonClass[$fn]="podel";





	$ist->input_fields["invoiceref"]="text";
	#$ist->input_fields["invoicedate"]="date_ClickCal";
	$ist->input_fields["invoicedate"]="uiDatePicker";
	$fn="invoicedate";
	$ist->dtClass[$fn]="invdate";
	$closeCode="var cn=$(this).attr('name');
   	var row=$(this).closest('tr');
	var cval=row.find('input[name=fakeinvoicedate]').val();
	var poref=row.find('input[name=poref]').val();
   	var data='params=!I!idn=poref!I!idv='+poref+'!I!mode=saveField!I!url=rfqJrqHandler!I!tn=purchaseorders!I!fn=invoicedate!I!fv='+cval;
       	saver(data);
	";

	$closeFx=",onClose:function(){".$closeCode."}";
	#$sugcall="$('.invdate').datepicker({inline: true,dateFormat: 'dd/mm/yy',altField: '.fakeinvoicedate',altFormat: 'yy-mm-dd' $closeFx });";
	#$sugcall="$('.invdate').datepicker({inline: true,dateFormat: 'dd/mm/yy',altFormat: 'yy-mm-dd' $closeFx });";
	#$this->jcallA[]=$sugcall;

	$dpx="$('.invdate').each(function() {
	  $(this).datepicker({
	    inline: true,dateFormat: 'dd/mm/yy',altFormat: 'yy-mm-dd',
	    altField: $(this).closest('tr').find('input[name=fakeinvoicedate]')
	    $closeFx

	  });
	});";
	$this->jcallA[]=$dpx;

	#$ist->input_fields["paydate"]="date_ClickCal";
	$fn="paydate";
	$ist->dtClass[$fn]="paydate";
	$ist->input_fields[$fn]="uiDatePicker";
        //ANK
        #STB- back in untill implement link to compliant doc date
      	#$ist->input_fields["paydate"]="hpd";
        // End ANK

	$closeCode="var cn=$(this).attr('name');
   	var row=$(this).closest('tr');
	var cval=row.find('input[name=fakepaydate]').val();
	var poref=row.find('input[name=poref]').val();
   	var data='params=!I!idn=poref!I!idv='+poref+'!I!mode=saveField!I!url=rfqJrqHandler!I!tn=purchaseorders!I!fn=paydate!I!fv='+cval;
       	saver(data);
	";

	$closeFx=",onClose:function(){".$closeCode."}";

	$dppx="$('.paydate').each(function() {
	  $(this).datepicker({
	    inline: true,dateFormat: 'dd/mm/yy',altFormat: 'yy-mm-dd',
	    altField: $(this).closest('tr').find('input[name=fakepaydate]')
	    $closeFx
  	  });
	});";
        //ANK
        #STB- back in untill implement link to compliant doc date
	$this->jcallA[]=$dppx;
        // end ANK

	#$ist->input_fields["glchart"]="selectStatic";
	#$ist->input_jcall["glchartid"]="onchange=jrqSaver";
	#$ist->ajaxData["glchartid"]=kda("poref");
	$fn="glchartid";
	$ist->input_fields[$fn]="text";
	$ist->colClassText[$fn]="jrqChange";

	$ist->input_fields["glchartid"]="select";
	$ist->input_fields["syncbase"]="hidden";

	$this->glConditions($jobid);
	$gq="select glchartid, concat(glchartid,' ',glchartdesc) as glchartdesc from glchart
	where glgroupdesc='expenses' $this->glcondx";
	$gtf=kda("glchartid,glchartdesc");
	$dca=iqa($gq,$gtf);

	$gcca["not-to-be-migrated"]="not-to-be-migrated";
	foreach($dca as $row) {
		$rg=$row["glchartid"];
		$rd=$row["glchartdesc"];
		#echo "alert('$rg $rd')\n";
		$gcca[$rg]=$rd;
	}
	$ist->selectOptions["glchartid"]=$gcca;

	$ist->input_fields["cost"]="text";

	#superceded
	#$ist->input_jcall["cost"]="onchange=jrqSaver";
	#$ist->colClass["cost"]="jrqcost";
	#$ist->ajaxData["cost"]=kda("poref");

	$fn="cost";
	$ist->input_fields[$fn]="text";
	#$ist->colClassText[$fn]="jrqChange";
	$ist->curValAttr[$fn]="true";
	$ist->colClassText[$fn]="jrqcost";

	#$ist->input_jcall["invoiceref"]="onchange=jrqSaver";
	#$ist->ajaxData["invoiceref"]=kda("poref");
	$fn="invoiceref";
	$ist->input_fields[$fn]="text";
	$ist->colClassText[$fn]="jrqChange";

	#??? jq date saving...leave for moment..stbreview
	#$fn="invoicedate";
	#$ist->colClassText[$fn]="jrqChange";

	$ist->total_fields=kda("cost");

	$jax=$this->ja["attachments"];
	$ata=unserialize($jax);

	$title="Job Request Action Log ";

	#<i>Optional Docs:</i> <form name=attachmentsdcfmwo07J>";
	#$title.="<br><font color=#999;>MYOB sync'd read only</font>";
	#$title="<br><div style='color:#999;'>MYOB sync'd read only</div>";
	$title.="<br>MYOB sync'd read only";
	/*if(isset($this->doca)){
		foreach($this->doca as $lab=>$docn){
			$flag=$ata['dcfmwo07J'][$docn];
			unset($ckx);
			if($flag) $ckx="checked";
			$title.="<input type=checkbox name=dcfmwo07J$docn $ckx onclick=saveAttach('$docn','dcfmwo07J');><i>$lab &nbsp;</i>";
		}
	}
	*/
	/*
	$contrx="<br><b>Dynamic Forms</b>";
	#load contractor forms.
	$dtype="dcfmwo07J";
	$contrx.=$this->loadContractorForms($dtype);
	$title.=$contrx;
	$title.="</form>";
	*/

#return "rx 5307";
#determined that glchartid blows memory on compliance jobs
#rule for moment is that if data rows>20, don't display GL
#introduced pagination instead, so can handle it
#if(sizeof($pa)>20) unset($tf["glchartid"]);

	$ist->tableid="jrqList";




	if(sizeof($pa)<$perPage) $ist->paginate=false;



	if(sizeof($pa)>0) $jrqlog=$ist->inf_sortable($tf,$pa,$title,null,null,true);
	foreach($ist->jcallA as $icall) $this->jcallA[]=$icall;
	#$this->jcallA[]="alert('ok')";
	$nx.="<div id=jrqlog>$jrqlog</div>";
	//tfw("jrq.txt",$nx,true);
	#elog("n $nx");
	if($this->returnText) return $nx;
	if($this->noClean){
		$cx=$nx;
	}else{
		$cx=vClean($nx);
	}

	#$tjx="alert('pok')\n;";
	if($qlimit=="") $qlimit=0;
	$qvtest="$('.jrqcost').change(function(){

		//alert('test');
		var row=$(this).closest('tr');
		var rfq=row.find('input[name=rfqno]').val()
		var qval=round(row.find('input[name=qval]').val(),2)
		var cval=round($(this).val(),2);
		var lno=row.find('input[name=rowc]').val()
		var poref=row.find('input[name=poref]').val()
		var fail=false;



		 //summ all rfq
		 var tqval=0;
		 $('#rfqList input[name=accept]:checked').each(function(){
		 var row = $(this).parents('tr');
		 	var rfqv=round(row.find('input[name=qval]').val(),2);
			tqval+=round(rfqv,2);
			//alert(rfqv+' '+tqval);
		 });

		//summ all jrq
		 var tcval=0;
		 $('#jrqList input[name=accepted]:checked').each(function(){
		 var row = $(this).parents('tr');
		 	var cv=round(row.find('input[name=cost]').val(),2);
			tcval+=round(cv,2);
			//alert('cv '+cv);
		 });
		//alert(' tcv '+tcval);

		 var qlim=50;
		 //alert('qlim '+qlim);
		 if(tqval==0){
		 	var qtype='internal limit'
		 	tqval=$qlimit;
		 }else{
		 	var qtype='quoted'
		 }

		if(tqval>0){
			//alert('tqv '+tqval+' vs '+tcval);
			if(tcval>tqval){
				var max=round(tqval-(tcval-cval),2);
				//alert('This change would result in total accepted costs '+tcval+' exceeding total '+qtype+' value of '+tqval+'. Maximum would be '+max);
				//fail=true;
				$(this).val(0);
				jqml('dialogGeneric','rfqExceed','dialogGeneric','!I!poref='+poref+'!I!rfqno='+rfq+'!I!qval='+qval+'!I!cval='+cval+'!I!tqval='+tqval+'!I!tcval='+tcval)

			}
			else
			{
			var max=round(tqval-(tcval-cval),2);
				if(rfq!='')
				{
				//$(this).val(0);
				jqml('dialogGeneric','rfqClose','dialogGeneric','!I!poref='+poref+'!I!rfqno='+rfq+'!I!qval='+qval+'!I!cval='+cval)
				}
			}

		 }



		//alert(cval+' on po: '+lno);
		//if(rfq!=''){
		if(rfq>0){
		//alert('test '+cval+' vs  '+qval);
		//alert('testing change to cost '+cval+' on rfq:'+rfq+' '+qval);

			if(cval>qval){
				//alert('Cost '+cval+' exceeds quoted value of '+qval+' on rfq:'+rfq);
				jqml('dialogGeneric','rfqExceed','dialogGeneric','!I!poref='+poref+'!I!rfqno='+rfq+'!I!qval='+qval+'!I!cval='+cval)
				$(this).val(qval);
				fail=true;
			}
		}

		if(!fail){
			//alert('Cost '+cval+' is ok');
			jrqSaver(lno);
		}else{
			//alert('failed');
		}


	});\n";
	#$this->djcallA[]=$tjx;

	$pod="$('.podelete').click(function(){
		//alert('kill me');
		var row=$(this).closest('tr');
		var poref=row.find('input[name=poref]').val()
		var inc= row.find('input[name=podelete]').is(':checked');
		if(inc)	jqml('dialogGeneric','poDelete','dialogGeneric','!I!jobid=$jobid!I!poref='+poref);
	});";

	$this->djcallA[]=$qvtest;
	$this->djcallA[]=$pod;




$hovCall="
  //$('#divTable').tablesorter();
  $('#divTable').dataTable();
  $('.div-tip').cluetip({
     hoverClass: 'highlight',
     sticky: true,
     closePosition: 'top',
     width: '800',
     closeText: '<img src=\'/infbase/images/icons/alerts/error.gif\' alt=close>',
     ajaxSettings: {
       type: 'POST'
     }
  });";
  	#unset($this->jcallA);
  	foreach($this->jcallA as $i=>$jcl){
  		//used to spot offending jcalls if broken
  		if($i==44){
  		//elog("bad jc $jcl","cinf 5793");
  		//unset($this->jcallA[$i]);
  		}
  	}
  	$this->jcallA[]=$hovCall;




	if($this->viaAjxLog){
		$this->logx=$cx;
		return $cx;
	}
	echo "g('eaLog').innerHTML='$cx'\n";
}


function displayExternalLog_oldPreSplit($jobid){
	#return;
	###rfq
	$q = "select rfqno,'rfq' as atype,rfqdate as date,qval,margin,actualcost,supplierid,c.companyname,r.userid,
	responded,responsedate,replied,replydate,apply,closed,supplierqref,accepted as accept
	from rfq as r
	left outer join customer as c
	on r.supplierid=c.customerid
	where jobid=$jobid";
	$tf = kda("rfqno,atype,companyname,date,userid,responded,responsedate,replied,replydate,supplierqref,qval,margin,apply,accept,closed,actualcost,delete,supplierid,startprogress");
	$tf["replied"] = "Quoted";
	$tf["qval"] = "$ Quote (Ex GST)";
	$tf["margin"] = "$ Margin";
	$tf["supplierqref"] = "Qte No.";
	$tf["responsedate"] = "Resp.Dt.";
	$tf["replydate"] = "Qte.Dt.";
	$tf["startprogress"] = "Progress";
	$da = iqa($q,$tf);

	$aNewArray = array();

	foreach($da as $row){
		$this->logIt("get rfq progress");
		$progress = $this->getSafetyComplianceProgress($jobid, $row['rfqno'], $row['poref']);
		if($progress){
			$row['startprogress'] = $progress['start'].'% start';
		}
		unset($progress);
		$aNewArray[] = $row;
	}
	$da = $aNewArray;
	unset($aNewArray);

	$ist=new divTable();
	$ist->tableidx=$this->tablestyle;

	$fn="atype";

	$fn="atype";

	# breaking bad jquery
	#		$ist->multilink[$fn][1]=array("ajaxHrefIconMulti");
	#		$ist->ajaxDataM[$fn][1]=kda("rfqno");
	#		$ist->ajaxStaticDataM[$fn][1]=kda("divid=dialogRfqMailer!I!tn=rfqMailer!I!title=RFQ!I!jobid=$jobid");
	#		$ist->ajaxFnameM[$fn][1]="jqUiModalExisting";
	#		$ist->iconM[$fn][1]="eml";

	#go via customised function first to build PDF
	$params.="!I!fid=dcfmrfq07J!I!prefix=rfq!I!folder=rfq";
	$ist->ajaxStaticDataM[$fn][1]=kda("!I!divid=dialogRfqMailer!I!tn=rfqMailer!I!title=Rfq!I!jobid=$jobid$params");
	$ist->ajaxFnameM[$fn][1]="jrqPdfEmail";

	$ist->colClass["startprogress"] = "progress clue";

	//Add cluetip icon on progress column to show safety compliance progress
	$ist->multilink["startprogress"][1]=array("hrefClueTipMulti");
	$ist->ajaxFnameM["startprogress"][1]="previewSafety.php?jobid=$jobid";
	$ist->ajaxDataM["startprogress"][1]=kda("rfqno");
	$ist->iconM["startprogress"][1]="news";
	$ist->iconM["startprogress"]["iconTrails"]=true;
	$ist->noLinkIfBlank["startprogress"][1]=true;

	/*
		#superceded by UI email
		$ist->multilink["atype"][2]=array("ajaxHrefIconMulti");
			$caption="Mail&nbsp;Job&nbsp;Note";
			$ist->multilink["atype"][2]=array("ajaxHrefIconMulti");
			$ist->ajaxDataM["atype"][2]=kda("rfqno,supplierid");

			$ist->multilink["atype"][2]=array("ajaxHrefIconMulti");
			$url="../customscript/jobAttachments.php?rn=1&doctype=rfq&jobid=$jobid&nid=";
			$imageGroup="x";
			$ist->ajaxStaticDataM[$fn][2]=kda("$caption,$imageGroup,$url");
			$ist->ajaxFnameM[$fn][2]="thickBoxDual";
			$ist->iconM[$fn][2]="p";
	*/

	#more breaking bad
	/*
	$ist->multilink["atype"][3]=array("ajaxHrefIconMulti");
			$ist->ajaxFnameM[$fn][3]="idoc";;
		    $ist->ajaxStaticDataM[$fn][3]=kda("dcfmrfq07J");;
		  	$ist->ajaxDataM[$fn][3]=kda("rfqno");
		    $ist->iconM[$fn][3]="e";
		    $ist->iconM[$fn][3]="p";
	*/

	$ist->multilink[$fn][1]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][1]="rfqEml";
	$ist->input_fields["rfqno"]="hpd";
	$ist->iconM[$fn][1]="eml";

	#$ist->multilink[$fn][2]=array("ajaxHrefIconMultiJq");
	#$ist->colClassM[$fn][2]="rfqPdf";
	#$ist->input_fields["rfqno"]="hpd";
	#$ist->iconM[$fn][2]="pdf";

	$ist->multilink[$fn][2]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][2]="rfqDoc";
	$ist->input_fields["rfqno"]="hpd";
	$ist->iconM[$fn][2]="p";

    $ist->multilink[$fn][3]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][3]="rfqCompliance";
	$ist->input_fields["rfqno"]="hpd";
	$ist->iconM[$fn][3]="v";

    $ist->multilink[$fn][4]=array("jqButton");
	$ist->colClassM[$fn][4]="rfqEml";
	$ist->buttonClass[$fn]="infoRq";
	$ist->input_fields["rfqno"]="hpd";
	$ist->jbuttondesc[$fn]="RFQ Info Rq.";


	$fn="companyname";
	$ist->multilink[$fn][1]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][1]="companyEdit";
	$ist->input_fields["supplierid"]="hpd";
	$ist->iconM[$fn][1]="v";




	$ist->noCheckAll["responded"]=true;
	$ist->noCheckAll["replied"]=true;

	#superceded by jquery
	#$ist->input_jcall["responded"]="onclick=rfqResponded";
	$ist->input_fields["responded"]="checkboxJq";
	$ist->ajaxData["responded"]=kda("rfqno");
	$fn="responded";
	$ist->ckboxClass[$fn]=$fn;

	#superceded by jquery
	#$ist->input_jcall["replied"]="onclick=rfqReceived";
	$ist->input_fields["replied"]="checkboxJq";
	$ist->ajaxData["replied"]=kda("rfqno");
	$fn="replied";
	$ist->ckboxClass[$fn]=$fn;

	#superceded by jquery
	#$ist->input_jcall["apply"]="onclick=rfqApply";
	#$ist->ajaxData["apply"]=kda("rfqno");
	$fn="apply";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="rfqapply";
	$ist->noCheckAll["apply"]=true;

	#superceded by jquery
	#$ist->input_jcall["accept"]="onclick=rfqAccept";
	#$ist->ajaxData["accept"]=kda("rfqno");
	$ist->input_fields["accept"]="checkboxJq";
	$fn="accept";
	$ist->ckboxClass[$fn]="rfqaccept";
	$ist->noCheckAll[$fn]=true;



	#superceded by jquery
	#$ist->input_jcall["delete"]="onclick=rfqDelete";
	#$ist->ajaxData["delete"]=kda("rfqno");
	$ist->input_fields["delete"]="checkboxJq";
	$fn="delete";
	$ist->ckboxClass[$fn]="rfqdel";
	$ist->noCheckAll[$fn]=true;

	#saveRfqval not a function??
	$ist->input_fields["qval"]="text";
	$ist->colClassText["qval"]="qvalc";
	#$ist->input_jcall["qval"]="onchange=saveRfqval";

	$ist->input_fields["margin"]="text";
	$ist->colClassText["margin"]="marginc";
	#$ist->input_jcall["margin"]="onchange=saveRfqval";

	$ist->input_fields["supplierqref"]="text";
	$ist->colClassText["supplierqref"]="sqref";

	#superceded by jquery
	#$ist->input_jcall["closed"]="onclick=rfqClose";
	#$ist->ajaxData["closed"]=kda("rfqno");
	$ist->input_fields["closed"]="checkboxJq";
	$fn="closed";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="rfqclose";
	$ist->noCheckAll[$fn]=true;

	$ist->entrymode=true;

	$ist->input_fields["actualcost"]="text";
	$ist->colClassText["actualcost"]="acost";

	$ist->total_fields=kda("qval,margin");


	$jax=$this->ja["attachments"];
	$ata=unserialize($jax);

	$title="RFQ Action Log";

	/*
	#now handled via thickbox jobAttachments
	$title="RFQ Action Log   <i>Optional Docs:</i> <form name=attachmentsdcfmrfq07J>";
	$this->optionalDocs();

	foreach($this->doca as $lab=>$docn){
		$flag=$ata['dcfmrfq07J'][$docn];
		unset($ckx);
		if($flag) $ckx="checked";
		$title.="<input type=checkbox name=dcfmrfq07J$docn $ckx onclick=saveAttach('$docn','dcfmrfq07J');><i>$lab &nbsp;</i>";
	}
	*/

	/*$contrx.="<br><b>Dynamic Forms</b>";
	#load contractor forms.
	$dtype="dcfmrfq07J";
	$contrx.=$this->loadContractorForms($dtype);
	$title.=$contrx;
	$title.="</form>";
	*/

	$ist->tableid="rfqList";

	if(sizeof($da)>0)	$nx=$ist->inf_sortable($tf,$da,$title,null,null,true);

//	tfw("dexl.txt",$nx,true);

	##auto update safety for flagged suppliers
	/* ANK 29.11.2013 Disabled this as it is taken care of elsewhere in reminders and overwrites the safety flag
	$safeq="update purchaseorders as p	left outer join customer as c
	on p.supplierid=c.customerid set p.safety='on'
	where c.customtext3='on'";
	mysql_query($safeq);


	$safeq="update purchaseorders as p	left outer join customer as c
	on p.supplierid=c.customerid set p.safety='on'
	where c.customtext3='on'";
	mysql_query($safeq);
	*/

	###po
	$pq="select r.poref,'jrq' as atype,podate as date,r.supplierid,c.companyname,r.rfqno,rq.qval,
	invoiceref,r.cost,completed as pocompleted,completedate as pocompletedate,r.responded as poresponded,
	r.responsedate,r.acceptdate,exceed,safety,r.varianceValue,
	r.materialjobid,r.custordref,r.userid,r.accepted,glchartid,invoicedate,paydate,r.syncdate,0 as syncbase,j.internalqlimit


	from purchaseorders as r
	left outer join customer as c
	on r.supplierid=c.customerid
	left outer join rfq as rq
	on r.rfqno=rq.rfqno
	left outer join jobs as j
	on r.jobid=j.jobid
	where r.jobid=$jobid
	order by poref desc limit 2000

	";
	$tf=kda("supplierid,poref,atype,companyname,date,poresponded,responsedate,userid,accepted,acceptdate,invoiceref,invoicedate,paydate,cost,glchartid,pocompleted,pocompletedate,exceed,safety,podelete,syncdate,syncbase");
	$tf=kda("supplierid,poref,rfqno,qval,atype,companyname,date,poresponded,responsedate,userid,accepted,acceptdate,invoiceref,custordref,invoicedate,paydate,cost,varianceValue,glchartid,pocompleted,pocompletedate,taskcompleted,exceed,safety,progress,podelete,syncdate,syncbase,internalqlimit");
	#$tf["poresponded"]="Responded";
	$tf["cost"]="$ Cost (Ex GST)";
	$tf["varianceValue"]="$ Var Req.";
	$tf["podelete"]="Delete";
	$tf["responsedate"]="Resp.Dt.";
	$tf["acceptdate"]="Accept Dt.";
	$tf["exceed"]="$ > Limit ";
	$tf["safety"]="Safety OK";
	$tf["taskcomplete"]="Task Complete";
	$tf["materialjobid"]="Material Job";
	$tf["custordref"]="Material Job";
	$tf["pocompletedate"]="Comp. Dt.";

	unset($tf["poresponded"]);
	unset($tf["responsedate"]);

	$pa=iqa($pq,$tf);
	if(isset($pa)){
		foreach($pa as $row){
			$qlimit=$row["internalqlimit"];
			$progress = $this->getSafetyComplianceProgress($jobid, $row['rfqno'], $row['poref']);
			if($progress){
				$row['progress'] = $progress['start'].'% start<br />'.$progress['complete'].'% complete';
			}
			unset($progress);
			$aNewArray[] = $row;
		}
	}

	$pa = $aNewArray;
	unset($aNewArray);

	unset($tf["internalqlimit"]);

	$ist=new divTable();
	$ist->tableidx=$this->tablestyle;
	$ist->datefields=kda("date,responsedate,acceptdate,pocompletedate,syncdate");

	$ist->colClass["progress"] = "progress clue";

	//Add cluetip icon on progress column to show safety compliance progress
	$ist->multilink["progress"][1]=array("hrefClueTipMulti");
	$ist->ajaxFnameM["progress"][1]="previewSafety.php?mode=jrq&jobid=$jobid";
	$ist->ajaxDataM["progress"][1]=kda("poref");
	$ist->iconM["progress"][1]="news";
	$ist->iconM["progress"]["iconTrails"]=true;
	$ist->noLinkIfBlank["progress"][1]=true;

/*	$ist->multilink["progress"][0]=array("hrefClueTipMulti");
	$ist->ajaxFnameM["progress"][0]="previewSafety.php?mode=jrq&jobid=$jobid";
	$ist->ajaxDataM["progress"][0]=kda("poref");
	$ist->iconM["progress"][0]="jb";
	#$ist->iconM["progress"]["iconTrails"]=true;
	#$ist->noLinkIfBlank["progress"][1]=true;
*/


	$stylecall="style=color:white;background-color:#999;";
	$ist->cssPreAnalyseNeeded=true;

	$ist->cssEntireRow["syncdate"]=true;
	$ist->cssNumericComparison["syncdate"]["left"][]="syncdate";
	$ist->cssNumericComparison["syncdate"]["right"][]="syncbase";
	$ist->cssNumericComparison["syncdate"]["operator"]="<";
	$ist->cssNumericComparison["syncdate"]["stylecall"]=$stylecall;
	$ist->cssCondRowLocking=true;


	$fn="atype";

#breaking bad
#	$ist->multilink[$fn][1]=array("ajaxHrefIconMulti");
#	$ist->ajaxDataM[$fn][1]=kda("poref");
	#$ist->ajaxFnameM[$fn][1]="jqUiModalExisting";

	$ist->multilink[$fn][1]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][1]="jrqEml";
	$ist->input_fields["poref"]="hpd";
	$ist->iconM[$fn][1]="eml";

	#$ist->multilink[$fn][2]=array("ajaxHrefIconMultiJq");
	#$ist->colClassM[$fn][2]="jrqPdf";
	#$ist->input_fields["rfqno"]="hpd";
	#$ist->iconM[$fn][2]="pdf";

	$ist->multilink[$fn][2]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][2]="jrqDoc";
	$ist->input_fields["jrqno"]="hpd";
	$ist->iconM[$fn][2]="p";

    $ist->multilink[$fn][3]=array("ajaxHrefIconMultiJq");
	$ist->colClassM[$fn][3]="jrqCompliance";
	$ist->iconM[$fn][3]="v";

    $ist->multilink[$fn][4]=array("jqButton");
	$ist->colClassM[$fn][4]="rfqEml";
	$ist->buttonClass[$fn]="infoRqJrq";
	$ist->input_fields["rfqno"]="hpd";
	$ist->jbuttondesc[$fn]="Info Rq.";



	#go via customised function first to build PDF
	$rparams.="!I!fid=dcfmwo07J!I!prefix=jrq!I!folder=jrq";
	$ist->ajaxStaticDataM[$fn][1]=kda("!I!divid=dialogJrqMailer!I!tn=jrqMailer!I!title=Address!I!jobid=$jobid$rparams");
	$ist->ajaxFnameM[$fn][1]="jrqPdfEmail";

	$ist->iconM[$fn][1]="eml";

	#breaking bad
	#		$ist->multilink["atype"][3]=array("ajaxHrefIconMulti");
	#		$ist->ajaxFnameM[$fn][3]="idoc";;
	#	    $ist->ajaxStaticDataM[$fn][3]=kda("dcfmwo07J");;
	#	  	$ist->ajaxDataM[$fn][3]=kda("poref");
	#	    $ist->iconM[$fn][3]="e";
	#	    $ist->iconM[$fn][3]="p";

	$ist->entrymode=true;
	$ist->input_fields["poref"]="hpd";
	$ist->input_fields["rfqno"]="hpd";
	$ist->input_fields["qval"]="hidden";
	$ist->input_fields["supplierid"]="hidden";

	#superceded by jquery
	#$ist->input_jcall["poresponded"]="onclick=jrqResponded";
	#$ist->input_fields["poresponded"]="checkbox";
	#$ist->ajaxData["poresponded"]=kda("poref");
	#seems not in use
	#$fn="poresponded";
	#$ist->input_fields[$fn]="checkboxJq";
	#$ist->ckboxClass[$fn]="jrqrespond";
	#$ist->noCheckAll[$fn]=true;

	#superceded by jquery
	#$ist->input_jcall["accepted"]="onclick=jrqAccepted";
	#$ist->input_fields["accepted"]="checkbox";
	#$ist->ajaxData["accepted"]=kda("poref");
	$fn="accepted";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="jrqaccept";
	$ist->noCheckAll[$fn]=true;

	#superceded by jquery
	#$ist->input_jcall["pocompleted"]="onclick=jrqCompleted";
	#$ist->input_fields["pocompleted"]="checkbox";

	//ANK Disable jrqcomplete
	#$fn="pocompleted";
	#$ist->input_fields[$fn]="checkboxJq";
	#$ist->ckboxClass[$fn]="jrqComplete";
	#$ist->noCheckAll[$fn]=true;
	//End ANK

	$ist->ajaxData["pocompleted"]=kda("poref");

	$fn="taskcompleted";
	$ist->linka[$fn]=array("ajaxButton"=>"hx");
	#$ist->linka["desc"]=array("ajaxHrefIcon"=>"hx");

	#$ist->ajaxFname[$fn]="jrqTaskCompleted";;
  	#$ist->ajaxData[$fn]=kda("poref,jobid");
	$ist->jbuttondesc["taskcompleted"]="done";
	$ist->buttonClass[$fn]="jTC";

	$ist->colClass["materialjobid"]="mjobid";
	$ist->colClass["custordref"]="coref";
	$ist->colClass["poref"]="poref";
	$mjx="$('.coref').click(function(){
		var row=$(this).closest('tr');
		var poref= row.find('input[name=poref]').val();
		var customerid= row.find('input[name=customerid]').val();
		var customerid= $('#customerid').val();
		jmg('materialJobSearch','!I!poref='+poref+'!I!customerid='+customerid);
	});";



	$this->jcallA[]=$mjx;

	#  $ist->iconM[$fn][0]="e";
	#  $ist->iconM[$fn][0]="p";

	/*
		$ist->linkinput_fields["taskcompleted"]="ajaxButton";
		$ist->ajaxData["taskcompleted"]=kda("poref");
		$ist->ajaxFnameM["taskcompleted"]="jrqTaskCompleted";
		$ist->jbuttondesc["taskcompleted"]="done";
	*/

	#superceded by jquery
	#$ist->input_jcall["safety"]="onclick=jrqCheckBoxVal";
	#$ist->input_fields["safety"]="checkbox";
	#$ist->ajaxStaticData["safety"]=kda("safety");
	#$ist->ajaxData["safety"]=kda("poref");

	$fn="safety";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="safety";
	$ist->noCheckAll[$fn]=true;

	#superceded by jquery
	#$ist->input_jcall["exceed"]="onclick=jrqCheckBoxVal";
	#$ist->input_fields["exceed"]="checkbox";
	#$ist->ajaxStaticData["exceed"]=kda("exceed");
	#$ist->ajaxData["exceed"]=kda("poref");

	$fn="exceed";
	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="safety";
	$ist->noCheckAll[$fn]=true;

	#superceded by jquery
	#$ist->input_jcall["podelete"]="onclick=jrqDelete";
	#$ist->input_fields["podelete"]="checkbox";
	#$ist->colClass["podelete"]="podelete";
	#$ist->ajaxData["podelete"]=kda("poref");


	$fn="podelete";
/*	$ist->input_fields[$fn]="checkboxJq";
	$ist->ckboxClass[$fn]="podel";
	$ist->noCheckAll[$fn]=true;
*/
	#$ist->input_fields[$fn]="checkboxJq";
	$ist->linka[$fn]=array("ajaxButton"=>"hx");
	$ist->jbuttondesc[$fn]="Delete";
	$ist->buttonClass[$fn]="podel";




	$ist->input_fields["invoiceref"]="text";
	#$ist->input_fields["invoicedate"]="date_ClickCal";
	$ist->input_fields["invoicedate"]="uiDatePicker";
	$fn="invoicedate";
	$ist->dtClass[$fn]="invdate";
	$closeCode="var cn=$(this).attr('name');
   	var row=$(this).closest('tr');
	var cval=row.find('input[name=fakeinvoicedate]').val();
	var poref=row.find('input[name=poref]').val();
   	var data='params=!I!idn=poref!I!idv='+poref+'!I!mode=saveField!I!url=rfqJrqHandler!I!tn=purchaseorders!I!fn=invoicedate!I!fv='+cval;
       	saver(data);
	";

	$closeFx=",onClose:function(){".$closeCode."}";
	#$sugcall="$('.invdate').datepicker({inline: true,dateFormat: 'dd/mm/yy',altField: '.fakeinvoicedate',altFormat: 'yy-mm-dd' $closeFx });";
	#$sugcall="$('.invdate').datepicker({inline: true,dateFormat: 'dd/mm/yy',altFormat: 'yy-mm-dd' $closeFx });";
	#$this->jcallA[]=$sugcall;

	$dpx="$('.invdate').each(function() {
	  $(this).datepicker({
	    inline: true,dateFormat: 'dd/mm/yy',altFormat: 'yy-mm-dd',
	    altField: $(this).closest('tr').find('input[name=fakeinvoicedate]')
	    $closeFx

	  });
	});";
	$this->jcallA[]=$dpx;

	#$ist->input_fields["paydate"]="date_ClickCal";
	$fn="paydate";
	$ist->dtClass[$fn]="paydate";
	$ist->input_fields[$fn]="uiDatePicker";
	$closeCode="var cn=$(this).attr('name');
   	var row=$(this).closest('tr');
	var cval=row.find('input[name=fakepaydate]').val();
	var poref=row.find('input[name=poref]').val();
   	var data='params=!I!idn=poref!I!idv='+poref+'!I!mode=saveField!I!url=rfqJrqHandler!I!tn=purchaseorders!I!fn=paydate!I!fv='+cval;
       	saver(data);
	";

	$closeFx=",onClose:function(){".$closeCode."}";

	$dppx="$('.paydate').each(function() {
	  $(this).datepicker({
	    inline: true,dateFormat: 'dd/mm/yy',altFormat: 'yy-mm-dd',
	    altField: $(this).closest('tr').find('input[name=fakepaydate]')
	    $closeFx
  	  });
	});";
	$this->jcallA[]=$dppx;

	#$ist->input_fields["glchart"]="selectStatic";
	#$ist->input_jcall["glchartid"]="onchange=jrqSaver";
	#$ist->ajaxData["glchartid"]=kda("poref");
	$fn="glchartid";
	$ist->input_fields[$fn]="text";
	$ist->colClassText[$fn]="jrqChange";

	$ist->input_fields["glchartid"]="select";
	$ist->input_fields["syncbase"]="hidden";

	$this->glConditions($jobid);
	$gq="select glchartid, concat(glchartid,' ',glchartdesc) as glchartdesc from glchart
	where glgroupdesc='expenses' $this->glcondx";
	$gtf=kda("glchartid,glchartdesc");
	$dca=iqa($gq,$gtf);

	$gcca["not-to-be-migrated"]="not-to-be-migrated";
	foreach($dca as $row) {
		$rg=$row["glchartid"];
		$rd=$row["glchartdesc"];
		#echo "alert('$rg $rd')\n";
		$gcca[$rg]=$rd;
	}
	$ist->selectOptions["glchartid"]=$gcca;

	$ist->input_fields["cost"]="text";

	#superceded
	#$ist->input_jcall["cost"]="onchange=jrqSaver";
	#$ist->colClass["cost"]="jrqcost";
	#$ist->ajaxData["cost"]=kda("poref");

	$fn="cost";
	$ist->input_fields[$fn]="text";
	#$ist->colClassText[$fn]="jrqChange";
	$ist->colClassText[$fn]="jrqcost";
	$ist->curValAttr[$fn]="true";

	#$ist->input_jcall["invoiceref"]="onchange=jrqSaver";
	#$ist->ajaxData["invoiceref"]=kda("poref");
	$fn="invoiceref";
	$ist->input_fields[$fn]="text";
	$ist->colClassText[$fn]="jrqChange";

	#??? jq date saving...leave for moment..stbreview
	#$fn="invoicedate";
	#$ist->colClassText[$fn]="jrqChange";

	$ist->total_fields=kda("cost");

	$jax=$this->ja["attachments"];
	$ata=unserialize($jax);

	$title="Job Request Action Log ";

	#<i>Optional Docs:</i> <form name=attachmentsdcfmwo07J>";
	#$title.="<br><font color=#999;>MYOB sync'd read only</font>";
	#$title="<br><div style='color:#999;'>MYOB sync'd read only</div>";
	$title.="<br>MYOB sync'd read only";
	/*if(isset($this->doca)){
		foreach($this->doca as $lab=>$docn){
			$flag=$ata['dcfmwo07J'][$docn];
			unset($ckx);
			if($flag) $ckx="checked";
			$title.="<input type=checkbox name=dcfmwo07J$docn $ckx onclick=saveAttach('$docn','dcfmwo07J');><i>$lab &nbsp;</i>";
		}
	}
	*/
	/*
	$contrx="<br><b>Dynamic Forms</b>";
	#load contractor forms.
	$dtype="dcfmwo07J";
	$contrx.=$this->loadContractorForms($dtype);
	$title.=$contrx;
	$title.="</form>";
	*/
if(sizeof($pa)>20) unset($tf["glchartid"]);

	$ist->tableid="jrqList";
	if(sizeof($pa)>0) $nx.=$ist->inf_sortable($tf,$pa,$title,null,null,true);
	//tfw("jrq.txt",$nx,true);
	if($this->returnText) return $nx;
	if($this->noClean){
		$cx=$nx;
	}else{
		$cx=vClean($nx);
	}

	#$tjx="alert('pok')\n;";
	if($qlimit=="") $qlimit=0;
	$qvtest="$('.jrqcost').change(function(){

		//alert('test');
		var row=$(this).closest('tr');
		var rfq=row.find('input[name=rfqno]').val()
		var qval=round(row.find('input[name=qval]').val(),2)
		var cval=round($(this).val(),2);
		var lno=row.find('input[name=rowc]').val()
		var poref=row.find('input[name=poref]').val()
		var fail=false;



		 //summ all rfq
		 var tqval=0;
		 $('#rfqList input[name=accept]:checked').each(function(){
		 var row = $(this).parents('tr');
		 	var rfqv=round(row.find('input[name=qval]').val(),2);
			tqval+=round(rfqv,2);
			//alert(rfqv+' '+tqval);
		 });

		//summ all jrq
		 var tcval=0;
		 $('#jrqList input[name=accepted]:checked').each(function(){
		 var row = $(this).parents('tr');
		 	var cv=round(row.find('input[name=cost]').val(),2);
			tcval+=round(cv,2);
			//alert('cv '+cv);
		 });
		//alert(' tcv '+tcval);

		 var qlim=50;
		 //alert('qlim '+qlim);
		 if(tqval==0){
		 	var qtype='internal limit'
		 	tqval=$qlimit;
		 }else{
		 	var qtype='quoted'
		 }

		if(tqval>0){
			//alert('tqv '+tqval+' vs '+tcval);
			if(tcval>tqval){
				var max=round(tqval-(tcval-cval),2);
				//alert('This change would result in total accepted costs '+tcval+' exceeding total '+qtype+' value of '+tqval+'. Maximum would be '+max);
				//fail=true;
				$(this).val(0);
				jqml('dialogGeneric','rfqExceed','dialogGeneric','!I!poref='+poref+'!I!rfqno='+rfq+'!I!qval='+qval+'!I!cval='+cval+'!I!tqval='+tqval+'!I!tcval='+tcval)

			}
			else
			{
			var max=round(tqval-(tcval-cval),2);
				if(rfq!='')
				{
				//$(this).val(0);
				jqml('dialogGeneric','rfqClose','dialogGeneric','!I!poref='+poref+'!I!rfqno='+rfq+'!I!qval='+qval+'!I!cval='+cval)
				}
			}

		 }



		//alert(cval+' on po: '+lno);
		//if(rfq!=''){
		if(rfq>0){
		//alert('test '+cval+' vs  '+qval);
		//alert('testing change to cost '+cval+' on rfq:'+rfq+' '+qval);

			if(cval>qval){
				//alert('Cost '+cval+' exceeds quoted value of '+qval+' on rfq:'+rfq);
				jqml('dialogGeneric','rfqExceed','dialogGeneric','!I!poref='+poref+'!I!rfqno='+rfq+'!I!qval='+qval+'!I!cval='+cval)
				$(this).val(qval);
				fail=true;
			}
		}

		if(!fail){
			//alert('Cost '+cval+' is ok');
			jrqSaver(lno);
		}


	});\n";
	#$this->djcallA[]=$tjx;

	$pod="$('.podelete').click(function(){
		//alert('kill me');
		var row=$(this).closest('tr');
		var poref=row.find('input[name=poref]').val()
		var inc= row.find('input[name=podelete]').is(':checked');
		if(inc)	jqml('dialogGeneric','poDelete','dialogGeneric','!I!jobid=$jobid!I!poref='+poref);
	});";

	$this->djcallA[]=$qvtest;
	$this->djcallA[]=$pod;




$hovCall="
 // $('#divTable').tablesorter();
  $('#divTable').dataTable();
  $('.div-tip').cluetip({
     hoverClass: 'highlight',
     sticky: true,
     closePosition: 'top',
     width: '800',
     closeText: '<img src=\'/infbase/images/icons/alerts/error.gif\' alt=close>',
     ajaxSettings: {
       type: 'POST'
     }
  });";
  	$this->jcallA[]=$hovCall;




	if($this->viaAjxLog){
		$this->logx=$cx;
		return $cx;
	}
	echo "g('eaLog').innerHTML='$cx'\n";
}

function loadContractorForms($dtype,$supplierid=null){
	$q="select activeprofiledesc from activeprofile where profiletable='customer'";
	$tf=key2val(tfa("activeprofile"));
	$tf=kda("activeprofiledesc");
	$da=iqa($q,$tf);
	$custid=$this->custid;
	$sda=sizeof($da);
	tfw("sda.txt","sz $sda",true);


	#preload completed forms - actually needs to be on a contractor by contractor basis
	if($supplierid>0){
		$pq="select * from profileitem where linkfield='customer' and xrefno=$supplierid";
		$this->qq=$pq;
		$ptf=kda("profiletable,date");
		$pa=iqa($pq,$ptf);
		$ptA=aFV($pa,"profiletable");
		if(sizeof($ptA>0)){
		$this->compPta=$ptA;
		$this->completedFormx=implode(" - ",$ptA);
		}
	}

	foreach($da as $i=>$row){
		$fid=$row["activeprofiledesc"];
		$fidu=urlencode($fid);
		$ttx.="row $i $fidu";
		if(is_array($ptA)){
			if(in_array($fid,$ptA)) {
				#don't include completed forms.
				#bear in mind completed forms may be out of date - need a mechanism for that
				$contrx.="done $fid";
			}else{
				$this->incompleteDynA[]=$fid;
				$contrx.="<input type=checkbox name='$dtype$fidu' $ckx onclick=saveAttach('$fidu','$dtype');><i>$fid &nbsp;</i>";
			}
		}else{
			$this->incompleteDynA[]=$fid;
		}
	}
	#tfw("sdac.txt","$ttx",true);
	return $contrx;
}

function recordRfqReply($rfq,$qval,$mval,$replied,$sqref,$jobid){
	$rx='';
	#new method - test all responses
	$params="divid=dialogCompletion!I!tn=rfqReplyHandler!I!poref=$poref!I!jobid=$jobid!I!title=jobnote";
	tfw("jqp.txt","pp $params",true);
	$this->jqUiModal($params);

	#old method

	if($replied=='true'){
		$rx='on';
		$this->jobStageChange($jobid,"next_sendquote");
	}
	$dt=date("Y-m-d");
	$uq="update rfq set replied='$rx',qval='$qval',margin='$mval',replydate='$dt',supplierqref='$sqref' where rfqno=$rfq";
	mysql_query($uq);
	#redisplay log;
	$this->displayExternalLog($jobid);




}

function applyRfqVal($rfq,$qval,$mval,$apply,$sqref=null,$jobid=null){
	tfw("arqv.txt","$rfq q $qval m $mval",true);
	#echo "alert('arv $sqref j:$jobid $mval p:$rfq')\n";
	$rx='';
	if(($apply=='true')|($apply=='on')){
		$rx='on';
	}else{
		$mval=0;
	}
	$dt=date("Y-m-d");
	$uq="update rfq set apply='$rx',qval='$qval',margin='$mval',supplierqref='$sqref' where rfqno=$rfq";
	tfw("urq.txt","uq $uq",true);
	mysql_query($uq);

	#recalc order val;
	if($jobid>0){
	$vq="select sum(margin+qval) as qval from rfq where jobid=$jobid and apply='on'";
	$qval=iqasrf($vq,"qval");
	//echo "alert('udj $jobid $qval')\n";
	$ja["estimatedsell"]=$qval;
	performarrayupdate("jobs",$ja,"jobid",$jobid);

	#modal handled on success ;
	if($this->viaJquery) {
		echo $qval;
		return;
	}

	#echo "g('estimatedsell').value='$qval' \n";
	echo "if(g('diveditestimatedsell')) g('diveditestimatedsell').innerHTML='$qval' \n";

	}

	#stage handling
	$params="divid=dialogCompletion!I!tn=rfqReplyHandler!I!poref=$poref!I!jobid=$jobid!I!title=jobnote";
	tfw("jqp.txt","pp $params",true);
	$this->jqUiModal($params);


}

function respReplyAccept($jobid,$rfq){
	#auto tick if prior steps not already processed.
	#basic after the event updates, no normal job stage tracking;
	$uq="update rfq set responded='on',replied='on',accepted='on' where rfqno=$rfq";
	mysql_query($uq);
}

function closeRfq($rfq,$jobid){
	$uq="update rfq set closed='on'
	 where rfqno=$rfq";
	 mysql_query($uq);
}

function acceptRfqVal($rfq,$qval,$mval,$apply,$jobid,$sqref){
	$rx='';
	if($apply=='true'){
		$rx='on';
	}
	$dt=date("Y-m-d");
	$uq="update rfq set accepted='$rx',qval='$qval',margin='$mval',supplierqref='$sqref' where rfqno=$rfq";
	mysql_query($uq);
	#then spawn JRQ for this supplier;
	$sq="select supplierid from rfq where rfqno=$rfq";
	$supplierid=iqasrf($sq,"supplierid");

	#accept & respond and qte if not already
	#echo "alert('acceptor')\n";
	$this->respReplyAccept($jobid,$rfq);
	$this->createExternalAction($jobid,$supplierid,"jrq",$rfq);

	#$this->jobStageChange($jobid,"waiting_external_jobresponse");
	#stage change handled via dialog choices now.

	#stage handling -
	#not appropriate here - accepted comes much later in the piece.
	#$params="divid=dialogCompletion!I!tn=rfqReplyHandler!I!poref=$poref!I!jobid=$jobid!I!title=jobnote";
	#tfw("jqp.txt","pp $params",true);
	#$this->jqUiModal($params);


}

function recordRfqResponse($rfq,$responded,$jobid){
	//echo "alert('$rfq $responded')\n";
	//superceded by jquery
	$da=$this->jobFromRFQ($rfq);
	$cname=$da["companyname"];
	$jobid=$da["jobid"];
	$rx='';
	if($responded=='true'){
		$rx='on';
		#DEC 2010 - no jobstage change here, do via modal
		#$dt=date("Y-m-d h:i");
		#$dx=",responsedate='$dt'";
		#$logx="RFQ $cname response recd.";
		#$this->jobStageChange($jobid,"waiting_external_quote");
	}else{
		$logx="RFQ $cname response unchecked.";
	}
	$uq="update rfq set responded='$rx' $dx where rfqno=$rfq";
	mysql_query($uq);

	$params="divid=dialogCompletion!I!tn=rfqResponder!I!poref=$poref!I!jobid=$jobid!I!title=jobnote";
	tfw("jqp.txt","pp $params",true);
	$this->jqUiModal($params);



/*
		$t="editlog";
		$d=date("Y-m-d");
		$uid=$_SESSION["userid"];
		$ad=array(
		"tablename"=>"jobs",
		"recordid"=>$jobid,
		"editdate"=>$d,
		"userid"=>$uid,
		"fieldname"=>"RFQ response",
		"oldvalue"=>$logx,
		"newvalue"=>"");
		performarrayinsert($t,$ad);

	#redisplay log (to show date);
	$this->displayExternalLog($jobid);
*/
}

function recordJrqResponse($rfq,$responded,$jobid){

	$da=$this->jobFromPO($rfq);
	$cname=$da["companyname"];
	$jobid=$da["jobid"];

	#echo "alert('$rfq $responded $cname $jobid')\n";

	$rx='';
	if($responded=='true'){
		$rx='on';
		$logx="Job Request $cname response recd.";
		$this->jobStageChange($jobid,"waiting_job_decision");

	}else{
		$logx="Job Request $cname response unchecked.";
	}
	$dt=date("Y-m-d");
	$uq="update purchaseorders set responded='$rx',responsedate='$dt' where poref=$rfq";

	mysql_query($uq);
		$t="editlog";
		$d=date("Y-m-d");
		$uid=$_SESSION["userid"];
		$ad=array(
		"tablename"=>"jobs",
		"recordid"=>$jobid,
		"editdate"=>$d,
		"userid"=>$uid,
		"fieldname"=>"Job Request response",
		"oldvalue"=>$logx,
		"newvalue"=>"");
		performarrayinsert($t,$ad);

	$this->displayExternalLog($jobid);
}

function jobFromPO($rfq){
	$jq="select r.jobid,c.companyname from purchaseorders as r
	left outer join customer as c
	on r.supplierid=c.customerid where poref=$rfq";
	$tf=kda("companyname,jobid");
	$da=iqasra($jq,$tf);
	return $da;
}

function jobFromRFQ($rfq){
	$jq="select r.jobid,c.companyname from rfq as r
	left outer join customer as c
	on r.supplierid=c.customerid where rfqno=$rfq";
	$tf=kda("companyname,jobid");
	$da=iqasra($jq,$tf);
	return $da;
}


function rfqDelete($rfq){
	#editlog..
	$da=$this->jobFromRFQ($rfq);
	$cname=$da["companyname"];
	$jobid=$da["jobid"];

	#echo "alert('rfqd rr $rfq ccn $cname j $jobid')\n";

		$t="editlog";
		$d=date("Y-m-d");
		$uid=$_SESSION["userid"];
		$ad=array(
		"tablename"=>"jobs",
		"recordid"=>$jobid,
		"editdate"=>$d,
		"userid"=>$uid,
		"fieldname"=>"RFQ deleted",
		"oldvalue"=>"$rfq - $cname",
		"newvalue"=>"");
		performarrayinsert($t,$ad);

	#then delete
	$uq="delete from rfq where rfqno=$rfq";
	mysql_query($uq);

	#refresh display
	#	$this->displayExternalLog($jobid);
	echo "jobview($jobid)\n";



}

function jrqDelete($jrq){
	#editlog..
	$jq="select r.jobid,c.companyname from purchaseorders as r
	left outer join customer as c
	on r.supplierid=c.customerid where poref=$jrq";
	$tf=kda("companyname,jobid");
	$da=iqasra($jq,$tf);
	$cname=$da["companyname"];
	$jobid=$da["jobid"];

	#echo "alert('rfqd rr $rfq ccn $cname j $jobid')\n";

		$t="editlog";
		$d=date("Y-m-d");
		$uid=$_SESSION["userid"];
		$ad=array(
		"tablename"=>"jobs",
		"recordid"=>$jobid,
		"editdate"=>$d,
		"userid"=>$uid,
		"fieldname"=>"Job Request deleted",
		"oldvalue"=>"$jrq - $cname",
		"newvalue"=>"");
		performarrayinsert($t,$ad);

	#then delete
	$uq="delete from purchaseorders where poref=$jrq";
	mysql_query($uq);

	tfw("jrqdel.txt",$uq,true);
	#refresh display
	if($this->viaJquery) return;
	$this->displayExternalLog($jobid);

}


function recordPOrefAccepted($poref,$jobid){
	#echo "alert('apo'+$poref)\n";
	$dt=date("Y-m-d");
	$uq="update purchaseorders set accepted='on',responded='on',acceptdate='$dt' where poref=$poref";
	mysql_query($uq);
 	$newstage="external_incomplete";
 	$this->jobStageChange($jobid,$newstage);
	#$this->displayExternalLog($jobid);
}

function externalComplete($poref,$extinv,$cost,$jobid){
	$dt=date("Y-m-d");
	#auto responnd & complete (no log)
	//$uq="update purchaseorders set responded='on',accepted='on',completed='on',completedate='$dt',invoiceref='$extinv',cost='$cost' where poref=$poref";

	$uq="update purchaseorders set responded='on',accepted='on',completed='on',completedate='$dt' where poref=$poref";

	mysql_query($uq);
	$jobstage="next_notify_client";

	#refresh to display un-billed.
	#echo "alert('g j $jobid')\n";
	$this->jobExtras($jobid);

	#po is completed, but..
	#now processing job completion via modal
	#$this->jobCompletion($jobid,$jobstage,$dt);
	#echo "g('masterExtras').innerHTML='$this->masterExtrax' \n";
	if($this->viaJquery) return;
	$this->displayExternalLog($jobid);
	#now ask whether job is fully complete.
	$params="divid=dialogCompletion!I!tn=jrqCompletion!I!poref=$poref!I!jobid=$jobid!I!title=jobnote";
	#tfw("jqp.txt","pp $params",true);
	$this->jqUiModal($params);
}


function externalNewInvoice($jobid,$sell,$glc,$poref){
	$this->genInvoice("ext",$jobid,$sell,$glc,$poref);
}

function jobCompletion($jobid,$jstage,$dt){
	$juq="update jobs set jcompletedate='$dt' where jobid=$jobid";
	mysql_query($juq);
	$fdt=date("d/m/Y",strtotime($dt));
	echo "g('fakejcompletedate').value='$fdt';\n";
	$this->jobStageChange($jobid,$jstage,true);

}
 
function internalComplete($jobid,$taskid,$apptid){
	$dt=date("Y-m-d");
	#echo "alert('completion')\n";
	//echo "alert('saved $dt $taskid job $jobid  apt $apptid')\n";
	$uq="update task set completed='on',dateclosed='$dt' where taskid=$taskid ";
	mysql_query($uq);

	$duq="update diary set completed='on' where apptid=$apptid";
	mysql_query($duq);
	$jobstage="internal_complete";
	$this->jobCompletion($jobid,$jobstage,$dt);
	#now do modal offer
	#echo "jcompletion($jobid);\n";


	#po is completed, but..
	#now processing job completion via modal
	#$this->jobCompletion($jobid,$jobstage,$dt);
	#echo "g('masterExtras').innerHTML='$this->masterExtrax' \n";
	#$this->displayExternalLog($jobid);
	#now ask whether job is fully complete.
	$params="divid=dialogCompletion!I!tn=jrqCompletion!I!poref=$poref!I!jobid=$jobid!I!title=jobnote";
	tfw("jqp.txt","pp $params",true);
	$this->jqUiModal($params);
}
function sitefmreportsetup($custid=0,$sitefmid=0){
	#unset the session.(until managing clickoffs);
	unset($_SESSION["reportPack"][$custid]);

		if($sitefmid>0)
		{
			//echo $sitefmid;
		}
	$cx="<h3>Standard Job Report Setup </h3>";
	$cx.="Check/Uncheck fields to display on report";
	$cx.=$this->reportOptions($custid);
	$ocx="onclick=openCustJobs(\'$custid\')";
	$cx.="<button $ocx>Jobs In Progress Report</button>";

	$rocx="onclick=openCustJobs(\'$custid\',\'recurring\')";
	$cx.="<button $rocx>Recurring Jobs In Progress Report</button>";



	#$cx.="date setup";
	#echo "dtrangeset()\n";
	if($sitefmid==0)
		{
	$url="$this->basepath/infclasses/iDateSelectionAJ.php";
	include_once $url;
	$idt=new iDateSelection("reportpack");
	$idt->dtr=$_SESSION["idt_range"]["reportpack"];

	$idt->paramsA["dtrange"]=$idt->dtr;
	#$nx.="<br>aaaa $idt->dtr $idt->rn";
	$idt->dd();
	#$cx.=vClean($idt->dateFormx);
	$dcx=vClean($idt->dateFormx);

		}
	tfw("dsel.txt",$dcx,true);
	#if($custid==0)
	$nx.=$idt->dateFormx;

	if($this->chartMode=="global" && $sitefmid==0){
		#echo "state choice";
		$sa=statenames();
	   	$pc=new iForm();
	   	$jcall="onchange=sessioniseSelect('state')";
		$sx=$pc->array_select("state",$sa,$jcall);
		$filtx.="States: $sx <br>";
		#echo $sx;

		##fmchoices.
		$fma=$this->fmChoices();
	   	$fn="sitefm";
	   	$pc=new iForm();
	   	$pc->idField[$fn]="contactid";
	   	$pc->jqContext[$fn]="sitefm";
		$pc->fsize[$fn]=50;
		$pc->jqSuggIdf[$fn]="contactid";
		$pc->fsize[$fn]=50;


	   	#$jcall="onchange=sessioniseSelect('siteFM')";
		$pc->idField[$fn]="contactid";
		$ffx=$pc->uicomplete($fn);
		#$sx=$pc->array_select("fm",$fma,$jcall);
	   	$sx=$ffx;
		$filtx.="FM choice: $sx <br>";
	    $this->jcallA[]=$pc->initLookupCall;
	}
	$nx.="<form name='packFilter'>$filtx</form>";
	#$cx.=$url;


#echo $custid;
	if($custid>0){

	$rda[0]["id"]="QuotesOS";
	$rda[0]["desc"]="Outstanding Quotes";
	$rda[0]["rname"]="ocq_pending";
	#$rda[0]["rinclude"]="0";

	$rda[1]["id"]="Quotes1";
	$rda[1]["desc"]="Quotes <1000";
	$rda[1]["rname"]="ocqSingle";
	#$rda[1]["rinclude"]="1";
	$rda[1]["ll"]=0;
	$rda[1]["ul"]=1000;

	$rda[2]["id"]="Quotes2";
	$rda[2]["desc"]="Quotes  1000 - 5000";
	$rda[2]["rname"]="ocqSingle";
	#$rda[2]["rinclude"]="2";
	$rda[2]["ll"]=1000;
	$rda[2]["ul"]=5000;

	$rda[3]["id"]="Quotes3";
	$rda[3]["desc"]="Quotes  >5000";
	$rda[3]["rname"]="ocqSingle";
	#$rda[3]["rinclude"]="3";
	$rda[3]["ll"]=5000;
	#$rda[2]["ul"]=9995000;
	}

	$rda[4]["id"]="WorkRequest";
	$rda[4]["desc"]="Work Request";
	$rda[4]["rname"]="workRequestChart";
	#$rda[4]["rinclude"]="4";


	$rda[5]["id"]="QuoteConversion";
	$rda[5]["desc"]="Quote Conversion";
	$rda[5]["rname"]="quoteConversion";
	#$rda[5]["rinclude"]="5";

	$rda[6]["id"]="QuotesCurentM";
	$rda[6]["desc"]="Quotes Pie Chart Full Period";
	$rda[6]["rname"]="quotePie";
	#$rda[6]["rinclude"]="6";

	$rda[7]["id"]="QuotesCurentQ";
	$rda[7]["desc"]="Quotes Pie Chart Qtr";
	$rda[7]["rname"]="quotePieQ";

	$rc=8;
	$rda[$rc]["id"]="WorkCompletion";
	$rda[$rc]["desc"]="Work Completion";
	$rda[$rc]["rname"]="workCompletion";
	$rc++;

	$rda[$rc]["id"]="WorkCompletionRecur";
	$rda[$rc]["desc"]="Work Completion (Recurring)";
	$rda[$rc]["rname"]="workCompletionRecur";
	$rc++;

	$rda[$rc]["id"]="SalesCharts";
	$rda[$rc]["desc"]="Sales Charts";
	$rda[$rc]["rname"]="salesChart";
	$rc++;

	$rda[$rc]["id"]="jobCounts";
	$rda[$rc]["desc"]="Job Counts";
	$rda[$rc]["rname"]="jobCounts";
	$rc++;

	$rda[$rc]["id"]="workCompletionPercent";
	$rda[$rc]["desc"]="On Time %";
	$rda[$rc]["rname"]="workCompletionPercent";
	$rc++;


	$rda[$rc]["id"]="WorkCompletionDetail";
	$rda[$rc]["desc"]="Work Completion Detail";
	$rda[$rc]["rname"]="workCompletionDetail";
	$rc++;

	$rda[$rc]["id"]="WorkCompletionDetailRecur";
	$rda[$rc]["desc"]="Work Completion Detail (Recurring)";
	$rda[$rc]["rname"]="workCompletionDetailRecur";
	$rc++;

	$rda[$rc]["id"]="quoteCompletion";
	$rda[$rc]["desc"]="Quote Completion";
	$rda[$rc]["rname"]="quoteCompletion";
	$rc++;

	$rda[$rc]["id"]="quoteCompletionJobDate";
	$rda[$rc]["desc"]="Quote Completion Detail (Job Date - Internal Report)";
	$rda[$rc]["rname"]="quoteCompletionJobDate";
	$rc++;


	$rda[$rc]["id"]="quoteCompletionDetail";
	$rda[$rc]["desc"]="Quote Completion Detail";
	$rda[$rc]["rname"]="quoteCompletionDetail";
	$rc++;

	$rda[$rc]["id"]="quoteCompletionPercent";
	$rda[$rc]["desc"]="Quotes On Time %";
	$rda[$rc]["rname"]="quoteCompletionPercent";
	$rc++;

	$rda[$rc]["id"]="completionPercentCombo";
	$rda[$rc]["desc"]="Jobs & Quotes On Time %";
	$rda[$rc]["rname"]="completionPercentCombo";
	$rc++;

	$rda[$rc]["id"]="jobQuoteRatio";
	$rda[$rc]["desc"]="Quotes / Job Chart";
	$rda[$rc]["rname"]="jobQuoteRatio";
	$rc++;

	$rda[$rc]["id"]="extendedRatio";
	$rda[$rc]["desc"]="Jobs Extended / Not Extended";
	$rda[$rc]["rname"]="extendedRatio";
	$rc++;

	$rda[$rc]["id"]="jobCountRec";
	$rda[$rc]["desc"]="Job Counts / Invoice Date Reconciliations";
	$rda[$rc]["rname"]="jobCountRec";
	$rc++;

	$rda[$rc]["id"]="quoteOutcomes";
	$rda[$rc]["desc"]="Quote Outcomes";
	$rda[$rc]["rname"]="quoteOutcomes";
	$rc++;

	$rda[$rc]["id"]="quoteBandOutcomes";
	$rda[$rc]["desc"]="Quote Outcomes By Band";
	$rda[$rc]["rname"]="quoteBandOutcomes";
	$rc++;

	$rda[$rc]["id"]="invoicedBandOutcomes";
	$rda[$rc]["desc"]="Sales Outcomes By Band";
	$rda[$rc]["rname"]="invoicedBandOutcomes";
	$rc++;

	$rda[$rc]["id"]="invoicedBandOutcomesMth";
	$rda[$rc]["desc"]="24 Mth Sales Outcomes By Band";
	$rda[$rc]["rname"]="invoicedBandOutcomesMth";
	$rc++;

	$rda[$rc]["id"]="sitejobcounts";
	$rda[$rc]["desc"]="Site Job Counts";
	$rda[$rc]["rname"]="SitejobCounts";
	$rc++;
	
	$tf=kda("id,ll,ul,desc,rinclude");
	$tf["rinclude"]="Include?";
	$ist=new divTable();
	#$ist->input_fields["rinclude"]="checkboxAJ";


	$ist->input_fields["id"]="hidden";
	$ist->input_fields["ll"]="hidden";
	$ist->input_fields["ul"]="hidden";
	#$ist->input_fields["desc"]="hpd";
	$ist->linka["desc"]=array("ajaxHrefIcon"=>"hx");
	$ist->icon["desc"]="p";
	$ist->ajaxFname["desc"]="reportPack";
	$ist->ajaxStaticData["desc"]=kda($custid);
	$ist->ajaxData["desc"]=kda("rname,ll,ul");




	$ist->linka["rinclude"]=array("ajaxCheckbox"=>"hx");
	$ist->ajaxFname["rinclude"]="reportChain";
	$ist->ajaxStaticData["rinclude"]=kda($custid);
	$ist->ajaxData["rinclude"]=kda("rname,ll,ul,desc");


	#$ist->displayCount=true;
	$ist->entrymode=true;
	#$ist->formtag="<form name=\"rpack\" action=\"iireportChain.php\" method=\"post\">";
	#$ist->formtag="<form name=\"iireportChainP.php\">";
	$tf["desc"]="Report Name";

	$ldiv="<div id='loadingDiv'><img src=../../infbase/images/load.gif>Loading </div>";
	$dtx.="<div id=progress style=display:block;>&nbsp;</div>";
	$dtx.="<div id='progressbarWrapper' style='width:400px; height:10px;'  class='ui-widget-default'>
	<div id='progressbar' style='width:400px; height:100%;'></div>
	</div>";
	$nx.="$lidv $dtx yeah";




	$nx.=$ist->inf_sortable($tf,$rda,"Standard Reports (Tick to include in pack.)",null,$ist->ifa,true);
	#$nx.="<input type=submit value=\"Chain\">";
	#$nx.="</form>";
	$nx.="<div id=reportPack></div>";
	$nx.="<button onclick=\"rchain($custid,$sitefmid)\">Report Pack</button>";
	$nx.="<button onclick=\"rchain($custid,$sitefmid)\">PDF Pack</button>";

	$cx.=vClean($nx);



	$rxp="onclick=window.open(\'../../ii/iireportChain.php?m=chain&c=$sitefmid\')";

	tfw("chain.txt",$nx,true);
	if($custid>0){
		echo "g('guts').innerHTML='$cx'\n";
	}else{
		#return to /content/charts
		return $nx;
	}
}

function clientreportsetup($custid=0,$sitefmid=0){
	#unset the session.(until managing clickoffs);
	unset($_SESSION["reportPack"][$custid]);

		if($sitefmid>0)
		{
			//echo $sitefmid;
		}
	$cx="<h3>Standard Job Report Setup </h3>";
	$cx.="Check/Uncheck fields to display on report";
	$cx.=$this->reportOptions($custid);
	$ocx="onclick=openCustJobs(\'$custid\')";
	#$ocx
	$cx.="<button id=ocj>Jobs In Progress Report</button>";

	$rocx="onclick=openCustJobs(\'$custid\',\'recurring\')";
	$cx.="<button $rocx>Recurring Jobs In Progress Report</button>";



	#$cx.="date setup";
	#echo "dtrangeset()\n";
	if($sitefmid==0)
		{
	$url="$this->basepath/infclasses/iDateSelectionAJ.php";
	include_once $url;
	$idt=new iDateSelection("reportpack");
	$idt->dtr=$_SESSION["idt_range"]["reportpack"];

	$idt->paramsA["dtrange"]=$idt->dtr;
	#$nx.="<br>aaaa $idt->dtr $idt->rn";
	$idt->dd();
	#$cx.=vClean($idt->dateFormx);
	$dcx=vClean($idt->dateFormx);

		}
	tfw("dsel.txt",$dcx,true);
	#if($custid==0)
	$nx.=$idt->dateFormx;

	#if($this->chartMode=="global" && $sitefmid==0){
		#echo "state choice";
		$sa=statenames();
	   	$pc=new iForm();
	   	$jcall="onchange=sessioniseSelect('state')";
		$pc->multiple["state"]=true;
		$pc->selectsize["state"]=4;
		#$pc->styleCall["state"]="style='height:35px;'";
		$sx=$pc->array_select("state",$sa,$jcall);
		$filtx.="<div style=vertical-align:top;>States: $sx (Hold 'Ctrl' to select multiple)</div>";
		#echo $sx;

		##fmchoices.
		$fma=$this->fmChoices();
	   	$fn="sitefm";
	   	$pc=new iForm();
	   	$pc->idField[$fn]="contactid";
	   	$pc->jqContext[$fn]="sitefm";
		$pc->fsize[$fn]=50;
		$pc->jqSuggIdf[$fn]="contactid";
		$pc->fsize[$fn]=50;


	   	#$jcall="onchange=sessioniseSelect('siteFM')";
		$pc->idField[$fn]="contactid";
		    $pc->postSuggestCallBackFunc="fmAccumulator;";
		   /* $chx="function fmAccumulator(){
		    	var fmid=$('#contactid').val();
		    	alert(fmid);
		    	fmselection.append('testing'+fmid);
			  })
		    };";
		   */
		   $chx="function fmAccumulator(){
		    	//alert('hey');
		    		$('#fmSelCont').css('display','block');
		    			    	var fmid=$('#contactid').val();
					    	//alert(fmid);
					    	$('#fmselection').append(fmid+',');
					    	var fmx=$('#fmid').val();
					    	var cumfmx=fmx!=''?fmx+',':'';
					    	cumfmx+=fmid;
					    	$('#fmid').val(cumfmx);
					    	//sessioniseSelect('fmselection')
					    	var fma=cumfmx.split(',');
						var fmc=fma.length;
 					    	$('#fmcount').html(fmc);

			 };
			 $('#fmclear').click(function(){
			    	$('#fmselection').html('');
			    	$('#fmid').val('');
			    	$('#fmcount').html('No ');
			    	$('#contactid').val('');
			    	$('#sitefm').val('');

			    	//sessioniseSelect('fmselection')
			 });

			 ";
			 $fminput="<input name=fmid id=fmid type=hidden>";
		$ffx=$pc->uicomplete($fn);
		#$sx=$pc->array_select("fm",$fma,$jcall);
	   	$sx=$ffx;
		$filtx.="FM choice: (<i>repeat search to select multiple</i>) $sx <br>";
		$filtx.="<div id=fmSelCont style=background-color:#CCC;width:600px;display:none;><span id=fmcount></span> Selected FM's (shows contactid ID)<button type=button id=fmclear>clear</button><div id=fmselection></div></div>";


	    $this->jcallA[]=$pc->initLookupCall;
	    $this->jcallA[]=$chx;
	#}
	$nx.="<form name='packFilter'> $filtx $fminput </form>";
	#$cx.=$url;


#echo $custid;
	if($custid>0){

	$rda[0]["id"]="QuotesOS";
	$rda[0]["desc"]="Outstanding Quotes";
	$rda[0]["rname"]="ocq_pending";
	#$rda[0]["rinclude"]="0";

	$rda[1]["id"]="Quotes1";
	$rda[1]["desc"]="Quotes <1000";
	$rda[1]["rname"]="ocqSingle";
	#$rda[1]["rinclude"]="1";
	$rda[1]["ll"]=0;
	$rda[1]["ul"]=1000;

	$rda[2]["id"]="Quotes2";
	$rda[2]["desc"]="Quotes  1000 - 5000";
	$rda[2]["rname"]="ocqSingle";
	#$rda[2]["rinclude"]="2";
	$rda[2]["ll"]=1000;
	$rda[2]["ul"]=5000;

	$rda[3]["id"]="Quotes3";
	$rda[3]["desc"]="Quotes  >5000";
	$rda[3]["rname"]="ocqSingle";
	#$rda[3]["rinclude"]="3";
	$rda[3]["ll"]=5000;
	#$rda[2]["ul"]=9995000;
	}
	$rc=4;

	$rda[$rc]["id"]="WorkRequest";
	$rda[$rc]["desc"]="Work Request";
	$rda[$rc]["rname"]="workRequestChart";
	$rc++;


	$rda[$rc]["id"]="QuoteConversion";
	$rda[$rc]["desc"]="Quote Conversion";
	$rda[$rc]["rname"]="quoteConversion";
	$rc++;

	$rda[$rc]["id"]="QuotesCurentM";
	$rda[$rc]["desc"]="Quotes Pie Chart Full Period";
	$rda[$rc]["rname"]="quotePie";
	$rc++;

	$rda[$rc]["id"]="QuotesCurentM";
	$rda[$rc]["desc"]="Quotes Pie Chart Full Period - GP";
	$rda[$rc]["rname"]="quotePieGP";
	$rc++;

	$rda[$rc]["id"]="QuotesCurentQ";
	$rda[$rc]["desc"]="Quotes Pie Chart Qtr";
	$rda[$rc]["rname"]="quotePieQ";
	$rc++;

	$rda[$rc]["id"]="WorkCompletion";
	$rda[$rc]["desc"]="Work Completion";
	$rda[$rc]["rname"]="workCompletion";
	$rc++;

	$rda[$rc]["id"]="WorkCompletionRecur";
	$rda[$rc]["desc"]="Work Completion (Recurring)";
	$rda[$rc]["rname"]="workCompletionRecur";
	$rc++;

	$rda[$rc]["id"]="SalesCharts";
	$rda[$rc]["desc"]="Sales Charts";
	$rda[$rc]["rname"]="salesChart";
	$rc++;

	$rda[$rc]["id"]="jobCounts";
	$rda[$rc]["desc"]="Job Counts";
	$rda[$rc]["rname"]="jobCounts";
	$rc++;

	$rda[$rc]["id"]="workCompletionPercent";
	$rda[$rc]["desc"]="On Time %";
	$rda[$rc]["rname"]="workCompletionPercent";
	$rc++;


	$rda[$rc]["id"]="WorkCompletionDetail";
	$rda[$rc]["desc"]="Work Completion Detail";
	$rda[$rc]["rname"]="workCompletionDetail";
	$rc++;

	$rda[$rc]["id"]="WorkCompletionDetailRecur";
	$rda[$rc]["desc"]="Work Completion Detail (Recurring)";
	$rda[$rc]["rname"]="workCompletionDetailRecur";
	$rc++;

	$rda[$rc]["id"]="quoteCompletion";
	$rda[$rc]["desc"]="Quote Completion";
	$rda[$rc]["rname"]="quoteCompletion";
	$rc++;

	$rda[$rc]["id"]="quoteCompletionJobDate";
	$rda[$rc]["desc"]="Quote Completion Detail (Job Date - Internal Report)";
	$rda[$rc]["rname"]="quoteCompletionJobDate";
	$rc++;


	$rda[$rc]["id"]="quoteCompletionDetail";
	$rda[$rc]["desc"]="Quote Completion Detail";
	$rda[$rc]["rname"]="quoteCompletionDetail";
	$rc++;

	$rda[$rc]["id"]="quoteCompletionPercent";
	$rda[$rc]["desc"]="Quotes On Time %";
	$rda[$rc]["rname"]="quoteCompletionPercent";
	$rc++;

	$rda[$rc]["id"]="completionPercentCombo";
	$rda[$rc]["desc"]="Jobs & Quotes On Time %";
	$rda[$rc]["rname"]="completionPercentCombo";
	$rc++;

	$rda[$rc]["id"]="jobQuoteRatio";
	$rda[$rc]["desc"]="Quotes / Job Chart";
	$rda[$rc]["rname"]="jobQuoteRatio";
	$rc++;

	$rda[$rc]["id"]="extendedRatio";
	$rda[$rc]["desc"]="Jobs Extended / Not Extended";
	$rda[$rc]["rname"]="extendedRatio";
	$rc++;

	$rda[$rc]["id"]="jobCountRec";
	$rda[$rc]["desc"]="Job Counts / Invoice Date Reconciliations";
	$rda[$rc]["rname"]="jobCountRec";
	$rc++;

	$rda[$rc]["id"]="quoteOutcomes";
	$rda[$rc]["desc"]="Quote Outcomes";
	$rda[$rc]["rname"]="quoteOutcomes";
	$rc++;

	$rda[$rc]["id"]="quoteBandOutcomes";
	$rda[$rc]["desc"]="Quote Outcomes By Band";
	$rda[$rc]["rname"]="quoteBandOutcomes";
	$rc++;

	$rda[$rc]["id"]="invoicedBandOutcomes";
	$rda[$rc]["desc"]="Sales Outcomes By Band";
	$rda[$rc]["rname"]="invoicedBandOutcomes";
	$rc++;

	$rda[$rc]["id"]="invoicedBandOutcomesMth";
	$rda[$rc]["desc"]="24 Mth Sales Outcomes By Band";
	$rda[$rc]["rname"]="invoicedBandOutcomesMth";
	$rc++;

	foreach($rda as $i=>$rr){
		#making description available to rdesc (no confusion with link images)
		$rda[$i]["rdesc"]=$rr["desc"];
	}

	$tf=kda("rname,id,ll,ul,desc,rdesc,prog,rinclude");
	$tf["rinclude"]="Include?";
	$tf["prog"]="&nbsp";
	$ist=new divTable();
	#$ist->input_fields["rinclude"]="checkboxAJ";


	$ist->input_fields["rname"]="hidden";
	$ist->input_fields["rdesc"]="hidden";
	$ist->input_fields["id"]="hidden";
	$ist->input_fields["ll"]="hidden";
	$ist->input_fields["ul"]="hidden";
	$ist->linka["desc"]=array("ajaxHrefIcon"=>"hx");
	$ist->icon["desc"]="p";
	$ist->ajaxFname["desc"]="reportPack";
	$ist->ajaxStaticData["desc"]=kda($custid);
	$ist->ajaxData["desc"]=kda("rname,ll,ul");


	$ist->linka["rinclude"]=array("ajaxCheckbox"=>"hx");
	$ist->ajaxFname["rinclude"]="reportChain";
	$ist->ajaxStaticData["rinclude"]=kda($custid);
	$ist->ajaxData["rinclude"]=kda("rname,ll,ul,desc");


	#$ist->displayCount=true;
	$ist->entrymode=true;
	#$ist->formtag="<form name=\"rpack\" action=\"iireportChain.php\" method=\"post\">";
	#$ist->formtag="<form name=\"iireportChainP.php\">";
	$tf["desc"]="Report Name";
	$ist->tableidx="id=reportnames";


	$ldiv="<div id='loadingDiv'><img src=../../infbase/images/load.gif>Loading </div>";
	$dtx.="<div id=progress style=display:block;>&nbsp;</div>";
	$dtx.="<div id='progressbarWrapper' style='height:10px;'  class='ui-widget-default'>
	<div id='progressbar' style='height:100%;'></div>
	</div>";
	$nx.="$lidv $dtx ";

	$nx.=$ist->inf_sortable($tf,$rda,"Standard Reports (Tick to include in pack)",null,$ist->ifa,true);
	#$nx.="<input type=submit value=\"Chain\">";
	#$nx.="</form>";



	$nx.="<div id=reportPack></div>";
	#$nx.="<button onclick=\"rchain($custid)\">Report Pack</button>";
	$nx.="<button id=htmlpack type=button>HTML Pack</button>";
	$nx.="<button id=pdfpack type=button>PDF Pack</button>";
	#$nx.="<button onclick=tmakePdfPack();>F PDF</button>";
	$uid=$_SESSION["userid"];
	$pdfName="reportpack_".$uid.".pdf";
	$iconx=vClean(ibAjax("pdf"));
		//$ocjx="	$('#pdfpack').click(function(){
		//	jqml('generic','batchPDFReportBuild','dialogGeneric','stuff');
		//})";
		$ocjx="$('#pdfpack').click(function () {
				window.scrollTo(0,400);
				var packd=[];
				var descd=[];

				var matches = [];
				//$('.checkbox:checked').each(function() {
				$('.checkbox').each(function() {
					var row = $(this).closest('tr');
					var rname=row.find('input[name=rname]').val();
					//alert('got '+rname);
					matches.push(rname);
				})


				var ml=$('#reportnames .checkbox:checked').length;
				//alert(ml);
				var tl=$('#reportnames input[name=rname]').length;

				var processCount=0;
				$('#progressbarWrapper').resizable();
				$('#progress').html('Starting reports please wait...');

				var processCount=0;
				//alert('rn '+ml+' '+tl);

				function doRequest(index) {
					var row =$('#reportnames').find('tr').eq(index);
					var rname= row.find('input[name=rname]').val();
					var di=0;
					if(row.find('input[name=rinclude]').attr('checked')){
						processCount++;

						//alert('go '+rname);
						di++;
						if(di<5){
						}
						var ifobj=document.irangeset;
						var drr=getForm(ifobj) ;
						var rid=row.find('input[name=id]').val();
						var desc=row.find('input[name=rdesc]').val();
						var ul=row.find('input[name=ul]').val();
						var ll=row.find('input[name=ll]').val();
						var fmid=$('#fmid').val();
						var pm='c=$custid&rid='+rid+'&rn='+rname+'&dates='+drr+'&fmid='+fmid;
						packd.push(rid);
						descd.push(desc);


						if(ul!='') pm+='&ul='+ul;
						if(ll!='') pm+='&ll='+ll;
						//var pm='c=$custid&rn='+rname
						var url='./iireportViaAjax.php';
						//alert(url+' '+pm);
							$.ajax({
							url:url,
							async:true,
							type: 'GET',
							data: pm,
							success: function(data){
							var nexti=index;
							var pcnt=round(processCount/ml*100,0);
							$.ajaxSetup({async:true});
							$('#progress').html('Completed report'+index+' - '+rname+'  - '+pcnt+'%, '+processCount+' pdfs created  of '+ml);
							row.find('td').eq(1).html('Completed report:'+data);
							//alert('sgot '+data);
							var nexti=index;
								$( '#progressbar' ).progressbar({
								value: pcnt
								});
						if (index+1<=tl) {
							doRequest(index+1);
						}
						if(pcnt==100){
						//alert('ok readly');
							$('#progressbar').hide('slow')
							//$('.semail').css('display','block');
							finalPDF(packd,descd);
						}
						}
						});
					 }else{
				 		//	alert('skip '+index);
							if (index+1<=tl) {
								doRequest(index+1);
							}
				 }



				}
				doRequest(1);


				function bindPDFs(pack){
				var url='../../infbase/ajax/ajxtInfJq_legacyVersions.php';
				$('#progress').html('Loading PDF..');
				var packx = packd.join('*');
				//packx+='*quoteCompletionPercent1';
				var fpackx='contents*'+packx;
				var pm='func=batchReportPdf&bulkVars=1&qstr=!I!packd='+fpackx;
					//alert('all done '+packx);
					$.ajax({
						url:url,
						async:false,
						type: 'POST',
						data: pm,
						success: function(pdfname){
						//alert('Reports Completed ');
						//var nexti=index;
						//window.open('./$pdfName');
						var iconX='<a href=./$pdfName target=_blank>$iconx View Report Pack</a>';
						//alert('done');
						$('#progress').html(iconX);

						}
					});
				}

				function finalPDF(pack,descd){
					var descx = escape(descd.join('*'));
					var pm='c=$custid&rid=coverpage&contentslist='+descx;
					var url='./iireportViaAjax.php';
					//alert(url+' '+pm);
						$.ajax({
						url:url,
						async:true,
						type: 'POST',
						data: pm,
						success: function(data){
							bindPDFs(pack);
						}
						});

				}





})";

		$htmlocjx="$('#htmlpack').click(function () {
				window.scrollTo(0,400);
				var packd=[];
				var descd=[];

				var matches = [];
				//$('.checkbox:checked').each(function() {
				$('.checkbox').each(function() {
					var row = $(this).closest('tr');
					var rname=row.find('input[name=rname]').val();
					//alert('got '+rname);
					matches.push(rname);
				})


				var ml=$('#reportnames .checkbox:checked').length;
				//alert(ml);
				var tl=$('#reportnames input[name=rname]').length;

				var processCount=0;
				$('#progressbarWrapper').resizable();
				$('#progress').html('Starting reports please wait...');

				var processCount=0;
				//alert('rn '+ml+' '+tl);

				function doRequest(index) {
					var row =$('#reportnames').find('tr').eq(index);
					var rname= row.find('input[name=rname]').val();
					var di=0;
					if(row.find('input[name=rinclude]').attr('checked')){
						processCount++;

						//alert('go '+rname);
						di++;
						if(di<5){
						}
						var ifobj=document.irangeset;
						var drr=getForm(ifobj) ;
						var rid=row.find('input[name=id]').val();
						var desc=row.find('input[name=rdesc]').val();
						var ul=row.find('input[name=ul]').val();
						var ll=row.find('input[name=ll]').val();
						var fmid=$('#fmid').val();
						var pm='c=$custid&rid='+rid+'&rn='+rname+'&dates='+drr+'&fmid='+fmid;
						packd.push(rid);
						descd.push(desc);


						if(ul!='') pm+='&ul='+ul;
						if(ll!='') pm+='&ll='+ll;
						//var pm='c=$custid&rn='+rname
						var url='./iireportViaAjax.php';
						//alert(url+' '+pm);
							$.ajax({
							url:url,
							async:true,
							type: 'GET',
							data: pm,
							success: function(data){
							var nexti=index;
							var pcnt=round(processCount/ml*100,0);
							$.ajaxSetup({async:true});
							$('#progress').html('Completed report'+index+' - '+rname+'  - '+pcnt+'%, '+processCount+' pdfs created  of '+ml);
							row.find('td').eq(1).html('Completed report:'+data);
							//alert('sgot '+data);
							var nexti=index;
								$( '#progressbar' ).progressbar({
								value: pcnt
								});
						if (index+1<=tl) {
							doRequest(index+1);
						}
						if(pcnt==100){
						//alert('ok readly');
							$('#progressbar').hide('slow')
							//$('.semail').css('display','block');
							finalHTML(packd,descd);
						}
						}
						});
					 }else{
				 		//	alert('skip '+index);
							if (index+1<=tl) {
								doRequest(index+1);
							}
				 }



				}
				doRequest(1);


				function bindHTMLs(pack){
				var url='../../infbase/ajax/ajxtInfJq_legacyVersions.php';
				$('#progress').html('Loading HTML..');
				var packx = packd.join('*');
				//packx+='*quoteCompletionPercent1';
				var fpackx='contents*'+packx;
				var pm='func=batchReportHtml&bulkVars=1&qstr=!I!packd='+fpackx;
					//alert('all done '+packx);
					$.ajax({
						url:url,
						async:false,
						type: 'POST',
						data: pm,
						success: function(rname){
						//alert('Reports Completed ');
						//var nexti=index;
						window.open('../temp/'+rname);
						}
					});
				}

				function finalHTML(pack,descd){
					var descx = escape(descd.join('*'));
					var pm='c=$custid&rid=coverpage&contentslist='+descx;
					var url='./iireportViaAjax.php';
					//alert(url+' '+pm);
						$.ajax({
						url:url,
						async:true,
						type: 'POST',
						data: pm,
						success: function(data){
							bindHTMLs(pack);
						}
						});

				}





})";


		$this->jcallA[]=$ocjx;
		$this->jcallA[]=$htmlocjx;

	$cx.=vClean($nx);
if($sitefmid==0)
{
	$rxp="onclick=window.open(\'./iireportChain.php?m=chain&c=$custid\')";

}
else

{
		$rxp="onclick=window.open(\'../../ii/iireportChain.php?m=chain&c=$custid\')";
}
		$ocjx="	$('#ocj').click(function(){
			//alert('clicked ocj button');
			var state=$('#state').val();
			var par='!I!custid!eq!$custid!I!state!eq!'+state;
			openCustJobs($custid,par);
			});";
		$this->jcallA[]=$ocjx;


	#$this->ibuffCalls();

	tfw("chain.txt",$nx,true);

	$dcx="<div style=\'background:#FFF;width:500px;\'>$cx</div>";

	if($custid>0){
	#	echo "g('guts').innerHTML='$dcx'\n";
		echo "$('#guts').html('$dcx');";

		#$this->jcallA[]="$('#guts').html('$dcx');";
		$this->ibuffCalls();
	}else{
		#return to /content/charts
		return $nx;
	}
}

function reportOptions($custid){
   	include_once "$this->clientpath/customscript/jobQuery.php";
	$jq=new jobQuery;
	$jq->standardClientReportFields();
	$sfa=$jq->sfa;
		   	$pc=new iForm();
		    $tf=kda("fields");
		   	$pc->flabel=$tf;
		   	#gen $pc->ftypes
		   	$pc->allText($tf);
		   	#then override by exception.

		   	$pc->ftypes["fields"]="checkboxarray";

		   	//$oa["fields"]=key2val(array_keys($sfa));
		   	$oa["fields"]=$sfa;

		   	#set up positional arrays
			$new_fa=array_keys($pc->flabel);
			foreach($new_fa as $i=>$fn){
				$new_ft[$i]=$pc->ftypes[$fn];
			}
			$posByName=array_flip(array_values($new_fa));
			$row[]=kda("fields");

			foreach($row as $rowNo=>$rowd){
				foreach($rowd as $colPos=>$fname){
					$apos=$posByName[$fname];
					$new_rowpos[$apos]=$rowNo;
					$new_colpos[$apos]=$colPos;
				}
			}
			$jcalla["fields"]="onclick=saveClientReportSetup(\'$custid\')";


			#setup defaults
			$rsx=singlevalue("customer","reportsetup","customerid",$custid,false);
//			tfw("sxk.txt","$sqlx $sx",true);

			$rsa=array_keys(unserialize($rsx));
			$szz=sizeof($rsa);
			//echo "alert('qqq $custid sz $szz $rsx')\n";
			$pc->defaults["fields"]=$rsa;

		   	$pc->formfields($new_fa,$new_ft,$pc->defaults,$oa,$pc->compulsorya,$numerica,$new_rowpos,$new_colpos,$jcalla);
			$x=$pc->formtext;
			$rx="<table class=normal  style=\"border: 0.075em solid #000;width:400px;\">$x</table>";
		return $rx;
}


function clientReportSetupSaver($custid,$str){

	$da=formCruncher($str);
	$sx=serialize($da);
	$q="update customer set reportsetup='$sx' where customerid=$custid";
	//echo "alert('qqq $custid $sx')\n";
	mysql_query($q);
}


function checkDup($jobid,$sell,$salesgl,$poref){




	$sql="select * from invoice where poref=$poref and jobid=$jobid";


	$query=mysql_query($sql);


	if(mysql_num_rows($query)>0)
	{
	return 1;
	}
	else
	{
	return 0 ;
	}


}


function genInvoice($origin,$jobid,$sell,$salesgl,$poref,$finalclaim){
	$this->logit("enter genInvoice  j:$jobid s:$sell gl:$salesgl po:$poref fc:$finalclaim");
	//enter genInvoice  j:11.00 s:4-0120 gl:290333 po:
	//echo "alert('gen $poref')\n";
	$joba=iqasra(null,null,"jobs","where jobid=$jobid");
	$custid=$joba["customerid"];
	//$p=iqasra(null,null,"purchaseorders","where jobid=$jobid");

	switch($origin){
		case "int":
			$finalclaim = $poref;
		break;
		case "ext":
		//params are OK
	}
	
	
	
	if (isset($finalclaim)){
		$this->logit('geninvoice  '.$jobid.' finalclaim '.$finalclaim);
	} else {
		$this->logit('geninvoice  '.$jobid);
	}
	
	//$sql="select * from invoice where poref=$poref and jobid=$jobid and customerid=$custid";

	//$query=mysql_query($sql);

		//echo "alert('".mysql_num_rows($query)."')\n";
	//if(mysql_num_rows($query)>0)
	//{
	//echo "alert('Invoice with Poref  $poref already exist.')\n";
	//}
	//else
	//{

		$sql="select skillset,hasprogressbilling from jobs where jobid=$jobid";
		$query=mysql_query($sql);
		$row=mysql_fetch_array($query);
		$skillset=$row['skillset'];
		$progressbill=$row['hasprogressbilling'];

		$progclaim=0;
		if($progressbill=='on'){
			$progclaim=1;
		}
		$isfinalclaim=0;
		if($finalclaim=='true'){
			$isfinalclaim=1;
		}


		$skA=unserialize($skillset);
		foreach($skA as $sk=>$sv)
			{
				$trade=$sv['trade'];
				$skill=$sv['skill'];
				//$tA[]=$tsA;
			$sql="select invdetail,skilldesc from tradeskill where  id=".$skill;
			$query=mysql_query($sql);
			$row=mysql_fetch_array($query);

			$invdetail=$row['invdetail'];
			$skilldesc=$row['skilldesc'];
			if($invdetail!='')
				{
			$invdesc.=$invdetail."\n\n";
				}
			}

//$invdesc.="test";


	$idt=date("Y-m-d");
	$da["invoicedate"]=$idt;
	$da["reportingdate"]=$idt;
	$da["customerid"]=$custid;
	$da["custordref"]=$joba["custordref"];
	$da["jobid"]=$jobid;
	$da["shipto"]=$joba["siteaddress"];
	$da["poref"]=$poref;
	$mailto=cardMailLabel($custid);
	$da["mailto"]=$mailto;
	$da["userid"]=$_SESSION["userid"];
	$da["invdoctype"] = "INVOICE";
	$da["isprogressclaim"]=$progclaim;
	$da["isfinalclaim"]=$isfinalclaim;
	#allow unblock
	#echo "alert('unblock')\n";
	$invid=performarrayinsert("invoice",$da,false,false);

	//On final claim, update all invoice reporting dates to the final invoice date.
	if ($isfinalclaim=="1"){
		$data['reportingdate'] = $idt;
		pau('invoice',$data,'jobid',$jobid);
		
		$podata['invoicedate'] = $idt;
		pau('purchaseorders',$podata,'jobid',$jobid);
	}

	$netval=$sell;
	$taxval=round($sell*$_SESSION["gstrate"],2);
	$grossval=$netval+$taxval;
	$lda["invoicedate"]=$idt;
	$lda["customerid"]=$custid;
	$lda["qty"]=1;
	$lda["price"]=$sell;
	$lda["netval"]=$netval;
	$lda["taxval"]=$taxval;
	$lda["grossval"]=$grossval;
	$lda["invoiceno"]=$invid;
	$lda["chargetype"]="nonstock";

	if($invdesc!='')
	{
	$lda["chargedesc"]=$invdesc;
	}
	else
	{
	$lda["chargedesc"]=$joba["jobdescription"];
	}

	$lda["jobid"]=$jobid;
	$lda["glchartid"]=$salesgl;
	$this->logit(print_r($lda,1));
	performarrayinsert("invoicelines",$lda);
	//echo "alert('gen cid $custid')\n";



	//}
	$this->jobInvoices($jobid);
	if($this->viaJquery) return;
	echo "g('jobinvoices').innerHTML='$this->jobinvx' \n";
	//echo "g('jobinvoices').innerHTML='$sql' \n";
}


function invCreditJob($invoiceno){
	#echo "alert('gen  $invoiceno')\n";
	$inva=iqasra(null,null,"invoice","where invoiceno=$invoiceno");
	//$cdt=date("Y-m-d");
	$inva["userid"]=$_SESSION["userid"];
	$jobid=$inva["jobid"];
	$invoicedate=$inva["invoicedate"];
	
	//ANK 2015-12-9 Set credit date the same as the original invoice date for GP Reporting purposes.
	$cdt = $invoicedate;
	#allow unblock
	#echo "alert('unblock')\n";
	unset($inva["invoiceno"]);
	unset($inva["syncdate"]);
	$inva["invoicedate"]=$cdt;
	$inva["esent"]="na";
	#ANK New credit fields 6.3.2015
	$inva["invdoctype"]="CREDIT";
	$inva["origdocno"]=$invoiceno;
	#ANK
	$newInvno=performarrayinsert("invoice",$inva,false,false);

	#ANK 20.5.2015 Mark original invoice as reversed
	$q = "update invoice set isreversed=1 where invoiceno = '$invoiceno'";
	mysql_query($q);
	
	###-test original invoice sent, if not, mark na
	$q="select esent from invoice where invoiceno=$invoiceno";
	$esn=iqasrf($q,"esent");
	if($esn!="on"){
		$iua["esent"]="na";
		pau("invoice",$iua,"invoiceno",$invoiceno);
	}
	###-

	#echo "alert('crid $crid')\n";

	$iq="select * from invoicelines where invoiceno=$invoiceno";
	$ifa=key2val(tfa("invoicelines"));
	$inva=iqa($iq,$ifa);

	$ira["customerid"]=$inva["customerid"];
	$ira["rdate"]=$cdt;
	$newIrid=performarrayinsert("invreceipthead",$ira,false,false);

	foreach($inva as $i=>$cla){
		#foreach($cla as $ffn=>$ffv) echo "alert('ai $ffn ')\n";
		$cla["invoiceno"]=$newInvno;
		$cla["invoicedate"]=$cdt;

		$cla["price"]=0-$cla["price"];
		$cla["netval"]=0-$cla["netval"];
		$cla["taxval"]=0-$cla["taxval"];
		$cla["grossval"]=0-$cla["grossval"];
		$cla["cost"]=0-$cla["cost"];
	
		$this->logit(print_r($cla,1));
		performarrayinsert("invoicelines",$cla);
	//echo "alert('gen cid $custid')\n";
		#Auto invreceipts allocations
		$irl=$ira;
		$irl["customerid"]=$cla["customerid"];
		$irl["irid"]=$newIrid;
		$irl["invoiceno"]=$newInvno;
		$irl["invoicedate"]=$cdt;
		$irl["rmethod"]="autocredit";
		$irl["rvalue"]=$cla["grossval"];
		$irl["syncdate"]=date("Y-m-d");

		$newIrid=performarrayinsert("invreceipts",$irl,false,false);
		$irl2=$irl;
		$irl2["invoiceno"]=$invoiceno;
		$irl2["invoicedate"]=$invoicedate;
		$irl2["rvalue"]=0-$cla["grossval"];
		$newIrid=performarrayinsert("invreceipts",$irl2,false,false);

	}
	#echo "alert('jobid $jobid')\n";

	$this->jobInvoices($jobid);
	echo "g('jobinvoices').innerHTML='$this->jobinvx' \n";
}


function closeQuoteNote($jnid){
	$da["completed"]="on";
	performarrayupdate("jobnote",$da,"jobnoteid",$jnid);
}


function quoteFollowUp($params=null){
	$pa=formCruncher($params);
	#$qfx=quoteFollowup($this);
	#follow up notes instead, need first populated note from fuser
	$jnq="select max(jobnoteid) as jobnoteid from jobnote
	where ntype='quote followup'
	and (completed is null or completed!='on')
	group by jobid";
	$jf=kda("jobnoteid");
	$jna=iqa($jnq,$jf);
	$jnidA=aFV($jna,"jobnoteid");
	$ncond=implode(" or jobnoteid=",$jnidA);

	$q="select j.jobid,max(jobnoteid) as jobnoteid,max(j.date) as date,j.notes,j.contactid,followupdate,j.followupby,jb.customerid,
	concat(co.firstname,' ',co.surname) as contactname
	from jobnote as j
	left outer join jobs as jb on j.jobid=jb.jobid
	left outer join contact as co on j.contactid=co.contactid

	where ntype='quote followup'
	and (completed is null or completed!='on')
	group by j.jobid
	order by followupby,followupdate
	";

	$q="select j.jobid,j.jobnoteid,j.date as date,j.notes,j.contactid,followupdate,j.followupby,jb.customerid,
	concat(co.firstname,' ',co.surname) as contactname
	from jobnote as j
	left outer join jobs as jb on j.jobid=jb.jobid
	left outer join contact as co on j.contactid=co.contactid
	where jobnoteid=$ncond order by followupby,followupdate";

	tfw("qfup.txt","$jnq $q",true);



	#$tf=kda("jobid,customerid,contactid,leaddate,quotefud,qduedate,qfollowuserid");
	$tf=kda("jobid,jobnoteid,notes,customerid,contactid,date,followupdate,followupby,contactname");
	$da=iqa($q,$tf);
	unset($ist);
	$ist=new divTable();
	$tf["complete"]="complete";
	$ist->input_fields["complete"]="checkboxAJ";
	$ist->datefields=kda("leaddate,quotefud,qduedate");
	$ist->hidden=kda("jobnoteid,contactid,customerid");

	$ist->custidToName=true;
	$ist->highlighter=true;
	#$ist->rowID=kda("jobid,jobnoteid");
	$ist->rowIDParams=kda("jobid,jobnoteid");
	#$ist->highlightFname="editTask";
	#jqUiModal";
	#$ist->rowIDstatic=kda("div,div,startTask");
	#jqUiModal('$pmx','div',startTask)

	$ist->noHighlight[]="complete";
	$ist->noHighlight[]="companyname";
	$ist->noHighlight[]="jobid";

			$ist->multilink["jobid"][0]=array("ajaxHrefIconMulti");
  			$ist->ajaxFnameM["jobid"][0]="jobviewNW";
  			$ist->ajaxDataM["jobid"][0]=kda("jobid");
  			#$ist->ajaxStaticDataM["jobid"][0]=kda("jobid");
			$ist->iconM["jobid"][0]="v";


	#UI method
	$ist->highlightFname="jqUiModalExisting";
	$ist->excludeColData=true;
	$ist->rowIDstaticPre=kda("divid=dialogJobNote!I!tn=quotenote!I!tabsid=qTabs!I!tnalt=jobnote!I!title=JobNote!I!");

	$ist->entrymode=true;
	#$ist->linka["completed"]=array("ajaxButton"=>"xx");
	$ist->ajaxFname["complete"]="closeQuoteNote";
	$ist->ajaxData["complete"]=kda("jobnoteid");

	$ist->thStyleCol["detail"]="style='width:200px;'";
	/**/

	$ist->customerView=true;
	$ist->cidToName=true;
 	#$ist->offerDelete=true;
	$ist->tablen="task";
	$_SESSION["refresh"]["task"]="$"."this->ibuffer('taskList');";


	$this->quoteDA=$da;
	$x=$ist->inf_sortable($tf,$da,"Tasks",null,null,false);
	$aS->jcallA=$ist->jcallA;

	$this->sortMe=true;
	tfw("tlx.txt","q $q x $x",true);
	$cx=nl2br($cx);
	$cx.=vClean($x);

    if($this->noEcho) return $cx;

	#$cx=str_replace("\\n","",$cx);

	$divid=$pa["divid"];
	#echo "alert('div $divid')\n";
	#return;
	echo "g('$divid').innerHTML='$cx' \n";

	$this->tableSorter();
	#echo "standardistaTableSortingInit();\n";


#	return $cx;

}


function formeditor($ftype,$divid,$primid,$fid){
	#$fid=$_GET["fid"];
	$ex="<form name=docform action=\"modform.php\" method=\"post\">";
	$ex.="<input name=primid value=$primid type=hidden>";
	$ex.="<input name=ftype value=$ftype type=hidden>";
	$ex.="<input name=fid value=$fid type=hidden>";
	$ocx="onclick=formEditSaver()";
	$tx=$this->findJobText($ftype,$primid);
	#$tx="blah";
	#$urlA["]

	$pc=new iForm();
	$pc->defaultF["newtext"]=$tx;
	$pc->texta_cols["newtext"]=70;
	$pc->texta_rows["newtext"]=8;
	$ntx=$pc->textarea("newtext");
	#$ex.="<textarea name=newtext rows=10 cols=65 id=newtext>$fid $tx</textarea></form>";
	$ex.=$ntx;
	$ex.="<input type=submit value=OK>";
	#$ex.="</form><button $ocx>ok</button>";
	$ex=vClean($ex);
	#$ex=nl2br($ex);
	#$ex="aaa";
	//echo "alert('fff $divid')\n";
	echo "g('$divid').innerHTML='$ex' \n";
}

function formeditsave($str){
	$newda=formCruncher($str);
	$ftype=$newda["ftype"];
	$primidv=$newda["primid"];
	$txt=$newda["newtext"];
	switch($ftype){
		case "rfq":
		$da["rfqcomment"]=$txt;
		$t="rfq";
		$primid="rfqno";
		break;

		case "quote":
		$da["qdescription"]=$txt;
		$t="jobs";
		$primid="jobid";
		break;

		case "jrq":
		$da["pocomments"]=$txt;
		$t="purchaseorders";
		$primid="poref";
		break;

		case "jinv":
		$da["chargedesc"]=$txt;
		$t="invoicelines";
		$primid="invoiceno";
		break;

	}
  // echo "alert('$ftype $t $primid $primidv')\n";
   performarrayupdate($t,$da,$primid,$primidv);
   pagereload();
}

function findJobText($ftype,$primid){
	switch($ftype){
		case "rfq":
		$sqlx="select if(rfq.rfqcomment!='',rfq.rfqcomment,j.jobdescription) as jobdescription
		from rfq as rfq
		left outer join jobs as j
		on rfq.jobid=j.jobid
		where rfq.rfqno=$primid";
		break;

		case "quote":
		$sqlx="select if(j.qdescription!='',j.qdescription,j.jobdescription) as jobdescription
		from jobs as j
		where j.jobid=$primid";
		break;

		case "jrq":
		$sqlx="select if(p.pocomments!='',p.pocomments,j.jobdescription) as jobdescription
		from purchaseorders as p
		left outer join jobs as j
		on p.jobid=j.jobid
		where p.poref=$primid";
		break;

		case "jinv":
		$sqlx="select if(il.chargedesc!='',il.chargedesc,j.jobdescription) as jobdescription
		from invoicelines as il
		left outer join jobs as j
		on il.jobid=j.jobid
		where il.invoiceno=$primid";
		break;

	}
	$jx=iqasrf($sqlx,"jobdescription");
	tfw("jjx.txt",$jx,true);
	#$jx=vClean($jx);
	#$jx=vClean($jx);
	#tfw("jjx2.txt",$jx,true);
	#$jobdesc=mClean($jx);

	$jobdesc=$jx;
	return $jobdesc;
}

function saveJrqDetail($str,$jobid){

	$da=formCruncher($str);
	$pda=$da;
	$poref=$da["poref"];
	$invref=$da["invref"];
	$cost=$da["cost"];
	$pda["invoiceref"]=$invref;
	$pda["cost"]=$cost;
   #echo "alert('uq $poref $cost $str')\n";
   performarrayupdate("purchaseorders",$pda,"poref",$poref);
   #refresh
	$this->displayExternalLog($jobid);
	if(isset($this->djcallA)) foreach($this->djcallA as $jcx) echo $jcx;

}


function qStageChange($jobid,$qstatus,$screenupdate=true){
				#echo "alert('test $qstatus')\n";
				#prevent notified jobs being changed.
				$old_val=singlevalue("jobs","quotestatus","jobid",$jobid,false);


				$alertme=true;
				if($old_val==$qstatus) $alertme=false;

				#prevent completed quotes being anything other than notify.
				$ignoreUnlessAccepted=kda("accepted,approved");
				if(in_array($old_val,$ignoreUnlessAccepted)){

						 if($alertme){
						 if($this->viaJquery){
						   echo "Attempted to change quote status to '$qstatus'. \n However, This quote will remain at '$old_val' ";
						  }else{
						   echo "alert('Attempted to change quote status to \'$qstatus\'. \\n However, This quote will remain at \'$old_val\'')\n";
						  }
						  }
						return;
				}

				$this->logMasterEdit("jobs","jobid",$jobid,"quotestatus",$qstatus);
				#echo "alert('jsc $jobid $jobstage')\n";
				$nja["quotestatus"]=$qstatus;
				if($qstatus=="pending_approval"){
					#first time only
					$oldqdate=singlevalue("jobs","qdate","jobid",$jobid,false);
					elog("found qdate $oldqdate");

					if($oldqdate==""){
						$qdate=date("Y-m-d");
						$this->logMasterEdit("jobs","jobid",$jobid,"qdate",$qdate);
						$nja["qdate"]=$qdate;
					}


				}
				#echo "alert('next try upd qstat $qstatus')\n";
				if($screenupdate){
					#echo "alert('try upd qstat $qstatus')\n";
					if(!$this->viaJquery)	echo "if(g('quotestatus')) g('quotestatus').value='$qstatus' \n";
				}
				performarrayupdate("jobs",$nja,"jobid",$jobid);

				$qjobschange["accepted"]="next_allocate";
				$qjobschange["declined"]="declined";
				$qjobschange["pending_approval"]="wait_client_quote_resp";
				#$qjobschange["pre_approved"]="Waiting_WO";
				#changed 7/3/13
				$qjobschange["pre_approved"]="Waiting_Quote_WO";


				#echo "alert('test $qstatus')\n";
				if(array_key_exists($qstatus,$qjobschange)){
					$jobstage=$qjobschange[$qstatus];
					#echo "alert('new jstage $jobstage')\n";
					$this->jobStageChange($jobid,$jobstage,true);
				}


}

function jobStageChange($jobid,$jobstage,$screenupdate=true){
		#always good idea to update screen if possible
		$this->logit("Jobstagechange $jobid $jobstage");
		
		if($this->viaJquery) $screenupdate=false;
		if(!$this->viaJquery){
		echo "if(g('editSpanjobstage')) g('editSpanjobstage').innerHTML='$jobstage' \n";
		}

				#need an array of valid stages in sequence -not yet avail
				#attempts to lower stage must be validated

				#list of stages where quote must be accepted ie. all job stages
				#then check if quote exists,

				#some ambiguity exists. eg internal allocation - could be to pre inspect
				#JRE
				#in these 2 cases - ask question via popup test quote status.

				#prevent notified jobs being changed.
				$noalertA=kda("wait_client_qte_resp");
				$old_val=trim(singlevalue("jobs","jobstage","jobid",$jobid,false));

				#echo "alert('Attempting $jobstage vs oldval $old_val')\n";

				$alertme=true;
				if(in_array($jobstage,$noalertA)) $alertme=false;
				if($old_val==$jobstage) $alertme=false;


				if(in_array($_SESSION["userid"],$this->powerUsers)){
					$alertme=false;
					$this->skipCheck=true;
				}

				if(($old_val=="client_notified")||($old_val=="Client_Notified")){
					#echo "alert('aa Attempting $jobstage vs oldval $old_val')\n";
					if($alertme) echo "alert('Attempted to change job stage to \'$jobstage\'. \\n However, This job stage will remain at \'Client_notified\'')\n";
					$this->jscFailMess[]="Attempted to change job stage to '$jobstage'. <br> However, This job stage will remain at 'Client_notified'";
					return;
				}
				#prevent completed jobs being anything other than notify.

				//ANK 27.11.2014 Prevent job stage change if info_pending
				if($old_val=="info_pending"){
					if($alertme) echo "alert('Attempted to change job stage to \'$jobstage\'. \\n However, this job stage will remain at \'info_pending\'')\n";
					$this->jscFailMess[]="Attempted to change job stage to '$jobstage'. <br> However, this job stage will remain at 'info_pending'";
					return;
				}
				
				$checkStage=true;
				if($this->skipCheck) unset($checkStage); //flowchart example allows retro steps
				if($checkStage){
					$ignoreUnlessNotify=kda("internal_complete,int_complete,external_complete,next_notify_client");
					$acceptableUpgradeA=kda("next_notify_client,client_notified,Client_notified");
					if(in_array($old_val,$ignoreUnlessNotify)){
							#old rule was..if($old_val!="client_notified"){
							if(!in_array($jobstage,$acceptableUpgradeA)){
								 if($alertme) echo "alert('Attempted to change job stage to \'$jobstage\'. \\n However, This job stage will remain at \'$old_val\'')\n";
								 $this->jscFailMess[]="Attempted to change job stage to '$jobstage'. <br> However, This job stage will remain at '$old_val'";

								return;
							}
					}
				}

				$this->logMasterEdit("jobs","jobid",$jobid,"jobstage",$jobstage);
				#echo "alert('lmed jsc $jobid $jobstage')\n";

				$jobstage=str_replace("_(vialist)","",$jobstage);
				$nja["jobstage"]=$jobstage;
				tfw("jschange.txt","aa",true);
				if($screenupdate){
					tfw("jschangeA.txt","aa js: $jobstage",true);
					echo "if(g('jobstage')) g('jobstage').value='$jobstage' \n";
				}
				performarrayupdate("jobs",$nja,"jobid",$jobid);
}



function customSchedParams(){
			$this->colc=13;
			$this->viewHours=11;
			$this->minsPerUnit=30;

			#navbuffer if inside eg.a job
			$this->navBuffer=80;
			$this->baseLeftPos=150;
			$this->baseTopPos=208;

			$this->headTopPos=178;

#			headTopPos

			#2col liquid css version
			#$this->headTopPos=25;
			#$this->baseTopPos=100;
			#$this->baseLeftPos=-80;

			$this->colw=120;
			$this->rowh=45;
			$this->startingHour=6;
			$this->multirowh=47;

			$this->schedColor["completed"]="#03F";

}


function loadTerritorySet($custid){
	$x="skillshere";
		   	$pc=new iForm();
		    $tf=kda("territory");
		   	$pc->flabel=$tf;

		   	#gen $pc->ftypes
		   	$pc->allText($tf);
		   	#then override by exception.

		   	$pc->ftypes["territory"]="checkboxarray";
		   	$oa["territory"]=stca("territory",null,null,"order by territoryid");

		   	#set up positional arrays
			$new_fa=array_keys($pc->flabel);
			foreach($new_fa as $i=>$fn){
				$new_ft[$i]=$pc->ftypes[$fn];
			}
			$posByName=array_flip(array_values($new_fa));
			$row[]=kda("territory");

			foreach($row as $rowNo=>$rowd){
				foreach($rowd as $colPos=>$fname){
					$apos=$posByName[$fname];
					$new_rowpos[$apos]=$rowNo;
					$new_colpos[$apos]=$colPos;
				}
			}
			$jcalla["territory"]="onclick=saveTerritory($custid)";

			#territory defaults
			$sqlx="select territory from subbyterritory where customerid=$custid";
			$sf=kda("territory");
			$sda=iqa($sqlx,$sf);
			if(is_array($sda)){
				$sda=aFV($sda,"territory");
				$territoryA=$sda;
				#tfw("sxk.txt","$sqlx $sx",true);
				$pc->defaults["territory"]=$territoryA;
			}
		   	$pc->formfields($new_fa,$new_ft,$pc->defaults,$oa,$pc->compulsorya,$numerica,$new_rowpos,$new_colpos,$jcalla);
			$x=$pc->formtext;
			$rx="<table class=normal>$x</table>";
	return $rx;

}

function tsched($meth){
	#echo "alert('ttt')\n";
	/*
	$_SESSION["ttc"]++;
 	include "$this->clientpath/ii/dgt.php";
	$ts=new tsched();
	$ts->manual();
	$x="wtf".$_SESSION["ttc"];
	$x.=$ts->mx;
	echo "g('main-content').innerHTML='$x' \n";
	echo "tsched(50,20,100)\n";
	*/

	#unset($_SESSION["appt"]["scandays"]);
	include_once "$this->basepath/interfaces/iSchedule.php";

	$_SESSION["appt"]["scandays"]++;
	$ocx="onclick";
	//echo "alert('$meth')\n";
	switch($meth){
		case "redraw":
		echo "document.location='dgt.php'\n";;
		break;
		case "pureajax":
		$isc=new iSchedule($this);
		echo "g('dragsched').innerHTML=''\n";
		echo "alert('blank')\n";
		$isc->recalcUnits();
		$msx=vClean($isc->makeSched());
		for($r=0;$r<8;$r++){
			echo "alert('free $r $c')\n";
			for($c=0;$c<50;$c++){
			 echo "if(g('draggable-grid-$r-$c')) $('#draggable-grid-$r-$c')=null;\n";
			}
		}
		echo "g('dragsched').innerHTML='$msx'\n";
		echo "alert('ff')\n";
		echo "g('schedx').innerHTML='msx'\n";
		break;
	}
	#echo "<button>re draw</button>cols: $isc->colc rows: $isc->rowc";


}

function buildLoadJCall(){
	#for onload jcall to route left menu functions via reload rather than ajax
	if(isset($this->u1)){
		$this->ojf=$this->u1."('".$this->u2."')";
		$this->onloadJcall="onload=$this->ojf";
	}
	$this->onloadJcall="onload=alert('nol')";
	tfw("ojc.txt","tj $this->ojf $this->onloadJcall",true);
}

function supplierOnSched($id){
	#echo "alert('test $id')\n";
	#checks if supplier, then checks if needs
	#to be added or removed from user list where necessary
	$custtype=singlevalue("customer","custtype","customerid",$id,false);
	if(strtoupper($custtype)!="SUPPLIER") return;
	$quasiU=singlevalue("customer","customtext1","customerid",$id,false);
	$q="select * from users where email=$id";
	$alreadyU=qRowCount($q);
	if($quasiU){
		#echo "alert('add $alreadyU')\n";
		if($alreadyU<1){
			#add to schedule and permissions
			$tf=kda("customerid,companyname,firstname,surname");
			$q="select customerid,companyname,firstname,surname from customer where customerid=$id";
			$ca=iqasra($q,$tf,"customer");

			$maxq="select max(userorder) as maxu from users";
			$maxu=iqasrf($maxq,"maxu");
			$newu=$maxu+10;

			#echo "alert('add $q')\n";
			$ua["email"]=$id;
			$ua["firstname"]=$ca["firstname"];
			$ua["surname"]=$ca["surname"];
			$cname=$ca["companyname"];
			$cname=str_replace(" ","_",$cname);
			$cname=str_replace("'","",$cname);
			$uid=$cname;
			$ua["userid"]=$uid;
			$ua["userorder"]=$newu;
			$ua["useraccess"]="contractor";
			performarrayinsert("users",$ua);

			readusersa();
			foreach($GLOBALS["usersa"] as $ui){
				unset($upa);
				unset($ura);
				$upa["userid"]=$uid;
				$upa["colleagueid"]=$ui;
				performarrayinsert("userviewspermitted",$upa);

				#echo "alert('add RQ $ui - $uid list')\n";
				$ura["userid"]=$ui;
				$ura["colleagueid"]=$uid;
				performarrayinsert("userviewsrequested",$ura);
			}
		}
	}else{
		$tf=kda("userid,email");
		$q="select userid,email from users where email=$id";
		$ua=iqasra($q,$tf,"users");
		$uid=$ua["userid"];
		if($uid!=""){
			#echo "alert('remove')\n";
			$kd="delete from users where userid='$uid'";
			mysql_query($kd);
			$kd="delete from userviewspermitted where userid='$uid' or colleaugueid='$uid'";
			mysql_query($kd);
			$kd="delete from userviewsrequested where userid='$uid' or colleaugueid='$uid'";
			mysql_query($kd);
		}

	}
}


function saveChain($str){
	$da=formCruncher($str);
	$custid=$da["custid"];
	#echo "alert('save $custid $str')\n";
	#if str exists, its an un-check situation, destroy from session.
	if(in_array($str,$_SESSION["reportPack"][$custid])){
		#echo "alert('unset str')\n";
		$posa=array_flip($_SESSION["reportPack"][$custid]);
		$pos=$posa[$str];
		unset($_SESSION["reportPack"][$custid][$pos]);
	}else{
		#echo "alert('add str') \n";
		$_SESSION["reportPack"][$custid][]=$str;
	}

	$dx.="<ul>";
	foreach($_SESSION["reportPack"][$custid] as $pos=>$rr){
		$pra=formCruncher($rr);
		$rdesc=$pra["rdesc"];
		$divx[$pos]=$rdesc;
		$dx.="<li>$rdesc</li>";
	}
	$dx.="</ul>";
	echo "g('reportPack').innerHTML='$dx' \n";

}

function cancelJob($jobid,$newstage='cancelled'){
			$this->jobStageChange($jobid,$newstage,false);
			$dq="update jobs set jobstage='$newstage' where jobid=$jobid";
			mysql_query($dq);
			$bsx=$_SESSION["backsearch"]["jobs"];
			$bx="<div class=\"nav1\"><button $bsx>Back to search</button></div>";
			echo "g('main-content').innerHTML='$bx' \n";

			#also decline quote if necessary
			$dq="update jobs set quotestatus='declined' where jobid=$jobid and quotestatus!=''";
			mysql_query($dq);



			jtop();
			#back to search.
 }

 function delJob($jobid){
 			$dq="delete from jobs where jobid=$jobid";
 			mysql_query($dq);
 			#echo $dq;
 			$bsx=$_SESSION["backsearch"]["jobs"];
 			$bx="<div class=\"nav1\"><button $bsx>Back to search</button></div>";
 			if($this->viajquery){
 			return;
 			}
 			echo "g('main-content').innerHTML='$bx' \n";
 			jtop();
 			#back to search.
   }



 function mailJobNoteSave($note=null,$jobid,$recipA=null){
    $tablen="jobnote";
    $t=date("d M Y, H:i");
    $uid=$_SESSION["userid"];
    $rx=implode(";",$recipA);
    $uid=$_SESSION["userid"];
    $newda["notes"]=$note." emailed $t to $rx  by $uid";
    $newda["jobid"]=$jobid;
    $newda["date"]=date("Y-m-d");
    $newda["timestamp"]=date("Y-m-d h:i");
    $newda["userid"]=$uid;
    #$newda["notetype"]="internal";
    $newda["notetype"]=$this->clientNote?"client":"internal";//eg via quote

    $id=performarrayinsert($tablen,$newda);
 }

 function qsent($jobid,$view=true,$recipA=null){
    //echo "alert('qsent $jobid')\n";
 	$this->clientNote=true;

	$this->mailJobNoteSave("Quote",$jobid,$recipA);

	//Send Site Contact email
	include_once($this->clientpath."/contractor/Include/Class/cls.mail.php");
	$objmail = new clsmail;
	$objmail->eventEmail($jobid,"QUOTESUBMIT");

	//Update sent date for notification
	$q = "UPDATE jobs set qsendate = CURRENT_DATE() WHERE JOBID = $jobid";
	mysql_query($q);
	
/*
    $tablen="jobnote";
    $t=date("d M Y, H:i");
    $uid=$_SESSION["userid"];
    $rx=implode(";",$recipA);

	$newda["notes"]="Quote Emailed $t to $rx  by $uid";
    $newda["jobid"]=$jobid;
    $newda["date"]=date("Y-m-d");
    $newda["userid"]=$uid;
    $id=performarrayinsert($tablen,$newda);
*/
    #update quotestatus.
    $qstage="pending_approval";
    $view=1;
	$this->qStageChange($jobid,$qstage,$view);
    $jobstage="wait_client_quote_resp";
	$this->jobStageChange($jobid,$jobstage,$view,true);
	tfw("qsent.txt","qs $qstage js $jobstage",true);
	if($view){
	if(!$this->viaJquery) echo "jobview('$jobid');\n";
	}
 }


function unbillableDetail($ncharge=null,$jobstage=null){
	#populates iiscreen/unbilledgrid
	tfw("aaubd.txt","hey",true);
	#echo "alert('detail $mode $nmode')\n";
	if($ncharge=="unbillable"){
		$ncx=" and j.nonchargeable='on'";
	}else{
		$ncx=" and (j.nonchargeable is null or j.nonchargeable!='on')";
	}

	tfw("ubjobq.txt",$q,true);
		$q="select distinct j.jobid,j.leaddate,j.duedate,j.jobstage,
		j.jcompletedate,j.estimatedsell,c.companyname,
		sum(p.cost) as jobcost
		from purchaseorders as p
		left outer join jobs as j
		on p.jobid=j.jobid
		left outer join invoicelines as i
		on j.jobid=i.jobid
		left outer join customer as c
		on j.customerid=c.customerid
		where j.leaddate>'2008-01-01'
		and (j.jobstage='$jobstage')
		$ncx
		and p.cost>0
		and p.completed='on'
		and i.invoiceno is null
		group by jobid";

	tfw("ubx.txt",$q,true);
	elog("ubd $ncharge $jobstage $q","ciface 7805");
	$tf=kda("companyname,jobid,leaddate,estimatedsell,jobcost");
	$tf["leaddate"]="Job Date";
	$tf["duedate"]="Cl.Due Date";
	$tf["jobstage"]="Job Stage";
	$tf["jcompletedate"]="Completed Date";
	$tf["estimatedsell"]="$ Quoted";
	$tf["jobcost"]="$ Costs";
	$da=iqa($q,$tf);

	$ist=new divTable();
	$ist->rTitle="Unbillable Job Report";
	$ist->datefields=kda("leaddate,jcompletedate,duedate");
	$ist->total_fields=kda("jobcost");
	#$nx=$ist->iFixedHead($tf,$da);

	$ist->highlighter=true;
	#$ist->highlightFname="jobviewInParent";
	$ist->highlightFname="jobviewNW";
	$ist->rowID=kda("jobid");


	$nx=$ist->inf_sortable($tf,$da,"Detail Report $nox");
	#$cx.=vClean($condx);
	$cx.=vClean($nx);
	#$cx.=vClean($q);
   	#$cx.="got $jc jobs $condx $q";
   	echo "g('detail').innerHTML='$cx'\n";
   	echo $GLOBALS["initLookupCall"];
}


function unbilledGridDetail2($mode,$nmode){
	include_once "$this->clientpath/customscript/jobQuery.php";
   	$jq=new jobQuery;
	$jq->userTerritorySetup();
	$jq->userFMSetup();
	$jq->buildOpenJrq();
	$jq->unbilledGridCounter();


	#$condx=" where j.jobid=".implode(" or j.jobid=",$ja);
 	#$condx2="where (dcompleted='on' or pcompleted='on')";
 	$condx3=" and (dcompleted='on' or pcompleted='on')";


	$cq="select * from cjb where jobcost>0 and jobstage like '%client_notified%' $condx3";
	$tf=kda("jobid,jobcost,jobstage,poref");
	$ca=iqa($cq,$tf);
	$szc=sizeof($ca);


	$ist=new divTable();
	$ist->rTitle="Unbilled Job Report";
	$ist->datefields=kda("leaddate,jcompletedate,duedate");

	$ist->highlighter=true;
	#$ist->highlightFname="jobviewInParent";
	$ist->highlightFname="jobviewNW";
	$ist->rowID=kda("jobid");
	$ist->total_fields=kda("estimatedsell,jobcost,projmargin,tcommit");


	$nx=$ist->inf_sortable($tf,$ca,"Basic Detail Report  - Full estimates report pending.. $nox $jq->uTerrx $jq->uFmx");
	#$cx.=vClean($condx);
	$cx.=vClean($nx);
	#$cx.=vClean($q);
   	#$cx.="got $jc jobs $condx $q";

   	echo "g('detail').innerHTML='$cx'\n";

   	#echo "g('detail').innerHTML='cx6  $szc'\n";

}



function unbilledGridDetail($mode,$nmode){
	#populates iiscreen/unbilledgrid
	#echo "alert('detail $mode $nmode')\n";
	include_once "$this->clientpath/customscript/jobQuery.php";
   	$jq=new jobQuery;
	if($nmode=="notified"){
		$jq->notifiedOnly=true;
		$nox=" (Client notified only)";
		#$mode="notified";
	}else{
		$jq->excludenotified=true;
		$nox=" (Exception report - Excludes Client notified)";
		#$mode="excl_notified";
	}
	$jq->userTerritorySetup();
	$jq->userFMSetup();
	$jq->buildOpenJrq();
   	$jq->unbilledGridCounter();
   	$jc=$jq->ubjc[$mode];

   	switch($mode){
   		case "cost":
   		$ja=$jq->pojA;
   		break;

   		case "qnc":
   		$ja=array_keys($jq->qtEst);
   		break;

   		case "qnv":
   		$ja=array_keys($jq->qteRqd);
   		break;

   		case "ninf":
   		foreach($jq->jA as $i=>$jobid){
   			if(in_array($jobid,array_keys($jq->qteRqd))) unset($jq->jA[$i]);
   			if(in_array($jobid,$jq->pojA)) unset($jq->jA[$i]);
   			if(in_array($jobid,array_keys($jq->qtEst))) unset($jq->jA[$i]);
   		}
   		$ja=$jq->jA;

   		break;
   	}
   #	foreach($ja as $key=>$val){
   #		$cx.="<br>$key:$val";
   #	}

	$condx=" where j.jobid=".implode(" or j.jobid=",$ja);
 	$condx2="where (dcompleted='on' or pcompleted='on')";

	#perform margin analysis;
	$jq->nonClosed=true;
	$jq->buildCostSummary();
	$jq->costSummaryFromTemp();

	$jq->extrajoins=" left outer join jobsummary as js on j.jobid=js.jobid ";
/*	$jq->extraFields=",if(j.closed='on',j.grossprofit,js.projmargin)as projmargin,
	if(j.closed='on',j.totnetcosts,js.tcommit)as tcommit,
	if(j.closed='on',j.margin,js.marginpcnt)as marginpcnt ";
*/
	$jq->extraFields=",if(j.closed='on',j.estimatedsell-j.totnetcosts,j.estimatedsell-js.tcommit)as projmargin,
	if(j.closed='on',j.totnetcosts,js.tcommit)as tcommit,
	if(j.closed='on',round(100*(j.estimatedsell-j.totnetcosts)/j.estimatedsell,2),round(100*(j.estimatedsell-js.tcommit)/j.estimatedsell,2))as marginpcnt ";



	$q=$jq->coreJobQueryBilling($condx,$condx2);


	tfw("ubjobqSum.txt"," $jq->sumq",true);
	tfw("ubjobq.txt"," $q",true);
	$tf=kda("companyname,custordref,jobid,leaddate,estimatedsell,jobcost,tcommit");
	$tf["leaddate"]="Job Date";
	$tf["custordref"]="Cust Ord Ref's";

	$tf["duedate"]="Cl.Due Date";
	$tf["jobstage"]="Job Stage";
	$tf["jcompletedate"]="Completed Date";
	$tf["estimatedsell"]="$ Quoted";
	$tf["jobcost"]="$ Costs";
	$tf["tcommit"]="$ Committed";
	$tf["projmargin"]="$ Projected GP";
	$tf["marginpcnt"]="% Margin";


	$da=iqa($q,$tf);

	$ist=new divTable();
	$ist->rTitle="Unbilled Job Report";
	$ist->datefields=kda("leaddate,jcompletedate,duedate");

	#$nx=$ist->iFixedHead($tf,$da);


	$ist->highlighter=true;
	#$ist->highlightFname="jobviewInParent";
	$ist->highlightFname="jobviewNW";
	$ist->rowID=kda("jobid");
	$ist->total_fields=kda("estimatedsell,jobcost,projmargin,tcommit");

	$jobidA=aFV($da,"jobid");
	$this->constructMultiRefs_fromJob($jobidA);
	foreach($da as $i=>$row){
		$jobid=$row["jobid"];
		$cref=$this->extras["custordref"][$jobid];
		elog("8245 $i $jobid $cref");
		$da[$i]["custordref"]=$cref;
	}



	$nx=$ist->inf_sortable($tf,$da,"Detail Report $nox $jq->uTerrx $jq->uFmx");
	#$cx.=vClean($condx);
	$cx.=vClean($nx);
	#$cx.=vClean($q);
   	#$cx.="got $jc jobs $condx $q";
   	echo "g('detail').innerHTML='$cx'\n";
   	echo $GLOBALS["initLookupCall"];




 }


	function checkQteAccepted($jobid){
		$q="update jobs set quotestatus='accepted'
		where jobid=$jobid and quoterqd='on' and quotestatus!='accepted'
		and jobstage!='newjob'	";
		mysql_query($q);
		$raf=mysql_affected_rows();
		#echo "alert('raff $raf')\n";
		#return;
		if($raf>0){
		#	echo "alert('Note. Quote status has been changed to 'accepted')\n";
			return true;
		}
		return false;
	}


 function externalImport($eij){
 	$fa=formCruncherMultiLine($eij,"jline");
	foreach($fa as $da){
		$lno=$da["lineno"];
		$import=$da["import"];
		$src=$da["src"];
		$iix.="l:$lno i:$import";
		if($src!="duplicate"){
			if($import){
				$ia[]=$lno;
			}
		}
		tfw("fcm.txt","iix $iix",true);
	}
	if(is_readable("$this->basepath/infclasses/fileImports.php")){
		include_once "$this->basepath/infclasses/fileImports.php";
		$l=new loader();
		$l->tempDataLoad();
		$mapname=$_SESSION["importMapName"];
		$ma=$_SESSION["importMap"];
		switch($mapname){
			case "ANZ Branches":
			$this->anzsites($l,$ia,$ma);
			break;
			default:
			foreach($l->fileData as $lno=>$row){
				if(in_array($lno,$ia)){
							$fa=explode($l->fb,$row);
							foreach($fa as $i=>$dv){
								$viewI=$i+1;
								$fn=$ma[$viewI];
								if($fn!=""){
									$tf[$fn]=$fn;
									#echo "alert('$fn') \n";
									$nda[$fn]=$dv;
								}
							}
							$cname=$nda["companyname"];
							if($cname==""){
								$fname=$nda["firstname"];
								$sname=$nda["surname"];
								$nda["companyname"]="$fname $sname";
							}
							$nda["custtype"]="Supplier";
							if($nda["src"]=="duplicate") unset($nda);
							performarrayinsert("customer",$nda);
				}
			}
			break;
		}
	}
 }

function anzsites($l,$ia,$ma){
	tfw("anz.txt","do it",true);
			foreach($l->fileData as $lno=>$row){
				$lx.="row $lno";
				if(in_array($lno,$ia)){
							$fa=explode($l->fb,$row);
							foreach($fa as $i=>$dv){
								$viewI=$i+1;
								$fn=$ma[$viewI];
								$lx.="fn $fn for $viewI";
								if($fn!=""){
									if($dv!=""){
										$tf[$fn]=$fn;
										#echo "alert('$fn') \n";
										$ux.=" $fn = $dv";
										$nda[$fn]=$dv;
									}
								}
							}
							$sitesuburb=$nda["sitesuburb"];
							if(trim($sitesuburb)!=""){
								$cname="ANZ - $sitesuburb";
								$aq="select labelid from addresslabel where ldesc='$cname' and customerid=1682";
								$aid=iqasrf($aq,"labelid");
								if($aid>0){
									$xx.="update $aid $cname";
									performarrayupdate("addresslabel",$nda,"labelid",$aid);
								} else{
									$nx.="new $cname";
									$nda["customerid"]="1682";
									$nda["ldesc"]=$cname;
									performarrayinsert("addresslabel",$nda);
								}
								unset($nda);
							}

				}
			}

	tfw("newzna.txt","updx $xx $nx xxxx ",true);
}

function customTempSearch($tid,$context="normal",$buffd=false){
	switch($tid){
		case "inventory":
		$da=$this->loadSearchable($tid);
		$soc["fielddesc"]="Supplier Code";
		$soc["fieldid"]="altprodid";
		$soc["type"]="text";
		$da[]=$soc;

		$this->tabbedNav("search".$tid);
		$menux=stripslashes($this->tabrow);
		#$menux=str_replace("tabselect(","tabselect('divid=guts',",$menux);
		#$menux=str_replace("tabselect(","tabselect('search',",$menux);
		$cx.=vClean($menux);
		$cx.="<input id=searcht value=$tid type=hidden>";
		$cx.="<div id=guts>";



		#$this->tempSearchF=$da;
		$this->searchableFields($tid,"normal",$da);
		$cx.=$this->alphalist($tid);
		$cx.=$this->sfx;
		$cx.="<div id=\"precount\"></div>";
			if(isset($_SESSION["searchq"][$context][$tid])){
			$this->searchResult($_SESSION["searchq"][$context][$tid],$tid);
			$rx=vClean($this->searchx);
			$cx.="<div id=\"searchResultGrid\">$rx</div>";
		}else{
			$cx.="<div id=\"searchResultGrid\"></div>";
		}
		$cx.="</div></div>";

		echo "g('main-content').style.display = 'block'\n";
		echo "g('main-content').innerHTML='$cx'\n";
		break;

		case "sitecont":
		#echo "site";
		$da[0]["fieldid"]="firstname";
		$da[0]["fielddesc"]="First name";
		$da[1]["fieldid"]="Surname";
		$da[1]["fielddesc"]="Surname";
		$da[2]["fieldid"]="position";
		$da[2]["fielddesc"]="Position";
		$da[3]["fieldid"]="companyname";
		$da[3]["fielddesc"]="Client/Company Name";
		$da[4]["fieldid"]="sitesuburb";
		$da[4]["fielddesc"]="Site Suburb";
		$da[5]["fieldid"]="siteline1";
		$da[5]["fielddesc"]="Customer Name";
		/*
		$da[6]["fieldid"]="customerid";
		$da[6]["fielddesc"]="Customer ID";
		$da[7]["fieldid"]="ref";
		$da[7]["fielddesc"]="ref";
		*/


		$this->tabbedNav("search".$tid);
		$menux=stripslashes($this->tabrow);
		$cx.=vClean($menux);
		$cx.="<input id=searcht value=$tid type=hidden>";
		$cx.="<div id=guts>";


		$this->searchableFields($tid,"normal",$da);
		$cx.=$this->alphalist($tid);
		$cx.=$this->sfx;
		$cx.="<div id=\"precount\"></div>";
			if(isset($_SESSION["searchq"][$context][$tid])){
			$this->searchResult($_SESSION["searchq"][$context][$tid],$tid);
			$rx=vClean($this->searchx);
			$cx.="<div id=\"searchResultGrid\">$rx</div>";
		}else{
			$cx.="<div id=\"searchResultGrid\"></div>";
		}
		$cx.="</div></div>";
		if($buffd) {
			echo $cx;
			return;
		}
		#tfw("scx.txt",$cx,true);
		echo "g('main-content').style.display = 'block'\n";
		echo "g('main-content').innerHTML='$cx'\n";

		break;
	}
}

function customGridSearches($table){
	switch($table){
		case "sitecont":
		$sqlx="select * from contact as i ";
		break;
	}
	return $sqlx;
}


function customTempSearchContextFields($table,$context,$tf){
	if($table=="inventory"){
		if($context=="polines"){
				$ntf["prodid"]="Prod ID";
				$ntf["shortdesc"]="Description";
				$ntf["altprodid"]="Supplier Order Code";
				$ntf["costex"]="fob";
				$ntf["currencydesc"]="Currency";
				$ntf["poqty"]="PO Qty";
				$ntf["buyunit"]="unit";
				$ntf["umc"]="umc";
				$ntf["exchange"]="exchange";
				$ntf["companyname"]="Supplier";
		}
	}
 return $ntf;
}


function customListableFields($tid,$context="polines"){
	switch($tid){
		case "inventory":
			#if($context=="polines"){
				$this->loadListableFields($tid);
				$this->fnx.=",altprodid,companyname";
				$this->fieldNamesA["altprodid"]="altprodid";
				$this->fieldNamesA["companyname"]="Supplier";
			#}
		break;
			case "sitecont":
/*			$da[0]["fieldid"]="firstname";
			$da[0]["fielddesc"]="First name";
			$da[1]["fieldid"]="Surname";
			$da[1]["fielddesc"]="surname";
			$da[2]["fieldid"]="customername";
			$da[2]["fielddesc"]="Company Name";
			$da[3]["fieldid"]="sitesuburb";
			$da[3]["fielddesc"]="Site Suburb";
*/
		#echo "alert('clf')\n";
			$this->fieldNamesA["ref"]="Ref No";
			$this->fieldNamesA["customerid"]="Customer ID";
			$this->fieldNamesA["firstname"]="First name";
			$this->fieldNamesA["surname"]="Surname";
			$this->fieldNamesA["companyname"]="Client/Company Name";
			$this->fieldNamesA["position"]="Position";
			$this->fieldNamesA["phone"]="phone";
			$this->fieldNamesA["mobile"]="mobile";
			$this->fieldNamesA["email"]="email";
			$this->fieldNamesA["source"]="Source";
			$this->fieldNamesA["customername"]="Customer Name";
			$this->fieldNamesA["suburb"]="Suburb";
			$this->fnx=implode(",",array_keys($this->fieldNamesA));


		break;

	}
}


function buildCustomSearch($context,$t,$condx){
	$condx=strtolower($condx);
	switch($t){
		case "sitecont":
		#handle non existing firstname, surnam
		$sitecondx=$condx;
		#echo "alert('look')\n";
		if(strpos($condx,"firstname")){
				$sitecondx=str_replace("i.firstname","substring_index(i.sitecontact,' ',1)",$sitecondx);
		}
		if(strpos($condx,"surname")){
				$sitecondx=str_replace("i.surname","substring_index(i.sitecontact,' ',-1)",$sitecondx);
		}
		if(strpos($condx,"position")){
				$sitecondx=str_replace("i.position","i.siteposition",$sitecondx);
		}
		if(strpos($condx,"sitesuburb")){
				$condx=str_replace("i.sitesuburb","c.shipsuburb",$condx);
		}

		$fmcondx=str_replace("i.sitecontact","i.sitefm",$sitecondx);

		#tfw("alllx.txt","ccc try:$try c: $condx scx: $sitecondx",true);
		#echo "alert('bcs $t')\n";
		$qa[]="
		select i.customerid,i.contactid as ref,c.companyname,i.firstname,i.surname,i.position,i.phone,
		i.mobile,i.email,
		'contacts' as source,
		'' as customername,
		c.shipsuburb as suburb
		from contact as i
		left outer join customer as c
		on i.customerid=c.customerid
		$condx";

		$avoidContactFields=kda("siteline1");
		foreach($avoidContactFields as $afn){
			if(strpos($condx,$afn)){
				unset($qa);
			}
		}

		$qa[]="
		select i.customerid,i.labelid as ref,c.companyname,
		substring_index(i.sitecontact,' ',1) as firstname,
		substring_index(i.sitecontact,' ',-1) as surname,
		'site contact' as position,i.sitephone,
		i.sitemobile as mobile,i.siteemail as email,
		'site' as source,
		i.siteline1 as customername,
		i.sitesuburb as suburb
		from addresslabel as i
		left outer join customer as c
		on i.customerid=c.customerid
		$sitecondx
		";

		$qa[]="
		select i.customerid,i.labelid as ref,c.companyname,
		substring_index(i.sitefm,' ',1) as firstname,
		substring_index(i.sitefm,' ',-1) as surname,
		'site fm' as position,i.sitefmph as sitephone,
		'' as mobile,'' as email,
		'site' as source,
		i.siteline1 as customername,
		i.sitesuburb as suburb
		from addresslabel as i
		left outer join customer as c
		on i.customerid=c.customerid
		$fmcondx
		";


		$sqlx=implode(" union ",$qa);
		$this->customSearchx=$sqlx;


				$this->highlighter=true;
				$this->rowID=kda("customerid,source");
				$this->highlightFname=jumpSiteContact;
				$this->input_fields["customerid"]="hidden";

				$this->extralinka["firstname"]=array("ref"=>"ref","source"=>"source");
				$this->linka["firstname"]=array("hrefClueTip"=>"../../infbase/content/bizcard.php?mode=vca");
				$this->displaysuppress["firstname"]=true;



				$historypack[]=$this->highligher;
				$historypack[]=$this->rowID;
				$historypack[]=$this->highlightFname;
				$historypack[]=$this->input_fields;
				$historypack[]=$this->extralinka;
				$historypack[]=$this->linka;
				$historypack[]=$this->displaysuppress;
				$srz=serialize($historypack);
				$_SESSION["highlightpack"][$t]=$srz;
				$_SESSION["backsearch"]["customer"] ="onclick=javascript:searchPage(\'sitecont\')";

		break;
	}
	return $sqlx;
}

function customTempQuery($context,$t,$searchx){
	#tfw("ctq.txt",$searchx,true);
	return;
	echo "alert('ctq $t')\n";
	switch($t){
		case "inventory":
		$sqlx="	select $ffid from $t where $ffid like '$kv%'";
		$this->customSearchx=$sqlx;
		break;
	}
}

function customJoiners($t,$searchda){
	#foreach($searchda as $sfn=>$sfid) echo "alert('cjoiner fn:$sfn fv:$sfid')\n";
	switch($t){
		case "inventory":
		#only use if the search is based on a joined field.
		if($searchda["searchaltprodid"]!=""){
			#return;
			$j=" left outer join prodsuppliers as ps on i.prodid=ps.prodid";
			$j.=" left outer join customer as c on ps.supplierid=c.customerid";
			$invF=key2val(tfa("inventory"));
			$fx="i.".implode(",i.",$invF);
			$fx.=",ps.altprodid,c.companyname";
			$this->namedSearchFields=$fx;
		}else{
			return;
		}
		break;

		case "sitecont";
		$j.=" left outer join customer as c on i.customerid=c.customerid";
		break;
	}
	return $j;
}

function findRecip($fid,$ref){
	#echo "fid: $fid ; ref: $ref";
	$q="select if(j.billingemail!='',j.billingemail,c.email)as email
	from jobs as j
	left outer join customer as c
	on j.customerid=c.customerid
	where j.jobid=$ref";
	#singlevalue($tableid,$fieldid,$idfield,$idval,$numeric=false)
	$email=iqasrf($q,"email");

	switch($fid){
		case "dcfmwo07J":
		$t="purchaseorders";
		$custid=singlevalue($t,"supplierid","poref",$ref,true);
		$email=singlevalue("customer","email","customerid",$custid);
		break;
		case "dcfmrfq07J":
		$t="rfq";
		$custid=singlevalue($t,"supplierid","rfqno",$ref,true);
		$email=singlevalue("customer","email","customerid",$custid);
		break;

		case "dcfmq07J":
		$t="jobs";
		$custid=singlevalue($t,"customerid","jobid",$ref,true);
		break;

	}
	$this->custemail=$email;
	$this->customerid=$custid;
	if($this->custemail=="") $this->custemail="no email provided yet. $fid $ref $custid";
}

function customModal($tn=null,$id=null){
	#echo "<br><br>tt $tn ii $id";
	#return "x";
	elog("cm $tn","cif 8504");
	unset($this->customCall);
	#echo "alert('tn $tn')\n";

	#common functions
		$jobid=$this->paramsA["jobid"];
		$saveRefreshScript="
		//alert('upx');
		function updateButtons(){
			$('#dialogGeneric').dialog('option','buttons',{
		 			'close & refresh notes': function() {
	 					$(this).dialog('close');
	 					refreshNoteList();
						$this->otherRefreshCalls
	 				}
			});

		 }

		 function refreshNoteList(){
  			var url = '../../infbase/ajax/ajaxpostBack.php';
			data='params=!I!p=yes!I!url=jobNoteSaver!I!mode=refresh!I!jobid=$jobid';
			$.ajax({
					url: url,type: 'POST',
					data: data, async: false,
					cache: false,
					success: function (html) {
					 $('#jobnotes').html(html);
					}
			 })
		}
		";




	switch($tn){
		case "jobnote":
		$rx="new job notes ";
		$this->ui=true;
		$jobid=$this->paramsA["jobid"];
		$ntype=$this->paramsA["ntype"];


		$via=$this->paramsA["via"];
		#echo "alert('cmm $tn $ntype $id')\n";
		$this->paramsA["jobnoteid"]=$this->paramsA["jobnoteid"]==""?$id:$this->paramsA["jobnoteid"];
		$tp=$this->paramsA["jobnoteid"];
		#echo "<br>tp $tp";
		$rx=$this->quickJobNote($jobid,$ntype,$id,$via);

		$title=$this->dialogTitle;
		#$this->jcallA[]="$('#dialogJobNote').dialog('option','title','Edited $ntype');";
		$this->customCall.="$('#dialogJobNote').dialog('option','title','$title');\n";
		$standardSave="saveJobNote();	$(this).dialog('close');";

		$nextFnx="updateButtons()";
		switch($ntype){
			case "hold":
			$blab="Hold Job now";
			break;
			case "del":
			$blab="Delete Job now";
			break;
			case "nobill":
			$blab="Flag Un-Chargeable";

			break;
			case "can":
			$blab="cCancel Job Now";
			break;
			case "extend":
			$blab="Extending Job";
			$nextFnx="mailButtons()";
			break;
			case "complete":
			$blab="Save Completion Note";
			break;

			case "close":
			$closingNote=true;
			$blab="Save Closing Note";
			break;

			default:
			$blab="OK";
			if($this->editmode=="edit") $blab="Update";
			if($this->editmode=="new") $blab="Save new note";
			break;
		}

		#$this->customCall.="$('#dialogJobNote').dialog('option','buttons',{'$blab': function() { if(saveJobNote('$ntype')){ $(this).dialog('close');} } } ); \n";
		//$this->customCall="alert('saver')\n;";
		$this->customCall.="$('#dialogGeneric').dialog('option','buttons',{
			'$blab': function() {
					saveNoteNow();
				}
			}
		);
		function saveNoteNow(){
			//alert($('#reason').val());
			if($('#reason').val()=='Choose an option'){
				alert('Please select a reason');
				return false;
			}
			if($('#ntype').val()=='Choose an option'){
				alert('Please make a selection from Ntype.');
				$('#ntype').focus();
				return false;
			}
						
			
			var str=gdf('jobnote','!A!','!eq!') ;
			//alert('saving'+str);
			//var notetype=$('#notetype').val();
			var notetype=$('input[name=notetype]:checked', '#jobnoteF').val() ;
			
			var msg = 'Saving as a ' + notetype + ' note. Are you sure?';
			console.log('notetype',notetype);
			
			if(notetype == 'completion'){
				var notelength = $('#notelength').val();
				var thisnotelength = $('#notes').val();
				console.log(notelength,thisnotelength.length);
				if (notelength >= thisnotelength.length){
					alert('You didn\'t enter the description of completed works.... Now go back and do it.');
					return false;
				}
				if (thisnotelength.length - notelength < 10){
					alert('Not much of a description. Please go back and expand.');
					 return false;
				   
				}
				
			
			}
			
			switch (notetype){
				case 'client':
				 msg = 'Saving as a ' + notetype + ' note which will be visible on the client portal and in reports. Are you sure?';
				 break;
				case 'internal':	
				 msg = 'Saving as an internal note. Are you sure?';
				 break;
				default:
				 				
			}

			var cfm = confirm(msg);
			if (!cfm) {
				return false;
			}
		
			
			var ntype=$('#ntype').val();
  			var url = '../../infbase/ajax/ajaxpostBack.php';
			data='params=$this->otherParams!I!p=yes!I!url=jobNoteSaver!I!mode=closure!I!str='+str;
			var taskN=gdf('tasknote','!A!','!eq!');
			data+='!I!tasknote='+taskN;
			//alert(taskN);
			//alert(data);
			$.ajax({
					url: url,
					type: 'POST',
					data: data,
					async: false,
					cache: false,
					success: function(html) {
					//alert('ok'+' '+notetype);
					 $('#dialogGeneric').html(html);
					 //$('#dialogGeneric').dialog('close');
						switch(notetype){
							case 'close':
							case 'completion':
							case 'client':
							mailButtons(notetype);
							break;
							default:
							updateButtons();
							break;
						}
						if(ntype=='del'){
							$('#guts').html('');
						}


					}
			        })
		}

		$saveRefreshScript

		function xmailButtons(notetype){
		  $('#dialogGeneric').dialog('option','buttons',{
			'send mail': function() {
					//--jobNoteMailNow('$ntype','$jobid');
					var str=gdf('mailHead','!A!','!eq!');
					var mnote=gdf('mailNote','!A!','!eq!');
					str=str+mnote;
					//alert(str);
					fla=new Array('email');
					var eml=divLines('mailList',fla,'include')
					//alert(eml);
					var url = '../../infbase/ajax/ajaxpostBack.php';
					switch(notetype){
						case 'close':
						case 'completion':
						var mmode='notifyJobCloseEmail';
						break;
						default:
						var mmode='clientEmail';
						break;
					}
					var data='params=!I!p=yes!I!url=mailDocHandler!I!mode='+mmode+'!I!head='+str+'!I!recips='+eml;
					//alert(data);
					$.ajax({
							url: url,type: 'POST', data: data,async: true,	cache: false,
							success: function (html) {
							  $('#dialogGeneric').html(html);

							  $('#dialogGeneric').dialog( 'option', 'buttons', {
							 	'Close & Refresh': function(){
							 		//alert('colse');
									$('#dialogGeneric').dialog('close');
									jobview($jobid);
								 	}
								 });
							 }
					        })

					//replace jobnotemailnow

					$('#dialogGeneric').dialog('option','buttons',{
						'close': function() {
								$(this).dialog('close');
									refreshNoteList();
								}
					})


				},
			'close': function() {
					$('#dialogGeneric').dialog('close');
					refreshNoteList();
				}


			})

		}




		";

				###job completion date checking STB Dec2013
				$jcl="$('#ntype','#dialogJobNote').unbind('change').change(function(){
					var ntype=$(this).val();
					//alert('nt'+ntype);
					if(ntype=='complete') checkCompletionDate();
				});
				$('#notetype','#dialogJobNote').unbind('click').click(function(){
					var ntype=$(this).val();
					//alert('ntype'+ntype);
					if(ntype=='completion') checkCompletionDate();
				});
				function checkCompletionDate(){
					//alert('ccd');
							var data='params=!I!p=yes!I!url=jobDialogs!I!mode=closeNoteCompletionCheck!I!jobid=$jobid';
					 	 	cl('jrq building data:'+data);
							$.ajax({url:ijurl,	async:true,	type: 'POST', 	data: data,
								success: function(jdata){
									$('#jnx').html(jdata);
								}
							});
				}
				";
				$this->customCall.=$jcl;
				$rx.="<div id=jnx></div>";
				###


		#newer standardised approach
		$this->customCallGen=str_replace("dialogJobNote","dialogGeneric",$this->customCall);
		#elog("$this->customCallGen","customint 8582");
		return $rx;
		break;

		case "jobEmail":
		$rx="return email";
		$fm=new iForm();
		$fm->tabind=500;
		$fm->row[]=kda("position");
		$fm->row[]=kda("email");
		$fm->row[]=kda("customerid");
		$fm->row[]=kda("jobid");
		$fm->altFtypes["jobid"]="hidden";
		$fm->altFtypes["customerid"]="hidden";
		$jobid=$this->paramsA["jobid"];
		$custid=$this->paramsA["customerid"];

		$fm->defaultF["jobid"]=$jobid;
		$fm->defaultF["customerid"]=$custid;
		$fm->defaultF["position"]=$this->paramsA['contacttype'];
		$fm->flabel["okb"]=" ";
		$fm->fsize["email"]=60;



		$fm->formGen();
		$tx=$fm->formtext;
		$rx="<form name=contactEmail><table>$tx</table></form>";
		$this->customCall.="$('#dialogJobNote').dialog('option','buttons',
		{'OK': function() {
				var fobj=document.contactEmail;
				var str=getForm(fobj);
				$(this).dialog('close');
				ajx.send('newContactEmail',str);
			},
		'Cancel':function(){ $(this).dialog('close'); } } ); \n";
		$this->customCall.="pullFocus('position') \n";

		return $rx;
		break;

		case "checkClosingDates":
		$rx="checking dates";
		$url="customscript/jrqDateCheckUI.php";
		$jobid=$this->paramsA["jobid"];
		ob_start();
		if(is_readable("$this->clientpath/$url")) include_once "$this->clientpath/$url";
		$cfx = ob_get_contents();
		ob_end_clean();
		$rx=$cfx;
		$this->customCall.="$('#dialogCompletion').dialog('option','title','Edit Due Date')\n";
		$this->customCall.="$('#dialogCompletion').dialog('option','width',600)\n";
		$this->customCall.="$('#dialogCompletion').dialog('option','buttons',
		{'OK': function() {
			//alert('save me');
			//var fobj=document.duedate;
			//var str=getForm(fobj);
			formProcess(document.duedate,'job','update');
			$('#dialogCompletion').dialog('close');
		},
		'Cancel':function(){ $('#dialogCompletion').dialog('close'); } } ); \n";
		return $rx;
		break;

		case "jrqCompletionX":
		$url="customscript/jcompletionUI.php";
		$urlq="customscript/jcompletionUI.php?jobid=$jobid";
		$jobid=$this->paramsA["jobid"];
		$poref=$this->paramsA["poref"];
		$via=$this->paramsA["via"];
		$jstage=singlevalue("jobs","jobstage","jobid",$jobid);
	    $already=kda("client_notified");
		ob_start();
		switch($jstage){
			/**/
			case "Client_notified";
			case "client_notified";
	    	$rx="job $jobid already at $jstage";
			#$this->customCall.="$('#dialogCompletion').dialog('option','title','Already Complete')\n";
			$this->customCall.="$('#dialogCompletion').dialog('option','buttons',{ 'OK': function(){ $(this).dialog('close');}} ); \n";

			if($via=="appt"){
				#intant refresh
				#reset buffer that will have opened dialog
				ob_end_clean();
				ob_start();
				$this->exitModal=true;
				$this->customCall="document.location='ijqcal.php'\n";
				$this->jcallA[]=$this->customCall;
				$this->ibuffCalls();
				unset($rx);
			}
			return $rx;
	    	break;
	    	/**/
			default:
			if(is_readable("$this->clientpath/$url")) include_once "$this->clientpath/$url";
			$cfx = ob_get_contents();
			$rx="hey";
			ob_end_clean();
			$rx=$cfx;
			tfw("jrqmailer.txt","$cfx",true);
			/**/
			#$rx="quote stuff for $tn : $jobid";
			#$this->customCall.="$('#dialogCompletion').dialog('option','buttons',{'Close Job': function() { if(saveJobNote('$ntype')){ jobcompletor('$jobid');$(this).dialog('close');} } ,'Leave Open':function(){ $(this).dialog('close') }} ); \n";
			$this->customCall.="$('#dialogCompletion').dialog('option','title','Save Completion Date')\n";
		/*	$this->customCall.="$('#dialogCompletion').dialog('option','buttons',{
			'Close Job $jobid': function() {
				$(this).dialog('close');
				jobCompletor($jobid);},
				'Leave Open':function(){
					$(this).dialog('close');
					if(g('via')){
						if(g('via').value='appt') reload();
					}
				}
				} ); \n";
 		*/
 			#simpler via flowchart method.
			$this->customCall.="$('#dialogCompletion').dialog('option','buttons',{
			'OK $jobid': function() {
				$(this).dialog('close');
				jobCompletor($jobid);
				reload();
				},
				} ); \n";


			return $rx;
			break;
		}
		break;



		case "quoteMailer":
		#echo "alert('$tn $id')\n";
		$jobid=$this->paramsA["jobid"];
		ob_start();
		if(is_readable("$this->clientpath/customscript/jobQuoteMailerUi.php")) include_once "$this->clientpath/customscript/jobQuoteMailerUi.php";
		$cfx = ob_get_contents();
		ob_end_clean();
		$rx=$cfx;
		tfw("qmailer.txt","$cfx",true);
		#$rx="quote stuff for $tn : $jobid";
		return $rx;
		break;

		case "rfqMailer":
		#echo "alert('$tn $id')\n";
		$jobid=$this->paramsA["jobid"];
		$rfq=$this->paramsA["id"];
		ob_start();
		if(is_readable("$this->clientpath/customscript/jobRfqMailerUi.php")) include_once "$this->clientpath/customscript/jobRfqMailerUi.php";
		$cfx = ob_get_contents();
		ob_end_clean();
		$rx=$cfx;
		tfw("pmailer.txt","$cfx",true);
		#$rx="quote stuff for $tn : $jobid";
		return $rx;
		break;

		case "jrqMailer":
		#echo "alert('$tn $id')\n";
		$jobid=$this->paramsA["jobid"];
		$poref=$this->paramsA["id"];
		ob_start();
		if(is_readable("$this->clientpath/customscript/jobJrqMailerUi.php")) include_once "$this->clientpath/customscript/jobJrqMailerUi.php";
		$cfx = ob_get_contents();
		ob_end_clean();
		$rx=$cfx;
		tfw("pmailer.txt","$cfx",true);
		#$rx="quote stuff for $tn : $jobid";
		return $rx;
		break;


		case "mailResult":
		#echo "alert('mrr $tn $id')\n";
		$jobid=$this->paramsA["jobid"];
		$ntype=$this->paramsA["ntype"];
		$via=$this->paramsA["via"];
		$newmail=$this->paramsA["newmail"];
		$mx=$this->paramsA["mx"];

		#check if nextstage exists
		$nextJobStage=$this->paramsA["newjobstage"];
		if($nextJobStage!=""){
			$okk="$('#dialogJobNoteResult').dialog( 'option', 'buttons', { 'OK': function() {
				if(g('jobstage')) g('jobstage').value='$nextJobStage';
				$(this).dialog('close'); }
			} );";
			$this->modalCalls[]=$okk;
		}



		$rx="Response from mail server: $mx";
		#sbe via
		$rx.="<br><input type=hidden id=via value=$via>";
		if($newmail!="") {
			$customerid=$this->paramsA["customerid"];
			$ocx="onclick=newmailInstant('$customerid','$newmail');";
		$rx.="<br><br>New mail $newmail detected<br>Set as new default ? <input type=checkbox $ocx>";
		}

		switch($ntype){
			case "completion":
			$rx.="<br><input type=hidden id=refreshJobid value=$jobid>";
			$rx.="<br><input type=hidden id=ntype value=$ntype>";
			break;
		}
		return $rx;
		break;

		case "jobNoteMailer":
		#echo "alert('$tn $id')\n";
		ob_start();
		$via=$this->paramsA["via"];
		if(is_readable("$this->clientpath/customscript/jobNoteMailerUi.php")) include_once "$this->clientpath/customscript/jobNoteMailerUi.php";
		$cfx = ob_get_contents();
		ob_end_clean();
		$rx=$cfx;
		return $rx;
		break;


		case "phonecall":
		$x="call form";
		$this->usedCustom=true;
		if(is_readable("$this->clientpath/customscript/phoneInterface.php")) include_once "$this->clientpath/customscript/phoneInterface.php";
		$pci=new phoneInterface();
		$pci->onmodal=true;
		$x="reads ok";
		if($id>0) $pci->callid=$id;
		$pci->phoneSummaryPage($sfn,'edit',$customerid);
		#$headx=vClean($pci->fx);
		$headx=$pci->fx;
		$pcx=stripslashes($headx);
		$jcx=$GLOBALS["jcall"];
		$threadid=singlevalue("phonecall","threadid","phonecallid",$id);
		if($threadid=="") $threadid=$id;
		$threadHist=$this->loadThread($threadid);

		$valign="style=vertical-align:top;";
		$tst="style='border: solid #000 1px;'";

		$rx.="<table><tr><td $valign>";
		$rx.="<div id=main>$headx</div>";
		$rx.="<div id=quickAdd style='display:none;'>qa</div>";
		$rx.="</td><td $valign>";
$tabx="
<div id=\"uTabs\">
	<ul>
		<li><a href=\"#tabs-1\">Thread</a></li>
		<li><a href=\"#tabs-2\">Job List</a></li>
	</ul>
	<div id=\"tabs-1\">
		<p>$threadHist</p>
	</div>
	<div id=\"tabs-2\">
		<p>Recurring Info</p>
	</div>
	<!--div id=\"tabs-3\">
	</div-->
</div>";
		$rx.=$tabx;
		$rx.="</td></tr></table>";


		return $rx;

		break;

		case "quotenote":
		$qx="quote follow detail";
		$fm=new iForm();
		$fm->tabind=500;
		#$fm->row[]=kda("position");
		$fm->row[]=kda("contactid,newcontact");
		$fm->row[]=kda("customerid");
		#$fm->row[]=kda("contactid");
		$fm->row[]=kda("jobid");
		$fm->row[]=kda("jobnoteid");
		$fm->row[]=kda("notes");
		$fm->row[]=kda("date");
		$fm->row[]=kda("ntype");
		$fm->row[]=kda("followupdate");
		$fm->row[]=kda("followupby");
		$fm->row[]=kda("userid");

		$fm->verticals=kda("newcontact,contactname");
		$fm->dataColspans["contactname"]=2;
		$fm->labelColspans["contactname"]=2;


		$fm->altFtypes["jobid"]="readonlytext";
		$fm->altFtypes["ntype"]="readonlytext";
		$fm->altFtypes["date"]="hidden";
		$fm->altFtypes["jobnoteid"]="hidden";
		$fm->altFtypes["customerid"]="hidden";
		$fm->altFtypes["contactid"]="contactSuggest";
		$fm->altFtypes["notes"]="textarea";
		$fm->altFtypes["followupdate"]="date_ClickCal";


		$apx="onclick=modalQuickAdd('contact','quotenote','quickAdd')";
		$fm->jcallA["newcontact"]=$apx;
		$fm->altFtypes["newcontact"]="button";


		$fm->fsize["contactname"]=60;
		$jobnoteid=$this->paramsA["jobnoteid"];
		$jobid=$this->paramsA["jobid"];
		#get defaults from
		#load most recent jobnote
		$jnq="select * from jobnote where jobid=$jobid
		and contactid>0
		order by jobnoteid desc limit 1 ";
		#$fm->defaultF=iqatr("jobnote","jobnoteid",$jobnoteid);
		$fm->defaultF=iqasra($jnq,null,"jobnote");

		$jobid=$this->paramsA["jobid"];
		$custid=$this->paramsA["customerid"];


		$fm->defaultF["date"]=date("Y-m-d");
		$fm->defaultF["jobid"]=$jobid;
		#$fm->defaultF["jobnoteid"]=$jobnoteid;
		$fm->defaultF["customerid"]=$custid;
		$fm->defaultF["userid"]=$_SESSION["userid"];
		$fm->defaultF["ntype"]="quote followup";
		$fm->flabel["okb"]=" ";

		#force new note by resetting id
		unset($fm->defaultF["jobnoteid"]);
		unset($fm->defaultF["notes"]);
		#$fm->defaultF["notes"]="id $jnq $this->paramsX";
		#$fm->defaultF["contactname"]=5613;
		#$fm->defaultF["contactid"]=5613;
		#$fm->defaultF["followupby"]="fred";
		$fm->defaultF["followupdate"]='2010-01-10';
		$fm->fsize["followupdate"]=12;
		$fm->formGen();
		$fm->onmodal=false;
		$tx=$fm->formtext;
		$noteHist="job notes here";

		$tf=kda("date,notes");
		$this->altTf["jobnotes"]=$tf;
		$noteHist=$this->loadJobNotes($jobid);
		$valign="style=vertical-align:top;";

		$notex="<form name=jobnote><table>$tx";
		$notex.="<input type=hidden name=qnote id=qnote value=1>";
		$notex.="</table></form>";

		$rx.="<table style=height:300px;><tr><td $valign>";
		$rx.="<div id=main>$notex</div>";
		$rx.="<div id=quickAdd style='display:none;'>qa</div>";
		$rx.="</td><td $valign>";
		$tabx="
		<div id=\"qTabs\">
			<ul>
				<li><a href=\"#qtabs-1\">Notes</a></li>
			</ul>
			<div id=\"qtabs-1\">
				<p><span style=font-size:80%;>$noteHist</span></p>
			</div>
		</div>";
		$rx.=$tabx;
		$rx.="</td></tr></table>";
		$this->initLookupCall=$fm->initLookupCall;
		return $rx;
		break;

		case "task":
		$blab="Save Task";
		$this->customCall="$('#dialogGeneric').dialog('option','buttons',{
			'$blab': function() {
					saveTaskNow();
				}
			});
			function saveTaskNow(){
				var gx=gdf('mfn','!A!','!eq!');
				var url = '../../infbase/ajax/ajaxpostBack.php';
				var jobid=$('#jobid').val();
				//alert('save jid'+jobid);
				var alv=$('#allocatedto').val();
				//alert('alv: '+alv);
				var data='params=!I!url=saveMaster!I!mode=save!I!tablen=task!I!'+gx;
				//alert(data);
				//return;
				$.ajax({
				url: url,
				type: 'POST',
				data: data,
				cache: false,
				success: function (html) {
					$('#dialogGeneric').html('Task Saved OK');
					updateButtons();
				}
				});

		        }
			$saveRefreshScript
			";
		#elog("t $this->customCall","cif 9044");

		$this->customCallGen=$this->customCall;
		#needs to return blank
		$rx="";
		return $rx;
		break;
	}
			#echo "alert('cbb tn $tn $notex')\n";


}


function customModal_x($tn=null,$id=null){
	#echo "<br><br>tt $tn ii $id";
	#return "x";
	elog("cm $tn","cif 8504");
	unset($this->customCall);
	#echo "alert('tn $tn')\n";

	#common functions
		$jobid=$this->paramsA["jobid"];
		$saveRefreshScript="
		//alert('upx');
		function updateButtons(){
			$('#dialogGeneric').dialog('option','buttons',{
		 			'close & refresh notes': function() {
	 					$(this).dialog('close');
	 					refreshNoteList();
						$this->otherRefreshCalls
	 				}
			});

		 }

		 function refreshNoteList(){
  			var url = '../../infbase/ajax/ajaxpostBack.php';
			data='params=!I!p=yes!I!url=jobNoteSaver!I!mode=refresh!I!jobid=$jobid';
			$.ajax({
					url: url,type: 'POST',
					data: data, async: false,
					cache: false,
					success: function (html) {
					 $('#jobnotes').html(html);
					}
			 })
		}
		";




	switch($tn){
		case "jobnote":
		$rx="new job notes ";
		$this->ui=true;
		$jobid=$this->paramsA["jobid"];
		$ntype=$this->paramsA["ntype"];


		$via=$this->paramsA["via"];
		#echo "alert('cmm $tn $ntype $id')\n";
		$this->paramsA["jobnoteid"]=$this->paramsA["jobnoteid"]==""?$id:$this->paramsA["jobnoteid"];
		$tp=$this->paramsA["jobnoteid"];
		#echo "<br>tp $tp";
		$rx=$this->quickJobNote($jobid,$ntype,$id,$via);

		$title=$this->dialogTitle;
		#$this->jcallA[]="$('#dialogJobNote').dialog('option','title','Edited $ntype');";
		$this->customCall.="$('#dialogJobNote').dialog('option','title','$title');\n";
		$standardSave="saveJobNote();	$(this).dialog('close');";

		$nextFnx="updateButtons()";
		switch($ntype){
			case "hold":
			$blab="Hold Job now";
			break;
			case "del":
			$blab="Delete Job now";
			break;
			case "nobill":
			$blab="Flag Un-Chargeable";

			break;
			case "can":
			$blab="cCancel Job Now";
			break;
			case "extend":
			$blab="Extending Job";
			$nextFnx="mailButtons()";
			break;
			case "complete":
			$blab="Save Completion Note";
			break;

			case "close":
			$closingNote=true;
			$blab="Save Closing Note";
			break;

			default:
			$blab="OK";
			if($this->editmode=="edit") $blab="Update";
			if($this->editmode=="new") $blab="Save new note";
			break;
		}

		#$this->customCall.="$('#dialogJobNote').dialog('option','buttons',{'$blab': function() { if(saveJobNote('$ntype')){ $(this).dialog('close');} } } ); \n";
		//$this->customCall="alert('saver')\n;";
		$this->customCall.="$('#dialogGeneric').dialog('option','buttons',{
			'$blab': function() {
					saveNoteNow();
				}
			}
		);
		function saveNoteNow(){
			if($('#reason').val()=='Choose an option'){
				alert('Please select a reason');
				return false;
			}
			var str=gdf('jobnote','!A!','!eq!') ;
			//alert('saving'+str);
			//var notetype=$('#notetype').val();
			var notetype=$('input[name=notetype]:checked', '#jobnoteF').val() ;

			//alert('note type '+notetype);
			var ntype=$('#ntype').val();
  			var url = '../../infbase/ajax/ajaxpostBack.php';
			data='params=$this->otherParams!I!p=yes!I!url=jobNoteSaver!I!mode=closure!I!str='+str;
//alert(data);
			$.ajax({
					url: url,
					type: 'POST',
					data: data,
					async: false,
					cache: false,
					success: function(html) {
					//alert('ok'+' '+notetype);
					 $('#dialogGeneric').html(html);
					 //$('#dialogGeneric').dialog('close');
						switch(notetype){
							case 'close':
							case 'completion':
							case 'client':
							mailButtons(notetype);
							break;
							default:
							updateButtons();
							break;
						}
						if(ntype=='del'){
							$('#guts').html('');
						}


					}
			        })
		}

		$saveRefreshScript

		function xmailButtons(notetype){
		  $('#dialogGeneric').dialog('option','buttons',{
			'send mail': function() {
					//--jobNoteMailNow('$ntype','$jobid');
					var str=gdf('mailHead','!A!','!eq!');
					var mnote=gdf('mailNote','!A!','!eq!');
					str=str+mnote;
					//alert(str);
					fla=new Array('email');
					var eml=divLines('mailList',fla,'include')
					//alert(eml);
					var url = '../../infbase/ajax/ajaxpostBack.php';
					switch(notetype){
						case 'close':
						case 'completion':
						var mmode='notifyJobCloseEmail';
						break;
						default:
						var mmode='clientEmail';
						break;
					}
					var data='params=!I!p=yes!I!url=mailDocHandler!I!mode='+mmode+'!I!head='+str+'!I!recips='+eml;
					//alert(data);
					$.ajax({
							url: url,type: 'POST', data: data,async: true,	cache: false,
							success: function (html) {
							  $('#dialogGeneric').html(html);

							  $('#dialogGeneric').dialog( 'option', 'buttons', {
							 	'Close & Refresh': function(){
							 		//alert('colse');
									$('#dialogGeneric').dialog('close');
									jobview($jobid);
								 	}
								 });
							 }
					        })

					//replace jobnotemailnow

					$('#dialogGeneric').dialog('option','buttons',{
						'close': function() {
								$(this).dialog('close');
									refreshNoteList();
								}
					})


				},
			'close': function() {
					$('#dialogGeneric').dialog('close');
					refreshNoteList();
				}


			})

		}




		";

				###job completion date checking STB Dec2013
				$jcl="$('#ntype','#dialogJobNote').unbind('change').change(function(){
					var ntype=$(this).val();
					//alert('nt'+ntype);
					if(ntype=='complete') checkCompletionDate();
				});
				$('#notetype','#dialogJobNote').unbind('click').click(function(){
					var ntype=$(this).val();
					//alert('ntype'+ntype);
					if(ntype=='completion') checkCompletionDate();
				});
				function checkCompletionDate(){
					//alert('ccd');
							var data='params=!I!p=yes!I!url=jobDialogs!I!mode=closeNoteCompletionCheck!I!jobid=$jobid';
					 	 	cl('jrq building data:'+data);
							$.ajax({url:ijurl,	async:true,	type: 'POST', 	data: data,
								success: function(jdata){
									$('#jnx').html(jdata);
								}
							});
				}
				";
				$this->customCall.=$jcl;
				$rx.="<div id=jnx></div>";
				###


		#newer standardised approach
		$this->customCallGen=str_replace("dialogJobNote","dialogGeneric",$this->customCall);
		#elog("$this->customCallGen","customint 8582");
		return $rx;
		break;

		case "jobEmail":
		$rx="return email";
		$fm=new iForm();
		$fm->tabind=500;
		$fm->row[]=kda("position");
		$fm->row[]=kda("email");
		$fm->row[]=kda("customerid");
		$fm->row[]=kda("jobid");
		$fm->altFtypes["jobid"]="hidden";
		$fm->altFtypes["customerid"]="hidden";
		$jobid=$this->paramsA["jobid"];
		$custid=$this->paramsA["customerid"];

		$fm->defaultF["jobid"]=$jobid;
		$fm->defaultF["customerid"]=$custid;
		$fm->defaultF["position"]=$this->paramsA['contacttype'];
		$fm->flabel["okb"]=" ";
		$fm->fsize["email"]=60;



		$fm->formGen();
		$tx=$fm->formtext;
		$rx="<form name=contactEmail><table>$tx</table></form>";
		$this->customCall.="$('#dialogJobNote').dialog('option','buttons',
		{'OK': function() {
				var fobj=document.contactEmail;
				var str=getForm(fobj);
				$(this).dialog('close');
				ajx.send('newContactEmail',str);
			},
		'Cancel':function(){ $(this).dialog('close'); } } ); \n";
		$this->customCall.="pullFocus('position') \n";

		return $rx;
		break;

		case "checkClosingDates":
		$rx="checking dates";
		$url="customscript/jrqDateCheckUI.php";
		$jobid=$this->paramsA["jobid"];
		ob_start();
		if(is_readable("$this->clientpath/$url")) include_once "$this->clientpath/$url";
		$cfx = ob_get_contents();
		ob_end_clean();
		$rx=$cfx;
		$this->customCall.="$('#dialogCompletion').dialog('option','title','Edit Due Date')\n";
		$this->customCall.="$('#dialogCompletion').dialog('option','width',600)\n";
		$this->customCall.="$('#dialogCompletion').dialog('option','buttons',
		{'OK': function() {
			//alert('save me');
			//var fobj=document.duedate;
			//var str=getForm(fobj);
			formProcess(document.duedate,'job','update');
			$('#dialogCompletion').dialog('close');
		},
		'Cancel':function(){ $('#dialogCompletion').dialog('close'); } } ); \n";
		return $rx;
		break;

		case "jrqCompletionX":
		$url="customscript/jcompletionUI.php";
		$urlq="customscript/jcompletionUI.php?jobid=$jobid";
		$jobid=$this->paramsA["jobid"];
		$poref=$this->paramsA["poref"];
		$via=$this->paramsA["via"];
		$jstage=singlevalue("jobs","jobstage","jobid",$jobid);
	    $already=kda("client_notified");
		ob_start();
		switch($jstage){
			/**/
			case "Client_notified";
			case "client_notified";
	    	$rx="job $jobid already at $jstage";
			#$this->customCall.="$('#dialogCompletion').dialog('option','title','Already Complete')\n";
			$this->customCall.="$('#dialogCompletion').dialog('option','buttons',{ 'OK': function(){ $(this).dialog('close');}} ); \n";

			if($via=="appt"){
				#intant refresh
				#reset buffer that will have opened dialog
				ob_end_clean();
				ob_start();
				$this->exitModal=true;
				$this->customCall="document.location='ijqcal.php'\n";
				$this->jcallA[]=$this->customCall;
				$this->ibuffCalls();
				unset($rx);
			}
			return $rx;
	    	break;
	    	/**/
			default:
			if(is_readable("$this->clientpath/$url")) include_once "$this->clientpath/$url";
			$cfx = ob_get_contents();
			$rx="hey";
			ob_end_clean();
			$rx=$cfx;
			tfw("jrqmailer.txt","$cfx",true);
			/**/
			#$rx="quote stuff for $tn : $jobid";
			#$this->customCall.="$('#dialogCompletion').dialog('option','buttons',{'Close Job': function() { if(saveJobNote('$ntype')){ jobcompletor('$jobid');$(this).dialog('close');} } ,'Leave Open':function(){ $(this).dialog('close') }} ); \n";
			$this->customCall.="$('#dialogCompletion').dialog('option','title','Save Completion Date')\n";
		/*	$this->customCall.="$('#dialogCompletion').dialog('option','buttons',{
			'Close Job $jobid': function() {
				$(this).dialog('close');
				jobCompletor($jobid);},
				'Leave Open':function(){
					$(this).dialog('close');
					if(g('via')){
						if(g('via').value='appt') reload();
					}
				}
				} ); \n";
 		*/
 			#simpler via flowchart method.
			$this->customCall.="$('#dialogCompletion').dialog('option','buttons',{
			'OK $jobid': function() {
				$(this).dialog('close');
				jobCompletor($jobid);
				reload();
				},
				} ); \n";


			return $rx;
			break;
		}
		break;



		case "quoteMailer":
		#echo "alert('$tn $id')\n";
		$jobid=$this->paramsA["jobid"];
		ob_start();
		if(is_readable("$this->clientpath/customscript/jobQuoteMailerUi.php")) include_once "$this->clientpath/customscript/jobQuoteMailerUi.php";
		$cfx = ob_get_contents();
		ob_end_clean();
		$rx=$cfx;
		tfw("qmailer.txt","$cfx",true);
		#$rx="quote stuff for $tn : $jobid";
		return $rx;
		break;

		case "rfqMailer":
		#echo "alert('$tn $id')\n";
		$jobid=$this->paramsA["jobid"];
		$rfq=$this->paramsA["id"];
		ob_start();
		if(is_readable("$this->clientpath/customscript/jobRfqMailerUi.php")) include_once "$this->clientpath/customscript/jobRfqMailerUi.php";
		$cfx = ob_get_contents();
		ob_end_clean();
		$rx=$cfx;
		tfw("pmailer.txt","$cfx",true);
		#$rx="quote stuff for $tn : $jobid";
		return $rx;
		break;

		case "jrqMailer":
		#echo "alert('$tn $id')\n";
		$jobid=$this->paramsA["jobid"];
		$poref=$this->paramsA["id"];
		ob_start();
		if(is_readable("$this->clientpath/customscript/jobJrqMailerUi.php")) include_once "$this->clientpath/customscript/jobJrqMailerUi.php";
		$cfx = ob_get_contents();
		ob_end_clean();
		$rx=$cfx;
		tfw("pmailer.txt","$cfx",true);
		#$rx="quote stuff for $tn : $jobid";
		return $rx;
		break;


		case "mailResult":
		#echo "alert('mrr $tn $id')\n";
		$jobid=$this->paramsA["jobid"];
		$ntype=$this->paramsA["ntype"];
		$via=$this->paramsA["via"];
		$newmail=$this->paramsA["newmail"];
		$mx=$this->paramsA["mx"];

		#check if nextstage exists
		$nextJobStage=$this->paramsA["newjobstage"];
		if($nextJobStage!=""){
			$okk="$('#dialogJobNoteResult').dialog( 'option', 'buttons', { 'OK': function() {
				if(g('jobstage')) g('jobstage').value='$nextJobStage';
				$(this).dialog('close'); }
			} );";
			$this->modalCalls[]=$okk;
		}



		$rx="Response from mail server: $mx";
		#sbe via
		$rx.="<br><input type=hidden id=via value=$via>";
		if($newmail!="") {
			$customerid=$this->paramsA["customerid"];
			$ocx="onclick=newmailInstant('$customerid','$newmail');";
		$rx.="<br><br>New mail $newmail detected<br>Set as new default ? <input type=checkbox $ocx>";
		}

		switch($ntype){
			case "completion":
			$rx.="<br><input type=hidden id=refreshJobid value=$jobid>";
			$rx.="<br><input type=hidden id=ntype value=$ntype>";
			break;
		}
		return $rx;
		break;

		case "jobNoteMailer":
		#echo "alert('$tn $id')\n";
		ob_start();
		$via=$this->paramsA["via"];
		if(is_readable("$this->clientpath/customscript/jobNoteMailerUi.php")) include_once "$this->clientpath/customscript/jobNoteMailerUi.php";
		$cfx = ob_get_contents();
		ob_end_clean();
		$rx=$cfx;
		return $rx;
		break;


		case "phonecall":
		$x="call form";
		$this->usedCustom=true;
		if(is_readable("$this->clientpath/customscript/phoneInterface.php")) include_once "$this->clientpath/customscript/phoneInterface.php";
		$pci=new phoneInterface();
		$pci->onmodal=true;
		$x="reads ok";
		if($id>0) $pci->callid=$id;
		$pci->phoneSummaryPage($sfn,'edit',$customerid);
		#$headx=vClean($pci->fx);
		$headx=$pci->fx;
		$pcx=stripslashes($headx);
		$jcx=$GLOBALS["jcall"];
		$threadid=singlevalue("phonecall","threadid","phonecallid",$id);
		if($threadid=="") $threadid=$id;
		$threadHist=$this->loadThread($threadid);

		$valign="style=vertical-align:top;";
		$tst="style='border: solid #000 1px;'";

		$rx.="<table><tr><td $valign>";
		$rx.="<div id=main>$headx</div>";
		$rx.="<div id=quickAdd style='display:none;'>qa</div>";
		$rx.="</td><td $valign>";
$tabx="
<div id=\"uTabs\">
	<ul>
		<li><a href=\"#tabs-1\">Thread</a></li>
		<li><a href=\"#tabs-2\">Job List</a></li>
	</ul>
	<div id=\"tabs-1\">
		<p>$threadHist</p>
	</div>
	<div id=\"tabs-2\">
		<p>Recurring Info</p>
	</div>
	<!--div id=\"tabs-3\">
	</div-->
</div>";
		$rx.=$tabx;
		$rx.="</td></tr></table>";


		return $rx;

		break;

		case "quotenote":
		$qx="quote follow detail";
		$fm=new iForm();
		$fm->tabind=500;
		#$fm->row[]=kda("position");
		$fm->row[]=kda("contactid,newcontact");
		$fm->row[]=kda("customerid");
		#$fm->row[]=kda("contactid");
		$fm->row[]=kda("jobid");
		$fm->row[]=kda("jobnoteid");
		$fm->row[]=kda("notes");
		$fm->row[]=kda("date");
		$fm->row[]=kda("ntype");
		$fm->row[]=kda("followupdate");
		$fm->row[]=kda("followupby");
		$fm->row[]=kda("userid");

		$fm->verticals=kda("newcontact,contactname");
		$fm->dataColspans["contactname"]=2;
		$fm->labelColspans["contactname"]=2;


		$fm->altFtypes["jobid"]="readonlytext";
		$fm->altFtypes["ntype"]="readonlytext";
		$fm->altFtypes["date"]="hidden";
		$fm->altFtypes["jobnoteid"]="hidden";
		$fm->altFtypes["customerid"]="hidden";
		$fm->altFtypes["contactid"]="contactSuggest";
		$fm->altFtypes["notes"]="textarea";
		$fm->altFtypes["followupdate"]="date_ClickCal";


		$apx="onclick=modalQuickAdd('contact','quotenote','quickAdd')";
		$fm->jcallA["newcontact"]=$apx;
		$fm->altFtypes["newcontact"]="button";


		$fm->fsize["contactname"]=60;
		$jobnoteid=$this->paramsA["jobnoteid"];
		$jobid=$this->paramsA["jobid"];
		#get defaults from
		#load most recent jobnote
		$jnq="select * from jobnote where jobid=$jobid
		and contactid>0
		order by jobnoteid desc limit 1 ";
		#$fm->defaultF=iqatr("jobnote","jobnoteid",$jobnoteid);
		$fm->defaultF=iqasra($jnq,null,"jobnote");

		$jobid=$this->paramsA["jobid"];
		$custid=$this->paramsA["customerid"];


		$fm->defaultF["date"]=date("Y-m-d");
		$fm->defaultF["jobid"]=$jobid;
		#$fm->defaultF["jobnoteid"]=$jobnoteid;
		$fm->defaultF["customerid"]=$custid;
		$fm->defaultF["userid"]=$_SESSION["userid"];
		$fm->defaultF["ntype"]="quote followup";
		$fm->flabel["okb"]=" ";

		#force new note by resetting id
		unset($fm->defaultF["jobnoteid"]);
		unset($fm->defaultF["notes"]);
		#$fm->defaultF["notes"]="id $jnq $this->paramsX";
		#$fm->defaultF["contactname"]=5613;
		#$fm->defaultF["contactid"]=5613;
		#$fm->defaultF["followupby"]="fred";
		$fm->defaultF["followupdate"]='2010-01-10';
		$fm->fsize["followupdate"]=12;
		$fm->formGen();
		$fm->onmodal=false;
		$tx=$fm->formtext;
		$noteHist="job notes here";

		$tf=kda("date,notes");
		$this->altTf["jobnotes"]=$tf;
		$noteHist=$this->loadJobNotes($jobid);
		$valign="style=vertical-align:top;";

		$notex="<form name=jobnote><table>$tx";
		$notex.="<input type=hidden name=qnote id=qnote value=1>";
		$notex.="</table></form>";

		$rx.="<table style=height:300px;><tr><td $valign>";
		$rx.="<div id=main>$notex</div>";
		$rx.="<div id=quickAdd style='display:none;'>qa</div>";
		$rx.="</td><td $valign>";
		$tabx="
		<div id=\"qTabs\">
			<ul>
				<li><a href=\"#qtabs-1\">Notes</a></li>
			</ul>
			<div id=\"qtabs-1\">
				<p><span style=font-size:80%;>$noteHist</span></p>
			</div>
		</div>";
		$rx.=$tabx;
		$rx.="</td></tr></table>";
		$this->initLookupCall=$fm->initLookupCall;
		return $rx;
		break;

		case "task":
		$blab="Save Task";
		$this->customCall="$('#dialogGeneric').dialog('option','buttons',{
			'$blab': function() {
					saveTaskNow();
				}
			});
			function saveTaskNow(){
				var gx=gdf('mfn','!A!','!eq!');
				var url = '../../infbase/ajax/ajaxpostBack.php';
				var jobid=$('#jobid').val();
				//alert('save jid'+jobid);
				var alv=$('#allocatedto').val();
				//alert('alv: '+alv);
				var data='params=!I!url=saveMaster!I!mode=save!I!tablen=task!I!'+gx;
				//alert(data);
				//return;
				$.ajax({
				url: url,
				type: 'POST',
				data: data,
				cache: false,
				success: function (html) {
					$('#dialogGeneric').html('Task Saved OK');
					updateButtons();
				}
				});

		        }
			$saveRefreshScript
			";
		#elog("t $this->customCall","cif 9044");

		$this->customCallGen=$this->customCall;
		#needs to return blank
		$rx="";
		return $rx;
		break;
	}
			#echo "alert('cbb tn $tn $notex')\n";


}

function customTab($tname,$id){
	$tx="$tname $id";
		$sqlx="select * from addresslabel where customerid=$id
		order by sitesuburb";
		$da=iqa($sqlx,null,null,"addresslabel");
		$ist=new divTable();
		$tf=kda("labelid,address,ldesc,primarymail,primaryship");
		$tf["ldesc"]="Description";
		$tf["primarymail"]="Default Mail?";
		$tf["primaryship"]="Default Shipping?";


		#new modal version of adding

		$ist->highlighter=true;
		#$ist->rowID=kda("labelid,customerid");
		#$ist->highlightFname=("addresslabeledit");

		$ist->toolA[0]["ttype"]="button";
		$ist->toolA[0]["label"]="Add New";
		#$ist->toolA[0]["jname"]="newModalAddress($id)";


		#UI method
		$ist->rowID=kda("labelid");
		$ist->highlightFname="editAddress";
		$ist->highlightFname="jqUiModalExisting";
		$ist->excludeColData=true;

		$ist->rowIDstaticPre=kda("divid=dialogAddressLabel!I!tn=addresslabel!I!title=Address!I!");


		#$ist->toolA[0]["jname"]="jqUiModal('tn=addresslabel!I!title=New','div',startAddress)";
		$ist->toolA[0]["jname"]="jqUiModalExisting('divid=dialogAddressLabel!I!tn=addresslabel!I!title=Address')";

/*		$ist->input_fields["customerid"]="hidden";
		$ist->input_fields["labelid"]="hidden";
		$ist->input_fields["primarymail"]="tickfield";
		$ist->input_fields["primaryship"]="tickfield";
*/


 		$szd=sizeof($tf);

		$ist->tableIDcontainer="fred style='$this->standardScrollDimensions'";
		$ist->tablen="addresslabel";

		#checksecurity
		if(killok("addresslabel"))	$ist->offerDelete=true;
		#		$ist->noHighlight=kda("delete");

		$_SESSION["refresh"]["addresslabel"]="$"."this->displayCustAddresses($id);";


		$nx=$ist->inf_sortable($tf,$da,"Addresses ",null,null,true);

	return $nx;
}

function xcustomTab($table,$primid){
		switch($table){
		case "addresslabel":
		$custid=$primid;
		$sqlx="select * from addresslabel where customerid=$custid
		and sitesuburb!=''
		order by sitesuburb";
		$da=iqa($sqlx,null,null,"addresslabel");
 		//$szb=sizeof($da);
		$ist=new divTable();
				//return $da;
		listablefields("addresslabel");
		#if(!isset($GLOBALS["lfldid"])) $lfn=key2val(kda("address,primarymail,primaryship"));

		foreach($GLOBALS["lfldid"] as $fieldn){
			#echo "alert('got $fieldn')\n";
		 $pos=$GLOBALS["lfldid_pos"][$fieldn];
		 $lfn[$fieldn]=$pos;
			$lcc.="$fieldn $pos";
		}
		asort($lfn);
		reset($lfn);
		foreach($lfn as $fn=>$pos){
			$tf[$fn]=$GLOBALS["lflddesc"][$fn];
			$lcc.="sorted $pos $fn ".$tf[$fn];
		}
		//$nx=$ist->inf_sortable($tf,$da,"Search List",50,null,true);
 		//$sza=sizeof($da);
 		//tfw("lcc.txt","sz $szb $sza $sqlx $lcc",true);
		#$ocx="onclick=newMaster('addresslabel','na','newform')";

		#new modal version of adding

		$ist->highlighter=true;
		#$ist->rowID=kda("labelid,customerid");
		#$ist->highlightFname=("addresslabeledit");

		$ist->highlightFname="genericTableViewR";
		#$ist->highlightFname="subTabView";
		$ist->rowID=kda("labelid");
		$ist->rowIDstatic=kda("addresslabel");

		#(tablen,subtab,id)
		#genericTableView(tablen,id,postcall

		$ist->toolA[0]["ttype"]="button";
		$ist->toolA[0]["jname"]="newModalAddress($custid)";
		$ist->toolA[0]["label"]="Add New";

		$ist->input_fields["customerid"]="hidden";
		$ist->input_fields["labelid"]="hidden";



 		$szd=sizeof($tf);
 		#if($szd==0) $tf=kda("customerid,labelid,address,primarymail,primaryship,sitecontact,sitefm");
 		if($szd==0) $tf=kda("customerid,labelid,siteline1,siteline2,sitesuburb,sitecontact,sitefm");

 		#del checkboxes upset highlight mode
 		#$tf["del"]="delete";
 		#$ist->input_fields["del"]="checkboxAJ";
		#$ist->entrymode=true;

 		#foreach($tf as $tk=>$tdd) echo "alert('
 		#$nx=$ist->infEditableTable($tf,$da,"labelid","addresslabel","Sites",null,null,true);

		$ist->tableIDcontainer="fred style='$this->standardScrollDimensions'";

		$ist->offerxl=true;
		$ist->filt_fields=kda("sitefm");
		$_SESSION["filterJRecall"]="tabselect('addressview','$primid')";
		$nx=$ist->inf_sortable($tf,$da,"Addresses ",null,null,true);
 		#tfw("nxx.txt",$nx,true);
 		return $nx;
		break;
		}


}


function saveAttach($jobid,$docn,$dtype,$booly){
	#now saved via modal
	echo "alert('save from modal instead')\n";
	exit;
	$attx=singlevalue("jobs","attachments","jobid",$jobid);
	$ata=unserialize($attx);
	if($booly){
		$actx="added";
		$ata[$dtype][$docn]='on';
	}else{
		$actx="removed";
		unset($ata[$dtype][$docn]);
	}
	$atx=serialize($ata);
	$uj["attachments"]=$atx;
	performarrayupdate("jobs",$uj,"jobid",$jobid);

	$doca["safety"]="hazards";
	$doca["compliance"]="insuranceDec";
	$doca["work method"]="procedures";
	$doca["claim"]="contractorClaim";
	$doca["quote"]="quote";
	$doca["quote procedures"]="standardprocedure";

	$fda=array_flip($doca);
	$lab=$fda[$docn];
	$dta["dcfmwo07J"]="JRQ";
	$dta["dcfmrfq07J"]="RFQ";
	$dl=$dta[$dtype];
	echo "alert('$lab document $actx on $dl')\n";
}

function saveSafetyAttach($str,$jobid){
	$da=formCruncherMultiLine($str,"documentid");
	$sname=aFV($da,"sname");
	#foreach($sname as $fn){
	#	echo "alert('include $fn')\n";
	#}
	$srz=serialize($sname);
	$uj["procedureDocs"]=$srz;
	performarrayupdate("jobs",$uj,"jobid",$jobid);
}

function saveOptionalJobDoc($str,$jobid,$supplierid,$ftype,$doctype,$formName){
	#retrieve existing array;
	#echo "alert('save $str sid:$supplierid fn:$formName')\n";
	#exit;
	$docz=singlevalue("jobs","attachments","jobid",$jobid);
	$docA=unserialize($docz);

	$da=formCruncherMultiLine($str,$formName);
	$sA=aFV($da,$formName);
	foreach($sA as $sav){
		$dnA=formCruncher($sav);
		$dname=$dnA["dname"];
		$fname=$dnA["fname"];
		#echo "alert('a d:$dname f:$fname ')\n";
		$snameA[]=$dname;
		$snameA[]=$fname;
	}
	#echo "alert('save sid $supplierid ft: $ftype dt: $doctype')\n";
	$docA[$supplierid][$ftype][$doctype]=$snameA;
	$srz=serialize($docA);
	#echo "alert('save $srz')\n";
	$uj["attachments"]=$srz;
	#echo "alert('$jobid - $srz')\n";
	performarrayupdate("jobs",$uj,"jobid",$jobid);

}


function jobNoteMailer(){
	tfw("jnml.txt","5661",true);
	$ntype=singlevalue("jobnote","ntype","jobnoteid",$this->noteid);
	$notetype=singlevalue("jobnote","notetype","jobnoteid",$this->noteid);
	if($ntype=="quote followup"){
		$px='!I!divid=qfollowup';
		#redraw open quotes and exit here, else it redraws the job
		#useful option would be to close all other items
		$this->quoteFollowUp($px);
		exit;
	}

	if($notetype=="internal") return ;




	#echo "alert('ok mail me')\n";
	#$this->jcallA[]="tb_init('a.thickbox, area.thickbox, input.thickbox,td.thickbox');";
	$notifyurl=singlevalue("jobs","notifyurl","jobid",$this->jobid);
	$hpos=strpos($notifyurl,"xhttp://");
		#echo "alert('hpos $hpos')\n";

	if($notifyurl!=""){
		if(strpos($notifyurl,"http://")===false){
			#echo "alert('no http')\n";
			if(strpos($notifyurl,"https://")===false){
				#echo "alert('no https')\n";
				$notifyurl="http://$notifyurl";
			}
		}
		#echo "alert('$this->jobid $notifyurl')\n";
		#echo "window.open('$notifyurl')\n";
	}
	#now does both
	#else{
		$caption="Mail Job Note";
		$url="../customscript/jobNoteMailer.php?rn=1&jobid=$this->jobid&nid=$this->noteid";
		$imageGroup="x";
		#echo "tb_show('$caption','$url','$imageGroup')\n";
		#$ist->toolA[0]["jname"]="jqUiModalExisting('divid=dialogJobNote!I!tn=jobnote!I!jobid=$id!I!title=jobnote')";
		#echo "alert('gonna mailit ')\n";
		$via=$this->paramsA["via"]=="appt"?$this->paramsA["via"]:"";
		echo "jqUiModalExisting('divid=dialogJobNoteMailer!I!tn=jobNoteMailer!I!noteid=$this->noteid!I!jobid=$this->jobid!I!via=$via!I!title=mailer')\n";

	#}
}

function newContactEmail($str){
	#saves new email to customer, redisplays job
	$ca=formCruncher($str);
	$jobid=$ca["jobid"];
	$posn=$ca["position"];
	$newpos="call_centre:$posn";
	$ca["position"]=$newpos;
	performarrayinsert("contact",$ca);
	echo "jobview('$jobid');\n";

}

function jobCompletor($str){
	$pa=formcruncher($str);
	#echo "alert('paf $str')\n";
	tfw("paf.txt","s $str",true);
	#test if complete, offer mail
	#$jobstage="next_notify_client";

	$jobstage=$pa["jstage"];

	#$jstatus=singlevalue("jobs","jobstage","jobid",$jobid,false);
	#echo "alert('closing $jobid $jstatus')\n";
	#echo "alert('$jobid $jstatus')\n";
	#if($jstatus=="next_notify_client"){
	$jobid=$pa["jobid"];
	$uja["jcompletedate"]=$pa["jcompletedate"];


	/*
	$completeDate=$fa["jobcompletedate"];
	$completeTime=$fa["timejobcompletedate"];
	$cdateTime="$completeDate $completeTime";
	*/



	performarrayupdate("jobs",$uja,"jobid",$jobid);
	$via=$pa["via"];
	$this->skipCheck=1;
	$this->jobStageChange($jobid,$jobstage,false);
  	$params="divid=dialogJobNote!I!tn=jobnote!I!ntype=complete!I!jobid=$jobid!I!title=jobnote!I!via=$via";
	$this->jqUiModal($params);
	#$this->jqUiModal($params,true);
}


function rfqCompletor($str){
	$pa=formcruncher($str);
	#echo "alert('paf $str')\n";
	tfw("paf.txt","s $str",true);
	#test if complete, offer mail
	#$jobstage="next_notify_client";
	$jobstage=$pa["jstage"];
	echo "if(g('editSpanjobstage')) g('editSpanjobstage').innerHTML='$jobstage' \n";
	$jobid=$pa["jobid"];
	$this->skipCheck=1;
	$this->jobStageChange($jobid,$jobstage,false);
}




#function buildQuoteText($jobid){
function buildDocText($jobid,$fid,$docpath){
	###load document
	ob_end_clean();
	ob_start();
	include_once "$this->basepath/infclasses/iDocLayouts.php";
	#include_once "$this->clientpath/custform/formclass.php";
	if(!$this->noFormDocInc) include_once "$this->clientpath/custform/formclass.php";//causing redeclare3.9.13

	#include_once "$this->clientpath/custform/banner.php";
	#include_once "$this->clientpath/ii/custform/dcfmq07J.php";
	include_once "$docpath";


	#get quote text from jobid
	outputform($jobid,$fid);
	$mnote = ob_get_contents();
	#$mnote.="end";
	ob_end_clean();
	ob_start();

	$mnote =makePlainText($mnote);
	$mnote =plainText($mnote);

	#echo "alert('bdct $docpath $jobid $fid')\n";

	tfw("bqt.txt",$mnote,true);
	return $mnote;
}

function addRecipientLine($ra,$message) {
	
	$recips = implode(", ",$ra);
	$message = "Recipients: $recips"."<br /><br />".$message;
	
	return $message;
}
function jobQuoteMailNow($head,$email,$cc,$ntype=null){
	$email=urldecode($email);
	$head=urldecode($head);

	$this->mailMode="jobs";
	$ha=formCruncher($head);
	#$email=preg_replace('undefined#\r?\n#','', $email);


	$ema=explode("!I!",$email);
	$ccA=explode("!I!",$cc);

	$chead=vClean(urldecode(nl2br($head)));

	$mnote=$ha["notes"];
	$jobid=$ha["jobid"];

	#echo "alert('aa pre jqn cc $cc jobid: $jobid')\n";
	#exit;

	#echo "alert('fin $jobid')\n";
	#exit;

	$mnote=$this->buildQuoteText($jobid);


	$vid="qtext".$jobid;
	$msx=sysvalue($vid);
	$ma=unserialize($msx);
	#$ma=explode("!I!",$msx);
	$sza=sizeof($ma);
	#foreach($ma as $mid=>$mval) $mtx.="$mid = $mval";


	$link=urldecode($ma["linkx"]);
	tfw("trysysv.txt","sz $sza  mtx $mtx vid: $vid head $head msx $msx link $link $mnote",true);

	$displayNote=vClean(nl2br($mnote));
	$enote.="$link \n";
	#tfw("qlink.txt","yep $link",true);
	#$enote.=$this->textCleaner($mnote);
	$enote.=$mnote;
	$subject=vClean(nl2br($ha["subject"]));

	#get quote text from jobid
	foreach($ema as $ir=>$recip){
	 $ra[] = $recip;
	}
	
	$enote = $this->addRecipientLine($ra, $enote);
	#tfw("enote.txt","str $head ee mmmm: $email: $enote",true);

	foreach($ema as $ir=>$recip){
		if(($recip!="undefined")&($recip!="")){
			if($ir>1){
				#cc to first recip only.
				unset($ccA);
				unset($ccl);
			}
			foreach($ccA as $ccm) $ccl.=";$ccm";
			if($this->mailItNow($recip,$subject,$enote,$attach,$ccA)){
			#tfw("qlink$recip.txt","yep $enote",true);
			#$mx.="<br>sent OK to $recip $ccl";
			$mx.=$this->mailResponder($recip,$attach);
			$sentok=true;
			}else{
			$mx.="<br>Not sent to $recip $this->mailFail";
			}
		}
	}

	#tfw("jqmx.txt","$mtx $vid $msx link:$link note: $enote",true);
	#tfw("jqmx.txt","$mtx $vid $msx link:$link note: $enote",true);

	#echo "alert('sok ')\n";
	$cx=vClean($mx);
	#echo "g('thickBoxWrapper').innerHTML='$cx'\n";
	#echo "tb_remove()\n";

	if($sentok){
	$this->qsent($jobid);
	}
	return;
	#exit;


	echo "g('thickBoxWrapper').innerHTML='$displayNote<br><br>  $mx'\n";
	#echo "alert('still ok jqn')\n";
	if($sentok){
		#update notified if completion
		if($ntype=="completion"){
			$ndt=date("Y-m-d");
			$ca["clientnotified"]='on';
			$ca["datenotified"]=$ndt;
			$ca["jobstage"]="Client_notified";
			performarrayupdate("jobs",$ca,"jobid",$jobid);
			#$this->standardMasterview("jobs","jobid",$jobid);
		}
	}
}


function jobNoteMailNowUIDryRun($head,$email,$cc,$docs,$odocs,$sdocs){
	# 2 stage (pass thru JS) approach to allow wait dialog that would
	# otherwise be killed by ob_start used during quote stage
	$params="divid=dialogWaiting!I!tn=waiting!I!ntype=$ntype!I!jobid=$jobid!I!id=$id!I!mx=$mx";
	#echo "alert('start wait')\n";
	$this->jqUiModal($params);
	#echo "alert('start wait 2')\n";
	echo "jobNoteMailNowUI('$head','$email','$cc','$docs','$odocs','$sdocs')\n";
	return;
}

function jobQuoteMailNowUIDryRun($head,$email,$cc,$docs,$qpdf){
	# 2 stage (pass thru JS) approach to allow wait dialog that would
	# otherwise be killed by ob_start used during quote stage
	$params="divid=dialogWaiting!I!tn=waiting!I!ntype=$ntype!I!jobid=$jobid!I!id=$id!I!mx=$mx";
	#echo "alert('start wait')\n";
	$this->jqUiModal($params);
	#echo "alert('start wait 2')\n";

	echo "jobQuoteMailNowUI('$head','$email','$cc','$docs','$qpdf')\n";
	return;
}

function jobJrqMailNowUIDryRun($head,$docs,$odocs,$sdocs,$cdocs,$pdf){
	# 2 stage (pass thru JS) approach to allow wait dialog that would
	# otherwise be killed by ob_start used during quote stage
	$params="divid=dialogWaiting!I!tn=waiting!I!ntype=$ntype!I!jobid=$jobid!I!id=$id!I!mx=$mx";
	#echo "alert('start wait')\n";
	$this->jqUiModal($params);
	#echo "alert('start wait 2')\n";
	echo "jobJrqMailNowUI('$head','$docs','$odocs','$sdocs','$cdocs','$pdf')\n";
	
		
	return;
}

function jobRfqMailNowUIDryRun($head,$docs,$odocs,$sdocs,$cdocs,$pdf){
	# 2 stage (pass thru JS) approach to allow wait dialog that would
	# otherwise be killed by ob_start used during quote stage
	$params="divid=dialogWaiting!I!tn=waiting!I!ntype=$ntype!I!jobid=$jobid!I!id=$id!I!mx=$mx";
	#echo "alert('start wait')\n";
	$this->jqUiModal($params);
	#echo "alert('start wait 2')\n";
	echo "jobRfqMailNowUI('$head','$docs','$odocs','$sdocs','$cdocs','$pdf')\n";
	return;
}


function jobEmailNowUIDryRun($head,$att){
	# 2 stage (pass thru JS) approach to allow wait dialog that would
	# otherwise be killed by ob_start used during quote stage
	$params="divid=dialogWaiting!I!tn=waiting!I!ntype=$ntype!I!jobid=$jobid!I!id=$id!I!mx=$mx";
	#echo "alert('start wait')\n";
	$this->jqUiModal($params);
	#echo "alert('start wait 2')\n";
	echo "jobEmailNowUI('$head','$att')\n";
	return;
}

function jobEmailNowUI($head,$attx){
	# 2nd stage
	tfw("jemh.txt","hhaaa $head att: $attx",true);
	$head=urldecode($head);

	$ha=formCruncher($head,"!A!","!eq!");
	$atta=formCruncherMultiLine($attx,"fname","!A!","!eq!");


	foreach($atta as $a=>$row){
		$i++;
		$fname=$row["fname"];
		$dname=$row["dname"];
		$inc=$row["include"];
		$dtx.="fn:$fname #dn:$dname #inc:$inc";
		if($inc=="on"){
			$docsx.="** $fname $dname";
			$att[$i]["fname"]=$fname;
			$att[$i]["dname"]=$dname;
			$att[$i]["relpath"]=$this->docPath["jobs"];
		}else{
			$docxs.="**docfail $i";
		}
	}



	$this->relpath="../../$this->clientFolder/customscript/docs";





	#$ha=formCruncher($head);
	$recipX=$ha["recips"];
	$subject=$ha["subject"];
	tfw("jemhA.txt","rec $recipX docs: $docsx dtx $dtx",true);
	$recipA=explode(";",$recipX);
	#first recip, others cc
	foreach($recipA as $i=>$recip){
		$sx.=" r item $i is $recip";
		if($i==0){
			$sx.=" * main email is $recip";
			if($recip!=""){
				$sx.=" -- added $recip to ema --";
				$ema[]=$recip;
				$emaX=implode(":",$ema);
				$sx.=" ** split ema $emaX **";
			}
		}else{
			if($recip!="") $ccA[]=$recip;
			$sx.=" * cc email is $recip";
		}
	}
	$body=$ha["emailBody"];
	$mnote=$this->textCleaner($body)	;
	tfw("jemail.txt","$sx $head try $subject body: $body clean:$mnote ",true);

	$this->mailMode="jobs";
	tfw("dnx.txt","display: head $head jobid $jobid $ntype $displayNote mail: $mnote plain:$plainx",true);
	$subject=vClean(nl2br($ha["subject"]));

	$emaX=implode(":",$ema);
	tfw("jemail2.txt","ee $emaX ",true);

	#$mx.="<br>attempted to send from $emaX";
	foreach($ema as $recip){
		if($recip!="undefined"){
			if($this->mailItNow($recip,$subject,$mnote,$att,$ccA)){
			#$mx.="<br>sent OK to $recip";
			$mx.=$this->mailResponder($recip,$att);
			$sentok=true;
			$recipA[]=$recip;
			$this->mailJobNoteSave($subject,$jobid,$recipA);
			}else{
			$mx.="<br>Not sent to $recip $this->mailFail";
			}
		}
	}

	if(sizeof($att)>0){
		$atc=$this->attachCount>0?$this->attachCount:0;
		$mx.="<br>with $atc attachments ";
	}

	if($sentok){
		#update notified if completion
	}
	tfw("jemlr.txt",$mx,true);

	$via=$ha["via"];
	$params="divid=dialogJobNoteResult!I!tn=mailResult!I!ntype=$ntype!I!jobid=$jobid!I!id=$id!I!mx=$mx!I!via=$via!I!$extras";
	#$this->jqUiModal($params,false);
	ob_end_clean();
	ob_start();

	echo "closeDialog() \n";
	#echo "$('#dialogWaiting').dialog('close') \n";
	$this->jqUiModal($params);




}


function contractorBatchUIDryRun(){
	# Different to other dry runs
	# data saved by post http ajax, not via this method
	$params="divid=dialogWaiting!I!tn=waiting!I!";
	#echo "alert('start wait')\n";
	$this->jqUiModal($params);
	#echo "alert('start wait 2')\n";
	echo "contractorBatchMailNowUI()\n";
	return;
}


function contractorBatchMailNowUI(){
	#retrieve data from sysvar and process
	$sid=$_SESSION["userid"]."cmail";
	$msx=sysvalue($sid,false);
	$ma=unserialize($msx);
	foreach($ma as $mk=>$mv){
		$mxx.="$mk $mv";
	}
	$szm=sizeof($ma);
	$ha=$ma["header"];
	$contractorA=$ma["lines"];

	$hsz=sizeof($ha);
	$lsz=sizeof($contractorA);


	#$contractorA=formCruncherMultiLine($l,"subid");
	tfw("cbnow.txt","5861 $msx szm $szm $mxx sz $hsz sz2 $lsz",true);
	$this->mailMode="contractors";
	#$ha=formCruncher($header);
	foreach($ha as $hk=>$hv){
		$hx.="$hk $hv";
	}
	tfw("headxcbm.txt","$msx $hx $mxx",true);

	$subject=vClean(nl2br($ha["subject"]));
	$preamble=vClean(nl2br($ha["message"]));

	tfw("cbnow2.txt","6611 $msx",true);
	foreach($contractorA as $i=>$row){
		$dname=$row["contractor"];
		$recip=$row["email"];
		$note=$row["jobsx"];
		$poref=$row["poref"];
		#$displayNote=vClean(nl2br($note));
		$footx=$this->mailFooter();
		$mnote="$preamble \n\n $note $footx";
		#$mnote=nl2br($mnote);
		$mnote=urldecode(nl2br($mnote));
		$mnote=str_replace('\n','',$mnote);

		#$mnote=$this->textCleaner($mnote)	;

		$cbmx.="** $dname $recip $subject $mnote";
		#$recip="tcbflash77@gmail.com";
		#$recip="simonb@velocityweb.com.au";
		if($recip!="undefined"){
			if($this->mailItNow($recip,$subject,$mnote)){
			#$mx.="<br>sent OK to $recip";
			$mx.=$this->mailResponder($recip);
			$sentok=true;
			#mark each po as reminded.
			$poa=explode(";",$poref);
			foreach($poa as $pon){
				$dt=date("Y-m-d H:s");
				$poda["remindersent"]=$dt;
				pau("purchaseorders",$poda,"poref",$pon);
			}
			}else{
			$mx.="<br>Not sent to $recip $this->mailFail";
			}
		}
	}
	tfw("cbmx.txt","$cbmx poref $poref",true);


	$params="divid=dialogJobNoteResult!I!tn=mailResult!I!ntype=$ntype!I!jobid=$jobid!I!id=$id!I!mx=$mx";
	#tfw("jq6634.txt","pre",true);
	#echo "$('#dialogWaiting').dialog('close') \n";
	#tfw("jq6636.txt","pre",true);
	ob_end_clean();
	ob_start();
	echo "closeDialog() \n";

	if($sentok){
	 $this->jqUiModal($params);
	 $this->qsent($jobid,false);
	}
}

function addRecipients($ra,$message) {
	
	$recips = implode(", ",$ra);
	$message = "Recipients: $recips"."<br /><br />".$message;
	
	return $message;
}


function jobQuoteMailNowUI($head,$email,$cc,$docs,$qpdf){
	$email=urldecode($email);
	$head=urldecode($head);
	$docs=urldecode($docs);
	$cc = urldecode($cc);
	
	tfw("jnmuiQ.txt","5861 $head $email $docs",true);
	tfw("jnmuiQA.txt","5861 $email $docs qpdf : $qpdf",true);
	$this->mailMode="jobs";

	#echo "$('#dialogWaiting').dialog('close') \n";
	$ha=$this->altDelims?formCruncher($head,"!A!","!eq!"):formCruncher($head);
	#$ema=formCruncher($email);
	$emam=$this->altDelims?formCruncherMultiLine($email,"email","!A!","!eq!"):formCruncherMultiLine($email,"email");
	$ema=aFV($emam,"email");
	tfw("jqemx.txt","ee $email",true);

	//$cc=explode("!I!",$cc);
	$chead=vClean(urldecode(nl2br($head)));
	$qnote=$ha["notes"];
	$jobid=$ha["jobid"];
	$ntype=$ha["ntype"];
	$password=$ha["password"];

	$this->docPath();
	$url="$this->remotePath/doc.php?id=$password";
	#$linkx.="<b>View formatted document and other options by clicking the following link, or opening in a browser <br><a href=$url>$url</a></b>";

/*	$vid="qtext".$jobid;
	$msx=sysvalue($vid);
	$ma=unserialize($msx);
	$link=urldecode($ma["linkx"]);
	tfw("trysysvUI.txt","sz $sza  mtx $mtx vid: $vid head $head msx $msx link $link $mnote",true);
*/
	$displayNote=vClean(nl2br($mnote));
	$enote.="$linkx \n";

	$docpath="$this->clientpath/ii/custform/dcfmq07J.php";
	$fid="dcfmq07J";
	$qnote=$this->buildDocText($jobid,$fid,$docpath);
	#$qnote=$this->buildQuoteText($jobid);
	$mnote="$enote $qnote";

	$displayNote=vClean(nl2br($mnote));
	#$mnote=$this->textCleaner($mnote)	;
	tfw("dnx.txt","display: head $head jobid $jobid $ntype $displayNote mail: $mnote plain:$plainx",true);
	$subject=vClean(nl2br($ha["subject"]));

	#tfw("qjdocx.txt",$docs,true);
	$doca=$this->altDelims?formCruncherMultiLine($docs,"jdocn","!A!","!eq!"):formCruncherMultiLine($docs,"jdocn");
	foreach($doca as $i=>$row){
		$fname=$row["fname"];
		$dname=$row["dname"];
		$docsx.="** $fname $dname";
		$att[$i]["fname"]=$fname;
		$att[$i]["dname"]=$dname;
	}
	#$attachment=$doca;
	$attx=serialize($att);
	#$this->relpath="../../$this->clientFolder/libs/docs";
	#$this->relpath="$this->pdfPath/quotes";
	$this->relpath="$this->pdfPath"."quotes";

	tfw("serz.txt","atx $attx : $tryx pth $pth" ,true);


	#qpdf
	if($qpdf){
	$i++;
		$fname="quote_$jobid.pdf";
		$dname=$fname;
		$docsx.="** $fname $dname";
		$att[$i]["fname"]=$fname;
		$att[$i]["dname"]=$dname;
		#$att[$i]["relpath"]="../../$this->clientFolder/libs/docs";
		$att[$i]["relpath"]="$this->pdfPath"."quotes";

		$fullurl=$this->relpath."/$fname";
		$killQ[]=$fullurl;
   }




	tfw("ddocs.txt","$docs $docsx $fullurl",true);

	$mnote = $this->addRecipients($ema,$mnote);
	$this->logit("14468: $mnote");
	$this->logit(print_r($cc,1));
	
	//email!eq!eric.choa@ventia.com.au!A!cc!eq!on
	
	
	$cc2 = '';
	if ($cc != ''){
	 $this->logit("cc: ".$cc);
	 $ipos = strpos($cc,"!A!cc");
	 $cc2 = substr($cc,9,$ipos-9);
	 $this->logit("ipos: $ipos cc2: $cc2"); 
	}
	
	foreach($ema as $recip){
		if($recip!="undefined"){
			if($this->mailItNow($recip,$subject,$mnote,$att,$cc2)){
			#$mx.="<br>sent OK to $recip";
			$mx.=$this->mailResponder($recip,$att);
			$sentok=true;
			$recipSentA[]=$recip;
			}else{
			$mx.="<br>Not sent to $recip $this->mailFail";
			}
		}
	}


/*	if($sentok){
		#update notified if completion
		if($ntype=="completion"){
			$ndt=date("Y-m-d");
			$ca["clientnotified"]='on';
			$ca["datenotified"]=$ndt;
			$ca["jobstage"]="client_notified";
			$mx.="<BR>Job stage set to 'client_notified'";
			performarrayupdate("jobs",$ca,"jobid",$jobid);
			#$this->standardMasterview("jobs","jobid",$jobid);
			$needRefresh=true;
		}
	}
*/
	$params="divid=dialogJobNoteResult!I!tn=mailResult!I!ntype=$ntype!I!jobid=$jobid!I!id=$id!I!mx=$mx";
	$this->mailResult=$mx;
	#tfw("jq6634.txt","pre",true);
	#echo "$('#dialogWaiting').dialog('close') \n";
	#tfw("jq6636.txt","pre",true);
	if(!$this->viaJquery) 	echo "$('#dialogWaiting').dialog('close') \n";
	if($sentok){
		$this->taskReminders("sentQuote",$jobid,$recip);
		if(!$this->viaJquery) $this->jqUiModal($params);
		$this->qsent($jobid,false,$recipSentA);
	}
	$this->deleteFile($killQ);
}

function taskReminders($type,$jobid,$recip=null,$tuserid=null){
	$userid=$tuserid!=""?$tuserid:$_SESSION["userid"];
	#quotes state based reminder system
	switch($type){
		case "sentQuote":
		$ccRules["NSW"]="crm1@dcfm.com.au";
		$ccRules["ACT"]="manager@dcfm.com.au";
		$ccRules["VIC"]="manager@dcfm.com.au";
		$ccRules["NT"]="manager@dcfm.com.au";
		$ccRules["QLD"]="manager@dcfm.com.au";
		$ccRules["SA"]="bdm@dcfm.com.au";
		$ccRules["WA"]="bdm@dcfm.com.au";
		$ccRules["TAS"]="bdm@dcfm.com.au";
		#get job followupuser
		$kf=kda("sitestate,qfollowuserid,customerid");
		$ka["jobid"]=$jobid;
		$sa=iqatkf("jobs",$kf,$ka);
		$qfuid=$sa["qfollowuserid"];
		$state=$sa["sitestate"];
		$custid=$sa["customerid"];
		$statFuser=$ccRules[$state];
		$ruser=$qfuid!=""?$qfuid:$ccRules[$state];
		$fuser=$ruser;
		elog("state user: $statFuser fuser:$fuser");
		#$ap=strpos($ruser,"@");
		#$fuser=substr($ruser,0,$ap);
		$h=date("h");
		$min=date("m");
		$inty=date("Y");
		$intm=date("m");
		$intd=date("d");
		$twoDays=date("Y-m-d h:i",mktime($h,$min,0,$intm,$intd+2,$inty));
		$oneDay=date("Y-m-d h:i",mktime($h,$min,0,$intm,$intd+1,$inty));
		$tdate=date("Y-m-d h:i");
		$tsk["userid"]=$userid;
		$tsk["jobid"]=$jobid;
		$tsk["customerid"]=$custid;
		$tsk["tdate"]=$tdate;
		$tsk["reminder"]=$oneDay;
		$tsk["followupdate"]=$twoDays;
		$tsk["subject"]="Quote Reminder";
		$tsk["area"]="Quote Reminder";
		$tsk["raisedby"]=$_SESSION["userid"];
		$tsk["allocatedto"]=$fuser;
		$tsk["detail"]="Followup generated by quote sent to $recip $tdate ";
		pai("task",$tsk);
		break;
	}
}




function jobRfqInfMailNowUI($head,$docs,$odocs,$sdocs,$cdocs,$pdf,$mode){
	$this->jobInfoRq($head,$docs,$odocs,$sdocs,$cdocs,$pdf,$mode);
}
function jobJrqInfMailNowUI($head,$docs,$odocs,$sdocs,$cdocs,$pdf,$mode){
	$this->jobInfoRq($head,$docs,$odocs,$sdocs,$cdocs,$pdf,$mode);
}
function jobInfoRq($head,$docs,$odocs,$sdocs,$cdocs,$pdf,$mode=null){
	$head=urldecode($head);
	$docs=urldecode($docs);
	$odocs=urldecode($odocs);
	$sdocs=urldecode($sdocs);
	$cdocs=urldecode($cdocs);
	elog("odoc: $odocs<br>s: $sdocs","jrmui 9697 custominterface");
	elog("info head : <br>s: $head","cinf 10014 custominterface");

	$this->mailMode="jobs";

	$ha=formCruncher($head,"!A!","!eq!");
	$recips=trim($ha["recips"]);
	$ema=explode(";",$recips);


	$cc=explode("!I!",$cc);
	$chead=vClean(urldecode(nl2br($head)));
	$notes=$ha["notes"];
	$jobid=$ha["jobid"];
	$rfqno=$ha["rfqno"];
	$ntype=$ha["ntype"];
	$password=$ha["password"];


	$this->docPath();
	$url="$this->remotePath/doc.php?id=$password";

	#$mnote=vClean(nl2br($notes));
	$mnote=nl2br($notes);
	elog("mm $mnote","cinf 10041");


	$subject=vClean(nl2br($ha["subject"]));


	#need diff relpaths depending...
	$this->relpath="../../$this->clientFolder/libs/docs";
	$this->relpath="../../$this->clientFolder/libs/docs/jobsafety";
	$this->relpath="../../$this->clientFolder/customscript/docs";


	$doca=formCruncherMultiLine($docs,"jdocn");
	foreach($doca as $a=>$row){
		$i++;
		$fname=$row["fname"];
		$dname=$row["dname"];
		$docsx.="** $fname $dname";
		$att[$i]["fname"]=$fname;
		$att[$i]["dname"]=$dname;
		#$att[$i]["relpath"]="../../$this->clientFolder/libs/docs";
		$att[$i]["relpath"]=$this->docPath["jobs"];
	}

	$sdoca=formCruncherMultiLine($sdocs,"sdocn");
	foreach($sdoca as $a=>$row){
		$i++;
		$fname=$row["fname"];
		$dname=$row["dname"];
		$docsx.="** $fname $dname";
		$att[$i]["fname"]=$fname;
		$att[$i]["dname"]=$dname;
		#$att[$i]["relpath"]="../../$this->clientFolder/libs/docs/safety";
		#$att[$i]["relpath"]=$this->docPath["jobs"]."/safety";
		$att[$i]["relpath"]=$this->docPath["jobsafety"];


	}
	#elog("dd sdocs: $sdocs <br>** odocs:$odocs <br>dsofar: $docsx ","ciface 9778");
	$odoca=formCruncherMultiLine($odocs,"odocn");
	$wordDocxA=kda("B - SWMS - BLANK TEMPLATE,Technician Job Process Summary,SWMS - DCFM BLANK TEMPLATE");
	$wordDocA=kda("Work Instruction - Instruction for completing DCFM safety sheet,Work Instruction - Rules for using DCFM generic SWMS");
	$pdfA=kda("Form - 004 Safety Identifications & Action Sheet Check Sheet (DCFM),COMP - 0002 (PTCD) - DCFM Technician Declaration Form,Form - 004 Safety Identifications & Action Sheet Check Sheet (DCFM),Form - 004-1 -Hazard Prompt Sheet UGL (Aust Post),Form - 004-2 -Hazard Prompt Sheet UGL,Form - 004-3 -Take5 Hazard Assessment Form (Caltex),Form - 004-4 -JSEA Five-D,Form - 004 Safety Identifications - Action Sheet Check Sheet -DCFM,Form - 004-1 -Hazard Prompt Sheet UGL -Aust Post,Form - 004-2 -Hazard Prompt Sheet UGL,Form - 004-3 -Take5 Hazard Assessment Form -Caltex");

#Form - 004 Safety Identifications - Action Sheet Check Sheet -DCFM
#Form_-_004_Safety_Identifications_-_Action_Sheet_Check_Sheet_-DCFM

	$wdx=implode("!!",$wordDocA);
	if(strpos($odocs,"Work Instruction - Instruction for completing DCFM safety sheet")){
		#add hazard sheet also
		#$newrow["fname"]="hazards";
		#$newrow["dname"]="hazards";
		$newrow["fname"]="Form - 004 Safety Identifications & Action Sheet Check Sheet (DCFM)";
		$newrow["dname"]="Form - 004 Safety Identifications & Action Sheet Check Sheet (DCFM)";
		$odoca[]=$newrow;
	}

	foreach($odoca as $a=>$row){
		$i++;
		/*
		#pre Feb 2013 old method
		$fname=$row["fname"];
		$dname=$row["dname"];
		$docsx.="** $fname $dname";
		$extn="html";
		if(in_array($fname,$wordDocxA)) $extn="docx";
		if(in_array($fname,$wordDocA)) $extn="doc";
		if(in_array($fname,$pdfA)) $extn="pdf";
		#special case make dname=fname
		$att[$i]["fname"]=$fname.".$extn";
		$att[$i]["dname"]=$fname.".$extn";
		$att[$i]["relpath"]=$this->docPath["jobs"]."/safety";
		*/

		##Feb 2013 implemented doc id version##
		$docid=$row["dname"];
		$sq="select * from document where documentid=$docid";
		$docA=iqasra($sq);
		$extn=$docA["docformat"];
		$fname=$docA["docname"];
		$att[$i]["fname"]=$docid.".$extn";
		$att[$i]["dname"]=$fname;
		#$att[$i]["relpath"]=$this->docPath["jobs"]."/safety";
		$att[$i]["relpath"]=$this->docPath["jobsafety"];


	}
	#ptfw("odoc.txt","ox $odocs $docsx",true);

	$cdoca=formCruncherMultiLine($cdocs,"cdocn");
	foreach($cdoca as $a=>$row){
		$i++;
		$fname=$row["fname"];
		$dname=$row["dname"];
		$docsx.="** $fname $dname";
		$att[$i]["fname"]=$fname;
		$att[$i]["dname"]=$dname;
		#$att[$i]["relpath"]="../../$this->clientFolder/customscript/docs";
		$att[$i]["relpath"]=$this->docPath["customer"];
		$crpath=$this->docPath["customer"];
	}

	$attx=serialize($att);


	#elog("dd $docsx ","ciface 9835");

	$this->mailOrigin="rfqInfo";

	foreach($ema as $recip){
		#echo "alert('jrq to $recip')\n";
		if(($recip!="undefined")&($recip!="")){
			if($this->mailItNow($recip,$subject,$mnote,$att)){
				elog("$recip $subject","jrmui 10077 custominterface");
				$mx=$this->mailResponder($recip,$att);
				$sentok=true;
			$recipSentA[]=$recip;
			}else{
			$mx.="<br>Not sent to $recip $this->mailFail";
			}
		}
	}

	if(sizeof($att)>0){
		$atc=$this->attachCount>0?$this->attachCount:0;
		$mx.="<br>with $atc attachments ";
	}
	$this->mailResult=$mx;

	$oldrecip=$ha["cmpemail"];
	$supplierid=$ha["supplierid"];
	if($recip!=$oldrecip) $newMailDetected=true;
	if($newMailDetected){
		tfw("mrecipA.txt","$recip vs $oldrecip for $supplierid",true);
		$jx="newmail('$supplierid','$recip');";
		$newmail="!I!newmail=$recip!I!customerid=$supplierid";
		#$this->newmail="!I!newmail=$recip!I!customerid=$supplierid";
	}
	$params="divid=dialogJobNoteResult!I!tn=mailResult!I!ntype=$ntype!I!jobid=$jobid!I!id=$id!I!mx=$mx$newmail";
	ob_end_clean();
	ob_start();
	if($sentok){
		#create job note
		$this->mailJobNoteSave("$mode Info Request $subject ",$jobid,$recipSentA);
	}
}


function jobRfqMailNowUI($head,$docs,$odocs,$sdocs,$cdocs,$pdf){
	$head=urldecode($head);
	$docs=urldecode($docs);
	$odocs=urldecode($odocs);
	$sdocs=urldecode($sdocs);
	$cdocs=urldecode($cdocs);
	elog("odoc: $odocs<br>s: $sdocs","jrmui 9697 custominterface");


	tfw("jnmuiJRQ.txt","5861 $head $email $sdocs pdf $pdf",true);
	tfw("jnmuiJRQA.txt","5861 $email $docs",true);
	$this->mailMode="jobs";

	$ha=formCruncher($head,"!A!","!eq!");
	#$ema=formCruncher($email);
	#$email=$ha["recips"];

	$recips=trim($ha["recips"]);
	$ema=explode(";",$recips);


	$cc=explode("!I!",$cc);
	$chead=vClean(urldecode(nl2br($head)));
	$qnote=$ha["notes"];
	$jobid=$ha["jobid"];
	$rfqno=$ha["rfqno"];
	$ntype=$ha["ntype"];
	$password=$ha["password"];


	/*
	$vid="qtext".$jobid;
	$msx=sysvalue($vid);
	$ma=unserialize($msx);
	$link=urldecode($ma["linkx"]);
	tfw("trysysvUI.txt","sz $sza  mtx $mtx vid: $vid head $head msx $msx link $link $mnote",true);
	*/

	$this->docPath();
	$url="$this->remotePath/doc.php?id=$password";
	#$linkx.="<b>View formatted document and other options by clicking the following link, or opening in a browser <br><a href=$url>$url</a></b>";

	$displayNote=vClean(nl2br($mnote));
	$enote.="$linkx \n";

	$docpath="$this->clientpath/ii/custform/dcfmrfq07J.php";
	$fid="dcfmwo07J.php";
	$jnote=$this->buildDocText($rfqno,$fid,$docpath);
	$mnote="$enote $jnote";

	$displayNote=vClean(nl2br($mnote));
	#$mnote=$this->textCleaner($mnote)	;
	tfw("dnx.txt","display: head $head jobid $jobid $ntype $displayNote mail: $mnote plain:$plainx",true);
	$subject=vClean(nl2br($ha["subject"]));


	#need diff relpaths depending...
	$this->relpath="../../$this->clientFolder/libs/docs";
	$this->relpath="../../$this->clientFolder/libs/docs/jobsafety";
	$this->relpath="../../$this->clientFolder/customscript/docs";


	$doca=formCruncherMultiLine($docs,"jdocn");
	foreach($doca as $a=>$row){
		$i++;
		$fname=$row["fname"];
		$dname=$row["dname"];
		$docsx.="** $fname $dname";
		$att[$i]["fname"]=$fname;
		$att[$i]["dname"]=$dname;
		#$att[$i]["relpath"]="../../$this->clientFolder/libs/docs";
		$att[$i]["relpath"]=$this->docPath["jobs"];


	}

	$sdoca=formCruncherMultiLine($sdocs,"sdocn");
	foreach($sdoca as $a=>$row){
		$i++;
		$fname=$row["fname"];
		$dname=$row["dname"];
		$docsx.="** $fname $dname";
		$att[$i]["fname"]=$fname;
		$att[$i]["dname"]=$dname;
		#$att[$i]["relpath"]="../../$this->clientFolder/libs/docs/safety";
		#$att[$i]["relpath"]=$this->docPath["jobs"]."/safety";
		$att[$i]["relpath"]=$this->docPath["jobsafety"];


	}
#elog("dd sdocs: $sdocs <br>** odocs:$odocs <br>dsofar: $docsx ","ciface 9778");
	$odoca=formCruncherMultiLine($odocs,"odocn");
	$wordDocxA=kda("B - SWMS - BLANK TEMPLATE,Technician Job Process Summary,SWMS - DCFM BLANK TEMPLATE");
	$wordDocA=kda("Work Instruction - Instruction for completing DCFM safety sheet,Work Instruction - Rules for using DCFM generic SWMS");
	$pdfA=kda("Form - 004 Safety Identifications & Action Sheet Check Sheet (DCFM),COMP - 0002 (PTCD) - DCFM Technician Declaration Form,Form - 004 Safety Identifications & Action Sheet Check Sheet (DCFM),Form - 004-1 -Hazard Prompt Sheet UGL (Aust Post),Form - 004-2 -Hazard Prompt Sheet UGL,Form - 004-3 -Take5 Hazard Assessment Form (Caltex),Form - 004-4 -JSEA Five-D,Form - 004 Safety Identifications - Action Sheet Check Sheet -DCFM,Form - 004-1 -Hazard Prompt Sheet UGL -Aust Post,Form - 004-2 -Hazard Prompt Sheet UGL,Form - 004-3 -Take5 Hazard Assessment Form -Caltex");

	$wdx=implode("!!",$wordDocA);

	if(strpos($odocs,"Work Instruction - Instruction for completing DCFM safety sheet")){
		#add hazard sheet also
		#$newrow["fname"]="hazards";
		#$newrow["dname"]="hazards";
		$newrow["fname"]="Form - 004 Safety Identifications & Action Sheet Check Sheet (DCFM)";
		$newrow["dname"]="Form - 004 Safety Identifications & Action Sheet Check Sheet (DCFM)";
		$odoca[]=$newrow;
	}

	foreach($odoca as $a=>$row){
		$i++;
		/*
		#pre Feb 2013 old method
		$fname=$row["fname"];
		$dname=$row["dname"];
		$docsx.="** $fname $dname";
		$extn="html";
		if(in_array($fname,$wordDocxA)) $extn="docx";
		if(in_array($fname,$wordDocA)) $extn="doc";
		if(in_array($fname,$pdfA)) $extn="pdf";
		#special case make dname=fname
		$att[$i]["fname"]=$fname.".$extn";
		$att[$i]["dname"]=$fname.".$extn";
		$att[$i]["relpath"]=$this->docPath["jobs"]."/safety";
		*/

		##Feb 2013 implemented doc id version##
		$docid=$row["dname"];
		$sq="select * from document where documentid=$docid";
		$docA=iqasra($sq);
		$extn=$docA["docformat"];
		$fname=$docA["docname"];
		$att[$i]["fname"]=$docid.".$extn";
		$att[$i]["dname"]=$fname;
		#$att[$i]["relpath"]=$this->docPath["jobs"]."/safety";
		$att[$i]["relpath"]=$this->docPath["jobsafety"];



	}
	#ptfw("odoc.txt","ox $odocs $docsx",true);


	$cdoca=formCruncherMultiLine($cdocs,"cdocn");
	foreach($cdoca as $a=>$row){
		$i++;
		$fname=$row["fname"];
		$dname=$row["dname"];
		$docsx.="** $fname $dname";
		$att[$i]["fname"]=$fname;
		$att[$i]["dname"]=$dname;
		#$att[$i]["relpath"]="../../$this->clientFolder/customscript/docs";
		$att[$i]["relpath"]=$this->docPath["customer"];
		$crpath=$this->docPath["customer"];
	}


	tfw("ddocs.txt","$docs $docsx crp $crpath",true);
	#$attachment=$doca;
	$attx=serialize($att);

	tfw("serz.txt","atx $attx : $tryx pth $pth" ,true);


	#pdf
	if($pdf){
		$i++;
		$fname="rfq_$rfqno.pdf";
		$dname=$fname;
		$docsx.="** $fname $dname";
		$att[$i]["fname"]=$fname;
		$att[$i]["dname"]=$dname;
		$att[$i]["relpath"]="$this->pdfPath/rfq";

		$fullurl=$this->pdfPath."rfq/$fname";
		$killQ[]=$fullurl;

   }

#elog("dd $docsx ","ciface 9835");

	$this->mailOrigin="rfq";

	foreach($ema as $recip){
		#echo "alert('jrq to $recip')\n";
		if(($recip!="undefined")&($recip!="")){
			if($this->mailItNow($recip,$subject,$mnote,$att)){
				elog("$recip $subject","jrmui 10077 custominterface");
				$mx=$this->mailResponder($recip,$att);
				$sentok=true;
			$recipSentA[]=$recip;
			}else{
			$mx.="<br>Not sent to $recip $this->mailFail";
			}
		}
	}

	if(sizeof($att)>0){
		$atc=$this->attachCount>0?$this->attachCount:0;
		$mx.="<br>with $atc attachments ";
	}
	$this->mailResult=$mx;

	$oldrecip=$ha["cmpemail"];
	$supplierid=$ha["supplierid"];
	if($recip!=$oldrecip) $newMailDetected=true;
	if($newMailDetected){
		tfw("mrecipA.txt","$recip vs $oldrecip for $supplierid",true);
		$jx="newmail('$supplierid','$recip');";
		$newmail="!I!newmail=$recip!I!customerid=$supplierid";
		#$this->newmail="!I!newmail=$recip!I!customerid=$supplierid";
	}

	$params="divid=dialogJobNoteResult!I!tn=mailResult!I!ntype=$ntype!I!jobid=$jobid!I!id=$id!I!mx=$mx$newmail";
	ob_end_clean();
	ob_start();


	#echo "closeDialog() \n";

	#echo "$('#dialogWaiting').dialog('close') \n";
	#hide results
	#$this->jqUiModal($params);
	if($sentok){
		#create job note
		$this->mailJobNoteSave("RFQ $subject ",$jobid,$recipSentA);
	}
	$this->deleteFile($killQ);

}

function docPath(){
	$sname=$_SESSION["serverName"];
	$sport=$_SERVER["SERVER_PORT"];
	if($sport!="80") $sname.=":$sport";
	$pa=explode("/",$this->clientpath);
	$sz=sizeof($pa);
	$dirN=$pa[$sz-1];
	$this->remotePath="http://$sname/$dirN/iremote";
}


function jobJrqMailNowUI($head,$docs,$odocs,$sdocs,$cdocs,$pdf,$delim1="!A!",$delim2="!eq!"){
	tfw("jnmuiJRQ.txt","5861 $head $email $docs pdf $pdf",true);
	tfw("jnmuiJRQA.txt","5861 $email $docs",true);
	$this->logit("enter jobJRQMailNowUI");
	$this->logit("sdocs: $sdocs");
	
	
	$head=urldecode($head);
	$docs=urldecode($docs);
	$odocs=urldecode($odocs);
	$sdocs=urldecode($sdocs);
	$cdocs=urldecode($cdocs);

	$this->logit("urldecoded sdocs: $sdocs");

	$this->mailMode="jobs";
	#echo "$('#dialogWaiting').dialog('close') \n";
	$ha=formCruncher($head,$delim1,$delim2);

	#$ema=formCruncher($email);
	$email=$ha["email"];
	$cc=explode("!I!",$cc);

	$recips=trim($ha["recips"]);
	$ema=explode(";",$recips);



	$chead=vClean(urldecode(nl2br($head)));
	$qnote=$ha["notes"];
	$jobid=$ha["jobid"];
	$poref=$ha["jrqno"];
	$ntype=$ha["ntype"];
	$password=$ha["password"];



	$this->docPath();
	$url="$this->remotePath/doc.php?id=$password";
	#$linkx.="<b>View formatted document and other options by clicking the following link, or opening in a browser <br><a href=$url>$url</a></b>";

	#$vid="qtext".$jobid;
	#$msx=sysvalue($vid);
	#$ma=unserialize($msx);
	#$link=urldecode($ma["linkx"]);
	tfw("trysysvUI.txt","sz $sza  mtx $mtx vid: $vid head $head msx $msx link $link $mnote",true);

	$displayNote=vClean(nl2br($mnote));
	$enote.="$linkx \n";
	$enote.=$this->getPortalLink($jobid,$poref);

	$this->logit("enote: ".$enote);

	$docpath="$this->clientpath/ii/custform/dcfmwo07J.php";
	$fid="dcfmwo07J.php";

	$jnote=$this->buildDocText($poref,$fid,$docpath);
	#$mnote="$enote $jnote";
	$mnote=$this->optionalPreambleNote."$enote $jnote";

$this->logit("jrq email");

$this->logit($jnote);

	$displayNote=vClean(nl2br($mnote));
	#$mnote=$this->textCleaner($mnote)	;
	tfw("dnx.txt","display: head $head jobid $jobid $ntype $displayNote mail: $mnote plain:$plainx",true);
	$subject=vClean(nl2br($ha["subject"]));


	#need diff relpaths depending...
	$this->relpath="../../$this->clientFolder/libs/docs";
	$this->relpath="../../$this->clientFolder/libs/docs/jobsafety";
	$this->relpath="../../$this->clientFolder/customscript/docs";


	$doca=formCruncherMultiLine($docs,"jdocn");
	if(isset($doca)){
	foreach($doca as $a=>$row){
		$i++;
		$fname=$row["fname"];
		$dname=$row["dname"];
		$docsx.="** $fname $dname";
		$att[$i]["fname"]=$fname;
		$att[$i]["dname"]=$dname;
		#$att[$i]["relpath"]="../../$this->clientFolder/libs/docs";
		$att[$i]["relpath"]=$this->docPath["jobs"];
		$x.="$fname = $dname";
	}
	}

	$sdoca=formCruncherMultiLine($sdocs,"sdocn");
	foreach($sdoca as $a=>$row){
		$i++;
		$fname=$row["fname"];
		$dname=$row["dname"];
		$docsx.="** $fname $dname";
		$att[$i]["fname"]=$fname;
		$att[$i]["dname"]=$dname;
		#$this->docPath="/srv/www/htdocs/infomaniacDocs/jobdocs";
		#$att[$i]["relpath"]="../../$this->clientFolder/libs/docs/safety";
		#$att[$i]["relpath"]=$this->docPath["jobs"]."/safety";
		$att[$i]["relpath"]=$this->docPath["jobsafety"];
	}

	$odoca=formCruncherMultiLine($odocs,"odocn");
	$wordDocxA=kda("B - SWMS - BLANK TEMPLATE,Technician Job Process Summary");
	$wordDocA=kda("Work Instruction - Instruction for completing DCFM safety sheet,Work Instruction - Rules for using DCFM generic SWMS");
	$pdfA=kda("Form - 004 Safety Identifications & Action Sheet Check Sheet (DCFM),COMP - 0002 (PTCD) - DCFM Technician Declaration Form,Form - 004 Safety Identifications & Action Sheet Check Sheet (DCFM),Form - 004-1 -Hazard Prompt Sheet UGL (Aust Post),Form - 004-2 -Hazard Prompt Sheet UGL,Form - 004-3 -Take5 Hazard Assessment Form (Caltex),Form - 004-4 -JSEA Five-D,Form - 004 Safety Identifications - Action Sheet Check Sheet -DCFM,Form - 004-1 -Hazard Prompt Sheet UGL -Aust Post,Form - 004-2 -Hazard Prompt Sheet UGL,Form - 004-3 -Take5 Hazard Assessment Form -Caltex");


	$wdx=implode("!!",$wordDocA);
	foreach($odoca as $a=>$row){
		$i++;
		/*
		#pre Feb 2013 old method
		$fname=$row["fname"];
		$dname=$row["dname"];
		$docsx.="** $fname $dname";
		$extn="html";
		if(in_array($fname,$wordDocxA)) $extn="docx";
		if(in_array($fname,$wordDocA)) $extn="doc";
		if(in_array($fname,$pdfA)) $extn="pdf";
		#special case make dname=fname
		$att[$i]["fname"]=$fname.".$extn";
		$att[$i]["dname"]=$fname.".$extn";
		$att[$i]["relpath"]=$this->docPath["jobs"]."/safety";
		*/

		##Feb 2013 implemented doc id version##
		$docid=$row["dname"];
		$sq="select * from document where documentid=$docid";
		$docA=iqasra($sq);
		$extn=$docA["docformat"];
		$fname=$docA["docname"];
		$att[$i]["fname"]=$docid.".$extn";
		$att[$i]["dname"]=$fname;
		#$att[$i]["relpath"]=$this->docPath["jobs"]."/safety";
		$att[$i]["relpath"]=$this->docPath["jobsafety"];


	}
	#ptfw("odoc.txt","ox $odocs $docsx",true);

	$cdoca=formCruncherMultiLine($cdocs,"cdocn");
	foreach($cdoca as $a=>$row){
		$i++;
		$fname=$row["fname"];
		$dname=$row["dname"];
		$docsx.="** $fname $dname";
		$att[$i]["fname"]=$fname;
		$att[$i]["dname"]=$dname;
		$att[$i]["relpath"]=$this->docPath["customer"];
	}


	#STBinserting alternate attach via supplierInduction -
	if(isset($this->alternateAtt)){
		$att=$this->alternateAtt;
		$i=sizeof($att);
	}
	#//STBinserting alternate attach via supplierInduction -


	#pdf
	if($pdf){
		$i++;
		$fname="jrq_$poref.pdf";
		$dname=$fname;
		$docsx.="** $fname $dname";
		$att[$i]["fname"]=$fname;
		$att[$i]["dname"]=$dname;
		$att[$i]["relpath"]="$this->pdfPath/jrq";

		$fullurl=$this->pdfPath."jrq/$fname";
		$killQ[]=$fullurl;

   }


	tfw("ddocs.txt","$docs $docsx ",true);
	#$attachment=$doca;
	
	$this->logit("att: $att");
	$attx=serialize($att);
	$this->logit("serialized att: $attx");
	
	tfw("serz.txt","atx $attx : $tryx pth $pth" ,true);


	#echo "alert('6784')\n";
	#$mnote="short message";
	$this->mailOrigin="jrq";
	if($this->alternateNote!="") $mnote=$this->alternateNote;

	foreach($ema as $recip){
		#echo "alert('jrq to $recip')\n";
		if(($recip!="undefined")&($recip!="")){
			if($this->mailItNow($recip,$subject,$mnote,$att)){
				$mx.=$this->mailResponder($recip,$att);

			$sentok=true;
			$recipSentA[]=$recip;
			}else{
			$mx.="<br>Not sent to $recip $this->mailFail";
			}
			/**/
		}
	}

	if(sizeof($att)>0){
		$atc=$this->attachCount>0?$this->attachCount:0;
		$mx.="<br>with $atc attachments ";
	}


	$this->mailResult=$mx;


	$oldrecip=$ha["cmpemail"];
	$supplierid=$ha["supplierid"];
	if($recip!=$oldrecip) $newMailDetected=true;
	if($newMailDetected){
		tfw("mrecipA.txt","$recip vs $oldrecip for $supplierid",true);
		$jx="newmail('$supplierid','$recip');";
		$newmail="!I!newmail=$recip!I!customerid=$supplierid";
	}

	$params="divid=dialogJobNoteResult!I!tn=mailResult!I!ntype=$ntype!I!jobid=$jobid!I!id=$id!I!mx=$mx$newmail";
	tfw("newpx.txt",$params,true);
	tfw("jrq6895.txt","pre",true);

	ob_end_clean();
	ob_start();


	#echo "closeDialog() \n";
	#echo "$('#dialogWaiting').dialog('close') \n";
	tfw("jrq6897.txt","pre",true);
	#exit;
	#return;
	#$this->jqUiModal($params);
	if($sentok){
		#other activity
		$this->mailJobNoteSave("JRQ $subject ",$jobid,$recipSentA);
		
		//ANK 13.06.2014 Change JobStage to "waiting_jrq_response"
		$jobstage="waiting_jrq_response";
		$this->jobStageChange($jobid,$jobstage,true);
		
		$_SESSION["ErrorReport"] = false;
		require_once ($_SERVER['DOCUMENT_ROOT'] . "/common/phoenix/workflowrule.class.php");
		$this->logit("workflow jrq $poref");
		$wf = new workflowrule(appType::JobTracker,"");
		$wf->executeJRQrules($poref,ruleEvent::onemail);
		$res = print_r($wfresult,1);
		$this->logit("workflow result".$res);
		error_reporting(0);	
				
	}
	$this->deleteFile($killQ);

}
function getPortalLink($jobid,$jrq){

		$_SESSION["ErrorReport"] = false;
		require_once ($_SERVER['DOCUMENT_ROOT'] . "/common/phoenix/bl/compliance.class.php");
		$comp = new compliance(AppType::JobTracker, $_SESSION['userid']);
		$portalLink = $comp -> getPortalLink($jobid,$jrq);
		error_reporting(0);	
		return $portalLink;
/*	
	$sql = "SELECT cpa.pass FROM purchaseorders po 
	INNER JOIN customer c ON po.supplierid=c.customerid 
	INNER JOIN contact con ON con.email = c.email 
	INNER JOIN complianceportalaccess cpa ON cpa.xrefid=con.contactid
	WHERE poref=$jrq";

	$this->logit("getPortalLink: ".$sql);

	$result = mysql_query($sql);
	if(mysql_num_rows($result) == 0){
		$this->logit('No compliancePortalAccess record found using query: '.$sql.' so adding record in compliancePortalAccess now');
		
		#now try customer
		$sql = "SELECT cpa.pass FROM purchaseorders po 
		INNER JOIN customer c ON po.supplierid=c.customerid 
		INNER JOIN contact con ON con.email = c.email 
		INNER JOIN complianceportalaccess cpa ON cpa.xrefid=c.customerid
		WHERE poref=$jrq";

		$this->logit("getPortalLink: ".$sql);
		
		$result = mysql_query($sql);
		if(mysql_num_rows($result) == 0){
			
		#Create complianceportalaccess record
		$suppid = singlevalue("purchaseorders","supplierid","poref",$jrq);
		$q = "SELECT * FROM profileitem po INNER JOIN contact con ON po.contactid = con.contactid WHERE con.customerid=$suppid";
		$tf = kda("profileitemid");
		$pid = iqasrf($q,"profileitemid");
		$this->logit("pid: $pid");
		
		if (! isset($pid)){
			
		} else {
			$pass = $this->gen_password(36);
			$cpa["profileitem"] = $pid;
			$cpa["pass"] = $pass;
			$cpa["xrefid"] = $suppid;
			$cpa["xreftable"]="customer";
			//$id = pai("complianceportalaccess",$cpa);
			
		}
		
	
		}  else {
		  $row = mysql_fetch_assoc($result);
		  $this->logit('compliancePortalAccess record found using query: '.$sql);
	
		  $pass = $row['pass'];
			
		}		
		//$aS->paramsA['pword'] = $aS->gen_password(20);
		//setupNewPortal($aS);
		return "";
	}
	
	
		$sname=$_SESSION["serverName"];
    	$sport=$_SERVER["SERVER_PORT"];

	    if($sport!="80") $sname.=":$sport";

    	$remotePath="http://$sname/dcfm07/iremote/portal.php?id=$pass";
    	$this->logit("remotepath: ".$remotePath);
	
	
	$style = ".btn {
  		background: #3498db;
  		background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
 		background-image: -moz-linear-gradient(top, #3498db, #2980b9);
  		background-image: -ms-linear-gradient(top, #3498db, #2980b9);
  		background-image: -o-linear-gradient(top, #3498db, #2980b9);
  		background-image: linear-gradient(to bottom, #3498db, #2980b9);
  		-webkit-border-radius: 14;
  		-moz-border-radius: 14;
  		border-radius: 14px;
  		font-family: Arial;
  		color: #ffffff;
  		font-size: 22px;
  		padding: 8px 18px 8px 18px;
  		text-decoration: none;
	}";
	
	$purl = $remotePath;
	$onclick = "window.location.href='$purl'";
	$butx = '<button class= "btn" onclick='.$onclick.'>Log in</button>';
	//$portallinkx.="<div><style>$style</style>";
	$portallinkx.="<div style='text-align:left;padding-left:100px;font-family:Verdana,arial;font-size:9pt;'>";
    //$portallinkx.="<p><b>Access your contractor compliance portal ".$butx."</b><p>";
	$portallinkx = "Access your DCFM Contractor compliance portal from this <a href='$purl'><b><u>Portal link</u></b></a><br><br>";
		
	return $portallinkx;
*/	
}


function jobNoteMailNowUI($head,$email,$cc,$docs,$odocs,$sdocs){
	$email=urldecode($email);
	$head=urldecode($head);
	$docs=urldecode($docs);
	$odocs=urldecode($odocs);
	$sdocs=urldecode($sdocs);

	#echo "alert('jnmui')\n";
	#echo "window.scrollTo(0,0);\n";
    #echo "if(g('waiter')) g('waiter').style.visibility = 'visible';\n";
	#$params="divid=dialogJobNoteResult!I!tn=mailResult!I!id=$id!I!mx=Please wait...";
	#echo "hideWaiter()\n";
	#$this->jqUiModal($params);
	$this->mailMode="jobs";

	echo "$('#dialogWaiting').dialog('close') \n";


	tfw("jnmui.txt","5861 $head $email",true);
	$ha=formCruncher($head);
	#$ema=formCruncher($email);
	$emam=formCruncherMultiLine($email,"email");
	$ema=aFV($emam,"email");

	$cc=explode("!I!",$cc);
	$chead=vClean(urldecode(nl2br($head)));
	#$mnote=$ha["notes"];
	$jobid=$ha["jobid"];
	$ntype=$ha["ntype"];
	$noteid=$ha["noteid"];
	$uid=$_SESSION["userid"];
	$q="select doctext from tempdoc where reference=$noteid and userid='$uid'";
	$mA=iqasra($q,"doctext");
	$mnote=$mA["doctext"];
	$displayNote=vClean(nl2br($mnote));
	$mnote=$this->textCleaner($mnote)	;
	tfw("dnxjnmui.txt","display: head $head jobid $jobid $ntype $displayNote mail: $mnote plain:$plainx eml: $email docs $docs",true);
	$subject=vClean(nl2br($ha["subject"]));

	/*
	echo "g('thickBoxWrapper').innerHTML='$displayNote<br><br>  $mx'\n";
	*/

	$doca=formCruncherMultiLine($docs,"jdocn");
	foreach($doca as $i=>$row){
		$fname=$row["fname"];
		$dname=$row["dname"];
		$docsx.="**doca $fname $dname";
		$att[$i]["fname"]=$fname;
		$att[$i]["dname"]=$dname;
		$att[$i]["relpath"]=$this->docPath["jobs"];

	}
	tfw("ddocs.txt","$docs $docsx ",true);
	#$attachment=$doca;
	$attx=serialize($att);
	#$this->relpath="../../$this->clientFolder/libs/docs";
	$this->relpath="../../$this->docFolder";
	$sdoca=formCruncherMultiLine($sdocs,"sdocn");
	foreach($sdoca as $a=>$row){
		$i++;
		$fname=$row["fname"];
		$dname=$row["dname"];
		$docsx.="**sdoca $fname $dname";
		$att[$i]["fname"]=$fname;
		$att[$i]["dname"]=$dname;
		#$this->docPath="/srv/www/htdocs/infomaniacDocs/jobdocs";
		#$att[$i]["relpath"]="../../$this->clientFolder/libs/docs/safety";
		$rpath=$this->docPath["jobs"]."/safety";
		#$att[$i]["relpath"]=$this->docPath["jobs"]."/safety";
		$att[$i]["relpath"]=$this->docPath["jobsafety"];

	}

	$odoca=formCruncherMultiLine($odocs,"odocn");
	$wordDocxA=kda("B - SWMS - BLANK TEMPLATE,Technician Job Process Summary");
	$wordDocA=kda("Work Instruction - Instruction for completing DCFM safety sheet,Work Instruction - Rules for using DCFM generic SWMS");
	$wdx=implode("!!",$wordDocA);
	foreach($odoca as $a=>$row){
		$i++;
		$fname=$row["fname"];
		$dname=$row["dname"];
		$docsx.="** $fname $dname";
		$extn="html";
		if(in_array($fname,$wordDocxA)) $extn="docx";
		if(in_array($fname,$wordDocA)) $extn="doc";
		#special case make dname=fname
		$att[$i]["fname"]=$fname.".$extn";
		$att[$i]["dname"]=$fname.".$extn";
		$att[$i]["relpath"]="../../$this->clientFolder/customscript/docs";
	}
	#ptfw("odoc.txt","ox $odocs $docsx",true);



	tfw("serzD.txt","atx dpth $rpath $docsx" ,true);

	elog("jub pre updated $jobid $docsx","10503 cinf");


	foreach($ema as $recip){
		if($recip!="undefined"){
			if($this->mailItNow($recip,$subject,$mnote,$att)){
			#$mx.="<br>sent OK to $recip";
			$mx.=$this->mailResponder($recip,$att);
			tfw("mmmn.txt","ssmm $subject : $mnote dsm $displayNote",true);
			$sentok=true;
			$recipA[]=$recip;
			$this->mailJobNoteSave($subject,$jobid,$recipA);
			}else{
			$mx.="<br>Not sent to $recip $this->mailFail";
			}
		}
	}

	if(sizeof($att)>0){
		$atc=$this->attachCount>0?$this->attachCount:0;
		$mx.="<br>with $atc attachments ";
	}



	if($sentok){
		#update notified if completion
		if($ntype=="completion"){
			$ndt=date("Y-m-d");
			$ca["clientnotified"]='on';
			$ca["datenotified"]=$ndt;
			$ca["jobstage"]="client_notified";
			$mx.="<BR>Job stage set to 'client_notified'";
			elog("jub updated $jobid","10535 cinf");
			tfw("jscpre.txt","j $jobid",true);
			performarrayupdate("jobs",$ca,"jobid",$jobid);
			#$this->jobStageChange($jobid,"client_notified");
			#how to get this to work...-add to modal onsuccess methods
			#echo "alert('ok')\n";
			#echo "if(g('jobstage')) g('jobstage').value='$jobstage' \n";
			$extras="newjobstage=client_notified";
			#$this->standardMasterview("jobs","jobid",$jobid);
			$needRefresh=true;
			
			//ANK 4.4.2014 Email events fromm client portal
			include_once($this->clientpath."/contractor/Include/Class/cls.mail.php");
	    	$objmail = new clsmail;
	    	$this->logit("before job complete eventmail");
	    	$objmail->eventEmail($jobid,"JOBCOMPLETE"); 
			
			
		}
	}

	$via=$ha["via"];
	$params="divid=dialogJobNoteResult!I!tn=mailResult!I!ntype=$ntype!I!jobid=$jobid!I!id=$id!I!mx=$mx!I!via=$via!I!$extras";
	#$this->jqUiModal($params,false);
	ob_end_clean();
	ob_start();

	echo "closeDialog() \n";
	#echo "$('#dialogWaiting').dialog('close') \n";
	$this->jqUiModal($params);



}

function jobNoteMailNow($head,$email,$ntype=null){
	$email=urldecode($email);
	$head=urldecode($head);

	$ha=formCruncher($head);
	$ema=explode("!I!",$email);
	$chead=vClean(urldecode(nl2br($head)));
	$mnote=$ha["notes"];
	$jobid=$ha["jobid"];
	$displayNote=vClean(nl2br($mnote));
	$mnote=$this->textCleaner($mnote)	;
	$this->mailMode="jobs";

	tfw("dnx.txt","display: head $head jobid $jobid $ntype $displayNote mail: $mnote plain:$plainx",true);

	$subject=vClean(nl2br($ha["subject"]));

	foreach($ema as $recip){
		if($recip!="undefined"){
			if($this->mailItNow($recip,$subject,$mnote,$attach)){
			#$mx.="<br>sent OK to $recip";
			$mx.=$this->mailResponder($recip,$attach);
			$sentok=true;
			}else{
			$mx.="<br>Not sent to $recip $this->mailFail";
			}
		}
	}
	#echo "g('thickBoxWrapper').innerHTML='$displayNote<br><br>  $mx'\n";
	elog("sending the sucker $recip $ntype","cinf 10593");
	if($sentok){
		#update notified if completion
		if($ntype=="completion"){
			$ndt=date("Y-m-d");
			$ca["clientnotified"]='on';
			$ca["datenotified"]=$ndt;
			$ca["jobstage"]="client_notified";
			performarrayupdate("jobs",$ca,"jobid",$jobid);
			#$this->standardMasterview("jobs","jobid",$jobid);
		}
	}
}







function strposall($haystack,$needle){
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


function accountsEmailList($customerid){
$cq="select distinct c.email,mailgroupdesc as position from groupmembers as g
left outer join contact as c
on g.contactid=g.contactid where email!=''
and g.mailgroupdesc in ('invoice','Accounts dept','invoices','statement','statements')
and c.customerid=$customerid
group by c.email
order by c.email
";
tfw("emq$customerid.txt",$cq,true);
$this->sqlEmailList($cq);
return $this->emlist;
}

function sqlEmailList($q=null){
$scf=kda("position,email");
$cca=iqa($q,$scf);

if(sizeof($ea)==0){
	$ea=$cca;
}else{
	if(sizeof($cca)>0){
		$ea=array_merge($ea,$cca);
	}
}

	$ist=new divTable();
   	$ist->highlighter=true;
   	$ist->highlightFname="stageDetail";
   	$ist->rowID=kda("astage");
	if($this->noXL) $ist->noXL=true;
	if(isset($this->colClass)) $ist->colClass=$this->colClass;
	$title="Email List";
	$tf=kda("position,email,include,cc");
	$tf["include"]="Mail&nbsp;";
	$ist->input_fields["include"]="checkboxAJ";
	$ist->input_fields["cc"]="checkboxAJ";
	$ist->input_fields["email"]="hpd";
	$ist->formtag="<form name=mailLines>";
	$emgx=$ist->inf_sortable($tf,$ea,"Email Recipients List",null,null,true);
	#$emx="<form name=mailLines>$emgx</form>";
	$emx=$emgx;
 #return $emx;
 $this->emlist=$emx;

}


function jobEmailList($jobid){
$ja=iqatr("jobs","jobid",$jobid);

$fmemail=$ja["sitefmemail"];
$bemail=$ja["billingemail"];
$extend=$ja["extenduntil"];
$customerid=$ja["customerid"];
$custordref=$ja["custordref"];

$this->logit("fm: $fmemail");
$this->logit("be: $bemail");

if($fmemail!=""){
$ea[0]["position"]="job id $jobid<br>Site Fm &nbsp;";
$ea[0]["email"]=$fmemail;
$ea[0]["include"]=$fmemail;
}else{echo "<font color=red><br>Note:No site fm email</font>";}
if($bemail!=""){
$ea[1]["position"]="Cl Billing &nbsp;";
$ea[1]["email"]=$bemail;
$ea[1]["include"]="on";
}else{echo "<font color=red><br>Note:No cl email</font>";}

$this->logit("ea:".print_r($ea,1));

$cq="select * from contact where email!=''
and (position like '%call_centre%' or role='call centre')
and customerid=$customerid";
#echo $cq;
$scf=kda("position,email");
$cca=iqa($cq,$scf);

if(sizeof($ea)==0){
	$ea=$cca;
}else{
	if(sizeof($cca)>0){
		$ea=array_merge($ea,$cca);
	}
}

$this->logit("ea2:".print_r($ea,1));

	//ANK 2016-01-27 Check for Rule to send Quotes to designated contacts only
	$_SESSION["ErrorReport"] = false;
	require_once ($_SERVER['DOCUMENT_ROOT'] . "/common/phoenix/bl/blClientPortal.class.php");
	$rule = new blClientPortal(AppType::JobTracker, $_SESSION['userid']);
	$rulename = "restrict_quote_recipient";
	$contacts = $rule -> getCustomerRuleValue($customerid,$rulename);
	$cc_contacts = $rule -> getCustomerRuleValue($customerid,'quote_cc_recipient');
	error_reporting(0);
	$this->logit("quote contactid: ".$contacts);		
	$this->logit("quote cc contactid: ".$cc_contacts);
	if($contacts && $cc_contacts !=""){
		$cq="select POSITION,email,'on' AS include,'' AS cc from contact where contactid in ($contacts)
		UNION select POSITION,email,'' as include,'on' as cc from contact where contactid in ($cc_contacts)";
	}
	if($contacts && $cc_contacts ==""){
		$cq="select POSITION,email,'on' AS include,'' AS cc from contact where contactid in ($contacts)";
	}

	if($contacts == "" && $cc_contacts ==""){
		$cq = "select * from contact where email!='' and (position like '%call_centre%' or role IN ('call centre','sitefm')
		and customerid=$customerid";
	}
	

		$this->logit($cq);
		$scf=kda("position,email,include,cc");
		$earule=iqa($cq,$scf);
		if (sizeof($earule)>0){
		echo "<br><span style='color:red'>Email recipients overridden by a rule.</span><br>";
		$ea = $earule;
		}
	


	$ist=new divTable();
   	$ist->highlighter=true;
   	$ist->highlightFname="stageDetail";
   	$ist->rowID=kda("astage");
	if($this->noXL) $ist->noXL=true;
	if(isset($this->colClass)) $ist->colClass=$this->colClass;
	$title="Email List";
	$tf=kda("position,email,include,cc");
	$tf["include"]="Mail&nbsp;";
	$ist->input_fields["include"]="checkboxAJ";
	$ist->input_fields["cc"]="checkboxAJ";
	$ist->input_fields["email"]="hpd";
	$ist->formtag="<form name=mailLines>";
	$emgx=$ist->inf_sortable($tf,$ea,"Email Recipients List",null,null,true);
	#$emx="<form name=mailLines>$emgx</form>";
	$emx=$emgx;
 return $emx;
}


function rfqEmailList($rfq){
if($this->jrqInstead){
$ja=iqatr("purchaseorders","poref",$rfq);
}else{
$ja=iqatr("rfq","rfqno",$rfq);
}
$supplierid=$ja["supplierid"];
$this->supplierEmailList($supplierid);
}

function supplierEmailList($supplierid){
	if(!$supplierid>0) return;//STB 2013.10.22
$this->defaultEmail=singlevalue("customer","email","customerid",$supplierid);
 if($this->defaultEmail!="") return $this->defaultEmail;
$cq="select * from contact
where email!=''
and customerid=$supplierid";
#echo $cq;
$scf=kda("position,email");
$cca=iqa($cq,$scf);

if(sizeof($ea)==0){
	$ea=$cca;
}else{
	if(sizeof($cca)>0){
		$ea=array_merge($ea,$cca);
	}
}

	$ist=new divTable();
   	$ist->highlighter=true;
   	$ist->highlightFname="stageDetail";
   	$ist->rowID=kda("astage");
	if($this->noXL) $ist->noXL=true;
	if(isset($this->colClass)) $ist->colClass=$this->colClass;
	$title="Email List";
	$tf=kda("position,email,include,cc");
	$tf["include"]="Mail&nbsp;";
	$ist->input_fields["include"]="checkboxAJ";
	$ist->input_fields["cc"]="checkboxAJ";
	$ist->input_fields["email"]="hpd";
	$ist->formtag="<form name=mailLines>";
	$ist->tableidx="id=emailList";
	$emgx=$ist->inf_sortable($tf,$ea,"Email Recipients List",null,null,true);
	#$emx="<form name=mailLines>$emgx</form>";
	$emx=$emgx;
	$this->defaultEmail=$ea[1]["email"];


 return $emx;
}



function costgrid(){
	#echo "alert('migapproving');\n";
	#exit;
	$this->poCostGrid('all');
	$this->poCostGrid('synched');
	$this->poCostGrid('unsynched');
	#followup with current month and day selection
	#$this->poCostGrid(date('m'),date('Y'));
	#$this->poCostGrid(date("Y-m-d"));
	jtop();
}

function poCostGrid($mode){
	$this->toppoCostGrid($mode);
	$fx.="<div id=\'topGrid\'>".vClean($this->dgx)."</div>";
	$fx.="<div id=\'dailyTotals\' style=\'float:left;\'></div>";
	$fx.="<div id=\'dailyDetail\' style=\'padding-left: 20px;\'>daily detail</div>";

	echo "g('main-content').innerHTML='$fx'\n";
}


function toppoCostGrid($mode){
	$tig="aaa";
	$heading="Contractor Cost $ By Month $mode (invoice date)";
	$dref="pocostdaysummary$mode";
	$value_exp="sum(cost)";
	$condx="";
	switch($mode){
		case "synched":
		$condx=" and i.syncdate is not null";
		break;
		case "unsynched":
		$condx=" and i.syncdate is null";
		break;
	}
	$this->dql="select distinct i.invoiceref,sum(cost) as val,
	month(i.invoicedate) as mn,year(i.invoicedate) as yn from purchaseorders as i
	left outer join customer as c
	on i.supplierid=c.customerid
	where i.invoicedate>'2008-01-01'
	$condx
	group by mn,yn
	order by yn desc,mn desc";
	for($c=1;$c<=12;$c++){
	#$this->ajaxStaticDataTrailing[$c]=kda("mode=$mode");
	}
	$this->dataGrid("purchaseorders","podate",$value_exp,$heading,$dref,0,$condx);
	//return $tig;
}

function pocostdaysummary($m,$y,$mode="All",$viabuff=false){
	//echo "alert('$m $y')\n";
	$fx="dd $m $y";
	$this->monthpocostList($m,$y,$mode);
	$this->dlx=$this->dlx;
	$fx=vClean($this->dlx);
	if(!$viabuff){
	echo "g('dailyTotals').innerHTML='$fx'\n";
	echo "g('dailyDetail').innerHTML=''\n";
	}
}

function monthpocostList($m,$y,$mode="All"){
	$tig="aaa";
	$heading="Daily PO Cost Totals";
	$dref="pocostdaydetailall";
	$value_exp="sum(i.cost)";

	#$condx="and i.chargetype='product'";
	switch($mode){
		case "synched":
		$dref="pocostdaydetailsynched";
		$condx=" and i.syncdate is not null";
		break;
		case "unsynched":
		$dref="pocostdaydetailunsynched";
		$condx=" and i.syncdate is null";
		break;
	}

	$this->dql="select distinct i.invoicedate as dt,
 	 sum(i.cost) as val
 	 from purchaseorders as i
 	 where month(i.invoicedate)=$m
 	 and year(i.invoicedate)=$y
 	 $condx
 	 group by invoicedate
 	 order by invoicedate";
 	 #elog("mpcl $this->dql","cinf 10677");
 	 tfw("mgl.txt","qq $this->dql",true);
	$this->monthlyDateSummary("purchaseorders","invoicedate",$value_exp,$heading,$dref,2,$condx,$join,$m,$y);
	//return $tig;
}

function pocostdaydetail($dt,$mode="All"){
	$this->dailyPoCostDetail($dt,$mode);
	$this->dtx=$this->dtx;
	$fx=vClean($this->dtx);
	if(!$viabuff){
	//$fx="ddd $dt";
	//$fx="ddd ";
	echo "g('dailyDetail').innerHTML='$fx'\n";
	}
}


function dailyPoCostDetail($dt,$smode=null){
	$tig="aaa";
	$dref="jobview";
	#$value_exp="sum(buyqty*supplierprice)";
	$value_exp="sum(i.cost)";
	$m=date("m",strtotime($dt));
	#showing entire month for this one.
	#$mode="M";
	#$mode="D";
	$dtx=$mode.date("d M Y",strtotime($dt));
	switch($mode){
		default:
		$dcondx="where invoicedate='$dt'";
		break;
	}
	switch($smode){
		case "synched":
		$condx=" and i.syncdate is not null";
		break;
		case "unsynched":
		$condx=" and i.syncdate is null";
		break;
	}
	$heading="PO Costs by Job ID created $dtx";
	$this->dql="select distinct i.jobid as ref,i.poref,c.companyname,i.invoicedate as dt,
 	 $value_exp as val
 	 from purchaseorders as i
	 left outer join customer as c on i.supplierid=c.customerid
 	 $dcondx and cost<>0
 	 $condx
 	 group by i.poref
 	 order by i.jobid";

	//$condx="and i.chargetype='product' ";
	$join="left outer join customer as c on i.supplierid=c.customerid";
	$groupby="i.poref";
	#elog("$this->dql","cinf 10712");
 	 tfw("dgd.txt",$this->dql,true);

	$ist=new divTable();
		$ist->ajaxFname["ref"]=$dref;
		$ist->ajaxData["ref"]=kda("jobid");
		$ist->ajaxStaticData["ref"]=kda("jobid");
		$ist->icon["ref"]="v";

			$ist->multilink["ref"][0]=array("ajaxHrefIconMulti");
  			$ist->ajaxFnameM["ref"][0]="jobview";
  			$ist->ajaxDataM["ref"][0]=kda("ref");
  			#$ist->ajaxStaticDataM["ref"][0]=kda("jobid");
			$ist->iconM["ref"][0]="v";



		#$ist->multilink["soref"][]=array("ajaxHrefIcon","iformdoc.php?fid=hodgInv&custform=1&detail=1&ref=","edit");
		$this->customTable=$ist;
	$this->dailyDetailList("purchaseorders","jobid",$value_exp,$heading,$dref,2,$condx,$join,$groupby,$dt,"podate");
	//return $tig;
}


function jqsuggestHandler($idf,$id,$mode,$params=nll){
	#echo "alert('jsh 7718 idf:$idf id: $id mode: $mode p $params')\n";
	#only for first time customer/supplier searches
	tfw("jqshx.txt","$idf $id $mode",true);
	if($idf=="prodid") return false;
 	$pA=formCruncher($params);
	switch($mode){
		case "sitefm":
		echo "if(g('contactid')) g('contactid').value='$id'\n";
		$ca=iqatr("contact","contactid",$id);
		$ph=$ca["mobile"];
		$eml=$ca["email"];
		#$fmname=$ca["firstname"]." ".$ca["surname"];
		#only using first name now
		$fmname=$ca["firstname"];
		#if jobid, update job, refresh view
		if($pA["jobid"]>0){
			$jobid=$pA["jobid"];
		#echo "alert('got $id: $fmname $ph $eml for $jobid')\n";
			$uja["sitefmph"]=$ph;
			$uja["sitefmemail"]=$eml;
			$uja["contactid"]=$id;
			$uja["sitefm"]=$fmname;
			pau("jobs",$uja,"jobid",$jobid);
			echo "ajm('Refreshing view',800)\n";;
			echo "jobview($jobid)\n";
		}else{
			$fmname=vClean($fmname);

			echo "if(g('sitefm')) g('sitefm').value='$fmname'\n";
			echo "if(g('sitefmph')) g('sitefmph').value='$ph'\n";
			echo "if(g('sitefmemail')) g('sitefmemail').value='$eml'\n";
		}
		return true;
		break;

		case "_xjob":
		 #now handled by modal routine.
		 #changing customerid on job

		 if($idf=="customerid"){
			#echo "alert('$idf,$id,$mode,$params');\n";
			#changing related documents
			$jobid=$pA["jobid"];
			$this->logMasterEdit("jobs","jobid",$jobid,"customerid",$id);
			$docA=kda("jobs,diary,invoice,invoicelines");
			if($jobid>0){
				foreach($docA as $tn){
					$uq="update $tn set customerid=$id where jobid=$jobid";
					mysql_query($uq);
					$rowsC=mysql_affected_rows();
					$note[]="$rowsC $tn records updated";
					tfw("uu$tn.txt","$uq $rowsC",true);
				}
			foreach($note as $nx) $nnx.=$nx."\\n";
			#$nx=implode(" ok",$note);
			$messx="Customer ID changed, note the following ";
			$messx.="\\n";
			$messx.=$nnx;
			echo "alert('$messx')\n";
			}

		}
		break;


	}


	switch($idf){
		case "sitesuggest":
		break;

		default:
		#suburb
		#echo "if(g('suburb')) g('suburb').value='$idf $mode plus $id'\n";
		break;
	}
}

function NcustomisedAddressList($id){
	$tx="$tname $id";
		$sqlx="select * from addresslabel where customerid=$id
		order by sitesuburb";
		$da=iqa($sqlx,null,null,"addresslabel");
		$ist=new divTable();
		$tf=kda("labelid,address,ldesc,primarymail,primaryship");
		$tf["ldesc"]="Description";
		$tf["primarymail"]="Default Mail?";
		$tf["primaryship"]="Default Shipping?";


		#new modal version of adding

		$ist->highlighter=true;
		#$ist->rowID=kda("labelid,customerid");
		#$ist->highlightFname=("addresslabeledit");

		$ist->toolA[0]["ttype"]="button";
		$ist->toolA[0]["label"]="Add New";
		#$ist->toolA[0]["jname"]="newModalAddress($id)";


		#UI method
		$ist->rowID=kda("labelid");
		$ist->highlightFname="editAddress";
		$ist->highlightFname="jqUiModalExisting";
		$ist->excludeColData=true;

		$ist->rowIDstaticPre=kda("divid=dialogAddressLabel!I!tn=addresslabel!I!title=Address!I!");


		#$ist->toolA[0]["jname"]="jqUiModal('tn=addresslabel!I!title=New','div',startAddress)";
		$ist->toolA[0]["jname"]="jqUiModalExisting('divid=dialogAddressLabel!I!tn=addresslabel!I!title=Address')";

/*		$ist->input_fields["customerid"]="hidden";
		$ist->input_fields["labelid"]="hidden";
		$ist->input_fields["primarymail"]="tickfield";
		$ist->input_fields["primaryship"]="tickfield";
*/


 		$szd=sizeof($tf);

		$ist->tableIDcontainer="fred style='$this->standardScrollDimensions'";
		$ist->tablen="addresslabel";

		#checksecurity
		if(killok("addresslabel"))	$ist->offerDelete=true;
		#		$ist->noHighlight=kda("delete");

		$_SESSION["refresh"]["addresslabel"]="$"."this->displayCustAddresses($id);";


		$nx=$ist->inf_sortable($tf,$da,"Addresses ",null,null,true);
$nx.=$sqlx;

	$cx=vClean($nx);
	echo "if(g('guts')) g('guts').innerHTML='$cx'\n";
	#return $nx;

}

function customisedAddressList($custid){
	#$this->ibuffer("addresses","!I!divid=guts!I!customerid=$custid");
	$px="!I!tabno=1!I!primid=$custid!I!divid=guts";
	$this->ibuffer("addresses",$px);

	return;
	$params="!I!customerid=$custid!I!divid=guts";
	#$this->ibuffer("addressCard",$params);
	#return;
	#added March 2010
	$ccx="$custid address here";

	#count jobs
	$q="select distinct j.labelid,
	count(j.jobid) as jcount from jobs as j
	where customerid=$custid
	group by labelid";
	$tf=kda("labelid,jcount");
	$ja=iqa($q,$tf,"labelid");


	#count images
	$q="select distinct j.labelid,
	count(d.documentid) as dcount from document as d
	left outer join jobs as j
	on d.xrefid=j.jobid
	where d.xreftable='jobs'
	and d.xrefid>300000
	and customerid=$custid
	group by labelid";
	$tf=kda("labelid,dcount");
	$da=iqa($q,$tf,"labelid");

	$ist=new divTable();
	$nx=$ist->inf_sortable($tf,$da,"Image count");
	#$cx=vClean($nx);
	#dont display image counts

	$af=kda("labelid,siteline1,siteline2,sitesuburb,sitepostcode,sitecontact,sitefm");
	$aft=implode(",",$af);
	#$aq="select $aft from addresslabel where customerid=$custid ";
	$aq="select $aft from addresslabel where customerid=$custid and (siteline1 is not null or siteline2 is not null)";
	$aa=iqa($aq,$af);
	foreach($aa as $i=>$row){
		$labelid=$row["labelid"];
		$icount=$da[$labelid]["dcount"];
		$jcount=$ja[$labelid]["jcount"];
		$aa[$i]["jcount"]=$jcount;
		$aa[$i]["icount"]=$icount;
	}
	$af["jcount"]="Job count";
	$af["icount"]="Image count";

	$ist=new divTable();

		#$ist->highlighter=true;
		#$ist->rowID=kda("labelid,customerid");
		#$ist->highlightFname=("addresslabeledit");


		#$ist->input_fields["customerid"]="hidden";
		#$ist->input_fields["labelid"]="hidden";
		#$ist->input_fields["primarymail"]="tickfield";
		#$ist->input_fields["primaryship"]="tickfield";


		$ist->highlighter=true;
		$ist->rowID=kda("labelid");
		$ist->highlightFname="genericJqUiModal";
		$ist->rowIDstatic=kda("addresslabel");


		$ist->toolA[0]["ttype"]="button";
		$ist->toolA[0]["label"]="Add New";

		$ist->toolA[1]["ttype"]="button";
		$ist->toolA[1]["jname"]="ibuffer('addressConversion','!I!custid=$custid!I!divid=guts')";
		$ist->toolA[1]["label"]="Import Addresses";



		#UI method
		$ist->rowID=kda("labelid");
		$ist->highlightFname="editAddress";
		$ist->highlightFname="jqUiModalExisting";
		$ist->excludeColData=true;

		$ist->rowIDstaticPre=kda("divid=dialogAddressLabel!I!tn=addresslabel!I!title=Address!I!");


		#$ist->toolA[0]["jname"]="jqUiModal('tn=addresslabel!I!title=New','div',startAddress)";
		$ist->toolA[0]["jname"]="jqUiModalExisting('divid=dialogAddressLabel!I!tn=addresslabel!I!title=Address')";


	$ist->offerDelete=true;
	$ist->tablen="addresslabel";
	# eg: $_SESSION["refresh"]["contact"]="$"."this->displayCustContacts($custid);";
	$_SESSION["refresh"]["addresslabel"]="$"."this->customisedAddressList($custid);";




	$nx=$ist->inf_sortable($af,$aa,"Address List",20);
	$cx.=vClean($nx);

	echo "if(g('guts')) g('guts').innerHTML='$cx'\n";

}


function customisedSearhes($context,$str){
	switch($context){
		case "xcontact":
		#contact taken to standardAlternateSearhes
		$sa=explode("=",$str);
		$sv=isset($sa[2])?$sa[2]:$sa[1];
		$pA=formCruncher($str);
		$stype=$pA["stype"];
		$sv=str_replace("stype","",$sv);
		#echo "alert('yes str: $str sv:$sv')\n";
		$this->ibuffer("contacts","!I!via=ksearch!I!stype=$stype!I!ns=$sv");
		exit;
		break;
	}
}


function mailFooter(){
$nx.="
\n\n
Thanks and regards,
\n
Team DCFM!
Ph:     02 9460 7676 \nFax:    02 9460 8913 \nEmail:  dcfm@dcfm.com.au \nDCFM Australia Pty Ltd \nABN   69 122487 076";
return $nx;
}



function custSearchHandler($pda=null){
	#$jql="$('#mobile').val('wht')\n";
	$customerid=$pda["customerid"];
	#$pc=new iForm();
	#echo "alert('cxt $customerid $this->context')\n";
	if($this->context=="cash") return;
	$varq="select contactid as bossid,concat(firstname,' ',surname) as fieldtofind from contact where (firstname!='' or surname!='')
	and customerid=$customerid order by firstname";
	#$pc->valtable["bossid"]=$varq;
	#$ftype="validatedsql";
	#$nbf=$pc->renderFormField("bossid",$ftype);
	#$options="<option id=a>aa</option>";
	$ox=makeidselectoptions($varq,"","fieldtofind","bossid");
	tfw("poxxs.txt",$ox,true);
	$ox=str_replace("</SELECT>","",$ox);
	$cox=vClean($ox);
	$jql="$('#bossid').html('$cox');\n";
	echo $jql;

}

function fmChoices(){
	#$fma[0]["fm"]="fmc";
	$fma["fm"]="fmc";
	#select distinct(firstname) from contact where role='sitefm'
	return $fma;
}

function customCron(){
		$this->taskAlerter();
}

function taskAlerter(){
	$_SESSION["ttog"]=!$_SESSION["ttog"];
	if($_SESSION["ttog"]){
		$x="$('#taskAlertDue').css('display','block');";
		$this->jcallA[]=$x;
		$x="$('#taskCountDue').css('display','block');";
		$this->jcallA[]=$x;

		$duec=$this->overDueTaskCount('followupdate');
		$remindc=$this->overDueTaskCount('reminder');
		#echo "alert('due:$duec remind: $remindc')\n";
		$x="$('#taskCount').html('$odc open tasks');";
		$this->jcallA[]=$x;
		#$remindText="<a href=#buffer('taskList');>$remindc task reminders</a>";
		#$overdueText="<a href=#javascript:ibuffer('taskList');>$duec tasks overdue</a>";

		$remindText="$remindc task reminders";
		$overdueText="$duec tasks overdue";

		if($remindc>=1){
			$x="$('#taskAlertRemind').css('display','block');";
			$this->jcallA[]=$x;
			$x="$('#taskCountRemind').css('display','block').css('cursor','pointer')";
			$this->jcallA[]=$x;
			$x="$('#taskCountRemind').html('$remindText');";
			$this->jcallA[]=$x;
			$jc="$('#taskCountRemind').click(function(){ibuffer('taskList')})";
			$this->jcallA[]=$jc;
			}else{
			$x="$('#taskAlertRemind').css('display','none');";
			$this->jcallA[]=$x;
			$x="$('#taskCountRemind').css('display','none');";
			$this->jcallA[]=$x;
		}

		if($duec>=1){
			#echo "alert('yep due:$duec ')\n";
			$x="$('#taskAlertDue').css('display','block');";
			$this->jcallA[]=$x;
			$x="$('#taskCountDue').css('display','block').css('cursor','pointer');";
			$this->jcallA[]=$x;
			$x="$('#taskCountDue').html('$overdueText');";
			$this->jcallA[]=$x;
			$jc="$('#taskCountDue').click(function(){ibuffer('taskList')})";
			$this->jcallA[]=$jc;

			}else{
			$x="$('#taskAlertDue').css('display','none');";
			$this->jcallA[]=$x;
			$x="$('#taskCountDue').css('display','none');";
			$this->jcallA[]=$x;
		}
	}
	$this->ibuffCalls();
	#echo $x;
}

function jobImages($t,$jobid){
$jcx="
				$(\"area[rel^='prettyPhoto']\").prettyPhoto();
				$(\".gallery:first a[rel^='prettyPhoto']\").prettyPhoto({animation_speed:'fast',social_tools:false,theme:'light_square',slideshow:false});
				$(\".gallery:gt(0) a[rel^='prettyPhoto']\").prettyPhoto({animation_speed:'fast',social_tools:false,slideshow:false, hideflash: true});
";
#$this->jcallA[]=$jcx;

	###-show images
	$sqlx="select * from document where xreftable='jobs' and xrefid=$jobid
	and (docformat='jpg' or docformat='gif' or docformat='png')
	order by doctype,documentid desc";
	$tf=kda("documentid,docformat,doctype,dateadded,userid,documentdesc,docname,sender");
	$da=iqa($sqlx,$tf);
	if(isset($da)){
	foreach($da as $row){
			$img=$row["sender"];
			$id=$row["documentid"];
			$ext=$row["docformat"];
			$desc=$row["documentdesc"];
			$docname=$row["docname"];
			$doctype=$row["doctype"];
			$flname=$id.".".$ext;

			if($titledoc=='' || $doctype!=$titledoc){
			$titledoc=$doctype;
			$gallx.='<li style="display:inline;">
			<a href=\"../../infomaniacDocs/jobdocs/'.$flname.'" rel="prettyPhoto[gallery2]" title="'.$desc.' ">
			<img src="../../infomaniacDocs/jobdocs/'.$flname.'" width="60" height="60" alt="'.$docname.'" /></a></li>';
			}
	}
	}
	$gx='<div id="gmain"><h3>Job Images</h3>
			<ul class="gallery clearfix" >
			'.$gallx.'
			</ul>
	</div>';
	$this->elx.=vClean($gx);



}


function showTestImages($t,$id){
	$this->jobImages($t,$id);
	return;
	$x="hey";
$jcx="
				$(\"area[rel^='prettyPhoto']\").prettyPhoto();
				$(\".gallery:first a[rel^='prettyPhoto']\").prettyPhoto({animation_speed:'normal',theme:'light_square',slideshow:3000, autoplay_slideshow: true});
				$(\".gallery:gt(0) a[rel^='prettyPhoto']\").prettyPhoto({animation_speed:'fast',slideshow:10000, hideflash: true});
";

$this->jcallA[]=$jcx;


$ix='
		<div id="gmain">
			<h2>Gallery 2</h2>
			<ul class="gallery clearfix" >
				<li style="display:inline;"><a href=\"../../infomaniacDocs/jobdocs/2310.jpg" rel="prettyPhoto[gallery2]" title="image desc "><img src="../../infomaniacDocs/jobdocs/2310.jpg" width="60" height="60" alt="This is a pretty long title" /></a></li>
				<li style="display:inline;"><a href=\"../../infomaniacDocs/jobdocs/9619.jpg" rel="prettyPhoto[gallery2]" title="image desc "><img src="../../infomaniacDocs/jobdocs/9619.jpg" width="60" height="60" alt="This is a pretty long title" /></a></li>
				<li style="display:inline;"><a href=\"images/fullscreen/5.jpg" rel="prettyPhoto[gallery2]"><img src="images/thumbnails/t_5.jpg" width="60" height="60" alt="" /></a></li>
				<li style="display:inline;"><a href=\"images/fullscreen/1.jpg" rel="prettyPhoto[gallery2]"><img src="images/thumbnails/t_1.jpg" width="60" height="60" alt="" /></a></li>
				<li style="display:inline;"><a href=\"images/fullscreen/2.jpg" rel="prettyPhoto[gallery2]"><img src="images/thumbnails/t_2.jpg" width="60" height="60" alt="" /></a></li>
			</ul>

	</div>';

#echo $ix;

	$this->elx.=vClean($ix);
	#$this->jcallA[]="alert('okp')";;

	#return $x;
}


function poInvSender($bstr){
		$invA=urldecode($invA);
		$mailA=urldecode($mailA);
		$eA=formCruncher($bstr);
		$recip=$eA["recip"];
		$mbody=urldecode($eA["notes"]);
		$invX=$eA["invs"];
		$mailmode=$eA["mailmode"];
		switch($mailmode){
			case "sesp":
			$ixa=formCruncherMultiLine($invX,"pdf_name","!A!","!eq!");

			$ia=aFV($ixa,"pdf_name");
			foreach($ia as $k=>$v) $iaxx.=" * $k = $v";

			break;
			default:
			$ixa=formCruncherMultiLine($invX,"invoiceno","!A!","!eq!");
			$ia=aFV($ixa,"invoiceno");
			break;
		}

		$subj=urldecode($eA["subject"]);


		tfw("aix.txt","$mailmode ss $bstr then $iaxx",true);
		tfw("pobx.txt","hey $bstr $invA to $mailA",true);
		tfw("pobxs.txt","hey $recip subj $subj $mbody $invX",true);

		$this->eSubject=$subj;
		$this->ebodyText=nl2br($mbody);

		$mode="single";
		#emailA
		#invoiceno
		#$recip="tcbflash77@gmail.com";
		foreach($ia as $i=>$invno){
			$invData[$i]["invoiceno"]=$invno;
			$invData[$i]["emailA"]=$recip;
		}

		switch($mailmode){
			case "mesp":
			$recipX=$eA["recipList"];
			$invx=$eA["invoiceno"];
			$rA=formCruncherMultiLine($recipX,"email","!A!","!eq!");
			$recipa=aFV($rA,"email");

			#$subj ##
			$this->eSubject=str_replace("##",$invx,$this->eSubject);
			$this->ebodyText=str_replace("##",$invx,$this->ebodyText);

			foreach($recipa as $email){
				unset($invData);
				$invData[0]["invoiceno"]=$eA["invoiceno"];
				$invData[0]["emailA"]=$email;
				$mxx.=" sending $invx to $email";
				$this->buildAttachFromInvnos($invData,$mailmode);
				#$email="tcbflash77@gmail.com";
				if($this->invoiceSendBulk($mode,$pdfn,$email,$this->attA)){
					$respA[]=" Sent OK to $email";
				}else{
					$respA[]=" $email failed, response: $this->mailFail";
				}
			}
			tfw("mesp.txt","m $recipX innn $invx so $mxx",true);

			$respx=implode("<br>",$respA);
			tfw("mespResp.txt","mresp $respx",true);
			echo $respx;
			break;

			default:
			#sesp & semp
			$this->buildAttachFromInvnos($invData,$mailmode);
			if($this->invoiceSendBulk($mode,$pdfn,$recip,$this->attA)){
				echo "Sent OK to $recip";
			}else{
				echo "Failed, response: $this->mailFail";
			}
			break;
		}

}

function tmakePdfPack(){
	tfw("attsomake.txt","got ",true);
	passthru("$this->wkpath/wkhtmltopdf --margin-top 1mm ../../infomaniacDocs/jrq/jrq_24241a.html  ../../infomaniacDocs/jrq/jrq_24241a.pdf");
	passthru("$this->wkpath/wkhtmltopdf --margin-top 1mm ../../dcfm07/ii/batchr_Quotes3.html ../../dcfm07/ii/tpdf4.pdf");
	passthru("$this->wkpath/wkhtmltopdf --margin-top 1mm ../../infomaniacDocs/jrq/batchr_Quotes3.html ../../infomaniacDocs/jrq/tpdf3.pdf");

}



function makePdfPack($pkA){
	$this->pdfSettings();
	$uid=$_SESSION["userid"];
	foreach($pkA as $rn){
		$fn="../../dcfm07/ii/batchr_".$uid."_$rn.html";
		$pa[]=$fn;
	}
	$rax=implode(" ",$pa);
	$pdf_fn="reportpack_".$uid.".pdf";

	#delete existing pack
	$delPath="$this->clientpath/ii/$pdf_fn";
	unlink($delPath);
	#return;//testing the delete


	tfw("asomake.txt","got user $uid so $rax for $pdf_fn",true);
	#batchr_Quotes3.html
	#passthru("$this->wkpath/wkhtmltopdf --margin-top 1mm batchr_Quotes3.html tpdf.pdf");
	#popen("$this->wkpath/wkhtmltopdf --margin-top 1mm batchr_Quotes3.html tpdf2.pdf","r");
	#passthru("../../infomaniacDocs/jrq/jrq_24241a.html  ../../infomaniacDocs/jrq/jrq_24241a.pdf");

$varid="pdf_footer_".$_SESSION["userid"];
	$px=sysvalue($varid);
	$footA=formCruncher($px,"!A!","*E*");
	elog("12368 foot settings: $px");
	if($footA["incfooter"]){
	 $align=$footA["alignment"];
	 $textleft=$footA["lefttext"];
	 $textright=$footA["righttext"];
	 $pn=$footA["pagenumber"];
	 $tpn=$footA["totalpagenumber"];
	 $showdate=$footA["showdate"];
	 $lineabove=$footA["lineabove"];
	 if($pn) $pageC="Page [page]";
	 if($tpn) $tpageC="[topage]";
	 if(($pn)&($tpn)) $pof="/";
	 if($showdate) $datex=date("d/M/Y");
	 if($lineabove) $footLine="--footer-line";

	 #$footx=" --footer-$align '$textleft $datex $pageC $pof $tpageC $textright' $footLine";
	 #$footx=" --footer-left [page] / [topage]";
	}

	if($this->usePath){
			#$pdf_fn="../../infomaniacDocs/arstatements/statement_pdf";

			#passthru("$this->wkpath/wkhtmltopdf --margin-top 1mm $rax $pdf_fn");
			#popen("$this->wkpath/wkhtmltopdf --margin-top 1mm $rax $pdf_fn","r");
			passthru("$this->wkpath/wkhtmltopdf --margin-top 1mm $footx $rax ../../dcfm07/ii/reportoutput/$pdf_fn");

			tfw("pfnsing.txt","ff wkp $this->wkpath fulln $rax $pdf_fn",true);


			$pfx.="<br>yes wk path";
	}else{
			ptfw("pfnsingNo.txt","ff $fullfn $supportingDocx $pdf_fn",true);
			$pfx.="<br>no path";
			#passthru("wkhtmltopdf --margin-top 1mm $rax $pdf_fn");
			passthru("wkhtmltopdf $footx --margin-top 1mm $rax ../../dcfm07/ii/reportoutput/$pdf_fn");
			#passthru("wkhtmltopdf $footx $rax ../../dcfm07/ii/$pdf_fn");

	}



}

function makeHtmlPack($pkA){
	$this->pdfSettings();
	$uid=$_SESSION["userid"];
	foreach($pkA as $rn){
		$fn="../ii/batchr_".$uid."_$rn.html";
		$pa[]=$fn;
		$fx.="include_once('$fn');";
	}
	$pfx="<?".$fx."?>";
	ptfw("mhp.html",$pfx,true);
	echo "mhp.html";


}



function customAjlDateReactions($rn,$params,$tdiv){
	 # echo "alert('ok ajl dt $rn')\n";
	  $tdiv="main-content";
	  switch($rn){
	   case "runCharts";
	   $tdiv=$tdiv!=""?$tdiv:"";
	   #echo "alert('redo $xparams $tdiv')\n";
	   $this->returnNow=true;
	   echo "ajl('runCharts','$params','$tdiv')\n";
	   break;

	   case "invGP";
	   $tdiv=$tdiv!=""?$tdiv:"";
	   #echo "alert('redo $xparams $tdiv')\n";
	   $this->returnNow=true;
	   echo "ajl('invGP','$params','$tdiv')\n";
	   break;

	   default:
	   #cant really use this - regular date reports will break;
	   $tdiv=$tdiv!=""?$tdiv:"";
	   #echo "alert('redo $xparams $tdiv')\n";
	   #echo "ajl('$rn','$params','$tdiv')\n";
	   break;
	  }
}

function transferRelated($jobid,$newid){
	$q1="update rfq set jobid=$newid where jobid=$jobid";
	mysql_query($q1);
	$q2="update purchaseorders set jobid=$newid where jobid=$jobid";
	mysql_query($q2);

		$nta["jobid"]=$jobid;
		$nta["notes"]="RFQ & JRQ Documents transferred to sub job $newid";
		$nta["date"]=date("Y-m-d");
		$nta["timestamp"]=date("Y-m-d h:i");
		$nta["notetype"]="internal";
		$nta["ntype"]="doctransfer";
		$nta["userid"]=$_SESSION["userid"];
		$jnid=pai("jobnote",$nta);

}


function customerDelUI($custid=null)
	{
		$userid = $_SESSION["userid"];
		$qs = "SELECT count(*) as usercount FROM usersecurity us INNER JOIN usersecurityfunction usf ON us.functionid=usf.functionid
		WHERE functionname = 'delete_customer' AND hasaccess=1 AND userid = '$userid'";
		$tfs = kda("usercount");
		$das = iqa($qs,$tfs);
		
		$hasaccess = 0;
		
		if(isset($das)) {
			foreach($das as $i=>$row){
				$hasaccess = $row['usercount'];
			}
		}		
		
		if ($hasaccess){
			$jcall="$('#custdel').click(function(){
			alert('You are allowed to delete accounts.');
			//custDelUI('customer','customerid',$custid);
			
			var custid = $('#primid').val();
			console.log('soft delete ' + custid);

			$.ajax({
				url: '../../../common/phoenix/bl/ajax/ajax-customer.php',
				global: false,
				type: 'POST',
				data: 'mode=deletecustomer&custid='+custid,
				dataType: 'text',
				async:false,
		        success: function(msg){
					alert('Customer status changed to Deleted. ');
			    }
			 });   
			});";
		} 
		else {
			$jcall="$('#custdel').click(function(){
			alert('You are not allowed to delete accounts.');
			return;
			//var cid=$('#primid').val();
			custDelUI('customer','customerid',$custid);
			})";
		}
	//$jcall="alert('a test');";
	$this->jcallA[]=$jcall;

	$jcd='function custDelUI(tid,idn,lix){
	//alert(tid+" "+idn+" "+lix);
	var res;
	var jobs;
		res = $.ajax({


					 url: "checkUserDetail.php",

			      global: true,
			      type: "POST",
			   data: "custid="+lix,
			      dataType: "html",
			      async:false,
			      success: function(msg){


			      }
			   }
			).responseText;

			jobs = $.ajax({


					 url: "checkUserDetail.php",

			      global: true,
			      type: "POST",
			   data: "custid="+lix+"&type=jobs",
			      dataType: "html",
			      async:false,
			      success: function(msg){


			      }
			   }
			).responseText;


	jobs=$.trim(jobs);
	res=$.trim(res);
	document.getElementById("dialogDelete").innerHTML=res;
	if(jobs==0)
	{
			$( "#dialog:ui-dialog" ).dialog( "destroy" );

		$( "#dialogDelete" ).dialog({
			modal: true,
			height:500,
			width:600,
			buttons: {
				Delete: function() {
				ajx.send("masterdelete",tid,idn,lix);
					$( this ).dialog( "close" );
				},
					Cancel: function() {


					$( this ).dialog( "close" );
				}
			}
		});
	}

	else
		{

				$( "#dialog:ui-dialog" ).dialog( "destroy" );

		$( "#dialogDelete" ).dialog({
			modal: true,
			height:500,
			width:600,
			buttons: {

					Cancel: function() {


					$( this ).dialog( "close" );
				}
			}
		});

		}

	}';
	$this->jcallA[]=$jcd;

}


function quickJobNote($jobid,$ntype,$jobnoteid=null,$via="jq"){
	#return;
	#echo "alert('j $jobid n $ntype')\n";
	$this->taskElements($jobid);

	$pc=new iForm();
	$tf=kda("jobid,userid,date,notetype,notes");
	   	$pc->flabel=key2val($tf);
	   	#gen $pc->ftypes
	   	#$pc->allText($tf);
	   	#then override by exception.
	   	$pc->altFtypes["jobid"]="hidden";
	   	$pc->altFtypes["jobnoteid"]="hidden";
	   	$pc->altFtypes["primaryval"]="hidden";
	   	$pc->altFtypes["notes"]="textarea";
	   	$pc->altFtypes["notetype"]="customListRadio";

	   	//$pc->altFtypes["ntype"]="xhidden";
	   	$pc->altFtypes["ntype"]="customList";
	   	$pc->excludeVal["ntype"]=kda("del");
	   	$pc->altFtypes["date"]="date_ClickCal";
	   	#$pc->altFtypes["extenduntil"]="date_ClickCal";
	   	$pc->altFtypes["via"]="hidden";
	   	$pc->fsize["date"]=12;
	   	#$pc->fsize["extenduntil"]=12;
	   	$pc->defaultF["primaryval"]="jobnoteid";
		$jnid=$this->paramsA["noteid"]!=""?$this->paramsA["noteid"]:$this->paramsA["jobnoteid"];
		if($jnid>0){
			$jna=iqatr("jobnote","jobnoteid",$jnid);
			$pc->defaultF=$jna;;
		}


	   	$pc->defaultF["via"]=$via;
	   	$pc->defaultF["ntype"]=$ntype;


		$pc->valtable["ntype"]="jobnotetype";

		if($ntype=="extend"){
			$tf["reason"]="Reason";
		   	$pc->altFtypes["reason"]="customList";
		   	$pc->valtable["reason"]="extendnote";
		}

	   	#set up positional arrays
		$new_fa=array_keys($pc->flabel);
		foreach($new_fa as $i=>$fn){
			$new_ft[$i]=$pc->altFtypes[$fn];
		}
		$posByName=array_flip(array_values($new_fa));
		$pc->row[]=kda("primaryval");
		$pc->row[]=kda("jobnoteid");
		$pc->row[]=kda("jobid");
		$pc->row[]=kda("userid");
		$pc->row[]=kda("date");
		$pc->row[]=kda("ntype");
		$pc->row[]=kda("notelength");
		$pc->altFtypes["notelength"]="hidden";
		$pc->row[]=kda("via");
		if($ntype=="extend"){
			#$pc->row[]=kda("extenduntil");
			#$pc->defaultF["extenduntil"]=$this->paramsA["duedate"];
			$pc->row[]=kda("reason");
		}
		$pc->row[]=kda("notetype");
		$pc->row[]=kda("notes");

		/*
		foreach($row as $rowNo=>$rowd){
			foreach($rowd as $colPos=>$fname){
				$apos=$posByName[$fname];
				$new_rowpos[$apos]=$rowNo;
				$new_colpos[$apos]=$colPos;
			}
		}
		*/

		$pc->defaults["userid"]=$_SESSION["userid"];
		$pc->defaults["date"]=date("Y-m-d");
		$pc->defaults["jobid"]=$jobid;
		$pc->defaults["notetype"]="internal";

		if($ntype=="complete"){
			$pc->defaults["notetype"]="completion";
			$pc->defaults["ntype"]="complete";
		}

#	   	$pc->formfields($new_fa,$new_ft,$pc->defaults,$oa,$pc->compulsorya,$numerica,$new_rowpos,$new_colpos,$jcalla);

	$jcall="onclick=saveJobNote(\'$ntype\')";
	//echo "alert('$ntype')\n";
	switch($ntype)	{
		case "del":
		$tit="Reasons for deletion";
		$blab="Delete Job";
	   	$pc->altFtypes["ntype"]="ro_text";
		$pc->defaults["ntype"]="del";
		break;
		case "deljrq":
		$tit="Reasons for deletion";
		$blab="Delete JRQ";
		$jrq=$this->paramsA["jrq"];
		$sqlx="select c.companyname,p.supplierid,p.podate from purchaseorders as p
		left outer join customer as c on p.supplierid=c.customerid
		where poref=$jrq";
		$tf=kda("companyname,poref,podate");
		$pa=iqasra($sqlx,$tf);
		$podate=$pa["podate"];
		$cname=$pa["companyname"];
	   	$pc->altFtypes["ntype"]="ro_text";
		$pc->defaults["ntype"]="deljrq";
		$deld=date("d/m/Y");
		$nx="Deleted JRQ No:$jrq $cname $podate";
		$nx.="\nReasons for deletion:";
		$pc->defaults["notes"]=$nx;

		break;

		case "can":
		$tit="Reasons for cancellation";
		$blab="Cancel Job";
	   	$pc->altFtypes["ntype"]="ro_text";
		$pc->defaults["ntype"]="cancel";
		break;
		case "hold":
		$tit="Reasons for holding";
		$blab="Hold Job";
	   	$pc->altFtypes["ntype"]="ro_text";
		$pc->defaults["ntype"]="hold";
		break;
		case "nobill":
		$tit="Reasons for not charging";
		$blab="Flag Un-Chargeable";
	   	$pc->altFtypes["ntype"]="ro_text";
		$pc->defaults["ntype"]="nobill";
		break;
		case "extend":
		$tit="Reasons for Extension";
		$blab="Request Extension";
		$ja=iqatr("jobs","jobid",$jobid);
		$cname=$ja["siteline1"];
		$ssub=$ja["sitesuburb"];
		$xdt=$ja["extenduntil"];
		#$xdt=singlevalue("jobs","extenduntil","jobid",$jobid);
		$extd=date("d/m/Y",strtotime($xdt));
		$nx="$this->owner Job No:$jobid \n$cname ($ssub)";
		$nx.="\nReasons for extension request ";
		if($xdt>0) $nx.="to  $extd:";
		$pc->defaults["notes"]=$nx;
	   	$pc->altFtypes["ntype"]="ro_text";
		$pc->defaults["ntype"]="extend";
		break;

		case "close":
		$tit="Closing Notes";
		$blab="Closing ";
	   	$pc->altFtypes["ntype"]="ro_text";
		$pc->defaults["ntype"]="close";
		break;

		case "complete":
			$tit="Job Completion Note Entry";
			$blab="OK";
			$ja=iqatr("jobs","jobid",$jobid);
			$cname=$ja["siteline1"];
			$ssub=$ja["sitesuburb"];
			$custsuburb = $cname." ($ssub)";
			$customerPO = "Your Order: ".$ja['custordref'];	
			$arrival = "Job commenced: ".$ja['jrespdate']." ".$ja['jresptime'];	
			$completion = "Job completed: ".$ja['jcompletedate'];	
			$nx = $customerPO."\n"."DCFM Job No: $jobid"."\n \n".($custsuburb)."\n".
			$arrival."\n".
			$completion."\n"."\n".
			"Description of completed works:";
			
	
			
			//$nx="$this->owner Job No:$jobid \n$cname ($ssub)";
			$pc->defaults["notes"]=$nx;	
			$pc->defaults["notelength"] = strlen($nx);

		break;
		
		default:
		$tit="Job Note Entry";
		$blab="OK";
		$ja=iqatr("jobs","jobid",$jobid);
		$cname=$ja["siteline1"];
		$ssub=$ja["sitesuburb"];
		$xdt=$ja["extenduntil"];
		#$xdt=singlevalue("jobs","extenduntil","jobid",$jobid);
		$extd=date("d/m/Y",strtotime($xdt));
		$nx="$this->owner Job No:$jobid \n$cname ($ssub)";
		$pc->defaults["notes"]=$nx;

		break;
	}
	$this->dialogTitle=$tit;

	$pc->row[]=kda("taskrequired");
	$pc->altFtypes["taskrequired"]="checkbox";
	$pc->flabel["taskrequired"]="Task Required?";

	$pc->formGen();
   	#$pc->formfields($new_fa,$new_ft,$pc->defaults,$oa,$pc->compulsorya,$numerica,$new_rowpos,$new_colpos,$jcalla);
	#$tpx="<input type=xhidden name=ntype value=$ntype>";
	$saveButtx="<tr><td>$tpx<input type=\"button\" value=\"$blab\" $jcall></td></tr>";
   	#$cx=vClean($pc->formtext);
   	$cx=$pc->formtext;
   	$tx="<form id='jobnote' name='jobnote' method='post'><table>$cx $saveButtx</table></form>";
	#$tx.="job notes here?";
	tfw("jnx.txt",$tx,true);

	//$this->logIt("cx ".$cx);
	
	if($this->ui){
		#ui modal version
	   	$ux="<br><form id='jobnoteF' name='jobnote' method='post'><table>$cx</table></form>";

		$ux=str_replace('\n','*linebreak*', $ux);
		$ux=str_replace('<br />','*linebreak*', $ux);
		$ux=str_replace('*linebreak*',"".chr(13)."".chr(10)."", $ux);

		#$ux="<br>fnotes";
		elog("noteform14419 $ux");
		$ux.=$this->taskForm;

		return $ux;
	}

	#echo "g('layer1_title').innerHTML ='$tit - <b><i>$kdesc</i></b>';\n";
	#echo "g('layer1_content').innerHTML ='$tx';\n";
//	tfw("ccont.txt","cf $cx",true);

}



function quickJobNote_x($jobid,$ntype,$jobnoteid=null,$via="jq"){
	#return;
	#echo "alert('j $jobid n $ntype')\n";
	$pc=new iForm();
	$tf=kda("jobid,userid,date,notetype,notes");
	   	$pc->flabel=key2val($tf);
	   	#gen $pc->ftypes
	   	#$pc->allText($tf);
	   	#then override by exception.
	   	$pc->altFtypes["jobid"]="hidden";
	   	$pc->altFtypes["jobnoteid"]="hidden";
	   	$pc->altFtypes["primaryval"]="hidden";
	   	$pc->altFtypes["notes"]="textarea";
	   	$pc->altFtypes["notetype"]="customListRadio";

	   	//$pc->altFtypes["ntype"]="xhidden";
	   	$pc->altFtypes["ntype"]="customList";
	   	$pc->excludeVal["ntype"]=kda("del");
	   	$pc->altFtypes["date"]="date_ClickCal";
	   	#$pc->altFtypes["extenduntil"]="date_ClickCal";
	   	$pc->altFtypes["via"]="hidden";
	   	$pc->fsize["date"]=12;
	   	#$pc->fsize["extenduntil"]=12;
	   	$pc->defaultF["primaryval"]="jobnoteid";
		$jnid=$this->paramsA["noteid"]!=""?$this->paramsA["noteid"]:$this->paramsA["jobnoteid"];
		if($jnid>0){
			$jna=iqatr("jobnote","jobnoteid",$jnid);
			$pc->defaultF=$jna;;
		}


	   	$pc->defaultF["via"]=$via;
	   	$pc->defaultF["ntype"]=$ntype;


		$pc->valtable["ntype"]="jobnotetype";

		if($ntype=="extend"){
			$tf["reason"]="Reason";
		   	$pc->altFtypes["reason"]="customList";
		   	$pc->valtable["reason"]="extendnote";
		}

	   	#set up positional arrays
		$new_fa=array_keys($pc->flabel);
		foreach($new_fa as $i=>$fn){
			$new_ft[$i]=$pc->altFtypes[$fn];
		}
		$posByName=array_flip(array_values($new_fa));
		$pc->row[]=kda("primaryval");
		$pc->row[]=kda("jobnoteid");
		$pc->row[]=kda("jobid");
		$pc->row[]=kda("userid");
		$pc->row[]=kda("date");
		$pc->row[]=kda("ntype");
		$pc->row[]=kda("via");
		if($ntype=="extend"){
			#$pc->row[]=kda("extenduntil");
			#$pc->defaultF["extenduntil"]=$this->paramsA["duedate"];
			$pc->row[]=kda("reason");
		}
		$pc->row[]=kda("notetype");
		$pc->row[]=kda("notes");

		/*
		foreach($row as $rowNo=>$rowd){
			foreach($rowd as $colPos=>$fname){
				$apos=$posByName[$fname];
				$new_rowpos[$apos]=$rowNo;
				$new_colpos[$apos]=$colPos;
			}
		}
		*/

		$pc->defaults["userid"]=$_SESSION["userid"];
		$pc->defaults["date"]=date("Y-m-d");
		$pc->defaults["jobid"]=$jobid;
		$pc->defaults["notetype"]="internal";

		if($ntype=="complete"){
			$pc->defaults["notetype"]="completion";
			$pc->defaults["ntype"]="complete";
		}

#	   	$pc->formfields($new_fa,$new_ft,$pc->defaults,$oa,$pc->compulsorya,$numerica,$new_rowpos,$new_colpos,$jcalla);

	$jcall="onclick=saveJobNote(\'$ntype\')";
	//echo "alert('$ntype')\n";
	switch($ntype)	{
		case "del":
		$tit="Reasons for deletion";
		$blab="Delete Job";
	   	$pc->altFtypes["ntype"]="ro_text";
		$pc->defaults["ntype"]="del";
		break;
		case "deljrq":
		$tit="Reasons for deletion";
		$blab="Delete JRQ";
		$jrq=$this->paramsA["jrq"];
		$sqlx="select c.companyname,p.supplierid,p.podate from purchaseorders as p
		left outer join customer as c on p.supplierid=c.customerid
		where poref=$jrq";
		$tf=kda("companyname,poref,podate");
		$pa=iqasra($sqlx,$tf);
		$podate=$pa["podate"];
		$cname=$pa["companyname"];
	   	$pc->altFtypes["ntype"]="ro_text";
		$pc->defaults["ntype"]="deljrq";
		$deld=date("d/m/Y");
		$nx="Deleted JRQ No:$jrq $cname $podate";
		$nx.="\nReasons for deletion:";
		$pc->defaults["notes"]=$nx;

		break;

		case "can":
		$tit="Reasons for cancellation";
		$blab="Cancel Job";
	   	$pc->altFtypes["ntype"]="ro_text";
		$pc->defaults["ntype"]="cancel";
		break;
		case "hold":
		$tit="Reasons for holding";
		$blab="Hold Job";
	   	$pc->altFtypes["ntype"]="ro_text";
		$pc->defaults["ntype"]="hold";
		break;
		case "nobill":
		$tit="Reasons for not charging";
		$blab="Flag Un-Chargeable";
	   	$pc->altFtypes["ntype"]="ro_text";
		$pc->defaults["ntype"]="nobill";
		break;
		case "extend":
		$tit="Reasons for Extension";
		$blab="Request Extension";
		$ja=iqatr("jobs","jobid",$jobid);
		$cname=$ja["siteline1"];
		$ssub=$ja["sitesuburb"];
		$xdt=$ja["extenduntil"];
		#$xdt=singlevalue("jobs","extenduntil","jobid",$jobid);
		$extd=date("d/m/Y",strtotime($xdt));
		$nx="$this->owner Job No:$jobid \n$cname ($ssub)";
		$nx.="\nReasons for extension request ";
		if($xdt>0) $nx.="to  $extd:";
		$pc->defaults["notes"]=$nx;
	   	$pc->altFtypes["ntype"]="ro_text";
		$pc->defaults["ntype"]="extend";
		break;

		case "close":
		$tit="Closing Notes";
		$blab="Closing ";
	   	$pc->altFtypes["ntype"]="ro_text";
		$pc->defaults["ntype"]="close";
		break;


		default:
		$tit="Job Note Entry";
		$blab="OK";
		$ja=iqatr("jobs","jobid",$jobid);
		$cname=$ja["siteline1"];
		$ssub=$ja["sitesuburb"];
		$xdt=$ja["extenduntil"];
		#$xdt=singlevalue("jobs","extenduntil","jobid",$jobid);
		$extd=date("d/m/Y",strtotime($xdt));
		$nx="$this->owner Job No:$jobid \n$cname ($ssub)";
		$pc->defaults["notes"]=$nx;

		break;
	}
	$this->dialogTitle=$tit;

	$pc->formGen();
   	#$pc->formfields($new_fa,$new_ft,$pc->defaults,$oa,$pc->compulsorya,$numerica,$new_rowpos,$new_colpos,$jcalla);
	#$tpx="<input type=xhidden name=ntype value=$ntype>";
	$saveButtx="<tr><td>$tpx<input type=\"button\" value=\"$blab\" $jcall></td></tr>";
   	#$cx=vClean($pc->formtext);
   	$cx=$pc->formtext;
   	$tx="<form id='jobnote' name='jobnote' method='post'><table>$cx $saveButtx</table></form>";
	#$tx.="job notes here?";
	tfw("jnx.txt",$tx,true);
	if($this->ui){
		#ui modal version
	   	$ux="<br><form id='jobnoteF' name='jobnote' method='post'><table>$cx</table></form>";

		$ux=str_replace('\n','*linebreak*', $ux);
		$ux=str_replace('<br />','*linebreak*', $ux);
		$ux=str_replace('*linebreak*',"".chr(13)."".chr(10)."", $ux);

		#$ux="<br>fnotes";
		return $ux;
	}

	#echo "g('layer1_title').innerHTML ='$tit - <b><i>$kdesc</i></b>';\n";
	#echo "g('layer1_content').innerHTML ='$tx';\n";
//	tfw("ccont.txt","cf $cx",true);

}

function getAllSubordinates($bossid,$sub=null,$oldarray=null)	{
	#$sql="select * from contact where bossid=".$bossid." and (role='sitefm' and email!='' or role='dummy') ";
	$sql="select * from contact where bossid=".$bossid." and (role='sitefm' and email!='' or role='dummy' or role='') ";
	#echo $sql;
	$query=mysql_query($sql);

	if(mysql_num_rows($query)>0)	{
		while($row=mysql_fetch_array($query))	{
			$names[$row['firstname']]=$row['contactid'];
			##preload
			$cid=$row['email'];
			#echo "<br>$cid";
			if($cid!="") $this->includeA[]=$cid;

			if($sub==1){
				$names1.="&nbsp;&nbsp;&nbsp;   -  ".$row['firstname']." || ".$row['contactid']." || ".$row['email']."|||";
				}else{
				$names1.=$row['firstname']." || ".$row['contactid']." || ".$row['email']."|||";
			}

			//recursion
			$names1.=$this->getAllSubordinates($row['contactid'],1);
		}//end of while

		}//end of id

}

function unbillableDelJrq($jobstage){
	include_once "$this->clientpath/customscript/jobQuery.php";
   	$jq=new jobQuery;
/*	$jq->userTerritorySetup();
	$jq->userFMSetup();
	$jq->buildOpenJrq();
	$jq->unbilledGridCounter();
*/
#deljrqcost is temp table
	 $jq->deletedJrqCostBuild();

	$cq="select j.jobid,j.siteline1,j.leaddate,j.duedate,j.jobstage,j.jcompletedate from deljrqcost as d
	left outer join jobs as j
	on d.jobid=j.jobid
	where j.jobstage= '$jobstage'";
	$tf=kda("jobid,siteline1,leaddate,duedate,jobstage,jcompletedate");
	$ca=iqa($cq,$tf);
	$szc=sizeof($ca);

	$ist=new divTable();
	elog($cq);
	$ist->rTitle="Unbilled Job Report $cq";
	$ist->datefields=kda("leaddate,jcompletedate,duedate");

	$ist->highlighter=true;
	#$ist->highlightFname="jobviewInParent";
	$ist->highlightFname="jobviewNW";
	$ist->rowID=kda("jobid");
	#$ist->total_fields=kda("estimatedsell,jobcost,projmargin,tcommit");


	$nx=$ist->inf_sortable($tf,$ca,"Basic Detail Report ");
	#$cx.=vClean($condx);
	$cx.=vClean($nx);
	#$cx.=vClean($q);
   	#$cx.="got $jc jobs $condx $q";

   	echo "g('detail').innerHTML='$cx'\n";

   	#echo "g('detail').innerHTML='cx6  $szc'\n";

}
function constructMultiRefs($ars=null){
	$invc=implode(",",$ars->arinv["invno"]);
	if(isset($this->arinv)){
		#$invc=implode(",",key2val(array_values($this->arinv)));
		#foreach($this->arinv as $k=>$v) $kvx.="$k $v";
		#slow version
		foreach($this->arinvcust as $invcust){
			$bal=$this->arbal[$invcust];
			if($bal<>0){
				$invno=$this->arinv[$invcust];
				$openInvs[]=$invno;
				$invcustA[$invno]=$invcust;
			}
		}
		$invc=implode(",",$openInvs);

	elog("ok cmr $invc $kvx","invc 12255");
	}
	$q="select i.invoiceno,j.custordref,j.custordref2,j.custordref3,i.esentdate from invoice as i
	left outer join jobs as j
	on i.jobid=j.jobid
	where i.invoiceno in($invc)";
	elog($q,"cmf 255");
	$tf=kda("invoiceno,custordref,custordref2,custordref3,esentdate");
	$da=iqa($q,$tf);
	foreach($da as $row){
		$invoiceno=$row["invoiceno"];
		$c1=$row["custordref"];
		$c2=$row["custordref2"];
		$c3=$row["custordref3"];
		$esentdate=$row["esentdate"];
		unset($cA);
		if($c1!="") $cA[]=$c1;
		if($c2!="") $cA[]=$c2;
		if($c3!="") $cA[]=$c3;
		$cref=implode(" / ",$cA);
		#$ars->custordref[$invoiceno]=$cref;
		$ars->arinv["custordref"][$invoiceno]=$cref;
		$this->arinv["custordref"][$invoiceno]=$cref;
		$invcust=$invcustA[$invoiceno];
		$this->extras["custordref"][$invcust]=$cref;
		$this->arinvEmailSent[$invoiceno]=$esentdate;
		elog("customIntface14639 sentdate $invoiceno $esentdate");

	}
}


function constructMultiRefs_fromJob($jobidA){
	$jx=implode(",",$jobidA);
	$q="select j.jobid,j.custordref,j.custordref2,j.custordref3 from jobs as j
	where j.jobid in($jx)";
	elog($q,"cmf 255");
	$tf=kda("jobid,custordref,custordref2,custordref3");
	$da=iqa($q,$tf);
	foreach($da as $row){
		$jobid=$row["jobid"];
		$c1=$row["custordref"];
		$c2=$row["custordref2"];
		$c3=$row["custordref3"];
		unset($cA);
		if($c1!="") $cA[]=$c1;
		if($c2!="") $cA[]=$c2;
		if($c3!="") $cA[]=$c3;
		$cref=implode(" / ",$cA);
		#$ars->custordref[$invoiceno]=$cref;
		$this->arinv["custordref"][$jobid]=$cref;
		$this->extras["custordref"][$jobid]=$cref;
	}




}


function changeUserPo($user,$newUser,$aid){
	#find jobid,
	# supplierid linked to the old user
	# supplierid linked to the new user
	# find PO linked to job, change to new user
	$newUser=strtolower($newUser);
	$user=strtolower($user);
	$jobid=singlevalue("diary","jobid","apptid",$aid);
	$q="select email,userid from users where userid in ('$user','$newUser');";
	elog("q $q","cif 12616");
	$tf=kda("userid,email");
	$da=iqa($q,$tf);
	foreach($da as $row){
		$supplierid=$row["email"];
		$userid=strtolower($row["userid"]);
		$suppA[$userid]=$supplierid;
		elog("seet $userid as $supplierid","cif 12634");
	}
	$newSupplier=$suppA[$newUser];
	$oldSupplier=$suppA[$user];
	elog("j $jobid user: $user newu: $newUser os $oldSupplier ns $newSupplier");

	$poq="select poref from purchaseorders where apptid=$aid";
	$poref=iqasrf($poq,"poref");
	if($poref>0){
		$ua["supplierid"]=$newSupplier;
		$keyA["jobid"]=$jobid;
		$keyA["poref"]=$poref;
		#$keyA["supplierid"]=$oldSupplier;
		$keyA["apptid"]=$aid;
		$GLOBALS["elogger"]=true;
		pau("purchaseorders",$ua,$keyA);
		#log
		$ea=array(
		"tablename"=>"jobs",
		"recordid"=>$jobid,
		"editdate"=>date("Y-m-d H:i"),
		"userid"=>$_SESSION["userid"],
		"fieldname"=>"JRQ $poref supplier change on user move",
		"oldvalue"=>$user,
		"newvalue"=>$newUser);
		pai("editlog",$ea);

	}else{
		#first time entry
		elog("adding PO first time on move","cif 12651");
		$arow=iqatr("diary","apptid",$aid);
		foreach($arow as $k=>$kv) elog("$k $kv");
		$pa["supplierid"]=$newSupplier;
		$pa["jobid"]=$jobid;
		$pa["apptid"]=$aid;
		$pa["podate"]=$arow["dte"];
		$GLOBALS["elogger"]=true;
		$pa["glchartid"]=$this->regionalGlcode($jobid,"po");
		$pa["userid"]=$_SESSION["userid"];
		$poref=pai("purchaseorders",$pa);
		#log
		$ea=array(
		"tablename"=>"jobs",
		"recordid"=>$jobid,
		"editdate"=>date("Y-m-d H:i"),
		"userid"=>$_SESSION["userid"],
		"fieldname"=>"New JRQ $poref on user move",
		"oldvalue"=>$user,
		"newvalue"=>$newUser);
		pai("editlog",$ea);
		
		$_SESSION["ErrorReport"] = false;
		require_once ($_SERVER['DOCUMENT_ROOT'] . "/common/phoenix/bl/blTechAllocate.class.php");
		$tech = new blTechAllocate(AppType::JobTracker, $_SESSION['userid']);
		$tech -> new_po($aid);
		error_reporting(0);		

	}

}

	function isPowerUser(){
		$userid=$_SESSION["userid"];
		if(in_array($userid,$this->powerUsers)) return true;
		return false;
	}

function posAllRequestedUsers(){
$query="
SELECT uf.userid,uf.firstname,uf.surname,
ur.colleagueid ,uf.userorder
FROM userviewsrequested as ur
right outer join users as uf on
ur.colleagueid=uf.userid
where (ur.userid='".$_SESSION["userid"]."' or
ur.userid='".ucwords($_SESSION["userid"])."' or
ur.userid='".strtolower($_SESSION["userid"])."' )
and (uf.inactive is null or uf.inactive!='on')
order by uf.userorder,uf.userid
";
tfw("mup.txt",$query,true);
elog("paru $query","cifn 12706");
$tf=kda("userid,firstname,surname,userorder");
$da=iqa($query,$tf);
foreach($da as $i=>$row){
    $userid=strtolower($row["userid"]);
    $oap[$userid]=$i-1;
}
//$isa=serialize($oap);
//tfw("szq.txt",$isa,true);
asort($oap);
reset($oap);
$GLOBALS["oap"]=$oap;
//return $oap;


}


function addressExtras($ist=null,$custid=null){
		$n=6;
		$ist->toolA[$n]["label"]="Customer Location budgets";
		$ist->toolA[$n]["jname"]="ajl('custInventoryLocations','!I!custid=$custid!I!mode=location','guts')";
		$ist->toolA[$n]["ttype"]="button";

		$n++;
		$ist->toolA[$n]["label"]="Activity - No Location specified";
		$ist->toolA[$n]["jname"]="ajl('custActivityLocations','!I!custid=$custid!I!mode=location','guts')";
		$ist->toolA[$n]["ttype"]="button";

		$n++;
		$ist->toolA[$n]["label"]="Address Grouping";
		$ist->toolA[$n]["jname"]="ajl('addressGroups','!I!custid=$custid!I!mode=location','guts')";
		$ist->toolA[$n]["ttype"]="button";

}

function checkResendInvoice($jobid,$fn,$val){
	elog("13011check resend j:$jobid f:$fn v:$val");
	$q="select * from invoice where jobid=$jobid and (completed is null or completed!='on')";
	$tf=kda("invoiceno,customerid,netval,invoicedate");
	$da=iqa($q,$tf);
	foreach($da as $row){
		$invoiceno=$row["invoiceno"];
		$date=$row["invoicedate"];
		$netval=$row["netval"];
		$grossval=$row["netval"]*(1+$_SESSION["gstrate"]);
		$customerid=$row["customerid"];
		#make task
		$ta["allocatedto"]="finance";
		$ta["raisedby"]=$_SESSION["userid"];
		$ta["followupdate"]=date("Y-m-d H:i");
		$ta["area"]="finance";
		$ta["urgent"]="on";
		$ta["jobid"]=$jobid;
		$ta["customerid"]=$customerid;
		$ta["source"]="job edit trigger";
		$ta["subject"]="Re-send invoice";
		$ta["tdate"]=date("Y-m-d H:i");
		$ta["pdate"]=date("Y-m-d H:i");
		$ta["detail"]="New customer order reference $fn : $val on jobid: $jobid. Affects unpaid invoice $invoiceno ($ $grossval). Next action should be re-send invoice.";
		pai("task",$ta);
	}
}


function custArPrefs($custid){
	#
	$settings=singlevalue("customer","settings","customerid",$custid);
	$sa=unserialize($settings);
	$savedMeth=$sa["pdfInvPref"];
	if(trim($savedMeth=="")) $savedMeth="multiPDF";
	$mailmode=$savedMeth;

	$statdelA=explode(",",$sa["statDocs"]);
	elog("14423checking $settings for custid $custid","bmstatclass135");
	#if no value=>PDF , else whichever doc (CSV or PDF)
	if(sizeof($statdelA)==0){
		$pdfRequired=true;
	} else{
		if(in_array("pdf",$statdelA)) $pdfRequired=true;
		if(in_array("csv",$statdelA)) $csvRequired=true;
	}
	#if($csvRequired) echo "Note: This client has been flagged to receive CSV attachments";
	#preference for all invoices, or only those emailed
	$emailvall=$sa["statinvsinc"];
	if($emailvall=="emailed") $this->emailedInvoiceOnly=true;

}

function taskElements($jobid){
	?>
<style>
#notemessage {display:none; color:green;}
#noteform {display:block; color:#000;border-top:1px solid #FFF;border-bottom:1px solid #FFF;}
#taskform {display:none; background:#CCC; border-bottom:1px solid #FFF;}
#ctnotesave {display:none;}
</style>
<?
	$tc=new iForm();
	$tc->tabind=$pc->tabind;
	$tc->row[]=kda("area");
	$tc->row[]=kda("allocatedto,alloclist,recips");

	$tc->row[]=kda("followupdate");
	$tc->row[]=kda("source");
	$tc->defaultF["source"]="jobnote";
	$tc->altFtypes["source"]="hidden";
	#$tc->altFtypes["customerid"]="hidden";
	$tc->altFtypes["customerid"]="hidden";
	$tc->altFtypes["jobid"]="hidden";

	$tc->row[]=kda("customerid,jobid");
	$tc->defaultF["customerid"]=singlevalue("jobs","customerid","jobid",$jobid);
	$tc->defaultF["jobid"]=$jobid;

	$tc->row[]=kda("reminder");
	$tc->row[]=kda("raisedby");
	$tc->row[]=kda("taskinc");
	$tc->altFtypes["taskinc"]="hidden";
	$tc->altFtypes["alloclist"]="textblock";
	$tc->altFtypes["recips"]="hidden";
	$tc->fsize["allocatedto"]=5;
	$tc->flabel["alloclist"]="Task Recips <br><i>Click items to remove</i>";


	$tc->altFtypes["followupdate"]="uiDateTime";
	$tc->altFtypes["reminder"]="uiDateTime";
	$tc->altFtypes["area"]="customList";
	$tc->altFtypes["raisedby"]="ro_text";
	$tc->altFtypes["area"]="customList";

	$tc->valtable["area"]="taskType";
	$tc->altFtypes["allocatedto"]="validatedsql";
	$tc->valtable["allocatedto"]="select lcase(userid) as allocatedto,lcase(userid) as fieldtofind from users where (inactive is null or inactive!='on')order by userid";
	#$tc->multiple["allocatedto"]=true;
	#$tc

	$tc->bvalid["area"]="required";
	$tc->bvalid["allocatedto"]="required";

	$tc->defaultF["allocatedto"]=$_SESSION["userid"];
	$tc->defaultF["raisedby"]=$_SESSION["userid"];


	$tc->defaultF["allocatedto"]=$_SESSION["userid"];
	$startd=date("Y-m-d H:i");
	$tc->defaultF["followupdate"]=addTime($startd,0, 10);
	$tc->defaultF["reminder"]=addTime($startd,0, 10);

	$tc->formId="tasknote";
	$tx=$tc->fat("tasknote");
	$this->taskForm=$tx;
	$this->taskForm="<div id=taskform>$tx</div>";

	$jcl="
	$('#taskrequired','#jobnoteF').click(function(){
		$('#taskform').slideDown();

		$('#taskinc','#taskform').val(1);

	});
	$('#allocatedto','#taskform').click(function(){
		var anv=$(this).val();
		//alert(anv);
		var liv='<li class=lic>'+anv+'</li>';
		$('#alloclist').append(liv);
		listVals();
	});

	function listVals(){
		var lvx=$('.lic').map(function() {
		  return $(this).text();
		}).get().join('|');
	//alert(lvx);
		$('#recips','#taskform').val(lvx);
	}

	$('#taskform').delegate('.lic','click',function(){
		//alert('lic');
		$(this).remove();
		listVals();

	})

	//alert('ubd');
	//$('#fakefollowupdate').datepicker('destroy');
	$('#dialogPopTask').html('');

	//$('#dialogGeneric').dialog('option','buttons',  {
	//	'Save Note': function(){
	//		noteSaver();
	//	},
	//	'close': function(){
	//		$('#dialogGeneric').dialog('close');
	//	}
	//});

	$('#ctnotesave').click(function(){

	});";
#$jcl="alert('tcl');";


	$this->customCall.=$jcl;
	$this->customCall.=$tc->icall;


}


}
?>