<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="/home" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">IKP</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="/home" class="d-block"><b>{{ Auth::user()->name }}</b></a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-item">
                    <a href="/home" class="nav-link {{ Request::is('home*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{ route('media.index') }}" class="nav-link {{ Request::is('media*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Media
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.index') }}" class="nav-link {{ Request::is('user*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-alt"></i>
                        <p>
                            User
                        </p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{ route('categories.index') }}"
                        class="nav-link {{ Request::is('categories*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Category
                        </p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{ route('berita.index') }}" class="nav-link {{ Request::is('berita*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Berita
                        </p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{ route('video.index') }}" class="nav-link {{ Request::is('video*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Video
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('foto.index') }}" class="nav-link {{ Request::is('foto*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Foto
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('weblink.index') }}"
                        class="nav-link {{ Request::is('weblink*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Web/Link
                        </p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{ route('user.index') }}" class="nav-link {{ Request::is('user*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            User
                        </p>
                    </a>
                </li> --}}


            </ul>
        </nav>

    </div>

</aside>
