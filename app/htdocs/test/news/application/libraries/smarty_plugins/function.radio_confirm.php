<?php
//ラジオボタン確認用
function smarty_function_radio_confirm($params, &$smarty)
{

	$list = array();
	if(isset($params['list'])){
		$list = $params['list'];
	}
	$values = array();
	if(isset($params['value'])){
		if(is_array($params['value'])){
			$values = $params['value'];
		}else{
			$values[] = $params['value'];
		}
	}
	$separator  = '';
	if(isset($params['separator'])){
		$separator  = $params['separator'];
	}

	$tmp = array();
	foreach($values as $value){

		if(isset($list[$value])){
			$tmp[] = $list[$value];
		}
	}

	$result = implode($separator, $tmp);

	return $result;
}