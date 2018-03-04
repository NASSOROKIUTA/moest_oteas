(function () {
    'use strict';

    angular.module('authApp').factory('Helper', ['$mdToast', '$http', function($mdToast, $http) {
        
		return {
			
            notify: function (message) {
                $mdToast.show($mdToast.simple()
                    .position('top right')
                    .content(message)
                    .hideDelay(3000)
                );
            },
			searchLabTechnologist : function (text) {
                return $http.post('/api/searchLabTechnologists?keyWord=' + text)
                    .then(function (response) {
                        return response;
                    });
            },
            alert: function (message) {
                $mdToast.show($mdToast.simple()
                    .position('top left')
                    .content(message)
                    .hideDelay(4000)
                );
            },
			searchItemObservations: function (searchKey) {
				var postData={item_name:searchKey};
                return $http.post('/api/searchItemObserved',postData)
                    .then(function (response) {
                        return response;
                    });
            },
			
			 systemNotification : function (user_id) {
				 var message='';
               setInterval(function(){ 
     return $http.get('/api/mynotifications/'+ user_id)
                    .then(function (data) {	
					if(data.data.length>0){
						message=data.data[0].message;				
					$mdToast.show($mdToast.simple()
                    .position('top right')
                    .content(message)
                    .hideDelay(30000)					
                );
				}

                    });
            }, 360000); // Th
                
            },
			

           //Patients Record Search
            getPatients : function (text) {
                return $http.post('/api/search-patients?name=' + text)
                    .then(function (response) {
                        return response;
                    });
            },
            getPatientToEdit : function (text) {
                return $http.post('/api/editable-patients?name=' + text)
                    .then(function (response) {
                        return response;
                    });
            },
            getAllPatient : function (text) {
                return $http.post('/api/get-patient?name=' + text)
                    .then(function (response) {
                        return response;
                    });
            },

			searchItemToServiceInWard : function (searchKey,selectedPatient,facility_id) {
				var dataToPost={patient_category_id:selectedPatient.patient_category_id,search:searchKey,facility_id:facility_id};				
                return $http.post('/api/getListItemToServiceInWard',dataToPost)
                    .then(function (response) {
                        return response;
                    });
            },
			
			searchUser : function (email,facility_id) {
				var dataToPost={email:email,facility_id:facility_id};				
                return $http.post('/api/searchUser',dataToPost)
                    .then(function (response) {
                        return response;
                    });
            },
			getRegion : function (searchKey) {
				var dataToPost={searchKey:searchKey};				
                return $http.post('/api/getRegions',dataToPost)
                    .then(function (response) {
                        return response;
                    });
            },
            //Seach Trauma Patients Consultation
            TraumaSeachQueue : function (text,facility_id) {
                return $http.post('/api/trauma-patients',{
                    "name": text,
                    "facility_id": facility_id
                })
                    .then(function (response) {
                        return response;
                    });
            },
            //Seach Trauma Patients Investigation
            TraumaTreatmentSeachQueue : function (text,facility_id) {
                return $http.post('/api/traumaInv-patients',{
                    "name": text,
                    "facility_id": facility_id
                })
                    .then(function (response) {
                        return response;
                    });
            },
            Radiopatients : function (text) {
                return $http.post('/api/getAllRadiographics?name=' + text)
                    .then(function (response) {
                        return response;
                    });
            },
            getResidence : function (text) {
                return $http.post('/api/residence-patients?name=' + text)
                    .then(function (response) {
                        return response;
                    });
            },
            getVitalsUsers : function (text) {
                return $http.post('/api/vitals-patients?name=' + text)
                    .then(function (response) {
                        return response;
                    });
            },
            getAllItems : function (text) {
                return $http.post('/api/item-search?name=' + text)
                    .then(function (response) {
                        return response;
                    });
            },
            getAppointmentCardio : function (text,facility_id) {
                return $http.post('/api/cardiac-apointment',{
                    "name": text,
                    "facility_id": facility_id
                })
                    .then(function (response) {
                        return response;
                    });
            },
            getAppointmentPhysio : function (text,facility_id) {
                return $http.post('/api/physio-apointment',{
                    "name": text,
                    "facility_id": facility_id
                })
                    .then(function (response) {
                        return response;
                    });
            },getRadiologyPatients : function (text,facility_id) {
                return $http.post('/api/xray-patients',{
                    "name": text,
                    "facility_id": facility_id
                })
                    .then(function (response) {
                        return response;
                    });
            },
            getAppointmentCardioRefer : function (text,facility_id) {
                console.log(facility_id);
                return $http.post('/api/cardiac-refer', {
                    "name": text,
                    "facility_id": facility_id
                })
                    .then(function (response) {
                        return response;
                    });
            },
            getAppointmentPhysioRefer : function (text,facility_id) {
                console.log(facility_id);
                return $http.post('/api/physio-refer', {
                    "name": text,
                    "facility_id": facility_id
                })
                    .then(function (response) {
                        return response;
                    });
            },
			
			overlay: function (flag = false) {
				if(flag==true){
					var overlayDiv = jQuery('<div id="overlay" style="text-align:center"><img src="public/custom/img/loading.gif" /></div>');
					overlayDiv.appendTo(document.body);
				}else
					$("#overlay").remove();
					
			},


			temporaryError: function (target,custom_msg=''){
				return "A temporary error occured in the server while loading <b>"+target+"</b><br />"+(custom_msg != '' ? "<b><i>"+ custom_msg +"<i></b>": "This is usually an <b><i>arbitrary error<i></b>. <b>You may switch back to the dashboard and enter the module or re-attempt the action again</b>.")+"<br />If the error persists, please contact IT support.";
			},
			
			genericError: function (action,custom_msg=''){
				return "A temporary error occured in the server while <b>"+action+"</b><br />"+(custom_msg != '' ? "<b><i>"+ custom_msg +"<i></b>" : "This is usually an <b><i>arbitrary error<i></b>. <b>Your action may not have been effected, Retry</b>.")+"<br />If the error persists, please contact IT support.";
			}
        }
    }]);
})();