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

		$QuoteID = $_REQUEST["q"];
		$QuoteDetails = GetQuoteDetails($QuoteID);

		while ($Val = mysqli_fetch_array($QuoteDetails))
		{
			$QuoteNumber = $Val["QuoteNumber"];
			$ExpiryDate = $Val["ExpiryDate"];
			$DiscountPercent = $Val["DiscountPercent"];
			$Taxed = $Val["Taxed"];
			$QuoteStatus = $Val["QuoteStatus"];
			$Address1 = $Val["Address1"];
			$Address2 = $Val["Address2"];
			$City = $Val["City"];
			$State = $Val["State"];
			$PostCode = $Val["PostCode"];
			$CountryID = $Val["CountryID"];
			$ProposalText = $Val["ProposalText"];
			$FooterText = $Val["FooterText"];
		}


		$Countries = GetCountries();
		$ProductGroups = GetAllActiveProductGroups();

		$QuoteBillingType = GetQuoteBillingType($QuoteID);
		$NumLines = mysqli_num_rows($QuoteBillingType);

		//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
		if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;
	}
		else
		{
			$Access = CheckPageAccess('Quotes');
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

function GetProductPricing()
{
	var Product = document.getElementById("product").value;
	document.getElementById("productprice").options.length = 0;

	if (Product != "")
	{
		var GetPrices = agent.call('','GetProductPricingArrayQuote','', Product);

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

	var MinOrder = CheckMinOrder(Price);

	if (parseInt(Product) > 0 && parseInt(Price) > 0 && parseFloat(Quantity) >= 0.1)
	{
		if (Quantity >= MinOrder)
		{
			var AddQuoteItem = agent.call('','AddQuoteLine','', '<?php echo $QuoteID ?>', Product, Price, Quantity, '<?php echo $DiscountPercent ?>','<?php echo $CustomerID ?>');
			if (AddQuoteItem == "OK")
			{
				document.location.reload();
			}
			else
			{
				bootbox.alert(AddQuoteItem);
			}
		}
		else
		{
			bootbox.alert("The quantoty entered is less than the minimum order of " + MinOrder);
		}
	}
	else
	{
		bootbox.alert("Please select the product, price and quantity to add");
	}
}

function CheckMinOrder(PricingID)
{
		var MinOrder = agent.call('','GetMinOrder', '', PricingID);
		return MinOrder;
}

function AddQuantity(Text, Value)
{
	var QuantityBox = document.getElementById("quantity");
	var opt = document.createElement("option");
	QuantityBox.options.add(opt);
    opt.text = Text;
    opt.value = Value;
}

function RemoveLine(QuoteLineItemID)
{
	bootbox.confirm("Are you sure you would like to delete this quote line?", function(result)
	{
		if (result === true)
		{
			var DelLine = agent.call('','DeleteQuoteLine','', QuoteLineItemID, '<?php echo $QuoteID ?>');
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
	var BillingType = document.getElementById("custombillingtype").value;

	if (CustomItem != "" && CustomItemPrice != "" && parseFloat(CustomQuantity) >= 0.1)
	{
		var AddCustom = agent.call('','AddCustomQuoteItem','', '<?php echo $QuoteID ?>', CustomItem, CustomItemPrice, CustomQuantity, '<?php echo $DiscountPercent ?>', BillingType,"<?php echo $CustomerID?>");

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

function PublishQuote(PublishType)
{
	var HasLines = '<?php echo $NumLines ?>';

	if (parseInt(HasLines) > 0)
	{
		var DoPublish = agent.call('','PublishQuote','', 	'<?php echo $QuoteID ?>', PublishType, '<?php echo $CustomerID ?>');
		if (DoPublish == "OK")
		{
			bootbox.alert('The quote has been created successfully', function() {
						document.location = 'clientquotes.php?c=<?php echo $CustomerID ?>';
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

function UpdateProposal()
{
	var ProposalText = document.getElementById("proposaltext").value;

	var DoUpdateProposal = agent.call('','UpdateQuoteProposal','', '<?php echo $QuoteID ?>', ProposalText);
	if (DoUpdateProposal == "OK")
	{
		document.location.reload();
	}
	else
	{
		bootbox.alert(DoUpdateProposal);
	}
}

function UpdateFooter()
{
	var FooterText = document.getElementById("footertext").value;

	var DoUpdateFooter = agent.call('','UpdateQuoteFooter','', '<?php echo $QuoteID ?>', FooterText);
	if (DoUpdateFooter == "OK")
	{
		document.location.reload();
	}
	else
	{
		bootbox.alert(DoUpdateFooter);
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
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to add a new quote to the customer profile
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

                                <li ><a href="clientinvoices.php?c=<?php echo $CustomerID ?>">Invoices</a>
                                </li>
                                <li class="active"><a href="clientquotes.php?c=<?php echo $CustomerID ?>">Quotes</a>
                                </li>
                                <li><a href="clienttransactions.php?c=<?php echo $CustomerID ?>">Transactions</a>
                                </li>
                                <li><a href="clientstatement.php?c=<?php echo $CustomerID ?>">Statement</a>
                                </li>
                                <li ><a href="clientdocuments.php?c=<?php echo $CustomerID ?>">Documents</a>
                                </li>
                                <li ><a href="clientjobcards.php?c=<?php echo $CustomerID ?>">Job Cards</a>
                                </li>
                                <li ><a href="clientnotes.php?c=<?php echo $CustomerID ?>">Notes</a>
                                </li>

                                <li><a href="clientproducts.php?c=<?php echo $CustomerID ?>">Products</a>
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
                         <h4 style="padding-bottom: 10px">Proposal Text</h4>
                         <div class="col-sm-9">
                         <textarea class="form-control" id="proposaltext"><?php echo $ProposalText ?></textarea>
                         <button class="btn btn-info pull-right" onClick="javascript: UpdateProposal();" style="margin-top: 10px">Update</button>
                         </div>
                         </div>

                         <div class="col-lg-6" style="padding-top: 10px">
                         <h4 style="padding-bottom: 10px">Footer Text</h4>
                         <div class="col-sm-9">
                         <textarea class="form-control" id="footertext"><?php echo $FooterText ?></textarea>
                         <button class="btn btn-info pull-right" onClick="javascript: UpdateFooter();" style="margin-top: 10px">Update</button>
                         </div>
                         </div>

                             <div class="col-lg-6" style="padding-top: 10px">



                                                 <div class="clearfix"></div>
                                                <h4 style="padding-bottom: 10px">Add Quote Item</h4>

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
                                                    <select class="form-control" id="productprice">
                                                        <option value="" selected>Please select</option>

                                                        </select>
                                                  </div>
                                                </div>

                                                <div class="form-group row col-md-12">
                                                  <label for="quantity" class="col-sm-3 col-form-label" style="padding-top: 5px">Quantity Required *</label>
                                                  <div class="col-sm-6">
                                                   <input type="text"  name="quantity" class="form-control" id="quantity">


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

                                  <div class="col-lg-6" style="padding-top: 10px">



                                                 <div class="clearfix"></div>
                                                <h4 style="padding-bottom: 10px">Add Custom Item</h4>

                                                <div class="form-group row col-md-12">
                                                  <label for="address1" class="col-sm-3 col-form-label" style="padding-top: 5px">Item *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="customitem" placeholder="Custom Item Name" value="">
                                                  </div>
                                                </div>

                                                <div class="form-group row col-md-12">
                                                  <label for="address1" class="col-sm-3 col-form-label" style="padding-top: 5px">Price (ex VAT) *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="customitemprice" placeholder="Custom Item Price" value="">
                                                  </div>
                                                </div>

                                                <div class="form-group row col-md-12">
                                                  <label for="address1" class="col-sm-3 col-form-label" style="padding-top: 5px">Billing Type *</label>
                                                  <div class="col-sm-6">

                                                     <select name="custombillingtype" class="form-control" id="custombillingtype">

                                                     	<option value="Once-Off" selected>Once-Off</option>
                                                        <option value="Monthly" >Monthly</option>
                                                        <option value="Quarterly" >Quarterly (3 months)</option>
                                                        <option value="Semi-Annually" >Semi-Annually (6 months)</option>
                                                        <option value="Annually" >Annually (12 months)</option>

                                                    </select>

                                                  </div>
                                                </div>

                                                <div class="form-group row col-md-12">
                                                  <label for="address1" class="col-sm-3 col-form-label" style="padding-top: 5px">Quantity Required *</label>
                                                  <div class="col-sm-6">
                                                   <input type="text"  name="customquantity" class="form-control" id="customquantity">


                                                  </div>
                                                </div>

                                                 <div class="form-group row col-md-12">
                                                  <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px"></label>
                                                  <div class="col-sm-4">
                                                    <button class="btn btn-info pull-right" onClick="javascript: AddCustom();">Add Custom Item</button>
                                                  </div>
                                                </div>




                                                <!-- END FORM CONTROLS -->




                                  </div>
                            <!-- /.table-responsive -->



                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-lg-12">
                	 <?php while ($BillingVal = mysqli_fetch_array($QuoteBillingType))
		   {
			   $ThisType = $BillingVal["BillingType"];
			   $QuoteLines = GetQuoteLines($QuoteID, $ThisType);
			?>
                	 <div class="col-lg-12" style="padding-top: 10px">
                	<h4 style="padding-bottom: 10px">Quote Lines - <?php echo $ThisType ?></h4>

                    <table width="100%" class="table table-striped table-bordered table-hover" style="font-size: 12px" id="invoicetable">
                                	<thead>
                                    <tr>


                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Sub Total</th>

                                        <th>Discount</th>
                                        <th>VAT</th>

                                        <th>Total</th>


                                    </tr>
                                </thead>
                                <tbody>
                                	<?php
									$InvoiceSub = 0;
									$InvoiceDiscount = 0;
									$InvoiceVat = 0;
									$InvoiceTotal = 0;

									while ($Val = mysqli_fetch_array($QuoteLines))
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

										$QuoteLineItemID = $Val["QuoteLineItemID"];

									?>

                                    <tr class="odd gradeX">


                                        <td><a href="javascript: RemoveLine(<?php echo $QuoteLineItemID ?>)"><span class="fa fa-close" style="color: red"></span></a> <?php echo $Description ?> (<?php echo $Meassure ?>) </td>
                                        <td width="">R<?php echo number_format($Price,2) ?></td>
                                        <td width=""><?php echo $Quantity ?></td>
                                        <td width="">R<?php echo number_format($LineSub,2) ?></td>
                                       <td width="">R<?php echo number_format($Discount,2) ?></td>
                                       <td width="">R<?php echo number_format($Vat,2) ?></td>
                                      <td>R<?php echo number_format($LineTotal,2) ?></td>


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
                                          <td colspan=""></td>
                                        <td colspan=""><strong>Quote Sub Total</strong></td>
                                        <td colspan="" id="invoicesubtotal">R<?php echo number_format($InvoiceSub,2) ?></td>

                                    </tr>
                                    <tr>

                                        <td colspan=""></td>
                                        <td colspan=""></td>
                                         <td colspan=""></td>
                                          <td colspan=""></td>
                                          <td colspan=""></td>
                                        <td colspan=""><strong>Discount</strong></td>
                                        <td colspan="" id="invoicediscount">R<?php echo number_format($InvoiceDiscount,2) ?></td>

                                    </tr>
                                    <tr>

                                        <td colspan=""></td>
                                        <td colspan=""></td>
                                         <td colspan=""></td>
                                          <td colspan=""></td>
                                          <td colspan=""></td>
                                        <td colspan=""><strong>Quote VAT</strong></td>
                                        <td colspan="" id="invoicevat">R<?php echo number_format($InvoiceVat,2) ?></td>

                                    </tr>
                                    <tr>

                                        <td colspan=""></td>
                                        <td colspan=""></td>
                                         <td colspan=""></td>
                                          <td colspan=""></td>
                                          <td colspan=""></td>
                                        <td colspan=""><strong>Quote Total</strong></td>
                                        <td colspan="">R<span id="invoicetotal"><?php echo number_format($InvoiceTotal,2) ?></span></td>

                                    </tr>
                                </tbody>
                                </table>
                </div>
                <?php } ?>
                <div class="col-md-12" style="margin-bottom: 50px" align="right">
                	 <button class="btn btn-success" onClick="javascript: PublishQuote('email');">Create & Email</button> <button class="btn btn-warning" onClick="javascript: PublishQuote('publish');"  style="marign-right: 20px">Create</button> <a class="btn btn-danger" href="showquote.php?q=<?php echo $QuoteID ?>&c=<?php echo $CustomerID ?>" target="_blank">Preview Customer Quote</a>
                </div>
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

	document.getElementById("customermenu").className = 'active';
	document.getElementById("customermenucustomer").className = 'active';

	$('.datepicker').datepicker({
    	dateFormat: 'yy-mm-dd',
		minDate: new Date(<?php echo date("Y") ?>, <?php echo date("m") ?> - 1, <?php echo date("d") ?>)
});
</script>


</body>

</html>
