<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['smarty']	= array(
						'template_dir' =>   APPPATH. '/views/templates',
						'compile_dir' => APPPATH. '/views/templates_c',
						'cache_dir' =>	   APPPATH. '/cache',
						'plugins_dir' => APPPATH. '/libraries/smarty_plugins',
						'output_filter' => array('mobile'),
						'batch_plugins_dir'  => '../smarty/plugins',
					);

// セッション設定
$config['sess_use_php_session'] = TRUE;

// ログイン失敗でロックする規定回数
$config['login_fail_max'] = 3;

// 都道府県
$config["pref_id"] = array(
	"1" => "北海道",
	"2" => "青森県",
	"3" => "秋田県",
	"4" => "岩手県",
	"5" => "宮城県",
	"6" => "山形県",
	"7" => "福島県",
	"8" => "茨城県",
	"9" => "栃木県",
	"10" => "群馬県",
	"11" => "埼玉県",
	"12" => "千葉県",
	"13" => "東京都",
	"14" => "神奈川県",
	"15" => "山梨県",
	"16" => "長野県",
	"17" => "新潟県",
	"18" => "富山県",
	"19" => "石川県",
	"20" => "福井県",
	"21" => "岐阜県",
	"22" => "静岡県",
	"23" => "愛知県",
	"24" => "三重県",
	"25" => "滋賀県",
	"26" => "京都府",
	"27" => "大阪府",
	"28" => "兵庫県",
	"29" => "奈良県",
	"30" => "和歌山県",
	"31" => "鳥取県",
	"32" => "島根県",
	"33" => "岡山県",
	"34" => "広島県",
	"35" => "山口県",
	"36" => "徳島県",
	"37" => "香川県",
	"38" => "愛媛県",
	"39" => "高知県",
	"40" => "福岡県",
	"41" => "佐賀県",
	"42" => "長崎県",
	"43" => "熊本県",
	"44" => "大分県",
	"45" => "宮崎県",
	"46" => "鹿児島県",
	"47" => "沖縄県",
	"48" => "海外",
);

// お知らせ種別
$config["contents_type"] = array(
	"1" => "記事",
	"2" => "リンク",
	"3" => "ファイル",
);

// hash時間
$config['hash_time'] = 3600;

// 曜日
$config['week_mst'] = array(
	"0" => "日",
	"1" => "月",
	"2" => "火",
	"3" => "水",
	"4" => "木",
	"5" => "金",
	"6" => "土",
);

//携帯メールアドレスチェック
$config['mb_mail'] = array(
	'/^.+\@docomo\.ne\.jp$/',
	'/^.+\@ezweb\.ne\.jp$/',
	'/^.+\@[a-z][0-9]\.ezweb\.ne\.jp$/',
	'/^.+\@[a-z]\.vodafone\.ne\.jp$/',
	'/^.+\@softbank\.ne\.jp$/',
	'/^.+\@pdx\.ne\.jp$/'
);

// error message
$config['error_message'] = array(
	'500' => 'エラーが発生しました',
);

// datet最大値
$config['date_max'] = '9999-12-31';

// datetime最大値
$config['datetime_max'] = '9999-12-31 23:59:59';

$config['inquiry_status'] = array(
	'0' => '未対応',
	'1' => '対応済み',
	'2' => '確認中',
	'3' => '対応不要',
);

// ck image
$config['ck_img_path'] = array(
	'info' => array(
		'path' => '/ckimg/news/',
		'url' => dirname(BASE_PATH) . '/ckimg/news/',
	),
);

$config['search_select_status'] = array(
	1 => '全て',
	2 => '表示',
	3 => '非表示',
);

$config['account_auth'] = array(
	'0' => '一般ユーザー',
	'1' => '管理者',
);

$config['account_check_auth'] = array(

// 1: 管理者 , 2: お問い合わせ管理者 ,0: 一般ユーザ

	'account' => array(1),
	'category' => array(0,1),
	'contents' => array(0,1),
	'index' => array(0,1,2,3),
	'info' => array(0,1),
	'inq' => array(0,1,2),
	'pdf' => array(0,1),
	'pdfad' => array(0,1),
	'present' => array(0,1),
	'preview' => array(0,1),
	'program' => array(0,1,3),
	'timetable' => array(0,1,3),
	'program_csv' => array(0,1),
	'syncdev' => array(0,1),
	'carousel' => array(0,1),
	'g_pickup' => array(0,1),
	'pickup' => array(0,1),
	'youtube' => array(0,1),
);

$config['table_prefix'] = TABLE_PREFIX;
