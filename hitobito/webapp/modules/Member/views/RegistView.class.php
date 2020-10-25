<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Member
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: RegistView.class.php,v 1.2 2006/01/22 13:12:17 ryu Exp $
 */

class RegistInputView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('regist_input.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
		// radio 
		$hitobito_letter_options = array('1' => '����', '0'=> '���ɲ��');
		$this->setAttribute('hitobito_letter_options', $hitobito_letter_options);
		$status_options = array('1'=>'ͭ��', '3' => '������');
		$this->setAttribute('status_options', $status_options);
		
        $this->useMainTemplate();
    }
}

class RegistAlertView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('regist_confirm.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
		
        $this->useMainTemplate();
    }
}
class RegistSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('save_new_account.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        

		$this->setAttribute('message', '����Ͽĺ�����᡼�륢�ɥ쥹���˳�ǧ�᡼��������������ޤ�����');
		
        $this->useMainTemplate();
    }
}
class RegistErrorView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('save_new_account.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
		$this->setAttribute('message', '����Ͽ�˼��Ԥ��ޤ��������֤򤢤��ƺ�����Ͽ���Ƥ�������');		
        $this->useMainTemplate();
    }
}
?>