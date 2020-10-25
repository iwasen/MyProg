<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package contents
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowContentView.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */

class _blockShowContentSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

		$page = $request->getAttribute('content_block_page');
        $this->setTemplate($page);

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
    }
}
?>