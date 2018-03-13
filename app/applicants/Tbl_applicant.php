<?php

namespace App\applicants;

use Illuminate\Database\Eloquent\Model;

class Tbl_applicant extends Model
{
	//use \App\UuidForKey; 
    protected $fillable =['department_id','college','registration_number','year_graduated','year_certified','form_four_index','first_name','middle_name','last_name','gender','dob','email','applicant_id','sne'];
}
