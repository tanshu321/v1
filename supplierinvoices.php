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
	
	$Invoices = GetSupplierInvoices($SupplierID);
	
	$Countries = GetCountries();
	
	//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
	if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
	else
	{
		$Access = CheckPageAccess('Supplier Invoices');	
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
    <link rel="stylesheet" type="text/css" href="source/jquery.fancybox.css?v=2.1.5" media="screen" />

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
                    <h1 class="page-header">Supplier Purchase Orders - <?php echo $SupplierName ?> <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> Below you will find all the supplier invoices. To add a new invoice simply click on the Add Invoice button below
                            </div>
                 			<ul class="nav nav-tabs" style="margin-bottom: 20px">
                                
                                <li ><a href="showsupplier.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Supplier Details</a>
                                </li>
                                <li><a href="supplierproducts.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Products</a>
                                </li>
                                <li  class="active"><a href="supplierinvoices.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Invoices</a>
                                </li>
                                <li><a href="supplierpo.php?s=<?php echo $SupplierID ?>"><i class="fa fa-caret-right"></i> Purchase Orders</a>
                                </li>
                                <li class="pull-right"><a href="suppliersetup.php"><i class="fa fa-chevron-left"></i> Back to All Suppliers</a>
                                </li>
                               
                               
                            </ul>
                    
                           
									<?php if ($Access == 1) { ?>      
                         
                                    <!-- First Panel Table -->
                                    <div class="col-lg-12">
                                        
                                                
                                                
                                                 <div class="col-lg-12">   
                                                 <h4>Invoices <a href="editsupplierpo.php?s=<?php echo $SupplierID ?>" class="btn btn-default pull-right" style="margin-bottom: 10px" ><i class="fa fa-plus"></i> Add Purchase Order</a></h4>   
                                                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" style="font-size: 12px">
                                                    <thead>
                                                        <tr>
                                                           
                                                            <th>PO Number</th>
                                                            <th>Invoice Number</th>
                                                            <th>Added</th>
                                                            <th>Added By</th>
                                                            <th>PO Total</th>
                                                            <th>Status</th>
                                                            <th>View</th>
                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while ($Val = mysqli_fetch_array($Invoices)) 
                                                        { 
                                                            $SupplierInvoiceID = $Val["SupplierInvoiceID"];
                                                            $PurchaseNumber = $Val["PurchaseNumber"];
                                                            $InvoiceDate = $Val["InvoiceDate"];
                                                            $PurchaseStatus = $Val["InvoiceStatus"];
                                                            $AddedByName = $Val["AddedByName"];
															$SentDate = $Val["SentDate"];
															$InvoiceNumber = $Val["InvoiceNumber"];
                                                           
                                                            
                                                            if ($PurchaseStatus == 0)
                                                            {
                                                                $ShowStatus = 'Created Only';
                                                            }
                                                            else if ($PurchaseStatus == 1)
                                                            {
                                                                $ShowStatus = 'Complete';
                                                            }
															else if ($PurchaseStatus == 2)
                                                            {
                                                                $ShowStatus = 'Cancelled';
                                                            }
															$InvoiceTotal = 0;
															$InvoiceTotal = GetSupplierOrderTotal($SupplierInvoiceID);
                                                            
                                                            
                                                            
                                                            ?>
                                                        <tr class="odd gradeX">
                                                            
                                                            <td><?php echo $PurchaseNumber ?></td>
                                                            <td><?php echo $InvoiceNumber ?></td>
                                                            <td><?php echo $InvoiceDate ?></td>
                                                            <td><?php echo $AddedByName ?></td>
                                                            <td>R<?php echo number_format($InvoiceTotal, 2) ?></td>
                                                            <td><?php echo $ShowStatus ?></td>
                                                            
                                                           
                                                            <td class="center"><a href="editsupplierinvoice.php?s=<?php echo $SupplierID ?>&i=<?php echo $SupplierInvoiceID ?>" class="btn btn-sm btn-default">View Invoice Details</a></td>
                                                            
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
        <?php } else { ?>
        <h4>You do not have access to this module, if you think this is a mistake please contact your system administrator</h4>
        <?php } ?> 
		
    </div>
    
    
    
    <!-- /#wrapper -->

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

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    
    
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
    
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true,
			"order": [[ 1, "desc" ], [ 0, "desc" ]]
        });
    });
    </script>

</body>

</html>
