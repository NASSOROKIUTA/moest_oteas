<?php

namespace App\Http\Controllers\schools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\applicants\Tbl_applicant;
use App\schools\Tbl_teachers_requirement;
use Excel;
use DB;
class SchoolsController extends Controller
{

  public function teachersRequirementPerSchool(Request $request) 
  {
      $file =0; 
      $resposes=[];
      $success=true;
      $imports=false;
	   while (Input::hasFile($file)) {
		 $path = $request->file($file)->getRealPath();
			$data = Excel::load($path, function($reader) {})->get();
			 $records=$data->count();
			   $rec =0;			
			    for($p=1; $p<= $data->count(); $p++){
			      Excel::load($path, function($reader) {
                    $results = $reader->get();
                    $resposes[]=count($results);
			         $r=0;			 
			             while($r < count($results)){
			             	
	 $centre_number                     =$results[$r]['centre_number'];
     $total_non_sne_teachers            =$results[$r]['total_non_sne_teachers']; 
     $total_sne_teachers                =$results[$r]['total_sne_teachers']; 
     $total_sne_pupils					=$results[$r]['total_sne_pupils']; 
 	 $total_pre_primary_students        =$results[$r]['total_pre_primary_students'];
     $number_of_students_standard_1     =$results[$r]['number_of_students_standard_1']; 
     $number_of_students_standard_2     =$results[$r]['number_of_students_standard_2']; 
     $number_of_students_standard_3     =$results[$r]['number_of_students_standard_3']; 
     $number_of_students_standard_4     =$results[$r]['number_of_students_standard_4']; 
     $number_of_students_standard_5     =$results[$r]['number_of_students_standard_5']; 
     $number_of_students_standard_6     =$results[$r]['number_of_students_standard_6']; 
 	 $number_of_students_standard_7     =$results[$r]['number_of_students_standard_7']; 
  DB::beginTransaction();
    try {
    
    //import pre & primary data with SNE
     if(checkDuplicate('tbl_teachers_requirements',array('school_id','sne_non',"((timestampdiff(minute,created_at,CURRENT_TIMESTAMP) >= 0))"), array($centre_number,1))==false AND isset($centre_number)) {
        $resposes[]=Tbl_teachers_requirement::create(['students_taking'=>$total_sne_pupils,
	                                      'school_id'=>$centre_number,
	                                      'teachers_available'=>$total_sne_teachers,
	                                      'sne_non'=>1
								         ]);
        $imports=true;	
         }

         //import pre-primary data without SNE
     if(checkDuplicate('tbl_teachers_requirements',array('school_id','class_grade','sne_non',"((timestampdiff(minute,created_at,CURRENT_TIMESTAMP) >= 0))"), array($centre_number,1,0))==false AND isset($centre_number)) {
      $resposes[]=Tbl_teachers_requirement::create(['students_taking'=>$total_pre_primary_students,
	                                    'school_id'=>$centre_number,
	                                    'class_grade'=>1,
	                                    'teachers_available'=>$total_non_sne_teachers,
	                                    'sne_non'=>0
								         ]);
        $imports=true;	
         }

            //import STD I data without SNE
     if(checkDuplicate('tbl_teachers_requirements',array('school_id','class_grade','sne_non',"((timestampdiff(minute,created_at,CURRENT_TIMESTAMP) >= 0))"), array($centre_number,2,0))==false AND isset($centre_number)) {
      $resposes[]=  Tbl_teachers_requirement::create(['students_taking'=>$number_of_students_standard_1,
	                                    'school_id'=>$centre_number,
	                                    'class_grade'=>2,
	                                    'teachers_available'=>$total_non_sne_teachers,
	                                    'sne_non'=>0
								         ]);
        $imports=true;	
         }

               //import STD II data without SNE
     if(checkDuplicate('tbl_teachers_requirements',array('school_id','class_grade','sne_non',"((timestampdiff(minute,created_at,CURRENT_TIMESTAMP) >= 0))"), array($centre_number,3,0))==false AND isset($centre_number)) {
      $resposes[]=  Tbl_teachers_requirement::create(['students_taking'=>$number_of_students_standard_2,
	                                    'school_id'=>$centre_number,
	                                    'class_grade'=>3,
	                                    'teachers_available'=>$total_non_sne_teachers,
	                                    'sne_non'=>0
								         ]);
        $imports=true;	
         }

                    //import STD III data without SNE
     if(checkDuplicate('tbl_teachers_requirements',array('school_id','class_grade','sne_non',"((timestampdiff(minute,created_at,CURRENT_TIMESTAMP) >= 0))"), array($centre_number,4,0))==false AND isset($centre_number)) {
      $resposes[]=  Tbl_teachers_requirement::create(['students_taking'=>$number_of_students_standard_3,
	                                    'school_id'=>$centre_number,
	                                    'class_grade'=>4,
	                                    'teachers_available'=>$total_non_sne_teachers,
	                                    'sne_non'=>0
								         ]);
        $imports=true;	
         }

                      //import STD IV data without SNE
     if(checkDuplicate('tbl_teachers_requirements',array('school_id','class_grade','sne_non',"((timestampdiff(minute,created_at,CURRENT_TIMESTAMP) >= 0))"), array($centre_number,5,0))==false AND isset($centre_number)) {
       $resposes[]= Tbl_teachers_requirement::create(['students_taking'=>$number_of_students_standard_4,
	                                    'school_id'=>$centre_number,
	                                    'class_grade'=>5,
	                                    'teachers_available'=>$total_non_sne_teachers,
	                                    'sne_non'=>0
								         ]);
        $imports=true;	
         }

                           //import STD V data without SNE
     if(checkDuplicate('tbl_teachers_requirements',array('school_id','class_grade','sne_non',"((timestampdiff(minute,created_at,CURRENT_TIMESTAMP) >= 0))"), array($centre_number,6,0))==false AND isset($centre_number)) {
     $resposes[]=   Tbl_teachers_requirement::create(['students_taking'=>$number_of_students_standard_5,
	                                    'school_id'=>$centre_number,
	                                    'class_grade'=>6,
	                                    'teachers_available'=>$total_non_sne_teachers,
	                                    'sne_non'=>0
								         ]);
        $imports=true;	
         }

                             //import STD VI data without SNE
     if(checkDuplicate('tbl_teachers_requirements',array('school_id','class_grade','sne_non',"((timestampdiff(minute,created_at,CURRENT_TIMESTAMP) >= 0))"), array($centre_number,7,0))==false AND isset($centre_number)) {
       $resposes[]= Tbl_teachers_requirement::create(['students_taking'=>$number_of_students_standard_6,
	                                    'school_id'=>$centre_number,
	                                    'class_grade'=>7,
	                                    'teachers_available'=>$total_non_sne_teachers,
	                                    'sne_non'=>0
								         ]);
        $imports=true;	
         }

                                //import STD VII data without SNE
     if(checkDuplicate('tbl_teachers_requirements',array('school_id','class_grade','sne_non',"((timestampdiff(minute,created_at,CURRENT_TIMESTAMP) >= 0))"), array($centre_number,8,0))==false AND isset($centre_number)) {
      $resposes[]=  Tbl_teachers_requirement::create(['students_taking'=>$number_of_students_standard_7,
	                                    'school_id'=>$centre_number,
	                                    'class_grade'=>8,
	                                    'teachers_available'=>$total_non_sne_teachers,
	                                    'sne_non'=>0
								         ]);
        $imports=true;	
         }
  
   	DB::commit();
        $success = true;
    } catch (\Exception $e) {
        $success = false;       
        DB::rollback();
         $imports=false;
            }
		$r++;
			}
              });			 
			}
          $file++;
           return response()->json([
            'message' =>' Records were successfully imported into database',
            'status' => 200
        ]);
		 
		 
	
   }
 } 
 //Requirements for non-sne=0 students
 public function generateSchoolRequirements(Request $request){
 	try{
$sql="INSERT INTO tbl_projected_teachers(school_id, stream, periods,teachers_required,class_grade,created_at,updated_at)
        SELECT t1.school_id,
        CEIL(SUM(students_taking/60)) AS stream,              
        (CEIL(SUM(students_taking/60))*t2.number_of_periods) AS periods,
        CEIL((CEIL(SUM(students_taking/60))*t2.number_of_periods)/30) AS teachers_required,
       t1.class_grade,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP FROM `tbl_teachers_requirements` t1 
       INNER JOIN tbl_periods_per_weeks t2 ON t2.class_grade_id=t1.class_grade
       INNER JOIN tbl_schools t3 ON t3.centre_number=t1.school_id
       WHERE sne_non=0
       GROUP BY t1.`school_id`,t1.`class_grade`";
         return DB::SELECT($sql); 		
 	}catch (\Exception $e) {
         //something went wrong...
           }


 }

 public function teachersRequiredWithClassrooms(Request $request){
 	try{
$sql="INSERT INTO tbl_school_requirements(school_id,council_id,required_teachers,  ptr,deficit_rooms,created_at,updated_at)
      SELECT t1.school_id,t2.council_id,(SELECT SUM(t1.teachers_required)-t3.teachers_available FROM tbl_teachers_requirements t3
WHERE t3.school_id=t1.school_id AND t3.sne_non=0 GROUP BY t1.school_id LIMIT 1)AS required_teachers ,
(SELECT ROUND((SUM(t3.students_taking)/t3.teachers_available),1) FROM tbl_teachers_requirements t3
WHERE t3.school_id=t1.school_id AND t3.sne_non=0 GROUP BY t1.school_id LIMIT 1)AS ptr ,
SUM(stream)-t2.class_rooms AS deficit_rooms,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP
FROM tbl_projected_teachers t1
INNER JOIN tbl_schools t2 ON t2.centre_number=t1.school_id
GROUP BY t1.school_id";
         return DB::SELECT($sql); 		
 	}catch (\Exception $e) {
         //something went wrong...
           }
 }



}
