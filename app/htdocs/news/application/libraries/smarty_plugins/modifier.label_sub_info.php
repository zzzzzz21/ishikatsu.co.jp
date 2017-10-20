<?php
/**
 * ラベルのサブ情報
 */
function smarty_modifier_label_sub_info($setting)
{
	if ( ! isset($setting['rules']))
	{
		return '';
	}

	$rules = $setting['rules'];
	
	$rules_arr = explode('|', $rules);

	$hojo = '';
	if (isset($setting['is_required_disp_br']) && $setting['is_required_disp_br'])
	{
		$hojo = '<br>';
	}
	$hojo .= '<span class="label-sub">';
	$hojo_aft = '</span>';
	
	if (isset($setting['required_disp_custom']) && $setting['required_disp_custom'])
	{
		return $hojo.$setting['required_disp_custom'].$hojo_aft;
	}
	else if (array_search('required', $rules_arr) !== FALSE)
	{
		return $hojo.'※'.$hojo_aft;
	}
	else if (array_search('callback_check_match_by_report', $rules_arr) !== FALSE)
	{
		return $hojo.'※'.$hojo_aft;
	}
	else if (isset($setting['is_required_disp']) && $setting['is_required_disp'])
	{
		return $hojo.'※'.$hojo_aft;
	}

	return '';
}
