<?php

namespace App\Http\Controllers\placement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\colleges\Tbl_college;
use App\applicants\Tbl_year_limit;
use App\schools\Tbl_school;
use App\schools\Tbl_school_level;
use App\schools\Tbl_special_need;
use App\region\Tbl_region;
use App\Council\Tbl_council;
use App\subjects\Tbl_teaching_subject;
use App\permits\Tbl_permit;
use App\admin\Tbl_permission_user;
use App\applicants\Tbl_applicant;
use App\applicants\Tbl_application;
use App\applicants\Tbl_attendance_report;
use App\User;
use DB;
class placementController extends Controller
{
	
	
	public function getApplicationLists(Request $request){
		$department_id=$request->department_id;
		//$department_id=1;
		$sql="SELECT
    t4.*,t1.applicant_id,
    MAX(CASE WHEN t1.priority = 1 THEN t2.council_name ELSE NULL END) AS first_council,
    MAX(CASE WHEN t1.priority = 2 THEN t2.council_name ELSE NULL END) AS second_council,
    MAX(CASE WHEN t1.priority = 3 THEN t2.council_name ELSE NULL END) AS third_council,
    MAX(CASE WHEN t1.priority = 4 THEN t2.council_name ELSE NULL END) AS fourth_council,

    MAX(CASE WHEN t1.priority = 1 THEN (SELECT school_name FROM tbl_schools t5 WHERE t5.centre_number=t1.school_id AND t1.priority=1 GROUP BY  t1.applicant_id LIMIT 1) ELSE NULL END) AS first_school,

    MAX(CASE WHEN t1.priority = 2 THEN (SELECT school_name FROM tbl_schools t5 WHERE t5.centre_number=t1.school_id AND t1.priority=2 GROUP BY  t1.applicant_id LIMIT 1) ELSE NULL END) AS second_school,
    MAX(CASE WHEN t1.priority = 3 THEN (SELECT school_name FROM tbl_schools t5 WHERE t5.centre_number=t1.school_id AND t1.priority=3 GROUP BY  t1.applicant_id LIMIT 1) ELSE NULL END) AS third_school,
    MAX(CASE WHEN t1.priority = 4 THEN (SELECT school_name FROM tbl_schools t5 WHERE t5.centre_number=t1.school_id AND t1.priority=4 GROUP BY  t1.applicant_id LIMIT 1) ELSE NULL END) AS fourth_school
  
   
FROM
     tbl_applications t1
	 INNER JOIN tbl_councils t2 ON t1.council_id=t2.id
	 INNER JOIN tbl_regions t3 ON t3.id=t2.regions_id
	 INNER JOIN tbl_applicants t4 ON t4.applicant_id=t1.applicant_id
	 WHERE t4.department_id='".$department_id."'
GROUP BY
    t1.applicant_id
ORDER BY t1.created_at DESC 
     LIMIT 40";
	return DB::SELECT($sql);
		
	}
    // Get list of colleges...
	public function getColleges(Request $request){
		 $year_limit=Tbl_year_limit::all();
		 $responses=[];
		 $collegeYears=[];
		 $schoolYears=[];
		 $responses[]=Tbl_college::all();
for($starting_year_college=$year_limit[0]->college_graduation_year;$starting_year_college < date('Y')+1;$starting_year_college++ ){
			$collegeYears[]=$starting_year_college;
		}
		
		for($starting_year_school=$year_limit[0]->form_four_graduation_year;$starting_year_school < date('Y')-2;$starting_year_school++ ){
			$schoolYears[]=$starting_year_school;
		}
		//return $collegeYears;
		$responses[]=$collegeYears;  //year ending olevel		
		$responses[]=$schoolYears;  //year ending college
		
		 return $responses; 
		
		
	}
	
	public function verifyApplicantInfo(Request $request){
		$responses=[];
		//retrieve applicant details..
		$college_id = $request->college_id;
		$admn_no = $request->admn_no;
		$form_grad_year = $request->form_grad_year;
		$grad_year = $request->grad_year;
		$index_number = $request->index_number;
		$unique_index=$index_number."/".$form_grad_year;
	
     	$sql="SELECT t1.*,t2.college_name,t2.email,
     	      TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) AS age 
     	      FROM tbl_applicants t1 
     	      INNER JOIN tbl_colleges t2 ON  t2.college=t1.college
		      WHERE t1.registration_number='".$admn_no."'
		      AND   t1.year_graduated='".$grad_year."'
		      AND   t1.applicant_id='".$unique_index."'";		
		$responses[]= DB::SELECT($sql);
		
		if(count($responses[0]) > 0){
		 $responses[]=1;	
		}else{
		$responses[]=0;	
		}
			return $responses;
		
	}
	
	public function register(Request $request)
  {

    $name=$request['name'];
    $email=$request['email'];
    $user_type=$request['user_type'];
    $mobile_number=$request['mobile_number'];
    $gender=$request['gender'];
    $applicant_id=$request['applicant_id'];
    $password=bcrypt($request['password']);
    $user=User::create([
      'name'=>$name,
      'email'=>$email,
      'user_type'=>$user_type,
      'mobile_number'=>$mobile_number,
      'gender'=>$gender,
      'applicant_id'=>$applicant_id,
      'password'=>$password,

    ]);
	$user_id=$user->id;
	Tbl_permission_user::create(
	['permission_id'=>8,'user_id'=>$user_id,'grant'=>1]
	);
	
  }
  
  public function makeApplications(Request $request){
    $priority=1;
	if(isset($request->edit)){		
    foreach($request->selections AS $selection){
    Tbl_application::where('id',$selection['application_id'])->update([
      'council_id'=>$selection['council_id']
    ]);
	
	}
		
	}
	else{	
		//check duplcates..
    foreach($request->selections AS $selection){
    Tbl_application::create([
      'council_id'=>$selection['council_id'],
      'school_id'=>$selection['centre_number'],
      'applicant_id'=>$request['applicant_id'],
      'priority'=>$priority     
    ]);
	$priority++;
	}
	}
	
  }
  
  //placement algorithm for primary schools to council
  public function makePlacementPrimary(Request $request){
  	  if(isset($request->gender)){
  	  	if($request->gender=="M"){
  	  		$gender_permit="male"; 	  		
  	  	}
  	  	if($request->gender=="F"){
  	  		$gender_permit="female";
  	  	}
  	  	$gender=$request->gender;
        
  	  }else{
  	  	return "Please Specify Gender For the placement to proceed";
  	  }
      $female_permit=0;
      $male_permit  =0;     
   if(isset($request->selections)){		
    foreach($request->selections AS $selection){
    	try{
		//get list of councils for selected regions, #1st step
    $council_lists=DB::SELECT("SELECT * FROM vw_applications t1 WHERE t1.region_id='".$selection['region_id']."'
    	  GROUP BY t1.council_id");
    
	// return $council_lists;
	   //check for employ permits for each council   #2nd step
	foreach($council_lists AS $council_list){
		//return $council_list;
		 $sql="SELECT * FROM vw_permits t1 
	      WHERE t1.council_id='".$council_list->council_id."'";
      $permits=DB::SELECT($sql);
	 // return $permits;
	  if(isset($permits[0])){
	   $gender_permit_category=$permits[0]->$gender_permit; //No. of fe/males to employ/KIBALI
	  
	   $council_id=$permits[0]->council_id;   //council_id
	
	  if($gender_permit_category >0){
	  $this->executePlacement($council_id,$gender_permit_category,$gender);
	  }
	  }
	 }

}catch (\Exception $e) {       
          return $e;
          }
	
	
	}
		
	}
	return "Placement was successfully completed";
		
  }
  
  public function makePlacementPrimarySchool(Request $request){
      $female_permit=0;
      $male_permit  =0;
     
   if(isset($request->selections)){		
    foreach($request->selections AS $selection){
  //get list of councils for selected regions assigned to applicant, #1st step
    	
	$sql="SELECT t1.* FROM tbl_applications t1 
	     INNER JOIN tbl_councils t2 ON t1.council_id=t2.id 
	     INNER JOIN tbl_applicants t3 ON t1.applicant_id=t3.id 
	     WHERE t3.gender='F' 
		 AND t2.regions_id='".$selection['region_id']."'
	     AND t1.selected=1";
						  
	$council_lists=DB::SELECT($sql);
	
	$sql="SELECT t1.* FROM tbl_applications t1 
	     INNER JOIN tbl_councils t2 ON t1.council_id=t2.id 
	     INNER JOIN tbl_applicants t3 ON t1.applicant_id=t3.id 
	     WHERE t3.gender='M' 
		 AND t2.regions_id='".$selection['region_id']."'
	     AND t1.selected=1";
						  
	$council_lists_males=DB::SELECT($sql);
	
	
	if(count($council_lists)>0){
	
	//return $council_lists;
	   //check for employ permits for each council   #2nd step
	   //for female
	foreach($council_lists AS $council_list){
		//return $council_list;
	  $sql="SELECT * FROM vw_permits t1 
	      WHERE t1.council_id='".$council_list->council_id."'";
	  $permits=DB::SELECT($sql);
	  
	  $applicant_id=$council_list->applicant_id;
	
    	// return $permits;
	  if(isset($permits[0])){
	   $female_permit=$permits[0]->female; 
	   $male_permit=$permits[0]->male;   
	   $council_id=$permits[0]->council_id; 
	 //start place female...
	  if($female_permit >0){
	 // $this->executePlacement($council_id,$female_permit);
	  $sql="SELECT SUM(number_of_periods) AS number_of_periods FROM  tbl_periods_per_weeks t1
	  INNER JOIN tbl_teaching_subjects  t2 ON t1.subject_id=t2.id
	  WHERE t2.department_id=1 GROUP BY t2.department_id"; 
	  $number_of_periods=DB::SELECT($sql);
	  
	  $total_periods=$number_of_periods[0]->number_of_periods;
	  
	  
	  
	  $requirements_per_council="SELECT t1.school_id,t2.council_id,
	  CEIL(SUM((((t1.students_taking/45) * $total_periods )/25)-t4.teachers_number)) AS requireired_teachers FROM  tbl_teachers_requirements t1
	  INNER JOIN tbl_schools  t2 ON t1.school_id=t2.id
	  INNER JOIN tbl_councils t3 ON t3.id=t2.council_id
	  INNER JOIN tbl_subjects_teachers t4 ON t4.school_id=t1.school_id
	  WHERE t2.department_id=1 AND t2.council_id='".$council_id."' GROUP BY t2.council_id ORDER BY requireired_teachers DESC";
	  
	  $council_requirements=DB::SELECT($requirements_per_council);
	  
	  $requirements_in_schools="SELECT t1.school_id,t2.council_id,
	  CEIL((((students_taking/45) * $total_periods )/25)-teachers_number) AS requireired_teachers FROM  tbl_teachers_requirements t1
	  INNER JOIN tbl_schools  t2 ON t1.school_id=t2.id
	  INNER JOIN tbl_councils t3 ON t3.id=t2.council_id
	  INNER JOIN tbl_subjects_teachers t4 ON t4.school_id=t1.school_id
	  WHERE t2.department_id=1 AND t2.council_id='".$council_id."' GROUP BY t1.school_id ORDER BY requireired_teachers DESC";
	  
	  $requirements_per_schools=DB::SELECT($requirements_in_schools);
	  //return $council_requirements[0]->requireired_teachers;
	  if($council_requirements[0]->requireired_teachers > 0){
     $total_council_reqrments=$council_requirements[0]->requireired_teachers;
	  foreach($requirements_per_schools AS $obtained_requirement){
		 if($obtained_requirement->requireired_teachers >0){
		$distribution_ratio=($obtained_requirement->requireired_teachers/$total_council_reqrments)*$female_permit;
		//return $female_permit;
		//return $distribution_ratio;
		$starter=0;
		while($distribution_ratio > $starter){
		  $school_id=$obtained_requirement->school_id;
		   Tbl_application::where('applicant_id',$applicant_id)
		                 ->where('selected',1)
						 ->update(['school_id' =>$school_id]);
						 if($starter==$distribution_ratio){
							$starter=0;
						 }
						 $starter++;
						 
		}
		}
	  }		 
		  
	  }
	 }
	  }
	     
	  }
	}
	//MAKE PLACEMENT FOR MALE ONLY
	if(count($council_lists_males)>0){
	
	//return $council_lists;
	   //check for employ permits for each council   #2nd step
	   //for female
	foreach($council_lists_males AS $council_list){
		//return $council_list;
	  $sql="SELECT * FROM vw_permits t1 
	      WHERE t1.council_id='".$council_list->council_id."'";
	  $permits=DB::SELECT($sql);
	  
	  $applicant_id=$council_list->applicant_id;
	
    	// return $permits;
	  if(isset($permits[0])){
	   $male_permit=$permits[0]->male;   
	   $council_id=$permits[0]->council_id; 
	 //start place female...
	  if($male_permit >0){
	 // $this->executePlacement($council_id,$female_permit);
	  $sql="SELECT SUM(number_of_periods) AS number_of_periods FROM  tbl_periods_per_weeks t1
	  INNER JOIN tbl_teaching_subjects  t2 ON t1.subject_id=t2.id
	  WHERE t2.department_id=1 GROUP BY t2.department_id"; 
	  $number_of_periods=DB::SELECT($sql);
	  	  	  	  
	  $total_periods=$number_of_periods[0]->number_of_periods;
	  $requirements_per_council="SELECT t1.school_id,t2.council_id,
	  CEIL(SUM((((t1.students_taking/45) * $total_periods )/25)-t4.teachers_number)) AS requireired_teachers FROM  tbl_teachers_requirements t1
	  INNER JOIN tbl_schools  t2 ON t1.school_id=t2.id
	  INNER JOIN tbl_councils t3 ON t3.id=t2.council_id
	  INNER JOIN tbl_subjects_teachers t4 ON t4.school_id=t1.school_id
	  WHERE t2.department_id=1 AND t2.council_id='".$council_id."' GROUP BY t2.council_id ORDER BY requireired_teachers DESC";
	  
	  $council_requirements=DB::SELECT($requirements_per_council);
	  
	  $requirements_in_schools="SELECT t1.school_id,t2.council_id,
	  CEIL((((students_taking/45) * $total_periods )/25)-teachers_number) AS requireired_teachers FROM  tbl_teachers_requirements t1
	  INNER JOIN tbl_schools  t2 ON t1.school_id=t2.id
	  INNER JOIN tbl_councils t3 ON t3.id=t2.council_id
	  INNER JOIN tbl_subjects_teachers t4 ON t4.school_id=t1.school_id
	  WHERE t2.department_id=1 AND t2.council_id='".$council_id."' GROUP BY t1.school_id ORDER BY requireired_teachers DESC";
	  
	  $requirements_per_schools=DB::SELECT($requirements_in_schools);
	  //return $council_requirements[0]->requireired_teachers;
	  if($council_requirements[0]->requireired_teachers > 0){
     $total_council_reqrments=$council_requirements[0]->requireired_teachers;
	  foreach($requirements_per_schools AS $obtained_requirement){
		 if($obtained_requirement->requireired_teachers >0){
		$distribution_ratio=($obtained_requirement->requireired_teachers/$total_council_reqrments)*$male_permit;
		//return $female_permit;
		//return $distribution_ratio;
		$starter=0;
		while($distribution_ratio > $starter){
		  $school_id=$obtained_requirement->school_id;
		   Tbl_application::where('applicant_id',$applicant_id)
		                 ->where('selected',1)
						 ->update(['school_id' =>$school_id]);
						 if($starter==$distribution_ratio){
							$starter=0;
						 }
						 $starter++;
						 
		}
		  
	     
		 }
	  }		 
		  
	  }

	  }
	  }
	
	}
	}
	
	
	}
		
	}
		
  }
  
  //execute placement..
  public function executePlacement($council_id,$permit,$gender){

  	$sql="SELECT t1.*,t2.permits,t3.applicant_id FROM tbl_school_requirements t1 
  	          INNER JOIN tbl_permits t2 ON t1.council_id=t2.council_id
  	          INNER JOIN tbl_applications t3 ON t3.school_id=t1.school_id
  	          WHERE t2.gender='".$gender."'
  	                AND t1.council_id='".$council_id."'
  	                AND dept_id=1
  	                AND t3.lock_applicant=0  	          
  	                ORDER BY t1.ptr DESC LIMIT $permit";	 			
	  $applicants=DB::SELECT($sql);	 
	  
	  foreach($applicants AS $applicant){
		  $applicant_id=$applicant->applicant_id;
		  $school_id=$applicant->school_id;
		  $sql="SELECT required_teachers FROM tbl_school_requirements t1 WHERE t1.school_id='".$school_id."'";
		  $school=DB::SELECT($sql);
		  $actual_req= 0.5 * $school[0]->required_teachers;
		  $i=0;
		  while($i < $actual_req){		
		  $sql=DB::statement("UPDATE tbl_applications t1
		        SET t1.lock_applicant=1,t1.selected=1
		        WHERE t1.applicant_id='".$applicant_id."'
		              AND t1.school_id   ='".$school_id."'
                      AND t1.lock_applicant=0
		              ");
		              $i++;
		          }
			  
	  }  
	  return "Placement was successfully completed";
  }
  
  public function getApplications(Request $request){
   
	$sql="SELECT t2.*,t3.regions_id	,t1.updated_at,t3.council_name,
	      t4.region_name,t1.id AS application_id ,t1.council_id 
	      FROM  tbl_applications t1 
	      INNER JOIN tbl_applicants t2 ON t1.applicant_id=t2.applicant_id
	      INNER JOIN tbl_councils t3 ON t3.id=t1.council_id	
	      INNER JOIN tbl_regions t4 ON t4.id=t3.regions_id

           WHERE t1.applicant_id='".$request->applicant_id."'";
		   
		   return DB::SELECT($sql);
	
	
  } 
  
  
  
  public function getListSelectedToCouncils(Request $request){
   
	$sql="SELECT t2.*,t5.college_name,t3.regions_id	,t1.updated_at,t3.council_name,
	      t4.region_name,t1.id AS application_id ,t1.council_id 
	      FROM  tbl_applications t1 
	      INNER JOIN tbl_applicants t2 ON t1.applicant_id=t2.applicant_id
	      INNER JOIN tbl_councils t3 ON t3.id=t1.council_id	
	      INNER JOIN tbl_regions t4 ON t4.id=t3.regions_id
	      INNER JOIN tbl_colleges t5 ON t5.id=t2.college

           WHERE t1.selected=1 GROUP BY t1.applicant_id";
		   
		   return DB::SELECT($sql);
	
	
  }
  public function getListSelectedToSchools(Request $request){
   
	$sql="SELECT t6.school_name,t2.*,t5.college_name,t3.regions_id	,t1.updated_at,t3.council_name,
	      t4.region_name,t1.id AS application_id ,t1.council_id 
	      FROM  tbl_applications t1 
	      INNER JOIN tbl_applicants t2 ON t1.applicant_id=t2.applicant_id
	      INNER JOIN tbl_schools t6 ON t6.centre_number=t1.school_id
	      INNER JOIN tbl_councils t3 ON t3.id=t1.council_id	
	      INNER JOIN tbl_regions t4 ON t4.id=t3.regions_id
	      INNER JOIN tbl_colleges t5 ON t5.college=t2.college

           WHERE t1.selected=1 GROUP BY t1.applicant_id";
		   
		   return DB::SELECT($sql);
	
	
  }
  
  public function getListSelectedToThisCouncil(Request $request){
   
	$sql="SELECT t6.school_name,t2.*,t5.college_name,t3.regions_id	,t1.updated_at,t3.council_name,
	      t4.region_name,t1.id AS application_id ,
		  (SELECT applicant_image FROM tbl_attendance_reports t6 WHERE t6.applicant_id=t1.applicant_id LIMIT 1) AS applicant_image,
		  
		  (SELECT created_at FROM tbl_attendance_reports t6 WHERE t6.applicant_id=t1.applicant_id LIMIT 1) AS time_reported,
		  t1.council_id 
	      FROM  tbl_applications t1 
	      INNER JOIN tbl_applicants t2 ON t1.applicant_id=t2.applicant_id
	      INNER JOIN tbl_schools t6 ON t6.id=t1.school_id
	      INNER JOIN tbl_councils t3 ON t3.id=t1.council_id	
	      INNER JOIN tbl_regions t4 ON t4.id=t3.regions_id
	      INNER JOIN tbl_colleges t5 ON t5.id=t2.college

           WHERE t1.selected=1 AND t1.council_id='".$request->council_id."' GROUP BY t1.applicant_id";
		   
		   return DB::SELECT($sql);
	
	
  }
  
  public function saveApplicantPhoto(Request $request){
    if(Tbl_attendance_report::where('applicant_id',$request->applicant_id)->get()->count() ==0){	  
	  return Tbl_attendance_report::create($request->all());
	}   
	else{
		
		Tbl_attendance_report::where('applicant_id',$request->applicant_id)->update(['applicant_image' => $request->applicant_image]);
	}
		   	
  }

  public function searchResidence(Request $request){
  	  $residenceKey=$request->name;
  	  $sql="SELECT t1.*,t2.council_name FROM tbl_residences t1
  	        INNER JOIN tbl_councils t2 ON t1.council_id=t2.id
  	        WHERE t1.residence_name LIKE '%".$residenceKey."%' LIMIT 5";
  	        return DB::SELECT($sql);
  }
	
	public function saveAddress(Request $request){
		
		$applicant_id = $request->applicant_id;
		$residence_id = $request->residence_id;
		if(!isset($request->mobile_number)){
		return response()->json(
                  ['data' =>'Mobile number must be provided',
                   'status' => 0
               ]
           );
	   }
		$mobile_number = $request->mobile_number;
		$email = $request->email;
		     			
		return Tbl_applicant::where('applicant_id',$applicant_id)->update([
		'residence_id'=>$residence_id,
		'email'=>$email,
		'mobile_number'=>$mobile_number	
		]);
		
		
		
	}
	public function getPermits(Request $request){	
			
		$searchWord=$request->searchWord;
   		
		$query="SELECT * FROM `vw_permits` WHERE `".$request->field_name."` LIKE '%".$searchWord."%'";
		
		return DB::SELECT($query);
		
	}
	
	
	public function getSetupData(Request $request){
		$responses=[];
		$responses[]=Tbl_school_level::all();
		$responses[]=Tbl_special_need::all();
		return $responses;
		
	}
	public function getRegions(Request $request){
		if(isset($request->searchKey)){
	$sql="SELECT * FROM tbl_regions t1 WHERE t1.region_name LIKE '%".$request->searchKey."%' LIMIT 6";
      return DB::SELECT($sql);
		}else{		
			return Tbl_region::all();
		}
		
	}
	
	
	public function getSubjects(Request $request){
			return Tbl_teaching_subject::all();
		
	}
	public function getCouncils(Request $request){
		if(isset($request->council_id)){
	return Tbl_council::where('id',$request->council_id)->groupBy('council_name')->get();
		}
		else if(isset($request->region_id)){	
		return Tbl_council::where('regions_id',$request->region_id)->groupBy('council_name')->get();
		}
		
	}
	
	public function saveSchool(Request $request){
    if(Tbl_school::where('centre_number',$request->centre_number)->get()->count() > 0){
	return response()->json(
                  ['data' =>$request->centre_number.',already exists',
                   'status' => 0
               ]
           );	
		
	}
		
		Tbl_school::create($request->all());
		
		return response()->json(
            ['data' => $request->centre_number.', WAS SUCCESFULLY REGISTERED.',
             'status' => 1
               ]
           );
		
	}
	
	public function savePermit(Request $request){
 		
		Tbl_permit::create($request->all());
		
		return response()->json(
            ['data' =>'PERMIT WAS SUCCESFULLY REGISTERED.',
             'status' => 1
               ]
           );		
	  }
	
	public function getSchool(Request $request){
		$sql="CREATE OR REPLACE VIEW vw_schools AS (SELECT t1.*,t3.regions_id,t6.region_name,t3.council_name,t2.department_name,(SELECT special_need FROM tbl_special_needs t5 WHERE t1.special_needs_type=t5.id LIMIT 1) AS special_need FROM tbl_schools t1
      INNER JOIN tbl_departments t2 ON t1.department_id=t2.id
      INNER JOIN tbl_councils t3 ON t3.id=t1.council_id
      INNER JOIN tbl_regions t6 ON t3.regions_id=t6.id
      INNER JOIN tbl_school_levels t4 ON t4.id=t1.school_level)";
	   DB::statement($sql);
	   $searchWord=$request->searchWord;
   		
		$query="SELECT * FROM `vw_schools` WHERE `".$request->field_name."` LIKE '%".$searchWord."%'";

        return DB::SELECT($query);
		
	}



	
	public function getAvailableChances(Request $request){
		
		$sql="CREATE OR REPLACE VIEW  vw_available_chances AS(SELECT t1.*,t3.gender,t5.region_name,t4.council_name,(t2.permits - (SELECT count(*) FROM tbl_applications t7 WHERE t7.applicant_id=t3.id AND t7.subject_id=t2.subject_id  GROUP BY t7.subject_id,t3.gender LIMIT 1)) AS available_chances FROM tbl_applications t1 
		      INNER JOIN tbl_permits t2 ON t1.council_id=t2.council_id
		      INNER JOIN tbl_applicants t3 ON t1.applicant_id=t3.id
		      INNER JOIN tbl_councils t4 ON t4.id=t1.council_id
		      INNER JOIN tbl_regions t5 ON t4.regions_id=t5.id
			  
			  WHERE t1.subject_id=t2.subject_id
			  AND   t3.gender = t2.gender)";
			  
		DB::statement($sql);
		 
		$responses=[];
$majours="SELECT * FROM tbl_majoring_subjects t1
          INNER JOIN tbl_permits t2 ON t2.subject_id=t1.subject_id			 
		  WHERE t1.applicant_id='".$request->applicant_id."'";
		
		$majourSubjects=DB::SELECT($majours);
		
		foreach($majourSubjects AS $majour){
			
		$sql_1="SELECT * FROM vw_available_chances t1 WHERE t1.gender='".$request->gender."' AND t1.subject_id='".$majour->subject_id."'"; 
		$responses[]=DB::SELECT($sql_1);
			
		}
		
		if(isset($responses[0]) AND isset($responses[1])){
			//return $responses[0];
			$available_chance_1=count($responses[0]); 
			$available_chance_2=count($responses[1]);
            if($available_chance_1 >= $available_chance_2){               
		        return $responses[0]; //get regions based on first subject	
	        }	
        else if($available_chance_2 >= $available_chance_1){               
		        return $responses[1]; //get regions based on first subject	
	        }			
			
		}
		//only first subject considered..
		else if(isset($responses[0]) AND !isset($responses[1])){
			//return 0;
			     return $responses[0]; //get regions based on first subject	
	       		
		}
		//only seccond subject considered..
		else if(!isset($responses[0]) AND isset($responses[1])){
			return $responses[1]; //get regions based on second subject	
	       		
		}
		
		
		
		
	}
}
