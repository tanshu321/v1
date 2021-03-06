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
	$Suppliers = GetAllSuppliers();
	$Measurrements = GetAllMeasurements();
	$ProductCustomFields = GetProductCustomFields();
	$HasCustom = mysqli_num_rows($ProductCustomFields);
	
	$ProductID = $_REQUEST["p"];
	$ProductInfo = GetProductInfo($ProductID);
	
	while ($Val = mysqli_fetch_array($ProductInfo))
	{
		$ProductName = $Val["ProductName"];
		$ThisProductGroupID = $Val["ProductGroupID"];
		$ThisProductSubGroupID = $Val["ProductSubGroupID"];
		$IsStockItem = $Val["IsStockItem"];
		$ThisSupplierID = $Val["SupplierID"];
		$TaxableItem = $Val["TaxableItem"];
		$MinimumStock = $Val["MinimumStock"];
		$ProductCode = $Val["ProductCode"];
		$ProductDescription = $Val["ProductDescription"];
		$MinimumOrder = $Val["MinimumOrder"];
		$ShowInCatalog = $Val["ShowInCatalog"];
		$WarrantyMonths = $Val["WarrantyMonths"];
		$ProductSerialNumber = $Val["ProductSerialNumber"];
		$ProductStatus = $Val["ProductStatus"];
	}
	
	$ProductPricing = GetProductPricing($ProductID);
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
function GetSubGroups()
{
	var GroupID = document.getElementById("productgroup").value;
	document.getElementById('productsubgroup').options.length = 0;
	if (GroupID != "")
	{
		var SubGroups = agent.call('','GetSubGroupsArray','', GroupID);	
		if (SubGroups != "")
		{
			
			AddSubGroup('None',0);
			var SubGroupArray = SubGroups.split(":::");
			
			for (i = 0; i < SubGroupArray.length; i++) 
			{
				var ThisLine = SubGroupArray[i];
				var ThisLineArray = ThisLine.split("---");
				var ThisID = ThisLineArray[0];
				var ThisValue = ThisLineArray[1];
				
				AddSubGroup(ThisValue, ThisID);
			}
			
			document.getElementById("productsubgroup").disabled = false;	
		}
		else
		{
			document.getElementById("productsubgroup").disabled = true;	
			AddSubGroup('None',0);
		}
	}
	else
	{
		AddSubGroup('None',0);
	}
}

function AddSubGroup(Text, Value)
{
	var SubGroup = document.getElementById("productsubgroup");
	var opt = document.createElement("option");
	SubGroup.options.add(opt);
    opt.text = Text;
    opt.value = Value;
}

function CheckStock()
{
	var StockItem = document.getElementById("stockitem").value;
	if (StockItem == 1)
	{
		document.getElementById("minstock").disabled = false;	
	}
	else
	{
		document.getElementById("minstock").disabled = true;
		document.getElementById("minstock").value = 0;		
	}
}

function CheckSupplier()
{
	var Supplier = document.getElementById("supplier").value;
	
	if (Supplier > 0)
	{
		document.getElementById("supplierpacksize").disabled = false;	
	}
	else
	{
		document.getElementById("supplierpacksize").disabled = true;	
		document.getElementById("supplierpacksize").value = 0;	
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
                    <h1 class="page-header">Edit Product Pricing <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                
                <!-- /.col-lg-12 -->
            </div>
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to edit your product. Please make sure you have completed the setup of your product groups, sub groups, units of meassure as well as any custom fields you require for your business.
                            </div>     
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    
					 
                  
                          
                            
                            <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                <li><a href="editproduct.php?p=<?php echo $ProductID ?>"><i class="fa fa-caret-right"></i> Base Info</a>
                                </li>
                                
                                <li  class="active"><a href="editproductpricing.php?p=<?php echo $ProductID ?>"><i class="fa fa-caret-right"></i> Pricing</a>
                                </li>
                                <li><a href="productimages.php?p=<?php echo $ProductID ?>"><i class="fa fa-caret-right"></i> Images</a>
                                </li>
                                <li><a href="productstock.php?p=<?php echo $ProductID ?>"><i class="fa fa-caret-right"></i> Stock</a>
                                </li>
                                 <li class="pull-right"><a href="products.php"><i class="fa fa-caret-left"></i> Back to All Products</a>
                                </li>
                                
                                
                               
                               
                            </ul>
                            
                                    
                             <div class="col-lg-12"> 
                             
                             <h4>Product Pricing - <?php echo $ProductName ?> <a href="addpricing.php?p=<?php echo $ProductID ?>" class="btn btn-sm btn-default pull-right" style="margin-bottom: 10px"><i class="fa fa-plus"></i> Add Pricing</a></h4>
                             
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" style="font-size: 12px">
                                <thead>
                                    <tr>
                                       
                                        <th>Billing Type</th>
                                        <th>Cost</th>
                                        <th>Pack Size</th>
                                        <th>Measurement</th>
                                        <th>Stock Units Affect</th>
                                        <th>Pro Rata</th>
                                        <th>Edit</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php while ($Val = mysqli_fetch_array($ProductPricing)) 
									{ 
										$ClientCost = $Val["ClientCost"];
										$BillingType = $Val["BillingType"];
										$PackSize = $Val["PackSize"];
										$MeasurementID = $Val["MeasurementID"];
										$StockAffect = $Val["StockAffect"];
										$ProRataBilling = $Val["ProRataBilling"];
										$ProductCostID = $Val["ProductCostID"];
										
										if ($MeasurementID == 0)
										{
											$ShowMeasure = "N/A";	
										}
										else
										{
											$ShowMeasure = GetMeasurementByID($MeasurementID);
										}
										
										if ($StockAffect == 0)
										{
											$StockAffect = "N/A";	
										}
										
										if ($ProRataBilling == 0)
										{
											//ACTIVE	
											$ShowProRata = '<i class="fa fa-times" style="color: red"></i>';
										}
										else
										{
											//ACTIVE	
											$ShowProRata = '<i class="fa fa-check" style="color: green"></i>';
										}
										
										?>
                                    <tr class="odd gradeX">
                                        
                                        <td><?php echo $BillingType ?></td>
                                        <td>R<?php echo number_format($ClientCost,2) ?></td>
                                        <td><?php echo $PackSize ?></td>
                                        <td><?php echo $ShowMeasure ?></td>
                                        <td><?php echo $StockAffect ?></td>
                                        <td><?php echo $ShowProRata ?></td>
                                       
                                        <td class="center"><a href="editcosting.php?p=<?php echo $ProductID ?>&c=<?php echo $ProductCostID ?>" class="btn btn-sm btn-default">Edit Costing</a></td>
                                        
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
			"order": [[ 0, "asc" ], [ 1, "asc" ]]
        });
    });
	
	//MENU STUFF FOR PAGE
	document.getElementById("setupmenu").className = 'active';
	document.getElementById("setupproductmenu").className = 'active';
    </script>
    
    <script type="text/javascript">
	function UpdateProduct()
	{
		var Name = document.getElementById("productname").value;
		var Description = document.getElementById("productdescription").value;
		var Code = document.getElementById("productcode").value;
		var SerialNumber = document.getElementById("serialnumber").value;
		var Taxable = document.getElementById("taxable").value;
		var MinOrder = document.getElementById("minorder").value;
		var Group = document.getElementById("productgroup").value;
		var SubGroup = document.getElementById("productsubgroup").value;
		var Supplier = document.getElementById("supplier").value;
		var Warranty = document.getElementById("warranty").value;
		var StockItem = document.getElementById("stockitem").value;
		var MinStock = document.getElementById("minstock").value;
		var Catalogue = document.getElementById("catalogue").value;
		var Status = document.getElementById("status").value;
		
		if (Name != "" && Description != "" && Code != "" && Taxable != "" && parseInt(MinOrder) >= 0 && Group != "" && Supplier != "" && Warranty != "" && StockItem != "" && parseInt(MinStock) >= 0 && Catalogue != "" && Status != "")
		{
			//WE HAVE ENOUGH NOW TO ADD THE BASE INFO, BUT WE NEED TO CHECK REQUIRED CUSTOM FIELDS
			var CustomIDs = '<?php echo ltrim($AllCustomIDs, ":::"); ?>';
			var CustomError = "";
			
			if (CustomIDs != "")
			{
				var CustomIDArray = CustomIDs.split(":::");
				for (i = 0; i < CustomIDArray.length; i++) 
				{
					var ThisCustomID = CustomIDArray[i];
					var CustomType = agent.call('','GetProductCustomDetails','', ThisCustomID);
					
					var ThisType = CustomType[0];
					var ThisRequired = CustomType[1];
					
					if ((ThisType == "text" || ThisType == "textarea" || ThisType == "select") && ThisRequired == 1)
					{
						var HasData = document.getElementById("custom" + ThisCustomID).value;
						if (HasData == "")
						{
							CustomError = 1;	
						}
					}
					
					if ((ThisType == "checkbox" || ThisType == "radio") && ThisRequired == 1)
					{
						var Options = agent.call('','GetCustomFieldOptionsArray','', ThisCustomID);
						var HasSelected = 0;
						
						for (y = 0; i < Options.length; i++) 
						{
							var ThisOptionID = Options[y];
							var IsChecked = document.getElementById("customoption" + ThisOptionID).checked;
							if (IsChecked === true)
							{
								HasSelected = 1;	
							}
						}
						
						if (HasSelected == 0)
						{
							CustomError = 1;	
						}
					}
				}
				
				if (CustomError == 0)
				{
					//VALIDATION PASSED, ADD BASE PRODUCT FIRST
					var AddProductBase = agent.call('','UpdateBaseProduct','', Name, Description, Code, SerialNumber, Taxable, MinOrder, Group, SubGroup, Supplier, Warranty, StockItem, MinStock, Catalogue, Status, '<?php echo $ProductID ?>');
					if (AddProductBase > 0)
					{
						//CUSTOM FIELDS
						var CustomIDArray = CustomIDs.split(":::");
						for (i = 0; i < CustomIDArray.length; i++) 
						{
							var ThisCustomID = CustomIDArray[i];
							var CustomType = agent.call('','GetProductCustomDetails','', ThisCustomID);
							
							var ThisType = CustomType[0];
							
							
							if ((ThisType == "text" || ThisType == "textarea" || ThisType == "select"))
							{
								var HasData = document.getElementById("custom" + ThisCustomID).value;
								var SaveCustomEntry = agent.call('','UpdateProductCustomEntry','', ThisCustomID, 0, HasData, '<?php echo $ProductID ?>');
							}
							
							if ((ThisType == "checkbox" || ThisType == "radio"))
							{
								var TheseOptions = agent.call('','GetCustomFieldOptionsArray','', ThisCustomID);
								
								
								
								for (z = 0; z < TheseOptions.length; z++) 
								{
									
									var ThisOptionID = TheseOptions[z];
									var IsChecked = document.getElementById("customoption" + ThisOptionID).checked;
									var SaveCustomEntry = agent.call('','UpdateProductCustomEntry','', ThisCustomID, ThisOptionID, IsChecked, '<?php echo $ProductID ?>');
								}
								
							}
						}
						
						bootbox.alert("Product details updated successfully");
						
					}
					else
					{
						bootbox.alert(	AddProductBase);
					}
				}
				else
				{
					bootbox.alert("Please enter all fields marked with a * under the custom fields");
				}
					
			}
			else
			{
				//JUST ADD PRODUCT & CONTINUE
				var AddProductBase = agent.call('','UpdateBaseProduct','', Name, Description, Code, SerialNumber, Taxable, MinOrder, Group, SubGroup, Supplier, Warranty, StockItem, MinStock, Catalogue, Status, '<?php echo $ProductID ?>');
				if (AddProductBase > 0)
				{
					bootbox.alert("Product details updated successfully");
				}
				else
				{
					bootbox.alert(	AddProductBase);
				}
			}	
		}
		else
		{
			bootbox.alert("Please enter all the information marked with a * to save the product");
		}
	}
	</script>

</body>

</html>
