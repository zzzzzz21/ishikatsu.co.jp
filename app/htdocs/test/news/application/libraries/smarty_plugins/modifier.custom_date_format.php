<?php
/**
 * 日付フォーマット
 */
function smarty_modifier_custom_date_format($date, $format='Y/m/d')
{
	$tmp = strtotime($date);
	return date($format, $tmp);
}
