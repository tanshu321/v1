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
		
		//LETS AUTO WORK OUT A 7 DAY DUE DATE
		$DueDate = date("Y-m-d", mktime(0,0,0, date("m"), date("d") + 31, date("Y")));
		$Countries = GetCountries();
		
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
function AddQuote()
{
	
	var Expiry = document.getElementById("datepicker").value;
	var VATNumber = document.getElementById("vatnum").value;
	var DiscountPercent = document.getElementById("discount").value;
	
	var Address1 = document.getElementById("address1").value;
	var Address2 = document.getElementById("address2").value;
	var City = document.getElementById("city").value;
	var State = document.getElementById("state").value;
	var PostCode = document.getElementById("postcode").value;
	var Country = document.getElementById("country").value;
	
	var Error = 0;
	

	
	if (Error == 0)
	{
		if (Expiry != "" && Address1 != "" && City != "" && State != "" && PostCode != "" && Country != "")
		{
			var AddQuoteHeader = agent.call('','AddQuoteHeader','', Expiry, VATNumber, DiscountPercent, Address1, Address2, City, State, PostCode, Country, '<?php echo $CustomerID ?>');
			
			
			if (parseInt(AddQuoteHeader) > 0)
			{
				var Log = agent.call('','CreateClientAccess', '', <?php echo $CustomerID ?>, 'Added Customer Quote QU000000' + AddQuoteHeader);
				document.location = 'editcustomerquote.php?c=<?php echo $CustomerID ?>&q=' + AddQuoteHeader;
			}
			else
			{
				bootbox.alert(AddQuoteHeader);
			}
		}
		else
		{
			bootbox.alert("Please fill in all fields marked with a *");
		}
	}
	
}

function CheckUseInvoice()
{
	var IsChecked = document.getElementById("useinvoice").checked;
	if (IsChecked == true)
	{
		document.getElementById("reference").value = '';
		document.getElementById("reference").disabled = true;	
	}
	else
	{
		document.getElementById("reference").disabled = false;		
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
                             <div class="col-lg-12" style="padding-top: 10px">
                             
                                        <h4 style="padding-bottom: 10px">Quote Details</h4>
                                                
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="documentgroup" class="col-sm-5 col-form-label" style="padding-top: 5px">Expiry Date *</label>
                                                  <div class="col-sm-6">
                                                    
                                                    <input type="text" class="form-control datepicker" id="datepicker" name="datepicker" placeholder="Expiry Date"  data-date-format="yyyy-mm-dd" value="<?php echo $DueDate ?>">
                                                  </div>
                                                </div>
                                                
                                                
                                                
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
                                               
                                                
                                               
                                                
                                                <!-- END FORM CONTROLS -->
                                                 <div class="form-group row col-md-12" align="right" style="padding-top: 30px; padding-right: 120px">
                                                	<button class="btn btn-info" onClick="javascript: AddQuote();" style="">Next >></button> 
                                                
                                                   
                                            </div>
                                          
                                        
                                    
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
