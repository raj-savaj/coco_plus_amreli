<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Rider Dashboard | Coco+</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('public/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/assets/css/plugins.css') }}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/forms/theme-checkbox-radio.css') }}">
    <link href="{{ asset('public/plugins/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/assets/css/apps/contacts.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
    <script src="{{ asset('public/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('public/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('public/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('public/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/custom.js') }}"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('public/assets/js/apps/contact.js') }}"></script>
    <style>
        #content{
            margin-top: 0px;
        }
    </style>
</head>
<body>

    <!--  BEGIN NAVBAR  -->
    <div class="header-container fixed-top">
        <header class="header navbar navbar-expand-sm">
            <ul class="navbar-item theme-brand flex-row  text-center">
                <li class="nav-item theme-text">
                    <a href="#" class="nav-link"> Coco + </a>
                </li>
            </ul>
        </header>
    </div>
    <!--  END NAVBAR  -->


    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>


        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="row layout-top-spacing">
                    <div id="flFormsGrid" class="col-lg-8 layout-spacing">
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
                    <div id="flFormsGrid" class="col-lg-4 layout-spacing">
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
                    </div>
                </div>
            </div>
            <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                    <p class="">Copyright © 2020 <a target="_blank" href="https://ashtavinayaksoftsolution.in/">Ashtavinayak soft solution</a>, All rights reserved.</p>
                </div>
                <div class="footer-section f-section-2">
                    <p class="">Coded with <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></p>
                </div>
            </div>
        </div>
        <!--  END CONTENT AREA  -->
    </div>
        <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
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
                else
                {
                    window.location.href = "{{ url('/rider') }}";
                }
            }
        });
    });
</script>
    <script src="{{ asset('public/assets/js/app.js') }}"></script>
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
</body>
</html>
