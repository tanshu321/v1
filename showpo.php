<?php
session_start();

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
set_time_limit(1800);
	
$ThisClientID = $_SESSION["ClientID"];
$ThisUser = $_SESSION["ClientName"];
$ThisSecret = $_SESSION["SiteSecret"];

if ($ThisClientID > 0 && $ThisUser != "" && $ThisSecret == "E2A_crm_S5gdbh6nnj_usr_9898")
{

	$PurchaseID = $_REQUEST["p"];
	$SupplierID = $_REQUEST["s"];
	
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	$GetSupplier = "SELECT * FROM suppliers WHERE SupplierID = {$SupplierID}";
	$GotSupplier = mysqli_query($ClientCon, $GetSupplier);
	
	while ($Val = mysqli_fetch_array($GotSupplier))
	{
		$SupplierName = $Val["SupplierName"];
		$SupplierEmail = $Val["SupplierEmail"];
		$SupplierTel = $Val["SupplierTel"];
		$SupplierFax = $Val["SupplierFax"];
		$SupplierContact = $Val["SupplierContact"];
		$SupplierVat = $Val["SupplierVat"];
		$SupplierAddress1 = $Val["SupplierAddress1"];
		$SupplierAddress2 = $Val["SupplierAddress2"];
		$City = $Val["City"];
		$State = $Val["State"];
		$PostCode = $Val["PostCode"];
		$ThisCountryID = $Val["CountryID"];
		$SupplierNote = $Val["SupplierNote"];
		$SupplierStatus = $Val["SupplierStatus"];
		
		if ($SupplierFax == "")
		{
			$SupplierFax = 'None';	
		}
		
		if ($SupplierVat == "")
		{
			$SupplierVat = 'None';	
		}
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
		$DisplayVat = $Val["VATNumber"];
		
		if ($DisplayFax == "")
		{
			$DisplayFax = 'None';	
		}
		
		if ($DisplayVat == "")
		{
			$DisplayVat = 'None';	
		}
		
		
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
	
	//THEN GET PO DETAILS
	$GetPO = "SELECT * FROM purchaseorders WHERE PurchaseID = {$PurchaseID} AND SupplierID = {$SupplierID}";	
	$GotPO = mysqli_query($ClientCon, $GetPO);
	
	while ($Val = mysqli_fetch_array($GotPO))
	{
		$PurchaseNumber = $Val["PurchaseNumber"];
		$Created = $Val["PurchaseOrderDate"];	
		$AddedByName = $Val["AddedByName"];
		$SentDate = $Val["SentDate"];
		$DeliveryType = $Val["DeliveryType"];
		$SpecialInstructions = $Val["SpecialInstructions"];
	}
	
	//AND THEN THE LINES
	$GetLines = "SELECT * FROM purchaseorderlines WHERE PurchaseID = {$PurchaseID}";	
	$GotLines = mysqli_query($ClientCon, $GetLines);
	
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
	
	$pdf->addText(350,810,10,"<b>PO Number</b>"); $pdf->addText(430,810,10,$PurchaseNumber);
	$pdf->addText(350,790,10,"<b>Created</b>"); $pdf->addText(430,790,10,$Created);
	$pdf->addText(350,770,10,"<b>Sent</b>"); $pdf->addText(430,770,10,$SentDate);
	$pdf->addText(350,750,10,"<b>Supplier</b>"); $pdf->addText(430,750,10,$SupplierName);
	
	//BOTTOM
	$pdf->addText(490,20,8,"PO Number " . $PurchaseNumber);
	
	$data = array();
	
	$pdf->ezText("Purchase Order Details",10,array('aleft'=> 20));
	$pdf->ezSetDy(-10);
	
	if ($DeliveryType == "Deliver")
	{
		$data[] = array('<b>Supplier Details</b>'=>'<b>' . $SupplierName . '</b>','<b>Customer Details</b>'=>'<b>' . $DisplayCompany . '</b>','<b>Delivery Address</b>'=>$Address1);
		$data[] = array('<b>Supplier Details</b>'=>'Tel: ' . $SupplierTel,'<b>Customer Details</b>'=>'Tel : ' . $DisplayTel,'<b>Delivery Address</b>'=>$Address2);
		$data[] = array('<b>Supplier Details</b>'=>'Fax: ' . $SupplierFax,'<b>Customer Details</b>'=>'Fax : ' . $DisplayFax,'<b>Delivery Address</b>'=>$City);
		$data[] = array('<b>Supplier Details</b>'=>'Email : ' . $SupplierEmail,'<b>Customer Details</b>'=>'Email : '. $DisplayEmail,'<b>Delivery Address</b>'=>$Region);
		$data[] = array('<b>Supplier Details</b>'=>'Contact : ' . $SupplierContact,'<b>Customer Details</b>'=>'Requested By : ' . $AddedByName,'<b>Delivery Address</b>'=>$CountryName);
		$data[] = array('<b>Supplier Details</b>'=>'VAT Number : ' . $SupplierVat,'<b>Customer Details</b>'=>'VAT Number : ' . $DisplayVat,'<b>Delivery Address</b>'=>$PostCode);
	}
	else
	{
		$data[] = array('<b>Supplier Details</b>'=>'<b>' . $SupplierName . '</b>','<b>Customer Details</b>'=>'<b>' . $DisplayCompany . '</b>','<b>Delivery Address</b>'=>'Collect');
		$data[] = array('<b>Supplier Details</b>'=>'Tel: ' . $SupplierTel,'<b>Customer Details</b>'=>'Tel : ' . $DisplayTel,'<b>Delivery Address</b>'=>'');
		$data[] = array('<b>Supplier Details</b>'=>'Fax: ' . $SupplierFax,'<b>Customer Details</b>'=>'Fax : ' . $DisplayFax,'<b>Delivery Address</b>'=>'');
		$data[] = array('<b>Supplier Details</b>'=>'Email : ' . $SupplierEmail,'<b>Customer Details</b>'=>'Email : '. $DisplayEmail,'<b>Delivery Address</b>'=>'');
		$data[] = array('<b>Supplier Details</b>'=>'Contact : ' . $SupplierContact,'<b>Customer Details</b>'=>'Requested By : ' . $AddedByName,'<b>Delivery Address</b>'=>'');
		$data[] = array('<b>Supplier Details</b>'=>'VAT Number : ' . $SupplierVat,'<b>Customer Details</b>'=>'VAT Number : ' . $DisplayVat,'<b>Delivery Address</b>'=>'');

	}
	
	
	$pdf->ezTable($data,'','',array('shaded'=>0,'fontSize' => 8,'xPos'=>580,'xOrientation'=>'left','width'=>550,'showLines'=>0,'cols'=>array(
	'<b>QTY</b>'=>array('width'=>40)
	,'<b>Product</b>'=>array('width'=>250),'<b>Rate</b>'=>array('justification'=>'right'),'<b>VAT Amt</b>'=>array('justification'=>'right'),'<b>Amount</b>'=>array('justification'=>'right'))));
	
	
	$pdf->ezSetDy(-40);
	
	
	
	
	$pdf->ezText("Purchase Order Items",10,array('aleft'=> 20));
	
	$pdf->ezSetDy(-10);
	
	
	
	$InvoiceSub = 0;
	$InvoiceDiscount = 0;
	$InvoiceVat = 0;
	$InvoiceTotal = 0; 
	
	
	
	$data = array();
									
	while ($Val = mysqli_fetch_array($GotLines))
	{
		$Description = $Val["Description"];	
		$Quantity = $Val["Quantity"];
		$Price = $Val["Price"];
										
		$LineSub = $Val["LineSubTotal"];
		$InvoiceSub = $InvoiceSub + $LineSub;
										
		$Vat = $Val["LineVAT"];
		$InvoiceVat = $InvoiceVat + $Vat;
										
		$Meassure = $Val["MeassurementDescription"];
										
		$LineTotal = $Val["LineTotal"];
		$InvoiceTotal = $InvoiceTotal + $LineTotal;
										
		$PurchaseLineItemID = $Val["PurchaseLineItemID"];
		
		
		$data[] = array('<b>Product</b>'=>$Description . '(' . $Meassure . ')','<b>Price</b>'=>'R' . number_format($Price,2), '<b>QTY</b>'=>$Quantity, '<b>Sub Total</b>'=>'R' . number_format($LineSub,2), '<b>VAT</b>'=>'R' . number_format($Vat,2), '<b>Total</b>'=>'R' . number_format($LineTotal,2));
	}
	
							
	$pdf->ezTable($data,'','',array('shaded'=>1,'fontSize' => 8,'xPos'=>580,'xOrientation'=>'left','width'=>550,'cols'=>array(
	'<b>QTY</b>'=>array('width'=>40)
	,'<b>Product</b>'=>array('width'=>250),'<b>Rate</b>'=>array('justification'=>'right'),'<b>VAT Amt</b>'=>array('justification'=>'right'),'<b>Amount</b>'=>array('justification'=>'right'))));
	
	$pdf->ezSetDy(-20);
	
	//TOTALS
	$data = array();
	
	$data[] = array('<b>Total</b>'=>'<b>Sub Total</b>','<b>Price</b>'=>'R' . number_format($InvoiceSub,2));
	$data[] = array('<b>Total</b>'=>'<b>VAT</b>','<b>Price</b>'=>'R' . number_format($InvoiceVat,2));
	$data[] = array('<b>Total</b>'=>'<b>Total</b>','<b>Price</b>'=>'R' . number_format($InvoiceTotal,2));
	
	$pdf->ezTable($data,'','',array('shaded'=>0,'fontSize' => 8,'xPos'=>615,'xOrientation'=>'left','width'=>200,'showLines'=>0,'showHeadings'=>0,'cols'=>array(
	'<b>QTY</b>'=>array('width'=>40)
	,'<b>Product</b>'=>array('width'=>250),'<b>Rate</b>'=>array('justification'=>'right'),'<b>VAT Amt</b>'=>array('justification'=>'right'),'<b>Amount</b>'=>array('justification'=>'right'))));
	
	
	$pdf->ezSetDy(-20);
	$data = array();
	
	if ($SpecialInstructions == "")
	{
		$SpecialInstructions = "None";	
	}
	
	$data[] = array('<b>Special Instructions</b>'=>$SpecialInstructions);
	
	$pdf->ezTable($data,'','',array('shaded'=>1,'fontSize' => 8,'xPos'=>580,'xOrientation'=>'left','width'=>550,'showLines'=>0,'cols'=>array(
	'<b>QTY</b>'=>array('width'=>40)
	,'<b>Product</b>'=>array('width'=>250),'<b>Rate</b>'=>array('justification'=>'right'),'<b>VAT Amt</b>'=>array('justification'=>'right'),'<b>Amount</b>'=>array('justification'=>'right'))));
	
		
	//$pdfcode = $pdf->output();
	$pdf->ezStream();
	
}
?>

