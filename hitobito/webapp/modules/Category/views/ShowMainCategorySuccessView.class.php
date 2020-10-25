<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Category
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: ShowMainCategorySuccessView.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */

class ShowMainCategorySuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('MainCategory.html');

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