<?php
session_start();

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
set_time_limit(1800);
	
$ThisClientID = $_SESSION["ClientID"];
$ThisUser = $_SESSION["ClientName"];
$ThisSecret = $_SESSION["SiteSecret"];

$FromDate = $_REQUEST["from"];
$ToDate = $_REQUEST["to"];

if ($ThisClientID > 0 && $ThisUser != "" && $ThisSecret == "E2A_crm_S5gdbh6nnj_usr_9898")
{

	$InvoiceID = $_REQUEST["i"];
	$CustomerID = $_REQUEST["c"];
	
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
	$pdf->addText(430,20,8,"Income Report for " . $FromDate . " - " . $ToDate);
	
	$data = array();
	
	
	
	$pdf->ezText("Income Report for " . $FromDate . " - " . $ToDate ,10,array('aleft'=> 20));
	
	$pdf->ezSetDy(-10);
	
	
	$data = array();
	
	$Today = date("Y-m-d");
	
	$GetIncome = "SELECT * FROM customers, customertransactions WHERE customertransactions.CustomerID = customers.CustomerID AND PaymentDate >= '{$FromDate}' AND PaymentDate <= '{$ToDate} ORDER BY PaymentDate DESC'";
	$GotIncome = mysqli_query($ClientCon, $GetIncome);
	
	$TotalIncome = 0;
	
	//FIRST THE GROUPS
	while ($Val = mysqli_fetch_array($GotIncome))
	{
		$TransactionID = $Val["TransactionID"];
		$PaymentDate = $Val["PaymentDate"];
															
		$AddedBy = $Val["AddedByName"];
		$TotalPayment = $Val["TotalPayment"];
		$Description = $Val["Description"];
		$PaymentMethod = $Val["PaymentMethod"];
		$Ref = $Val["TransactionReference"];
															
		$Customer = $Val["FirstName"] . " " . $Val["Surname"];
		$CompanyName = $Val["CompanyName"];
		$CustomerID = $Val["CustomerID"];
															
		if ($CompanyName != "")
		{
			$Customer .= " (" . $CompanyName . ")";	
		}
															
		$TotalIncome = $TotalIncome + $TotalPayment;
		
		$data[] = array('<b>Customer</b>'=>$Customer,'<b>Payment Date</b>'=>$PaymentDate,'<b>Description</b>'=>$Description, '<b>Reference</b>'=>$Ref, '<b>Payment Method</b>'=>$PaymentMethod, '<b>Added By</b>'=>$AddedBy, '<b>Payment Amount</b>'=>'R' . number_format($TotalPayment,2));
	}
	
	$data[] = array('<b>Customer</b>'=>'');
	$data[] = array('<b>Customer</b>'=>'','<b>Payment Date</b>'=>'','<b>Description</b>'=>'', '<b>Reference</b>'=>'', '<b>Payment Method</b>'=>'', '<b>Added By</b>'=>'<b>Total Income for Period</b>', '<b>Payment Amount</b>'=>'<b>R' . number_format($TotalIncome,2) . '</b>');
									
	
							
	$pdf->ezTable($data,'','',array('shaded'=>1,'fontSize' => 6,'xPos'=>580,'xOrientation'=>'left','width'=>550,'cols'=>array(
	'<b>QTY</b>'=>array('width'=>40)
	,'<b>Product</b>'=>array('width'=>250),'<b>Rate</b>'=>array('justification'=>'right'),'<b>VAT Amt</b>'=>array('justification'=>'right'),'<b>Amount</b>'=>array('justification'=>'right'))));
	
	$pdf->ezSetDy(-20);
	
	
	
		
	//$pdfcode = $pdf->output();
	$pdf->ezStream();
	
}


?>

