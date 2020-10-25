<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package contents
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowContentAction.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */

class _blockShowContentAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
        $page = $request->getAttribute('content_block_page');
        
        if(file_exists(MO_WEBAPP_DIR.'/modules/contents/templates/'.$page.'.html')){
        	$request->setAttribute('content_block_page', $page.'.html');
	        return View::SUCCESS;
        }
//        $controller->forward('Default', 'Error404');
        return View::NONE;
    }
    
    public function isSecure()
    {
        return false;
    }
}

?>