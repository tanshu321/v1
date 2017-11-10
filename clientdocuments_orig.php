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
		
		//UPLOAD HERE
		$Upload = $_REQUEST["u"];
			if ($Upload == "y")
			{
				//OK FORM SUBMITTED, LETS CHECK IF THERES A LOGO TO UPLOAD
				$FileType = $_REQUEST["type"];
				
				if ($FileType == 'doc' || $FileType == 'DOC' || $FileType == 'docx' || $FileType == 'DOCX')
				{
					$ThisFileType = 'DOC';	
				}
				else if ($FileType == 'xls' || $FileType == 'XLS')
				{
					$ThisFileType = 'EXCEL';	
				}
				else if ($FileType == 'ppt' || $FileType == 'PPT')
				{
					$ThisFileType = 'POWERPOINT';	
				}
				else if ($FileType == 'jpg' || $FileType == 'JPG' || $FileType == 'png' || $FileType == 'PNG')
				{
					$ThisFileType = 'IMAGE';	
				}
				else if ($FileType == 'pdf' || $FileType == 'PDF')
				{
					$ThisFileType = 'PDF';	
				}
				
				
				$SafeFile = $_FILES['documentfile']['name']; 
				$ThisDescript = $_POST["documentname"];
				
				
				
				if(is_uploaded_file(($_FILES['documentfile']['tmp_name'])))
				{
						$imagename = $_FILES['documentfile']['name'];
			
						if ($imagename != "")
						{
							$source = $_FILES['documentfile']['tmp_name'];
							$NewFileName = time() . "_" . str_replace(" ","",$imagename);
							$target = "clientdocs/" . $NewFileName;  
							move_uploaded_file($source, $target);
							
							$AddClientDoc = AddClientDoc($CustomerID, $ThisFileType, $NewFileName, $ThisDescript);
							
							
							
							echo "<script type='text/javascript'>document.location = 'clientdocuments.php?c=" . $CustomerID . "';</script>"; 
						}
				 }
				
		
			
			
		}
		
		
		$ClientDocuments = GetClientDocuments($CustomerID);
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
function UploadFile()
{
	var DocumentName = document.getElementById("documentname").value;
	var DocumentFile = document.getElementById("documentfile").value;
	
	var FileType = DocumentFile.split('.').pop();
				
	if (DocumentName != "" && DocumentFile != "")
	{
		if (FileType == 'doc' || FileType == 'DOC' || FileType == 'pdf' || FileType == 'PDF' || FileType == 'DOCX' || FileType == 'docx' || FileType == 'xls' || FileType == 'XLS' || FileType == 'ppt' || FileType == 'PPT' || FileType == 'jpg' || FileType == 'JPG' || FileType == 'png' || FileType == 'PNG')
		{
			document.getElementById("documentform").action = "clientdocuments.php?u=y&c=<?php echo $CustomerID ?>&type=" + FileType;
			document.getElementById("documentform").submit();	
		}
		else
		{
			bootbox.alert("The file you are uploading is not supported");		
		}
	}
	else
	{
		bootbox.alert("Please enter a document name as well as select the file to upload");	
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
                    <h1 class="page-header">Customer Details <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           <h5><?php echo $Name ?> <?php echo $Surname ?> <?php echo $TopCompanyName ?> <div class="pull-right"><?php echo $ShowStatus ?></div></h5>
                          
                           
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li ><a href="clientinfo.php?c=<?php echo $CustomerID ?>">Summary</a>
                                </li>
                                <li><a href="clientprofile.php?c=<?php echo $CustomerID ?>">Profile</a>
                                </li>
                                <li><a href="clientcontacts.php?c=<?php echo $CustomerID ?>">Contacts</a>
                                </li>
                                <li><a href="clientservices.php?c=<?php echo $CustomerID ?>">Services</a>
                                </li>
                                <li><a href="clientinvoices.php?c=<?php echo $CustomerID ?>">Invoices</a>
                                </li>
                                <li><a href="clientquotes.php?c=<?php echo $CustomerID ?>">Quotes</a>
                                </li>
                                <li><a href="clienttransactions.php?c=<?php echo $CustomerID ?>">Transactions</a>
                                </li>
                                <li><a href="clientstatement.php?c=<?php echo $CustomerID ?>">Statement</a>
                                </li>
                                <li  class="active"><a href="clientdocuments.php?c=<?php echo $CustomerID ?>">Documents</a>
                                </li>
                                <li><a href="clientnotes.php?c=<?php echo $CustomerID ?>">Notes</a>
                                </li>
                                <li><a href="cientsites.php?c=<?php echo $CustomerID ?>">Sites</a>
                                </li>
                               <li><a href="clientlogs.php?c=<?php echo $CustomerID ?>">Logs</a>
                                </li>
                               
                               
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="home" style="padding: 10px; ">
                                    <!-- Start Inside Tab -->
                                    <!-- Start Upload Table -->
                                    <div class="col-lg-3">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                               <strong><i class="fa fa-upload fa-fw"></i> Upload New Document</strong>
                                          </div>
                                            <!-- /.panel-heading -->
                                            <div class="panel-body"><!-- /.table-responsive -->
                                            	<form enctype="multipart/form-data" method="post" name="documentform" id="documentform">
                                                <div class="form-group row col-md-12">
                                                  <label for="city" class="col-sm-12 col-form-label" style="padding-top: 5px">Document Name *</label>
                                                  <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="documentname" name="documentname" placeholder="Document Name" value="">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-12">
                                                  <label for="state" class="col-sm-12 col-form-label" style="padding-top: 5px">Document File *</label>
                                                  <div class="col-sm-12">
                                                    <input type="file" class="form-control" id="documentfile" name="documentfile" placeholder="Document File" value="">
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row col-md-12" style="padding-top: 10px">
                                                  <button class="btn btn-info pull-right col-md-12" onClick="javascript: UploadFile();">Upload</button>
                                                </div>
                                                </form>
                                          </div>
                                            <!-- /.panel-body -->
                                        </div>
                                        <!-- /.panel -->
                                    </div>
                                    <!-- End Table -->
                                    <!-- First Panel Table -->
                                    <div class="col-lg-9">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                               <strong><i class="fa fa-database fa-fw"></i> Customer Documents</strong>
                                          </div>
                                            <!-- /.panel-heading -->
                                            <div class="panel-body">
                                                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                                    <thead>
                                                        <tr>
                                                           
                                                            <th>Document Name</th>
                                                            <th>Document Type</th>
                                                            <th>Date Added</th>
                                                            <th>Added By</th>
                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while ($Val = mysqli_fetch_array($ClientDocuments))
                                                        {
                                                            $DocumentID = $Val["DocumentID"];
                                                            $DocumentName = $Val["DocumentName"];
                                                            $DocumentType = $Val["DocumentType"];	
                                                            $DateAdded = $Val["DateAdded"];
															$AddedBy = $Val["AddedByName"];
                                                            
                                                        ?>
                                                        <tr class="odd gradeX" onClick="javascript: document.location = 'downloaddocument.php?d=<?php echo $DocumentID ?>&c=<?php echo $CustomerID ?>';">
                                                           
                                                            <td><?php echo $DocumentName ?></td>
                                                            <td><?php echo $DocumentType ?></td>
                                                            <td><?php echo $DateAdded ?></td>
                                                            <td><?php echo $AddedBy ?></td>
                                                            
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
                                    <!-- End first panel -->
                                    
                                    <!-- First Panel Table --><!-- End first panel -->
                                    
                                    <!-- First Panel Table --><!-- End first panel -->
                                    
                                    <!-- First Panel Table --><!-- End first panel -->
                                   
                                   
                                    <!-- End inside tab -->
                                </div>
                                
                            </div>
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
    </script>

</body>

</html>
