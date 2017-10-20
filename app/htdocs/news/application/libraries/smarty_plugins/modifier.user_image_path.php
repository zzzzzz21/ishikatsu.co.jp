<?php
// 画像パス設定
function smarty_modifier_user_image_path($file)
{
	if ($file == '')
	{
		// 空白
		return '/img/user-icon.jpg';
	}

	if (strpos($file, 'http') === 0)
	{
		// http(s)から始まる
		return $file;
	}

	// ローカルファイル
	return '/images/user/' . $file;
}
