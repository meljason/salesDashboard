@extends('layouts.app')

@section('content')
<div class="container mw-100">
    <div class="row justify-content-center pb-5">
        <div class="col">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle m-3 float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Select Year
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="/allyears">All Years</a>
                        <a class="dropdown-item" href="/2016">2016</a>
                        <a class="dropdown-item" href="/2017">2017</a>
                        <a class="dropdown-item" href="/2018">2018</a>
                    </div>
                </div>

                <div class="card-body">
                    @if(url()->current() == route('2016'))
                        {!! $yearlySales2016->container() !!}

                        {!! $yearlySales2016->script() !!}
                    @elseif(url()->current() == route('2017'))
                        {!! $yearlySales2017->container() !!}

                        {!! $yearlySales2017->script() !!}
                    @elseif(url()->current() == route('2018'))
                        {!! $yearlySales2018->container() !!}

                        {!! $yearlySales2018->script() !!}
                    @else
                        {!! $allYearlySales->container() !!}

                        {!! $allYearlySales->script() !!}
                    @endif
                </div>
            </div>
            
        </div>
    </div>
    <div class="row justify-content-end pb-3 pr-3">
        <div class="col-sm-3">
            <form action="/search" method="get">
                <div class="input-group">
                    <input type="search" name="search" class="form-control">
                    <span class="input-group-prepend">
                        <button type="submit" class="btn btn-dark">Search</button>
                    </span>
                </div>
            </form>
        </div>
    </div>
    <div class="row justify-content-center tableFixHead table-responsive">
        <div class="col">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">@sortablelink('id', 'ID', ['filter' => 'active, visible'], ['class' => 'text-light'])</th>
                        <th scope="col">@sortablelink('order_id', 'Order ID', ['filter' => 'active, visible'], ['class' => 'text-light'])</th>
                        <th scope="col">@sortablelink('purchase_date','Purchase Date', ['filter' => 'active, visible'], ['class' => 'text-light'])</th>
                        <th scope="col">@sortablelink('po_number', 'PO Number', ['filter' => 'active, visible'], ['class' => 'text-light'])</th>
                        <th scope="col">@sortablelink('cust_fname','First Name', ['filter' => 'active, visible'], ['class' => 'text-light'])</th>
                        <th scope="col">@sortablelink('cust_order','Customer Order', ['filter' => 'active, visible'], ['class' => 'text-light'])</th>
                        <th scope="col">@sortablelink('cust_city','City', ['filter' => 'active, visible'], ['class' => 'text-light'])</th>
                        <th scope="col">@sortablelink('cust_country','Country', ['filter' => 'active, visible'], ['class' => 'text-light'])</th>
                        <th scope="col">@sortablelink('cust_province','Province', ['filter' => 'active, visible'], ['class' => 'text-light'])</th>
                        <th scope="col">@sortablelink('currency','Currency', ['filter' => 'active, visible'], ['class' => 'text-light'])</th>
                        <th scope="col">@sortablelink('tax','Tax', ['filter' => 'active, visible'], ['class' => 'text-light'])</th>
                        <th scope="col">@sortablelink('grand_total','Grand Total', ['filter' => 'active, visible'], ['class' => 'text-light'])</th>
                    </tr>
                </thead>
                    <tbody>
                        @foreach ($salesData as $data)
                            <tr>
                                <th>{{$data->id}}</th>
                                <th>{{$data->order_id}}</th>
                                <th>{{date('Y-m-d', strtotime($data->purchase_date))}}</th>
                                <th>{{$data->po_number}}</th>
                                <th>{{$data->cust_fname}}</th>
                                <th>{{$data->cust_order}}</th>
                                <th>{{$data->cust_city}}</th>
                                <th>{{$data->cust_country}}</th>
                                <th>{{$data->cust_province}}</th>
                                <th>{{$data->currency}}</th>
                                <th>{{$data->tax}}</th>
                                <th>{{$data->grand_total}}</th>
                            </tr>
                        @endforeach
                    </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
