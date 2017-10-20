<?php
/**
 * アンカーリンクの検索フォーム作成
 */
function smarty_function_text_link($params, &$smarty)
{
	$list = array();
	if ( ! isset($params['form']))
	{
		$params['form'] = array();
	}
	$form = $params['form'];

	// パラメータ名
	$name    = $params['name'];

	// 選択肢
	$options = $params['options'];

	// 「全て」を含めるか
	$has_all = $params['has_all'];

	// その他フォーム値のパラメータ化
	$form_params = array();
	foreach ($form as $key => $val)
	{
		// 検索条件は必ず「s_」で始まる
		if ($key != $name && strpos($key, 's_') === 0)
		{
			$form_params[] = $key . '=' . urlencode($val);
		}
	}
	$form_param = implode('&', $form_params);
	if ($form_param)
	{
		$form_param = '&' . $form_param;
	}

	$links = array();
	if ($has_all)
	{
		$links[''] = '全て';
	}

	foreach ($options as $key => $val)
	{
		$links[$key] = $val;
	}

	// タグ生成
	$now_value = isset($form[$name]) ? $form[$name] : '';
	$results = array();
	foreach ($links as $key => $val)
	{
		if ((string) $key === $now_value)
		{
			$results[] = htmlspecialchars($val);
		}
		else
		{
			$results[] = '<a href="./?' . $name . '=' . $key . $form_param . '">' . htmlspecialchars($val) . '</a>';
		}
	}
	$result = implode(' | ', $results);

	return $result;
}