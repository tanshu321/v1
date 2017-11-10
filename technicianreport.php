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
	
	$FromDate = $_REQUEST["from"];
	$ToDate = $_REQUEST["to"];
	
	if ($FromDate == "")
	{
		$FromDate = date("Y-m-d", mktime(0,0,0, date("m"), 1, date("Y")));
		$ToDate = date("Y-m-d");	
	}
	
	//FIRST WE NEED TO GET ALL TECHNICIANS
	$GetTechs = GetTechnicians();
	
	//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
	if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
	else
	{
		$Access = CheckPageAccess('Technician Report');	
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
function UpdateJobcardStatus()
{
	
	var FromDate = document.getElementById("datepicker").value;
	var ToDate = document.getElementById("datepicker2").value;
	
	document.location = "technicianreport.php?from=" + FromDate + "&to=" + ToDate;
}
</script>
</head>

<body>

    <div id="wrapper">

        <?php include("navigation.php") ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Technician Report <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> Below you will find the technician jobcard report</div>    
            <div class="row">
                <div class="col-lg-12">
                
                     <?php if ($Access == 1) { ?>               
                   <div class="col-lg-12"> 
                             
                             <h4>Technician Report</h4>
                             
                             <h5 class="pull-right form-inline">
                            Jobs between <input type="text" class="form-control datepicker form-inline" id="datepicker" name="datepicker" placeholder="From"  data-date-format="yyyy-mm-dd" value="<?php echo $FromDate ?>"> to <input type="text" class="form-control datepicker form-inline" id="datepicker2" name="datepicker2" placeholder="To"  data-date-format="yyyy-mm-dd" value="<?php echo $ToDate ?>">
                                                
                                                <button class="btn btn-default form-inline" onClick="javascript: UpdateJobcardStatus();">Update Filter</button>
                            &nbsp;&nbsp;<a href="techreportpdf.php?from=<?php echo $FromDate ?>&to=<?php echo $ToDate ?>" class="btn btn-sm btn-default pull-right" style="margin-bottom: 10px" target="_blank"><i class="fa fa-print"></i> Print Report</a>
                            </h5>
                            
                            
                            <div class="col-md-12" style="padding-bottom: 10px; margin-left: -15px"></div>
                            <table width="100%" class="table table-striped table-bordered table-hover"  id="dataTables-example"  style="font-size: 12px">
                                <thead>
                                    <tr>
                                        <th>Technician Name</th>
                                        <th>Scheduled for Period</th>
                                        <th>Incomplete</th>
                                        <th>Waiting Invoice</th>
                                        <th>Completed</th>
                                        
                                        <th>Recorded Hours</th>
                                        <th>Invoiced Amount</th>
                                        
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php while ($Val = mysqli_fetch_array($GetTechs))
									{
										
										$AssignedTo = $Val["AssignedTo"];
										$AssignedTech = GetEmployee($AssignedTo);
										while ($ValEmp = mysqli_fetch_array($AssignedTech))
										{
											$EmpName = $ValEmp["Name"];	
											$EmpSurname = $ValEmp["Surname"];	
											
											$ShowEmployee = $EmpName . " " . $EmpSurname;
										}
										
										//HERE COMES THE SMARTNESS
										$TotalJobsPeriod = GetTotalJobs($AssignedTo, $FromDate, $ToDate);
										$IncomepletePeriod = GetTotalIncompleteJobs($AssignedTo, $FromDate, $ToDate);
										$WaitingInvoice = GetTotalWaitingJobs($AssignedTo, $FromDate, $ToDate);
										$CompletedJobs = GetTotalCompletedJobs($AssignedTo, $FromDate, $ToDate);
										$RecordedHours = GetTotalHours($AssignedTo, $FromDate, $ToDate);
										
										$IncomeFromJobs = GetTotalIncomeFromJobs($AssignedTo, $FromDate, $ToDate);
										
										
										
										
										
									?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $ShowEmployee ?></td>
                                        <td><?php echo $TotalJobsPeriod ?></td>
                                        <td><?php echo $IncomepletePeriod ?></td>
                                        <td><?php echo $WaitingInvoice ?></td>
                                        <td><?php echo $CompletedJobs ?></td>
                                        
                                        <td><?php echo $RecordedHours ?></td>
                                        <td>R<?php echo number_format($IncomeFromJobs,2) ?></td>
                                       
                                       
                                        
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
    
	
	document.getElementById("reports").className = 'active';
	document.getElementById("techreport").className = 'active';
    </script>
    
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true,
			pageLength: 100,
			"order": [[ 0, "asc" ]]
        });
    });
	
	$('.datepicker').datepicker({
    	dateFormat: 'yy-mm-dd'
	});
	
	$('.datepicker2').datepicker({
			dateFormat: 'yy-mm-dd'
			
	});
    </script>

</body>

</html>
