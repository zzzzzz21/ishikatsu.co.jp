<?php
/**
 * エラーかどうか
 */
function smarty_modifier_is_form_error($name="")
{
	$output = '';
	if (function_exists('form_error')) 
	{
		if ($name)
		{
			$output = form_error($name, '', '');
		}
		else
		{
		
			$ci =& get_instance();
			if(isset($ci->form_validation)){
				$i = 1;
				foreach($ci->form_validation->get_field_data() as $key=>$value){
					$output = form_error($key, '', '');
					if ($output)
					{
						break;
					}
				}
			}
		}
	}

	if ($output)
	{
		return TRUE;
	}
	return FALSE;
}
