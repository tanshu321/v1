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
	
	$FromDate = $_REQUEST["from"];
	$ToDate = $_REQUEST["to"];
		
	if ($FromDate == "")
	{
		$FromDate = date("Y-m-d", mktime(0,0,0, date("m"), 1, date("Y")));
		$ToDate = date("Y-m-d");	
	}
	
	//FIRST WE GET ALL CUSTOMERS
	$IncomeReport = GetIncomeReport($FromDate, $ToDate);
	
	//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
	if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
	else
	{
		$Access = CheckPageAccess('Income Report');	
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
     <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

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

function SendStatement(CustomerID)
{
	var SendCustomerIStatement = agent.call('','SendCustomerStatement','', CustomerID, '<?php echo $FromDate ?>', '<?php echo $ToDate ?>');	
	if (SendCustomerIStatement == "OK")
	{
		bootbox.alert('The statement has been sent successfully');
	}
	else
	{
		alert(SendCustomerIStatement)	
	}
}



function ShowStatementDetails(CustomerID)
{
	//$OpeningBalance = GetCustomerOpeningStatement($FromDate, $ToDate, $CustomerID);
	//$CustomerStatementArray = GetCustomerStatement($FromDate, $ToDate, $CustomerID);
	var OpeningBalance = agent.call('','GetCustomerOpeningStatement','', '<?php echo $FromDate ?>', '<?php echo $ToDate ?>', CustomerID);
	var StatementArray = agent.call('','GetCustomerStatement','',  '<?php echo $FromDate ?>', '<?php echo $ToDate ?>', CustomerID);
	var Output = "<h4>Customer Statement Details</h4><table style='font-size: 12px'  cellspacing='10' cellpadding='10' class='table table-striped table-bordered table-hover  table-responsive'>";
	
	Output += "<thead>";
		Output += "<th>Date</th>";
		
		Output += "<th>Reference</th>";
		Output += "<th>Description</th>";
		Output += "<th>Debit</th>";
		Output += "<th>Credit</th>";
		Output += "</thead>";
		
		var DebitTotal = 0;
		var CreditTotal = 0;
		
		if (OpeningBalance > 0)
		{
			Output += "<tr>";
			Output += "<td><?php echo $FromDate ?></td>";
			
			var ShowOpening = 'R' + Number(OpeningBalance).toFixed(2);
			
			Output += "<td>OB</td>";
			Output += "<td>Opening Balance</td>";
			Output += "<td>" + ShowOpening + "</td>";
			Output += "<td></td>";
			Output += "</tr>";	
			
			DebitTotal = DebitTotal + Number(OpeningBalance);
		}
		else
		{
			var NewOpening = parseFloat(OpeningBalance) * -1;
			var ShowOpening = 'R' + Number(NewOpening).toFixed(2);
			
			Output += "<td>OB</td>";
			Output += "<td>Opening Balance</td>";
			Output += "<td></td>";
			Output += "<td>" + ShowOpening + "</td>";
			Output += "</tr>";	
			
			CreditTotal = CreditTotal + Number(NewOpening);
				
		}
		
		
	
	for (i = 0; i < StatementArray.length; i++) 
	{
		var ThisDate = StatementArray[i]["Date"];
		var ThisReference = StatementArray[i]["Reference"];
		var ThisDescription = StatementArray[i]["Description"];
		var ThisCredit = StatementArray[i]["Credit"];
		var ThisDebit = StatementArray[i]["Debit"];
		
		var ShowDebit = '';
		var ShowCredit = '';
		
		if (ThisDebit > 0)
		{
			ShowDebit = 	'R' + Number(ThisDebit).toFixed(2);
			DebitTotal = DebitTotal + parseFloat(ThisDebit);
		}
		
		if (ThisCredit > 0)
		{
			ShowCredit = 	'R' + Number(ThisCredit).toFixed(2);
			CreditTotal = CreditTotal + parseFloat(ThisCredit);
		}
		
		Output += "<tr>";
		Output += "<td>" + ThisDate + "</td>";
		
		Output += "<td>" + ThisReference + "</td>";
		Output += "<td>" + ThisDescription + "</td>";
		Output += "<td>" + ShowDebit + "</td>";
		Output += "<td>" + ShowCredit + "</td>";
		Output += "</tr>";
		
	}
	
	Output += "<tr>";
	Output += "<td></td>";
		
	Output += "<td></td>";
	Output += "<td>&nbsp</td>";
	Output += "<td></td>";
	Output += "<td></td>";
	Output += "</tr>";
	
	
	var ShowTotalDebit = 'R' + Number(DebitTotal).toFixed(2);
	var ShowTotalCredit =  'R' + Number(CreditTotal).toFixed(2);
	
	Output += "<tr>";
	Output += "<td></td>";
	Output += "<td></td>";
	Output += "<td><b>Totals</b></td>";
	Output += "<td><b>" + ShowTotalDebit + "</b></td>";
	Output += "<td><b>" + ShowTotalCredit + "</b></td>";
	Output += "</tr>";
	
	Output += "<tr>";
	Output += "<td></td>";
	Output += "<td></td>";
	Output += "<td>&nbsp</td>";
	Output += "<td></td>";
	Output += "<td></td>";
	Output += "</tr>";
	
	var ClosingBalance = parseFloat(DebitTotal) - parseFloat(CreditTotal);
	
	if (ClosingBalance > 0)
	{
		var ShowClosing =  'R' + Number(ClosingBalance).toFixed(2);
		Output += "<tr>";
		Output += "<td></td>";
		Output += "<td></td>";
		Output += "<td><b>Closgin Balance on <?php echo $ToDate ?></td>";
		Output += "<td><b>" + ShowClosing + "</b></td>";
		Output += "<td></td>";
		Output += "</tr>";
	}
	else
	{
		var ShowClosing =  'R' + Number(ClosingBalance * -1).toFixed(2);
		Output += "<tr>";
		Output += "<td></td>";
		Output += "<td></td>";
		Output += "<td><b>Closgin Balance on <?php echo $ToDate ?></td>";
		Output += "<td></td>";
		Output += "<td><b>" + ShowClosing + "</b></td>";
		Output += "</tr>";
	}
	
	Output += "</table>";
	
	
	
	
	
	bootbox.alert(Output);	
}

function ChangeStatementDate()
{
	var FromDate = document.getElementById("fromdate").value;
	var ToDate = document.getElementById("todate").value;
	
	if (FromDate != "" && ToDate != "")
	{
		document.location = 'incomereport.php?from=' + FromDate + '&to=' + ToDate;
	}
	else
	{
		bootbox.alert("Please select a from and to date for the income report");	
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
                    <h1 class="page-header">Customer Statements</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> Below you will find all customers statements. You can select different date ranges for this report.</div>    
            <div class="row">
                <div class="col-lg-12">
                
                     <?php if ($Access == 1) { ?>                   
                   <div class="col-lg-12"> 
                             
                             <h4>Income Report <?php echo $FromDate ?> - <?php echo $ToDate ?> <a href="incomereportpdf.php?from=<?php echo $FromDate ?>&to=<?php echo $ToDate ?>" class="btn btn-sm btn-default pull-right" style="margin-bottom: 10px" target="_blank"><i class="fa fa-print"></i> Print Report</a></h4>
                            <div class="col-lg-12" style="padding-top: 10px"> 
                             
                             <div class="col-md-6">
                            <div class="form-group row col-md-12">
                               	<label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">From Date *</label>
                                <div class="col-sm-6">
                                   <input type="text" class="form-control fromdate" id="fromdate" name="fromdate" placeholder="From Date"  data-date-format="yyyy-mm-dd" value="<?php echo $FromDate ?>">
                                </div>
                             </div>
                             
                             <div class="form-group row col-md-12">
                                 <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">To Date *</label>
                                 <div class="col-sm-6">
                                   <input type="text" class="form-control todate" id="todate" name="todate" placeholder="To Date"  data-date-format="yyyy-mm-dd" value="<?php echo $ToDate ?>">
                                </div>
                             </div>
                             
                             <div class="form-group row col-md-11">
                                 <input type="button" class="btn btn-sm btn-default pull-right" value="Show" style="margin-right: 16px" onClick="ChangeStatementDate();">
                             </div>
                             </div>
                            
                            <div class="col-md-12" style="padding-bottom: 10px; margin-left: -15px"></div>
                            <table width="100%" class="table table-striped table-bordered table-hover" style="font-size: 12px">
                                                    <thead>
                                                        <tr>
                                                        	<th>Customer</th>
                                                            <th>Payment Date</th>
                                                            <th>Description</th>
                                                            <th>Reference</th>
                                                            <th>Payment Method</th>
                                                            <th>Added By</th>
                                                            <th>Payment Amount</th>
                                                            
                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
														$TotalIncome = 0;
														while ($Val = mysqli_fetch_array($IncomeReport))
                                                        {
                                                            $TransactionID = $Val["TransactionID"];
															$PaymentDate = $Val["PaymentDate"];
															
															$AddedBy = $Val["AddedByName"];
															$TotalPayment = $Val["TotalPayment"];
															$Description = $Val["Description"];
															$PaymentMethod = $Val["PaymentMethod"];
															$Ref = $Val["TransactionReference"];
															
															$Customer = $Val["FirstName"] . " " . $Val["Surname"];
															$CompanyName = $Val["CompanyName"];
															$CustomerID = $Val["CustomerID"];
															
															if ($CompanyName != "")
															{
																$Customer .= " (" . $CompanyName . ")";	
															}
															
															$TotalIncome = $TotalIncome + $TotalPayment;
															
															
															
                                                        ?>
                                                        <tr class="odd gradeX">
                                                        <td><?php echo $Customer ?></td>
                                                           <td><?php echo $PaymentDate ?></td>
                                                            <td><?php echo $Description ?></td>
                                                            <td><?php echo $Ref ?></td>
                                                            <td><?php echo $PaymentMethod ?></td>
                                                            <td><?php echo $AddedBy ?></td>
                                                            <td>R<?php echo number_format($TotalPayment,2) ?></td>
                                                            
                                                            
                                                        </tr>
                                                        <?php } ?>
                                                        
                                                        <tr class="odd gradeX">
                                                        <td></td>
                                                           <td>&nbsp;</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        
                                                        <tr class="odd gradeX">
                                                        <td></td>
                                                           <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td><strong>Total Income for Period</strong></td>
                                                            <td><strong>R<?php echo number_format($TotalIncome,2) ?></strong></td>
                                                            
                                                            
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
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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
			pageLength: 100
        });
		
		$('.fromdate').datepicker({
			dateFormat: 'yy-mm-dd'
		});
		
		$('.todate').datepicker({
			dateFormat: 'yy-mm-dd'
		});
    });
	
	document.getElementById("reports").className = 'active';
	document.getElementById("incomereport").className = 'active';
    </script>


</body>

</html>
