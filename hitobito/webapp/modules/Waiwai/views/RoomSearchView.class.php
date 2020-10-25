<?php
/**
 *
 *
 * 
 * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: RoomSearchView.class.php,v 1.3 2006/06/07 07:32:07 aizawa Exp $
 */

class RoomSearchSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('SearchResult.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
//        $this->useMainTemplate();
    }
}
?>