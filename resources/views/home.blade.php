@extends('layouts.admin.header')


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
                    <div class="col-md-10 my-3">
                        <div class="card">
                            <div class="card-header">BANK DATA</div>

                            <div class="card-body">
                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <h5>Halaman Admin Sistem Informasi Bank Data</h5>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ratione repellendus odio
                                    sint dolore sit nemo deserunt corporis accusantium voluptatem voluptates atque,
                                    praesentium iste. Suscipit quos molestiae officiis asperiores expedita aliquid!</p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</body>



@include('layouts.admin.footer')
