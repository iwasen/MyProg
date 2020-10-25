<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: modifyAssistView.class.php,v 1.2 2006/01/09 04:17:25 ryu Exp $
 */

class modifyAssistInputView extends SmartyView
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
        
        $this->setAttribute('hide_email_input', TRUE);
        
    }
}

class modifyAssistSuccessView extends SmartyView
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

class modifyAssistErrorView extends SmartyView
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