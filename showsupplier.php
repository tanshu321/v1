<?php
include("includes/webfunctions.php");
include('includes/agent.php');
$agent->init();


//SECURITY
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
	
	$Countries = GetCountries();
	
	//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
	if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
	else
	{
		$Access = CheckPageAccess('Supplier Details');	
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

function UpdateSupplier()
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
		var CaptureSupplier = agent.call('','UpdateSupplier','', SupplierName, SupplierEmail, SupplierContactNumber, SupplierFax, SupplierContact, Address1, Address2, City, State, PostCode, Country, SupplierNotes, SupplierStatus, SupplierVat, '<?php echo $SupplierID ?>', VAT);
		if (CaptureSupplier == "OK")
		{
			bootbox.alert('Supplier details updated successfully', function() {
							document.location.reload();
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

        <?php include("navigation.php") ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                
                    <h1 class="page-header">Supplier Details - <?php echo $SupplierName ?> <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
            
                <div class="col-lg-12">
                <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to update your supplier information.
                            </div>  
                 <!-- Nav tabs -->
                            <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                
                                <li class="active"><a href="showsupplier.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Supplier Details</a>
                                </li>
                                <li><a href="supplierproducts.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Products</a>
                                </li>
                                <li><a href="supplierinvoices.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Invoices</a>
                                </li>
                                <li><a href="supplierpo.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Purchase Orders</a>
                                </li>
                                 <li class="pull-right"><a href="suppliersetup.php"><i class="fa fa-chevron-left"></i> Back to All Suppliers</a>
                                </li>
                               
                               
                            </ul>
                   
							<?php if ($Access == 1) { ?>    
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="home" style="padding: 10px; ">
                                    <!-- Start Inside Tab -->
                                    <!-- First Panel Table -->
                                    <div class="col-lg-12">
                                        
                                                <div class="form-group col-md-6">
                                                  <label for="firstname" class="col-sm-5 col-form-label" style="padding-top: 5px">Supplier Name *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="suppliername" placeholder="Supplier Name" value="<?php echo $SupplierName ?>">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="surname" class="col-sm-5 col-form-label" style="padding-top: 5px">Supplier Email Address *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="supplieremial" placeholder="Supplier Email" value="<?php echo $SupplierEmail ?>">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">Supplier Contact Number *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="suppliercontactnumber" placeholder="Contact Number" value="<?php echo $SupplierTel ?>">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="tel" class="col-sm-5 col-form-label" style="padding-top: 5px">Supplier Fax Number</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="supplierfax" placeholder="Contact Fax" value="<?php echo $SupplierFax ?>">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="emailaddress" class="col-sm-5 col-form-label" style="padding-top: 5px">Contact Name *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="contactname" placeholder="Contact Name" value="<?php echo $SupplierContact ?>">
                                                  </div>
                                                </div>
                                                
                                               
                                                 <div class="form-group col-md-6">
                                                  <label for="vatnumber" class="col-sm-5 col-form-label" style="padding-top: 5px">Supplier VAT Number</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="vatnumber" placeholder="VAT Number" value="<?php echo $SupplierVat ?>">
                                                  </div>
                                                </div>
                                                
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="address1" class="col-sm-5 col-form-label" style="padding-top: 5px">Address 1 *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="address1" placeholder="Address 1" value="<?php echo $SupplierAddress1 ?>">
                                                  </div>
                                                </div>
                                                
                                                 <div class="form-group col-md-6">
                                                  <label for="address2" class="col-sm-5 col-form-label" style="padding-top: 5px">Address 2</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="address2" placeholder="Address 2" value="<?php echo $SupplierAddress2 ?>">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="city" class="col-sm-5 col-form-label" style="padding-top: 5px">City *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="city" placeholder="City" value="<?php echo $City ?>">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="state" class="col-sm-5 col-form-label" style="padding-top: 5px">State/Region *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="state" placeholder="State/Region" value="<?php echo $State ?>">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="postcode" class="col-sm-5 col-form-label" style="padding-top: 5px">Post Code *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="postcode" placeholder="Post Code" value="<?php echo $PostCode ?>">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px">Country *</label>
                                                  <div class="col-sm-6">
                                                    <select id="country" class="form-control">
                                                        <?php while ($Val = mysqli_fetch_array($Countries))
                                                        {
                                                            $CountryID = $Val["CountryID"];
                                                            $CountryName = $Val["CountryName"];
															
															
                                                            
                                                            if ($CountryID == 192 && $ThisCountryID == "")
                                                            {
                                                                $Selected = 'selected';	
                                                            }
                                                            else
                                                            {
                                                                if ($ThisCountryID == $CountryID)
																{
																	$Selected = 'selected';	
																}
																else
																{
																	$Selected = '';	
																}
                                                            }
                                                        ?>
                                                            <option value="<?php echo $CountryID ?>" <?php echo $Selected ?>><?php echo $CountryName ?></option>
                                                        <?php } ?>
                                                    </select>
                                                  </div>
                                                </div>
                                                
                                                
                                                
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px">VAT Registered *</label>
                                                  <div class="col-sm-6">
                                                    <select id="vat" class="form-control">
                                                        
                                                        	<?php if ($ChargesVAT == 1) { ?>
                                                            <option value="1" selected>Yes</option>
                                                            <option value="0">No</option>
                                                            <?php } else { ?>
                                                            <option value="1">Yes</option>
                                                            <option value="0" selected>No</option>
                                                            <?php } ?>
                                                        
                                                    </select>
                                                  </div>
                                                  </div>
                                                  
                                                  <div class="form-group col-md-6">
                                                  <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px">Supplier Status *</label>
                                                  <div class="col-sm-6">
                                                    <select id="status" class="form-control">
                                                        
                                                        	<?php if ($SupplierStatus == 1) { ?>
                                                            <option value="1" selected>Active</option>
                                                            <option value="0">Disabled</option>
                                                            <?php } else { ?>
                                                            <option value="1">Active</option>
                                                            <option value="0" selected>Disabled</option>
                                                            <?php } ?>
                                                        
                                                    </select>
                                                  </div>
                                                  </div>
                                                  
                                                  <div class="form-group col-md-6">
                                                  <label for="postcode" class="col-sm-5 col-form-label" style="padding-top: 5px">Supplier Notes</label>
                                                  <div class="col-sm-6">
                                                    <textarea class="form-control" id="suppliernotes"><?php echo $SupplierNote ?></textarea>
                                                    
                                                  </div>
                                                </div>
                                                  
                                                  
                                                </div>
                                                    <div class="box-footer" align="right">
                                        
                                                    <button type="button" class="btn btn-primary" onClick="javascript: UpdateSupplier();">Update Details</button>
                                                 </div>
                                                <!-- END FORM CONTROLS -->
                                            </div>
                                            
                                        
                                    
                                    
                                   
                                   
                                    <!-- End inside tab -->
                                </div>
                                
                            </div>
                      
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
    
    <script src="js/bootbox.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
    </script>
    
    <script type="text/javascript">
	//MENU STUFF FOR PAGE
	document.getElementById("suppliermenu").className = 'active';
	document.getElementById("setupsuppliermenu").className = 'active';
	</script>

</body>

</html>
