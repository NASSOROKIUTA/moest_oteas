<?php
use App\Occupation\Tbl_occupation;
use App\Tribe\Tbl_tribe;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\nursing_care\Tbl_department;
use App\nursing_care\Tbl_country;
use App\nursing_care\Tbl_country_zone;
use App\nursing_care\Tbl_marital;
use App\nursing_care\Tbl_proffesional;
use App\Region\Tbl_region;
use App\Council\Tbl_council_type;
use App\Council\Tbl_council;
use App\Residence\Tbl_residence;
use App\admin\Tbl_permission_user;
use App\Patient\Tbl_relationship;
use App\nursing_care\Tbl_permission;
use App\nursing_care\Tbl_glyphicon;
use App\colleges\Tbl_college;
use App\colleges\Tbl_education_level;
use App\colleges\Tbl_ownership;
use App\colleges\Tbl_college_status_registration;
use App\colleges\Tbl_college_type;
use App\applicants\Tbl_year_limit;
use App\schools\Tbl_school_level;
use App\schools\Tbl_school;
use App\schools\Tbl_special_need;
use App\schools\Tbl_class_grade;
use App\schools\Tbl_periods_per_week;
use App\subjects\Tbl_teaching_subject;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()   {
        Model::unguard();

		
		DB::statement('SET FOREIGN_KEY_CHECKS=0');
		
        DB::table('users')->truncate();       
        DB::table('tbl_departments')->truncate();
        DB::table('tbl_proffesionals')->truncate();
        DB::table('tbl_residences')->truncate();
        DB::table('tbl_permission_users')->truncate();
        DB::table('tbl_regions')->truncate();
        DB::table('tbl_councils')->truncate();
        DB::table('tbl_council_types')->truncate();
        DB::table('tbl_permissions')->truncate();
        DB::table('tbl_colleges')->truncate();
        DB::table('tbl_education_levels')->truncate();
        DB::table('tbl_ownerships')->truncate();
        DB::table('tbl_college_status_registrations')->truncate();
        DB::table('tbl_college_types')->truncate();
        DB::table('tbl_year_limits')->truncate();
        DB::table('tbl_school_levels')->truncate();
        DB::table('tbl_teaching_subjects')->truncate();
        DB::table('tbl_periods_per_weeks')->truncate();
        DB::table('tbl_class_grades')->truncate();
		DB::statement('SET FOREIGN_KEY_CHECKS=1'); 
		
							 
	$permissions= array(
	 ['module' => 'system','glyphicons'=>'fa fa-dashboard fa-3x','title'=>'System Settings','main_menu'=>1,'keyGenerated' =>Hash::make('system')],
             
    ['module' => 'schoolSettings','glyphicons' =>'fa fa-plus','title' => 'School Setup','keyGenerated' =>Hash::make('schoolSettings') ,'main_menu' => 1],
	   
	['module' => 'collegeSettings','glyphicons' => 'fa fa-user','title' => 'College Setup','keyGenerated' =>Hash::make('collegeSettings') ,'main_menu' => 1],
	
	['module' => 'permits','glyphicons' => 'fa fa-user','title' => 'Employment Permit','keyGenerated' =>Hash::make('permits'),'main_menu' => 1],
	
	['module' => 'reports','glyphicons' => 'fa fa-user-plus','title' => 'Reports','keyGenerated' =>Hash::make('reports') ,'main_menu' => 1],
	
	['module' => 'addViews','glyphicons' => 'fa fa-file-archive','title' => 'System Views','keyGenerated' =>Hash::make('addViews') ,'main_menu' => 1],
                
				
    ['module' => 'UsersList','glyphicons'=>'fa fa-list fa-3x','title'=>'Users List','main_menu'=>1,'keyGenerated' =>Hash::make('UsersList')],
				 
                 
    ['module' => 'applications','glyphicons'=>'fa fa-home fa-3x','title'=>'Applications','main_menu'=>1,'keyGenerated' =>Hash::make('applications')],
    
	['module' => 'applicants_registration','glyphicons'=>'fa fa-home fa-3x','title'=>'Applicants Management','main_menu'=>1,'keyGenerated' =>Hash::make('applicants_registration')], 
	
	['module' => 'reported_to_councils','glyphicons'=>'fa fa-home fa-3x','title'=>'Fist Appointments','main_menu'=>1,'keyGenerated' =>Hash::make('reported_to_councils')],
				 
                
                     );

           


        $council_types= array(
                ['description' => 'TOWN COUNCIL'],
                ['description' => 'DISTRICT COUNCIL'],
                ['description' => 'MUNICIPAL COUNCIL'],
                ['description' => 'CITY COUNCIL'],                
                     ); 

$teaching_subjects= array(
   ['subject_name' => 'KUSOMA','department_id' =>1,'code' =>'P01'],
   ['subject_name' => 'KUHESABU','department_id' =>1,'code' =>'P02'],
   ['subject_name' => 'KUANDIKA','department_id' =>1,'code' =>'P03'],
   ['subject_name' => 'HISABATI','department_id' =>1,'code' =>'P04'],
   ['subject_name' => 'KISWAHILI(M)','department_id' =>1,'code' =>'P05'],
   ['subject_name' => 'URAIA','department_id' =>1,'code' =>'P06'],
   ['subject_name' => 'HISTORIA','department_id' =>1,'code' =>'P07'],
   ['subject_name' => 'GOGRAPHIA','department_id' =>1,'code' =>'P08'],
   ['subject_name' => 'HAIBA NA MICHEZO','department_id' =>1,'code' =>'P09'],
   ['subject_name' => 'SAYANSI','department_id' =>1,'code' =>'P10'],
   ['subject_name' => 'TEHAMA','department_id' =>1,'code' =>'P11'],
   ['subject_name' => 'ENGLISH','department_id' =>1,'code' =>'P12'],
   ['subject_name' => 'MAARIFA JAMII','department_id' =>1,'code' =>'P13'],
     //SECONDARY SUBJECTS...
   ['subject_name' => 'BASIC MATHEMATICS','department_id' =>2,'code' =>'S01'],
   ['subject_name' => 'ENGLISH LANGUAGE','department_id' =>2,'code' =>'S02'],
   ['subject_name' => 'PHYSICS','department_id' =>2,'code' =>'S03'],
   ['subject_name' => 'CHEMISTRY','department_id' =>2,'code' =>'S04'],
   ['subject_name' => 'BIOLOGY','department_id' =>2,'code' =>'S05'],
   ['subject_name' => 'GEOGRAPHY','department_id' =>2,'code' =>'S06'],
   ['subject_name' => 'HISTORY','department_id' =>2,'code' =>'S07'],
   ['subject_name' => 'CIVICS','department_id' =>2,'code' =>'S08'],
   ['subject_name' => 'KISWAHILI(S)','department_id' =>2,'code' =>'S09'],
                       );
					   
					   
   $class_grades= array(
   ['grade' => 'PRE-PRIMARY','school_level'=>1,'dept_id' =>1],
   ['grade' => 'STD I','school_level'=>2,'dept_id' =>1],
   ['grade' => 'STD II','school_level'=>2,'dept_id' =>1],
   ['grade' => 'STD III','school_level'=>2,'dept_id' =>1],
   ['grade' => 'STD IV','school_level'=>2,'dept_id' =>1],
   ['grade' => 'STD V','school_level'=>2,'dept_id' =>1],
   ['grade' => 'STD VI','school_level'=>2,'dept_id' =>1],
   ['grade' => 'STD VII','school_level'=>2,'dept_id' =>1],
   ['grade' => 'FORM I','school_level'=>4,'dept_id' =>2],
   ['grade' => 'FORM II','school_level'=>4,'dept_id' =>2],
   ['grade' => 'FORM III','school_level'=>4,'dept_id' =>2],
   ['grade' => 'FORM IV','school_level'=>4,'dept_id' =>2],
   ['grade' => 'FORM V','school_level'=>5,'dept_id' =>2],
   ['grade' => 'FORM VI','school_level'=>5,'dept_id' =>2],
                  ); 
			 
  $periods_per_weeks= array(
   //STD 1
   ['number_of_periods' => 30,'class_grade_id' =>1,'subject_id' =>null],
   //STD 2
   ['number_of_periods' => 30,'class_grade_id' =>2,'subject_id' =>null],
     //STD 3
   ['number_of_periods' => 31,'class_grade_id' =>3,'subject_id' =>null],
    //STD 4
   ['number_of_periods' => 31,'class_grade_id' =>4,'subject_id' =>null],  
	//STD 5
   ['number_of_periods' => 40,'class_grade_id' =>5,'subject_id' =>null],   
  //STD 6
   ['number_of_periods' => 40,'class_grade_id' =>6,'subject_id' =>null], 
  //STD 7
   ['number_of_periods' => 40,'class_grade_id' =>7,'subject_id' =>null],    
  
  );  
					 
				 $education_levels= array(
                ['education_level' => 'CERTIFICATE'],
                ['education_level' => 'DIPLOMA'],
                ['education_level' => 'DEGREE'],
                ['education_level' => 'POST GRADUATE'],
                              
                     );
					 
					 $ownerships= array(
                ['ownership' => 'GOVERNMENT'],
                ['ownership' => 'NON-GOVERNMENT'],
                             
                     ); 
					 
  $year_limits= array(
      ['form_four_graduation_year' => 2006,'college_graduation_year' =>2014],
                
                             
                     ); 
		$registration_statuses= array(
                ['registration_status' => 'PRE-PREPARETORY'],
                ['registration_status' => 'CONDITIONAL'],
                ['registration_status' => 'FULL'],
                             
                     );
					 
			$school_levels= array(
                ['school_level' => 'PRE-PRIMARY SCHOOL'],
                ['school_level' => 'PRIMARY SCHOOL'],
                ['school_level' => 'PRE AND PRIMARY SCHOOL'],
                ['school_level' => 'O-LEVEL SECONDARY SCHOOL'],
                ['school_level' => 'A-LEVEL SECONDARY SCHOOL'],
                             
                     );
		    $special_needs= array(
                ['special_need' => 'Visual Impairments'],
                ['special_need' => 'Hearing Impairments'],
                ['special_need' => 'Intelectual Impairments'],
                ['special_need' => 'Physical Impairments'],
                ['special_need' => 'Albinisim'],
                ['special_need' => 'Autisim'],
                ['special_need' => 'Deafblindness'],
                              
                     );
					 
		$college_types= array(
                ['college_status' => 'UNIVERSITY'],
                ['college_status' => 'UNIVERSITY COLLEGE'],
                ['college_status' => 'COLLEGE'],                             
                     );
			
					 
		$residences= array(
                ['residence_name' => 'CDA STREET','council_id' =>1], 
				        ['residence_name' => 'NKUHUNGU','council_id' =>1],
				        ['residence_name' => 'MAKULU','council_id' =>1],
				        ['residence_name' => 'AREA D','council_id' =>1],
				        ['residence_name' => 'AREA C','council_id' =>1],
                
                     );
					 
		$proffesionals= array(
                ['prof_name' => 'EDUCATIONAL OFFICER'],
                ['prof_name' => 'ICTO'],
                ['prof_name' => 'RAS'],
                ['prof_name' => 'MoEST'],
                ['prof_name' => 'DED'],
                ['prof_name' => 'SYSTEM ADMIN'],
                ['prof_name' => 'PO RALG -PRIMARY DEPARTMENT'],
                ['prof_name' => 'PO RALG -SECONDARY DEPARTMENT'],
                ['prof_name' => 'AUDITOR'],
                ['prof_name' => 'APPLICANT'],	
                     );
								 
		
					 
					 
					
					
					 
	   $country_zones= array(
                ['country_zone' =>'EAST AFRICA'],				
                ['country_zone' =>'WEST AFRICA'],
                ['country_zone' =>'NORTH AFRICA'],
                ['country_zone' =>'SOUTH AFRICA'],
                     );
			   
	   $regions = array(
		        ['region_code' => '01','region_name' =>'DODOMA'],
                     ); 
		
					 
		$marital_statuses = array(
                ['marital_status' => 'MARRIED'],			
                ['marital_status' => 'SINGLE'],			
                ['marital_status' => 'DIVORCED'],			
                ['marital_status' => 'CO-HABITING'],			
                ['marital_status' => 'WIDOW'],			
                
                     );
					


		$glyphicons = array(
                ['icon_class' => 'fa fa-calender fa-3x','icon_name' => 'Calender'],				
                ['icon_class' =>'fa fa-check-square-o fa-3x','icon_name' =>'Checking'],				
                ['icon_class' =>'fa fa-credit-card fa-3x','icon_name' =>'Credit Card'],	
                ['icon_class' =>'fa fa-ambulance fa-3x','icon_name' =>'Checking'],
                ['icon_class' =>'fa fa-film fa-3x','icon_name' =>'Checking'],			
                ['icon_class' =>'fa fa-dashboard fa-3x','icon_name' =>'Dashboard'],				
                ['icon_class' =>'fa fa-pie-chart fa-3x','icon_name' =>'Pie Chart Report'],				
                ['icon_class' =>'fa fa-power-off fa-3x','icon_name' =>'Power Off'],				
                ['icon_class' =>'fa fa-desktop fa-3x','icon_name' =>'Registered'],				
                ['icon_class' =>'fa fa-toggle-on fa-3x','icon_name' =>'Toggle On'],				
                ['icon_class' =>'fa fa-wheelchair fa-3x','icon_name' =>'Wheel Chair'],				
                ['icon_class' =>'fa fa-toggle-off fa-3x','icon_name' =>'Toggle Off'],				
                ['icon_class' =>'fa fa-upload fa-3x','icon_name' =>'Upload Icon'],				
                ['icon_class' =>'fa fa-trash fa-2x','icon_name' =>'Delete Icon'],				
                ['icon_class' =>'fa fa-wrench fa-3x','icon_name' =>'Setting Icon'],				
                ['icon_class' =>'fa fa-server fa-3x','icon_name' =>'Listing Icon'],				
             				
                
                     );
					 
		$departments = array(
                 ['id'=>1,'department_name' => 'PRIMARY DEPARTMENT'],
				 ['id'=>2,'department_name' => 'SECONDARY DEPARTMENT'],
                
                     );
	


		$tribes= array(
			['tribe_name' => 'SUKUMA'],
			['tribe_name' => 'YAO'],
			['tribe_name' => 'NGONI'],
			['tribe_name' => 'ZARAMO'],
			['tribe_name' => 'NYAMWEZI'],
			['tribe_name' => 'HEHE'],
			['tribe_name' => 'CHAGA'],
			['tribe_name' => 'BENA'],
			['tribe_name' => 'MUHA'],
			['tribe_name' => 'ZIGUA'],
			['tribe_name' => 'KWERE'],
			['tribe_name' => 'MZANZIBARI'],
			['tribe_name' => 'NDENDEURE'],
			['tribe_name' => 'ZANAKI'],
			['tribe_name' => 'MASAI'],
			['tribe_name' => 'PARE'],
			['tribe_name' => 'MUIRAQ'],
			['tribe_name' => 'NYERAMBA'],
			['tribe_name' => 'BONDEI'],
			['tribe_name' => 'ZIGUA'],
			['tribe_name' => 'LUGURU'],
			['tribe_name' => 'MAKONDE'],
			['tribe_name' => 'NDENGEREKO'],
			['tribe_name' => 'MATUMBI'],
			['tribe_name' => 'POGORO'],
			['tribe_name' => 'HAYA'],


		);

    foreach ($departments as $department)
        {
            Tbl_department::create($department);
        }  
		
	foreach ($teaching_subjects as $teaching_subject)
        {
       Tbl_teaching_subject::create($teaching_subject);
        }
	foreach ($school_levels as $school_level)
        {
            Tbl_school_level::create($school_level);
        }

     foreach ($class_grades as $class_grade)
        {
       Tbl_class_grade::create($class_grade);
        }  
		
	foreach ($periods_per_weeks as $periods_per_week)
        {
       Tbl_periods_per_week::create($periods_per_week);
        } 
		
				
		
		


		foreach ($proffesionals as $proffesional)
        {
            Tbl_proffesional::create($proffesional);
        }
		
		foreach ($education_levels as $education_level)
        {
            Tbl_education_level::create($education_level);
        }
		foreach ($ownerships as $ownership)
        {
            Tbl_ownership::create($ownership);
        }
		foreach ($registration_statuses as $registration_status)
        {
            Tbl_college_status_registration::create($registration_status);
        }
		foreach ($college_types as $college_type)
        {
            Tbl_college_type::create($college_type);
        }
		foreach ($year_limits as $year_limit)
        {
            Tbl_year_limit::create($year_limit);
        }
		
		foreach ($special_needs as $special_need)
        {
            Tbl_special_need::create($special_need);
        }
		
				
				
		foreach ($permissions as $permission)
        {
            Tbl_permission::create($permission);
        }
		
	
			
		
		foreach ($glyphicons as $glyphicon)
        {
            Tbl_glyphicon::create($glyphicon);
        }
		
		

       		
		
		$region = __DIR__ . '\REGIONS.csv';
		if (!file_exists($region) || !is_readable($region))
			return false;
		
		$header = null;
		$data = array();
		if (($handle = fopen($region, 'r')) !== false)
		{
			while (($row = fgetcsv($handle, 1000, ',')) !== false)
			{
				if (!$header)
				    $header = $row;
				else
					$data[] = array_combine($header, $row);
			}
			fclose($handle);
			
			foreach($data as $region)
			{
				
				Tbl_region::create($region);
			}
		}
		
		
		foreach ($council_types as $council_type)
        {
            Tbl_council_type::create($council_type);
        }
		
				
		
		$council = __DIR__ . '\COUNCILS.csv';
		if (!file_exists($council) || !is_readable($council))
			return false;
		
		$header = null;
		$data = array();
		if (($handle = fopen($council, 'r')) !== false)
		{
			while (($row = fgetcsv($handle, 1000, ',')) !== false)
			{
				if (!$header)
				    $header = $row;
				else
					$data[] = array_combine($header, $row);
			}
			fclose($handle);
			
			foreach($data as $council)
			{
				
				Tbl_council::create($council);
			}
		}

		
		$residence = __DIR__ . '\RESIDENCES.csv';
		if (!file_exists($residence) || !is_readable($residence))
			return false;
		
		$header = null;
		$data = array();
		if (($handle = fopen($residence, 'r')) !== false)
		{
			while (($row = fgetcsv($handle, 1000, ',')) !== false)
			{
				if (!$header)
				    $header = $row;
				else
					$data[] = array_combine($header, $row);
			}
			fclose($handle);
			
			foreach($data as $residence)
			{
				Tbl_residence::create($residence);
			}
		}

		
		$colleges = __DIR__ . '\COLLEGE_REGISTER_FORM_TEMPLATE.csv';
		if (!file_exists($colleges) || !is_readable($colleges)){
		echo'FAILI LENYE ORODHA YA VYUO HALIWEZI SOMEKA ,TAFDHALI HAKIKISHA FAILI HILO KAMA NI EXCEL';		
		return false;
		}
		$header = null;
		$data = array();
		if (($handle = fopen($colleges, 'r')) !== false)
		{
			
			while (($row = fgetcsv($handle, 1000, ',')) !== false)
			{
				if (!$header)
				    $header = $row;
				else
					$data[] = array_combine($header, $row);
			}
			fclose($handle);
			
			foreach($data as $college)
			{
				Tbl_college::create($college);
			}
		}
		
		
		  $users = array(
                ['name' => 'OTEAS SUPERADMIN',
				'email' => 'admin@oteas',
				'password' => Hash::make('12345678'),
				'mobile_number' => '0652576368',
				'gender' => 'MALE',
				'user_type' =>6
				],

        
            


        );
		
		
		foreach ($users as $user)
        {
            User::create($user);
        }
		
		$user_admin=User::where('email','admin@oteas')->get();
		
		$permission_users = array(
		          ['permission_id' =>1,'user_id' =>$user_admin[0]->id,'grant' =>1],
		             );
			
		foreach ($permission_users as $permission_user)
        {
            Tbl_permission_user::create($permission_user);
        }
		
			
		echo'HONGERA,MFUMO WA OTEAS UPO TAYARI KUANZA KUTUMIKA';
		
        Model::reguard();
    }
}
