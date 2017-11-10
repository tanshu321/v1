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
	$ProductGroupID = $_REQUEST["g"];
	$ProductSubGroupID = $_REQUEST["s"];
	
	$ThisGroup = GetProductGroup($ProductGroupID, $ProductSubGroupID);
	
	$GroupProducts = GetGroupProducts($ProductGroupID);
	
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
function AddProduct()
{
	var ProductName = document.getElementById("productname").value;
	var IsStock = document.getElementById("stockitem").value;
	var ProductStatus = document.getElementById("productstatus").value;
	
	if (ProductName != "" && IsStock != "" && ProductStatus != "")
	{
		var AddProduct = agent.call('','AddProduct','', ProductName, IsStock, ProductStatus, '<?php echo $ProductGroupID ?>');
		
		if (AddProduct != "Error")
		{
			document.location = "editproduct.php?p=" + AddProduct + "&g=<?php echo $ProductGroupID ?>";
		}
		else
		{
			bootobox.alert("There was an error adding the product, please check your values and try again");
		}
		
	}
	else
	{
		bootbox.alert("Please enter all the information to save this product");	
	}
}

function CheckStockItem()
{
	var IsStock = document.getElementById("stockitem").value;
	var BillingType = document.getElementById("billingtype").value;
	
	if (BillingType != "Once-Off")
	{
		if (IsStock == 0)
		{
			document.getElementById("prorata").disabled = false;	
		}
		else
		{
			document.getElementById("prorata").disabled = true;	
			document.getElementById("prorata").selectedIndex = 0;	
		}
	}
	else
	{
		document.getElementById("prorata").disabled = true;	
		document.getElementById("prorata").selectedIndex = 0;	
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
                    <h1 class="page-header"><?php echo $ThisGroup ?> Products <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    
					
                   
                        
                        <div class="col-lg-3">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                               <strong><i class="fa fa-upload fa-fw"></i> Add Product</strong>
                                          </div>
                                            <!-- /.panel-heading -->
                                            <div class="panel-body"><!-- /.table-responsive -->
                                            	
                                                <div class="form-group row col-md-12">
                                                  <label for="city" class="col-sm-12 col-form-label" style="padding-top: 5px">Product Name *</label>
                                                  <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="productname" name="productname" placeholder="Product Name" value="">
                                                  </div>
                                                </div>
                                                
                                               
                                                <div class="form-group row col-md-12">
                                                  <label for="city" class="col-sm-12 col-form-label" style="padding-top: 5px">Stock Item *</label>
                                                  <div class="col-sm-12">
                                                  	<select class="form-control" id="stockitem">
                                                    	<option value="">Please Select</option>
                                                        <option value="0">No (Virtual Product)</option>
                                                        <option value="1">Yes (Physical Product)</option>
                                                    </select>
                                                  </div>
                                                </div>
                                                
                                               
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="city" class="col-sm-12 col-form-label" style="padding-top: 5px">Product Status *</label>
                                                  <div class="col-sm-12">
                                                  	<select class="form-control" id="productstatus">
                                                    	
                                                        <option value="1">Inactive</option>
                                                        <option value="2" selected>Active</option>
                                                    </select>
                                                  </div>
                                                </div>
                                                
                                                
                                                
                                                <div class="form-group row col-md-12" style="padding-top: 10px">
                                                  <button class="btn btn-info pull-right col-md-12" onClick="javascript: AddProduct();">Add Product</button>
                                                </div>
                                               
                                          </div>
                                            <!-- /.panel-body -->
                                        </div>
                                        <!-- /.panel -->
                                    </div>
                                    
                             <div class="col-lg-9">      
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" style="font-size: 12px">
                                <thead>
                                    <tr>
                                       
                                        <th>Product Name</th>
                                       
                                        <th>Is Stock Item</th>
                                        
                                        <th>Status</th>
                                        <th>Edit Product</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php while ($Val = mysqli_fetch_array($GroupProducts)) 
									{ 
										$ProductID = $Val["ProductID"];
										$ProductName = $Val["ProductName"];
										$ProductBilling = $Val["ProductBilling"];
										$IsStockItem = $Val["IsStockItem"];
										$ProRataBilling = $Val["ProRataBilling"];
										$ProductStatus = $Val["ProductStatus"];
										
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
										
										if ($IsStockItem == 1)
										{
											$ShowStock = '<i class="fa fa-check fa-fw" style="color: green"></i>';
										}
										else
										{
											$ShowStock = '<i class="fa fa-close fa-fw" style="color: red"></i>';
										}
										
										if ($ProRataBilling == 1)
										{
											$ShowProRata = '<i class="fa fa-check fa-fw" style="color: green"></i>';
										}
										else
										{
											$ShowProRata = '<i class="fa fa-close fa-fw" style="color: red"></i>';
										}
										
										?>
                                    <tr class="odd gradeX">
                                        
                                        <td><?php echo $ProductName ?></td>
                                        
                                        <td><?php echo $ShowStock ?></td>
                                        
                                        <td><?php echo $ShowStatus ?></td>
                                        
                                       
                                        <td class="center"><a href="editproduct.php?g=<?php echo $ProductGroupID ?>&p=<?php echo $ProductID ?>" class="btn btn-sm btn-default">Edit</a></td>
                                        
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
            responsive: true
        });
    });
	
	//MENU STUFF FOR PAGE
	document.getElementById("setupmenu").className = 'active';
	document.getElementById("setupproductmenu").className = 'active';
    </script>

</body>

</html>
