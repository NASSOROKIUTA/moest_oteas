<?php

namespace App\schools;

use Illuminate\Database\Eloquent\Model;

class Tbl_teachers_requirement extends Model
{
     protected $fillable=['school_id','students_taking','teachers_available','subject_id','class_grade','sne_non'];
}
