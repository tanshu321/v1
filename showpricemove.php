<?php
include("includes/webfunctions.php");


//SECURITY
//SECURITY
$ThisClientID = $_SESSION["ClientID"];
$ThisUser = $_SESSION["ClientName"];
$ThisSecret = $_SESSION["SiteSecret"];

if ($ThisClientID > 0 && $ThisUser != "" && $ThisSecret == "E2A_crm_S5gdbh6nnj_usr_9898")
{
	$SupplierID = $_REQUEST["s"];
	$SupplierDetails = GetSupplierDetails($SupplierID);
	
	while ($Val = mysqli_fetch_array($SupplierDetails))
	{
		$SupplierName = $Val["SupplierName"];
		$SupplierEmail = $Val["SupplierEmail"];
		$SupplierTel = $Val["SupplierTel"];
		$SupplierFax = $Val["SupplierFax"];
		$SupplierContact = $Val["SupplierContact"];
		$SupplierVat = $Val["SupplierVat"];
		$SupplierAddress1 = $Val["SupplierAddress1"];
		$SupplierAddress2 = $Val["SupplierAddress2"];
		$City = $Val["City"];
		$State = $Val["State"];
		$PostCode = $Val["PostCode"];
		$ThisCountryID = $Val["CountryID"];
		$SupplierNote = $Val["SupplierNote"];
		$SupplierStatus = $Val["SupplierStatus"];
		
		if ($SupplierStatus == 1)
		{
			//ACTIVE	
			$ShowStatus = '<span class="label label-success" style="font-size: 14px">Active</span>';
		}
		else
		{
			//INACTIVE	
			$ShowStatus = '<span class="label label-danger" style="font-size: 14px">Inactive</span>';
		}
	}
	
	$ProductID = $_REQUEST["p"];
	$CostingID = $_REQUEST["c"];
	
	$ProductName = GetProductName($ProductID);
	
	$PriceMove = GetPriceMove($CostingID);
	
	
	$SupplierCosting = GetSupplierCost($ProductID, $SupplierID);
	while ($Val2 = mysqli_fetch_array($SupplierCosting))
	{
			$MeasurementID = $Val2["MeasurementID"];
			$BillingType = $Val2["BillingType"];
			$SupplierCostID = $Val2["SupplierCostID"];
			$ProRata = $Val2["ProRataBilling"];
			$PackSize = $Val2["PackSize"];
			
			$Measurement = GetSingleMeassure($MeasurementID);
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
    
    <!-- Add fancyBox -->
    <link rel="stylesheet" href="fancybox/source/jquery.fancybox.css?v=2.1.6" type="text/css" media="screen" />
   

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <?php include("navigation.php") ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Supplier Products - <?php echo $SupplierName ?> <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> Below you will find the price movement of this specific product and pricing option chosen
                            </div>
                 <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                
                                <li ><a href="showsupplier.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Supplier Details</a>
                                </li>
                                <li class="active"><a href="supplierproducts.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Products</a>
                                </li>
                                <li><a href="supplierinvoices.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Invoices</a>
                                </li>
                                <li><a href="supplierpo.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Purchase Orders</a>
                                </li>
                                <li class="pull-right"><a href="supplierproducts.php?s=<?php echo $SupplierID ?>"><i class="fa fa-chevron-left"></i> Back to All Products</a>
                                </li>
                               
                               
                            </ul>
                    
                           

                         
                                    <!-- First Panel Table -->
                                    <div class="col-lg-12">
                                        
                                                
                                                
                                                            
                                                            
                                                 <div class="col-lg-12">
                                                 <h4><?php echo $ProductName ?> <span class=""><?php echo $ShowStatus ?></span></h4>
                                                <table width="100%" class="table table-striped table-bordered table-hover" style="font-size: 12px">
                                                    <thead>
                                                        <tr>
                                                           
                                                            <th>Price Date</th>
                                                            <th>Pack Size</th>
                                                            <th>Price Type</th>
                                                            <th>Supplier Cost</th>
                                                            <th>Unit Cost</th>
                                                            
                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while ($Val = mysqli_fetch_array($PriceMove))
														{
															$PriceDate = $Val["PriceDate"];
															$SupplierCost = $Val["SupplierCost"];
															$UnitCost = $Val["UnitCost"];
															
															
														?>
                                                        <tr class="odd gradeX">
                                                            
                                                            <td><?php echo $PriceDate ?></td>
                                                            <td><?php echo $PackSize . " " . $Measurement ?></td>
                                                            <td><?php echo $BillingType ?></td>
                                                            <td>R<?php echo number_format($SupplierCost,2) ?></td>
                                                            <td>R<?php echo number_format($UnitCost,2) ?></td>
                                                            
                                                            
                                                        </tr>
                                                       <?php } ?>
                                                        
                                                        
                                                    </tbody>
                                                </table>
                                                 </div>
                                               
                                               
                                                <!-- END FORM CONTROLS -->
                                           
                                        
                                        
                                    </div>
                                    <!-- End first panel -->
                                    
                                    
                                   
                                   
                                    <!-- End inside tab -->
                                </div>
                                
                            </div>
                      
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

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
     <script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js?v=2.1.6"></script>
    
  <script type="text/javascript">
	$(document).ready(function() {
		$(".fancybox").fancybox();
	});
</script>

<script type="text/javascript">
	//MENU STUFF FOR PAGE
	document.getElementById("suppliermenu").className = 'active';
	document.getElementById("setupsuppliermenu").className = 'active';
	</script>

</body>

</html>
