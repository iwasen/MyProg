<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: receiveTrackbackAction.class.php,v 1.6 2009/04/23 02:32:03 aizawa Exp $
 */
//require_once MO_WEBAPP_DIR.'/modules/Blog/forms/commentForm.class.php';
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseAction.class.php';
require_once MO_WEBAPP_DIR.'/modules/Blog/lib/NgwordValidator.class.php';
require_once MO_WEBAPP_DIR .'/modules/Navi/lib/notifyMail.class.php';
require_once MO_LIB_DIR . '/HNbSpamCheck.class.php';

class ReceiveTrackbackAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        

		$manager = new BlogTrackbackManager();
		
		$storyid = $request->getParameter('story_id', 0);
		if($storyid == 0){
			return View::ERROR;
		}
		$storyManager = new BlogStoryManager();
		$story = $storyManager->get($storyid);
		if(!$story->canTrackback()){
			return View::ERROR;
		}

		// SPAMチェック 2007/05/23 aizawa
		$spamcheck = new HNbSpamCheck;
		if ($spamcheck->checkTrackbackSpam($request)) {
			return View::ERROR;
		}

		$trackback = $manager->create();
		$trackback->setAttribute('blt_date', time());
		$trackback->setAttribute('blt_article_id', $storyid);
		$trackback->setAttribute('blt_title', mb_convert_encoding(strip_tags($request->getParameter('title')), 'EUC-JP', 'UTF-8, EUC-JP, SJIS'));
		$trackback->setAttribute('blt_url', strip_tags($request->getParameter('url')));
		$trackback->setAttribute('blt_blog_name', mb_convert_encoding(strip_tags($request->getParameter('blog_name')), 'EUC-JP', 'UTF-8,EUC-JP, SJIS'));
		$trackback->setAttribute('blt_excerpt', mb_convert_encoding(strip_tags($request->getParameter('excerpt')), 'EUC-JP', 'UTF-8, EUC-JP, SJIS'));
		if(!$this->validateTrackback($trackback)){
			return View::ERROR;
		}
		if($manager->insert($trackback)){
		    $notifyValues = array(
                'TITLE' => $trackback->getAttribute('blt_title'),
                'BLOG_NAME' => $trackback->getAttribute('blt_blog_name'),
                'BLOG_URL' => $trackback->getAttribute('blt_url'),
                'BODY' => $trackback->getAttribute('blt_excerpt'),
				'ENTRY_TITLE' => $story->getTitle(),
				'ENTRY_URL' => hitobito::getUrl() . "index.php?module=Blog&action=ViewStory&blog_story_id=$storyid"
                );
            $notifer = new NaviNotifyMail();
            $notifer->notify(hitobito::getNaviPageInfo(), 'trackback', $notifyValues);

			return View::SUCCESS;
		}else{
			return View::ERROR;
		}
    }

	/**
	 * NGワードチェックと必須項目チェック
	 */    
    private function validateTrackback($trackback)
    {
        // 必須チェック
        $require_params = array('blt_title', 'blt_url', 'blt_blog_name', 'blt_excerpt');
        foreach($require_params as $name)
        {
            $text = trim($trackback->getAttribute($name));
            if(strlen($text) == 0){
                return false;
            }
        }
        //NGワード
    	$NgWordValidator = new BlogNgwordValidator(hitobito::getNaviPageInfo()->getId());
    	if(!$NgWordValidator->validateUrl($trackback->getAttribute('blt_url'))){
    		return FALSE;
    	}
    	$checkAttributeName = array('blt_title', 'blt_blog_name', 'blt_excerpt');
    	foreach($checkAttributeName as $name){
	    	if(!$NgWordValidator->validateText($trackback->getAttribute($name))){
	    		return FALSE;
	    	}
    	}
    	return TRUE;
    }
    
    public function getRequestMethods()
    {
    	return Request::GET | Request::POST;
    }
    
    public function isSecure()
    {
        return FALSE;
    }
}

?>