<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Category
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowMainCategoriesAction.class.php,v 1.2 2006/01/19 06:44:19 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/hitobito/hitobitoChannel.class.php';
class _blockShowMainCategoriesAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        if(hitobito::getChannelId() > 0){
        	// sublist
			$id = hitobito::getChannelId();
	        $manager = new hitobitoSubChannelManager();
	        $list = $manager->getSubChannelList($id);
	        $request->setAttribute('category_sub_categories', $list);
	        $request->setAttribute('template', '_blockSubCategories.html');
        	return View::SUCCESS;
        }
        
        $manager = new hitobitoChannelManager();
        $list = $manager->getMainChannelList();
        $request->setAttribute('category_main_categories', $list);
	   	$request->setAttribute('template', '_blockMainCategories.html');
        
        return View::SUCCESS;
    }
    
    public function isSecure()
    {
        return false;
    }
}

?>