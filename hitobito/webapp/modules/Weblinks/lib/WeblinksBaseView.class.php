<?php

class WeblinksBaseView extends SmartyView
{
	protected $controller,$request,$user;
	public function initialize($context)
	{
		parent::initialize($context);
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
		
		return TRUE;
	}
	
	public function execute()
	{
	}
	
}
?>