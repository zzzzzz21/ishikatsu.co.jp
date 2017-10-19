<?php
//アウトプットフィルタ
function smarty_function_validation_errors($params, &$smarty)
{
	$prefix = null;
	$suffix = null;
	if(isset($params['prefix'])){
		$prefix = $params['prefix'];
	}
	if(isset($params['suffix'])){
		$suffix = $params['suffix'];
	}
	
	$ci =& get_instance();

	$output = '';
	if(isset($ci->form_validation)){
		$i = 1;
		foreach($ci->form_validation->get_field_data() as $key=>$value){
			if ($prefix && $suffix) {
				$output .= form_error($key, $prefix, $suffix);
			} else {
				$tmp_err = str_replace(array('<p>', '</p>'), '', form_error($key, '', ''));
				if ($tmp_err) {
					if ($i != 1) {
						$output .= '<br />'."\n" . $tmp_err;
					} else {
						$output .= $tmp_err;
					}
					$i++;
				}
			}
		}
	}
	return $output;
}