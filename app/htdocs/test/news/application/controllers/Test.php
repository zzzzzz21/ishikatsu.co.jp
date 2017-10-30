<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * トップページ用3件表示
 */
class Test extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * トップページ用3件表示
	 */
	public function index()
	{
		// 1ページあたりの表示件数
		$limit = 3;

		$this->load->model('Information_model');

		// ニュース
		$open_news_list = $this->Information_model->get_information_list(FALSE, null, null, false, 3, 0);
		$this->assign('open_news_list', $open_news_list);

		$this->view('news/toplist.tpl');
	}
}
