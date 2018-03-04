<?php

namespace App\Http\Controllers\System_Updates;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;


class Updater extends Controller
{
    public function update(){
		echo 'Update in progress....';
	}	
}
