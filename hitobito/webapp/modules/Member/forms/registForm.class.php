<?php

class MemberNewRegistForm extends HNbActionForm
{
	public 	$member_mail,
			$member_password,
			$member_confirm_password;
	
	public function fetch($master , $request)
	{
		parent::fetch($master, $request);
		
		$strParamsKey = array('member_mail', 'member_password', 'member_confirm_password');
		
		foreach($strParamsKey as $key){
			$this->$key = trim($request->getParameter($key, ''));
		}
		
		
		$validater = new HNbValidater();
		
		// ����ɬ�ܹ��ܤΥ����å�
		if(strlen($this->member_mail) == 0){
			$this->addError('�᡼�륢�ɥ쥹�����Ϥ��Ƥ�������');
		}
		if(strlen($this->member_password) == 0){
			$this->addError('�ѥ���ɤ����Ϥ��Ƥ���������');
		}
		if(strlen($this->member_password) < 6){
			$this->addError('�ѥ���ɤ�6ʸ���ʾ�����Ϥ��Ƥ���������');
		}
		
		if(strlen($this->member_confirm_password) == 0){
			$this->addError('�ѥ���ɡʳ�ǧ�ѡˤ����Ϥ��Ƥ���������');
		}
		// �᡼�륢�ɥ쥹������������
		if(!$validater->validateEmail($this->member_mail)){
			$this->addError('�᡼�륢�ɥ쥹��ͭ���ǤϤ���ޤ���');
		}
		// pass���פ��Ƥ��뤫
		if($this->member_password != $this->member_confirm_password){
			$this->addError('�ѥ���ɤȥѥ���ɺ����Ϥ����פ��ޤ���');
		}
		// �᡼�륢�ɥ쥹�ν�ʣ����
		$handler = HNb::getHandler('MemberManager');
		if($handler->isEmailUsedInAliveMemberAccount($this->member_mail)){
			$this->addError('���Ǥ˻Ȥ��Ƥ���᡼�륢�ɥ쥹�Ǥ�');
		}	
	}
	
	public function load($master, $requset)
	{
	}
	
	public function update($master, $request)
	{
		$master->setEmail($this->member_mail);
		if(strlen($this->member_password) > 0){
			$master->setPassword($this->member_password);
		}
	}

}
?>