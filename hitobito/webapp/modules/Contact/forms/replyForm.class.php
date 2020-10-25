<?php
/**
 * @package	Contact
 * @author Ryuji
 * @version $Id: replyForm.class.php,v 1.1 2005/12/22 23:35:50 ryu Exp $
 */
//require_once MO_WEBAPP_DIR .'/modules/Contact/lib/BaseForm.class.php';
class ContactReplyForm extends HNbSemiAutoActionForm
{
	public
		$contact_subject,
		$contact_mail_addr,
		$contact_content;
	
	protected function getStringParameterKeys()
	{
		$strParamsKey = array(
			'contact_mail_addr',
			'contact_subject',
			'contact_content',
		);
		return $strParamsKey;
	}
	protected function getIntegerParameterKeys()
	{
		return array();
	}
	
	protected function getRequireParameterKeys()
	{
		return array(
			'contact_subject' => HNb::tr('SUBJECT��̤���ϤǤ���'),
			'contact_mail_addr' => HNb::tr('�᡼�륢�ɥ쥹��̤���ϤǤ�'),
			'contact_content' => HNb::tr('��ʸ��̤���ϤǤ�')
		);
	}
		
		
	public function fetch($master , $request)
	{
		parent::fetch($master, $request);
		$validator = new HNbValidator();
		if(!$validator->validateEmail($this->contact_mail_addr)){
			$this->addError(HNb::tr('�᡼�륢�ɥ쥹��ͭ���ǤϤ���ޤ���'));
		}
	}
	
	public function load($master, $requset)
	{
		$this->contact_mail_addr = $master->getAttribute('nva_mail_addr');
		$this->contact_subject = $master->getAttribute('nva_subject');
		$this->contact_content = $master->getAttribute('nva_content');
	}
	
	public function update($master, $request)
	{
		$master->setAttribute('nva_mail_addr',	$this->contact_mail_addr);
		$master->setAttribute('nva_content',	$this->contact_content);
		$master->setAttribute('nva_subject',	$this->contact_subject);
		
	}

}
?>