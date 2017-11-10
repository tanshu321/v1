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
	
	$AddToClient = $_REQUEST["c"];
	
	if ($AddToClient != "")
	{
		$Sites = GetCustomerSites($AddToClient);	
	}
	
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


function AddJobcard()
{
	var Customer = document.getElementById("client").value;
	var Employee = document.getElementById("employee").value;	
	var ScheduledDate = document.getElementById("datepicker").value;
	var Notes = document.getElementById("notes").value;
	var Site = document.getElementById("site").value;
	var WorkOrder = document.getElementById("workorder").value;
	
	
	if (Customer != "" && Employee != "" && ScheduledDate != "" && Site != "")
	{
		var AddJobcardDetails = agent.call('','AddJobcard','', Customer, Employee, ScheduledDate, Notes, Site, WorkOrder);
		if (AddJobcardDetails > 0)
		{
			//LOG EVENT
			var Log = agent.call('','CreateClientAccess', '', Customer, 'Added Jobcard JBC' + AddJobcardDetails);
			document.location = 'editjobcard.php?j=' + AddJobcardDetails;	
			
		}
		else
		{
			bootbox.alert(AddJobcardDetails);	
		}
	}
	else
	{
		bootbox.alert("Please fill in all fields marked with a *");
	}
}

function GetSites()
{
	var Customer = document.getElementById("client").value;
	document.getElementById('site').options.length = 0;
	if (Customer != "")
	{
		var SubGroups = agent.call('','GetCustomerSitesArray','', Customer);	
		if (SubGroups != "")
		{
			
			AddSubGroup('Head Office',0);
			var SubGroupArray = SubGroups.split(":::");
			
			for (i = 0; i < SubGroupArray.length; i++) 
			{
				var ThisLine = SubGroupArray[i];
				var ThisLineArray = ThisLine.split("---");
				var ThisID = ThisLineArray[0];
				var ThisValue = ThisLineArray[1];
				
				AddSubGroup(ThisValue, ThisID);
			}
			
			document.getElementById("site").disabled = false;	
		}
		else
		{
			document.getElementById("site").disabled = true;	
			AddSubGroup('Head Office',0);
		}
	}
	else
	{
		AddSubGroup('Head Office',0);
	}
}

function AddSubGroup(Text, Value)
{
	var SubGroup = document.getElementById("site");
	var opt = document.createElement("option");
	SubGroup.options.add(opt);
    opt.text = Text;
    opt.value = Value;
}

</script>
</head>

<body>

    <div id="wrapper">

        <?php include("navigation.php") ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Add Jobcard <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                
                <!-- /.col-lg-12 -->
            </div>
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to add a new jobcard
                            </div>     
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    
					 <!-- Nav tabs -->
                           <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                <li><a href="jobcards.php"><i class="fa fa-caret-right"></i> Incomplete Jobcards</a>
                                </li>
                                <li><a href="jobcardsinvoicing.php"><i class="fa fa-caret-right"></i> Jobcards Waiting Invoicing</a>
                                </li>
                                <li><a href="jobcardscomplete.php"><i class="fa fa-caret-right"></i> Completed Jobcards</a>
                                </li>
                                
                                </li>
                                <li  class="active"><a href="addjobcard.php"><i class="fa fa-caret-right"></i> Add Jobcard</a>
                                </li>
                                
                                
                               
                               
                            </ul>
                  
                        
                        <?php if ($Access == 1) { ?>            
                                    
                             <div class="col-lg-12" style="padding-top: 10px">
                                        <h4 style="padding-bottom: 10px">Jobcard Details</h4>
                                                <div class="form-group row col-md-6">
                                                  <label for="firstname" class="col-sm-5 col-form-label" style="padding-top: 5px">Select Customer *</label>
                                                  <div class="col-sm-6">
                                                    <select class="form-control" id="client" onChange="javascript: GetSites();">
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
															
															if ($AddToClient == $CustomerID)
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
                                                    	<option value="0" selected>Head Office</option>
                                                        <?php while ($Val = mysqli_fetch_array($Sites))
														{
															$SiteID = $Val["SiteID"];
															$SiteName = $Val["SiteName"];
															
														?>
                                                        <option value="<?php echo $SiteID ?>"><?php echo $SiteName ?></option>
                                                        <?php } ?>
                                                    	
                                                    </select>
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="surname" class="col-sm-5 col-form-label" style="padding-top: 5px">Assign To Employee *</label>
                                                  <div class="col-sm-6">
                                                    <select class="form-control" id="employee">
                                                    	<option value="" selected>Select Employee</option>
                                                    	<?php while ($Val = mysqli_fetch_array($Employees))
														{
															$EmployeeID = $Val["EmployeeID"];
															$Name = $Val["Name"];
															$Surname = $Val["Surname"];	
															
														?>
                                                        <option value="<?php echo $EmployeeID ?>"><?php echo $Name ?> <?php echo $Surname ?></option>
                                                        <?php } ?>
                                                    </select>
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">Schedule for *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control datepicker" id="datepicker" name="datepicker" placeholder="Schedule Date"  data-date-format="yyyy-mm-dd" value="">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="tel" class="col-sm-5 col-form-label" style="padding-top: 5px">Jobcard Notes/Fault Description</label>
                                                  <div class="col-sm-6">
                                                  	<textarea class="form-control" id="notes"></textarea>
                                                    
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">Customer Work Order Number</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="workorder" name="workorder" placeholder="Customer Work Order Number" value="">
                                                  </div>
                                                </div>
                                                
                                                <!-- END FORM CONTROLS -->
                                                <div class="col-lg-12" align="center" style="padding-top: 20px; padding-bottom: 20px">
                                                	<button class="btn btn-info" onClick="javascript: AddJobcard();" style="margin-right: 20px">Add Jobcard</button> 
                                                
                                                   
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
