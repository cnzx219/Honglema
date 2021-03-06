@extends('layouts.app')

@section('content')
<!-- TODO: Current Tasks -->
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />
    <title>index</title>
    <link rel="stylesheet" type="text/css" href="{{URL::asset('css/normalize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('css/main.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('css/style.css')}}">
    <style>
        .v_header{
            width: 100%;
            height: 50px;
            background-color: #fff;
            margin: 0px auto;
            border-bottom: 1px solid #f6f6f6;
        }
        .v_header img{
            position: absolute;
            margin: 10px;
            width: 30px;
        }
        .headin{
            width: 100%;
            text-align: center;
            color: deeppink;
            font-size: 20px;
            padding: 15px 0 0 0;
        }
        .name{
            font-size: 16px;
            color: rgba(0,0,0,0.8);
            margin-left: 0;
        }
        .fensi{
            margin-top: 8px;
            color: darkgray;
            font-size: 14px;
        }
        li{
            background-color: pink;
        }
        .selected{
            color: #ff5000;
            border-bottom-width: 1px;
            border-bottom-color: #ff5000;
            border-bottom-style: solid;
        }
        .unselected{
            color: #777777;
        }
    </style>
</head>
<body>
<div class="htmleaf-container">

    <div style="width:100%;height: 1px;background-color: #fff;margin: 0px auto;"></div>
    <div style="width:100%;margin: 0 auto;">
        <img style="width: 100%;vertical-align: middle;" src="http://image.weipai.cn/honglema/banner/banner20160318.jpg" />
    </div>

    <div id="sort-menu" class="single-page">
        <div class="single-page-artical">
            <div class="artical-content">
                <div>
                    <table style=" width: 100%; text-align: center; font-size: 16px; ">
                        <tr>
                            <td class="{!! $sort === 'comprehensive'  ?  'selected': 'unselected' !!}" style="width:33%;padding-bottom: 12px; padding-top: 12px;" data-key="comprehensive">综合排序</td>
                            <td class="{!! $sort === 'fans'  ?  'selected': 'unselected' !!}" style="width:33%;padding-bottom: 12px; padding-top: 12px;" data-key="fans">粉丝量排序</td>
                            {{--<td style="width:33%;" data-key="view">浏览量排序</td>--}}
                        </tr>
                    </table>
                    <div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>

    <div role="main">
        <ul id="container" class="tiles-wrap animated" style="opacity: 0;">
            <!-- These are our grid blocks -->
            @if (count($celebrities) > 0)
            @foreach ($celebrities as $celebrity)
            <li>
                <a href="{{ url('/celebrity/'.$celebrity->id) }}">
                    @if( $celebrity->profile_img != null )
                    <img src="{{ $celebrity->profile_img }}" style="width: 100%;">
                    @else
                    @foreach ( $celebrity->pictures as $picture)
                    @if (str_contains($picture, 'jpg') || str_contains($picture, 'jpeg'))
                    <img src="{{ $picture->url }}" style="width: 100%;">
                    @break;
                    @endif
                    @endforeach
                    @endif
                </a>
                <div style="padding: 8px;">
                    <div class="name">{{ $celebrity->nickname }}</div>
                    <div class="fensi">
                        粉丝&nbsp;{{ $celebrity->total_fans_num }}
                    </div>
                </div>
            </li>
            @endforeach
            @endif
            <!-- End of grid blocks -->
        </ul>
    </div>
    <div id='imloading' style='width:150px;height:30px;line-height:30px;font-size:16px;text-align:center;border-radius:3px;opacity:0.7;background:#000;margin:10px auto 30px;color:rgba(255,255,255,0.5);'>
        正在加载...
    </div>
</div>

<!-- include jQuery -->
<script type="text/javascript" src="{{URL::asset('js/jquery-1.8.3.min.js')}}"></script>
<!-- Include the imagesLoaded plug-in -->
<script src="{{URL::asset('js/imagesloaded.pkgd.min.js')}}"></script>

<!-- Include the plug-in -->
<script src="{{URL::asset('js/wookmark.min.js')}}"></script>

<!-- Once the page is loaded, initalize the plug-in. -->
<script type="text/javascript">
    $(".container").imagesLoaded(function () {
        setTimeout(function(){
            $("#container").css('opacity', 1);
        },800);
    });
    (function ($) {
        var intemwidth;
        var off_set = 10;
        if( $(window).width() == 414 ){
            intemwidth = 192;
        }else if( $(window).width() == 375 ){
            off_set = 11;
            intemwidth = 172;
        }else if( $(window).width() == 320 ){
            intemwidth = 145;
        }
        // Instantiate wookmark after all images have been loaded
        var wookmark,
            container = '#container',
            $container = $(container),
            $window = $(window),
            $document = $(document);

        imagesLoaded(container, function () {
            wookmark = new Wookmark(container, {
                offset: off_set, // Optional, the distance between grid items
                itemWidth: intemwidth // Optional, the width of a grid item
            });
        });

        /**
         * When scrolled all the way to the bottom, add more tiles
         */
        var curruntPage = 1;
        var flag = 1;
        function onScroll() {
            // Check if we're within 100 pixels of the bottom edge of the broser window.
            var winHeight = window.innerHeight ? window.innerHeight : $window.height(), // iphone fix
                closeToBottom = ($window.scrollTop() + winHeight > $document.height() - 500);

            if (closeToBottom && flag) {
                // Get the first then items from the grid, clone them, and add them to the bottom of the grid
                flag = 0;
                $.ajax({
                    url: '/celebrities/list.json',
                    type: 'GET',
                    data: {'page' : curruntPage + 1},
                    success: function (data) {
                        sqlJson = data.data;
                        if (JSON.stringify(sqlJson) == '[]') {
                            $("#imloading").text('刷到底了~');
                        } else {
                            curruntPage = data.current_page;
                            var html = "";
                            for (var i in sqlJson) {
                                var myurl = "#";
                                if (sqlJson[i].profile_img != null) {
                                    myurl = sqlJson[i].profile_img;
                                } else {
                                    myurl = (sqlJson[i].pictures)[0].url;
                                }
                                html += "<li><a href='/celebrity/" + sqlJson[i].id + "'>";
                                html += "<img src='" + myurl + "' style='width: 100%;'>";
                                html += "</a>";
                                html += "<div style='padding: 8px;'>";
                                html += "<div class='name' >" + sqlJson[i].nickname;
                                html += "</div><div class='fensi'>粉丝&nbsp;" + sqlJson[i].total_fans_num;
                                html += "</div></div></li>";
                            }
                            //var $items = $('li', $container);
                            //var $firstTen = $items.slice(0, 10).clone().css('opacity', 0);
                            $(html).find('img').each(function (index) {
                                loadImage($(this).attr('src'));
                            });
                            var $firstTen = $(html).css({opacity: 0});
                            $firstTen.imagesLoaded(function () {
                                $container.append($firstTen);
                                wookmark.initItems();
                                wookmark.layout(true, function () {
                                    // Fade in items after layout
                                    setTimeout(function () {
                                        $firstTen.css('opacity', 1);
                                    }, 1000);
                                });
                            });
                            flag = 1;
                        }
                    }
                });
            }
        };

        function loadImage(url) {
            var img = new Image();
            //创建一个Image对象，实现图片的预下载
            img.src = url;
            if (img.complete) {
                return img.src;
            }
            img.onload = function () {
                return img.src;
            };
        };

        // Capture scroll event.
        $window.bind('scroll.wookmark', onScroll);

        $("#sort-menu td").click(function($e) {
            window.location = updateUrlParameter(window.location.href, 'sort', this.dataset.key);
        });
    })(jQuery);

    function updateUrlParameter(uri, key, value) {
        var i = uri.indexOf('#');
        var hash = i === -1 ? ''  : uri.substr(i);
        uri = i === -1 ? uri : uri.substr(0, i);

        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.match(re)) {
            uri = uri.replace(re, '$1' + key + "=" + value + '$2');
        } else {
            uri = uri + separator + key + "=" + value;
        }
        return uri + hash;
    }
</script>
</body>
</html>
@endsection