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
	
	$PurchaseID = $_REQUEST["p"];
	
	if ($PurchaseID != "")
	{
		$PurchaseLines = GetPurchaseOrderLines($PurchaseID);	
		$NumLines = mysqli_num_rows($PurchaseLines);
		
		$PurchaseOrderDetails = GetPurchaseOrderDetails($PurchaseID);
		
		while ($Val = mysqli_fetch_array($PurchaseOrderDetails))
		{
			$PurchaseNumber = $Val["PurchaseNumber"];	
			$PurchaseStatus = $Val["PurchaseStatus"];
			$DeliveryType = $Val["DeliveryType"];
			$SpecialInstructions = $Val["SpecialInstructions"];
			$ThisWarehouseID = $Val["WarehouseID"];
		}
	}
	
	$ProductGroups = GetAllActiveProductGroupsSupplier($SupplierID);
	
	$Warehouses = GetAllWarehouses();
	
	//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
	if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
	else
	{
		$Access = CheckPageAccess('Purchase Orders');	
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
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

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
	document.getElementById("productprice").options.length = 0;
		
	if (SelectedGroup != "")
	{
		var GetGroupProducts = agent.call('','GetGroupProductsArraySupplier','', SelectedGroup, '<?php echo $SupplierID ?>');
		
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

function GetProductPricing()
{
	var Product = document.getElementById("product").value;
	document.getElementById("productprice").options.length = 0;
	
	if (Product != "")
	{
		var GetPrices = agent.call('','GetProductPricingArraySupplier','', Product, '<?php echo $SupplierID ?>');
		
		AddPricing('Please Select', '');
		
		//WE SHOULD HAVE ARRAY BACK
		for (i = 0; i < GetPrices.length; i++) 
		{
			var PricingID = GetPrices[i][0];
			var PricingDescript = GetPrices[i][1];
			var MinOrder = GetPrices[i][2];
			
			AddPricing(PricingDescript, PricingID);
		}
	}
	
}

function AddPricing(Text, Value)
{
	var PricingBox = document.getElementById("productprice");
	var opt = document.createElement("option");
	PricingBox.options.add(opt);
    opt.text = Text;
    opt.value = Value;
}
function isNumeric(input) {
			 var RE = /^-{0,1}\d*\.{0,1}\d+$/;
			 return (RE.test(input));
}
		  
function AddItem()
{
	var Product = document.getElementById("product").value;
	var Price = document.getElementById("productprice").value;
	var Quantity = document.getElementById("quantity").value;
	
	if (parseInt(Product) > 0 && parseInt(Price) > 0 && isNumeric(Quantity) )
	{
		var AddInvoiceItem = agent.call('','AddPOLine','', '<?php echo $PurchaseID ?>', Product, Price, Quantity, '<?php echo $SupplierID ?>', '<?php echo $ChargesVAT ?>');
		if (AddInvoiceItem == "OK")
		{
			document.location.reload();
		}
		else
		{
			bootbox.alert(AddInvoiceItem);	
		}
	}
	else
	{
		bootbox.alert("Please select the product, price and quantity to add");	
	}
}

function CheckMinOrder()
{
	var PricingID = document.getElementById("productprice").value;
	
	if (PricingID != "")
	{
		var MinOrder = agent.call('','GetMinOrderSupplier', '', PricingID);
		
		if (MinOrder > 0)
		{
			//REDO QUANTITY BOX
			document.getElementById("quantity").options.length = 0;
			AddQuantity('Please Select', '')
				
			//LOOP IT, BUILD IT
			for (X = MinOrder; X <= 100; X++)
			{
				AddQuantity(X, X)
			}
				
					
		}
		else
		{
			//REDO QUANTITY BOX
			document.getElementById("quantity").options.length = 0;
			AddQuantity('Please Select', '');
				
			//LOOP IT, BUILD IT
			for (X = 1; X <= 100; X++)
			{
				AddQuantity(X, X)
			}
		}
	}
	else
	{
		document.getElementById("quantity").options.length = 0;	
		AddQuantity('Please Select', '');
	}
}

function AddQuantity(Text, Value)
{
	var QuantityBox = document.getElementById("quantity");
	var opt = document.createElement("option");
	QuantityBox.options.add(opt);
    opt.text = Text;
    opt.value = Value;
}

function RemoveLine(PurchaseLineItemID)
{
	bootbox.confirm("Are you sure you would like to delete this purchase order line?", function(result)
	{ 
		if (result === true)
		{
			var DelLine = agent.call('','DeletePOLine','', PurchaseLineItemID);	
			if (DelLine == "OK")
			{
				document.location.reload();	
			}
			
		}
	 });
}

function SendSupplierPO()
{
	var HasLines = '<?php echo $NumLines ?>';
	var SupplierEmail = '<?php echo $SupplierEmail ?>';
	
	if (parseInt(HasLines) > 0 && SupplierEmail != "")
	{
		var DoPublish = agent.call('','SendSupplierPO','', 	'<?php echo $PurchaseID ?>', '<?php echo $SupplierID ?>');
		if (DoPublish == "OK")
		{
			bootbox.alert('The purcharse order has been sent successfully', function() {
						document.location = 'supplierpo.php?s=<?php echo $SupplierID ?>';
			});
		}
		else
		{
			bootbox.alert(DoPublish);	
		}
	}
	else
	{
		if (SupplierEmail != "")
		{
			bootbox.alert("There are no purchase order lines found, please add some items for this purchase order");
		}
		else
		{
			bootbox.alert("There is no email address setup for this supplier, please setup the email address");
		}
	}
}

function ResendSupplierPO()
{
	var HasLines = '<?php echo $NumLines ?>';
	var SupplierEmail = '<?php echo $SupplierEmail ?>';
	
	if (parseInt(HasLines) > 0 && SupplierEmail != "")
	{
		var DoPublish = agent.call('','ResendSupplierPO','', 	'<?php echo $PurchaseID ?>', '<?php echo $SupplierID ?>');
		if (DoPublish == "OK")
		{
			bootbox.alert('The purcharse order has been resent successfully', function() {
						document.location = 'supplierpo.php?s=<?php echo $SupplierID ?>';
			});
		}
		else
		{
			bootbox.alert(DoPublish);	
		}
	}
	else
	{
		if (SupplierEmail != "")
		{
			bootbox.alert("There are no purchase order lines found, please add some items for this purchase order");
		}
		else
		{
			bootbox.alert("There is no email address setup for this supplier, please setup the email address");
		}
	}
}

function CheckUsePO()
{
	var IsSelected = document.getElementById("usepo").checked;
	if (IsSelected === true)
	{
		document.getElementById("reference").disabled = true;	
	}
	else
	{
		document.getElementById("reference").disabled = false;	
	}
}

function SavePODetails()
{
	var PONumber = document.getElementById("reference").value;	
	var IsSelected = document.getElementById("usepo").checked;
	var Delivery = document.getElementById("delivery").value;
	var SpecialInstructions = document.getElementById("specialinstructions").value;
	var WarehouseID = document.getElementById("warehouse").value;
	
	var Error = 0;
	
	if (PONumber == "" && IsSelected == false)
	{
		Error = 1;
		bootbox.alert("Please enter a PO Number");
	}
	
	if (WarehouseID == "")
	{
		Error = 1;
		bootbox.alert("Please select the warehouse to assign stock to");	
	}
	
	if (Error == 0)
	{
		var DoUpdatePO = agent.call('','AddPurchaseOrder','', PONumber, IsSelected, Delivery, SpecialInstructions, '<?php echo $PurchaseID ?>', '<?php echo $SupplierID ?>', WarehouseID);
		
		if (parseInt(DoUpdatePO) > 0)
		{
			document.location = 'editsupplierpo.php?s=<?php echo $SupplierID ?>&p=' + DoUpdatePO;
		}
		else
		{
			bootbox.alert(DoUpdatePO);	
		}
	}
}

function CreateSupplierInvoice()
{
	var HasLines = '<?php echo $NumLines ?>';
	
	if (parseInt(HasLines) > 0)
	{
		bootbox.confirm("Are you sure you would like to convert this PO to an Invoice?", function(result)
		{
			if (result === true)
			{
				var CreateInvoice = agent.call('','CreateSupplierInvoiceFromPO','', '<?php echo $PurchaseID ?>', '<?php echo $SupplierID ?>', '<?php echo $PurchaseNumber ?>', <?php echo $ThisWarehouseID ?>);
				
				if (parseInt(CreateInvoice) > 0)
				{
					document.location = 'editsupplierinvoice.php?s=<?php echo $SupplierID ?>&i=' + CreateInvoice;
				}
				else
				{
					bootbox.alert(CreateInvoice);	
				}
			}
		});
	}
	else
	{
		bootbox.alert('There are no purchase order lines, please add some products to the purchase order first');	
	}
}

function EditLine(PurchaseLineItemID, Price, Quantity, ProductID, SupplierCostID)
{
	bootbox.confirm("<h4>Edit PO Line</h4><label>Price (ex VAT)</label><input type='text' class='form-control' id='newprice' value='" + Price + "'><label style='margin-top: 10px'>Quantity Received</label><input type='text' class='form-control' id='newquantity' value='" + Quantity + "' ><input type='radio' style='margin-top: 10px' id='updateprice'> Update Supplier Costing with new price", function(result)
		{
			if (result === true)
			{
				var NewPrice = document.getElementById("newprice").value;
				var NewQuantity = document.getElementById("newquantity").value;
				var UpdateCost = document.getElementById("updateprice").checked;
				
				if (parseFloat(NewPrice) > 0 && parseFloat(NewQuantity) > 0)
				{
					var UpdatePOLine = agent.call('','UpdatePOLine','', PurchaseLineItemID, NewPrice, NewQuantity, UpdateCost, ProductID, '<?php echo $SupplierID ?>', SupplierCostID, '<?php echo $ChargesVAT ?>');
					if (UpdatePOLine == "OK")
					{
						document.location.reload();
					}
					else
					{
						bootbox.alert('There was an error updating the PO line, please check your input and try again', function() {
							EditLine(PurchaseLineItemID, Price, Quantity, ProductID, SupplierCostID);
						});
					}
				}
				else
				{
					bootbox.alert('Please enter the new price and quantity to proceed', function() {
						EditLine(PurchaseLineItemID, Price, Quantity, ProductID, SupplierCostID);
					});
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
                    <h1 class="page-header">Supplier Purchase Orders - <?php echo $SupplierName ?> <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                
                <!-- /.col-lg-12 -->
            </div>
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to add/edit your purchase orders
                            </div>     
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    
					 <!-- Nav tabs -->
                            <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                
                                <li ><a href="showsupplier.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Supplier Details</a>
                                </li>
                                <li><a href="supplierproducts.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Products</a>
                                </li>
                                <li><a href="supplierinvoices.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Invoices</a>
                                </li>
                                <li  class="active"><a href="supplierpo.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Purchase Orders</a>
                                </li>
                                <li class="pull-right"><a href="suppliersetup.php"><i class="fa fa-chevron-left"></i> Back to All Suppliers</a>
                                </li>
                               
                               
                  </ul>
                  
                        
                        
                             <?php if ($Access == 1) { ?>           
                             <div class="col-lg-6" style="padding-top: 10px">
                             
                                       
                                                
                                                 <div class="clearfix"></div>
                                                <h4 style="padding-bottom: 10px">Purchase Order Details</h4>
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="documentname" class="col-sm-3 col-form-label" style="padding-top: 5px">PO Number *</label>
                                                  <div class="col-sm-6">
                                                  <?php if ($PurchaseNumber == "") { ?>
                                                    <input type="text" class="form-control" id="reference" name="reference" placeholder="PO Number" value="<?php echo $PurchaseNumber ?>" disabled>
                                                    
                                                    <input type="checkbox" id="usepo" onChange="javascript: CheckUsePO();" checked> Use Auto PO Number 
                                                    <?php } else { ?>
                                                    <input type="text" class="form-control" id="reference" name="reference" placeholder="PO Number" value="<?php echo $PurchaseNumber ?>">
                                                    <input type="checkbox" id="usepo" onChange="javascript: CheckUsePO();" style="display: none">
                                                    <?php } ?>
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="product" class="col-sm-3 col-form-label" style="padding-top: 5px">Delivery *</label>
                                                  <div class="col-sm-6">
                                                    <select class="form-control" id="delivery"> 
                                                    	<?php if ($DeliveryType == 'Deliver' || $DeliveryType == "")
														{ ?>
                                                        <option value="Deliver" selected>Deliver</option>
                                                        <option value="Collect">Collect</option>
                                                        <?php } else { ?>
                                                       	<option value="Deliver">Deliver</option>
                                                        <option value="Collect" selected>Collect</option>
                                                        
                                                        <?php } ?>
                                                        </select>
                                                  </div>
                                                </div>
                                                
                                                 <div class="form-group row col-md-12">
                                                  <label for="productprice" class="col-sm-3 col-form-label" style="padding-top: 5px">Special Instructions</label>
                                                  <div class="col-sm-6">
                                                    <textarea class="form-control" placeholder="Special Instructions" id="specialinstructions"><?php echo $SpecialInstructions ?></textarea>
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="product" class="col-sm-3 col-form-label" style="padding-top: 5px">Stock Warehouse *</label>
                                                  <div class="col-sm-6">
                                                    <select class="form-control" id="warehouse"> 
                                                    	<option value="" selected>Please select Warehouse</option>
                                                    	<?php while ($Val = mysqli_fetch_array($Warehouses))
														{
															$WarehouseID = $Val["WarehouseID"];
															$WarehouseName = $Val["WarehouseName"];
															
															if ($WarehouseID == $ThisWarehouseID)
															{
																$Selected = 'selected';	
															}
															else
															{
																$Selected = '';	
															}
														?>
                                                        <option value="<?php echo $WarehouseID ?>" <?php echo $Selected ?>><?php echo $WarehouseName ?></option>
                                                       <?php } ?>
                                                        
                                                        </select>
                                                  </div>
                                                </div>
                                                
                                                
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px"></label>
                                                  <div class="col-sm-4">
                                                    <button class="btn btn-info pull-right" onClick="javascript: SavePODetails();">Save Details</button>
                                                  </div>
                                                </div>
                                                
                                                
                                               
                                                
                                               
                                                
                                                <!-- END FORM CONTROLS -->
                                                
                                          
                                        
                                    
                                  </div>
                                  <?php if ($PurchaseID != "") { ?>
                                  <div class="col-lg-6" style="padding-top: 10px">
                             
                                       
                                                
                                                 <div class="clearfix"></div>
                                                <h4 style="padding-bottom: 10px">Add Purchase Order Item</h4>
                                                
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
                                                    <select class="form-control" id="product" onChange="javascript: GetProductPricing();"> 
                                                        <option value="" selected>Please select</option>
                                                       
                                                        </select>
                                                  </div>
                                                </div>
                                                
                                                 <div class="form-group row col-md-12">
                                                  <label for="productprice" class="col-sm-3 col-form-label" style="padding-top: 5px">Price Option *</label>
                                                  <div class="col-sm-6">
                                                  <!--  <select class="form-control" id="productprice" onChange="javascript: CheckMinOrder();"> -->
                                                    <select class="form-control" id="productprice" >
                                                        <option value="" selected>Please select</option>
                                                       
                                                        </select>
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="quantity" class="col-sm-3 col-form-label" style="padding-top: 5px">Quantity Required *</label>
                                                  <div class="col-sm-6">
                                                   
                                                   <!--  <select name="quantity" class="form-control" id="quantity">
                                                     	<option value="" selected>Please select</option>
                                                        
                                                     	
                                                    </select> -->
													
                                                  <input type="text" class="form-control" id="quantity" name="quantity" placeholder="Quantity" value="<?php echo $Quantity ?>">
												  
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px"></label>
                                                  <div class="col-sm-4">
                                                    <button class="btn btn-info pull-right" onClick="javascript: AddItem();">Add Item</button>
                                                  </div>
                                                </div>
                                                
                                                
                                               
                                                
                                               
                                                
                                                <!-- END FORM CONTROLS -->
                                                
                                          
                                        
                                    
                                  </div>
                                  <?php } ?>
                            <!-- /.table-responsive -->
                            
                       
                   
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-lg-12">
                <?php if ($PurchaseID != "") { ?>
                	 <div class="col-lg-12" style="padding-top: 10px">
                	<h4 style="padding-bottom: 10px">Purchase Order Lines</h4>
                    
                    <table width="100%" class="table table-striped table-bordered table-hover" style="font-size: 12px" id="invoicetable">
                                	<thead>
                                    <tr>
                                       
                                        
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Sub Total</th>
                                        
                                        
                                        <th>VAT</th>
                                        
                                        <th>Total</th>
                                        <th></th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php 
									$InvoiceSub = 0;
									$InvoiceDiscount = 0;
									$InvoiceVat = 0;
									$InvoiceTotal = 0; 
									
									while ($Val = @mysqli_fetch_array($PurchaseLines))
									{
										$Description = $Val["Description"];	
										$Quantity = $Val["Quantity"];
										$Price = $Val["Price"];
										
										$LineSub = $Val["LineSubTotal"];
										$InvoiceSub = $InvoiceSub + $LineSub;
										
										$Discount = $Val["LineDiscount"];
										$InvoiceDiscount = $InvoiceDiscount + $Discount;
										
										$Vat = $Val["LineVAT"];
										$InvoiceVat = $InvoiceVat + $Vat;
										
										$Meassure = $Val["MeassurementDescription"];
										
										$LineTotal = $Val["LineTotal"];
										$InvoiceTotal = $InvoiceTotal + $LineTotal;
										$ProductID = $Val["ProductID"];
										$PurchaseLineItemID = $Val["PurchaseLineItemID"];
										$SupplierCostID = $Val["SupplierCostID"];
										
									?>
                                    <tr class="odd gradeX">
                                        
                                        
                                        <td><?php echo $Description ?> (<?php echo $Meassure ?>)</td>
                                        <td width="">R<?php echo number_format($Price,2) ?></td>
                                        <td width=""><?php echo $Quantity ?></td>
                                        <td width="">R<?php echo number_format($LineSub,2) ?></td>
                                       
                                       <td width="">R<?php echo number_format($Vat,2) ?></td>
                                      <td>R<?php echo number_format($LineTotal,2) ?></td>
                                        <td class="center"><?php if ($PurchaseStatus == 1) { ?><i class="fa fa-plus-square" style="color: green; font-size: 18px" onClick="javascript: EditLine(<?php echo $PurchaseLineItemID ?>, <?php echo $Price ?>, <?php echo $Quantity ?>, <?php echo $ProductID ?>, <?php echo $SupplierCostID ?>)"></i><?php } ?>
                                         <i class="fa fa-minus-square" style="color: #F00; font-size: 18px" onClick="javascript: RemoveLine(<?php echo $PurchaseLineItemID ?>)"></i></td>
                                        
                                    </tr>
                                    <?php } ?>
                                  	
                                    <tr>
                                    	
                                        <td colspan="">&nbsp;</td>
                                        <td colspan=""></td>
                                        <td colspan=""></td>
                                         <td colspan=""></td>
                                         
                                        <td colspan=""></td>
                                        <td colspan=""></td>
                                        <td colspan=""></td>
                                    </tr>
                                    
                                   
                                    <tr>
                                    	
                                        <td colspan=""></td>
                                        <td colspan=""></td>
                                         <td colspan=""></td>
                                          <td colspan=""></td>
                                          
                                        <td colspan=""><strong>Estimated Sub Total</strong></td>
                                        <td colspan="" id="invoicesubtotal">R<?php echo number_format($InvoiceSub,2) ?></td>
                                        <td colspan=""></td>
                                    </tr>
                                    
                                    <tr>
                                    	
                                        <td colspan=""></td>
                                        <td colspan=""></td>
                                         <td colspan=""></td>
                                          
                                          <td colspan=""></td>
                                        <td colspan=""><strong>Estimated VAT</strong></td>
                                        <td colspan="" id="invoicevat">R<?php echo number_format($InvoiceVat,2) ?></td>
                                        <td colspan=""></td>
                                    </tr>
                                    <tr>
                                    	
                                        <td colspan=""></td>
                                        <td colspan=""></td>
                                         <td colspan=""></td>
                                          
                                          <td colspan=""></td>
                                        <td colspan=""><strong>Estimated Total</strong></td>
                                        <td colspan="">R<span id="invoicetotal"><?php echo number_format($InvoiceTotal,2) ?></span></td>
                                        <td colspan=""></td>
                                    </tr>
                                </tbody>
                                </table>
                </div>
                <div class="col-md-12" style="margin-bottom: 50px" align="right">
                	 <?php if ($PurchaseStatus == 0) { ?><button class="btn btn-success" onClick="javascript: SendSupplierPO();">Send to Supplier</button><?php } else { ?><button class="btn btn-success" onClick="javascript: ResendSupplierPO();">Resend to Supplier</button><?php } ?> <?php if ($PurchaseID != "") { ?><a class="btn btn-danger" href="showpo.php?p=<?php echo $PurchaseID ?>&s=<?php echo $SupplierID ?>" target="_blank">Preview Purchase Order</a><?php } ?> <?php if ($PurchaseStatus != 0 && $PurchaseStatus != 2) { ?><button class="btn btn-warning" onClick="javascript: CreateSupplierInvoice();"  style="marign-right: 20px">Convert to Invoice</button><?php } ?>
                </div>
                <?php } ?>
                </div>
            </div>
           
        </div>
        <!-- /#page-wrapper -->
		<?php } else { ?>
        <h4>You do not have access to this module, if you think this is a mistake please contact your system administrator</h4>
        <?php } ?> 
    </div>
    
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    

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
	
	
	
	$('.datepicker').datepicker({
    	dateFormat: 'yy-mm-dd',
		minDate: new Date(<?php echo date("Y") ?>, <?php echo date("m") ?> - 1, <?php echo date("d") ?>)
});
</script>

<script type="text/javascript">
	//MENU STUFF FOR PAGE
	document.getElementById("setupmenu").className = 'active';
	document.getElementById("setupsuppliermenu").className = 'active';
	</script>
</body>

</html>
