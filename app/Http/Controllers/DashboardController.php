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

        //pluck data from database (cust_city and grand_total)
        $sales2018 = Sales_data::where(DB::raw('YEAR(purchase_date)'), '=', '2018')->orderBy('purchase_date')->pluck('grand_total', 'purchase_date');
        $sales2017 = Sales_data::where(DB::raw('YEAR(purchase_date)'), '=', '2017')->orderBy('purchase_date')->pluck('grand_total', 'purchase_date');
        $sales2016 = Sales_data::where(DB::raw('YEAR(purchase_date)'), '=', '2016')->orderBy('purchase_date')->pluck('grand_total', 'purchase_date');
        $allYearSales = Sales_data::orderBy('purchase_date')->pluck('grand_total', 'purchase_date');

        $yearlySales2018 = new YearlySalesChart();
        $yearlySales2018->labels($sales2018->keys());
        $yearlySales2018->dataset('Total Sales 2018', 'line', $sales2018->values());

        $yearlySales2017 = new YearlySalesChart();
        $yearlySales2017->labels($sales2017->keys());
        $yearlySales2017->dataset('Total Sales 2017', 'line', $sales2017->values());

        $yearlySales2016 = new YearlySalesChart();
        $yearlySales2016->labels($sales2016->keys());
        $yearlySales2016->dataset('Total Sales 2016', 'line', $sales2016->values());

        $allYearlySales = new YearlySalesChart();
        $allYearlySales->labels($allYearSales->keys());
        $allYearlySales->dataset('Total Sales', 'line', $allYearSales->values());

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
                        
        return view('dashboard', compact('yearlySales2018', 
                                        'yearlySales2017', 
                                        'yearlySales2016', 
                                        'allYearlySales', 
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
