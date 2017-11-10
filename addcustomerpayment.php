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
            $ClientPayment = $Val["PaymentMethod"];

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

        }


        if ($ThisStatus == 2) {
            //ACTIVE
            $ShowStatus = '<span class="label label-success" style="font-size: 14px">Active</span>';
        } else {
            //INACTIVE
            $ShowStatus = '<span class="label label-danger" style="font-size: 14px">Inactive</span>';
        }


        //GET ALL UNPAID INVOICES
        $Invoices = GetAllUnpaidInvoices($CustomerID);

        //NOW CHECK IF THEY CAME FROM THE INVOICE MODULE
        $ThisInvoiceID = $_REQUEST["inv"];

        if ($ThisInvoiceID != "") {
            $Description = 'Invoice Payment INV' . $ThisInvoiceID;
            $ThisOutstanding = GetInvoiceOutstandingAmount($ThisInvoiceID);
        }

        //NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
        if ($_SESSION["MainClient"] == 1) {
            $Access = 1;
        } else {
            $Access = CheckPageAccess('Transactions');
        }

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
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

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
                <h1 class="page-header">Customer Details <?php echo $TopCompanyName ?> <img src="images/logo.png"
                                                                                            class="img-responsive pull-right"
                                                                                            style="height: 45px"></h1>
            </div>

            <!-- /.col-lg-12 -->
        </div>
        <div class="alert alert-info">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to add
            payment to the customer profile
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

                    <li><a href="clientinvoices.php?c=<?php echo $CustomerID ?>">Invoices</a>
                    </li>
                    <li><a href="clientquotes.php?c=<?php echo $CustomerID ?>">Quotes</a>
                    </li>
                    <li class="active"><a href="clienttransactions.php?c=<?php echo $CustomerID ?>">Transactions</a>
                    </li>
                    <li><a href="clientdocuments.php?c=<?php echo $CustomerID ?>">Documents</a>
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


                <?php if ($Access == 1) { ?>
                <div class="col-lg-12" style="padding-top: 10px">


                    <div class="clearfix"></div>
                    <h4 style="padding-bottom: 10px">Add Payment</h4>

                    <div class="form-group row col-md-6">
                        <label for="productgroup" class="col-sm-3 col-form-label" style="padding-top: 5px">Payment Date
                            *</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control datepicker" id="datepicker" name="datepicker"
                                   placeholder="Payment Date" data-date-format="yyyy-mm-dd"
                                   value="<?php echo date("Y-m-d") ?>">
                        </div>
                    </div>

                    <div class="form-group row col-md-6">
                        <label for="product" class="col-sm-3 col-form-label" style="padding-top: 5px">Payment Amount
                            *</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="paymentamountin" placeholder="Amount In"
                                   value="<?php echo number_format($ThisOutstanding, 2, ".", "") ?>">
                            <input type="text" class="form-control" id="hiddenpaymentamountin" style="display: none;">
                        </div>
                    </div>

                    <div class="form-group row col-md-6">
                        <label for="product" class="col-sm-3 col-form-label" style="padding-top: 5px">Description
                            *</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="paymentdescription"
                                   placeholder="Payment Description" value="<?php echo $Description ?>">

                        </div>
                    </div>


                    <div class="form-group row col-md-6">
                        <label for="productprice" class="col-sm-3 col-form-label" style="padding-top: 5px">Transaction
                            Reference *</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="paymentreference"
                                   placeholder="Transaction Reference">
                        </div>
                    </div>


                    <div class="form-group row col-md-6">
                        <label for="product" class="col-sm-3 col-form-label" style="padding-top: 5px">Allocate to
                            Invoice</label>
                        <div class="col-sm-6">
                            <?php while ($Val = mysqli_fetch_array($Invoices)) {
                                $InvoiceID = $Val["InvoiceID"];
                                $InvoiceNumber = $Val["InvoiceNumber"];
                                $AllInvoices .= $InvoiceID . ",";

                                $OutStandingAmount = GetInvoiceOutstandingAmount($InvoiceID);

                                if ($ThisInvoiceID != $InvoiceID) {
                                    ?>
                                    <input type="checkbox"
                                           onChange="javascript: CheckInvoicePayment(<?php echo $InvoiceID ?>)" class=""
                                           id="inv<?php echo $InvoiceID ?>"> <?php echo $InvoiceNumber ?> (R<?php echo number_format($OutStandingAmount, 2) ?> outstanding)
                                    <input type="hidden" value="<?php echo $InvoiceNumber ?>"
                                           id="invoiceNumber<?php echo $InvoiceID ?>"/>
                                    <input type="text" class="form-control" id="invpayment<?php echo $InvoiceID ?>"
                                           placeholder="Amount to Allocate" style="display: none;"
                                           onblur="getPaymentValue();"><br>
                                <?php } else { ?>
                                    <input type="checkbox"
                                           onChange="javascript: CheckInvoicePayment(<?php echo $InvoiceID ?>)" class=""
                                           id="inv<?php echo $InvoiceID ?>"
                                           checked> <?php echo $InvoiceNumber ?> (R<?php echo number_format($OutStandingAmount, 2) ?> outstanding)
                                    <input type="hidden" value="<?php echo $InvoiceNumber ?>"
                                           id="invoiceNumber<?php echo $InvoiceID ?>"/>
                                    <input type="text" class="form-control" id="invpayment<?php echo $InvoiceID ?>"
                                           placeholder="Amount to Allocate"
                                           value="<?php echo number_format($ThisOutstanding, 2, ".", "") ?>"
                                           onblur="getPaymentValue();"><br>
                                <?php }
                            } ?>
                        </div>
                    </div>

                    <div class="form-group row col-md-6">
                        <label for="quantity" class="col-sm-3 col-form-label" style="padding-top: 5px">Payment Method
                            *</label>
                        <div class="col-sm-6">

                            <select name="paymentmethod" class="form-control" id="paymentmethod">
                                <option value="" selected>Please select</option>
                                <option value="None">None</option>
                                <?php if ($ClientPayment == "Debit Order") {
                                    $Selected = 'selected';
                                } else {
                                    $Selected = '';
                                } ?>
                                <option value="Debit Order" <?php echo $Selected ?>>Debit Order</option>
                                <?php if ($ClientPayment == "EFT Payment") {
                                    $Selected = 'selected';
                                } else {
                                    $Selected = '';
                                } ?>
                                <option value="EFT Payment" <?php echo $Selected ?>>EFT Payment</option>
                                <?php if ($ClientPayment == "Credit Card Payment") {
                                    $Selected = 'selected';
                                } else {
                                    $Selected = '';
                                } ?>
                                <option value="Credit Card Payment" <?php echo $Selected ?>>Credit Card Payment</option>


                            </select>

                        </div>
                    </div>


                    <div class="form-group row col-md-12">
                        <label for="country" class="col-sm-5 col-form-label" style="padding-top: 5px"></label>
                        <div class="col-sm-6">
                            <button class="btn btn-info pull-right" onClick="javascript: AddPayment();">Add Payment
                            </button>
                        </div>
                    </div>


                    <!-- END FORM CONTROLS -->


                </div>
                <!-- /.table-responsive -->


            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->


    </div>
    <!-- /#page-wrapper -->
<?php } else { ?>
    <h4>You do not have access to this module, if you think this is a mistake please contact your system
        administrator</h4>
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
    $(document).ready(function () {
        $('#dataTables-example').DataTable({
            responsive: true
        });

        getPaymentValue();
    });

    //MENU STUFF FOR PAGE

    document.getElementById("customermenu").className = 'active';
    document.getElementById("customermenucustomer").className = 'active';

    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });
</script>

<script type="text/javascript">

    var CustomerID = "<?php echo $CustomerID ?>";

    function getPaymentValue() {
        var AllInvoices = '<?php echo rtrim($AllInvoices, ","); ?>';
        var AllInvoiceArray = AllInvoices.split(",");

        var count = 0;

        for (var i = 0; i < AllInvoiceArray.length; i++) {
            var ThisInvoiceID = AllInvoiceArray[i];
            var IsChecked = document.getElementById("inv" + ThisInvoiceID).checked;
            if (IsChecked === true) {
                console.log(ThisInvoiceID);
                var ThisAmount = document.getElementById("invpayment" + ThisInvoiceID).value;
                if (ThisAmount == '')
                    ThisAmount = 0;

                if (parseFloat(ThisAmount) > 0 && $.isNumeric(ThisAmount)) {
                    count = count + parseFloat(ThisAmount);
                }
            }

        }

        document.getElementById("paymentamountin").value = count;
        document.getElementById("hiddenpaymentamountin").value = count;

    }

    function CheckInvoicePayment(InvoiceID) {
        /**/
        var IsChecked = document.getElementById("inv" + InvoiceID).checked;
        if (IsChecked === true) {
            document.getElementById("invpayment" + InvoiceID).style.display = 'block';
        } else {
            document.getElementById("invpayment" + InvoiceID).style.display = 'none'
        }

    }


    function AddPayment() {
        var AllInvoices = '<?php echo rtrim($AllInvoices, ","); ?>';
        var AllInvoiceArray = AllInvoices.split(",");

        var PaymentDate = document.getElementById("datepicker").value;
        var PaymentAmount = parseFloat(document.getElementById("paymentamountin").value);
        var Description = document.getElementById("paymentdescription").value;
        var Reference = document.getElementById("paymentreference").value;
        var PaymentMethod = document.getElementById("paymentmethod").value;

        var CalcPaymentAmount = document.getElementById("hiddenpaymentamountin").value;
        if (PaymentDate != "" && PaymentAmount > 0 && Description != "" && PaymentMethod != "" && Reference != "") {
            //NOW CHECK IF WE CAN ALLOCATE ANY OF THIS AMOUNT TO THE INVOICES
            var InvoicePayments = 0;
            var Error = 0;
            var HasInvoiceAllocate = 0;
            var creaditAmount = 0;


            for (i = 0; i < AllInvoiceArray.length; i++) {
                if (Error == 0) {
                    var ThisInvoiceID = AllInvoiceArray[i];
                    var IsChecked = document.getElementById("inv" + ThisInvoiceID).checked;
                    if (IsChecked === true) {
                        var ThisAmount = document.getElementById("invpayment" + ThisInvoiceID).value;
                        if (parseFloat(ThisAmount) > 0 && $.isNumeric(ThisAmount)) {
                            //CHECK THE PAYMENT IS NOT MORE THAN WHATS OUTSTANDING
                            var ThisOutstanding = agent.call('', 'GetInvoiceOutstandingAmount', '', ThisInvoiceID);
                           // console.log(parseFloat(ThisOutstanding), parseFloat(ThisAmount));
                            if (parseFloat(ThisOutstanding) >= parseFloat(ThisAmount)) {
                                InvoicePayments = InvoicePayments + parseFloat(ThisAmount);
                                HasInvoiceAllocate = 1;
                            }
                            else {
                                Error = 1;
                                bootbox.alert("You cannot allocate an amount to an invoice thats greater than the outstanding amount of the invoice, please correct");
                            }
                        }
                        else {
                            Error = 1;
                            bootbox.alert("You have allocated payment to an invoice but have not entered the amount, please fix");
                        }
                    }
                }
            }



            if (Error == 0) {
                //PASSED TOP VALIDATION


                if (parseFloat(InvoicePayments) <= parseFloat(PaymentAmount)) {


                    if (parseFloat(CalcPaymentAmount) < parseFloat(PaymentAmount)) {
                        Error = 1;

                        bootbox.confirm({
                            title: "Confirmation",
                            message: "The allocated amounts to invoices are more than to the total payment amount. So the remaining amount will be added in your credit amount",
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
                                    doPayment(HasInvoiceAllocate);
                                }

                            }
                        });


                    }else{
                        doPayment(HasInvoiceAllocate)
                    }


                }
                else {
                    bootbox.alert("The allocated amounts to invoices is larger than the total payment amount, please rectify");
                }
            }

        }
        else {
            bootbox.alert("Please fill in all fields marked with a *");
        }
    }

    function doPayment(HasInvoiceAllocate){

        var PaymentDate = document.getElementById("datepicker").value;
        var PaymentAmount = parseFloat(document.getElementById("paymentamountin").value);
        var Description = document.getElementById("paymentdescription").value;
        var Reference = document.getElementById("paymentreference").value;
        var PaymentMethod = document.getElementById("paymentmethod").value;
        var CalcPaymentAmount = document.getElementById("hiddenpaymentamountin").value;

        var creditAmount = parseFloat(PaymentAmount) - parseFloat(CalcPaymentAmount);

        var SaveTransaction = agent.call('', 'SaveTransaction', '', PaymentDate, CalcPaymentAmount, Description, Reference, PaymentMethod, '<?php echo $CustomerID ?>');
        if (SaveTransaction > 0) {

            //update creadit amount

            var SaveCreditAmount = agent.call('', 'updateCustomerCreadit', '', creditAmount, '<?php echo $CustomerID ?>');

            // end

            var Log = agent.call('', 'CreateClientAccess', '', <?php echo $CustomerID ?>, 'Added customer transaction ' + Reference);
            if (HasInvoiceAllocate == 1) {
                var AllInvoices = '<?php echo rtrim($AllInvoices, ","); ?>';
                var AllInvoiceArray = AllInvoices.split(",");

                for (i = 0; i < AllInvoiceArray.length; i++) {
                    var ThisInvoiceID = AllInvoiceArray[i];
                    var IsChecked = document.getElementById("inv" + ThisInvoiceID).checked;
                    if (IsChecked === true) {
                        var ThisAmount = document.getElementById("invpayment" + ThisInvoiceID).value;
                        var AddInvoicePayment = agent.call('', 'AddInvoicePayment', '', ThisInvoiceID, ThisAmount, SaveTransaction);
                        if (AddInvoicePayment == "OK") {

                        }
                    }
                }


                bootbox.alert("The payment has been added successfully", function () {
                    document.location = 'clienttransactions.php?c=<?php echo $CustomerID ?>';
                });

            }
            else {
                bootbox.alert("The payment has been added successfully", function () {
                    document.location = 'clienttransactions.php?c=<?php echo $CustomerID ?>';
                });
            }
        }
        else {
            bootbox.alert(SaveTransaction);
        }
    }
</script>

</body>

</html>
