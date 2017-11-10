<?php

session_start();

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
set_time_limit(1800);
	
$ThisClientID = $_SESSION["ClientID"];
$ThisUser = $_SESSION["ClientName"];
$ThisSecret = $_SESSION["SiteSecret"];

if ($ThisClientID > 0 && $ThisUser != "" && $ThisSecret == "E2A_crm_S5gdbh6nnj_usr_9898")
{

	
	
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	
	$JobcardID = $_REQUEST["j"];
	
	$GetJobcard = "SELECT * FROM jobcards, customers WHERE JobcardID = {$JobcardID} AND jobcards.CustomerID = customers.CustomerID ";
	$GotJobcard = mysqli_query($ClientCon, $GetJobcard);
	
	while ($Val = mysqli_fetch_array($GotJobcard))
	{
		
		$JobCustomerID = $Val["CustomerID"];
										
		//JOBCARD FIELDS
		$JobcardNumber = "JBC" . $JobcardID;
		$AssignedTo = $Val["AssignedTo"];
		$AddedBy = $Val["AddedByName"];
		$DateCreated = $Val["DateCreated"];
		$DateScheduled = $Val["DateScheduled"];
		$Notes = $Val["JobcardNotes"];
		$TechNotes = $Val["TechReport"];
		$JobcardStatus = $Val["JobcardStatus"];
		$JobcardFile = $Val["JobcardFile"];
		$JobcardNumber = "JBC" . $JobcardID;
		$CustomerAddress1 = $Val["Address1"];
		$CustomerAddress2 = $Val["Address2"];
		$CustomerCity = $Val["City"];
		$CustomerRegion = $Val["Region"];
		$CustomerPostCode = $Val["PostCode"];
		$AddedBy = $Val["AddedByName"];
		$JobcardNotes = $Val["JobcardNotes"];
		
		$CustomerTel = $Val["ContactNumber"];
		$CustomerEmail = $Val["EmailAddress"];
		
		$CustomerName = $Val["FirstName"];
		$CustomerSurname = $Val["Surname"];	
		$CustomerCompanyName = $Val["CompanyName"];
		$CustomerVAT = $Val["VatNumber"];
		
		if ($CustomerVAT == "")
		{
			$CustomerVAT = "None";	
		}
		
										
	}
	
	if ($CustomerCompanyName != "")
	{
		$ShowClient = $CustomerCompanyName;	
	}
	else
	{
		$ShowClient = $Name . "  " . $Surname;	
	}
										
	//TECH DETAILS
	include('includes/dbinc.php');
	
	$GetEmployee = "SELECT * FROM employees WHERE EmployeeID = {$AssignedTo}";
	$GotEmployee = mysqli_query($DB, $GetEmployee);
	
	while ($ValEmp = mysqli_fetch_array($GotEmployee))
	{
		$EmpName = $ValEmp["Name"];	
		$EmpSurname = $ValEmp["Surname"];	
											
		$ShowEmployee = $EmpName . " " . $EmpSurname;
	}
	
	
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	if ($JobcardStatus != 0)
	{
		die("This jobcard was already used and cannot be used again");
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
	
	$pdf->addText(350,810,10,"<b>Jobcard Number</b>"); $pdf->addText(460,810,10,$JobcardNumber);
	$pdf->addText(350,790,10,"<b>Created</b>"); $pdf->addText(460,790,10,$DateCreated);
	$pdf->addText(350,770,10,"<b>Technician</b>"); $pdf->addText(460,770,10, $ShowEmployee);
	
	//BOTTOM
	$pdf->addText(490,20,8,"Jobcard Number: " . $JobcardNumber);
	
	$data = array();
	
	$pdf->ezText("Jobcard Details",10,array('aleft'=> 20));
	$pdf->ezSetDy(-10);
	
	$data[] = array('<b>Client Details</b>'=>'<b>'  . $ShowClient . '</b>','<b>Our Details</b>'=>'<b>' . $DisplayCompany . '</b>','<b>Client Address</b>'=>$CustomerAddress1);
	$data[] = array('<b>Client Details</b>'=>'Tel: ' . $CustomerTel,'<b>Our Details</b>'=>'Tel : ' . $DisplayTel,'<b>Client Address</b>'=>$CustomerAddress2);
	$data[] = array('<b>Client Details</b>'=>'Fax: N/A','<b>Our Details</b>'=>'Fax : ' . $DisplayFax,'<b>Client Address</b>'=>$CustomerCity);
	$data[] = array('<b>Client Details</b>'=>'Email : ' . $CustomerEmail,'<b>Our Details</b>'=>'Email : '. $DisplayEmail,'<b>Client Address</b>'=>$CustomerRegion);
	$data[] = array('<b>Client Details</b>'=>'','<b>Our Details</b>'=>'Added By : ' . $AddedBy,'<b>Client Address</b>'=>$CustomerPostCode);
	$data[] = array('<b>Client Details</b>'=>'VAT Number : ' . $CustomerVAT,'<b>Our Details</b>'=>'VAT Number : ' . $DisplayVat,'<b>Client Address</b>'=>'');

	
	
	$pdf->ezTable($data,'','',array('shaded'=>0,'fontSize' => 8,'xPos'=>580,'xOrientation'=>'left','width'=>550,'showLines'=>0,'cols'=>array(
	'<b>QTY</b>'=>array('width'=>40)
	,'<b>Product</b>'=>array('width'=>250),'<b>Rate</b>'=>array('justification'=>'right'),'<b>VAT Amt</b>'=>array('justification'=>'right'),'<b>Amount</b>'=>array('justification'=>'right'))));
	
	
	$pdf->ezSetDy(-20);
	
	
	//NOW GET DYNAMIC TABLES
	$GetTables = "SELECT * FROM jobcardtables ORDER BY TablePosition ASC";
	$GotTables = mysqli_query($ClientCon, $GetTables);
	
	
	$ThisTotalLine = array();
	
	while ($Val = mysqli_fetch_array($GotTables))
	{
		$TableID = $Val["JobcardTableID"];
		$TableHeading = $Val["TableHeading"];
		$ShowHeading = $Val["ShowHeading"];
		$ShowLines = $Val["ShowLines"];
		$ShowTableHeadings = $Val["ShowTableHeadings"];
		
		if ($ShowLines == 1)
		{
			$ShowLines = 2;	
		}
		
		$data = array();
		
		if ($ShowHeading == 1)
		{
			$pdf->ezText($TableHeading,10,array('aleft'=> 20));	
			$pdf->ezSetDy(-10);
		}
		
		$GetFields = "SELECT * FROM 	jobcardfields WHERE jobcardfields.JobcardTableID = {$TableID} ORDER BY Position";
		$GotFields = mysqli_query($ClientCon, $GetFields);
		$NumFields = mysqli_num_rows($GotFields);
		$X = 0;
		
		//FIRST GET OUR LINES
		//THEN GET LINES
		$GetLines = "SELECT JobcardInputLineID FROM jobcardinputlines WHERE JobcardTableID = {$TableID}";
		$GotLines = mysqli_query($ClientCon, $GetLines);
		
		
		while ($Val4 = mysqli_fetch_array($GotLines))
		{
			$ThisLine = array();
			$LineID = $Val4["JobcardInputLineID"];
			$BuildLine = "";
		
		
			//NOW GET ALL THE LINES
			$GetLineValues = "SELECT * FROM jobcardinputlinevalues, jobcardfields WHERE JobcardInputLineID = {$LineID} AND jobcardinputlinevalues.JobcardTableID = {$TableID} AND jobcardinputlinevalues.JobcardFieldID = jobcardfields.JobcardFieldID";
			$GotLineValues = mysqli_query($ClientCon, $GetLineValues);
	
			
			while ($Val5 = mysqli_fetch_array($GotLineValues))
			{
					$ThisField = $Val5["FieldName"];
					$ThisValue = $Val5["InputValue"];
			
						
					//THEN PUSH TO ARRAY
					//array_push($ThisLine, array($ThisField=>$InputValue));
					$BuildLine .= $ThisField . "=>" . $ThisValue . ",";
					
			}
			
			//HERE WE GO TO NEXT LINE
			
			//array_push($ThisLine, $BuildLine);
			$BuildLine = rtrim($BuildLine, ",");
			$BuildLineArray = explode(",", $BuildLine);
			
			//NOW WE HAVE A BROKEN UP ARRAY
			$X = 0;
			foreach ($BuildLineArray as $LineItem) 
			{
   				 $LineItemArray = explode("=>", $LineItem);
				 $ThisItem = $LineItemArray[0];
				 $ThisValue = $LineItemArray[1];
				 
				 $ItemArray[$X]["Heading"] = $ThisItem;
				 $ItemArray[$X]["Value"] = $ThisValue;
				 
				$X++;
			}
			
			
			if ($NumFields == 6)
			{
				$data[] = array('<b>' . $ItemArray[0]["Heading"] . '</b>'=>$ItemArray[0]["Value"],'<b>' . $ItemArray[1]["Heading"] . '</b>'=>$ItemArray[1]["Value"],'<b>' . $ItemArray[2]["Heading"] . '</b>'=>$ItemArray[2]["Value"],'<b>' . $ItemArray[3]["Heading"] . '</b>'=>$ItemArray[3]["Value"],'<b>' . $ItemArray[4]["Heading"] . '</b>'=>$ItemArray[4]["Value"],'<b>' . $ItemArray[5]["Heading"] . '</b>'=>$ItemArray[5]["Value"]);
			}
			else if ($NumFields == 5)
			{
				$data[] = array('<b>' . $ItemArray[0]["Heading"] . '</b>'=>$ItemArray[0]["Value"],'<b>' . $ItemArray[1]["Heading"] . '</b>'=>$ItemArray[1]["Value"],'<b>' . $ItemArray[2]["Heading"] . '</b>'=>$ItemArray[2]["Value"],'<b>' . $ItemArray[3]["Heading"] . '</b>'=>$ItemArray[3]["Value"],'<b>' . $ItemArray[4]["Heading"] . '</b>'=>$ItemArray[4]["Value"]);
			}
			else if ($NumFields == 4)
			{
				$data[] = array('<b>' . $ItemArray[0]["Heading"] . '</b>'=>$ItemArray[0]["Value"],'<b>' . $ItemArray[1]["Heading"] . '</b>'=>$ItemArray[1]["Value"],'<b>' . $ItemArray[2]["Heading"] . '</b>'=>$ItemArray[2]["Value"],'<b>' . $ItemArray[3]["Heading"] . '</b>'=>$ItemArray[3]["Value"]);
			}
			else if ($NumFields == 3)
			{
				$data[] = array('<b>' . $ItemArray[0]["Heading"] . '</b>'=>$ItemArray[0]["Value"],'<b>' . $ItemArray[1]["Heading"] . '</b>'=>$ItemArray[1]["Value"],'<b>' . $ItemArray[2]["Heading"] . '</b>'=>$ItemArray[2]["Value"]);
			}
			else if ($NumFields == 2)
			{
				$data[] = array('<b>' . $ItemArray[0]["Heading"] . '</b>'=>$ItemArray[0]["Value"],'<b>' . $ItemArray[1]["Heading"] . '</b>'=>$ItemArray[1]["Value"]);
			}
			else if ($NumFields == 1)
			{
				$data[] = array('<b>' . $ItemArray[0]["Heading"] . '</b>'=>$ItemArray[0]["Value"]);
			}
			
			
			
			//echo $BuildLine . "<br><br>";
			//$data[] = array('<b>Client Details</b>'=>'VAT Number : ' . $SupplierVat,'<b>Our Details</b>'=>'VAT Number : ' . $DisplayVat,'<b>Delivery Address</b>'=>'');
			
			//array_push($data, explode(",", $BuildLine));
			
			
		}
		
		
		//print_r($ThisTotalLine);
		
	
		
		
		$pdf->ezTable($data,'','',array('shaded'=>0,'fontSize' => 8,'xPos'=>580,'xOrientation'=>'left','width'=>550,'showLines'=>$ShowLines,'showHeadings'=>$ShowTableHeadings));
	
		$pdf->ezSetDy(-10);
		
		
	}
	
	
	
	
	
	
	if ($JobcardNotes != "")
		{
			$pdf->ezNewPage();
			
			
		$pdf->addText(350,810,10,"<b>Jobcard Number</b>"); $pdf->addText(460,810,10,$JobcardNumber);
		$pdf->addText(350,790,10,"<b>Created</b>"); $pdf->addText(460,790,10,$DateCreated);
		$pdf->addText(350,770,10,"<b>Technician</b>"); $pdf->addText(460,770,10, $ShowEmployee);
		//BOTTOM
		$pdf->addText(490,20,8,"Jobcard Number: " . $JobcardNumber);
		
			$pdf->addText(20, 720,12, '<b>Notes</b>');
			
			$pdf->ezSetDy(-20);
			
			$data = array();
			
			$data[] = array('<b>Notes</b>'=>$JobcardNotes);
			
			$pdf->ezTable($data,'','',array('shaded'=>0,'xPos'=>550,'xOrientation'=>'left','showLines'=>0,'showHeadings'=>0,'width'=>520,'rowGap'=>3,
			'fontSize'=>8,'cols'=>array('<b>Rate</b>'=>array('justification'=>'right'),'<b>VAT Amt</b>'=>array('justification'=>'right'),'<b>Description2</b>'=>array('justification'=>'left'))));
		}
	
	
	
							
	
	
		
	//$pdfcode = $pdf->output();
	$pdf->ezStream();
	
}
?>

