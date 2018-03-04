<?php

namespace App\applicants;

use Illuminate\Database\Eloquent\Model;

class Tbl_attendance_report extends Model
{
    protected $fillable=['applicant_id','applicant_image','status'];
}