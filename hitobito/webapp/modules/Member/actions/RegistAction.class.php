<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Member
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: RegistAction.class.php,v 1.2 2006/01/22 13:12:17 ryu Exp $
 */

require_once MO_WEBAPP_DIR.'/lib/HNbActionForm.class.php';

require_once MO_WEBAPP_DIR.'/hitobito/hitobitoMemberManager.class.php';

require_once MO_WEBAPP_DIR.'/modules/Member/lib/MemberRegistSubAction.class.php';
class RegistAction extends Action
{
	public function execute()
	{
		$subaction = new MemberRegistSubAction();
		$subaction->initialize($this->getContext());
		return $subaction->execute();
	}
}

?>