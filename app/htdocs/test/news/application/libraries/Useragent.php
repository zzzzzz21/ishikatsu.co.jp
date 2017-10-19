<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 *  ユーザエージェント操作クラス
 *
 *  @author	 nakanishi
 */
define('USER_AGENT_DOCOMO',   1);
define('USER_AGENT_AU',	   2);
define('USER_AGENT_SOFTBANK', 3);
define('USER_AGENT_IPHONE',   5);
define('USER_AGENT_ANDROID',  6);
define('USER_AGENT_IPAD',	 11);
define('USER_AGENT_ANDROID_TABLET',  12);
define('USER_AGENT_PC',	   99);

class Useragent
{
	// エージェント種別 (USER_AGENT_xxxx)
	private $agentType = '';

	/**
	 * コンストラクタ
	 */
	public function __construct() {

		// エージェント種別判定
		if (preg_match('/^Docomo/i', $this->getAgent())) {
			$this->agentType = USER_AGENT_DOCOMO;

		} elseif (preg_match('/^(J-PHONE|Vodafone|SoftBank)/i', $this->getAgent())) {
			$this->agentType = USER_AGENT_SOFTBANK;

		} elseif (preg_match('/^(UP\.Browser|KDDI)/i', $this->getAgent())) {
			$this->agentType = USER_AGENT_AU;


		} elseif (preg_match('/(iPhone|iPod)/i', $this->getAgent())) {
			$this->agentType = USER_AGENT_IPHONE;
		} elseif (preg_match('/(iPad)/i', $this->getAgent())) {
			$this->agentType = USER_AGENT_IPAD;


		} elseif (preg_match('/(Android.*Mobile)/i', $this->getAgent())) {
			$this->agentType = USER_AGENT_ANDROID;
		} elseif (preg_match('/(Android)/i', $this->getAgent())) {
			$this->agentType = USER_AGENT_ANDROID_TABLET;

		} else {
			$this->agentType = USER_AGENT_PC;
		}
	}

	/**
	 * ユーザエージェント文字列を返す
	 *
	 * @return	string
	 */
	public function getAgent() {

		return $_SERVER['HTTP_USER_AGENT'];
	}

	/**
	 * モバイル端末かどうかを返す
	 *
	 * @return	bool
	 */
	public function isMobile() {
		if ($this->agentType == USER_AGENT_DOCOMO || $this->agentType == USER_AGENT_AU || $this->agentType == USER_AGENT_SOFTBANK) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * スマートフォン端末かどうかを返す
	 *
	 * @return	bool
	 */
	public function isSmartphone() {
		if ($this->agentType == USER_AGENT_IPHONE || $this->agentType == USER_AGENT_ANDROID) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * アイフォンかどうかを返す
	 *
	 * @return	bool
	 */
	public function is_iphone() {
		if ($this->agentType == USER_AGENT_IPHONE) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * androidかどうかを返す
	 *
	 * @return	bool
	 */
	public function is_android() {
		if ($this->agentType == USER_AGENT_ANDROID) {
			return true;
		} else {
			return false;
		}
	}

}
/* End of file Useragent.php */
/* Location: ./application/libraries/Useragent.php */
?>