@extends('layouts.main')
@section('content')
    <main class="main">
        <section id="portfolio" class="portfolio section">
            <br>
            <div class="container section-title mt-3" data-aos="fade-up">
                <h2>Portfolio All</h2>
            </div>

            <div class="container">
                <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">
                    <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
                        <li data-filter="*" class="filter-active">All</li>
                        <li data-filter=".filter-foto">Foto</li>
                        <li data-filter=".filter-video">Video</li>
                        <li data-filter=".filter-web">Web</li>
                        <li data-filter=".filter-document">Doc</li>
                    </ul><!-- End Portfolio Filters -->

                    <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">
                        @foreach ($medialist as $item)
                            @php
                                $categoryClass = '';
                                if (in_array($item->type, ['png', 'jpg', 'jpeg'])) {
                                    $categoryClass = 'filter-foto';
                                } elseif (in_array($item->type, ['mp4', 'mov', 'avi'])) {
                                    $categoryClass = 'filter-video';
                                } elseif ($item->type === 'web') {
                                    $categoryClass = 'filter-web';
                                } elseif (in_array($item->type, ['pdf', 'doc', 'docx'])) {
                                    $categoryClass = 'filter-document';
                                }
                            @endphp
                            <div class="col-lg-4 col-md-6 portfolio-item isotope-item {{ $categoryClass }}">
                                @if (in_array($item->type, ['png', 'jpg', 'jpeg']))
                                    <img src="{{ asset('storage/' . $item->file_path) }}" class="img-fluid"
                                        alt="{{ $item->file }}">
                                @elseif(in_array($item->type, ['mp4', 'mov', 'avi']))
                                    <video src="{{ asset('storage/' . $item->file_path) }}" class="img-fluid" autoplay loop
                                        muted></video>
                                @elseif(in_array($item->type, ['pdf', 'doc', 'docx']))
                                    <div class="icon-preview" class="img-fluid">
                                        @if ($item->type === 'pdf')
                                            <i class="bi bi-file-earmark-pdf" style="font-size: 5rem; color: red;"></i>
                                        @elseif($item->type === 'doc' || $item->type === 'docx')
                                            <i class="bi bi-file-earmark-word" style="font-size: 5rem; color: blue;"></i>
                                        @endif
                                    </div>
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

                    <!-- End Portfolio Container -->
                </div>
            </div>
        </section>
    </main>
@endsection
