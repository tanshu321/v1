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
	$JobcardTables = GetAllJobcardTables();
	
	//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
	if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
	else
	{
		$Access = CheckPageAccess('Jobcard Setup');	
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
function AddTable()
{
	var FieldList = '<div class="col-md-12"></div>';
	FieldList += "<p><label>Table Heading *</label><input type='text' class='form-control' id='tableheading' style='padding-bottom: 10px'></p>";
	FieldList += "<p><label>Show Heading</label><select class='form-control' id='showheading' style='padding-bottom: 10px'><option value='0' selected>No</option><option value='1'>Yes</option></select></p>";
	FieldList += "<p><label>Show Lines</label><select class='form-control' id='showlines' style='padding-bottom: 10px'><option value='0' selected>No</option><option value='1'>Yes</option></select></p>";
	
	bootbox.confirm({
        message: FieldList,
		title: "Add New Table",
        callback: function (result) 
		{
            if (result === true)
			{
				var TableHeading = document.getElementById("tableheading").value;
				var ShowLines = document.getElementById("showlines").value;
				var ShowHeading = document.getElementById("showheading").value;
				
				//NOW WE HAVE ENTIRE LINE INPUT, LINK TO OUR TABLE ID, AS WELL AS CREATE A LINE FOR IT
				var CreateNewTable = agent.call('','CreateJobcardTable', '', TableHeading, ShowLines, ShowHeading);
				if (CreateNewTable == "OK")
				{
					document.location.reload();	
				}
				else
				{
					bootbox.alert(CreateNewTable);
				}
				
			}
        }
    })
	
}

</script>
</head>

<body>

    <div id="wrapper">

        <?php include("navigation.php") ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Job Card Setup<img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
            
                <div class="col-lg-12">
                  <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to add all information to be displayed on your job card. Job cards work on tables and you can add as many tables as needed to your job card. All tables fill the width of an A4 page.</div>     
					
                   
                        
                        
                                    
                             <div class="col-lg-12">   
                             <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                <li class="active"><a href="jobcardsetup.php"><i class="fa fa-caret-right"></i> Job card Setup</a>
                                </li>
                                
                                
                               
                               
                            </ul>
                             <?php if ($Access == 1) { ?>
                             <h4>Current Job Card Tables<a href="javascript: AddTable();" class="btn btn-default pull-right" style="margin-bottom: 10px" ><i class="fa fa-plus"></i> Add Table</a><a href="showjobcardsetup.php" class="btn btn-default pull-right" style="margin-bottom: 10px; margin-right: 10px" target="_blank"><i class="fa fa-eye"></i> Preview Job Card</a></h4>   
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" style="font-size: 12px">
                                <thead>
                                    <tr>
                                        <th>Position</th>
                                        <th>Table Heading</th>
                                        <th>Num Fields</th>
                                        <th>Show Heading</th>
                                        <th>Show Table Headings</th>
                                        <th>Show Lines</th>

                                        <th>View</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php while ($Val = mysqli_fetch_array($JobcardTables)) 
									{ 
										$TableID = $Val["JobcardTableID"];
										$Position = $Val["TablePosition"];
										$TableHeading = $Val["TableHeading"];
										$ShowHeading = $Val["ShowHeading"];
										$ShowLines = $Val["ShowLines"];
										$ShowTableHeadings = $Val["ShowTableHeadings"];
										
										if ($ShowTableHeadings == 0)
										{
											$ShowTableHeadings = "No";	
										}
										else
										{
											$ShowTableHeadings = "Yes";	
										}
										
										
										if ($ShowHeading == 0)
										{
											$ShowHeading = "No";	
										}
										else
										{
											$ShowHeading = "Yes";	
										}
										
										if ($ShowLines == 0)
										{
											$ShowLines = "No";	
										}
										else
										{
											$ShowLines = "Yes";	
										}
										
										
										$NumFields = CountJobcardFields($TableID);
										
										
										
										?>
                                    <tr class="odd gradeX">
                                        
                                        <td><?php echo $Position ?></td>
                                        <td><?php echo $TableHeading ?></td>
                                        <td><?php echo $NumFields ?></td>
                                        <td><?php echo $ShowHeading ?></td>
                                        <td><?php echo $ShowTableHeadings ?></td>
                                        <td><?php echo $ShowLines ?></td>
                                        <td class="center"><a href="editjobcardfields.php?t=<?php echo $TableID ?>&table=<?php echo $TableHeading ?>" class="btn btn-sm btn-default">Edit Fields</a></td>
                                        
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
