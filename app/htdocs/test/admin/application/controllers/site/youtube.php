<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * トップyoutube管理
 */
class Youtube extends MY_Controller {

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
		$this->load->model('Youtube_model');
		$list = $this->Youtube_model->get_list();
		$this->assign('list', $list);
		// ワンタイム
		$this->set_unique_hash();
		$this->view('site/youtube.tpl');
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
		$this->load->model('Youtube_model');
		$this->Youtube_model->load->database();
		$this->Youtube_model->db->trans_begin();

		$youtube_url = $this->get('youtube_url');
		$title = $this->get('title');
		$current_filename = $this->get('current_filename');

		// 順番に登録
		foreach ($youtube_url as $key => $val)
		{
			$form = array();
			$form['seq'] = $key + 1;
			$form['youtube_url'] = $youtube_url[$key];
			$form['title'] = $title[$key];
			$form['filename'] = $current_filename[$key];
			$ret = $this->Youtube_model->regist($form);
			// 戻り値から、失敗時には、ロールバックを行う
			$this->complete_fail($ret, '');

			// ファイルアップロード
			$filename = $this->upload_image_array('filename', 'youtube_image_path', date('YmdHis') . '_' . ($key+1), $key);
			if ($filename === FALSE)
			{
				$this->complete_fail($ret, '');
			}
			if (($filename !== TRUE) && $filename)
			{
				$conf = $this->config->item('youtube_image_path');
				$filename = $conf['url'] . $filename;
				$ret = $this->Youtube_model->update_filename($key+1, $filename);
				$this->complete_fail($ret, '');
			}
		}

		$this->Youtube_model->db->trans_commit();

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
				'label' => '動画タイトル', // POSTフィールドラベル
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

	/**
	 * youtubeから動画データ取得
	 */
	public function get_movie()
	{
		$vid = $this->get('vid');
		$key = $this->config->item('google_api_key');
		$url = 'https://www.googleapis.com/youtube/v3/videos?id=%s&key=%s&fields=items(id,snippet(channelTitle,title,thumbnails),statistics)&part=snippet,contentDetails,statistics';
		$url = sprintf($url, $vid, $key);
		echo file_get_contents($url);
		exit;
	}
}
