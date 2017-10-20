<?php

/**
 * 共通Exceptions
 */
class MY_Exceptions extends CI_Exceptions {

	public function __construct()
	{
		parent::__construct();
	}

	function show_error($heading, $message, $template = 'error_general', $status_code = 500)
	{
		set_status_header($status_code);

		// 追加部分
		require_once BASEPATH.'core/Controller.php';
		require_once BASEPATH.'core/Input.php';
		require_once APPPATH . '/core/MY_Controller.php';

		$CI = NEW MY_Controller(TRUE);

		// 追加ここまで
		$message = ''.implode('', ( ! is_array($message)) ? array($message) : $message).'';

		$tpl = 'error/error.tpl';
		$CI->assign('message', $heading);
		$html = $CI->fetch($tpl);

		return $html;
    }
}

?>
