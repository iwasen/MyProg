<?php
/**
 * @package	Weblinks
 * @author Ryuji
 * @version $Id: linkForm.class.php,v 1.1 2005/12/13 18:31:26 ryu Exp $
 */
require_once MO_WEBAPP_DIR .'/lib/HNbActionForm.class.php';
class WeblinksLinkForm extends HNbSemiAutoActionForm
{
	public
		$weblinks_category_id,
		$weblinks_url,
		$weblinks_title,
		$weblinks_outline;
	
	protected function getStringParameterKeys()
	{
		$strParamsKey = array('weblinks_url', 'weblinks_title', 'weblinks_outline');
		return $strParamsKey;
	}
	protected function getIntegerParameterKeys()
	{
		$intParamsKey = array('weblinks_category_id');
		return $intParamsKey;
	}
	protected function getRequireParameterKeys()
	{
		return array(
			'weblinks_url' => HNb::tr('リンク先URLを入力してください。'),
			'weblinks_title' => HNb::tr('リンク先名称を入力してください。'),
			'weblinks_outline' => HNb::tr('リンク先概要を入力してください。'),
			'weblinks_category_id' => HNb::tr('カテゴリを選択してください。'),
		);
	}
		
		
	public function fetch($master , $request)
	{
		parent::fetch($master, $request);
		$validator = new HNbValidator();
		if(!$validator->validateUrl($this->weblinks_url)){
			$this->addError(HNb::tr('URLを半角で正しく入力してください'));
		}
	}
	
	public function load($master, $requset)
	{
		$this->weblinks_category_id = $master->getAttribute('nol_link_category_id');
		$this->weblinks_url = $master->getAttribute('nol_link_url');
		$this->weblinks_title = $master->getAttribute('nol_link_name');
		$this->weblinks_outline = $master->getAttribute('nol_link_outline');
	}
	
	public function update($master, $request)
	{
		$master->setAttribute('nol_link_category_id', $this->weblinks_category_id);
		$master->setAttribute('nol_link_url', $this->weblinks_url);
		$master->setAttribute('nol_link_name', $this->weblinks_title);
		$master->setAttribute('nol_link_outline', $this->weblinks_outline);
	}
}
?>