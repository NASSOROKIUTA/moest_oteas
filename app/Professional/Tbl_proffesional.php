<?php

namespace App\professional;

use Illuminate\Database\Eloquent\Model;

class Tbl_proffesional extends Model
{
	use \App\UuidForKey; 
    //
	protected  $fillable=['prof_name'];
}
