<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\admin\Tbl_permission_user;
use App\admin\Tbl_integrating_key;
class UsersRegistrationController extends Controller
{
	
	//
    public function adminRegistration(Request $data)
    {
$email=$data['email'];
$mobile_number=$data['mobile_number'];
$facility_id=$data['facility_id'];
$intergratingKeys=Tbl_integrating_key::where('api_type',3)->get();

  if(count($intergratingKeys) ==0){
	 
      return response()->json([
                           'data' =>'Please Set Central IP Address for this Facility',
                           'status' =>0
                       ]);
	  
  }
		      $base_urls=$intergratingKeys[0]->base_urls;
		      $private_keys=$intergratingKeys[0]->private_keys;
		      $public_keys=$intergratingKeys[0]->public_keys;
        $check= User::where('email',$email)
            ->where('mobile_number',$mobile_number)
            ->where('facility_id',$facility_id)
            ->first();
        if(count($check)==1){
         	  return response()->json([
                           'data' =>$email." "."Already Registered",
                           'status' =>0
                       ]);
			 }
        else{
             $user[]= User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'mobile_number' => $data['mobile_number'],
                'facility_id' => $data['facility_id'],
                'proffesionals_id' => $data['proffesionals_id'],
                'gender' => $data['gender'],
                'password' => bcrypt($data['password']),
            ]);
			
	$user_id= $user[0]->id;
	Tbl_permission_user::create(["permission_id"=>52,"user_id"=>$user_id,"grant"=>1]);
			
				$foliolist_array=array();		
        $user_info=array();
        $diseases=array();
        $items_array =array();
        $entity_array["entities"]=array();
			foreach($user as $row) {
            $user_info['user_id']=$row->id;
            $user_info['name']=$row->name;
            $user_info['email']=$row->email;
            $user_info['mobile_number']=$row->mobile_number;
            $user_info['facility_id']=$row->facility_id;
            $user_info['proffesionals_id']=$row->proffesionals_id;
            $user_info['gender']=$row->gender;
            $user_info['password']=$row->password;         	         
            array_push($foliolist_array,$user_info);
			}
		
		$entity_array["entities"]=$foliolist_array;
        $data_string=json_encode($entity_array,JSON_PRETTY_PRINT);
			//return  $data_string;
			return self::DataSync($data_string,$base_urls,$private_keys,$public_keys);
			
            
        }
           
        }
		
		public function searchCouncil(Request $request){
   
	$sql="SELECT t1.* FROM tbl_councils t1 
	     WHERE t1.council_name LIKE '%".$request->searchKey."%'";
		   
		   return DB::SELECT($sql);
	
	
  }

    
    public function user_registration(Request $data)
    {
	
$email=$data['email'];
$mobile_number=$data['mobile_number'];
$council_id=$data['council_id'];

		

        $check= User::where('email',$email)
            ->where('mobile_number',$mobile_number)
             ->first();
        if(count($check)==1)
        {
           return response()->json([
            'data' => $email." "."Already Exists",
            'status' => 0
        ]);
        }
        else{
             $user[]= User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'council_id' => $data['council_id'],
                'department_id' => $data['department_id'],
                'mobile_number' => $data['mobile_number'],
                'user_type' => $data['proffesionals_id'],
                'gender' => $data['gender'],
                'password' => bcrypt($data['password']),
            ]);
			
		return response()->json([
            'data' => $data['name'].', was successfully registered',
            'status' => 1
        ]);
		
        }

    }
	
	
	
	

    public function user_list()
    {
        //return User::get();
        return DB::table('users')
            ->join('tbl_proffesionals','tbl_proffesionals.id','=','users.user_type')
            ->select('users.id','users.name','users.user_type',
                'users.mobile_number',
                'users.email',
                'users.created_at',
                'users.updated_at',
                'tbl_proffesionals.prof_name')
          //  ->where('users.user_type','!=',6)
            ->get();
    }


    public function user_delete($id)
    {

        return User::destroy($id);

    }

    public function user_update(Request $request)
    {
         $id=$request->all();
        $id=$request['id'];

        return User::where('id',$id)->update([
            'name'=>$request['name'],
            'mobile_number'=>$request['mobile_number'],
            'proffesionals_id'=>$request['proffesionals_id'],
            'email'=>$request['email'],
        ]);
    }


     public function check_password(Request $request)
    {
        
        $user_id=$request['id'];
        $password=bcrypt($request['password']) ;
        User::where('id',$user_id)->update(['password'=>$password]);
        return response()->json([
            'msg' => 'User Password Has changed..',
            'status' => '1'
        ]);
    }

    public function reset_password(Request $request)
    {
        $user = $request['user'];
        $userData = $request['details'];
        if($userData['new_password'] !=$userData['confirm_password']){
            return response()->json([
                'data' => 'Password mismatch..Please re-type password correctly and click save to continue',
                'status' => '0'
            ]);
        }
        else if(strlen($userData['new_password'])<8){
            return response()->json([
                'data' => 'Password must have at least 8 characters',
                'status' => '0'
            ]);
        }
        else {
            //$user_id=$request['user_id'];
            $user_id=$user['id'];
            $password=bcrypt($userData['new_password']) ;
            User::where('id',$user_id)->update(['password'=>$password]);
            return response()->json([
                'data' => 'Password changed..use your new password on next login',
                'status' => '1'
            ]);
        }

    }
}
