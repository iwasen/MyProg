<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package contents
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowAdminBlogPRView.class.php,v 1.1 2006/02/04 09:51:12 ryu Exp $
 */

class _blockShowAdminBlogPRSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('_block_admin_blog_pr.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
    }
}

?>