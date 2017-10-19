<?php

/**
 * 共通コントローラ
 */
class SSL_Controller extends MY_Controller {

	public function __construct()
	{
		$this->is_ssl_page = TRUE;

		parent::__construct();
		
		// SSLに飛ばす
		if ($this->config->item('protocol_https') == 'https')
		{
			if (empty($_SERVER['HTTPS']))
			{
				header("Location: https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
				exit;
			}
		}
		
	
	}
}
/* End of file SSL_Controller.php */
/* Location: ./application/core/SSL_Controller.php */
?>