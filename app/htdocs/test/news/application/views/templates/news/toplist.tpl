{{foreach from=$open_news_list item=item}}
					<li class="newsList__item">
						<p class="newsList__Timetext"><time class="newsList__date" datetime="{{$item.disp_date|date_format:'Y-m-d'}}">{{$item.disp_date|date_format:'Y年m月d日'}}</time></p>
						<p class="newsList__Linktext">{{include file="parts/news_link.tpl" item=$item}}{{$item.title}}</a></p>
					</li>
{{/foreach}}
