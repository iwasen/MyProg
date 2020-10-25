<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package contents
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: ShowPageView.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */

class ShowPageSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

		$page = $request->getAttribute('content_file');
        $this->setTemplate($page);

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
    }
}
?>