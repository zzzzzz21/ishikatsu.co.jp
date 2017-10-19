<?php

class Batch_Controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
	}

	/**
	 * ログ出力
	 */
	public function log($filename, $text)
	{
		$log_path = APPPATH . 'logs/' . $filename . '.log';
		$fp = fopen($log_path, 'a');
		fwrite($fp, sprintf("[%s] %s\n", date('Y-m-d H:i:s'), $text));
		fclose($fp);
		unset($fp);
	}

}

/* End of file Batch_Controller.php */
/* Location: ./application/core/Batch_Controller.php */
?>