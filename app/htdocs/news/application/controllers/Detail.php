<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * お知らせ詳細
 */
class Detail extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * お知らせ詳細
	 */
	public function index($news_id, $arg2=null)
	{
		if ( ! $news_id || $arg2)
		{
			$this->show_404();
			return;
		}

		// 詳細取得
		$this->load->model('Information_model');
		$detail = $this->Information_model->get_info_detail($news_id);

		if ( ! $detail)
		{
			$this->show_404();
			return;
		}

		// リンクタイプにアクセスした場合もエラー
		if ($detail['link_url'])
		{
			$this->show_404();
			return;
		}

		$this->assign('detail', $detail);
		$this->assign('detail_ne', $detail, false);

		// 次へ前へ情報を取得
		$next = $this->Information_model->get_next_prev($detail['info_id'], $type='next');
		$prev = $this->Information_model->get_next_prev($detail['info_id'], $type='prev');
		$this->assign('next', $next);
		$this->assign('prev', $prev);

		// 登録されている年一覧を取得
		$news_year_list = $this->Information_model->get_year_list(null);
		$this->assign('news_year_list', $news_year_list);

		$this->view('news/detail.tpl');
	}

	/**
	 * お知らせ詳細(古いURLから)
	 */
	public function oldid($old_id='', $prm2)
	{
		if ( ! $old_id || $prm2)
		{
			$this->show_404();
			return;
		}

		// 詳細取得
		$this->load->model('Information_model');
		$detail = $this->Information_model->get_info_detail_oldid($old_id);

		if ( ! $detail)
		{
			$this->show_404();
			return;
		}

		header('Location:/news/' . $detail['info_id'] . '/', false, 301);
		exit;
	}

}

/* End of file detail.php */
/* Location: ./application/controllers/program/detail.php */
