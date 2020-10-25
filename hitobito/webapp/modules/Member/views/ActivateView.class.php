<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Member
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: ActivateView.class.php,v 1.2 2005/12/02 07:21:10 ryu Exp $
 */

class ActivateInputView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('activate.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
    }
}

class ActivateSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();


        $this->setTemplate('activate.html');
		$this->setAttribute('message', 'メンバー登録ありがとうございます。');
        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
    }
}

class ActivateErrorView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('activate.html');
		$this->setAttribute('message', '仮登録されていません');

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