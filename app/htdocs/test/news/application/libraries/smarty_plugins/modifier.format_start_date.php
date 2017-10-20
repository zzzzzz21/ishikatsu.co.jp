<?php
/**
 * 開始時間フォーマット
 */
function smarty_modifier_format_start_date($time)
{
	if ( ! $time)
	{
		return '';
	}

	$time = sprintf('%06d', $time);

	$hour = substr($time, 0, 2);
	$min  = substr($time, 2, 2);
	$sec  = substr($time, 4, 2);
	return $hour . ':' . $min;
}
