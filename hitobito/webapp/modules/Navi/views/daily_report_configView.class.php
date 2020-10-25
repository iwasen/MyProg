<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: daily_report_configView.class.php,v 1.3 2006/05/06 10:53:59 ryu Exp $
 */

class Daily_report_configInputView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('daily_report_config.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $status_options = array(0 => HNb::tr('利用しない'), 1=>HNb::tr('利用する'));
        $this->setAttribute('status_options', $status_options);
        
        $this->useMainTemplate();
    }
}

class Daily_report_configSuccessView extends SmartyView
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
        $this->setAttribute('return_url', 'index.php?module=Navi&amp;action=ShowNaviEdit&amp;navipage_id='.hitobito::getNaviPageInfo()->getId());   
        $this->useMainTemplate();
    }
}

class Daily_report_configErrorView extends SmartyView
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
        
        $this->setAttribute('return_url', 'index.php?module=Navi&amp;action=ShowNaviEdit&amp;navipage_id='.hitobito::getNaviPageInfo()->getId());   
        $this->useMainTemplate();
    }
}
?>