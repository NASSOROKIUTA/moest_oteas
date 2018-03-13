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
		
            alert: function (message) {
                $mdToast.show($mdToast.simple()
                    .position('top left')
                    .content(message)
                    .hideDelay(4000)
                );
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
			
		
			getRegion : function (searchKey) {
				var dataToPost={searchKey:searchKey};				
                return $http.post('/api/getRegions',dataToPost)
                    .then(function (response) {
                        return response;
                    });
            },

            getRegionRequirements : function (searchKey) {
                var dataToPost={searchKey:searchKey};               
                return $http.post('/api/getRegionRequirements',dataToPost)
                    .then(function (response) {
                        return response;
                    });
            },


            
       
          
            getResidence : function (text) {
                return $http.post('/api/searchResidence?name=' + text)
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