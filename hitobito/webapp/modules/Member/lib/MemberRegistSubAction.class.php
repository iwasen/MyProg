<?php

/**
 * ���С�������Ͽ��ºݤ˹Ԥ����֥��������
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
		// Object Init Code : ���֥������Ȥξ����ˤ��碌���������ɬ�פʤ餳���˽�
		// =====================================================================
		// (Ex.) $obj->setBlog_id($this->blog_id);
		$obj->setAttribute('mbr_hitobito_letter',1);///< ������Ͽ���ˤҤȤӤ��ؤ���ۿ���ON�ˤ���
		// ����å������󥿤Υ��С���Ͽ���򥫥���Ȥ��뤿��ˡ����å����˥ꥯ�롼��ID������С�mbr_recruit_id�˵�Ͽ����
		if(isset($_COOKIE[MEMBER_RECRUIT_ID_COOKIE_NAME])){
		    $obj->setAttribute('mbr_recruit_id', intval($_COOKIE[MEMBER_RECRUIT_ID_COOKIE_NAME]));
		}
	}

	protected function isEdit($obj)
	{
		// =======================================================================
		// Permission Check etc...  �Խ����¤Υ����å��ʤɤ�ɬ�פʤ餳���˥����ɤ��
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
		// ����Ͽ����
		$this->manager->insertPreConfirmMemberObject($master);

		$result =FALSE;		
		if($master->getId() >0){
		    $this->updateRecruitCount();
			// ��ǧ�ѥ᡼��������
			$result = $this->manager->sendRegixxxonfirmMail($master->getId());
		}
		//�᡼���������Ԥʤ����Хå�����
		HNb::getAdodb()->CompleteTrans($result);
		
		return $result;		
	}	
	/**
	 * ����å������󥿹���
	 */
	private function updateRecruitCount()
	{
		if(isset($_COOKIE[MEMBER_RECRUIT_ID_COOKIE_NAME])){
		    $recruitId = intval($_COOKIE[MEMBER_RECRUIT_ID_COOKIE_NAME]);
	        $db = HNb::getAdodb();
	        $sql = "UPDATE c_recruit_click SET rcc_regist_count = rcc_regist_count +1 WHERE rcc_recruit_id=".$recruitId;
	        $db->Execute($sql);
	        setcookie(MEMBER_RECRUIT_ID_COOKIE_NAME, '',0, '/'); // ����å������󥿥��å������
	        
	    }
	}
	
	// action form ������ɬ�ܤˤ�����ܥ����ɥꥹ�ȤΥ��å�
	public function setExtendRequireCodeList($code_list)
	{
		$this->extend_require_code_list = $code_list;
	}
}
?>