<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Member
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: NewRegistStep3Action.class.php,v 1.3 2006/06/07 07:32:07 aizawa Exp $
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class NewRegistStep3Action extends WaiwaiBaseAction 
{
	public function execute()
	{
		return View::SUCCESS;
	}
}
?>
