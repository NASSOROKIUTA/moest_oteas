<!doctype html>
<html ng-app="authApp">

<head>
    <meta charset="utf-8">
    <title>OTEAS</title>

    <link href="/css/roboto-fontface/css/roboto/roboto-fontface.css" rel="stylesheet">
    <link rel="stylesheet" href="/bower_components/sweetalert2/dist/sweetalert2.css">
    <link rel="stylesheet" href="/css/bootstrap.css">

    <link rel="stylesheet" href="/css/design.css">
 
    <link rel="stylesheet" href="/bower_components/angular-toastr/dist/angular-toastr.css">
    <link rel="stylesheet" href="/bower_components/material-design-lite/material.css">
    <link rel='stylesheet' href='/css-materials/material.css?family=Roboto:400,700,300|Material+Icons'
        type='text/css'>
    <!-- angular material css put last to hopefully overwrite the previous ones -->
    <link rel="stylesheet" href="/bower_components/angular-material/angular-material.min.css">
    <link rel="stylesheet" type="text/css" href="/css/ang-accordion.css">
    <link rel="stylesheet" type="text/css" href="/css/angularjs-datetime-picker.css">

    <link rel='stylesheet' href="/css/loading-bar.min.css" type="text/css" media="all" />
    <link href="/css/v-accordion.css" rel="stylesheet" />

    <!-- datatables -->
    <link rel="stylesheet" href="/css/angular-datatables.css">
    <link rel="stylesheet" href="/css/datatable_style.css">

    <link href="css/zoomer.css" rel="stylesheet" type="text/css">

    <style type="text/css">
        body{
            font-family: 'Roboto','Helvetica','san-sarif';
        }
        /**
       * hide when angular is not yet loaded and initialized
       */
        
        [ng\:cloak],
        [ng-cloak],
        [data-ng-cloak],
        [x-ng-cloak],
        .ng-cloak,
        .x-ng-cloak {
            display: none !important;
        }
		

		
    </style>
</head>

<body layout="row" ng-controller="AppController" class="dashboard" ng-cloak>
	    <div id="main-ui-view" ui-view layout="row" flex>
	

	
	</div>
    <script src="/js/highchart.js"></script>
    <script src="/js/export.js"></script>
    <script src="/bower_components/MDBootstrap/js/jquery-3.1.1.js"></script>

<!--
    disable scrollbar
    <script src="/js/scrollbar.min.js"></script>
-->

    <script src="/bower_components/es6-promise/es6-promise.auto.min.js"></script>
    <script src="/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="/bower_components/angular/angular.min.js"></script>
    <script src="/bower_components/angular-ui-router/release/angular-ui-router.min.js"></script>
    <script src="/bower_components/satellizer/dist/satellizer.js"></script>
    <script src="/bower_components/angular-bootstrap/ui-bootstrap.min.js"></script>
    <script src="/bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js"></script>
    <script src="/bower_components/pdfobject/pdfobject.js"></script>
    <script src="/bower_components/angularUtils-pagination/dirPagination.js"></script>
    <script src="/bower_components/chart.js/dist/Chart.min.js"></script>
    <script src="/bower_components/angular-animate/angular-animate.js"></script>
    <script src="/bower_components/angular-aria/angular-aria.js"></script>
    <script src="/bower_components/angular-messages/angular-messages.min.js"></script>
    <script src="/bower_components/angular-perfect-scrollbar/src/angular-perfect-scrollbar.min.js"></script>
    <script src="/bower_components/material-design-lite/material.min.js"></script> 
    <script src="/bower_components/angular-material/angular-material.min.js"></script>
    <script src="/bower_components/angular-material-icons/angular-material-icons.js"></script>
    <script src="/bower_components/angular-chart.js/dist/angular-chart.min.js"></script>
    <script src="/bower_components/angular-toastr/dist/angular-toastr.tpls.js"></script>
    <script src="/bower_components/material-design-lite/material.min.js"></script>
    <script src="/bower_components/ng-chatbox/build/chatbox.min.js"></script>
    <script type="text/javascript" src="/js/ng-accordion.js"></script>
	<script type='text/javascript' src='/js/loading-bar.min.js'></script>
    <script type="text/javascript" src="/js/moment.js"></script>
    <script src="/js/v-accordion.js"></script>




    <!-- forked version of datetime picker -->

    <script src="/js/angularjs-datetime-picker.js"></script>
    <!-- databables -->
    <script src="/js/jquery.dataTables.min.js"></script>
    <script src="/js/angular-datatables.min.js"></script>



<!--    <script src="/scripts_build/scripts.js"></script>-->
    <script src="/bower_components/ng-chatbox/build/chatbox.min.js"></script>
    <script src="/scripts/app.js"></script>
	<!--OTEAS -->
   <script src="/scripts/modules/schools/js/schoolController.js"></script>
   <script src="/scripts/modules/permits/permitsController.js"></script>
   <script src="/scripts/modules/applicants/applicantsController.js"></script>
   <script src="/scripts/services/webcam.min.js"></script>
   <script src="/scripts/services/ng-camera.js"></script>
	
   
   
   
    <script src="/scripts/authController.js"></script>
    <script src="/scripts/admin/rolesController.js"></script>
    <script src="/scripts/admin/adminController.js"></script>   
    <script src="/scripts/userController.js"></script>
    <script src="/scripts/AppController.js"></script>
      <!--  Models  -->
    <script src="/scripts/services/Helpers.js"></script>
    
    <script src="/scripts/services/models.js"></script>
    <script src="/scripts/directives/hamburger.js"></script>
    <script src="/scripts/directives/avatar.js"></script>
</body>

</html>
