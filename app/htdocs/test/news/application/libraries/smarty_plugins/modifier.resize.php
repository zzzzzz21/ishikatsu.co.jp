<?php
/**
 * 画像リサイズパスに変更
 */
function smarty_modifier_resize($path, $x, $y)
{
	return '/resize_image/' . $x . 'x' . $y . $path;
}
