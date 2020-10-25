<?php
/**
 * 
 * @package	hitobito2
 * @author Ryuji
 * @version $Id: hitobitoMembers.class.php,v 1.2 2006/01/24 19:11:03 ryu Exp $
 */
 
/**
 * hitobito �Υ��С���Ϣ���饹
 */
 
 class hitobitoMembers extends HNbMembers
 {
	 // ����Ͽ�ѥơ��֥�
	 private $preMemberTable = 'pre_confirm_member';
	 // ����Ͽ�ѥơ��֥�Υץ饤�ޥꥭ��
	 private $preMemberTablePkey = 'pre_confirm_member_id';

	 private $memberTable = 't_member';
	 private $memberTablePkey = 'mbr_member_id';
	
	public function __construct($db)
	{
		parent::__construct($db);
	}
	/**
	 * ������Ǥ��뤫�����å�
	 * 
	 * @param	string	�᡼�륢�ɥ쥹
	 * @param	string	�ѥ����
	 * 
	 * @return	int	�桼��ID�ʥ������Ԥʤ�0��
	 */
	public function checkLogin($mail, $pass)
	{
		$md5pass = md5($pass);
		$lowmail = strtolower($mail);
		$sql = sprintf("SELECT %s FROM %s WHERE mbr_mail_addr_low = %s AND mbr_password=%s",
				$this->memberTablePkey,
				$this->memberTable,
				$this->db->qstr($lowmail),
				$this->db->qstr($md5pass ));
		$id = $this->db->GetOne($sql);
		return $id;
	}
	
	
	public function getMemberAttributesById($id)
	{
		$sql = sprintf("SELECT * FROM %s WHERE %s=%d",
				$this->memberTable,
				$this->memberTablePkey,
				$id);
		$rs = $this->db->GetRow($sql);
		return $rs;
		
	}
	/**
	 * TODO �桼��°��������
	 * 
	 * @param	int	�桼��ID
	 * 
	 * @return	array	�桼��°��
	 */
	 public function get($user_id)
	 {
		 
	 }
	 
	/**
	 * �᡼�륢�ɥ쥹����Ͽ�Ѥߤ������å�����
	 * 
	 * 
	 * @param	string email
	 * @return bool
	 */
	 public function inMemberByEmail($email)
	 {
		 $sql = 'SELECT count(*) FROM t_member WHERE mbr_mail_addr_low = '.strtolower($email);
		 $num = $this->db->GetOne($sql);
		 return ($num > 0) ? TRUE : FALSE;
		 
	 }
	 
	
	/**
	 * �����ƥ��١��ȥ����å�
	 */
	public function checkActivate($id, $actkey)
	{
		$sql = sprintf("SELECT * FROM %s WHERE pre_confirm_member_id=%d AND actkey=%s", 
			$this->preMemberTable,
			$id,
			$this->db->qstr($actkey));
		$rs = $this->db->GetRow($sql);
		if($rs[$this->preMemberTablePkey] == $id){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	/**
	 * ����ǧ�ںѤߥ桼���ѥ�᡼������
	 */
	public function getConfirmedMember($id)
	{
		$sql = sprintf("SELECT * FROM %s WHERE %s=%d", 
			$this->preMemberTable, 
			$this->preMemberTablePkey,
			$id
			);
		$rs = $this->db->GetRow($sql);
		return $rs;
	}
	
	/**
	 * �������С���Ͽ
	 * @param	array	Ϣ������ mail, password, hitobito_letter, status
	 * @return id
	 */
	public function registMember($vars)
	{
		$registVars = array(
			'mbr_status' => $vars['status'],
			'mbr_regist_date' => date("Y-m-d H:i:s"),
			'mbr_mail_addr' => $vars['mail'],
			'mbr_mail_addr_low' => strtolower($vars['mail']),
			'mbr_password' => $vars['password'],
			'mbr_hitobito_letter' => $vars['hitobito_letter'],
		);
		$this->db->AutoExecute('t_member', $registVars, 'INSERT');
		$oid = $this->db->Insert_ID();
		$id = $this->db->GetOne("SELECT mbr_member_id FROM t_member WHERE oid=".$oid);
		return $id;
	}
	
 }
?>