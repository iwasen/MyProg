<?php

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');
class RegistMemberBaseView extends WaiwaiBaseView
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

		// 閲覧共通設定
		$this->setBowseCommon();

		return TRUE;
	}

	public function execute()
	{
	}

}

class RegistMemberInputView extends RegistMemberBaseView
{
	public function execute()
	{
		parent::execute();
		$this->setTemplate('RegistMember.html');
	}
}

class RegistMemberSuccessView extends RegistMemberBaseView
{
	public function execute()
	{
		parent::execute();
		$this->setTemplate('RegistMemberSuccess.html');
	}
}

class RegistMemberErrorView extends RegistMemberBaseView
{
	public function execute()
	{
		parent::execute();

		$this->setTemplate('error.html');
	}
}

?>