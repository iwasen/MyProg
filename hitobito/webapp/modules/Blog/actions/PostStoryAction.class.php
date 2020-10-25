<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: PostStoryAction.class.php,v 1.4 2006/03/20 03:35:54 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/modules/Blog/forms/storyForm.class.php';
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseAction.class.php';

class PostStoryAction extends BlogBaseAction
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        Navi::GuardNaviEdit($this->getContext()->getUser());
        $request->setAttribute('blog_id', $this->blog_id);
        
        $editform = new BlogStoryForm();

		$manager = new BlogStoryManager();
		
		$id = $request->getParameter('blog_story_id', 0);
		
		$master = $manager->get($id);
		if($master->isNew()){
			$master->setBlog_id($this->blog_id);
			$master->setPoster($user->getAttribute('mbr_member_id'));
		}
		
		if($editform->init($master, $request) === HNB_ACTIONFORM_POST_SUCCESS){
			// update
			$editform->update($master, $request);
			$manager->insert($master);

            // 公開＆公開日が未来ではない
            if($master->getAttribute('bla_open_flag') == 1 && strtotime($master->getAttribute('bla_date')) < time() ){
                // send trackback
			    $tb = new HNbTrackBack();
			    $tb->blog_name = $this->navipage->getAttribute('nvp_title');
			    $tb->title = $master->getTitle();
			    $tb->url = hitobito::getUrl().'index.php?module=Blog&amp;action=ViewStory&amp;blog_story_id='.$master->getId().'&amp;navipage_id='.$this->navipage->getId();
			    $tb->excerpt = $master->getText4trackback();
			    foreach($master->getTrackBackList() as $url){
				    $tb->sendTrackBack(trim($url));
			    }
		    }
			return View::SUCCESS;
		}else{
			$request->setAttribute('editform', $editform);
			return View::INPUT;
		}
    }



    public function getRequestMethods()
    {
        
        return Request::POST | Request::GET;
    }

    public function isSecure()
    {
        return true;
    }
}

?>