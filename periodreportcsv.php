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
	
	
	
	
	//$CSVFile = "Period;Date;Acc No;Reference;Description;Amount;Tax Type;Contra Account\n";
    $CSVFile ="";
	
	$Invoices = GetAllPeriodInvoices($FromDate, $ToDate);
	//FIRST THE GROUPS
	while ($Val = mysqli_fetch_assoc($Invoices))
	{

        $periodData = getPeriodByDate($Val["InvoiceDate"]);
        $Period = isset($periodData['title']) ? $periodData['title'] : "";
        $Description = isset($periodData['description']) ? $periodData['description'] : "";
        $CA = isset($periodData['contact_account']) ? $periodData['contact_account'] : "";
        $gdc = isset($periodData['gdc']) ? $periodData['gdc'] : "";
        $TaxType = $Val['Taxed'];
        $AccountNumber = $Val["DepositReference"];
        $CustomerID = $Val["CustomerID"];
        $InvoiceDate = date("d/m/Y",strtotime($Val["InvoiceDate"]));
        $reference = $Val["InvoiceNumber"];
        $InvoiceID = $Val["InvoiceID"];
        $ThisInvoiceAmount = GetInvoiceTotal($InvoiceID);


        if($TaxType == 1)
            $ticked= "2";
        else
            $ticked = "1";


        $OpenItem="";
        $Project="";
        $RxchangeRate = $BankExchangeRate="1";
        $BatchID="0";
        $DiscountTax = $DiscountAmount =  "0";
        $TaxAmount="R".number_format($ThisInvoiceAmount['InvoiceVat'],2,".", "");


		$CSVFile .= $Period . ";" . $InvoiceDate . ";" .$gdc.";".$AccountNumber . ";" . $reference . ";".$Description.";R" . number_format($ThisInvoiceAmount["InvoiceTotal"],2,".", "") .
			";".$ticked.
            ";".$TaxAmount.
            ";".$OpenItem.
            ";".$Project.
            ";".$CA.
            ";".$RxchangeRate.
            ";".$BankExchangeRate.
            ";".$BatchID.
			";".$DiscountTax.
			";".$DiscountAmount.
			";R".number_format($ThisInvoiceAmount["InvoiceTotal"],2,".", "").
			"\n";
		
		
		
	}
	
	$CSVFile .= "\n";
	$CSVFile .= "\n";
	
	

	header('Content-Disposition: attachment; filename="invoiceexport_' . $FromDate . '-' . $ToDate . '.csv"');
	header("Content-Type: text/csv");
	
	echo $CSVFile;
	
}

function GetInvoiceTotal($InvoiceID)
{
	$returnArray=array();
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	$ThisClientID = $_SESSION["ClientID"];
	
	 $GetInvoiceTotal = "SELECT SUM(LineTotal) AS InvoiceTotal, sum(LineVAT) as InvoiceVat FROM customerinvoicelines WHERE 
InvoiceID = {$InvoiceID}";

	$GotInvoiceTotal = mysqli_query($ClientCon, $GetInvoiceTotal);
	
	while ($Val = mysqli_fetch_array($GotInvoiceTotal))
	{
        $returnArray["InvoiceTotal"] = $Val["InvoiceTotal"];
        $returnArray["InvoiceVat"] = $Val["InvoiceVat"];

	}
	
	
	
	return $returnArray;
}

function GetAllPeriodInvoices($FromDate, $ToDate)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $Today = date("Y-m-d");

    $GetInvoicing = "SELECT * FROM customers, customerinvoices WHERE customers.CustomerID = customerinvoices.CustomerID AND InvoiceStatus IN (1,2,6) AND InvoiceDate <= '{$ToDate}' AND InvoiceDate >= '{$FromDate}' ORDER BY InvoiceID ASC";
    $GotInvoicing = mysqli_query($ClientCon, $GetInvoicing);

    return $GotInvoicing;
}

/**
 *
 */
function getPeriodByDate($invoiceDate = ''){
    $returnData = array();
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    if(!empty($invoiceDate)) {
        $nmonth = date('m', strtotime($invoiceDate));
        $GetPeriod = "SELECT * FROM periodsetup where month = '$nmonth'";
        $GotPeriod = mysqli_query($ClientCon, $GetPeriod);

        $returnData = mysqli_fetch_assoc($GotPeriod);

    }
    return $returnData;
}
?>

