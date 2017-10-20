{{if $page_info && $page_info.totalPage > 1}}
			<div class="pager min-w970">
				<ul>
{{if $page_info.current > 1}}
					<li class="page-btn"><a href="#" onclick="paging_link(1);return false;" class="page-num">&lt;&lt;</a></li>
{{/if}}
{{if $page_info.prev}}
					<li class="page-btn"><a href="#" onclick="paging_link({{$page_info.prev}});return false;" class="page-num">&lt;</a></li>
{{/if}}
{{foreach from=$page_info.pages key=key item=item}}
{{if $item == $page_info.current}}
					<li class="page-btn current"><span class="page-num">{{$item}}</span></li>
{{else}}
					<li class="page-btn"><a href="#" onclick="paging_link({{$item}});return false;" class="page-num">{{$item}}</a></li>
{{/if}}
{{/foreach}}
{{if $page_info.next}}
					<li class="page-btn"><a href="#" onclick="paging_link({{$page_info.next}});return false;" class="page-num">&gt;</a></li>
{{/if}}
{{if $page_info.current < $page_info.totalPage}}
					<li class="page-btn"><a href="#" onclick="paging_link({{$page_info.totalPage}});return false;" class="page-num">&gt;&gt;</a></li>
{{/if}}
				</ul>
			</div>
{{/if}}
