@extends('layout.app')
@section('title','Menu | Coco+')
@if (isset($up_menu)) @section('header','Update Menu') @else @section('header','Menu') @endif
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div id="flFormsGrid" class="col-lg-8 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>@if (isset($up_menu)) Update {{ $up_menu[0]->name }} @else Menu @endif</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        @if (Session::get('success'))
                            <div class="alert alert-light-success" role="alert">
                                <strong>Success!</strong> {{ Session::get('success') }}
                            </div>
                        @endif
                        @if (isset($up_menu))
                            <form method="POST" action="{{ action('Controller@updateMenuAdmin') }}" enctype="multipart/form-data">
                            <input type="hidden" name="m_id" value="{{ $up_menu[0]->m_id }}">
                        @else
                        <form method="POST" action="{{ action('Controller@addMenu') }}">
                        @endif
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <select name="category" class="form-control">
                                        @foreach ($category as $c)
                                            @if(isset($up_menu) && $c->c_id==$up_menu[0]->c_id)
                                                <option value="{{ $c->c_id }}" selected>{{ $c->name }}</option>
                                            @else
                                                <option value="{{ $c->c_id }}">{{ $c->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-7">
                                    <input type="text" class="form-control" value="{{ (isset($up_menu) ? $up_menu[0]->name : '' ) }}" name="name" id="name" placeholder="Menu name">
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
                                        <th>Category</th>
                                        <th>Name</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($menu as $m)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $m->cname }}</td>
                                            <td>{{ $m->mname }}</td>
                                            <td class="text-right"><a href="{{ url('updateMenu') }}/{{ $m->m_id }}" class="btn btn-warning">Edit</a></td>
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
@endsection
