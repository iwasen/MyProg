<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: IndexView.class.php,v 1.2 2006/01/15 21:46:15 ryu Exp $
 */
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseView.class.php';

class IndexInputView extends BlogBaseView
{
    public function execute ()
    {
        $this->setTemplate('Index.html');
    }
}

class IndexSuccessView extends BlogBaseView
{
    public function execute ()
    {
        $this->setTemplate('Index.html');
        $request = $this->getContext()->getRequest();
        $channelId = intval($request->getParameter('category_id'));
        if($channelId){
        	$this->setAttribute('listUrlAddParameter', '&amp;category_id='.$channelId);
        }elseif(hitobito::getNaviPageInfo()->getId() > 0){
        	$this->setAttribute('listUrlAddParameter', '&amp;navipage_id='.hitobito::getNaviPageInfo()->getId());
        }else{
        	$this->setAttribute('listUrlAddParameter', '');
        }
    }
}

class IndexErrorView extends BlogBaseView
{
    public function execute ()
    {
        $this->setTemplate('Index.html');
    }
}
?>