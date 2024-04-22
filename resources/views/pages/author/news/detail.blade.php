@extends('layouts.author.sidebar')

@section('style')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/dist/imageuploadify.min.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    {{-- <style>
        .news-card-a {
            border: 1px solid #ddd;
            margin-bottom: 20px;
            border-radius: 10px;
            background-color: #fff;
        }
    </style> --}}
@endsection

<head>
    <title>Admin | News-Detail</title>
</head>

@section('content')
    <div class="container" style="margin-top: 3%;">
            <div class="card border border-1 shadow-sm mt-5" style="background-color: #FFFFFF">
                <div class="px-4 py-3 border-bottom">
                    <h5 class="card-title fw-semibold mb-0 lh-sm">Detail Berita</h5>
                </div>

                <div class="d-flex justify-content-between mt-4 ms-4 me-4">

                    <div class="d-flex justify-content-start gap-2">

                        {{-- @if ($news->status === 'panding')
                            <div>
                                @if ($news->status === "panding")
                                <div>
                                    <a href="{{ route('approved-news.index') }}" class="btn btn-lg px-3 text-white" style="background-color: #5D87FF;">Kembali</a>
                                </div>
                                @else
                                <div>
                                    <a href="{{ route('news.approve.admin') }}" class="btn btn-lg px-3 text-white" style="background-color: #5D87FF;">Kembali</a>
                                </div>
                                @endif
                            </div>
                        @else
                            <div>
                                <a href="{{ route('list.approved.index') }}" class="btn btn-lg px-3 text-white"
                                    style="background-color: #5D87FF;">Kembali</a>
                            </div>
                        @endif --}}
                    </div>
                </div>

                <div class="container p-4">

                    <div class="card border shadow-none p-3">
                        <div class="row justify-content-between mt-2">

                            <div class="col-lg-12 col-md-12 from-outline mt-2">
                                <label class="form-label" for="photo">Thumbnail Berita</label>
                                <div>
                                    <img width="350px" src="{{ asset('storage/' . $news->photo) }}">
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 row-span-1 from-outline mt-5">
                                <div class="mt-2">
                                    <label class="form-label" for="password_confirmation">Multi Gambar</label>
                                    <div class="d-flex gap-2">
                                        @foreach ($newsPhoto as $photo)
                                            <img width="320 px" src="{{ asset('storage/' . $photo->multi_photo) }}"
                                                alt="{{ $photo->multi_photo }}">
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12 from-outline mt-4">
                                <label class="form-label">Judul Berita</label>
                                {{-- <input type="text" name="name" class="form-control" value="{{ $news->name }}"> --}}
                                <h5>{{ $news->name }}</h5>
                            </div>
                            <div class="col-lg-6 col-md-12 from-outline mt-4">
                                <label class="form-label">Penulis</label>
                                <h5>{{ $news->author->user->name }}</h5>
                            </div>
                            <div class="col-lg-6 col-md-12 from-outline mt-2">
                                <label class="form-label">Tanggal Upload</label>
                                <h5>{{ $news->upload_date }}</h5>
                            </div>
                            <div class="col-lg-6 col-md-12 from-outline mt-2">
                                <label class="form-label" for="password_confirmation">Tags</label>
                                <select class="form-control select2 tags" name="tags[]" multiple="multiple">
                                    <option>pilih tags</option>
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->name }}"
                                            {{ $newsTags->contains('tag_id', $tag->id) ? 'selected' : '' }}>
                                            {{ $tag->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-12 from-outline mt-2">
                                <label class="form-label" for="password_confirmation">Kategori</label>
                                <select id="category_id"
                                    class="select2 form-control category @error('category') is-invalid @enderror"
                                    name="category[]" multiple="true" value="" aria-label="Default select example">
                                    <option>pilih kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $newsCategories->contains('category_id', $category->id) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-12 from-outline mt-2">
                                <label class="form-label" for="password_confirmation">Sub Kategori</label>
                                <select id="sub_category_id"
                                    class="form-control sub-category select2 @error('sub_category') is-invalid @enderror"
                                    name="sub_category[]" multiple="true" value="" aria-label="Default select example">
                                    <option>pilih sub kategori</option>
                                    @foreach ($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}"
                                            {{ $newsSubCategories->contains('sub_category_id', $subCategory->id) ? 'selected' : '' }}>
                                            {{ $subCategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row justify-content-between mt-2">
                            <div class="">
                                <label class="form-label" for="content">Content</label>
                                <textarea class="form-control" name="content" rows="10" value="{{ old('content') }}" id="content" style="resize: none; height: 400;">{!! $news->content !!}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
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
        // var edit = function() {
        //     $('#content').summernote({
        //         height: 400,
        //         toolbar: [
        //             ['style', ['style']],
        //             ['font', ['bold', 'underline', 'clear']],
        //             ['color', ['color']],
        //             ['para', ['ul', 'ol', 'paragraph']],
        //             ['table', ['table']],
        //             ['insert', ['link', 'picture', 'video']],
        //             ['view', ['fullscreen', 'codeview', 'help']]
        //         ]
        //     });
        // };

        $(document).ready(function() {
            $('#content').summernote({
                height: 400,
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

        // var save = function() {
        //     var markup = $('#content').summernote('code');
        //     $('#content').summernote('destroy');
        // }
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
                beforeSend: function() {
                    $('.sub-category').html('')
                },
                success: function(response) {
                    $.each(response.data, function(index, data) {
                        $('.sub-category').append('<option value="' + data.id + '">' + data.name +
                            '</option>');
                    });
                }
            })
        }
    </script>

    <script>
        $(".tags").select2({
            tags: true,
            tokenSeparators: [',', ' ']
        })
    </script>

    <script>
        $('.btn-reject').click(function() {
            const formData = getDataAttributes($(this).attr('id'))
            $('#detail-synopsis').html(formData['synopsis'])
            handleDetail(formData)
            $('#modal-reject').modal('show')
        })
    </script>
@endsection