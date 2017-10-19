<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends MY_Controller {

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
		$this->load->model('Account_model');
		$pagenum = $this->get('p');
		if ( ! $pagenum)
		{
			$pagenum = 1;
		}
		$offset = ($pagenum - 1) * $this->limit;

		// 検索条件
		$search = array();
		$search['account_id'] = $this->get('search_account_id');
		$search['name'] = $this->get('search_name');
		$search['is_admin'] = $this->get('search_is_admin');

		// ソート
		list($sort_key, $sort_type) = $this->get_sort_cond();
		$list = $this->Account_model->get_list($search, $sort_key, $sort_type, $this->limit, $offset);
		$this->assign('list', $list);

		// ページャ生成
		$allnum = $this->Account_model->allnum;
		$page_info = $this->get_pager_source('./', $allnum, $this->limit, $pagenum);
		$this->assign('page_info', $page_info);

		$this->view('account/index.tpl');
	}

	/**
	 * detail
	 */
	public function detail()
	{
		$this->load->model('Account_model');
		$account_id = $this->get('account_id');
		$choices = array();

		// ワンタイム
		$this->set_unique_hash();

		if ($account_id)
		{
			$information = $this->Account_model->get_account($account_id);
			$this->assign_form('login_id', $information['login_id']);
			$this->assign_form('name', $information['name']);
			$this->assign_form('password', $information['password']);
			$this->assign_form('is_admin', $information['is_admin']);
		}
		else
		{
			// デフォルト値
		}

		$this->view('account/detail.tpl');
	}

	/**
	 * エラーチェック
	 */
	public function validate($is_check_only=FALSE)
	{
		$rules =  array(
			array (
				'field' => 'name', // POSTフィールド名
				'label' => '名前', // POSTフィールドラベル
				'rules' => 'required', // ルール
			),
			array (
				'field' => 'login_id', // POSTフィールド名
				'label' => 'ログインID', // POSTフィールドラベル
				'rules' => 'required|callback_check_login_id', // ルール
			),
			array (
				'field' => 'password', // POSTフィールド名
				'label' => 'パスワード', // POSTフィールドラベル
				'rules' => 'required', // ルール
			),
			array (
				'field' => 'is_admin', // POSTフィールド名
				'label' => '権限', // POSTフィールドラベル
				'rules' => 'required', // ルール
			)
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
	 * URLチェック
	 */
	public function check_login_id($login_id)
	{
		$account_id = $this->get('account_id');

		if ($login_id == '')
		{
			// 未入力はOK
			return TRUE;
		}

		$this->load->model('Account_model');
		if ($this->Account_model->check_login_id($login_id, $account_id))
		{
			$this->form_validation->set_message('check_login_id', '登録済みの%sです。');
			return FALSE;
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

		$this->load->model('Account_model');
		$this->Account_model->load->database();
		$this->Account_model->db->trans_begin();

		$account_id = $this->get('account_id');

		$forms = array();
		$forms['name'] = $this->get('name');
		$forms['login_id'] = $this->get('login_id');
		$forms['password'] = $this->get('password');
		$forms['is_admin'] = $this->get('is_admin');

		// 更新
		if ($account_id)
		{
			// info登録
			$ret = $this->Account_model->update($account_id, $forms);

			// 戻り値から、失敗時には、ロールバックを行う
			$this->complete_fail($ret, '');
		}
		// 新規
		else
		{
			// info登録
			$ret = $this->Account_model->insert($forms);

			// 戻り値から、失敗時には、ロールバックを行う
			$this->complete_fail($ret, '');
		}

		$this->Account_model->db->trans_commit();

		// 完了後、一覧へ飛ばす
		$url = str_replace('complete/', '', $_SERVER["REQUEST_URI"]) .'?type=complete';
		$this->util->header('Location: '. $url);
		exit;
	}

	/**
	 * 削除処理
	 */
	public function delete()
	{
		$this->load->model('Account_model');

		// hashチェック
		$hash = $this->get('hash');
		if ( ! $this->check_unique_hash($hash))
		{
			$url = str_replace('delete/', '', $_SERVER["REQUEST_URI"]) .'?type=deletefail';
			$this->util->header('Location: '. $url);
			exit;
		}

		$account_id = $this->get('account_id');

		$info = $this->Account_model->get_account($account_id);
		if ( ! $info)
		{
			$url = str_replace('delete/', '', $_SERVER["REQUEST_URI"]) .'?type=deletefail';
			$this->util->header('Location: '. $url);
			exit;
		}

		$this->Account_model->load->database();
		$this->Account_model->db->trans_begin();


		// 削除
		$ret = $this->Account_model->delete($account_id);
		if ( ! $ret)
		{
			$this->Account_model->db->trans_rollback();
			$url = str_replace('delete/', '', $_SERVER["REQUEST_URI"]) .'?type=deletefail';
			$this->util->header('Location: '. $url);
			exit;
		}

		$this->Account_model->db->trans_commit();

		// 完了後、一覧へ飛ばす
		$url = str_replace('delete/', '', $_SERVER["REQUEST_URI"]) .'?type=delete';
		$this->util->header('Location: '. $url);
		exit;
	}

}

/* End of file info.php */
/* Location: ./application/controllers/info.php */
