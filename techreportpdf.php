<?php
session_start();

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
set_time_limit(1800);
	
$ThisClientID = $_SESSION["ClientID"];
$ThisUser = $_SESSION["ClientName"];
$ThisSecret = $_SESSION["SiteSecret"];

if ($ThisClientID > 0 && $ThisUser != "" && $ThisSecret == "E2A_crm_S5gdbh6nnj_usr_9898")
{

	
	$FromDate = $_REQUEST["from"];
	$ToDate = $_REQUEST["to"];
	
	
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	
	
	//GET LOGO FROM COMPANY SETUP, MUST BE JPG
	$GetInvoiceLogo = "SELECT * FROM companysettings";
	$GotInvoiceLogo = mysqli_query($ClientCon, $GetInvoiceLogo);
	
	
	while ($Val = mysqli_fetch_array($GotInvoiceLogo))
	{
		$CompanyLogo = $Val["InvoiceLogo"];	
		$Address1 = $Val["Address1"];
		$Address2 = $Val["Address2"];
		$City = $Val["City"];
		$Region = $Val["Region"];
		$PostCode  = $Val["PostCode"];
		$CountryID = $Val["CountryID"];
		$DisplayCompany = $Val["InvoiceDisplayCompany"];
		$DisplayEmail = $Val["InvoiceDisplayEmail"];
		$DisplayTel = $Val["InvoiceDisplayTel"];
		$DisplayFax = $Val["InvoiceDisplayFax"];
		$DisplayVat = $Val["VatNumber"];
		$CompanyReg = $Val["CompanyRegistration"];
		
		if ($DisplayFax == "")
		{
			$DisplayFax = 'None';	
		}
		
		if ($DisplayVat == "")
		{
			$DisplayVat = 'None';	
		}
		
		$BankName = $Val["BankName"];
		$AccountHolder = $Val["AccountHolder"];
		$AccountNumber = $Val["AccountNumber"];
		$BranchCode = $Val["BranchCode"];
		$AccountType = $Val["AccountType"];
		
		
	}
	
	if ($CountryID != "")
	{
		$GetCountry = "SELECT * FROM countries WHERE CountryID = {$CountryID}";
		$GotCountry = mysqli_query($ClientCon, $GetCountry);
		
		while ($Val = mysqli_fetch_array($GotCountry))
		{
			$CountryName = $Val["CountryName"];	
		}
	}
	
	
	$ThisQuoteCompany = $SupplierName;
	if ($CompanyLogo != "")
	{
		$CompanyLogo = "images/" . $CompanyLogo;
	}
	
	
	
	
	//NEW PDF CREATION SCRIPT//////////////////////////////////////////////
	include 'ezpdf/class.ezpdf.php';
		
	error_reporting(E_ALL ^ E_NOTICE);
	//set_time_limit(1800);
	
	$pdf = new Cezpdf('a4','portrait');
	$pdf -> ezSetMargins(130,70,50,50);
		
	// put a line top and bottom on all the pages
	$all = $pdf->openObject();
	$pdf->saveState();
	$pdf->setStrokeColor(0,0,0,1);
	$pdf->line(20,40,578,40);
	if ($CompanyLogo != "")
	{
		$pdf->addJpegFromFile($CompanyLogo,20,760,150);
		$pdf->addJpegFromFile($CompanyLogo,20,5,60);
	}
	
	
	
	
	
	
	$pdf->restoreState();
	$pdf->closeObject();
	// note that object can be told to appear on just odd or even pages by changing 'all' to 'odd'
	// or 'even'.
	$pdf->addObject($all,'all');
		
	//$mainFont = 'fonts/Helvetica.afm';
	$mainFont = 'ezpdf/fonts/Helvetica.afm';
	$codeFont = 'ezpdf/fonts/Helvetica.afm';
	// select a font
	$pdf->selectFont($mainFont);
	//$pdf->ezStartPageNumbers(550,20,10,'right','',1);
	$pdf->openHere('Fit');
	
	
	
	//BOTTOM
	$pdf->addText(430,20,8,"Jobcard Report - " . $ReportType);
	
	$data = array();
	
	
	
	$pdf->ezText("<b>Technician Report from " . $FromDate . " to " . $ToDate . "</b>" ,10,array('aleft'=> 20));
	
	$pdf->ezSetDy(-10);
	
	
	$data = array();
	
	$Today = date("Y-m-d");
	
	$GetTechs = GetTechnicians();
	
	//FIRST THE GROUPS
	while ($Val = mysqli_fetch_array($GetTechs))
	{
		$AssignedTo = $Val["AssignedTo"];
		$AssignedTech = GetEmployee($AssignedTo);
		while ($ValEmp = mysqli_fetch_array($AssignedTech))
		{
			$EmpName = $ValEmp["Name"];	
			$EmpSurname = $ValEmp["Surname"];	
											
			$ShowEmployee = $EmpName . " " . $EmpSurname;
		}
										
		//HERE COMES THE SMARTNESS
		$TotalJobsPeriod = GetTotalJobs($AssignedTo, $FromDate, $ToDate);
		$IncomepletePeriod = GetTotalIncompleteJobs($AssignedTo, $FromDate, $ToDate);
		$WaitingInvoice = GetTotalWaitingJobs($AssignedTo, $FromDate, $ToDate);
		$CompletedJobs = GetTotalCompletedJobs($AssignedTo, $FromDate, $ToDate);
		$RecordedHours = GetTotalHours($AssignedTo, $FromDate, $ToDate);
										
		$IncomeFromJobs = GetTotalIncomeFromJobs($AssignedTo, $FromDate, $ToDate);
		
		
		
		$data[] = array('<b>Technician Name</b>'=>$ShowEmployee,'<b>Scheduled Period</b>'=>$TotalJobsPeriod, '<b>Incomplete</b>'=>$IncomepletePeriod, '<b>Waiting Invoice</b>'=>$WaitingInvoice,'<b>Completed</b>'=>$CompletedJobs,'<b>Recorded Hours</b>'=>$RecordedHours,'<b>Invoiced Amount</b>'=>'R' . number_format($IncomeFromJobs,2));
	}
						
	$pdf->ezTable($data,'','',array('shaded'=>1,'fontSize' => 8,'xPos'=>580,'xOrientation'=>'left','width'=>550,'cols'=>array(
	'<b>QTY</b>'=>array('width'=>40)
	,'<b>Product</b>'=>array('width'=>250),'<b>Rate</b>'=>array('justification'=>'right'),'<b>VAT Amt</b>'=>array('justification'=>'right'),'<b>Amount</b>'=>array('justification'=>'right'))));
	
	$pdf->ezSetDy(-20);
	
	
	
		
	//$pdfcode = $pdf->output();
	$pdf->ezStream();
	
}

//TECHNICIAN REPORT
function GetEmployee($EmployeeID)
{
	include('includes/dbinc.php');
	
	$GetEmployee = "SELECT * FROM employees WHERE EmployeeID = {$EmployeeID}";
	$GotEmployee = mysqli_query($DB, $GetEmployee);
	
	
	return $GotEmployee;
}

function GetTechnicians()
{
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	$GetTechs = "SELECT DISTINCT(AssignedTo) FROM jobcards";
	$GotTechs = mysqli_query($ClientCon, $GetTechs);
	echo mysqli_error($ClientCon);
	
	return $GotTechs;
}

function GetTotalJobs($AssignedTo, $FromDate, $ToDate)
{
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	$GetTotalJobsPeriod = "SELECT COUNT(JobcardID) AS NumJobs FROM jobcards WHERE DateScheduled >= '{$FromDate}' AND DateScheduled <= '{$ToDate}' AND AssignedTo = {$AssignedTo}";
	$GotTotalJobsPeriod = mysqli_query($ClientCon, $GetTotalJobsPeriod);
	echo mysqli_error($ClientCon);
	
	$TotalJobs = 0;
	
	while ($Val = mysqli_fetch_array($GotTotalJobsPeriod))
	{
		$TotalJobs = $Val["NumJobs"];	
	}
	
	return $TotalJobs;
}

function GetTotalIncompleteJobs($AssignedTo, $FromDate, $ToDate)
{
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	$GetTotalJobsPeriod = "SELECT COUNT(JobcardID) AS NumJobs FROM jobcards WHERE DateScheduled >= '{$FromDate}' AND DateScheduled <= '{$ToDate}' AND AssignedTo = {$AssignedTo} AND JobcardStatus = 0";
	$GotTotalJobsPeriod = mysqli_query($ClientCon, $GetTotalJobsPeriod);
	echo mysqli_error($ClientCon);
	
	$TotalJobs = 0;
	
	while ($Val = mysqli_fetch_array($GotTotalJobsPeriod))
	{
		$TotalJobs = $Val["NumJobs"];	
	}
	
	return $TotalJobs;	
}

function GetTotalWaitingJobs($AssignedTo, $FromDate, $ToDate)
{
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	$GetTotalJobsPeriod = "SELECT COUNT(JobcardID) AS NumJobs FROM jobcards WHERE DateScheduled >= '{$FromDate}' AND DateScheduled <= '{$ToDate}' AND AssignedTo = {$AssignedTo} AND JobcardStatus = 1";
	$GotTotalJobsPeriod = mysqli_query($ClientCon, $GetTotalJobsPeriod);
	echo mysqli_error($ClientCon);
	
	$TotalJobs = 0;
	
	while ($Val = mysqli_fetch_array($GotTotalJobsPeriod))
	{
		$TotalJobs = $Val["NumJobs"];	
	}
	
	return $TotalJobs;
}

function GetTotalCompletedJobs($AssignedTo, $FromDate, $ToDate)
{
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	$GetTotalJobsPeriod = "SELECT COUNT(JobcardID) AS NumJobs FROM jobcards WHERE DateScheduled >= '{$FromDate}' AND DateScheduled <= '{$ToDate}' AND AssignedTo = {$AssignedTo} AND JobcardStatus = 2";
	$GotTotalJobsPeriod = mysqli_query($ClientCon, $GetTotalJobsPeriod);
	echo mysqli_error($ClientCon);
	
	$TotalJobs = 0;
	
	while ($Val = mysqli_fetch_array($GotTotalJobsPeriod))
	{
		$TotalJobs = $Val["NumJobs"];	
	}
	
	return $TotalJobs;
}

function GetTotalHours($AssignedTo, $FromDate, $ToDate)
{
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	$GetTotalJobsPeriod = "SELECT * FROM jobcards WHERE DateScheduled >= '{$FromDate}' AND DateScheduled <= '{$ToDate}' AND AssignedTo = {$AssignedTo} AND JobcardStatus = 2";
	$GotTotalJobsPeriod = mysqli_query($ClientCon, $GetTotalJobsPeriod);
	echo mysqli_error($ClientCon);
	
	$TotalHours = 0;
	$TotalMinutes = 0;
	
	while ($Val = mysqli_fetch_array($GotTotalJobsPeriod))
	{
		$TotalTime = $Val["TotalTime"];
		$TotalTimeArray = explode(":", $TotalTime);
		
		
		$ThisHours = str_ireplace("hrs", "", $TotalTimeArray[0]);
		$ThisMinutes = str_ireplace("min", "", $TotalTimeArray[1]);
		
		
		
		
		$TotalHours = $TotalHours + $TotalTimeArray[0];
		$TotalMinutes = $TotalMinutes + $TotalTimeArray[1];	
	}
	
	
	$NumMinutes = $TotalMinutes % 60;
	$NumHours = floor($TotalMinutes / 60) + $TotalHours;
		
	
	
	
	//echo $NumHours . ":" . $NumMinutes . "<br><br>";
	
	if ($NumHours < 10)
	{
		$NumHours = "0". $NumHours;	
	}
	
	if ($NumMinutes < 10)
	{
		$NumMinutes = "0". $NumMinutes;	
	}
	
	return $NumHours . ":" . $NumMinutes;
}

function GetTotalIncomeFromJobs($AssignedTo, $FromDate, $ToDate)
{
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	$GetTotalJobsPeriod = "SELECT SUM(LineTotal) AS TotalInvoiced FROM jobcards, customerinvoicelines WHERE DateScheduled >= '{$FromDate}' AND DateScheduled <= '{$ToDate}' AND AssignedTo = {$AssignedTo} AND JobcardStatus = 2 AND jobcards.InvoiceID = customerinvoicelines.InvoiceID AND jobcards.InvoiceID > 0";
	$GotTotalJobsPeriod = mysqli_query($ClientCon, $GetTotalJobsPeriod);
	echo mysqli_error($ClientCon);
	
	$TotalJobs = 0;
	
	while ($Val = mysqli_fetch_array($GotTotalJobsPeriod))
	{
		$TotalInvoiced = $Val["TotalInvoiced"];	
	}
	
	return $TotalInvoiced;	
}
?>

