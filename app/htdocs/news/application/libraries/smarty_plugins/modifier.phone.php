<?php
/**
 * phone [000-000-000]形式の場合にリンクにして返す
 */
function smarty_modifier_phone($tel)
{
	if (preg_match('/^([0-9\]+\-[0-9]+\-?[0-9]+)$/', $tel, $matches))
	{
		return '<a class="blue-link" href="tel:'.str_replace('-', '', $matches[1]).'">' . $matches[1] . '</a>';
	}
	else if (preg_match('/^([0-9]+\-[0-9]+\-?[0-9]+)([^0-9\-]+.*)$/', $tel, $matches))
	{
		return '<a class="blue-link" href="tel:'.str_replace('-', '', $matches[1]).'">' . $matches[1] . '</a>' . $matches[2];
	}
	else if (preg_match('/^(.*[^0-9\-]+)([0-9]+\-[0-9]+\-?[0-9]+)$/', $tel, $matches))
	{
		return $matches[1] . '<a class="blue-link" href="tel:'.str_replace('-', '', $matches[2]).'">' . $matches[2] . '</a>';
	}
	else if (preg_match('/^(.*[^0-9\-]+)([0-9]+\-[0-9]+\-?[0-9]+)([^0-9\-]+.*)$/', $tel, $matches))
	{
		return $matches[1] . '<a class="blue-link" href="tel:'.str_replace('-', '', $matches[2]).'">' . $matches[2] . '</a>' . $matches[3];
	}
	return $tel;
}
