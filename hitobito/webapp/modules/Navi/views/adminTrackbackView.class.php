<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: adminTrackbackView.class.php,v 1.2 2006/01/11 13:53:13 ryu Exp $
 */

class AdminTrackbackInputView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('TrackbackList.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
    }
}

class AdminTrackbackSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('result.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
        $this->setAttribute('return_url', 'index.php?module=Navi&amp;action=adminTrackback&amp;navipage_id='.hitobito::getNaviPageInfo()->getId());
    }
}

class AdminTrackbackErrorView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('error.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
        $this->setAttribute('return_url', 'index.php?module=Navi&amp;action=adminTrackback&amp;navipage_id='.hitobito::getNaviPageInfo()->getId());
    }
}
?>