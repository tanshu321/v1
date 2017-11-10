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
	$Task = GetAllTask();
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

</script>
</head>

<body>

    <div id="wrapper">

        <?php include("navigation.php") ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Task</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to get an overview of all task captured in the system.
                            </div>     
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                 <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                
                                
                                <li class="pull-right"><a href="dashboard.php"><i class="fa fa-caret-left"></i> Back to Dashboard</a>
                                </li>
                                
                               
                               
                            </ul>
                   
                           
                           
                    
                        <!-- /.panel-heading -->
                        

                            <!-- Tab panes -->
                          
                               
                                    <!-- End Table -->
                                    <!-- First Panel Table -->
                                    <div class="col-lg-12" style="padding-top: 10px">
                                    
                                     <h4>Customer Task</h4>
                                      
                                          
                                                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" style="font-size: 12px">
                                                    <thead>
                                                        <tr>
                                                            <th>Customer</th>
                                                            <th>Task</th>
                                                            <th>Scheduled For</th>
                                                            <th>Added</th>
                                                            <th>Added By</th>
                                                            <th>Status</th>
                                                            <th>Actions</th>
                                                            
                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while ($Val = mysqli_fetch_array($Task))
                                                        {
                                                            $TaskID = $Val["TaskID"];
                                                            $TaskDescription = $Val["TaskDescription"];
                                                            $TaskDate = $Val["TaskDate"];	
															$AddedBy = $Val["AddedByName"];
															$DateAdded = $Val["DateAdded"];
															$Status = $Val["Status"];
															
															
															if ($Status != 1)
															{
																//MUST STILL BE COMPLETED
																$Status = '<i class="fa fa-close fa-fw" style="color: red"></i>';
															}
															else
															{
																//TASK COMPLETED
																$Status = '<i class="fa fa-check fa-fw" style="color: green"></i>';
															}
															
															$CustomerName = $Val["FirstName"] . " " . $Val["Surname"];
															$CompanyName = $Val["CompanyName"];
															$CustomerID = $Val["CustomerID"];
															
															if ($CompanyName != "")
															{
																$CustomerName .= " (" . $CompanyName . ")";	
															}
															
                                                           
                                                            
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $CustomerName ?></td>
                                                            <td><?php echo $TaskDescription ?></td>
                                                            <td><?php echo $TaskDate ?></td>
                                                            <td><?php echo $DateAdded ?></td>
                                                            <td><?php echo $AddedBy ?></td>
                                                            <td><?php echo $Status ?></td>
                                                            <td><a href="edittask.php?t=<?php echo $TaskID ?>&c=<?php echo $CustomerID ?>" class="btn btn-sm btn-default">Update Task</a></td>
                                                           
                                                            
                                                        </tr>
                                                        <?php } ?>
                                                        
                                                        
                                                    </tbody>
                                                </table>
                                                <!-- /.table-responsive -->
                                                
                                         
                                    
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
          </div> 
        
        <!-- /#page-wrapper -->
		
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
    <script src="js/bootbox.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true,
			"order": [[ 2, "desc" ]]
        });
    });
	
	document.getElementById("dashboard").className = 'active';
    </script>

</body>

</html>
