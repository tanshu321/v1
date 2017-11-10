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
	$ThisCustomID = $_REQUEST["c"];
	
	$CustomDetails = GetProductCustomField($ThisCustomID);
	
	while ($Val = mysqli_fetch_array($CustomDetails))
	{
		$CustomFieldName = $Val["CustomFieldName"];
		$CustomFieldType = $Val["CustomFieldType"];
		$Required = $Val["Required"];
		$DisplayOrder = $Val["DisplayOrder"];	
		$DisplayInvoice = $Val["ShowInvoice"];
		$DisplayQuote = $Val["ShowQuote"];
		
		if ($CustomFieldType == "text")
		{
			$CustomOption = "	Text Box";
		}
		else if ($CustomFieldType == "checkbox")
		{
			$CustomOption = "	Checkbox";	
		}
		else if ($CustomFieldType == "radio")
		{
			$CustomOption = "	Radio Selection";	
		}
		else if ($CustomFieldType == "select")
		{
			$CustomOption = "	Select Box";	
		}
		else if ($CustomFieldType == "textarea")
		{
			$CustomOption = "	Text Area";	
		}
	}
	
	$ProductCustom = GetProductCustomFields();
	$NumFields = mysqli_num_rows($ProductCustom);
	
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
function EditCustomOption(CustomFieldOptionID, CurrentValue)
{
	bootbox.prompt({
	  title: "Please change the current option value",
	  value: CurrentValue,
	  callback: function(result) 
	  {
		var NewOption = result;
		
		if (NewOption != "" && NewOption != null)
		{
			var UpdateProductOption = agent.call('','UpdateCustomOption','', NewOption, CustomFieldOptionID);
		
			if (UpdateProductOption == "OK")
			{
				document.location.reload();
			}
			else
			{
				bootobox.alert(UpdateProductOption);
			}
		}
	  }
	});
}

function RemoveOption(CustomFieldOptionID)
{
	bootbox.confirm("Are you sure you would like to remove this option? This action cannot be undone", function(result)
	{ 
		if (result == true)
		{
			var DoRemoveOption = agent.call('','RemoveCustomProductOption','', CustomFieldOptionID);	
			if (DoRemoveOption == "OK")
			{
				document.location.reload();
			}
			else
			{
				bootbox.alert(	DoRemoveOption);
			}
		}
	});
}

function UpdateMainCustom()
{
	var FieldName = document.getElementById("fieldname").value;
	var Required = document.getElementById("required").value;
	var DisplayOrder = document.getElementById("displayorder").value;	
	var DisplayInvoice = document.getElementById("displayinvoice").value;
	var DisplayQuote = document.getElementById("displayquote").value;
	
	if (FieldName != "" && Required != "" && DisplayOrder != "")
	{
		var DoUpdate = agent.call('','UpdateCustomProductField','', FieldName, Required, DisplayOrder, '<?php echo $ThisCustomID ?>', DisplayInvoice, DisplayQuote);
		if (DoUpdate == "OK")
		{
			bootbox.alert("Update has been completed");	
		}
		else
		{
			bootbox.alert(DoUpdate);	
		}
	}
	else
	{
		bootbox.alert("Please fill in all fields to save the Custom Field");	
	}
}

function AddCustomOption()
{
	bootbox.prompt({
	  title: "Please enter the new option value",
	  value: '',
	  callback: function(result) 
	  {
		var NewOption = result;
		
		if (NewOption != "" && NewOption != null)
		{
			var AddProductOption = agent.call('','AddCustomProductOption','', NewOption, '<?php echo $ThisCustomID ?>');
		
			if (AddProductOption == "OK")
			{
				document.location.reload();
			}
			else
			{
				bootobox.alert(AddProductOption);
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
                    <h1 class="page-header">Product Custom Fields <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                
                <!-- /.col-lg-12 -->
            </div>
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to edit your custom field. To add a new option field simply click on the Add Option button below.
                            </div>     
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    
					 <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                <li><a href="products.php"><i class="fa fa-caret-right"></i> Products</a>
                                </li>
                                <li ><a href="productsetup.php"><i class="fa fa-caret-right"></i> Product Groups</a>
                                </li>
                                <li><a href="productsubgroups.php"><i class="fa fa-caret-right"></i> Product Sub Groups</a>
                                </li>
                                <li><a href="productmeassures.php"><i class="fa fa-caret-right"></i> Units of Meassure</a>
                                </li>
                                <li class="active"><a href="productcustomfields.php"><i class="fa fa-caret-right"></i> Custom Product Fields</a>
                                </li>
                                <li class="pull-right"><a href="productcustomfields.php"><i class="fa fa-caret-left"></i> Show All Custom Fields</a>
                                </li>
                                
                               
                               
                            </ul>
                    
                        
                        
                                    
                             <div class="col-lg-12 tab-content"> 
                             
                             <h4>Edit Custom Field - <?php echo $CustomFieldName ?></h4>
                             
                            <div class="form-group row col-md-6">
                              <label for="fieldname" class="col-sm-5 col-form-label" style="padding-top: 5px">Field Name *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="fieldname" placeholder="Field Name" value="<?php echo $CustomFieldName ?>">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="surname" class="col-sm-5 col-form-label" style="padding-top: 5px">Field Type *</label>
                              <div class="col-sm-6">
                                <select class="form-control" id="fieldtype" disabled>
                                	<option value="<?php echo $CustomFieldType ?>" selected><?php echo $CustomOption ?></option>
                                	<option value="checkbox">Checkbox</option>
                                    <option value="radio">Radio Selection</option>
                                    <option value="select">Select Box</option>
                                    <option value="text">Text Box</option>
                                    <option value="textarea">Text Area</option>
                                </select>
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">Is this field required?</label>
                              <div class="col-sm-6">
                                <select class="form-control" id="required">
                                	<?php if ($Required == 0) { ?>
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
                              <label for="tel" class="col-sm-5 col-form-label" style="padding-top: 5px">Display Order *</label>
                              <div class="col-sm-6">
                                <select class="form-control" id="displayorder">
                                	<?php for ($X = 1; $X <= $NumFields; $X++) 
									{ 
										if ($X == $DisplayOrder)
										{
											$Selected = 'selected';	
										}
										else
										{
											$Selected = '';	
										}
									?>
                                	<option value="<?php echo $X ?>" <?php echo $Selected ?>><?php echo $X ?></option>
                                    <?php } ?>
                                </select>
                              </div>
                            </div>
                            
                             <div class="form-group row col-md-6">
                              <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">Display on Invoice?</label>
                              <div class="col-sm-6">
                                <select class="form-control" id="displayinvoice">
                                	<?php if ($DisplayInvoice == 0) { ?>
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
                              <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">Display on Quote?</label>
                              <div class="col-sm-6">
                                <select class="form-control" id="displayquote">
                                	<?php if ($DisplayQuote == 0) { ?>
                                	<option value="0" selected>No</option>
                                    <option value="1">Yes</option>
                                    <?php } else { ?>
                                    <option value="0">No</option>
                                    <option value="1" selected>Yes</option>
                                    <?php } ?>
                                </select>
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-12" align="center" style="padding-top: 40px">
                            <button class="btn btn-default" onClick="javascript: UpdateMainCustom();">Update Custom Field Details</button>
                            </div>
                            
                            </div>
                            <!-- /.table-responsive -->
                          
                          <?php 
						  if ($CustomFieldType != "text" && $CustomFieldType != "textarea") 
						  { 
						  		$CustomFieldOptions = GetCustomFieldOptions($ThisCustomID);
								
						  ?>
                          <!-- HERE ADD TABLE FOR OPTIONS IF ANY -->
                          
                          <div class="col-lg-12 tab-content" style="padding-bottom: 30px"> 
                             
                             <h4>Custom Field Options <a href="javascript: AddCustomOption();" class="btn btn-sm btn-default pull-right" style="margin-bottom: 10px"><i class="fa fa-plus"></i> Add Option</a></h4>
                             
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" style="font-size: 12px">
                                <thead>
                                    <tr>
                                       
                                        <th>Option Value</th>
                                        <th>Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php while ($Val = mysqli_fetch_array($CustomFieldOptions)) 
									{ 
										$CustomFieldOptionID = $Val["CustomFieldOptionID"];
										$OptionValue = $Val["OptionValue"];
										
										
										?>
                                    <tr class="odd gradeX">
                                        
                                        <td><?php echo $OptionValue ?></td>
                                        <td class="center"><a href="javascript: EditCustomOption(<?php echo $CustomFieldOptionID ?>, '<?php echo $OptionValue ?>')" class="btn btn-sm btn-default">Edit</a> <a href="javascript: RemoveOption(<?php echo $CustomFieldOptionID ?>)" class="btn btn-sm btn-danger">Remove Option</a> </td>
                                        
                                    </tr>
                                    <?php } ?>
                                    
                                    
                                </tbody>
                            </table>
                            </div>
                            <!-- /.table-responsive -->  
                          <?php } ?>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                   
                
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
   
	//MENU STUFF FOR PAGE
	document.getElementById("setupmenu").className = 'active';
	document.getElementById("setupproductmenu").className = 'active';
    </script>

</body>

</html>
