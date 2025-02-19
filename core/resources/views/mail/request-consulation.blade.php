<!doctype html>
@php
    $default_lang = get_default_language();
@endphp
<html lang="{{$default_lang}}" dir="{{ get_user_lang_direction() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{__('Request Quote From') }} {{get_static_option('site_title')}}</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *{
            font-family: 'Open Sans', sans-serif;
        }
        .mail-container {
            max-width: 650px;
            margin: 0 auto;
            text-align: center;
            background-color: #f2f2f2;
            padding: 40px 0;
        }
        .inner-wrap {
            background-color: #fff;
            margin: 40px;
            padding: 30px 20px;
            text-align: left;
            box-shadow: 0 0 20px 0 rgba(0,0,0,0.01);
        }
        .inner-wrap p {
            font-size: 16px;
            line-height: 26px;
            color: #656565;
            margin: 0;
            margin-bottom: 20px;
        }
        .message-wrap {
            background-color: #f2f2f2;
            padding: 30px;
            margin-top: 40px;
        }

        .message-wrap p {
            font-size: 14px;
            line-height: 26px;
        }
        .btn-wrap {
            text-align: center;
        }

        .btn-wrap .anchor-btn {
            background-color: {{get_static_option('site_color')}};
            color: #fff;
            font-size: 14px;
            line-height: 26px;
            font-weight: 500;
            text-transform: capitalize;
            text-decoration: none;
            padding: 8px 20px;
            display: inline-block;
            margin-top: 40px;
            border-radius: 5px;
            transition: all 300ms;
        }

        .btn-wrap .anchor-btn:hover {
            opacity: .8;
        }
        .verify-code{
            background-color:#f2f2f2;
            color:#333;
            padding: 10px 15px;
            border-radius: 3px;
            display: inline-block;
            margin: 20px;
        }
        table {
            margin: 0 auto;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }

        table td, table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table tr:nth-child(even){background-color: #f2f2f2;}

        table tr:hover {background-color: #ddd;}

        table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #111d5c;
            color: white;
        }

        /*RTL CSS Start*/
        [dir="rtl"] .earning-wrapper {
            text-align: right !important;
        }
        [dir="rtl"] .earning-wrapper .earning-title {
            text-align: right !important;
        }
        [dir="rtl"] .wrap-para {
            text-align: right !important;
        }
        [dir="rtl"] .inner-wrap-contents p {
            text-align: right !important;
        }
        [dir="rtl"] .inner-wrap-contents .earning-order-title {
            text-align: right !important;
        }
        [dir="rtl"] .earning-title {
            text-align: right !important;
        }
        /*RTL CSS end*/
    </style>
</head>
<body>
    <div class="mail-container">
        <div class="logo-wrapper">
            <a href="{{url('/')}}">
                @php
                    $site_logo = get_attachment_image_by_id(get_static_option('site_logo'),"full",false);
                @endphp
                @if (!empty($site_logo))
                    <img src="{{$site_logo['img_url']}}" alt="{{get_static_option('site__title')}}">
                @endif
            </a>
        </div>
      <div class="inner-wrap">
          <p class="wrap-para">{{__('Hello')}} <br> {{__('You Have A Consultation From')}} {{get_static_option('site_title')}}</p>
          <table class="wrap-para">
              @foreach($data as $key => $field)
                  @php $name = str_replace(['-','_'],[' ',' '],$key); @endphp
                  <tr>
                      <td>{{ucwords($name)}}</td>
                      <td>{{htmlspecialchars(strip_tags($field))}}</td>
                  </tr>
              @endforeach
          </table>
      </div>
        <footer>
            {!! render_footer_copyright_text() !!}
        </footer>
    </div>
</body>
</html>