<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: PreviewStoryAction.class.php,v 1.1 2006/02/21 14:09:05 ryu Exp $
 */
 require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseAction.class.php';
 

class PreviewStoryAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
		$manager = new BlogStoryManager();
		$obj = $manager->create();
		$obj->setAttribute('bla_title', $request->getParameter('bla_title'));
		$obj->setAttribute('bla_article_text', $request->getParameter('bla_article_text'));
		$obj->setPoster($user->getAttribute('mbr_member_id'));
		
		$request->setAttribute('obj', $obj);
        return View::SUCCESS;
    }


    public function getDefaultView ()
    {
        return View::INPUT;
    }

    public function getRequestMethods()
    {
        
        return Request::POST;
    }

    public function isSecure()
    {
        return true;
    }
}

?>