<?php

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');
class NewRegistStep2SuccessView extends WaiwaiBaseView
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
		$this->controller->redirect('index.php?module=Waiwai&action=Browse&navipage_id='.hitobito::getNaviPageInfo()->getId());
		exit();
	}
	
}

class NewRegistStep2InputView extends WaiwaiBaseView
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
		$this->setTemplate('RegistMemberStep2.html');
	}
	
}


?>