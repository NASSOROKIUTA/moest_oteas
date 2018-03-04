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
   <script src="/scripts/modules/schools/schoolController.js"></script>
   <script src="/scripts/modules/permits/permitsController.js"></script>
   <script src="/scripts/modules/applicants/applicantsController.js"></script>
   <script src="/scripts/services/webcam.min.js"></script>
   <script src="/scripts/services/ng-camera.js"></script>
	
   
   
   
    <script src="/scripts/authController.js"></script>
    <script src="/scripts/admin/rolesController.js"></script>
    <script src="/scripts/userController.js"></script>
    <script src="/scripts/AppController.js"></script>
    <script src="/scripts/modules/registrations/nhifRegistrationModal.js"></script>
    <script src="/scripts/modules/registrations/printCard.js"></script>
    <script src="/scripts/modules/registrations/printCardBima.js"></script>
    <script src="/scripts/modules/registrations/registrationModalCorpse.js"></script>
    <script src="/scripts/modules/registrations/patientController.js"></script>
    <script src="/scripts/admin/adminController.js"></script>
    <script src="/scripts/modules/nusring_care/nursingCareController.js"></script>
    <script src="/scripts/modules/nusring_care/nursingCareModal.js"></script>
    <script src="/scripts/modules/nusring_care/patientDischargedModal.js"></script>
    <script src="/scripts/modules/nusring_care/physicalExaminations.js"></script>
    <script src="/scripts/modules/nusring_care/postPatientsToTheatreModal.js"></script>
    <script src="/scripts/modules/nusring_care/wardManagementModal.js"></script>
    <script src="/scripts/modules/nusring_care/TimePicker.js"></script>
    <script src="/scripts/modules/mortuary/mortuaryController.js"></script>
    <script src="/scripts/modules/mortuary/mortuaryManagementModal.js"></script>
    <script src="/scripts/modules/mortuary/corpseDisposedModal.js"></script>
    <script src="/scripts/modules/mortuary/mortuaryCareModal.js"></script>
    <script src="/scripts/modules/laboratory/labController.js"></script>
    <script src="/scripts/modules/laboratory/equipmentsInfo.js"></script>
    <script src="/scripts/modules/laboratory/LabTestRequestPatient.js"></script>
    <script src="/scripts/modules/laboratory/printSampleNumberBarcode.js"></script>
    <script src="/scripts/regions/regionController.js"></script>
    <script src="/scripts/modules/clinic/ctc/ctcController.js"></script>
    <script src="/scripts/modules/clinic/ctc/ctcPatientQues.js"></script>
    <script src="/scripts/modules/clinic/ctc/ctcSetup.js"></script>
    <script src="/scripts/Exemption/exemptionController.js"></script>
    <script src="/scripts/Exemption/discountController.js"></script>
    <script src="/scripts/admin/userSettingController.js"></script>
    <script src="/scripts/regions/facilityController.js"></script>
    <script src="/scripts/regions/regionController.js"></script>
    <script src="/scripts/Pharmacy/PharmacyController.js"></script>
    <script src="/scripts/Pharmacy/PharmacyItemsController.js"></script>
    <script src="/scripts/Pharmacy/SubStoreItemsController.js"></script>
    <script src="/scripts/Pharmacy/DispensingController.js"></script>
    <script src="/scripts/Pharmacy/PrescriptionController.js"></script>
    <script src="/scripts/modules/clinic/TB/TbController.js"></script>
    <script src="/scripts/modules/clinic/TB/Tb_data.js"></script>
    <script src="/scripts/modules/clinic/Pediatric/PediatricController.js"></script>
    <script src="/scripts/modules/clinic/VCT/VctController.js"></script>
    <script src="/scripts/modules/clinic/VCT/Vct_data.js"></script>
    <script src="/scripts/modules/clinic/Medical/MedicalController.js"></script>
    <script src="/scripts/modules/clinic/Orthopedic/OrthopedicController.js"></script>
    <script src="/scripts/BloodBank/BloodBankController.js"></script>
    <script src="/scripts/RCH/Anti_natalController.js"></script>
    <script src="/scripts/RCH/PostnatalController.js"></script>
    <script src="/scripts/RCH/LabourController.js"></script>
    <script src="/scripts/RCH/Child_Controller.js"></script>
    <script src="/scripts/RCH/Family_planning_Controller.js"></script>
    <script src="/scripts/RCH/Rch_reoprtController.js"></script>
    <script src="/scripts/Payment_type/payment_typeController.js"></script>
    <script src="/scripts/Item_setups/itemSetupController.js"></script>
    <script src="/scripts/Item_setups/itemPriceController.js"></script>
    <script src="/scripts/modules/payments/paymentsController.js"></script>
    <script src="/scripts/modules/payments/receiptsController.js"></script>
    <script src="/scripts/modules/payments/shopController.js"></script>
    <script src="/scripts/modules/payments/printReceipt.js"></script>
    <script src="/scripts/modules/payments/posReceipts.js"></script>
    <script src="/scripts/modules/payments/reportsController.js"></script>
    <script src="/scripts/modules/clinicalServices/opdController.js"></script>
    <script src="/scripts/modules/clinicalServices/opdQueueController.js"></script>
    <script src="/scripts/modules/clinicalServices/admissionModal.js"></script>
    <script src="/scripts/modules/clinicalServices/ipdController.js"></script>
    <script src="/scripts/modules/clinicalServices/ipdQueueController.js"></script>
    <script src="/scripts/modules/icu/icuController.js"></script>
    <script src="/scripts/modules/icu/icuModals.js"></script>
    <script src="/scripts/modules/clinic/dental/dentalHomeController.js"></script>
    <script src="/scripts/modules/clinic/dental/dentalController.js"></script>
    <script src="/scripts/modules/clinic/eye/eyeHomeController.js"></script>
    <script src="/scripts/modules/clinic/eye/eyeController.js"></script>
    <script src="/scripts/modules/clinic/surgical/surgicalHomeController.js"></script>
    <script src="/scripts/modules/clinic/surgical/surgicalController.js"></script>
    <script src="/scripts/modules/clinic/obgy/obgyHomeController.js"></script>
    <script src="/scripts/modules/clinic/obgy/obgyController.js"></script>
    <script src="/scripts/modules/referral/referralController.js"></script>
    <script src="/scripts/modules/insurance/insuranceController.js"></script>
    <script src="/scripts/modules/insurance/claimsModal.js"></script>
    <script src="/scripts/modules/referral/referralModal.js"></script>
    <script src="/scripts/modules/laboratory/laboratorySettingController.js"></script>
    <script src="/scripts/modules/laboratory/patientresultcontroller.js"></script>
    <!-- Radiology Module   -->
    <script src="/scripts/radiology/radiologyController.js"></script>
    <script src="/scripts/radiology/radiopatientsController.js"></script>
    <script src="/scripts/radiology/queManagementModal.js"></script>
    <script src="/scripts/radiology/radiologyDepartmentController.js"></script>
    <script src="/scripts/radiology/radiologyViewController.js"></script>
    <script src="/scripts/radiology/radiologyTestController.js"></script>
    <script src="/scripts/radiology/deviceModal.js"></script>
    <!--Emergency Module    -->
    <script src="/scripts/modules/emergency/emergencyController.js"></script>
    <script src="/scripts/modules/emergency/emergencyModal.js"></script>
    <script src="/scripts/modules/emergency/urgencyModal.js"></script>
    <script src="/scripts/modules/emergency/emergencyprintCard.js"></script>
    <script src="/scripts/modules/emergency/emergencydepartmentController.js"></script>
    <script src="/scripts/modules/emergency/normalRegistrationController.js"></script>
    <script src="/scripts/modules/emergency/treatmentDepartmentController.js"></script>
    <script src="/scripts/modules/emergency/casualtyRoomController.js"></script>
    <script src="/scripts/modules/emergency/observationRoomController.js"></script>
    <script src="/scripts/patient_tracing/patient_tracerController.js"></script>
	 <script src="/scripts/Environmental/EnvironmentalController.js"></script>
    <script src="/scripts/modules/inventory/inventoryController.js"></script>
    <script src="/scripts/modules/inventory/inventoryClientController.js"></script>
    <!-- Vital Sign-->
    <script src="/scripts/modules/VitalSign/VitalSignController.js"></script>
    <script src="/scripts/modules/VitalSign/vitalModal.js"></script>
    <!-- Cardiac Clinic   -->
    <script src="/scripts/modules/clinic/cardiac/cardiacController.js"></script>
    <!-- Diabetic Clinic   -->
    <script src="/scripts/modules/clinic/diabetic/diabeticController.js"></script>
    <!-- Physiotherapy Controller   -->
    <script src="/scripts/modules/clinic/physiotherapy/physioController.js"></script>
    <script src="/scripts/modules/clinic/physiotherapy/physiotherapyController.js"></script>
    <!-- Diabetic Clinic   -->
    <script src="/scripts/modules/clinic/diabetic/diabeticClinicController.js"></script>
    <!-- Dermatology Clinic   -->
    <script src="/scripts/modules/clinic/dermatology/dermatologyController.js"></script>
	
	<!--nutrition -->
	<script src="/scripts/modules/clinic/Nutrition/NutritionController.js"></script>
	   <script src="/scripts/General_Appointment/General_AppointmentController.js"></script>
	<!-- Psychiatric  Controller   -->
    <script src="/scripts/modules/clinic/psychiatric/psychiatricHomeController.js"></script>
   
	
	
	
    <!--  Models  -->
    <script src="/scripts/services/Helpers.js"></script>
    
    <script src="/scripts/services/models.js"></script>
    <script src="/scripts/directives/hamburger.js"></script>
    <script src="/scripts/directives/avatar.js"></script>
</body>

</html>
