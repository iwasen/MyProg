<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: modifyCommentView.class.php,v 1.2 2006/01/11 11:20:41 ryu Exp $
 */

class ModifyCommentInputView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('CommentForm.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
        $status_options = array(1 => HNb::tr('¸ø³«'),0 => HNb::tr('Èó¸ø³«'));
        $this->setAttribute('status_options', $status_options);
    }
}

class ModifyCommentSuccessView extends SmartyView
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
        $this->setAttribute('return_url', 'index.php?module=Navi&amp;action=adminComment&amp;navipage_id='.hitobito::getNaviPageInfo()->getId());
        
    }
}

class ModifyCommentErrorView extends SmartyView
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
        $this->setAttribute('return_url', 'index.php?module=Navi&amp;action=adminComment&amp;navipage_id='.hitobito::getNaviPageInfo()->getId());
    }
}
?>