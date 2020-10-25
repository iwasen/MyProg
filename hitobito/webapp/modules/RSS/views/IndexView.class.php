<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package RSS
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: IndexView.class.php,v 1.1 2006/01/04 10:03:10 ryu Exp $
 */

class IndexSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('Index.html');

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