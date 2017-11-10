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
	
	$ProductGroups = GetAllProductGroups();
	$Suppliers = GetAllSuppliers();
	$Measurrements = GetAllMeasurements();
	$ProductCustomFields = GetProductCustomFields();
	$HasCustom = mysqli_num_rows($ProductCustomFields);
	
	$ProductID = $_REQUEST["p"];
	$ProductInfo = GetProductInfo($ProductID);
	
	while ($Val = mysqli_fetch_array($ProductInfo))
	{
		$ProductName = $Val["ProductName"];
		$ThisProductGroupID = $Val["ProductGroupID"];
		$ThisProductSubGroupID = $Val["ProductSubGroupID"];
		$IsStockItem = $Val["IsStockItem"];
		$ThisSupplierID = $Val["SupplierID"];
		$TaxableItem = $Val["TaxableItem"];
		$MinimumStock = $Val["MinimumStock"];
		$ProductCode = $Val["ProductCode"];
		$ProductDescription = $Val["ProductDescription"];
		$MinimumOrder = $Val["MinimumOrder"];
		$ShowInCatalog = $Val["ShowInCatalog"];
		$WarrantyMonths = $Val["WarrantyMonths"];
		$ProductSerialNumber = $Val["ProductSerialNumber"];
		$ProductStatus = $Val["ProductStatus"];
		
	}
	
	$Upload = $_REQUEST["u"];
	if ($Upload == "y")
	{
		//OK FORM SUBMITTED, LETS CHECK IF THERES A LOGO TO UPLOAD
				
				$SafeFile = $_FILES['imagefile']['name']; 
				
				
				
				
				if(is_uploaded_file(($_FILES['imagefile']['tmp_name'])))
				{
						$imagename = $_FILES['imagefile']['name'];
			
						if ($imagename != "")
						{
							$source = $_FILES['imagefile']['tmp_name'];
							$NewFileName = time() . "_" . str_replace(" ","",$imagename);
							$target = "productimages/" . $NewFileName;  
							move_uploaded_file($source, $target);
							
							$AddProductImage = AddProductImage($ProductID, $ThisFileType, $NewFileName);
							
							
							
							echo "<script type='text/javascript'>document.location = 'productimages.php?p=" . $ProductID . "';</script>"; 
						}
			}	
	}
	
	$ProductImages = GetProductImages($ProductID);
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
function UploadImage()
{
	var ImageFile = document.getElementById("imagefile").value;
	var FileType = ImageFile.split('.').pop();
				
	if (ImageFile != "")
	{
		if (FileType == 'jpg' || FileType == 'JPG' || FileType == 'png' || FileType == 'PNG')
		{
			document.getElementById("imageform").action = "productimages.php?u=y&p=<?php echo $ProductID ?>&type=" + FileType;
			document.getElementById("imageform").submit();	
		}
		else
		{
			bootbox.alert("The file you are uploading is not supported");		
		}
	}
	else
	{
		bootbox.alert("Please select an image to upload");	
	}
}

function RemoveImage(ProductImageID)
{
	bootbox.confirm("Are you sure you would like to delete this image?", function(result)
	{ 
		if (result === true)
		{
			var DoDel = agent.call('','RemoveImage','', ProductImageID);
			if (DoDel == "OK")
			{
				document.location.reload();
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
                    <h1 class="page-header">Edit Product Images <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                
                <!-- /.col-lg-12 -->
            </div>
            <div class="alert alert-info">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to edit your product images.To upload a new image simply use the input on the top right, select your image and click on the Upload Image button. The ideal image size is 800px wide by 600px high
                            </div>     
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                            <ul class="nav nav-tabs" style="margin-bottom: 20px">
                                <li ><a href="editproduct.php?p=<?php echo $ProductID ?>"><i class="fa fa-caret-right"></i> Base Info</a>
                                </li>
                                
                                <li><a href="editproductpricing.php?p=<?php echo $ProductID ?>"><i class="fa fa-caret-right"></i> Pricing</a>
                                </li>
                                <li class="active"><a href="productimages.php?p=<?php echo $ProductID ?>"><i class="fa fa-caret-right"></i> Images</a>
                                </li>
                                <li><a href="productstock.php?p=<?php echo $ProductID ?>"><i class="fa fa-caret-right"></i> Stock</a>
                                </li>
                                 <li class="pull-right"><a href="products.php"><i class="fa fa-caret-left"></i> Back to All Products</a>
                                </li>
                                
                                
                               
                               
                            </ul>
                            
                                    
                             <div class="col-lg-12" style="padding-bottom: 20px">
                                <h4>Product Images <span class="pull-right"><form enctype="multipart/form-data" id="imageform" method="post" action=""><label>Upload image (png, jpg)</label><input type="file" class="form-control" name="imagefile" id="imagefile"> <input type="button" class="btn btn-default  form-inline pull-right" value="Upload Image" style="margin-top: 10px" onClick="javascript: UploadImage();"></form></span></h4>
                                
                               
                            </div>
                			
                            <?php while ($Val = mysqli_fetch_array($ProductImages))
							{
								$ProductImageID = $Val["ProductImageID"];
								$ProductImage = $Val["ProductImage"];	
								
							?>
                            <div class="col-lg-3 col-md-4 col-xs-6 thumb" align="center" style="padding-bottom: 20px">
                                    <img class="img-responsive img-rounded" src="productimages/<?php echo $ProductImage ?>" alt="" style="max-height: 300px; overflow: hidden; padding-bottom: 10px;">
                                    <input type="button" class="btn btn-danger" value="Remove Image" onClick="javascript: RemoveImage(<?php echo $ProductImageID ?>);">
                            </div>
                            <?php } ?>
                            
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
            responsive: true
        });
    });
	
	//MENU STUFF FOR PAGE
	document.getElementById("setupmenu").className = 'active';
	document.getElementById("setupproductmenu").className = 'active';
    </script>
    
    <script type="text/javascript">
	function UpdateProduct()
	{
		var Name = document.getElementById("productname").value;
		var Description = document.getElementById("productdescription").value;
		var Code = document.getElementById("productcode").value;
		var SerialNumber = document.getElementById("serialnumber").value;
		var Taxable = document.getElementById("taxable").value;
		var MinOrder = document.getElementById("minorder").value;
		var Group = document.getElementById("productgroup").value;
		var SubGroup = document.getElementById("productsubgroup").value;
		var Supplier = document.getElementById("supplier").value;
		var Warranty = document.getElementById("warranty").value;
		var StockItem = document.getElementById("stockitem").value;
		var MinStock = document.getElementById("minstock").value;
		var Catalogue = document.getElementById("catalogue").value;
		var Status = document.getElementById("status").value;
		
		if (Name != "" && Description != "" && Code != "" && Taxable != "" && parseInt(MinOrder) >= 0 && Group != "" && Supplier != "" && Warranty != "" && StockItem != "" && parseInt(MinStock) >= 0 && Catalogue != "" && Status != "")
		{
			//WE HAVE ENOUGH NOW TO ADD THE BASE INFO, BUT WE NEED TO CHECK REQUIRED CUSTOM FIELDS
			var CustomIDs = '<?php echo ltrim($AllCustomIDs, ":::"); ?>';
			var CustomError = "";
			
			if (CustomIDs != "")
			{
				var CustomIDArray = CustomIDs.split(":::");
				for (i = 0; i < CustomIDArray.length; i++) 
				{
					var ThisCustomID = CustomIDArray[i];
					var CustomType = agent.call('','GetProductCustomDetails','', ThisCustomID);
					
					var ThisType = CustomType[0];
					var ThisRequired = CustomType[1];
					
					if ((ThisType == "text" || ThisType == "textarea" || ThisType == "select") && ThisRequired == 1)
					{
						var HasData = document.getElementById("custom" + ThisCustomID).value;
						if (HasData == "")
						{
							CustomError = 1;	
						}
					}
					
					if ((ThisType == "checkbox" || ThisType == "radio") && ThisRequired == 1)
					{
						var Options = agent.call('','GetCustomFieldOptionsArray','', ThisCustomID);
						var HasSelected = 0;
						
						for (y = 0; i < Options.length; i++) 
						{
							var ThisOptionID = Options[y];
							var IsChecked = document.getElementById("customoption" + ThisOptionID).checked;
							if (IsChecked === true)
							{
								HasSelected = 1;	
							}
						}
						
						if (HasSelected == 0)
						{
							CustomError = 1;	
						}
					}
				}
				
				if (CustomError == 0)
				{
					//VALIDATION PASSED, ADD BASE PRODUCT FIRST
					var AddProductBase = agent.call('','UpdateBaseProduct','', Name, Description, Code, SerialNumber, Taxable, MinOrder, Group, SubGroup, Supplier, Warranty, StockItem, MinStock, Catalogue, Status, '<?php echo $ProductID ?>');
					if (AddProductBase > 0)
					{
						//CUSTOM FIELDS
						var CustomIDArray = CustomIDs.split(":::");
						for (i = 0; i < CustomIDArray.length; i++) 
						{
							var ThisCustomID = CustomIDArray[i];
							var CustomType = agent.call('','GetProductCustomDetails','', ThisCustomID);
							
							var ThisType = CustomType[0];
							
							
							if ((ThisType == "text" || ThisType == "textarea" || ThisType == "select"))
							{
								var HasData = document.getElementById("custom" + ThisCustomID).value;
								var SaveCustomEntry = agent.call('','UpdateProductCustomEntry','', ThisCustomID, 0, HasData, '<?php echo $ProductID ?>');
							}
							
							if ((ThisType == "checkbox" || ThisType == "radio"))
							{
								var TheseOptions = agent.call('','GetCustomFieldOptionsArray','', ThisCustomID);
								
								
								
								for (z = 0; z < TheseOptions.length; z++) 
								{
									
									var ThisOptionID = TheseOptions[z];
									var IsChecked = document.getElementById("customoption" + ThisOptionID).checked;
									var SaveCustomEntry = agent.call('','UpdateProductCustomEntry','', ThisCustomID, ThisOptionID, IsChecked, '<?php echo $ProductID ?>');
								}
								
							}
						}
						
						bootbox.alert("Product details updated successfully");
						
					}
					else
					{
						bootbox.alert(	AddProductBase);
					}
				}
				else
				{
					bootbox.alert("Please enter all fields marked with a * under the custom fields");
				}
					
			}
			else
			{
				//JUST ADD PRODUCT & CONTINUE
				var AddProductBase = agent.call('','UpdateBaseProduct','', Name, Description, Code, SerialNumber, Taxable, MinOrder, Group, SubGroup, Supplier, Warranty, StockItem, MinStock, Catalogue, Status, '<?php echo $ProductID ?>');
				if (AddProductBase > 0)
				{
					bootbox.alert("Product details updated successfully");
				}
				else
				{
					bootbox.alert(	AddProductBase);
				}
			}	
		}
		else
		{
			bootbox.alert("Please enter all the information marked with a * to save the product");
		}
	}
	</script>

</body>

</html>
