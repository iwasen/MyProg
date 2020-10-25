<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Weblinks
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: IndexView.class.php,v 1.2 2006/02/14 16:57:49 ryu Exp $
 */
require_once MO_WEBAPP_DIR . '/modules/Weblinks/lib/WeblinksBaseView.class.php';

class IndexSuccessView extends WeblinksBaseView
{
    public function execute ()
    {
        $this->setTemplate('Index.html');
        
        // set current category name
        $request = $this->getContext()->getRequest();
        $categoryId = intval($request->getParameter('weblinks_category_id'));
        $categoryManager = new WeblinksCategoryManager();
        $category = $categoryManager->get($categoryId);
        $this->setAttribute('weblinks_current_category_name', $category->getTitle());
    }
}

?>