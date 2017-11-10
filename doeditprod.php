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
	
	$ProductID = $_REQUEST["p"];
	$ThisProductGroupID = $_REQUEST["g"];	
	
	$ProductCostID = $_REQUEST["c"];
	
	$ProductCosting = GetProductCosting($ProductCostID);
	
	while ($Val = mysqli_fetch_array($ProductCosting))
	{
		$OptionName = $Val["OptionName"];
		$OptionCost = $Val["OptionCost"];
		$BillingType = $Val["BillingType"];
		$ProRataBilling = $Val["ProRataBilling"];	
	}

}
else
{
	echo "<script type='text/javascript'>alert('Your session has expired, please login again'); parent.location = 'logout.php';</script>";
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
    <link rel="stylesheet" type="text/css" href="source/jquery.fancybox.css?v=2.1.5" media="screen" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<script type="text/javascript">
function EditPricing()
{
	var PriceDescript = document.getElementById("pricedescript").value;	
	var Price = document.getElementById("price").value;
	var BillingType = document.getElementById("billingtype").value;
	var ProRata = document.getElementById("prorata").value;
	
	if (PriceDescript != "" && parseFloat(Price) > 0 && BillingType != "" && ProRata != "")
	{
		var AddPricing = agent.call('','UpdateProductPricing','', PriceDescript, Price, BillingType, ProRata, '<?php echo $ProductCostID ?>');
		if (AddPricing == "OK")
		{
			parent.location.reload();
		}
		else
		{
			bootbox.alert(AddPricing);	
		}
	}
	else
	{
		bootbox.alert("Please enter the description and price, make sure there is no R in front of the price");	
	}
}
</script>
</head>

<body>
<div align="center">
<table width="600" style="font-size: 12px">
<tr><td>
                                            <!-- /.panel-heading -->
                                            <div class="col-md-12">
                                            
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                               <strong><i class="fa fa-upload fa-fw"></i> Edit Product Pricing</strong>
                                          </div>
                                            <!-- /.panel-heading -->
                                            <div class="panel-body"><!-- /.table-responsive -->
                                            	
                                                <div class="form-group row col-md-12">
                                                  <label for="city" class="col-sm-12 col-form-label" style="padding-top: 5px">Price Description *</label>
                                                  <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="pricedescript" name="pricedescript" placeholder="Price Description" value="<?php echo $OptionName ?>">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="city" class="col-sm-12 col-form-label" style="padding-top: 5px">Price Ex VAT *</label>
                                                  <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="price" name="price" placeholder="Price Ex VAT" value="<?php echo $OptionCost ?>">
                                                  </div>
                                                </div>
                                                
                                                 <div class="form-group row col-md-12">
                                                  <label for="surname" class="col-sm-12 col-form-label" style="padding-top: 5px">Billing Type *</label>
                                                  <div class="col-sm-12">
                                                  <select name="billingtype" class="form-control" id="billingtype">
                                                      <?php if ($BillingType == "Once-Off") { $Selected = 'selected'; } else { $Selected = ""; } ?>
                                                      <option value="Once-Off" <?php echo $Selected ?>>Once-Off</option>
                                                      <?php if ($BillingType == "Monthly") { $Selected = 'selected'; } else { $Selected = ""; } ?>
                                                      <option value="Monthly" <?php echo $Selected ?>>Monthly</option>
                                                      <?php if ($BillingType == "Quarterly") { $Selected = 'selected'; } else { $Selected = ""; } ?>
                                                      <option value="Quarterly" <?php echo $Selected ?>>Quarterly (3 months)</option>
                                                      <?php if ($BillingType == "Semi-Annually") { $Selected = 'selected'; } else { $Selected = ""; } ?>
                                                      <option value="Semi-Annually" <?php echo $Selected ?>>Semi-Annually (6 months)</option>
                                                      <?php if ($BillingType == "Annually") { $Selected = 'selected'; } else { $Selected = ""; } ?>
                                                      <option value="Annually" <?php echo $Selected ?>>Annually (12 months)</option>
                                                    </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="emailaddress" class="col-sm-12 col-form-label" style="padding-top: 5px">Allow Pro Rata Billing *</label>
                                                  <div class="col-sm-12">
                                                    <select class="form-control" id="prorata" <?php echo $ProRataDisabled ?>>
                                                    	
                                                        <option value="0" selected>No</option>
                                                        <option value="1">Yes</option>
                                                        
                                                    </select>
                                                    
                                                  </div>
                                                </div>
                                                
                                                
                                                
                                                <div class="form-group row col-md-12" style="padding-top: 10px">
                                                  <button class="btn btn-info pull-right col-md-12" onClick="javascript: EditPricing();">Edit Pricing</button>
                                                </div>
                                               
                                          </div>
                                          
                                          </div>
                                          </div>
                                          </td>
                                          </tr>
                                          </table>
                                          </div>
                               
</body>
 <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="source/jquery.fancybox.js"></script>

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
</html>