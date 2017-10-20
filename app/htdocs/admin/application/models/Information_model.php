<?php

class Information_model extends MY_Model {

	/**
	 * 公開中一覧取得
	 */
	public function get_list_now_open()
	{
		$this->load->database();

		$binds = array();

		$sql = <<<EOD
SELECT
 *
FROM
 {$this->config->item('table_prefix')}t_information
WHERE
	now() between disp_date and end_date
AND
 is_hide = 0
ORDER BY info_id DESC
EOD;

		$query = $this->db->query($sql, $binds);
		$rs = $query->result_array();
		return $rs;
	}

	/**
	 * 詳細取得
	 */
	public function get_list($search, $sort_key, $sort_type, $limit, $offset)
	{
		$this->load->database();

		$binds = array();

		$where = '';
		if (isset($search['info_id']) && $search['info_id'])
		{
			$where .= ' AND info_id = ? ';
			$binds[] = $search['info_id'];
		}
		if (isset($search['contents_type']) && $search['contents_type'])
		{
			$where .= ' AND contents_type = ? ';
			$binds[] = $search['contents_type'];
		}
		if (isset($search['title']) && $search['title'])
		{
			$where .= ' AND title LIKE ? ';
			$binds[] = '%'.$search['title'].'%';
		}
		if (isset($search['disp_date']) && $search['disp_date'])
		{
			$where .= ' AND `end_date` >= ? ';
			$binds[] = $search['disp_date'];
		}
		if (isset($search['end_date']) && $search['end_date'])
		{
			$where .= ' AND `disp_date` <= ? ';
			$binds[] = $search['end_date'];
		}
		if (isset($search['status']) && $search['status'])
		{
			if ($search['status'] == 2)
			{
				$where .= ' AND CURRENT_DATE() between disp_date and end_date ';
			}

			elseif ($search['status'] == 3)
			{
				$where .= ' AND NOT CURRENT_DATE() between disp_date and end_date ';
			}
		}


		$binds[] = $limit;
		$binds[] = $offset;

		// sort
		$sort_sql = ' ORDER BY info_id DESC ';
		if ($sort_key && $sort_type)
		{
			$sort_sql = ' ORDER BY '.$sort_key.' '.$sort_type.' ';
		}

		$sql = <<<EOD
SELECT  SQL_CALC_FOUND_ROWS
 *,
 now() between disp_date and end_date as status
FROM
 {$this->config->item('table_prefix')}t_information
WHERE
 is_hide = 0
{$where}
{$sort_sql}


LIMIT ? OFFSET ?
EOD;

		$query = $this->db->query($sql, $binds);
		$rs = $query->result_array();

		$query = $this->db->query('SELECT FOUND_ROWS() as cnt');
		$row = $query->row_array();
		$this->allnum = $row['cnt'];

		return $rs;
	}

	/**
	 * 詳細取得
	 */
	public function get_info($info_id)
	{
		$this->load->database();

		$binds = array();
		$binds[] = $info_id;

		$sql = <<<EOD
SELECT
 *
FROM
 {$this->config->item('table_prefix')}t_information
WHERE
 info_id = ?
AND
 is_hide = 0
EOD;

		$query = $this->db->query($sql, $binds);
		$rs = $query->row_array();
		if ($rs)
		{
			$rs['is_not_limit_end'] = 0;
			if ($rs['end_date'] == $this->config->item('datetime_max'))
			{
				$rs['end_date'] = '';
				$rs['is_not_limit_end'] = 1;
			}
		}
		return $rs;
	}

	/**
	 * 登録
	 */
	public function insert($forms)
	{
		$this->load->database();

		$binds = array();
		$binds[] = $forms['info_type'];
		$binds[] = $forms['prog_category_id'];
		if (isset($forms['null_link_url']) && $forms['null_link_url'])
		{
			$binds[] = null;
		}
		else
		{
			$binds[] = $forms['link_url'];
		}
		if (isset($forms['null_is_target']) && $forms['null_is_target'])
		{
			$binds[] = null;
		}
		else
		{
			$binds[] = $forms['is_target'];
		}
		$binds[] = $forms['title'];
		if (isset($forms['null_body']) && $forms['null_body'])
		{
			$binds[] = null;
		}
		else
		{
			$binds[] = $forms['body'];
		}
		$binds[] = $forms['disp_date'];
		$binds[] = $forms['end_date'];
		$binds[] = $forms['contents_type'];

		$sql = <<<EOD
INSERT INTO
 {$this->config->item('table_prefix')}t_information
(
 info_type,
 prog_category_id,
 link_url,
 is_target,
 title,
 body,
 disp_date,
 end_date,
 contents_type,
 regdate,
 upddate
) VALUES (
 ?,
 ?,
 ?,
 ?,
 ?,
 ?,
 ?,
 ?,
 ?,
 now(),
 now()
)
EOD;

		$ret = $this->db->query($sql, $binds);

		if ($ret)
		{
			$query = $this->db->query('SELECT LAST_INSERT_ID() as id');
			$rs = $query->row_array();
			return $rs['id'];
		}

		return $ret;
	}

	/**
	 * 更新
	 */
	public function update($info_id, $forms)
	{
		$this->load->database();

		$binds = array();
		$set_sql = '';
		if (isset($forms['info_type']))
		{
			$binds[] = $forms['info_type'];
			$set_sql .= ',info_type = ? ';
		}
		if (isset($forms['prog_category_id']))
		{
			$binds[] = $forms['prog_category_id'];
			$set_sql .= ',prog_category_id = ? ';
		}
		if (isset($forms['title']))
		{
			$binds[] = $forms['title'];
			$set_sql .= ',title = ? ';
		}
		if (isset($forms['body']))
		{
			$binds[] = $forms['body'];
			$set_sql .= ',body = ? ';
		}
		else if (isset($forms['null_body']) && $forms['null_body'])
		{
			$binds[] = null;
			$set_sql .= ',body = ? ';
		}
		if (isset($forms['link_url']))
		{
			$binds[] = $forms['link_url'];
			$set_sql .= ',link_url = ? ';
		}
		else if (isset($forms['null_link_url']) && $forms['null_link_url'])
		{
			$binds[] = null;
			$set_sql .= ',link_url = ? ';
		}
		if (isset($forms['is_target']))
		{
			$binds[] = $forms['is_target'];
			$set_sql .= ',is_target = ? ';
		}
		else if (isset($forms['null_is_target']) && $forms['null_is_target'])
		{
			$binds[] = null;
			$set_sql .= ',is_target = ? ';
		}
		if (isset($forms['disp_date']))
		{
			$binds[] = $forms['disp_date'];
			$set_sql .= ',disp_date = ? ';
		}
		if (isset($forms['end_date']))
		{
			$binds[] = $forms['end_date'];
			$set_sql .= ',end_date = ? ';
		}
		if (isset($forms['is_hide']))
		{
			$binds[] = $forms['is_hide'];
			$set_sql .= ',is_hide = ? ';
		}
		if (isset($forms['contents_type']))
		{
			$binds[] = $forms['contents_type'];
			$set_sql .= ',contents_type = ? ';
		}
		if (isset($forms['content_ext']))
		{
			$binds[] = $forms['content_ext'];
			$set_sql .= ',content_ext = ? ';
		}
		if (isset($forms['content_filesize']))
		{
			$binds[] = $forms['content_filesize'];
			$set_sql .= ',content_filesize = ? ';
		}

		$binds[] = $info_id;

		$sql = <<<EOD
UPDATE
 {$this->config->item('table_prefix')}t_information
SET
upddate = now()
{$set_sql}
WHERE
 info_id = ?
EOD;
		$ret = $this->db->query($sql, $binds);

		return $ret;
	}

	/**
	 * 無効
	 */
	public function delete($info_id)
	{
		$forms = array();
		$forms['is_hide'] = 1;
		return $this->update($info_id, $forms);
	}

	/**
	 * sitemap.xml用全件取得
	 */
	public function get_all_list()
	{
		$this->load->database();

		$binds = array();

		$sql = <<<EOD
SELECT
 info_id
FROM
 {$this->config->item('table_prefix')}t_information
WHERE
 is_hide = 0
ORDER BY info_id DESC
EOD;

		$query = $this->db->query($sql, $binds);
		$rs = $query->result_array();
		return $rs;
	}
}
