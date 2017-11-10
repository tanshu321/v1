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
	$CustomerID = $_REQUEST["c"];
	
	$ClientInfo = GetSingleClient($CustomerID);
	$FoundClient = mysqli_num_rows($ClientInfo);
	
	if ($FoundClient != 0)
	{
	
		while ($Val = mysqli_fetch_array($ClientInfo))
		{
			$Name = $Val["FirstName"];
			$Surname = $Val["Surname"];
			$CompanyName = $Val["CompanyName"];
			
			if ($CompanyName != "")
			{
				$TopCompanyName = $CompanyName . " ( " . $Name . " " . $Surname . " )";		
			}
			
			$EmailAddress = $Val["EmailAddress"];
			$DateAdded = $Val["DateAdded"];
											
			$ThisStatus = $Val["Status"];
			
			$Address1 = $Val["Address1"];
			$Address2 = $Val["Address2"];
			$City = $Val["City"];
			$Region = $Val["Region"];
			$PostCode = $Val["PostCode"];
			$CountryName = $Val["CountryName"];
			$ContactNumber  = $Val["ContactNumber"];
			$ClientCountryID = $Val["CountryID"];
			
			$TaxExempt = $Val["TaxExempt"];
			$OverdueNotices = $Val["OverdueNotices"];
			$MarketingEmails = $Val["MarketingEmails"];
			$PaymentMethod = $Val["PaymentMethod"];
			$VatNumber = $Val["VatNumber"];
			$AdminNotes = $Val["AdminNotes"];
			
			$ThisResellerID = $Val["ResellerID"];
			
		}
		
		
		if ($ThisStatus == 2)
		{
			//ACTIVE	
			$ShowStatus = '<span class="label label-success" style="font-size: 14px">Active</span>';
		}
		else
		{
			//INACTIVE	
			$ShowStatus = '<span class="label label-danger" style="font-size: 14px">Inactive</span>';
		}
		
		
		$ProductGroups = GetAllActiveProductGroupsNonOnce();
		$RecurringInvoiceDay = GetCompanyRecurringDay();
		
		$Today = date("d");
		
		if ($RecurringInvoiceDay < $Today)
		{
			//NEXT MONTH
			$NextRun = date("Y-m-d", mktime(0,0,0, date("m") + 1, $RecurringInvoiceDay, date("Y")));	
		}
		else
		{
			//THIS MONTH STILL	
			$NextRun = date("Y-m-d", mktime(0,0,0, date("m"), $RecurringInvoiceDay, date("Y")));	
		}
		
		$Warehouses = GetAllWarehouses();
		
		//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
		if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
		else
		{
			$Access = CheckPageAccess('Products');	
		}
		
	}
	else
	{
		echo "<script type='text/javascript'>alert('Your session has expired, please login again'); parent.location = 'logout.php';</script>";
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
		var GetGroupProducts = agent.call('','GetGroupProductsArrayNonOnce','', SelectedGroup);
		
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
		var GetPrices = agent.call('','GetProductPricingArrayNonOnce','', Product);
		
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
	var InvoiceOption1 = document.getElementById("prorataproductprorata").checked;
	var InvoiceOption2 = document.getElementById("prorataproductfull").checked;
	var InvoiceOption3 = document.getElementById("prorataproductnext").checked;
	var RecurringTimes = document.getElementById("recurringtimes").value;
	var WareHouseID = document.getElementById("warehouse").value;
	
	var MinOrder = agent.call('','GetMinOrderProduct', '', Price);
	var ThisMinOrder = MinOrder[0];
	
	if (parseInt(Product) > 0 && parseInt(Price) > 0 && parseInt(Quantity) > 0 && (InvoiceOption1 === true || InvoiceOption2 === true || InvoiceOption3 === true) && RecurringTimes >= 0 && WareHouseID != "")
	{
		if (Quantity >= ThisMinOrder)
		{
			var InvoiceType = '';
			if (InvoiceOption1 === true)
			{
				InvoiceType = 1;	
			}
			if (InvoiceOption2 === true)
			{
				InvoiceType = 2;	
			}
			if (InvoiceOption3 === true)
			{
				InvoiceType = 3;	
			}
			var AddInvoiceItem = agent.call('','AddProductRecurring','', Product, Price, Quantity, InvoiceType, '<?php echo $CustomerID ?>', '<?php echo $NextRun ?>', '<?php echo $TaxExempt ?>', '<?php echo $Address1 ?>', '<?php echo $Address2 ?>', '<?php echo $City ?>', '<?php echo $Region ?>', '<?php echo $PostCode ?>', '<?php echo $ClientCountryID ?>', RecurringTimes, WareHouseID);
			if (AddInvoiceItem != "Error")
			{
				var Log = agent.call('','CreateClientAccess', '', <?php echo $CustomerID ?>, 'Added ccustomer product');
				bootbox.alert(AddInvoiceItem, function(){ document.location = 'clientproducts.php?c=<?php echo $CustomerID ?>'; });
				
			}
			else
			{
				bootbox.alert(AddInvoiceItem);	
			}
		}
		else
		{
			bootbox.alert("The quanity required is less than the minimum quantity of " + ThisMinOrder);	
		}
	}
	else
	{
		bootbox.alert("Please fill in all fields marked with a *");	
	}
}

function CheckRecurring()
{
	//TO DO IF SEMI ANNUAL, YEARLY ETC
}

function CheckMinOrder()
{
	var PricingID = document.getElementById("productprice").value;
	
	if (PricingID != "")
	{
		var MinOrder = agent.call('','GetMinOrderProduct', '', PricingID);
		
		var ThisMinOrder = MinOrder[0];
		var ProRata = MinOrder[1];
		
		if (parseInt(ProRata) == 0)
		{
			
			//DISABLE PRO RATA INVOICING
			document.getElementById("prorataproductprorata").disabled = true;
			document.getElementById("prorataproductprorata").checked = false;
		}
		else
		{
			document.getElementById("prorataproductprorata").disabled = false;
		}
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

function RemoveLine(InvoiceLineItemID)
{
	bootbox.confirm("Are you sure you would like to delete this invoice line?", function(result)
	{ 
		if (result === true)
		{
			var DelLine = agent.call('','DeleteInvoiceLine','', InvoiceLineItemID, '<?php echo $InvoiceID ?>');	
			if (DelLine == "OK")
			{
				document.location.reload();	
			}
			
		}
	 });
}

function AddCustom()
{
	var CustomItem = document.getElementById("customitem").value;	
	var CustomItemPrice = document.getElementById("customitemprice").value;
	var CustomQuantity = document.getElementById("customquantity").value;
	
	if (CustomItem != "" && CustomItemPrice != "" && parseInt(CustomQuantity) > 0)
	{
		var AddCustom = agent.call('','AddCustomInvoiceItemRecurring','', CustomItem, CustomItemPrice, CustomQuantity);
		if (AddCustom == "OK")
		{
			document.location.reload();
		}
		else
		{
			bootbox.alert(AddCustom);	
		}
	}
	else
	{
		bootbox.alert("Please fill in all fields to save your custom item to the invoice");	
	}
}

function PublishInvoice(PublishType)
{
	var HasLines = '<?php echo $NumLines ?>';
	
	if (parseInt(HasLines) > 0)
	{
		var DoPublish = agent.call('','PublishInvoice','', 	'<?php echo $InvoiceID ?>', PublishType);
		if (DoPublish == "OK")
		{
			bootbox.alert('The invoice has been published successfully', function() {
						document.location = 'clientinvoices.php?c=<?php echo $CustomerID ?>';
			});
		}
		else
		{
			bootbox.alert(DoPublish);	
		}
	}
	else
	{
		bootbox.alert("There are no invoice lines found, please add some items for this invoice");
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
                    <h1 class="page-header">Customer Details <?php echo $TopCompanyName ?> <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                
                <!-- /.col-lg-12 -->
            </div>
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to add a new manual invoice to the customer profile
                            </div>     
            <!-- /.row -->
            <div class="row">
              <div class="col-lg-12">
                    
					 <!-- Nav tabs -->
                            <ul class="nav nav-tabs responsive">
                                <li ><a href="clientinfo.php?c=<?php echo $CustomerID ?>">Summary</a>
                                </li>
                                <li ><a href="clientprofile.php?c=<?php echo $CustomerID ?>">Profile</a>
                                </li>
                                <li ><a href="clientcontacts.php?c=<?php echo $CustomerID ?>">Contacts</a>
                                </li>
                                
                                <li><a href="clientinvoices.php?c=<?php echo $CustomerID ?>">Invoices</a>
                                </li>
                                <li><a href="clientquotes.php?c=<?php echo $CustomerID ?>">Quotes</a>
                                </li>
                                <li><a href="clienttransactions.php?c=<?php echo $CustomerID ?>">Transactions</a>
                                </li>
                                <li><a href="clientstatement.php?c=<?php echo $CustomerID ?>">Statement</a>
                                </li>
                                <li ><a href="clientdocuments.php?c=<?php echo $CustomerID ?>">Documents</a>
                                </li>
                                <li ><a href="clientnotes.php?c=<?php echo $CustomerID ?>">Notes</a>
                                </li>
                                
                                <li class="active"><a href="clientproducts.php?c=<?php echo $CustomerID ?>">Products</a>
                                </li>
                                <li><a href="clienttask.php?c=<?php echo $CustomerID ?>">Tasks</a>
                                </li>
                                <li><a href="clientfollowup.php?c=<?php echo $CustomerID ?>">Follow Ups</a>
                                </li>
                               <li><a href="cientsites.php?c=<?php echo $CustomerID ?>">Sites</a>
                                </li>
                               <li><a href="clientlogs.php?c=<?php echo $CustomerID ?>">Logs</a>
                                </li>
                                
                                <li class="pull-right"><a href="showclients.php"><i class="fa fa-caret-left"></i> Back to All Customers</a>
                                </li>
                               
                            </ul>
                  
                        
                <?php if ($Access == 1) { ?>                 
                                    
                <div class="col-lg-6" style="padding-top: 10px">
                             
                                       
                                                
                                                 <div class="clearfix"></div>
                                                <h4 style="padding-bottom: 10px">Add Recurring Product</h4>
                                                
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
                                                    <select class="form-control" id="productprice" onChange="javascript: CheckMinOrder(); CheckRecurring();"> 
                                                        <option value="" selected>Please select</option>
                                                       
                                                    </select>
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="quantity" class="col-sm-3 col-form-label" style="padding-top: 5px">Quantity Required *</label>
                                                  <div class="col-sm-6">
                                                   	<input type="text" name="quantity" class="form-control" id="quantity">
                                                     
                                                  
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="quantity" class="col-sm-3 col-form-label" style="padding-top: 5px">From Warehouse *</label>
                                                  <div class="col-sm-6">
                                                   
                                                     <select name="warehouse" class="form-control" id="warehouse">
                                                     	<option value="" selected>Please select</option>
                                                        <?php while ($Val = mysqli_fetch_array($Warehouses))
														{
															$WarehouseID = $Val["WarehouseID"];
															$WarehouseName = $Val["WarehouseName"];	
														?>
                                                        <option value="<?php echo $WarehouseID ?>"><?php echo $WarehouseName ?></option>
                                                        <?php 
														} 
														?>

                                                    </select>
                                                  
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="quantity" class="col-sm-3 col-form-label" style="padding-top: 5px">How many times to recur (term - 0 indefinite) *</label>
                                                  <div class="col-sm-6">
                                                   	<input type="text" name="recurringtimes" class="form-control" id="recurringtimes">
                                                     
                                                  
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="product" class="col-sm-3 col-form-label" style="padding-top: 5px">Invoice Options *</label>
                                                  <div class="col-sm-9">
                                                    <input type="radio" class="" name="invoiceproduct" id="prorataproductprorata"> Create Pro Rata Invoice until <?php echo $NextRun ?> and recur from <?php echo $NextRun ?> onwards
                                                    <br><input type="radio" name="invoiceproduct" class="" id="prorataproductfull" checked> 
                                                    Create full invoice now and recur when due<br><input type="radio" name="invoiceproduct" class="" id="prorataproductnext"> 
                                                    Create full invoice on next system recurring invoice date (<?php echo $NextRun ?>)
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px"></label>
                                                  <div class="col-sm-4">
                                                    <button class="btn btn-info pull-right" onClick="javascript: AddItem();">Add Product</button>
                                                  </div>
                                                </div>
                                                
                                                
                                               
                                                
                                               
                                                
                                                <!-- END FORM CONTROLS -->
                                                
                                          
                                        
                                    
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
	
	document.getElementById("customermenu").className = 'active';
	document.getElementById("customermenucustomer").className = 'active';
	
	$('.datepicker').datepicker({
    	dateFormat: 'yy-mm-dd',
		minDate: new Date(<?php echo date("Y") ?>, <?php echo date("m") ?> - 1, <?php echo date("d") ?>)
});
</script>


</body>

</html>
