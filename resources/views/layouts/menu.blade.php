<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{config('app.name')}} | @yield('title')</title>
    <link rel="icon" href="{{asset('img/icon.png')}}" type="image/png" sizes="16x16">
    @include('layouts.menu_css')
</head>

<body class="hold-transition sidebar-mini sidebar-collapse layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <div class="logo-group">
                        <img src="{{ asset(config('app.logo')) }}" alt="EnRuta" height="40px">
                        <div class="separator-line"></div>
                        <img src="{{ asset('img/company_logo.png') }}" alt="Logo" height="40px">
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar elevation-3 sidebar-light-warning">
            <div class="brand-link">&nbsp;</div>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ asset('img/user.png')}}" class="img-circle" alt="User Image">
                    </div>
                    <div class="info">
                        <p class="mb-0 text-white">{{ Auth::user()->name }}</p>
                    </div>
                </div>
                <!-- /.Sidebar user panel -->

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link">
                                <i class="nav-icon fa fa-home"></i>
                                <p>@lang('base_lang.home')</p>
                            </a>
                        </li>

                        @if(Auth::user()->is_admin)
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                    @lang('base_lang.admin')
                                    <i class="right fas fa-angle-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('roles.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>@lang('base_lang.roles')</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>@lang('base_lang.users')</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @if(App\Http\Validations\Validation::validate('master'))
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-share-alt"></i>
                                <p>
                                    @lang('base_lang.masters')
                                    <i class="right fas fa-angle-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @if(App\Http\Validations\Validation::permissionsUser('lists'))
                                <li class="nav-item">
                                    <a href="{{ route('lists.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>@lang('base_lang.lists')</p>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif

                        @if(Auth::user()->is_super_admin)
                        <li class="nav-item">
                            <a href="{{ url('log-viewer/logs') }}" class="nav-link" target="_blank">
                                <i class="nav-icon fa fa-exclamation-triangle"></i>
                                <p>@lang('base_lang.log')</p>
                            </a>
                        </li>
                        @endif

                        <li class="nav-item">
                            @impersonating
                            <a href="{{ route('impersonate.leave') }}" class="nav-link">
                                <i class="nav-icon fa fa-sign-out-alt"></i>
                                <p>@lang('users.leave_impersonate', ['name' => Auth::user()->name])</p>
                            </a>
                            @else
                            <a href="{{ route('logout') }}" class="nav-link"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="nav-icon fa fa-sign-out-alt"></i>
                                <p>@lang('base_lang.logout')</p>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            @endImpersonating
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">

                            <h6 class="m-0 text-dark"><b>@yield('title_page')</b></h6>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                @yield('content_page')
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer text-center">
            <img src="{{ asset('img/powered.png?id=new')}}" alt="" width="40px;">
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    @include('layouts.menu_js')
    @yield('javascript')
</body>

</html>