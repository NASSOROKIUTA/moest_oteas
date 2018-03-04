<?php

namespace App\permits;

use Illuminate\Database\Eloquent\Model;

class Tbl_permit extends Model
{
  protected $fillable=['subject_id','gender','council_id','permits','dept_id'];
}
