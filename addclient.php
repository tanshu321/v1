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
	$CustomCustomerFields = GetCustomerCustomFields();
	$NumCustomFields = mysqli_num_rows($CustomCustomerFields);
	
	//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
	if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
	else
	{
		$Access = CheckPageAccess('Add Customers');	
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

</head>


<body>

    <div id="wrapper">

        <?php include("navigation.php"); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Add New Client  <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                   
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to add a new customer. All fields marked with a * is required
                            </div>     
            <!-- /.row -->
            
           
            <div class="row">
                <div class="col-lg-12">
                 <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                <li><a href="showclients.php"><i class="fa fa-caret-right"></i> My Customers</a>
                                </li>
                                <li class="active"><a href="addclient.php"><i class="fa fa-caret-right"></i> Add Customer</a>
                                </li>
                                
                                
                               
                               
                            </ul>
                            
                    
                           
                          <?php if ($Access == 1) { ?>  
                         <h4>Customer Details</h4>
                        <!-- /.panel-heading -->
                        
                            <div class="form-group row col-md-6">
                              <label for="firstname" class="col-sm-5 col-form-label" style="padding-top: 5px">First Name</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="firstname" placeholder="First Name">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="surname" class="col-sm-5 col-form-label" style="padding-top: 5px">Surname</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="surname" placeholder="Surname">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">Company Name *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="companyname" placeholder="Company Name">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="tel" class="col-sm-5 col-form-label" style="padding-top: 5px">Contact Number *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="tel" placeholder="Contact Number">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="emailaddress" class="col-sm-5 col-form-label" style="padding-top: 5px">Email Address *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="emailaddress" placeholder="Email Address">
                              </div>
                            </div>
                            
                           
                             <div class="form-group row col-md-6">
                              <label for="vatnumber" class="col-sm-5 col-form-label" style="padding-top: 5px">VAT Number</label>
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
                            
                            
                            <!-- END FORM CONTROLS -->
                        
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    
                    
                </div>
                <!-- /.col-lg-8 -->
                
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-lg-12">
                     <h4 style="padding-bottom: 10px">Billing Details</h4>
                        <!-- /.panel-heading -->
                        
                            <div class="form-group row col-md-6">
                              <label for="firstname" class="col-sm-5 col-form-label">Tax Exempt</label>
                              <div class="col-sm-6">
                                <input type="checkbox" id="tax"> Don't Apply Tax to Invoices
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="firstname" class="col-sm-5 col-form-label">Overdue Notices</label>
                              <div class="col-sm-6">
                                <input type="checkbox" id="overdue"> Don't Send Overdue Emails
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="firstname" class="col-sm-5 col-form-label">Marketing Emails</label>
                              <div class="col-sm-6">
                                <input type="checkbox" id="marketing"> Don't Send Marketing Emails
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px">Payment Method</label>
                              <div class="col-sm-6">
                                <select id="paymentmethod" class="form-control">
                                	
                                    	<option value="Debit Order">Debit Order</option>
                                        <option value="EFT">EFT Payment</option>
                                        <option value="Credit Card">Credit Card Payment</option>
                                   
                                </select>
                              </div>
                            </div>
                            
                             <div class="form-group row col-md-6">
                              <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px">Status</label>
                              <div class="col-sm-6">
                                <select id="status" class="form-control">
                                	
                                    	<option value="2">Active</option>
                                        <option value="1">Disabled</option>
                                        
                                   
                                </select>
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                                  <label for="postcode" class="col-sm-5 col-form-label" style="padding-top: 5px">Customer Code/Dep Reference *</label>
                                  <div class="col-sm-6">
                                        <input type="text" class="form-control" id="deposit" placeholder="Customer Code/Dep Reference" value="">
                                  </div>
                            </div>
                            
                           
                            
                            <div class="form-group row col-md-6">
                              <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px">Admin Notes</label>
                              <div class="col-sm-6">
                                <textarea class="form-control" placeholder="Admin Notes" id="adminnotes"></textarea>
                              </div>
                            </div>
                            
                          
                    
                </div>
                <!-- /.col-lg-8 -->
                
            </div>
            <!-- /.row -->
            
            <?php if ($NumCustomFields > 0)
			{
			?>
            <!--STRART CUSTOM-->
            <div class="row">
                <div class="col-lg-12">
                     <h4 style="padding-bottom: 10px">Custom Fields</h4>
                        <!-- /.panel-heading -->
                        	<?php
                            $AllCustomIDs = "";
							while ($Val = mysqli_fetch_array($CustomCustomerFields))
							{ 
								$CustomFieldName = $Val["CustomFieldName"];
								$CustomFieldType = $Val["CustomFieldType"];
								$CustomFieldID = $Val["CustomFieldID"];
								$Required = $Val["Required"];
								
								$AllCustomIDs = $AllCustomIDs . ":::" . $CustomFieldID;
								
								if ($Required == 1)
								{
									$CustomFieldName = $CustomFieldName . " *";	
								}
							
							?>
                            <div class="form-group row col-md-6">
                              <label for="productgroup" class="col-sm-5 col-form-label" style="padding-top: 5px"><?php echo $CustomFieldName ?></label>
                              <div class="col-sm-6">
                               <?php if ($CustomFieldType == "text")
							   { ?>
                               <input type="text" class="form-control" id="custom<?php echo $CustomFieldID ?>" placeholder="<?php echo $CustomFieldName ?>" value="">
                               <?php 
							   } 
							   else if ($CustomFieldType == "textarea") 
							   { ?>
                               <textarea class="form-control" id="custom<?php echo $CustomFieldID ?>" placeholder="<?php echo $CustomFieldName ?>"></textarea>
                               <?php 
							   } 
							   else if ($CustomFieldType == "select") 
							   { 
							   		$CustomOptions = GetCustomFieldOptionsCustomer($CustomFieldID);
									
									
							   ?>
                               <select class="form-control" id="custom<?php echo $CustomFieldID ?>" >
                               		<option value="" >Please select</option>
                                	<?php
									while ($CustomOption = mysqli_fetch_array($CustomOptions))
									{
										$CustomFieldOptionID = $CustomOption["CustomFieldOptionID"];
										$OptionValue = $CustomOption["OptionValue"];
									?>
                                    <option value="<?php echo $CustomFieldOptionID ?>" ><?php echo $OptionValue ?></option>
                                    <?php } ?>
                                    
                                </select>
                               <?php 
							   } 
							   else if ($CustomFieldType == "radio") 
							   { 
							   		$CustomOptions = GetCustomFieldOptionsCustomer($CustomFieldID);
									while ($CustomOption = mysqli_fetch_array($CustomOptions))
									{
										$CustomFieldOptionID = $CustomOption["CustomFieldOptionID"];
										$OptionValue = $CustomOption["OptionValue"];
									?>
                                    <div class="radio" style="padding-left: 20px">
                                    <input type="radio" name="custom<?php echo $CustomFieldID ?>" id="customoption<?php echo $CustomFieldOptionID ?>"> <?php echo $OptionValue ?>
                                    </div>
                                    <?php } ?>
                                <?php 
							   } 
							   else if ($CustomFieldType == "checkbox") 
							   { 
							   		$CustomOptions = GetCustomFieldOptionsCustomer($CustomFieldID);
									while ($CustomOption = mysqli_fetch_array($CustomOptions))
									{
										$CustomFieldOptionID = $CustomOption["CustomFieldOptionID"];
										$OptionValue = $CustomOption["OptionValue"];
									?>
                                    <div class="checkbox">
                                    <label><input type="checkbox" id="customoption<?php echo $CustomFieldOptionID ?>"> <?php echo $OptionValue ?></label>
                                    </div>
                                    <?php } ?>
                               <?php } ?>
                              </div>
                            </div>
                            
                            <?php 
							} 
							?>
                            
                            
                            
                </div>
                <!-- /.col-lg-8 -->
                
            </div>
            <!-- /.row -->
           <!-- END CUSTOM -->
           <?php } ?>
            
            
            <!-- ADD BUTTON ON ITS OWN -->
            <div class="row">
                <div class="col-lg-12">
                     
                            
                             <div class="form-group row col-md-6">
                              &nbsp;
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px"></label>
                              <div class="col-sm-6">
                                <button class="btn btn-info pull-right" onClick="javascript: AddClient();">Add Client</button>
                              </div>
                            </div>
                            
                           
                            
                        
                    
                    
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
	document.getElementById("customermenu").className = 'active';
	document.getElementById("customermenucustomer").className = 'active';
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

function AddClient()
{
	var Name = document.getElementById("firstname").value;
	var Surname = document.getElementById("surname").value;	
	var CompanyName = document.getElementById("companyname").value;
	var ContactTel = document.getElementById("tel").value;
	var EmailAddress = document.getElementById("emailaddress").value;
	
	var Address1 = document.getElementById("address1").value;
	var Address2 = document.getElementById("address2").value;
	var City = document.getElementById("city").value;
	var State = document.getElementById("state").value;
	var PostCode = document.getElementById("postcode").value;
	var Country = document.getElementById("country").value;
	
	var TaxExempt = document.getElementById("tax").checked;
	var OverDueNotice = document.getElementById("overdue").checked;
	var Marketing = document.getElementById("marketing").checked;
	
	var PaymentMethod = document.getElementById("paymentmethod").value;
	var Status = document.getElementById("status").value;
	
	var VATNumber = document.getElementById("vatnumber").value;
	var AdminNotes = document.getElementById("adminnotes").value;
	var DepositReference = document.getElementById("deposit").value;
	
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
	
	
	
	if (CompanyName == "" || ContactTel == "" || Address1 == "" || City == "" || State == "" || PostCode == "" || Country == "" || DepositReference == "")
	{
		ErrorText += "Please fill in all fields marked with a *<br>";
		Error = 1;
	}
	else
	{
		
	}
	
	if (Error != 1)
	{
		//THEN CUSTOM FIELD VALIDATION
		//WE HAVE ENOUGH NOW TO ADD THE BASE INFO, BUT WE NEED TO CHECK REQUIRED CUSTOM FIELDS
		var CustomIDs = '<?php echo ltrim($AllCustomIDs, ":::"); ?>';
		var CustomError = "";
			
		if (CustomIDs != "")
		{
			var CustomIDArray = CustomIDs.split(":::");
			for (i = 0; i < CustomIDArray.length; i++) 
			{
				var ThisCustomID = CustomIDArray[i];
				var CustomType = agent.call('','GetCustomerCustomDetails','', ThisCustomID);
					
				var ThisType = CustomType[0];
				var ThisRequired = CustomType[1];
					
				if ((ThisType == "text" || ThisType == "textarea" || ThisType == "select") && ThisRequired == 1)
				{
					var HasData = document.getElementById("custom" + ThisCustomID).value;
					if (HasData == "")
					{
						CustomError = 1;	
					}
				}
					
				if ((ThisType == "checkbox" || ThisType == "radio") && ThisRequired == 1)
				{
					var Options = agent.call('','GetCustomFieldOptionsArrayCustomer','', ThisCustomID);
					var HasSelected = 0;
						
					for (y = 0; i < Options.length; i++) 
					{
						var ThisOptionID = Options[y];
						var IsChecked = document.getElementById("customoption" + ThisOptionID).checked;
						if (IsChecked === true)
						{
							HasSelected = 1;	
						}
					}
						
					if (HasSelected == 0)
					{
						CustomError = 1;	
					}
				}
			}
		}
		
		if (CustomError == 0)
		{
			var CaptureClient = agent.call('','AddClientDetails','', Name, Surname, CompanyName, ContactTel, EmailAddress, Address1, Address2, City, State, PostCode, Country, TaxExempt, OverDueNotice, Marketing, PaymentMethod, Status, VATNumber, AdminNotes, DepositReference);
			if (CaptureClient > 0)
			{
				//CUSTOM FIELDS
				var CustomIDArray = CustomIDs.split(":::");
				for (i = 0; i < CustomIDArray.length; i++) 
				{
					var ThisCustomID = CustomIDArray[i];
					var CustomType = agent.call('','GetCustomerCustomDetails','', ThisCustomID);
							
					var ThisType = CustomType[0];
							
							
					if ((ThisType == "text" || ThisType == "textarea" || ThisType == "select"))
					{
						var HasData = document.getElementById("custom" + ThisCustomID).value;
						var SaveCustomEntry = agent.call('','SaveCustomerCustomEntry','', ThisCustomID, 0, HasData, CaptureClient);
					}
							
					if ((ThisType == "checkbox" || ThisType == "radio"))
					{
						var TheseOptions = agent.call('','GetCustomFieldOptionsArrayCustomer','', ThisCustomID);
								
								
								
						for (z = 0; z < TheseOptions.length; z++) 
						{
									
							var ThisOptionID = TheseOptions[z];
							var IsChecked = document.getElementById("customoption" + ThisOptionID).checked;
							var SaveCustomEntry = agent.call('','SaveCustomerCustomEntry','', ThisCustomID, ThisOptionID, IsChecked, CaptureClient);
						}
								
					}
				}
				
				bootbox.alert('Client details added successfully', function() {
								document.location = 'clientinfo.php?c=' + CaptureClient;
				});
			}
			else
			{
				bootbox.alert(CaptureClient);	
			}
		}
		else
		{
			bootbox.alert("Please fill in values in all the custom fields marked with a *");	
		}
	}
	else
	{
		bootbox.alert(ErrorText);	
	}
	
}
</script>
</body>

</html>
