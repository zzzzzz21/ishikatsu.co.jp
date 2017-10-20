<?php
/**
 * ファイルサイズ取得
 */
function smarty_modifier_filesize($path)
{
	if ( ! file_exists($path))
	{
		return '';
	}

	if (is_dir($path))
	{
		return '';
	}
	
	$filesize = filesize($path);

	if ($filesize > 1024*1024*1024)
	{
		// GB
		$filesize_str = number_format(ceil($filesize/1024/1024/1024)) . 'GByte';
	}
	elseif ($filesize > 1024*1024)
	{
		// MB
		$filesize_str = number_format(ceil($filesize/1024/1024)) . 'MByte';
	}
	elseif ($filesize > 1024)
	{
		// KB
		$filesize_str = number_format(ceil($filesize/1024)) . 'KByte';
	}
	else
	{
		// B
		$filesize_str = number_format($filesize) . 'Byte';
	}

	return $filesize_str;
}
