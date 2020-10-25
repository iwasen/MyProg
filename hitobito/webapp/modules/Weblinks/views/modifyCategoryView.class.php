<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Weblinks
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: modifyCategoryView.class.php,v 1.2 2006/06/12 11:30:21 ryu Exp $
 */
require_once MO_WEBAPP_DIR . '/modules/Weblinks/lib/WeblinksBaseView.class.php';

class ModifyCategoryInputView extends WeblinksBaseView
{
    public function execute ()
    {
        $this->setTemplate('category_form.html');
    }
}

class ModifyCategorySuccessView extends WeblinksBaseView
{
    public function execute ()
    {
        $this->setTemplate('result.html');
        $return_url = 'index.php?module=Weblinks&amp;action=adminShowCategories&amp;navipage_id='.hitobito::getNaviPageInfo()->getId();
        $this->setAttribute('return_url', $return_url);
    }
}

class ModifyCategoryErrorView extends WeblinksBaseView
{
    public function execute ()
    {
        $this->setTemplate('category_form.html');
    }
}
?>