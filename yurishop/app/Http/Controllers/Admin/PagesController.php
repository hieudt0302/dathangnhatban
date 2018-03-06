<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class PagesController extends Controller
{
    public function getDashboard()
    {
        $user_numbers = DB::table('users')->where('created_at', '>=', Carbon::today()->startOfWeek())->count();
        $rate = DB::table('rates')->orderby('created_at', 'DESC')->first();

        if (!empty($rate))
        {
            $lastest_rate = $rate->rate;
        }
        else
        {
            $lastest_rate = 0;
        }

        $order_new = DB::table('orders')->where('status', '1')->count();
        $order_wait = DB::table('orders')->where('status', '2')->count();
       
        return view('admin.pages.dashboard', compact('user_numbers', 'lastest_rate', 'order_new', 'order_wait'));
    }
     
    public function getBlank()
    {
        return view('admin.pages.blank');
    }
}
