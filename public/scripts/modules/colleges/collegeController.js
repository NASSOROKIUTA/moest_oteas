/**
 * Created by NASSORO KIUTA Jr on 2017-12-17.
 */
(function () {
    'use strict';
    var app = angular.module('authApp');
    app.controller('collegeController',['$scope','$http','$rootScope',
        function ($scope,$http,$rootScope) {
        	var user_name = $rootScope.currentUser.id;

        	var formdata = new FormData();
					
			$scope.getExcelFiles = function ($files) {
            angular.forEach($files, function (value, key) {
                formdata.append(key, value);
            });
            formdata.append('uploaded_by', user_name);
            };
			
	       $scope.getCollegesRegistered=function(actionPerformed){
		    var postData={fieldName:actionPerformed.fieldName,filter_by:actionPerformed.filter_by};
		     $http.post('/api/getCollegesRegistered',postData).then(function(data) {
				$scope.colleges=data.data;
				});				
			 };  

		   
			$scope.saveColleges = function () {
            var request = {
                method: 'POST',
                url: '/api/saveColleges',
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


			

	        

        }]);

})();