<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: modifyNgwordView.class.php,v 1.1 2006/01/09 14:31:36 ryu Exp $
 */

class ModifyNgwordInputView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('ngwordForm.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
        
        // type option
        $type_options = array(1 => HNb::tr('URL'), 2 => HNb::tr('ワード'));
        $this->setAttribute('type_options', $type_options);
        // status option
        $status_options = array(1 => HNb::tr('有効'), 9 => HNb::tr('無効'));
        $this->setAttribute('status_options', $status_options);
    }
}

class ModifyNgwordSuccessView extends SmartyView
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
        $this->setAttribute('return_url', 'index.php?module=Blog&amp;action=adminNgword&amp;navipage_id='.hitobito::getNaviPageInfo()->getId());
        
        $this->useMainTemplate();
    }
}

class ModifyNgwordErrorView extends SmartyView
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
        $this->setAttribute('return_url', 'index.php?module=Blog&amp;action=adminNgword&amp;navipage_id='.hitobito::getNaviPageInfo()->getId());
    }
}
?>