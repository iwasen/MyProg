<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package hitobito2
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowSpecialStoriesView.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */

class _blockShowSpecialStoriesSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('_block_special_stories.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
    }
}

?>