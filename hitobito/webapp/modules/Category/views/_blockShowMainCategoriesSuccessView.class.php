<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Category
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowMainCategoriesSuccessView.class.php,v 1.2 2006/01/19 06:44:19 ryu Exp $
 */

class _blockShowMainCategoriesSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate($request->getAttribute('template'));
//        $this->setTemplate('_blockMainCategories.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
    }
}

?>