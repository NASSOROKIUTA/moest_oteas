<?php

namespace App\Http\Controllers\reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;


class reportsController extends Controller
{
	
	public function pdfPrinting(Request $request){
		// instantiate and use the dompdf class
		$dompdf = new Dompdf();
		$html = "<html><head><link rel='stylesheet' href='/css/bootstrap.min.css'><link rel='stylesheet' href='/bower_components/angular-material/angular-material.min.css'>";
		$html .= "<style>.not_applicable {background-color:#c1c1c1;}</style>
        </head><body><button class='no-print pull-right btn btn-warning' onclick='window.print()'>PRINT</button>";
		$html .= $request['html']."</body></html>";
		print_r($html);return;
		$dompdf->loadHtml($html);

		$landscape = isset($request['orientation']) ? $request['orientation'] : 'potrait';
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4', $landscape);

		// Render the HTML as PDF
		$dompdf->render();

		// Output the generated PDF to Browser
		$dompdf->stream("PDF Format.pdf", array("Attachment" => false));
	}
	
	  public function reportsDrugs(request $request)
    {
        $response = [];
        $facility_id = $request->facility_id;
        if(!isset($request->start_date) OR !isset($request->end_date) ){
            $start_date=date('Y-m-01 07:00:00');
            $end_date=date("Y-m-d H:i:s");
        }else{
            $start_date=$request->start_date;
            $end_date=$request->end_date;
        }
        $dataQuery="SELECT *        
        FROM `vw_os_drugs` t1 WHERE t1.facility_id='{$facility_id}' AND (date_out_of_stock BETWEEN  '{$start_date}' AND '{$end_date}')";
        $response[] = DB::select($dataQuery);


        return $response;
    }


    public function reportsUnavailableTests(request $request)
    {
        $response = [];
        $facility_id = $request->facility_id;
        if(!isset($request->start_date) OR !isset($request->end_date) ){
            $start_date=date('Y-m-01 07:00:00');
            $end_date=date("Y-m-d H:i:s");
        }else{
            $start_date=$request->start_date;
            $end_date=$request->end_date;
        }
        $dataQuery="SELECT *     
        FROM `vw_unavailable_tests` t1 WHERE t1.facility_id='{$facility_id}' AND (date_out_of_stock BETWEEN  '{$start_date}' AND '{$end_date}')";
        $response[] = DB::select($dataQuery);


        return $response;
    }


    public function getMahudhurioOPD(request $request){
        $response = [];
        $facility_id=$request->facility_id;
        if(!isset($request->start_date) OR !isset($request->end_date) ){
            $start_date=date('Y-m-01 07:00:00');
            $end_date=date("Y-m-d H:i:s");
        }else{
            $start_date=$request->start_date;
            $end_date=$request->end_date;
        }
		
		$none = array("male_under_one_month"=>0, "female_under_one_month"=>0, "total_under_one_month"=>0, "male_under_one_year"=>0, "female_under_one_year"=>0, "total_under_one_year"=>0, "male_under_five_year"=>0, "female_under_five_year"=>0, "total_under_five_year"=>0, "male_above_five_under_sixty"=>0, "female_above_five_under_sixty"=>0, "total_above_five_under_sixty"=>0, "male_above_sixty"=>0, "female_above_sixty"=>0, "total_above_sixty"=>0, "grand_total_male"=>0, "grand_total_female"=>0, "grand_total"=>0);
		
        $sql_1="SELECT SUM(female_under_one_month) AS female_under_one_month ,SUM(male_under_one_month) AS male_under_one_month,SUM(total_under_one_month) AS total_under_one_month 
          ,SUM(female_under_one_year) AS female_under_one_year
          ,SUM(male_under_one_year) AS 	male_under_one_year
          ,SUM(total_under_one_year) AS total_under_one_year
          
         ,SUM(female_under_five_year) AS female_under_five_year
          ,SUM(male_under_five_year) AS male_under_five_year
          ,SUM(total_under_five_year) AS total_under_five_year
          
         ,SUM(female_above_five_under_sixty) AS female_above_five_under_sixty
          ,SUM(male_above_five_under_sixty) AS male_above_five_under_sixty
          ,SUM(total_above_five_under_sixty) AS total_above_five_under_sixty
        
        ,SUM(female_above_sixty) AS female_above_sixty
          ,SUM(male_above_sixty) AS male_above_sixty
          ,SUM(total_above_sixty) AS total_above_sixty
          
        ,SUM(total_female) AS grand_total_female
          ,SUM(total_male) AS grand_total_male
          ,SUM(grand_total) AS grand_total
        
        
         FROM `vw_newattendance_register` WHERE facility_id='{$facility_id}' AND clinic_id = 1 AND (date BETWEEN  date('$start_date') AND date('$end_date')) GROUP BY facility_id";
        
		$record = DB::select($sql_1);
		if(count($record) > 0)
			$response[] = $record;
		else
			$response[] = array($none);


        $sql_2="SELECT SUM(female_under_one_month) AS female_under_one_month ,SUM(male_under_one_month) AS male_under_one_month,SUM(total_under_one_month) AS total_under_one_month 
          ,SUM(female_under_one_year) AS female_under_one_year
          ,SUM(male_under_one_year) AS 	male_under_one_year
          ,SUM(total_under_one_year) AS total_under_one_year
          
         ,SUM(female_under_five_year) AS female_under_five_year
          ,SUM(male_under_five_year) AS male_under_five_year
          ,SUM(total_under_five_year) AS total_under_five_year
          
         ,SUM(female_above_five_under_sixty) AS female_above_five_under_sixty
          ,SUM(male_above_five_under_sixty) AS male_above_five_under_sixty
          ,SUM(total_above_five_under_sixty) AS total_above_five_under_sixty
        
        ,SUM(female_above_sixty) AS female_above_sixty
          ,SUM(male_above_sixty) AS male_above_sixty
          ,SUM(total_above_sixty) AS total_above_sixty
          
        ,SUM(total_female) AS grand_total_female
          ,SUM(total_male) AS grand_total_male
          ,SUM(grand_total) AS grand_total
        
        
         FROM `vw_reattendance_register` WHERE facility_id='{$facility_id}' AND clinic_id = 1 AND (date BETWEEN  date('$start_date') AND date('$end_date')) GROUP BY facility_id";
        
		$record = DB::select($sql_2);
		if(count($record) > 0)
			$response[] = $record;
		else
			$response[] = array($none);
		
		$response[] = array();
		
		$mtuha_diagnoses = DB::select("select id, description from tbl_opd_mtuha_diagnoses ORDER BY id");
		
		foreach($mtuha_diagnoses as $diagnosis){
			$sql_3="SELECT ".$diagnosis->id." as diagnosis_id,'".$diagnosis->description."' as description,ifnull(sum(male_under_one_month),0) as male_under_one_month,ifnull(sum(female_under_one_month),0) as female_under_one_month, ifnull(sum(total_under_one_month),0) as total_under_one_month,ifnull(sum(male_under_one_year),0) as male_under_one_year,ifnull(sum(female_under_one_year),0) as female_under_one_year,ifnull(sum(total_under_one_year),0) as total_under_one_year,ifnull(sum(male_under_five_year),0) as male_under_five_year,ifnull(sum(female_under_five_year),0) as female_under_five_year,ifnull(sum(total_under_five_year),0) as total_under_five_year,ifnull(sum(male_above_five_under_sixty),0) as male_above_five_under_sixty,ifnull(sum(female_above_five_under_sixty),0) as female_above_five_under_sixty,ifnull(sum(total_above_five_under_sixty),0) as total_above_five_under_sixty,ifnull(sum(male_above_sixty),0) as male_above_sixty,ifnull(sum(female_above_sixty),0) as female_above_sixty,ifnull(sum(total_above_sixty),0) as total_above_sixty,ifnull(sum(total_male),0) as grand_total_male,ifnull(sum(total_female),0) as grand_total_female,ifnull(sum(grand_total),0) as grand_total FROM tbl_opd_diseases_registers  WHERE opd_mtuha_diagnosis_id = ".$diagnosis->id."  AND facility_id ='$facility_id' AND date BETWEEN  date('$start_date') AND date('$end_date')";
			$record = DB::select($sql_3);
			if(count($record) > 0)
				array_push($response[2], $record[0]);
			else{
				$none['description'] = $diagnosis->description;
				array_push($response[2], (Object)$none);
			}
		}
		
		$others = DB::select("select 0 as diagnosis_id, 'Diagnoses, Other' as description,  ifnull(sum(male_under_one_month),0) as male_under_one_month,ifnull(sum(female_under_one_month),0) as female_under_one_month, ifnull(sum(total_under_one_month),0) as total_under_one_month,ifnull(sum(male_under_one_year),0) as male_under_one_year,ifnull(sum(female_under_one_year),0) as female_under_one_year,ifnull(sum(total_under_one_year),0) as total_under_one_year,ifnull(sum(male_under_five_year),0) as male_under_five_year,ifnull(sum(female_under_five_year),0) as female_under_five_year,ifnull(sum(total_under_five_year),0) as total_under_five_year,ifnull(sum(male_above_five_under_sixty),0) as male_above_five_under_sixty,ifnull(sum(female_above_five_under_sixty),0) as female_above_five_under_sixty,ifnull(sum(total_above_five_under_sixty),0) as total_above_five_under_sixty,ifnull(sum(male_above_sixty),0) as male_above_sixty,ifnull(sum(female_above_sixty),0) as female_above_sixty,ifnull(sum(total_above_sixty),0) as total_above_sixty,ifnull(sum(total_male),0) as grand_total_male,ifnull(sum(total_female),0) as grand_total_female,ifnull(sum(grand_total),0) as grand_total from tbl_opd_diseases_registers where opd_mtuha_diagnosis_id IS NULL AND facility_id ='$facility_id' AND date BETWEEN  date('$start_date') AND date('$end_date')");
		array_push($response[2], (Object)$others[0]);
		
		$referrals = DB::select("select 0 as diagnosis_id, 'Waliopewa Rufaa' as description,  ifnull(sum(male_under_one_month),0) as male_under_one_month,ifnull(sum(female_under_one_month),0) as female_under_one_month, ifnull(sum(total_under_one_month),0) as total_under_one_month,ifnull(sum(male_under_one_year),0) as male_under_one_year,ifnull(sum(female_under_one_year),0) as female_under_one_year,ifnull(sum(total_under_one_year),0) as total_under_one_year,ifnull(sum(male_under_five_year),0) as male_under_five_year,ifnull(sum(female_under_five_year),0) as female_under_five_year,ifnull(sum(total_under_five_year),0) as total_under_five_year,ifnull(sum(male_above_five_under_sixty),0) as male_above_five_under_sixty,ifnull(sum(female_above_five_under_sixty),0) as female_above_five_under_sixty,ifnull(sum(total_above_five_under_sixty),0) as total_above_five_under_sixty,ifnull(sum(male_above_sixty),0) as male_above_sixty,ifnull(sum(female_above_sixty),0) as female_above_sixty,ifnull(sum(total_above_sixty),0) as total_above_sixty,ifnull(sum(total_male),0) as grand_total_male,ifnull(sum(total_female),0) as grand_total_female,ifnull(sum(grand_total),0) as grand_total from tbl_outgoing_referral_registers where facility_id ='$facility_id' AND date BETWEEN  date('$start_date') AND date('$end_date')");
		array_push($response[2], (Object)$referrals[0]);
		return $response;
    }

 public function getStaffPerfomance(request $request){
        $response = [];
        $facility_id=$request->facility_id;
        $user_id=$request->user_id;
        if(!isset($request->start_date) OR !isset($request->end_date) ){
            $start_date=date('Y-m-01 07:00:00');
            $end_date=date("Y-m-d H:i:s");
        }else{
            $start_date=$request->start_date;
            $end_date=$request->end_date;
        }

        $sql_1="SELECT SUM(number_registered) AS number_registered 
 
               FROM `vw_staff_perfomances` t1 WHERE t1.user_id='{$user_id}' AND  t1.facility_id='{$facility_id}' AND (date_attended BETWEEN  '{$start_date}' AND '{$end_date}')";
        $response[] = DB::select($sql_1);

       $sql_2="SELECT SUM(number_registered) AS number_registered 
 
               FROM `vw_staff_perfomances` t1 WHERE t1.user_id <> '{$user_id}' AND t1.facility_id='{$facility_id}' AND (date_attended BETWEEN  '{$start_date}' AND '{$end_date}')";
        $response[] = DB::select($sql_2);


        $response[]=$start_date;

        $response[]=$end_date;

        return $response;

    }


    public function getIpdReport(request $request)
    {
        $response = [];
        $facility_id=$request->facility_id;
        if(!isset($request->start_date) OR !isset($request->end_date) ){
            $start_date=date('Y-m-01 07:00:00');
            $end_date=date("Y-m-d H:i:s");
        }else{
            $start_date=$request->start_date;
            $end_date=$request->end_date;
        }
		
		$none = array("male_under_one_month"=>0, "female_under_one_month"=>0, "total_under_one_month"=>0, "male_under_one_year"=>0, "female_under_one_year"=>0, "total_under_one_year"=>0, "male_under_five_year"=>0, "female_under_five_year"=>0, "total_under_five_year"=>0, "male_above_five_under_sixty"=>0, "female_above_five_under_sixty"=>0, "total_above_five_under_sixty"=>0, "male_above_sixty"=>0, "female_above_sixty"=>0, "total_above_sixty"=>0, "grand_total_male"=>0, "grand_total_female"=>0, "grand_total"=>0);
		
        $sql_1="SELECT SUM(female_under_one_month) AS female_under_one_month ,SUM(male_under_one_month) AS male_under_one_month,SUM(total_under_one_month) AS total_under_one_month 
          ,SUM(female_under_one_year) AS female_under_one_year
          ,SUM(male_under_one_year) AS 	male_under_one_year
          ,SUM(total_under_one_year) AS total_under_one_year
          
         ,SUM(female_under_five_year) AS female_under_five_year
          ,SUM(male_under_five_year) AS male_under_five_year
          ,SUM(total_under_five_year) AS total_under_five_year
          
         ,SUM(female_above_five_under_sixty) AS female_above_five_under_sixty
          ,SUM(male_above_five_under_sixty) AS male_above_five_under_sixty
          ,SUM(total_above_five_under_sixty) AS total_above_five_under_sixty
        
        ,SUM(female_above_sixty) AS female_above_sixty
          ,SUM(male_above_sixty) AS male_above_sixty
          ,SUM(total_above_sixty) AS total_above_sixty
          
        ,SUM(total_female) AS grand_total_female
          ,SUM(total_male) AS grand_total_male
          ,SUM(grand_total) AS grand_total
        
        
         FROM `vw_admission_register` WHERE facility_id='{$facility_id}' AND (date BETWEEN  date('$start_date') AND date('$end_date')) GROUP BY facility_id";
		$record = DB::select($sql_1);
		if(count($record) > 0)
			$response[] = $record;
		else
			$response[] = array($none);
			
		$response[] = array();
		
		$mtuha_diagnoses = DB::select("select id, description from tbl_ipd_mtuha_diagnoses ORDER BY id");
		
		foreach($mtuha_diagnoses as $diagnosis){
			$sql_3="SELECT '".$diagnosis->id."' as diagnosis_id,'".$diagnosis->description."' as description,ifnull(sum(male_under_one_month),0) as male_under_one_month,ifnull(sum(female_under_one_month),0) as female_under_one_month, ifnull(sum(total_under_one_month),0) as total_under_one_month,ifnull(sum(male_under_one_year),0) as male_under_one_year,ifnull(sum(female_under_one_year),0) as female_under_one_year,ifnull(sum(total_under_one_year),0) as total_under_one_year,ifnull(sum(male_under_five_year),0) as male_under_five_year,ifnull(sum(female_under_five_year),0) as female_under_five_year,ifnull(sum(total_under_five_year),0) as total_under_five_year,ifnull(sum(male_above_five_under_sixty),0) as male_above_five_under_sixty,ifnull(sum(female_above_five_under_sixty),0) as female_above_five_under_sixty,ifnull(sum(total_above_five_under_sixty),0) as total_above_five_under_sixty,ifnull(sum(male_above_sixty),0) as male_above_sixty,ifnull(sum(female_above_sixty),0) as female_above_sixty,ifnull(sum(total_above_sixty),0) as total_above_sixty,ifnull(sum(total_male),0) as grand_total_male,ifnull(sum(total_female),0) as grand_total_female,ifnull(sum(grand_total),0) as grand_total FROM tbl_ipd_diseases_registers  WHERE ipd_mtuha_diagnosis_id = ".$diagnosis->id."  AND facility_id ='$facility_id' AND date BETWEEN  date('$start_date') AND date('$end_date')";
			$record = DB::select($sql_3);
			if(count($record) > 0)
				array_push($response[1], $record[0]);
			else{
				$none['description'] = $diagnosis->description;
				array_push($response[1], (Object)$none);
			}
		}
		
		$others = DB::select("select 0 as diagnosis_id, 'Diagnoses, Other' as description,  ifnull(sum(male_under_one_month),0) as male_under_one_month,ifnull(sum(female_under_one_month),0) as female_under_one_month, ifnull(sum(total_under_one_month),0) as total_under_one_month,ifnull(sum(male_under_one_year),0) as male_under_one_year,ifnull(sum(female_under_one_year),0) as female_under_one_year,ifnull(sum(total_under_one_year),0) as total_under_one_year,ifnull(sum(male_under_five_year),0) as male_under_five_year,ifnull(sum(female_under_five_year),0) as female_under_five_year,ifnull(sum(total_under_five_year),0) as total_under_five_year,ifnull(sum(male_above_five_under_sixty),0) as male_above_five_under_sixty,ifnull(sum(female_above_five_under_sixty),0) as female_above_five_under_sixty,ifnull(sum(total_above_five_under_sixty),0) as total_above_five_under_sixty,ifnull(sum(male_above_sixty),0) as male_above_sixty,ifnull(sum(female_above_sixty),0) as female_above_sixty,ifnull(sum(total_above_sixty),0) as total_above_sixty,ifnull(sum(total_male),0) as grand_total_male,ifnull(sum(total_female),0) as grand_total_female,ifnull(sum(grand_total),0) as grand_total from tbl_ipd_diseases_registers where ipd_mtuha_diagnosis_id IS NULL AND facility_id ='$facility_id' AND date BETWEEN  date('$start_date') AND date('$end_date')");
		array_push($response[1], (Object)$others[0]);
		return $response;
    }


//getDentalClinicReport this uses dept_id = 9
    public function getDentalClinicReport(request $request)
    {
        $response = [];
        $facility_id=$request->facility_id;
        if(!isset($request->start_date) OR !isset($request->end_date) ){
            $start_date=date('Y-m-01 07:00:00');
            $end_date=date("Y-m-d H:i:s");
        }else{
            $start_date=$request->start_date;
            $end_date=$request->end_date;
        }

        $sql_1="SELECT date_attended,dept_id,facility_id,SUM(female_under_one_month) AS female_under_one_month ,SUM(male_under_one_month) AS male_under_one_month,SUM(total_under_one_month) AS total_under_one_month 
          ,SUM(female_under_one_year) AS female_under_one_year
          ,SUM(male_under_one_year) AS 	male_under_one_year
          ,SUM(total_under_one_year) AS total_under_one_year
          
         ,SUM(female_under_five_year) AS female_under_five_year
          ,SUM(male_under_five_year) AS male_under_five_year
          ,SUM(total_under_five_year) AS total_under_five_year
          
          	,SUM(female_5_upto_15_year) AS female_5_upto_15_year
          ,SUM(male_5_upto_15_year) AS male_5_upto_15_year
          ,SUM(	total_5_upto_15_year) AS 	total_5_upto_15_year
        
          	,SUM(female_15_and_above_year) AS female_15_and_above_year
          ,SUM(male_15_and_above_year) AS male_15_and_above_year
          ,SUM(total_15_and_above_year) AS 	total_15_and_above_year
        
         ,SUM(female_above_five_under_sixty_year) AS female_above_five_under_sixty_year
          ,SUM(male_above_five_under_sixty) AS male_above_five_under_sixty
          ,SUM(total_above_five_under_sixty) AS total_above_five_under_sixty
        
        ,SUM(female_above_sixty) AS female_above_sixty
          ,SUM(male_above_sixty) AS male_above_sixty
          ,SUM(total_above_sixty) AS total_above_sixty
          
        ,SUM(grand_total_female) AS grand_total_female
          ,SUM(grand_total_male) AS grand_total_male
          ,SUM(grand_total) AS grand_total
     
        FROM `vw_clinic_attendaces` t1 WHERE t1.dept_id=9 AND  t1.facility_id='{$facility_id}' AND (date_attended BETWEEN  '{$start_date}' AND '{$end_date}') GROUP BY date_attended,dept_id,facility_id";
        $response[] = DB::select($sql_1);

        return $response;

    }

//getDentalClinicReport this uses dept_id = 9
    public function getDoctorsPerfomaces(request $request)
    {

        $facility_id=$request->facility_id;
        if(!isset($request->start_date) OR !isset($request->end_date) ){
            $start_date=date('Y-m-01 07:00:00');
            $end_date=date("Y-m-d H:i:s");
        }else{
            $start_date=$request->start_date;
            $end_date=$request->end_date;
        }

       // return $end_date;

        $sql_1="SELECT t1.facility_id,t1.doctor_id,
        SUM(total_clerked) AS total_clerked,        
       t1.doctor_name,t1.prof_name     
     
        FROM `vw_doctor_perfomances` t1 WHERE t1.facility_id=".$facility_id." AND (time_treated BETWEEN   '".$start_date."' AND '".$end_date."') GROUP BY  t1.doctor_id,t1.doctor_name,t1.prof_name,facility_id";
        return DB::select($sql_1);


    }




//getEyeClinicReport this uses dept_id = 10
    public function getEyeClinicReport(request $request)
    {
        $response = [];
        $facility_id=$request->facility_id;
        if(!isset($request->start_date) OR !isset($request->end_date) ){
            $start_date=date('Y-m-01 07:00:00');
            $end_date=date("Y-m-d H:i:s");
        }else{
            $start_date=$request->start_date;
            $end_date=$request->end_date;
        }


        $sql_1="SELECT date_attended,dept_id,facility_id,SUM(female_under_one_month) AS female_under_one_month ,SUM(male_under_one_month) AS male_under_one_month,SUM(total_under_one_month) AS total_under_one_month 
          ,SUM(female_under_one_year) AS female_under_one_year
          ,SUM(male_under_one_year) AS 	male_under_one_year
          ,SUM(total_under_one_year) AS total_under_one_year
          
         ,SUM(female_under_five_year) AS female_under_five_year
          ,SUM(male_under_five_year) AS male_under_five_year
          ,SUM(total_under_five_year) AS total_under_five_year
          
          	,SUM(female_5_upto_15_year) AS female_5_upto_15_year
          ,SUM(male_5_upto_15_year) AS male_5_upto_15_year
          ,SUM(	total_5_upto_15_year) AS 	total_5_upto_15_year
        
          	,SUM(female_15_and_above_year) AS female_15_and_above_year
          ,SUM(male_15_and_above_year) AS male_15_and_above_year
          ,SUM(total_15_and_above_year) AS 	total_15_and_above_year
        
         ,SUM(female_above_five_under_sixty_year) AS female_above_five_under_sixty_year
          ,SUM(male_above_five_under_sixty) AS male_above_five_under_sixty
          ,SUM(total_above_five_under_sixty) AS total_above_five_under_sixty
        
        ,SUM(female_above_sixty) AS female_above_sixty
          ,SUM(male_above_sixty) AS male_above_sixty
          ,SUM(total_above_sixty) AS total_above_sixty
          
        ,SUM(grand_total_female) AS grand_total_female
          ,SUM(grand_total_male) AS grand_total_male
          ,SUM(grand_total) AS grand_total
     
        FROM `vw_clinic_attendaces` t1 WHERE t1.dept_id=10 AND  t1.facility_id='{$facility_id}' AND (date_attended BETWEEN  '{$start_date}' AND '{$end_date}') GROUP BY date_attended,dept_id,facility_id";
        $response[] = DB::select($sql_1);

        return $response;

    }




    public function getChildAttendanceReport(request $request)
    {

        $facility_id=$request->facility_id;
        $start_date=$request->start_date;
        $end_date=$request->end_date;


        $sql_child="SELECT sum(`Male_count`)as total_male,sum(`Female_count`) as total_female,sum(`total_gender`) as total_gender FROM
 `vw_child_registers`
 WHERE facility_id='$facility_id' and created_at BETWEEN '".$start_date."' and '".$end_date."' ";
        $response = DB::select($sql_child);

        return $response;

    }

    public function getChildGrowthAttendanceReport(request $request)
    {

        $facility_id=$request->facility_id;
        $start_date=$request->start_date;
        $end_date=$request->end_date;


        $sql_child="SELECT  sum(CASE when gender='MALE' then 1 ELSE  0 END ) as total_male,sum(CASE when gender='FEMALE' then 1 ELSE  0 END ) as total_female,COUNT(CASE when gender='FEMALE' OR gender='MALE' then 1  ELSE  0  END ) as total_gender FROM
 `tbl_child_growth_registers` INNER JOIN tbl_child_registers ON tbl_child_registers.id=tbl_child_growth_registers.patient_id
 WHERE  `tbl_child_growth_registers`.facility_id='$facility_id' AND timestampdiff(MONTH ,'".$end_date."',dob)<12 and `tbl_child_growth_registers`.created_at BETWEEN '".$start_date."' and '".$end_date."' ";
        $response = DB::select($sql_child);

        return $response;

    }

    public function getChildfeedingReport(request $request)
    {

        $facility_id=$request->facility_id;
        $start_date=$request->start_date;
        $end_date=$request->end_date;


        $sql_child_feed="SELECT
 sum(`ebf_male`)as ebf_male,sum(`ebf_female`) as ebf_female,sum(`ebf_total`) as ebf_total,
 sum(`rf_male`)as rf_male,sum(`rf_female`) as rf_female,sum(`rf_total`) as rf_total
  FROM
 `vw_baby_feedings`
 WHERE facility_id='$facility_id' and created_at BETWEEN '".$start_date."' and '".$end_date."' ";
        $response = DB::select($sql_child_feed);

        return $response;

    }


    public function getChilddewormgivenReport(request $request)
    {
        $dewormVourcher=[];
        $facility_id=$request->facility_id;
        $start_date=$request->start_date;
        $end_date=$request->end_date;


        $sql_child_dewom="SELECT
 sum(CASE when gender='MALE' AND deworm_given='YES'   then 1 ELSE  0 END ) as male,sum(CASE when  gender='FEMALE' AND deworm_given='YES'   then 1 ELSE  0 END )as female,
 sum(CASE when deworm_given='YES'   then 1 ELSE  0 END )as total
  FROM tbl_child_vitamin_deworm_registers INNER  JOIN tbl_child_registers ON tbl_child_registers.id=tbl_child_vitamin_deworm_registers.client_id
  
 WHERE tbl_child_vitamin_deworm_registers.facility_id='$facility_id' and tbl_child_vitamin_deworm_registers.created_at BETWEEN '".$start_date."' and '".$end_date."' ";
        $dewormVourcher[] = DB::select($sql_child_dewom);

        $sql_child_voucher="SELECT
 sum(CASE when gender='MALE' AND voucher_given='YES'   then 1 ELSE  0 END ) as male,sum(CASE when  gender='FEMALE' AND voucher_given='YES'   then 1 ELSE  0 END )as female,
 sum(CASE when voucher_given='YES'   then 1 ELSE  0 END )as total
  FROM tbl_child_subsidized_voucher_registers INNER  JOIN tbl_child_registers ON tbl_child_registers.id=tbl_child_subsidized_voucher_registers.patient_id
  
 WHERE tbl_child_subsidized_voucher_registers.facility_id='$facility_id' and tbl_child_subsidized_voucher_registers.created_at BETWEEN '".$start_date."' and '".$end_date."' ";
        $dewormVourcher[] = DB::select($sql_child_voucher);
        $sql_child_exp="SELECT
 sum(CASE when gender='MALE' AND heid_no IS NOT  NULL  then 1 ELSE  0 END ) as male,sum(CASE when  gender='FEMALE' AND heid_no IS NOT  NULL   then 1 ELSE  0 END )as female,
 sum(CASE when heid_no IS NOT  NULL   then 1 ELSE  0 END )as total
  FROM tbl_child_hiv_expose_registers INNER  JOIN tbl_child_registers ON tbl_child_registers.id=tbl_child_hiv_expose_registers.patient_id
  
 WHERE tbl_child_hiv_expose_registers.facility_id='$facility_id' and tbl_child_hiv_expose_registers.created_at BETWEEN '".$start_date."' and '".$end_date."' ";
        $dewormVourcher[] = DB::select($sql_child_exp);
        $sql_albendazole= "SELECT
 sum(CASE when gender='MALE' AND vitamin_given='YES' and timestampdiff(month,dob,CURDATE())=6  then 1 ELSE  0 END ) as six_month_male,sum(CASE when  gender='FEMALE' AND vitamin_given='YES' AND timestampdiff(month,dob,CURDATE())=6    then 1 ELSE  0 END )as six_month_female,
 sum(CASE when vitamin_given='YES' AND timestampdiff(month,dob,CURDATE())=6   then 1 ELSE  0 END )as six_month_total,
 sum(CASE when gender='MALE' AND vitamin_given='YES' and timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END ) as one_year_male,sum(CASE when  gender='FEMALE' AND vitamin_given='YES' AND timestampdiff(month,dob,CURDATE())<12    then 1 ELSE  0 END )as one_year_female,
 sum(CASE when vitamin_given='YES' AND timestampdiff(month,dob,CURDATE())<12   then 1 ELSE  0 END )as one_year_total,
  sum(CASE when gender='MALE' AND vitamin_given='YES' and timestampdiff(month,dob,CURDATE())>=12 and timestampdiff(month,dob,CURDATE())<=160  then 1 ELSE  0 END ) as one_five_year_male,sum(CASE when  gender='FEMALE' AND vitamin_given='YES' AND   timestampdiff(month,dob,CURDATE())>=12 and timestampdiff(month,dob,CURDATE())<=160    then 1 ELSE  0 END )as one_five_year_female,
 sum(CASE when vitamin_given='YES' and timestampdiff(month,dob,CURDATE())>=12 and timestampdiff(month,dob,CURDATE())<=160   then 1 ELSE  0 END )as one_five_year_total
  FROM tbl_child_vitamin_deworm_registers INNER  JOIN tbl_child_registers ON tbl_child_registers.id=tbl_child_vitamin_deworm_registers.client_id
  
 WHERE tbl_child_vitamin_deworm_registers.facility_id='$facility_id' and tbl_child_vitamin_deworm_registers.created_at BETWEEN '".$start_date."' and '".$end_date."' ";
        $dewormVourcher[] = DB::select($sql_albendazole);

        $sql_child_pepopunda="SELECT
 sum(CASE when gender='MALE' AND  vaccination_id=1  then 1 ELSE  0 END ) as male,sum(CASE when  gender='FEMALE' AND vaccination_id=1    then 1 ELSE  0 END )as female,
 sum(CASE when vaccination_id=1  then 1 ELSE  0 END )as total
  FROM tbl_child_vaccination_registers INNER  JOIN tbl_child_registers ON tbl_child_registers.id=tbl_child_vaccination_registers.patient_id
  
 WHERE tbl_child_vaccination_registers.facility_id='$facility_id' and tbl_child_vaccination_registers.created_at BETWEEN '".$start_date."' and '".$end_date."' ";
        $dewormVourcher[] = DB::select($sql_child_pepopunda);

        $sql_child_bcg="SELECT
 sum(CASE when gender='MALE' AND  vaccination_id=2 AND place=1 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END ) as male_less_year_inplace,sum(CASE when  gender='FEMALE' AND vaccination_id=2  AND  place=1 AND timestampdiff(month,dob,CURDATE())<12 then 1 ELSE  0 END )as female_less_year_inplace,
 sum(CASE when vaccination_id=2 AND place=1 AND timestampdiff(month,dob,CURDATE())<12 then 1 ELSE  0 END )as total_less_year_inplace,
 sum(CASE when gender='MALE' AND  vaccination_id=2 AND place=2 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END ) as male_less_year_outplace,sum(CASE when  gender='FEMALE' AND vaccination_id=2  AND  place=2 AND timestampdiff(month,dob,CURDATE())<12 then 1 ELSE  0 END )as female_less_year_outplace,
 sum(CASE when vaccination_id=2 AND place=2 AND timestampdiff(month,dob,CURDATE())<12 then 1 ELSE  0 END )as total_less_year_outplace,
 
 sum(CASE when gender='MALE' AND  vaccination_id=2 AND place=1 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END ) as male_above_year_inplace,sum(CASE when  gender='FEMALE' AND vaccination_id=2  AND  place=1 AND timestampdiff(month,dob,CURDATE())>11 then 1 ELSE  0 END )as female_above_year_inplace,
 sum(CASE when vaccination_id=2 AND place=1 AND timestampdiff(month,dob,CURDATE())>11 then 1 ELSE  0 END )as total_above_year_inplace,
 sum(CASE when gender='MALE' AND  vaccination_id=2 AND place=2 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END ) as male_above_year_outplace,sum(CASE when  gender='FEMALE' AND vaccination_id=2  AND  place=2 AND timestampdiff(month,dob,CURDATE())>11 then 1 ELSE  0 END )as female_above_year_outplace,
 sum(CASE when vaccination_id=2 AND place=2 AND timestampdiff(month,dob,CURDATE())>11 then 1 ELSE  0 END )as total_above_year_outplace
  FROM tbl_child_vaccination_registers INNER  JOIN tbl_child_registers ON tbl_child_registers.id=tbl_child_vaccination_registers.patient_id
  
 WHERE tbl_child_vaccination_registers.facility_id='$facility_id' and tbl_child_vaccination_registers.created_at BETWEEN '".$start_date."' and '".$end_date."' ";
        $dewormVourcher[] = DB::select($sql_child_bcg);


        $sql_child_polio="SELECT
 
 sum(CASE when gender='MALE' AND  vaccination_id=4 AND place=1 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END ) as male_dose_0,sum(CASE when  gender='FEMALE' AND vaccination_id=4  AND place=1 AND timestampdiff(month,dob,CURDATE())<12   then 1 ELSE  0 END )as female_dose_0,
 sum(CASE when vaccination_id=4 AND place=1 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END )as total_dose_0,
 sum(CASE when gender='MALE' AND  vaccination_id=5 AND place=1 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END ) as male_dose_1,sum(CASE when  gender='FEMALE' AND vaccination_id=5 AND place=1 AND timestampdiff(month,dob,CURDATE())<12    then 1 ELSE  0 END )as female_dose_1,
 sum(CASE when vaccination_id=5 AND place=1 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END )as total_dose_1,
 sum(CASE when gender='MALE' AND  vaccination_id=6 AND place=1 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END ) as male_dose_2,sum(CASE when  gender='FEMALE' AND vaccination_id=6  AND place=1 AND timestampdiff(month,dob,CURDATE())<12   then 1 ELSE  0 END )as female_dose_2,
 sum(CASE when vaccination_id=6 AND place=1 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END )as total_dose_2,
 sum(CASE when gender='MALE' AND  vaccination_id=7 AND place=1 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END ) as male_dose_3,sum(CASE when  gender='FEMALE' AND vaccination_id=7 AND place=1 AND timestampdiff(month,dob,CURDATE())<12    then 1 ELSE  0 END )as female_dose_3,
 sum(CASE when vaccination_id=7 AND place=1 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END )as total_dose_3,
 
 
 sum(CASE when gender='MALE' AND  vaccination_id=5 AND place=2 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END ) as male_dose_1_out,sum(CASE when  gender='FEMALE' AND vaccination_id=5 AND place=2 AND timestampdiff(month,dob,CURDATE())<12    then 1 ELSE  0 END )as female_dose_1_out,
 sum(CASE when vaccination_id=5 AND place=2 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END )as total_dose_1_out,
 sum(CASE when gender='MALE' AND  vaccination_id=6 AND place=2 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END ) as male_dose_2_out,sum(CASE when  gender='FEMALE' AND vaccination_id=6  AND place=2 AND timestampdiff(month,dob,CURDATE())<12   then 1 ELSE  0 END )as female_dose_2_out,
 sum(CASE when vaccination_id=6 AND place=2 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END )as total_dose_2_out,
 sum(CASE when gender='MALE' AND  vaccination_id=7 AND place=2 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END ) as male_dose_3_out,sum(CASE when  gender='FEMALE' AND vaccination_id=7 AND place=2 AND timestampdiff(month,dob,CURDATE())<12    then 1 ELSE  0 END )as female_dose_3_out,
 sum(CASE when vaccination_id=7 AND place=2 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END )as total_dose_3_out,
 
 
 
 sum(CASE when gender='MALE' AND  vaccination_id=5 AND place=1 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END ) as above_year_male_dose_1,sum(CASE when  gender='FEMALE' AND vaccination_id=5 AND place=1 AND timestampdiff(month,dob,CURDATE())>11    then 1 ELSE  0 END )as above_year_female_dose_1,
 sum(CASE when vaccination_id=5 AND place=1 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END )as above_year_total_dose_1,
 sum(CASE when gender='MALE' AND  vaccination_id=6 AND place=1 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END ) as above_year_male_dose_2,sum(CASE when  gender='FEMALE' AND vaccination_id=6  AND place=1 AND timestampdiff(month,dob,CURDATE())>11   then 1 ELSE  0 END )as above_year_female_dose_2,
 sum(CASE when vaccination_id=6 AND place=1 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END )as above_year_total_dose_2,
 sum(CASE when gender='MALE' AND  vaccination_id=7 AND place=1 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END ) as above_year_male_dose_3,sum(CASE when  gender='FEMALE' AND vaccination_id=7 AND place=1 AND timestampdiff(month,dob,CURDATE())>11    then 1 ELSE  0 END )as above_year_female_dose_3,
 sum(CASE when vaccination_id=7 AND place=1 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END )as above_year_total_dose_3,
 
 sum(CASE when gender='MALE' AND  vaccination_id=5 AND place=2 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END ) as above_year_male_dose_1_out,sum(CASE when  gender='FEMALE' AND vaccination_id=5 AND place=2 AND timestampdiff(month,dob,CURDATE())>11    then 1 ELSE  0 END )as above_year_female_dose_1_out,
 sum(CASE when vaccination_id=5 AND place=2 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END )as above_year_total_dose_1_out,
 sum(CASE when gender='MALE' AND  vaccination_id=6 AND place=2 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END ) as above_year_male_dose_2_out,sum(CASE when  gender='FEMALE' AND vaccination_id=6  AND place=2 AND timestampdiff(month,dob,CURDATE())>11   then 1 ELSE  0 END )as above_year_female_dose_2_out,
 sum(CASE when vaccination_id=6 AND place=2 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END )as above_year_total_dose_2_out,
 sum(CASE when gender='MALE' AND  vaccination_id=7 AND place=2 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END ) as above_year_male_dose_3_out,sum(CASE when  gender='FEMALE' AND vaccination_id=7 AND place=2 AND timestampdiff(month,dob,CURDATE())>11    then 1 ELSE  0 END )as above_year_female_dose_3_out,
 sum(CASE when vaccination_id=7 AND place=2 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END )as above_year_total_dose_3_out,
 
 
 
 
 


  sum(CASE when gender='MALE' AND  vaccination_id=11 AND place=1 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END ) as male_penta_dose_1,sum(CASE when  gender='FEMALE' AND vaccination_id=11 AND place=1 AND timestampdiff(month,dob,CURDATE())<12    then 1 ELSE  0 END )as female_penta_dose_1,
 sum(CASE when vaccination_id=11 AND place=1 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END )as total_penta_dose_1,
 sum(CASE when gender='MALE' AND  vaccination_id=12 AND place=1 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END ) as male_penta_dose_2,sum(CASE when  gender='FEMALE' AND vaccination_id=12  AND place=1 AND timestampdiff(month,dob,CURDATE())<12   then 1 ELSE  0 END )as female_penta_dose_2,
 sum(CASE when vaccination_id=12 AND place=1 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END )as total_penta_dose_2,
 sum(CASE when gender='MALE' AND  vaccination_id=13 AND place=1 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END ) as male_penta_dose_3,sum(CASE when  gender='FEMALE' AND vaccination_id=13 AND place=1 AND timestampdiff(month,dob,CURDATE())<12    then 1 ELSE  0 END )as female_penta_dose_3,
 sum(CASE when vaccination_id=13 AND place=1 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END )as total_penta_dose_3,
 
 
 sum(CASE when gender='MALE' AND  vaccination_id=11 AND place=2 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END ) as male_penta_dose_1_out,sum(CASE when  gender='FEMALE' AND vaccination_id=11 AND place=2 AND timestampdiff(month,dob,CURDATE())<12    then 1 ELSE  0 END )as female_penta_dose_1_out,
 sum(CASE when vaccination_id=11 AND place=2 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END )as total_penta_dose_1_out,
 sum(CASE when gender='MALE' AND  vaccination_id=12 AND place=2 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END ) as male_penta_dose_2_out,sum(CASE when  gender='FEMALE' AND vaccination_id=12  AND place=2 AND timestampdiff(month,dob,CURDATE())<12   then 1 ELSE  0 END )as female_penta_dose_2_out,
 sum(CASE when vaccination_id=12 AND place=2 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END )as total_penta_dose_2_out,
 sum(CASE when gender='MALE' AND  vaccination_id=13 AND place=2 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END ) as male_penta_dose_3_out,sum(CASE when  gender='FEMALE' AND vaccination_id=13 AND place=2 AND timestampdiff(month,dob,CURDATE())<12    then 1 ELSE  0 END )as female_penta_dose_3_out,
 sum(CASE when vaccination_id=13 AND place=2 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END )as total_penta_dose_3_out,
 
 
 
 sum(CASE when gender='MALE' AND  vaccination_id=11 AND place=1 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END ) as above_year_penta_male_dose_1,sum(CASE when  gender='FEMALE' AND vaccination_id=11 AND place=1 AND timestampdiff(month,dob,CURDATE())>11    then 1 ELSE  0 END )as above_year_penta_female_dose_1,
 sum(CASE when vaccination_id=11 AND place=1 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END )as above_year_penta_total_dose_1,
 sum(CASE when gender='MALE' AND  vaccination_id=12 AND place=1 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END ) as above_year_penta_male_dose_2,sum(CASE when  gender='FEMALE' AND vaccination_id=12  AND place=1 AND timestampdiff(month,dob,CURDATE())>11   then 1 ELSE  0 END )as above_year_penta_female_dose_2,
 sum(CASE when vaccination_id=12 AND place=1 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END )as above_year_penta_total_dose_2,
 sum(CASE when gender='MALE' AND  vaccination_id=13 AND place=1 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END ) as above_year_penta_male_dose_3,sum(CASE when  gender='FEMALE' AND vaccination_id=13 AND place=1 AND timestampdiff(month,dob,CURDATE())>11    then 1 ELSE  0 END )as above_year_penta_female_dose_3,
 sum(CASE when vaccination_id=13 AND place=1 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END )as above_year_penta_total_dose_3,
 
 sum(CASE when gender='MALE' AND  vaccination_id=11 AND place=2 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END ) as above_year_penta_male_dose_1_out,sum(CASE when  gender='FEMALE' AND vaccination_id=11 AND place=2 AND timestampdiff(month,dob,CURDATE())>11    then 1 ELSE  0 END )as above_year_penta_female_dose_1_out,
 sum(CASE when vaccination_id=11 AND place=2 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END )as above_year_penta_total_dose_1_out,
 sum(CASE when gender='MALE' AND  vaccination_id=12 AND place=2 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END ) as above_year_penta_male_dose_2_out,sum(CASE when  gender='FEMALE' AND vaccination_id=12  AND place=2 AND timestampdiff(month,dob,CURDATE())>11   then 1 ELSE  0 END )as above_year_penta_female_dose_2_out,
 sum(CASE when vaccination_id=12 AND place=2 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END )as above_year_penta_total_dose_2_out,
 sum(CASE when gender='MALE' AND  vaccination_id=13 AND place=2 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END ) as above_year_penta_male_dose_3_out,sum(CASE when  gender='FEMALE' AND vaccination_id=13 AND place=2 AND timestampdiff(month,dob,CURDATE())>11    then 1 ELSE  0 END )as above_year_penta_female_dose_3_out,
 sum(CASE when vaccination_id=13 AND place=2 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END )as above_year_penta_total_dose_3_out,
 
 


 


 


  sum(CASE when gender='MALE' AND  vaccination_id=14 AND place=1 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END ) as male_pneu_dose_1,sum(CASE when  gender='FEMALE' AND vaccination_id=14 AND place=1 AND timestampdiff(month,dob,CURDATE())<12    then 1 ELSE  0 END )as female_pneu_dose_1,
 sum(CASE when vaccination_id=14 AND place=1 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END )as total_pneu_dose_1,
 sum(CASE when gender='MALE' AND  vaccination_id=15 AND place=1 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END ) as male_pneu_dose_2,sum(CASE when  gender='FEMALE' AND vaccination_id=15  AND place=1 AND timestampdiff(month,dob,CURDATE())<12   then 1 ELSE  0 END )as female_pneu_dose_2,
 sum(CASE when vaccination_id=15 AND place=1 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END )as total_pneu_dose_2,
 sum(CASE when gender='MALE' AND  vaccination_id=16 AND place=1 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END ) as male_pneu_dose_3,sum(CASE when  gender='FEMALE' AND vaccination_id=16 AND place=1 AND timestampdiff(month,dob,CURDATE())<12    then 1 ELSE  0 END )as female_pneu_dose_3,
 sum(CASE when vaccination_id=16 AND place=1 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END )as total_pneu_dose_3,
 
 
 sum(CASE when gender='MALE' AND  vaccination_id=14 AND place=2 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END ) as male_pneu_dose_1_out,sum(CASE when  gender='FEMALE' AND vaccination_id=14 AND place=2 AND timestampdiff(month,dob,CURDATE())<12    then 1 ELSE  0 END )as female_pneu_dose_1_out,
 sum(CASE when vaccination_id=14 AND place=2 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END )as total_pneu_dose_1_out,
 sum(CASE when gender='MALE' AND  vaccination_id=15 AND place=2 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END ) as male_pneu_dose_2_out,sum(CASE when  gender='FEMALE' AND vaccination_id=15  AND place=2 AND timestampdiff(month,dob,CURDATE())<12   then 1 ELSE  0 END )as female_pneu_dose_2_out,
 sum(CASE when vaccination_id=12 AND place=2 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END )as total_pneu_dose_2_out,
 sum(CASE when gender='MALE' AND  vaccination_id=16 AND place=2 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END ) as male_pneu_dose_3_out,sum(CASE when  gender='FEMALE' AND vaccination_id=16 AND place=2 AND timestampdiff(month,dob,CURDATE())<12    then 1 ELSE  0 END )as female_pneu_dose_3_out,
 sum(CASE when vaccination_id=16 AND place=2 AND timestampdiff(month,dob,CURDATE())<12  then 1 ELSE  0 END )as total_pneu_dose_3_out,
 
 
 
 sum(CASE when gender='MALE' AND  vaccination_id=14 AND place=1 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END ) as above_year_pneu_male_dose_1,sum(CASE when  gender='FEMALE' AND vaccination_id=14 AND place=1 AND timestampdiff(month,dob,CURDATE())>11    then 1 ELSE  0 END )as above_year_pneu_female_dose_1,
 sum(CASE when vaccination_id=11 AND place=1 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END )as above_year_pneu_total_dose_1,
 sum(CASE when gender='MALE' AND  vaccination_id=12 AND place=1 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END ) as above_year_pneu_male_dose_2,sum(CASE when  gender='FEMALE' AND vaccination_id=15  AND place=1 AND timestampdiff(month,dob,CURDATE())>11   then 1 ELSE  0 END )as above_year_pneu_female_dose_2,
 sum(CASE when vaccination_id=12 AND place=1 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END )as above_year_pneu_total_dose_2,
 sum(CASE when gender='MALE' AND  vaccination_id=13 AND place=1 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END ) as above_year_pneu_male_dose_3,sum(CASE when  gender='FEMALE' AND vaccination_id=16 AND place=1 AND timestampdiff(month,dob,CURDATE())>11    then 1 ELSE  0 END )as above_year_pneu_female_dose_3,
 sum(CASE when vaccination_id=13 AND place=1 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END )as above_year_pneu_total_dose_3,
 
 sum(CASE when gender='MALE' AND  vaccination_id=14 AND place=2 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END ) as above_year_pneu_male_dose_1_out,sum(CASE when  gender='FEMALE' AND vaccination_id=14 AND place=2 AND timestampdiff(month,dob,CURDATE())>11    then 1 ELSE  0 END )as above_year_pneu_female_dose_1_out,
 sum(CASE when vaccination_id=14 AND place=2 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END )as above_year_pneu_total_dose_1_out,
 sum(CASE when gender='MALE' AND  vaccination_id=15 AND place=2 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END ) as above_year_pneu_male_dose_2_out,sum(CASE when  gender='FEMALE' AND vaccination_id=15  AND place=2 AND timestampdiff(month,dob,CURDATE())>11   then 1 ELSE  0 END )as above_year_pneu_female_dose_2_out,
 sum(CASE when vaccination_id=15 AND place=2 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END )as above_year_pneu_total_dose_2_out,
 sum(CASE when gender='MALE' AND  vaccination_id=16 AND place=2 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END ) as above_year_pneu_male_dose_3_out,sum(CASE when  gender='FEMALE' AND vaccination_id=16 AND place=2 AND timestampdiff(month,dob,CURDATE())>11    then 1 ELSE  0 END )as above_year_pneu_female_dose_3_out,
 sum(CASE when vaccination_id=16 AND place=2 AND timestampdiff(month,dob,CURDATE())>11  then 1 ELSE  0 END )as above_year_pneu_total_dose_3_out,
 
 


 

 sum(CASE when gender='MALE' AND  vaccination_id=17 AND place=1 AND timestampdiff(month,dob,CURDATE())=9  then 1 ELSE  0 END ) as male_rubela_dose_1,sum(CASE when  gender='FEMALE' AND vaccination_id=17 AND place=1 AND timestampdiff(month,dob,CURDATE())=9    then 1 ELSE  0 END )as female_rubela_dose_1,
 sum(CASE when vaccination_id=17 AND place=1 AND timestampdiff(month,dob,CURDATE())=9  then 1 ELSE  0 END )as total_rubela_dose_1,
 sum(CASE when gender='MALE' AND  vaccination_id=17 AND place=2 AND timestampdiff(month,dob,CURDATE())=9  then 1 ELSE  0 END ) as male_rubela_dose_1_out,sum(CASE when  gender='FEMALE' AND vaccination_id=17 AND place=2 AND timestampdiff(month,dob,CURDATE())=9    then 1 ELSE  0 END )as female_rubela_dose_1_out,
 sum(CASE when vaccination_id=17 AND place=2 AND timestampdiff(month,dob,CURDATE())=9  then 1 ELSE  0 END )as total_rubela_dose_1_out,
  
sum(CASE when gender='MALE' AND  vaccination_id=18 AND place=1 AND timestampdiff(month,dob,CURDATE())=18  then 1 ELSE  0 END ) as male_rubela_dose_2,sum(CASE when  gender='FEMALE' AND vaccination_id=18 AND place=1 AND timestampdiff(month,dob,CURDATE())=18    then 1 ELSE  0 END )as female_rubela_dose_2,
 sum(CASE when vaccination_id=18 AND place=1 AND timestampdiff(month,dob,CURDATE())=18  then 1 ELSE  0 END )as total_rubela_dose_2,
 sum(CASE when gender='MALE' AND  vaccination_id=18 AND place=2 AND timestampdiff(month,dob,CURDATE())=18  then 1 ELSE  0 END ) as male_rubela_dose_2_out,sum(CASE when  gender='FEMALE' AND vaccination_id=18 AND place=2 AND timestampdiff(month,dob,CURDATE())=18    then 1 ELSE  0 END )as female_rubela_dose_2_out,
 sum(CASE when vaccination_id=18 AND place=2 AND timestampdiff(month,dob,CURDATE())=18  then 1 ELSE  0 END )as total_rubela_dose_2_out,
 
 
 
 
 sum(CASE when gender='MALE' AND  vaccination_id=5 AND place=1 AND timestampdiff(month,dob,CURDATE())=18  then 1 ELSE  0 END ) as male_polio_dose_1,sum(CASE when  gender='FEMALE' AND vaccination_id=5 AND place=1 AND timestampdiff(month,dob,CURDATE())=18    then 1 ELSE  0 END )as female_polio_dose_1,
 sum(CASE when vaccination_id=5 AND place=1 AND timestampdiff(month,dob,CURDATE())=18  then 1 ELSE  0 END )as total_polio_dose_1,
 sum(CASE when gender='MALE' AND  vaccination_id=5 AND place=2 AND timestampdiff(month,dob,CURDATE())=9  then 1 ELSE  0 END ) as male_polio_dose_1_out,sum(CASE when  gender='FEMALE' AND vaccination_id=5 AND place=2 AND timestampdiff(month,dob,CURDATE())=18    then 1 ELSE  0 END )as female_polio_dose_1_out,
 sum(CASE when vaccination_id=5 AND place=2 AND timestampdiff(month,dob,CURDATE())=18  then 1 ELSE  0 END )as total_polio_dose_1_out,
 
 
 sum(CASE when gender='MALE' AND  vaccination_id=9 AND place=1 AND timestampdiff(week,dob,CURDATE()) BETWEEN 6 and 15 then 1 ELSE  0 END ) as male_rota_dose_1,sum(CASE when  gender='FEMALE' AND vaccination_id=9 AND place=1 AND timestampdiff(week,dob,CURDATE())  BETWEEN 6 and 15    then 1 ELSE  0 END )as female_rota_dose_1,
 sum(CASE when vaccination_id=9 AND place=1 AND timestampdiff(week,dob,CURDATE())  BETWEEN 6 and 15  then 1 ELSE  0 END )as total_rota_dose_1,
 sum(CASE when gender='MALE' AND  vaccination_id=9 AND place=2 AND timestampdiff(week,dob,CURDATE())  BETWEEN 6 and 15  then 1 ELSE  0 END ) as male_rota_dose_1_out,sum(CASE when  gender='FEMALE' AND vaccination_id=9 AND place=2 AND timestampdiff(week,dob,CURDATE())  BETWEEN 6 and 15    then 1 ELSE  0 END )as female_rota_dose_1_out,
 sum(CASE when vaccination_id=9 AND place=2 AND timestampdiff(week,dob,CURDATE())  BETWEEN 6 and 15  then 1 ELSE  0 END )as total_rota_dose_1_out,
  
 
 sum(CASE when gender='MALE' AND  vaccination_id=10 AND place=1 AND timestampdiff(week,dob,CURDATE()) BETWEEN 10 and 32 then 1 ELSE  0 END ) as male_rota_dose_2,sum(CASE when  gender='FEMALE' AND vaccination_id=10 AND place=1 AND timestampdiff(week,dob,CURDATE())  BETWEEN 10 and 32    then 1 ELSE  0 END )as female_rota_dose_2,
 sum(CASE when vaccination_id=10 AND place=1 AND timestampdiff(week,dob,CURDATE())  BETWEEN 10 and 32  then 1 ELSE  0 END )as total_rota_dose_2,
 sum(CASE when gender='MALE' AND  vaccination_id=9 AND place=2 AND timestampdiff(week,dob,CURDATE())  BETWEEN 10 and 32  then 1 ELSE  0 END ) as male_rota_dose_2_out,sum(CASE when  gender='FEMALE' AND vaccination_id=10 AND place=2 AND timestampdiff(week,dob,CURDATE())  BETWEEN 10 and 32    then 1 ELSE  0 END )as female_rota_dose_2_out,
 sum(CASE when vaccination_id=10 AND place=2 AND timestampdiff(week,dob,CURDATE())  BETWEEN 10 and 32  then 1 ELSE  0 END )as total_rota_dose_2_out
  
  
 
  FROM tbl_child_vaccination_registers INNER  JOIN tbl_child_registers ON tbl_child_registers.id=tbl_child_vaccination_registers.patient_id
  INNER  JOIN tbl_vaccination_registers ON tbl_vaccination_registers.id=tbl_child_vaccination_registers.vaccination_id
  
 WHERE tbl_child_vaccination_registers.facility_id='$facility_id' and tbl_child_vaccination_registers.created_at BETWEEN '".$start_date."' and '".$end_date."' ";
        $dewormVourcher[] = DB::select($sql_child_polio);

        $sql_vct_ref="SELECT
 sum(CASE when gender='MALE'   then 1 ELSE  0 END ) as male,sum(CASE when  gender='FEMALE'     then 1 ELSE  0 END )as female,
 sum(CASE when gender='MALE' or gender='FEMALE'  then 1 ELSE  0 END )as total
   from tbl_child_registers inner JOIN tbl_accounts_numbers on tbl_accounts_numbers.patient_id=tbl_child_registers.patient_id
inner join tbl_clinic_instructions on tbl_clinic_instructions.visit_id= tbl_accounts_numbers.id 
  
 WHERE tbl_clinic_instructions.dept_id=8 AND tbl_accounts_numbers.facility_id='$facility_id' and tbl_clinic_instructions.created_at BETWEEN '".$start_date."' and '".$end_date."' GROUP by tbl_child_registers.patient_id ";

        $dewormVourcher[] = DB::select($sql_vct_ref);

        $sql_uzito_umri_less_year="SELECT
 sum(CASE when gender='MALE' AND (weightp >80 or weightz> -2)  then 1 ELSE  0 END ) as male_above_80,sum(CASE when  gender='FEMALE'  AND (weightp >80 or weightz> -2)   then 1 ELSE  0 END )as female_above_80,
 sum(CASE when gender='MALE' or gender='FEMALE' AND (weightp >80 or weightz> -2)  then 1 ELSE  0 END )as total_above_80,
   
    sum(CASE when gender='MALE' AND (weightp BETWEEN 60 and 80 or weightz BETWEEN -2 and -3)  then 1 ELSE  0 END ) as male_between_60_80,sum(CASE when  gender='FEMALE'  AND (weightp BETWEEN 60 and 80 or weightz BETWEEN -2 and -3)   then 1 ELSE  0 END )as female_between_60_80,
 sum(CASE when gender='MALE' or gender='FEMALE' AND (weightp BETWEEN 60 and 80 or weightz BETWEEN -2 and -3)  then 1 ELSE  0 END )as total_between_60_80,
   
   sum(CASE when gender='MALE' AND (weightp <60 or weightz< -3)  then 1 ELSE  0 END ) as male_less_60,sum(CASE when  gender='FEMALE'  AND (weightp <60 or weightz< -3)   then 1 ELSE  0 END )as female_less_60,
 sum(CASE when gender='MALE' or gender='FEMALE' AND (weightp <60 or weightz< -3)  then 1 ELSE  0 END )as total_less_60,
 
    sum(CASE when gender='MALE' AND  heightp >-2  then 1 ELSE  0 END ) as male_height_greater_2,sum(CASE when  gender='FEMALE'  AND heightp >-2   then 1 ELSE  0 END )as female_height_greater_2,
 sum(CASE when gender='MALE' or gender='FEMALE' AND heightp >-2  then 1 ELSE  0 END )as total_height_greater_2,
 
 sum(CASE when gender='MALE' AND  heightp BETWEEN -2 and -3  then 1 ELSE  0 END ) as male_height_between_2_3,sum(CASE when  gender='FEMALE'  AND heightp BETWEEN -2 and -3   then 1 ELSE  0 END )as female_height_between_2_3,
 sum(CASE when gender='MALE' or gender='FEMALE' AND heightp BETWEEN -2 and -3   then 1 ELSE  0 END )as total_height_between_2_3,
   
   sum(CASE when gender='MALE' AND  heightp < -3  then 1 ELSE  0 END ) as male_height_less_3,sum(CASE when  gender='FEMALE'  AND heightp < -3   then 1 ELSE  0 END )as female_height_less_3,
 sum(CASE when gender='MALE' or gender='FEMALE' AND heightp < -3   then 1 ELSE  0 END )as total_height_less_3
   
   from tbl_child_registers inner JOIN tbl_child_growth_registers on tbl_child_growth_registers.patient_id=tbl_child_registers.patient_id

 WHERE timestampdiff(MONTH ,dob,CURDATE())<12 AND tbl_child_growth_registers.facility_id='$facility_id' and tbl_child_growth_registers.created_at BETWEEN '".$start_date."' and '".$end_date."' GROUP by tbl_child_registers.patient_id ";
        $dewormVourcher[] = DB::select($sql_uzito_umri_less_year);

        $sql_uzito_umri_between_1_5="SELECT
 sum(CASE when gender='MALE' AND (weightp >80 or weightz> -2)  then 1 ELSE  0 END ) as male_above_80_1_5,sum(CASE when  gender='FEMALE'  AND (weightp >80 or weightz> -2)   then 1 ELSE  0 END )as female_above_80_1_5,
 sum(CASE when gender='MALE' or gender='FEMALE' AND (weightp >80 or weightz> -2)  then 1 ELSE  0 END )as total_above_80_1_5,
   
    sum(CASE when gender='MALE' AND (weightp BETWEEN 60 and 80 or weightz BETWEEN -2 and -3)  then 1 ELSE  0 END ) as male_between_60_80_1_5,sum(CASE when  gender='FEMALE'  AND (weightp BETWEEN 60 and 80 or weightz BETWEEN -2 and -3)   then 1 ELSE  0 END )as female_between_60_80_1_5,
 sum(CASE when gender='MALE' or gender='FEMALE' AND (weightp BETWEEN 60 and 80 or weightz BETWEEN -2 and -3)  then 1 ELSE  0 END )as total_between_60_80_1_5,
   
   sum(CASE when gender='MALE' AND (weightp <60 or weightz< -3)  then 1 ELSE  0 END ) as male_less_60_1_5,sum(CASE when  gender='FEMALE'  AND (weightp <60 or weightz< -3)   then 1 ELSE  0 END )as female_less_60_1_5,
 sum(CASE when gender='MALE' or gender='FEMALE' AND (weightp <60 or weightz< -3)  then 1 ELSE  0 END )as total_less_60_1_5,
 
    sum(CASE when gender='MALE' AND  heightp >-2  then 1 ELSE  0 END ) as male_height_greater_2_1_5,sum(CASE when  gender='FEMALE'  AND heightp >-2   then 1 ELSE  0 END )as female_height_greater_2_1_5,
 sum(CASE when gender='MALE' or gender='FEMALE' AND heightp >-2  then 1 ELSE  0 END )as total_height_greater_2_1_5,
 
 sum(CASE when gender='MALE' AND  heightp BETWEEN -2 and -3  then 1 ELSE  0 END ) as male_height_between_2_3_1_5,sum(CASE when  gender='FEMALE'  AND heightp BETWEEN -2 and -3   then 1 ELSE  0 END )as female_height_between_2_3_1_5,
 sum(CASE when gender='MALE' or gender='FEMALE' AND heightp BETWEEN -2 and -3   then 1 ELSE  0 END )as total_height_between_2_3_1_5,
   
   sum(CASE when gender='MALE' AND  heightp < -3  then 1 ELSE  0 END ) as male_height_less_3_1_5,sum(CASE when  gender='FEMALE'  AND heightp < -3   then 1 ELSE  0 END )as female_height_less_3_1_5,
 sum(CASE when gender='MALE' or gender='FEMALE' AND heightp < -3   then 1 ELSE  0 END )as total_height_less_3_1_5
   
   from tbl_child_registers inner JOIN tbl_child_growth_registers on tbl_child_growth_registers.patient_id=tbl_child_registers.patient_id

 WHERE timestampdiff(MONTH ,dob,CURDATE()) BETWEEN 1  and  60 AND tbl_child_growth_registers.facility_id='$facility_id' and tbl_child_growth_registers.created_at BETWEEN '".$start_date."' and '".$end_date."' GROUP by tbl_child_registers.patient_id ";
        $dewormVourcher[] = DB::select($sql_uzito_umri_between_1_5);

        return $dewormVourcher;

    }


    public function Anti_natl_mtuha(Request $request)
    {

        $antinal=[]  ;
        $facility_id=$request->facility_id;
        $start_date=$request->start_date;
        $end_date=$request->end_date;

        $sql_antinatal_less_12week="SELECT
 sum(CASE when timestampdiff(YEAR ,dob,CURDATE()) <20  then 1 ELSE  0 END ) as less_20,sum(CASE when  timestampdiff(YEAR ,dob,CURDATE()) >20 then 1 ELSE  0 END )as above_20,
 sum(CASE when timestampdiff(YEAR ,dob,CURDATE()) <20 or timestampdiff(YEAR ,dob,CURDATE()) >20  then 1 ELSE  0 END )as total
   from tbl_previous_pregnancy_infos  inner JOIN tbl_anti_natal_registers on tbl_previous_pregnancy_infos.client_id=tbl_anti_natal_registers.id
  
 WHERE timestampdiff(week ,lnmp,CURDATE())<12 and tbl_previous_pregnancy_infos.facility_id='$facility_id' and tbl_previous_pregnancy_infos.created_at BETWEEN '".$start_date."' and '".$end_date."' ";
        $antinal[] = DB::select($sql_antinatal_less_12week);

        $sql_antinatal_above_12week="SELECT
 sum(CASE when timestampdiff(YEAR ,dob,CURDATE()) <20  then 1 ELSE  0 END ) as less_20,sum(CASE when  timestampdiff(YEAR ,dob,CURDATE()) >20 then 1 ELSE  0 END )as above_20,
 sum(CASE when timestampdiff(YEAR ,dob,CURDATE()) <20 or timestampdiff(YEAR ,dob,CURDATE()) >20  then 1 ELSE  0 END )as total
   from tbl_previous_pregnancy_infos  inner JOIN tbl_anti_natal_registers on tbl_previous_pregnancy_infos.client_id=tbl_anti_natal_registers.id
  
 WHERE timestampdiff(week ,lnmp,CURDATE())>12 and tbl_previous_pregnancy_infos.facility_id='$facility_id' and tbl_previous_pregnancy_infos.created_at BETWEEN '".$start_date."' and '".$end_date."' ";
        $antinal[] = DB::select($sql_antinatal_above_12week);

        $sql_antinatal_total_less_above="SELECT
 sum(CASE when timestampdiff(YEAR ,dob,CURDATE()) <20 AND (timestampdiff(week ,lnmp,CURDATE())<12 or timestampdiff(week ,lnmp,CURDATE())>12) then 1 ELSE  0 END ) as total_less_20,sum(CASE when  timestampdiff(YEAR ,dob,CURDATE()) >20 AND (timestampdiff(week ,lnmp,CURDATE())<12 or timestampdiff(week ,lnmp,CURDATE())>12) then 1 ELSE  0 END )as total_above_20,
 sum(CASE when (timestampdiff(YEAR ,dob,CURDATE()) <20 or timestampdiff(YEAR ,dob,CURDATE()) >20) AND (timestampdiff(week ,lnmp,CURDATE())<12 or timestampdiff(week ,lnmp,CURDATE())>12)  then 1 ELSE  0 END )as total_a_b
   from tbl_previous_pregnancy_infos  inner JOIN tbl_anti_natal_registers on tbl_previous_pregnancy_infos.client_id=tbl_anti_natal_registers.id
  
 WHERE timestampdiff(week ,lnmp,CURDATE())>12 or timestampdiff(week ,lnmp,CURDATE())<12 and tbl_previous_pregnancy_infos.facility_id='$facility_id' and tbl_previous_pregnancy_infos.created_at BETWEEN '".$start_date."' and '".$end_date."' ";
        $antinal[] = DB::select($sql_antinatal_total_less_above);

        $Mimba_zaidi_ya_4="SELECT
 sum(CASE when  timestampdiff(YEAR ,dob,CURDATE()) >20     then 1 ELSE  0 END )as total
   from tbl_previous_pregnancy_infos  inner JOIN tbl_anti_natal_registers on tbl_previous_pregnancy_infos.client_id=tbl_anti_natal_registers.id
  
 WHERE  tbl_previous_pregnancy_infos.number_of_pregnancy>4 and tbl_previous_pregnancy_infos.facility_id='$facility_id' and tbl_previous_pregnancy_infos.created_at BETWEEN '".$start_date."' and '".$end_date."' ";
        $antinal[] = DB::select($Mimba_zaidi_ya_4);
        $Umri_chini_ya_miaka_20_above_35="SELECT
 sum(CASE when  timestampdiff(YEAR ,dob,CURDATE()) <20     then 1 ELSE  0 END )as total_less_20,
 sum(CASE when  timestampdiff(YEAR ,dob,CURDATE()) >35     then 1 ELSE  0 END )as total_above_35
   from tbl_previous_pregnancy_infos  inner JOIN tbl_anti_natal_registers on tbl_previous_pregnancy_infos.client_id=tbl_anti_natal_registers.id
  
 WHERE   tbl_previous_pregnancy_infos.facility_id='$facility_id' and tbl_previous_pregnancy_infos.created_at BETWEEN '".$start_date."' and '".$end_date."' ";
        $antinal[] = DB::select($Umri_chini_ya_miaka_20_above_35);

        $vidokezo_vya_hatari="SELECT
 sum(CASE when  hb<8.5  AND timestampdiff(YEAR ,dob,CURDATE()) <20  then 1 ELSE  0 END )as less_hb_less_20, sum(CASE when  hb<8.5  AND timestampdiff(YEAR ,dob,CURDATE()) >20   then 1 ELSE  0 END )as less_hb_above_20,
  sum(CASE when  hb<8.5  AND (timestampdiff(YEAR ,dob,CURDATE()) >20 OR timestampdiff(YEAR ,dob,CURDATE()) <20)  then 1 ELSE  0 END )as less_hb_total,
 sum(CASE when  bp<140  AND timestampdiff(YEAR ,dob,CURDATE()) <20  then 1 ELSE  0 END )as less_bp_less_20, sum(CASE when  bp<140  AND timestampdiff(YEAR ,dob,CURDATE()) >20   then 1 ELSE  0 END )as less_bp_above_20,
   sum(CASE when  bp<140  AND (timestampdiff(YEAR ,dob,CURDATE()) >20 OR timestampdiff(YEAR ,dob,CURDATE()) <20)  then 1 ELSE  0 END )as less_bp_total
   from tbl_anti_natal_attendances  inner JOIN tbl_anti_natal_registers on tbl_anti_natal_attendances.client_id=tbl_anti_natal_registers.id
  
 WHERE   tbl_anti_natal_attendances.facility_id='$facility_id' and tbl_anti_natal_attendances.created_at BETWEEN '".$start_date."' and '".$end_date."' ";
        $antinal[] = DB::select($vidokezo_vya_hatari);

        $tb="SELECT
  
 sum(CASE when tb='YES'  AND timestampdiff(YEAR ,dob,CURDATE()) <20  then 1 ELSE  0 END )as tb_less_20, sum(CASE when  tb='YES'  AND timestampdiff(YEAR ,dob,CURDATE()) >20   then 1 ELSE  0 END )as tb_above_20,
   sum(CASE when  tb='YES'  AND (timestampdiff(YEAR ,dob,CURDATE()) >20 OR timestampdiff(YEAR ,dob,CURDATE()) <20)  then 1 ELSE  0 END )as tb_total
   from  tbl_previous_pregnancy_indicators  inner JOIN tbl_anti_natal_registers on  tbl_previous_pregnancy_indicators.client_id=tbl_anti_natal_registers.id
  
 WHERE    tbl_previous_pregnancy_indicators.facility_id='$facility_id' and  tbl_previous_pregnancy_indicators.created_at BETWEEN '".$start_date."' and '".$end_date."' ";
        $antinal[] = DB::select($tb);

        $urine_sugar_protain="SELECT
 sum(CASE when  urine_sugar>0  AND timestampdiff(YEAR ,dob,CURDATE()) <20  then 1 ELSE  0 END )as urine_sugar_less_20, sum(CASE when  urine_sugar>0  AND timestampdiff(YEAR ,dob,CURDATE()) >20   then 1 ELSE  0 END )as urine_sugar_above_20,
  sum(CASE when  urine_sugar>0  AND (timestampdiff(YEAR ,dob,CURDATE()) >20 OR timestampdiff(YEAR ,dob,CURDATE()) <20)  then 1 ELSE  0 END )as urine_sugar_total,
 sum(CASE when  urine_albumin>0  AND timestampdiff(YEAR ,dob,CURDATE()) <20  then 1 ELSE  0 END )as urine_albumin_less_20, sum(CASE when  urine_albumin>0  AND timestampdiff(YEAR ,dob,CURDATE()) >20   then 1 ELSE  0 END )as urine_albumin_above_20,
   sum(CASE when  urine_albumin>0  AND (timestampdiff(YEAR ,dob,CURDATE()) >20 OR timestampdiff(YEAR ,dob,CURDATE()) <20)  then 1 ELSE  0 END )as urine_albumin_total
   from tbl_anti_natal_attendances  inner JOIN tbl_anti_natal_registers on tbl_anti_natal_attendances.client_id=tbl_anti_natal_registers.id
  
 WHERE   tbl_anti_natal_attendances.facility_id='$facility_id' and tbl_anti_natal_attendances.created_at BETWEEN '".$start_date."' and '".$end_date."' ";
        $antinal[] = DB::select($urine_sugar_protain);

        $mrdt_kaswende_voucher_no="SELECT
  
 sum(CASE when vdrl_rpr IS  not NULL  AND timestampdiff(YEAR ,dob,CURDATE()) <20  then 1 ELSE  0 END )as vdrl_rpr_less_20, sum(CASE when  vdrl_rpr IS not NULL  AND timestampdiff(YEAR ,dob,CURDATE()) >20   then 1 ELSE  0 END )as vdrl_rpr_above_20,
   sum(CASE when  vdrl_rpr IS not NULL  AND (timestampdiff(YEAR ,dob,CURDATE()) >20 OR timestampdiff(YEAR ,dob,CURDATE()) <20)  then 1 ELSE  0 END )as vdrl_rpr_total,
 
  sum(CASE when mrdt_bs ='MRDT'  AND timestampdiff(YEAR ,dob,CURDATE()) <20  then 1 ELSE  0 END )as mrdt_less_20, sum(CASE when  mrdt_bs ='MRDT'  AND timestampdiff(YEAR ,dob,CURDATE()) >20   then 1 ELSE  0 END )as mrdt_above_20,
   sum(CASE when  mrdt_bs ='MRDT'  AND (timestampdiff(YEAR ,dob,CURDATE()) >20 OR timestampdiff(YEAR ,dob,CURDATE()) <20)  then 1 ELSE  0 END )as mrdt_total,
 
  sum(CASE when result ='+ve'   AND timestampdiff(YEAR ,dob,CURDATE()) <20  then 1 ELSE  0 END )as malaria_less_20, sum(CASE when result ='+ve'  AND timestampdiff(YEAR ,dob,CURDATE()) >20   then 1 ELSE  0 END )as malaria_above_20,
   sum(CASE when  result ='+ve'  AND (timestampdiff(YEAR ,dob,CURDATE()) >20 OR timestampdiff(YEAR ,dob,CURDATE()) <20)  then 1 ELSE  0 END )as malaria_total,
  
  sum(CASE when voucher_no is not NULL   AND timestampdiff(YEAR ,dob,CURDATE()) <20  then 1 ELSE  0 END )as voucher_no_less_20, sum(CASE when voucher_no is not NULL  AND timestampdiff(YEAR ,dob,CURDATE()) >20   then 1 ELSE  0 END )as voucher_no_above_20,
   sum(CASE when  voucher_no is not NULL  AND (timestampdiff(YEAR ,dob,CURDATE()) >20 OR timestampdiff(YEAR ,dob,CURDATE()) <20)  then 1 ELSE  0 END )as voucher_no_total
  
   from  tbl_anti_natal_lab_tests  inner JOIN tbl_anti_natal_registers on  tbl_anti_natal_lab_tests.client_id=tbl_anti_natal_registers.id
  
 WHERE    tbl_anti_natal_lab_tests.facility_id='$facility_id' and  tbl_anti_natal_lab_tests.created_at BETWEEN '".$start_date."' and '".$end_date."' ";
        $antinal[] = DB::select($mrdt_kaswende_voucher_no);

        $ipts="SELECT

  sum(CASE when ipt ='IPT-2'  AND timestampdiff(YEAR ,dob,CURDATE()) <20  then 1 ELSE  0 END )as ipt2_less_20, sum(CASE when  ipt ='IPT-2'  AND timestampdiff(YEAR ,dob,CURDATE()) >20   then 1 ELSE  0 END )as ipt2_above_20,
   sum(CASE when ipt ='IPT-2'  AND (timestampdiff(YEAR ,dob,CURDATE()) >20 OR timestampdiff(YEAR ,dob,CURDATE()) <20)  then 1 ELSE  0 END )as ipt2_total,
 
  sum(CASE when ipt ='IPT-4'   AND timestampdiff(YEAR ,dob,CURDATE()) <20  then 1 ELSE  0 END )as ipt4_less_20, sum(CASE when ipt ='IPT-4'  AND timestampdiff(YEAR ,dob,CURDATE()) >20   then 1 ELSE  0 END )as ipt4_above_20,
   sum(CASE when  ipt ='IPT-4'  AND (timestampdiff(YEAR ,dob,CURDATE()) >20 OR timestampdiff(YEAR ,dob,CURDATE()) <20)  then 1 ELSE  0 END )as ipt4_total
  
   from  tbl_anti_natal_ipts  inner JOIN tbl_anti_natal_registers on  tbl_anti_natal_ipts.patient_id=tbl_anti_natal_registers.id
  
 WHERE    tbl_anti_natal_ipts.facility_id='$facility_id' and  tbl_anti_natal_ipts.created_at BETWEEN '".$start_date."' and '".$end_date."' ";
        $antinal[] = DB::select($ipts);

        $sql_vct_ref="SELECT
 sum(CASE when timestampdiff(YEAR ,dob,CURDATE()) <20   then 1 ELSE  0 END ) as male,sum(CASE when  timestampdiff(YEAR ,dob,CURDATE()) >20    then 1 ELSE  0 END )as female,
 sum(CASE when timestampdiff(YEAR ,dob,CURDATE()) <20 or timestampdiff(YEAR ,dob,CURDATE()) >20  then 1 ELSE  0 END )as total
   from tbl_anti_natal_registers inner JOIN tbl_accounts_numbers on tbl_accounts_numbers.patient_id=tbl_anti_natal_registers.client_id
inner join tbl_clinic_instructions on tbl_clinic_instructions.visit_id= tbl_accounts_numbers.id 
  
 WHERE tbl_clinic_instructions.dept_id=8 AND tbl_accounts_numbers.facility_id='$facility_id' and tbl_clinic_instructions.created_at BETWEEN '".$start_date."' and '".$end_date."' GROUP by tbl_anti_natal_registers.client_id ";

        $antinal[] = DB::select($sql_vct_ref);

        $deworms="SELECT
  sum(CASE when deworm ='YES'   AND timestampdiff(YEAR ,dob,CURDATE()) <20  then 1 ELSE  0 END )as deworm_less_20, sum(CASE when deworm ='YES'  AND timestampdiff(YEAR ,dob,CURDATE()) >20   then 1 ELSE  0 END )as deworm_above_20,
   sum(CASE when  deworm  ='YES'  AND (timestampdiff(YEAR ,dob,CURDATE()) >20 OR timestampdiff(YEAR ,dob,CURDATE()) <20)  then 1 ELSE  0 END )as deworm_total,
  
  sum(CASE when folic_acid ='YES'   AND timestampdiff(YEAR ,dob,CURDATE()) <20  then 1 ELSE  0 END )as folic_less_20, sum(CASE when folic_acid ='YES'  AND timestampdiff(YEAR ,dob,CURDATE()) >20   then 1 ELSE  0 END )as folic_above_20,
   sum(CASE when  folic_acid  ='YES'  AND (timestampdiff(YEAR ,dob,CURDATE()) >20 OR timestampdiff(YEAR ,dob,CURDATE()) <20)  then 1 ELSE  0 END )as folic_total
  
   from  tbl_anti_natal_preventives  inner JOIN tbl_anti_natal_registers on  tbl_anti_natal_preventives.client_id=tbl_anti_natal_registers.id
  
 WHERE    tbl_anti_natal_preventives.facility_id='$facility_id' and  tbl_anti_natal_preventives.created_at BETWEEN '".$start_date."' and '".$end_date."' group by tbl_anti_natal_preventives.client_id ";
        $antinal[] = DB::select($deworms);

        return $antinal;



    }

   

 

public function Tb_mtuha(Request $request)
    {
        $facility_id=$request->facility_id;
        $start_date=$request->start_date;
        $end_date=$request->end_date;
        //return $request->all();

$tb=[];
        $tbAttendance="SELECT DISTINCT 
  ifnull(sum(CASE when gender ='MALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') <5  then 1 ELSE  0 END ),0) as male_less_5, ifnull(sum(CASE when gender ='FEMALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') <5  then 1 ELSE  0 END ),0) as female_less_5,
  ifnull(sum(CASE when gender ='MALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') >5  then 1 ELSE  0 END ),0) as male_above_5, ifnull(sum(CASE when gender ='FEMALE'   AND timestampdiff(YEAR ,dob,'".$end_date."')>5  then 1 ELSE  0 END ),0) as female_above_5,
  ifnull(sum(CASE when (gender ='MALE' or gender ='FEMALE') AND (timestampdiff(YEAR ,dob,'".$end_date."') <5 or timestampdiff(YEAR ,dob,'".$end_date."') >5 ) then 1 ELSE  0 END ),0) as total
   from   tbl_tb_pre_entry_registers  inner JOIN tbl_patients on   tbl_tb_pre_entry_registers.client_id=tbl_patients.id
 WHERE    tbl_tb_pre_entry_registers.facility_id='$facility_id' and  tbl_tb_pre_entry_registers.created_at BETWEEN '".$start_date."' and '".$end_date."'  ";
        $tb[] = DB::select($tbAttendance);

        $TBPatientTestingForHIV="SELECT DISTINCT 
  ifnull(sum(CASE when gender ='MALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') <5  then 1 ELSE  0 END ),0) as male_less_5, ifnull(sum(CASE when gender ='FEMALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') <5  then 1 ELSE  0 END ),0) as female_less_5,
  ifnull(sum(CASE when gender ='MALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') >5  then 1 ELSE  0 END ),0) as male_above_5, ifnull(sum(CASE when gender ='FEMALE'   AND timestampdiff(YEAR ,dob,'".$end_date."')>5  then 1 ELSE  0 END ),0) as female_above_5,
  ifnull(sum(CASE when (gender ='MALE' or gender ='FEMALE') AND (timestampdiff(YEAR ,dob,'".$end_date."') <5 or timestampdiff(YEAR ,dob,'".$end_date."') >5 ) then 1 ELSE  0 END ),0) as total
   from   tbl_vct_registers  inner JOIN tbl_patients on   tbl_vct_registers.client_id=tbl_patients.id
 WHERE vvu_test_result is not NULL  AND  tbl_vct_registers.facility_id='$facility_id' and  tbl_vct_registers.created_at BETWEEN '".$start_date."' and '".$end_date."'  ";
        $tb[] = DB::select($TBPatientTestingForHIV);

        $TBatientHIVPositive="SELECT DISTINCT 
  ifnull(sum(CASE when gender ='MALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') <5  then 1 ELSE  0 END ),0) as male_less_5, ifnull(sum(CASE when gender ='FEMALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') <5  then 1 ELSE  0 END ),0) as female_less_5,
  ifnull(sum(CASE when gender ='MALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') >5  then 1 ELSE  0 END ),0) as male_above_5, ifnull(sum(CASE when gender ='FEMALE'   AND timestampdiff(YEAR ,dob,'".$end_date."')>5  then 1 ELSE  0 END ),0) as female_above_5,
  ifnull(sum(CASE when (gender ='MALE' or gender ='FEMALE') AND (timestampdiff(YEAR ,dob,'".$end_date."') <5 or timestampdiff(YEAR ,dob,'".$end_date."') >5 ) then 1 ELSE  0 END ),0) as total
   from   tbl_vct_registers  inner JOIN tbl_tb_patient_treatment_types on   tbl_vct_registers.client_id=tbl_tb_patient_treatment_types.client_id 
   inner JOIN tbl_patients on   tbl_tb_patient_treatment_types.client_id=tbl_patients.id
 WHERE  vvu_test_result='POSITIVE' AND  tbl_vct_registers.facility_id='$facility_id' and  tbl_vct_registers.created_at BETWEEN '".$start_date."' and '".$end_date."'  ";
        $tb[] = DB::select($TBatientHIVPositive);
        $TBPatientReferredtoCTC="SELECT DISTINCT 
  ifnull(sum(CASE when gender ='MALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') <5  then 1 ELSE  0 END ),0) as male_less_5, ifnull(sum(CASE when gender ='FEMALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') <5  then 1 ELSE  0 END ),0) as female_less_5,
  ifnull(sum(CASE when gender ='MALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') >5  then 1 ELSE  0 END ),0) as male_above_5, ifnull(sum(CASE when gender ='FEMALE'   AND timestampdiff(YEAR ,dob,'".$end_date."')>5  then 1 ELSE  0 END ),0) as female_above_5,
  ifnull(sum(CASE when (gender ='MALE' or gender ='FEMALE') AND (timestampdiff(YEAR ,dob,'".$end_date."') <5 or timestampdiff(YEAR ,dob,'".$end_date."') >5 ) then 1 ELSE  0 END ),0) as total
   from   tbl_tb_pre_entry_registers  inner JOIN tbl_accounts_numbers on tbl_accounts_numbers.patient_id=tbl_tb_pre_entry_registers.client_id
inner join tbl_clinic_instructions on tbl_clinic_instructions.visit_id= tbl_accounts_numbers.id 
inner JOIN tbl_patients on   tbl_tb_pre_entry_registers.client_id=tbl_patients.id
 WHERE tbl_clinic_instructions.dept_id=8  AND tbl_tb_pre_entry_registers.facility_id='$facility_id' and  tbl_tb_pre_entry_registers.created_at BETWEEN '".$start_date."' and '".$end_date."'  ";

        $tb[] = DB::select($TBPatientReferredtoCTC);

        $TBPatientRegisteredatCTC="SELECT DISTINCT 
  ifnull(sum(CASE when gender ='MALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') <5  then 1 ELSE  0 END ),0) as male_less_5, ifnull(sum(CASE when gender ='FEMALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') <5  then 1 ELSE  0 END ),0) as female_less_5,
  ifnull(sum(CASE when gender ='MALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') >5  then 1 ELSE  0 END ),0) as male_above_5, ifnull(sum(CASE when gender ='FEMALE'   AND timestampdiff(YEAR ,dob,'".$end_date."')>5  then 1 ELSE  0 END ),0) as female_above_5,
  ifnull(sum(CASE when (gender ='MALE' or gender ='FEMALE') AND (timestampdiff(YEAR ,dob,'".$end_date."') <5 or timestampdiff(YEAR ,dob,'".$end_date."') >5 ) then 1 ELSE  0 END ),0) as total
  from   tbl_tb_pre_entry_registers  inner JOIN tbl_accounts_numbers on tbl_accounts_numbers.patient_id=tbl_tb_pre_entry_registers.client_id
inner join tbl_clinic_instructions on tbl_clinic_instructions.visit_id= tbl_accounts_numbers.id 
inner JOIN tbl_patients on   tbl_tb_pre_entry_registers.client_id=tbl_patients.id
 WHERE tbl_clinic_instructions.dept_id=15  AND tbl_tb_pre_entry_registers.facility_id='$facility_id' and  tbl_tb_pre_entry_registers.created_at BETWEEN '".$start_date."' and '".$end_date."'  ";

        $tb[] = DB::select($TBPatientRegisteredatCTC);

        $ReceivingCPT="SELECT DISTINCT 
  ifnull(sum(CASE when gender ='MALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') <5  then 1 ELSE  0 END ),0) as male_less_5, ifnull(sum(CASE when gender ='FEMALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') <5  then 1 ELSE  0 END ),0) as female_less_5,
  ifnull(sum(CASE when gender ='MALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') >5  then 1 ELSE  0 END ),0) as male_above_5, ifnull(sum(CASE when gender ='FEMALE'   AND timestampdiff(YEAR ,dob,'".$end_date."')>5  then 1 ELSE  0 END ),0) as female_above_5,
  ifnull(sum(CASE when (gender ='MALE' or gender ='FEMALE') AND (timestampdiff(YEAR ,dob,'".$end_date."') <5 or timestampdiff(YEAR ,dob,'".$end_date."') >5 ) then 1 ELSE  0 END ),0) as total
   from   tbl_tb_vvu_services  inner JOIN tbl_patients on   tbl_tb_vvu_services.client_id=tbl_patients.id
 WHERE  cpt='YES' AND  tbl_tb_vvu_services.facility_id='$facility_id' and  tbl_tb_vvu_services.created_at BETWEEN '".$start_date."' and '".$end_date."'  ";
        $tb[] = DB::select($ReceivingCPT);

        $ReceivingART="SELECT DISTINCT 
  ifnull(sum(CASE when gender ='MALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') <5  then 1 ELSE  0 END ),0) as male_less_5, ifnull(sum(CASE when gender ='FEMALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') <5  then 1 ELSE  0 END ),0) as female_less_5,
  ifnull(sum(CASE when gender ='MALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') >5  then 1 ELSE  0 END ),0) as male_above_5, ifnull(sum(CASE when gender ='FEMALE'   AND timestampdiff(YEAR ,dob,'".$end_date."')>5  then 1 ELSE  0 END ),0) as female_above_5,
  ifnull(sum(CASE when (gender ='MALE' or gender ='FEMALE') AND (timestampdiff(YEAR ,dob,'".$end_date."') <5 or timestampdiff(YEAR ,dob,'".$end_date."') >5 ) then 1 ELSE  0 END ),0) as total
   from   tbl_tb_vvu_services  inner JOIN tbl_patients on   tbl_tb_vvu_services.client_id=tbl_patients.id
 WHERE  art_start_date IS NOT  NULL AND  tbl_tb_vvu_services.facility_id='$facility_id' and  tbl_tb_vvu_services.created_at BETWEEN '".$start_date."' and '".$end_date."'  ";
        $tb[] = DB::select($ReceivingART);

        $Patientsonanti_TBfromCTC
            = "SELECT DISTINCT 
  ifnull(sum(CASE when gender ='MALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') <5  then 1 ELSE  0 END ),0) as male_less_5, ifnull(sum(CASE when gender ='FEMALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') <5  then 1 ELSE  0 END ),0) as female_less_5,
  ifnull(sum(CASE when gender ='MALE'   AND timestampdiff(YEAR ,dob,'".$end_date."') >5  then 1 ELSE  0 END ),0) as male_above_5, ifnull(sum(CASE when gender ='FEMALE'   AND timestampdiff(YEAR ,dob,'".$end_date."')>5  then 1 ELSE  0 END ),0) as female_above_5,
  ifnull(sum(CASE when (gender ='MALE' or gender ='FEMALE') AND (timestampdiff(YEAR ,dob,'".$end_date."') <5 or timestampdiff(YEAR ,dob,'".$end_date."') >5 ) then 1 ELSE  0 END ),0) as total
  from   tbl_tb_patient_treatment_types  inner JOIN tbl_accounts_numbers on tbl_accounts_numbers.patient_id=tbl_tb_patient_treatment_types.client_id
inner join tbl_clinic_instructions on tbl_clinic_instructions.visit_id= tbl_accounts_numbers.id 
inner JOIN tbl_patients on   tbl_tb_patient_treatment_types.client_id=tbl_patients.id
 WHERE tbl_clinic_instructions.dept_id=15  AND tbl_tb_patient_treatment_types.facility_id='$facility_id' and  tbl_tb_patient_treatment_types.created_at BETWEEN '".$start_date."' and '".$end_date."'  ";

        $tb[] = DB::select($Patientsonanti_TBfromCTC);


        return $tb;
    }
}
