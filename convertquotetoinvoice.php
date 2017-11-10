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
		}
		
		if ($QuoteStatus == 1)
		{
			$QuoteLabel = ' <div class="alert alert-warning">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								   <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This quote is currently pending
									</div>';	
		}
		else if ($QuoteStatus == 2)
		{
			$QuoteLabel = ' <div class="alert alert-success">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								   <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This quote was accepted
									</div>';	
		}
		else if ($QuoteStatus == 3)
		{
			$QuoteLabel = ' <div class="alert alert-danger">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								   <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This quote was declined
									</div>';	
		}
		
		$Today = date("Y-m-d");
		
		if ($Today > $ExpiryDate)
		{
			$InvoiceLabel = ' <div class="alert alert-danger">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								   <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This quote has expired
									</div>';	
			$QuoteStatus = 4;
		}
		
		
		
		$Countries = GetCountries();
		$ProductGroups = GetAllActiveProductGroups();
		
		$QuoteBillingType = GetQuoteBillingType($QuoteID);
		$NumLines = mysqli_num_rows($QuoteBillingType);
		
		//$QuoteLines = GetQuoteLines($QuoteID);
		//$NumLines = mysqli_num_rows($QuoteLines);
		
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
function UpdateQuoteStatus()
{
	var NewStatus = document.getElementById("newstatus").value;
	var DoUpdateStatus = agent.call('','UpdateQuoteStatus','', NewStatus, '<?php echo $QuoteID ?>');
	if (DoUpdateStatus == "OK")
	{
		if (NewStatus == 1)
		{
			NewStatus = 'Pending';	
		}
		if (NewStatus == 2)
		{
			NewStatus = 'Accepted';	
		}
		if (NewStatus == 3)
		{
			NewStatus = 'Declined';	
		}
		
		
		var Log = agent.call('','CreateClientAccess', '', <?php echo $CustomerID ?>, 'Updates Customer Quote QU000000<?php echo $QuoteID ?> status to ' + NewStatus);
		
		bootbox.alert('Quote status updated successfully', function() {
						document.location = 'clientquotes.php?c=<?php echo $CustomerID ?>';
			});
	}
	else
	{
		bootbox.alert(DoUpdateStatus);	
	}
}

function ResendQuote()
{
	var Resend = agent.call('','EmailCustomerQuote','', '<?php echo $QuoteID ?>', '<?php echo $CustomerID ?>');	
	if (Resend == "OK")
	{
		bootbox.alert("Customer quote has been resent successfully");
	}
	else
	{
		bootbox.alert(Resend);	
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
               <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to convert a quote to an invoice
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
                                <li  class="active"><a href="clientquotes.php?c=<?php echo $CustomerID ?>">Quotes</a>
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

                            <!-- /.table-responsive -->
                            
                       
                   
                </div>
                <!-- /.col-lg-12 -->
                
                
            </div>
            <!-- /.row -->
            
            
           
          
            <div class="row">
            
            <div class="col-lg-12" style="padding-top: 10px">
                             
                                        <h4 style="padding-bottom: 10px">Invoice Details</h4>
                                                <div class="form-group row col-md-6">
                                                  <label for="documentname" class="col-sm-5 col-form-label" style="padding-top: 5px">Customer Reference *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="reference" name="reference" placeholder="Customer Reference" value="" disabled>
                                                    <input type="checkbox" id="useinvoice" onChange="javascript: CheckUseInvoice();" checked> Use Invoice Number 
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="documentgroup" class="col-sm-5 col-form-label" style="padding-top: 5px">Due Date *</label>
                                                  <div class="col-sm-6">
                                                    
                                                    <input type="text" class="form-control datepicker" id="datepicker" name="datepicker" placeholder="Due Date"  data-date-format="yyyy-mm-dd" value="<?php echo $DueDate ?>">
                                                  </div>
                                                </div>
                                                
                                                <div class="clearfix"></div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="documentgroup" class="col-sm-5 col-form-label" style="padding-top: 5px">VAT Number</label>
                                                  <div class="col-sm-6">
                                                    
                                                    <input type="text" class="form-control" id="vatnum" name="vatnum" placeholder="VAT Number" value="<?php echo $VatNumber ?>">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="documentgroup" class="col-sm-5 col-form-label" style="padding-top: 5px">Discount %</label>
                                                  <div class="col-sm-6">
                                                    
                                                    <input type="text" class="form-control" id="discount" name="discount" placeholder="Discount %" value="0">
                                                  </div>
                                                </div>
                                                 <div class="clearfix"></div>
                                                <h4 style="padding-bottom: 10px">Billing Address</h4>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="address1" class="col-sm-5 col-form-label" style="padding-top: 5px">Address 1 *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="address1" placeholder="Address 1" value="<?php echo $Address1 ?>">
                                                  </div>
                                                </div>
                                                
                                                 <div class="form-group row col-md-6">
                                                  <label for="address2" class="col-sm-5 col-form-label" style="padding-top: 5px">Address 2</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="address2" placeholder="Address 2" value="<?php echo $Address2 ?>">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="city" class="col-sm-5 col-form-label" style="padding-top: 5px">City *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="city" placeholder="City" value="<?php echo $City ?>">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="state" class="col-sm-5 col-form-label" style="padding-top: 5px">State/Region *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="state" placeholder="State/Region" value="<?php echo $Region ?>">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="postcode" class="col-sm-5 col-form-label" style="padding-top: 5px">Post Code *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="postcode" placeholder="Post Code" value="<?php echo $PostCode ?>">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px">Country *</label>
                                                  <div class="col-sm-6">
                                                    <select id="country" class="form-control">
                                                        <?php while ($Val = mysqli_fetch_array($Countries))
                                                        {
                                                            $CountryID = $Val["CountryID"];
                                                            $CountryName = $Val["CountryName"];
                                                            
                                                            if ($ClientCountryID == $CountryID)
                                                            {
                                                                $Selected = 'selected';	
                                                            }
                                                            else
                                                            {
                                                                $Selected = '';	
                                                            }
                                                        ?>
                                                            <option value="<?php echo $CountryID ?>" <?php echo $Selected ?>><?php echo $CountryName ?></option>
                                                        <?php } ?>
                                                    </select>
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="state" class="col-sm-5 col-form-label" style="padding-top: 5px">Customer Invoice Notes</label>
                                                  <div class="col-sm-6">
                                                    <textarea class="form-control" id="invoicenotes" placeholder="Customer Invoice Notes" value=""></textarea>
                                                  </div>
                                                </div>
                                               
                                                
                                               
                                                
                                                <!-- END FORM CONTROLS -->
                                                
                                          
                                        
                                    
                                  </div>
            
                <div class="col-lg-12">
                 <?php 
				 $AllQuoteLines = '';
				 while ($BillingVal = mysqli_fetch_array($QuoteBillingType))
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
                                        <th>From Warehouse *</th>
                                        
                                        <th>How many times to recur (term - 0 indefinite) *</th>
                                        <th>Invoice Options</th>
                                        
                                        
                                        
                                        
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
										
										$Vat = $Val["LineVat"];
										$InvoiceVat = $InvoiceVat + $Vat;
										
										$Meassure = $Val["MeassurementDescription"];
										
										$LineTotal = $Val["LineTotal"];
										$InvoiceTotal = $InvoiceTotal + $LineTotal;
										
										$QuoteLineItemID = $Val["QuoteLineItemID"];
										$ProductCostID = $Val["ProductCostID"];
										$ProductID = $Val["ProductID"];
										
										$AllQuoteLines .= $QuoteLineItemID . ",";
										
										$Warehouses = GetAllWarehouses();
										
										//CHECK IF PRO RATA ALLOWED
										$HasProRata = 0; //INITIALIZE NOT TO HAVE
										
										if ($ProductCostID != 0)
										{
											$HasProRata = CheckProRata($ProductCostID);
										}
										
										$IsStickItem = 0;
										
										if ($ProductID > 0)
										{
											$IsStockItem = CheckStockItem($ProductID);
										}
										
									?>
										
                                    <tr class="odd gradeX">
                                        
                                        
                                        <td><?php echo $Description ?> (<?php echo $Meassure ?>)</td>
                                        <td width="">R<?php echo number_format($Price,2) ?></td>
                                        <td width=""><?php echo $Quantity ?></td>
                                        <td width=""><select name="warehouse<?php echo $QuoteLineItemID ?>" class="form-control" id="warehouse<?php echo $QuoteLineItemID ?>">
                                                     	
                                                        <?php while ($Val = mysqli_fetch_array($Warehouses))
														{
															$WarehouseID = $Val["WarehouseID"];
															$WarehouseName = $Val["WarehouseName"];
															
															if ($WarehouseName == "Main")
															{
																$Selected = 'selected';	
															}
															else
															{
																$Selected ='';	
															}
														?>
                                                        <option value="<?php echo $WarehouseID ?>" <?php echo $Selected ?>><?php echo $WarehouseName ?></option>
                                                        <?php 
														} 
														?>

                                                    </select></td>
                                       <td width="">
                                       <?php if ($ThisType == "Once-Off") { ?>
                                       <input type="text" name="recurringtimes<?php echo $QuoteLineItemID ?>" class="form-control" id="recurringtimes<?php echo $QuoteLineItemID ?>" value="1" disabled>
                                       <?php } else { ?>
                                       <input type="text" name="recurringtimes<?php echo $QuoteLineItemID ?>" class="form-control" id="recurringtimes<?php echo $QuoteLineItemID ?>" >
                                       <?php } ?>
                                       </td>
                                       <td width="">
                                       <?php if ($ThisType == "Once-Off") { ?>
                                      
                                       <input type="radio" class="" name="invoiceproduct<?php echo $QuoteLineItemID ?>" id="prorataproductprorata<?php echo $QuoteLineItemID ?>" disabled> Create Pro Rata Invoice until <?php echo $NextRun ?> and recur from <?php echo $NextRun ?> onwards
                                        <br>
                                        <input type="radio" name="invoiceproduct<?php echo $QuoteLineItemID ?>" class="" id="prorataproductfull<?php echo $QuoteLineItemID ?>" disabled checked> 
                                        Create full invioce now
                                        <br>
                                        <input type="radio" name="invoiceproduct<?php echo $QuoteLineItemID ?>" class="" id="prorataproductnext<?php echo $QuoteLineItemID ?>"  disabled> 
                                        Create full invioce on next system recurring invoice date (<?php echo $NextRun ?>)
                                       <?php } else { ?>
                                       <?php if ($HasProRata == 0) { ?>
                                       <input type="radio" class="" name="invoiceproduct<?php echo $QuoteLineItemID ?>" id="prorataproductprorata<?php echo $QuoteLineItemID ?>" disabled> Create Pro Rata Invoice until <?php echo $NextRun ?> and recur from <?php echo $NextRun ?> onwards
                                        <?php } else { ?>
                                        <input type="radio" class="" name="invoiceproduct<?php echo $QuoteLineItemID ?>" id="prorataproductprorata<?php echo $QuoteLineItemID ?>"> Create Pro Rata Invoice until <?php echo $NextRun ?> and recur from <?php echo $NextRun ?> onwards
                                        <?php } ?>
                                        <br><input type="radio" name="invoiceproduct<?php echo $QuoteLineItemID ?>" class="" id="prorataproductfull<?php echo $QuoteLineItemID ?>" checked> 
                                        Create full invioce now and recur when due<br><input type="radio" name="invoiceproduct<?php echo $QuoteLineItemID ?>" class="" id="prorataproductnext<?php echo $QuoteLineItemID ?>"> 
                                        Create full invioce on next system recurring invoice date (<?php echo $NextRun ?>)
                                       <?php } ?>
                                       
                                       </td>
                                      
                                      
                                        
                                    </tr>
                                    <?php } ?>
                                  	
                                   
                                </tbody>
                                </table>
                </div>
                <?php } ?>
                <?php if ($QuoteStatus != 4) { ?>
                <div class="col-md-12" style="margin-bottom: 50px; " align="right">
                	 <a href="javascript: AddInvoice();" class="btn btn-warning">Convert to Invoice</a>
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
	
	document.getElementById("customermenu").className = 'active';
	document.getElementById("customermenucustomer").className = 'active';
	
	$('.datepicker').datepicker({
    	dateFormat: 'yy-mm-dd',
		minDate: new Date(<?php echo date("Y") ?>, <?php echo date("m") ?> - 1, <?php echo date("d") ?>)
});

function AddInvoice()
{
	
	var UseInvoiceReference = document.getElementById("useinvoice").checked;
	var CustomerReference = document.getElementById("reference").value;
	var DueDate = document.getElementById("datepicker").value;
	var VATNumber = document.getElementById("vatnum").value;
	var DiscountPercent = document.getElementById("discount").value;
	
	var Address1 = document.getElementById("address1").value;
	var Address2 = document.getElementById("address2").value;
	var City = document.getElementById("city").value;
	var State = document.getElementById("state").value;
	var PostCode = document.getElementById("postcode").value;
	var Country = document.getElementById("country").value;
	
	var InvoiceNotes = document.getElementById("invoicenotes").value;
	
	var Error = 0;
	
	if (UseInvoiceReference == false && CustomerReference == "")
	{
		bootbox.alert("Please enter a customer reference");	
		Error = 1;
	}
	
	if (Error == 0)
	{
		if (DueDate != "" && Address1 != "" && City != "" && State != "" && PostCode != "" && Country != "")
		{
			var AddInvoiceHeader = agent.call('','AddInvoiceHeader','', UseInvoiceReference, CustomerReference, DueDate, VATNumber, DiscountPercent, Address1, Address2, City, State, PostCode, Country, '<?php echo $CustomerID ?>', '<?php echo $TaxExempt ?>', InvoiceNotes, 0);
			
			
			if (parseInt(AddInvoiceHeader) > 0)
			{
				var Log = agent.call('','CreateClientAccess', '', <?php echo $CustomerID ?>, 'Added Customer Invoice INV' + AddInvoiceHeader);
				ConvertQuoteToInvoice(AddInvoiceHeader);
			}
			else
			{
				bootbox.alert(AddInvoiceHeader);
			}
		}
		else
		{
			bootbox.alert("Please fill in all fields marked with a *");
		}
	}
	
}

function ConvertQuoteToInvoice(InvoiceID)
{
	var AllQuoteLines = '<?php echo rtrim($AllQuoteLines, ",") ?>';
	var QuoteLineArray = AllQuoteLines.split(",");
	

	//NOW WE NEED TO LOOP THROUGH AND MAKE SURE ALL WAREHOUSE, RECURRING AND OPTIONS SELECTED
	var Error = 0;
	var InstaInvoice = 0;
	
	//VALIDATION
	for (i = 0; i < QuoteLineArray.length; i++) 
	{
    	if (Error == 0)
		{
			var ThisQuoteLineID = QuoteLineArray[i];
			
			var ThisWarehouseID = document.getElementById("warehouse" + ThisQuoteLineID).value;
			var ThisRecurring = document.getElementById("recurringtimes" + ThisQuoteLineID).value;
			
			var ThisBillProRata = document.getElementById("prorataproductprorata" + ThisQuoteLineID).checked;
			var ThisBillFull = document.getElementById("prorataproductfull" + ThisQuoteLineID).checked;
			var ThisBillNext = document.getElementById("prorataproductnext" + ThisQuoteLineID).checked;
			
			if (ThisWarehouseID != "" && ThisRecurring >= 0 && (ThisBillProRata === true || ThisBillFull === true || ThisBillNext === true))
			{
				if (ThisBillProRata === true || ThisBillFull === true)
				{
					InstaInvoice = 1;	
				}
			}
			else
			{
				Error = 1;
				bootbox.alert("Please make sure you have selected a warehouse, recurring period and invoice option for each product in the quote");	
			}
		}
	}
	
	if (Error == 0)
	{
		
		
		var QuoteLineArray = AllQuoteLines.split(",");
	
		
		for (i = 0; i < QuoteLineArray.length; i++) 
		{
			var ThisQuoteLineID = QuoteLineArray[i];
				
			var ThisWarehouseID = document.getElementById("warehouse" + ThisQuoteLineID).value;
			var ThisRecurring = document.getElementById("recurringtimes" + ThisQuoteLineID).value;
				
			var ThisBillProRata = document.getElementById("prorataproductprorata" + ThisQuoteLineID).checked;
			var ThisBillFull = document.getElementById("prorataproductfull" + ThisQuoteLineID).checked;
			var ThisBillNext = document.getElementById("prorataproductnext" + ThisQuoteLineID).checked;
			
			var AddInvoiceQuoteItem = agent.call('','AddInvoiceQuoteItem','', ThisQuoteLineID, ThisWarehouseID, ThisRecurring, ThisBillProRata, ThisBillFull, ThisBillNext, InvoiceID, '<?php echo $CustomerID ?>', '<?php echo $NextRun ?>');
			
			
				
		}
		
		//WHEN ITS DONE WE SHOULD GO TO INVOICE?
		if (InstaInvoice == 1) //SOME ITEMS REQUIRE AN INSTANT INVOICE, FOR THEM TO BE GROUPED WE WILL CREATE ONE SINGLE INVOICE FOR ALL
		{
			//MARK QUOTE AS ACCEPTED
			var AcceptQuote = agent.call('','UpdateQuoteAccepted','', '<?php echo $QuoteID ?>');
			
			bootbox.alert("The quote has been converted and a draft invoice has been created accordingly, please review the invoice and publish", function()
			{ 
				document.location = 'editcustomerinvoice.php?i=' + InvoiceID + '&c=<?php echo $CustomerID ?>';
			
			});
		}
		else
		{
			//NO INVOICE TO CREATE NOW, REMOVE THE INVOICE CREATED - To-Do - THINK	
			var RemoveInvoice = agent.call('','RemoveInvoice','', InvoiceID);
			bootbox.alert("The quote has been converted, but no invoice was due after your selections made", function()
			{ 
				document.location = 'clientquotes.php?c=<?php echo $CustomerID ?>';
			
			});
		}
		
		
	}
}
</script>


</body>

</html>
