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
	$Products = GetAllProducts();
	
	//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
	if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
	else
	{
		$Access = CheckPageAccess('System Products');	
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
function AddGroup()
{
	bootbox.prompt("Please enter the new group name", function(result)
	{ 
		var NewGroup = result;
		
		if (NewGroup != "" && NewGroup != null)
		{
			var AddProductGroup = agent.call('','AddProductGroup','', NewGroup);
		
			if (AddProductGroup == "OK")
			{
				document.location.reload();
			}
			else
			{
				bootobox.alert(AddProductGroup);
			}
		}
	});
}

function EditName(GroupName, ProductGroupID)
{
	bootbox.prompt({
	  title: "Please change the current group name below",
	  value: GroupName,
	  callback: function(result) 
	  {
		var NewGroup = result;
		
		if (NewGroup != "" && NewGroup != null)
		{
			var AddProductGroup = agent.call('','UpdateProductGroup','', NewGroup, ProductGroupID);
		
			if (AddProductGroup == "OK")
			{
				document.location.reload();
			}
			else
			{
				bootobox.alert(AddProductGroup);
			}
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
                    <h1 class="page-header">Products <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                
                <!-- /.col-lg-12 -->
            </div>
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to add all your products. Please make sure you have completed the setup of your product groups, sub groups, units of meassure as well as any custom fields you require for your business.
                            </div>     
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    
					 <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                <li class="active"><a href="products.php"><i class="fa fa-caret-right"></i> Products</a>
                                </li>
                                <li><a href="productsetup.php"><i class="fa fa-caret-right"></i> Product Groups</a>
                                </li>
                                <li><a href="productsubgroups.php"><i class="fa fa-caret-right"></i> Product Sub Groups</a>
                                </li>
                                <li><a href="productmeassures.php"><i class="fa fa-caret-right"></i> Units of Meassure</a>
                                </li>
                                <li><a href="productcustomfields.php"><i class="fa fa-caret-right"></i> Custom Product Fields</a>
                                </li>
                                
                               
                               
                            </ul>
                  
                        
                        
                               <?php if ($Access == 1) { ?>            
                             <div class="col-lg-12"> 
                             
                             <h4>Products <a href="addproduct.php" class="btn btn-sm btn-default pull-right" style="margin-bottom: 10px"><i class="fa fa-plus"></i> Add Product</a></h4>
                             
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" style="font-size: 12px">
                                <thead>
                                    <tr>
                                       
                                        <th>Product Name</th>
                                        <th>Product Group</th>
                                        <th>Status</th>
                                        <th>Edit</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php while ($Val = mysqli_fetch_array($Products)) 
									{ 
										$ProductID = $Val["ProductID"];
										$ProductName = $Val["ProductName"];
										
										$ProductGroupID = $Val["ProductGroupID"];
										$ProductSubGroupID = $Val["ProductSubGroupID"];
										$ProductStatus = $Val["ProductStatus"];
										
										if ($ProductGroupID != "")
										{
											$ShowGroup = GetThisProductGroup($ProductGroupID, $ProductSubGroupID);
										}
										else
										{
											$ShowGroup = "None";	
										}
										
										
										if ($ProductStatus == 2)
										{
											//ACTIVE	
											$ShowStatus = '<span class="label label-success">Active</span>';
										}
										else
										{
											//INACTIVE	
											$ShowStatus = '<span class="label label-danger">Inactive</span>';
										}
										
										?>
                                    <tr class="odd gradeX">
                                        
                                        <td><?php echo $ProductName ?></td>
                                        <td><?php echo $ShowGroup ?></td>
                                        <td><?php echo $ShowStatus ?></td>
                                        
                                       
                                        <td class="center"><a href="editproduct.php?p=<?php echo $ProductID ?>" class="btn btn-sm btn-default">Edit Product</a></td>
                                        
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
            responsive: true
        });
    });
	
	//MENU STUFF FOR PAGE
	document.getElementById("stockmenu").className = 'active';
	document.getElementById("setupproductmenu").className = 'active';
    </script>

</body>

</html>
