<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

 


class reportGenerator extends Controller
{
    public function countNewPatient(Request $request){
        //find age in months
		$dob = explode('-',$request['dob']);
		$dob = $dob[2]."-".$dob[1]."-".$dob[0];
		$dob = new \DateTime($dob);
		$interval = (new \DateTime())->diff($dob);
		$age_group = $interval->m + $interval->y*12;
		$gender = $request['gender'];

		if($age_group <= 0){
			$age_group = $gender == "MALE" ? "male_under_month" : "female_under_month";
			$total_group = "total_under_month";
		}elseif($age_group <= 11){
			$age_group = $gender == "MALE" ? "male_under_year" : "female_under_year";
			$total_group = "total_under_year";
		}elseif($age_group <= 59){
			$age_group = $gender == "MALE" ? "male_under_5year" : "female_under_5year";
			$total_group = "female_under_5year";
		}elseif($age_group <= 719){
			$age_group = $gender == "MALE" ? "male_under_60year" : "female_under_60year";
			$total_group = "total_under_60year";
		}elseif($age_group >= 720){
			$age_group = $gender == "MALE" ? "male_above_60year" : "female_above_60year";
			$total_group = "total_above_60year";
		}

		$todayCounts = DB::select("select count(*) count from opd_attendance_register where date=CURRENT_DATE and facility_id='".$request['facility_id']."'");
		if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
			DB::statement("update opd_attendance_register set $age_group = $age_group+1,$total_group=$total_group+1,".($gender == "MALE" ? "total_male = total_male+1" : "total_female=total_female+1").",grand_total=grand_total+1 where date = CURRENT_DATE and facility_id='".$request['facility_id']."'");
		}else{
			DB::statement("insert into opd_attendance_register(facility_id,$age_group,$total_group,".($gender == "MALE" ? "total_male" : "total_female").",grand_total,date) select '".$request['facility_id']."',1,1,1,1, CURRENT_DATE");
		}
    }

	public function countInternalReferral(Request $request){
		//find age in months
		$dob = explode('-',$request['dob']);
		$dob = $dob[2]."-".$dob[1]."-".$dob[0];
		$dob = new \DateTime($dob);
		$interval = (new \DateTime())->diff($dob);
		$age_group = $interval->m + $interval->y*12;
		$gender = $request['gender'];

		if($age_group <= 0){
			$age_group = $gender == "MALE" ? "male_under_month" : "female_under_month";
			$total_group = "total_under_month";
		}elseif($age_group <= 11){
			$age_group = $gender == "MALE" ? "male_under_year" : "female_under_year";
			$total_group = "total_under_year";
		}elseif($age_group <= 59){
			$age_group = $gender == "MALE" ? "male_under_5year" : "female_under_5year";
			$total_group = "female_under_5year";
		}elseif($age_group <= 719){
			$age_group = $gender == "MALE" ? "male_under_60year" : "female_under_60year";
			$total_group = "total_under_60year";
		}elseif($age_group >= 720){
			$age_group = $gender == "MALE" ? "male_above_60year" : "female_above_60year";
			$total_group = "total_above_60year";
		}

		$todayCounts = DB::select("select count(*) count from internal_referral_register where date=CURRENT_DATE and facility_id='".$request['facility_id']."'");
		if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
			DB::statement("update internal_referral_register set $age_group = $age_group+1,$total_group=$total_group+1,".($gender == "MALE" ? "total_male = total_male+1" : "total_female=total_female+1").",grand_total=grand_total+1 where date = CURRENT_DATE and facility_id='".$request['facility_id']."'");
		}else{
			DB::statement("insert into internal_referral_register(facility_id,$age_group,$total_group,".($gender == "MALE" ? "total_male" : "total_female").",grand_total,date) select '".$request['facility_id']."',1,1,1,1, CURRENT_DATE");
		}
    }

	public function countExternalReferral(Request $request){
		//find age in months
		$dob = explode('-',$request['dob']);
		$dob = $dob[2]."-".$dob[1]."-".$dob[0];
		$dob = new \DateTime($dob);
		$interval = (new \DateTime())->diff($dob);
		$age_group = $interval->m + $interval->y*12;
		$gender = $request['gender'];

		if($age_group <= 0){
			$age_group = $gender == "MALE" ? "male_under_month" : "female_under_month";
			$total_group = "total_under_month";
		}elseif($age_group <= 11){
			$age_group = $gender == "MALE" ? "male_under_year" : "female_under_year";
			$total_group = "total_under_year";
		}elseif($age_group <= 59){
			$age_group = $gender == "MALE" ? "male_under_5year" : "female_under_5year";
			$total_group = "female_under_5year";
		}elseif($age_group <= 719){
			$age_group = $gender == "MALE" ? "male_under_60year" : "female_under_60year";
			$total_group = "total_under_60year";
		}elseif($age_group >= 720){
			$age_group = $gender == "MALE" ? "male_above_60year" : "female_above_60year";
			$total_group = "total_above_60year";
		}

		$todayCounts = DB::select("select count(*) count from external_referral_register where date=CURRENT_DATE and facility_id='".$request['facility_id']."'");
		if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
			DB::statement("update external_referral_register set $age_group = $age_group+1,$total_group=$total_group+1,".($gender == "MALE" ? "total_male = total_male+1" : "total_female=total_female+1").",grand_total=grand_total+1 where date = CURRENT_DATE and facility_id='".$request['facility_id']."'");
		}else{
			DB::statement("insert into external_referral_register(facility_id,$age_group,$total_group,".($gender == "MALE" ? "total_male" : "total_female").",grand_total,date) select '".$request['facility_id']."',1,1,1,1, CURRENT_DATE");
		}
    }


    public function countReattendance(Request $request){
       //find age in months
		$dob = explode('-',$request['dob']);
		$dob = $dob[2]."-".$dob[1]."-".$dob[0];
		$dob = new \DateTime($dob);
		$interval = (new \DateTime())->diff($dob);
		$age_group = $interval->m + $interval->y*12;
		$gender = $request['gender'];

		if($age_group <= 0){
			$age_group = $gender == "MALE" ? "male_under_month" : "female_under_month";
			$total_group = "total_under_month";
		}elseif($age_group <= 11){
			$age_group = $gender == "MALE" ? "male_under_year" : "female_under_year";
			$total_group = "total_under_year";
		}elseif($age_group <= 59){
			$age_group = $gender == "MALE" ? "male_under_5year" : "female_under_5year";
			$total_group = "female_under_5year";
		}elseif($age_group <= 719){
			$age_group = $gender == "MALE" ? "male_under_60year" : "female_under_60year";
			$total_group = "total_under_60year";
		}elseif($age_group >= 720){
			$age_group = $gender == "MALE" ? "male_above_60year" : "female_above_60year";
			$total_group = "total_above_60year";
		}

		$todayCounts = DB::select("select count(*) count from reattendance_register where date=CURRENT_DATE and facility_id='".$request['facility_id']."'");
		if(is_array($todayCounts)  && $todayCounts[0]->count > 0){print_r('1');
			DB::statement("update reattendance_register set $age_group = $age_group+1,$total_group=$total_group+1,".($gender == "MALE" ? "total_male = total_male+1" : "total_female=total_female+1").",grand_total=grand_total+1 where date = CURRENT_DATE and facility_id='".$request['facility_id']."'");
		}else{print_r('2');
			DB::statement("insert into reattendance_register(facility_id,$age_group,$total_group,".($gender == "MALE" ? "total_male" : "total_female").",grand_total,date) select '".$request['facility_id']."',1,1,1,1, CURRENT_DATE");
		}
    }

	public function countAdmissions(Request $request){
		//find age in months
		$dob = explode('-',$request['dob']);
		$dob = $dob[2]."-".$dob[1]."-".$dob[0];
		$dob = new \DateTime($dob);
		$interval = (new \DateTime())->diff($dob);
		$age_group = $interval->m + $interval->y*12;
		$gender = $request['gender'];

		if($age_group <= 0){
			$age_group = $gender == "MALE" ? "male_under_month" : "female_under_month";
			$total_group = "total_under_month";
		}elseif($age_group <= 11){
			$age_group = $gender == "MALE" ? "male_under_year" : "female_under_year";
			$total_group = "total_under_year";
		}elseif($age_group <= 59){
			$age_group = $gender == "MALE" ? "male_under_5year" : "female_under_5year";
			$total_group = "female_under_5year";
		}elseif($age_group <= 719){
			$age_group = $gender == "MALE" ? "male_under_60year" : "female_under_60year";
			$total_group = "total_under_60year";
		}elseif($age_group >= 720){
			$age_group = $gender == "MALE" ? "male_above_60year" : "female_above_60year";
			$total_group = "total_above_60year";
		}

		$todayCounts = DB::select("select count(*) count from admission_register where date=CURRENT_DATE and facility_id='".$request['facility_id']."'");
		if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
			DB::statement("update admission_register set $age_group = $age_group+1,$total_group=$total_group+1,".($gender == "MALE" ? "total_male = total_male+1" : "total_female=total_female+1").",grand_total=grand_total+1 where date = CURRENT_DATE and facility_id='".$request['facility_id']."'");
		}else{
			DB::statement("insert into admission_register(facility_id,$age_group,$total_group,".($gender == "MALE" ? "total_male" : "total_female").",grand_total,date) select '".$request['facility_id']."',1,1,1,1, CURRENT_DATE");
		}
    }

	public function countOPDDiseases(Request $request){
		//find age in months
		$dob = explode('-',$request['dob']);
		$dob = $dob[2]."-".$dob[1]."-".$dob[0];
		$dob = new \DateTime($dob);
		$interval = (new \DateTime())->diff($dob);
		$age_group = $interval->m + $interval->y*12;
		$gender = $request['gender'];

		if($age_group <= 0){
			$age_group = $gender == "MALE" ? "male_under_month" : "female_under_month";
			$total_group = "total_under_month";
		}elseif($age_group <= 11){
			$age_group = $gender == "MALE" ? "male_under_year" : "female_under_year";
			$total_group = "total_under_year";
		}elseif($age_group <= 59){
			$age_group = $gender == "MALE" ? "male_under_5year" : "female_under_5year";
			$total_group = "female_under_5year";
		}elseif($age_group <= 719){
			$age_group = $gender == "MALE" ? "male_under_60year" : "female_under_60year";
			$total_group = "total_under_60year";
		}elseif($age_group >= 720){
			$age_group = $gender == "MALE" ? "male_above_60year" : "female_above_60year";
			$total_group = "total_above_60year";
		}

		$observations = $request['concepts'];
		foreach($observations as $observation){
			$todayCounts = DB::select("select count(*) count from tbl_opd_diseases_register where date=CURRENT_DATE and diagnosis_id = '".$observation['diagnosis_id']."' and facility_id='".$request['facility_id']."'");
			if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
				DB::statement("update tbl_opd_diseases_register set $age_group = $age_group+1,$total_group=$total_group+1,".($gender == "MALE" ? "total_male = total_male+1" : "total_female=total_female+1").",grand_total=grand_total+1 where date = CURRENT_DATE  and diagnosis_id = '".$observation['diagnosis_id']."' and facility_id='".$request['facility_id']."'");
			}else{
				DB::statement("insert into tbl_opd_diseases_register(facility_id,diagnosis_id, description,$age_group,$total_group,".($gender == "MALE" ? "total_male" : "total_female").",grand_total,date) select '".$request['facility_id']."','".$observation['diagnosis_id']."','".$observation['concept']."',1,1,1,1, CURRENT_DATE");
			}
		}
    }

	public function countIPDDiseases(Request $request){
		//find age in months
		$dob = explode('-',$request['dob']);
		$dob = $dob[2]."-".$dob[1]."-".$dob[0];
		$dob = new \DateTime($dob);
		$interval = (new \DateTime())->diff($dob);
		$age_group = $interval->m + $interval->y*12;
		$gender = $request['gender'];

		if($age_group <= 0){
			$age_group = $gender == "MALE" ? "male_under_month" : "female_under_month";
			$total_group = "total_under_month";
		}elseif($age_group <= 11){
			$age_group = $gender == "MALE" ? "male_under_year" : "female_under_year";
			$total_group = "total_under_year";
		}elseif($age_group <= 59){
			$age_group = $gender == "MALE" ? "male_under_5year" : "female_under_5year";
			$total_group = "female_under_5year";
		}elseif($age_group <= 719){
			$age_group = $gender == "MALE" ? "male_under_60year" : "female_under_60year";
			$total_group = "total_under_60year";
		}elseif($age_group >= 720){
			$age_group = $gender == "MALE" ? "male_above_60year" : "female_above_60year";
			$total_group = "total_above_60year";
		}

		$observations = $request['concepts'];
		foreach($observations as $observation){
			$todayCounts = DB::select("select count(*) count from ipd_diseases_register where date=CURRENT_DATE and diagnosis_id = '".$observation['diagnosis_id']."' and facility_id='".$request['facility_id']."'");
			if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
				DB::statement("update ipd_diseases_register set $age_group = $age_group+1,$total_group=$total_group+1,".($gender == "MALE" ? "total_male = total_male+1" : "total_female=total_female+1").",grand_total=grand_total+1 where date = CURRENT_DATE  and diagnosis_id = '".$observation['diagnosis_id']."' and facility_id='".$request['facility_id']."'");
			}else{
				DB::statement("insert into ipd_diseases_register(facility_id,diagnosis_id, description,$age_group,$total_group,".($gender == "MALE" ? "total_male" : "total_female").",grand_total,date) select '".$request['facility_id']."','".$observation['diagnosis_id']."','".$observation['concept']."',1,1,1,1, CURRENT_DATE");
			}
		}
    }

	public function migrateMtuha(Request $request){
		DB::statement("truncate tbl_opd_diseases_register");
		DB::statement("truncate ipd_diseases_register");
		DB::statement("truncate opd_attendance_register");
		DB::statement("truncate reattendance_register");
		DB::statement("truncate admission_register");
		DB::statement("truncate internal_referral_register");


		$facility_id = $request['facility_id'];
		$dates = DB::select("select distinct date from patients");
		foreach($dates as $date){
			//<1month
			$group = DB::select("select count(*) count from patients where gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  date) < 1 and date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from opd_attendance_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update opd_attendance_register set male_under_month = male_under_month+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into opd_attendance_register(facility_id,male_under_month,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*) count from patients where gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  date) < 1 and date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from opd_attendance_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update opd_attendance_register set female_under_month = female_under_month+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into opd_attendance_register(facility_id,female_under_month,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}


			//<1year
			$group = DB::select("select count(*) count from patients where gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  date) between 1 and 11 and date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from opd_attendance_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update opd_attendance_register set male_under_year = male_under_year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into opd_attendance_register(facility_id,male_under_year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*) count from patients where gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  date)  between 1 and  11 and date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from opd_attendance_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update opd_attendance_register set female_under_year = female_under_year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into opd_attendance_register(facility_id,female_under_year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}


			//<5year
			$group = DB::select("select count(*) count from patients where gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  date)  between 12 and  59 and date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from opd_attendance_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update opd_attendance_register set male_under_5year = male_under_5year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into opd_attendance_register(facility_id,male_under_5year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*) count from patients where gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  date)  between 12 and  59 and date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from opd_attendance_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update opd_attendance_register set female_under_5year = female_under_5year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into opd_attendance_register(facility_id,female_under_5year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}


			//<60year
			$group = DB::select("select count(*) count from patients where gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  date)  between 60 and  719 and date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from opd_attendance_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update opd_attendance_register set male_under_60year = male_under_60year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into opd_attendance_register(facility_id,male_under_60year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*) count from patients where gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  date)  between 60 and  719 and date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from opd_attendance_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update opd_attendance_register set female_under_60year = female_under_60year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into opd_attendance_register(facility_id,female_under_60year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}


			//>=60year
			$group = DB::select("select count(*) count from patients where gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  date) >=720 and date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from opd_attendance_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update opd_attendance_register set male_above_60year = male_above_60year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into opd_attendance_register(facility_id,male_above_60year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*) count from patients where gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  date) >=720 and date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from opd_attendance_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update opd_attendance_register set female_above_60year = female_above_60year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into opd_attendance_register(facility_id,female_above_60year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}

		}

		$dates = DB::select("select distinct date from reattendances");
		foreach($dates as $date){
			//<1month
			$group = DB::select("select count(*) count from patients inner join reattendances on patients.id=reattendances.patient_id where gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  reattendances.date) < 1 and reattendances.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from reattendance_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update reattendance_register set male_under_month = male_under_month+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into reattendance_register(facility_id,male_under_month,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*) count from patients inner join reattendances on patients.id=reattendances.patient_id where gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  reattendances.date) < 1 and reattendances.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from reattendance_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update reattendance_register set female_under_month = female_under_month+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into reattendance_register(facility_id,female_under_month,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}


			//<1year
			$group = DB::select("select count(*) count from patients inner join reattendances on patients.id=reattendances.patient_id where gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  reattendances.date) between 1 and 11 and reattendances.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from reattendance_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update reattendance_register set male_under_year = male_under_year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into reattendance_register(facility_id,male_under_year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*) count from patients inner join reattendances on patients.id=reattendances.patient_id where gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  reattendances.date)  between 1 and  11 and reattendances.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from reattendance_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update reattendance_register set female_under_year = female_under_year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into reattendance_register(facility_id,female_under_year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}


			//<5year
			$group = DB::select("select count(*) count from patients inner join reattendances on patients.id=reattendances.patient_id where gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  reattendances.date)  between 12 and  59 and reattendances.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from reattendance_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update reattendance_register set male_under_5year = male_under_5year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into reattendance_register(facility_id,male_under_5year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*) count from patients inner join reattendances on patients.id=reattendances.patient_id where gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  reattendances.date)  between 12 and  59 and reattendances.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from reattendance_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update reattendance_register set female_under_5year = female_under_5year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into reattendance_register(facility_id,female_under_5year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}


			//<60year
			$group = DB::select("select count(*) count from patients inner join reattendances on patients.id=reattendances.patient_id where gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  reattendances.date)  between 60 and  719 and reattendances.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from reattendance_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update reattendance_register set male_under_60year = male_under_60year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into reattendance_register(facility_id,male_under_60year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*) count from patients inner join reattendances on patients.id=reattendances.patient_id where gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  reattendances.date)  between 60 and  719 and reattendances.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from reattendance_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update reattendance_register set female_under_60year = female_under_60year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into reattendance_register(facility_id,female_under_60year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}


			//>=60year
			$group = DB::select("select count(*) count from patients inner join reattendances on patients.id=reattendances.patient_id where gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  reattendances.date) >=720 and reattendances.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from reattendance_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update reattendance_register set male_above_60year = male_above_60year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into reattendance_register(facility_id,male_above_60year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*) count from patients inner join reattendances on patients.id=reattendances.patient_id where gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  reattendances.date) >=720 and reattendances.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from reattendance_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update reattendance_register set female_above_60year = female_above_60year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into reattendance_register(facility_id,female_above_60year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}

		}


		$dates = DB::select("select distinct date from admissions");
		foreach($dates as $date){
			//<1month
			$group = DB::select("select count(*) count from patients inner join admissions on patients.id=admissions.patient_id where gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  admissions.date) < 1 and admissions.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from admission_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update admission_register set male_under_month = male_under_month+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into admission_register(facility_id,male_under_month,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*) count from patients inner join admissions on patients.id=admissions.patient_id where gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  admissions.date) < 1 and admissions.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from admission_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update admission_register set female_under_month = female_under_month+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into admission_register(facility_id,female_under_month,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}


			//<1year
			$group = DB::select("select count(*) count from patients inner join admissions on patients.id=admissions.patient_id where gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  admissions.date) between 1 and 11 and admissions.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from admission_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update admission_register set male_under_year = male_under_year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into admission_register(facility_id,male_under_year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*) count from patients inner join admissions on patients.id=admissions.patient_id where gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  admissions.date)  between 1 and  11 and admissions.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from admission_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update admission_register set female_under_year = female_under_year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into admission_register(facility_id,female_under_year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}


			//<5year
			$group = DB::select("select count(*) count from patients inner join admissions on patients.id=admissions.patient_id where gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  admissions.date)  between 12 and  59 and admissions.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from admission_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update admission_register set male_under_5year = male_under_5year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into admission_register(facility_id,male_under_5year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*) count from patients inner join admissions on patients.id=admissions.patient_id where gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  admissions.date)  between 12 and  59 and admissions.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from admission_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update admission_register set female_under_5year = female_under_5year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into admission_register(facility_id,female_under_5year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}


			//<60year
			$group = DB::select("select count(*) count from patients inner join admissions on patients.id=admissions.patient_id where gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  admissions.date)  between 60 and  719 and admissions.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from admission_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update admission_register set male_under_60year = male_under_60year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into admission_register(facility_id,male_under_60year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*) count from patients inner join admissions on patients.id=admissions.patient_id where gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  admissions.date)  between 60 and  719 and admissions.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from admission_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update admission_register set female_under_60year = female_under_60year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into admission_register(facility_id,female_under_60year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}


			//>=60year
			$group = DB::select("select count(*) count from patients inner join admissions on patients.id=admissions.patient_id where gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  admissions.date) >=720 and admissions.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from admission_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update admission_register set male_above_60year = male_above_60year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into admission_register(facility_id,male_above_60year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*) count from patients inner join admissions on patients.id=admissions.patient_id where gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  admissions.date) >=720 and admissions.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from admission_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update admission_register set female_above_60year = female_above_60year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into admission_register(facility_id,female_above_60year,date) select '$facility_id',".$group[0]->count.",'".$date->date."'");
				}
			}
		}


		$dates = DB::select("select distinct date from observations where  observations.value_text = 'cd' and observations.patient_id not in (select patient_id from admissions)");
		foreach($dates as $date){
			//<1month
			$group = DB::select("select count(*) count, diagnosis_id, description from patients inner join observations on observations.value_text = 'cd' and observations.patient_id not in (select patient_id from admissions) and patients.id=observations.patient_id inner join concept_dictionaries on observations.diagnosis_id = concept_dictionaries.id where gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  observations.date) < 1 and observations.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from tbl_opd_diseases_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update tbl_opd_diseases_register set male_under_month = male_under_month+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into tbl_opd_diseases_register(facility_id,diagnosis_id, description,male_under_month,date) select '$facility_id','".$group[0]->diagnosis_id."','".$group[0]->description."',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*) count, diagnosis_id, description from patients inner join observations on observations.value_text = 'cd' and observations.patient_id not in (select patient_id from admissions) and patients.id=observations.patient_id inner join concept_dictionaries on observations.diagnosis_id = concept_dictionaries.id where  gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  observations.date) < 1 and observations.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from tbl_opd_diseases_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update tbl_opd_diseases_register set female_under_month = female_under_month+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into tbl_opd_diseases_register(facility_id,diagnosis_id, description,female_under_month,date) select '$facility_id','".$group[0]->diagnosis_id."','".$group[0]->description."',".$group[0]->count.",'".$date->date."'");
				}
			}


			//<1year
			$group = DB::select("select count(*) count, diagnosis_id, description from patients inner join observations on observations.value_text = 'cd' and observations.patient_id not in (select patient_id from admissions) and patients.id=observations.patient_id inner join concept_dictionaries on observations.diagnosis_id = concept_dictionaries.id where gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  observations.date) between 1 and 11 and observations.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from tbl_opd_diseases_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update tbl_opd_diseases_register set male_under_year = male_under_year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into tbl_opd_diseases_register(facility_id,diagnosis_id, description,male_under_year,date) select '$facility_id','".$group[0]->diagnosis_id."','".$group[0]->description."',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*) count, diagnosis_id, description from patients inner join observations on observations.value_text = 'cd' and observations.patient_id not in (select patient_id from admissions) and patients.id=observations.patient_id inner join concept_dictionaries on observations.diagnosis_id = concept_dictionaries.id where gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  observations.date)  between 1 and  11 and observations.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from tbl_opd_diseases_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update tbl_opd_diseases_register set female_under_year = female_under_year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into tbl_opd_diseases_register(facility_id,diagnosis_id, description,female_under_year,date) select '$facility_id','".$group[0]->diagnosis_id."','".$group[0]->description."',".$group[0]->count.",'".$date->date."'");
				}
			}


			//<5year
			$group = DB::select("select count(*) count, diagnosis_id, description from patients inner join observations on observations.value_text = 'cd' and observations.patient_id not in (select patient_id from admissions) and patients.id=observations.patient_id inner join concept_dictionaries on observations.diagnosis_id = concept_dictionaries.id where gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  observations.date)  between 12 and  59 and observations.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from tbl_opd_diseases_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update tbl_opd_diseases_register set male_under_5year = male_under_5year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into tbl_opd_diseases_register(facility_id,diagnosis_id, description,male_under_5year,date) select '$facility_id','".$group[0]->diagnosis_id."','".$group[0]->description."',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*) count, diagnosis_id, description from patients inner join observations on observations.value_text = 'cd' and observations.patient_id not in (select patient_id from admissions) and patients.id=observations.patient_id inner join concept_dictionaries on observations.diagnosis_id = concept_dictionaries.id where gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  observations.date)  between 12 and  59 and observations.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from tbl_opd_diseases_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update tbl_opd_diseases_register set female_under_5year = female_under_5year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into tbl_opd_diseases_register(facility_id,diagnosis_id, description,female_under_5year,date) select '$facility_id','".$group[0]->diagnosis_id."','".$group[0]->description."',".$group[0]->count.",'".$date->date."'");
				}
			}


			//<60year
			$group = DB::select("select count(*) count, diagnosis_id, description from patients inner join observations on observations.value_text = 'cd' and observations.patient_id not in (select patient_id from admissions) and patients.id=observations.patient_id inner join concept_dictionaries on observations.diagnosis_id = concept_dictionaries.id where gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  observations.date)  between 60 and  719 and observations.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from tbl_opd_diseases_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update tbl_opd_diseases_register set male_under_60year = male_under_60year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into tbl_opd_diseases_register(facility_id,diagnosis_id, description,male_under_60year,date) select '$facility_id','".$group[0]->diagnosis_id."','".$group[0]->description."',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*) count, diagnosis_id, description from patients inner join observations on observations.value_text = 'cd' and observations.patient_id not in (select patient_id from admissions) and patients.id=observations.patient_id inner join concept_dictionaries on observations.diagnosis_id = concept_dictionaries.id where gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  observations.date)  between 60 and  719 and observations.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from tbl_opd_diseases_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update tbl_opd_diseases_register set female_under_60year = female_under_60year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into tbl_opd_diseases_register(facility_id,diagnosis_id, description,female_under_60year,date) select '$facility_id','".$group[0]->diagnosis_id."','".$group[0]->description."',".$group[0]->count.",'".$date->date."'");
				}
			}


			//>=60year
			$group = DB::select("select count(*) count, diagnosis_id, description from patients inner join observations on observations.value_text = 'cd' and observations.patient_id not in (select patient_id from admissions) and patients.id=observations.patient_id inner join concept_dictionaries on observations.diagnosis_id = concept_dictionaries.id where gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  observations.date) >=720 and observations.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from tbl_opd_diseases_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update tbl_opd_diseases_register set male_above_60year = male_above_60year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into tbl_opd_diseases_register(facility_id,diagnosis_id, description,male_above_60year,date) select '$facility_id','".$group[0]->diagnosis_id."','".$group[0]->description."',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*) count, diagnosis_id, description from patients inner join observations on observations.value_text = 'cd' and observations.patient_id not in (select patient_id from admissions) and patients.id=observations.patient_id inner join concept_dictionaries on observations.diagnosis_id = concept_dictionaries.id where gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  observations.date) >=720 and observations.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from tbl_opd_diseases_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update tbl_opd_diseases_register set female_above_60year = female_above_60year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into tbl_opd_diseases_register(facility_id,diagnosis_id, description,female_above_60year,date) select '$facility_id','".$group[0]->diagnosis_id."','".$group[0]->description."',".$group[0]->count.",'".$date->date."'");
				}
			}

		}

		$dates = DB::select("select distinct observations.date from observations inner join admissions on observations.value_text = 'cd' and observations.patient_id = admissions.patient_id");
		foreach($dates as $date){
			//<1month
			$group = DB::select("select count(*) count,diagnosis_id, description from patients inner join observations on patients.id=observations.patient_id inner join admissions on patients.id = observations.value_text = 'cd' and admissions.patient_id inner join concept_dictionaries on observations.diagnosis_id = concept_dictionaries.id where gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  observations.date) < 1 and observations.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from ipd_diseases_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update ipd_diseases_register set male_under_month = male_under_month+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into tbl_opd_diseases_register(facility_id,diagnosis_id, description,male_under_month,date) select '$facility_id','".$group[0]->diagnosis_id."','".$group[0]->description."',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*) count,diagnosis_id, description  from patients inner join observations on patients.id=observations.patient_id inner join admissions on patients.id = observations.value_text = 'cd' and admissions.patient_id inner join concept_dictionaries on observations.diagnosis_id = concept_dictionaries.id where  gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  observations.date) < 1 and observations.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from ipd_diseases_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update ipd_diseases_register set female_under_month = female_under_month+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into tbl_opd_diseases_register(facility_id,diagnosis_id, description,female_under_month,date) select '$facility_id','".$group[0]->diagnosis_id."','".$group[0]->description."',".$group[0]->count.",'".$date->date."'");
				}
			}


			//<1year
			$group = DB::select("select count(*)  count,diagnosis_id, description  from patients inner join observations on patients.id=observations.patient_id inner join admissions on patients.id = observations.value_text = 'cd' and admissions.patient_id inner join concept_dictionaries on observations.diagnosis_id = concept_dictionaries.id where  gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  observations.date) between 1 and 11 and observations.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from ipd_diseases_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update ipd_diseases_register set male_under_year = male_under_year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into tbl_opd_diseases_register(facility_id,diagnosis_id, description,male_under_year,date) select '$facility_id','".$group[0]->diagnosis_id."','".$group[0]->description."',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*)  count,diagnosis_id, description  from patients inner join observations on patients.id=observations.patient_id inner join admissions on patients.id = observations.value_text = 'cd' and admissions.patient_id inner join concept_dictionaries on observations.diagnosis_id = concept_dictionaries.id where  gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  observations.date)  between 1 and  11 and observations.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from ipd_diseases_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update ipd_diseases_register set female_under_year = female_under_year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into tbl_opd_diseases_register(facility_id,diagnosis_id, description,female_under_year,date) select '$facility_id','".$group[0]->diagnosis_id."','".$group[0]->description."',".$group[0]->count.",'".$date->date."'");
				}
			}


			//<5year
			$group = DB::select("select count(*)  count,diagnosis_id, description  from patients inner join observations on patients.id=observations.patient_id inner join admissions on patients.id = observations.value_text = 'cd' and admissions.patient_id inner join concept_dictionaries on observations.diagnosis_id = concept_dictionaries.id where  gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  observations.date)  between 12 and  59 and observations.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from ipd_diseases_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update ipd_diseases_register set male_under_5year = male_under_5year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into tbl_opd_diseases_register(facility_id,diagnosis_id, description,male_under_5year,date) select '$facility_id','".$group[0]->diagnosis_id."','".$group[0]->description."',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*)  count,diagnosis_id, description  from patients inner join observations on patients.id=observations.patient_id inner join admissions on patients.id = observations.value_text = 'cd' and admissions.patient_id inner join concept_dictionaries on observations.diagnosis_id = concept_dictionaries.id where  gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  observations.date)  between 12 and  59 and observations.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from ipd_diseases_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update ipd_diseases_register set female_under_5year = female_under_5year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into tbl_opd_diseases_register(facility_id,diagnosis_id, description,female_under_5year,date) select '$facility_id','".$group[0]->diagnosis_id."','".$group[0]->description."',".$group[0]->count.",'".$date->date."'");
				}
			}


			//<60year
			$group = DB::select("select count(*)  count,diagnosis_id, description  from patients inner join observations on patients.id=observations.patient_id inner join admissions on patients.id = observations.value_text = 'cd' and admissions.patient_id inner join concept_dictionaries on observations.diagnosis_id = concept_dictionaries.id where  gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  observations.date)  between 60 and  719 and observations.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from ipd_diseases_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update ipd_diseases_register set male_under_60year = male_under_60year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into tbl_opd_diseases_register(facility_id,diagnosis_id, description,male_under_60year,date) select '$facility_id','".$group[0]->diagnosis_id."','".$group[0]->description."',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*)  count,diagnosis_id, description  from patients inner join observations on patients.id=observations.patient_id inner join admissions on patients.id = observations.value_text = 'cd' and admissions.patient_id inner join concept_dictionaries on observations.diagnosis_id = concept_dictionaries.id where  gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  observations.date)  between 60 and  719 and observations.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from ipd_diseases_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update ipd_diseases_register set female_under_60year = female_under_60year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into tbl_opd_diseases_register(facility_id,diagnosis_id, description,female_under_60year,date) select '$facility_id','".$group[0]->diagnosis_id."','".$group[0]->description."',".$group[0]->count.",'".$date->date."'");
				}
			}


			//>=60year
			$group = DB::select("select count(*)  count,diagnosis_id, description  from patients inner join observations on patients.id=observations.patient_id inner join admissions on patients.id = observations.value_text = 'cd' and admissions.patient_id inner join concept_dictionaries on observations.diagnosis_id = concept_dictionaries.id where  gender='male' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  observations.date) >=720 and observations.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from ipd_diseases_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update ipd_diseases_register set male_above_60year = male_above_60year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into tbl_opd_diseases_register(facility_id,diagnosis_id, description,male_above_60year,date) select '$facility_id','".$group[0]->diagnosis_id."','".$group[0]->description."',".$group[0]->count.",'".$date->date."'");
				}
			}

			$group = DB::select("select count(*)  count,diagnosis_id, description  from patients inner join observations on patients.id=observations.patient_id inner join admissions on patients.id = observations.value_text = 'cd' and admissions.patient_id inner join concept_dictionaries on observations.diagnosis_id = concept_dictionaries.id where  gender='female' and timestampdiff(month, str_to_date(date_of_birth, '%d-%m-%Y'),  observations.date) >=720 and observations.date='".$date->date."'");
			if(is_array($group) && $group[0]->count > 0){
				$todayCounts = DB::select("select count(*) count from ipd_diseases_register where date='".$date->date."'");
				if(is_array($todayCounts)  && $todayCounts[0]->count > 0){
					DB::statement("update ipd_diseases_register set female_above_60year = female_above_60year+".$group[0]->count." where date='".$date->date."'");
				}else{
					DB::statement("insert into tbl_opd_diseases_register(facility_id,diagnosis_id, description,female_above_60year,date) select '$facility_id','".$group[0]->diagnosis_id."','".$group[0]->description."',".$group[0]->count.",'".$date->date."'");
				}
			}

		}

		//sum all total fields
		DB::statement("update tbl_opd_diseases_register set total_under_year = male_under_year+female_under_year");
		DB::statement("update tbl_opd_diseases_register set total_under_month = male_under_month+female_under_month");
		DB::statement("update tbl_opd_diseases_register set total_under_5year = male_under_5year+female_under_5year");
		DB::statement("update tbl_opd_diseases_register set total_under_60year = male_under_60year+female_under_60year");
		DB::statement("update tbl_opd_diseases_register set total_above_60year = male_above_60year+female_above_60year");
		DB::statement("update tbl_opd_diseases_register set total_male = male_under_month+male_under_year+male_under_5year+male_under_60year+male_above_60year");
		DB::statement("update tbl_opd_diseases_register set total_female=female_under_month+female_under_year+female_under_5year+female_under_60year+female_above_60year");
		DB::statement("update tbl_opd_diseases_register set grand_total = total_male+total_female");

		DB::statement("update ipd_diseases_register set total_under_year = male_under_year+female_under_year");
		DB::statement("update ipd_diseases_register set total_under_month = male_under_month+female_under_month");
		DB::statement("update ipd_diseases_register set total_under_5year = male_under_5year+female_under_5year");
		DB::statement("update ipd_diseases_register set total_under_60year = male_under_60year+female_under_60year");
		DB::statement("update ipd_diseases_register set total_above_60year = male_above_60year+female_above_60year");
		DB::statement("update ipd_diseases_register set total_male = male_under_month+male_under_year+male_under_5year+male_under_60year+male_above_60year");
		DB::statement("update ipd_diseases_register set total_female=female_under_month+female_under_year+female_under_5year+female_under_60year+female_above_60year");

		DB::statement("update opd_attendance_register set total_under_year = male_under_year+female_under_year");
		DB::statement("update opd_attendance_register set total_under_month = male_under_month+female_under_month");
		DB::statement("update opd_attendance_register set total_under_5year = male_under_5year+female_under_5year");
		DB::statement("update opd_attendance_register set total_under_60year = male_under_60year+female_under_60year");
		DB::statement("update opd_attendance_register set total_above_60year = male_above_60year+female_above_60year");
		DB::statement("update opd_attendance_register set total_male = male_under_month+male_under_year+male_under_5year+male_under_60year+male_above_60year");
		DB::statement("update opd_attendance_register set total_female=female_under_month+female_under_year+female_under_5year+female_under_60year+female_above_60year");
		DB::statement("update opd_attendance_register set grand_total = total_male+total_female");


		DB::statement("update reattendance_register set total_under_year = male_under_year+female_under_year");
		DB::statement("update reattendance_register set total_under_month = male_under_month+female_under_month");
		DB::statement("update reattendance_register set total_under_5year = male_under_5year+female_under_5year");
		DB::statement("update reattendance_register set total_under_60year = male_under_60year+female_under_60year");
		DB::statement("update reattendance_register set total_above_60year = male_above_60year+female_above_60year");
		DB::statement("update reattendance_register set total_male = male_under_month+male_under_year+male_under_5year+male_under_60year+male_above_60year");
		DB::statement("update reattendance_register set total_female=female_under_month+female_under_year+female_under_5year+female_under_60year+female_above_60year");
		DB::statement("update reattendance_register set grand_total = total_male+total_female");
		DB::statement("update ipd_diseases_register set grand_total = total_male+total_female");

		DB::statement("update admission_register set total_under_year = male_under_year+female_under_year");
		DB::statement("update admission_register set total_under_month = male_under_month+female_under_month");
		DB::statement("update admission_register set total_under_5year = male_under_5year+female_under_5year");
		DB::statement("update admission_register set total_under_60year = male_under_60year+female_under_60year");
		DB::statement("update admission_register set total_above_60year = male_above_60year+female_above_60year");
		DB::statement("update admission_register set total_male = male_under_month+male_under_year+male_under_5year+male_under_60year+male_above_60year");
		DB::statement("update admission_register set total_female=female_under_month+female_under_year+female_under_5year+female_under_60year+female_above_60year");
		DB::statement("update admission_register set grand_total = total_male+total_female");

		DB::statement("update internal_referral_register set total_under_year = male_under_year+female_under_year");
		DB::statement("update internal_referral_register set total_under_month = male_under_month+female_under_month");
		DB::statement("update internal_referral_register set total_under_5year = male_under_5year+female_under_5year");
		DB::statement("update internal_referral_register set total_under_60year = male_under_60year+female_under_60year");
		DB::statement("update internal_referral_register set total_above_60year = male_above_60year+female_above_60year");
		DB::statement("update internal_referral_register set total_male = male_under_month+male_under_year+male_under_5year+male_under_60year+male_above_60year");
		DB::statement("update internal_referral_register set total_female=female_under_month+female_under_year+female_under_5year+female_under_60year+female_above_60year");
		DB::statement("update internal_referral_register set grand_total = total_male+total_female");


		/*
		update tbl_opd_diseases_register set total_under_year = male_under_year+female_under_year;
		update tbl_opd_diseases_register set total_under_month = male_under_month+female_under_month;
		update tbl_opd_diseases_register set total_under_5year = male_under_5year+female_under_5year;
		update tbl_opd_diseases_register set total_under_60year = male_under_60year+female_under_60year;
		update tbl_opd_diseases_register set total_above_60year = male_above_60year+female_above_60year;
		update tbl_opd_diseases_register set total_male = male_under_month+male_under_year+male_under_5year+male_under_60year+male_above_60year;
		update tbl_opd_diseases_register set total_female=female_under_month+female_under_year+female_under_5year+female_under_60year+female_above_60year;
		update tbl_opd_diseases_register set grand_total = total_male+total_female;


		update ipd_diseases_register set total_under_year = male_under_year+female_under_year;
		update ipd_diseases_register set total_under_month = male_under_month+female_under_month;
		update ipd_diseases_register set total_under_5year = male_under_5year+female_under_5year;
		update ipd_diseases_register set total_under_60year = male_under_60year+female_under_60year;
		update ipd_diseases_register set total_above_60year = male_above_60year+female_above_60year;
		update ipd_diseases_register set total_male = male_under_month+male_under_year+male_under_5year+male_under_60year+male_above_60year;
		update ipd_diseases_register set total_female=female_under_month+female_under_year+female_under_5year+female_under_60year+female_above_60year;
		update ipd_diseases_register set grand_total = total_male+total_female;


		update opd_attendance_register set total_under_year = male_under_year+female_under_year;
		update opd_attendance_register set total_under_month = male_under_month+female_under_month;
		update opd_attendance_register set total_under_5year = male_under_5year+female_under_5year;
		update opd_attendance_register set total_under_60year = male_under_60year+female_under_60year;
		update opd_attendance_register set total_above_60year = male_above_60year+female_above_60year;
		update opd_attendance_register set total_male = male_under_month+male_under_year+male_under_5year+male_under_60year+male_above_60year;
		update opd_attendance_register set total_female=female_under_month+female_under_year+female_under_5year+female_under_60year+female_above_60year;
		update opd_attendance_register set grand_total = total_male+total_female;


		update reattendance_register set total_under_year = male_under_year+female_under_year;
		update reattendance_register set total_under_month = male_under_month+female_under_month;
		update reattendance_register set total_under_5year = male_under_5year+female_under_5year;
		update reattendance_register set total_under_60year = male_under_60year+female_under_60year;
		update reattendance_register set total_above_60year = male_above_60year+female_above_60year;
		update reattendance_register set total_male = male_under_month+male_under_year+male_under_5year+male_under_60year+male_above_60year;
		update reattendance_register set total_female=female_under_month+female_under_year+female_under_5year+female_under_60year+female_above_60year;
		update reattendance_register set grand_total = total_male+total_female;

		update admission_register set total_under_year = male_under_year+female_under_year;
		update admission_register set total_under_month = male_under_month+female_under_month;
		update admission_register set total_under_5year = male_under_5year+female_under_5year;
		update admission_register set total_under_60year = male_under_60year+female_under_60year;
		update admission_register set total_above_60year = male_above_60year+female_above_60year;
		update admission_register set total_male = male_under_month+male_under_year+male_under_5year+male_under_60year+male_above_60year;
		update admission_register set total_female=female_under_month+female_under_year+female_under_5year+female_under_60year+female_above_60year;
		update admission_register set grand_total = total_male+total_female;


		*/
	}
}
