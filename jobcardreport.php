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
	$JobcardStatus = $_REQUEST["status"];
	$FromDate = $_REQUEST["from"];
	$ToDate = $_REQUEST["to"];
	$FilterClient = $_REQUEST["client"];
	
	if ($FilterClient != "")
	{
		$FilterClientArray = explode(":::", $FilterClient);
		$FilterClient = $FilterClientArray[0];
		$FilterSite = $FilterClientArray[1];	
	}
	
	$Jobcards = GetAllJobcardsReport($JobcardStatus, $FromDate, $ToDate, $FilterClient, $FilterSite);
	$Clients = GetAllClients();
	
	//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
	if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
	else
	{
		$Access = CheckPageAccess('Jobcard Report');	
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

function UpdateJobcardStatus()
{
	var NewStatus = document.getElementById("newstatus").value;
	var FromDate = document.getElementById("datepicker").value;
	var ToDate = document.getElementById("datepicker2").value;
	var ClientFilter = document.getElementById("filterclient").value;
	
	document.location = "jobcardreport.php?status=" + NewStatus + "&from=" + FromDate + "&to=" + ToDate + "&client=" + ClientFilter;
}
</script>
</head>

<body>

    <div id="wrapper">

        <?php include("navigation.php") ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Jobcard Report <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> Below you will find all customers jobcards</div>    
            <div class="row">
                <div class="col-lg-12">
                
                     <?php if ($Access == 1) { ?>               
                   <div class="col-lg-12"> 
                             
                             <h4>Job Card Report</h4>
                             
                             <h5 class="pull-right form-inline">Status
                             <select id="newstatus" class="form-control form-inline">
                                                	<option value="" selected>Please select filter status</option>
                                                	 <?php if ($JobcardStatus == 0 && $JobcardStatus != "") { $Selected = 'selected'; } else { $Selected = ''; } ?>
                                                    <option value="0" <?php echo $Selected ?>>Incomplete</option>
                                                     <?php if ($JobcardStatus == 1) { $Selected = 'selected'; } else { $Selected = ''; } ?>
                                                    <option value="1" <?php echo $Selected ?>>Waiting Invoice</option>
                                                    <?php if ($JobcardStatus == 2) { $Selected = 'selected'; } else { $Selected = ''; } ?>
                                                    <option value="2" <?php echo $Selected ?>>Completed</option>
                                                   
                                                </select>
                                                
                            Client/Site
                             <select id="filterclient" class="form-control form-inline" style="max-width: 150px">
                                                	<option value="" selected>Client/Site</option>
                                                    <?php
													while ($Val = mysqli_fetch_array($Clients))
													{
														$CustomerID = $Val["CustomerID"];
														$CompanyName = $Val["CompanyName"];
														
														if ($CompanyName == "")
														{
															$CompanyName = $Val["FirstName"] . " " . $Val["Surname"];	
														}
														
														if ($FilterClient == $CustomerID)
														{
															$Selected = 'selected';
														}
														else
														{
															$Selected = '';	
														}
													?>
                                                	
                                                    <option value="<?php echo $CustomerID ?>:::0" <?php echo $Selected ?>><?php echo $CompanyName ?> - Head Office</option>
                                                    <?php
													//NOW ALSO CHECK IF THIS CLIENT HAS ANY SITES
													$ClientSites = GetAllClientSites($CustomerID);
													
													while ($ValSites = mysqli_fetch_array($ClientSites))
													{
														$SiteID = $ValSites["SiteID"];
														$SiteName = $ValSites["SiteName"];
														
														if ($FilterSite == $SiteID)
														{
															$Selected = 'selected';
														}
														else
														{
															$Selected = '';	
														}
													?>
                                                    <option value="<?php echo $CustomerID . ":::" . $SiteID ?>" <?php echo $Selected ?>>
													<?php echo $CompanyName ?> - <?php echo $SiteName ?>
                                                    </option>
                                                    <?php 
													}} 
													?>
                                                   
                                                </select>
                                                
                             
                            Created between <input type="text" class="form-control datepicker form-inline" id="datepicker" name="datepicker" placeholder="From"  data-date-format="yyyy-mm-dd" value=""> to <input type="text" class="form-control datepicker form-inline" id="datepicker2" name="datepicker2" placeholder="To"  data-date-format="yyyy-mm-dd" value="">
                                                
                                                <button class="btn btn-default form-inline" onClick="javascript: UpdateJobcardStatus();">Update Filter</button>
                            &nbsp;&nbsp;<a href="jobcardreportpdf.php?status=<?php echo $JobcardStatus ?>&from=<?php echo $FromDate ?>&to=<?php echo $ToDate ?>&client=<?php echo $FilterClient ?>&site=<?php echo $FilterSite ?>" class="btn btn-sm btn-default pull-right" style="margin-bottom: 10px" target="_blank"><i class="fa fa-print"></i> Print Report</a>&nbsp;&nbsp;<a href="jobcardreportcsv.php?status=<?php echo $JobcardStatus ?>&from=<?php echo $FromDate ?>&to=<?php echo $ToDate ?>&client=<?php echo $FilterClient ?>&site=<?php echo $FilterSite ?>" class="btn btn-sm btn-default pull-right" style="margin-bottom: 10px; margin-right: 5px" target="_blank"><i class="fa fa-file-excel-o"></i> Export Report</a>
                            </h5>
                            
                            
                            <div class="col-md-12" style="padding-bottom: 10px; margin-left: -15px"></div>
                            <table width="100%" class="table table-striped table-bordered table-hover"  id="dataTables-example"  style="font-size: 12px">
                                <thead>
                                    <tr>
                                       
                                        <th style="display: none">Job card ID</th>
                                        <th>System Job card #</th>
                                        <th>Manual Job card #</th>
                                        <th>Customer</th>
                                        <th>Site</th>
                                        <th>Date Added</th>
                                        <th>Added By</th>
                                        
                                        <th>Scheduled</th>
                                        <th>Scheduled For</th>
                                        <th>Status</th>
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
										$SiteID = $Val["SiteID"];
										
										if ($SiteID == 0)
										{
											$SiteName = "Head Office";	
										}
										else
										{
											$SiteName = GetSiteName($SiteID);	
										}
										
										
										//JOBCARD FIELDS
										$JobCardID = $Val["JobcardID"];
										$JobcardNumber = "JBC" . $JobCardID;
										$AssignedTo = $Val["AssignedTo"];
										$AddedBy = $Val["AddedByName"];
										$DateCreated = $Val["DateCreated"];
										$DateScheduled = $Val["DateScheduled"];
										$ManualJobcardNumber = $Val["ManualJobcardNumber"];
										
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
										
										switch ($JobcardStatus)
										{
											case 0: $ShowStatus = 'Incomplete'; break;	
											case 1: $ShowStatus = 'Waiting Invoice'; break;	
											case 2: $ShowStatus = 'Completed'; break;	
										}
										
										
									?>
                                    <tr class="odd gradeX">
                                        
                                        <td style="display: none"><?php echo $JobCardID ?></td>
                                        <td><?php echo $JobcardNumber ?></td>
                                        <td><?php echo $ManualJobcardNumber ?></td>
                                        <td><?php echo $ShowClient ?></td>
                                        <td><?php echo $SiteName ?></td>
                                        <td><?php echo $DateCreated ?></td>
                                        <td><?php echo $AddedBy ?></td>
                                        
                                        <td><?php echo $DateScheduled ?></td>
                                        <td><?php echo $ShowEmployee ?></td>
                                        <td><?php echo $ShowStatus ?></td>
                                        <td class="center"><a href="editjobcard.php?j=<?php echo $JobCardID ?>" class="btn btn-sm btn-default">View Jobcard</a></td>
                                        
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
	document.getElementById("jobcardreport").className = 'active';
    </script>
    
    <script>
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
