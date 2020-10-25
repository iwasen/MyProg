<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: modifyCategoryView.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseView.class.php';

class ModifyCategoryInputView extends BlogBaseView
{
    public function execute ()
    {
        $this->setTemplate('category_form.html');
    }
}

class ModifyCategorySuccessView extends BlogBaseView
{
    public function execute ()
    {
        $this->setTemplate('result.html');
    }
}

class ModifyCategoryErrorView extends BlogBaseView
{
    public function execute ()
    {
        $this->setTemplate('category_form.html');
    }
}
?>