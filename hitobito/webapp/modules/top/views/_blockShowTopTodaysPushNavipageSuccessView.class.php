<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowTopTodaysPushNavipageSuccessView.class.php,v 1.1 2007/01/31 08:57:37 shimizu Exp $
 */
class _blockShowTopTodaysPushNavipageSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('_blockTopTodaysPush.html');
        // assign
        $attributeNames = $request->getAttributeNames();

        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
    }
}
?>