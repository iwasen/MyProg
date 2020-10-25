<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Weblinks
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: modifyLinkView.class.php,v 1.3 2006/06/15 16:08:06 ryu Exp $
 */
require_once MO_WEBAPP_DIR . '/modules/Weblinks/lib/WeblinksBaseView.class.php';

class modifyLinkInputView extends WeblinksBaseView
{
    public function execute ()
    {
        $this->setTemplate('itemform.html');


        
        // category 
        $categoryManager = new WeblinksCategoryManager();
//        $categoris = $categoryManager->getCategories();
		$categories = $categoryManager->getCategoriesByModifyNavipageId(hitobito::getNaviPageInfo()->getId());
        $categories_options = array();
        foreach($categories as $obj){
        	$categories_options[$obj->getId()] = $obj->getCategoryName();
        }
        $this->setAttribute('categories_options', $categories_options);

		$category_id = intval($this->request->getParameter('weblinks_return_category_id'));
		$url = '';
		if($category_id > 0){
			$url .= '&amp;weblinks_category_id='.$category_id;
		}
		$this->setAttribute('return_extra_params', $url);		
    }
}

class modifyLinkSuccessView extends WeblinksBaseView
{
    public function execute ()
    {
        $this->setTemplate('result.html');
    }
}

class modifyLinkErrorView extends WeblinksBaseView
{
    public function execute ()
    {
        $this->setTemplate('error.html');
    }
}
?>