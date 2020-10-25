<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Category
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowRandomNavipageAction.class.php,v 1.1 2005/12/01 05:32:46 ryu Exp $
 */
 
 // TODO ランダム
require_once MO_WEBAPP_DIR.'/hitobito/hitobitoChannel.class.php';
class _blockShowRandomNavipageAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
 
		$navipage = hitobito::getNaviPageInfo();
		$subchannelId = $navipage->getAttribute('nvp_subchannel_id');
		 
		$handler = new hitobitoNavipageManager();
        
        return View::SUCCESS;
    }
    
    public function isSecure()
    {
        return false;
    }
}

?>