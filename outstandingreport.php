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
	
	$Outstanding = GetAllCustomersOutstandingInvoices();
	
	//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
	if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
	else
	{
		$Access = CheckPageAccess('Outstanding Invoice Report');	
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



function ShowInvoiceDetails(CustomerID)
{
	var CustomerOutstandingDetails = agent.call('','GetCustomerOutstandingDetails','', CustomerID);
	var Output = "<h4>Oustanding Invoices Details</h4><table style='font-size: 12px'  cellspacing='10' cellpadding='10' class='table table-striped table-bordered table-hover  table-responsive'>";
	
	Output += "<thead>";
		Output += "<th>Invoice Number</th>";
		
		Output += "<th>Due Date</th>";
		Output += "<th>Outstanding Amount</th>";
		Output += "</thead>";
	
	for (i = 0; i < CustomerOutstandingDetails.length; i++) 
	{
		var ThisInvoice = CustomerOutstandingDetails[i]["Invoice"];
		var ThisDueDate = CustomerOutstandingDetails[i]["DueDate"];
		var ThisCreated = CustomerOutstandingDetails[i]["InvoiceDate"];
		var ThisAmount = CustomerOutstandingDetails[i]["Outstanding"];
		
		Output += "<tr>";
		Output += "<td>" + ThisInvoice + "</td>";
		
		Output += "<td>" + ThisDueDate + "</td>";
		Output += "<td>" + ThisAmount + "</td>";
		Output += "</tr>";
		
	}
	
	Output += "</table>";
	
	
	
	bootbox.alert(Output);	
}
</script>
</head>

<body>

    <div id="wrapper">

        <?php include("navigation.php") ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Customer Outstanding Invoice Report <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> Below you will find all customers that have an outstanding amount due</div>    
            <div class="row">
                <div class="col-lg-12">
                
                    <?php if ($Access == 1) { ?>             
                   <div class="col-lg-12"> 
                             
                             <h4>Outstanding Report <?php echo date("d M Y") ?> <a href="outstandingreportpdf2.php" class="btn btn-sm btn-default pull-right" style="margin-bottom: 10px" target="_blank"><i class="fa fa-print"></i> Print Report</a></h4>
                            
                            
                            <div class="col-md-12" style="padding-bottom: 10px; margin-left: -15px"></div>
                            <table width="100%" class="table table-striped table-bordered table-hover" id="" style="font-size: 12px">
                                <thead>
                                    <tr>
                                       <th>Company Name</th>
                                        <th>Customer</th>
                                        
                                        <th>Outstanding Invoices</th>
                                        <th>Outstanding Invoices Amount</th>
                                        
                                        
                                        <th>View</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php 
									$AllOutstanding = 0;
									while ($Val = mysqli_fetch_array($Outstanding))
									{
										$CustomerID = $Val["CustomerID"];
										$CustomerName = $Val["FirstName"] . " " . $Val["Surname"];
										$CompanyName = $Val["CompanyName"];
										
										$NumOutstandingInv = GetNumberOutstandingInvoices($CustomerID);
										$AmountOutstanding = GetNumberOutstandingAmount($CustomerID);
										
										
										$AllOutstanding = $AllOutstanding + $AmountOutstanding
										
										
									?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $CompanyName ?></td>
                                        <td><?php echo $CustomerName ?></td>
                                        
                                        <td><?php echo $NumOutstandingInv ?></td>
                                        <td>R<?php echo number_format($AmountOutstanding,2) ?></td>
                                        
                                        
                                        <td class="center"><a href="javascript: ShowInvoiceDetails(<?php echo $CustomerID ?>)" class="btn btn-sm btn-default">Show Details</a>&nbsp;<a href="clientinvoices.php?c=<?php echo $CustomerID ?>" class="btn btn-sm btn-default">Open Client</a></td>
                                        
                                    </tr>
                                    <?php } ?>
                                    
                                    <tr class="odd gradeX">
                                        
                                        <td>&nbsp;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        
                                        
                                        <td class="center"></td>
                                        
                                    </tr>
                                    
                                    <tr class="odd gradeX">
                                        
                                        <td>&nbsp;</td>
                                        <td></td>
                                        <td><strong>Total Outstanding Invoices</strong></td>
                                        <td><strong>R <?php echo number_format($AllOutstanding,2); ?></strong></td>
                                        
                                        
                                        <td class="center"></td>
                                        
                                    </tr>
                                    
                                    
                                </tbody>
                            </table>
                           
                            <!-- /.table-responsive -->
                            
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
    
	
	document.getElementById("reports").className = 'active';
	document.getElementById("outstanding").className = 'active';
    </script>

</body>

</html>
