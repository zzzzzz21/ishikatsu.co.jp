<?php
/**
 * 配列に追加
 */
function smarty_modifier_add($array, $key, $value)
{
	if ( ! $array)
	{
		$array = array();
	}
	$array[$key] = $value;
	return $array;
}
