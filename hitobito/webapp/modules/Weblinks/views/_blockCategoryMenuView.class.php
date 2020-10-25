<?php
/**
 * 
 * 
 * 
 * 
 * @package Weblinks
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockCategoryMenuView.class.php,v 1.1 2005/12/13 18:31:26 ryu Exp $
 */

class _blockCategoryMenuSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('_blockCategoryMenu.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
    }
}

?>