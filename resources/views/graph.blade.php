@extends('layouts.app')

@section('content')
<div class="container mw-100">
    <div class="row pb-4">
        <div class="col-6">
            <div class="card" style="height: 40rem">
                <div class="card-header">
                    Total Orders per Product
                </div>
                <div class="card-body">
                    {{-- Render product sales chart --}}
                    {!! $productSales->container() !!}
                    {!! $productSales->script() !!}
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card" style="height: 40rem">
                <div class="card-header">
                    Tax & Shipping By Date
                </div>
                <div class="card-body">
                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Select Year
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="/graphs">All Years</a>
                            <a class="dropdown-item" href="/graphs/taxshipping/2016">2016</a>
                            <a class="dropdown-item" href="/graphs/taxshipping/2017">2017</a>
                            <a class="dropdown-item" href="/graphs/taxshipping/2018">2018</a>
                        </div>
                    </div>
                    <div class="p-3">  
                        {{-- Render tax and shipping chart --}}
                        {!! $taxShipping->container() !!}
                        {!! $taxShipping->script() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row pb-4">
        <div class="col-6">
            <div class="card" style="height: 40rem">
                <div class="card-header">
                    Total Sales by City & Province
                </div>
                <div class="card-body">
                    {{-- Render sales by location chart --}}
                    {!! $locationSales->container() !!}
                    {!! $locationSales->script() !!}
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card" style="height: 40rem">
                <div class="card-header">
                    Number of orders in Provinces
                </div>
                <div class="card-body">
                    {{-- Render customer by location chart --}}
                    {!! $customerLocation->container() !!}
                    {!! $customerLocation->script() !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card" style="height: 80vh">
                <div class="card-header">
                    Total Sales by Customer
                </div>
                <div class="card-body">
                    {{-- Render cusstomer sales chart --}}
                    {!! $customerSalesChart->container() !!}
                    {!! $customerSalesChart->script() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
