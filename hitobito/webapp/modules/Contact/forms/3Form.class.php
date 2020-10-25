<?php
/**
 * @package	Contact
 * @author Ryuji
 * @version $Id: 3Form.class.php,v 1.1 2005/12/22 16:24:31 ryu Exp $
 */
require_once MO_WEBAPP_DIR .'/modules/Contact/lib/BaseForm.class.php';
class Contact3Form extends ContactBaseForm
{
	public $inquiry_type_options = array();
	public $contact_inquiry_type = 5;
	
	public function __construct()
	{
		$this->inquiry_type_options = array(
			1 => HNb::tr('�����ˤĤ���'),
			2 => HNb::tr('�磻�磻��ļ��ˤĤ���'),
			9 => HNb::tr('����¾'));
	}
	
		
	protected function getRequireParameterKeys()
	{
		$ret = parent::getRequireParameterKeys();
		$ret['contact_content'] = HNb::tr('��ʸ��̤���ϤǤ���');
		return $ret;
	}
		
	public function fetch($master , $request)
	{
		parent::fetch($master, $request);
	}
	
	public function load($master, $request)
	{
		parent::load($master, $request);
	}
	
	public function update($master, $request)
	{
		parent::update($master, $request);
	}
	public function getSuccessMessage()
	{
		return HNb::tr('���������դ��ޤ�����');
	}
	public function getErrorMessage()
	{
		return HNb::tr('��������');
	}
	
}
?>