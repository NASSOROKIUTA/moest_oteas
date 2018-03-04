<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class menuController extends Controller
{
	
    public function getUserMenu($user_id){

        $sql="SELECT t1.*,
 
 CASE WHEN t1.user_id IS NOT NULL  THEN (SELECT  t2.name FROM  users t2 WHERE t2.id=t1.user_id AND t2.id='".$user_id."' GROUP  BY t2.id) END AS name


FROM vw_user_access_level t1

             WHERE t1.user_id='".$user_id."'
               AND t1.allowed=1
               AND t1.is_it_allowed_to_access=1
                GROUP BY state_p 
                ORDER BY descr ASC
                LIMIT 40
             ";

        return DB::SELECT($sql);


		
	}

	public function getLoginUserDetails($user_id){

	return DB::table('vw_user_details')->where('user_id',$user_id)->get();



	}
	
	public function getAuthorization($user_id,$state_name){
		
	$authorization_number= DB::table('vw_user_access_level')
									->select('state_p','descr','user_type','icons')
									->where('user_id',$user_id)
									->where('state_p',$state_name)
									->where('allowed',1)
									->where('is_it_allowed_to_access',1)
									->orderBy('descr','ASC')			
									->first();
									
									return count($authorization_number);		
		
		
	}
	
	
	
	
}
