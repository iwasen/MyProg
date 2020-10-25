<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: adminStoryView.class.php,v 1.1 2005/11/28 08:31:18 ryu Exp $
 */
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseView.class.php';

class AdminStoryInputView extends BlogBaseView
{
    public function execute ()
    {
        $this->setTemplate('deleteConfirm.html');
    }
}

class AdminStorySuccessView extends BlogBaseView
{
    public function execute ()
    {
        $this->setTemplate('result.html');
    }
}

class AdminStoryErrorView extends BlogBaseView
{
    public function execute ()
    {
        $this->setTemplate('error.html');
    }
}
?>