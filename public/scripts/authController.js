(function() {

  'use strict';

  angular
    .module('authApp')
    .controller('AuthController', AuthController);

  function AuthController($auth, $state, $http, $rootScope,$scope,toastr,$mdToast,$mdDialog,$mdBottomSheet,Helper) {
	    angular.element(document).ready(function () {
			
			        $scope.saveApp = function(apps) {
						
						console.log(apps);
						var admn_no = apps.admn_no;
						var college_id = apps.college;
						var form_grad_year = apps.form_grad_year;
						var grad_year = apps.grad_year;
						var index_number = apps.index_number;
			var postData ={admn_no:admn_no,college_id:college_id, form_grad_year:form_grad_year,grad_year:grad_year,index_number:index_number};
			
		$http.post('/apps/verifyApplicantInfo',postData).then(function(data) {
						 var applicant=data.data[0];  
						 $scope.statusResponses=data.data[1];  
						 var college=data.data[2];  
								
				if($scope.statusResponses==1){
				$mdDialog.show({                 
                        controller: function ($scope) {
                              $scope.candidate=applicant;
                              $scope.college=college;
							  $scope.apps=null;
                                $scope.cancel = function () {
                                 $mdDialog.hide();
                            };
							
							 //Residence
        $scope.getResidence = function (text) {
            return Helper.getResidence(text)
                .then(function (response) {
                    return response.data;
                });
        };
		
		 $scope.selectedResidence = function (residence) {
                   $scope.residence = residence;
        }
							
			$scope.saveAddress = function (apps,applicant) {
                                console.log(applicant);
								 
						var mobile_number = apps.mobile_number;
						var email = apps.email;
						var applicant_id = applicant.id;
						var residence_id = $scope.residence.residence_id;
						
			var postData ={applicant_id:applicant_id,mobile_number:mobile_number,email:email,               residence_id:residence_id};
						   
		$http.post('/apps/saveAddress',postData).then(function(data) {
				  var applicant_response=data.data;  
				  if(applicant_response==1){
					$scope.cancel();

					$mdDialog.show({                 
                        controller: function ($scope) {
                        $scope.candidate=applicant;
                        $scope.candidate=applicant;
       $scope.username=applicant.form_four_index+"/"+applicant.year_certified;
							  $scope.college=college;
							  $scope.apps=null;
                           $scope.cancel = function () {
                                 $mdDialog.hide();
                            };
				$scope.saveCredentials = function (login,candidate,username) {
                        //....
						console.log(login);
						console.log(candidate);
						console.log(username);
						
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
				templateUrl: '/views/templates/applicants/credentials.html',
                parent: angular.element(document.body),
                clickOutsideToClose: false,
                fullscreen: $scope.customFullscreen // Only for -xs, -sm breakpoints.
						});
					  
				  }else{
	return sweetAlert('Please enter correct details','','error');	
	  
					  
				  }
				  
				  });
						   
						   };
		
							
                        },
                 templateUrl: '/views/templates/applicants/address.html',
                        parent: angular.element(document.body),
                        clickOutsideToClose: false,
                         fullscreen: $scope.customFullscreen // Only for -xs, -sm breakpoints.
                    }); 
				}else{
return sweetAlert('WRONG INFORMATION',admn_no+'/'+grad_year,'error');	
				}

});					
			       	   
                      };    

					  $scope.loginForm = function() {
				
				$mdDialog.show({                 
                        controller: function ($scope) {
							
							
                              
                                $scope.cancel = function () {
                                 $mdDialog.hide();
                            };
                                $scope.submitCredentials = function (auths) {
                                console.log(auths);
								var vm = this;

    vm.loginError = false;

    vm.logout = function() {
        //console.log("logging out")
    };
	
	vm.login = function() {
        vm.Loads = true;

     
    };
								
								 var credentials = {
        email: auths.email,
        password: auths.password
      };

      $auth.login(credentials).then(function() {
         vm.Loads = true;

        // Return an $http request for the now authenticated
        // user so that we can flatten the promise chain
        return $http.get('/api/authenticate/user');

        // Handle errors
      }, function(error) {
        vm.loginError = true;
        $scope.error=error.data.error;
        vm.loginErrorText =error.data.error;
        toastr.error('',vm.loginErrorText);

        // Because we returned the $http.get request in the $auth.login
        // promise, we can chain the next promise to the end here
      }).then(function(response) {

	  if(angular.isDefined(response)==false){
		  return;
	  }
	  
        // Stringify the returned data to prepare it
        // to go into local storage
        var user = JSON.stringify(response.data.user);

        // Set the stringified user data into local storage
        localStorage.setItem('user', user);

        // The user's authenticated state gets flipped to
        // true so we can now show parts of the UI that rely
        // on the user being logged in
        $rootScope.authenticated = true;

        // Putting the user's data on $rootScope allows
        // us to access it anywhere across the app
        $rootScope.currentUser = response.data.user;

        var login_name=$rootScope.currentUser.name;
          $mdToast.show(
              $mdToast.simple()
                  .textContent('WELCOME  '+login_name)
                  .hideDelay(5000)
          );

        // Everything worked out so we can now redirect to
        // the users state to view the data
		$scope.cancel();
        $state.go('dashboard');
      });
								
                            };
							
							$scope.saveAddress = function () {
                                 return sweetAlert('Yes we have seen','','error');
                            };
		
							
                        },
                 templateUrl: '/views/templates/login.html',
                        parent: angular.element(document.body),
                        clickOutsideToClose: false,
                         fullscreen: $scope.customFullscreen // Only for -xs, -sm breakpoints.
                    });   
			       	   
                      };
			
			
			$http.post('/apps/getColleges').then(function(data) {
						 $scope.colleges=data.data[0];  
						 $scope.college_years=data.data[1];  
						 $scope.school_years=data.data[2];  
				
			});
                          });
	  

    
	
	$scope.searchCollege = function(SearchText) {
        //console.log("logging out")
       };
	   
	   
	   $scope.cancelSheet = function () {
            $mdBottomSheet.hide();
        };

	   
	   

    
  }
})();
