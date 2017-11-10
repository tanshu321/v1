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

$ThisClientID = $_SESSION["ClientID"];
$ThisUser = $_SESSION["ClientName"];
$ThisSecret = $_SESSION["SiteSecret"];

if ($ThisClientID > 0 && $ThisUser != "" && $ThisSecret == "E2A_crm_S5gdbh6nnj_usr_9898")
{
	$ThisFieldID = $_REQUEST["c"];
	$CustomFieldDetails = GetCustomFieldDetails($ThisFieldID);
	
	while ($Val = mysqli_fetch_array($CustomFieldDetails))
	{
		$CustomFieldName = $Val["CustomFieldName"];
		$CustomFieldType = $Val["CustomFieldType"];	
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
function RemoveOption(CustomClientFieldOptionID)
{
	bootbox.confirm("Are you sure you would like to remove this option?", function(Result)
	{ 
		if (Result === true)
		{
			var RemoveCustomOption = agent.call('','RemoveCustomOption','', CustomClientFieldOptionID);
			if (RemoveCustomOption == "OK")
			{
				bootbox.alert('Custom option removed successfully', function() {
							document.location.reload();
				});
			}
			else
			{
				bootbox.alert(RemoveCustomOption);	
			}
		}
	});	
}

function AddOption()
{
	var NewOption = document.getElementById("optionvalue").value;
	if (NewOption != "")
	{
		var AddNewOption = agent.call('', 'AddNewOption','', NewOption, '<?php echo $ThisFieldID ?>');
		if (AddNewOption == "OK")
		{
			bootbox.alert('Option added successfully', function() {
							document.location.reload();
				});
		}
		else
		{
			bootbox.alert(AddNewOption);
		}
	}
	else
	{
		bootbox.alert("Please type the new value to add in the box");	
	}
}

function RemoveCustomField()
{
	bootbox.confirm("Are you sure you would like to remove this field completely?", function(Result)
	{ 
		if (Result === true)
		{
			var RemoveCustomOption = agent.call('','RemoveCustomField','', '<?php echo $ThisFieldID ?>');
			if (RemoveCustomOption == "OK")
			{
				bootbox.alert('Field removed successfully', function() {
							document.location = 'customsetup.php';
				});
			}
			else
			{
				bootbox.alert(RemoveCustomOption);	
			}
		}
	});		
}
</script>
</head>

<body>

    <div id="wrapper">

        <?php include("navigation.php"); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Edit Custom Field</h1>
                   
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong><i class="fa fa-cog fa-fw"></i> Field Details</strong>
                            
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="form-group row col-md-6">
                              <label for="firstname" class="col-sm-5 col-form-label" style="padding-top: 5px">Field Name *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="fieldname" placeholder="Field Name" value="<?php echo $CustomFieldName ?>">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="surname" class="col-sm-5 col-form-label" style="padding-top: 5px">Field Type</label>
                              <div class="col-sm-6">
                                <select class="form-control" id="fieldtype" disabled>
                                	<option value="<?php echo $CustomFieldType ?>"><?php echo $CustomFieldType ?></option>
                                    
                                </select>
                              </div>
                            </div>
                            
                            
                            
                            <!-- END FORM CONTROLS -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    
                    
                </div>
                <!-- /.col-lg-8 -->
                
            </div>
            <!-- /.row -->
            <?php if ($CustomFieldType == "select" || $CustomFieldType == "checkbox" || $CustomFieldType == "radio") 
			{ 
				$CustomOptions = GetCustomOptions($ThisFieldID);
			
			?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong><i class="fa fa-cog fa-fw"></i> Field Options</strong>
                            
                        </div>
                        <!-- /.panel-heading -->
                      <div class="panel-body">
                        <div class="col-lg-3">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                               <strong><i class="fa fa-plus fa-fw"></i> Add New Option</strong>
                                          </div>
                                            <!-- /.panel-heading -->
                                            <div class="panel-body"><!-- /.table-responsive -->
                                            	
                                                <div class="form-group row col-md-12">
                                                  <label for="city" class="col-sm-12 col-form-label" style="padding-top: 5px">Option Value *</label>
                                                  <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="optionvalue" name="optionvalue" placeholder="Option Value" value="">
                                                  </div>
                                                </div>
                                                
                                                
                                                
                                                <div class="form-group row col-md-12" style="padding-top: 10px">
                                                  <button class="btn btn-info pull-right col-md-12" onClick="javascript: AddOption();">Add Option</button>
                                                </div>
                                               
                                          </div>
                                            <!-- /.panel-body -->
                                        </div>
                                        <!-- /.panel -->
                        </div>
                            
                        <div class="col-lg-9">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                               <strong><i class="fa fa-database fa-fw"></i> Current Options</strong>
                                          </div>
                                            <!-- /.panel-heading -->
                                            <div class="panel-body">
                                                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" style="font-size:12px">
                                                    <thead>
                                                        <tr>
                                                           
                                                            <th>Value ID</th>
                                                            <th>Option Value</th>
                                                            <th>Remove</th>
                                                            
                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while ($Val = mysqli_fetch_array($CustomOptions))
                                                        {
                                                            $CustomClientFieldOptionID = $Val["CustomClientFieldOptionID"];
                                                            $OptionValue = $Val["OptionValue"];
                                                           
                                                            
                                                        ?>
                                                        <tr class="odd gradeX">
                                                           
                                                            <td><?php echo $CustomClientFieldOptionID ?></td>
                                                            <td><?php echo $OptionValue ?></td>
                                                            <td><button class="btn btn-danger" onClick="javascript: RemoveOption(<?php echo  $CustomClientFieldOptionID ?>);">Remove</button></td>
                                                            
                                                            
                                                        </tr>
                                                        <?php } ?>
                                                        
                                                        
                                                    </tbody>
                                                </table>
                                                <!-- /.table-responsive -->
                                                
                                          </div>
                                            <!-- /.panel-body -->
                                        </div>
                                        <!-- /.panel -->
                                    </div>
                          <!-- END FORM CONTROLS -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    
                    
                </div>
                <!-- /.col-lg-8 -->
                
            </div>
            <?php } ?>
           
            <!-- /.row -->
            
            <div class="row" style="padding-bottom: 20px">
            	 
                              <div class="col-md-12" align="right">
                                <button class="btn btn-danger pull-left" onClick="javascript: RemoveCustomField();">Remove Custom Field</button>
                                <button class="btn btn-info pull-right" onClick="javascript: AddClient();">Update Custom Field</button>
                                
                              </div>
                            
         
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

   
    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>
    <script src="js/bootbox.js"></script>

</body>

</html>
