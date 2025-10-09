<!-- App favicon -->
{{-- <link rel="shortcut icon" href="{{ asset('theme/dist/default/assets/images/favicon.ico') }}"> --}}
<link rel="shortcut icon" href="{{ asset('Favicon.png') }}">
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- aos css -->
<link rel="stylesheet" href="{{ asset('theme/dist/default/assets/libs/aos/aos.css') }}" />
<!-- Layout config Js -->
<script src="{{ asset('theme/dist/default/assets/js/layout.js') }}"></script>
<!-- Bootstrap Css -->
<link href="{{ asset('theme/dist/default/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css"
    href="https://cdn.datatables.net/fixedcolumns/3.3.0/css/fixedColumns.dataTables.css" />
<link href="{{ asset('theme/dist/default/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet"
    type="text/css" />

{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" /> --}}
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
    media="screen">
<link href="{{ asset('theme/dist/default/assets/css/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet"
    type="text/css" />
<!-- Icons Css -->
<link href="{{ asset('theme/dist/default/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ asset('theme/dist/default/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
<!-- custom Css-->
<link href="{{ asset('theme/dist/default/assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css"
    href="https://cdn.jsdelivr.net/gh/exacti/floating-labels@latest/floating-labels.min.css" media="screen">
<link href="{{ asset('theme/dist/default/assets/libs/fullcalendar/main.min.css') }}" rel="stylesheet"
    type="text/css" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/u-c-h.css') }}">

<base href="{{ asset('') }}">

<!-- quill css -->
<link href="{{ asset('theme/dist/default/assets/libs/quill/quill.snow.css') }}" rel="stylesheet" type="text/css" />

<!-- prismjs plugin -->
<script src="{{ asset('theme/dist/default/assets/libs/prismjs/prism.js') }}"></script>
<script src="{{ asset('theme/dist/default/assets/libs/list.js/list.min.js') }}"></script>
<script src="{{ asset('theme/dist/default/assets/libs/list.pagination.js/list.pagination.min.js') }}"></script>

<!-- listjs init -->
<script src="{{ asset('theme/dist/default/assets/js/pages/listjs.init.js') }}"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
{{-- <link href="https://cdn.jsdelivr.net/npm/datetimepicker-jquery@2.5.11/jquery.datetimepicker.min.css" rel="stylesheet"> --}}
<link href="https://fonts.googleapis.com/earlyaccess/notonastaliqurdudraft.css" rel="stylesheet" type="text/css" />

<style type="text/css">
    .navbar-brand-box {
        padding: 15px 0;
    }


    .invalid-tooltip {
        padding: 0.1rem 0.4rem;
        right: 0;
    }

    .treegrid-indent {
        width: 0px;
        height: 16px;
        display: inline-block;
        position: relative;
    }

    .treegrid-expander {
        width: 0px;
        height: 16px;
        display: inline-block;
        position: relative;
        left: -17px;
        cursor: pointer;
    }

    .app-menu.navbar-menu,
    [data-layout=vertical][data-sidebar=dark] .navbar-menu,
    [data-layout=vertical][data-sidebar=dark][data-sidebar-size=sm] .navbar-brand-box,
    [data-layout=vertical][data-sidebar=dark][data-sidebar-size=sm] .navbar-menu .navbar-nav .nav-item:hover>.menu-dropdown {
        background-color: rgb(255, 255, 255);
    }

    [data-layout=vertical][data-sidebar=dark] .navbar-menu {
        border-right: rgb(255, 255, 255)
    }

    [data-layout=vertical][data-sidebar=dark] .navbar-nav .nav-link,
    [data-layout=vertical][data-sidebar=dark] .navbar-nav .nav-sm .nav-link,
    [data-layout=vertical][data-sidebar=dark] .menu-title {
        color: rgba(0, 0, 0, 0.92)
    }

    [data-layout=vertical][data-sidebar=dark] .navbar-nav .nav-link:hover,
    [data-layout=vertical][data-sidebar-size=sm] .navbar-menu .navbar-nav .nav-item.active:hover>a.menu-link {
        background-color: #ebf5ff
    }

    [data-layout=vertical][data-sidebar=dark] .navbar-nav .nav-link:hover i {
        color: #007AFF;
    }

    /* .nav-link.menu-link:not(.collapsed) {
        background: red;
    } */

    .nav-link.menu-link:after {
        display: none
    }

    .nav-link.menu-link.collapsed span {
        color: #0a2066
    }

    .nav-link.menu-link.collapsed:hover span {
        color: #007AFF
    }

    /* .nav-link.menu-link span {
        color: #007AFF;

    } */

    .nav-link:hover < .nav-link.menu-link span {
        color: #007AFF;
        background-color: #ebf5ff;
    }

    .app-menu.navbar-menu {
        position: 'relative'
    }

    .navbar-menu .navbar-nav .nav-link[data-bs-toggle=collapse]:after {
        display: none
    }

    #scrollbar {
        z-index: 1
    }

    .text_dir_rtl {
        direction: rtl;
        font-family: 'Noto Nastaliq Urdu Draft', serif;
        text-align: right;
    }

    a.disabled {
        pointer-events: none;
        cursor: default;
    }

    .ucs_loader {
        width: 100px;
        height: 100px;
    }

    .orange-bg {
        display: inline-block;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 600;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
        --vz-bg-opacity: 1;
        background-color: rgb(241 141 75) !important;
    }
</style>
