<?php

namespace App\Http\Controllers;



class DashboardController extends Controller
{
    //
    // هيك منطبق middleware
    public function __construct(){
        $this->middleware(['auth']);
    }
    public function index(){

        return view('dashboard.index');
    }
}
