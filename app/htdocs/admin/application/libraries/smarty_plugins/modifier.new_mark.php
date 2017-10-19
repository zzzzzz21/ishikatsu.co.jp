<?php
/**
 * newアイコン表示
 */
function smarty_modifier_new_mark($null, $data, $col, $time, $tag_icon)
{
	$now = mktime();
	if ( ! $data || ! isset($data[$col]))
	{
		return '';
	}
	$target = strtotime($data[$col]);
	if ($now - $target < $time*60*60)
	{
		return $tag_icon;
	}
	return '';
}
