@extends('backend.admin-master')
@section('site-title')
    {{__('Add New Ethnicity')}}
@endsection
@section('style')
    <link rel="stylesheet" href="{{asset('assets/backend/css/bootstrap-tagsinput.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/fontawesome-iconpicker.min.css')}}">
    <x-summernote.css/>
    <x-media.css/>
    <style>
        .media-upload-btn-wrapper .img-wrap {
            position: relative;
            display: inline-block;
            max-width: 30%;
        }
    </style>
@endsection
@section('content')
    <div class="row g-4 mt-0">
        <div class="col-xl-6 col-lg-6 mt-0">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="header-wrap d-flex justify-content-between">
                    <div class="left-content">
                        <h4 class="header-title">{{__('Add New Ethnicity')}}   </h4>
                    </div>
                    <div class="right-content">
                        <a class="cmnBtn btn_5 btn_bg_blue radius-5" href="{{route('admin.subcategory')}}">{{__('All Ethnicity')}}</a>
                    </div>
                </div>
                <x-validation.error/>
                <form action="{{route('admin.subcategory.new')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form__input__flex">

                        <div class="form__input__single">
                            <label for="category_id" class="form__input__single__label">{{__('Parent Ethnicity')}}</label>
                            <select name="category_id" id="category_id" class="select2_activation radius-5">
                                @foreach($categories as $cat)
                                    <option value="{{$cat->id}}">{{$cat->name}}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form__input__single">
                            <label for="name" class="form__input__single__label">{{__('Sub Ethnicity')}}</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="{{__('Sub Ethnicity')}}">
                        </div>
                        <div class="form__input__single permalink_label">
                            <label class="text-dark form__input__single__label">{{__('Permalink * :')}}
                                <span id="slug_show" class="display-inline"></span>
                                <span id="slug_edit" class="display-inline">
                                     <button class="btn btn-warning btn-sm slug_edit_button"> <i class="fas fa-edit"></i> </button>
                                    <input type="text" name="slug" class="form-control subcategory_slug mt-2" style="display: none">
                                      <button class="btn btn-info btn-sm slug_update_button mt-2" style="display: none">{{__('Update')}}</button>
                                </span>
                            </label>
                        </div>
                        <div class="form__input__single">
                            <label class="form__input__single__label">{{__('Description')}}</label>
                            <input type="hidden" name="description">
                            <div class="summernote"></div>
                        </div>
                        <div class="form__input__single">
                            <label for="image" class="form__input__single__label">{{__('Upload Sub Ethnicity Image')}}</label>
                            <div class="media-upload-btn-wrapper">
                                <div class="img-wrap"></div>
                                <input type="hidden" name="image">
                                <button type="button" class="cmnBtn btn_5 btn_bg_blue radius-5 media_upload_form_btn"
                                        data-btntitle="{{__('Select Image')}}"
                                        data-modaltitle="{{__('Upload Image')}}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#media_upload_modal">
                                    {{__('Upload Image')}}
                                </button>
                            </div>
                        </div>
                        <x-meta.meta-section/>
                    </div>
                    <div class="btn_wrapper mt-4">
                        <button type="submit" id="update" class="cmnBtn btn_5 btn_bg_blue radius-5">{{ __('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection
@section('scripts')
    <script src="{{asset('assets/backend/js/bootstrap-tagsinput.js')}}"></script>
    <script src="{{asset('assets/backend/js/fontawesome-iconpicker.min.js')}}"></script>
    <script>
        <x-icon.icon-picker/>
    </script>
    <x-summernote.js/>
    <x-media.js />
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                //Permalink Code
                $('.permalink_label').hide();
                $(document).on('keyup', '#name', function (e) {
                    let slug = converToSlug($(this).val());
                    let url = "{{url('/subcategory/')}}/" + slug;
                    $('.permalink_label').show();
                    let data = $('#slug_show').text(url).css('color', 'blue');
                    $('.subcategory_slug').val(slug);
                });

                function converToSlug(slug){
                    let finalSlug = slug.replace(/[^a-zA-Z0-9]/g, ' ');
                    finalSlug = slug.replace(/  +/g, ' ');
                    finalSlug = slug.replace(/\s/g, '-').toLowerCase().replace(/[^\w-]+/g, '-');
                    return finalSlug;
                }

                //Slug Edit Code
                $(document).on('click', '.slug_edit_button', function (e) {
                    e.preventDefault();
                    $('.subcategory_slug').show();
                    $(this).hide();
                    $('.slug_update_button').show();
                });

                //Slug Update Code
                $(document).on('click', '.slug_update_button', function (e) {
                    e.preventDefault();
                    $(this).hide();
                    $('.slug_edit_button').show();
                    let update_input = $('.subcategory_slug').val();
                    let slug = converToSlug(update_input);
                    let url = `{{url('/subcategory/')}}/` + slug;
                    $('#slug_show').text(url);
                    $('.subcategory_slug').val(slug);
                    $('.subcategory_slug').hide();
                });

            });
        })(jQuery)
    </script>
@endsection
