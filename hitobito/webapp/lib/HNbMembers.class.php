<?php
/**
 * ユーザ管理クラス
 * 
 * @package HNb
 * @author Ryuji
 * @@version $Id: HNbMembers.class.php,v 1.2 2006/01/24 19:11:03 ryu Exp $
 */
 
abstract class HNbMembers
{
	protected $db;
	
	public function __construct($db)
	{
		$this->db = $db;
	}
	/**
	 * 
	 * @param	string	メールアドレス
	 * @param	string	パスワード
	 * 
	 * @return	int	ユーザID（ログイン失敗なら0）
	 */
	abstract function checkLogin($mail, $pass);
	
	/**
	 * ユーザ属性を得る
	 * 
	 * @param	int	ユーザID
	 * 
	 * @return	array	ユーザ属性
	 */
	 abstract function get($user_id);
	 
	/**
	 * メールアドレスが登録済みかチェックする
	 * @param	string email
	 * @return bool
	 */
	 abstract function inMemberByEmail($email);
 }
?>