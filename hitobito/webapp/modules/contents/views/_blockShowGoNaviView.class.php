<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package contents
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowGoNaviView.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */

class _blockShowGoNaviSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('_block_go_navi.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
    }
}

?>