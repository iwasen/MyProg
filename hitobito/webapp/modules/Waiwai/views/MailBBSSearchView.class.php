<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Waiwai
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: MailBBSSearchView.class.php,v 1.3 2006/06/07 07:32:07 aizawa Exp $
 */

class MailBBSSearchSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('MailBBSSearchResult.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
    }
}

?>