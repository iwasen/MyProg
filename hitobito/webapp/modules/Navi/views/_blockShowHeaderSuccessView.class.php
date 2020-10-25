<?php
/**
 *  ナビページヘッダの表示
 *
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowHeaderSuccessView.class.php,v 1.10 2006/02/16 11:16:25 ryu Exp $
 */

class _blockShowHeaderSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('_blockNavipageHeader.html');



        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
		
    }
}

?>