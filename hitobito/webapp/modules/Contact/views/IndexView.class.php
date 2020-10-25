<?php

class IndexBaseView extends SmartyView
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
        $this->editform = $this->request->getAttribute('editform');
        $this->useMainTemplate();
        
        // TODO �����ȥ�������å�
        $formId = intval($this->request->getParameter('contact_form_id'));
        switch($formId){
            case 1:
                $form_title = array(
                    'img' => 'image/navi_request_ttl.gif',
                    'text' => '�Ż�/���ΰ���'
                    );
                break;
            case 2:
                $form_title = array(
                    'img' => 'image/navi_inquiry_ttl.gif',
                    'text' => '���䤤��碌'
                    );
                break;
            case 3:
                $form_title = array(
                    'img' => 'image/navi_ask_navi_ttl.gif',
                    'text' => '�ʥӤؼ��䤹��'
                    );
                break;
        }
		$this->setAttribute('form_title', $form_title);
		return TRUE;
	}
	
	public function execute()
	{
	}
	
}

class IndexInputView extends IndexBaseView
{
	public function execute()
	{
		$formId = $this->request->getAttribute('formId');
		$this->setTemplate('form_'.$formId.'_input.html');
	}
}
class IndexAlertView extends IndexBaseView
{
	public function execute()
	{
		$formId = $this->request->getAttribute('formId');
		$this->setTemplate('form_'.$formId.'_confirm.html');
	}
}

class IndexSuccessView extends IndexBaseView
{
	public function execute()
	{
        $this->setAttribute('message', '�������ޤ�����');
		$this->setTemplate('form_result.html');
	}
}

class IndexErrorView extends IndexBaseView
{
	public function execute()
	{
        $this->setAttribute('message', '��������');
		$this->setTemplate('form_result.html');
	}
}
?>