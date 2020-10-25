<?php
/**
 * �桼���������饹
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
	 * @param	string	�᡼�륢�ɥ쥹
	 * @param	string	�ѥ����
	 * 
	 * @return	int	�桼��ID�ʥ������Ԥʤ�0��
	 */
	abstract function checkLogin($mail, $pass);
	
	/**
	 * �桼��°��������
	 * 
	 * @param	int	�桼��ID
	 * 
	 * @return	array	�桼��°��
	 */
	 abstract function get($user_id);
	 
	/**
	 * �᡼�륢�ɥ쥹����Ͽ�Ѥߤ������å�����
	 * @param	string email
	 * @return bool
	 */
	 abstract function inMemberByEmail($email);
 }
?>