<?php
class MY_Form_validation extends  CI_Form_validation {

	/**
	 * Run the Validator
	 *
	 * This function does all the work.
	 *
	 * @access	public
	 * @return	bool
	 */
	public function run($group = '', $force = FALSE)
	{
		// 強制的に空値をセット
/*
		if ($this->_config_rules)
		{
			foreach ($this->_config_rules as $key => $val)
			{
				if ( ! isset($_POST[$val['field']]))
				{
					$_POST[$val['field']] = '';
					$this->CI->form[$val['field']] = '';
				}
			}
		}
*/
	
		// Do we even have any data to process?  Mm?
		if (count($_POST) == 0)
		{
			if ($force)
			{
				return FALSE;
			}
		}

		// Does the _field_data array containing the validation rules exist?
		// If not, we look to see if they were assigned via a config file
		if (count($this->_field_data) == 0)
		{
			// No validation rules?  We're done...
			if (count($this->_config_rules) == 0)
			{
				return FALSE;
			}

			// Is there a validation rule for the particular URI being accessed?
			$uri = ($group == '') ? trim($this->CI->uri->ruri_string(), '/') : $group;

			if ($uri != '' AND isset($this->_config_rules[$uri]))
			{
				$this->set_rules($this->_config_rules[$uri]);
			}
			else
			{
				$this->set_rules($this->_config_rules);
			}

			// We're we able to set the rules correctly?
			if (count($this->_field_data) == 0)
			{
				log_message('debug', "Unable to find validation rules");
				return FALSE;
			}
		}

		// Load the language file containing error messages
		$this->CI->lang->load('form_validation');

		// Cycle through the rules for each field, match the
		// corresponding $_POST item and test for errors
		foreach ($this->_field_data as $field => $row)
		{
			// Fetch the data from the corresponding $_POST array and cache it in the _field_data array.
			// Depending on whether the field name is an array or a string will determine where we get it from.

			if ($row['is_array'] == TRUE)
			{
				$this->_field_data[$field]['postdata'] = $this->_reduce_array($_POST, $row['keys']);
			}
			else
			{
				if (isset($_POST[$field]) AND $_POST[$field] != "")
				{
					$this->_field_data[$field]['postdata'] = $_POST[$field];
				}
			}

			$this->_execute($row, explode('|', $row['rules']), $this->_field_data[$field]['postdata']);
		}

		// Did we end up with any errors?
		$total_errors = count($this->_error_array);

		if ($total_errors > 0)
		{
			$this->_safe_form_data = TRUE;
		}

		// Now we need to re-set the POST data with the new, processed data
		$this->_reset_post_array();

		// No errors, validation passes!
		if ($total_errors == 0)
		{
			return TRUE;
		}

		// Validation fails
		return FALSE;
	}

	// エラー時に、ポスト値をエスケープしてしまうので、エスケープしないようにする
	
	public function prep_for_form($data = '')
	{
		return $data;
	}
	
	/**
	 * Performs a Regular Expression match test.
	 *
	 * @access	public
	 * @param	string
	 * @param	regex
	 * @return	bool
	 */
	public function mb_regex_match($str, $regex)
	{
		if ( ! mb_ereg($regex, $str))
		{
			return FALSE;
		}

		return  TRUE;

	}
	
	/**
	 * Max Date
	 *
	 * @access	public
	 * @param	string
	 * @param	value
	 * @return	bool
	 */
	public function max_date($str, $val)
	{
		$t_max = strtotime($val);
		$t_var = strtotime($str);
		if ($t_var > $t_max) {
			return FALSE;
		}
		
		return  TRUE;
	}

	/**
	 * Min Date
	 *
	 * @access	public
	 * @param	string
	 * @param	value
	 * @return	bool
	 */
	public function min_date($str, $val)
	{
		$t_min = strtotime($val);
		$t_var = strtotime($str);
		if ($t_var < $t_min) {
			return FALSE;
		}
		
		return  TRUE;
	}
	/**
	 * Add Error Msg
	 *
	 * @access	public
	 * @param	string
	 * @param	value
	 * @return	bool
	 */
	public function add_error_msg($field, $msg, $label='')
	{
		if (!isset($this->_field_data[$field])) {
			$this->_field_data[$field] = array();
		}
		
		if (strlen($label) > 0) {
			$this->_field_data[$field]['label'] = $label;
		}
		$this->_field_data[$field]['error'] = $msg;
		$this->_field_data[$field]['rules'] = '';

		if (strpos($field, '[') !== FALSE AND preg_match_all('/\[(.*?)\]/', $field, $matches)) {
			$x = explode('[', $field);
			$indexes[] = current($x);

			for ($i = 0; $i < count($matches['0']); $i++) {
				if ($matches['1'][$i] != '')
				{
					$indexes[] = $matches['1'][$i];
				}
			}

			$is_array = TRUE;
		} else {
			$indexes	= array();
			$is_array	= FALSE;
		}

		
		$this->_field_data[$field]['is_array'] = $is_array;
		$this->_field_data[$field]['keys'] = $indexes;
		$this->_field_data[$field]['postdata'] = NULL;
		$this->_error_array[$field] = $msg;
	}
	
	/**
	 * Get Field Data
	 *
	 * @access	public
	 * @param	string
	 * @param	value
	 * @return	bool
	 */
	public function get_field_data()
	{
		return $this->_field_data;
	}

	/**
	 * not hankana
	 *
	 * @access	public
	 * @param	string
	 * @param	value
	 * @return	bool
	 */
	public function not_hankana($str)
	{
		mb_regex_encoding("UTF-8");
		if (mb_ereg("[ｦ-ﾟ]", $str)) 
		{
			return FALSE;
		}
		return  TRUE;
	}

	/**
	 * check_image
	 * 画像のエラーチェック
	 *  [オプション] 必須チェック
	 *  php.iniに設定されている画像サイズを超えていないか
	 *  ファイルタイプがGIF、JPEG、PNGか
	 *  [オプション] 指定サイズ以上か
	 * ファイル名にドットを含んでいないか
	 *
	 * @access	public
	 * @param	string
	 * @param	value
	 * @return	bool
	 */
	public function check_image($key, $label, $options=array(), $messages=array())
	{
		// チェック内容定義
		$required   = isset($options['required'])   ? $options['required']   : false;
		$min_width  = isset($options['min_width'])  ? $options['min_width']  : 0;
		$min_height = isset($options['min_height']) ? $options['min_height'] : 0;
		$max_width  = isset($options['max_width'])  ? $options['max_width']  : 4294967296;
		$max_height = isset($options['max_height']) ? $options['max_height'] : 4294967296;

		$file = isset($_FILES[$key]) ? $_FILES[$key] : array();

		$file_error = isset($file['error']) ? $file['error'] : "";
		
		// アップロードされていない場合
		if (($file_error == UPLOAD_ERR_NO_FILE) OR ! $file)
		{
			if ($required)
			{
				// 必須
				if (isset($messages['required']))
				{
					$this->add_error_msg($key, $messages['required']);
				}
				else
				{
					$this->add_error_msg($key, "{$label}を選択してください。");
				}
				return FALSE;
			} else
			{
				// 必須でない
				return TRUE;
			}
		}

		// サイズオーバーチェック
		if ($file_error != UPLOAD_ERR_OK) {
			if ($file_error == UPLOAD_ERR_INI_SIZE || $file_error == UPLOAD_ERR_FORM_SIZE)
			{
				$this->add_error_msg($key, "{$label}のファイルサイズが大きい為、アップロードに失敗しました。");
				return FALSE;
			} else
			{
				$this->add_error_msg($key, "{$label}のアップロードに失敗しました。");
				return FALSE;
			}
		}

		// ファイル名にドット
		$ex_arr = explode("." , $_FILES[$key]['name']);
		if( count($ex_arr) > 2 )
		{
			$this->add_error_msg($key, "ファイル名にドット(.)が付いているファイルはアップロードできません。");
			return FALSE;
		}

		// 形式チェック
		$image = getimagesize($_FILES[$key]['tmp_name']);
		if (($image[2] != IMAGETYPE_GIF) && ($image[2] != IMAGETYPE_JPEG) && ($image[2] != IMAGETYPE_PNG) )
		{
			$this->add_error_msg($key, "{$label}に指定可能なファイル形式は、GIF、JPEG、PNGです。");
			return FALSE;
		}

		// 最小サイズ
		if ($image[0] < $min_width || $image[1] < $min_height)
		{
			$this->add_error_msg($key, "{$label}の幅もしくは高さが不正です。");
			return FALSE;
		}

		// 最大サイズ
		if ($image[0] > $max_width || $image[1] > $max_height)
		{
			$this->add_error_msg($key, "{$label}の幅もしくは高さが不正です。");
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Max Value
	 *
	 * @access	public
	 * @param	string
	 * @param	value
	 * @return	bool
	 */
	public function max_value($str, $val)
	{
		if ($val < $str) {
			return FALSE;
		}

		return  TRUE;
	}

	/**
	 * Min Value
	 *
	 * @access	public
	 * @param	string
	 * @param	value
	 * @return	bool
	 */
	public function min_value($str, $val)
	{
		if ($str < $val) {
			return FALSE;
		}
		return  TRUE;
	}
}
/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/MY_Form_validation.php */
?>