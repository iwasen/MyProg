<?php
/**
 *
 *
 * 
 * 
 * @package Contact
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: AdminListView.class.php,v 1.3 2005/12/25 09:07:18 ryu Exp $
 */

class AdminListInputView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('InquiryList.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
        
        $inquiry_type_options = array(
        	1 => HNb::tr('�����ˤĤ���'),
        	2 => HNb::tr('�磻�磻��ļ��ˤĤ���'),
        	3 => HNb::tr('�Ż�����'),
        	4 => HNb::tr('������'),
        	5 => HNb::tr('����'),
        	9 => HNb::tr('����¾')
        );
        $this->setAttribute('inquiry_type_options', $inquiry_type_options);
        
        // �ʥ�Х��
        $itemlist = $request->getAttribute('itemlist');
        $num = $request->getAttribute('end_num');
        $new_itemlist = array(); 
        foreach($itemlist as $item){
        	$new_itemlist[$num] = $item;
        	$num--;
        }
        $this->setAttribute('itemlist', $new_itemlist);
        
    }
}
class AdminListErrorView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('error.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
        
        
    }
}
class AdminListSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('result.html');

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