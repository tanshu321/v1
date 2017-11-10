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
	
	$InvoiceID = $_REQUEST["i"];
	
	//FILE UPLOAD
	$Upload = $_REQUEST["u"];
	if ($Upload == "y")
	{
		//OK FORM SUBMITTED, LETS CHECK IF THERES A LOGO TO UPLOAD
		$SafeFile = $_FILES['invoicefile']['name']; 
				
		if(is_uploaded_file(($_FILES['invoicefile']['tmp_name'])))
		{
			$imagename = $_FILES['invoicefile']['name'];
			
			if ($imagename != "")
			{
				$source = $_FILES['invoicefile']['tmp_name'];
				$NewFileName = time() . "_" . str_replace(" ","",$imagename);
				$target = "supplierinvoices/" . $NewFileName;  
				move_uploaded_file($source, $target);
							
				$AddInvoiceFile = AddSupplierInvoiceFile($InvoiceID, $ThisFileType, $NewFileName);
				echo "<script type='text/javascript'>document.location = 'editsupplierinvoice.php?s=" . $SupplierID . "&i=" . $InvoiceID . "';</script>"; 
			}
		}	
	}
	
	if ($InvoiceID != "")
	{
		$InvoiceLines = GetSupplierInvoiceLines($InvoiceID);	
		$NumLines = mysqli_num_rows($InvoiceLines);
		
		$InvoiceOrderDetails = GetSupplierInvoiceDetails($InvoiceID);
		
		while ($Val = mysqli_fetch_array($InvoiceOrderDetails))
		{
			$PurchaseNumber = $Val["PurchaseNumber"];	
			$InvoiceStatus = $Val["InvoiceStatus"];
			$PurchaseOrderID = $Val["PurchaseOrderID"];
			$InvoiceNumber = $Val["InvoiceNumber"];
			$InvoiceFile = $Val["InvoiceFile"];
			
			if ($InvoiceFile == "")
			{
				$InvoiceFile = "None Uploaded";	
			}
			else
			{
				$InvoiceFile = '<a href="supplierinvoices/' . $InvoiceFile . '" target="_blank">View</a>';	
			}
		}
	}
	
	$ProductGroups = GetAllActiveProductGroupsSupplier($SupplierID);
	
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

function AddItem()
{
	var Product = document.getElementById("product").value;
	var Price = document.getElementById("productprice").value;
	var Quantity = document.getElementById("quantity").value;
	var NewItemPrice = document.getElementById("itemprice").value;
	
	if (NewItemPrice == ItemPrice)
	{
		if (parseInt(Product) > 0 && parseInt(Price) > 0 && parseInt(Quantity) > 0)
		{
			var AddInvoiceItem = agent.call('','AddSupplierInvoiceLine','', '<?php echo $InvoiceID ?>', Product, Price, Quantity, '<?php echo $SupplierID ?>', '<?php echo $ChargesVAT ?>');
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
	else
	{
		bootbox.confirm("The price for this item has changed, please confirm you would like to accept and update this new price", function(result)
		{ 
			if (result === true)
			{
				if (parseInt(Product) > 0 && parseInt(Price) > 0 && parseInt(Quantity) > 0)
				{
					var AddInvoiceItem = agent.call('','AddSupplierInvoiceLine','', '<?php echo $InvoiceID ?>', Product, Price, Quantity, '<?php echo $SupplierID ?>', '<?php echo $ChargesVAT ?>', ItemPrice, NewItemPrice);
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
		 });
	}
}

var ItemPrice = 0;

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
		
		//THEN ALSO GET PRICE ALONE FOR PRICING OPTION
		var PriceOptionPrice = agent.call('','GetSupplierPrice','', PricingID);
		ItemPrice = PriceOptionPrice;
		
		document.getElementById("itemprice").value = PriceOptionPrice;
		document.getElementById("itemprice").disabled = false;
		
	}
	else
	{
		document.getElementById("quantity").options.length = 0;	
		AddQuantity('Please Select', '');
		
		document.getElementById("itemprice").value = '';
		document.getElementById("itemprice").disabled = true;
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

function RemoveLine(SupplierInvoiceLineItemID)
{
	bootbox.confirm("Are you sure you would like to delete this invoice line?", function(result)
	{ 
		if (result === true)
		{
			var DelLine = agent.call('','DeleteSupplierInvoiceLine','', SupplierInvoiceLineItemID);	
			if (DelLine == "OK")
			{
				document.location.reload();	
			}
			
		}
	 });
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

function SaveInvoiceDetails()
{
	var PONumber = document.getElementById("reference").value;
	var SupplierInvoiceNumber = document.getElementById("supplierreference").value;
	var InvoiceFile = document.getElementById("invoicefile").value;
	
	var Error = 0;
	
	if (PONumber == "" && SupplierInvoiceNumber != "")
	{
		Error = 1;
		bootbox.alert("Please enter a PO Number");
	}
	
	if (InvoiceFile != "")
	{
		var FileType = InvoiceFile.split('.').pop();
		if (FileType == 'jpg' || FileType == 'JPG' || FileType == 'png' || FileType == 'PNG'  || FileType == 'PDF' || FileType == 'pdf' || FileType == 'XLS' || FileType == 'xls')
		{
			
		}
		else
		{
			Error = 1;
			bootbox.alert("The file you are trying to upload is not supported");		
		}
	}
	
	if (Error == 0)
	{
		var DoUpdateInvoice = agent.call('','AddSupplierInvoice','', PONumber, '<?php echo $InvoiceID ?>', '<?php echo $SupplierID ?>', '<?php $PurchaseOrderID ?>', SupplierInvoiceNumber);
		
		if (parseInt(DoUpdateInvoice) > 0)
		{
			if (InvoiceFile == "")
			{
			document.location = 'editsupplierinvoice.php?s=<?php echo $SupplierID ?>&i=' + DoUpdateInvoice;
			}
			else
			{
				document.getElementById("imageform").action = "editsupplierinvoice.php?u=y&s=<?php echo $SupplierID ?>&i=" + DoUpdateInvoice;
				document.getElementById("imageform").submit();		
			}
		}
		else
		{
			bootbox.alert(DoUpdateInvoice);	
		}
	}
}

function CaptureInvoiceStock()
{
	//FIRST CHECK WE HAVE A SUPPLIER INVOICE NUMBER
	var SupplierInvoice = document.getElementById("supplierreference").value;
	if (SupplierInvoice != "")
	{
		bootbox.confirm("Are you sure you would like to complete this invoice and add stock? The invoice cannot be edited after completion.", function(result)
		{ 
			if (result === true)
			{
				var CompleteInvoice = agent.call('','CompleteSupplierInvoice','', '<?php echo $InvoiceID ?>');
				
				if (CompleteInvoice == "OK")
				{
						bootbox.alert({
							message: "The invoice has been completed successfully",
							callback: function () {
								document.location = 'supplierinvoices.php?s=<?php echo $SupplierID ?>';
							}
						})
				}
				else
				{
					bootbox.alert(	CompleteInvoice);
				}
			}
		 });
	}
	else
	{
		bootbox.alert("Please enter and save a supplier invoice number before completing the transaction");	
	}
}

function EditLine(SupplierLineItemID, Price, Quantity, ProductID, SupplierCostID)
{
	bootbox.confirm("<h4>Edit Invoice Line</h4><label>Price (ex VAT)</label><input type='text' class='form-control' id='newprice' value='" + Price + "'><label style='margin-top: 10px'>Quantity Received</label><input type='text' class='form-control' id='newquantity' value='" + Quantity + "' ><input type='radio' style='margin-top: 10px' id='updateprice'> Update Supplier Costing with new price", function(result)
		{
			if (result === true)
			{
				var NewPrice = document.getElementById("newprice").value;
				var NewQuantity = document.getElementById("newquantity").value;
				var UpdateCost = document.getElementById("updateprice").checked;
				
				if (parseFloat(NewPrice) > 0 && parseFloat(NewQuantity) > 0)
				{
					var UpdateInvoiceLine = agent.call('','UpdateInvoiceLine','', SupplierLineItemID, NewPrice, NewQuantity, UpdateCost, ProductID, '<?php echo $SupplierID ?>', SupplierCostID, '<?php echo $ChargesVAT ?>');
					if (UpdateInvoiceLine == "OK")
					{
						document.location.reload();
					}
					else
					{
						bootbox.alert('There was an error updating the invoice line, please check your input and try again', function() {
							EditLine(SupplierLineItemID, Price, Quantity, ProductID, SupplierCostID);
						});
					}
				}
				else
				{
					bootbox.alert('Please enter the new price and quantity to proceed', function() {
						EditLine(SupplierLineItemID, Price, Quantity, ProductID, SupplierCostID);
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
                    <h1 class="page-header">Supplier Invoice - <?php echo $SupplierName ?> <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                
                <!-- /.col-lg-12 -->
            </div>
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to add/edit your supplier invoice</div>     
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    
					 <!-- Nav tabs -->
                            <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                
                                <li ><a href="showsupplier.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Supplier Details</a>
                                </li>
                                <li><a href="supplierproducts.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Products</a>
                                </li>
                                <li class="active"><a href="supplierinvoices.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Invoices</a>
                                </li>
                                <li ><a href="supplierpo.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Purchase Orders</a>
                                </li>
                                <li class="pull-right"><a href="suppliersetup.php"><i class="fa fa-chevron-left"></i> Back to All Suppliers</a>
                                </li>
                               
                               
                  </ul>
                  
                        
                        
                                    
                             <div class="col-lg-6" style="padding-top: 10px">
                             
                                       
                                                
                                                 <div class="clearfix"></div>
                                                <h4 style="padding-bottom: 10px">Invoice Details</h4>
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="documentname" class="col-sm-3 col-form-label" style="padding-top: 5px">PO Number *</label>
                                                  <div class="col-sm-6">
                                                  
                                                    <input type="text" class="form-control" id="reference" name="reference" placeholder="PO Number" value="<?php echo $PurchaseNumber ?>">
                                                    
                                                    
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="documentname" class="col-sm-3 col-form-label" style="padding-top: 5px">Supplier Invoice Number *</label>
                                                  <div class="col-sm-6">
                                                  
                                                    <input type="text" class="form-control" id="supplierreference" name="supplierreference" placeholder="Supplier Invoice Number" value="<?php echo $InvoiceNumber  ?>">
                                                    
                                                    
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="documentname" class="col-sm-3 col-form-label" style="padding-top: 5px">Supplier Invoice Copy</label>
                                                  <div class="col-sm-6">
                                                  
                                                    <p style="margin-top: 5px"><?php echo $InvoiceFile ?></p>
                                                    
                                                    
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="documentname" class="col-sm-3 col-form-label" style="padding-top: 5px">Upload File</label>
                                                  <div class="col-sm-6">
                                                   <form enctype="multipart/form-data" id="imageform" method="post" action="">
                                                   <input type="file" class="form-control" id="invoicefile" name="invoicefile">
                                                   </form>
                                                    
                                                  </div>
                                                </div>
                                                
                                                
                                                
                                                
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px"></label>
                                                  <div class="col-sm-4">
                                                    <button class="btn btn-info pull-right" onClick="javascript: SaveInvoiceDetails();">Save Details</button>
                                                  </div>
                                                </div>
                                                
                                                
                                               
                                                
                                               
                                                
                                                <!-- END FORM CONTROLS -->
                                                
                                          
                                        
                                    
                                  </div>
                                  <?php if ($InvoiceID != "" && $InvoiceStatus == 0) { ?>
                                  <div class="col-lg-6" style="padding-top: 10px">
                             
                                       
                                                
                                                 <div class="clearfix"></div>
                                                <h4 style="padding-bottom: 10px">Add Invoice Item</h4>
                                                
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
                                                    <select class="form-control" id="productprice" onChange="javascript: CheckMinOrder();"> 
                                                        <option value="" selected>Please select</option>
                                                       
                                                        </select>
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="quantity" class="col-sm-3 col-form-label" style="padding-top: 5px">Item Price</label>
                                                  <div class="col-sm-6">
                                                   
                                                     <input type="text" class="form-control" id="itemprice" name="itemprice" placeholder="Item Price (ex VAT)" disabled>
                                                  
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="quantity" class="col-sm-3 col-form-label" style="padding-top: 5px">Quantity Required *</label>
                                                  <div class="col-sm-6">
                                                   
                                                     <select name="quantity" class="form-control" id="quantity">
                                                     	<option value="" selected>Please select</option>
                                                        
                                                     	
                                                    </select>
                                                  
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
                <?php if ($InvoiceID != "") { ?>
                	 <div class="col-lg-12" style="padding-top: 10px">
                	<h4 style="padding-bottom: 10px">Invoice Lines</h4>
                    
                    <table width="100%" class="table table-striped table-bordered table-hover" style="font-size: 12px" id="invoicetable">
                                	<thead>
                                    <tr>
                                       
                                        
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Stock Affect</th>
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
									
									while ($Val = @mysqli_fetch_array($InvoiceLines))
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
										
										$SupplierInvoiceLineItemID = $Val["SupplierInvoiceLineItemID"];
										
										$StockAffect = $Val["StockAffect"];
										$ProductID = $Val["ProductID"];
										$SupplierCostID = $Val["SupplierCostID"];
										
									?>
                                    <tr class="odd gradeX">
                                        
                                        
                                        <td><?php echo $Description ?> (<?php echo $Meassure ?>)</td>
                                        <td width="">R<?php echo number_format($Price,2) ?></td>
                                        <td width=""><?php echo $Quantity ?></td>
                                        <td width=""><?php echo $StockAffect ?></td>
                                        <td width="">R<?php echo number_format($LineSub,2) ?></td>
                                       
                                       <td width="">R<?php echo number_format($Vat,2) ?></td>
                                      <td>R<?php echo number_format($LineTotal,2) ?></td>
                                        <td class="center"><?php if ($InvoiceStatus == 0 ) { ?><i class="fa fa-plus-square" style="color: green; font-size: 18px" onClick="javascript: EditLine(<?php echo $SupplierInvoiceLineItemID ?>, <?php echo $Price ?>, <?php echo $Quantity ?>, <?php echo $ProductID ?>, <?php echo $SupplierCostID ?>)"></i> <i class="fa fa-minus-square" style="color: #F00; font-size: 18px" onClick="javascript: RemoveLine(<?php echo $SupplierInvoiceLineItemID ?>)"></i><?php } ?></td>
                                        
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
                                        <td colspan=""></td>
                                        
                                    </tr>
                                    
                                   
                                    <tr>
                                    	
                                        <td colspan=""></td>
                                        <td colspan=""></td>
                                         <td colspan=""></td>
                                          <td colspan=""></td>
                                           <td colspan=""></td>
                                        <td colspan=""><strong>Sub Total</strong></td>
                                        <td colspan="" id="invoicesubtotal">R<?php echo number_format($InvoiceSub,2) ?></td>
                                        <td colspan=""></td>
                                        
                                    </tr>
                                    
                                    <tr>
                                    	
                                        <td colspan=""></td>
                                        <td colspan=""></td>
                                         <td colspan=""></td>
                                          <td colspan=""></td>
                                          <td colspan=""></td>
                                        <td colspan=""><strong>VAT</strong></td>
                                        <td colspan="" id="invoicevat">R<?php echo number_format($InvoiceVat,2) ?></td>
                                         <td colspan=""></td>
                                        
                                    </tr>
                                    <tr>
                                    	
                                        <td colspan=""></td>
                                        <td colspan=""></td>
                                         <td colspan=""></td>
                                          <td colspan=""></td>
                                          <td colspan=""></td>
                                        <td colspan=""><strong>Total</strong></td>
                                        <td colspan="">R<span id="invoicetotal"><?php echo number_format($InvoiceTotal,2) ?></span></td>
                                         <td colspan=""></td>
                                        
                                    </tr>
                                </tbody>
                                </table>
                </div>
                <div class="col-md-12" style="margin-bottom: 50px" align="right">
                	 <?php if ($InvoiceStatus == 0) { ?><button class="btn btn-warning" onClick="javascript: CaptureInvoiceStock();"  style="marign-right: 20px">Capture Stock & Complete</button><?php } ?>
                </div>
                <?php } ?>
                </div>
            </div>
           
        </div>
        <!-- /#page-wrapper -->
		
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
