<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Util
{	
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
	 * ページング取得関数
	 * 
	 * @param	string
	 * @param	string
	 * @param	int
	 * @param	int
	 * @param	bool
	 * @param	bool
	 * @param	string
	 * @param	bool
	 * 
	 **/
	function pager($base_url, $count, $limit, $page, $paging_type=FALSE, $is_link_return=FALSE, $anchor_class='', $is_pagehtml_return=FALSE)
	{
		$CI =& get_instance();
		
		// ページング
		$CI->load->library('pagination');
		$config['base_url'] = $base_url;
		$config['total_rows'] = $count;
		$config['per_page'] = $limit;
		$config['cur_page'] = $page;
		$config['paging_type'] = $paging_type;
		if ($anchor_class)
		{
			$config['anchor_class'] = ' class="'. $anchor_class .'"';
		}
		
		$CI->pagination->initialize($config);

		// リンク情報で返したい場合
		if ($is_link_return)
		{
			return $CI->pagination->create_links_nxpv(TRUE);
		}
		// HTMLタグとして返す
		else
		{
			// ページング文字列をリターンする
			if ($is_pagehtml_return)
			{
				return $CI->pagination->create_links_nxpv();
			}
			// ページング文字列をassignする
			else
			{
				$CI->assign('paging', $CI->pagination->create_links_nxpv(), false);
			}
		}
	}
	
	/**
	 * ページ番号チェック
	 * 
	 * @param	int
	 * 
	 **/
	function page_check($page)
	{
		if ( $page == 0)
		{
			$page = 1;
		}
		
		if ( ! preg_match('/^[0-9]+$/', $page))
		{
			$page = 1;
		}
		return $page;
	}
	/**
	 * メール送信関数
	 * 
	 * @param	string
	 * @param	string
	 * @param	array
	 * 
	 **/
	function send_mail($to, $template, $macro)
	{

		$CI =& get_instance();
		$smarty = clone $CI->smarty;

		// キャッシュしないようにする
		$smarty->caching = 0;
		$smarty->cache_lifetime = 0;

		// メール送信時は、sp pcのフォルダ分けはなし
		$config = $CI->config->item('smarty');
		$smarty->template_dir = $config['template_dir'];

		//テンプレート変数に設定
		if (is_array($macro)) 
		{
			foreach ($macro as $key => $value) 
			{
				$smarty->assign($key, $value);
			}
		}

		// ドメイン、プロトコルは共通で設定
		$smarty->assign('protocol_https', $CI->config->item('protocol_https'));
		$smarty->assign('protocol_http', $CI->config->item('protocol_http'));
		$smarty->assign('www_domain', $CI->config->item('www_domain'));

		//メール生成
		$mail = $smarty->fetch($template);
		list($header_line, $body) = preg_split('/\r?\n\r?\n/', $mail, 2);

		//ヘッダ抽出
		$header_lines = explode("\n", $header_line);
		foreach ($header_lines as $h) 
		{
			if (strstr($h, ':') == false) 
			{
				continue;
			}
			list($key, $value) = preg_split('/\s*:\s*/', $h, 2);

			if (strtolower($key) == "from")
			{
				$from = $value;
			}
			elseif(strtolower($key) == "subject")
			{
				$subject = $value;
			}
			else
			{
				continue;
			}
		}

		//メール送信
		$CI->load->library('email');
		$config = array();
		$config['charset'] = 'ISO-2022-JP';
		$config['wordwrap'] = FALSE;
		$CI->email->initialize($config);
		$CI->email->from($from);
		$CI->email->to($to);
		$CI->email->subject(mb_convert_encoding($subject, "ISO-2022-JP-MS", "UTF-8"));
		$CI->email->message(mb_convert_encoding($body, "ISO-2022-JP-MS", "UTF-8"));
		$result = $CI->email->send();

		return $result;
	}

	// 4時区切りでYmd, Himを返す
	function get_today()
	{
		$time = time();

		$h = date('H', $time);
		$i = date('i', $time);
		$s = date('s', $time);
		$date = date('Ymd', $time);
		if ($h < 4)
		{
			// 前日
			$date = date('Ymd', strtotime('-1 day'));

			$h = $h + 24;
		}

		return array($date, $h.$i.$s);
	}
}
/* End of file Util.php */
/* Location: ./application/libraries/Util.php */
?>