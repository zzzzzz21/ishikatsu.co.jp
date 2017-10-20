<?php

require_once APPPATH . 'core/Batch_Controller.php';

/**
 * 共通コントローラ
 */
class MY_Controller extends CI_Controller {
	protected $template;
	protected $cache_id = NULL;

	var $form;
	var $metas;

	public function __construct()
	{
		parent::__construct();

		// セッション開始
		$this->load->library('session');

		// ログインページ以外はログインチェック
		$this->load->helper('url');
		if ($_SERVER['REQUEST_URI'] != BASE_PATH . 'login/')
		{
			if (! $this->check_login())
			{
				//未ログインの場合ログインページへリダイレクト
				//戻り先指定
				$protocol = 'http://' .$_SERVER['SERVER_NAME'];
				if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) {
					$protocol = 'https://'. $_SERVER['SERVER_NAME'];
				}
				$this->session->set_userdata('redirect_url', $protocol. $_SERVER['REQUEST_URI']);
				$this->util->header('Location: '. $protocol . BASE_PATH . 'login/');
				exit;
			}
			$this->assign('login', $this->session->userdata('login'));

			// 権限チェック
			$this->check_auth();
		}

		//ドメイン設定
		$domain = array();
		$domain['www'] = $_SERVER['SERVER_NAME'];
		$this->assign('domain', $domain);

		//独自プラグインdir追加
		$config = $this->config->item('smarty');
		$this->smarty->addPluginsDir($config['plugins_dir']);

		$dir = '';
		$this->smarty->template_dir = $config['template_dir'] .$dir;
		$this->smarty->compile_dir  = $config['compile_dir'];
		$this->smarty->cache_dir  = $config['cache_dir'];
		$this->smarty->left_delimiter = '{{';
		$this->smarty->right_delimiter = '}}';

		//ログイン状態設定
		$this->assign('is_login', $this->check_login());

		// request値をassign
		$request = $this->get_form();
		if ($request)
		{
			foreach ($request as $key => $value) {
				$this->form[$key] = $value;
			}
		}

		// session値をassign
		$this->assign('session', $this->session->all_userdata());

		// 左ナビcookie値をassign
		$this->assign('left_navi_open', explode(',', $this->input->cookie('left_navi_open')));

		$this->assign('config', $this->config->config);
	}

	/**
	 * 権限チェック
	 */
	public function check_auth()
	{
		$account_check_auth = $this->config->item('account_check_auth');

		$login = $this->session->userdata('login');

		$RTR =& load_class('Router', 'core');
		$page_class = $RTR->fetch_class();

		if (array_search($login['is_admin'], $account_check_auth[$page_class]) === FALSE)
		{
			$this->util->header('Location: /');
			exit;
		}
		return TRUE;
	}

	/**
	 * テンプレート出力
	 */
	public function view($template, $compile_id='')
	{
		$this->template = $template;
		$this->compile_id = $compile_id;
	}

	/**
	 * テンプレート出力前処理
	 */
	public function _output($output)
	{
		// フォーム値設定
		$this->assign('form', $this->form);

		if (strlen($output) > 0)
		{
			echo $output;
		}
		else
		{
			if ( ! isset($this->compile_id))
			{
				$this->smarty->display($this->template, $this->cache_id);
			}
			else
			{
				$this->smarty->display($this->template, $this->cache_id, $this->compile_id);
			}
		}
	}

	public function get_template()
	{
		return $this->template;
	}

	/**
	 * フォーム値にアサイン
	 */
	public function assign_form($name, $value)
	{
		$this->form[$name] = $value;
	}

	/**
	 * テンプレート値にアサイン
	 */
	public function assign($name, $value, $is_escape=TRUE, $nocache=FALSE)
	{
		// エスケープ
		if ($is_escape) {
			$value = $this->escape_val($value);
		}

		$this->smarty->assign($name, $value, $nocache);
	}

	/**
	 * 再帰エスケープ
	 */
	public function escape_val($value)
	{
		if (is_array($value)) {
			foreach ($value as $key => $val) {
				// 配列の場合、再帰的にエスケープ
				if (is_array($val) || is_a($val, 'stdClass')) {
					$value[$key] = $this->escape_val($val);
				} else {
					$value[$key] = htmlspecialchars($val, ENT_QUOTES);
				}
			}
		} else if (is_a($value, 'stdClass')) {
			$tmp_value = (array) $value;
			if ($tmp_value) {
				$tmp_value = $this->escape_val($tmp_value);
				if ($tmp_value) {
					$tmp_obj = new stdClass;
					foreach ($tmp_value as $key => $val) {
						$tmp_obj->$key = $val;
					}
					$value = $tmp_obj;
				}
			}
		} else {
			$value = htmlspecialchars($value, ENT_QUOTES);
		}
		return $value;
	}

	/**
	 * CSRF対策ハッシュセット
	 *
	 * @param	string
	 * @param	bool
	 * @param	int
	 *
	 **/
	function set_unique_hash($name="hash")
	{
		$hash_time = $this->config->item('hash_time');

		$now = time();

		// hash 有効時間
		$time = $hash_time + $now;

		// hash値
		$hash = md5($now + microtime());

		// テンプレートに設定
		$this->assign_form($name, $hash, TRUE, TRUE);

		// 有効期限が切れているhashは削除
		$session_hash = $this->session->userdata('session_hash');
		if (is_array($session_hash) && count($session_hash) > 0) {
			foreach ($session_hash as $key => $val) {
				if ($val['e_time'] < $now) {
					unset($session_hash[$key]);
				}
			}
		}

		// セッション設定
		$session_hash[$name.$hash] = array('hash' => $hash, 'e_time' => $time);
		$this->session->set_userdata('session_hash', $session_hash);
		return $hash;
	}

	/**
	 * CSRFチェック
	 *
	 * @param	string
	 * @param	bool
	 * @param	int
	 *
	 **/
	function check_unique_hash($hash, $name="hash")
	{
		// POSTのハッシュ値とセッションのハッシュ値を比較
		$session_hash = $this->session->userdata('session_hash');
		if (! isset($session_hash[$name.$hash]['hash']))
		{
			return false;
		}
		else if (! $session_hash[$name.$hash]['hash'])
		{
			return false;
		}
		else if (! $hash)
		{
			return false;
		}
		else if ($hash != $session_hash[$name.$hash]['hash'])
		{
			return false;
		}
		else
		{
			unset($session_hash[$name.$hash]);
			$this->session->set_userdata('session_hash', $session_hash);
			return true;
		}
	}

	// hidddenタグ自動生成
	function set_hidden_vars($includes=array(), $excludes=array()){

		$form = $this->input->post();
		if($form === False){
			$this->assign('hidden_vars', '');
		}

		$values = array();
		if(count($includes) == 0 && count($excludes) == 0){
			$values = $form;
		} else {
			foreach($form as $key=>$value){
				if(in_array($key, $excludes)){
					//exclude
					continue;
				}
				if(in_array($key, $includes)){
					//include
					$values[$key] = $value;
				}
			}
		}

		//hiddenタグ作成
		$hidden = '';
		if (is_array($values))
		{
			foreach($values as $key=>$value){
				$hidden .= $this->make_hidden($key, $value);
			}
			$this->assign('hidden_vars', $hidden, FALSE);
		}
		return;
	}

	protected function make_hidden($name, $value){
		$hidden = '';
		if(is_array($value)){
			foreach($value as $k=>$v){
				$hidden .= $this->make_hidden($name.'['.$k.']', $v);
			}
		}else{
			$hidden .= '<input type="hidden" name="'. $name. '" value="'. $this->escape_val($value). '">' ."\n";
		}

		return $hidden;
	}

	// アクセスIP取得
	function get_accsess_ip(){
		return  $_SERVER['REMOTE_ADDR'];
	}

	//クッキーセット
	function set_cookie($name, $value, $secure=FALSE){
		$cookie_expire = $this->config->item('cookie_expire');

		$expire = 0;
		if (isset($cookie_expire[$name])){
			$expire = $cookie_expire[$name];
		}

		$cookie = array(
			'name'   => $name,
			'value'  => $value,
			'expire' => $expire,
			'domain' => $_SERVER['SERVER_NAME'],
			'path'   => '/',
			'secure' => $secure
		);

		$this->input->set_cookie($cookie);
	}

	//クッキー取得
	function get_cookie($name){
		return $this->input->cookie($name);
	}

	//クッキー削除
	function delete_cookie($name){
		$cookie = array(
			'name'   => $name,
			'domain' => $_SERVER['SERVER_NAME'],
			'path'   => '/',
		);

		$this->input->set_cookie($cookie);
	}

	/**
	 * ソート条件取得
	 */
	public function get_sort_cond()
	{
		// Cookieにソート内容を記憶するようにする
		$kind = 'list_sort';
		$is_post = $this->get('is_post');

		$sort_key = '';
		$sort_type = '';
		if ($is_post)
		{
			$sort_key = $this->get('sort_key');
			$sort_type = $this->get('sort_type');
			$val = array('key' => $sort_key, 'type' => $sort_type);
			$this->set_list_cookie($kind, $val);
		} else {
			$sorts = $this->get_list_cookie($kind);
			if (isset($sorts['key']))
			{
				$sort_key = $sorts['key'];
			}
			if (isset($sorts['type']))
			{
				$sort_type = $sorts['type'];
			}
			$this->assign_form('sort_key', $sort_key);
			$this->assign_form('sort_type', $sort_type);
		}

		if (($sort_type != 'ASC') && ($sort_type != 'DESC'))
		{
			$sort_type = '';
		}

		return array($sort_key, $sort_type);
	}

	/**
	 * 一覧用クッキーの基本名取得
	 */
	public function get_list_cookie_base_name()
	{
		$tmp = $_SERVER['REQUEST_URI'];
		$tmp2 = explode('/', $tmp);
		if (isset($tmp2[3]))
		{
			$base_name = $tmp2[1] . '_' . $tmp2[2];
		}
		else
		{
			$base_name = $tmp2[1] . '_';
		}
		return $base_name;
	}

	/**
	 * 一覧用クッキー設定
	 */
	public function set_list_cookie($kind, $val)
	{
		$base_name = $this->get_list_cookie_base_name();

		$cookies = $this->get_list_cookie($kind, TRUE);
		$cookies[$base_name] = $val;

		// シリアライズ
		$cookies = base64_encode(serialize($cookies));
		$this->set_cookie($kind, $cookies);
		return '';
	}

	/**
	 * 一覧用クッキー取得
	 */
	public function get_list_cookie($kind, $is_all=FALSE)
	{
		$base_name = $this->get_list_cookie_base_name();

		$cookies = $this->get_cookie($kind);

		// デコード
		$cookies = unserialize(base64_decode($cookies));

		if ($is_all)
		{
			return $cookies;
		}

		if (isset($cookies[$base_name]))
		{
			return $cookies[$base_name];
		}

		return '';
	}

	//ログインチェック
	function check_login()
	{
		if ($this->session->userdata('login'))
		{
			return TRUE;
		}
		return FALSE;
	}

	//ログイン時リダイレクト先設定
	function set_redirect_url_by_login($url)
	{
		$this->session->set_userdata('redirect_url', $url);
	}

	/**
	 * 一時画像アップロード
	 */
	public function tmp_file_upload($key, $is_array=false)
	{
		if ( ! is_array($_FILES) || ! isset($_FILES[$key]))
		{
			// アップロードファイル無し
			return TRUE;
		}

		$file = $_FILES[$key];
		if ($is_array)
		{
			// 対象キーが配列の場合
			foreach ($file['name'] as $ii => $val)
			{
				$a_file = array(
					'name'     => $file['name'][$ii],
					'type'     => $file['type'][$ii],
					'tmp_name' => $file['tmp_name'][$ii],
					'error'    => $file['error'][$ii],
					'size'     => $file['size'][$ii],
				);
				$this->tmp_file_upload_main($a_file, $key . '_' . $ii);
			}
		}
		else
		{
			$this->tmp_file_upload_main($file, $key);
		}
	}

	/**
	 * 一時画像アップロードメイン
	 */
	public function tmp_file_upload_main($file, $key)
	{
		if ( ! isset($file) || ! isset($file['tmp_name']) || $file['tmp_name'] == '')
		{
			// アップロードファイル無し
			return TRUE;
		}

		if (isset($this->image_error[$key]))
		{
			// エラーあり
			return TRUE;
		}

		// 画像の読込、セッションに記録
		$tmp   = explode('.', $file['name']);
		$ext   = $tmp[count($tmp)-1];

		$type  = $file['type'];
		$bin   = file_get_contents($file['tmp_name']);
		$bin64 = base64_encode($bin);

		// フォーム値に保存
		$this->form[$key . '_type']  = $type;
		$this->form[$key . '_bin64'] = $bin64;
		$this->form[$key . '_ext']   = $ext;
		$this->form[$key . '_filename'] = $file['name'];

//		// セッションに保存
//		$this->session->set_userdata($key . '_type',  $type);
//		$this->session->set_userdata($key . '_bin64', $bin64);
//		$this->session->set_userdata($key . '_ext', $ext);
//		$this->session->set_userdata($key . '_filename', $file['name']);
	}

	/**
	 * 画像ファイル読込
	 */
	public function load_image($path, $key, $filename)
	{
		$base_path = dirname(dirname(dirname(__FILE__)));
		$file_path = $base_path . '/htdocs/images/' . $path . '/';

		// 読込
		$bin   = file_get_contents($file_path . $filename);
		$bin64 = base64_encode($bin);
		$tmp   = explode('.', $filename);
		$ext   = $tmp[count($tmp)-1];
		$type  = $this->get_mime_type($ext);

		// フォームに保存
		$this->form[$key . '_type']  = $type;
		$this->form[$key . '_bin64'] = $bin64;
		$this->form[$key . '_ext']  = $ext;

	}

	/**
	 * 拡張子からmimeタイプ取得
	 */
	public function get_mime_type($ext)
	{
		$mime = '';
		switch (strtolower($ext))
		{
			case 'jpg':
			case 'jpeg':
				$mime = 'image/jpeg';
				break;
			case 'gif':
				$mime = 'image/gif';
				break;
			case 'png':
				$mime = 'image/png';
				break;
		}
		return $mime;
	}

	/**
	 * 全角ファイル名対応のヘッダー取得
	 */
	function get_file_download_header($name, $file_path)
	{
		// ブラウザを判定する
		$ua = $_SERVER['HTTP_USER_AGENT'];

		$browser = 'unknown';
		if (strstr($ua, 'MSIE') && !strstr($ua, 'Opera'))
		{
			$browser = 'msie';
		}
		elseif (strstr($ua, 'Opera'))
		{
			$browser = 'opera';
		}
		elseif (strstr($ua, 'Firefox'))
		{
			$browser = 'firefox';
		}
		elseif (strstr($ua, "Chrome"))
		{
			$browser = 'chrome';
		}
		elseif (strstr($ua, "Safari"))
		{
			$browser = 'safari';
		}

		// 英数字だけかを判定する
		$ascii = mb_convert_encoding($name, "US-ASCII", "UTF-8");
		if ($ascii == $name)
		{
			$browser = 'ascii';
		}

		// ブラウザに応じた処理
		$is_rfc2231 = FALSE;
		switch ($browser)
		{
			case 'ascii':
			// urlencode する
				$name = str_replace(' ','_',$name);
				$name = rawurlencode($name);
				break;

			case 'msie':
				// SJIS に変換する
				$name = mb_convert_encoding($name, "SJIS", "UTF-8");
				break;

			case 'firefox':
			case 'chrome':
			case 'opera':
				// RFC2231形式を使用する
				$name = "utf-8'ja'".rawurlencode($name);
				$is_rfc2231 = TRUE;
				break;

			case 'safari':
				// UTF-8 のまま
				break;

			default:
				// 諦めて代替えを使う
				$name = $name_alt;
				$name = str_replace(' ','_',$name);
				$name = rawurlencode($name);
				break;
		}

		$size = filesize( $file_path );
		$mime = 'application/octet-stream';

		if ( $is_rfc2231 )
		{
			$dis = 'Content-Disposition: attachment; filename*=';
		}
		else
		{
			$dis = 'Content-Disposition: attachment; filename=';
		}

		ini_set('zlib.output_compression', 'Off');
		mb_http_output('pass');

		// ヘッダー出力
		$headers = array();
		$headers[] = 'Pragma: public';
		$headers[] = 'Cache-Control: must-revaitem_idate, post-check=0, pre-check=0';
		$headers[] = 'Content-Description: File Transfer';
		$headers[] = 'Content-Type: '. $mime ;
		$headers[] = 'Content-Length: '. $size;
		$headers[] = $dis . $name;

		return $headers;
	}

	/**
	 * ゴミセッションクリア
	 * ハッシュ
	 */
	public function clean_session()
	{
		$sessions = $this->session->all_userdata();
		$this->session->unset_userdata('session_hash');
	}

	/**
	 * 出力を取得
	 */
	public function fetch($tpl, $html_path='')
	{
		// フォーム値設定
		$this->assign('form', $this->form);
		$html = $this->smarty->fetch($tpl);
		if ( ! $html_path)
		{
			// 保存先htmlの指定が無い場合、結果テキストを返す
			return $html;
		}
		$build_base_path = $this->config->item('build_base_path');
		file_put_contents($build_base_path . $html_path, $html);
	}

	/**
	 * フォーム値を全て取得
	 */
	public function get_form()
	{
		$post = $this->input->post();
		$get  = $this->input->get();
		if ( ! $post)
		{
			return $get;
		}
		if ( ! $get)
		{
			return $post;
		}
		return array_merge($get, $post);
	}

	/**
	 * フォーム値の取得
	 */
	public function get($key='')
	{
		if ($key)
		{
			if (isset($this->form[$key]))
			{
				return $this->form[$key];
			}
			else
			{
				return '';
			}
		}
		return $this->form;
	}

	/**
	 *  csv用に変換
	 *
	 * @param	string
	 * @param	bool
	 * @param	int
	 *
	 **/
	function convert_csv_format($string)
	{
		// 「=」から始まる場合、エスケープ
		if (strpos($string, '=') === 0) {
			$string = "'" . $string;
		}
		// 「,」がある場合
		$string = '"' . str_replace('"', '""', $string) . '"';
		return $string;
	}

	/**
	 *  ヘッダー出力用関数
	 *
	 * @param	string
	 * @param	bool
	 * @param	int
	 *
	 **/
	function header($str, $replace = "", $code ="")
	{
		// \r\nは取り除いて出力
		$strEsc = strtr($str, array("\r"=>"", "\n"=>""));

		if ($replace && $code)
		{
			header($strEsc, $replace, $code);
		}
		elseif ($replace && !$code)
		{
			header($strEsc, $replace);
		}
		else
		{
			header($strEsc);
		}
	}

	/**
	 * ページャーのデータソースを返す
	 *
	 */
	function get_pager_source($base_url, $totalLine, $perPage, $curPage = 1, $dispPage = 5, $isPagingAll=FALSE)
	{
		if (!preg_match('/[0-9]+/', $curPage) OR !$curPage)
		{
			$curPage = 1;
		}

		// ページ総数
		if ($totalLine)
		{
			$totalPage = ceil($totalLine / $perPage);
		}
		else
		{
			$totalPage = 1;
		}
		$centerPos = floor($dispPage / 2);

		if ($curPage <= 2) {
			// 2ページ目以下は3ページ目中央
			$centerPos = 5 - $curPage;
		}
		if ($curPage >= $totalPage - 1) {
			// 最終ページ1つ前からは最終2ページ前中央
			$centerPos = 4 - ($totalPage - $curPage);
		}
		$begin = 1;

        //表示ページ番号
        $begin = $curPage - $centerPos;
        if ($begin < 1) {
        	$begin = 1;
        }

		$end = $curPage + $centerPos;
		if ($totalPage < $end) {
			$end = $totalPage;
		}

		// データライン
		$from = $perPage * ($curPage - 1) + 1;
		$to   = $perPage * $curPage;
		if ($totalLine < $to) {
			$to = $totalLine;
		}

		if ($isPagingAll)
		{
			return array(
				'current'	=> $curPage,
				'prev'		=> ($curPage <= 1) ? null : ($curPage - 1),
				'next'		=> ($curPage < $totalPage) ? ($curPage + 1) :null,
				'totalPage' => $totalPage,                  //追加：表示が１ページの際には表示しない
				'pages'		=> range(1, $totalPage),
				'lines'		=> array(
					'total'		=> $totalLine,
					'from'		=> $from,
					'to'		=> $to,
				),
				'base_url' => $base_url,
			);
		}
		else
		{
			return array(
				'current'	=> $curPage,
				'prev'		=> ($curPage <= 1) ? null : ($curPage - 1),
				'next'		=> ($curPage < $totalPage) ? ($curPage + 1) :null,
				'totalPage' => $totalPage,                  //追加：表示が１ページの際には表示しない
				'pages'		=> range($begin, $end),
				'lines'		=> array(
					'total'		=> $totalLine,
					'from'		=> $from,
					'to'		=> $to,
				),
				'base_url' => $base_url,
			);
		}
	}

	/**
	 * 日付結合
	 *
	 */
	function concat_ymd_h_m($ymd, $h, $m, $to='')
	{
		if ( ! $to)
		{
			return $ymd . ' ' . sprintf('%02d', $h) . ':' . sprintf('%02d',$m) . ':00';
		}
		else
		{
			return $ymd . ' ' . sprintf('%02d', $h) . ':' . sprintf('%02d',$m) . ':59';
		}
	}

	/**
	 * ダウンロード
	 */
	public function dl_common($filepath, $filename)
	{
		$data = file_get_contents($filepath);

		$this->util->header("Content-Type: application/octet-stream");
		$this->util->header("Content-Disposition: attachment; filename=".$filename);
		echo $data;
		exit;
	}

    function upcount_name_callback($matches) {
        $index = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
        $ext = isset($matches[2]) ? $matches[2] : '';
        return ' ('.$index.')'.$ext;
    }

    function upcount_name($name) {
        return preg_replace_callback(
            '/(?:(?: \(([\d]+)\))?(\.[^.]+))?$/',
            array($this, 'upcount_name_callback'),
            $name,
            1
        );
    }

	/**
	 * 同一ファイル名回避名取得
	 */
    function get_rename_file_name($readdir, $filename_pram) {
    	$file_name = $filename_pram;
        while (is_file($readdir . $file_name))
        {
            $file_name = $this->upcount_name($file_name);
        }
        return $file_name;
    }


	/**
	 * 完了処理失敗時
	 */
	public function complete_fail($ret, $id)
	{
		if ( ! $ret)
		{
			$this->load->model('Program_pdf_model');
			$this->Program_pdf_model->db->trans_rollback();
			$url = str_replace('complete/', '', $_SERVER["REQUEST_URI"]) .'?type=completefail&complete_id='.urlencode($id);
			$this->util->header('Location: '. $url);
			exit;
		}
	}

	/**
	 * upload(ck editore)
	 */
	public function upload($conf_name)
	{
		$filepath = '';
		$message = '';

		if ( ! isset($_FILES['upload']))
		{
			$message = 'ファイルを指定してください。';
			$this->upload_response($filepath, $message);
		}

		$upload = $_FILES['upload'];

		// 画像チェック
		$check_message = $this->check_upload_image_base($upload);
		if ($check_message)
		{
			$message = sprintf($check_message, 'ご指定のファイル');
			$this->upload_response($filepath, $message);
		}

		// 全角チェック
		if (strlen($upload['name']) != mb_strlen($upload['name']))
		{
			$message = 'ファイル名は半角にしてください。';
			$this->upload_response($filepath, $message);
		}

		// ファイル名取得 同一ファイルは.1.2...としていく
		$ck_img_path = $this->config->item('ck_img_path');
		$conf = $ck_img_path[$conf_name];
		$filename = $this->get_rename_file_name($conf['path'], $upload['name']);
		$dest = $this->config->item('path') . dirname(BASE_PATH) . $conf['path'] . $filename;

		// 保存
		$ret = move_uploaded_file($upload['tmp_name'], $dest);
		if ( ! $ret)
		{
			$message = 'ファイルの保存に失敗しました。';
			$this->upload_response($filepath, $message);
		}

		// 正常レスポンス
		$filepath = $conf['url'] . $filename;
		$this->upload_response($filepath, $message);
	}

	/**
	 * upload response(ck editore)
	 */
	public function upload_response($filepath, $message)
	{
		$rtn = <<<EOD
<script>// <![CDATA[
window.parent.CKEDITOR.tools.callFunction({$_GET['CKEditorFuncNum']}, '{$filepath}', '{$message}');
// ]]></script>
EOD;
		echo $rtn;
		exit;
	}

	/**
	 * 画像アップロード
	 */
	public function upload_image($name, $confname, $id)
	{
		$filename = '';
		if ( ! isset($_FILES[$name]) OR !$_FILES[$name]['name'])
		{
			return TRUE;
		}

		$file = $_FILES[$name];
		$conf_image_path = $this->config->item($confname);

		$ext = end(explode('.', $file['name']));
		$filename = $id . '.' .$ext;

		$filepath = $conf_image_path['path'] . '/' . $filename;

		$rtn = @move_uploaded_file($file["tmp_name"], $filepath);

		if ($rtn)
		{
			return $filename;
		}
		return FALSE;
	}

	/**
	 * 画像アップロード(配列)
	 */
	public function upload_image_array($name, $confname, $id, $key)
	{
		$filename = '';
		if ( ! isset($_FILES[$name]) OR !$_FILES[$name]['name'][$key])
		{
			return TRUE;
		}

		$file = $_FILES[$name];
		$conf_image_path = $this->config->item($confname);

		$ext = end(explode('.', $file['name'][$key]));
		$filename = $id . '.' .$ext;

		$filepath = $conf_image_path['path'] . '/' . $filename;

		$rtn = @move_uploaded_file($file["tmp_name"][$key], $filepath);

		if ($rtn)
		{
			return $filename;
		}
		return FALSE;
	}

	/**
	 * 画像削除
	 */
	public function delete_image($confname, $filename)
	{
		$conf_image_path = $this->config->item($confname);

		$filepath = $conf_image_path['path'] . '/' . $filename;

		if (file_exists($filepath))
		{
			unlink($filepath);
		}

		return TRUE;
	}

	/**
	 * 画像移動
	 */
	public function rename_image($confname, $orgfilename, $destfilename)
	{
		$conf_image_path = $this->config->item($confname);

		$filepath = $conf_image_path['path'] . '/' . $orgfilename;
		$dest = $conf_image_path['path'] . '/' . $destfilename;

		if (file_exists($filepath))
		{
			rename($filepath, $dest);
		}

		return TRUE;
	}

	/**
	 * 画像移動
	 */
	public function copy_image($confname, $orgfilename, $destfilename)
	{
		$conf_image_path = $this->config->item($confname);

		$filepath = $conf_image_path['path'] . '/' . $orgfilename;
		$dest = $conf_image_path['path'] . '/' . $destfilename;

		if (file_exists($filepath))
		{
			copy($filepath, $dest);
		}

		return TRUE;
	}

	/**
	 * image同期
	 */
	public function sync_ck_image($conf_name, $conf_name2)
	{
		$ck_img_path = $this->config->item('ck_img_path');
		$conf = $ck_img_path[$conf_name];

		$ck_image_sync = $this->config->item('ck_image_sync');
		$conf2 = $ck_image_sync[$conf_name2];

		$command = $this->config->item('command');
		$dests = $conf2['dest'];
		if ( ! is_array($dests))
		{
			$dests = array($dests);
		}
		foreach ($dests as $key => $dest)
		{
			exec($command['sync'] . $conf['path'] . '/ ' . $dest, $out, $ret);
		}
	}

	/******************************************************************************************
	 *
	 * エラーチェック系の関数
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 ******************************************************************************************/

	/**
	 * 画像アップロードの共通チェック
	 * jpg,png,gifのみ、バイト数チェック
	 */
	public function check_upload_image_required($image, $name)
	{
		if ( ! isset($_FILES[$name]) OR ! $_FILES[$name]['name'])
		{
			$this->form_validation->set_message('check_upload_image_required', '%sを選択してください。');
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * 複数画像アップロードの共通チェック
	 * jpg,png,gifのみ、バイト数チェック
	 */
	public function check_upload_images($image, $name)
	{
		if ( ! isset($_FILES[$name]) OR ! isset($_FILES[$name]['name']))
		{
			// アップロード無し
			return TRUE;
		}

		$loop_count = 1;
		foreach ($_FILES[$name]['name'] as $key => $val)
		{

			if ($_FILES[$name]['name'][$key] == '')
			{
				$loop_count++;
				// アップロード無し
				continue;
			}

			$tmp_val = array();
			$tmp_val['name'] = $_FILES[$name]['name'][$key];
			$tmp_val['type'] = $_FILES[$name]['type'][$key];
			$tmp_val['tmp_name'] = $_FILES[$name]['tmp_name'][$key];
			$tmp_val['error'] = $_FILES[$name]['error'][$key];
			$tmp_val['size'] = $_FILES[$name]['size'][$key];

			if ($msg = $this->check_upload_image_base($tmp_val, $loop_count))
			{
				$this->form_validation->set_message('check_upload_images', $msg);
				return FALSE;
			}
			$loop_count++;
		}
		return TRUE;
	}

	/**
	 * 画像アップロードの共通チェック
	 * jpg,png,gifのみ、バイト数チェック
	 */
	public function check_upload_image($image, $name)
	{
		if ( ! isset($_FILES[$name]))
		{
			// アップロード無し
			return TRUE;
		}

		$val = $_FILES[$name];

		if ($val['name'] == '')
		{
			// アップロード無し
			return TRUE;
		}

		if ($msg = $this->check_upload_image_base($val))
		{
			$this->form_validation->set_message('check_upload_image', $msg);
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * 画像アップロードの共通チェック
	 * jpg,png,gifのみ、バイト数チェック
	 */
	public function check_upload_image_base($val, $num='')
	{
		// 拡張子
		$info = @getimagesize($val['tmp_name']);
		if ( ! isset($info['mime']))
		{
			if ($num)
			{
				return '%sの'.$num.'つ目の画像が正しくありません。';
			}
			else
			{
				return '%sが正しくありません。';
			}
		}

		switch ($info['mime'])
		{
			case 'image/jpeg':
			case 'image/pjpeg':
			case 'image/x-png':
			case 'image/png':
			case 'image/gif':
				break;
			default:
				if ($num)
				{
					return '%sの'.$num.'つ目の画像が正しくありません。';
				}
				else
				{
					return '%sが正しくありません。';
				}
				break;
		}

		// ファイルサイズ
		$filesize = @filesize($val['tmp_name']);
		if ($filesize == false || $filesize == 0)
		{
			if ($num)
			{
				return '%sの'.$num.'つ目の画像が正しくありません。';
			}
			else
			{
				return '%sが正しくありません。';
			}
		}

		if ($filesize > 1024*1024*2)
		{
			// 2MB
			if ($num)
			{
				return '%sの'.$num.'つ目の画像のサイズが大きすぎます。(2MB以内)';
			}
			else
			{
				return '%sのサイズが大きすぎます。(2MB以内)';
			}
		}
		return '';
	}

	/**
	 * 日付の共通チェック
	 * YYYY-MM-DD
	 */
	public function check_date($date, $form_name='check_date')
	{
		if ($date == '')
		{
			// 未入力はOK
			return TRUE;
		}

		if ( ! $form_name)
		{
		    $form_name = 'check_date';
		}

		// 形式
		if ( ! preg_match('/^([0-9]{4})\-([0-9]{2})\-([0-9]{2})$/', $date))
		{
			$this->form_validation->set_message($form_name, '%sが正しくありません。');
			return FALSE;
		}

		// 日付
		list($y, $m, $d) = explode('-', $date);
		if ( ! checkdate($m, $d, $y))
		{
			$this->form_validation->set_message($form_name, '%sが正しくありません。');
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * 日付の範囲チェック
	 * YYYY-MM-DDのFrom-To整合性
	 */
	public function check_date_range($to, $from)
	{
		$from = isset($this->form[$from]) ? $this->form[$from] : '';
		if ($from == '' || $to == '')
		{
			// 未入力はOK
			return TRUE;
		}

		$from_stamp = strtotime($from);
		$to_stamp = strtotime($to);

		// 範囲
		if ($from > $to)
		{
			$this->form_validation->set_message('check_date_range', '日付の範囲が正しくありません。');
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * URLチェック
	 * 自ドメイン内も認めるため、[http(s)]で始まる必要は無い
	 */
	public function check_url($url)
	{
		if ($url == '')
		{
			// 未入力はOK
			return TRUE;
		}

		// チェック
		// [http://][https://][/]いずれかで始まり、指定の英数記号以外を含まない
		if ( ! preg_match('/^(https?:\/)?\/([a-zA-Z0-9_\-\/\.\?\&=%#~|]+)$/', $url))
		{
			$this->form_validation->set_message('check_url', '%sが正しくありません。');
			return FALSE;
		}

		return TRUE;
	}

	// 必須チェック（選択肢の場合）
	public function check_required_select($val)
	{
		$ret = $this->form_validation->required($val);
		if ( ! $ret)
		{
			$this->form_validation->set_message('required_select', '%sを選択してください。');
		}
		return $ret;
	}

	// 必須チェック（文言違い）
	public function check_required_original_message($val)
	{
		$ret = $this->form_validation->required($val);
		if ( ! $ret)
		{
			$this->form_validation->set_message('required_original_message', '%sが入力されていません。');
		}
		return $ret;
	}

	/**
	 * 年チェック
	 */
	public function check_year($year, $addyear=5)
	{
		if ( ! strlen($year))
		{
			return TRUE;
		}

		$year_list = $this->get_select_year(2009, $addyear);

		if (array_search($year, $year_list) === FALSE)
		{
			$this->form_validation->set_message('check_year', '%sは正しくありません。');
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * 時チェック
	 */
	public function check_hour($hour, $form_name='check_hour')
	{
		if ( ! strlen($hour))
		{
			return TRUE;
		}

		$hour_list = $this->get_select_hour();

		if ( ! $form_name)
		{
		    $form_name = 'check_hour';
		}

		if (array_search($hour, $hour_list) === FALSE)
		{
			$this->form_validation->set_message($form_name, '%sは正しくありません。');
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * 分チェック
	 */
	public function check_min($min, $form_name='check_min')
	{
		if ( ! strlen($min))
		{
			return TRUE;
		}

		$min_list = $this->get_select_min();

		if ( ! $form_name)
		{
		    $form_name = 'check_min';
		}

		if (array_search($min, $min_list) === FALSE)
		{
			$this->form_validation->set_message($form_name, '%sは正しくありません。');
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * 日時分チェック
	 */
	public function check_date_ymdhi_required($date, $base_name)
	{
		$date_hour = $this->get($base_name.'_hour');
		$date_min = $this->get($base_name.'_min');

		if ( ! strlen($date) && ! strlen($date_hour) && ! strlen($date_min))
		{
			$this->form_validation->set_message('check_date_ymdhi', '%sを入力してください');
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * 日時分チェック
	 */
	public function check_date_ymdhi($date, $base_name)
	{
		$date_hour = $this->get($base_name.'_hour');
		$date_min = $this->get($base_name.'_min');

		if ( ! strlen($date) && ! strlen($date_hour) && ! strlen($date_min))
		{
			return TRUE;
		}

		if ( ! strlen($date) OR ! strlen($date_hour) OR ! strlen($date_min))
		{
			$this->form_validation->set_message('check_date_ymdhi', '%sは正しくありません。');
			return FALSE;
		}

		if ( ! $this->check_date($date, 'check_date_ymdhi'))
		{
			return FALSE;
		}

		if ( ! $this->check_hour($date_hour, 'check_date_ymdhi'))
		{
			return FALSE;
		}

		if ( ! $this->check_min($date_min, 'check_date_ymdhi'))
		{
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * 半角チェック
	 */
	public function check_hankaku($val)
	{
		$id = $this->get('id');

		// 半角チェック
		if (strlen($val) != mb_strlen($val))
		{
			$this->form_validation->set_message('check_hankaku', '%sは半角で入力してください。');
			return FALSE;
		}

		return TRUE;
	}

	/******************************************************************************************
	 *
	 * 選択肢系の取得関数
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 ******************************************************************************************/

	/**
	 * 年つきリスト取得
	 */
	public function get_select_year_month($start=2011)
	{
		// 2ヶ月先
		$tow_month_next = strtotime('+2 month');
		$year = date('Y', $tow_month_next);
		$month = date('n', $tow_month_next);


		$tmp = range(date('Y', $tow_month_next), $start,  -1);
		foreach ($tmp as $key => $val)
		{
			if (date('Y', $tow_month_next) == $val)
			{
				for ($i=$month; $i>0; $i--)
				{
					$rtn[$val.sprintf('%02d',$i)] = $val.'年'.$i.'月';
				}
			}
			else
			{
				for ($i=12; $i>0; $i--)
				{
					$rtn[$val.sprintf('%02d',$i)] = $val.'年'.$i.'月';
				}
			}
		}
		return  $rtn;
	}

	// 誕生年リスト取得
	public function get_birth_year_list(){
		$tmp = range(1940, date('Y'));
		return  array_combine($tmp,$tmp);
	}

	/**
	 * 年度リスト取得
	 */
	public function get_select_year_fiscal($start=2010, $addend=5)
	{
		$tmp = range($start, date('Y') + $addend);
		foreach ($tmp as $key => $val)
		{
			$rtn[$val] = $val.'-'.($val+1);
		}
		return  $rtn;
	}

	/**
	 * 年リスト取得
	 */
	public function get_select_year($start=2010, $addend=5)
	{
		$tmp = range($start, date('Y') + $addend);
		return  array_combine($tmp,$tmp);
	}

	/**
	 * 年リスト取得
	 */
	public function get_select_month()
	{
		$tmp = range(1, 12);
		return  array_combine($tmp,$tmp);
	}

	/**
	 * 年リスト取得(ギャラリー)
	 */
	public function get_select_gallery_year()
	{
		$list = $this->get_select_year($start=2010, $addend=0);
		$rtn = array();
		foreach ($list as $key => $val)
		{
			$rtn[$key] = $val.'年';
		}

		return $rtn;
	}

	/**
	 * 時リスト取得
	 */
	public function get_select_hour()
	{
		$tmp = range(0, 23);
		foreach ($tmp as $key => $val)
		{
			$tmp[$key] = $val;
		}
		return array_combine($tmp,$tmp);
	}

	/**
	 * 分リスト取得
	 */
	public function get_select_min()
	{
		$tmp = range(0, 59);
		foreach ($tmp as $key => $val)
		{
			$tmp[$key] = $val;
		}
		return array_combine($tmp,$tmp);
	}

	/**
	 * 分リスト取得
	 */
	public function get_select_min_step_ten()
	{
		$tmp = array(0, 10, 20, 30, 40, 50);
		foreach ($tmp as $key => $val)
		{
			$tmp[$key] = $val;
		}
		return array_combine($tmp,$tmp);
	}

	/**
	 * 優先順位
	 */
	public function get_select_priority()
	{
		$tmp = range(1, 10);
		foreach ($tmp as $key => $val)
		{
			$tmp[$key] = $val;
		}
		return array_combine($tmp,$tmp);
	}


}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
?>
