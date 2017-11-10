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
	
	$Outstanding = TotalInvoicesCAR('1,6');
	
	$date = new DateTime();
	
	$currentMonth = $date->format('F');
	
	//$date = new DateTime();
	$interval = new DateInterval('P31D');
	$date->sub($interval);
	$thirtyDayMonth = $date->format('F');
	
	//$date = new DateTime();
	$interval = new DateInterval('P31D');
	$date->sub($interval);
	$sixtyDayMonth = $date->format('F');
	
	//$date = new DateTime();
	$interval = new DateInterval('P31D');
	$date->sub($interval);
	$nityDayMonth = $date->format('F');
	
	//$date = new DateTime();
	$interval = new DateInterval('P31D');
	$date->sub($interval);
	$lastDayMonth = $date->format('F');
	
	
	/*
	  $currentMonth = date("F");
	 //echo $curDate = date("Y-m-d");
     echo $thirtyDayMonth = Date('F', strtotime($currentMonth . " -31 days"));
	 echo $sixtyDayMonth = Date('F', strtotime($thirtyDayMonth . " -31 days"));
	 echo $nityDayMonth = Date('F', strtotime($sixtyDayMonth . " -31 days"));
	 echo $lastDayMonth = Date('F', strtotime($nityDayMonth . " -31 days"));
*/
	
	
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

</head>

<body>

    <div id="wrapper">

        <?php include("navigation.php") ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Customer Aging Report  <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> Below you will find all Customer aging report </div>    
            <div class="row">
                <div class="col-lg-12">
                
                    <?php if ($Access == 1) { ?>             
                   <div class="col-lg-12"> 
                             
                             <h4>Customer Aging Report <?php echo date("d M Y") ?>
							  <a href="customeragingeportpdf.php" class="btn btn-sm btn-default pull-right" style="margin-bottom: 10px" target="_blank"><i class="fa fa-print"></i> Print Report</a> </h4>
                            
                            
                            <div class="col-md-12" style="padding-bottom: 10px; margin-left: -15px"></div>
                            <table width="100%" class="table table-striped table-bordered table-hover" id="" style="font-size: 12px">
                                <thead>
                                    <tr>
                                       
                                        <th>Customer</th>
                                        <th>Company Name</th>
                                        <th>Total Unpaid invoice</th>
                                        <th>Current Invoices </th>
										<th>30 days </th>
										<th>60 days </th>
										<th>90 days </th>
										<th>120+ days </th>	  
                                        
                                                                              
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php 
									$AllInvoiceTotals = $AllCurrentInvoices=$AllthirtyDayInvoice=$AllsixtyDayInvoice=$AllninetyDayInvoice=$AllInvoice= 0;
									for($i=0;$i<count($Outstanding);$i++)
									{
										$CustomerID = $Outstanding[$i]["CustomerID"];
										$CustomerName = $Outstanding[$i]["Fullname"] ;
										$CompanyName = $Outstanding[$i]["CompanyName"];
										
										$CurrentInvoices = TotalInvoicesByDays($currentMonth, $CustomerID, '1,6');
										$thirtyDayInvoice = TotalInvoicesByDays($thirtyDayMonth, $CustomerID, '1,6');
										$sixtyDayInvoice = TotalInvoicesByDays($sixtyDayMonth, $CustomerID, '1,6');
										$ninetyDayInvoice = TotalInvoicesByDays($nityDayMonth, $CustomerID, '1,6');
										$allInvoice = TotalInvoicesByDays($lastDayMonth, $CustomerID, '1,6',TRUE);
													
										$InvoiceTotals = $CurrentInvoices + $thirtyDayInvoice + $sixtyDayInvoice + $ninetyDayInvoice + $allInvoice;									
										$AllInvoiceTotals = $AllInvoiceTotals + $InvoiceTotals;
										$AllCurrentInvoices = $AllCurrentInvoices + $CurrentInvoices;
										$AllthirtyDayInvoice = $AllthirtyDayInvoice + $thirtyDayInvoice;
										$AllsixtyDayInvoice = $AllsixtyDayInvoice + $sixtyDayInvoice;
										$AllninetyDayInvoice = $AllninetyDayInvoice + $ninetyDayInvoice;
										$AllInvoice = $AllInvoice + $allInvoice;
										
										if($InvoiceTotals != '0' && $CurrentInvoices != '0' && $thirtyDayInvoice != '0' && $sixtyDayInvoice != '0' && $ninetyDayInvoice != '0' && $allInvoice != '0')
										{
									?>
									
                                    <tr class="odd gradeX">
                                        
                                        <td><?php echo $CustomerName ?></td>
                                        <td><?php echo $CompanyName ?></td>
                                        <td>R<?php echo number_format($InvoiceTotals,2) ?></td>
										<td>R<?php echo number_format($CurrentInvoices,2) ?></td>
                                        <td>R<?php echo number_format($thirtyDayInvoice,2) ?></td>
										<td>R<?php echo number_format($sixtyDayInvoice,2) ?></td>
										<td>R<?php echo number_format($ninetyDayInvoice,2) ?></td>
										<td>R<?php echo number_format($allInvoice,2) ?></td>
                                        
                                    </tr>
                                    <?php 
										}
									} ?>
                                    
                                    <tr class="odd gradeX">
                                        
                                        <td>&nbsp;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
										<td></td>
										<td></td>
										<td class="center"></td>
                                        
                                    </tr>
                                    
                                    <tr class="odd gradeX" style="font-size:11px;">
                                        
                                        <td>&nbsp;</td>
                                        <td><strong>Total Invoices</strong></td>
                                        <td><strong>R <?php echo number_format($AllInvoiceTotals,2); ?></strong></td>
                                        <td><strong>R <?php echo number_format($AllCurrentInvoices,2); ?></strong></td>
                                        <td><strong>R <?php echo number_format($AllthirtyDayInvoice,2); ?></strong></td>
										<td><strong>R <?php echo number_format($AllsixtyDayInvoice,2); ?></strong></td>
										<td><strong>R <?php echo number_format($AllninetyDayInvoice,2); ?></strong></td>
										<td><strong>R <?php echo number_format($AllInvoice,2); ?></strong></td>
                                        
                                       
                                        
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
