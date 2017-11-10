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
	
	if ($FromDate == "")
	{
		$FromDate = date("Y-m-d", mktime(0,0,0, date("m"), 1, date("Y")));
		$ToDate = date("Y-m-d");	
	}
	
	$Clients = GetAllClients();
	
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
	$pdf->addText(430,20,8,"Statement Report " . $FromDate . " - " . $ToDate);
	
	$data = array();
	
	
	
	$pdf->ezText("<b>Statement Report " . $FromDate . " - " . $ToDate . "</b>" ,10,array('aleft'=> 20));
	
	$pdf->ezSetDy(-10);
	
	
	$data = array();
	
	
	
	//FIRST THE GROUPS
	while ($Val = mysqli_fetch_array($Clients))
	{
		$CustomerID = $Val["CustomerID"];
		$Name = $Val["FirstName"];
		$Surname = $Val["Surname"];	
		$CustomerName = $Name . " " . $Surname;
		$CompanyName = $Val["CompanyName"];
		$EmailAddress = $Val["EmailAddress"];
		$DateAdded = $Val["DateAdded"];
										
		$OpeningBalance = GetCustomerOpeningStatement($FromDate, $ToDate, $CustomerID);
		$CustomerStatementArray = GetCustomerStatementReport($FromDate, $ToDate, $CustomerID);
										
		$TotalDebit = $CustomerStatementArray[0]["Debit"];
		$TotalCredit = $CustomerStatementArray[0]["Credit"];
										
										
		if ($OpeningBalance > 0)
		{
			$AccountBalance = ($TotalDebit - $TotalCredit) + $OpeningBalance;
		}
		else
		{
			$AccountBalance =  ($TotalDebit - $TotalCredit) - $OpeningBalance;
		}
		
		$data[] = array('<b>Customer</b>'=>$CustomerName,'<b>Company Name</b>'=>$CompanyName, '<b>Opening Balance ' . $FromDate . '</b>'=>'R' . number_format($OpeningBalance,2), '<b>Debit</b>'=>'R' . number_format($TotalDebit,2), '<b>Credit</b>'=>'R' . number_format($TotalCredit,2), '<b>Closing Balance ' . $ToDate . '</b>'=>'R' . number_format($AccountBalance,2));
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

function GetAllClients()
{
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	
	
	$ThisClientID = $_SESSION["ClientID"];
	
	$GetClients = "SELECT * FROM customers ORDER BY FirstName, Surname, CompanyName DESC";	
	$GotClients = mysqli_query($ClientCon, $GetClients);
	echo mysqli_error($ClientCon);
	
	return $GotClients;
}

function GetCustomerOpeningStatement($FromDate, $ToDate, $CustomerID)
{
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	//FIRST WE NEED TO GET OPENING BALANCE
	$GetOpening = "SELECT SUM(LineTotal) AS TotalDebits FROM customerinvoices, customerinvoicelines WHERE customerinvoicelines.InvoiceID = customerinvoices.InvoiceID AND customerinvoices.CustomerID = {$CustomerID}  AND InvoiceDate < '{$FromDate}' AND InvoiceStatus != 0";
	$GotOpening = mysqli_query($ClientCon, $GetOpening);
	$FoundOpening = mysqli_num_rows($GotOpening);
	
	if ($FoundOpening > 0)
	{
		while ($Val = mysqli_fetch_array($GotOpening))
		{
			$TotalDebits = $Val["TotalDebits"];	
		}
	}
	else
	{
		$TotalDebits = 0;	
	}
	
	//NOW PAYMENTS
	$GetPayments = "SELECT SUM(TotalPayment) AS TotalCredits FROM customertransactions WHERE PaymentDate < '{$FromDate}' AND CustomerID = {$CustomerID}";
	$GotPayments = mysqli_query($ClientCon, $GetPayments);
	$FoundPayments = mysqli_num_rows($GotPayments);
	
	if ($FoundPayments > 0)
	{
		while ($Val = mysqli_fetch_array($GotPayments))
		{
			$TotalCredits = $Val["TotalCredits"];	
		}
	}
	else
	{
		$TotalCredits = 0;	
	}
	
	$OpeningBalance = $TotalDebits - $TotalCredits;
	
	return $OpeningBalance;
	
}

function GetCustomerStatementReport($FromDate, $ToDate, $CustomerID)
{
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	//GET ALL INVOICES IN DATE RANGE
	$GetInvoices = "SELECT SUM(LineTotal) AS TotalDebits, customerinvoices.* FROM customerinvoices, customerinvoicelines WHERE customerinvoicelines.InvoiceID = customerinvoices.InvoiceID AND customerinvoices.CustomerID = {$CustomerID}  AND InvoiceDate >= '{$FromDate}' AND InvoiceDate <= '{$ToDate}' AND InvoiceStatus != 0 GROUP BY InvoiceID";
	
	$GotInvoices = mysqli_query($ClientCon, $GetInvoices);
	
	
	//GET ALL PAYMENTS IN DATE RANGE
	$GetPayments = "SELECT * FROM customertransactions WHERE PaymentDate >= '{$FromDate}' AND PaymentDate <= '{$ToDate}' AND CustomerID = {$CustomerID}";
	$GotPayments = mysqli_query($ClientCon, $GetPayments);
	
	
	$X = 0;
	$TotalDebit = 0;
	$TotalCredit = 0;
	
	//NOW WE NEED TO CREATE AN ARRAY OF THE INFORMATION SO WE CAN ORDER IT
	while ($Val = mysqli_fetch_array($GotInvoices))
	{
		$InvoiceDate = $Val["InvoiceDate"];
		$InvoiceNumber = $Val["InvoiceNumber"];
		$Description = "Invoice " . $InvoiceNumber;
		$InvoiceAmount = $Val["TotalDebits"];
		
		$TotalDebit = $TotalDebit + $InvoiceAmount;
	}
	
	while ($Val = mysqli_fetch_array($GotPayments))
	{
		$PaymentDate = $Val["PaymentDate"];
		$PaymentRef = $Val["TransactionReference"];
		$Description = $Val["Description"];
		$PaymentAmount = $Val["TotalPayment"];
		
		$TotalCredit = $TotalCredit + $PaymentAmount;
		
		$X++;
		
		
	}
	
	$ReturnArray[0]["Debit"] = $TotalDebit;
	$ReturnArray[0]["Credit"] = $TotalCredit;
	
	return $ReturnArray;
	
	
}
?>

