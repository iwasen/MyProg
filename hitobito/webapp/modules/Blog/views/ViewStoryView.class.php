<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: ViewStoryView.class.php,v 1.5 2006/06/12 07:15:20 ryu Exp $
 */
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseView.class.php';

class ViewStoryInputView extends BlogBaseView
{
    public function execute ()
    {
        $this->setTemplate('story.html');
    }
}

class ViewStorySuccessView extends BlogBaseView
{
    public function execute ()
    {
    	$obj = $this->request->getAttribute('obj');
    	$obj->updatePageView();
        $this->setTemplate('story.html');
        
        // 記事ナビゲーション
        $manager = new BlogStoryManager();
        $nextId = $manager->getNextStoryId($obj, $this->request);
        $this->setAttribute('nextId', $nextId);
        $prevId = $manager->getPrevStoryId($obj, $this->request);
        $this->setAttribute('prevId', $prevId);
        
    }
}

class ViewStoryErrorView extends BlogBaseView
{
    public function execute ()
    {
        $this->setTemplate('error.html');
    }
}
?>