<?php
/**
 * @package	Waiwai
 * @author Ryuji
 * @version $Id: registNicknameForm.class.php,v 1.3 2006/06/07 07:32:07 aizawa Exp $
 */
require_once MO_WEBAPP_DIR .'/lib/HNbActionForm.class.php';
class WaiwaiRegistNicknameForm extends HNbSemiAutoActionForm
{
	public
		$waiwai_nickname;
	
	protected function getStringParameterKeys()
	{
		$strParamsKey = array('waiwai_nickname');
		return $strParamsKey;
	}
	protected function getIntegerParameterKeys()
	{
		return array();
	}
	protected function getRequireParameterKeys()
	{
		return array(
			'waiwai_nickname' => HNb::tr('ニックネームを入力してください。'),
		);
	}
		
		
	public function fetch($master , $request)
	{
		parent::fetch($master, $request);
		// ニックネームの重複チェック
		$handler = HNb::getDBHandler('t_room_member');
		$criteria = new HNbCriteria();

		$criteria->setWhere('rmm_room_id='.hitobito::getNaviPageInfo()->getWaiwaiId());
		$where = sprintf("rmm_nickname='%s'", addslashes(htmlspecialchars($this->waiwai_nickname)));
		$criteria->addWhere($where);
		if($handler->getCount($criteria)){
			$this->addError(HNb::tr('すでに使われているニックネームです。'));
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
		$master->setAttribute('rmm_nickname', htmlspecialchars($this->waiwai_nickname));
	}
}
?>