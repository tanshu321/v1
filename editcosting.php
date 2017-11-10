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
	
	$Measurrements = GetAllMeasurements();
	
	
	$ProductID = $_REQUEST["p"];
	$CostingID = $_REQUEST["c"];
	
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
	
	$ThisCosting = GetSingleCosting($CostingID);
	
	while ($Val = mysqli_fetch_array($ThisCosting))
	{
		$ClientCost = $Val["ClientCost"];
		$BillingType = $Val["BillingType"];
		$PackSize = $Val["PackSize"];
		$MeasurementID = $Val["MeasurementID"];
		$StockAffect = $Val["StockAffect"];
		$ProRataBilling = $Val["ProRataBilling"];
		$MinOrder = $Val["MinimumOrder"];
			
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
function UpdateProductCosting()
{
	var BillingType = document.getElementById("billingtype").value;
	var SellPrice = document.getElementById("sellprice").value;
	var PackSize = document.getElementById("packsize").value;
	var Meassure = document.getElementById("meassure").value;
	var StockAffect = document.getElementById("stockaffect").value;
	var ProRata = document.getElementById("prorata").value;
	var MinOrder = document.getElementById("minorder").value;
	
	if (BillingType != "" && parseFloat(SellPrice) > 0 && parseInt(PackSize) > 0 && Meassure != "" && StockAffect != "" && ProRata != "" && parseInt(MinOrder) >= 0)
	{
		var UpdateCost = agent.call('','UpdateCosting','', 	BillingType, SellPrice, PackSize, Meassure, StockAffect, ProRata, "<?php echo $CostingID ?>", MinOrder);
		if (UpdateCost == "OK")
		{
			bootbox.alert("Costing has been updated successfully");
		}
		else
		{
			bootbox.alert(	UpdateCost);
		}
	}
	else
	{
		bootbox.alert("Please fill in all information to save this costing information");	
	}
}

function DisableProRata()
{
	var ChosenBillingType = document.getElementById("billingtype").value;
	
	if (ChosenBillingType == "Monthly")
	{
		document.getElementById("prorata").disabled = false;
	}
	else
	{
		document.getElementById("prorata").disabled = true;
		document.getElementById("prorata").selectedIndex = 'No';
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
                    <h1 class="page-header">Edit Costing <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                
                <!-- /.col-lg-12 -->
            </div>
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to edit your product costing.
                            </div>     
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    
					 <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                <li ><a href="editproduct.php?p=<?php echo $ProductID ?>"><i class="fa fa-caret-right"></i> Base Info</a>
                                </li>
                                
                                <li class="active"><a href="editproductpricing.php?p=<?php echo $ProductID ?>"><i class="fa fa-caret-right"></i> Pricing</a>
                                </li>
                                <li><a href="productimages.php?p=<?php echo $ProductID ?>"><i class="fa fa-caret-right"></i> Images</a>
                                </li>
                                <li><a href="productstock.php?p=<?php echo $ProductID ?>"><i class="fa fa-caret-right"></i> Stock</a>
                                </li>
                                 <li class="pull-right"><a href="editproductpricing.php?p=<?php echo $ProductID ?>"><i class="fa fa-caret-left"></i> Back to All Pricing</a>
                                </li>
                                
                                
                               
                               
                            </ul>
                                    
                             <div class="col-lg-12"> 
                             
                             <h4>Product Cost Information - <?php echo $ProductName ?></h4>
                             
                            <div class="form-group row col-md-6">
                              <label for="fieldname" class="col-sm-5 col-form-label" style="padding-top: 5px">Billing Type *</label>
                              <div class="col-sm-6">
                               <select name="billingtype" class="form-control" id="billingtype"  onChange="javascript: DisableProRata();">
                                                      <?php if ($BillingType == "Once-Off") { $Selected = 'selected'; } else { $Selected = ""; } ?>
                                                      <option value="Once-Off" <?php echo $Selected ?>>Once-Off</option>
                                                      <?php if ($BillingType == "Monthly") { $Selected = 'selected'; } else { $Selected = ""; } ?>
                                                      <option value="Monthly" <?php echo $Selected ?>>Monthly</option>
                                                      <?php if ($BillingType == "Quarterly") { $Selected = 'selected'; } else { $Selected = ""; } ?>
                                                      <option value="Quarterly" <?php echo $Selected ?>>Quarterly (3 months)</option>
                                                      <?php if ($BillingType == "Semi-Annually") { $Selected = 'selected'; } else { $Selected = ""; } ?>
                                                      <option value="Semi-Annually" <?php echo $Selected ?>>Semi-Annually (6 months)</option>
                                                      <?php if ($BillingType == "Annually") { $Selected = 'selected'; } else { $Selected = ""; } ?>
                                                      <option value="Annually" <?php echo $Selected ?>>Annually (12 months)</option>
                                                    </select>
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="fieldname" class="col-sm-5 col-form-label" style="padding-top: 5px">Sell Price (ex VAT) *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="sellprice" placeholder="Sell Price" value="<?php echo $ClientCost ?>">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="fieldname" class="col-sm-5 col-form-label" style="padding-top: 5px">Pack Size *</label>
                              <div class="col-sm-6">
                                <input type="number" class="form-control" id="packsize" placeholder="Pack Size" value="<?php echo $PackSize ?>">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="fieldname" class="col-sm-5 col-form-label" style="padding-top: 5px">Unit Of Meassure</label>
                             
                                <div class="col-sm-6">
                                <select class="form-control" id="meassure" >
                                	
                                	<option value="0" selected>N/A</option>
                                    <?php while ($Val = mysqli_fetch_array($Measurrements))
									{
										$ThisMeasurementID = $Val["MeasurementID"];
										$MeasurementDescription = $Val["MeasurementDescription"];
										
										if ($ThisMeasurementID == $MeasurementID)
										{
											$Selected = 'selected';	
										}
										else
										{
											$Selected = '';	
										}
									?>
                                    <option value="<?php echo $ThisMeasurementID ?>" <?php echo $Selected ?>><?php echo $MeasurementDescription ?></option>
                                    <?php } ?>
                                    
                                </select>
                              
                              </div>
                            </div>
                            
                            
                            
                            <div class="form-group row col-md-6">
                              <label for="fieldname" class="col-sm-5 col-form-label" style="padding-top: 5px">Stock Items in this Pack Size *</label>
                              <div class="col-sm-6">
                                <input type="number" class="form-control" id="stockaffect" placeholder="Stock Items in this Pack Size" value="<?php echo $StockAffect ?>">
                              </div>
                            </div>
                           
                            
                            <div class="form-group row col-md-6">
                              <label for="productgroup" class="col-sm-5 col-form-label" style="padding-top: 5px">Allow Pro Rata Billing *</label>
                              <div class="col-sm-6">
                                <?php if ($BillingType == "Monthly") { ?>
                                <select class="form-control" id="prorata" >
                                	<?php if ($ProRataBilling == 0) { ?>
                                	<option value="0" selected>No</option>
                                    <option value="1" >Yes</option>
                                    <?php } else { ?>
                                    	
                                        <option value="0" >No</option>
                                        <option value="1" selected>Yes</option>
                                        
                                    <?php } ?>
                                    
                                </select>
                                <?php } else { ?>
                                <select class="form-control" id="prorata" disabled>
                                	
                                	<option value="0" selected>No</option>
                                    <option value="1" >Yes</option>
                                  
                                </select>
                                <?php } ?>
                              </div>
                            </div>
                            
                             <div class="form-group row col-md-6">
                              <label for="fieldname" class="col-sm-5 col-form-label" style="padding-top: 5px">Minimum Order (0 disabled) *</label>
                              <div class="col-sm-6">
                                <input type="number" class="form-control" id="minorder" placeholder="Minimum Order" value="<?php echo $MinOrder ?>">
                              </div>
                            </div>
                            
                            
                            
                           
                            
                             
                            
                            
                            
                             <div class="form-group row col-md-6">
                              <label for="productgroup" class="col-sm-5 col-form-label" style="padding-top: 5px">&nbsp;</label>
                              <div class="col-sm-6">
                               &nbsp;
                              </div>
                            </div>
                            
                            
                            
                            <div class="form-group row col-md-6">
                              <label for="productgroup" class="col-sm-5 col-form-label" style="padding-top: 5px"></label>
                              <div class="col-sm-6" >
                                <button class="btn btn-default pull-right" onClick="javascript: UpdateProductCosting();">Update Information</button>
                              </div>
                            </div>
                            
                            
                            
                            
                            
                            
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
