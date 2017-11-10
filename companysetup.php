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
	
	
	$Upload = $_REQUEST["u"];
	if ($Upload == "y")
	{
		//OK FORM SUBMITTED, LETS CHECK IF THERES A LOGO TO UPLOAD
		$FileType = $_REQUEST["type"];
		$SafeFile = $_FILES['invoicelogo']['name']; 
				
		if(is_uploaded_file(($_FILES['invoicelogo']['tmp_name'])))
		{
				$imagename = $_FILES['invoicelogo']['name'];
				if ($imagename != "")
				{
					$source = $_FILES['invoicelogo']['tmp_name'];
					$NewFileName = time() . "_" . str_replace(" ","",$imagename);
					$target = "images/" . $NewFileName;  
					move_uploaded_file($source, $target);
					
					$AddClientInvoiceLogo = AddClientInvoiceLogo($NewFileName);
					
							
					echo "<script type='text/javascript'>document.location = 'companysetup.php';</script>"; 		
							
					
				}
		 }
	}
	
	
	$CurrentSetup = GetCompanySettings();
	
	while ($Val = mysqli_fetch_array($CurrentSetup))
	{
		$CompanyLogo = $Val["CompanyLogo"];
		$VATRegistered = $Val["VATRegistered"];
		$VATNumber = $Val["VATNumber"];
		$Address1 = $Val["Address1"];
		$Address2 = $Val["Address2"];
		$City = $Val["City"];
		$Region = $Val["Region"];
		$PostCode = $Val["PostCode"];
		$CountryID = $Val["CountryID"];
		$BankName = $Val["BankName"];
		$AccountHolder = $Val["AccountHolder"];
		$AccountNumber = $Val["AccountNumber"];
		$BranchCode = $Val["BranchCode"];
		$AccountType = $Val["AccountType"];
		$VATRate = $Val["VATRate"];
		$InvoiceLogo = $Val["InvoiceLogo"];
		$InvoiceDisplayCompany = $Val["InvoiceDisplayCompany"];
		$InvoiceDisplayEmail = $Val["InvoiceDisplayEmail"];
		$InvoiceDisplayTel = $Val["InvoiceDisplayTel"];
		$InvoiceDisplayFax = $Val["InvoiceDisplayFax"];
		$RecurringInvoiceDay = $Val["RecurringInvoiceDay"];
		$CompanyRegistration = $Val["CompanyRegistration"];
		$TermsAndConditions = $Val['TermsAndConditions'];
		$accountemail = $Val['accountemail'];
	}
	
	$Countries = GetCountries();
	
	//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
	if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
	else
	{
		$Access = CheckPageAccess('Company Setup');	
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
function UpdateSettings()
{
	var CompanyName = document.getElementById("companyname").value;
	var CompanyReg = document.getElementById("companyregistration").value;
	var VatRegistered = document.getElementById("vatregistered").value;
	var VatNumber = document.getElementById("vatnumber").value;
	var VatRate = 14;
	var ContactNum = document.getElementById("tel").value;
	var EmailAddress = document.getElementById("emailaddress").value;
	var FaxNumber = document.getElementById("faxnumber").value;
	var InvoiceLogo = document.getElementById("invoicelogo").value;
	var RecurringDay = document.getElementById("recurringday").value;
    var TermsAndConditions = document.getElementById("tandcs").value;
	
	//ADDRESS
	var Address1 = document.getElementById("address1").value;
	var Address2 = document.getElementById("address2").value;
	var City = document.getElementById("city").value;
	var Region = document.getElementById("state").value;
	var PostCode = document.getElementById("postcode").value;
	var Country = document.getElementById("country").value;
	
	//BANKING
	var Bank = document.getElementById("bank").value;
	var AccountHolder = document.getElementById("accountholder").value;
	var AccountNumber = document.getElementById("accountnumber").value;
	var BranchCode = document.getElementById("branchcode").value;
	var AccountType = document.getElementById("accounttype").value;
	var accountemail = document.getElementById("accountemail").value;

	//NOW CHECK EVERYTHING
	var Error = 0;
	var ErrorMessage = "";
	var CurrentLogo = '<?php echo $InvoiceLogo ?>';
	if (CurrentLogo == "" && InvoiceLogo == "")
	{
		ErrorMessage += "You must upload a logo for your documents<br>";
		Error = 1;
	}
	else
	{
		if (InvoiceLogo != "")
		{
			var FileType = InvoiceLogo.split('.').pop();	
			if (FileType == 'jpg' || FileType == 'JPG')
			{
				
			}
			else
			{
				ErrorMessage += "The logo needs to be a jpg file<br>";
				Error = 1;
			}
		}
	}
	
	//CHECK VAT VALUES
	if (VatRegistered == 1)
	{
		if (parseFloat(VatRate) > 0 && VatNumber != "" && $.isNumeric( VatRate) == true)
		{
			
		}
		else
		{
			ErrorMessage += "Please make sure your VAT Number has been enetered<br>";
			Error = 1;
		}
	}
	
	//NOW CHECK ALL OTHER FIELDS
	if (Error == 0)
	{
		if (CompanyName != "" && ContactNum != "" && EmailAddress != "" && Address1 != "" && City != "" && Region != "" && PostCode != "" && Country != "" && Bank != "" && AccountHolder != "" && AccountNumber != "" && BranchCode != "" && AccountType != "" && RecurringDay != "" && accountemail!='')
		{
			var UpdateCompanySettings = agent.call('','UpdateCompanySettings','', CompanyName, CompanyReg, VatRegistered, VatNumber, VatRate, ContactNum, EmailAddress, FaxNumber, RecurringDay, Address1, Address2, City, Region, PostCode, Country, Bank, AccountHolder, AccountNumber, BranchCode, AccountType, TermsAndConditions, accountemail);
			if (UpdateCompanySettings == "OK")
			{
				if (InvoiceLogo != "")
				{
					document.getElementById("invoicelogoform").action = "companysetup.php?u=y&type=" + FileType;
					document.getElementById("invoicelogoform").submit();	
				}
				else
				{
					bootbox.alert("Your company settings have been updated successfully");	
				}
			}
			else
			{
				bootbox.alert(	UpdateCompanySettings);
			}
		}
		else
		{
			bootbox.alert("Please fill in all fields marked with a *");	
		}
	}
	else
	{
		bootbox.alert(ErrorMessage);	
	}
	
	
	
}

function CheckVAT()
{
	var IsRegistered = document.getElementById("vatregistered").value;
	
	if (IsRegistered == 0)
	{
		document.getElementById("vatnumber").value = '';
		document.getElementById("vatnumber").disabled = true;	
		
		
	}
	else
	{
		document.getElementById("vatnumber").disabled = false;	
		
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
                    <h1 class="page-header">Company Setup<img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
            
                <div class="col-lg-12">
                  <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to setup your company information which appear on your invoices/jobcards and quotes.</div>     
					
                   
                        
                        
                               <?php if ($Access == 1) { ?>            
                             <div class="col-lg-12">   
                             <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                <li class="active"><a href="jobcardsetup.php"><i class="fa fa-caret-right"></i> Company Setup</a>
                                </li>
                                 <li ><a href="periodsetup.php"><i class="fa fa-caret-right"></i> Period Setup</a>
                                 </li>




                             </ul>
                             <h4>Company Settings</h4>
                             <div class="form-group row col-md-6">
                              <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">Company Name *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="companyname" placeholder="Company Name" value="<?php echo $InvoiceDisplayCompany ?>">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">Company Registration Number</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="companyregistration" placeholder="Company Registration" value="<?php echo $CompanyRegistration ?>">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="surname" class="col-sm-5 col-form-label" style="padding-top: 5px">VAT Registered *</label>
                              <div class="col-sm-6">
                                <select class="form-control" id="vatregistered" onChange="javascript: CheckVAT();">
                                	<?php if ($VATRegistered == 0) { ?>
                                    <option value="0" selected>No</option>
                                    <option value="1">Yes</option>
                                    <?php } else { ?>
                                    <option value="0">No</option>
                                    <option value="1" selected>Yes</option>
                                    <?php } ?>
                                </select>
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">VAT Number</label>
                              <div class="col-sm-6">
                              	<?php if ($VATRegistered == 0) { ?>
                                <input type="text" class="form-control" id="vatnumber" placeholder="VAT Number" disabled>
                                <?php } else { ?>
                                <input type="text" class="form-control" id="vatnumber" placeholder="VAT Number" value="<?php echo $VATNumber ?>">
                                <?php } ?>
                              </div>
                            </div>
                            
                            
                            
                           
                            
                            <div class="form-group row col-md-6">
                              <label for="tel" class="col-sm-5 col-form-label" style="padding-top: 5px">Contact Number *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="tel" placeholder="Contact Number" value="<?php echo $InvoiceDisplayTel ?>">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="emailaddress" class="col-sm-5 col-form-label" style="padding-top: 5px">Email Address *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="emailaddress" placeholder="Email Address" value="<?php echo $InvoiceDisplayEmail ?>">
                              </div>
                            </div>
                            
                           
                             <div class="form-group row col-md-6">
                              <label for="vatnumber" class="col-sm-5 col-form-label" style="padding-top: 5px">Fax Number</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="faxnumber" placeholder="Fax Number" value="<?php echo $InvoiceDisplayFax ?>">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="vatnumber" class="col-sm-5 col-form-label" style="padding-top: 5px">Current Invoice Logo</label>
                              <div class="col-sm-6">
                              	<?php if ($InvoiceLogo != "")
								{ ?>
                                <img src="images/<?php echo $InvoiceLogo ?>" class="img-responsive">
                                <?php } else { ?>
                                None
                                <?php } ?>
                              </div>
                            </div>
                            
                            <form name="invoicelogoform" id="invoicelogoform" method="post" enctype="multipart/form-data" action="">
                            <?php if ($InvoiceLogo != "")
							{ ?>
                            <div class="form-group row col-md-6">
                              <label for="vatnumber" class="col-sm-5 col-form-label" style="padding-top: 5px">Replace Invoice Logo (jpg 200 by 100px)</label>
                              <div class="col-sm-6">
                                <input type="file" class="form-control" id="invoicelogo"  name="invoicelogo">
                              </div>
                            </div>
                            <?php }  else { ?>
                            <div class="form-group row col-md-6">
                              <label for="vatnumber" class="col-sm-5 col-form-label" style="padding-top: 5px">Invoice Logo (jpg 200 by 100px) *</label>
                              <div class="col-sm-6">
                                <input type="file" class="form-control" id="invoicelogo" name="invoicelogo">
                              </div>
                            </div>
                            <?php } ?>
                            </form>
                            
                             <div class="form-group row col-md-6">
                              <label for="address1" class="col-sm-5 col-form-label" style="padding-top: 5px">Recurring Invoice Creation Day *</label>
                              <div class="col-sm-6">
                                <select class="form-control" id="recurringday">
                                	<option value="<?php echo $RecurringInvoiceDay ?>"><?php echo $RecurringInvoiceDay ?></option>
                                    <?php for ($X = 1; $X <= 28; $X++)
									{ ?>
                                    <option value="<?php echo $X ?>"><?php echo $X ?></option>
                                    <?php } ?>
                                    
                                </select>
                              </div>
                            </div>

                             <div class="form-group row col-md-6">
                                 <label for="tandcs" class="col-sm-5 col-form-label" style="padding-top: 5px">Quote terms & conditions</label>
                                 <div class="col-sm-6">
                                     <textarea name="tandcs" id="tandcs" class="form-control" placeholder="Terms and conditions for quote"><?= $TermsAndConditions ?></textarea>
                                 </div>
                             </div>

                                 <div class="form-group row col-md-6">
                                     <label for="emailaddress" class="col-sm-5 col-form-label" style="padding-top: 5px">Accounts Email *</label>
                                     <div class="col-sm-6">
                                         <input type="text" class="form-control" id="accountemail" placeholder="Accounts Email" value="<?php echo $accountemail; ?>">
                                     </div>
                                 </div>

                            
                            <div class="clearfix"></div>
                            <h4 class="">Company Address</h4>
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
										$ThisCountryID = $Val["CountryID"];
										$CountryName = $Val["CountryName"];
										
										if ($ThisCountryID == $CountryID)
										{
											$Selected = 'selected';	
										}
										else
										{
											$Selected = '';	
										}
									?>
                                    	<option value="<?php echo $ThisCountryID ?>" <?php echo $Selected ?>><?php echo $CountryName ?></option>
                                    <?php } ?>
                                </select>
                              </div>   
                            
                            </div>
                            
                            <div class="clearfix"></div>
                            <h4 class="">Company Banking</h4>
                            <div class="form-group row col-md-6">
                              <label for="address1" class="col-sm-5 col-form-label" style="padding-top: 5px">Bank *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="bank" placeholder="Bank Name" value="<?php echo $BankName ?>">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="address1" class="col-sm-5 col-form-label" style="padding-top: 5px">Account Holder *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="accountholder" placeholder="Account Holder" value="<?php echo $AccountHolder ?>">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="address1" class="col-sm-5 col-form-label" style="padding-top: 5px">Account Number *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="accountnumber" placeholder="Account Number" value="<?php echo $AccountNumber ?>">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="address1" class="col-sm-5 col-form-label" style="padding-top: 5px">Branch Code *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="branchcode" placeholder="Branch Code" value="<?php echo $BranchCode ?>">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="address1" class="col-sm-5 col-form-label" style="padding-top: 5px">Account Type *</label>
                              <div class="col-sm-6">
                                <select class="form-control" id="accounttype">
                                	<option value="<?php echo $AccountType ?>"><?php echo $AccountType ?></option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Savings">Savings</option>
                                    <option value="Transmission">Transmission</option>
                                </select>
                              </div>
                            </div>

                            <!-- /.table-responsive -->
                            
                            <div class="col-lg-12" align="center" style="padding-top: 20px; padding-bottom: 40px">
                                                <button class="btn btn-info" onClick="javascript: UpdateSettings();">Update Changes</button>
                                                <button class="btn btn-default" onClick="javascript: document.location.reload();">Cancel Changes</button>
                                    
                                  
                                    
                                    <!-- First Panel Table --><!-- End first panel -->
                                   
                                   
                                    <!-- End inside tab -->
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

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>
    <script src="js/bootbox.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true,
			"order": [[ 0, "asc" ]]
        });
    });
    </script>

</body>

</html>
