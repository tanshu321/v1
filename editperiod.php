<?php
include("includes/webfunctions.php");
include('includes/agent.php');
$agent->init();


//SECURITY
$ThisClientID = $_SESSION["ClientID"];
$ThisUser = $_SESSION["ClientName"];
$ThisSecret = $_SESSION["SiteSecret"];
$months = array('',
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July ',
    'August',
    'September',
    'October',
    'November',
    'December',
);
if ($ThisClientID > 0 && $ThisUser != "" && $ThisSecret == "E2A_crm_S5gdbh6nnj_usr_9898") {
    $ThisPeriodID = $_REQUEST["c"];

    $PeriodDetails = GetPeriodSetup($ThisPeriodID);

    while ($Val = mysqli_fetch_array($PeriodDetails)) {

        $title = $Val["title"];
        $description = $Val["description"];
        $month = $Val["month"];
        $contact_account = $Val["contact_account"];
        $gdc = $Val['gdc'];
    }

    $CustomerCustom = GetCustomerCustomFields();
    $NumFields = mysqli_num_rows($CustomerCustom);

    //NEW SECURITY CHECK FOR THIS MODULE, SKIP IF MAIN CLIENT
    if ($_SESSION["MainClient"] == 1) {
        $Access = 1;
    } else {
        $Access = CheckPageAccess('Period Setup');
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

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">


        function UpdatePeriod() {
            var title = document.getElementById("title").value;
            var month = document.getElementById("month").value;
            var description = document.getElementById("description").value;
            var contact_account = document.getElementById("contact_account").value;
            var gdc = document.getElementById("gdc").value;

            if (title != "" && month != "0" && description != "" && contact_account != "" && gdc!='0') {
                var DoUpdate = agent.call('', 'AddPeriod', '', title, month, description, contact_account,gdc,'<?php
                    echo $ThisPeriodID;?>');
                if (DoUpdate == "OK") {
                    document.location = 'periodsetup.php';
                }
                else {
                    bootbox.alert(DoUpdate);
                }
            }
            else {
                bootbox.alert("Please fill in all fields to save the Field");
            }
        }



        function CancelPeriod(){
            document.location = 'periodsetup.php';
        }

    </script>
</head>

<body>

<div id="wrapper">

    <?php include("navigation.php") ?>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Period Setup<img src="images/logo.png"
                                                                   class="img-responsive pull-right"
                                                                   style="height: 45px"></h1>
            </div>

            <!-- /.col-lg-12 -->
        </div>
        <div class="alert alert-info">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong><i class="fa fa-info-circle fa-fw" style="font-size: 16px"></i></strong> This page allows
            you to Edit your Period.
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">

                <ul class="nav nav-tabs" style="margin-bottom: 20px">
                    <li><a href="jobcardsetup.php"><i class="fa fa-caret-right"></i> Company Setup</a>
                    </li>
                    <li class="active"><a href="periodsetup.php"><i class="fa fa-caret-right"></i> Period Setup</a>
                    </li>
                </ul>


                <?php if ($Access == 1) { ?>

                <div class="col-lg-12 tab-content">

                    <h4>Edit Period - <?php echo $title ?></h4>

                    <div class="form-group row col-md-6">
                        <label for="fieldname" class="col-sm-5 col-form-label" style="padding-top: 5px">Period Number
                            *</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="title" placeholder="Title"
                                   value="<?php echo $title ?>">
                        </div>
                    </div>

                    <div class="form-group row col-md-6">
                        <label for="surname" class="col-sm-5 col-form-label" style="padding-top: 5px">Month
                            *</label>
                        <div class="col-sm-6">

                            <select id='month' class="form-control" >
                                <?php
                                for($i=0;$i<count($months);$i++){
                                    $monthName = $months[$i];
                                    if(empty($monthName)){
                                        $monthName = "Select Month";
                                    }
                                    $selected = "";
                                    if($month ==$i)
                                        $selected = "selected";
                                    ?>
                                    <option value='<?php echo $i;?>' <?php echo $selected;?>><?php echo $monthName;
                                    ?></option>
                                    <?php

                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row col-md-6">
                        <label for="companyname" class="col-sm-5 col-form-label" style="padding-top: 5px">Invoice Description *</label>
                        <div class="col-sm-6">
                           <textarea name="description" id="description" class="form-control"
                                     placeholder="Description"><?php echo $description;?></textarea>
                        </div>
                    </div>

                    <div class="form-group row col-md-6">
                        <label for="tel" class="col-sm-5 col-form-label" style="padding-top: 5px">Contra account
                            *</label>
                        <div class="col-sm-6">
                            <input type="text" name="contact_account" id="contact_account" class="form-control"
                                   placeholder="Contact Account" value="<?php echo $contact_account?>">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group row col-md-6">
                        <label for="surname" class="col-sm-5 col-form-label" style="padding-top: 5px">GDC
                            *</label>
                        <div class="col-sm-6">
                            <?php
                            $gdcArray=array("G","D","C");
                            ?>
                            <select id='gdc' class="form-control" >
                                <option value="0">Select GDC</option>
                                <?php
                                for($i=0;$i<count($gdcArray);$i++){
                                    $selected = "";
                                    if($gdc == $gdcArray[$i])
                                        $selected = "selected";
                                    ?>
                                    <option value='<?php echo $gdcArray[$i];?>' <?php echo $selected;?>><?php echo
                                        $gdcArray[$i];?></option>
                                    <?php

                                }
                                ?>

                            </select>
                        </div>
                    </div>



                    <div class="form-group row col-md-12" align="center" style="padding-top: 40px">
                        <button class="btn btn-default" onClick="javascript: UpdatePeriod();">Update Period Details
                        </button>
                        <button class="btn btn-default btn-danger" onClick="javascript: CancelPeriod();">Cancel</button>
                    </div>

                </div>
                <!-- /.table-responsive -->


            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->


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

    //MENU STUFF FOR PAGE
    document.getElementById("setupmenu").className = 'active';
    document.getElementById("setupcustomermenu").className = 'active';
</script>

</body>

</html>
