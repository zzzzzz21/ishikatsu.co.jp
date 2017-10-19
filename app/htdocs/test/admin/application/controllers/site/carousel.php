<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * トップカルーセル管理
 */
class Carousel extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		// 権限チェック
		$login = $this->session->userdata('login');
		if ( ! isset($login['is_site']) || $login['is_site'] == 0)
		{
			header('Location: /');
			exit;
		}
	}

	/**
	 * Index
	 */
	public function index()
	{
		// データ取得
		$this->load->model('Carousel_model');
		$list = $this->Carousel_model->get_list();
		$this->assign('list', $list);
		// ワンタイム
		$this->set_unique_hash();
		$this->view('site/carousel.tpl');
	}

	/**
	 * 完了処理
	 */
	public function complete()
	{
		// エラーチェック

		// エラーチェック実行
		if ( ! $this->validate(TRUE))
		{
			$url = str_replace('complete/', '', $_SERVER["REQUEST_URI"]) .'?type=completefail';
			$this->util->header('Location: '. $url);
			exit;
		}

		// hashチェック
		$hash = $this->get('hash');
		if ( ! $this->check_unique_hash($hash))
		{
			$url = str_replace('complete/', '', $_SERVER["REQUEST_URI"]) .'?type=completefail';
			$this->util->header('Location: '. $url);
			exit;
		}
		$this->load->model('Carousel_model');
		$this->Carousel_model->load->database();
		$this->Carousel_model->db->trans_begin();

		$title = $this->get('title');
		$description = $this->get('description');
		$description_sp = $this->get('description_sp');
		$link_url = $this->get('link_url');
		$target_blank = $this->get('target_blank');
		$current_filename_pc = $this->get('current_filename_pc');
		$current_filename_sp = $this->get('current_filename_sp');

		// 削除分
		$this->Carousel_model->delete(count($title));

		// 順番に登録
		foreach ($title as $key => $val)
		{
			$form = array();
			$form['seq'] = $key + 1;
			$form['title'] = $title[$key];
			$form['description'] = $description[$key];
			$form['description_sp'] = $description_sp[$key];
			$form['link_url'] = $link_url[$key];
			$form['title'] = $title[$key];
			$form['filename_pc'] = $current_filename_pc[$key];
			$form['filename_sp'] = $current_filename_sp[$key];
			$form['target_blank'] = $target_blank[$key];
			$ret = $this->Carousel_model->regist($form);
			// 戻り値から、失敗時には、ロールバックを行う
			$this->complete_fail($ret, '');

			// ファイルアップロード
			$filename_pc = $this->upload_image_array('filename_pc', 'carousel_image_path', date('YmdHis') . '_' . ($key+1) . '_pc', $key);
			if ($filename_pc === FALSE)
			{
				$this->complete_fail($ret, '');
			}
			if (($filename_pc !== TRUE) && $filename_pc)
			{
				$ret = $this->Carousel_model->update_filename_pc($key+1, $filename_pc);
				$this->complete_fail($ret, '');
			}
			$filename_sp = $this->upload_image_array('filename_sp', 'carousel_image_path', date('YmdHis') . '_' . ($key+1) . '_sp', $key);
			if ($filename_sp === FALSE)
			{
				$this->complete_fail($ret, '');
			}
			if (($filename_sp !== TRUE) && $filename_sp)
			{
				$ret = $this->Carousel_model->update_filename_sp($key+1, $filename_sp);
				$this->complete_fail($ret, '');
			}
		}

		$this->Carousel_model->db->trans_commit();

		// 同期処理
		$this->sync_image();

		// 完了後、一覧へ飛ばす
		$url = str_replace('complete/', '', $_SERVER["REQUEST_URI"]) .'?type=complete';
		$this->util->header('Location: '. $url);
		exit;
	}

	/**
	 * エラーチェック
	 */
	public function validate($is_check_only=FALSE)
	{
		$rules =  array(
			array (
				'field' => 'title[]', // POSTフィールド名
				'label' => 'タイトル', // POSTフィールドラベル
				'rules' => 'required', // ルール
			),
			array (
				'field' => 'description[]', // POSTフィールド名
				'label' => 'PC用説明文', // POSTフィールドラベル
				'rules' => 'required', // ルール
			),
		);

		$this->load->library('form_validation', $rules);

		// エラーチェック実行
		$ret = $this->form_validation->run();

		if ($is_check_only)
		{
			return $ret;
		}
		else
		{
			$this->view('common/validate.tpl');
		}
	}

	/**
	 * 画像同期
	 */
	public function sync_image()
	{
		$carousel_image_path = $this->config->item('carousel_image_path');
		$carousel_image_sync_info = $this->config->item('carousel_image_sync_info');
		$command = $this->config->item('command');

		$dests = $carousel_image_sync_info['dest'];
		if ( ! is_array($dests))
		{
			$dests = array($dests);
		}
		foreach ($dests as $key => $dest)
		{
			exec($command['sync'] . $carousel_image_path['path'] . '/ ' . $dest, $out, $ret);
		}
	}
}
