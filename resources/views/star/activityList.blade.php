<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield("title")</title>
    <link rel="stylesheet" href="{{URL::asset('css/sm.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('/css/weui.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/sm-extend.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/merchant/myStyle.css')}}">
    <script type='text/javascript' src="{{URL::asset('js/zepto.min.js')}}" charset='utf-8'></script>
	<script type='text/javascript' src="{{URL::asset('js/sm.min.js')}}" charset='utf-8'></script>
	<script type='text/javascript' src="{{URL::asset('js/sm-extend.min.js')}}" charset='utf-8'></script>
</head>
<body>
<header class="bar bar-nav">
  	<h1 class='title'>红了吗网红端</h1>
  	<span class="icon icon-search pull-right"></span>
</header>
<div class="content">
 
 
    <div class="tabs">
      
      <div id="tab3" >
      <?php
  //     	echo "<pre>";
		// var_dump(count($list));die;
      ?>
      @foreach ($list as $vo)
      <?php

      	$merchant = App\Models\Merchant::where('merchant_id',$vo->merchant_id)->first();
      ?>
        <div class="content-block content-block-my content-no-margin">
         	<div class="content-block content-block-my">
		   		<div class="list-block content-no-margin" style="margin-top: -1rem;">
				  	<ul>
					   	<li>
					        <div class="item-content">
					            <div class="item-inner">
					                <div class="item-title">{{$merchant['name']}} ></div>
					            </div>
					        </div>
					    </li>
				   	</ul>
		   		</div>
		  		<div class="list-block media-list content-no-margin">
				    <ul>
				      <li>
				        <a href="/star/activityDetail/{{$vo['activity_id']}}" class="blackfont item-content">
				          <div class="item-media"><img src="{{$vo['picture']}}" style='width: 4rem;'></div>
				          <div class="item-inner">
				            <div class="item-title-row">
				              <div class="item-subtitle">{{$vo['title']}}</div>
				              <div class="item-after">¥{{$vo['total_price']}}</div>
				            </div>
				            <div class="item-subtitle">&nbsp;</div>
				           <div class="item-subtitle">
				            </div>
				          </div>
				        </a>
				      </li>
				    </ul>
			  	</div>
			</div>
      	</div>
      	@endforeach
      </div>
    </div>
  </div>
   @include("star.star_footer")
</div>

</body>
</html>