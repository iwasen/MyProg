<?php
/**
 * hitobito���С�����
 * 
 * @package	hitobito
 * @author Ryuji
 * @version $Id: hitobitoMemberManager.class.php,v 1.20 2006/03/09 08:21:57 ryu Exp $
 */
 require_once MO_WEBAPP_DIR .'/hitobito/hitobitoMember.class.php';
 require_once MO_WEBAPP_DIR .'/hitobito/hitobitoNavipage.class.php';

class hitobitoMemberManager
{
	const
		STATUS_PRE_CONFIRM=0, // ����Ͽ
		STATUS_ACTIVE	= 1, // ͭ��
		STATUS_NOT_ALIVE= 2, // ��ã
		STATUS_STOP		= 3, // ������
		STATUS_RESIGN	= 9;  // ���
		
		
	 // ����Ͽ�ѥơ��֥�
	 private $preMemberTable = 'pre_confirm_member';
	 // ����Ͽ�ѥơ��֥�ץ饤�ޥꥭ��
	 private $preMemberTablePkey = 'pre_confirm_member_id';

	 private $memberTable = 't_member';
	 private $memberTablePkey = 'mbr_member_id';
	 
	 private $db;
	
	public function __construct($db = null)
	{
		$this->db = HNb::getAdodb();
	}
	
	/**
	 * Debug�Ѥ˥ơ��֥�������
	 */
	 public function getTableInfo()
	 {
	 	$ret = array();
	 	$ret['pkey'] = $this->preMemberTablePkey;
	 	$ret['table'] = $this->preMemberTable;
	 	return $ret;
	 }
	
	/**
	 * ���������
	 * 
	 * @param	object BasicSecurityUser
	 * @param	string	�᡼�륢�ɥ쥹
	 * @param	string	�ѥ����
	 * 
	 * @return	object	hitobitoMember�ʥ������Ԥʤ�FALSE��
	 */
	public function login($user, $email, $password, $md5 = true)
	{
		if ($md5)
			$password = md5($password);
		$lowmail = strtolower($email);

		// ��񤷤Ƥ�������󤵤��ʤ�. �����ƥ��١������ޤ����ä�������󤵤��ʤ�
		$where = sprintf("mbr_status !=" . self::STATUS_RESIGN . " AND mbr_status != ". self::STATUS_PRE_CONFIRM ." AND mbr_mail_addr_low = %s AND mbr_password=%s", 
				$this->db->qstr($lowmail), $this->db->qstr($password));
		
		$dbHandler = HNb::getDBHandler('t_member');
		$num = $dbHandler->getCount($where);
		if($num == 0){
			return FALSE;
		}
		
		// login OK!
		$t_member = $dbHandler->getOne($where);
		$member = new hitobitoMember();
		$member->setAttributes($t_member->getAttributes());
		$user->setAttribute('member_type', 'regist_member');


		$user->setAttribute('id', $member->getId(), 'Member');
		$user->setAuthenticated(TRUE);

		
		// get navi prof
		$sql = sprintf('SELECT * FROM t_navi WHERE nav_member_id=%d', $member->getAttribute('mbr_member_id'));
		if($naviVars = $this->db->GetRow($sql)){
		    foreach($naviVars as $key=>$var){
		        $member->setAttribute($key, $var);
		    }
		    $user->setAttribute('member_type', 'navi');
		    
		    // ô���ʥӥڡ���������Ϥ�
//        	$navipageManager = new hitobitoNavipageManager(); 
//        	$naviPages = $navipageManager->getNaviPagesByMemberId($user->getAttribute('mbr_member_id'));
//        	$user->setAttribute('my_navipages', $naviPages, 'Navi');
		    
		}
		
		
		$user->setAttributes($member->getAttributes());
		
		
		
		return $member;
	}
	
	/**
	 * TODO �������Ƚ���
	 * 
	 * @param	object	hitobitoMember
	 */
	public function logout($member)
	{
		
	}
	
	
	/** 
	 * �᡼�륢�ɥ쥹��¸�ߤ��뤫�����å�
	 * ��񤷤����ɥ쥹�ϥ����å����ʤ�
	 */
	public function inMemberByEmail($email, $ignore_member_id = null)
	{
		$lowmail = strtolower($email);
		$where = "mbr_mail_addr_low=".$this->db->qstr($lowmail) .' AND mbr_status != 9 ';
		if(isset($ignore_member_id)){
			$where .= " AND mbr_member_id !=". $ignore_member_id;
		}

		$dbHandler = HNb::getDBHandler('t_member');
		$num = $dbHandler->getCount($where);
		
		return ($num > 0) ?  TRUE : FALSE;
	}

	/**
	 * ��񡢲���Ͽ�ʳ��Υ��ơ������Υ�������Ȥǥ᡼�륢�ɥ쥹���Ȥ��Ƥ��뤫�����å�����
	 * 
	 */
	public function isEmailUsedInAliveMemberAccount($email)
	{
		$lowmail = strtolower($email);
		$where = "mbr_mail_addr_low=".$this->db->qstr($lowmail) .' AND mbr_status !='.self::STATUS_RESIGN.' AND mbr_status !='.self::STATUS_PRE_CONFIRM.'';

		$dbHandler = HNb::getDBHandler('t_member');
		$num = $dbHandler->getCount($where);
		
		return ($num > 0) ?  TRUE : FALSE;
	}
	
	/**
	 * @param	integer	���С�ID
	 */	
	public function get($member_id)
	{
		$dbHandler = HNb::getDBHandler('t_member');
		
		$t_member = $dbHandler->get($member_id);
		
		$naviHandler = HNb::getDBHandler('t_navi');
		$naviInfo = $naviHandler->getOne('nav_member_id='.$member_id);
		if($naviInfo->getId() > 0){
			// navi
			$member = new hitobitoNavi();
			$member->setNaviInfo($naviInfo);
		}else{
			// member
			$member = new hitobitoMember();
		}
		$member->setAttributes($t_member->getAttributes());
		return $member;
	}
	
	public function create()
	{
		return  new hitobitoMember();
	}
	
	public function getByEmail($email)
	{
		$dbHandler = HNb::getDBHandler('t_member');
		$lowmail = strtolower($email);
		$t_member = $dbHandler->getOne('mbr_mail_addr_low='.$this->db->qstr($lowmail));
		$member = new hitobitoMember();
		$member->setAttributes($t_member->getAttributes());
		return $member;
	}		
	
	public function getNavi($navi_id)
	{
		$member_id = $this->db->GetOne('SELECT nav_member_id FROM t_navi WHERE nav_navi_id='.$navi_id);
		return $this->get($member_id);
	}
	
	/**
	 * ��¸
	 */
	 public function insert($member)
	 {
		$dbHandler = HNb::getDBHandler('t_member');
		$t_member = $dbHandler->create();
		$t_member->setAttributes($member->getAttributes());
		$t_member->setAttribute('mbr_update_date', time());
		$result = $dbHandler->insert($t_member);
		$member->setAttributes($t_member->getAttributes());
		return $result; 
	 }
	 
	 
	 // ���ε�Ͽ
	 public function resignMember($user, $resign_id)
	 {
	 	$resignMember = $this->get($user->getAttribute('mbr_member_id'));
	 	$resignMember->resign($resign_id);
	 	$this->insert($resignMember);
	 		 	
		 // logout ����
		$user->clearCredentials();
		$user->clearAttributes();
        $user->setAuthenticated(FALSE);

		 
	 }
	 
	 
	 /**
	  * ���С�����Ͽ
	  */
	 public function insertPreConfirmMemberObject($member)
	 {
		 $handler = HNb::getDBHandler('t_member');
		 //����Ͽ�Ԥκ���Ͽ�ʤ�����β���Ͽ�ǡ���������������Ԥκ���Ͽ���ϡ������Υ�������ȥǡ����ϻĤ����ޤޡ�������������Ȥˤ����
	 	// ���Ԥ�����Ͽ�Ԥκ���Ͽ���ä���ơ��֥�򹹿�����
	 	$where = 'mbr_mail_addr_low='.$this->db->qstr(strtolower($member->getEmail())) . ' AND ( mbr_status = '.self::STATUS_PRE_CONFIRM .')';
	 	$handler->deletes($where);
	 	$Insertmember = $handler->create();
	 	$Insertmember->setAttributes($member->getAttributes());
	 	$Insertmember->setAttribute('mbr_regist_date', time());
	 	$Insertmember->setAttribute('mbr_actkey', $this->makeActkey($member->getEmail()));
	 	$Insertmember->setAttribute('mbr_mail_addr_low', strtolower($member->getEmail()));
	 	$Insertmember->setAttribute('mbr_status', 0); // ����Ͽ���ϥ��ơ�����0
	 	if(!$handler->insert($Insertmember)){
	 		return FALSE;
	 	}
	 	$member->setAttribute('mbr_member_id', $Insertmember->getId());
		return $Insertmember->getId();	 	
	 }
	 
	 private function makeActkey($str)
	{
		srand(microtime()*100000);
        $actkey = md5($str.uniqid(rand(),true));
 
		return $actkey;
	}
	 
	 /**
	  * ����Ͽ��ǧ�᡼������
	  * 
	  * @param	int	pre_confirm_member �Υץ饤�ޥꥭ��
	  */
	  // �᡼��ƥ�ץ졼�Ȥ���᡼��ΤҤʷ������
	public function sendRegixxxonfirmMail($pre_confirm_member_id)
	{
		$handler = HNb::getDBHandler('t_member');
		$member = $handler->get($pre_confirm_member_id);
		
		$siteUrl = hitobito::getUrl();
		
		
		$mailer = hitobito::getMailer();
		$mailer->loadTemplate('regist_confirm');
		
		$actkey = $member->getAttribute('mbr_actkey');
		//ACTKEY��navipage_id�򺮤���ʥ����ƥ��١��ȸ�˳�����ļ��إ�����쥯�Ȥ����뤿���
		$navipage_id = hitobito::getNaviPageInfo()->getId();
		if($navipage_id > 0){
			$actkey .= '&navipage_id='.$navipage_id;
		}
		$mailer->setAttribute('ACTKEY', $actkey);
		$mailer->setAttribute('MAIL', $member->getAttribute('mbr_mail_addr'));
		$mailer->setAttribute('URL', $siteUrl);
		$mailer->setAttribute('CONFIRM_ID', $pre_confirm_member_id);

		return $mailer->send($member->getAttribute('mbr_mail_addr'));
	}	
	
	/**
	 * �����ƥ��١������
	 * @param	integer	$preId	����ϿID
	 * @param	string	$actkey	�����ƥ��١�����󥭡�
	 */ 
	public function activate ($preId, $actkey)
	{
		$handler = HNb::getDBHandler('t_member');
		$where = 'mbr_member_id='.intval($preId) . ' AND mbr_actkey='.$this->db->qstr($actkey);
		$member = $handler->getOne($where);
		if($member->getId() != $preId){
			return FALSE;
		}
		$member->setAttribute('mbr_actkey', NULL);
		$member->setAttribute('mbr_status', 1);
		$handler->insert($member);
		
		// ����Ͽ��λ�᡼������
		$mailer = hitobito::getMailer();
		$mailer->loadTemplate('activate_ok');
		$mailer->setAttribute('MAIL', $member->getAttribute('mbr_mail_addr'));
		$mailer->setAttribute('URL', hitobito::getUrl());

		$mailer->send($member->getAttribute('mbr_mail_addr'));
		
		
		return TRUE;
		
//    	$sql = sprintf("SELECT * FROM pre_confirm_member WHERE pre_confirm_member_id=%d AND actkey=%s", 
//			$preId,
//			$this->db->qstr($actkey));
//		$rs = $this->db->GetRow($sql);
//		if($rs['pre_confirm_member_id'] != $preId){
//			return FALSE;
//		}
//		// ����Ͽ�������
//		$this->db->query('DELETE FROM '.$this->preMemberTable. ' WHERE '. $this->preMemberTablePkey.'='.$preId);
//		
////		$member = new hitobitoMember();
//		$member = $this->getByEmail($rs['mail']);
//		$member->setStatus($rs['status']);
//		$member->setRegistDate(time());
//		$member->setEmail($rs['mail']);
//		$member->setMd5Password($rs['password']);
//		$member->setHitobito_letter($rs['hitobito_letter']);
//		$this->insert($member);
//    	
//    	return TRUE;
    }
    
    /**
     * �ѥ���ɺ�ȯ��
     */
    public function remindPasssword($mail)
    {
    	// TODO �᡼��ƥ�ץ졼�Ȥ�Ȥ��褦�ˤ���
    	$member = $this->getByEmail($mail);
    	$remindPassword = $this->getRemindPassword();
    	$member->setPassword($remindPassword);
    	$this->insert($member);
    	$body = "�����ѥ���ɤ�Ĥ��äƥ�����塢�ѥ���ɤ��ѹ����Ƥ�������\n";
    	$body .= $remindPassword;
    	mb_send_mail($member->getEmail(), '�ѥ���ɺ�ȯ��', $body);
    	
    	/*
    	$mailer = new HNbMailer();
		$mailer->setTemplatePath(MO_WEBAPP_DIR.'/modules/Member/mailTemplates');
		$mailer->setTemplate('confirmMail.tpl');
		
		$rs = $this->db->GetRow('SELECT * FROM '. $table.' WHERE pre_confirm_member_id='.$pre_confirm_member_id);
		$mailer->setTo($rs['mail']);
		$mailer->setFrom('ryuji@rhym.biz');
		$mailer->setSubject('�ҤȤӤȡ�net �ˤ���Ͽ���꤬�Ȥ��������ޤ�');
		
		$mailer->setAttribute('actkey', $rs['actkey']);
		$mailer->setAttribute('mail', $rs['mail']);
		$mailer->setAttribute('hnb_url', HNB_HTTP_URL);
		$mailer->setAttribute('pre_confirm_member_id', $pre_confirm_member_id);
		$mailer->send();
		*/
    	
    }
    
    
    private function getRemindPassword()
    {
	    $makepass = '';
	    $syllables = array("er","in","tia","wol","fe","pre","vet","jo","nes","al","len","son","cha","ir","ler","bo","ok","tio","nar","sim","ple","bla","ten","toe","cho","co","lat","spe","ak","er","po","co","lor","pen","cil","li","ght","wh","at","the","he","ck","is","mam","bo","no","fi","ve","any","way","pol","iti","cs","ra","dio","sou","rce","sea","rch","pa","per","com","bo","sp","eak","st","fi","rst","gr","oup","boy","ea","gle","tr","ail","bi","ble","brb","pri","dee","kay","en","be","se");
	    srand((double)microtime()*1000000);
	    for ($count = 1; $count <= 4; $count++) {
	        if (rand()%10 == 1) {
	            $makepass .= sprintf("%0.0f",(rand()%50)+1);
	        } else {
	            $makepass .= sprintf("%s",$syllables[rand()%62]);
	        }
	    }
	    return $makepass;    	
    }
	/**
	 * ��������Ⱥ��
	 * 
	 * @param hitobitoMember	$member
	 * 
	 */
	 public function delete($member)
	 {
	 	$id = $member->getId();
	 	$this->db->query("DELETE FROM t_member WHERE mbr_member_id=".$id);
	 	
	 }

}

class hitobitoWaiwaiMemberManager extends hitobitoMemberManager
{
	public function get($id){
		$member = parent::get($id);
		if($member->getId() > 0){
			
		}
		
	}
}
?>