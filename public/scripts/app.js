(function() {
    'use strict';

    angular.module('authApp', [
        'ui.router',
        'satellizer',
        'ui.bootstrap',
        'ui.bootstrap.modal',
        'angularjs-datetime-picker',
        'angularUtils.directives.dirPagination',
        'chart.js',
        'toastr',
        'ngChatbox',
        'ngMaterial',
        'ngMessages',
        'perfect_scrollbar',
        'ngMdIcons',
        'angAccordion',
		'angular-loading-bar',
        'vAccordion',
        'datatables',
		'webcam',
		
    ]);
    angular.module('authApp').config(function($stateProvider, $urlRouterProvider, $authProvider, $httpProvider, $provide, $mdThemingProvider, $mdIconProvider, $mdDateLocaleProvider,cfpLoadingBarProvider) {
        $mdDateLocaleProvider.formatDate = function(date) {
				return moment(date).format('YYYY-MM-DD');
        };
		
		cfpLoadingBarProvider.includeSpinner = false;

        $mdThemingProvider.theme('default')
            .primaryPalette('teal')
            .accentPalette('red');
        $mdIconProvider.defaultIconSet("/svg/avatars.svg",128)
            .icon("menu", "/svg/menu.svg", 512)
            .icon("dashboard", "/svg/home.svg", 512)
            .icon("schoolSettings", "/svg/addViews.svg", 512)
            .icon("primary_applicants_registration", "/svg/home.svg", 512)            
            .icon("logout", "/svg/logout.svg", 512)
            .icon("default_profile", "/svg/default_profile.svg", 512)
            .icon("permits", "/svg/addRoles.svg", 512)
            .icon("applications", "/svg/addRoles.svg", 512)
			.icon("reported_to_councils", "/svg/Appointment.svg", 512)           
            .icon("applicants_registration", "/svg/addModules.svg", 512)
            .icon("normalRegistration", "/svg/normalRegistration.svg", 512)
            .icon("system", "/svg/system.svg", 512)           
            .icon("notifications", "/svg/notifications.svg", 512)
            .icon("collegeSettings", "/svg/notifications.svg", 512);
            
        function redirectWhenLoggedOut($q, $injector) {
            return {
                responseError: function(rejection) {

                    // Need to use $injector.get to bring in $state or else we get
                    // a circular dependency error
                    var $state = $injector.get('$state');


                    // Instead of checking for a status code of 400 which might be used
                    // for other reasons in Laravel, we check for the specific rejection
                    // reasons to tell us if we need to redirect to the login state
                    var rejectionReasons = ['token_not_provided', 'token_expired', 'token_absent', 'token_invalid'];

                    // Loop through each rejection reason and redirect to the login
                    // state if one is encountered
                    angular.forEach(rejectionReasons, function(value, key) {

                        if (rejection.data.error === value) {

                            // If we get a rejection corresponding to one of the reasons
                            // in our array, we know we need to authenticate the user so
                            // we can remove the current user from local storage
                            localStorage.removeItem('user');

                            // Send the user to the auth state so they can login
                            $state.go('auth');
                        }
                    });
                    return $q.reject(rejection);
                }
            };
        }

        // Setup for the $httpInterceptor
        $provide.factory('redirectWhenLoggedOut', redirectWhenLoggedOut);

        // Push the new factory onto the $http interceptor array
        $httpProvider.interceptors.push('redirectWhenLoggedOut');

        // Satellizer configuration that specifies which API
        // route the JWT should be retrieved from
        $authProvider.loginUrl = '/api/authenticate';

        // Redirect to the auth state if any other states
        // are requested other than users
        $urlRouterProvider.otherwise('/auth');

        $stateProvider
            .state('master', {
                abstract: true,
                controller: 'AuthController as auth',
                views: {
                    '@': {
                        templateUrl: '/views/layouts/master.tmpl.html',
                    }
                }
            })
            .state('auth', {
                url: '/auth',
                templateUrl: '/views/authView.html',
                controller: 'AuthController as auth'
            })
            .state('dashboard', {
                url: '/dashboard',
                parent: 'master',
                templateUrl: '/views/welcome.html',
                controller: 'UserController as user'
            })
            .state('schoolSettings', {
                url: '/schoolSettings',
                parent: 'master',
                templateUrl: '/scripts/modules/schools/templates/school-dashboard.html',
                controller: 'schoolController'
            }) 
			.state('permits', {
                url: '/permits',
                parent: 'master',
               templateUrl: '/views/templates/permits/permits-dashboard.html',
                controller: 'permitsController'
            })

            .state('collegeSettings', {
                url: '/collegeSettings',
                parent: 'master',
               templateUrl: '/views/templates/colleges/colleges-dashboard.html',
                controller: 'collegeController'
            })

            
			.state('applicants_registration', {
                url: '/applicants_registration',
                parent: 'master',
               templateUrl: '/views/templates/applicants/applicants-dashboard.html',
                controller: 'applicantsController'
            })

                .state('primary_applicants_registration', {
                url: '/primary_applicants_registration',
                parent: 'master',
               templateUrl: '/views/templates/applicants/primary-applicants-dashboard.html',
                controller: 'applicantsController'
            })
			.state('reported_to_councils', {
                url: '/reported_to_councils',
                parent: 'master',
              templateUrl: '/views/templates/ded/report_councils.html',
                controller: 'applicantsController'
            })
			.state('applications', {
                url: '/applications',
                parent: 'master',
               templateUrl: '/views/templates/applicants/application-dashboard.html',
                controller: 'applicationsController'
            })
			
            .state('system', {
                url: '/system',
                parent: 'master',
                templateUrl: '/views/admin_template.html',
                controller: 'adminController'
            })
           		            
            .state('UsersList', {
                url: '/UsersList',
                parent: 'master',
                templateUrl: '/views/UsersList.html',
                controller: 'userSettingController as user'
            })
            .state('password_resset', {
                url: '/password_resset',
                parent: 'master',
                templateUrl: '/views/password_resset.html',
                controller: 'userSettingController as user'
            });
            
    });

    angular.module('authApp').run(function($rootScope,$http, $state, $location) {
        // $stateChangeStart is fired whenever the state changes. We can use some parameters
        // such as toState to hook into details about the state as it is changing
        $rootScope.$on('$stateChangeStart', function(event, toState) {

            // Grab the user from local storage and parse it to an object
            var user = JSON.parse(localStorage.getItem('user'));
            if (!user && toState.name != "auth") {

                //event.preventDefault();

                // go to the "main" state which in our case is users
                $location.path("/auth");
            }
			
			/*
			var stored_data = window.localStorage.getItem("stored_residences");
            if(!stored_data) {
				$http
					.post('/api/searchResidences' , {searchKey:"%"})
					.then(function(data) {
						localStorage.setItem('stored_residences', JSON.stringify(data.data));
						
				});
            }
			
			*/

            

            // If there is any user data in local storage then the user is quite
            // likely authenticated. If their token is expired, or if they are
            // otherwise not actually authenticated, they will be redirected to
            // the auth state because of the rejected request anyway
            if (user) {

                // The user's authenticated state gets flipped to
                // true so we can now show parts of the UI that rely
                // on the user being logged in
                $rootScope.authenticated = true;

                // Putting the user's data on $rootScope allows
                // us to access it anywhere across the app. Here
                // we are grabbing what is in local storage
                $rootScope.currentUser = user;

                // If the user is logged in and we hit the auth route we don't need
                // to stay there and can send the user to the main state
                if (toState.name === "auth") {

                    // Preventing the default behavior allows us to use $state.go
                    // to change states
                    event.preventDefault();
                    // go to the "main" state which in our case is users
                    $state.go('dashboard');


                }

                if (toState.name !== "auth" && $rootScope.currentUser.length === 0) {

                    // Preventing the default behavior allows us to use $state.go
                    // to change states
                    event.preventDefault();

                    // go to the "main" state which in our case is users
                    $state.go('auth');
                }
            }
        });
    });
})();


(function() {
    angular.module('authApp').directive('verticalTabs', function() {
        return {
            restrict: 'E',
            transclude: true,
            scope: {},
            controller: ['$scope', function MyTabsController($scope) {
                var panes = $scope.panes = [];

                $scope.select = function(pane) {
                    angular.forEach(panes, function(pane) {
                        pane.selected = false;
                    });
                    pane.selected = true;
                };

                this.addPane = function(pane) {
                    if (panes.length === 0) {
                        $scope.select(pane);
                    }
                    panes.push(pane);
                };
            }],
            templateUrl: '/views/layouts/vertical_tabs/vertical-tabs.html',
        };
    })
        .directive('verticalTabPane', function() {
            return {
                require: '^^verticalTabs',
                restrict: 'E',
                transclude: true,
                scope: {
                    title: '@'
                },
                link: function(scope, element, attrs, tabsCtrl) {
                    tabsCtrl.addPane(scope);
                },
                templateUrl: '/views/layouts/vertical_tabs/vertical-tab-pane.html'
            };
        });
})();


(function() {
    angular.module('authApp').directive('zoom', function() {

        return {
            restrict: 'EA',
            replace: true,
            template: '<div class="magnify-container" data-ng-style="getContainerStyle()">' +
            '<div class="magnify-glass" data-ng-style="getGlassStyle()"></div>' +
            '<img class="magnify-image" data-ng-src="{{ imageSrc }}"/>' +
            '</div>',
            scope: {
                imageSrc: '@',
                imageWidth: '=',
                imageHeight: '=',
                glassWidth: '=',
                glassHeight: '='
            },
            link: function (scope, element) {
                var glass = element.find('div'),
                    image = element.find('img'),
                    el, nWidth, nHeight, magnifyCSS;

                // if touch devices, do something
                if (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch) {
                    return;
                }
                element.on('mousemove', function (evt) {
                    el = angular.extend(scope.getOffset(element[0]), {
                        width: element[0].offsetWidth,
                        height: element[0].offsetHeight,
                        imageWidth: image[0].offsetWidth,
                        imageHeight: image[0].offsetHeight,
                        glassWidth: glass[0].offsetWidth,
                        glassHeight: glass[0].offsetHeight
                    });
                    magnifyCSS = scope.magnify(evt);

                    if (magnifyCSS) {
                        glass.css( magnifyCSS );
                    }
                })
                    .on('mouseout', function () {
                        glass.on('mouseleave', function () {
                            glass.css({
                                opacity: 0,
                                filter: 'alpha(opacity=0)'
                            });
                        });
                    });

                scope.magnify = function (evt) {
                    var mx, my, rx, ry, px, py, bgp, img;

                    if (!nWidth && !nHeight) {
                        img = new Image();
                        img.onload = function () {
                            nWidth = img.width;
                            nHeight = img.height;
                        };
                        img.src = scope.imageSrc;
                    } else {
                        // IE8 uses evt.x and evt.y
                        mx = (evt.pageX) ? (evt.pageX - el.left) : evt.x;
                        my = (evt.pageY) ? (evt.pageY - el.top) : evt.y;

                        if (mx < el.width && my < el.height && mx > 0 && my > 0) {
                            glass.css({
                                opacity: 1,
                                'z-index': 1,
                                filter: 'alpha(opacity=100)'
                            });
                        } else {
                            glass.css({
                                opacity: 0,
                                'z-index': -1,
                                filter: 'alpha(opacity=0)'
                            });
                            return;
                        }

                        rx = Math.round(mx/el.imageWidth*nWidth - el.glassWidth/2)*-1;
                        ry = Math.round(my/el.imageHeight*nHeight - el.glassHeight/2)*-1;
                        bgp = rx + 'px ' + ry + 'px';

                        px = mx - el.glassWidth/2;
                        py = my - el.glassHeight/2;

                        return { left: px+'px', top: py+'px', backgroundPosition: bgp };
                    }
                    return;
                };

                scope.getOffset = function (_el) {
                    var de = document.documentElement;
                    var box = _el.getBoundingClientRect();
                    var top = box.top + window.pageYOffset - de.clientTop;
                    var left = box.left + window.pageXOffset - de.clientLeft;
                    return { top: top, left: left };
                };

                scope.getContainerStyle = function () {
                    return {
                        width: (scope.imageWidth) ? scope.imageWidth + 'px' : '',
                        height: (scope.imageHeight) ? scope.imageHeight + 'px' : ''
                    };
                };

                scope.getGlassStyle = function () {
                    return {
                        background: 'url("' + scope.imageSrc + '") no-repeat',
                        width: (scope.glassWidth) ? scope.glassWidth + 'px' : '',
                        height: (scope.glassHeight) ? scope.glassHeight + 'px' : ''
                    };
                };
            }
        };

    });
})();
