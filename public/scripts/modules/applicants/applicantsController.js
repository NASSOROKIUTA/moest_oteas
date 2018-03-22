/**
 * Created by NASSORO KIUTA Jr on 2017-12-18.
 */
(function () {
    'use strict';
    var app = angular.module('authApp');
    app.controller('applicantsController',['$scope','$http','$rootScope','Helper','$mdDialog','$window',
        function ($scope,$http,$rootScope,Helper,$mdDialog,$window) {
			var user_name = $rootScope.currentUser.id;
			var user_id = $rootScope.currentUser.id;
			var department_id = $rootScope.currentUser.department_id;
			var applicant_id = $rootScope.currentUser.applicant_id;
			var council_id = $rootScope.currentUser.council_id;
			var selectedSchool ={};
			var council_name ="";
			
			$scope.getApplications= function(){	
			  var postData={department_id:department_id};
			  if(department_id==null){
			  	return sweetAlert("Sorry You are not authorized to view any list of applications","Contact Administrator");
			  }				
		      $http.post('/apps/getApplicationLists',postData).then(function(data) {
				$scope.getApplicationLists=data.data;				
		           });						
		    }();


			
			$scope.getListSelectedToCouncils= function(){
			$http.post('/apps/getListSelectedToCouncils').then(function(data) {
				$scope.selectedApplicants=data.data;		
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
		/**
		 $scope.selectedRegion = function (region) {
              var region_id=region.id;
              $scope.region=region;
			  $scope.getCouncil(region_id);
        };	
					
		**/					
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

			
			$scope.makePlacementPrimary= function(selections){
				if(angular.isDefined($scope.gender)==false){
					return sweetAlert("Please Select Gender","","error");
				}
			  var postData={gender:$scope.gender,selections:selections,user_id:user_id};
		$http.post('/apps/makePlacementPrimary',postData).then(function(data) {
			$scope.application=data.data;
			return swal(data.data,"","info");
                        
				
				});
				
			};


			
			$scope.makePlacementPrimarySchool= function(selections){
			var postData={selections:selections,user_id:user_id};
 $http.post('/apps/makePlacementPrimarySchool',postData).then(function(data) {
			$scope.application=data.data;
			return sweetAlert("Placement ,was successfully Saved","","success");
				});
				
			};
			/**
			$scope.getApplications= function(){
			var postData={applicant_id:applicant_id};
		$http.post('/apps/getApplications',postData).then(function(data) {
				$scope.applications=data.data;
				
		});
						
			};
			**/
			
	    	$scope.addSelections= function(choice,selectedSchool){

				var region_name=focusedSchool.region_name;
				var region_id=focusedSchool.region_id;
				var council_id=focusedSchool.council_id;
				council_name=focusedSchool.council_name;
				var school_name=focusedSchool.school_name;
			if($scope.selectionsDone.length ==5){
				 swal("Only five selections are allowed","","info");
                        return;	
					
				}
				
				  var countRegion=0;
				 for (var i = 0; i < $scope.selectionsDone.length; i++)
                    if ($scope.selectionsDone[i].region_id == region_id) {
                   countRegion++;
                    } 
					
					if(countRegion > 1){
					swal(" Only two councils allowed for "+region_name,"","info");
                        return;	
					}
					
					
					
					for (var i = 0; i < $scope.selectionsDone.length; i++)
                    if ($scope.selectionsDone[i].council_id == focusedSchool.council_id) {
                swal(selectedSchool.council_name + ' ' + "already in your selection list!","","info");
                        return;
                    }
				
			$scope.selectionsDone.push({
                    "region_name": region_name,
                    "region_id": focusedSchool.region_id,
                    "council_id":focusedSchool.council_id,
                    "council_name":council_name,
                    "school_name":school_name,
                    "centre_number":focusedSchool.centre_number
                });
				
			};
			
			
			$scope.addRegionsForPlacement= function(){
				var region_name=$scope.region.region_name;
				var region_id=$scope.region.region_id;
			
			if($scope.selectionsDone.length > 5){
				 swal("Not more than five regions are allowed at once to make placement to run","","info");
                        return;	
					
				}
				
				  var countRegion=0;
				 for (var i = 0; i < $scope.selectionsDone.length; i++)
                    if ($scope.selectionsDone[i].region_id == region_id) {
                   countRegion++;
                    } 
					
			if(countRegion >= 1){
			swal(region_name+",Already selected in your list","","info");
                        return;	
					}
					
								
			$scope.selectionsDone.push({
                    "region_name": region_name,
                    "region_id": region_id,
                  
                });
				
			};
			$scope.schoolSelectionsDone=[];
			$scope.addRegionsForSchoolPlacement= function(){
				var region_name=$scope.region.region_name;
				var region_id=$scope.region.id;
				
			
			if($scope.schoolSelectionsDone.length > 5){
				 swal("Not more than five regions are allowed at once to make placement to run","","info");
                        return;	
					
				}
				
				  var countRegion=0;
				 for (var i = 0; i < $scope.schoolSelectionsDone.length; i++)
                    if ($scope.schoolSelectionsDone[i].region_id == region_id) {
                   countRegion++;
                    } 
					
			if(countRegion >= 1){
			swal(region_name+",Already selected in your list","","info");
                        return;	
					}
					
								
			$scope.schoolSelectionsDone.push({
                    "region_name": region_name,
                    "region_id": region_id,
                  
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






			/**
			
			$scope.exportExcel=function(exportData){
				
					
    $http({
        method: 'GET',
        url: '/api/downloadExcel/xls',
        headers:"application/vnd.ms-excel;"
    }).then(function (data) {
      
	   var filename="DataMining";
				 
			var linkElement = document.createElement('a');
        try {
            
 var blob = new Blob([data], 
                    {type: "application/vnd.ms-excel;"});
			var url = window.URL.createObjectURL(blob);
 
            linkElement.setAttribute('href', url);
            linkElement.setAttribute("download", filename);
 
            var clickEvent = new MouseEvent("click", {
                "view": window,
                "bubbles": true,
                "cancelable": false
            });
            linkElement.dispatchEvent(clickEvent);
        } catch (ex) {
            console.log(ex);
        }	 
				 
				 
				 
    }).then(function (data) {
        var header = headers('content-disposition');
    var result = header.split(';')[1].trim().split('=')[1];
    return result.replace(/"/g, '');
    });
			
	}; 
			**/
	   var formdata = new FormData();
					
			$scope.getExcelFiles = function ($files) {
            angular.forEach($files, function (value, key) {
                formdata.append(key, value);

            });
            formdata.append('uploaded_by', user_name);
        };
		
		
		// NOW UPLOAD THE FILES.
        $scope.applicantsUpload = function () {

            var request = {
                method: 'POST',
                url: '/api/schoolUpload',
                data: formdata,
                headers: {
                    'Content-Type': undefined
                }

            };

            // SEND THE FILES.
            $http(request).then(function (data) {
                //console.log(request);
             return sweetAlert('',data.data,'success');


            })
                .then(function () {
                });
        }; 
		
		
			
	$scope.getSubjects=function(){
	$http.post('/api/getSubject').then(function(data) {
				$scope.subjects=data.data;
		});
				
			};
			
			$scope.saveSchool=function(school){
				if(angular.isDefined(school.centre_number)==false){
					return sweetAlert('Please Fill Centre number','','error');
				}
  
   else if(school.is_special_need ==1 && angular.isDefined(school.special_needs_type)==false){
			return sweetAlert('Please enter Special needs ','','error');
				}
  
				var centre_number=school.centre_number;
				var council_id=3;
				var residence_id=school.residence_id;
				var school_level=school.school_level;
				var school_name=school.school_name;
				var special_needs=school.is_special_need;
				var special_needs_type=school.special_needs_type;
				var day_boarding=school.day_boarding;
				var distance_km=school.distance_km;
				var teaching_language=school.teaching_language;
				var department_id=school.department_id;
				var postData={department_id:department_id,centre_number:centre_number,council_id:council_id,residence_id:residence_id,school_level:school_level,school_name:school_name,special_needs:special_needs,special_needs_type:special_needs_type,day_boarding:day_boarding,distance_km:distance_km,teaching_language:teaching_language};
				
		$http.post('/api/saveSchool',postData).then(function(data) {
          if(data.data.status==1){
return 	sweetAlert(data.data.data,'','success');	
			}
		else{			
			return 	sweetAlert(data.data.data,'','error');	
			
			}
                    

					});
			};
			
			$scope.savePermit=function(permit){
   if(angular.isDefined(permit)==false){
					return sweetAlert('Please Fill the permits','','error');
				}
  
   else if(angular.isDefined(permit.council)==false){
			return sweetAlert('Please Select Council','','error');
				}
   else if(angular.isDefined(permit.gender)==false){
			return sweetAlert('Please Select Gender','','error');
				}
   else if(angular.isDefined(permit.subject)==false){
			return sweetAlert('Please Select Subject','','error');
				} 
	else if(angular.isDefined(permit.permits)==false){
			return sweetAlert('Please Enter number of employees allowed','','error');
				}
  
				var council_id=permit.council;
				var subject=permit.subject;
				var gender=permit.gender;
				var dept_id=permit.dept_id;
				var permits=permit.permits;
				
	var postData={permits:permits,dept_id:dept_id,council_id:council_id,subject_id:subject,council_id:council_id,gender:gender};
				
		$http.post('/api/savePermit',postData).then(function(data) {
          if(data.data.status==1){
return 	sweetAlert(data.data.data,'','success');	
			}
		else{			
			return 	sweetAlert(data.data.data,'','error');	
			
			}
                    

					});
			};
			
			$scope.getSchool=function(keyWord){
				if(angular.isDefined(keyWord)==false){
					return sweetAlert('Please Select KeyWord ','','error');
				}
  
     			var filter_by=keyWord.filter_by;
     			var fieldName=keyWord.fieldName;

	    var postData={searchWord:filter_by,field_name:fieldName};
				
		$http.post('/api/getSchool',postData).then(function(data) {         			
			$scope.schools=data.data;	
			
			    });
			};
			
			$scope.getPermits=function(keyWord){
				if(angular.isDefined(keyWord)==false){
					return sweetAlert('Please Select KeyWord ','','error');
				}
  
     			var filter_by=keyWord.filter_by;
     			var fieldName=keyWord.fieldName;

	    var postData={searchWord:filter_by,field_name:fieldName};
				
		$http.post('/api/getPermits',postData).then(function(data) {         			
			$scope.permits=data.data;	
			
			    });
			};
          
		  
		  
		   $http.post('/api/getRegions').then(function(data) {
                    $scope.regions = data.data;
                      });
		  
		  var patientCategory =[];
            $scope.searchPatientCategory = function(searchKey) {
                $http.get('/api/searchPatientCategory/'+searchKey).then(function(data) {
                    patientCategory = data.data;
                });
                return patientCategory;
            };
            $scope.getPricedItems=function (patient_category_selected) {
                //console.log(patient_category_selected);
	var dataPost={patient_category:patient_category_selected,facility_id:facility_id};
    $http.post('/api/getPricedItems',dataPost).then(function(data) {
                    $scope.services=data.data;
                });

            };

            

        }]);

})();