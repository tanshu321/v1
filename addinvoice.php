<?php
session_start();
if ($_SESSION["Remember"] == "true")
{
	$ThisUserName = $_SESSION["AdminEmail"];
	$year = time() + 31536000;
	setcookie('remember_me_crm_admin', $ThisUserName, $year);	
}
include("includes/webfunctions.php");
include('includes/agent.php');
$agent->init();

//SECURITY
$ThisClientID = $_SESSION["ClientID"];
$ThisUser = $_SESSION["ClientName"];
$ThisSecret = $_SESSION["SiteSecret"];

if ($ThisClientID > 0 && $ThisUser != "" && $ThisSecret == "E2A_crm_S5gdbh6nnj_usr_9898")
{
	
	$Invoices = GetAllInvoices();
	$NumInvoices = mysqli_num_rows($Invoices);
	
	$NextInvoice = $NumInvoices + 1;
	if ($NextInvoice < 10)
	{
		$InvoiceReference = "INV" . $NextInvoice;
	}
	else if ($NextInvoice < 100)
	{
		$InvoiceReference = "INV00000" . $NextInvoice;
	}
	else if ($NextInvoice < 1000)
	{
		$InvoiceReference = "INV0000" . $NextInvoice;
	}
	else if ($NextInvoice < 10000)
	{
		$InvoiceReference = "INV000" . $NextInvoice;
	}
	else if ($NextInvoice < 100000)
	{
		$InvoiceReference = "INV00" . $NextInvoice;
	}
	
	$ProductGroups = GetProductGroups();
	
	$ThisCustomerID = $_REQUEST["c"];
	
}
else
{
	echo "<script type='text/javascript'>alert('Your session has expired, please login again'); parent.location = 'logout.php';</script>";
}

$CurrentRow = 1;
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

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<script type="text/javascript">
var RowCounter = 1;
var VATOn = 1;

function AddTableRow(CurrentRow)
{
	var NextRow = parseInt(CurrentRow) + 1;
	
	// Find a <table> element with id="myTable":
	var table = document.getElementById("invoicetable");
	
	// Create an empty <tr> element and add it to the 1st position of the table:
	var row = table.insertRow(NextRow);
	
	// Insert new cells (<td> elements) at the 1st and 2nd position of the "new" <tr> element:
	var cell1 = row.insertCell(0);
	var cell2 = row.insertCell(1);
	var cell3 = row.insertCell(2);
	var cell4 = row.insertCell(3);
	var cell5 = row.insertCell(4);
	var cell6 = row.insertCell(5);
	
	
	
	cell1.innerHTML = '<select class="form-control" id="item' + NextRow + '" onChange="javascript: GetProductDetails(' + NextRow + ')"></select>';
	
	//THEN POPULATE IT
	var ThisBox = "item" + CurrentRow;
	var NewBox = "item" + NextRow;
	var $options = $("#" + ThisBox + " > option").clone();
	$('#' + NewBox).append($options);
	
	cell2.innerHTML = '<input type="text" class="form-control" id="description' + NextRow + '">';	
	
	cell3.innerHTML = '<select class="form-control" id="quantity' + NextRow + '" disabled  onChange="javascript: ReworkPrice(' + NextRow + ')"></select>';	
	
	//THEN POPULATE IT
	var ThisBox = "quantity" + CurrentRow;
	var NewBox = "quantity" + NextRow;
	var $options2 = $("#" + ThisBox + " > option").clone();
	$('#' + NewBox).append($options2);
	
	cell4.innerHTML = '<input type="text" class="form-control" id="price' + NextRow + '" onKeyUp="javascript: ReworkPrice(' + NextRow + ')">';
	cell5.innerHTML = '<span id="total' + NextRow + '"></span>';
	
	
	cell6.innerHTML = '<i class="fa fa-plus-square" style="color: #060; font-size: 18px" onClick="javascript: AddTableRow(' + NextRow + ')"></i> <i class="fa fa-minus-square" style="color: #F00; font-size: 18px" onClick="javascript: RemoveRow(' + NextRow + ')"></i>';
	
	
	RowCounter = RowCounter + 1;
}

function RemoveRow(RowNumber)
{
	 document.getElementById("invoicetable").deleteRow(RowNumber);
}

function GetProductDetails(CurrentRow)
{
	var ChosenProduct = document.getElementById("item" + CurrentRow).value;
	if (ChosenProduct != "" && ChosenProduct != "Other")
	{
		var ProductDetails = agent.call('','GetProductItem','', ChosenProduct);
		var ProductDetailsArray = ProductDetails.split(":::");
		var ThisDescription = ProductDetailsArray[0];
		var ThisPrice = ProductDetailsArray[1];
		
		document.getElementById("description" + CurrentRow).value = ThisDescription;
		document.getElementById("price" + CurrentRow).value = ThisPrice;
		document.getElementById("quantity" + CurrentRow).disabled = false;
		
		
	}
	else
	{
		if (ChosenProduct == "Other")
		{
			document.getElementById("quantity" + CurrentRow).disabled = false;
			document.getElementById("quantity" + CurrentRow).selectedIndex = 0;
			document.getElementById("description" + CurrentRow).value = '';
			document.getElementById("price" + CurrentRow).value = '';
		}
		else
		{
			document.getElementById("quantity" + CurrentRow).disabled = true;
			document.getElementById("quantity" + CurrentRow).selectedIndex = 0;
		}
	}
	
	ReworkPrice(CurrentRow);
	
}

function ReworkPrice(CurrentRow)
{
	
	var QuantitySelected = document.getElementById("quantity" + CurrentRow).value;
	var CurrentPrice = document.getElementById("price" + CurrentRow).value;
	var TotalPrice = parseInt(QuantitySelected) * parseFloat(CurrentPrice);
	if (TotalPrice > 0)
	{
		document.getElementById("total" + CurrentRow).innerHTML = "R" + TotalPrice.toFixed(2);
	}
	
	ShowTotal();
}

function ShowTotal()
{
	//LOOP THE ROW COUNTER, ALSO REMEMBER THEY COULD OF REMOVED ROWS IN RANDOM
	var InvoiceTotal = 0;
	var TotalAlone = 0;
	var Discount = document.getElementById("discount").value;
	
	for (var X = 1; X <= RowCounter; X++)
	{
		if ($('#total' + X).length > 0) 
		{
		 	var ThisTotalLine = document.getElementById("total" + X).innerHTML;
			TotalAlone = parseFloat(ThisTotalLine.substring(1));
			
			if (TotalAlone >= 0)
			{
				InvoiceTotal = parseFloat(InvoiceTotal) + parseFloat(TotalAlone);
				
				
			}
			
			
		}
	}
	
	//NOW SHOW THE TOTAL
	var TotalDiscount = 0;
	document.getElementById("invoicesubtotal").innerHTML = "R" + InvoiceTotal.toFixed(2);
	
	if (parseFloat(Discount) > 0)
	{
		TotalDiscount = parseFloat(InvoiceTotal) - (parseFloat(InvoiceTotal) * ((100 - parseFloat(Discount))/100));
		document.getElementById("invoicediscount").innerHTML = "R" + TotalDiscount.toFixed(2);	
	}
	else
	{
		document.getElementById("invoicediscount").innerHTML = "R0.00";	
	}
	
	//THEN VAT
	var VATOnAmount = parseFloat(InvoiceTotal) - parseFloat(TotalDiscount);
	//CHECK IF CLIENT SELECTED PAYS VAT OR NOT
	
	var ThisClient = '<?php echo $ThisCustomerID ?>';
	var ChargeVat = 0;
	
	if (ThisClient != "")
	{
		ChargeVat = agent.call('','CheckClientVatSetting','', ThisClient);
	}
	
	var VatAmount = 0;
	if (parseInt(ChargeVat) == 0)
	{
		VatAmount = (parseFloat(VATOnAmount) * 1.14) - VATOnAmount;
		document.getElementById("invoicevat").innerHTML = "R" + VatAmount.toFixed(2);	
	}
	else
	{
		document.getElementById("invoicevat").innerHTML = "R0.00";	
	}
	
	var InvoiceTotal = parseFloat(VATOnAmount) + parseFloat(VatAmount);
	document.getElementById("invoicetotal").innerHTML = "R" + InvoiceTotal.toFixed(2);
	
}

function CreateInvoice()
{
	//INVOICE INFO
	var Reference = document.getElementById("reference").value;
	var InvoiceNumber = document.getElementById("invoicenumber").value;
	var DiscountPercent = document.getElementById("discount").value;
	var DueDate = document.getElementById("startdate").value;
	var Status = document.getElementById("status").value;
	var AdminInvoiceNotes = document.getElementById("invoicenotes").value;
	
	//CHECK IF REQUIRED FIELDS ARE FILLED IN
	if (DueDate != "" && Status != "")
	{
		//NOW WE NEED TO CHEWCK WE HAVE ITEMS FOR THIS INVOICE
		for (var X = 1; X <= RowCounter; X++)
		{
			var HasItems = 0;
			
			if ($('#total' + X).length > 0) 
			{
				var ThisTotalLine = document.getElementById("total" + X).innerHTML;
				TotalAlone = parseFloat(ThisTotalLine.substring(1));
				
				
				if (TotalAlone >= 0)
				{
					HasItems = 1;
				}
				else
				{
					HasItems = 0; //THERE SEEMS TO BE A LINE WITH NO TOTAL	
				}
				
			}
		}
		
		if (HasItems == 1)
		{
			//CREATE BASE INVOICE
			var CreateInvoice = agent.call('','CreateInvoice','', '<?php echo $ThisCustomerID ?>', Reference, InvoiceNumber, DiscountPercent, DueDate, Status, AdminInvoiceNotes);
			if (CreateInvoice > 0)
			{
				//NOW THE LINE ITEMS
				for (var X = 1; X <= RowCounter; X++)
				{
					if ($('#total' + X).length > 0) 
					{
						//GET ITEM, DESCRIPTION, QUANTITY AND PRICE
						var Item = document.getElementById("item" + X).value;
						var Description = document.getElementById("description" + X).value;
						var Quantity = document.getElementById("quantity" + X).value;
						var Price = document.getElementById("price" + X).value;
						
						var AddInvoiceLine = agent.call('','AddInvoiceLine','', CreateInvoice, Item, Description, Quantity, Price);
	
					}
				}
				
				bootbox.alert('Invoice added successfully', function() {
							
							window.history.go(-1);
				});
				
			}
			else
			{
				bootbox.alert(CreateInvoice);	
			}
		}
		else
		{
			bootbox.alert("It appears that there are no invoice lines added or a price has not been entered for a line or the price is negative, please check and correct");	
		}
		
	}
	else
	{
		bootbox.alert("Please enter all fields marked with a *");	
	}
	
		
}
</script>
</head>

<body>

    <div id="wrapper">

        <?php include("navigation.php"); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Add New Tax Invoice  <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                   
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong><i class="fa fa-user fa-fw"></i> Document Details</strong>
                            
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            
                            
                            
                            
                            <div class="form-group row col-md-6">
                              <label for="reccuringnumber" class="col-sm-5 col-form-label" style="padding-top: 5px">Invoice Number *</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="invoicenumber" placeholder="Invoice Number" value="<?php echo $InvoiceReference ?>" disabled>
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="reference" class="col-sm-5 col-form-label" style="padding-top: 5px">Reference</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="reference" placeholder="Reference">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="discount" class="col-sm-5 col-form-label" style="padding-top: 5px">Discount %</label>
                              <div class="col-sm-6">
                                <input type="text" class="form-control" id="discount" placeholder="0.00" onKeyUp="javascript: ShowTotal();" value="0">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="startdate" class="col-sm-5 col-form-label">Due Date *</label>
                              <div class="col-sm-6">
                                <input type="date" id="startdate" class="form-control">
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px">Invoice Status</label>
                              <div class="col-sm-6">
                                <select id="status" class="form-control">
                                	
                                    	<option value="0">Draft</option>
                                        <option value="1" selected>Active</option>
                                        
                                   
                                </select>
                              </div>
                            </div>
                            
                            <div class="form-group row col-md-6">
                              <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px">Admin Invoice Notes</label>
                              <div class="col-sm-6">
                                <textarea class="form-control" placeholder="Admin Invoice Notes" id="invoicenotes"></textarea>
                              </div>
                            </div>
                            
                            
                            
                            <!-- END FORM CONTROLS -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    
                    
                </div>
                <!-- /.col-lg-8 -->
                
            </div>
            <!-- /.row -->
            
            
            
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong><i class="fa fa-list fa-fw"></i> Invoice Items</strong>
                            
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="col-md-12">
                              	<table width="100%" class="table table-striped table-bordered table-hover" style="font-size: 12px" id="invoicetable">
                                	<thead>
                                    <tr>
                                       
                                        <th>Item</th>
                                        <th>Description</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        
                                        <th>Total</th>
                                        <th></th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	
                                    <tr class="odd gradeX">
                                        
                                        <td width="20%">
                                        <select class="form-control" id="item<?php echo $CurrentRow ?>" onChange="javascript: GetProductDetails(<?php echo $CurrentRow ?>)"> 
                                        <option value="" selected>Please select</option>
                                        <?php while ($Val = mysqli_fetch_array($ProductGroups))
										{
											$ProductGroupID = $Val["ProductGroupID"];
											$ProductGroup = $Val["ProductGroup"];
											
											$GroupProducts = GetGroupProducts($ProductGroupID);	
											
											while ($Val2 = mysqli_fetch_array($GroupProducts))
											{
												$ItemID = $Val2["ItemID"];
												$Item = $Val2["Item"];
										?>
                                        	<option value="<?php echo $ItemID ?>"><?php echo $ProductGroup ?> - <?php echo $Item ?></option>
                                        <?php }
										}?>
                                        <option value="Other">Other</option>
                                        </select>
                                        </td>
                                        <td><input type="text" class="form-control" id="description<?php echo $CurrentRow ?>"></td>
                                        <td width="60">
										<select class="form-control" id="quantity<?php echo $CurrentRow ?>" disabled onChange="javascript: ReworkPrice(<?php echo $CurrentRow ?>)">
                                            
                                           
                                            <option value="1" selected>1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                            <option value="13">13</option>
                                            <option value="14">14</option>
                                            <option value="15">15</option>
                                            <option value="16">16</option>
                                            <option value="17">17</option>
                                            <option value="18">18</option>
                                            <option value="19">19</option>
                                            <option value="20">20</option>
                                            <option value="21">21</option>
                                            <option value="22">22</option>
                                            <option value="23">23</option>
                                            <option value="24">24</option>
                                            <option value="25">25</option>
                                            <option value="26">26</option>
                                            <option value="27">27</option>
                                            <option value="28">28</option>
                                            <option value="29">29</option>
                                            <option value="30">30</option>
                                            
                                        </select>
                                        </td>
                                        <td width="100"><input type="text" class="form-control" id="price<?php echo $CurrentRow ?>"  onKeyUp="javascript: ReworkPrice(<?php echo $CurrentRow ?>)"></td>
                                       
                                        <td style="padding-top: 15px"><span id="total<?php echo $CurrentRow ?>"></span></td>
                                        <td class="center"><i class="fa fa-plus-square" style="color: #060; font-size: 18px" onClick="javascript: AddTableRow(<?php echo $CurrentRow ?>)"></i></td>
                                        
                                    </tr>
                                  
                                    
                                    <tr>
                                    <td colspan="6"></td>
                                    </tr>
                                    <tr>
                                    	<td colspan=""></td>
                                        <td colspan=""></td>
                                        <td colspan=""></td>
                                        <td colspan=""><strong>Invoice Sub Total</strong></td>
                                        <td colspan="" id="invoicesubtotal">R0.00</td>
                                        <td colspan=""></td>
                                    </tr>
                                    <tr>
                                    	<td colspan=""></td>
                                        <td colspan=""></td>
                                        <td colspan=""></td>
                                        <td colspan=""><strong>Discount</strong></td>
                                        <td colspan="" id="invoicediscount">R0.00</td>
                                        <td colspan=""></td>
                                    </tr>
                                    <tr>
                                    	<td colspan=""></td>
                                        <td colspan=""></td>
                                        <td colspan=""></td>
                                        <td colspan=""><strong>Invoice VAT</strong></td>
                                        <td colspan="" id="invoicevat">R0.00</td>
                                        <td colspan=""></td>
                                    </tr>
                                    <tr>
                                    	<td colspan=""></td>
                                        <td colspan=""></td>
                                        <td colspan=""></td>
                                        <td colspan=""><strong>Invoice Total</strong></td>
                                        <td colspan="" id="invoicetotal">R0.00</td>
                                        <td colspan=""></td>
                                    </tr>
                                </tbody>
                                </table>
                            </div>
                            
                            
                            
                             
                            
                           
                            
                            <!-- END FORM CONTROLS -->
                        </div>
                        <!-- /.panel-body -->
                    <div class="col-lg-12" style="padding-top: 20px; padding-bottom: 20px">
                                                <button class="btn btn-info pull-right" onClick="javascript: CreateInvoice();">Create Invoice</button>
                                                
                                                
                    </div>
                    <!-- /.panel -->
                    
                    
                </div>
                <!-- /.col-lg-8 -->
                
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
   
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="vendor/metisMenu/metisMenu.min.js"></script>

   
    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>
    <script src="js/bootbox.js"></script>
    
 <script type="text/javascript">
	var dateToday = new Date();
	dateToday.setDate(dateToday.getDate() + 1);
	
	
	$(function () {
	  $('#startdate').datepicker({
		   dateFormat: 'yy-mm-dd',
		   autoclose: true,
		   changeMonth: true,
		   changeYear: false,
		   yearRange: '<?php echo date("Y") ?>:2030',
		   minDate: dateToday
		});
		
		
	});
	
	$(function () {
	  $('#enddate').datepicker({
		   dateFormat: 'yy-mm-dd',
		   autoclose: true,
		   changeMonth: true,
		   changeYear: false,
		   yearRange: '<?php echo date("Y") ?>:2030',
		   minDate: dateToday
		});
		
		
	});
  </script>   

</body>

</html>
