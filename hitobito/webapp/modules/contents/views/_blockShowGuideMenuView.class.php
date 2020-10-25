<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package contents
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowGuideMenuView.class.php,v 1.1 2006/03/11 11:53:23 ryu Exp $
 */

class _blockShowGuideMenuSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('_blockLmenuGuide.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
    }
}
?>