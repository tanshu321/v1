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
	$GroupID = $_REQUEST["g"];
	$GroupName = $_REQUEST["gn"];
	
	$AllModules = GetAllModules();
	
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
	bootbox.prompt("Please enter the new security group name", function(result)
	{ 
		var NewGroup = result;
		
		if (NewGroup != "" && NewGroup != null)
		{
			var AddNewSecurity = agent.call('','AddSecurityGroup','', NewGroup);
		
			if (AddNewSecurity > 0)
			{
				document.location = 'editsecuritygroup.php?g=' + AddNewSecurity;
			}
			else
			{
				bootbox.alert(AddNewDepartment);
			}
		}
	});
}

function EditDepartment(DepartmentID, DepartmentName)
{
	bootbox.prompt({
	  title: "Please change the current department name below",
	  value: DepartmentName,
	  callback: function(result) 
	  {
		var NewDepartmentName = result;
		
		if (NewDepartmentName != "" && NewDepartmentName != null)
		{
			var UpdateDepartment = agent.call('','UpdateDepartment','', DepartmentID, NewDepartmentName);
		
			if (UpdateDepartment == "OK")
			{
				  document.location.reload();
			}
			else
			{
				bootbox.alert(UpdateDepartment);
			}
		}
	  }
	});	
}


</script>
</head>

<body>

    <div id="wrapper">

        <?php include("navigation.php") ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Group Security Settings - <?php echo $GroupName ?> <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
            
                <div class="col-lg-12">
                  <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to manage the group security settings.</div>     
					
                   
                        
                        
                                    
                             <div class="col-lg-12">   
                             <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                <li ><a href="employees.php"><i class="fa fa-caret-right"></i> Employees</a>
                                </li>
                                <li><a href="employeedepartments.php"><i class="fa fa-caret-right"></i> Departments</a>
                                
                                </li>
                                <li  class="active"><a href="employeesecurity.php"><i class="fa fa-caret-right"></i> Security Groups</a>
                                
                                </li>
                                
                                <li class="pull-right"><a href="employeesecurity.php"><i class="fa fa-caret-left"></i> Back to All Groups</a>
                                
                                </li>
                                
                               
                               
                            </ul>
                             <h4 style="margin-bottom: 50px">Current Security Group Settings - <?php echo $GroupName ?></h4>   
                            <?php while ($Val = mysqli_fetch_array($AllModules))
							{
								$ModuleID = $Val["ModuleID"];
								$ModuleName = $Val["ModuleName"];	
								
								$SubModules = GetAllSubModules($ModuleID);
							?>
                            <h4><?php echo $ModuleName ?></h4>
                            <?php while ($Val2 = mysqli_fetch_array($SubModules))
							{
								$SubModuleID = $Val2["SubModuleID"];
								$SubModuleName = $Val2["SubModuleName"];	
								
								$AllSubModules .= $SubModuleID . ",";
								
								//CHECK IF ITS SELECTED FOR THIS GROUP
								$CheckGroupSecurity = CheckGroupSecurity($GroupID, $SubModuleID);
								
								if ($CheckGroupSecurity > 0)
								{
									//THEY HAVE ACCESS TO THIS MODULE
									$Checked = 'checked';	
								}
								else
								{
									$Checked = '';	
								}
							?>
                            <div class="col-md-3">
                            	<h5><input type="checkbox" id="sub<?php echo $SubModuleID ?>" <?php echo $Checked ?>> <?php echo $SubModuleName ?></h5>
                            </div>
                            
                            <?php } ?>
                            <div class="clearfix"></div>
                            <div><hr size="1"></div>
                            <?php } ?>
                            </div>
                            <!-- /.table-responsive -->
                            
                        <div class="col-md-12" style="margin-bottom: 30px"><input type="button" value="Update Security Settings" class="btn btn-info pull-right" onClick="javascript: SaveSecuritySettings();"></div>
                   
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
	
	document.getElementById("setupmenu").className = 'active';
	document.getElementById("setupdepartmentmenu").className = 'active';
	
	function SaveSecuritySettings()
	{
		var AllSubModules = '<?php echo rtrim($AllSubModules, ",") ?>';	
		var AllSubModulesArray = AllSubModules.split(",");
		
		var HasAny = 0;
		
		//CHECK THEY SELECTED ANY
		for (i = 0; i < AllSubModulesArray.length; i++) 
		{
			var ThisSubID = AllSubModulesArray[i];
			var IsChecked = document.getElementById("sub" + ThisSubID).checked;
			
			if (IsChecked === true)
			{
				HasAny = 1;	
			}
			
		}
		
		if (HasAny == 1)
		{
			//CLEAR OUT EXISTING FIRST
			var ClearSecuritySettings = agent.call('','ClearSecuritySettings','', '<?php echo $GroupID ?>');
			var AllSubModulesArray = AllSubModules.split(",");
			
			for (i = 0; i < AllSubModulesArray.length; i++) 
			{
				var ThisSubID = AllSubModulesArray[i];
				var IsChecked = document.getElementById("sub" + ThisSubID).checked;
				
				if (IsChecked === true)
				{
					var AddSecuritySetting = agent.call('','AddGroupSecuritySetting','', ThisSubID, '<?php echo $GroupID ?>');
				}
				
			}
			
			bootbox.alert("Security settings updated successfully", function(){ document.location = 'employeesecurity.php'; });
		}
		else
		{
			bootbox.alert("You have not selected any security settings for this group, please select before saving");	
		}
		
	}
    </script>

</body>

</html>
