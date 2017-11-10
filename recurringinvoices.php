<?php
include("includes/webfunctions.php");


//SECURITY
$ThisClientID = $_SESSION["ClientID"];
$ThisUser = $_SESSION["ClientName"];
$ThisSecret = $_SESSION["SiteSecret"];

if ($ThisClientID > 0 && $ThisUser != "" && $ThisSecret == "E2A_crm_S5gdbh6nnj_usr_9898")
{
	$Recurring = GetAllRecurringInvoices();
	
	
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
                    <h1 class="page-header">Recurring Invoices <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           <strong><i class="fa fa-bars fa-fw"></i> Current Recurring Invoices</strong>
                           <span class="pull-right" ><button class="btn btn-info" style="margin-top: -7px" onClick="javascript: document.location = 'addrecurring.php'">Add New Recurring Invoice</button></span>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                       
                                        <th>Customer</th>
                                        <th>Recurring Invoice Number</th>
                                        <th>Start Date</th>
                                        <th>Last Run</th>
                                        <th>Next Run</th>
                                        <th>End Date</th>
                                        <th>Total</th>
                                        <th>Frequency</th>
                                        <th>Status</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php while ($Val = mysqli_fetch_array($Recurring))
									{
										$ClientID = $Val["ClientID"];
										$RecurringID = $Val["RecurringID"];
										$CustomerID = $Val["CustomerID"];
										$Name = $Val["FirstName"];
										$Surname = $Val["Surname"];	
										$CompanyName = $Val["CompanyName"];
										$EmailAddress = $Val["EmailAddress"];
										$DateAdded = $Val["InvoiceDateAdded"];
										$TaxExempt = $Val["TaxExempt"];
										
										$StartDate = $Val["StartDate"];
										$EndDate = $Val["EndDate"];
										$Frequency = $Val["Frequency"];
										$ClientReccuringInvoiceNumber = $Val["ClientReccuringInvoiceNumber"];
										$InvoiceDateAdded = $Val["InvoiceDateAdded"];
										$LastRun = $Val["LastRun"];
										$NextRun = $Val["NextRun"];
										$DiscountPercent = $Val["DiscountPercentage"];
										
										if ($EndDate == "" || $EndDate ="0000-00-00")
										{
											$EndDate = "Never";	
										}
										
										
										
										if ($LastRun == "")
										{
											$LastRun = "Never";	
											$NextRun = $StartDate;
										}
										else
										{
											$StartArray = explode("-", $LastRun);
											
											$StartYear = $StartArray[0];
											$StartMonth = $StartArray[1];
											$StartDay = $StartArray[2];
											
											
											
											//WORK OUT NEXT RUN
											if ($Frequency == "Every Day")
											{
												$NextRun = date("Y-m-d", mktime(0,0,0, $StartMonth, $StartDay + 1, $StartYear)); 	
											}
											
											if ($Frequency == "Every Week")
											{
												$NextRun = date("Y-m-d", mktime(0,0,0, $StartMonth, $StartDay + 7, $StartYear)); 	
											}
											
											if ($Frequency == "Every Second Week")
											{
												$NextRun = date("Y-m-d", mktime(0,0,0, $StartMonth, $StartDay + 14, $StartYear)); 	
											}
											
											if ($Frequency == "Every Month")
											{
												$NextRun = date("Y-m-d", mktime(0,0,0, $StartMonth + 1, $StartDay, $StartYear)); 	
											}
											
											if ($Frequency == "Every Third Month")
											{
												$NextRun = date("Y-m-d", mktime(0,0,0, $StartMonth + 3, $StartDay, $StartYear)); 	
											}
											
											if ($Frequency == "Every Six Months")
											{
												$NextRun = date("Y-m-d", mktime(0,0,0, $StartMonth + 6, $StartDay, $StartYear)); 	
											}
											
											if ($Frequency == "Every Nine Months")
											{
												$NextRun = date("Y-m-d", mktime(0,0,0, $StartMonth + 6, $StartDay, $StartYear)); 	
											}
											
											if ($Frequency == "Every Year")
											{
												$NextRun = date("Y-m-d", mktime(0,0,0, $StartMonth, $StartDay, $StartYear + 1)); 	
											}	
										}
										
										
										
										
										$Status = $Val["RecurringStatus"];
										if ($Status == 2)
										{
											//ACTIVE	
											$ShowStatus = '<span class="label label-success">Active</span>';
										}
										else
										{
											//INACTIVE	
											$ShowStatus = '<span class="label label-danger">Disabled</span>';
										}
										
										$RecurringTotal = GetRecurringTotal($RecurringID, $DiscountPercent, $TaxExempt);
										
										
									?>
                                    <tr class="odd gradeX">
                                        
                                        <td><a href="editrecurring.php?r=<?php echo $RecurringID ?>"><?php echo $Name ?> <?php echo $Surname ?> <?php if ($CompanyName != "") { ?>( <?php echo $CompanyName ?> )<?php } ?></a></td>
                                        <td><?php echo $ClientReccuringInvoiceNumber ?></td>
                                        <td><?php echo $StartDate ?></td>
                                        <td><?php echo $LastRun ?></td>
                                        <td><?php echo $NextRun ?></td>
                                        <td><?php echo $EndDate ?></td>
                                         <td>R<?php echo $RecurringTotal ?></td>
                                        <td><?php echo $Frequency ?></td>
                                        <td><?php echo $ShowStatus ?></td>
                                        
                                    </tr>
                                    <?php } ?>
                                    
                                    
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
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
            responsive: true
        });
    });
    </script>

</body>

</html>
