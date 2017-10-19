<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Index
	 */
	public function index()
	{
		$this->view('index.tpl');
	}
}

/* End of file index.php */
/* Location: ./application/controllers/index.php */
