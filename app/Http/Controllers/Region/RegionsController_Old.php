<?php

namespace App\Http\Controllers\Region;

use App\Region\Tbl_region;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Region\RegionsController;

class RegionsController extends Controller
{
    //
    public function store(Request $request)
    {
        
     return Tbl_region::create($request->all());
        
    }

    public function index()
    {
        return Tbl_region::get();
    }
    public function delete($id)
    {

        return Tbl_region::destroy($id);

    }

    public function update_region(Request $request)
    {
        $id=$request['id'];
        return Tbl_region::where('id',$id)->update($request->all());
    }
}
