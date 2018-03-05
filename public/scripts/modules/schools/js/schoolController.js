/**
 * Created by Mazigo Jr on 2017-04-10.
 */
(function () {
    'use strict';
    var app = angular.module('authApp');
    app.controller('schoolController',['$scope','$http','$rootScope',
        function ($scope,$http,$rootScope) {
			var user_name = $rootScope.currentUser.id;
			
			
			 var formdata = new FormData();
					
			$scope.getExcelFiles = function ($files) {
            angular.forEach($files, function (value, key) {
                formdata.append(key, value);

            });
            formdata.append('uploaded_by', user_name);
        };
			
			$scope.getSpecialNeeds=function(special){
				if(special==1){
				$scope.show_special_needs=true;	
				}
				else{
				$scope.show_special_needs=false;	
					
				}
				
			};
			
			
			$scope.teachersRequirements = function () {

            var request = {
                method: 'POST',
                url: '/api/teachersRequirementPerSchool',
                data: formdata,
                headers: {
                    'Content-Type': undefined
                }

            };

            // SEND THE FILES.
            $http(request).then(function (responses) {
            	if(responses.data.status==200){
            	  return sweetAlert('',responses.data.message,'success');
            	}
            	else{
            	 return sweetAlert('',responses.data.message,'error');	
            	}
                 
              }).then(function () {
                	 
                });
            	               
        };

        /**
		
		$scope.teachersRequirementsSecondary = function () {

            var request = {
                method: 'POST',
                url: '/api/secondaryTeachersRequirementPerSchool',
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
                .error(function (error) {
                	  return sweetAlert('','something went wrong','error');
                }).finally(function (error) {
                	  return sweetAlert('','something went wrong','error');
                });
        };
        **/
			
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
          
		  
		  
		   $http.post('/api/getSetupData').then(function(data) {
                    $scope.school_levels = data.data[0];
                    $scope.special_needs = data.data[1];
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