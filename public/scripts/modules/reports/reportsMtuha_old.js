(function () {

    'use strict';

    var app = angular.module('authApp');

    app.controller('reportsMtuha',

        ['$filter', '$scope', '$http', '$rootScope', '$uibModal', '$mdDialog',
            function ($filter, $scope, $http, $rootScope, $uibModal, $mdDialog) {

                var facility_id = $rootScope.currentUser.facility_id;
                var user_id = $rootScope.currentUser.id;
                $scope.cancel = function () {
                    $mdDialog.hide();
                };


                $scope.getNHIFdashboard = function () {
                    var reportsDrugs = {
                        "facility_id": facility_id,
                        "start_date": '2017-01-01',
                        "end_date": '2017-07-07'
                    };

                    $http.post('/api/reportsDrugs', reportsDrugs).then(function (data) {

                        var drugsOutOfStock = data.data[0];
                        var object = {'drugsOutOfStock': drugsOutOfStock};
                        var modalInstance = $uibModal.open({
                            templateUrl: '/views/modules/insurance/insurance.html',
                            size: 'lg',
                            animation: true,
                            controller: 'insuranceController',
                            windowClass: 'app-modal-window',
                            resolve: {
                                object: function () {
                                    return object;
                                }
                            }
                        });
                    });
                }


                $scope.showDrugsOutOfStock = function () {
                    var reportsDrugs = {
                        "facility_id": facility_id,
                        "start_date": '2017-01-01',
                        "end_date": '2017-07-07'
                    };

                    $http.post('/api/reportsDrugs', reportsDrugs).then(function (data) {

                        var drugsOutOfStock = data.data[0];
                        var object = {'drugsOutOfStock': drugsOutOfStock};
                        var modalInstance = $uibModal.open({
                            templateUrl: '/views/modules/reports/drugs.html',
                            size: 'lg',
                            animation: true,
                            controller: 'drugsMtuhaController',
                            windowClass: 'app-modal-window',
                            resolve: {
                                object: function () {
                                    return object;
                                }
                            }
                        });
                    });
                }


                $scope.showTestsOutOfStock = function () {
                    var reportsTests = {
                        "facility_id": facility_id,
                        "start_date": '2017-01-01',
                        "end_date": '2017-07-01'
                    };

                    $http.post('/api/reportsUnavailableTests', reportsTests).then(function (data) {

                        var testsOutOfStock = data.data[0];
                        var object = {'testsOutOfStock': testsOutOfStock};
                        var modalInstance = $uibModal.open({
                            templateUrl: '/views/modules/reports/lab_tests.html',
                            size: 'lg',
                            animation: true,
                            controller: 'labMtuhaController',
                            windowClass: 'app-modal-window',
                            resolve: {
                                object: function () {
                                    return object;
                                }
                            }
                        });
                    });
                }


                $scope.showBookFive = function () {

                    var reportsOPD = {"facility_id": facility_id, start_date: undefined, end_date: undefined};

                    $mdDialog.show({
                        controller: function ($scope, $rootScope) {
                            $scope.cancel =  function() {
                              $mdDialog.hide();
                            };
                            
							$scope.start_date = undefined;
							$scope.end_date = undefined;
							$scope.facility_id = facility_id;
							$scope.sno = 4;
							
							$scope.setSerial = function(index){
								if(index == 20)
									$scope.sno = 1;
							}
							
                            $scope.getReportBasedOnthisDate=function (dt_start,dt_end) {
								$scope.sno = 4;
                                var reportsOPD={"facility_id":facility_id,"start_date":dt_start,"end_date":dt_end};

                                $http.post('/api/getMahudhurioOPD',reportsOPD).then(function(data) {

                                    $scope.opd_mahudhurio_new = data.data[0][0];
									$scope.opd_mahudhurio_marudio = data.data[1][0];
									if(data.data[0].length > 0){
										$scope.opd_mahudhurio = $scope.opd_mahudhurio_new;
										if(data.data[1].length > 0)
											Object.keys($scope.opd_mahudhurio_marudio).forEach(function(key){
											$scope.opd_mahudhurio[key] = parseInt($scope.opd_mahudhurio[key]) +parseInt($scope.opd_mahudhurio_marudio[key]);});
									}else 
										$scope.opd_mahudhurio = $scope.opd_mahudhurio_marudio;
									
									$scope.opd_diagnosises = data.data[2];

                                });
                            }

							$scope.print = function(){
								var printer = window.open("", "Print");
								printer.document.writeln($('.to-print').html());
								printer.document.close();
								printer.focus();
								printer.print();
								printer.close();
								return;
								
								//$scope.html = $('.to-print').html();
								//window.open("","OPD_MTUHA");
								//$('#OPD_MTUHA').submit();
							}
							
							$scope.startup = function(){
								$scope.getReportBasedOnthisDate(undefined, undefined);
							}
							
							$scope.startup();
                            /*var reportsOPD = {"facility_id": facility_id};

                            $http.post('/api/getMahudhurioOPD', reportsOPD).then(function (data) {

                                $scope.opd_mahudhurio_new = data.data[0][0];
                                $scope.opd_mahudhurio_marudio = data.data[1][0];
									if(data.data[0].length > 0){
										$scope.opd_mahudhurio = $scope.opd_mahudhurio_new;
										if(data.data[1].length > 0)
											Object.keys($scope.opd_mahudhurio_marudio).forEach(function(key){
											$scope.opd_mahudhurio[key] = parseInt($scope.opd_mahudhurio[key]) +parseInt($scope.opd_mahudhurio_marudio[key]);});
									}else 
										$scope.opd_mahudhurio = $scope.opd_mahudhurio_marudio;
								
                                $scope.opd_diagnosises = data.data[2];

                            });
*/



                        },
                        templateUrl: '/views/modules/reports/opd.html',
                        parent: angular.element(document.body),
                        clickOutsideToClose: false,
                        fullscreen: $scope.customFullscreen // Only for -xs, -sm breakpoints.
                    });
                };

                $scope.showDoctorsPerfomances = function () {

                    $mdDialog.show({
                        controller: function ($scope, $rootScope) {
                            var reportsPerfomaces = {"facility_id": facility_id};
                            $http.get('/api/getLoginUserDetails/' + user_id).then(function (data) {
                                $scope.loginUserFacilityDetails = data.data;

                                console.log($scope.loginUserFacilityDetails);

                            });

                            $scope.getReportBasedOnthisDate = function (dt_start, dt_end) {
                                var reportsOPD = {
                                    "facility_id": facility_id,
                                    "start_date": dt_start,
                                    "end_date": dt_end
                                };
                                $scope.cancel = function () {
                                    $mdDialog.hide();
                                };
                                $http.post('/api/getDoctorsPerfomaces', reportsOPD).then(function (data) {

                                    $scope.DoctorsPerfomaces = data.data;

                                    $scope.start_date = dt_start;
                                    $scope.end_date = dt_end;


                                });
                            };

                            $http.post('/api/getDoctorsPerfomaces', reportsPerfomaces).then(function (data) {
                                $scope.DoctorsPerfomaces = data.data[0];
                            });

                        },
                        templateUrl: '/views/modules/reports/doctorsPerfomaces.html',
                        parent: angular.element(document.body),
                        clickOutsideToClose: false,
                        fullscreen: $scope.customFullscreen // Only for -xs, -sm breakpoints.
                    });

                };


                $scope.showDrugsExpired = function () {

                    $http.get('/api/expired/' + facility_id).then(function (data) {

                        var drugsExpired = data.data;
                        var object = {'drugsExpired': drugsExpired};
                        var modalInstance = $uibModal.open({
                            templateUrl: '/views/modules/reports/expiredDrugs.html',
                            size: 'lg',
                            animation: true,
                            controller: 'expiredDrugsMtuhaController',
                            windowClass: 'app-modal-window',
                            resolve: {
                                object: function () {
                                    return object;
                                }
                            }
                        });
                    });
                }

				$scope.showBookFourteen = function () {

                    var reportsIPD = {"facility_id": facility_id};

                    $mdDialog.show({
                        controller: function ($scope, $rootScope) {
                            $scope.cancel =  function() {
                              $mdDialog.hide();
                            };
                            $http.get('/api/getLoginUserDetails/'+user_id ).then(function(data) {
                                $scope.loginUserFacilityDetails=data.data;

                            });
							$scope.sno = 2;
                            $scope.getIPDReportBasedOnthisDate=function (dt_start,dt_end) {
                                $scope.sno = 2;
								var reportsIPD={"facility_id":facility_id,"start_date":dt_start,"end_date":dt_end};

                                $http.post('/api/getIpdReport',reportsIPD).then(function(data) {

                                    $scope.ipd_admissions = data.data[0][0];
									$scope.IPDDiagnoses = data.data[1];

                                });
                            }


                            var reportsIPD = {"facility_id": facility_id};

                            $http.post('/api/getIpdReport', reportsIPD).then(function (data) {

									$scope.ipd_admissions = data.data[0][0];
									$scope.IPDDiagnoses = data.data[1];

                            });




                        },
                        templateUrl: '/views/modules/reports/ipd.html',
                        parent: angular.element(document.body),
                        clickOutsideToClose: false,
                        fullscreen: $scope.customFullscreen // Only for -xs, -sm breakpoints.
                    });
                };

                $scope.showBookEleven = function () {
                    var ClinicReport = {
                        "facility_id": facility_id,
                        "start_date": '2017-01-01',
                        "end_date": '2017-07-07'
                    };

                    $http.post('/api/getDentalClinicReport', ClinicReport).then(function (data) {

                        var postedToClinic = data.data[0][0];
                        var object = {'postedToClinic': postedToClinic};
                        var modalInstance = $uibModal.open({
                            templateUrl: '/views/modules/reports/dental.html',
                            size: 'lg',
                            animation: true,
                            controller: 'dentalMtuhaController',
                            windowClass: 'app-modal-window',
                            resolve: {
                                object: function () {
                                    return object;
                                }
                            }
                        });
                    });
                }


                $scope.showBookSixteen = function () {
                    var ClinicReport = {
                        "facility_id": facility_id,
                        "start_date": '2017-01-01',
                        "end_date": '2017-07-07'
                    };

                    $http.post('/api/getEyeClinicReport', ClinicReport).then(function (data) {

                        var postedToClinic = data.data[0][0];
                        var object = {'postedToClinic': postedToClinic};
                        var modalInstance = $uibModal.open({
                            templateUrl: '/views/modules/reports/eye.html',
                            size: 'lg',
                            animation: true,
                            controller: 'eyeMtuhaController',
                            windowClass: 'app-modal-window',
                            resolve: {
                                object: function () {
                                    return object;
                                }
                            }
                        });
                    });
                }


                $scope.cancel = function () {
                    //console.log('done and cleared');
                    $uibModalInstance.dismiss();

                }


                $scope.closeAllModals = function () {
                    //console.log('done and cleared');
                    $uibModalInstance.dismissAll();

                }

            }]);


}());