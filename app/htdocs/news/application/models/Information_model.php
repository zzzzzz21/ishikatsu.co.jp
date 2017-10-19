<?php
/**
 * お知らせ
 *
 * @package	application
 * @author	 maruoka
 * @version	1.0
 * @filesource
 */
class Information_model extends MY_Model {

	/**
	 * コンストラクタ
	 *
	 * @access　public
	 */
	function __construct()
	{
		parent::__construct();

		// プレビュー情報設定
		$this->preview_manage_type = 1;

		// データ上書き項目設定
		// [データ項目名] => [設定項目名]
		$this->preview_post_data_format_item = array(
			'info_id' => 'info_id',
			'info_type' => 'info_type',
			'prog_category_id' => 'prog_category_id',
			'link_url' => 'link_url',
			'title' => 'title',
			'body' => 'body',
			'disp_date_ymd' => 'disp_date',
			'end_date' => 'end_date',
			'is_target' => 'is_target',
		);

		// プレビュー情報取得
		if ($preview_id = $this->is_preview())
		{
			$data = $this->get_preview_data($preview_id);
			$this->preview_post_data = $data;
		}
	}

	/**
	 * 詳細取得
	 */
	function get_info_detail($news_id)
	{
		$sql = <<<EOD
SELECT
 info_id
 ,info_type
 ,disp_date
 ,title
 ,body
 ,link_url
FROM
 {$this->config->item('table_prefix')}t_information
WHERE
 info_id = ?
AND
 disp_date <= now()
AND
 is_hide = 0
EOD;
		$bind = array(
			$news_id,
		);
		$query = $this->db->query($sql, $bind);
		$rs = $query->row_array();

		// プレビューデータ上書き※プレビュー時のみ
		$rs = $this->set_preview_data($rs);

		return $rs;
	}

	/**
	 * 詳細取得
	 */
	function get_info_detail_oldid($old_id)
	{
		$sql = <<<EOD
SELECT
 info_id
FROM
 {$this->config->item('table_prefix')}t_information
WHERE
 old_id = ?
EOD;
		$bind = array(
			$old_id,
		);
		$query = $this->db->query($sql, $bind);
		$rs = $query->row_array();

		return $rs;
	}

	/**
	 * 一覧取得(有効なもののみ)
	 */
	function get_info_list($type, $category_id = 0, $limit = 2)
	{
		$sql = <<<EOD
SELECT
 info_id
 ,info_type
 ,disp_date
 ,title
 ,link_url
 ,is_target
 ,content_ext
 ,content_filesize
FROM
 {$this->config->item('table_prefix')}t_information
WHERE
 info_type = ?
AND
 now() between disp_date and end_date
AND
 is_hide = 0
ORDER BY
 disp_date DESC
LIMIT ?
EOD;
		$bind = array(
			$type,
			$limit,
		);
		$query = $this->db->query($sql, $bind);
		$rs = $query->result_array();

		return $rs;
	}

	/**
	 * 一覧取得(終了していない OR 終了しているものを全件)
	 */
	function get_information_list($end = 0, $type = null, $year = null, $is_link_url_null=FALSE, $limit=null, $offset=null)
	{
		$date = date('Y-m-d H:i:s');

		// 終了していない
		$bind[] = $date;
		if ($end == 0) {
			$wehreSql = ' end_date >= ? ';
		// 終了している
		} else {
			$wehreSql = ' end_date < ? ';
		}

		if (($type !== null) && $type)
		{
			$wehreSql .= ' AND link_url IS NULL AND content_filesize IS NULL ';
		}

		if (($year !== null) && $year)
		{
			$wehreSql .= " AND DATE_FORMAT(disp_date, '%Y') = ? ";
			$bind[] = $year;
		}

		$sql_link_url_null = '';
		if ($is_link_url_null)
		{
			$sql_link_url_null = ' AND link_url IS NULL ';
		}

		$limit_sql = '';
		if ($limit !== null)
		{
			$bind[] = $limit;
			$limit_sql = ' LIMIT ? ';
		}
		if ($offset !== null)
		{
			$bind[] = $offset;
			$limit_sql .= ' OFFSET ? ';
		}


		$sql = <<<EOD
SELECT SQL_CALC_FOUND_ROWS
 info_id
 ,info_type
 ,disp_date
 ,title
 ,link_url
 ,is_target
 ,content_ext
 ,content_filesize
FROM
 {$this->config->item('table_prefix')}t_information
WHERE
 {$wehreSql}
AND
 is_hide = 0
AND
 disp_date <= now()
{$sql_link_url_null}
ORDER BY
 disp_date DESC, info_id DESC
{$limit_sql}
EOD;

		$query = $this->db->query($sql, $bind);
		$rs = $query->result_array();
		// 結果総件数を取得
		$row_num_query = $this->db->query('SELECT FOUND_ROWS() as cnt');
		$row_num = $row_num_query->row_array();
		$this->row_num = $row_num['cnt'];
		return $rs;
	}

	/**
	 * 次へ前へを取得
	 */
	function get_next_prev($info_id, $type='next')
	{
		// 終了していないニュース
		$open_news_list = $this->get_information_list(FALSE, 1, null, TRUE);

		// 終了しているニュース
		$close_news_list = $this->get_information_list(TRUE, 1, null, TRUE);

		$rs = array();

		if ($open_news_list)
		{
			foreach ($open_news_list as $key => $val)
			{
				$rs[] = $val;
			}
		}
		if ($close_news_list)
		{
			foreach ($close_news_list as $key => $val)
			{
				$rs[] = $val;
			}
		}

		$rtn = array();
		if ($rs)
		{
			$list = $rs;
			if ($type == 'next')
			{
				$list = array_reverse($rs);
			}
			$is_hit = FALSE;
			foreach ($list as $key => $val)
			{
				if ($is_hit)
				{
					return $val;
				}
				if ($val['info_id'] == $info_id)
				{
					$is_hit = TRUE;
				}
			}
		}

		return $rtn;
	}

	/**
	 * 登録されている年一覧取得
	 */
	function get_year_list($type)
	{
		$bind = array();
		$wehreSql = '';
		if (($type !== null) && $type)
		{
			$wehreSql = ' info_type = ? AND ';
			$bind[] = $type;
		}

		$sql = <<<EOD
SELECT DISTINCT
 date_format(disp_date, '%Y') as year
FROM
 {$this->config->item('table_prefix')}t_information
WHERE
{$wehreSql}
 disp_date < NOW()
AND
 is_hide = 0
ORDER BY
 disp_date DESC
EOD;
		$query = $this->db->query($sql, $bind);
		$rs = $query->result_array();

		return $rs;
	}

}
?>
