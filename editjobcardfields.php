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
	$TableID = $_REQUEST["t"];
	$TableHeading = $_REQUEST["table"];
	
	$Fields = GetJobcardFields($TableID);
	$Fields2 = GetJobcardFields($TableID);
	$NumFields = mysqli_num_rows($Fields2);
	
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
function AddField()
{
	var FieldList = '<div class="col-md-12"></div>';
	FieldList += "<p><label>New Field Name *</label><input type='text' class='form-control' id='newfield' style='padding-bottom: 10px'></p>";
	
	bootbox.confirm({
        message: FieldList,
		title: "Add New Field",
        callback: function (result) 
		{
            if (result === true)
			{
				var NewField = document.getElementById("newfield").value;
				if (NewField != "")
				{
					//NOW WE HAVE ENTIRE LINE INPUT, LINK TO OUR TABLE ID, AS WELL AS CREATE A LINE FOR IT
					var CreateNewField = agent.call('','CreateTableField', '', '<?php echo $TableID ?>', NewField);
					if (CreateNewField == "OK")
					{
						document.location.reload();	
					}
					else
					{
						bootbox.alert(CreateNewField);
					}
				}
				else
				{
					bootbox.alert("You must enter a field name");
				}	
				
			}
        }
    })
}

function AddNewLine()
{
	var CurrentFields = agent.call('','GetJobcardFieldsArray','', '<?php echo $TableID ?>');
	
	//NOW WE BUILD A BOOTBOX INSIDE BOX
	var FieldList = '<div class="col-md-12"></div>';
	
	for (i = 0; i < CurrentFields.length; i++) 
	{
    	var ThisFieldID = CurrentFields[i]["FieldID"];
		var ThisFieldName = CurrentFields[i]["FieldName"];
		
		FieldList += "<p><label>" + ThisFieldName + "</label><input type='text' class='form-control' id='field" + ThisFieldID + "' style='padding-bottom: 10px'></p>";
	}
	
	bootbox.confirm({
        message: FieldList,
		title: "Add New Row",
        callback: function (result) 
		{
            if (result === true)
			{
				var CurrentFields = agent.call('','GetJobcardFieldsArray','', '<?php echo $TableID ?>'); //GET ARRAY AGAIN SO WE CAN SEE WHAT WAS FILLED IN, REMEMBER ITS AN ENTIRE LINE INPUT, SO HAVE TO GROUP TOGHETHER SOMEHOW
				var SendValues = "";
				for (i = 0; i < CurrentFields.length; i++) 
				{
					var ThisFieldID = CurrentFields[i]["FieldID"];
					var FilledInValue = document.getElementById("field" + ThisFieldID).value;
					SendValues += FilledInValue + "--" + ThisFieldID + ":::";
					
				}
				
				//NOW WE HAVE ENTIRE LINE INPUT, LINK TO OUR TABLE ID, AS WELL AS CREATE A LINE FOR IT
				var CreateNewLine = agent.call('','CreateJobcardLine', '', '<?php echo $TableID ?>', SendValues);
				if (CreateNewLine == "OK")
				{
					document.location.reload();	
				}
				else
				{
					bootbox.alert(CreateNewLine);
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
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to add all fields to show on your job card table</div>     
					
                   
                        
                        
                                    
                             <div class="col-lg-12">   
                             <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                <li class="active"><a href="jobcardsetup.php"><i class="fa fa-caret-right"></i> Job Card Setup</a>
                                </li>
                                
                                
                               
                               
                            </ul>
                             <?php if ($Access == 1) { ?>
                             <h4>Current Jobcard Fields - Table <?php echo $TableHeading ?>. A maximum of 6 fields are allowed per table and each field must have a unique name.<?php if ($NumFields < 6) { ?><a href="javascript: AddField();" class="btn btn-default pull-right" style="margin-bottom: 10px" ><i class="fa fa-plus"></i> Add Field</a><?php } ?></h4>   
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" style="font-size: 12px; ">
                                <thead>
                                    <tr>
                                        <th>Position</th>
                                        <th>Field Name</th>
                                        

                                        <th>Action</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php while ($Val = mysqli_fetch_array($Fields)) 
									{ 
										$JobcardFieldID = $Val["JobcardFieldID"];
										$FieldName = $Val["FieldName"];
										$Position = $Val["Position"];
										
										//$NumOptions = CountJobcardFieldOptions($JobcardFieldID);
										
										
										
										?>
                                    <tr class="odd gradeX">
                                        
                                        <td><?php echo $Position ?></td>
                                        <td><?php echo $FieldName ?></td>
                                        <td></td>
                                        
                                        
                                        
                                    </tr>
                                    <?php } ?>
                                    
                                    
                                </tbody>
                            </table>
                            <div style="col-md-12">&nbsp;</div>
                            <?php if ($NumFields > 0) { ?>
                            <h4>Current Job Card Field Lines (You can add blank lines here should you need more spacing for a row)<a href="javascript: AddNewLine();" class="btn btn-default pull-right" style="margin-bottom: 10px" ><i class="fa fa-plus"></i> Add Line</a></h4>   
                            <table width="100%" class="table table-striped table-bordered table-hover" style="font-size: 12px">
                                <thead>
                                    <tr>
                                    	<th>LineID</th>
                                    	<?php while ($Val = mysqli_fetch_array($Fields2))
										{
											$FieldName = $Val["FieldName"];	
										?>
                                        <th><?php echo $FieldName ?></th>
                                       <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                	 <?php 
										//NOW WE NEED TO GET ALL THE LINES FOR THIS TABLE
										$GetLines = GetTableLines($TableID);
										
										while ($Val2 = mysqli_fetch_array($GetLines))
										{
											$LineID = $Val2["JobcardInputLineID"];
											
									?>
                                    <tr class="odd gradeX">
                                       <td><?php echo $LineID ?></td>
										<?php	
											//ANOTHER LOOP TO ADD THE LINE ITEM VALUES
											$Fields = GetJobcardFields($TableID);
											while ($Val3 = mysqli_fetch_array($Fields))
											{
												$JobcardFieldID = $Val3["JobcardFieldID"];
												
												$LineValue = GetJobcardLineValue($TableID, $JobcardFieldID, $LineID);
										?>
                                        <td><?php echo $LineValue ?></td>
                                        <?php }  ?>
                                        
                                        
                                    </tr>
                                    <?php } ?>
                                    
                                    
                                    
                                </tbody>
                            </table>
                            <?php } ?>
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
	document.getElementById("setupmenu").className = 'active';
	document.getElementById("setupjobcardmenu").className = 'active';
	
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true,
			"order": [[ 0, "asc" ]]
        });
		
		 $('#dataTables-example2').DataTable({
            responsive: true,
			"order": [[ 0, "asc" ]]
        });
    });
    </script>

</body>

</html>
