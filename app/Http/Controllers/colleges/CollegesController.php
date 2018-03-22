<?php

namespace App\Http\Controllers\colleges;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;
use Excel;
use App\colleges\Tbl_college;
class CollegesController extends Controller
{
    //
    public function getCollegesRegistered(Request $request){
        $fieldName=$request->fieldName;
        $filter_by=$request->filter_by;
        $sql="SELECT t1.* FROM tbl_colleges t1 WHERE t1.`$fieldName` LIKE '%".$filter_by."%'";
           return DB::SELECT($sql);

    }

    public function saveColleges(Request $request){
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
	 $reg_number                       =$results[$r]['reg_number'];
     $college_name                     =$results[$r]['college_name']; 
     $email                            =$results[$r]['email']; 
     $focal_person					   =$results[$r]['focal_person']; 
 	 $education_level                  =$results[$r]['education_level'];
     $ownership_status                 =$results[$r]['ownership_status']; 
     $registration_status              =$results[$r]['registration_status']; 
     $college_status                   =$results[$r]['college_status']; 
     $college_address                  =$results[$r]['college_address']; 
     DB::beginTransaction();
    try {
    
     if(checkDuplicate('tbl_colleges',array('college',"((timestampdiff(minute,created_at,CURRENT_TIMESTAMP) >= 0))"), array($reg_number))==false AND isset($reg_number)) {
     	$college=['college'=>$reg_number,'college_name'=>$college_name,'college_status'=>$college_status,
                  'registration_status'=>$registration_status,'email'=>$email,'focalname'=>$focal_person,
                  'college_address'=>$college_address,'education_level'=>$education_level,'ownership_status'=>$ownership_status];
        $resposes[]=Tbl_college::create($college);
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
            'message' =>'College Records were successfully imported into database',
            'status' => 200
        ]);
		 
		 
	
   }
 }
}
