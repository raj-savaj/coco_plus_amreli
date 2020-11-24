@extends('layout.app')
@section('title','Menu | Coco+')
@if (isset($update)) @section('header','Update Hotel Menu') @else @section('header','Hotel Menu') @endif
@php
    use \App\Http\Controllers\Controller;
@endphp
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div id="flFormsGrid" class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>@if (isset($update)) Update Hotel Menu @else Hotel Menu @endif</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        @if (Session::get('success'))
                            <div class="alert alert-light-success" role="alert">
                                <strong>Success!</strong> {{ Session::get('success') }}
                            </div>
                        @endif
                        @if (isset($update))
                            <form method="POST" action="{{ action('Controller@updateHotelMenuAdmin') }}" enctype="multipart/form-data">
                            <input type="hidden" name="hm_id" value="{{ $update[0]->hm_id }}">
                            <input type="hidden" name="old_image" value="{{ $update[0]->image }}">
                        @else
                            <form method="POST" action="{{ action('Controller@addHotelMenu') }}" enctype="multipart/form-data">
                        @endif
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <select name="hotel" class="form-control select_hotel">
                                        @foreach ($hotel as $h)
                                            @if (isset($update) && $h->h_id==$update[0]->h_id)
                                                <option value="{{ $h->h_id }}" selected>{{ $h->name }} ({{ $h->city }})</option>
                                            @else
                                                <option value="{{ $h->h_id }}">{{ $h->name }} ({{ $h->city }})</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <select name="menu" class="form-control select_menu">
                                        @foreach ($menu as $m)
                                            @if (isset($update) && $m->m_id==$update[0]->m_id)
                                                <option value="{{ $m->m_id }}" selected>{{ $m->name }}</option>
                                            @else
                                                <option value="{{ $m->m_id }}">{{ $m->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-1">
                                    <input type="text" name="type" class="form-control" value="{{ (isset($update) ? $update[0]->type : '' ) }}" placeholder="Type">
                                </div>
                                <div class="form-group col-md-1">
                                    <input type="text" name="price" class="form-control" value="{{ (isset($update) ? $update[0]->price : '' ) }}" placeholder="Price">
                                </div>
                                <div class="form-group col-md-2">
                                    <input type="text" name="sell_price" class="form-control" value="{{ (isset($update) ? $update[0]->sell_price : '' ) }}" placeholder="Sell Price">
                                </div>
                                <div class="col-xl-2 col-lg-12 col-md-2">
                                    <div class="upload pr-md-2">
                                        @if (isset($update))
                                            <input type="file" id="input-file-max-fs" class="dropify" data-default-file="{{ asset('public/menu') }}/{{ $update[0]->image }}" name="image" data-max-file-size="1M" />
                                        @else
                                            <input type="file" id="input-file-max-fs" class="dropify" data-default-file="" name="image" data-max-file-size="1M" />
                                        @endif
                                        <p class="mt-2"><i class="flaticon-cloud-upload mr-1"></i> Upload Menu Image</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-1">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                        @foreach ($hotel as $h)
                            <div id="toggleAccordion">
                                <div class="card">
                                <div class="card-header" id="heading{{ $h->h_id }}">
                                    <section class="mb-0 mt-0">
                                    <div role="menu" class="collapsed" data-toggle="collapse" data-target="#defaultAccordionOne{{ $h->h_id }}" aria-expanded="true" aria-controls="defaultAccordionOne{{ $h->h_id }}">
                                        {{ $h->name }} <div class="icons"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></div>
                                    </div>
                                    </section>
                                </div>

                                <div id="defaultAccordionOne{{ $h->h_id }}" class="collapse" aria-labelledby="heading{{ $h->h_id }}" data-parent="#toggleAccordion">
                                    <div class="card-body">
                                        <p class="">
                                            @php
                                                $menu=Controller::getHotelMenuDetail($h->h_id);
                                            @endphp
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
                                                                    <img width="50px" src="{{ url('public/menu') }}/{{ $m->image }}">
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
                                        </p>
                                    </div>
                                </div>
                                </div>
                            </div>
                          @endforeach
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
        var menu = $(".select_menu").select2();
        var hotel = $(".select_hotel").select2();

    </script>
@endsection
