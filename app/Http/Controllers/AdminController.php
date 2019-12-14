<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PostView;

class AdminController extends Controller
{
    
    public function dashboard(){
        $views = PostView::getTodaysHourlyTotalViews();
        $visitors = PostView::getUniqueVisitors();
        return view("admin.pages.dashboard")->with(["views"=>json_encode($views),"visitors"=> json_encode($visitors)]);
    }
}
