<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: adminAssistView.class.php,v 1.1 2005/12/15 00:20:18 ryu Exp $
 */

class AdminAssistInputView extends SmartyView
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

        $this->setTemplate('adminAssist.html');

    }
}


class AdminAssistSuccessView extends SmartyView
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

class AdminAssistErrorView extends SmartyView
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