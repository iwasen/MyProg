<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: notify_configView.class.php,v 1.2 2006/05/30 17:35:01 ryu Exp $
 */

class Notify_configInputView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('notify_config.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $notify_options = array(0 => HNb::tr('利用しない'), 1=>HNb::tr('利用する'));
        $this->setAttribute('notify_options', $notify_options);
        //$this->usePopupTemplate();
        $this->useMainTemplate();
    }
}

class Notify_configSuccessView extends SmartyView
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
        
        $notify_target = $request->getAttribute('notify_target');
        $url = 'index.php?module=Navi&action=notify_config&navipage_id='.hitobito::getNaviPageInfo()->getId().'&navi_notify_target='.$notify_target;
        $this->setAttribute('return_url', $url);
        $this->useMainTemplate();
    }
}

class Notify_configErrorView extends SmartyView
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
    }
}
?>