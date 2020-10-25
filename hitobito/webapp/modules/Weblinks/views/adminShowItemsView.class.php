<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Weblinks
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: adminShowItemsView.class.php,v 1.2 2006/06/15 16:08:06 ryu Exp $
 */


class AdminShowItemsSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('adminShowItems.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        if($request->hasParameter('weblinks_category_id')){
            $weblinks_category_id = intval($request->getParameter('weblinks_category_id'));
            $this->setAttribute('weblinks_category_id', $weblinks_category_id);
        }
        
        $this->useMainTemplate();
        
    }
}

?>