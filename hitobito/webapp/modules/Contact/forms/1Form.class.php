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
			3 => HNb::tr('仕事の依頼'),
			4 => HNb::tr('取材の依頼'),
			9 => HNb::tr('その他'));
	}
	
	protected function getRequireParameterKeys()
	{
		return array_merge(parent::getRequireParameterKeys(), array('contact_company_name' => HNb::tr('貴社名が入力されていません。')));
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
			// 不正リクエスト
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