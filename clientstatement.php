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

    CreateClientAccess($CustomerID, 'Accessed Customer Statements');

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

        $FromDate = $_REQUEST["from"];
        $ToDate = $_REQUEST["to"];

        if ($FromDate == "") {
            $FromDate = date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y")));
            $ToDate = date("Y-m-d");
        }

        $OpeningBalance = GetCustomerOpeningStatement($FromDate, $ToDate, $CustomerID);


        //NOW WE NEED TO GET DEBITS AND CREDITS - THIS IS AN ARRAY
        $CustomerStatementArray = GetCustomerStatement($FromDate, $ToDate, $CustomerID);

        //NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
        if ($_SESSION["MainClient"] == 1) {
            $Access = 1;
        } else {
            $Access = CheckPageAccess('Statements');
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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
        function ChangeStatementDate() {
            var FromDate = document.getElementById("fromdate").value;
            var ToDate = document.getElementById("todate").value;

            if (FromDate != "" && ToDate != "") {
                document.location = 'clientstatement.php?c=<?php echo $CustomerID ?>&from=' + FromDate + '&to=' + ToDate;
            }
            else {
                bootbox.alert("Please select a from and to date for the statement");
            }
        }

        function SendStatement() {
            var SendCustomerIStatement = agent.call('', 'SendCustomerStatement', '', '<?php echo $CustomerID ?>', '<?php echo $FromDate ?>', '<?php echo $ToDate ?>');
            if (SendCustomerIStatement == "OK") {
                bootbox.alert('The statement has been sent successfully');
            }
            else {
                alert(SendCustomerIStatement)
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
                <h1 class="page-header">Customer Details <?php echo $TopCompanyName ?> <img src="images/logo.png"
                                                                                            class="img-responsive pull-right"
                                                                                            style="height: 45px"></h1>
            </div>

            <!-- /.col-lg-12 -->
        </div>
        <div class="alert alert-info">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows you to
            view the customer statement.
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">

                <!-- Nav tabs -->
                <ul class="nav nav-tabs">
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
                    <li><a href="clienttransactions.php?c=<?php echo $CustomerID ?>">Transactions</a>
                    </li>
                    <li class="active"><a href="clientstatement.php?c=<?php echo $CustomerID ?>">Statement</a>
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


                <?php if ($Access == 1) { ?>

                <div class="col-lg-12" style="padding-top: 10px">
                    <h4 style="padding-bottom: 10px">Customer Statement Dates</h4>
                    <div class="col-md-6">
                        <div class="form-group row col-md-12">
                            <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">From Date
                                *</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control fromdate" id="fromdate" name="fromdate"
                                       placeholder="From Date" data-date-format="yyyy-mm-dd"
                                       value="<?php echo $FromDate ?>">
                            </div>
                        </div>

                        <div class="form-group row col-md-12">
                            <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">To Date
                                *</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control todate" id="todate" name="todate"
                                       placeholder="To Date" data-date-format="yyyy-mm-dd"
                                       value="<?php echo $ToDate ?>">
                            </div>
                        </div>

                        <div class="form-group row col-md-11">
                            <input type="button" class="btn btn-sm btn-default pull-right" value="Show"
                                   style="margin-right: 16px" onClick="ChangeStatementDate();">
                        </div>
                    </div>
                    <div class="col-md-6">

                    </div>
                    <div class="row">&nbsp;</div>
                    <h4 style="padding-bottom: 10px">Statement <?php echo $FromDate ?> - <?php echo $ToDate ?></h4>
                    <table width="100%" class="table table-striped table-bordered table-hover" style="font-size: 12px">
                        <thead>
                        <tr>

                            <th>Date</th>
                            <th>Reference</th>
                            <th>Description</th>
                            <th>Debit</th>
                            <th>Credit</th>


                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($OpeningBalance >= 0) {
                            $DebitTotal = $DebitTotal + $OpeningBalance;
                            ?>
                            <tr class="odd gradeX">
                                <td><?php echo $FromDate ?></td>
                                <td>OB</td>
                                <td>Opening Balance</td>
                                <td>R <?php echo number_format($OpeningBalance, 2) ?></td>
                                <td></td>


                            </tr>
                        <?php } else {
                            $OpeningBalance = $OpeningBalance * -1;
                            $CreditTotal = $CreditTotal + $OpeningBalance;
                            ?>
                            <tr class="odd gradeX">
                                <td><?php echo $FromDate ?></td>
                                <td>OB</td>
                                <td>Opening Balance</td>
                                <td></td>
                                <td>R <?php echo number_format($OpeningBalance, 2) ?></td>


                            </tr>
                        <?php } ?>
                        <?php
                        //HERE WE LOOP THE TRANSACTIONS ARRAY
                        //$CustomerStatementArray
                        //print_r($CustomerStatementArray);
                        if ($CustomerStatementArray != "") {
                            foreach ($CustomerStatementArray as $TransactionLine) {

                                if (round($TransactionLine["TransactionDetails"]) >= 0) {
                                    $ThisDate = $TransactionLine["Date"];
                                    $ThisReference = $TransactionLine["Reference"];
                                    $ThisDescription = $TransactionLine["Description"];
                                    $ThisCredit = $TransactionLine["Credit"];
                                    $ThisDebit = $TransactionLine["Debit"];
                                    $InvoiceStatus = $TransactionLine['InvoiceStatus'];
                                    //$ThisDebit = $TransactionLine["TransactionDetails"];

                                    $DebitTotal = $DebitTotal + $ThisDebit;
                                    $CreditTotal = $CreditTotal + $ThisCredit;


                                    if ($ThisDate != "") {
                                        ?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $ThisDate ?></td>
                                            <td><?php echo $ThisReference ?></td>
                                            <td><?php echo $ThisDescription ?></td>
                                            <?php if ($ThisDebit != "") { ?>
                                                <td>R<?php echo number_format($ThisDebit, 2) ?></td>
                                            <?php } else { ?>
                                                <td></td>
                                            <?php } ?>
                                            <?php if ($ThisCredit != "") { ?>
                                                <td>R<?php echo number_format($ThisCredit, 2) ?></td>
                                            <?php } else { ?>
                                                <td></td>
                                            <?php } ?>


                                        </tr>
                                    <?php }
                                }
                            }
                        } ?>

                        <tr class="odd gradeX">
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr class="odd gradeX">
                            <td></td>
                            <td></td>
                            <td align=""><strong>Totals</strong></td>
                            <td><strong>R<?php echo number_format($DebitTotal, 2) ?></strong></td>
                            <td><strong>R<?php echo number_format($CreditTotal, 2) ?></strong></td>
                        </tr>
                        <?php
                        if ($creadit_amount > 0) {
                            ?>
                            <tr class="odd gradeX">
                                <td></td>
                                <td></td>
                                <td align=""><strong>Credit Amount</strong></td>
                                <td></td>
                                <td>
                                    <strong>R<?php echo number_format($creadit_amount, 2) ?></strong>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>


                        <tr class="odd gradeX">
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <?php
                        $AccountBalance = $DebitTotal - $CreditTotal - $creadit_amount;

                        if ($AccountBalance > 0) {
                            ?>

                            <tr class="odd gradeX">
                                <td>&nbsp;</td>
                                <td></td>
                                <td align=""><strong>Closing Balance on <?php echo $ToDate ?></strong></td>
                                <td><strong>R<?php echo number_format($AccountBalance, 2)
                                        ?></strong></td>
                                <td></td>
                            </tr>
                        <?php } else {
                            $AccountBalance = $AccountBalance * -1;
                            ?>
                            <tr class="odd gradeX">
                                <td>&nbsp;</td>
                                <td></td>
                                <td align=""><strong>Closing Balance on <?php echo $ToDate ?></strong></td>
                                <td></td>
                                <td><strong>R <?php echo $AccountBalance ?></strong></td>
                            </tr>
                        <?php } ?>

                        </tbody>
                    </table>
                </div>
                <div class="col-md-12" style="margin-bottom: 50px" align="right">

                    <button class="btn btn-success" onClick="javascript: SendStatement();">Send Statement</button>
                    <a class="btn btn-danger"
                       href="showstatement.php?c=<?php echo $CustomerID ?>&from=<?php echo $FromDate ?>&to=<?php echo $ToDate ?>"
                       target="_blank">Preview Customer Statement</a>
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
            responsive: true,
            "order": [[0, "desc"]]
        });

        $('.fromdate').datepicker({
            dateFormat: 'yy-mm-dd'
        });

        $('.todate').datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });

    //MENU STUFF FOR PAGE

    document.getElementById("customermenu").className = 'active';
    document.getElementById("customermenucustomer").className = 'active';
</script>
</body>

</html>
