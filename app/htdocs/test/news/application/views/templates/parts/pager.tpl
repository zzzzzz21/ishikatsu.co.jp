{{if $page_info.next || $page_info.prev}}
<ul class="pagerButton">
	<li class="pagerButton__item pagerButton__item--prev">{{if $page_info.prev}}<a href="{{$page_info.base_url}}{{if $is_other_param|default:''}}&amp;{{else}}?{{/if}}p=1" class="pagerButton__link pagerButton__link--prev">PREV</a>{{/if}}</li>
	<li class="pagerButton__item pagerButton__item--next">{{if $page_info.next}}<a href="{{$page_info.base_url}}{{if $is_other_param|default:''}}&amp;{{else}}?{{/if}}p={{$page_info.next}}" class="pagerButton__link pagerButton__link--next">NEXT</a>{{/if}}</li>
</ul>
{{/if}}
{{if $page_info.pages|default:""}}
<ul class="pagerNumber">
{{foreach from=$page_info.pages key=key item=item}}
{{if $item == $page_info.current}}
	<li class="pagerNumber__item"><a href="{{$page_info.base_url}}{{if $is_other_param|default:''}}&amp;{{else}}?{{/if}}p={{$item}}" class="pagerNumber__link pagerNumber__link--current">{{$item}}</a></li>
{{else}}
	<li class="pagerNumber__item"><a href="{{$page_info.base_url}}{{if $is_other_param|default:''}}&amp;{{else}}?{{/if}}p={{$item}}" class="pagerNumber__link">{{$item}}</a></li>
{{/if}}
{{/foreach}}
</ul>
{{/if}}
