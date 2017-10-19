<?php

require_once APPPATH . 'core/SSL_Controller.php';

/**
 * 共通コントローラ
 */
class MY_Controller extends CI_Controller {
	protected $template;
	protected $cache_id = NULL;

	var $form;
	var $metas;
	var $is_ssl_page = FALSE;

	public function __construct($is_core_error=FALSE)
	{
		// エラーページ表示用にinputクラスを補正する
		if ($is_core_error)
		{
			$IN =& load_class('Input', 'core');
			$this->input = $IN;
		}

		parent::__construct();

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

		// request値をassign
		$request = $this->get_form();
		if ($request)
		{
			foreach ($request as $key => $value) {
				$this->form[$key] = $this->trimEmoji($value);
			}
		}

		$this->assign('config', $this->config->config);
	}

	/**
	 * 404
	 */
	public function show_404()
	{
		// 404を返さないと検索エンジンに404画面が登録されてしまう。
		$this->output->set_status_header('404');

		// 各言語対応
		$file = 'error/404.tpl';

		// 404になったURLのログを残す
		$filename = '404';
		$text = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		$this->log($filename, $text);

		// ここでViewの構築など、独自の処理
		$this->view($file);
	}

	/**
	 * ログ出力
	 */
	public function log($filename, $text)
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

		$log_path = APPPATH . 'logs/' . $filename . '.log';
		$fp = fopen($log_path, 'a');
		fwrite($fp, sprintf("[%s] %s\n", date('Y-m-d H:i:s'), $text));
		fclose($fp);
		unset($fp);
	}

	/**
	 * テンプレート出力
	 */
	public function view($template, $compile_id='')
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

		$this->template = $template;
		$this->compile_id = $compile_id;
	}

	/**
	 * テンプレート出力前処理
	 */
	public function _output($output)
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

		return $this->template;
	}

	/**
	 * フォーム値にアサイン
	 */
	public function assign_form($name, $value)
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

		$this->form[$name] = $value;
	}

	/**
	 * テンプレート値にアサイン
	 */
	public function assign($name, $value, $is_escape=TRUE, $nocache=FALSE)
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
	function set_hidden_vars($includes=array(), $excludes=array())
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}


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

	protected function make_hidden($name, $value)
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
	function get_accsess_ip()
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

		return  $_SERVER['REMOTE_ADDR'];
	}

	//クッキーセット
	function set_cookie($name, $value, $secure=FALSE)
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
	function get_cookie($name)
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

		return $this->input->cookie($name);
	}

	//クッキー削除
	function delete_cookie($name)
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

		$cookie = array(
			'name'   => $name,
			'domain' => $_SERVER['SERVER_NAME'],
			'path'   => '/',
		);

		$this->input->set_cookie($cookie);
	}

	//ログインチェック
	function check_login()
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

		if ($this->session->userdata('login'))
		{
			return TRUE;
		}
		return FALSE;
	}

	//ログイン時リダイレクト先設定
	function set_redirect_url_by_login($url)
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

		$this->session->set_userdata('redirect_url', $url);
	}

	/**
	 * 一時画像アップロード
	 */
	public function tmp_file_upload($key, $is_array=false)
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

		$sessions = $this->session->all_userdata();
		$this->session->unset_userdata('session_hash');
	}

	/**
	 * 出力を取得
	 */
	public function fetch($tpl, $html_path='')
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
	 *  ヘッダー出力用関数
	 *
	 * @param	string
	 * @param	bool
	 * @param	int
	 *
	 **/
	function header($str, $replace = "", $code ="")
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
				'limit' => $perPage,
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
				'limit' => $perPage,
			);
		}
	}

	/**
	 * 日付結合
	 *
	 */
	function concat_ymd_h_m($ymd, $h, $m, $to='')
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

		if ( ! $to)
		{
			return $ymd . ' ' . sprintf('%02d', $h) . ':' . sprintf('%02d',$m) . ':00';
		}
		else
		{
			return $ymd . ' ' . sprintf('%02d', $h) . ':' . sprintf('%02d',$m) . ':59';
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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

		$id = $this->get('id');

		// 半角チェック
		if (strlen($val) != mb_strlen($val))
		{
			$this->form_validation->set_message('check_hankaku', '%sは半角で入力してください。');
			return FALSE;
		}

		return TRUE;
	}
	/**
	 * エラーチェック
	 */
	public function check_mail($mail)
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

		// 半角英数字のみ
		if ( ! preg_match('/([a-zA-Z0-9]+)$/', $mail))
		{
			$this->form_validation->set_message('check_mail', '%sは半角英数字で入力してください。');
			return FALSE;
		}

		// 確認用との一致チェック
		$mail_confirm = $this->get('email_confirmation');
		if ($mail != $mail_confirm)
		{
			$this->form_validation->set_message('check_mail', 'Eメールアドレス確認用が一致していません。');
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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
	public function get_birth_year_list()
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

		$tmp = range(1940, date('Y'));
		return  array_combine($tmp,$tmp);
	}

	/**
	 * 年度リスト取得
	 */
	public function get_select_year_fiscal($start=2010, $addend=5)
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

		$tmp = range($start, date('Y') + $addend);
		return  array_combine($tmp,$tmp);
	}

	/**
	 * 年リスト取得(ギャラリー)
	 */
	public function get_select_gallery_year()
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

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
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

		$tmp = range(0, 23);
		foreach ($tmp as $key => $val)
		{
			$tmp[$key] = sprintf("%02d", $val);
		}
		return array_combine($tmp,$tmp);
	}

	/**
	 * 分リスト取得
	 */
	public function get_select_min()
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

		$tmp = range(0, 59);
		foreach ($tmp as $key => $val)
		{
			$tmp[$key] = sprintf("%02d", $val);
		}
		return array_combine($tmp,$tmp);
	}

	/**
	 * SP判定
	 */
	function is_sp()
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

		// プレビュー時には、判定をパラメータで行う
		if ($this->is_preview())
		{
			if ($this->input->get('admin_device_type') == 2)
			{
				return TRUE;
			}
			return FALSE;
		}

		$is_sp = FALSE;
		//スマートフォンのユーザーエージェント
		$ua_list = array(
			'iPhone',	// Apple iPhone
			'iPod',	// Apple iPod touch
			'Android.*Mobile',	// Android
			'dream',	// Pre 1.5 Android
			'CUPCAKE',	// 1.5+ Android
			'blackberry',	// blackberry
			'webOS',	// Palm Pre Experimental
			'incognito',	// Other iPhone browser
			'webmate'	// Other iPhone browser
		);

		for($i=0; $i<sizeof($ua_list); $i++) {

			$str = "/".$ua_list[$i]."/i";
			$ret = preg_match($str, $_SERVER['HTTP_USER_AGENT']);

			if($ret != 0 ){
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * preview判定
	 */
	function is_preview()
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

		$this->load->model('Preview_model');
		return $this->Preview_model->is_preview();
	}

	/**
	 * 性別リスト取得
	 */
	public function get_sex_type()
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

		return array(
				'1' => '男',
				'2' => '女',
				);
	}


	/**
	 * 絵文字除去
	 */
	public function trimEmoji($value)
	{
		//直アクセス対応
		if($this->router->fetch_method() == __FUNCTION__){
			$this->show_404();
			return;
		}

		mb_substitute_character('');
		$value = mb_convert_encoding(mb_convert_encoding($value, 'SJIS', 'UTF-8'), 'UTF-8', 'SJIS');
		return $value;
	}

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
?>
