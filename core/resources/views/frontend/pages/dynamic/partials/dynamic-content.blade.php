<section class="dynamic-page-content-area section-bg-2 pat-100 pab-100">
    <div class="container-1440">
        <div class="row">
            <div class="col-lg-12">
                @if (!auth()->guard('web')->check() && $page_post->visibility === 'all')
                    <div class="dynamic-page-content-wrap">
                        {!! $page_post->page_content !!}
                    </div>
                @elseif(auth()->guard('web')->check())
                    <div class="dynamic-page-content-wrap">
                        {!! $page_post->page_content !!}
                    </div>
                @else
                    <div class="alert alert-warning">
                        <p><a class="text-primary" href="{{route('user.login')}}">{{__('Login')}}</a> {{__('to see this page')}} </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
