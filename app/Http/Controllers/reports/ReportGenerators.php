<?php

namespace App\Http\Controllers\reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReportGenerators extends Controller
{
    //
	public function countClinicDiagnosis(Request $request){
        //find age in months
        $dob = explode('-',$request['dob']);
        $dob = $dob[2]."-".$dob[1]."-".$dob[0];
        $dob = new \DateTime($dob);
        $interval = (new \DateTime())->diff($dob);
        $age_group = $interval->m + $interval->y*12;
        $gender = $request['gender'];

        if($age_group <= 0){
            $age_group = $gender == "MALE" ? "male_under_one_month" : "female_under_one_month";
            $total_group = "total_under_one_month";
        }elseif($age_group <= 11){
            $age_group = $gender == "MALE" ? "male_under_one_year" : "female_under_one_year";
            $total_group = "total_under_one_year";
        }elseif($age_group <= 59){
            $age_group = $gender == "MALE" ? "male_under_five_year" : "female_under_five_year";
            $total_group = "total_under_five_year";
        }elseif($age_group <= 719){
            $age_group = $gender == "MALE" ? "male_above_five_under_sixty" : "female_above_five_under_sixty";
            $total_group = "total_above_five_under_sixty";
        }elseif($age_group >= 720){
            $age_group = $gender == "MALE" ? "male_above_sixty" : "female_above_sixty";
            $total_group = "total_above_sixty";
        }

        $todayCounts = DB::select("select count(*) count from tbl_clinic_diagnosis_register where facility_id='".$request['facility_id']."' and clinic_id='".$request['clinic_id']."' and  date=CURRENT_DATE");
        if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
            DB::statement("update tbl_clinic_diagnosis_register set $age_group = $age_group+1,$total_group=$total_group+1,".($gender == "MALE" ? "total_male = total_male+1" : "total_female=total_female+1").",grand_total=grand_total+1 where date = CURRENT_DATE and facility_id='".$request['facility_id']."' and clinic_id='".$request['clinic_id']."'");
        }else{
            DB::statement("insert into tbl_clinic_diagnosis_register(facility_id,clinic_id,$age_group,$total_group,".($gender == "MALE" ? "total_male" : "total_female").",grand_total,date) select '".$request['facility_id']."','".$request['clinic_id']."',1,1,1,1, CURRENT_DATE");
        }
    }


    public function countNewAttendance(Request $request){
		if(!isset($request['clinic_id']))
			return;
        //find age in months
        $dob = explode('-',$request['dob']);
        $dob = $dob[2]."-".$dob[1]."-".$dob[0];
        $dob = new \DateTime($dob);
        $interval = (new \DateTime())->diff($dob);
        $age_group = $interval->m + $interval->y*12;
        $gender = $request['gender'];

        if($age_group <= 0){
            $age_group = $gender == "MALE" ? "male_under_one_month" : "female_under_one_month";
            $total_group = "total_under_one_month";
        }elseif($age_group <= 11){
            $age_group = $gender == "MALE" ? "male_under_one_year" : "female_under_one_year";
            $total_group = "total_under_one_year";
        }elseif($age_group <= 59){
            $age_group = $gender == "MALE" ? "male_under_five_year" : "female_under_five_year";
            $total_group = "total_under_five_year";
        }elseif($age_group <= 719){
            $age_group = $gender == "MALE" ? "male_above_five_under_sixty" : "female_above_five_under_sixty";
            $total_group = "total_above_five_under_sixty";
        }elseif($age_group >= 720){
            $age_group = $gender == "MALE" ? "male_above_sixty" : "female_above_sixty";
            $total_group = "total_above_sixty";
        }
		
	
        $todayCounts = DB::select("select count(*) count from tbl_newattendance_registers where facility_id='".$request['facility_id']."' and clinic_id='".$request['clinic_id']."' and date=CURRENT_DATE");
		
		return $todayCounts;
        if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
            DB::statement("update tbl_newattendance_registers set $age_group = $age_group+1,$total_group=$total_group+1,".($gender == "MALE" ? "total_male = total_male+1" : "total_female=total_female+1").",grand_total=grand_total+1 where date = CURRENT_DATE and facility_id='".$request['facility_id']."' and clinic_id='".$request['clinic_id']."'");
        }else{
            DB::statement("insert into tbl_newattendance_registers(facility_id,clinic_id,$age_group,$total_group,".($gender == "MALE" ? "total_male" : "total_female").",grand_total,date) select '".$request['facility_id']."','".$request['clinic_id']."',1,1,1,1, CURRENT_DATE");
        }
    }

   

    public function countReattendance(Request $request){
		if(!isset($request['clinic_id']))
			return;
        //find age in months
        $dob = explode('-',$request['dob']);
        $dob = $dob[2]."-".$dob[1]."-".$dob[0];
        $dob = new \DateTime($dob);
        $interval = (new \DateTime())->diff($dob);
        $age_group = $interval->m + $interval->y*12;
        $gender = $request['gender'];

        if($age_group <= 0){
            $age_group = $gender == "MALE" ? "male_under_one_month" : "female_under_one_month";
            $total_group = "total_under_one_month";
        }elseif($age_group <= 11){
            $age_group = $gender == "MALE" ? "male_under_one_year" : "female_under_one_year";
            $total_group = "total_under_one_year";
        }elseif($age_group <= 59){
            $age_group = $gender == "MALE" ? "male_under_five_year" : "female_under_five_year";
            $total_group = "total_under_five_year";
        }elseif($age_group <= 719){
            $age_group = $gender == "MALE" ? "male_above_five_under_sixty" : "female_above_five_under_sixty";
            $total_group = "total_above_five_under_sixty";
        }elseif($age_group >= 720){
            $age_group = $gender == "MALE" ? "male_above_sixty" : "female_above_sixty";
            $total_group = "total_above_sixty";
        }

        $todayCounts = DB::select("select count(*) count from tbl_reattendance_registers where facility_id='".$request['facility_id']."' and clinic_id='".$request['clinic_id']."' and date=CURRENT_DATE");
        if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
            DB::statement("update tbl_reattendance_registers set $age_group = $age_group+1,$total_group=$total_group+1,".($gender == "MALE" ? "total_male = total_male+1" : "total_female=total_female+1").",grand_total=grand_total+1 where date = CURRENT_DATE and facility_id='".$request['facility_id']."' and clinic_id='".$request['clinic_id']."'");
        }else{
            DB::statement("insert into tbl_reattendance_registers(facility_id,clinic_id,$age_group,$total_group,".($gender == "MALE" ? "total_male" : "total_female").",grand_total,date) select '".$request['facility_id']."','".$request['clinic_id']."',1,1,1,1, CURRENT_DATE");
        }
    }

	public function countReferral(Request $request){
		 $dob = explode('-',$request['dob']);
        $dob = $dob[2]."-".$dob[1]."-".$dob[0];
        $dob = new \DateTime($dob);
        $interval = (new \DateTime())->diff($dob);
        $age_group = $interval->m + $interval->y*12;
        $gender = $request['gender'];

        if($age_group <= 0){
            $age_group = $gender == "MALE" ? "male_under_one_month" : "female_under_one_month";
            $total_group = "total_under_one_month";
        }elseif($age_group <= 11){
            $age_group = $gender == "MALE" ? "male_under_one_year" : "female_under_one_year";
            $total_group = "total_under_one_year";
        }elseif($age_group <= 59){
            $age_group = $gender == "MALE" ? "male_under_five_year" : "female_under_five_year";
            $total_group = "total_under_five_year";
        }elseif($age_group <= 719){
            $age_group = $gender == "MALE" ? "male_above_five_under_sixty" : "female_above_five_under_sixty";
            $total_group = "total_above_five_under_sixty";
        }elseif($age_group >= 720){
            $age_group = $gender == "MALE" ? "male_above_sixty" : "female_above_sixty";
            $total_group = "total_above_sixty";
        }

        $todayCounts = DB::select("select count(*) count from tbl_outgoing_referral_register where facility_id='".$request['facility_id']."' and date=CURRENT_DATE");
        if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
            DB::statement("update tbl_outgoing_referral_register set $age_group = $age_group+1,$total_group=$total_group+1,".($gender == "MALE" ? "total_male = total_male+1" : "total_female=total_female+1").",grand_total=grand_total+1 where date = CURRENT_DATE and facility_id='".$request['facility_id']."'");
        }else{
            DB::statement("insert into tbl_outgoing_referral_register(facility_id,$age_group,$total_group,".($gender == "MALE" ? "total_male" : "total_female").",grand_total,date) select '".$request['facility_id']."',1,1,1,1, CURRENT_DATE");
        }
    }

	
    public function countAdmission(Request $request){
        //find age in months
        $dob = explode('-',$request['dob']);
        $dob = $dob[2]."-".$dob[1]."-".$dob[0];
        $dob = new \DateTime($dob);
        $interval = (new \DateTime())->diff($dob);
        $age_group = $interval->m + $interval->y*12;
        $gender = $request['gender'];

        if($age_group <= 0){
            $age_group = $gender == "MALE" ? "male_under_one_month" : "female_under_one_month";
            $total_group = "total_under_one_month";
        }elseif($age_group <= 11){
            $age_group = $gender == "MALE" ? "male_under_one_year" : "female_under_one_year";
            $total_group = "total_under_one_year";
        }elseif($age_group <= 59){
            $age_group = $gender == "MALE" ? "male_under_five_year" : "female_under_five_year";
            $total_group = "total_under_five_year";
        }elseif($age_group <= 719){
            $age_group = $gender == "MALE" ? "male_above_five_under_sixty" : "female_above_five_under_sixty";
            $total_group = "total_above_five_under_sixty";
        }elseif($age_group >= 720){
            $age_group = $gender == "MALE" ? "male_above_sixty" : "female_above_sixty";
            $total_group = "total_above_sixty";
        }

        $todayCounts = DB::select("select count(*) count from tbl_admission_registers where facility_id='".$request['facility_id']."' and  date=CURRENT_DATE");
        if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
            DB::statement("update tbl_admission_registers set $age_group = $age_group+1,$total_group=$total_group+1,".($gender == "MALE" ? "total_male = total_male+1" : "total_female=total_female+1").",grand_total=grand_total+1 where date = CURRENT_DATE and facility_id='".$request['facility_id']."'");
        }else{
            DB::statement("insert into tbl_admission_registers(facility_id,$age_group,$total_group,".($gender == "MALE" ? "total_male" : "total_female").",grand_total,date) select '".$request['facility_id']."',1,1,1,1, CURRENT_DATE");
        }
    }

    public function countOPDDiagnosis(Request $request){
        //find age in months
        $dob = explode('-',$request['dob']);
        $dob = $dob[2]."-".$dob[1]."-".$dob[0];
        $dob = new \DateTime($dob);
        $interval = (new \DateTime())->diff($dob);
        $age_group = $interval->m + $interval->y*12;
        $gender = $request['gender'];

        if($age_group <= 0){
            $age_group = $gender == "MALE" ? "male_under_one_month" : "female_under_one_month";
            $total_group = "total_under_one_month";
        }elseif($age_group <= 11){
            $age_group = $gender == "MALE" ? "male_under_one_year" : "female_under_one_year";
            $total_group = "total_under_one_year";
        }elseif($age_group <= 59){
            $age_group = $gender == "MALE" ? "male_under_five_year" : "female_under_five_year";
            $total_group = "total_under_five_year";
        }elseif($age_group <= 719){
            $age_group = $gender == "MALE" ? "male_above_five_under_sixty" : "female_above_five_under_sixty";
            $total_group = "total_above_five_under_sixty";
        }elseif($age_group >= 720){
            $age_group = $gender == "MALE" ? "male_above_sixty" : "female_above_sixty";
            $total_group = "total_above_sixty";
        }

        $observations = $request['concepts'];
        foreach($observations as $observation){
            $mtuha_diagnosis = DB::select("select opd_mtuha_diagnosis_id as mtuha_diagnosis_id from tbl_opd_mtuha_icd_blocks where icd_block = '".substr($observation['code'],0,3)."'");
			
			if(!is_array($mtuha_diagnosis) || count($mtuha_diagnosis) == 0)
				$mtuha_diagnosis[0] = (Object) array("mtuha_diagnosis_id"=>NULL);
			
			$todayCounts = DB::select("select count(*) count from tbl_opd_diseases_registers where opd_mtuha_diagnosis_id = '". $mtuha_diagnosis[0]->mtuha_diagnosis_id."' and facility_id='".$request['facility_id']."' and date=CURRENT_DATE");
            if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                DB::statement("update tbl_opd_diseases_registers set $age_group = $age_group+1,$total_group=$total_group+1,".($gender == "MALE" ? "total_male = total_male+1" : "total_female=total_female+1").",grand_total=grand_total+1 where date = CURRENT_DATE  and opd_mtuha_diagnosis_id = ".$mtuha_diagnosis[0]->mtuha_diagnosis_id." and facility_id='".$request['facility_id']."'");
            }else{
                DB::statement("insert into tbl_opd_diseases_registers(facility_id,opd_mtuha_diagnosis_id,$age_group,$total_group,".($gender == "MALE" ? "total_male" : "total_female").",grand_total,date) select '".$request['facility_id']."',".($mtuha_diagnosis[0]->mtuha_diagnosis_id != NULL  ? $mtuha_diagnosis[0]->mtuha_diagnosis_id : "NULL") .",1,1,1,1, CURRENT_DATE");
            }
        }
    }

    public function countIPDDiagnosis(Request $request){
        //find age in months
        $dob = explode('-',$request['dob']);
        $dob = $dob[2]."-".$dob[1]."-".$dob[0];
        $dob = new \DateTime($dob);
        $interval = (new \DateTime())->diff($dob);
        $age_group = $interval->m + $interval->y*12;
        $gender = $request['gender'];

        if($age_group <= 0){
            $age_group = $gender == "MALE" ? "male_under_one_month" : "female_under_one_month";
            $total_group = "total_under_one_month";
        }elseif($age_group <= 11){
            $age_group = $gender == "MALE" ? "male_under_one_year" : "female_under_one_year";
            $total_group = "total_under_one_year";
        }elseif($age_group <= 59){
            $age_group = $gender == "MALE" ? "male_under_five_year" : "female_under_five_year";
            $total_group = "total_under_five_year";
        }elseif($age_group <= 719){
            $age_group = $gender == "MALE" ? "male_above_five_under_sixty" : "female_above_five_under_sixty";
            $total_group = "total_above_five_under_sixty";
        }elseif($age_group >= 720){
            $age_group = $gender == "MALE" ? "male_above_sixty" : "female_above_sixty";
            $total_group = "total_above_sixty";
        }

        $observations = $request['concepts'];
        foreach($observations as $observation){
            $mtuha_diagnosis = DB::select("select ipd_mtuha_diagnosis_id as mtuha_diagnosis_id from tbl_ipd_mtuha_icd_blocks where icd_block = '".substr($observation['code'],0,3)."'");
			if(!is_array($mtuha_diagnosis) || count($mtuha_diagnosis)==0)
				$mtuha_diagnosis[0] = (Object) array("mtuha_diagnosis_id"=>NULL, "count"=>0);
			
			$todayCounts = DB::select("select count(*) count from tbl_ipd_diseases_registers where ipd_mtuha_diagnosis_id = '". $mtuha_diagnosis[0]->mtuha_diagnosis_id."' and facility_id='".$request['facility_id']."' and date=CURRENT_DATE");
            if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                DB::statement("update tbl_ipd_diseases_registers set $age_group = $age_group+1,$total_group=$total_group+1,".($gender == "MALE" ? "total_male = total_male+1" : "total_female=total_female+1").",grand_total=grand_total+1 where date = CURRENT_DATE  and ipd_mtuha_diagnosis_id = ".$mtuha_diagnosis[0]->mtuha_diagnosis_id." and facility_id='".$request['facility_id']."'");
            }else{
                DB::statement("insert into tbl_ipd_diseases_registers(facility_id,ipd_mtuha_diagnosis_id, description,$age_group,$total_group,".($gender == "MALE" ? "total_male" : "total_female").",grand_total,date) select '".$request['facility_id']."',".($mtuha_diagnosis[0]->mtuha_diagnosis_id != NULL  ? $mtuha_diagnosis[0]->mtuha_diagnosis_id : "NULL").",1,1,1,1, CURRENT_DATE");
            }
        }
    }

    public function restartRegister(Request $request){
		//note only OPD (clinic_id=1) is considered here for attendances
		$clinic_id  = 1;
        $facility_id = $request['facility_id'];
        DB::statement("delete from tbl_opd_diseases_registers where facility_id=$facility_id");
        DB::statement("delete from  tbl_ipd_diseases_registers where facility_id=$facility_id");
        DB::statement("delete from  tbl_newattendance_registers where facility_id=$facility_id  and clinic_id = $clinic_id");
        DB::statement("delete from  tbl_reattendance_registers where facility_id=$facility_id  and clinic_id = $clinic_id");
        DB::statement("delete from  tbl_admission_registers where facility_id=$facility_id");

		$dates = DB::select("select distinct date(created_at) `date` from tbl_patients where facility_id=$facility_id");
        foreach($dates as $date){
            //<1month
            $group = DB::select("select count(*) count from tbl_patients where facility_id=$facility_id and gender='male' and timestampdiff(month, dob,  created_at) < 1 and date(created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_newattendance_registers where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_newattendance_registers set male_under_one_month = male_under_one_month+".$group[0]->count." where facility_id=$facility_id and clinic_id = $clinic_id date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_newattendance_registers(facility_id,clinic_id,male_under_one_month,date) select '$facility_id','$clinic_id',".$group[0]->count.",'".$date->date."'");
                }
            }

            $group = DB::select("select count(*) count from tbl_patients where facility_id=$facility_id and gender='female' and timestampdiff(month, dob,  created_at) < 1 and date(created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_newattendance_registers where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_newattendance_registers set female_under_one_month = female_under_one_month+".$group[0]->count." where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_newattendance_registers(facility_id,clinic_id,female_under_one_month,date) select '$facility_id','$clinic_id',".$group[0]->count.",'".$date->date."'");
                }
            }


            //<1year
            $group = DB::select("select count(*) count from tbl_patients where facility_id=$facility_id and  gender='male' and timestampdiff(month, dob,  created_at) between 1 and 11 and date(created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_newattendance_registers where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_newattendance_registers set male_under_one_year = male_under_one_year+".$group[0]->count." where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_newattendance_registers(facility_id,clinic_id,male_under_one_year,date) select '$facility_id','$clinic_id',".$group[0]->count.",'".$date->date."'");
                }
            }

            $group = DB::select("select count(*) count from tbl_patients where facility_id=$facility_id and gender='female' and timestampdiff(month, dob,  created_at)  between 1 and  11 and date(created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_newattendance_registers where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_newattendance_registers set female_under_one_year = female_under_one_year+".$group[0]->count." where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_newattendance_registers(facility_id,clinic_id,female_under_one_year,date) select '$facility_id','$clinic_id',".$group[0]->count.",'".$date->date."'");
                }
            }


            //<5year
            $group = DB::select("select count(*) count from tbl_patients where facility_id=$facility_id and gender='male' and timestampdiff(month, dob,  created_at)  between 12 and  59 and date(created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_newattendance_registers where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_newattendance_registers set male_under_five_year = male_under_five_year+".$group[0]->count." where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_newattendance_registers(facility_id,clinic_id,male_under_five_year,date) select '$facility_id','$clinic_id',".$group[0]->count.",'".$date->date."'");
                }
            }

            $group = DB::select("select count(*) count from tbl_patients where facility_id=$facility_id and gender='female' and timestampdiff(month, dob,  created_at)  between 12 and  59 and date(created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_newattendance_registers where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_newattendance_registers set female_under_five_year = female_under_five_year+".$group[0]->count." where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_newattendance_registers(facility_id,clinic_id,female_under_five_year,date) select '$facility_id','$clinic_id',".$group[0]->count.",'".$date->date."'");
                }
            }


            //<60year
            $group = DB::select("select count(*) count from tbl_patients where facility_id=$facility_id and gender='male' and timestampdiff(month, dob,  created_at)  between 60 and  719 and date(created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_newattendance_registers where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_newattendance_registers set male_above_five_under_sixty = male_above_five_under_sixty+".$group[0]->count." where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_newattendance_registers(facility_id,clinic_id,male_above_five_under_sixty,date) select '$facility_id','$clinic_id',".$group[0]->count.",'".$date->date."'");
                }
            }

            $group = DB::select("select count(*) count from tbl_patients where facility_id=$facility_id and gender='female' and timestampdiff(month, dob,  created_at)  between 60 and  719 and date(created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_newattendance_registers where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_newattendance_registers set female_above_five_under_sixty = female_above_five_under_sixty+".$group[0]->count." where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_newattendance_registers(facility_id,clinic_id,female_above_five_under_sixty,date) select '$facility_id','$clinic_id',".$group[0]->count.",'".$date->date."'");
                }
            }


            //>=60year
            $group = DB::select("select count(*) count from tbl_patients where facility_id=$facility_id and gender='male' and timestampdiff(month, dob,  created_at) >=720 and date(created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_newattendance_registers where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_newattendance_registers set male_above_sixty = male_above_sixty+".$group[0]->count." where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_newattendance_registers(facility_id,clinic_id,male_above_sixty,date) select '$facility_id','$clinic_id',".$group[0]->count.",'".$date->date."'");
                }
            }

            $group = DB::select("select count(*) count from tbl_patients where facility_id=$facility_id and gender='female' and timestampdiff(month, dob,  created_at) >=720 and date(created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_newattendance_registers where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_newattendance_registers set female_above_sixty = female_above_sixty+".$group[0]->count." where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_newattendance_registers(facility_id,clinic_id,female_above_sixty,date) select '$facility_id','$clinic_id',".$group[0]->count.",'".$date->date."'");
                }
            }

        }

        $dates = DB::select("select distinct date(created_at) `date` from tbl_accounts_numbers where facility_id=$facility_id");
        foreach($dates as $date){
            //<1month
            $group = DB::select("select count(*) count from tbl_patients where gender='male' and id in (select patient_id from tbl_accounts_numbers where facility_id=$facility_id and timestampdiff(month, dob,  tbl_accounts_numbers.created_at) < 1 and date(tbl_accounts_numbers.created_at)='".$date->date."' having count(patient_id) > 1)");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_reattendance_registers where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_reattendance_registers set male_under_one_month = male_under_one_month+".$group[0]->count." where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_reattendance_registers(facility_id,clinic_id,male_under_one_month,date) select '$facility_id','$clinic_id',".$group[0]->count.",'".$date->date."'");
                }
            }

            $group = DB::select("select count(*) count from tbl_patients where gender='female' and id in (select patient_id from tbl_accounts_numbers where facility_id=$facility_id and timestampdiff(month, dob,  tbl_accounts_numbers.created_at) < 1 and date(tbl_accounts_numbers.created_at)='".$date->date."' having count(patient_id) > 1)");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_reattendance_registers where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_reattendance_registers set female_under_one_month = female_under_one_month+".$group[0]->count." where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_reattendance_registers(facility_id,clinic_id,female_under_one_month,date) select '$facility_id','$clinic_id',".$group[0]->count.",'".$date->date."'");
                }
            }


            //<1year
            $group = DB::select("select count(*) count from tbl_patients where gender='male' and id in (select patient_id from tbl_accounts_numbers where facility_id=$facility_id and timestampdiff(month, dob,  tbl_accounts_numbers.created_at) between 1 and 11 and date(tbl_accounts_numbers.created_at)='".$date->date."' having count(patient_id) > 1)");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_reattendance_registers where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_reattendance_registers set male_under_one_year = male_under_one_year+".$group[0]->count." where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_reattendance_registers(facility_id,clinic_id,male_under_one_year,date) select '$facility_id','$clinic_id',".$group[0]->count.",'".$date->date."'");
                }
            }

            $group = DB::select("select count(*) count from tbl_patients where gender='female' and id in (select patient_id from tbl_accounts_numbers where facility_id=$facility_id and timestampdiff(month, dob,  tbl_accounts_numbers.created_at) between 1 and 11 and date(tbl_accounts_numbers.created_at)='".$date->date."' having count(patient_id) > 1)");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_reattendance_registers where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_reattendance_registers set female_under_one_year = female_under_one_year+".$group[0]->count." where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_reattendance_registers(facility_id,clinic_id,female_under_one_year,date) select '$facility_id','$clinic_id',".$group[0]->count.",'".$date->date."'");
                }
            }


            //<5year
            $group = DB::select("select count(*) count from tbl_patients where gender='male' and id in (select patient_id from tbl_accounts_numbers where facility_id=$facility_id and timestampdiff(month, dob,  tbl_accounts_numbers.created_at) between 12 and 59 and  date(tbl_accounts_numbers.created_at)='".$date->date."' having count(patient_id) > 1)");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_reattendance_registers where facility_id=$facility_id and clinic_id = $clinic_id and date ='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_reattendance_registers set male_under_five_year = male_under_five_year+".$group[0]->count." where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_reattendance_registers(facility_id,clinic_id,male_under_five_year,date) select '$facility_id','$clinic_id',".$group[0]->count.",'".$date->date."'");
                }
            }

            $group = DB::select("select count(*) count from tbl_patients where gender='female' and id in (select patient_id from tbl_accounts_numbers where facility_id=$facility_id and timestampdiff(month, dob,  tbl_accounts_numbers.created_at) between 12 and 59 and date(tbl_accounts_numbers.created_at)='".$date->date."' having count(patient_id) > 1)");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_reattendance_registers where facility_id=$facility_id and clinic_id = $clinic_id and date ='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_reattendance_registers set female_under_five_year = female_under_five_year+".$group[0]->count." where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_reattendance_registers(facility_id,clinic_id,female_under_five_year,date) select '$facility_id','$clinic_id',".$group[0]->count.",'".$date->date."'");
                }
            }


            //<60year
            $group = DB::select("select count(*) count from tbl_patients where gender='male' and id in (select patient_id from tbl_accounts_numbers where facility_id=$facility_id and timestampdiff(month, dob,  tbl_accounts_numbers.created_at) between 60 and 719 and  date(tbl_accounts_numbers.created_at)='".$date->date."' having count(patient_id) > 1)");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_reattendance_registers where facility_id=$facility_id and clinic_id = $clinic_id and date ='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_reattendance_registers set male_above_five_under_sixty = male_above_five_under_sixty+".$group[0]->count." where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_reattendance_registers(facility_id,clinic_id,male_above_five_under_sixty,date) select '$facility_id','$clinic_id',".$group[0]->count.",'".$date->date."'");
                }
            }

            $group = DB::select("select count(*) count from tbl_patients where gender='female' and id in (select patient_id from tbl_accounts_numbers where facility_id=$facility_id and timestampdiff(month, dob,  tbl_accounts_numbers.created_at) between 60 and 719 and date(tbl_accounts_numbers.created_at)='".$date->date."' having count(patient_id) > 1)");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_reattendance_registers where facility_id=$facility_id and clinic_id = $clinic_id and date ='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_reattendance_registers set female_above_five_under_sixty = female_above_five_under_sixty+".$group[0]->count." where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_reattendance_registers(facility_id,clinic_id,female_above_five_under_sixty,date) select '$facility_id','$clinic_id',".$group[0]->count.",'".$date->date."'");
                }
            }


            //>=60year
            $group = DB::select("select count(*) count from tbl_patients where gender='male' and id in (select patient_id from tbl_accounts_numbers where facility_id=$facility_id and timestampdiff(month, dob,  tbl_accounts_numbers.created_at) >= 720 and date(tbl_accounts_numbers.created_at)='".$date->date."' having count(patient_id) > 1)");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_reattendance_registers where facility_id=$facility_id and clinic_id = $clinic_id and date ='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_reattendance_registers set male_above_sixty = male_above_sixty+".$group[0]->count." where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_reattendance_registers(facility_id,clinic_id,male_above_sixty,date) select '$facility_id','$clinic_id',".$group[0]->count.",'".$date->date."'");
                }
            }

            $group = DB::select("select count(*) count from tbl_patients where gender='female' and id in (select patient_id from tbl_accounts_numbers where facility_id=$facility_id and timestampdiff(month, dob,  tbl_accounts_numbers.created_at) >= 720 and date(tbl_accounts_numbers.created_at)='".$date->date."' having count(patient_id) > 1)");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_reattendance_registers where facility_id=$facility_id and clinic_id = $clinic_id and date ='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_reattendance_registers set female_above_sixty = female_above_sixty+".$group[0]->count." where facility_id=$facility_id and clinic_id = $clinic_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_reattendance_registers(facility_id,clinic_id,female_above_sixty,date) select '$facility_id','$clinic_id',".$group[0]->count.",'".$date->date."'");
                }
            }

        }


        $dates = DB::select("select distinct admission_date `date` from tbl_admissions where facility_id=$facility_id");
        foreach($dates as $date){
            //<1month
            $group = DB::select("select count(*) count from tbl_patients inner join tbl_admissions on tbl_admissions.facility_id=$facility_id and tbl_patients.id=tbl_admissions.patient_id where gender='male' and timestampdiff(month, dob,  tbl_admissions.admission_date) < 1 and tbl_admissions.admissions_date='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_admission_registers where facility_id=$facility_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_admission_registers set male_under_one_month = male_under_one_month+".$group[0]->count." where facility_id=$facility_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_admission_registers(facility_id,male_under_one_month,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
                }
            }

            $group = DB::select("select count(*) count from tbl_patients inner join tbl_admissions on tbl_admissions.facility_id=$facility_id and tbl_patients.id=tbl_admissions.patient_id where  gender='female' and timestampdiff(month, dob,  tbl_admissions.admission_date) < 1 and tbl_admissions.admissions_date='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_admission_registers where facility_id=$facility_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_admission_registers set female_under_one_month = female_under_one_month+".$group[0]->count." where facility_id=$facility_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_admission_registers(facility_id,female_under_one_month,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
                }
            }


            //<1year
            $group = DB::select("select count(*) count from tbl_patients inner join tbl_admissions on tbl_admissions.facility_id=$facility_id and tbl_patients.id=tbl_admissions.patient_id where  gender='male' and timestampdiff(month, dob,  tbl_admissions.admission_date)  between 1 and 11 and tbl_admissions.admissions_date='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_admission_registers where facility_id=$facility_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_admission_registers set male_under_one_year = male_under_one_year+".$group[0]->count." where facility_id=$facility_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_admission_registers(facility_id,male_under_one_year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
                }
            }

            $group = DB::select("select count(*) count from tbl_patients inner join tbl_admissions on tbl_admissions.facility_id=$facility_id and tbl_patients.id=tbl_admissions.patient_id where  gender='female' and timestampdiff(month, dob,  tbl_admissions.admission_date)  between 1 and 11 and tbl_admissions.admissions_date='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_admission_registers where facility_id=$facility_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_admission_registers set female_under_one_year = female_under_one_year+".$group[0]->count." where facility_id=$facility_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_admission_registers(facility_id,female_under_one_year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
                }
            }


            //<5year
            $group = DB::select("select count(*) count from tbl_patients inner join tbl_admissions on tbl_admissions.facility_id=$facility_id and tbl_patients.id=tbl_admissions.patient_id where  gender='male' and timestampdiff(month, dob,  tbl_admissions.admission_date)  between 12 and 59 and tbl_admissions.admissions_date='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_admission_registers where facility_id=$facility_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_admission_registers set male_under_five_year = male_under_five_year+".$group[0]->count." where facility_id=$facility_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_admission_registers(facility_id,male_under_five_year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
                }
            }

            $group = DB::select("select count(*) count from tbl_patients inner join tbl_admissions on tbl_admissions.facility_id=$facility_id and tbl_patients.id=tbl_admissions.patient_id where  gender='female' and timestampdiff(month, dob,  tbl_admissions.admission_date)  between 12 and 59 and tbl_admissions.admissions_date='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_admission_registers where facility_id=$facility_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_admission_registers set female_under_five_year = female_under_five_year+".$group[0]->count." where facility_id=$facility_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_admission_registers(facility_id,female_under_five_year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
                }
            }


            //<60year
            $group = DB::select("select count(*) count from tbl_patients inner join tbl_admissions on tbl_admissions.facility_id=$facility_id and tbl_patients.id=tbl_admissions.patient_id where  gender='male' and timestampdiff(month, dob,  tbl_admissions.admission_date)  between 60 and 719 and tbl_admissions.admissions_date='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_admission_registers where facility_id=$facility_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_admission_registers set male_above_five_under_sixty = male_above_five_under_sixty+".$group[0]->count." where facility_id=$facility_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_admission_registers(facility_id,male_above_five_under_sixty,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
                }
            }

            $group = DB::select("select count(*) count from tbl_patients inner join tbl_admissions on tbl_admissions.facility_id=$facility_id and tbl_patients.id=tbl_admissions.patient_id where  gender='female' and timestampdiff(month, dob,  tbl_admissions.admission_date)  between 60 and 719 and tbl_admissions.admissions_date='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_admission_registers where facility_id=$facility_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_admission_registers set female_above_five_under_sixty = female_above_five_under_sixty+".$group[0]->count." where facility_id=$facility_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_admission_registers(facility_id,female_above_five_under_sixty,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
                }
            }


            //>=60year
            $group = DB::select("select count(*) count from tbl_patients inner join tbl_admissions on tbl_admissions.facility_id=$facility_id and tbl_patients.id=tbl_admissions.patient_id where  gender='male' and timestampdiff(month, dob,  tbl_admissions.admission_date) >= 720 and tbl_admissions.admissions_date='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_admission_registers where facility_id=$facility_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_admission_registers set male_above_sixty = male_above_sixty+".$group[0]->count." where facility_id=$facility_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_admission_registers(facility_id,male_above_sixty,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
                }
            }

            $group = DB::select("select count(*) count from tbl_patients inner join tbl_admissions on tbl_admissions.facility_id=$facility_id and tbl_patients.id=tbl_admissions.patient_id where  gender='female' and timestampdiff(month, dob,  tbl_admissions.admission_date) >= 720 and tbl_admissions.admissions_date='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_admission_registers where facility_id=$facility_id and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_admission_registers set female_above_sixty = female_above_sixty+".$group[0]->count." where facility_id=$facility_id and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_admission_registers(facility_id,female_above_sixty,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
                }
            }
        }


        $dates = DB::select("select distinct date(tbl_diagnosis_details.created_at) `date` from tbl_diagnosis_details join tbl_diagnoses on tbl_diagnosis_details.diagnosis_id = tbl_diagnoses.id where  tbl_diagnosis_details.facility_id = '$facility_id' and tbl_diagnosis_details.status = 'confirmed' and tbl_diagnoses.admission_id IS NULL");
        foreach($dates as $date){
            //<1month
            $group = DB::select("select count(*) count, opd_mtuha_diagnosis_id from tbl_patients join tbl_diagnoses on tbl_diagnoses.facility_id = '$facility_id' and tbl_patients.id = tbl_diagnoses.patient_id join tbl_diagnosis_details on tbl_diagnosis_details.diagnosis_id = tbl_diagnoses.id join tbl_diagnosis_descriptions on  tbl_diagnosis_descriptions.id = tbl_diagnosis_details.diagnosis_description_id join tbl_opd_mtuha_icd_blocks on tbl_diagnosis_descriptions.code like concat(tbl_opd_mtuha_icd_blocks.icd_block,'%') where gender='male' and tbl_diagnosis_details.status = 'confirmed' and tbl_diagnoses.patient_id not in (select patient_id from tbl_admissions) and timestampdiff(month, dob,  tbl_diagnosis_details.created_at) < 1 and date(tbl_diagnosis_details.created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_opd_diseases_registers where opd_mtuha_diagnosis_id = ".$group[0]->opd_mtuha_diagnosis_id." and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_opd_diseases_registers set male_under_one_month = male_under_one_month+".$group[0]->count." where opd_mtuha_diagnosis_id = ".$group[0]->opd_mtuha_diagnosis_id." and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_opd_diseases_registers(facility_id,opd_mtuha_diagnosis_id, male_under_one_month,date) select '$facility_id','".$group[0]->opd_mtuha_diagnosis_id."',".$group[0]->count.",'".$date->date."'");
                }
            }

            $group = DB::select("select count(*) count, opd_mtuha_diagnosis_id from tbl_patients join tbl_diagnoses on tbl_diagnoses.facility_id = '$facility_id' and tbl_patients.id = tbl_diagnoses.patient_id join tbl_diagnosis_details on tbl_diagnosis_details.diagnosis_id = tbl_diagnoses.id join tbl_diagnosis_descriptions on  tbl_diagnosis_descriptions.id = tbl_diagnosis_details.diagnosis_description_id join tbl_opd_mtuha_icd_blocks on tbl_diagnosis_descriptions.code like concat(tbl_opd_mtuha_icd_blocks.icd_block,'%')  where gender='female' and tbl_diagnosis_details.status = 'confirmed' and tbl_diagnoses.patient_id not in (select patient_id from tbl_admissions) and timestampdiff(month, dob,  tbl_diagnosis_details.created_at) < 1 and date(tbl_diagnosis_details.created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_opd_diseases_registers where opd_mtuha_diagnosis_id = ".$group[0]->opd_mtuha_diagnosis_id." and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_opd_diseases_registers set female_under_one_month = female_under_one_month+".$group[0]->count." where opd_mtuha_diagnosis_id = ".$group[0]->opd_mtuha_diagnosis_id." and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_opd_diseases_registers(facility_id,opd_mtuha_diagnosis_id, female_under_one_month,date) select '$facility_id','".$group[0]->opd_mtuha_diagnosis_id."',".$group[0]->count.",'".$date->date."'");
                }
            }


            //<1year
            $group = DB::select("select count(*) count, opd_mtuha_diagnosis_id from tbl_patients join tbl_diagnoses on tbl_diagnoses.facility_id = '$facility_id' and tbl_patients.id = tbl_diagnoses.patient_id join tbl_diagnosis_details on tbl_diagnosis_details.diagnosis_id = tbl_diagnoses.id join tbl_diagnosis_descriptions on  tbl_diagnosis_descriptions.id = tbl_diagnosis_details.diagnosis_description_id join tbl_opd_mtuha_icd_blocks on tbl_diagnosis_descriptions.code like concat(tbl_opd_mtuha_icd_blocks.icd_block,'%')   where gender='male' and tbl_diagnosis_details.status = 'confirmed' and tbl_diagnoses.patient_id not in (select patient_id from tbl_admissions) and timestampdiff(month, dob,  tbl_diagnosis_details.created_at) between 1 and 11 and date(tbl_diagnosis_details.created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_opd_diseases_registers where opd_mtuha_diagnosis_id = ".$group[0]->opd_mtuha_diagnosis_id." and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_opd_diseases_registers set male_under_one_month = male_under_one_month+".$group[0]->count." where opd_mtuha_diagnosis_id = ".$group[0]->opd_mtuha_diagnosis_id." and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_opd_diseases_registers(facility_id,opd_mtuha_diagnosis_id, male_under_one_month,date) select '$facility_id','".$group[0]->opd_mtuha_diagnosis_id."',".$group[0]->count.",'".$date->date."'");
                }
            }
			
			$group = DB::select("select count(*) count, opd_mtuha_diagnosis_id from tbl_patients join tbl_diagnoses on tbl_diagnoses.facility_id = '$facility_id' and tbl_patients.id = tbl_diagnoses.patient_id join tbl_diagnosis_details on tbl_diagnosis_details.diagnosis_id = tbl_diagnoses.id join tbl_diagnosis_descriptions on  tbl_diagnosis_descriptions.id = tbl_diagnosis_details.diagnosis_description_id join tbl_opd_mtuha_icd_blocks on tbl_diagnosis_descriptions.code like concat(tbl_opd_mtuha_icd_blocks.icd_block,'%')   where gender='female' and tbl_diagnosis_details.status = 'confirmed' and tbl_diagnoses.patient_id not in (select patient_id from tbl_admissions) and timestampdiff(month, dob,  tbl_diagnosis_details.created_at) between 1 and 11 and date(tbl_diagnosis_details.created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_opd_diseases_registers where opd_mtuha_diagnosis_id = ".$group[0]->opd_mtuha_diagnosis_id." and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_opd_diseases_registers set female_under_one_month = female_under_one_month+".$group[0]->count." where opd_mtuha_diagnosis_id = ".$group[0]->opd_mtuha_diagnosis_id." and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_opd_diseases_registers(facility_id,opd_mtuha_diagnosis_id, female_under_one_month,date) select '$facility_id','".$group[0]->opd_mtuha_diagnosis_id."',".$group[0]->count.",'".$date->date."'");
                }
            }


            //<5year
            $group = DB::select("select count(*) count, opd_mtuha_diagnosis_id from tbl_patients join tbl_diagnoses on tbl_diagnoses.facility_id = '$facility_id' and tbl_patients.id = tbl_diagnoses.patient_id join tbl_diagnosis_details on tbl_diagnosis_details.diagnosis_id = tbl_diagnoses.id join tbl_diagnosis_descriptions on  tbl_diagnosis_descriptions.id = tbl_diagnosis_details.diagnosis_description_id join tbl_opd_mtuha_icd_blocks on tbl_diagnosis_descriptions.code like concat(tbl_opd_mtuha_icd_blocks.icd_block,'%')   where gender='male' and tbl_diagnosis_details.status = 'confirmed' and tbl_diagnoses.patient_id not in (select patient_id from tbl_admissions) and timestampdiff(month, dob,  tbl_diagnosis_details.created_at) between 12 and 59 and date(tbl_diagnosis_details.created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_opd_diseases_registers where opd_mtuha_diagnosis_id = ".$group[0]->opd_mtuha_diagnosis_id." and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_opd_diseases_registers set male_under_one_month = male_under_one_month+".$group[0]->count." where opd_mtuha_diagnosis_id = ".$group[0]->opd_mtuha_diagnosis_id." and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_opd_diseases_registers(facility_id,opd_mtuha_diagnosis_id, male_under_one_month,date) select '$facility_id','".$group[0]->opd_mtuha_diagnosis_id."',".$group[0]->count.",'".$date->date."'");
                }
            }
			
			$group = DB::select("select count(*) count, opd_mtuha_diagnosis_id from tbl_patients join tbl_diagnoses on tbl_diagnoses.facility_id = '$facility_id' and tbl_patients.id = tbl_diagnoses.patient_id join tbl_diagnosis_details on tbl_diagnosis_details.diagnosis_id = tbl_diagnoses.id join tbl_diagnosis_descriptions on  tbl_diagnosis_descriptions.id = tbl_diagnosis_details.diagnosis_description_id join tbl_opd_mtuha_icd_blocks on tbl_diagnosis_descriptions.code like concat(tbl_opd_mtuha_icd_blocks.icd_block,'%')   where gender='female' and tbl_diagnosis_details.status = 'confirmed' and tbl_diagnoses.patient_id not in (select patient_id from tbl_admissions) and timestampdiff(month, dob,  tbl_diagnosis_details.created_at) between 12 and 59 and date(tbl_diagnosis_details.created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_opd_diseases_registers where opd_mtuha_diagnosis_id = ".$group[0]->opd_mtuha_diagnosis_id." and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_opd_diseases_registers set female_under_one_month = female_under_one_month+".$group[0]->count." where opd_mtuha_diagnosis_id = ".$group[0]->opd_mtuha_diagnosis_id." and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_opd_diseases_registers(facility_id,opd_mtuha_diagnosis_id, female_under_one_month,date) select '$facility_id','".$group[0]->opd_mtuha_diagnosis_id."',".$group[0]->count.",'".$date->date."'");
                }
            }


            //<60year
            $group = DB::select("select count(*) count, opd_mtuha_diagnosis_id from tbl_patients join tbl_diagnoses on tbl_diagnoses.facility_id = '$facility_id' and tbl_patients.id = tbl_diagnoses.patient_id join tbl_diagnosis_details on tbl_diagnosis_details.diagnosis_id = tbl_diagnoses.id join tbl_diagnosis_descriptions on  tbl_diagnosis_descriptions.id = tbl_diagnosis_details.diagnosis_description_id join tbl_opd_mtuha_icd_blocks on tbl_diagnosis_descriptions.code like concat(tbl_opd_mtuha_icd_blocks.icd_block,'%')   where gender='male' and tbl_diagnosis_details.status = 'confirmed' and tbl_diagnoses.patient_id not in (select patient_id from tbl_admissions) and timestampdiff(month, dob,  tbl_diagnosis_details.created_at) between 60 and 719 and date(tbl_diagnosis_details.created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_opd_diseases_registers where opd_mtuha_diagnosis_id = ".$group[0]->opd_mtuha_diagnosis_id." and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_opd_diseases_registers set male_under_one_month = male_under_one_month+".$group[0]->count." where opd_mtuha_diagnosis_id = ".$group[0]->opd_mtuha_diagnosis_id." and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_opd_diseases_registers(facility_id,opd_mtuha_diagnosis_id, male_under_one_month,date) select '$facility_id','".$group[0]->opd_mtuha_diagnosis_id."',".$group[0]->count.",'".$date->date."'");
                }
            }
			
			$group = DB::select("select count(*) count, opd_mtuha_diagnosis_id from tbl_patients join tbl_diagnoses on tbl_diagnoses.facility_id = '$facility_id' and tbl_patients.id = tbl_diagnoses.patient_id join tbl_diagnosis_details on tbl_diagnosis_details.diagnosis_id = tbl_diagnoses.id join tbl_diagnosis_descriptions on  tbl_diagnosis_descriptions.id = tbl_diagnosis_details.diagnosis_description_id join tbl_opd_mtuha_icd_blocks on tbl_diagnosis_descriptions.code like concat(tbl_opd_mtuha_icd_blocks.icd_block,'%')   where gender='female' and tbl_diagnosis_details.status = 'confirmed' and tbl_diagnoses.patient_id not in (select patient_id from tbl_admissions) and timestampdiff(month, dob,  tbl_diagnosis_details.created_at) between 60 and 719 and date(tbl_diagnosis_details.created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_opd_diseases_registers where opd_mtuha_diagnosis_id = ".$group[0]->opd_mtuha_diagnosis_id." and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_opd_diseases_registers set female_under_one_month = female_under_one_month+".$group[0]->count." where opd_mtuha_diagnosis_id = ".$group[0]->opd_mtuha_diagnosis_id." and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_opd_diseases_registers(facility_id,opd_mtuha_diagnosis_id, female_under_one_month,date) select '$facility_id','".$group[0]->opd_mtuha_diagnosis_id."',".$group[0]->count.",'".$date->date."'");
                }
            }


            //>=60year
            $group = DB::select("select count(*) count, opd_mtuha_diagnosis_id from tbl_patients join tbl_diagnoses on tbl_diagnoses.facility_id = '$facility_id' and tbl_patients.id = tbl_diagnoses.patient_id join tbl_diagnosis_details on tbl_diagnosis_details.diagnosis_id = tbl_diagnoses.id join tbl_diagnosis_descriptions on  tbl_diagnosis_descriptions.id = tbl_diagnosis_details.diagnosis_description_id join tbl_opd_mtuha_icd_blocks on tbl_diagnosis_descriptions.code like concat(tbl_opd_mtuha_icd_blocks.icd_block,'%')   where gender='male' and tbl_diagnosis_details.status = 'confirmed' and tbl_diagnoses.patient_id not in (select patient_id from tbl_admissions) and timestampdiff(month, dob,  tbl_diagnosis_details.created_at) >= 720 and date(tbl_diagnosis_details.created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_opd_diseases_registers where opd_mtuha_diagnosis_id = ".$group[0]->opd_mtuha_diagnosis_id." and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_opd_diseases_registers set male_under_one_month = male_under_one_month+".$group[0]->count." where opd_mtuha_diagnosis_id = ".$group[0]->opd_mtuha_diagnosis_id." and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_opd_diseases_registers(facility_id,opd_mtuha_diagnosis_id, male_under_one_month,date) select '$facility_id','".$group[0]->opd_mtuha_diagnosis_id."',".$group[0]->count.",'".$date->date."'");
                }
            }
			
			$group = DB::select("select count(*) count, opd_mtuha_diagnosis_id from tbl_patients join tbl_diagnoses on tbl_diagnoses.facility_id = '$facility_id' and tbl_patients.id = tbl_diagnoses.patient_id join tbl_diagnosis_details on tbl_diagnosis_details.diagnosis_id = tbl_diagnoses.id join tbl_diagnosis_descriptions on  tbl_diagnosis_descriptions.id = tbl_diagnosis_details.diagnosis_description_id join tbl_opd_mtuha_icd_blocks on tbl_diagnosis_descriptions.code like concat(tbl_opd_mtuha_icd_blocks.icd_block,'%')   where gender='female' and tbl_diagnosis_details.status = 'confirmed' and tbl_diagnoses.patient_id not in (select patient_id from tbl_admissions) and timestampdiff(month, dob,  tbl_diagnosis_details.created_at) >= 720 and date(tbl_diagnosis_details.created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_opd_diseases_registers where opd_mtuha_diagnosis_id = ".$group[0]->opd_mtuha_diagnosis_id." and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_opd_diseases_registers set female_under_one_month = female_under_one_month+".$group[0]->count." where opd_mtuha_diagnosis_id = ".$group[0]->opd_mtuha_diagnosis_id." and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_opd_diseases_registers(facility_id,opd_mtuha_diagnosis_id, female_under_one_month,date) select '$facility_id','".$group[0]->opd_mtuha_diagnosis_id."',".$group[0]->count.",'".$date->date."'");
                }
            }

        }

		$dates = DB::select("select distinct date(tbl_diagnosis_details.created_at) `date` from tbl_diagnosis_details join tbl_diagnoses on tbl_diagnosis_details.facility_id ='$facility_id' and tbl_diagnosis_details.diagnosis_id = tbl_diagnoses.id  where  tbl_diagnosis_details.status = 'confirmed' and tbl_diagnoses.admission_id IS NOT NULL");
        foreach($dates as $date){
            //<1month
            $group = DB::select("select count(*) count, ipd_mtuha_diagnosis_id from tbl_patients join tbl_diagnoses on tbl_diagnoses.facility_id = '$facility_id' and tbl_patients.id = tbl_diagnoses.patient_id join tbl_diagnosis_details on tbl_diagnosis_details.diagnosis_id = tbl_diagnoses.id join tbl_diagnosis_descriptions on  tbl_diagnosis_descriptions.id = tbl_diagnosis_details.diagnosis_description_id join tbl_ipd_mtuha_icd_blocks on tbl_diagnosis_descriptions.code like concat(tbl_ipd_mtuha_icd_blocks.icd_block,'%') where gender='male' and tbl_diagnosis_details.status = 'confirmed' and tbl_diagnoses.patient_id in (select  patient_id from tbl_admissions) and timestampdiff(month, dob,  tbl_diagnosis_details.created_at) < 1 and date(tbl_diagnosis_details.created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_ipd_diseases_registers where ipd_mtuha_diagnosis_id = ".$group[0]->ipd_mtuha_diagnosis_id." and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_ipd_diseases_registers set male_under_one_month = male_under_one_month+".$group[0]->count." where ipd_mtuha_diagnosis_id = ".$group[0]->ipd_mtuha_diagnosis_id." and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_ipd_diseases_registers(facility_id,ipd_mtuha_diagnosis_id, male_under_one_month,date) select '$facility_id','".$group[0]->ipd_mtuha_diagnosis_id."',".$group[0]->count.",'".$date->date."'");
                }
            }

            $group = DB::select("select count(*) count, ipd_mtuha_diagnosis_id from tbl_patients join tbl_diagnoses on tbl_diagnoses.facility_id = '$facility_id' and tbl_patients.id = tbl_diagnoses.patient_id join tbl_diagnosis_details on tbl_diagnosis_details.diagnosis_id = tbl_diagnoses.id join tbl_diagnosis_descriptions on  tbl_diagnosis_descriptions.id = tbl_diagnosis_details.diagnosis_description_id join tbl_ipd_mtuha_icd_blocks on tbl_diagnosis_descriptions.code like concat(tbl_ipd_mtuha_icd_blocks.icd_block,'%')  where gender='female' and tbl_diagnosis_details.status = 'confirmed' and tbl_diagnoses.patient_id in (select  patient_id from tbl_admissions) and timestampdiff(month, dob,  tbl_diagnosis_details.created_at) < 1 and date(tbl_diagnosis_details.created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_ipd_diseases_registers where ipd_mtuha_diagnosis_id = ".$group[0]->ipd_mtuha_diagnosis_id." and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_ipd_diseases_registers set female_under_one_month = female_under_one_month+".$group[0]->count." where opd_mtuha_diagnosis_id = ".$group[0]->ipd_mtuha_diagnosis_id." and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_ipd_diseases_registers(facility_id,ipd_mtuha_diagnosis_id, female_under_one_month,date) select '$facility_id','".$group[0]->ipd_mtuha_diagnosis_id."',".$group[0]->count.",'".$date->date."'");
                }
            }


            //<1year
            $group = DB::select("select count(*) count, ipd_mtuha_diagnosis_id from tbl_patients join tbl_diagnoses on tbl_diagnoses.facility_id = '$facility_id' and tbl_patients.id = tbl_diagnoses.patient_id join tbl_diagnosis_details on tbl_diagnosis_details.diagnosis_id = tbl_diagnoses.id join tbl_diagnosis_descriptions on  tbl_diagnosis_descriptions.id = tbl_diagnosis_details.diagnosis_description_id join tbl_ipd_mtuha_icd_blocks on tbl_diagnosis_descriptions.code like concat(tbl_ipd_mtuha_icd_blocks.icd_block,'%')  where gender='male' and tbl_diagnosis_details.status = 'confirmed' and tbl_diagnoses.patient_id in (select  patient_id from tbl_admissions) and timestampdiff(month, dob,  tbl_diagnosis_details.created_at) between 1 and 11 and date(tbl_diagnosis_details.created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_ipd_diseases_registers where ipd_mtuha_diagnosis_id = ".$group[0]->ipd_mtuha_diagnosis_id." and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_ipd_diseases_registers set male_under_one_month = male_under_one_month+".$group[0]->count." where ipd_mtuha_diagnosis_id = ".$group[0]->ipd_mtuha_diagnosis_id." and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_ipd_diseases_registers(facility_id,ipd_mtuha_diagnosis_id, male_under_one_month,date) select '$facility_id','".$group[0]->ipd_mtuha_diagnosis_id."',".$group[0]->count.",'".$date->date."'");
                }
            }
			
			$group = DB::select("select count(*) count, ipd_mtuha_diagnosis_id from tbl_patients join tbl_diagnoses on tbl_diagnoses.facility_id = '$facility_id' and tbl_patients.id = tbl_diagnoses.patient_id join tbl_diagnosis_details on tbl_diagnosis_details.diagnosis_id = tbl_diagnoses.id join tbl_diagnosis_descriptions on  tbl_diagnosis_descriptions.id = tbl_diagnosis_details.diagnosis_description_id join tbl_ipd_mtuha_icd_blocks on tbl_diagnosis_descriptions.code like concat(tbl_ipd_mtuha_icd_blocks.icd_block,'%')  where gender='female' and tbl_diagnosis_details.status = 'confirmed' and tbl_diagnoses.patient_id in (select  patient_id from tbl_admissions) and timestampdiff(month, dob,  tbl_diagnosis_details.created_at) between 1 and 11 and date(tbl_diagnosis_details.created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_ipd_diseases_registers where ipd_mtuha_diagnosis_id = ".$group[0]->ipd_mtuha_diagnosis_id." and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_ipd_diseases_registers set female_under_one_month = female_under_one_month+".$group[0]->count." where ipd_mtuha_diagnosis_id = ".$group[0]->ipd_mtuha_diagnosis_id." and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_ipd_diseases_registers(facility_id,ipd_mtuha_diagnosis_id, female_under_one_month,date) select '$facility_id','".$group[0]->ipd_mtuha_diagnosis_id."',".$group[0]->count.",'".$date->date."'");
                }
            }


            //<5year
            $group = DB::select("select count(*) count, ipd_mtuha_diagnosis_id from tbl_patients join tbl_diagnoses on tbl_diagnoses.facility_id = '$facility_id' and tbl_patients.id = tbl_diagnoses.patient_id join tbl_diagnosis_details on tbl_diagnosis_details.diagnosis_id = tbl_diagnoses.id join tbl_diagnosis_descriptions on  tbl_diagnosis_descriptions.id = tbl_diagnosis_details.diagnosis_description_id join tbl_ipd_mtuha_icd_blocks on tbl_diagnosis_descriptions.code like concat(tbl_ipd_mtuha_icd_blocks.icd_block,'%')  where gender='male' and tbl_diagnosis_details.status = 'confirmed' and tbl_diagnoses.patient_id in (select  patient_id from tbl_admissions) and timestampdiff(month, dob,  tbl_diagnosis_details.created_at) between 12 and 59 and date(tbl_diagnosis_details.created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_ipd_diseases_registers where opd_mtuha_diagnosis_id = ".$group[0]->ipd_mtuha_diagnosis_id." and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_ipd_diseases_registers set male_under_one_month = male_under_one_month+".$group[0]->count." where ipd_mtuha_diagnosis_id = ".$group[0]->ipd_mtuha_diagnosis_id." and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_ipd_diseases_registers(facility_id,ipd_mtuha_diagnosis_id, male_under_one_month,date) select '$facility_id','".$group[0]->ipd_mtuha_diagnosis_id."',".$group[0]->count.",'".$date->date."'");
                }
            }
			
			$group = DB::select("select count(*) count, ipd_mtuha_diagnosis_id from tbl_patients join tbl_diagnoses on tbl_diagnoses.facility_id = '$facility_id' and tbl_patients.id = tbl_diagnoses.patient_id join tbl_diagnosis_details on tbl_diagnosis_details.diagnosis_id = tbl_diagnoses.id join tbl_diagnosis_descriptions on  tbl_diagnosis_descriptions.id = tbl_diagnosis_details.diagnosis_description_id join tbl_ipd_mtuha_icd_blocks on tbl_diagnosis_descriptions.code like concat(tbl_ipd_mtuha_icd_blocks.icd_block,'%')  where gender='female' and tbl_diagnosis_details.status = 'confirmed' and tbl_diagnoses.patient_id in (select  patient_id from tbl_admissions) and timestampdiff(month, dob,  tbl_diagnosis_details.created_at) between 12 and 59 and date(tbl_diagnosis_details.created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_ipd_diseases_registers where ipd_mtuha_diagnosis_id = ".$group[0]->ipd_mtuha_diagnosis_id." and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_ipd_diseases_registers set female_under_one_month = female_under_one_month+".$group[0]->count." where ipd_mtuha_diagnosis_id = ".$group[0]->ipd_mtuha_diagnosis_id." and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_ipd_diseases_registers(facility_id,ipd_mtuha_diagnosis_id, female_under_one_month,date) select '$facility_id','".$group[0]->ipd_mtuha_diagnosis_id."',".$group[0]->count.",'".$date->date."'");
                }
            }


            //<60year
            $group = DB::select("select count(*) count, ipd_mtuha_diagnosis_id from tbl_patients join tbl_diagnoses on tbl_diagnoses.facility_id = '$facility_id' and tbl_patients.id = tbl_diagnoses.patient_id join tbl_diagnosis_details on tbl_diagnosis_details.diagnosis_id = tbl_diagnoses.id join tbl_diagnosis_descriptions on  tbl_diagnosis_descriptions.id = tbl_diagnosis_details.diagnosis_description_id join tbl_ipd_mtuha_icd_blocks on tbl_diagnosis_descriptions.code like concat(tbl_ipd_mtuha_icd_blocks.icd_block,'%')  where gender='male' and tbl_diagnosis_details.status = 'confirmed' and tbl_diagnoses.patient_id in (select  patient_id from tbl_admissions) and timestampdiff(month, dob,  tbl_diagnosis_details.created_at) between 60 and 719 and date(tbl_diagnosis_details.created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_ipd_diseases_registers where ipd_mtuha_diagnosis_id = ".$group[0]->ipd_mtuha_diagnosis_id." and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_ipd_diseases_registers set male_under_one_month = male_under_one_month+".$group[0]->count." where ipd_mtuha_diagnosis_id = ".$group[0]->ipd_mtuha_diagnosis_id." and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_ipd_diseases_registers(facility_id,ipd_mtuha_diagnosis_id, male_under_one_month,date) select '$facility_id','".$group[0]->ipd_mtuha_diagnosis_id."',".$group[0]->count.",'".$date->date."'");
                }
            }
			
			$group = DB::select("select count(*) count, ipd_mtuha_diagnosis_id from tbl_patients join tbl_diagnoses on tbl_diagnoses.facility_id = '$facility_id' and tbl_patients.id = tbl_diagnoses.patient_id join tbl_diagnosis_details on tbl_diagnosis_details.diagnosis_id = tbl_diagnoses.id join tbl_diagnosis_descriptions on  tbl_diagnosis_descriptions.id = tbl_diagnosis_details.diagnosis_description_id join tbl_ipd_mtuha_icd_blocks on tbl_diagnosis_descriptions.code like concat(tbl_ipd_mtuha_icd_blocks.icd_block,'%')  where gender='female' and tbl_diagnosis_details.status = 'confirmed' and tbl_diagnoses.patient_id in (select  patient_id from tbl_admissions) and timestampdiff(month, dob,  tbl_diagnosis_details.created_at) between 60 and 719 and date(tbl_diagnosis_details.created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_ipd_diseases_registers where ipd_mtuha_diagnosis_id = ".$group[0]->ipd_mtuha_diagnosis_id." and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_ipd_diseases_registers set female_under_one_month = female_under_one_month+".$group[0]->count." where ipd_mtuha_diagnosis_id = ".$group[0]->ipd_mtuha_diagnosis_id." and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_ipd_diseases_registers(facility_id,ipd_mtuha_diagnosis_id, female_under_one_month,date) select '$facility_id','".$group[0]->ipd_mtuha_diagnosis_id."',".$group[0]->count.",'".$date->date."'");
                }
            }


            //>=60year
            $group = DB::select("select count(*) count, ipd_mtuha_diagnosis_id from tbl_patients join tbl_diagnoses on tbl_diagnoses.facility_id = '$facility_id' and tbl_patients.id = tbl_diagnoses.patient_id join tbl_diagnosis_details on tbl_diagnosis_details.diagnosis_id = tbl_diagnoses.id join tbl_diagnosis_descriptions on  tbl_diagnosis_descriptions.id = tbl_diagnosis_details.diagnosis_description_id join tbl_ipd_mtuha_icd_blocks on tbl_diagnosis_descriptions.code like concat(tbl_ipd_mtuha_icd_blocks.icd_block,'%')  where gender='male' and tbl_diagnosis_details.status = 'confirmed' and tbl_diagnoses.patient_id in (select  patient_id from tbl_admissions) and timestampdiff(month, dob,  tbl_diagnosis_details.created_at) >= 720 and date(tbl_diagnosis_details.created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_ipd_diseases_registers where ipd_mtuha_diagnosis_id = ".$group[0]->ipd_mtuha_diagnosis_id." and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_ipd_diseases_registers set male_under_one_month = male_under_one_month+".$group[0]->count." where ipd_mtuha_diagnosis_id = ".$group[0]->ipd_mtuha_diagnosis_id." and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_ipd_diseases_registers(facility_id,ipd_mtuha_diagnosis_id, male_under_one_month,date) select '$facility_id','".$group[0]->opd_mtuha_diagnosis_id."',".$group[0]->count.",'".$date->date."'");
                }
            }
			
			$group = DB::select("select count(*) count, ipd_mtuha_diagnosis_id from tbl_patients join tbl_diagnoses on tbl_diagnoses.facility_id = '$facility_id' and tbl_patients.id = tbl_diagnoses.patient_id join tbl_diagnosis_details on tbl_diagnosis_details.diagnosis_id = tbl_diagnoses.id join tbl_diagnosis_descriptions on  tbl_diagnosis_descriptions.id = tbl_diagnosis_details.diagnosis_description_id join tbl_ipd_mtuha_icd_blocks on tbl_diagnosis_descriptions.code like concat(tbl_ipd_mtuha_icd_blocks.icd_block,'%')  where gender='female' and tbl_diagnosis_details.status = 'confirmed' and tbl_diagnoses.patient_id in (select  patient_id from tbl_admissions) and timestampdiff(month, dob,  tbl_diagnosis_details.created_at) >= 720 and date(tbl_diagnosis_details.created_at)='".$date->date."'");
            if(is_array($group) && $group[0]->count > 0){
                $todayCounts = DB::select("select count(*) count from tbl_ipd_diseases_registers where ipd_mtuha_diagnosis_id = ".$group[0]->ipd_mtuha_diagnosis_id." and date='".$date->date."'");
                if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
                    DB::statement("update tbl_ipd_diseases_registers set female_under_one_month = female_under_one_month+".$group[0]->count." where ipd_mtuha_diagnosis_id = ".$group[0]->ipd_mtuha_diagnosis_id." and date='".$date->date."'");
                }else{
                    DB::statement("insert into tbl_ipd_diseases_registers(facility_id,ipd_mtuha_diagnosis_id, female_under_one_month,date) select '$facility_id','".$group[0]->ipd_mtuha_diagnosis_id."',".$group[0]->count.",'".$date->date."'");
                }
            }
        }
		
		

        //sum all total fields
        DB::statement("update tbl_opd_diseases_registers set total_under_one_month = male_under_one_month+female_under_one_month where facility_id='$facility_id'");
        DB::statement("update tbl_opd_diseases_registers set total_under_one_year = male_under_one_year+female_under_one_yearwhere facility_id='$facility_id'");
        DB::statement("update tbl_opd_diseases_registers set total_under_five_year = male_under_five_year+female_under_five_yearwhere facility_id='$facility_id'");
        DB::statement("update tbl_opd_diseases_registers set total_above_five_under_sixty = male_above_five_under_sixty+female_above_five_under_sixtywhere facility_id='$facility_id'");
        DB::statement("update tbl_opd_diseases_registers set total_above_sixty = male_above_sixty+female_above_sixtywhere facility_id='$facility_id'");
        DB::statement("update tbl_opd_diseases_registers set total_male = male_under_one_month+male_under_one_year+male_under_five_year+male_above_five_under_sixty+male_above_sixtywhere facility_id='$facility_id'");
        DB::statement("update tbl_opd_diseases_registers set total_female=female_under_one_month+female_under_one_year+female_under_five_year+female_above_five_under_sixty+female_above_sixtywhere facility_id='$facility_id'");
        DB::statement("update tbl_opd_diseases_registers set grand_total = total_male+total_femalewhere facility_id='$facility_id'");

        DB::statement("update tbl_ipd_diseases_registers set total_under_one_month = male_under_one_month+female_under_one_monthwhere facility_id='$facility_id'");
        DB::statement("update tbl_ipd_diseases_registers set total_under_one_year = male_under_one_year+female_under_one_yearwhere facility_id='$facility_id'");
        DB::statement("update tbl_ipd_diseases_registers set total_under_five_year = male_under_five_year+female_under_five_yearwhere facility_id='$facility_id'");
        DB::statement("update tbl_ipd_diseases_registers set total_above_five_under_sixty = male_above_five_under_sixty+female_above_five_under_sixtywhere facility_id='$facility_id'");
        DB::statement("update tbl_ipd_diseases_registers set total_above_sixty = male_above_sixty+female_above_sixtywhere facility_id='$facility_id'");
        DB::statement("update tbl_ipd_diseases_registers set total_male = male_under_one_month+male_under_one_year+male_under_five_year+male_above_five_under_sixty+male_above_sixtywhere facility_id='$facility_id'");
        DB::statement("update tbl_ipd_diseases_registers set total_female=female_under_one_month+female_under_one_year+female_under_five_year+female_above_five_under_sixty+female_above_sixtywhere facility_id='$facility_id'");

        DB::statement("update tbl_newattendance_registers set total_under_one_month = male_under_one_month+female_under_one_monthwhere facility_id='$facility_id'");
        DB::statement("update tbl_newattendance_registers set total_under_one_year = male_under_one_year+female_under_one_yearwhere facility_id='$facility_id'");
        DB::statement("update tbl_newattendance_registers set total_under_five_year = male_under_five_year+female_under_five_yearwhere facility_id='$facility_id'");
        DB::statement("update tbl_newattendance_registers set total_above_five_under_sixty = male_above_five_under_sixty+female_above_five_under_sixtywhere facility_id='$facility_id'");
        DB::statement("update tbl_newattendance_registers set total_above_sixty = male_above_sixty+female_above_sixtywhere facility_id='$facility_id'");
        DB::statement("update tbl_newattendance_registers set total_male = male_under_one_month+male_under_one_year+male_under_five_year+male_above_five_under_sixty+male_above_sixtywhere facility_id='$facility_id'");
        DB::statement("update tbl_newattendance_registers set total_female=female_under_one_month+female_under_one_year+female_under_five_year+female_above_five_under_sixty+female_above_sixtywhere facility_id='$facility_id'");
        DB::statement("update tbl_newattendance_registers set grand_total = total_male+total_femalewhere facility_id='$facility_id'");


        DB::statement("update tbl_reattendance_registers set total_under_one_month = male_under_one_month+female_under_one_monthwhere facility_id='$facility_id'");
        DB::statement("update tbl_reattendance_registers set total_under_one_year = male_under_one_year+female_under_one_yearwhere facility_id='$facility_id'");
        DB::statement("update tbl_reattendance_registers set total_under_five_year = male_under_five_year+female_under_five_yearwhere facility_id='$facility_id'");
        DB::statement("update tbl_reattendance_registers set total_above_five_under_sixty = male_above_five_under_sixty+female_above_five_under_sixtywhere facility_id='$facility_id'");
        DB::statement("update tbl_reattendance_registers set total_above_sixty = male_above_sixty+female_above_sixtywhere facility_id='$facility_id'");
        DB::statement("update tbl_reattendance_registers set total_male = male_under_one_month+male_under_one_year+male_under_five_year+male_above_five_under_sixty+male_above_sixtywhere facility_id='$facility_id'");
        DB::statement("update tbl_reattendance_registers set total_female=female_under_one_month+female_under_one_year+female_under_five_year+female_above_five_under_sixty+female_above_sixtywhere facility_id='$facility_id'");
        DB::statement("update tbl_reattendance_registers set grand_total = total_male+total_femalewhere facility_id='$facility_id'");
        DB::statement("update tbl_ipd_diseases_registers set grand_total = total_male+total_femalewhere facility_id='$facility_id'");

        DB::statement("update tbl_admission_registers set total_under_one_month = male_under_one_month+female_under_one_monthwhere facility_id='$facility_id'");
        DB::statement("update tbl_admission_registers set total_under_one_year = male_under_one_year+female_under_one_yearwhere facility_id='$facility_id'");
        DB::statement("update tbl_admission_registers set total_under_five_year = male_under_five_year+female_under_five_yearwhere facility_id='$facility_id'");
        DB::statement("update tbl_admission_registers set total_above_five_under_sixty = male_above_five_under_sixty+female_above_five_under_sixtywhere facility_id='$facility_id'");
        DB::statement("update tbl_admission_registers set total_above_sixty = male_above_sixty+female_above_sixtywhere facility_id='$facility_id'");
        DB::statement("update tbl_admission_registers set total_male = male_under_one_month+male_under_one_year+male_under_five_year+male_above_five_under_sixty+male_above_sixtywhere facility_id='$facility_id'");
        DB::statement("update tbl_admission_registers set total_female=female_under_one_month+female_under_one_year+female_under_five_year+female_above_five_under_sixty+female_above_sixty");
        DB::statement("update tbl_admission_registers set grand_total = total_male+total_femalewhere facility_id='$facility_id'");

        DB::statement("update tbl_outgoing_referral_register set total_under_one_month = male_under_one_month+female_under_one_monthwhere facility_id='$facility_id'");
        DB::statement("update tbl_outgoing_referral_register set total_under_one_year = male_under_one_year+female_under_one_yearwhere facility_id='$facility_id'");
        DB::statement("update tbl_outgoing_referral_register set total_under_five_year = male_under_five_year+female_under_five_yearwhere facility_id='$facility_id'");
        DB::statement("update tbl_outgoing_referral_register set total_above_five_under_sixty = male_above_five_under_sixty+female_above_five_under_sixtywhere facility_id='$facility_id'");
        DB::statement("update tbl_outgoing_referral_register set total_above_sixty = male_above_sixty+female_above_sixtywhere facility_id='$facility_id'");
        DB::statement("update tbl_outgoing_referral_register set total_male = male_under_one_month+male_under_one_year+male_under_five_year+male_above_five_under_sixty+male_above_sixtywhere facility_id='$facility_id'");
        DB::statement("update tbl_outgoing_referral_register set total_female=female_under_one_month+female_under_one_year+female_under_five_year+female_above_five_under_sixty+female_above_sixtywhere facility_id='$facility_id'");
        DB::statement("update tbl_outgoing_referral_register set grand_total = total_male+total_femalewhere facility_id='$facility_id'");
    }
	
	public function talliedPatient(Request $request){
	DB::statement("update tbl_accounts_numbers set tallied =1 where patient_id ='".$request['patient_id']."'");
	}
}
