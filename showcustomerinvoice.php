<?php
include("includes/webfunctions.php");
include('includes/agent.php');
$agent->init();


//SECURITY
$ThisClientID = $_SESSION["ClientID"];
$ThisUser = $_SESSION["ClientName"];
$ThisSecret = $_SESSION["SiteSecret"];

if ($ThisClientID > 0 && $ThisUser != "" && $ThisSecret == "E2A_crm_S5gdbh6nnj_usr_9898") {
    $CustomerID = $_REQUEST["c"];

    $ClientInfo = GetSingleClient($CustomerID);

    $FoundClient = mysqli_num_rows($ClientInfo);


    if ($FoundClient != 0) {

        while ($Val = mysqli_fetch_array($ClientInfo)) {

            $Name = $Val["FirstName"];
            $Surname = $Val["Surname"];
            $CompanyName = $Val["CompanyName"];

            if ($CompanyName != "") {
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
            $ContactNumber = $Val["ContactNumber"];
            $ClientCountryID = $Val["CountryID"];

            $TaxExempt = $Val["TaxExempt"];
            $OverdueNotices = $Val["OverdueNotices"];
            $MarketingEmails = $Val["MarketingEmails"];
            $PaymentMethod = $Val["PaymentMethod"];
            $VatNumber = $Val["VatNumber"];
            $AdminNotes = $Val["AdminNotes"];

            $ThisResellerID = $Val["ResellerID"];
            $creadit_amount = $Val["creadit_amount"];

        }


        if ($ThisStatus == 2) {
            //ACTIVE
            $ShowStatus = '<span class="label label-success" style="font-size: 14px">Active</span>';
        } else {
            //INACTIVE
            $ShowStatus = '<span class="label label-danger" style="font-size: 14px">Inactive</span>';
        }

        $InvoiceID = $_REQUEST["i"];
        $InvoiceDetails = GetInvoiceDetails($InvoiceID);

        while ($Val = mysqli_fetch_array($InvoiceDetails)) {
            $InvoiceNumber = $Val["InvoiceNumber"];
            $DueDate = $Val["DueDate"];
            $DiscountPercent = $Val["DiscountPercent"];
            $InvoiceStatus = $Val["InvoiceStatus"];
            $Address1 = $Val["Address1"];
            $Address2 = $Val["Address2"];
            $City = $Val["City"];
            $State = $Val["State"];
            $PostCode = $Val["PostCode"];
            $CountryID = $Val["CountryID"];
            $InvoiceNotes = $Val["InvoiceNotes"];

            if ($InvoiceStatus == 0) {
                //WERE IN THE WRONG PLACE
                echo "<script type='text/javascript'>document.location = 'editcustomerinvoice.php?i=" . $InvoiceID . "&c=" . $CustomerID . "';</script>";
            }
        }

        if ($InvoiceStatus == 1) {
            $InvoiceLabel = ' <div class="alert alert-danger">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								   <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This invoice is currently unpaid
									</div>';
        } else if ($InvoiceStatus == 2) {
            $InvoiceLabel = ' <div class="alert alert-success">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								   <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This invoice is paid
									</div>';
        } else if ($InvoiceStatus == 3) {
            $InvoiceLabel = ' <div class="alert alert-warning">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								   <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This invoice was cancelled
									</div>';
        } else if ($InvoiceStatus == 4) {
            $InvoiceLabel = ' <div class="alert alert-warning">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								   <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This invoice was refunded
									</div>';
        } else if ($InvoiceStatus == 5) {
            $InvoiceLabel = ' <div class="alert alert-danger">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								   <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This invoice was handed over for collection
									</div>';
        } else if ($InvoiceStatus == 5) {
            $InvoiceLabel = ' <div class="alert alert-warning">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								   <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This invoice is partially paid
									</div>';
        }


        $Countries = GetCountries();
        $ProductGroups = GetAllActiveProductGroups();

        $InvoiceGroups = GetInvoiceGroups($InvoiceID);
        $InvoiceLines = GetInvoiceLines($InvoiceID);
        $NumLines = mysqli_num_rows($InvoiceLines);
    } else {
        echo "<script type='text/javascript'>alert('Your session has expired, please login again'); parent.location = 'logout.php';</script>";
    }

} else {
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
        function UpdateInvoiceStatus() {
            var NewStatus = document.getElementById("newstatus").value;
            var DoUpdateStatus = agent.call('', 'UpdateInvoiceStatus', '', NewStatus, '<?php echo $InvoiceID ?>');
            if (DoUpdateStatus == "OK") {
                if (NewStatus == 1) {
                    NewStatus = 'Unpaid';
                }
                if (NewStatus == 2) {
                    NewStatus = 'Paid';
                }
                if (NewStatus == 3) {
                    NewStatus = 'Cancelled';
                }
                if (NewStatus == 4) {
                    NewStatus = 'Refunded';
                }
                if (NewStatus == 5) {
                    NewStatus = 'Collections';
                }

                var Log = agent.call('', 'CreateClientAccess', '', <?php echo $CustomerID ?>, 'Updates Customer Invoice INV<?php echo $InvoiceID ?> status to ' + NewStatus);

                bootbox.alert('Invoice status updated successfully', function () {
                    document.location = 'clientinvoices.php?c=<?php echo $CustomerID ?>';
                });
            }
            else {
                bootbox.alert(DoUpdateStatus);
            }
        }

        function SendInvoice() {
            var SendCustomerInvoice = agent.call('', 'SendCustomerInvoice', '', '<?php echo $CustomerID ?>', '<?php echo $InvoiceID ?>');
            if (SendCustomerInvoice == "OK") {
                bootbox.alert('The invoice has been sent successfully', function () {
                    document.location = 'clientinvoices.php?c=<?php echo $CustomerID ?>';
                });
            }
            else {
                alert(SendCustomerInvoice)
            }
        }

        function ResendInvoice() {
            var SendCustomerInvoice = agent.call('', 'ResendCustomerInvoice', '', '<?php echo $CustomerID ?>', '<?php echo $InvoiceID ?>');
            if (SendCustomerInvoice == "OK") {
                bootbox.alert('The invoice has been resent successfully', function () {
                    document.location = 'clientinvoices.php?c=<?php echo $CustomerID ?>';
                });
            }
            else {
                alert(SendCustomerInvoice)
            }
        }

        function UseCredit() {
            var creadit_amount = '<?php echo $creadit_amount;?>';
            bootbox.confirm({
                title: "Confiramation",
                message: "Do you really want to use your Credit Amount <strong>" +
                " <?php echo number_format($creadit_amount, 2)?></strong>?",
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Confirm'
                    }
                },
                callback: function (result) {
                    if (result) {
                        var txtInvoiceTotal = $("#txtInvoiceTotal").val();

                        var PaymentDate = '<?php echo date("Y-m-d H:i:s",strtotime("now"))?>';
                        var Description = 'Invoice Payment INV<?php echo $InvoiceID ?>';
                        var PaymentMethod = "Credit amount";
                        var Reference = "Thank-you for your payment";
                        var CalcCreditAmount = 0;
                        var invoiceAmount=0;
                        if(parseFloat(creadit_amount) > parseFloat(txtInvoiceTotal)){
                            CalcCreditAmount = parseFloat(creadit_amount) - parseFloat(txtInvoiceTotal);
                            invoiceAmount = parseFloat(txtInvoiceTotal);
                        }else{
                            CalcCreditAmount = 0;
                            invoiceAmount = parseFloat(creadit_amount);
                        }
                        var SaveTransaction = agent.call('', 'SaveTransaction', '', PaymentDate, invoiceAmount, Description, Reference, PaymentMethod, '<?php echo $CustomerID ?>');

                        var AddInvoicePayment = agent.call('', 'AddInvoicePayment', '', "<?php echo $InvoiceID ?>", invoiceAmount, SaveTransaction);

                        var SaveCreditAmount = agent.call('', 'updateCustomerCreadit', '', CalcCreditAmount, '<?php echo $CustomerID ?>','2');

                        if (SaveCreditAmount == "OK") {
                            bootbox.alert('Invoice status updated successfully', function () {
                                document.location = 'clientinvoices.php?c=<?php echo $CustomerID ?>';
                            });
                        }
                        else {
                            alert(SendCustomerInvoice)
                        }
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
                <h1 class="page-header">Customer Details <?php echo $TopCompanyName ?> <img src="images/logo.png"
                                                                                            class="img-responsive pull-right"
                                                                                            style="height: 45px"></h1>
            </div>

            <!-- /.col-lg-12 -->
        </div>
        <div class="alert alert-info">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page shows you the
            invoice details
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">

                <!-- Nav tabs -->
                <ul class="nav nav-tabs responsive">
                    <li><a href="clientinfo.php?c=<?php echo $CustomerID ?>">Summary</a>
                    </li>
                    <li><a href="clientprofile.php?c=<?php echo $CustomerID ?>">Profile</a>
                    </li>
                    <li><a href="clientcontacts.php?c=<?php echo $CustomerID ?>">Contacts</a>
                    </li>

                    <li class="active"><a href="clientinvoices.php?c=<?php echo $CustomerID ?>">Invoices</a>
                    </li>
                    <li><a href="clientquotes.php?c=<?php echo $CustomerID ?>">Quotes</a>
                    </li>
                    <li><a href="clienttransactions.php?c=<?php echo $CustomerID ?>">Transactions</a>
                    </li>
                    <li><a href="clientstatement.php?c=<?php echo $CustomerID ?>">Statement</a>
                    </li>
                    <li><a href="clientdocuments.php?c=<?php echo $CustomerID ?>">Documents</a>
                    </li>
                    <li><a href="clientjobcards.php?c=<?php echo $CustomerID ?>">Job Cards</a>
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

                    <li class="pull-right"><a href="showclients.php"><i class="fa fa-caret-left"></i> Back to All
                            Customers</a>
                    </li>

                </ul>


                <div class="col-lg-12" style="padding-top: 10px">
                    <?php echo $InvoiceLabel ?>
                    <?php if ($InvoiceStatus != 2) { ?>
                        <h4 style="padding-bottom: 10px" class="pull-right form-inline">Update Invoice Status
                            <select id="newstatus" class="form-control form-inline">
                                <option value="">Please select new status</option>

                                <?php if ($InvoiceStatus == 3) {
                                    $Selected = 'selected';
                                } else {
                                    $Selected = '';
                                } ?>
                                <option value="3" <?php echo $Selected ?>>Cancelled</option>
                                <?php if ($InvoiceStatus == 4) {
                                    $Selected = 'selected';
                                } else {
                                    $Selected = '';
                                } ?>
                                <option value="4" <?php echo $Selected ?>>Refunded</option>
                                <?php if ($InvoiceStatus == 5) {
                                    $Selected = 'selected';
                                } else {
                                    $Selected = '';
                                } ?>
                                <option value="5" <?php echo $Selected ?>>Hand Over for Collection</option>
                            </select>

                            <button class="btn btn-default form-inline" onClick="javascript: UpdateInvoiceStatus();">
                                Update Status
                            </button>
                        </h4>
                    <?php } ?>

                    <div class="clearfix"></div>
                    <h4 style="padding-bottom: 10px">Invoice Details</h4>
                    <div class="form-group row col-md-6">
                        <label for="documentname" class="col-sm-5 col-form-label" style="padding-top: 5px">Customer
                            Reference *</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="reference" name="reference"
                                   placeholder="Customer Reference" value="<?php echo $InvoiceNumber ?>" disabled>
                        </div>
                    </div>

                    <div class="form-group row col-md-6">
                        <label for="documentgroup" class="col-sm-5 col-form-label" style="padding-top: 5px">Due Date
                            *</label>
                        <div class="col-sm-6">

                            <input type="text" class="form-control datepicker" id="datepicker" name="datepicker"
                                   placeholder="Due Date" data-date-format="yyyy-mm-dd" value="<?php echo $DueDate ?>"
                                   disabled>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="form-group row col-md-6">
                        <label for="documentgroup" class="col-sm-5 col-form-label" style="padding-top: 5px">VAT
                            Number</label>
                        <div class="col-sm-6">

                            <input type="text" class="form-control" id="vatnum" name="vatnum" placeholder="VAT Number"
                                   value="<?php echo $VatNumber ?>" disabled>
                        </div>
                    </div>

                    <div class="form-group row col-md-6">
                        <label for="documentgroup" class="col-sm-5 col-form-label" style="padding-top: 5px">Discount
                            %</label>
                        <div class="col-sm-6">

                            <input type="text" class="form-control" id="discount" name="discount"
                                   placeholder="Discount %" value="0" disabled>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <h4 style="padding-bottom: 10px">Billing Address</h4>

                    <div class="form-group row col-md-6">
                        <label for="address1" class="col-sm-5 col-form-label" style="padding-top: 5px">Address 1
                            *</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="address1" placeholder="Address 1"
                                   value="<?php echo $Address1 ?>" disabled>
                        </div>
                    </div>

                    <div class="form-group row col-md-6">
                        <label for="address2" class="col-sm-5 col-form-label" style="padding-top: 5px">Address 2</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="address2" placeholder="Address 2"
                                   value="<?php echo $Address2 ?>" disabled>
                        </div>
                    </div>

                    <div class="form-group row col-md-6">
                        <label for="city" class="col-sm-5 col-form-label" style="padding-top: 5px">City *</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="city" placeholder="City"
                                   value="<?php echo $City ?>" disabled>
                        </div>
                    </div>

                    <div class="form-group row col-md-6">
                        <label for="state" class="col-sm-5 col-form-label" style="padding-top: 5px">State/Region
                            *</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="state" placeholder="State/Region"
                                   value="<?php echo $Region ?>" disabled>
                        </div>
                    </div>

                    <div class="form-group row col-md-6">
                        <label for="postcode" class="col-sm-5 col-form-label" style="padding-top: 5px">Post Code
                            *</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="postcode" placeholder="Post Code"
                                   value="<?php echo $PostCode ?>" disabled>
                        </div>
                    </div>

                    <div class="form-group row col-md-6">
                        <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px">Country *</label>
                        <div class="col-sm-6">
                            <select id="country" class="form-control" disabled>
                                <?php while ($Val = mysqli_fetch_array($Countries)) {
                                    $CountryID = $Val["CountryID"];
                                    $CountryName = $Val["CountryName"];

                                    if ($ClientCountryID == $CountryID) {
                                        $Selected = 'selected';
                                    } else {
                                        $Selected = '';
                                    }
                                    ?>
                                    <option value="<?php echo $CountryID ?>" <?php echo $Selected ?>><?php echo $CountryName ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row col-md-6">
                        <label for="state" class="col-sm-5 col-form-label" style="padding-top: 5px">Customer Invoice
                            Notes</label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="invoicenotes" placeholder="Customer Invoice Notes"
                                      disabled><?php echo $InvoiceNotes ?></textarea>
                        </div>
                    </div>


                    <!-- END FORM CONTROLS -->


                </div>


                <!-- /.table-responsive -->


            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-12" style="padding-top: 10px">
                    <h4 style="padding-bottom: 10px">Invoice Lines</h4>

                    <table width="100%" class="table table-striped table-bordered table-hover" style="font-size: 12px"
                           id="invoicetable">
                        <thead>
                        <tr>


                            <th>Description</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Sub Total</th>
                            <th>Gross Profit</th>

                            <th>Discount</th>
                            <th>VAT</th>

                            <th>Total</th>


                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        //INVOICE GROUPS
                        $InvoiceSub = 0;
                        $InvoiceDiscount = 0;
                        $InvoiceVat = 0;
                        $InvoiceTotal = 0;
                        $TotalGP = 0;

                        while ($Val = mysqli_fetch_array($InvoiceGroups)) {
                            $GroupName = $Val["GroupName"];
                            $InvoiceGroupID = $Val["InvoiceGroupID"];

                            $GroupPrice = GetInvoiceGroupPrice($InvoiceID, $InvoiceGroupID);
                            $GroupSub = GetInvoiceGroupSub($InvoiceID, $InvoiceGroupID);
                            $GroupDiscount = GetInvoiceGroupDiscount($InvoiceID, $InvoiceGroupID);
                            $GroupVat = GetInvoiceGroupVat($InvoiceID, $InvoiceGroupID);
                            $GroupLineTotal = GetInvoiceGroupLineTotal($InvoiceID, $InvoiceGroupID);
                            $GroupLineProfit = GetInvoiceGroupLineProfit($InvoiceID, $InvoiceGroupID);

                            $TotalGP = $TotalGP + $GroupLineProfit;

                            $InvoiceSub = $InvoiceSub + $GroupSub;
                            $InvoiceDiscount = $InvoiceDiscount + $GroupDiscount;
                            $InvoiceVat = $InvoiceVat + $GroupVat;
                            $InvoiceTotal = $InvoiceTotal + $GroupLineTotal;

                            //NOW GET WHAT THE GROUP INCLUDES
                            $GroupItems = GetGroupItems($InvoiceID, $InvoiceGroupID);

                            ?>


                            <tr class="odd gradeX">


                                <td><?php echo $GroupName ?><br>
                                    <?php while ($GroupVal = mysqli_fetch_array($GroupItems)) {
                                        $Description = $GroupVal["Description"];
                                        $Quantity = $GroupVal["Quantity"];
                                        $Meassure = $GroupVal["MeassurementDescription"];
                                        $InvoiceLineItemID = $GroupVal["InvoiceLineItemID"];

                                        if ($Meassure == "") {
                                            $ThisLine = $Description;
                                        } else {
                                            $ThisLine = $Description . " (" . $Meassure . ")";
                                        }


                                        echo " - " . $Quantity . " x " . $ThisLine . "<br>";
                                        ?>

                                    <?php } ?>

                                </td>
                                <td width="">R<?php echo number_format($GroupLineTotal, 2) ?></td>
                                <td width="">1</td>
                                <td width="">R<?php echo number_format($GroupSub, 2) ?></td>
                                <td width="">R<?php echo number_format($GroupLineProfit, 2) ?></td>
                                <td width="">R<?php echo number_format($GroupDiscount, 2) ?></td>
                                <td width="">R<?php echo number_format($GroupVat, 2) ?></td>
                                <td>R<?php echo number_format($GroupLineTotal, 2) ?></td>


                            </tr>
                        <?php } ?>
                        <?php


                        while ($Val = mysqli_fetch_array($InvoiceLines)) {
                            $Description = $Val["Description"];
                            $Quantity = $Val["Quantity"];
                            $Price = $Val["Price"];

                            $LineSub = $Val["LineSubTotal"];
                            $InvoiceSub = $InvoiceSub + $LineSub;

                            $Discount = $Val["LineDiscount"];
                            $InvoiceDiscount = $InvoiceDiscount + $Discount;

                            $Vat = $Val["LineVAT"];
                            $InvoiceVat = $InvoiceVat + $Vat;

                            $Meassure = $Val["MeassurementDescription"];

                            $LineTotal = $Val["LineTotal"];
                            $InvoiceTotal = $InvoiceTotal + $LineTotal;

                            $InvoiceLineItemID = $Val["InvoiceLineItemID"];
                            $Profit = $Val["Profit"];

                            $TotalGP = $TotalGP + $Profit;

                            ?>
                            <tr class="odd gradeX">


                                <td><?php echo $Description ?> (<?php echo $Meassure ?>)</td>
                                <td width="">R<?php echo number_format($Price, 2) ?></td>
                                <td width=""><?php echo $Quantity ?></td>
                                <td width="">R<?php echo number_format($LineSub, 2) ?></td>
                                <td width="">R<?php echo number_format($Profit, 2) ?></td>
                                <td width="">R<?php echo number_format($Discount, 2) ?></td>
                                <td width="">R<?php echo number_format($Vat, 2) ?></td>
                                <td>R<?php echo number_format($LineTotal, 2) ?></td>


                            </tr>
                        <?php } ?>

                        <tr>

                            <td colspan="">&nbsp;</td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>

                        </tr>


                        <tr>

                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""><strong>Invoice Sub Total</strong></td>
                            <td colspan="" id="invoicesubtotal">R<?php echo number_format($InvoiceSub, 2) ?></td>

                        </tr>
                        <tr>

                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""><strong>Discount</strong></td>
                            <td colspan="" id="invoicediscount">R<?php echo number_format($InvoiceDiscount, 2) ?></td>

                        </tr>
                        <tr>

                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""><strong>Invoice VAT</strong></td>
                            <td colspan="" id="invoicevat">R<?php echo number_format($InvoiceVat, 2) ?></td>

                        </tr>
                        <tr>

                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""><strong>Invoice Total</strong></td>
                            <td colspan="">R<span id="invoicetotal"><?php echo number_format($InvoiceTotal, 2) ?>
                                    <input type="hidden" id="txtInvoiceTotal" value="<?php echo
                                    GetInvoiceOutstandingAmount($InvoiceID);
                                    ?>"/>
                                            </span></td>

                        </tr>

                        <tr>

                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan="">&nbsp;</td>

                        </tr>

                        <tr>

                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""><strong>Total Profit</strong></td>
                            <td colspan="">R<span id="invoicetotal"><?php echo number_format($TotalGP, 2) ?></span></td>

                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12" style="margin-bottom: 50px" align="right">
                    <?php if ($InvoiceStatus == 0) { ?>
                        <button class="btn btn-success" onClick="javascript: SendInvoice();">Send Invoice</button>
                    <?php } else { ?>

                        <button class="btn btn-success" onClick="javascript: ResendInvoice();">Resend Invoice</button>
                        <?php if ($InvoiceStatus == 1 || $InvoiceStatus == 6) {
                            if (floatval($creadit_amount) > 0) {
                                ?>

                                <button class="btn btn-info" onClick="javascript: UseCredit();">Use Credit Amount
                                </button>
                            <?php } ?>
                            <button class="btn btn-warning"
                                    onClick="javascript: document.location = 'addcustomerpayment.php?c=<?php echo $CustomerID ?>&inv=<?php echo $InvoiceID ?>'">
                                Add Payment
                            </button>
                        <?php } ?>
                    <?php } ?>
                    <a class="btn btn-danger"
                       href="showinvoice.php?i=<?php echo $InvoiceID ?>&c=<?php echo $CustomerID ?>" target="_blank">Preview
                        Customer Invoice</a>
                </div>
            </div>
        </div>

    </div>
    <!-- /#page-wrapper -->

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
    $(document).ready(function () {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });

    //MENU STUFF FOR PAGE

    document.getElementById("customermenu").className = 'active';
    document.getElementById("customermenucustomer").className = 'active';

    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: new Date(<?php echo date("Y") ?>, <?php echo date("m") ?> -1, <?php echo date("d") ?>)
    });
</script>


</body>

</html>
