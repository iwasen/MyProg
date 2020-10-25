<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Member
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: changeMailStatusView.class.php,v 1.2 2006/02/14 17:12:02 ryu Exp $
 */

class ChangeMailStatusInputView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('changeMailStatusForm.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
        $letter_options = array(1 => HNb::tr('����'), 0 => HNb::tr('���'));
        $this->setAttribute('letter_options', $letter_options);
        $status_options = array(1 => HNb::tr('����'), 3 => HNb::tr('���'));
        $this->setAttribute('status_options', $status_options);
    }
}

class ChangeMailStatusSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('changeMailStatusResult.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
        $obj = $request->getAttribute('obj');
        $letter_options = array(1 => HNb::tr('����'), 0 => HNb::tr('���'));
        $this->setAttribute('letter_option', $letter_options[$obj->getHitobito_letter()]);
        $status_options = array(1 => HNb::tr('����'), 3 => HNb::tr('���'));
        $this->setAttribute('status_option', $status_options[$obj->getStatus()]);
        $this->setAttribute('message', HNb::tr('��������λ�������ޤ�����'));
    }
}

class ChangeMailStatusErrorView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('changeMailStatusResult.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
        $this->setAttribute('message', HNb::tr('�������Ԥ��ޤ��������֤򤢤��ƺ����ѹ����Ƥ���������'));
    }
}
?>
