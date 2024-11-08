@extends('layouts.main')
@section('content')
<style>
    #captcha {
        font-size: 20px;
        font-weight: bold;
        letter-spacing: 3px;
        text-align: center;
        background-color: #e0e0e0;
        border-radius: 5px;
        color: #333;
        padding: 10px;
        margin-bottom: 10px;
    }

    #refresh {
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 5px;
        background-color: #17a2b8;
        color: #fff;
        font-size: 14px;
        transition: 0.3s ease;
    }

    #refresh:hover {
        background-color: #138496;
    }

    #inputcaptcha {
        border-radius: 5px;
    }

    .fade-out {
        opacity: 0;
        transition: opacity 0.2 s ease - out;
    }

    .fade-in {
        opacity: 1;
        transition: opacity 0.3 s ease - in;
    }
</style>

<body class="index-page">
    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section dark-background">

            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center"
                        data-aos="zoom-out">
                        <h1>BANK DATA DINAS KOMINFO</h1>
                        <p>Kumpulan Foto,Video & Desain Kominfo Kota Padang</p>
                        <div class="d-flex">
                            <a href="#about" class="btn-get-started">Get Started</a>
                            {{-- <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8"
                                class="glightbox btn-watch-video d-flex align-items-center"><i
                                    class="bi bi-play-circle"></i><span>Watch Video</span></a> --}}
                        </div>
                    </div>
                    <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="200">
                        <img src="{{ asset('assets2/img/hero-img.png') }}" class="img-fluid animated" alt="">
                    </div>
                </div>
            </div>

        </section><!-- /Hero Section -->


        <!-- About Section -->
        <section id="about" class="about section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>About Us</h2>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row gy-4">

                    <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
                        <p>
                            Bank data dinas kominfo merupakan tempat pengumpulan data-data yang ada pada dinas
                            Kominfo
                            Kota Padang seperti Foto,Video,Web,Berita dan lain-lain.
                        </p>
                        {{-- <ul>
                            <li><i class="bi bi-check2-circle"></i> <span>Ullamco laboris nisi ut aliquip ex ea commodo
                                    consequat.</span></li>
                            <li><i class="bi bi-check2-circle"></i> <span>Duis aute irure dolor in reprehenderit in
                                    voluptate velit.</span></li>
                            <li><i class="bi bi-check2-circle"></i> <span>Ullamco laboris nisi ut aliquip ex ea
                                    commodo</span></li>
                        </ul> --}}
                    </div>

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                        <p>Website Bank Data ini dikelola oleh Bidang IKP Dinas Kominfo Kota Padang </p>
                        {{-- <a href="#" class="read-more"><span>Read More</span><i class="bi bi-arrow-right"></i></a> --}}
                    </div>

                </div>

            </div>

        </section><!-- /About Section -->

        <!-- Call To Action Section -->
        <section id="call-to-action" class="call-to-action section dark-background">

            <img src="{{ asset('assets2/img/cta-bg.jpg') }}" alt="">

            <div class="container">

                <div class="row" data-aos="zoom-in" data-aos-delay="100">
                    <div class="col-xl-9 text-center text-xl-start">
                        <h3>Call To Action</h3>
                        <p>Bidang IKP Dinas Kominfo Kota Padang</p>
                        <br>
                        <p>Bagi masyarakat Kota Padang yang ingin menyumbangkan/berbagi file untuk ditampilkan
                            pada
                            website ini, silahkan ditambahkan filenya melalui tombol berikut</p>
                    </div>
                    <div class="col-xl-3 cta-btn-container text-center">
                        <a class="cta-btn align-middle" href="#contact">Call To Action</a>
                    </div>
                </div>

            </div>

        </section><!-- /Call To Action Section -->

        <!-- Portfolio Section -->
        <section id="portfolio" class="portfolio section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Portfolio</h2>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

                    <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
                        <li data-filter="*" class="filter-active">All</li>
                        <li data-filter=".filter-foto">Foto</li>
                        <li data-filter=".filter-video">Video</li>
                        <li data-filter=".filter-web">Web</li>
                        {{-- <li data-filter=".filter-document">Doc</li> --}}
                    </ul><!-- End Portfolio Filters -->

                    <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">
                        @foreach ($media->take(6) as $item)
                        {{-- Hanya menampilkan 6 item --}}
                        @php
                        $categoryClass = '';
                        if (in_array($item->type, ['png', 'jpg', 'jpeg'])) {
                        $categoryClass = 'filter-foto';
                        } elseif (in_array($item->type, ['mp4', 'mov', 'avi'])) {
                        $categoryClass = 'filter-video';
                        } elseif ($item->type === 'web') {
                        $categoryClass = 'filter-web';
                        // } elseif (in_array($item->type, ['pdf', 'doc', 'docx'])) {
                        // $categoryClass = 'filter-document';
                        }
                        @endphp
                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item {{ $categoryClass }}">
                            @if (in_array($item->type, ['png', 'jpg', 'jpeg']))
                            <img src="{{ asset('storage/' . $item->file_path) }}" class="img-fluid"
                                alt="{{ $item->file }}">
                            @elseif(in_array($item->type, ['mp4', 'mov', 'avi']))
                            <video src="{{ asset('storage/' . $item->file_path) }}" class="img-fluid" autoplay
                                loop muted></video>
                            {{-- @elseif(in_array($item->type, ['pdf', 'doc', 'docx']))
                                        <div class="icon-preview">
                                            @if ($item->type === 'pdf')
                                                <i class="bi bi-file-earmark-pdf" style="font-size: 5rem; color: red;"></i>
                                            @elseif($item->type === 'doc' || $item->type === 'docx')
                                                <i class="bi bi-file-earmark-word"
                                                    style="font-size: 5rem; color: blue;"></i>
                                            @endif
                                        </div> --}}
                            @endif

                            <div class="portfolio-info">
                                <h4>{{ $item->file }}</h4>
                                <p>{{ $item->type }}</p>
                                <a href="{{ asset('storage/' . $item->file_path) }}" title="{{ $item->file }}"
                                    data-gallery="portfolio-gallery-{{ strtolower($categoryClass) }}"
                                    class="glightbox preview-link"><i class="bi bi-zoom-in"></i>
                                </a>
                                <a href="{{ route('detailHome', $item->id) }}" title="More Details"
                                    class="details-link">
                                    <i class="bi bi-link-45deg"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="text-center">
                    <a href="{{ route('files') }}" class="btn btn-info text-white mt-3 mx-auto justify-content-center">Load More</a>
                </div>
            </div>
        </section><!-- /Portfolio Section -->

        <!-- Contact Section -->
        <section id="contact" class="contact section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Tambah Berita</h2>
                <p>Form untuk masyarakat umum bagi yang ingin menambahkan berita</p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4">

                    <div class="col-lg">
                        <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up"
                            data-aos-delay="200">
                            @csrf
                            <div class="row gy-4">

                                <div class="col-md-6">
                                    <label for="name-field" class="pb-2">Your Name</label>
                                    <input type="text" name="name" id="name-field" class="form-control"
                                        required="">
                                </div>

                                <div class="col-md-6">
                                    <label for="email-field" class="pb-2">Your Email</label>
                                    <input type="email" class="form-control" name="email" id="email-field"
                                        required="">
                                </div>

                                <div class="col-md-12">
                                    <label for="subject-field" class="pb-2">Subject</label>
                                    <input type="text" class="form-control" name="subject" id="subject-field"
                                        required="">
                                </div>

                                <div class="col-md-12">
                                    <label for="message-field" class="pb-2">Berita</label>
                                    <textarea class="form-control" name="message" rows="10" id="message-field" required=""></textarea>
                                </div>

                                {{-- Captcha --}}
                                <div class=" form-group">
                                    <input type="text" name="captcha" id="captcha" readonly>
                                    <div id="refresh" class="btn btn-info btn-sm text-white"> <i
                                            class="bi bi-arrow-clockwise">&nbsp;</i>Refresh</div>
                                    <br>
                                    <small style="color: lightcoral">Pastikan Captcha yang anda masukan sesuai,
                                        jika
                                        salah maka
                                        upload gagal!</small><br>
                                    <input type="text" id="inputcaptcha" class="form-control my-3" required
                                        name="captchacek" placeholder="Captcha...">
                                    @if (session('error'))
                                    <div class="alert alert-warning" style="background-color: lightcoral;"
                                        role="alert">
                                        <strong>{{ session('error') }}</strong>
                                    </div>
                                    @endif
                                </div>
                                {{-- Captcha --}}


                                <div class="col-md-12 text-center">
                                    <div class="loading">Loading</div>
                                    <div class="error-message"></div>
                                    <div class="sent-message">Your message has been sent. Thank you!</div>

                                    <button type="submit">Send Message</button>
                                </div>

                            </div>
                        </form>
                    </div><!-- End Contact Form -->

                </div>

            </div>

        </section><!-- /Contact Section -->

    </main>
    @endsection