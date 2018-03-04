<?php

namespace App\applicants;

use Illuminate\Database\Eloquent\Model;

class Tbl_application extends Model
{
     protected $fillable =['lock_applicant','selected','school_id','subject_id','applicant_id','priority','council_id'];
}
