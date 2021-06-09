<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $page }} - {{ config('app.name') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@100;200;300;400;500;600&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.23.0/slimselect.min.css" rel="stylesheet">

        <link href="{{ asset('assets') }}/css/buttons.dataTables.min.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js">
        <link href="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js">
        <link href="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js">
        <!-- Icons -->
        <link href="{{ asset('assets') }}/css/nucleo-icons.css" rel="stylesheet" />

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="{{asset('sweetalert/sweetalert.css')}}">
        <link href="{{ asset('assets') }}/dataTables.min.css" rel="stylesheet" />
        <link href="{{ asset('assets') }}/css/bootstrap.css?v=1.0.0" rel="stylesheet" />
        <link href="{{ asset('assets') }}/css/bootstrap.min.css?v=1.0.0" rel="stylesheet" />
        <link href="{{ asset('assets') }}/css/white-dashboard.css?v=1.0.0" rel="stylesheet" />
        <link href="{{ asset('assets') }}/css/theme.css" rel="stylesheet" />

        <link href="{{ asset('assets') }}/css/buttons.dataTables.min.css" rel="stylesheet" />
        <script src="{{asset('sweetalert/sweetalert.min.js')}}" type="text/javascript" charset="utf-8" async defer></script>




    </head>
    <body class="white-content {{ $class ?? '' }}">
        @auth()
            <div class="wrapper">
                    @include('layouts.navbars.sidebar')
                <div class="main-panel">
                    @include('layouts.navbars.navbar')

                    <div class="content">
                        @yield('content')
                    </div>

                    {{-- <div class="footer">
                        @include('layouts.footer')
                    </div> --}}
                </div>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @else
            @include('layouts.navbars.navbar')
            <div class="wrapper wrapper-full-page">
                <div class="full-page {{ $contentClass ?? '' }}">
                    <div class="content">
                        <div class="container">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        @endauth

        <script src="{{ asset('assets') }}/js/jszip.min.js"></script>
        {{-- <script src="{{ asset('assets') }}/js/pdfmake.min.js"></script> --}}
        <script src="{{ asset('assets') }}/js/vfs_fonts.js"></script>
        {{-- <script src="{{ asset('assets') }}/js/buttons.html5.min.js"></script> --}}
        {{-- <script src="{{ asset('assets') }}/js/buttons.print.min.js"></script> --}}

        <script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
        <script src="{{ asset('assets') }}/js/function.js"></script>
        <script src="{{ asset('assets') }}/js/core/popper.min.js"></script>
        <script src="{{ asset('assets') }}/js/core/bootstrap.min.js"></script>
        <script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.jquery.min.js"></script>
        <script src="{{ asset('assets') }}/js/core/bootstrap-material-datetimepicker.js"></script>
        <script src="{{ asset('assets') }}/datatables.min.js"></script>
        <!-- Chart JS -->
        {{-- <script src="{{ asset('assets') }}/js/plugins/chartjs.min.js"></script> --}}
        <!--  Notifications Plugin    -->
        <script src="{{ asset('assets') }}/js/plugins/bootstrap-notify.js"></script>

        <script src="{{ asset('assets') }}/js/white-dashboard.min.js?v=1.0.0"></script>
        <script src="{{ asset('assets') }}/js/theme.js"></script>
        <script>
            $(document).ready(function() {
                $().ready(function() {
                    $sidebar = $('.sidebar');
                    $navbar = $('.navbar');
                    $main_panel = $('.main-panel');

                    $full_page = $('.full-page');

                    $sidebar_responsive = $('body > .navbar-collapse');
                    sidebar_mini_active = true;
                    white_color = false;

                    window_width = $(window).width();

                    fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

                    $('.fixed-plugin a').click(function(event) {
                        if ($(this).hasClass('switch-trigger')) {
                            if (event.stopPropagation) {
                                event.stopPropagation();
                            } else if (window.event) {
                                window.event.cancelBubble = true;
                            }
                        }
                    });




                    $('.fixed-plugin .background-color span').click(function() {
                        $(this).siblings().removeClass('active');
                        $(this).addClass('active');

                        var new_color = $(this).data('color');

                        if ($sidebar.length != 0) {
                            $sidebar.attr('data', new_color);
                        }

                        if ($main_panel.length != 0) {
                            $main_panel.attr('data', new_color);
                        }

                        if ($full_page.length != 0) {
                            $full_page.attr('filter-color', new_color);
                        }

                        if ($sidebar_responsive.length != 0) {
                            $sidebar_responsive.attr('data', new_color);
                        }
                    });

                    $('.switch-sidebar-mini input').on("switchChange.bootstrapSwitch", function() {
                        var $btn = $(this);

                        if (sidebar_mini_active == true) {
                            $('body').removeClass('sidebar-mini');
                            sidebar_mini_active = false;
                            whiteDashboard.showSidebarMessage('Sidebar mini deactivated...');
                        } else {
                            $('body').addClass('sidebar-mini');
                            sidebar_mini_active = true;
                            whiteDashboard.showSidebarMessage('Sidebar mini activated...');
                        }

                        // we simulate the window Resize so the charts will get updated in realtime.
                        var simulateWindowResize = setInterval(function() {
                            window.dispatchEvent(new Event('resize'));
                        }, 180);

                        // we stop the simulation of Window Resize after the animations are completed
                        setTimeout(function() {
                            clearInterval(simulateWindowResize);
                        }, 1000);
                    });

                    $('.switch-change-color input').on("switchChange.bootstrapSwitch", function() {
                            var $btn = $(this);

                            if (white_color == true) {
                                $('body').addClass('change-background');
                                setTimeout(function() {
                                    $('body').removeClass('change-background');
                                    $('body').removeClass('white-content');
                                }, 900);
                                white_color = false;
                            } else {
                                $('body').addClass('change-background');
                                setTimeout(function() {
                                    $('body').removeClass('change-background');
                                    $('body').addClass('white-content');
                                }, 900);

                                white_color = true;
                            }
                    });

                    $('.light-badge').click(function() {
                        $('body').addClass('white-content');
                    });

                    $('.dark-badge').click(function() {
                        $('body').removeClass('white-content');
                    });
                });
            });
            $(function () {
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.23.0/slimselect.min.js"></script>
        @stack('js')


    </body>
</html>
