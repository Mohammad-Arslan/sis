<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">

<head>

    <meta charset="utf-8" />
    <title>SUPER NOVA | SIS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('layouts.header_scripts')
    @stack('header_scripts')

</head>

<body>

    @if(Auth::guard()->check())
    <div id="layout-wrapper">

        @include('layouts.header')

        @role('super_admin')
        @include('layouts.side_nav')
        @else
        @include('layouts.nwa_side_nav')
        @endrole

        {{--@role('network_associate|teacher')
            @include('layouts.nwa_side_nav')
            @endrole--}}


        <div class="vertical-overlay"></div>

        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    {{--@include('layouts.breadcrum')--}}
                    @yield('content')

                </div>
            </div>

            @include('layouts.footer')

        </div>
    </div>
    @else
    {{--Without Header Footer Sidebar for Guest Auth--}}
    <div class="container-fluid">

        @include('layouts.breadcrum')
        @yield('content')

    </div>
    @endif

    <div id="modal-div"></div>

    @include('layouts.theme_setting')
    @include('layouts.footer_scripts')

    @stack('footer_scripts')


</body>

</html>