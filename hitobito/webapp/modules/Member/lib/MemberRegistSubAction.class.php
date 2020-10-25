<?php

/**
 * メンバー新規登録を実際に行うサブアクション
 * ex)
	public function execute()
	{
		$this->setBrowseCommon();
		$subaction = new MemberRegistSubAction();
		$subaction->initialize($this->getContext());
		return $subaction->execute();
	}
	
 */

require_once MO_WEBAPP_DIR.'/modules/Member/forms/accountForm.class.php';
define('MEMBER_RECRUIT_ID_COOKIE_NAME', 'cookie_recruit_id');
class MemberRegistSubAction extends HNbEditAction
{
	private $recruitId;
	private $extend_require_code_list = array();
	
	protected function fetchID()
	{
		return 0;
	}

	protected function getManager()
	{
		return new hitobitoMemberManager();
	}
	
	protected function getActionForm()
	{
		$form =  new MemberNewRegistForm();
		$form->setRequireParameterList($this->extend_require_code_list);
		return $form;
	}

	protected function setUpNewObject(&$obj)
	{
		// =====================================================================
		// Object Init Code : オブジェクトの状況にあわせた初期化が必要ならここに書く
		// =====================================================================
		// (Ex.) $obj->setBlog_id($this->blog_id);
		$obj->setAttribute('mbr_hitobito_letter',1);///< 新規登録時にひとびと便りの配信をONにする
		// クリックカウンタのメンバー登録数をカウントするために、クッキーにリクルートIDがあれば、mbr_recruit_idに記録する
		if(isset($_COOKIE[MEMBER_RECRUIT_ID_COOKIE_NAME])){
		    $obj->setAttribute('mbr_recruit_id', intval($_COOKIE[MEMBER_RECRUIT_ID_COOKIE_NAME]));
		}
	}

	protected function isEdit($obj)
	{
		// =======================================================================
		// Permission Check etc...  編集権限のチェックなどが必要ならここにコードを書く
		// ======================================================================= 
		return TRUE;
	}
	
	protected function isConfirm()
	{
		return TRUE;
	}

	public function isSecure()
	{
		return FALSE;
	}
	
	protected function insert($master)
	{
		HNb::getAdodb()->StartTrans();
		// 仮登録する
		$this->manager->insertPreConfirmMemberObject($master);

		$result =FALSE;		
		if($master->getId() >0){
		    $this->updateRecruitCount();
			// 確認用メール送信。
			$result = $this->manager->sendRegixxxonfirmMail($master->getId());
		}
		//メール送信失敗ならロールバックする
		HNb::getAdodb()->CompleteTrans($result);
		
		return $result;		
	}	
	/**
	 * クリックカウンタ更新
	 */
	private function updateRecruitCount()
	{
		if(isset($_COOKIE[MEMBER_RECRUIT_ID_COOKIE_NAME])){
		    $recruitId = intval($_COOKIE[MEMBER_RECRUIT_ID_COOKIE_NAME]);
	        $db = HNb::getAdodb();
	        $sql = "UPDATE c_recruit_click SET rcc_regist_count = rcc_regist_count +1 WHERE rcc_recruit_id=".$recruitId;
	        $db->Execute($sql);
	        setcookie(MEMBER_RECRUIT_ID_COOKIE_NAME, '',0, '/'); // クリックカウンタクッキー削除
	        
	    }
	}
	
	// action form で入力必須にする項目コードリストのセット
	public function setExtendRequireCodeList($code_list)
	{
		$this->extend_require_code_list = $code_list;
	}
}
?>