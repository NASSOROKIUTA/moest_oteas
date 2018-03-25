<?php
namespace App\Http\Controllers\excel;
ob_start();
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\applicants\Tbl_applicant;
use App\schools\Tbl_teachers_requirement;
use App\permits\Tbl_permit;
use Excel;
use DB;
class excelController extends Controller
{
   /**
     * Return View file
     *
     * @var array
     */
	public function importExport()
	{
	}

	/**
     * File Export Code
     *
     * @var array
     */
	public function downloadExcel(Request $request)
	{
		
		$data = Tbl_applicant::get()->toArray();
		$path=basename(__DIR__)."/../../../public/downloads/";
 return Excel::create('applicants', function($excel) use ($data) {
 $excel->sheet('applicants', function($sheet) use ($data){
			$sheet->fromArray($data);
	        });
		})->store('xls', storage_path('../public/excel'))->export('xls');
 
 // ->store('xls')->export('xls');
		
				
	}
	
	
public function loadPermits(Request $request){
         $file =0; 
		  while (Input::hasFile($file)) {
			
			$path = $request->file($file)->getRealPath();		

			$data = Excel::load($path, function($reader) {})->get();
			$records=$data->count();
			
			
			for($p=1; $p<= $data->count(); $p++){
			 Excel::load($path, function($reader) {
             $results = $reader->get();
			// print_r($results); return;
                  
              if(!isset($results[0]['council_id'])){
               return response()->json(
                  ['data' =>'Please Check your file, Something is wrong there.',
                   'status' => 400
               ]
           );
              }


			 $r=0;
			while($r < count($results)){
			 $council_id=$results[$r]['council_id'];
             $female_permit=$results[$r]['female_permit'];
             $male_permit=$results[$r]['male_permit'];
             $total_permit=$results[$r]['total_permit'];
             $council_name=$results[$r]['council_name'];
             $region_name=$results[$r]['region_name']; 

              if( $male_permit>=0){
              	  $duplicates=Tbl_permit::where('gender','M')
	                 ->where('council_id', $council_id)
                     ->where('dept_id', 1)
	                 ->get()->count();
       if($duplicates == 0){
       	try{
        Tbl_permit::create(['council_id'=>$council_id,
	                        'permits'=>$male_permit,
							'gender'=>'M',
							'dept_id' =>1
							]);
        }catch (\Exception $e) {
        return response()->json(
                  ['data' =>'Some data failed to be enrolled please inform admin for support',
                   'status' => 400
               ]
           );       
              }
        
        

               }  
           }

                if( $female_permit>=0){
                	  $duplicates=Tbl_permit::where('gender','F')
	                 ->where('council_id', $council_id)
                     ->where('dept_id', 1)
	                 ->get()->count();
    if($duplicates == 0){
    	try{
        Tbl_permit::create(['council_id'=>$council_id,
	                        'permits'=>$female_permit,
							'gender'=>'F',
							'dept_id' =>1
							]);

        }catch (\Exception $e) {
        	return response()->json(
                  ['data' =>'Some data failed to be enrolled please inform admin for support',
                   'status' => 400
               ]
           );
                 
              }
        
    }

               }  


			 
	        
			$r++;
			}
			  
			  
               });
			 
			}
			
		
          $file++;
      return response()->json(
                  ['data' =>'All records were successfully imported into database.',
                   'status' => 200
               ]
           );
	
   }
 }  


 public function schoolUpload(Request $request){
         $file =0; 
		  while (Input::hasFile($file)) {
			
			$path = $request->file($file)->getRealPath();
			
			

			$data = Excel::load($path, function($reader) {})->get();
			$records=$data->count();
			
			
			for($p=1; $p<= $data->count(); $p++){
			 Excel::load($path, function($reader) {
             $results = $reader->get();
			 //echo count($results); return;
			 $r=0;
			while($r < count($results)){
			 $first_name=$results[$r]['first_name'];
             $middlename=$results[$r]['middle_name'];
             $lastname=$results[$r]['last_name'];
             $sex=$results[$r]['sex'];
             $form_four=$results[$r]['form_four_index_number'];
             $form_four_certification_year=$results[$r]['certification_year'];
             $applicant_id=$form_four."/".$form_four_certification_year;
             $college_admission=$results[$r]['college_admission_number'];
             $year_graduated=$results[$r]['graduated_year'];
             $department_id=$results[$r]['department'];
             $college_graduated=$results[$r]['college_graduated'];
             $dob=$results[$r]['dob'];
             $sne=$results[$r]['sne'];
            
			 
	$duplicates=Tbl_applicant::where('form_four_index',$form_four)
	             ->where('year_certified',$form_four_certification_year)->get()->count();
    if($duplicates == 0){
	Tbl_applicant::create(['first_name'=>$first_name,
	                        'middle_name'=>$middlename,
							'last_name'=>$lastname,
							'gender'=>$sex,
							'applicant_id'=>$applicant_id,
							'registration_number'=>$college_admission,
							'year_graduated'=>$year_graduated,
							'form_four_index'=>$form_four,
							'department_id' => $department_id,
							'college'      =>$college_graduated,
							'dob'=>$dob,
							'year_certified'=>$form_four_certification_year,
							'sne'=>$sne
							]);
		$imports=true;
	
	}
			$r++;
			}
			  
			  
               });
			 
			}
			
			return;

          $file++;
      return 'All records were successfully imported into database.';
	
   }
 }   
 
 public function teachersRequirementPerSchool(Request $request){
         $file =0; 
		  while (Input::hasFile($file)) {
			
			$path = $request->file($file)->getRealPath();
			
			

			$data = Excel::load($path, function($reader) {})->get();
			$records=$data->count();
			$rec =0;
			
			for($p=1; $p<= $data->count(); $p++){
			 Excel::load($path, function($reader) {
             $results = $reader->get();
			//return $results;
			//print_r($results); return;
			 $r=0;
			 
			while($r < count($results)){
			 $studentsNumber=$results[$r]['students_number'];
             $schoolIndex=$results[$r]['school_index'];
			 //return $schoolIndex;
			// print_r($schoolIndex);return;
			 
			 $sql="SELECT t1.id AS school_id FROM tbl_schools t1 	       
				   WHERE t1.centre_number='".$schoolIndex."'";
			 
	 $schools = DB::SELECT($sql);
   
   //return $schools;	
	
	foreach($schools AS $school){
		
		$school_id =$school->school_id;
		
		$duplicates=Tbl_teachers_requirement::where('school_id',$school_id)
	                      ->get()->count();
						  
						  
    if($duplicates == 0){
	  Tbl_teachers_requirement::create(['students_taking'=>$studentsNumber,
	                                    'school_id'=>$school_id
								]);
		$imports=true;
	
	}
		
	}
	
	
             
			 
	
			$r++;
			}
			  
			  
               });
			 
			}
			
			
          $file++;
		    return ' Records were successfully imported into database.';
		 
	
   }
 } 
 public function secondaryTeachersRequirementPerSchool(Request $request){
         $file =0; 
		  while (Input::hasFile($file)) {
			
			$path = $request->file($file)->getRealPath();
			
			

			$data = Excel::load($path, function($reader) {})->get();
			$records=$data->count();
			$rec =0;
			
			for($p=1; $p<= $data->count(); $p++){
			 Excel::load($path, function($reader) {
             $results = $reader->get();
			//return $results;
			//print_r($results); return;
			 $r=0;
			 
			while($r < count($results)){
			 $studentsNumber=$results[$r]['students_number'];
             $schoolIndex=$results[$r]['school_index'];
             $subject_code=$results[$r]['subject_code'];
             $class_grade=$results[$r]['class_grade'];
			 //return $schoolIndex;
			// print_r($schoolIndex);return;
			 
			 $sql="SELECT t1.id AS school_id,(SELECT t2.id  FROM tbl_teaching_subjects t2 WHERE t2.code='".$subject_code."' LIMIT 1) AS subject_id FROM tbl_schools t1 	       
				   WHERE t1.centre_number='".$schoolIndex."'";
			 
	 $schools = DB::SELECT($sql);
   
   //return $schools;	
	
	foreach($schools AS $school){
		
		$school_id =$school->school_id;
		$subject_id =$school->subject_id;
		
		$duplicates=Tbl_teachers_requirement::where('school_id',$school_id)                                    ->where('subject_id',$subject_id)
	                      ->get()->count();
						  
						  
    if($duplicates == 0){
	  Tbl_teachers_requirement::create(['students_taking'=>$studentsNumber,
	                                    'school_id'=>$school_id,
	                                    'subject_id'=>$subject_id
								]);
		$imports=true;
	
	}
		
	}
	
	
             
			 
	
			$r++;
			}
			  
			  
               });
			 
			}
			
			
          $file++;
		    return ' Records were successfully imported into database.';
		 
	
   }
 }   
	
	

	/**
     * Import file into database Code
     *
     * @var array
     */
	public function importExcel(Request $request)
	{

		if($request->hasFile('import_file')){
			$path = $request->file('import_file')->getRealPath();

			$data = Excel::load($path, function($reader) {})->get();

			if(!empty($data) && $data->count()){

				foreach ($data->toArray() as $key => $value) {
					if(!empty($value)){
						foreach ($value as $v) {		
							$insert[] = ['title' => $v['title'], 'description' => $v['description']];
						}
					}
				}

				
				if(!empty($insert)){
					Item::insert($insert);
					return back()->with('success','Insert Record successfully.');
				}

			}

		}

		return back()->with('error','Please Check your file, Something is wrong there.');
	}

}
?>