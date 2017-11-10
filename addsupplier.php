<?php
session_start();
if ($_SESSION["Remember"] == "true")
{
	$ThisUserName = $_SESSION["AdminEmail"];
	$year = time() + 31536000;
	setcookie('remember_me_crm_admin', $ThisUserName, $year);	
}
include("includes/webfunctions.php");
include('includes/agent.php');
$agent->init();

//SECURITY
$ThisClientID = $_SESSION["ClientID"];
$ThisUser = $_SESSION["ClientName"];
$ThisSecret = $_SESSION["SiteSecret"];

if ($ThisClientID > 0 && $ThisUser != "" && $ThisSecret == "E2A_crm_S5gdbh6nnj_usr_9898")
{
	$Countries = GetCountries();
	
	//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
	if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
	else
	{
		$Access = CheckPageAccess('Add Suppliers');	
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

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
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

function AddSupplier()
{
	var SupplierName = document.getElementById("suppliername").value;
	var SupplierEmail = document.getElementById("supplieremial").value;	
	var SupplierContactNumber = document.getElementById("suppliercontactnumber").value;
	var SupplierFax = document.getElementById("supplierfax").value;
	var SupplierContact = document.getElementById("contactname").value;
	var SupplierVat = document.getElementById("vatnumber").value;
	
	var Address1 = document.getElementById("address1").value;
	var Address2 = document.getElementById("address2").value;
	var City = document.getElementById("city").value;
	var State = document.getElementById("state").value;
	var PostCode = document.getElementById("postcode").value;
	var Country = document.getElementById("country").value;
	
	var SupplierNotes = document.getElementById("suppliernotes").value;
	var SupplierStatus = document.getElementById("status").value;
	var VAT = document.getElementById("vat").value;
	
	var Error = 0;
	var ErrorText = '';
	
	if (SupplierEmail != "")
	{
		var IsValidEmail = ValidateEmail(SupplierEmail);
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
	
	
	
	if (SupplierName == "" || SupplierEmail == "" || SupplierContactNumber == "" || Address1 == "" || City == "" || State == "" || PostCode == "" || Country == "" || SupplierContact == "" || VAT == "")
	{
		ErrorText += "Please fill in all fields marked with a *<br>";
		Error = 1;
	}
	else
	{
		
	}
	
	if (Error != 1)
	{
		var CaptureSupplier = agent.call('','AddSupplier','', SupplierName, SupplierEmail, SupplierContactNumber, SupplierFax, SupplierContact, Address1, Address2, City, State, PostCode, Country, SupplierNotes, SupplierStatus, SupplierVat, VAT);
		if (CaptureSupplier == "OK")
		{
			bootbox.alert('Supplier details added successfully', function() {
							document.location = 'suppliersetup.php';
			});
		}
		else
		{
			bootbox.alert(CaptureSupplier);	
		}
	}
	else
	{
		bootbox.alert(ErrorText);	
	}
	
}
</script>
</head>

<body>

    <div id="wrapper">

        <?php include("navigation.php"); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Add New Supplier  <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                   
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to add a new supplier to your database. Please fill in all fields marked with a * to add your supplier information.
                            </div>     
            <div class="row">
                <div class="col-lg-12">
                   <ul class="nav nav-tabs">
                                
                               
                                 <li class="pull-right"><a href="suppliersetup.php"><i class="fa fa-chevron-left"></i> Back to All Suppliers</a>
                                </li>
                               
                               
                            </ul>
                    <?php if ($Access == 1) { ?>    
                        <!-- /.panel-heading -->
                        <h4>Add Supplier Information</h4>
                        <div class="panel-body">
                            <div class="form-group row col-md-6">
                              <label for="firstname" class="col-sm-5 col-form-label" style="padding-top: 5px">Supplier Name *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="suppliername" placeholder="Supplier Name">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="surname" class="col-sm-5 col-form-label" style="padding-top: 5px">Supplier Email Address *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="supplieremial" placeholder="Supplier Email">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">Supplier Contact Number *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="suppliercontactnumber" placeholder="Contact Number">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="tel" class="col-sm-5 col-form-label" style="padding-top: 5px">Supplier Fax Number</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="supplierfax" placeholder="Contact Fax">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="emailaddress" class="col-sm-5 col-form-label" style="padding-top: 5px">Contact Name *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="contactname" placeholder="Contact Name">
                              </div>
                            </div>
                            
                           
                             <div class="form-group row col-md-6">
                              <label for="vatnumber" class="col-sm-5 col-form-label" style="padding-top: 5px">Supplier VAT Number</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="vatnumber" placeholder="VAT Number">
                              </div>
                            </div>
                            
                            
                            <div class="form-group row col-md-6">
                              <label for="address1" class="col-sm-5 col-form-label" style="padding-top: 5px">Address 1 *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="address1" placeholder="Address 1">
                              </div>
                            </div>
                            
                             <div class="form-group row col-md-6">
                              <label for="address2" class="col-sm-5 col-form-label" style="padding-top: 5px">Address 2</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="address2" placeholder="Address 2">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="city" class="col-sm-5 col-form-label" style="padding-top: 5px">City *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="city" placeholder="City">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="state" class="col-sm-5 col-form-label" style="padding-top: 5px">State/Region *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="state" placeholder="State/Region">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="postcode" class="col-sm-5 col-form-label" style="padding-top: 5px">Post Code *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="postcode" placeholder="Post Code">
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
										
										if ($CountryID == 192)
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
                              <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px">VAT Registered *</label>
                              <div class="col-sm-6">
                                <select id="vat" class="form-control">
                                	
									
                                    	<option value="1">Yes</option>
                                        <option value="0">No</option>
                                    
                                </select>
                              </div>
                              
                              
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px">Supplier Status *</label>
                              <div class="col-sm-6">
                                <select id="status" class="form-control">
                                	
									
                                    	<option value="1">Active</option>
                                        <option value="0">Disabled</option>
                                    
                                </select>
                              </div>
                              
                              
                            </div>
                            
                             <div class="form-group row col-md-6">
                              <label for="postcode" class="col-sm-5 col-form-label" style="padding-top: 5px">Supplier Notes *</label>
                              <div class="col-sm-6">
                              	<textarea class="form-control" id="suppliernotes"></textarea>
                                
                              </div>
                            </div>
                            	<div class="box-footer" align="right">
                  	
                    			<button type="button" class="btn btn-primary" onClick="javascript: AddSupplier();">Add Supplier</button>
                 			 </div>
                            <!-- END FORM CONTROLS -->
                        </div>
                        
                        <!-- /.panel-body -->
                    
                    
                    
                </div>
                <!-- /.col-lg-8 -->
                
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

   
    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>
    <script src="js/bootbox.js"></script>
    
    <script type="text/javascript">
	//MENU STUFF FOR PAGE
	document.getElementById("setupmenu").className = 'active';
	document.getElementById("setupsuppliermenu").className = 'active';
	</script>

</body>

</html>
