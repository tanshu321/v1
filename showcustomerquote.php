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
			$QuoteStatus = $Val["QuoteStatus"];
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
		
		if ($Today > $ExpiryDate && $QuoteStatus < 2)
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
function UpdateStatus()
{
	var NewStatus = document.getElementById("newstatus").value;
	var ExpiryDate = document.getElementById("datepicker").value;
	
	if (NewStatus != "" && ExpiryDate != "")
	{
		var DoUpdateStatus = agent.call('','UpdateQuoteStatus','', NewStatus, '<?php echo $QuoteID ?>', ExpiryDate);
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
	else
	{
		bootbox.alert("Please make sure you have set the expiry date as well as selected a status");		
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
               <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page shows you the quote details. You can add more items to this quote by using the boxes below.
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
                  
                        
                        
                                    
                             <div class="col-lg-12" style="padding-top: 10px">
                             					
                                                
                                                <div class="clearfix"></div>
                                                <h4 style="padding-bottom: 10px">Quote Details</h4>
                                                
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="documentgroup" class="col-sm-5 col-form-label" style="padding-top: 5px">Expiry Date *</label>
                                                  <div class="col-sm-6">
                                                    
                                                    <input type="text" class="form-control datepicker" id="datepicker" name="datepicker" placeholder="Due Date"  data-date-format="yyyy-mm-dd" value="<?php echo $ExpiryDate ?>">
                                                  </div>
                                                </div>
                                                
                                                 <div class="form-group row col-md-6">
                                                  <label for="documentgroup" class="col-sm-5 col-form-label" style="padding-top: 5px">Quote Status *</label>
                                                  <div class="col-sm-6">
                                                    
                                                     <select id="newstatus" class="form-control form-inline">
                                                	<option value="" selected>Please select status</option>
                                                	 <?php if ($QuoteStatus == 0 && $QuoteStatus != "") { $Selected = 'selected'; } else { $Selected = ''; } ?>
                                                    <option value="0" <?php echo $Selected ?>>Draft</option>
                                                     <?php if ($QuoteStatus == 1) { $Selected = 'selected'; } else { $Selected = ''; } ?>
                                                    <option value="1" <?php echo $Selected ?>>Pending</option>
                                                    <?php if ($QuoteStatus == 2) { $Selected = 'selected'; } else { $Selected = ''; } ?>
                                                    <option value="2" <?php echo $Selected ?>>Accepted</option>
                                                    <?php if ($QuoteStatus == 3) { $Selected = 'selected'; } else { $Selected = ''; } ?>
                                                    <option value="3" <?php echo $Selected ?>>Lost</option>
                                                   
                                                </select>
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="documentgroup" class="col-sm-5 col-form-label" style="padding-top: 5px">VAT Number</label>
                                                  <div class="col-sm-6">
                                                    
                                                    <input type="text" class="form-control" id="vatnum" name="vatnum" placeholder="VAT Number" value="<?php echo $VatNumber ?>" disabled>
                                                  </div>
                                                </div>
                                                
                                                
                                                
                                                
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="documentgroup" class="col-sm-5 col-form-label" style="padding-top: 5px">Discount %</label>
                                                  <div class="col-sm-6">
                                                    
                                                    <input type="text" class="form-control" id="discount" name="discount" placeholder="Discount %" value="0" disabled>
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="documentgroup" class="col-sm-5 col-form-label" style="padding-top: 5px">&nbsp;</label>
                                                  <div class="col-sm-6">
                                                    
                                                    &nbsp;
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="documentgroup" class="col-sm-5 col-form-label" style="padding-top: 5px">&nbsp;</label>
                                                  <div class="col-sm-6">
                                                    
                                                    <button class="btn btn-default pull-right" onClick="javascript: UpdateStatus();">Update</button>
                                                  </div>
                                                </div>
                                                 <div class="clearfix"></div>
                                                <h4 style="padding-bottom: 10px">Billing Address</h4>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="address1" class="col-sm-5 col-form-label" style="padding-top: 5px">Address 1 *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="address1" placeholder="Address 1" value="<?php echo $Address1 ?>" disabled>
                                                  </div>
                                                </div>
                                                
                                                 <div class="form-group row col-md-6">
                                                  <label for="address2" class="col-sm-5 col-form-label" style="padding-top: 5px">Address 2</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="address2" placeholder="Address 2" value="<?php echo $Address2 ?>" disabled>
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="city" class="col-sm-5 col-form-label" style="padding-top: 5px">City *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="city" placeholder="City" value="<?php echo $City ?>" disabled>
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="state" class="col-sm-5 col-form-label" style="padding-top: 5px">State/Region *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="state" placeholder="State/Region" value="<?php echo $Region ?>" disabled>
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="postcode" class="col-sm-5 col-form-label" style="padding-top: 5px">Post Code *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="postcode" placeholder="Post Code" value="<?php echo $PostCode ?>" disabled>
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px">Country *</label>
                                                  <div class="col-sm-6">
                                                    <select id="country" class="form-control"  disabled>
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
                                       <?php
                                       $colspan=0;
                                        if($TaxExempt == 1){
                                            $colspan=2;
                                        }
                                       ?>
                                        
                                        <th colspan="<?php echo  $colspan;?>">Description</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Sub Total</th>
                                        
                                        <th>Discount</th>
                                        <?php

                                        if($TaxExempt == 0) {
                                        ?>
                                        <th>VAT</th>
                                        <?php }?>
                                        
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
                                        
                                        
                                        <td colspan="<?php echo $colspan;?>"><?php echo $Description ?> (<?php echo
                                            $Meassure ?>)
                                        </td>
                                        <td width="">R<?php echo number_format($Price,2) ?></td>
                                        <td width=""><?php echo $Quantity ?></td>
                                        <td width="">R<?php echo number_format($LineSub,2) ?></td>
                                       <td width="">R<?php echo number_format($Discount,2) ?></td>
                                        <?php   if($TaxExempt == 0) {?>
                                       <td width="">R<?php echo number_format($Vat,2) ?></td>
                                        <?php } ?>
                                      <td>R<?php echo number_format($LineTotal,2) ?></td>

                                        
                                    </tr>
                                    <?php } ?>
                                  	
                                    <tr>
                                    	
                                        <td colspan="<?php echo $colspan;?>">&nbsp;</td>
                                        <td colspan=""></td>
                                        <td colspan=""></td>
                                         <td colspan=""></td>
                                          <td colspan=""></td>
                                        <td colspan=""></td>
                                        <?php
                                        if($TaxExempt==0){
                                            ?>
                                            <td colspan=""></td>
                                        <?php } ?>

                                       
                                    </tr>
                                    
                                   
                                    <tr>
                                    	
                                        <td colspan="<?php echo $colspan;?>"></td>
                                        <td colspan=""></td>
                                         <td colspan=""></td>
                                          <td colspan=""></td>
                                        <?php
                                            if($TaxExempt==0){
                                        ?>
                                          <td colspan=""></td>
                                        <?php } ?>
                                        <td colspan=""><strong>Quote Sub Total</strong></td>
                                        <td colspan="" id="invoicesubtotal">R<?php echo number_format($InvoiceSub,2) ?></td>
                                       
                                    </tr>
                                    <tr>
                                    	
                                        <td colspan="<?php echo $colspan;?>"></td>
                                        <td colspan=""></td>
                                         <td colspan=""></td>
                                          <td colspan=""></td>
                                        <?php
                                        if($TaxExempt==0){
                                            ?>
                                            <td colspan=""></td>
                                        <?php } ?>
                                        <td colspan=""><strong>Discount</strong></td>
                                        <td colspan="" id="invoicediscount">R<?php echo number_format($InvoiceDiscount,2) ?></td>
                                        
                                    </tr>
                                    <?php
                                    if($TaxExempt == 0) {
                                    ?>
                                    <tr>
                                    	
                                        <td colspan=""></td>
                                        <td colspan=""></td>
                                         <td colspan=""></td>
                                          <td colspan=""></td>
                                        <?php
                                        if($TaxExempt==0){
                                            ?>
                                            <td colspan=""></td>
                                        <?php } ?>
                                        <td colspan=""><strong>Quote VAT</strong></td>
                                        <td colspan="" id="invoicevat">R<?php echo number_format($InvoiceVat,2) ?></td>
                                       
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                    	
                                        <td colspan="<?php echo $colspan;?>"></td>
                                        <td colspan=""></td>
                                         <td colspan=""></td>
                                          <td colspan=""></td>
                                        <?php
                                        if($TaxExempt==0){
                                            ?>
                                            <td colspan=""></td>
                                        <?php } ?>
                                        <td colspan=""><strong>Quote Total</strong></td>
                                        <td colspan="">R<span id="invoicetotal"><?php echo number_format($InvoiceTotal,2) ?></span></td>
                                        
                                    </tr>
                                </tbody>
                                </table>
                </div>
                <?php } ?>
                <?php if ($QuoteStatus != 4) { ?>
                <div class="col-md-12" style="margin-bottom: 50px; " align="right">
                	 <button class="btn btn-success" onClick="javascript: ResendQuote();">Resend Quote</button> <a href="convertquotetoinvoice.php?q=<?php echo $QuoteID ?>&c=<?php echo $CustomerID ?>" class="btn btn-warning">Convert to Invoice</a> <a class="btn btn-danger" href="showquote.php?q=<?php echo $QuoteID ?>&c=<?php echo $CustomerID ?>" target="_blank">Show Quote</a>
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
</script>


</body>

</html>
