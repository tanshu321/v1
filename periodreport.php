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
	
	
	
	//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
	if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
	else
	{
		$Access = CheckPageAccess('Period Report');
	}
	
	$FromDate = $_REQUEST["from"];
	$ToDate = $_REQUEST["to"];
	
	if ($FromDate == "")
	{
		$FromDate = date("Y-m-d", mktime(0,0,0,date("m"), 1, date("Y")));
		$ToDate = date("Y-m-d");
	}
	
	$Invoices = GetAllPeriodInvoices($FromDate, $ToDate);
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
function UpdateDateRange()
{
	
	var FromDate = document.getElementById("datepicker").value;
	var ToDate = document.getElementById("datepicker2").value;
	
	if (FromDate != "" && ToDate != "")
	{
		document.location = "periodreport.php?from=" + FromDate + "&to=" + ToDate;
	}
	else
	{
		bootbox.alert("Please select a from and to date to filter the report");	
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
                    <h1 class="page-header">Period Report <img src="images/logo.png" class="img-responsive pull-right"
                                                          style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> Below you will find all invoices for a given date range</div>    
            <div class="row">
                <div class="col-lg-12">
                
                    <?php if ($Access == 1) { ?>             
                   <div class="col-lg-12"> 
                             
                             <h4>Period Report</h4>
                             
                             <h5 class="pull-right form-inline">
                                                
                             
                            From <input type="text" class="form-control datepicker form-inline" id="datepicker" name="datepicker" placeholder="From"  data-date-format="yyyy-mm-dd" value="<?php echo $FromDate ?>"> to <input type="text" class="form-control datepicker form-inline" id="datepicker2" name="datepicker2" placeholder="To"  data-date-format="yyyy-mm-dd" value="<?php echo $ToDate ?>">
                                                
                                                <button class="btn btn-default form-inline" onClick="javascript: UpdateDateRange();">Update Filter</button>
                            &nbsp;&nbsp;<a href="periodreport.php?from=<?php echo $FromDate ?>&to=<?php echo $ToDate ?>" class="btn btn-sm btn-default pull-right" style="margin-bottom: 10px" target="_blank"><i class="fa fa-print"></i> Print Report</a>&nbsp;&nbsp;<a href="periodreportcsv.php?from=<?php echo $FromDate ?>&to=<?php echo $ToDate ?>" class="btn btn-sm btn-default pull-right" style="margin-bottom: 10px; margin-right: 5px" target="_blank"><i class="fa fa-file-excel-o"></i> Export Report</a><a href="periodreporttext.php?from=<?php echo $FromDate ?>&to=<?php echo $ToDate ?>" class="btn btn-sm btn-default pull-right" style="margin-bottom: 10px; margin-right: 5px" target="_blank"><i class="fa fa-file-excel-o"></i> Export
                                     Text file</a>
                            </h5>
                            
                            
                            <div class="col-md-12" style="padding-bottom: 10px; margin-left: -15px"></div>
                            <table width="100%" class="table table-striped table-bordered table-hover"  id="dataTables-example"  style="font-size: 12px">
                                <thead>
                                    <tr>
                                        <th style="display: none"></th>
                                        <th>Period</th>
                                        <th>Invoice Date</th>
                                        <th>GDC</th>
                                        <th>Acc No</th>
                                        <th>Reference</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Tax Type</th>
                                        <th>Contra Account</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php 
									$AllOutstanding = 0;
									while ($Val = mysqli_fetch_array($Invoices))
									{
									    $periodData = getPeriodByDate($Val["InvoiceDate"]);
									    $Period = isset($periodData['title']) ? $periodData['title'] : "";
									    $Description = isset($periodData['description']) ? $periodData['description'] : "";
									    $CA = isset($periodData['contact_account']) ? $periodData['contact_account'] : "";
                                        $gdc = isset($periodData['gdc']) ? $periodData['gdc'] : "";
									    $TaxType = $Val['Taxed'];
                                        $AccountNumber = $Val["DepositReference"];
										$CustomerID = $Val["CustomerID"];
										$InvoiceDate = $Val["InvoiceDate"];
										$reference = $Val["InvoiceNumber"];
                                        $InvoiceID = $Val["InvoiceID"];
										$ThisInvoiceAmount = GetInvoiceTotal($InvoiceID);
										
										//$TotalInvoiced = $TotalInvoiced + $ThisInvoiceAmount;
										
										
										//$InvNumber = "INV" . $InvoiceID;
									?>
                                    <tr class="odd gradeX">
                                        <td style="display: none"><?php echo $InvoiceID ?></td>
                                        <td><?php echo $Period ?></td>
                                        <td><?php echo date("d/m/Y",strtotime($InvoiceDate)) ?></td>
                                        <td><?php echo $gdc ?></td>
                                        <td><?php echo $AccountNumber ?></td>

                                        <td><?php echo $reference ?></td>
                                        <td><?php echo $Description ?></td>
                                        <td><?php echo number_format($ThisInvoiceAmount,2) ?></td>
                                        <td><?php
                                                if($TaxType == 1)
                                                    echo "2";
                                                else
                                                    echo "1";
                                            ?></td>
                                        <td><?php echo $CA ?></td>
                                        
                                        
                                    </tr>
                                    <?php } ?>
                                    
                                    <!--<tr class="odd gradeX">
                                        <td style="display: none"></td>
                                        <td>&nbsp;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        
                                        
                                        
                                    </tr>
                                    
                                    <tr class="odd gradeX">
                                        <td style="display: none"></td>
                                        <td>&nbsp;</td>
                                        <td></td>
                                        <td></td>
                                        <td><strong>Total Billed Invoices</strong></td>
                                        <td><strong>R <?php echo number_format($TotalInvoiced,2); ?></strong></td>
                                        
                                        
                                        
                                        
                                    </tr> -->
                                    
                                    
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
    
	
	document.getElementById("reports").className = 'active';
	document.getElementById("invoicereport").className = 'active';
	
	 $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true,
			pageLength: 100,
			"order": [[ 0, "desc" ]]
        });
		
		
    });
	
    $('.datepicker').datepicker({
    	dateFormat: 'yy-mm-dd'
	});
	
	$('.datepicker2').datepicker({
			dateFormat: 'yy-mm-dd'
			
	});
    </script>

</body>

</html>
