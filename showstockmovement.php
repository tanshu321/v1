<?php
include("includes/webfunctions.php");


//SECURITY
$ThisClientID = $_SESSION["ClientID"];
$ThisUser = $_SESSION["ClientName"];
$ThisSecret = $_SESSION["SiteSecret"];

if ($ThisClientID > 0 && $ThisUser != "" && $ThisSecret == "E2A_crm_S5gdbh6nnj_usr_9898")
{
	$ProductID = $_REQUEST["p"];
	$WarehouseID = $_REQUEST["w"];
	
	$ProductName = GetProductName($ProductID);
	
	$StockOut = GetAllStockOut($ProductID, $WarehouseID);
	$StockIn = GetAllStockIn($ProductID, $WarehouseID);
	$StockTake = GetAllStockTake($ProductID, $WarehouseID);
	$StockTransfer = GetAllStockTransfer($ProductID, $WarehouseID);
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

</head>

<body>

    <div id="wrapper">

        <?php include("navigation.php") ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Stock Movement - <?php echo $ProductName ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> Below you will find all stock movement for <?php echo $ProductName ?>
            </div>    
            <div class="row">
                <div class="col-lg-12">
                <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                <li class="active"><a href="stockcontrol.php"><i class="fa fa-caret-right"></i> System Stock</a>
                                <li><a href="warehouses.php"><i class="fa fa-caret-right"></i> Warehouses</a>
                                <li class="pull-right"><a href="stockcontrol.php"><i class="fa fa-caret-left"></i> Back to All Stock</a>
                                </li>
                                
                                
                                
                               
                               
                            </ul>
                            
                            <div class="col-lg-12"> 
                             
                             <h4>Stock Takes</h4>
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example3">
                                <thead>
                                    <tr>
                                       
                                        <th>Stock Adjusted Date</th>
                                        <th>Stock Adjusted</th>
                                        
                                        
                                       
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php while ($Val = mysqli_fetch_array($StockTake))
									{
										
										$DateAdded = $Val["DateAdded"];
										$Stock = $Val["Stock"];
										$InvoiceID = $Val["InvoiceID"];
										
										if ($InvoiceID == "")
										{
											$ShowInvoice = 'None';	
											$InvoiceNumber = 'None';	
											$ShowInvoiceStatus = 'N/A';
										}
										else
										{
											$InvoiceDetails = GetInvoiceDetails($InvoiceID);
											
											while ($Val = mysqli_fetch_array($InvoiceDetails))
											{
												$CustomerID = $Val["CustomerID"];	
												$InvoiceNumber = $Val["InvoiceNumber"];
												$InvoiceStatus = $Val["InvoiceStatus"];
												
												switch ($InvoiceStatus)
												{
													case 0: $ShowInvoiceStatus	= 'Draft'; break;
													case 1: $ShowInvoiceStatus	= 'Sent to Customer - Unpaid'; break;
													case 2: $ShowInvoiceStatus	= 'Paid'; break;
													case 3: $ShowInvoiceStatus	= 'Cancelled'; break;
													case 4: $ShowInvoiceStatus	= 'Refunded'; break;
													case 5: $ShowInvoiceStatus	= 'Collections'; break;
												}
												
												
											}
											
											$ShowInvoice = '<a href="showinvoice.php?i=' . $InvoiceID . '&c=' . $CustomerID . '" class="btn btn-sm btn-default" target="_blank">Show Invoice</a>';
										}
										
									?>
                                    <tr class="odd gradeX">
                                        
                                        <td><?php echo $DateAdded ?></td>
                                        <td><?php echo $Stock ?></td>
                                        
                                        
                                     
                                        
                                    </tr>
                                    <?php } ?>
                                    
                                    
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                            
                       </div>
                            
                   <div class="col-lg-12"> 
                             
                             <h4>Stock Out</h4>
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                       
                                        <th>Stock Out Date</th>
                                        <th>Stock Out</th>
                                        
                                        <th>Invoice</th>
                                        
                                        <th>Invoice Status</th>
                                        <th>View</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php while ($Val = mysqli_fetch_array($StockOut))
									{
										
										$DateAdded = $Val["DateAdded"];
										$Stock = $Val["Stock"];
										$InvoiceID = $Val["InvoiceID"];
										
										if ($InvoiceID == "")
										{
											$ShowInvoice = 'None';	
											$InvoiceNumber = 'None';	
											$ShowInvoiceStatus = 'N/A';
										}
										else
										{
											$InvoiceDetails = GetInvoiceDetails($InvoiceID);
											
											while ($Val = mysqli_fetch_array($InvoiceDetails))
											{
												$CustomerID = $Val["CustomerID"];	
												$InvoiceNumber = $Val["InvoiceNumber"];
												$InvoiceStatus = $Val["InvoiceStatus"];
												
												switch ($InvoiceStatus)
												{
													case 0: $ShowInvoiceStatus	= 'Draft'; break;
													case 1: $ShowInvoiceStatus	= 'Sent to Customer - Unpaid'; break;
													case 2: $ShowInvoiceStatus	= 'Paid'; break;
													case 3: $ShowInvoiceStatus	= 'Cancelled'; break;
													case 4: $ShowInvoiceStatus	= 'Refunded'; break;
													case 5: $ShowInvoiceStatus	= 'Collections'; break;
												}
												
												
											}
											
											$ShowInvoice = '<a href="showinvoice.php?i=' . $InvoiceID . '&c=' . $CustomerID . '" class="btn btn-sm btn-default" target="_blank">Show Invoice</a>';
										}
										
									?>
                                    <tr class="odd gradeX">
                                        
                                        <td><?php echo $DateAdded ?></td>
                                        <td><?php echo $Stock ?></td>
                                        
                                        
                                       <td class="center"><?php echo $InvoiceNumber ?></td>
                                        <td class="center"><?php echo $ShowInvoiceStatus ?></td>
                                        <td class="center"><?php echo $ShowInvoice ?></td>
                                        
                                    </tr>
                                    <?php } ?>
                                    
                                    
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                            
                       </div>
                       
                       
                       <!--STOCK IN -->
                       <div class="col-lg-12"> 
                             
                             <h4>Stock In</h4>
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example2">
                                <thead>
                                    <tr>
                                       
                                        <th>Stock In Date</th>
                                        <th>Stock In</th>
                                        
                                        <th>Invoice</th>
                                        
                                        <th>Invoice Status</th>
                                        <th>View</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php while ($Val = mysqli_fetch_array($StockIn))
									{
										
										$DateAdded = $Val["DateAdded"];
										$Stock = $Val["Stock"];
										$InvoiceID = $Val["SupplierInvoiceID"];
										
										if ($InvoiceID == "")
										{
											$ShowInvoice = 'None';	
											$InvoiceNumber = 'None';	
											$ShowInvoiceStatus = 'N/A';
										}
										else
										{
											$InvoiceDetails = GetSupplierInvoiceDetails($InvoiceID);
											
											while ($Val = mysqli_fetch_array($InvoiceDetails))
											{
												$SupplierID = $Val["SupplierID"];	
												$InvoiceNumber = $Val["InvoiceNumber"];
												$InvoiceStatus = $Val["InvoiceStatus"];
												
												$ShowInvoiceStatus = 'Complete';
												$InvoiceFile = $Val["InvoiceFile"];
												
												
												
												
											}
											
											if ($InvoiceFile != "")
											{
												$ShowInvoice = '<a href="supplierinvoices/' . $InvoiceFile . '" class="btn btn-sm btn-default" target="_blank">Show Invoice</a>';
											}
											else
											{
												$ShowInvoice = "None uploaded";	
											}
										}
										
									?>
                                    <tr class="odd gradeX">
                                        
                                        <td><?php echo $DateAdded ?></td>
                                        <td><?php echo $Stock ?></td>
                                        
                                        
                                       <td class="center"><?php echo $InvoiceNumber ?></td>
                                        <td class="center"><?php echo $ShowInvoiceStatus ?></td>
                                        <td class="center"><?php echo $ShowInvoice ?></td>
                                        
                                    </tr>
                                    <?php } ?>
                                    
                                    
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                            
                       </div>
                       
                       <!--END STOCK IN -->
                       
                       <!--STOCK IN -->
                       <div class="col-lg-12"> 
                             
                             <h4>Stock Transfers</h4>
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example4">
                                <thead>
                                    <tr>
                                       
                                        <th>Stock Transfer Date</th>
                                        <th>Stock Transfered</th>
                                        
                                        <th>Description</th>
                                        
                                       
                                        
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php while ($Val = mysqli_fetch_array($StockTransfer))
									{
										
										$DateAdded = $Val["DateAdded"];
										$Stock = $Val["Stock"];
										$InvoiceID = $Val["SupplierInvoiceID"];
										
										$Description = $Val["StockType"];
										
									?>
                                    <tr class="odd gradeX">
                                        
                                        <td><?php echo $DateAdded ?></td>
                                        <td><?php echo $Stock ?></td>
                                        
                                        
                                       <td class="center"><?php echo $Description ?></td>
                                       
                                        
                                        
                                    </tr>
                                    <?php } ?>
                                    
                                    
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                            
                       </div>
                       
                       <!--END STOCK IN -->
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
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true,
			"order": [[ 0, "desc" ]]
        });
		
		 $('#dataTables-example2').DataTable({
            responsive: true,
			"order": [[ 0, "desc" ]]
        });
		
		$('#dataTables-example3').DataTable({
            responsive: true,
			"order": [[ 0, "desc" ]]
        });
		
		$('#dataTables-example4').DataTable({
            responsive: true,
			"order": [[ 0, "desc" ]]
        });
    });
	
	//MENU STUFF FOR PAGE
	
	document.getElementById("stockmenu").className = 'active';
	document.getElementById("stockcontrolrmenu").className = 'active';
    </script>

</body>

</html>
