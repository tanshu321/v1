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
		
		
		
		if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
		else
		{
			$Access = CheckPageAccess('Contacts');	
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

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<script type="text/javascript">


function UpdateContact()
{
	var Name = document.getElementById("firstname").value;
	var Surname = document.getElementById("surname").value;	
	var CompanyName = document.getElementById("companyname").value;
	var ContactTel = document.getElementById("tel").value;
	var EmailAddress = document.getElementById("emailaddress").value;
	var Department = document.getElementById("department").value;
		

	
	var EmailSupport = document.getElementById("support").checked;
	if (EmailSupport == true)
	{
		EmailSupport = 1;	
	}
	else
	{
		EmailSupport = 0;	
	}
	
	var EmailQuotes = document.getElementById("quotes").checked;
	if (EmailQuotes == true)
	{
		EmailQuotes = 1;	
	}
	else
	{
		EmailQuotes = 0;	
	}
	
	var EmailInvoices = document.getElementById("invoices").checked;
	if (EmailInvoices == true)
	{
		EmailInvoices = 1;	
	}
	else
	{
		 EmailInvoices = 0;	
	}
	
	var AddContacts = document.getElementById("addcontacts").checked;
	if (AddContacts == true)
	{
		AddContacts = 1;	
	}
	else
	{
		 AddContacts = 0;	
	}
	
	var AcceptQuotes = document.getElementById("acceptquotes").checked;
	if (AcceptQuotes == true)
	{
		AcceptQuotes = 1;	
	}
	else
	{
		 AcceptQuotes = 0;	
	}
	
	var ChangeDetails = document.getElementById("changedetails").checked;
	if (ChangeDetails == true)
	{
		ChangeDetails = 1;	
	}
	else
	{
		 ChangeDetails = 0;	
	}
	
	if (Name != "" && Surname != "" && ContactTel != "" && EmailAddress != "")
	{
		var UpdateContact = agent.call('','AddContact','', Name, Surname, CompanyName, ContactTel, EmailAddress, Department, EmailSupport, EmailQuotes, EmailInvoices, AddContacts, AcceptQuotes, ChangeDetails, '<?php echo $CustomerID ?>');
		if (UpdateContact == "OK")
		{
			//LOG EVENT
			var Log = agent.call('','CreateClientAccess', '', <?php echo $CustomerID ?>, 'Contact Added ' + Name + " " + Surname);
					
			bootbox.alert("Contact added successfully", function(){ document.location = 'clientcontacts.php?c=<?php echo $CustomerID ?>'; });
		}
		else
		{
			bootbox.alert(UpdateContact);	
		}
	}
	else
	{
		bootbox.alert("Please fill in all fields marked with a *");
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
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to add a new customer contact
                            </div>     
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    
					 <!-- Nav tabs -->
                           <ul class="nav nav-tabs">
                                <li ><a href="clientinfo.php?c=<?php echo $CustomerID ?>">Summary</a>
                                </li>
                                <li ><a href="clientprofile.php?c=<?php echo $CustomerID ?>">Profile</a>
                                </li>
                                <li  class="active"><a href="clientcontacts.php?c=<?php echo $CustomerID ?>">Contacts</a>
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
                                        <h4 style="padding-bottom: 10px">Contact Details</h4>
                                                <div class="form-group row col-md-6">
                                                  <label for="firstname" class="col-sm-5 col-form-label" style="padding-top: 5px">Contact Name *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="firstname" placeholder="Contact Name" value="">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="surname" class="col-sm-5 col-form-label" style="padding-top: 5px">Surname *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="surname" placeholder="Surname" value="">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">Company Name</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="companyname" placeholder="Company Name" value="">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="tel" class="col-sm-5 col-form-label" style="padding-top: 5px">Contact Number *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="tel" placeholder="Contact Number" value="">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="emailaddress" class="col-sm-5 col-form-label" style="padding-top: 5px">Email Address *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="emailaddress" placeholder="Email Address" value="">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="vatnumber" class="col-sm-5 col-form-label" style="padding-top: 5px">Department</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="department" placeholder="Department" value="">
                                                  </div>
                                                </div>
                                                
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="address2" class="col-sm-5 col-form-label" style="padding-top: 5px">Email Notifications</label>
                                                  <div class="col-sm-6">
                                                      <div class="checkbox">
                                                      			<?php if ($EmailSupport == 1) { $Checked = 'checked'; } else { $Checked = ''; } ?>
                                                                <label><input type="checkbox" id="support" <?php echo $Checked ?>> Support</label>
                                                        </div>
                                                        <div class="checkbox">
                                                        		<?php if ($EmailQuotes == 1) { $Checked = 'checked'; } else { $Checked = ''; } ?>
                                                                <label><input type="checkbox" id="quotes" <?php echo $Checked ?>> Quotes</label>
                                                        </div>
                                                        <div class="checkbox">
                                                       			<?php if ($EmailInvoice == 1) { $Checked = 'checked'; } else { $Checked = ''; } ?>
                                                                <label><input type="checkbox" id="invoices" <?php echo $Checked ?>> Invoices</label>
                                                        </div>
                                                   </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="address2" class="col-sm-5 col-form-label" style="padding-top: 5px">Permissions</label>
                                                  <div class="col-sm-6">
                                                      <div class="checkbox">
                                                      			<?php if ($AddContacts == 1) { $Checked = 'checked'; } else { $Checked = ''; } ?>
                                                                <label><input type="checkbox" id="addcontacts" <?php echo $Checked ?>> Add Additional Contacts</label>
                                                        </div>
                                                        <div class="checkbox">
                                                        		<?php if ($AcceptQuotes == 1) { $Checked = 'checked'; } else { $Checked = ''; } ?>
                                                                <label><input type="checkbox" id="acceptquotes" <?php echo $Checked ?>> Accept Quotes</label>
                                                        </div>
                                                        <div class="checkbox">
                                                        		<?php if ($ChangeDetails == 1) { $Checked = 'checked'; } else { $Checked = ''; } ?>
                                                                <label><input type="checkbox" id="changedetails" <?php echo $Checked ?>> Change Account Details</label>
                                                        </div>
                                                   </div>
                                                </div>
                                                
                                                <!-- END FORM CONTROLS -->
                                                <div class="col-lg-12" align="center" style="padding-top: 20px; padding-bottom: 20px">
                                                	<button class="btn btn-info" onClick="javascript: UpdateContact();" style="margin-right: 20px">Add Contact</button> 
                                                
                                                   
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
</script>
</body>

</html>
