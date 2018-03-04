<?php

namespace App\Http\Controllers\System_Updates;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\classes\Updater;
use Illuminate\Support\Facades\Config;

class Updater_Init extends Controller
{
    private function downloadUpdater(){
		$request = "<client>
						<ip>".Config::get('updater.myIP')."</ip>
					</client>";
		$ch = curl_init(Config::get('updater.update_server'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		curl_setopt($ch,  CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/xml'));
		
		$attempt = 0;
		while($attempt++ < 10){
			try{
				$data = curl_exec($ch);return $data;
				file_put_contents(Config::get('updater.updater_path'), $data);
				curl_close($ch);
				Updater::update();
			}catch(Examption $ex){
				file_put_contents("update_log", Date('Y-m-d H:i:s')."    ".curl_error($ch).PHP_EOL, FILE_APPEND);
				curl_close($ch);
			}
		}
	}
	
	public function init(){
		$this->downloadUpdater();
	}
	
}
