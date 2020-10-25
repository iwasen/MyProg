<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Member
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: NewRegistStep1Action.class.php,v 1.5 2006/06/09 07:16:19 ryu Exp $
 */

require_once MO_WEBAPP_DIR.'/lib/HNbActionForm.class.php';

require_once MO_WEBAPP_DIR.'/modules/Waiwai/lib/WaiwaiBaseAction.class.php';
require_once MO_WEBAPP_DIR.'/hitobito/hitobitoMemberManager.class.php';

require_once MO_WEBAPP_DIR.'/modules/Member/lib/MemberRegistSubAction.class.php';
class NewRegistStep1Action extends WaiwaiBaseAction
{
	public function execute()
	{
		$this->setBrowseCommon();
		
		// この会議室のメンバー必須パラメータを得る
		
		$waiwaiDataBase = new WaiwaiDatabase();
		$required_item = $waiwaiDataBase->getRequiredItem(hitobito::getNaviPageInfo()->getWaiwaiId());
		$required_item_list = explode(',', $required_item);
		$request = $this->getContext()->getRequest();
		$request->setAttribute('member_required_item_list', $required_item_list);


		$subaction = new MemberRegistSubAction();
		$subaction->setExtendRequireCodeList($required_item_list);
		
		
		$subaction->initialize($this->getContext());
		return $subaction->execute();
	}
}


?>
