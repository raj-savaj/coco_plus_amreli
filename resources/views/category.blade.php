@extends('layout.app')
@section('title','Category | Coco+')
@if (isset($up_cat)) @section('header','Update Category') @else @section('header','Add Category') @endif
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div id="flFormsGrid" class="col-lg-8 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>@if (isset($up_cat)) Update {{ $up_cat[0]->name }} @else Category @endif</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        @if (Session::get('success'))
                            <div class="alert alert-light-success" role="alert">
                                <strong>Success!</strong> {{ Session::get('success') }}
                            </div>
                        @endif
                        @if (isset($up_cat))
                            <form method="POST" action="{{ action('Controller@updateCategoryAdmin') }}" enctype="multipart/form-data">
                            <input type="hidden" name="c_id" value="{{ $up_cat[0]->c_id }}">
                            <input type="hidden" name="old_image" value="{{ $up_cat[0]->image }}">
                        @else
                            <form method="POST" action="{{ action('Controller@addCategory') }}" enctype="multipart/form-data">
                        @endif
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control" value="{{ (isset($up_cat) ? $up_cat[0]->name : '' ) }}" name="name" id="name" placeholder="Category name">
                                </div>
                                <div class="col-xl-2 col-lg-12 col-md-4">
                                    <div class="upload pr-md-2">
                                        @if (isset($up_cat))
                                            <input type="file" id="input-file-max-fs" class="dropify" data-default-file="{{ asset('public/category') }}/{{ $up_cat[0]->image }}" name="image" data-max-file-size="1M" />
                                        @else
                                            <input type="file" id="input-file-max-fs" class="dropify" data-default-file="" name="image" data-max-file-size="1M" />
                                        @endif
                                        <p class="mt-2"><i class="flaticon-cloud-upload mr-1"></i> Upload Picture</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive mb-4 mt-4">
                            <table id="default-ordering" class="table table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($category as $c)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <img width="50px" src="{{ url('public/category') }}/{{ $c->image }}">
                                                </div>
                                            </td>
                                            <td>{{ $c->name }}</td>
                                            <td class="text-right"><a href="{{ url('updateCategory') }}/{{ $c->c_id }}" class="btn btn-warning">Edit</a></td>
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
    <script src="{{ asset('public/plugins/dropify/dropify.min.js') }}"></script>
    <script src="{{ asset('public/plugins/blockui/jquery.blockUI.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/users/account-settings.js') }}"></script>

    <script src="{{ asset('public/assets/js/scrollspyNav.js') }}"></script>
    <script src="{{ asset('public/assets/js/components/ui-accordions.js') }}"></script>
@endsection
