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
	
	CreateClientAccess($CustomerID, 'Accessed Customer Notes');
	
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
		
		
		
		
		$ClientNotes = GetClientNotes($CustomerID);
		
		//NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
		if ($_SESSION["MainClient"] == 1)
	{
		$Access = 1;	
	}
		else
		{
			$Access = CheckPageAccess('Notes');	
		}
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
function AddNote()
{
	bootbox.confirm({ 
	  
	  message: "<h4>Enter your customer note</h4><input type='text' class='form-control' id='note'><label style='padding-top: 10px'><input type='checkbox' id='sticky'> Stick Note</label>", 
	  callback: function(result)
	  { /* result is a boolean; true = OK, false = Cancel*/ 
	  	if (result === true)
		{
			var Note = document.getElementById("note").value;
			var Sticky = document.getElementById("sticky").checked;
			
			if (Sticky === true)
			{
				Sticky = 1;
			}
			else
			{
				Sticky = 0;	
			}
			
			if (Note != "")
			{
				var AddNote = agent.call('','AddClientNote','', Note, '<?php echo $CustomerID ?>', Sticky);
				if (AddNote == "OK")
				{
					//LOG EVENT
					var Log = agent.call('','CreateClientAccess', '', <?php echo $CustomerID ?>, 'Added Customer Note');
					
					bootbox.alert('Note added successfully', function() {
									document.location = 'clientnotes.php?c=<?php echo $CustomerID ?>';
					});
				}
				else
				{
					bootbox.alert(AddNote);	
				}
			}
			else
			{
				
			}	
		}
	  }
	})
}

function AddNote2()
{
	bootbox.prompt("Please enter the note", function(result)
	{ 
		var Note = result;
		
		if (Note != "" && Note != null)
		{
			var AddNote = agent.call('','AddClientNote','', Note, '<?php echo $CustomerID ?>');
			if (AddNote == "OK")
			{
				bootbox.alert('Note added successfully', function() {
								document.location = 'clientnotes.php?c=<?php echo $CustomerID ?>';
				});
			}
			else
			{
				bootbox.alert(AddNote);	
			}
		}
	});
}

function AddClientNote()
{
	var Note = document.getElementById("clientnote").value;
	
				
	if (Note != "")
	{
		var AddNote = agent.call('','AddClientNote','', Note, '<?php echo $CustomerID ?>');
		if (AddNote == "OK")
		{
			bootbox.alert('Note added successfully', function() {
							document.location = 'clientnotes.php?c=<?php echo $CustomerID ?>';
			});
		}
		else
		{
			bootbox.alert(AddNote);	
		}
	}
	else
	{
		bootbox.alert("Please enter the client note to add");	
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
                    <h1 class="page-header">Customer Details <?php echo $TopCompanyName ?> <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to add any notes for your client. To add a new note simply click on the Add Note button below.
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
                                <li ><a href="clientjobcards.php?c=<?php echo $CustomerID ?>">Job Cards</a>
                                </li>
                                <li  class="active"><a href="clientnotes.php?c=<?php echo $CustomerID ?>">Notes</a>
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
                   
                           
                           
                    
                        <!-- /.panel-heading -->
                        

                            <!-- Tab panes -->
                          <?php if ($Access == 1) { ?>         
                               
                                    <!-- End Table -->
                                    <!-- First Panel Table -->
                                    <div class="col-lg-12" style="padding-top: 10px">
                                    
                                     <h4>Customer Notes <a href="javascript: AddNote();" class="btn btn-sm btn-default pull-right" style="margin-bottom: 10px"><i class="fa fa-plus"></i> Add Note</a></h4>
                                      
                                          
                                                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" style="font-size: 12px">
                                                    <thead>
                                                        <tr>
                                                            
                                                            <th>Note</th>
                                                            <th>Sticky Note</th>
                                                            <th>Added</th>
                                                            <th>Added By</th>
                                                            
                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while ($Val = mysqli_fetch_array($ClientNotes))
                                                        {
                                                            $NoteID = $Val["NoteID"];
                                                            $Note = $Val["Note"];
                                                            $DateAdded = $Val["DateAdded"];	
															$AddedBy = $Val["AddedByName"];
															$StickyNote = $Val["StickyNote"];
															
															if ($StickyNote == 0)
															{
																$ShowStatus = '<i class="fa fa-close fa-fw" style="color: red"></i>';
															}
															else
															{
																$ShowStatus = '<i class="fa fa-check fa-fw" style="color: green"></i>';
															}
															
                                                           
                                                            
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            
                                                            <td><?php echo $Note ?></td>
                                                             <td><?php echo $ShowStatus ?></td>
                                                            <td><?php echo $DateAdded ?></td>
                                                             <td><?php echo $AddedBy ?></td>
                                                           
                                                            
                                                        </tr>
                                                        <?php } ?>
                                                        
                                                        
                                                    </tbody>
                                                </table>
                                                <!-- /.table-responsive -->
                                                
                                         
                                    
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
    <script src="js/bootbox.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
	
	document.getElementById("customermenu").className = 'active';
	document.getElementById("customermenucustomer").className = 'active';
    </script>

</body>

</html>
