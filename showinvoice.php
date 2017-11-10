<?php
session_start();

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
set_time_limit(1800);

$ThisClientID = $_SESSION["ClientID"];
$ThisUser = $_SESSION["ClientName"];
$ThisSecret = $_SESSION["SiteSecret"];

if ($ThisClientID > 0 && $ThisUser != "" && $ThisSecret == "E2A_crm_S5gdbh6nnj_usr_9898") {

    $InvoiceID = $_REQUEST["i"];
    $CustomerID = $_REQUEST["c"];

    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCustomer = "SELECT * FROM customers, countries WHERE CustomerID = {$CustomerID} AND customers.CountryID = countries.CountryID";
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
        $DepositReference = $Val["DepositReference"];

        $ThisResellerID = $Val["ResellerID"];

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
        $DisplayVat = $Val["VATNumber"];
        $CompanyReg = $Val["CompanyRegistration"];

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

    //THEN GET PO DETAILS
    $GetInvoice = "SELECT * FROM customerinvoices WHERE InvoiceID = {$InvoiceID} AND CustomerID = {$CustomerID}";

    $GotInvoice = mysqli_query($ClientCon, $GetInvoice);
    $InvoiceAdditional = "";
    while ($Val = mysqli_fetch_array($GotInvoice)) {
        $InvoiceNumber = $Val["InvoiceNumber"];
        $InvoiceDate = $Val["InvoiceDate"];
        $DueDate = $Val["DueDate"];
        $AddedByName = $Val["AddedByName"];
        $InvoiceNotes = $Val["InvoiceNotes"];
        $additionalnotes = $Val["additionalnotes"];
        $InvoiceStatus = $Val["InvoiceStatus"];

        //LETS SERR IF THIS INVOICE HAS JOBCARD ASSOCIATED
        $CheckLinkJob = "SELECT * FROM jobcards WHERE InvoiceID = {$InvoiceID}";
        $DoCheckLinkJob = mysqli_query($ClientCon, $CheckLinkJob);

        while ($ValJob = mysqli_fetch_array($DoCheckLinkJob)) {
            $ManualJobcardNumber = $ValJob["ManualJobcardNumber"];
            $WorkOrder = $ValJob["WorkOrder"];

            if ($ManualJobcardNumber != "") {
                $InvoiceAdditional .= "Job Card Number : " . $ManualJobcardNumber . "\n";
            }

            if ($WorkOrder != "") {
                $InvoiceAdditional .= "Work Order Number : " . $WorkOrder . "\n";
            }

        }

    }
    $InvoiceNotes = $InvoiceAdditional . $InvoiceNotes;

    $GetGroups = "SELECT * FROM customerinvoicegroups WHERE InvoiceID = {$InvoiceID} AND InvoiceGroupID IN (SELECT GroupID FROM customerinvoicelines WHERE InvoiceID = {$InvoiceID})";
    $GotGroups = mysqli_query($ClientCon, $GetGroups);

    //AND THEN THE LINES
    $GetLines = "SELECT * FROM customerinvoicelines WHERE InvoiceID = {$InvoiceID} AND GroupID = 0";
    $GotLines = mysqli_query($ClientCon, $GetLines);

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


    if ($InvoiceStatus == 3) {
        $pdf->addJpegFromFile("images/inv-canceled.jpg", 355, 695, 120);
    }



    $pdf->restoreState();
    $pdf->closeObject();
    $pdf->addLink("http://www.paypal.com/",50,100,500,120);
    $pdf->addJpegFromFile("images/paynow.jpg", 250, 100, 100);
    //$pdf->rectangle(50,100,450,20);
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

    /////
    $pdf->addText(230, 810, 10, $Address1);
    $pdf->addText(230, 795, 10, $Address2);
    $pdf->addText(230, 780, 10, $City);
    $pdf->addText(230, 765, 10, $Region);
    $pdf->addText(230, 750, 10, $CountryName . " - " . $PostCode);
    //$pdf->addText(250,735,10,$PostCode);
/////////

    /*$pdf->addText(380,810,10,"<b>TAX Invoice</b>");
    $pdf->addText(380,790,10,"<b>Invoice Number</b>"); $pdf->addText(480,790,10,$InvoiceNumber);
    $pdf->addText(380,770,10,"<b>Invoice Date</b>"); $pdf->addText(480,770,10,$InvoiceDate);
    $pdf->addText(380,750,10,"<b>Due Date</b>"); $pdf->addText(480,750,10,$DueDate);
    $pdf->addText(380,730,10,"<b>Client Code</b>"); $pdf->addText(480,730,10,$DepositReference);*/
    $pdf->addText(360, 810, 10, "<b>Tel: </b>");
    $pdf->addText(430, 810, 10, $DisplayTel);
    $pdf->addText(360, 795, 10, "<b>Email: 	</b>");
    $pdf->addText(430, 795, 10, $DisplayEmail);
    $pdf->addText(360, 780, 10, "<b>VAT Number: </b>");
    $pdf->addText(430, 780, 10, $DisplayVat);
    $pdf->addText(360, 765, 10, "<b>Company Reg: </b>");
    $pdf->addText(430, 765, 10, $CompanyReg);


    //BOTTOM
    $pdf->addText(90, 25, 8, "<b>Banking Details</b> Account Holder: " . $AccountHolder);
    $pdf->addText(90, 16, 8, "Account Number: "
        . $AccountNumber . ", Branch Code:" . $BranchCode . ", Account Type: " . $AccountType);
    $pdf->addText(90, 7, 8, "Deposit Reference: " . $DepositReference);
    $pdf->addText(490, 25, 8, "Invoice Number " . $InvoiceNumber);


    $data = array();

    $pdf->ezText("Invoice Details", 10, array('aleft' => 20));
    $pdf->line(20, 690, 580, 690);
    $pdf->line(20, 600, 580, 600);
    /*$pdf->ezText("______________________________________________________________________________________________",10,array('aleft'=> 20));*/
    $pdf->ezSetDy(-10);

    $data[] = array('<b>CUSTOMER DETAILS</b>' => '<b>' . strtoupper($CompanyName) . '</b>', '<b>ADDRESS</b>' => $CustomerAddress1, '<b>TAX INVOICE</b>' => '<b>Invoice Number : ' . $InvoiceNumber . "</b>");

    $data[] = array('<b>CUSTOMER DETAILS</b>' => 'Tel: ' . $ContactNumber, '<b>ADDRESS</b>' => $CustomerAddress2, '<b>TAX INVOICE</b>' => 'Invoice Date : ' . $InvoiceDate);
    $data[] = array('<b>CUSTOMER DETAILS</b>' => 'Email : ' . $EmailAddress, '<b>ADDRESS</b>' => $CustomerCity . ", " . $CustomerRegion, '<b>TAX INVOICE</b>' => 'Due Date : ' . $DueDate);
    $data[] = array('<b>CUSTOMER DETAILS</b>' => 'VAT Number : ' . $VatNumber, '<b>ADDRESS</b>' => $CustomerCountryName . " - " . $CustomerPostCode, '<b>TAX INVOICE</b>' => 'Client Code : ' . $DepositReference);
    $data[] = array('<b>CUSTOMER DETAILS</b>' => '', '<b>ADDRESS</b>' => '', '<b>TAX INVOICE</b>' => '');
    /*$data[] = array('<b>CUSTOMER DETAILS</b>'=>'<b>ADDRESS</b>','<bADDRESS</b>'=>'<b>ADDRESSs</b>', '');
    $data[] = array('<b>CUSTOMER DETAILS</b>'=>$CustomerADDRESS1,'<b>Our Details</b>'=>$Address1, '<b>Banking Details</b>'=>'');
    $data[] = array('<b>CUSTOMER DETAILS</b>'=>$CustomerAddress2,'<b>Our Details</b>'=>$Address2, '<b>Banking Details</b>'=>'');
    $data[] = array('<b>CUSTOMER DETAILS</b>'=>$CustomerCity,'<b>Our Details</b>'=>$City, '<b>Banking Details</b>'=>'');
    $data[] = array('<b>CUSTOMER DETAILS</b>'=>$CustomerRegion,'<b>Our Details</b>'=>$Region, '<b>Banking Details</b>'=>'');
    $data[] = array('<b>CUSTOMER DETAILS</b>'=>$CustomerCountryName,'<b>Our Details</b>'=>$CountryName, '<b>Banking Details</b>'=>'');
    $data[] = array('<b>CUSTOMER DETAILS</b>'=>$CustomerPostCode,'<b>Our Details</b>'=>$PostCode, '<b>Banking
    Details</b>'=>'');*/


    if ($TaxExempt == 0) {
        $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 10, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'showLines' => 0, 'cols' => array(
            '<b>QTY</b>' => array('width' => 40)
        , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));
    } else {
        $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 10, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'showLines' => 0, 'cols' => array(
            '<b>QTY</b>' => array('width' => 40)
        , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));
    }

    /* $pdf->ezText("______________________________________________________________________________________________",10,array('aleft'=> 20));*/


    //NOW LETS ADD CUSTOM FIELDS HERE AS WELL
    $GetCustomFieldsInvoice = "SELECT * FROM customercustomfields WHERE DisplayInvoice = 1 ORDER BY DisplayOrder";
    $GotCustomFieldsInvoice = mysqli_query($ClientCon, $GetCustomFieldsInvoice);

    $FoundCustom = mysqli_num_rows($GotCustomFieldsInvoice);

    if ($FoundCustom > 0) {
        $pdf->ezSetDy(-10);

        $pdf->ezText("Customer Additional Information", 10, array('aleft' => 20));

        $pdf->ezSetDy(-10);

        $data = array();

        //LETS TRY PUT 2 IN A ROW FOR THIS, 4x Spaces NO TABLE HEADINGS, BUILD AN ARRAY WE CAN LOOP THROUGH IN THE END
        $X = 0;

        while ($Val = mysqli_fetch_array($GotCustomFieldsInvoice)) {
            $CustomFieldName = $Val["CustomFieldName"];
            $CustomFieldID = $Val["CustomFieldID"];
            $CustomFieldType = $Val["CustomFieldType"];
            $MultiAnswer = '';

            if ($CustomFieldType == "text" || $CustomFieldType == "textarea") {


                //NOW GET CLIENT VALUES FOR THIS CUSTOM FIELD - KEEP IN MIND WE CAN HAVE MULTIPLE OPTIONS HERE AS WELL
                $GetCustomValue = "SELECT * FROM customercustomentries WHERE CustomFieldID = {$CustomFieldID} AND CustomerID = {$CustomerID}";
                $GotCustomValue = mysqli_query($ClientCon, $GetCustomValue);

                while ($Val2 = mysqli_fetch_array($GotCustomValue)) {
                    $CustomFieldOptionID = $Val2["CustomFieldOptionID"];
                    $CustomFieldValue = $Val2["CustomOptionValue"];


                }

                if ($CustomFieldValue != "") {
                    $DisplayHeading[$X] = $CustomFieldName;
                    $DisplayValue[$X] = $CustomFieldValue;
                    $X++;
                }


            } else if ($CustomFieldType == "checkbox") {
                //HERE WE NEED TO CHECK THE OPTIONS
                //NOW GET CLIENT VALUES FOR THIS CUSTOM FIELD - KEEP IN MIND WE CAN HAVE MULTIPLE OPTIONS HERE AS WELL
                $GetCustomValue = "SELECT * FROM customercustomentries WHERE CustomFieldID = {$CustomFieldID} AND CustomerID = {$CustomerID}";
                $GotCustomValue = mysqli_query($ClientCon, $GetCustomValue);

                while ($Val2 = mysqli_fetch_array($GotCustomValue)) {
                    $CustomFieldOptionID = $Val2["CustomFieldOptionID"];
                    $CustomFieldValue = $Val2["CustomOptionValue"];
                    $CustomFieldOptionID = $Val2["CustomFieldOptionID"];

                    if ($CustomFieldValue == "true") {
                        //GET THE SELECTED BOX
                        $GetOptionValue = "SELECT * FROM customercustomfieldsvalues WHERE CustomFieldOptionID = {$CustomFieldOptionID}";
                        $GotOptionValue = mysqli_query($ClientCon, $GetOptionValue);
                        echo mysqli_error($ClientCon);


                        while ($CustOption = mysqli_fetch_array($GotOptionValue)) {
                            $SelectedOption = $CustOption["OptionValue"];
                            $MultiAnswer .= $SelectedOption . "\n";
                        }
                    }

                }


                if (str_replace("\n", "", $MultiAnswer) != "") {
                    $DisplayHeading[$X] = $CustomFieldName;
                    $DisplayValue[$X] = $MultiAnswer;
                    $X++;
                }


            } else if ($CustomFieldType == "radio") {
                //HERE WE NEED TO CHECK THE OPTIONS
                //NOW GET CLIENT VALUES FOR THIS CUSTOM FIELD - KEEP IN MIND WE CAN HAVE MULTIPLE OPTIONS HERE AS WELL
                $GetCustomValue = "SELECT * FROM customercustomentries WHERE CustomFieldID = {$CustomFieldID} AND CustomerID = {$CustomerID}";
                $GotCustomValue = mysqli_query($ClientCon, $GetCustomValue);

                while ($Val2 = mysqli_fetch_array($GotCustomValue)) {
                    $CustomFieldOptionID = $Val2["CustomFieldOptionID"];
                    $CustomFieldValue = $Val2["CustomOptionValue"];
                    $CustomFieldOptionID = $Val2["CustomFieldOptionID"];

                    if ($CustomFieldValue == "true") {
                        //GET THE SELECTED BOX
                        $GetOptionValue = "SELECT * FROM customercustomfieldsvalues WHERE CustomFieldOptionID = {$CustomFieldOptionID}";
                        $GotOptionValue = mysqli_query($ClientCon, $GetOptionValue);
                        echo mysqli_error($ClientCon);

                        while ($CustOption = mysqli_fetch_array($GotOptionValue)) {
                            $SelectedOption = $CustOption["OptionValue"];
                            $MultiAnswer .= $SelectedOption . "\n";
                        }
                    }

                }


                if (str_replace("\n", "", $MultiAnswer) != "") {
                    $DisplayHeading[$X] = $CustomFieldName;
                    $DisplayValue[$X] = $MultiAnswer;
                    $X++;
                }


            } else if ($CustomFieldType == "select") {
                //HERE WE NEED TO CHECK THE OPTIONS
                //NOW GET CLIENT VALUES FOR THIS CUSTOM FIELD - KEEP IN MIND WE CAN HAVE MULTIPLE OPTIONS HERE AS WELL
                $GetCustomValue = "SELECT * FROM customercustomentries WHERE CustomFieldID = {$CustomFieldID} AND CustomerID = {$CustomerID}";
                $GotCustomValue = mysqli_query($ClientCon, $GetCustomValue);


                while ($Val2 = mysqli_fetch_array($GotCustomValue)) {
                    $CustomFieldOptionID = $Val2["CustomFieldOptionID"];
                    $CustomFieldValue = $Val2["CustomOptionValue"];
                    $CustomFieldOptionID = $Val2["CustomFieldOptionID"];

                    //GET THE SELECTED BOX
                    $GetOptionValue = "SELECT * FROM customercustomfieldsvalues WHERE CustomFieldOptionID = {$CustomFieldValue}";
                    $GotOptionValue = mysqli_query($ClientCon, $GetOptionValue);


                    while ($CustOption = mysqli_fetch_array($GotOptionValue)) {
                        $SelectedOption = $CustOption["OptionValue"];

                    }


                }


                if ($SelectedOption != "") {
                    $DisplayHeading[$X] = $CustomFieldName;
                    $DisplayValue[$X] = $SelectedOption;
                    $X++;
                }


            }

        }


        //NOW THAT WE LOOPED THROUGH ALL CUSTOM FIELDS, BUILD DISPLAY FOR IT
        $keys = sizeof($DisplayHeading);
        $Lines = ceil($keys / 3);

        $Y = 0;
        $Location = 0;
        for ($i = 0; $i < $Lines; ++$i) {
            $ThisHeading1 = $DisplayHeading[$Location];
            $ThisValue1 = $DisplayValue[$Location];
            $Location++;

            $ThisHeading2 = $DisplayHeading[$Location];
            $ThisValue2 = $DisplayValue[$Location];
            $Location++;

            $ThisHeading3 = $DisplayHeading[$Location];
            $ThisValue3 = $DisplayValue[$Location];
            $Location++;

            $data[] = array('<b>Custom 1</b>' => '<b>' . $ThisHeading1 . '</b>', '<b>Custom 2</b>' => '<b>' . $ThisHeading2 . '</b>', '<b>Custom 3</b>' => '<b>' . $ThisHeading3 . '</b>');
            $data[] = array('<b>Custom 1</b>' => $ThisValue1, '<b>Custom 2</b>' => $ThisValue2, '<b>Custom 3</b>' => $ThisValue3);


        }


        $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 8, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'showLines' => 0, 'showHeadings' => 0, 'cols' => array(
            '<b>Custom 123</b>' => array('width' => 250)
        , '<b>Custom 1222</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));

    }


    $data = array();

    //INVOICE NOTES
    if ($InvoiceNotes != "") {
        $pdf->ezSetDy(-10);
        $pdf->ezText("Invoice Notes", 10, array('aleft' => 20));
        $pdf->ezSetDy(-10);
        $data[] = array('<b>Invoice Notes</b>' => $InvoiceNotes);
        $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 9, 'showHeadings' => 0, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'showLines' => 0, 'cols' => array(
            '<b>QTY</b>' => array('width' => 40)
        , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));
    }


    $pdf->ezSetDy(-20);


    $pdf->ezText("Invoice Items", 10, array('aleft' => 20));

    $pdf->ezSetDy(-10);


    $InvoiceSub = 0;
    $InvoiceDiscount = 0;
    $InvoiceVat = 0;
    $InvoiceTotal = 0;


    $data = array();

    //FIRST THE GROUPS
    while ($Val = mysqli_fetch_array($GotGroups)) {
        $GroupName = $Val["GroupName"];
        $InvoiceGroupID = $Val["InvoiceGroupID"];

        $GetSubCost = "SELECT SUM(LineSubTotal) AS SubTotal FROM customerinvoicelines WHERE InvoiceID = {$InvoiceID} AND GroupID = {$InvoiceGroupID}";
        $GotSubCost = mysqli_query($ClientCon, $GetSubCost);

        while ($ValInv = mysqli_fetch_array($GotSubCost)) {
            $SubTotal = $ValInv["SubTotal"];
        }

        $InvoiceSub = $InvoiceSub + $SubTotal;

        $GetSubCost = "SELECT SUM(LineVAT) AS VATTotal FROm customerinvoicelines WHERE InvoiceID = {$InvoiceID} AND GroupID = {$InvoiceGroupID}";
        $GotSubCost = mysqli_query($ClientCon, $GetSubCost);

        while ($ValInv = mysqli_fetch_array($GotSubCost)) {
            $VATTotal = $ValInv["VATTotal"];
        }

        $InvoiceVat = $InvoiceVat + $VATTotal;

        $GetSubCost = "SELECT SUM(LineTotal) AS LineTotal FROm customerinvoicelines WHERE InvoiceID = {$InvoiceID} AND GroupID = {$InvoiceGroupID}";
        $GotSubCost = mysqli_query($ClientCon, $GetSubCost);

        while ($ValInv = mysqli_fetch_array($GotSubCost)) {
            $LineTotal = $ValInv["LineTotal"];
        }

        $InvoiceTotal = $InvoiceTotal + $LineTotal;


        if ($TaxExempt == '0') {
            $data[] = array('<b>Product</b>' => $GroupName, '<b>Price</b>' => 'R' . number_format($LineTotal, 2), '<b>QTY</b>' => 1, '<b>Sub Total</b>' => 'R' . number_format($SubTotal, 2),
                '<b>Discount</b>' => 'R' . number_format($VATTotal, 2),
                '<b>VAT</b>' => 'R' . number_format($VATTotal, 2), '<b>Total</b>' => 'R' . number_format($LineTotal, 2));
        } else {
            $data[] = array('<b>Product</b>' => $GroupName, '<b>Price</b>' => 'R' . number_format($LineTotal, 2), '<b>QTY</b>' => 1, '<b>Sub Total</b>' => 'R' . number_format($SubTotal, 2), '<b>Total</b>' => 'R' . number_format($LineTotal, 2));
        }
    }

    $LineDiscountTotal = 0;
    while ($Val = mysqli_fetch_array($GotLines)) {
        $Description = $Val["Description"];
        $Quantity = $Val["Quantity"];
        $Price = $Val["Price"];
        $ProductID = $Val["ProductID"];

        $LineSub = $Val["LineSubTotal"];
        $InvoiceSub = $InvoiceSub + $LineSub;

        $Vat = $Val["LineVAT"];
        $InvoiceVat = $InvoiceVat + $Vat;

        $Meassure = $Val["MeassurementDescription"];

        $LineTotal = $Val["LineTotal"];
        $InvoiceTotal = $InvoiceTotal + $LineTotal;

        $PurchaseLineItemID = $Val["PurchaseLineItemID"];
        $LineDiscount = $Val['LineDiscount'];
        $LineDiscountTotal += $Val['LineDiscount'];
        if ($Meassure == "") {
            $ThisLine = $Description;
        } else {
            $ThisLine = $Description . " (" . $Meassure . ")";
        }

        //NOW LETS GET ALL CUSTOM PRODUCT DISPLAY VALUES AS WELL
        $GetCustomFields = "SELECT * FROM productcustomfields WHERE ShowInvoice = 1 ORDER BY DisplayOrder";
        $GotCustomFields = mysqli_query($ClientCon, $GetCustomFields);

        $ProductExtra = "\n";

        while ($CustomVal = mysqli_fetch_array($GotCustomFields)) {
            $CustomFieldName = $CustomVal["CustomFieldName"];
            $CustomFieldID = $CustomVal["CustomFieldID"];
            $CustomFieldType = $CustomVal["CustomFieldType"];

            if ($CustomFieldType == "text" || $CustomFieldType == "textarea") {
                $GetValue = "SELECT * FROM productcustomentries WHERE ProductID = {$ProductID} AND CustomFieldID = {$CustomFieldID}";
                $GotValue = mysqli_query($ClientCon, $GetValue);

                while ($ThisValue = mysqli_fetch_array($GotValue)) {
                    $CustomValue = $ThisValue["CustomOptionValue"];
                }

                if ($CustomValue != "") {
                    $ProductExtra .= $CustomFieldName . ": " . $CustomValue . "\n";
                }
            } else if ($CustomFieldType == "radio") {
                //HERE WE NEED TO CHECK THE OPTIONS
                //NOW GET CLIENT VALUES FOR THIS CUSTOM FIELD - KEEP IN MIND WE CAN HAVE MULTIPLE OPTIONS HERE AS WELL
                $GetCustomValue = "SELECT * FROM productcustomentries WHERE CustomFieldID = {$CustomFieldID} AND ProductID = {$ProductID}";
                $GotCustomValue = mysqli_query($ClientCon, $GetCustomValue);

                while ($Val2 = mysqli_fetch_array($GotCustomValue)) {
                    $CustomFieldOptionID = $Val2["CustomFieldOptionID"];
                    $CustomFieldValue = $Val2["CustomOptionValue"];
                    $CustomFieldOptionID = $Val2["CustomFieldOptionID"];

                    if ($CustomFieldValue == "true") {
                        //GET THE SELECTED BOX
                        $GetOptionValue = "SELECT * FROM productcustomfieldsvalues WHERE CustomFieldOptionID = {$CustomFieldOptionID}";
                        $GotOptionValue = mysqli_query($ClientCon, $GetOptionValue);
                        echo mysqli_error($ClientCon);

                        while ($CustOption = mysqli_fetch_array($GotOptionValue)) {
                            $SelectedOption = $CustOption["OptionValue"];
                            $MultiAnswer .= $SelectedOption . ", ";
                        }
                    }

                }


                $MultiAnswer = rtrim($MultiAnswer, ", ");
                if ($MultiAnswer != "") {
                    $ProductExtra .= $CustomFieldName . ": " . $MultiAnswer . "\n";
                }
                $MultiAnswer = '';
            } else if ($CustomFieldType == "select") {
                //HERE WE NEED TO CHECK THE OPTIONS
                //NOW GET CLIENT VALUES FOR THIS CUSTOM FIELD - KEEP IN MIND WE CAN HAVE MULTIPLE OPTIONS HERE AS WELL
                $GetCustomValue = "SELECT * FROM productcustomentries WHERE CustomFieldID = {$CustomFieldID} AND ProductID = {$ProductID}";
                $GotCustomValue = mysqli_query($ClientCon, $GetCustomValue);


                while ($Val2 = mysqli_fetch_array($GotCustomValue)) {
                    $CustomFieldOptionID = $Val2["CustomFieldOptionID"];
                    $CustomFieldValue = $Val2["CustomOptionValue"];
                    $CustomFieldOptionID = $Val2["CustomFieldOptionID"];


                    //GET THE SELECTED BOX
                    $GetOptionValue = "SELECT * FROM productcustomfieldsvalues WHERE CustomFieldOptionID = {$CustomFieldValue}";

                    $GotOptionValue = mysqli_query($ClientCon, $GetOptionValue);
                    echo mysqli_error($ClientCon);

                    while ($CustOption = mysqli_fetch_array($GotOptionValue)) {
                        $SelectedOption = $CustOption["OptionValue"];
                        $MultiAnswer .= $SelectedOption . ", ";
                    }


                }


                $MultiAnswer = rtrim($MultiAnswer, ", ");
                if ($MultiAnswer != "") {
                    $ProductExtra .= $CustomFieldName . ": " . $MultiAnswer . "\n";
                }
                $MultiAnswer = '';
            } else if ($CustomFieldType == "checkbox") {
                //HERE WE NEED TO CHECK THE OPTIONS
                //NOW GET CLIENT VALUES FOR THIS CUSTOM FIELD - KEEP IN MIND WE CAN HAVE MULTIPLE OPTIONS HERE AS WELL
                $GetCustomValue = "SELECT * FROM productcustomentries WHERE CustomFieldID = {$CustomFieldID} AND ProductID = {$ProductID}";
                $GotCustomValue = mysqli_query($ClientCon, $GetCustomValue);

                while ($Val2 = mysqli_fetch_array($GotCustomValue)) {
                    $CustomFieldOptionID = $Val2["CustomFieldOptionID"];
                    $CustomFieldValue = $Val2["CustomOptionValue"];
                    $CustomFieldOptionID = $Val2["CustomFieldOptionID"];

                    if ($CustomFieldValue == "true") {
                        //GET THE SELECTED BOX
                        $GetOptionValue = "SELECT * FROM productcustomfieldsvalues WHERE CustomFieldOptionID = {$CustomFieldOptionID}";
                        $GotOptionValue = mysqli_query($ClientCon, $GetOptionValue);
                        echo mysqli_error($ClientCon);

                        while ($CustOption = mysqli_fetch_array($GotOptionValue)) {
                            $SelectedOption = $CustOption["OptionValue"];
                            $MultiAnswer .= $SelectedOption . ", ";
                        }
                    }

                }


                $MultiAnswer = rtrim($MultiAnswer, ", ");
                if ($MultiAnswer != "") {
                    $ProductExtra .= $CustomFieldName . ": " . $MultiAnswer . "\n";
                }

                $MultiAnswer = '';
            }
        }

        if ($TaxExempt == '1') {
            $data[] = array('<b>Product</b>' => '<b>' . $ThisLine . "</b>" . $ProductExtra, '<b>Price</b>' => 'R' . number_format($Price, 2), '<b>QTY</b>' => $Quantity, '<b>Sub Total</b>' => 'R' . number_format($LineSub, 2),
                '<b>Discount</b>' => 'R' . number_format($LineDiscount, 2),
                '<b>Total</b>' => 'R' . number_format($LineTotal, 2));
        } else {

            $data[] = array('<b>Product</b>' => '<b>' . $ThisLine . "</b>" . $ProductExtra, '<b>Price</b>' => 'R' . number_format($Price, 2), '<b>QTY</b>' => $Quantity, '<b>Sub Total</b>' => 'R' . number_format($LineSub, 2),
                '<b>Discount</b>' => 'R' . number_format($LineDiscount, 2),
                '<b>VAT</b>' => 'R' . number_format($Vat, 2), '<b>Total</b>' => 'R' . number_format($LineTotal, 2));
        }
    }


    $pdf->ezTable($data, '', '', array('shaded' => 1, 'fontSize' => 9, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));

    $pdf->ezSetDy(-20);

    //TOTALS
    $data = array();

    $data[] = array('<b>Total</b>' => '<b>Sub Total</b>', '<b>Price</b>' => 'R' . number_format($InvoiceSub, 2));
    $data[] = array('<b>Total</b>' => '<b>Discount</b>', '<b>Price</b>' => 'R' . number_format($LineDiscountTotal, 2));
    if ($TaxExempt == 1) {

    } else {
        $data[] = array('<b>Total</b>' => '<b>VAT</b>', '<b>Price</b>' => 'R' . number_format($InvoiceVat, 2));
    }
    $data[] = array('<b>Total</b>' => '<b>Total</b>', '<b>Price</b>' => '<b>R' . number_format($InvoiceTotal, 2) . '</b>');

    $pdf->ezTable($data, '', '', array('shaded' => 1, 'fontSize' => 10, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 200, 'showHeadings' => 0, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));


    if (!empty($additionalnotes)) {
        $pdf->ezSetDy(-10);
        $pdf->ezText("Additional Invoice Notes", 10, array('aleft' => 20));
        $pdf->ezSetDy(-10);
        $dataNew[] = array('<b>Additional Invoice Notes</b>' => $additionalnotes);
        $pdf->ezTable($dataNew, '', '', array('shaded' => 0, 'fontSize' => 9, 'showHeadings' => 0, 'xPos' => 570, 'xOrientation' => 'left', 'width' => 550, 'showLines' => 0, 'cols' => array()));


    }
    $pdf->ezSetDy(-20);
    $data = array();


    //$pdfcode = $pdf->output();
    $pdf->ezStream();

}
?>

