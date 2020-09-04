<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    //
    public function controlledMethod(){
        return response()->json([
            'msg' => 'We should return only json'
        ]);
    }

    public function test(){
        return response()->json([
            'msg' => 'some error occured'
        ],422); 
        //return 'it is working';
    }
}
