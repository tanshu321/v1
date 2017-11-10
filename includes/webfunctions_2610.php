<?php
session_start();
function MoveRowInvoice($RowID, $PreRowID){
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	$MoveRowUp = "UPDATE `customerinvoicelines` SET `RowOrder` =  `RowOrder`-1 WHERE `InvoiceLineItemID` = {$RowID}";
	mysqli_query($ClientCon, $MoveRowUp);

	$MoveRowDown = "UPDATE `customerinvoicelines` SET `RowOrder` =  `RowOrder`+1 WHERE `InvoiceLineItemID` = {$PreRowID}";
	mysqli_query($ClientCon, $MoveRowDown);

	return "OK";
}
function MoveRowQuotes($RowID, $PreRowID){
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	$MoveRowUp = "UPDATE `customerquotelines` SET `RowOrder` =  `RowOrder`-1 WHERE `QuoteLineItemID` = {$RowID}";
	mysqli_query($ClientCon, $MoveRowUp);

	$MoveRowDown = "UPDATE `customerquotelines` SET `RowOrder` =  `RowOrder`+1 WHERE `QuoteLineItemID` = {$PreRowID}";
	mysqli_query($ClientCon, $MoveRowDown);

	return "OK";
}

function UpdateInvoiceNotes($InvoiceID, $InvoiceNotes, $additionalnotes="")
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $InvoiceNotes = CleanInput($InvoiceNotes);

    $updateInvoice = "UPDATE `customerinvoices` SET `InvoiceNotes` = '{$InvoiceNotes}' , additionalnotes ='{$additionalnotes}' WHERE `InvoiceID` = {$InvoiceID}";
    mysqli_query($ClientCon, $updateInvoice);

    return "OK";
}


function CleanInput($Text)
{
    include('includes/dbinc.php');
    $Text = str_replace("'", "`", $Text);

    //MYSQL COMMANDS - SQL INJECTION CHECK
    $Text = str_ireplace("update ", "", $Text);
    $Text = str_ireplace("select ", "", $Text);
    $Text = str_ireplace("modify ", "", $Text);
    $Text = str_ireplace("create ", "", $Text);
    $Text = str_ireplace("show ", "", $Text);
    $Text = str_ireplace("describe ", "", $Text);
    $Text = str_ireplace("drop ", "", $Text);
    $Text = str_ireplace("=", "", $Text);
    $Text = str_ireplace("$", "", $Text);
    $Text = str_ireplace(");", "", $Text);
    $Text = str_ireplace("<br>", "", $Text);
    $Text = str_ireplace("alert(", "", $Text);
    $Text = str_ireplace("id=", "", $Text);
    $Text = str_ireplace('"', "`", $Text);

    $Text = strip_tags($Text);
    $Text = addslashes($Text);
    $Text = mysqli_real_escape_string($DB, $Text);

    return $Text;
}

function LoginUserAccount($UserEmail, $Password, $RememberMe)
{
    include('includes/dbinc.php');
    $UserEmail = CleanInput($UserEmail);
    $Password = CleanInput($Password);

    $CheckClient = "SELECT * FROM clients WHERE EmailAddress = '{$UserEmail}' AND Status = 2";
    $DoCheckClient = mysqli_query($DB, $CheckClient);
    echo mysqli_error($DB);

    $FoundResult = mysqli_num_rows($DoCheckClient);
    if ($FoundResult == 1) {
        while ($Val = mysqli_fetch_array($DoCheckClient)) {
            $UserPass = $Val["Password"];
            $ThisUserName = $Val["FirstName"];
            $UserSurname = $Val["Surname"];
            $ClientID = $Val["ClientID"];
            $ClientIP = $_SERVER['HTTP_CLIENT_IP'];

            $DatabaseHost = $Val["DatabaseHost"];
            $DatabaseUserName = $Val["DatabaseUserName"];
            $DatabasePassword = $Val["DatabasePassword"];
            $DatabaseName = $Val["DatabaseName"];

            $_SESSION["DBHost"] = $DatabaseHost;
            $_SESSION["DBUser"] = $DatabaseUserName;
            $_SESSION["DBPass"] = $DatabasePassword;
            $_SESSION["DBName"] = $DatabaseName;


            if ($ClientIP == "") {
                $ClientIP = $_SERVER['REMOTE_ADDR'];
            }
        }

        //NOW CHECK PASSWORDS MATCH
        if (password_verify($Password, $UserPass)) {
            $SiteSecret = "E2A_crm_S5gdbh6nnj_usr_9898";

            $_SESSION["ClientID"] = $ClientID;
            $_SESSION["ClientName"] = $ThisUserName . " " . $UserSurname;
            $EmployeeID = 0;
            $_SESSION["SiteSecret"] = $SiteSecret;
            $_SESSION["Remember"] = $RememberMe;
            $_SESSION["ClientEmail"] = $UserEmail;
            $_SESSION["MainClient"] = 1;

            //LOG ADMIN LOGIN ACTIVITY
            $LoginDate = date("Y-m-d H:i:s");

            $InsertActivity = "INSERT INTO clientlogin (ClientID, LoginDate, LoginIPAddress) VALUES ({$ClientID}, '{$LoginDate}', '{$ClientIP}')";
            $DoInsertActivity = mysqli_query($DB, $InsertActivity);


            return "OK";
        } else {
            return "Your details where not found on the system, please check your input and try again";
        }
    } else {
        //CHECK IF ITS AN EMPLOYEE
        $CheckEmployee = "SELECT * FROM employees WHERE UserName = '{$UserEmail}' AND ClientID IN (SELECT ClientID FROM clients WHERE Status = 2) AND SystemAccess = 1 AND UserName != ''";
        $DoCheckEmployee = mysqli_query($DB, $CheckEmployee);

        $FoundEmployee = mysqli_num_rows($DoCheckEmployee);

        if ($FoundEmployee == 1) {
            while ($Val = mysqli_fetch_array($DoCheckEmployee)) {
                $UserPass = $Val["Password"];
                $EmployeeID = $Val["EmployeeID"];
                $ThisUserName = $Val["Name"];
                $UserSurname = $Val["Surname"];
                $ClientID = $Val["ClientID"];
                $ClientIP = $_SERVER['HTTP_CLIENT_IP'];

                $ClientDetails = "SELECT * FROM clients WHERE ClientID = {$ClientID}";
                $DoGetClientDetails = mysqli_query($DB, $ClientDetails);

                while ($Con = mysqli_fetch_array($DoGetClientDetails)) {
                    $DatabaseHost = $Con["DatabaseHost"];
                    $DatabaseUserName = $Con["DatabaseUserName"];
                    $DatabasePassword = $Con["DatabasePassword"];
                    $DatabaseName = $Con["DatabaseName"];

                    $_SESSION["DBHost"] = $DatabaseHost;
                    $_SESSION["DBUser"] = $DatabaseUserName;
                    $_SESSION["DBPass"] = $DatabasePassword;
                    $_SESSION["DBName"] = $DatabaseName;
                }


                if ($ClientIP == "") {
                    $ClientIP = $_SERVER['REMOTE_ADDR'];
                }
            }

            if (password_verify($Password, $UserPass)) {
                $SiteSecret = "E2A_crm_S5gdbh6nnj_usr_9898";

                $_SESSION["ClientID"] = $ClientID;
                $_SESSION["EmployeeID"] = $EmployeeID;
                $_SESSION["ClientName"] = $ThisUserName . " " . $UserSurname;
                $_SESSION["SiteSecret"] = $SiteSecret;
                $_SESSION["Remember"] = $RememberMe;
                $_SESSION["ClientEmail"] = $UserEmail;
                $_SESSION["MainClient"] = 0;

                $DB = mysqli_connect($HostName, $DBUserName, $DBPassword, 'e2acrm');

                //LOG ADMIN LOGIN ACTIVITY
                $LoginDate = date("Y-m-d H:i:s");

                $InsertActivity = "INSERT INTO clientlogin (ClientID, EmployeeID, LoginDate, LoginIPAddress) VALUES ({$ClientID}, {$EmployeeID}, '{$LoginDate}', '{$ClientIP}')";
                $DoInsertActivity = mysqli_query($DB, $InsertActivity);


                return "OK";
            } else {
                return "Your details where not found on the system, please check your input and try again";
            }
        } else {
            return "Your details were not found on the system, please check your values and try again";
        }
    }
}

function GetCountries()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $GetCountries = "SELECT * FROM countries ORDER BY CountryName";
    $GotCountries = mysqli_query($ClientCon, $GetCountries);

    return $GotCountries;
}


function CheckUniqueEmail($EmailAddress)
{
    include('includes/dbinc.php');
    $EmailAddress = CleanInput($EmailAddress);
    $CheckEmail = "SELECT ClientID FROM clients WHERE EmailAddress = '{$EmailAddress}'";
    $DoCheckEmail = mysqli_query($DB, $CheckEmail);

    $FoundEmail = mysqli_num_rows($CheckEmail);

    if ($FoundEmail == 0) {
        //ALSO CHECK RESELLER SIDE - To-do, think about it first, am I splitting reseller?
        return 0;
    } else {
        return 1;
    }

}

function CheckUniqueEmailEdit($EmailAddress, $ClientID)
{
    include('includes/dbinc.php');
    $EmailAddress = CleanInput($EmailAddress);
    $CheckEmail = "SELECT ClientID FROM clients WHERE EmailAddress = '{$EmailAddress}' AND ClientID != {$ClientID}";
    $DoCheckEmail = mysqli_query($DB, $CheckEmail);

    $FoundEmail = mysqli_num_rows($CheckEmail);

    if ($FoundEmail == 0) {
        //ALSO CHECK RESELLER SIDE - To-do, think about it first, am I splitting reseller?
        return 0;
    } else {
        return 1;
    }
}

function CheckPassword($Password)
{
    if (strlen($Password) < 8) {
        return "Password too short, must be at least 8 characters";
    } else if (!preg_match("#[0-9]+#", $Password)) {
        return "Password must include at least one number!";
    } else if (!preg_match('/[a-z]/', $Password)) {
        return "Password must include at least one lowercase letter!";
    } else if (!preg_match('/[A-Z]/', $Password)) {
        return "Password must include at least one uppercase case letter!";
    } else if (!preg_match('/[^a-zA-Z\d]/', $Password)) {
        return "Password must include at least one special character like ?";
    } else {
        return "OK";
    }
}

function AddClientDetails($Name, $Surname, $CompanyName, $ContactTel, $EmailAddress, $Address1, $Address2, $City, $State, $PostCode, $Country, $TaxExempt, $OverDueNotice, $Marketing, $PaymentMethod, $Status, $VATNumber, $AdminNotes, $DepositReference)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $Name = CleanInput($Name);
    $Surname = CleanInput($Surname);
    $CompanyName = CleanInput($CompanyName);
    $ContactTel = CleanInput($ContactTel);
    $EmailAddress = CleanInput($EmailAddress);

    $Address1 = CleanInput($Address1);
    $Address2 = CleanInput($Address2);
    $City = CleanInput($City);
    $PostCode = CleanInput($PostCode);
    $Country = CleanInput($Country);

    $TaxExempt = CleanInput($TaxExempt);
    $OverDueNotice = CleanInput($OverDueNotice);
    $Marketing = CleanInput($Marketing);

    $PaymentMethod = CleanInput($PaymentMethod);
    $Reseller = CleanInput($Reseller);
    $VATNumber = CleanInput($VATNumber);
    $AdminNotes = CleanInput($AdminNotes);
    $DepositReference = CleanInput($DepositReference);

    $DateAdded = date("Y-m-d H:i:s");

    //1 = yes
    //0 = no

    if ($TaxExempt == "true") {
        $TaxExempt = 1;
    } else {
        $TaxExempt = 0;
    }

    if ($OverDueNotice == "true") {
        $OverDueNotice = 1;
    } else {
        $OverDueNotice = 0;
    }

    if ($Marketing == "true") {
        $Marketing = 0;
    } else {
        $Marketing = 1;
    }

    $ThisClientID = $_SESSION["ClientID"];

    $InsertClientInfo = "INSERT INTO customers (FirstName, Surname, CompanyName, ContactNumber, EmailAddress, Address1, Address2, City, Region, PostCode, CountryID, TaxExempt, OverdueNotices, MarketingEmails, PaymentMethod, Status, VatNumber, AdminNotes, DateAdded, DepositReference) ";
    $InsertClientInfo .= "VALUES ('{$Name}', '{$Surname}', '{$CompanyName}', '{$ContactTel}', '{$EmailAddress}', '{$Address1}', '{$Address2}', '{$City}', '{$State}', '{$PostCode}', {$Country}, {$TaxExempt}, {$OverDueNotice}, {$Marketing}, '{$PaymentMethod}', {$Status},  '{$VATNumber}', '{$AdminNotes}', '{$DateAdded}', '{$DepositReference}')";
    $DoInsertClientInfo = mysqli_query($ClientCon, $InsertClientInfo);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return mysqli_insert_id($ClientCon);
    } else {
        return "There was an error adding the client details, please check your input and try again";
    }


}

function UpdateClientDetails($Name, $Surname, $CompanyName, $ContactTel, $EmailAddress, $Address1, $Address2, $City, $State, $PostCode, $Country, $TaxExempt, $OverDueNotice, $Marketing, $PaymentMethod, $Status, $VATNumber, $AdminNotes, $CustomerID, $DepositReference)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];

    $Name = CleanInput($Name);
    $Surname = CleanInput($Surname);
    $CompanyName = CleanInput($CompanyName);
    $ContactTel = CleanInput($ContactTel);
    $EmailAddress = CleanInput($EmailAddress);

    $Address1 = CleanInput($Address1);
    $Address2 = CleanInput($Address2);
    $City = CleanInput($City);
    $PostCode = CleanInput($PostCode);
    $Country = CleanInput($Country);

    $TaxExempt = CleanInput($TaxExempt);
    $OverDueNotice = CleanInput($OverDueNotice);
    $Marketing = CleanInput($Marketing);

    $PaymentMethod = CleanInput($PaymentMethod);
    $VATNumber = CleanInput($VATNumber);
    $AdminNotes = CleanInput($AdminNotes);
    $DepositReference = CleanInput($DepositReference);

    $DateAdded = date("Y-m-d H:i:s");

    //1 = yes
    //0 = no

    if ($TaxExempt == "true") {
        $TaxExempt = 1;
    } else {
        $TaxExempt = 0;
    }

    if ($OverDueNotice == "true") {
        $OverDueNotice = 1;
    } else {
        $OverDueNotice = 0;
    }

    if ($Marketing == "true") {
        $Marketing = 0;
    } else {
        $Marketing = 1;
    }


    $UpdateClientInfo = "UPDATE customers SET FirstName = '{$Name}', Surname = '{$Surname}', CompanyName = '{$CompanyName}', ContactNumber =  '{$ContactTel}', EmailAddress = '{$EmailAddress}', Address1 = '{$Address1}', Address2 = '{$Address2}', City = '{$City}', Region = '{$State}', PostCode = '{$PostCode}', CountryID = {$Country}, TaxExempt = {$TaxExempt}, OverdueNotices = {$OverDueNotice}, MarketingEmails = {$Marketing}, PaymentMethod = '{$PaymentMethod}', Status = {$Status}, VatNumber =  '{$VATNumber}', AdminNotes = '{$AdminNotes}', ";

    $UpdateClientInfo .= "DepositReference = '{$DepositReference}' WHERE CustomerID = {$CustomerID}";

    $DoUpdateClientInfo = mysqli_query($ClientCon, $UpdateClientInfo);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the client details, please check your input and try again" . $UpdateClientInfo;
    }


}

// added by Akshay

function GetPeriodSetup($periodID ="")
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $extra = "";
    if(!empty($periodID)){
        $extra = " where periodID = ".$periodID." ";
    }
    $GetPeriod = "SELECT * FROM periodsetup $extra ORDER BY periodID DESC ";
    $GotPeriod = mysqli_query($ClientCon, $GetPeriod);

    return $GotPeriod;
}
function AddPeriod( $title, $month, $description, $contact_account,$gdc, $periodID=''){
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];
    $DateAdded = date("Y-m-d");

    if(empty($periodID)) {
        $InsertPeriod = "INSERT INTO periodsetup (`title`, `month`,`description`, `contact_account`,`gdc`,`clientID`,`dateAdded`) VALUES 
('" . $title . "', '" . $month . "', '" . $description . "', '" . $contact_account . "','" . $gdc . "',{$ThisClientID},'" .
            $DateAdded . "')";
        $DoInsertPeriod = mysqli_query($ClientCon, $InsertPeriod);
    }else{
        $updatePeriod = "Update periodsetup set `title` = '$title', `month` = '$month', `description` = '$description',`contact_account` = '$contact_account', `gdc` = '" . $gdc . "',
 `clientID` = $ThisClientID, `dateAdded` = '$DateAdded' where periodID = ".$periodID;
        $DoUpdatePeriod = mysqli_query($ClientCon, $updatePeriod);
    }



    return "OK";
}

function RemovePeriod($periodID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $RemoveOption = "DELETE FROM periodsetup WHERE periodID = {$periodID}";
    $DoRemoveOption = mysqli_query($ClientCon, $RemoveOption);


    return "OK";
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
function updateCustomerCreadit($creadit_amount, $CustomerID, $flagType = '1')
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    if ($flagType == '1') {
        $UpdateClientInfo = "Update customers set creadit_amount= creadit_amount + '" . $creadit_amount . "' where CustomerID = '" . $CustomerID . "' ";
    } else if ($flagType == '2') {
        $UpdateClientInfo = "Update customers set creadit_amount= '" . $creadit_amount . "' where CustomerID = '" . $CustomerID . "' ";
    }


    $DoUpdateClientInfo = mysqli_query($ClientCon, $UpdateClientInfo);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the client details, please check your input and try again" . $UpdateClientInfo;
    }
}



// end
function GetAllClients()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);


    $ThisClientID = $_SESSION["ClientID"];

    $GetClients = "SELECT * FROM customers ORDER BY CompanyName, FirstName, Surname  DESC";
    $GotClients = mysqli_query($ClientCon, $GetClients);
    echo mysqli_error($ClientCon);

    return $GotClients;
}

function GetSingleClient($CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];

    $GetClient = "SELECT * FROM customers, countries WHERE customers.CountryID = countries.CountryID AND
	CustomerID = {$CustomerID}";
    $GotClient = mysqli_query($ClientCon, $GetClient);


    return $GotClient;
}

function CountInvoices($InvoiceStatus, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];

    $CountInvoices = "SELECT COUNT(InvoiceID) AS NumInvoices FROM customerinvoices WHERE CustomerID = {$CustomerID} AND InvoiceStatus = {$InvoiceStatus}";
    $DoCountInvoices = mysqli_query($ClientCon, $CountInvoices);
    $NumInvoices = 0;

    while ($Val = mysqli_fetch_array($DoCountInvoices)) {
        $NumInvoices = $Val["NumInvoices"];
    }

    return $NumInvoices;
}

function TotalInvoices($InvoiceStatus, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];

    $CountInvoices = "SELECT SUM(LineTotal) AS InvoiceTotals FROM customerinvoices, customerinvoicelines WHERE InvoiceStatus = {$InvoiceStatus}  AND CustomerID = {$CustomerID} AND customerinvoices.InvoiceID = customerinvoicelines.InvoiceID ";
    $DoCountInvoices = mysqli_query($ClientCon, $CountInvoices);
    $InvoiceTotals = 0;

    while ($Val = mysqli_fetch_array($DoCountInvoices)) {
        $InvoiceTotal = $Val["InvoiceTotals"];
    }

    return $InvoiceTotal;

}

function TotalInvoicesPaid($InvoiceStatus, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];

    $CountInvoices = "SELECT SUM(PaymentAmount) AS InvoiceTotals FROM customerinvoicepayments, customertransactions WHERE CustomerID = {$CustomerID} ";
    $DoCountInvoices = mysqli_query($ClientCon, $CountInvoices);
    $InvoiceTotals = 0;

    while ($Val = mysqli_fetch_array($DoCountInvoices)) {
        $InvoiceTotal = $Val["InvoiceTotals"];
    }

    return $InvoiceTotal;

}


function GetCustomFields()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];

    $GetCustomFields = "SELECT * FROM customcustomerfields ORDER BY CustomFieldType, CustomFieldName";
    $GotCustomFields = mysqli_query($ClientCon, $GetCustomFields);

    return $GotCustomFields;
}

function GetCustomOptions($CustomFieldID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];

    $GetCustomFieldsOptions = "SELECT * FROM customcustomerfieldsvalues WHERE CustomFieldID = {$CustomFieldID} ORDER BY OptionValue";
    $GotCustomFieldsOptions = mysqli_query($ClientCon, $GetCustomFieldsOptions);
    echo mysqli_error($ClientCon);

    return $GotCustomFieldsOptions;
}

function ClearCustomValues($CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];

    $RemoveCustom = "DELETE FROM customercustomfieldvalues WHERE CustomerID = {$CustomerID}";
    $DoRemoveCustom = mysqli_query($ClientCon, $RemoveCustom);

    return "OK";
}

function SaveCustomValue($ThisCustomID, $ClientCustomFieldOptionID, $EnteredValue, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];

    $EnteredValue = CleanInput($EnteredValue);

    $InsertCustom = "INSERT INTO customercustomfieldvalues (CustomerID, CustomFieldID, ClientCustomFieldOptionID, ClientCustomFieldValue) VALUES ({$CustomerID}, {$ThisCustomID}, {$ClientCustomFieldOptionID}, '{$EnteredValue}')";
    $DoInsertCustom = mysqli_query($ClientCon, $InsertCustom);

    return "OK";
}

function GetCustomOptionsArray($CustomFieldID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];

    $GetCustomFieldsOptions = "SELECT * FROM customcustomerfieldsvalues WHERE CustomFieldID = {$CustomFieldID} ORDER BY OptionValue";
    $GotCustomFieldsOptions = mysqli_query($ClientCon, $GetCustomFieldsOptions);

    while ($Val = mysqli_fetch_array($GotCustomFieldsOptions)) {
        $CustomClientFieldOptionID = $Val["CustomClientFieldOptionID"];
        $ReturnString .= $CustomClientFieldOptionID . ",";
    }

    return rtrim($ReturnString, ",");
}

function GetEnteredCustomValue($CustomFieldID, $ClientCustomFieldOptionID, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];

    $GetCurrentValue = "SELECT * FROM customercustomfieldvalues WHERE CustomerID = {$CustomerID} AND CustomFieldID = {$CustomFieldID} AND ClientCustomFieldOptionID = {$ClientCustomFieldOptionID}";
    $GotCurrentValue = mysqli_query($ClientCon, $GetCurrentValue);

    $ReturnValue = "";

    while ($Val = mysqli_fetch_array($GotCurrentValue)) {
        $ReturnValue = $Val["ClientCustomFieldValue"];
    }

    return $ReturnValue;
}

function GetClientDocuments($CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];

    $GetDocuments = "SELECT * FROM customerdocuments WHERE CustomerID = {$CustomerID} ORDER BY DateAdded DESC";
    $GotDocuments = mysqli_query($ClientCon, $GetDocuments);
    echo mysqli_error($ClientCon);

    return $GotDocuments;
}

function AddClientDoc($CustomerID, $ThisFileType, $NewFileName, $ThisDescript, $ThisGroup)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisUserID = $_SESSION["ClientID"];
    $ThisEmployeeID = $_SESSION["EmployeeID"];

    if ($ThisEmployeeID == "") {
        //ITS THE MAIN USER ADDING THIS
        $ThisEmployeeID = 0;

    }


    $ThisUser = $_SESSION["ClientName"];

    $ThisDescript = CleanInput($ThisDescript);
    $DateAdded = date("Y-m-d");

    $InsertFile = "INSERT INTO customerdocuments (CustomerID, DocumentName, DocumentFile, DocumentType, DateAdded, AddedBy, AddedByName, AddedByEmployeeID, DocumentGroupID) VALUES ({$CustomerID}, '{$ThisDescript}', '{$NewFileName}', '{$ThisFileType}', '{$DateAdded}',  {$ThisUserID}, '{$ThisUser}', {$ThisEmployeeID}, {$ThisGroup})";
    $DoInsertFile = mysqli_query($ClientCon, $InsertFile);


    return "OK";
}

function GetDocumentFile($DocumentID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $DocumentID = CleanInput($DocumentID);
    $ThisUserID = $_SESSION["ClientID"];

    $GetDoc = "SELECT DocumentFile FROM customerdocuments WHERE DocumentID = {$DocumentID}";
    $GotDoc = mysqli_query($ClientCon, $GetDoc);

    while ($Val = mysqli_fetch_array($GotDoc)) {
        $DocumentFile = $Val["DocumentFile"];
    }

    return $DocumentFile;


}

function GetClientNotes($CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];

    $GetNotes = "SELECT * FROM customernotes WHERE CustomerID = {$CustomerID} ORDER BY NoteID DESC";
    $GotNotes = mysqli_query($ClientCon, $GetNotes);

    return $GotNotes;
}

function AddClientNote($Note, $CustomerID, $Sticky)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $Note = CleanInput($Note);

    $ThisUserID = $_SESSION["ClientID"];
    $ThisEmployeeID = $_SESSION["EmployeeID"];

    if ($ThisEmployeeID == "") {
        //ITS THE MAIN USER ADDING THIS
        $ThisEmployeeID = 0;

    }


    $ThisUser = $_SESSION["ClientName"];


    $DateAdded = date("Y-m-d");

    $AddNote = "INSERT INTO customernotes (CustomerID, Note, DateAdded, AddedBy, AddedByName, AddedByEmployee, StickyNote) VALUES ({$CustomerID}, '{$Note}', '{$DateAdded}', {$ThisUserID}, '{$ThisUser}', {$ThisEmployeeID}, {$Sticky})";
    $DoAddNote = mysqli_query($ClientCon, $AddNote);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding the note, please check your input and try again";
    }
}


//CLIENT LOGS
function GetClientLogs($CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $ThisClientID = $_SESSION["ClientID"];

    $GetLogs = "SELECT * FROM customerlogs WHERE CustomerID = {$CustomerID} ORDER BY CustomerLogID DESC";
    $GotLogs = mysqli_query($ClientCon, $GetLogs);

    return $GotLogs;
}

function AddLogInformation($LogText, $LogType, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $ThisClientID = $_SESSION["ClientID"];
    $EmployeeID = $Val["EmployeeID"];
    if ($EmployeeID == "") {
        $EmployeeID = 0;
    }
    $ThisUser = $_SESSION["ClientName"];

    $LogText = CleanInput($LogText);
    $LogType = CleanInput($LogType);
    $DateAdded = date("Y-m-d H:i:s");

    $AddClientLog = "INSERT INTO customerlogs (CustomerID, LogText, LogAdded, AddedByClientID, AddedByEmployeeID, AddedByName, LogType) ";
    $AddClientLog .= "VALUES ({$CustomerID}, '{$LogText}', '{$DateAdded}', {$ThisClientID}, {$EmployeeID}, '{$ThisUser}', '{$LogType}')";
    $DoAddClientLog = mysqli_query($ClientCon, $AddClientLog);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding the log, please check your input and try again";
    }

}


//CUSTOM FIELDS////////////////


function GetAllCustomFields()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $GetCustomFields = "SELECT * FROM customcustomerfields ORDER BY DisplayOrder ASC";
    $GotCustomFields = mysqli_query($ClientCon, $GetCustomFields);
    echo mysqli_error($DB);

    return $GotCustomFields;
}

function CountCustomOptions($CustomFieldID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCustomFieldsOptions = "SELECT COUNT(CustomClientFieldOptionID) AS NumOptions FROM customclientfieldsvalues WHERE CustomFieldID = {$CustomFieldID}";
    $GotCustomFieldsOptions = mysqli_query($ClientCon, $GetCustomFieldsOptions);

    while ($Val = mysqli_fetch_array($GotCustomFieldsOptions)) {
        $NumOptions = $Val["NumOptions"];

    }

    return $NumOptions;
}

function GetCustomFieldDetails($ThisFieldID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCustomField = "SELECT * FROM customcustomerfields WHERE CustomFieldID = {$ThisFieldID}";
    $GotCustomField = mysqli_query($ClientCon, $GetCustomField);
    echo $GetCustomField;

    return $GotCustomField;
}

function RemoveCustomOption($CustomClientFieldOptionID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $DeleteCustom = "DELETE FROM customcustomerfieldsvalues WHERE CustomClientFieldOptionID = {$CustomClientFieldOptionID}";
    $DoDeleteCustom = mysqli_query($ClientCon, $DeleteCustom);

    return "OK";
}

function AddNewOption($NewOption, $ThisFieldID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $NewOption = CleanInput($NewOption);

    $InsertOption = "INSERT INTO customcustomerfieldsvalues (CustomFieldID, OptionValue) VALUES ({$ThisFieldID}, '{$NewOption}')";
    $DoInsertOption = mysqli_query($ClientCon, $InsertOption);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding the option, please check your input and try again";
    }

}

function RemoveCustomField($ThisFieldID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    if ($_SESSION["MainClient"] == 1) {
        //REMOVE MAIN FIELD
        $GetCustomField = "DELETE FROM customercustomfields WHERE CustomFieldID = {$ThisFieldID}";
        $GotCustomField = mysqli_query($ClientCon, $GetCustomField);
        echo mysqli_error($ClientCon);

        //ALSO REMOVE OPTIONS
        $GetCustomField = "DELETE FROM customercustomfieldsvalues WHERE CustomFieldID = {$ThisFieldID}";
        $GotCustomField = mysqli_query($ClientCon, $GetCustomField);
        echo mysqli_error($ClientCon);


        return "OK";
    } else {
        return "You do not have permission to remove fields, please ask the administrator to remove the field";
    }
}

//SUPPLIER MODULE
function GetPriceMove($CostingID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetPriceMove = "SELECT * FROM suppliercostingtracking WHERE SupplierCostID = {$CostingID} ORDER BY SupplierCostingID DESC";
    $GotPriceMove = mysqli_query($ClientCon, $GetPriceMove);
    echo mysqli_error($ClientCon);

    return $GotPriceMove;
}

function GetBillingType($CostingID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);


}

function GetAllSuppliers()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetSuppliers = "SELECT * FROM suppliers ORDER BY SupplierName";
    $GotSuppliers = mysqli_query($ClientCon, $GetSuppliers);

    return $GotSuppliers;
}

function AddSupplier($SupplierName, $SupplierEmail, $SupplierContactNumber, $SupplierFax, $SupplierContact, $Address1, $Address2, $City, $State, $PostCode, $Country, $SupplierNotes, $SupplierStatus, $SupplierVat, $VAT)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $SupplierName = CleanInput($SupplierName);
    $SupplierEmail = CleanInput($SupplierEmail);
    $SupplierContactNumber = CleanInput($SupplierContactNumber);
    $SupplierFax = CleanInput($SupplierFax);
    $SupplierContact = CleanInput($SupplierContact);
    $Address1 = CleanInput($Address1);
    $Address2 = CleanInput($Address2);
    $City = CleanInput($City);
    $State = CleanInput($State);
    $PostCode = CleanInput($PostCode);
    $Country = CleanInput($Country);
    $SupplierNotes = CleanInput($SupplierNotes);
    $SupplierStatus = CleanInput($SupplierStatus);

    $InsertSupplier = "INSERT INTO suppliers (SupplierName, SupplierEmail, SupplierTel, SupplierFax, SupplierContact, SupplierVat, SupplierAddress1, SupplierAddress2, City, State, PostCode, CountryID, SupplierNote, SupplierStatus, ChargesVAT) ";
    $InsertSupplier .= "VALUES ('{$SupplierName}', '{$SupplierEmail}', '{$SupplierContactNumber}', '{$SupplierFax}', '{$SupplierContact}', '{$SupplierVat}', '{$Address1}', '{$Address2}', '{$City}', '{$State}', '{$PostCode}', {$Country}, '{$SupplierNotes}', {$SupplierStatus}, {$VAT})";
    $DoInsertSupplier = mysqli_query($ClientCon, $InsertSupplier);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding the supplier information, please check your values and try again";
    }
}

function UpdateSupplier($SupplierName, $SupplierEmail, $SupplierContactNumber, $SupplierFax, $SupplierContact, $Address1, $Address2, $City, $State, $PostCode, $Country, $SupplierNotes, $SupplierStatus, $SupplierVat, $SupplierID, $VAT)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $SupplierName = CleanInput($SupplierName);
    $SupplierEmail = CleanInput($SupplierEmail);
    $SupplierContactNumber = CleanInput($SupplierContactNumber);
    $SupplierFax = CleanInput($SupplierFax);
    $SupplierContact = CleanInput($SupplierContact);
    $Address1 = CleanInput($Address1);
    $Address2 = CleanInput($Address2);
    $City = CleanInput($City);
    $State = CleanInput($State);
    $PostCode = CleanInput($PostCode);
    $Country = CleanInput($Country);
    $SupplierNotes = CleanInput($SupplierNotes);
    $SupplierStatus = CleanInput($SupplierStatus);

    $UpdateSupplier = "UPDATE suppliers SET SupplierName = '{$SupplierName}', SupplierEmail =  '{$SupplierEmail}', SupplierTel =  '{$SupplierContactNumber}', SupplierFax = '{$SupplierFax}', SupplierContact = '{$SupplierContact}', SupplierVat = '{$SupplierVat}', SupplierAddress1 = '{$Address1}', SupplierAddress2 = '{$Address2}', City = '{$City}', State =  '{$State}', PostCode = '{$PostCode}', CountryID = {$Country}, SupplierNote = '{$SupplierNotes}', SupplierStatus = {$SupplierStatus}, ChargesVAT = {$VAT} WHERE SupplierID = {$SupplierID} ";

    $DoInsertSupplier = mysqli_query($ClientCon, $UpdateSupplier);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the supplier information, please check your values and try again";
    }
}

function GetSupplierDetails($SupplierID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetSupplier = "SELECT * FROM suppliers WHERE SupplierID = {$SupplierID}";
    $GotSupplier = mysqli_query($ClientCon, $GetSupplier);

    return $GotSupplier;
}

function GetSupplierProducts($SupplierID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetProducts = "SELECT * FROM products WHERE ProductID IN (SELECT ProductID FROM supplierproducts WHERE SupplierID = {$SupplierID})";
    $GotProducts = mysqli_query($ClientCon, $GetProducts);

    return $GotProducts;
}


function GetCurrentSupplierBillingDetails($ProductID, $SupplierID, $MeasurementID, $BillingType)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCost = "SELECT * FROM suppliercost WHERE ProductID = {$ProductID} AND SupplierID = {$SupplierID} AND suppliercost.MeasurementID = {$MeasurementID}  AND BillingType = '{$BillingType}' ORDER BY SupplierCostID ASC LIMIT 1";
    $GotCost = mysqli_query($ClientCon, $GetCost);

    return $GotCost;
}

function GetAllMeasurements()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetMeasurements = "SELECT * FROM productmeasurement ORDER BY MeasurementDescription";
    $GotMeasurements = mysqli_query($ClientCon, $GetMeasurements);

    return $GotMeasurements;
}

function GetCurrentSellingPrice($ProductID, $SupplierID, $MeasurementID, $BillingType)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCost = "SELECT * FROM productcost WHERE ProductID = {$ProductID} AND SupplierID = {$SupplierID} AND MeasurementID = {$MeasurementID}  AND BillingType = '{$BillingType}' ORDER BY ProductCostID ASC LIMIT 1";
    $GotCost = mysqli_query($ClientCon, $GetCost);

    return $GotCost;
}

function AddSupplierPricing($Price, $BillingType, $ProRata, $PackSize, $SellingPrice, $ProductID, $SupplierID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $PriceDate = date("Y-m-d");

    //WE DONT REMOVE HISTORY, WE JUST KEEP ADDING TO IT
    $InsertSupplierCost = "INSERT INTO suppliercost (ProductID, SupplierCost, SupplierCostDate, MeasurementID, SupplierID, BillingType, ProRataBilling) ";
    $InsertSupplierCost .= "VALUES ({$ProductID}, {$Price}, '{$PriceDate}', {$PackSize}, {$SupplierID}, '{$BillingType}', {$ProRata})";

    $DoInsertPrice = mysqli_query($ClientCon, $InsertSupplierCost);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        //THEN ADD TO OUR NORMAL PRODUCT COSTING
        //WE DONT REMOVE HISTORY, WE JUST KEEP ADDING TO IT
        $InsertCost = "INSERT INTO productcost (ProductID, ClientCost, ClientCostDate, MeasurementID, SupplierID, BillingType, ProRataBilling) ";
        $InsertCost .= "VALUES ({$ProductID}, {$SellingPrice}, '{$PriceDate}', {$PackSize}, {$SupplierID}, '{$BillingType}', {$ProRata})";

        $DoInsertPrice = mysqli_query($ClientCon, $InsertCost);

        $Error = mysqli_error($ClientCon);

        if ($Error == "") {
            return "OK";
        } else {
            return "There was an error updating the selling price, please check your values and try again";
        }

    } else {
        return "There was an error updating the pricing" . $InsertSupplierCost;
    }
}

//UNITS OF MEASSURE
function GetAllProductMeassures()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetMeassures = "SELECT * FROM productmeasurement ORDER BY MeasurementDescription";
    $GotMeassures = mysqli_query($ClientCon, $GetMeassures);

    echo mysqli_error($ClientCon);

    return $GotMeassures;
}

function GetSingleMeassure($MeasurementID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetMeassures = "SELECT * FROM productmeasurement WHERE MeasurementID = {$MeasurementID} ORDER BY MeasurementDescription";
    $GotMeassures = mysqli_query($ClientCon, $GetMeassures);

    while ($Val = mysqli_fetch_array($GotMeassures)) {
        $Measure = $Val["MeasurementDescription"];
    }

    return $Measure;
}

function AddUnitMeassure($Unit)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $Unit = CleanInput($Unit);

    //SEE IF ITS NOT THERE ALREADY
    $CheckMeassure = "SELECT * FROM productmeasurement WHERE MeasurementDescription = '{$Unit}'";
    $DoCheckMeasure = mysqli_query($ClientCon, $CheckMeassure);

    $FoundMeassure = mysqli_num_rows($DoCheckMeasure);

    if ($FoundMeassure == 0) {
        $InsertMeassure = "INSERT INTO productmeasurement (MeasurementDescription) VALUES ('{$Unit}')";
        $DoInsertMeasure = mysqli_query($ClientCon, $InsertMeassure);

        $Error = mysqli_error($ClientCon);

        if ($Error == "") {
            return "OK";
        } else {
            return "There was an error adding the measurement, please check your input and try again";
        }
    } else {
        return "This unit of meassure is already in your database";
    }
}

function UpdateUnitMeassure($Unit, $MeasurementID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);


    $Unit = CleanInput($Unit);

    $CheckMeassure = "SELECT * FROM productmeasurement WHERE MeasurementDescription = '{$Unit}' AND MeasurementID != {$MeasurementID}";
    $DoCheckMeasure = mysqli_query($ClientCon, $CheckMeassure);

    $FoundMeassure = mysqli_num_rows($DoCheckMeasure);

    if ($FoundMeassure == 0) {

        $UpdateMeassure = "UPDATE productmeasurement SET MeasurementDescription = '{$Unit}' WHERE MeasurementID = {$MeasurementID}";
        $DoUpdateMeassure = mysqli_query($ClientCon, $UpdateMeassure);

        $Error = mysqli_error($ClientCon);

        if ($Error == "") {
            return "OK";
        } else {
            return "There was an error adding the measurement, please check your input and try again";
        }
    } else {
        return "This unit of meassure is already in your database";
    }
}

//SUB GROUPS
function GetSubGroups($ProductGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetSubGroups = "SELECT * FROM productsubgroups WHERE ProductGroupID = {$ProductGroupID} ORDER BY SubGroupName";
    $GotSubGroups = mysqli_query($ClientCon, $GetSubGroups);

    return $GotSubGroups;
}

function GetSubGroupsArray($ProductGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetSubGroups = "SELECT * FROM productsubgroups WHERE ProductGroupID = {$ProductGroupID} ORDER BY SubGroupName";
    $GotSubGroups = mysqli_query($ClientCon, $GetSubGroups);

    $SubGroupArray = '';

    while ($Val = mysqli_fetch_array($GotSubGroups)) {
        $ProductSubGroupID = $Val["ProductSubGroupID"];
        $SubGroupName = $Val["SubGroupName"];

        $SubGroupArray .= $ProductSubGroupID . "---" . $SubGroupName . ":::";
    }

    return rtrim($SubGroupArray, ":::");
}

function AddProductSubGroup($NewGroup, $ProductGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $NewGroup = CleanInput($NewGroup);

    $InserSubGroup = "INSERT INTO productsubgroups (ProductGroupID, SubGroupName) VALUES ({$ProductGroupID}, '{$NewGroup}')";
    $DoInsertSubGroup = mysqli_query($ClientCon, $InserSubGroup);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding the sub category, please check your input and try again";
    }
}

function UpdateProductSubGroup($NewGroup, $ProductSubGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $NewGroup = CleanInput($NewGroup);

    $InserSubGroup = "UPDATE productsubgroups SET SubGroupName= '{$NewGroup}' WHERE ProductSubGroupID = {$ProductSubGroupID}";
    $DoInsertSubGroup = mysqli_query($ClientCon, $InserSubGroup);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding the sub category, please check your input and try again";
    }
}


//PRODUCT CUSTOM FIELDS
function RemoveCustomFieldProduct($ThisFieldID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    if ($_SESSION["MainClient"] == 1) {
        //REMOVE MAIN FIELD
        $GetCustomField = "DELETE FROM productcustomfields WHERE CustomFieldID = {$ThisFieldID}";
        $GotCustomField = mysqli_query($ClientCon, $GetCustomField);
        echo mysqli_error($ClientCon);

        //ALSO REMOVE OPTIONS
        $GetCustomField = "DELETE FROM productcustomfieldsvalues WHERE CustomFieldID = {$ThisFieldID}";
        $GotCustomField = mysqli_query($ClientCon, $GetCustomField);
        echo mysqli_error($ClientCon);


        return "OK";
    } else {
        return "You do not have permission to remove fields, please ask the administrator to remove the field";
    }
}

function GetProductCustomFields()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCustom = "SELECT * FROM productcustomfields ORDER BY DisplayOrder ASC";
    $GotCustom = mysqli_query($ClientCon, $GetCustom);

    return $GotCustom;
}

function CountCustomProductOptions($CustomFieldID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCustom = "SELECT COUNT(CustomFieldOptionID) AS NumOptions FROM productcustomfieldsvalues WHERE CustomFieldID = {$CustomFieldID}";
    $GotCustom = mysqli_query($ClientCon, $GetCustom);

    while ($Val = mysqli_fetch_array($GotCustom)) {
        $NumOptions = $Val["NumOptions"];
    }

    return $NumOptions;
}

function GetProductCustomField($ThisCustomID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCustom = "SELECT * FROM productcustomfields WHERE CustomFieldID = {$ThisCustomID}";
    $GotCustom = mysqli_query($ClientCon, $GetCustom);

    return $GotCustom;
}

function GetCustomFieldOptions($CustomFieldID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetOptions = "SELECT * FROM productcustomfieldsvalues WHERE CustomFieldID = {$CustomFieldID} ORDER BY OptionValue";
    $GotOptions = mysqli_query($ClientCon, $GetOptions);
    echo mysqli_error($ClientCon);

    return $GotOptions;
}

function RemoveCustomProductOption($CustomFieldOptionID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $RemoveOption = "DELETE FROM productcustomfieldsvalues WHERE CustomFieldOptionID = {$CustomFieldOptionID}";
    $DoRemoveOption = mysqli_query($ClientCon, $RemoveOption);

    return "OK";
}

function UpdateCustomOption($NewOption, $CustomFieldOptionID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $NewOption = CleanInput($NewOption);

    $UpdateOption = "UPDATE productcustomfieldsvalues SET OptionValue = '{$NewOption}' WHERE CustomFieldOptionID = {$CustomFieldOptionID}";
    $DoUpdateOption = mysqli_query($ClientCon, $UpdateOption);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the option value, please check your input and try again";
    }
}

function UpdateCustomProductField($FieldName, $Required, $DisplayOrder, $ThisCustomID, $DisplayInvoice, $DisplayQuote)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $FieldName = CleanInput($FieldName);
    $Required = CleanInput($Required);
    $DisplayOrder = CleanInput($DisplayOrder);

    //HERE WE CLEAN DISPLAY ORDER AGAIN
    $GetAllDisplay = "SELECT * FROM productcustomfields WHERE DisplayOrder >= {$DisplayOrder} AND CustomFieldID != {$ThisCustomID} ORDER BY DisplayOrder ASC";
    $GotAllDisplay = mysqli_query($ClientCon, $GetAllDisplay);

    $NewOrder = $DisplayOrder + 1;

    while ($Val = mysqli_fetch_array($GotAllDisplay)) {
        $ChangeID = $Val["CustomFieldID"];

        $UpdateOrder = "UPDATE productcustomfields SET DisplayOrder = {$NewOrder} WHERE CustomFieldID = {$ChangeID}";
        $DoUpdateOrder = mysqli_query($ClientCon, $UpdateOrder);

        $NewOrder = $NewOrder + 1;
    }

    //THEN UPDATE ACTUAL
    $UpdateCustom = "UPDATE productcustomfields SET CustomFieldName = '{$FieldName}', Required = {$Required}, DisplayOrder = {$DisplayOrder}, ShowInvoice = {$DisplayInvoice}, ShowQuote = {$DisplayQuote} WHERE CustomFieldID = {$ThisCustomID}";
    $DoUpdateCustom = mysqli_query($ClientCon, $UpdateCustom);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the custom field details, please check your input and try again";
    }
}

function AddCustomProductField($FieldName, $Required, $DisplayOrder, $FieldType, $DisplayInvoice, $DisplayQuote)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $FieldName = CleanInput($FieldName);
    $Required = CleanInput($Required);
    $DisplayOrder = CleanInput($DisplayOrder);

    //THEN ADD IT
    $AddCustom = "INSERT INTO productcustomfields (CustomFieldName, CustomFieldType, Required, DisplayOrder, ShowInvoice, ShowQuote) ";
    $AddCustom .= "VALUES ('{$FieldName}', '{$FieldType}', {$Required}, {$DisplayOrder}, {$DisplayInvoice}, {$DisplayQuote})";

    $DoInsertCustom = mysqli_query($ClientCon, $AddCustom);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        $NewFieldID = mysqli_insert_id($ClientCon);

        //THEN RE-ORDER AGAIN
        //HERE WE CLEAN DISPLAY ORDER AGAIN
        $GetAllDisplay = "SELECT * FROM productcustomfields WHERE DisplayOrder >= {$DisplayOrder} AND CustomFieldID != {$NewFieldID} ORDER BY DisplayOrder ASC";
        $GotAllDisplay = mysqli_query($ClientCon, $GetAllDisplay);

        $NewOrder = $DisplayOrder + 1;

        while ($Val = mysqli_fetch_array($GotAllDisplay)) {
            $ChangeID = $Val["CustomFieldID"];

            $UpdateOrder = "UPDATE productcustomfields SET DisplayOrder = {$NewOrder} WHERE CustomFieldID = {$ChangeID}";
            $DoUpdateOrder = mysqli_query($ClientCon, $UpdateOrder);

            $NewOrder = $NewOrder + 1;
        }

        return $NewFieldID;
    } else {
        return "There was an error adding the custom field, please check your input and try again";
    }

}

function AddCustomProductOption($NewOption, $ThisCustomID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $NewOption = CleanInput($NewOption);

    $AddOption = "INSERT INTO productcustomfieldsvalues (CustomFieldID, OptionValue) VALUES ({$ThisCustomID}, '{$NewOption}')";
    $DoAddOption = mysqli_query($ClientCon, $AddOption);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding the option value, please check your input and try again";
    }
}

//PRODUCTS MODULE
function GetAllProductGroups()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetProductGroups = "SELECT * FROM productgroups ORDER BY GroupName";
    $GotProductGroups = mysqli_query($ClientCon, $GetProductGroups);

    return $GotProductGroups;
}

function GetAllActiveProductGroupsNonOnce()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetProductGroups = "SELECT * FROM productgroups WHERE ProductGroupID IN (SELECT ProductGroupID FROM products WHERE ProductID IN (SELECT ProductID FROM productcost WHERE BillingType != 'Once-Off')) ORDER BY GroupName";
    $GotProductGroups = mysqli_query($ClientCon, $GetProductGroups);

    return $GotProductGroups;
}

function GetAllActiveProductGroups()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetProductGroups = "SELECT * FROM productgroups WHERE ProductGroupID IN (SELECT ProductGroupID FROM products WHERE ProductID IN (SELECT ProductID FROM productcost WHERE BillingType = 'Once-Off')) ORDER BY GroupName";
    $GotProductGroups = mysqli_query($ClientCon, $GetProductGroups);

    return $GotProductGroups;
}

function GetGroupProducts($ProductGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetProducts = "SELECT * FROM products WHERE ProductGroupID = {$ProductGroupID} ORDER BY ProductName";
    $GotProducts = mysqli_query($ClientCon, $GetProducts);

    return $GotProducts;
}

function GetGroupProductsArrayNonOnce($ProductGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetProducts = "SELECT * FROM products WHERE ProductGroupID = {$ProductGroupID} AND ProductID IN (SELECT ProductID FROM productcost WHERE BillingType != 'Once-Off') ORDER BY ProductCode, ProductName";
    $GotProducts = mysqli_query($ClientCon, $GetProducts);

    $X = 0;

    while ($Val = mysqli_fetch_array($GotProducts)) {
        $ProductID = $Val["ProductID"];
        $ProductName = $Val["ProductName"];
        $ProductCode = $Val["ProductCode"];


        $Item[$X][0] = $ProductID;
        $Item[$X][1] = $ProductName;
        $Item[$X][2] = $ProductCode;

        $X++;
    }

    return $Item;
}


function GetGroupProductsArray($ProductGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetProducts = "SELECT * FROM products WHERE ProductGroupID = {$ProductGroupID} AND ProductID IN (SELECT ProductID FROM productcost WHERE BillingType = 'Once-Off') ORDER BY ProductCode, ProductName";
    $GotProducts = mysqli_query($ClientCon, $GetProducts);

    $X = 0;

    while ($Val = mysqli_fetch_array($GotProducts)) {
        $ProductID = $Val["ProductID"];
        $ProductName = $Val["ProductName"];
        $ProductCode = $Val["ProductCode"];


        $Item[$X][0] = $ProductID;
        $Item[$X][1] = $ProductName;
        $Item[$X][2] = $ProductCode;

        $X++;
    }

    return $Item;
}

function GetGroupProductsArrayQuote($ProductGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetProducts = "SELECT * FROM products WHERE ProductGroupID = {$ProductGroupID} AND ProductID IN (SELECT ProductID FROM productcost) ORDER BY ProductCode, ProductName";
    $GotProducts = mysqli_query($ClientCon, $GetProducts);

    $X = 0;

    while ($Val = mysqli_fetch_array($GotProducts)) {
        $ProductID = $Val["ProductID"];
        $ProductName = $Val["ProductName"];
        $ProductCode = $Val["ProductCode"];


        $Item[$X][0] = $ProductID;
        $Item[$X][1] = $ProductName;
        $Item[$X][2] = $ProductCode;

        $X++;
    }

    return $Item;
}

function GetSubGroupProducts($ProductSubGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetProducts = "SELECT * FROM products WHERE ProductSubGroupID = {$ProductSubGroupID} ORDER BY ProductName";
    $GotProducts = mysqli_query($ClientCon, $GetProducts);

    return $GotProducts;
}

function DeleteProductSubGroup($ProductSubGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $DelGroup = "DELETE FROM productsubgroups WHERE ProductSubGroupID = {$ProductSubGroupID}";
    $DoDelGroup = mysqli_query($ClientCon, $DelGroup);

    return "OK";
}

function DeleteProductGroup($ProductGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $DelGroup = "DELETE FROM productgroups WHERE ProductGroupID = {$ProductGroupID}";
    $DoDelGroup = mysqli_query($ClientCon, $DelGroup);

    return "OK";
}

function AddProductGroup($GroupName)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GroupName = CleanInput($GroupName);

    $InsertGroup = "INSERT INTO productgroups (GroupName) VALUES ('{$GroupName}')";
    $DoInsertGroup = mysqli_query($ClientCon, $InsertGroup);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding the product group, please check your entered value and try again";
    }
}

function UpdateProductGroup($GroupName, $ProductGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GroupName = CleanInput($GroupName);

    $InsertGroup = "UPDATE productgroups SET GroupName = '{$GroupName}' WHERE ProductGroupID = {$ProductGroupID}";
    $DoInsertGroup = mysqli_query($ClientCon, $InsertGroup);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the product group, please check your entered value and try again";
    }
}

function GetProductGroup($ProductGroupID, $ProductSubGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    if ($ProductSubGroupID == "") {
        $GetGroupName = "SELECT * FROM productgroups WHERE ProductGroupID = {$ProductGroupID}";
        $GotGroupName = mysqli_query($ClientCon, $GetGroupName);

        while ($Val = mysqli_fetch_array($GotGroupName)) {
            $GroupName = $Val["GroupName"];
        }
    } else {
        $GetGroupName = "SELECT * FROM productgroups, productsubgroups WHERE productgroups.ProductGroupID = {$ProductGroupID} AND productgroups.ProductGroupID = productsubgroups.ProductSubGroupID  AND ProductSubGroupID =  {$ProductSubGroupID}";
        $GotGroupName = mysqli_query($ClientCon, $GetGroupName);


        while ($Val = mysqli_fetch_array($GotGroupName)) {
            $GroupName = $Val["GroupName"] . " - " . $Val["SubGroupName"];
        }

    }

    return $GroupName;
}

function GetProductSubGroup($ProductSubGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetSubGroupName = "SELECT * FROM productsubgroups WHERE ProductSubGroupID = {$ProductSubGroupID}";
    $GotSubGroupName = mysqli_query($ClientCon, $GetSubGroupName);

    while ($Val = mysqli_fetch_array($GotSubGroupName)) {
        $SubGroupName = $Val["SubGroupName"];
    }

    return $SubGroupName;
}

function AddProduct($ProductName, $IsStock, $ProductStatus, $ProductGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ProductName = CleanInput($ProductName);

    $AddProduct = "INSERT INTO products (ProductGroupID, ProductName, IsStockItem, ProductStatus) VALUES ({$ProductGroupID}, '{$ProductName}',  {$IsStock}, {$ProductStatus})";
    $DoAddProduct = mysqli_query($ClientCon, $AddProduct);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return mysqli_insert_id($ClientCon);
    } else {
        return "Error";
    }
}

function GetSingleProduct($ProductID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetProduct = "SELECT * FROM products WHERE ProductID = {$ProductID}";
    $GotProduct = mysqli_query($ClientCon, $GetProduct);

    return $GotProduct;
}

function AddProductPricing($PriceDescript, $Price, $BillingType, $ProRata, $ProductID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $PriceDescript = CleanInput($PriceDescript);

    $AddPricing = "INSERT INTO productcost (ProductID, OptionName, OptionCost, BillingType, ProRataBilling) VALUES ({$ProductID}, '{$PriceDescript}', {$Price}, '{$BillingType}', '{$ProRata}')";
    $DoAddPricing = mysqli_query($ClientCon, $AddPricing);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding the pricing option, please make sure your price does not have the currency symbol in";
    }
}

function GetProductPricing($ProductID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetPricing = "SELECT * FROM productcost WHERE ProductID = {$ProductID}";
    $GotPricing = mysqli_query($ClientCon, $GetPricing);

    return $GotPricing;
}

function GetProductPricingArrayNonOnce($ProductID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetPricing = "SELECT * FROM productcost WHERE ProductID = {$ProductID} AND BillingType != 'Once-Off' ORDER BY ClientCost ASC";
    $GotPricing = mysqli_query($ClientCon, $GetPricing);
    $X = 0;

    while ($Val = mysqli_fetch_array($GotPricing)) {
        $ProductCostID = $Val["ProductCostID"];
        $BillingType = $Val["BillingType"];
        $ClientCost = $Val["ClientCost"];
        $PackSize = $Val["PackSize"];
        $MeassurementID = $Val["MeasurementID"];
        $MinimumOrder = $Val["MinimumOrder"];
        $ProRataBilling = $Val["ProRataBilling"];

        $MeasurementDescription = "";

        if ($MeassurementID > 0) {
            $GetMeassurement = "SELECT * FROM productmeasurement WHERE MeasurementID = {$MeassurementID}";
            $GotMeassurement = mysqli_query($ClientCon, $GetMeassurement);
            echo mysqli_error($ClientCon);

            while ($Measure = mysqli_fetch_array($GotMeassurement)) {
                $MeasurementDescription = $Measure["MeasurementDescription"];
            }

        }

        if ($MeasurementDescription != "") {
            $Description = $PackSize . " " . $MeasurementDescription . " @ R" . $ClientCost . " " . $BillingType;
        } else {
            $Description = $PackSize . " @ R" . $ClientCost . " " . $BillingType;
        }


        $Pricing[$X][0] = $ProductCostID;
        $Pricing[$X][1] = $Description;
        $Pricing[$X][2] = $MinimumOrder;
        $Pricing[$X][3] = $ProRataBilling;


        $X++;
    }

    return $Pricing;
}

function GetProductPricingArray($ProductID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetPricing = "SELECT * FROM productcost WHERE ProductID = {$ProductID} AND BillingType = 'Once-Off' ORDER BY ClientCost ASC";
    $GotPricing = mysqli_query($ClientCon, $GetPricing);
    $X = 0;

    while ($Val = mysqli_fetch_array($GotPricing)) {
        $ProductCostID = $Val["ProductCostID"];
        $BillingType = $Val["BillingType"];
        $ClientCost = $Val["ClientCost"];
        $PackSize = $Val["PackSize"];
        $MeassurementID = $Val["MeasurementID"];
        $MinimumOrder = $Val["MinimumOrder"];

        $MeasurementDescription = "";

        if ($MeassurementID > 0) {
            $GetMeassurement = "SELECT * FROM productmeasurement WHERE MeasurementID = {$MeassurementID}";
            $GotMeassurement = mysqli_query($ClientCon, $GetMeassurement);
            echo mysqli_error($ClientCon);

            while ($Measure = mysqli_fetch_array($GotMeassurement)) {
                $MeasurementDescription = $Measure["MeasurementDescription"];
            }

        }

        if ($MeasurementDescription != "") {
            $Description = $PackSize . " " . $MeasurementDescription . " @ R" . $ClientCost . " " . $BillingType;
        } else {
            $Description = $PackSize . " @ R" . $ClientCost . " " . $BillingType;
        }


        $Pricing[$X][0] = $ProductCostID;
        $Pricing[$X][1] = $Description;
        $Pricing[$X][2] = $MinimumOrder;


        $X++;
    }

    return $Pricing;
}

function GetProductPricingArrayQuote($ProductID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetPricing = "SELECT * FROM productcost WHERE ProductID = {$ProductID} ORDER BY ClientCost ASC";
    $GotPricing = mysqli_query($ClientCon, $GetPricing);
    $X = 0;

    while ($Val = mysqli_fetch_array($GotPricing)) {
        $ProductCostID = $Val["ProductCostID"];
        $BillingType = $Val["BillingType"];
        $ClientCost = $Val["ClientCost"];
        $PackSize = $Val["PackSize"];
        $MeassurementID = $Val["MeasurementID"];
        $MinimumOrder = $Val["MinimumOrder"];

        $MeasurementDescription = "";

        if ($MeassurementID > 0) {
            $GetMeassurement = "SELECT * FROM productmeasurement WHERE MeasurementID = {$MeassurementID}";
            $GotMeassurement = mysqli_query($ClientCon, $GetMeassurement);
            echo mysqli_error($ClientCon);

            while ($Measure = mysqli_fetch_array($GotMeassurement)) {
                $MeasurementDescription = $Measure["MeasurementDescription"];
            }

        }

        if ($MeasurementDescription != "") {
            $Description = $PackSize . " " . $MeasurementDescription . " @ R" . $ClientCost . " " . $BillingType;
        } else {
            $Description = $PackSize . " @ R" . $ClientCost . " " . $BillingType;
        }


        $Pricing[$X][0] = $ProductCostID;
        $Pricing[$X][1] = $Description;
        $Pricing[$X][2] = $MinimumOrder;


        $X++;
    }

    return $Pricing;
}

function UpdateProduct($ProductName, $IsStock, $ProductStatus, $ProductGroupID, $ProductID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ProductName = CleanInput($ProductName);

    $UpdateProduct = "UPDATE products SET ProductGroupID = {$ProductGroupID}, ProductName = '{$ProductName}', IsStockItem = {$IsStock}, ProductStatus = {$ProductStatus} WHERE ProductID = {$ProductID}";
    $DoAddProduct = mysqli_query($ClientCon, $UpdateProduct);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the product information, please check your values and try again";
    }
}

function RemovePricing($ProductCostID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    //TO-DO
    $DeleteCosting = "DELETE FROM productcost WHERE ProductCostID = {$ProductCostID}";
    $DoDelCosting = mysqli_query($ClientCon, $DeleteCosting);

    return "OK";
}

function GetProductCosting($ProductCostID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCosting = "SELECT * FROM productcost WHERE ProductCostID = {$ProductCostID}";
    $GotCosting = mysqli_query($ClientCon, $GetCosting);

    return $GotCosting;
}

function UpdateProductPricing($PriceDescript, $Price, $BillingType, $ProRata, $ProductCostID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $PriceDescript = CleanInput($PriceDescript);

    $UpdateCosting = "UPDATE productcost SET OptionName = '{$PriceDescript}', OptionCost = {$Price}, BillingType = '{$BillingType}', ProRataBilling = {$ProRata} WHERE ProductCostID = {$ProductCostID}";
    $DoUpdateCosting = mysqli_query($ClientCon, $UpdateCosting);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the costing, please check your values and try again";
    }
}

//NEW PRODUCT MANAGEMENT
function GetAllProducts()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetProducts = "SELECT * FROM products ORDER BY ProductName";
    $GotProducts = mysqli_query($ClientCon, $GetProducts);

    return $GotProducts;
}

function GetThisProductGroup($ProductGroupID, $ProductSubGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetGroup = "SELECT * FROM productgroups WHERE ProductGroupID = {$ProductGroupID}";
    $GotGroup = mysqli_query($ClientCon, $GetGroup);

    while ($Val = mysqli_fetch_array($GotGroup)) {
        $ReturnGroup = $Val["GroupName"];
    }

    if ($ProductSubGroupID != "") {
        $GetGroup = "SELECT * FROM productsubgroups WHERE ProductSubGroupID = {$ProductSubGroupID}";
        $GotGroup = mysqli_query($ClientCon, $GetGroup);

        while ($Val = mysqli_fetch_array($GotGroup)) {
            $ReturnGroup .= " - " . $Val["SubGroupName"];
        }
    }

    return $ReturnGroup;
}

function GetProductCustomDetails($ThisCustomID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCustomField = "SELECT * FROM productcustomfields WHERE CustomFieldID = {$ThisCustomID}";
    $GotCustomField = mysqli_query($ClientCon, $GetCustomField);

    while ($Val = mysqli_fetch_array($GotCustomField)) {
        $CustomFieldType = $Val["CustomFieldType"];
        $Required = $Val["Required"];
    }

    $ReturnArray[0] = $CustomFieldType;
    $ReturnArray[1] = $Required;

    return $ReturnArray;
}

function GetCustomFieldOptionsArray($CustomFieldID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetOptions = "SELECT * FROM productcustomfieldsvalues WHERE CustomFieldID = {$CustomFieldID} ORDER BY OptionValue";
    $GotOptions = mysqli_query($ClientCon, $GetOptions);
    $X = 0;

    while ($Val = mysqli_fetch_array($GotOptions)) {
        $CustomOptions[$X] = $Val["CustomFieldOptionID"];
        $X++;
    }

    return $CustomOptions;
}

function UpdateBaseProduct($Name, $Description, $Code, $SerialNumber, $Group, $SubGroup, $Warranty, $StockItem, $MinStock, $Catalogue, $Status, $ProductID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $Name = CleanInput($Name);
    $Description = CleanInput($Description);
    $Code = CleanInput($Code);
    $SerialNumber = CleanInput($SerialNumber);
    $Group = CleanInput($Group);
    $SubGroup = CleanInput($SubGroup);
    $Warranty = CleanInput($Warranty);
    $StockItem = CleanInput($StockItem);
    $MinStock = CleanInput($MinStock);
    $Catalogue = CleanInput($Catalogue);
    $Status = CleanInput($Status);

    $UpdateProduct = "UPDATE products SET ProductName = '{$Name}', ProductGroupID = {$Group}, ProductSubGroupID = {$SubGroup}, IsStockItem = {$StockItem}, ProductCode = '{$Code}', ProductDescription = '{$Description}', ShowInCatalog = {$Catalogue}, WarrantyMonths = {$Warranty}, ProductSerialNumber = '{$SerialNumber}', ProductStatus = {$Status} WHERE ProductID = {$ProductID} ";

    $DoUpdateProduct = mysqli_query($ClientCon, $UpdateProduct);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return $ProductID;
    } else {
        return "There was an error updating the product base information, please check your values and try again" . $UpdateProduct;
    }
}

function AddBaseProduct($Name, $Description, $Code, $SerialNumber, $Group, $SubGroup, $Warranty, $StockItem, $MinStock, $Catalogue, $Status)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $Name = CleanInput($Name);
    $Description = CleanInput($Description);
    $Code = CleanInput($Code);
    $SerialNumber = CleanInput($SerialNumber);
    $Group = CleanInput($Group);
    $SubGroup = CleanInput($SubGroup);
    $Warranty = CleanInput($Warranty);
    $StockItem = CleanInput($StockItem);
    $MinStock = CleanInput($MinStock);
    $Catalogue = CleanInput($Catalogue);
    $Status = CleanInput($Status);

    $InsertProduct = "INSERT INTO products (ProductName, ProductGroupID, ProductSubGroupID, IsStockItem, MinimumStock, ProductCode, ProductDescription, ShowInCatalog, WarrantyMonths, ProductSerialNumber, ProductStatus) ";
    $InsertProduct .= "VALUES ('{$Name}', {$Group}, {$SubGroup}, {$StockItem}, {$MinStock}, '{$Code}', '{$Description}', {$Catalogue}, {$Warranty}, '{$SerialNumber}', {$Status})";
    $DoInsertProduct = mysqli_query($ClientCon, $InsertProduct);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return mysqli_insert_id($ClientCon);
    } else {
        return "There was an error adding the product base information, please check your values and try again" . $Error;
    }
}

function SaveProductCustomEntry($CustomFieldID, $CustomFieldOptionID, $Value, $ProductID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $Value = CleanInput($Value);

    $InsertValue = "INSERT INTO productcustomentries (ProductID, CustomFieldID, CustomFieldOptionID, CustomOptionValue) ";
    $InsertValue .= "VALUES ({$ProductID}, {$CustomFieldID}, {$CustomFieldOptionID}, '{$Value}')";

    $DoInsertValue = mysqli_query($ClientCon, $InsertValue);
    echo mysqli_error($ClientCon);

    //JUST HOPING IT WORKS
    return "OK";
}

function UpdateProductCustomEntry($ThisCustomID, $ThisOptionID, $Value, $ProductID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    //FIRST SEE IF ITS THERE
    $CheckField = "SELECT * FROM productcustomentries WHERE ProductID = {$ProductID} AND CustomFieldID = {$ThisCustomID} AND CustomFieldOptionID = {$ThisOptionID}";
    $DoCheckField = mysqli_query($ClientCon, $CheckField);
    $FoundEntry = mysqli_num_rows($DoCheckField);

    if ($FoundEntry > 0) {
        //UPDATE IT
        while ($Val = mysqli_fetch_array($DoCheckField)) {
            $ProductCustomValueID = $Val["ProductCustomValueID"];
        }

        $UpdateCustom = "UPDATE productcustomentries SET CustomOptionValue = '{$Value}' WHERE ProductCustomValueID = {$ProductCustomValueID}";
        $DoUpdateCustom = mysqli_query($ClientCon, $UpdateCustom);
    } else {
        //ADD IT
        $InsertValue = "INSERT INTO productcustomentries (ProductID, CustomFieldID, CustomFieldOptionID, CustomOptionValue) ";
        $InsertValue .= "VALUES ({$ProductID}, {$ThisCustomID}, {$ThisOptionID}, '{$Value}')";

        $DoInsertValue = mysqli_query($ClientCon, $InsertValue);
    }

    return "OK";
}

function GetProductInfo($ProductID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetProduct = "SELECT * FROM products WHERE ProductID = {$ProductID}";
    $GotProduct = mysqli_query($ClientCon, $GetProduct);

    return $GotProduct;
}

function GetCustomValue($ProductID, $CustomFieldID, $CustomFieldOptionID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetValue = "SELECT CustomOptionValue FROM productcustomentries WHERE ProductID = {$ProductID} AND CustomFieldID = {$CustomFieldID} AND CustomFieldOptionID = {$CustomFieldOptionID}";

    $GotValue = mysqli_query($ClientCon, $GetValue);

    $CustomValue = '';

    while ($Val = mysqli_fetch_array($GotValue)) {
        $CustomValue = $Val["CustomOptionValue"];
    }

    //return $GetValue;
    return $CustomValue;
}

function AddProductImage($ProductID, $ThisFileType, $NewFileName)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $InsertImage = "INSERT INTO productimages (ProductID, ProductImage) VALUES ({$ProductID}, '{$NewFileName}')";
    $DoInsertImage = mysqli_query($ClientCon, $InsertImage);

    return "OK";
}

function GetProductImages($ProductID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetImages = "SELECT * FROM productimages WHERE ProductID = {$ProductID}";
    $GotImages = mysqli_query($ClientCon, $GetImages);

    return $GotImages;
}

function RemoveImage($ProductImageID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $DeleteImage = "DELETE FROM productimages WHERE ProductImageID = {$ProductImageID}";
    $DoDeleteImage = mysqli_query($ClientCon, $DeleteImage);

    return "OK";
}

function GetMeasurementByID($MeasurementID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetMeasure = "SELECT * FROM productmeasurement WHERE MeasurementID = {$MeasurementID}";
    $GotMeasure = mysqli_query($ClientCon, $GetMeasure);

    while ($Val = mysqli_fetch_array($GotMeasure)) {
        $MeasurementDescription = $Val["MeasurementDescription"];
    }

    return $MeasurementDescription;
}

function GetSingleCosting($CostingID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCosting = "SELECT * FROM productcost WHERE ProductCostID = {$CostingID}";
    $GotCosting = mysqli_query($ClientCon, $GetCosting);

    return $GotCosting;
}

function UpdateCosting($BillingType, $SellPrice, $PackSize, $Meassure, $StockAffect, $ProRata, $CostingID, $MinOrder)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $UpdateCosting = "UPDATE productcost SET ClientCost = {$SellPrice}, MeasurementID = {$Meassure}, BillingType = '{$BillingType}', ProRataBilling = {$ProRata}, PackSize = {$PackSize}, StockAffect = {$StockAffect}, MinimumOrder = {$MinOrder} WHERE ProductCostID = {$CostingID}";
    $DoUpdateCosting = mysqli_query($ClientCon, $UpdateCosting);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the costing, please make sure your values are numeric";
    }
}

function AddCosting($BillingType, $SellPrice, $PackSize, $Meassure, $StockAffect, $ProRata, $ProductID, $MinOrder)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $UpdateCosting = "INSERT INTO productcost (ClientCost, MeasurementID, BillingType, ProRataBilling, PackSize, StockAffect, ProductID, MinimumOrder) ";
    $UpdateCosting .= "VALUES ({$SellPrice}, {$Meassure},  '{$BillingType}',  {$ProRata}, {$PackSize}, {$StockAffect}, {$ProductID}, {$MinOrder})";
    $DoUpdateCosting = mysqli_query($ClientCon, $UpdateCosting);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the costing, please make sure your values are numeric";
    }
}

//CUSTOMER CUSTOM FIELDS
function GetCustomerCustomFields()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCustom = "SELECT * FROM customercustomfields ORDER BY DisplayOrder ASC";
    $GotCustom = mysqli_query($ClientCon, $GetCustom);

    return $GotCustom;
}

function CountCustomCustomerOptions($CustomFieldID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCustom = "SELECT COUNT(CustomFieldOptionID) AS NumOptions FROM productcustomfieldsvalues WHERE CustomFieldID = {$CustomFieldID}";
    $GotCustom = mysqli_query($ClientCon, $GetCustom);

    while ($Val = mysqli_fetch_array($GotCustom)) {
        $NumOptions = $Val["NumOptions"];
    }

    return $NumOptions;
}

function GetCustomerCustomField($ThisCustomID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCustom = "SELECT * FROM customercustomfields WHERE CustomFieldID = {$ThisCustomID}";
    $GotCustom = mysqli_query($ClientCon, $GetCustom);

    return $GotCustom;
}

function GetCustomFieldOptionsCustomer($CustomFieldID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetOptions = "SELECT * FROM customercustomfieldsvalues WHERE CustomFieldID = {$CustomFieldID} ORDER BY OptionValue";
    $GotOptions = mysqli_query($ClientCon, $GetOptions);
    echo mysqli_error($ClientCon);

    return $GotOptions;
}

function RemoveCustomCustomerOption($CustomFieldOptionID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $RemoveOption = "DELETE FROM customercustomfieldsvalues WHERE CustomFieldOptionID = {$CustomFieldOptionID}";
    $DoRemoveOption = mysqli_query($ClientCon, $RemoveOption);

    return "OK";
}

function UpdateCustomerCustomOption($NewOption, $CustomFieldOptionID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $NewOption = CleanInput($NewOption);

    $UpdateOption = "UPDATE customercustomfieldsvalues SET OptionValue = '{$NewOption}' WHERE CustomFieldOptionID = {$CustomFieldOptionID}";
    $DoUpdateOption = mysqli_query($ClientCon, $UpdateOption);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the option value, please check your input and try again";
    }
}

function UpdateCustomCustomerField($FieldName, $Required, $DisplayOrder, $ThisCustomID, $DisplayInvoice, $DisplayQuote)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $FieldName = CleanInput($FieldName);
    $Required = CleanInput($Required);
    $DisplayOrder = CleanInput($DisplayOrder);

    //HERE WE CLEAN DISPLAY ORDER AGAIN
    $GetAllDisplay = "SELECT * FROM customercustomfields WHERE DisplayOrder >= {$DisplayOrder} AND CustomFieldID != {$ThisCustomID} ORDER BY DisplayOrder ASC";
    $GotAllDisplay = mysqli_query($ClientCon, $GetAllDisplay);

    $NewOrder = $DisplayOrder + 1;

    while ($Val = mysqli_fetch_array($GotAllDisplay)) {
        $ChangeID = $Val["CustomFieldID"];

        $UpdateOrder = "UPDATE customercustomfields SET DisplayOrder = {$NewOrder} WHERE CustomFieldID = {$ChangeID}";
        $DoUpdateOrder = mysqli_query($ClientCon, $UpdateOrder);

        $NewOrder = $NewOrder + 1;
    }

    //THEN UPDATE ACTUAL
    $UpdateCustom = "UPDATE customercustomfields SET CustomFieldName = '{$FieldName}', Required = {$Required}, DisplayOrder = {$DisplayOrder}, DisplayInvoice = {$DisplayInvoice}, DisplayQuote = {$DisplayQuote} WHERE CustomFieldID = {$ThisCustomID}";
    $DoUpdateCustom = mysqli_query($ClientCon, $UpdateCustom);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the custom field details, please check your input and try again";
    }
}

function AddCustomCustomerField($FieldName, $Required, $DisplayOrder, $FieldType, $DisplayInvoice, $DisplayQuote)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $FieldName = CleanInput($FieldName);
    $Required = CleanInput($Required);
    $DisplayOrder = CleanInput($DisplayOrder);

    //THEN ADD IT
    $AddCustom = "INSERT INTO customercustomfields (CustomFieldName, CustomFieldType, Required, DisplayOrder, DisplayInvoice, DisplayQuote) ";
    $AddCustom .= "VALUES ('{$FieldName}', '{$FieldType}', {$Required}, {$DisplayOrder}, {$DisplayInvoice}, {$DisplayQuote})";

    $DoInsertCustom = mysqli_query($ClientCon, $AddCustom);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        $NewFieldID = mysqli_insert_id($ClientCon);

        //THEN RE-ORDER AGAIN
        //HERE WE CLEAN DISPLAY ORDER AGAIN
        $GetAllDisplay = "SELECT * FROM customercustomfields WHERE DisplayOrder >= {$DisplayOrder} AND CustomFieldID != {$NewFieldID} ORDER BY DisplayOrder ASC";
        $GotAllDisplay = mysqli_query($ClientCon, $GetAllDisplay);

        $NewOrder = $DisplayOrder + 1;

        while ($Val = mysqli_fetch_array($GotAllDisplay)) {
            $ChangeID = $Val["CustomFieldID"];

            $UpdateOrder = "UPDATE customercustomfields SET DisplayOrder = {$NewOrder} WHERE CustomFieldID = {$ChangeID}";
            $DoUpdateOrder = mysqli_query($ClientCon, $UpdateOrder);

            $NewOrder = $NewOrder + 1;
        }

        return $NewFieldID;
    } else {
        return "There was an error adding the custom field, please check your input and try again";
    }

}

function AddCustomCustomerOption($NewOption, $ThisCustomID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $NewOption = CleanInput($NewOption);

    $AddOption = "INSERT INTO customercustomfieldsvalues (CustomFieldID, OptionValue) VALUES ({$ThisCustomID}, '{$NewOption}')";
    $DoAddOption = mysqli_query($ClientCon, $AddOption);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding the option value, please check your input and try again";
    }
}

function GetCustomFieldOptionsArrayCustomer($CustomFieldID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetOptions = "SELECT * FROM customercustomfieldsvalues WHERE CustomFieldID = {$CustomFieldID} ORDER BY OptionValue";
    $GotOptions = mysqli_query($ClientCon, $GetOptions);
    $X = 0;

    while ($Val = mysqli_fetch_array($GotOptions)) {
        $CustomOptions[$X] = $Val["CustomFieldOptionID"];
        $X++;
    }

    return $CustomOptions;
}

function GetCustomerCustomDetails($ThisCustomID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCustomField = "SELECT * FROM customercustomfields WHERE CustomFieldID = {$ThisCustomID}";
    $GotCustomField = mysqli_query($ClientCon, $GetCustomField);

    while ($Val = mysqli_fetch_array($GotCustomField)) {
        $CustomFieldType = $Val["CustomFieldType"];
        $Required = $Val["Required"];
    }

    $ReturnArray[0] = $CustomFieldType;
    $ReturnArray[1] = $Required;

    return $ReturnArray;
}

function SaveCustomerCustomEntry($CustomFieldID, $CustomFieldOptionID, $Value, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $Value = CleanInput($Value);

    $InsertValue = "INSERT INTO customercustomentries (CustomerID, CustomFieldID, CustomFieldOptionID, CustomOptionValue) ";
    $InsertValue .= "VALUES ({$CustomerID}, {$CustomFieldID}, {$CustomFieldOptionID}, '{$Value}')";

    $DoInsertValue = mysqli_query($ClientCon, $InsertValue);
    echo mysqli_error($ClientCon);

    //JUST HOPING IT WORKS
    return "OK";
}

function UpdateCustomerCustomEntry($ThisCustomID, $ThisOptionID, $Value, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    //FIRST SEE IF ITS THERE
    $CheckField = "SELECT * FROM customercustomentries WHERE CustomerID = {$CustomerID} AND CustomFieldID = {$ThisCustomID} AND CustomFieldOptionID = {$ThisOptionID}";
    $DoCheckField = mysqli_query($ClientCon, $CheckField);
    $FoundEntry = mysqli_num_rows($DoCheckField);

    if ($FoundEntry > 0) {
        //UPDATE IT
        while ($Val = mysqli_fetch_array($DoCheckField)) {
            $CustomerCustomValueID = $Val["CustomerCustomValueID"];
        }

        $UpdateCustom = "UPDATE customercustomentries SET CustomOptionValue = '{$Value}' WHERE CustomerCustomValueID = {$CustomerCustomValueID}";
        $DoUpdateCustom = mysqli_query($ClientCon, $UpdateCustom);
    } else {
        //ADD IT
        $InsertValue = "INSERT INTO customercustomentries (CustomerID, CustomFieldID, CustomFieldOptionID, CustomOptionValue) ";
        $InsertValue .= "VALUES ({$CustomerID}, {$ThisCustomID}, {$ThisOptionID}, '{$Value}')";

        $DoInsertValue = mysqli_query($ClientCon, $InsertValue);
    }

    return "OK";
}

function GetCustomValueCustomer($CustomerID, $CustomFieldID, $CustomFieldOptionID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetValue = "SELECT CustomOptionValue FROM customercustomentries WHERE CustomerID = {$CustomerID} AND CustomFieldID = {$CustomFieldID} AND CustomFieldOptionID = {$CustomFieldOptionID}";

    $GotValue = mysqli_query($ClientCon, $GetValue);

    $CustomValue = '';

    while ($Val = mysqli_fetch_array($GotValue)) {
        $CustomValue = $Val["CustomOptionValue"];
    }

    //return $GetValue;
    return $CustomValue;
}


//CUSTOMER CONTACTS MODULE
function GetCustomerContacts($CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetContacts = "SELECT * FROM customercontacts WHERE CustomerID = {$CustomerID}";
    $GotContacts = mysqli_query($ClientCon, $GetContacts);
    echo mysqli_error($ClientCon);

    return $GotContacts;
}

function GetSingleContact($ContactID)
{

    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetContact = "SELECT * FROM customercontacts WHERE ContactID = {$ContactID}";
    $GotContact = mysqli_query($ClientCon, $GetContact);

    return $GotContact;
}

function UpdateContact($Name, $Surname, $CompanyName, $ContactTel, $EmailAddress, $Department, $EmailSupport, $EmailQuotes, $EmailInvoices, $AddContacts, $AcceptQuotes, $ChangeDetails, $ContactID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $Name = CleanInput($Name);
    $Surname = CleanInput($Surname);
    $CompanyName = CleanInput($CompanyName);
    $ContactTel = CleanInput($ContactTel);
    $EmailAddress = CleanInput($EmailAddress);
    $Department = CleanInput($Department);

    $UpdateContact = "UPDATE customercontacts SET Name = '{$Name}', Surname = '{$Surname}', CompanyName = '{$CompanyName}', Department = '{$Department}', EmailAddress = '{$EmailAddress}', ContactNumber = '{$ContactTel}', EmailInvoice = {$EmailInvoices}, ";
    $UpdateContact .= "EmailSupport = {$EmailSupport}, EmailQuotes = {$EmailQuotes}, AddContacts = {$AddContacts}, AcceptQuotes = {$AcceptQuotes}, ChangeDetails = {$ChangeDetails} WHERE ContactID = {$ContactID}";
    $DoUpdateContact = mysqli_query($ClientCon, $UpdateContact);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating this contact, please check your values and try again";
    }
}

function AddContact($Name, $Surname, $CompanyName, $ContactTel, $EmailAddress, $Department, $EmailSupport, $EmailQuotes, $EmailInvoices, $AddContacts, $AcceptQuotes, $ChangeDetails, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $Name = CleanInput($Name);
    $Surname = CleanInput($Surname);
    $CompanyName = CleanInput($CompanyName);
    $ContactTel = CleanInput($ContactTel);
    $EmailAddress = CleanInput($EmailAddress);
    $Department = CleanInput($Department);

    $InsertContact = "INSERT INTO customercontacts (CustomerID, Name, Surname, CompanyName, Department, EmailAddress, ContactNumber, EmailInvoice, EmailSupport, EmailQuotes, AddContacts, AcceptQuotes, ChangeDetails) ";
    $InsertContact .= "VALUES ({$CustomerID}, '{$Name}', '{$Surname}', '{$CompanyName}', '{$Department}', '{$EmailAddress}', '{$ContactTel}', {$EmailInvoices}, {$EmailSupport}, {$EmailQuotes}, {$AddContacts}, {$AcceptQuotes}, {$ChangeDetails})";

    $DoInsertContact = mysqli_query($ClientCon, $InsertContact);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding this contact, please check your values and try again";
    }
}

function DeleteContact($ContactID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $DelCon = "DELETE FROM customercontacts WHERE ContactID = {$ContactID}";
    $DoDelCon = mysqli_query($ClientCon, $DelCon);

    return "OK";
}

//DOCUMENT GROUP SETUP
function GetDocumentGroups()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetDocGroups = "SELECT * FROM customerdocumentgroups ORDER BY GroupName";
    $GotDocGroups = mysqli_query($ClientCon, $GetDocGroups);

    return $GotDocGroups;
}

function CountDocumentsGroup($DocumentGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCountDocs = "SELECT COUNT(DocumentID) AS NumDocuments FROM customerdocuments WHERE DocumentGroupID = {$DocumentGroupID}";
    $GotCountDocs = mysqli_query($ClientCon, $GetCountDocs);
    echo mysqli_error($ClientCon);

    while ($Val = mysqli_fetch_array($GotCountDocs)) {
        $NumDocs = $Val["NumDocuments"];
    }

    return $NumDocs;
}

function UpdateDocumentGroup($NewGroup, $DocumentGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $NewGroup = CleanInput($NewGroup);

    $UpdateGroup = "UPDATE customerdocumentgroups SET GroupName = '{$NewGroup}' WHERE DocumentGroupID = {$DocumentGroupID}";
    $DoUpdateGroup = mysqli_query($ClientCon, $UpdateGroup);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the document group, please check your value and try again";
    }
}

function AddDocumentGroup($NewGroup)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $NewGroup = CleanInput($NewGroup);

    $UpdateGroup = "INSERT INTO customerdocumentgroups (GroupName) VALUES ('{$NewGroup}')";
    $DoUpdateGroup = mysqli_query($ClientCon, $UpdateGroup);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding the document group, please check your value and try again";
    }
}

function GetDocumentGroupName($DocumentGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetDocGroups = "SELECT * FROM customerdocumentgroups WHERE DocumentGroupID = {$DocumentGroupID}";
    $GotDocGroups = mysqli_query($ClientCon, $GetDocGroups);

    while ($Val = mysqli_fetch_array($GotDocGroups)) {
        $GroupName = $Val["GroupName"];
    }

    return $GroupName;
}

function GetStickyNotes($CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetSticky = "SELECt * FROM customernotes WHERE StickyNote = 1 AND CustomerID = {$CustomerID}";
    $GotSticky = mysqli_query($ClientCon, $GetSticky);

    return $GotSticky;
}

//CLIENT LOG RECORDINGS
function CreateClientAccess($CustomerID, $ActivityType)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $LogDate = date("Y-m-d H:i:s");

    $ThisUser = $_SESSION["ClientName"];
    $EmployeeID = $_SESSION["EmployeeID"];
    $ClientID = $_SESSION["ClientID"];

    if ($EmployeeID == "") {
        $EmployeeID = 0;
    }


    $InsertLog = "INSERT INTO customeraccess(CustomerID, ClientID, EmployeeID, LogType, LogDate, AccessName) VALUES ({$CustomerID}, {$ClientID}, {$EmployeeID}, '{$ActivityType}', '{$LogDate}', '{$ThisUser}')";
    $DoInsertLog = mysqli_query($ClientCon, $InsertLog);
    echo mysqli_error($ClientCon);
}

function GetCustomerLogs($CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetLogs = "SELECT * FROM customeraccess WHERE CustomerID = {$CustomerID} ORDER BY LogDate DESC";
    $GotLogs = mysqli_query($ClientCon, $GetLogs);

    return $GotLogs;
}

function GetCustomerEmailLogs($CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetLogs = "SELECT * FROM customerlogs WHERE CustomerID = {$CustomerID} ORDER BY LogAdded DESC";
    $GotLogs = mysqli_query($ClientCon, $GetLogs);
    echo mysqli_error($ClientCon);

    return $GotLogs;
}

//INVOICING MODULE
//NORMAL INVOICES
function GetAllInvoices()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $ThisClientID = $_SESSION["ClientID"];


    $GetInvoices = "SELECT * FROM customerinvoices";
    $GotInvoices = mysqli_query($ClientCon, $GetInvoices);
    echo mysqli_error($ClientCon);

    return $GotInvoices;
}

function GetAllCustomerInvoices($CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $ThisClientID = $_SESSION["ClientID"];

    $GetInvoices = "SELECT * FROM customerinvoices WHERE CustomerID = {$CustomerID} ORDER BY InvoiceID DESC";
    $GotInvoices = mysqli_query($ClientCon, $GetInvoices);
    echo mysqli_error($ClientCon);

    return $GotInvoices;
}

function GetInvoiceTotal($InvoiceID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $ThisClientID = $_SESSION["ClientID"];

    $GetInvoiceTotal = "SELECT SUM(LineTotal) AS InvoiceTotal FROM customerinvoicelines WHERE InvoiceID = {$InvoiceID}";
    $GotInvoiceTotal = mysqli_query($ClientCon, $GetInvoiceTotal);

    while ($Val = mysqli_fetch_array($GotInvoiceTotal)) {
        $InvoiceTotal = $Val["InvoiceTotal"];
    }


    return $InvoiceTotal;
}

function CreateInvoice($CustomerID, $Reference, $InvoiceNumber, $DiscountPercent, $DueDate, $Status, $AdminInvoiceNotes)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $ThisClientID = $_SESSION["ClientID"];

    $AdminInvoiceNotes = CleanInput($AdminInvoiceNotes);

    $InvoiceDate = date("Y-m-d");

    $EmployeeID = $Val["EmployeeID"];
    if ($EmployeeID == "") {
        $EmployeeID = 0;
    }

    if ($DiscountPercent == "") {
        $DiscountPercent = 0;
    }

    $ThisUser = $_SESSION["ClientName"];

    $TaxExempt = CheckClientVatSetting($CustomerID);

    if ($TaxExempt == 0) //MUST BE TAXED
    {
        $Taxed = 1;
    } else {
        $Taxed = 0;
    }

    $ThisUser = $_SESSION["ClientName"];

    $AddCustomerInvoice = "INSERT INTO customerinvoices (CustomerID, InvoiceNumber, InvoiceDate, DueDate, DiscountPercent, InvoiceStatus, InvoiceReference, InvoiceNotes, Taxed, AddedByClient, AddedByEmployee, AddedByName) ";
    $AddCustomerInvoice .= "VALUES ({$CustomerID}, '{$InvoiceNumber}', '{$InvoiceDate}', '{$DueDate}', {$DiscountPercent}, {$Status}, '{$Reference}', '{$AdminInvoiceNotes}', {$Taxed}, {$ThisClientID}, {$EmployeeID}, '{$ThisUser}')";
    $DoAddCustomerInvoice = mysqli_query($ClientCon, $AddCustomerInvoice);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return mysqli_insert_id($ClientCon);
    } else {
        return "There was an error creating this invoice, please check your values and try again";
    }


}


//RECURRING INVOICES
function GetAllRecurringInvoices()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $ThisClientID = $_SESSION["ClientID"];

    $GetRecurring = "SELECT * FROM customerrecurring, customers WHERE customerrecurring.CustomerID = customers.CustomerID ORDER BY NextRun ASC";
    $GotRecurring = mysqli_query($ClientCon, $GetRecurring);
    echo mysqli_error($DB);

    return $GotRecurring;
}


function CheckClientVatSetting($CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $ThisClientID = $_SESSION["ClientID"];

    $CheckTax = "SELECT TaxExempt FROM customers WHERE CustomerID = {$CustomerID}";
    $DoCheckTax = mysqli_query($ClientCon, $CheckTax);

    while ($Val = mysqli_fetch_array($DoCheckTax)) {
        $TaxExempt = $Val["TaxExempt"];
    }

    return $TaxExempt;
}

function CreateRecurringInvoice($Customer, $Reference, $RecurringInvoiceNumber, $DiscountPercent, $StartDate, $EndDate, $DaysTillDue, $Frequency, $Status, $AdminInvoiceNotes)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $ThisClientID = $_SESSION["ClientID"];

    $Reference = CleanInput($Reference);
    $RecurringInvoiceNumber = CleanInput($RecurringInvoiceNumber);
    $DiscountPercent = CleanInput($DiscountPercent);
    $StartDate = CleanInput($StartDate);
    $EndDate = CleanInput($EndDate);
    $DaysTillDue = CleanInput($DaysTillDue);
    $Frequency = CleanInput($Frequency);
    $AdminInvoiceNotes = CleanInput($AdminInvoiceNotes);

    $DateAdded = date("Y-m-d");

    $EmployeeID = $Val["EmployeeID"];
    if ($EmployeeID == "") {
        $EmployeeID = 0;
    }

    if ($DiscountPercent == "") {
        $DiscountPercent = 0;
    }

    $ThisUser = $_SESSION["ClientName"];


    $InsertRecurring = "INSERT INTO customerrecurring (CustomerID, StartDate, EndDate, Frequency, DueDateForPayment, InvoiceDateAdded, ClientReccuringInvoiceNumber, ReferenceNumber, RecurringStatus, InvoiceNotes, AddedByClient, AddedByEmployee, DiscountPercentage, AddedByName) ";
    $InsertRecurring .= "VALUES ({$Customer}, '{$StartDate}', '{$EndDate}', '{$Frequency}', {$DaysTillDue}, '{$DateAdded}', '{$RecurringInvoiceNumber}', '{$Reference}', {$Status}, '{$AdminInvoiceNotes}', {$ThisClientID}, {$EmployeeID}, {$DiscountPercent}, '{$ThisUser}')";

    $DoInsertRecurring = mysqli_query($ClientCon, $InsertRecurring);
    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return mysqli_insert_id($ClientCon);
    } else {
        return "There was an error setting up the recurring invoice, please check your values and try again" . $Error;
    }


}

function AddRecurringLine($RecurringID, $Item, $Description, $Quantity, $Price)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $Description = CleanInput($Description);
    if ($Item == "Other") {
        $Item = 0;
    }

    $LineTotal = $Price * $Quantity;

    $AddLine = "INSERT INTO customerrecurringlines (RecurringID, Description, Quantity, Price, ItemID, LineTotal) VALUES ({$RecurringID}, '{$Description}', {$Quantity}, {$Price}, {$Item}, {$LineTotal})";
    $DoAddLine = mysqli_query($ClientCon, $AddLine);

    //WE ASSUME ALL FINE
    return "OK";
}

function GetRecurringTotal($RecurringID, $DiscountPercent, $TaxExempt)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetLines = "SELECT SUM(LineTotal) AS TotalInvoice FROM customerrecurringlines WHERE RecurringID = {$RecurringID}";
    $GotLines = mysqli_query($ClientCon, $GetLines);

    while ($Val = mysqli_fetch_array($GotLines)) {
        $TotalInvoice = $Val["TotalInvoice"];
    }

    if ($DiscountPercent > 0) {
        $TotalInvoice = $TotalInvoice * ((100 - $DiscountPercent) / 100);
    }

    if ($TaxExempt == 0) {
        $TotalInvoice = $TotalInvoice * 1.14;
    }

    return number_format($TotalInvoice, 2);


}


//CLIENT TASK
function GetClientTask($CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetTask = "SELECT	 * FROM customertask WHERE CustomerID = {$CustomerID} ORDER BY TaskID DESC";
    $GotTask = mysqli_query($ClientCon, $GetTask);

    return $GotTask;
}

function AddNewTask($Task, $TaskDate, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];
    $ThisUser = $_SESSION["ClientName"];
    $DateAdded = date("Y-m-d");

    $EmployeeID = $Val["EmployeeID"];
    if ($EmployeeID == "") {
        $EmployeeID = 0;
    }

    $Task = CleanInput($Task);

    $AddTask = "INSERT INTO customertask (CustomerID, TaskDescription, TaskDate, ClientID, EmployeeID, AddedByName, DateAdded, Status) ";
    $AddTask .= "VALUES ({$CustomerID}, '{$Task}', '{$TaskDate}', {$ThisClientID}, {$EmployeeID}, '{$ThisUser}', '{$DateAdded}', 0)";

    $DoAddTask = mysqli_query($ClientCon, $AddTask);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding the task, please check your input and try again";
    }
}

function GetTask($TaskID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetTask = "SELECT * FROM customertask WHERE TaskID = {$TaskID}";
    $GotTask = mysqli_query($ClientCon, $GetTask);

    return $GotTask;
}

function UpdateTask($Task, $TaskDate, $TaskStatus, $TaskID, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $Task = CleanInput($Task);

    $UpdateTask = "UPDATE customertask SET TaskDescription = '{$Task}', TaskDate = '{$TaskDate}', Status = {$TaskStatus} WHERE TaskID = {$TaskID} AND CustomerID = {$CustomerID}";
    $DoUpdateTask = mysqli_query($ClientCon, $UpdateTask);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the task, please check your input and try again";
    }
}

//CLIENT FOLLOW UPS
function GetClientFollowUps($CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetFollowUps = "SELECT	 * FROM customerfollowups WHERE CustomerID = {$CustomerID} ORDER BY FollowUpID DESC";
    $GotFollowUps = mysqli_query($ClientCon, $GetFollowUps);

    return $GotFollowUps;
}

function AddNewFollowUp($Description, $FollowUpDate, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];
    $ThisUser = $_SESSION["ClientName"];
    $DateAdded = date("Y-m-d");

    $EmployeeID = $Val["EmployeeID"];
    if ($EmployeeID == "") {
        $EmployeeID = 0;
    }

    $Description = CleanInput($Description);
    $DateAdded = date("Y-m-d");

    $AddTask = "INSERT INTO customerfollowups (CustomerID, Description, FollowUpDate, ClientID, EmployeeID, AddedByName, DateAdded, Status) ";
    $AddTask .= "VALUES ({$CustomerID}, '{$Description}', '{$FollowUpDate}', {$ThisClientID}, {$EmployeeID}, '{$ThisUser}', '{$DateAdded}', 0)";

    $DoAddTask = mysqli_query($ClientCon, $AddTask);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding the follow up, please check your input and try again";
    }
}

function GetFollowUp($FollowUpID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetFollowUp = "SELECT * FROM customerfollowups WHERE FollowUpID = {$FollowUpID}";
    $GotFollowUp = mysqli_query($ClientCon, $GetFollowUp);

    return $GotFollowUp;
}

function UpdateFollowUp($Description, $FollowUpDate, $Status, $Outcome, $FollowUpID, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $Description = CleanInput($Description);
    $Outcome = CleanInput($Outcome);

    $UpdateFollowUp = "UPDATE customerfollowups SET Description = '{$Description}', Outcome = '{$Outcome}', Status = {$Status}, FollowUpDate = '{$FollowUpDate}' WHERE FollowUpID = {$FollowUpID}";
    $DoUpdateFollowUp = mysqli_query($ClientCon, $UpdateFollowUp);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the follow up, please check your input and try again";
    }

}

//INVOICING MODULES
function AddInvoiceHeader($UseInvoiceReference, $CustomerReference, $DueDate, $VATNumber, $DiscountPercent, $Address1, $Address2, $City, $State, $PostCode, $Country, $CustomerID, $TaxExempt, $InvoiceNotes, $JobID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];
    $ThisUser = $_SESSION["ClientName"];
    $DateAdded = date("Y-m-d");

    $EmployeeID = $Val["EmployeeID"];
    if ($EmployeeID == "") {
        $EmployeeID = 0;
    }

    if ($TaxExempt == 0) {
        $Taxed = 1;
    } else {
        $Taxed = 0;
    }

    $CustomerReference = CleanInput($CustomerReference);
    $VATNumber = CleanInput($VATNumber);
    $DiscountPercent = CleanInput($DiscountPercent);
    $Address1 = CleanInput($Address1);
    $Address2 = CleanInput($Address2);
    $City = CleanInput($City);
    $State = CleanInput($State);
    $PostCode = CleanInput($PostCode);
    $Country = CleanInput($Country);
    $InvoiceNotes = CleanInput($InvoiceNotes);

    if ($DiscountPercent == "") {
        $DiscountPercent = 0;
    }

    $DateAdded = date("Y-m-d");

    $AddInvoice = "INSERT INTO customerinvoices (CustomerID, InvoiceNumber, InvoiceDate, DueDate, DiscountPercent, InvoiceStatus, Taxed, AddedByClient, AddedByEmployee, AddedByName, Address1, Address2, City, State, PostCode, CountryID, InvoiceNotes) ";
    $AddInvoice .= "VALUES ({$CustomerID}, '{$CustomerReference}', '{$DateAdded}', '{$DueDate}', {$DiscountPercent}, 0, {$Taxed}, {$ThisClientID}, {$EmployeeID}, '{$ThisUser}', '{$Address1}', '{$Address2}', '{$City}', '{$State}', '{$PostCode}', {$Country}, '{$InvoiceNotes}')";
    $DoAddInvoice = mysqli_query($ClientCon, $AddInvoice);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        $InvoiceID = mysqli_insert_id($ClientCon);

        if ($UseInvoiceReference == "true") {
            $Reference = "INV" . $InvoiceID;
            $UpdateInvoice = "UPDATE customerinvoices SET 	InvoiceNumber = '{$Reference}' WHERE InvoiceID = {$InvoiceID}";
            $DoUpdateInvoice = mysqli_query($ClientCon, $UpdateInvoice);
        }

        if ($JobID > 0) {
            //ALSO LINKED THIS INVOICE TO THE JOBCARD

            $UpdateJob = "UPDATE jobcards SET InvoiceID = {$InvoiceID}, DateInvoice = '{$DateAdded}', JobcardStatus = 2 WHERE JobcardID = {$JobID}";
            $DoUpdateJob = mysqli_query($ClientCon, $UpdateJob);
        }

        return $InvoiceID;
    } else {
        return "There was an error adding the invoice header, please check your input and try again" . $Error;
    }
}

function GetInvoiceDetails($InvoiceID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetInvoice = "SELECT * FROM customerinvoices WHERE InvoiceID = {$InvoiceID}";
    $GotInvoice = mysqli_query($ClientCon, $GetInvoice);

    return $GotInvoice;
}

function GetProductItem($ProductID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetProduct = "SELECT * FROM products WHERE ProductID = {$ProductID}";
    $GotProduct = mysqli_query($ClientCon, $GetProduct);

    while ($Val = mysqli_fetch_array($GotProduct)) {
        $ProductDescription = $Val["ProductDescription"];
    }

    return $ProductDescription;
}

function AddInvoiceLineProRata($InvoiceID, $ProductID, $Price, $Quantity, $DiscountPercent, $NumBillingDays, $Days, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    //CHECK COMPANY SETTINGS FOR VAT REGISTER
    $CheckClientSettings = "SELECT * FROM customers, countries WHERE customers.CountryID = countries.CountryID AND
	CustomerID = {$CustomerID}";
    $DoCheckClientSettings = mysqli_query($ClientCon, $CheckClientSettings);


    while ($Val = mysqli_fetch_array($DoCheckClientSettings)) {
        $VATRegistered = $Val["TaxExempt"];
    }


    //PRODUCT DETAILS
    $GetProduct = "SELECT * FROM products WHERE ProductID = {$ProductID}";
    $GotProduct = mysqli_query($ClientCon, $GetProduct);

    while ($Val = mysqli_fetch_array($GotProduct)) {
        $ProductName = $Val["ProductName"];
        $ProductCode = $Val["ProductCode"];
        $ProductDescription = $Val["ProductDescription"];
    }

    //PRICING DETAILS
    $GetPricing = "SELECT * FROM productcost WHERE ProductCostID = {$Price}";
    $GotPricing = mysqli_query($ClientCon, $GetPricing);

    while ($Val = mysqli_fetch_array($GotPricing)) {
        $ProductCostID = $Val["ProductCostID"];
        $BillingType = $Val["BillingType"];
        $ClientCost = $Val["ClientCost"];
        $PackSize = $Val["PackSize"];
        $MeassurementID = $Val["MeasurementID"];
        $StockAffect = $Val["StockAffect"];


        $MeasurementDescription = "";

        if ($MeassurementID > 0) {
            $GetMeassurement = "SELECT * FROM productmeasurement WHERE MeasurementID = {$MeassurementID}";
            $GotMeassurement = mysqli_query($ClientCon, $GetMeassurement);
            echo mysqli_error($ClientCon);

            while ($Measure = mysqli_fetch_array($GotMeassurement)) {
                $MeasurementDescription = $Measure["MeasurementDescription"];
            }

        }
    }

    //OK ENOUGH INFO
    if ($MeasurementDescription != "") {
        $MeasurreDescript = $PackSize . " " . $MeasurementDescription;
    } else {
        $MeasurreDescript = $PackSize;
    }

    $ThisProduct = $ProductName;

    //BECAUSE ITS PRO RATA THE BILL IS PARTIAL
    $ClientCost = $ClientCost * ($NumBillingDays / $Days);

    $CostBeforeVat = $ClientCost;

    $SubTotal = $ClientCost * $Quantity;

    if ($DiscountPercent > 0) {
        $DiscountPercent = ($DiscountPercent / 100);
        $DiscountAmount = $SubTotal * $DiscountPercent;

    } else {
        $DiscountAmount = 0;
    }

    $DiscountAmount = number_format($DiscountAmount, 2, '.', '');

    $VatableAmount = $SubTotal - $DiscountAmount;

    //BEFORE WE DO VAT, LETS WORK OUT UNIT COST NOW
    $StockAffect = $StockAffect * $Quantity;
    $UnitCost = $VatableAmount / $StockAffect;

    $UnitCost = number_format($UnitCost, 2, '.', '');

    //NO WE WANT TO WORK OUT OPUT PROFIT FROM THIS
    $GetLastUnitPrice = "SELECT UnitCost FROM suppliercostingtracking WHERE ProductID = {$ProductID} ORDER BY SupplierCostingID DESC LIMIT 1";
    $GotLastUnitPrice = mysqli_query($ClientCon, $GetLastUnitPrice);

    $FoundUnitPrice = mysqli_num_rows($GotLastUnitPrice);

    if ($FoundUnitPrice > 0) {
        while ($SupplierUnit = mysqli_fetch_array($GotLastUnitPrice)) {
            $SupplierUnitCost = $SupplierUnit["UnitCost"];
        }

        $ProfitPerUnit = $UnitCost - $SupplierUnitCost;
    } else {
        $SupplierUnitCost = 0;
        $ProfitPerUnit = $UnitCost;
    }

    //TOTAL PROFIT HERE
    $LineProfit = $ProfitPerUnit * $StockAffect;

    if ($VATRegistered == 0) {
        //MUST DO VAT
        $VatInc = $VatableAmount * 1.14;
        $Vat = $VatInc - $VatableAmount;
    } else {
        $Vat = 0;
    }

    $Vat = number_format($Vat, 2, '.', '');

    $LineTotal = $VatableAmount + $Vat;

    $InsertLine = "INSERT INTO customerinvoicelines (InvoiceID, Description, Quantity, Price, ProductID, ProductCode, LineSubTotal, LineDiscount, LineVAT, LineTotal, BillingType, StockAffect, MeassurementDescription, UnitPrice, UnitPriceCost, Profit) ";
    $InsertLine .= "VALUES ({$InvoiceID}, '{$ThisProduct}', {$Quantity}, {$ClientCost}, {$ProductID}, '{$ProductCode}', {$SubTotal}, {$DiscountAmount}, {$Vat}, {$LineTotal}, '{$BillingType}', {$StockAffect}, '{$MeasurreDescript}', {$UnitCost}, {$SupplierUnitCost}, {$LineProfit})";
    $DoInsertLine = mysqli_query($ClientCon, $InsertLine);

    $Error = mysqli_error($ClientCon);


    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding the invoice line";
    }
}

function AddInvoiceLine($InvoiceID, $ProductID, $Price, $Quantity, $DiscountPercent, $WarehouseID, $CustomerID = "")
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    //CHECK COMPANY SETTINGS FOR VAT REGISTER
    $CheckClientSettings = "SELECT * FROM customers, countries WHERE customers.CountryID = countries.CountryID AND
	CustomerID = {$CustomerID}";
    $DoCheckClientSettings = mysqli_query($ClientCon, $CheckClientSettings);


    while ($Val = mysqli_fetch_array($DoCheckClientSettings)) {
        $VATRegistered = $Val["TaxExempt"];
    }

    //PRODUCT DETAILS
    $GetProduct = "SELECT * FROM products WHERE ProductID = {$ProductID}";
    $GotProduct = mysqli_query($ClientCon, $GetProduct);

    while ($Val = mysqli_fetch_array($GotProduct)) {
        $ProductName = $Val["ProductName"];
        $ProductCode = $Val["ProductCode"];
        $ProductDescription = $Val["ProductDescription"];
    }

    //PRICING DETAILS
    $GetPricing = "SELECT * FROM productcost WHERE ProductCostID = {$Price}";
    $GotPricing = mysqli_query($ClientCon, $GetPricing);

    while ($Val = mysqli_fetch_array($GotPricing)) {
        $ProductCostID = $Val["ProductCostID"];
        $BillingType = $Val["BillingType"];
        $ClientCost = $Val["ClientCost"];
        $PackSize = $Val["PackSize"];
        $MeassurementID = $Val["MeasurementID"];
        $StockAffect = $Val["StockAffect"];


        $MeasurementDescription = "";

        if ($MeassurementID > 0) {
            $GetMeassurement = "SELECT * FROM productmeasurement WHERE MeasurementID = {$MeassurementID}";
            $GotMeassurement = mysqli_query($ClientCon, $GetMeassurement);
            echo mysqli_error($ClientCon);

            while ($Measure = mysqli_fetch_array($GotMeassurement)) {
                $MeasurementDescription = $Measure["MeasurementDescription"];
            }

        }
    }

    //OK ENOUGH INFO
    if ($MeasurementDescription != "") {
        $MeasurreDescript = $PackSize . " " . $MeasurementDescription;
    } else {
        $MeasurreDescript = $PackSize;
    }

    $ThisProduct = $ProductName;

    $CostBeforeVat = $ClientCost;


    $SubTotal = $ClientCost * $Quantity;

    if ($DiscountPercent > 0) {
        $DiscountPercent = ($DiscountPercent / 100);
        $DiscountAmount = $SubTotal * $DiscountPercent;

    } else {
        $DiscountAmount = 0;
    }

    $DiscountAmount = number_format($DiscountAmount, 2, '.', '');

    $VatableAmount = $SubTotal - $DiscountAmount;

    //BEFORE WE DO VAT, LETS WORK OUT UNIT COST NOW
    $StockAffect = $StockAffect * $Quantity;
    $UnitCost = $VatableAmount / $StockAffect;

    $UnitCost = number_format($UnitCost, 2, '.', '');

    //NO WE WANT TO WORK OUT OPUT PROFIT FROM THIS
    $GetLastUnitPrice = "SELECT UnitCost FROM suppliercostingtracking WHERE ProductID = {$ProductID} ORDER BY SupplierCostingID DESC LIMIT 1";
    $GotLastUnitPrice = mysqli_query($ClientCon, $GetLastUnitPrice);

    $FoundUnitPrice = mysqli_num_rows($GotLastUnitPrice);

    if ($FoundUnitPrice > 0) {
        while ($SupplierUnit = mysqli_fetch_array($GotLastUnitPrice)) {
            $SupplierUnitCost = $SupplierUnit["UnitCost"];
        }

        $ProfitPerUnit = $UnitCost - $SupplierUnitCost;
    } else {
        $SupplierUnitCost = 0;
        $ProfitPerUnit = $UnitCost;
    }

    //TOTAL PROFIT HERE
    $LineProfit = $ProfitPerUnit * $StockAffect;

    if ($VATRegistered == 0) {
        //MUST DO VAT
        $VatInc = $VatableAmount * 1.14;
        $Vat = $VatInc - $VatableAmount;
    } else {
        $Vat = 0;
    }

    $Vat = number_format($Vat, 2, '.', '');

    $LineTotal = $VatableAmount + $Vat;

    $InsertLine = "INSERT INTO customerinvoicelines (InvoiceID, Description, Quantity, Price, ProductID, ProductCode, LineSubTotal, LineDiscount, LineVAT, LineTotal, BillingType, StockAffect, MeassurementDescription, UnitPrice, UnitPriceCost, Profit, WarehouseID) ";
    $InsertLine .= "VALUES ({$InvoiceID}, '{$ThisProduct}', {$Quantity}, {$ClientCost}, {$ProductID}, '{$ProductCode}', {$SubTotal}, {$DiscountAmount}, {$Vat}, {$LineTotal}, '{$BillingType}', {$StockAffect}, '{$MeasurreDescript}', {$UnitCost}, {$SupplierUnitCost}, {$LineProfit}, {$WarehouseID})";
    $DoInsertLine = mysqli_query($ClientCon, $InsertLine);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding the invoice line";
    }
}

function CancelClientProduct($ClientProductID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $UpdateClientProduct = "UPDATE customerproducts SET ClientProductStatus = 1 WHERE ClientProductID = {$ClientProductID}";
    $DoUpdateClientProduct = mysqli_query($ClientCon, $UpdateClientProduct);

    return "OK";
}

function AddProductRecurring($Product, $Price, $Quantity, $InvoiceType, $CustomerID, $NextRun, $TaxExempt, $Address1, $Address2, $City, $State, $PostCode, $Country, $RecurringTimes, $WareHouseID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $DateAdded = date("Y-m-d");

    $ThisClientID = $_SESSION["ClientID"];
    $ThisUser = $_SESSION["ClientName"];
    $DateAdded = date("Y-m-d");


    $CheckClientSettings = "SELECT * FROM customers, countries WHERE customers.CountryID = countries.CountryID AND
	CustomerID = {$CustomerID}";
    $DoCheckClientSettings = mysqli_query($ClientCon, $CheckClientSettings);


    while ($Val = mysqli_fetch_array($DoCheckClientSettings)) {
        $VATRegistered = $Val["TaxExempt"];
    }

    $EmployeeID = 0;
    $CheckCompSettings = "SELECT * FROM companysettings";
    $DoCheckCompSettings = mysqli_query($ClientCon, $CheckCompSettings);

    while ($Val = mysqli_fetch_array($DoCheckCompSettings)) {
        $EmployeeID = $Val["EmployeeID"];
    }

    $NextRecurringDay = date("d", strtotime($NextRun));
    $RecurredTimes = 0;


    //GET BILLING TYPE FOR THIS
    $GetBillingType = "SELECT BillingType, ClientCost FROM productcost WHERE ProductCostID = {$Price}";
    $GotBillingType = mysqli_query($ClientCon, $GetBillingType);

    while ($Val = mysqli_fetch_array($GotBillingType)) {
        $BillingType = $Val["BillingType"];
        $ClientCost = $Val["ClientCost"];

        $ClientCost = $ClientCost * $Quantity;
    }

    if ($InvoiceType == 1) //PRO RATA
    {
        $FirstBilling = date("Y-m-d");
        $start = strtotime($FirstBilling);
        $end = strtotime($NextRun);
        $RecurredTimes = 1;
        $NumBillingDays = ceil(abs($end - $start) / 86400);
        //HOW MANY DAYS IN THIS MONTH
        $Days = date("d", mktime(0, 0, 0, date("m") + 1, 1 - 1, date("Y")));
    } else if ($InvoiceType == 2) //FULL INVOICE
    {
        $FirstBilling = date("Y-m-d");
        $RecurredTimes = 1;
        if ($BillingType == "Monthly") {
            $NextRun = date("Y-m-d", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")));
        } else if ($BillingType == "Quarterly") {
            $NextRun = date("Y-m-d", mktime(0, 0, 0, date("m") + 3, date("d"), date("Y")));
        } else if ($BillingType == "Semi-Annually") {
            $NextRun = date("Y-m-d", mktime(0, 0, 0, date("m") + 6, date("d"), date("Y")));
        } else if ($BillingType == "Annually") {
            $NextRun = date("Y-m-d", mktime(0, 0, 0, date("m") + 12, date("d"), date("Y")));
        }
    } else if ($InvoiceType == 3) //NEXT INVOICE RUN ONLY
    {
        $FirstBilling = $NextRun;
    }

    //NOW WE ADD THE RECURRING SIDE
    $InsertRecurring = "INSERT INTO customerproducts (CustomerID, ProductID, ProductCostID , FirstBillingDate, NextBillingDate, ProductDateAdded, ClientID, EmployeeID, AddedByName, ClientProductStatus, ProductName, ProductQuantity, RecurringTimes, RecurredTimes, WarehouseID, RecurringAmount) ";
    $InsertRecurring .= "VALUES ({$CustomerID}, {$Product}, {$Price}, '{$FirstBilling}', '{$NextRun}', '{$DateAdded}', {$ThisClientID}, {$EmployeeID}, '{$ThisUser}', 2, '{$ThisProduct}', {$Quantity}, {$RecurringTimes}, {$RecurredTimes}, {$WareHouseID}, {$ClientCost})";
    $DoInsertRecurring = mysqli_query($ClientCon, $InsertRecurring);
    $Error = mysqli_error($ClientCon);


    if ($Error == "") {
        if ($InvoiceType == 1) //CREATE A PRO RATA INVOICE AND RECUR ON RECURRING DATE
        {
            $DueDate = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 7, date("Y")));
            $NewInvoiceID = AddInvoiceHeader("true", '', $DueDate, $VATNumber, 0, $Address1, $Address2, $City, $State, $PostCode, $Country, $CustomerID, $TaxExempt, '', 0);
            if ($NewInvoiceID > 0) {
                $AddInvoiceItem = AddInvoiceLineProRata($NewInvoiceID, $Product, $Price, $Quantity, 0, $NumBillingDays, $Days, $CustomerID);
                if ($AddInvoiceItem == "OK") {
                    //COMPLETE INVOICE AND SEND TO CLIENT
                    $PublisInvoice = PublishInvoice($NewInvoiceID, 'publish');
                    return "The product has been added successfully and the pro rata invoice has been created";
                } else {

                    return "Error";
                }
            } else {
                return "Error";
            }
        } else if ($InvoiceType == 2) //CREATE FULL INVOICE
        {
            $DueDate = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 7, date("Y")));
            $NewInvoiceID = AddInvoiceHeader("true", '', $DueDate, $VATNumber, 0, $Address1, $Address2, $City, $State, $PostCode, $Country, $CustomerID, $TaxExempt, '', 0);
            if ($NewInvoiceID > 0) {
                //function AddInvoiceLine($InvoiceID, $ProductID, $Price, $Quantity, $DiscountPercent, $WarehouseID)
                $AddInvoiceItem = AddInvoiceLine($NewInvoiceID, $Product, $Price, $Quantity, 0, $WareHouseID);
                if ($AddInvoiceItem == "OK") {
                    //COMPLETE INVOICE AND SEND TO CLIENT
                    PublishInvoice($NewInvoiceID, 'publish');
                    return "The product has been added successfully and the invoice has been created";
                } else {
                    echo $AddInvoiceItem;
                    return "Error";
                }
            } else {
                return "Error";
            }
        } else if ($InvoiceType == 3) //DONT CREATE INVOICE NOW, ONLY ON RECURRING DATE
        {
            return "The product has been added successfully and the next invoice run will be " . $NextRun;
        }
    } else {
        echo $Error;
        return "Error";
    }
}

function GetCompanyRecurringDay()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetRecurringDay = "SELECT RecurringInvoiceDay FROM companysettings";
    $GotRecurringDay = mysqli_query($ClientCon, $GetRecurringDay);

    while ($Val = mysqli_fetch_array($GotRecurringDay)) {
        $RecurringInvoiceDay = $Val["RecurringInvoiceDay"];
    }

    return $RecurringInvoiceDay;
}

function AddCustomInvoiceItemRecurring($CustomItem, $CustomItemPrice, $CustomQuantity)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $CustomItem = CleanInput($CustomItem);
    $DateAdded = date("Y-m-d");

    //GET RECURRING INVOICE DAY
    $GetRecurringDay = "SELECT RecurringInvoiceDay FROM companysettings";
    $GotRecurringDay = mysqli_query($ClientCon, $GetRecurringDay);

    while ($Val = mysqli_fetch_array($GotRecurringDay)) {
        $RecurringInvoiceDay = $Val["RecurringInvoiceDay"];
    }


}

function AddCustomInvoiceItem($InvoiceID, $CustomItem, $CustomItemPrice, $Quantity, $DiscountPercent, $CostPrice, $CustomerID = "")
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $CustomItem = CleanInput($CustomItem);

    $Profit = ($CustomItemPrice - $CostPrice) * $Quantity;


    //CHECK COMPANY SETTINGS FOR VAT REGISTER
    $CheckClientSettings = "SELECT * FROM customers, countries WHERE customers.CountryID = countries.CountryID AND
	CustomerID = {$CustomerID}";
    $DoCheckClientSettings = mysqli_query($ClientCon, $CheckClientSettings);


    while ($Val = mysqli_fetch_array($DoCheckClientSettings)) {
        $VATRegistered = $Val["TaxExempt"];
    }

    $ClientCost = $CustomItemPrice;

    $ThisProduct = $CustomItem;

    $CostBeforeVat = $CustomItemPrice;


    $SubTotal = $CustomItemPrice * $Quantity;

    if ($DiscountPercent > 0) {
        $DiscountPercent = ($DiscountPercent / 100);
        $DiscountAmount = $SubTotal * $DiscountPercent;
    } else {
        $DiscountAmount = 0;
    }

    //$DiscountAmount = number_format($DiscountAmount,2, '.', '');

    $VatableAmount = $SubTotal - $DiscountAmount;

    if ($VATRegistered == 0) {
        //MUST DO VAT
        $VatInc = $VatableAmount * 1.14;
        $Vat = $VatInc - $VatableAmount;
    } else {
        $Vat = 0;
    }

    $Vat = number_format($Vat, 2, '.', '');

    $LineTotal = $VatableAmount + $Vat;

    $StockAffect = 0;


    $InsertLine = "INSERT INTO customerinvoicelines (InvoiceID, Description, Quantity, Price, ProductID, ProductCode, LineSubTotal, LineDiscount, LineVAT, LineTotal, BillingType, StockAffect, MeassurementDescription, WarehouseID, Profit) ";
    $InsertLine .= "VALUES ({$InvoiceID}, '{$ThisProduct}', {$Quantity}, {$ClientCost}, 0, 'CUSTOM', {$SubTotal}, {$DiscountAmount}, {$Vat}, {$LineTotal}, 'Once-Off', {$StockAffect}, '', 0, {$Profit})";
    $DoInsertLine = mysqli_query($ClientCon, $InsertLine);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding the invoice line";
    }
}

function GetInvoiceLines($InvoiceID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetLines = "SELECT * FROM customerinvoicelines WHERE InvoiceID = {$InvoiceID} AND GroupID = 0 ORDER BY RowOrder";
    $GotLines = mysqli_query($ClientCon, $GetLines);
    echo mysqli_error($ClientCon);

    return $GotLines;
}

function GetMinOrder($PricingID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetMin = "SELECT MinimumOrder, ProRataBilling FROM productcost WHERE ProductCostID = {$PricingID}";
    $GotMin = mysqli_query($ClientCon, $GetMin);

    while ($Val = mysqli_fetch_array($GotMin)) {
        $Minimum = $Val["MinimumOrder"];
        $ProRataBilling = $Val["ProRataBilling"];
    }

    $Details[0] = $Minimum;
    $Details[1] = $ProRataBilling;

    return $Minimum;
}

function GetMinOrderProduct($PricingID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetMin = "SELECT MinimumOrder, ProRataBilling FROM productcost WHERE ProductCostID = {$PricingID}";
    $GotMin = mysqli_query($ClientCon, $GetMin);

    while ($Val = mysqli_fetch_array($GotMin)) {
        $Minimum = $Val["MinimumOrder"];
        $ProRataBilling = $Val["ProRataBilling"];
    }

    $Details[0] = $Minimum;
    $Details[1] = $ProRataBilling;

    return $Details;
}

function DeleteSupplierInvoiceLine($SupplierInvoiceLineItemID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $DeleteLine = "DELETE FROM supplierorderlines WHERE SupplierInvoiceLineItemID = {$SupplierInvoiceLineItemID}";
    $DoDeleteLine = mysqli_query($ClientCon, $DeleteLine);
    echo mysqli_error($ClientCon);

    return "OK";
}

function DeleteInvoiceLine($InvoiceLineItemID, $InvoiceID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $DeleteLine = "DELETE FROM customerinvoicelines WHERE InvoiceID = {$InvoiceID} AND InvoiceLineItemID = {$InvoiceLineItemID}";
    $DoDeleteLine = mysqli_query($ClientCon, $DeleteLine);
    echo mysqli_error($ClientCon);

    return "OK";
}

function PublishInvoice($InvoiceID, $PublishType, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $UpdateInvoice = "UPDATE customerinvoices SET InvoiceStatus = 1 WHERE InvoiceID = {$InvoiceID}";
    $DoUpdateInvoice = mysqli_query($ClientCon, $UpdateInvoice);

    $Error = mysqli_error($ClientCon);


    if ($Error == "") {

        //NOW LETS REDUCE THE STOCK AS WELL
        $GetStock = "SELECT DISTINCT(ProductID), SUM(StockAffect) AS StockUsed, WarehouseID FROM customerinvoicelines WHERE ProductID > 0 AND InvoiceID = {$InvoiceID} GROUP BY ProductID, WarehouseID";
        $GotStock = mysqli_query($ClientCon, $GetStock);
        $StockDate = date("Y-m-d :H:i:s");

        while ($Val = mysqli_fetch_array($GotStock)) {
            $StockProd = $Val["ProductID"];
            $StockUsed = $Val["StockUsed"];
            $WarehouseID = $Val["WarehouseID"];

            $GetLastUnitPrice = "SELECT UnitCost FROM suppliercostingtracking WHERE ProductID = {$StockProd} ORDER BY SupplierCostingID DESC LIMIT 1";
            $GotLastUnitPrice = mysqli_query($ClientCon, $GetLastUnitPrice);

            $FoundUnitPrice = mysqli_num_rows($GotLastUnitPrice);

            if ($FoundUnitPrice > 0) {
                while ($SupplierUnit = mysqli_fetch_array($GotLastUnitPrice)) {
                    $SupplierUnitCost = $SupplierUnit["UnitCost"];
                }
            } else {
                $SupplierUnitCost = 0;
            }

            //THEN REMOVE STOCK, MAKE IT NEGATIVE
            $StockUsed = $StockUsed * -1;

            $InsertStockMove = "INSERT INTO productstock (ProductID, Stock, DateAdded, StockType, UnitCost, InvoiceID, WarehouseID) ";
            $InsertStockMove .= "VALUES ({$StockProd}, {$StockUsed}, '{$StockDate}', 'Sell', {$SupplierUnitCost}, {$InvoiceID}, {$WarehouseID})";
            $DoInsertStockMove = mysqli_query($ClientCon, $InsertStockMove);

        }

        if ($PublishType == "email") {
            SendCustomerInvoice($CustomerID, $InvoiceID);

            return "OK";
        } else {
            return "OK";
        }
    } else {
        return "There was an error publishing the invoice, please check and try again";
    }
}

function UpdateInvoiceStatus($NewStatus, $InvoiceID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $UpdateStatus = "UPDATE customerinvoices SET InvoiceStatus = {$NewStatus} WHERE InvoiceID = {$InvoiceID}";
    $DoUpdateStatus = mysqli_query($ClientCon, $UpdateStatus);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the invoice status";
    }
}

//QUOTE MODULE
function GetAllCustomerQuotes($CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $ThisClientID = $_SESSION["ClientID"];

    $GetQuotes = "SELECT * FROM customerquotes WHERE CustomerID = {$CustomerID} ORDER BY QuoteID DESC";
    $GotQuotes = mysqli_query($ClientCon, $GetQuotes);
    echo mysqli_error($ClientCon);

    return $GotQuotes;
}

function GetQuoteTotal($QuoteID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $ThisClientID = $_SESSION["ClientID"];

    $GetQuoteTotal = "SELECT SUM(LineTotal) AS QuoteTotal FROM customerquotelines WHERE QuoteID = {$QuoteID}";
    $GotQuoteTotal = mysqli_query($ClientCon, $GetQuoteTotal);

    while ($Val = mysqli_fetch_array($GotQuoteTotal)) {
        $QuoteTotal = $Val["QuoteTotal"];
    }


    return $QuoteTotal;
}

function AddQuoteHeader($Expiry, $VATNumber, $DiscountPercent, $Address1, $Address2, $City, $State, $PostCode, $Country, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];
    $ThisUser = $_SESSION["ClientName"];
    $DateAdded = date("Y-m-d");

    $EmployeeID = $Val["EmployeeID"];
    if ($EmployeeID == "") {
        $EmployeeID = 0;
    }

    if ($TaxExempt == 0) {
        $Taxed = 1;
    } else {
        $Taxed = 0;
    }

    $CustomerReference = CleanInput($CustomerReference);
    $VATNumber = CleanInput($VATNumber);
    $DiscountPercent = CleanInput($DiscountPercent);
    $Address1 = CleanInput($Address1);
    $Address2 = CleanInput($Address2);
    $City = CleanInput($City);
    $State = CleanInput($State);
    $PostCode = CleanInput($PostCode);
    $Country = CleanInput($Country);

    if ($DiscountPercent == "") {
        $DiscountPercent = 0;
    }

    $DateAdded = date("Y-m-d");

    $AddInvoice = "INSERT INTO customerquotes (CustomerID, QuoteDate, ExpiryDate, DiscountPercent, QuoteStatus, AddedByClient, AddedByEmployee, AddedByName, Address1, Address2, City, State, PostCode, CountryID) ";
    $AddInvoice .= "VALUES ({$CustomerID}, '{$DateAdded}', '{$Expiry}', {$DiscountPercent}, 0, {$ThisClientID}, {$EmployeeID}, '{$ThisUser}', '{$Address1}', '{$Address2}', '{$City}', '{$State}', '{$PostCode}', {$Country})";
    $DoAddInvoice = mysqli_query($ClientCon, $AddInvoice);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        $QuoteID = mysqli_insert_id($ClientCon);

        if ($UseInvoiceReference == "true") {
            $Reference = "QU000000" . $QuoteID;
            $UpdateInvoice = "UPDATE customerquotes SET 	QuoteNumber = '{$Reference}' WHERE QuoteID = {$QuoteID}";
            $DoUpdateInvoice = mysqli_query($ClientCon, $UpdateInvoice);
        }

        return $QuoteID;
    } else {
        return "There was an error adding the quote header, please check your input and try again" . $Error;
    }
}

function GetQuoteDetails($QuoteID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetQuote = "SELECT * FROM customerquotes WHERE QuoteID = {$QuoteID}";
    $GotQuote = mysqli_query($ClientCon, $GetQuote);

    return $GotQuote;
}

function GetQuoteLines($QuoteID, $BillingType = '')
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetLines = "SELECT * FROM customerquotelines WHERE QuoteID = {$QuoteID} AND BillingType = '{$BillingType}'";
    $GotLines = mysqli_query($ClientCon, $GetLines);
    echo mysqli_error($ClientCon);

    return $GotLines;
}

function AddCustomQuoteItem($QuoteID, $CustomItem, $CustomItemPrice, $Quantity, $DiscountPercent, $BillingType, $CustomerID = "")
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $CustomItem = CleanInput($CustomItem);

    //CHECK COMPANY SETTINGS FOR VAT REGISTER
    $CheckClientSettings = "SELECT * FROM customers, countries WHERE customers.CountryID = countries.CountryID AND
	CustomerID = {$CustomerID}";
    $DoCheckClientSettings = mysqli_query($ClientCon, $CheckClientSettings);


    while ($Val = mysqli_fetch_array($DoCheckClientSettings)) {
        $VATRegistered = $Val["TaxExempt"];
    }

    $ClientCost = $CustomItemPrice;

    $ThisProduct = $CustomItem;

    $CostBeforeVat = $CustomItemPrice;


    $SubTotal = $CustomItemPrice * $Quantity;

    if ($DiscountPercent > 0) {
        $DiscountPercent = ($DiscountPercent / 100);
        $DiscountAmount = $SubTotal * $DiscountPercent;
    } else {
        $DiscountAmount = 0;
    }

    //$DiscountAmount = number_format($DiscountAmount,2, '.', '');

    $VatableAmount = $SubTotal - $DiscountAmount;

    if ($VATRegistered == 0) {
        //MUST DO VAT
        $VatInc = $VatableAmount * 1.14;
        $Vat = $VatInc - $VatableAmount;
    } else {
        $Vat = 0;
    }

    $Vat = number_format($Vat, 2, '.', '');

    $LineTotal = $VatableAmount + $Vat;

    $StockAffect = 0;


    $InsertLine = "INSERT INTO customerquotelines (QuoteID, Description, Quantity, Price, ProductID, ProductCode, LineSubTotal, LineDiscount, LineVAT, LineTotal, BillingType, StockAffect, MeassurementDescription) ";
    $InsertLine .= "VALUES ({$QuoteID}, '{$ThisProduct}', {$Quantity}, {$ClientCost}, 0, 'CUSTOM', {$SubTotal}, {$DiscountAmount}, {$Vat}, {$LineTotal}, '{$BillingType}', {$StockAffect}, '')";
    $DoInsertLine = mysqli_query($ClientCon, $InsertLine);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding the quote line" . $InsertLine;
    }
}

function AddQuoteLine($QuoteID, $ProductID, $Price, $Quantity, $DiscountPercent, $CustomerID = "")
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    //CHECK COMPANY SETTINGS FOR VAT REGISTER
    $CheckClientSettings = "SELECT * FROM customers, countries WHERE customers.CountryID = countries.CountryID AND
	CustomerID = {$CustomerID}";
    $DoCheckClientSettings = mysqli_query($ClientCon, $CheckClientSettings);


    while ($Val = mysqli_fetch_array($DoCheckClientSettings)) {
        $VATRegistered = $Val["TaxExempt"];
    }


    //PRODUCT DETAILS
    $GetProduct = "SELECT * FROM products WHERE ProductID = {$ProductID}";
    $GotProduct = mysqli_query($ClientCon, $GetProduct);

    while ($Val = mysqli_fetch_array($GotProduct)) {
        $ProductName = $Val["ProductName"];
        $ProductCode = $Val["ProductCode"];
        $ProductDescription = $Val["ProductDescription"];
    }

    //PRICING DETAILS
    $GetPricing = "SELECT * FROM productcost WHERE ProductCostID = {$Price}";
    $GotPricing = mysqli_query($ClientCon, $GetPricing);

    while ($Val = mysqli_fetch_array($GotPricing)) {
        $ProductCostID = $Val["ProductCostID"];
        $BillingType = $Val["BillingType"];
        $ClientCost = $Val["ClientCost"];
        $PackSize = $Val["PackSize"];
        $MeassurementID = $Val["MeasurementID"];
        $StockAffect = $Val["StockAffect"];

        $MeasurementDescription = "";

        if ($MeassurementID > 0) {
            $GetMeassurement = "SELECT * FROM productmeasurement WHERE MeasurementID = {$MeassurementID}";
            $GotMeassurement = mysqli_query($ClientCon, $GetMeassurement);
            echo mysqli_error($ClientCon);

            while ($Measure = mysqli_fetch_array($GotMeassurement)) {
                $MeasurementDescription = $Measure["MeasurementDescription"];
            }

        }
    }

    //OK ENOUGH INFO
    if ($MeasurementDescription != "") {
        $MeasurreDescript = $PackSize . " " . $MeasurementDescription;
    } else {
        $MeasurreDescript = $PackSize;
    }

    $ThisProduct = $ProductName . " - " . $ProductDescription;

    $CostBeforeVat = $ClientCost;


    $SubTotal = $ClientCost * $Quantity;

    if ($DiscountPercent > 0) {
        $DiscountPercent = ($DiscountPercent / 100);
        $DiscountAmount = $SubTotal * $DiscountPercent;

    } else {
        $DiscountAmount = 0;
    }

    $DiscountAmount = number_format($DiscountAmount, 2, '.', '');

    $VatableAmount = $SubTotal - $DiscountAmount;

    if ($VATRegistered == 0) {
        //MUST DO VAT
        $VatInc = $VatableAmount * 1.14;
        $Vat = $VatInc - $VatableAmount;
    } else {
        $Vat = 0;
    }

    $Vat = number_format($Vat, 2, '.', '');

    $LineTotal = $VatableAmount + $Vat;

    $StockAffect = $StockAffect * $Quantity;


    $InsertLine = "INSERT INTO customerquotelines (QuoteID, Description, Quantity, Price, ProductID, ProductCode, LineSubTotal, LineDiscount, LineVAT, LineTotal, BillingType, StockAffect, MeassurementDescription, ProductCostID) ";
    $InsertLine .= "VALUES ({$QuoteID}, '{$ThisProduct}', {$Quantity}, {$ClientCost}, {$ProductID}, '{$ProductCode}', {$SubTotal}, {$DiscountAmount}, {$Vat}, {$LineTotal}, '{$BillingType}', {$StockAffect}, '{$MeasurreDescript}', {$ProductCostID})";
    $DoInsertLine = mysqli_query($ClientCon, $InsertLine);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding the quote line";
    }
}

function DeleteQuoteLine($QuoteLineItemID, $QuoteID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $DeleteLine = "DELETE FROM customerquotelines WHERE QuoteID = {$QuoteID} AND QuoteLineItemID = {$QuoteLineItemID}";
    $DoDeleteLine = mysqli_query($ClientCon, $DeleteLine);
    echo mysqli_error($ClientCon);

    return "OK";
}

function PublishQuote($QuoteID, $PublishType, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $UpdateQuote = "UPDATE customerquotes SET QuoteStatus = 1 WHERE QuoteID = {$QuoteID}";
    $DoUpdateQuote = mysqli_query($ClientCon, $UpdateQuote);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        if ($PublishType == "email") {
            EmailCustomerQuote($QuoteID, $CustomerID);

            return "OK";
        } else {
            return "OK";
        }
    } else {
        return "There was an error publishing the quote, please check and try again";
    }
}

function EmailCustomerQuote($QuoteID, $CustomerID)
{
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
        $TermsAndConditions = $Val["TermsAndConditions"];

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
    $GetInvoice = "SELECT * FROM customerquotes WHERE QuoteID = {$QuoteID} AND CustomerID = {$CustomerID}";
    $GotInvoice = mysqli_query($ClientCon, $GetInvoice);

    while ($Val = mysqli_fetch_array($GotInvoice)) {
        $QuoteNumber = $Val["QuoteNumber"];
        $QuoteDate = $Val["QuoteDate"];
        $ExpiryDate = $Val["ExpiryDate"];
        $AddedByName = $Val["AddedByName"];
        $ProposalText = $Val["ProposalText"];
        $FooterText = $Val["FooterText"];
    }


    //AND THEN THE LINES - ONCE OFF FIRST
    $GetLinesOnce = "SELECT * FROM customerquotelines WHERE QuoteID = {$QuoteID} AND BillingType = 'Once-Off'";
    $GotLinesOnce = mysqli_query($ClientCon, $GetLinesOnce);
    $NumOnceOff = mysqli_num_rows($GotLinesOnce);

    //AND THEN THE LINES - MONTHLY
    $GetLinesMonthly = "SELECT * FROM customerquotelines WHERE QuoteID = {$QuoteID} AND BillingType = 'Monthly'";
    $GotLinesMonthly = mysqli_query($ClientCon, $GetLinesMonthly);
    $NumMonthly = mysqli_num_rows($GotLinesMonthly);

    //AND THEN THE LINES - Quarterly
    $GetLinesQuarterly = "SELECT * FROM customerquotelines WHERE QuoteID = {$QuoteID} AND BillingType = 'Quarterly'";
    $GotLinesQuarterly = mysqli_query($ClientCon, $GetLinesQuarterly);
    $NumQuarterly = mysqli_num_rows($GotLinesQuarterly);

    //AND THEN THE LINES - Semi-Annually
    $GetLinesSemiAnnually = "SELECT * FROM customerquotelines WHERE QuoteID = {$QuoteID} AND BillingType = 'Semi-Annually'";
    $GotLinesSemiAnnually = mysqli_query($ClientCon, $GetLinesSemiAnnually);
    $NumSemiAnnually = mysqli_num_rows($GotLinesSemiAnnually);

    //AND THEN THE LINES - Annually
    $GetLinesAnnually = "SELECT * FROM customerquotelines WHERE QuoteID = {$QuoteID} AND BillingType = 'Annually'";
    $GotLinesAnnually = mysqli_query($ClientCon, $GetLinesAnnually);
    $NumAnnually = mysqli_num_rows($GotLinesAnnually);

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

    $pdf->addText(350, 810, 10, "<b>Customer Quote</b>");
    $pdf->addText(350, 790, 10, "<b>Quote Number</b>");
    $pdf->addText(450, 790, 10, 'QR' . $QuoteID);
    $pdf->addText(350, 770, 10, "<b>Quote Date</b>");
    $pdf->addText(450, 770, 10, $QuoteDate);
    $pdf->addText(350, 750, 10, "<b>Expiry Date</b>");
    $pdf->addText(450, 750, 10, $ExpiryDate);
    $pdf->addText(350, 730, 10, "<b>Client Code</b>");
    $pdf->addText(450, 730, 10, $DepositReference);


    //BOTTOM
    $pdf->addText(470, 20, 8, "Quote Number " . $QuoteID);


    $data = array();

    $pdf->ezText("Quote Details", 10, array('aleft' => 20));
    $pdf->ezSetDy(-10);

    $data[] = array('<b>Customer Details</b>' => '<b>' . $CompanyName . '</b>', '<b>Our Details</b>' => '<b>' . $DisplayCompany . '</b>', '<b>Banking Details</b>' => 'Bank : ' . $BankName);
    $data[] = array('<b>Customer Details</b>' => 'Tel: ' . $ContactNumber, '<b>Our Details</b>' => 'Tel : ' . $DisplayTel, '<b>Banking Details</b>' => 'Account Holder : ' . $AccountHolder);
    $data[] = array('<b>Customer Details</b>' => 'Email : ' . $EmailAddress, '<b>Our Details</b>' => 'Email : ' . $DisplayEmail, '<b>Banking Details</b>' => 'Account Number : ' . $AccountNumber);
    $data[] = array('<b>Customer Details</b>' => 'VAT Number : ' . $VatNumber, '<b>Our Details</b>' => 'VAT Number : ' . $DisplayVat, '<b>Banking Details</b>' => 'Branch Code : ' . $BranchCode);
    $data[] = array('<b>Customer Details</b>' => '', '<b>Our Details</b>' => 'Company Reg : ' . $CompanyReg, '<b>Banking Details</b>' => 'Account Type : ' . $AccountType);
    $data[] = array('<b>Customer Details</b>' => '<b>Address</b>', '<b>Our Details</b>' => '<b>Address</b>', '<b>Banking Details</b>' => 'Deposit Reference: ' . $DepositReference);
    $data[] = array('<b>Customer Details</b>' => $CustomerAddress1, '<b>Our Details</b>' => $Address1, '<b>Banking Details</b>' => '');
    $data[] = array('<b>Customer Details</b>' => $CustomerAddress2, '<b>Our Details</b>' => $Address2, '<b>Banking Details</b>' => '');
    $data[] = array('<b>Customer Details</b>' => $CustomerCity, '<b>Our Details</b>' => $City, '<b>Banking Details</b>' => '');
    $data[] = array('<b>Customer Details</b>' => $CustomerRegion, '<b>Our Details</b>' => $Region, '<b>Banking Details</b>' => '');
    $data[] = array('<b>Customer Details</b>' => $CustomerCountryName, '<b>Our Details</b>' => $CountryName, '<b>Banking Details</b>' => '');
    $data[] = array('<b>Customer Details</b>' => $CustomerPostCode, '<b>Our Details</b>' => $PostCode, '<b>Banking Details</b>' => '');


    $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 8, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'showLines' => 0, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));

    //NOW LETS ADD CUSTOM FIELDS HERE AS WELL
    $GetCustomFieldsInvoice = "SELECT * FROM customercustomfields WHERE DisplayQuote = 1 ORDER BY DisplayOrder";
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


    $pdf->ezSetDy(-20);


    if ($ProposalText != "") {
        $data = array();

        $data[] = array(' ' => $ProposalText);
        $pdf->ezTable($data, '', '', array('shaded' => 1, 'fontSize' => 8, 'showLines' => 0, 'showHeading' => 0, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'cols' => array(
            '<b>QTY</b>' => array('width' => 40)
        , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));


        $pdf->ezSetDy(-20);
    }


    if ($NumOnceOff > 0) {

        $pdf->ezText("Quote Items (Once-Off)", 10, array('aleft' => 20));

        $pdf->ezSetDy(-10);

        $InvoiceSub = 0;
        $InvoiceDiscount = 0;
        $InvoiceVat = 0;
        $InvoiceTotal = 0;


        $data = array();


        while ($Val = mysqli_fetch_array($GotLinesOnce)) {
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

                    if ($MultiAnswer != "") {
                        $MultiAnswer = rtrim($MultiAnswer, ", ");
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


            $data[] = array('<b>Product</b>' => '<b>' . $ThisLine . "</b>" . $ProductExtra, '<b>Price</b>' => 'R' . number_format($Price, 2), '<b>QTY</b>' => $Quantity, '<b>Sub Total</b>' => 'R' . number_format($LineSub, 2), '<b>VAT</b>' => 'R' . number_format($Vat, 2), '<b>Total</b>' => 'R' . number_format($LineTotal, 2));
        }


        $pdf->ezTable($data, '', '', array('shaded' => 1, 'fontSize' => 8, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'cols' => array(
            '<b>QTY</b>' => array('width' => 40)
        , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));

        $pdf->ezSetDy(-20);

        //TOTALS
        $data = array();

        $data[] = array('<b>Total</b>' => '<b>Sub Total</b>', '<b>Price</b>' => 'R' . number_format($InvoiceSub, 2));
        $data[] = array('<b>Total</b>' => '<b>VAT</b>', '<b>Price</b>' => 'R' . number_format($InvoiceVat, 2));
        $data[] = array('<b>Total</b>' => '<b>Total</b>', '<b>Price</b>' => '<b>R' . number_format($InvoiceTotal, 2) . '</b>');

        $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 8, 'xPos' => 615, 'xOrientation' => 'left', 'width' => 200, 'showLines' => 0, 'showHeadings' => 0, 'cols' => array(
            '<b>QTY</b>' => array('width' => 40)
        , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));


        $pdf->ezSetDy(-20);

    }

    if ($NumMonthly > 0) {

        $pdf->ezText("Quote Items (Monthly)", 10, array('aleft' => 20));

        $pdf->ezSetDy(-10);

        $InvoiceSub = 0;
        $InvoiceDiscount = 0;
        $InvoiceVat = 0;
        $InvoiceTotal = 0;


        $data = array();


        while ($Val = mysqli_fetch_array($GotLinesMonthly)) {
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

                    if ($MultiAnswer != "") {
                        $MultiAnswer = rtrim($MultiAnswer, ", ");
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


            $data[] = array('<b>Product</b>' => '<b>' . $ThisLine . "</b>" . $ProductExtra, '<b>Price</b>' => 'R' . number_format($Price, 2), '<b>QTY</b>' => $Quantity, '<b>Sub Total</b>' => 'R' . number_format($LineSub, 2), '<b>VAT</b>' => 'R' . number_format($Vat, 2), '<b>Total</b>' => 'R' . number_format($LineTotal, 2));
        }


        $pdf->ezTable($data, '', '', array('shaded' => 1, 'fontSize' => 8, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'cols' => array(
            '<b>QTY</b>' => array('width' => 40)
        , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));

        $pdf->ezSetDy(-20);

        //TOTALS
        $data = array();

        $data[] = array('<b>Total</b>' => '<b>Sub Total</b>', '<b>Price</b>' => 'R' . number_format($InvoiceSub, 2));
        $data[] = array('<b>Total</b>' => '<b>VAT</b>', '<b>Price</b>' => 'R' . number_format($InvoiceVat, 2));
        $data[] = array('<b>Total</b>' => '<b>Total</b>', '<b>Price</b>' => '<b>R' . number_format($InvoiceTotal, 2) . '</b>');

        $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 8, 'xPos' => 615, 'xOrientation' => 'left', 'width' => 200, 'showLines' => 0, 'showHeadings' => 0, 'cols' => array(
            '<b>QTY</b>' => array('width' => 40)
        , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));


        $pdf->ezSetDy(-20);

    }

    if ($NumSemiAnnually > 0) {

        $pdf->ezText("Quote Items (Semi-Annualy)", 10, array('aleft' => 20));

        $pdf->ezSetDy(-10);

        $InvoiceSub = 0;
        $InvoiceDiscount = 0;
        $InvoiceVat = 0;
        $InvoiceTotal = 0;


        $data = array();


        while ($Val = mysqli_fetch_array($GotLinesSemiAnnually)) {
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

                    if ($MultiAnswer != "") {
                        $MultiAnswer = rtrim($MultiAnswer, ", ");
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


            $data[] = array('<b>Product</b>' => '<b>' . $ThisLine . "</b>" . $ProductExtra, '<b>Price</b>' => 'R' . number_format($Price, 2), '<b>QTY</b>' => $Quantity, '<b>Sub Total</b>' => 'R' . number_format($LineSub, 2), '<b>VAT</b>' => 'R' . number_format($Vat, 2), '<b>Total</b>' => 'R' . number_format($LineTotal, 2));
        }


        $pdf->ezTable($data, '', '', array('shaded' => 1, 'fontSize' => 8, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'cols' => array(
            '<b>QTY</b>' => array('width' => 40)
        , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));

        $pdf->ezSetDy(-20);

        //TOTALS
        $data = array();

        $data[] = array('<b>Total</b>' => '<b>Sub Total</b>', '<b>Price</b>' => 'R' . number_format($InvoiceSub, 2));
        $data[] = array('<b>Total</b>' => '<b>VAT</b>', '<b>Price</b>' => 'R' . number_format($InvoiceVat, 2));
        $data[] = array('<b>Total</b>' => '<b>Total</b>', '<b>Price</b>' => '<b>R' . number_format($InvoiceTotal, 2) . '</b>');

        $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 8, 'xPos' => 615, 'xOrientation' => 'left', 'width' => 200, 'showLines' => 0, 'showHeadings' => 0, 'cols' => array(
            '<b>QTY</b>' => array('width' => 40)
        , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));


        $pdf->ezSetDy(-20);

    }

    if ($NumAnnually > 0) {

        $pdf->ezText("Quote Items (Annualy)", 10, array('aleft' => 20));

        $pdf->ezSetDy(-10);

        $InvoiceSub = 0;
        $InvoiceDiscount = 0;
        $InvoiceVat = 0;
        $InvoiceTotal = 0;


        $data = array();


        while ($Val = mysqli_fetch_array($GotLinesAnnually)) {
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

            if ($Meassure == "") {
                $ThisLine = $Description;
            } else {
                $ThisLine = $Description . " (" . $Meassure . ")";
            }


            $data[] = array('<b>Product</b>' => $ThisLine, '<b>Price</b>' => 'R' . number_format($Price, 2), '<b>QTY</b>' => $Quantity, '<b>Sub Total</b>' => 'R' . number_format($LineSub, 2), '<b>VAT</b>' => 'R' . number_format($Vat, 2), '<b>Total</b>' => 'R' . number_format($LineTotal, 2));
        }


        $pdf->ezTable($data, '', '', array('shaded' => 1, 'fontSize' => 8, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'cols' => array(
            '<b>QTY</b>' => array('width' => 40)
        , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));

        $pdf->ezSetDy(-20);

        //TOTALS
        $data = array();

        $data[] = array('<b>Total</b>' => '<b>Sub Total</b>', '<b>Price</b>' => 'R' . number_format($InvoiceSub, 2));
        $data[] = array('<b>Total</b>' => '<b>VAT</b>', '<b>Price</b>' => 'R' . number_format($InvoiceVat, 2));
        $data[] = array('<b>Total</b>' => '<b>Total</b>', '<b>Price</b>' => '<b>R' . number_format($InvoiceTotal, 2) . '</b>');

        $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 8, 'xPos' => 615, 'xOrientation' => 'left', 'width' => 200, 'showLines' => 0, 'showHeadings' => 0, 'cols' => array(
            '<b>QTY</b>' => array('width' => 40)
        , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));


        $pdf->ezSetDy(-20);

    }

    if ($TermsAndConditions != "") {
        $pdf->ezNewPage();

        $pdf->addText(350, 810, 10, "<b>Customer Quote</b>");
        $pdf->addText(350, 790, 10, "<b>Quote Number</b>");
        $pdf->addText(450, 790, 10, 'QR' . $QuoteID);
        $pdf->addText(350, 770, 10, "<b>Quote Date</b>");
        $pdf->addText(450, 770, 10, $QuoteDate);
        $pdf->addText(350, 750, 10, "<b>Expiry Date</b>");
        $pdf->addText(450, 750, 10, $ExpiryDate);
        $pdf->addText(350, 730, 10, "<b>Client Code</b>");
        $pdf->addText(450, 730, 10, $DepositReference);


        //BOTTOM
        $pdf->addText(470, 20, 8, "Quote Number " . $QuoteID);

        $pdf->ezText("<b>Terms and Conditions</b>", 10, array('aleft' => 20));

        $pdf->ezSetDy(-10);

        $data = array();

        $data[] = array(' ' => $TermsAndConditions);
        $pdf->ezTable($data, '', '',
            array('shaded' => 1,
                'fontSize' => 8,
                'showLines' => 0,
                'showHeading' => 0,
                'xPos' => 580,
                'xOrientation' => 'left',
                'width' => 550,
                'cols' => array(
                    '<b>QTY</b>' => array('width' => 40),
                    '<b>Product</b>' => array('width' => 250),
                    '<b>Rate</b>' => array('justification' => 'right'),
                    '<b>VAT Amt</b>' => array('justification' => 'right'),
                    '<b>Amount</b>' => array('justification' => 'right')
                )
            )
        );


    }


    if ($FooterText != "") {
        $data = array();

        $data[] = array(' ' => $FooterText);
        $pdf->ezTable($data, '', '', array('shaded' => 1, 'fontSize' => 8, 'showLines' => 0, 'showHeading' => 0, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'cols' => array(
            '<b>QTY</b>' => array('width' => 40)
        , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));


    }

    $pdfcode = $pdf->output();

    //EMAIL MESSAGE
    $SupplierMail = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					<title>Untitled Document</title>
					</head>
					<body>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family: Arial, Helvetica, sans-serif">
						  
						  <tr>
							<td  style="font-family: Arial, Helvetica, sans-serif; font-size: 14px" valign="top">
							Dear ' . $CompanyName . '<br>
							  <br>
							  Kindly find attached your quote QR' . $QuoteID . '. Please dont hesitate to ask if you have any questions and we will gladly assist.
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
    $data = chunk_split(base64_encode($pdfcode));
    $message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$files[$x]\"\n" .
        "Content-Disposition: attachment;\n" . " filename=\"QR$QuoteID.pdf\"\n" .
        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
    $message .= "--{$mime_boundary}\n";


    //$ok = @mail('alex@allweb.co.za', "Quote - QR" . $QuoteID, $message, $headers);
    mail($EmailAddress, "Quote - QR" . $QuoteID, $message, $headers);

    //UPDATE INVOICE STATUS TO UNPAID
    $UpdateQoute = "UPDATE customerquotes SET QuoteStatus = 1 WHERE QuoteID = {$QuoteID}";

    $DoUpdateQoute = mysqli_query($ClientCon, $UpdateQoute);

    return "OK";
}

function UpdateQuoteStatus($NewStatus, $QuoteID, $ExpiryDate)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $UpdateStatus = "UPDATE customerquotes SET QuoteStatus = {$NewStatus}, ExpiryDate = '{$ExpiryDate}' WHERE QuoteID = {$QuoteID}";
    $DoUpdateStatus = mysqli_query($ClientCon, $UpdateStatus);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the quote status";
    }
}

//DASHBOARD
function GetLatestAccess()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $LatestAccess = "SELECT * FROM customeraccess, customers WHERE customeraccess.CustomerID = customers.CustomerID  ORDER BY LogDate DESC LIMIT 10";
    $GotLatestAccess = mysqli_query($ClientCon, $LatestAccess);

    return $GotLatestAccess;
}

function GetAllAccessLogs()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $LatestAccess = "SELECT * FROM customeraccess, customers WHERE customeraccess.CustomerID = customers.CustomerID  ORDER BY LogDate DESC";
    $GotLatestAccess = mysqli_query($ClientCon, $LatestAccess);

    return $GotLatestAccess;
}

function GetIncompleteTask()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $IncompleteTask = "SELECT * FROM customertask, customers WHERE customertask.Status = 0 AND customertask.CustomerID = customers.CustomerID  ORDER BY TaskDate ASC";
    $GotIncompleteTask = mysqli_query($ClientCon, $IncompleteTask);

    return $GotIncompleteTask;
}

function GetIncompleteFollowUps()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $IncompleteFollowUps = "SELECT * FROM customerfollowups, customers WHERE customerfollowups.Status = 0 AND customerfollowups.CustomerID = customers.CustomerID  ORDER BY FollowUpDate ASC";
    $GotIncompleteFollowUps = mysqli_query($ClientCon, $IncompleteFollowUps);

    return $GotIncompleteFollowUps;
}

function GetAllFollowUps()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $FollowUps = "SELECT * FROM customers, customerfollowups  WHERE customerfollowups.CustomerID = customers.CustomerID  ORDER BY FollowUpDate DESC";
    $GotFollowUps = mysqli_query($ClientCon, $FollowUps);

    return $GotFollowUps;
}

function GetAllTask()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $Task = "SELECT * FROM customers, customertask WHERE customertask.CustomerID = customers.CustomerID  ORDER BY TaskDate DESC";
    $GotTask = mysqli_query($ClientCon, $Task);

    return $GotTask;
}

//SUPPLIER COSTINGS
function UpdateSupplierCosting($BillingType, $SellPrice, $PackSize, $Meassure, $StockAffect, $ProRata, $ProductID, $MinOrder, $SupplierID, $CostingID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    //WE NEED TO GET THE PRICE PER UNIT
    $UnitCost = $SellPrice / $StockAffect;
    $UnitCost = number_format($UnitCost, 2, '.', '');

    $UpdateCosting = "UPDATE suppliercost SET SupplierCost = {$SellPrice}, MeasurementID = {$Meassure}, BillingType = '{$BillingType}', ProRataBilling = {$ProRata}, PackSize = {$PackSize}, StockAffect = {$StockAffect}, MinimumOrder = {$MinOrder}, PricePerUnit = {$UnitCost} ";
    $UpdateCosting .= "WHERE SupplierCostID = {$CostingID}";
    $DoUpdateCosting = mysqli_query($ClientCon, $UpdateCosting);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        $SupplierCostID = $CostingID;
        $PriceDate = date("Y-m-d");

        //ADD COSTING DATE SO WE CAN TRACK
        $InsertCostingTrack = "INSERT INTO suppliercostingtracking (ProductID, SupplierCostID, SupplierCost, UnitCost, PriceDate) ";
        $InsertCostingTrack .= "VALUES ({$ProductID}, {$SupplierCostID}, {$SellPrice}, {$UnitCost}, '{$PriceDate}')";
        $DoInsertCostingTrack = mysqli_query($ClientCon, $InsertCostingTrack);
        //echo mysqli_error($ClientCon);
        return "OK";
    } else {
        return "There was an error updating the costing, please make sure your values are numeric";
    }
}


function AddSupplierCosting($BillingType, $SellPrice, $PackSize, $Meassure, $StockAffect, $ProRata, $ProductID, $MinOrder, $SupplierID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    //WE NEED TO GET THE PRICE PER UNIT
    $UnitCost = $SellPrice / $StockAffect;
    $UnitCost = number_format($UnitCost, 2, '.', '');

    $UpdateCosting = "INSERT INTO suppliercost (SupplierCost, MeasurementID, BillingType, ProRataBilling, PackSize, StockAffect, ProductID, MinimumOrder, PricePerUnit, SupplierID) ";
    $UpdateCosting .= "VALUES ({$SellPrice}, {$Meassure},  '{$BillingType}',  {$ProRata}, {$PackSize}, {$StockAffect}, {$ProductID}, {$MinOrder}, {$UnitCost}, {$SupplierID})";
    $DoUpdateCosting = mysqli_query($ClientCon, $UpdateCosting);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        $SupplierCostID = mysqli_insert_id($ClientCon);
        $PriceDate = date("Y-m-d");

        //ADD COSTING DATE SO WE CAN TRACK
        $InsertCostingTrack = "INSERT INTO suppliercostingtracking (ProductID, SupplierCostID, SupplierCost, UnitCost, PriceDate) ";
        $InsertCostingTrack .= "VALUES ({$ProductID}, {$SupplierCostID}, {$SellPrice}, {$UnitCost}, '{$PriceDate}')";
        $DoInsertCostingTrack = mysqli_query($ClientCon, $InsertCostingTrack);
        //echo mysqli_error($ClientCon);
        return "OK";
    } else {
        return "There was an error updating the costing, please make sure your values are numeric";
    }
}

function GetSupplierCosting($SupplierCostID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCosting = "SELECT * FROM suppliercostingtracking WHERE SupplierCostID = {$SupplierCostID} ORDER BY SupplierCostingID DESC LIMIT 1";
    $GotCosting = mysqli_query($ClientCon, $GetCosting);


    return $GotCosting;
}

function GetPercentMovement($SupplierCostID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    //INITIAL COST
    $GetCost = "SELECT * FROM suppliercostingtracking WHERE SupplierCostID = {$SupplierCostID} ORDER BY SupplierCostingID ASC LIMIT 1";
    $GotCost = mysqli_query($ClientCon, $GetCost);

    while ($Val = mysqli_fetch_array($GotCost)) {
        $InititalCost = $Val["UnitCost"];
    }

    //MOST RECENT PRICING
    $GetCost = "SELECT * FROM suppliercostingtracking WHERE SupplierCostID = {$SupplierCostID} ORDER BY SupplierCostingID DESC LIMIT 1";
    $GotCost = mysqli_query($ClientCon, $GetCost);

    while ($Val = mysqli_fetch_array($GotCost)) {
        $SupplierCost = $Val["UnitCost"];
    }


    if ($InititalCost == $SupplierCost) {
        //STAYED THE SAME
        return "0%";
    } else if ($InititalCost > $SupplierCost) {
        //WENT DOWN
        //GET PERCENTAGE IT WENT DOWN WITH
        $Movement = (($InititalCost - $SupplierCost) / $InititalCost) * 100;
        return '<div style="color: green"><i class="fa fa-arrow-down" style="color: green"></i> ' . number_format($Movement, 2) . '%</div>';
    } else {
        //WENT UP
        $Movement = (($SupplierCost - $InititalCost) / $InititalCost) * 100;
        return '<div style="color: red"><i class="fa fa-arrow-up" style="color: red"></i> ' . number_format($Movement, 2) . '%</div>';
    }
}

function GetSupplierCost($ProductID, $SupplierID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCost = "SELECT * FROM suppliercost WHERE ProductID = {$ProductID} AND SupplierID = {$SupplierID} ORDER BY SupplierCost ASC";
    $GotCost = mysqli_query($ClientCon, $GetCost);


    return $GotCost;

}

function GetSupplierCostings($CostingID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCost = "SELECT * FROM suppliercost WHERE SupplierCostID = {$CostingID}";
    $GotCost = mysqli_query($ClientCon, $GetCost);


    return $GotCost;
}

function GetSupplierPO($SupplierID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetPO = "SELECT * FROM 	purchaseorders WHERE SupplierID = {$SupplierID}";
    $GotPO = mysqli_query($ClientCon, $GetPO);

    return $GotPO;
}

function GetSupplierInvoices($SupplierID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetInvoices = "SELECT * FROM supplierorders WHERE SupplierID = {$SupplierID}";
    $GotInvoices = mysqli_query($ClientCon, $GetInvoices);
    echo mysqli_error($ClientCon);

    return $GotInvoices;
}

function GetPurchaseOrderTotal($PurchaseID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetTotal = "SELECT SUM(LineTotal) AS POTotal FROM purchaseorderlines WHERE PurchaseID = {$PurchaseID}";
    $GotTotal = mysqli_query($ClientCon, $GetTotal);

    while ($Val = mysqli_fetch_array($GotTotal)) {
        $POTotal = $Val["POTotal"];
    }

    return $POTotal;
}

function GetSupplierOrderTotal($SupplierInvoiceID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetTotal = "SELECT SUM(LineTotal) AS InvoiceTotal FROM supplierorderlines WHERE SupplierInvoiceID = {$SupplierInvoiceID}";
    $GotTotal = mysqli_query($ClientCon, $GetTotal);
    echo mysqli_error($ClientCon);

    while ($Val = mysqli_fetch_array($GotTotal)) {
        $InvoiceTotal = $Val["InvoiceTotal"];
    }

    return $InvoiceTotal;
}

function GetPurchaseOrder($PurchaseID, $SupplierID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetPO = "SELECT * FROM purchaseorders WHERE PurchaseID = {$PurchaseID} AND SupplierID = {$SupplierID}";
    $GotPO = mysqli_query($ClientCon, $GetPO);

    return $GotPO;
}

function GetSupplierInvoiceLines($InvoiceID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetLines = "SELECT * FROM supplierorderlines WHERE SupplierInvoiceID = {$InvoiceID}";
    $GotLines = mysqli_query($ClientCon, $GetLines);


    return $GotLines;
}

function CompleteSupplierInvoice($InvoiceID, $WarehouseID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $InvoiceLines = GetSupplierInvoiceLines($InvoiceID);

    $DateAdded = date("Y-m-d H:i:s");

    //HERE WE ADD STOCK NOW
    while ($Val = mysqli_fetch_array($InvoiceLines)) {
        $ProductID = $Val["ProductID"];
        $StockAffect = $Val["StockAffect"];
        $LineSubTotal = $Val["LineSubTotal"];

        $UnitCost = $LineSubTotal / $StockAffect;
        $UnitCost = number_format($UnitCost, 2, '.', '');

        //THEN ADD THE STOCK
        $AddStock = "INSERT INTO productstock (ProductID, Stock, DateAdded, StockType, UnitCost, SupplierInvoiceID, WarehouseID) ";
        $AddStock .= "VALUES ({$ProductID}, {$StockAffect}, '{$DateAdded}', 'Purchased', {$UnitCost}, {$InvoiceID}, {$WarehouseID})";

        $DoAddStock = mysqli_query($ClientCon, $AddStock);

    }

    //THEN UPDATE INVOICE TO COMPLETED
    $UpdateSupplierInvoice = "UPDATE supplierorders SET InvoiceStatus = 1 WHERE SupplierInvoiceID = {$InvoiceID}";
    $DoUpdateInvoice = mysqli_query($ClientCon, $UpdateSupplierInvoice);

    return "OK";

}

function GetCurrentStock($ProductID)
{

}

function GetPurchaseOrderLines($PurchaseID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetLines = "SELECT * FROM purchaseorderlines WHERE PurchaseID = {$PurchaseID}";
    $GotLines = mysqli_query($ClientCon, $GetLines);


    return $GotLines;
}

function GetAllActiveProductGroupsSupplier($SupplierID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetProductGroups = "SELECT * FROM productgroups WHERE ProductGroupID IN (SELECT ProductGroupID FROM products WHERE ProductID IN (SELECT ProductID FROM supplierproducts WHERE SupplierID = {$SupplierID})) ORDER BY GroupName";
    $GotProductGroups = mysqli_query($ClientCon, $GetProductGroups);

    return $GotProductGroups;
}

function GetGroupProductsArraySupplier($ProductGroupID, $SupplierID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetProducts = "SELECT * FROM products WHERE ProductGroupID = {$ProductGroupID} AND ProductID IN (SELECT ProductID FROM supplierproducts WHERE SupplierID = {$SupplierID}) ORDER BY ProductCode, ProductName";
    $GotProducts = mysqli_query($ClientCon, $GetProducts);

    $X = 0;

    while ($Val = mysqli_fetch_array($GotProducts)) {
        $ProductID = $Val["ProductID"];
        $ProductName = $Val["ProductName"];
        $ProductCode = $Val["ProductCode"];


        $Item[$X][0] = $ProductID;
        $Item[$X][1] = $ProductName;
        $Item[$X][2] = $ProductCode;

        $X++;
    }

    return $Item;
}

function GetProductPricingArraySupplier($ProductID, $SupplierID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetPricing = "SELECT * FROM suppliercost WHERE ProductID = {$ProductID} ORDER BY SupplierCost ASC";
    $GotPricing = mysqli_query($ClientCon, $GetPricing);
    $X = 0;

    while ($Val = mysqli_fetch_array($GotPricing)) {
        $ProductCostID = $Val["SupplierCostID"];
        $BillingType = $Val["BillingType"];
        $SupplierCost = $Val["SupplierCost"];
        $PackSize = $Val["PackSize"];
        $MeassurementID = $Val["MeasurementID"];
        $MinimumOrder = $Val["MinimumOrder"];

        $MeasurementDescription = "";

        if ($MeassurementID > 0) {
            $GetMeassurement = "SELECT * FROM productmeasurement WHERE MeasurementID = {$MeassurementID}";
            $GotMeassurement = mysqli_query($ClientCon, $GetMeassurement);
            echo mysqli_error($ClientCon);

            while ($Measure = mysqli_fetch_array($GotMeassurement)) {
                $MeasurementDescription = $Measure["MeasurementDescription"];
            }

        }

        if ($MeasurementDescription != "") {
            $Description = $PackSize . " " . $MeasurementDescription . " @ R" . $SupplierCost . " " . $BillingType;
        } else {
            $Description = $PackSize . " @ R" . $SupplierCost . " " . $BillingType;
        }


        $Pricing[$X][0] = $ProductCostID;
        $Pricing[$X][1] = $Description;
        $Pricing[$X][2] = $MinimumOrder;


        $X++;
    }

    return $Pricing;
}

function GetSupplierInvoiceDetails($InvoiceID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetPO = "SELECT * FROM 	supplierorders WHERE SupplierInvoiceID = {$InvoiceID}";
    $GotPO = mysqli_query($ClientCon, $GetPO);

    return $GotPO;
}

function GetPurchaseOrderDetails($PurchaseID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetPO = "SELECT * FROM 	purchaseorders WHERE PurchaseID = {$PurchaseID}";
    $GotPO = mysqli_query($ClientCon, $GetPO);

    return $GotPO;
}

function AddSupplierInvoice($PONumber, $InvoiceID, $SupplierID, $PurchaseOrderID, $SupplierInvoiceNumber, $WarehouseID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    if ($InvoiceID == "") {
        //FIRST CREATE THE MAIN PO
        $ThisClientID = $_SESSION["ClientID"];
        $ThisUser = $_SESSION["ClientName"];
        $DateAdded = date("Y-m-d");

        $EmployeeID = $Val["EmployeeID"];
        if ($EmployeeID == "") {
            $EmployeeID = 0;
        }

        if ($PurchaseOrderID == "") {
            $PurchaseOrderID = 0;
        }

        $AddInvoice = "INSERT INTO supplierorders (SupplierID, PurchaseOrderID, InvoiceDate, InvoiceStatus, AddedByClient, AddedByEmployee, AddedByName PurchaseNumber, InvoiceNumber, WarehouseID) ";
        $AddInvoice .= "VALUES ({$SupplierID}, '{$PurchaseOrderID}', '{$DateAdded}', 0, {$ThisClientID}, {$EmployeeID}, '{$ThisUser}', '{$PONumber}', '{$SupplierInvoiceNumber}', {$WarehouseID})";

        $DoAddInvoice = mysqli_query($ClientCon, $AddInvoice);

        $Error = mysqli_error($ClientCon);
        if ($Error == "") {
            $InvoiceID = mysqli_insert_id($ClientCon);

            return $InvoiceID;
        } else {
            return "There was an error creating the invoice header" . $Error;
        }

    } else {
        //ITS AN UPDATE
        $UpdatePO = "UPDATE supplierorders SET PurchaseNumber = '{$PONumber}', InvoiceNumber = '{$SupplierInvoiceNumber}', WarehouseID = {$WarehouseID}  WHERE SupplierInvoiceID = {$InvoiceID}";
        $DoUpdatePO = mysqli_query($ClientCon, $UpdatePO);
    }

    return $InvoiceID;
}

function AddSupplierInvoiceFile($InvoiceID, $ThisFileType, $NewFileName)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    //ITS AN UPDATE
    $UpdatePO = "UPDATE supplierorders SET InvoiceFile = '{$NewFileName}'  WHERE SupplierInvoiceID = {$InvoiceID}";
    $DoUpdatePO = mysqli_query($ClientCon, $UpdatePO);

    return "OK";
}

function AddPurchaseOrder($PONumber, $IsSelected, $Delivery, $SpecialInstructions, $PurchaseID, $SupplierID, $WarehouseID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    if ($PurchaseID == "") {
        //FIRST CREATE THE MAIN PO
        $ThisClientID = $_SESSION["ClientID"];
        $ThisUser = $_SESSION["ClientName"];
        $DateAdded = date("Y-m-d");

        $EmployeeID = $Val["EmployeeID"];
        if ($EmployeeID == "") {
            $EmployeeID = 0;
        }

        $AddPO = "INSERT INTO purchaseorders (SupplierID, PurchaseOrderDate, PurchaseStatus, AddedByClient, AddedByEmployee, AddedByName, DeliveryType, SpecialInstructions, PurchaseNumber, WarehouseID) ";
        $AddPO .= "VALUES ({$SupplierID}, '{$DateAdded}', 0, {$ThisClientID}, {$EmployeeID}, '{$ThisUser}', '{$Delivery}', '{$SpecialInstructions}', '{$PONumber}', {$WarehouseID})";

        $DoAddPO = mysqli_query($ClientCon, $AddPO);

        $Error = mysqli_error($ClientCon);
        if ($Error == "") {
            $PurchaseID = mysqli_insert_id($ClientCon);
            $PurchaseNumber = "PO" . $PurchaseID;

            if ($IsSelected == "true") {
                $UpdatePO = "UPDATE purchaseorders SET PurchaseNumber = '{$PurchaseNumber}' WHERE PurchaseID = {$PurchaseID}";
                $DoUpdatePO = mysqli_query($ClientCon, $UpdatePO);
                echo mysqli_error($ClientCon);
            }

            return $PurchaseID;
        } else {
            return "There was an error creating the purchase order header" . $Error;
        }

    } else {
        //ITS AN UPDATE
        $UpdatePO = "UPDATE purchaseorders SET PurchaseNumber = '{$PONumber}', DeliveryType = '{$Delivery}', SpecialInstructions = '{$SpecialInstructions}', WarehouseID = {$WarehouseID} WHERE PurchaseID = {$PurchaseID}";
        $DoUpdatePO = mysqli_query($ClientCon, $UpdatePO);
    }

    return $PurchaseID;
}

function AddSupplierInvoiceLine($InvoiceID, $Product, $Price, $Quantity, $SupplierID, $ChargesVAT, $ItemPrice, $NewItemPrice)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    ////THEN THE ACTUAL LINE ITEM
    //PRODUCT DETAILS
    $GetProduct = "SELECT * FROM products WHERE ProductID = {$Product}";
    $GotProduct = mysqli_query($ClientCon, $GetProduct);

    while ($Val = mysqli_fetch_array($GotProduct)) {
        $ProductName = $Val["ProductName"];
        $ProductCode = $Val["ProductCode"];
        $ProductDescription = $Val["ProductDescription"];
    }

    //PRICING DETAILS
    $GetPricing = "SELECT * FROM suppliercost WHERE SupplierCostID = {$Price}";
    $GotPricing = mysqli_query($ClientCon, $GetPricing);

    while ($Val = mysqli_fetch_array($GotPricing)) {
        $ProductCostID = $Val["SupplierCostID"];
        $BillingType = $Val["BillingType"];
        $ClientCost = $NewItemPrice;
        $PackSize = $Val["PackSize"];
        $MeassurementID = $Val["MeasurementID"];
        $StockAffect = $Val["StockAffect"];

        $MeasurementDescription = "";

        if ($MeassurementID > 0) {
            $GetMeassurement = "SELECT * FROM productmeasurement WHERE MeasurementID = {$MeassurementID}";
            $GotMeassurement = mysqli_query($ClientCon, $GetMeassurement);
            echo mysqli_error($ClientCon);

            while ($Measure = mysqli_fetch_array($GotMeassurement)) {
                $MeasurementDescription = $Measure["MeasurementDescription"];
            }

        }
    }

    //OK ENOUGH INFO
    if ($MeasurementDescription != "") {
        $MeasurreDescript = $PackSize . " " . $MeasurementDescription;
    } else {
        $MeasurreDescript = $PackSize;
    }

    $ThisProduct = $ProductName . " - " . $ProductDescription;

    $CostBeforeVat = $ClientCost;


    $SubTotal = $ClientCost * $Quantity;

    if ($DiscountPercent > 0) {
        $DiscountPercent = ($DiscountPercent / 100);
        $DiscountAmount = $SubTotal * $DiscountPercent;

    } else {
        $DiscountAmount = 0;
    }

    $DiscountAmount = number_format($DiscountAmount, 2, '.', '');

    $VatableAmount = $SubTotal - $DiscountAmount;

    if ($ChargesVAT == 1) {
        //MUST DO VAT
        $VatInc = $VatableAmount * 1.14;
        $Vat = $VatInc - $VatableAmount;
    } else {
        $Vat = 0;
    }

    $Vat = number_format($Vat, 2, '.', '');

    $LineTotal = $VatableAmount + $Vat;

    $OrigStockAffect = $StockAffect;

    $StockAffect = $StockAffect * $Quantity;


    $InsertLine = "INSERT INTO supplierorderlines (SupplierInvoiceID, Description, Quantity, Price, ProductID, ProductCode, LineSubTotal, LineDiscount, LineVAT, LineTotal, BillingType, StockAffect, MeassurementDescription) ";
    $InsertLine .= "VALUES ({$InvoiceID}, '{$ThisProduct}', {$Quantity}, {$ClientCost}, {$Product}, '{$ProductCode}', {$SubTotal}, {$DiscountAmount}, {$Vat}, {$LineTotal}, '{$BillingType}', {$StockAffect}, '{$MeasurreDescript}')";
    $DoInsertLine = mysqli_query($ClientCon, $InsertLine);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        //THEN CHECK IF WE HAVE A PRICE UPDATE TO MAKE
        if ($ItemPrice != $NewItemPrice) {
            //WE NEED TO GET THE PRICE PER UNIT
            $UnitCost = $NewItemPrice / $OrigStockAffect;
            $UnitCost = number_format($UnitCost, 2, '.', '');

            $UpdateCosting = "UPDATE suppliercost SET SupplierCost = {$NewItemPrice}, PricePerUnit = {$UnitCost} ";
            $UpdateCosting .= "WHERE SupplierCostID = {$Price}";
            $DoUpdateCosting = mysqli_query($ClientCon, $UpdateCosting);

            $Error = mysqli_error($ClientCon);

            if ($Error == "") {
                $SupplierCostID = $Price;
                $PriceDate = date("Y-m-d");

                //ADD COSTING DATE SO WE CAN TRACK
                $InsertCostingTrack = "INSERT INTO suppliercostingtracking (ProductID, SupplierCostID, SupplierCost, UnitCost, PriceDate) ";
                $InsertCostingTrack .= "VALUES ({$Product}, {$SupplierCostID}, {$NewItemPrice}, {$UnitCost}, '{$PriceDate}')";
                $DoInsertCostingTrack = mysqli_query($ClientCon, $InsertCostingTrack);
                //echo mysqli_error($ClientCon);
                return "OK";
            } else {
                return "There was an error updating the costing, please make sure your values are numeric";
            }
        } else {
            return "OK";
        }
    } else {
        return "There was an error adding the purchase order line" . $Error;
    }
}

function AddPOLine($PurchaseID, $Product, $Price, $Quantity, $SupplierID, $ChargesVAT)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    if ($PurchaseID == "") {
        //FIRST CREATE THE MAIN PO
        $ThisClientID = $_SESSION["ClientID"];
        $ThisUser = $_SESSION["ClientName"];
        $DateAdded = date("Y-m-d");

        $EmployeeID = $Val["EmployeeID"];
        if ($EmployeeID == "") {
            $EmployeeID = 0;
        }

        $AddPO = "INSERT INTO purchaseorders (SupplierID, PurchaseOrderDate, PurchaseStatus, AddedByClient, AddedByEmployee, AddedByName) ";
        $AddPO .= "VALUES ({$SupplierID}, '{$DateAdded}', 0, {$ThisClientID}, {$EmployeeID}, '{$ThisUser}')";

        $DoAddPO = mysqli_query($ClientCon, $AddPO);

        $Error = mysqli_error();
        if ($Error == "") {
            $PurchaseID = mysqli_insert_id($ClientCon);
            $PurchaseNumber = "PO" . $PurchaseID;

            $UpdatePO = "UPDATE purchaseorders SET PurchaseNumber = '{$PurchaseNumber}' WHERE PurchaseID = {$PurchaseID}";
            $DoUpdatePO = mysqli_query($ClientCon, $UpdatePO);
        } else {
            return "There was an error creating the purchase order header";
        }

    }

    ////THEN THE ACTUAL LINE ITEM
    //PRODUCT DETAILS
    $GetProduct = "SELECT * FROM products WHERE ProductID = {$Product}";
    $GotProduct = mysqli_query($ClientCon, $GetProduct);

    while ($Val = mysqli_fetch_array($GotProduct)) {
        $ProductName = $Val["ProductName"];
        $ProductCode = $Val["ProductCode"];
        $ProductDescription = $Val["ProductDescription"];
    }

    //PRICING DETAILS
    $GetPricing = "SELECT * FROM suppliercost WHERE SupplierCostID = {$Price}";
    $GotPricing = mysqli_query($ClientCon, $GetPricing);

    while ($Val = mysqli_fetch_array($GotPricing)) {
        $ProductCostID = $Val["SupplierCostID"];
        $BillingType = $Val["BillingType"];
        $ClientCost = $Val["SupplierCost"];
        $PackSize = $Val["PackSize"];
        $MeassurementID = $Val["MeasurementID"];
        $StockAffect = $Val["StockAffect"];

        $MeasurementDescription = "";

        if ($MeassurementID > 0) {
            $GetMeassurement = "SELECT * FROM productmeasurement WHERE MeasurementID = {$MeassurementID}";
            $GotMeassurement = mysqli_query($ClientCon, $GetMeassurement);
            echo mysqli_error($ClientCon);

            while ($Measure = mysqli_fetch_array($GotMeassurement)) {
                $MeasurementDescription = $Measure["MeasurementDescription"];
            }

        }
    }

    //OK ENOUGH INFO
    if ($MeasurementDescription != "") {
        $MeasurreDescript = $PackSize . " " . $MeasurementDescription;
    } else {
        $MeasurreDescript = $PackSize;
    }

    $ThisProduct = $ProductName . " - " . $ProductDescription;

    $CostBeforeVat = $ClientCost;


    $SubTotal = $ClientCost * $Quantity;

    if ($DiscountPercent > 0) {
        $DiscountPercent = ($DiscountPercent / 100);
        $DiscountAmount = $SubTotal * $DiscountPercent;

    } else {
        $DiscountAmount = 0;
    }

    $DiscountAmount = number_format($DiscountAmount, 2, '.', '');

    $VatableAmount = $SubTotal - $DiscountAmount;

    if ($ChargesVAT == 1) {
        //MUST DO VAT
        $VatInc = $VatableAmount * 1.14;
        $Vat = $VatInc - $VatableAmount;
    } else {
        $Vat = 0;
    }

    $Vat = number_format($Vat, 2, '.', '');

    $LineTotal = $VatableAmount + $Vat;

    $StockAffect = $StockAffect * $Quantity;


    $InsertLine = "INSERT INTO purchaseorderlines (PurchaseID, Description, Quantity, Price, ProductID, ProductCode, LineSubTotal, LineDiscount, LineVAT, LineTotal, BillingType, StockAffect, MeassurementDescription, SupplierCostID) ";
    $InsertLine .= "VALUES ({$PurchaseID}, '{$ThisProduct}', {$Quantity}, {$ClientCost}, {$Product}, '{$ProductCode}', {$SubTotal}, {$DiscountAmount}, {$Vat}, {$LineTotal}, '{$BillingType}', {$StockAffect}, '{$MeasurreDescript}', {$Price})";
    $DoInsertLine = mysqli_query($ClientCon, $InsertLine);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding the purchase order line" . $Error;
    }


}

function GetMinOrderSupplier($PricingID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetMin = "SELECT MinimumOrder FROM suppliercost WHERE SupplierCostID = {$PricingID}";
    $GotMin = mysqli_query($ClientCon, $GetMin);

    while ($Val = mysqli_fetch_array($GotMin)) {
        $Minimum = $Val["MinimumOrder"];
    }

    return $Minimum;
}

function SendCustomerInvoice($CustomerID, $InvoiceID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];


    $GetCustomer = "SELECT * FROM customers, countries WHERE CustomerID = {$CustomerID} AND customers.CountryID = countries.CountryID";
    $GotCustomer = mysqli_query($ClientCon, $GetCustomer);

    while ($Val = mysqli_fetch_array($GotCustomer)) {
        $Name = $Val["FirstName"];
        $Surname = $Val["Surname"];
        $CompanyName = $Val["CompanyName"];

        if ($CompanyName != "") {

        } else {
            $CompanyName = $Name . " " . $Surname;
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

    while ($Val = mysqli_fetch_array($GotInvoice)) {
        $InvoiceNumber = $Val["InvoiceNumber"];
        $InvoiceDate = $Val["InvoiceDate"];
        $DueDate = $Val["DueDate"];
        $AddedByName = $Val["AddedByName"];
        $InvoiceNotes = $Val["InvoiceNotes"];
        $additionalnotes = $Val["additionalnotes"];

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


            $InvoiceNotes = $InvoiceAdditional . $InvoiceNotes;
        }

    }

    $AddLog = AddLogInformation('Sent customer invoice number ' . $InvoiceNumber, 'Invoice', $CustomerID);

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

    /*$pdf->addText(350, 810, 10, "<b>TAX Invoice</b>");
    $pdf->addText(350, 790, 10, "<b>Invoice Number</b>");
    $pdf->addText(450, 790, 10, $InvoiceNumber);
    $pdf->addText(350, 770, 10, "<b>Invoice Date</b>");
    $pdf->addText(450, 770, 10, $InvoiceDate);
    $pdf->addText(350, 750, 10, "<b>Due Date</b>");
    $pdf->addText(450, 750, 10, $DueDate);
    $pdf->addText(350, 730, 10, "<b>Client Code</b>");
    $pdf->addText(450, 730, 10, $DepositReference);*/


    $pdf->addText(230,810,10,$Address1);
    $pdf->addText(230,795,10,$Address2);
    $pdf->addText(230,780,10,$City);
    $pdf->addText(230,765,10,$Region);
    $pdf->addText(230,750,10,$CountryName." - ".$PostCode);

    $pdf->addText(360,810,10,"<b>Tel: </b>"); $pdf->addText(430,810,10,$DisplayTel);
    $pdf->addText(360,795,10,"<b>Email: 	</b>"); $pdf->addText(430,795,10,$DisplayEmail);
    $pdf->addText(360,780,10,"<b>VAT Number: </b>"); $pdf->addText(430,780,10,$DisplayVat);
    $pdf->addText(360,765,10,"<b>Company Reg: </b>"); $pdf->addText(430,765,10,$CompanyReg);


    //BOTTOM
    $pdf->addText(90,25,8,"<b>Banking Details</b> Account Holder: " . $AccountHolder);
    $pdf->addText(90,16,8,"Account Number: "
        .$AccountNumber.", Branch Code:".$BranchCode.", Account Type: ".$AccountType);
    $pdf->addText(90,7,8,"Deposit Reference: ".$DepositReference);
    $pdf->addText(490,25,8,"Invoice Number " . $InvoiceNumber);

    $data = array();

    $pdf->ezText("Invoice Details", 10, array('aleft' => 20));
    $pdf->line(20,690,580,690);
    $pdf->line(20,600,580,600);
    $pdf->ezSetDy(-10);

    $data[] = array('<b>CUSTOMER DETAILS</b>'=>'<b>' . strtoupper($CompanyName) . '</b>','<b>ADDRESS</b>'=> $CustomerAddress1, '<b>TAX INVOICE</b>'=>'<b>Invoice Number : ' .$InvoiceNumber."</b>");
    $data[] = array('<b>CUSTOMER DETAILS</b>'=>'Tel: ' . $ContactNumber,'<b>ADDRESS</b>'=>$CustomerAddress2, '<b>TAX INVOICE</b>'=>'Invoice Date : ' .$InvoiceDate);
    $data[] = array('<b>CUSTOMER DETAILS</b>'=>'Email : ' . $EmailAddress,'<b>ADDRESS</b>'=>$CustomerCity.", ".$CustomerRegion, '<b>TAX INVOICE</b>'=>'Due Date : ' .$DueDate);
    $data[] = array('<b>CUSTOMER DETAILS</b>'=>'VAT Number : ' . $VatNumber,'<b>ADDRESS</b>'=> $CustomerCountryName." - ".$CustomerPostCode, '<b>TAX INVOICE</b>'=>'Client Code : ' .$DepositReference);
    $data[] = array('<b>CUSTOMER DETAILS</b>'=>'','<b>ADDRESS</b>'=> '', '<b>TAX INVOICE</b>'=>'');


if($TaxExempt == 0) {
    $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 10, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'showLines' => 0, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));
}else{
    $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 10, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'showLines' => 0, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));
}

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

        if($TaxExempt == 0) {
            $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 10, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'showLines' => 0, 'showHeadings' => 0, 'cols' => array(
                '<b>Custom 123</b>' => array('width' => 250)
            , '<b>Custom 1222</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'),
                '<b>VAT Amt</b>' => array('justification' => 'right'),
                '<b>Amount</b>' => array('justification' => 'right'))));
        }else{
            $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 10, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'showLines' => 0, 'showHeadings' => 0, 'cols' => array(
                '<b>Custom 123</b>' => array('width' => 250)
            , '<b>Custom 1222</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'),

                '<b>Amount</b>' => array('justification' => 'right'))));
        }

    }


    $data = array();

    //INVOICE NOTES
    if ($InvoiceNotes != "") {
        $pdf->ezSetDy(-10);
        $pdf->ezText("Invoice Notes", 10, array('aleft' => 20));
        $pdf->ezSetDy(-10);
        $data[] = array('<b>Invoice Notes</b>' => $InvoiceNotes);

        if($TaxExempt == 0) {
            $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 9, 'showHeadings' => 0, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'showLines' => 0, 'cols' => array(
                '<b>QTY</b>' => array('width' => 40)
            , '<b>Product</b>' => array('width' => 250),
                '<b>Rate</b>' => array('justification' => 'right'),
                '<b>VAT Amt</b>' => array('justification' => 'right'),
                '<b>Amount</b>' => array('justification' => 'right'))));
        }else{
            $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 9, 'showHeadings' => 0, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'showLines' => 0, 'cols' => array(
                '<b>QTY</b>' => array('width' => 40)
            , '<b>Product</b>' => array('width' => 250),
                '<b>Rate</b>' => array('justification' => 'right'),

                '<b>Amount</b>' => array('justification' => 'right'))));
        }
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

        $GetSubCost = "SELECT SUM(LineSubTotal) AS SubTotal FROm customerinvoicelines WHERE InvoiceID = {$InvoiceID} AND GroupID = {$InvoiceGroupID}";
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
        if($TaxExempt == 0) {
            $data[] = array('<b>Product</b>' => $GroupName, '<b>Price</b>' => 'R' . number_format($LineTotal, 2), '<b>QTY</b>' => 1, '<b>Sub Total</b>' => 'R' . number_format($SubTotal, 2),
                '<b>VAT</b>' => 'R' . number_format($VATTotal, 2),
                '<b>Total</b>' => 'R' . number_format($LineTotal, 2));
        }else{
            $data[] = array('<b>Product</b>' => $GroupName, '<b>Price</b>' => 'R' . number_format($LineTotal, 2), '<b>QTY</b>' => 1, '<b>Sub Total</b>' => 'R' . number_format($SubTotal, 2),

                '<b>Total</b>' => 'R' . number_format($LineTotal, 2));
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

        if($TaxExempt=='1'){
            $data[] = array('<b>Product</b>' => '<b>' . $ThisLine . "</b>" . $ProductExtra, '<b>Price</b>' => 'R' . number_format($Price, 2), '<b>QTY</b>' => $Quantity, '<b>Sub Total</b>' => 'R' . number_format($LineSub, 2),
                '<b>Discount</b>' => 'R' . number_format($LineDiscount, 2),
                '<b>Total</b>' => 'R' . number_format($LineTotal, 2));
        }else {

            $data[] = array('<b>Product</b>' => '<b>' . $ThisLine . "</b>" . $ProductExtra, '<b>Price</b>' => 'R' . number_format($Price, 2), '<b>QTY</b>' => $Quantity, '<b>Sub Total</b>' => 'R' . number_format($LineSub, 2),
                '<b>Discount</b>' => 'R' . number_format($LineDiscount, 2),
                '<b>VAT</b>' => 'R' . number_format($Vat, 2), '<b>Total</b>' => 'R' . number_format($LineTotal, 2));
        }
    }

if($TaxExempt == 0) {
    $pdf->ezTable($data, '', '', array('shaded' => 1, 'fontSize' => 8, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'),
        '<b>VAT Amt</b>' => array('justification' => 'right'),
        '<b>Amount</b>' => array('justification' => 'right'))));
}else{
    $pdf->ezTable($data, '', '', array('shaded' => 1, 'fontSize' => 8, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'),

        '<b>Amount</b>' => array('justification' => 'right'))));
}
    $pdf->ezSetDy(-20);

    //TOTALS
    $data = array();

    $data[] = array('<b>Total</b>' => '<b>Sub Total</b>', '<b>Price</b>' => 'R' . number_format($InvoiceSub, 2));
    $data[] = array('<b>Total</b>'=>'<b>Discount</b>','<b>Price</b>'=>'R' . number_format($LineDiscountTotal,2));
    if($TaxExempt == 0) {
        $data[] = array('<b>Total</b>' => '<b>VAT</b>', '<b>Price</b>' => 'R' . number_format($InvoiceVat, 2));
    }
    $data[] = array('<b>Total</b>' => '<b>Total</b>', '<b>Price</b>' => '<b>R' . number_format($InvoiceTotal, 2) . '</b>');

if($TaxExempt == 0) {
    $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 8, 'xPos' => 615, 'xOrientation' => 'left', 'width' => 200, 'showHeadings' => 0, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'),
        '<b>VAT Amt</b>' => array('justification' => 'right'),
        '<b>Amount</b>' => array('justification' => 'right'))));
}else{
    $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 8, 'xPos' => 615, 'xOrientation' => 'left', 'width' => 200,  'showHeadings' => 0, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'),

        '<b>Amount</b>' => array('justification' => 'right'))));
}

    if(!empty($additionalnotes)) {
        $pdf->ezSetDy(-20);
        $pdf->addText(20,200,10,"Additional Invoice Notes");
        $pdf->addText(20,188,9,$additionalnotes);


    }

    $pdf->ezSetDy(-20);
    $data = array();


    $pdfcode = $pdf->output();

    //EMAIL MESSAGE
    $SupplierMail = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					<title>Untitled Document</title>
					</head>
					<body>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family: Arial, Helvetica, sans-serif">
						  
						  <tr>
							<td  style="font-family: Arial, Helvetica, sans-serif; font-size: 14px" valign="top">
							Dear ' . $CompanyName . '<br>
							  <br>
							  Kindly find attached your invoice ' . $InvoiceNumber . '. Please send us your POP as soon as you have settled your invoice.
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
    $data = chunk_split(base64_encode($pdfcode));
    $message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$files[$x]\"\n" .
        "Content-Disposition: attachment;\n" . " filename=\"$InvoiceNumber.pdf\"\n" .
        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
    $message .= "--{$mime_boundary}\n";

    //$EmailAddress = "tanshu321@gmail.com";
    //$ok = @mail('alex@allweb.co.za', "Invoice - " . $InvoiceNumber, $message, $headers);
    mail($EmailAddress, "Invoice - " . $InvoiceNumber, $message, $headers);

    //UPDATE INVOICE STATUS TO UNPAID
    $SentDate = date("Y-m-d H:i:s");

    $UpdateInvoice = "UPDATE customerinvoices SET InvoiceStatus = 1, SentToCustomer = 1, SentToCustomerDate = '{$SentDate}' WHERE InvoiceID = {$InvoiceID}";

    $DoUpdateInvoice = mysqli_query($ClientCon, $UpdateInvoice);

    return "OK";
}

function ResendCustomerInvoice($CustomerID, $InvoiceID)
{

    return SendCustomerInvoice($CustomerID, $InvoiceID);
}
/*
function ResendCustomerInvoice($CustomerID, $InvoiceID)
{

    return SendCustomerInvoice($CustomerID, $InvoiceID);

    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];

    $GetCustomer = "SELECT * FROM customers, countries WHERE CustomerID = {$CustomerID} AND customers.CountryID = countries.CountryID";
    $GotCustomer = mysqli_query($ClientCon, $GetCustomer);

    while ($Val = mysqli_fetch_array($GotCustomer)) {
        $Name = $Val["FirstName"];
        $Surname = $Val["Surname"];
        $CompanyName = $Val["CompanyName"];

        if ($CompanyName != "") {

        } else {
            $CompanyName = $Name . " " . $Surname;
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

    while ($Val = mysqli_fetch_array($GotInvoice)) {
        $InvoiceNumber = $Val["InvoiceNumber"];
        $InvoiceDate = $Val["InvoiceDate"];
        $DueDate = $Val["DueDate"];
        $AddedByName = $Val["AddedByName"];
        $InvoiceNotes = $Val["InvoiceNotes"];
        $additionalnotes = $Val["additionalnotes"];

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


            $InvoiceNotes = $InvoiceAdditional . $InvoiceNotes;
        }

    }

    $AddLog = AddLogInformation('Sent customer invoice number ' . $InvoiceNumber, 'Invoice', $CustomerID);

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

    $pdf->addText(350, 810, 10, "<b>TAX Invoice</b>");
    $pdf->addText(350, 790, 10, "<b>Invoice Number</b>");
    $pdf->addText(450, 790, 10, $InvoiceNumber);
    $pdf->addText(350, 770, 10, "<b>Invoice Date</b>");
    $pdf->addText(450, 770, 10, $InvoiceDate);
    $pdf->addText(350, 750, 10, "<b>Due Date</b>");
    $pdf->addText(450, 750, 10, $DueDate);
    $pdf->addText(350, 730, 10, "<b>Client Code</b>");
    $pdf->addText(450, 730, 10, $DepositReference);


    //BOTTOM
    $pdf->addText(470, 20, 8, "Invoice Number " . $InvoiceNumber);

    $data = array();

    $pdf->ezText("Invoice Details", 10, array('aleft' => 20));
    $pdf->ezSetDy(-10);

    $data[] = array('<b>Customer Details</b>' => '<b>' . $CompanyName . '</b>', '<b>Our Details</b>' => '<b>' . $DisplayCompany . '</b>', '<b>Banking Details</b>' => 'Bank : ' . $BankName);
    $data[] = array('<b>Customer Details</b>' => 'Tel: ' . $ContactNumber, '<b>Our Details</b>' => 'Tel : ' . $DisplayTel, '<b>Banking Details</b>' => 'Account Holder : ' . $AccountHolder);
    $data[] = array('<b>Customer Details</b>' => 'Email : ' . $EmailAddress, '<b>Our Details</b>' => 'Email : ' . $DisplayEmail, '<b>Banking Details</b>' => 'Account Number : ' . $AccountNumber);
    $data[] = array('<b>Customer Details</b>' => 'VAT Number : ' . $VatNumber, '<b>Our Details</b>' => 'VAT Number : ' . $DisplayVat, '<b>Banking Details</b>' => 'Branch Code : ' . $BranchCode);
    $data[] = array('<b>Customer Details</b>' => '', '<b>Our Details</b>' => 'Company Reg : ' . $CompanyReg, '<b>Banking Details</b>' => 'Account Type : ' . $AccountType);
    $data[] = array('<b>Customer Details</b>' => '<b>Address</b>', '<b>Our Details</b>' => '<b>Address</b>', '<b>Banking Details</b>' => 'Deposit Reference: ' . $DepositReference);
    $data[] = array('<b>Customer Details</b>' => $CustomerAddress1, '<b>Our Details</b>' => $Address1, '<b>Banking Details</b>' => '');
    $data[] = array('<b>Customer Details</b>' => $CustomerAddress2, '<b>Our Details</b>' => $Address2, '<b>Banking Details</b>' => '');
    $data[] = array('<b>Customer Details</b>' => $CustomerCity, '<b>Our Details</b>' => $City, '<b>Banking Details</b>' => '');
    $data[] = array('<b>Customer Details</b>' => $CustomerRegion, '<b>Our Details</b>' => $Region, '<b>Banking Details</b>' => '');
    $data[] = array('<b>Customer Details</b>' => $CustomerCountryName, '<b>Our Details</b>' => $CountryName, '<b>Banking Details</b>' => '');
    $data[] = array('<b>Customer Details</b>' => $CustomerPostCode, '<b>Our Details</b>' => $PostCode, '<b>Banking Details</b>' => '');


    $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 7, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'showLines' => 0, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));

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


        $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 7, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'showLines' => 0, 'showHeadings' => 0, 'cols' => array(
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
        $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 7, 'showHeadings' => 0, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'showLines' => 0, 'cols' => array(
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

        $GetSubCost = "SELECT SUM(LineSubTotal) AS SubTotal FROm customerinvoicelines WHERE InvoiceID = {$InvoiceID} AND GroupID = {$InvoiceGroupID}";
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

        $data[] = array('<b>Product</b>' => $GroupName, '<b>Price</b>' => 'R' . number_format($LineTotal, 2), '<b>QTY</b>' => 1, '<b>Sub Total</b>' => 'R' . number_format($SubTotal, 2), '<b>VAT</b>' => 'R' . number_format($VATTotal, 2), '<b>Total</b>' => 'R' . number_format($LineTotal, 2));
    }

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


        $data[] = array('<b>Product</b>' => '<b>' . $ThisLine . "</b>" . $ProductExtra, '<b>Price</b>' => 'R' . number_format($Price, 2), '<b>QTY</b>' => $Quantity, '<b>Sub Total</b>' => 'R' . number_format($LineSub, 2), '<b>VAT</b>' => 'R' . number_format($Vat, 2), '<b>Total</b>' => 'R' . number_format($LineTotal, 2));
    }


    $pdf->ezTable($data, '', '', array('shaded' => 1, 'fontSize' => 8, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));

    $pdf->ezSetDy(-20);

    //TOTALS
    $data = array();

    $data[] = array('<b>Total</b>' => '<b>Sub Total</b>', '<b>Price</b>' => 'R' . number_format($InvoiceSub, 2));
    $data[] = array('<b>Total</b>' => '<b>VAT</b>', '<b>Price</b>' => 'R' . number_format($InvoiceVat, 2));
    $data[] = array('<b>Total</b>' => '<b>Total</b>', '<b>Price</b>' => '<b>R' . number_format($InvoiceTotal, 2) . '</b>');

    $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 8, 'xPos' => 615, 'xOrientation' => 'left', 'width' => 200, 'showLines' => 0, 'showHeadings' => 0, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));


    $pdf->ezSetDy(-20);
    $data = array();


    $pdfcode = $pdf->output();

    //EMAIL MESSAGE
    $SupplierMail = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					<title>Untitled Document</title>
					</head>
					<body>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family: Arial, Helvetica, sans-serif">
						  
						  <tr>
							<td  style="font-family: Arial, Helvetica, sans-serif; font-size: 14px" valign="top">
							Dear ' . $CompanyName . '<br>
							  <br>
							  Kindly find attached your invoice ' . $InvoiceNumber . '. Please send us your POP as soon as you have settled your invoice.
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
    $data = chunk_split(base64_encode($pdfcode));
    $message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$files[$x]\"\n" .
        "Content-Disposition: attachment;\n" . " filename=\"$InvoiceNumber.pdf\"\n" .
        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
    $message .= "--{$mime_boundary}\n";


    $EmailAddress = "tanshu321@gmail.com";
    //$ok = mail('alex@allweb.co.za', "Invoice - " . $InvoiceNumber, $message, $headers);
    mail($EmailAddress, "Invoice - " . $InvoiceNumber, $message, $headers);

    $SentDate = date("Y-m-d H:i:s");

    $UpdateInvoice = "UPDATE customerinvoices SET SentToCustomer = 1, SentToCustomerDate = '{$SentDate}' WHERE InvoiceID = {$InvoiceID}";
    $DoUpdateInvoice = mysqli_query($ClientCon, $UpdateInvoice);
    echo mysqli_error($ClientCon);

    return "OK";
}


*/
function DeletePOLine($PurchaseLineItemID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $DoDel = "DELETE FROM purchaseorderlines WHERE PurchaseLineItemID = {$PurchaseLineItemID}";
    $Del = mysqli_query($ClientCon, $DoDel);

    return "OK";
}

function ResendSupplierPO($PurchaseID, $SupplierID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];

    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetSupplier = "SELECT * FROM suppliers WHERE SupplierID = {$SupplierID}";
    $GotSupplier = mysqli_query($ClientCon, $GetSupplier);

    while ($Val = mysqli_fetch_array($GotSupplier)) {
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

        if ($SupplierFax == "") {
            $SupplierFax = 'None';
        }

        if ($SupplierVat == "") {
            $SupplierVat = 'None';
        }
    }

    if ($SupplierEmail == "") {
        return "There is no supplier email setup, please enter the supplier email address firs";
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

        if ($DisplayFax == "") {
            $DisplayFax = 'None';
        }

        if ($DisplayVat == "") {
            $DisplayVat = 'None';
        }


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
    $GetPO = "SELECT * FROM purchaseorders WHERE PurchaseID = {$PurchaseID} AND SupplierID = {$SupplierID}";
    $GotPO = mysqli_query($ClientCon, $GetPO);

    while ($Val = mysqli_fetch_array($GotPO)) {
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

    $pdf->addText(350, 810, 10, "<b>PO Number</b>");
    $pdf->addText(430, 810, 10, $PurchaseNumber);
    $pdf->addText(350, 790, 10, "<b>Created</b>");
    $pdf->addText(430, 790, 10, $Created);
    $pdf->addText(350, 770, 10, "<b>Sent</b>");
    $pdf->addText(430, 770, 10, $SentDate);
    $pdf->addText(350, 750, 10, "<b>Supplier</b>");
    $pdf->addText(430, 750, 10, $SupplierName);

    //BOTTOM
    $pdf->addText(490, 20, 8, "PO Number " . $PurchaseNumber);

    $data = array();

    $pdf->ezText("Purchase Order Details", 10, array('aleft' => 20));
    $pdf->ezSetDy(-10);

    if ($DeliveryType == "Deliver") {
        $data[] = array('<b>Client Details</b>' => '<b>' . $SupplierName . '</b>', '<b>Customer Details</b>' => '<b>' . $DisplayCompany . '</b>', '<b>Delivery Address</b>' => $Address1);
        $data[] = array('<b>Client Details</b>' => 'Tel: ' . $SupplierTel, '<b>Customer Details</b>' => 'Tel : ' . $DisplayTel, '<b>Delivery Address</b>' => $Address2);
        $data[] = array('<b>Client Details</b>' => 'Fax: ' . $SupplierFax, '<b>Customer Details</b>' => 'Fax : ' . $DisplayFax, '<b>Delivery Address</b>' => $City);
        $data[] = array('<b>Client Details</b>' => 'Email : ' . $SupplierEmail, '<b>Customer Details</b>' => 'Email : ' . $DisplayEmail, '<b>Delivery Address</b>' => $Region);
        $data[] = array('<b>Client Details</b>' => 'Contact : ' . $SupplierContact, '<b>Customer Details</b>' => 'Requested By : ' . $AddedByName, '<b>Delivery Address</b>' => $CountryName);
        $data[] = array('<b>Client Details</b>' => 'VAT Number : ' . $SupplierVat, '<b>Customer Details</b>' => 'VAT Number : ' . $DisplayVat, '<b>Delivery Address</b>' => $PostCode);
    } else {
        $data[] = array('<b>Client Details</b>' => '<b>' . $SupplierName . '</b>', '<b>Customer Details</b>' => '<b>' . $DisplayCompany . '</b>', '<b>Delivery Address</b>' => 'Collect');
        $data[] = array('<b>Client Details</b>' => 'Tel: ' . $SupplierTel, '<b>Customer Details</b>' => 'Tel : ' . $DisplayTel, '<b>Delivery Address</b>' => '');
        $data[] = array('<b>Client Details</b>' => 'Fax: ' . $SupplierFax, '<b>Customer Details</b>' => 'Fax : ' . $DisplayFax, '<b>Delivery Address</b>' => '');
        $data[] = array('<b>Client Details</b>' => 'Email : ' . $SupplierEmail, '<b>Customer Details</b>' => 'Email : ' . $DisplayEmail, '<b>Delivery Address</b>' => '');
        $data[] = array('<b>Client Details</b>' => 'Contact : ' . $SupplierContact, '<b>Customer Details</b>' => 'Requested By : ' . $AddedByName, '<b>Delivery Address</b>' => '');
        $data[] = array('<b>Client Details</b>' => 'VAT Number : ' . $SupplierVat, '<b>Customer Details</b>' => 'VAT Number : ' . $DisplayVat, '<b>Delivery Address</b>' => '');

    }


    $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 8, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'showLines' => 0, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));


    $pdf->ezSetDy(-40);

    $pdf->ezText("Purchase Order Items", 10, array('aleft' => 20));

    $pdf->ezSetDy(-10);

    $InvoiceSub = 0;
    $InvoiceDiscount = 0;
    $InvoiceVat = 0;
    $InvoiceTotal = 0;


    $data = array();

    while ($Val = mysqli_fetch_array($GotLines)) {
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


        $data[] = array('<b>Product</b>' => $Description . '(' . $Meassure . ')', '<b>Price</b>' => 'R' . number_format($Price, 2), '<b>QTY</b>' => $Quantity, '<b>Sub Total</b>' => 'R' . number_format($LineSub, 2), '<b>VAT</b>' => 'R' . number_format($Vat, 2), '<b>Total</b>' => 'R' . number_format($LineTotal, 2));
    }


    $pdf->ezTable($data, '', '', array('shaded' => 1, 'fontSize' => 8, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));

    $pdf->ezSetDy(-20);

    //TOTALS
    $data = array();

    $data[] = array('<b>Total</b>' => '<b>Sub Total</b>', '<b>Price</b>' => 'R' . number_format($InvoiceSub, 2));
    $data[] = array('<b>Total</b>' => '<b>VAT</b>', '<b>Price</b>' => 'R' . number_format($InvoiceVat, 2));
    $data[] = array('<b>Total</b>' => '<b>Total</b>', '<b>Price</b>' => 'R' . number_format($InvoiceTotal, 2));

    $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 8, 'xPos' => 615, 'xOrientation' => 'left', 'width' => 200, 'showLines' => 0, 'showHeadings' => 0, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));


    $pdf->ezSetDy(-20);
    $data = array();

    if ($SpecialInstructions == "") {
        $SpecialInstructions = "None";
    }

    $data[] = array('<b>Special Instructions</b>' => $SpecialInstructions);

    $pdf->ezTable($data, '', '', array('shaded' => 1, 'fontSize' => 8, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'showLines' => 0, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));

    $pdfcode = $pdf->output();

    //EMAIL MESSAGE
    $SupplierMail = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					<title>Untitled Document</title>
					</head>
					<body>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family: Arial, Helvetica, sans-serif">
						  
						  <tr>
							<td  style="font-family: Arial, Helvetica, sans-serif; font-size: 14px" valign="top">
							Dear ' . $SupplierName . '<br>
							  <br>
							  Kindly find attached copy of our purchase order ' . $PurchaseNumber . '. Please let us know if there are any problems.
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
    $data = chunk_split(base64_encode($pdfcode));
    $message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$files[$x]\"\n" .
        "Content-Disposition: attachment;\n" . " filename=\"$PurchaseNumber.pdf\"\n" .
        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
    $message .= "--{$mime_boundary}\n";


    //$ok = @mail('alex@allweb.co.za', "Purchase Order - " . $PurchaseNumber, $message, $headers);
    mail($SupplierEmail, "Purchase Order - " . $PurchaseNumber, $message, $headers);

    return "OK";
}

function SendSupplierPO($PurchaseID, $SupplierID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];

    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetSupplier = "SELECT * FROM suppliers WHERE SupplierID = {$SupplierID}";
    $GotSupplier = mysqli_query($ClientCon, $GetSupplier);

    while ($Val = mysqli_fetch_array($GotSupplier)) {
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

        if ($SupplierFax == "") {
            $SupplierFax = 'None';
        }

        if ($SupplierVat == "") {
            $SupplierVat = 'None';
        }
    }

    if ($SupplierEmail == "") {
        return "There is no supplier email setup, please enter the supplier email address firs";
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

        if ($DisplayFax == "") {
            $DisplayFax = 'None';
        }

        if ($DisplayVat == "") {
            $DisplayVat = 'None';
        }


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
    $GetPO = "SELECT * FROM purchaseorders WHERE PurchaseID = {$PurchaseID} AND SupplierID = {$SupplierID}";
    $GotPO = mysqli_query($ClientCon, $GetPO);

    while ($Val = mysqli_fetch_array($GotPO)) {
        $PurchaseNumber = $Val["PurchaseNumber"];
        $Created = $Val["PurchaseOrderDate"];
        $AddedByName = $Val["AddedByName"];
        $SentDate = $Val["SentDate"];
        $DeliveryType = $Val["DeliveryType"];
        $SpecialInstructions = $Val["SpecialInstructions"];
    }

    if ($SentDate == "") {
        $SentDate = date("Y-m-d");
    }

    //AND THEN THE LINES
    $GetLines = "SELECT * FROM purchaseorderlines WHERE PurchaseID = {$PurchaseID}";
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

    $pdf->addText(350, 810, 10, "<b>PO Number</b>");
    $pdf->addText(430, 810, 10, $PurchaseNumber);
    $pdf->addText(350, 790, 10, "<b>Created</b>");
    $pdf->addText(430, 790, 10, $Created);
    $pdf->addText(350, 770, 10, "<b>Sent</b>");
    $pdf->addText(430, 770, 10, $SentDate);
    $pdf->addText(350, 750, 10, "<b>Supplier</b>");
    $pdf->addText(430, 750, 10, $SupplierName);

    //BOTTOM
    $pdf->addText(490, 20, 8, "PO Number " . $PurchaseNumber);

    $data = array();

    $pdf->ezText("Purchase Order Details", 10, array('aleft' => 20));
    $pdf->ezSetDy(-10);

    if ($DeliveryType == "Deliver") {
        $data[] = array('<b>Client Details</b>' => '<b>' . $SupplierName . '</b>', '<b>Customer Details</b>' => '<b>' . $DisplayCompany . '</b>', '<b>Delivery Address</b>' => $Address1);
        $data[] = array('<b>Client Details</b>' => 'Tel: ' . $SupplierTel, '<b>Customer Details</b>' => 'Tel : ' . $DisplayTel, '<b>Delivery Address</b>' => $Address2);
        $data[] = array('<b>Client Details</b>' => 'Fax: ' . $SupplierFax, '<b>Customer Details</b>' => 'Fax : ' . $DisplayFax, '<b>Delivery Address</b>' => $City);
        $data[] = array('<b>Client Details</b>' => 'Email : ' . $SupplierEmail, '<b>Customer Details</b>' => 'Email : ' . $DisplayEmail, '<b>Delivery Address</b>' => $Region);
        $data[] = array('<b>Client Details</b>' => 'Contact : ' . $SupplierContact, '<b>Customer Details</b>' => 'Requested By : ' . $AddedByName, '<b>Delivery Address</b>' => $CountryName);
        $data[] = array('<b>Client Details</b>' => 'VAT Number : ' . $SupplierVat, '<b>Customer Details</b>' => 'VAT Number : ' . $DisplayVat, '<b>Delivery Address</b>' => $PostCode);
    } else {
        $data[] = array('<b>Client Details</b>' => '<b>' . $SupplierName . '</b>', '<b>Customer Details</b>' => '<b>' . $DisplayCompany . '</b>', '<b>Delivery Address</b>' => 'Collect');
        $data[] = array('<b>Client Details</b>' => 'Tel: ' . $SupplierTel, '<b>Customer Details</b>' => 'Tel : ' . $DisplayTel, '<b>Delivery Address</b>' => '');
        $data[] = array('<b>Client Details</b>' => 'Fax: ' . $SupplierFax, '<b>Customer Details</b>' => 'Fax : ' . $DisplayFax, '<b>Delivery Address</b>' => '');
        $data[] = array('<b>Client Details</b>' => 'Email : ' . $SupplierEmail, '<b>Customer Details</b>' => 'Email : ' . $DisplayEmail, '<b>Delivery Address</b>' => '');
        $data[] = array('<b>Client Details</b>' => 'Contact : ' . $SupplierContact, '<b>Customer Details</b>' => 'Requested By : ' . $AddedByName, '<b>Delivery Address</b>' => '');
        $data[] = array('<b>Client Details</b>' => 'VAT Number : ' . $SupplierVat, '<b>Customer Details</b>' => 'VAT Number : ' . $DisplayVat, '<b>Delivery Address</b>' => '');

    }


    $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 8, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'showLines' => 0, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));


    $pdf->ezSetDy(-40);

    $pdf->ezText("Purchase Order Items", 10, array('aleft' => 20));

    $pdf->ezSetDy(-10);


    $InvoiceSub = 0;
    $InvoiceDiscount = 0;
    $InvoiceVat = 0;
    $InvoiceTotal = 0;


    $data = array();

    while ($Val = mysqli_fetch_array($GotLines)) {
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


        $data[] = array('<b>Product</b>' => $Description . '(' . $Meassure . ')', '<b>Price</b>' => 'R' . number_format($Price, 2), '<b>QTY</b>' => $Quantity, '<b>Sub Total</b>' => 'R' . number_format($LineSub, 2), '<b>VAT</b>' => 'R' . number_format($Vat, 2), '<b>Total</b>' => 'R' . number_format($LineTotal, 2));
    }


    $pdf->ezTable($data, '', '', array('shaded' => 1, 'fontSize' => 8, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));

    $pdf->ezSetDy(-20);

    //TOTALS
    $data = array();

    $data[] = array('<b>Total</b>' => '<b>Sub Total</b>', '<b>Price</b>' => 'R' . number_format($InvoiceSub, 2));
    $data[] = array('<b>Total</b>' => '<b>VAT</b>', '<b>Price</b>' => 'R' . number_format($InvoiceVat, 2));
    $data[] = array('<b>Total</b>' => '<b>Total</b>', '<b>Price</b>' => 'R' . number_format($InvoiceTotal, 2));

    $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 8, 'xPos' => 615, 'xOrientation' => 'left', 'width' => 200, 'showLines' => 0, 'showHeadings' => 0, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));


    $pdf->ezSetDy(-20);
    $data = array();

    if ($SpecialInstructions == "") {
        $SpecialInstructions = "None";
    }

    $data[] = array('<b>Special Instructions</b>' => $SpecialInstructions);

    $pdf->ezTable($data, '', '', array('shaded' => 1, 'fontSize' => 8, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'showLines' => 0, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));

    $pdfcode = $pdf->output();

    //EMAIL MESSAGE
    $SupplierMail = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					<title>Untitled Document</title>
					</head>
					<body>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family: Arial, Helvetica, sans-serif">
						  
						  <tr>
							<td  style="font-family: Arial, Helvetica, sans-serif; font-size: 14px" valign="top">
							Dear ' . $SupplierName . '<br>
							  <br>
							  Kindly find attached copy of our purchase order ' . $PurchaseNumber . '. Please let us know if there are any problems.
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
    $data = chunk_split(base64_encode($pdfcode));
    $message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$files[$x]\"\n" .
        "Content-Disposition: attachment;\n" . " filename=\"$PurchaseNumber.pdf\"\n" .
        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
    $message .= "--{$mime_boundary}\n";


    //$ok = @mail('alex@allweb.co.za', "Purchase Order - " . $PurchaseNumber, $message, $headers);
    mail($SupplierEmail, "Purchase Order - " . $PurchaseNumber, $message, $headers);

    //THEN UPDATE IT WAS SENT
    $UpdatePO = "UPDATE purchaseorders SET  SentDate = '{$SentDate}', PurchaseStatus = 1 WHERE PurchaseID = {$PurchaseID} AND SupplierID = {$SupplierID}";
    $DoUpdatePO = mysqli_query($ClientCon, $UpdatePO);


    return "OK";


}

function CreateSupplierInvoiceFromPO($PurchaseID, $SupplierID, $PurchaseNumber, $WarehouseID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    //FIRST CREATE INVOICE HEADER
    $ThisClientID = $_SESSION["ClientID"];
    $ThisUser = $_SESSION["ClientName"];
    $DateAdded = date("Y-m-d");

    $EmployeeID = $Val["EmployeeID"];
    if ($EmployeeID == "") {
        $EmployeeID = 0;
    }

    $AddInvoice = "INSERT INTO supplierorders (SupplierID, PurchaseOrderID, InvoiceDate, InvoiceStatus, AddedByClient, AddedByEmployee, AddedByName, PurchaseNumber, WarehouseID) ";
    $AddInvoice .= "VALUES ({$SupplierID}, {$PurchaseID}, '{$DateAdded}', 0, {$ThisClientID}, {$EmployeeID}, '{$ThisUser}', '{$PurchaseNumber}', {$WarehouseID})";

    $DoAddInvoice = mysqli_query($ClientCon, $AddInvoice);

    $Error = mysqli_error($ClientCon);
    if ($Error == "") {
        $SupplierInvoiceID = mysqli_insert_id($ClientCon);

        //THEN ADD THE ITEMS TO IT
        $PurchaseLines = GetPurchaseOrderLines($PurchaseID);

        while ($Val = mysqli_fetch_array($PurchaseLines)) {
            $Description = $Val["Description"];
            $Quantity = $Val["Quantity"];
            $Price = $Val["Price"];
            $ProductID = $Val["ProductID"];
            $ProductCode = $Val["ProductCode"];
            $LineSubTotal = $Val["LineSubTotal"];
            $LineDiscount = $Val["LineDiscount"];
            $LineVAT = $Val["LineVAT"];
            $LineTotal = $Val["LineTotal"];
            $BillingType = $Val["BillingType"];
            $StockAffect = $Val["StockAffect"];
            $MeassurementDescription = $Val["MeassurementDescription"];
            $SupplierCostID = $Val["SupplierCostID"];

            //THEN ADD
            $AddLine = "INSERT INTO supplierorderlines (SupplierInvoiceID, Description, Quantity, Price, ProductID, ProductCode, LineSubTotal, LineDiscount, LineVAT, LineTotal, BillingType, StockAffect, MeassurementDescription, SupplierCostID) ";
            $AddLine .= "VALUES ({$SupplierInvoiceID}, '{$Description}', {$Quantity}, {$Price}, {$ProductID}, '{$ProductCode}', {$LineSubTotal}, {$LineDiscount}, {$LineVAT}, {$LineTotal}, '{$BillingType}', {$StockAffect}, '{$MeassurementDescription}', {$SupplierCostID})";
            $DoAddLine = mysqli_query($ClientCon, $AddLine);

        }

        //THEN UPDATE THE PO
        $UpdatePO = "UPDATE purchaseorders SET  PurchaseStatus = 2 WHERE PurchaseID = {$PurchaseID} AND SupplierID = {$SupplierID}";
        $DoUpdatePO = mysqli_query($ClientCon, $UpdatePO);

        return $SupplierInvoiceID;
    } else {
        return "There was an error adding the invoice header";
    }
}

function GetSupplierPrice($PricingID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetPrice = "SELECT * FROM suppliercost WHERE SupplierCostID = {$PricingID}";
    $GotPrice = mysqli_query($ClientCon, $GetPrice);

    while ($Val = mysqli_fetch_array($GotPrice)) {
        $SupplierCost = $Val["SupplierCost"];
    }

    return $SupplierCost;
}

//COMPANY SETTINGS
function GetCompanyInvoiceLogo()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetInvoiceLogo = "SELECT InvoiceLogo FROM companysettings";
    $GotInvoiceLogo = mysqli_query($ClientCon, $GetInvoiceLogo);
    echo mysqli_error($ClientCon);

    while ($Val = mysqli_fetch_array($GotInvoiceLogo)) {
        $InvoiceLogo = $Val["InvoiceLogo"];
    }

    return $InvoiceLogo;
}

function LinkSupplierProduct($ProductID, $SupplierID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    //CHECK IF NOT LINKED ALREADY
    $CheckLink = "SELECT * FROM supplierproducts WHERE SupplierID = {$SupplierID} AND ProductID = {$ProductID}";
    $DoCheckLink = mysqli_query($ClientCon, $CheckLink);

    $FoundLink = mysqli_num_rows($DoCheckLink);

    if ($FoundLink == 0) {
        $InsertLink = "INSERT INTO supplierproducts (SupplierID, ProductID) VALUES ({$SupplierID}, {$ProductID})";
        $DoInsertLink = mysqli_query($ClientCon, $InsertLink);

        $Error = mysqli_error($ClientCon);
        if ($Error == "") {
            return "OK";
        } else {
            return "There was an error linking the product to the supplier";
        }
    } else {
        return "This product is already linked to the supplier";
    }
}

function UpdatePOLine($PurchaseLineItemID, $NewPrice, $NewQuantity, $UpdateCost, $ProductID, $SupplierID, $SupplierCostID, $ChargesVAT)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    ////THEN THE ACTUAL LINE ITEM
    //PRODUCT DETAILS
    $GetProduct = "SELECT * FROM products WHERE ProductID = {$ProductID}";
    $GotProduct = mysqli_query($ClientCon, $GetProduct);
    echo mysqli_error($ClientCon);

    while ($Val = mysqli_fetch_array($GotProduct)) {
        $ProductName = $Val["ProductName"];
        $ProductCode = $Val["ProductCode"];
        $ProductDescription = $Val["ProductDescription"];
    }

    //PRICING DETAILS
    $GetPricing = "SELECT * FROM suppliercost WHERE SupplierCostID = {$SupplierCostID}";
    $GotPricing = mysqli_query($ClientCon, $GetPricing);

    while ($Val = mysqli_fetch_array($GotPricing)) {
        $ProductCostID = $Val["SupplierCostID"];
        $BillingType = $Val["BillingType"];
        $ClientCost = $NewPrice;
        $PackSize = $Val["PackSize"];
        $MeassurementID = $Val["MeasurementID"];
        $StockAffect = $Val["StockAffect"];
        $OrigStockAffect = $StockAffect;

        $MeasurementDescription = "";

        if ($MeassurementID > 0) {
            $GetMeassurement = "SELECT * FROM productmeasurement WHERE MeasurementID = {$MeassurementID}";
            $GotMeassurement = mysqli_query($ClientCon, $GetMeassurement);
            echo mysqli_error($ClientCon);

            while ($Measure = mysqli_fetch_array($GotMeassurement)) {
                $MeasurementDescription = $Measure["MeasurementDescription"];
            }

        }
    }

    //OK ENOUGH INFO
    if ($MeasurementDescription != "") {
        $MeasurreDescript = $PackSize . " " . $MeasurementDescription;
    } else {
        $MeasurreDescript = $PackSize;
    }

    $ThisProduct = $ProductName . " - " . $ProductDescription;

    $CostBeforeVat = $NewPrice;


    $SubTotal = $ClientCost * $NewQuantity;

    if ($DiscountPercent > 0) {
        $DiscountPercent = ($DiscountPercent / 100);
        $DiscountAmount = $SubTotal * $DiscountPercent;

    } else {
        $DiscountAmount = 0;
    }

    $DiscountAmount = number_format($DiscountAmount, 2, '.', '');

    $VatableAmount = $SubTotal - $DiscountAmount;

    if ($ChargesVAT == 1) {
        //MUST DO VAT
        $VatInc = $VatableAmount * 1.14;
        $Vat = $VatInc - $VatableAmount;
    } else {
        $Vat = 0;
    }

    $Vat = number_format($Vat, 2, '.', '');

    $LineTotal = $VatableAmount + $Vat;

    $StockAffect = $StockAffect * $NewQuantity;


    $UpdateLine = "UPDATE purchaseorderlines SET Quantity = {$NewQuantity}, Price = {$ClientCost}, LineSubTotal = {$SubTotal}, LineDiscount = {$DiscountAmount}, LineVAT =  {$Vat}, LineTotal = {$LineTotal}, StockAffect = {$StockAffect} WHERE PurchaseLineItemID = {$PurchaseLineItemID} ";
    $DoUpdateLine = mysqli_query($ClientCon, $UpdateLine);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        if ($UpdateCost == "true") {
            $UpdateSupplierCost = "UPDATE suppliercost SET SupplierCost = {$NewPrice} WHERE SupplierCostID = {$SupplierCostID}";
            $DoUpdateCost = mysqli_query($ClientCon, $UpdateSupplierCost);

            $DateUpdated = date("Y-m-d");

            $UnitCost = $NewPrice / $OrigStockAffect;
            $UnitCost = number_format($UnitCost, 2, '.', '');

            $InsertCostTrack = "INSERT INTO suppliercostingtracking (ProductID, SupplierCostID, SupplierCost, UnitCost, PriceDate) VALUES ({$ProductID}, {$SupplierCostID}, {$NewPrice}, {$UnitCost}, '{$DateUpdated}')";
            $DoInsertTrack = mysqli_query($ClientCon, $InsertCostTrack);
        }

        return "OK";
    } else {
        return "There was an error updating the purchase order line" . $UpdateLine;
    }


}

function UpdateInvoiceLine($SupplierLineItemID, $NewPrice, $NewQuantity, $UpdateCost, $ProductID, $SupplierID, $SupplierCostID, $ChargesVAT)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    ////THEN THE ACTUAL LINE ITEM
    //PRODUCT DETAILS
    $GetProduct = "SELECT * FROM products WHERE ProductID = {$ProductID}";
    $GotProduct = mysqli_query($ClientCon, $GetProduct);
    echo mysqli_error($ClientCon);

    while ($Val = mysqli_fetch_array($GotProduct)) {
        $ProductName = $Val["ProductName"];
        $ProductCode = $Val["ProductCode"];
        $ProductDescription = $Val["ProductDescription"];
    }

    //PRICING DETAILS
    $GetPricing = "SELECT * FROM suppliercost WHERE SupplierCostID = {$SupplierCostID}";
    $GotPricing = mysqli_query($ClientCon, $GetPricing);

    while ($Val = mysqli_fetch_array($GotPricing)) {
        $ProductCostID = $Val["SupplierCostID"];
        $BillingType = $Val["BillingType"];
        $ClientCost = $NewPrice;
        $PackSize = $Val["PackSize"];
        $MeassurementID = $Val["MeasurementID"];
        $StockAffect = $Val["StockAffect"];
        $OrigStockAffect = $StockAffect;

        $MeasurementDescription = "";

        if ($MeassurementID > 0) {
            $GetMeassurement = "SELECT * FROM productmeasurement WHERE MeasurementID = {$MeassurementID}";
            $GotMeassurement = mysqli_query($ClientCon, $GetMeassurement);
            echo mysqli_error($ClientCon);

            while ($Measure = mysqli_fetch_array($GotMeassurement)) {
                $MeasurementDescription = $Measure["MeasurementDescription"];
            }

        }
    }

    //OK ENOUGH INFO
    if ($MeasurementDescription != "") {
        $MeasurreDescript = $PackSize . " " . $MeasurementDescription;
    } else {
        $MeasurreDescript = $PackSize;
    }

    $ThisProduct = $ProductName . " - " . $ProductDescription;

    $CostBeforeVat = $NewPrice;


    $SubTotal = $ClientCost * $NewQuantity;

    if ($DiscountPercent > 0) {
        $DiscountPercent = ($DiscountPercent / 100);
        $DiscountAmount = $SubTotal * $DiscountPercent;

    } else {
        $DiscountAmount = 0;
    }

    $DiscountAmount = number_format($DiscountAmount, 2, '.', '');

    $VatableAmount = $SubTotal - $DiscountAmount;

    if ($ChargesVAT == 1) {
        //MUST DO VAT
        $VatInc = $VatableAmount * 1.14;
        $Vat = $VatInc - $VatableAmount;
    } else {
        $Vat = 0;
    }

    $Vat = number_format($Vat, 2, '.', '');

    $LineTotal = $VatableAmount + $Vat;

    $StockAffect = $StockAffect * $NewQuantity;


    $UpdateLine = "UPDATE supplierorderlines SET Quantity = {$NewQuantity}, Price = {$ClientCost}, LineSubTotal = {$SubTotal}, LineDiscount = {$DiscountAmount}, LineVAT =  {$Vat}, LineTotal = {$LineTotal}, StockAffect = {$StockAffect} WHERE SupplierInvoiceLineItemID = {$SupplierLineItemID} ";
    $DoUpdateLine = mysqli_query($ClientCon, $UpdateLine);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        if ($UpdateCost == "true") {
            $UpdateSupplierCost = "UPDATE suppliercost SET SupplierCost = {$NewPrice} WHERE SupplierCostID = {$SupplierCostID}";
            $DoUpdateCost = mysqli_query($ClientCon, $UpdateSupplierCost);

            $DateUpdated = date("Y-m-d");

            $UnitCost = $NewPrice / $OrigStockAffect;
            $UnitCost = number_format($UnitCost, 2, '.', '');

            $InsertCostTrack = "INSERT INTO suppliercostingtracking (ProductID, SupplierCostID, SupplierCost, UnitCost, PriceDate) VALUES ({$ProductID}, {$SupplierCostID}, {$NewPrice}, {$UnitCost}, '{$DateUpdated}')";
            $DoInsertTrack = mysqli_query($ClientCon, $InsertCostTrack);
        }

        return "OK";
    } else {
        return "There was an error updating the invoice line" . $Error;
    }


}

//EMPLOYEES
function GetAllEmployees()
{
    include('includes/dbinc.php');

    $ClientID = $_SESSION["ClientID"];

    $GetEmployees = "SELECT * FROM employees WHERE ClientID = {$ClientID} ORDER BY Name, Surname";
    $GotEmployees = mysqli_query($DB, $GetEmployees);


    return $GotEmployees;
}

function GetAllDepartments()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetDepartments = "SELECT * FROM employeedepartments ORDER BY DepartmentName";
    $GotDepartments = mysqli_query($ClientCon, $GetDepartments);

    return $GotDepartments;
}

function GetNumEmployeesDepartment($DepartmentID)
{
    include('includes/dbinc.php');

    $GetEmployees = "SELECT COUNT(EmployeeID) AS NumEmployees FROM employees WHERE DepartmentID = {$DepartmentID}";
    $GotEmployees = mysqli_query($DB, $GetEmployees);

    while ($Val = mysqli_fetch_array($GotEmployees)) {
        $NumEmployees = $Val["NumEmployees"];
    }

    return $NumEmployees;
}

function AddDepartment($NewDepartment)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $NewDepartment = CleanInput($NewDepartment);

    //CHECK IF NOT THERE ALREADY
    $CheckDepartment = "SELECT * FROM employeedepartments WHERE DepartmentName = '{$NewDepartment}'";
    $DoCheckDepartment = mysqli_query($ClientCon, $CheckDepartment);
    $FoundDepartment = mysqli_num_rows($DoCheckDepartment);

    if ($FoundDepartment == 0) {
        $AddDepartment = "INSERT INTO employeedepartments (DepartmentName) VALUES ('{$NewDepartment}')";
        $DoAddDepartment = mysqli_query($ClientCon, $AddDepartment);

        $Error = mysqli_error($ClientCon);
        if ($Error == "") {
            return "OK";
        } else {
            return "There was an error adding the department, please check your inut and try again";
        }
    } else {
        return "The department already ecist";
    }
}

function UpdateDepartment($DepartmentID, $NewDepartment)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $NewDepartment = CleanInput($NewDepartment);

    //CHECK IF NOT THERE ALREADY
    $CheckDepartment = "SELECT * FROM employeedepartments WHERE DepartmentName = '{$NewDepartment}' AND DepartmentID != {$DepartmentID}";
    $DoCheckDepartment = mysqli_query($ClientCon, $CheckDepartment);
    $FoundDepartment = mysqli_num_rows($DoCheckDepartment);

    if ($FoundDepartment == 0) {
        $UpdateDepartment = "UPDATE employeedepartments SET DepartmentName = '{$NewDepartment}' WHERE DepartmentID = {$DepartmentID}";
        $DoUpdateDepartment = mysqli_query($ClientCon, $UpdateDepartment);

        $Error = mysqli_error($ClientCon);
        if ($Error == "") {
            return "OK";
        } else {
            return "There was an error updating the department, please check your inut and try again";
        }
    } else {
        return "The department already ecist";
    }
}

function GetEmployee($EmployeeID)
{
    include('includes/dbinc.php');

    $GetEmployee = "SELECT * FROM employees WHERE EmployeeID = {$EmployeeID}";
    $GotEmployee = mysqli_query($DB, $GetEmployee);


    return $GotEmployee;
}

function GetDepartmentName($DepartmentID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $CheckDepartment = "SELECT * FROM employeedepartments WHERE DepartmentID = {$DepartmentID}";
    $DoCheckDepartment = mysqli_query($ClientCon, $CheckDepartment);

    while ($Val = mysqli_fetch_array($DoCheckDepartment)) {
        $DepartmentName = $Val["DepartmentName"];
    }

    return $DepartmentName;
}

function MaxSystemUsers()
{
    include('includes/dbinc.php');
    $ClientID = $_SESSION["ClientID"];

    $GetMaxUsers = "SELECT * FROM clientpackage, packages WHERE clientpackage.PackageID = packages.PackageID AND ClientID = {$ClientID}";
    $GotMaxUsers = mysqli_query($DB, $GetMaxUsers);

    while ($Val = mysqli_fetch_array($GotMaxUsers)) {
        $MaxUsers = $Val["MaxUsers"];
    }

    //THEN HOW MANY DO WE HAVE NOW
    $GetEmployees = "SELECT * FROM employees WHERE ClientID = {$ClientID} AND SystemAccess = 1 ORDER BY Name, Surname";
    $GotEmployees = mysqli_query($DB, $GetEmployees);
    $NumEmployees = mysqli_num_rows($GotEmployees);

    $Spots = $MaxUsers - $NumEmployees;

    return $Spots;

}

function MaxSystemUsersEdit($EmployeeID)
{
    include('includes/dbinc.php');
    $ClientID = $_SESSION["ClientID"];

    $GetMaxUsers = "SELECT * FROM clientpackage, packages WHERE clientpackage.PackageID = packages.PackageID AND ClientID = {$ClientID}";
    $GotMaxUsers = mysqli_query($DB, $GetMaxUsers);

    while ($Val = mysqli_fetch_array($GotMaxUsers)) {
        $MaxUsers = $Val["MaxUsers"];
    }

    //THEN HOW MANY DO WE HAVE NOW
    $GetEmployees = "SELECT * FROM employees WHERE ClientID = {$ClientID} AND SystemAccess = 1 AND EmployeeID != {$EmployeeID} ORDER BY Name, Surname";
    $GotEmployees = mysqli_query($DB, $GetEmployees);
    $NumEmployees = mysqli_num_rows($GotEmployees);

    $Spots = $MaxUsers - $NumEmployees;

    return $Spots;
}

function UpdateEmployeeDetails($Name, $Surname, $IDNumber, $EmployeeNumber, $TaxReference, $Department, $Extension, $ContactHome, $ContactCell, $ContactEmail, $AlternativeContact, $AlternativeTel, $Address1, $Address2, $City, $State, $PostCode, $Country, $SystemAccess, $EmployeeStatus, $UserName, $Password, $EmployeeID, $SecurityGroup)
{
    include('includes/dbinc.php');
    $ClientID = $_SESSION["ClientID"];

    $Name = CleanInput($Name);
    $Surname = CleanInput($Surname);
    $IDNumber = CleanInput($IDNumber);
    $EmployeeNumber = CleanInput($EmployeeNumber);
    $TaxReference = CleanInput($TaxReference);
    $Extension = CleanInput($Extension);
    $ContactHome = CleanInput($ContactHome);
    $ContactCell = CleanInput($ContactCell);
    $ContactEmail = CleanInput($ContactEmail);
    $AlternativeContact = CleanInput($AlternativeContact);
    $AlternativeTel = CleanInput($AlternativeTel);
    $Address1 = CleanInput($Address1);
    $Address2 = CleanInput($Address2);
    $City = CleanInput($City);
    $State = CleanInput($State);
    $PostCode = CleanInput($PostCode);
    $UserName = CleanInput($UserName);
    $Password = CleanInput($Password);

    if ($UserName != "") {
        $CheckUserName = "SELECT * FROM employees WHERE UserName = '{$UserName}' AND EmployeeID != {$EmployeeID}";
        $DoCheckUserName = mysqli_query($DB, $CheckUserName);

        $FoundUser = mysqli_num_rows($DoCheckUserName);

        if ($FoundUser > 0) {
            return "The username is already in use, please select a different username";
        }
    }

    $UpdateEmployee = "UPDATE employees SET Name = '{$Name}', Surname = '{$Surname}', IDNumber = '{$IDNumber}', EmployeeNumber = '{$EmployeeNumber}', TaxReference = '{$TaxReference}', Address1 = '{$Address1}', Address2 = '{$Address2}', City = '{$City}', ";
    $UpdateEmployee .= "Region = '{$State}', PostCode = '{$PostCode}', CountryID = {$Country}, ContactHome = '{$ContactHome}', ContactCell = '{$ContactCell}', ContactEmail = '{$ContactEmail}', UserName = '{$UserName}', SystemAccess = {$SystemAccess}, ";
    $UpdateEmployee .= "EmployeeStatus = {$EmployeeStatus}, DepartmentID = {$Department}, InternalExtension = '{$Extension}', AdditionalContact = '{$AlternativeContact}', AdditionalContactNumber = '{$AlternativeTel}' ";
    if ($Password != "") {
        $Salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);

        $options["cost"] = 11;
        $options["salt"] = $Salt;

        $HashPass = password_hash($Password, PASSWORD_BCRYPT, $options);

        $UpdateEmployee .= ", Password = '{$HashPass}' ";
    }

    $UpdateEmployee .= "WHERE EmployeeID = {$EmployeeID} AND ClientID = {$ClientID}";

    $DoUpdateEmployee = mysqli_query($DB, $UpdateEmployee);

    $Error = mysqli_error($DB);

    if ($Error == "") {
        //THEN SECURITY GROUP IF ANY
        $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

        $DelCurrent = "DELETE FROM employeesecuritygroups WHERE EmployeeID = {$EmployeeID}";
        $DoDelCurrent = mysqli_query($ClientCon, $DelCurrent);

        if ($SecurityGroup != "") {
            $InsertCurrent = "INSERT INTO employeesecuritygroups (SecurityGroupID, EmployeeID) VALUES ({$SecurityGroup}, {$EmployeeID})";
            $DoInsertCurrent = mysqli_query($ClientCon, $InsertCurrent);

        }

        return "OK";
    } else {
        return "There was an error updating the employee details, please check your input and try again";
    }


}

function AddEmployeeDetails($Name, $Surname, $IDNumber, $EmployeeNumber, $TaxReference, $Department, $Extension, $ContactHome, $ContactCell, $ContactEmail, $AlternativeContact, $AlternativeTel, $Address1, $Address2, $City, $State, $PostCode, $Country, $SystemAccess, $EmployeeStatus, $UserName, $Password, $SecurityGroup)
{

    include('includes/dbinc.php');
    $ClientID = $_SESSION["ClientID"];

    $Name = CleanInput($Name);
    $Surname = CleanInput($Surname);
    $IDNumber = CleanInput($IDNumber);
    $EmployeeNumber = CleanInput($EmployeeNumber);
    $TaxReference = CleanInput($TaxReference);
    $Extension = CleanInput($Extension);
    $ContactHome = CleanInput($ContactHome);
    $ContactCell = CleanInput($ContactCell);
    $ContactEmail = CleanInput($ContactEmail);
    $AlternativeContact = CleanInput($AlternativeContact);
    $AlternativeTel = CleanInput($AlternativeTel);
    $Address1 = CleanInput($Address1);
    $Address2 = CleanInput($Address2);
    $City = CleanInput($City);
    $State = CleanInput($State);
    $PostCode = CleanInput($PostCode);
    $UserName = CleanInput($UserName);
    $Password = CleanInput($Password);

    if ($UserName != "") {
        $CheckUserName = "SELECT * FROM employees WHERE UserName = '{$UserName}'";
        $DoCheckUserName = mysqli_query($DB, $CheckUserName);

        $FoundUser = mysqli_num_rows($DoCheckUserName);

        if ($FoundUser > 0) {
            return "The username is already in use, please select a different username";
        }
    }

    $AddEmployee = "INSERT INTO employees (Name, Surname, IDNumber, EmployeeNumber, TaxReference, Address1, Address2, City, ";
    $AddEmployee .= "Region, PostCode, CountryID, ContactHome, ContactCell, ContactEmail, UserName, SystemAccess, ";
    $AddEmployee .= "EmployeeStatus, DepartmentID, InternalExtension, AdditionalContact, AdditionalContactNumber, ClientID ";
    if ($Password != "") {
        $AddEmployee .= ", Password ";
    }

    $AddEmployee .= ") ";

    $AddEmployee .= "VALUES ('{$Name}', '{$Surname}', '{$IDNumber}', '{$EmployeeNumber}', '{$TaxReference}', '{$Address1}', '{$Address2}', '{$City}', ";
    $AddEmployee .= "'{$State}', '{$PostCode}', {$Country}, '{$ContactHome}', '{$ContactCell}', '{$ContactEmail}', '{$UserName}', {$SystemAccess}, ";
    $AddEmployee .= "{$EmployeeStatus}, {$Department}, '{$Extension}', '{$AlternativeContact}', '{$AlternativeTel}', {$ClientID} ";
    if ($Password != "") {
        $Salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);

        $options["cost"] = 11;
        $options["salt"] = $Salt;

        $HashPass = password_hash($Password, PASSWORD_BCRYPT, $options);

        $AddEmployee .= ", '{$HashPass}' ";
    }

    $AddEmployee .= ")";

    $DoAddEmployee = mysqli_query($DB, $AddEmployee);


    $Error = mysqli_error($DB);

    if ($Error == "") {
        $EmployeeID = mysqli_insert_id($DB);

        $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

        if ($SecurityGroup != "") {
            $InsertCurrent = "INSERT INTO employeesecuritygroups (SecurityGroupID, EmployeeID) VALUES ({$SecurityGroup}, {$EmployeeID})";
            $DoInsertCurrent = mysqli_query($ClientCon, $InsertCurrent);

        }

        return "OK";
    } else {
        return "There was an error adding the employee details, please check your input and try again" . $AddEmployee;
    }


}

//STOCK CONTROL SYSTEM
function GetAllStockProducts()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    //ONLY GET THE PRODUCT WE WANT  TO MANAGE STOCK FOR
    $GetStockProducts = "SELECT * FROM products, productgroups  WHERE IsStockItem = 1 AND products.ProductGroupID = productgroups.ProductGroupID  ORDER BY ProductName";
    $GotStockProducts = mysqli_query($ClientCon, $GetStockProducts);


    return $GotStockProducts;
}

function GetStockIn($ProductID, $WarehouseID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetStockIn = "SELECT SUM(Stock) AS StockIn FROM productstock WHERE Stock > 0 AND ProductID = {$ProductID} AND WarehouseID = {$WarehouseID}";
    $GotStockIn = mysqli_query($ClientCon, $GetStockIn);

    while ($Val = mysqli_fetch_array($GotStockIn)) {
        $StockIn = $Val["StockIn"];
    }

    return $StockIn;

}

function GetStockOut($ProductID, $WarehouseID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetStockOut = "SELECT SUM(Stock) AS StockOut FROM productstock WHERE Stock < 0 AND ProductID = {$ProductID} AND WarehouseID = {$WarehouseID}";
    $GotStockOut = mysqli_query($ClientCon, $GetStockOut);

    while ($Val = mysqli_fetch_array($GotStockOut)) {
        $StockOut = $Val["StockOut"];
    }

    return $StockOut;
}

function GetProductName($ProductID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetProductName = "SELECT ProductName FROM products WHERE ProductID = {$ProductID}";
    $GotProductName = mysqli_query($ClientCon, $GetProductName);

    echo mysqli_error($ClientCon);

    while ($Val = mysqli_fetch_array($GotProductName)) {
        $ProductName = $Val["ProductName"];
    }

    return $ProductName;
}

function GetAllStockOut($ProductID, $WarehouseID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetStockOut = "SELECT * FROM productstock WHERE Stock < 0 AND ProductID = {$ProductID} AND StockType = 'Sell' AND WarehouseID = {$WarehouseID} ORDER BY StockID DESC";
    $GotStockOut = mysqli_query($ClientCon, $GetStockOut);

    return $GotStockOut;
}

function GetAllStockOutAll($ProductID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetStockOut = "SELECT * FROM productstock WHERE Stock < 0 AND ProductID = {$ProductID} AND StockType = 'Sell' ORDER BY StockID DESC";
    $GotStockOut = mysqli_query($ClientCon, $GetStockOut);

    return $GotStockOut;
}

function GetAllStockTake($ProductID, $WarehouseID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetStockOut = "SELECT * FROM productstock WHERE ProductID = {$ProductID} AND StockType LIKE 'Stock Take%' AND WarehouseID = {$WarehouseID} ORDER BY StockID DESC";
    $GotStockOut = mysqli_query($ClientCon, $GetStockOut);

    return $GotStockOut;
}

function GetAllStockTakeAll($ProductID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetStockOut = "SELECT * FROM productstock WHERE ProductID = {$ProductID} AND StockType LIKE 'Stock Take%' ORDER BY StockID DESC";
    $GotStockOut = mysqli_query($ClientCon, $GetStockOut);

    return $GotStockOut;
}

function GetAllStockTransfer($ProductID, $WarehouseID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetStockOut = "SELECT * FROM productstock WHERE ProductID = {$ProductID} AND StockType LIKE 'Stock Movement%' AND WarehouseID = {$WarehouseID} ORDER BY StockID DESC";
    $GotStockOut = mysqli_query($ClientCon, $GetStockOut);

    return $GotStockOut;
}

function GetAllStockTransferAll($ProductID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetStockOut = "SELECT * FROM productstock WHERE ProductID = {$ProductID} AND StockType LIKE 'Stock Movement%' ORDER BY StockID DESC";
    $GotStockOut = mysqli_query($ClientCon, $GetStockOut);

    return $GotStockOut;
}

function GetAllStockIn($ProductID, $WarehouseID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetStockIn = "SELECT * FROM productstock WHERE Stock > 0 AND StockType = 'Purchased' AND ProductID = {$ProductID}  AND WarehouseID = {$WarehouseID} ORDER BY StockID DESC";
    $GotStockIn = mysqli_query($ClientCon, $GetStockIn);

    return $GotStockIn;
}

function GetAllStockInAll($ProductID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetStockIn = "SELECT * FROM productstock WHERE Stock > 0 AND StockType = 'Purchased' AND ProductID = {$ProductID} ORDER BY StockID DESC";
    $GotStockIn = mysqli_query($ClientCon, $GetStockIn);

    return $GotStockIn;
}

function AdjustStockLevel($StockLeft, $NewStock, $Difference, $ProductID, $WarehouseID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $DateAdded = date("Y-m-d H:i:s");

    $Description = "Stock Take (" . $NewStock . ")";

    //GET LAST UNIT COST FOR THIS PRODUCT
    $CheckLastUnit = "SELECT UnitCost FROM productstock WHERE ProductID = {$ProductID} AND StockType = 'Purchased' ORDER BY StockID DESC LIMIT 1";
    $GotLastUnit = mysqli_query($ClientCon, $CheckLastUnit);

    $UnitCost = 0;

    while ($Val = mysqli_fetch_array($GotLastUnit)) {
        $UnitCost = $Val["UnitCost"];
    }

    $InsertStock = "INSERT INTO productstock (ProductID, Stock, DateAdded, StockType, UnitCost, SupplierInvoiceID, InvoiceID, WarehouseID) ";
    $InsertStock .= "VALUES ({$ProductID}, {$Difference}, '{$DateAdded}', '{$Description}', {$UnitCost}, 0,0, {$WarehouseID})";
    $DoInsertStock = mysqli_query($ClientCon, $InsertStock);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adjusting the stock count";
    }
}

function MoveStock($QuantityTransfer, $ToWarehouse, $ProductID, $WarehouseID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetFromWarehouseName = "SELECT WarehouseName FROm warehouses WHERE WarehouseID = {$WarehouseID}";
    $GotFromWarehouseName = mysqli_query($ClientCon, $GetFromWarehouseName);

    while ($Val = mysqli_fetch_array($GotFromWarehouseName)) {
        $FromWarehouse = $Val["WarehouseName"];
    }

    $GetToWarehouseName = "SELECT WarehouseName FROm warehouses WHERE WarehouseID = {$ToWarehouse}";
    $GotToWarehouseName = mysqli_query($ClientCon, $GetToWarehouseName);

    while ($Val = mysqli_fetch_array($GotToWarehouseName)) {
        $ToWarehouseName = $Val["WarehouseName"];
    }

    //GET LAST UNIT COST FOR THIS PRODUCT
    $CheckLastUnit = "SELECT UnitCost FROM productstock WHERE ProductID = {$ProductID} AND StockType = 'Purchased' ORDER BY StockID DESC LIMIT 1";
    $GotLastUnit = mysqli_query($ClientCon, $CheckLastUnit);

    $UnitCost = 0;

    while ($Val = mysqli_fetch_array($GotLastUnit)) {
        $UnitCost = $Val["UnitCost"];
    }

    //FISRT STEP, REMOVE STOCK FROM CURRENT LOCATION
    $DateAdded = date("Y-m-d H:i:s");
    $Description = "Stock Movement from " . $FromWarehouse . " to " . $ToWarehouseName;
    $MoveStock = $QuantityTransfer * -1;

    $RemoveStock = "INSERT INTO productstock (ProductID, Stock, DateAdded, StockType, UnitCost, WarehouseID) VALUES ({$ProductID}, {$MoveStock}, '{$DateAdded}', '{$Description}', {$UnitCost}, {$WarehouseID})";
    $DoRemoveStock = mysqli_query($ClientCon, $RemoveStock);
    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        //THEN ADD IT IN OUR NEW LOCATION
        $AddStock = "INSERT INTO productstock (ProductID, Stock, DateAdded, StockType, UnitCost, WarehouseID, MovedFrom) VALUES ({$ProductID}, {$QuantityTransfer}, '{$DateAdded}', '{$Description}', {$UnitCost}, {$ToWarehouse}, {$WarehouseID})";
        $DoAddStock = mysqli_query($ClientCon, $AddStock);
        $Error = mysqli_error($ClientCon);

        if ($Error == "") {
            return "OK";
        } else {
            return "There was an error adding the stock" . $AddStock;
        }
    } else {
        return "There was an error removing the stock";
    }
}

function GetAllCustomerProducts($CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetProducts = "SELECT * FROM customerproducts, customers, products, productcost WHERE customerproducts.CustomerID = customers.CustomerID AND customerproducts.ProductID = products.ProductID AND customerproducts.ProductCostID = productcost.ProductCostID AND customerproducts.CustomerID = {$CustomerID} ORDER BY products.ProductName";
    $GotProducts = mysqli_query($ClientCon, $GetProducts);

    return $GotProducts;

}

function GetAllCustomerPayments($CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetPayments = "SELECT * FROM customertransactions WHERE CustomerID = {$CustomerID} ORDER BY PaymentDate DESC";
    $GotPayments = mysqli_query($ClientCon, $GetPayments);

    return $GotPayments;
}

function GetAllUnpaidInvoices($CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetUnpaid = "SELECT * FROM customerinvoices WHERE InvoiceStatus NOT IN (0,2) AND CustomerID = {$CustomerID}";
    $GotUnpaid = mysqli_query($ClientCon, $GetUnpaid);

    return $GotUnpaid;
}

function GetInvoiceOutstandingAmount($InvoiceID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $InvoiceTotal = GetInvoiceTotal($InvoiceID);

    //THEN CHECK IF ANY PAYMENTS FOR THIS INVOICE
    $CheckPayments = "SELECT SUM(PaymentAmount) AS PaidAmount FROM customerinvoicepayments WHERE InvoiceID = {$InvoiceID}";
    $DoCheckPayments = mysqli_query($ClientCon, $CheckPayments);
    $PaidAmount = 0;

    while ($Val = mysqli_fetch_array($DoCheckPayments)) {
        $PaidAmount = $Val["PaidAmount"];
    }

    $Balance = round($InvoiceTotal, 2) - round($PaidAmount, 2);

    return $Balance;
}

function SaveTransaction($PaymentDate, $PaymentAmount, $Description, $Reference, $PaymentMethod, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisUserID = $_SESSION["ClientID"];
    $ThisEmployeeID = $_SESSION["EmployeeID"];

    if ($ThisEmployeeID == "") {
        //ITS THE MAIN USER ADDING THIS
        $ThisEmployeeID = 0;

    }


    $ThisUser = $_SESSION["ClientName"];

    $AddTransaction = "INSERT INTO customertransactions (CustomerID, PaymentDate, ClientID, EmployeeID, AddedByName, TotalPayment, Description, PaymentMethod, TransactionReference) ";
    $AddTransaction .= "VALUES ({$CustomerID}, '{$PaymentDate}', {$ThisUserID}, {$ThisEmployeeID}, '{$ThisUser}', {$PaymentAmount}, '{$Description}', '{$PaymentMethod}', '{$Reference}')";
    $DoAddTransaction = mysqli_query($ClientCon, $AddTransaction);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return mysqli_insert_id($ClientCon);
    } else {
        return "There was an error adding the transaction";
    }
}

function AddInvoicePayment($InvoiceID, $Amount, $TransactionID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $AddPayment = "INSERT INTO customerinvoicepayments (InvoiceID, TransactionID, PaymentAmount) VALUES ({$InvoiceID}, {$TransactionID}, {$Amount})";
    $DoAddPayment = mysqli_query($ClientCon, $AddPayment);

    //NOW LETS CHECK THE INVOICE AGAIN, IF ALL PAID WE NEED TO MARK IT AS PAID
    $OutStanding = GetInvoiceOutstandingAmount($InvoiceID);

    if ($OutStanding == 0) {
        //MARK IT AS PAID
        $UpdateInvoice = "UPDATE customerinvoices SET InvoiceStatus = 2 WHERE InvoiceID = {$InvoiceID}";
        $DoUpdateInvoice = mysqli_query($ClientCon, $UpdateInvoice);
    } else {
        //MARK IT PARTIALLY PAID
        $UpdateInvoice = "UPDATE customerinvoices SET InvoiceStatus = 6 WHERE InvoiceID = {$InvoiceID}";
        $DoUpdateInvoice = mysqli_query($ClientCon, $UpdateInvoice);
    }

    return "OK";

}


//JOBCARDS
function DeleteJobcard($JobCardID)
{
    if ($_SESSION["MainClient"] == 1) {
        $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

        $DeleteJob = "DELETE FROM jobcards WHERE JobcardID = {$JobCardID}";
        $DoDeleteJob = mysqli_query($ClientCon, $DeleteJob);

        return "OK";
    } else {
        return "You dont have permissions to delete jobcards";
    }
}

function CreateJobcardTable($TableHeading, $ShowLines, $ShowHeading)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $TableHeading = CleanInput($TableHeading);

    //GET THE LAST POSITION
    $GetLastPosition = "SELECT TablePosition FROM jobcardtables ORDER BY TablePosition DESC LIMIT 1";
    $GotLastPosition = mysqli_query($ClientCon, $GetLastPosition);

    while ($Val = mysqli_fetch_array($GotLastPosition)) {
        $TablePosition = $Val["TablePosition"];
    }

    if ($TablePosition == "") {
        $TablePosition = 1;
    } else {
        $TablePosition++;
    }

    //THEN INSERT TABLE
    $InsertTable = "INSERT INTO jobcardtables (TableHeading, TablePosition, ShowHeading, ShowLines) VALUES ('{$TableHeading}', {$TablePosition}, {$ShowHeading}, {$ShowLines})";
    $DoInsertTable = mysqli_query($ClientCon, $InsertTable);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding the new table";
    }
}

function CreateTableField($TableID, $NewField)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $NewField = CleanInput($NewField);

    //FIRST CHECK ITS UNIQUE
    $CheckField = "SELECT * FROM jobcardfields WHERE JobcardTableID = {$TableID} AND FieldName = '{$NewField}'";
    $DoCheckField = mysqli_query($ClientCon, $CheckField);
    $FoundField = mysqli_num_rows($DoCheckField);

    if ($FoundField == 0) {

        //GET THE LAST POSITION
        $GetLastPosition = "SELECT Position FROM jobcardfields WHERE JobcardTableID = {$TableID} ORDER BY Position DESC LIMIT 1";
        $GotLastPosition = mysqli_query($ClientCon, $GetLastPosition);

        while ($Val = mysqli_fetch_array($GotLastPosition)) {
            $FieldPosition = $Val["Position"];
        }

        if ($FieldPosition == "") {
            $FieldPosition = 1;
        } else {
            $FieldPosition++;
        }

        $AddField = "INSERT INTO jobcardfields (JobcardTableID, FieldName, Position) VALUES ({$TableID}, '{$NewField}', {$FieldPosition})";
        $DoAddField = mysqli_query($ClientCon, $AddField);

        $Error = mysqli_error($ClientCon);

        if ($Error == "") {
            return "OK";
        } else {
            return "There was an error adding the new field" . $Error;
        }
    } else {
        return "There is already a field with this name, all fields need to be unique";
    }


}

function GetAllJobcardTables()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetTables = "SELECT * FROM jobcardtables ORDER BY TablePosition ASC";
    $GotTables = mysqli_query($ClientCon, $GetTables);
    echo mysqli_error($ClientCon);

    return $GotTables;

}

function CountJobcardFields($TableID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCount = "SELECT COUNT(JobcardFieldID) AS NumFields FROM jobcardfields WHERE JobcardTableID = {$TableID}";
    $GotCount = mysqli_query($ClientCon, $GetCount);

    while ($Val = mysqli_fetch_array($GotCount)) {
        $NumFields = $Val["NumFields"];
    }

    return $NumFields;
}

function GetJobcardFields($TableID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetFields = "SELECT * FROM 	jobcardfields WHERE JobcardTableID = {$TableID} ORDER BY Position";
    $GotFields = mysqli_query($ClientCon, $GetFields);

    return $GotFields;

}

function GetJobcardFieldsArray($TableID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetFields = "SELECT * FROM 	jobcardfields WHERE JobcardTableID = {$TableID} ORDER BY Position";
    $GotFields = mysqli_query($ClientCon, $GetFields);
    $FieldArray = array();
    $X = 0;

    while ($Val = mysqli_fetch_array($GotFields)) {
        $FieldName = $Val["FieldName"];
        $FieldID = $Val["JobcardFieldID"];

        $FieldArray[$X]["FieldID"] = $FieldID;
        $FieldArray[$X]["FieldName"] = $FieldName;
        $X++;
    }

    return $FieldArray;
}

function CreateJobcardLine($TableID, $SendValues)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    //FIRST CREATE A LINE SO WE CAN GROUP VALUES TOGHETHER
    $NewLine = "INSERT INTO jobcardinputlines (JobcardTableID) VALUES ({$TableID})";
    $DoInsertLine = mysqli_query($ClientCon, $NewLine);

    $NewLineID = mysqli_insert_id($ClientCon);

    if ($NewLineID > 0) {
        $ValuesArray = explode(":::", $SendValues);
        foreach ($ValuesArray as $LineValue) {
            $LineArray = explode("--", $LineValue);
            $ThisValue = $LineArray[0];
            $ThisID = $LineArray[1];

            $ThisValue = CleanInput($ThisValue);

            $InsertLine = "INSERT INTO jobcardinputlinevalues (JobcardTableID, JobcardInputLineID, JobcardFieldID, InputValue) VALUES ({$TableID}, {$NewLineID}, {$ThisID}, '{$ThisValue}')";
            $DoInsertLine = mysqli_query($ClientCon, $InsertLine);
        }

        //RETURN OK
        return "OK";

    } else {
        return "There was an error adding the line group";
    }
}

function GetTableLines($TableID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetLines = "SELECT JobcardInputLineID FROM jobcardinputlines WHERE JobcardTableID = {$TableID}";
    $GotLines = mysqli_query($ClientCon, $GetLines);

    return $GotLines;
}

function GetJobcardLineValue($TableID, $JobcardFieldID, $LineID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetValue = "SELECT InputValue FROM jobcardinputlinevalues WHERE JobcardTableID = {$TableID} AND JobcardInputLineID = {$LineID} AND JobcardFieldID = {$JobcardFieldID}";
    $GotValue = mysqli_query($ClientCon, $GetValue);

    while ($Val = mysqli_fetch_array($GotValue)) {
        $ThisValue = $Val["InputValue"];
    }

    return $ThisValue;
}

//JOBCARD SYSTEM
function GetAllJobcards($Status)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetJobcards = "SELECT * FROM jobcards, customers WHERE JobcardStatus = {$Status} AND jobcards.CustomerID = customers.CustomerID ORDER BY JobcardID DESC";
    $GotJobcards = mysqli_query($ClientCon, $GetJobcards);

    return $GotJobcards;
}

function GetAllJobcardsReport($Status, $FromDate, $ToDate, $FilterClient, $FilterSite)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetJobcards = "SELECT * FROM jobcards, customers WHERE jobcards.CustomerID = customers.CustomerID ";
    if ($Status != "") {
        $GetJobcards .= "AND JobcardStatus = {$Status} ";
    }
    if ($FromDate != "") {
        $GetJobcards .= "AND DateCreated >= '{$FromDate}' ";
    }
    if ($ToDate != "") {
        $GetJobcards .= "AND DateCreated <= '{$ToDate}' ";
    }
    if ($FilterClient != "") {
        $GetJobcards .= "AND jobcards.CustomerID = {$FilterClient} ";
    }
    if ($FilterSite > 0) {
        $GetJobcards .= "AND jobcards.SiteID = {$FilterSite} ";
    }

    $GetJobcards .= "ORDER BY JobcardID DESC";
    $GotJobcards = mysqli_query($ClientCon, $GetJobcards);

    return $GotJobcards;
}

function AddJobcard($Customer, $Employee, $ScheduledDate, $Notes, $Site, $WorkOrder)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $Notes = CleanInput($Notes);
    $WorkOrder = CleanInput($WorkOrder);

    $ThisUserID = $_SESSION["ClientID"];
    $ThisEmployeeID = $_SESSION["EmployeeID"];

    if ($ThisEmployeeID == "") {
        //ITS THE MAIN USER ADDING THIS
        $ThisEmployeeID = 0;

    }

    $ThisUser = $_SESSION["ClientName"];
    $DateAdded = date("Y-m-d");

    $InsertJob = "INSERT INTO jobcards (CustomerID, AssignedTo, AddedByEmployee, AddedBy, AddedByName, DateCreated, DateScheduled, JobcardNotes, SiteID, WorkOrder) ";
    $InsertJob .= "VALUES ({$Customer}, {$Employee}, {$ThisEmployeeID}, {$ThisUserID}, '{$ThisUser}', '{$DateAdded}', '{$ScheduledDate}', '{$Notes}', {$Site}, '{$WorkOrder}')";
    $DoInsertJob = mysqli_query($ClientCon, $InsertJob);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        $JobcardID = mysqli_insert_id($ClientCon);

        return $JobcardID;
    } else {
        return "There was an error creating the jobcard, please check your input and try again" . $Error;
    }


}

function GetJobcard($JobcardID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetJobcard = "SELECT * FROM jobcards, customers WHERE JobcardID = {$JobcardID} AND jobcards.CustomerID = customers.CustomerID ";
    $GotJobcard = mysqli_query($ClientCon, $GetJobcard);


    return $GotJobcard;
}

function UpdateJobcard($Employee, $ScheduledDate, $Notes, $TechNotes, $InvoiceID, $JobcardID, $CurrentStatus, $NewJobcardFile, $ManualJobcardNumber,
                       $TotalTime, $Site, $WorkOrder)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $NewStatus = 0;

    $Notes = CleanInput($Notes);
    $TechNotes = CleanInput($TechNotes);
    $WorkOrder = CleanInput($WorkOrder);

    if ($CurrentStatus == 0) {
        if ($NewJobcardFile != "") {
            $NewStatus = 1;
        }
    } else {
        $NewStatus = $CurrentStatus;
    }

    if ($InvoiceID != "") {
        $NewStatus = 2;
    }

    if ($InvoiceID == "") {
        //WE ARE JUST UPDATING THE JOB
        if ($NewStatus == 1) {
            $TechDate = date("Y-m-d");
            $UpdateJobcard = "UPDATE jobcards SET AssignedTo = {$Employee}, DateScheduled = '{$ScheduledDate}', JobcardNotes = '{$Notes}', TechReport = '{$TechNotes}', DateTechReport = '{$TechDate}', JobcardStatus = {$NewStatus}, ManualJobcardNumber = '{$ManualJobcardNumber}', TotalTime = '{$TotalTime}', SiteID = {$Site}, WorkOrder = '{$WorkOrder}' WHERE JobcardID = {$JobcardID} ";
        } else {
            $UpdateJobcard = "UPDATE jobcards SET AssignedTo = {$Employee}, DateScheduled = '{$ScheduledDate}', JobcardNotes = '{$Notes}', TechReport = '{$TechNotes}', JobcardStatus = {$NewStatus}, ManualJobcardNumber = '{$ManualJobcardNumber}', TotalTime = '{$TotalTime}', WorkOrder = '{$WorkOrder}' WHERE JobcardID = {$JobcardID} ";
        }
    } else {
        if ($CurrentStatus == 1) {
            $InvoiceDateAdded = date("Y-m-d");
            $UpdateJobcard = "UPDATE jobcards SET AssignedTo = {$Employee}, DateScheduled = '{$ScheduledDate}', JobcardNotes = '{$Notes}', TechReport = '{$TechNotes}', JobcardStatus = {$NewStatus}, InvoiceID = {$InvoiceID}, DateInvoice = '{$InvoiceDateAdded}', ManualJobcardNumber = '{$ManualJobcardNumber}', TotalTime = '{$TotalTime}', WorkOrder = '{$WorkOrder}' WHERE JobcardID = {$JobcardID} ";
        } else {
            //JUST AN UPDATE TO ALREADY INVOICED JOB
            $UpdateJobcard = "UPDATE jobcards SET AssignedTo = {$Employee}, DateScheduled = '{$ScheduledDate}', JobcardNotes = '{$Notes}', TechReport = '{$TechNotes}', JobcardStatus = {$NewStatus}, InvoiceID = {$InvoiceID}, ManualJobcardNumber = '{$ManualJobcardNumber}', TotalTime = '{$TotalTime}', WorkOrder = '{$WorkOrder}' WHERE JobcardID = {$JobcardID} ";
        }
    }

    $DoUpdateJobcard = mysqli_query($ClientCon, $UpdateJobcard);
    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the jobcard, please check your values and try again" . $Error;
    }


}

function UpdateJobcardDoc($JobcardID, $NewFileName)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $UpdateJobcard = "UPDATE jobcards SET JobcardFile = '{$NewFileName}' WHERE JobcardID = {$JobcardID} ";
    $DoUpdateJobcard = mysqli_query($ClientCon, $UpdateJobcard);
}

function GetClientJobcards($CustomerID, $SearchText)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    if ($SearchText == "") {
        $GetJobcard = "SELECT * FROM jobcards, customers WHERE jobcards.CustomerID = customers.CustomerID AND jobcards.CustomerID = {$CustomerID}";
        $GotJobcard = mysqli_query($ClientCon, $GetJobcard);
    } else {
        $GetJobcard = "SELECT * FROM jobcards, customers WHERE jobcards.CustomerID = customers.CustomerID AND jobcards.CustomerID = {$CustomerID} AND (TechReport LIKE '%{$SearchText}%' OR JobcardNotes LIKE '%{$SearchText}%') ";
        $GotJobcard = mysqli_query($ClientCon, $GetJobcard);
    }


    return $GotJobcard;
}

function GetCompanySettings()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetSettings = "SELECT * FROM companysettings";
    $GotSettings = mysqli_query($ClientCon, $GetSettings);

    return $GotSettings;
}

function UpdateCompanySettings($CompanyName, $CompanyReg, $VatRegistered, $VatNumber, $VatRate, $ContactNum, $EmailAddress, $FaxNumber, $RecurringDay, $Address1, $Address2, $City, $Region, $PostCode, $Country, $Bank, $AccountHolder, $AccountNumber, $BranchCode, $AccountType, $TermsAndConditions)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $CompanyName = CleanInput($CompanyName);
    $CompanyReg = CleanInput($CompanyReg);
    $VatNumber = CleanInput($VatNumber);
    $VatRate = CleanInput($VatRate);
    $ContactNum = CleanInput($ContactNum);
    $EmailAddress = CleanInput($EmailAddress);
    $FaxNumber = CleanInput($FaxNumber);
    $Address1 = CleanInput($Address1);
    $Address2 = CleanInput($Address2);
    $City = CleanInput($City);
    $Region = CleanInput($Region);
    $PostCode = CleanInput($PostCode);
    $Bank = CleanInput($Bank);
    $AccountHolder = CleanInput($AccountHolder);
    $AccountNumber = CleanInput($AccountNumber);
    $BranchCode = CleanInput($BranchCode);
    $AccountType = CleanInput($AccountType);
    $TermsAndConditions = CleanInput($TermsAndConditions);

    //CHECK IF ITS IN THERE ALREADY
    $CurrentSettings = GetCompanySettings();
    $FoundSettings = mysqli_num_rows($CurrentSettings);

    if ($VatRate == "") {
        $VatRate = 0;
    }

    if ($FoundSettings == 0) //FIRST TIME THEY SETTING IT UP
    {
        //INSERT
        $InsertSettings = "INSERT INTO companysettings (VATRegistered, VATNumber, Address1, Address2, City, Region, PostCode, CountryID, BankName, AccountHolder, AccountNumber, BranchCode, AccountType, VATRate, InvoiceDisplayCompany, InvoiceDisplayEmail, InvoiceDisplayTel, InvoiceDisplayFax, RecurringInvoiceDay, CompanyRegistration, TermsAndConditions) ";
        $InsertSettings .= "VALUES ({$VatRegistered}, '{$VatNumber}', '{$Address1}', '{$Address2}', '{$City}', '{$Region}', '{$PostCode}', {$Country}, '{$Bank}', '{$AccountHolder}', '{$AccountNumber}', '{$BranchCode}', '{$AccountType}', {$VatRate}, '{$CompanyName}', '{$EmailAddress}', '{$ContactNum}', '{$FaxNumber}', {$RecurringDay}, '{$CompanyReg}', '{$TermsAndConditions}')";
        $DoInsertSettings = mysqli_query($ClientCon, $InsertSettings);

    } else {
        while ($Val = mysqli_fetch_array($CurrentSettings)) {
            $SettingsID = $Val["SettingsID"];
        }

        //UPDATE
        $UpdateSettings = "UPDATE companysettings SET VATRegistered = {$VatRegistered}, VATNumber = '{$VatNumber}', Address1 =  '{$Address1}', Address2 =  '{$Address2}', City =  '{$City}', Region = '{$Region}', PostCode = '{$PostCode}', CountryID = {$Country}, BankName =  '{$Bank}', AccountHolder = '{$AccountHolder}', AccountNumber = '{$AccountNumber}', BranchCode = '{$BranchCode}', AccountType = '{$AccountType}', VATRate = {$VatRate}, InvoiceDisplayCompany =  '{$CompanyName}', InvoiceDisplayEmail = '{$EmailAddress}', InvoiceDisplayTel = '{$ContactNum}', InvoiceDisplayFax = '{$FaxNumber}', RecurringInvoiceDay = {$RecurringDay}, CompanyRegistration =  '{$CompanyReg}',  TermsAndConditions =  '{$TermsAndConditions}' WHERE SettingsID = {$SettingsID}";

        $DoUpdateSettings = mysqli_query($ClientCon, $UpdateSettings);
    }

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating your company settings";
    }


}

function AddClientInvoiceLogo($NewFileName)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $UpdateLogo = "UPDATE companysettings SET InvoiceLogo = '{$NewFileName}'";
    $DoUpdateLogo = mysqli_query($ClientCon, $UpdateLogo);

    return "OK";
}

//WAREHOUSES
function GetAllWarehouses()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetWarehouses = "SELECT * FROM warehouses ORDER BY WarehouseID ASC";
    $GotWarehouses = mysqli_query($ClientCon, $GetWarehouses);

    return $GotWarehouses;
}

function GetWarehouseArray($WarehouseID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetWarehouses = "SELECT * FROM warehouses WHERE WarehouseID != {$WarehouseID} ORDER BY WarehouseID ASC";
    $GotWarehouses = mysqli_query($ClientCon, $GetWarehouses);

    $WarehouseArray = array();

    $X = 0;
    while ($Val = mysqli_fetch_array($GotWarehouses)) {
        $WarehouseID = $Val["WarehouseID"];
        $WarehouseName = $Val["WarehouseName"];

        $WarehouseArray[$X]["WarehouseID"] = $WarehouseID;
        $WarehouseArray[$X]["WarehouseName"] = $WarehouseName;
        $X++;
    }

    return $WarehouseArray;
}

function UpdateWarehouseName($WarehouseID, $NewName)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $NewName = CleanInput($NewName);

    $UpdateWarehouse = "UPDATE warehouses SET WarehouseName = '{$NewName}' WHERE WarehouseID = {$WarehouseID}";
    $DoUpdateWarehouse = mysqli_query($ClientCon, $UpdateWarehouse);

    $Error = mysqli_error($ClientCon);
    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the warehouse name";
    }
}

function AddWarehouse($NewName)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $NewName = CleanInput($NewName);

    $AddWarehouse = "INSERT INTO warehouses (WarehouseName) VALUES ('{$NewName}')";
    $DoAddWarehouse = mysqli_query($ClientCon, $AddWarehouse);

    $Error = mysqli_error($ClientCon);
    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the warehouse name";
    }
}

function GetJobcardInvoiceNumber($InvoiceID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetInvNumber = "SELECT InvoiceNumber FROM customerinvoices WHERE InvoiceID = {$InvoiceID}";
    $GotInvNumber = mysqli_query($ClientCon, $GetInvNumber);

    while ($Val = mysqli_fetch_array($GotInvNumber)) {
        $InvoiceNumber = $Val["InvoiceNumber"];
    }

    return $InvoiceNumber;
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

function GetCustomerStatement($FromDate, $ToDate, $CustomerID, $statusPass = ' IN (1,2,6) ')
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    /*
	//GET ALL INVOICES IN DATE RANGE
	$GetInvoices = "SELECT SUM(LineTotal) AS TotalDebits, customerinvoices.* FROM customerinvoices, customerinvoicelines WHERE customerinvoicelines.InvoiceID = customerinvoices.InvoiceID AND customerinvoices.CustomerID = {$CustomerID}  AND InvoiceDate >= '{$FromDate}' AND InvoiceDate <= '{$ToDate}' AND InvoiceStatus != 0 AND InvoiceStatus != 3 GROUP BY InvoiceID ORDER BY customerinvoices.InvoiceID ASC"; //REMOVING DRAFT AND CANCELLED

	$GotInvoices = mysqli_query($ClientCon, $GetInvoices);


	//GET ALL PAYMENTS IN DATE RANGE
	$GetPayments = "SELECT * FROM customertransactions WHERE PaymentDate >= '{$FromDate}' AND PaymentDate <= '{$ToDate}' AND CustomerID = {$CustomerID}";
	$GotPayments = mysqli_query($ClientCon, $GetPayments);
	$NumPaymets = mysqli_num_rows($GotPayments);


	$X = 0;

	//NOW WE NEED TO CREATE AN ARRAY OF THE INFORMATION SO WE CAN ORDER IT
	while ($Val = mysqli_fetch_array($GotInvoices))
	{
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


	while ($Val = mysqli_fetch_array($GotPayments))
	{
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





	function date_compare($a, $b)
	{
		$t2 = strtotime($a["Date"]);
		$t1 = strtotime($b["Date"]);
		return $t2 - $t1;
	}

	if ($NumPaymets > 0)
	{
		@usort($StatmentArray, 'date_compare');
	}

	return $StatmentArray; */
    $X = 0;

    while (strtotime($FromDate) <= strtotime($ToDate)) {
        //FIRST GET INVOICES
        $GetInvoices = "SELECT SUM(LineTotal) AS TotalDebits, customerinvoices.* FROM customerinvoices,
	   customerinvoicelines WHERE customerinvoicelines.InvoiceID = customerinvoices.InvoiceID
	   AND customerinvoices.CustomerID = {$CustomerID}  AND InvoiceDate = '{$FromDate}'
	   AND InvoiceStatus {$statusPass} GROUP BY InvoiceID ORDER BY customerinvoices.InvoiceID ASC";

   // print("<br />");
        $GotInvoices = mysqli_query($ClientCon, $GetInvoices);

        while ($Val = mysqli_fetch_array($GotInvoices)) {

            if (!empty($Val)) {
                $StatmentArray[$X] = array();
            $InvoiceDate = $Val["InvoiceDate"];
            $InvoiceNumber = $Val["InvoiceNumber"];
            $Description = "Invoice " . $InvoiceNumber;
            $InvoiceAmount = $Val["TotalDebits"];
            $InvoiceID = $Val["InvoiceID"];


            $StatmentArray[$X]["Date"] = $InvoiceDate;
            $StatmentArray[$X]["Reference"] = $InvoiceNumber;
            $StatmentArray[$X]["Description"] = $Description;
            if($Val['InvoiceStatus']=='2' || $Val['InvoiceStatus']=='6'){

               $GetPayments = "SELECT SUM(PaymentAmount) AS TotalCredits FROM customerinvoicepayments WHERE  InvoiceID = {$InvoiceID} group by InvoiceID";

                $DoCheckPayments = mysqli_query($ClientCon, $GetPayments);
                $Val = mysqli_fetch_assoc($DoCheckPayments);

                $StatmentArray[$X]["Credit"] = $Val['TotalCredits'];
                $StatmentArray[$X]["Debit"] = $InvoiceAmount;
            }else{
                $StatmentArray[$X]["Debit"] = $InvoiceAmount;
                $StatmentArray[$X]["Credit"] ="";
            }

            $StatmentArray[$X]["InvoiceStatus"] = $Val['InvoiceStatus'];
            $StatmentArray[$X]["InvoiceID"] = $InvoiceID;
            $StatmentArray[$X]["TransactionDetails"] = GetInvoiceOutstandingAmount($InvoiceID);

            $X++;
            }
        }

       

        //THEN PAYMENTS
        //NOW PAYMENTS
        $GetPayments = "SELECT SUM(TotalPayment) AS TotalCredits FROM customertransactions WHERE PaymentDate = '{$FromDate}' AND CustomerID = {$CustomerID} group by TransactionID";
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

    //print_r("<pre>");
    //print_r($StatmentArray);
    //exit;
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

function SendCustomerStatement($CustomerID, $FromDate, $ToDate)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCustomer = "SELECT * FROM customers, countries WHERE CustomerID = {$CustomerID} AND customers.CountryID = countries.CountryID";
    $GotCustomer = mysqli_query($ClientCon, $GetCustomer);

    $AddLog = AddLogInformation('Sent customer statement from ' . $FromDate . " to " . $ToDate, 'Statement', $CustomerID);

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
        $DepositReference = $Val['DepositReference'];

        $creadit_amount = $Val["creadit_amount"];

        if ($VatNumber == "") {
            $VatNumber = 'None';
        }
        if ($DepositReference == "") {
            $DepositReference = 'None';
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
    $data[] = array('<b>Customer Details</b>' => 'Deposit Reference : ' . $DepositReference, '<b>Our Details</b>' => '', '<b>Banking Details</b>' => 'Account Type : ' . $AccountType);
    $data[] = array('<b>Customer Details</b>' => '<b>Address</b>', '<b>Our Details</b>' => '<b>Address</b>', '<b>Banking Details</b>' => '');
    $data[] = array('<b>Customer Details</b>' => $CustomerAddress1, '<b>Our Details</b>' => $Address1, '<b>Banking Details</b>' => '');
    $data[] = array('<b>Customer Details</b>' => $CustomerAddress2, '<b>Our Details</b>' => $Address2, '<b>Banking Details</b>' => '');
    $data[] = array('<b>Customer Details</b>' => $CustomerCity, '<b>Our Details</b>' => $City, '<b>Banking Details</b>' => '');
    $data[] = array('<b>Customer Details</b>' => $CustomerRegion, '<b>Our Details</b>' => $Region, '<b>Banking Details</b>' => '');
    $data[] = array('<b>Customer Details</b>' => $CustomerCountryName, '<b>Our Details</b>' => $CountryName, '<b>Banking Details</b>' => '');
    $data[] = array('<b>Customer Details</b>' => $CustomerPostCode, '<b>Our Details</b>' => $PostCode, '<b>Banking Details</b>' => '');


    $pdf->ezTable($data, '', '', array('shaded' => 0, 'fontSize' => 8, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'showLines' => 0, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));


    $pdf->ezSetDy(-20);


    $pdf->ezText("Statement " . $FromDate . " - " . $ToDate, 10, array('aleft' => 20));

    $pdf->ezSetDy(-10);


    $OpeningBalance = GetCustomerOpeningStatement($FromDate, $ToDate, $CustomerID);
    //NOW WE NEED TO GET DEBITS AND CREDITS - THIS IS AN ARRAY
    $CustomerStatementArray = GetCustomerStatement($FromDate, $ToDate, $CustomerID, ' Not IN (0,3) ');


    $data = array();

    if ($OpeningBalance >= 0) {
        $DebitTotal = $DebitTotal + $OpeningBalance;
        $data[] = array('<b>Date</b>' => $FromDate, '<b>Reference</b>' => 'OB', '<b>Description</b>' => 'Opening Balance', '<b>Debit</b>' => 'R' . number_format($OpeningBalance, 2), '<b>Credit</b>' => '');
    } else {
        $OpeningBalance = $OpeningBalance * -1;
        $CreditTotal = $CreditTotal + $OpeningBalance;
        $data[] = array('<b>Date</b>' => $FromDate, '<b>Reference</b>' => 'OB', '<b>Description</b>' => 'Opening Balance', '<b>Debit</b>' => '', '<b>Credit</b>' => 'R' . number_format($OpeningBalance, 2));
    }
    $InvoiceIDs = array();
    foreach ($CustomerStatementArray as $TransactionLine) {
        $ThisDate = $TransactionLine["Date"];
        $ThisReference = $TransactionLine["Reference"];
        $ThisDescription = $TransactionLine["Description"];
        $ThisCredit = $TransactionLine["Credit"];
        $ThisDebit = $TransactionLine["Debit"];
        if (!empty($TransactionLine["InvoiceID"]))
            $InvoiceIDs[] = $TransactionLine["InvoiceID"];

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

    if (!empty($InvoiceIDs)) {
        $strInvoiceID = implode(",", $InvoiceIDs);
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
    $AccountBalance = $DebitTotal - $allCreaditTotal - $creadit_amount;
    if ($AccountBalance > 0) {
        $data[] = array('<b>Date</b>' => '', '<b>Reference</b>' => '', '<b>Description</b>' => '<b>Closing balance on ' . $ToDate . '</b>', '<b>Debit</b>' => '<b>R' . number_format($AccountBalance, 2) . '</b>', '<b>Credit</b>' => '');
    } else {
        $AccountBalance = $AccountBalance * -1;
        $data[] = array('<b>Date</b>' => '', '<b>Reference</b>' => '', '<b>Description</b>' => '<b>Closing balance on ' . $ToDate . '</b>', '<b>Debit</b>' => '', '<b>Credit</b>' => '<b>R' . number_format($AccountBalance, 2) . '</b>');
    }


    $pdf->ezTable($data, '', '', array('shaded' => 1, 'fontSize' => 8, 'xPos' => 580, 'xOrientation' => 'left', 'width' => 550, 'cols' => array(
        '<b>QTY</b>' => array('width' => 40)
    , '<b>Product</b>' => array('width' => 250), '<b>Rate</b>' => array('justification' => 'right'), '<b>VAT Amt</b>' => array('justification' => 'right'), '<b>Amount</b>' => array('justification' => 'right'))));

    $pdf->ezSetDy(-20);

    $pdfcode = $pdf->output();

    //EMAIL MESSAGE
    $SupplierMail = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					<title>Untitled Document</title>
					</head>
					<body>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family: Arial, Helvetica, sans-serif">
						  
						  <tr>
							<td  style="font-family: Arial, Helvetica, sans-serif; font-size: 14px" valign="top">
							Dear ' . $CompanyName . '<br>
							  <br>
							  Kindly find attached your statement dated ' . $FromDate . ' - ' . $ToDate . '. Please dont hesitate to contact us should you require any further information.
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
    $data = chunk_split(base64_encode($pdfcode));
    $message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$files[$x]\"\n" .
        "Content-Disposition: attachment;\n" . " filename=\"CustomerStatement.pdf\"\n" .
        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
    $message .= "--{$mime_boundary}\n";


    ///$ok = @mail('tanshu321@gmail.com', "Customer Statement", $message, $headers);
    $ok = @mail($EmailAddress, "Customer Statement", $message, $headers);


    return "OK";
}


//NEW STOCK TAKES
function CheckLastStockTake($WarehouseID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $CheckStockTake = "SELECT * FROM stocktakes WHERE WarehouseID = {$WarehouseID} ORDER BY StockTakeID DESC LIMIT 1";
    $DoCheckStockTake = mysqli_query($ClientCon, $CheckStockTake);
    $FoundStockTake = mysqli_num_rows($DoCheckStockTake);

    if ($FoundStockTake > 0) {
        while ($Val = mysqli_fetch_array($DoCheckStockTake)) {
            $StockTakeStatus = $Val["StockTakeStatus"];
        }

        return $StockTakeStatus;
    } else {
        return 1;
    }
}

function CreateStockTake($WarehouseID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $DateAdded = date("Y-m-d H:i:s");

    $InsertStockTake = "INSERT INTO stocktakes (WarehouseID, StockTakeDate) VALUES ({$WarehouseID}, '{$DateAdded}')";
    $DoInsertStockTake = mysqli_query($ClientCon, $InsertStockTake);
    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding the stock take";
    }
}

function GetStockTakeDetails($WarehouseID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $CheckStockTake = "SELECT * FROM stocktakes, warehouses WHERE stocktakes.WarehouseID = {$WarehouseID} AND stocktakes.WarehouseID = warehouses.WarehouseID ORDER BY StockTakeID DESC LIMIT 1";
    $DoCheckStockTake = mysqli_query($ClientCon, $CheckStockTake);


    return $DoCheckStockTake;
}

function GetStockInDated($ProductID, $WarehouseID, $StockTakeDate)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetStockIn = "SELECT SUM(Stock) AS StockIn FROM productstock WHERE Stock > 0 AND ProductID = {$ProductID} AND WarehouseID = {$WarehouseID} AND DateAdded <= '{$StockTakeDate}'";
    $GotStockIn = mysqli_query($ClientCon, $GetStockIn);

    while ($Val = mysqli_fetch_array($GotStockIn)) {
        $StockIn = $Val["StockIn"];
    }

    return $StockIn;

}

function GetStockOutDated($ProductID, $WarehouseID, $StockTakeDate)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetStockOut = "SELECT SUM(Stock) AS StockOut FROM productstock WHERE Stock < 0 AND ProductID = {$ProductID} AND WarehouseID = {$WarehouseID} AND DateAdded <= '{$StockTakeDate}'";
    $GotStockOut = mysqli_query($ClientCon, $GetStockOut);

    while ($Val = mysqli_fetch_array($GotStockOut)) {
        $StockOut = $Val["StockOut"];
    }

    return $StockOut;
}

function AdjustStockLevelStockTake($StockLeft, $NewStock, $Difference, $ProductID, $WarehouseID, $StockTakeID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $DateAdded = date("Y-m-d H:i:s");

    $Description = "Stock Take (" . $NewStock . ")";

    //GET LAST UNIT COST FOR THIS PRODUCT
    $CheckLastUnit = "SELECT UnitCost FROM productstock WHERE ProductID = {$ProductID} AND StockType = 'Purchased' ORDER BY StockID DESC LIMIT 1";
    $GotLastUnit = mysqli_query($ClientCon, $CheckLastUnit);

    $UnitCost = 0;

    while ($Val = mysqli_fetch_array($GotLastUnit)) {
        $UnitCost = $Val["UnitCost"];
    }

    $InsertStock = "INSERT INTO productstock (ProductID, Stock, DateAdded, StockType, UnitCost, SupplierInvoiceID, InvoiceID, WarehouseID, StockTakeID) ";
    $InsertStock .= "VALUES ({$ProductID}, {$Difference}, '{$DateAdded}', '{$Description}', {$UnitCost}, 0,0, {$WarehouseID}, {$StockTakeID})";
    $DoInsertStock = mysqli_query($ClientCon, $InsertStock);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adjusting the stock count";
    }
}

function CompleteStockTake($StockTakeID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $Completed = date("Y-m-d H:i:s");

    $UpdateStockTake = "UPDATE stocktakes SET StockTakeStatus = 1, StockTakeCompleted = '{$Completed}' WHERE StockTakeID = {$StockTakeID}";
    $DoUpdateStockTake = mysqli_query($ClientCon, $UpdateStockTake);

    return "OK";
}


//INVOICE GROUPING FUNCTIONS
function AddInvoiceGroup($InvoiceID, $GroupName)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GroupName = CleanInput($GroupName);

    //THEN CREATE THE GROUP
    $AddGroup = "INSERT INTO customerinvoicegroups (InvoiceID, GroupName) VALUES ({$InvoiceID}, '{$GroupName}')";
    $DoAddGroup = mysqli_query($ClientCon, $AddGroup);
    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return mysqli_insert_id($ClientCon);
    } else {
        return "There was an error adding the group";
    }
}

function AssignGroupLine($GroupID, $LineItemID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $AssignGroup = "UPDATE customerinvoicelines SET GroupID = {$GroupID} WHERE InvoiceLineItemID = {$LineItemID}";
    $DoAssignGroup = mysqli_query($ClientCon, $AssignGroup);

    return "OK";
}

function GetInvoiceGroups($InvoiceID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetGroups = "SELECT * FROM customerinvoicegroups WHERE InvoiceID = {$InvoiceID} AND InvoiceGroupID IN (SELECT GroupID FROM customerinvoicelines WHERE InvoiceID = {$InvoiceID})";

    $GotGroups = mysqli_query($ClientCon, $GetGroups);
    echo mysqli_error($ClientCon);

    return $GotGroups;
}

function GetInvoiceGroupSub($InvoiceID, $InvoiceGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetSubCost = "SELECT SUM(LineSubTotal) AS SubTotal FROm customerinvoicelines WHERE InvoiceID = {$InvoiceID} AND GroupID = {$InvoiceGroupID}";
    $GotSubCost = mysqli_query($ClientCon, $GetSubCost);

    while ($Val = mysqli_fetch_array($GotSubCost)) {
        $SubTotal = $Val["SubTotal"];
    }

    return $SubTotal;
}

function GetInvoiceGroupDiscount($InvoiceID, $InvoiceGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetSubCost = "SELECT SUM(LineDiscount) AS DiscountTotal FROm customerinvoicelines WHERE InvoiceID = {$InvoiceID} AND GroupID = {$InvoiceGroupID}";
    $GotSubCost = mysqli_query($ClientCon, $GetSubCost);

    while ($Val = mysqli_fetch_array($GotSubCost)) {
        $DiscountTotal = $Val["DiscountTotal"];
    }

    return $DiscountTotal;
}

function GetInvoiceGroupVat($InvoiceID, $InvoiceGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetSubCost = "SELECT SUM(LineVAT) AS VATTotal FROm customerinvoicelines WHERE InvoiceID = {$InvoiceID} AND GroupID = {$InvoiceGroupID}";
    $GotSubCost = mysqli_query($ClientCon, $GetSubCost);

    while ($Val = mysqli_fetch_array($GotSubCost)) {
        $VATTotal = $Val["VATTotal"];
    }

    return $VATTotal;
}

function GetInvoiceGroupLineTotal($InvoiceID, $InvoiceGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetSubCost = "SELECT SUM(LineTotal) AS LineTotal FROm customerinvoicelines WHERE InvoiceID = {$InvoiceID} AND GroupID = {$InvoiceGroupID}";
    $GotSubCost = mysqli_query($ClientCon, $GetSubCost);

    while ($Val = mysqli_fetch_array($GotSubCost)) {
        $LineTotal = $Val["LineTotal"];
    }

    return $LineTotal;
}

function GetInvoiceGroupLineProfit($InvoiceID, $InvoiceGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetSubCost = "SELECT SUM(Profit) AS LineProfit FROm customerinvoicelines WHERE InvoiceID = {$InvoiceID} AND GroupID = {$InvoiceGroupID}";
    $GotSubCost = mysqli_query($ClientCon, $GetSubCost);

    while ($Val = mysqli_fetch_array($GotSubCost)) {
        $LineProfit = $Val["LineProfit"];
    }

    return $LineProfit;
}

function GetInvoiceGroupPrice($InvoiceID, $InvoiceGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetSubCost = "SELECT SUM(Price) AS PriceTotal FROm customerinvoicelines WHERE InvoiceID = {$InvoiceID} AND GroupID = {$InvoiceGroupID}";
    $GotSubCost = mysqli_query($ClientCon, $GetSubCost);

    while ($Val = mysqli_fetch_array($GotSubCost)) {
        $PriceTotal = $Val["PriceTotal"];
    }

    return $PriceTotal;
}

function GetGroupItems($InvoiceID, $InvoiceGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetGroupItems = "SELECT * FROM customerinvoicelines WHERE GroupID = {$InvoiceGroupID} AND InvoiceID = {$InvoiceID}";
    $GotGroupItems = mysqli_query($ClientCon, $GetGroupItems);

    return $GotGroupItems;
}

function RemoveItemGroup($InvoiceID, $InvoiceLineItemID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $UpdateLine = "UPDATE customerinvoicelines SET GroupID = 0 WHERE InvoiceID = {$InvoiceID} AND InvoiceLineItemID = {$InvoiceLineItemID}";
    $DoUpdateLine = mysqli_query($ClientCon, $UpdateLine);

    return "OK";
}

function HasInvoiceLines($InvoiceID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $CheckLines = "SELECT COUNT(InvoiceLineItemID) AS NumLines FROM customerinvoicelines WHERE InvoiceID = {$InvoiceID}";
    $DoCheckLines = mysqli_query($ClientCon, $CheckLines);

    $NumLines = 0;

    while ($Val = mysqli_fetch_array($DoCheckLines)) {
        $NumLines = $Val["NumLines"];
    }

    return $NumLines;
}


//CUSTOMER OVERDUE REPORT
function GetAllCustomersOutstandingInvoices()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $Today = date("Y-m-d");

    $GetOutstanding = "SELECT * FROM customers WHERE CustomerID IN (SELECT customerinvoices.CustomerID FROM customerinvoices WHERE InvoiceStatus IN (1,6)) ORDER BY  CompanyName";
    $GotOutstanding = mysqli_query($ClientCon, $GetOutstanding);

    return $GotOutstanding;
}

function GetAllCustomersOutstanding()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $Today = date("Y-m-d");

    $GetOutstanding = "SELECT * FROM customers WHERE CustomerID IN (SELECT customerinvoices.CustomerID FROM customerinvoices WHERE InvoiceStatus IN (1,6) AND DueDate <= '{$Today}') ORDER BY FirstName, Surname, CompanyName";
    $GotOutstanding = mysqli_query($ClientCon, $GetOutstanding);

    return $GotOutstanding;
}

function GetNumberOutstandingInvoices($CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $Today = date("Y-m-d");

    $GetOutstanding = "SELECT COUNT(InvoiceID) AS NumOutstanding FROM customerinvoices WHERE InvoiceStatus IN (1,6) AND DueDate <= '{$Today}' AND CustomerID = {$CustomerID}";
    $GotOutstanding = mysqli_query($ClientCon, $GetOutstanding);
    echo mysqli_error($ClientCon);

    while ($Val = mysqli_fetch_array($GotOutstanding)) {
        $NumOutstanding = $Val["NumOutstanding"];
    }

    return $NumOutstanding;
}

function GetNumberOutstandingAmount($CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $Today = date("Y-m-d");

    $GetOutstanding = "SELECT SUM(LineTotal) AS TotalOutstanding FROM customerinvoicelines WHERE InvoiceID IN (SELECT InvoiceID FROM customerinvoices WHERE InvoiceStatus = 1 AND DueDate <= '{$Today}' AND CustomerID = {$CustomerID})";
    $GotOutstanding = mysqli_query($ClientCon, $GetOutstanding);


    while ($Val = mysqli_fetch_array($GotOutstanding)) {
        $TotalOutstanding = $Val["TotalOutstanding"];
    }

    //NOW GET ANY PARTIAL PAID
    $GetOutstanding = "SELECT * FROM customerinvoices WHERE InvoiceStatus = 6 AND DueDate <= '{$Today}' AND CustomerID = {$CustomerID}";
    $GotOutstanding = mysqli_query($ClientCon, $GetOutstanding);
    echo mysqli_error($ClientCon);

    while ($Val = mysqli_fetch_array($GotOutstanding)) {
        $InvoiceID = $Val["InvoiceID"];

        $GetInvoiceTotal = "SELECT SUM(LineTotal) AS InvoiceTotal FROM customerinvoicelines WHERE InvoiceID = {$InvoiceID}";
        $GotInvoiceTotal = mysqli_query($ClientCon, $GetInvoiceTotal);
        echo mysqli_error($ClientCon);

        while ($InvoiceVal = mysqli_fetch_array($GotInvoiceTotal)) {
            $InvoiceTotal = $InvoiceVal["InvoiceTotal"];
        }

        $GetPaidAmount = "SELECT SUM(PaymentAmount) AS InvoicePayments FROM customerinvoicepayments WHERE InvoiceID = {$InvoiceID}";
        $GotPaidAmount = mysqli_query($ClientCon, $GetPaidAmount);
        echo mysqli_error($ClientCon);

        while ($ValPartial = mysqli_fetch_array($GotPaidAmount)) {
            $PaidAmount = $ValPartial["InvoicePayments"];
        }

        $Owing = $InvoiceTotal - $PaidAmount;


        $TotalOutstanding = $TotalOutstanding + $Owing;
    }


    return $TotalOutstanding;
}

function GetCustomerOutstandingDetails($CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $Today = date("Y-m-d");

    $GetOutstanding = "SELECT customerinvoices.*, SUM(LineTotal) AS TotalOutstanding FROM customerinvoices, customerinvoicelines WHERE customerinvoices.CustomerID = {$CustomerID} AND customerinvoicelines.InvoiceID = customerinvoices.InvoiceID AND DueDate <= '{$Today}' AND InvoiceStatus  IN (1,6) GROUP BY customerinvoicelines.InvoiceID";
    $GotOutstanding = mysqli_query($ClientCon, $GetOutstanding);

    $OutstandingArray = '';
    $X = 0;

    while ($Val = mysqli_fetch_array($GotOutstanding)) {
        $ThisInv = $Val["InvoiceNumber"];
        $ThisDue = $Val["DueDate"];
        $ThisOutstanding = $Val["TotalOutstanding"];
        $ThisCreated = $Val["InvoiceDate"];

        $ThisStatus = $Val["InvoiceStatus"];
        $ThisInvoiceID = $Val["InvoiceID"];

        if ($ThisStatus == 6) {
            //PARTIALLY PAID INVOICE, LETS SEE HOW MUCH THEY OWE ON IT
            $GetPaidAmount = "SELECT SUM(PaymentAmount) AS InvoicePayments FROM customerinvoicepayments WHERE InvoiceID = {$ThisInvoiceID}";
            $GotPaidAmount = mysqli_query($ClientCon, $GetPaidAmount);
            echo mysqli_error($ClientCon);

            while ($ValPartial = mysqli_fetch_array($GotPaidAmount)) {
                $PaidAmount = $ValPartial["InvoicePayments"];
            }

            $ThisOutstanding = $ThisOutstanding - $PaidAmount;
        }

        $OutstandingArray[$X]["Invoice"] = $ThisInv;
        $OutstandingArray[$X]["InvoiceDate"] = $ThisCreated;
        $OutstandingArray[$X]["DueDate"] = $ThisDue;
        $OutstandingArray[$X]["Outstanding"] = "R " . number_format($ThisOutstanding, 2);

        $X++;
    }

    return $OutstandingArray;

}

//CUSTOMER STATEMENT REPORT
function GetCustomerStatementReport($FromDate, $ToDate, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    //GET ALL INVOICES IN DATE RANGE
    /*$GetInvoices = "SELECT SUM(LineTotal) AS TotalDebits, customerinvoices.* FROM customerinvoices, customerinvoicelines WHERE customerinvoicelines.InvoiceID = customerinvoices.InvoiceID AND customerinvoices.CustomerID = {$CustomerID}  AND InvoiceDate >= '{$FromDate}' AND InvoiceDate <= '{$ToDate}' AND InvoiceStatus != 0 GROUP BY InvoiceID";*/

    $GetInvoices = "SELECT SUM(LineTotal) AS TotalDebits, customerinvoices.* FROM customerinvoices, customerinvoicelines WHERE customerinvoicelines.InvoiceID = customerinvoices.InvoiceID AND customerinvoices.CustomerID = {$CustomerID}  AND InvoiceDate >= '{$FromDate}' AND InvoiceDate <= '{$ToDate}'
	AND InvoiceStatus IN (1,2,6) GROUP BY InvoiceID";

    $GotInvoices = mysqli_query($ClientCon, $GetInvoices);


    //GET ALL PAYMENTS IN DATE RANGE
    $GetPayments = "SELECT * FROM customertransactions WHERE PaymentDate >= '{$FromDate}' AND PaymentDate <= '{$ToDate}' AND CustomerID = {$CustomerID}";
    $GotPayments = mysqli_query($ClientCon, $GetPayments);


    $X = 0;
    $TotalDebit = 0;
    $TotalCredit = 0;

    //NOW WE NEED TO CREATE AN ARRAY OF THE INFORMATION SO WE CAN ORDER IT
    while ($Val = mysqli_fetch_array($GotInvoices)) {
        $InvoiceDate = $Val["InvoiceDate"];
        $InvoiceNumber = $Val["InvoiceNumber"];
        $Description = "Invoice " . $InvoiceNumber;
        $InvoiceAmount = $Val["TotalDebits"];

        $TotalDebit = $TotalDebit + $InvoiceAmount;
    }

    while ($Val = mysqli_fetch_array($GotPayments)) {
        $PaymentDate = $Val["PaymentDate"];
        $PaymentRef = $Val["TransactionReference"];
        $Description = $Val["Description"];
        $PaymentAmount = $Val["TotalPayment"];

        $TotalCredit = $TotalCredit + $PaymentAmount;

        $X++;


    }

    $ReturnArray[0]["Debit"] = $TotalDebit;
    $ReturnArray[0]["Credit"] = $TotalCredit;

    return $ReturnArray;


}


//STOCK TAKE REPORTING
function GetAllStockTakes()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetStockTakes = "SELECT * FROM stocktakes ORDER BY StockTakeID DESC";
    $GotStockTakes = mysqli_query($ClientCon, $GetStockTakes);

    return $GotStockTakes;
}

function GetWarehouseName($WarehouseID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetWarehouse = "SELECT * FROM 	warehouses WHERE WarehouseID = {$WarehouseID}";
    $GotWarehouse = mysqli_query($ClientCon, $GetWarehouse);

    while ($Val = mysqli_fetch_array($GotWarehouse)) {
        $WarehouseName = $Val["WarehouseName"];
    }

    return $WarehouseName;
}

function GetStockVariances($StockTakeID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetVariances = "SELECT * FROM productstock WHERE StockTakeID = {$StockTakeID}";
    $GotVariances = mysqli_query($ClientCon, $GetVariances);

    return $GotVariances;
}

function GetStockTakeVariances($StockTakeID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetVariances = "SELECT * FROM productstock, products WHERE StockTakeID = {$StockTakeID} AND products.ProductID = productstock.ProductID";
    $GotVariances = mysqli_query($ClientCon, $GetVariances);

    $Variance = '';

    $X = 0;

    while ($Val = mysqli_fetch_array($GotVariances)) {
        $ProductName = $Val["ProductName"];
        $StockVariance = $Val["Stock"];
        $UnitCost = $Val["UnitCost"];
        if ($UnitCost != 0) {
            $EstimatedLoss = $UnitCost * $StockVariance;
            $EstimatedLoss = "R " . number_format($EstimatedLoss, 2);
        } else {
            $EstimatedLoss = 'NA';
        }

        $Variance[$X]["Product"] = $ProductName;
        $Variance[$X]["StockVariance"] = $StockVariance;
        $Variance[$X]["UnitCost"] = $UnitCost;
        $Variance[$X]["EstimatedLoss"] = $EstimatedLoss;

        $X++;

    }

    return $Variance;
}

function GetQuoteBillingType($QuoteID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetBillingTypes = "SELECT DISTINCT(BillingType) FROM customerquotelines WHERE QuoteID = {$QuoteID}";
    $GotBillingTypes = mysqli_query($ClientCon, $GetBillingTypes);


    return $GotBillingTypes;
}

function CheckProRata($ProductCostID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $CheckProRata = "SELECT ProRataBilling FROM productcost WHERE ProductCostID = {$ProductCostID}";
    $DoCheckProRata = mysqli_query($ClientCon, $CheckProRata);

    while ($Val = mysqli_fetch_array($DoCheckProRata)) {
        $ProRata = $Val["ProRataBilling"];
    }

    return $ProRata;
}

function CheckStockItem($ProductID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $CheckStock = "SELECT IsStockItem FROM products WHERE ProductID = {$ProductID}";
    $DoCheckStock = mysqli_query($ClientCon, $CheckStock);

    while ($Val = mysqli_fetch_array($DoCheckStock)) {
        $IsStockItem = $Val["IsStockItem"];
    }

    return $IsStockItem;
}

function AddInvoiceQuoteItem($ThisQuoteLineID, $ThisWarehouseID, $ThisRecurring, $ThisBillProRata, $ThisBillFull, $ThisBillNext, $InvoiceID, $CustomerID, $NextRun)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    //CHECK COMPANY SETTINGS FOR VAT REGISTER
    $CheckClientSettings = "SELECT * FROM customers, countries WHERE customers.CountryID = countries.CountryID AND
	CustomerID = {$CustomerID}";
    $DoCheckClientSettings = mysqli_query($ClientCon, $CheckClientSettings);


    while ($Val = mysqli_fetch_array($DoCheckClientSettings)) {
        $VATRegistered = $Val["TaxExempt"];
    }

    $ThisUserID = $_SESSION["ClientID"];
    $ThisEmployeeID = $_SESSION["EmployeeID"];

    if ($ThisEmployeeID == "") {
        //ITS THE MAIN USER ADDING THIS
        $ThisEmployeeID = 0;

    }

    $ThisUser = $_SESSION["ClientName"];
    $DateAdded = date("Y-m-d");

    //FIRST LETS GET THE QUOTE ITEM
    $GetQuoteLine = "SELECT * FROM customerquotelines WHERE QuoteLineItemID = {$ThisQuoteLineID}";
    $GotQuoteLine = mysqli_query($ClientCon, $GetQuoteLine);

    while ($Val = mysqli_fetch_array($GotQuoteLine)) {
        $ProductID = $Val["ProductID"];
        $ProductCostID = $Val["ProductCostID"];
        $Quantity = $Val["Quantity"];
        $BillingType = $Val["BillingType"];
        $ProductName = $Val["Description"];
        $ThisPrice = $Val["Price"];
    }

    //NOW CHECK INVOICE HEADER FOR DISCOUNT PERCENT IF ANY
    $GetInvoiceHeader = "SELECT * FROM customerinvoices WHERE InvoiceID = {$InvoiceID}";
    $GotInvoiceHeader = mysqli_query($ClientCon, $GetInvoiceHeader);

    while ($Val = mysqli_fetch_array($GotInvoiceHeader)) {
        $DiscountPercent = $Val["DiscountPercent"];
    }


    //GET PRODUCT COSTING
    //GET BILLING TYPE FOR THIS
    if ($ProductID > 0) {
        $GetBillingType = "SELECT BillingType, ClientCost FROM productcost WHERE ProductCostID = {$ProductCostID}";
        $GotBillingType = mysqli_query($ClientCon, $GetBillingType);

        while ($Val = mysqli_fetch_array($GotBillingType)) {
            $BillingType = $Val["BillingType"];
            $ClientCost = $Val["ClientCost"];

            $ClientCost = $ClientCost * $Quantity;

            /*$CostBeforeVat = $ClientCost;

			$SubTotal = $ClientCost * $Quantity;

			if ($DiscountPercent > 0)
			{
				$DiscountPercent = ($DiscountPercent / 100);
				$DiscountAmount = $SubTotal * $DiscountPercent;

			}
			else
			{
				$DiscountAmount = 0;
			}

			$DiscountAmount = number_format($DiscountAmount,2, '.', '');

			$VatableAmount = $SubTotal - $DiscountAmount;*/
        }
    }


    //NOW LETS CHECK THE BILLING TYPE
    if ($ThisBillProRata == "true") {
        $FirstBilling = date("Y-m-d");
        $start = strtotime($FirstBilling);
        $end = strtotime($NextRun);
        $RecurredTimes = 1;
        $NumBillingDays = ceil(abs($end - $start) / 86400);
        //HOW MANY DAYS IN THIS MONTH
        $Days = date("d", mktime(0, 0, 0, date("m") + 1, 1 - 1, date("Y")));

        //BUILDING TO USE AddInvoiceLineProRata($InvoiceID, $ProductID, $Price, $Quantity, $DiscountPercent, $NumBillingDays, $Days)
        AddInvoiceLineProRata($InvoiceID, $ProductID, $ProductCostID, $Quantity, $DiscountPercent, $NumBillingDays, $Days);

        if ($ProductID > 0) {
            if ($BillingType != "Once-Off") {
                //NOW WE WANT TO ADD THE PRODUCT
                $InsertRecurring = "INSERT INTO customerproducts (CustomerID, ProductID, ProductCostID , FirstBillingDate, NextBillingDate, ProductDateAdded, ClientID, EmployeeID, AddedByName, ClientProductStatus, ProductName, ProductQuantity, RecurringTimes, RecurredTimes, WarehouseID, RecurringAmount) ";
                $InsertRecurring .= "VALUES ({$CustomerID}, {$ProductID}, {$ProductCostID}, '{$FirstBilling}', '{$NextRun}', '{$DateAdded}', {$ThisUserID}, {$ThisEmployeeID}, '{$ThisUser}', 2, '{$ProductName}', {$Quantity}, {$ThisRecurring}, {$RecurredTimes}, {$ThisWarehouseID}, {$ClientCost})";
                $DoInsertRecurring = mysqli_query($ClientCon, $InsertRecurring);
                echo mysqli_error($ClientCon);
            }
        }


    } else if ($ThisBillNext == "true") //ONLY CREATE INVOICE ON NEXT RUN, WE DO NEED TO ADD THE PRODUCT
    {
        if ($BillingType != "Once-Off") {
            //NOW WE WANT TO ADD THE PRODUCT
            $InsertRecurring = "INSERT INTO customerproducts (CustomerID, ProductID, ProductCostID , FirstBillingDate, NextBillingDate, ProductDateAdded, ClientID, EmployeeID, AddedByName, ClientProductStatus, ProductName, ProductQuantity, RecurringTimes, RecurredTimes, WarehouseID, RecurringAmount) ";
            $InsertRecurring .= "VALUES ({$CustomerID}, {$ProductID}, {$ProductCostID}, '{$NextRun}', '{$NextRun}', '{$DateAdded}', {$ThisUserID}, {$ThisEmployeeID}, '{$ThisUser}', 2, '{$ProductName}', {$Quantity}, {$ThisRecurring}, 0, {$ThisWarehouseID}, {$ClientCost})";
            $DoInsertRecurring = mysqli_query($ClientCon, $InsertRecurring);
            echo mysqli_error($ClientCon);
        }
    } else if ($ThisBillFull == "true") //FULL INVOICE LINE NOW
    {
        //BUILDING TO USE THIS FUNCTION AddInvoiceLine($InvoiceID, $ProductID, $Price, $Quantity, $DiscountPercent, $WarehouseID)
        if ($ProductID > 0) {
            $DoAddLine = AddInvoiceLine($InvoiceID, $ProductID, $ProductCostID, $Quantity, $DiscountPercent, $ThisWarehouseID);
        } else {
            $DoAddLine = AddCustomInvoiceItem($InvoiceID, $ProductName, $ThisPrice, $Quantity, $DiscountPercent, 0);
        }


        if ($ProductID > 0) {
            if ($BillingType != "Once-Off") {
                $FirstBilling = date("Y-m-d");

                //WE NEED TO WORK OUT NEXT RUN BASED ON BILLING TYPE
                if ($BillingType == "Monthly") {
                    $NextRun = date("Y-m-d", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")));
                } else if ($BillingType == "Quarterly") {
                    $NextRun = date("Y-m-d", mktime(0, 0, 0, date("m") + 3, date("d"), date("Y")));
                } else if ($BillingType == "Semi-Annually") {
                    $NextRun = date("Y-m-d", mktime(0, 0, 0, date("m") + 6, date("d"), date("Y")));
                } else if ($BillingType == "Annually") {
                    $NextRun = date("Y-m-d", mktime(0, 0, 0, date("m") + 12, date("d"), date("Y")));
                }


                //NOW WE WANT TO ADD THE PRODUCT
                $InsertRecurring = "INSERT INTO customerproducts (CustomerID, ProductID, ProductCostID , FirstBillingDate, NextBillingDate, ProductDateAdded, ClientID, EmployeeID, AddedByName, ClientProductStatus, ProductName, ProductQuantity, RecurringTimes, RecurredTimes, WarehouseID, RecurringAmount) ";
                $InsertRecurring .= "VALUES ({$CustomerID}, {$ProductID}, {$ProductCostID}, '{$FirstBilling}', '{$NextRun}', '{$DateAdded}', {$ThisUserID}, {$ThisEmployeeID}, '{$ThisUser}', 2, '{$ProductName}', {$Quantity}, {$ThisRecurring}, 1, {$ThisWarehouseID}, {$ClientCost})";
                $DoInsertRecurring = mysqli_query($ClientCon, $InsertRecurring);
                echo mysqli_error($ClientCon);
            }
        }
    }

    //SHOULD BE IT, LETS RETURN OK


    return "OK";


}

function UpdateQuoteAccepted($QuoteID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $UpdateQuote = "UPDATE customerquotes SET QuoteStatus = 2 WHERE QuoteID = {$QuoteID}";
    $DoUpdateQuote = mysqli_query($ClientCon, $UpdateQuote);

    return "OK";
}

//FUNCTION TO REMOVE AN INVOICE WHEN CONVERTING A QUOTE TO INVOICE BUT NO ITEMS ARE DUE
function RemoveInvoice($InvoiceID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $DelInvoice = "DELETE FROM customerinvoices WHERE InvoiceID = {$InvoiceID}";
    $DoDelInvoice = mysqli_query($ClientCon, $DelInvoice);

    return "OK";
}

//SECURITY
function GetAllSecurityGroups()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetGroups = "SELECT * FROM securitygroups ORDER BY SecurityGroupName";
    $GotGroups = mysqli_query($ClientCon, $GetGroups);

    return $GotGroups;
}

function AddSecurityGroup($NewGroup)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $NewGroup = CleanInput($NewGroup);

    $CheckGroup = "SELECT * FROM securitygroups WHERE SecurityGroupName = '{$NewGroup}'";
    $DoCheckGroup = mysqli_query($ClientCon, $CheckGroup);

    $FoundGroup = mysqli_num_rows($DoCheckGroup);

    if ($FoundGroup == 0) {
        $InsertGroup = "INSERT INTO securitygroups (SecurityGroupName) VALUES ('{$NewGroup}')";
        $DoInsertGroup = mysqli_query($ClientCon, $InsertGroup);

        $Error = mysqli_error($ClientCon);

        if ($Error == "") {
            return mysqli_insert_id($ClientCon);
        } else {
            return "There was an error adding the security group";
        }
    } else {
        return "The group name already exist, please enter a new group name";
    }
}

function GetNumEmployeesSecurityGroup($SecurityGroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $CheckEmployees = "SELECT COUNT(EmployeeSecurityGroupID) AS NumEmployees FROM employeesecuritygroups WHERE SecurityGroupID = {$SecurityGroupID}";
    $DoCheckEmployees = mysqli_query($ClientCon, $CheckEmployees);

    while ($Val = mysqli_fetch_array($DoCheckEmployees)) {
        $NumEmployees = $Val["NumEmployees"];
    }

    return $NumEmployees;
}

function GetAllModules()
{
    include('includes/dbinc.php');

    $GetAllModules = "SELECT * FROM systemmodules ORDER BY ModuleID ASC";
    $GotAllModules = mysqli_query($DB, $GetAllModules);

    return $GotAllModules;
}

function GetAllSubModules($ModuleID)
{
    include('includes/dbinc.php');

    $GetAllSubModules = "SELECT * FROM systemsubmodules WHERE ModuleID = {$ModuleID} ORDER BY SubModuleName ";
    $GotAllSubModules = mysqli_query($DB, $GetAllSubModules);
    echo mysqli_error($DB);

    return $GotAllSubModules;
}

function CheckGroupSecurity($GroupID, $SubModuleID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $CheckGroupSecurity = "SELECT * FROM securitygroupsettings WHERE SecurityGroupID = {$GroupID} AND SubModuleID = {$SubModuleID}";
    $DoCheckGroupSecurity = mysqli_query($ClientCon, $CheckGroupSecurity);

    $IsInGroup = mysqli_num_rows($DoCheckGroupSecurity);

    return $IsInGroup;
}

function ClearSecuritySettings($GroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ClearSettings = "DELETE FROM securitygroupsettings WHERE SecurityGroupID = {$GroupID}";
    $DoClearSettings = mysqli_query($ClientCon, $ClearSettings);

    return "OK";
}

function AddGroupSecuritySetting($ThisSubID, $GroupID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $AddSetting = "INSERT INTO securitygroupsettings (SecurityGroupID, SubModuleID) VALUES ({$GroupID}, {$ThisSubID})";
    $DoAddSetting = mysqli_query($ClientCon, $AddSetting);

    return "OK";
}

function CheckEmployeeSecurity($EmployeeID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetCurrentGroup = "SELECT * FROM employeesecuritygroups WHERE EmployeeID = {$EmployeeID}";
    $GotCurrentGroup = mysqli_query($ClientCon, $GetCurrentGroup);

    $CurrentGroup = '';

    while ($Val = mysqli_fetch_array($GotCurrentGroup)) {
        $CurrentGroup = $Val["SecurityGroupID"];
    }

    return $CurrentGroup;
}

function CheckPageAccess($SubModule)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $EmployeeID = $_SESSION["EmployeeID"];

    $GetEmployeeGroup = "SELECT * FROM employeesecuritygroups WHERE EmployeeID = {$EmployeeID}";
    $GotEmployeeGroup = mysqli_query($ClientCon, $GetEmployeeGroup);

    $EmployeeGroup = '';

    while ($Val = mysqli_fetch_array($GotEmployeeGroup)) {
        $EmployeeGroup = $Val["SecurityGroupID"];
    }

    if ($EmployeeGroup > 0) {
        //CHECK THE GROUP ACCESS TO THIS MODULE, GET THE SUB MODULE ID
        include('includes/dbinc.php');

        $GetSubModuleID = "SELECT * FROM systemsubmodules WHERE SubModuleName = '{$SubModule}'";
        $GotSubModuleID = mysqli_query($DB, $GetSubModuleID);

        $SubModuleID = "";

        while ($Val = mysqli_fetch_array($GotSubModuleID)) {
            $SubModuleID = $Val["SubModuleID"];
        }

        if ($SubModuleID > 0) {
            $CheckAccess = "SELECT * FROM securitygroupsettings WHERE SecurityGroupID = {$EmployeeGroup} AND SubModuleID = {$SubModuleID}";
            $DoCheckAccess = mysqli_query($ClientCon, $CheckAccess);

            $FoundAccess = mysqli_num_rows($DoCheckAccess);

            if ($FoundAccess > 0) {
                $Access = 1;
            } else {
                $Access = 0;
            }
        } else {
            $Access = 0;
        }

    } else {
        $Access = 0;
    }

    return $Access;
}

//ALL INVOICES
function GetAllInvoicesReport($InvoiceStatus)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetAllInvoices = "SELECT * FROM customerinvoices, customers WHERE customerinvoices.CustomerID = customers.CustomerID AND InvoiceStatus != 0 ";
    if ($InvoiceStatus != "") {
        $GetAllInvoices .= " AND InvoiceStatus = {$InvoiceStatus}";
    }
    $GetAllInvoices .= " ORDER BY InvoiceDate DESC";
    $GotAllInvoices = mysqli_query($ClientCon, $GetAllInvoices);

    echo mysqli_error($ClientCon);

    return $GotAllInvoices;
}

//ALL QUOTES
function UpdateQuoteProposal($QuoteID, $ProposalText)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ProposalText = CleanInput($ProposalText);

    $UpdateQuote = "UPDATE customerquotes SET ProposalText = '{$ProposalText}' WHERE QuoteID = {$QuoteID}";
    $DoUpdateQuote = mysqli_query($ClientCon, $UpdateQuote);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the proposal text";
    }
}

function UpdateQuoteFooter($QuoteID, $FooterText)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $FooterText = CleanInput($FooterText);

    $UpdateQuote = "UPDATE customerquotes SET FooterText = '{$FooterText}' WHERE QuoteID = {$QuoteID}";
    $DoUpdateQuote = mysqli_query($ClientCon, $UpdateQuote);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the footer text";
    }
}

function GetAllQuotesReport($QuoteStatus)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetAllQuotes = "SELECT * FROM customerquotes, customers WHERE customerquotes.CustomerID = customers.CustomerID AND QuoteStatus != 0 ";
    if ($QuoteStatus != "") {
        $GetAllQuotes .= " AND QuoteStatus = {$QuoteStatus}";
    }
    $GetAllQuotes .= " ORDER BY QuoteDate DESC";
    $GotAllQuotes = mysqli_query($ClientCon, $GetAllQuotes);

    echo mysqli_error($ClientCon);

    return $GotAllQuotes;
}

//INCOME REPORT
function GetIncomeReport($FromDate, $ToDate)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetIncome = "SELECT * FROM customers, customertransactions WHERE customertransactions.CustomerID = customers.CustomerID AND PaymentDate >= '{$FromDate}' AND PaymentDate <= '{$ToDate} ORDER BY PaymentDate DESC'";
    $GotIncome = mysqli_query($ClientCon, $GetIncome);

    return $GotIncome;
}

//CUSTOMER SITES
function GetCustomerSites($CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetSites = "SELECT * FROM customersites WHERE CustomerID = {$CustomerID} ORDER BY SiteName";
    $GotSites = mysqli_query($ClientCon, $GetSites);

    return $GotSites;
}

function GetSiteDetails($CustomerID, $SiteID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetSites = "SELECT * FROM customersites WHERE CustomerID = {$CustomerID} AND SiteID = {$SiteID} ORDER BY SiteName";
    $GotSites = mysqli_query($ClientCon, $GetSites);


    return $GotSites;
}

function UpdateSiteDetails($SiteName, $ContactName, $ContactTel, $EmailAddress, $Address1, $Address2, $City, $State, $PostCode, $Country, $CustomerID, $SiteID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $ThisClientID = $_SESSION["ClientID"];

    $SiteName = CleanInput($SiteName);
    $ContactName = CleanInput($ContactName);
    $ContactTel = CleanInput($ContactTel);
    $EmailAddress = CleanInput($EmailAddress);

    $Address1 = CleanInput($Address1);
    $Address2 = CleanInput($Address2);
    $City = CleanInput($City);
    $PostCode = CleanInput($PostCode);
    $Country = CleanInput($Country);


    $UpdateClientInfo = "UPDATE customersites SET SiteName = '{$SiteName}', ContactPerson = '{$ContactName}', ContactNumber =  '{$ContactTel}', EmailAddress = '{$EmailAddress}', Address1 = '{$Address1}', Address2 = '{$Address2}', City = '{$City}', Region = '{$State}', PostCode = '{$PostCode}', CountryID = {$Country} ";

    $UpdateClientInfo .= " WHERE SiteID = {$SiteID} AND CustomerID = {$CustomerID}";

    $DoUpdateClientInfo = mysqli_query($ClientCon, $UpdateClientInfo);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error updating the client site, please check your input and try again" . $UpdateClientInfo;
    }
}

function AddSiteDetails($SiteName, $ContactName, $ContactTel, $EmailAddress, $Address1, $Address2, $City, $State, $PostCode, $Country, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);
    $ThisClientID = $_SESSION["ClientID"];

    $SiteName = CleanInput($SiteName);
    $ContactName = CleanInput($ContactName);
    $ContactTel = CleanInput($ContactTel);
    $EmailAddress = CleanInput($EmailAddress);

    $Address1 = CleanInput($Address1);
    $Address2 = CleanInput($Address2);
    $City = CleanInput($City);
    $PostCode = CleanInput($PostCode);
    $Country = CleanInput($Country);


    $UpdateClientInfo = "INSERT INTO customersites (CustomerID, SiteName, ContactPerson, ContactNumber, EmailAddress , Address1, Address2, City, Region, PostCode, CountryID) ";
    $UpdateClientInfo .= "VALUES ( {$CustomerID}, '{$SiteName}', '{$ContactName}', '{$ContactTel}', '{$EmailAddress}', '{$Address1}', '{$Address2}', '{$City}', '{$State}', '{$PostCode}', {$Country})";


    $DoUpdateClientInfo = mysqli_query($ClientCon, $UpdateClientInfo);

    $Error = mysqli_error($ClientCon);

    if ($Error == "") {
        return "OK";
    } else {
        return "There was an error adding the client site, please check your input and try again" . $UpdateClientInfo;
    }
}

function GetSiteName($SiteID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetSites = "SELECT * FROM customersites WHERE  SiteID = {$SiteID}";
    $GotSites = mysqli_query($ClientCon, $GetSites);
    echo mysqli_error($ClientCon);

    while ($Val = mysqli_fetch_array($GotSites)) {
        $SiteName = $Val["SiteName"];
    }


    return $SiteName;


}

function GetCustomerSitesArray($Customer)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetSites = "SELECT * FROM customersites WHERE  CustomerID = {$Customer} ORDER BY SiteName";
    $GotSites = mysqli_query($ClientCon, $GetSites);

    $SitesArray = '';

    while ($Val = mysqli_fetch_array($GotSites)) {
        $SiteID = $Val["SiteID"];
        $SiteName = $Val["SiteName"];

        $SitesArray .= $SiteID . "---" . $SiteName . ":::";
    }

    return rtrim($SitesArray, ":::");
}

//SALES REPORT
function GetProductSales($ProductID, $FromDate, $ToDate)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetSales = "SELECT SUM(LineTotal) AS RandValue, SUM(StockAffect) AS NumSold FROM customerinvoices, customerinvoicelines WHERE customerinvoices.InvoiceID = customerinvoicelines.InvoiceID AND InvoiceDate >= '{$FromDate}' AND InvoiceDate <= '{$ToDate}' AND ProductID = {$ProductID}";
    $GotSales = mysqli_query($ClientCon, $GetSales);

    while ($Val = mysqli_fetch_array($GotSales)) {
        $RandValue = $Val["RandValue"];
        $NumSold = $Val["NumSold"];
    }

    if ($RandValue == "") {
        $RandValue = 0;
        $NumSold = 0;
    }

    $ReturnArray = array(0 => number_format($RandValue, 2), 1 => $NumSold);


    return $ReturnArray;
}


//TECHNICIAN REPORT
function GetTechnicians()
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetTechs = "SELECT DISTINCT(AssignedTo) FROM jobcards";
    $GotTechs = mysqli_query($ClientCon, $GetTechs);
    echo mysqli_error($ClientCon);

    return $GotTechs;
}

function GetTotalJobs($AssignedTo, $FromDate, $ToDate)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetTotalJobsPeriod = "SELECT COUNT(JobcardID) AS NumJobs FROM jobcards WHERE DateScheduled >= '{$FromDate}' AND DateScheduled <= '{$ToDate}' AND AssignedTo = {$AssignedTo}";
    $GotTotalJobsPeriod = mysqli_query($ClientCon, $GetTotalJobsPeriod);
    echo mysqli_error($ClientCon);

    $TotalJobs = 0;

    while ($Val = mysqli_fetch_array($GotTotalJobsPeriod)) {
        $TotalJobs = $Val["NumJobs"];
    }

    return $TotalJobs;
}

function GetTotalIncompleteJobs($AssignedTo, $FromDate, $ToDate)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetTotalJobsPeriod = "SELECT COUNT(JobcardID) AS NumJobs FROM jobcards WHERE DateScheduled >= '{$FromDate}' AND DateScheduled <= '{$ToDate}' AND AssignedTo = {$AssignedTo} AND JobcardStatus = 0";
    $GotTotalJobsPeriod = mysqli_query($ClientCon, $GetTotalJobsPeriod);
    echo mysqli_error($ClientCon);

    $TotalJobs = 0;

    while ($Val = mysqli_fetch_array($GotTotalJobsPeriod)) {
        $TotalJobs = $Val["NumJobs"];
    }

    return $TotalJobs;
}

function GetTotalWaitingJobs($AssignedTo, $FromDate, $ToDate)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetTotalJobsPeriod = "SELECT COUNT(JobcardID) AS NumJobs FROM jobcards WHERE DateScheduled >= '{$FromDate}' AND DateScheduled <= '{$ToDate}' AND AssignedTo = {$AssignedTo} AND JobcardStatus = 1";
    $GotTotalJobsPeriod = mysqli_query($ClientCon, $GetTotalJobsPeriod);
    echo mysqli_error($ClientCon);

    $TotalJobs = 0;

    while ($Val = mysqli_fetch_array($GotTotalJobsPeriod)) {
        $TotalJobs = $Val["NumJobs"];
    }

    return $TotalJobs;
}

function GetTotalCompletedJobs($AssignedTo, $FromDate, $ToDate)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetTotalJobsPeriod = "SELECT COUNT(JobcardID) AS NumJobs FROM jobcards WHERE DateScheduled >= '{$FromDate}' AND DateScheduled <= '{$ToDate}' AND AssignedTo = {$AssignedTo} AND JobcardStatus = 2";
    $GotTotalJobsPeriod = mysqli_query($ClientCon, $GetTotalJobsPeriod);
    echo mysqli_error($ClientCon);

    $TotalJobs = 0;

    while ($Val = mysqli_fetch_array($GotTotalJobsPeriod)) {
        $TotalJobs = $Val["NumJobs"];
    }

    return $TotalJobs;
}

function GetTotalHours($AssignedTo, $FromDate, $ToDate)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetTotalJobsPeriod = "SELECT * FROM jobcards WHERE DateScheduled >= '{$FromDate}' AND DateScheduled <= '{$ToDate}' AND AssignedTo = {$AssignedTo} AND JobcardStatus = 2";
    $GotTotalJobsPeriod = mysqli_query($ClientCon, $GetTotalJobsPeriod);
    echo mysqli_error($ClientCon);

    $TotalHours = 0;
    $TotalMinutes = 0;

    while ($Val = mysqli_fetch_array($GotTotalJobsPeriod)) {
        $TotalTime = $Val["TotalTime"];
        $TotalTimeArray = explode(":", $TotalTime);


        $ThisHours = str_ireplace("hrs", "", $TotalTimeArray[0]);
        $ThisMinutes = str_ireplace("min", "", $TotalTimeArray[1]);


        $TotalHours = $TotalHours + $TotalTimeArray[0];
        $TotalMinutes = $TotalMinutes + $TotalTimeArray[1];
    }


    $NumMinutes = $TotalMinutes % 60;
    $NumHours = floor($TotalMinutes / 60) + $TotalHours;


    //echo $NumHours . ":" . $NumMinutes . "<br><br>";

    if ($NumHours < 10) {
        $NumHours = "0" . $NumHours;
    }

    if ($NumMinutes < 10) {
        $NumMinutes = "0" . $NumMinutes;
    }

    return $NumHours . ":" . $NumMinutes;
}

function GetTotalIncomeFromJobs($AssignedTo, $FromDate, $ToDate)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetTotalJobsPeriod = "SELECT SUM(LineTotal) AS TotalInvoiced FROM jobcards, customerinvoicelines WHERE DateScheduled >= '{$FromDate}' AND DateScheduled <= '{$ToDate}' AND AssignedTo = {$AssignedTo} AND JobcardStatus = 2 AND jobcards.InvoiceID = customerinvoicelines.InvoiceID AND jobcards.InvoiceID > 0";
    $GotTotalJobsPeriod = mysqli_query($ClientCon, $GetTotalJobsPeriod);
    echo mysqli_error($ClientCon);

    $TotalJobs = 0;

    while ($Val = mysqli_fetch_array($GotTotalJobsPeriod)) {
        $TotalInvoiced = $Val["TotalInvoiced"];
    }

    return $TotalInvoiced;
}

//INVOICE REPORT
function GetAllInvoicesDone($FromDate, $ToDate)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $Today = date("Y-m-d");

    $GetInvoicing = "SELECT * FROM customers, customerinvoices WHERE customers.CustomerID = customerinvoices.CustomerID AND InvoiceStatus NOT IN (0,3) AND InvoiceDate <= '{$ToDate}' AND InvoiceDate >= '{$FromDate}' ORDER BY InvoiceID ASC";
    $GotInvoicing = mysqli_query($ClientCon, $GetInvoicing);

    return $GotInvoicing;
}

//QUOTE REPORT
function GetAllQuotesReporting($QuoteStatus, $FromDate, $ToDate)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetQuotes = "SELECT * FROM customers, customerquotes WHERE customers.CustomerID = customerquotes.CustomerID AND QuoteStatus NOT IN (0) AND QuoteDate <= '{$ToDate}' AND QuoteDate >= '{$FromDate}' ";
    if ($QuoteStatus != "") {
        $GetQuotes .= "AND QuoteStatus = {$QuoteStatus} ";
    }

    $GetQuotes .= " ORDER BY QuoteID ASC";

    $GotQuotes = mysqli_query($ClientCon, $GetQuotes);
    echo mysqli_error($ClientCon);

    return $GotQuotes;
}

//ADDITIONAL TO JOBCARD REPORT
function GetAllClientSites($CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $GetSites = "SELECT * FROM customersites WHERE CustomerID = {$CustomerID} ORDER BY SiteName";
    $GotSites = mysqli_query($ClientCon, $GetSites);

    return $GotSites;
}

// added by e2a
function TotalInvoicesCAR($InvoiceStatus)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];
    $returnArray = array();
    $count = 0;

    $CountInvoices = "SELECT customers.CustomerID as CustomerID, CONCAT(customers.FirstName, ' ', customers.Surname) as Fullname,
	customers.CompanyName as CompanyName, SUM(LineTotal) AS InvoiceTotals,customerinvoices.InvoiceID as InvoiceID
	FROM customerinvoices, customerinvoicelines,
	customers WHERE InvoiceStatus IN ($InvoiceStatus) AND customerinvoices.InvoiceID = customerinvoicelines.InvoiceID
	and customers.CustomerID =customerinvoices.CustomerID group by customers.CustomerID order by fullname ASC";

    $DoCountInvoices = mysqli_query($ClientCon, $CountInvoices);
    $InvoiceTotals = 0;

    while ($Val = mysqli_fetch_array($DoCountInvoices)) {
        //print_r($Val);
        $returnArray[$count]['CustomerID'] = $Val["CustomerID"];
        $returnArray[$count]['Fullname'] = $Val["Fullname"];
        $returnArray[$count]['CompanyName'] = $Val["CompanyName"];
        //$returnArray[$count]['InvoiceTotals'] = $Val["InvoiceTotals"];
        $returnArray[$count]['InvoiceTotals'] = GetInvoiceOutstandingAmount($Val["InvoiceID"]);

        $count++;
    }

    return $returnArray;

}

function TotalInvoicesByDays($monthName, $CustomerID, $InvoiceStatus, $lastMonth = FALSE)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];

    $extraCondition = " AND  MONTHNAME(customerinvoices.InvoiceDate) = '$monthName'";

    if ($lastMonth)
        $extraCondition = " AND  customerinvoices.InvoiceDate <= DATE_SUB(NOW(), INTERVAL 4 MONTH) ";

    $CountInvoices = "SELECT customerinvoicelines.InvoiceID as InvoiceID
	FROM customerinvoices, customerinvoicelines WHERE
	InvoiceStatus IN ($InvoiceStatus)  AND CustomerID = {$CustomerID} $extraCondition
	AND	customerinvoices.InvoiceID = customerinvoicelines.InvoiceID group by customerinvoicelines.InvoiceID";


    $DoCountInvoices = mysqli_query($ClientCon, $CountInvoices);
    $InvoiceTotals = 0;

    while ($Val = mysqli_fetch_array($DoCountInvoices)) {
        $InvoiceTotal += GetInvoiceOutstandingAmount($Val["InvoiceID"]);
    }

    return $InvoiceTotal;

}

function TotalInvoicesCurrentMonth($InvoiceStatus, $CustomerID)
{
    $ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], $_SESSION["DBName"]);

    $ThisClientID = $_SESSION["ClientID"];

    $CountInvoices = "SELECT SUM(LineTotal) AS InvoiceTotals FROM customerinvoices, customerinvoicelines
	WHERE InvoiceStatus IN ($InvoiceStatus)  AND CustomerID = {$CustomerID} AND MONTH(InvoiceDate) = MONTH(CURRENT_DATE())
	AND customerinvoices.InvoiceID = customerinvoicelines.InvoiceID ";
    $DoCountInvoices = mysqli_query($ClientCon, $CountInvoices);
    $InvoiceTotals = 0;

    while ($Val = mysqli_fetch_array($DoCountInvoices)) {
        $InvoiceTotal = $Val["InvoiceTotals"];
    }

    return $InvoiceTotal;

}

//end

?>	