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
	$Jobcards = GetAllJobcards(2);
	
	//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
	if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
	else
	{
		$Access = CheckPageAccess('Completed Jobcards');	
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

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<script type="text/javascript">
function DeleteJobcard(JobCardID)
{
	bootbox.confirm("Are you sure you would like to delete this job card? This action cannot be undone!", function(result)
	{ 
		if (result === true)
		{
			var DoDelJob = agent.call('','DeleteJobcard','', JobCardID);
			if (DoDelJob == "OK")
			{
				document.location.reload();
			}
			else
			{
				bootbox.alert(DoDelJob);	
			}
		}
	});
}
</script>
</head>

<body>

    <div id="wrapper">

        <?php include("navigation.php") ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">My Jobcards <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to view all your system job cards.
            </div>    
            <div class="row">
                <div class="col-lg-12">
                <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                <li><a href="jobcards.php"><i class="fa fa-caret-right"></i> Incomplete Job Cards</a>
                                </li>
                                <li><a href="jobcardsinvoicing.php"><i class="fa fa-caret-right"></i> Job Cards Waiting Invoicing</a>
                                </li>
                                <li   class="active"><a href="jobcardscomplete.php"><i class="fa fa-caret-right"></i> Completed Job Cards</a>
                                </li>
                                
                                </li>
                                <li><a href="addjobcard.php"><i class="fa fa-caret-right"></i> Add Job Card</a>
                                </li>
                                
                                
                               
                               
                            </ul>
                     <?php if ($Access == 1) { ?>                
                   <div class="col-lg-12"> 
                             
                             <h4>Job Cards</h4>
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example"  style="font-size: 12px">
                                <thead>
                                    <tr>
                                       
                                        <th style="display: none">Job Card ID</th>
                                        <th>System Job Card #</th>
                                        <th>Manual Job Card #</th>
                                        <th>Work Order #</th>
                                        <th>Customer</th>
                                        <th>Date Added</th>
                                        <th>Added By</th>
                                        
                                        <th>Scheduled</th>
                                        <th>Scheduled For</th>
                                        <th>View</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php while ($Val = mysqli_fetch_array($Jobcards))
									{
										
										$CustomerID = $Val["CustomerID"];
										$Name = $Val["FirstName"];
										$Surname = $Val["Surname"];	
										$CompanyName = $Val["CompanyName"];
										
										//JOBCARD FIELDS
										$JobCardID = $Val["JobcardID"];
										$JobcardNumber = "JBC" . $JobCardID;
										$AssignedTo = $Val["AssignedTo"];
										$ManualJobcardNumber = $Val["ManualJobcardNumber"];
										$WorkOrder = $Val["WorkOrder"];
										
										$AddedBy = $Val["AddedByName"];
										$DateCreated = $Val["DateCreated"];
										$DateScheduled = $Val["DateScheduled"];
										$InvoiceID = $Val["InvoiceID"];
										
										$AssignedTech = GetEmployee($AssignedTo);
										while ($ValEmp = mysqli_fetch_array($AssignedTech))
										{
											$EmpName = $ValEmp["Name"];	
											$EmpSurname = $ValEmp["Surname"];	
											
											$ShowEmployee = $EmpName . " " . $EmpSurname;
										}
										
										
										if ($CompanyName != "")
										{
											$ShowClient = $CompanyName;	
										}
										else
										{
											$ShowClient = $Name . "  " . $Surname;	
										}
										
										
									?>
                                    <tr class="odd gradeX">
                                        
                                         <td style="display: none"><?php echo $JobCardID ?></td>
                                        <td><?php echo $JobcardNumber ?></td>
                                        <td><?php echo $ManualJobcardNumber ?></td>
                                        <td><?php echo $WorkOrder ?></td>
                                        <td><?php echo $ShowClient ?></td>
                                        <td><?php echo $DateCreated ?></td>
                                        <td><?php echo $AddedBy ?></td>
                                        
                                        <td><?php echo $DateScheduled ?></td>
                                        <td><?php echo $ShowEmployee ?></td>
                                        <td class="center"><a href="editjobcard.php?j=<?php echo $JobCardID ?>" 
                                        class="btn btn-sm btn-default">View Job Card</a> <?php if ($_SESSION["MainClient"] == 1) { ?> <a href="javascript: DeleteJobcard(<?php echo $JobCardID ?>)" class="btn btn-sm btn-danger">Delete Job Card</a><?php } ?> <?php if ($InvoiceID != "") { ?> <a href="showcustomerinvoice.php?i=<?php echo $InvoiceID ?>&c=<?php echo $CustomerID ?>" class="btn btn-sm btn-warning">Go to Invoice</a><?php } ?></td>
                                        
                                    </tr>
                                    <?php } ?>
                                    
                                    
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                            
                       </div>
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
            responsive: true,
			"order": [[ 0, "desc" ]]
        });
    });
    </script>

</body>

</html>
