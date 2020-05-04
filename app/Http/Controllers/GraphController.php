<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\ProductSalesChart;
use App\Charts\TaxShippingChart;
use App\Charts\LocationChart;
use App\Charts\CustomerSalesChart;
use App\Charts\CustomerLocationChart;
use Illuminate\Support\Facades\DB;
use App\Sales_data;

class GraphController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {

        //------------TOTAL ORDERS PER PRODUCT FOR LAST 3 MONTHS ----------------------
        $dateFrom = date('2018-07-01');
        $dateTo = date('2018-10-01');

        $orderCount = Sales_data::select(DB::raw('count(cust_order) as total_count'))->whereBetween('purchase_date', [$dateFrom, $dateTo])->orderByRaw('cust_order', 'asc')->groupBy('cust_order')->get()->take(10)->pluck('total_count');
        $custOrder = Sales_data::select('cust_order')->whereBetween('purchase_date', [$dateFrom, $dateTo])->orderByRaw('cust_order', 'asc')->groupBy('cust_order')->get()->take(10)->pluck('cust_order');

        // return $orderCount->values();
        // return $custOrder->values();

        $colorCount = Sales_data::select(DB::raw('count(cust_order) as total_count'))->get()->pluck('total_count');

        //randomizing colors for the pie chart
        function random_color_part() {
            return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        }
        
        function random_color() {
            return random_color_part() . random_color_part() . random_color_part();
        }

        //randomizing piechart color
        $colors = array();
        for ($i=0; $i < 400; $i++) { 
            array_push($colors, '#'. random_color());
        }
        //----------TAX & SHIPPING BY DATE --------------------------------------------        
        if (url()->current() == route('2016')) {
            $monthData = Sales_data::selectRaw('MONTHNAME(purchase_date) as month')
                                            ->where(DB::raw('YEAR(purchase_date)'), '=', '2016')
                                            ->groupBy(DB::raw('month(purchase_date)'))
                                            ->orderBy('purchase_date')
                                            ->get()
                                            ->pluck('month');
            $taxData = Sales_data::where(DB::raw('YEAR(purchase_date)'), '=', '2016')
                                            ->groupBy(DB::raw('month(purchase_date)'))
                                            ->orderBy('purchase_date')
                                            ->get()
                                            ->pluck('tax');

            $shippingData = Sales_data::where(DB::raw('YEAR(purchase_date)'), '=', '2016')
                                            ->groupBy(DB::raw('month(purchase_date)'))
                                            ->orderBy('purchase_date')
                                            ->pluck('shipping');
        } else if (url()->current() == route('2017')) {
            $monthData = Sales_data::selectRaw('MONTHNAME(purchase_date) as month')
                                            ->where(DB::raw('YEAR(purchase_date)'), '=', '2017')
                                            ->groupBy(DB::raw('month(purchase_date)'))
                                            ->orderBy('purchase_date')
                                            ->get()
                                            ->pluck('month');
            $taxData = Sales_data::where(DB::raw('YEAR(purchase_date)'), '=', '2017')
                                            ->groupBy(DB::raw('month(purchase_date)'))
                                            ->orderBy('purchase_date')
                                            ->get()->pluck('tax');

            $shippingData = Sales_data::where(DB::raw('YEAR(purchase_date)'), '=', '2017')
                                            ->groupBy(DB::raw('month(purchase_date)'))
                                            ->orderBy('purchase_date')
                                            ->pluck('shipping');
        } else if (url()->current() == route('2018')) {
            $monthData = Sales_data::selectRaw('MONTHNAME(purchase_date) as month')
                                            ->where(DB::raw('YEAR(purchase_date)'), '=', '2018')
                                            ->groupBy(DB::raw('month(purchase_date)'))
                                            ->orderBy('purchase_date')
                                            ->get()
                                            ->pluck('month');
            $taxData = Sales_data::where(DB::raw('YEAR(purchase_date)'), '=', '2018')
                                            ->groupBy(DB::raw('month(purchase_date)'))
                                            ->orderBy('purchase_date')
                                            ->get()->pluck('tax');

            $shippingData = Sales_data::where(DB::raw('YEAR(purchase_date)'), '=', '2018')
                                            ->groupBy(DB::raw('month(purchase_date)'))
                                            ->orderBy('purchase_date')
                                            ->pluck('shipping');
        } else {
            $monthData = Sales_data::selectRaw('MONTHNAME(purchase_date) as month')
                                            ->groupBy(DB::raw('month(purchase_date)'))
                                            ->orderBy('purchase_date')
                                            ->get()
                                            ->pluck('month');
            $taxData = Sales_data::groupBy(DB::raw('month(purchase_date)'))
                                            ->orderBy('purchase_date')
                                            ->pluck('tax');

            $shippingData = Sales_data::groupBy(DB::raw('month(purchase_date)'))
                                            ->orderBy('purchase_date')
                                            ->pluck('shipping');
        }


        //-----------TOTAL SALES BY CITY------------------------------------------------
        $cityLocationData = Sales_data::pluck('grand_total', 'cust_city');
        $provinceLocationData = Sales_data::pluck('grand_total', 'cust_province');

        //-----------CUSTOMER LOCATION CHART-------------------------------------------
        $customerLocationData = Sales_data::select(DB::raw('count(id) as total_count'), DB::raw('cust_province'))->groupBy('cust_province')->get()->pluck('total_count', 'cust_province');
        
        //-----------TOTAL SALES BY CUSTOMER-------------------------------------------
        $customerSale = Sales_data::select(DB::raw('sum(grand_total) as total_sales'), DB::raw('cust_fname'))->groupBy('cust_fname')->get()->pluck('total_sales', 'cust_fname');

        
        //=================================================================

        //new chart object for Total Orders per product
        $productSales = new ProductSalesChart();
        $productSales->displayAxes(false);

        $productSales->labels($custOrder->values());
        $productSales->dataset('Total orders per product', 'pie', $orderCount->values())
                    ->backgroundColor(collect($colors));

        //new chart object for tax and shipping by date
        $taxShipping = new TaxShippingChart();

        $taxShipping->labels($monthData->values());

        if (url()->current() == route('2016')) {
            $taxShipping->dataset('Tax ($) 2016', 'bar', $taxData->values())
                        ->backgroundColor('red');
            
            $taxShipping->dataset('Shipping ($) 2016', 'line', $shippingData->values())
                        ->backgroundColor('rgba(0,0,0, .4)');
        } else if (url()->current() == route('2017')) {
            $taxShipping->dataset('Tax ($) 2017', 'bar', $taxData->values())
                        ->backgroundColor('red');
            
            $taxShipping->dataset('Shipping ($) 2017', 'line', $shippingData->values())
                        ->backgroundColor('rgba(0,0,0, .4)');
        } else if (url()->current() == route('2018')) {
            $taxShipping->dataset('Tax ($) 2018', 'bar', $taxData->values())
                        ->backgroundColor('red');
            
            $taxShipping->dataset('Shipping ($) 2018', 'line', $shippingData->values())
                        ->backgroundColor('rgba(0,0,0, .4)');
        } else {
            $taxShipping->dataset('Tax ($)', 'bar', $taxData->values())
                        ->backgroundColor('red');
            
            $taxShipping->dataset('Shipping ($)', 'line', $shippingData->values())
                        ->backgroundColor('rgba(0,0,0, .4)');
        } 
        
        //new chart object for total sales by location
        $locationSales = new LocationChart();

        $locationSales->labels($cityLocationData->keys());
        $locationSales->dataset('Total sales by city', 'bar', $cityLocationData->values())
                    ->backgroundColor('#82CDFF');

        $locationSales->labels($provinceLocationData->keys());
        $locationSales->dataset('Total sales by Province', 'bar', $provinceLocationData->values())
                    ->backgroundColor('#FFD060');

        //new chart object for total customer by location (province)
        $customerLocation = new CustomerLocationChart();
        $customerLocation->displayAxes(false);
        $customerLocation->labels($customerLocationData->keys());
        $customerLocation->dataset('Number of Customer', 'polarArea', $customerLocationData->values())
                    ->backgroundColor(collect($colors));
        
        //new chart object for total sales by customer
        $customerSalesChart = new CustomerSalesChart();
        $customerSalesChart->displayAxes(false);

        $customerSalesChart->labels($customerSale->keys());
        $customerSalesChart->dataset('Total sales by customer', 'doughnut', $customerSale->values())
                    ->backgroundColor(collect($colors)); 

        return view('graph', compact('productSales', 'taxShipping', 'locationSales', 'customerSalesChart', 'customerLocation'));
    }
}
