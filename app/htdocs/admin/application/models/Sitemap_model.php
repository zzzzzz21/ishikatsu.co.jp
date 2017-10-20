<?php

/**
 * sitemap.xml生成
 */
class Sitemap_model extends MY_Model
{
		//sitemap-iniのデータ
		var $ini = null;

	public function __construct()
	{
		parent::__construct();

		// SQLのタイムアウトを防止する
		set_time_limit(0);

		// データベースエラーをアラートできるようにデバッグモードをオフにする
		$this->load->database();
		$this->db->db_debug = FALSE;
	}

	/**
	* sitemap作成メイン
	*
	*/
	function main()
	{
		// 処理日
		$today = date('Y-m-d', mktime(0, 0, 0, date("n"), date("j")-1, date("Y")));

		// 作成
		$this->putSitemapDetail();
	}

	/**
	 * サイトマップ作成
	 */
	function putSitemapDetail(){

		// １ファイルあたりのURL上限
		$limitUrl = 30000;

		if (dirname(BASE_PATH) != '/')
		{
			$url = 'http://www.ishikatsu.co.jp' . dirname(BASE_PATH);
		}
		else
		{
			$url = 'http://www.ishikatsu.co.jp';
		}
		$path = dirname(dirname(dirname(dirname(__FILE__))));

		// 出力先
		$filename = "{$path}/sitemap.xml";
		$fileurl	= "{$url}sitemap.xml";

		$data = array();
		$mdata = array();
		$number = 1;

		$this->load->model('Information_model');

		// トップページ
		$data[] = $this->getIniData("{$url}/", "index");
		// お知らせトップ
		$data[] = $this->getIniData("{$url}/news/", "news");
		//$data[] = $this->getIniData("{$url}/terms/", "terms");

		// 動的ページ系

		// ニュース詳細
		$news_list = $this->Information_model->get_all_list();
		foreach ($news_list as $key => $val)
		{
			$data[] = $this->getIniData("{$url}/news/{$val['info_id']}/", "news_detail");
		}
		$this->createXml($filename, $data, false);
	}

	/**
	 * iniファイルから各種キーを取得
	 */
	function getIniData($url, $key){
		if ( ! $this->ini)
		{
				$this->ini = $this->config->item("sitemap");
		}
		$arr = isset($this->ini[$key]) ? $this->ini[$key] : array();
		$arr['url'] = $url;
		if (isset($arr['lastmod']) && $arr['lastmod'] == "now")
		{
				$arr['lastmod'] = date('c');
		}
		return $arr;
	}

	/**
	 * 圧縮XMLファイル出力
	 */
	function createXml($filename, $data, $mobile=false){

		$dom = new DomDocument('1.0');//DOMを作成
		$dom->encoding = "UTF-8";//文字コードをUTF-8に
		$dom->formatOutput = true;//出力XMLを整形(改行,タブ)する

		$urlset = $dom->appendChild($dom->createElement('urlset'));
		$urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
		if($mobile){
				$urlset->setAttribute('xmlns:mobile', 'http://www.google.com/schemas/sitemap-mobile/1.0');
		}

		foreach ($data as $v)
		{
				$url = $urlset->appendChild($dom->createElement('url'));
				//loc
				$loc = $url->appendChild($dom->createElement('loc'));
				$loc->appendChild($dom->createTextNode($v['url']));

				//lastmod
				if (isset($v['lastmod']) && $v['lastmod'])
				{
						$lastmod = $url->appendChild($dom->createElement('lastmod'));
						$lastmod->appendChild($dom->createTextNode($v['lastmod']));
				}

				//changefreq
				if (isset($v['changefreq']) && $v['changefreq'])
				{
						$changefreq = $url->appendChild($dom->createElement('changefreq'));
						$changefreq->appendChild($dom->createTextNode($v['changefreq']));
				}

				//priority
				if (isset($v['priority']) && $v['priority'])
				{
						$priority = $url->appendChild($dom->createElement('priority'));
						$priority->appendChild($dom->createTextNode($v['priority']));
				}

				//mobile
				if ($mobile)
				{
						$mobile = $url->appendChild($dom->createElement('mobile:mobile'));
				}
		}

		//DomXMLをXML形式で出力
		$xml = $dom->saveXML();

		//タブ除去
		$xml = preg_replace('/\n([ ]*)/', "\n" ,$xml);

		//圧縮なし
		$fp = fopen($filename, "wb");
		fwrite($fp, $xml);
		fclose($fp);
	}

	/**
	 * サイトマップインデックスファイル出力
	 */
	function createIndex($filename, $data, $mobile=false){

		$dom = new DomDocument('1.0');//DOMを作成
		$dom->encoding = "UTF-8";//文字コードをUTF-8に
		$dom->formatOutput = true;//出力XMLを整形(改行,タブ)する

		$sitemapindex = $dom->appendChild($dom->createElement('sitemapindex'));
		$sitemapindex->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

		foreach($data as $v){
				$sitemap = $sitemapindex->appendChild($dom->createElement('sitemap'));
				//loc
				$loc = $sitemap->appendChild($dom->createElement('loc'));
				$loc->appendChild($dom->createTextNode($v['url']));

				//lastmod
				$lastmod = $sitemap->appendChild($dom->createElement('lastmod'));
				$lastmod->appendChild($dom->createTextNode($v['lastmod']));

				//mobile
				if($mobile){
						$mobile = $url->appendChild($dom->createElement('mobile'));
						$mobile->appendChild($dom->createTextNode('mobile'));
				}
		}

		//DomXMLをXML形式で出力
		$xml = $dom->saveXML();
		//圧縮なし
		$fp = fopen($filename, "wb");
		fwrite($fp, $xml);
		fclose($fp);
	}

}
