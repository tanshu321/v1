<?php
include("includes/webfunctions.php");


//SECURITY
$ThisClientID = $_SESSION["ClientID"];
$ThisUser = $_SESSION["ClientName"];
$ThisSecret = $_SESSION["SiteSecret"];

if ($ThisClientID > 0 && $ThisUser != "" && $ThisSecret == "E2A_crm_S5gdbh6nnj_usr_9898")
{
	$Clients = GetAllClients();
	
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

</head>

<body>

    <div id="wrapper">

        <?php include("navigation.php") ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">My Customers <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to view all your customers.
            </div>    
            <div class="row">
                <div class="col-lg-12">
                <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                <li class="active"><a href="showclients.php"><i class="fa fa-caret-right"></i> My Customers</a>
                                </li>
                                <li><a href="addclient.php"><i class="fa fa-caret-right"></i> Add Customer</a>
                                </li>
                                
                                
                               
                               
                            </ul>
                            
                   <div class="col-lg-12"> 
                             
                             <h4>Customers</h4>
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                       <th>Company Name</th>
                                        <th>First Name</th>
                                        <th>Surname</th>
                                        
                                        <th>Email Address</th>
                                        
                                        <th>Created</th>
                                        <th>Status</th>
                                        <th>View</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php while ($Val = mysqli_fetch_array($Clients))
									{
										$ClientID = $Val["ClientID"];
										$CustomerID = $Val["CustomerID"];
										$Name = $Val["FirstName"];
										$Surname = $Val["Surname"];	
										$CompanyName = $Val["CompanyName"];
										$EmailAddress = $Val["EmailAddress"];
										$DateAdded = $Val["DateAdded"];
										
										$Status = $Val["Status"];
										if ($Status == 2)
										{
											//ACTIVE	
											$ShowStatus = '<span class="label label-success">Active</span>';
										}
										else
										{
											//INACTIVE	
											$ShowStatus = '<span class="label label-danger">Inactive</span>';
										}
										
										//$ClientPackagesActive = GetCountClientPackages(2, $ClientID);
										//$ClientPackagesInactive = GetCountClientPackages(1, $ClientID);
										
										$TotalPackages = $ClientPackagesActive + $ClientPackagesInactive;
									?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $CompanyName ?></td>
                                        <td><?php echo $Name ?></td>
                                        <td><?php echo $Surname ?></td>
                                        
                                        <td><?php echo $EmailAddress ?></td>
                                        
                                        <td><?php echo $DateAdded ?></td>
                                        <td class="center"><?php echo $ShowStatus ?></td>
                                        <td class="center"><a href="clientinfo.php?c=<?php echo $CustomerID ?>" class="btn btn-sm btn-default">View Client</a></td>
                                        
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

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
    </script>

</body>

</html>
