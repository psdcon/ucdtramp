<!DOCTYPE html>
<html lang="en" ng-app="bbook">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <!-- <link rel="shortcut icon" href="favicon.ico"> -->

    <title>Bounce Book</title>

    <!-- Styles -->
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">

    <link href="plugins/select2/css/select2.min.css" rel="stylesheet">
</head>

<body>
    <!-- Top navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#/">Bounce Book</a>
            </div>
            <div class="navbar-collapse collapse">
                <form class="navbar-form navbar-right">
                    <input type="text" class="form-control" placeholder="Search...">
                </form>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="" onClick="return false;" data-toggle="modal" data-target="#login-modal">Login</a></li>
                    <li><a href="#profile">Profile</a></li>
                    <li><a href="#settings">Settings</a></li>
                    <li><a href="#help">Help</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- login modal html -->
    <div class="modal fade" id="login-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Login</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label for="username" class="col-sm-2 control-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="username" placeholder="Username">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pass" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="pass" placeholder="Password">
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10" id="error">
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Sign in</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar container -->
            <div class="col-sm-3 col-md-2 sidebar" style="background-color:rgb(51, 51, 51);">
                <ul class="nav nav-sidebar">
                    <li><a href="#/">Dashboard</a></li>
                    <li><a href="#browse/all">Browse</a></li>
                    <li><a href="#tree">Tree</a></li>
                </ul>
                <div style="color:white;font-size:1.5em;">Manage</div>
                <ul class="nav nav-sidebar">
                    <li><a href="#add"><span class="glyphicon glyphicon-plus"></span> Add skill</a></li>
                    <li><a href="#edit/all"><span class="glyphicon glyphicon-pencil"></span> Edit skill</a></li>
                    <li><a href="#more">More navigation</a></li>
                </ul>
            </div>

            <!-- Main content -->
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <div class="page" ng-view>

                </div>
            </div>
        </div>
        h</div>

    <!-- JavaScript (Placed at the end of the document so pages load faster)
    ======================================================= -->
    <script src="node_modules/jquery/dist/jquery.js"></script>
    <script src="node_modules/angular/angular.js"></script>
    <script src="node_modules/angular-route/angular-route.js"></script>
    <script src="node_modules/angular-animate/angular-animate.js"></script>
    <script src="js/bbook.js"></script>

    <script src="node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <script src="plugins/select2/js/select2.min.js"></script>

</body>

</html>