<?php

namespace App\Model\SetupData;

use Illuminate\Database\Eloquent\Model;

class Tbl_permission extends Model
{
   protected  $fillable=['module','glyphicons','title','main_menu','keyGenerated'];
}
