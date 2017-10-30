<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// preview情報
$config['sitemap'] = array(
	'index' => array(
		'lastmod'    => 'now',
		'changefreq' => 'daily',
		'priority'   => '1.0',
	),
	'news' => array(
		'lastmod'    => 'now',
		'changefreq' => 'daily',
		'priority'   => '0.8',
	),
	'news_detail' => array(
		'lastmod'    => 'now',
		'changefreq' => 'daily',
		'priority'   => '0.9',
	),
	'static' => array(
		'lastmod'    => 'now',
		'changefreq' => 'never',
		'priority'   => '0.8',
	),

);
