<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package NaviSelect
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowNewNaviSelectListView.class.php,v 1.1 2005/12/07 22:50:12 ryu Exp $
 */


class _blockShowNewNaviSelectListSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('_blockNaviSelectList.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
    }
}

?>