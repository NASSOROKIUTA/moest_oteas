<?php

use Illuminate\Contracts\Routing\ResponseFactory;

if (!function_exists('checkDuplicate')) {
	
   function checkDuplicate($table,$fields, $values, $updating=false,  $updatingKey=0, $primaryKey='id'){
			$query = "select count(*) as count from $table where ";
			for($field = 0; $field < count($fields); $field++)
				$query .= $fields[$field] .((strpos($fields[$field], "))") > 0) ? "" : "= '".$values[$field]."'"). (($field+1) < count($fields) ? " and " : "");
		
			if($updating)
				$query .= " and $primaryKey <> '$updatingKey'";
			try{
				$result = DB::select($query);
				if($result[0]->count !=0){
					$GLOBALS['data'] = array('message'=>array('type'=>'warning','simple'=>'Attempt to add a duplicate value','real'=>null), 'data'=>null);
					return true;
				}
			return false;
			}catch(QueryException $exception){
				$GLOBALS['data'] = array('message'=>array('type'=>'error','simple'=>'An error occured while checking the new value','real'=>$exception->getMessage()), 'data'=>null);
				return true;//cant check. return true to prevent blind insert
			}

		}	 
   
}
if (!function_exists('generateBarcode')) {
//Generate BARCODE SERIAL NUMBER
function generateBarcode(){
   $constant=1;
        $sql="SELECT * FROM `tbl_barcode_generators`  ORDER BY created_at DESC LIMIT 1";
        $BarcodeNumber = DB::SELECT($sql);

           if(count($BarcodeNumber)>0){
            $CustomerExecute =  $BarcodeNumber[0]->barcode_no;
            if(substr($CustomerExecute,-4,4) !=date('my')){
                $barcode_no  ='000001'.date('my');
            }else{
                $barcode_no=str_pad(((int)substr($CustomerExecute, -10,6)+1),6,'0',STR_PAD_LEFT).date('my');
            }
        }
           else{
               $barcode_no  ='000001'.date('my');
           }



     return $barcode_no;

    }

}






if (!function_exists('customApiResponse')) {
    /**
     * Return a new response from the application.
     *
     * eg in controller return customApiResponse($users, 'successfully created', 201)
     * eg in controller return customApiResponse($users, 'error', 500, ['internal server error'])
     * @param  string  $data
     * @param  string  $message
     * @param  string  $errors
     * @param  int     $status
     * @param  array   $headers
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    function customApiResponse($data = '', $message = '', $status = 200, $errors = [], array $headers = ['Content-Type' => 'application/json']) {
        /**
         * make sure that we always return errors as an array of errors
         */
        $errorsArray = [];
        if(!is_array($errors)) {
            array_push($errorsArray, $errors);
            $errors = $errorsArray;
        }

        $responseData =  [
            'data'    => $data,
            'message' => $message,
            'errors'  => $errors,
            'status'  => $status
        ];

        $factory = app(ResponseFactory::class);
        if (func_num_args() === 0) {
            return $factory;
        }
        return $factory->make(json_encode($responseData), $status, $headers);
    }
}