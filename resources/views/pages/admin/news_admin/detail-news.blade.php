@extends('layouts.admin.app')

@section('content')
<head>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/dist/imageuploadify.min.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <style>
        .news-card-a {
            box-shadow: 0 5px 2px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
            padding: 20px;
            /* Memberi ruang di sekitar elemen */
            margin-bottom: 20px;
            /* Memberi jarak antara elemen berikutnya */
            border-radius: 10px;
        }
    </style>
</head>

<div class="container" style="margin-top: 3%;">
    <h2 class="text-center">Detail Berita</h2>
        <h5 class="text-left mb-0">Detail / {{ $news->name }}</h6>
            <form method="post" action="{{ route('update.news.admin', ['news' => $news->id]) }}" enctype="multipart/form-data">
                @method('post')
                @csrf
        <div class="news-card-a mt-5">
            <div class="container" style="padding: 3%;">

                    <div class="justify-content-start">
                        <div class="col-lg-6 col-md-20 from-outline">
                            <label class="form-label" for="photo">Thumbnail Berita</label>
                            <div>
                                <img width="400px" src="{{ asset('storage/' . $news->photo) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-between mt-2">
                        <div class="col-lg-6 col-md-12 from-outline">
                            <label class="form-label" for="nomor">Judul Berita</label>
                            <input type="text" name="name" class="form-control" value="{{ $news->name }}">
                        </div>
                        <div class="col-lg-6 col-md-12 from-outline">
                            <label class="form-label" for="password_confirmation">Tags</label>
                            <input type="text"  name="tags" class="form-control" value="{{ $news->tags }}">
                        </div>
                            <div class="col-lg-6 col-md-12 from-outline">
                                <label class="form-label" for="password_confirmation">Kategori</label>
                                <input type="text" name="category_id" class="form-control" value="{{ $news->category->name }}">
                            </div>
                            <div class="col-lg-6 col-md-12 from-outline">
                                <label class="form-label" for="password_confirmation">Sub Kategori</label>
                                <input type="text" class="form-control" value="{{ $news->subCategory->name }}">
                            </div>
                    </div>

                    <div class="row justify-content-between mt-2">
                        <div class="col-lg-6 col-md-12 col-span-2 from-outline">
                            <label class="form-label" for="content">Content</label>
                            <textarea class="form-control" name="content" rows="10"  value="{{ $news->content }}" style="resize: none;">{!! $news->content !!}</textarea>
                        </div>
                        <div class="col-lg-6 col-md-12 row-span-1 from-outline">
                            <div class="mt-2">
                                <label class="form-label" for="password_confirmation">Tanggal Upload</label>
                                <input type="date" name="upload_date" class="form-control" value="{{ $news->upload_date }}">
                            </div>
                            <div class="mt-2">
                                <label class="form-label" for="password_confirmation">Multi Gambar</label>
                                <div>
                                    @foreach ($newsPhoto as $photo)
                                    <img width="200px" src="{{ asset('storage/' . $photo->multi_photo) }}" alt="{{ $photo->multi_photo }}">
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between ms-5 me-5 mb-3">
                @if ($news->status === "panding")
                    <div>
                        <a href="{{ route('approved-news.index') }}" class="btn btn-lg px-3 text-black" style="padding-left: 1rem; padding-right: 1rem; background-color: #C9C9C9;">Kembali</a>
                    </div>
                @else
                    <div>
                        <a href="{{ route('list.approved') }}" class="btn btn-lg px-3 text-black" style="padding-left: 1rem; padding-right: 1rem; background-color: #C9C9C9;">Kembali</a>
                    </div>
                @endif

                @if ($news->status === "panding")
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success btn-lg px-3">Simpan</button>

                    <form action="{{ route('approved-news', ['news' => $news->id]) }}" method="post">
                        @method('patch')
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg px-3">Approved</button>
                    </form>
                    <button type="submit" class="btn btn-danger btn-lg px-3 btn-reject" id="btn-reject-{{ $news->id }}">Tolak</button>
                </div>
                @else
                <div>
                    <a href="{{ route('news.option.editor', ['news' => $news->id]) }}" class="btn btn-lg px-3 btn-{{ $news->is_primary ? 'primary' : 'dark'}}">
                        <div class="d-flex gap-2">
                                {{ $news->is_primary ? '' : ''}} <i><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M16 9V4h2V2H6v2h2v5c0 1.66-1.34 3-3 3v2h5.97v7l1 1l1-1v-7H19v-2c-1.66 0-3-1.34-3-3"/></svg></i>
                                Pin Berita
                            </div>
                        </a>
                    </div>
                    @endif
                </div>
            </form>

            </div>
        </div>
    </div>
</div>


@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    $('#content').summernote
    ({
        height: 275,
        toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
        ]

    });
});
</script>

<script src="{{ asset('assets/dist/imageuploadify.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#image-uploadify').imageuploadify();
    })

    $('.category').change(function() {
        getSubCategory($(this).val())
    })

    function getSubCategory(id) {
        $.ajax({
            url: "sub-category-detail/" + id,
            method: "GET",
            dataType: "JSON",
            beforeSend:function(){
                $('.sub-category').html('')
            },
            success: function(response) {
                $.each(response.data, function(index, data) {
                    $('.sub-category').append('<option value="' + data.id +'">' + data.name + '</option>');
                });
            }
        })
    }
</script>
@endsection