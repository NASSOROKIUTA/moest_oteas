<?php
namespace App\classes;
require("constants.php"); 
use DB;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ServiceManager
 *
 * @author arashid
 */
class ServiceManager {
    
    public function SendRequest($data_string)
    {
        $request=BASE_URL.'claims/SubmitFolios';

       
        //echo $data_string;
        $ch = curl_init($request);

        $request_method = 'POST';
        $nonce=uniqid("",true);
        // calculate the hash
        $timestamp=strval(time());
        $data_hash=base64_encode(md5($data_string, true));
        $signature_raw_data=PUBLIC_KEY.$request_method.$timestamp.$nonce.$data_hash;
        $hash = hash_hmac ('sha256', $signature_raw_data, PRIVATE_KEY,$raw=true);
        $signature = base64_encode($hash);
        $amx=PUBLIC_KEY.':'.$signature.':'.$nonce.':'.$timestamp;
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($data_string),
          'Authorization: amx '.$amx
           ));
        $result = curl_exec($ch);
        $result = trim($result,"\"");
        $StatusCode =  curl_getinfo($ch,CURLINFO_HTTP_CODE);

        $array_data = array();
        $array_data['StatusCode'] = $StatusCode;
        $array_data['Message'] = $result;
        $result = json_encode($array_data);

        curl_close($ch);

        return $result;
         
    }

    public static function AuthorizeCard($CardNo,$FacilityCode,$UserName,$patient_id)
    {
        

        $request=BASE_URL.'verification/AuthorizeCard?CardNo='.$CardNo.'&FacilityCode='.$FacilityCode.'&UserName='.$UserName;       
        $ch = curl_init($request);
        $request_method = 'GET';
        $nonce=uniqid("",true);
        // calculate the hash
        $timestamp=strval(time());
        $data_hash='';
        $signature_raw_data=PUBLIC_KEY.$request_method.$timestamp.$nonce.$data_hash;
        $hash = hash_hmac ('sha256', $signature_raw_data, PRIVATE_KEY,$raw=true);
        $signature = base64_encode($hash);
        $amx=PUBLIC_KEY.':'.$signature.':'.$nonce.':'.$timestamp;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: amx '.$amx));
        $result = curl_exec($ch);
        $StatusCode=  curl_getinfo($ch,CURLINFO_HTTP_CODE);

        if($StatusCode == 200){
            $array_data = json_decode($result,true);
            $array_data['StatusCode'] = $StatusCode;
            $result = json_encode($array_data);
			if(!empty($patient_id)){
			 $LastName=$array_data['LastName'];
			 $MiddleName=$array_data['MiddleName'];
			 $FirstName=$array_data['FirstName'];
			 $DateOfBirth=$array_data['DateOfBirth'];
			 $CardNo=$array_data['CardNo'];
			 $MembershipNo=$array_data['MembershipNo'];
			 $AuthorizationNo=$array_data['AuthorizationNo'];
			
			 $sql="SELECT t2.*,t1.id AS account_id FROM tbl_accounts_numbers t1,tbl_patients t2 WHERE t1.patient_id='".$patient_id."' AND t1.patient_id=t2.id ORDER BY t1.created_at DESC LIMIT 1";
			 $patient=DB::SELECT($sql);
			 
			 if(count($patient) > 0 ){
			 $first_name=strtoupper($patient[0]->first_name);
			 $FirstName=strtoupper($FirstName);
			 
			 if(strcmp($FirstName,$first_name)!=0)
			 {
				  return response()->json([
                'data' => $first_name.', Patient Information from NHIF and that saved in GoT-HoMIS never match, '.$LastName,
                'StatusCode' => 101
            ]);
			 }
			 
			 $last_name=$patient[0]->last_name;
			 $dob=$patient[0]->dob;
			 $account_id=$patient[0]->account_id;
  $update="UPDATE tbl_accounts_numbers t1 SET t1.card_no='".$CardNo."',t1.membership_number='".$MembershipNo."',
     t1.authorization_number='".$AuthorizationNo."' WHERE t1.id='".$account_id."'";
                DB::statement($update);	 
				 
			 }
			 
			return response()->json([
                'data' => $last_name.', was assigned '.$CardNo.' as card number',
                'StatusCode' => 102
            ]);
			 
				return $result;
		}
        }else{
            $array_data = array();
            $array_data['StatusCode'] = $StatusCode;
            $array_data['Message'] = $result;
            $result = json_encode($array_data);
        }
		
        curl_close($ch);
		
		
        return $result;
    }


 public static function getPricePackage($FacilityCode,$UserName)
    {


        $request=BASE_URL.'Packages/GetPricePackage?FacilityCode='.$FacilityCode;
        $ch = curl_init($request);
        $request_method = 'GET';
        $nonce=uniqid("",true);
        // calculate the hash
        $timestamp=strval(time());
        $data_hash='';
        $signature_raw_data=PUBLIC_KEY.$request_method.$timestamp.$nonce.$data_hash;
        $hash = hash_hmac ('sha256', $signature_raw_data, PRIVATE_KEY,$raw=true);
        $signature = base64_encode($hash);
        $amx=PUBLIC_KEY.':'.$signature.':'.$nonce.':'.$timestamp;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: amx '.$amx));
        $result = curl_exec($ch);
        $StatusCode=  curl_getinfo($ch,CURLINFO_HTTP_CODE);
         if($StatusCode == 200){
            $array_data = json_decode($result,true);
            $array_data['StatusCode'] = $StatusCode;
            $result = json_encode($array_data);
        }else{
            $array_data = array();
            $array_data['StatusCode'] = $StatusCode;
            $array_data['Message'] = $result;
            $result = json_encode($array_data);
        }

        curl_close($ch);


        return $result;





    }
	
	 public static function sendFolios($data_string)
    {
         $request=BASE_URL.'claims/SubmitFolios';
       // $data_string= file_get_contents('Folios.json');

        //echo $data_string;
        $ch = curl_init($request);

        $request_method = 'POST';
        $nonce=uniqid("",true);
        // calculate the hash
        $timestamp=strval(time());
        $data_hash=base64_encode(md5($data_string, true));
        $signature_raw_data=PUBLIC_KEY.$request_method.$timestamp.$nonce.$data_hash;
        $hash = hash_hmac ('sha256', $signature_raw_data, PRIVATE_KEY,$raw=true);
        $signature = base64_encode($hash);
        $amx=PUBLIC_KEY.':'.$signature.':'.$nonce.':'.$timestamp;
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($data_string),
          'Authorization: amx '.$amx
           ));
        $result = curl_exec($ch);
        $result = trim($result,"\"");
        $StatusCode =  curl_getinfo($ch,CURLINFO_HTTP_CODE);

        $array_data = array();
        $array_data['StatusCode'] = $StatusCode;
        $array_data['Message'] = $result;
        $result = json_encode($array_data);

        curl_close($ch);

        return $result;
    }
	

    public static function SubmitFolios($data_string)
    {
         $request=BASE_URL_EMR.'api/send_folio';
       // $data_string= file_get_contents('Folios.json');
	   return $request;

        //echo $data_string;
        $ch = curl_init($request);

        $request_method = 'POST';
        $nonce=uniqid("",true);
        // calculate the hash
        $timestamp=strval(time());
        $data_hash=base64_encode(md5($data_string, true));
        $signature_raw_data=PUBLIC_KEY.$request_method.$timestamp.$nonce.$data_hash;
        $hash = hash_hmac ('sha256', $signature_raw_data, PRIVATE_KEY,$raw=true);
        $signature = base64_encode($hash);
        $amx=PUBLIC_KEY.':'.$signature.':'.$nonce.':'.$timestamp;
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($data_string),
          'Authorization: amx '.$amx
           ));
        $result = curl_exec($ch);
        $result = trim($result,"\"");
        $StatusCode =  curl_getinfo($ch,CURLINFO_HTTP_CODE);

        $array_data = array();
        $array_data['StatusCode'] = $StatusCode;
        $array_data['Message'] = $result;
        $result = json_encode($array_data);

        curl_close($ch);

        return $result;
    }
	
	
     public static function sendExtRefferal($data_string)
    {
         $request=BASE_URL.'claims/SubmitFolios';
       // $data_string= file_get_contents('Folios.json');

        //echo $data_string;
        $ch = curl_init($request);

        $request_method = 'POST';
        $nonce=uniqid("",true);
        // calculate the hash
        $timestamp=strval(time());
        $data_hash=base64_encode(md5($data_string, true));
        $signature_raw_data=PUBLIC_KEY.$request_method.$timestamp.$nonce.$data_hash;
        $hash = hash_hmac ('sha256', $signature_raw_data, PRIVATE_KEY,$raw=true);
        $signature = base64_encode($hash);
        $amx=PUBLIC_KEY.':'.$signature.':'.$nonce.':'.$timestamp;
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($data_string),
          'Authorization: amx '.$amx
           ));
        $result = curl_exec($ch);
        $result = trim($result,"\"");
        $StatusCode =  curl_getinfo($ch,CURLINFO_HTTP_CODE);

        $array_data = array();
        $array_data['StatusCode'] = $StatusCode;
        $array_data['Message'] = $result;
        $result = json_encode($array_data);

        curl_close($ch);

        return $result;
    }
    
    public function GetDetails($CardNo)
    {
        

        $request=BASE_URL.'verification/AuthorizeCard?CardNo='.$CardNo.'&UserName=test';       
        $ch = curl_init($request);
        $request_method = 'GET';
        $nonce=uniqid("",true);
        // calculate the hash
        $timestamp=strval(time());
        $data_hash='';
        $signature_raw_data=PUBLIC_KEY.$request_method.$timestamp.$nonce.$data_hash;
        $hash = hash_hmac ('sha256', $signature_raw_data, PRIVATE_KEY,$raw=true);
        $signature = base64_encode($hash);
        $amx=PUBLIC_KEY.':'.$signature.':'.$nonce.':'.$timestamp;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: amx '.$amx));
        $result = curl_exec($ch);
        $StatusCode=  curl_getinfo($ch,CURLINFO_HTTP_CODE);

        if($StatusCode == 200){
            $array_data = json_decode($result,true);
            $array_data['StatusCode'] = $StatusCode;
            $result = json_encode($array_data);
        }else{
            $array_data = array();
            $array_data['StatusCode'] = $StatusCode;
            $array_data['Message'] = $result;
            $result = json_encode($array_data);
        }

        curl_close($ch);
        return $result;
    }
    
    public function Authenticate($TelephoneNo,$ServiceCode)
    {
        $request=BASE_URL.'verification/authenticate?TelephoneNo='.$TelephoneNo.'&ServiceCode='.$ServiceCode;
        $ch = curl_init($request);
        $request_method = 'GET';
        $nonce=uniqid("",true);
        // calculate the hash
        $timestamp=strval(time());
        $data_hash='';
        $signature_raw_data=PUBLIC_KEY.$request_method.$timestamp.$nonce.$data_hash;
        $hash = hash_hmac ('sha256', $signature_raw_data, PRIVATE_KEY,$raw=true);
        $signature = base64_encode($hash);
        $amx=PUBLIC_KEY.':'.$signature.':'.$nonce.':'.$timestamp;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: amx '.$amx));
        $result = curl_exec($ch);
        //$StatusCode=  curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $result;
    }

    public function TestPost()
    {
        $data = array("name" => "Hagrid", "age" => "36");                                                                    
        $data_string = json_encode($data);                                                                                   
                                                                                                                     
        $ch = curl_init('http://api.local/rest/users');                                                                      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($data_string))                                                                       
        );                                                                                                                   
                                                                                                                     
        $result = curl_exec($ch);
        echo($resu);
    }
}
