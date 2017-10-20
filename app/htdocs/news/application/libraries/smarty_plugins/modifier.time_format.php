<?php
/**
 * 時間をフォーマット25時以降を1,2,3....
 */
function smarty_modifier_time_format($time, $type='h')
{
	if ($type == 'h')
	{
		if ($time > 24)
		{
			return ($time - 24);
		}
		return $time;
	}
	else if ($type == 'h:m')
	{
		$tmp = explode(':', $time);
		$h = $tmp[0];
		if ($h > 24)
		{
			$h = $h - 24;
		}
		return $h . ':' . $tmp[1];
	}

	return $time;
}
