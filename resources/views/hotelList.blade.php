@extends('layout.app')
@section('title','Hotel Menu List | Coco+')
@section('header','Hotel Menu List')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div id="flFormsGrid" class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>Hotel Menu List</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        @if (Session::get('success'))
                            <div class="alert alert-light-success" role="alert">
                                <strong>Success!</strong> {{ Session::get('success') }}
                            </div>
                        @endif
                        <form method="POST" action="" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-row">
                                <div class="form-group col-md-8">
                                    <select name="hotel" class="form-control select_hotel">
                                        @foreach ($hotel as $h)
                                            <option value="{{ $h->h_id }}">{{ $h->name }} ({{ $h->city }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <button type="button" class="btn btn-primary getMenu">Get Menu</button>
                                </div>
                            </div>
                        </form>
                        @if(count($menu)>0)
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <h3>{{ $menu[0]->h_name }}'s Menu</h3>
                                </div>
                            </div>
                        @endif
                        <table class="table">
                            <thead>
                                <th>#</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Type</th>
                                <th>Sell Price</th>
                                <th class="text-right">Action</th>
                            </thead>
                            <tbody>
                                @foreach ($menu as $m)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex">
                                                {{-- <img width="50px" src="{{ url('public/menu') }}/{{ $m->image }}"> --}}
                                                <p class="align-self-center ml-2 admin-name"> {{ $m->mname }} </p>
                                            </div>
                                        </td>
                                        <td>{{ $m->cname }}</td>
                                        <td>{{ $m->price }}</td>
                                        <td>{{ $m->type }}</td>
                                        <td>{{ $m->sell_price }}</td>
                                        <td class="text-right"><a href="{{ url('updateHotelMenu') }}/{{ $m->hm_id }}" class="btn btn-warning">Edit</a> <a href="{{ url('deleteHotelMenu') }}/{{ $m->hm_id }}" class="btn btn-danger">Delete</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('public/plugins/dropify/dropify.min.js') }}"></script>
    <script src="{{ asset('public/plugins/blockui/jquery.blockUI.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/users/account-settings.js') }}"></script>

    <script src="{{ asset('public/assets/js/scrollspyNav.js') }}"></script>
    <script src="{{ asset('public/assets/js/components/ui-accordions.js') }}"></script>

    <script src="{{ asset('public/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('public/plugins/select2/custom-select2.js') }}"></script>
    <script>
        var menu = $(".select_hotel").select2();
        $(".getMenu").click(function(e){
            e.preventDefault();
            var id=$(".select_hotel").val();
            location.href="{{ url('/hotelList/') }}/"+id;
        });

    </script>
@endsection
