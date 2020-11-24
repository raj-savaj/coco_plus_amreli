@extends('layout.app')
@section('title','Order Detail | Coco+')
@section('header','Order Detail')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div id="flFormsGrid" class="col-lg-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Order Detail</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="row">
                        <div class="col-md-12">
                            <h2>{{ $order[0]->name }}</h2>
                        </div>
                        <div class="col-md-12">
                            {{ $order[0]->house_no }}, {{ $order[0]->address }}
                        </div>
                        <div class="col-md-12">
                            {{ $order[0]->status_name }}
                            @if ($order[0]->rider)
                               By {{ $order[0]->rider }}
                            @endif
                        </div>
                        <div class="col-md-12">
                            {{ $order[0]->phone }}
                        </div>
                        <div class="col-md-12">
                            Cash On Delivery
                        </div>
                        <div class="col-md-12">
                            @php
                                $date=date_create($order[0]->date);
                                echo date_format($date,"l d M Y h:i:s A")
                            @endphp
                        </div>
                        <div class="col-md-12">
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <h2>{{ $order[0]->hotel}}</h2>
                        </div>

                        <div class="col-md-12">
                            {{ $order[0]->hotel_address }}
                        </div>
                    </div>
                    <div class="table-responsive mb-4 mt-4">
                        <table class="table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Sell Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item as $i)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $i->menu }}</td>
                                        <td>{{ $i->qty }}</td>
                                        <td>{{ $i->price }}</td>
                                        <td>{{ $i->sell_price }}</td>
                                        <td>{{ $i->qty*$i->sell_price }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right" colspan="5">Total</td>
                                    <td>{{ $order[0]->total }}</td>
                                    @php
                                        $payable=$order[0]->total;
                                    @endphp
                                </tr>
                                @if ($order[0]->discount > 0)
                                <tr>
                                    <td class="text-right" colspan="5">Discount</td>
                                    <td>{{ $order[0]->discount }}</td>
                                    @php
                                        $payable-=$order[0]->discount;
                                    @endphp
                                </tr>
                                @endif
                                <tr>
                                    <td class="text-right" colspan="5">Delivery Charge</td>
                                    <td>{{ $order[0]->del_charge }}</td>
                                    @php
                                        $payable+=$order[0]->del_charge;
                                    @endphp
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5">Payable Amount</td>
                                    <td>{{ $payable }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div id="flFormsGrid" class="col-lg-4 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Order Status</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="wait" style="display: none">
                        <div class="alert alert-light-warning" role="alert">
                            <strong>Wait!</strong> Updating Order Status...
                        </div>
                    </div>
                    <div class="up_msg" style="display: none">
                        <div class="alert alert-light-success" role="alert">
                            <strong>Success!</strong> Status Updated
                        </div>
                    </div>
                    @foreach ($status_detail as $st)
                        <div class="custom-control custom-radio">
                            <input type="radio" id="customRadio{{ $st->s_id }}" value="{{ $st->s_id }}" name="status" class="custom-control-input status"@if($order[0]->status==$st->s_id) checked @endif>
                            <label class="custom-control-label" for="customRadio{{ $st->s_id }}">{{ $st->status_name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div> --}}

    </div>
</div>
<script src="{{ asset('public/plugins/table/datatable/datatables.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(".status").change(function(e){
        var oid=JSON.parse("{{ json_encode($order[0]->o_id) }}");
        $.ajax({
            url: "{{ url('changeOrderStatus') }}",
            method: 'get',
            data: {o_id:oid,status:this.value},
            beforeSend: function() {
                $(".up_msg").hide();
                $(".wait").show();
            },
            success: function(result){
                if(result.status==true)
                {
                    $(".wait").hide();
                    $(".up_msg").show();
                    setTimeout(function(){
                        $(".wait").hide();
                        $(".up_msg").hide();
                     }, 3000);
                }
            }
        });
    });
</script>
@endsection()
