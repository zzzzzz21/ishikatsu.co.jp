<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * sitemap.xml生成
 */
class Create_sitemap extends Batch_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Index
	 */
	public function index($date='')
	{
		$this->load->model('Sitemap_model');

		// sitemap.xml生成
		$this->Sitemap_model->main();
		exit;
	}
}

/* End of file index.php */
/* Location: ./application/controllers/index.php */