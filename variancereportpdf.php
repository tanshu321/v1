<?php
session_start();

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
set_time_limit(1800);
	
$ThisClientID = $_SESSION["ClientID"];
$ThisUser = $_SESSION["ClientName"];
$ThisSecret = $_SESSION["SiteSecret"];

if ($ThisClientID > 0 && $ThisUser != "" && $ThisSecret == "E2A_crm_S5gdbh6nnj_usr_9898")
{

	$StockTakeID = $_REQUEST["s"];
	
	
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
	
	//GET THE STOCK TAKE DETAILS
	$GetStockTake = "SELECT * FROM stocktakes, warehouses WHERE StockTakeID = {$StockTakeID} AND stocktakes.WarehouseID = warehouses.WarehouseID";
	$GotStockTake = mysqli_query($ClientCon, $GetStockTake);
	echo mysqli_error($ClientCon);
	
	while ($Val = mysqli_fetch_array($GotStockTake))
	{
		$StockTakeID = $Val["StockTakeID"];
		$WarehouseID = $Val["WarehouseID"];
		$StockTakeDate = $Val["StockTakeDate"];
		$StockTakeCompleteDate = $Val["StockTakeCompleted"];
		
		$WarehouseName = $Val["WarehouseName"];
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
	$pdf->addText(400,20,8,"Stock Variance Report " . $WarehouseName . " - " . $StockTakeDate);
	
	$data = array();
	
	
	
	$pdf->ezText("<b>Stock Variance Report " . $WarehouseName . " - " . $StockTakeDate . "</b>" ,10,array('aleft'=> 20));
	
	$pdf->ezSetDy(-10);
	
	
	$data = array();
	
	$Today = date("Y-m-d");
	
	
	
	$GetVariances = "SELECT * FROM productstock, products WHERE StockTakeID = {$StockTakeID} AND productstock.ProductID = products.ProductID";
	$GotVariances = mysqli_query($ClientCon, $GetVariances);
	
	
	
	
	
	//FIRST THE GROUPS
	while ($Val = mysqli_fetch_array($GotVariances))
	{
		$ProductName = $Val["ProductName"];	
		$StockVariance = $Val["Stock"];
		$UnitCost = $Val["UnitCost"];
		if ($UnitCost != 0)
		{
			$EstimatedLoss = $UnitCost * $StockVariance;
			$EstimatedLoss = "R " . number_format($EstimatedLoss,2);
		}
		else
		{
			$EstimatedLoss = 'NA';	
		}
		
		$data[] = array('<b>Product Name</b>'=>$ProductName,'<b>Variance</b>'=>$StockVariance, '<b>Estimated Loss</b>'=>$EstimatedLoss);
	}
	
	
							
	$pdf->ezTable($data,'','',array('shaded'=>1,'fontSize' => 8,'xPos'=>580,'xOrientation'=>'left','width'=>550,'cols'=>array(
	'<b>QTY</b>'=>array('width'=>40)
	,'<b>Product</b>'=>array('width'=>250),'<b>Rate</b>'=>array('justification'=>'right'),'<b>VAT Amt</b>'=>array('justification'=>'right'),'<b>Amount</b>'=>array('justification'=>'right'))));
	
	$pdf->ezSetDy(-20);
	
	
	
		
	//$pdfcode = $pdf->output();
	$pdf->ezStream();
	
}

function GetNumberOutstandingAmount($CustomerID)
{
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	$Today = date("Y-m-d");
	
	$GetOutstanding = "SELECT SUM(LineTotal) AS TotalOutstanding FROM customerinvoicelines WHERE InvoiceID IN (SELECT InvoiceID FROM customerinvoices WHERE InvoiceStatus = 1 AND DueDate <= '{$Today}' AND CustomerID = {$CustomerID})";
	$GotOutstanding = mysqli_query($ClientCon, $GetOutstanding);
	
	
	while ($Val = mysqli_fetch_array($GotOutstanding))
	{
		$TotalOutstanding = $Val["TotalOutstanding"];	
	}
	
	//NOW GET ANY PARTIAL PAID
	$GetOutstanding = "SELECT * FROM customerinvoices WHERE InvoiceStatus = 6 AND DueDate <= '{$Today}' AND CustomerID = {$CustomerID}";
	$GotOutstanding = mysqli_query($ClientCon, $GetOutstanding);
	echo mysqli_error($ClientCon);
	
	while ($Val = mysqli_fetch_array($GotOutstanding))
	{
		$InvoiceID = $Val["InvoiceID"];
		
		$GetInvoiceTotal = "SELECT SUM(LineTotal) AS InvoiceTotal FROM customerinvoicelines WHERE InvoiceID = {$InvoiceID}";
		$GotInvoiceTotal = mysqli_query($ClientCon, $GetInvoiceTotal);
		echo mysqli_error($ClientCon);
		
		while ($InvoiceVal = mysqli_fetch_array($GotInvoiceTotal))
		{
			$InvoiceTotal = 	$InvoiceVal["InvoiceTotal"];
		}
		
		$GetPaidAmount = "SELECT SUM(PaymentAmount) AS InvoicePayments FROM customerinvoicepayments WHERE InvoiceID = {$InvoiceID}";
		$GotPaidAmount = mysqli_query($ClientCon, $GetPaidAmount);
		echo mysqli_error($ClientCon);
			
		while ($ValPartial = mysqli_fetch_array($GotPaidAmount))
		{
			$PaidAmount = $ValPartial["InvoicePayments"];	
		}
		
		$Owing = $InvoiceTotal - $PaidAmount;
		
		
		$TotalOutstanding = $TotalOutstanding + $Owing;	
	}
	
	
	return $TotalOutstanding;
}
?>

