<?php
//文字の切捨て（マルチバイト対応）

function smarty_modifier_mb_truncate_over($string, $over=100, $length = 80, $etc = '...') {
	if ($length == 0) {return '';}

	if (mb_strlen($string) > $over) {
		return mb_substr($string, 0, $length).$etc;
	} else {
		return $string;
	}
}
