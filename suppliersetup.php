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
	$Suppliers = GetAllSuppliers();
	
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
function AddGroup()
{
	var GroupName = document.getElementById("groupname").value;
	if (GroupName != "")
	{
		var AddProductGroup = agent.call('','AddProductGroup','', GroupName);
		
		if (AddProductGroup == "OK")
		{
			document.location.reload();
		}
		else
		{
			bootobox.alert(AddProductGroup);
		}
		
	}
	else
	{
		bootbox.alert("Please enter the name for the new product group");	
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
                    <h1 class="page-header">Supplier Management <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
            
                <div class="col-lg-12">
                  <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to add all your supplier information. To add a new supplier simply click on the Add Supplier button below.
                            </div>     
					
                   
                        
                        
                                    
                             <div class="col-lg-12">   
                             <h4>Current Suppliers <a href="addsupplier.php" class="btn btn-default pull-right" style="margin-bottom: 10px" ><i class="fa fa-plus"></i> Add Supplier</a></h4>   
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" style="font-size: 12px">
                                <thead>
                                    <tr>
                                       
                                        <th>Supplier Name</th>
                                        <th>Tel #</th>
                                        <th>Fax #</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>View</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php while ($Val = mysqli_fetch_array($Suppliers)) 
									{ 
										$SupplierID = $Val["SupplierID"];
										$SupplierName = $Val["SupplierName"];
										$SupplierTel = $Val["SupplierTel"];
										$SupplierFax = $Val["SupplierFax"];
										$SupplierContact = $Val["SupplierContact"];
										$SupplierEmail = $Val["SupplierEmail"];
										$SupplierStatus = $Val["SupplierStatus"];
										
										if ($SupplierStatus == 1)
										{
											$ShowStatus = '<i class="fa fa-check fa-fw" style="color: green"></i>';
										}
										else
										{
											$ShowStatus = '<i class="fa fa-close fa-fw" style="color: red"></i>';
										}
										
										
										
										?>
                                    <tr class="odd gradeX">
                                        
                                        <td><?php echo $SupplierName ?></td>
                                        <td><?php echo $SupplierTel ?></td>
                                        <td><?php echo $SupplierFax ?></td>
                                        <td><a href="mailto: <?php echo $SupplierEmail ?>"><?php echo $SupplierEmail ?></a></td>
                                        <td><?php echo $ShowStatus ?></td>
                                        
                                       
                                        <td class="center"><a href="showsupplier.php?s=<?php echo $SupplierID ?>" class="btn btn-sm btn-default">View Supplier Details</a></td>
                                        
                                    </tr>
                                    <?php } ?>
                                    
                                    
                                </tbody>
                            </table>
                            </div>
                            <!-- /.table-responsive -->
                            
                        
                   
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
            responsive: true,
			"order": [[ 0, "asc" ]]
        });
    });
    </script>

</body>

</html>
