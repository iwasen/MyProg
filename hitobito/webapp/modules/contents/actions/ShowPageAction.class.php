<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package contents
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: ShowPageAction.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */

class ShowPageAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
        $id = intval($request->getParameter('content_id'));
        if(file_exists(MO_WEBAPP_DIR.'/modules/contents/templates/'.$id.'.html')){
        	$request->setAttribute('content_file', $id.'.html');
	        return View::SUCCESS;
        }
        $controller->forward('Default', 'Error404');
        return View::NONE;
    }
    
    public function isSecure()
    {
        return false;
    }
}

?>