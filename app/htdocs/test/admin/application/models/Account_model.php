<?php

class Account_model extends MY_Model {

	/**
	 * ログインチェック
	 */
	public function check_login($login_id, $password)
	{
		$this->load->database();

		$sql = <<<EOD
SELECT
  account_id
 ,login_id
 ,name
 ,is_admin
 ,is_site
FROM
 {$this->config->item('table_prefix')}t_accounts
WHERE
 login_id = ?
AND
 password = ?
AND
 is_hide = 0
EOD;

		$bind = array(
			$login_id,
			$password,
		);
		$query = $this->db->query($sql, $bind);
		$rs = $query->row_array();
		if ($rs)
		{
			$this->session->set_userdata('login', $rs);
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * ログインIDチェック
	 */
	public function check_login_id($login_id, $account_id)
	{
		$this->load->database();

		$sql = <<<EOD
SELECT
 *
FROM
 {$this->config->item('table_prefix')}t_accounts
WHERE
 login_id = ?
AND
 is_hide = 0
AND
 account_id != ?
EOD;

		$bind = array(
			$login_id,
			$account_id,
		);
		$query = $this->db->query($sql, $bind);
		$rs = $query->row_array();
		return $rs;
	}

	/**
	 * アカウント情報取得
	 */
	public function get_account($account_id)
	{
		$this->load->database();

		$sql = <<<EOD
SELECT
  *
FROM
 {$this->config->item('table_prefix')}t_accounts
WHERE
 account_id = ?
AND
 is_hide = 0
EOD;

		$bind = array(
			$account_id,
		);
		$query = $this->db->query($sql, $bind);
		$rs = $query->row_array();
		return $rs;
	}

	/**
	 * アカウント情報取得
	 */
	public function get_account_by_login_id($login_id)
	{
		$this->load->database();

		$sql = <<<EOD
SELECT
  *
FROM
 {$this->config->item('table_prefix')}t_accounts
WHERE
 login_id = ?
AND
 is_hide = 0
EOD;

		$bind = array(
			$login_id,
		);
		$query = $this->db->query($sql, $bind);
		$rs = $query->row_array();
		return $rs;
	}

	/**
	 * アカウント情報取得
	 */
	public function get_list($search, $sort_key, $sort_type, $limit, $offset)
	{
		$where = '';
		$binds = array();
		if (isset($search['account_id']) && $search['account_id'])
		{
			$where .= ' AND account.account_id = ? ';
			$binds[] = $search['account_id'];
		}

		if (isset($search['name']) && $search['name'])
		{
			$where .= ' AND account.name LIKE ? ';
			$binds[] = '%'.$search['name'].'%';
		}

		if (isset($search['is_admin']) && strlen($search['is_admin']))
		{
			$where .= ' AND account.is_admin = ? ';
			$binds[] = $search['is_admin'];
		}

		$binds[] = $limit;
		$binds[] = $offset;

		// sort
		$sort_sql = ' ORDER BY account.account_id DESC ';
		if ($sort_key && $sort_type)
		{
			$sort_sql = ' ORDER BY '.$sort_key.' '.$sort_type.' ';
		}

		$this->load->database();

		$sql = <<<EOD
SELECT  SQL_CALC_FOUND_ROWS
  account.account_id,
  account.name,
  account.login_id,
  account.is_admin,
  account.upddate,
  account2.name as upd_account_name
FROM
 {$this->config->item('table_prefix')}t_accounts account
LEFT JOIN
 {$this->config->item('table_prefix')}t_accounts account2
ON
 account.upd_account_id = account2.account_id
AND
 account2.is_hide = 0
WHERE
 account.is_hide = 0
{$where}
{$sort_sql}
Limit ?
OFFSET ?
EOD;

		$query = $this->db->query($sql, $binds);
		$rs = $query->result_array();

		// 行数取得
		$query = $this->db->query('SELECT FOUND_ROWS() as cnt');
		$row = $query->row_array();
		$this->allnum = $row['cnt'];

		return $rs;
	}

	/**
	 * 更新
	 */
	public function update($id, $forms)
	{
		$this->load->database();

		$binds = array();

		$login = $this->session->userdata('login');
		$binds[] = $login['account_id'];

		$set_sql = '';
		if (isset($forms['name']))
		{
			$binds[] = $forms['name'];
			$set_sql .= ',name = ? ';
		}
		if (isset($forms['login_id']))
		{
			$binds[] = $forms['login_id'];
			$set_sql .= ',login_id = ? ';
		}
		if (isset($forms['password']))
		{
			$binds[] = $forms['password'];
			$set_sql .= ',password = ? ';
		}
		if (isset($forms['is_admin']))
		{
			$binds[] = $forms['is_admin'];
			$set_sql .= ',is_admin = ? ';
		}
		if (isset($forms['is_hide']))
		{
			$binds[] = $forms['is_hide'];
			$set_sql .= ',is_hide = ? ';
		}

		$binds[] = $id;

		$sql = <<<EOD
UPDATE
 {$this->config->item('table_prefix')}t_accounts
SET
 upddate = now(),
 upd_account_id = ?
 {$set_sql}
WHERE
 account_id = ?
EOD;
		$ret = $this->db->query($sql, $binds);
		return $ret;
	}

	/**
	 * 登録
	 */
	public function insert($forms)
	{
		$this->load->database();

		$binds = array();
		$binds[] = $forms['login_id'];
		$binds[] = $forms['name'];
		$binds[] = $forms['password'];
		$binds[] = $forms['is_admin'];
		$login = $this->session->userdata('login');
		$binds[] = $login['account_id'];

		$sql = <<<EOD
INSERT INTO
 {$this->config->item('table_prefix')}t_accounts
(
 login_id,
 name,
 password,
 is_admin,
 is_hide,
 regdate,
 upddate,
 upd_account_id
) VALUES (
 ?,
 ?,
 ?,
 ?,
 0,
 now(),
 now(),
 ?
)

EOD;

		$ret = $this->db->query($sql, $binds);
		return $ret;
	}

	/**
	 * 削除
	 */
	public function delete($id)
	{
		$forms = array();
		$forms['is_hide'] = 1;
		return $this->update($id, $forms);
	}

}
