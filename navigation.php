<?php
$MainClient = $_SESSION["MainClient"];
?>

<!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="dashboard.php">Business CRM v1.0</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li style="margin-top: 15px">
                   
                        License Status : Active
                    
                    
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <!--<li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button" style="height: 34px">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                            <!-- /input-group -->
                        <!--</li> --> 
                        <li>
                            <a href="dashboard.php" id="dashboard"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <li id="customermenu">
                            <a href="#"><i class="fa fa-users fa-fw"></i> Customers<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="showclients.php" id="customermenucustomer">My Customers</a>
                                </li>
                               
                                
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        
                        <li id="suppliermenu">
                            <a href="#"><i class="fa fa-user fa-fw"></i> Suppliers<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="suppliersetup.php" id="setupsuppliermenu">My Suppliers</a>
                                </li>
                                
                               
                               
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        
                        <li id="jobcardmenu">
                            <a href="#"><i class="fa fa-cogs fa-fw"></i> Job Cards<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="jobcards.php" id="alljobcards">My Job Cards</a>
                                </li>
                                
                               
                               
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        
                        <li id="stockmenu">
                            <a href="#"><i class="fa fa-reorder fa-fw"></i> Products &amp; Services<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                             	<li>
                                    <a href="products.php" id="setupproductmenu">Product/Services</a>
                                </li>
                                <li>
                                    <a href="stockcontrol.php" id="stockcontrolrmenu">Stock Control</a>
                                </li>
                                <li>
                                    <a href="manufacturing.php" id="manufacturingmenu">Manufacturing</a>
                                </li>
                                
                               
                               
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        
                        
                        <li id="billing">
                            <a href="#"><i class="fa fa-table fa-fw"></i> Billing <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                <a href="allinvoices.php" id="allinvoices">Invoices</a>                                </li>
                                <li>
                                <a href="allquotes.php" id="allquotes">Quotes</a>                                </li>
                            </ul>
                        </li>
                        <li id="reports">
                            <a href="#"><i class="fa fa-edit fa-fw"></i> Reports<span class="fa arrow"></span></a>
                            	<ul class="nav nav-second-level">
                                    <li >
                                        <a href="outstandingreport.php"  id="outstanding">Outstanding Invoice Report</a>
                                    </li>
                                    <li >
                                        <a href="overduereport.php"  id="overduereport">Overdue Invoice Report</a>
                                    </li>
                                    <li >
                                        <a href="invoicereport.php"  id="invoicereport">Invoice Report</a>
                                    </li>
                                    <li >
                                        <a href="incomereport.php"  id="incomereport">Income Report</a>
                                    </li>
                                    <li >
                                        <a href="quotereport.php"  id="quoterport">Quote Report</a>
                                    </li>
                                    <li >
                                        <a href="salesreport.php"  id="sales">Sales By Product</a>
                                    </li>
                                    <li>
                                        <a href="statementsreport.php"  id="statements">Customer Statements</a>
                                    </li>
									 <li>
                                        <a href="customeragingreport.php"  id="statements">Customer Aging Report</a>
                                    </li>
                                    <li>
                                        <a href="stocktakereport.php"  id="stocktakereport">Stock Take Variance Report</a>
                                    </li>
                                    <li>
                                        <a href="jobcardreport.php"  id="jobcardreport">Job Card Report</a>
                                    </li>
                                    <li>
                                        <a href="technicianreport.php"  id="techreport">Technician Report</a>
                                    </li>
                                    <li>
                                        <a href="periodreport.php"  id="periodreport">Invoice Export</a>
                                    </li>
                                </ul>
                        </li>
                       <!-- <li  id="utilities">
                            <a href="#"><i class="fa fa-wrench fa-fw"></i> Utilities<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li id="calendartab">
                                    <a href="calendar.php">Calendar</a>
                                </li>
                                <li>
                                    <a href="todo.php">To-do List</a>
                                </li>
                                
                               
                            </ul>
                            <!-- /.nav-second-level -->
                        <!--</li> -->
                        
                       
                         <li id="setupmenu">
                            <a href="#"><i class="fa fa-cog fa-fw"></i> Setup<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="companysetup.php">Company Setup</a>
                                </li>
                                
                               
                              <li>
                                    <a href="customsetup.php" id="setupcustomermenu">Customer Setup</a>
                                </li>
                                <li>
                                    <a href="jobcardsetup.php" id="setupjobcardmenu">Jobcard Setup</a>
                                </li>
                                <li>
                                    <a href="employees.php" id="setupdepartmentmenu">Employee Management</a></li>
                                
                                <li>
                                    <a href="dashboardaccesslogs.php">Logs</a>
                                </li>
                               
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                       
                        
                        <li>
                            <a href="logout.php"><i class="fa fa-lock fa-fw"></i>Logout</a>
                            
                            <!-- /.nav-second-level -->
                        </li>
                    </ul>
                    
                    
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>