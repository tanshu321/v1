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
	$ProductGroups = GetAllProductGroups();
	
	//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
	if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
	else
	{
		$Access = CheckPageAccess('Product Groups');	
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

function DeleteGroup(ProductGroupID)
{
	bootbox.confirm("Are you sure you would like to delete this group? This action cannot be undone.", 
	function(result)
	{ 
		if (result === true)
		{
			var DoDelGroup = agent.call('','DeleteProductGroup','', ProductGroupID);
			if (DoDelGroup == "OK")
			{
				document.location.reload();
			}
			else
			{
				bootbox.alert(DoDelGroup);	
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
                    <h1 class="page-header">Product Groups <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                
                <!-- /.col-lg-12 -->
            </div>
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to add all your product groups. All products must belong to a group for easy management. To add a new group simply click on the Add Group button below. To view the products within a group please click on the View Group button next to the corresponding group. To delete a group, there must be no products currently in the group. Once no products are linked to the group a Delete Group button will appear.
                            </div>     
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    
					 <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                <li><a href="products.php"><i class="fa fa-caret-right"></i> Products</a>
                                </li>
                                <li  class="active"><a href="productsetup.php"><i class="fa fa-caret-right"></i> Product Groups</a>
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
                             
                             <h4>Product Groups <a href="javascript: AddGroup();" class="btn btn-sm btn-default pull-right" style="margin-bottom: 10px"><i class="fa fa-plus"></i> Add Product Group</a></h4>
                             
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" style="font-size: 12px">
                                <thead>
                                    <tr>
                                       
                                        <th>Group Name</th>
                                        <th>Num Products</th>
                                        <th>Open</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php while ($Val = mysqli_fetch_array($ProductGroups)) 
									{ 
										$GroupName = $Val["GroupName"];
										$ProductGroupID = $Val["ProductGroupID"];
										
										$GroupProducts = GetGroupProducts($ProductGroupID);
										$NumProducts = mysqli_num_rows($GroupProducts);
										
										?>
                                    <tr class="odd gradeX">
                                        
                                        <td><?php echo $GroupName ?></td>
                                        <td><?php echo $NumProducts ?></td>
                                        
                                       
                                        <td class="center"><a href="javascript: EditName('<?php echo $GroupName ?>', '<?php echo $ProductGroupID ?>');" class="btn btn-sm btn-default">Edit Group Name</a> <a href="showproducts.php?g=<?php echo $ProductGroupID ?>" class="btn btn-sm btn-default">View Products</a> <?php if ($NumProducts == 0) { ?><a href="javascript: DeleteGroup(<?php echo $ProductGroupID ?>)" class="btn btn-sm btn-default">Delete Group</a><?php } ?></td>
                                        
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
	
	document.getElementById("stockmenu").className = 'active';
	document.getElementById("setupproductmenu").className = 'active';
    </script>

</body>

</html>
