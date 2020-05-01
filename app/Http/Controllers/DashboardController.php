<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sales_data;
use App\Charts\ProvinceSalesChart;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //get data from database for the table
        // $salesData = DB::table('sales_data')->paginate(10);

        $salesData = Sales_data::all();       

        //pluck data from database (cust_city and grand_total)
        $sales = Sales_data::pluck('grand_total', 'cust_city');
        

        $provinceSales = new ProvinceSalesChart();

        $provinceSales->labels($sales->keys());
        $provinceSales->dataset('Total Sales by Province', 'bar', $sales->values());

        return view('dashboard', compact('provinceSales', 'salesData'));
    }
}
