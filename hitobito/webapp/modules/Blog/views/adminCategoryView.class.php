<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: adminCategoryView.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseView.class.php';

class AdminCategoryInputView extends BlogBaseView
{
    public function execute ()
    {
        $this->setTemplate('deleteConfirm.html');
    }
}

class AdminCategorySuccessView extends BlogBaseView
{
    public function execute ()
    {
        $this->setTemplate('result.html');
    }
}

class AdminCategoryErrorView extends BlogBaseView
{
    public function execute ()
    {
        $this->setTemplate('error.html');
    }
}
?>