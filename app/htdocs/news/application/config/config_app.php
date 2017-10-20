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


$config['from_mail'] = 'no-reply@' . $_SERVER['SERVER_NAME'];

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

// 性別
$config["sex"] = array(
	"1" => "男性",
	"2" => "女性",
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

// 時間プルダウン内容
$config['hour_list'] = array(
	'00' => '00',
	'01' => '01',
	'02' => '02',
	'03' => '03',
	'04' => '04',
	'05' => '05',
	'06' => '06',
	'07' => '07',
	'08' => '08',
	'09' => '09',
	'10' => '10',
	'11' => '11',
	'12' => '12',
	'13' => '13',
	'14' => '14',
	'15' => '15',
	'16' => '16',
	'17' => '17',
	'18' => '18',
	'19' => '19',
	'20' => '20',
	'21' => '21',
	'22' => '22',
	'23' => '23',
);

// 分プルダウン内容
$config['min_list'] = array(
	'0'  => '0',
	'15' => '15',
	'30' => '30',
	'45' => '45',
);

// 分プルダウン内容(1分ごと)
$config['min_list_full'] = array(
	'00' => '00',
	'01' => '01',
	'02' => '02',
	'03' => '03',
	'04' => '04',
	'05' => '05',
	'06' => '06',
	'07' => '07',
	'08' => '08',
	'09' => '09',
	'10' => '10',
	'11' => '11',
	'12' => '12',
	'13' => '13',
	'14' => '14',
	'15' => '15',
	'16' => '16',
	'17' => '17',
	'18' => '18',
	'19' => '19',
	'20' => '20',
	'21' => '21',
	'22' => '22',
	'23' => '23',
	'24' => '24',
	'25' => '25',
	'26' => '26',
	'27' => '27',
	'28' => '28',
	'29' => '29',
	'30' => '30',
	'31' => '31',
	'32' => '32',
	'33' => '33',
	'34' => '34',
	'35' => '35',
	'36' => '36',
	'37' => '37',
	'38' => '38',
	'39' => '39',
	'40' => '40',
	'41' => '41',
	'42' => '42',
	'43' => '43',
	'44' => '44',
	'45' => '45',
	'46' => '46',
	'47' => '47',
	'48' => '48',
	'49' => '49',
	'50' => '50',
	'51' => '51',
	'52' => '52',
	'53' => '53',
	'54' => '54',
	'55' => '55',
	'56' => '56',
	'57' => '57',
	'58' => '58',
	'59' => '59',
);

// datetime最大値
$config['datetime_max'] = '9999-12-31 23:59:59';

// 曜日
$config['youbi_list'] = array(
	0 => '日',
	1 => '月',
	2 => '火',
	3 => '水',
	4 => '木',
	5 => '金',
	6 => '土',
);

$config['table_prefix'] = TABLE_PREFIX;