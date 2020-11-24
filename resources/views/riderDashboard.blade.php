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
    <style>
        #content{
            margin-top: 40px;
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
            <ul class="navbar-item flex-row ml-md-auto">

                <li class="nav-item dropdown notification-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle notificationOrder" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg><span class="badge badge-success"></span>
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="notificationDropdown">
                        <div class="notification-scroll">
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown user-profile-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <img src="{{ asset('public/assets/img/profile-11.jpg')}}" alt="avatar">
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                        <div class="">
                            <div class="dropdown-item">
                                <a class="" href="{{ url('/LogoutRider') }}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> Sign Out</a>
                            </div>
                        </div>
                    </div>
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
                <div class="row layout-spacing layout-top-spacing" id="cancel-row">
                    <div class="col-lg-12">
                        <div class="widget-content searchable-container list">
                            <div class="row">
                                <div class="col-xl-8 col-lg-7 col-md-7 col-sm-5 text-sm-right text-center layout-spacing align-self-center">
                                    <div class="d-flex justify-content-sm-end justify-content-center">
                                        <div class="switch align-self-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list view-list active-view"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3" y2="6"></line><line x1="3" y1="12" x2="3" y2="12"></line><line x1="3" y1="18" x2="3" y2="18"></line></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid view-grid"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="searchable-items list">
                                <div class="items items-header-section">
                                    <div class="item-content">
                                        <div class="name">
                                            <h4>Name</h4>
                                        </div>
                                        <div class="delivery_address">
                                            <h4>Delivery Address</h4>
                                        </div>
                                        <div class="hotel_name">
                                            <h4 style="margin-left: 0;">Hotel Name</h4>
                                        </div>
                                        <div class="hotel_address">
                                            <h4 style="margin-left: 3px;">Hotel Address</h4>
                                        </div>
                                        <div class="status">
                                            <h4 style="margin-left: 3px;">Status</h4>
                                        </div>
                                        <div class="action-btn">

                                        </div>
                                    </div>
                                </div>
                                @foreach ($pending as $p)
                                <div class="items">
                                    <div class="item-content">
                                        <div class="user-profile">
                                            <div class="user-meta-info">
                                                <p class="user-name">{{ $p->name }}</p>
                                                <p class="user-work">{{ $p->phone }}</p>
                                            </div>
                                        </div>
                                        <div class="user-email">
                                            <p class="info-title">Delivery Address </p>
                                            <p class="usr-email-addr" data-email="alan@mail.com">{{ $p->house_no }},{{ $p->landmark }}<br>{{ $p->address }}</p>
                                        </div>
                                        <div class="user-location">
                                            <p class="info-title">Hotel Name </p>
                                            <p class="usr-location" data-location="Boston, USA">{{ $p->hotel }}</p>
                                        </div>
                                        <div class="user-phone">
                                            <p class="info-title">Hotel Address </p>
                                            <p class="usr-ph-no" data-phone="+1 (070) 123-4567">{{ $p->hotel_address }}</p>
                                        </div>
                                        <div class="user-phone">
                                            <p class="info-title">Status</p>
                                            <p class="usr-ph-no" data-phone="+1 (070) 123-4567">{{ $p->status_name }}</p>
                                        </div>
                                        <div class="action-btn">
                                            <a href="{{ url('riderOrderDetail') }}/{{ $p->o_id }}" class="btn btn-warning">View</a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @foreach ($orders as $o)
                                <div class="items">
                                    <div class="item-content">
                                        <div class="user-profile">
                                            <div class="user-meta-info">
                                                <p class="user-name">{{ $o->name }}</p>
                                                <p class="user-work">{{ $o->phone }}</p>
                                            </div>
                                        </div>
                                        <div class="user-email">
                                            <p class="info-title">Delivery Address </p>
                                            <p class="usr-email-addr" data-email="alan@mail.com">{{ $o->house_no }},{{ $o->landmark }}<br>{{ $o->address }}</p>
                                        </div>
                                        <div class="user-location">
                                            <p class="info-title">Hotel Name </p>
                                            <p class="usr-location" data-location="Boston, USA">{{ $o->hotel }}</p>
                                        </div>
                                        <div class="user-phone">
                                            <p class="info-title">Hotel Address </p>
                                            <p class="usr-ph-no" data-phone="+1 (070) 123-4567">{{ $o->hotel_address }}</p>
                                        </div>
                                        <div class="user-phone">
                                            <p class="info-title">Status</p>
                                            <p class="usr-ph-no" data-phone="+1 (070) 123-4567">{{ $o->status_name }}</p>
                                        </div>
                                        <div class="action-btn">
                                            <a href="{{ url('riderOrderDetail') }}/{{ $o->o_id }}" class="btn btn-warning">View</a>
                                        </div>
                                    </div>
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
    <script src="{{ asset('public/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('public/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('public/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/app.js') }}"></script>
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script src="{{ asset('public/assets/js/custom.js') }}"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('public/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/apps/contact.js') }}"></script>
    <script>
        var count=0;
        var audio = new Audio("{{ asset('public/sound/bell.mp3') }}");
        $.when(getPendingOrderCount()).done(function(a1){
            setInterval(getPendingOrderCount, 10000);
        });
        function getPendingOrderCount() {
            return $.ajax({
                url: "{{ url('getPendingOrderCountRider') }}",
                method: 'get',
                success: function(result){
                    if(result>count)
                    {
                        audio.play();
                    }
                }
            });
        }

        $(".notificationOrder").click(function(){
            audio.pause();
            $.ajax({
                url: "{{ url('getPendingOrderDetail') }}",
                method: 'get',
                success: function(result){
                    $(".notification-scroll").html('');
                    count=result.length;
                    for(var i=0;i<result.length;i++)
                    {
                        var raw="<a href='{{url('/riderOrderDetail')}}/"+result[i].o_id+"' class='list-group-item list-group-item-action'> <div class='row align-items-center'> <div class='col-auto'></div><div class='col ml--2'> <div class='d-flex justify-content-between align-items-center'> <div> <h4 class='mb-0 text-sm'>"+result[i].name+"</h4> </div><div class='text-right text-muted'> <small>"+dateFormatted(result[i].date)+"</small> </div></div><p class='text-sm mb-0'>has placed a new order</p></div></div></a>";
                        $(".notification-scroll").append(raw);
                    }
                }
            });
        });
        function dateFormatted(d)
        {
            const date = new Date(d)
            const dateTimeFormat = new Intl.DateTimeFormat('en', { year: 'numeric', month: 'short', day: '2-digit' })
            const [{ value: month },,{ value: day },,{ value: year }] = dateTimeFormat .formatToParts(date )
            return(`${day}-${month}-${year }`)
        }
    </script>
</body>
</html>
