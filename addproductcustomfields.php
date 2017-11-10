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
	
	
	$ProductCustom = GetProductCustomFields();
	$NumFields = mysqli_num_rows($ProductCustom) + 1;
	
	//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
	if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
	else
	{
		$Access = CheckPageAccess('Custom Product Fields');	
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
function AddMainCustom()
{
	var FieldName = document.getElementById("fieldname").value;
	var Required = document.getElementById("required").value;
	var DisplayOrder = document.getElementById("displayorder").value;	
	var FieldType = document.getElementById("fieldtype").value;
	var DisplayInvoice = document.getElementById("displayinvoice").value;
	var DisplayQuote = document.getElementById("displayquote").value;
	
	if (FieldName != "" && Required != "" && DisplayOrder != "" && FieldType != "")
	{
		var DoUpdate = agent.call('','AddCustomProductField','', FieldName, Required, DisplayOrder, FieldType, DisplayInvoice, DisplayQuote);
		if (DoUpdate > 0)
		{
			if (FieldType == "text" || FieldType == "textarea")
			{
				document.location = 'productcustomfields.php';	
			}
			else
			{
				document.location = 'editproductcustomfields.php?c=' + DoUpdate;	
			}
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
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to add your custom field.
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
                    
                        
                        
                               <?php if ($Access == 1) { ?>                
                             <div class="col-lg-12 tab-content"> 
                             
                             <h4>Add Custom Field</h4>
                             
                            <div class="form-group row col-md-6">
                              <label for="fieldname" class="col-sm-5 col-form-label" style="padding-top: 5px">Field Name *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="fieldname" placeholder="Field Name" value="">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="surname" class="col-sm-5 col-form-label" style="padding-top: 5px">Field Type *</label>
                              <div class="col-sm-6">
                                <select class="form-control" id="fieldtype">
                                	<option value="" selected>Please select</option>
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
                                	<option value="" selected>Please select</option>
                                	<option value="0" >No</option>
                                    <option value="1">Yes</option>
                                    
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
                                	
                                	<option value="0" selected>No</option>
                                    <option value="1">Yes</option>
                                   
                                </select>
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">Display on Quote?</label>
                              <div class="col-sm-6">
                                <select class="form-control" id="displayquote">
                                	
                                	<option value="0" selected>No</option>
                                    <option value="1">Yes</option>
                                   
                                </select>
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-12" align="center" style="padding-top: 40px">
                            <button class="btn btn-default" onClick="javascript: AddMainCustom();">Add Custom Field</button>
                            </div>
                            
                            </div>
                            <!-- /.table-responsive -->
                          
                         
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                   
                
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
			"order": [[ 3, "asc" ]]
        });
    });
	
	//MENU STUFF FOR PAGE
	document.getElementById("setupmenu").className = 'active';
	document.getElementById("setupproductmenu").className = 'active';
    </script>

</body>

</html>
