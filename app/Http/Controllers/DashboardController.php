<?php

namespace App\Http\Controllers;

use App\Charts\YearlySalesChart;
use App\Sales_data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use App\Traits\Nicolaslopezj\Searchable\SearchableTrait;

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
        //get data for column with sortable
        $salesData = Sales_data::sortable()->get();

        //condition to test if its going to that route
        if (url()->current() == route('sales2016')) {
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

        //new object for sales chart
        $yearlySales = new YearlySalesChart();
        $yearlySales->labels($monthData->values());

        if (url()->current() == route('sales2018')) {
            $yearlySales->dataset('Total Sales 2018 ($)', 'line', $grandSalesData->values())
                                ->backgroundColor('rgba(140,217,201, .6)')
                                ->color('rgba(41,56,70)');
        } else if (url()->current() == route('sales2017')) {
            $yearlySales->dataset('Total Sales 2017', 'line', $grandSalesData->values())
                                ->backgroundColor('rgba(140,217,201, .6)')
                                ->color('rgba(41,56,70)');
        } else if (url()->current() == route('sales2016')) {
            $yearlySales->dataset('Total Sales 2016 ($)', 'line', $grandSalesData->values())
                                ->backgroundColor('rgba(140,217,201, .6)')
                                ->color('rgba(41,56,70)');
        } else {
            $yearlySales->dataset('Total Sales ($)', 'line', $grandSalesData->values())
                                ->backgroundColor('rgba(140,217,201, .6)')
                                ->color('rgba(41,56,70)');
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
}
