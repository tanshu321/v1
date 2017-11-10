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
	$pdf->addText(415,20,8,"Invoice Report from " . $FromDate . " - " . $ToDate);
	
	$data = array();
	
	
	
	$pdf->ezText("<b>Invoice Report from " . $FromDate . " - " . $ToDate . "</b>" ,10,array('aleft'=> 20));
	
	$pdf->ezSetDy(-10);
	
	
	$data = array();
	
	$Invoices = GetAllInvoicesDone($FromDate, $ToDate);
	//FIRST THE GROUPS
	while ($Val = mysqli_fetch_array($Invoices))
	{
		$CustomerID = $Val["CustomerID"];
		$CustomerName = $Val["FirstName"] . " " . $Val["Surname"];
		$CompanyName = $Val["CompanyName"];
		$InvoiceID = $Val["InvoiceID"];
		$InvoiceDate = $Val["InvoiceDate"];
										
		$ThisInvoiceAmount = GetInvoiceTotal($InvoiceID);
										
		$TotalInvoiced = $TotalInvoiced + $ThisInvoiceAmount;
																		
		$InvNumber = "INV" . $InvoiceID;
		
		
		$data[] = array('<b>Invoice Number</b>'=>$InvNumber,'<b>Company Name</b>'=>$CompanyName, '<b>Customer</b>'=>$CustomerName, '<b>Invoice Date</b>'=>$InvoiceDate,'<b>Invoiced Amount</b>'=>'R' . number_format($ThisInvoiceAmount,2));
	}
	
	
	$data[] = array('<b>Invoice Number</b>'=>'','<b>Company Name</b>'=>'', '<b>Customer</b>'=>'', '<b>Invoice Date</b>'=>'','<b>Invoiced Amount</b>'=>'');
	
	$data[] = array('<b>Invoice Number</b>'=>'','<b>Company Name</b>'=>'', '<b>Customer</b>'=>'', '<b>Invoice Date</b>'=>'<b>Total Billed Invoices</b>','<b>Invoiced Amount</b>'=>'<b>R' . number_format($TotalInvoiced,2) . '</b>');								
	
							
	$pdf->ezTable($data,'','',array('shaded'=>1,'fontSize' => 8,'xPos'=>580,'xOrientation'=>'left','width'=>550,'cols'=>array(
	'<b>QTY</b>'=>array('width'=>40)
	,'<b>Product</b>'=>array('width'=>250),'<b>Rate</b>'=>array('justification'=>'right'),'<b>VAT Amt</b>'=>array('justification'=>'right'),'<b>Amount</b>'=>array('justification'=>'right'))));
	
	$pdf->ezSetDy(-20);
	
	
	
		
	//$pdfcode = $pdf->output();
	$pdf->ezStream();
	
}

function GetInvoiceTotal($InvoiceID)
{
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	$ThisClientID = $_SESSION["ClientID"];
	
	$GetInvoiceTotal = "SELECT SUM(LineTotal) AS InvoiceTotal FROM customerinvoicelines WHERE InvoiceID = {$InvoiceID}";
	$GotInvoiceTotal = mysqli_query($ClientCon, $GetInvoiceTotal);
	
	while ($Val = mysqli_fetch_array($GotInvoiceTotal))
	{
		$InvoiceTotal = $Val["InvoiceTotal"];	
	}
	
	
	
	return $InvoiceTotal;
}

function GetAllInvoicesDone($FromDate, $ToDate)
{
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	$Today = date("Y-m-d");
	
	$GetInvoicing = "SELECT * FROM customers, customerinvoices WHERE customers.CustomerID = customerinvoices.CustomerID AND InvoiceStatus NOT IN (0,3) AND InvoiceDate <= '{$ToDate}' AND InvoiceDate >= '{$FromDate}' ORDER BY InvoiceID ASC";
	$GotInvoicing = mysqli_query($ClientCon, $GetInvoicing);
	
	return $GotInvoicing;
}
?>

