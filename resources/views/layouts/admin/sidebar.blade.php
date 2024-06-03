<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="/home">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-heading mt-3">Access & Permission</li>
      
      {{-- @can('read-user') --}}
        <li class="nav-item">
          <a class="nav-link collapsed {{ Request::is('user*') ? 'active' : '' }}" href="{{ route('user.index') }}">
            <i class="bi bi-person"></i>
            <span>User</span>
          </a>
        </li>
      {{-- @endcan --}}

      {{-- @can('read-permission') --}}
        <li class="nav-item">
          <a class="nav-link collapsed {{ Request::is('permission*') ? 'active' : '' }}" href="{{ route('permission.index') }}">
            <i class="bi bi-person"></i>
            <span>Permission</span>
          </a>
        </li>
      {{-- @endcan --}}
      
      {{-- @can('read-role') --}}
      <li class="nav-item">
        <a class="nav-link collapsed {{ Request::is('role*') ? 'active' : '' }}" href="{{ route('role.index') }}">
          <i class="bi bi-person"></i>
          <span>Role</span>
        </a>
      </li>
      {{-- @endcan --}}
       
      <!-- End Dashboard Nav -->

      <li class="nav-heading">Pages</li>
      {{-- @can('read-berita') --}}
      <li class="nav-item">
        <a class="nav-link collapsed {{ Request::is('berita*') ? 'active' : '' }}" href="{{ route('berita.index') }}">
          <i class="bi bi-person"></i>
          <span>Berita</span>
        </a>
      </li>
      {{-- @endcan --}}

      {{-- @can('read-foto') --}}
      <li class="nav-item">
        <a class="nav-link collapsed {{ Request::is('foto*') ? 'active' : '' }}" href="{{ route('foto.index') }}">
          <i class="bi bi-question-circle"></i>
          <span>Foto</span>
        </a>
      </li>      
      {{-- @endcan --}}

      {{-- @can('read-video') --}}
      <li class="nav-item">
        <a class="nav-link collapsed {{ Request::is('foto*') ? 'active' : '' }}" href="{{ route('foto.index') }}">
          <i class="bi bi-envelope"></i>
          <span>Video</span>
        </a>
      </li>      
      {{-- @endcan --}}

      {{-- @can('read-report')   --}}
      <li class="nav-item">
        <a class="nav-link collapsed"  href="#">
          <i class="bi bi-card-list"></i>
          <span>Report</span>
        </a>
      {{-- @endcan --}}
    </ul>

  </aside>
  <!-- End Sidebar-->