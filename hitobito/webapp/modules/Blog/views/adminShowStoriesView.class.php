<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: adminShowStoriesView.class.php,v 1.1 2006/05/08 19:32:23 ryu Exp $
 */

class AdminShowStoriesSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('adminShowStories.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        if($request->hasParameter('blog_category_id')){
            $blog_category_id = intval($request->getParameter('blog_category_id'));
            $this->setAttribute('blog_category_id', $blog_category_id);
        }
        $this->useMainTemplate();
        
    }
    
}

?>