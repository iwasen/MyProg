<?php
/**
 * hitobitoメンバー管理
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
		STATUS_PRE_CONFIRM=0, // 仮登録
		STATUS_ACTIVE	= 1, // 有効
		STATUS_NOT_ALIVE= 2, // 不達
		STATUS_STOP		= 3, // 一時停止
		STATUS_RESIGN	= 9;  // 退会
		
		
	 // 仮登録用テーブル
	 private $preMemberTable = 'pre_confirm_member';
	 // 仮登録用テーブルプライマリキー
	 private $preMemberTablePkey = 'pre_confirm_member_id';

	 private $memberTable = 't_member';
	 private $memberTablePkey = 'mbr_member_id';
	 
	 private $db;
	
	public function __construct($db = null)
	{
		$this->db = HNb::getAdodb();
	}
	
	/**
	 * Debug用にテーブル情報取得
	 */
	 public function getTableInfo()
	 {
	 	$ret = array();
	 	$ret['pkey'] = $this->preMemberTablePkey;
	 	$ret['table'] = $this->preMemberTable;
	 	return $ret;
	 }
	
	/**
	 * ログイン処理
	 * 
	 * @param	object BasicSecurityUser
	 * @param	string	メールアドレス
	 * @param	string	パスワード
	 * 
	 * @return	object	hitobitoMember（ログイン失敗ならFALSE）
	 */
	public function login($user, $email, $password, $md5 = true)
	{
		if ($md5)
			$password = md5($password);
		$lowmail = strtolower($email);

		// 退会してたらログインさせない. アクティベーションまだだったらログインさせない
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
		    
		    // 担当ナビページ情報を渡す
//        	$navipageManager = new hitobitoNavipageManager(); 
//        	$naviPages = $navipageManager->getNaviPagesByMemberId($user->getAttribute('mbr_member_id'));
//        	$user->setAttribute('my_navipages', $naviPages, 'Navi');
		    
		}
		
		
		$user->setAttributes($member->getAttributes());
		
		
		
		return $member;
	}
	
	/**
	 * TODO ログアウト処理
	 * 
	 * @param	object	hitobitoMember
	 */
	public function logout($member)
	{
		
	}
	
	
	/** 
	 * メールアドレスが存在するかチェック
	 * 退会したアドレスはチェックしない
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
	 * 退会、仮登録以外のステータスのアカウントでメールアドレスが使われているかチェックする
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
	 * @param	integer	メンバーID
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
	 * 保存
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
	 
	 
	 // 退会の記録
	 public function resignMember($user, $resign_id)
	 {
	 	$resignMember = $this->get($user->getAttribute('mbr_member_id'));
	 	$resignMember->resign($resign_id);
	 	$this->insert($resignMember);
	 		 	
		 // logout 処理
		$user->clearCredentials();
		$user->clearAttributes();
        $user->setAuthenticated(FALSE);

		 
	 }
	 
	 
	 /**
	  * メンバー仮登録
	  */
	 public function insertPreConfirmMemberObject($member)
	 {
		 $handler = HNb::getDBHandler('t_member');
		 //仮登録者の再登録なら以前の仮登録データを削除する（退会者の再登録時は、以前のアカウントデータは残したまま、新規アカウントにする）
	 	// 退会者か仮登録者の再登録だったらテーブルを更新する
	 	$where = 'mbr_mail_addr_low='.$this->db->qstr(strtolower($member->getEmail())) . ' AND ( mbr_status = '.self::STATUS_PRE_CONFIRM .')';
	 	$handler->deletes($where);
	 	$Insertmember = $handler->create();
	 	$Insertmember->setAttributes($member->getAttributes());
	 	$Insertmember->setAttribute('mbr_regist_date', time());
	 	$Insertmember->setAttribute('mbr_actkey', $this->makeActkey($member->getEmail()));
	 	$Insertmember->setAttribute('mbr_mail_addr_low', strtolower($member->getEmail()));
	 	$Insertmember->setAttribute('mbr_status', 0); // 仮登録時はステータス0
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
	  * 仮登録確認メール送信
	  * 
	  * @param	int	pre_confirm_member のプライマリキー
	  */
	  // メールテンプレートからメールのひな形を取得
	public function sendRegixxxonfirmMail($pre_confirm_member_id)
	{
		$handler = HNb::getDBHandler('t_member');
		$member = $handler->get($pre_confirm_member_id);
		
		$siteUrl = hitobito::getUrl();
		
		
		$mailer = hitobito::getMailer();
		$mailer->loadTemplate('regist_confirm');
		
		$actkey = $member->getAttribute('mbr_actkey');
		//ACTKEYにnavipage_idを混ぜる（アクティベート後に該当会議室へリダイレクトさせるため）
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
	 * アクティべーション
	 * @param	integer	$preId	仮登録ID
	 * @param	string	$actkey	アクティべーションキー
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
		
		// 本登録完了メール送信
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
//		// 仮登録情報を削除
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
     * パスワード再発行
     */
    public function remindPasssword($mail)
    {
    	// TODO メールテンプレートを使うようにする
    	$member = $this->getByEmail($mail);
    	$remindPassword = $this->getRemindPassword();
    	$member->setPassword($remindPassword);
    	$this->insert($member);
    	$body = "下記パスワードをつかってログイン後、パスワードを変更してください\n";
    	$body .= $remindPassword;
    	mb_send_mail($member->getEmail(), 'パスワード再発行', $body);
    	
    	/*
    	$mailer = new HNbMailer();
		$mailer->setTemplatePath(MO_WEBAPP_DIR.'/modules/Member/mailTemplates');
		$mailer->setTemplate('confirmMail.tpl');
		
		$rs = $this->db->GetRow('SELECT * FROM '. $table.' WHERE pre_confirm_member_id='.$pre_confirm_member_id);
		$mailer->setTo($rs['mail']);
		$mailer->setFrom('ryuji@rhym.biz');
		$mailer->setSubject('ひとびと・net にご登録ありがとうございます');
		
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
	 * アカウント削除
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