<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @stack('meta')
    <title>@yield('title')</title>
    @if (auth()->check())
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endif
    @include('layouts.admin.header')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav ml-auto">
                <li>
                    <a class="dropdown-item d-flex align-items-center"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </a>
                </li>

            </ul>
        </nav>
        @include('layouts.admin.sidebar')
        <div class="content-wrapper">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12 my-3">
                        <div class="card">
                            @yield('content')


                            @include('layouts.admin.footer')
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</body>
