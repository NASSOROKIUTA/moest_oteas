/**
 * Created by USER on 2017-02-13.
 */

(function () {
    'use strict';
    angular
        .module('authApp').directive('ngFiles', ['$parse', function ($parse) {

        function fn_link(scope, element, attrs) {
            var onChange = $parse(attrs.ngFiles);
            element.on('change', function (event) {
                onChange(scope, {$files: event.target.files});
            });
        };

        return {
            link: fn_link
        }
    }]).controller('adminController', adminController);

    function adminController($http, $auth, $rootScope, $state, $location, $scope, $uibModal,$mdDialog,$mdBottomSheet, toastr,Helper) {


        var user = $rootScope.currentUser;
        var user_name = $rootScope.currentUser.id;
       
		$scope.displayUserInfo=function(item){			
			$scope.selectedUser=item;
            console.log($scope.selectedUser);			
		};
		$scope.getApiType=function(api_selected){			
			$scope.selectedApi=api_selected;            	
		};
		
		$scope.unlock=function(){
			var user_id=$scope.selectedUser.id;
			var name=$scope.selectedUser.name;
			 $http.post('/api/changeStatus', {user_id:user_id}).then(function (data) {
                if(data.data==1){
			     return   sweetAlert(name+', can now login.', "", "success");
					}else{
			      return  sweetAlert(name+', sorry process did not complete please,try again.', "", "error");
											
					}
			 });
			
		};	
		
		$scope.saveIpAddress=function(api){
			if(angular.isDefined(api)==false){
			 return  sweetAlert('Fill all required fields', "", "error");	
			}
			
			 $http.post('/api/saveIpAddress', {api_type:$scope.selectedApi,facility_id:facility_id,ip_address:api.ip_address,base_urls:api.ip_address,private_keys:api.private_keys,public_keys:api.public_keys}).then(function (data) {
                if(data.data.status==1){
			     return   sweetAlert(data.data.data, "", "success");
					}else{
			      return  sweetAlert(data.data.data, "", "error");
											
					}
			 });
			
		};
		
		$scope.synchronizeFacilityCentrally=function(facility_code){
			$http.post('/api/synchronizeFacilityCentrally', {facility_code:facility_code}).then(function (data) {
                if(data.data.status==1){
			     
				 $scope.returnedData=data.data.data;
				 
				  var facility= $scope.returnedData;
				  $mdDialog.show({
                controller: function ($scope) {


                    $scope.facility = facility;
					console.log($scope.facility);
                    $scope.cancel = function () {
                        $mdDialog.hide();
                    };

					$scope.saveFacilityFromCentral = function (downloadedFacility) {
                        console.log(downloadedFacility);
						var facility_id=downloadedFacility.id;
						var facility_name=downloadedFacility.facility_name;
						var facility_type_id=downloadedFacility.facility_type_id;
						var mobile_number=downloadedFacility.mobile_number;
						var region_id=downloadedFacility.region_id;
						var council_id=downloadedFacility.council_id;
						var email=downloadedFacility.email;
						var address=downloadedFacility.address;
						var postData={council_id:council_id,facility_code:facility_code,facility_id:facility_id,facility_name:facility_name,facility_type_id:facility_type_id,mobile_number:mobile_number,region_id:region_id,email:email,address:address};
				$http.post('/api/downloadFacility',postData).then(function (data) {
						
                   if(data.data.status==1){
			     $scope.cancel();
				 return   sweetAlert(data.data.data, "", "success");
				
				   }else{
					  return   sweetAlert(data.data.data, "", "error");
				  
					   
				   }				 
						});
						
                    };
                },
                templateUrl: '/views/modules/admin/facility_form.html',
                parent: angular.element(document.body),
                clickOutsideToClose: false,
                fullscreen: $scope.customFullscreen // Only for -xs, -sm breakpoints.
            });
				 
				 
					}
					else{
			      return  sweetAlert(data.data.data, "", "info");
											
					}
			 });
			
		};

        var formdata = new FormData();

        $scope.getTheFiles = function ($files) {
            angular.forEach($files, function (value, key) {
                formdata.append(key, value);

            });
            formdata.append('photo_for', user_name);
        }; 


		$scope.getExcelFiles = function ($files) {
            angular.forEach($files, function (value, key) {
                formdata.append(key, value);

            });
            formdata.append('uploaded_by', user_name);
        };

        $scope.chosenRole = null;
        $scope.chooseTheRole = function (role) {

            $scope.chosenRole = role;
            $scope.selectedRole = role;

            $scope.getAssignedRole(role);

        };

        $scope.permSelected = false;
        $scope.addAPerm = function (roles) {
            var title = roles.title;
            var parents = roles.parent;
            $scope.getAssignedRole(roles);
            var save_roles = {'title': title, 'parent': parents};

            $http.post('/api/addRoles', save_roles).then(function (data) {
                var getstatus = data.status;
                var getdata = data.data;

                swal({
                    title: 'Role added',
                    html: $('<div>')
                        .text('' + getdata + ''),
                    animation: false,
                    customClass: 'animated tada'
                });
            }).then(function (data) {
                console.log(data);
                swal({
                    title: 'Role already has this permission',
                    html: $('<div>')
                        .text('' + data + ''),
                    animation: false,
                    customClass: 'animated tada'
                });

            });
        };


        // NOW UPLOAD THE FILES.
        $scope.uploadFiles = function () {

            var request = {
                method: 'POST',
                url: '/api/' + 'fileupload',
                data: formdata,
                headers: {
                    'Content-Type': undefined
                }

            };

            // SEND THE FILES.
            $http(request).then(function (data) {
                //console.log(request);
                swal({
                    title: '',
                    html: $('<div>')
                        .addClass('some-class')
                        .text('' + data.data + ''),
                    animation: false,
                    customClass: 'animated tada'
                });


            })
                .then(function () {
                });
        };
		
		
    // NOW UPLOAD THE FILES.
        $scope.schoolUpload = function () {

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
                swal({
                    title: '',
                    html: $('<div>')
                        .addClass('some-class')
                        .text('' + data.data + ''),
                    animation: false,
                    customClass: 'animated tada'
                });


            })
                .then(function () {
                });
        };


        $scope.runViews = function () {

            $http.get('/api/userView').then(function (data) {
                $scope.icons = data.data;

                var getstatus = data.status;
                var getdata = 'SYSTEM DATABASE ACTIVATED';

                toastr.success('', getdata);

            });
        }

        $scope.setSystemData = function () {
            $http.get('/api/getPerm').then(function (data) {
                $scope.permission = data.data;
                //console.log($scope.perms);

            });

            $http.get('/api/geticon').then(function (data) {
                $scope.icons = data.data;

            });


            $http.get('/api/getUserImage/' + user_name).then(function (data) {
                if (data.data.length != 0) {
                    $scope.photo = '/uploads/' + data.data[0].photo_path;
                }

            });


            $http.get('/api/user_list').then(function (data) {
                $scope.users = data.data;
                //console.log($scope.users);
            });

        };
		
		$scope.adminRegistration = function (user) {

            $http.post('/api/adminRegistration', user).then(function (data) {
                $scope.user_list();
               
			   if (data.data.status == 0) {
                    sweetAlert(data.data.data, "", "error");
                } else {
                    sweetAlert(data.data.data, "", "success");
                }
			   
			   
            });
        };


        $scope.savePermRoles = function (permRoles) {

            var perm_list = $scope.labcartsxx;
            angular.forEach(perm_list, function (value, key) {
                //console.log(key + ': ' + value);
            });
            /**
             $http.post('/api/perm_role',$scope.labcartsxx).then(function(data) {
                //console.log(data);
                });    **/
        }

          $scope.getUser = function (email) {
            return Helper.searchUser(email,facility_id)
                .then(function (response) {
                    return response.data;
                });
        };
		
        $scope.saveModules = function (modules) {
            var menu = {
                'main_menu': 1,
                'module': modules.module,
                'title': modules.title,
                'glyphicons': modules.glyphicons.icon_class
            };
            //console.log(menu);
            $http.post('/api/addPermission', menu).then(function (data) {
                var getstatus = data.status;
                var getdata = data.data;
                swal({
                    title: '' + getstatus + '',
                    html: $('<div>')
                        .addClass('some-class')
                        .text('' + getdata + ''),
                    animation: false,
                    customClass: 'animated tada'
                });
            }).then(function (data) {
                swal({
                    title: '' + getstatus + '',
                    html: $('<div>')
                        .addClass('some-class')
                        .text('' + getdata + ''),
                    animation: false,
                    customClass: 'animated tada'
                });
            });
        }


        $scope.saveRoles = function (roles) {
            var title = roles.title;
            var parents = roles.parent;

            var save_roles = {'title': title, 'parent': parents};


            //console.log(save_roles);
            $http.post('/api/addRoles', save_roles).then(function (data) {
                var getstatus = data.status;
                var getdata = data.data;

                swal({
                    title: '' + getstatus + '',
                    html: $('<div>')
                        .addClass('some-class')
                        .text('' + getdata + ''),
                    animation: false,
                    customClass: 'animated tada'
                });
            }).then(function (data) {

                swal({
                    title: '' + getstatus + '',
                    html: $('<div>')
                        .addClass('some-class')
                        .text('' + getdata + ''),
                    animation: false,
                    customClass: 'animated tada'
                });
            });
        }

        $scope.logout = function () {
            // Remove the authenticated user from local storage
            localStorage.removeItem('user');

            // Flip authenticated to false so that we no longer
            // show UI elements dependant on the user being logged in
            $rootScope.authenticated = false;

            // Remove the current user info from rootscope
            $rootScope.currentUser = null;
            event.preventDefault();
            $state.go('auth');

        }
        var user_id = $rootScope.currentUser.id;
        var state_name = $state.current.name;

        $http.get('/api/getUsermenu/' + user_id).then(function (data) {
            $scope.menu = data.data;
            //console.log($scope.menu);

        });


        $http.get('/api/getAuthorization/' + user_id + ',' + state_name).then(function (data) {
            $scope.authorization_number = data.data;

            if ($scope.authorization_number == 0 && state_name != 'auth') {

                // Preventing the default behavior allows us to use $state.go
                // to change states
                event.preventDefault();

                // go to the "main" state which in our case is users
                $state.go('dashboard');
            }

        });


        $scope.labcartsxx = [];

        $scope.checkTest = function (item, permRoles) {

            if (angular.isDefined(permRoles) == false) {
                swal({
                    title: 'ERROR:',
                    html: $('<div>')
                        .addClass('some-class')
                        .text('PLEASE SELECT THE ROLE ,FROM SEARCH BOX ABOVE BEFORE PROCEED'),
                    animation: false,
                    customClass: 'animated tada'
                });
            } else {
                var permission_id = item.id;
                var role_id = permRoles.role.id;

                var perm_roles = {'permission_id': permission_id, 'role_id': role_id, 'grant': 1};

                $http.get('/api/getPermName/' + permission_id).then(function (data) {
                    $scope.perms = data.data;
                    //console.log(role_id);
                    var perm_name = 'Permission ' + $scope.perms + ' was selected and SAVED in the SYSTEM';

                    $http.post('/api/perm_role', perm_roles).then(function (data) {
                        var getstatus = data.status;
                        var getdata = data.data;

                        swal({
                            title: '' + getstatus + '',
                            html: $('<div>')
                                .addClass('some-class')
                                .text('' + getdata + ''),
                            animation: false,
                            customClass: 'animated tada'
                        });


                    });

                });

                if ((item.selected) == true) {
                }
                else if ((item.selected) == false) {
                    var indexremove = $scope.labcartsxx.indexOf(item);
                    $scope.labcartsxx.splice(indexremove, 1);
                }

            }
        }


        $scope.chooseUser = function (user) {

            $scope.selectedUser = user;

            $scope.getAssignedPerms(user);

        }

        $scope.chooseRole = function (role) {

            $scope.selectedRole = role;

            $scope.getAssignedRole(role);

        }

        $scope.getAssignedRole = function (selectedRole) {

            var selectedRoleId = selectedRole.id;
            $http.get('/api/getAssignedRole/' + selectedRoleId).then(function (data) {
                $scope.savedRolePerms = data.data;
            });

        }

        $scope.getAssignedPerms = function (permUsers) {

            var selectedUserId = permUsers.id;
            $http.get('/api/getAssignedMenu/' + selectedUserId).then(function (data) {
                $scope.savedPerms = data.data;
            });

        }


        $scope.removeAccess = function (accessgiven) {
            //console.log(accessgiven);
            var perm_user_id = accessgiven.id;
            var user_id = accessgiven.user_id;
            var title = accessgiven.title;
            var data = {"perm_id": perm_user_id, "title": title};
            $http.post('/api/removeAccess', data).then(function (data) {

                var getdata = data.data.data;
                toastr.success(getdata, '');
            }).finally(function () {
                $http.get('/api/getAssignedMenu/' + user_id).then(function (data) {
                    $scope.savedPerms = data.data;
                });

            });

        }

        $scope.removeRoleAccess = function (accessgiven) {
            var permission_role_id = accessgiven.id;
            var role_id = accessgiven.role_id;
            var title = accessgiven.title;
            var role_name = accessgiven.role_name;
            var data = {"role_name": role_name, "permission_role_id": permission_role_id, "title": title};
            $http.post('/api/removeRoleAccess', data).then(function (data) {

                var getdata = data.data.data;
                toastr.success(getdata, '');
            }).finally(function () {
                $http.get('/api/getAssignedRole/' + role_id).then(function (data) {
                    $scope.savedRolePerms = data.data;
                });

            });

        }

        $scope.checkUserPerms = function (permUserVal, item, permUsers) {

            if (permUserVal == true) {

                if (angular.isDefined(permUsers) == false) {
                    toastr.error('PLEASE SELECT THE USER ,FROM SEARCH BOX ABOVE BEFORE PROCEED', '');

                } else {
                    var permission_id = item.id;
                    var user_id = permUsers.id;
                    //console.log(user_id);
                    var perm_users = {'permission_id': permission_id, 'user_id': user_id, 'grant': 1};

                    $http.get('/api/getPermName/' + permission_id).then(function (data) {
                        $scope.perms = data.data;
                        var perm_name = 'Permission ' + $scope.perms + ' was selected and SAVED in the SYSTEM';

                        $http.post('/api/perm_user', perm_users).then(function (data) {
                            var getstatus = data.status;
                            var getdata = data.data.data;


                            if (data.data.status == 0) {
                                toastr.error(getdata, '');
                            } else {
                                $scope.getAssignedPerms(permUsers);
                                toastr.success(getdata, '');
                            }


                        });

                    });


                }
            }
        }


        $scope.checkRolePerms = function (permUserVal, item, selectedRole) {
console.log(item);
            if (permUserVal == true) {

                if (angular.isDefined(selectedRole) == false) {
                    toastr.error('PLEASE SELECT THE ROLE ', '');

                } else {
                    var permission_id = item.id;
                    var role_id = selectedRole.id;
                    var perm_roles = {'permission_id': permission_id, 'role_id': role_id, 'grant': 1};

                    $http.get('/api/getPermName/' + permission_id).then(function (data) {
                        $scope.perms = data.data;
                        var perm_name = 'Permission ' + $scope.perms + ' was selected and SAVED in the SYSTEM';

                        $http.post('/api/perm_role', perm_roles).then(function (data) {
                            var getstatus = data.status;
                            var getdata = data.data.data;


                            if (data.data.status == 0) {
                                toastr.error(getdata, '');
                            } else {
                                $scope.getAssignedRole(selectedRole);
                                toastr.success(getdata, '');
                            }


                        });

                    });


                }
            }
        }


        $scope.mytime1 = new Date();
        $scope.mytime2 = new Date();

        $scope.AdmissionNotes = "";
        var resdata = [];
        var nextresdata = [];
        var patientCategory = [];
        var patientService = [];
        var patientsList = [];
        var maritals = [];
        var tribe = [];
        var occupation = [];
        var country = [];
        var relationships = [];

        angular.element(document).ready(function () {
            $scope.setSystemData();
            $scope.setTabUserReg(1);
            toastr.success('Opening Admin Dashboard...', '');
            $scope.getProffesions();
        });

        $scope.setTabUserReg = function (newTab) {
            $scope.tab = newTab;
        };

        $scope.setTabSystemMenu = function (newTab) {
            $scope.tab = newTab;
        };

        $scope.setTabPermUser = function (newTab) {
            $scope.tab = newTab;
        };

        $scope.setTabPermRole = function (newTab) {
            $scope.getSystemActivity();
            $scope.tab = newTab;
        };

        $scope.user_list = function () {
            $http.get('/api/user_list').then(function (data) {
                $scope.users = data.data;
                ////console.log($scope.users)

            });
        }
        $scope.user_registration = function (user) {
			if(angular.isDefined(user)==false){
				return  sweetAlert("Fill all required data", "", "error");
			}
			else if(angular.isDefined(user.email)==false){
				return  sweetAlert("Fill user name", "", "error");
			}
			else if(angular.isDefined(user.name)==false){
				return  sweetAlert("Fill  name of the staff", "", "error");
			}
			else if(angular.isDefined(user.password)==false){
				return  sweetAlert("Enter password", "", "error");
			}
			else if(user.password !=user.retype_password){
				return  sweetAlert("Password mismatch", "", "error");
			}

              var council_id=null;
            if(angular.isDefined($scope.council)==true){
              council_id= $scope.council.id;
            }


			var postData={proffesionals_id:user.proffesionals_id,email:user.email,department_id:1,password:user.password,gender:user.gender,mobile_number:user.mobile_number,name:user.name,council_id:council_id};

          $http.post('/api/user_registration',postData).then(function (data) {
				
				if (data.data.status == 0) {
                    sweetAlert(data.data.data, "", "error");
                } else {
                    sweetAlert(data.data.data, "", "success");
                }

                //$scope.user_list();
               
            });
        }

        $scope.getSystemActivity = function () {
            $http.get('/api/getSystemActivity').then(function (data) {
                $scope.systemActivities = data.data;

            });
        };


        $http.get('/api/getUsermenu/' + user_id).then(function (data) {
            $scope.menu = data.data;
        });


        $scope.isSet = function (tabNum) {
            return $scope.tab === tabNum;
        }
        $scope.oneAtATime = true;


        $scope.addWardTypes = function (wards) {
            //var ward_type=wards.ward_type;
            if (angular.isDefined(wards) == false) {
                return sweetAlert("Please Enter WARD TYPE", "", "error");
            } else {
                $http.post('/api/saveWardTypes', {"ward_type_name": wards.ward_type}).then(function (data) {

                    if (data.data.status == 0) {
                        $scope.wards = null;
                        sweetAlert(data.data.data, "", "error");
                    } else {
                        $scope.wards = null;
                        sweetAlert(data.data.data, "", "success");


                    }


                });

            }


        }


        $scope.addDevices = function (device) {
            if (angular.isDefined(device) == false) {
                return sweetAlert("Please Enter DEVICE ", "", "error");
            }
            else {

                var dataPost = {
                    "eraser": 0,
                    "reagents": device.reagents,
                    "equipment_name": device.equip_name,
                    "equipment_status_id": device.equip_status,
                    "sub_department_id": device.subdepartment,
                    "facility_id": facility_id,
                    "user_id": user_id
                };

                $http.post('/api/addDevices', dataPost).then(function (data) {
                    if (data.data.status == 0) {
                        sweetAlert(data.data.data, "", "error");
                    }
                    else {
                        device.reagents = null;
                        device.equip_name = null;
                        sweetAlert(data.data.data, "", "success");
                    }
                });
            }
        }


        $scope.addLabTest = function (test) {
            if (angular.isDefined(test) == false) {
                return sweetAlert("Please Enter TEST Name", "", "error");
            }
            else {

                var dataPost = {
                    "erasor": 0,
                    "equipment_id": test.equipment_id,
                    "minimum_limit": test.minimum_limit,
                    "panel_compoent_name": test.name,
                    "si_units": test.si_units,
                    "maximum_limit": test.maximum_limit,
                    "user_id": user_id
                };

                $http.post('/api/addLabTest', dataPost).then(function (data) {
                    if (data.data.status == 0) {
                        sweetAlert(data.data.data, "", "error");
                    }
                    else {
                        test.name = null;
                        test.si_units = null;
                        sweetAlert(data.data.data, "", "success");
                    }
                });
            }
        }
		
		$scope.getOtherOptions = function (user_type) {
            if (angular.isDefined(user_type) == true) {
                if(user_type==5){
					$scope.showCouncil=true;
				}
            }
            };
			
			var council_Lists=[];
			$scope.searchCouncil = function (searchKey) {
            if (angular.isDefined(searchKey) == true) {
               var postData={searchKey:searchKey};
			   
			   $http.post('/api/searchCouncil', postData).then(function (data) {
                 council_Lists=data.data;
			   });
			   return council_Lists;
            }
            };
			
			$scope.selectedCouncil = function (council) {
            $scope.council=council;
            };

        $scope.addLabTestPanel = function (testPanel) {
            if (angular.isDefined(testPanel) == false) {
                return sweetAlert("Please Enter TEST PANEL ", "", "error");
            }
            else {
                var dataPost = {
                    "erasor": 0,
                    "item_id": testPanel.item_id,
                    "equipment_id": testPanel.equipment_id,
                    "minimum_limit": testPanel.minimum_limit,
                    "panel_compoent_name": testPanel.name,
                    "si_units": testPanel.si_units,
                    "maximum_limit": testPanel.maximum_limit,
                    "user_id": user_id
                };
                //console.log(dataPost);
                $http.post('/api/addLabTestPanel', dataPost).then(function (data) {
                    if (data.data.status == 0) {
                        sweetAlert(data.data.data, "", "error");
                    }
                    else {
                        testPanel.name = null;
                        sweetAlert(data.data.data, "", "success");
                    }
                });
            }
        }


        $scope.addLabPanel = function (test) {
            if (angular.isDefined(test) == false) {
                return sweetAlert("Please Enter LAB TEST PANEL", "", "error");
            }
            else {

                var dataPost = {
                    "erasor": 0, "equipment_id": test.equipment_id, "panel_name": test.name,
                    "user_id": user_id
                };

                $http.post('/api/addLabPanel', dataPost).then(function (data) {
                    if (data.data.status == 0) {
                        sweetAlert(data.data.data, "", "error");
                    }
                    else {
                        test.name = null;
                        test.si_units = null;
                        sweetAlert(data.data.data, "", "success");
                    }
                });
            }
        }


        $scope.PreOPCondition = function (PreOPCondition, selectedPatient) {
            if (angular.isDefined(PreOPCondition) == false) {
                return sweetAlert("Please Enter Pre Operation Condition", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Please Select Patient Before Proceed", "", "error");
            }

            else {

                var information_category = "PRE-OPERTION CONDITION";
                var dataPost = {
                    "erasor": 0,
                    "noted_value": PreOPCondition.condition,
                    "admission_id": selectedPatient.admission_id,
                    "request_id": selectedPatient.id,
                    "remarks": PreOPCondition.case,
                    "information_category": information_category,
                    "nurse_id": user_id
                };
                $http.post('/api/addTimesQue', dataPost).then(function (data) {
                    if (data.data.status == 0) {
                        sweetAlert(data.data.data, "", "error");
                    }
                    else {
                        $scope.PreOPCondition = null;
                        sweetAlert(data.data.data, "", "success");
                    }
                });
            }
        }


        $scope.anaesthBloodLoss = function (pulse_rate, selectedPatient) {
            if (angular.isDefined(pulse_rate) == false) {
                return sweetAlert("Please Enter AMOUNT OF BLOOD COLLECTED", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Please Select Patient Before Proceed", "", "error");
            }

            else {

                var information_category = "BLOOD LOSS";
                var dataPost = {
                    "erasor": 0,
                    "noted_value": pulse_rate.noted_amount,
                    "admission_id": selectedPatient.admission_id,
                    "request_id": selectedPatient.id,
                    "remarks": pulse_rate.noted_amount,
                    "information_category": information_category,
                    "nurse_id": user_id
                };
                $http.post('/api/addTimesQue', dataPost).then(function (data) {
                    if (data.data.status == 0) {
                        sweetAlert(data.data.data, "", "error");
                    }
                    else {
                        $scope.PreOPCondition = null;
                        sweetAlert(data.data.data, "", "success");
                    }
                });
            }
        }

        $scope.anaesthFluidGiven = function (pulse_rate, selectedPatient) {
            if (angular.isDefined(pulse_rate) == false) {
                return sweetAlert("Please Enter AMOUNT OF FLUID GIVEN", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Please Select Patient Before Proceed", "", "error");
            }

            else {

                var information_category = "FLUID GIVEN";
                var dataPost = {
                    "erasor": 0,
                    "noted_value": pulse_rate.noted_amount_fluid,
                    "admission_id": selectedPatient.admission_id,
                    "request_id": selectedPatient.id,
                    "remarks": pulse_rate.noted_amount_fluid,
                    "information_category": information_category,
                    "nurse_id": user_id
                };
                $http.post('/api/addTimesQue', dataPost).then(function (data) {
                    if (data.data.status == 0) {
                        sweetAlert(data.data.data, "", "error");
                    }
                    else {
                        $scope.PreOPCondition = null;
                        sweetAlert(data.data.data, "", "success");
                    }
                });
            }
        }


        $scope.anaesthUrineOutput = function (pulse_rate, selectedPatient) {
            if (angular.isDefined(pulse_rate) == false) {
                return sweetAlert("Please Enter AMOUNT OF URINE OUTPUT", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Please Select Patient Before Proceed", "", "error");
            }

            else {

                var information_category = "URINE OUTPUT";
                var dataPost = {
                    "erasor": 0,
                    "noted_value": pulse_rate.urine_output,
                    "admission_id": selectedPatient.admission_id,
                    "request_id": selectedPatient.id,
                    "remarks": pulse_rate.urine_output,
                    "information_category": information_category,
                    "nurse_id": user_id
                };
                $http.post('/api/addTimesQue', dataPost).then(function (data) {
                    if (data.data.status == 0) {
                        sweetAlert(data.data.data, "", "error");
                    }
                    else {
                        pulse_rate.urine_output = null;
                        sweetAlert(data.data.data, "", "success");
                    }
                });
            }
        }


        $scope.anaesthComplications = function (pulse_rate, selectedPatient) {
            if (angular.isDefined(pulse_rate) == false) {
                return sweetAlert("Write any Compilcations found during Operations", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Please Select Patient Before Proceed", "", "error");
            }
            else {
                var information_category = "COMPLICATIONS";
                var dataPost = {
                    "erasor": 0,
                    "noted_value": pulse_rate.complications,
                    "admission_id": selectedPatient.admission_id,
                    "request_id": selectedPatient.id,
                    "remarks": pulse_rate.complications,
                    "information_category": information_category,
                    "nurse_id": user_id
                };
                $http.post('/api/addTimesQue', dataPost).then(function (data) {
                    if (data.data.status == 0) {
                        sweetAlert(data.data.data, "", "error");
                    }
                    else {
                        pulse_rate.complications = null;
                        sweetAlert(data.data.data, "", "success");
                    }
                });
            }
        }

        $scope.anaesthPulseRate = function (pulse_rate, selectedPatient) {
            if (angular.isDefined(pulse_rate) == false) {
                return sweetAlert("Please Enter Pulse Rate Associating Info", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Please Select Patient Before Proceed", "", "error");
            }
            else if (pulse_rate.am_pm == null) {
                return sweetAlert("IS IT AM/PM ? ", "", "error");
            }

            else if (pulse_rate.hr == null) {
                return sweetAlert("HOURS MISSED ", "", "error");
            }
            else if (pulse_rate.mins == null) {
                return sweetAlert("MINUTES MISSED ", "", "error");
            }
            else if (pulse_rate.mins.length != 2) {
                return sweetAlert("MINUTES MUST BE IN TWO DIGITS ", "", "error");
            }
            else if (pulse_rate.mins >= 60) {
                return sweetAlert("MINUTES MUST BE LESS THAN 60 ", "", "error");
            }

            else if (pulse_rate.mins < 0) {
                return sweetAlert("MINUTES MUST BE GREATER THAN 0 ", "", "error");
            }
            else if (pulse_rate.read == null) {
                return sweetAlert("EMPTY VALUE IN READ BOX  ", "", "error");
            }


            else {

                var information_category = "PULSE RATE";

                var dataPost = {
                    "erasor": 0,
                    "noted_value": pulse_rate.read,
                    "admission_id": selectedPatient.admission_id,
                    "request_id": selectedPatient.id,
                    "am_pm": pulse_rate.am_pm,
                    "mins": pulse_rate.mins,
                    "hr": pulse_rate.hr,
                    "information_category": information_category,
                    "nurse_id": user_id
                };
                $http.post('/api/addPrBp', dataPost).then(function (data) {
                    if (data.data.status == 0) {
                        sweetAlert(data.data.data, "", "error");
                    }
                    else {
                        $scope.PreOPCondition = null;
                        sweetAlert(data.data.data, "", "success");
                    }
                });
            }
        }


        $scope.anaesthSystolic = function (pulse_rate, selectedPatient) {
            if (angular.isDefined(pulse_rate) == false) {
                return sweetAlert("Please Enter Pulse Rate Associating Info", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Please Select Patient Before Proceed", "", "error");
            }
            else if (pulse_rate.am_pm == null) {
                return sweetAlert("IS IT AM/PM ? ", "", "error");
            }

            else if (pulse_rate.hr == null) {
                return sweetAlert("HOURS MISSED ", "", "error");
            }
            else if (pulse_rate.mins == null) {
                return sweetAlert("MINUTES MISSED ", "", "error");
            }
            else if (pulse_rate.mins.length != 2) {
                return sweetAlert("MINUTES MUST BE IN TWO DIGITS ", "", "error");
            }
            else if (pulse_rate.mins >= 60) {
                return sweetAlert("MINUTES MUST BE LESS THAN 60 ", "", "error");
            }

            else if (pulse_rate.mins < 0) {
                return sweetAlert("MINUTES MUST BE GREATER THAN 0 ", "", "error");
            }
            else if (pulse_rate.read == null) {
                return sweetAlert("EMPTY VALUE IN READ BOX  ", "", "error");
            }


            else {

                var information_category = "SYSTOLIC PRESSURE";

                var dataPost = {
                    "erasor": 0,
                    "noted_value": pulse_rate.read,
                    "admission_id": selectedPatient.admission_id,
                    "request_id": selectedPatient.id,
                    "am_pm": pulse_rate.am_pm,
                    "mins": pulse_rate.mins,
                    "hr": pulse_rate.hr,
                    "information_category": information_category,
                    "nurse_id": user_id
                };
                $http.post('/api/addPrBp', dataPost).then(function (data) {
                    if (data.data.status == 0) {
                        sweetAlert(data.data.data, "", "error");
                    }
                    else {
                        pulse_rate.read = null;
                        sweetAlert(data.data.data, "", "success");
                    }
                });
            }
        }

        $scope.anaesthDiastolic = function (pulse_rate, selectedPatient) {
            if (angular.isDefined(pulse_rate) == false) {
                return sweetAlert("Please Enter Pulse Rate Associating Info", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Please Select Patient Before Proceed", "", "error");
            }
            else if (pulse_rate.am_pm == null) {
                return sweetAlert("IS IT AM/PM ? ", "", "error");
            }

            else if (pulse_rate.hr == null) {
                return sweetAlert("HOURS MISSED ", "", "error");
            }
            else if (pulse_rate.mins == null) {
                return sweetAlert("MINUTES MISSED ", "", "error");
            }
            else if (pulse_rate.mins.length != 2) {
                return sweetAlert("MINUTES MUST BE IN TWO DIGITS ", "", "error");
            }
            else if (pulse_rate.mins >= 60) {
                return sweetAlert("MINUTES MUST BE LESS THAN 60 ", "", "error");
            }

            else if (pulse_rate.mins < 0) {
                return sweetAlert("MINUTES MUST BE GREATER THAN 0 ", "", "error");
            }
            else if (pulse_rate.read_diastolic == null) {
                return sweetAlert("EMPTY VALUE IN READ BOX  ", "", "error");
            }


            else {

                var information_category = "DIASTOLIC PRESSURE";

                var dataPost = {
                    "erasor": 0,
                    "noted_value": pulse_rate.read_diastolic,
                    "admission_id": selectedPatient.admission_id,
                    "request_id": selectedPatient.id,
                    "am_pm": pulse_rate.am_pm,
                    "mins": pulse_rate.mins,
                    "hr": pulse_rate.hr,
                    "information_category": information_category,
                    "nurse_id": user_id
                };
                $http.post('/api/addPrBp', dataPost).then(function (data) {
                    if (data.data.status == 0) {
                        sweetAlert(data.data.data, "", "error");
                    }
                    else {
                        pulse_rate.read_diastolic = null;
                        sweetAlert(data.data.data, "", "success");
                    }
                });
            }
        }


        $scope.anaesthIntubation = function (Intubation, selectedPatient) {
            if (angular.isDefined(Intubation) == false) {
                return sweetAlert("Please Enter INTUBATION INFO", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Please Select Patient Before Proceed", "", "error");
            }

            else {

                var information_category = "INTUBATION";
                var dataPost = {
                    "erasor": 0,
                    "noted_value": Intubation.condition,
                    "admission_id": selectedPatient.admission_id,
                    "request_id": selectedPatient.id,
                    "remarks": Intubation.condition,
                    "information_category": information_category,
                    "nurse_id": user_id
                };
                $http.post('/api/addTimesQue', dataPost).then(function (data) {
                    if (data.data.status == 0) {
                        sweetAlert(data.data.data, "", "error");
                    }
                    else {
                        $scope.PreOPCondition = null;
                        sweetAlert(data.data.data, "", "success");
                    }
                });
            }
        }


        $scope.anaesthRespiration = function (Respiration, selectedPatient) {
            if (angular.isDefined(Respiration) == false) {
                return sweetAlert("Please Enter RESPIRATION INFO", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Please Select Patient Before Proceed", "", "error");
            }

            else {
                var information_category = "RESPIRATION";
                var dataPost = {
                    "erasor": 0,
                    "noted_value": Respiration.condition,
                    "admission_id": selectedPatient.admission_id,
                    "request_id": selectedPatient.id,
                    "remarks": Respiration.condition,
                    "information_category": information_category,
                    "nurse_id": user_id
                };
                $http.post('/api/addTimesQue', dataPost).then(function (data) {
                    if (data.data.status == 0) {
                        sweetAlert(data.data.data, "", "error");
                    }
                    else {
                        $scope.Respiration = null;
                        sweetAlert(data.data.data, "", "success");
                    }
                });
            }
        }

        $scope.anaesthLocal = function (Local, selectedPatient) {
            if (angular.isDefined(Local) == false) {
                return sweetAlert("Please Enter LOCAL INFO", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Please Select Patient Before Proceed", "", "error");
            }

            else {
                var information_category = "LOCAL";
                var dataPost = {
                    "erasor": 0,
                    "noted_value": Local.condition,
                    "admission_id": selectedPatient.admission_id,
                    "request_id": selectedPatient.id,
                    "remarks": Local.condition,
                    "information_category": information_category,
                    "nurse_id": user_id
                };
                $http.post('/api/addTimesQue', dataPost).then(function (data) {
                    if (data.data.status == 0) {
                        sweetAlert(data.data.data, "", "error");
                    }
                    else {
                        $scope.Respiration = null;
                        sweetAlert(data.data.data, "", "success");
                    }
                });
            }
        }

        $scope.anaesthPosition = function (Position, selectedPatient) {
            if (angular.isDefined(Position) == false) {
                return sweetAlert("Please Enter POSITION INFO", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Please Select Patient Before Proceed", "", "error");
            }

            else {
                var information_category = "POSITION";
                var dataPost = {
                    "erasor": 0,
                    "noted_value": Position.condition,
                    "admission_id": selectedPatient.admission_id,
                    "request_id": selectedPatient.id,
                    "remarks": Position.condition,
                    "information_category": information_category,
                    "nurse_id": user_id
                };
                $http.post('/api/addTimesQue', dataPost).then(function (data) {
                    if (data.data.status == 0) {
                        sweetAlert(data.data.data, "", "error");
                    }
                    else {
                        $scope.Respiration = null;
                        sweetAlert(data.data.data, "", "success");
                    }
                });
            }
        }


        $scope.anaesthNeedle = function (Needle, selectedPatient) {
            if (angular.isDefined(Needle) == false) {
                return sweetAlert("Please Enter NEEDLE INFO", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Please Select Patient Before Proceed", "", "error");
            }

            else {
                var information_category = "NEEDLE";
                var dataPost = {
                    "erasor": 0,
                    "noted_value": Needle.condition,
                    "admission_id": selectedPatient.admission_id,
                    "request_id": selectedPatient.id,
                    "remarks": Needle.condition,
                    "information_category": information_category,
                    "nurse_id": user_id
                };
                $http.post('/api/addTimesQue', dataPost).then(function (data) {
                    if (data.data.status == 0) {
                        sweetAlert(data.data.data, "", "error");
                    }
                    else {
                        $scope.Needle = null;
                        sweetAlert(data.data.data, "", "success");
                    }
                });
            }
        }

        $scope.anaesthEffect = function (Effect, selectedPatient) {
            if (angular.isDefined(Effect) == false) {
                return sweetAlert("Please Enter NEEDLE INFO", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Please Select Patient Before Proceed", "", "error");
            }

            else {
                var information_category = "EFFECT";
                var dataPost = {
                    "erasor": 0,
                    "noted_value": Effect.condition,
                    "admission_id": selectedPatient.admission_id,
                    "request_id": selectedPatient.id,
                    "remarks": Effect.condition,
                    "information_category": information_category,
                    "nurse_id": user_id
                };
                $http.post('/api/addTimesQue', dataPost).then(function (data) {
                    if (data.data.status == 0) {
                        sweetAlert(data.data.data, "", "error");
                    }
                    else {
                        $scope.Effect = null;
                        sweetAlert(data.data.data, "", "success");
                    }
                });
            }
        }

        $scope.saveHb = function (hb, selectedPatient) {
            //var ward_type=wards.ward_type;
            if (angular.isDefined(hb) == false) {
                return sweetAlert("Please Enter Laboratory Status", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Please Enter Patient Selected", "", "error");
            } else {
                var information_category = 'LABORATORY STATUS';
                var dataToPost = {
                    "erasor": 0, "admission_id": selectedPatient.admission_id, "request_id": selectedPatient.id,
                    "value_noted": hb.laboratory, "information_category": information_category, "nurse_id": user_id
                };
                $http.post('/api/saveStatusAnaesthetic', dataToPost).then(function (data) {

                    if (data.data.status == 0) {

                        sweetAlert(data.data.data, "", "error");
                    } else {
                        hb.laboratory = null;
                        sweetAlert(data.data.data, "", "success");


                    }


                });

            }


        }


        $scope.savePreAnaestheticOrder = function (hb, selectedPatient) {
            //var ward_type=wards.ward_type;
            if (angular.isDefined(hb) == false) {
                return sweetAlert("Please Enter PRE ANAESTHETIC ORDER", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Please Enter Patient Selected", "", "error");
            } else {
                var information_category = 'PRE ANAESTHETIC ORDER';
                var dataToPost = {
                    "erasor": 0,
                    "admission_id": selectedPatient.admission_id,
                    "request_id": selectedPatient.id,
                    "value_noted": hb.pre_anaesthetic_order,
                    "information_category": information_category,
                    "nurse_id": user_id
                };
                $http.post('/api/saveStatusAnaesthetic', dataToPost).then(function (data) {

                    if (data.data.status == 0) {

                        sweetAlert(data.data.data, "", "error");
                    } else {
                        hb.pre_anaesthetic_order = null;
                        sweetAlert(data.data.data, "", "success");


                    }


                });

            }


        }


        $scope.saveAnaestheticTechniques = function (hb, selectedPatient) {
            //var ward_type=wards.ward_type;
            if (angular.isDefined(hb) == false) {
                return sweetAlert("Please Enter ANAESTHETIC TECHNIQUES", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Please Enter Patient Selected", "", "error");
            } else {
                var information_category = 'ANAESTHETIC TECHNIQUES';
                var dataToPost = {
                    "erasor": 0,
                    "admission_id": selectedPatient.admission_id,
                    "request_id": selectedPatient.id,
                    "value_noted": hb.anaesthetic_technique,
                    "information_category": information_category,
                    "nurse_id": user_id
                };
                $http.post('/api/saveStatusAnaesthetic', dataToPost).then(function (data) {

                    if (data.data.status == 0) {

                        sweetAlert(data.data.data, "", "error");
                    } else {
                        hb.anaesthetic_technique = null;
                        sweetAlert(data.data.data, "", "success");


                    }


                });

            }


        }

        $scope.savePhysicalStatus = function (hb, selectedPatient) {
            //var ward_type=wards.ward_type;
            if (angular.isDefined(hb) == false) {
                return sweetAlert("Please Enter Physical Status", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Please Enter Patient Selected", "", "error");
            } else {
                //console.log(hb);
                var information_category = 'PHYSICAL STATUS';
                var dataToPost = {
                    "erasor": 0, "admission_id": selectedPatient.admission_id, "request_id": selectedPatient.id,
                    "value_noted": hb.physical_status, "information_category": information_category, "nurse_id": user_id
                };
                $http.post('/api/saveStatusAnaesthetic', dataToPost).then(function (data) {

                    if (data.data.status == 0) {

                        sweetAlert(data.data.data, "", "error");
                    } else {
                        hb.laboratory = null;
                        sweetAlert(data.data.data, "", "success");


                    }


                });

            }


        }

        $scope.saveOral = function (hb, selectedPatient) {
            //var ward_type=wards.ward_type;
            if (angular.isDefined(hb) == false) {
                return sweetAlert("Please Enter LAST ORAL INTAKE", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Please Enter Patient Selected", "", "error");
            } else {
                //console.log(hb);
                var information_category = 'LAST ORAL INTAKE';
                var dataToPost = {
                    "erasor": 0, "admission_id": selectedPatient.admission_id, "request_id": selectedPatient.id,
                    "value_noted": hb.oral, "information_category": information_category, "nurse_id": user_id
                };
                $http.post('/api/saveStatusAnaesthetic', dataToPost).then(function (data) {

                    if (data.data.status == 0) {

                        sweetAlert(data.data.data, "", "error");
                    } else {
                        hb.oral = null;
                        sweetAlert(data.data.data, "", "success");


                    }


                });

            }


        }

        $scope.saveNutritional = function (hb, selectedPatient) {
            //var ward_type=wards.ward_type;
            if (angular.isDefined(hb) == false) {
                return sweetAlert("Please Enter NUTRITIONAL STATUS", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Please Enter Patient Selected", "", "error");
            } else {
                //console.log(hb);
                var information_category = 'NUTRITIONAL STATUS';
                var dataToPost = {
                    "erasor": 0, "admission_id": selectedPatient.admission_id, "request_id": selectedPatient.id,
                    "value_noted": hb.nutrional, "information_category": information_category, "nurse_id": user_id
                };
                $http.post('/api/saveStatusAnaesthetic', dataToPost).then(function (data) {

                    if (data.data.status == 0) {

                        sweetAlert(data.data.data, "", "error");
                    } else {
                        hb.nutrional = null;
                        sweetAlert(data.data.data, "", "success");


                    }


                });

            }


        }

        var ward_types = [];
        $scope.showSearchWardTypes = function (searchKey) {

            $http.get('/api/searchWardTypes/' + searchKey).then(function (data) {
                ward_types = data.data;

            });
            return ward_types;
        }

        var ward_classes = [];
        $scope.showSearchWardClass = function (searchKey) {

            $http.get('/api/getWardClasses/' + searchKey).then(function (data) {
                ward_classes = data.data;

            });
            return ward_classes;
        }


        var beds = [];
        $scope.showSearchBedTypes = function (searchKey) {
            $http.get('/api/searchBedTypes/' + searchKey).then(function (data) {
                beds = data.data;
            });
            return beds;
        }


        $scope.getOutPutTypes = function () {
            $http.get('/api/getOutPutTypes').then(function (data) {
                $scope.getOutPutTypes = data.data;

            });

        }

        $scope.getVital = function () {
            $http.post('/api/getVital').then(function (data) {
                $scope.getVitals = data.data;
                ////console.log($getVitals);
            });

        }


        $scope.saveAssociateHistory = function (associate_history, selectedPatient) {
            if (angular.isDefined(selectedPatient) == false) {

                return sweetAlert("Please Select the Patient From list on the Left panel", "", "error");
            }
            else if (angular.isDefined(associate_history) == false) {

                return sweetAlert("Please Enter History Records or NILL", "", "error");
            }
            var admission_id = selectedPatient.admission_id;
            var information_category = 'ASSOCIATE HISTORY';
            var associateHistory = {
                "erasor": 0,
                "admission_id": selectedPatient.admission_id,
                "request_id": selectedPatient.id,
                "medical": associate_history.medical,
                "surgical": associate_history.surgical,
                "anaesthetic": associate_history.anaesthetic,
                "information_category": information_category,
                "nurse_id": user_id
            };
            $http.post('/api/saveAssociateHistory', associateHistory).then(function (data) {

                if (data.data.status == 0) {

                    sweetAlert(data.data.data, "", "error");
                } else {

                    sweetAlert(data.data.data, "", "success");
                }
            });

        }

        $scope.savePastHistory = function (past_history, selectedPatient) {
            if (angular.isDefined(selectedPatient) == false) {

                return sweetAlert("Please Select the Patient From list on the Left panel", "", "error");
            }
            else if (angular.isDefined(past_history) == false) {

                return sweetAlert("Please Enter History Records or NILL", "", "error");
            }
            var admission_id = selectedPatient.admission_id;
            var information_category = 'PAST HISTORY';
            var associateHistory = {
                "erasor": 0,
                "admission_id": selectedPatient.admission_id,
                "request_id": selectedPatient.id,
                "medical": past_history.medical,
                "surgical": past_history.surgical,
                "anaesthetic": past_history.anaesthetic,
                "information_category": information_category,
                "nurse_id": user_id
            };
            $http.post('/api/savePastHistory', associateHistory).then(function (data) {

                if (data.data.status == 0) {

                    sweetAlert(data.data.data, "", "error");
                } else {

                    sweetAlert(data.data.data, "", "success");
                }
            });

        }


        $scope.saveSocialSurgery = function (social, selectedPatient) {
            if (angular.isDefined(selectedPatient) == false) {

                return sweetAlert("Please Select the Patient From list on the Left panel", "", "error");
            }
            else if (angular.isDefined(social) == false) {

                return sweetAlert("Please Enter Social History Records or NILL", "", "error");
            }
            var admission_id = selectedPatient.admission_id;
            var information_category = 'SOCIAL AND FAMILY HISTORY';
            var socialHistory = {
                "erasor": 0,
                "admission_id": selectedPatient.admission_id,
                "request_id": selectedPatient.id,
                "chronic_illness": social.chronic_illness,
                "substance_abuse": social.substance_abuse,
                "adoption": social.adoption,
                "others": social.others,
                "other_information": information_category,
                "nurse_id": user_id
            };
            $http.post('/api/saveSocialHistory', socialHistory).then(function (data) {

                if (data.data.status == 0) {

                    sweetAlert(data.data.data, "", "error");
                } else {

                    sweetAlert(data.data.data, "", "success");
                }
            });

        }


        $scope.saveDentalStatus = function (tooth_id, tooth_number, selectedPatient) {
            if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Select the Patient Before ENTERING DENTAL STATUS", "", "error");
            }
            else {

                $scope.selectedPatient = selectedPatient;
                //console.log($scope.selectedPatient);

                $scope.dentals = {"tooth_id": tooth_id, "tooth_number": tooth_number};
                var object = angular.extend($scope.selectedPatient, $scope.dentals);
                //console.log(object);
                //console.log($scope.dentals);
                var modalInstance = $uibModal.open({
                    templateUrl: '/views/modules/nursing_care/dental_status.html',
                    size: 'lg',
                    animation: true,
                    controller: 'physicalExaminations',
                    resolve: {
                        object: function () {
                            return object;
                        }
                    }
                });


            }

        }

        $scope.saveCardivascularExaminations = function (selectedPatient) {
            if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Select the Patient Before Adding Cardivascular System", "", "error");
            }
            else {

                $scope.selectedPatient = selectedPatient;
                //console.log($scope.selectedPatient);

                ////console.log(beds_number);
                var object = $scope.selectedPatient;
                ////console.log(beds_number);
                var modalInstance = $uibModal.open({
                    templateUrl: '/views/modules/nursing_care/cardivascular_examinations.html',
                    size: 'lg',
                    animation: true,
                    controller: 'physicalExaminations',
                    resolve: {
                        object: function () {
                            return object;
                        }
                    }
                });


            }

        }


        $scope.saveGastroIntestineExaminations = function (selectedPatient) {
            if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Select the Patient Before Adding Gastro Intestine System", "", "error");
            }
            else {

                $scope.selectedPatient = selectedPatient;
                //console.log($scope.selectedPatient);

                ////console.log(beds_number);
                var object = $scope.selectedPatient;
                ////console.log(beds_number);
                var modalInstance = $uibModal.open({
                    templateUrl: '/views/modules/nursing_care/gastrointestine.html',
                    size: 'lg',
                    animation: true,
                    controller: 'physicalExaminations',
                    resolve: {
                        object: function () {
                            return object;
                        }
                    }
                });


            }

        }

        $scope.saveCentralNervousSystem = function (selectedPatient) {
            if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Select the Patient Before Adding CENTRAL NERVOUS SYSTEM", "", "error");
            }
            else {

                $scope.selectedPatient = selectedPatient;
                //console.log($scope.selectedPatient);

                ////console.log(beds_number);
                var object = $scope.selectedPatient;
                ////console.log(beds_number);
                var modalInstance = $uibModal.open({
                    templateUrl: '/views/modules/nursing_care/centralNervousSystem.html',
                    size: 'lg',
                    animation: true,
                    controller: 'physicalExaminations',
                    resolve: {
                        object: function () {
                            return object;
                        }
                    }
                });


            }

        }

        $scope.getDiagnosis = function () {
            $http.get('/api/getDiagnosis').then(function (data) {
                $scope.getDiagnosises = data.data;
                ////console.log($getVitals);
            });

        }

        $scope.getTeeth = function (selectedPatient) {

            $http.get('/api/getTeethAbove').then(function (data) {
                $scope.teethAboves = data.data;
                $http.get('/api/getTeethBelow').then(function (data) {
                    $scope.teethBelows = data.data;
                    //console.log($scope.teethBelows);
                });

            });

            if (angular.isDefined(selectedPatient) == false) {
                return sweetAlert("Select the Patient Before SETTING DENTAL STATUS", "", "error");
            } else {
                var request_id = selectedPatient.id;
                $http.get('/api/getTeethStatusFromPatientAbove/' + request_id).then(function (data) {
                    $scope.teeth_patientsAboves = data.data;

                });

                $http.get('/api/getTeethStatusFromPatientBelow/' + request_id).then(function (data) {
                    $scope.teeth_patientsBelows = data.data;

                });

            }


        }


        $scope.addImplementation = function (implementations, selectedPatient) {
            if (angular.isDefined(implementations) == false) {
                return sweetAlert("Please Select Diagnosis for Implementations", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {

                return sweetAlert("Please Select the Patient From list on the Left panel", "", "error");
            }


            var admission_id = selectedPatient.admission_id;
            var nursing_care_type = "IMPLEMENTATIONS";

            var implementations = {
                "targeted_plans": implementations.implementation,
                "nurse_diagnosis_id": implementations.getDiagnos,
                "admission_id": admission_id,
                "nursing_care_types": nursing_care_type, "nurse_id": user_id
            };
            $http.post('/api/addImplementations', implementations).then(function (data) {

                if (data.data.status == 0) {

                    sweetAlert(data.data.data, "", "error");
                } else {

                    sweetAlert(data.data.data, "", "success");
                    //$scope.getAdmPatient(selectedPatient);

                }
            });

        }

        $scope.addEvaluations = function (evaluations, selectedPatient) {
            if (angular.isDefined(evaluations) == false) {
                return sweetAlert("Please Select Diagnosis for Implementations", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {

                return sweetAlert("Please Select the Patient From list on the Left panel", "", "error");
            }


            var admission_id = selectedPatient.admission_id;
            var nursing_care_type = "EVALUATIONS";

            var evaluations = {
                "targeted_plans": evaluations.evaluation,
                "nurse_diagnosis_id": evaluations.getDiagnos,
                "admission_id": admission_id,
                "nursing_care_types": nursing_care_type, "nurse_id": user_id
            };
            $http.post('/api/addEvaluations', evaluations).then(function (data) {

                if (data.data.status == 0) {

                    sweetAlert(data.data.data, "", "error");
                } else {

                    sweetAlert(data.data.data, "", "success");
                    //$scope.getAdmPatient(selectedPatient);

                }
            });

        }

        $scope.addTimes = function (times, selectedPatient) {
            if (angular.isDefined(times) == false) {
                return sweetAlert("Please Select Diagnosis for TIMING", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {

                return sweetAlert("Please Select the Patient From list on the Left panel", "", "error");
            }

            //console.log(times);

            var admission_id = selectedPatient.admission_id;
            var nursing_care_type = "TIME";
            var daytime = times.time_day;
            var resultsTime = times.time + ' ' + daytime;

            var times = {
                "targeted_plans": resultsTime,
                "nurse_diagnosis_id": times.getDiagnos,
                "admission_id": admission_id,
                "nursing_care_types": nursing_care_type, "nurse_id": user_id, "daytime": daytime
            };
            //console.log(times);

            $http.post('/api/addTimes', times).then(function (data) {

                if (data.data.status == 0) {

                    sweetAlert(data.data.data, "", "error");
                } else {

                    sweetAlert(data.data.data, "", "success");

                }
            });

        }

        $scope.patientDischarge = function (selectedPatient) {
            $scope.selectedPatient = selectedPatient;
            //console.log($scope.selectedPatient);

            ////console.log(beds_number);
            var object = $scope.selectedPatient;
            ////console.log(beds_number);
            var modalInstance = $uibModal.open({
                templateUrl: '/views/modules/nursing_care/patientDischarge.html',
                size: 'lg',
                animation: true,
                controller: 'patientDischargedModal',
                resolve: {
                    object: function () {
                        return object;
                    }
                }
            });

        }


        $scope.addOutPuts = function (getOutPuts, selectedPatient) {
            if (angular.isDefined(getOutPuts) == false) {
                return sweetAlert("Please Select the OUTPUT TYPES", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {

                return sweetAlert("Please Select the Patient From list on the Left panel", "", "error");
            }

            var admission_id = selectedPatient.admission_id;
            var getOutPuts = {
                "amount": getOutPuts.amount,
                "observation_output_type_id": getOutPuts.types,
                "admission_id": admission_id,
                "si_units": getOutPuts.units,
                "nurse_id": user_id
            };
            //console.log(getOutPuts);
            $http.post('/api/addOutPuts', getOutPuts).then(function (data) {
                $scope.getOutPuts = data.data;

                if (data.data.status == 0) {

                    sweetAlert(data.data.data, "", "error");
                } else {

                    sweetAlert(data.data.data, "", "success");
                    //$scope.getAdmPatient(selectedPatient);

                }
            });

        }


        $scope.addIntravenous = function (intravenous, selectedPatient) {
            if (angular.isDefined(intravenous) == false) {
                return sweetAlert("Please Select the INTRAVENOUS FLUID", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {

                return sweetAlert("Please Select the Patient From list on the Left panel", "", "error");
            }


            var admission_id = selectedPatient.admission_id;
            var intravenous = {
                "intravenous_mils": intravenous.amount,
                "intravenous_types_id": intravenous.types,
                "admission_id": admission_id
            };
            $http.post('/api/addIntakeObservation', intravenous).then(function (data) {
                $scope.getIntravenous = data.data;

                if (data.data.status == 0) {

                    sweetAlert(data.data.data, "", "error");
                } else {

                    sweetAlert(data.data.data, "", "success");
                    //$scope.getAdmPatient(selectedPatient);

                }
            });

        }


        $scope.addIntakeFluid = function (oral, selectedPatient) {
            if (angular.isDefined(oral) == false) {
                return sweetAlert("Please Select the ORAL FLUID TYPE TAKEN", "", "error");
            }
            else if (angular.isDefined(selectedPatient) == false) {

                return sweetAlert("Please Select the Patient From list on the Left panel", "", "error");
            }
            var admission_id = selectedPatient.admission_id;

            var oral_mils = {"oral_mils": oral.amount, "oral_types_id": oral.types, "admission_id": admission_id};
            $http.post('/api/addIntakeFluid', oral_mils).then(function (data) {
                $scope.getIntravenous = data.data;

                if (data.data.status == 0) {

                    sweetAlert(data.data.data, "", "error");
                } else {

                    sweetAlert(data.data.data, "", "success");

                }
            });

        }


        $scope.addWard = function (wards, ward_class) {
            //var ward_type=wards.ward_type;
            ////console.log(ward_class);
            if (angular.isDefined(wards) == false) {
                return sweetAlert("Please Enter WARD NAME", "", "error");
            }
            else if (angular.isDefined(wards.ward_type) == false) {
                return sweetAlert("Please Enter WARD TYPE", "", "error");
            }

            else if (angular.isDefined(ward_class) == false) {
                return sweetAlert("Please Enter WARD CLASS", "", "error");
            }

            else if (angular.isDefined(ward_class.ward_class_id) == false) {
                return sweetAlert("Please Enter WARD CLASS", "", "error");
            }

            else {

                $http.post('/api/saveWards', {
                    "ward_class_id": ward_class.ward_class_id.item_id,
                    "ward_name": wards.ward_name,
                    "ward_type_id": wards.ward_type.id,
                    "ward_type_name": wards.ward_type.ward_type_name,
                    "facility_id": facility_id
                }).then(function (data) {

                    if (data.data.status == 0) {
                        $scope.wards = null;
                        sweetAlert(data.data.data, "", "error");
                    } else {
                        $scope.wards = null;
                        sweetAlert(data.data.data, "", "success");


                    }


                });

            }


        }


        $scope.addBed = function (wards, ward_id) {
            //var ward_type=wards.ward_type;
            if (angular.isDefined(wards) == false) {
                return sweetAlert("Please Enter BED NUMBER " + ward_id, "", "error");
            }
            else if (angular.isDefined(wards.bed_type) == false) {
                return sweetAlert("Please Enter BED TYPE " + ward_id, "", "error");
            }
            else {
                //console.log(wards.bed_type);
                $http.post('/api/saveBeds', {
                    "bed_name": wards.bed_number,
                    "bed_type_id": wards.bed_type.id,
                    "ward_id": ward_id,
                    "facility_id": facility_id,
                    "eraser": 1
                }).then(function (data) {
                    if (data.data.status == 0) {

                        sweetAlert(data.data.data, "", "error");
                    } else {
                        // $scope.wards = null;
                        sweetAlert(data.data.data, "", "success");

                    }


                });

            }


        }


        var beds_number = 0;
        var beds = [];
        var wards = [];
        $scope.getWardDetails = function (ward_id) {

            $http.get('/api/getWardOneInfo/' + ward_id).then(function (data) {
                wards = data.data;
            });
            $http.get('/api/getBedsNumber/' + ward_id).then(function (data) {
                beds_number = data.data;
            });

            $http.get('/api/getBeds/' + ward_id).then(function (data) {
                beds = data.data;
                ////console.log(beds_number);
                var object = angular.extend({}, wards, beds_number);
                ////console.log(beds_number);
                var modalInstance = $uibModal.open({
                    templateUrl: '/views/modules/nursing_care/manageWardBeds.html',
                    size: 'lg',
                    animation: true,
                    controller: 'wardManagementModal',
                    resolve: {
                        object: function () {
                            return object;
                        }
                    }
                });

                modalInstance.result.then(function (quick_registration) {
                    $scope.quick_reg = quick_registration;
                    //console.log($scope.quick_reg);
                });

            });

        }


        $scope.getBedDetails = function (bed_id, ward_id, bed_available) {
            $http.get('/api/OnThisBed/' + bed_id).then(function (data) {
                beds = data.data;

                if (data.data.status == 0) {

                    sweetAlert(data.data.data, "", "error");
                } else {
                    var bed_details = bed_available + ' TAKEN BY ' + data.data.data;
                    sweetAlert(bed_details, "", "success");

                }

            });

        }


        var patapata = "";
        $scope.getAdmissionNotes = function (patient) {
            $http.post('/api/getInstructions', {"patient_id": patient}).then(function (data) {
                $scope.AdmissionNotes = data.data;
                patapata = $scope.AdmissionNotes;
                console.dir($scope.AdmissionNotes);
            });

        }

        $scope.getProffesions = function () {
            $http.get('/api/professional_registration').then(function (data) {
                $scope.professsionals = data.data;
                ////console.log($scope.professsionals)

            });

        }

        $scope.giveBed = function (bed_id, last_name, ward_id, admission_id, bed_available) {

            // sweetAlert(bed_id+' '+bed_available, "", "success");
            //console.log(admission_id);

            $http.post('/api/giveBed', {
                "bed_id": bed_id,
                "ward_id": ward_id,
                "admission_id": admission_id,
                "bed_available": bed_available
            }).then(function (data) {
                $scope.giveBeds = data.data;
                //console.log($scope.giveBeds);

                if (data.data.status == 0) {
                    sweetAlert(data.data.data, "", "error");
                } else {
                    var bed_details = bed_available + ' SUCCESSFULLY ASSIGNED TO ' + last_name;
                    sweetAlert(bed_details, "", "success");

                }


            });


        }


        $scope.getAdmPatient = function (admitted) {
            $scope.selectedPatient = admitted;
            //console.log($scope.selectedPatient);
        }

        $scope.getPatientsSentToTheatre = function () {

            $http.get('/api/getPatientSentToTheatre').then(function (data) {
                $scope.patientsSentToTheatres = data.data;
            });
        }


        $scope.getAdmission = function (patient, ward_id, admission_id) {


            $http.post('/api/getInstructions', {"patient_id": patient, "ward_id": ward_id}).then(function (data) {
                $scope.AdmissionNotes = data.data;
                patapata = $scope.AdmissionNotes;
                console.dir($scope.AdmissionNotes);
            });

            $http.get('/api/getPatientInfo/' + patient).then(function (data) {
                $scope.quick_registration = data.data;


                var object = angular.extend({}, $scope.quick_registration, patapata);
                var modalInstance = $uibModal.open({
                    templateUrl: '/views/modules/nursing_care/bedAllocation.html',
                    size: 'lg',
                    animation: true,
                    controller: 'nursingCareModal',
                    resolve: {
                        object: function () {
                            return object;
                        }
                    }
                });

                modalInstance.result.then(function (quick_registration) {
                    $scope.quick_reg = quick_registration;
                    //console.log($scope.quick_reg);
                });

            });

        }


        $scope.assignToTheatre = function (patient, ward_id, admission_id) {


            $http.get('/api/getFullAdmitedPatientInfo/' + admission_id).then(function (data) {
                $scope.admissions = data.data;
                //console.log(data.data);
                //console.log(admission_id);
                var object = $scope.admissions;
                var modalInstance = $uibModal.open({
                    templateUrl: '/views/modules/nursing_care/postPatientsToTheatre.html',
                    size: 'lg',
                    animation: true,
                    controller: 'postPatientsToTheatreModal',
                    resolve: {
                        object: function () {
                            return object;
                        }
                    }
                });


            });

        }

        $http.get('/api/region_registration').then(function(data) {
                $scope.regions=data.data;

            });
        $http.get('/api/council_type_list').then(function(data) {
            $scope.council_types=data.data;

        });


        $http.get('/api/council_list').then(function(data) {
            $scope.councils=data.data;

        });
        
       

        $scope.facility_type_registration=function (facility_type) {
            ////console.log(facility_type)
            $http.post('/api/facility_type_registration',facility_type).then(function(data) {
                var sending=data.data;
                swal(
                    'Feedback..',
                    sending,
                    'success'
                )

                $scope.facility_type_list();
                $scope.fading();
            });
        }


        $scope.facility_type_list=function () {

            $http.get('/api/facility_type_list').then(function(data) {
                $scope.facility_types=data.data;

            });
        }



        //facility update


        $scope.facility_type_update=function (facility_type) {
            swal({
                title: 'Are you sure?',
                text: " ",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false
            }).then(function () {



                $http.post('/api/facility_type_update', facility_type).then(function (data) {
                    $scope.facility_type_list();
                    var sending=data.data;
                    swal(
                        'Feedback..',
                        'Updates Success...',
                        'success'
                    )
                })


            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
                if (dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        ' ',
                        'error'
                    )
                }
            })


        }
       
       

//facility delete
        $scope.facility_type_delete=function (facility_type,id) {
            swal({
                title: 'Are you sure?',
                text: " ",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false
            }).then(function () {



                $http.get('/api/facility_type_delete/'+id).then(function(data) {


                    $scope.facility_type_list();

                    swal(
                        'Feedback..',
                        'Deleted...',
                        'warning'
                    )
                })

            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
                if (dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        ' ',
                        'error'
                    )
                }
            })


        }


        //facilitys registration CRUD



        $scope.facility_registration=function (facility) {
            ////console.log(facility);
            $http.post('/api/facility_registration',facility).then(function(data) {
                $scope.facility_code="";

                var sending=data.data;
                swal(
                    '',
                    sending,
                    'success'
                )


//$scope.facility.facility_name=="";
            });
        }

//displaying facilities when function clicked
        $scope.facility_list=function () {

            $http.get('/api/facility_list').then(function(data) {
                $scope.facilities=data.data;

            });
        }



        //  update


        $scope.facility_update=function (facility) {
            ////console.log(facility)
            swal({
                title: 'Are you sure?',
                text: " ",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false
            }).then(function () {



                $http.post('/api/facility_update', facility).then(function (data) {


                    $scope.facility_list();
                    var sending=data.data;
                    swal(
                        'Feedback..',
                        'Updates Success...',
                        'success'
                    )
                })

            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
                if (dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        ' ',
                        'error'
                    )
                }
            })





        }


//  delete
        $scope.facility_delete=function (facility,id) {

            swal({
                title: 'Are you sure?',
                text: " ",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false
            }).then(function () {


                $http.get('/api/facility_delete/'+id).then(function(data) {


                    $scope.facility_list();
                    var sending=data.data;
                    swal(
                        'Feedback..',
                        'Item Deleted...',
                        'warning'
                    )
                })

            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
                if (dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        ' ',
                        'error'
                    )
                }
            })





        }




        //department registration CRUD



        $scope.department_registration=function (department) {

            swal({
                title: 'Are you sure?',
                text: " ",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false
            }).then(function () {


                $http.post('/api/department_registration',department).then(function(data) {


                    var sending=data.data;
                    swal(
                        'Feedback..',
                        sending,
                        'success'
                    )
                    $scope.department.department_name=="";
                    $scope.department_list();


                });

            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
                if (dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        ' ',
                        'error'
                    )
                }
            })



        }

//displaying department when function clicked
        $scope.department_list=function () {

            $http.get('/api/department_list').then(function(data) {
                $scope.departments=data.data;

            });
        };

     
        $scope.department_update=function (department) {
            ////console.log(department)

            swal({
                title: 'Are you sure?',
                text: " ",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false
            }).then(function () {


                $http.post('/api/department_update', department).then(function (data) {


                    $scope.department_list();
                    var sending=data.data;
                    swal(
                        'Feedback..',
                        'Updates Success...',
                        'success',2000
                    )
                })


            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
                if (dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        ' ',
                        'error'
                    )
                }
            })


        }


//  delete
        $scope.department_delete=function (department,id) {


            swal({
                title: 'Are you sure?',
                text: " ",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false
            }).then(function () {

                $http.get('/api/department_delete/'+id).then(function(data) {


                    $scope.department_list();
                    var sending=data.data;
                    swal(
                        'Feedback..',
                        'Item Deleted...',
                        'warning'
                    )
                })

            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
                if (dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        ' ',
                        'error'
                    )
                }
            })


        }


//Region Registrations
        $scope.region_registration=function (region) {
            ////console.log(region);
            $http.post('/api/region_registration',region).then(function(data) {
                // region.region.region_name ==' ';

                $scope.region_list();
                var sending=data.data;
                swal(
                    'Feedback..',
                    sending,
                    'success'
                )

            });

        }
        // $http.get('/api/user_rights/'+$rootScope.currentUser.id).then(function(data) {
        //     $scope.roles=data.data;
        //    //////console.log( $scope.roles);
        //
        // });
        //displaying region list when function clicked
        $scope.region_list=function () {

            $http.get('/api/region_registration').then(function(data) {
                $scope.regions=data.data;

            });
        }

        //displaying region list when browser loading
        $http.get('/api/region_registration').then(function(data) {
            $scope.regions=data.data;

        });


        //region update


        $scope.update=function (region) {
            swal({
                title: 'Are you sure?',
                text: " ",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false
            }).then(function () {


                $http.post('/api/update_region', region).then(function (data) {

                    $scope.e="";
                    //console.log($scope.e);
                    $scope.region_list();
                    swal(
                        'Feedback..',
                        'Updates Success...',
                        'success'
                    )
                })

            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
                if (dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        ' ',
                        'error'
                    )
                }
            })





        }


//regions delete
        $scope.delete=function (region,id) {
            swal({
                title: 'Are you sure?',
                text: " ",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false
            }).then(function () {

                $http.get('/api/delete/'+id).then(function(data) {


                    $scope.region_list();
                    swal(
                        'Feedback..',
                        'Deleted...',
                        'warning'
                    )
                })




            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
                if (dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        ' ',
                        'error'
                    )
                }
            })



        }



        //council_type_registration  CRUD


        $scope.council_type_registration=function (council_type) {

            $http.post('/api/council_type_registration',council_type).then(function(data) {

                $scope.council_type_list();
                var sending=data.data;
                swal(
                    'Feedback..',
                    sending,
                    'success'
                )

            });
        }


        $scope.council_type_list=function () {

            $http.get('/api/council_type_list').then(function(data) {
                $scope.council_types=data.data;

            });
        }

//displaying council types list when browser loading
        $http.get('/api/council_type_list').then(function(data) {
            $scope.council_types=data.data;

        });

        //council_type update


        $scope.council_type_update=function (council_type) {

            swal({
                title: 'Are you sure?',
                text: " ",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false
            }).then(function () {


                $http.post('/api/council_type_update', council_type).then(function (data) {


                    $scope.council_type_list();
                    swal(
                        'Feedback..',
                        'Updates Success...',
                        'success'
                    )
                })


            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
                if (dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        ' ',
                        'error'
                    )
                }
            })




        }


//council_type delete
        $scope.council_type_delete=function (council_type,id) {
            swal({
                title: 'Are you sure?',
                text: " ",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false
            }).then(function () {
                $http.get('/api/council_type_delete/'+id).then(function(data) {


                    $scope.council_type_list();
                    swal(
                        'Feedback..',
                        'Deleted...',
                        'warning'
                    )
                })

            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
                if (dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        ' ',
                        'error'
                    )
                }
            })



        }


        //councils registration CRUD



        $scope.council_registration=function (council) {

            $http.post('/api/council_registration',council).then(function(data) {

                $scope.council_list();
                var sending=data.data;
                swal(
                    'Feedback..',
                    sending,
                    'success'
                )
            });
        }

//displaying region list when function clicked
        $scope.council_list=function () {

            $http.get('/api/council_list').then(function(data) {
                $scope.councils=data.data;

            });
        }
//displaying region list when browser loading
        $http.get('/api/council_list').then(function(data) {
            $scope.councils=data.data;

        });

        //  update


        $scope.council_update=function (council) {
            ////console.log(council)
            swal({
                title: 'Are you sure?',
                text: " ",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false
            }).then(function () {
                $http.post('/api/council_update', council).then(function (data) {


                    $scope.council_list();
                    swal(
                        'Feedback..',
                        'Updates Success...',
                        'success'
                    )
                })


            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
                if (dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        ' ',
                        'error'
                    )
                }
            })



        }


//  delete
        $scope.council_delete=function (council,id) {
            swal({
                title: 'Are you sure?',
                text: " ",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false
            }).then(function () {

                $http.get('/api/council_delete/'+id).then(function(data) {


                    $scope.council_list();
                    swal(
                        'Feedback..',
                        'Deleted...',
                        'warning'
                    )
                })


            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
                if (dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        ' ',
                        'error'
                    )
                }
            })


        }




        //residence registration CRUD



        $scope.residence_registration=function (residence) {
            ////console.log(residence)
            $http.post('/api/residence_registration',residence).then(function(data) {


                $scope.residence_list();
                var sending=data.data;
                swal(
                    'Feedback..',
                    sending,
                    'success'
                )

            });
        }


        $scope.residence_list=function () {
return;
            $http.get('/api/residence_list').then(function(data) {
                $scope.residences=data.data;

            });

        }

        $scope.residence_list();

        //  update


        $scope.residence_update=function (residence) {
            var updates={'id':residence.residence_id,'residence_name':residence.residence_name,'council_id':residence.council_id};
            ////console.log(updates)
            swal({
                title: 'Are you sure?',
                text: " ",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false
            }).then(function () {



                $http.post('/api/residence_update', updates).then(function (data) {


                    $scope.residence_list();
                    swal(
                        'Feedback..',
                        'Updates Success...',
                        'success'
                    )
                })

            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
                if (dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        ' ',
                        'error'
                    )
                }
            })






        }


//  delete
        $scope.residence_delete=function (residence,id) {
            swal({
                title: 'Are you sure?',
                text: " ",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false
            }).then(function () {
                $http.get('/api/residence_delete/'+id).then(function(data) {

                    $scope.residence_list();
                    swal(
                        'Feedback..',
                        'Deleted...',
                        'warning'
                    )
                })



            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay',
                // 'close', and 'timer'
                if (dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        ' ',
                        'error'
                    )
                }
            })




        }



//Professioals Get Data From the database
        $scope.getprofessional=function () {

            $http.get('/api/professional_registration').then(function(data) {
                $scope.professional=data.data;
            });
        }

        //Professionals Registrations
        $scope.professional_registration=function (prof) {
            var comit=confirm('Are you sure you want to Register '+prof.prof_name);
            if(comit) {
                $http.post('/api/professional_registration',prof).then(function(data) {
                    //$scope.prof.prof_name="";
                    //$scope.region_list();
                    $scope.getprofessional();
                });
            }
        }

        $scope.getprofessional();
        //Professionals Update
        $scope.update_professional=function (professional) {
            var comit=confirm('Are you sure you want to Update '+professional.prof_name);
            if(comit) {
                $http.post('/api/update_professional', professional).then(function (data) {
                    $scope.good = professional.prof_name + " " + 'Successful Updated';
                    $scope.kol = data.status;
                    //$scope.region_list();
                    $scope.getprofessional();
                })
            }
            else{
                return false;
            }
        }

        //Professionals delete
        $scope.deleteprof=function (professional) {
            var comit=confirm('Are you sure you want to delete'+" "+professional.prof_name);
            if(comit){
                $http.get('/api/deleteprof/'+professional.id).then(function(data) {
                    $scope.getprofessional();
                })
            }
            else {
                return false;
            }
        }

    }
})();
