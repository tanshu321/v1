<?php
include("includes/webfunctions.php");
include('includes/agent.php');
$agent->init();


//SECURITY
//SECURITY
$ThisClientID = $_SESSION["ClientID"];
$ThisUser = $_SESSION["ClientName"];
$ThisSecret = $_SESSION["SiteSecret"];

if ($ThisClientID > 0 && $ThisUser != "" && $ThisSecret == "E2A_crm_S5gdbh6nnj_usr_9898")
{
	$SupplierID = $_REQUEST["s"];
	$SupplierDetails = GetSupplierDetails($SupplierID);
	
	while ($Val = mysqli_fetch_array($SupplierDetails))
	{
		$SupplierName = $Val["SupplierName"];
		$SupplierEmail = $Val["SupplierEmail"];
		$SupplierTel = $Val["SupplierTel"];
		$SupplierFax = $Val["SupplierFax"];
		$SupplierContact = $Val["SupplierContact"];
		$SupplierVat = $Val["SupplierVat"];
		$SupplierAddress1 = $Val["SupplierAddress1"];
		$SupplierAddress2 = $Val["SupplierAddress2"];
		$City = $Val["City"];
		$State = $Val["State"];
		$PostCode = $Val["PostCode"];
		$ThisCountryID = $Val["CountryID"];
		$SupplierNote = $Val["SupplierNote"];
		$SupplierStatus = $Val["SupplierStatus"];
		$ChargesVAT = $Val["ChargesVAT"];
		
		if ($SupplierStatus == 1)
		{
			//ACTIVE	
			$ShowStatus = '<span class="label label-success" style="font-size: 14px">Active</span>';
		}
		else
		{
			//INACTIVE	
			$ShowStatus = '<span class="label label-danger" style="font-size: 14px">Inactive</span>';
		}
	}
	
	$Countries = GetCountries();
	$ProductGroups = GetAllActiveProductGroups();
	
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
function GetGroupProducts()
{
	var SelectedGroup = document.getElementById("productgroup").value;
	document.getElementById("product").options.length = 0;
	
		
	if (SelectedGroup != "")
	{
		var GetGroupProducts = agent.call('','GetGroupProductsArrayQuote','', SelectedGroup);
		
		AddProduct('Please Select', '');
		
		//WE SHOULD HAVE ARRAY BACK
		for (i = 0; i < GetGroupProducts.length; i++) 
		{
			var ProductID = GetGroupProducts[i][0];
			var ProductName = GetGroupProducts[i][1];
			var ProductCode = GetGroupProducts[i][2];
			
			
			var Product = ProductCode + " - " + ProductName;
			
			AddProduct(Product, ProductID);
			
		}
		
	}
}

function AddProduct(Text, Value)
{
	var ProductBox = document.getElementById("product");
	var opt = document.createElement("option");
	ProductBox.options.add(opt);
    opt.text = Text;
    opt.value = Value;
}

function LinkProduct()
{
	var ProductID = document.getElementById("product").value;
	
	if (ProductID != "")
	{
		var DoLinkProduct = agent.call('','LinkSupplierProduct','', ProductID, '<?php echo $SupplierID ?>');
		if (DoLinkProduct == "OK")
		{
			document.location = 'supplierproducts.php?s=<?php echo $SupplierID ?>';
		}
		else
		{
			bootbox.alert(DoLinkProduct);
		}
	}
	else
	{
		bootbox.alert("Please select the product to link to the supplier");	
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
                
                    <h1 class="page-header">Link Supplier Product - <?php echo $SupplierName ?> <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
            
                <div class="col-lg-12">
                <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to link products to your supplier
                            </div>  
                 <!-- Nav tabs -->
                            <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                
                                <li ><a href="showsupplier.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Supplier Details</a>
                                </li>
                                <li class="active"><a href="supplierproducts.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Products</a>
                                </li>
                                <li><a href="supplierinvoices.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Invoices</a>
                                </li>
                                <li><a href="supplierpo.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Purchase Orders</a>
                                </li>
                                 <li class="pull-right"><a href="suppliersetup.php"><i class="fa fa-chevron-left"></i> Back to All Suppliers</a>
                                </li>
                               
                               
                            </ul>
                   

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="home" style="padding: 10px; ">
                                    <!-- Start Inside Tab -->
                                    <!-- First Panel Table -->
                                    <div class="col-lg-6" style="padding-top: 10px">
                             
                                       
                                                
                                                 <div class="clearfix"></div>
                                                <h4 style="padding-bottom: 10px">Link Product to Supplier</h4>
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="productgroup" class="col-sm-3 col-form-label" style="padding-top: 5px">Product Group *</label>
                                                  <div class="col-sm-6">
                                                    <select class="form-control" id="productgroup" onChange="javascript: GetGroupProducts();"> 
                                                        <option value="" selected>Please select</option>
                                                        <?php while ($Val = mysqli_fetch_array($ProductGroups))
                                                        {
                                                            $ProductGroupID = $Val["ProductGroupID"];
                                                            $ProductGroup = $Val["GroupName"];
                                                            
                                                            
                                                            
                                                        ?>
                                                            <option value="<?php echo $ProductGroupID ?>"><?php echo $ProductGroup ?></option>
                                                        <?php
                                                        }?>
                                                        
                                                        </select>
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="product" class="col-sm-3 col-form-label" style="padding-top: 5px">Product *</label>
                                                  <div class="col-sm-6">
                                                    <select class="form-control" id="product"> 
                                                        <option value="" selected>Please select</option>
                                                       
                                                        </select>
                                                  </div>
                                                </div>
                                                
                                                 
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px"></label>
                                                  <div class="col-sm-4">
                                                    <button class="btn btn-info pull-right" onClick="javascript: LinkProduct();">Link Product</button>
                                                  </div>
                                                </div>
                                                
                                                
                                               
                                                
                                               
                                                
                                                <!-- END FORM CONTROLS -->
                                                
                                          
                                        
                                    
                                  </div>
                                                    <div class="box-footer" align="right">
                                        
                                                    <button type="button" class="btn btn-primary" onClick="javascript: UpdateSupplier();">Update Details</button>
                                                 </div>
                                                <!-- END FORM CONTROLS -->
                                            </div>
                                            
                                        
                                    
                                    
                                   
                                   
                                    <!-- End inside tab -->
                                </div>
                                
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
    
    <script src="js/bootbox.js"></script>

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
    
    <script type="text/javascript">
	//MENU STUFF FOR PAGE
	document.getElementById("setupmenu").className = 'active';
	document.getElementById("setupsuppliermenu").className = 'active';
	</script>

</body>

</html>
