@extends('layout.app')
@section('title','Hotels | Coco+')
@section('header','Hotels')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div id="flFormsGrid" class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>Hotels <a href="{{ url('/addHotel') }}" class="btn btn-primary">Add New Hotel</a></h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        @if (Session::get('success'))
                            <div class="alert alert-light-success" role="alert">
                                <strong>Success!</strong> {{ Session::get('success') }}
                            </div>
                        @endif
                        <div class="table-responsive mb-4 mt-4">
                            <table id="default-ordering" class="table table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Cost for two</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hotel as $h)
                                        <tr>
                                            <td>
                                                <div class="d-flex">
                                                    <img width="50px" src="{{ url('public/hotel') }}/{{ $h->image }}">
                                                    <p class="align-self-center ml-2 admin-name"> {{ $h->name }} </p>
                                                </div>
                                            </td>
                                            <td>{{ $h->phone }}</td>
                                            <td>{{ $h->address }}</td>
                                            <td>₹{{ $h->cost }}</td>
                                            <td>{{ $h->type }}</td>
                                            <td>
                                                <div class="form-group">
                                                    <select name="status" data-key="{{ $h->h_id }}" class="form-control status">
                                                        @if ($h->status==1)
                                                            <option value="1" selected>Active</option>
                                                            <option value="2">Not Active</option>
                                                        @else
                                                            <option value="1">Active</option>
                                                            <option value="2" selected>Not Active</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="badge outline-badge-warning" id="msg{{ $h->h_id }}" style="display:none">Updating Wait...</div>
                                                <div class="badge outline-badge-success" id="msg_suc{{ $h->h_id }}" style="display:none">Updated</div>
                                            </td>
                                            <td class="text-center"><a href="{{ url('updateHotel') }}/{{ $h->h_id }}" class="btn btn-warning">Edit</a> </td>
                                        </tr>
                                    @endforeach
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('public/plugins/table/datatable/datatables.js') }}"></script>
    <script>
        $('#default-ordering').DataTable( {
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
               "sLengthMenu": "Results :  _MENU_",
            },
            "order": [[ 3, "desc" ]],
            "stripeClasses": [],
            "lengthMenu": [7, 10, 20, 50],
            "pageLength": 7,
            drawCallback: function () { $('.dataTables_paginate > .pagination').addClass(' pagination-style-13 pagination-bordered mb-5'); }
	    } );
        $(".status").change(function(){
            var hotel=$(this).attr('data-key');
            $.ajax({
                url: "{{ url('changeHotelStatus') }}",
                method: 'get',
                data: {h_id:hotel,status:this.value},
                beforeSend: function() {
                    $("#msg"+hotel).hide();
                    $("#msg_suc"+hotel).hide();
                    $("#msg"+hotel).show();
                },
                success: function(result){
                    $("#msg"+hotel).hide();
                    $("#msg_suc"+hotel).show();
                    setTimeout(function(){
                        $("#msg"+hotel).hide();
                        $("#msg_suc"+hotel).hide();
                     }, 3000);
                }
            });
        });
    </script>
@endsection
