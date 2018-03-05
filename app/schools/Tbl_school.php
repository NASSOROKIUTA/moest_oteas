<?php

namespace App\schools;

use Illuminate\Database\Eloquent\Model;

class Tbl_school extends Model
{
    protected $fillable=['department_id','centre_number','council_id','school_level','school_name','special_needs','special_needs_type','day_boarding','distance_km','teaching_language','double_shift'
       ,'class_rooms'];
}
