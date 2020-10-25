<?php
/**
 * @package	Contact
 * @author Ryuji
 * @version $Id: BaseForm.class.php,v 1.1 2005/12/22 16:24:31 ryu Exp $
 */
require_once MO_WEBAPP_DIR .'/lib/HNbActionForm.class.php';
class ContactBaseForm extends HNbSemiAutoActionForm
{
	public
		$contact_title,
		$contact_name,
		$contact_mail_addr,
		$contact_inquiry_type,
		$contact_content,
		$contact_company_name,
		$contact_section_name,
		$contact_tel_no,
		$contact_fax_no;
	
	protected function getStringParameterKeys()
	{
		$strParamsKey = array(
			'contact_title',
			'contact_name',
			'contact_mail_addr',
			'contact_content',
			'contact_company_name',
			'contact_section_name',
			'contact_tel_no',
			'contact_fax_no'
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
			'contact_title' => HNb::tr('タイトルが未入力です。'),
			'contact_name' => HNb::tr('お名前が未入力です'),
			'contact_mail_addr' => HNb::tr('メールアドレスが未入力です'),
			'contact_content' => HNb::tr('詳細が未入力です')
		);
	}
		
		
	public function fetch($master , $request)
	{
		parent::fetch($master, $request);
		$validator = new HNbValidator();
		if(!$validator->validateEmail($this->contact_mail_addr)){
			$this->addError(HNb::tr('メールアドレスが有効ではありません'));
		}
	}
	
	public function load($master, $requset)
	{
//		$this->weblinks_category_id = $master->getAttribute('nol_link_category_id');
//		$this->weblinks_url = $master->getAttribute('nol_link_url');
//		$this->weblinks_title = $master->getAttribute('nol_link_name');
//		$this->weblinks_outline = $master->getAttribute('nol_link_outline');
	}
	
	public function update($master, $request)
	{
		$master->setAttribute('nvi_name',	$this->contact_name);
		$master->setAttribute('nvi_mail_addr',	$this->contact_mail_addr);
		$master->setAttribute('nvi_content',	$this->contact_content);
		$master->setAttribute('nvi_company_name',	$this->contact_company_name);
		$master->setAttribute('nvi_section_name',	$this->contact_section_name);
		$master->setAttribute('nvi_tel_no',	$this->contact_tel_no);
		$master->setAttribute('nvi_fax_no',	$this->contact_fax_no);
		$master->setAttribute('nvi_inquiry_type',	$this->contact_inquiry_type);
		$master->setAttribute('nvi_title',	$this->contact_title);
		
	}
	
	public function getSuccessMessage()
	{
		return HNb::tr('送信しました。');
	}
	public function getErrorMessage()
	{
		return HNb::tr('送信失敗');
	}
}
?>