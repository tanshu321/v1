<?php
include("includes/webfunctions.php");
include('includes/agent.php');
$agent->init();


//SECURITY
$ThisClientID = $_SESSION["ClientID"];
$ThisUser = $_SESSION["ClientName"];
$ThisSecret = $_SESSION["SiteSecret"];

if ($ThisClientID > 0 && $ThisUser != "" && $ThisSecret == "E2A_crm_S5gdbh6nnj_usr_9898")
{
	$Clients = GetAllClients();
	$Employees = GetAllEmployees();
	
	$JobcardID = $_REQUEST["j"];
	$CustomerAccount = $_REQUEST["c"];
	
	$Upload = $_REQUEST["u"];
			if ($Upload == "y")
			{
				//OK FORM SUBMITTED, LETS CHECK IF THERES A LOGO TO UPLOAD
				
	
				$SafeFile = $_FILES['jobcardfile']['name']; 
				
				
				
				if(is_uploaded_file(($_FILES['jobcardfile']['tmp_name'])))
				{
						$imagename = $_FILES['jobcardfile']['name'];
			
						if ($imagename != "")
						{
							$source = $_FILES['jobcardfile']['tmp_name'];
							$NewFileName = "JBC" . $JobcardID . "_" . time() . "_" . $imagename;
							$target = "jobcards/" . $NewFileName;  
							move_uploaded_file($source, $target);
							
							$UpdateJobDic = UpdateJobcardDoc($JobcardID, $NewFileName);
							echo "<script type='text/javascript'>document.location = 'editjobcard.php?j=" . $JobcardID . "';</script>"; 
						}
				 }
				
		
			
			
		}
	
	
	
	$JobcardDetails = GetJobcard($JobcardID);
	
	while ($Val = mysqli_fetch_array($JobcardDetails))
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
		$InvoiceID = $Val["InvoiceID"];		
		$ManualJobcardNumber = $Val["ManualJobcardNumber"];
		$TotalTime = $Val["TotalTime"];
		$SiteID = $Val["SiteID"];
		$WorkOrder = $Val["WorkOrder"];
		
		
		
		if ($SiteID != 0)
		{
			$SiteName = GetSiteName($SiteID);
		}
		else
		{
			$SiteName = 'Head Office';	
		}
		
		if ($TotalTime != "")
		{
			$TotalTimeArray = explode(":", $TotalTime);
			$TotalHours	 = $TotalTimeArray[0];
			$TotalMinutes = $TotalTimeArray[1];
		}
		
		
		$Sites = GetCustomerSites($JobCustomerID);	
		
		
		$CustomerID = $Val["CustomerID"];						
	}
	
	$CustomerInvoices = GetAllCustomerInvoices($CustomerID);
	
	//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
	if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
	else
	{
		$Access = CheckPageAccess('Add Jobcard');	
	}
	
	
}
else
{
	echo "<script type='text/javascript'>alert('Your session has expired, please login again'); parent.location = 'logout.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Business CRM</title>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<script type="text/javascript">


function UpdateJobcard()
{
	var Customer = document.getElementById("client").value;
	var Employee = document.getElementById("employee").value;	
	var ScheduledDate = document.getElementById("datepicker").value;
	var Notes = document.getElementById("notes").value;
	var TechNotes = document.getElementById("technotes").value;
	var UploadJobcard = document.getElementById("jobcardfile").value;
	var InvoiceID = document.getElementById("customerinvoice").value;
	var ManualJobcardNumber = document.getElementById("manualjob").value;
	var WorkOrder = document.getElementById("workorder").value;
	
	var TotalHours = document.getElementById("hours").value;
	var TotalMinutes = document.getElementById("minutes").value;
	var Site = document.getElementById("site").value;
	
	var Error = 0;
	
	if (UploadJobcard != "")
	{
		var FileType = UploadJobcard.split('.').pop();	
		if (FileType == 'doc' || FileType == 'DOC' || FileType == 'pdf' || FileType == 'PDF' || FileType == 'DOCX' || FileType == 'docx' || FileType == 'jpg' || FileType == 'JPG' || FileType == 'png' || FileType == 'PNG')
		{
			
		}
		else
		{
			Error = 1;
			bootbox.alert("The file you are uploading is not supported, please make sure the file type is a doc, docx, pdf, jpg or png file"); 	
		}
	}
	
	if (parseInt(TotalHours) >= 0)
	{
		
	}
	else
	{
		if (Error == 0)
		{
			Error = 1;
			bootbox.alert("Please enter the total number of hours"); 
		}
	}
	
	if (parseInt(TotalMinutes) >= 0 && parseInt(TotalMinutes) < 59)
	{
		
	}
	else
	{
		if (Error == 0)
		{
			Error = 1;
			bootbox.alert("Please enter the total number of minutes between 0 and 59"); 
		}
	}
	
	if (Error == 0)
	{
		if (TotalHours.length == 1)
		{
			TotalHours = "0" + TotalHours;	
		}
		
		if (TotalMinutes.length == 1)
		{
			TotalMinutes = "0" + TotalMinutes;	
		}
		
		var TotalTime = TotalHours + ":" + TotalMinutes;
		
		if (Employee != "" && ScheduledDate != "" && Site != "")
		{
			var UpdateJobcardDetails = agent.call('','UpdateJobcard','', Employee, ScheduledDate, Notes, TechNotes, InvoiceID, '<?php echo $JobcardID ?>', '<?php echo $JobcardStatus ?>', UploadJobcard, ManualJobcardNumber, TotalTime, Site, WorkOrder);
			if (UpdateJobcardDetails == "OK")
			{
				
				if (UploadJobcard != "")
				{
					document.getElementById("jobcardform").action = "editjobcard.php?u=y&j=<?php echo $JobcardID ?>&type=" + FileType;
					document.getElementById("jobcardform").submit();	
				}
				else
				{
					bootbox.alert("Jobcard updated successfully");	
				}
				
			}
			else
			{
				bootbox.alert(UpdateJobcardDetails);	
			}
		}
		else
		{
			bootbox.alert("Please fill in all fields marked with a *");
		}
	}
}

function UpdateJobcardInvoice()
{
	//MUST STILL FILL IN BASICS...	
	var Customer = document.getElementById("client").value;
	var Employee = document.getElementById("employee").value;	
	var ScheduledDate = document.getElementById("datepicker").value;
	var Notes = document.getElementById("notes").value;
	var TechNotes = document.getElementById("technotes").value;
	var UploadJobcard = document.getElementById("jobcardfile").value;
	var InvoiceID = document.getElementById("customerinvoice").value;
	var ManualJobcardNumber = document.getElementById("manualjob").value;
	
	var TotalHours = document.getElementById("hours").value;
	var TotalMinutes = document.getElementById("minutes").value;
	var Site = document.getElementById("site").value;
	
	var Error = 0;
	
	if (UploadJobcard != "")
	{
		var FileType = UploadJobcard.split('.').pop();	
		if (FileType == 'doc' || FileType == 'DOC' || FileType == 'pdf' || FileType == 'PDF' || FileType == 'DOCX' || FileType == 'docx' || FileType == 'jpg' || FileType == 'JPG' || FileType == 'png' || FileType == 'PNG')
		{
			
		}
		else
		{
			Error = 1;
			bootbox.alert("The file you are uploading is not supported, please make sure the file type is a doc, docx, pdf, jpg or png file"); 	
		}
	}
	
	if (parseInt(TotalHours) >= 0)
	{
		
	}
	else
	{
		if (Error == 0)
		{
			Error = 1;
			bootbox.alert("Please enter the total number of hours"); 
		}
	}
	
	if (parseInt(TotalMinutes) >= 0 && parseInt(TotalMinutes) < 59)
	{
		
	}
	else
	{
		if (Error == 0)
		{
			Error = 1;
			bootbox.alert("Please enter the total number of minutes between 0 and 59"); 
		}
	}
	
	if (Error == 0)
	{
		if (TotalHours.length == 1)
		{
			TotalHours = "0" + TotalHours;	
		}
		
		if (TotalMinutes.length == 1)
		{
			TotalMinutes = "0" + TotalMinutes;	
		}
		
		var TotalTime = TotalHours + ":" + TotalMinutes;
		
		if (Employee != "" && ScheduledDate != "" && Site != "")
		{
			var UpdateJobcardDetails = agent.call('','UpdateJobcard','', Employee, ScheduledDate, Notes, TechNotes, InvoiceID, '<?php echo $JobcardID ?>', '<?php echo $JobcardStatus ?>', UploadJobcard, ManualJobcardNumber, TotalTime, Site);
			if (UpdateJobcardDetails == "OK")
			{
				
				document.location = 'addcustomerinvoice.php?c=<?php echo $CustomerID ?>&job=<?php echo $JobcardID ?>';
				
			}
			else
			{
				bootbox.alert(UpdateJobcardDetails);	
			}
		}
		else
		{
			bootbox.alert("Please fill in all fields marked with a *");
		}
	}
}

</script>
</head>

<body>

    <div id="wrapper">

        <?php include("navigation.php") ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Job Card Details <?php echo $JobcardNumber ?> <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                
                <!-- /.col-lg-12 -->
            </div>
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to add a new job card
                            </div>     
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    
					 <!-- Nav tabs -->
                           <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                <li><a href="jobcards.php"><i class="fa fa-caret-right"></i> Incomplete Job Cards</a>
                                </li>
                                <li><a href="jobcardsinvoicing.php"><i class="fa fa-caret-right"></i> Job Cards Waiting Invoicing</a>
                                </li>
                                <li><a href="jobcardscomplete.php"><i class="fa fa-caret-right"></i> Completed Job Cards</a>
                                </li>
                                
                                </li>
                                <li><a href="addjobcard.php"><i class="fa fa-caret-right"></i> Add Job Card</a>
                                </li>
                                <li class="pull-right"><a href="jobcards.php"><i class="fa fa-caret-left"></i> Back to all Job Cards</a>
                                </li>
                                
                                
                               
                               
                            </ul>
                  
                        
                        
                               <?php if ($Access == 1) { ?>                
                             <div class="col-lg-12" style="padding-top: 10px">
                                        <h4 style="padding-bottom: 10px">Job Card Details <?php if ($JobcardFile == "") { ?><a href="showjobcardprint.php?j=<?php echo $JobcardID ?>" class="btn btn-default pull-right" style="margin-bottom: 10px; margin-right: 10px" target="_blank"><i class="fa fa-eye"></i> Print Jobcard</a><?php } else { ?><a href="jobcards/<?php echo $JobcardFile ?>" class="btn btn-default pull-right" style="margin-bottom: 10px; margin-right: 10px" target="_blank"><i class="fa fa-eye"></i> Download Signed Job Card</a><?php } ?><?php if ($CustomerID != "") { ?> <a href="clientjobcards.php?c=<?php echo $CustomerID ?>" class="btn btn-default pull-right" style="margin-bottom: 10px; margin-right: 10px"><i class="fa fa-caret-left"></i> Open Client</a> <?php } ?> </h4>
                                                <div class="form-group row col-md-6">
                                                  <label for="firstname" class="col-sm-5 col-form-label" style="padding-top: 5px">Select Customer *</label>
                                                  <div class="col-sm-6">
                                                    <select class="form-control" id="client" name="client" disabled>
                                                    	<option value="" selected>Select Customer</option>
                                                    	<?php while ($Val = mysqli_fetch_array($Clients))
														{
															$CustomerID = $Val["CustomerID"];
															$Name = $Val["FirstName"];
															$Surname = $Val["Surname"];	
															$CompanyName = $Val["CompanyName"];	
															
															if ($CompanyName != "")
															{
																$ShowClient = $CompanyName . " (" . $Name . " " . $Surname . ")";	
															}
															else
															{
																$ShowClient = $Name . "  " . $Surname;	
															}
															
															if ($CustomerID == $JobCustomerID)
															{
																$Selected = 'selected';	
															}
															else
															{
																$Selected = '';	
															}
														?>
                                                        <option value="<?php echo $CustomerID ?>" <?php echo $Selected ?>><?php echo $ShowClient ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    
                                                   
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="firstname" class="col-sm-5 col-form-label" style="padding-top: 5px">Select Site *</label>
                                                  <div class="col-sm-6">
                                                    <select class="form-control" id="site">
                                                    	<option value="<?php echo $SiteID ?>" selected><?php echo $SiteName ?></option>
                                                        <?php while ($Val = mysqli_fetch_array($Sites))
														{
															$ThisSiteID = $Val["SiteID"];
															$ThisSiteName = $Val["SiteName"];
															
														?>
                                                        <option value="<?php echo $ThisSiteID ?>"><?php echo $ThisSiteName ?></option>
                                                        <?php } ?>
                                                    	
                                                    </select>
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="surname" class="col-sm-5 col-form-label" style="padding-top: 5px">Assign To Employee *</label>
                                                  <div class="col-sm-6">
                                                    <select class="form-control" id="employee" name="employee">
                                                    	<option value="" selected>Select Employee</option>
                                                    	<?php while ($Val = mysqli_fetch_array($Employees))
														{
															$EmployeeID = $Val["EmployeeID"];
															$Name = $Val["Name"];
															$Surname = $Val["Surname"];	
															
															if ($EmployeeID == $AssignedTo)
															{
																$Selected = 'selected';	
															}
															else
															{
																$Selected = '';		
															}
															
														?>
                                                        <option value="<?php echo $EmployeeID ?>"  <?php echo $Selected ?>><?php echo $Name ?> <?php echo $Surname ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">Date Added</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control datepicker" id="dateadded" name="dateadded" placeholder="Date Added" value="<?php echo $DateCreated ?>" disabled>
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">Schedule for *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control datepicker" id="datepicker" name="datepicker" placeholder="Schedule Date"  data-date-format="yyyy-mm-dd" value="<?php echo $DateScheduled ?>">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="tel" class="col-sm-5 col-form-label" style="padding-top: 5px">Job Card Notes/Fault Description</label>
                                                  <div class="col-sm-6">
                                                  	<textarea class="form-control" id="notes"><?php echo $Notes ?></textarea>
                                                    
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="tel" class="col-sm-5 col-form-label" style="padding-top: 5px">Technician Notes</label>
                                                  <div class="col-sm-6">
                                                  	<textarea class="form-control" id="technotes"><?php echo $TechNotes ?></textarea>
                                                    
                                                  </div>
                                                </div>
                                                <form enctype="multipart/form-data" id="jobcardform" name="jobcardform" action="" method="post">
                                                <?php if ($JobcardFile == "") { ?>
                                                <div class="form-group row col-md-6">
                                                  <label for="tel" class="col-sm-5 col-form-label" style="padding-top: 5px">Upload Signed Job Card (doc, docx, pdf, jpg, png)</label>
                                                  <div class="col-sm-6">
                                                  	<input type="file" class="form-control" id="jobcardfile" name="jobcardfile">
                                                    
                                                  </div>
                                                </div>
                                               
                                                <?php } else { ?>
                                                <div class="form-group row col-md-6">
                                                  <label for="tel" class="col-sm-5 col-form-label" style="padding-top: 5px">Replace Signed Job Card (doc, docx, pdf, jpg, png)</label>
                                                  <div class="col-sm-6">
                                                  	<input type="file" class="form-control" id="jobcardfile" name="jobcardfile">
                                                    
                                                  </div>
                                                </div>
                                                <?php } ?>
                                                 </form>
                                                
                                                <?php if ($JobcardStatus > 0)
												{ $ShowInvoice = 'block'; } else { $ShowInvoice = 'none'; }?>
                                                <div class="form-group row col-md-6" style="display: <?php echo $ShowInvoice ?>">
                                                  <label for="tel" class="col-sm-5 col-form-label" style="padding-top: 5px">Link to Invoice</label>
                                                  <div class="col-sm-6">
                                                  	<select class="form-control" id="customerinvoice" name="customerinvoice">
                                                    	<option value="" selected>Select Customer Invoice</option>
                                                    	<?php while ($Val = mysqli_fetch_array($CustomerInvoices))
														{
															$ThisInvoiceID = $Val["InvoiceID"];
															$InvNumber = "INV" . $ThisInvoiceID;
															
															if ($ThisInvoiceID == $InvoiceID)
															{
																$Selected = 'selected';	
															}
															else
															{
																$Selected = '';	
															}
															
														?>
                                                        <option value="<?php echo $ThisInvoiceID ?>" <?php echo $Selected ?>><?php echo $InvNumber ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">Manual Job Card Number</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="manualjob" name="manualjob" placeholder="Manual Jobcard Number" value="<?php echo $ManualJobcardNumber ?>">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">Total Time</label>
                                                  <div class="col-sm-6">
                                                    <form action="" class="form-inline">
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                              <span class="input-group-addon">H</span>
                                                              <input type="text" class="form-control" placeholder="Hours" id="hours" value="<?php echo $TotalHours ?>">
                                                            </div>
                                                            <div class="input-group" style="margin-top: 5px; margin-left: -1px">
                                                              <span class="input-group-addon">M</span>
                                                              <input type="text" class="form-control" placeholder="Minutes" id="minutes" value="<?php echo $TotalMinutes ?>">
                                                            </div>
                                                        </div>
                                                        
                                                    </form>
                                                  </div>
                                                </div>
                                               
                                                 <div class="form-group row col-md-6">
                                                  <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">Customer Work Order Number</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="workorder" name="workorder" placeholder="Customer Work Order Number" value="<?php echo $WorkOrder ?>">
                                                  </div>
                                                </div>
                                                
                                                
                                                <!-- END FORM CONTROLS -->
                                                <div class="col-lg-12" align="center" style="padding-top: 20px; padding-bottom: 20px">
                                                	<button class="btn btn-info" onClick="javascript: UpdateJobcard();" style="margin-right: 20px">Update Job Card</button> <?php if ($InvoiceID == "") { ?><a href="javascript: UpdateJobcardInvoice()" class="btn btn-warning" style="margin-right: 20px">Create Invoice</a><?php } ?>
                                                
                                                   
                                            </div>
                                          
                                        
                                        
                                  </div>
                            <!-- /.table-responsive -->
                            
                       
                   
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
           
        </div>
        <!-- /#page-wrapper -->
        <?php } else { ?>
        <h4>You do not have access to this module, if you think this is a mistake please contact your system administrator</h4>
        <?php } ?>
		
    </div>
    
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="vendor/metisMenu/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="vendor/datatables-responsive/dataTables.responsive.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>
    <script src="js/bootbox.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
		
		$("#totaltime").val("<?php echo $TotalTime ?>");
		$("#customerinvoice").val("<?php echo $InvoiceID ?>");
		$("#employee").val("<?php echo $AssignedTo ?>");
		$("#client").val("<?php echo $JobCustomerID ?>");
	
    });
	
	$('.datepicker').datepicker({
    	dateFormat: 'yy-mm-dd',
		minDate: new Date(<?php echo date("Y") ?>, <?php echo date("m") ?> - 1, <?php echo date("d") ?>)
});
	
	//MENU STUFF FOR PAGE
	
	document.getElementById("jobcardmenu").className = 'active';
	document.getElementById("alljobcards").className = 'active';
</script>
</body>

</html>
