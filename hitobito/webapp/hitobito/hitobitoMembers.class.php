<?php
/**
 * 
 * @package	hitobito2
 * @author Ryuji
 * @version $Id: hitobitoMembers.class.php,v 1.2 2006/01/24 19:11:03 ryu Exp $
 */
 
/**
 * hitobito のメンバー関連クラス
 */
 
 class hitobitoMembers extends HNbMembers
 {
	 // 仮登録用テーブル
	 private $preMemberTable = 'pre_confirm_member';
	 // 仮登録用テーブルのプライマリキー
	 private $preMemberTablePkey = 'pre_confirm_member_id';

	 private $memberTable = 't_member';
	 private $memberTablePkey = 'mbr_member_id';
	
	public function __construct($db)
	{
		parent::__construct($db);
	}
	/**
	 * ログインできるかチェック
	 * 
	 * @param	string	メールアドレス
	 * @param	string	パスワード
	 * 
	 * @return	int	ユーザID（ログイン失敗なら0）
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
	 * TODO ユーザ属性を得る
	 * 
	 * @param	int	ユーザID
	 * 
	 * @return	array	ユーザ属性
	 */
	 public function get($user_id)
	 {
		 
	 }
	 
	/**
	 * メールアドレスが登録済みかチェックする
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
	 * アクティベートチェック
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
	 * 自己認証済みユーザパラメータ取得
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
	 * 新規メンバー登録
	 * @param	array	連想配列 mail, password, hitobito_letter, status
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