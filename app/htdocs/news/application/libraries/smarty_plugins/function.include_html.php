<?php
/**
 * htmlインクルード
 */
function smarty_function_include_html($params, &$smarty)
{
	$result = '';
	$file = $params['file'];
	$CI =& get_instance();
	$inc_dir = $CI->config->item('path');
	$file_path = $inc_dir . BASE_PATH . 'share/include/' . $file;

	return file_get_contents($file_path);
}
