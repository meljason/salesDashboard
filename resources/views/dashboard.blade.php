@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    {!! $provinceSales->container() !!}
                    {!! $provinceSales->script() !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center tableFixHead">
        <table>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Order ID</th>
                    <th scope="col">Purchase Date</th>
                    <th scope="col">PO Number</th>
                    <th scope="col">Customer First Name</th>
                    <th scope="col">Customer Order</th>
                    <th scope="col">City</th>
                    <th scope="col">Country</th>
                    <th scope="col">Province</th>
                    <th scope="col">Currency</th>
                    <th scope="col">Tax</th>
                    <th scope="col">Grand Total</th>
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

        {{-- {{ $salesData->links() }} --}}
    </div>
</div>
@endsection
