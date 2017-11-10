<?php
session_start();

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
set_time_limit(1800);

$ThisClientID = $_SESSION["ClientID"];
$ThisUser = $_SESSION["ClientName"];
$ThisSecret = $_SESSION["SiteSecret"];

if ($ThisClientID > 0 && $ThisUser != "" && $ThisSecret == "E2A_crm_S5gdbh6nnj_usr_9898") {


    $CustomerID = $_REQUEST["c"];

    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCustomer = "SELECT * FROM customers, countries WHERE CustomerID = {$CustomerID}
	AND customers.CountryID = countries.CountryID";
    $GotCustomer = mysqli_query($ClientCon, $GetCustomer);

    while ($Val = mysqli_fetch_array($GotCustomer)) {
        $Name = $Val["FirstName"];
        $Surname = $Val["Surname"];
        $CompanyName = $Val["CompanyName"];

        if ($CompanyName != "") {
            $TopCompanyName = $CompanyName . " ( " . $Name . " " . $Surname . " )";
        }

        $EmailAddress = $Val["EmailAddress"];
        $DateAdded = $Val["DateAdded"];

        $ThisStatus = $Val["Status"];

        $CustomerAddress1 = $Val["Address1"];
        $CustomerAddress2 = $Val["Address2"];
        $CustomerCity = $Val["City"];
        $CustomerRegion = $Val["Region"];
        $CustomerPostCode = $Val["PostCode"];
        $CustomerCountryName = $Val["CountryName"];
        $ContactNumber = $Val["ContactNumber"];
        $ClientCountryID = $Val["CountryID"];

        $TaxExempt = $Val["TaxExempt"];
        $OverdueNotices = $Val["OverdueNotices"];
        $MarketingEmails = $Val["MarketingEmails"];
        $PaymentMethod = $Val["PaymentMethod"];
        $VatNumber = $Val["VatNumber"];
        $AdminNotes = $Val["AdminNotes"];

        $ThisResellerID = $Val["ResellerID"];
        $creadit_amount = $Val["creadit_amount"];
        if ($VatNumber == "") {
            $VatNumber = 'None';
        }
    }

    //GET LOGO FROM COMPANY SETUP, MUST BE JPG
    $GetInvoiceLogo = "SELECT * FROM companysettings";
    $GotInvoiceLogo = mysqli_query($ClientCon, $GetInvoiceLogo);


    while ($Val = mysqli_fetch_array($GotInvoiceLogo)) {

        $CompanyLogo = $Val["InvoiceLogo"];
        $Address1 = $Val["Address1"];
        $Address2 = $Val["Address2"];
        $City = $Val["City"];
        $Region = $Val["Region"];
        $PostCode = $Val["PostCode"];
        $CountryID = $Val["CountryID"];
        $DisplayCompany = $Val["InvoiceDisplayCompany"];
        $DisplayEmail = $Val["InvoiceDisplayEmail"];
        $DisplayTel = $Val["InvoiceDisplayTel"];
        $DisplayFax = $Val["InvoiceDisplayFax"];
        $DisplayVat = $Val["VatNumber"];

        if ($DisplayFax == "") {
            $DisplayFax = 'None';
        }

        if ($DisplayVat == "") {
            $DisplayVat = 'None';
        }

        $BankName = $Val["BankName"];
        $AccountHolder = $Val["AccountHolder"];
        $AccountNumber = $Val["AccountNumber"];
        $BranchCode = $Val["BranchCode"];
        $AccountType = $Val["AccountType"];


    }

    if ($CountryID != "") {
        $GetCountry = "SELECT * FROM countries WHERE CountryID = {$CountryID}";
        $GotCountry = mysqli_query($ClientCon, $GetCountry);

        while ($Val = mysqli_fetch_array($GotCountry)) {
            $CountryName = $Val["CountryName"];
        }
    }


    $ThisQuoteCompany = $SupplierName;
    if ($CompanyLogo != "") {
        $CompanyLogo = "images/" . $CompanyLogo;
    }

    $FromDate = $_REQUEST["from"];
    $ToDate = $_REQUEST["to"];

    //NEW PDF CREATION SCRIPT//////////////////////////////////////////////
    include 'ezpdf/class.ezpdf.php';

    error_reporting(E_ALL ^ E_NOTICE);
    //set_time_limit(1800);

    $pdf = new Cezpdf('a4', 'portrait');
    $pdf->ezSetMargins(130, 70, 50, 50);

    // put a line top and bottom on all the pages
    $all = $pdf->openObject();
    $pdf->saveState();
    $pdf->setStrokeColor(0, 0, 0, 1);
    $pdf->line(20, 40, 578, 40);
    if ($CompanyLogo != "") {
        $pdf->addJpegFromFile($CompanyLogo, 20, 760, 150);
        $pdf->addJpegFromFile($CompanyLogo, 20, 5, 60);
    }


    $pdf->restoreState();
    $pdf->closeObject();
    // note that object can be told to appear on just odd or even pages by changing 'all' to 'odd'
    // or 'even'.
    $pdf->addObject($all, 'all');

    //$mainFont = 'fonts/Helvetica.afm';
    $mainFont = 'ezpdf/fonts/Helvetica.afm';
    $codeFont = 'ezpdf/fonts/Helvetica.afm';
    // select a font
    $pdf->selectFont($mainFont);
    //$pdf->ezStartPageNumbers(550,20,10,'right','',1);
    $pdf->openHere('Fit');

    $pdf->addText(350, 810, 9, "<b>Client Statement</b>");
    $pdf->addText(450, 810, 9, $CompanyName);
    $pdf->addText(350, 790, 9, "<b>From Date</b>");
    $pdf->addText(450, 790, 9, $FromDate);
    $pdf->addText(350, 770, 9, "<b>To Date</b>");
    $pdf->addText(450, 770, 9, $ToDate);


    //BOTTOM
    $pdf->addText(520, 20, 8, "Client Statement");

    $data = array();

    $pdf->ezText("Statement Details", 10, array('aleft' => 20));
    $pdf->ezSetDy(-10);

    $data[] = array('<b>Customer Details</b>' => '<b>' . $CompanyName . '</b>', '<b>Our Details</b>' => '<b>' . $DisplayCompany . '</b>', '<b>Banking Details</b>' => 'Bank : ' . $BankName);
    $data[] = array('<b>Customer Details</b>' => 'Tel: ' . $ContactNumber, '<b>Our Details</b>' => 'Tel : ' . $DisplayTel, '<b>Banking Details</b>' => 'Account Holder : ' . $AccountHolder);
    $data[] = array('<b>Customer Details</b>' => 'Email : ' . $EmailAddress, '<b>Our Details</b>' => 'Email : ' . $DisplayEmail, '<b>Banking Details</b>' => 'Account Number : ' . $AccountNumber);
    $data[] = array('<b>Customer Details</b>' => 'VAT Number : ' . $VatNumber, '<b>Our Details</b>' => 'VAT Number : ' . $DisplayVat, '<b>Banking Details</b>' => 'Branch Code : ' . $BranchCode);
    $data[] = array('<b>Customer Details</b>' => '', '<b>Our Details</b>' => '', '<b>Banking Details</b>' => 'Account Type : ' . $AccountType);
    $data[] = array('<b>Customer Details</b>' => '<b>Address</b>', '<b>Our Details</b>' => '<b>Address</b>', '<b>Banking Details</b>' => '');
    $data[] = array('<b>Customer Details</b>' => $CustomerAddress1, '<b>Our Details</b>' => $Address1, '<b>Banking Details</b>' => '');
    $data[] = array('<b>Customer Details</b>' => $CustomerAddress2, '<b>Our Details</b>' => $Address2, '<b>Banking Details</b>' => '');
    $data[] = array('<b>Customer Details</b>' => $CustomerCity, '<b>Our Details</b>' => $City, '<b>Banking Details</b>' => '');
    $data[] = array('<b>Customer Details</b>' => $CustomerRegion, '<b>Our Details</b>' => $Region, '<b>Banking Details</b>' => '');
    $data[] = array('<b>Customer Details</b>' => $CustomerCountryName, '<b>Our Details</b>' => $CountryName, '<b>Banking Details</b>' => '');
    $data[] = array('<b>Customer Details</b>' => $CustomerPostCode, '<b>Our Details</b>' => $PostCode, '<b>Banking Details</b>' => '');


    $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 7, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'showLines' => 0, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));


    $pdf->ezSetDy(-20);


    $pdf->ezText("Statement " . $FromDate . " - " . $ToDate, 10, array('aleft' => 20));

    $pdf->ezSetDy(-10);


    $OpeningBalance = GetCustomerOpeningStatement($FromDate, $ToDate, $CustomerID);
    //NOW WE NEED TO GET DEBITS AND CREDITS - THIS IS AN ARRAY
    $CustomerStatementArray = GetCustomerStatement($FromDate, $ToDate, $CustomerID);


    $data = array();

    if ($OpeningBalance >= 0) {
        $DebitTotal = $DebitTotal + $OpeningBalance;
        $data[] = array('<b>Date</b>' => $FromDate, '<b>Reference</b>' => 'OB', '<b>Description</b>' => 'Opening Balance', '<b>Debit</b>' => 'R' . number_format($OpeningBalance, 2), '<b>Credit</b>' => '');
    } else {
        $OpeningBalance = $OpeningBalance * -1;
        $CreditTotal = $CreditTotal + $OpeningBalance;
        $data[] = array('<b>Date</b>' => $FromDate, '<b>Reference</b>' => 'OB', '<b>Description</b>' => 'Opening Balance', '<b>Debit</b>' => '', '<b>Credit</b>' => 'R' . number_format($OpeningBalance, 2));
    }
    $InvoiceID = array();
    foreach ($CustomerStatementArray as $TransactionLine) {
        $ThisDate = $TransactionLine["Date"];
        $ThisReference = $TransactionLine["Reference"];
        $ThisDescription = $TransactionLine["Description"];
        $ThisCredit = $TransactionLine["Credit"];
        $ThisDebit = $TransactionLine["Debit"];
        $InvoiceID[] = $TransactionLine["InvoiceID"];
        $DebitTotal = $DebitTotal + $ThisDebit;
        $CreditTotal = $CreditTotal + $ThisCredit;


        if ($ThisDate != "") {
            if ($ThisDebit != "") {
                $data[] = array('<b>Date</b>' => $ThisDate, '<b>Reference</b>' => $ThisReference, '<b>Description</b>' => $ThisDescription, '<b>Debit</b>' => 'R' . number_format($ThisDebit, 2), '<b>Credit</b>' => '');
            } else {
                //ITS A CREDIT LINE
                $data[] = array('<b>Date</b>' => $ThisDate, '<b>Reference</b>' => $ThisReference, '<b>Description</b>' => $ThisDescription, '<b>Debit</b>' => '', '<b>Credit</b>' => 'R' . number_format($ThisCredit, 2));
            }
        }
    }
    if (!empty($InvoiceID)) {
        $strInvoiceID = implode(",", $InvoiceID);
        $transactions = GetInvoiceTransactionData($strInvoiceID);

        $allCreaditTotal = 0;
        for ($i = 0; $i < count($transactions); $i++) {
            $data[] = array('<b>Date</b>' => $transactions[$i]['Date'],
                '<b>Reference</b>' => $transactions[$i]['Reference'],
                '<b>Description</b>' => $transactions[$i]['Description'],
                '<b>Debit</b>' => '', '<b>Credit</b>' => 'R' . number_format($transactions[$i]['Credit'], 2));
            $allCreaditTotal += $transactions[$i]['Credit'];
        }

    }


    $data[] = array('<b>Date</b>' => '', '<b>Reference</b>' => '', '<b>Description</b>' => '', '<b>Debit</b>' => '', '<b>Credit</b>' => '');
    $data[] = array('<b>Date</b>' => '', '<b>Reference</b>' => '', '<b>Description</b>' => '<b>Totals</b>', '<b>Debit</b>' => '<b>R' . number_format($DebitTotal, 2) . '</b>', '<b>Credit</b>' => '<b>R' . number_format($allCreaditTotal, 2) . '</b>');
    if ($creadit_amount > 0) {

        $data[] = array('<b>Date</b>' => '', '<b>Reference</b>' => '', '<b>Description</b>' => '<b>Credit Amount</b>', '<b>Debit</b>' => '', '<b>Credit</b>' => '<b>R' . number_format($creadit_amount, 2) . '</b>');

    }
    $data[] = array('<b>Date</b>' => '', '<b>Reference</b>' => '', '<b>Description</b>' => '', '<b>Debit</b>' => '', '<b>Credit</b>' => '');



    //$AccountBalance = $DebitTotal - $CreditTotal;
    $AccountBalance = $DebitTotal - $allCreaditTotal - $creadit_amount ;
    if ($AccountBalance > 0) {
        $data[] = array('<b>Date</b>' => '', '<b>Reference</b>' => '', '<b>Description</b>' => '<b>Closing balance on ' . $ToDate . '</b>', '<b>Debit</b>' => '<b>R' . number_format($AccountBalance, 2) . '</b>', '<b>Credit</b>' => '');
    } else {
        $AccountBalance = $AccountBalance * -1;
        $data[] = array('<b>Date</b>' => '', '<b>Reference</b>' => '', '<b>Description</b>' => '<b>Closing balance on ' . $ToDate . '</b>', '<b>Debit</b>' => '', '<b>Credit</b>' => '<b>R' . number_format($AccountBalance, 2) . '</b>');
    }


    $pdf->ezTable($data, '', '', array('shaded' => 1, 'fontSize' => 7, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));

    $pdf->ezSetDy(-20);


    //$pdfcode = $pdf->output();
    $pdf->ezStream();

}

//CUSTOMER STATEMENT
function GetCustomerOpeningStatement($FromDate, $ToDate, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    //FIRST WE NEED TO GET OPENING BALANCE
    /*$GetOpening = "SELECT SUM(LineTotal) AS TotalDebits FROM customerinvoices, customerinvoicelines WHERE customerinvoicelines.InvoiceID = customerinvoices.InvoiceID AND customerinvoices.CustomerID = {$CustomerID}  AND InvoiceDate < '{$FromDate}' AND InvoiceStatus != 0 AND InvoiceStatus != 3";*/

     $GetOpening = "SELECT SUM(LineTotal) AS TotalDebits FROM customerinvoices, customerinvoicelines
	  WHERE customerinvoicelines.InvoiceID = customerinvoices.InvoiceID
	  AND customerinvoices.CustomerID = {$CustomerID}
	  AND InvoiceDate < '{$FromDate}' AND InvoiceStatus IN (1,6) GROUP BY customerinvoices.InvoiceID ";

    $GotOpening = mysqli_query($ClientCon, $GetOpening);
    $FoundOpening = mysqli_num_rows($GotOpening);
    $TotalDebits = 0;
    if ($FoundOpening > 0) {
        while ($Val = mysqli_fetch_array($GotOpening)) {
            $TotalDebits += $Val["TotalDebits"];
        }
    } else {
        $TotalDebits = 0;
    }

    //NOW PAYMENTS

    $GetPayments = "SELECT SUM(TotalPayment) AS TotalCredits FROM customertransactions WHERE PaymentDate < '{$FromDate}' AND PaymentDate > '{$ToDate}' AND CustomerID = {$CustomerID}";

    $GotPayments = mysqli_query($ClientCon, $GetPayments);
    $FoundPayments = mysqli_num_rows($GotPayments);
    $TotalCredits = 0;
    if ($FoundPayments > 0) {
        while ($Val = mysqli_fetch_array($GotPayments)) {
            $TotalCredits += $Val["TotalCredits"];
        }
    } else {
        $TotalCredits = 0;
    }

    $OpeningBalance = $TotalDebits - $TotalCredits;

    return $OpeningBalance;


}

function GetCustomerStatement($FromDate, $ToDate, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $X = 0;

    while (strtotime($FromDate) <= strtotime($ToDate)) {
        //FIRST GET INVOICES
        $GetInvoices = "SELECT SUM(LineTotal) AS TotalDebits, customerinvoices.* FROM customerinvoices,
	   customerinvoicelines WHERE customerinvoicelines.InvoiceID = customerinvoices.InvoiceID
	   AND customerinvoices.CustomerID = {$CustomerID}  AND InvoiceDate = '{$FromDate}'
	   AND InvoiceStatus Not IN (0,3) GROUP BY InvoiceID ORDER BY customerinvoices.InvoiceID ASC";
        $GotInvoices = mysqli_query($ClientCon, $GetInvoices);

        while ($Val = mysqli_fetch_array($GotInvoices)) {
            $InvoiceDate = $Val["InvoiceDate"];
            $InvoiceNumber = $Val["InvoiceNumber"];
            $Description = "Invoice " . $InvoiceNumber;
            $InvoiceAmount = $Val["TotalDebits"];
            $InvoiceID = $Val["InvoiceID"];

            $StatmentArray[$X]["Date"] = $InvoiceDate;
            $StatmentArray[$X]["Reference"] = $InvoiceNumber;
            $StatmentArray[$X]["Description"] = $Description;
            $StatmentArray[$X]["Debit"] = $InvoiceAmount;
            $StatmentArray[$X]["Credit"] = '';
            $StatmentArray[$X]["InvoiceID"] = $InvoiceID;


            $X++;
        }

        //THEN PAYMENTS
        //NOW PAYMENTS
        $GetPayments = "SELECT SUM(TotalPayment) AS TotalCredits FROM customertransactions WHERE PaymentDate = '{$FromDate}' AND CustomerID = {$CustomerID}";
        $GotPayments = mysqli_query($ClientCon, $GetPayments);

        while ($Val = mysqli_fetch_array($GotPayments)) {
            if (!empty($Val["PaymentDate"])) {
                $PaymentDate = $Val["PaymentDate"];
                $PaymentRef = $Val["TransactionReference"];
                $Description = $Val["Description"];
                $PaymentAmount = $Val["TotalPayment"];

                $StatmentArray[$X]["Date"] = $PaymentDate;
                $StatmentArray[$X]["Reference"] = $PaymentRef;
                $StatmentArray[$X]["Description"] = $Description;
                $StatmentArray[$X]["Debit"] = '';
                $StatmentArray[$X]["Credit"] = $PaymentAmount;

                $X++;
            }


        }


        $FromDate = date("Y-m-d", strtotime("+1 day", strtotime($FromDate)));
    }

    return $StatmentArray;


}

function GetInvoiceTransactionData($InvoiceID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $returnArray = array();

    //THEN CHECK IF ANY PAYMENTS FOR THIS INVOICE
    $CheckPayments = "SELECT SUM(PaymentAmount) AS PaidAmount,customerinvoicepayments.InvoiceID as InvoiceID,
	customertransactions.* FROM customerinvoicepayments,
	customertransactions WHERE customertransactions.TransactionID = customerinvoicepayments.TransactionID
	and customerinvoicepayments.InvoiceID IN ({$InvoiceID}) group by InvoiceID order by PaymentDate";
    $DoCheckPayments = mysqli_query($ClientCon, $CheckPayments);
    $PaidAmount = 0;
    $i = 0;
    while ($Val = mysqli_fetch_array($DoCheckPayments)) {
        $returnArray[$i]['Date'] = $Val["PaymentDate"];
        $returnArray[$i]['Reference'] = "INV" . $Val["InvoiceID"];
        $returnArray[$i]['Description'] = $Val["Description"];
        $returnArray[$i]['Credit'] = $Val["PaidAmount"];
        $i++;

    }


    return $returnArray;
}

?>

