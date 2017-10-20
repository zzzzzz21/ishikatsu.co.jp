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
		if (($prefix !== null) && ($suffix !== null))
		{
			$output = form_error($params['name'], $prefix, $suffix);
		}
		else
		{
			$output = str_replace(array('<p>', '</p>'), '', form_error($params['name'], '', ''));
		}
		return $output;
	}
	else 
	{
		return '';
	}
}