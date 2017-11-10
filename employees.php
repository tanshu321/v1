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
	$Employees = GetAllEmployees();
	
	//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
	if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
	else
	{
		$Access = CheckPageAccess('Employee Management');	
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
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to add all your employee information. To add a new employee simply click on the Add Employee button below. There is no limit on how many employees you can add to the system, but your license will determine how many employees will have access to the system.
                            </div>     
					
                   
                        
                        
                                    
                             <div class="col-lg-12">   
                             <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                <li class="active"><a href="employees.php"><i class="fa fa-caret-right"></i> Employees</a>
                                </li>
                                <li><a href="employeedepartments.php"><i class="fa fa-caret-right"></i> Departments</a>
                                
                                </li>
                                <li><a href="employeesecurity.php"><i class="fa fa-caret-right"></i> Security Groups</a>
                                
                                </li>
                                
                               
                               
                            </ul>
                            <?php if ($Access == 1) { ?>
                             <h4>Current Employees <a href="addemployee.php" class="btn btn-default pull-right" style="margin-bottom: 10px" ><i class="fa fa-plus"></i> Add Employee</a></h4>   
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" style="font-size: 12px">
                                <thead>
                                    <tr>
                                       
                                        <th>Employee Name</th>
                                        <th>Department</th>
                                        <th>Employee Number</th>
                                        <th>Cell</th>
                                        <th>Email</th>
                                        <th>Employement Status</th>
                                        <th>System Access</th>
                                        
                                        <th>View</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php while ($Val = mysqli_fetch_array($Employees)) 
									{ 
										$EmployeeID = $Val["EmployeeID"];
										$Name = $Val["Name"];
										$Surname = $Val["Surname"];
										$IDNumber = $Val["IDNumber"];
										$EmployeeNumber = $Val["EmployeeNumber"];
										
										$EmployeeStatus = $Val["EmployeeStatus"];
										$SystemAccess = $Val["SystemAccess"];
										$DepartmentID = $Val["DepartmentID"];
										$ContactCell = $Val["ContactCell"];
										$ContactEmail = $Val["ContactEmail"];
										
										$Department = GetDepartmentName($DepartmentID);
										
										if ($EmployeeStatus == 1)
										{
											$ShowStatus = '<i class="fa fa-check fa-fw" style="color: green"></i>';
										}
										else
										{
											$ShowStatus = '<i class="fa fa-close fa-fw" style="color: red"></i>';
										}
										
										if ($SystemAccess == 1)
										{
											$ShowSystemAccess = '<i class="fa fa-check fa-fw" style="color: green"></i>';
										}
										else
										{
											$ShowSystemAccess = '<i class="fa fa-close fa-fw" style="color: red"></i>';
										}
										
										
										
										?>
                                    <tr class="odd gradeX">
                                        
                                        <td><?php echo $Name ?> <?php echo $Surname ?></td>
                                        <td><?php echo $Department ?></td>
                                        <td><?php echo $EmployeeNumber ?></td>
                                        <td><?php echo $ContactCell ?></td>
                                        <td><?php echo $ContactEmail ?></td>
                                        <td><?php echo $ShowStatus ?></td>
                                        <td><?php echo $ShowSystemAccess ?></td>
                                        
                                        
                                       
                                        <td class="center"><a href="showemployee.php?e=<?php echo $EmployeeID ?>" class="btn btn-sm btn-default">View Employee Details</a></td>
                                        
                                    </tr>
                                    <?php } ?>
                                    
                                    
                                </tbody>
                            </table>
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
