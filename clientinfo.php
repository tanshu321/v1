<?php
include("includes/webfunctions.php");


//SECURITY
//SECURITY
$ThisClientID = $_SESSION["ClientID"];
$ThisUser = $_SESSION["ClientName"];
$ThisSecret = $_SESSION["SiteSecret"];

if ($ThisClientID > 0 && $ThisUser != "" && $ThisSecret == "E2A_crm_S5gdbh6nnj_usr_9898")
{
	$CustomerID = $_REQUEST["c"];
	
	CreateClientAccess($CustomerID, 'Accessed Customer Summary');
	
	$ClientInfo = GetSingleClient($CustomerID);
	$FoundClient = mysqli_num_rows($ClientInfo);
	
	if ($FoundClient != 0)
	{
		while ($Val = mysqli_fetch_array($ClientInfo))
		{
			$Name = $Val["FirstName"];
			$Surname = $Val["Surname"];
			$CompanyName = $Val["CompanyName"];
			
			if ($CompanyName != "")
			{
				$TopCompanyName = $CompanyName . " ( " . $Name . " " . $Surname . " )";		
			}
			
			$EmailAddress = $Val["EmailAddress"];
			$DateAdded = $Val["DateAdded"];
											
			$Status = $Val["Status"];
			
			$Address1 = $Val["Address1"];
			$Address2 = $Val["Address2"];
			$City = $Val["City"];
			$Region = $Val["Region"];
			$PostCode = $Val["PostCode"];
			$CountryName = $Val["CountryName"];
			$ContactNumber  = $Val["ContactNumber"];
			
			
		}
		
		
		if ($Status == 2)
		{
			//ACTIVE	
			$ShowStatus = '<span class="label label-success" style="font-size: 14px">Active</span>';
		}
		else
		{
			//INACTIVE	
			$ShowStatus = '<span class="label label-danger" style="font-size: 14px">Inactive</span>';
		}
		
		//INVOICING
		$NumPaid = CountInvoices(2, $CustomerID);
		$TotalPaid = TotalInvoicesPaid(2, $CustomerID);
		
		$NumDraft = CountInvoices(0, $CustomerID);
		$TotalDraft = TotalInvoices(0, $CustomerID);
		
		$NumUnpaid = CountInvoices(1, $CustomerID);
		$TotalUnpaid = TotalInvoices(1, $CustomerID);
		
		$NumCancelled = CountInvoices(3, $CustomerID);
		$TotalCancelled = TotalInvoices(3, $CustomerID);
		
		$NumRefunded = CountInvoices(4, $CustomerID);
		$TotalRefunded = TotalInvoices(4, $CustomerID);
		
		$NumCollections = CountInvoices(5, $CustomerID);
		$TotalCollections = TotalInvoices(5, $CustomerID);
		
		$CreditBalance = ($TotalCancelled + $TotalRefunded) - $TotalPaid;
		
		$StickyNotes = GetStickyNotes($CustomerID);
	}
	else
	{
		echo "<script type='text/javascript'>alert('Your session has expired, please login again'); parent.location = 'logout.php';</script>";
	}
	
	
	//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
	if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
	else
	{
		$Access = CheckPageAccess('Summary');	
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
                    <h1 class="page-header">Customer Details <?php echo $TopCompanyName ?> <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
             <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page shows you all the details you have collected for this customer
                            </div>     
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
              
                    
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="clientinfo.php?c=<?php echo $CustomerID ?>">Summary</a>
                                </li>
                                <li><a href="clientprofile.php?c=<?php echo $CustomerID ?>">Profile</a>
                                </li>
                                <li><a href="clientcontacts.php?c=<?php echo $CustomerID ?>">Contacts</a>
                                </li>
                                
                                <li><a href="clientinvoices.php?c=<?php echo $CustomerID ?>">Invoices</a>
                                </li>
                                <li><a href="clientquotes.php?c=<?php echo $CustomerID ?>">Quotes</a>
                                </li>
                                <li><a href="clienttransactions.php?c=<?php echo $CustomerID ?>">Transactions</a>
                                </li>
                                <li><a href="clientstatement.php?c=<?php echo $CustomerID ?>">Statement</a>
                                </li>
                                <li ><a href="clientdocuments.php?c=<?php echo $CustomerID ?>">Documents</a>
                                </li>
                                <li ><a href="clientjobcards.php?c=<?php echo $CustomerID ?>">Job Cards</a>
                                </li>
                                <li><a href="clientnotes.php?c=<?php echo $CustomerID ?>">Notes</a>
                                </li>
                                <li><a href="clientproducts.php?c=<?php echo $CustomerID ?>">Products</a>
                                </li>
                                <li><a href="clienttask.php?c=<?php echo $CustomerID ?>">Tasks</a>
                                </li>
                                <li><a href="clientfollowup.php?c=<?php echo $CustomerID ?>">Follow Ups</a>
                                </li>
                               <li><a href="cientsites.php?c=<?php echo $CustomerID ?>">Sites</a>
                                </li>
                               <li><a href="clientlogs.php?c=<?php echo $CustomerID ?>">Logs</a>
                                </li>
                                
                                <li class="pull-right"><a href="showclients.php"><i class="fa fa-caret-left"></i> Back to All Customers</a>
                                </li>
                               
                            </ul>

							<?php if ($Access == 1) { ?>  
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="home" style="padding: 10px; ">
                                  <?php while ($Val = mysqli_fetch_array($StickyNotes))
									{ 
										$Note = $Val["Note"];
									?>
									<div class="alert alert-warning">
												 <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
												  <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong>Sticky Note : <?php echo $Note ?>
												</div>   
									<?php } ?>
                                    <!-- Start Inside Tab -->
                                    <!-- First Panel Table -->
                                    <div class="col-lg-6">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <strong><i class="fa fa-user fa-fw"></i> Customer Information </strong>
                                                
                                            </div>
                                            <!-- /.panel-heading -->
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-condensed">
                                                        
                                                        <tbody>
                                                            <tr>
                                                                <td width="40%"><h6>First Name</h6></td>
                                                                <td><h6><?php echo $Name ?></h6></td>
                                                               
                                                            </tr>
                                                            <tr>
                                                                <td><h6>Surname</h6></td>
                                                                <td><h6><?php echo $Surname ?></h6></td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td><h6>Company Name</h6></td>
                                                                <td><h6><?php echo $CompanyName ?></h6></td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td><h6>Email Address</h6></td>
                                                                <td><h6><?php echo $EmailAddress ?></h6></td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td><h6>Address 1</h6></td>
                                                                <td><h6><?php echo $Address1 ?></h6></td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td><h6>Address 2</h6></td>
                                                                <td><h6><?php echo $Address2 ?></h6></td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td><h6>City</h6></td>
                                                                <td><h6><?php echo $City ?></h6></td>
                                                                
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td><h6>State/Region</h6></td>
                                                                <td><h6><?php echo $Region ?></h6></td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td><h6>Post Code</h6></td>
                                                                <td><h6><?php echo $PostCode ?></h6></td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td><h6>Country</h6></td>
                                                                <td><h6><?php echo $CountryName ?></h6></td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td><h6>Contact Number</h6></td>
                                                                <td><h6><?php echo $ContactNumber ?></h6></td>
                                                                
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- /.table-responsive -->
                                            </div>
                                            <!-- /.panel-body -->
                                        </div>
                                        <!-- /.panel -->
                                    </div>
                                    <!-- End first panel -->
                                    
                                    <!-- First Panel Table -->
                                    <div class="col-lg-6">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <strong><i class="fa fa-table fa-fw"></i> Invoices/Billing</strong></div>
                                            <!-- /.panel-heading -->
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-condensed">
                                                        
                                                        <tbody>
                                                            <tr>
                                                                <td width="40%"><h6>Paid</h6></td>
                                                                <td><h6><?php echo $NumPaid ?> (R<?php echo number_format($TotalPaid,2,".","") ?>)</h6></td>
                                                               
                                                            </tr>
                                                            <tr>
                                                                <td><h6>Draft</h6></td>
                                                                <td><h6><?php echo $NumDraft ?> (R<?php echo number_format($TotalDraft,2,".","") ?>)</h6></td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td><h6>Unpaid/Due</h6></td>
                                                                <td><h6><?php echo $NumUnpaid ?> (R<?php echo number_format($TotalUnpaid,2,".","") ?>)</h6></td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td><h6>Cancelled</h6></td>
                                                                <td><h6><?php echo $NumCancelled ?> (R<?php echo number_format($TotalCancelled,2,".","") ?>)</h6></td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td><h6>Refunded</h6></td>
                                                                <td><h6><?php echo $NumRefunded ?> (R<?php echo number_format($TotalRefunded,2,".","") ?>)</h6></td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td><h6>Collections</h6></td>
                                                                <td><h6><?php echo $NumCollections ?> (R<?php echo number_format($TotalCollections,2,".","") ?>)</h6></td>
                                                                
                                                            </tr>
                                                            <tr>
                                                                <td><h6><strong>Income</strong></h6></td>
                                                                <td><h6><strong>R<?php echo number_format($TotalPaid,2,".","") ?></strong></h6></td>
                                                                
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td><h6>Credit Balance</h6></td>
                                                                <td><h6>R<?php echo number_format($CreditBalance ,2,".","") ?></h6></td>
                                                                
                                                            </tr>
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- /.table-responsive -->
                                            </div>
                                            <!-- /.panel-body -->
                                        </div>
                                        <!-- /.panel -->
                                    </div>
                                    <!-- End first panel -->
                                    
                                   
                                   
                                   
                                    <!-- End inside tab -->
                                </div>
                                
                            
                    </div>
                    <!-- /.panel -->
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

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
    </script>
<script type="text/javascript">
	document.getElementById("customermenu").className = 'active';
	document.getElementById("customermenucustomer").className = 'active';
</script>
</body>

</html>
