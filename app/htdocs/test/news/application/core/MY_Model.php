<?php

/**
 * 共通model
 */
class MY_Model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	// プレビューデータ
	var $preview_post_data;
	
	// プレビュータイプ
	var $preview_manage_type;

	// プレビュー詳細データ上書き項目
	var $preview_post_data_format_item;

	// プレビュー一覧データ上書き設定
	var $preview_list_setting;

	/*
	 * プレビュー用データを取得
	 */
	protected function get_preview_data($preview_id)
	{
		$this->load->model('Preview_model');
		$data = $this->Preview_model->get_preview_data($preview_id);
		if ( ! $data)
		{
			return FALSE;
		}

		if ($data['manage_type'] != $this->preview_manage_type)
		{
			return FALSE;
		}

		$rtn = $data;
		if ($this->preview_post_data_format_item)
		{
			$rtn = array();
			foreach ($this->preview_post_data_format_item as $data_item => $item)
			{
				if (isset($data['post_data'][$data_item]))
				{
					$rtn[$item] = $data['post_data'][$data_item];
				}
				else
				{
					$rtn[$item] = null;
				}
			}
		}
		
		return $rtn;
	}

	/*
	 * プレビュー用データで上書き
	 */
	public function set_preview_data($org, $data=array())
	{
		// データが引数に渡されなければ、すでに取得済みのデータを再利用
		if ( ! $data)
		{
			$data = $this->preview_post_data;
		}

		// 対象プレビューデータがない場合は、処理しない
		if ( ! $data)
		{
			return $org;
		}

		// 対象プレビューデータ上書き項目設定がない場合は、処理しない
		if ( ! $this->preview_post_data_format_item)
		{
			return $org;
		}
		
		if ($this->preview_post_data_format_item)
		{
			foreach ($this->preview_post_data_format_item as $data_item => $item)
			{
				$org[$item] = $data[$item];
			}
		}
		
		return $org;
	}
	
	/**
	 * preview判定
	 */
	function is_preview()
	{
		// プレビュー用の処理
		$preview_id = $this->input->get('admin_preview_id');
		if ($preview_id)
		{
			// データも確認※プレビューデータがない時は、プレビューと見なさない
			$this->load->model('Preview_model');
			$data = $this->Preview_model->get_preview_data($preview_id);
			if ($data)
			{
				return $preview_id;
			}
		}
		return FALSE;
	}

	/*
	 * 一覧データへプレビュー用データ挿入
	 * 
	 *  ※ 一覧の先頭挿入。並び順指定で挿入などに使える。
	 *  ※ ページングには対応していません
	 */
	public function set_preview_data_into_list($list, $data=array())
	{
		if ( ! $data)
		{
			$data = $this->preview_post_data;
		}

		// データがなければ処理しない
		if ( ! $data)
		{
			return $list;
		}

		$org_id_item = '';
		$new_id_item = '';
		$order_item = '';
		if (isset($this->preview_list_setting['org_id_item']) && $this->preview_list_setting['org_id_item'])
		{
			$org_id_item = $this->preview_list_setting['org_id_item'];
		}
		if (isset($this->preview_list_setting['new_id_item']) && $this->preview_list_setting['new_id_item'])
		{
			$new_id_item = $this->preview_list_setting['new_id_item'];
		}
		if (isset($this->preview_list_setting['order_item']) && $this->preview_list_setting['order_item'])
		{
			$order_item = $this->preview_list_setting['order_item'];
		}

		// 編集時は、一旦元データをunset
		// ※設定があるときだけ処理する
		if ($org_id_item && $new_id_item)
		{
			if ($data[$org_id_item])
			{
				foreach ($list as $key => $val)
				{
					if ($val[$new_id_item] == $data[$org_id_item])
					{
						// 並び順設定がある場合は、unset
						if ($order_item)
						{
							unset($list[$key]);
						}
						// そうでなければ、書き変えて終了
						else
						{
							$list[$key] = $data;
							return $list;
						}
					}
				}
			}
		}
		
		// 並び順設定がない場合は、先頭に追加して返す
		if ( ! $order_item)
		{
			$rtn = array();
			$rtn[] = $data;
			foreach ($list as $key => $val)
			{
				$rtn[] = $val;
			}
			return $rtn;
		}

		$order1_item = '';
		$order2_item = '';
		$order1_type = '';
		$order2_type = '';
		if (isset($order_item[1]) && isset($order_item[1]['item']))
		{
			$order1_item = $order_item[1]['item'];
		}
		if (isset($order_item[2]) && isset($order_item[2]['item']))
		{
			$order2_item = $order_item[2]['item'];
		}
		if (isset($order_item[1]) && isset($order_item[1]['type']))
		{
			$order1_type = $order_item[1]['type'];
		}
		if (isset($order_item[2]) && isset($order_item[2]['type']))
		{
			$order2_type = $order_item[2]['type'];
		}

		// 設定がある場合は、並び順設定道理に設定をして返す
		$rtn = array();
		if ($list)
		{
			$is_hit = FALSE;
			foreach ($list as $key => $val)
			{
				if ( ! $is_hit)
				{
					// 第一並び順
					if ($order1_type == 'desc')
					{
						$hikaku1 = $val[$order1_item] < $data[$order1_item];
					}
					else
					{
						$hikaku1 = $val[$order1_item] > $data[$order1_item];
					}
					if ($hikaku1)
					{
						$rtn[] = $data;
						$is_hit = TRUE;
					}
					// 第二並び順※設定がる場合のみ
					else if ($order2_item && ($val[$order1_item] == $data[$order1_item]))
					{
						if ($order2_type == 'desc')
						{
							$hikaku2 = $val[$order2_item] < $data[$order2_item];
						}
						else
						{
							$hikaku2 = $val[$order2_item] > $data[$order2_item];
						}
						if ($hikaku2)
						{
							$rtn[] = $data;
							$is_hit = TRUE;
						}
					}
				}

				$rtn[] = $val;
			}
		}
		if ( ! $is_hit)
		{
			$rtn[] = $data;
		}

		return $rtn;
	}
}

?>