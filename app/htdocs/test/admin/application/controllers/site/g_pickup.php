<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ピックアップ管理
 */
class G_pickup extends MY_Controller {

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
		$this->load->model('Global_pickup_model');
		$list = $this->Global_pickup_model->get_list();
		$this->assign('list', $list);
		// ワンタイム
		$this->set_unique_hash();
		$this->view('site/g_pickup.tpl');
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
		$this->load->model('Global_pickup_model');
		$this->Global_pickup_model->load->database();
		$this->Global_pickup_model->db->trans_begin();

		$copylight = $this->get('copylight');
		$description = $this->get('description');
		$link_url = $this->get('link_url');
		$target_blank = $this->get('target_blank');
		$current_filename_pc = $this->get('current_filename_pc');

		// 順番に登録
		foreach ($copylight as $key => $val)
		{
			$form = array();
			$form['seq'] = $key + 1;
			$form['copylight'] = $copylight[$key];
			$form['description'] = $description[$key];
			$form['link_url'] = $link_url[$key];
			$form['target_blank'] = isset($target_blank[$key]) ? 1 : 0;
			$form['filename_pc'] = $current_filename_pc[$key];
			$ret = $this->Global_pickup_model->regist($form);
			// 戻り値から、失敗時には、ロールバックを行う
			$this->complete_fail($ret, '');

			// ファイルアップロード
			$filename_pc = $this->upload_image_array('filename_pc', 'global_pickup_image_path', date('YmdHis') . '_' . ($key+1), $key);
			if ($filename_pc === FALSE)
			{
				$this->complete_fail($ret, '');
			}
			if (($filename_pc !== TRUE) && $filename_pc)
			{
				$ret = $this->Global_pickup_model->update_filename_pc($key+1, $filename_pc);
				$this->complete_fail($ret, '');
			}
		}

		$this->Global_pickup_model->db->trans_commit();

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
				'field' => 'description[]', // POSTフィールド名
				'label' => '説明文', // POSTフィールドラベル
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
		$image_path = $this->config->item('global_pickup_image_path');
		$image_sync_info = $this->config->item('global_pickup_image_sync_info');
		$command = $this->config->item('command');

		$dests = $image_sync_info['dest'];
		if ( ! is_array($dests))
		{
			$dests = array($dests);
		}
		foreach ($dests as $key => $dest)
		{
			exec($command['sync'] . $image_path['path'] . '/ ' . $dest, $out, $ret);
		}
	}
}
