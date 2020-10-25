<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: NewAssistView.class.php,v 1.1 2005/12/15 00:20:18 ryu Exp $
 */

class NewAssistInputView extends SmartyView
{
    public function execute ()
    {
        $this->controller = $this->getContext()->getController();
        $this->request = $this->getContext()->getRequest();
        $this->user = $this->getContext()->getUser();

        // assign
        $attributeNames = $this->request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $this->request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
        $this->setTemplate('modifyAssist.html');
        
        $navi_status_options = array(1 => HNb::tr('¸ø³«'), 0 => HNb::tr('Èó¸ø³«'));
        $this->setAttribute('navi_status_options', $navi_status_options);
    }
}

class NewAssistSuccessView extends SmartyView
{
    public function execute ()
    {
        $this->controller = $this->getContext()->getController();
        $this->request = $this->getContext()->getRequest();
        $this->user = $this->getContext()->getUser();

        // assign
        $attributeNames = $this->request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $this->request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
        $this->setTemplate('result.html');
    }
}

class NewAssistErrorView extends SmartyView
{
    public function execute ()
    {
        $this->controller = $this->getContext()->getController();
        $this->request = $this->getContext()->getRequest();
        $this->user = $this->getContext()->getUser();

        // assign
        $attributeNames = $this->request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $this->request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
        $this->setTemplate('error.html');
    }
}
?>