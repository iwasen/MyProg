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

require_once MO_WEBAPP_DIR . '/modules/Weblinks/lib/WeblinksBaseView.class.php';
class modifyCategoriesInputView extends WeblinksBaseView
{
    public function execute ()
    {

        $this->setTemplate('adminCategories.html');

    }
}


class modifyCategoriesSuccessView extends WeblinksBaseView
{
    public function execute ()
    {
        $url = 'index.php?module=Weblinks&amp;action=adminShowCategories&amp;navipage_id='.hitobito::getNaviPageInfo()->getId();
        $this->setAttribute('return_url', $url);
        $this->setTemplate('result.html');

    }
}

class modifyCategoriesErrorView extends WeblinksBaseView
{
    public function execute ()
    {
        $url = 'index.php?module=Weblinks&amp;action=adminShowCategories&amp;navipage_id='.hitobito::getNaviPageInfo()->getId();
        $this->setAttribute('return_url', $url);

        $this->setTemplate('error.html');

    }
}
?>