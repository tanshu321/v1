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
	$EmployeeID = $_REQUEST["e"];
	$EmployeeDetails = GetEmployee($EmployeeID);
	
	while ($Val = mysqli_fetch_array($EmployeeDetails))
	{
		$Name = $Val["Name"];	
		$Surname = $Val["Surname"];	
		$IDNumber = $Val["IDNumber"];	
		$EmployeeNumber = $Val["EmployeeNumber"];	
		$EmployeeStartDate = $Val["EmployeeStartDate"];	
		$EmployeeLeftDate = $Val["EmployeeLeftDate"];	
		$Address1 = $Val["Address1"];	
		$Address2 = $Val["Address2"];	
		$City = $Val["City"];	
		$Region = $Val["Region"];	
		$PostCode = $Val["PostCode"];	
		$CountryID = $Val["CountryID"];	
		$ContactHome = $Val["ContactHome"];	
		$ContactCell = $Val["ContactCell"];	
		$ContactEmail = $Val["ContactEmail"];	
		$UserName = $Val["UserName"];	
		$SystemAccess = $Val["SystemAccess"];	
		$EmployeeStatus = $Val["EmployeeStatus"];	
		$DepartmentID = $Val["DepartmentID"];	
		$TaxReference = $Val["TaxReference"];	
		$PermissionsGroup = $Val["PermissionsGroup"];	
		$InternalExtension = $Val["InternalExtension"];	
		$AdditionalContact = $Val["AdditionalContact"];	
		$AdditionalContactNumber = $Val["AdditionalContactNumber"];	
		$Password = $Val["Password"];
		
		if ($Password == "")
		{
			$HasPassword = 0;	
		}
		else
		{
			$HasPassword = 1;	
		}
	}
	
	$Countries = GetCountries();
	$Departments = GetAllDepartments();
	$SecurityGroups = GetAllSecurityGroups();
	
	$CurrentSecurityGroup = CheckEmployeeSecurity($EmployeeID);
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
function AddGroup()
{
	var GroupName = document.getElementById("groupname").value;
	if (GroupName != "")
	{
		var AddProductGroup = agent.call('','AddProductGroup','', GroupName);
		
		if (AddProductGroup == "OK")
		{
			document.location.reload();
		}
		else
		{
			bootobox.alert(AddProductGroup);
		}
		
	}
	else
	{
		bootbox.alert("Please enter the name for the new product group");	
	}
}

function CheckAccess()
{
	var HasAccess = document.getElementById("systemaccess").value;
	if (HasAccess == 1)
	{
		document.getElementById("username").disabled = false;
		document.getElementById("password").disabled = false;
		document.getElementById("securitygroup").disabled = false;
		
		document.getElementById("username").value = '<?php echo $UserName ?>';
	}
	else
	{
		document.getElementById("username").disabled = true;
		document.getElementById("password").disabled = true;
		document.getElementById("securitygroup").disabled = true;
		
		document.getElementById("username").value = '';
		document.getElementById("password").value = '';
		document.getElementById("securitygroup").selectedIndex = '';
	}
}

function UpdateEmployee()
{
	var Name = document.getElementById("name").value;
	var Surname = document.getElementById("surname").value;
	var IDNumber = document.getElementById("idnumber").value;
	var EmployeeNumber = document.getElementById("employeenumber").value;
	var TaxReference = document.getElementById("taxnumber").value;
	var Department = document.getElementById("department").value;
	var Extension = document.getElementById("extensionnumber").value;
	var ContactHome = document.getElementById("contacthome").value;
	var ContactCell = document.getElementById("cell").value;
	var ContactEmail = document.getElementById("email").value;
	var AlternativeContact = document.getElementById("additionalcontact").value;
	var AlternativeTel = document.getElementById("additionaltel").value;
	
	var Address1 = document.getElementById("address1").value;
	var Address2 = document.getElementById("address2").value;
	var City = document.getElementById("city").value;
	var State = document.getElementById("state").value;
	var PostCode = document.getElementById("postcode").value;
	var Country = document.getElementById("country").value;
	
	var SystemAccess = document.getElementById("systemaccess").value;
	var EmployeeStatus = document.getElementById("status").value;
	var UserName = document.getElementById("username").value;
	var Password = document.getElementById("password").value;
	
	var SecurityGroup = document.getElementById("securitygroup").value;
	
	var HasPassword = '<?php echo $HasPassword ?>';
	
	//VALIDATION
	var Error = 0;
	if (SystemAccess == 1)
	{
		var CheckSpots = agent.call('','MaxSystemUsersEdit','', '<?php echo $EmployeeID ?>');	
		if (CheckSpots == 0 || CheckSpots < 0)
		{	
			bootbox.alert("Your current package does not allow for more system access, please remove a different user access to grant access to this one");
			Error = 1;
		}
	}
	
	if (Error == 0)
	{
		if (SystemAccess == 1)
		{
			if (UserName == "")
			{
				bootbox.alert("Please enter a username for this employee");
				Error = 1;
			}
			else
			{
				if (Password == "" && parseInt(HasPassword) == 0)
				{
					bootbox.alert("Please enter a password for this employee");
					Error = 1;	
				}
				else
				{
					if (Password != "")
					{
						var CheckPassword = agent.call('', 'CheckPassword','', Password);	
						if (CheckPassword == "OK")
						{
							
							
						}
						else
						{
							bootbox.alert(CheckPassword);
							Error = 1;	
						}
					}
				}
			}
		}
	}
	
	//CHECK SECURITY
	if (SystemAccess == 1 && Error == 0)
	{
		if (SecurityGroup == "")
		{
			bootbox.alert("Please select the security group for this user");
			Error = 1;	
		}
	}
	
	if (Error == 0)
	{
		//OK PASSWORD CHECK COMPLTE, NOW DEFAULT FIELDS
		if (Name != "" && Surname != "" && Department != "" && Address1 != "" && City != "" && State != "" && PostCode != "" && Country != "" && SystemAccess != "" && EmployeeStatus != "")
		{
			var UpdateEmployee = agent.call('', 'UpdateEmployeeDetails','', Name, Surname, IDNumber, EmployeeNumber, TaxReference, Department, Extension, ContactHome, ContactCell, ContactEmail, AlternativeContact, AlternativeTel, Address1, Address2, City, State, PostCode, Country,  SystemAccess, EmployeeStatus, UserName, Password, '<?php echo $EmployeeID ?>', SecurityGroup);
			if (UpdateEmployee == "OK")
			{
				bootbox.alert("The employee has been updated successfully");
			}
			else
			{
				bootbox.alert(UpdateEmployee);
			}
		}
		else
		{
			bootbox.alert("Please fill in all fields marked with a *");	
		}
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
                    <h1 class="page-header">Employee Management <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
            
                <div class="col-lg-12">
                  <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> Below you will find all the information about the employee</div>     
					
                   
                        
                        
                                    
                             <div class="col-lg-12">   
                             <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                <li class="active"><a href="employees.php"><i class="fa fa-caret-right"></i> Employees</a>
                                </li>
                                <li><a href="employeedepartments.php"><i class="fa fa-caret-right"></i> Departments</a>
                                
                                </li>
                                <li ><a href="employeesecurity.php"><i class="fa fa-caret-right"></i> Security Groups</a>
                                
                                </li>
                                
                               
                               
                            </ul>
                             <h4>Employee Details - <?php echo $Name ?> <?php echo $Surname ?></h4>   
                            <div class="col-lg-12">
                                        
                                                <div class="form-group col-md-6">
                                                  <label for="firstname" class="col-sm-5 col-form-label" style="padding-top: 5px">Name *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="name" placeholder="Name" value="<?php echo $Name ?>">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="surname" class="col-sm-5 col-form-label" style="padding-top: 5px">Surname *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="surname" placeholder="Surname" value="<?php echo $Surname ?>">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">ID Number</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="idnumber" placeholder="ID Number" value="<?php echo $IDNumber ?>">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="tel" class="col-sm-5 col-form-label" style="padding-top: 5px">Employee Number</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="employeenumber" placeholder="Employee Number" value="<?php echo $EmployeeNumber ?>">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="emailaddress" class="col-sm-5 col-form-label" style="padding-top: 5px">Tax Reference</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="taxnumber" placeholder="Tax Reference" value="<?php echo $TaxReference ?>">
                                                  </div>
                                                </div>
                                                
                                               
                                                 <div class="form-group col-md-6">
                                                  <label for="vatnumber" class="col-sm-5 col-form-label" style="padding-top: 5px">Department *</label>
                                                  <div class="col-sm-6">
                                                    <select class="form-control" id='department'>
                                                    	<option value="">Please select</option>
                                                        <?php while ($Val = mysqli_fetch_array($Departments))
														{
															$ThisDepartmentID = $Val["DepartmentID"];
															$ThisDepartmentName = $Val["DepartmentName"];	
															
															if ($ThisDepartmentID == $DepartmentID)
															{
																$Selected = 'selected';
															}
															else
															{
																$Selected = '';	
															}
														?>
                                                        <option value="<?php echo $ThisDepartmentID ?>" <?php echo $Selected ?>><?php echo $ThisDepartmentName ?></option>
                                                        <?php
														}
														
														?>
                                                    </select>
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="postcode" class="col-sm-5 col-form-label" style="padding-top: 5px">Extension Number</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="extensionnumber" placeholder="Extension Number" value="<?php echo $InternalExtension ?>">
                                                    
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="postcode" class="col-sm-5 col-form-label" style="padding-top: 5px">Contact Home</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="contacthome" placeholder="Contact Home" value="<?php echo $ContactHome ?>">
                                                    
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="postcode" class="col-sm-5 col-form-label" style="padding-top: 5px">Contact Cell</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="cell" placeholder="Contact Cell" value="<?php echo $ContactCell ?>">
                                                    
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="postcode" class="col-sm-5 col-form-label" style="padding-top: 5px">Contact Email</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="email" placeholder="Contact Email" value="<?php echo $ContactEmail ?>">
                                                    
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="postcode" class="col-sm-5 col-form-label" style="padding-top: 5px">Alternative Contact Name</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="additionalcontact" placeholder="Alternative Contact Name" value="<?php echo $AdditionalContact ?>">
                                                    
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="postcode" class="col-sm-5 col-form-label" style="padding-top: 5px">Alternative Contact Number</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="additionaltel" placeholder="Alternative Contact Number" value="<?php echo $AdditionalContactNumber ?>">
                                                    
                                                  </div>
                                                </div>
                                                
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="address1" class="col-sm-5 col-form-label" style="padding-top: 5px">Address 1 *</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="address1" placeholder="Address 1" value="<?php echo $Address1 ?>">
                                                  </div>
                                                </div>
                                                
                                                 <div class="form-group col-md-6">
                                                  <label for="address2" class="col-sm-5 col-form-label" style="padding-top: 5px">Address 2</label>
                                                  <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="address2" placeholder="Address 2" value="<?php echo $Address2 ?>">
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
                                                    <input type="text" class="form-control" id="state" placeholder="State/Region" value="<?php echo $Region ?>">
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
                                                  <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px">System Access *</label>
                                                  <div class="col-sm-6">
                                                    <select id="systemaccess" class="form-control" onChange="javascript: CheckAccess();">
                                                        
                                                        	<?php if ($SystemAccess == 1) { ?>
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
                                                  <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px">Employee Status *</label>
                                                  <div class="col-sm-6">
                                                    <select id="status" class="form-control">
                                                        
                                                        	<?php if ($EmployeeStatus == 1) { ?>
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
                                                  <label for="postcode" class="col-sm-5 col-form-label" style="padding-top: 5px">Username</label>
                                                  <div class="col-sm-6">
                                                  	<?php if ($SystemAccess == 0)
													{
													?>
                                                    <input type="text" class="form-control" id="username" value="" disabled>
                                                    <?php } else { ?>
                                                    <input type="text" class="form-control" id="username" value="<?php echo $UserName ?>" placeholder="Username">
                                                    <?php } ?>
                                                    
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="postcode" class="col-sm-5 col-form-label" style="padding-top: 5px">Password (leave blank to keep existing)</label>
                                                  <div class="col-sm-6">
                                                  <?php if ($SystemAccess == 0)
													{
													?>
                                                    <input type="password" class="form-control" id="password" disabled>
                                                    <?php } else { ?>
                                                    <input type="password" class="form-control" id="password">
                                                    <?php } ?>
                                                    
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group col-md-6">
                                                  <label for="vatnumber" class="col-sm-5 col-form-label" style="padding-top: 5px">Security Group *</label>
                                                  <?php if ($SystemAccess == 1) { ?>
                                                  <div class="col-sm-6">
                                                    <select class="form-control" id='securitygroup'>
                                                    	<option value="">Please select</option>
                                                        <?php while ($Val = mysqli_fetch_array($SecurityGroups))
														{
															$SecurityGroupID = $Val["SecurityGroupID"];
															$SecurityGroupName = $Val["SecurityGroupName"];	
															
															if ($CurrentSecurityGroup == $SecurityGroupID)
															{
																$Selected = 'selected';	
															}
															else
															{
																$Selected = '';	
															}
															
															
														?>
                                                        <option value="<?php echo $SecurityGroupID ?>" <?php echo $Selected ?>><?php echo $SecurityGroupName ?></option>
                                                        <?php
														}
														
														?>
                                                    </select>
                                                  </div>
                                                  <?php } else { ?>
                                                  <select class="form-control" id='securitygroup' disabled>
                                                    	<option value="">Please select</option>
                                                        <?php while ($Val = mysqli_fetch_array($SecurityGroups))
														{
															$SecurityGroupID = $Val["SecurityGroupID"];
															$SecurityGroupName = $Val["SecurityGroupName"];	
															
															
															
															
														?>
                                                        <option value="<?php echo $SecurityGroupID ?>"><?php echo $SecurityGroupName ?></option>
                                                        <?php
														}
														
														?>
                                                    </select>
                                                  <?php } ?>
                                                </div>
                                                  
                                                  
                                                </div>
                                                
                                                <div class="box-footer" align="center" style="margin-bottom: 50px; margin-top: 20px">
                                        
                                                    <button type="button" class="btn btn-primary pull-right" onClick="javascript: UpdateEmployee();">Update Details</button>
                                                 </div>
                            </div>
                            <!-- /.table-responsive -->
                            
                        
                   
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
           
        </div>
        <!-- /#page-wrapper -->
		
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
