<?php
/**
 * Blogコメント投稿
 *
 * 
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: modifyCommentAction.class.php,v 1.5 2009/04/23 02:32:03 aizawa Exp $
 */
require_once MO_WEBAPP_DIR.'/modules/Blog/forms/commentForm.class.php';
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseAction.class.php';
require_once MO_WEBAPP_DIR .'/modules/Navi/lib/notifyMail.class.php';
class ModifyCommentAction extends BlogBaseAction
{
    public function execute()
    {
//    	return View::INPUT;
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
        if(!$request->hasAttribute('obj')){ ///< ViewStoryから取得
        	return VIEW::NONE;
        }
        $story = $request->getAttribute('obj');
        if(!$story->canPoxxxomment()){
        	return VIEW::NONE;
        }

		if($user->hasAttribute('result_message', 'Blog')){
			$request->setAttribute('result_message', $user->getAttribute('result_message', 'Blog'));
			$user->removeAttribute('result_message', 'Blog');
		}
        
        $editform = new BlogCommentForm();

		$manager = new BlogCommentManager();
		
		$storyid = $request->getParameter('blog_story_id', $request->getAttribute('blog_story_id', 0));
		if($storyid == 0){
			return View::NONE;
		}
		$comment_no = $request->getParameter('blog_comment_no', $request->getAttribute('blog_comment_no', 0));
		$id = array('blo_article_id' => $storyid, 'blo_comment_no' => $comment_no);
		$master = $manager->get($id);
		if($master->isNew()){
			$master->setAttribute('blo_article_id', $storyid);
		}
		
		if($editform->init($master, $request) === HNB_ACTIONFORM_POST_SUCCESS && !$request->getParameter('blog_key_change')){
			// update
			$editform->update($master, $request);
			$manager->insert($master);
            
            $notifyValues = array(
            'POSTER' => $master->getAttribute('blo_user_name'),
            'BODY' => $master->getAttribute('blo_comment'),
			'ENTRY_TITLE' => $story->getTitle(),
			'ENTRY_URL' => hitobito::getUrl() . "index.php?module=Blog&action=ViewStory&blog_story_id=$storyid"
            );
            $notifer = new NaviNotifyMail();
            $notifer->notify(hitobito::getNaviPageInfo(), 'comment', $notifyValues);
            
			// 成功したらメッセージを積んでリダイレクト
			$this->getContext()->getUser()->setAttribute('result_message', HNb::tr('コメント投稿ありがとうございました。'), 'Blog');
			$this->getContext()->getController()->redirect('index.php?module=Blog&action=ViewStory&blog_story_id='.$storyid);
			return View::NONE;
		}else{
			$request->setAttribute('editform', $editform);
			return View::INPUT;
		}
    }
    
    public function isSecure()
    {
        return FALSE;
    }
}

?>