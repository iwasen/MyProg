<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package RSS
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: StoriesView.class.php,v 1.1 2006/01/04 10:03:10 ryu Exp $
 */

class StoriesSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('rss20.xml');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        $itemlist = $request->getAttribute('itemlist');
        $viewItemList = array();
        foreach($itemlist as $item){
        	$vars = array();
        	$vars['title'] = $item->getTitle();
        	$vars['link'] = hitobito::getUrl().'index.php?module=Blog&amp;action=ViewStory&amp;blog_story_id='.$item->getId();
        	$vars['description'] = $item->getBody();
        	$vars['pubdate'] = $item->getAttribute('bla_date');
        	$viewItemList[] = $vars;
        }
        $this->setAttribute('itemlist', $viewItemList);
    }
}

?>