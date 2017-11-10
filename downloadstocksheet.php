<?php
session_start();

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
set_time_limit(1800);
	
$ThisClientID = $_SESSION["ClientID"];
$ThisUser = $_SESSION["ClientName"];
$ThisSecret = $_SESSION["SiteSecret"];

if ($ThisClientID > 0 && $ThisUser != "" && $ThisSecret == "E2A_crm_S5gdbh6nnj_usr_9898")
{

	$WarehouseID = $_REQUEST["w"];
	
	
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	//GET STOCK TAKE DETIALS
	$CheckStockTake = "SELECT * FROM stocktakes, warehouses WHERE stocktakes.WarehouseID = {$WarehouseID} AND stocktakes.WarehouseID = warehouses.WarehouseID ORDER BY StockTakeID DESC LIMIT 1";
	$DoCheckStockTake = mysqli_query($ClientCon, $CheckStockTake);
	
	while ($Val = mysqli_fetch_array($DoCheckStockTake))
	{
		$StockTakeDate = $Val["StockTakeDate"];
		$WarehouseName = $Val["WarehouseName"];
	}
	
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
	
	
	$ThisQuoteCompany = $SupplierName;
	if ($CompanyLogo != "")
	{
		$CompanyLogo = "images/" . $CompanyLogo;
	}
	
	
	
	//NOW WE NEED TO GET THE STOCK ITEMS AND CURRENT COUNT UP UNTIL THIS EXACT TIME OF THE STOCK TAKE
	$GetStockProducts = "SELECT * FROM products, productgroups  WHERE IsStockItem = 1 AND products.ProductGroupID = productgroups.ProductGroupID ORDER BY ProductName";
	$GotStockProducts = mysqli_query($ClientCon, $GetStockProducts);
	
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
	
	$pdf->addText(350,810,10,"<b>Warehouse</b>"); $pdf->addText(450,810,10,$WarehouseName);
	$pdf->addText(350,790,10,"<b>Stock Take Date</b>"); $pdf->addText(450,790,10,$StockTakeDate);
	
	
	//BOTTOM
	$pdf->addText(410,20,8,"Stock Take - " . $WarehouseName . " - " . $StockTakeDate);
	
	
	$pdf->ezText("Stock Take Items",10,array('aleft'=> 20));
	
	$pdf->ezSetDy(-10);
	
	
	$data = array();
									
	while ($Val = mysqli_fetch_array($GotStockProducts))
	{
		$ProductID = $Val["ProductID"];
		$ProductName = $Val["ProductName"];
		$ProductGroupID = $Val["ProductGroupID"];
		$ProductSubGroupID = $Val["ProductSubGroupID"];
		$GroupName = $Val["GroupName"];	
		$MinimumStock = $Val["MinimumStock"];
										
		$StockIn = 0;
		$StockOut = 0;
		
		if ($ProductSubGroupID != 0)
		{
			$GetSubGroupName = "SELECT * FROM productsubgroups WHERE ProductSubGroupID = {$ProductSubGroupID}";
			$GotSubGroupName = mysqli_query($ClientCon, $GetSubGroupName);
			
			while ($Val = mysqli_fetch_array($GotSubGroupName))
			{
				$SubGroupName = $Val["SubGroupName"];	
			}
			
			$GroupName .= " - " . $SubGroupName;
		}
		
		
		$GetStockIn = "SELECT SUM(Stock) AS StockIn FROM productstock WHERE Stock > 0 AND ProductID = {$ProductID} AND WarehouseID = {$WarehouseID} AND DateAdded <= '{$StockTakeDate}'";
		$GotStockIn = mysqli_query($ClientCon, $GetStockIn);
		
		while ($Val = mysqli_fetch_array($GotStockIn))
		{
			$StockIn = $Val["StockIn"];	
		}
		
		if ($StockIn == "")
		{
			$StockIn = 0;	
		}
		
		$GetStockOut = "SELECT SUM(Stock) AS StockOut FROM productstock WHERE Stock < 0 AND ProductID = {$ProductID} AND WarehouseID = {$WarehouseID} AND DateAdded <= '{$StockTakeDate}'";
		$GotStockOut = mysqli_query($ClientCon, $GetStockOut);
		
		while ($Val = mysqli_fetch_array($GotStockOut))
		{
			$StockOut = $Val["StockOut"];	
		}
		
		if ($StockOut == "")
		{
			$StockOut = 0;	
		}
										
		$StockLeft = $StockIn + $StockOut;
		
		
		$data[] = array('<b>Product</b>'=>$ProductName,'<b>Product Group</b>'=>$GroupName, '<b>Theoretical Stock</b>'=>$StockLeft, '<b>Actual Stock</b>'=>'');
	}
	
							
	$pdf->ezTable($data,'','',array('shaded'=>0,'showLines'=>2,'fontSize' => 8,'xPos'=>580,'xOrientation'=>'left','width'=>550,'cols'=>array(
	'<b>QTY</b>'=>array('width'=>40)
	,'<b>Product</b>'=>array('width'=>250),'<b>Rate</b>'=>array('justification'=>'right'),'<b>VAT Amt</b>'=>array('justification'=>'right'),'<b>Amount</b>'=>array('justification'=>'right'))));
		
	//$pdfcode = $pdf->output();
	$pdf->ezStream();
	
}
?>

