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
    $productname = $_REQUEST["productname"];
    $productgroup = $_REQUEST["productgroup"];
	
	
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
	$pdf->addText(430,20,8,"Sales Report " . $FromDate . " - " . $ToDate);
	
	$data = array();
	
	
	
	$pdf->ezText("<b>Sales Report " . $FromDate . " - " . $ToDate . "</b>" ,10,array('aleft'=> 20));
	
	$pdf->ezSetDy(-10);
	
	
	$data = array();


    $condition = " where 1=1 ";
    if($FromDate !='' && $ToDate !=''){
        $condition .= " and customerinvoices.InvoiceDate between '$FromDate' and '$ToDate' ";
    }
    if($productname!=''){
        $condition .=" and  products.ProductName like '%$productname%'";
    }

    if($productgroup!=''){
        $condition .=" and  productgroups.GroupName like '%$productgroup%'";
    }

    $GetProducts = "select products.ProductID as ProductID, products.ProductName as ProductName, products.ProductGroupID as ProductGroupID, products.ProductSubGroupID as ProductSubGroupID,products.ProductStatus as ProductStatus
            from products products 
            left join productgroups on productgroups.ProductGroupID = products.ProductGroupID
            left join customerinvoicelines on customerinvoicelines.ProductID = products.ProductID
            left join customerinvoices on customerinvoices.InvoiceID = customerinvoicelines.InvoiceID
            $condition group by products.ProductID order by products.ProductName asc";



	$GotProducts = mysqli_query($ClientCon, $GetProducts);
	
	//FIRST THE GROUPS
	while ($Val = mysqli_fetch_array($GotProducts))
	{
		
		$ProductID = $Val["ProductID"];
		$ProductName = $Val["ProductName"];
										
		$ProductGroupID = $Val["ProductGroupID"];
		$ProductSubGroupID = $Val["ProductSubGroupID"];
		$ProductStatus = $Val["ProductStatus"];
										
		if ($ProductGroupID != "")
		{
			$ShowGroup = GetThisProductGroup($ProductGroupID, $ProductSubGroupID);
		}
		else
		{
			$ShowGroup = "None";	
		}
		
		$ProductSales = GetProductSales($ProductID, $FromDate, $ToDate);
		$RandValue = $ProductSales[0];
		$StockValue = $ProductSales[1];
		
		$data[] = array('<b>Product Name</b>'=>$ProductName,'<b>Product Group</b>'=>$ShowGroup, '<b>Sold</b>'=>'R' . $RandValue, '<b>Stock</b>'=>$StockValue);
	}
	
	
									
	
							
	$pdf->ezTable($data,'','',array('shaded'=>1,'fontSize' => 8,'xPos'=>580,'xOrientation'=>'left','width'=>550,'cols'=>array(
	'<b>QTY</b>'=>array('width'=>40)
	,'<b>Product</b>'=>array('width'=>250),'<b>Rate</b>'=>array('justification'=>'right'),'<b>VAT Amt</b>'=>array('justification'=>'right'),'<b>Amount</b>'=>array('justification'=>'right'))));
	
	$pdf->ezSetDy(-20);
	
	
	
		
	//$pdfcode = $pdf->output();
	$pdf->ezStream();
	
}

function GetThisProductGroup($ProductGroupID, $ProductSubGroupID)
{
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	$GetGroup = "SELECT * FROM productgroups WHERE ProductGroupID = {$ProductGroupID}";
	$GotGroup = mysqli_query($ClientCon, $GetGroup);
	
	while ($Val = mysqli_fetch_array($GotGroup))
	{
		$ReturnGroup = $Val["GroupName"];
	}
	
	if ($ProductSubGroupID != "")
	{
		$GetGroup = "SELECT * FROM productsubgroups WHERE ProductSubGroupID = {$ProductSubGroupID}";
		$GotGroup = mysqli_query($ClientCon, $GetGroup);
		
		while ($Val = mysqli_fetch_array($GotGroup))
		{
			$ReturnGroup .= " - " .  $Val["SubGroupName"];
		}
	}
	
	return $ReturnGroup;
}

//SALES REPORT
function GetProductSales($ProductID, $FromDate, $ToDate)
{
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	$GetSales = "SELECT SUM(LineTotal) AS RandValue, SUM(StockAffect) AS NumSold FROM customerinvoices, customerinvoicelines WHERE customerinvoices.InvoiceID = customerinvoicelines.InvoiceID AND InvoiceDate >= '{$FromDate}' AND InvoiceDate <= '{$ToDate}' AND ProductID = {$ProductID}";
	$GotSales = mysqli_query($ClientCon, $GetSales);
	
	while ($Val = mysqli_fetch_array($GotSales))
	{
		$RandValue = $Val["RandValue"];
		$NumSold = $Val["NumSold"];	
	}
	
	if ($RandValue == "")
	{
		$RandValue = 0;
		$NumSold = 0;
	}
	
	$ReturnArray = array(0=>number_format($RandValue,2), 1=>$NumSold);
	
	
	
	return $ReturnArray;
	
	
}
?>

