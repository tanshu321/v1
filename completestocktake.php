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
	
	$WarehouseID = $_REQUEST["w"];
	
	$StockTake = GetStockTakeDetails($WarehouseID);

	while ($Val = mysqli_fetch_array($StockTake))
	{
		$StockTakeDate = $Val["StockTakeDate"];
		$WarehouseName = $Val["WarehouseName"];
		$StockTakeID = $Val["StockTakeID"];
	}
	
	$StockProducts = GetAllStockProducts();
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
function AdjustStock(StockLeft, ProductID, WarehouseID)
{
	bootbox.prompt({
	  title: "Please change the current stock below",
	  value: StockLeft,
	  callback: function(result) 
	  {
		var NewStock = result;
		
		if (NewStock != "" && NewStock != null && parseFloat(NewStock) >= 0 && $.isNumeric(NewStock))
		{
			var Difference = parseFloat(NewStock) - parseFloat(StockLeft);
			
			bootbox.confirm("This will result in a " + Difference + " in current stock, please confirm", 
			function(result)
			{ 
				if (result === true)
				{
					var AdjustStock = agent.call('', 'AdjustStockLevel','', StockLeft, NewStock, Difference, ProductID, WarehouseID);
					if (AdjustStock == "OK")
					{
						document.location.reload();	
					}
					else
					{
						bootbox.alert(AdjustStock);	
					}
				}
			});
		}
		else
		{
			  
			    if ($.isNumeric(NewStock))
				{
					
				}
				else
				{
					bootbox.alert({
						message: "Please enter a numeric value for your stock",
						callback: function () {
							
						}
					})
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
                    <h1 class="page-header">Stock Take - <?php echo $WarehouseName ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> Please enter the stock take values captured for <?php echo $WarehouseName ?>. Please note the capture has to happen all at once.
            </div>    
            <div class="row">
                <div class="col-lg-12">
                
                            
                   <div class="col-lg-12"> 
                             
                             <h4>Stock Items</h4>
                           
                            <table width="100%" class="table table-striped table-bordered table-hover" id="warehouse<?php echo $WarehouseID ?>" style="font-size: 12px">
                                <thead>
                                    <tr>
                                       
                                        <th>Product</th>
                                        <th>Product Group</th>
                                        
                                        <th>Theoretical Stock</th>
                                        <th>Actual Stock</th>
                                        
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php while ($Val = mysqli_fetch_array($StockProducts))
									{
										$ProductID = $Val["ProductID"];
										$ProductName = $Val["ProductName"];
										$ProductGroupID = $Val["ProductGroupID"];
										$ProductSubGroupID = $Val["ProductSubGroupID"];
										$GroupName = $Val["GroupName"];	
										$MinimumStock = $Val["MinimumStock"];
										
										$AllProductID .= $ProductID . ",";
										
										$StockIn = 0;
										$StockOut = 0;
										
										if ($ProductSubGroupID != 0)
										{
											$SubGroup = GetProductSubGroup($ProductSubGroupID);
											$ProductGroup .= " - " . $SubGroup;
										}	
										
										$StockIn = GetStockInDated($ProductID, $WarehouseID, $StockTakeDate);
										if ($StockIn == "")
										{
											$StockIn = 0;	
										}
										
										$StockOut = GetStockOutDated($ProductID, $WarehouseID, $StockTakeDate);
										if ($StockOut == "")
										{
											$StockOut = 0;	
										}
										
										$StockLeft = $StockIn + $StockOut;
										
										if ($MinimumStock == 0)
										{
											$ShowStatus = "N/A";	
										}
										else
										{
											$Buffer = $StockLeft - $MinimumStock;
											
											if ($Buffer > 0)
											{
												$ShowStatus = '<span class="label label-success">' . $Buffer . ' Above Min</span>';
											}
											else
											{
												$ShowStatus = '<span class="label label-danger">Below Min</span>';
											}
										}
										
										if ($StockLeft <= 0)
										{
											$ShowStatus = '<span class="label label-danger">Out of Stock</span>';
										}
										
										
										
									?>
                                    <tr class="odd gradeX">
                                        
                                        <td><?php echo $ProductName ?></td>
                                        <td><?php echo $GroupName ?></td>
                                        
                                        
                                        <td><span id="stockleft<?php echo $ProductID ?>"><?php echo $StockLeft ?></span></td>
                                        <td class="center"><input type="text" class="form-control" style="width: 90px" value="<?php echo $StockLeft ?>" id="stock<?php echo $ProductID ?>"></td>
                                        
                                        
                                    </tr>
                                    <?php } 
									$AllProductID = rtrim($AllProductID, ",");
									?>
                                    
                                    
                                </tbody>
                            </table>
                            
                            <!-- /.table-responsive -->
                            
                       </div>
                       
                       <div class="row">
                            
                                 
                                        
                                        
                                        <div class="form-group row col-md-12">
                                         
                                            <button class="btn btn-success pull-right" onClick="javascript: CompleteStockTake();">Complete Stock Take</button>
                                          
                                        </div>
                                        
                                       
                                        
                                    
                                
                                
                            
                            <!-- /.col-lg-8 -->
                            
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
    <script src="js/bootbox.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script type="text/javascript">
	function CompleteStockTake()
	{
		var AllProductID = '<?php echo $AllProductID ?>';
		var AllProductIDArray = AllProductID.split(",");
		
		var Error = 0;
		
		for (i = 0; i < AllProductIDArray.length; i++) 
		{
			var ThisID = AllProductIDArray[i];
			var NewStock = document.getElementById("stock" + ThisID).value;
			NewStock = NewStock.replace(/\s+/g, '');
			
			if ($.isNumeric( NewStock) && NewStock >= 0 && NewStock != "")
			{
				
			}
			else
			{
				Error = 1;	
			}
			
		}
		
		if (Error == 0)
		{
			bootbox.confirm("Are you sure all values are correct, this action cannot be undone and the stock take will be completed? Please note this may take a while, please wait for confirmation of completion.", function(result)
			{ 
				if (result === true)
				{
					for (i = 0; i < AllProductIDArray.length; i++) 
					{	
						var ThisID = AllProductIDArray[i];
						var NewStock = document.getElementById("stock" + ThisID).value;
						var CurrentStock = document.getElementById("stockleft" + ThisID).innerHTML;
						
						var Difference = parseFloat(NewStock) - parseFloat(CurrentStock);
						
						if (Difference != 0)
						{
							var AdjustStock = agent.call('', 'AdjustStockLevelStockTake','', CurrentStock, NewStock, Difference, ThisID, '<?php echo $WarehouseID ?>', '<?php echo $StockTakeID ?>');
						}
					
					}
					
					//THEN WHEN IT COMPLETES, COMPLETE STOCK TAKE AND GO BACK TO STOCK SYSTEM
					var DoCompleteStockTake = agent.call('','CompleteStockTake','', '<?php echo $StockTakeID ?>');
					
					if (DoCompleteStockTake == "OK")
					{
						bootbox.alert("The stock take has been completed successfully", function()
						{ 
							document.location = 'stockcontrol.php';
						});
					}
					
				}
			});
		}
		else
		{
			bootbox.alert("Please make sure you have entered all the values and that all values are numeric");	
		}
		
		
	}
    </script>

</body>

</html>
