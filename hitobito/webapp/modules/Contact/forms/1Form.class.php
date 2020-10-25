<?php
/**
 * @package	Contact
 * @author Ryuji
 * @version $Id: 1Form.class.php,v 1.1 2005/12/22 16:24:31 ryu Exp $
 */
require_once MO_WEBAPP_DIR .'/modules/Contact/lib/BaseForm.class.php';
class Contact1Form extends ContactBaseForm
{
	public $inquiry_type_options = array();
	public $contact_inquiry_type = 3;
	
	public function __construct()
	{
		$this->inquiry_type_options = array(
			3 => HNb::tr('�Ż��ΰ���'),
			4 => HNb::tr('���ΰ���'),
			9 => HNb::tr('����¾'));
	}
	
	protected function getRequireParameterKeys()
	{
		return array_merge(parent::getRequireParameterKeys(), array('contact_company_name' => HNb::tr('����̾�����Ϥ���Ƥ��ޤ���')));
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
		
		if($this->contact_inquiry_type != 3 && $this->contact_inquiry_type != 4 && $this->contact_inquiry_type != 9){
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
}
?>