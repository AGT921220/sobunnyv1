@extends('frontend.layout.master')
@section('site-title')
    @if($child_category !='')
        {{ $child_category->name }}
    @endif
@endsection
@section('page-title')
    @if($child_category !='')
        {{ $child_category->name }}
    @endif
@endsection
@section('inner-title')
    @if($child_category !='')
        {{ $child_category->name }}
    @endif
@endsection
@section('page-meta-data')
    {!!  render_page_meta_data_for_child_category($child_category) !!}
@endsection
@section('content')
    <div class="catagory-wise-listing section-padding2">
        <div class="container-1440">
            <x-breadcrumb.user-profile-breadcrumb
                :title="''"
                :innerTitle="$child_category->category?->name"
                :subInnerTitle="$child_category->subcategory?->name"
                :chidInnerTitle="$child_category->name"
                :routeName="route('frontend.show.listing.by.category', $child_category->category?->slug ?? 'x')"
                :subRouteName="route('frontend.show.listing.by.subcategory', $child_category->subcategory?->slug ?? 'x')"
            />

            <x-validation.frontend-error/>

            @if(!is_null($child_category->description))
            <div class="row g-4 mt-4">
                <div class="col-12">
                    <div class="category_info_new mb-5 mt-2">
                        {!! $child_category->description !!}
                    </div>
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-lg-12">
                    <section class="featureListing mb-5 mt-5">
                        <div class="container-1440">
                            <div class="titleWithBtn d-flex justify-content-between align-items-center mb-40">
                                <h3 class="catagory-wise-title">{{ sprintf(__('Available Listings in :child_category', ['child_category' => $child_category->name]))  }}</h3>
                                <form id="filter_with_listing_page_subcategory" action="{{ url('/') .'/'. get_static_option('listing_filter_page_url') ?? url('/listings') }}" method="get">
                                    <input type="hidden" name="cat" value="{{$child_category->category_id}}"/>
                                    <input type="hidden" name="subcat" value="{{$child_category->sub_category_id}}"/>
                                    <input type="hidden" name="child_cat" value="{{$child_category->id}}"/>
                                    <a href="#" id="submit_form_listing_filter_subcategory" class="see-all">{{ __('See All') }}<i class="las la-angle-right"></i></a>
                                </form>
                            </div>
                            <div class="slider-inner-margin">
                                <x-listings.listing-single :listings="$all_listings"/>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
         </div>
     </div>
@endsection
