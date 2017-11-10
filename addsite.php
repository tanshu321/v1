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
			$ClientCountryID = $Val["CountryID"];
			if ($CompanyName != "")
			{
				$TopCompanyName = $CompanyName . " ( " . $Name . " " . $Surname . " )";		
			}
			
			$EmailAddress = $Val["EmailAddress"];
			$DateAdded = $Val["DateAdded"];
											
			$ThisStatus = $Val["Status"];
			
			
			
			
			
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
		
		$Countries = GetCountries();
		
		
		
		
		
		//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
		if ($_SESSION["MainClient"] == 1)
		{
			$Access = 1;	
		}
		else
		{
			$Access = CheckPageAccess('Sites');	
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

</head>

<body>

    <div id="wrapper">

        <?php include("navigation.php") ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Site Details <?php echo $TopCompanyName ?> <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
             <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to update the site details.
                            </div>     
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                  
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li ><a href="clientinfo.php?c=<?php echo $CustomerID ?>">Summary</a>
                                </li>
                                <li><a href="clientprofile.php?c=<?php echo $CustomerID ?>">Profile</a>
                                </li>
                                <li><a href="clientcontacts.php?c=<?php echo $CustomerID ?>">Contacts</a>
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
                               <li class="active"><a href="cientsites.php?c=<?php echo $CustomerID ?>">Sites</a>
                                </li>
                               <li><a href="clientlogs.php?c=<?php echo $CustomerID ?>">Logs</a>
                                </li>
                                
                                <li class="pull-right"><a href="showclients.php"><i class="fa fa-caret-left"></i> Back to All Customers</a>
                                </li>
                               
                            </ul>
							
                            <?php if ($Access == 1) { ?>  
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="home" style="padding: 10px; ">
                                    <!-- Start Inside Tab -->
                                    <!-- First Panel Table -->
                                    <div class="col-lg-12">
                                        <h4 style="padding-bottom: 10px">Site Details</h4>
                                                <div class="form-group row col-md-6">
                                                  <label for="firstname" class="col-sm-5 col-form-label" style="padding-top: 5px">Site Name *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="sitename" placeholder="Site Name" value="">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-6">
                                                  <label for="surname" class="col-sm-5 col-form-label" style="padding-top: 5px">Contact Person *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="contactperson" placeholder="Contact Person" value="">
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
                                          
                                        
                                        
                                  </div>
                                    <!-- End first panel -->
                                    
                                   
                                        <div class="col-lg-12" align="center" style="padding-top: 20px; padding-bottom: 20px">
                                                <button class="btn btn-info" onClick="javascript: UpdateClient();">Add Site</button>
                                               
                                    
                                  
                                    
                                    <!-- First Panel Table --><!-- End first panel -->
                                   
                                   
                                    <!-- End inside tab -->
                                </div>
                                
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
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
    </script>
    
    <script type="text/javascript">
	function ValidateEmail(Email) 
	{
		var x = Email;
		var atpos = x.indexOf("@");
		var dotpos = x.lastIndexOf(".");
		if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
		   
			return false;
		}
		else
		{
			return true;	
		}
	}

	function UpdateClient()
	{
		var SiteName = document.getElementById("sitename").value;
		var ContactName = document.getElementById("contactperson").value;	
		
		var ContactTel = document.getElementById("tel").value;
		var EmailAddress = document.getElementById("emailaddress").value;
		
		var Address1 = document.getElementById("address1").value;
		var Address2 = document.getElementById("address2").value;
		var City = document.getElementById("city").value;
		var State = document.getElementById("state").value;
		var PostCode = document.getElementById("postcode").value;
		var Country = document.getElementById("country").value;
		
		
		var Error = 0;
		var ErrorText = '';
		
		if (EmailAddress != "")
		{
			var IsValidEmail = ValidateEmail(EmailAddress);
			if (IsValidEmail === false)
			{
				Error = 1;
				ErrorText += "The email address entered is not valid, please enter a valid email address<br>";
					
			}
			else
			{
				
			}
		}
		else
		{
			ErrorText += "Please fill in an email address<br>";
			Error = 1;
		}
		
		
		
		if (SiteName == "" || ContactName == "" || ContactTel == "" || Address1 == "" || City == "" || State == "" || PostCode == "" || Country == "")
		{
			ErrorText += "Please fill in all fields marked with a *<br>";
			Error = 1;
		}
		else
		{
			
		}
		
		if (Error != 1)
		{

				var DoUpdateClient = agent.call('','AddSiteDetails','', SiteName, ContactName, ContactTel, EmailAddress, Address1, Address2, City, State, PostCode, Country, '<?php echo $CustomerID ?>');
				if (DoUpdateClient == "OK")
				{
					//LOG EVENT
					var Log = agent.call('','CreateClientAccess', '', <?php echo $CustomerID ?>, 'Added Client Site - ' + SiteName);
					
					document.location = 'cientsites.php?c=<?php echo $CustomerID ?>';
					
				}
				else
				{
					bootbox.alert(DoUpdateClient);	
				}
			
		}
		else
		{
			bootbox.alert(ErrorText);	
		}	
	}
	
	</script>
<script type="text/javascript">
	document.getElementById("customermenu").className = 'active';
	document.getElementById("customermenucustomer").className = 'active';
</script>
</body>

</html>
