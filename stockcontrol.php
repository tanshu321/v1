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
	
	$Warehouses = GetAllWarehouses();
	$NumWarehouses = mysqli_num_rows($Warehouses);
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

function MoveStock(StockLeft, ProductID, WarehouseID)
{
	var WarehouseArray = agent.call('','GetWarehouseArray','', WarehouseID);
	
	var FieldList = '<div class="col-md-12"></div>';
	FieldList += "<p><label>Quantity to Transfer (Max " + StockLeft + ") *</label><input type='text' class='form-control' id='quantitytransfer' style='padding-bottom: 10px'></p>";
	
	FieldList += "<p><label>Move to Warehouse</label><select class='form-control' id='warehousetomove' style='padding-bottom: 10px'>";
	FieldList += "<option value='' selected>Please Select</option>";
	
	for (i = 0; i < WarehouseArray.length; i++) 
	{
    	var ThisWarehouseID = WarehouseArray[i]["WarehouseID"];
		var ThisWarehouseName = WarehouseArray[i]["WarehouseName"];
		alert(ThisWarehouseID);
		FieldList += "<option value='" + ThisWarehouseID + "'>" + ThisWarehouseName + "</option>";
	}
	
	FieldList += "</select></p>";
	
	
	bootbox.confirm({
        message: FieldList,
		title: "Transfer Stock",
        callback: function (result) 
		{
            if (result === true)
			{
				var QuantityTransfer = document.getElementById("quantitytransfer").value;
				var ToWarehouse = document.getElementById("warehousetomove").value;
				
				
				if (parseInt(QuantityTransfer) <= parseInt(StockLeft))
				{
				
					if (ToWarehouse != "")
					{
						//NOW WE HAVE ENTIRE LINE INPUT, LINK TO OUR TABLE ID, AS WELL AS CREATE A LINE FOR IT
						var DoStockMove = agent.call('','MoveStock', '', QuantityTransfer, ToWarehouse, ProductID, WarehouseID);
						if (DoStockMove == "OK")
						{
							document.location.reload();	
						}
						else
						{
							bootbox.alert(DoStockMove);
						}
					}
					else
					{
						    bootbox.alert({
								message: "Please select a warehouse to move to",
								callback: function () {
									MoveStock(StockLeft, ProductID, WarehouseID)
								}
							})
						
						
					}
				}
				else
				{
					 bootbox.alert({
								message: "The stock amount you entered is greater than the stock available",
								callback: function () {
									MoveStock(StockLeft, ProductID, WarehouseID)
								}
					})
					
				}
				
			}
        }
    })
}

function DoStockTake(WarehouseID)
{
	bootbox.confirm("Are you sure you would like to perform a stock take? All sales after this will not affect the stock until the stock take has been completed.", function(result)
	{ 
		if (result === true)
		{
			var CreateStockTake = agent.call('','CreateStockTake','', WarehouseID);	
			if (CreateStockTake == "OK")
			{
				bootbox.alert("Stock take created, you can now download the stock sheet for the warehouse", function()
				{ 
					document.location.reload();
				});
			}
			else
			{
				bootbox.alert(CreateStockTake);	
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
                    <h1 class="page-header">Stock Control <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> Below you will find all products and their stock counts
            </div>    
            <div class="row">
                <div class="col-lg-12">
                <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                <li class="active"><a href="stockcontrol.php"><i class="fa fa-caret-right"></i> System Stock</a>
                                </li>
                                <li><a href="warehouses.php"><i class="fa fa-caret-right"></i> Warehouses</a>
                                
                                
                                
                               
                               
                            </ul>
                            
                   <div class="col-lg-12"> 
                             
                             <h4>Stock</h4>
                            <?php while ($ValWarehouses = mysqli_fetch_array($Warehouses))
							{
								$WarehouseID = 	$ValWarehouses["WarehouseID"];
								$WarehouseName = $ValWarehouses["WarehouseName"];
								
								$AllWarehouses .= $WarehouseID . ":::";
								
								$StockProducts = GetAllStockProducts();	
								
								//CHEK IF THERES AN OPEN STOCK TAKE
								$LastStockTake = CheckLastStockTake($WarehouseID);
										
							?>
                            <h4><?php echo $WarehouseName ?> 
                            <?php if ($LastStockTake == 1) { ?><button class="btn btn-info" onClick="javascript: DoStockTake(<?php echo $WarehouseID ?>);">Perform Stock Take for <?php echo $WarehouseName ?></button><?php } ?>
                            <?php if ($LastStockTake == 0) { ?><a href="completestocktake.php?w=<?php echo $WarehouseID ?>" class="btn btn-danger">Complete Stock Take for <?php echo $WarehouseName ?></a> <a href="downloadstocksheet.php?w=<?php echo $WarehouseID ?>" class="btn btn-default" target="_blank">Download Stock Take Sheet</a><?php } ?>
                            </h4>
                            <div class="col-md-12" style="padding-bottom: 10px; margin-left: -15px"></div>
                            <table width="100%" class="table table-striped table-bordered table-hover" id="warehouse<?php echo $WarehouseID ?>" style="font-size: 12px">
                                <thead>
                                    <tr>
                                       
                                        <th>Product</th>
                                        <th>Product Group</th>
                                        <th>Stock In</th>
                                        <th>Stock Out</th>
                                        
                                        <th>Current Stock</th>
                                        <th>Stock Status</th>
                                        <th>View</th>
                                        
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
										
										$StockIn = 0;
										$StockOut = 0;
										
										if ($ProductSubGroupID != 0)
										{
											$SubGroup = GetProductSubGroup($ProductSubGroupID);
											$ProductGroup .= " - " . $SubGroup;
										}	
										
										$StockIn = GetStockIn($ProductID, $WarehouseID);
										if ($StockIn == "")
										{
											$StockIn = 0;	
										}
										
										$StockOut = GetStockOut($ProductID, $WarehouseID);
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
                                        <td><?php echo $StockIn ?></td>
                                        <td><?php echo $StockOut ?></td>
                                        
                                        <td><?php echo $StockLeft ?></td>
                                        <td class="center"><?php echo $ShowStatus ?></td>
                                        <td class="center"><a href="showstockmovement.php?p=<?php echo $ProductID ?>&w=<?php echo $WarehouseID ?>" class="btn btn-sm btn-default">View Stock Movement</a>&nbsp;<?php if ($Foo == 'bar') { ?><a href="javascript: AdjustStock(<?php echo $StockLeft ?>, <?php echo $ProductID ?>, <?php echo $WarehouseID ?>);" class="btn btn-sm btn-default">Adjust Available Stock</a><?php } ?><?php if ($NumWarehouses > 1 && $StockLeft > 0) { ?>&nbsp;<a href="javascript: MoveStock(<?php echo $StockLeft ?>, <?php echo $ProductID ?>, <?php echo $WarehouseID ?>);" class="btn btn-sm btn-default">Move Stock</a><?php } ?></td>
                                        
                                    </tr>
                                    <?php } ?>
                                    
                                    
                                </tbody>
                            </table>
                            <?php } ?>
                            <!-- /.table-responsive -->
                            
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
    <script>
    $(document).ready(function() {
		var AllWarehouses = '<?php echo rtrim($AllWarehouses, ":::") ?>';
		var AllWarehousesArray = AllWarehouses.split(":::");
		
		for (i = 0; i < AllWarehousesArray.length; i++) 
		{
    		var ThisID = AllWarehousesArray[i];
			$('#warehouse' + ThisID).DataTable({
            	responsive: true
        	});
		}
        
    });
    </script>

</body>

</html>
