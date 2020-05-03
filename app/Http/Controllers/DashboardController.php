<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sales_data;
use App\Charts\YearlySalesChart;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;


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
        // $salesTable = DB::table('sales_data');
        
        //get data for column with sortable including pagination
        $salesData = Sales_data::sortable()->paginate(356);
        
        if(url()->current() == route('sales2016')) {
            $monthData = Sales_data::selectRaw('MONTHNAME(purchase_date) as month')
                                    ->where(DB::raw('YEAR(purchase_date)'), '=', '2016')
                                    ->groupBy(DB::raw('month(purchase_date)'))
                                    ->orderBy('purchase_date')
                                    ->get()
                                    ->pluck('month');
            
            $grandSalesData = Sales_data::where(DB::raw('YEAR(purchase_date)'), '=', '2016')
                                    ->groupBy(DB::raw('month(purchase_date)'))
                                    ->orderBy('purchase_date')
                                    ->get()
                                    ->pluck('grand_total');
        } else if (url()->current() == route('sales2017')) {
            $monthData = Sales_data::selectRaw('MONTHNAME(purchase_date) as month')
                                    ->where(DB::raw('YEAR(purchase_date)'), '=', '2017')
                                    ->groupBy(DB::raw('month(purchase_date)'))
                                    ->orderBy('purchase_date')
                                    ->get()
                                    ->pluck('month');
        
            $grandSalesData = Sales_data::where(DB::raw('YEAR(purchase_date)'), '=', '2017')
                                    ->groupBy(DB::raw('month(purchase_date)'))
                                    ->orderBy('purchase_date')
                                    ->get()
                                    ->pluck('grand_total');
        } else if (url()->current() == route('sales2018')) {
            $monthData = Sales_data::selectRaw('MONTHNAME(purchase_date) as month')
                                    ->where(DB::raw('YEAR(purchase_date)'), '=', '2018')
                                    ->groupBy(DB::raw('month(purchase_date)'))
                                    ->orderBy('purchase_date')
                                    ->get()
                                    ->pluck('month');
        
            $grandSalesData = Sales_data::where(DB::raw('YEAR(purchase_date)'), '=', '2018')
                                    ->groupBy(DB::raw('month(purchase_date)'))
                                    ->orderBy('purchase_date')
                                    ->get()
                                    ->pluck('grand_total');
        } else {
            $monthData = Sales_data::selectRaw('MONTHNAME(purchase_date) as month')
                                    ->groupBy(DB::raw('month(purchase_date)'))
                                    ->orderBy('purchase_date')
                                    ->get()
                                    ->pluck('month');
        
            $grandSalesData = Sales_data::groupBy(DB::raw('month(purchase_date)'))
                                    ->orderBy('purchase_date')
                                    ->get()
                                    ->pluck('grand_total');
        }

        $yearlySales = new YearlySalesChart();
        $yearlySales->labels($monthData->values());

        if(url()->current() == route('sales2018')) {
            $yearlySales->dataset('Total Sales 2018 ($)', 'line', $grandSalesData->values());
        } else if(url()->current() == route('sales2017')) {
            $yearlySales->dataset('Total Sales 2017', 'line', $grandSalesData->values());
        } else if(url()->current() == route('sales2016')) {
            $yearlySales->dataset('Total Sales 2016 ($)', 'line', $grandSalesData->values());
        } else {
            $yearlySales->dataset('Total Sales ($)', 'line', $grandSalesData->values());
        }

        //Totals
        $grandTotalSales = DB::table('sales_data')->sum('grand_total');
        $totalShipping = DB::table('sales_data')->sum('shipping');
        $totalTax = DB::table('sales_data')->sum('tax');

        //Top sales based on city.
        $topCitySales = DB::table('sales_data')
                                    ->groupBy(DB::raw('cust_city'))
                                    ->orderByRaw('sum(grand_total) desc')
                                    ->take(1)
                                    ->pluck('cust_city')
                                    ->first();
                        
        return view('dashboard', compact('yearlySales', 
                                        'salesData', 
                                        'grandTotalSales', 
                                        'totalShipping', 
                                        'totalTax', 
                                        'topCitySales'));
    }

    public function search(Request $request) {
        $search = $request->get('search');
        $sales = DB::table('sales_data')->where('id', 'like', '%'.$search.'%')->paginate(5);
                            
        
        return view('dashboard', compact('sales'));
    }
}
