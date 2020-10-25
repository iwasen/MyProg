<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: ViewStoryAction.class.php,v 1.4 2006/08/01 22:08:08 ryu Exp $
 */
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseAction.class.php';

class ViewStoryAction extends BlogBaseAction
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();

		$id = intval($request->getParameter('blog_story_id', 0));
		$manager = new BlogStoryManager();
		$criteria = new HNbCriteria();
		$criteria->setWhere('bla_article_id='.$id);
		$criteria->addWhere('bla_date < now()');
		$obj = $manager->getOne($criteria);
		$request->setAttribute('obj', $obj);
		if($obj->getId() == $id){
	        ob_start();
	    	$controller->forward('Blog', 'modifyComment');
	    	$html= ob_get_contents();
	   		$request->setAttribute('blog_comment_form', $html);
	    	ob_end_clean();
			return View::SUCCESS;
		}else{
			$controller->forward('Default', 'Error404');
			return View::NONE;
		}	

    }
    
    public function isSecure()
    {
        return false;
    }
}




?>