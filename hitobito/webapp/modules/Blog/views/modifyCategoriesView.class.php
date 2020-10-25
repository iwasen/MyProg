<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Weblinks
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: modifyCategoriesView.class.php,v 1.1 2006/05/08 19:32:23 ryu Exp $
 */

require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseView.class.php';
class modifyCategoriesInputView extends BlogBaseView
{
    public function execute ()
    {

        $this->setTemplate('modifyCategories.html');

    }
}


class modifyCategoriesSuccessView extends BlogBaseView
{
    public function execute ()
    {
        $url = 'index.php?module=Blog&amp;action=adminShowCategories&amp;navipage_id='.hitobito::getNaviPageInfo()->getId();
        $this->setAttribute('return_url', $url);
        $this->setTemplate('result.html');

    }
}

class modifyCategoriesErrorView extends BlogBaseView
{
    public function execute ()
    {
        $url = 'index.php?module=Blog&amp;action=adminShowCategories&amp;navipage_id='.hitobito::getNaviPageInfo()->getId();
        $this->setAttribute('return_url', $url);

        $this->setTemplate('error.html');

    }
}
?>