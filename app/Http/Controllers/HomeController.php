<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
  public function index() {
    $arr = [];
    $arr[] = ['name'=>'aaaa','id'=>'1000'];
    return view('index', ['data'=>$arr]);
  }
}
