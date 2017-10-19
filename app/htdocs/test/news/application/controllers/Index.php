<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ニュース
 */
class Index extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * ニュース一覧
	 */
	public function index()
	{
		// 1ページあたりの表示件数
		$limit = 10;

		$this->load->model('Information_model');

		$type = $this->get('type');
		$y = $this->get('y');
		$p = $this->get('p');
		$p = $p ? $p : 1;
		$offset = $limit * ($p - 1);

		// 終了していないニュース
		$open_news_list = $this->Information_model->get_information_list(FALSE, $type, $y, false, $limit, $offset);

		// 終了しているニュース
		//$close_news_list = $this->Information_model->get_information_list($end=true, $type, $y);
		$close_news_list = array();

		$this->assign('open_news_list', $open_news_list);
		$this->assign('close_news_list', $close_news_list);

		// 登録されている年一覧を取得
		$news_year_list = $this->Information_model->get_year_list($type);
		$this->assign('news_year_list', $news_year_list);

		// ページャ
		$base_url = BASE_PATH . 'news/';
		$page_info = $this->get_pager_source($base_url, $this->Information_model->row_num, $limit, $p);
		$this->assign('page_info', $page_info);

		$this->view('news/index.tpl');
	}
}
