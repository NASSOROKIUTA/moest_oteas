/**
 * Created by USER on 2017-02-13.
 */

(function() {
    'use strict';
    angular
        .module('authApp').directive('ngFiles', ['$parse', function($parse) {

            function fn_link(scope, element, attrs) {
                var onChange = $parse(attrs.ngFiles);
                element.on('change', function(event) {
                    onChange(scope, {
                        $files: event.target.files
                    });
                });
            };

            return {
                link: fn_link
            }
        }]).controller('rolesController', rolesController);

    function rolesController($http, $auth, $rootScope, $state, $location, $scope, toastr) {

        var user = $rootScope.currentUser;
        var user_name = $rootScope.currentUser.id;
        var facility_id = $rootScope.currentUser.facility_id;
        $scope.messages_counts = 0;

        $scope.messages = ['Write your notification to staff members'];
        var sender_text = "Hi, " + $rootScope.currentUser.name;
        $scope.messages.push({
            time: new Date().toISOString().replace(/T/, ' ').substr(0, 19),
            text: sender_text,
            own: 'their'
        });



        $scope.countNotifications = function(sms) {


            $http.get('/api/getNotifications').then(function(data) {
                var message_sent = data.data[3];
                var messages_received = data.data[1][1];
                $scope.messages_counts = data.data[2];
                $scope.readMessages = message_sent;

                $scope.messages.push({
                    time: messages_received.updated_at,
                    text: messages_received.message,
                    own: 'their'
                });

                $scope.messages.push({
                    //time: time_sent_text,
                    text: sms,
                    own: 'mine'
                });
                //toastr.success('', 'Message Sent');
                $scope.my_text = null;
            });
        };


        $scope.countNotifications();


        $scope.add = function(sms) {

            var sendSmsToGroup = {
                "delete_sms": 1,
                "receiver_id": null,
                "sender_id": user_id,
                "message": sms
            };

            $http.post('/api/sendSmsToGroup', sendSmsToGroup).then(function(data) {
                var message_sent = data.data[0][0];
                var messages_received = data.data[1][1];
                var time_sent_text = data.data[2][0];
                $scope.messages_counts = data.data[3];

                $scope.messages.push({
                    time: messages_received.updated_at,
                    text: messages_received.message,
                    own: 'their'
                });

                $scope.messages.push({
                    time: time_sent_text,
                    text: sms,
                    own: 'mine'
                });
                //toastr.success('', 'Message Sent');
                $scope.my_text = null;
            });
        };


        var formdata = new FormData();

        $scope.getTheFiles = function($files) {
            angular.forEach($files, function(value, key) {
                formdata.append(key, value);

            });
            formdata.append('photo_for', user_name);
        };


        // NOW UPLOAD THE FILES.
        $scope.uploadFiles = function() {

            var request = {
                method: 'POST',
                url: '/api/' + 'fileupload',
                data: formdata,
                headers: {
                    'Content-Type': undefined
                }

            };

            // SEND THE FILES.
            $http(request).then(function(data) {
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
                .then(function() {});
        }





        $scope.runViews = function() {

            $http.get('/api/userView/' + facility_id).then(function(data) {
                $scope.icons = data.data;

                var getstatus = data.status;
                if (getstatus == 200) {
                    var getdata = 'ALL SYSTEM VIEWS WERE SUCCESSFULLY CREATED';
                } else {
                    var getdata = 'SOME THING WENT WRONG,PLEASE CONTACT DATABASE ADMINISTRATOR TO REVIEW YOUR TABLE STRUCTURE';


                }
                swal({
                    title: '',
                    html: $('<div>')
                        .addClass('some-class')
                        .text('' + getdata + ''),
                    animation: false,
                    customClass: 'animated tada'
                });

            });
        }


        $http.get('/api/getPerm').then(function(data) {
            $scope.perms = data.data;
            //console.log($scope.perms);

        });

        $http.get('/api/geticon').then(function(data) {
            $scope.icons = data.data;

        });


        $http.get('/api/getUserImage/' + user_name).then(function(data) {
            //$scope.photo = '/uploads/' + data.data[0].photo_path;
            //console.log(data);

        });

        $http.get('/api/getRoles').then(function(data) {
            $scope.roles = data.data;

        });

        $http.get('/api/user_list/'+facility_id).then(function(data) {
            $scope.users = data.data;
            //console.log($scope.users);
        });


        $scope.savePermRoles = function(permRoles) {

            var perm_list = $scope.labcartsxx;
            angular.forEach(perm_list, function(value, key) {
                //console.log(key + ': ' + value);
            });
            /**
			    $http.post('/api/perm_role',$scope.labcartsxx).then(function(data) {
                //console.log(data);	            
                });	**/
        }



        $scope.saveModules = function(modules) {
            var menu = {
                'main_menu': 1,
                'module': modules.module,
                'title': modules.title,
                'glyphicons': modules.glyphicons.icon_class
            };
            //console.log(menu);
            $http.post('/api/addPermission', menu).then(function(data) {
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
            }).then(function(data) {
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


        $scope.saveRoles = function(roles) {
            var title = roles.title;
            var parents = roles.parent;

            var save_roles = {
                'title': title,
                'parent': parents
            };


            //console.log(save_roles);
            $http.post('/api/addRoles', save_roles).then(function(data) {
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
            }).then(function(data) {

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

        $scope.logout = function() {
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

        $http.get('/api/getUsermenu/' + user_id).then(function(data) {
            $scope.menu = data.data;
            //console.log($scope.menu);

        });



        $http.get('/api/getAuthorization/' + user_id + ',' + state_name).then(function(data) {
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

        $scope.checkTest = function(item, permRoles) {

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

                var perm_roles = {
                    'permission_id': permission_id,
                    'role_id': role_id,
                    'grant': 1
                };

                $http.get('/api/getPermName/' + permission_id).then(function(data) {
                    $scope.perms = data.data;
                    //console.log(role_id);
                    var perm_name = 'Permission ' + $scope.perms + ' was selected and SAVED in the SYSTEM';

                    $http.post('/api/perm_role', perm_roles).then(function(data) {
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

                if ((item.selected) == true) {} else if ((item.selected) == false) {
                    var indexremove = $scope.labcartsxx.indexOf(item);
                    $scope.labcartsxx.splice(indexremove, 1);
                }

            }
        }

        $scope.checkUserPerms = function(item, permUsers) {

            if (angular.isDefined(permUsers) == false) {
                swal({
                    title: 'ERROR:',
                    html: $('<div>')
                        .addClass('some-class')
                        .text('PLEASE SELECT THE USER ,FROM SEARCH BOX ABOVE BEFORE PROCEED'),
                    animation: false,
                    customClass: 'animated tada'
                });
            } else {
                var permission_id = item.id;
                var user_id = permUsers.user.id;
                //console.log(user_id);
                var perm_users = {
                    'permission_id': permission_id,
                    'user_id': user_id,
                    'grant': 1
                };

                $http.get('/api/getPermName/' + permission_id).then(function(data) {
                    $scope.perms = data.data;
                    var perm_name = 'Permission ' + $scope.perms + ' was selected and SAVED in the SYSTEM';

                    $http.post('/api/perm_user', perm_users).then(function(data) {
                        var getstatus = data.status;
                        var getdata = data.data;

                        swal({
                            title: '',
                            html: $('<div>')
                                .addClass('some-class')
                                .text('' + getdata + ''),
                            animation: false,
                            customClass: 'animated tada'
                        });


                    });

                });


            }
        }




    }
})();
