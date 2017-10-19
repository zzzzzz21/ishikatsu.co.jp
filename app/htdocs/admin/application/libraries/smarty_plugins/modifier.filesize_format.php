<?php
/**
 * ファイルサイズ表示書式のフォーマット
 */
function smarty_modifier_filesize_format($string)
{
    if ($string < 1024) {
        // B
        return ($string)."B";
    }

    if ($string < 1024*1024) {
        // KB
        return (round($string / 1024 * 100) / 100)."KB";
    }

    if ($string < 1024*1024*1024) {
        // MB
        return (round($string / 1024 / 1024 * 100) / 100)."MB";
    }

    if ($string < 1024*1024*1024*1024) {
        // GB
        return (round($string / 1024 / 1024 / 1024 * 100) / 100)."GB";
    }

    return ($string);
}

?>