<?php
session_start();

//SECURITY
//SECURITY
$ThisClientID = $_SESSION["ClientID"];
$ThisUser = $_SESSION["ClientName"];
$ThisSecret = $_SESSION["SiteSecret"];

if ($ThisClientID > 0 && $ThisUser != "" && $ThisSecret == "E2A_crm_S5gdbh6nnj_usr_9898")
{
	$DocumentID = $_REQUEST["d"];
	$CustomerID = $_REQUEST["c"];
	
	$ClientCon = mysqli_connect($_SESSION["DBHost"], $_SESSION["DBUser"], $_SESSION["DBPass"], 	$_SESSION["DBName"]);
	
	
	
	
	$GetDoc = "SELECT DocumentFile, DocumentName FROM customerdocuments WHERE DocumentID = {$DocumentID} AND CustomerID = {$CustomerID}";
	$GotDoc = mysqli_query($ClientCon, $GetDoc);
	echo mysqli_error($ClientCon);
	
	while ($Val = mysqli_fetch_array($GotDoc))
	{
		$DocumentFile = $Val["DocumentFile"];
		$DocumentName = $Val["DocumentName"];
	}
	
	$LogDate = date("Y-m-d H:i:s");
	
	$ThisUser = $_SESSION["ClientName"];
	$EmployeeID = $_SESSION["EmployeeID"];
	$ClientID = $_SESSION["ClientID"];
	
	$ActivityType = "Downloaded Document " . $DocumentName;
	
	if ($EmployeeID == "")
	{
		$EmployeeID = 0;	
	}
	
	
	$InsertLog = "INSERT INTO customeraccess(CustomerID, ClientID, EmployeeID, LogType, LogDate, AccessName) VALUES ({$CustomerID}, {$ClientID}, {$EmployeeID}, '{$ActivityType}', '{$LogDate}', '{$ThisUser}')";
	$DoInsertLog = mysqli_query($ClientCon, $InsertLog);
	echo mysqli_error($ClientCon);
	
	if ($DocumentFile != "")
	{
		//FORCE DOWNLOAD
		$file_url = 'clientdocs/' . $ThisFile;
		header('Content-Type: application/octet-stream');
		header("Content-disposition: attachment; filename=\"" . $DocumentFile . "\""); 
		echo readfile($file_url);
	}
	else
	{
		die("Security breach detected");	
	}
}
else
{
	die("Security breach detected");	
}