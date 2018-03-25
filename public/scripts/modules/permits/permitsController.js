/**
 * Created by NASSORO KIUTA Jr on 2017-12-17.
 */
(function () {
    'use strict';
    var app = angular.module('authApp');
    app.controller('permitsController',['$scope','$http','$rootScope',
        function ($scope,$http,$rootScope) {

        	var user_name = $rootScope.currentUser.id;
        	var formdata = new FormData();
					
			$scope.getExcelFiles = function ($files) {
            angular.forEach($files, function (value, key) {
                formdata.append(key, value);
            });
            formdata.append('uploaded_by', user_name);
            };

            	$scope.loadPermits = function () {
            var request = {
                method: 'POST',
                url: '/api/loadPermits',
                data: formdata,
                headers: {
                    'Content-Type': undefined
                }
               };
             // SEND THE FILES.
              $http(request).then(function (responses) {
            	if(responses.data.status==200){
            	  return sweetAlert('',responses.data.data,'success');
            	}
            	else{
            	 return sweetAlert('','Some data failed to be enrolled please inform admin for support','error');	
            	}                 
               }).then(function () {
                	 
                });
            	               
        };
			
	  $scope.getCouncil=function(region){
				console.log(region);
				var postData={region_id:region};
		$http.post('/api/getCouncil',postData).then(function(data) {
				$scope.councils=data.data;
				});
				
			};  

       $scope.generatePermits=function(region){
       	var postData={region_id:region};
		$http.post('/api/generatePermits',postData).then(function(data) {
		 $scope.permitSuggestions=data.data;
		 });
				
			};  



			$scope.showSubjects=function(showStatus){
				$scope.showSubject=false;
				if(showStatus ==2){
				$scope.showSubject=true;
				}
				return;
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
   else if(angular.isDefined(permit.subject)==false && permit.dept_id==2){
			return sweetAlert('Please Select Subject','','error');
				}  
   else if(angular.isDefined(permit.dept_id)==false){
			return sweetAlert('Please Select Department','','error');
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