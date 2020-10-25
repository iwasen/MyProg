<?php
/**
 *
 *
 * 
 * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: BlogSearchView.class.php,v 1.1 2006/01/04 02:52:43 ryu Exp $
 */

class BlogSearchSuccessView extends SmartyView
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