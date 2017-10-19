<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info extends MY_Controller {

	var $limit = 20;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Index
	 */
	public function index()
	{
		$this->load->model('Information_model');
		$pagenum = $this->get('p');
		if ( ! $pagenum)
		{
			$pagenum = 1;
		}
		$offset = ($pagenum - 1) * $this->limit;

		// 検索条件
		$search = array();
		$search['info_id'] = $this->get('search_info_id');
		$search['title'] = $this->get('search_title');
		$search['contents_type'] = $this->get('search_contents_type');
		$disp_date_ymd = $this->get('search_disp_date_ymd');
		$disp_date_hour = $this->get('search_disp_date_hour');
		$disp_date_min = $this->get('search_disp_date_min');
		$end_date_ymd = $this->get('search_end_date_ymd');
		$end_date_hour = $this->get('search_end_date_hour');
		$end_date_min = $this->get('search_end_date_min');

		// 時分に関しては、なければ保管
		if ( ! strlen($disp_date_hour))
		{
			$disp_date_hour = 0;
		}
		if ( ! strlen($disp_date_min))
		{
			$disp_date_min = 0;
		}
		if ( ! strlen($end_date_hour))
		{
			$end_date_hour = 23;
		}
		if ( ! strlen($end_date_min))
		{
			$end_date_min = 59;
		}

		if (strlen($disp_date_ymd) && strlen($disp_date_hour) && strlen($disp_date_min))
		{
			$search['disp_date'] = $this->concat_ymd_h_m($disp_date_ymd, $disp_date_hour, $disp_date_min);
		}
		if (strlen($end_date_ymd) && strlen($end_date_hour) && strlen($end_date_min))
		{
			$search['end_date'] = $this->concat_ymd_h_m($end_date_ymd, $end_date_hour, $end_date_min, 1);
		}

		$search['status'] = $this->get('search_status');

		// ソート
		list($sort_key, $sort_type) = $this->get_sort_cond();

		$list = $this->Information_model->get_list($search, $sort_key, $sort_type, $this->limit, $offset);
		$this->assign('list', $list);

		// ページャ生成
		$allnum = $this->Information_model->allnum;
		$page_info = $this->get_pager_source('./', $allnum, $this->limit, $pagenum);
		$this->assign('page_info', $page_info);

		// 時、分セレクト
		$select_hour = $this->get_select_hour();
		$select_min = $this->get_select_min();
		$this->assign('select_hour', $select_hour);
		$this->assign('select_min', $select_min);

		$this->view('info/index.tpl');
	}

	/**
	 * detail
	 */
	public function detail()
	{
		$this->load->model('Information_model');
		$info_id = $this->get('info_id');
		$choices = array();

		// ワンタイム
		$this->set_unique_hash();

		if ($info_id)
		{
			$information = $this->Information_model->get_info($info_id);
			$this->assign_form('info_type', $information['info_type']);
			$this->assign_form('prog_category_id', $information['prog_category_id']);
			$this->assign_form('title', $information['title']);
			$this->assign_form('body', $information['body']);
			$this->assign_form('link_url', $information['link_url']);
			$this->assign_form('is_target', $information['is_target']);
			$this->assign_form('contents_type', $information['contents_type']);

			$this->assign_form('disp_date_ymd', substr($information['disp_date'], 0, 10));
			$this->assign_form('disp_date_hour', substr($information['disp_date'], 11, 2));
			$this->assign_form('disp_date_min', substr($information['disp_date'], 14, 2));
			$this->assign_form('end_date_ymd', substr($information['end_date'], 0, 10));
			$this->assign_form('end_date_hour', substr($information['end_date'], 11, 2));
			$this->assign_form('end_date_min', substr($information['end_date'], 14, 2));
			$this->assign_form('is_hide', $information['is_hide']);
			$this->assign_form('is_not_limit_end', $information['is_not_limit_end']);
			$this->assign_form('content_ext', $information['content_ext']);
			$this->assign_form('content_filesize', $information['content_filesize']);
		}
		else
		{
			// デフォルト値
		}

		// 時、分セレクト
		$select_hour = $this->get_select_hour();
		$select_min = $this->get_select_min();
		$this->assign('select_hour', $select_hour);
		$this->assign('select_min', $select_min);

		$this->view('info/detail.tpl');
	}

	/**
	 * エラーチェック
	 */
	public function validate($is_check_only=FALSE)
	{
		$rules =  array(
			array (
				'field' => 'title', // POSTフィールド名
				'label' => 'タイトル', // POSTフィールドラベル
				'rules' => 'required', // ルール
			)
		);

		$rules[] =  array (
				'field' => 'disp_date_ymd', // POSTフィールド名
				'label' => '掲載開始', // POSTフィールドラベル
				'rules' => 'callback_check_date', // ルール
		);

		// 記事の内容によってチェックを切り替える
		$contents_type = $this->get('contents_type');
		if ($contents_type == 1)
		{
			$rules[] =  array (
				'field' => 'body', // POSTフィールド名
				'label' => '内容', // POSTフィールドラベル
				'rules' => 'required', // ルール
			);
		}
		elseif ($contents_type == 2)
		{
			$rules[] =  array (
				'field' => 'link_url', // POSTフィールド名
				'label' => 'リンク先URL', // POSTフィールドラベル
				'rules' => 'required|callback_check_url', // ルール
			);
			$rules[] =  array (
				'field' => 'is_target', // POSTフィールド名
				'label' => '新規ウィンドウで開く', // POSTフィールドラベル
				'rules' => 'required', // ルール
			);
		}

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
	 * 掲載終了チェック
	 */
	public function check_date($val)
	{
		$is_not_limit_end = $this->get('is_not_limit_end');
		$disp_date_ymd = $this->get('disp_date_ymd');
		$disp_date_hour = $this->get('disp_date_hour');
		$disp_date_min = $this->get('disp_date_min');
		$end_date_ymd = $this->get('end_date_ymd');
		$end_date_hour = $this->get('end_date_hour');
		$end_date_min = $this->get('end_date_min');

		// 必須
		if ( ! strlen($disp_date_ymd) OR  ! strlen($disp_date_hour) OR  ! strlen($disp_date_min))
		{
			$this->form_validation->set_message('check_date', '掲載開始を選択してください。');
			return FALSE;
		}
		if ( ! $is_not_limit_end && ( ! strlen($end_date_ymd) OR  ! strlen($end_date_hour) OR  ! strlen($end_date_min)))
		{
			$this->form_validation->set_message('check_date', '掲載終了を選択してください。');
			return FALSE;
		}

		// 形式
		if ( ! preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $disp_date_ymd))
		{
			$this->form_validation->set_message('check_date', '掲載開始が正しくありません。');
			return FALSE;
		}
		if ( ! $is_not_limit_end && (  ! preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $end_date_ymd)))
		{
			$this->form_validation->set_message('check_date', '掲載終了が正しくありません。');
			return FALSE;
		}

		// 日付チェック
		$start_tmp = explode('-', $disp_date_ymd);
		$end_tmp = explode('-', $end_date_ymd);
		if ( ! checkdate($start_tmp[1], $start_tmp[2], $start_tmp[0]))
		{
			$this->form_validation->set_message('check_date', '掲載開始が正しくありません。');
			return FALSE;
		}
		if ( ! $is_not_limit_end && (  ! checkdate($end_tmp[1], $end_tmp[2], $end_tmp[0])))
		{
			$this->form_validation->set_message('check_date', '掲載終了が正しくありません。');
			return FALSE;
		}

		// 整合性チェック
		if ( ! $is_not_limit_end)
		{
			$start = sprintf('%04d-%02d-%02d %02d:%02d:00', $start_tmp[0], $start_tmp[1], $start_tmp[2], $disp_date_hour, $disp_date_min);
			$end = sprintf('%04d-%02d-%02d %02d:%02d:00', $end_tmp[0], $end_tmp[1], $end_tmp[2], $end_date_hour, $end_date_min);
			if ($end <= $start)
			{
				$this->form_validation->set_message('check_date', '掲載終了が掲載開始以前になっています。');
				return FALSE;
			}
		}

		return TRUE;
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

		$this->load->model('Information_model');
		$this->Information_model->load->database();
		$this->Information_model->db->trans_begin();

		$info_id = $this->get('info_id');

		$contents_type = $this->get('contents_type');

		$forms = array();
		$forms['info_type'] = $this->get('info_type');
		$forms['prog_category_id'] = $this->get('prog_category_id');
		$forms['title'] = $this->get('title');

		$forms['contents_type'] = $this->get('contents_type');

		if ($contents_type == 1)
		{
			// 記事
			$forms['body'] = $this->get('body');
			$forms['null_link_url'] = 1;
			$forms['null_is_target'] = 1;
		}
		elseif ($contents_type == 2)
		{
			// リンク
			$forms['null_body'] = 1;
			$forms['link_url'] = $this->get('link_url');
			$forms['is_target'] = $this->get('is_target');
		} else {
			// ファイル
			$forms['null_body'] = 1;
			$forms['null_link_url'] = 1;
			$forms['null_is_target'] = 1;
		}
		$disp_date_ymd = $this->get('disp_date_ymd');
		$disp_date_hour = $this->get('disp_date_hour');
		$disp_date_min = $this->get('disp_date_min');
		$end_date_ymd = $this->get('end_date_ymd');
		$end_date_hour = $this->get('end_date_hour');
		$end_date_min = $this->get('end_date_min');
		$forms['disp_date'] = $this->concat_ymd_h_m($disp_date_ymd, $disp_date_hour, $disp_date_min);
		$forms['end_date'] = $this->concat_ymd_h_m($end_date_ymd, $end_date_hour, $end_date_min, 1);

		if ($this->get('is_not_limit_end'))
		{
			$forms['end_date'] = $this->config->item('datetime_max');
		}

		// 更新
		if ($info_id)
		{
			// info登録
			$ret = $this->Information_model->update($info_id, $forms);

			// 戻り値から、失敗時には、ロールバックを行う
			$this->complete_fail($ret, '');
		}
		// 新規
		else
		{
			// info登録
			$info_id = $this->Information_model->insert($forms);

			// 戻り値から、失敗時には、ロールバックを行う
			$this->complete_fail($info_id, '');
		}

		// ファイル
		if ($contents_type == 3 && $_FILES['file'])
		{
			// ファイル情報取得
			$file_size = strlen(file_get_contents($_FILES['file']['tmp_name']));
			$tmp = explode('.', $_FILES['file']['name']);
			$file_ext = $tmp[count($tmp)-1];
			// ファイル移動
			rename($_FILES['file']['tmp_name'], $this->config->item('path') . dirname(BASE_PATH) . '/news/files/' . $info_id . '.' . $file_ext);
			chmod($this->config->item('path') . dirname(BASE_PATH) . '/news/files/' . $info_id . '.' . $file_ext, 0644);
			// データ更新
			$update = array(
				'content_ext' => $file_ext,
				'content_filesize' => $file_size,
			);
			$ret = $this->Information_model->update($info_id, $update);

			// 戻り値から、失敗時には、ロールバックを行う
			$this->complete_fail($ret, '');
		}

		// ck img同期
		$this->sync_ck_image();

		$this->Information_model->db->trans_commit();

		// 完了後、一覧へ飛ばす
		$url = str_replace('complete/', '', $_SERVER["REQUEST_URI"]) .'?type=complete';
		$this->util->header('Location: '. $url);
		exit;
	}

	/**
	 * upload
	 */
	public function upload()
	{
		$conf_name = 'info';
		parent::upload($conf_name);
	}

	/**
	 * image同期
	 */
	public function sync_ck_image()
	{
		$conf_name = 'info';
		$conf_name2 = 'info';
		parent::sync_ck_image($conf_name, $conf_name2);
	}

	/**
	 * 削除処理
	 */
	public function delete()
	{
		$this->load->model('Information_model');

		// hashチェック
		$hash = $this->get('hash');
		if ( ! $this->check_unique_hash($hash))
		{
			$url = str_replace('delete/', '', $_SERVER["REQUEST_URI"]) .'?type=deletefail';
			$this->util->header('Location: '. $url);
			exit;
		}

		$info_id = $this->get('info_id');

		$info = $this->Information_model->get_info($info_id);
		if ( ! $info)
		{
			$url = str_replace('delete/', '', $_SERVER["REQUEST_URI"]) .'?type=deletefail';
			$this->util->header('Location: '. $url);
			exit;
		}

		$this->Information_model->load->database();
		$this->Information_model->db->trans_begin();


		// 削除
		$ret = $this->Information_model->delete($info_id);
		if ( ! $ret)
		{
			$this->Information_model->db->trans_rollback();
			$url = str_replace('delete/', '', $_SERVER["REQUEST_URI"]) .'?type=deletefail';
			$this->util->header('Location: '. $url);
			exit;
		}

		$this->Information_model->db->trans_commit();

		// 完了後、一覧へ飛ばす
		$url = str_replace('delete/', '', $_SERVER["REQUEST_URI"]) .'?type=delete';
		$this->util->header('Location: '. $url);
		exit;
	}

}

/* End of file info.php */
/* Location: ./application/controllers/info.php */
