<?php
/**
 * @package	Contact
 * @author Ryuji
 * @version $Id: 2Form.class.php,v 1.1 2005/12/22 16:24:31 ryu Exp $
 */
require_once MO_WEBAPP_DIR .'/modules/Contact/lib/BaseForm.class.php';
class Contact2Form extends ContactBaseForm
{
	public $inquiry_type_options = array();
	public $contact_inquiry_type = 1;
	
	public function __construct()
	{
		$this->inquiry_type_options = array(
			1 => HNb::tr('�����ˤĤ���'),
			2 => HNb::tr('�磻�磻��ļ��ˤĤ���'),
			9 => HNb::tr('����¾'));
	}
	
		
	protected function getIntegerParameterKeys()
	{
		$intParamsKey = array(
			'contact_inquiry_type'
			);
		return $intParamsKey;
	}
		
	public function fetch($master , $request)
	{
		parent::fetch($master, $request);
		
		if($this->contact_inquiry_type != 1 && $this->contact_inquiry_type != 2 && $this->contact_inquiry_type != 9){
			// �����ꥯ������
			die();
		}
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
		return HNb::tr('���䤤��碌���������ޤ�����');
	}
	public function getErrorMessage()
	{
		return HNb::tr('��������');
	}
	
}
?>