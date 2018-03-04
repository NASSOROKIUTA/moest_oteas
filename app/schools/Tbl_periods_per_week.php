<?php

namespace App\schools;

use Illuminate\Database\Eloquent\Model;

class Tbl_periods_per_week extends Model
{
 protected $fillable =['number_of_periods','class_grade_id','subject_id','status'];
}
