<?php
//アウトプットフィルタ
function smarty_function_form_error($params, &$smarty)
{

	$prefix = null;
	$suffix = null;
	if(isset($params['prefix'])){
		$prefix = $params['prefix'];
	}
	if(isset($params['suffix'])){
		$suffix = $params['suffix'];
	}
	
	if (function_exists('form_error')) 
	{
		$output = form_error($params['name'], $prefix, $suffix);
		return $output;
	}
	else 
	{
		return '';
	}
}