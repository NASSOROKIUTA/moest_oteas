<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});
//gepg routes
Route::group(['prefix' => 'apps'], function()
{
    Route::post('getColleges','placement\placementController@getColleges');
	Route::post('verifyApplicantInfo','placement\placementController@verifyApplicantInfo');
	Route::post('saveAddress','placement\placementController@saveAddress');
	Route::post('register', 'placement\placementController@register');
	Route::post('makeApplications', 'placement\placementController@makeApplications');
	Route::post('makePlacementPrimary', 'placement\placementController@makePlacementPrimary');
	Route::post('makePlacementPrimarySchool', 'placement\placementController@makePlacementPrimarySchool');
	Route::post('getApplications', 'placement\placementController@getApplications');
	Route::post('getListSelectedToCouncils', 'placement\placementController@getListSelectedToCouncils');
	Route::post('getListSelectedToSchools', 'placement\placementController@getListSelectedToSchools');
	Route::post('getApplicationLists', 'placement\placementController@getApplicationLists');
	Route::post('getListSelectedToThisCouncil', 'placement\placementController@getListSelectedToThisCouncil');
	Route::post('saveApplicantPhoto', 'placement\placementController@saveApplicantPhoto');
	///residence routes
    
	
	
	});
	
Route::group(['prefix' => 'api'], function()
{
	Route::post('getAvailableChances', 'placement\placementController@getAvailableChances');
	Route::post('searchCouncil', 'User\UsersRegistrationController@searchCouncil');
    Route::get('update', 'System_Updates\Updater_Init@Init');  	
    Route::resource('authenticate', 'AuthenticateController', ['only' => ['index']]);
    Route::post('authenticate', 'AuthenticateController@authenticate');
    Route::post('sendSmsToGroup', 'admin\stateController@sendSmsToGroup');
    Route::get('getNotifications', 'admin\stateController@getNotifications');
    Route::post('saveRoutingKeys', 'admin\stateController@saveRoutingKeys');
	Route::get('authenticate/user', 'AuthenticateController@getAuthenticatedUser');
	//Route::get('logout/user', 'AuthenticateController@logout');
	Route::get('logout/{user_id}', '\App\Http\Controllers\Auth\LoginController@logout');
    //routes for editing user
    Route::post('update_user', 'AuthenticateController@update_user');
    Route::post('register', 'AuthenticateController@register');
    Route::get('delete/{id}', 'AuthenticateController@delete');
    Route::get('edit/{id}', 'AuthenticateController@edit');

        //regions routes
    Route::resource('region_registration', 'Region\RegionsController');
    Route::get('delete/{id}', 'Region\RegionsController@delete');
    Route::post('update_region', 'Region\RegionsController@update_region');

    //proffesionals routes
	Route::resource('professional_registration', 'Professional\ProfessionalController');
	Route::post('update_professional', 'Professional\ProfessionalController@update_professional');
	Route::get('deleteprof/{id}', 'Professional\ProfessionalController@deleteprof');
	
	//Country zone routes
	Route::resource('country_zone_registration', 'Country\CountryController');
	Route::post('country_zone_registration', 'Country\CountryController@country_zone_registration');
	Route::get('getcountry_zone', 'Country\CountryController@getcountry_zone');
	Route::post('update_country_zone', 'Country\CountryController@update_country_zone');
	Route::get('deletecountryzone/{id}', 'Country\CountryController@deletecountryzone');
	
	//Country Registration routes
	Route::resource('country_name_registration', 'Country\CountryController');
	Route::post('country_name_registration', 'Country\CountryController@country_name_registration');
	Route::get('getcountries', 'Country\CountryController@getcountries');
	Route::post('update_country_name', 'Country\CountryController@update_country_name');
	Route::get('deletecountry/{id}', 'Country\CountryController@deletecountry');
	
	//Tribe Routes
	Route::post('tribe_registration', 'Tribe\TribeController@tribe_registration');
	Route::get('gettribe_name', 'Tribe\TribeController@gettribe_name');
	Route::post('updatetribe', 'Tribe\TribeController@updatetribe');
	Route::get('deletetribe/{id}', 'Tribe\TribeController@deletetribe');
	
	// Marital Routes
	Route::post('marital_registration', 'Marital\MaritalController@marital_registration');
	Route::get('getmarital_status', 'Marital\MaritalController@getmarital_status');
	Route::post('updatemaritalstatus', 'Marital\MaritalController@updatemaritalstatus');
	Route::get('deletemaritalstatus/{id}', 'Marital\MaritalController@deletemaritalstatus');
	
	
  ///residence routes
    Route::post('residence_registration', 'Residence\ResidenceController@residence_registration');
    Route::get('residence_list', 'Residence\ResidenceController@residence_list');
    Route::get('residence_delete/{id}', 'Residence\ResidenceController@residence_delete');
    Route::post('residence_update', 'Residence\ResidenceController@residence_update');
	//councils routes
    Route::post('council_registration', 'Region\RegionsController@council_registration');
    Route::get('council_list', 'Region\RegionsController@council_list');
    Route::get('council_delete/{id}', 'Region\RegionsController@council_delete');
    Route::post('council_update', 'Region\RegionsController@council_update');
	
	//council_type_registration routes
    Route::post('council_type_registration', 'Region\RegionsController@council_type_registration');
    Route::get('council_type_list', 'Region\RegionsController@council_type_list');
    Route::get('council_type_delete/{id}', 'Region\RegionsController@council_type_delete');
    Route::post('council_type_update', 'Region\RegionsController@council_type_update');
	
	///users routes
    Route::post('user_registration', 'User\UsersRegistrationController@user_registration');
    Route::get('user_list', 'User\UsersRegistrationController@user_list');
    Route::get('user_delete/{id}', 'User\UsersRegistrationController@user_delete');
    Route::post('user_update', 'User\UsersRegistrationController@user_update');
		
	//SETUP DATA CONTROLLER   @ NASSORO S KIUTA
	Route::post('getSetupData', 'placement\placementController@getSetupData');
	Route::post('saveSchool', 'placement\placementController@saveSchool');
	Route::post('getSchool', 'placement\placementController@getSchool');
	Route::post('getRegions', 'placement\placementController@getRegions');
	Route::post('getCouncil', 'placement\placementController@getCouncils');
	Route::post('getSubject', 'placement\placementController@getSubjects');
	Route::post('savePermit', 'placement\placementController@savePermit');
	Route::post('getPermits', 'placement\placementController@getPermits');
	
	
	//SYSTEM MENU CONTROLLER   @ NASSORO S KIUTA
	Route::post('adminRegistration', 'User\UsersRegistrationController@adminRegistration');
	
	Route::get('getUsermenu/{id}', 'admin\menuController@getUserMenu');
	Route::get('getLoginUserDetails/{id}', 'admin\menuController@getLoginUserDetails');
	Route::get('getAuthorization/{id},{state_name}', 'admin\menuController@getAuthorization');
	Route::post('addPermission', 'admin\stateController@checkIfStateExists');
	Route::post('addRoles', 'admin\stateController@checkIfRoleExists');
	Route::post('perm_user', 'admin\stateController@checkIfPermissionUserExists');
	Route::get('getAssignedMenu/{user_id}', 'admin\stateController@getAssignedMenu');
	Route::get('getAssignedRole/{role_id}', 'admin\stateController@getAssignedRole');
	Route::get('getSystemActivity', 'admin\stateController@getSystemActivity');
	Route::post('perm_role', 'admin\stateController@checkIfPermissionRoleExists');
	Route::post('removeAccess', 'admin\stateController@removeAccess');
	Route::post('removeRoleAccess', 'admin\stateController@removeRoleAccess');
	Route::get('geticon', 'admin\stateController@geticon');
	Route::get('getPerm', 'admin\stateController@getPermissions');
	Route::get('getPermName/{id}', 'admin\stateController@getPermissionName');
	Route::get('getUserName/{id}', 'admin\stateController@getUserName');
	Route::get('getRoles', 'admin\stateController@getRoles');
	Route::get('userView/', 'admin\stateController@userView');
	Route::post('fileupload', 'admin\stateController@uploadEntry');
	Route::post('searchUser', 'admin\stateController@searchUser');
	Route::post('changeStatus', 'admin\stateController@changeStatus');
	Route::get('getUserImage/{id}', 'admin\stateController@getUserImage');
	Route::post('installSystem', 'installation\installationController@installSystem');
	Route::post('createSchema', 'installation\installationController@createSchema');
	Route::post('createSeeder', 'installation\installationController@createSeeder');
	Route::post('createNewDatabase', 'admin\stateController@createNewDatabase');
	   
   
   //regions routes
    Route::resource('region_registration', 'Region\RegionsController');
    Route::get('delete/{id}', 'Region\RegionsController@delete');
    Route::post('update_region', 'Region\RegionsController@update_region');

//council_type_registration routes
    Route::post('council_type_registration', 'Region\RegionsController@council_type_registration');
    Route::get('council_type_list', 'Region\RegionsController@council_type_list');
    Route::get('council_type_delete/{id}', 'Region\RegionsController@council_type_delete');
    Route::post('council_type_update', 'Region\RegionsController@council_type_update');
//councils routes
    Route::post('council_registration', 'Region\RegionsController@council_registration');
    Route::get('council_list', 'Region\RegionsController@council_list');
    Route::get('council_delete/{id}', 'Region\RegionsController@council_delete');
    Route::post('council_update', 'Region\RegionsController@council_update');
    ///users routes
    Route::post('user_registration', 'User\UsersRegistrationController@user_registration');
    Route::get('user_delete/{id}', 'User\UsersRegistrationController@user_delete');
    Route::post('user_update', 'User\UsersRegistrationController@user_update');
    Route::post('check_password', 'User\UsersRegistrationController@check_password');
    Route::post('reset_password', 'User\UsersRegistrationController@reset_password');
    ///residence routes
    Route::post('residence_registration', 'Residence\ResidenceController@residence_registration');
    Route::get('residence_list', 'Residence\ResidenceController@residence_list');
    Route::get('residence_delete/{id}', 'Residence\ResidenceController@residence_delete');
    Route::post('residence_update', 'Residence\ResidenceController@residence_update');
   //excell
   Route::get('importExport', 'MaatwebsiteDemoController@importExport');
   Route::get('downloadExcel/{type}', 'MaatwebsiteDemoController@downloadExcel');
   Route::post('importExcel', 'MaatwebsiteDemoController@importExcel');
   Route::post('schoolUpload', 'excel\excelController@schoolUpload');
   Route::post('teachersRequirementPerSchool', 'excel\excelController@teachersRequirementPerSchool');
   Route::post('secondaryTeachersRequirementPerSchool', 'excel\excelController@secondaryTeachersRequirementPerSchool');
   Route::get('downloadExcel/{type}', 'excel\excelController@downloadExcel');
});
