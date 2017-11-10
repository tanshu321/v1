<?php
session_start();

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
set_time_limit(1800);
	
$ThisClientID = $_SESSION["ClientID"];
$ThisUser = $_SESSION["ClientName"];
$ThisSecret = $_SESSION["SiteSecret"];

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
	$pdf->addText(430,20,8,"Outstanding Invoice Report " . date("d M Y"));
	
	$data = array();
	
	
	
	$pdf->ezText("<b>Outstanding Invoice Report " . date("d M Y") . "</b>" ,10,array('aleft'=> 20));
	
	$pdf->ezSetDy(-10);
	
	$data = array();
	
	$Today = date("Y-m-d");
	
	$Outstanding = TotalInvoicesCAR('1,6');
	
	 /*$currentMonth = date("F");
     $thirtyDayMonth = Date('F', strtotime($currentMonth . " last month"));
	 $sixtyDayMonth = Date('F', strtotime($thirtyDayMonth . " last month"));
	 $nityDayMonth = Date('F', strtotime($sixtyDayMonth . " last month"));
	 $lastDayMonth = Date('F', strtotime($nityDayMonth . " last month"));*/
	 
	 $date = new DateTime();
	
	$currentMonth = $date->format('F');
	
	//$date = new DateTime();
	$interval = new DateInterval('P31D');
	$date->sub($interval);
	$thirtyDayMonth = $date->format('F');
	
	//$date = new DateTime();
	$interval = new DateInterval('P31D');
	$date->sub($interval);
	$sixtyDayMonth = $date->format('F');
	
	//$date = new DateTime();
	$interval = new DateInterval('P31D');
	$date->sub($interval);
	$nityDayMonth = $date->format('F');
	
	//$date = new DateTime();
	$interval = new DateInterval('P31D');
	$date->sub($interval);
	$lastDayMonth = $date->format('F');
	
	 
	 //FIRST THE GROUPS
	$AllInvoiceTotals = $AllCurrentInvoices=$AllthirtyDayInvoice=$AllsixtyDayInvoice=$AllninetyDayInvoice=$AllInvoice= 0;
	for($i=0;$i<count($Outstanding);$i++)
	{
		$CustomerID = $Outstanding[$i]["CustomerID"];
		$CustomerName = $Outstanding[$i]["Fullname"] ;
		$CompanyName = $Outstanding[$i]["CompanyName"];
		//$InvoiceTotals = $Outstanding[$i]["InvoiceTotals"];
		$CurrentInvoices = TotalInvoicesByDays($currentMonth, $CustomerID, '1,6');
		$thirtyDayInvoice = TotalInvoicesByDays($thirtyDayMonth, $CustomerID, '1,6');
		$sixtyDayInvoice = TotalInvoicesByDays($sixtyDayMonth, $CustomerID, '1,6');
		$ninetyDayInvoice = TotalInvoicesByDays($nityDayMonth, $CustomerID, '1,6');
		$allInvoice = TotalInvoicesByDays($lastDayMonth, $CustomerID, '1,6',TRUE);
		$InvoiceTotals = $CurrentInvoices +		$thirtyDayInvoice +	$sixtyDayInvoice + $ninetyDayInvoice +	$allInvoice ;
		
		$AllInvoiceTotals = $AllInvoiceTotals + $InvoiceTotals;
		$AllCurrentInvoices = $AllCurrentInvoices + $CurrentInvoices;
		$AllthirtyDayInvoice = $AllthirtyDayInvoice + $thirtyDayInvoice;
		$AllsixtyDayInvoice = $AllsixtyDayInvoice + $sixtyDayInvoice;
		$AllninetyDayInvoice = $AllninetyDayInvoice + $ninetyDayInvoice;
		$AllInvoice = $AllInvoice + $allInvoice;
		
		if($InvoiceTotals != '0' && $CurrentInvoices != '0' && $thirtyDayInvoice != '0' && $sixtyDayInvoice != '0' && $ninetyDayInvoice != '0' && $allInvoice != '0')
										{
		$data[] = array('<b>Customer</b>'=>$CustomerName,
						'<b>Company Name</b>'=>$CompanyName,
						'<b>Total Unpaid invoice</b>'=>'R' . number_format($InvoiceTotals,2),
						'<b>Current Invoices</b>'=>'R' . number_format($CurrentInvoices,2),
						'<b>30 days</b>'=>'R' . number_format($thirtyDayInvoice,2),
						'<b>60 days</b>'=>'R' . number_format($sixtyDayInvoice,2),
						'<b>90 days</b>'=>'R' . number_format($ninetyDayInvoice,2),
						'<b>120+ days</b>'=>'R' . number_format($allInvoice,2));
										}
	}
	
	$data[] = array('<b>Customer</b>'=>'',
					'<b>Company Name</b>'=>'',
					'<b>Total Unpaid invoice</b>'=>'',
					'<b>Current Invoices</b>'=>'',
					'<b>30 days</b>'=>'',
					'<b>60 days</b>'=>'',
					'<b>90 days</b>'=>'',
					'<b>120+ days</b>'=>''
					);
	$data[] = array('<b>Customer</b>'=>'',
					'<b>Company Name</b>'=>'<b>Total Invoices</b>',
					'<b>Total Unpaid invoice</b>'=>'<b>R' . number_format($AllInvoiceTotals,2) . '</b>',
					'<b>Current Invoices</b>'=>'<b>R' . number_format($AllCurrentInvoices,2) . '</b>',
					'<b>30 days</b>'=>'<b>R' . number_format($AllthirtyDayInvoice,2) . '</b>',
					'<b>60 days</b>'=>'<b>R' . number_format($AllsixtyDayInvoice,2) . '</b>',
					'<b>90 days</b>'=>'<b>R' . number_format($AllninetyDayInvoice,2) . '</b>',
					'<b>120+ days</b>'=>'<b>R' . number_format($AllInvoice,2) . '</b>'
					);
	
									
	
							
	$pdf->ezTable($data,'','',array('shaded'=>1,'fontSize' => 8,'xPos'=>580,'xOrientation'=>'left','width'=>550,'cols'=>array(
	'<b>QTY</b>'=>array('width'=>40)
	,'<b>Product</b>'=>array('width'=>250),'<b>Rate</b>'=>array('justification'=>'right'),'<b>VAT Amt</b>'=>array('justification'=>'right'),'<b>Amount</b>'=>array('justification'=>'right'))));
	
	$pdf->ezSetDy(-20);
	
	
	
		
	//$pdfcode = $pdf->output();
	$pdf->ezStream();
	
}
function TotalInvoicesCAR($InvoiceStatus)
{
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	$ThisClientID = $_SESSION["ClientID"];
	$returnArray=array();
	$count=0;
	
	$CountInvoices = "SELECT customers.CustomerID as CustomerID, CONCAT(customers.FirstName, ' ', customers.Surname) as Fullname,
	customers.CompanyName as CompanyName, SUM(LineTotal) AS InvoiceTotals,customerinvoices.InvoiceID as InvoiceID
	FROM customerinvoices, customerinvoicelines,
	customers WHERE InvoiceStatus IN ($InvoiceStatus) AND customerinvoices.InvoiceID = customerinvoicelines.InvoiceID
	and customers.CustomerID =customerinvoices.CustomerID group by customers.CustomerID order by fullname ASC";
	
	$DoCountInvoices = mysqli_query($ClientCon, $CountInvoices);
	$InvoiceTotals = 0;
	
	while ($Val = mysqli_fetch_array($DoCountInvoices))
	{
		$returnArray[$count]['CustomerID'] = $Val["CustomerID"];
		$returnArray[$count]['Fullname'] = $Val["Fullname"];
		$returnArray[$count]['CompanyName'] = $Val["CompanyName"];
		//$returnArray[$count]['InvoiceTotals'] = $Val["InvoiceTotals"];
		$returnArray[$count]['InvoiceTotals'] = GetInvoiceOutstandingAmount($Val["InvoiceID"]);
		$count++;
	}
	
	return $returnArray;
	
}

function GetInvoiceOutstandingAmount($InvoiceID)
{
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	$InvoiceTotal = GetInvoiceTotal($InvoiceID);
	
	//THEN CHECK IF ANY PAYMENTS FOR THIS INVOICE
	$CheckPayments = "SELECT SUM(PaymentAmount) AS PaidAmount FROM customerinvoicepayments WHERE InvoiceID = {$InvoiceID}";
	$DoCheckPayments = mysqli_query($ClientCon, $CheckPayments);
	$PaidAmount = 0;
	
	while ($Val = mysqli_fetch_array($DoCheckPayments))
	{
		$PaidAmount = $Val["PaidAmount"];	
	}
	
	$Balance = round($InvoiceTotal,2) - round($PaidAmount,2);
	
	return $Balance;
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


function TotalInvoicesByDays($monthName, $CustomerID, $InvoiceStatus,$latMonth=FALSE)
{
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	$ThisClientID = $_SESSION["ClientID"];
	
	$extraCondition = " AND  MONTHNAME(customerinvoices.InvoiceDate) = '$monthName'";
	
	if($latMonth)
		$extraCondition = " AND  customerinvoices.InvoiceDate <= DATE_SUB(NOW(), INTERVAL 4 MONTH) ";
		
	$CountInvoices = "SELECT customerinvoicelines.InvoiceID as InvoiceID
	FROM customerinvoices, customerinvoicelines WHERE
	InvoiceStatus IN ($InvoiceStatus)  AND CustomerID = {$CustomerID} $extraCondition
	AND	customerinvoices.InvoiceID = customerinvoicelines.InvoiceID group by customerinvoicelines.InvoiceID ";

	
	$DoCountInvoices = mysqli_query($ClientCon, $CountInvoices);
	$InvoiceTotals = 0;
	
	while ($Val = mysqli_fetch_array($DoCountInvoices))
	{
		$InvoiceTotal += GetInvoiceOutstandingAmount($Val["InvoiceID"]);
	}
	
	return $InvoiceTotal;
	
}
function TotalInvoicesCurrentMonth($InvoiceStatus, $CustomerID)
{
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	$ThisClientID = $_SESSION["ClientID"];
	
	$CountInvoices = "SELECT SUM(LineTotal) AS InvoiceTotals FROM customerinvoices, customerinvoicelines
	WHERE InvoiceStatus IN ($InvoiceStatus)  AND CustomerID = {$CustomerID} AND MONTH(InvoiceDate) = MONTH(CURRENT_DATE())
	AND customerinvoices.InvoiceID = customerinvoicelines.InvoiceID ";
	$DoCountInvoices = mysqli_query($ClientCon, $CountInvoices);
	$InvoiceTotals = 0;
	
	while ($Val = mysqli_fetch_array($DoCountInvoices))
	{
		$InvoiceTotal = $Val["InvoiceTotals"];
	}
	
	return $InvoiceTotal;
	
}

//end

?>

