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
	$Warehouses = GetAllWarehouses();
	
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


function EditWarehouseName(WarehouseID, WarehouseName)
{
	bootbox.prompt("Please enter the new name for warehouse " + WarehouseName , 
	function(result)
	{ 
		if (result != "" && result != null)
		{
			var NewName = result;
			var DoUpdateName = agent.call('','UpdateWarehouseName','', WarehouseID, NewName);
			if (DoUpdateName == "OK")
			{
				document.location.reload();	
			}
			else
			{
				bootbox.alert(DoUpdateName);
			}
		}
	});
}

function AddWarehouse()
{
	bootbox.prompt("Please enter the new warehouse name", 
	function(result)
	{ 
		if (result != "" && result != null)
		{
			var NewName = result;
			var DoAddWarehouse = agent.call('','AddWarehouse','', NewName);
			if (DoAddWarehouse == "OK")
			{
				document.location.reload();	
			}
			else
			{
				bootbox.alert(DoAddWarehouse);
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
                    <h1 class="page-header">Warehouses<img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
            
                <div class="col-lg-12">
                  <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to add your warehouses for your stock. To add a warehouse simply click on the Add Warehouse button below</div>     
					
                   
                        
                        
                                    
                             <div class="col-lg-12">   
                             <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                <li><a href="stockcontrol.php"><i class="fa fa-caret-right"></i> System Stock</a>
                                </li>
                                <li  class="active"><a href="warehouses.php"><i class="fa fa-caret-right"></i> Warehouses</a>
                                
                                
                               
                               
                            </ul>
                             <h4>Current Warehouses<a href="javascript: AddWarehouse();" class="btn btn-default pull-right" style="margin-bottom: 10px" ><i class="fa fa-plus"></i> Add Warehouse</a></h4>   
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" style="font-size: 12px">
                                <thead>
                                    <tr>
                                        <th>Warehouse ID</th>
                                        <th>Warehouse Name</th>
                                        

                                        <th>Edit</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php while ($Val = mysqli_fetch_array($Warehouses)) 
									{ 
										$WarehouseID = $Val["WarehouseID"];
										$WarehouseName = $Val["WarehouseName"];
										
										
										
										?>
                                    <tr class="odd gradeX">
                                        
                                        <td><?php echo $WarehouseID ?></td>
                                        <td><?php echo $WarehouseName ?></td>
                                        
                                        <td class="center"><a href="javascript: EditWarehouseName(<?php echo $WarehouseID ?>, '<?php echo $WarehouseName ?>')" class="btn btn-sm btn-default">Edit Name</a></td>
                                        
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
    <script src="js/bootbox.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true,
			"order": [[ 0, "asc" ]]
        });
    });
	
	document.getElementById("stockmenu").className = 'active';
	document.getElementById("stockcontrolrmenu").className = 'active';
    </script>

</body>

</html>
