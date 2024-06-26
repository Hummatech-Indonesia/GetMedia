@extends('layouts.user.sidebar')

@section('content')
<div class="card shadow-sm position-relative overflow-hidden"  style="background-color: #175A95;">
    <div class="card-body px-4 py-4">
      <div class="row justify-content-between">
        <div class="col-8 text-white">
          <h4 class="fw-semibold mb-3 mt-2 text-white">Berita Disukai</h4>
            <p>Teruskan membaca berita, Perluas pegetahuan</p>
        </div>
        <div class="col-3">
          <div class="text-center mb-n4">
            <img src="{{asset('assets/img/news-bg.svg')}}" width="250px" alt="" class="img-fluid">
          </div>
        </div>
      </div>
    </div>
  </div>

<div class="">
    <div>
        <div class="d-flex gap-2">
            <select class="form-select" id="opsi-latest" style="width: 200px">
                <option value="">Tampilkan semua</option>
                <option value="terbaru">Terbaru</option>
                <option value="terlama">Terlama</option>
            </select>
  
            <select class="form-select" id="opsi-perpage" style="width: 200px">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-6 col-md-12">
            
            <div class="news-card-five">
                <div class="news-card-img">
                    <img src="{{ asset('storage/' . $item->photo) }}" class="" width="100%"
                                            height="150px" style="object-fit: cover" alt="Image">
                    {{-- <a href="business.html" class="news-cat">Lifestyle</a> --}}
                </div>
                <div class="news-card-info">
                    <h3>
                        <a data-toggle="tooltip" data-placement="top" title="{{ $item->name }}"
                            href="{{ route('news.user', ['news' => $item->slug, 'year' => $dateParts['year'], 'month' => $dateParts['month'], 'day' => $dateParts['day']]) }}">{!! Illuminate\Support\Str::limit($item->name, $limit = 50, $end = '...') !!}</a>
                    </h3>
                    <p>
                        {!! Illuminate\Support\Str::limit(strip_tags($item->content), 120, '...') !!}
                    </p>
                    <ul class="news-metainfo list-style">
                    <li>
                        <i class="fi fi-rr-calendar-minus"></i><a href="javascript:view(0)">{{ \Carbon\Carbon::parse($item->upload_date)->format('M d Y') }}</a>
                    </li>
                    <li>
                        <i>
                            <svg xmlns="http://www.w3.org/2000/svg" class="mb-1" width="20" height="20"
                            viewBox="0 0 24 24">
                            <path fill="#E93314"
                                d="M18 21H7V8l7-7l1.25 1.25q.175.175.288.475t.112.575v.35L14.55 8H21q.8 0 1.4.6T23 10v2q0 .175-.05.375t-.1.375l-3 7.05q-.225.5-.75.85T18 21m-9-2h9l3-7v-2h-9l1.35-5.5L9 8.85zM9 8.85V19zM7 8v2H4v9h3v2H2V8z" />
                            </svg>
                        </i><a href="javascript:void(0sing)">{{ $item->newsHasLikes->count() }}</a></li>
                    </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection