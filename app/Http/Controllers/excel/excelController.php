<?php
namespace App\Http\Controllers\excel;
ob_start();
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\applicants\Tbl_applicant;
use App\schools\Tbl_teachers_requirement;
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
		return view('importExport');
	}

	/**
     * File Export Code
     *
     * @var array
     */
	public function downloadExcel(Request $request, $type)
	{
		$data = Tbl_applicant::get()->toArray();
  Excel::create('applicants', function($excel) use ($data) {
 $excel->sheet('applicants', function($sheet) use ($data){
			$sheet->fromArray($data);
	        });
		})->store('xls')->export('xls');
		
		header('Content-Disposition: attachment;filename="applicants.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
		
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
			 $first_name=$results[$r]['firstname'];
             $middlename=$results[$r]['middlename'];
             $lastname=$results[$r]['lastname'];
             $sex=$results[$r]['sex'];
             $form_four=$results[$r]['form_four_index'];
             $form_four_certification_year=$results[$r]['certification_year'];
             $college_admission=$results[$r]['college_admission'];
             $year_graduated=$results[$r]['graduated_year'];
             $dob=$results[$r]['dob'];
             $id=$results[$r]['id'];
			 
			 
	$duplicates=Tbl_applicant::where('form_four_index',$form_four)
	             ->where('year_certified',$form_four_certification_year)->get()->count();
    if($duplicates == 0){
	Tbl_applicant::create(['first_name'=>$first_name,
	                        'middle_name'=>$middlename,
							'last_name'=>$lastname,
							'gender'=>$sex,
							'registration_number'=>$college_admission,
							'year_graduated'=>$year_graduated,
							'form_four_index'=>$form_four,
							'dob'=>$dob,
							'year_certified'=>$form_four_certification_year
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