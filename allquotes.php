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
	$QuoteStatus = $_REQUEST["status"];
	$Quotes = GetAllQuotesReport($QuoteStatus);
	
	//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
	if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
	else
	{
		$Access = CheckPageAccess('All Quotesß');	
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

function UpdateQuoteStatus()
{
	var NewStatus = document.getElementById("newstatus").value;
	document.location = "allquotes.php?status=" + NewStatus;
}

</script>
</head>

<body>

    <div id="wrapper">

        <?php include("navigation.php") ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">System Quotes</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> Below you will find all customers quotes</div>    
            <div class="row">
                <div class="col-lg-12">
                
                     <?php if ($Access == 1) { ?>               
                   <div class="col-lg-12"> 
                             <h4>Quotes</h4>
                             <h4 class="pull-right form-inline">Filter
                             <select id="newstatus" class="form-control form-inline">
                                                	<option value="">Please select filter status</option>
                                                	 <?php if ($InvoiceStatus == 1) { $Selected = 'selected'; } else { $Selected = ''; } ?>
                                                    <option value="1" <?php echo $Selected ?>>Pending</option>
                                                     <?php if ($InvoiceStatus == 2) { $Selected = 'selected'; } else { $Selected = ''; } ?>
                                                    <option value="2" <?php echo $Selected ?>>Accepted</option>
                                                    <?php if ($InvoiceStatus == 3) { $Selected = 'selected'; } else { $Selected = ''; } ?>
                                                    <option value="3" <?php echo $Selected ?>>Lost</option>
                                                   
                                                </select>
                                                
                                                <button class="btn btn-default form-inline" onClick="javascript: UpdateQuoteStatus();">Update Status</button>
                            
                            </h4>
                            <div class="col-md-12" style="padding-bottom: 10px; margin-left: -15px"></div>
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" style="font-size: 12px">
                                                    <thead>
                                                        <tr>
                                                        	
                                                            <th>Quote Number</th>
                                                            <th>Customer</th>
                                                            
                                                            <th>Quote Date</th>
                                                            <th>Expiry Date</th>
                                                            <th>Status</th>
                                                            <th>Added By</th>
                                                            <th>Quote Total</th>
                                                            <th>Actions</th>
                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while ($Val = mysqli_fetch_array($Quotes))
                                                        {
                                                            $QuoteID = $Val["QuoteID"];
															$QuoteNumber = "QU000000" . $QuoteID;
                                                            
															
                                                            $QuoteDate = $Val["QuoteDate"];	
                                                            $ExpiryDate = $Val["ExpiryDate"];
															$AddedBy = $Val["AddedByName"];
															
															$QuoteStatus = $Val["QuoteStatus"];
															
															$Customer = $Val["FirstName"] . " " . $Val["Surname"];
															$CompanyName = $Val["CompanyName"];
															$CustomerID = $Val["CustomerID"];
															
															if ($CompanyName != "")
															{
																$Customer .= " (" . $CompanyName . ")";	
															}
															
															switch ($QuoteStatus)
															{
																case 0: $ShowStatus	= 'Draft'; break;
																case 1: $ShowStatus	= 'Pending'; break;
																case 2: $ShowStatus	= 'Accepted'; break;
																case 3: $ShowStatus	= 'Lost'; break;
																
															}
															
															$Today = date("Y-m-d");
															
															if ($Today > $ExpiryDate && $QuoteStatus < 2)
															{
																$ShowStatus = 'Expired';	
															}
															
															$QuoteTotal = GetQuoteTotal($QuoteID);
															
															//THEN CHECK IF WE CAN STILL EDIT THIS INVOICE
															if ($QuoteStatus == 0) //DRAFT
															{
																$Button = 	'<a href="editcustomerquote.php?q=' . $QuoteID . '&c=' . $CustomerID .'" class="btn btn-sm btn-default" target="_blank">Edit Quote</a>';
															}
															else
															{
																$Button = 	'<a href="showcustomerquote.php?q=' . $QuoteID . '&c=' . $CustomerID .'" class="btn btn-sm btn-default" target="_blank">Show Quote</a>';
															}
															
                                                        ?>
                                                        <tr class="odd gradeX">
                                                           <td><?php echo $QuoteNumber ?></td>
                                                           <td><?php echo $Customer ?></td>
                                                           
                                                            <td><?php echo $QuoteDate ?></td>
                                                            <td><?php echo $ExpiryDate ?></td>
                                                            <td><?php echo $ShowStatus ?></td>
                                                            <td><?php echo $AddedBy ?></td>
                                                            <td>R <?php echo number_format($QuoteTotal,2) ?></td>
                                                            <td><?php echo $Button ?></td>
                                                            
                                                        </tr>
                                                        <?php } ?>
                                                        
                                                        
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
    
	
	document.getElementById("billing").className = 'active';
	document.getElementById("allquotes").className = 'active';
    </script>
    
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true,
			pageLength: 100,
			"order": [[ 0, "desc" ]]
        });
    });
    </script>

</body>

</html>
