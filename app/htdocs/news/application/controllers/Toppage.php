<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * トップページ
 */
class Toppage extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * トップページ
	 */
	public function index()
	{
		// 1ページあたりの表示件数
		$limit = 3;

		$this->load->model('Information_model');

		// ニュース
		$open_news_list = $this->Information_model->get_information_list(FALSE, null, null, false, 3, 0);
		$this->assign('open_news_list', $open_news_list);

		$this->assign('BASE_PATH', BASE_PATH);
		$this->view('toppage.tpl');
	}
}
