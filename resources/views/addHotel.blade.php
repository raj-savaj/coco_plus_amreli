@extends('layout.app')
@section('title','Add Hotel | Coco+')
@if (isset($hotel)) @section('header','Update Hotel') @else @section('header','Add Hotel') @endif
@section('header','Add Hotel')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div id="flFormsGrid" class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>@if (isset($hotel)) Update {{ $hotel[0]->name }} @else Add Hotel @endif</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        @if (isset($hotel))
                            <form method="POST" action="{{ action('Controller@updateHotelAdmin') }}" enctype="multipart/form-data">
                            <input type="hidden" name="h_id" value="{{ $hotel[0]->h_id }}">
                            <input type="hidden" name="old_image" value="{{ $hotel[0]->image }}">
                        @else
                            <form method="POST" action="{{ action('Controller@addHotel') }}" enctype="multipart/form-data">
                        @endif
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <div class="form-row">
                                        <div class="form-group col-md-8">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" value="{{ (isset($hotel) ? $hotel[0]->name : '' ) }}" name="name" id="name" placeholder="Hotel name">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="type">Type</label>
                                            <select name="type" id="type" class="form-control">
                                                <option value="">Hotel type</option>
                                                <option value="Veg.">Veg.</option>
                                                <option value="Non Veg.">Non Veg.</option>
                                                <option value="Veg. & Non Veg.">Veg. & Non Veg.</option>
                                                <option value="Veg. With Egg">Veg. With Egg</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="city">City</label>
                                            <select name="city" id="city" class="form-control">
                                                <option value="">Select City</option>
                                                <option value="Amreli" selected>Amreli</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="phone">Phone</label>
                                            <input type="number" class="form-control" value="{{ (isset($hotel) ? $hotel[0]->phone : '' ) }}" name="phone" id="phone" placeholder="Hotel phone no.">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="cost">Cost</label>
                                            <input type="number" class="form-control" value="{{ (isset($hotel) ? $hotel[0]->cost : '' ) }}" name="cost" id="cost" placeholder="For two person">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="del_charge">Delivery Charge</label>
                                            <input type="number" class="form-control" value="{{ (isset($hotel) ? $hotel[0]->del_charge : '' ) }}" name="del_charge" id="del_charge" placeholder="Delivery Charge">
                                        </div>
                                        @if (isset($hotel_time) && count($hotel_time)>0)
                                            @foreach ($hotel_time as $time)
                                                <div class="form-group col-md-3">
                                                    <label for="timeFrom">Open</label>
                                                    <input id="timeFrom" name="open[]" value="{{ $time->open_at }}" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Date..">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="timeTo">Close</label>
                                                    <input id="timeTo" name="close[]" value="{{ $time->close_at }}" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Date..">
                                                </div>
                                            @endforeach
                                            @if (count($hotel_time)==1)
                                                <div class="form-group col-md-3">
                                                    <label for="timeFrom">Open</label>
                                                    <input id="timeFrom" name="open[]" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Date..">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="timeTo">Close</label>
                                                    <input id="timeTo" name="close[]" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Date..">
                                                </div>
                                            @endif
                                        @else
                                            <div class="form-group col-md-3">
                                                <label for="timeFrom">Open</label>
                                                <input id="timeFrom" name="open[]" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Date..">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="timeTo">Close</label>
                                                <input id="timeTo" name="close[]" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Date..">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="timeFrom">Open</label>
                                                <input id="timeFrom" name="open[]" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Date..">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="timeTo">Close</label>
                                                <input id="timeTo" name="close[]" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Date..">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="address">Hotel Address</label>
                                            <textarea class="form-control" id="address" name="address" placeholder="Hotel Address">{{ (isset($hotel) ? $hotel[0]->address : '' ) }}</textarea>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <div class="upload pr-md-2">
                                                @if (isset($hotel))
                                                    <input type="file" id="input-file-max-fs" class="dropify" data-default-file="{{ asset('public/hotel') }}/{{ $hotel[0]->image }}" name="image" data-max-file-size="1M" />
                                                @else
                                                    <input type="file" id="input-file-max-fs" class="dropify" data-default-file="" name="image" data-max-file-size="1M" />
                                                @endif
                                                <p class="mt-2"><i class="flaticon-cloud-upload mr-1"></i>Upload Hotel Image</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          <button type="submit" class="btn btn-primary">Save</button>
                        </form>
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

    <script src="{{ asset('public/assets/js/scrollspyNav.js') }}"></script>
    <script src="{{ asset('public/plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('public/plugins/noUiSlider/nouislider.min.js') }}"></script>

    <script src="{{ asset('public/plugins/flatpickr/custom-flatpickr.js') }}"></script>
    <script src="{{ asset('public/plugins/noUiSlider/custom-nouiSlider.js') }}"></script>
    <script src="{{ asset('public/plugins/bootstrap-range-Slider/bootstrap-rangeSlider.js') }}"></script>
    <script>
        var l = flatpickr(document.getElementsByClassName('flatpickr'), {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
        });
        @if (isset($hotel))
            var hotel = {!! json_encode($hotel->toArray()) !!};
            $("#type").val(hotel[0].type);

        @endif
    </script>
@endsection
