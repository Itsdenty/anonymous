
<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">
<title>SendMunk: Create Campaigns</title>
<!-- Favicon-->
<link rel="icon" href="assets/favicon.png" type="image/x-icon">
<link rel="stylesheet" href="../assets/plugins/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/plugins/jvectormap/jquery-jvectormap-2.0.3.min.css"/>
<link rel="stylesheet" href="../assets/plugins/morrisjs/morris.min.css" />
<!-- Custom Css -->
<link  rel="stylesheet" href="assets/css/main.css">
<link rel="stylesheet" href="assets/css/color_skins.css">
</head>
<body class="theme-red">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="../assets/images/logo-svg.svg" width="55" height="55" alt="SendMunk"></div>
        <p>SendMunk Loading...</p>
    </div>
</div>

<!-- Overlay For Sidebars -->
<div class="overlay"></div>

<!-- Top Bar -->
<nav class="navbar p-l-5 p-r-5">
    <ul class="nav navbar-nav navbar-left">
        <li class="float-right">
            <a href="sign-in.html" class="mega-menu" data-close="true"><i class="zmdi zmdi-power"></i></a>
        </li>
    </ul>
</nav>

<!-- Left Sidebar -->
<aside id="leftsidebar" class="sidebar">
    <ul class="nav">
        <li class="nav-item"><a class="nav-link active" href="#dashboard"><img class="img-responsive" src="assets/SM_Avi.png"></a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane stretchRight active" id="dashboard">
            <div class="menu">
                <ul class="list">
                    <li>
                        <div class="user-info">
                            <div class="detail">
                                <h4>Name</h4>
                            </div>
                        </div>
                    </li>
                        <li> <a href="javascript:void(0);" class="menu-toggle header"><span>Subaccount</span></a>
                            <ul class="ml-menu">
                                <li> <a href="ec-dashboard.html">Default</a></li>
                            </ul>
                        </li>
                        <li class="active open"> <a href="campaigns.html"><i class="material-icons">alarm</i><span>Campaigns</span></a>
                        </li>
                        <li> <a href="sequences.html"><i class="material-icons">timeline</i><span>Sequences</span> </a>
                        </li>
                        <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-assignment"></i><span>Forms</span> </a>
                            <ul class="ml-menu">
                                <li> <a href="basic forms.html">Basic Forms</a></li>
                                <li> <a href="polls.html">Polls</a></li>
                                <li> <a href="quizzes.html">Quizzes</a></li>
                                <li> <a href="calculators.html">Calculators</a></li>
                            </ul>
                        </li>
                        <li> <a href="push-notifications.html"><i class="material-icons">touch_app</i><span>Push Notifications</span> </a>
                        <li> <a href="javascript:void(0);"><i class="material-icons">account_circle</i><span>Contacts</span> </a>
                        </li>
                        <li> <a href="javascript:void(0);"><i class="material-icons">view_module</i><span>Templates</span> </a>
                        </li>
                        <li> <a href="javascript:void(0);" class="menu-toggle"><i class="material-icons">autorenew</i><span>Automation</span> </a>
                            <ul class="ml-menu">
                                <li> <a href="#">Visual Automation</a> </li>
                                <li> <a href="#">RSS Feeds</a> </li>
                            </ul>
                        </li>
                        <li> <a href="javascript:void(0);" class="menu-toggle"><i class="material-icons">settings</i><span>Settings</span> </a>
                            <ul class="ml-menu">
                            <li><a href="subaccount.html">Subaccount</a> </li>
                            <li><a href="team.html">Team</a> </li>
                            <li><a href="email-settings.html">Sender Email Settings</a> </li>
                            <li><a href="sms-mms-settings.html">SMS/MMS Settings</a> </li>
                            <li><a href="integration.html">Integration</a> </li>
                            <li><a href="contact-attribute.html">Contact Attribute</a> </li>
                            <li><a href="account-settings.html">Account Settings</a> </li>
                            </ul>
                        </li>
                        <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-lock"></i><span>Authentication</span> </a>
                            <ul class="ml-menu">
                                <li><a href="sign-in.html">Sign In</a> </li>
                                <li><a href="sign-up.html">Sign Up</a> </li>
                            </ul>
                        </li>
                        <li>
                            <div class="progress-container progress-primary m-t-10">
                                <span class="progress-badge">Activity this Month</span>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100" style="width: 67%;">
                                        <span class="progress-value">67%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="progress-container progress-info">
                                <span class="progress-badge">Subscription</span>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="86" aria-valuemin="0" aria-valuemax="100" style="width: 86%;">
                                        <span class="progress-value">86%</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
            </div>
        </div>
    </div>
</aside>

<section class="content">
    <div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-6 col-sm-12">
                <h2>Campaigns
                <small>Create and send campaigns in a jiffy</small>
                </h2>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">
                <ul class="breadcrumb float-md-right">
                    <li class="breadcrumb-item"><a href="index.html"><i class="zmdi zmdi-home"></i> SendMunk</a></li>
                    <li class="breadcrumb-item"><a href="campaigns.html">Campaigns</a></li>
                    <li class="breadcrumb-item active">Create Campaign</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Let's get started!</strong></h2>
                    <ul class="header-dropdown">
                        <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                            <ul class="dropdown-menu">
                                <li><a href="javascript:void(0);">Action</a></li>
                                <li><a href="javascript:void(0);">Another action</a></li>
                                <li><a href="javascript:void(0);">Something else</a></li>
                            </ul>
                        </li>
                        <li class="remove">
                            <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <!-- Nav tabs -->
                    <ul class="nav">
                        <li class="nav-item"><a class="nav-link" href="#form"><i class="material-icons">description</i> Form </a></li>
                    </ul>

                    <div class="row clearfix" id="form">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card">
                                    <div class="header row clearfix">
                                        <button type="button" class="btn btn-default waves-effect m-l-20">Filter Recipients</button>
                                    </div>
                                    <div class="body">
                                        <form>
                                            <div class="row clearfix">
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="Campaign name">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        <input type="email" class="form-control" placeholder="Sender's email">
                                                    </div>
                                                </div>
                                                <div class="dropdown dropright" style="cursor:pointer;">
                                                        <a class="dropdown-toggle" data-toggle="dropdown">
                                                          Sending Integration<i class="material-icons">arrow_drop_down</i>
                                                        </a>
                                                        <div class="dropdown-menu">
                                                          <a class="dropdown-item" href="#">SMTP</a>
                                                          <a class="dropdown-item" href="#">Mail API</a>
                                                          <a class="dropdown-item" href="#">Webhook</a>
                                                        </div>
                                                      </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix" id="editor">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <ul class="nav">
                        <li class="nav-item"><a class="nav-link" href="#editor"><i class="material-icons">mode_edit</i> Choose Editor </a></li>
                    </ul>
                    <div class="header row clearfix">
                        <button type="button" class="btn btn-default waves-effect m-l-20">Personalize campaign with...</button>

                    <label class="checkbox-inline" style="margin-left: 40%">
                        <input type="checkbox" data-toggle="toggle"> AB Test
                    </label>
                    </div>
                    <div class="body">
                        <form>
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Email Subject">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="container mb-5 mt-5">
                        <div class="pricing card-deck flex-column flex-md-row mb-3">
                            <div class="card card-pricing popular shadow text-center px-3 mb-4">
                                <span class="h6 w-60 mx-auto px-4 py-1 rounded-bottom bg-secondary text-white shadow-sm"><i class="material-icons">mode_edit</i>Editor One</span>
                                <div class="bg-transparent card-header pt-4 border-0">
                                    <span class="h6 w-60 mx-auto text-black"><i class="material-icons">code</i></span>
                                    <h3 class="h3 font-weight-normal text-muted text-center mb-10">Rich Text Editor/Paste your code</h3>
                                </div>
                                <div class="card-body pt-0">
                                    <a href="https://www.totoprayogo.com" target="_blank" class="btn btn-primary mb-3">Choose</a>
                                </div>
                            </div>
                            <div class="card card-pricing popular shadow text-center px-3 mb-4">
                                <span class="h6 w-60 mx-auto px-4 py-1 rounded-bottom bg-secondary text-white shadow-sm"><i class="material-icons">mode_edit</i>Editor Two</span>
                                <div class="bg-transparent card-header pt-4 border-0">
                                        <span class="h6 w-60 mx-auto text-black"><i class="material-icons">highlight</i></span>
                                        <h3 class="h3 font-weight-normal text-muted text-center mb-10">Drag and Drop Editor</h3>
                                </div>
                                <div class="card-body pt-0">
                                    <a href="https://www.totoprayogo.com" target="_blank" class="btn btn-primary mb-3" style ="margin-top:3rem !important;">Choose</a>
                                </div>
                            </div>
                        <div>
                    </div>
                </div>
            </div>
        </div>
</div>
</div>
</div>
</div>
    <!-- #END# Tabs With Icon Title -->
    </div>
</section>


<!-- Jquery Core Js -->
<script src="assets/bundles/libscripts.bundle.js"></script> <!-- Lib Scripts Plugin Js ( jquery.v3.2.1, Bootstrap4 js) -->
<script src="assets/bundles/vendorscripts.bundle.js"></script> <!-- slimscroll, waves Scripts Plugin Js -->

<script src="assets/bundles/morrisscripts.bundle.js"></script><!-- Morris Plugin Js -->
<script src="assets/bundles/jvectormap.bundle.js"></script> <!-- JVectorMap Plugin Js -->
<script src="assets/bundles/knob.bundle.js"></script> <!-- Jquery Knob, Count To, Sparkline Js -->

<script src="assets/bundles/mainscripts.bundle.js"></script>
<script src="assets/js/pages/index.js"></script>

</body>
</html>
