<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package RSS
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: IndexAction.class.php,v 1.1 2006/01/04 10:03:10 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/hitobito/hitobitoChannel.class.php';
class IndexAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
    
    
        $manager = new hitobitoChannelManager();
        
        $start = 0;
        $limit = 20;
        
        $itemlist = $manager->getMainChannelList();
        $request->setAttribute('itemlist', $itemlist);
        
        return View::SUCCESS;
    }
    
    public function isSecure()
    {
        return false;
    }
    
}


?>