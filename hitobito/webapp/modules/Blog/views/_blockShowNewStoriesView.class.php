<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowNewStoriesView.class.php,v 1.4 2006/01/14 14:25:52 ryu Exp $
 */

class _blockShowNewStoriesSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('_blockNewStories.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        // rssUrl
        $navipage_id = hitobito::getNaviPageInfo()->getId();
        
        $rssUrl = 'index.php?module=RSS&amp;action=Stories';
        if($navipage_id > 0){
        	$rssUrl .='&amp;navipage_id='.$navipage_id;
        }elseif($request->hasParameter('category_id')){
        	$category_id = intval($request->getParameter('category_id'));
        	$rssUrl .='&amp;category_id='.$category_id;
        }
        $this->setAttribute('rssUrl', $rssUrl);
        
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

?>