{{if isset($form.type) && $form.type == 'hide'}}
<div class="result">無効にしました</div>
{{elseif isset($form.type) && $form.type == 'hidefail'}}
<div class="result false"><span class="bold">無効処理が失敗しました</span></div>
{{elseif isset($form.type) && $form.type == 'show'}}
<div class="result">有効にしました</div>
{{elseif isset($form.type) && $form.type == 'showfail'}}
<div class="result false"><span class="bold">有効処理が失敗しました</span></div>
{{elseif isset($form.type) && $form.type == 'hide2'}}
<div class="result">非表示にしました</div>
{{elseif isset($form.type) && $form.type == 'hide2fail'}}
<div class="result false"><span class="bold">非表示に失敗しました</span></div>
{{elseif isset($form.type) && $form.type == 'show2'}}
<div class="result">表示しました</div>
{{elseif isset($form.type) && $form.type == 'show2fail'}}
<div class="result false"><span class="bold">表示処理が失敗しました</span></div>
{{elseif isset($form.type) && $form.type == 'delete'}}
<div class="result">削除しました</div>
{{elseif isset($form.type) && $form.type == 'deletefail'}}
<div class="result false"><span class="bold">削除処理が失敗しました</span></div>
{{elseif isset($form.type) && $form.type == 'status'}}
<div class="result">ステータスを更新しました</div>
{{elseif isset($form.type) && $form.type == 'statusfail'}}
<div class="result false"><span class="bold">ステータス更新に失敗しました</span></div>
{{elseif isset($form.type) && $form.type == 'hideshow'}}
<div class="result">有効/無効一括更新を実行しました</div>
{{elseif isset($form.type) && $form.type == 'hideshowfail'}}
<div class="result false"><span class="bold">有効/無効一括更新に失敗しました</span></div>
{{elseif isset($form.type) && $form.type == 'complete'}}
<div class="result"><span class="bold">更新しました</span> {{if $form.complete_id|default:''}} ID:{{$form.complete_id}}{{/if}}</div>
{{elseif isset($form.type) && $form.type == 'completefail'}}
<div class="result false"><span class="bold">更新に失敗しました</span>{{if $form.complete_id|default:''}} ID:{{$form.complete_id}}{{/if}}</div>
{{/if}}