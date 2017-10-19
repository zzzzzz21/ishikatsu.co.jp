<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Index
	 */
	public function index()
	{
		$do = $this->get('do');
		$this->assign('login_fail', 0);

		// セッション削除
		$this->session->unset_userdata('login');

		// ログインチェック
		if ($do)
		{
			$this->load->model('account_model');
			$login_id = $this->get('login_id');
			$password = $this->get('password');
			$auth = $this->account_model->check_login($login_id, $password);
			if ($auth)
			{
				// ログイン認証OK時
				header('Location: ' . BASE_PATH);
				exit;
			}
			$this->assign('login_fail', 1);
		}
		$this->view('login.tpl');
	}
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */
