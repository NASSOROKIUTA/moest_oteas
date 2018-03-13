<?php

namespace App\colleges;

use Illuminate\Database\Eloquent\Model;

class Tbl_college extends Model
{
   protected $fillable=['email','focalname','education_level','ownership_status','registration_status','college','college_name','college_address','college_status'];
}
