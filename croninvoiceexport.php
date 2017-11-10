<?php

ini_set('display_errors', 'Off');

$ThisClientID = "2";
$ThisUser = "B.E. Cooling";

$dbhost="dedi763.jnb1.host-h.net";
$dbusername="becoohxjcr_1";
$dbpwd="yews26FKkp8";
$dbname="becoohxjcr_db1";

$ClientCon = mysqli_connect($dbhost, $dbusername, $dbpwd, 	$dbname);

if ($ThisUser != "")
{

    
    $GetInvoiceLogo = "SELECT * FROM companysettings";
    $GotInvoiceLogo = mysqli_query($ClientCon, $GetInvoiceLogo);


    while ($Val = mysqli_fetch_assoc($GotInvoiceLogo))
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
        $accountemail = $Val["accountemail"];

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

	//$CSVFile = "Period;Date;Acc No;Reference;Description;Amount;Tax Type;Contra Account\n";
    $TxtFile ="";

    $yesterday = date('Y-m-d', strtotime('-1 day', strtotime('now')));
	//$yesterday = date('Y-m-d');
	$Invoices = GetAllPeriodInvoices($yesterday,$ClientCon);
	//FIRST THE GROUPS
	
	
	while ($Val = mysqli_fetch_assoc($Invoices))
	{

        $periodData = getPeriodByDate($Val["InvoiceDate"],$ClientCon);
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
        $ThisInvoiceAmount = GetInvoiceTotal($InvoiceID,$ClientCon);


        // email body content
		$fullName = $Val['FirstName']." ".$Val['Surname'];
		$CompanyName  = $Val['CompanyName'];
		$EmailAddress = $Val['EmailAddress'];
		$CustomerID = $Val['CustomerID'];

		// end
        if($TaxType == 1)
            $ticked= "2";
        else
            $ticked = "1";


        $OpenItem="";
        $Project="";
        $RxchangeRate = $BankExchangeRate="1";
        $BatchID="0";
        $DiscountTax = $DiscountAmount =  "0";
		$TaxAmount=number_format($ThisInvoiceAmount['InvoiceVat'],2,".", "");


		$TxtFile .= $Period . "," . $InvoiceDate . "," .$gdc.",".$AccountNumber . "," . $reference . ",".$Description."," . number_format($ThisInvoiceAmount["InvoiceTotal"],2,".", "") .
			",".$ticked.
            ",".$TaxAmount.
            ",".$OpenItem.
            ",".$Project.
            ",".$CA.
            ",".$RxchangeRate.
            ",".$BankExchangeRate.
            ",".$BatchID.
			",".$DiscountTax.
			",".$DiscountAmount.
			",".number_format($ThisInvoiceAmount["InvoiceTotal"],2,".", "").
			"\r\n";
		
		
		
	}

	$fileName = "invoiceexport_" . $yesterday . ".txt";

    file_put_contents($fileName, $TxtFile, FILE_APPEND);

    //EMAIL MESSAGE
    $SupplierMail = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					
					</head>
					<body>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family: Arial, Helvetica, sans-serif">
						  
						  <tr>
							<td  style="font-family: Arial, Helvetica, sans-serif; font-size: 14px" valign="top">
							Dear ' . $DisplayCompany . ',<br>
							  <br>
							  Kindly find attached your invoices of ' . $yesterday . '. Please send us your POP as soon as you have settled your invoice.
							  <br><br>
							  Kind Regards<br>
							  ' . $DisplayCompany . '<br>
							  ' . $DisplayTel . '
							  </td>
							
						  </tr>
						  
						</table>
					</body>
			</html>';


    $headers = "From: " . $DisplayEmail;

    $semi_rand = md5(time());
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
    $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

    $message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"iso-8859-1\"\n" .
        "Content-Transfer-Encoding: 7bit\n\n" . $SupplierMail . "\n\n";
    $message .= "--{$mime_boundary}\n";

    //ADD POLICY FILE
	$content = file_get_contents($fileName);
    $data = chunk_split(base64_encode($content));
    $message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$fileName\"\n" .
        "Content-Disposition: attachment;\n" . " filename=\"$fileName\"\n" .
        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
    $message .= "--{$mime_boundary}\n";

    $EmailAddress = $accountemail;
    //echo $EmailAddress = "tanshu321@gmail.com";
    //$ok = @mail('alex@allweb.co.za', "Invoice - " . $InvoiceNumber, $message, $headers);

    mail($EmailAddress, "Invoices on - " . $yesterday, $message, $headers);
	
	mail("tanshu321@gmail.com,ben@e2a.co.za","Cron run - ".date('Y-m-d H:i'),$message, $headers);
	
	echo "sent";
    unlink($fileName);
	//echo $CSVFile;
	
}

function GetInvoiceTotal($InvoiceID,$ClientCon)
{
	$returnArray=array();
	//$ClientCon = mysqli_connect($dbhost, $dbusername, $dbpwd, 	$dbname);
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

function GetAllPeriodInvoices($yesterday,$ClientCon)
{
    //$ClientCon = mysqli_connect($dbhost, $dbusername, $dbpwd, 	$dbname);

    $GetInvoicing = "SELECT * FROM customers, customerinvoices WHERE customers.CustomerID = customerinvoices.CustomerID AND InvoiceStatus IN (1,2,6) AND PublishDate = '{$yesterday}' ORDER BY InvoiceID ASC";

	
    $GotInvoicing = mysqli_query($ClientCon, $GetInvoicing);

    return $GotInvoicing;
}

/**
 *
 */
function getPeriodByDate($invoiceDate = '',$ClientCon){
    $returnData = array();
   // $ClientCon = mysqli_connect($dbhost, $dbusername, $dbpwd, 	$dbname);

    if(!empty($invoiceDate)) {
        $nmonth = date('m', strtotime($invoiceDate));
        $GetPeriod = "SELECT * FROM periodsetup where month = '$nmonth'";
        $GotPeriod = mysqli_query($ClientCon, $GetPeriod);

        $returnData = mysqli_fetch_assoc($GotPeriod);

    }
    return $returnData;
}
?>

