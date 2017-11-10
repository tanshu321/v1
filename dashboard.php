<?php
session_start();

if ($_SESSION["Remember"] == "true")
{
	$ThisUserName = $_SESSION["ClientEmail"];
	$year = time() + 31536000;
	setcookie('remember_me_crm_user', $ThisUserName, $year);	
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
	$AccessLogs = GetLatestAccess();
	$ClientTask = GetIncompleteTask();
	$FollowUps = GetIncompleteFollowUps();
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

</head>

<body>

    <div id="wrapper">

        <?php include("navigation.php"); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Dashboard   <img src="images/logo.png" class="img-responsive pull-right" style="height: 45px"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Income Overview
                            
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="morris-area-chart"></div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    
                        
                    
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-8 -->
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bell fa-fw"></i> Customer Follow Up
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group">
                                <?php while ($Val = mysqli_fetch_array($FollowUps))
								{
									$CustomerName = $Val["FirstName"] . " " . $Val["Surname"];
									$CompanyName = $Val["CompanyName"];
									$CustomerID = $Val["CustomerID"];
									
									if ($CompanyName != "")
									{
										$CustomerName .= " (" . $CompanyName . ")";	
									}
									
									$Description = $Val["Description"];
									$FollowUpDate = $Val["FollowUpDate"];
									
								?>
                                <a href="clientinfo.php?c=<?php echo $CustomerID ?>" class="list-group-item">
                                	<h5><i class="fa fa-user fa-fw"></i> <?php echo $CustomerName ?></h5>
                                     <?php echo $Description ?>
                                    <span class="pull-right text-muted small"><em><?php echo $FollowUpDate ?></em>
                                    </span>
                                </a>
                                <?php } ?>
                                
                            </div>
                            <!-- /.list-group -->
                            <a href="dashboardfollowup.php" class="btn btn-default btn-block">View All Follow Ups</a>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    
                    <!-- /.panel -->
                    
                    <!-- /.panel .chat-panel -->
                </div>
                <!-- /.col-lg-4 -->
                
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-calendar fa-fw"></i> Customer Incomplete Task
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group">
                                <?php while ($Val = mysqli_fetch_array($ClientTask))
								{
									$CustomerName = $Val["FirstName"] . " " . $Val["Surname"];
									$CompanyName = $Val["CompanyName"];
									$CustomerID = $Val["CustomerID"];
									
									if ($CompanyName != "")
									{
										$CustomerName .= " (" . $CompanyName . ")";	
									}
									
									$TaskDescription = $Val["TaskDescription"];
									$TaskDate = $Val["TaskDate"];
									
								?>
                                <a href="clientinfo.php?c=<?php echo $CustomerID ?>" class="list-group-item">
                                	<h5><i class="fa fa-user fa-fw"></i> <?php echo $CustomerName ?></h5>
                                     <?php echo $TaskDescription ?>
                                    <span class="pull-right text-muted small"><em><?php echo $TaskDate ?></em>
                                    </span>
                                </a>
                                <?php } ?>
                                
                            </div>
                            <!-- /.list-group -->
                            <a href="dashboardtask.php" class="btn btn-default btn-block">View All Task</a>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    
                    <!-- /.panel -->
                    
                    <!-- /.panel .chat-panel -->
                </div>
                <!-- /.col-lg-4 -->
                
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-info-circle fa-fw"></i> Latest Access Logs
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group">
                            	<?php while ($Val = mysqli_fetch_array($AccessLogs))
								{
									$LogType = $Val["LogType"];
									$AccessedBy = $Val["AccessName"];
									$LogDate = $Val["LogDate"];
									
									$CustomerName = $Val["FirstName"] . " " . $Val["Surname"];
									$CompanyName = $Val["CompanyName"];
									$CustomerID = $Val["CustomerID"];
									
									if ($CompanyName != "")
									{
										$CustomerName .= " (" . $CompanyName . ")";	
									}
								?>
                                <a href="clientinfo.php?c=<?php echo $CustomerID ?>" class="list-group-item">
                                	<h5><i class="fa fa-user fa-fw"></i> <?php echo $CustomerName ?></h5>
                                     <?php echo $AccessedBy ?> <?php echo $LogType ?>
                                    <span class="pull-right text-muted small"><em><?php echo $LogDate ?></em>
                                    </span>
                                </a>
                                <?php } ?>
                                
                            </div>
                            <!-- /.list-group -->
                            <a href="dashboardaccesslogs.php" class="btn btn-default btn-block">View All Access Logs</a>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    
                    <!-- /.panel -->
                    
                    <!-- /.panel .chat-panel -->
                </div>
                <!-- /.col-lg-4 -->
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

    <!-- Morris Charts JavaScript -->
    <script src="vendor/raphael/raphael.min.js"></script>
    <script src="vendor/morrisjs/morris.min.js"></script>
    <script src="data/morris-data.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>
    <script src="js/bootbox.js"></script>

</body>

</html>
