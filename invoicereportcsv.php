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
	
	
	
	
	$CSVFile = "Invoice Number;Company Name;Customer;Invoice Date;Invoiced Amount\n";
	
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
		
		$CSVFile .= $InvNumber . ";" . $CompanyName . ";" . $CustomerName . ";" . $InvoiceDate . ";R" . number_format($ThisInvoiceAmount,2,".", "") . "\n";
		
		
		
	}
	
	$CSVFile .= "\n";
	$CSVFile .= "\n";
	
	
	$CSVFile .= ";;;Total Billed Invoices;R" . number_format($TotalInvoiced,2,".", "") . "\n";
	
	header('Content-Disposition: attachment; filename="invoicereport_' . $FromDate . '-' . $ToDate . '.csv"');
	header("Content-Type: text/csv");
	
	echo $CSVFile;
	
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

