<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>IKP</title>
</head>
<body>
  @include('layouts.admin.header')
  @include('layouts.admin.navbar')

  @include('layouts.admin.sidebar')

  <main id="main" class="main">
    
    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/home">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div>
    <!-- End Page Title -->
    
    <section class="section dashboard">
      <div class="row">
        <div class="col-lg-12">
          <div class="row">
            <!-- Customers Card -->
            <div class="col-xxl-4 col-xl-12">
              <div class="card info-card customers-card">
                <div class="card-body">
                  {{-- <h5 class="card-title">Table <span>| This Year</span></h5> --}}
                  {{-- Table --}}
                  @yield('content')

                  {{-- Table --}}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>


  
  @include('layouts.admin.footer')  
</body>
</html>