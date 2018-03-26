/**
 * Created by NASSORO KIUTA Jr on 2017-12-18.
 */
(function () {
    'use strict';
    var app = angular.module('authApp');
    app.controller('applicationsController',['$scope','$http','$rootScope','Helper','$mdDialog','$window',
        function ($scope,$http,$rootScope,Helper,$mdDialog,$window) {
			var user_name = $rootScope.currentUser.id;
			var user_id = $rootScope.currentUser.id;
			var department_id = $rootScope.currentUser.department_id;
			var applicant_id = $rootScope.currentUser.applicant_id;
			var council_id = $rootScope.currentUser.council_id;
			var selectedSchool ={};
			var council_name ="";

			  $scope.getRegisteredCandidate = function (searchWord) {
  return Helper.getRegisteredCandidate(searchWord)
                .then(function (response) {
                    return response.data;
                });
            
           };

			
					
			$scope.getListSelectedToCouncils= function(){
			$http.post('/apps/getListSelectedToCouncils').then(function(data) {
				$scope.selectedApplicants=data.data;		
		});
					
			};

			$scope.generateReports= function(){
			$http.post('/api/generateReports').then(function(data) {
				$scope.reports=data.data;		
		                });					
			};

			$scope.exportAsExcel= function(){
			$http.post('/api/exportAsExcel').then(function(data) {
				$scope.reports=data.data;
               location.href = "../../../excel/LIST-SELECTED-TO-SCHOOLS.xls";
						
		                });					
			};

			


			
			
			$scope.getListSelectedToSchools= function(){
			$http.post('/apps/getListSelectedToSchools').then(function(data) {
				$scope.selectedApplicantsToSchools=data.data;		
		});
		
					
			};
			
	$scope.getListSelectedToThisCouncil= function(){
				
   $http.post('/apps/getListSelectedToThisCouncil',{council_id:council_id}).then(function(data) {
				$scope.selectedApplicantsToDEDS=data.data;		
		});				
	};
			
			$scope.getListSelectedToThisCouncil();
					
				
			$scope.selectionsDone = [];
			
			$scope.getSelectedCouncil= function(council_id){
				
				var postData={council_id:council_id};
				$http.post('/api/getSchoolWithRequirements',postData).then(function(data) {
				$scope.schools=data.data;
				});
			};

           var focusedSchool =[];
			$scope.getSelectedSchool = function(school){
              if(angular.isDefined(school)==false){
              	return sweetAlert("Please Select school","","error");
              }

              var postData={school:school};             
               $http.post('/api/getSelectedSchool',postData).then(function(data) {
				focusedSchool=data.data[0];
				});
               return focusedSchool;

			};
			
			 $scope.removeFromSelection = function(item, objectdata) {

                var indexremoveobject = objectdata.indexOf(item);

                objectdata.splice(indexremoveobject, 1);

            }
			
			$scope.viewMoreDetails = function(selectedApplicant){
                    $mdDialog.show({                 
                        controller: function ($scope) {
                         $scope.applicant=selectedApplicant;
                            $scope.cancel = function () {
                                 $mdDialog.hide();
                            };
							
							
							
							var _video = null,
        patData = null;

    $scope.patOpts = {x: 0, y: 0, w: 25, h: 25};

    // Setup a channel to receive a video property
    // with a reference to the video element
    // See the HTML binding in main.html
    $scope.channel = {};

    $scope.webcamError = false;
    $scope.onError = function (err) {
        $scope.$apply(
            function() {
                $scope.webcamError = err;
            }
        );
    };

    $scope.onSuccess = function () {
        // The video element contains the captured camera data
        _video = $scope.channel.video;
        $scope.$apply(function() {
            $scope.patOpts.w = _video.width;
            $scope.patOpts.h = _video.height;
            //$scope.showDemos = true;
        });
    };

    $scope.onStream = function (stream) {
        // You could do something manually with the stream.
    };

	 $scope.photoDiscarded=true;
	$scope.makeSnapshot = function(applicant_id) {
		$scope.photoDiscarded=false;	
		$scope.applicant_id=applicant_id;	
        if (_video) {
            var patCanvas = document.querySelector('#snapshot');
            if (!patCanvas) return;

            patCanvas.width = _video.width;
            patCanvas.height = _video.height;
            var ctxPat = patCanvas.getContext('2d');

            var idata = getVideoData($scope.patOpts.x, $scope.patOpts.y, $scope.patOpts.w, $scope.patOpts.h);
            ctxPat.putImageData(idata, 0, 0);

            sendSnapshotToServer(patCanvas.toDataURL());

            patData = idata;
			
		 // Generate the image data
    var Pic = document.getElementById("snapshot").toDataURL("image/png");
    Pic = Pic.replace(/^data:image\/(png|jpg);base64,/, "");

    // Sending the image data to Server
		var applicant_id=$scope.applicant_id;		
    $.ajax({
        type: 'POST',
        url: '/apps/saveApplicantPhoto',
        data: '{ "applicant_id":"'+applicant_id+'","applicant_image" : "' + Pic + '" }',
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        success: function (msg) {
            alert("Done, Picture Uploaded.");
        }
    });
			
           }
    };
    
    /**
     * Redirect the browser to the URL given.
     * Used to download the image by passing a dataURL string
     */
    $scope.downloadSnapshot = function downloadSnapshot(dataURL) {
        window.location.href = dataURL;
    };
    
    var getVideoData = function getVideoData(x, y, w, h) {
        var hiddenCanvas = document.createElement('canvas');
        hiddenCanvas.width = _video.width;
        hiddenCanvas.height = _video.height;
        var ctx = hiddenCanvas.getContext('2d');
        ctx.drawImage(_video, 0, 0, _video.width, _video.height);
        return ctx.getImageData(x, y, w, h);
    };

    /**
     * This function could be used to send the image data
     * to a backend server that expects base64 encoded images.
     *
     * In this example, we simply store it in the scope for display.
     */
    var sendSnapshotToServer = function sendSnapshotToServer(imgBase64) {
        $scope.snapshotData = imgBase64;
    };
	
	$scope.discardPhoto=function(){
		$scope.photoDiscarded=true;	
		
	}
	
	$scope.savePicture=function(){
		var postData={applicant_id:$scope.applicant_id,applicant_image:$scope.snapshotData};

$http.post('/apps/saveApplicantPhoto',postData).then(function(data) {
				$scope.councils=data.data;
				});		
		
	}
	
	//end..
		         },
				templateUrl: '/views/templates/ded/posted_applicant.html',
                parent: angular.element(document.body),
                clickOutsideToClose: false,
                fullscreen: $scope.customFullscreen // Only for -xs, -sm breakpoints.
						});
               
            };
			
			
			$scope.changeFromSelection = function(application, applications) {
     		         var application=application;
			         var selections=applications;
               $mdDialog.show({                 
                        controller: function ($scope) {
                         $scope.application=application;
                         $scope.selections=selections;
                           $scope.cancel = function () {
                                 $mdDialog.hide();
                            };
							
							 $scope.getCouncil=function(region){
					var postData={region_id:region};
		$http.post('/api/getCouncil',postData).then(function(data) {
				$scope.councils=data.data;
				});
				
			}; 
			
			$scope.modifySelections= function(selections){
				
		var postData={edit:1,selections:selections,applicant_id:applicant_id};
		$http.post('/apps/makeApplications',postData).then(function(data) {
				$scope.application=data.data;
				$scope.selectionsDone=null;
				$window.location.reload();
				$scope.cancel();
				
				return sweetAlert("Requests,successfully Saved","","success");
				});
				
			};
			
			$scope.getSelectedCouncil= function(council_id){
				var postData={council_id:council_id};
				$http.post('/api/getCouncil',postData).then(function(data) {
				$scope.selectedCouncil=data.data;
				});
			};
						
        $scope.editSelections= function(choice,selections,application){
				var region_name=$scope.region.region_name;
				var region_id=$scope.region.id;
				var council_id=choice;
				$scope.selectionsDone=[];
				$scope.selections=selections;
				//var council_name=choice.council_name;
			if($scope.selectionsDone.length ==1){
				 swal("Only One selection is allowed","","info");
                        return;	
					
				}
				
				  var countRegion=0;
				 for (var i = 0; i < $scope.selections.length; i++)
                 if ($scope.selections[i].region_id == region_id) {
                   countRegion++;
                    } 
					
					if(countRegion > 1){
			swal(" Only two selections allowed for "+region_name,"","info");
                        return;	
					}
					
					
					
					for (var i = 0; i < $scope.selections.length; i++)
                    if ($scope.selections[i].council_id == choice) {
                swal($scope.selectedCouncil[0].council_name + ' ' + "already in your selection list!","","info");
                        return;
                    }
				
			$scope.selectionsDone.push({
                    "region_name": region_name,
                    "region_id": region_id,
                    "council_id":council_id,
                    "application_id":application.application_id,
                    "council_name":$scope.selectedCouncil[0].council_name

                });
				
			};

						
			$scope.getRegion = function (text) {
            return Helper.getRegion(text)
                .then(function (response) {
                    return response.data;
                });
        };

         

 			
				$scope.saveCredentials = function (login,candidate,username) {                        
						
		        if(angular.isDefined(login)==false){
					return sweetAlert('Please enter Password ','','error');	
				}						
				if(password_retype != password){
		               return sweetAlert('Password mismatch','','error');					
				}						
				var applicant_id=candidate.id;
				var gender=candidate.gender;
				var mobile_number=candidate.mobile_number;
				var name=candidate.first_name+" "+candidate.last_name;
				var password=login.password;
				var password_retype=login.retype_pwd;				
	            var postData={mobile_number:mobile_number,gender:gender,password:password,applicant_id:applicant_id,email:username,name:name,user_type:10};
						
			$http.post('/apps/register',postData).then(function(data) {
				$scope.results=data.data;				
			});
		
					
                            };
						},
				templateUrl: '/views/templates/applicants/change-applications.html',
                parent: angular.element(document.body),
                clickOutsideToClose: false,
                fullscreen: $scope.customFullscreen // Only for -xs, -sm breakpoints.
						});

            }

			
			$scope.saveSelections= function(selections){
				console.log(selections);
				
		var postData={selections:selections,applicant_id:applicant_id};
		$http.post('/apps/makeApplications',postData).then(function(data) {
				$scope.application=data.data;
				$scope.selectionsDone=null;
				return sweetAlert("Requests,successfully Saved","","success");
				});
				
			};

			$scope.setGender=function(gender){
              $scope.gender=gender;
			};

			

		
			$scope.getApplications= function(){
			var postData={applicant_id:applicant_id};
		$http.post('/apps/getApplications',postData).then(function(data) {
				$scope.applications=data.data;
				
		});
						
			}();
			
			
	    	$scope.addSelections= function(choice,selectedSchool){

				var region_name=focusedSchool.region_name;
				var region_id=focusedSchool.region_id;
				var council_id=focusedSchool.council_id;
				council_name=focusedSchool.council_name;
				var school_name=focusedSchool.school_name;
				var chanceRequired=0;

				if($scope.selectionsDone.length ==4){
				 swal("Only Four selections are allowed","","info");
                        return;						
				}
				var countRegion=0;
				for (var i = 0; i < $scope.selectionsDone.length; i++)
                if ($scope.selectionsDone[i].region_id == region_id) {
                countRegion++;
                } 
				if(countRegion > 1){
					swal("Only two selections allowed for "+region_name,"","info");
                        return;	
				}
					
				for (var i = 0; i < $scope.selectionsDone.length; i++)
                if ($scope.selectionsDone[i].centre_number == focusedSchool.centre_number) {
                swal(school_name + ' ' + "already in your selection list!","","info");
                        return;
                }
					var postData={centre_number:focusedSchool.centre_number};
		$http.post('/apps/getRequirementStatus',postData).then(function(data) {
				chanceRequired=data.data[0].required_teachers;
			  	$scope.selectionsDone.push({
                    "region_name": region_name,
                    "region_id": focusedSchool.region_id,
                    "council_id":focusedSchool.council_id,
                    "council_name":council_name,
                    "school_name":school_name,
                    "centre_number":focusedSchool.centre_number,
                    "required_teachers":chanceRequired
                });
			});
				
			};
			
			
			
			
			
			
	  $scope.getCouncil=function(region){
					var postData={region_id:region};
		$http.post('/api/getCouncil',postData).then(function(data) {
				$scope.councils=data.data;
				});
				
			}; 

  $scope.getCouncilRequirements=function(region){
	   var postData={region_id:region};
		$http.post('/api/getCouncilRequirements',postData).then(function(data) {
				$scope.councilsWithRequirements=data.data;
				});
				
			}; 
										 //Residence
        $scope.getRegion = function (text) {
            return Helper.getRegion(text)
                .then(function (response) {
                    return response.data;
                });
        };

       
	 //Residence
        $scope.getRegionRequirements = function (text) {
            return Helper.getRegionRequirements(text)
                .then(function (response) {
                    return response.data;
                });
        };
        
		
		 $scope.selectedRegion = function (region) {
			 if(angular.isDefined(region)==true){
              var region_id=region.region_id;
              $scope.region=region;
			  $scope.getCouncilRequirements(region_id);
			  
			 }
			  return;
        };


$scope.exportExcel = function () {
	var requestData={filename:'applicants',fileExt:'xls'};
	var header="application/vnd.ms-excel;";
    $http.post('/api/downloadExcel',requestData, {responseType:'arraybuffer',headers:header
        })
            .then(function (response) {
               $scope.showDownload=true;
            });
};






		

            

        }]);

})();