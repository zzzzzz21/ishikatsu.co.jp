{{strip}}
{{if $item.link_url}}
<a href="{{$item.link_url}}"{{if $item.is_target}} target="_blank"{{/if}} class="newsList__link newsList__link--{{if $item.is_target}}blank{{else}}detail{{/if}}">
{{elseif $item.content_filesize}}
<a href="{{$smarty.const.BASE_PATH}}news/files/{{$item.info_id}}.{{$item.content_ext}}" target="_blank" class="newsList__link newsList__link--pdf">
{{else}}
<a href="{{$smarty.const.BASE_PATH}}news/{{$item.info_id}}/"{{$attr|default:''}} class="newsList__link newsList__link--detail">
{{/if}}
{{/strip}}
