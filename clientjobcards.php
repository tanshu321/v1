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
	$CustomerID = $_REQUEST["c"];
	
	CreateClientAccess($CustomerID, 'Accessed Customer Jobcards');
	
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
											
			$ThisStatus = $Val["Status"];
			
			$Address1 = $Val["Address1"];
			$Address2 = $Val["Address2"];
			$City = $Val["City"];
			$Region = $Val["Region"];
			$PostCode = $Val["PostCode"];
			$CountryName = $Val["CountryName"];
			$ContactNumber  = $Val["ContactNumber"];
			$ClientCountryID = $Val["CountryID"];
			
			$TaxExempt = $Val["TaxExempt"];
			$OverdueNotices = $Val["OverdueNotices"];
			$MarketingEmails = $Val["MarketingEmails"];
			$PaymentMethod = $Val["PaymentMethod"];
			$VatNumber = $Val["VatNumber"];
			$AdminNotes = $Val["AdminNotes"];
			
			$ThisResellerID = $Val["ResellerID"];
			
		}
		
		
		if ($ThisStatus == 2)
		{
			//ACTIVE	
			$ShowStatus = '<span class="label label-success" style="font-size: 14px">Active</span>';
		}
		else
		{
			//INACTIVE	
			$ShowStatus = '<span class="label label-danger" style="font-size: 14px">Inactive</span>';
		}
		
		$SearchText = $_REQUEST["s"];
		$Jobcards = GetClientJobcards($CustomerID, $SearchText);
		
	}
	else
	{
		echo "<script type='text/javascript'>alert('Your session has expired, please login again'); parent.location = 'logout.php';</script>";
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
function AddGroup()
{
	bootbox.prompt("Please enter the new group name", function(result)
	{ 
		var NewGroup = result;
		
		if (NewGroup != "" && NewGroup != null)
		{
			var AddProductGroup = agent.call('','AddProductGroup','', NewGroup);
		
			if (AddProductGroup == "OK")
			{
				document.location.reload();
			}
			else
			{
				bootobox.alert(AddProductGroup);
			}
		}
	});
}

function EditName(GroupName, ProductGroupID)
{
	bootbox.prompt({
	  title: "Please change the current group name below",
	  value: GroupName,
	  callback: function(result) 
	  {
		var NewGroup = result;
		
		if (NewGroup != "" && NewGroup != null)
		{
			var AddProductGroup = agent.call('','UpdateProductGroup','', NewGroup, ProductGroupID);
		
			if (AddProductGroup == "OK")
			{
				document.location.reload();
			}
			else
			{
				bootobox.alert(AddProductGroup);
			}
		}
	  }
	});
}

function ShowTextSearch()
{
	bootbox.prompt("Enter the text you would like to search for", function(result)
	{ 
		var TextSearch = result;
		if (TextSearch != "" && TextSearch != null)
		{
			document.location = 'clientjobcards.php?c=<?php echo $CustomerID ?>&s=' + TextSearch;	
		}
	});
}

</script>

<script type="text/javascript">
function DeleteJobcard(JobCardID)
{
	bootbox.confirm("Are you sure you would like to delete this jobcard? This action cannot be undone!", function(result)
	{ 
		if (result === true)
		{
			var DoDelJob = agent.call('','DeleteJobcard','', JobCardID);
			if (DoDelJob == "OK")
			{
				document.location.reload();
			}
			else
			{
				bootbox.alert(DoDelJob);	
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
                    <h1 class="page-header">Customer Details <?php echo $TopCompanyName ?> <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                
                <!-- /.col-lg-12 -->
            </div>
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to manage all the client documents. To add a new document simply click on the Add Document button below.
                            </div>     
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    
					 <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li ><a href="clientinfo.php?c=<?php echo $CustomerID ?>">Summary</a>
                                </li>
                                <li ><a href="clientprofile.php?c=<?php echo $CustomerID ?>">Profile</a>
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
                                <li  class="active"><a href="clientjobcards.php?c=<?php echo $CustomerID ?>">Job Cards</a>
                                </li>
                                <li ><a href="clientnotes.php?c=<?php echo $CustomerID ?>">Notes</a>
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
                  
                        
                        
                                    
                             <div class="col-lg-12" style="padding-top: 10px"> 
                             
                             <h4>Customer Jobcards <a href="addjobcard.php?c=<?php echo $CustomerID ?>" class="btn btn-default pull-right" style="margin-bottom: 10px; " ><i class="fa fa-plus"></i> Add Jobcard</a> <a href="javascript: ShowTextSearch(); " class="btn btn-default pull-right" style="margin-bottom: 10px; margin-right: 5px" ><i class="fa fa-plus"></i> Text Search</a></h4>
                             
                             
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" style="font-size: 12px">
                                <thead>
                                    <tr>
                                       
                                        
                                        <th style="display: none">Job Card ID</th>
                                        <th>Site</th>
                                        <th>System Job Card #</th>
                                        <th>Manual Job Card #</th>
                                        <th>Invoice Number</th>
                                        
                                        <th>Status</th>
                                        <th>Date Added</th>
                                        <th>Added By</th>
                                        
                                        <th>Scheduled</th>
                                        <th>Scheduled For</th>
                                        <th>View</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php while ($Val = mysqli_fetch_array($Jobcards))
									{
										
										$CustomerID = $Val["CustomerID"];
										$Name = $Val["FirstName"];
										$Surname = $Val["Surname"];	
										$CompanyName = $Val["CompanyName"];
										
										//JOBCARD FIELDS
										$JobCardID = $Val["JobcardID"];
										$JobcardNumber = "JBC" . $JobCardID;
										$AssignedTo = $Val["AssignedTo"];
										$AddedBy = $Val["AddedByName"];
										$DateCreated = $Val["DateCreated"];
										$DateScheduled = $Val["DateScheduled"];
										$InvoiceID = $Val["InvoiceID"];
										$ManualJobcardNumber = $Val["ManualJobcardNumber"];
										$SiteID = $Val["SiteID"];
										
										if ($SiteID == 0)
										{
											$Site = "Head Office";	
										}
										else
										{
											$Site = GetSiteName($SiteID);	
										}
										
										
										if ($InvoiceID != "")
										{
											$InvoiceNumber = GetJobcardInvoiceNumber($InvoiceID);
										}
										else
										{
											$InvoiceNumber = "None";	
										}
										
										$AssignedTech = GetEmployee($AssignedTo);
										while ($ValEmp = mysqli_fetch_array($AssignedTech))
										{
											$EmpName = $ValEmp["Name"];	
											$EmpSurname = $ValEmp["Surname"];	
											
											$ShowEmployee = $EmpName . " " . $EmpSurname;
										}
										
										
										if ($CompanyName != "")
										{
											$ShowClient = $CompanyName;	
										}
										else
										{
											$ShowClient = $Name . "  " . $Surname;	
										}
										
										$JobcardStatus = $Val["JobcardStatus"];
										
										if ($JobcardStatus == 0)
										{
											$ShowStatus = "Waiting Tech Report";
										}
										else if ($JobcardStatus == 1)
										{
											$ShowStatus = "Waiting Invoice";	
										}
										else
										{
											$ShowStatus = "Complete";	
										}
										
										
									?>
                                    <tr class="odd gradeX">
                                        
                                        
                                        <td style="display: none"><?php echo $JobCardID ?></td>
                                        <td><?php echo $Site ?></td>
                                        <td><?php echo $JobcardNumber ?></td>
                                        <td><?php echo $ManualJobcardNumber ?></td>
                                        <td><?php echo $InvoiceNumber ?></td>
                                        
                                        <td><?php echo $ShowStatus ?></td>
                                        <td><?php echo $DateCreated ?></td>
                                        <td><?php echo $AddedBy ?></td>
                                        
                                        <td><?php echo $DateScheduled ?></td>
                                        <td><?php echo $ShowEmployee ?></td>
                                        <td class="center"><a href="editjobcard.php?j=<?php echo $JobCardID ?>&c=<?php echo $CustomerID ?>" class="btn btn-sm btn-default">View</a><?php if ($_SESSION["MainClient"] == 1) { ?>&nbsp;&nbsp;<a href="javascript: DeleteJobcard(<?php echo $JobCardID ?>)" class="btn btn-sm btn-danger">Delete</a><?php } ?> <?php if ($InvoiceID != "") { ?> <a href="showcustomerinvoice.php?i=<?php echo $InvoiceID ?>&c=<?php echo $CustomerID ?>" class="btn btn-sm btn-warning">Open Invoice</a><?php } ?></td>
                                        
                                    </tr>
                                    <?php } ?>
                                    
                                    
                                </tbody>
                            </table>
                            </div>
                            <!-- /.table-responsive -->
                            
                       
                   
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
        $('#dataTables-example').DataTable({
            responsive: true,
			"order": [[ 0, "desc" ]]
        });
    });
	
	//MENU STUFF FOR PAGE
	
	document.getElementById("customermenu").className = 'active';
	document.getElementById("customermenucustomer").className = 'active';
</script>
</body>

</html>
