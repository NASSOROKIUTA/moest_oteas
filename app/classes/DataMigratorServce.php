<?php
namespace App\classes;
require("DataSnch.php"); 

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
    
    public function SendRequest($ServiceCode,$TelephoneNo,$RequestContent)
    {
        $request=BASE_URL.'claims/SubmitFolios';

        /*
        $post_data = array(
                        'ServiceCode'=>$ServiceCode,
                        'TelephoneNo'=>$TelephoneNo,
                        'RequestContent'=>$RequestContent
                        );

        $data_string=json_encode($post_data);
        */
        $data_string= file_get_contents('Folios.json');

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

    public static function AuthorizeCard($CardNo,$FacilityCode,$UserName)
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

    public static function SubmitFolios($data_string)
    {
         $request=BASE_URL_EMR.'api/send_folio';
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
